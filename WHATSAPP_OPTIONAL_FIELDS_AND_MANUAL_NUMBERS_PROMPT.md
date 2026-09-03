# Master Prompt — Make message/brochure both optional (send either or both) + add manual number entry

Paste this to your coding agent as-is.

---

## What's changing and why

Two independent changes to the "Create WhatsApp Broadcast" flow (`packages/Crm/Admin/src/Resources/views/whatsapp/create.blade.php` → `CampaignController::store()` → `PhoneParserService`):

1. **Right now `caption` and `brochure_file` are validated as: caption optional, brochure_file `required`.** Change this so either one alone is enough — a text-only broadcast (caption, no brochure), a brochure-only broadcast (media, no caption), or both together — but never neither.
2. **Right now the only way to supply recipients is `numbers_file` (`required`).** Add a manual "type/paste numbers" textarea as a second, equally valid way to supply recipients — either one alone is enough, or both together (merged and de-duplicated as one list), but never neither.

I checked the full send pipeline first — good news, it already fully supports both of these on the sending side with zero changes needed there:

- `SendWhatsappCampaignMessageJob::handle()` (`packages/Crm/WhatsApp/src/Jobs/SendWhatsappCampaignMessageJob.php`) already resolves `$absoluteMediaPath = null` whenever `$campaign->brochure_path` is empty, and passes that straight through to `WhatsAppClientService::sendMessage(mediaPath: null, caption: $campaign->caption, ...)`.
- The Node gateway (`whatsapp-gateway/index.js`, `/send` handler, ~line 214) already branches on `if (mediaPath) { ...image/video/document... } else { messagePayload = { text: caption || '' } }` — a text-only send already works today at the transport layer.

So this is purely a validation + storage + display change, not a messaging-logic change. Don't touch the Job, `WhatsAppClientService`, or the gateway.

## Part 1 — Message and brochure: either or both, never neither

### 1a. Migration: `brochure_path` must become nullable

`packages/Crm/WhatsApp/src/Database/Migrations/2026_09_01_000001_create_whatsapp_campaigns_table.php` declared `$table->string('brochure_path');` — not nullable, and that migration has already run, so editing it retroactively won't change the live column. Add a **new** migration instead:

```php
// packages/Crm/WhatsApp/src/Database/Migrations/2026_09_03_000001_make_whatsapp_campaign_brochure_path_nullable.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // doctrine/dbal isn't installed in this project, so Schema::table()->change()
        // isn't available — use a raw statement instead. This project runs MySQL
        // (confirmed via DB_CONNECTION=mysql in .env).
        DB::statement('ALTER TABLE whatsapp_campaigns MODIFY brochure_path VARCHAR(255) NULL');
    }

    public function down(): void
    {
        // Not safely reversible once any row has brochure_path = NULL; left as a no-op.
    }
};
```

Run `php artisan migrate` after adding it.

### 1b. `CampaignController::store()` validation and storage

In `packages/Crm/Admin/src/Http/Controllers/WhatsApp/CampaignController.php`, change the validation block (currently ~line 89):

```php
$request->validate([
    'name'             => 'required|string|max:255',
    'numbers_file'     => 'required|file|max:10240',
    'brochure_file'    => 'required|file|max:' . (config('whatsapp.max_media_mb', 16) * 1024),
    'caption'          => 'nullable|string|max:2000',
    'throttle_seconds' => 'required|integer|min:5|max:300',
    'daily_limit'      => 'nullable|integer|min:1|max:5000',
]);
```

to:

```php
$request->validate([
    'name'             => 'required|string|max:255',
    'numbers_file'     => 'nullable|file|max:10240',
    'brochure_file'    => 'nullable|required_without:caption|file|max:' . (config('whatsapp.max_media_mb', 16) * 1024),
    'caption'          => 'nullable|required_without:brochure_file|string|max:2000',
    'throttle_seconds' => 'required|integer|min:5|max:300',
    'daily_limit'      => 'nullable|integer|min:1|max:5000',
], [
    'caption.required_without'       => 'Please provide a message caption, a brochure file, or both.',
    'brochure_file.required_without' => 'Please provide a brochure file, a message caption, or both.',
]);
```

