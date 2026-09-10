# Master Prompt — Redesign the Activities Calendar (Real Estate CRM)

Paste this whole prompt, as-is, into whichever AI coding assistant is working on the codebase (Claude Code, Cursor, Windsurf, Copilot Chat, etc.). It is self-contained.

---

## 1. Project context

This is a Laravel-based CRM (Krayin-style package architecture: `packages/Crm/<Module>/...`) with:

- Backend: Laravel, Blade views, Eloquent (`packages/Crm/Activity/src/Models/Activity.php`, `ActivityRepository.php`, `ActivityController.php`).
- Frontend: Tailwind CSS 3 + Vue 3 mounted inside Blade via the project's existing convention — a `<script type="text/x-template" id="v-xxx-template">` block paired with `app.component('v-xxx-component', { template: '#v-xxx-template', data() {...}, methods: {...} })` in a `<script type="module">` block (see `packages/Crm/Admin/src/Resources/views/activities/edit.blade.php` for the pattern already used elsewhere in this file's own module).
- Relevant JS libraries already installed and available (`packages/Crm/Admin/package.json`): `vue` 3.4, `vue-cal` 4.9, `flatpickr` + `vue-flatpickr`, `mitt`, `vee-validate`. **Prefer these over new dependencies.**
- Target file: `packages/Crm/Admin/src/Resources/views/activities/index.blade.php`. The block to redesign is the "FULL INTERACTIVE REAL ESTATE CALENDAR VIEW" section (`<div id="container-calendar-view">`, roughly lines 600–850), which sits inside the page alongside the "Feed List" view (`#container-feed-view`), the top stat tiles, and the "Quick Activity Logger" widget.

## 2. What's wrong with the current calendar (fix this)

1. **It is not actually a grid.** The month view is coded as `grid grid-cols-7 gap-2` but in real usage it renders as a single vertical column — one full-width block per day, 35 rows deep (Aug 30 → Oct 3), each with a big empty area when there's no event. The weekday header (`Sun Mon Tue Wed...`) also renders as 7 stacked lines instead of 7 columns. Whatever is causing the grid classes to not take effect (Tailwind purge/JIT not picking up the classes, a CSS build issue, or a wrapping element breaking the grid context) must be fixed as part of this redesign — the shipped result must be a **real 7-column grid** on tablet/desktop, not a description of one.
2. **All data is hardcoded.** The `$gridDays` array in the Blade file is a static PHP array of fake dates/events/IDs. Month navigation (`‹`/`›`) just calls `alert('Viewing August 2026 archive')` — it doesn't change anything. "Today" jump calls a search-filter hack, not a real scroll/highlight. None of this is wired to the real `Activity` model/repository.
3. **No fast way to find a date.** With 35 rows (many empty) stacked vertically, a user has to scroll through a huge blank list to find a specific day. There's no compact overview of "which days this month have something on them."
4. **Everything is raw inline JS.** `onclick="..."` handlers and global `function` declarations scattered through the Blade file make this hard to extend or reason about.

## 3. Design reference

A reference dashboard screenshot is attached/available showing the target *feel* (not literal content) for the navigation pattern: a small, compact calendar in the corner/sidebar with the current date marked and small colored dots on days that have events, sitting next to compact stat tiles (events scheduled, tours completed, call success rate, goals met) and a clean vertical timeline of upcoming items with status pills (Completed / Upcoming / Pending) and colored left-border accents per item.

Adapt that *pattern* — mini calendar for fast navigation + colored date markers + tidy timeline — to this app's existing luxury real-estate visual language: white/`gray-900` cards, `rounded-2xl`/`rounded-3xl`, orange (`orange-600`) as the primary accent, existing badge colors for activity types, and full dark-mode support (`dark:` variants throughout, matching the rest of the file).

## 4. What to build

