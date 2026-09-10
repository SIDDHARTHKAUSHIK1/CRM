<v-sidebar-drawer>
    <i class="icon-menu lg:hidden cursor-pointer rounded-md p-1.5 text-2xl hover:bg-gray-100 dark:hover:bg-gray-950 max-lg:block"></i>
</v-sidebar-drawer>

@php
    $mobileNavIcons = [
        'dashboard' => [
            'bg_style' => 'background-color: #eff6ff !important; color: #3b82f6 !important;',
            'svg' => '<svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>',
        ],
        'leads' => [
            'bg_style' => 'background-color: #ecfdf5 !important; color: #10b981 !important;',
            'svg' => '<svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>',
        ],
        'quotes' => [
            'bg_style' => 'background-color: #f5f3ff !important; color: #8b5cf6 !important;',
            'svg' => '<svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>',
        ],
        'whatsapp' => [
            'bg_style' => 'background-color: #f0fdf4 !important; color: #22c55e !important;',
            'svg' => '<svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>',
        ],
        'mail' => [
            'bg_style' => 'background-color: #f0eeff !important; color: #5b5df4 !important;',
            'svg' => '<svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>',
        ],
        'activities' => [
            'bg_style' => 'background-color: #f0f9ff !important; color: #0ea5e9 !important;',
            'svg' => '<svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line><path d="M9 16l2 2 4-4"></path></svg>',
        ],
        'contacts' => [
            'bg_style' => 'background-color: #fff1f2 !important; color: #f43f5e !important;',
            'svg' => '<svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>',
        ],
        'products' => [
            'bg_style' => 'background-color: #fff7ed !important; color: #f97316 !important;',
            'svg' => '<svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>',
        ],
        'admin_panel' => [
            'bg_style' => 'background-color: #fef2f2 !important; color: #ef4444 !important;',
            'svg' => '<svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>',
        ],
        'settings' => [
            'bg_style' => 'background-color: #f1f5f9 !important; color: #64748b !important;',
            'svg' => '<svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>',
        ],
        'configuration' => [
            'bg_style' => 'background-color: #faf5ff !important; color: #a855f7 !important;',
            'svg' => '<svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path></svg>',
        ],
    ];

    $mobileSubmenuDetails = [
        'mail.inbox' => ['title' => 'Inbox', 'subtitle' => 'View received emails', 'badge' => '3'],
        'mail.draft' => ['title' => 'Draft', 'subtitle' => 'Saved email drafts', 'badge' => null],
        'mail.outbox' => ['title' => 'Outbox', 'subtitle' => 'Emails being sent', 'badge' => null],
        'mail.sent' => ['title' => 'Sent', 'subtitle' => 'Sent emails', 'badge' => null],
        'mail.trash' => ['title' => 'Trash', 'subtitle' => 'Deleted emails', 'badge' => null],
        'contacts.persons' => ['title' => 'Persons', 'subtitle' => 'Individual contacts', 'badge' => null],
        'contacts.organizations' => ['title' => 'Organizations', 'subtitle' => 'Business contacts', 'badge' => null],
    ];
