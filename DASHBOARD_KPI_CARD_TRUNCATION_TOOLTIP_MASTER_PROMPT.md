# Dashboard KPI Card — Hover Preview for Truncated Values — Master Prompt

**Paste this into a coding agent (or follow it yourself) with write access to the CRM repo. Self-contained: problem, verified codebase context, two implementation options with a clear recommendation, ready-to-paste code, and a QA checklist.**

---

## 1. The problem

At higher browser zoom, the 4 KPI cards (Won Revenue, Lost Revenue, Total Leads, Average Lead Value) don't have enough width for their content, so the label and/or value get cut off with an ellipsis — `AVERAGE LEA…`, `₹107,00…`, `₹35,000…`, `₹51,167,…`. Zoom doesn't fire a dedicated browser event, but it does change how much CSS pixel width each card has, which is exactly what `ResizeObserver` exists to detect — so this isn't a "zoom" feature, it's a "handle truncation whenever it happens" feature, and zoom is just today's trigger. The same fix also covers a narrow window, a collapsed/expanded sidebar changing card width, or a very large number blowing past the card's width on a normal-size screen.

**Goal:** on hover (and on keyboard focus, for accessibility), if a KPI card's label or value is actually truncated, show a small preview with the full, untruncated text. If it isn't truncated (e.g. "10" for Total Leads), nothing should happen on hover — no tooltip for content that already fits.

---

## 2. Verified codebase context (read directly from the repo)

- Laravel 12 / Krayin-based CRM, admin package at `packages/Crm/Admin`.
- Tailwind CSS 3.3.2, `darkMode: 'class'`, central stylesheet `packages/Crm/Admin/src/Resources/assets/css/app.css`, Vite build.
- **There is already a global Vue tooltip directive in this codebase**: `packages/Crm/Admin/src/Resources/assets/js/app.js` registers it —
  ```js
  import ToolTip from "./directives/tooltip";
  app.directive("tooltip", ToolTip);
  ```
  — meaning `v-tooltip="..."` is already usable somewhere in this app's Blade markup, driven by `packages/Crm/Admin/src/Resources/assets/js/directives/tooltip.js`. **Before building anything new, check whether this directive already fits the job** (see step 3) — reusing it keeps the UI consistent with every other tooltip in the admin panel and is less code than a bespoke solution.
- The 4 KPI cards were already given `kpi-card`, `kpi-card--{success|danger|info|warning}`, `kpi-icon-chip`, and `kpi-accent-bar` classes in a previous pass (ambient color-glow animation) — build on that same markup rather than re-touching unrelated parts of the card.

---

## 3. Step 1 (do this first): decide which path to take

1. **Read the existing tooltip directive**: open `packages/Crm/Admin/src/Resources/assets/js/directives/tooltip.js`. Check specifically:
   - What shape of value does it expect (a plain string? `{ content, position }`? an options object)?
   - Does it already skip showing itself when the bound text is empty/false/undefined? (Most tooltip directives do — this matters for step 4.)
   - Does it already have any "only show if the element is overflowing" logic baked in? (Some polished tooltip directives do this automatically for exactly this use case — if so, you may be able to use it with zero extra truncation-detection code.)
2. **Find real usage examples**: `grep -rn "v-tooltip" packages/Crm/Admin/src/Resources/views/` — read 2–3 hits to learn the actual attribute syntax used elsewhere in this app.
3. **Confirm the dashboard is inside the Vue mount root.** `v-tooltip` only works on elements Vue actually hydrates. Check `packages/Crm/Admin/src/Resources/views/components/layouts/index.blade.php` (or wherever `window.app.mount(...)` / `#app` is defined) to see what portion of the page Vue controls. If the dashboard content sits inside that mounted region, `v-tooltip` will work directly in `dashboard/index/*.blade.php`. If it doesn't (e.g. Vue is only mounted for specific islands like the calendar), use the framework-free fallback in §5 instead — don't fight the architecture to force Vue onto a page it doesn't own.

**Decision:** if `v-tooltip` is usable and reasonably simple to bind conditionally → use §4 (Option A, Vue directive). Otherwise, or if you want zero risk of it interacting oddly with the existing directive → use §5 (Option B, framework-free). Both produce the same visible behavior; pick whichever is less code given what you find in step 1.

---

## 4. Option A — reuse the existing `v-tooltip` directive

Bind the tooltip conditionally so it's inert when the text isn't truncated. Example (adapt the exact `v-tooltip` value shape to whatever step 3.1 revealed — this assumes it accepts a plain string and treats an empty/falsy value as "don't show"):

