# Master Prompt — Fix "brochure file failed to upload" for MP4s + raise the media size limit correctly

Paste this to your coding agent as-is.

---

## Ignore the console noise again — same as before

`chrome-extension://ojplmecpdpgccookcobabopnaifgidhf/.../couponCollection.js` is a coupon/shopping browser extension failing to inject itself into the page — it has nothing to do with this project and would show up on any website. It is not related to the upload error below. If it keeps cluttering your console while you test, disable that one extension in `chrome://extensions` or test in an Incognito window with extensions off — don't look for it in the codebase, there's nothing there to fix.

## Part 1 — "The brochure file failed to upload." on MP4s

This exact error text is not something this project wrote — it's **Laravel's own built-in validation message for the `file` rule failing** (`"The :attribute failed to upload."`). Laravel's `file` rule fails specifically when PHP itself rejected the upload *before* Laravel ever got to check your `max:` size rule — PHP sets an internal upload-error code (`UPLOAD_ERR_INI_SIZE` or `UPLOAD_ERR_FORM_SIZE`) whenever a file exceeds `upload_max_filesize` or the whole request exceeds `post_max_size` in `php.ini`. Both of those default to small values on a fresh PHP install (commonly `2M` or `8M`) — plenty for a PDF or a compressed image, but far too small for almost any MP4, which is exactly why images upload fine and video doesn't: this isn't specific to video at all, it happens to any brochure file bigger than whatever `php.ini` currently allows.

