# Master Prompt — Project-Wide UI Consistency Pass (Buttons, Colors, Fonts, Dark/Light Mode, Alignment)

Paste this whole prompt into whichever AI coding assistant is working on the codebase. It is self-contained. It reflects an actual audit of this repo, not generic advice — the findings below are real and should be trusted as the starting point instead of being re-discovered from scratch.

---

## 1. Stack & how theming works here

- Laravel + Blade, modular package architecture (`packages/Crm/<Module>/...`), Vue 3 mounted into Blade via `app.component(...)` + `<script type="text/x-template">`, Tailwind CSS 3.
- Tailwind config: `packages/Crm/Admin/tailwind.config.js` — `darkMode: 'class'`. This means dark mode is controlled by a `dark` class on an ancestor element, and **every color you hardcode must be given an explicit `dark:` counterpart** — there's no automatic contrast handling.
- The global font is already centralized correctly: `font-inter` is set once in `packages/Crm/Admin/src/Resources/views/components/layouts/index.blade.php`, and all ~272 Blade views extend that one layout. **Do not touch font-family** — that part isn't broken. The real font problem is *text-color/contrast* legibility per theme, and weight/size drift between pages, not the typeface itself.
- There is already a real, tenant-configurable brand color system: a CSS variable `--brand-color` (exposed to Tailwind as the `brandColor` color, set in `tailwind.config.js`), configurable by the admin at Settings → General → Menu Color → Brand Color. Three shared button classes already exist for it in `packages/Crm/Admin/src/Resources/assets/css/app.css` (search `.primary-button`, `.secondary-button`, `.transparent-button`, around lines 522–535):
  - `.primary-button` → solid brand-colored button, white text.
  - `.secondary-button` → brand-colored outline button, already has correct `dark:` overrides (`dark:border-gray-400 dark:bg-gray-800 dark:text-white`).
  - `.transparent-button` → ghost/tertiary button, already has correct `dark:` overrides.
- There is also a generic Vue wrapper component, `<x-admin::button buttonClass="..." title="..." />` (`packages/Crm/Admin/src/Resources/views/components/button/index.blade.php`), used in ~21 places — but it just proxies whatever class string it's given, so it does **not** by itself guarantee consistency; the class string passed to it still has to be one of the standard button classes.

## 2. The actual problem (confirmed by grep, not guesswork)

The three standard button classes above are used correctly in **84 files**. But at least **33+ files** bypass them entirely and hardcode one-off Tailwind colors directly on raw `<button>` tags — a different accent color per page, picked ad hoc, none of them tied to the configurable brand color, and none guaranteed to have a matching `dark:` pair. Confirmed offenders include (this list is a starting point, not the whole set):

- `packages/Crm/Admin/src/Resources/views/activities/index.blade.php` — orange-600/500, emerald-500/600, amber-500, purple-500/600, blue-500/600 all mixed together.
- `packages/Crm/Admin/src/Resources/views/quotes/index.blade.php`, `create.blade.php`, `edit.blade.php` — orange-600 + emerald-500 + amber-500.
- `packages/Crm/Admin/src/Resources/views/leads/index/kanban.blade.php` — emerald-500, purple-500, blue-500, amber-500, indigo-500 all in one file.
- `packages/Crm/Admin/src/Resources/views/settings/data-transfer/imports/import.blade.php` — green-600/700.
- `packages/Crm/Admin/src/Resources/views/mail/index.blade.php` — sky-600.
- `packages/Crm/Admin/src/Resources/views/whatsapp/show.blade.php` — emerald-500.
- `packages/Crm/Admin/src/Resources/views/leads/view/stages.blade.php` — emerald-600.

This is why the app currently feels visually inconsistent: primary actions are orange on one screen, blue on another, green or sky-blue elsewhere, and none of it moves together if the brand color is changed in Settings — plus, because these are all bespoke classes, nobody has verified each one actually reads correctly with a `dark` class on the page.

## 3. The target design system (converge everything onto this)

- **Primary action buttons** (the main "Save", "Add", "Create", "Send" button on any screen) → `.primary-button`. No more per-page accent colors for primary actions.
- **Secondary/outline actions** ("Cancel", secondary options next to a primary button) → `.secondary-button`.
- **Tertiary/ghost/icon-only actions** → `.transparent-button`.
- **Semantic colors** (success, danger/error, warning, info — used for status badges, toasts, validation states, "done/completed" indicators, delete buttons, etc.) are the one legitimate exception to "always use the brand color." Collapse these to exactly **one fixed mapping used everywhere**, each written with its `dark:` pair every time it's used:
  - Success → `emerald` (e.g. `bg-emerald-500 dark:bg-emerald-600` / `text-emerald-700 dark:text-emerald-300`, consistent shade numbers app-wide).
  - Danger/destructive → `rose` or `red` (pick one family and use it everywhere — don't mix `red` and `rose` for the same meaning).
  - Warning → `amber`.
  - Info/neutral highlight → `blue`.
  - Anything using `purple`, `indigo`, `sky`, `violet`, `green` (as opposed to `emerald`), `teal`, `cyan`, `pink` for a *button or status meaning* should be re-mapped into the above set unless it's a deliberate, documented exception (e.g. a kanban stage color picker where the whole point is many distinct colors — that's fine to leave alone; a "Save" button being purple for no reason is not).