(`numbers_file` also moves to `nullable` here — its own "at least one of file or manual entry" requirement is handled together with the new `manual_numbers` field in Part 2, so don't add a `required_without` to it yet; Part 2 replaces this line again.)

Then guard the brochure upload (currently ~line 98-101, unconditional):

```php
$brochureFile = $request->file('brochure_file');
$brochurePath = $brochureFile->store('whatsapp_brochures', 'public');
$brochureName = $brochureFile->getClientOriginalName();
```

becomes:

```php
$brochurePath = null;
$brochureName = null;

if ($request->hasFile('brochure_file')) {
    $brochureFile = $request->file('brochure_file');
    $brochurePath = $brochureFile->store('whatsapp_brochures', 'public');
    $brochureName = $brochureFile->getClientOriginalName();
}
```

And in the `if ($parseResult['valid_count'] === 0) { ... }` cleanup block right after it, guard the brochure delete the same way: `if ($brochurePath) { Storage::disk('public')->delete($brochurePath); }` (currently unconditional and would error on a null path).

### 1c. `create.blade.php` — stop requiring brochure

In the "2. Contact List & Brochure File" card:

- Add a one-line note under the card's `<h3>` heading: `<p class="text-xs text-gray-500 dark:text-gray-400 mb-4">Provide a message caption, a brochure file, or both — at least one is required.</p>`
- On the "Product Brochure / Media File" label, remove `<span class="text-red-500">*</span>` and change the label text to `Product Brochure / Media File (Optional)`.
- On the `<input type="file" name="brochure_file" ...>`, remove the `required` attribute.

### 1d. Display fixes — don't imply a brochure exists when there isn't one

Three places currently assume every campaign has a brochure. Fix each:

**`preview.blade.php`** (~line 68-76): the "Brochure File" summary tile always renders. Wrap it:

```blade
@if ($campaign->brochure_path)
    <div>
        <span class="text-xs text-gray-500 dark:text-gray-400 block">Brochure File</span>
        <span class="font-medium text-gray-800 dark:text-gray-200 flex items-center gap-1 mt-0.5">
            <span class="icon-image text-sm"></span>
            {{ $campaign->brochure_name ?: 'Uploaded Brochure' }}
        </span>
    </div>
@else
    <div>
        <span class="text-xs text-gray-500 dark:text-gray-400 block">Brochure File</span>
        <span class="font-medium text-gray-500 dark:text-gray-400 flex items-center gap-1 mt-0.5">
            <span class="icon-message text-sm"></span>
            Message only — no brochure
        </span>
    </div>
@endif
```

(this tile sits in a `grid grid-cols-1 md:grid-cols-3 gap-4` row alongside "Safety Throttle" and "Estimated Duration" — keep it in the grid, just swap its content.)

**`show.blade.php`** (~line 202-253, the "Right 1 Col: Brochure File Preview" panel in the Vue `v-broadcast-dashboard` template): this whole panel currently always renders and falls through to a generic "Media Attachment" card when there's no brochure. Wrap the entire panel:

```blade
<div v-if="brochureName || brochureUrl" class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-800 dark:bg-gray-800/40 flex flex-col justify-between">
    <!-- ...existing panel content unchanged... -->
</div>
<div v-else class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-800 dark:bg-gray-800/40 flex items-center justify-center text-center">
    <div>
        <span class="icon-message text-3xl text-gray-400 dark:text-gray-500"></span>
        <p class="mt-2 text-xs font-medium text-gray-500 dark:text-gray-400">Message-only broadcast — no brochure attached</p>
    </div>
</div>
```

Don't change anything inside the existing panel (the image/video/document branches and the download link already correctly check `brochureUrl`/`mediaType` — the only bug is that the outer panel itself has no guard).

**`index.blade.php`** (~line 234-240, the campaign list table's brochure column): change

```blade
<span class="inline-flex items-center gap-1.5 text-xs text-gray-700 dark:text-gray-300">
    <span class="icon-image text-sm text-gray-400 dark:text-gray-500 flex-shrink-0"></span>
    <span class="truncate max-w-[90px] md:max-w-[110px]" :title="campaign.brochure_name || 'Brochure'">
        @{{ campaign.brochure_name || 'Brochure' }}
    </span>
</span>
```

to conditionally show "Text only" with a different icon when there's no brochure:

```blade
<span class="inline-flex items-center gap-1.5 text-xs text-gray-700 dark:text-gray-300">
    <span :class="campaign.brochure_name ? 'icon-image' : 'icon-message'" class="text-sm text-gray-400 dark:text-gray-500 flex-shrink-0"></span>
    <span class="truncate max-w-[90px] md:max-w-[110px]" :title="campaign.brochure_name || 'Text only'">
        @{{ campaign.brochure_name || 'Text only' }}
    </span>
</span>
```

(if `icon-message` doesn't exist as an icon class in this theme's icon font, check `packages/Crm/Admin/src/Resources/assets/css/app.css`'s `.icon-*` definitions for the closest existing chat/message glyph and use that instead — don't invent a new icon asset for this.)

## Part 2 — Manual number entry (type/paste numbers, as many as needed)

### 2a. `PhoneParserService` — add a manual-text path, reuse the existing normalize/dedupe pipeline

`packages/Crm/WhatsApp/src/Services/PhoneParserService.php` already has all the parsing logic needed — `readTextFile()` (splits on newlines, then on comma/semicolon/tab/pipe/slash within a line) is exactly the same shape as what a manual textarea needs. Refactor to share it instead of duplicating it:

1. Extract the loop body of `parseFile()` (everything from `$valid = [];` down to the `return [...]` at the end — the part that walks `$rawRows`, checks for a header row, calls `extractCandidateValuesFromRow()`, calls `normalizeNumber()`, and tracks `$seen` for dedup) into a new `protected function processRows(array $rawRows): array` that returns the same `['total_rows', 'valid_count', 'invalid_count', 'duplicate_count', 'valid', 'invalid', 'duplicates']` shape. `parseFile()` becomes:

   ```php
   public function parseFile($file): array
   {
       return $this->processRows($this->extractRawRows($file));
   }
   ```

2. Extract the line-splitting logic inside `readTextFile()` (the `preg_split('/[\r\n]+/', $content)` + per-line delimiter splitting) into a new `protected function splitTextIntoRows(string $content): array`, and have `readTextFile()` call it: `return $this->splitTextIntoRows($content ?: '');` after reading the file.

3. Add a new public method that takes the raw textarea string directly (no file involved):

   ```php
   public function parseManualText(string $text): array
   {
       return $this->processRows($this->splitTextIntoRows($text));
   }
   ```

4. Add a combining method so a file upload and manual entry can be used together — recipients from both sources must be deduplicated against each other, not just within each source separately, so run them through `processRows()` together in one pass:

   ```php
   public function parseCombined($file, ?string $manualText): array
   {
       $rawRows = [];

       if ($file) {
           $rawRows = array_merge($rawRows, $this->extractRawRows($file));
       }

       if ($manualText !== null && trim($manualText) !== '') {
           $rawRows = array_merge($rawRows, $this->splitTextIntoRows($manualText));
       }

       return $this->processRows($rawRows);
   }
   ```

   Note: because `$rawRows` from the file comes first and the header-row check in `processRows()` (formerly in `parseFile()`) only skips a header at index `0`, this is still correct — a header row, if present, is still the very first row overall.

### 2b. `CampaignController::store()` — accept and merge both sources

Replace the `numbers_file` validation line from Part 1b with the full "at least one of file or manual text" pair:

```php
'numbers_file'   => 'nullable|required_without:manual_numbers|file|max:10240',
'manual_numbers' => 'nullable|required_without:numbers_file|string|max:50000',
```

add a custom message for both:

```php
'numbers_file.required_without'   => 'Please upload a phone numbers file, type numbers manually, or both.',
'manual_numbers.required_without' => 'Please type phone numbers manually, upload a file, or both.',
```

Then change the parse call (currently ~line 104):

```php
$parseResult = $this->phoneParserService->parseFile($request->file('numbers_file'));
```

to:

```php
$parseResult = $this->phoneParserService->parseCombined(
    $request->hasFile('numbers_file') ? $request->file('numbers_file') : null,
    $request->input('manual_numbers')
);
```

Nothing else in `store()` needs to change — recipient insertion already just iterates `$parseResult['valid']` regardless of where the rows came from.

### 2c. `create.blade.php` — add the manual-entry textarea

In the "2. Contact List & Brochure File" card, under the existing "Contact Phone Numbers File" upload block:

- Remove `<span class="text-red-500">*</span>` from that label and change it to `Contact Phone Numbers File (Optional if typing numbers below)`.
- Remove the `required` attribute from `<input type="file" name="numbers_file" ...>`.
- Immediately after that upload block (before the "Brochure Upload" block), add:

```blade
<!-- Manual Number Entry -->
<div class="mb-6">
    <div class="relative my-4 flex items-center">
        <div class="flex-grow border-t border-gray-200 dark:border-gray-800"></div>
        <span class="mx-3 flex-shrink text-xs font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500">Or</span>
        <div class="flex-grow border-t border-gray-200 dark:border-gray-800"></div>
    </div>

    <label for="manual_numbers" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
        Type or Paste Numbers Manually (Optional)
    </label>
    <textarea
        name="manual_numbers"
        id="manual_numbers"
        rows="6"
        placeholder="e.g.&#10;9876543210&#10;+91 98765 43211, 9876543212&#10;9876543213"
        class="w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-brandColor focus:outline-none dark:border-gray-700 dark:bg-gray-800 dark:text-white"
    >{{ old('manual_numbers') }}</textarea>
    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
        Add as many numbers as you like — one per line, or separated by commas. Same formatting rules as the file upload (10-digit Indian numbers, +91, spaces, dashes all handled automatically).
    </p>
    <p class="text-xs text-gray-400 mt-1">
        You can upload a file, type numbers here, or both — matching numbers between the two are automatically merged and de-duplicated. For very large lists (tens of thousands of numbers), the file upload is more reliable than pasting into this box.
    </p>
</div>
```

Every class used above (`w-full rounded-md border ...`, the `flex-grow border-t` divider pattern, `text-xs uppercase tracking-wider`) is already compiled into this theme's CSS from existing usage elsewhere in this same file and in `mail`/`quotes` views — don't introduce any new one-off utility class or arbitrary value. **This matters concretely here**: the last dark-mode/scrollbar bug in this project was caused by editing Blade files without rebuilding the Tailwind-compiled CSS afterward, so if you do end up needing any class that doesn't already appear elsewhere in this codebase, you must rebuild the Admin package's frontend assets afterward (`cd packages/Crm/Admin && npm install && npm run build`, then `php artisan view:clear`) or it will silently not render, exactly like before.

## Verify

- **Message/brochure combinations**: create one campaign with only a caption (no brochure), one with only a brochure (no caption), one with both, and confirm the store request succeeds for all three and fails with the friendly validation message when both are left empty.
- For the caption-only campaign, confirm on `preview.blade.php` the Brochure tile shows "Message only — no brochure", on `show.blade.php` the brochure panel shows the "Message-only broadcast" placeholder instead of a fake document card, and on `index.blade.php` the list row shows "Text only" instead of "Brochure".
- Actually run the caption-only campaign against the connected WhatsApp Gateway and confirm recipients receive a plain text message (no attachment) — this exercises the gateway's existing `else { text: caption }` branch, which needed no code change but is worth confirming end-to-end.
- **Manual numbers**: create a campaign using only the `manual_numbers` textarea (no file) with a mix of comma-separated and newline-separated numbers, confirm they parse and appear correctly on the preview screen with the same valid/invalid/duplicate breakdown a file upload would produce. Then create one using both a file and the textarea where a few numbers overlap between the two, and confirm the overlapping ones show up once in "valid" and the rest in "Duplicates Removed" — not duplicated as separate recipients.
- Confirm leaving both `numbers_file` and `manual_numbers` empty produces the friendly validation error instead of a silent zero-recipient campaign.
- Run `php artisan migrate` and confirm it applies the new nullable-column migration cleanly on a copy of the current database (check `whatsapp_campaigns.brochure_path` is nullable afterward, e.g. via `DESCRIBE whatsapp_campaigns;`).
