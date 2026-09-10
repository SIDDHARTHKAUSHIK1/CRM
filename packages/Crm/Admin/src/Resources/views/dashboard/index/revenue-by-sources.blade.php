{!! view_render_event('admin.dashboard.index.revenue_by_sources.before') !!}

<!-- Revenue by Sources Vue Component -->
<v-dashboard-revenue-by-sources>
    <!-- Shimmer -->
    <x-admin::shimmer.dashboard.index.revenue-by-sources />
</v-dashboard-revenue-by-sources>

{!! view_render_event('admin.dashboard.index.revenue_by_sources.after') !!}

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-dashboard-revenue-by-sources-template"
    >
        <!-- Shimmer -->
        <template v-if="isLoading">
            <x-admin::shimmer.dashboard.index.revenue-by-sources />
        </template>

        <!-- Revenue by Sources Card -->
        <template v-else>
            <div class="flex h-full flex-col justify-between rounded-2xl border border-slate-200/80 bg-white p-5 shadow-2xs transition-all hover:shadow-xs dark:border-gray-800 dark:bg-gray-900">
                <!-- Header -->
                <div class="flex items-start gap-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100 dark:border-emerald-900/50 dark:bg-emerald-950/40">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/>
                            <path d="M3 6h18"/>
                            <path d="M16 10a4 4 0 0 1-8 0"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-gray-900 dark:text-white">
                            Revenue by Source
                        </h4>
                        <p class="text-xs text-slate-400 font-medium">
                            Where your business comes from
                        </p>
                    </div>
                </div>

                <!-- Items List -->
                <div class="mt-5 space-y-3.5 flex-1">
                    <template v-if="report.statistics && report.statistics.length">
                        <div
                            class="flex items-center justify-between border-b border-dashed border-slate-100 pb-2.5 last:border-b-0 last:pb-0 dark:border-gray-800"
                            v-for="stat in report.statistics"
                        >
                            <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">
                                @{{ stat.name || 'Direct' }}
                            </span>
                            <span class="text-sm font-bold text-gray-900 dark:text-white">
                                @{{ formatPrice(stat.total) }}
                            </span>
                        </div>
                    </template>
                    <template v-else>
                        <div class="py-6 text-center text-xs text-slate-400 font-medium">
                            No source revenue recorded for this period.
                        </div>
                    </template>
                </div>
            </div>
        </template>
    </script>

    <script type="module">
        app.component('v-dashboard-revenue-by-sources', {
            template: '#v-dashboard-revenue-by-sources-template',

            data() {
                return {
                    report: {
                        statistics: []
                    },
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

                    var f = Object.assign({}, filters, { type: 'revenue-by-sources' });

                    this.$axios.get("{{ route('admin.dashboard.stats') }}", { params: f })
                        .then(response => {
                            this.report = response.data;
                            this.isLoading = false;
                        })
                        .catch(error => {
                            this.isLoading = false;
                        });
                },

                formatPrice(amount) {
                    let num = parseFloat(amount || 0);
                    let currency = '₹';
                    return currency + num.toLocaleString('en-IN', { maximumFractionDigits: 0 });
                }
            }
        });
    </script>
@endPushOnce
