{!! view_render_event('admin.dashboard.index.top_selling_proudcts.before') !!}

<!-- Top Selling Products Vue Component -->
<v-dashboard-top-selling-products>
    <!-- Shimmer -->
    <x-admin::shimmer.dashboard.index.top-selling-products />
</v-dashboard-top-selling-products>

{!! view_render_event('admin.dashboard.index.top_selling_proudcts.after') !!}

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-dashboard-top-selling-products-template"
    >
        <!-- Shimmer -->
        <template v-if="isLoading">
            <x-admin::shimmer.dashboard.index.top-selling-products />
        </template>

        <!-- Top Selling Products Card -->
        <template v-else>
            <div class="flex h-full flex-col justify-between rounded-2xl border border-slate-200/80 bg-white p-5 shadow-2xs transition-all hover:shadow-xs dark:border-gray-800 dark:bg-gray-900">
                <!-- Header -->
                <div class="flex items-start gap-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600 border border-blue-100 dark:border-blue-900/50 dark:bg-blue-950/40">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m2 7 4.41-4.41A2 2 0 0 1 7.83 2h8.34a2 2 0 0 1 1.42.59L22 7"/>
                            <path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/>
                            <path d="M15 22v-4a2 2 0 0 0-2-2h-2a2 2 0 0 0-2 2v4"/>
                            <path d="M2 7h20"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-gray-900 dark:text-white">
                            Top Products
                        </h4>
                        <p class="text-xs text-slate-400 font-medium">
                            Your best selling products
                        </p>
                    </div>
                </div>

                <!-- Product Rows -->
                <div class="mt-5 space-y-3 flex-1">
                    <template v-if="report.statistics && report.statistics.length">
                        <a
                            :href="'{{ route('admin.products.view', ':id') }}'.replace(':id', item.id)"
                            class="flex items-center justify-between border-b border-slate-100 pb-3 last:border-b-0 hover:text-blue-600 transition-colors dark:border-gray-800"
                            v-for="item in report.statistics.slice(0, 3)"
                            target="_blank"
                        >
                            <span class="text-xs font-semibold text-slate-800 dark:text-slate-200 line-clamp-1 pr-2">
                                @{{ item.name }}
                            </span>
                            <span class="shrink-0 text-xs font-bold text-gray-900 dark:text-white">
                                @{{ item.formatted_price || formatPrice(item.price) }}
                            </span>
                        </a>
                    </template>
                    <template v-else>
                        <div class="py-6 text-center text-xs text-slate-400 font-medium">
                            No product sales recorded.
                        </div>
                    </template>
                </div>

                <!-- Footer Link -->
                <div class="mt-4 pt-3 border-t border-slate-100 text-center dark:border-gray-800">
                    <a
                        href="{{ route('admin.products.index') }}"
                        class="text-xs font-bold text-blue-600 hover:text-blue-700 hover:underline transition-all"
                    >
                        View all products
                    </a>
                </div>
            </div>
        </template>
    </script>

    <script type="module">
        app.component('v-dashboard-top-selling-products', {
            template: '#v-dashboard-top-selling-products-template',

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

                    var f = Object.assign({}, filters, { type: 'top-selling-products' });

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
