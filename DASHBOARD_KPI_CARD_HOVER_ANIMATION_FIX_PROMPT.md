# Dashboard KPI Card — Hover Animation Not Firing — Fix Prompt

**Paste this into a coding agent (or follow it yourself) with write access to the CRM repo. I read your actual `packages/Crm/Admin/src/Resources/assets/css/app.css` and `packages/Crm/Admin/tailwind.config.js` — the previous animation prompt WAS implemented correctly in both files, but there's a real bug in it that explains why nothing animates. Root cause + drop-in fix below.**

---

## 1. The actual bug (confirmed by reading your files)

Your `tailwind.config.js` defines the glow/pulse/shimmer as **Tailwind keyframes** (`theme.extend.keyframes` / `theme.extend.animation`), and `app.css` then references them with plain CSS like:

```css
.kpi-card {
    animation: kpi-card-glow 2.6s ease-in-out infinite;
}
```

This is the bug. Tailwind v3's JIT compiler only ever **emits** a keyframes block into the compiled CSS when it detects the matching `animate-*` utility class (e.g. `animate-kpi-card-glow`) actually being used somewhere in the files listed in `content` (your blade/js files). Since nothing in your markup uses the class `animate-kpi-card-glow` — you reference the keyframe name directly through a hand-written `animation:` property instead — Tailwind treats the whole utility as unused and **strips the `@keyframes` block out of the built CSS entirely**. The browser then sees `animation: kpi-card-glow ...` pointing at a keyframe name that doesn't exist anywhere in the stylesheet, so it silently does nothing — no error, no motion, on any card, on hover or otherwise.

This is why "still no animation" persists even though the CSS you were given was correct on paper: the keyframes it depends on never actually reach the browser.

**The fix:** stop relying on Tailwind's config to generate these keyframes. Write the four `@keyframes` blocks directly in `app.css` as plain CSS, outside of Tailwind's theme system. Hand-authored CSS in that file is never purged, tree-shaken, or content-scanned — it always ships as-is.

---

## 2. Drop-in fix for `app.css`

Find your existing `/* Dashboard KPI cards — ambient color-motion */` section (near the end of the file) and add these four `@keyframes` blocks directly **above** the `.kpi-card` rule — same file, no config changes needed:

```css
/* =========================================================
   Dashboard KPI cards — ambient color-motion
   ========================================================= */

/* Keyframes are hand-written here (not in tailwind.config.js) so Tailwind's
   content-based purge can never strip them — they are referenced directly
   by the `animation:` properties below and must ship verbatim. */
@keyframes kpi-card-glow {
    0%, 100% {
        box-shadow: 0 0 0 1px rgb(var(--kpi-accent) / 0.22), 0 0 0 0 rgb(var(--kpi-accent) / 0);
    }
    50% {
        box-shadow: 0 0 0 1px rgb(var(--kpi-accent) / 0.55), 0 0 16px 2px rgb(var(--kpi-accent) / 0.30);
    }
}

@keyframes kpi-icon-pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.08); }
}

@keyframes kpi-accent-bar {
    0%, 100% { opacity: 0.5; transform: scaleX(0.94); }
    50% { opacity: 1; transform: scaleX(1); }
}

@keyframes kpi-ring-ping {
    0% { box-shadow: 0 0 0 0 rgb(var(--kpi-accent) / 0.45); }
    100% { box-shadow: 0 0 0 14px rgb(var(--kpi-accent) / 0); }
}

.kpi-card {
    position: relative;
    --kpi-accent: 100 116 139;
    transition: transform 200ms ease-out, box-shadow 200ms ease-out, border-color 200ms ease-out;
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
    pointer-events: none;
    z-index: 1;
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

    /* Hover: force this to win regardless of any other hover: utility already
       sitting on the same element — !important is deliberate and scoped
       narrowly to just these three properties, not the whole rule. */
    .kpi-card:hover,
    .kpi-card:focus-within {
        animation-play-state: paused !important;
        transform: translateY(-3px) !important;
        box-shadow: 0 12px 24px -8px rgb(var(--kpi-accent) / 0.35), 0 0 0 1.5px rgb(var(--kpi-accent) / 0.7) !important;
    }

    .kpi-card:hover .kpi-icon-chip,
    .kpi-card:hover .kpi-accent-bar {
        animation-play-state: paused !important;
    }

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
        transform: none !important;
        box-shadow: 0 0 0 1.5px rgb(var(--kpi-accent) / 0.6) !important;
    }
}
```