```blade
<p
    class="kpi-value truncate text-2xl font-bold text-gray-900 dark:text-white"
    x-data="{ isTruncated: false }"
    x-init="isTruncated = $el.scrollWidth > $el.clientWidth"
    :v-tooltip="isTruncated ? '{{ $formattedFullValue }}' : null"
>
    {{ $formattedValue }}
</p>
```

If this Blade view has no Alpine/reactive layer available and is plain Vue-hydrated markup instead, do the truncation check in a small mounted/directive hook rather than inline `x-init` — the point is the same either way: **compute `el.scrollWidth > el.clientWidth` once the element has real layout, and only pass a real value into `v-tooltip` when that's true, re-checking on resize.** A minimal vanilla helper that works regardless of what templating layer wraps it:

```js
function bindConditionalTooltips(selector = '[data-tooltip-source]') {
    document.querySelectorAll(selector).forEach((el) => {
        const update = () => {
            const truncated = el.scrollWidth > el.clientWidth + 1;
            el.dataset.tooltipActive = truncated ? '1' : '0';
            // if v-tooltip reads from a data-* attribute or needs a Vue
            // reactive prop instead, wire it here per what you found in step 1.
        };
        update();
        new ResizeObserver(update).observe(el);
    });
}
```

---

## 5. Option B — framework-free fallback (works no matter what wraps the dashboard)

A small, dependency-free tooltip that only appears when the target is actually overflowing. Drop the JS into the dashboard's own script section (or a new small file under `assets/js/`, registered in `app.js` the same way the other directives are) and the CSS into `app.css`.

**Important nuance:** `text-overflow: ellipsis` only *visually* clips text — it never removes characters from the DOM. So `el.textContent` on a truncated element already holds the full, untruncated string; you don't need to duplicate the value into a `data-*` attribute unless the visible text is a shortened/rounded version of a more precise underlying value (e.g. showing `₹107,00…` for a number that's actually rendered in full elsewhere) — in that case, add `data-full-text="{{ $preciseFullValue }}"` and prefer it over `textContent` when present.

### 5a. JS (`assets/js/kpi-tooltip.js`, then import it from `app.js` like the other directives)

```js
export default function initKpiTruncationTooltips(root = document) {
    let tooltipEl = document.querySelector('.kpi-tooltip');

    if (!tooltipEl) {
        tooltipEl = document.createElement('div');
        tooltipEl.className = 'kpi-tooltip';
        tooltipEl.setAttribute('role', 'tooltip');
        document.body.appendChild(tooltipEl);
    }

    function isTruncated(el) {
        return el.scrollWidth > el.clientWidth + 1;
    }

    function position(el) {
        const rect = el.getBoundingClientRect();
        const tipRect = tooltipEl.getBoundingClientRect();
        let top = rect.top - tipRect.height - 8;
        let flipped = false;

        if (top < 8) {
            top = rect.bottom + 8;
            flipped = true;
        }

        const left = Math.min(
            Math.max(rect.left + rect.width / 2 - tipRect.width / 2, 8),
            window.innerWidth - tipRect.width - 8
        );

        tooltipEl.style.top = `${top}px`;
        tooltipEl.style.left = `${left}px`;
        tooltipEl.classList.toggle('kpi-tooltip--flipped', flipped);
    }

    function show(el) {
        if (!isTruncated(el)) return;

        tooltipEl.textContent = el.dataset.fullText || el.textContent.trim();
        tooltipEl.classList.add('kpi-tooltip--visible');
        position(el);
    }

    function hide() {
        tooltipEl.classList.remove('kpi-tooltip--visible');
    }

    root.querySelectorAll('[data-truncate-tooltip]').forEach((el) => {
        if (el.dataset.tooltipBound) return;
        el.dataset.tooltipBound = '1';

        el.addEventListener('mouseenter', () => show(el));
        el.addEventListener('mouseleave', hide);
        el.addEventListener('focus', () => show(el));
        el.addEventListener('blur', hide);

        // Re-check on resize (covers zoom, sidebar collapse, viewport changes);
        // if the tooltip is open and the element stops being truncated, close it.
        new ResizeObserver(() => {
            if (tooltipEl.classList.contains('kpi-tooltip--visible') && !isTruncated(el)) {
                hide();
            }
        }).observe(el);
    });
}
```

In `app.js`, alongside the existing directive imports:
```js
import initKpiTruncationTooltips from "./kpi-tooltip";
document.addEventListener('DOMContentLoaded', () => initKpiTruncationTooltips());
```

### 5b. CSS (append to `app.css`)

