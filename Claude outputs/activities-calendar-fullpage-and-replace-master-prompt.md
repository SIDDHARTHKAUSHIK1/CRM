# 🗓️ Master Prompt — True Full-Page "Full View Calendar" + New Calendar Source Swap

Paste this whole prompt, as-is, into whichever AI coding assistant works on this codebase (Claude Code, Cursor, Windsurf, Copilot Chat, etc.). It is self-contained and describes real, already-diagnosed behavior in this exact codebase — don't re-derive the architecture from scratch, use what's below.

This prompt has **two parts**. Part A is fully specified and ready to implement immediately. Part B (swapping in new calendar source code) is blocked on the actual replacement file — see the note at the top of Part B before doing anything there.

---

## 1. Objective

1. **Part A — Make "Full View Calendar" truly full-page.** Right now, clicking either "Full View Calendar" button just swaps a section's visibility *inside* the existing Activities admin page — the admin sidebar, topbar, and page chrome stay on screen and the calendar is still constrained to `calc(100vh - 180px)`. Change it so clicking "Full View Calendar" makes the calendar workspace cover the entire browser viewport (a true full-page/fullscreen takeover), with a clear way to exit back to the normal page.
2. **Part B — Replace `ActivitiesCalendar.vue` with new calendar source code**, re-aligned to match this project's existing design system (colors, spacing, radii, dark mode, typography) so it looks native, not bolted on.

---

## 2. Project context

Laravel + Vue 3 (Composition API, `<script setup>`) + Tailwind CRM, packages-based (Krayin-style) architecture.

| Piece | Path |
|---|---|
| Calendar Vue component | `packages/Crm/Admin/src/Resources/assets/js/components/ActivitiesCalendar.vue` |
| Activities page Blade (mounts the component, holds the view-switch buttons & JS) | `packages/Crm/Admin/src/Resources/views/activities/index.blade.php` |
| Controller | `packages/Crm/Admin/src/Http/Controllers/Activity/ActivityController.php` |
| Routes | `packages/Crm/Admin/src/Routes/Admin/activities-routes.php` |
| Prior calendar work (alignment/popover/sync, classic layout, redesign) already applied — do not redo | `Claude outputs/activities-calendar-alignment-preview-sync-master-prompt.md`, `activities-calendar-classic-layout-master-prompt.md`, `activities-calendar-redesign-master-prompt.md` |

---

## 3. Part A — Full-page "Full View Calendar" (implement now)

### 3.1 Current behavior (verified in the code)

- Two buttons both call the same function with the same argument:
  - Header segmented control, `id="btn-header-calendar"` (~line 63 of `index.blade.php`)
  - Sub-section button, `id="btn-sub-calendar-toggle"` (~line 267)
  - Both: `onclick="switchMainView('calendar')"`
- `switchMainView('calendar')` (defined ~line 756) just does `calView.classList.remove('hidden')` / `feedView.classList.add('hidden')` and restyles the two buttons. `#container-calendar-workspace` (~line 502) keeps its normal `class="hidden w-full space-y-4"` box, so it renders **inside** the existing `<x-admin::layouts>` page — sidebar, topbar, and page padding are all still visible, and the calendar itself is capped by its own root `class="flex h-[calc(100vh-180px)] min-h-[680px] ..."` (in `ActivitiesCalendar.vue`, ~line 5).
- The calendar workspace already has an exit action — the "✕ Return to Timeline Feed" button (~line 533, `onclick="switchMainView('feed')"`) — reuse it as the full-page exit, don't add a second exit control.
- There's already one global `keydown` listener in the page (~line 1044) that closes the schedule modal on `Escape` — extend that same listener, don't add a second one.

### 3.2 Changes

**A. `ActivitiesCalendar.vue` — give the root element a stable id**

Root template element is ~line 5:

```html
<!-- BEFORE -->
<div ref="calendarContainerRef" class="flex h-[calc(100vh-180px)] min-h-[680px] bg-white dark:bg-gray-900 rounded-2xl border border-slate-200 dark:border-gray-800 shadow-sm overflow-hidden text-slate-800 dark:text-slate-100 font-sans select-none relative">
```