@endphp

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-sidebar-drawer-template"
    >
        <x-admin::drawer
            position="left"
            width="280px"
            class="lg:hidden [&>:nth-child(3)]:!m-0 [&>:nth-child(3)]:!rounded-l-none [&>:nth-child(3)]:max-sm:!w-[85%] [&>:nth-child(3)]:!pt-[env(safe-area-inset-top,0px)] [&>:nth-child(3)]:!pb-[env(safe-area-inset-bottom,0px)]"
        >
            <x-slot:toggle>
                <i class="icon-menu lg:hidden cursor-pointer rounded-md p-1.5 text-2xl hover:bg-gray-100 dark:hover:bg-gray-950 max-lg:block"></i>
            </x-slot>

            <x-slot:header>
                <div class="flex items-center gap-3 pt-1">
                    <div
                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl font-bold text-white text-base shadow-sm"
                        style="background-color: #5b5df4 !important; color: #ffffff !important; box-shadow: 0 4px 12px rgba(91, 93, 244, 0.28) !important;"
                    >
                        RE
                    </div>
                    <div class="flex flex-col">
                        <span class="sidebar-brand-title text-xl font-extrabold text-gray-900 dark:text-white leading-none">CRM</span>
                        <span class="text-[11.5px] font-semibold text-slate-400 dark:text-slate-400 mt-1.5">Grow &bull; Manage &bull; Succeed</span>
                    </div>
                </div>
            </x-slot>

            <x-slot:content class="p-3">
                <div class="journal-scroll h-[calc(100vh-140px-env(safe-area-inset-top,0px))] h-[calc(100dvh-140px-env(safe-area-inset-top,0px))] overflow-auto flex flex-col justify-between">
                    <nav class="flex flex-col gap-1 w-full">
                        @foreach (menu()->getItems('admin') as $menuItem)
                            @php
                                $menuKey = $menuItem->getKey();
                                if ($menuKey === 'admin_panel' && (! auth()->guard('user')->user()?->role || auth()->guard('user')->user()->role->permission_type !== 'all')) {
                                    continue;
                                }
                                $hasActiveChild = $menuItem->haveChildren() && collect($menuItem->getChildren())->contains(fn($child) => $child->isActive());
                                $isMenuActive = $menuItem->isActive() == 'active' || $hasActiveChild;
                                $hasChildren = ! in_array($menuKey, ['settings', 'configuration', 'admin_panel']) && $menuItem->haveChildren();
                                $iconConf = $mobileNavIcons[$menuKey] ?? [
                                    'bg_style' => 'background-color: #f1f5f9 !important; color: #64748b !important;',
                                    'svg' => '<svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"></circle></svg>',
                                ];
                            @endphp

                            <div
                                class="menu-item relative"
                                data-menu-key="{{ $menuKey }}"
                            >
                                <a
                                    href="{{ $hasChildren ? 'javascript:void(0)' : $menuItem->getUrl() }}"
                                    class="flex items-center justify-between p-2 rounded-2xl transition-all duration-150 {{ $isMenuActive ? 'bg-[#f0eeff] dark:bg-indigo-950/60' : 'hover:bg-slate-50 dark:hover:bg-gray-800/60' }}"
                                    @if ($hasChildren)
                                        @click.prevent="toggleMenu('{{ $menuKey }}')"
                                    @endif
                                >
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0"
                                            style="{{ $isMenuActive ? 'background-color: #5b5df4 !important; color: #ffffff !important;' : $iconConf['bg_style'] }}"
                                        >
                                            {!! $iconConf['svg'] !!}
                                        </div>

                                        <p
                                            class="whitespace-nowrap text-sm font-bold {{ $isMenuActive ? 'text-indigo-600 dark:text-indigo-300' : 'text-slate-800 dark:text-white' }}"
                                        >
                                            {{ core()->getConfigData('general.settings.menu.'.$menuKey) ?: $menuItem->getName() }}
                                        </p>
                                    </div>

                                    @if ($hasChildren)
                                        <svg
                                            class="w-4 h-4 transition-transform duration-200"
                                            :class="{ 'rotate-180': activeMenu === '{{ $menuKey }}' }"
                                            style="{{ $isMenuActive ? 'color: #818cf8 !important;' : 'color: #94a3b8 !important;' }}"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    @endif
                                </a>

                                @if ($hasChildren)
                                    <div
                                        class="overflow-hidden transition-all duration-200 pl-4 pr-1 flex flex-col gap-1"
                                        :class="{ 'max-h-[500px] py-1.5': activeMenu === '{{ $menuKey }}' || {{ $hasActiveChild ? 'true' : 'false' }}, 'max-h-0 py-0': activeMenu !== '{{ $menuKey }}' && !{{ $hasActiveChild ? 'true' : 'false' }} }"
                                    >
                                        @foreach ($menuItem->getChildren() as $subMenuItem)
                                            @php
                                                $subKey = $subMenuItem->getKey();
                                                $subDet = $mobileSubmenuDetails[$subKey] ?? ['title' => $subMenuItem->getName(), 'subtitle' => '', 'badge' => null];
                                                $isChildActive = $subMenuItem->isActive() == 'active';
                                            @endphp
                                            <a
                                                href="{{ $subMenuItem->getUrl() }}"
                                                class="flex items-center justify-between p-2 rounded-xl text-xs font-semibold transition-all {{ $isChildActive ? 'font-bold' : 'hover:bg-slate-50' }}"
                                                style="{{ $isChildActive ? 'background-color: #f0eeff !important; color: #5b5df4 !important;' : 'color: #475569 !important;' }}"
                                            >
                                                <span>{{ $subDet['title'] }}</span>
                                                @if (!empty($subDet['badge']))
                                                    <span
                                                        class="w-4 h-4 rounded-full text-white text-[10px] font-bold flex items-center justify-center shrink-0"
                                                        style="background-color: #5b5df4 !important; color: #ffffff !important;"
                                                    >
                                                        {{ $subDet['badge'] }}
                                                    </span>
                                                @endif
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </nav>

                    <!-- Mobile Bottom Help Card -->
                    <div class="pt-4 mt-4 border-t border-slate-100 dark:border-gray-800">
                        <a
                            href="mailto:support@example.com"
                            class="p-2.5 rounded-2xl flex items-center justify-between"
                            style="background-color: #f0eeff !important; border: 1px solid #e0dbff !important;"
                        >
                            <div class="flex items-center gap-2.5 min-w-0">
                                <div
                                    class="w-9 h-9 rounded-xl shadow-2xs flex items-center justify-center shrink-0"
                                    style="background-color: #ffffff !important; color: #5b5df4 !important;"
                                >
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M3 18v-6a9 9 0 0 1 18 0v6"></path>
                                        <path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"></path>
                                    </svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-xs font-bold leading-tight" style="color: #1e1b4b !important;">Need Help?</p>
                                    <p class="text-[10px] font-medium truncate mt-0.5" style="color: #64748b !important;">Contact support anytime</p>
                                </div>
                            </div>
                            <svg class="w-4 h-4 shrink-0" style="color: #818cf8 !important;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </x-slot>
        </x-admin::drawer>
    </script>

    <script type="module">
        app.component('v-sidebar-drawer', {
            template: '#v-sidebar-drawer-template',

            data() {
                return { activeMenu: null };
            },

            mounted() {
                const activeElement = document.querySelector('.menu-item .menu-link.bg-brandColor');

                if (activeElement) {
                    this.activeMenu = activeElement.closest('.menu-item').getAttribute('data-menu-key');
                }
            },

            methods: {
                toggleMenu(menuKey) {
                    this.activeMenu = this.activeMenu === menuKey ? null : menuKey;
                }
            },
        });
    </script>
@endPushOnce
