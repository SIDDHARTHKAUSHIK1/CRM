# Dashboard KPI Card — Ambient Color-Motion — Master Prompt

**Paste this entire document into a coding agent (or read it yourself) with full read/write access to the CRM repo (`E:\Study Material\Skills\Project\CRM`). It is self-contained: goal, current state, exact design spec, ready-to-paste code, and a QA checklist.**

---

## 0. What you're building

Right now, one KPI card on the dashboard — **Lost Revenue** — has a colored highlighted border/glow that the other three (Won Revenue, Total Leads, Average Lead Value) don't have. The ask: give **all four cards** the same kind of animated color treatment, each in its **own** semantic color, so the whole KPI row feels like one consistent, alive system instead of one card looking "special."

This is a decorative motion layer — it must never become the only way a user tells cards apart (icons, labels, and value color already do that job) and it must respect `prefers-reduced-motion`.

**Definition of done:**
- All 4 stat cards (Won Revenue, Lost Revenue, Total Leads, Average Lead Value) have a continuous, subtle ambient glow/pulse around the card border, tinted with that card's own color.
- Each card's icon chip and top accent line breathe gently in sync with the border glow.
- Hovering a card pauses the ambient loop and snaps to a crisper "focused" elevated state.
- Everything is built with one shared CSS/Tailwind system driven by a `variant` (success / danger / info / warning), not four copy-pasted, hand-tuned animations.
- Works in light and dark mode (this app uses Tailwind `darkMode: 'class'`).
- Fully disabled under `prefers-reduced-motion: reduce`.
- No layout shift, no jank — only `transform`, `opacity`, and `box-shadow` are animated.

---

## 1. Confirmed current stack (verified against the repo)

- Laravel 12 CRM built on the Krayin package architecture, rebranded `Webkul\*` → `Crm\*`. Admin UI package: `packages/Crm/Admin`.
- Styling: **Tailwind CSS 3.3.2**, config at `packages/Crm/Admin/tailwind.config.js`, `darkMode: 'class'`, no animation plugin installed (no `tailwindcss-animate`) — custom keyframes must be added by hand.
- Central stylesheet: `packages/Crm/Admin/src/Resources/assets/css/app.css`.
- Build: Vite (`laravel-vite-plugin`) + PostCSS/Autoprefixer. Rebuild with `npm run build` (or `npm run dev` to watch) from inside `packages/Crm/Admin`.
- Views are Blade (`.blade.php`) under `packages/Crm/Admin/src/Resources/views/`, with Vue 3 mounted for the more interactive widgets (charts, calendars). The KPI row is very likely plain Blade + Tailwind, not a Vue component — confirm in step 4 below.
- Dashboard-specific partials live in `packages/Crm/Admin/src/Resources/views/dashboard/index/`. Based on file names and sizes, the KPI cards in the screenshot are rendered from one or more of:
  - `revenue.blade.php` (≈16KB — most likely home of Won Revenue + Lost Revenue)
  - `over-all.blade.php` (≈10KB — likely the "overall stats" row, possibly all 4 cards)
  - `total-leads.blade.php` (≈4.7KB — likely Total Leads)
  - There is a matching shimmer/skeleton set under `views/components/shimmer/dashboard/index/*.blade.php` — if you add markup wrappers, mirror them in the shimmer state too so the loading skeleton doesn't visually "pop" once real data renders.

I could not open these exact files directly from this session (a device-bridge depth limit blocked it), so **step 4 is mandatory**: confirm the real file(s) and current markup before editing.

---

## 2. What the reference screenshot actually shows

Four cards in a row, each: white surface, rounded corners, a label in small caps gray text, a large bold value, a colored icon chip top-right, and a colored trend pill at the bottom ("↑/↓ 100% from last 30 days").

- **Won Revenue** — green icon chip (checkmark/briefcase), green trend pill. No visible border treatment.
- **Lost Revenue** — red icon chip (down-trend arrow), red value text, an extra small red badge under the value, red trend pill, **and a distinct solid red border/glow around the entire card** — this is the treatment being generalized.
- **Total Leads** — blue icon chip (people), blue trend pill. No border treatment.
- **Average Lead Value** — amber/orange icon chip (currency), orange trend pill. No border treatment.

