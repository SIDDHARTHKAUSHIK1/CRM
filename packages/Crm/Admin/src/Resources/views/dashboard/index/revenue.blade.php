{!! view_render_event('admin.dashboard.index.revenue.before') !!}

<!-- 4 Primary Stat Cards Vue Component -->
<v-dashboard-revenue-stats>
    <!-- Shimmer -->
    <x-admin::shimmer.dashboard.index.revenue />
</v-dashboard-revenue-stats>

{!! view_render_event('admin.dashboard.index.revenue.after') !!}

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-dashboard-revenue-stats-template"
    >
        <!-- Shimmer -->
        <template v-if="isLoading">
            <x-admin::shimmer.dashboard.index.revenue />
        </template>

        <!-- 4 Primary Stat Cards Grid -->
        <template v-else>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                
                <!-- 1. Total Revenue Won (White Card) -->
                <div class="group relative flex flex-col justify-between rounded-2xl border border-slate-200/90 bg-white p-4 sm:p-5 shadow-xs transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md hover:border-emerald-400 dark:border-gray-800 dark:bg-gray-900 min-w-0">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-300 truncate">
                                @lang('admin::app.dashboard.index.revenue.won-revenue')
                            </p>
                            <div class="mt-2 min-w-0 max-w-full">
                                <h3 class="auto-fit-text text-2xl font-bold tracking-tight text-gray-900 dark:text-white xl:text-[28px] block cursor-pointer transition-all duration-200 group-hover:text-emerald-600 dark:group-hover:text-emerald-400" data-auto-fit data-min-font-size="12" data-max-font-size="28" :title="'Full Value: ' + (report.total_won_revenue ? report.total_won_revenue.formatted_total : '₹0')">
                                    @{{ report.total_won_revenue ? report.total_won_revenue.formatted_total : '₹0' }}
                                </h3>

                                <!-- In-Box Full Value Preview Badge on Hover/Scroll -->
                                <div class="mt-1.5 hidden group-hover:flex items-center gap-1.5 animate-fadeIn">
                                    <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-700 dark:text-emerald-300 bg-emerald-100/80 dark:bg-emerald-950 px-2 py-0.5 rounded-md border border-emerald-300 dark:border-emerald-700 shadow-xs">
                                        <span>🔍</span>
                                        <span>@{{ report.total_won_revenue ? report.total_won_revenue.formatted_total : '₹0' }}</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="flex h-11 w-11 sm:h-12 sm:w-12 shrink-0 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-100 dark:border-emerald-900/50 dark:bg-emerald-950/40 dark:text-emerald-400 shadow-xs">
                            <svg class="h-5 w-5 sm:h-6 sm:w-6" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2a3 3 0 0 0-3 3c0 .34.06.67.17.97L4.5 9A3.5 3.5 0 0 0 1 12.5v4A6.5 6.5 0 0 0 7.5 23h9a6.5 6.5 0 0 0 6.5-6.5v-4A3.5 3.5 0 0 0 19.5 9l-4.67-3.03c.11-.3.17-.63.17-.97a3 3 0 0 0-3-3Zm0 2a1 1 0 0 1 1 1c0 .24-.09.47-.24.64l-.16.16-1.2.8-1.2-.8-.16-.16A1 1 0 0 1 11 5a1 1 0 0 1 1-1Zm0 8a2 2 0 0 1 2 2v2a2 2 0 1 1-4 0v-2a2 2 0 0 1 2-2Z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center min-w-0">
                        <span class="inline-flex max-w-full truncate items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-700 dark:bg-emerald-950/80 dark:text-emerald-300 dark:border dark:border-emerald-800/60">
                            <span class="shrink-0">↑</span>
                            <span class="truncate">@{{ report.total_won_revenue ? Math.abs(report.total_won_revenue.progress.toFixed(0)) : 100 }}% from last 30 days</span>
                        </span>
                    </div>
                </div>

                <!-- 2. Total Revenue Lost (White Card) -->
                <div class="group relative flex flex-col justify-between rounded-2xl border border-slate-200/90 bg-white p-4 sm:p-5 shadow-xs transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md hover:border-rose-400 dark:border-gray-800 dark:bg-gray-900 min-w-0">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-300 truncate">
                                @lang('admin::app.dashboard.index.revenue.lost-revenue')
                            </p>
                            <div class="mt-2 min-w-0 max-w-full">
                                <h3 class="auto-fit-text text-2xl font-bold tracking-tight text-gray-900 dark:text-white xl:text-[28px] block cursor-pointer transition-all duration-200 group-hover:text-rose-600 dark:group-hover:text-rose-400" data-auto-fit data-min-font-size="12" data-max-font-size="28" :title="'Full Value: ' + (report.total_lost_revenue ? report.total_lost_revenue.formatted_total : '₹0')">
                                    @{{ report.total_lost_revenue ? report.total_lost_revenue.formatted_total : '₹0' }}
                                </h3>

                                <!-- In-Box Full Value Preview Badge on Hover/Scroll -->
                                <div class="mt-1.5 hidden group-hover:flex items-center gap-1.5 animate-fadeIn">
                                    <span class="inline-flex items-center gap-1 text-[11px] font-bold text-rose-700 dark:text-rose-300 bg-rose-100/80 dark:bg-rose-950 px-2 py-0.5 rounded-md border border-rose-300 dark:border-rose-700 shadow-xs">
                                        <span>🔍</span>
                                        <span>@{{ report.total_lost_revenue ? report.total_lost_revenue.formatted_total : '₹0' }}</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="flex h-11 w-11 sm:h-12 sm:w-12 shrink-0 items-center justify-center rounded-2xl bg-rose-50 text-rose-600 border border-rose-100 dark:border-rose-900/50 dark:bg-rose-950/40 dark:text-rose-400 shadow-xs">
                            <svg class="h-5 w-5 sm:h-6 sm:w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"/>
                                <polyline points="17 18 23 18 23 12"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center min-w-0">
                        <span class="inline-flex max-w-full truncate items-center gap-1 rounded-full bg-rose-50 px-2.5 py-1 text-xs font-bold text-rose-700 dark:bg-rose-950/80 dark:text-rose-300 dark:border dark:border-rose-800/60">
                            <span class="shrink-0">↓</span>
                            <span class="truncate">@{{ report.total_lost_revenue ? Math.abs(report.total_lost_revenue.progress.toFixed(0)) : 100 }}% from last 30 days</span>
                        </span>
                    </div>
                </div>

                <!-- 3. Total Leads (White Card) -->
                <div class="group relative flex flex-col justify-between rounded-2xl border border-slate-200/90 bg-white p-4 sm:p-5 shadow-xs transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md hover:border-blue-400 dark:border-gray-800 dark:bg-gray-900 min-w-0">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-300 truncate">
                                @lang('admin::app.dashboard.index.over-all.total-leads')
                            </p>
                            <div class="mt-2 min-w-0 max-w-full">
                                <h3 class="auto-fit-text text-2xl font-bold tracking-tight text-gray-900 dark:text-white xl:text-[28px] block cursor-pointer transition-all duration-200 group-hover:text-blue-600 dark:group-hover:text-blue-400" data-auto-fit data-min-font-size="12" data-max-font-size="28" :title="'Total Leads: ' + (overall.total_leads ? overall.total_leads.current : 0)">
                                    @{{ overall.total_leads ? overall.total_leads.current : 0 }}
                                </h3>

                                <!-- In-Box Full Value Preview Badge on Hover/Scroll -->
                                <div class="mt-1.5 hidden group-hover:flex items-center gap-1.5 animate-fadeIn">
                                    <span class="inline-flex items-center gap-1 text-[11px] font-bold text-blue-700 dark:text-blue-300 bg-blue-100/80 dark:bg-blue-950 px-2 py-0.5 rounded-md border border-blue-300 dark:border-blue-700 shadow-xs">
                                        <span>🔍</span>
                                        <span>@{{ overall.total_leads ? overall.total_leads.current : 0 }} Leads</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="flex h-11 w-11 sm:h-12 sm:w-12 shrink-0 items-center justify-center rounded-2xl bg-blue-50 text-blue-600 border border-blue-100 dark:border-blue-900/50 dark:bg-blue-950/40 dark:text-blue-400 shadow-xs">
                            <svg class="h-5 w-5 sm:h-6 sm:w-6" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center min-w-0">
                        <span class="inline-flex max-w-full truncate items-center gap-1 rounded-full bg-blue-50 px-2.5 py-1 text-xs font-bold text-blue-700 dark:bg-blue-950/80 dark:text-blue-300 dark:border dark:border-blue-800/60">
                            <span class="shrink-0">↑</span>
                            <span class="truncate">@{{ overall.total_leads ? Math.abs(overall.total_leads.progress.toFixed(0)) : 100 }}% from last 30 days</span>
                        </span>
                    </div>
                </div>

                <!-- 4. Avg. Lead Value (White Card) -->
                <div class="group relative flex flex-col justify-between rounded-2xl border border-slate-200/90 bg-white p-4 sm:p-5 shadow-xs transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md hover:border-amber-400 dark:border-gray-800 dark:bg-gray-900 min-w-0">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-300 truncate">
                                @lang('admin::app.dashboard.index.over-all.average-lead-value')
                            </p>
                            <div class="mt-2 min-w-0 max-w-full">
                                <h3 class="auto-fit-text text-2xl font-bold tracking-tight text-gray-900 dark:text-white xl:text-[28px] block cursor-pointer transition-all duration-200 group-hover:text-amber-600 dark:group-hover:text-amber-400" data-auto-fit data-min-font-size="12" data-max-font-size="28" :title="'Full Value: ' + (overall.average_lead_value ? overall.average_lead_value.formatted_total : '₹0')">
                                    @{{ overall.average_lead_value ? overall.average_lead_value.formatted_total : '₹0' }}
                                </h3>

                                <!-- In-Box Full Value Preview Badge on Hover/Scroll -->
                                <div class="mt-1.5 hidden group-hover:flex items-center gap-1.5 animate-fadeIn">
                                    <span class="inline-flex items-center gap-1 text-[11px] font-bold text-amber-700 dark:text-amber-300 bg-amber-100/80 dark:bg-amber-950 px-2 py-0.5 rounded-md border border-amber-300 dark:border-amber-700 shadow-xs">
                                        <span>🔍</span>
                                        <span>@{{ overall.average_lead_value ? overall.average_lead_value.formatted_total : '₹0' }}</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="flex h-11 w-11 sm:h-12 sm:w-12 shrink-0 items-center justify-center rounded-2xl bg-amber-50 text-amber-600 border border-amber-100 dark:border-amber-900/50 dark:bg-amber-950/40 dark:text-amber-400 shadow-xs font-bold text-xl sm:text-2xl">
                            ₹
                        </div>
                    </div>
                    <div class="mt-4 flex items-center min-w-0">
                        <span class="inline-flex max-w-full truncate items-center gap-1 rounded-full bg-amber-50 px-2.5 py-1 text-xs font-bold text-amber-700 dark:bg-amber-950/80 dark:text-amber-300 dark:border dark:border-amber-800/60">
                            <span class="shrink-0">↑</span>
                            <span class="truncate">@{{ overall.average_lead_value ? Math.abs(overall.average_lead_value.progress.toFixed(0)) : 100 }}% from last 30 days</span>
                        </span>
                    </div>
                </div>

            </div>
        </template>
    </script>

    <script type="module">
        app.component('v-dashboard-revenue-stats', {
            template: '#v-dashboard-revenue-stats-template',

            data() {
                return {
                    report: {},
                    overall: {},
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

                    var f1 = Object.assign({}, filters, { type: 'revenue-stats' });
                    var f2 = Object.assign({}, filters, { type: 'over-all' });

                    Promise.all([
                        this.$axios.get("{{ route('admin.dashboard.stats') }}", { params: f1 }),
                        this.$axios.get("{{ route('admin.dashboard.stats') }}", { params: f2 })
                    ])
                    .then(([res1, res2]) => {
                        this.report = res1.data.statistics || {};
                        this.overall = res2.data.statistics || {};
                        this.isLoading = false;
                    })
                    .catch(error => {
                        this.isLoading = false;
                    });
                }
            }
        });
    </script>
@endPushOnce
