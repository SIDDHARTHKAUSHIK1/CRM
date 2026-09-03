# Master Prompt — Bring the WhatsApp Broadcast UI in line with the rest of the admin theme

Paste this to your coding agent as-is.

---

## Context

The WhatsApp Broadcast section (`packages/Crm/Admin/src/Resources/views/whatsapp/*.blade.php`: `index`, `create`, `preview`, `show`, `gateway`, `dnc`) doesn't visually match the rest of the admin panel — button sizes/alignment are inconsistent from screen to screen, some text/buttons are hard to read or invisible in dark mode, and the campaign history table's scrollbar looks and behaves differently from the rest of the app. This isn't a matter of taste — I compared it directly against `packages/Crm/Admin/src/Resources/views/quotes/*.blade.php`, `mail/*.blade.php`, and `leads/index.blade.php`, which are the reference for how this theme is supposed to look, and found concrete, fixable divergences. Fix these by making the WhatsApp views match those files' actual patterns — don't invent new styling.

## 1. Buttons: stop overriding size/padding, use the shared classes plain

`packages/Crm/Admin/src/Resources/assets/css/app.css` defines three button classes everyone else uses as-is, with no per-instance size overrides:
```css
.primary-button    /* bg-brandColor, text-gray-50, px-3 py-1.5, font-semibold */
.secondary-button   /* border-2 border-brandColor, bg-white, text-brandColor, dark:border-gray-400 dark:bg-gray-800 dark:text-white */
.transparent-button /* border-2 border-transparent, text-gray-600, dark:hover:bg-gray-950 */
```
Confirmed: every single `.primary-button`/`.secondary-button`/`.transparent-button` usage in `quotes/*.blade.php`, `mail/*.blade.php`, and `leads/index.blade.php` is used bare — `class="primary-button"`, nothing appended — letting the class's own padding and (inherited, contextual) font size stand. That's why buttons look consistent everywhere else.

The WhatsApp views instead pile ad hoc overrides onto the same base classes, and every screen picked different values:
- `create.blade.php`: `class="primary-button w-full justify-center !py-2.5 text-sm"`
- `dnc.blade.php`: `class="primary-button w-full justify-center !py-2 text-xs"`
- `index.blade.php`: `class="primary-button !py-1.5 !px-3 text-xs"` (Create Campaign button) and a plain `class="primary-button"` elsewhere, and `class="secondary-button !py-1 !px-2.5 text-xs"` (Manage button)
- `preview.blade.php`: `class="secondary-button text-xs !py-1.5"` and `class="primary-button disabled:opacity-50 disabled:cursor-not-allowed !py-2.5 !px-6 text-sm"`

