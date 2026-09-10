<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.leads.view.title', ['title' => strip_tags($lead->title)])
    </x-slot>

    <!-- Content -->
    <div class="relative flex gap-5 max-lg:flex-wrap items-start">

        <!-- Left Panel -->
        {!! view_render_event('admin.leads.view.left.before', ['lead' => $lead]) !!}

        <div class="max-lg:min-w-full max-lg:max-w-full lg:sticky lg:top-[73px] flex min-w-[394px] max-w-[394px] flex-col gap-4 self-start">
            <!-- Lead Information & Deal Value Card -->
            <div class="flex w-full flex-col gap-3.5 rounded-2xl border border-slate-200/90 bg-white p-4.5 shadow-xs dark:border-gray-800 dark:bg-gray-900">
                <!-- Breadcrumbs & Rotten Alert -->
                <div class="flex items-center justify-between">
                    <x-admin::breadcrumbs
                        name="leads.view"
                        :entity="$lead"
                    />
                </div>

                <div class="flex flex-wrap items-center gap-1.5">
                    @if (($days = $lead->rotten_days) > 0)
                        @php
                            $lead->tags->prepend([
                                'name' => '<span class="icon-rotten text-base mr-1"></span>' . trans('admin::app.leads.view.rotten-days', ['days' => $days]),
                                'color' => '#FEE2E2'
                            ]);
                        @endphp
                    @endif

                    {!! view_render_event('admin.leads.view.tags.before', ['lead' => $lead]) !!}

                    <!-- Tags -->
                    <x-admin::tags
                        :attach-endpoint="route('admin.leads.tags.attach', $lead->id)"
                        :detach-endpoint="route('admin.leads.tags.detach', $lead->id)"
                        :added-tags="$lead->tags"
                    />

                    {!! view_render_event('admin.leads.view.tags.after', ['lead' => $lead]) !!}
                </div>

                {!! view_render_event('admin.leads.view.title.before', ['lead' => $lead]) !!}

                <!-- Lead Title with ID Badge -->
                <div class="flex items-start gap-2 pt-0.5">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-lg bg-purple-100 text-purple-800 dark:bg-purple-950/60 dark:text-purple-300 font-bold text-xs shrink-0 mt-0.5">
                        #{{ $lead->id }}
                    </span>
                    <h1 class="text-lg font-bold text-gray-600dark:text-white leading-snug break-words">
                        {{ $lead->title }}
                    </h1>
                </div>

                {!! view_render_event('admin.leads.view.title.after', ['lead' => $lead]) !!}

                <!-- Estimated Deal Value Box -->
                <div class="rounded-xl border border-slate-100 bg-slate-50/80 p-3.5 dark:border-gray-800/80 dark:bg-gray-800/40">
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider dark:text-slate-400">
                            Estimated Deal Value
                        </span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold {{ $lead->stage->code == 'won' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/70 dark:text-emerald-300' : ($lead->stage->code == 'lost' ? 'bg-rose-100 text-rose-800 dark:bg-rose-950/70 dark:text-rose-300' : 'bg-purple-100 text-purple-800 dark:bg-purple-950/70 dark:text-purple-300') }}">
                            {{ $lead->stage->name }}
                        </span>
                    </div>

                    <div class="mt-1.5 flex items-baseline gap-2">
                        <span class="text-2xl font-bold text-gray-800 dark:text-white tracking-tight">
                            {{ core()->formatBasePrice($lead->lead_value) }}
                        </span>
                        <span class="text-xs text-slate-500 dark:text-slate-400 font-medium">Total Value</span>
                    </div>

                    @php
                        $primaryPhone = collect($lead->person?->contact_numbers ?? [])->pluck('value')->first();
                        $cleanPhone = $primaryPhone ? preg_replace('/[^0-9]/', '', $primaryPhone) : null;
                    @endphp

                    @if ($primaryPhone)
                        <!-- Quick Contact Bar (WhatsApp & Call) -->
                        <div class="mt-3 grid grid-cols-2 gap-2 pt-2.5 border-t border-slate-200/70 dark:border-gray-700/60">
                            <a
                                href="https://wa.me/{{ $cleanPhone }}"
                                target="_blank"
                                class="flex items-center justify-center gap-1.5 py-1.5 px-2.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 dark:bg-emerald-950/50 dark:hover:bg-emerald-900/60 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/60 rounded-xl text-xs font-semibold transition active:scale-95 shadow-2xs"
                            >
                                <svg class="w-3.5 h-3.5 text-emerald-600 dark:text-emerald-400 shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                                </svg>
                                <span>WhatsApp</span>
                            </a>

                            <a
                                href="tel:{{ $primaryPhone }}"
                                class="flex items-center justify-center gap-1.5 py-1.5 px-2.5 bg-sky-50 hover:bg-sky-100 text-sky-700 dark:bg-sky-950/50 dark:hover:bg-sky-900/60 dark:text-sky-300 border border-sky-200 dark:border-sky-800/60 rounded-xl text-xs font-semibold transition active:scale-95 shadow-2xs"
                            >
                                <svg class="w-3.5 h-3.5 text-sky-600 dark:text-sky-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                <span>Call Client</span>
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Activity Action Buttons Grid -->
                <div class="grid grid-cols-4 gap-2 pt-0.5">
                    {!! view_render_event('admin.leads.view.actions.before', ['lead' => $lead]) !!}

                    @if (bouncer()->hasPermission('mail.compose'))
                        <!-- Mail Activity Action -->
                        <x-admin::activities.actions.mail
                            :entity="$lead"
                            :emails="collect($lead->person?->emails ?? [])->pluck('value')->filter()->values()->toArray()"
                            entity-control-name="lead_id"
                        />
                    @endif

                    @if (bouncer()->hasPermission('activities.create'))
                        <!-- File Activity Action -->
                        <x-admin::activities.actions.file
                            :entity="$lead"
                            entity-control-name="lead_id"
                        />

                        <!-- Note Activity Action -->
                        <x-admin::activities.actions.note
                            :entity="$lead"
                            entity-control-name="lead_id"
                        />

                        <!-- Activity Action -->
                        <x-admin::activities.actions.activity
                            :entity="$lead"
                            entity-control-name="lead_id"
                        />
                    @endif

                    {!! view_render_event('admin.leads.view.actions.after', ['lead' => $lead]) !!}
                </div>
            </div>

            <!-- Lead Attributes (Deal Specifics) -->
            @include ('admin::leads.view.attributes')

            <!-- Contact Person -->
            @include ('admin::leads.view.person')
        </div>

        {!! view_render_event('admin.leads.view.left.after', ['lead' => $lead]) !!}

        {!! view_render_event('admin.leads.view.right.before', ['lead' => $lead]) !!}

        <!-- Right Panel -->
        <div class="flex flex-1 flex-col gap-4 min-w-0">
            <!-- Stages Navigation -->
            @include ('admin::leads.view.stages')

            <!-- Activities -->
            {!! view_render_event('admin.leads.view.activities.before', ['lead' => $lead]) !!}

            <x-admin::activities
                :endpoint="route('admin.leads.activities.index', $lead->id)"
                :email-detach-endpoint="route('admin.leads.emails.detach', $lead->id)"
                :activeType="request()->query('tab') ?? (request()->query('from') === 'quotes' ? 'quotes' : 'all')"
                :extra-types="[
                    ['name' => 'description', 'label' => trans('admin::app.leads.view.tabs.description')],
                    ['name' => 'products', 'label' => trans('admin::app.leads.view.tabs.products')],
                    ['name' => 'quotes', 'label' => trans('admin::app.leads.view.tabs.quotes')],
                ]"
            >
                <!-- Products -->
                <x-slot:products>
                    @include ('admin::leads.view.products')
                </x-slot>

                <!-- Quotes -->
                <x-slot:quotes>
                    @include ('admin::leads.view.quotes')
                </x-slot>

                <!-- Description -->
                <x-slot:description>
                    <div class="p-4.5 text-sm leading-relaxed text-slate-700 dark:text-slate-200">
                        {{ $lead->description ?: 'No additional description provided.' }}
                    </div>
                </x-slot>
            </x-admin::activities>

            {!! view_render_event('admin.leads.view.activities.after', ['lead' => $lead]) !!}
        </div>

        {!! view_render_event('admin.leads.view.right.after', ['lead' => $lead]) !!}
    </div>
</x-admin::layouts>
