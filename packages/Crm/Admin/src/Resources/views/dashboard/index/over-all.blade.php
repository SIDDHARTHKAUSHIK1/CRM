{!! view_render_event('admin.dashboard.index.over_all.before') !!}

<!-- 6 Mini Metric Cards Vue Component -->
<v-dashboard-over-all-stats>
    <!-- Shimmer -->
    <x-admin::shimmer.dashboard.index.over-all />
</v-dashboard-over-all-stats>

{!! view_render_event('admin.dashboard.index.over_all.after') !!}

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-dashboard-over-all-stats-template"
    >
        <!-- Shimmer -->
        <template v-if="isLoading">
            <x-admin::shimmer.dashboard.index.over-all />
        </template>

        <!-- 6 Mini Metric Cards Row -->
        <template v-else>
            <div class="grid grid-cols-2 gap-3.5 sm:grid-cols-3 lg:grid-cols-6">
                
                <!-- 1. New Leads -->
                <div class="flex flex-col justify-between rounded-2xl border border-slate-200/80 bg-white p-3.5 shadow-2xs transition-all hover:shadow-xs dark:border-gray-800 dark:bg-gray-900">
                    <span class="text-[11px] font-semibold text-slate-500 dark:text-slate-400">
                        New Leads
                    </span>
                    <div class="mt-2.5 flex items-center gap-2.5">
                        <svg class="h-5 w-5 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <line x1="19" y1="8" x2="19" y2="14"/>
                            <line x1="22" y1="11" x2="16" y2="11"/>
                        </svg>
                        <span class="text-2xl font-bold tracking-tight text-gray-800 dark:text-white">
                            @{{ getStageCount('new') }}
                        </span>
                    </div>
                </div>

                <!-- 2. In Negotiation -->
                <div class="flex flex-col justify-between rounded-2xl border border-slate-200/80 bg-white p-3.5 shadow-2xs transition-all hover:shadow-xs dark:border-gray-800 dark:bg-gray-900">
                    <span class="text-[11px] font-semibold text-slate-500 dark:text-slate-400">
                        In Negotiation
                    </span>
                    <div class="mt-2.5 flex items-center gap-2.5">
                        <svg class="h-5 w-5 text-amber-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m11 17 2 2a1 1 0 0 0 1.4 0l4.3-4.3a1 1 0 0 0 0-1.4l-2-2"/>
                            <path d="m7 11-2-2a1 1 0 0 1 0-1.4l4.3-4.3a1 1 0 0 1 1.4 0l2 2"/>
                            <path d="M18 11l-5-5"/>
                            <path d="M6 13l5 5"/>
                        </svg>
                        <span class="text-2xl font-bold tracking-tight text-gray-800 dark:text-white">
                            @{{ getStageCount('negotiat') }}
                        </span>
                    </div>
                </div>

                <!-- 3. In Prospect -->
                <div class="flex flex-col justify-between rounded-2xl border border-slate-200/80 bg-white p-3.5 shadow-2xs transition-all hover:shadow-xs dark:border-gray-800 dark:bg-gray-900">
                    <span class="text-[11px] font-semibold text-slate-500 dark:text-slate-400">
                        In Prospect
                    </span>
                    <div class="mt-2.5 flex items-center gap-2.5">
                        <svg class="h-5 w-5 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <circle cx="12" cy="12" r="6"/>
                            <circle cx="12" cy="12" r="2"/>
                        </svg>
                        <span class="text-2xl font-bold tracking-tight text-gray-800 dark:text-white">
                            @{{ getStageCount('prospect') }}
                        </span>
                    </div>
                </div>

                <!-- 4. Total Quotations -->
                <div class="flex flex-col justify-between rounded-2xl border border-slate-200/80 bg-white p-3.5 shadow-2xs transition-all hover:shadow-xs dark:border-gray-800 dark:bg-gray-900">
                    <span class="text-[11px] font-semibold text-slate-500 dark:text-slate-400">
                        Total Quotations
                    </span>
                    <div class="mt-2.5 flex items-center gap-2.5">
                        <svg class="h-5 w-5 text-purple-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <polyline points="14 2 14 8 20 8"/>
                            <line x1="16" y1="13" x2="8" y2="13"/>
                            <line x1="16" y1="17" x2="8" y2="17"/>
                            <polyline points="10 9 9 9 8 9"/>
                        </svg>
                        <span class="text-2xl font-bold tracking-tight text-gray-800 dark:text-white">
                            @{{ report.total_quotations ? report.total_quotations.current : 0 }}
                        </span>
                    </div>
                </div>

                <!-- 5. Total Persons -->
                <div class="flex flex-col justify-between rounded-2xl border border-slate-200/80 bg-white p-3.5 shadow-2xs transition-all hover:shadow-xs dark:border-gray-800 dark:bg-gray-900">
                    <span class="text-[11px] font-semibold text-slate-500 dark:text-slate-400">
                        Total Persons
                    </span>
                    <div class="mt-2.5 flex items-center gap-2.5">
                        <svg class="h-5 w-5 text-teal-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                        <span class="text-2xl font-bold tracking-tight text-gray-800 dark:text-white">
                            @{{ report.total_persons ? report.total_persons.current : 0 }}
                        </span>
                    </div>
                </div>

                <!-- 6. Total Organizations -->
                <div class="flex flex-col justify-between rounded-2xl border border-slate-200/80 bg-white p-3.5 shadow-2xs transition-all hover:shadow-xs dark:border-gray-800 dark:bg-gray-900">
                    <span class="text-[11px] font-semibold text-slate-500 dark:text-slate-400">
                        Total Organizations
                    </span>
                    <div class="mt-2.5 flex items-center gap-2.5">
                        <svg class="h-5 w-5 text-indigo-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="4" y="2" width="16" height="20" rx="2" ry="2"/>
                            <path d="M9 22v-4h6v4"/>
                            <path d="M8 6h.01M16 6h.01M8 10h.01M16 10h.01M8 14h.01M16 14h.01"/>
                        </svg>
                        <span class="text-2xl font-bold tracking-tight text-gray-800 dark:text-white">
                            @{{ report.total_organizations ? report.total_organizations.current : 0 }}
                        </span>
                    </div>
                </div>

            </div>
        </template>
    </script>

    <script type="module">
        app.component('v-dashboard-over-all-stats', {
            template: '#v-dashboard-over-all-stats-template',

            data() {
                return {
                    report: {},
                    openStates: [],
                    isLoading: true,
                }
            },

            mounted() {
                this.getStats({});
                this.$emitter.on('reporting-filter-updated', this.getStats);
            },

            methods: {
                getStats(filters) {
                    this.isLoading = true;

                    var f1 = Object.assign({}, filters, { type: 'over-all' });
                    var f2 = Object.assign({}, filters, { type: 'open-leads-by-states' });

                    Promise.all([
                        this.$axios.get("{{ route('admin.dashboard.stats') }}", { params: f1 }),
                        this.$axios.get("{{ route('admin.dashboard.stats') }}", { params: f2 })
                    ])
                    .then(([res1, res2]) => {
                        this.report = res1.data.statistics || {};
                        this.openStates = res2.data.statistics || [];
                        this.isLoading = false;
                    })
                    .catch(error => {
                        this.isLoading = false;
                    });
                },

                getStageCount(query) {
                    if (!this.openStates || !this.openStates.length) {
                        return 0;
                    }
                    const found = this.openStates.find(s => s.name && s.name.toLowerCase().includes(query.toLowerCase()));
                    return found ? found.total : 0;
                }
            }
        });
    </script>
@endPushOnce