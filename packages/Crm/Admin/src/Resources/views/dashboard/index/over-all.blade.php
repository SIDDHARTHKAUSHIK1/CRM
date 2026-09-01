{!! view_render_event('admin.dashboard.index.over_all.before') !!}

<!-- Over Details Vue Component -->
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

        <!-- Total Sales Section -->
        <template v-else>
            <!-- Stats Cards -->
            <div class="grid grid-cols-3 gap-4 max-md:grid-cols-2 max-sm:grid-cols-1">
                <!-- Average Revenue Card -->
                <div class="flex min-w-0 flex-col justify-between gap-2 overflow-hidden rounded-lg border border-gray-300 bg-white px-4 py-4 dark:border-gray-800 dark:bg-gray-900">
                    <p class="truncate text-xs font-medium text-gray-600 dark:text-gray-300">
                        @lang('admin::app.dashboard.index.over-all.average-lead-value')
                    </p>

                    <div class="flex min-w-0 flex-wrap items-baseline justify-between gap-1.5">
                        <p
                            class="min-w-0 font-bold dark:text-gray-300"
                            :class="[(String(report.statistics.average_lead_value.formatted_total || '')).length > 14 ? 'text-lg' : (String(report.statistics.average_lead_value.formatted_total || '')).length > 10 ? 'text-xl' : 'text-2xl']"
                            :title="report.statistics.average_lead_value.formatted_total"
                        >
                            @{{ report.statistics.average_lead_value.formatted_total }}
                        </p>

                        <div
                            class="inline-flex shrink-0 items-center gap-0.5 rounded-full px-1.5 py-0.5 text-xs font-semibold"
                            :class="[report.statistics.average_lead_value.progress < 0 ? 'bg-red-50 text-red-600 dark:bg-red-950/40 dark:text-red-400' : 'bg-green-50 text-green-600 dark:bg-green-950/40 dark:text-green-400']"
                        >
                            <span
                                class="text-xs font-bold"
                                :class="[report.statistics.average_lead_value.progress < 0 ? 'icon-stats-down' : 'icon-stats-up']"
                            ></span>

                            <p class="text-xs font-semibold">
                                @{{ Math.abs(report.statistics.average_lead_value.progress.toFixed(2)) }}%
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Total Leads Card -->
                <div class="flex min-w-0 flex-col justify-between gap-2 overflow-hidden rounded-lg border border-gray-300 bg-white px-4 py-4 dark:border-gray-800 dark:bg-gray-900">
                    <p class="truncate text-xs font-medium text-gray-600 dark:text-gray-300">
                        @lang('admin::app.dashboard.index.over-all.total-leads')
                    </p>

                    <div class="flex min-w-0 flex-wrap items-baseline justify-between gap-1.5">
                        <p class="min-w-0 text-xl font-bold dark:text-gray-300">
                            @{{ report.statistics.total_leads.current }}
                        </p>

                        <div
                            class="inline-flex shrink-0 items-center gap-0.5 rounded-full px-1.5 py-0.5 text-xs font-semibold"
                            :class="[report.statistics.total_leads.progress < 0 ? 'bg-red-50 text-red-600 dark:bg-red-950/40 dark:text-red-400' : 'bg-green-50 text-green-600 dark:bg-green-950/40 dark:text-green-400']"
                        >
                            <span
                                class="text-xs font-bold"
                                :class="[report.statistics.total_leads.progress < 0 ? 'icon-stats-down' : 'icon-stats-up']"
                            ></span>

                            <p class="text-xs font-semibold">
                                @{{ Math.abs(report.statistics.total_leads.progress.toFixed(2)) }}%
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Average Lead Per Day -->
                <div class="flex min-w-0 flex-col justify-between gap-2 overflow-hidden rounded-lg border border-gray-300 bg-white px-4 py-4 dark:border-gray-800 dark:bg-gray-900">
                    <p class="truncate text-xs font-medium text-gray-600 dark:text-gray-300">
                        @lang('admin::app.dashboard.index.over-all.average-leads-per-day')
                    </p>

                    <div class="flex min-w-0 flex-wrap items-baseline justify-between gap-1.5">
                        <p class="min-w-0 text-xl font-bold dark:text-gray-300">
                            @{{ report.statistics.average_leads_per_day.current.toFixed(2) }}
                        </p>

                        <div
                            class="inline-flex shrink-0 items-center gap-0.5 rounded-full px-1.5 py-0.5 text-xs font-semibold"
                            :class="[report.statistics.average_leads_per_day.progress < 0 ? 'bg-red-50 text-red-600 dark:bg-red-950/40 dark:text-red-400' : 'bg-green-50 text-green-600 dark:bg-green-950/40 dark:text-green-400']"
                        >
                            <span
                                class="text-xs font-bold"
                                :class="[report.statistics.average_leads_per_day.progress < 0 ? 'icon-stats-down' : 'icon-stats-up']"
                            ></span>

                            <p class="text-xs font-semibold">
                                @{{ Math.abs(report.statistics.average_leads_per_day.progress.toFixed(2)) }}%
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Total Quotes -->
                <div class="flex min-w-0 flex-col justify-between gap-2 overflow-hidden rounded-lg border border-gray-300 bg-white px-4 py-4 dark:border-gray-800 dark:bg-gray-900">
                    <p class="truncate text-xs font-medium text-gray-600 dark:text-gray-300">
                        @lang('admin::app.dashboard.index.over-all.total-quotations')
                    </p>

                    <div class="flex min-w-0 flex-wrap items-baseline justify-between gap-1.5">
                        <p class="min-w-0 text-xl font-bold dark:text-gray-300">
                            @{{ report.statistics.total_quotations.current }}
                        </p>

                        <div
                            class="inline-flex shrink-0 items-center gap-0.5 rounded-full px-1.5 py-0.5 text-xs font-semibold"
                            :class="[report.statistics.total_quotations.progress < 0 ? 'bg-red-50 text-red-600 dark:bg-red-950/40 dark:text-red-400' : 'bg-green-50 text-green-600 dark:bg-green-950/40 dark:text-green-400']"
                        >
                            <span
                                class="text-xs font-bold"
                                :class="[report.statistics.total_quotations.progress < 0 ? 'icon-stats-down' : 'icon-stats-up']"
                            ></span>

                            <p class="text-xs font-semibold">
                                @{{ Math.abs(report.statistics.total_quotations.progress.toFixed(2)) }}%
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Total Persons -->
                <div class="flex min-w-0 flex-col justify-between gap-2 overflow-hidden rounded-lg border border-gray-300 bg-white px-4 py-4 dark:border-gray-800 dark:bg-gray-900">
                    <p class="truncate text-xs font-medium text-gray-600 dark:text-gray-300">
                        @lang('admin::app.dashboard.index.over-all.total-persons')
                    </p>

                    <div class="flex min-w-0 flex-wrap items-baseline justify-between gap-1.5">
                        <p class="min-w-0 text-xl font-bold dark:text-gray-300">
                            @{{ report.statistics.total_persons.current }}
                        </p>

                        <div
                            class="inline-flex shrink-0 items-center gap-0.5 rounded-full px-1.5 py-0.5 text-xs font-semibold"
                            :class="[report.statistics.total_persons.progress < 0 ? 'bg-red-50 text-red-600 dark:bg-red-950/40 dark:text-red-400' : 'bg-green-50 text-green-600 dark:bg-green-950/40 dark:text-green-400']"
                        >
                            <span
                                class="text-xs font-bold"
                                :class="[report.statistics.total_persons.progress < 0 ? 'icon-stats-down' : 'icon-stats-up']"
                            ></span>

                            <p class="text-xs font-semibold">
                                @{{ Math.abs(report.statistics.total_persons.progress.toFixed(2)) }}%
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Total Organizations -->
                <div class="flex min-w-0 flex-col justify-between gap-2 overflow-hidden rounded-lg border border-gray-300 bg-white px-4 py-4 dark:border-gray-800 dark:bg-gray-900">
                    <p class="truncate text-xs font-medium text-gray-600 dark:text-gray-300">
                        @lang('admin::app.dashboard.index.over-all.total-organizations')
                    </p>

                    <div class="flex min-w-0 flex-wrap items-baseline justify-between gap-1.5">
                        <p class="min-w-0 text-xl font-bold dark:text-gray-300">
                            @{{ report.statistics.total_organizations.current }}
                        </p>

                        <div
                            class="inline-flex shrink-0 items-center gap-0.5 rounded-full px-1.5 py-0.5 text-xs font-semibold"
                            :class="[report.statistics.total_organizations.progress < 0 ? 'bg-red-50 text-red-600 dark:bg-red-950/40 dark:text-red-400' : 'bg-green-50 text-green-600 dark:bg-green-950/40 dark:text-green-400']"
                        >
                            <span
                                class="text-xs font-bold"
                                :class="[report.statistics.total_organizations.progress < 0 ? 'icon-stats-down' : 'icon-stats-up']"
                            ></span>

                            <p class="text-xs font-semibold">
                                @{{ Math.abs(report.statistics.total_organizations.progress.toFixed(2)) }}%
                            </p>
                        </div>
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
                    report: [],

                    isLoading: true,
                    
                    chart: undefined,
                }
            },

            mounted() {
                this.getStats({});

                this.$emitter.on('reporting-filter-updated', this.getStats);
            },

            methods: {
                getStats(filters) {
                    this.isLoading = true;

                    var filters = Object.assign({}, filters);

                    filters.type = 'over-all';

                    this.$axios.get("{{ route('admin.dashboard.stats') }}", {
                            params: filters
                        })
                        .then(response => {
                            this.report = response.data;

                            this.isLoading = false;
                        })
                        .catch(error => {});
                },
            }
        });
    </script>
@endPushOnce