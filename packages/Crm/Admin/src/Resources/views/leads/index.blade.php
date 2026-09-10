<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.leads.index.title')
    </x-slot>

    <!-- Header -->
    {!! view_render_event('admin.leads.index.header.before') !!}

    <div class="flex items-center justify-between rounded-2xl border border-slate-200/90 bg-white px-5 py-4 text-sm shadow-xs dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
        {!! view_render_event('admin.leads.index.header.left.before') !!}

        <div class="flex items-center gap-3.5">
            <!-- Blue Squircle Icon -->
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-blue-100 text-blue-600 dark:bg-blue-950/70 dark:text-blue-400">
                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
                </svg>
            </div>

            <div class="flex flex-col">
                <div class="flex items-center gap-2">
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                        @lang('admin::app.leads.index.title')
                    </h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-600 border border-emerald-200/60 dark:bg-emerald-950/60 dark:text-emerald-300 dark:border-emerald-800/60">
                        Live Deals
                    </span>
                </div>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-normal mt-0.5">
                    Track and manage your potential customers.
                </p>
            </div>
        </div>

        {!! view_render_event('admin.leads.index.header.left.after') !!}

        {!! view_render_event('admin.leads.index.header.right.before') !!}

        <div class="flex items-center gap-x-3">
            <!-- Upload File for Lead Creation -->
            @if(core()->getConfigData('general.magic_ai.doc_generation.enabled'))
                @include('admin::leads.index.upload')
            @endif

            @if ((request()->view_type ?? "kanban") == "table")
                <!-- Export Modal -->
                <x-admin::datagrid.export :src="route('admin.leads.index')" />
            @endif

            <!-- Create button for Leads -->
            <div class="flex items-center gap-x-2.5">
                @if (bouncer()->hasPermission('leads.create'))
                    <a
                        href="{{ route('admin.leads.create', request()->query()) }}"
                        style="background-color: #6366f1 !important; color: #ffffff !important;"
                        class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold rounded-xl shadow-sm transition hover:opacity-95 active:scale-95"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #ffffff;">
                            <path d="M12 4v16m8-8H4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"></path>
                        </svg>
                        <span style="color: #ffffff !important;">@lang('admin::app.leads.index.create-btn')</span>
                    </a>
                @endif
            </div>
        </div>

        {!! view_render_event('admin.leads.index.header.right.after') !!}
    </div>

    {!! view_render_event('admin.leads.index.header.after') !!}

    {!! view_render_event('admin.leads.index.content.before') !!}

    <!-- Content -->
    <div class="[&>*>*>*.toolbarRight]:max-lg:w-full [&>*>*>*.toolbarRight]:max-lg:justify-between [&>*>*>*.toolbarRight]:max-md:gap-y-2 [&>*>*>*.toolbarRight]:max-md:flex-wrap mt-3.5 [&>*>*:nth-child(1)]:max-lg:!flex-wrap">
        @if ((request()->view_type ?? "kanban") == "table")
            @include('admin::leads.index.table')
        @else
            @include('admin::leads.index.kanban')
        @endif
    </div>

    {!! view_render_event('admin.leads.index.content.after') !!}
</x-admin::layouts>