So the job is literally: take whatever gives Lost Revenue its colored border today, turn it into a reusable animated variant, and apply it to all four using each card's own color instead of hard-coding red everywhere.

---

## 3. Step 4 (do this first): locate the real markup

Before writing any CSS, find the exact source:

```bash
grep -rn "LOST REVENUE\|Lost Revenue" packages/Crm/Admin/src/Resources/views/
grep -rn "WON REVENUE\|Won Revenue" packages/Crm/Admin/src/Resources/views/
grep -rn "AVERAGE LEAD VALUE\|Average Lead Value" packages/Crm/Admin/src/Resources/views/
```

From there:
1. Note the exact file(s) and whether the 4 cards are 4 separate hard-coded blocks or already loop over a data array / shared partial.
2. Find whatever currently gives Lost Revenue its border (likely a conditional Tailwind class like `border border-red-500` or `ring-2 ring-red-200`, possibly already inside an `@if` for "is this metric negative"). Note it — you'll replace/extend it, not duplicate it.
3. Check whether there's already a shared "stat card" Blade partial anywhere under `views/components/` — if the cards are copy-pasted, this is also the moment to fold them into one partial (recommended, see §6), but if refactor risk is a concern, §7's "minimal-diff" path skips the partial and just adds classes to each existing block.

---

## 4. Design tokens

Use CSS custom properties so one animation definition serves all four colors. RGB triplets (Tailwind-compatible, so you can also reuse them as `rgb(var(--kpi-accent) / <alpha>)`):

| Card | Variant name | Tailwind color | `--kpi-accent` (R G B) |
|---|---|---|---|
| Won Revenue | `success` | emerald-500 | `16 185 129` |
| Lost Revenue | `danger` | red-500 | `239 68 68` |
| Total Leads | `info` | blue-500 | `59 130 246` |
| Average Lead Value | `warning` | amber-500 | `245 158 11` |

If the app already has brand tokens for these states elsewhere (check `app.css` for existing `--success`/`--danger` type variables before introducing new ones — reuse what exists rather than forking the palette).

---

## 5. Animation spec

Three synchronized, low-amplitude loops plus one hover state. Keep amplitude subtle — this is a business dashboard, not a game UI. Target: someone should register "this feels alive" peripherally, not consciously notice cards flashing.

