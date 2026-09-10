# Master Prompt — Standalone "Admin Panel" Sidebar Section (Employee Oversight: Create, View, Edit, Password Reset/Reveal, Switch Account, Delete) for this Krayin-based Laravel CRM

Paste everything below to your coding agent as-is (Claude Code, Cursor, etc.), in the root of this repo, with full file read/write access.

---

## Step 0 — Check what already exists before writing anything

This repo already contains `ADMIN_EMPLOYEE_SYSTEM_MASTER_PROMPT.md` at the project root, which specs a similar employee-oversight feature nested under Settings → Users → Employees. Before writing any code:

- Check whether that prompt was already implemented. Look for: `packages/Crm/Admin/src/Http/Controllers/Settings/EmployeeController.php`, an `EmployeeDataGrid.php`, a `password_plain` column on `users` (check `packages/Crm/User/src/Database/Migrations/` for a migration adding it), a `last_login_at` column, and an ADMIN/EMPLOYEE badge in the header (`components/layouts/header/index.blade.php`).
- Report what you find before making changes.
- **If that work already exists:** your job here is to (a) move it out from under "Settings" into its own new top-level sidebar section (described below), (b) add full **edit** capability for name/email/role/status if it isn't already there, (c) add the **switch-account / impersonate** feature described below, which was not in the original prompt, (d) confirm the **create-employee** action (see Feature 2 below) is reachable only from this new section and only by an admin. Do not rebuild what already works — extend it.
- **If none of it exists yet:** build the whole thing fresh, directly under the new top-level section below. Do not nest it inside Settings at all.

---

## Context (read these real files before writing anything)

Same Laravel 12 / Krayin-package-architecture CRM described in `ADMIN_EMPLOYEE_SYSTEM_MASTER_PROMPT.md` — re-read that file's "Context" section in full; it already documents the exact files, columns, and conventions you need (`User.php`, `Role.php`, `Bouncer.php`'s `permission_type === 'all'` definition of "admin", `acl.php`, `menu.php` format, `settings-routes.php`, the single shared login form, dark-mode conventions, translation files, and `deploy.sh`/`update.sh`). Do not diverge from any convention documented there. In particular:

- **`Bouncer.php`'s check (`$role->permission_type === 'all'`) is still the one and only definition of "admin" anywhere in this feature.** Never hardcode a role name or `role_id == 1`.
- The existing `menu.php` is a flat, sorted array of entries with `key`, `name`, `route`, `sort`, `icon-class`. Find the entry for `key = 'settings'` and note its exact `sort` value — you'll need it below.
- Find the actual sidebar-rendering Blade/Vue file (search for where the admin panel loops over `menu()->getAdminMenu()` or equivalent — likely under `packages/Crm/Admin/src/Resources/views/components/layouts/`) — this is the file where the hard visibility check goes, not just the menu config.
- Look at `UserController::store()` (the existing "Settings → Users" create action) for the exact validation rules and repository call used to create an account today — reuse that logic rather than writing a second, divergent create-user code path.

---

## Goal

