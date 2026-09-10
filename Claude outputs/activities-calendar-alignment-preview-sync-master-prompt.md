# 🗓️ Master Prompt — Fix Calendar Alignment, Add Date-Preview Popover, Fix Live Marking

Paste this whole prompt, as-is, into whichever AI coding assistant works on this codebase (Claude Code, Cursor, Windsurf, Copilot Chat, etc.). It is self-contained and describes real, already-diagnosed bugs in this exact file — don't re-derive the architecture from scratch, use what's below.

---

## 1. Objective

`ActivitiesCalendar.vue` (the Full Calendar Workspace) has three problems to fix, in order of priority:

1. **Visual alignment / overlap** — in Week and Day view, event chips for the same day get squeezed so tight they visually collide and their text is unreadable. Sidebar mini-calendar dots and Year-view dots sit awkwardly close to the day number. Fix the layout so nothing ever visually overlaps, at any screen width, with any number of events on a day.
2. **Click-to-preview on a "marked" date** — clicking a date that has activities (in Month view, Year view, or the sidebar mini calendar) currently jumps straight into full Day view. Instead, it should first open a small, fast preview popover showing that day's events (time, title, type, status) with a way to open the full day or add another activity — without losing the current view.
3. **New activities must mark every view instantly** — when a user schedules a new activity, the date must show as "marked" (dot/pill) in Month, Week, Day, Year view *and* the mini calendar right away, with no full page reload and no missed views.

---

## 2. Project context

Laravel + Vue 3 (Composition API, `<script setup>`) + Tailwind CRM, packages-based architecture (Krayin-style).

| Piece | Path |
|---|---|
| Calendar Vue component (all 3 fixes happen mostly here) | `packages/Crm/Admin/src/Resources/assets/js/components/ActivitiesCalendar.vue` |
| Activities page Blade (schedule modal, mounts the component) | `packages/Crm/Admin/src/Resources/views/activities/index.blade.php` |
| Controller (`index`, `calendarEvents`, `store`) | `packages/Crm/Admin/src/Http/Controllers/Activity/ActivityController.php` |
| Routes | `packages/Crm/Admin/src/Routes/Admin/activities-routes.php` |

Key existing pieces you must reuse, not duplicate:
- `typeEmoji` map, `getMonthEventPillClasses(type)`, `formatTime()`, `formatTimeRange()`, `formatFullDate()` — already defined in `ActivitiesCalendar.vue`; the popover and any new UI must use these, not invent new formatting/colors.
- The existing event detail slide-in panel (`selectedEvent`, template block "4. EVENT DETAIL SLIDE-IN PANEL") is the source of truth for a *single* event's full details — the new date-preview popover is a *lighter, day-level summary* that links into this panel, it does not replace it.
- `openAddModal(dateStr)` (calls `window.openScheduleModal`) and `selectEvent(ev)` already work correctly and must keep working exactly as-is from inside cell bodies / event chips.

---

## 3. Diagnosed root causes (verified in the current code — fix these specifically)

### 3.1 Alignment / overlap (Week + Day view)
`getPositionedEventsForDate()` (~line 1046) does correct greedy interval-graph column packing, but then divides the day-column width evenly by `totalCols` as a **percentage** (`width = 100 / totalCols`, `left = colIdx * width`, ~line 1096). With narrow day columns (Week view container is only `min-w-[760px]` for 7 columns, ~line 306) and 2+ concurrent events, chips shrink to ~50px and their text is fully truncated, and because the gutter is a `-1%` width trick rather than a real pixel gap, chips read as touching/overlapping.

Sidebar mini calendar dots (~line 87, `absolute -bottom-0.5`) and Year view dots (~line 619, `absolute bottom-0.5`) are absolutely positioned right under the day-number circle with very little clearance, so on the "today"/"selected" filled circle the dot can visually merge into the circle's ring.

### 3.2 No date-preview popover exists yet
Every "click a date" path currently does something else already:
- Month view date-number button → `jumpToDay(cell.dateStr)` (~line 526) — jumps straight to Day view.
- Month view cell body → `openAddModal(cell.dateStr)` (~line 512) — opens the add-activity modal.
- Year view day cell → `jumpToDay(cell.dateStr)` (~line 606).
- Mini calendar cell → `selectMiniDate(cell.dateStr, cell.isOtherMonth)` (~line 72/1312) — just selects the date, no preview.

