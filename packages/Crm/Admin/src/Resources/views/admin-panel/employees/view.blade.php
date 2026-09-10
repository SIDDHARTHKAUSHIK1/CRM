<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.admin-panel.employees.view.title', ['name' => $employee->name])
    </x-slot>

    @php
        $isAdmin = $employee->role && $employee->role->permission_type === 'all';
    @endphp

    <div class="flex flex-col gap-4" id="employee-detail-app">
        <!-- Sticky Action Header -->
        <div class="scroll-reactive-sticky sticky top-[60px] z-[1000] flex flex-wrap items-center justify-between gap-3 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm shadow-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
            <div class="flex flex-col gap-1">
                <x-admin::breadcrumbs name="admin_panel.employees.view" :entity="$employee" />

                <div class="flex items-center gap-3">
                    <h1 class="text-xl font-extrabold text-gray-900 dark:text-white">
                        {{ $employee->name }}
                    </h1>

                    @if ($isAdmin)
                        <span class="inline-flex items-center gap-1 rounded-full bg-red-600 px-2.5 py-0.5 text-xs font-black uppercase tracking-wider text-white dark:bg-red-500">
                            <svg class="w-3 h-3 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                            <span>@lang('admin::app.admin-panel.roles.admin')</span>
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 rounded-full bg-blue-600 px-2.5 py-0.5 text-xs font-black uppercase tracking-wider text-white dark:bg-blue-500">
                            <svg class="w-3 h-3 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                            <span>@lang('admin::app.admin-panel.roles.employee')</span>
                        </span>
                    @endif

                    <span class="{{ $employee->status ? 'label-active' : 'label-inactive' }} text-xs">
                        {{ $employee->status ? trans('admin::app.admin-panel.employees.datagrid.active') : trans('admin::app.admin-panel.employees.datagrid.inactive') }}
                    </span>
                </div>
            </div>

            <!-- Header Action Buttons -->
            <div class="flex items-center gap-2">
                <a
                    href="{{ route('admin.panel.employees.index') }}"
                    class="secondary-button !px-3 !py-1.5 text-xs"
                >
                    &larr; @lang('admin::app.admin-panel.employees.view.back-btn')
                </a>

                <!-- Switch Account (If not admin) -->
                @if (! $isAdmin)
                    <form method="POST" action="{{ route('admin.panel.employees.impersonate', $employee->id) }}" class="m-0" onsubmit="return confirm('@lang('admin::app.admin-panel.impersonate.modal_message', ['name' => $employee->name])');">
                        @csrf
                        <button
                            type="submit"
                            class="secondary-button !px-3 !py-1.5 text-xs !text-emerald-600 !border-emerald-300 hover:!bg-emerald-50 dark:!border-emerald-800 dark:hover:!bg-emerald-950/40"
                        >
                            <svg class="w-3.5 h-3.5 inline-block mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path><polyline points="10 17 15 12 10 7"></polyline><line x1="15" y1="12" x2="3" y2="12"></line></svg>
                            @lang('admin::app.admin-panel.impersonate.btn')
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Top Profile & Stats Grid -->
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-4">
            <!-- Profile Info Card -->
            <div class="rounded-xl border border-gray-300 bg-white p-5 shadow-xs dark:border-gray-800 dark:bg-gray-900 lg:col-span-1 flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-3.5 pb-4 border-b border-gray-100 dark:border-gray-800">
                        @if ($employee->image)
                            <img
                                class="h-14 w-14 shrink-0 rounded-full object-cover shadow-sm"
                                src="{{ Storage::url($employee->image) }}"
                                alt="{{ $employee->name }}"
                            />
                        @else
                            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-indigo-600 font-extrabold text-lg text-white shadow-sm">
                                {{ strtoupper(substr($employee->name, 0, 2)) }}
                            </div>
                        @endif

                        <div class="flex flex-col min-w-0">
                            <span class="text-base font-extrabold text-gray-900 dark:text-white truncate">
                                {{ $employee->name }}
                            </span>
                            <span class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                {{ $employee->email }}
                            </span>
                        </div>
                    </div>

                    <div class="flex flex-col gap-2.5 pt-4 text-xs">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-500 dark:text-gray-400">@lang('admin::app.admin-panel.employees.view.role'):</span>
                            <span class="font-bold text-gray-900 dark:text-white">{{ $employee->role?->name ?? '—' }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-500 dark:text-gray-400">@lang('admin::app.admin-panel.employees.edit.view-permission'):</span>
                            <span class="font-semibold text-gray-800 dark:text-gray-200 capitalize">{{ $employee->view_permission }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-500 dark:text-gray-400">@lang('admin::app.admin-panel.employees.view.created-at'):</span>
                            <span class="font-medium text-gray-800 dark:text-gray-200">{{ core()->formatDate($employee->created_at, 'd M Y') }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-500 dark:text-gray-400">@lang('admin::app.admin-panel.employees.view.last-login'):</span>
                            <span class="font-semibold text-gray-900 dark:text-white">
                                {{ $employee->last_login_at ? core()->formatDate($employee->last_login_at, 'd M Y, h:i A') : trans('admin::app.admin-panel.employees.view.never-logged-in') }}
                            </span>
                        </div>
                        @if ($employee->groups->isNotEmpty())
                            <div class="flex flex-col gap-1 pt-1">
                                <span class="text-gray-500 dark:text-gray-400">@lang('admin::app.admin-panel.employees.edit.groups'):</span>
                                <div class="flex flex-wrap gap-1">
                                    @foreach ($employee->groups as $group)
                                        <span class="rounded bg-slate-100 px-1.5 py-0.5 text-[11px] font-semibold text-slate-700 dark:bg-gray-800 dark:text-gray-300">
                                            {{ $group->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- KPI Work Summary Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 lg:col-span-3">
                <!-- Leads KPI -->
                <div class="rounded-xl border border-emerald-200 bg-emerald-50/50 p-5 shadow-xs dark:border-emerald-950/60 dark:bg-emerald-950/20 flex flex-col justify-between">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-emerald-700 dark:text-emerald-400">
                            @lang('admin::app.admin-panel.employees.view.leads')
                        </span>
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600 dark:bg-emerald-900/60 dark:text-emerald-300">
                            <span class="icon-leads text-xl"></span>
                        </div>
                    </div>
                    <div class="mt-4">
                        <span class="text-3xl font-extrabold text-emerald-950 dark:text-emerald-100">
                            {{ $leadsCount }}
                        </span>
                    </div>
                </div>

                <!-- Quotes KPI -->
                <div class="rounded-xl border border-purple-200 bg-purple-50/50 p-5 shadow-xs dark:border-purple-950/60 dark:bg-purple-950/20 flex flex-col justify-between">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-purple-700 dark:text-purple-400">
                            @lang('admin::app.admin-panel.employees.view.quotes')
                        </span>
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-purple-100 text-purple-600 dark:bg-purple-900/60 dark:text-purple-300">
                            <span class="icon-quote text-xl"></span>
                        </div>
                    </div>
                    <div class="mt-4">
                        <span class="text-3xl font-extrabold text-purple-950 dark:text-purple-100">
                            {{ $quotesCount }}
                        </span>
                    </div>
                </div>

                <!-- Activities KPI -->
                <div class="rounded-xl border border-sky-200 bg-sky-50/50 p-5 shadow-xs dark:border-sky-950/60 dark:bg-sky-950/20 flex flex-col justify-between">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-sky-700 dark:text-sky-400">
                            @lang('admin::app.admin-panel.employees.view.activities')
                        </span>
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-sky-100 text-sky-600 dark:bg-sky-900/60 dark:text-sky-300">
                            <span class="icon-activity text-xl"></span>
                        </div>
                    </div>
                    <div class="mt-4">
                        <span class="text-3xl font-extrabold text-sky-950 dark:text-sky-100">
                            {{ $activitiesCount }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Work Detail Tables -->
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
            <!-- Recent Leads -->
            <div class="rounded-xl border border-gray-300 bg-white shadow-xs dark:border-gray-800 dark:bg-gray-900 flex flex-col">
                <div class="border-b border-gray-200 px-4 py-3 dark:border-gray-800 flex items-center justify-between">
                    <h3 class="text-sm font-extrabold text-gray-900 dark:text-white">
                        @lang('admin::app.admin-panel.employees.view.recent-leads') ({{ $recentLeads->count() }})
                    </h3>
                </div>
                <div class="p-3 overflow-x-auto flex-1">
                    @if ($recentLeads->isEmpty())
                        <p class="py-6 text-center text-xs text-gray-400">@lang('admin::app.admin-panel.employees.view.no-leads')</p>
                    @else
                        <div class="flex flex-col gap-2">
                            @foreach ($recentLeads as $lead)
                                <div class="flex items-center justify-between rounded-lg border border-slate-100 p-2.5 dark:border-gray-800/80 hover:bg-slate-50 dark:hover:bg-gray-800/40 transition">
                                    <div class="flex flex-col min-w-0">
                                        <a href="{{ route('admin.leads.view', $lead->id) }}" class="font-bold text-xs text-gray-900 dark:text-white truncate hover:text-brandColor">
                                            {{ $lead->title }}
                                        </a>
                                        <span class="text-[11px] text-gray-400">
                                            {{ $lead->person?->name ?? '—' }} &bull; {{ core()->formatBasePrice($lead->lead_value) }}
                                        </span>
                                    </div>
                                    <span class="text-[10px] font-semibold text-gray-400 shrink-0">
                                        {{ core()->formatDate($lead->created_at, 'd M') }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Quotes -->
            <div class="rounded-xl border border-gray-300 bg-white shadow-xs dark:border-gray-800 dark:bg-gray-900 flex flex-col">
                <div class="border-b border-gray-200 px-4 py-3 dark:border-gray-800 flex items-center justify-between">
                    <h3 class="text-sm font-extrabold text-gray-900 dark:text-white">
                        @lang('admin::app.admin-panel.employees.view.recent-quotes') ({{ $recentQuotes->count() }})
                    </h3>
                </div>
                <div class="p-3 overflow-x-auto flex-1">
                    @if ($recentQuotes->isEmpty())
                        <p class="py-6 text-center text-xs text-gray-400">@lang('admin::app.admin-panel.employees.view.no-quotes')</p>
                    @else
                        <div class="flex flex-col gap-2">
                            @foreach ($recentQuotes as $quote)
                                <div class="flex items-center justify-between rounded-lg border border-slate-100 p-2.5 dark:border-gray-800/80 hover:bg-slate-50 dark:hover:bg-gray-800/40 transition">
                                    <div class="flex flex-col min-w-0">
                                        <a href="{{ route('admin.quotes.edit', $quote->id) }}" class="font-bold text-xs text-gray-900 dark:text-white truncate hover:text-brandColor">
                                            {{ $quote->subject }}
                                        </a>
                                        <span class="text-[11px] text-gray-400">
                                            {{ $quote->person?->name ?? '—' }} &bull; {{ core()->formatBasePrice($quote->grand_total) }}
                                        </span>
                                    </div>
                                    <span class="text-[10px] font-semibold text-gray-400 shrink-0">
                                        {{ core()->formatDate($quote->created_at, 'd M') }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Activities -->
            <div class="rounded-xl border border-gray-300 bg-white shadow-xs dark:border-gray-800 dark:bg-gray-900 flex flex-col">
                <div class="border-b border-gray-200 px-4 py-3 dark:border-gray-800 flex items-center justify-between">
                    <h3 class="text-sm font-extrabold text-gray-900 dark:text-white">
                        @lang('admin::app.admin-panel.employees.view.recent-activities') ({{ $recentActivities->count() }})
                    </h3>
                </div>
                <div class="p-3 overflow-x-auto flex-1">
                    @if ($recentActivities->isEmpty())
                        <p class="py-6 text-center text-xs text-gray-400">@lang('admin::app.admin-panel.employees.view.no-activities')</p>
                    @else
                        <div class="flex flex-col gap-2">
                            @foreach ($recentActivities as $activity)
                                <div class="flex items-center justify-between rounded-lg border border-slate-100 p-2.5 dark:border-gray-800/80 hover:bg-slate-50 dark:hover:bg-gray-800/40 transition">
                                    <div class="flex items-center gap-2 min-w-0">
                                        <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-slate-100 text-[10px] font-bold text-slate-600 dark:bg-gray-800 dark:text-gray-300 uppercase">
                                            {{ substr($activity->type, 0, 1) }}
                                        </span>
                                        <div class="flex flex-col min-w-0">
                                            <span class="font-bold text-xs text-gray-900 dark:text-white truncate">
                                                {{ $activity->title ?: ucfirst($activity->type) }}
                                            </span>
                                            <span class="text-[10px] text-gray-400">
                                                {{ $activity->is_done ? 'Completed' : 'Pending' }}
                                            </span>
                                        </div>
                                    </div>
                                    <span class="text-[10px] font-semibold text-gray-400 shrink-0">
                                        {{ core()->formatDate($activity->created_at, 'd M') }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-admin::layouts>