### A. New: compact Mini Calendar Navigator
Add a small calendar (roughly 260–300px wide) as a persistent navigation aid, positioned beside/above the main calendar (e.g., a left column on desktop, collapsible or above the grid on mobile — don't just bury it at the bottom).

- Single month, small day cells (no event text inside — just the day number).
- Days that have ≥1 activity get a small colored dot (or up to 3 stacked dots if multiple activity types that day) under the day number, using the **same color mapping** as the main calendar/legend (blue = Calls, amber = Lunches, purple = Site Tours & Meetings, emerald = Documentation Tasks).
- Today is visually distinct (filled orange circle, matching the main grid's "today" treatment).
- The currently-selected/viewed date is highlighted differently from "today."
- Clicking a date scrolls/jumps the main calendar to that date and highlights it there.
- Small `‹` / month label / `›` controls, plus a one-click "Today" reset — all **functional** (see section C).
- Build this as its own small Vue component (`v-mini-calendar-component` or similar, following this codebase's existing template/component convention) rather than more hardcoded Blade loops, so it can react to the same date/events state as the main view. `flatpickr` (already a dependency, supports inline mode + custom day rendering for dots) or a small custom Vue component are both acceptable — pick whichever integrates more cleanly with the state described below.

### B. Redesign: the main calendar view
- Keep the month-grid concept but make it a genuine, responsive CSS grid: 7 columns from `sm`/`md` breakpoints up. Below that, fall back **intentionally** to a clean agenda list — but only show days that actually have events (plus today), with a sticky/visible date label per group, instead of rendering 30+ empty full-height rows. Do not ship a "collapse into one endless column" result again, whether by accident or by design.
- Trim the excessive empty vertical space on days with no events — empty cells should be visually minimal (small numeral, no big empty content well), so the eye is drawn to days that matter.
- Keep the existing per-type color coding and the legend at the bottom, and make sure the legend colors, the mini calendar dots, and the main grid's event chips all reference one shared color/type map so they can never drift out of sync.
- Keep "today" clearly marked (current orange ring/badge treatment is fine to reuse).
- On hover/focus, a day cell should offer the existing "+ add event on this date" affordance; clicking an event chip still opens/edits that activity.

### C. Make navigation and data real
- Replace the `alert(...)` prev/next handlers and the fake "Today" search hack with real state: track a `currentMonth`/`currentYear` (and `selectedDate`) value, re-render the grid and the mini calendar off it, and update the header title (`#cal-month-title`) and "Active Month" badge accordingly.
- Replace the static `$gridDays` PHP array with real data: either (a) have the repository/controller supply activities for the visible month (grouped by date) as JSON/a Vue prop when the page loads and refetch on month change, or (b) expose a small dedicated endpoint (e.g. `GET /admin/activities/calendar?month=2026-09`) returning `{date, type, title, id}[]` for the requested month, and call it when the user navigates months. Reuse the existing `Activity` model/repository and the same activity-type → color mapping used elsewhere in this file — don't invent a second source of truth for colors.
- "Today" button should jump the visible month back to the real current month and select/scroll to today, not run a text search.

### D. Responsiveness
- Verify at common breakpoints (≈375px, 768px, 1024px, 1440px) that: the mini calendar never overflows or squashes; the main grid is a real 7-column grid wherever it's supposed to be one; and the agenda fallback (if used below `sm`) is compact, not the current wall of empty rows.

### E. Visual polish
- Match existing card chrome (`rounded-2xl`/`rounded-3xl`, `border-slate-200/90`, `shadow-xs`, `dark:bg-gray-900`, `dark:border-gray-800`) so the new pieces look native to this page, not bolted on.
- Keep type sizes/weights consistent with the rest of the page (`text-xs font-black uppercase tracking-wider` for labels, etc.).
- Preserve accessibility basics: sufficient color contrast for the dots/badges in both light and dark mode, and keyboard-reachable day/nav controls (don't rely on hover-only affordances for anything essential).

## 5. Explicitly do NOT change
- The top stat tiles row ("Today's Agenda", "Site Tours & Demos", "Calls & Check-ins", "Total Completion"), the "Quick Activity Logger" widget, the Feed List view, the schedule modal, or the "Activities Data Grid & Export" datagrid at the bottom — these are out of scope. Only the calendar view (`#container-calendar-view`) and its immediate navigation need to change, plus whatever minimal backend endpoint/data change is needed to feed it real data.

## 6. Acceptance checklist
- [ ] Main calendar renders as an actual 7-column grid on tablet/desktop (verified visually, not just in markup).
- [ ] A small mini calendar is visible for quick month navigation, with colored dots on days that have real activities.
- [ ] Prev / Next / Today all work and change what's displayed — no `alert()`s remain.
- [ ] Calendar data reflects real `Activity` records for the selected month, not the hardcoded `$gridDays` array.
- [ ] Empty days are visually compact; a user can see a whole month without excessive scrolling.
- [ ] Colors are consistent across mini calendar dots, main grid chips, and the legend.
- [ ] Dark mode and mobile widths (≥375px) both look correct.
- [ ] Nothing outside the calendar section/its data source was modified.
