<x-admin::layouts>
    <!-- Title of the page. -->
    <x-slot:title>
        @lang('admin::app.configuration.index.title')
    </x-slot>

    {!! view_render_event('admin.configuration.index.header.before') !!}

    <!-- Modern Header Card -->
    <div class="flex items-center justify-between rounded-2xl border border-slate-200/90 bg-white px-5 py-4 text-sm shadow-xs dark:border-gray-800 dark:bg-gray-900">
        <div class="flex items-center gap-3.5">
            <!-- Purple Squircle Icon Container -->
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-purple-100 text-purple-600 dark:bg-purple-950/70 dark:text-purple-400">
                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M19.14 12.94c.04-.3.06-.61.06-.94 0-.32-.02-.64-.07-.94l2.03-1.58c.18-.14.23-.41.12-.61l-1.92-3.32c-.12-.22-.37-.29-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54c-.04-.24-.24-.41-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.05.3-.09.63-.09.94s.02.64.07.94l-2.03 1.58c-.18.14-.23.41-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58zM12 15.6c-1.98 0-3.6-1.62-3.6-3.6s1.62-3.6 3.6-3.6 3.6 1.62 3.6 3.6-1.62 3.6-3.6 3.6z"/>
                </svg>
            </div>

            <div class="flex flex-col">
                <span class="text-xs font-semibold text-purple-600 dark:text-purple-400">
                    Settings
                </span>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                    @lang('admin::app.configuration.index.title')
                </h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-normal mt-0.5">
                    Manage your CRM settings and application preferences.
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2.5">
            <!-- Dark Mode Toggle Button -->
            <button
                type="button"
                onclick="toggleConfigDarkMode()"
                id="configDarkModeBtn"
                class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm font-semibold text-slate-700 shadow-xs transition-all duration-200 hover:bg-slate-50 hover:text-slate-900 active:scale-95 cursor-pointer dark:border-gray-800 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700 shrink-0"
                title="Toggle Dark / Light Mode"
            >
                <span id="configDarkIcon" class="flex items-center gap-2">
                    @if (request()->cookie('dark_mode'))
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
                    @else
                        <svg class="h-4 w-4 text-slate-700 dark:text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                        </svg>
                        <span>Dark Mode</span>
                    @endif
                </span>
            </button>

            <!-- Red Logout Button -->
            <form
                method="POST"
                action="{{ route('admin.session.destroy') }}"
                onsubmit="return confirm('Are you sure you want to log out?');"
                class="shrink-0"
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

    {!! view_render_event('admin.configuration.index.header.after') !!}

    {!! view_render_event('admin.configuration.index.content.before') !!}

    @php
        $categoryConfig = [
            'general' => [
                'title' => 'General Configuration',
                'subtitle' => 'Update your general settings here.',
                'icon_bg' => 'bg-blue-100 text-blue-600 dark:bg-blue-950/70 dark:text-blue-400',
                'icon' => 'gear',
            ],
            'email' => [
                'title' => 'Email Settings',
                'subtitle' => 'Configure how your emails work in the CRM.',
                'icon_bg' => 'bg-blue-100 text-blue-600 dark:bg-blue-950/70 dark:text-blue-400',
                'icon' => 'envelope',
            ],
        ];

        $itemConfig = [
            'general.general' => [
                'title' => 'General Settings',
                'subtitle' => 'Update your company details, preferences and basic settings.',
                'card_bg' => 'bg-[#f0f7ff] border-blue-100/90 hover:border-blue-200 dark:bg-blue-950/20 dark:border-blue-900/50',
                'icon_bg' => 'bg-blue-100 text-blue-600 dark:bg-blue-900/60 dark:text-blue-300',
                'btn_bg' => 'bg-blue-100/70 text-blue-600 group-hover:bg-blue-600 group-hover:text-white dark:bg-blue-900/70 dark:text-blue-300',
                'icon' => 'gear',
            ],
            'general.settings' => [
                'title' => 'Settings',
                'subtitle' => 'Manage system settings like notifications, date format, etc.',
                'card_bg' => 'bg-[#f0fdf4] border-emerald-100/90 hover:border-emerald-200 dark:bg-emerald-950/20 dark:border-emerald-900/50',
                'icon_bg' => 'bg-emerald-100 text-emerald-600 dark:bg-emerald-900/60 dark:text-emerald-300',
                'btn_bg' => 'bg-emerald-100/70 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white dark:bg-emerald-900/70 dark:text-emerald-300',
                'icon' => 'sliders',
            ],
            'general.magic_ai' => [
                'title' => 'Magic AI',
                'subtitle' => 'Configure AI settings for smart suggestions and assistance.',
                'card_bg' => 'bg-[#faf5ff] border-purple-100/90 hover:border-purple-200 dark:bg-purple-950/20 dark:border-purple-900/50',
                'icon_bg' => 'bg-purple-100 text-purple-600 dark:bg-purple-900/60 dark:text-purple-300',
                'btn_bg' => 'bg-purple-100/70 text-purple-600 group-hover:bg-purple-600 group-hover:text-white dark:bg-purple-900/70 dark:text-purple-300',
                'icon' => 'sparkle',
            ],
            'email.imap' => [
                'title' => 'IMAP Settings',
                'subtitle' => 'Connect your email account to send and receive emails in CRM.',
                'card_bg' => 'bg-[#fff1f2] border-rose-100/90 hover:border-rose-200 dark:bg-rose-950/20 dark:border-rose-900/50',
                'icon_bg' => 'bg-rose-100 text-rose-600 dark:bg-rose-900/60 dark:text-rose-300',
                'btn_bg' => 'bg-rose-100/70 text-rose-600 group-hover:bg-rose-600 group-hover:text-white dark:bg-rose-900/70 dark:text-rose-300',
                'icon' => 'envelope',
            ],
        ];
    @endphp

    <!-- Configuration Categories List -->
    <div class="flex flex-col gap-5 mt-5">
        @foreach (system_config()->getItems() as $item)
            @php
                $itemKey = $item->getKey();
                $meta = $categoryConfig[$itemKey] ?? [
                    'title' => $item->getName(),
                    'subtitle' => $item->getInfo() ?: 'Update your settings here.',
                    'icon_bg' => 'bg-blue-100 text-blue-600 dark:bg-blue-950/70 dark:text-blue-400',
                    'icon' => 'gear',
                ];
            @endphp

            <div class="rounded-2xl border border-slate-200/90 bg-white p-6 shadow-xs dark:border-gray-800 dark:bg-gray-900">
                <!-- Category Section Header -->
                <div class="flex items-center gap-3.5 mb-5">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl {{ $meta['icon_bg'] }}">
                        @if ($meta['icon'] === 'envelope')
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                            </svg>
                        @else
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19.14 12.94c.04-.3.06-.61.06-.94 0-.32-.02-.64-.07-.94l2.03-1.58c.18-.14.23-.41.12-.61l-1.92-3.32c-.12-.22-.37-.29-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54c-.04-.24-.24-.41-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.05.3-.09.63-.09.94s.02.64.07.94l-2.03 1.58c-.18.14-.23.41-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58zM12 15.6c-1.98 0-3.6-1.62-3.6-3.6s1.62-3.6 3.6-3.6 3.6 1.62 3.6 3.6-1.62 3.6-3.6 3.6z"/>
                            </svg>
                        @endif
                    </div>

                    <div class="flex flex-col">
                        <h2 class="text-base font-bold text-gray-900 dark:text-white">
                            {{ $meta['title'] }}
                        </h2>
                        <p class="text-xs text-slate-400 dark:text-slate-500 font-medium mt-0.5">
                            {{ $meta['subtitle'] }}
                        </p>
                    </div>
                </div>

                <!-- 3-Column Responsive Cards Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($item->getChildren() as $key => $child)
                        @php
                            $childKey = $item->getKey() . '.' . $key;
                            $childMeta = $itemConfig[$childKey] ?? [
                                'title' => $child->getName(),
                                'subtitle' => $child->getInfo() ?: 'Update your preferences.',
                                'card_bg' => 'bg-[#f8faff] border-slate-200/90 dark:bg-gray-800 dark:border-gray-700',
                                'icon_bg' => 'bg-blue-100 text-blue-600 dark:bg-blue-950/70 dark:text-blue-300',
                                'btn_bg' => 'bg-blue-100/70 text-blue-600 group-hover:bg-blue-600 group-hover:text-white dark:bg-blue-900/70 dark:text-blue-300',
                                'icon' => 'gear',
                            ];
                        @endphp

                        <a
                            href="{{ route('admin.configuration.index', ($item->getKey() . '/' . $key)) }}"
                            class="group flex items-center justify-between gap-3.5 rounded-2xl border p-5 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-sm {{ $childMeta['card_bg'] }}"
                        >
                            <div class="flex items-center gap-3.5 min-w-0">
                                <!-- Squircle Glyph Icon Container -->
                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl {{ $childMeta['icon_bg'] }}">
                                    @if ($childMeta['icon'] === 'sliders')
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16M8 4v4m8 2v4m-6 4v4"/>
                                        </svg>
                                    @elseif ($childMeta['icon'] === 'sparkle')
                                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 2L14.5 9.5L22 12L14.5 14.5L12 22L9.5 14.5L2 12L9.5 9.5L12 2Z"/>
                                        </svg>
                                    @elseif ($childMeta['icon'] === 'envelope')
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                    @else
                                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M19.14 12.94c.04-.3.06-.61.06-.94 0-.32-.02-.64-.07-.94l2.03-1.58c.18-.14.23-.41.12-.61l-1.92-3.32c-.12-.22-.37-.29-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54c-.04-.24-.24-.41-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.05.3-.09.63-.09.94s.02.64.07.94l-2.03 1.58c-.18.14-.23.41-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58zM12 15.6c-1.98 0-3.6-1.62-3.6-3.6s1.62-3.6 3.6-3.6 3.6 1.62 3.6 3.6-1.62 3.6-3.6 3.6z"/>
                                        </svg>
                                    @endif
                                </div>

                                <!-- Text Information -->
                                <div class="flex flex-col min-w-0">
                                    <span class="text-sm font-bold text-gray-900 transition dark:text-white group-hover:text-blue-600">
                                        {{ $childMeta['title'] }}
                                    </span>
                                    <span class="text-xs text-slate-500 dark:text-slate-400 font-normal mt-1 leading-relaxed line-clamp-2">
                                        {{ $childMeta['subtitle'] }}
                                    </span>
                                </div>
                            </div>

                            <!-- Right Arrow Button -->
                            <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full transition-all duration-200 shadow-2xs {{ $childMeta['btn_bg'] }}">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    {!! view_render_event('admin.configuration.index.content.after') !!}

    @pushOnce('scripts')
        <script type="text/x-template" id="v-configuration-search-template">
            <div class="relative flex w-[525px] max-w-[525px] items-center max-lg:w-[400px]">
                <i class="icon-search absolute top-1.5 flex items-center text-2xl ltr:left-3 rtl:right-3"></i>

                <input 
                    type="text"
                    class="peer block w-full rounded-lg border border-gray-300 bg-white px-10 py-1.5 leading-6 text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"
                    :class="{'border-gray-400': isDropdownOpen}"
                    placeholder="@lang('admin::app.configuration.index.search')"
                    v-model.lazy="searchTerm"
                    @click="searchTerm.length >= 2 ? isDropdownOpen = true : {}"
                    v-debounce="500"
                >

                <div
                    class="absolute top-10 z-10 w-full rounded-lg border bg-white shadow-[0px_0px_0px_0px_rgba(0,0,0,0.10),0px_1px_3px_0px_rgba(0,0,0,0.10),0px_5px_5px_0px_rgba(0,0,0,0.09),0px_12px_7px_0px_rgba(0,0,0,0.05),0px_22px_9px_0px_rgba(0,0,0,0.01),0px_34px_9px_0px_rgba(0,0,0,0.00)] dark:border-gray-800 dark:bg-gray-900"
                    v-if="isDropdownOpen"
                >
                    <template v-if="isLoading">
                        <div class="shimmer flex h-[42px] w-full rounded-md"></div>
                    </template>

                    <template v-else>
                        <div class="grid max-h-[400px] overflow-y-auto">
                            <a
                                :href="category.url"
                                class="cursor-pointer border-b p-4 text-sm font-semibold text-gray-600 last:border-b-0 hover:bg-gray-100 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-gray-950"
                                v-for="category in searchedResults.data"
                            >
                                @{{ category.title }}
                            </a>

                            <div
                                class="p-4 text-sm font-semibold text-gray-600 dark:text-gray-300"
                                v-if="searchedResults.data.length === 0"
                            >
                                @lang('No results found.')
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </script>

        <script type="module">
            app.component('v-configuration-search', {
                template: '#v-configuration-search-template',
                
                data() {
                    return {
                        isDropdownOpen: false,

                        isLoading: false,

                        searchTerm: '',

                        searchedResults: [],
                    };
                },

                watch: {
                    searchTerm(newVal, oldVal) {
                        this.search();
                    },
                },

                created() {
                    window.addEventListener('click', this.handleFocusOut);
                },

                beforeDestroy() {
                    window.removeEventListener('click', this.handleFocusOut);
                },

                methods: {
                    search() {
                        if (this.searchTerm.length <= 1) {
                            this.searchedResults = [];

                            this.isDropdownOpen = false;

                            return;
                        }

                        this.isDropdownOpen = true;

                        this.isLoading = true;
                        
                        this.$axios.get("{{ route('admin.configuration.search') }}", {
                                params: {query: this.searchTerm}
                            })
                            .then((response) => {
                                this.searchedResults = response.data;

                                this.isLoading = false;
                            })
                            .catch((error) => {});
                    },

                    handleFocusOut(e) {
                        if (! this.$el.contains(e.target)) {
                            this.isDropdownOpen = false;
                        }
                    },
                },
            });
        </script>

        <script>
            function toggleConfigDarkMode() {
                const isDark = document.documentElement.classList.contains('dark');
                const newMode = isDark ? 0 : 1;
                const expiryDate = new Date();
                expiryDate.setMonth(expiryDate.getMonth() + 1);
                document.cookie = 'dark_mode=' + newMode + '; path=/; expires=' + expiryDate.toGMTString();
                document.documentElement.classList.toggle('dark', newMode === 1);
                updateConfigDarkBtn();
            }

            function updateConfigDarkBtn() {
                const isDark = document.documentElement.classList.contains('dark');
                const container = document.getElementById('configDarkIcon');
                if (!container) return;
                if (isDark) {
                    container.innerHTML = `<svg class="h-4 w-4 text-amber-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg><span>Light Mode</span>`;
                } else {
                    container.innerHTML = `<svg class="h-4 w-4 text-slate-700 dark:text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg><span>Dark Mode</span>`;
                }
            }

            document.addEventListener('DOMContentLoaded', updateConfigDarkBtn);
        </script>
    @endpushOnce
</x-admin::layouts>
