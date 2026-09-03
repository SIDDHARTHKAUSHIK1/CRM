<x-admin::layouts>
    <x-slot:title>
        Broadcast Dashboard — {{ $campaign->name }}
    </x-slot>

    <div class="flex flex-col gap-6 font-sans text-sm">
        <!-- Sticky Top Navigation Header -->
        <div class="scroll-reactive-sticky sticky top-[60px] z-[1000] flex flex-wrap items-center justify-between gap-4 rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm shadow-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
            <div class="flex flex-col gap-1">
                <x-admin::breadcrumbs name="whatsapp.show" :entity="$campaign" />
                <div class="flex items-center gap-3">
                    <span class="text-xl font-bold text-gray-900 dark:text-white">
                        {{ $campaign->name }}
                    </span>
                </div>
            </div>

            <div class="flex items-center gap-2.5">
                <a
                    href="{{ route('admin.whatsapp.index') }}"
                    class="secondary-button"
                >
                    &larr; All Broadcasts
                </a>
            </div>
        </div>

        <!-- Live Reactive Broadcast Dashboard Component -->
        <v-broadcast-dashboard
            :campaign-id="{{ $campaign->id }}"
            initial-name="{{ $campaign->name }}"
            initial-status="{{ $campaign->status }}"
            :initial-caption='@json($campaign->caption)'
            :initial-brochure-name='@json($campaign->brochure_name)'
            :initial-brochure-url='@json($campaign->brochure_url)'
            :initial-media-type='@json($campaign->media_type)'
            :initial-total="{{ $campaign->total_recipients }}"
            :initial-sent="{{ $campaign->sent_count }}"
            :initial-failed="{{ $campaign->failed_count }}"
            :initial-progress="{{ $campaign->progress_percent }}"
            :initial-pause-reason='@json($campaign->pause_reason)'
            :initial-throttle="{{ $campaign->throttle_seconds }}"
            :initial-recipients='@json($initialRecipients)'
        ></v-broadcast-dashboard>
    </div>

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-broadcast-dashboard-template"
        >
            <div class="flex flex-col gap-6 font-sans">
                <!-- Action Bar & Live Status -->
                <div class="flex flex-wrap items-center justify-between gap-4 rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm shadow-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
                    <div class="flex items-center gap-3">
                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                            Broadcast Status:
                        </span>
                        <span
                            class="inline-block rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-wider"
                            :class="{
                                'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-200 animate-pulse': currentStatus === 'running',
                                'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-200': currentStatus === 'completed',
                                'bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-200': currentStatus === 'paused',
                                'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300': currentStatus === 'cancelled',
                                'bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-200': currentStatus === 'draft'
                            }"
                        >
                            @{{ currentStatus }}
                        </span>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-wrap items-center gap-2.5">
                        <!-- Toggle Edit Details Button -->
                        <button
                            type="button"
                            @click="isEditingDetails = !isEditingDetails"
                            class="secondary-button"
                        >
                            <span class="icon-edit text-sm"></span>
                            @{{ isEditingDetails ? 'Close Editor' : 'Edit Campaign Details' }}
                        </button>

                        <!-- Start Broadcast Button (For Draft Campaigns) -->
                        <button
                            type="button"
                            v-show="currentStatus === 'draft'"
                            @click="openStartModal()"
                            :disabled="isActionLoading || totalRecipients === 0"
                            class="primary-button"
                        >
                            <span class="icon-play text-sm"></span>
                            Start Broadcast Now
                        </button>

                        <!-- Pause Button -->
                        <button
                            type="button"
                            v-show="currentStatus === 'running'"
                            @click="pauseCampaign()"
                            :disabled="isActionLoading"
                            class="secondary-button"
                        >
                            <span class="icon-pause text-sm"></span>
                            Pause Broadcast
                        </button>

                        <!-- Resume Button -->
                        <button
                            type="button"
                            v-show="currentStatus === 'paused'"
                            @click="resumeCampaign()"
                            :disabled="isActionLoading"
                            class="primary-button"
                        >
                            <span class="icon-play text-sm"></span>
                            Resume Broadcast
                        </button>

                        <!-- Cancel Button -->
                        <button
                            type="button"
                            v-show="currentStatus === 'running' || currentStatus === 'paused'"
                            @click="cancelCampaign()"
                            :disabled="isActionLoading"
                            class="secondary-button"
                        >
                            Cancel Broadcast
                        </button>

                        <!-- Retry Failed Button -->
                        <button
                            type="button"
                            v-show="failedCount > 0 && currentStatus !== 'running'"
                            @click="retryFailed()"
                            :disabled="isActionLoading"
                            class="primary-button"
                        >
                            <span class="icon-refresh text-sm"></span>
                            Retry @{{ failedCount }} Failed Messages
                        </button>
                    </div>
                </div>

                <!-- Campaign Overview & Details Editor Card -->
                <div class="rounded-lg border border-gray-300 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                    <div class="flex items-center justify-between border-b pb-3 mb-4 dark:border-gray-800">
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                            <span class="icon-mail text-emerald-600 dark:text-emerald-400 text-lg"></span>
                            Campaign Overview &amp; Brochure Details
                        </h3>

                        <button
                            type="button"
                            @click="isEditingDetails = !isEditingDetails"
                            class="text-xs font-semibold text-brandColor hover:underline dark:text-brandColor focus:outline-none"
                        >
                            @{{ isEditingDetails ? 'Cancel Editing' : '✎ Edit Details' }}
                        </button>
                    </div>

                    <!-- View Mode -->
                    <div v-if="!isEditingDetails" class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                        <!-- Left 2 Cols: Message Caption & Details -->
                        <div class="space-y-4 lg:col-span-2">
                            <div>
                                <h4 class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                    Campaign Title
                                </h4>
                                <p class="text-lg font-bold text-gray-900 dark:text-white mt-0.5">
                                    @{{ name }}
                                </p>
                            </div>

                            <div>
                                <h4 class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-1">
                                    Sent Message / Caption
                                </h4>
                                <div class="rounded-lg border border-gray-200 bg-gray-50/80 p-4 dark:border-gray-800 dark:bg-gray-800/60">
                                    <p v-if="caption" class="whitespace-pre-line text-sm font-sans text-gray-900 dark:text-gray-100 leading-relaxed">
                                        @{{ caption }}
                                    </p>
                                    <p v-else class="text-xs italic text-gray-500 dark:text-gray-400">
                                        No message caption set. Brochure file is broadcast directly to recipients.
                                    </p>
                                </div>
                            </div>

                            <div class="flex flex-wrap items-center gap-4 text-xs text-gray-700 dark:text-gray-300">
                                <div>
                                    <span class="font-medium text-gray-500 dark:text-gray-400">Pacing Delay:</span>
                                    <strong class="ml-1 font-mono text-emerald-700 dark:text-emerald-400">@{{ throttleSeconds }}s / message</strong>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-500 dark:text-gray-400">Total Contacts:</span>
                                    <strong class="ml-1 text-gray-900 dark:text-white">@{{ totalRecipients }} recipients</strong>
                                </div>
                            </div>
                        </div>

                        <!-- Right 1 Col: Brochure File Preview -->
                        <div v-if="brochureName || brochureUrl" class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-800 dark:bg-gray-800/40 flex flex-col justify-between">
                            <div>
                                <h4 class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-2">
                                    Attached Brochure Preview
                                </h4>

                                <!-- Image Preview -->
                                <div v-if="mediaType === 'image' && brochureUrl" class="mb-3">
                                    <a :href="brochureUrl" target="_blank" class="group relative block overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700">
                                        <img :src="brochureUrl" :alt="brochureName" class="max-h-48 w-full object-contain bg-white dark:bg-gray-900 p-1">
                                        <div class="absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 transition-opacity group-hover:opacity-100 text-white text-xs font-semibold">
                                            View Full Image &rarr;
                                        </div>
                                    </a>
                                </div>

                                <!-- Video Preview -->
                                <div v-else-if="mediaType === 'video' && brochureUrl" class="mb-3">
                                    <video :src="brochureUrl" controls class="max-h-48 w-full rounded-lg border border-gray-200 dark:border-gray-700 bg-black"></video>
                                </div>

                                <!-- Document / PDF Preview Card -->
                                <div v-else class="flex items-center gap-3 rounded-lg border border-gray-200 bg-white p-3 dark:border-gray-700 dark:bg-gray-800 mb-3">
                                    <span class="icon-document text-3xl text-emerald-600 dark:text-emerald-400"></span>
                                    <div class="overflow-hidden">
                                        <p class="truncate text-xs font-semibold text-gray-900 dark:text-white">
                                            @{{ brochureName || 'Brochure File' }}
                                        </p>
                                        <p class="text-[11px] font-medium text-gray-500 dark:text-gray-400">
                                            Media Attachment
                                        </p>
                                    </div>
                                </div>

                                <p class="truncate text-xs font-medium text-gray-700 dark:text-gray-300">
                                    📄 @{{ brochureName }}
                                </p>
                            </div>

                            <div class="mt-4 pt-3 border-t border-gray-200 dark:border-gray-700">
                                <a
                                    v-if="brochureUrl"
                                    :href="brochureUrl"
                                    target="_blank"
                                    class="inline-flex cursor-pointer items-center justify-center gap-1.5 rounded-md border border-gray-300 bg-white px-3 py-1.5 text-xs font-semibold text-gray-700 shadow-sm transition-all hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700/80 w-full"
                                >
                                    <span class="icon-download text-xs"></span>
                                    View / Download Brochure File
                                </a>
                            </div>
                        </div>
                        <div v-else class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-800 dark:bg-gray-800/40 flex items-center justify-center text-center">
                            <div>
                                <span class="icon-message text-3xl text-gray-400 dark:text-gray-500"></span>
                                <p class="mt-2 text-xs font-medium text-gray-500 dark:text-gray-400">Message-only broadcast — no brochure attached</p>
                            </div>
                        </div>
                    </div>

                    <!-- Edit Mode Form -->
                    <form v-else @submit.prevent="saveCampaignDetails()" class="space-y-4 font-sans">
                        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">
                                    Campaign Name <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    v-model="editForm.name"
                                    required
                                    class="w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-brandColor focus:outline-none dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                                >
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">
                                    Pacing Delay (Seconds Between Messages) <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="number"
                                    v-model="editForm.throttle_seconds"
                                    min="5"
                                    max="300"
                                    required
                                    class="w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-brandColor focus:outline-none dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                                >
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">
                                Sent Message / Caption
                            </label>
                            <textarea
                                v-model="editForm.caption"
                                rows="3"
                                placeholder="Type your WhatsApp broadcast message caption..."
                                class="w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-brandColor focus:outline-none dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                            ></textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">
                                Replace Product Brochure File (Optional)
                            </label>
                            <input
                                type="file"
                                ref="editBrochureFileInput"
                                @change="onEditBrochureSelected"
                                accept=".pdf,.jpg,.jpeg,.png,.webp,.mp4,.doc,.docx"
                                class="text-xs text-gray-500 dark:text-gray-400 file:mr-4 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-brandColor/10 file:text-brandColor hover:file:bg-brandColor/20"
                            >
                            <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">
                                Current file: <strong class="text-gray-900 dark:text-white">@{{ brochureName }}</strong>. Upload a new PDF, Image, or Video to replace it.
                            </p>
                        </div>

                        <div class="flex items-center justify-end gap-3 border-t pt-3 dark:border-gray-800">
                            <button
                                type="button"
                                @click="isEditingDetails = false"
                                class="secondary-button"
                            >
                                Cancel
                            </button>

                            <button
                                type="submit"
                                :disabled="isSavingDetails"
                                class="primary-button"
                            >
                                <span v-if="isSavingDetails" class="icon-refresh animate-spin text-xs"></span>
                                Save Campaign Details
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Draft Status Ready to Launch Banner -->
                <div v-if="currentStatus === 'draft'" class="rounded-lg border border-emerald-300 bg-emerald-50/80 p-4 text-emerald-950 shadow-sm dark:border-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-200">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div class="flex items-start gap-3">
                            <span class="icon-mail text-2xl text-emerald-600 dark:text-emerald-400 mt-0.5"></span>
                            <div>
                                <h4 class="font-bold text-sm text-gray-900 dark:text-white">Draft Broadcast Ready to Launch</h4>
                                <p class="text-xs mt-0.5 text-emerald-900 dark:text-emerald-300">
                                    This campaign is saved as a draft with <strong>@{{ totalRecipients }} recipients</strong> ready. Pacing delay is set to <strong>@{{ throttleSeconds }} seconds</strong> per message. You can launch it right now.
                                </p>
                            </div>
                        </div>

                        <button
                            type="button"
                            @click="openStartModal()"
                            :disabled="isActionLoading || totalRecipients === 0"
                            class="primary-button"
                        >
                            <span class="icon-play text-sm"></span>
                            Start Broadcast Now
                        </button>
                    </div>
                </div>

                <!-- Circuit Breaker Alert Banner -->
                <div v-if="pauseReason" class="rounded-lg border border-amber-300 bg-amber-50 p-4 text-amber-950 shadow-sm dark:border-amber-800 dark:bg-amber-950/40 dark:text-amber-200">
                    <div class="flex items-start gap-3">
                        <span class="icon-alert text-2xl text-amber-600 dark:text-amber-400 mt-0.5"></span>
                        <div>
                            <h4 class="font-bold text-sm text-gray-900 dark:text-white">Broadcast Paused</h4>
                            <p class="text-xs mt-0.5 text-amber-900 dark:text-amber-300">@{{ pauseReason }}</p>
                            <div class="mt-2 flex items-center gap-3">
                                <a
                                    href="{{ route('admin.whatsapp.gateway') }}"
                                    class="text-xs font-semibold underline hover:text-amber-950 dark:hover:text-amber-100"
                                >
                                    Check WhatsApp QR Link &rarr;
                                </a>
                                <button
                                    type="button"
                                    @click="resumeCampaign()"
                                    class="primary-button"
                                >
                                    Resume Sending
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Progress Bar & Live Counter Cards -->
                <div class="rounded-lg border border-gray-300 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-semibold text-gray-800 dark:text-gray-200">
                            Live Broadcast Progress
                        </span>
                        <span class="text-sm font-bold text-brandColor dark:text-brandColor">
                            @{{ progressPercent }}% Completed
                        </span>
                    </div>

                    <!-- Progress Bar -->
                    <div class="w-full bg-gray-100 rounded-full h-3.5 mb-6 overflow-hidden dark:bg-gray-800">
                        <div
                            class="bg-emerald-500 h-3.5 rounded-full transition-all duration-500 ease-out"
                            :style="'width: ' + progressPercent + '%'"
                        ></div>
                    </div>

                    <!-- 4 Metrics Cards -->
                    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
                        <div class="rounded-md border border-gray-200 bg-gray-50/50 p-3.5 dark:border-gray-800 dark:bg-gray-800/40">
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Total Recipients</p>
                            <p class="text-xl font-bold text-gray-900 dark:text-white mt-1">
                                @{{ totalRecipients }}
                            </p>
                        </div>

                        <div class="rounded-md border border-emerald-200 bg-emerald-50/50 p-3.5 dark:border-emerald-900/40 dark:bg-emerald-950/30">
                            <p class="text-xs font-medium text-emerald-700 dark:text-emerald-400">Successfully Sent</p>
                            <p class="text-xl font-bold text-emerald-600 dark:text-emerald-300 mt-1">
                                @{{ sentCount }}
                            </p>
                        </div>

                        <div class="rounded-md border border-red-200 bg-red-50/50 p-3.5 dark:border-red-900/40 dark:bg-red-950/30">
                            <p class="text-xs font-medium text-red-700 dark:text-red-400">Failed / Errors</p>
                            <p class="text-xl font-bold text-red-600 dark:text-red-300 mt-1">
                                @{{ failedCount }}
                            </p>
                        </div>

                        <div class="rounded-md border border-blue-200 bg-blue-50/50 p-3.5 dark:border-blue-900/40 dark:bg-blue-950/30">
                            <p class="text-xs font-medium text-blue-700 dark:text-blue-400">Pending in Queue</p>
                            <p class="text-xl font-bold text-blue-600 dark:text-blue-300 mt-1">
                                @{{ pendingCount }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Live Recipient Delivery Logs Table -->
                <div class="rounded-lg border border-gray-300 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                    <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
                        <div class="flex items-center gap-2">
                            <h3 class="text-base font-semibold text-gray-900 dark:text-white">
                                Recipient Delivery Logs
                            </h3>
                            <span v-if="currentStatus === 'running'" class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2 py-0.5 text-[11px] font-medium text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/50">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-ping"></span>
                                Live updating
                            </span>
                        </div>

                        <!-- Filter Status & Search -->
                        <div class="flex flex-wrap items-center gap-2">
                            <select
                                v-model="filterStatus"
                                @change="onFilterChange"
                                class="rounded-md border border-gray-300 bg-white px-2.5 py-1.5 text-xs text-gray-700 focus:outline-none dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200"
                            >
                                <option value="">All Statuses</option>
                                <option value="pending">Pending</option>
                                <option value="sending">Sending</option>
                                <option value="sent">Sent</option>
                                <option value="failed">Failed</option>
                                <option value="skipped_dnc">DNC Skipped</option>
                                <option value="skipped">Skipped / Cancelled</option>
                            </select>

                            <input
                                type="text"
                                v-model="searchQuery"
                                @input="onSearchInput"
                                placeholder="Search phone number..."
                                class="rounded-md border border-gray-300 bg-white px-3 py-1.5 text-xs text-gray-700 focus:outline-none dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500"
                            >

                            <button
                                type="button"
                                @click="fetchStatus(1)"
                                class="secondary-button"
                                title="Refresh logs"
                            >
                                <span class="icon-refresh text-sm"></span>
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto max-h-[calc(100vh-320px)] overflow-y-auto rounded-md border border-gray-200 dark:border-gray-800">
                        <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
                            <thead class="sticky top-0 z-10 border-b border-gray-200 bg-gray-50 text-xs uppercase text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">
                                <tr>
                                    <th class="px-4 py-2.5">#</th>
                                    <th class="px-4 py-2.5">Phone (E.164)</th>
                                    <th class="px-4 py-2.5">Status</th>
                                    <th class="px-4 py-2.5">Sent At</th>
                                    <th class="px-4 py-2.5">Attempts</th>
                                    <th class="px-4 py-2.5">Error / Note</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="recipient in recipients"
                                    :key="recipient.id"
                                    class="border-b border-gray-100 hover:bg-gray-50 dark:border-gray-800 dark:hover:bg-gray-800/50"
                                >
                                    <td class="px-4 py-2.5 text-xs font-mono text-gray-500 dark:text-gray-400">
                                        @{{ recipient.id }}
                                    </td>
                                    <td class="px-4 py-2.5 font-mono text-xs font-semibold text-gray-900 dark:text-white">
                                        +@{{ recipient.phone_e164 }}
                                    </td>
                                    <td class="px-4 py-2.5">
                                        <span
                                            class="inline-block rounded px-2 py-0.5 text-xs font-medium"
                                            :class="{
                                                'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-200': recipient.status === 'sent',
                                                'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-200': recipient.status === 'failed',
                                                'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-200 animate-pulse': recipient.status === 'sending',
                                                'bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-200': recipient.status === 'skipped_dnc',
                                                'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300': recipient.status === 'pending' || recipient.status === 'skipped'
                                            }"
                                        >
                                            @{{ recipient.status }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2.5 text-xs text-gray-600 dark:text-gray-400">
                                        @{{ formatDate(recipient.sent_at) }}
                                    </td>
                                    <td class="px-4 py-2.5 text-xs text-gray-600 dark:text-gray-400">
                                        @{{ recipient.attempts }}
                                    </td>
                                    <td class="px-4 py-2.5 text-xs text-red-500 dark:text-red-400 max-w-xs truncate" :title="recipient.error_message || ''">
                                        @{{ recipient.error_message || '-' }}
                                    </td>
                                </tr>

                                <tr v-if="!recipients || recipients.length === 0">
                                    <td colspan="6" class="px-4 py-8 text-center text-xs text-gray-400 dark:text-gray-500">
                                        No recipient logs found matching current filter.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Live Pagination Controls -->
                    <div v-if="lastPage > 1" class="mt-4 flex flex-wrap items-center justify-between gap-2 text-xs text-gray-500 dark:text-gray-400">
                        <div>
                            Showing page <strong class="text-gray-800 dark:text-gray-200">@{{ currentPage }}</strong> of <strong class="text-gray-800 dark:text-gray-200">@{{ lastPage }}</strong> (@{{ totalLogs }} total records)
                        </div>

                        <div class="flex items-center gap-1.5">
                            <button
                                type="button"
                                @click="changePage(currentPage - 1)"
                                :disabled="currentPage <= 1"
                                class="secondary-button"
                            >
                                &larr; Previous
                            </button>

                            <button
                                type="button"
                                @click="changePage(currentPage + 1)"
                                :disabled="currentPage >= lastPage"
                                class="secondary-button"
                            >
                                Next &rarr;
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Start Broadcast Modal from Draft (Teleported to body for perfect centering & z-index) -->
                <teleport to="body">
                    <div
                        v-if="showStartModal"
                        class="fixed inset-0 z-[10002] bg-gray-900/60 dark:bg-black/80 backdrop-blur-[2px] transition-opacity"
                        @click.self="showStartModal = false"
                    ></div>

                    <div
                        v-if="showStartModal"
                        class="fixed inset-0 z-[10003] flex items-center justify-center p-4 overflow-y-auto"
                        @click.self="showStartModal = false"
                    >
                        <div class="relative w-full max-w-[540px] rounded-xl border border-gray-200 bg-white p-6 shadow-2xl dark:border-gray-800 dark:bg-gray-900 text-left my-8 font-sans">
                            <!-- Header -->
                            <div class="flex items-center justify-between border-b pb-3 mb-4 dark:border-gray-800">
                                <h3 class="text-base font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                    <span class="icon-whatsapp text-emerald-600 dark:text-emerald-400 text-lg"></span>
                                    Launch WhatsApp Broadcast
                                </h3>
                                <span
                                    class="icon-cross-large cursor-pointer text-2xl text-gray-400 hover:text-gray-600 dark:hover:text-gray-200"
                                    @click="showStartModal = false"
                                ></span>
                            </div>

                            <!-- Content -->
                            <div class="space-y-4 text-sm text-gray-600 dark:text-gray-300">
                                <p class="text-sm font-medium text-gray-800 dark:text-gray-200">
                                    You are about to launch broadcast delivery to <strong>@{{ totalRecipients }} contacts</strong>.
                                </p>

                                <!-- Attached Brochure File & Option to Change / Upload New Brochure -->
                                <div class="rounded-lg border border-gray-200 bg-gray-50 p-3.5 dark:border-gray-700 dark:bg-gray-800/70 space-y-2">
                                    <div class="flex items-center justify-between gap-2">
                                        <span class="text-xs font-semibold text-gray-700 dark:text-gray-300">
                                            Product Brochure / Media File:
                                        </span>
                                        <label class="cursor-pointer text-xs font-semibold text-brandColor hover:underline dark:text-brandColor flex items-center gap-1">
                                            <span class="icon-attachment text-xs"></span>
                                            <span>Upload / Change Brochure</span>
                                            <input
                                                type="file"
                                                ref="brochureInput"
                                                @change="handleBrochureFileChange"
                                                accept=".pdf,.jpg,.jpeg,.png,.webp,.mp4,.doc,.docx"
                                                class="hidden"
                                            >
                                        </label>
                                    </div>
                                    <div class="flex items-center gap-2 text-xs text-emerald-700 dark:text-emerald-300 font-medium">
                                        <span class="icon-image text-sm"></span>
                                        <span>@{{ selectedBrochureName || brochureName || 'Brochure Attached' }}</span>
                                    </div>
                                    <p v-if="selectedBrochureName" class="text-[11px] text-emerald-600 dark:text-emerald-400 font-semibold">
                                        ✓ New brochure selected and ready to update upon starting.
                                    </p>
                                </div>

                                <div class="rounded-lg bg-gray-50 p-3.5 text-xs dark:bg-gray-800/70 border border-gray-200 dark:border-gray-700 space-y-2">
                                    <div class="flex items-center gap-2 text-gray-700 dark:text-gray-300">
                                        <span class="font-semibold">Pacing delay:</span>
                                        <span class="inline-block rounded bg-emerald-100 px-2 py-0.5 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-200 font-mono">
                                            @{{ throttleSeconds }}s between messages
                                        </span>
                                    </div>
                                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                                        • Numbers on the Do Not Contact (DNC) list will be skipped automatically.<br>
                                        • You can pause, resume, or cancel at any moment from this dashboard.
                                    </p>
                                </div>

                                <label class="flex items-start gap-2.5 pt-1 cursor-pointer select-none">
                                    <input
                                        type="checkbox"
                                        v-model="startConsentGiven"
                                        class="mt-0.5 h-4 w-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500 dark:border-gray-700 dark:bg-gray-800"
                                    >
                                    <span class="text-xs text-gray-700 dark:text-gray-300 leading-relaxed">
                                        I confirm that all recipients consented to receive marketing/business messages from my organization.
                                    </span>
                                </label>
                            </div>

                            <!-- Footer Actions -->
                            <div class="mt-6 flex items-center justify-end gap-3 border-t pt-4 dark:border-gray-800">
                                <button
                                    type="button"
                                    @click="showStartModal = false"
                                    class="secondary-button"
                                >
                                    Cancel
                                </button>

                                <button
                                    type="button"
                                    @click="startCampaignFromDraft()"
                                    :disabled="!startConsentGiven || isActionLoading"
                                    class="primary-button"
                                >
                                    <span v-if="isActionLoading" class="icon-refresh animate-spin text-sm"></span>
                                    <span v-else class="icon-play text-sm"></span>
                                    Confirm &amp; Start Broadcast
                                </button>
                            </div>
                        </div>
                    </div>
                </teleport>
            </div>
        </script>

        <script type="module">
            app.component('v-broadcast-dashboard', {
                template: '#v-broadcast-dashboard-template',

                props: {
                    campaignId: {
                        type: Number,
                        required: true
                    },
                    initialName: {
                        type: String,
                        default: ''
                    },
                    initialStatus: {
                        type: String,
                        default: 'draft'
                    },
                    initialCaption: {
                        type: String,
                        default: ''
                    },
                    initialBrochureName: {
                        type: String,
                        default: ''
                    },
                    initialBrochureUrl: {
                        type: String,
                        default: ''
                    },
                    initialMediaType: {
                        type: String,
                        default: 'document'
                    },
                    initialTotal: {
                        type: Number,
                        default: 0
                    },
                    initialSent: {
                        type: Number,
                        default: 0
                    },
                    initialFailed: {
                        type: Number,
                        default: 0
                    },
                    initialProgress: {
                        type: Number,
                        default: 0
                    },
                    initialPauseReason: {
                        type: String,
                        default: null
                    },
                    initialThrottle: {
                        type: Number,
                        default: 20
                    },
                    initialRecipients: {
                        type: Object,
                        default: () => ({ data: [], current_page: 1, last_page: 1, total: 0 })
                    }
                },

                data() {
                    return {
                        name: this.initialName,
                        currentStatus: this.initialStatus,
                        caption: this.initialCaption,
                        brochureName: this.initialBrochureName,
                        brochureUrl: this.initialBrochureUrl,
                        mediaType: this.initialMediaType,
                        totalRecipients: this.initialTotal,
                        sentCount: this.initialSent,
                        failedCount: this.initialFailed,
                        progressPercent: this.initialProgress,
                        pauseReason: this.initialPauseReason,
                        throttleSeconds: this.initialThrottle,
                        selectedBrochureFile: null,
                        selectedBrochureName: '',
                        recipients: this.initialRecipients?.data || [],
                        currentPage: this.initialRecipients?.current_page || 1,
                        lastPage: this.initialRecipients?.last_page || 1,
                        totalLogs: this.initialRecipients?.total || 0,
                        filterStatus: '',
                        searchQuery: '',
                        pollTimer: null,
                        searchDebounce: null,
                        isActionLoading: false,
                        showStartModal: false,
                        startConsentGiven: false,
                        isEditingDetails: false,
                        isSavingDetails: false,
                        editForm: {
                            name: this.initialName,
                            caption: this.initialCaption,
                            throttle_seconds: this.initialThrottle,
                        },
                        editBrochureFile: null
                    };
                },

                computed: {
                    pendingCount() {
                        return Math.max(0, this.totalRecipients - (this.sentCount + this.failedCount));
                    }
                },

                mounted() {
                    this.initPolling();
                },

                beforeUnmount() {
                    if (this.pollTimer) {
                        clearInterval(this.pollTimer);
                    }
                },

                methods: {
                    handleBrochureFileChange(event) {
                        const file = event.target.files?.[0];
                        if (file) {
                            this.selectedBrochureFile = file;
                            this.selectedBrochureName = file.name;
                        }
                    },

                    onEditBrochureSelected(event) {
                        const file = event.target.files?.[0];
                        if (file) {
                            this.editBrochureFile = file;
                        }
                    },

                    async saveCampaignDetails() {
                        this.isSavingDetails = true;
                        try {
                            const formData = new FormData();
                            formData.append('name', this.editForm.name);
                            formData.append('caption', this.editForm.caption || '');
                            formData.append('throttle_seconds', this.editForm.throttle_seconds);
                            if (this.editBrochureFile) {
                                formData.append('brochure_file', this.editBrochureFile);
                            }

                            const res = await fetch(`{{ url('admin/whatsapp/update') }}/${this.campaignId}`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                },
                                body: formData
                            });

                            const data = await res.json();
                            if (data.success) {
                                this.name = data.name;
                                this.caption = data.caption;
                                this.throttleSeconds = data.throttle;
                                if (data.brochure_name) {
                                    this.brochureName = data.brochure_name;
                                    this.brochureUrl = data.brochure_url;
                                    this.mediaType = data.media_type;
                                }
                                this.isEditingDetails = false;
                                this.editBrochureFile = null;
                            } else {
                                alert(data.message || 'Failed to update campaign details.');
                            }
                        } catch (e) {
                            console.error('Error saving campaign details:', e);
                            alert('An error occurred while saving campaign details.');
                        } finally {
                            this.isSavingDetails = false;
                        }
                    },

                    initPolling() {
                        if (this.currentStatus === 'running' && !this.pollTimer) {
                            this.pollTimer = setInterval(() => this.fetchStatus(), 3000);
                        }
                    },

                    async fetchStatus(page = this.currentPage) {
                        try {
                            const params = new URLSearchParams({
                                page: page,
                                status: this.filterStatus,
                                search: this.searchQuery
                            });

                            const res = await fetch(`{{ url('admin/whatsapp/status') }}/${this.campaignId}?${params.toString()}`);
                            if (!res.ok) return;
                            const data = await res.json();

                            this.currentStatus = data.status;
                            this.totalRecipients = data.total;
                            this.sentCount = data.sent;
                            this.failedCount = data.failed;
                            this.progressPercent = data.progress_percent;
                            this.pauseReason = data.pause_reason;

                            if (data.recipients) {
                                this.recipients = data.recipients.data || [];
                                this.currentPage = data.recipients.current_page || 1;
                                this.lastPage = data.recipients.last_page || 1;
                                this.totalLogs = data.recipients.total || 0;
                            }

                            if (this.currentStatus !== 'running' && this.pollTimer) {
                                clearInterval(this.pollTimer);
                                this.pollTimer = null;
                            } else if (this.currentStatus === 'running' && !this.pollTimer) {
                                this.initPolling();
                            }
                        } catch (e) {
                            console.error('Failed to poll status:', e);
                        }
                    },

                    openStartModal() {
                        this.startConsentGiven = false;
                        this.selectedBrochureFile = null;
                        this.selectedBrochureName = '';
                        this.showStartModal = true;
                    },

                    async startCampaignFromDraft() {
                        if (!this.startConsentGiven) return;
                        this.isActionLoading = true;
                        try {
                            const formData = new FormData();
                            formData.append('confirm_consent', '1');
                            if (this.selectedBrochureFile) {
                                formData.append('brochure_file', this.selectedBrochureFile);
                            }

                            const res = await fetch(`{{ url('admin/whatsapp/start') }}/${this.campaignId}`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                },
                                body: formData
                            });

                            const data = await res.json();
                            if (data.success) {
                                this.showStartModal = false;
                                this.currentStatus = 'running';
                                if (this.selectedBrochureName) {
                                    this.brochureName = this.selectedBrochureName;
                                }
                                this.initPolling();
                                await this.fetchStatus();
                            } else {
                                window.location.reload();
                            }
                        } catch (e) {
                            window.location.reload();
                        } finally {
                            this.isActionLoading = false;
                        }
                    },

                    onSearchInput() {
                        clearTimeout(this.searchDebounce);
                        this.searchDebounce = setTimeout(() => {
                            this.fetchStatus(1);
                        }, 350);
                    },

                    onFilterChange() {
                        this.fetchStatus(1);
                    },

                    changePage(newPage) {
                        if (newPage < 1 || newPage > this.lastPage) return;
                        this.fetchStatus(newPage);
                    },

                    formatDate(isoString) {
                        if (!isoString) return '-';
                        try {
                            const d = new Date(isoString);
                            return d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' }) + ' ' + d.toLocaleDateString([], { month: 'short', day: 'numeric' });
                        } catch (e) {
                            return isoString;
                        }
                    },

                    async pauseCampaign() {
                        if (!confirm('Are you sure you want to pause this broadcast?')) return;
                        this.isActionLoading = true;
                        try {
                            await fetch(`{{ url('admin/whatsapp/pause') }}/${this.campaignId}`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                }
                            });
                            this.currentStatus = 'paused';
                            this.pauseReason = 'Manually paused by user.';
                            if (this.pollTimer) {
                                clearInterval(this.pollTimer);
                                this.pollTimer = null;
                            }
                            await this.fetchStatus();
                        } catch (e) {
                            window.location.reload();
                        } finally {
                            this.isActionLoading = false;
                        }
                    },

                    async resumeCampaign() {
                        this.isActionLoading = true;
                        try {
                            await fetch(`{{ url('admin/whatsapp/resume') }}/${this.campaignId}`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                }
                            });
                            this.currentStatus = 'running';
                            this.pauseReason = null;
                            this.initPolling();
                            await this.fetchStatus();
                        } catch (e) {
                            window.location.reload();
                        } finally {
                            this.isActionLoading = false;
                        }
                    },

                    async cancelCampaign() {
                        if (!confirm('Are you sure you want to cancel this broadcast? Pending messages will be skipped.')) return;
                        this.isActionLoading = true;
                        try {
                            await fetch(`{{ url('admin/whatsapp/cancel') }}/${this.campaignId}`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                }
                            });
                            this.currentStatus = 'cancelled';
                            if (this.pollTimer) {
                                clearInterval(this.pollTimer);
                                this.pollTimer = null;
                            }
                            await this.fetchStatus();
                        } catch (e) {
                            window.location.reload();
                        } finally {
                            this.isActionLoading = false;
                        }
                    },

                    async retryFailed() {
                        if (!confirm('Retry all failed messages now?')) return;
                        this.isActionLoading = true;
                        try {
                            await fetch(`{{ url('admin/whatsapp/retry') }}/${this.campaignId}`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                }
                            });
                            this.currentStatus = 'running';
                            this.initPolling();
                            await this.fetchStatus();
                        } catch (e) {
                            window.location.reload();
                        } finally {
                            this.isActionLoading = false;
                        }
                    }
                }
            });
        </script>
    @endPushOnce
</x-admin::layouts>
