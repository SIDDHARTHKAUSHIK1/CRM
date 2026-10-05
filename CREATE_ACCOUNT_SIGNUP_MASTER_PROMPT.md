# MASTER PROMPT — Self-Serve "Create Account" (Sign Up) for the Real Estate CRM

> Paste everything below this line into your AI coding agent, with the repo root open.

---

## 0. Role & ground rules

You are a senior Laravel 12 engineer working in an existing CRM built on the Krayin package architecture (namespace `Crm\*`, packages under `packages/Crm/*`). Your job is to make the **"Create Account"** button on the login card do real work: a visitor signs up, the system creates a **new isolated workspace (Tenant)** with them as its Administrator, saves everything in the database, logs them in and sends them to the dashboard.

Rules:
- **Read before you write.** Open every file named below before changing it. Follow the code style already in the repo (Pint config in `pint.json`, the existing controller and route patterns).
- **Reuse, don't rebuild.** Multi-tenancy already exists. Do NOT create a second tenant system, a second `users` table or a second auth guard.
- Make the **smallest set of changes** that delivers the feature. Don't touch unrelated packages, the landing app (`landing/`) or the WhatsApp gateway.
- Every DB write for one signup happens inside **one transaction**. A failed signup must leave no half-built tenant behind.
- Don't commit or push. At the end, list every file you created or changed.

---

## 1. What already exists (verify each item first)

| Piece | Location | Notes |
|---|---|---|
| Login page with the Create Account button | `packages/Crm/Admin/src/Resources/views/sessions/login.blade.php` (around section `<!-- 7. CREATE ACCOUNT BUTTON -->`) | Button is currently `href="/#contact"`, a placeholder. Styles `.login-glass-card`, `.glass-input-wrap`, `.btn-electric-blue`, `.btn-create-account` live in the `@push('styles')` block of this file. |
| Anonymous layout | `<x-admin::layouts.anonymous>` | Used by login and forgot-password. Reuse it. |
| Auth routes | `packages/Crm/Admin/src/Routes/Admin/auth-routes.php` | Guest routes sit inside `Route::withoutMiddleware(['user'])->group(...)`. |
| Login controller | `packages/Crm/Admin/src/Http/Controllers/User/SessionController.php` | Guard is `auth()->guard('user')`. `status == 0` means the account is blocked. Updates `last_login_at`. |
| Tenant model | `packages/Crm/Core/src/Models/Tenant.php` | Table `tenants` (`id`, `name`, `status`, timestamps). Tenant #1 = "Default Tenant". |
| Tenant context | `packages/Crm/Core/src/TenantContext.php`, helper `current_tenant_id()` in `packages/Crm/Core/src/Http/helpers.php` | Resolves tenant from the logged-in user. Has `setTenantId()` and `reset()`. |
| Global scope | `Crm\Core\Traits\BelongsToTenant` + `Crm\Core\Scopes\TenantScope` | Auto-fills `tenant_id` on create when a tenant is resolved. Does nothing for guests. |
| **Provisioner** | `packages/Crm/Core/src/Services/TenantProvisioner.php` → `provision($name, $adminEmail, $adminPassword, $adminName)` | Already, in a transaction: creates the Tenant, Administrator and Employee roles, the admin User (`status = 1`, `view_permission = global`, `password_plain` encrypted) and copies pipelines/stages, sources, types, attributes and email templates from tenant 1. **This is the core of signup. Call it, don't duplicate it.** |
| CLI equivalent | `packages/Crm/Core/src/Console/Commands/CreateTenantCommand.php` | Reference for how the provisioner is called. |
| User model | `packages/Crm/User/src/Models/User.php` | Uses `BelongsToTenant`. `users.email` is **globally unique**. Keep it that way, because login looks users up by email only. |
| Tenant migrations | `database/migrations/2026_09_12_00000{1..4}_*.php` | New migrations go in `database/migrations/` too. |
| Translations | `packages/Crm/Admin/src/Resources/lang/en/app.php` | Add new strings here. |

If anything in this table doesn't match the code, stop and report the difference before continuing.

---

## 2. User flow to build

1. Visitor clicks **Create Account** on the login card → `GET /admin/register` (route name `admin.register.create`).
2. They see a signup card styled exactly like the login card (same glass card, inputs, electric-blue button, same background and layout).
3. Form fields:
   - **Full name** (required, max 100)
   - **Company / workspace name** (required, max 100). This becomes the Tenant name.
   - **Work email** (required, valid email, max 191, `unique:users,email`)
   - **Phone** (optional, max 20, digits, spaces, `+`, `-` only)
   - **Password** (required, min 8, at least one letter and one number, `confirmed`)
   - **Confirm password**
   - **Agree to Terms** checkbox (required, `accepted`)
   - Hidden **honeypot** field (e.g. `website`). If it's filled, silently reject.
