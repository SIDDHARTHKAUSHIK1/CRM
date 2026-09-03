# Master Prompt — Fix "bright white" dark-mode hover + missing scrollbar in WhatsApp Broadcast (stale frontend build, not a code bug)

Paste this to your coding agent as-is.

---

## Read this first: the Blade/CSS source is already correct — do NOT rewrite classes again

I went through every WhatsApp view file line by line looking for the "bright white hover in dark mode" and "no scrollbar in Broadcast Campaigns History" bugs, expecting to find an unguarded `hover:bg-*` utility missing its `dark:hover:*` counterpart (that was the pattern the last cleanup pass fixed). It is **not there anymore** — every `hover:` class in `index.blade.php`, `show.blade.php`, `preview.blade.php`, `dnc.blade.php`, `create.blade.php` already has a correct `dark:hover:*` pairing (e.g. `hover:bg-gray-50 dark:hover:bg-gray-800/50` on table rows), and the shared `.secondary-button` / `.primary-button` / `.transparent-button` classes in `packages/Crm/Admin/src/Resources/assets/css/app.css` already have correct dark-mode hover treatment (`.secondary-button` has `hover:bg-[#eff6ff61] ... dark:hover:opacity-80`). The `max-h-[calc(100vh-320px)] overflow-y-auto` scroll container on the campaign history table (`index.blade.php` line ~152) is also already there.

**The actual root cause is a stale production build**, confirmed directly:

- `packages/Crm/Admin/` is its own Vite package (own `vite.config.js`, own `package.json` with `"build": "vite build"`) that compiles `packages/Crm/Admin/src/Resources/assets/css/app.css` (Tailwind) into `public/admin/build/assets/app-CJys9pX6.css` — this is the file Tailwind actually generates, and it's what `<x-admin::layouts>` serves to the browser via `@vite`, using `public/admin/build/manifest.json`. (There's also a *separate*, unrelated root `public/build/` from the top-level `vite.config.js` — don't confuse the two; the WhatsApp/admin theme's CSS is `public/admin/build/assets/app-CJys9pX6.css`.)
- That compiled CSS file's timestamp is **2026-09-01 12:15** (checked with `ls -la`).
- `show.blade.php` and `index.blade.php` (and `app.css` itself, which has 10 uncommitted lines adding a WhatsApp icon glyph) were last edited **2026-09-02 10:38–10:40** — over 22 hours *after* that build, and `git status` confirms these edits are still uncommitted, i.e. never rebuilt since.
- Tailwind is a JIT/on-demand compiler: it only emits a CSS rule for a class name if that exact class name is present in the scanned source *at the moment `vite build` runs*. I confirmed by grepping the compiled `app-CJys9pX6.css` directly:
  - `hover:bg-gray-50` (the light-mode rule) → **present** (1 match — it already existed from before).
  - `dark:hover:bg-gray-800/50` (the dark-mode override that's supposed to beat it) → **0 matches. Completely absent.**
  - `dark:hover:opacity-80` (the `.secondary-button` dark hover) → **0 matches. Completely absent.**
  - `max-h-[calc(100vh-320px)]` (the scroll container's height cap) → **0 matches. Completely absent.** `overflow-y-auto` alone *is* present (it's a generic class used elsewhere already), but with no compiled `max-height` rule the container has nothing to constrain it, so it just grows to fit every row — nothing ever overflows, so no scrollbar ever appears, even though the Blade markup is correct.
  - `packages/Crm/Admin/node_modules` is **missing entirely** — confirming a build genuinely has not been run in this package recently (a bare `vite build` would fail immediately without it).

This is exactly consistent with what you're seeing and with what you're not seeing: Leads/Quotes/Mail look correct in dark mode because their `dark:hover:*` classes were compiled into this CSS bundle in an earlier, successful build and haven't changed since — only the newly-added WhatsApp dark-hover classes and the new scroll-container class are missing, because they were written to the source after the last time anyone ran the build.

## The fix — rebuild the Admin package's frontend assets, don't touch Blade/CSS class names

Run these commands from the project root:

```bash
cd packages/Crm/Admin
npm install
npm run build
cd ../../..
```

(`npm install` is required first — `node_modules` doesn't exist yet in this package. `npm run build` runs `vite build`, which reads `packages/Crm/Admin/vite.config.js` — that config's `laravel-vite-plugin` entry has `buildDirectory: "admin/build"`, so it writes fresh, content-hashed files into `public/admin/build/` and regenerates `public/admin/build/manifest.json`. Laravel's `@vite(...)` directive reads that manifest on every request, so the new hashed filenames are picked up automatically — no manual cache-busting or version bump needed.)

Then clear Laravel's own caches so nothing else is serving a stale compiled view or cached config on top of this:

```bash
php artisan view:clear
php artisan optimize:clear
```

Do **not** edit any `.blade.php` or `app.css` class names as part of this fix — the classes are already right; this is purely a "the compiled output hasn't caught up with the source" problem. If, after rebuilding, you spot an *actual* remaining unguarded `hover:` class (i.e. one truly missing a `dark:hover:` pairing in the source, not just missing from a stale bundle), fix only that specific class the same way the rest of the theme does it (`hover:bg-gray-50 dark:hover:bg-gray-800/50` on rows, `hover:bg-gray-100 dark:hover:bg-gray-700` on buttons) — but based on the source review above, none should be left.

## Verify

- After the rebuild, confirm `public/admin/build/manifest.json` now has a newer timestamp than `show.blade.php`/`index.blade.php`, and that the new CSS file (new hash, e.g. no longer `app-CJys9pX6.css`) contains `dark:hover:bg-gray-800/50`, `dark:hover:opacity-80`, and `max-h-\[calc(100vh-320px)\]` — you can check with `grep -c "hover\\\\:opacity-80" public/admin/build/assets/app-*.css` (should be ≥1 after rebuild, was 0 before).
- Hard-refresh (disable cache) the WhatsApp Broadcast list (`index.blade.php`), the campaign management dashboard (`show.blade.php`), the preview screen, and the Do Not Contact list, in dark mode. Hover over table rows, the Manage/Pause/Resume/Cancel/Retry buttons, and the DNC/Link WhatsApp header links — every hover should now be the same dull/muted dark hover used on Leads/Quotes/Mail, not bright white.
- On the WhatsApp Broadcast list, add or view enough campaigns that the history table would overflow a typical viewport, and confirm the "Broadcast Campaigns History" panel now actually caps its height and shows a real scrollbar (matching the global gray scrollbar style already defined in `app.css`) instead of growing the page indefinitely.
- Toggle light mode back on and confirm nothing regressed there — this rebuild should only add previously-missing rules, not remove or change any existing ones.
- Once confirmed working, commit the pending changes shown by `git status` (`app.css`, the WhatsApp Blade views, and the newly-rebuilt `public/admin/build/assets/*`) together, so the compiled output in git stays in sync with the source from now on — a repeat of this same bug (edit Blade/CSS, forget to rebuild) is the single most likely way this regresses again.
