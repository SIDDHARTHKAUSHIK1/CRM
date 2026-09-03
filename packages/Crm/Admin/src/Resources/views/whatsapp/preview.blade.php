<x-admin::layouts>
    <x-slot:title>
        Preview Broadcast — {{ $campaign->name }}
    </x-slot>

    <div class="flex flex-col gap-6">
        <!-- Header -->
        <div class="scroll-reactive-sticky sticky top-[60px] z-[1000] flex flex-wrap items-center justify-between gap-4 rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm shadow-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
            <div class="flex flex-col gap-1">
                <x-admin::breadcrumbs name="whatsapp.preview" :entity="$campaign" />
                <div class="text-xl font-bold dark:text-white">
                    Preview: {{ $campaign->name }}
                </div>
            </div>

            <div class="flex items-center gap-2.5">
                <a
                    href="{{ route('admin.whatsapp.index') }}"
                    class="secondary-button"
                >
                    Save as Draft
                </a>
            </div>
        </div>

        <v-whatsapp-preview></v-whatsapp-preview>
    </div>

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-whatsapp-preview-template"
        >
            <div class="flex flex-col gap-6">
                <!-- 4 Stat Summary Cards -->
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <!-- Total Rows -->
                    <div class="rounded-lg border border-gray-300 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                        <p class="text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Total Rows Found</p>
                        <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">{{ $previewData['total_rows'] ?? $campaign->total_recipients }}</p>
                    </div>

                    <!-- Valid Numbers -->
                    <div class="rounded-lg border border-emerald-200 bg-emerald-50/50 p-4 shadow-sm dark:border-emerald-900 dark:bg-emerald-950/20">
                        <p class="text-xs font-medium uppercase tracking-wider text-emerald-700 dark:text-emerald-400">Valid &amp; Ready to Send</p>
                        <p class="mt-1 text-2xl font-bold text-emerald-600 dark:text-emerald-300">{{ $previewData['valid_count'] ?? $campaign->total_recipients }}</p>
                    </div>

                    <!-- Rejected Numbers -->
                    <div class="rounded-lg border border-red-200 bg-red-50/50 p-4 shadow-sm dark:border-red-900 dark:bg-red-950/20">
                        <p class="text-xs font-medium uppercase tracking-wider text-red-700 dark:text-red-400">Rejected / Invalid Format</p>
                        <p class="mt-1 text-2xl font-bold text-red-600 dark:text-red-300">{{ $previewData['invalid_count'] ?? 0 }}</p>
                    </div>

                    <!-- Duplicates Removed -->
                    <div class="rounded-lg border border-amber-200 bg-amber-50/50 p-4 shadow-sm dark:border-amber-900 dark:bg-amber-950/20">
                        <p class="text-xs font-medium uppercase tracking-wider text-amber-700 dark:text-amber-400">Duplicates Removed</p>
                        <p class="mt-1 text-2xl font-bold text-amber-600 dark:text-amber-300">{{ $previewData['duplicate_count'] ?? 0 }}</p>
                    </div>
                </div>

                <!-- Campaign Summary & Consent Confirmation -->
                <div class="rounded-lg border border-gray-300 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-3">
                        Campaign Summary
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm mb-6 border-b border-gray-200 pb-4 dark:border-gray-800">
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
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400 block">Safety Throttle</span>
                            <span class="font-medium text-gray-800 dark:text-gray-200 mt-0.5 block">
                                {{ $campaign->throttle_seconds }} seconds delay per message
                            </span>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400 block">Estimated Duration</span>
                            <span class="font-medium text-gray-800 dark:text-gray-200 mt-0.5 block">
                                @php
                                    $totalSeconds = $campaign->total_recipients * $campaign->throttle_seconds;
                                    $minutes = ceil($totalSeconds / 60);
                                @endphp
                                Approx. {{ $minutes }} minute{{ $minutes > 1 ? 's' : '' }}
                            </span>
                        </div>
                    </div>

                    @if ($campaign->caption)
                        <div class="mb-6 rounded-md bg-gray-50 p-3 text-xs text-gray-700 dark:bg-gray-800 dark:text-gray-300">
                            <span class="font-semibold block mb-1">Attached Caption:</span>
                            <p class="whitespace-pre-line">{{ $campaign->caption }}</p>
                        </div>
                    @endif

                    <!-- Mandatory Consent Box -->
                    <div class="rounded-lg border-2 border-brandColor/30 bg-brandColor/5 p-4 dark:border-brandColor/40 dark:bg-brandColor/10">
                        <label class="flex items-start gap-3 cursor-pointer select-none">
                            <input
                                type="checkbox"
                                v-model="hasConsent"
                                class="mt-1 h-5 w-5 rounded border-gray-300 text-brandColor focus:ring-brandColor"
                            >
                            <div class="text-xs text-gray-800 dark:text-gray-200 leading-relaxed">
                                <strong class="text-sm block text-brandColor mb-0.5">Mandatory Compliance &amp; Consent Confirmation</strong>
                                I explicitly confirm that all recipients in this list have given express consent to receive business and marketing communications from our organization. I understand that sending unsolicited messages violates WhatsApp Terms of Service and applicable anti-spam regulations.
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Valid Contacts Preview Table -->
                <div class="rounded-lg border border-gray-300 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white">
                            Valid Recipients Sample (Showing first {{ $sampleRecipients->count() }} of {{ $campaign->total_recipients }})
                        </h3>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
                            <thead class="border-b border-gray-200 bg-gray-50 text-xs uppercase text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                                <tr>
                                    <th class="px-4 py-2.5">#</th>
                                    <th class="px-4 py-2.5">Raw Cell Value</th>
                                    <th class="px-4 py-2.5">Normalized Phone (E.164)</th>
                                    <th class="px-4 py-2.5">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sampleRecipients as $index => $recipient)
                                    <tr class="border-b border-gray-100 hover:bg-gray-50 dark:border-gray-800 dark:hover:bg-gray-800/50">
                                        <td class="px-4 py-2 text-xs font-mono">{{ $index + 1 }}</td>
                                        <td class="px-4 py-2 text-xs font-mono text-gray-500">{{ $recipient->raw_input }}</td>
                                        <td class="px-4 py-2 text-xs font-mono font-semibold text-emerald-600 dark:text-emerald-400">
                                            +{{ $recipient->phone_e164 }}
                                        </td>
                                        <td class="px-4 py-2 text-xs">
                                            <span class="rounded bg-gray-100 px-2 py-0.5 text-xs text-gray-600 dark:bg-gray-800 dark:text-gray-300">
                                                Pending
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Rejected / Duplicate Numbers Section (if any) -->
                @if (!empty($previewData['invalid']) || !empty($previewData['duplicates']))
                    <div class="rounded-lg border border-red-200 bg-white p-5 shadow-sm dark:border-red-900 dark:bg-gray-900">
                        <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
                            <div>
                                <h3 class="text-base font-semibold text-red-600 dark:text-red-400">
                                     Rejected &amp; Deduplicated Records
                                 </h3>
                                 <p class="text-xs text-gray-500 dark:text-gray-400">
                                     These records will NOT be sent. You can download the full list with rejection reasons for correction.
                                 </p>
                            </div>

                            <a
                                 href="{{ route('admin.whatsapp.download_rejected', $campaign->id) }}"
                                 class="secondary-button"
                            >
                                <span class="icon-download text-sm"></span>
                                Download Rejected CSV
                            </a>
                        </div>

                        <div class="overflow-x-auto max-h-60 overflow-y-auto">
                            <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
                                <thead class="sticky top-0 border-b border-gray-200 bg-gray-50 text-xs uppercase text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">
                                    <tr>
                                        <th class="px-4 py-2">Type</th>
                                        <th class="px-4 py-2">Raw Input</th>
                                        <th class="px-4 py-2">Rejection Reason</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($previewData['invalid'] ?? [] as $inv)
                                        <tr class="border-b border-gray-100 hover:bg-red-50/30 dark:border-gray-800 dark:hover:bg-red-950/20">
                                            <td class="px-4 py-1.5 text-xs font-semibold text-red-600 dark:text-red-400">Invalid</td>
                                            <td class="px-4 py-1.5 text-xs font-mono text-gray-700 dark:text-gray-300">{{ $inv['raw'] ?? '-' }}</td>
                                            <td class="px-4 py-1.5 text-xs text-red-500 dark:text-red-400">{{ $inv['reason'] ?? 'Invalid format' }}</td>
                                        </tr>
                                    @endforeach

                                    @foreach ($previewData['duplicates'] ?? [] as $dup)
                                        <tr class="border-b border-gray-100 hover:bg-amber-50/30 dark:border-gray-800 dark:hover:bg-amber-950/20">
                                            <td class="px-4 py-1.5 text-xs font-semibold text-amber-600 dark:text-amber-400">Duplicate</td>
                                            <td class="px-4 py-1.5 text-xs font-mono text-gray-700 dark:text-gray-300">{{ $dup['raw'] ?? '-' }}</td>
                                            <td class="px-4 py-1.5 text-xs text-amber-500 dark:text-amber-400">{{ $dup['reason'] ?? 'Duplicate' }} (+{{ $dup['phone_e164'] ?? '' }})</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                <!-- Bottom Action Sticky Bar -->
                <div class="flex items-center justify-between rounded-lg border border-gray-300 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                    <a
                        href="{{ route('admin.whatsapp.index') }}"
                        class="secondary-button"
                    >
                        Cancel / Save as Draft
                    </a>

                    <form
                        action="{{ route('admin.whatsapp.start', $campaign->id) }}"
                        method="POST"
                    >
                        @csrf
                        <input type="hidden" name="confirm_consent" :value="hasConsent ? '1' : '0'">
                        <button
                            type="submit"
                            class="primary-button"
                            :disabled="!hasConsent"
                        >
                            <span class="icon-mail text-sm"></span>
                            Start Broadcast ({{ $campaign->total_recipients }} Messages)
                        </button>
                    </form>
                </div>
            </div>
        </script>

        <script type="module">
            app.component('v-whatsapp-preview', {
                template: '#v-whatsapp-preview-template',
                data() {
                    return {
                        hasConsent: false
                    };
                }
            });
        </script>
    @endPushOnce
</x-admin::layouts>
