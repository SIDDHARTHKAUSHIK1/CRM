{!! view_render_event('admin.leads.index.view_switcher.before') !!}

<div class="flex items-center gap-4 max-md:w-full max-md:!justify-between">
    <x-admin::dropdown>
        <x-slot:toggle>
            {!! view_render_event('admin.leads.index.view_switcher.pipeline.button.before') !!}

            <button
                type="button"
                class="flex cursor-pointer appearance-none items-center justify-between gap-x-2.5 rounded-xl border border-slate-200/90 bg-white px-3.5 py-2 text-sm font-semibold text-slate-700 shadow-2xs transition hover:border-slate-300 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300"
            >
                <span class="whitespace-nowrap">
                    {{ $pipeline->name }}
                </span>
                
                <span class="icon-down-arrow text-xl text-slate-400"></span>
            </button>

            {!! view_render_event('admin.leads.index.view_switcher.pipeline.button.after') !!}
        </x-slot>

        <x-slot:content class="!p-0">
            {!! view_render_event('admin.leads.index.view_switcher.pipeline.content.header.before') !!}

            <!-- Header -->
            <div class="flex items-center justify-between px-3 py-2.5">
                <span class="text-xs font-medium text-gray-500 dark:text-gray-300">
                    @lang('admin::app.leads.index.view-switcher.all-pipelines')
                </span>
            </div>

            {!! view_render_event('admin.leads.index.view_switcher.pipeline.content.header.after') !!}
            
            <!-- Pipeline Links -->
            @foreach (app('Crm\Lead\Repositories\PipelineRepository')->all() as $tempPipeline)
                {!! view_render_event('admin.leads.index.view_switcher.pipeline.content.before', ['tempPipeline' => $tempPipeline]) !!}

                <a
                    href="{{ route('admin.leads.index', [
                        'pipeline_id' => $tempPipeline->id,
                        'view_type' => request('view_type')
                    ]) }}"
                    class="block px-3 py-2.5 pl-4 text-gray-600 transition-all hover:bg-gray-100 dark:hover:bg-gray-950 dark:text-gray-300 {{ $pipeline->id == $tempPipeline->id ? 'bg-gray-100 dark:bg-gray-950 font-semibold text-blue-600' : '' }}"
                >
                    {{ $tempPipeline->name }}
                </a>

                {!! view_render_event('admin.leads.index.view_switcher.pipeline.content.after', ['tempPipeline' => $tempPipeline]) !!}
            @endforeach

            {!! view_render_event('admin.leads.index.view_switcher.pipeline.content.footer.before') !!}

            @if (bouncer()->hasPermission('settings.lead.pipelines.create'))
            <!-- Footer -->
            <a
                href="{{ route('admin.settings.pipelines.create') }}"
                target="_blank"
                class="flex items-center justify-between border-t border-gray-200 px-3 py-2.5 text-xs font-semibold text-blue-600 dark:border-gray-800"
            >
                <span>                    
                    @lang('admin::app.leads.index.view-switcher.create-new-pipeline')
                </span>
            </a>
            @endif

            {!! view_render_event('admin.leads.index.view_switcher.pipeline.content.footer.after') !!}
        </x-slot>
    </x-admin::dropdown>

    <div class="flex items-center gap-1">
        {!! view_render_event('admin.leads.index.view_switcher.pipeline.view_type.before') !!}

        @if (request('view_type'))
            <a
                class="flex h-9 w-9 items-center justify-center rounded-xl text-slate-400 transition hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-gray-800"
                href="{{ route('admin.leads.index') }}"
                title="Kanban View"
            >
                <span class="icon-kanban text-xl"></span>
            </a>

            <span class="flex h-9 w-9 items-center justify-center rounded-xl border border-blue-200/80 bg-blue-50/80 text-blue-600 shadow-2xs dark:border-blue-900/60 dark:bg-blue-950/50 dark:text-blue-300" title="Table View">
                <span class="icon-list text-xl"></span>
            </span>
        @else
            <span class="flex h-9 w-9 items-center justify-center rounded-xl border border-blue-200/80 bg-blue-50/80 text-blue-600 shadow-2xs dark:border-blue-900/60 dark:bg-blue-950/50 dark:text-blue-300" title="Kanban View">
                <span class="icon-kanban text-xl"></span>
            </span>

            <a
                href="{{ route('admin.leads.index', ['view_type' => 'table']) }}"
                class="flex h-9 w-9 items-center justify-center rounded-xl text-slate-400 transition hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-gray-800"
                title="Table View"
            >
                <span class="icon-list text-xl"></span>
            </a>
        @endif

        {!! view_render_event('admin.leads.index.view_switcher.pipeline.view_type.after') !!}
    </div>
</div>

{!! view_render_event('admin.leads.index.view_switcher.after') !!}