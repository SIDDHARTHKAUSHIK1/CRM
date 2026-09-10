# Master Prompt — Rebuild the Activities/Calendar Layout to Match a Classic CRM Toolbar + Side-Panel Design

Paste this whole prompt into whichever AI coding assistant is working on the codebase. It is self-contained and reflects the *actual current state* of the file, not a guess — read section 1 before making changes.

---

## 1. Current state (verified in the repo — don't rebuild what already works)

Target file: `packages/Crm/Admin/src/Resources/views/activities/index.blade.php` (Laravel Blade + Vue 3, ~2,180 lines). As of now it already has real, working functionality that must be **preserved**, not thrown away:

- A genuine 7-column month grid, driven by Vue state (not hardcoded arrays anymore).
- A compact mini-calendar navigator in a left column next to the main grid.
- Real backend wiring via `axios`: `GET {{ route('admin.activities.calendar_events') }}` to load events, `POST {{ route('admin.activities.store') }}` to create, `PUT {{ route('admin.activities.update', ...) }}` to edit, `DELETE {{ route('admin.activities.delete', ...) }}` to remove.
- A filter-pill row for activity type, a "+ Schedule Activity" modal for creating new activities (fields: `type` [call/meeting/lunch/note], `title`, `lead_id`, `schedule_from`, `schedule_to`, `location`, `comment`), a "Today's Agenda"/upcoming timeline feed, and a color legend.
- Buttons already largely use the project's brand-color system (`.primary-button`, `bg-brandColor`).

This task is a **layout/interaction redesign on top of that existing functionality** — re-arrange, restyle, and extend the UI to match the reference below, reusing the real data and endpoints that already exist. It is not a rebuild from zero, and it must not regress any of the working pieces above.

## 2. Reference layout to match

A reference screenshot (classic CRM calendar) shows this structure — match this *pattern*, adapted to this app's real estate CRM data and existing visual language (cards, brand color, dark mode):

- **Top utility row**: breadcrumb ("Home / Calendar"), then on the right two compact dropdown filters side by side (in the reference: "Filter: All Tasks" and "Viewing Calendar for: Tasks"), plus a help affordance.
- **Calendar toolbar** directly above the grid: rounded pill-style Prev/Next arrow buttons next to a "TODAY" pill button on the left; the current "Month YYYY" title centered; and on the right a **segmented view switcher**: `MONTH | WEEK | DAY | SCHEDULE`.
- **Main grid**: clean 7-column month grid, thin gridlines, weekday header row (Sun–Sat), and each day's events rendered as **solid, full-width colored bars** stacked inside the cell (not small dots or tiny chips) — colored by activity type/status, each bar showing a short label (e.g. "Call – West, Hugh, Young & Rygell").
- **Persistent right-side "Task Details" panel** (not a modal, always part of the page layout): shows the selected item's Title, Details/notes, Activity Date, All Day, and Priority, with a primary "EDIT TASK" button at the bottom. It updates live as you click different dates/events.

## 3. Important constraint — don't fabricate fields that don't exist

The reference panel shows **Priority** and **All Day** fields. The real `activities` table/model (`packages/Crm/Activity/src/Models/Activity.php` fillable list + its migration) only has: `title`, `type`, `location`, `comment`, `additional` (json), `schedule_from`, `schedule_to`, `is_done`, `user_id`. **There is no `priority` and no `all_day` column today.**

- Map the panel to real fields: Title → `title`, Details → `comment`, Activity Date/Time → `schedule_from` (and `schedule_to` if an end time is relevant), Type → `type` (reuse the existing call/meeting/lunch/note options from the current modal), Location → `location`.
- Do **not** silently add fake Priority/All-Day UI that doesn't save anywhere — that recreates the exact "looks real but is fake" problem this page already had once before. If Priority/All-Day are genuinely wanted, treat that as an explicit, separate backend change (new migration + `$fillable` entry + form field) and call it out as optional/follow-up work rather than faking it in this pass.

## 4. What to build

### A. Top utility row
- Keep the existing breadcrumb.
- Replace or sit alongside the current filter-pill row with two compact dropdowns in the reference's style:
  1. **Activity type filter** — "All Activities" plus the real types already used elsewhere in this file (Call, Meeting, Lunch, Note/Task).
  2. **Scope filter** — the reference's "Viewing Calendar for: Tasks" doesn't map 1:1 here. Repurpose it meaningfully: if activities are tied to a `user_id`/owner, use it as an owner/assignee filter (e.g. "My Activities" vs. a specific associate); otherwise keep this dropdown simple and honest about what it actually filters — don't invent people or data that isn't real.
