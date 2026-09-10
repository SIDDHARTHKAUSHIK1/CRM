{!! view_render_event('admin.leads.view.attributes.before', ['lead' => $lead]) !!}

<div class="flex w-full flex-col gap-3 rounded-2xl border border-slate-200/90 bg-white p-4.5 shadow-xs dark:border-gray-800 dark:bg-gray-900">
    <x-admin::accordion class="select-none !border-none">
        <x-slot:header class="!p-0">
            <div class="flex w-full items-center justify-between gap-4 font-bold text-gray-600dark:text-white">
                <div class="flex items-center gap-2">
                    <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-purple-100 text-purple-600 text-xs dark:bg-purple-950/60 dark:text-purple-400">
                        📄
                    </span>
                    <h4 class="text-sm font-bold">@lang('admin::app.leads.view.attributes.title')</h4>
                </div>
                
                @if (bouncer()->hasPermission('leads.edit'))
                    <a
                        href="{{ route('admin.leads.edit', $lead->id) }}"
                        class="icon-edit rounded-lg p-1 text-xl text-slate-400 hover:text-purple-600 hover:bg-purple-50 transition-all dark:hover:bg-gray-800 dark:hover:text-purple-400"
                        title="Edit Deal Attributes"
                        target="_blank"
                    ></a>
                @endif
            </div>
        </x-slot>

        <x-slot:content class="mt-3 !px-0 !pb-0">
            {!! view_render_event('admin.leads.view.attributes.form_controls.before', ['lead' => $lead]) !!}

            <x-admin::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
                ref="modalForm"
            >
                <form @submit="handleSubmit($event, () => {})">
                    {!! view_render_event('admin.leads.view.attributes.form_controls.attributes.view.before', ['lead' => $lead]) !!}
        
                    <x-admin::attributes.view
                        :custom-attributes="app('Crm\Attribute\Repositories\AttributeRepository')->findWhere([
                            'entity_type' => 'leads',
                            ['code', 'NOTIN', ['title', 'description', 'lead_pipeline_id', 'lead_pipeline_stage_id']]
                        ])"
                        :entity="$lead"
                        :url="route('admin.leads.attributes.update', $lead->id)"
                        :allow-edit="true"
                    />
        
                    {!! view_render_event('admin.leads.view.attributes.form_controls.attributes.view.after', ['lead' => $lead]) !!}
                </form>
            </x-admin::form>
        
            {!! view_render_event('admin.leads.view.attributes.form_controls.after', ['lead' => $lead]) !!}
        </x-slot>
    </x-admin::accordion>
</div>

{!! view_render_event('admin.leads.view.attributes.before', ['lead' => $lead]) !!}