**Find the php.ini actually being used** (I can't determine this remotely — it depends on your local PHP/Apache setup, e.g. XAMPP, Laravel Herd, WAMP):

```
php --ini
```

This prints a line like `Loaded Configuration File: C:\xampp\php\php.ini` (path varies by setup) — open that exact file, not just any `php.ini` you find by searching, since PHP CLI and the Apache/webserver module can sometimes load different files.

Edit these two values (coordinate the numbers with Part 2 below — set them at least as high as the new `WHATSAPP_MAX_MEDIA_MB` you land on, with some headroom since `post_max_size` has to cover the whole multipart request, not just the file):

```ini
upload_max_filesize = 100M
post_max_size = 110M
```

**Restart Apache** after saving (in XAMPP: Control Panel → Stop, then Start next to Apache — editing `php.ini` does nothing until the server process restarts, this is the single most common thing people forget here).

If you're on Apache and have ever customized `httpd.conf` or a `.htaccess` with `LimitRequestBody`, check it isn't independently capping request size lower than the above — by default Apache doesn't set this, so it's unlikely, but worth a quick grep if the error persists after the php.ini fix.

## Part 2 — Raising the size limit correctly (not just bigger — matched to what WhatsApp actually allows)

I looked up WhatsApp's real, current media limits before picking a number, because this isn't arbitrary — WhatsApp enforces its own caps independently of anything this app validates:

- **Images/videos sent as inline media** (the `image`/`video` message types, played back directly in the chat): capped by WhatsApp itself at **16 MB**, regardless of what our app allows the user to upload.
- **Files sent as a document attachment**: WhatsApp allows up to **2 GB**.

So simply raising `WHATSAPP_MAX_MEDIA_MB` past 16 without anything else would let a user upload, say, a 60 MB product video — and the send would then fail or get silently rejected by WhatsApp, because `whatsapp-gateway/index.js`'s `/send` handler currently always sends an image/video MIME type as inline media (~line 229-241: `if (detectedMime.startsWith('image/')) { messagePayload = { image: buffer, ... } } else if (detectedMime.startsWith('video/')) { messagePayload = { video: buffer, ... } }`), with no size check at all. That would just trade one confusing failure ("failed to upload" from PHP) for a different one (silent WhatsApp delivery failure) — not an actual fix.

The correct fix has two parts:

### 2a. Raise the configured ceiling to a document-friendly size

In `.env` and `.env.example`:

```
WHATSAPP_MAX_MEDIA_MB=100
```

(Pick a number that fits your real brochures — anywhere up to `2048` is technically valid per WhatsApp's document ceiling, but there's no reason to allow bigger than the largest brochure/video you'll actually send; 100 MB comfortably covers a multi-minute product video sent as a document while keeping upload times reasonable on a slow connection. Don't set this below 16.)

`config/whatsapp.php` already reads this via `'max_media_mb' => (int) env('WHATSAPP_MAX_MEDIA_MB', 16)`, and all three of the existing `brochure_file` validation rules in `CampaignController.php` (store, and the two edit/update methods, currently at lines ~93, ~244, ~316) already reference `config('whatsapp.max_media_mb', 16) * 1024` — so changing the one `.env` value raises the ceiling everywhere consistently. Don't hardcode a new number into any of those three lines individually.

### 2b. Auto-fallback oversized images/videos to "document" delivery in the gateway

In `whatsapp-gateway/index.js`'s `/send` handler, after `const buffer = fs.readFileSync(mediaPath);` and `const detectedMime = mime.lookup(mediaPath) || 'application/octet-stream';` (~line 225-226), add a size check and use it to decide the branch instead of relying on MIME type alone:

```js
const INLINE_MEDIA_LIMIT_BYTES = 16 * 1024 * 1024; // WhatsApp's own cap for inline image/video playback
const isOversizedMedia = buffer.length > INLINE_MEDIA_LIMIT_BYTES;

if (detectedMime.startsWith('image/') && !isOversizedMedia) {
  messagePayload = {
    image: buffer,
    caption: caption || '',
    mimetype: detectedMime
  };
} else if (detectedMime.startsWith('video/') && !isOversizedMedia) {
  messagePayload = {
    video: buffer,
    caption: caption || '',
    mimetype: detectedMime
  };
} else {
  // Document / PDF / Sheet, OR an image/video too large to send inline —
  // WhatsApp allows documents up to 2GB, so this is how a large brochure/video
  // still gets delivered instead of failing WhatsApp's inline media size cap.
  messagePayload = {
    document: buffer,
    mimetype: detectedMime,
    fileName: cleanFilename,
    caption: caption || ''
  };
}
```

This is a minimal change to the existing `if/else if/else` chain already there (~line 229-248) — just add the `&& !isOversizedMedia` condition to the two size-sensitive branches; the existing `else` branch already builds the correct `document` payload shape, so it needs no changes itself.

### 2c. Update the UI copy so the behavior isn't a surprise

In `create.blade.php`, the brochure upload helper text currently says "Supports **PDF documents, Images (JPG, PNG, WEBP), and Videos (MP4)** up to 16 MB." Update it to reflect the real ceiling and the fallback behavior, e.g.:

```blade
<p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
    Supports <strong>PDF documents, Images (JPG, PNG, WEBP), and Videos (MP4)</strong> up to {{ config('whatsapp.max_media_mb', 16) }} MB.
</p>
<p class="text-xs text-gray-400 mt-1">
    Images and videos over 16 MB are automatically sent as a document attachment instead of inline media — that's a WhatsApp limit on inline playback, not this app; documents can be much larger.
</p>
```

## Verify

- After editing `php.ini` and restarting Apache, run `php --ini` again and confirm the printed config file is the one you edited (guards against having edited the wrong copy if more than one PHP install exists on this machine).
- Upload a small MP4 (well under 16 MB) as a brochure and confirm it still uploads and sends as an inline video with a working preview, exactly as before.
- Upload a large MP4 (over 16 MB but under your new `WHATSAPP_MAX_MEDIA_MB`) and confirm: the "failed to upload" validation error is gone, the campaign is created successfully, and when sent, the recipient receives it as a document attachment (not a broken/failed inline video) — check `WhatsappCampaignRecipient.status` flips to `sent`, not `failed`.
- Try uploading a file larger than your new `WHATSAPP_MAX_MEDIA_MB` ceiling and confirm Laravel's normal `max:` validation error appears (not the PHP-level "failed to upload" message) — this confirms php.ini is no longer the bottleneck and Laravel's own size rule is now the one being enforced, as intended.
- Confirm a normal image brochure under 16 MB still displays inline exactly as before — this change should only affect files that cross the 16 MB line.

Sources checked for WhatsApp's real size limits:
- [WhatsApp File Size Limits 2026 - Maximum Upload Limits | FileSize.org](https://filesize.org/limits/whatsapp/)
- [WhatsApp File Size Limit: Photos, Videos & Documents (2026)](https://www.usecarly.com/blog/whatsapp-file-size-limit/)
