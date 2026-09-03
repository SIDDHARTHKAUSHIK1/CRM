<x-admin::layouts>
    <x-slot:title>
        Create WhatsApp Broadcast
    </x-slot>

    <div class="flex flex-col gap-4">
        <!-- Header -->
        <div class="scroll-reactive-sticky sticky top-[60px] z-[1000] flex flex-wrap items-center justify-between gap-4 rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm shadow-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
            <div class="flex flex-col gap-1">
                <x-admin::breadcrumbs name="whatsapp.create" />
                <div class="text-xl font-bold dark:text-white">
                    Create New WhatsApp Broadcast
                </div>
            </div>

            <div class="flex items-center gap-2.5">
                <a
                    href="{{ route('admin.whatsapp.index') }}"
                    class="secondary-button"
                >
                    &larr; Back to Broadcasts
                </a>
            </div>
        </div>

        @if ($errors->any())
            <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-800 dark:border-red-800 dark:bg-red-950/40 dark:text-red-300">
                <p class="font-semibold">Please resolve the following errors:</p>
                <ul class="mt-1.5 list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form
            action="{{ route('admin.whatsapp.store') }}"
            method="POST"
            enctype="multipart/form-data"
            class="grid grid-cols-1 gap-6 lg:grid-cols-3"
        >
            @csrf

            <!-- Left 2 Cols: Main Content & Files -->
            <div class="flex flex-col gap-6 lg:col-span-2">
                <!-- Basic Info Card -->
                <div class="rounded-lg border border-gray-300 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4">
                        1. Broadcast Details
                    </h3>

                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Campaign Name <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            name="name"
                            id="name"
                            required
                            placeholder="e.g. Summer Product Brochure Launch"
                            value="{{ old('name') }}"
                            class="w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-brandColor focus:outline-none dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                        >
                    </div>

                    <div>
                        <label for="caption" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Message Caption (Optional)
                        </label>
                        <textarea
                            name="caption"
                            id="caption"
                            rows="4"
                            placeholder="Type the message to accompany your brochure/media file..."
                            class="w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-brandColor focus:outline-none dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                        >{{ old('caption') }}</textarea>
                        <p class="text-xs text-gray-400 mt-1">
                            Tip: Keep your message concise, polite, and clearly state how the recipient can opt out (e.g. "Reply STOP to unsubscribe").
                        </p>
                    </div>
                </div>

                <!-- File Uploads Card -->
                <div class="rounded-lg border border-gray-300 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-1">
                        2. Contact List & Brochure File
                    </h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">Provide a message caption, a brochure file, or both — at least one is required.</p>

                    <!-- Numbers File Upload -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Contact Phone Numbers File (Optional if typing numbers below)
                        </label>
                        <div class="rounded-lg border-2 border-dashed border-gray-300 p-6 text-center hover:border-brandColor dark:border-gray-700">
                            <span class="icon-attachment text-3xl text-gray-400"></span>
                            <div class="mt-2">
                                <input
                                    type="file"
                                    name="numbers_file"
                                    id="numbers_file"
                                    accept=".csv,.xlsx,.xls,.txt"
                                    class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-brandColor/10 file:text-brandColor hover:file:bg-brandColor/20"
                                >
                            </div>
                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                Supports <strong>CSV, Excel (.xlsx, .xls), or plain Text (.txt)</strong>.
                            </p>
                            <p class="text-xs text-gray-400 mt-1">
                                Automatic formatting handles 10-digit Indian numbers, international codes (+91), spaces, dashes, and scientific notation.
                            </p>
                        </div>
                    </div>

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

                    <!-- Brochure Upload -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Product Brochure / Media File (Optional)
                        </label>
                        <div class="rounded-lg border-2 border-dashed border-gray-300 p-6 text-center hover:border-brandColor dark:border-gray-700">
                            <span class="icon-image text-3xl text-gray-400"></span>
                            <div class="mt-2">
                                <input
                                    type="file"
                                    name="brochure_file"
                                    id="brochure_file"
                                    accept=".pdf,.jpg,.jpeg,.png,.webp,.mp4,.doc,.docx"
                                    class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-brandColor/10 file:text-brandColor hover:file:bg-brandColor/20"
                                >
                            </div>
                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                Supports <strong>PDF documents, Images (JPG, PNG, WEBP), and Videos (MP4)</strong> up to {{ config('whatsapp.max_media_mb', 16) }} MB.
                            </p>
                            <p class="text-xs text-gray-400 mt-1">
                                Images and videos over 16 MB are automatically sent as a document attachment instead of inline media — that's a WhatsApp limit on inline playback, not this app; documents can be much larger.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Col: Safety Throttling & Submit -->
            <div class="flex flex-col gap-6">
                <!-- Safety Pacing Card -->
                <div class="rounded-lg border border-gray-300 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-2 flex items-center gap-2">
                        <span class="icon-setting text-brandColor"></span>
                        Safety & Pacing
                    </h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">
                        Pacing intervals prevent WhatsApp automated spam detection filters from flagging your account.
                    </p>

                    <div class="mb-4">
                        <label for="throttle_seconds" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Delay Between Messages (Seconds) <span class="text-red-500">*</span>
                        </label>
                        <div class="flex items-center gap-3">
                            <input
                                type="number"
                                name="throttle_seconds"
                                id="throttle_seconds"
                                min="5"
                                max="300"
                                value="{{ old('throttle_seconds', config('whatsapp.default_throttle_seconds', 20)) }}"
                                class="w-24 rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-brandColor focus:outline-none dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                            >
                            <span class="text-xs text-gray-500 dark:text-gray-400">sec (15-30s recommended)</span>
                        </div>
                        <p class="text-[11px] text-amber-600 dark:text-amber-400 mt-1">
                            ⚠️ Never set to 0. A higher delay ensures high deliverability and protects your phone number.
                        </p>
                    </div>

                    <div class="mb-4">
                        <label for="daily_limit" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Daily Send Limit (Optional)
                        </label>
                        <input
                            type="number"
                            name="daily_limit"
                            id="daily_limit"
                            min="10"
                            max="5000"
                            placeholder="e.g. 500"
                            value="{{ old('daily_limit') }}"
                            class="w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-brandColor focus:outline-none dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                        >
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            Leaves remaining numbers in queue for subsequent batches.
                        </p>
                    </div>

                    <div class="rounded-md bg-blue-50 p-3 text-xs text-blue-800 dark:bg-blue-950/40 dark:text-blue-300">
                        <strong>🛡️ Built-in Circuit Breaker:</strong> If 5 consecutive message deliveries fail, the broadcast will automatically pause to prevent burning through your list into an offline session.
                    </div>
                </div>

                <!-- Actions Card -->
                <div class="rounded-lg border border-gray-300 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                    <button
                        type="submit"
                        class="primary-button w-full justify-center"
                    >
                        Upload &amp; Preview Contacts &rarr;
                    </button>
                    <p class="text-center text-xs text-gray-500 dark:text-gray-400 mt-2">
                        You will be able to review valid and rejected numbers before any messages are sent.
                    </p>
                </div>
            </div>
        </form>
    </div>
</x-admin::layouts>
