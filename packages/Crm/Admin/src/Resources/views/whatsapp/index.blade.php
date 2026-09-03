<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.layouts.whatsapp')
    </x-slot>

    <div class="flex flex-col gap-4 font-sans text-sm">
        <!-- Top Navigation Header -->
        <div class="scroll-reactive-sticky sticky top-[60px] z-[1000] flex flex-wrap items-center justify-between gap-4 rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm shadow-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
            <div class="flex flex-col gap-1">
                <x-admin::breadcrumbs name="whatsapp" />
                <div class="text-xl font-bold text-gray-900 dark:text-white">
                    @lang('admin::app.layouts.whatsapp')
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2.5">
                <!-- DNC Registry Button -->
                <a
                    href="{{ route('admin.whatsapp.dnc') }}"
                    class="secondary-button"
                >
                    <span class="icon-bookmark text-sm"></span>
                    Do Not Contact List
                </a>

                <!-- Gateway QR / Link Button -->
                <a
                    href="{{ route('admin.whatsapp.gateway') }}"
                    class="secondary-button"
                >
                    <span class="icon-whatsapp text-sm text-emerald-600 dark:text-emerald-400"></span>
                    Link WhatsApp (QR)
                </a>

                <!-- Create Campaign Button -->
                @if (bouncer()->hasPermission('whatsapp.create'))
                    <a
                        href="{{ route('admin.whatsapp.create') }}"
                        class="primary-button"
                    >
                        <span class="icon-add text-sm"></span>
                        New Broadcast
                    </a>
                @endif
            </div>
        </div>

        <!-- Gateway Connection Banner -->
        <div class="flex items-center justify-between rounded-lg border p-4 shadow-sm {{ !empty($gatewayStatus['connected']) ? 'border-emerald-200 bg-emerald-50 text-emerald-950 dark:border-emerald-800/70 dark:bg-emerald-950/40 dark:text-emerald-200' : 'border-amber-200 bg-amber-50 text-amber-950 dark:border-amber-800/70 dark:bg-amber-950/40 dark:text-amber-200' }}">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-full {{ !empty($gatewayStatus['connected']) ? 'bg-emerald-200 text-emerald-800 dark:bg-emerald-800 dark:text-emerald-200' : 'bg-amber-200 text-amber-800 dark:bg-amber-800 dark:text-amber-200' }}">
                    <span class="icon-whatsapp text-xl"></span>
                </div>
                <div>
                    <h4 class="font-bold text-sm text-gray-900 dark:text-white">
                        @if (!empty($gatewayStatus['connected']))
                            WhatsApp Gateway Connected
                        @else
                            WhatsApp Gateway Not Connected
                        @endif
                    </h4>
                    <p class="text-xs text-gray-600 dark:text-gray-300 mt-0.5">
                        @if (!empty($gatewayStatus['connected']))
                            Authenticated as <strong>+{{ $gatewayStatus['number'] ?? 'WhatsApp User' }}</strong>. Ready to broadcast brochures and messages.
                        @else
                            The Node.js WhatsApp Gateway needs to be linked. Click "Link WhatsApp (QR)" to scan your login QR code.
                        @endif
                    </p>
                </div>
            </div>

            @if (empty($gatewayStatus['connected']))
                <a
                    href="{{ route('admin.whatsapp.gateway') }}"
                    class="primary-button"
                >
                    <span class="icon-whatsapp text-sm"></span>
                    Scan QR Code
                </a>
            @else
                <a
                    href="{{ route('admin.whatsapp.gateway') }}"
                    class="secondary-button"
                >
                    <span class="icon-whatsapp text-sm"></span>
                    Manage Session &amp; QR
                </a>
            @endif
        </div>

        @php
            $paginationData = [
                'current_page' => $campaigns->currentPage(),
                'last_page'    => $campaigns->lastPage(),
                'total'        => $campaigns->total(),
                'per_page'     => $campaigns->perPage(),
                'has_more'     => $campaigns->hasMorePages(),
                'first_item'   => $campaigns->firstItem(),
                'last_item'    => $campaigns->lastItem(),
            ];
        @endphp

        <!-- Vue-Powered Real-Time Infinite Scroll Campaigns History Table -->
        <v-broadcast-history
            :initial-campaigns='@json($campaigns->items())'
            :initial-pagination='@json($paginationData)'
            :initial-search='@json($search)'
            :can-create="{{ bouncer()->hasPermission('whatsapp.create') ? 'true' : 'false' }}"
            :can-delete="{{ bouncer()->hasPermission('whatsapp.delete') ? 'true' : 'false' }}"
            csrf-token="{{ csrf_token() }}"
            index-url="{{ route('admin.whatsapp.index') }}"
            create-url="{{ route('admin.whatsapp.create') }}"
            show-url-template="{{ route('admin.whatsapp.show', ':id') }}"
            delete-url-template="{{ route('admin.whatsapp.delete', ':id') }}"
        ></v-broadcast-history>
    </div>

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-broadcast-history-template"
        >
            <div class="rounded-lg border border-gray-300 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <!-- Card Header with Search & Live Stats -->
                <div class="flex flex-wrap items-center justify-between gap-4 pb-3 mb-3 border-b border-gray-200 dark:border-gray-800">
                    <div class="flex flex-wrap items-center gap-3">
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                            <span>Broadcast Campaigns History</span>
                        </h3>

                        <!-- Live Total Counter Badge -->
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 px-3 py-0.5 text-xs font-medium text-gray-600 dark:bg-gray-800 dark:text-gray-300">
                            <strong>@{{ total }}</strong> Total
                        </span>

                        <!-- Real-time Active Page Indicator -->
                        <span v-if="total > 0" class="inline-flex items-center gap-1.5 rounded-full bg-brandColor/10 px-3 py-0.5 text-xs font-semibold text-brandColor dark:bg-brandColor/20 dark:text-white">
                            Page @{{ currentVisiblePage }} of @{{ lastPage }}
                        </span>
                    </div>

                    <!-- Real-Time Search Bar -->
                    <div class="flex items-center gap-2">
                        <div class="relative">
                            <input
                                type="text"
                                v-model="searchQuery"
                                @input="onSearchInput"
                                @keyup.enter="submitSearch"
                                placeholder="Search campaign name or caption..."
                                class="w-60 md:w-80 rounded-md border border-gray-300 bg-white px-3 py-1.5 text-xs text-gray-900 shadow-sm focus:border-brandColor focus:outline-none dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-400"
                            >
                            <span v-if="isSearching" class="icon-refresh animate-spin absolute right-2.5 top-2 text-xs text-brandColor"></span>
                        </div>

                        <button
                            type="button"
                            @click="submitSearch"
                            class="primary-button"
                        >
                            Search
                        </button>

                        <button
                            v-if="searchQuery"
                            type="button"
                            @click="clearSearch"
                            class="secondary-button"
                            title="Clear search"
                        >
                            Clear
                        </button>
                    </div>
                </div>

                <!-- Active Search Filter Banner -->
                <div v-if="activeSearch" class="mb-4 flex flex-wrap items-center justify-between gap-2 rounded-md border border-brandColor/40 bg-brandColor/5 p-3 text-xs text-gray-800 dark:border-brandColor/50 dark:bg-brandColor/10 dark:text-gray-200">
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-brandColor">🔍 Active Search:</span>
                        <span>Showing matching campaigns for <strong class="text-gray-900 dark:text-white">"@{{ activeSearch }}"</strong> (@{{ total }} matching)</span>
                    </div>

                    <button
                        type="button"
                        @click="clearSearch"
                        class="font-semibold text-red-600 dark:text-red-400 hover:underline"
                    >
                        Clear Search &amp; Show All Campaigns &rarr;
                    </button>
                </div>

                <!-- Increased Preview Size & Real-Time Infinite Scroll Table Container (No Horizontal Scrollbar) -->
                <div
                    v-if="campaigns.length > 0"
                    ref="scrollContainer"
                    @scroll.passive="onContainerScroll"
                    class="max-h-[calc(100vh-210px)] min-h-[520px] overflow-x-hidden overflow-y-auto rounded-md border border-gray-200 dark:border-gray-800 relative shadow-inner"
                >
                    <table class="w-full text-left text-xs md:text-sm text-gray-600 dark:text-gray-300 table-auto">
                        <thead class="sticky top-0 z-20 border-b border-gray-200 bg-gray-50 text-xs uppercase text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 shadow-sm backdrop-blur-sm">
                            <tr>
                                <th scope="col" class="w-10 px-3 py-3">#</th>
                                <th scope="col" class="px-3 py-3">Campaign Name</th>
                                <th scope="col" class="w-28 px-3 py-3">Brochure File</th>
                                <th scope="col" class="w-24 px-3 py-3 text-center">Status</th>
                                <th scope="col" class="w-36 px-3 py-3">Progress</th>
                                <th scope="col" class="w-24 px-3 py-3">Pacing</th>
                                <th scope="col" class="w-28 px-3 py-3">Created</th>
                                <th scope="col" class="w-44 px-3 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="(campaign, index) in campaigns"
                                :key="campaign.id"
                                class="border-b border-gray-200 hover:bg-gray-50 dark:border-gray-800 dark:hover:bg-gray-800/50 transition-colors"
                            >
                                <td class="px-3 py-3 font-mono text-xs font-medium text-gray-500 dark:text-gray-400">
                                    #@{{ campaign.id }}
                                </td>

                                <td class="px-3 py-3">
                                    <a
                                        :href="getShowUrl(campaign.id)"
                                        class="font-semibold text-brandColor hover:underline dark:text-brandColor block text-sm truncate max-w-[160px] md:max-w-xs"
                                        :title="campaign.name"
                                    >
                                        @{{ campaign.name }}
                                    </a>
                                    <p v-if="campaign.caption" class="truncate text-xs text-gray-500 dark:text-gray-400 max-w-[150px] md:max-w-xs mt-0.5" :title="campaign.caption">
                                        @{{ campaign.caption }}
                                    </p>
                                </td>

                                <td class="px-3 py-3">
                                    <span class="inline-flex items-center gap-1.5 text-xs text-gray-700 dark:text-gray-300">
                                        <span :class="campaign.brochure_name ? 'icon-image' : 'icon-message'" class="text-sm text-gray-400 dark:text-gray-500 flex-shrink-0"></span>
                                        <span class="truncate max-w-[90px] md:max-w-[110px]" :title="campaign.brochure_name || 'Text only'">
                                            @{{ campaign.brochure_name || 'Text only' }}
                                        </span>
                                    </span>
                                </td>

                                <td class="px-3 py-3 text-center">
                                    <span
                                        class="inline-block rounded-full px-2 py-0.5 text-xs font-semibold capitalize whitespace-nowrap"
                                        :class="{
                                            'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-200 animate-pulse': campaign.status === 'running' || campaign.status === 'sending',
                                            'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-200': campaign.status === 'completed',
                                            'bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-200': campaign.status === 'paused',
                                            'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300': campaign.status === 'cancelled',
                                            'bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-200': campaign.status === 'draft'
                                        }"
                                    >
                                        @{{ campaign.status }}
                                    </span>
                                </td>

                                <td class="px-3 py-3">
                                    <div class="flex items-center justify-between text-xs text-gray-700 dark:text-gray-300 mb-1">
                                        <span class="text-[11px] truncate">@{{ campaign.sent_count }}/@{{ campaign.total_recipients }}</span>
                                        <span class="font-semibold text-gray-900 dark:text-white text-[11px]">@{{ campaign.progress_percent }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-1.5 dark:bg-gray-700 overflow-hidden">
                                        <div
                                            class="bg-emerald-500 h-1.5 rounded-full transition-all duration-300"
                                            :style="'width: ' + campaign.progress_percent + '%'"
                                        ></div>
                                    </div>
                                    <p v-if="campaign.failed_count > 0" class="text-[10px] font-medium text-red-500 dark:text-red-400 mt-0.5">
                                        @{{ campaign.failed_count }} failed
                                    </p>
                                </td>

                                <td class="px-3 py-3 text-xs text-gray-700 dark:text-gray-300 font-mono whitespace-nowrap">
                                    @{{ campaign.throttle_seconds }}s/msg
                                </td>

                                <td class="px-3 py-3 text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                    @{{ formatDate(campaign.created_at) }}
                                </td>

                                <td class="px-3 py-3 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <!-- Start Broadcast Direct CTA (if draft) -->
                                        <a
                                            v-if="campaign.status === 'draft'"
                                            :href="getShowUrl(campaign.id)"
                                            class="primary-button"
                                        >
                                            Start
                                        </a>

                                        <!-- Manage Dashboard CTA -->
                                        <a
                                            :href="getShowUrl(campaign.id)"
                                            class="secondary-button"
                                        >
                                            Manage
                                        </a>

                                        <!-- Delete Action -->
                                        <button
                                            v-if="canDelete"
                                            type="button"
                                            @click="deleteCampaign(campaign)"
                                            class="icon-delete cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-100 dark:hover:bg-gray-800 text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300"
                                            title="Delete Broadcast"
                                        >
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Real-Time Loading More Spinner on Scroll -->
                    <div
                        v-if="isLoadingMore"
                        class="py-4 text-center text-xs font-semibold text-brandColor flex items-center justify-center gap-2 border-t border-gray-100 dark:border-gray-800 bg-white/80 dark:bg-gray-900/80 backdrop-blur-sm"
                    >
                        <span class="icon-refresh animate-spin text-sm"></span>
                        Loading more broadcast campaigns in real time...
                    </div>

                    <!-- End of Campaigns Message -->
                    <div
                        v-if="!hasMore && campaigns.length > 10"
                        class="py-3 text-center text-xs text-gray-400 dark:text-gray-500 border-t border-gray-100 dark:border-gray-800"
                    >
                        ✓ All @{{ total }} campaigns loaded
                    </div>
                </div>

                <!-- Dynamic Bottom Footer with Real-Time Counter & Quick Jump Controls -->
                <div v-if="campaigns.length > 0" class="mt-4 flex flex-wrap items-center justify-between gap-4 border-t border-gray-200 pt-4 dark:border-gray-800 text-xs text-gray-600 dark:text-gray-300">
                    <div class="flex items-center gap-2">
                        <span>
                            Showing <strong>@{{ campaigns.length }}</strong> of <strong>@{{ total }}</strong> total broadcast campaigns
                        </span>
                        <span class="text-gray-400 dark:text-gray-600">•</span>
                        <span class="font-medium text-gray-800 dark:text-gray-200">
                            Active Page: <strong>@{{ currentVisiblePage }}</strong> / <strong>@{{ lastPage }}</strong>
                        </span>
                    </div>

                    <div class="flex items-center gap-2">
                        <button
                            v-if="hasMore"
                            type="button"
                            @click="fetchNextPage"
                            :disabled="isLoadingMore"
                            class="secondary-button"
                        >
                            <span v-if="isLoadingMore" class="icon-refresh animate-spin text-xs"></span>
                            <span v-else>&darr;</span>
                            Load More Campaigns
                        </button>
                        <span v-else class="text-xs text-emerald-600 dark:text-emerald-400 font-medium">
                            ✓ Complete list loaded
                        </span>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-if="campaigns.length === 0 && !isSearching" class="grid justify-center justify-items-center gap-3.5 py-12 text-center">
                    <div class="flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 text-gray-400 dark:bg-gray-800 dark:text-gray-500">
                        <span class="icon-whatsapp text-3xl"></span>
                    </div>

                    <div class="flex flex-col items-center">
                        <p class="text-base font-semibold text-gray-700 dark:text-gray-200">
                            <template v-if="activeSearch">
                                No Broadcast Campaigns Found for "@{{ activeSearch }}"
                            </template>
                            <template v-else>
                                No WhatsApp Broadcasts Yet
                            </template>
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 max-w-sm mt-1">
                            <template v-if="activeSearch">
                                Try searching for another campaign name or clear your search query.
                            </template>
                            <template v-else>
                                Create your first WhatsApp campaign by uploading your recipient contact list and product brochure.
                            </template>
                        </p>
                    </div>

                    <button
                        v-if="activeSearch"
                        type="button"
                        @click="clearSearch"
                        class="secondary-button mt-2"
                    >
                        Clear Search Filter
                    </button>
                    <a
                        v-else-if="canCreate"
                        :href="createUrl"
                        class="primary-button mt-2"
                    >
                        <span class="icon-add text-sm"></span>
                        Create First Broadcast
                    </a>
                </div>
            </div>
        </script>

        <script type="module">
            app.component('v-broadcast-history', {
                template: '#v-broadcast-history-template',

                props: {
                    initialCampaigns: {
                        type: Array,
                        default: () => []
                    },
                    initialPagination: {
                        type: Object,
                        default: () => ({})
                    },
                    initialSearch: {
                        type: String,
                        default: ''
                    },
                    canCreate: {
                        type: Boolean,
                        default: false
                    },
                    canDelete: {
                        type: Boolean,
                        default: false
                    },
                    csrfToken: {
                        type: String,
                        default: ''
                    },
                    indexUrl: {
                        type: String,
                        default: ''
                    },
                    createUrl: {
                        type: String,
                        default: ''
                    },
                    showUrlTemplate: {
                        type: String,
                        default: ''
                    },
                    deleteUrlTemplate: {
                        type: String,
                        default: ''
                    }
                },

                data() {
                    return {
                        campaigns: Array.isArray(this.initialCampaigns) ? [...this.initialCampaigns] : [],
                        searchQuery: this.initialSearch || '',
                        activeSearch: this.initialSearch || '',
                        currentPage: this.initialPagination?.current_page || 1,
                        currentVisiblePage: this.initialPagination?.current_page || 1,
                        lastPage: this.initialPagination?.last_page || 1,
                        total: this.initialPagination?.total || 0,
                        perPage: this.initialPagination?.per_page || 15,
                        hasMore: !!this.initialPagination?.has_more,
                        isLoadingMore: false,
                        isSearching: false,
                        searchDebounceTimer: null,
                        pollTimer: null,
                    };
                },

                mounted() {
                    this.startStatusPolling();
                },

                beforeUnmount() {
                    if (this.pollTimer) {
                        clearInterval(this.pollTimer);
                    }
                    if (this.searchDebounceTimer) {
                        clearTimeout(this.searchDebounceTimer);
                    }
                },

                methods: {
                    getShowUrl(id) {
                        return this.showUrlTemplate.replace(':id', id);
                    },

                    getDeleteUrl(id) {
                        return this.deleteUrlTemplate.replace(':id', id);
                    },

                    formatDate(dateStr) {
                        if (!dateStr) return '-';
                        try {
                            const d = new Date(dateStr);
                            return d.toLocaleDateString('en-US', {
                                month: 'short',
                                day: '2-digit',
                                year: 'numeric',
                                hour: '2-digit',
                                minute: '2-digit',
                                hour12: false
                            });
                        } catch (e) {
                            return dateStr;
                        }
                    },

                    onSearchInput() {
                        if (this.searchDebounceTimer) {
                            clearTimeout(this.searchDebounceTimer);
                        }
                        this.searchDebounceTimer = setTimeout(() => {
                            this.fetchCampaigns(1, true);
                        }, 350);
                    },

                    submitSearch() {
                        if (this.searchDebounceTimer) {
                            clearTimeout(this.searchDebounceTimer);
                        }
                        this.fetchCampaigns(1, true);
                    },

                    clearSearch() {
                        this.searchQuery = '';
                        this.activeSearch = '';
                        this.fetchCampaigns(1, true);
                    },

                    async fetchCampaigns(page = 1, reset = false) {
                        if (reset) {
                            this.isSearching = true;
                        } else {
                            this.isLoadingMore = true;
                        }

                        try {
                            const params = new URLSearchParams({
                                page: page,
                                search: this.searchQuery,
                                format: 'json'
                            });

                            const response = await fetch(`${this.indexUrl}?${params.toString()}`, {
                                headers: {
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            });

                            if (!response.ok) throw new Error('Failed to load campaigns');

                            const res = await response.json();
                            const newItems = res.data || [];

                            if (reset) {
                                this.campaigns = newItems;
                                this.activeSearch = this.searchQuery;
                                if (this.$refs.scrollContainer) {
                                    this.$refs.scrollContainer.scrollTop = 0;
                                }
                            } else {
                                // Deduplicate items by ID
                                const existingIds = new Set(this.campaigns.map(c => c.id));
                                const uniqueNew = newItems.filter(c => !existingIds.has(c.id));
                                this.campaigns = [...this.campaigns, ...uniqueNew];
                            }

                            this.currentPage = res.current_page;
                            this.lastPage = res.last_page;
                            this.total = res.total;
                            this.perPage = res.per_page || 15;
                            this.hasMore = !!res.has_more;

                            this.calculateVisiblePage();
                        } catch (error) {
                            console.error('Error fetching broadcast campaigns:', error);
                        } finally {
                            this.isSearching = false;
                            this.isLoadingMore = false;
                        }
                    },

                    fetchNextPage() {
                        if (this.hasMore && !this.isLoadingMore) {
                            this.fetchCampaigns(this.currentPage + 1, false);
                        }
                    },

                    onContainerScroll(e) {
                        const target = e.target;
                        const { scrollTop, scrollHeight, clientHeight } = target;

                        // 1. Real-Time infinite scroll trigger near bottom (within 220px)
                        if (scrollHeight - scrollTop - clientHeight < 220) {
                            this.fetchNextPage();
                        }

                        // 2. Real-Time visible page calculation as user scrolls
                        const rowHeight = 62; // approx row height in px
                        const topItemIndex = Math.floor(scrollTop / rowHeight);
                        const calculatedPage = Math.min(
                            this.lastPage,
                            Math.max(1, Math.floor(topItemIndex / this.perPage) + 1)
                        );

                        if (this.currentVisiblePage !== calculatedPage) {
                            this.currentVisiblePage = calculatedPage;
                        }
                    },

                    calculateVisiblePage() {
                        if (!this.$refs.scrollContainer) return;
                        const scrollTop = this.$refs.scrollContainer.scrollTop;
                        const rowHeight = 62;
                        const topItemIndex = Math.floor(scrollTop / rowHeight);
                        this.currentVisiblePage = Math.min(
                            this.lastPage,
                            Math.max(1, Math.floor(topItemIndex / this.perPage) + 1)
                        );
                    },

                    async deleteCampaign(campaign) {
                        if (!confirm(`Are you sure you want to delete broadcast campaign "${campaign.name}"? This action cannot be undone.`)) {
                            return;
                        }

                        try {
                            const response = await fetch(this.getDeleteUrl(campaign.id), {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': this.csrfToken,
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            });

                            if (response.ok) {
                                this.campaigns = this.campaigns.filter(c => c.id !== campaign.id);
                                this.total = Math.max(0, this.total - 1);
                            } else {
                                alert('Could not delete campaign. Please try again.');
                            }
                        } catch (err) {
                            console.error('Delete campaign error:', err);
                            alert('An error occurred while deleting the campaign.');
                        }
                    },

                    startStatusPolling() {
                        this.pollTimer = setInterval(() => {
                            const hasActive = this.campaigns.some(c => c.status === 'running' || c.status === 'sending');
                            if (hasActive) {
                                // Refresh current visible page data in background
                                this.refreshLiveCampaigns();
                            }
                        }, 4000);
                    },

                    async refreshLiveCampaigns() {
                        try {
                            const activeCampaigns = this.campaigns.filter(c => c.status === 'running' || c.status === 'sending');
                            for (const campaign of activeCampaigns) {
                                const res = await fetch(`/admin/whatsapp/status/${campaign.id}`, {
                                    headers: { 'Accept': 'application/json' }
                                });
                                if (res.ok) {
                                    const data = await res.json();
                                    campaign.status = data.status || campaign.status;
                                    campaign.sent_count = data.sent_count ?? campaign.sent_count;
                                    campaign.failed_count = data.failed_count ?? campaign.failed_count;
                                    campaign.progress_percent = data.progress_percent ?? campaign.progress_percent;
                                }
                            }
                        } catch (e) {
                            // silent poll failure
                        }
                    }
                }
            });
        </script>
    @endPushOnce
</x-admin::layouts>
