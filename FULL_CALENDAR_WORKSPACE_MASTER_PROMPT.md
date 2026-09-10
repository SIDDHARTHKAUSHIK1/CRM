# 🗓️ FULL CALENDAR WORKSPACE — MASTER PROMPT
## CRM Real Estate · Activities Module · Full Interactive Calendar

---

## 🎯 OBJECTIVE

When the user clicks the **"Full Calendar Workspace"** button in the Activities page segmented switcher, the `#container-calendar-workspace` div becomes visible. Currently the Vue component (`ActivitiesCalendar.vue`) only has a **working Week view** — the Day, Month, and Year views show a "Coming soon" placeholder, and several critical UX features from the reference screenshot are missing.

**Your goal is to rebuild `ActivitiesCalendar.vue` from scratch** so it is a **pixel-perfect, fully functional, Google-Calendar-style calendar** with all 4 views working, a rich dark sidebar, live event rendering, event detail panel, and the ability to create new activities — all fed from the real CRM activity data already passed as props.

---

## 📁 PROJECT CONTEXT & FILE LOCATIONS

This is a **Laravel 10 + Vue 3 + Tailwind CSS** CRM (Real Estate) project using a packages-based architecture.

| File | Path |
|------|------|
| **Vue Calendar Component** | `packages/Crm/Admin/src/Resources/assets/js/components/ActivitiesCalendar.vue` |
| **Blade View (Activities Index)** | `packages/Crm/Admin/src/Resources/views/activities/index.blade.php` |
| **Activity Controller** | `packages/Crm/Admin/src/Http/Controllers/Activity/ActivityController.php` |
| **JS App Entry** | `packages/Crm/Admin/src/Resources/assets/js/app.js` |

### How the component is mounted
The Vue component is registered and called in the blade view like this:
```blade
<!-- packages/Crm/Admin/src/Resources/views/activities/index.blade.php, line ~683 -->
<div id="container-calendar-workspace" class="hidden space-y-6">
    <v-activities-calendar
        initial-month="{{ $calendarInitialMonth ?? now()->format('Y-m') }}"
        :initial-activities='@json($calendarActivities ?? [])'
        :users='@json($users ?? [])'
        :leads='@json($leads ?? [])'
        :current-user-id="{{ $currentUserId ?? 0 }}"
    ></v-activities-calendar>
</div>
```

The view is shown/hidden by `switchMainView('calendar')` in the blade's vanilla JS — **no page reload occurs**.

### Activity Data Shape (each activity object in `initialActivities`)
```json
{
  "id": 123,
  "title": "VIP Penthouse Tour with Dr. Ramesh",
  "type": "meeting",       // "call" | "meeting" | "lunch" | "task" | "note"
  "comment": "Discuss payment plan and floor selection",
  "location": "DLF Tower B, Level 14",
  "is_done": 0,            // 0 = pending, 1 = completed
  "schedule_from": "2026-09-07 09:00:00",
  "schedule_to":   "2026-09-07 10:30:00",
  "lead_id": 45,
  "lead_title": "Skyline Penthouse Deal",
  "user_id": 3,
  "user_name": "Ravi Sharma"
}
```

### Activity Type Color System (must match existing CRM colors)
| Type | Tailwind BG | Border-Left | Text | Hex |
|------|------------|------------|------|-----|
| `call` | `bg-blue-100` | `border-blue-500` | `text-blue-800` | `#3b82f6` |
| `meeting` | `bg-purple-100` | `border-purple-500` | `text-purple-800` | `#8b5cf6` |
| `lunch` | `bg-amber-100` | `border-amber-500` | `text-amber-800` | `#f59e0b` |
| `task` / `note` | `bg-emerald-100` | `border-emerald-500` | `text-emerald-800` | `#10b981` |

---

## 🖼️ REFERENCE DESIGN — MATCH THIS LAYOUT EXACTLY

The target UI looks exactly like the **macOS Calendar app** / **Google Calendar** with these key regions:

