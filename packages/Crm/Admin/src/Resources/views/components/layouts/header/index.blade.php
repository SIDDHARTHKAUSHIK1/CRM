<header class="admin-header-bar sticky top-0 z-[10001] flex items-center justify-between gap-1.5 border-b border-slate-200/90 bg-white px-4 dark:border-gray-800 dark:bg-gray-900">  
    <!-- logo (visible only on mobile where desktop sidebar is hidden) -->
    <div class="mobile-only-logo flex items-center gap-1.5 shrink-0">
        <!-- Sidebar Menu -->
        <x-admin::layouts.sidebar.mobile />
        
        <a href="{{ route('admin.dashboard.index') }}" class="flex items-center gap-2">
            @if ($logo = core()->getConfigData('general.general.admin_logo.logo_image'))
                <img
                    class="h-10"
                    src="{{ Storage::url($logo) }}"
                    alt="{{ config('app.name') }}"
                />
            @else
                <div class="flex items-center gap-2">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#6366f1] font-bold text-white text-sm tracking-tight shadow-sm">
                        RE
                    </div>
                    <span class="text-xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                        CRM
                    </span>
                </div>
            @endif
        </a>
    </div>

    <div class="flex items-center gap-2.5 max-md:hidden">
        <!-- Mega Search Bar -->
        @include('admin::components.layouts.header.desktop.mega-search')

        <!-- Quick Creation Bar -->
        @include('admin::components.layouts.header.quick-creation')
    </div>

    <div class="flex items-center gap-1.5 sm:gap-2.5 flex-nowrap shrink-0">
        @if (request()->routeIs('admin.whatsapp.*') || request()->is('admin/whatsapp*'))
            <!-- WhatsApp Broadcast Contextual Header Actions -->
            <div class="flex items-center gap-2 max-sm:hidden">
                <!-- DNC Registry Button -->
                <a
                    href="{{ route('admin.whatsapp.dnc') }}"
                    class="secondary-button !px-3 !py-1.5 !text-xs !font-bold {{ request()->routeIs('admin.whatsapp.dnc') ? '!bg-gray-100 dark:!bg-gray-800' : '' }}"
                    title="Do Not Contact List"
                >
                    <span class="icon-bookmark text-sm"></span>
                    <span class="hidden lg:inline">Do Not Contact List</span>
                </a>

                <!-- Gateway QR / Link Button -->
                <a
                    href="{{ route('admin.whatsapp.gateway') }}"
                    class="secondary-button !px-3 !py-1.5 !text-xs !font-bold {{ request()->routeIs('admin.whatsapp.gateway') ? '!bg-gray-100 dark:!bg-gray-800' : '' }}"
                    title="Link WhatsApp (QR)"
                >
                    <span class="icon-whatsapp text-sm text-emerald-600 dark:text-emerald-400"></span>
                    <span class="hidden lg:inline">Link WhatsApp (QR)</span>
                </a>

                <!-- Create Broadcast Button -->
                @if (bouncer()->hasPermission('whatsapp.create'))
                    <a
                        href="{{ route('admin.whatsapp.create') }}"
                        class="primary-button !px-3.5 !py-1.5 !text-xs !font-bold {{ request()->routeIs('admin.whatsapp.create') ? 'ring-2 ring-brandColor/30' : '' }}"
                        title="New Broadcast"
                    >
                        <span class="icon-add text-sm font-bold"></span>
                        <span class="hidden sm:inline">New Broadcast</span>
                    </a>
                @endif
            </div>
        @endif

        <div class="md:hidden">
            <!-- Mega Search Bar -->
            @include('admin::components.layouts.header.mobile.mega-search')
        </div>

        <div class="md:hidden">
            <!-- Quick Creation Bar -->
            @include('admin::components.layouts.header.quick-creation')
        </div>

        @php
            $currentUser = auth()->guard('user')->user();
            $isAdmin = $currentUser?->role && $currentUser->role->permission_type === 'all';
        @endphp

        <!-- Role Badge -->
        <div class="flex items-center">
            @if ($isAdmin)
                <span class="inline-flex items-center gap-1 rounded-full bg-red-600 px-2 py-0.5 text-xs font-extrabold uppercase tracking-wider text-white shadow-xs dark:bg-red-500 dark:text-white sm:px-2.5" title="@lang('admin::app.admin-panel.roles.admin')">
                    <svg class="w-3 h-3 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                    <span class="hidden sm:inline">@lang('admin::app.admin-panel.roles.admin')</span>
                </span>
            @else
                <span class="inline-flex items-center gap-1 rounded-full bg-blue-600 px-2 py-0.5 text-xs font-extrabold uppercase tracking-wider text-white shadow-xs dark:bg-blue-500 dark:text-white sm:px-2.5" title="@lang('admin::app.admin-panel.roles.employee')">
                    <svg class="w-3 h-3 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    <span class="hidden sm:inline">@lang('admin::app.admin-panel.roles.employee')</span>
                </span>
            @endif
        </div>
        
        <!-- Admin profile -->
        <x-admin::dropdown position="bottom-{{ in_array(app()->getLocale(), ['fa', 'ar']) ? 'left' : 'right' }}">
            <x-slot:toggle>
                @if ($currentUser->image)
                    <button class="flex h-9 w-9 cursor-pointer overflow-hidden rounded-full hover:opacity-80 focus:opacity-80">
                        <img
                            src="{{ $currentUser->image_url }}"
                            class="h-full w-full object-cover"
                        />
                    </button>
                @else
                    <button class="flex h-8 w-8 cursor-pointer items-center justify-center rounded-full bg-[#7c3aed] font-bold text-sm leading-none text-white shadow-sm">
                        {{ substr($currentUser->name, 0, 1) }}
                    </button>
                @endif
            </x-slot>

            <!-- Admin Dropdown -->
            <x-slot:content class="mt-2 border-t-0 !p-0">
                <div class="flex items-center justify-between border-b border-gray-200 px-5 py-2.5 dark:border-gray-800">
                    <div class="flex flex-col min-w-0 pr-2">
                        <span class="truncate text-sm font-bold text-gray-900 dark:text-white">{{ $currentUser->name }}</span>
                        <span class="truncate text-xs text-gray-500 dark:text-gray-400">{{ $currentUser->email }}</span>
                    </div>
                    @if ($isAdmin)
                        <span class="inline-flex items-center rounded-full bg-red-600 px-2 py-0.5 text-[10px] font-black uppercase text-white dark:bg-red-500">
                            @lang('admin::app.admin-panel.roles.admin')
                        </span>
                    @else
                        <span class="inline-flex items-center rounded-full bg-blue-600 px-2 py-0.5 text-[10px] font-black uppercase text-white dark:bg-blue-500">
                            @lang('admin::app.admin-panel.roles.employee')
                        </span>
                    @endif
                </div>

                <div class="flex items-center gap-1.5 border border-x-0 border-b-gray-300 px-5 py-2 dark:border-gray-800">
                    @if ($logo = core()->getConfigData('general.general.admin_logo.logo_image'))
                        <img
                            src="{{ Storage::url($logo) }}"
                            alt="{{ config('app.name') }}"
                            width="24"
                            height="24"
                        />
                    @else
                        <img
                            src="{{ request()->cookie('dark_mode') ? vite()->asset('images/dark-logo.svg') : vite()->asset('images/logo.svg') }}"
                            id="logo-image"
                            alt="{{ config('app.name') }}"
                            width="24"
                            height="24"
                        />
                    @endif

                    <!-- Version -->
                    <p class="text-gray-400">
                        @lang('admin::app.layouts.app-version', ['version' => core()->version()])
                    </p>
                </div>

                <div class="grid gap-1 pb-2.5">
                    <a
                        class="cursor-pointer px-5 py-2 text-base text-gray-800 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-950"
                        href="{{ route('admin.user.account.edit') }}"
                    >
                        @lang('admin::app.layouts.my-account')
                    </a>

                    <!--Admin logout-->
                    <x-admin::form
                        method="DELETE"
                        action="{{ route('admin.session.destroy') }}"
                        id="adminLogout"
                    >
                    </x-admin::form>

                    <a
                        class="cursor-pointer px-5 py-2 text-base text-gray-800 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-950"
                        href="{{ route('admin.session.destroy') }}"
                        onclick="event.preventDefault(); document.getElementById('adminLogout').submit();"
                    >
                        @lang('admin::app.layouts.sign-out')
                    </a>
                </div>
            </x-slot>
        </x-admin::dropdown>
    </div>
