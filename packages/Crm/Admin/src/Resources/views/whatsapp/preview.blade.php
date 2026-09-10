<x-admin::layouts>
    <x-slot:title>
        Preview Broadcast — {{ $campaign->name }}
    </x-slot>

    <div v-pre class="flex flex-col gap-6" id="whatsapp-preview-container">
        <!-- Header -->
        <div class="flex flex-wrap items-center justify-between gap-4 rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm shadow-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
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
                <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">{{ $previewData['valid_count'] ?? $campaign->total_recipients }}</p>
            </div>

            <!-- Rejected Numbers -->
            <div class="rounded-lg border border-red-200 bg-red-50/50 p-4 shadow-sm dark:border-red-900 dark:bg-red-950/20">
                <p class="text-xs font-medium uppercase tracking-wider text-red-700 dark:text-red-400">Rejected / Invalid Format</p>
                <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">{{ $previewData['invalid_count'] ?? 0 }}</p>
            </div>

            <!-- Duplicates Removed -->
            <div class="rounded-lg border border-amber-200 bg-amber-50/50 p-4 shadow-sm dark:border-amber-900 dark:bg-amber-950/20">
                <p class="text-xs font-medium uppercase tracking-wider text-amber-700 dark:text-amber-400">Duplicates Removed</p>
                <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">{{ $previewData['duplicate_count'] ?? 0 }}</p>
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
                        id="consent-checkbox"
                        class="mt-1 h-5 w-5 rounded border-gray-300 text-brandColor focus:ring-brandColor"
                        onchange="toggleSubmitButton(this.checked)"
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
                                <td class="px-4 py-2 text-xs text-gray-900 dark:text-white">{{ $index + 1 }}</td>
                                <td class="px-4 py-2 text-xs text-gray-500 dark:text-gray-400">{{ $recipient->raw_input }}</td>
                                <td class="px-4 py-2 text-xs font-bold text-gray-900 dark:text-white">
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
                                    <td class="px-4 py-1.5 text-xs text-gray-700 dark:text-gray-300">{{ $inv['raw'] ?? '-' }}</td>
                                    <td class="px-4 py-1.5 text-xs text-red-500 dark:text-red-400">{{ $inv['reason'] ?? 'Invalid format' }}</td>
                                </tr>
                            @endforeach

                            @foreach ($previewData['duplicates'] ?? [] as $dup)
                                <tr class="border-b border-gray-100 hover:bg-amber-50/30 dark:border-gray-800 dark:hover:bg-amber-950/20">
                                    <td class="px-4 py-1.5 text-xs font-semibold text-amber-600 dark:text-amber-400">Duplicate</td>
                                    <td class="px-4 py-1.5 text-xs text-gray-700 dark:text-gray-300">{{ $dup['raw'] ?? '-' }}</td>
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
                id="start-broadcast-form"
                action="{{ route('admin.whatsapp.start', $campaign->id) }}"
                method="POST"
                onsubmit="return handleStartBroadcast(event)"
            >
                @csrf
                <input type="hidden" name="confirm_consent" id="form-consent-input" value="0">
                <button
                    type="submit"
                    id="submit-broadcast-btn"
                    class="primary-button opacity-50 cursor-not-allowed"
                    disabled
                >
                    <span class="icon-mail text-sm"></span>
                    <span>Start Broadcast ({{ $campaign->total_recipients }} Messages)</span>
                </button>
            </form>
        </div>

        <!-- WhatsApp Not Connected Modal Popup -->
        <div
            id="not-connected-modal"
            class="fixed inset-0 z-[10006] hidden items-center justify-center bg-slate-900/60 p-4 backdrop-blur-xs transition-opacity duration-200"
            onclick="if(event.target === this) closeNotConnectedModal()"
        >
            <div class="relative w-full max-w-md rounded-3xl border border-slate-200 bg-white p-6 shadow-2xl transition-all dark:border-gray-800 dark:bg-gray-900">
                <!-- Close Button -->
                <button
                    type="button"
                    onclick="closeNotConnectedModal()"
                    class="absolute top-4 right-4 flex h-8 w-8 items-center justify-center rounded-xl text-slate-400 hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-gray-800 dark:hover:text-slate-200 transition cursor-pointer"
                >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>

                <!-- Icon & Content -->
                <div class="flex flex-col items-center text-center">
                    <div class="relative mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-amber-50 text-amber-500 border border-amber-200 shadow-sm dark:bg-amber-950/60 dark:border-amber-800/80 dark:text-amber-400">
                        <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
                        </svg>
                        <span class="absolute -bottom-1 -right-1 flex h-6 w-6 items-center justify-center rounded-full bg-rose-500 text-white font-bold text-xs shadow-sm ring-2 ring-white dark:ring-gray-900">!</span>
                    </div>

                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                        WhatsApp Not Connected
                    </h3>

                    <p class="mt-2 text-xs font-medium leading-relaxed text-slate-600 dark:text-slate-300 max-w-sm">
                        WhatsApp is not connected to the CRM project. To send broadcast messages, you must first connect your WhatsApp by scanning the QR code.
                    </p>

                    <div class="mt-6 flex flex-col gap-2.5 w-full">
                        <a
                            href="{{ route('admin.whatsapp.gateway') }}"
                            class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-3 text-xs font-bold shadow-md active:scale-95 transition cursor-pointer"
                        >
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="7" height="7"></rect>
                                <rect x="14" y="3" width="7" height="7"></rect>
                                <rect x="14" y="14" width="7" height="7"></rect>
                                <rect x="3" y="14" width="7" height="7"></rect>
                            </svg>
                            <span>Connect WhatsApp (Scan QR Code)</span>
                        </a>

                        <button
                            type="button"
                            onclick="closeNotConnectedModal()"
                            class="w-full inline-flex items-center justify-center rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-xs font-semibold text-slate-600 hover:bg-slate-100 dark:border-gray-700 dark:bg-gray-800 dark:text-slate-300 dark:hover:bg-gray-700 transition cursor-pointer"
                        >
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @pushOnce('scripts')
        <script>
            const isGatewayConnected = {{ !empty($gatewayStatus['connected']) ? 'true' : 'false' }};

            function toggleSubmitButton(checked) {
                const btn = document.getElementById('submit-broadcast-btn');
                const consentInput = document.getElementById('form-consent-input');
                if (btn) {
                    btn.disabled = !checked;
                    if (checked) {
                        btn.classList.remove('opacity-50', 'cursor-not-allowed');
                    } else {
                        btn.classList.add('opacity-50', 'cursor-not-allowed');
                    }
                }
                if (consentInput) {
                    consentInput.value = checked ? '1' : '0';
                }
            }

            function openNotConnectedModal() {
                const modal = document.getElementById('not-connected-modal');
                if (modal) {
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                }
            }

            function closeNotConnectedModal() {
                const modal = document.getElementById('not-connected-modal');
                if (modal) {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }
            }

            function handleStartBroadcast(e) {
                e.preventDefault();
                const checkbox = document.getElementById('consent-checkbox');
                if (!checkbox || !checkbox.checked) {
                    alert('Please confirm compliance & consent confirmation before starting.');
                    return false;
                }

                if (!isGatewayConnected) {
                    openNotConnectedModal();
                    return false;
                }

                document.getElementById('start-broadcast-form').submit();
                return true;
            }
        </script>
    @endPushOnce
</x-admin::layouts>