```
┌────────────────────────────────────────────────────────────────────────────────┐
│  LEFT SIDEBAR (dark, ~280px wide, bg-slate-900)                               │
│  ┌──────────────────────────────────────────┐                                 │
│  │  ◀  February 2021  ▶                     │ ← Mini month navigator          │
│  │  Su Mo Tu We Th Fr Sa                    │                                 │
│  │   1  2  3  4  5  6  7                    │ ← Clickable mini calendar       │
│  │  ...   [27]  ← today in orange circle    │   Days with events show dots    │
│  └──────────────────────────────────────────┘                                 │
│                                                                                │
│  TODAY 2/27/2021  55°/40° ☀                                                   │
│  ● All-Hands Company Meeting (orange badge)                                   │
│    8:30–9:00 AM  Monthly catch-up                                             │
│    8:30–9:00 AM  Quarterly review                                             │
│         https://zoom.us/j/link...                                             │
│                                                                                │
│  TOMORROW 2/28/2021                                                            │
│    ● 8:30–9:00 AM  Visit to discuss improvements                              │
│    ● 8:30–9:00 AM  Presentation of new products...                           │
│                                                                                │
│  MONDAY 3/1/2021                                                               │
│    ● 8:30–9:00 AM  City Sales Pitch                                           │
│                                                                                │
│  (scrollable list of upcoming dates & events)                                 │
│                                                                                │
├────────────────────────────────────────────────────────────────────────────────┤
│  TOP NAV BAR (light, white)                                                    │
│  ◀  Today  ▶   [Date Range Title]   [Day] [Week★] [Month] [Year]  🔍  IST+5:30│
├────────────────────────────────────────────────────────────────────────────────┤
│  WEEK VIEW GRID (main content area)                                            │
│                                                                                │
│        │ SUN 21 │ MON 22 │ TUE 23 │ WED 24 │ THU 25 │ FRI 26 │ SAT 27 │    │
│  7 AM  │        │        │        │        │        │        │        │    │
│  8 AM  │        │ 8:00AM │        │        │        │        │        │    │
│        │        │Monday  │        │        │        │        │        │    │
│        │        │Wake-Up │        │        │        │        │        │    │
│  9 AM  │        │ 9:00AM │ 9:00AM │ 9:00AM │ 9:00AM │        │ 9:00AM │    │
│        │        │All-Team│ Design │Webinar │Coffee  │        │Coffee  │    │
│        │        │Kickoff │Review  │Figma…  │Chat    │        │Chat    │    │
│ 10 AM  │        │10:00AM │        │        │10:00AM │        │        │    │
│        │        │Financ. │        │        │Health  │        │        │    │
│        │        │Update  │        │        │Benefits│        │        │    │
│  ...   │  ...   │  ...   │  ...   │  ...   │  ...   │  ...   │  ...   │    │
│                                                                                │
│  Each event block = colored pill with border-left, time + title truncated     │
└────────────────────────────────────────────────────────────────────────────────┘
```

---

## ✅ FULL FEATURE REQUIREMENTS

### 1. LAYOUT & WRAPPER
- Outer: `flex h-[calc(100vh-180px)] overflow-hidden rounded-2xl border border-slate-200 dark:border-gray-800`
- **Left sidebar**: fixed `w-72`, `bg-slate-900 text-white`, own vertical scrollbar
- **Right main area**: `flex-1 flex flex-col bg-white dark:bg-gray-900`
- Separated by `border-r border-slate-700`

---

### 2. LEFT SIDEBAR — DARK PANEL

#### 2A. Mini Month Calendar
- Month name + year in large bold text, `◀` / `▶` navigation
- 7-column grid: `Su Mo Tu We Th Fr Sa` header in `text-slate-400 text-[10px] uppercase`
- Each day cell: small `h-8 w-8` rounded button
  - Today → `bg-orange-500 text-white rounded-full font-bold`
  - Selected → `bg-orange-600 text-white rounded-full`
  - Has events → 1–3 tiny colored dots below number
  - Other month → `text-slate-600 opacity-50`
  - Click → selects that date AND jumps the main view to that week/day
- Always 6 rows × 7 cols = 42 cells (pad with prev/next month days)

#### 2B. Upcoming Events List (scrollable)
Groups by date label (TODAY, TOMORROW, MONDAY, TUESDAY, ...):
```
TODAY 9/7/2026  ☀
● All-Hands Company Meeting   ← featured orange badge on first event of today
  8:30 – 9:00 AM  Monthly catch-up
  
  8:30 – 9:00 AM  Quarterly review
  https://zoom.us/j/link...    ← location/zoom link, truncated gray text
```
- Colored dot per event matching type color
- Click event → open right-side detail panel
- Show max 5 date groups (today + 4 next dates with events)

