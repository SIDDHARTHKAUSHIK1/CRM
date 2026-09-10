{!! view_render_event('admin.dashboard.index.top_persons.before') !!}

<!-- Top Persons Vue Component -->
<v-dashboard-top-persons>
    <!-- Shimmer -->
    <x-admin::shimmer.dashboard.index.top-persons />
</v-dashboard-top-persons>

{!! view_render_event('admin.dashboard.index.top_persons.after') !!}

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-dashboard-top-persons-template"
    >
        <!-- Shimmer -->
        <template v-if="isLoading">
            <x-admin::shimmer.dashboard.index.top-persons />
        </template>

        <!-- Top Persons Card -->
        <template v-else>
            <div class="flex h-full flex-col justify-between rounded-2xl border border-slate-200/80 bg-white p-5 shadow-2xs transition-all hover:shadow-xs dark:border-gray-800 dark:bg-gray-900">
                <!-- Header -->
                <div class="flex items-start gap-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600 border border-blue-100 dark:border-blue-900/50 dark:bg-blue-950/40">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-gray-900 dark:text-white">
                            Top Persons
                        </h4>
                        <p class="text-xs text-slate-400 font-medium">
                            People you interacted with most
                        </p>
                    </div>
                </div>

                <!-- Person Rows -->
                <div class="mt-5 space-y-3.5 flex-1">
                    <template v-if="report.statistics && report.statistics.length">
                        <a
                            :href="'{{ route('admin.contacts.persons.view', ':id') }}'.replace(':id', item.id)"
                            class="flex items-center gap-3 border-b border-slate-100 pb-3 last:border-b-0 hover:opacity-85 transition-opacity dark:border-gray-800"
                            v-for="(item, idx) in report.statistics.slice(0, 3)"
                            target="_blank"
                        >
                            <!-- Colored Initials Avatar -->
                            <div
                                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full font-bold text-xs ring-2 ring-white shadow-2xs"
                                :class="getAvatarBg(idx)"
                            >
                                @{{ getInitials(item.name) }}
                            </div>

                            <div class="flex flex-col overflow-hidden">
                                <span class="text-xs font-bold text-gray-900 dark:text-white truncate">
                                    @{{ item.name }}
                                </span>
                                <span class="text-[11px] text-slate-400 truncate">
                                    @{{ item.emails && item.emails.length ? item.emails[0].value : 'No email' }}
                                </span>
                            </div>
                        </a>
                    </template>
                    <template v-else>
                        <div class="py-6 text-center text-xs text-slate-400 font-medium">
                            No top contacts recorded.
                        </div>
                    </template>
                </div>

                <!-- Footer Link -->
                <div class="mt-4 pt-3 border-t border-slate-100 text-center dark:border-gray-800">
                    <a
                        href="{{ route('admin.contacts.persons.index') }}"
                        class="text-xs font-bold text-blue-600 hover:text-blue-700 hover:underline transition-all"
                    >
                        View all persons
                    </a>
                </div>
            </div>
        </template>
    </script>

    <script type="module">
        app.component('v-dashboard-top-persons', {
            template: '#v-dashboard-top-persons-template',

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

                    var f = Object.assign({}, filters, { type: 'top-persons' });

                    this.$axios.get("{{ route('admin.dashboard.stats') }}", { params: f })
                        .then(response => {
                            this.report = response.data;
                            this.isLoading = false;
                        })
                        .catch(error => {
                            this.isLoading = false;
                        });
                },

                getInitials(name) {
                    if (!name) return 'U';
                    const parts = name.trim().split(' ');
                    if (parts.length >= 2) {
                        return (parts[0][0] + parts[1][0]).toUpperCase();
                    }
                    return name.substring(0, 2).toUpperCase();
                },

                getAvatarBg(index) {
                    const palettes = [
                        'bg-emerald-100 text-emerald-800 border border-emerald-200',
                        'bg-amber-100 text-amber-800 border border-amber-200',
                        'bg-rose-100 text-rose-800 border border-rose-200',
                        'bg-indigo-100 text-indigo-800 border border-indigo-200',
                    ];
                    return palettes[index % palettes.length];
                }
            }
        });
    </script>
@endPushOnce