```css
/* =========================================================
   KPI card — truncated value/label hover preview
   ========================================================= */
.kpi-tooltip {
    position: fixed;
    z-index: 9999;
    max-width: min(320px, 90vw);
    padding: 6px 10px;
    border-radius: 6px;
    background: #111827; /* gray-900 */
    color: #f9fafb;
    font-size: 12px;
    line-height: 1.4;
    font-weight: 600;
    white-space: normal;
    word-break: break-word;
    box-shadow: 0 8px 20px -6px rgb(0 0 0 / 0.35);
    opacity: 0;
    transform: scale(0.92) translateY(2px);
    transform-origin: bottom center;
    pointer-events: none;
    transition: opacity 120ms ease-out, transform 120ms ease-out;
}

.kpi-tooltip::after {
    content: '';
    position: absolute;
    left: 50%;
    bottom: -4px;
    width: 8px;
    height: 8px;
    background: inherit;
    transform: translateX(-50%) rotate(45deg);
}

.kpi-tooltip--flipped {
    transform-origin: top center;
}

.kpi-tooltip--flipped::after {
    top: -4px;
    bottom: auto;
}

.kpi-tooltip--visible {
    opacity: 1;
    transform: scale(1) translateY(0);
}

.dark .kpi-tooltip {
    background: #f9fafb;
    color: #111827;
}

@media (prefers-reduced-motion: reduce) {
    .kpi-tooltip {
        transition: opacity 80ms linear;
        transform: none !important;
    }
}
```

### 5c. Blade markup — mark which elements should get the preview

Add `data-truncate-tooltip` (and `truncate`/`overflow-hidden whitespace-nowrap` if not already present) to the label and the value inside each of the 4 cards:

```blade
<p class="truncate text-xs font-medium uppercase tracking-wide text-gray-500" data-truncate-tooltip>
    {{ __('admin::app.dashboard.average-lead-value') }}
</p>

<p class="kpi-value truncate text-2xl font-bold text-gray-900 dark:text-white" data-truncate-tooltip>
    {{ core()->formatBasePrice($stats['average_lead_value']) }}
</p>
```

No `data-full-text` is needed here since the full formatted value is already the element's real text content — the ellipsis is purely visual.

---

## 6. Which option to pick

**Recommendation: Option B**, unless step 3 shows the existing `v-tooltip` directive already has overflow-detection built in (in which case Option A is less code and keeps this consistent with the rest of the app's tooltips). Reasoning: this dashboard partial's exact relationship to the Vue mount root is unconfirmed from here, and Option B has zero dependency on that — it works identically whether the markup is inside or outside Vue's hydrated region, and it's a small, self-contained addition that matches the bespoke styling already used for the KPI cards' color system. Don't build both — pick one path per step 3's findings.

A simpler third option exists if timeline matters more than visual polish: set the native `title="{{ $fullValue }}"` attribute via the same `isTruncated()` check (title only when actually truncated, so it never doubles up with anything). Zero CSS, zero positioning bugs, works on mobile long-press and screen readers for free — but it looks like a plain OS tooltip, not a styled one matching the rest of the dashboard. Mention this to whoever's reviewing the change in case "good enough, ship it" beats "on-brand tooltip" for this particular round.

---

## 7. QA checklist

- [ ] At 100% zoom, with content that fits, hovering a card shows **no** tooltip.
- [ ] Zoom in (e.g. Ctrl/Cmd + repeatedly to 150–200%) until a value/label visibly truncates with `…` — hovering it now shows the full text in the preview.
- [ ] Zoom back out — the same element no longer shows a tooltip on hover (truncation is re-evaluated live, not just once on page load).
- [ ] Keyboard: Tab to a truncated element — the preview appears on focus, not just mouse hover; Tab away or blur hides it.
- [ ] Tooltip never gets clipped off-screen at the edges (test a card near the left/right/top edge of the viewport).
- [ ] Tooltip appears above the ambient glow/animated border from the earlier KPI card work (correct z-index, not hidden behind anything).
- [ ] Dark mode: tooltip contrast is legible.
- [ ] `prefers-reduced-motion: reduce` — tooltip still appears/disappears, just without the scale/fade motion.
- [ ] No layout shift when the tooltip appears (it's `position: fixed`, appended to `<body>`, so it can't push card content around).
- [ ] Rebuild (`npm run build` inside `packages/Crm/Admin`) and hard-refresh before testing — this is a CSS/JS-only change, so nothing shows until assets are rebuilt (and redeployed, if testing the live site rather than local).

---

## 8. Rollback

Everything here is additive: remove the `data-truncate-tooltip` attributes (or the conditional `v-tooltip` bindings), the new JS file/import, and the `.kpi-tooltip*` CSS block. Nothing else is touched.