---

### 3. TOP NAVIGATION BAR

```
◀   Today   ▶     [Period Title]     [Day][Week][Month][Year]   🔍 Search   IST GMT+5:30
```
- `◀` / `▶`: advance/retreat by 1 period (week in Week view, day in Day view, month in Month view)
- **Today**: jump to current date
- **Period title** (center, `text-base font-bold text-slate-900`): e.g. `September 1 – 7, 2026`
- **View switcher**: active = `bg-orange-500 text-white`, inactive = `border border-slate-300 text-slate-600`
- **Search**: filters events client-side (title + comment + location)
- **Timezone**: `IST GMT+5:30` (hardcoded)

---

### 4. MAIN CALENDAR VIEWS

#### 4A. WEEK VIEW (default)
- **8 columns**: narrow time col (~52px) + 7 day columns
- **Sticky day header row** (`bg-slate-50 dark:bg-gray-800`):
  - Day name (SUN/MON/...) in `text-xs text-slate-500 uppercase`
  - Day number in `text-2xl font-bold`
  - Today's column: `bg-orange-50 dark:bg-orange-950/20`, date in `text-orange-500`
- **Time rows**: 7 AM – 8 PM, each **60px tall**, `border-b border-slate-100`
  - Time labels right-aligned: `text-xs text-slate-500`
  - Column dividers: `border-r border-slate-100`
  - Click empty cell → `window.openScheduleModal(dateStr)`
- **Event pills** absolutely positioned:
  - `top = (minutes / 60) * 60px`
  - `height = max(30px, (durationMins / 60) * 60px)`
  - `left: 2px; right: 2px`
  - Colored with `border-l-4`, rounded, `p-1`, `text-xs`, `cursor-pointer`
  - Shows: time + title (truncated)
  - Hover: slight `opacity-80` + shadow
  - Click → opens detail panel

#### 4B. DAY VIEW
- Full-width time grid (7 AM – 8 PM, 60px rows)
- Header: `Monday, September 7, 2026` in bold
- 2 columns: time labels + single day (full width)
- Events same as Week view but wider
- Click empty slot → `window.openScheduleModal(dateStr)`

#### 4C. MONTH VIEW
- 7 cols × 5–6 rows, header: `SUN | MON | ...`
- Each cell:
  - Day number in top-right; today → `bg-orange-500 text-white rounded-full w-7 h-7 flex items-center justify-center`
  - Up to 3 event pills: `bg-TYPE-100 text-TYPE-800 text-[10px] rounded px-1 truncate`
  - `+N more` link if > 3 events → tooltip/popover listing all
  - Click day number → switch to Day view for that date
  - Click event pill → opens detail panel

#### 4D. YEAR VIEW
- 12-month grid (3 cols × 4 rows desktop, 2×6 tablet)
- Each month = mini calendar (same style as sidebar mini cal)
- Days with events show a colored dot
- Click month → switches to Month view
- Click a day → switches to Day view for that date

---

### 5. EVENT DETAIL SLIDE-IN PANEL

Slides in from right inside the main area (`transition-transform`), `w-80 bg-white dark:bg-gray-900 border-l border-slate-200`:

```
✕ [Close]

🏢  [Type Icon]
VIP Penthouse Tour with Dr. Ramesh

🕐  9:00 AM – 10:30 AM
    Monday, September 7, 2026
📍  DLF Tower B, Level 14
📋  Skyline Penthouse Deal   ← lead title
👤  Ravi Sharma              ← assigned user
💬  Discuss payment plan...  ← comment

Status: ⏳ Scheduled   [or ✓ Completed]

[Edit Activity]  [Mark Complete]  [Delete]
```

- **Edit**: opens `window.openScheduleModal()` (or pre-filled inline form) via AJAX PUT to `admin/activities/edit/{id}`
- **Mark Complete**: AJAX PUT `{is_done: 1}` — updates local Vue state instantly
- **Delete**: AJAX DELETE `admin/activities/delete/{id}` — removes from local `activities` ref

---

### 6. DATA MANAGEMENT & REACTIVITY