```html
<!-- AFTER -->
<div id="activities-calendar-root" ref="calendarContainerRef" class="flex h-[calc(100vh-180px)] min-h-[680px] bg-white dark:bg-gray-900 rounded-2xl border border-slate-200 dark:border-gray-800 shadow-sm overflow-hidden text-slate-800 dark:text-slate-100 font-sans select-none relative">
```

This id is only used from the blade-side script below to resize the calendar in full-page mode — nothing inside the Vue component itself needs to change.

**B. `index.blade.php` — replace `switchMainView()` with the full-page-aware version**

Replace the whole function (~line 756–782) with:

```js
// Switch main view: 'feed' or 'calendar'
let isCalendarFullPage = false;

// Literal class strings so Tailwind's content scanner picks them up (same
// pattern already used by activeHeaderClass / inactiveHeaderClass below).
const CAL_FULLPAGE_CLASSES = 'w-full space-y-4 fixed inset-0 z-[999] !m-0 !rounded-none p-3 sm:p-6 bg-white dark:bg-gray-900 overflow-y-auto';
const CAL_NORMAL_CLASSES = 'hidden w-full space-y-4';

function switchMainView(mode) {
    const feedView = document.getElementById('container-feed-view');
    const calView = document.getElementById('container-calendar-workspace');
    const btnFeed = document.getElementById('btn-header-feed');
    const btnCal = document.getElementById('btn-header-calendar');
    const btnSubCal = document.getElementById('btn-sub-calendar-toggle');
    const calRoot = document.getElementById('activities-calendar-root');

    const activeHeaderClass = 'px-3.5 py-1.5 rounded-xl bg-white dark:bg-gray-900 shadow-2xs text-xs font-bold text-blue-600 dark:text-blue-400 flex items-center gap-1.5 transition cursor-pointer';
    const inactiveHeaderClass = 'px-3.5 py-1.5 rounded-xl text-xs font-bold text-slate-600 dark:text-slate-300 hover:text-gray-600dark:hover:text-white flex items-center gap-1.5 transition cursor-pointer';

    if (mode === 'calendar') {
        if (feedView) feedView.classList.add('hidden');
        if (calView) calView.className = CAL_FULLPAGE_CLASSES;
        if (btnFeed) btnFeed.className = inactiveHeaderClass;
        if (btnCal) btnCal.className = activeHeaderClass;
        if (btnSubCal) {
            btnSubCal.className = 'px-3.5 py-2 rounded-xl bg-blue-50 text-blue-700 dark:bg-blue-950 dark:text-blue-300 border border-blue-300 dark:border-blue-700 text-xs font-bold transition cursor-pointer flex items-center gap-1.5 shadow-2xs';
        }

        // True full-page takeover: cover the admin sidebar/topbar and lock
        // background scroll while the calendar is open.
        isCalendarFullPage = true;
        document.body.style.overflow = 'hidden';
        if (calRoot) calRoot.style.setProperty('height', 'calc(100vh - 148px)', 'important');

        setTimeout(() => {
            window.dispatchEvent(new Event('resize'));
        }, 50);
    } else {
        if (calView) calView.className = CAL_NORMAL_CLASSES;
        if (feedView) feedView.classList.remove('hidden');
        if (btnFeed) btnFeed.className = activeHeaderClass;
        if (btnCal) btnCal.className = inactiveHeaderClass;
        if (btnSubCal) {
            btnSubCal.className = 'px-3.5 py-2 rounded-xl bg-slate-50 hover:bg-slate-100 dark:bg-gray-800 dark:hover:bg-gray-700 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-gray-700 text-xs font-bold transition cursor-pointer flex items-center gap-1.5 shadow-2xs';
        }

        isCalendarFullPage = false;
        document.body.style.overflow = '';
        if (calRoot) calRoot.style.removeProperty('height');
    }
}
```

