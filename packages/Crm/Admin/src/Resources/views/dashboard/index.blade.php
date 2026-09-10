<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.dashboard.index.title')
    </x-slot>

    <!-- Head Details Section -->
    {!! view_render_event('admin.dashboard.index.header.before') !!}

    <div class="mb-5 flex items-center justify-between gap-4 max-sm:flex-wrap">
        {!! view_render_event('admin.dashboard.index.header.left.before') !!}

        <div class="grid gap-0.5">
            <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                @lang('admin::app.dashboard.index.title')
            </h1>
            <p class="text-xs font-medium text-slate-500 dark:text-slate-400">
                Here's what's happening in your business today.
            </p>
        </div>

        {!! view_render_event('admin.dashboard.index.header.left.after') !!}

        <!-- Actions / Filters -->
        {!! view_render_event('admin.dashboard.index.header.right.before') !!}

        <v-dashboard-filters>
            <!-- Shimmer -->
            <div class="flex items-center gap-2">
                @if ($pipelines->count() > 1)
                    <div class="light-shimmer-bg dark:shimmer h-[38px] w-[140px] rounded-xl"></div>
                @endif

                <div class="light-shimmer-bg dark:shimmer h-[38px] w-[130px] rounded-xl"></div>
                <span class="text-xs text-slate-400">to</span>
                <div class="light-shimmer-bg dark:shimmer h-[38px] w-[130px] rounded-xl"></div>
            </div>
        </v-dashboard-filters>

        {!! view_render_event('admin.dashboard.index.header.right.after') !!}
    </div>

    {!! view_render_event('admin.dashboard.index.header.after') !!}

    <!-- Body Component: 5-Tier Balanced Grid -->
    {!! view_render_event('admin.dashboard.index.content.before') !!}

    <div class="mt-4 flex flex-col gap-5">
        <!-- Tier 1: 4 Primary Stat Cards (Won, Lost, Leads, Avg Value) -->
        @include('admin::dashboard.index.revenue')

        <!-- Tier 2: 6 Mini Metric Cards (New Leads, Negotiation, Prospect, Quotes, Persons, Orgs) -->
        @include('admin::dashboard.index.over-all')

        <!-- Tier 3: 3 Breakdown Cards (Revenue by Source, Revenue by Type, Leads Summary) -->
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
            @include('admin::dashboard.index.revenue-by-sources')
            @include('admin::dashboard.index.revenue-by-types')
            @include('admin::dashboard.index.open-leads-by-states')
        </div>

        <!-- Tier 4: 2 Performance Cards (Top Products, Top Persons) -->
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
            @include('admin::dashboard.index.top-selling-products')
            @include('admin::dashboard.index.top-persons')
        </div>

        <!-- Tier 5: Quick Tip Banner -->
        <div class="flex items-center gap-2.5 rounded-2xl border border-blue-100 bg-[#edf5ff] px-4.5 py-3 text-xs text-slate-700 shadow-2xs dark:border-blue-900/40 dark:bg-blue-950/30 dark:text-slate-300">
            <span class="flex h-5 w-5 shrink-0 items-center justify-center text-blue-600 dark:text-blue-400">
                <svg class="h-4.5 w-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M15 14c.2-1 .7-1.7 1.5-2.5 1-.9 1.5-2.2 1.5-3.5A6 6 0 0 0 6 8c0 1 .2 2.2 1.5 3.5.7.7 1.3 1.5 1.5 2.5"/>
                    <path d="M9 18h6"/>
                    <path d="M10 22h4"/>
                </svg>
            </span>
            <p>
                <strong class="font-bold text-slate-800 dark:text-white">Tip:</strong> Use the <span class="font-bold text-blue-600 dark:text-blue-400">+ Add</span> button to quickly add Leads, Quotes, or Contacts.
            </p>
        </div>
    </div>

    {!! view_render_event('admin.dashboard.index.content.after') !!}

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-dashboard-filters-template"
        >
            {!! view_render_event('admin.dashboard.index.date_filters.before') !!}

            <div class="flex items-center gap-2">
                @if ($pipelines->count() > 1)
                    <!-- Pipeline Selector -->
                    <select
                        class="custom-select flex min-h-[38px] w-[140px] rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-2xs hover:border-slate-300 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300"
                        v-model="filters.pipeline_id"
                    >
                        @foreach ($pipelines as $pipeline)
                            <option value="{{ $pipeline->id }}">{{ $pipeline->name }}</option>
                        @endforeach
                    </select>
                @endif

                <x-admin::flat-picker.date
                    class="!w-[130px]"
                    ::allow-input="false"
                    ::max-date="filters.end"
                >
                    <input
                        class="flex min-h-[38px] w-full rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-2xs transition-all hover:border-slate-300 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300"
                        v-model="filters.start"
                        placeholder="@lang('admin::app.dashboard.index.start-date')"
                    />
                </x-admin::flat-picker.date>

                <span class="text-xs font-semibold text-slate-400">to</span>

                <x-admin::flat-picker.date
                    class="!w-[130px]"
                    ::allow-input="false"
                    ::max-date="filters.end"
                >
                    <input
                        class="flex min-h-[38px] w-full rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-2xs transition-all hover:border-slate-300 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300"
                        v-model="filters.end"
                        placeholder="@lang('admin::app.dashboard.index.end-date')"
                    />
                </x-admin::flat-picker.date>
            </div>

            {!! view_render_event('admin.dashboard.index.date_filters.after') !!}
        </script>

        <script type="module">
            app.component('v-dashboard-filters', {
                template: '#v-dashboard-filters-template',

                data() {
                    return {
                        filters: {
                            channel: '',

                            pipeline_id: "{{ $defaultPipeline?->id }}",

                            start: "{{ $startDate->format('Y-m-d') }}",

                            end: "{{ $endDate->format('Y-m-d') }}",
                        }
                    }
                },

                watch: {
                    filters: {
                        handler() {
                            this.$emitter.emit('reporting-filter-updated', this.filters);
                        },

                        deep: true
                    }
                },
            });
        </script>
    @endPushOnce
</x-admin::layouts>