</header>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-dark-template"
    >
        <div class="flex">
            <span
                class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-100 dark:hover:bg-gray-950"
                :class="[isDarkMode ? 'icon-light' : 'icon-dark']"
                @click="toggle"
            ></span>
        </div>
    </script>

    <script type="module">
        app.component('v-dark', {
            template: '#v-dark-template',

            data() {
                return {
                    isDarkMode: {{ request()->cookie('dark_mode') ?? 0 }},

                    logo: "{{ vite()->asset('images/logo.svg') }}",

                    dark_logo: "{{ vite()->asset('images/dark-logo.svg') }}",
                };
            },

            methods: {
                toggle() {
                    this.isDarkMode = parseInt(this.isDarkModeCookie()) ? 0 : 1;

                    var expiryDate = new Date();

                    expiryDate.setMonth(expiryDate.getMonth() + 1);

                    document.cookie = 'dark_mode=' + this.isDarkMode + '; path=/; expires=' + expiryDate.toGMTString();

                    document.documentElement.classList.toggle('dark', this.isDarkMode === 1);

                    if (this.isDarkMode) {
                        this.$emitter.emit('change-theme', 'dark');

                        document.getElementById('logo-image').src = this.dark_logo;
                    } else {
                        this.$emitter.emit('change-theme', 'light');

                        document.getElementById('logo-image').src = this.logo;
                    }
                },

                isDarkModeCookie() {
                    const cookies = document.cookie.split(';');

                    for (const cookie of cookies) {
                        const [name, value] = cookie.trim().split('=');

                        if (name === 'dark_mode') {
                            return value;
                        }
                    }

                    return 0;
                },
            },
        });
    </script>
@endPushOnce
