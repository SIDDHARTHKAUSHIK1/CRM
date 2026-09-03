# Master Prompt — WhatsApp Bulk Broadcast Module for laravel-crm-2.2.5

Paste everything below to your coding agent as-is.

---

## Context

I have a Laravel 12 CRM at the repo root, built as modular packages under `packages/Crm/*` (Konekt Concord + Prettus repositories). Two existing features you must study and pattern-match before writing anything:

- **Quotes**: `packages/Crm/Quote` (Contracts/Models/Migrations/Repositories/Providers) + `packages/Crm/Admin/src/Http/Controllers/Quote/QuoteController.php` + `packages/Crm/Admin/src/Routes/Admin/quote-routes.php` + `packages/Crm/Admin/src/Resources/views/quotes/*.blade.php`.
- **Mail/Email**: `packages/Crm/Email` (same layered structure) + `packages/Crm/Admin/src/Http/Controllers/Mail/EmailController.php` + `packages/Crm/Admin/src/Routes/Admin/mail-routes.php` + `packages/Crm/Admin/src/Resources/views/mail/*.blade.php`.
- Also look at `packages/Crm/Marketing` (`Campaign`/`Event` models, `CampaignCommand.php`, `CampaignMail.php`) — it's the closest existing precedent for "send the same content to a list of people."

Sidebar entries live in `packages/Crm/Admin/src/Config/menu.php` (flat array of `key`/`name`/`route`/`sort`/`icon-class`). Permissions live in `packages/Crm/Admin/src/Config/acl.php`. Frontend is Blade + Alpine.js (no Vue/React SPA) — match that, don't introduce a new frontend framework. `maatwebsite/excel` is already a composer dependency — use it for XLS/XLSX parsing rather than adding a new library.

## What to build

A new admin section, **"WhatsApp Broadcast"**, that lets an admin:

1. Upload a file of phone numbers — accept `.csv`, `.xlsx`, `.xls`, and plain `.txt` (one number per line, or comma/newline separated).
2. Upload one brochure file to send — image (`jpg/png`), `pdf`, or short video (`mp4`), with a sensible size cap (start at 16 MB, WhatsApp's own media cap).
3. Optionally write a caption/message to go with the brochure.
4. Click **Start**, and have the app send the brochure to every parsed number **one at a time, automatically, in the background**, with live progress (sent / failed / pending counts) visible on the page without a full reload.
5. Pause, resume, and cancel a running broadcast, and retry only the failed numbers afterward.

## ⚠️ Read this before writing send logic

You (the user) have chosen **unofficial WhatsApp Web automation** (a `whatsapp-web.js`/Baileys-style session, not the official Meta Cloud API or Twilio) for sending. Flag these constraints explicitly back to whoever runs this, and build the safeguards below — don't skip them because "it's just a demo":

- This uses a real personal/business WhatsApp account's own web session. WhatsApp actively detects and bans numbers that send bulk, unsolicited, automated messages — this risk is real and common, not theoretical, and scales with list size and speed. There is no official appeal path for a banned number used this way.
- Sending to people who never gave consent to receive marketing/business messages is a WhatsApp ToS violation regardless of tooling, and in India additionally risks TRAI/DND commercial-communication rules. **Do not build this to send to arbitrary purchased/scraped number lists.** Build in: a required "I confirm these contacts consented to receive messages from us" checkbox before Start is enabled, and make it easy to exclude/opt-out a number permanently (a `do_not_contact` flag checked before every send).
- Build real throttling, not just a queue: a configurable delay between messages (default 15–30 seconds, admin-adjustable, never allow "0"), a configurable max-messages-per-day cap, and a hard per-broadcast pause if WhatsApp starts returning errors for consecutive sends (auto-pause after N consecutive failures, don't burn through the whole list into a dead session).
- Keep the WhatsApp session/credentials out of the PHP app and off git: the Node gateway (below) is the only thing that touches the WhatsApp session, and its session store must be `.gitignore`d.

## Architecture

Two pieces, talking over localhost HTTP:

### 1. Node.js WhatsApp gateway (new top-level folder `whatsapp-gateway/`)

A small standalone Node service using `whatsapp-web.js` (Puppeteer-backed) or `@whiskeysockets/baileys` (your call — Baileys is lighter, no headless Chromium) that:

- On first run, generates a WhatsApp Web login QR code. Expose it as `GET /qr` returning a base64 PNG (or raw PNG bytes), so the Laravel admin UI can display "scan this to link WhatsApp" instead of making the admin read a terminal.
- Persists the authenticated session to disk (`whatsapp-gateway/.session/`, gitignored) so it survives restarts without re-scanning.
- `GET /status` → `{ connected: bool, number: string|null }`.
- `POST /send` → body `{ to: "91XXXXXXXXXX", mediaPath or mediaUrl, caption }`, sends the document/image to that number, returns `{ success, messageId, error }`. Must return promptly per call — no batching inside the gateway; Laravel controls pacing.
- `POST /logout` → unlinks the session.
- Every endpoint requires a shared-secret header (`X-Gateway-Key`), value from an env var, checked with a constant-time comparison. Bind the server to `127.0.0.1` only — never expose it externally.
- Include a `package.json`, a README with setup/run instructions, and a process-manager note (pm2 or a systemd unit) since this needs to stay running independently of `php artisan serve`/queue workers.

### 2. Laravel side — new package `packages/Crm/WhatsApp`

Mirror the `Quote` package's layering exactly:

- `src/Contracts/WhatsappCampaign.php`, `src/Contracts/WhatsappCampaignRecipient.php`
- `src/Models/WhatsappCampaign.php`, `WhatsappCampaignProxy.php`, `WhatsappCampaignRecipient.php`, `WhatsappCampaignRecipientProxy.php`
- `src/Database/Migrations/xxxx_create_whatsapp_campaigns_table.php`, `xxxx_create_whatsapp_campaign_recipients_table.php`
- `src/Repositories/WhatsappCampaignRepository.php`, `WhatsappCampaignRecipientRepository.php`
- `src/Providers/ModuleServiceProvider.php`, `WhatsAppServiceProvider.php` (register the Guzzle client for the gateway, config binding)
- `src/Jobs/SendWhatsappCampaignMessageJob.php` — one job per recipient (see below)
- `composer.json` for the package, wired into the root `composer.json` autoload/repositories the same way `Crm/Quote` is.

**Schema**

`whatsapp_campaigns`: id, name, brochure_path, caption (text, nullable), status (`draft|running|paused|completed|cancelled`), throttle_seconds (int, default 20), daily_limit (int, nullable), total_recipients, sent_count, failed_count, created_by (user id), timestamps.

`whatsapp_campaign_recipients`: id, whatsapp_campaign_id (FK), raw_input (original cell/line value, for debugging bad parses), phone_e164 (normalized), status (`pending|sending|sent|failed|skipped_dnc`), error_message (nullable text), sent_at (nullable), attempts (int default 0), timestamps. Unique index on `(whatsapp_campaign_id, phone_e164)` — dedupe within one upload.

**Phone parsing (this is the fiddly part — get it right)**

- Accept `.csv`/`.xlsx`/`.xls` via `maatwebsite/excel` (a `ToCollection`/`WithHeadingRow`-agnostic import — don't assume a header row exists; if the first row parses as a phone number, treat the whole file as headerless). Accept `.txt` by reading lines and splitting on comma/whitespace/newline too.
- Numbers may appear as: with/without country code, with `+`, with spaces/dashes/parens, as Excel-mangled scientific notation (`9.19E+11` — Excel does this to long digit strings), with a leading `0`, or already E.164. Normalize all to E.164: strip non-digits, if 10 digits assume the CRM's configured default country code (make this an admin setting, default `91` for India since this is an Indian business), if it already starts with a valid country code leave it, reject/flag anything that doesn't end up looking like a plausible E.164 number (log it against the recipient row with a `skipped_invalid`-style error rather than silently dropping it).
- Dedupe across the whole file before insert.
- After parsing, show a **preview screen** before any send starts: total rows found, how many parsed OK, how many were rejected (with the reasons), and how many were deduped — with the option to download the rejected rows as CSV. Don't let Start fire straight off the raw upload.

**Background send flow**

Given design constraint: there's a real queue system already in this app (`config/queue.php` supports `database`/`redis`, defaults to `sync`). Use it properly here — `sync` will not work for this feature (it would block the HTTP request for the entire list). Document in your README that `QUEUE_CONNECTION` must be `database` or `redis` for this feature, and that `php artisan queue:work` (or Supervisor/Horizon) must be running.

- On **Start**: validate the consent checkbox, mark campaign `running`, then dispatch one `SendWhatsappCampaignMessageJob` per pending recipient, each with `->delay(now()->addSeconds($index * $campaign->throttle_seconds))` so they fire one-by-one at the configured spacing rather than all at once (queue workers would otherwise run them in parallel).
- The job: re-check the campaign is still `running` (bail out silently if `paused`/`cancelled` so already-queued jobs don't fire after a pause), re-check the recipient isn't `do_not_contact`, call the gateway's `POST /send` via Guzzle with the brochure file (upload it once to a stable public/local path the gateway can read, don't re-upload per message), update the recipient row (`sent`/`failed` + `error_message`), increment the campaign's counters, and on **N consecutive failures across the campaign**, flip the campaign to `paused` and record why (surface this prominently in the UI — this is the auto-circuit-breaker from the warnings above).
- **Pause**: sets status to `paused`; already-delayed jobs check status and no-op. **Resume**: re-dispatch jobs for remaining `pending` recipients with fresh delays starting from now. **Cancel**: sets status to `cancelled`, marks remaining `pending` recipients `skipped`. **Retry failed**: re-dispatch jobs only for `status = failed` recipients, resetting `attempts`.
- Respect `daily_limit` if set: don't dispatch jobs beyond that count in the queue at once for jobs whose delay would land after the daily cap — practically simplest: dispatch only the day's allotment, and have a scheduled command (see `packages/Crm/Marketing/src/Console/Commands/CampaignCommand.php` for the existing pattern of an Artisan command driving campaign progress) that tops up the next day's batch. Keep this simple; a naive but correct version is fine.

**Admin UI** (Blade + Alpine, matching existing look)

- New controller `packages/Crm/Admin/src/Http/Controllers/WhatsApp/CampaignController.php`: `index`, `create`, `store` (handles both file uploads + parses + shows preview), `startCampaign`, `pause`, `resume`, `cancel`, `retryFailed`, `status` (JSON endpoint the page polls for live counts), `destroy`.
- New route file `packages/Crm/Admin/src/Routes/Admin/whatsapp-routes.php`, following `quote-routes.php`'s `Route::controller(...)->prefix('whatsapp')->group(...)` shape, all names `admin.whatsapp.*`. Require it from wherever `quote-routes.php`/`mail-routes.php` are required into the route service provider.
- Views under `packages/Crm/Admin/src/Resources/views/whatsapp/`: `index.blade.php` (list of past/running campaigns with status + counts), `create.blade.php` (the two-file-upload form + throttle/daily-limit/consent-checkbox fields), `preview.blade.php` (the parsed-numbers preview described above), `show.blade.php` (a running campaign's live dashboard — sent/failed/pending tally, a recipient table with per-row status, Pause/Resume/Cancel/Retry-failed buttons). Use Alpine + a `setInterval` fetch against the `status` JSON endpoint for live updates — same low-tech polling approach as the rest of this app, no websockets.
- Add a menu entry to `packages/Crm/Admin/src/Config/menu.php` (top-level, `route: admin.whatsapp.index`, pick a free `sort` value and an existing icon class or add a new `icon-whatsapp` class to the admin theme's icon font/CSS) and a permission entry to `packages/Crm/Admin/src/Config/acl.php` mirroring how `quotes`/`mail` are registered there.
- A small settings sub-screen (or a section on the campaign `create` page) to show gateway `/status` (linked number or "not linked") and, if not linked, render the `/qr` image with a "Refresh" button — this is where the admin actually links their WhatsApp account.

**Config/env**

Add to `.env.example` and a new `config/whatsapp.php`:
```
WHATSAPP_GATEWAY_URL=http://127.0.0.1:3001
WHATSAPP_GATEWAY_KEY=
WHATSAPP_DEFAULT_COUNTRY_CODE=91
WHATSAPP_DEFAULT_THROTTLE_SECONDS=20
WHATSAPP_MAX_MEDIA_MB=16
```

## Testing / verification checklist (don't skip)

- Migrations run clean on a fresh DB (`php artisan migrate`).
- Unit tests (Pest, matching the existing `tests/` setup) for the phone-normalization function against the messy formats listed above (scientific notation, missing country code, symbols, duplicates, garbage rows).
- Feature test: upload a small CSV + image through the controller, assert the preview screen's parsed/rejected/deduped counts are correct.
- Feature test: starting a campaign dispatches the right number of queued jobs with increasing delays; pausing prevents a still-delayed job from actually sending (mock the Guzzle call to the gateway — don't hit a real WhatsApp session in tests).
- Manual end-to-end pass: run the Node gateway, scan the QR from the CRM UI, upload a real small test list (numbers you own), start a broadcast with a short throttle, confirm messages arrive one at a time in real WhatsApp, confirm Pause actually stops delivery, confirm Retry-failed only touches failed rows.
- Confirm the gateway's session folder and any `.env` secrets are in `.gitignore`.

## Deliverable

Working code for both the `whatsapp-gateway/` Node service and the `packages/Crm/WhatsApp` + `packages/Crm/Admin` Laravel changes, migrations included, menu/ACL wired in, README section (root `README.md` or a new `whatsapp-gateway/README.md`) explaining: how to install/run the gateway, how to link WhatsApp via the QR screen, that `QUEUE_CONNECTION` must be `database`/`redis` with a worker running, and the ban-risk/consent caveats from above stated plainly for whoever operates this.