Notes on the numbers/values above (tune to taste, they're not magic):
- `z-[999]` — high enough to sit above the admin layout's sidebar/topbar (those normally use low z-index values like `z-10`/`z-20`/`z-30`). If this project has a toast/notification system mounted at an even higher z-index, bump this further so toasts still show above the full-page calendar.
- `calc(100vh - 148px)` — viewport height minus the full-page workspace's own header card (~76px) plus the `p-3 sm:p-6` padding and gaps around it. Adjust by a few px if the header card's actual rendered height differs after you test it.
- `!m-0 !rounded-none` uses this project's existing `!utility` important-prefix convention (already used elsewhere, e.g. `!px-4 !py-2 !rounded-xl` on the "+ Schedule" button in the same file) so the rounded-card look is dropped only while full-page.

**C. `index.blade.php` — exit full-page on `Escape` too**

Extend the existing single `keydown` listener (~line 1044) rather than adding a second one:

```js
// BEFORE
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' || e.key === 'Esc') {
        closeScheduleModal();
    }
});
```

```js
// AFTER
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' || e.key === 'Esc') {
        closeScheduleModal();
        if (isCalendarFullPage) {
            switchMainView('feed');
        }
    }
});
```

### 3.3 Explicitly do NOT change (Part A)

- Don't touch `container-feed-view`, the stat tiles, the filter pills, or the schedule modal markup/logic.
- Don't rename `switchMainView`, `btn-header-feed`, `btn-header-calendar`, `btn-sub-calendar-toggle`, or `container-calendar-workspace` — other code/tests may reference these ids.
- Don't remove the existing "✕ Return to Timeline Feed" button — it's now doing double duty as the full-page exit action; just leave it as-is.
- Don't touch anything inside `ActivitiesCalendar.vue` other than adding the one `id` attribute — its internal layout/alignment was already fixed by the prior master prompts in `Claude outputs/`.

### 3.4 Acceptance checklist (Part A)

- [ ] Clicking either "Full View Calendar" button (header or sub-section) makes the calendar cover the full browser viewport — admin sidebar/topbar are no longer visible.
- [ ] Background page cannot scroll while the calendar is full-page (`document.body` scroll is locked).
- [ ] The calendar itself fills the available full-page height (no dead whitespace, no scrollbar-within-scrollbar unless a specific view genuinely needs it, e.g. Week view's own horizontal scroll).
- [ ] "✕ Return to Timeline Feed" and `Escape` both exit full-page mode cleanly: sidebar/topbar reappear, body scroll is restored, the calendar's height style is removed (falls back to its normal `calc(100vh-180px)` sizing) and the Timeline Feed is shown.
- [ ] Re-entering full-page mode after exiting works repeatedly without drift (no leftover inline styles, no stuck `overflow: hidden` on body).
- [ ] Internal calendar views (Month/Week/Day/Year, popover, slide-in detail panel) all still work identically full-page as they did before — nothing inside `ActivitiesCalendar.vue` was touched except the new `id`.
- [ ] Light and dark mode both look correct full-page (no accidental transparent background showing page content behind it).

---

## 4. Part B — Rebuild `ActivitiesCalendar.vue` on top of DayPilot Lite

### 4.0 What the "new calendar source" actually is, and the scope decision made

The source provided (folder `daypilot-react-date-picker`) is a **React** + Vite demo (`@daypilot/daypilot-lite-react` v5.3.0): one Week view, a date-picker toolbar, and browser-`prompt()`-based add/edit. It doesn't match this project's stack (Vue 3, not React) and has none of the current component's features — no Month/Day/Year views, no mini calendar, no sidebar, no activity types, no dark mode.

**Decision (confirmed with the project owner): full rebuild.** Swap the current hand-built grid/positioning engine for the official Vue port of the same library, **`@daypilot/daypilot-lite-vue`**, and rebuild Month/Week/Day views plus the sidebar mini calendar on top of it — while keeping every existing feature (sidebar shell, search, event detail slide-in panel, day-preview popover, activity types, status toggle, delete, dark mode, the `activity:created` sync, and Part A's full-page toggle) working exactly as before. This is a genuine win beyond "using the new source": DayPilot's Week/Day/Month views pack overlapping events natively, which retires the entire hand-rolled `getPositionedEventsForDate()` column-math that the alignment-preview-sync prompt had to hand-patch — the alignment bug class goes away at the engine level instead of being patched again.

**Year view is explicitly kept as-is, hand-built, unchanged** — DayPilot Lite has no year/agenda view, and building one from scratch is out of scope here.

Everything below was verified against the real package (installed and inspected: `@daypilot/daypilot-lite-vue@5.10.1` and its `daypilot-vue.min.d.ts` / the underlying `@daypilot/daypilot-lite-javascript` type definitions) — the prop/event names are real, not guessed. No separate CSS file needs importing; this version of the library injects its own styles.

### 4.1 Dependency

Add to `packages/Crm/Admin/package.json` → `"dependencies"`:

```json
"@daypilot/daypilot-lite-vue": "^5.10.1",
```

Then, from `packages/Crm/Admin/`:

```bash
npm install
npm run build   # or: npm run dev
```

Import it locally inside `ActivitiesCalendar.vue`'s `<script setup>` (no `app.js`/global registration needed — Vue 3 `<script setup>` resolves imported components automatically in the template):

```js
import { DayPilot, DayPilotCalendar, DayPilotMonth, DayPilotNavigator } from '@daypilot/daypilot-lite-vue';
```

### 4.2 What gets replaced vs. what stays untouched

| Region | Action |
|---|---|
| Sidebar mini calendar grid (`ActivitiesCalendar.vue` ~lines 61–94, the `Su Mo Tu…` header + 7-col button loop, inside the `<aside>`) | **Replace** with `<DayPilotNavigator>` |
| Week view section (~lines 307–417, `v-if="currentView === 'week'"`) | **Replace**, merged with Day view into one `<DayPilotCalendar>` |
| Day view section (~lines 418–516, `v-else-if="currentView === 'day'"`) | **Replace**, merged with Week view into the same `<DayPilotCalendar>` |
| Month view section (~lines 517–591, `v-else-if="currentView === 'month'"`) | **Replace** with `<DayPilotMonth>` |
| Year view section (~line 592 onward, `v-else-if="currentView === 'year'"`) | **Untouched** |
| Header toolbar, view-switcher buttons, search box, timezone pill (~lines 194–290) | **Untouched** — they already call `setView()`/`navigatePeriod()`/`goToToday()`, which keep working |
| Sidebar "Upcoming Events" list (~lines 96–178, driven by `upcomingGroupedDates`) | **Untouched** |
| Event detail slide-in panel, day-preview popover (`selectedEvent`, `activePopover` template blocks) | **Untouched** |
| Schedule modal wiring (`openAddModal`, `openAddModalWithTime`, `window.openScheduleModal`) | **Untouched** — new DayPilot handlers call into these same functions, they don't open their own modal |
| `activities` ref, `filteredActivities`, `activity:created` listener, `toggleStatus`, `deleteEvent`, `fetchMonthEvents` | **Untouched** |
| `typeEmoji`, `getEventDotColor`, `getEventColorClasses`, `getMonthEventPillClasses`, `formatTime`/`formatTimeRange`/`formatFullDate` | **Untouched** — reused by the new DayPilot render hooks below, don't duplicate them |
| Root `id="activities-calendar-root"` from Part A | **Untouched**, keep it on the same root element |

Obsolete once the swap is done (safe to delete — nothing else references them):
`getPositionedEventsForDate()`, `monthViewGridCells`, `miniCalendarCells`, `weekDays`, `todayInWeekView`, `todayWeekIndex`, `currentTimeTop`, `updateCurrentTime()`, `timeInterval`, `hourSlots`. `periodTitle`'s Week-view branch currently reads `weekDays.value[0]`/`[6]` — replace it with the small `weekRange` helper in §4.3 instead of deleting the branch outright.

### 4.3 New shared logic (add to `<script setup>`)

```js
// XSS-safe: DayPilot's event `areas[].html` / `cell.html` are raw HTML, not auto-escaped like `text`.
function escapeHtml(str) {
    if (str === null || str === undefined) return '';
    return String(str)
        .replaceAll('&', '&amp;').replaceAll('<', '&lt;').replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;').replaceAll("'", '&#039;');
}

// Local-time "YYYY-MM-DDTHH:mm:ss" — never toISOString() (UTC), matches this file's existing IST-safety rule.
function formatDateTimeLocal(d) {
    const pad = n => String(n).padStart(2, '0');
    return `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}T${pad(d.getHours())}:${pad(d.getMinutes())}:${pad(d.getSeconds())}`;
}

function addOneHour(dateStr) {
    const d = new Date(dateStr);
    if (isNaN(d.getTime())) return dateStr;
    d.setHours(d.getHours() + 1);
    return formatDateTimeLocal(d);
}

// activities -> DayPilot EventData[], shared by the Week/Day calendar and the Month grid
const dayPilotEvents = computed(() => {
    return filteredActivities.value.map(a => ({
        id: a.id,
        text: a.title || '(untitled)',
        start: a.schedule_from,
        end: a.schedule_to || addOneHour(a.schedule_from),
        backColor: a.is_done ? '#94a3b8' : getEventDotColor(a.type), // slate-400 when done, else existing type color
        borderColor: 'darker',
        fontColor: '#ffffff',
    }));
});

// Custom chip rendering, reusing this file's existing typeEmoji map — shared by Calendar (Week/Day) and Month
function onBeforeEventRender(args) {
    const activity = activities.value.find(a => a.id === args.data.id);
    if (!activity) return;
    const emoji = typeEmoji[activity.type] || '📌';
    const doneMark = activity.is_done ? ' <span style="opacity:.85">✓</span>' : '';
    args.data.html = '';
    args.data.areas = [{
        left: 4, right: 4, top: 3, bottom: 3,
        html: `<div class="dp-event-chip">${emoji} <b>${escapeHtml(activity.title || '')}</b>${doneMark}</div>`,
    }];
}

// Event chip clicked -> reuse the existing slide-in detail panel, don't build a new one
function onCalendarEventClicked(args) {
    const activity = activities.value.find(a => a.id === args.e.data.id);
    if (activity) selectEvent(activity);
}

// Week/Day: clicking/dragging an empty time slot -> same as the old hourly-grid click, straight to the schedule modal
function onCalendarTimeRangeSelected(args) {
    const startDate = new Date(args.start.toString());
    const dateStr = formatDateToYMD(startDate);
    const hour = isNaN(startDate.getHours()) ? 9 : startDate.getHours();
    args.control.clearSelection();
    openAddModalWithTime(dateStr, hour);
}

// Month: clicking a day cell -> the day-preview popover first, NOT straight to the modal
// (preserves the click-to-preview behavior from the alignment-preview-sync master prompt)
function onMonthTimeRangeSelected(args) {
    const dateStr = formatDateToYMD(new Date(args.start.toString()));
    args.control.clearSelection();
    selectMiniDate(dateStr, false);
    openDayPreview(dateStr, null); // no source element to anchor to here — see caveat below
}

// Mini calendar (sidebar) date pick -> same combined select+preview behavior as the old onMiniCellClick
function onNavigatorDateSelected(args) {
    const dateStr = formatDateToYMD(new Date(args.day.toString()));
    onMiniCellClick({ dateStr, isOtherMonth: false }, null);
}

// weekRange replaces weekDays.value[0]/[6] usage inside periodTitle's 'week' branch
const weekRange = computed(() => {
    const curr = parseSafeDate(selectedDate.value);
    const dow = curr.getDay();
    const start = new Date(curr); start.setDate(curr.getDate() - dow);
    const end = new Date(curr); end.setDate(curr.getDate() + (6 - dow));
    return { start, end };
});
```

In `periodTitle`'s `'week'` branch, replace `weekDays.value[0]`/`weekDays.value[6]` with `weekRange.value.start`/`weekRange.value.end` (same date-formatting logic below it stays as-is).

> **Caveat to verify visually:** `openDayPreview(dateStr, null)` (Month click) and the Navigator's date-pick both call `openDayPreview` without a source DOM element to anchor against, so the popover falls back to the top-left of the calendar container instead of appearing next to the clicked cell. Functionally it still works (same content, same "Open full day"/"+ Add activity" actions) — this is a cosmetic follow-up, not a blocker. If you want it anchored, DayPilot's Month click args expose `args.originalEvent` in most versions — pass `{ currentTarget: args.originalEvent?.target }` as the second argument instead of `null` and confirm it positions correctly in your build.

### 4.4 New template blocks

**Week/Day (replaces both the old ~307–417 and ~418–516 blocks with one unified block):**

```html
<div v-if="currentView === 'week' || currentView === 'day'" class="flex flex-col h-full">
    <DayPilotCalendar
        :viewType="currentView === 'day' ? 'Day' : 'Week'"
        :startDate="selectedDate"
        :events="dayPilotEvents"
        :businessBeginsHour="7"
        :businessEndsHour="21"
        heightSpec="Full"
        :durationBarVisible="false"
        timeRangeSelectedHandling="Enabled"
        eventClickHandling="Enabled"
        @timeRangeSelected="onCalendarTimeRangeSelected"
        @eventClicked="onCalendarEventClicked"
        @beforeEventRender="onBeforeEventRender"
    />
</div>
```

**Month (replaces the old ~517–591 block):**

```html
<div v-else-if="currentView === 'month'" class="flex flex-col h-full">
    <DayPilotMonth
        :startDate="selectedDate"
        :events="dayPilotEvents"
        eventClickHandling="Enabled"
        timeRangeSelectedHandling="Enabled"
        @timeRangeSelected="onMonthTimeRangeSelected"
        @eventClicked="onCalendarEventClicked"
        @beforeEventRender="onBeforeEventRender"
    />
</div>
```

**Sidebar mini calendar (replaces the old ~61–94 block, keep the surrounding padding/border wrapper div):**

```html
<DayPilotNavigator
    :startDate="selectedDate"
    selectMode="Day"
    :showMonths="1"
    :skipMonths="1"
    :weekStarts="0"
    @timeRangeSelected="onNavigatorDateSelected"
/>
```

The Year view block (unchanged) simply becomes the next `v-else-if="currentView === 'year'"` after these three.

### 4.5 Dark-theme styling pass (required, needs visual QA — can't be verified without a running browser)

DayPilot ships a default light theme and renders plain DOM with predictable class prefixes per control (`calendar_default_*`, `month_default_*`, `navigator_default_*` — confirmed from this project's own leftover CSS rule `.navigator_default_main` in an unrelated file). Add scoped overrides in `ActivitiesCalendar.vue`'s `<style>` block so the Navigator (sitting in the dark `bg-slate-900` sidebar) and the Month/Week/Day grids (sitting in the light `bg-white dark:bg-gray-900` main pane) match this project's palette in both themes — inspect the actual rendered class names in devtools after `npm run dev` and adjust selectors/colors to match §4.6's tokens; don't ship this without opening it in a browser first.

Also give the injected event chip a matching look:

```css
.dp-event-chip {
    font-family: inherit;
    font-size: 11px;
    font-weight: 700;
    line-height: 1.3;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
```

### 4.6 Project alignment conventions (extracted from the real codebase — use these tokens for every new bit of chrome you add, e.g. wrapper padding around the DayPilot components)

| Token | Value used throughout this project |
|---|---|
| Card corners | `rounded-2xl` (cards/panels), `rounded-xl` (buttons/inputs/pills) |
| Shadows | `shadow-2xs` (resting cards/buttons), `shadow-xl`/`shadow-2xl` (popovers/modals/slide-ins) |
| Borders | `border-slate-200 dark:border-gray-800` (or `/90` opacity variants) |
| Base surface | `bg-white dark:bg-gray-900` |
| Muted surface | `bg-slate-50 dark:bg-gray-800` / `bg-slate-100 dark:bg-gray-800` |
| Primary accent | `red-500` (brand/today marker), `blue-600` (primary actions/links), `orange-500` (focus rings, hover accents) |
| Status colors | `emerald-500` (done/success), `amber-500` (pending/warning) |
| Headings | `font-black tracking-tight text-gray-600dark:text-white` |
| Body/labels | `font-bold`/`font-semibold`, `text-xs` for chrome, `text-sm` for readable content |
| Muted text | `text-slate-500 dark:text-slate-400` |
| Important-prefix overrides | `!px-4 !py-2 !rounded-xl` style (Tailwind `!utility` prefix) |
| Dark mode | Every color utility must have a `dark:` pair |

### 4.7 Explicitly do NOT change (Part B)

- Don't touch `ActivityController.php`, the routes file, or the schedule-modal markup — the prop contract and endpoints are unchanged by this rewrite.
- Don't touch Year view, the header toolbar, search, the sidebar upcoming list, the event detail slide-in panel, the day-preview popover template, `activity:created` sync, or Part A's full-page `id="activities-calendar-root"`/resize logic.
- Don't rename `openAddModal`, `openAddModalWithTime`, `selectEvent`, `selectMiniDate`, `openDayPreview`, `jumpToDay`, `setView`, `navigatePeriod`, `goToToday`, `toggleStatus`, `deleteEvent` — the new DayPilot handlers call into these by name.

### 4.8 Rollout plan (this is the biggest change made to this file — don't do it in one blind pass)

1. Commit/branch before starting — this touches roughly a third of a 2000+ line file.
2. Wire up and test Week + Day view first (`onCalendarTimeRangeSelected`, `onCalendarEventClicked`, `onBeforeEventRender`) — confirm no two events overlap at any width (the original alignment bug), event click opens the real slide-in panel, empty-slot click opens the real schedule modal.
3. Then Month view — confirm date-cell click opens the popover (not the modal directly), event chip click opens the slide-in panel, "+N more"-style overflow is no longer needed (DayPilot handles it) but if it's not showing all events per day check `DayPilotMonth`'s `eventHeight`/`cellHeight` config.
4. Then the Navigator — confirm it lands in the dark sidebar readably (§4.5), date pick updates `selectedDate` and opens the popover.
5. Re-run Part A's full-page acceptance checklist against the new views — DayPilot components must resize correctly when `#activities-calendar-root`'s height changes (the existing `window.dispatchEvent(new Event('resize'))` call after `switchMainView('calendar')` should already trigger DayPilot's own resize handling; verify it does).
6. Only then delete the obsolete functions/computeds listed in §4.2.

### 4.9 Acceptance checklist (Part B)

- [ ] `@daypilot/daypilot-lite-vue` installed and imported locally in `ActivitiesCalendar.vue`; app builds with no errors.
- [ ] Week and Day views render via one `<DayPilotCalendar>`; no two events ever visually overlap at any width (verified — this was the original bug class, now handled by the library).
- [ ] Month view renders via `<DayPilotMonth>`; clicking a date cell opens the day-preview popover (not the modal); clicking an event chip opens the slide-in panel.
- [ ] Sidebar mini calendar renders via `<DayPilotNavigator>`, styled to fit the dark sidebar in both light and dark mode, and date-picking it opens the popover and syncs `selectedDate`.
- [ ] Year view is untouched and still works.
- [ ] Event chips show the correct `typeEmoji`, title, and a done-mark when `is_done`, matching `getEventDotColor`'s palette; no raw/unescaped HTML injection risk (title/comment run through `escapeHtml`).
- [ ] `activity:created` still marks every view (Month/Week/Day/Navigator) instantly with zero reload — no new sync code was needed because everything still derives from the same `activities`/`filteredActivities` refs.
- [ ] Part A's full-page toggle still works with the new views (root id preserved, resize event still fires and DayPilot picks it up).
- [ ] Search, status toggle, delete, the schedule modal, and the upcoming-events list all still work — none of the untouched regions in §4.2 regressed.
- [ ] Obsolete functions/computeds from §4.2 removed only after the above is verified working, not before.
