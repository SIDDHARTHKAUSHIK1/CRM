# Master Prompt — Fix "Manage" button crashing the WhatsApp Broadcast dashboard page

Paste this to your coding agent as-is.

---

## The bug

The "Manage" link on the WhatsApp Broadcast list (`packages/Crm/Admin/src/Resources/views/whatsapp/index.blade.php`) is a plain `<a href="{{ route('admin.whatsapp.show', $campaign->id) }}">` — it navigates correctly. The problem is on the destination page: `show.blade.php` (and `preview.blade.php`, same bug) throws a fatal PHP error while rendering, which is why it looks like the button "doesn't open" anything — the request is failing server-side, not silently doing nothing client-side.

## Root cause

`packages/Crm/Admin/src/Resources/views/components/breadcrumbs/index.blade.php` declares its accepted props as:
```php
@props([
    'name' => '',
    'entity' => null,
    'route' => null,
])
```
It only forwards `$entity` (and `$route`) into `Breadcrumbs::view('admin::partials.breadcrumbs', $name, $route, $entity)`. Every other admin page passes the model as `:entity`, e.g. `packages/Crm/Admin/src/Resources/views/quotes/edit.blade.php`:
```blade
<x-admin::breadcrumbs name="quotes.edit" :entity="$quote" />
```

But the WhatsApp views pass `:campaign` instead of `:entity`:
- `packages/Crm/Admin/src/Resources/views/whatsapp/show.blade.php` line ~27: `<x-admin::breadcrumbs name="whatsapp.show" :campaign="$campaign" />`
- `packages/Crm/Admin/src/Resources/views/whatsapp/preview.blade.php` line ~17: `<x-admin::breadcrumbs name="whatsapp.preview" :campaign="$campaign" />`

Because `campaign` isn't in the component's `@props`, it's silently dropped (Blade treats it as a stray HTML attribute on the component's root `<div>`, not as data passed to the breadcrumb renderer) — so `$entity` stays `null`. But `routes/breadcrumbs.php` registers these two breadcrumbs with a **required** closure parameter:
```php
Breadcrumbs::for('whatsapp.show', function (BreadcrumbTrail $trail, $campaign) { ... });
Breadcrumbs::for('whatsapp.preview', function (BreadcrumbTrail $trail, $campaign) { ... });
```
Calling `Breadcrumbs::view(..., 'whatsapp.show', null, null)` with no entity ends up invoking that closure without the `$campaign` argument it requires, which throws a fatal error and takes down the whole page render (`show`/`preview` never finish rendering — hence "the manage tab doesn't open").

## The fix

In both files, change `:campaign="$campaign"` to `:entity="$campaign"`:

- `packages/Crm/Admin/src/Resources/views/whatsapp/show.blade.php`: `<x-admin::breadcrumbs name="whatsapp.show" :entity="$campaign" />`
- `packages/Crm/Admin/src/Resources/views/whatsapp/preview.blade.php`: `<x-admin::breadcrumbs name="whatsapp.preview" :entity="$campaign" />`

Do not change anything in `routes/breadcrumbs.php`, the breadcrumbs component itself, or the controller — they're all correct as-is; this is purely the two call sites using the wrong prop name.

While you're in these two files, grep the entire `packages/Crm/Admin/src/Resources/views/whatsapp/` folder for any other `<x-admin::breadcrumbs ... :campaign=` occurrences to make sure there isn't a third instance elsewhere, and check `dnc.blade.php`/`create.blade.php`/`gateway.blade.php`/`index.blade.php` too even though they currently pass no entity — confirm none of them were also meant to pass one and got the same typo.

## Verify

- Set `APP_DEBUG=true` temporarily (or check `storage/logs/laravel.log`) and click "Manage" on an existing campaign — before the fix you should see the real error (something like "Too few arguments to function" or "Argument #2 ($campaign) not passed" from inside `Breadcrumbs::view`). After the fix, the dashboard page should render fully instead.
- Click "Manage" from the campaign list and confirm the broadcast dashboard (sent/failed/pending counts, recipient table, Pause/Resume/Cancel/Retry buttons) now loads.
- Go through the create-broadcast flow to the preview screen and confirm that page also loads without error now.
- If `APP_DEBUG` was true only for the source Laravel PHP app anyway, don't leave it on in production — set it back to `false` once confirmed.
