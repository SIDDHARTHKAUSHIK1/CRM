# Master Prompt — WhatsApp Broadcast messages aren't actually sending

Paste this to your coding agent as-is.

---

## First: the console error is not part of this app — ignore it

```
Denying load of chrome-extension://ojplmecpdpgccookcobabopnaifgidhf/assets/couponCollection.js-...
GET chrome-extension://invalid/ net::ERR_FAILED
TypeError: Failed to fetch dynamically imported module: chrome-extension://ojplmecpdpgccookcobabopnaifgidhf/...
```

`chrome-extension://ojplmecpdpgccookcobabopnaifgidhf` is a browser extension's own ID (a coupon/shopping extension, based on the file name `couponCollection.js`) trying to inject a script into the page and failing because of Chrome's own `web_accessible_resources` restrictions. This has nothing to do with the CRM's code — it would show up on any website while that extension is active, not just this admin panel. Do not spend time chasing this in the codebase; there is nothing to fix here. If it's visually distracting while testing, open the page in an Incognito window with extensions disabled instead.

## The real bug: messages never send — root cause found, it's not a code bug

I checked the full send pipeline end to end. The dispatch code itself is correct:

- `CampaignController::startCampaign()`, `::resume()`, and `::retryFailed()` (`packages/Crm/Admin/src/Http/Controllers/WhatsApp/CampaignController.php`, lines ~286, ~469, ~541) all correctly call `SendWhatsappCampaignMessageJob::dispatch($campaign->id, $recipient->id)->delay(...)` for every pending recipient.
- That job (`packages/Crm/WhatsApp/src/Jobs/SendWhatsappCampaignMessageJob.php`) correctly calls the gateway and logs its own progress (`Log::info`/`Log::warning`/`Log::error` calls throughout `handle()`).

But `.env` has `QUEUE_CONNECTION=database` — with the database driver, calling `::dispatch()` only **inserts a row into the `jobs` table**. It does not run the job. A job only actually executes when a separate, continuously-running `php artisan queue:work` process picks it up and processes it.

I checked `storage/logs/laravel.log` for today (2026-09-03) and there is **not a single `[WhatsApp ...]`-tagged log line** anywhere in it, even though messages were supposedly attempted today — no "Skipping recipient", no gateway send error, no circuit-breaker warning, nothing. Every one of those log lines lives inside the job's `handle()` method, so if the job had run at all — even to fail — something would have been logged. Zero log activity for a whole day of attempts means the jobs are sitting in the `jobs` table, never picked up: **there's no queue worker running.** This is different from (and more fundamental than) a gateway connection problem, an invalid phone number, or a WhatsApp API error — all of those would still produce a log line; a job that's never dequeued produces none, which is exactly what's happening here.

I also found a secondary gap while checking this: `packages/Crm/WhatsApp/src/Console/Commands/ProcessScheduledCampaignsCommand.php` (artisan signature `whatsapp:process-campaigns`) exists as a safety net that re-dispatches any stuck `pending` recipients for campaigns already marked `running` — but it is **not registered anywhere in `routes/console.php`**, which currently only schedules `Schedule::command('inbound-emails:process')->everyFiveMinutes();`. It's not the primary cause (Start/Resume/Retry already dispatch directly), but it means a campaign that gets interrupted mid-send (e.g. the app or PC restarts partway through) has nothing to automatically pick it back up later. Worth fixing alongside the main issue.

(For completeness, I also checked `WHATSAPP_GATEWAY_KEY` in `.env` — it's blank, and `whatsapp-gateway/index.js`'s `validateGatewayKey` middleware explicitly treats a blank `GATEWAY_KEY` as "no auth required in dev" and lets every request through (`if (!configuredKey) return next();`). The gateway itself has no `.env` setting a key either, so both sides agree on "no key" — this is not currently causing failures. Leave it as-is; it only matters if the gateway is ever exposed beyond `127.0.0.1`.)

## The fix

### 1. Run a persistent queue worker

This has to be a separate, long-lived process — it is not something `php artisan serve` or the browser starts for you. From the project root, in its own terminal window that you leave open:

```
php artisan queue:work database --queue=default --tries=1 --timeout=90 -v
```

(`--tries=1` matches the job's own `public $tries = 1;`, so a failed send is recorded once and not silently retried by the queue on top of the job's own circuit-breaker logic. `-v` prints each processed job so you can watch messages go out live.)

For convenience, add a small helper script at the project root so this is a double-click instead of a remembered command — `run-queue-worker.bat`:

```bat
@echo off
cd /d "%~dp0"
php artisan queue:work database --queue=default --tries=1 --timeout=90 -v
pause
```

Important: `queue:work` boots the app once and keeps running with that in-memory copy — if you edit any PHP file (including anything from the other WhatsApp prompts), **stop this process (Ctrl+C) and restart it** so it picks up the change. This trips people up constantly: "I fixed the bug but it's still broken" is very often just a queue worker still running the old code.

### 2. Register the safety-net command on the scheduler

In `routes/console.php`, add alongside the existing schedule line:

```php
Schedule::command('inbound-emails:process')->everyFiveMinutes();
Schedule::command('whatsapp:process-campaigns')->everyMinute();
```

Laravel's scheduler itself needs an OS-level trigger to fire even once — on Windows the simplest local-dev option (no Task Scheduler entry needed) is to leave a second terminal open running:

```
php artisan schedule:work
```

(This is a foreground command built into Laravel — it loops internally and calls `schedule:run` every minute for you. If you'd rather use Windows Task Scheduler instead so it runs even when no terminal is open, create a task that runs `php artisan schedule:run` from the project root every minute — but `schedule:work` is simpler to verify while you're actively testing.)

### 3. Clear up any stale process from before the folder rename

The project folder was renamed from `laravel-crm-2.2.5` to `CRM` partway through development. If you ever started `php artisan queue:work` or `php artisan serve` from a terminal opened against the old `laravel-crm-2.2.5` path and never closed that terminal, close it now — a long-running PHP process can keep old file handles open after a rename on Windows and end up running stale/mismatched code. Start both the queue worker and the schedule runner fresh from a terminal `cd`'d into the current `CRM` folder.

## Verify

- With the WhatsApp Gateway running and linked (QR already scanned — confirmed working from the earlier fix), start `php artisan queue:work database --queue=default --tries=1 --timeout=90 -v` in one terminal and `php artisan schedule:work` in another; leave both open.
- From the admin panel, create a small test campaign (1-2 numbers you personally control) and click Start.
- Watch the `queue:work` terminal — you should see the job being picked up and processed within moments (governed by `throttle_seconds`), and `storage/logs/laravel.log` should now show fresh `[WhatsApp Job]`/`[WhatsApp Campaign]` lines for today's date.
- Confirm the WhatsApp message actually arrives on the test number's phone.
- Check the recipient row's status flips to `sent` (or `failed` with a real error message from the gateway, if something else is wrong) on the campaign's Manage page — either outcome now proves the job is executing; only a stuck `pending` status with no log line at all would mean the worker still isn't running.
- Stop and restart the `queue:work` process once to confirm you remember to do this after any future code change — it won't pick up edits on its own while running.