A brand-new **top-level sidebar item**, separate from "Settings" and positioned **immediately above it**, labeled **"Admin Panel"** (rename if you find a better fit with this app's existing naming style — check other top-level labels for tone/casing first). This item — and everything inside it, including creating new employee accounts — must be:

- Completely absent from the DOM for any employee account (`permission_type !== 'all'`), not just hidden by CSS or grayed out.
- Reachable and fully visible for any admin account, regardless of what that admin's role is named.
- **Not** gated the same way ordinary Settings sub-pages are (a checkbox in a custom role's permission list). Gate it by the hardcoded `permission_type === 'all'` boolean, exactly like the ADMIN/EMPLOYEE badge does — an admin must never be able to accidentally grant an employee role access to this section via the normal role/permission UI. Still register the matching route-protection ACL keys as a defense-in-depth layer underneath, the same double-gate pattern (`ACL key` + explicit `abort(403)` in the controller) already required by `ADMIN_EMPLOYEE_SYSTEM_MASTER_PROMPT.md`.

## Placement mechanics

- Add a new top-level `menu.php` entry with its own `key` (e.g. `admin_panel`), a `sort` value one less than the `settings` entry's current sort value (shift nothing else — just slot in directly above it), its own icon (check the existing icon font for something distinct from the settings gear icon), and its own route group prefix (e.g. `admin.panel.*`), separate from `admin.settings.*`.
- In the sidebar-rendering file you located above, wrap this specific entry's render block in the hardcoded admin check (`auth()->guard('user')->user()->role?->permission_type === 'all'`) in addition to whatever ACL-based filtering already happens generically for every menu item — belt and braces.

## Feature list inside "Admin Panel"

1. **Create a new employee account — admin-only, no exceptions.** This is a distinct, explicit feature, not just an assumption:
   - A "+ Add Employee" button lives only on this new Admin Panel employees page (not duplicated elsewhere), visible only when the hardcoded admin check passes — same rule as the sidebar entry itself.
   - The create form and its submit route (`admin.panel.employees.store` or similar) must reuse the exact validation rules already used by `UserController::store()` (name/email/password/role, unique email, etc.) rather than a second, divergent implementation.
   - **Server-side, independent of the menu being hidden:** the controller method backing this route must start with an explicit check — `if (! auth()->guard('user')->user()->role || auth()->guard('user')->user()->role->permission_type !== 'all') { abort(403); }` — before doing anything else. This must hold even if the ACL config is ever hand-edited to grant a custom role the underlying permission key by mistake; the hardcoded check is the real gate, ACL is the secondary one.
   - No self-registration, no "sign up" flow, no way for an employee (or anyone unauthenticated) to create their own or another account through any route in this app. Grep the whole `packages/Crm/Admin` and `packages/Crm/Installer` trees for any other place a `User` row can be created via a web-facing route, and confirm every one of them is behind the same admin-only check (the Installer's first-run setup, which creates the very first Administrator, is the one legitimate exception — leave that alone).
   - New employee accounts created here default to a `custom`-permission-type role (never `permission_type = 'all'`) unless the creating admin explicitly assigns an admin-level role — surface a clear warning in the UI if they do.

2. **Employees list** — one page, columns: name, username/email, role, status, created date, last login (`last_login_at`, set in `SessionController::store()` on successful login exactly as the earlier prompt specifies), and a work summary (counts of leads/quotes/activities currently owned by that user — confirm the real FK column name in each package's migrations before querying, don't guess).
3. **View** — a read-only detail view of everything above, expanded (e.g. their recent activity list, not just counts).
4. **Edit** — admin can change name, email, role, and status for any employee, using the same validation rules as `UserController::update`. This must work as a full save, not a preview.
5. **Password** — reset (set a brand-new password, always available) and reveal (decrypt and show the current one) using the exact `password`/`password_plain` dual-write pattern, `Crypt::encryptString`/`Crypt::decryptString`, re-authentication-of-the-requesting-admin-before-reveal, rate limiting, and audit-log-on-reveal requirements already fully specified in `ADMIN_EMPLOYEE_SYSTEM_MASTER_PROMPT.md` — reuse that spec verbatim rather than re-deriving it, and reuse the actual columns/endpoints if Step 0 found they already exist.
6. **Switch account (impersonate)** — a "Login as this employee" action that:
   - Stores the current admin's id in session (e.g. `session(['impersonator_id' => auth()->guard('user')->id()])`), then logs the admin panel into the target employee's account (`auth()->guard('user')->loginUsingId($employee->id)`).
   - Shows a persistent, unmissable banner on every page while impersonating ("Viewing as {employee name} — Return to Admin"), styled consistently with the ADMIN/EMPLOYEE badge work.
   - "Return to Admin" logs back into the original admin id from `session('impersonator_id')` and clears that session key.
   - Is blocked from targeting another admin account (or, if you decide to allow it, requires an extra explicit confirmation step — pick one and document your choice in the final summary).
   - Fires a logged event on both start and end of impersonation (who impersonated whom, when), same pattern as the password-reveal audit log.
7. **Delete** — same safeguards already required by the earlier prompt: can't delete the last remaining admin account, can't delete your own account from this page, normal employee delete actually revokes login ability.

## Validation checklist the agent must self-verify before calling this done

- [ ] Logged in as a `custom`-role employee: the "Admin Panel" sidebar entry does not appear anywhere in the rendered HTML (view source, not just visually hidden), the "+ Add Employee" action does not exist for them anywhere, and every `admin.panel.*` route — including the create/store route — 403s or redirects on direct URL access.
- [ ] Logged in as the seeded Administrator: "Admin Panel" appears directly above "Settings" in the sidebar, in both light and dark mode, and "+ Add Employee" successfully creates a working account (new employee can log in with the credentials just set).
- [ ] A newly created employee defaults to a non-admin role unless the creating admin deliberately picked an admin-level one, and that choice surfaced a warning.
- [ ] Edit actually persists (reload the page, changed fields stick) and re-uses the same validation as the existing Users settings page.
- [ ] Password reveal still requires the requesting admin's own password and is rate-limited; setting a new password updates both columns and login works with the new one.
- [ ] Impersonation: starting it logs in as the employee, the return banner is visible on every subsequent page, clicking it restores the original admin session exactly (not a fresh login), and an admin cannot casually be impersonated by another admin unless you deliberately chose to allow it.
- [ ] Deleting the last admin, or your own account, from this page is blocked; deleting a normal employee works.
- [ ] `php artisan migrate` (fresh local) and `php artisan migrate --force` (VPS path) apply cleanly; `npm run build` at root and inside `packages/Crm/Admin` both complete with zero new errors.
- [ ] No literal `localhost` or `realestate.aflix.co.in` string introduced.
- [ ] Every new user-facing string has an `en/app.php` translation key.

## Deliverables

List every file created or changed in your final summary, state clearly whether you built this from scratch or extended existing work found in Step 0, and restate the `APP_KEY`/plaintext-password security caveat from `ADMIN_EMPLOYEE_SYSTEM_MASTER_PROMPT.md` so it isn't lost.
