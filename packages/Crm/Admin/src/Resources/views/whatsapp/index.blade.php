<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.layouts.whatsapp')
    </x-slot>

    <div class="flex flex-col gap-4 font-sans text-sm">
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
            <div class="rounded-2xl border border-slate-200/90 bg-white p-5 shadow-xs dark:border-gray-800 dark:bg-gray-900">
                <!-- Card Header with Breadcrumbs, Title, Live Stats & Search -->
                <div class="flex flex-wrap items-center justify-between gap-4 pb-4 mb-4 border-b border-gray-200 dark:border-gray-800">
                    <div class="flex flex-col gap-1">
                        <x-admin::breadcrumbs name="whatsapp" />
                        
                        <div class="flex items-center gap-3 flex-wrap">
                            <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                                Broadcast Campaigns History
                            </h1>

                            <!-- Live Total Counter Badge -->
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 text-gray-900 dark:bg-gray-800 dark:text-white border border-gray-300 dark:border-gray-700 px-3 py-0.5 text-xs font-bold shadow-2xs">
                                <strong>@{{ total }}</strong> Total
                            </span>

                            <!-- Real-time Active Page Indicator -->
                            <span v-if="total > 0" class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 px-3 py-0.5 text-xs font-semibold text-gray-900 dark:bg-gray-800 dark:text-white border border-gray-200 dark:border-gray-700">
                                Page @{{ currentVisiblePage }} of @{{ lastPage }}
                            </span>
                        </div>
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
                                class="w-60 md:w-80 rounded-xl border border-gray-300 bg-white px-3.5 py-2 text-xs text-gray-900 shadow-2xs focus:border-brandColor focus:outline-none dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-400"
                            >
                            <span v-if="isSearching" class="icon-refresh animate-spin absolute right-3 top-2.5 text-xs text-brandColor"></span>
                        </div>

                        <button
                            type="button"
                            @click="submitSearch"
                            class="primary-button !px-4 !py-2 !text-xs !font-bold"
                        >
                            Search
                        </button>

                        <button
                            v-if="searchQuery"
                            type="button"
                            @click="clearSearch"
                            class="secondary-button !px-3 !py-2 !text-xs !font-bold"
                            title="Clear search"
                        >
                            Clear
                        </button>
                    </div>
                </div>

                <!-- Active Search Filter Banner -->
                <div v-if="activeSearch" class="mb-4 flex flex-wrap items-center justify-between gap-2 rounded-xl border border-brandColor/40 bg-brandColor/5 p-3.5 text-xs text-gray-800 dark:border-brandColor/50 dark:bg-brandColor/10 dark:text-gray-200">
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

                <!-- Full Size Real-Time Infinite Scroll Table Container -->
                <div
                    v-if="campaigns.length > 0"
                    ref="scrollContainer"
                    @scroll.passive="onContainerScroll"
                    class="w-full overflow-x-auto rounded-xl border border-slate-200/80 dark:border-gray-800 shadow-2xs"
                >
                    <table class="w-full text-left text-xs md:text-sm text-slate-700 dark:text-slate-200">
                        <thead class="sticky top-0 z-20 border-b border-gray-200 bg-slate-50 text-xs font-bold uppercase tracking-wider text-slate-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 shadow-xs backdrop-blur-sm">
                            <tr>
                                <th scope="col" class="px-3 py-3 w-[24%]">Campaign Name</th>
                                <th scope="col" class="px-3 py-3 w-[14%] whitespace-nowrap">Brochure File</th>
                                <th scope="col" class="px-3 py-3 w-[12%] text-center whitespace-nowrap">Status</th>
                                <th scope="col" class="px-3 py-3 w-[14%] whitespace-nowrap">Progress</th>
                                <th scope="col" class="px-3 py-3 w-[10%] whitespace-nowrap">Pacing</th>
                                <th scope="col" class="px-3 py-3 w-[12%] whitespace-nowrap">Created</th>
                                <th scope="col" class="px-4 pr-6 py-3 w-[14%] text-right whitespace-nowrap">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="(campaign, index) in campaigns"
                                :key="campaign.id"
                                class="border-b border-gray-200 hover:bg-blue-50/40 dark:border-gray-800 dark:hover:bg-gray-800/60 transition-colors"
                            >
                                <td class="px-3 py-3">
                                    <a
                                        :href="getShowUrl(campaign.id)"
                                        class="font-bold text-blue-600 hover:text-blue-700 hover:underline dark:text-blue-400 block text-sm truncate max-w-[200px] md:max-w-xs"
                                        :title="campaign.name"
                                    >
                                        @{{ campaign.name }}
                                    </a>
                                    <p v-if="campaign.caption" class="truncate text-xs font-medium text-slate-500 dark:text-slate-400 max-w-[190px] md:max-w-xs mt-0.5" :title="campaign.caption">
                                        @{{ campaign.caption }}
                                    </p>
                                </td>

                                <td class="px-3 py-3">
                                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-700 dark:text-slate-200">
                                        <span :class="campaign.brochure_name ? 'icon-image' : 'icon-message'" class="text-sm text-blue-500 dark:text-blue-400 shrink-0"></span>
                                        <span class="truncate max-w-[90px] md:max-w-[110px]" :title="campaign.brochure_name || 'Text only'">
                                            @{{ campaign.brochure_name || 'Text only' }}
                                        </span>
                                    </span>
                                </td>

                                <td class="px-3 py-3 text-center">
                                    <span
                                        class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-xs font-bold capitalize whitespace-nowrap shadow-2xs"
                                        :class="{
                                            'bg-blue-100 text-blue-700 dark:bg-blue-900/60 dark:text-blue-300 animate-pulse': campaign.status === 'running' || campaign.status === 'sending',
                                            'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/60 dark:text-emerald-300': campaign.status === 'completed',
                                            'bg-amber-100 text-amber-800 dark:bg-amber-900/60 dark:text-amber-300': campaign.status === 'paused',
                                            'bg-slate-100 text-slate-700 dark:bg-gray-800 dark:text-gray-300': campaign.status === 'cancelled',
                                            'bg-purple-100 text-purple-700 dark:bg-purple-900/60 dark:text-purple-300': campaign.status === 'draft'
                                        }"
                                    >
                                        <span v-if="campaign.status === 'completed'">✓</span>
                                        <span v-else-if="campaign.status === 'running' || campaign.status === 'sending'">▶</span>
                                        @{{ campaign.status }}
                                    </span>
                                </td>

                                <td class="px-3 py-3">
                                    <div class="flex items-center justify-between text-xs font-bold text-gray-900 dark:text-white mb-1">
                                        <span class="text-xs font-semibold text-gray-900 dark:text-white truncate">@{{ campaign.sent_count }}/@{{ campaign.total_recipients }}</span>
                                        <span class="font-bold text-gray-900 dark:text-white text-xs">@{{ campaign.progress_percent }}%</span>
                                    </div>
                                    <div class="w-full bg-slate-200 rounded-full h-2 dark:bg-gray-700 overflow-hidden">
                                        <div
                                            class="bg-emerald-500 h-2 rounded-full transition-all duration-300"
                                            :style="'width: ' + campaign.progress_percent + '%'"
                                        ></div>
                                    </div>
                                    <p v-if="campaign.failed_count > 0" class="text-[11px] font-bold text-gray-900 dark:text-white mt-0.5">
                                        @{{ campaign.failed_count }} failed
                                    </p>
                                </td>

                                <td class="px-3 py-3 text-xs font-bold text-gray-900 dark:text-white whitespace-nowrap">
                                    🕒 @{{ campaign.throttle_seconds }}s/msg
                                </td>

                                <td class="px-3 py-3 text-xs font-medium text-slate-600 dark:text-slate-300 whitespace-nowrap">
                                    📅 @{{ formatDate(campaign.created_at) }}
                                </td>

                                <td class="px-4 pr-5 py-3 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-2">
                                        <!-- Start Broadcast Direct CTA (if draft) -->
                                        <a
                                            v-if="campaign.status === 'draft'"
                                            :href="getShowUrl(campaign.id)"
                                            class="inline-flex items-center gap-1 rounded-lg px-3 py-1.5 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-xs transition"
                                        >
                                            <span>▶</span> Start
                                        </a>

                                        <!-- Manage Dashboard CTA -->
                                        <a
                                            :href="getShowUrl(campaign.id)"
                                            class="inline-flex items-center gap-1 rounded-lg px-3 py-1.5 text-xs font-bold text-blue-600 border border-blue-200 bg-blue-50 hover:bg-blue-100 dark:border-blue-800 dark:bg-gray-800 dark:text-blue-300 shadow-xs transition"
                                        >
                                            <span class="text-sm">⚙</span> Manage
                                        </a>

                                        <!-- Delete Action -->
                                        <button
                                            v-if="canDelete"
                                            type="button"
                                            @click="deleteCampaign(campaign)"
                                            class="cursor-pointer rounded-lg p-1.5 text-base transition-all hover:bg-red-50 text-red-500 hover:text-red-700 dark:hover:bg-red-950/40 dark:text-red-400"
                                            title="Delete Broadcast"
                                        >
                                            🗑
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
                    window.addEventListener('scroll', this.onWindowScroll, { passive: true });
                },

                beforeUnmount() {
                    window.removeEventListener('scroll', this.onWindowScroll);
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

                    onWindowScroll() {
                        if ((window.innerHeight + window.scrollY) >= (document.documentElement.scrollHeight - 300)) {
                            this.fetchNextPage();
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
