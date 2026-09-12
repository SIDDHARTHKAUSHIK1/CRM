<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.admin-panel.employees.title')
    </x-slot>

    <div class="flex flex-col gap-4">
        <!-- Sticky Header Bar -->
        <div class="scroll-reactive-sticky sticky top-[60px] z-[1000] flex flex-wrap items-center justify-between gap-3 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm shadow-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
            <div class="flex flex-col gap-1">
                <x-admin::breadcrumbs name="admin_panel.employees" />

                <div class="flex items-center gap-3">
                    <h1 class="text-xl font-extrabold text-gray-900 dark:text-white">
                        @lang('admin::app.admin-panel.employees.title')
                    </h1>
                    
                    <span class="inline-flex items-center gap-1 rounded-full bg-red-600 px-2.5 py-0.5 text-xs font-black uppercase tracking-wider text-white dark:bg-red-500">
                        <svg class="w-3 h-3 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                        <span>@lang('admin::app.admin-panel.roles.admin')</span>
                    </span>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    @lang('admin::app.admin-panel.employees.description')
                </p>
            </div>

            <div class="flex items-center gap-3">
                <!-- Summary KPI Badges -->
                <div class="flex items-center gap-2 max-sm:hidden">
                    <div class="flex items-center gap-1.5 rounded-lg border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs font-semibold text-slate-700 dark:border-gray-800 dark:bg-gray-800 dark:text-gray-200">
                        <span class="text-slate-400">@lang('admin::app.admin-panel.employees.total-employees'):</span>
                        <span class="font-extrabold text-slate-900 dark:text-white">{{ $totalEmployees }}</span>
                    </div>
                    <div class="flex items-center gap-1.5 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-800 dark:border-emerald-950/60 dark:bg-emerald-950/40 dark:text-emerald-300">
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                        <span>@lang('admin::app.admin-panel.employees.active-employees'):</span>
                        <span class="font-extrabold">{{ $activeEmployees }}</span>
                    </div>
                    <div class="flex items-center gap-1.5 rounded-lg border border-rose-200 bg-rose-50 px-3 py-1.5 text-xs font-semibold text-rose-800 dark:border-rose-950/60 dark:bg-rose-950/40 dark:text-rose-300">
                        <span class="h-2 w-2 rounded-full bg-rose-500"></span>
                        <span>@lang('admin::app.admin-panel.employees.inactive-employees'):</span>
                        <span class="font-extrabold">{{ $inactiveEmployees }}</span>
                    </div>
                </div>

                <!-- Add Employee Action (Admin Only) -->
                @if (auth()->guard('user')->user()?->role?->permission_type === 'all')
                    <button
                        type="button"
                        class="primary-button flex items-center gap-1.5 shadow-sm"
                        @click="$refs.employeesOversight.openCreateModal()"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                        <span>@lang('admin::app.admin-panel.employees.create-btn')</span>
                    </button>
                @endif
            </div>
        </div>

        <!-- Vue Employee Oversight Component -->
        <v-employees-oversight ref="employeesOversight">
            <x-admin::shimmer.datagrid />
        </v-employees-oversight>
    </div>

    @pushOnce('scripts')
        <script type="text/x-template" id="v-employees-oversight-template">
            <div>
                <!-- Datagrid -->
                <x-admin::datagrid
                    :src="route('admin.panel.employees.index')"
                    ref="datagrid"
                >
                    <template #body="{
                        isLoading,
                        available,
                        applied,
                        selectAll,
                        sort,
                        performAction
                    }">
                        <template v-if="isLoading">
                            <x-admin::shimmer.datagrid.table.body />
                        </template>

                        <template v-else>
                            <div
                                v-for="record in available.records"
                                :key="record.id"
                                class="row grid items-center gap-3 border-b px-4 py-3.5 text-sm text-gray-700 transition-all hover:bg-slate-50 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-gray-950 max-lg:hidden"
                                style="grid-template-columns: 60px 2.2fr 2fr 1.6fr 100px 1.4fr 1.6fr 1.8fr 180px;"
                            >
                                <!-- ID -->
                                <p class="font-mono text-xs font-bold text-gray-500">#@{{ record.id }}</p>

                                <!-- Employee Name & Avatar -->
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <template v-if="record.name.image">
                                        <img
                                            class="h-9 w-9 shrink-0 rounded-full object-cover shadow-2xs"
                                            :src="record.name.image"
                                            :alt="record.name.name"
                                        />
                                    </template>
                                    <template v-else>
                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-indigo-600 font-bold text-xs text-white shadow-2xs">
                                            @{{ (record.name.name || '').substring(0, 2).toUpperCase() }}
                                        </div>
                                    </template>

                                    <div class="flex flex-col min-w-0">
                                        <a
                                            :href="'{{ route('admin.panel.employees.view', ':id') }}'.replace(':id', record.id)"
                                            class="truncate font-bold text-gray-900 transition hover:text-brandColor dark:text-white"
                                            :title="record.name.name"
                                        >
                                            @{{ record.name.name }}
                                        </a>
                                    </div>
                                </div>

                                <!-- Email -->
                                <p class="truncate text-xs font-medium text-gray-600 dark:text-gray-300" :title="record.email">
                                    @{{ record.email }}
                                </p>

                                <!-- Role & Role Badge -->
                                <div class="flex flex-col gap-1 items-start">
                                    <span class="font-bold text-xs text-gray-800 dark:text-gray-200 truncate">
                                        @{{ record.role_name.role_name }}
                                    </span>
                                    <span
                                        v-if="record.role_name.role_permission_type === 'all'"
                                        class="inline-flex items-center gap-1 rounded-full bg-red-600 px-2 py-0.2 text-[10px] font-black uppercase text-white dark:bg-red-500"
                                    >
                                        @lang('admin::app.admin-panel.roles.admin')
                                    </span>
                                    <span
                                        v-else
                                        class="inline-flex items-center gap-1 rounded-full bg-blue-600 px-2 py-0.2 text-[10px] font-black uppercase text-white dark:bg-blue-500"
                                    >
                                        @lang('admin::app.admin-panel.roles.employee')
                                    </span>
                                </div>

                                <!-- Status -->
                                <div>
                                    <span
                                        :class="record.status == 1 ? 'label-active' : 'label-inactive'"
                                        class="text-xs"
                                    >
                                        @{{ record.status == 1 ? "@lang('admin::app.admin-panel.employees.datagrid.active')" : "@lang('admin::app.admin-panel.employees.datagrid.inactive')" }}
                                    </span>
                                </div>

                                <!-- Created At -->
                                <p class="text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                    @{{ record.created_at }}
                                </p>

                                <!-- Last Login -->
                                <div class="flex items-center gap-1.5 text-xs text-gray-600 dark:text-gray-300 whitespace-nowrap">
                                    <svg class="w-3.5 h-3.5 shrink-0 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                    <span :class="record.last_login_at === '@lang('admin::app.admin-panel.employees.datagrid.never')' ? 'text-amber-600 dark:text-amber-400 font-semibold' : ''">
                                        @{{ record.last_login_at }}
                                    </span>
                                </div>

                                <!-- Work Summary (Leads, Quotes, Activities) -->
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    <span
                                        class="inline-flex items-center gap-1 rounded-md bg-emerald-50 px-1.5 py-0.5 text-[11px] font-bold text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300"
                                        title="@lang('admin::app.admin-panel.employees.datagrid.leads')"
                                    >
                                        <span class="text-[9px] uppercase tracking-tighter text-emerald-500">L:</span>
                                        @{{ record.work_summary.leads }}
                                    </span>
                                    <span
                                        class="inline-flex items-center gap-1 rounded-md bg-purple-50 px-1.5 py-0.5 text-[11px] font-bold text-purple-700 dark:bg-purple-950/60 dark:text-purple-300"
                                        title="@lang('admin::app.admin-panel.employees.datagrid.quotes')"
                                    >
                                        <span class="text-[9px] uppercase tracking-tighter text-purple-500">Q:</span>
                                        @{{ record.work_summary.quotes }}
                                    </span>
                                    <span
                                        class="inline-flex items-center gap-1 rounded-md bg-sky-50 px-1.5 py-0.5 text-[11px] font-bold text-sky-700 dark:bg-sky-950/60 dark:text-sky-300"
                                        title="@lang('admin::app.admin-panel.employees.datagrid.activities')"
                                    >
                                        <span class="text-[9px] uppercase tracking-tighter text-sky-500">A:</span>
                                        @{{ record.work_summary.activities }}
                                    </span>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex items-center justify-end gap-1">
                                    <!-- View Details -->
                                    <a
                                        :href="'{{ route('admin.panel.employees.view', ':id') }}'.replace(':id', record.id)"
                                        class="cursor-pointer rounded-lg p-1.5 text-gray-500 transition hover:bg-slate-200 hover:text-indigo-600 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-indigo-400"
                                        title="@lang('admin::app.admin-panel.employees.datagrid.view')"
                                    >
                                        <span class="icon-eye text-lg"></span>
                                    </a>

                                    <!-- Edit Employee -->
                                    <button
                                        type="button"
                                        class="cursor-pointer rounded-lg p-1.5 text-gray-500 transition hover:bg-slate-200 hover:text-blue-600 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-blue-400"
                                        @click="openEditModal(record.id)"
                                        title="@lang('admin::app.admin-panel.employees.datagrid.edit')"
                                    >
                                        <span class="icon-edit text-lg"></span>
                                    </button>

                                    <!-- Password Management (Reveal / Reset) -->
                                    <button
                                        type="button"
                                        class="cursor-pointer rounded-lg p-1.5 text-gray-500 transition hover:bg-slate-200 hover:text-amber-600 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-amber-400"
                                        @click="openPasswordModal(record.id, record.name.name)"
                                        title="@lang('admin::app.admin-panel.employees.datagrid.password')"
                                    >
                                        <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                                    </button>

                                    <!-- Switch Account (Impersonate) -->
                                    <button
                                        v-if="record.role_name.role_permission_type !== 'all'"
                                        type="button"
                                        class="cursor-pointer rounded-lg p-1.5 text-gray-500 transition hover:bg-slate-200 hover:text-emerald-600 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-emerald-400"
                                        @click="openImpersonateModal(record.id, record.name.name)"
                                        title="@lang('admin::app.admin-panel.employees.datagrid.impersonate')"
                                    >
                                        <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path><polyline points="10 17 15 12 10 7"></polyline><line x1="15" y1="12" x2="3" y2="12"></line></svg>
                                    </button>
                                    <button
                                        v-else
                                        type="button"
                                        disabled
                                        class="cursor-not-allowed rounded-lg p-1.5 text-gray-300 dark:text-gray-700 opacity-50"
                                        title="@lang('admin::app.admin-panel.impersonate.cannot_impersonate_admin')"
                                    >
                                        <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path><polyline points="10 17 15 12 10 7"></polyline><line x1="15" y1="12" x2="3" y2="12"></line></svg>
                                    </button>

                                    <!-- Delete Employee -->
                                    <button
                                        type="button"
                                        class="cursor-pointer rounded-lg p-1.5 text-gray-500 transition hover:bg-slate-200 hover:text-rose-600 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-rose-400"
                                        @click="openDeleteModal(record.id, record.name.name)"
                                        title="@lang('admin::app.admin-panel.employees.datagrid.delete')"
                                    >
                                        <span class="icon-delete text-lg"></span>
                                    </button>
                                </div>
                            </div>

                            <!-- Mobile Card View -->
                            <div
                                v-for="record in available.records"
                                :key="'mobile-' + record.id"
                                class="hidden border-b p-4 text-gray-700 dark:border-gray-800 dark:text-gray-300 max-lg:flex flex-col gap-3"
                            >
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-base text-gray-900 dark:text-white">@{{ record.name.name }}</span>
                                        <span
                                            v-if="record.role_name.role_permission_type === 'all'"
                                            class="rounded-full bg-red-600 px-2 py-0.2 text-[10px] font-black uppercase text-white"
                                        >
                                            @lang('admin::app.admin-panel.roles.admin')
                                        </span>
                                        <span
                                            v-else
                                            class="rounded-full bg-blue-600 px-2 py-0.2 text-[10px] font-black uppercase text-white"
                                        >
                                            @lang('admin::app.admin-panel.roles.employee')
                                        </span>
                                    </div>
                                    <span :class="record.status == 1 ? 'label-active' : 'label-inactive'" class="text-xs">
                                        @{{ record.status == 1 ? "@lang('admin::app.admin-panel.employees.datagrid.active')" : "@lang('admin::app.admin-panel.employees.datagrid.inactive')" }}
                                    </span>
                                </div>

                                <p class="text-xs text-gray-500 dark:text-gray-400">@{{ record.email }} &bull; Role: @{{ record.role_name.role_name }}</p>

                                <div class="flex items-center gap-2 text-xs">
                                    <span>Last Login:</span>
                                    <span class="font-semibold text-gray-900 dark:text-white">@{{ record.last_login_at }}</span>
                                </div>

                                <div class="flex items-center justify-end gap-2 pt-2 border-t border-gray-100 dark:border-gray-800">
                                    <a :href="'{{ route('admin.panel.employees.view', ':id') }}'.replace(':id', record.id)" class="primary-button !px-2.5 !py-1 text-xs">View</a>
                                    <button type="button" @click="openEditModal(record.id)" class="secondary-button !px-2.5 !py-1 text-xs">Edit</button>
                                    <button type="button" @click="openPasswordModal(record.id, record.name.name)" class="secondary-button !px-2.5 !py-1 text-xs">Password</button>
                                    <button v-if="record.role_name.role_permission_type !== 'all'" type="button" @click="openImpersonateModal(record.id, record.name.name)" class="secondary-button !px-2.5 !py-1 text-xs !text-emerald-600">Login As</button>
                                    <button type="button" @click="openDeleteModal(record.id, record.name.name)" class="secondary-button !px-2.5 !py-1 text-xs !text-rose-600">Delete</button>
                                </div>
                            </div>
                        </template>
                    </template>
                </x-admin::datagrid>

                <!-- Create Employee Modal -->
                <x-admin::modal ref="createEmployeeModal">
                    <x-slot:header>
                        <p class="text-lg font-bold text-gray-800 dark:text-white">
                            @lang('admin::app.admin-panel.employees.create-title')
                        </p>
                    </x-slot>

                    <x-slot:content>
                        <form @submit.prevent="storeEmployee" ref="createForm" class="flex flex-col gap-3">
                            <!-- Name -->
                            <div class="flex flex-col gap-1">
                                <label class="text-xs font-bold text-gray-800 dark:text-white required">
                                    @lang('admin::app.admin-panel.employees.create.name')
                                </label>
                                <input
                                    type="text"
                                    v-model="createData.name"
                                    required
                                    placeholder="e.g. Jane Doe"
                                    class="rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-800 dark:border-gray-800 dark:bg-gray-900 dark:text-white"
                                />
                                <span v-if="createErrors.name" class="text-xs text-rose-500">@{{ createErrors.name[0] }}</span>
                            </div>

                            <!-- Email -->
                            <div class="flex flex-col gap-1">
                                <label class="text-xs font-bold text-gray-800 dark:text-white required">
                                    @lang('admin::app.admin-panel.employees.create.email')
                                </label>
                                <input
                                    type="email"
                                    v-model="createData.email"
                                    required
                                    placeholder="jane.doe@example.com"
                                    class="rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-800 dark:border-gray-800 dark:bg-gray-900 dark:text-white"
                                />
                                <span v-if="createErrors.email" class="text-xs text-rose-500">@{{ createErrors.email[0] }}</span>
                            </div>

                            <!-- Password & Confirm Password -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div class="flex flex-col gap-1">
                                    <label class="text-xs font-bold text-gray-800 dark:text-white required">
                                        @lang('admin::app.admin-panel.employees.create.password')
                                    </label>
                                    <div class="relative flex items-center">
                                        <input
                                            :type="showCreatePassword ? 'text' : 'password'"
                                            v-model="createData.password"
                                            placeholder="••••••••"
                                            class="w-full rounded-md border border-gray-300 px-3 py-2 pr-10 text-sm text-gray-800 dark:border-gray-800 dark:bg-gray-900 dark:text-white"
                                        />
                                        <button
                                            type="button"
                                            @click="showCreatePassword = !showCreatePassword"
                                            class="absolute right-2.5 p-1 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 focus:outline-none"
                                            :title="showCreatePassword ? 'Hide Password' : 'Show Password'"
                                        >
                                            <svg v-if="showCreatePassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a10.025 10.025 0 011.13-.163c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21M3 3l18 18"/></svg>
                                        </button>
                                    </div>
                                    <span v-if="createErrors.password" class="text-xs text-rose-500">@{{ createErrors.password[0] }}</span>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <label class="text-xs font-bold text-gray-800 dark:text-white required">
                                        @lang('admin::app.admin-panel.employees.create.confirm-password')
                                    </label>
                                    <div class="relative flex items-center">
                                        <input
                                            :type="showCreateConfirmPassword ? 'text' : 'password'"
                                            v-model="createData.confirm_password"
                                            placeholder="••••••••"
                                            class="w-full rounded-md border border-gray-300 px-3 py-2 pr-10 text-sm text-gray-800 dark:border-gray-800 dark:bg-gray-900 dark:text-white"
                                        />
                                        <button
                                            type="button"
                                            @click="showCreateConfirmPassword = !showCreateConfirmPassword"
                                            class="absolute right-2.5 p-1 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 focus:outline-none"
                                            :title="showCreateConfirmPassword ? 'Hide Password' : 'Show Password'"
                                        >
                                            <svg v-if="showCreateConfirmPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a10.025 10.025 0 011.13-.163c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21M3 3l18 18"/></svg>
                                        </button>
                                    </div>
                                    <span v-if="createErrors.confirm_password" class="text-xs text-rose-500">@{{ createErrors.confirm_password[0] }}</span>
                                </div>
                            </div>

                            <!-- Role -->
                            <div class="flex flex-col gap-1">
                                <label class="text-xs font-bold text-gray-800 dark:text-white required">
                                    @lang('admin::app.admin-panel.employees.create.role')
                                </label>
                                <select
                                    v-model="createData.role_id"
                                    required
                                    class="rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-800 dark:border-gray-800 dark:bg-gray-900 dark:text-white"
                                >
                                    <option v-for="role in formattedRoles" :key="role.id" :value="role.id">
                                        @{{ role.label }}
                                    </option>
                                </select>
                                <span v-if="createErrors.role_id" class="text-xs text-rose-500">@{{ createErrors.role_id[0] }}</span>

                                <!-- Admin Role Selection Warning Banner -->
                                <div v-if="selectedCreateRoleIsAdmin" class="mt-2 rounded-lg bg-amber-50 p-3 border border-amber-300 text-xs font-semibold text-amber-900 dark:bg-amber-950/50 dark:border-amber-700/60 dark:text-amber-200 flex items-start gap-2">
                                    <svg class="w-4 h-4 shrink-0 text-amber-600 dark:text-amber-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                    <span>@lang('admin::app.admin-panel.employees.admin-role-warning')</span>
                                </div>
                            </div>

                            <!-- Status Toggle -->
                            <div class="flex items-center gap-2 pt-2">
                                <input
                                    type="checkbox"
                                    id="create_status"
                                    v-model="createData.status"
                                    class="h-4 w-4 rounded text-brandColor"
                                />
                                <label for="create_status" class="text-sm font-semibold text-gray-800 dark:text-white cursor-pointer">
                                    @lang('admin::app.admin-panel.employees.create.status') (@{{ createData.status ? '@lang('admin::app.admin-panel.employees.create.active')' : '@lang('admin::app.admin-panel.employees.create.inactive')' }})
                                </label>
                            </div>
                        </form>
                    </x-slot>

                    <x-slot:footer>
                        <div class="flex items-center justify-end gap-2">
                            <button
                                type="button"
                                class="secondary-button"
                                @click="$refs.createEmployeeModal.toggle()"
                            >
                                @lang('admin::app.admin-panel.employees.create.cancel-btn')
                            </button>
                            <button
                                type="button"
                                class="primary-button"
                                :disabled="isCreating"
                                @click="storeEmployee"
                            >
                                <span v-if="isCreating">Creating...</span>
                                <span v-else>@lang('admin::app.admin-panel.employees.create.save-btn')</span>
                            </button>
                        </div>
                    </x-slot>
                </x-admin::modal>

                <!-- Edit Employee Modal -->
                <x-admin::modal ref="editEmployeeModal">
                    <x-slot:header>
                        <p class="text-lg font-bold text-gray-800 dark:text-white">
                            @lang('admin::app.admin-panel.employees.edit.title')
                        </p>
                    </x-slot>

                    <x-slot:content>
                        <form @submit.prevent="saveEmployee" ref="editForm" class="flex flex-col gap-3">
                            <!-- Name -->
                            <div class="flex flex-col gap-1">
                                <label class="text-xs font-bold text-gray-800 dark:text-white required">
                                    @lang('admin::app.admin-panel.employees.edit.name')
                                </label>
                                <input
                                    type="text"
                                    v-model="editData.name"
                                    required
                                    class="rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-800 dark:border-gray-800 dark:bg-gray-900 dark:text-white"
                                />
                                <span v-if="errors.name" class="text-xs text-rose-500">@{{ errors.name[0] }}</span>
                            </div>

                            <!-- Email -->
                            <div class="flex flex-col gap-1">
                                <label class="text-xs font-bold text-gray-800 dark:text-white required">
                                    @lang('admin::app.admin-panel.employees.edit.email')
                                </label>
                                <input
                                    type="email"
                                    v-model="editData.email"
                                    required
                                    class="rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-800 dark:border-gray-800 dark:bg-gray-900 dark:text-white"
                                />
                                <span v-if="errors.email" class="text-xs text-rose-500">@{{ errors.email[0] }}</span>
                            </div>

                            <!-- Role -->
                            <div class="flex flex-col gap-1">
                                <label class="text-xs font-bold text-gray-800 dark:text-white required">
                                    @lang('admin::app.admin-panel.employees.edit.role')
                                </label>
                                <select
                                    v-model="editData.role_id"
                                    required
                                    class="rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-800 dark:border-gray-800 dark:bg-gray-900 dark:text-white"
                                >
                                    <option v-for="role in roles" :key="role.id" :value="role.id">
                                        @{{ role.permission_type === 'all' ? 'Administrator (Full Access)' : (role.name === 'Employee' ? 'Employee (Limited Access)' : role.name + ' (Limited Access)') }}
                                    </option>
                                </select>
                                <span v-if="errors.role_id" class="text-xs text-rose-500">@{{ errors.role_id[0] }}</span>
                            </div>

                            <!-- View Permission -->
                            <div class="flex flex-col gap-1">
                                <label class="text-xs font-bold text-gray-800 dark:text-white required">
                                    @lang('admin::app.admin-panel.employees.edit.view-permission')
                                </label>
                                <select
                                    v-model="editData.view_permission"
                                    required
                                    class="rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-800 dark:border-gray-800 dark:bg-gray-900 dark:text-white"
                                >
                                    <option value="global">@lang('admin::app.admin-panel.employees.edit.global')</option>
                                    <option value="group">@lang('admin::app.admin-panel.employees.edit.group')</option>
                                    <option value="individual">@lang('admin::app.admin-panel.employees.edit.individual')</option>
                                </select>
                            </div>

                            <!-- Groups if group permission -->
                            <div v-if="editData.view_permission === 'group'" class="flex flex-col gap-1">
                                <label class="text-xs font-bold text-gray-800 dark:text-white">
                                    @lang('admin::app.admin-panel.employees.edit.groups')
                                </label>
                                <div class="grid grid-cols-2 gap-2 border border-gray-200 p-2 rounded-md dark:border-gray-800">
                                    <label v-for="group in groups" :key="group.id" class="flex items-center gap-2 text-xs cursor-pointer">
                                        <input type="checkbox" :value="group.id" v-model="editData.groups" class="rounded" />
                                        <span>@{{ group.name }}</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Status Toggle -->
                            <div class="flex items-center gap-2 pt-2">
                                <input
                                    type="checkbox"
                                    id="edit_status"
                                    v-model="editData.status"
                                    class="h-4 w-4 rounded text-brandColor"
                                />
                                <label for="edit_status" class="text-sm font-semibold text-gray-800 dark:text-white cursor-pointer">
                                    @lang('admin::app.admin-panel.employees.edit.status') (@{{ editData.status ? '@lang('admin::app.admin-panel.employees.edit.active')' : '@lang('admin::app.admin-panel.employees.edit.inactive')' }})
                                </label>
                            </div>
                        </form>
                    </x-slot>

                    <x-slot:footer>
                        <div class="flex items-center justify-end gap-2">
                            <button
                                type="button"
                                class="secondary-button"
                                @click="$refs.editEmployeeModal.toggle()"
                            >
                                @lang('admin::app.admin-panel.impersonate.cancel')
                            </button>
                            <button
                                type="button"
                                class="primary-button"
                                :disabled="isProcessing"
                                @click="saveEmployee"
                            >
                                <span v-if="isProcessing">Saving...</span>
                                <span v-else>@lang('admin::app.admin-panel.employees.edit.save-btn')</span>
                            </button>
                        </div>
                    </x-slot>
                </x-admin::modal>

                <!-- Password Management Modal (Reveal & Reset) -->
                <x-admin::modal ref="passwordModal">
                    <x-slot:header>
                        <p class="text-lg font-bold text-gray-800 dark:text-white">
                            @{{ "@lang('admin::app.admin-panel.employees.password.modal-title')".replace(':name', targetEmployeeName) }}
                        </p>
                    </x-slot>

                    <x-slot:content>
                        <!-- Tabs -->
                        <div class="flex border-b border-gray-200 dark:border-gray-800 mb-4">
                            <button
                                type="button"
                                class="px-4 py-2 text-sm font-bold border-b-2 transition"
                                :class="activePasswordTab === 'reveal' ? 'border-brandColor text-brandColor' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400'"
                                @click="activePasswordTab = 'reveal'"
                            >
                                @lang('admin::app.admin-panel.employees.password.tab-reveal')
                            </button>
                            <button
                                type="button"
                                class="px-4 py-2 text-sm font-bold border-b-2 transition"
                                :class="activePasswordTab === 'reset' ? 'border-brandColor text-brandColor' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400'"
                                @click="activePasswordTab = 'reset'"
                            >
                                @lang('admin::app.admin-panel.employees.password.tab-reset')
                            </button>
                        </div>

                        <!-- Tab 1: Reveal Password -->
                        <div v-if="activePasswordTab === 'reveal'" class="flex flex-col gap-3">
                            <div class="rounded-lg bg-amber-50 p-3 border border-amber-200 text-xs text-amber-800 dark:bg-amber-950/40 dark:border-amber-900/60 dark:text-amber-200">
                                @lang('admin::app.admin-panel.employees.password.admin-password-prompt')
                            </div>

                            <div v-if="!revealedPassword" class="flex flex-col gap-2">
                                <label class="text-xs font-bold text-gray-800 dark:text-white required">
                                    @lang('admin::app.admin-panel.employees.password.admin-password-label')
                                </label>
                                <div class="relative">
                                    <input
                                        :type="showAdminPass ? 'text' : 'password'"
                                        v-model="adminPasswordVerify"
                                        placeholder="@lang('admin::app.admin-panel.employees.password.admin-password-placeholder')"
                                        class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-800 dark:border-gray-800 dark:bg-gray-900 dark:text-white"
                                        @keyup.enter="revealPassword"
                                    />
                                    <button
                                        type="button"
                                        @click="showAdminPass = !showAdminPass"
                                        class="absolute right-2.5 top-2.5 text-gray-400 hover:text-gray-600"
                                    >
                                        <span :class="showAdminPass ? 'icon-eye-hide' : 'icon-eye'"></span>
                                    </button>
                                </div>
                                <span v-if="revealError" class="text-xs font-semibold text-rose-500">@{{ revealError }}</span>

                                <button
                                    type="button"
                                    class="primary-button self-start mt-2"
                                    :disabled="isRevealing || !adminPasswordVerify"
                                    @click="revealPassword"
                                >
                                    <span v-if="isRevealing">Verifying...</span>
                                    <span v-else>@lang('admin::app.admin-panel.employees.password.reveal-btn')</span>
                                </button>
                            </div>

                            <!-- Revealed Password Box -->
                            <div v-else class="flex flex-col gap-2 mt-2">
                                <label class="text-xs font-bold text-gray-600 dark:text-gray-300">
                                    @lang('admin::app.admin-panel.employees.password.revealed-title')
                                </label>
                                <div class="flex items-center gap-2 rounded-lg border border-emerald-300 bg-emerald-50/80 p-3 dark:border-emerald-900/60 dark:bg-emerald-950/40">
                                    <input
                                        :type="showPlainPass ? 'text' : 'password'"
                                        :value="revealedPassword"
                                        readonly
                                        class="w-full bg-transparent font-mono text-base font-bold text-emerald-900 dark:text-emerald-200 outline-hidden"
                                    />
                                    <button
                                        type="button"
                                        @click="showPlainPass = !showPlainPass"
                                        class="rounded p-1 text-emerald-700 hover:bg-emerald-100 dark:text-emerald-300 dark:hover:bg-emerald-900/60"
                                        title="Toggle Visibility"
                                    >
                                        <span :class="showPlainPass ? 'icon-eye-hide' : 'icon-eye'" class="text-base"></span>
                                    </button>
                                    <button
                                        type="button"
                                        @click="copyPassword"
                                        class="secondary-button !px-2.5 !py-1 text-xs whitespace-nowrap"
                                    >
                                        @{{ isCopied ? "@lang('admin::app.admin-panel.employees.password.copied')" : "@lang('admin::app.admin-panel.employees.password.copy-btn')" }}
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Tab 2: Reset Password -->
                        <div v-if="activePasswordTab === 'reset'" class="flex flex-col gap-3">
                            <div class="flex flex-col gap-1">
                                <label class="text-xs font-bold text-gray-800 dark:text-white required">
                                    @lang('admin::app.admin-panel.employees.password.new-password-label')
                                </label>
                                <div class="relative flex items-center">
                                    <input
                                        :type="showResetPassword ? 'text' : 'password'"
                                        v-model="resetPasswordData.password"
                                        required
                                        minlength="6"
                                        class="w-full rounded-md border border-gray-300 px-3 py-2 pr-10 text-sm text-gray-800 dark:border-gray-800 dark:bg-gray-900 dark:text-white"
                                    />
                                    <button
                                        type="button"
                                        @click="showResetPassword = !showResetPassword"
                                        class="absolute right-2.5 p-1 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 focus:outline-none"
                                        :title="showResetPassword ? 'Hide Password' : 'Show Password'"
                                    >
                                        <svg v-if="showResetPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a10.025 10.025 0 011.13-.163c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21M3 3l18 18"/></svg>
                                    </button>
                                </div>
                                <span v-if="resetErrors.password" class="text-xs text-rose-500">@{{ resetErrors.password[0] }}</span>
                            </div>

                            <div class="flex flex-col gap-1">
                                <label class="text-xs font-bold text-gray-800 dark:text-white required">
                                    @lang('admin::app.admin-panel.employees.password.confirm-password-label')
                                </label>
                                <div class="relative flex items-center">
                                    <input
                                        :type="showResetConfirmPassword ? 'text' : 'password'"
                                        v-model="resetPasswordData.confirm_password"
                                        required
                                        minlength="6"
                                        class="w-full rounded-md border border-gray-300 px-3 py-2 pr-10 text-sm text-gray-800 dark:border-gray-800 dark:bg-gray-900 dark:text-white"
                                    />
                                    <button
                                        type="button"
                                        @click="showResetConfirmPassword = !showResetConfirmPassword"
                                        class="absolute right-2.5 p-1 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 focus:outline-none"
                                        :title="showResetConfirmPassword ? 'Hide Password' : 'Show Password'"
                                    >
                                        <svg v-if="showResetConfirmPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a10.025 10.025 0 011.13-.163c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21M3 3l18 18"/></svg>
                                    </button>
                                </div>
                                <span v-if="resetErrors.confirm_password" class="text-xs text-rose-500">@{{ resetErrors.confirm_password[0] }}</span>
                            </div>

                            <button
                                type="button"
                                class="primary-button self-start mt-2"
                                :disabled="isResetting"
                                @click="submitPasswordReset"
                            >
                                <span v-if="isResetting">Updating...</span>
                                <span v-else>@lang('admin::app.admin-panel.employees.password.reset-btn')</span>
                            </button>
                        </div>
                    </x-slot>

                    <x-slot:footer>
                        <button
                            type="button"
                            class="secondary-button"
                            @click="$refs.passwordModal.toggle()"
                        >
                            Close
                        </button>
                    </x-slot>
                </x-admin::modal>

                <!-- Impersonate Confirmation Modal -->
                <x-admin::modal ref="impersonateModal">
                    <x-slot:header>
                        <p class="text-lg font-bold text-gray-800 dark:text-white">
                            @lang('admin::app.admin-panel.impersonate.modal_title')
                        </p>
                    </x-slot>

                    <x-slot:content>
                        <div class="flex flex-col gap-3">
                            <div class="rounded-lg bg-indigo-50 p-4 border border-indigo-200 text-sm text-indigo-900 dark:bg-indigo-950/40 dark:border-indigo-900/60 dark:text-indigo-200">
                                @{{ "@lang('admin::app.admin-panel.impersonate.modal_message')".replace(':name', targetEmployeeName) }}
                            </div>

                            <form :action="'{{ route('admin.panel.employees.impersonate', ':id') }}'.replace(':id', targetEmployeeId)" method="POST" ref="impersonateForm">
                                @csrf
                            </form>
                        </div>
                    </x-slot>

                    <x-slot:footer>
                        <div class="flex items-center justify-end gap-2">
                            <button
                                type="button"
                                class="secondary-button"
                                @click="$refs.impersonateModal.toggle()"
                            >
                                @lang('admin::app.admin-panel.impersonate.cancel')
                            </button>
                            <button
                                type="button"
                                class="primary-button !bg-emerald-600 hover:!bg-emerald-700 text-white"
                                @click="$refs.impersonateForm.submit()"
                            >
                                @lang('admin::app.admin-panel.impersonate.confirm')
                            </button>
                        </div>
                    </x-slot>
                </x-admin::modal>

                <!-- Delete Confirmation Modal -->
                <x-admin::modal ref="deleteModal">
                    <x-slot:header>
                        <p class="text-lg font-bold text-rose-600 dark:text-rose-400">
                            @lang('admin::app.admin-panel.employees.delete.modal-title')
                        </p>
                    </x-slot>

                    <x-slot:content>
                        <p class="text-sm text-gray-700 dark:text-gray-300">
                            @{{ "@lang('admin::app.admin-panel.employees.delete.modal-message')".replace(':name', targetEmployeeName) }}
                        </p>
                        <span v-if="deleteError" class="mt-2 block text-xs font-semibold text-rose-500">@{{ deleteError }}</span>
                    </x-slot>

                    <x-slot:footer>
                        <div class="flex items-center justify-end gap-2">
                            <button
                                type="button"
                                class="secondary-button"
                                @click="$refs.deleteModal.toggle()"
                            >
                                @lang('admin::app.admin-panel.employees.delete.cancel')
                            </button>
                            <button
                                type="button"
                                class="primary-button !bg-rose-600 hover:!bg-rose-700 text-white"
                                :disabled="isDeleting"
                                @click="confirmDelete"
                            >
                                <span v-if="isDeleting">Deleting...</span>
                                <span v-else>@lang('admin::app.admin-panel.employees.delete.confirm')</span>
                            </button>
                        </div>
                    </x-slot>
                </x-admin::modal>
            </div>
        </script>

        <script type="module">
            app.component('v-employees-oversight', {
                template: '#v-employees-oversight-template',

                data() {
                    return {
                        roles: @json($roles),
                        groups: @json($groups),

                        targetEmployeeId: null,
                        targetEmployeeName: '',

                        // Password visibility toggles
                        showCreatePassword: true,
                        showCreateConfirmPassword: true,
                        showResetPassword: false,
                        showResetConfirmPassword: false,

                        // Create Modal state
                        createData: {
                            name: '',
                            email: '',
                            password: '',
                            confirm_password: '',
                            role_id: null,
                            status: true,
                            view_permission: 'individual',
                            groups: [],
                        },
                        createErrors: {},
                        isCreating: false,

                        // Edit Modal state
                        editData: {
                            id: null,
                            name: '',
                            email: '',
                            role_id: null,
                            status: true,
                            view_permission: 'global',
                            groups: [],
                        },
                        errors: {},
                        isProcessing: false,

                        // Password Modal state
                        activePasswordTab: 'reveal',
                        adminPasswordVerify: '',
                        showAdminPass: false,
                        showPlainPass: true,
                        revealedPassword: '',
                        revealError: '',
                        isRevealing: false,
                        isCopied: false,

                        // Reset password state
                        resetPasswordData: {
                            password: '',
                            confirm_password: '',
                        },
                        resetErrors: {},
                        isResetting: false,

                        // Delete state
                        isDeleting: false,
                        deleteError: '',
                    };
                },

                computed: {
                    formattedRoles() {
                        return [...this.roles].sort((a, b) => {
                            if (a.permission_type === 'all' && b.permission_type !== 'all') return 1;
                            if (a.permission_type !== 'all' && b.permission_type === 'all') return -1;
                            return 0;
                        }).map(r => ({
                            id: r.id,
                            name: r.name,
                            permission_type: r.permission_type,
                            label: r.permission_type === 'all'
                                ? 'Administrator (Full Access)'
                                : (r.name === 'Employee' ? 'Employee (Limited Access)' : r.name + ' (Limited Access)')
                        }));
                    },

                    selectedCreateRoleIsAdmin() {
                        const role = this.roles.find(r => r.id == this.createData.role_id);
                        return role && role.permission_type === 'all';
                    },
                },

                methods: {
                    openCreateModal() {
                        const defaultRole = this.formattedRoles.find(r => r.permission_type !== 'all') || this.formattedRoles[0];
                        this.createData = {
                            name: '',
                            email: '',
                            password: '',
                            confirm_password: '',
                            role_id: defaultRole ? defaultRole.id : '',
                            status: true,
                            view_permission: 'global',
                            groups: [],
                        };
                        this.showCreatePassword = true;
                        this.showCreateConfirmPassword = true;
                        this.createErrors = {};
                        this.$refs.createEmployeeModal.toggle();
                    },

                    storeEmployee() {
                        this.isCreating = true;
                        this.createErrors = {};

                        const payload = {
                            ...this.createData,
                            status: this.createData.status ? 1 : 0,
                        };

                        this.$axios.post("{{ route('admin.panel.employees.store') }}", payload)
                            .then(response => {
                                this.isCreating = false;
                                this.$refs.createEmployeeModal.toggle();
                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                                this.$refs.datagrid.get();
                            })
                            .catch(error => {
                                this.isCreating = false;
                                if (error.response?.status === 422) {
                                    this.createErrors = error.response.data.errors;
                                } else {
                                    this.$emitter.emit('add-flash', { type: 'error', message: error.response?.data?.message || 'Failed to create employee.' });
                                }
                            });
                    },

                    openEditModal(id) {
                        this.errors = {};
                        this.$axios.get("{{ route('admin.panel.employees.edit', ':id') }}".replace(':id', id))
                            .then(response => {
                                const user = response.data.data;
                                this.editData = {
                                    id: user.id,
                                    name: user.name,
                                    email: user.email,
                                    role_id: user.role_id,
                                    status: Boolean(user.status),
                                    view_permission: user.view_permission || 'global',
                                    groups: (user.groups || []).map(g => g.id),
                                };
                                this.$refs.editEmployeeModal.toggle();
                            })
                            .catch(error => {
                                this.$emitter.emit('add-flash', { type: 'error', message: 'Failed to load employee details.' });
                            });
                    },

                    saveEmployee() {
                        this.isProcessing = true;
                        this.errors = {};

                        this.$axios.put("{{ route('admin.panel.employees.update', ':id') }}".replace(':id', this.editData.id), this.editData)
                            .then(response => {
                                this.isProcessing = false;
                                this.$refs.editEmployeeModal.toggle();
                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                                this.$refs.datagrid.get();
                            })
                            .catch(error => {
                                this.isProcessing = false;
                                if (error.response?.status === 422) {
                                    this.errors = error.response.data.errors;
                                } else {
                                    this.$emitter.emit('add-flash', { type: 'error', message: error.response?.data?.message || 'Update failed.' });
                                }
                            });
                    },

                    openPasswordModal(id, name) {
                        this.targetEmployeeId = id;
                        this.targetEmployeeName = name;
                        this.activePasswordTab = 'reveal';
                        this.adminPasswordVerify = '';
                        this.revealedPassword = '';
                        this.revealError = '';
                        this.showAdminPass = false;
                        this.showPlainPass = true;
                        this.isCopied = false;
                        this.resetPasswordData = { password: '', confirm_password: '' };
                        this.resetErrors = {};
                        this.$refs.passwordModal.toggle();
                    },

                    revealPassword() {
                        if (!this.adminPasswordVerify) return;

                        this.isRevealing = true;
                        this.revealError = '';

                        this.$axios.post("{{ route('admin.panel.employees.reveal_password', ':id') }}".replace(':id', this.targetEmployeeId), {
                            admin_password: this.adminPasswordVerify,
                        })
                        .then(response => {
                            this.isRevealing = false;
                            this.revealedPassword = response.data.password;
                        })
                        .catch(error => {
                            this.isRevealing = false;
                            this.revealError = error.response?.data?.message || 'Failed to reveal password.';
                        });
                    },

                    copyPassword() {
                        if (!this.revealedPassword) return;
                        navigator.clipboard.writeText(this.revealedPassword);
                        this.isCopied = true;
                        setTimeout(() => { this.isCopied = false; }, 2000);
                    },

                    submitPasswordReset() {
                        this.isResetting = true;
                        this.resetErrors = {};

                        this.$axios.post("{{ route('admin.panel.employees.update_password', ':id') }}".replace(':id', this.targetEmployeeId), this.resetPasswordData)
                            .then(response => {
                                this.isResetting = false;
                                this.$refs.passwordModal.toggle();
                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                            })
                            .catch(error => {
                                this.isResetting = false;
                                if (error.response?.status === 422) {
                                    this.resetErrors = error.response.data.errors;
                                } else {
                                    this.$emitter.emit('add-flash', { type: 'error', message: error.response?.data?.message || 'Password update failed.' });
                                }
                            });
                    },

                    openImpersonateModal(id, name) {
                        this.targetEmployeeId = id;
                        this.targetEmployeeName = name;
                        this.$refs.impersonateModal.toggle();
                    },

                    openDeleteModal(id, name) {
                        this.targetEmployeeId = id;
                        this.targetEmployeeName = name;
                        this.deleteError = '';
                        this.$refs.deleteModal.toggle();
                    },

                    confirmDelete() {
                        this.isDeleting = true;
                        this.deleteError = '';

                        this.$axios.delete("{{ route('admin.panel.employees.delete', ':id') }}".replace(':id', this.targetEmployeeId))
                            .then(response => {
                                this.isDeleting = false;
                                this.$refs.deleteModal.toggle();
                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                                this.$refs.datagrid.get();
                            })
                            .catch(error => {
                                this.isDeleting = false;
                                this.deleteError = error.response?.data?.message || 'Failed to delete account.';
                            });
                    },
                },
            });
        </script>
    @endPushOnce
</x-admin::layouts>