There is no lightweight "here's what's on this day" popover anywhere — you're building this from scratch, not modifying a broken one.

### 3.3 New activities don't reliably mark every view
- The schedule modal (`packages/Crm/Admin/src/Resources/views/activities/index.blade.php`, `<form action="{{ route('admin.activities.store') }}" method="POST">` ~line 596) is a **plain, non-AJAX form**. Submitting it does a full browser navigation/reload. `ActivityController::index()` (~line 48) does re-query *all* activities on that reload, so the data itself isn't the problem — but the reload throws away `currentView`, `selectedDate`, and which tab (Feed vs Calendar) was open, so the user lands somewhere else and reasonably concludes "my new event isn't showing."
- `todayDateStr` and the initial `selectedDate` (~lines 816–817) are computed with `new Date().toISOString().split('T')[0]`, which is the **UTC** calendar date, not the local (Asia/Calcutta, UTC+5:30) date. Between roughly 00:00–05:29 IST every day, this returns *yesterday's* date, so "today"/the default selected date — and therefore which day gets the "today" ring and which day a same-day new activity appears to belong to — can silently be off by one. The `initialMonth` prop default (~line 781) has the same bug.
- Contrast this with `toggleStatus()` and `deleteEvent()` (~lines 1374–1421), which mutate the shared `activities` ref directly and correctly — proving that once a record is in `activities.value`, **every** view updates instantly, because `monthViewGridCells`, `miniCalendarCells`, `yearViewMonths`, `weekDays`/`getPositionedEventsForDate`, and `upcomingGroupedDates` all read from the same `filteredActivities` computed. The fix for "add" is to get new activities into that same pipeline the same way — not a bigger redesign.

---

## 4. Requirements

### A. Fix the layout so nothing overlaps
- Week/Day view: give concurrently-overlapping events a **real pixel gap** (e.g. 3–4px) between columns instead of the percentage `-1%` hack, and enforce a **sensible minimum chip width** (e.g. ~90px). When more events overlap at once than can fit at that minimum width, do **not** keep shrinking — cap the visible columns (e.g. 3) and show a small "+N" overflow indicator/chip for that time slot (same spirit as Month view's "+N more"), clicking it opens the day's preview popover (see part B) already scrolled/focused to that time.
- Increase the Week view's minimum width proportionally if needed so 7 day columns stay comfortably readable at the capped chip width — don't let the grid get narrower than what the capped chips need.
- Mini calendar and Year view: make sure the dot cluster never touches or overlaps the day-number circle or an adjacent cell's border in any state (default / today / selected) — add clearance, and re-check contrast of dots against the filled "today"/"selected" circle color in both light and dark mode.
- Re-check Month view cells too (event chip stack vs. the "+Add" label and the day-number circle at the top of the cell) for any pixel-level collisions at the smallest supported cell height, even though it currently looks broadly fine.

### B. Click-to-preview popover on any marked date
Add a new small popover component (e.g. `DayPreviewPopover` or inline in `ActivitiesCalendar.vue`, your call) triggered by clicking the **date number** in:
- Month view (replaces the direct `jumpToDay` call on that button specifically),
- Year view day cells (replaces the direct `jumpToDay` call),
- Mini calendar cells (in addition to `selectMiniDate`, which should still run so the mini calendar stays in sync).

Do **not** change what clicking an event chip does (`selectEvent`, still opens the existing slide-in detail panel) or what clicking empty cell space does (`openAddModal`).

Popover content, for the clicked date:
- Header: full formatted date (reuse `formatFullDate`-style logic), "Today"/"Selected" badge if applicable.
- If the day has activities: a compact scrollable list (cap visible height, e.g. ~4–5 rows before scrolling) of that day's events — type emoji (`typeEmoji`), time (`formatTime`), title, a small status pill (done/scheduled, matching the slide-in panel's badge styling). Clicking a row calls the existing `selectEvent(ev)` (opens the full detail panel; popover can close).
- If the day has none: a simple "No activities scheduled" state.
- Footer actions: **"Open full day"** (runs the same logic `jumpToDay` used to run) and **"+ Add activity"** (calls `openAddModal(dateStr)`).
- Behavior: positioned near the clicked element (or a small centered sheet on narrow/mobile widths), closes on outside click, on `Escape`, and when another date is clicked. Only one popover open at a time. Must work in both light and dark mode and match existing card styling (`rounded-2xl`/`rounded-xl`, `shadow-xl`, `border-slate-200 dark:border-gray-800`, etc.) — it should look native to this component, not bolted on.