- **Every** text/background color pairing must be checked in both themes. The rule: if a class list contains any `bg-*` or a container is known to get `dark:bg-*` from its layout, the text classes on that same element (and its children) must include an explicit `dark:text-*` that keeps contrast readable — don't rely on inherited color or assume it "probably still reads fine."

## 4. What to actually do — audit first, then fix module by module

Don't attempt one giant, unreviewable rewrite of the whole app in a single pass. Work like this:

### Step 1 — Audit and produce a written inventory (do this before changing anything)
Run these (or equivalent) across the whole `packages/Crm` tree and record the results:

```bash
# Every raw color used directly on a <button> or button-like element, instead of the standard classes
grep -rnoE "bg-(orange|blue|indigo|violet|purple|red|green|teal|cyan|pink|amber|sky)-(400|500|600|700)" packages/Crm --include="*.blade.php"

# Files that have <button> markup but never reference the standard button classes at all
grep -rl "<button" packages/Crm --include="*.blade.php" | xargs grep -L "primary-button\|secondary-button\|transparent-button\|x-admin::button"

# Every place a dark: background is set on a container, to manually re-check text contrast inside it
grep -rn "dark:bg-\(gray\|slate\)-[0-9]\+" packages/Crm --include="*.blade.php"
```

Turn the output into a simple checklist grouped by module/folder (Activity, Lead, Contact, Quote, Product, Warehouse, Settings, Mail, WhatsApp, shared `components/`) so the fix pass below can go folder by folder instead of file by random file.

### Step 2 — Fix module by module, smallest reasonable diffs
For each module in turn:
1. Replace bespoke button classes with `.primary-button` / `.secondary-button` / `.transparent-button` per the rules in section 3, or route the semantic-color cases into the fixed success/danger/warning/info mapping.
2. Add or correct the missing `dark:` counterpart on every text/background color you touch — don't just add `dark:text-white` everywhere reflexively; pick a shade that keeps real contrast against that element's actual dark-mode background.
3. While you're in a file, normalize obviously inconsistent spacing/alignment on the elements you're touching: card corner radius, internal padding, icon sizing, and label typography (`text-xs font-black uppercase tracking-wider` is the pattern already used for eyebrow/label text in several screens — reuse it rather than inventing new label styles) — but don't do a speculative full re-layout of screens outside this task's scope.
4. Keep using the existing conventions: Blade components under `components/`, the `x-admin::button` wrapper, and the Vue `app.component('v-xxx-component', {...})` + `text/x-template` pattern already used throughout this codebase. Don't introduce a new UI kit or a second styling approach.

### Step 3 — Rebuild and manually verify after each module
This project's front-end assets are built with Vite (`vite.config.js` at the repo root, plus a separate `package.json`/build per package: `packages/Crm/Admin`, `packages/Crm/Installer`, `packages/Crm/WebForm`). After changing a module, rebuild (`npm run build` in the relevant package) and actually load that module in a browser:
- Toggle light → dark mode and re-check every screen you touched.
- Check at roughly 375px, 768px, 1024px, and 1440px widths.
- Confirm the primary action button now visibly follows the configured Brand Color (test by changing Settings → General → Menu Color → Brand Color and confirming the button updates, instead of staying a hardcoded color).

### Step 4 — Final whole-app pass
Once every module is done, re-run the Step 1 grep commands against the whole `packages/Crm` tree again — they should return nothing outside the documented semantic-color exceptions. Do one more full click-through in both themes as a sanity check.

## 5. Guardrails — do not do these
- Do not change any backend logic, routes, controllers, or JS behavior/bindings while restyling — this is a visual/consistency pass only.
- Do not introduce a new CSS framework, component library, or a second "design system" alongside the existing one — everything should converge on the `.primary-button`/`.secondary-button`/`.transparent-button` + brand-color system that already exists.
- Do not change the global font-family setup — it's already centralized correctly.
- Don't blanket-replace every non-brand color you find — kanban stage pickers, calendar activity-type colors, chart colors, and similar "many distinct colors are the point" UI are legitimate exceptions; use judgment and call them out explicitly rather than silently leaving them or silently converting them.

## 6. Acceptance checklist
- [ ] Every primary action button across the app uses `.primary-button` (or `.secondary-button`/`.transparent-button` for secondary/tertiary actions) and visibly reacts to the Brand Color setting.
- [ ] Semantic colors (success/danger/warning/info) are reduced to one consistent mapping, each with a correct `dark:` pair, used the same way on every screen.
- [ ] No remaining raw `bg-{color}-{shade}` primary/secondary buttons outside the documented exceptions (kanban stages, calendar types, charts).
- [ ] Every screen checked in both light and dark mode at 375px/768px/1024px/1440px — no invisible or low-contrast text anywhere.
- [ ] Spacing, radius, and label typography look consistent card-to-card and page-to-page.
- [ ] No functional/behavioral regressions — only classes/markup for styling changed.
- [ ] Front-end assets rebuilt after changes and the app was actually viewed in a browser, not just diffed.
