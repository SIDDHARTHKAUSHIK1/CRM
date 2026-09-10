<x-admin::layouts>
    <!-- Page Title -->
    <x-slot:title>
        @lang('admin::app.settings.title')
    </x-slot>

    <!-- Custom Settings View Component -->
    <v-settings></v-settings>

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-settings-template"
        >
            <div class="flex flex-col gap-5">
                <!-- Top Header Card -->
                <div class="flex flex-wrap items-center justify-between gap-4 rounded-2xl border border-gray-200 bg-white p-5 shadow-xs dark:border-gray-800 dark:bg-gray-900">
                    <div class="flex items-center gap-4">
                        <!-- Squircle Settings Icon -->
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-blue-100 text-blue-600 dark:bg-blue-950/60 dark:text-blue-400 shadow-xs">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="3"></circle>
                                <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                            </svg>
                        </div>

                        <div>
                            <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                                Settings
                            </h1>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Manage your CRM in one place.
                            </p>
                        </div>
                    </div>

                    <!-- Actions: Search Input & Red Logout Button -->
                    <div class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
                        <!-- Search Input -->
                        <div class="relative w-full sm:w-72">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                </svg>
                            </div>
                            <input
                                type="text"
                                v-model="searchQuery"
                                placeholder="Search settings..."
                                class="block w-full rounded-xl border border-gray-200 bg-gray-50/50 py-2.5 pl-9 pr-9 text-sm text-gray-900 transition-all placeholder:text-gray-400 focus:border-blue-500 focus:bg-white focus:outline-hidden dark:border-gray-800 dark:bg-gray-900 dark:text-white dark:focus:border-blue-500"
                            >
                            <button
                                v-if="searchQuery"
                                @click="searchQuery = ''"
                                class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600"
                            >
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                            </button>
                        </div>

                        <!-- Dark Mode Toggle Button -->
                        <button
                            type="button"
                            @click="toggleDarkMode"
                            class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-3.5 py-2.5 text-sm font-semibold text-gray-700 shadow-xs transition-all duration-200 hover:bg-gray-50 hover:text-gray-900 active:scale-95 cursor-pointer dark:border-gray-800 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700 dark:hover:text-white shrink-0"
                            :title="isDarkMode ? 'Switch to Light Mode' : 'Switch to Dark Mode'"
                        >
                            <template v-if="isDarkMode">
                                <svg class="h-4 w-4 text-amber-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="5"></circle>
                                    <line x1="12" y1="1" x2="12" y2="3"></line>
                                    <line x1="12" y1="21" x2="12" y2="23"></line>
                                    <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
                                    <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
                                    <line x1="1" y1="12" x2="3" y2="12"></line>
                                    <line x1="21" y1="12" x2="23" y2="12"></line>
                                    <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
                                    <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
                                </svg>
                                <span>Light Mode</span>
                            </template>
                            <template v-else>
                                <svg class="h-4 w-4 text-slate-700 dark:text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                                </svg>
                                <span>Dark Mode</span>
                            </template>
                        </button>

                        <!-- Red Logout Button -->
                        <form
                            method="POST"
                            action="{{ route('admin.session.destroy') }}"
                            id="settingsHeaderLogoutForm"
                            class="shrink-0"
                            onsubmit="return confirm('Are you sure you want to log out?');"
                        >
                            @csrf
                            @method('DELETE')
                            <button
                                type="submit"
                                class="inline-flex items-center gap-2 rounded-xl bg-red-600 px-4 py-2.5 text-sm font-bold text-white shadow-xs transition-all duration-200 hover:bg-red-700 hover:shadow-md active:scale-95 cursor-pointer dark:bg-red-600 dark:hover:bg-red-700"
                                title="Log out of your account"
                            >
                                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Empty State When No Search Matches -->
                <div
                    v-if="filteredCategories.length === 0"
                    class="flex flex-col items-center justify-center rounded-2xl border border-gray-200 bg-white p-12 text-center shadow-xs dark:border-gray-800 dark:bg-gray-900"
                >
                    <div class="flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 text-gray-400 dark:bg-gray-800 dark:text-gray-500 mb-3">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </div>
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white">No settings found</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        No settings match your search term "@{{ searchQuery }}". Try a different keyword.
                    </p>
                </div>

                <!-- Setting Category Sections -->
                <div
                    v-for="category in filteredCategories"
                    :key="category.id"
                    class="flex flex-col gap-4 rounded-2xl border border-gray-200 bg-white p-5 shadow-xs dark:border-gray-800 dark:bg-gray-900"
                >
                    <!-- Category Header Banner -->
                    <div class="flex items-center gap-3.5 rounded-xl bg-gray-50/70 p-3.5 dark:bg-gray-800/40">
                        <!-- Category Squircle Icon -->
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-600 dark:bg-blue-950/60 dark:text-blue-400 shadow-2xs">
                            <!-- Users Category Icon -->
                            <template v-if="category.id === 'users'">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                </svg>
                            </template>
                            <!-- Lead Management Icon -->
                            <template v-else-if="category.id === 'leads'">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <circle cx="12" cy="12" r="6"></circle>
                                    <circle cx="12" cy="12" r="2"></circle>
                                </svg>
                            </template>
                            <!-- Inventory Icon -->
                            <template v-else-if="category.id === 'inventory'">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                    <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                    <line x1="12" y1="22.08" x2="12" y2="12"></line>
                                </svg>
                            </template>
                            <!-- Automation Icon -->
                            <template v-else-if="category.id === 'automation'">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                                </svg>
                            </template>
                            <!-- Other Settings Icon -->
                            <template v-else>
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="3"></circle>
                                    <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                                </svg>
                            </template>
                        </div>

                        <div>
                            <h2 class="text-base font-bold text-gray-900 dark:text-white">
                                @{{ category.title }}
                            </h2>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                @{{ category.subtitle }}
                            </p>
                        </div>
                    </div>

                    <!-- Category Items Grid -->
                    <div
                        class="grid gap-4"
                        :class="getGridClass(category.id)"
                    >
                        <a
                            v-for="item in category.items"
                            :key="item.name"
                            :href="item.url"
                            class="group relative flex items-center justify-between gap-3.5 rounded-2xl border border-gray-200/90 bg-white p-4 transition-all duration-200 hover:border-blue-300 hover:shadow-md hover:-translate-y-0.5 dark:border-gray-800 dark:bg-gray-900/90 dark:hover:border-blue-700 min-h-[92px]"
                        >
                            <div class="flex items-center gap-3.5 min-w-0 flex-1">
                                <!-- Card Squircle Icon -->
                                <div
                                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border transition-transform duration-200 group-hover:scale-105 shadow-2xs"
                                    :class="item.iconClass"
                                >
                                    <!-- Groups -->
                                    <template v-if="item.iconType === 'groups'">
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="9" cy="7" r="4"></circle>
                                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                        </svg>
                                    </template>
                                    <!-- Roles -->
                                    <template v-else-if="item.iconType === 'roles'">
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                                        </svg>
                                    </template>
                                    <!-- Users -->
                                    <template v-else-if="item.iconType === 'users'">
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="12" cy="7" r="4"></circle>
                                        </svg>
                                    </template>
                                    <!-- Pipelines -->
                                    <template v-else-if="item.iconType === 'pipelines'">
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                                        </svg>
                                    </template>
                                    <!-- Sources -->
                                    <template v-else-if="item.iconType === 'sources'">
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="18" cy="5" r="3"></circle>
                                            <circle cx="6" cy="12" r="3"></circle>
                                            <circle cx="18" cy="19" r="3"></circle>
                                            <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line>
                                            <line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line>
                                        </svg>
                                    </template>
                                    <!-- Types -->
                                    <template v-else-if="item.iconType === 'types'">
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                                            <line x1="7" y1="7" x2="7.01" y2="7"></line>
                                        </svg>
                                    </template>
                                    <!-- Warehouses -->
                                    <template v-else-if="item.iconType === 'warehouses'">
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                        </svg>
                                    </template>
                                    <!-- Attributes -->
                                    <template v-else-if="item.iconType === 'attributes'">
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <line x1="4" y1="21" x2="4" y2="14"></line>
                                            <line x1="4" y1="10" x2="4" y2="3"></line>
                                            <line x1="12" y1="21" x2="12" y2="12"></line>
                                            <line x1="12" y1="8" x2="12" y2="3"></line>
                                            <line x1="20" y1="21" x2="20" y2="16"></line>
                                            <line x1="20" y1="12" x2="20" y2="3"></line>
                                            <line x1="1" y1="14" x2="7" y2="14"></line>
                                            <line x1="9" y1="8" x2="15" y2="8"></line>
                                            <line x1="17" y1="16" x2="23" y2="16"></line>
                                        </svg>
                                    </template>
                                    <!-- Email Templates -->
                                    <template v-else-if="item.iconType === 'email-templates'">
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                            <polyline points="22,6 12,13 2,6"></polyline>
                                        </svg>
                                    </template>
                                    <!-- Events -->
                                    <template v-else-if="item.iconType === 'events'">
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                            <line x1="16" y1="2" x2="16" y2="6"></line>
                                            <line x1="8" y1="2" x2="8" y2="6"></line>
                                            <line x1="3" y1="10" x2="21" y2="10"></line>
                                        </svg>
                                    </template>
                                    <!-- Campaigns -->
                                    <template v-else-if="item.iconType === 'campaigns'">
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                                            <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                                        </svg>
                                    </template>
                                    <!-- Webhooks -->
                                    <template v-else-if="item.iconType === 'webhooks'">
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                                            <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                                        </svg>
                                    </template>
                                    <!-- Workflows -->
                                    <template v-else-if="item.iconType === 'workflows'">
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <rect x="3" y="3" width="7" height="7"></rect>
                                            <rect x="14" y="3" width="7" height="7"></rect>
                                            <rect x="14" y="14" width="7" height="7"></rect>
                                            <rect x="3" y="14" width="7" height="7"></rect>
                                        </svg>
                                    </template>
                                    <!-- Data Transfer -->
                                    <template v-else-if="item.iconType === 'data-transfer'">
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                            <polyline points="7 10 12 15 17 10"></polyline>
                                            <line x1="12" y1="15" x2="12" y2="3"></line>
                                        </svg>
                                    </template>
                                    <!-- Web Forms -->
                                    <template v-else-if="item.iconType === 'web-forms'">
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                            <polyline points="14 2 14 8 20 8"></polyline>
                                            <line x1="16" y1="13" x2="8" y2="13"></line>
                                            <line x1="16" y1="17" x2="8" y2="17"></line>
                                            <polyline points="10 9 9 9 8 9"></polyline>
                                        </svg>
                                    </template>
                                    <!-- Tags -->
                                    <template v-else-if="item.iconType === 'tags'">
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                                            <line x1="7" y1="7" x2="7.01" y2="7"></line>
                                        </svg>
                                    </template>
                                    <!-- Google Contacts -->
                                    <template v-else-if="item.iconType === 'google-contacts'">
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="9" cy="7" r="4"></circle>
                                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                        </svg>
                                    </template>
                                    <!-- Default -->
                                    <template v-else>
                                        <span class="icon-settings text-xl"></span>
                                    </template>
                                </div>

                                <!-- Card Text -->
                                <div class="min-w-0 flex-1">
                                    <h3 class="text-sm font-bold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                        @{{ item.name }}
                                    </h3>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 line-clamp-2 leading-relaxed">
                                        @{{ item.info }}
                                    </p>
                                </div>
                            </div>

                            <!-- Right Chevron Arrow -->
                            <div class="flex items-center text-gray-300 group-hover:text-blue-600 dark:text-gray-600 dark:group-hover:text-blue-400 transition-colors shrink-0 pl-1">
                                <svg class="h-5 w-5 transition-transform duration-200 group-hover:translate-x-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Theme & Appearance Card in Settings -->
                <div class="flex flex-wrap items-center justify-between gap-4 rounded-2xl border border-indigo-200/90 bg-indigo-50/50 p-5 shadow-xs dark:border-indigo-950/60 dark:bg-indigo-950/20">
                    <div class="flex items-center gap-3.5">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-indigo-100 text-indigo-600 dark:bg-indigo-900/50 dark:text-indigo-400 shadow-2xs border border-indigo-200/60 dark:border-indigo-800/60">
                            <svg v-if="isDarkMode" class="h-5 w-5 text-amber-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="5"></circle>
                                <line x1="12" y1="1" x2="12" y2="3"></line>
                                <line x1="12" y1="21" x2="12" y2="23"></line>
                                <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
                                <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
                                <line x1="1" y1="12" x2="3" y2="12"></line>
                                <line x1="21" y1="12" x2="23" y2="12"></line>
                                <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
                                <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
                            </svg>
                            <svg v-else class="h-5 w-5 text-indigo-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-sm font-bold text-gray-900 dark:text-white">Theme &amp; Appearance</h3>
                                <span
                                    class="rounded-full px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider"
                                    :class="isDarkMode ? 'bg-indigo-950 text-indigo-300 border border-indigo-800' : 'bg-indigo-100 text-indigo-700 border border-indigo-200'"
                                >
                                    @{{ isDarkMode ? 'Dark Mode Active' : 'Light Mode Active' }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Toggle between crisp Light Mode and eye-friendly Dark Mode interface.</p>
                        </div>
                    </div>

                    <button
                        type="button"
                        @click="toggleDarkMode"
                        class="inline-flex items-center gap-2.5 rounded-xl border border-indigo-200 bg-white px-4 py-2.5 text-sm font-bold text-indigo-700 shadow-xs transition-all duration-200 hover:bg-indigo-50 active:scale-95 cursor-pointer dark:border-indigo-800 dark:bg-gray-800 dark:text-indigo-300 dark:hover:bg-gray-700"
                    >
                        <template v-if="isDarkMode">
                            <svg class="h-4 w-4 text-amber-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="5"></circle>
                                <line x1="12" y1="1" x2="12" y2="3"></line>
                                <line x1="12" y1="21" x2="12" y2="23"></line>
                                <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
                                <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
                                <line x1="1" y1="12" x2="3" y2="12"></line>
                                <line x1="21" y1="12" x2="23" y2="12"></line>
                                <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
                                <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
                            </svg>
                            <span>Switch to Light Mode</span>
                        </template>
                        <template v-else>
                            <svg class="h-4 w-4 text-indigo-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                            </svg>
                            <span>Switch to Dark Mode</span>
                        </template>
                    </button>
                </div>

                <!-- Session & Sign Out Card at bottom of Settings -->
                <div class="flex flex-wrap items-center justify-between gap-4 rounded-2xl border border-red-200 bg-red-50/50 p-5 shadow-xs dark:border-red-950/60 dark:bg-red-950/20">
                    <div class="flex items-center gap-3.5">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-red-100 text-red-600 dark:bg-red-900/50 dark:text-red-400 shadow-2xs">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-gray-900 dark:text-white">Sign Out of Session</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">End your current CRM session securely across all devices.</p>
                        </div>
                    </div>

                    <form
                        method="POST"
                        action="{{ route('admin.session.destroy') }}"
                        id="settingsBottomLogoutForm"
                        onsubmit="return confirm('Are you sure you want to log out?');"
                    >
                        @csrf
                        @method('DELETE')
                        <button
                            type="submit"
                            class="inline-flex items-center gap-2 rounded-xl bg-red-600 px-4 py-2.5 text-sm font-bold text-white shadow-xs transition-all duration-200 hover:bg-red-700 hover:shadow-md active:scale-95 cursor-pointer dark:bg-red-600 dark:hover:bg-red-700"
                        >
                            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </script>

        <script type="module">
            app.component('v-settings', {
                template: '#v-settings-template',

                data() {
                    return {
                        isDarkMode: {{ request()->cookie('dark_mode') ? 'true' : 'false' }},
                        logo: "{{ vite()->asset('images/logo.svg') }}",
                        dark_logo: "{{ vite()->asset('images/dark-logo.svg') }}",
                        searchQuery: '',
                        categories: [
                            {
                                id: 'users',
                                title: 'Users & Permissions',
                                subtitle: 'Manage who can access your CRM and what they can do.',
                                items: [
                                    {
                                        name: 'Groups',
                                        info: 'Create and manage groups of users (e.g., Sales, Support, etc.).',
                                        url: '{{ route('admin.settings.groups.index') }}',
                                        iconType: 'groups',
                                        iconClass: 'bg-purple-50 text-purple-600 border-purple-100 dark:bg-purple-950/40 dark:border-purple-900',
                                    },
                                    {
                                        name: 'Roles',
                                        info: 'Set permissions for different roles (e.g., Admin, Manager, Sales).',
                                        url: '{{ route('admin.settings.roles.index') }}',
                                        iconType: 'roles',
                                        iconClass: 'bg-blue-50 text-blue-600 border-blue-100 dark:bg-blue-950/40 dark:border-blue-900',
                                    },
                                    {
                                        name: 'Users',
                                        info: 'Add, edit or remove users in your CRM.',
                                        url: '{{ route('admin.settings.users.index') }}',
                                        iconType: 'users',
                                        iconClass: 'bg-emerald-50 text-emerald-600 border-emerald-100 dark:bg-emerald-950/40 dark:border-emerald-900',
                                    },
                                ],
                            },
                            {
                                id: 'leads',
                                title: 'Lead Management',
                                subtitle: 'Manage how your leads are captured and organized.',
                                items: [
                                    {
                                        name: 'Pipelines',
                                        info: 'Create and manage your sales pipelines (e.g., New -> Contacted -> Won).',
                                        url: '{{ route('admin.settings.pipelines.index') }}',
                                        iconType: 'pipelines',
                                        iconClass: 'bg-purple-50 text-purple-600 border-purple-100 dark:bg-purple-950/40 dark:border-purple-900',
                                    },
                                    {
                                        name: 'Sources',
                                        info: 'Track where your leads come from (e.g., Website, WhatsApp, Referral).',
                                        url: '{{ route('admin.settings.sources.index') }}',
                                        iconType: 'sources',
                                        iconClass: 'bg-emerald-50 text-emerald-600 border-emerald-100 dark:bg-emerald-950/40 dark:border-emerald-900',
                                    },
                                    {
                                        name: 'Types',
                                        info: 'Organize leads by type (e.g., Hot, Warm, Cold).',
                                        url: '{{ route('admin.settings.types.index') }}',
                                        iconType: 'types',
                                        iconClass: 'bg-orange-50 text-orange-600 border-orange-100 dark:bg-orange-950/40 dark:border-orange-900',
                                    },
                                ],
                            },
                            {
                                id: 'inventory',
                                title: 'Inventory',
                                subtitle: 'Manage all your inventory related settings in the CRM.',
                                items: [
                                    {
                                        name: 'Warehouses',
                                        info: 'Add, edit or delete warehouses where your products are stored.',
                                        url: '{{ route('admin.settings.warehouses.index') }}',
                                        iconType: 'warehouses',
                                        iconClass: 'bg-blue-50 text-blue-600 border-blue-100 dark:bg-blue-950/40 dark:border-blue-900',
                                    },
                                ],
                            },
                            {
                                id: 'automation',
                                title: 'Automation',
                                subtitle: 'Set up smart actions to save time and work faster.',
                                items: [
                                    {
                                        name: 'Attributes',
                                        info: 'Add, edit or delete attributes (e.g., Size, Color, Brand).',
                                        url: '{{ route('admin.settings.attributes.index') }}',
                                        iconType: 'attributes',
                                        iconClass: 'bg-pink-50 text-pink-600 border-pink-100 dark:bg-pink-950/40 dark:border-pink-900',
                                    },
                                    {
                                        name: 'Email Templates',
                                        info: 'Create and manage email templates for your campaigns.',
                                        url: '{{ route('admin.settings.email_templates.index') }}',
                                        iconType: 'email-templates',
                                        iconClass: 'bg-sky-50 text-sky-600 border-sky-100 dark:bg-sky-950/40 dark:border-sky-900',
                                    },
                                    {
                                        name: 'Events',
                                        info: 'Add, edit or delete events (e.g., Follow-up, Meeting).',
                                        url: '{{ route('admin.settings.marketing.events.index') }}',
                                        iconType: 'events',
                                        iconClass: 'bg-emerald-50 text-emerald-600 border-emerald-100 dark:bg-emerald-950/40 dark:border-emerald-900',
                                    },
                                    {
                                        name: 'Campaigns',
                                        info: 'Create and manage marketing campaigns.',
                                        url: '{{ route('admin.settings.marketing.campaigns.index') }}',
                                        iconType: 'campaigns',
                                        iconClass: 'bg-purple-50 text-purple-600 border-purple-100 dark:bg-purple-950/40 dark:border-purple-900',
                                    },
                                    {
                                        name: 'Webhooks',
                                        info: 'Connect with external apps and services.',
                                        url: '{{ route('admin.settings.webhooks.index') }}',
                                        iconType: 'webhooks',
                                        iconClass: 'bg-teal-50 text-teal-600 border-teal-100 dark:bg-teal-950/40 dark:border-teal-900',
                                    },
                                    {
                                        name: 'Workflows',
                                        info: 'Automate your processes (e.g., Lead assignment, Follow-ups).',
                                        url: '{{ route('admin.settings.workflows.index') }}',
                                        iconType: 'workflows',
                                        iconClass: 'bg-purple-50 text-purple-600 border-purple-100 dark:bg-purple-950/40 dark:border-purple-900',
                                    },
                                    {
                                        name: 'Data Transfer',
                                        info: 'Move data between systems and keep everything in sync.',
                                        url: '{{ route('admin.settings.data_transfer.imports.index') }}',
                                        iconType: 'data-transfer',
                                        iconClass: 'bg-sky-50 text-sky-600 border-sky-100 dark:bg-sky-950/40 dark:border-sky-900',
                                    },
                                ],
                            },
                            {
                                id: 'other',
                                title: 'Other Settings',
                                subtitle: 'Manage all your extra settings in the CRM.',
                                items: [
                                    {
                                        name: 'Web Forms',
                                        info: 'Create forms to capture leads from your website.',
                                        url: '{{ route('admin.settings.web_forms.index') }}',
                                        iconType: 'web-forms',
                                        iconClass: 'bg-pink-50 text-pink-600 border-pink-100 dark:bg-pink-950/40 dark:border-pink-900',
                                    },
                                    {
                                        name: 'Tags',
                                        info: 'Add, edit or delete tags to organize your data.',
                                        url: '{{ route('admin.settings.tags.index') }}',
                                        iconType: 'tags',
                                        iconClass: 'bg-amber-50 text-amber-600 border-amber-100 dark:bg-amber-950/40 dark:border-amber-900',
                                    },
                                    {
                                        name: 'Google Contacts',
                                        info: 'Connect your Google account and import contacts to your CRM.',
                                        url: '{{ route('admin.settings.google_contacts.index') }}',
                                        iconType: 'google-contacts',
                                        iconClass: 'bg-blue-50 text-blue-600 border-blue-100 dark:bg-blue-950/40 dark:border-blue-900',
                                    },
                                ],
                            },
                        ],
                    };
                },

                computed: {
                    filteredCategories() {
                        const q = (this.searchQuery || '').trim().toLowerCase();
                        if (!q) {
                            return this.categories;
                        }

                        return this.categories
                            .map(category => {
                                const matchingItems = category.items.filter(item => {
                                    return item.name.toLowerCase().includes(q) ||
                                           item.info.toLowerCase().includes(q) ||
                                           category.title.toLowerCase().includes(q);
                                });

                                if (matchingItems.length > 0) {
                                    return {
                                        ...category,
                                        items: matchingItems,
                                    };
                                }
                                return null;
                            })
                            .filter(Boolean);
                    },
                },

                mounted() {
                    this.isDarkMode = document.documentElement.classList.contains('dark');
                },

                methods: {
                    toggleDarkMode() {
                        this.isDarkMode = !this.isDarkMode;
                        const val = this.isDarkMode ? 1 : 0;
                        const expiryDate = new Date();
                        expiryDate.setMonth(expiryDate.getMonth() + 1);
                        document.cookie = 'dark_mode=' + val + '; path=/; expires=' + expiryDate.toGMTString();
                        document.documentElement.classList.toggle('dark', this.isDarkMode);

                        if (this.isDarkMode) {
                            this.$emitter?.emit('change-theme', 'dark');
                            const logoImg = document.getElementById('logo-image');
                            if (logoImg) logoImg.src = this.dark_logo;
                        } else {
                            this.$emitter?.emit('change-theme', 'light');
                            const logoImg = document.getElementById('logo-image');
                            if (logoImg) logoImg.src = this.logo;
                        }
                    },

                    getGridClass(categoryId) {
                        if (categoryId === 'inventory') {
                            return 'grid-cols-1';
                        }
                        if (categoryId === 'automation') {
                            return 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-4';
                        }
                        return 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3';
                    },
                },
            });
        </script>
    @endPushOnce
</x-admin::layouts>
