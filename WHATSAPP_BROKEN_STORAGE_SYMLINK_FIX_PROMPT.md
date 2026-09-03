# Master Prompt — Brochure/video preview broken, direct file link 404s (broken storage symlink)

Paste this to your coding agent as-is.

---

## Root cause — confirmed directly, not guessed

The uploaded brochure files are physically fine. I checked the file the screenshot's 404'd URL points to:

```
storage/app/public/whatsapp_brochures/aYLUHxXUARvtBFuJWqCJKfTBLmJFF57WfQk2nl0n.png
```

exists on disk, 189,714 bytes, timestamped the same minute it was uploaded. The model code that builds the preview/download URL is also already correct — `WhatsappCampaign::getBrochureUrlAttribute()` (`packages/Crm/WhatsApp/src/Models/WhatsappCampaign.php`) just calls Laravel's standard `\Storage::url($this->brochure_path)`, which is the right way to do this. Nothing wrong in the PHP/Blade/Vue code for brochures, images, or videos — don't edit any of those files for this fix.

The actual problem: `public/storage` is supposed to be a symlink pointing at `storage/app/public` (created by `php artisan storage:link`) — that's what makes `\Storage::url()`'s `/storage/...` URLs resolve to real files when served by `php artisan serve` or any webserver. I checked it directly:

```
lrwxrwxrwx ... public/storage
```

It exists as a symlink, but it's **broken** — attempting to resolve it returns `Input/output error`, meaning its target no longer exists. This matches this project's history exactly: the symlink was created on Aug 31 (before the project folder was renamed from `laravel-crm-2.2.5` to `CRM`), so it points at an absolute path under the *old* folder name. On Windows, `php artisan storage:link` creates either a real symlink or an NTFS junction using the **absolute path at the time it was created** — renaming the parent folder afterward doesn't update it, it just leaves it pointing at a path that no longer exists. That's why:

- The small "Attached Brochure Preview" thumbnail shows a broken-image icon (its `<img src>` — and the `<video>` tag, for an `.mp4` upload — resolves to `/storage/whatsapp_brochures/...`, which 404s the same way).
- Opening `127.0.0.1:8000/storage/whatsapp_brochures/....png` directly in the browser gives a plain 404.

This is the same category of issue as the earlier stale-build bug (something that broke silently as a side effect of the folder rename) — just a broken symlink this time instead of a stale compiled asset.

## The fix — recreate the symlink from the current folder, no code changes

Run these from the project root (`E:\Study Material\Skills\Project\CRM`), in a terminal opened at that path:

```
php artisan storage:link
```

If it reports the link already exists (Laravel won't silently overwrite a broken one), remove the broken one first, then recreate it:

- **Command Prompt / PowerShell:** `rmdir public\storage` (this is correct even though it "looks like a folder" — a directory symlink/junction is removed with `rmdir`, not `del`, on Windows; using `del` here can sometimes delete the *target* directory's contents instead if the link is a soft symlink rather than a junction, so use `rmdir` and confirm the `storage/app/public/whatsapp_brochures` files listed above still exist afterward).
- Then run `php artisan storage:link` again.

If it fails with a permissions/privilege error (Windows requires elevated rights to create symlinks unless Developer Mode is on), do either of:

- Enable Windows Developer Mode once: Settings → Privacy & Security → For Developers → toggle Developer Mode on. Then re-run `php artisan storage:link` from a normal terminal.
- Or just run the terminal itself "as Administrator" for this one command.

## Verify

- After recreating the link, open the same failing URL directly in the browser (e.g. `http://127.0.0.1:8000/storage/whatsapp_brochures/aYLUHxXUARvtBFuJWqCJKfTBLmJFF57WfQk2nl0n.png`) and confirm it now shows the actual image instead of a 404.
- Reload the campaign create/preview/manage pages for a campaign with an image brochure and confirm the "Attached Brochure Preview" thumbnail now renders fully, and "View / Download Brochure File" opens the real file.
- Upload and preview an `.mp4` brochure on a fresh test campaign and confirm the `<video>` preview plays instead of showing a broken/empty player — same symlink, so this should now work identically to the image case.
- While you're in `storage/app/public/`, there's a leftover `whatsapp-brochures` folder (hyphenated, not the `whatsapp_brochures` underscored one the app actually uses) with a single stray file in it from an earlier test — this isn't referenced by any code path (the controller always stores to `'whatsapp_brochures'` with an underscore) and is safe to delete, it's just a one-off artifact, not a bug.
- Note for the future: if this project folder ever gets renamed or moved again, re-run `php artisan storage:link` afterward (after removing the old broken one) — this symlink does not update itself and this exact failure will recur otherwise.
