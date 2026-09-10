<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.contacts.persons.index.title')
    </x-slot>

    <div class="flex flex-col gap-4">
        <!-- Top Header Card -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 rounded-xl border border-gray-200 bg-white p-4 shadow-xs dark:border-gray-800 dark:bg-gray-900">
            <div class="flex items-center gap-3.5">
                <div class="flex h-11 w-11 sm:h-12 sm:w-12 shrink-0 items-center justify-center rounded-xl bg-blue-600 text-white shadow-sm">
                    <span class="icon-user text-2xl"></span>
                </div>

                <div class="flex flex-col">
                    <div class="flex items-center gap-2">
                        <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                            @lang('admin::app.contacts.persons.index.title')
                        </h1>
                    </div>
                    <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">
                        Here are all your contacts and their details.
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2.5 shrink-0">
                <!-- Export Modal -->
                <x-admin::datagrid.export
                    :src="route('admin.contacts.persons.index')"
                    :google-contacts-src="bouncer()->hasPermission('contacts.persons.export_google') ? route('admin.contacts.persons.google_export.store') : null"
                />

                <!-- Create button for person -->
                {!! view_render_event('admin.persons.index.create_button.before') !!}

                @if (bouncer()->hasPermission('contacts.persons.create'))
                    <a
                        href="{{ route('admin.contacts.persons.create') }}"
                        class="primary-button inline-flex items-center gap-1.5 rounded-xl px-4 py-2 text-sm font-medium shadow-sm transition-all"
                    >
                        <span class="text-lg leading-none font-bold">+</span>
                        @lang('admin::app.contacts.persons.index.create-btn')
                    </a>
                @endif

                {!! view_render_event('admin.persons.index.create_button.after') !!}
            </div>
        </div>

        {!! view_render_event('admin.persons.index.datagrid.before') !!}

        <v-persons>
            <!-- Datagrid shimmer -->
            <x-admin::shimmer.datagrid :is-multi-row="true"/>
        </v-persons>

        {!! view_render_event('admin.persons.index.datagrid.after') !!}
    </div>

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-persons-template"
        >
            <x-admin::datagrid
                src="{{ route('admin.contacts.persons.index') }}"
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
                        <div class="row grid min-w-[940px] w-full grid-cols-[44px_minmax(210px,1.2fr)_minmax(230px,1.3fr)_minmax(190px,1fr)_260px] items-center gap-4 border-b border-gray-200 bg-gray-50/80 px-4 py-3 text-xs font-semibold uppercase tracking-wider text-gray-600 dark:border-gray-800 dark:bg-gray-900/90 dark:text-gray-300 max-lg:hidden">
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

                            <!-- Name & Designation Column Header -->
                            <div
                                class="flex items-center gap-1.5 cursor-pointer select-none hover:text-gray-900 dark:hover:text-white"
                                @click="sortColumn('person_name', available, sort)"
                            >
                                <span class="icon-user text-sm text-gray-400"></span>
                                <span>Name &amp; Designation</span>
                                <i
                                    class="text-sm text-gray-800 dark:text-white"
                                    :class="[applied.sort.order === 'asc' ? 'icon-stats-down': 'icon-stats-up']"
                                    v-if="applied.sort.column === 'person_name'"
                                ></i>
                            </div>

                            <!-- Contact Details Column Header -->
                            <div class="flex items-center gap-1.5 select-none">
                                <span class="icon-call text-sm text-gray-400"></span>
                                <span>Contact Details</span>
                            </div>

                            <!-- Organization Column Header -->
                            <div
                                class="flex items-center gap-1.5 cursor-pointer select-none hover:text-gray-900 dark:hover:text-white"
                                @click="sortColumn('organization', available, sort)"
                            >
                                <span class="icon-organization text-sm text-gray-400"></span>
                                <span>Organization</span>
                                <i
                                    class="text-sm text-gray-800 dark:text-white"
                                    :class="[applied.sort.order === 'asc' ? 'icon-stats-down': 'icon-stats-up']"
                                    v-if="applied.sort.column === 'organization'"
                                ></i>
                            </div>

                            <!-- Actions Column Header -->
                            <div class="text-right pr-4 select-none">
                                <span>Actions</span>
                            </div>
                        </div>

                        <!-- Mobile Sort/Filter Toolbar Header -->
                        <div class="hidden border-b border-gray-200 bg-gray-50 px-4 py-3 text-black dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 max-lg:block">
                            <div class="flex items-center justify-between">
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
                            class="row grid min-w-[940px] w-full grid-cols-[44px_minmax(210px,1.2fr)_minmax(230px,1.3fr)_minmax(190px,1fr)_260px] items-center gap-4 border-b border-gray-200 px-4 py-3 transition-colors hover:bg-gray-50/70 dark:border-gray-800 dark:hover:bg-gray-950/60 max-lg:hidden"
                            v-for="record in available.records"
                            :key="`person_${record.id}`"
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

                            <!-- Name & Designation -->
                            <div class="flex items-center gap-3 min-w-0">
                                <!-- Colored Initials Avatar -->
                                <div
                                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full border text-xs font-bold tracking-wider shadow-xs"
                                    :class="getAvatarColor(record.person_name)"
                                >
                                    @{{ getInitials(record.person_name) }}
                                </div>

                                <!-- Name & Role / Job Title -->
                                <div class="flex flex-col min-w-0">
                                    <a
                                        :href="findAction(record, 'view')?.url || '#'"
                                        class="text-sm font-semibold text-gray-900 hover:text-blue-600 dark:text-white dark:hover:text-blue-400 truncate"
                                        :title="record.person_name"
                                    >
                                        @{{ record.person_name }}
                                    </a>
                                    <span class="text-xs text-gray-500 dark:text-gray-400 truncate font-normal" :title="record.job_title || 'Client'">
                                        @{{ record.job_title || 'Client' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Contact Details (Phone & Email Stacked) -->
                            <div class="flex flex-col gap-1 min-w-0">
                                <!-- Phone -->
                                <div class="flex items-center gap-1.5 min-w-0 text-xs sm:text-sm">
                                    <span class="icon-call text-emerald-600 dark:text-emerald-400 text-sm shrink-0"></span>
                                    <a
                                        v-if="record.contact_numbers"
                                        :href="getCallUrl(record.contact_numbers)"
                                        class="font-medium text-gray-700 hover:text-blue-600 dark:text-gray-300 dark:hover:text-blue-400 truncate"
                                        :title="record.contact_numbers"
                                    >
                                        @{{ record.contact_numbers }}
                                    </a>
                                    <span v-else class="text-gray-400 dark:text-gray-500 italic text-xs">No phone</span>
                                </div>

                                <!-- Email -->
                                <div class="flex items-center gap-1.5 min-w-0 text-xs sm:text-sm">
                                    <span class="icon-mail text-blue-500 dark:text-blue-400 text-sm shrink-0"></span>
                                    <a
                                        v-if="record.emails"
                                        :href="`mailto:${record.emails}`"
                                        class="text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400 truncate font-normal"
                                        :title="record.emails"
                                    >
                                        @{{ record.emails }}
                                    </a>
                                    <span v-else class="text-gray-400 dark:text-gray-500 italic text-xs">No email</span>
                                </div>
                            </div>

                            <!-- Organization & Tag / Industry Badge -->
                            <div class="flex flex-col gap-1.5 min-w-0">
                                <div class="flex items-center gap-1.5 min-w-0">
                                    <span class="icon-organization text-gray-400 dark:text-gray-500 text-base shrink-0"></span>
                                    <span
                                        v-if="record.organization"
                                        class="text-sm font-medium text-gray-800 dark:text-gray-200 truncate"
                                        :title="record.organization"
                                    >
                                        @{{ record.organization }}
                                    </span>
                                    <span v-else class="text-xs text-gray-400 dark:text-gray-500 italic">No Organization</span>
                                </div>

                                <div>
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium leading-4"
                                        :class="getIndustryClass(record.organization, record.tag_name)"
                                    >
                                        @{{ getIndustryLabel(record.organization, record.tag_name) }}
                                    </span>
                                </div>
                            </div>

                            <!-- Actions Column -->
                            <div class="flex items-center justify-end gap-1.5 shrink-0">
                                <!-- WhatsApp -->
                                <a
                                    v-if="record.contact_numbers"
                                    :href="getWhatsAppUrl(record.contact_numbers)"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="flex h-8 w-8 items-center justify-center rounded-full bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white dark:bg-emerald-950/40 dark:text-emerald-400 dark:hover:bg-emerald-600 dark:hover:text-white transition-colors shadow-xs shrink-0"
                                    title="Chat on WhatsApp"
                                >
                                    <span class="icon-whatsapp text-sm"></span>
                                </a>

                                <!-- Call -->
                                <a
                                    v-if="record.contact_numbers"
                                    :href="getCallUrl(record.contact_numbers)"
                                    class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white dark:bg-blue-950/40 dark:text-blue-400 dark:hover:bg-blue-600 dark:hover:text-white transition-colors shadow-xs shrink-0"
                                    title="Call"
                                >
                                    <span class="icon-call text-sm"></span>
                                </a>

                                <!-- Email -->
                                <a
                                    v-if="record.emails"
                                    :href="`mailto:${record.emails}`"
                                    class="flex h-8 w-8 items-center justify-center rounded-full bg-sky-50 text-sky-600 hover:bg-sky-600 hover:text-white dark:bg-sky-950/40 dark:text-sky-400 dark:hover:bg-sky-600 dark:hover:text-white transition-colors shadow-xs shrink-0"
                                    title="Send Email"
                                >
                                    <span class="icon-mail text-sm"></span>
                                </a>

                                <!-- View Button -->
                                <a
                                    v-if="findAction(record, 'view')"
                                    :href="findAction(record, 'view').url"
                                    class="inline-flex items-center gap-1 rounded-lg bg-gray-100 px-2.5 py-1.5 text-xs font-semibold text-gray-700 hover:bg-blue-50 hover:text-blue-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-blue-900/40 dark:hover:text-blue-300 transition-colors shrink-0"
                                    title="View Details"
                                >
                                    <span class="icon-eye text-base"></span>
                                    <span>View</span>
                                </a>

                                <!-- Edit Action -->
                                <a
                                    v-if="findAction(record, 'edit')"
                                    :href="findAction(record, 'edit').url"
                                    class="flex h-8 w-8 items-center justify-center rounded-lg text-gray-500 hover:bg-gray-100 hover:text-blue-600 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-blue-400 transition-colors shrink-0"
                                    title="Edit"
                                >
                                    <span class="icon-edit text-base"></span>
                                </a>

                                <!-- Delete Action -->
                                <button
                                    type="button"
                                    v-if="findAction(record, 'delete')"
                                    @click="performAction(findAction(record, 'delete'))"
                                    class="flex h-8 w-8 items-center justify-center rounded-lg text-gray-500 hover:bg-red-50 hover:text-red-600 dark:text-gray-400 dark:hover:bg-red-950/40 dark:hover:text-red-400 transition-colors shrink-0"
                                    title="Delete"
                                >
                                    <span class="icon-delete text-base"></span>
                                </button>
                            </div>
                        </div>

                        <!-- Mobile Card View -->
                        <div
                            class="hidden border-b border-gray-200 p-4 transition-colors hover:bg-gray-50/60 dark:border-gray-800 dark:hover:bg-gray-900/60 max-lg:block w-full min-w-0 max-w-full overflow-hidden"
                            v-for="record in available.records"
                            :key="`mobile_record_${record.id}`"
                        >
                            <!-- Top Row: Checkbox + Avatar + Name/Designation + Quick Actions -->
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
                                        :class="getAvatarColor(record.person_name)"
                                    >
                                        @{{ getInitials(record.person_name) }}
                                    </div>

                                    <!-- Name and Designation -->
                                    <div class="flex flex-col min-w-0 flex-1">
                                        <a
                                            :href="findAction(record, 'view')?.url || '#'"
                                            class="text-sm sm:text-base font-bold text-gray-900 hover:text-blue-600 dark:text-white dark:hover:text-blue-400 truncate block"
                                        >
                                            @{{ record.person_name }}
                                        </a>
                                        <span class="text-xs text-gray-500 dark:text-gray-400 truncate block">
                                            @{{ record.job_title || 'Client' }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Edit / Delete Icons -->
                                <div class="flex items-center gap-0.5 shrink-0">
                                    <a
                                        v-if="findAction(record, 'edit')"
                                        :href="findAction(record, 'edit').url"
                                        class="flex h-8 w-8 items-center justify-center rounded-lg text-gray-400 hover:bg-gray-100 hover:text-blue-600 dark:hover:bg-gray-800"
                                        title="Edit"
                                    >
                                        <span class="icon-edit text-base"></span>
                                    </a>
                                    <button
                                        type="button"
                                        v-if="findAction(record, 'delete')"
                                        @click="performAction(findAction(record, 'delete'))"
                                        class="flex h-8 w-8 items-center justify-center rounded-lg text-gray-400 hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-950/40"
                                        title="Delete"
                                    >
                                        <span class="icon-delete text-base"></span>
                                    </button>
                                </div>
                            </div>

                            <!-- Middle: Organization & Industry Badge -->
                            <div class="mt-2.5 flex flex-wrap items-center gap-2 min-w-0 w-full">
                                <div class="flex items-center gap-1.5 text-xs text-gray-700 dark:text-gray-300 min-w-0 max-w-full">
                                    <span class="icon-organization text-gray-400 text-sm shrink-0"></span>
                                    <span class="font-medium truncate">@{{ record.organization || 'No Organization' }}</span>
                                </div>
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium shrink-0"
                                    :class="getIndustryClass(record.organization, record.tag_name)"
                                >
                                    @{{ getIndustryLabel(record.organization, record.tag_name) }}
                                </span>
                            </div>

                            <!-- Bottom: Contact Info & Action Buttons -->
                            <div class="mt-3 flex flex-col gap-2.5 border-t border-gray-100 pt-3 dark:border-gray-800 w-full min-w-0">
                                <div class="flex flex-col gap-1 text-xs min-w-0 w-full">
                                    <div class="flex items-center gap-1.5 min-w-0 w-full" v-if="record.contact_numbers">
                                        <span class="icon-call text-emerald-600 text-xs shrink-0"></span>
                                        <a :href="getCallUrl(record.contact_numbers)" class="font-medium text-gray-700 dark:text-gray-300 hover:underline truncate block">
                                            @{{ record.contact_numbers }}
                                        </a>
                                    </div>
                                    <div class="flex items-center gap-1.5 min-w-0 w-full" v-if="record.emails">
                                        <span class="icon-mail text-blue-500 text-xs shrink-0"></span>
                                        <a :href="`mailto:${record.emails}`" class="text-gray-500 dark:text-gray-400 hover:underline truncate block">
                                            @{{ record.emails }}
                                        </a>
                                    </div>
                                </div>

                                <!-- Action Buttons Row -->
                                <div class="flex items-center justify-between gap-2 pt-1 w-full min-w-0">
                                    <div class="flex items-center gap-2 shrink-0">
                                        <a
                                            v-if="record.contact_numbers"
                                            :href="getWhatsAppUrl(record.contact_numbers)"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="flex h-8 w-8 items-center justify-center rounded-full bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white dark:bg-emerald-950/40 dark:text-emerald-400"
                                            title="WhatsApp"
                                        >
                                            <span class="icon-whatsapp text-sm"></span>
                                        </a>
                                        <a
                                            v-if="record.contact_numbers"
                                            :href="getCallUrl(record.contact_numbers)"
                                            class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white dark:bg-blue-950/40 dark:text-blue-400"
                                            title="Call"
                                        >
                                            <span class="icon-call text-sm"></span>
                                        </a>
                                        <a
                                            v-if="record.emails"
                                            :href="`mailto:${record.emails}`"
                                            class="flex h-8 w-8 items-center justify-center rounded-full bg-sky-50 text-sky-600 hover:bg-sky-600 hover:text-white dark:bg-sky-950/40 dark:text-sky-400"
                                            title="Email"
                                        >
                                            <span class="icon-mail text-sm"></span>
                                        </a>
                                    </div>

                                    <a
                                        v-if="findAction(record, 'view')"
                                        :href="findAction(record, 'view').url"
                                        class="inline-flex items-center gap-1 rounded-lg bg-gray-100 px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-200 shrink-0"
                                    >
                                        <span class="icon-eye text-sm"></span>
                                        <span>View</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </template>
                </template>
            </x-admin::datagrid>
        </script>

        <script type="module">
            app.component('v-persons', {
                template: '#v-persons-template',

                methods: {
                    getInitials(name) {
                        if (!name) return '??';
                        const parts = name.trim().split(/\s+/).filter(p => !['dr.', 'dr', 'mr.', 'mr', 'ms.', 'ms', 'mrs.', 'mrs'].includes(p.toLowerCase()));
                        if (!parts.length) return name.trim().substring(0, 2).toUpperCase();
                        if (parts.length === 1) {
                            return parts[0].substring(0, 2).toUpperCase();
                        }
                        return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase();
                    },

                    getAvatarColor(name) {
                        const palettes = [
                            'bg-emerald-100 text-emerald-700 border-emerald-200 dark:bg-emerald-950/60 dark:text-emerald-300 dark:border-emerald-800',
                            'bg-amber-100 text-amber-800 border-amber-200 dark:bg-amber-950/60 dark:text-amber-300 dark:border-amber-800',
                            'bg-blue-100 text-blue-700 border-blue-200 dark:bg-blue-950/60 dark:text-blue-300 dark:border-blue-800',
                            'bg-rose-100 text-rose-700 border-rose-200 dark:bg-rose-950/60 dark:text-rose-300 dark:border-rose-800',
                            'bg-purple-100 text-purple-700 border-purple-200 dark:bg-purple-950/60 dark:text-purple-300 dark:border-purple-800',
                            'bg-teal-100 text-teal-800 border-teal-200 dark:bg-teal-950/60 dark:text-teal-300 dark:border-teal-800',
                            'bg-indigo-100 text-indigo-700 border-indigo-200 dark:bg-indigo-950/60 dark:text-indigo-300 dark:border-indigo-800',
                            'bg-orange-100 text-orange-800 border-orange-200 dark:bg-orange-950/60 dark:text-orange-300 dark:border-orange-800',
                            'bg-cyan-100 text-cyan-800 border-cyan-200 dark:bg-cyan-950/60 dark:text-cyan-300 dark:border-cyan-800',
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

                    cleanPhone(phone) {
                        if (!phone) return '';
                        return phone.replace(/[^\d]/g, '');
                    },

                    getWhatsAppUrl(phone) {
                        if (!phone) return '#';
                        let digits = phone.replace(/\D/g, '');
                        if (digits.length === 10) {
                            digits = '91' + digits;
                        }
                        return `https://wa.me/${digits}`;
                    },

                    getCallUrl(phone) {
                        if (!phone) return '#';
                        const cleaned = phone.replace(/[^\d+]/g, '');
                        return `tel:${cleaned}`;
                    },

                    getIndustryLabel(org, tag) {
                        if (tag && tag !== '--') return tag;
                        if (!org) return 'Not Assigned';
                        const lower = org.toLowerCase();
                        if (lower.includes('real estate') || lower.includes('realty') || lower.includes('properties') || lower.includes('group') || lower.includes('brigade')) return 'Real Estate';
                        if (lower.includes('tech') || lower.includes('software') || lower.includes('systems') || lower.includes('digital')) return 'Technology';
                        if (lower.includes('health') || lower.includes('pharma') || lower.includes('hospital') || lower.includes('care')) return 'Healthcare';
                        if (lower.includes('food') || lower.includes('cafe') || lower.includes('restaurant')) return 'Food & Beverage';
                        if (lower.includes('finance') || lower.includes('capital') || lower.includes('bank')) return 'Financial Services';
                        return 'Corporate';
                    },

                    getIndustryClass(org, tag) {
                        const label = this.getIndustryLabel(org, tag);
                        switch (label) {
                            case 'Real Estate':
                                return 'bg-blue-50 text-blue-700 border border-blue-200 dark:bg-blue-950/50 dark:text-blue-300 dark:border-blue-800';
                            case 'Technology':
                            case 'Food & Tech':
                                return 'bg-purple-50 text-purple-700 border border-purple-200 dark:bg-purple-950/50 dark:text-purple-300 dark:border-purple-800';
                            case 'Healthcare':
                                return 'bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950/50 dark:text-emerald-300 dark:border-emerald-800';
                            case 'Food & Beverage':
                                return 'bg-amber-50 text-amber-700 border border-amber-200 dark:bg-amber-950/50 dark:text-amber-300 dark:border-amber-800';
                            case 'Financial Services':
                                return 'bg-teal-50 text-teal-700 border border-teal-200 dark:bg-teal-950/50 dark:text-teal-300 dark:border-teal-800';
                            case 'Not Assigned':
                                return 'bg-gray-100 text-gray-600 border border-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-700';
                            default:
                                return 'bg-slate-100 text-slate-700 border border-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700';
                        }
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