That's five different effective button sizes across five screens for what should be the same primary/secondary action button. Fix: go through all six WhatsApp view files and strip every `!py-*`, `!px-*`, `text-xs`, `text-sm` override that's just resizing a `.primary-button`/`.secondary-button`/`.transparent-button` instance — leave `class="primary-button"` / `class="secondary-button"` bare, exactly like `quotes/create.blade.php` line 33 or `mail/index.blade.php` line 33. Keep `w-full`/`justify-center` where a button is genuinely meant to be full-width (that's a layout concern, not a sizing override, and fine to keep). Where a button legitimately needs to be visually smaller (e.g. a compact icon-only action in a table row), match how the rest of the theme does that instead of inventing a new size — check how `quotes/index.blade.php` or `mail/index.blade.php` size their small in-row action buttons and copy that pattern rather than an arbitrary `!py-1 !px-2.5 text-xs` guess.

Also check every non-button-class `<a>`/`<button>` element across these six files (e.g. the "Do Not Contact List" and "Link WhatsApp (QR)" links at the top of `index.blade.php`, which are currently hand-rolled with a long inline Tailwind class list instead of using `.secondary-button` or `.transparent-button`) — where an existing shared class fits, use it instead of a bespoke one-off class list, for the same consistency reason.

## 2. Campaign history table: replace the bespoke scrollbar CSS with the project's real pattern

`index.blade.php` currently has an inline `<style>` block (near the top of the file, ~40 lines) defining two custom, one-off things not used anywhere else in the codebase:

- `.whatsapp-table-scroll` — a fixed `max-height: 420px` with `overflow-y: scroll !important` and hardcoded emerald/gray hex scrollbar colors (`#10b981`, `#059669`, `#1f2937`, etc.) set via `::-webkit-scrollbar-thumb { background: ... }`.
- `.whatsapp-campaign-row:hover` — hardcoded hex row-hover backgrounds (`background-color: #f3f4f6 !important` / `#1f2937 !important` in dark mode) applied via a custom class on `<tr>`.

Neither pattern exists anywhere else in this theme, and both fight the project's actual conventions:
- The theme already has a global scrollbar style (`::-webkit-scrollbar` / `::-webkit-scrollbar-thumb` in `app.css`, ~12px gray thumb) that every other scrollable area inherits automatically — no per-page override needed.
- Scrollable content panels elsewhere (e.g. `leads/index/kanban.blade.php` line 78: `class="flex h-[calc(100vh-317px)] flex-col gap-2 overflow-y-auto p-2"`, or `mail/view.blade.php`'s thread panel) use a **responsive height tied to the viewport** (`h-[calc(100vh-Npx)]` or a `max-h-[...]` Tailwind utility) plus plain `overflow-y-auto` — never a fixed pixel `max-height` with `overflow-y: scroll !important`.
- Row hover states elsewhere are plain Tailwind utility classes directly on the `<tr>` — e.g. `hover:bg-gray-50 dark:hover:bg-gray-800/50` — never a custom CSS class with hardcoded hex and `!important`.

Fix: delete the entire `<style>` block from `index.blade.php`. Replace `class="whatsapp-table-scroll rounded-md border border-gray-200 dark:border-gray-800"` on the table's wrapper `<div>` with a responsive scrollable container matching the kanban/mail pattern — something like `class="max-h-[calc(100vh-320px)] overflow-y-auto rounded-md border border-gray-200 dark:border-gray-800"` (pick the exact offset so it lines up with this page's own header height, matching how `kanban.blade.php` sizes its offset against its own header). Replace `<tr class="whatsapp-campaign-row border-b border-gray-200 dark:border-gray-800">` with `<tr class="border-b border-gray-200 hover:bg-gray-50 dark:border-gray-800 dark:hover:bg-gray-800/50">` — this is the exact hover pattern already used on other tables in this theme, so it'll finally look and behave like the rest of the app, and the scrollbar will finally match every other scrollable panel instead of standing out with its own green theme.

## 3. Dark mode pass

Go through all six files and check every element that sets a background, text, or border color for a `dark:` counterpart, the same way the rest of the theme does it (e.g. `bg-white dark:bg-gray-900`, `text-gray-700 dark:text-gray-300`, `border-gray-300 dark:border-gray-800` — this pairing shows up on nearly every card/header in `quotes` and `mail`). Treat any raw hex color or `!important` rule as a signal something was hand-tuned instead of using the theme's Tailwind dark: utilities — replace those with the equivalent Tailwind dark: classes instead of keeping bespoke CSS. Specifically check:
- Every button and link for visible, correctly-contrasted text and background in both light and dark mode (this is the "buttons invisible in dark mode" complaint — usually caused by a light-only text or background color with no dark: pairing).
- Status badges, progress bars, and the circuit-breaker/alert banners on `show.blade.php` — confirm they have the same `dark:` treatment as equivalent elements elsewhere (e.g. compare against how `leads/index.blade.php`'s stage badges handle dark mode).
- Table headers, borders, and empty-state text across `index.blade.php`, `show.blade.php`, and `dnc.blade.php`.

## 4. General alignment/spacing pass

Compare each WhatsApp screen's header bar, card padding, and gap spacing against the equivalent screen in `quotes/` or `mail/` (both already use `px-4 py-3` header bars and `p-5` card padding consistently) and fix any WhatsApp screen that drifted from those values — the goal is that a user can't tell, just from spacing and button sizing, that the WhatsApp section was built separately from the rest of the app.

## Verify

- Open `index.blade.php`, `create.blade.php`, `preview.blade.php`, `show.blade.php`, `gateway.blade.php`, and `dnc.blade.php` side by side with `quotes/index.blade.php` and `mail/index.blade.php` in both light and dark mode. Every primary/secondary button across all six screens should now be visually identical in size (since none of them override padding/font-size anymore).
- Scroll the campaign history table on `index.blade.php`: confirm the scrollbar now looks like every other scrollbar in the app (gray thumb, no green), and the table height is responsive to viewport size rather than a fixed 420px.
- Toggle dark mode on every WhatsApp screen and confirm no text or button becomes low-contrast or invisible.
- Confirm no `<style>` blocks with hardcoded hex colors or `!important` remain anywhere in the six WhatsApp view files.
