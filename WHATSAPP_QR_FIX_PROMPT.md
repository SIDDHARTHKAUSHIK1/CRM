# Master Prompt — Fix missing QR code on WhatsApp Broadcast "Link WhatsApp" page

Paste this to your coding agent as-is.

---

## The bug

`packages/Crm/Admin/src/Resources/views/whatsapp/gateway.blade.php` (and the same issue exists in `preview.blade.php` and `show.blade.php` in the same folder) was written using **Alpine.js** directives: `x-data="whatsappGateway()"`, `x-init`, `<template x-if="...">`, `:src="qrImage"`, `@click`.

This project does **not** load Alpine.js anywhere — confirm this yourself with `grep -rl "x-data=" packages/Crm/Admin/src/Resources/views` and note it only matches the three WhatsApp files. There is no Alpine `<script>` tag in any layout, and it is not a dependency in `package.json`. Because Alpine never runs, every `<template x-if>` on these pages renders nothing, which is why the QR code box, the connected/disconnected states, and the live campaign progress on `show.blade.php` all appear as an empty box.

## The actual frontend convention this app uses (Vue 3, global component registration)

Look at `packages/Crm/Admin/src/Resources/views/mail/index.blade.php` and `packages/Crm/Admin/src/Resources/assets/js/app.js` before changing anything, and copy this pattern exactly:

- `app.js` does `window.app = createApp({...})` once, globally, for the whole admin theme. You never touch this file or rebuild Vite for a Blade-only change — it's already built and running.
- Each admin page that needs interactivity registers one or more components against that global `app` instance, inline in the page's own Blade file, inside a `<script type="module">` block:
  ```js
  app.component('v-whatsapp-gateway', {
      template: '#v-whatsapp-gateway-template',
      data() {
          return { connected: false, phoneNumber: '', pushName: '', qrImage: '', pollTimer: null };
      },
      mounted() {
          this.fetchStatus();
          this.pollTimer = setInterval(() => {
              this.fetchStatus();
              if (! this.connected) this.fetchQr();
          }, 3000);
      },
      beforeUnmount() {
          clearInterval(this.pollTimer);
      },
      methods: {
          async fetchStatus() { /* fetch '{{ route('admin.whatsapp.gateway.status') }}', assign into this.connected/this.phoneNumber/this.pushName */ },
          async fetchQr() { /* fetch '{{ route('admin.whatsapp.gateway.qr') }}', assign into this.qrImage */ },
      },
  });
  ```
- The actual HTML for that component lives in a separate `<script type="text/x-template" id="v-whatsapp-gateway-template">...</script>` block elsewhere in the same file, using normal Vue template syntax (`v-if`/`v-else-if`/`v-else` instead of Alpine's `x-if`, `:src` stays the same in Vue, `@click` stays the same in Vue — those two happen to be identical syntax between Alpine and Vue, so most of the existing markup can be reused almost as-is; it's the `x-data`/`x-init`/`<template x-if>` scaffolding around it that needs to change).
- The component is then placed in the page body as a custom element: `<v-whatsapp-gateway></v-whatsapp-gateway>`.

## What to fix

1. **`gateway.blade.php`**: convert the `x-data="whatsappGateway()"` root wrapper + the plain `<script>` `function whatsappGateway() {...}` at the bottom into the `app.component('v-whatsapp-gateway', {...})` + `<script type="text/x-template">` pattern above. Preserve all existing behavior exactly:
   - Calls `{{ route('admin.whatsapp.gateway.status') }}` and `{{ route('admin.whatsapp.gateway.qr') }}` on a 3-second poll (these two endpoints are correct server-side — do not change the controller or `WhatsAppClientService`).
   - Three visual states: connected (show linked number + Unlink button), QR ready (show the QR image + Refresh button), and loading/offline (spinner + "verify the Node service is running on 127.0.0.1:3001" message + Retry button).
   - Initial values still come from the Blade-rendered `$status`/`$qrData` variables the controller already passes in (`gateway()` method), so the page isn't blank before the first poll completes.
2. **`preview.blade.php`** and **`show.blade.php`**: same conversion — find every `x-data`/`x-init`/`x-if`/`x-show` in each file, rebuild it as a `v-xxx` Vue component the same way, keeping every existing route call, field name, and button action identical. `show.blade.php` is the live campaign dashboard (polls `{{ route('admin.whatsapp.status', $campaign->id) }}` — response shape is `{ id, status, pause_reason, consecutive_failures, total, sent, failed, pending, progress_percent, recent_recipients }`, already correct server-side) plus the Pause/Resume/Cancel/Retry-failed POST actions — preserve all of it, just re-platform the templating.
3. Do **not** add an Alpine.js `<script>` tag as an alternative fix — that would make this one page inconsistent with the rest of the app's stack and is exactly the kind of divergence to avoid. Vue is the existing, correct convention; use it.
4. After editing, no `npm run build` / Vite rebuild is needed — these are server-rendered Blade files with inline `<script type="text/x-template">` markup, not compiled by Vite. If the app has view caching on (`php artisan view:cache` was run), run `php artisan view:clear` so the edited Blade files take effect.

## Verify

- Load `admin.whatsapp.gateway` with the Node gateway (`whatsapp-gateway/`) running and not yet linked: confirm the QR image actually renders now (open browser devtools console — there should be zero JS errors, and the `<img>` `src` should be a populated `data:image/png;base64,...` string, not empty).
- Scan it with WhatsApp → confirm the page flips to the "Linked & Ready" state within a few seconds via the poll, without a manual refresh.
- Open a running campaign's `show` page and confirm the sent/failed/pending counters and recipient table actually update live, and Pause/Resume/Cancel/Retry buttons still work.
- Re-run whatever test suite exists for this feature; add none of this logic needs new backend tests since only view templates changed.
