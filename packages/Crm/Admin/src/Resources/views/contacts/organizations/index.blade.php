<x-admin::layouts>
    <!-- Page Title -->
    <x-slot:title>
        @lang('admin::app.contacts.organizations.index.title')
    </x-slot>

    <div class="flex flex-col gap-4">
        <!-- Top Header Card -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 rounded-xl border border-gray-200 bg-white p-4 shadow-xs dark:border-gray-800 dark:bg-gray-900">
            <div class="flex items-center gap-3.5">
                <div class="flex h-11 w-11 sm:h-12 sm:w-12 shrink-0 items-center justify-center rounded-2xl bg-blue-100 text-blue-600 dark:bg-blue-950/60 dark:text-blue-400 shadow-xs">
                    <span class="icon-organization text-2xl"></span>
                </div>

                <div class="flex flex-col">
                    <div class="flex items-center gap-2">
                        <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                            @lang('admin::app.contacts.organizations.index.title')
                        </h1>
                    </div>
                    <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">
                        View and manage all your organizations in one place.
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2.5 shrink-0">
                {!! view_render_event('admin.organizations.index.create_button.before') !!}

                @if (bouncer()->hasPermission('contacts.organizations.create'))
                    <a
                        href="{{ route('admin.contacts.organizations.create') }}"
                        class="primary-button inline-flex items-center gap-1.5 rounded-xl px-4 py-2 text-sm font-medium shadow-sm transition-all"
                    >
                        <span class="text-lg leading-none font-bold">+</span>
                        @lang('admin::app.contacts.organizations.index.create-btn')
                    </a>
                @endif

                {!! view_render_event('admin.organizations.index.create_button.after') !!}
            </div>
        </div>

        {!! view_render_event('admin.organizations.datagrid.index.before') !!}

        <v-organizations>
            <!-- DataGrid Shimmer -->
            <x-admin::shimmer.datagrid :is-multi-row="true"/>
        </v-organizations>

        {!! view_render_event('admin.organizations.datagrid.index.after') !!}
    </div>

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-organizations-template"
        >
            <x-admin::datagrid
                src="{{ route('admin.contacts.organizations.index') }}"
                :isMultiRow="true"
                ref="datagrid"
            >
                <!-- Desktop & Mobile Header -->
                <template #header="{
                    isLoading,
                    available,
                    applied,
                    selectAll,
                    sort,
                    performAction
                }">
                    <template v-if="isLoading">
                        <x-admin::shimmer.datagrid.table.head :isMultiRow="true" />
                    </template>

                    <template v-else>
                        <!-- Desktop Table Header -->
                        <div
                            class="row grid min-w-[860px] w-full items-center gap-4 border-b border-gray-200 bg-gray-50/80 px-4 py-3 text-xs font-semibold tracking-wide text-gray-600 dark:border-gray-800 dark:bg-gray-900/90 dark:text-gray-300 max-lg:hidden"
                            style="grid-template-columns: 44px 100px minmax(220px, 1.6fr) 150px 200px 140px;"
                        >
                            <!-- Mass Select All Checkbox -->
                            <div class="flex items-center justify-center">
                                <label
                                    class="flex cursor-pointer items-center justify-center"
                                    for="mass_action_select_all_records"
                                >
                                    <input
                                        type="checkbox"
                                        name="mass_action_select_all_records"
                                        id="mass_action_select_all_records"
                                        class="peer hidden"
                                        :checked="['all', 'partial'].includes(applied.massActions.meta.mode)"
                                        @change="selectAll"
                                    >
                                    <span
                                        class="icon-checkbox-outline cursor-pointer rounded-md text-2xl text-gray-400 peer-checked:text-brandColor hover:text-gray-600 dark:text-gray-500"
                                        :class="[
                                            applied.massActions.meta.mode === 'all' ? 'peer-checked:icon-checkbox-select peer-checked:text-brandColor' : (
                                                applied.massActions.meta.mode === 'partial' ? 'peer-checked:icon-checkbox-multiple peer-checked:text-brandColor' : ''
                                            ),
                                        ]"
                                    ></span>
                                </label>
                            </div>

                            <!-- # Column Header -->
                            <div
                                class="flex items-center gap-1.5 cursor-pointer select-none hover:text-gray-900 dark:hover:text-white"
                                @click="sortColumn('id', available, sort)"
                            >
                                <span>#</span>
                                <i
                                    class="text-sm text-gray-800 dark:text-white"
                                    :class="[applied.sort.order === 'asc' ? 'icon-stats-down': 'icon-stats-up']"
                                    v-if="applied.sort.column === 'id'"
                                ></i>
                            </div>

                            <!-- Organization Name Column Header -->
                            <div
                                class="flex items-center gap-1.5 cursor-pointer select-none hover:text-gray-900 dark:hover:text-white"
                                @click="sortColumn('name', available, sort)"
                            >
                                <span class="icon-organization text-base text-gray-400"></span>
                                <span>Organization Name</span>
                                <i
                                    class="text-sm text-gray-800 dark:text-white"
                                    :class="[applied.sort.order === 'asc' ? 'icon-stats-down': 'icon-stats-up']"
                                    v-if="applied.sort.column === 'name'"
                                ></i>
                            </div>

                            <!-- Person Count Column Header -->
                            <div class="flex items-center gap-1.5 select-none">
                                <span class="icon-user text-base text-gray-400"></span>
                                <span>Person Count</span>
                            </div>

                            <!-- Created At Column Header -->
                            <div
                                class="flex items-center gap-1.5 cursor-pointer select-none hover:text-gray-900 dark:hover:text-white"
                                @click="sortColumn('created_at', available, sort)"
                            >
                                <span class="icon-calendar text-base text-gray-400"></span>
                                <span>Created At</span>
                                <i
                                    class="text-sm text-gray-800 dark:text-white"
                                    :class="[applied.sort.order === 'asc' ? 'icon-stats-down': 'icon-stats-up']"
                                    v-if="applied.sort.column === 'created_at'"
                                ></i>
                            </div>

                            <!-- Actions Column Header -->
                            <div class="text-right pr-4 select-none flex items-center justify-end gap-1.5">
                                <span class="icon-settings text-base text-gray-400"></span>
                                <span>Actions</span>
                            </div>
                        </div>

                        <!-- Mobile Sort/Filter Toolbar Header -->
                        <div class="hidden border-b border-gray-200 bg-gray-50 px-4 py-3 text-black dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 max-lg:block w-full max-w-full overflow-hidden">
                            <div class="flex items-center justify-between w-full min-w-0">
                                <!-- Mass Actions Checkbox for Mobile -->
                                <div v-if="available.massActions.length">
                                    <label
                                        class="flex w-max cursor-pointer select-none items-center gap-1"
                                        for="mass_action_select_all_records_mobile"
                                    >
                                        <input
                                            type="checkbox"
                                            name="mass_action_select_all_records_mobile"
                                            id="mass_action_select_all_records_mobile"
                                            class="peer hidden"
                                            :checked="['all', 'partial'].includes(applied.massActions.meta.mode)"
                                            @change="selectAll"
                                        >
                                        <span
                                            class="icon-checkbox-outline cursor-pointer rounded-md text-2xl text-gray-500 peer-checked:text-brandColor"
                                            :class="[
                                                applied.massActions.meta.mode === 'all' ? 'peer-checked:icon-checkbox-select peer-checked:text-brandColor' : (
                                                    applied.massActions.meta.mode === 'partial' ? 'peer-checked:icon-checkbox-multiple peer-checked:text-brandColor' : ''
                                                ),
                                            ]"
                                        ></span>
                                        <span class="text-xs font-medium text-gray-600 dark:text-gray-400">Select All</span>
                                    </label>
                                </div>

                                <!-- Mobile Sort Dropdown -->
                                <div v-if="available.columns.some(column => column.sortable)">
                                    <x-admin::dropdown position="bottom-{{ in_array(app()->getLocale(), ['fa', 'ar']) ? 'left' : 'right' }}">
                                        <x-slot:toggle>
                                            <div class="flex items-center gap-1">
                                                <button
                                                    type="button"
                                                    class="inline-flex w-full max-w-max cursor-pointer appearance-none items-center justify-between gap-x-2 rounded-md border border-gray-300 bg-white px-2.5 py-1.5 text-center text-xs font-medium text-gray-700 shadow-xs hover:border-gray-400 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200"
                                                >
                                                    <span>Sort</span>
                                                    <span class="icon-down-arrow text-xl"></span>
                                                </button>
                                            </div>
                                        </x-slot>

                                        <x-slot:menu>
                                            <x-admin::dropdown.menu.item
                                                v-for="column in available.columns.filter(column => column.sortable && column.visibility)"
                                                @click="sort(column)"
                                            >
                                                <div class="flex items-center gap-2">
                                                    <span v-html="column.label"></span>
                                                    <i
                                                        class="align-text-bottom text-base text-gray-600 dark:text-gray-300"
                                                        :class="[applied.sort.order === 'asc' ? 'icon-stats-down': 'icon-stats-up']"
                                                        v-if="column.index == applied.sort.column"
                                                    ></i>
                                                </div>
                                            </x-admin::dropdown.menu.item>
                                        </x-slot>
                                    </x-admin::dropdown>
                                </div>
                            </div>
                        </div>
                    </template>
                </template>

                <!-- Table Body -->
                <template #body="{
                    isLoading,
                    available,
                    applied,
                    selectAll,
                    sort,
                    performAction
                }">
                    <template v-if="isLoading">
                        <x-admin::shimmer.datagrid.table.body :isMultiRow="true" />
                    </template>

                    <template v-else>
                        <!-- Desktop Row View -->
                        <div
                            class="row grid min-w-[860px] w-full items-center gap-4 border-b border-gray-200 px-4 py-3 transition-colors hover:bg-gray-50/70 dark:border-gray-800 dark:hover:bg-gray-950/60 max-lg:hidden"
                            style="grid-template-columns: 44px 100px minmax(220px, 1.6fr) 150px 200px 140px;"
                            v-for="record in available.records"
                            :key="`org_${record.id}`"
                        >
                            <!-- Mass Action Checkbox -->
                            <div class="flex items-center justify-center">
                                <input
                                    type="checkbox"
                                    :name="`mass_action_select_record_${record.id}`"
                                    :id="`mass_action_select_record_${record.id}`"
                                    :value="record.id"
                                    class="peer hidden"
                                    v-model="applied.massActions.indices"
                                >
                                <label
                                    class="icon-checkbox-outline peer-checked:icon-checkbox-select cursor-pointer rounded-md text-2xl text-gray-400 peer-checked:text-brandColor hover:text-gray-600 dark:text-gray-500"
                                    :for="`mass_action_select_record_${record.id}`"
                                ></label>
                            </div>

                            <!-- Avatar & ID (#) -->
                            <div class="flex items-center gap-3 min-w-0">
                                <!-- Colored Initials Avatar -->
                                <div
                                    class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full border text-xs font-bold tracking-wider shadow-xs"
                                    :class="getAvatarColor(record.name)"
                                >
                                    @{{ getInitials(record.name) }}
                                </div>

                                <!-- ID Number -->
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                    @{{ record.id }}
                                </span>
                            </div>

                            <!-- Organization Name -->
                            <div class="flex items-center min-w-0">
                                <a
                                    :href="findAction(record, 'edit')?.url || '#'"
                                    class="text-sm font-semibold text-gray-900 hover:text-blue-600 dark:text-white dark:hover:text-blue-400 truncate"
                                    :title="record.name"
                                >
                                    @{{ record.name }}
                                </a>
                            </div>

                            <!-- Person Count -->
                            <div class="flex items-center gap-2 min-w-0 text-sm font-medium text-gray-700 dark:text-gray-300">
                                <span class="icon-user text-gray-400 text-base shrink-0"></span>
                                <span>@{{ record.persons_count || 0 }}</span>
                            </div>

                            <!-- Created At -->
                            <div class="flex items-center gap-2 min-w-0 text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-300">
                                <span class="icon-calendar text-gray-400 text-base shrink-0"></span>
                                <span class="truncate">@{{ record.created_at }}</span>
                            </div>

                            <!-- Actions Column -->
                            <div class="flex items-center justify-end gap-2 shrink-0">
                                <!-- View -->
                                <a
                                    v-if="findAction(record, 'view')"
                                    :href="findAction(record, 'view').url"
                                    class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white dark:bg-blue-950/40 dark:text-blue-400 dark:hover:bg-blue-600 dark:hover:text-white transition-colors shadow-xs shrink-0"
                                    title="View"
                                >
                                    <span class="icon-eye text-sm"></span>
                                </a>

                                <!-- Edit -->
                                <a
                                    v-if="findAction(record, 'edit')"
                                    :href="findAction(record, 'edit').url"
                                    class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white dark:bg-blue-950/40 dark:text-blue-400 dark:hover:bg-blue-600 dark:hover:text-white transition-colors shadow-xs shrink-0"
                                    title="Edit"
                                >
                                    <span class="icon-edit text-sm"></span>
                                </a>

                                <!-- Delete -->
                                <button
                                    type="button"
                                    v-if="findAction(record, 'delete')"
                                    @click="performAction(findAction(record, 'delete'))"
                                    class="flex h-8 w-8 items-center justify-center rounded-full bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white dark:bg-rose-950/40 dark:text-rose-400 dark:hover:bg-rose-600 dark:hover:text-white transition-colors shadow-xs shrink-0"
                                    title="Delete"
                                >
                                    <span class="icon-delete text-sm"></span>
                                </button>
                            </div>
                        </div>

                        <!-- Mobile Card View -->
                        <div
                            class="hidden border-b border-gray-200 p-4 transition-colors hover:bg-gray-50/60 dark:border-gray-800 dark:hover:bg-gray-900/60 max-lg:block w-full min-w-0 max-w-full overflow-hidden"
                            v-for="record in available.records"
                            :key="`mobile_org_${record.id}`"
                        >
                            <!-- Top Row: Checkbox + Avatar + Name + ID + Quick Actions -->
                            <div class="flex items-start justify-between gap-2.5 w-full min-w-0">
                                <div class="flex items-center gap-2.5 min-w-0 flex-1">
                                    <!-- Mass Action Checkbox -->
                                    <div class="shrink-0" v-if="available.massActions.length">
                                        <input
                                            type="checkbox"
                                            :name="`mass_action_select_record_mobile_${record.id}`"
                                            :id="`mass_action_select_record_mobile_${record.id}`"
                                            :value="record.id"
                                            class="peer hidden"
                                            v-model="applied.massActions.indices"
                                        >
                                        <label
                                            class="icon-checkbox-outline peer-checked:icon-checkbox-select cursor-pointer rounded-md text-2xl text-gray-400 peer-checked:text-brandColor hover:text-gray-600 dark:text-gray-500"
                                            :for="`mass_action_select_record_mobile_${record.id}`"
                                        ></label>
                                    </div>

                                    <!-- Avatar -->
                                    <div
                                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full border text-xs font-bold tracking-wider shadow-xs"
                                        :class="getAvatarColor(record.name)"
                                    >
                                        @{{ getInitials(record.name) }}
                                    </div>

                                    <!-- Name & ID -->
                                    <div class="flex flex-col min-w-0 flex-1">
                                        <a
                                            :href="findAction(record, 'edit')?.url || '#'"
                                            class="text-sm sm:text-base font-bold text-gray-900 hover:text-blue-600 dark:text-white dark:hover:text-blue-400 truncate block"
                                        >
                                            @{{ record.name }}
                                        </a>
                                        <span class="text-xs text-gray-500 dark:text-gray-400 font-medium">
                                            #@{{ record.id }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex items-center gap-1.5 shrink-0">
                                    <a
                                        v-if="findAction(record, 'view')"
                                        :href="findAction(record, 'view').url"
                                        class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white dark:bg-blue-950/40 dark:text-blue-400"
                                        title="View"
                                    >
                                        <span class="icon-eye text-sm"></span>
                                    </a>
                                    <a
                                        v-if="findAction(record, 'edit')"
                                        :href="findAction(record, 'edit').url"
                                        class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white dark:bg-blue-950/40 dark:text-blue-400"
                                        title="Edit"
                                    >
                                        <span class="icon-edit text-sm"></span>
                                    </a>
                                    <button
                                        type="button"
                                        v-if="findAction(record, 'delete')"
                                        @click="performAction(findAction(record, 'delete'))"
                                        class="flex h-8 w-8 items-center justify-center rounded-full bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white dark:bg-rose-950/40 dark:text-rose-400"
                                        title="Delete"
                                    >
                                        <span class="icon-delete text-sm"></span>
                                    </button>
                                </div>
                            </div>

                            <!-- Bottom Info Row: Person Count & Created At -->
                            <div class="mt-3 flex flex-wrap items-center justify-between gap-2 border-t border-gray-100 pt-3 dark:border-gray-800 text-xs text-gray-600 dark:text-gray-400">
                                <div class="flex items-center gap-1.5">
                                    <span class="icon-user text-gray-400 text-sm"></span>
                                    <span class="font-medium">@{{ record.persons_count || 0 }} persons</span>
                                </div>

                                <div class="flex items-center gap-1.5">
                                    <span class="icon-calendar text-gray-400 text-sm"></span>
                                    <span>@{{ record.created_at }}</span>
                                </div>
                            </div>
                        </div>
                    </template>
                </template>
            </x-admin::datagrid>
        </script>

        <script type="module">
            app.component('v-organizations', {
                template: '#v-organizations-template',

                methods: {
                    getInitials(name) {
                        if (!name) return '??';
                        const clean = name.replace(/\([^)]*\)/g, '').replace(/,\s*(ltd|inc|llc|corp)\.?/gi, '').trim();
                        const words = clean.split(/\s+/).filter(w => w.length > 0 && !/^(ltd|inc|llc|pvt|corp|and|the|of)&?$/i.test(w));
                        if (words.length === 0) return name.substring(0, 2).toUpperCase();
                        if (words.length === 1) {
                            return words[0].substring(0, 1).toUpperCase();
                        }
                        if (words[0].toLowerCase() === 'prestige' && words.length >= 2) {
                            return 'PR';
                        }
                        if (words[words.length - 1].toLowerCase() === 'group') {
                            return (words[0][0] + words[words.length - 1][0]).toUpperCase();
                        }
                        return (words[0][0] + words[1][0]).toUpperCase();
                    },

                    getAvatarColor(name) {
                        const palettes = [
                            'bg-emerald-100 text-emerald-700 border-emerald-200 dark:bg-emerald-950/60 dark:text-emerald-300 dark:border-emerald-800',
                            'bg-amber-100 text-amber-800 border-amber-200 dark:bg-amber-950/60 dark:text-amber-300 dark:border-amber-800',
                            'bg-sky-100 text-sky-700 border-sky-200 dark:bg-sky-950/60 dark:text-sky-300 dark:border-sky-800',
                            'bg-rose-100 text-rose-700 border-rose-200 dark:bg-rose-950/60 dark:text-rose-300 dark:border-rose-800',
                            'bg-purple-100 text-purple-700 border-purple-200 dark:bg-purple-950/60 dark:text-purple-300 dark:border-purple-800',
                            'bg-green-100 text-green-700 border-green-200 dark:bg-green-950/60 dark:text-green-300 dark:border-green-800',
                            'bg-orange-100 text-orange-800 border-orange-200 dark:bg-orange-950/60 dark:text-orange-300 dark:border-orange-800',
                            'bg-indigo-100 text-indigo-700 border-indigo-200 dark:bg-indigo-950/60 dark:text-indigo-300 dark:border-indigo-800',
                            'bg-teal-100 text-teal-800 border-teal-200 dark:bg-teal-950/60 dark:text-teal-300 dark:border-teal-800',
                            'bg-pink-100 text-pink-700 border-pink-200 dark:bg-pink-950/60 dark:text-pink-300 dark:border-pink-800',
                        ];
                        if (!name) return palettes[0];
                        let hash = 0;
                        for (let i = 0; i < name.length; i++) {
                            hash = name.charCodeAt(i) + ((hash << 5) - hash);
                        }
                        const index = Math.abs(hash) % palettes.length;
                        return palettes[index];
                    },

                    findAction(record, actionType) {
                        if (!record || !record.actions) return null;
                        return record.actions.find(action => {
                            const title = (action.title || '').toLowerCase();
                            const icon = (action.icon || '').toLowerCase();
                            return title.includes(actionType.toLowerCase()) || icon.includes(actionType.toLowerCase());
                        });
                    },

                    sortColumn(columnName, available, sort) {
                        const col = available?.columns?.find(c => c.index === columnName);
                        if (col && col.sortable) {
                            sort(col);
                        }
                    },
                },
            });
        </script>
    @endPushOnce
</x-admin::layouts>