- Keep the existing "+ Schedule Activity" primary button.

### B. Calendar toolbar
- Restyle Prev/Next into small rounded pill/icon buttons flanking a "TODAY" pill, matching the reference. These should call the same month-navigation logic that already drives the real grid — this is primarily a style pass, not new logic.
- Keep the centered "Month YYYY" title.
- Add a real segmented control: `MONTH | WEEK | DAY | SCHEDULE`.
  - **MONTH** = the existing, working grid. Must stay fully functional.
  - **SCHEDULE** = a flat chronological list of activities — the existing "Today's Agenda" / "Earlier this week" timeline sections on this page are the natural source to adapt into this tab rather than building a new list from scratch.
  - **WEEK** and **DAY** = simpler alternate views of the same underlying activity data (a 7-day strip and a single-day agenda respectively). Implement them for real using the data already being fetched, if time allows.
  - If WEEK/DAY can't be fully built in this pass, they must be **honestly non-functional** — e.g. visibly disabled with a "coming soon" affordance — never a button that looks live but silently does nothing.

### C. Main month grid
- Keep the real 7-column grid logic that already exists. Change the *event rendering* from small chips/dots into solid, full-width colored bars per event, stacked inside the day cell, each showing time + a short title (truncate with ellipsis on overflow), colored using the type→color mapping already established in this file (don't invent a new palette — keep it consistent with the legend that already exists on this page).
- Clicking a bar (or an empty day, to start a new one) selects it and drives the panel described below.

### D. New: persistent right-side "Activity Details" panel
- Add a right-hand column beside the main grid (mirroring the existing left-column mini-calendar pattern already used in this file, e.g. a fixed-width `lg:w-[340px]` column), not a modal. Visible whenever a date/activity is selected; show a clear empty state ("Select a date or activity to see details") when nothing is selected.
- Fields, bound to the real `Activity` record: Title (`title`), Type (`type`, as a select with the same options as the existing modal), Location (`location`), Details (`comment`), Activity Date/Time (`schedule_from`/`schedule_to`).
- A primary "Save Changes" / "Edit Task" button (`.primary-button`) that calls the already-existing `admin.activities.update` endpoint. Consider also surfacing the existing delete action here as a secondary/destructive control.
- Decide explicitly whether this panel replaces the current "+ Schedule Activity" modal for **editing** existing activities (recommended — one editing surface, not two), while the modal can stay for **creating** new ones, or vice versa. Don't end up with two different, inconsistent UIs for touching the same record.

### E. Preserve
- The mini-calendar navigator (it's fine to make it collapsible or reposition it if the new right panel makes the layout too wide — just don't delete working functionality without a replacement).
- The "Today's Agenda"/timeline feed content (reuse it for the SCHEDULE tab per section B rather than deleting it).
- The color legend, dark-mode support, and all existing `axios` calls to the real endpoints.
- The project's existing button/color conventions (`.primary-button`, `.secondary-button`, `bg-brandColor`) — don't introduce new ad hoc colors for this redesign.

## 5. Guardrails
- Don't fabricate Priority/All-Day fields that don't persist anywhere (see section 3).
- Don't regress the working month grid, mini calendar, or the real create/update/delete wiring while restyling around them.
- Don't build a second, parallel styling system — reuse the brand-color button classes and existing spacing/radius conventions already in this file.
- Verify in an actual browser after rebuilding assets (`npm run build` in `packages/Crm/Admin`), in both light and dark mode, at mobile/tablet/desktop widths — a three-column layout (mini calendar + grid + details panel) is the most likely thing to break responsively, so check it explicitly at narrow widths (e.g. stack columns vertically below `lg`).

## 6. Acceptance checklist
- [ ] Top row shows working type + scope filter dropdowns, matching the reference's placement.
- [ ] Calendar toolbar has pill-style Prev/Today/Next and a real MONTH/WEEK/DAY/SCHEDULE segmented control; MONTH and SCHEDULE are fully functional; anything not implemented is honestly disabled, not fake.
- [ ] Month grid renders events as solid, full-width colored bars per day, using the existing color mapping.
- [ ] A persistent right-side details panel shows and edits the selected activity's real fields (no fake Priority/All-Day), saving via the existing update endpoint.
- [ ] Mini calendar, timeline feed content, legend, and all existing axios-backed create/update/delete flows still work.
- [ ] Layout checked in both themes and at mobile/tablet/desktop widths, with the three-column layout degrading sensibly on narrow screens.