### C. New activities must mark every view immediately, with no reload
1. Convert the schedule modal's submission (`index.blade.php` ~line 596, `route('admin.activities.store')`) to an AJAX request (axios/fetch), matching the style already used for `toggleStatus`/`deleteEvent` in `ActivitiesCalendar.vue`. Keep the plain `<form>` only as a no-JS fallback if you want, but the real path must be AJAX.
2. Make the `store` endpoint return the created activity as JSON in the **same shape** already used for `calendarActivities`/`calendar-events` (`id`, `title`, `type`, `comment`, `location`, `is_done`, `schedule_from`, `schedule_to`, `lead_id`, `lead_title`, `user_id`, `user_name`).
3. Wire that response into the calendar without a reload — e.g. dispatch `window.dispatchEvent(new CustomEvent('activity:created', { detail: activity }))` from the blade-side success handler, and in `ActivitiesCalendar.vue`'s `onMounted()`, listen for it and `activities.value.push(event.detail)`. Because every computed view already derives from `activities`/`filteredActivities`, this one push is enough to mark Month, Week, Day, Year, and the mini calendar simultaneously — don't build a separate sync mechanism per view.
4. After a successful create, navigate the calendar to the new activity's date (same effect as `jumpToDay`/`selectMiniDate`) so the user visibly sees it land, instead of having to go find it.
5. Fix the timezone bug: add one shared local-date helper (e.g. `todayYMD()` built from `getFullYear()`/`getMonth()`/`getDate()`, not `toISOString()`), and use it for `todayDateStr`, the initial `selectedDate`, and the `initialMonth` prop default — replace all `new Date().toISOString()...` usages in `ActivitiesCalendar.vue` with it.
6. If any other UI on the Activities page creates/edits activities outside this component, apply the same "push into the shared `activities` ref, no reload" treatment so nothing else can get out of sync with the calendar.

---

## 5. Explicitly do NOT change
- The event detail slide-in panel's content/actions (`selectedEvent`), the schedule modal's fields, the Activities data grid/export, the Feed List view, or the stat tiles — out of scope. Only the calendar's layout/spacing, the new date-preview popover, and the activity-creation sync path should change.
- Don't rename or restructure existing computed properties/functions (`monthViewGridCells`, `miniCalendarCells`, `yearViewMonths`, `getPositionedEventsForDate`, `weekDays`, `jumpToDay`, `selectMiniDate`, `openAddModal`, `selectEvent`) — extend/call them, don't replace their names, since other code may depend on them.

## 6. Acceptance checklist
- [ ] In Week/Day view, no two event chips ever visually touch or overlap, at any zoom/width, no matter how many events overlap in time (overflow becomes a "+N" chip, not shrinking text).
- [ ] Mini calendar and Year view dots never overlap the day number or an adjacent cell border, in light and dark mode, today/selected/default states.
- [ ] Clicking a date number in Month view, Year view, or the mini calendar opens the new preview popover (not an immediate jump to Day view); clicking an event chip still opens the existing full detail panel; clicking empty cell space still opens the add-activity modal.
- [ ] The popover correctly lists all of that day's events with working time/status/emoji, has working "Open full day" and "+ Add activity" actions, and closes on outside click / Escape / picking another date.
- [ ] Creating a new activity immediately shows its mark in Month, Week, Day, Year view and the mini calendar — with zero full-page reload and no need to manually revisit each view.
- [ ] `todayDateStr`/default `selectedDate`/`initialMonth` all use local (Asia/Calcutta) date logic, not `toISOString()`, and "today" is highlighted correctly at all hours.
- [ ] Nothing outside the calendar component's layout, the new popover, and the activity-creation sync path was modified.
