{!! view_render_event('admin.dashboard.index.revenue_by_types.before') !!}

<!-- Revenue by Types Vue Component -->
<v-dashboard-revenue-by-types>
    <!-- Shimmer -->
    <x-admin::shimmer.dashboard.index.revenue-by-types />
</v-dashboard-revenue-by-types>

{!! view_render_event('admin.dashboard.index.revenue_by_types.after') !!}

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-dashboard-revenue-by-types-template"
    >
        <!-- Shimmer -->
        <template v-if="isLoading">
            <x-admin::shimmer.dashboard.index.revenue-by-types />
        </template>

        <!-- Revenue by Types Card -->
        <template v-else>
            <div class="flex h-full flex-col justify-between rounded-2xl border border-slate-200/80 bg-white p-5 shadow-2xs transition-all hover:shadow-xs dark:border-gray-800 dark:bg-gray-900">
                <!-- Header -->
                <div class="flex items-start gap-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-purple-50 text-purple-600 border border-purple-100 dark:border-purple-900/50 dark:bg-purple-950/40">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 2H2v10l9.29 9.29c.94.94 2.48.94 3.42 0l6.58-6.58c.94-.94.94-2.48 0-3.42L12 2Z"/>
                            <path d="M7 7h.01"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-gray-900 dark:text-white">
                            Revenue by Type
                        </h4>
                        <p class="text-xs text-slate-400 font-medium">
                            What your customers are buying
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
                                @{{ stat.name || 'New Business' }}
                            </span>
                            <span class="text-sm font-bold text-gray-900 dark:text-white">
                                @{{ formatPrice(stat.total) }}
                            </span>
                        </div>
                    </template>
                    <template v-else>
                        <div class="py-6 text-center text-xs text-slate-400 font-medium">
                            No type revenue recorded for this period.
                        </div>
                    </template>
                </div>
            </div>
        </template>
    </script>

    <script type="module">
        app.component('v-dashboard-revenue-by-types', {
            template: '#v-dashboard-revenue-by-types-template',

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

                    var f = Object.assign({}, filters, { type: 'revenue-by-types' });

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