4. Submit → `POST /admin/register` (`admin.register.store`).
5. Server validates, then calls `TenantProvisioner::provision(...)` and saves the extra tenant fields (section 3), all inside one `DB::transaction`.
6. On success:
   - If `crm.signup.auto_activate = true` (default): log the new admin in with `auth()->guard('user')->login($user)`, call `TenantContext::reset()` then `TenantContext::setTenantId($user->tenant_id)`, set `last_login_at`, regenerate the session, flash a success message and redirect to `admin.dashboard.index`.
   - If `auto_activate = false`: set the tenant `status = 'pending'` and user `status = 0`, don't log in, redirect to login with the flash "Your account was created and is awaiting approval." (The existing `status == 0` check in `SessionController@store` already blocks login, so don't change it.)
7. On failure: back to the form with errors and old input (never re-fill the passwords).
8. A logged-in user who visits `/admin/register` is redirected to the dashboard, the same way `SessionController@create` does it.
9. On the signup page, add a link "Already have an account? **Sign in**" pointing to `admin.session.create`.

---

## 3. Database changes

Create **one** new migration: `database/migrations/2026_10_05_000001_add_signup_fields_to_tenants_and_users.php`

**`tenants` table**: add, with `Schema::hasColumn` guards like the existing tenant migrations:
- `slug` string(120), nullable, **unique**. Generate from the company name with `Str::slug`. On a collision, append `-2`, `-3`, and so on.
- `owner_user_id` unsigned int, nullable, indexed (the admin created at signup)
- `contact_email` string(191), nullable
- `contact_phone` string(20), nullable
- `signup_source` string(30), default `'admin'`. Use `'self_signup'` for this flow, and leave existing rows at the default.
- `signup_ip` string(45), nullable
- `trial_ends_at` timestamp, nullable. Set to `now()->addDays(config('crm.signup.trial_days'))` when trial_days > 0, otherwise null.

