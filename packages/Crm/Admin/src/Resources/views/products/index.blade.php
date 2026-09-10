<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.products.index.title')
    </x-slot>

    <div class="flex flex-col gap-5">
        <!-- Header Card -->
        <div class="flex flex-wrap items-center justify-between gap-4 rounded-2xl border border-gray-200 bg-white p-5 shadow-xs dark:border-gray-800 dark:bg-gray-900">
            <div class="flex items-center gap-4">
                <!-- Squircle Box Icon -->
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-blue-100 text-blue-600 dark:bg-blue-950/60 dark:text-blue-400 shadow-xs">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                        <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                        <line x1="12" y1="22.08" x2="12" y2="12"></line>
                    </svg>
                </div>

                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                        Products
                    </h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        View and manage all your products in one place.
                    </p>
                </div>
            </div>

            <a
                href="{{ route('admin.products.create') }}"
                class="primary-button inline-flex items-center gap-1.5 rounded-xl px-4 py-2 text-sm font-medium shadow-sm transition-all"
                style="background-color: #6366f1; color: #ffffff;"
            >
                <span class="text-lg leading-none font-bold">+</span>
                <span>Create Product</span>
            </a>
        </div>

        <!-- 4 Stat / KPI Cards -->
        <div class="grid grid-cols-2 gap-3.5 sm:gap-4 lg:grid-cols-4">
            <!-- Total Products -->
            <div class="flex items-center gap-4 rounded-2xl border border-gray-200 bg-white p-4 shadow-xs dark:border-gray-800 dark:bg-gray-900">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl border border-sky-100 bg-sky-50 text-sky-600 dark:border-sky-900 dark:bg-sky-950/40 dark:text-sky-400">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                        <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                        <line x1="12" y1="22.08" x2="12" y2="12"></line>
                    </svg>
                </div>
                <div>
                    <div class="text-xs font-semibold text-gray-500 dark:text-gray-400">Total Products</div>
                    <div class="text-xl sm:text-2xl font-bold text-sky-600 dark:text-sky-400">{{ $stats['total_products'] ?? 10 }}</div>
                </div>
            </div>

            <!-- Total Value -->
            <div class="flex items-center gap-4 rounded-2xl border border-gray-200 bg-white p-4 shadow-xs dark:border-gray-800 dark:bg-gray-900">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl border border-emerald-100 bg-emerald-50 text-xl font-bold text-emerald-600 dark:border-emerald-900 dark:bg-emerald-950/40 dark:text-emerald-400">
                    ₹
                </div>
                <div>
                    <div class="text-xs font-semibold text-gray-500 dark:text-gray-400">Total Value</div>
                    <div class="text-base sm:text-xl xl:text-2xl font-bold text-emerald-600 dark:text-emerald-400 whitespace-nowrap">{{ $stats['total_value'] ?? '₹ 9,305,000' }}</div>
                </div>
            </div>

            <!-- In Stock -->
            <div class="flex items-center gap-4 rounded-2xl border border-gray-200 bg-white p-4 shadow-xs dark:border-gray-800 dark:bg-gray-900">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl border border-indigo-100 bg-indigo-50 text-indigo-600 dark:border-indigo-900 dark:bg-indigo-950/40 dark:text-indigo-400">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                        <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                        <line x1="12" y1="22.08" x2="12" y2="12"></line>
                    </svg>
                </div>
                <div>
                    <div class="text-xs font-semibold text-gray-500 dark:text-gray-400">In Stock</div>
                    <div class="text-xl sm:text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ $stats['in_stock'] ?? 7 }}</div>
                </div>
            </div>

            <!-- Low Stock -->
            <div class="flex items-center gap-4 rounded-2xl border border-gray-200 bg-white p-4 shadow-xs dark:border-gray-800 dark:bg-gray-900">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl border border-amber-100 bg-amber-50 text-amber-600 dark:border-amber-900 dark:bg-amber-950/40 dark:text-amber-400">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                        <line x1="12" y1="9" x2="12" y2="13"></line>
                        <line x1="12" y1="17" x2="12.01" y2="17"></line>
                    </svg>
                </div>
                <div>
                    <div class="text-xs font-semibold text-gray-500 dark:text-gray-400">Low Stock</div>
                    <div class="text-xl sm:text-2xl font-bold text-amber-600 dark:text-amber-400">{{ $stats['low_stock'] ?? 3 }}</div>
                </div>
            </div>
        </div>

        <!-- Custom Products DataGrid -->
        <v-products>
            <!-- DataGrid Shimmer -->
            <x-admin::shimmer.datagrid :isMultiRow="true" />
        </v-products>
    </div>

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-products-template"
        >
            <x-admin::datagrid
                :src="route('admin.products.index')"
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
                            class="row grid min-w-[960px] w-full items-center gap-3 border-b border-gray-200 bg-gray-50/80 px-4 py-3 text-xs font-semibold tracking-wide text-gray-600 dark:border-gray-800 dark:bg-gray-900/90 dark:text-gray-300 max-lg:hidden"
                            style="grid-template-columns: 40px 125px minmax(210px, 1.6fr) 130px 75px 75px 75px 110px 110px;"
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

                            <!-- SKU Column Header -->
                            <div
                                class="flex items-center gap-1.5 cursor-pointer select-none hover:text-gray-900 dark:hover:text-white"
                                @click="sortColumn('sku', available, sort)"
                            >
                                <svg class="h-4 w-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M3 5v14M6 5v14M11 5v14M14 5v14M17 5v14M21 5v14"></path>
                                </svg>
                                <span>SKU</span>
                                <i
                                    class="text-sm text-gray-800 dark:text-white"
                                    :class="[applied.sort.order === 'asc' ? 'icon-stats-down': 'icon-stats-up']"
                                    v-if="applied.sort.column === 'sku'"
                                ></i>
                            </div>

                            <!-- Product Name Column Header -->
                            <div
                                class="flex items-center gap-1.5 cursor-pointer select-none hover:text-gray-900 dark:hover:text-white"
                                @click="sortColumn('name', available, sort)"
                            >
                                <svg class="h-4 w-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                    <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                </svg>
                                <span>Product Name</span>
                                <i
                                    class="text-sm text-gray-800 dark:text-white"
                                    :class="[applied.sort.order === 'asc' ? 'icon-stats-down': 'icon-stats-up']"
                                    v-if="applied.sort.column === 'name'"
                                ></i>
                            </div>

                            <!-- Price Column Header -->
                            <div
                                class="flex items-center gap-1.5 cursor-pointer select-none hover:text-gray-900 dark:hover:text-white"
                                @click="sortColumn('price', available, sort)"
                            >
                                <span class="text-sm font-bold text-gray-400">₹</span>
                                <span>Price</span>
                                <i
                                    class="text-sm text-gray-800 dark:text-white"
                                    :class="[applied.sort.order === 'asc' ? 'icon-stats-down': 'icon-stats-up']"
                                    v-if="applied.sort.column === 'price'"
                                ></i>
                            </div>

                            <!-- In Stock Column Header -->
                            <div
                                class="flex items-center gap-1.5 cursor-pointer select-none hover:text-gray-900 dark:hover:text-white"
                                @click="sortColumn('total_in_stock', available, sort)"
                            >
                                <span class="icon-product text-base text-gray-400"></span>
                                <span>In Stock</span>
                                <i
                                    class="text-sm text-gray-800 dark:text-white"
                                    :class="[applied.sort.order === 'asc' ? 'icon-stats-down': 'icon-stats-up']"
                                    v-if="applied.sort.column === 'total_in_stock'"
                                ></i>
                            </div>

                            <!-- Allocated Column Header -->
                            <div
                                class="flex items-center gap-1.5 cursor-pointer select-none hover:text-gray-900 dark:hover:text-white"
                                @click="sortColumn('total_allocated', available, sort)"
                            >
                                <span class="icon-user text-base text-gray-400"></span>
                                <span>Allocated</span>
                                <i
                                    class="text-sm text-gray-800 dark:text-white"
                                    :class="[applied.sort.order === 'asc' ? 'icon-stats-down': 'icon-stats-up']"
                                    v-if="applied.sort.column === 'total_allocated'"
                                ></i>
                            </div>

                            <!-- On Hand Column Header -->
                            <div
                                class="flex items-center gap-1.5 cursor-pointer select-none hover:text-gray-900 dark:hover:text-white"
                                @click="sortColumn('total_on_hand', available, sort)"
                            >
                                <svg class="h-4 w-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                </svg>
                                <span>On Hand</span>
                                <i
                                    class="text-sm text-gray-800 dark:text-white"
                                    :class="[applied.sort.order === 'asc' ? 'icon-stats-down': 'icon-stats-up']"
                                    v-if="applied.sort.column === 'total_on_hand'"
                                ></i>
                            </div>

                            <!-- Tag Column Header -->
                            <div class="flex items-center gap-1.5 select-none">
                                <svg class="h-4 w-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                                    <line x1="7" y1="7" x2="7.01" y2="7"></line>
                                </svg>
                                <span>Tag</span>
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
                                        <span class="text-sm font-semibold">Select All</span>
                                    </label>
                                </div>

                                <!-- Mobile Sort Dropdown -->
                                <div class="relative">
                                    <x-admin::dropdown position="bottom-right">
                                        <x-slot:toggle>
                                            <div class="flex cursor-pointer select-none items-center gap-1 rounded-md border border-gray-300 bg-white px-3 py-1 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200">
                                                <span>Sort</span>
                                                <i class="icon-down-arrow text-xs text-gray-500"></i>
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
                            class="row grid min-w-[960px] w-full items-center gap-3 border-b border-gray-200 px-4 py-3 transition-colors hover:bg-gray-50/70 dark:border-gray-800 dark:hover:bg-gray-950/60 max-lg:hidden"
                            style="grid-template-columns: 40px 125px minmax(210px, 1.6fr) 130px 75px 75px 75px 110px 110px;"
                            v-for="record in available.records"
                            :key="`prod_${record.id}`"
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

                            <!-- SKU Pill Badge -->
                            <div class="flex items-center min-w-0">
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold border tracking-wider"
                                    :class="getSkuColor(record.sku)"
                                >
                                    @{{ record.sku }}
                                </span>
                            </div>

                            <!-- Product Name with Illustrated Thumbnail & Subtitle -->
                            <div class="flex items-center gap-3 min-w-0">
                                <!-- Product Icon Container -->
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-gray-200/80 bg-gray-50/80 shadow-2xs dark:border-gray-800 dark:bg-gray-800/80">
                                    <!-- Store/Retail Icon -->
                                    <template v-if="getProductInfo(record).iconType === 'retail'">
                                        <svg class="h-5 w-5 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                            <path d="M3 9l1 12a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1l1-12"></path>
                                            <path d="M2 9h20M3 9a3 3 0 0 1 6 0 3 3 0 0 1 6 0 3 3 0 0 1 6 0"></path>
                                            <path d="M10 22V14h4v8"></path>
                                        </svg>
                                    </template>
                                    <!-- Apartment/Highrise Icon -->
                                    <template v-else-if="getProductInfo(record).iconType === 'apartment'">
                                        <svg class="h-5 w-5 text-sky-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                            <rect x="4" y="2" width="16" height="20" rx="2"></rect>
                                            <path d="M9 22v-4h6v4M8 6h.01M16 6h.01M8 10h.01M16 10h.01M8 14h.01M16 14h.01"></path>
                                        </svg>
                                    </template>
                                    <!-- Villa Icon -->
                                    <template v-else-if="getProductInfo(record).iconType === 'villa'">
                                        <svg class="h-5 w-5 text-amber-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                            <path d="M3 10.5L12 3l9 7.5V20a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-9.5z"></path>
                                            <path d="M9 22V12h6v10"></path>
                                        </svg>
                                    </template>
                                    <!-- Commercial Office Tower Icon -->
                                    <template v-else-if="getProductInfo(record).iconType === 'office'">
                                        <svg class="h-5 w-5 text-blue-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                            <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                                            <path d="M7 7h3M7 11h3M7 15h3M14 7h3M14 11h3M14 15h3"></path>
                                        </svg>
                                    </template>
                                    <!-- Residence / Condo Icon -->
                                    <template v-else-if="getProductInfo(record).iconType === 'residence'">
                                        <svg class="h-5 w-5 text-indigo-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                            <path d="M4 22h16M7 22V7l5-4 5 4v15"></path>
                                            <path d="M10 10h.01M14 10h.01M10 14h.01M14 14h.01M10 18h.01M14 18h.01"></path>
                                        </svg>
                                    </template>
                                    <!-- Sky Penthouse Icon -->
                                    <template v-else-if="getProductInfo(record).iconType === 'penthouse'">
                                        <svg class="h-5 w-5 text-rose-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                            <path d="M2 12l10-8 10 8M12 4v18M5 12v8a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-8"></path>
                                        </svg>
                                    </template>
                                    <!-- AI Automation Icon -->
                                    <template v-else-if="getProductInfo(record).iconType === 'ai'">
                                        <svg class="h-5 w-5 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                            <path d="M12 2a10 10 0 1 0 10 10H12V2z"></path>
                                            <path d="M12 12L2.1 12.05"></path>
                                            <path d="M12 12a10 10 0 0 1 7.07-7.07"></path>
                                        </svg>
                                    </template>
                                    <!-- Security Shield Icon -->
                                    <template v-else-if="getProductInfo(record).iconType === 'security'">
                                        <svg class="h-5 w-5 text-purple-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                                            <path d="M9 12l2 2 4-4"></path>
                                        </svg>
                                    </template>
                                    <!-- Support SLA Icon -->
                                    <template v-else-if="getProductInfo(record).iconType === 'support'">
                                        <svg class="h-5 w-5 text-orange-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                            <polyline points="14 2 14 8 20 8"></polyline>
                                            <line x1="16" y1="13" x2="8" y2="13"></line>
                                            <line x1="16" y1="17" x2="8" y2="17"></line>
                                        </svg>
                                    </template>
                                    <!-- Database Cluster Icon -->
                                    <template v-else-if="getProductInfo(record).iconType === 'database'">
                                        <svg class="h-5 w-5 text-violet-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                            <ellipse cx="12" cy="5" rx="9" ry="3"></ellipse>
                                            <path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path>
                                            <path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path>
                                        </svg>
                                    </template>
                                    <!-- Default Box Icon -->
                                    <template v-else>
                                        <span class="icon-product text-xl text-blue-600"></span>
                                    </template>
                                </div>

                                <!-- Text: Title & Subtitle -->
                                <div class="min-w-0 flex-1">
                                    <a
                                        :href="findAction(record, 'edit')?.url || '#'"
                                        class="block font-bold text-gray-900 hover:text-blue-600 dark:text-white dark:hover:text-blue-400 text-sm leading-snug"
                                        :title="getProductInfo(record).title"
                                    >
                                        @{{ getProductInfo(record).title }}
                                    </a>
                                    <div
                                        v-if="getProductInfo(record).subtitle"
                                        class="text-xs text-gray-500 dark:text-gray-400 truncate mt-0.5"
                                    >
                                        @{{ getProductInfo(record).subtitle }}
                                    </div>
                                </div>
                            </div>

                            <!-- Price -->
                            <div class="flex items-center min-w-0 font-semibold text-gray-900 dark:text-white text-sm">
                                @{{ record.price }}
                            </div>

                            <!-- In Stock -->
                            <div class="flex items-center min-w-0">
                                <span class="inline-flex items-center justify-center min-w-[28px] h-6 px-2 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400">
                                    @{{ record.total_in_stock || 0 }}
                                </span>
                            </div>

                            <!-- Allocated -->
                            <div class="flex items-center min-w-0 text-sm font-medium text-gray-600 dark:text-gray-300">
                                @{{ record.total_allocated || 0 }}
                            </div>

                            <!-- On Hand -->
                            <div class="flex items-center min-w-0 text-sm font-medium text-gray-600 dark:text-gray-300">
                                @{{ record.total_on_hand || 0 }}
                            </div>

                            <!-- Tag -->
                            <div class="flex items-center min-w-0">
                                <span
                                    v-if="record.tag_name && record.tag_name !== '--'"
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border"
                                    :class="getTagColor(record.tag_name)"
                                >
                                    @{{ record.tag_name }}
                                </span>
                                <span v-else class="text-gray-400 text-xs">--</span>
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center justify-end gap-2 shrink-0 pr-4">
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
                            :key="`mobile_prod_${record.id}`"
                        >
                            <!-- Card Header: Checkbox + SKU + Tag + Actions -->
                            <div class="flex items-center justify-between gap-2 mb-3">
                                <div class="flex items-center gap-2 min-w-0">
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
                                            class="icon-checkbox-outline peer-checked:icon-checkbox-select cursor-pointer rounded-md text-2xl text-gray-400 peer-checked:text-brandColor"
                                            :for="`mass_action_select_record_mobile_${record.id}`"
                                        ></label>
                                    </div>

                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-semibold border tracking-wider"
                                        :class="getSkuColor(record.sku)"
                                    >
                                        @{{ record.sku }}
                                    </span>

                                    <span
                                        v-if="record.tag_name && record.tag_name !== '--'"
                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium border"
                                        :class="getTagColor(record.tag_name)"
                                    >
                                        @{{ record.tag_name }}
                                    </span>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex items-center gap-1.5 shrink-0">
                                    <a
                                        v-if="findAction(record, 'view')"
                                        :href="findAction(record, 'view').url"
                                        class="flex h-7 w-7 items-center justify-center rounded-full bg-blue-50 text-blue-600 dark:bg-blue-950/40 dark:text-blue-400"
                                    >
                                        <span class="icon-eye text-xs"></span>
                                    </a>
                                    <a
                                        v-if="findAction(record, 'edit')"
                                        :href="findAction(record, 'edit').url"
                                        class="flex h-7 w-7 items-center justify-center rounded-full bg-blue-50 text-blue-600 dark:bg-blue-950/40 dark:text-blue-400"
                                    >
                                        <span class="icon-edit text-xs"></span>
                                    </a>
                                    <button
                                        type="button"
                                        v-if="findAction(record, 'delete')"
                                        @click="performAction(findAction(record, 'delete'))"
                                        class="flex h-7 w-7 items-center justify-center rounded-full bg-rose-50 text-rose-600 dark:bg-rose-950/40 dark:text-rose-400"
                                    >
                                        <span class="icon-delete text-xs"></span>
                                    </button>
                                </div>
                            </div>

                            <!-- Product Name & Subtitle -->
                            <div class="mb-3">
                                <a
                                    :href="findAction(record, 'edit')?.url || '#'"
                                    class="block font-bold text-gray-900 hover:text-blue-600 dark:text-white text-base leading-snug"
                                >
                                    @{{ getProductInfo(record).title }}
                                </a>
                                <div
                                    v-if="getProductInfo(record).subtitle"
                                    class="text-xs text-gray-500 dark:text-gray-400 mt-0.5"
                                >
                                    @{{ getProductInfo(record).subtitle }}
                                </div>
                            </div>

                            <!-- Stock & Price Metrics Grid -->
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 pt-2 border-t border-gray-100 dark:border-gray-800 text-xs">
                                <div>
                                    <div class="text-gray-400 font-medium">Price</div>
                                    <div class="font-bold text-gray-900 dark:text-white text-sm">@{{ record.price }}</div>
                                </div>
                                <div>
                                    <div class="text-gray-400 font-medium">In Stock</div>
                                    <div>
                                        <span class="inline-flex items-center justify-center min-w-[24px] h-5 px-1.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400">
                                            @{{ record.total_in_stock || 0 }}
                                        </span>
                                    </div>
                                </div>
                                <div>
                                    <div class="text-gray-400 font-medium">Allocated</div>
                                    <div class="font-medium text-gray-700 dark:text-gray-300">@{{ record.total_allocated || 0 }}</div>
                                </div>
                                <div>
                                    <div class="text-gray-400 font-medium">On Hand</div>
                                    <div class="font-medium text-gray-700 dark:text-gray-300">@{{ record.total_on_hand || 0 }}</div>
                                </div>
                            </div>
                        </div>
                    </template>
                </template>
            </x-admin::datagrid>
        </script>

        <script type="module">
            app.component('v-products', {
                template: '#v-products-template',

                methods: {
                    getProductInfo(record) {
                        const sku = record.sku || '';
                        const name = record.name || '';
                        const specs = {
                            'PROP-RET-006': {
                                title: 'High-Street Retail Boulevard',
                                subtitle: 'Showroom (2,800 sq.ft)',
                                iconType: 'retail'
                            },
                            'PROP-SMT-005': {
                                title: 'Smart City 2BHK',
                                subtitle: 'High-Rise Executive Suite (1,150 sq.ft)',
                                iconType: 'apartment'
                            },
                            'PROP-VIL-004': {
                                title: '5BHK Private Independent',
                                subtitle: 'Gated Estate Villa (7,200 sq.ft)',
                                iconType: 'villa'
                            },
                            'PROP-COM-003': {
                                title: 'Grade-A Commercial',
                                subtitle: 'Office Floor Plate (10,000 sq.ft)',
                                iconType: 'office'
                            },
                            'PROP-APT-002': {
                                title: '3BHK Golf Course',
                                subtitle: 'Luxury Residence (2,350 sq.ft)',
                                iconType: 'residence'
                            },
                            'PROP-SKY-001': {
                                title: '4BHK Sea-Facing',
                                subtitle: 'Sky Penthouse (4,800 sq.ft)',
                                iconType: 'penthouse'
                            },
                            'AI-AUTO-006': {
                                title: 'Custom AI',
                                subtitle: 'Automation & Scoring Module',
                                iconType: 'ai'
                            },
                            'SEC-AUD-005': {
                                title: 'Security & Compliance Audit',
                                subtitle: 'Package',
                                iconType: 'security'
                            },
                            'SUP-247-004': {
                                title: '24/7 Dedicated Priority SLA',
                                subtitle: 'Support',
                                iconType: 'support'
                            },
                            'INF-DB-003': {
                                title: 'Dedicated High-Availability',
                                subtitle: 'DB Cluster',
                                iconType: 'database'
                            }
                        };

                        if (specs[sku]) return specs[sku];

                        const parenIndex = name.indexOf('(');
                        if (parenIndex > 0) {
                            return {
                                title: name.substring(0, parenIndex).trim(),
                                subtitle: name.substring(parenIndex).trim(),
                                iconType: 'box'
                            };
                        }
                        return {
                            title: name,
                            subtitle: record.description ? record.description.substring(0, 45) + '...' : '',
                            iconType: 'box'
                        };
                    },

                    getSkuColor(sku) {
                        const colors = {
                            'PROP-RET-006': 'bg-sky-50 text-sky-700 border-sky-200 dark:bg-sky-950/40 dark:text-sky-300 dark:border-sky-800',
                            'PROP-SMT-005': 'bg-indigo-50 text-indigo-700 border-indigo-200 dark:bg-indigo-950/40 dark:text-indigo-300 dark:border-indigo-800',
                            'PROP-VIL-004': 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-950/60 dark:text-emerald-300 dark:border-emerald-800',
                            'PROP-COM-003': 'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-950/60 dark:text-amber-300 dark:border-amber-800',
                            'PROP-APT-002': 'bg-sky-50 text-sky-700 border-sky-200 dark:bg-sky-950/40 dark:text-sky-300 dark:border-sky-800',
                            'PROP-SKY-001': 'bg-rose-50 text-rose-700 border-rose-200 dark:bg-rose-950/60 dark:text-rose-300 dark:border-rose-800',
                            'AI-AUTO-006':  'bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-950/40 dark:text-blue-300 dark:border-blue-800',
                            'SEC-AUD-005':  'bg-purple-50 text-purple-700 border-purple-200 dark:bg-purple-950/60 dark:text-purple-300 dark:border-purple-800',
                            'SUP-247-004':  'bg-orange-50 text-orange-700 border-orange-200 dark:bg-orange-950/60 dark:text-orange-300 dark:border-orange-800',
                            'INF-DB-003':   'bg-violet-50 text-violet-700 border-violet-200 dark:bg-violet-950/60 dark:text-violet-300 dark:border-violet-800',
                        };
                        return colors[sku] || 'bg-gray-100 text-gray-700 border-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700';
                    },

                    getTagColor(tag) {
                        const colors = {
                            'Showroom':       'bg-sky-50 text-sky-600 border-sky-200 dark:bg-sky-950/40 dark:text-sky-400 dark:border-sky-800',
                            'Residential':    'bg-indigo-50 text-indigo-600 border-indigo-200 dark:bg-indigo-950/40 dark:text-indigo-400 dark:border-indigo-800',
                            'Luxury':         'bg-rose-50 text-rose-600 border-rose-200 dark:bg-rose-950/40 dark:text-rose-400 dark:border-rose-800',
                            'Commercial':     'bg-blue-50 text-blue-600 border-blue-200 dark:bg-blue-950/40 dark:text-blue-400 dark:border-blue-800',
                            'Software':       'bg-sky-50 text-sky-600 border-sky-200 dark:bg-sky-950/40 dark:text-sky-400 dark:border-sky-800',
                            'Service':        'bg-emerald-50 text-emerald-600 border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-400 dark:border-emerald-800',
                            'Support':        'bg-teal-50 text-teal-600 border-teal-200 dark:bg-teal-950/40 dark:text-teal-400 dark:border-teal-800',
                            'Infrastructure': 'bg-purple-50 text-purple-600 border-purple-200 dark:bg-purple-950/40 dark:text-purple-400 dark:border-purple-800',
                        };
                        return colors[tag] || 'bg-gray-50 text-gray-600 border-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-700';
                    },

                    findAction(record, actionType) {
                        if (!record || !record.actions) return null;
                        return record.actions.find(action => {
                            const index = (action.index || '').toLowerCase();
                            const title = (action.title || '').toLowerCase();
                            const icon = (action.icon || '').toLowerCase();
                            return index.includes(actionType.toLowerCase()) || title.includes(actionType.toLowerCase()) || icon.includes(actionType.toLowerCase());
                        });
                    },

                    sortColumn(columnName, available, sort) {
                        const column = available.columns.find(col => col.index === columnName);
                        if (column && column.sortable) {
                            sort(column);
                        }
                    },
                },
            });
        </script>
    @endPushOnce
</x-admin::layouts>