Replace your existing block with this one (it's the same rules plus the four `@keyframes` and `!important` on the hover declarations, which removes any possibility of a pre-existing Tailwind `hover:` utility on the card winning a specificity tie).

You can leave the `keyframes`/`animation` entries in `tailwind.config.js` in place — they're harmless dead config now — or delete them for cleanliness. They are not required for this to work anymore.

---

## 3. Before you conclude it's still broken — verify these, in order

1. **The 4 card elements actually carry the classes.** Open the dashboard in Chrome, right-click a KPI card → Inspect, and confirm the root `<div>` literally has `kpi-card kpi-card--success` (or `--danger`/`--info`/`--warning`) in its `class` attribute, a child with `kpi-icon-chip`, and a `<span class="kpi-accent-bar">` present. If any of these are missing in the live DOM, the CSS has nothing to attach to — go back to the blade file(s) under `packages/Crm/Admin/src/Resources/views/dashboard/index/` and confirm the markup changes from the original prompt were actually applied, not just the CSS/config.

2. **Assets were rebuilt.** Editing `app.css` alone does nothing until Vite recompiles it:
   ```bash
   cd packages/Crm/Admin
   npm run build
   ```
   (or `npm run dev` while actively testing locally). If you're checking the **live** site (`https://realestate.aflix.co.in/`) rather than a local dev server, the build has to be re-deployed — run your existing `deploy.sh`/`update.sh` — editing files on your machine doesn't change what the VPS is serving.

3. **Laravel's view cache isn't serving a stale blade compile.** If you edited the blade markup:
   ```bash
   php artisan view:clear
   php artisan cache:clear
   ```

4. **Hard-refresh the browser.** `Ctrl+Shift+R` (or open in an incognito window) to bypass a cached CSS bundle — Vite fingerprints filenames on build, but the browser can still hold an old `app.css` in cache if you're testing against a server that isn't cache-busting correctly.

5. **Confirm the rule is actually reaching the page.** DevTools → Elements → select the card → Styles panel → search for `kpi-card`. You should see the rule listed (not greyed out/overridden). Hover the element in DevTools using the "Toggle Element State" (`:hov` pin icon in the Styles pane) to force the `:hover` state and watch the Styles panel update live — this confirms whether the rule matches and wins, independent of your mouse actually being over it.

6. **Quick binary-search debug** (temporary, remove after): add this to the very end of `app.css`, rebuild, and hover a card — if you see a hot pink dashed outline appear on hover, the selector is matching and the build pipeline is fine, so the problem is purely the property values (proceed to check for a conflicting rule elsewhere with a higher-specificity `!important`); if you see nothing, the class isn't on the element or the build isn't picking up your CSS edit at all.
   ```css
   .kpi-card:hover { outline: 4px dashed magenta !important; }
   ```

---

## 4. Definition of done

- Hovering any of the 4 KPI cards visibly lifts it (`translateY(-3px)`) and snaps to a crisper, full-opacity colored border/glow, with the ambient pulse pausing while hovered.
- Moving the mouse away resumes the gentle ambient glow smoothly.
- Confirmed in DevTools that `.kpi-card:hover` is the winning rule (not crossed out by anything else).
- Confirmed on the actual environment you're checking (local dev server or the redeployed live site — not a stale build of either).