**`users` table:**
- `phone` string(20), nullable (skip it if a phone column already exists)
- `email_verified_at` timestamp, nullable (only if it doesn't exist yet)

`down()` drops exactly these columns and the unique index.

Then:
- Add the new tenant columns to `Tenant::$fillable`, and add `casts` for `trial_ends_at` (datetime). Add an `owner()` belongsTo relation to `User`.
- Add `phone` to `User::$fillable`, and `email_verified_at` to casts.
- **Backfill** in the same migration: set tenant 1's `slug` to `'default'` if it's null.

**Extend the provisioner without breaking its current callers** (the CLI command must keep working):
- Add an optional final parameter `array $extra = []` to `TenantProvisioner::provision()`.
- Inside the existing transaction, after the tenant and admin are created, apply `$extra` to the tenant (`slug`, `contact_email`, `contact_phone`, `signup_source`, `signup_ip`, `trial_ends_at`, `status`), set `owner_user_id = $adminUser->id`, and set the admin's `phone` and `status` if they're passed in.
- Don't change anything else in the provisioner.

---

## 4. Config

Create `config/crm-signup.php` (or add a `signup` key to an existing CRM config if there's a natural home for it; check `config/` first) and read values with `config('crm.signup.*')` or the matching key you choose:

```php
return [
    'enabled'       => env('CRM_SIGNUP_ENABLED', true),
    'auto_activate' => env('CRM_SIGNUP_AUTO_ACTIVATE', true),
    'trial_days'    => env('CRM_SIGNUP_TRIAL_DAYS', 14),
    'notify_email'  => env('CRM_SIGNUP_NOTIFY_EMAIL'), // optional: who gets a "new signup" email
];
```

Add these keys, commented, to `.env.example`. When `enabled` is false: both register routes return 404, and the login card shows the original "Request Demo" link (`/#contact`) instead of pointing at the register page.

---

## 5. Backend files

**Create:**
1. `packages/Crm/Admin/src/Http/Requests/RegisterRequest.php`: all the validation from section 2, plus friendly messages taken from the translation file. Trim and lowercase `email`. Trim the names.
2. `packages/Crm/Admin/src/Http/Controllers/User/RegisterController.php`
   - `create()`: abort 404 when disabled, redirect when already logged in, otherwise return `view('admin::sessions.register')`.
   - `store(RegisterRequest $request, TenantProvisioner $provisioner)`: honeypot check, build `$extra`, call `provision()`, then log in or redirect as described in section 2. Wrap it in try/catch: log the exception with `report($e)`, flash a generic error, and redirect back with input. Never show raw exception text to the visitor.
   - After a successful commit, if `notify_email` is set, send a simple notification ("New workspace: {company}, {email}"). Queue it if a queue is configured. The default `.env` uses `QUEUE_CONNECTION=sync`, so it must not slow down or break signup if mail fails: wrap it in try/catch.
3. **Optional, behind a flag, off by default:** a welcome email to the new admin. If you add it, reuse the mail layout the forgot-password email uses.

**Edit:**
- `packages/Crm/Admin/src/Routes/Admin/auth-routes.php`: inside the existing `withoutMiddleware(['user'])` group, add:
  ```php
  Route::controller(RegisterController::class)->prefix('register')->group(function () {
      Route::get('', 'create')->name('admin.register.create');
      Route::post('', 'store')->middleware('throttle:5,1')->name('admin.register.store');
  });
  ```
  The throttle allows 5 attempts per minute per IP. The repo already uses `throttle:` middleware elsewhere.
- Confirm the final URL by running `php artisan route:list --name=register`. It should sit under the same admin prefix as `/admin/login`.

---

## 6. Frontend files

**Create** `packages/Crm/Admin/src/Resources/views/sessions/register.blade.php`:
- Start by **copying the structure and the `@push('styles')` block of `login.blade.php`**, so the glass card, inputs, autofill fix, button styles, background and responsive behaviour match exactly. Then replace the form body.
- Use the same `<x-admin::form>` / `<x-admin::form.control-group>` components and the same error display as the login view (`<x-admin::form.control-group.error>`). Use `@csrf` and `action="{{ route('admin.register.store') }}"`.
- Input icons follow the login pattern: user (name), building (company), mail (email), phone, lock (password ×2). Add a show/hide toggle on the password fields if the login view has one.
- Show a small live **password-strength hint** (min 8, letter + number) with plain JS or a tiny Vue snippet, whichever the login view already uses.
- Disable the submit button and show "Creating your workspace…" while submitting, to prevent double submits.
- Title slot: `Real Estate CRM — Create Account`.
- The page must fit the viewport like the login page does (login sets `html, body { height: 100vh; overflow: hidden }`). On short screens, let the card scroll internally instead of clipping the button.
- Check it in light mode, and in dark mode if the anonymous layout supports it.

**Edit** `login.blade.php`: change the Create Account anchor from `href="/#contact"` to:
```blade
href="{{ config('crm.signup.enabled') ? route('admin.register.create') : '/#contact' }}"
```
(Use whichever config key you chose in section 4.) Leave the "Request Demo" footer link alone. Make no other visual changes to the login card.

**Translations:** add every new label, placeholder, success, error and validation message under a new `admin::app.sessions.register.*` key group (or match how the login strings are grouped), and use `@lang(...)` / `trans(...)` in the view and request.

---

## 7. Security checklist (all required)

- CSRF on the form (Laravel default).
- `throttle:5,1` on POST, plus the honeypot field.
- Passwords hashed with `bcrypt` inside the provisioner. Never log the password or put it in a flash message, the session or an exception message.
- Validate `unique:users,email` at the DB validator level. Also handle the race condition: catch `QueryException` with a duplicate-key error and return a friendly "email already registered" message.
- Call `$request->session()->regenerate()` after login.
- A new tenant must **see zero rows from any other tenant**. After signup, verify that leads, contacts, quotes, products and users lists are empty apart from the seeded config (pipelines, sources, types, attributes, templates).
- The new admin must not be able to reach tenant 1 data through any URL with an ID (spot-check `/admin/leads/view/{id-from-tenant-1}`; it should return 404).

---

## 8. Tests

Add `tests/Feature/RegisterTest.php`. Use the existing phpunit setup and look at `phpunit.xml` for the test DB. Cover:
1. The register page loads for guests (200) and redirects logged-in users.
2. A valid signup creates exactly 1 tenant, 2 roles, 1 user and the copied pipelines, all with the new `tenant_id`. The user is authenticated and redirected to the dashboard.
3. `tenants.owner_user_id`, `slug`, `signup_source = 'self_signup'` and `trial_ends_at` are set correctly.
4. A duplicate email fails validation and creates no new tenant.
5. A weak password or mismatched confirmation fails.
6. A filled honeypot creates nothing.
7. With `auto_activate = false`, the user's status is 0, they aren't logged in, and logging in with those credentials is blocked with the activation warning.
8. With `enabled = false`, both routes return 404.
9. Isolation: a lead created by tenant 1 is not visible to the new tenant's admin.
10. The CLI `CreateTenantCommand` still works (the provisioner is backward compatible).

---

## 9. Run & verify

```bash
php artisan migrate
php artisan optimize:clear
php artisan route:list --name=register
php artisan test --filter=RegisterTest
# rebuild admin assets only if you touched JS/CSS that Vite compiles:
cd packages/Crm/Admin && npm run build
```

Manual check:
1. Click **Create Account** on `/admin/login` → the form appears in the same visual style.
2. Sign up as "Test Realty", test@example.com → you land on an empty dashboard for the new workspace.
3. Log out, then log in as the original tenant-1 admin → none of Test Realty's data is visible, and the reverse holds too.
4. In the DB: `SELECT id,name,slug,status,owner_user_id,signup_source,trial_ends_at FROM tenants ORDER BY id DESC LIMIT 1;`

**Production (Hostinger VPS):** `update.sh` / `deploy.sh` must run `php artisan migrate --force`. Check that they do, and add it if missing. Set the `CRM_SIGNUP_*` values in the server `.env`. If `MAIL_*` is still the mailhog default, leave `notify_email` empty until SMTP is set up.

---

## 10. Deliverable

When you're done, reply with:
1. A list of every created or modified file with a one-line purpose.
2. The migration's columns.
3. The test results.
4. Anything in section 1 that didn't match the actual code, and how you handled it.
