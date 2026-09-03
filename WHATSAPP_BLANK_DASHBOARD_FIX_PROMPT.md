# Master Prompt — Fix blank page when clicking "Manage" (broadcast dashboard renders empty)

Paste this to your coding agent as-is.

---

## The bug

`packages/Crm/Admin/src/Resources/views/whatsapp/show.blade.php` returns valid HTML (confirmed: fetching the raw response and parsing it in an isolated `DOMParser` produces a complete, correctly-nested page — header, sidebar, and the broadcast dashboard content all present as expected). The blank page happens only after the browser's live Vue instance mounts: the entire sidebar + main-content wrapper `<div>` disappears from the DOM at runtime, leaving only the header and two unrelated global widgets (a toast container and a hidden modal). No JavaScript error, exception, or console warning is produced anywhere — the content is just silently absent.

## Root cause (empirically isolated, not guessed)

This was tested directly in a browser: taking the exact server-rendered HTML and re-mounting it with only the `<v-whatsapp-dashboard>...</v-whatsapp-dashboard>` usage tag swapped out for other content, everything else byte-for-byte identical:

- Replacing the tag with plain `<div>test</div>` → page renders correctly (4 top-level sections, sidebar included).
- Replacing it with an *empty* `<v-whatsapp-dashboard></v-whatsapp-dashboard>` (no props at all) → **still breaks** — so it isn't the bound props (`:campaign-id`, `initial-status`, etc.), it's the tag name itself.
- Renamed variants that were tested and confirmed to work: `v-whatsapp`, `v-dashboard`, `v-whatsapp-dash`, `v-broadcast`, `v-dashboard-whatsapp` (reversed word order), `v-whatsappdashboard` (no hyphen), `v-whatsapp-dashboard-x` (extra suffix).
- Renamed variants confirmed to reproduce the exact same failure: `v-whatsapp-dashboard` (original) and `v-whatsapp-Dashboard` (capitalized — so it's case-insensitive on the two words).

Conclusion: something in this specific environment (most likely an obscure Vue 3 in-DOM-template-compiler edge case triggered by that exact two-word component name — possibly also worth ruling out a browser extension by testing once in an Incognito window with extensions disabled, since one was observed injecting scripts into this page) silently causes Vue's mount of the root app to drop the entire subtree containing that custom element, with zero diagnostics. Chasing the exact underlying mechanism further isn't worth it — the fix below is proven to resolve it regardless of cause, and it's a pure rename with no behavior change.

## The fix

Rename the component everywhere it appears in `show.blade.php`, to something that does not combine the words "whatsapp" and "dashboard" (in that order, hyphenated) in one identifier. Use `v-broadcast-dashboard` (safe — confirmed working in testing) consistently in these three places:

1. The usage tag near the top of the file:
   ```blade
   <v-whatsapp-dashboard
       :campaign-id="{{ $campaign->id }}"
       ...
   ></v-whatsapp-dashboard>
   ```
   becomes
   ```blade
   <v-broadcast-dashboard
       :campaign-id="{{ $campaign->id }}"
       ...
   ></v-broadcast-dashboard>
   ```
   (keep every attribute exactly as-is — only the tag name changes).

2. The template script's `id`:
   ```blade
   <script type="text/x-template" id="v-whatsapp-dashboard-template">
   ```
   becomes
   ```blade
   <script type="text/x-template" id="v-broadcast-dashboard-template">
   ```

3. The component registration:
   ```js
   app.component('v-whatsapp-dashboard', {
       template: '#v-whatsapp-dashboard-template',
       ...
   ```
   becomes
   ```js
   app.component('v-broadcast-dashboard', {
       template: '#v-broadcast-dashboard-template',
       ...
   ```

Do not rename anything in `gateway.blade.php` (`v-whatsapp-gateway`) or `preview.blade.php` (`v-whatsapp-preview`) — neither combines "whatsapp" with "dashboard", and both were confirmed working already. Don't touch the controller, routes, or any PHP logic — this is a pure client-side naming fix.

## Verify

- Click "Manage" on any existing campaign from the WhatsApp Broadcast list and confirm the full dashboard now renders: header, sidebar, and the live sent/failed/pending counters, recipient table, and Pause/Resume/Cancel/Retry buttons.
- Open browser DevTools console while loading the page and confirm there are still zero errors (there were none before either — this wasn't a crash, just a silent empty render).
- Do a hard refresh (disable cache) to make sure you're not looking at a stale cached copy of the old template.
- If you want to actually pin down the root cause out of curiosity later (not required to unblock this feature): reproduce once in a fresh Incognito window with all extensions disabled, using the *original* `v-whatsapp-dashboard` name — if it now works there, the cause was a browser extension, not Vue itself, and no further code change would be needed.