#### Initial load
- `initialActivities` prop → `const activities = ref(props.initialActivities || [])`

#### Month navigation → lazy-load
```javascript
axios.get('/admin/activities/calendar-events', {
    params: { month: 'YYYY-MM' }
}).then(res => { activities.value = res.data.data; })
```
(The `calendarEvents` endpoint already exists in `ActivityController.php`)

#### AJAX pattern (reuse existing approach)
```javascript
axios.put(`/admin/activities/edit/${id}`, payload, {
    headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
        'X-Requested-With': 'XMLHttpRequest'
    }
})
```

---

### 7. VISUAL POLISH

- **Today indicator line**: horizontal red line at current time in Week/Day views, updated every minute
  ```javascript
  const nowTop = computed(() => {
      const now = new Date();
      return (now.getHours() - 7) * 60 + now.getMinutes(); // pixels from 7AM
  });
  ```
- **Loading state**: overlay skeleton on main grid while fetching month data
- **Empty state**: `"No activities scheduled. Click a time slot to add one."` when no events
- **Smooth transitions**: `transition-all duration-200` on view switches
- **Event overlap**: side-by-side layout (each gets proportional width, e.g., 50/50 split) when 2+ events overlap same time slot on same day
- **Dark mode**: main area `dark:bg-gray-900`, grid lines `dark:border-gray-800`, text `dark:text-white`
- **Custom scrollbar** (matches existing project CSS): 6px wide, `bg-slate-300` thumb

---

### 8. ACTIVITY TYPE EMOJI MAP
```javascript
const typeEmoji = { call: '📞', meeting: '🏢', lunch: '🍽️', task: '📋', note: '📝' };
```

---

### 9. EXISTING CODE TO PRESERVE & INTEGRATE

The blade file already has:
- `window.openScheduleModal(prefillDate)` — call this when clicking empty slots
- `window.closeScheduleModal()` — existing close handler
- `switchMainView('calendar')` — shows/hides the calendar container
- `toggleActivityStatus(id, currentStatus, btn)` — AJAX toggle pattern to replicate in Vue

**Do NOT modify the blade file** unless strictly necessary.

---

### 10. COMPONENT PROPS (do not change)
```javascript
const props = defineProps({
    initialMonth:      String,
    initialActivities: Array,
    users:             Array,
    leads:             Array,
    currentUserId:     Number
});
```

---

### 11. AXIOS & CSRF SETUP (add at top of script)
```javascript
axios.defaults.headers.common['X-CSRF-TOKEN'] =
    document.querySelector('meta[name="csrf-token"]')?.content;
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
```

---

## 🚀 IMPLEMENTATION STEPS

1. **Rebuild** `packages/Crm/Admin/src/Resources/assets/js/components/ActivitiesCalendar.vue` entirely using Vue 3 `<script setup>` + Composition API + Tailwind CSS
2. **Verify** component is registered in `app.js` as `v-activities-calendar`
3. **Run** `npm run dev` (or `npm run build`)
4. **Test** by clicking the "Full Calendar Workspace" button on the Activities page

---

## ✔ FINAL VERIFICATION CHECKLIST

- [ ] Week view renders all events with correct time/position
- [ ] Sidebar mini calendar shows correct month with event dots
- [ ] Sidebar upcoming list shows today + future events (up to 5 groups)
- [ ] Day view renders full-width time grid with events
- [ ] Month view classic grid with event pills and "+N more"
- [ ] Year view 12-month mini grid with event dots
- [ ] Click any event → slide-in detail panel opens with all fields
- [ ] Mark Complete → AJAX, no reload, updates panel status
- [ ] Delete → AJAX, no reload, removes event from grid
- [ ] Navigate month → lazy-loads fresh data from `calendarEvents` API
- [ ] Search filters events in real time (client-side)
- [ ] Click empty slot → `window.openScheduleModal(dateStr)` is called
- [ ] Today indicator red line at correct time in Week/Day views
- [ ] Dark mode correct throughout
- [ ] Smooth transitions between views
- [ ] Loading skeleton while fetching
- [ ] Empty state message when no events

---

*Generated by Antigravity AI · Full codebase analysis of Laravel 10 + Vue 3 + Tailwind CSS CRM · 2026-09-07*