1. **Border glow breathing** (primary effect, on the card root): box-shadow oscillates between a tight, faint ring and a slightly larger, slightly stronger glow. 2.6s, ease-in-out, infinite, alternating direction (no snap-back).
2. **Icon chip pulse** (on the colored icon square): a gentle `scale(1 → 1.08 → 1)` on a slightly longer period (3.2s) so it doesn't beat in lockstep with the border — staggered life reads more organic than perfectly synced motion.
3. **Top accent bar shimmer**: a 3px gradient bar along the top edge of the card (add this element if it doesn't exist) fading opacity 0.5 → 1 → 0.5 in time with the border glow.
4. **Hover / focus state**: pause the ambient loop (`animation-play-state: paused`), snap to `translateY(-3px)`, full-opacity border, `shadow-lg`, 200ms ease-out transition. This makes hovering feel intentional/crisp rather than fighting the ambient motion.

Optional stretch goal (nice-to-have, skip if time-boxed): when a card's value changes after a live dashboard refresh, fire a one-shot expanding "ping" ring in the card's color (~900ms, single iteration) so the user's eye catches which number just updated. Implementation note in §7.

---

## 6. Ready-to-paste code

### 6a. `packages/Crm/Admin/tailwind.config.js` — extend `theme.extend`

Add alongside the existing `colors`/`fontFamily` entries (don't replace the file, merge into `extend`):

```js
extend: {
    colors: {
        brandColor: "var(--brand-color)",
    },

    fontFamily: {
        inter: ['Inter'],
        icon: ['icomoon']
    },

    keyframes: {
        'kpi-card-glow': {
            '0%, 100%': {
                boxShadow: '0 0 0 1px rgb(var(--kpi-accent) / 0.22), 0 0 0 0 rgb(var(--kpi-accent) / 0)',
            },
            '50%': {
                boxShadow: '0 0 0 1px rgb(var(--kpi-accent) / 0.55), 0 0 16px 2px rgb(var(--kpi-accent) / 0.30)',
            },
        },
        'kpi-icon-pulse': {
            '0%, 100%': { transform: 'scale(1)' },
            '50%': { transform: 'scale(1.08)' },
        },
        'kpi-accent-bar': {
            '0%, 100%': { opacity: '0.5', transform: 'scaleX(0.94)' },
            '50%': { opacity: '1', transform: 'scaleX(1)' },
        },
        'kpi-ring-ping': {
            '0%': { boxShadow: '0 0 0 0 rgb(var(--kpi-accent) / 0.45)' },
            '100%': { boxShadow: '0 0 0 14px rgb(var(--kpi-accent) / 0)' },
        },
    },

    animation: {
        'kpi-card-glow': 'kpi-card-glow 2.6s ease-in-out infinite',
        'kpi-icon-pulse': 'kpi-icon-pulse 3.2s ease-in-out infinite',
        'kpi-accent-bar': 'kpi-accent-bar 2.6s ease-in-out infinite',
        'kpi-ring-ping': 'kpi-ring-ping 900ms ease-out 1',
    },
},
```

### 6b. `packages/Crm/Admin/src/Resources/assets/css/app.css` — new section

Add as its own clearly-marked block (don't scatter into unrelated rules):

```css
/* =========================================================
   Dashboard KPI cards — ambient color-motion
   ========================================================= */
.kpi-card {
    position: relative;
    --kpi-accent: 100 116 139; /* neutral fallback (slate-500) if no variant class matches */
    transition: transform 200ms ease-out, box-shadow 200ms ease-out;
}

.kpi-card--success { --kpi-accent: 16 185 129; }
.kpi-card--danger  { --kpi-accent: 239 68 68; }
.kpi-card--info    { --kpi-accent: 59 130 246; }
.kpi-card--warning { --kpi-accent: 245 158 11; }

.kpi-card .kpi-accent-bar {
    position: absolute;
    top: 0;
    left: 12px;
    right: 12px;
    height: 3px;
    border-radius: 9999px;
    background: linear-gradient(90deg, rgb(var(--kpi-accent) / 0), rgb(var(--kpi-accent) / 0.9), rgb(var(--kpi-accent) / 0));
}

@media (prefers-reduced-motion: no-preference) {
    .kpi-card {
        animation: kpi-card-glow 2.6s ease-in-out infinite;
    }

    .kpi-card .kpi-icon-chip {
        animation: kpi-icon-pulse 3.2s ease-in-out infinite;
    }

    .kpi-card .kpi-accent-bar {
        animation: kpi-accent-bar 2.6s ease-in-out infinite;
    }

    .kpi-card:hover,
    .kpi-card:focus-within {
        animation-play-state: paused;
        transform: translateY(-3px);
        box-shadow: 0 12px 24px -8px rgb(var(--kpi-accent) / 0.35), 0 0 0 1.5px rgb(var(--kpi-accent) / 0.7);
    }

    .kpi-card:hover .kpi-icon-chip,
    .kpi-card:hover .kpi-accent-bar {
        animation-play-state: paused;
    }

    /* One-shot ping, toggled via JS/Vue when a value just changed */
    .kpi-card.kpi-card--pinged {
        animation: kpi-ring-ping 900ms ease-out 1;
    }
}

@media (prefers-reduced-motion: reduce) {
    .kpi-card,
    .kpi-card .kpi-icon-chip,
    .kpi-card .kpi-accent-bar {
        animation: none !important;
    }

    .kpi-card:hover,
    .kpi-card:focus-within {
        transform: none;
        box-shadow: 0 0 0 1.5px rgb(var(--kpi-accent) / 0.6);
    }
}
```

Dark mode: since `darkMode: 'class'` is already set, the glow reads fine as-is on a dark surface (it's additive light), but soften it slightly so it doesn't overpower dark card backgrounds:

```css
.dark .kpi-card {
    --kpi-glow-dark-boost: 1; /* placeholder if you want to scale opacity differently in dark mode */
}
```
If dark-mode contrast looks too hot after testing, drop the `0.55`/`0.30` alphas in the keyframes to `0.45`/`0.22` inside a `.dark` override rather than maintaining a second keyframe set.

### 6c. Blade markup — apply to each existing card

Wherever each card's root `<div>` currently lives, add the shared class + variant class + accent bar element. Example shape (adapt to whatever the real markup looks like once you've done §3):

```blade
<div class="kpi-card kpi-card--success relative rounded-lg bg-white p-5 dark:bg-gray-900 ...">
    <span class="kpi-accent-bar"></span>

    <div class="flex items-start justify-between">
        <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
            {{ __('admin::app.dashboard.won-revenue') }}
        </p>

        <div class="kpi-icon-chip flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10">
            <!-- existing icon markup -->
        </div>
    </div>

    <p class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">
        {{ core()->formatBasePrice($stats['won_revenue']) }}
    </p>

    <!-- existing trend pill markup -->
</div>
```

Repeat with `kpi-card--danger` (Lost Revenue), `kpi-card--info` (Total Leads), `kpi-card--warning` (Average Lead Value) — same structural additions, only the variant class and existing color classes change per card. **Remove whatever one-off border/ring class currently only exists on the Lost Revenue card** once `kpi-card--danger` is in place, so the effect isn't doubled.

### 6d. Optional — trigger the one-shot ping on live refresh

If the dashboard re-fetches these numbers via AJAX/Vue without a full page reload, add a tiny helper to flash `.kpi-card--pinged` on whichever card's value changed, then remove the class after the animation ends:

```js
function pingCard(el) {
    el.classList.add('kpi-card--pinged');
    el.addEventListener('animationend', () => el.classList.remove('kpi-card--pinged'), { once: true });
}
```
Skip this if the dashboard is a normal full-page load — it only matters for live-updating widgets.

---

## 7. Minimal-diff alternative (skip the partial refactor)

If consolidating the 4 cards into one Blade partial feels too risky for this change, you don't have to: just add `kpi-card kpi-card--{variant}` and the `<span class="kpi-accent-bar">` to each of the 4 existing blocks in place, and add `kpi-icon-chip` to each existing icon wrapper. The CSS in §6b works identically either way — the partial refactor is a nice-to-have for future maintainability, not a requirement for this animation to work.

---

## 8. Accessibility & performance guardrails

- **Never rely on the glow color alone** to communicate meaning — labels, icons, and value color already carry that; the glow is pure ambience. Screen readers ignore it entirely, which is correct.
- Respect `prefers-reduced-motion: reduce` — fully implemented above; verify by enabling "reduce motion" in OS settings and confirming cards go static with a small static ring instead.
- Only `transform`, `opacity`, and `box-shadow` are animated — all GPU-composited, no layout thrash, safe to run 4 infinite loops simultaneously without measurable CPU cost.
- Don't add `will-change` globally; if profiling shows any jank on low-end devices, scope `will-change: box-shadow, transform` to `.kpi-card:hover` only.
- Keep `kpi-card` `position: relative` so the absolutely-positioned accent bar doesn't escape the card.

---

## 9. QA checklist

- [ ] All 4 cards show the ambient glow in their correct color (green / red / blue / amber) on page load, without needing to hover.
- [ ] Hover pauses the ambient loop and shows the elevated focused state; moving away resumes the ambient loop smoothly (no jump/flash).
- [ ] Dark mode: glow is visible but not blown out against dark card backgrounds.
- [ ] OS-level "reduce motion" turns all animation off; cards still show a subtle static colored ring so the variant is still visually legible.
- [ ] No layout shift when the accent bar/animation is added — check with the browser's layout-shift/paint-flashing tools.
- [ ] Mobile width (~375–430px): cards still render correctly, glow doesn't clip against card edges or get cut off by `overflow: hidden` on a parent.
- [ ] The old one-off red border/ring that only existed on Lost Revenue is fully removed (not layered under the new effect).
- [ ] If a shimmer/skeleton loading state exists for these cards, it still looks correct (skeleton doesn't need the animation, but shouldn't visually jump when real content swaps in).
- [ ] `npm run build` inside `packages/Crm/Admin` completes clean, and the built dashboard reflects the change after a hard refresh.

---

## 10. Rollback

Every change here is additive and isolated:
- Revert the new CSS block in `app.css`.
- Revert the `keyframes`/`animation` additions in `tailwind.config.js`.
- Remove `kpi-card`, `kpi-card--*`, `kpi-icon-chip`, `kpi-accent-bar` classes from the 4 card blocks (and restore whatever one-off class previously drew the Lost Revenue border, if you don't want to keep the new system).

No data, routes, or backend logic are touched — this is a pure CSS/Tailwind + markup change.
