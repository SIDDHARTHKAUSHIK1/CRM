<x-admin::layouts>
    <x-slot:title>
        Activities &amp; Daily Schedule - RealEstate CRM
    </x-slot>

    @pushOnce('styles')
        <style>
            .activity-card {
                transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            }
            .activity-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.03);
            }
            @keyframes modalFadeIn {
                from { opacity: 0; transform: scale(0.96) translateY(8px); }
                to { opacity: 1; transform: scale(1) translateY(0); }
            }
            .animate-modal-in {
                animation: modalFadeIn 0.2s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            }
        </style>
    @endPushOnce

    <div class="flex flex-col gap-6" id="activities-workspace">

        <!-- ========================================================= -->
        <!-- 1. TOP HEADER SECTION -->
        <!-- ========================================================= -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 bg-transparent">
            <!-- Greeting & Titles -->
            <div class="flex flex-col gap-1 min-w-0">
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400">Good Morning, 👋</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-800 dark:text-white flex items-center gap-2.5">
                    Activities &amp; Daily Schedule
                </h1>
                <p class="text-xs font-semibold text-slate-500 dark:text-slate-400">
                    Manage tasks, client calls, meetings &amp; follow-ups
                </p>
            </div>

            <!-- View Switchers, Date Widget & + Add Activity Action -->
            <div class="flex items-center gap-3 flex-wrap">
                
                <!-- View Switcher: Timeline Feed vs Full View Calendar -->
                <div class="inline-flex rounded-2xl p-1 bg-slate-100 dark:bg-gray-800/90 border border-slate-200/90 dark:border-gray-700 shadow-2xs">
                    <button
                        type="button"
                        id="btn-header-feed"
                        onclick="switchMainView('feed')"
                        class="px-3.5 py-1.5 rounded-xl bg-white dark:bg-gray-900 shadow-2xs text-xs font-bold text-blue-600 dark:text-blue-400 flex items-center gap-1.5 transition cursor-pointer"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path d="M4 6h16M4 12h16M4 18h16" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                        <span>Timeline Feed</span>
                    </button>
                    
                    <button
                        type="button"
                        id="btn-header-calendar"
                        onclick="switchMainView('calendar')"
                        class="px-3.5 py-1.5 rounded-xl text-xs font-bold text-slate-600 dark:text-slate-300 hover:text-gray-600 dark:hover:text-white flex items-center gap-1.5 transition cursor-pointer"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                        <span>Full View Calendar</span>
                    </button>
                </div>

                <!-- Date Pill Card with Motivational Badge -->
                <div class="flex items-center gap-3 bg-white dark:bg-gray-900 border border-slate-200/90 dark:border-gray-800 rounded-2xl px-4 py-2 shadow-2xs">
                    <div class="flex items-center gap-2 text-xs font-bold text-slate-700 dark:text-slate-200">
                        <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                        <span>{{ now()->format('l, j M Y') }}</span>
                    </div>

                    <span class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-800 border border-amber-200 dark:bg-amber-950/60 dark:text-amber-300 dark:border-amber-800">
                        ⭐ Keep going! You're doing great!
                    </span>
                </div>

                <!-- + Add Activity Button -->
                <button
                    type="button"
                    onclick="window.openScheduleModal('{{ now()->format('Y-m-d') }}')"
                    class="primary-button !px-4 !py-2.5 !rounded-2xl !text-xs font-bold shadow-sm cursor-pointer transition active:scale-95 flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="M12 4v16m8-8H4" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                    <span>Add Activity</span>
                </button>
            </div>
        </div>

        <!-- ========================================================= -->
        <!-- 2. TOP 4 METRICS CARDS -->
        <!-- ========================================================= -->
        <div id="activities-metrics-section" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 transition-all duration-200">
            
            <!-- Card 1: Total Activities (Rose / Pink Icon) -->
            <div class="bg-white dark:bg-gray-900 rounded-2xl p-5 border border-slate-200/90 dark:border-gray-800 shadow-2xs flex flex-col justify-between space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400">Total Activities</span>
                    <div class="w-9 h-9 rounded-xl bg-rose-50 text-rose-600 dark:bg-rose-950/60 dark:text-rose-400 border border-rose-100 dark:border-rose-900/50 flex items-center justify-center">
                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                            <path d="M9 3v18M14 9h4M14 15h4"></path>
                        </svg>
                    </div>
                </div>
                <div class="space-y-1">
                    <h3 class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-white">
                        {{ $totalActivitiesCount }}
                    </h3>
                    <div class="flex items-center gap-1 text-[11px] font-bold text-emerald-600 dark:text-emerald-400">
                        <span>Today +3%</span>
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M7 17l10-10M17 7H7m10 0v10" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Card 2: Completed (Emerald / Green Icon) -->
            <div class="bg-white dark:bg-gray-900 rounded-2xl p-5 border border-slate-200/90 dark:border-gray-800 shadow-2xs flex flex-col justify-between space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400">Completed</span>
                    <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 dark:bg-emerald-950/60 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/50 flex items-center justify-center">
                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" stroke-linecap="round" stroke-linejoin="round"></path>
                            <polyline points="22 4 12 14.01 9 11.01" stroke-linecap="round" stroke-linejoin="round"></polyline>
                        </svg>
                    </div>
                </div>
                <div class="space-y-1">
                    <h3 class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-white">
                        {{ $totalCompletedCount }}
                    </h3>
                    <div class="flex items-center gap-1 text-[11px] font-bold text-emerald-600 dark:text-emerald-400">
                        <span>Today +1</span>
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M7 17l10-10M17 7H7m10 0v10" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Card 3: Pending (Blue Icon) -->
            <div class="bg-white dark:bg-gray-900 rounded-2xl p-5 border border-slate-200/90 dark:border-gray-800 shadow-2xs flex flex-col justify-between space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400">Pending</span>
                    <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 dark:bg-blue-950/60 dark:text-blue-400 border border-blue-100 dark:border-blue-900/50 flex items-center justify-center">
                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                    </div>
                </div>
                <div class="space-y-1">
                    <h3 class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-white">
                        {{ $todayPendingCount }}
                    </h3>
                    <div class="flex items-center gap-1 text-[11px] font-bold text-emerald-600 dark:text-emerald-400">
                        <span>Today +2</span>
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M7 17l10-10M17 7H7m10 0v10" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Card 4: Calls (Purple Icon) -->
            <div class="bg-white dark:bg-gray-900 rounded-2xl p-5 border border-slate-200/90 dark:border-gray-800 shadow-2xs flex flex-col justify-between space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400">Calls</span>
                    <div class="w-9 h-9 rounded-xl bg-purple-50 text-purple-600 dark:bg-purple-950/60 dark:text-purple-400 border border-purple-100 dark:border-purple-900/50 flex items-center justify-center">
                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                        </svg>
                    </div>
                </div>
                <div class="space-y-1">
                    <h3 class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-white">
                        {{ $callsCount }}
                    </h3>
                    <div class="flex items-center gap-1 text-[11px] font-bold text-slate-500 dark:text-slate-400">
                        <span>Today 0</span>
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 19V5M5 12l7-7 7 7" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                    </div>
                </div>
            </div>

        </div>

        <!-- ========================================================= -->
        <!-- 3A. VIEW 1: TIMELINE FEED & SIDEBAR (2 COLUMNS)           -->
        <!-- ========================================================= -->
        <div id="container-feed-view" class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            
            <!-- LEFT COLUMN: SEARCH/FILTERS + TIMELINE GROUPS (8 COLS) -->
            <div class="lg:col-span-8 space-y-6">
                
                <!-- Live Search & Filter Bar (Sub Section Controls) -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white dark:bg-gray-900 p-3.5 sm:p-4 rounded-2xl border border-slate-200/90 dark:border-gray-800 shadow-2xs">
                    
                    <!-- Search Input -->
                    <div class="relative flex-1 min-w-[180px]">
                        <input
                            type="text"
                            oninput="filterActivitiesBySearch(this.value)"
                            placeholder="Search activities..."
                            class="w-full pl-9 pr-4 py-2 text-xs font-semibold bg-slate-50 dark:bg-gray-800/80 border border-slate-200 dark:border-gray-700 rounded-xl focus:border-blue-500 focus:bg-white dark:focus:bg-gray-900 text-gray-600dark:text-white placeholder:text-slate-400 transition"
                        >
                        <span class="absolute left-3 top-2.5 text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                        </span>
                    </div>

                    <!-- Filter Options & Full View Calendar Button in Sub Section -->
                    <div class="flex items-center gap-2 flex-wrap">
                        <!-- All Activities Pill Button -->
                        <button
                            type="button"
                            onclick="filterActivitiesByType('all', this)"
                            class="filter-pill-btn px-3.5 py-2 rounded-xl bg-blue-600 text-white text-xs font-bold shadow-2xs transition cursor-pointer"
                        >
                            All Activities
                        </button>

                        <!-- Date Range Select -->
                        <select
                            id="filter-date-period"
                            onchange="filterActivitiesByPeriod(this.value)"
                            class="px-3 py-2 rounded-xl bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 text-xs font-bold text-slate-700 dark:text-slate-300 focus:border-blue-500 transition cursor-pointer"
                        >
                            <option value="all">All Dates</option>
                            <option value="today" selected>Today</option>
                            <option value="yesterday">Yesterday</option>
                            <option value="upcoming">Upcoming</option>
                        </select>

                        <!-- Type Select -->
                        <select
                            id="filter-type-select"
                            onchange="filterActivitiesByTypeSelect(this.value)"
                            class="px-3 py-2 rounded-xl bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 text-xs font-bold text-slate-700 dark:text-slate-300 focus:border-blue-500 transition cursor-pointer"
                        >
                            <option value="all">All Types</option>
                            <option value="call">Calls</option>
                            <option value="meeting">Meetings</option>
                            <option value="lunch">Luncheons</option>
                            <option value="task">Tasks</option>
                        </select>

                        <!-- Full View Calendar Button in Sub Section -->
                        <button
                            type="button"
                            onclick="switchMainView('calendar')"
                            id="btn-sub-calendar-toggle"
                            class="px-3.5 py-2 rounded-xl bg-slate-50 hover:bg-slate-100 dark:bg-gray-800 dark:hover:bg-gray-700 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-gray-700 text-xs font-bold transition cursor-pointer flex items-center gap-1.5 shadow-2xs"
                            title="Open Full View Calendar Workspace"
                        >
                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                            <span>Full View Calendar</span>
                        </button>
                    </div>

                </div>

                <!-- TIMELINE FEED SECTION -->
                <div id="container-feed-list" class="space-y-6">

                    <!-- TIMELINE GROUP 1: TODAY -->
                    <div class="space-y-3.5 activity-date-group" id="group-today">
                        <div class="flex items-center justify-between pb-1 border-b border-slate-200/80 dark:border-gray-800">
                            <h2 class="text-sm font-bold text-gray-800 dark:text-white flex items-center gap-2">
                                <span>Today · {{ now()->format('l, j M Y') }}</span>
                            </h2>
                            <span class="text-xs font-bold text-slate-400">
                                {{ count($todayActivities) }}
                            </span>
                        </div>

                        <div class="space-y-3" id="activities-list-today">
                            @forelse ($todayActivities as $act)
                                @include('admin::activities.card-item', ['activity' => $act])
                            @empty
                                <div class="p-6 bg-slate-50 dark:bg-gray-800/50 rounded-2xl border border-dashed border-slate-300 dark:border-gray-700 text-center text-xs font-bold text-slate-500">
                                    No touchpoints recorded for today. Click <strong>+ Add Activity</strong> to log tasks or calls.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- TIMELINE GROUP 2: YESTERDAY -->
                    <div class="space-y-3.5 activity-date-group" id="group-yesterday">
                        <div class="flex items-center justify-between pb-1 border-b border-slate-200/80 dark:border-gray-800">
                            <h2 class="text-sm font-bold text-gray-800 dark:text-white flex items-center gap-2">
                                <span>Yesterday · {{ now()->subDay()->format('l, j M Y') }}</span>
                            </h2>
                            <span class="text-xs font-bold text-slate-400">
                                {{ count($yesterdayActivities) }}
                            </span>
                        </div>

                        <div class="space-y-3" id="activities-list-yesterday">
                            @forelse ($yesterdayActivities as $act)
                                @include('admin::activities.card-item', ['activity' => $act])
                            @empty
                                <div class="p-6 bg-slate-50 dark:bg-gray-800/50 rounded-2xl border border-dashed border-slate-300 dark:border-gray-700 text-center text-xs font-bold text-slate-500">
                                    No activities recorded for yesterday.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- TIMELINE GROUP 3: UPCOMING -->
                    <div class="space-y-3.5 activity-date-group" id="group-upcoming">
                        <div class="flex items-center justify-between pb-1 border-b border-slate-200/80 dark:border-gray-800">
                            <h2 class="text-sm font-bold text-gray-800 dark:text-white flex items-center gap-2">
                                <span>Upcoming · {{ now()->addDay()->format('l, j M Y') }}</span>
                            </h2>
                            <span class="text-xs font-bold text-slate-400">
                                {{ count($upcomingActivities) }}
                            </span>
                        </div>

                        <div class="space-y-3" id="activities-list-upcoming">
                            @forelse ($upcomingActivities as $act)
                                @include('admin::activities.card-item', ['activity' => $act])
                            @empty
                                <div class="p-6 bg-slate-50 dark:bg-gray-800/50 rounded-2xl border border-dashed border-slate-300 dark:border-gray-700 text-center text-xs font-bold text-slate-500">
                                    No future upcoming activities found.
                                </div>
                            @endforelse
                        </div>
                    </div>

                </div>

            </div>

            <!-- RIGHT COLUMN: 4 WIDGETS (4 COLS) -->
            <div class="lg:col-span-4 space-y-5 sticky top-20">
                
                <!-- WIDGET 1: QUICK STATS -->
                <div class="bg-white dark:bg-gray-900 p-5 rounded-2xl border border-slate-200/90 dark:border-gray-800 shadow-2xs space-y-4">
                    <h3 class="text-sm font-bold text-gray-800 dark:text-white">
                        Quick Stats
                    </h3>

                    <div class="space-y-3 text-xs font-bold">
                        <!-- Row 1: Completed Today -->
                        <div class="flex items-center justify-between py-1 border-b border-slate-100 dark:border-gray-800">
                            <span class="text-slate-500 dark:text-slate-400">Completed Today</span>
                            <span class="text-emerald-600 dark:text-emerald-400 font-bold text-sm">{{ $todayDoneCount }}</span>
                        </div>

                        <!-- Row 2: Pending Today -->
                        <div class="flex items-center justify-between py-1 border-b border-slate-100 dark:border-gray-800">
                            <span class="text-slate-500 dark:text-slate-400">Pending Today</span>
                            <span class="text-amber-600 dark:text-amber-400 font-bold text-sm">{{ $todayPendingCount }}</span>
                        </div>

                        <!-- Row 3: Calls Today -->
                        <div class="flex items-center justify-between py-1 border-b border-slate-100 dark:border-gray-800">
                            <span class="text-slate-500 dark:text-slate-400">Calls Today</span>
                            <span class="text-blue-600 dark:text-blue-400 font-bold text-sm">{{ $todayCallsCount }}</span>
                        </div>

                        <!-- Row 4: Follow Ups -->
                        <div class="flex items-center justify-between py-1">
                            <span class="text-slate-500 dark:text-slate-400">Follow Ups</span>
                            <span class="text-purple-600 dark:text-purple-400 font-bold text-sm">{{ $todayFollowUpsCount }}</span>
                        </div>
                    </div>
                </div>

                <!-- WIDGET 2: UPCOMING TODAY -->
                <div class="bg-white dark:bg-gray-900 p-5 rounded-2xl border border-slate-200/90 dark:border-gray-800 shadow-2xs space-y-4">
                    <h3 class="text-sm font-bold text-gray-800 dark:text-white">
                        Upcoming Today
                    </h3>

                    <div class="space-y-3">
                        @forelse ($upcomingTodayActivities as $item)
                            @php
                                $itemTime = $item->schedule_from ? date('h:i A', strtotime($item->schedule_from)) : '11:30 AM';
                                $leadName = $item->lead_title ?: ($item->user_name ?: 'Apex Corp');
                            @endphp
                            <div class="flex items-start gap-3 p-2.5 rounded-xl bg-slate-50/60 dark:bg-gray-800/40 border border-slate-100 dark:border-gray-800">
                                <span class="px-2 py-1 rounded-lg text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200 dark:bg-blue-950 dark:text-blue-300 dark:border-blue-800 shrink-0">
                                    {{ $itemTime }}
                                </span>
                                <div class="min-w-0 flex-1">
                                    <h4 class="text-xs font-bold text-gray-800 dark:text-white truncate">
                                        {{ $item->title }}
                                    </h4>
                                    <p class="text-[11px] text-slate-400 truncate">
                                        {{ $leadName }}
                                    </p>
                                </div>
                            </div>
                        @empty
                            <div class="flex items-start gap-3 p-2.5 rounded-xl bg-slate-50/60 dark:bg-gray-800/40 border border-slate-100 dark:border-gray-800">
                                <span class="px-2 py-1 rounded-lg text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950 dark:text-emerald-300 dark:border-emerald-800 shrink-0">
                                    11:30 AM
                                </span>
                                <div class="min-w-0 flex-1">
                                    <h4 class="text-xs font-bold text-gray-800 dark:text-white truncate">
                                        Client Meeting
                                    </h4>
                                    <p class="text-[11px] text-slate-400 truncate">
                                        Apex Corp
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3 p-2.5 rounded-xl bg-slate-50/60 dark:bg-gray-800/40 border border-slate-100 dark:border-gray-800">
                                <span class="px-2 py-1 rounded-lg text-[10px] font-bold bg-purple-50 text-purple-700 border border-purple-200 dark:bg-purple-950 dark:text-purple-300 dark:border-purple-800 shrink-0">
                                    02:00 PM
                                </span>
                                <div class="min-w-0 flex-1">
                                    <h4 class="text-xs font-bold text-gray-800 dark:text-white truncate">
                                        Follow up on Quotation
                                    </h4>
                                    <p class="text-[11px] text-slate-400 truncate">
                                        Skyline Ventures
                                    </p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- WIDGET 3: RECENT CUSTOMERS -->
                <div class="bg-white dark:bg-gray-900 p-5 rounded-2xl border border-slate-200/90 dark:border-gray-800 shadow-2xs space-y-4">
                    <h3 class="text-sm font-bold text-gray-800 dark:text-white">
                        Recent Customers
                    </h3>

                    <div class="space-y-3">
                        @php
                            $mockCustomers = [
                                ['initials' => 'RE', 'color' => 'bg-blue-50 text-blue-600 border-blue-200 dark:bg-blue-950 dark:text-blue-400', 'name' => 'Real Estate Lead', 'time' => 'Last contact: Today'],
                                ['initials' => 'TS', 'color' => 'bg-purple-50 text-purple-600 border-purple-200 dark:bg-purple-950 dark:text-purple-400', 'name' => 'Tech Support Deal', 'time' => 'Last contact: Today'],
                                ['initials' => 'GT', 'color' => 'bg-emerald-50 text-emerald-600 border-emerald-200 dark:bg-emerald-950 dark:text-emerald-400', 'name' => 'Global Traders Ltd', 'time' => 'Last contact: Yesterday'],
                                ['initials' => 'AL', 'color' => 'bg-amber-50 text-amber-600 border-amber-200 dark:bg-amber-950 dark:text-amber-400', 'name' => 'Apex Logistics', 'time' => 'Last contact: 2 days ago'],
                            ];
                        @endphp

                        @foreach ($mockCustomers as $c)
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl {{ $c['color'] }} border flex items-center justify-center font-bold text-xs shrink-0">
                                    {{ $c['initials'] }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h4 class="text-xs font-bold text-gray-600dark:text-white truncate">
                                        {{ $c['name'] }}
                                    </h4>
                                    <p class="text-[11px] text-slate-400">
                                        {{ $c['time'] }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- WIDGET 4: MOTIVATION CARD -->
                <div class="rounded-2xl bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-600 text-white p-5 shadow-sm space-y-2">
                    <div class="flex items-center gap-2">
                        <span class="text-lg">🎯</span>
                        <h4 class="text-sm font-bold">Small Steps Big Results!</h4>
                    </div>
                    <p class="text-xs font-medium text-white/80 leading-relaxed">
                        Complete 2 more activities to hit your daily goal.
                    </p>
                </div>

            </div>

        </div>

        <!-- ========================================================= -->
        <!-- 3B. VIEW 2: TRUE FULL-WIDTH CALENDAR WORKSPACE (100% WIDE)-->
        <!-- ========================================================= -->
        <div id="container-calendar-workspace" class="hidden w-full space-y-4">
            <!-- Full Calendar Header Card -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white dark:bg-gray-900 p-4 sm:p-5 rounded-2xl border border-slate-200/90 dark:border-gray-800 shadow-2xs">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 border border-blue-200 dark:border-blue-800 flex items-center justify-center font-bold text-lg shadow-2xs shrink-0">
                        📅
                    </div>
                    <div>
                        <h2 class="text-base sm:text-lg font-bold tracking-tight text-gray-800 dark:text-white flex items-center gap-2">
                            <span>Full View Calendar Workspace</span>
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        </h2>
                        <p class="text-xs font-medium text-slate-500 dark:text-slate-400">
                            Interactive month, week, day &amp; year scheduling agenda
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-2 sm:gap-3 flex-wrap sm:flex-nowrap">
                    <button
                        type="button"
                        onclick="window.openScheduleModal('{{ now()->format('Y-m-d') }}')"
                        class="primary-button !px-3 sm:!px-4 !py-2 !rounded-xl !text-xs font-bold shadow-sm cursor-pointer transition active:scale-95 flex items-center gap-1.5 bg-blue-600 hover:bg-blue-700 text-white shrink-0"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path d="M12 4v16m8-8H4" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                        <span>+ Schedule</span>
                    </button>

                    <button
                        type="button"
                        onclick="switchMainView('feed')"
                        class="px-3 sm:px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-slate-700 dark:text-slate-200 text-xs font-bold transition cursor-pointer flex items-center gap-1.5 shrink-0"
                    >
                        <span>✕</span>
                        <span><span class="hidden sm:inline">Return to </span>Feed</span>
                    </button>
                </div>
            </div>

            <!-- Full-Width Interactive Vue Component -->
            <div class="w-full">
                <v-activities-calendar
                    initial-month="{{ $calendarInitialMonth ?? now()->format('Y-m') }}"
                    :initial-activities='@json($calendarActivities ?? [], JSON_HEX_APOS)'
                    :users='@json($users ?? [], JSON_HEX_APOS)'
                    :leads='@json($leads ?? [], JSON_HEX_APOS)'
                    :current-user-id="{{ $currentUserId ?? 0 }}"
                ></v-activities-calendar>
            </div>
        </div>

    </div>

    <!-- ========================================================= -->
    <!-- 4. SCHEDULE ACTIVITY POPUP MODAL -->
    <!-- ========================================================= -->
    <div
        id="schedule-modal"
        class="fixed inset-0 z-[10005] flex items-center justify-center bg-slate-950/70 backdrop-blur-sm p-4 overflow-y-auto hidden transition-all duration-300"
        onclick="handleModalBackdropClick(event)"
    >
        <div
            id="schedule-modal-content"
            class="animate-modal-in bg-white dark:bg-gray-900 rounded-3xl border border-slate-200/90 dark:border-gray-800 max-w-xl w-full mx-auto my-auto shadow-2xl overflow-hidden flex flex-col"
            onclick="event.stopPropagation()"
        >
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-5 sm:p-6 border-b border-slate-100 dark:border-gray-800 bg-slate-50/50 dark:bg-gray-800/40">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-blue-600 text-white flex items-center justify-center font-bold text-lg shadow-md shadow-blue-500/20 shrink-0">
                        🗓️
                    </div>
                    <div>
                        <h3 class="text-base sm:text-lg font-bold tracking-tight text-gray-800 dark:text-white">
                            Schedule Activity
                        </h3>
                        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-0.5">
                            Add client meetings, calls, notes, or tasks
                        </p>
                    </div>
                </div>

                <button
                    type="button"
                    onclick="closeScheduleModal()"
                    class="w-9 h-9 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-200/70 dark:hover:bg-gray-800 dark:hover:text-white flex items-center justify-center transition cursor-pointer"
                    title="Close (Esc)"
                >
                    ✕
                </button>
            </div>

            <!-- Modal Form -->
            <form action="{{ route('admin.activities.store') }}" method="POST" id="schedule-activity-form" onsubmit="handleScheduleActivitySubmit(event)" class="p-5 sm:p-6 space-y-4">
                @csrf

                <!-- Activity Type Picker -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                        Touchpoint Category <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-4 gap-2">
                        <label class="cursor-pointer">
                            <input type="radio" name="type" value="call" checked class="peer sr-only">
                            <div class="p-2.5 rounded-xl border border-slate-200 dark:border-gray-700 text-center peer-checked:border-blue-500 peer-checked:bg-blue-50/70 dark:peer-checked:bg-blue-950/60 peer-checked:text-blue-700 dark:peer-checked:text-blue-300 transition text-xs font-bold flex flex-col items-center gap-1">
                                <span class="text-base">📞</span>
                                <span>Phone Call</span>
                            </div>
                        </label>

                        <label class="cursor-pointer">
                            <input type="radio" name="type" value="lunch" class="peer sr-only">
                            <div class="p-2.5 rounded-xl border border-slate-200 dark:border-gray-700 text-center peer-checked:border-amber-500 peer-checked:bg-amber-50/70 dark:peer-checked:bg-amber-950/60 peer-checked:text-amber-700 dark:peer-checked:text-amber-300 transition text-xs font-bold flex flex-col items-center gap-1">
                                <span class="text-base">🍽️</span>
                                <span>Luncheon</span>
                            </div>
                        </label>

                        <label class="cursor-pointer">
                            <input type="radio" name="type" value="meeting" class="peer sr-only">
                            <div class="p-2.5 rounded-xl border border-slate-200 dark:border-gray-700 text-center peer-checked:border-purple-500 peer-checked:bg-purple-50/70 dark:peer-checked:bg-purple-950/60 peer-checked:text-purple-700 dark:peer-checked:text-purple-300 transition text-xs font-bold flex flex-col items-center gap-1">
                                <span class="text-base">🏢</span>
                                <span>Site Tour</span>
                            </div>
                        </label>

                        <label class="cursor-pointer">
                            <input type="radio" name="type" value="note" class="peer sr-only">
                            <div class="p-2.5 rounded-xl border border-slate-200 dark:border-gray-700 text-center peer-checked:border-emerald-500 peer-checked:bg-emerald-50/70 dark:peer-checked:bg-emerald-950/60 peer-checked:text-emerald-700 dark:peer-checked:text-emerald-300 transition text-xs font-bold flex flex-col items-center gap-1">
                                <span class="text-base">📝</span>
                                <span>Task / Note</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Title & Client Name -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                        Activity Title / Subject <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        name="title"
                        id="modal-activity-title"
                        required
                        placeholder="e.g. Follow up on proposal with client"
                        class="w-full text-xs font-semibold border-slate-300 dark:border-gray-700 bg-slate-50 dark:bg-gray-800 text-gray-600dark:text-white rounded-xl px-3.5 py-2 focus:border-blue-500 shadow-2xs transition"
                    >
                </div>

                <!-- Link Deal / Lead -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                        Associate Deal / Lead (Optional)
                    </label>
                    <select
                        name="lead_id"
                        id="modal-activity-lead"
                        class="w-full text-xs font-bold border-slate-300 dark:border-gray-700 bg-slate-50 dark:bg-gray-800 text-gray-600dark:text-white rounded-xl px-3 py-2 focus:border-blue-500 shadow-2xs transition cursor-pointer"
                    >
                        <option value="">-- Standalone Activity / General Follow-up --</option>
                        @foreach($leads as $lead)
                            <option value="{{ $lead->id }}">
                                #{{ $lead->id }} · {{ $lead->title }} ({{ optional($lead->person)->name ?? 'Client' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Schedule From & To -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Start Date &amp; Time <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="datetime-local"
                            name="schedule_from"
                            id="modal-schedule-from"
                            required
                            value="{{ now()->format('Y-m-dTH:00') }}"
                            class="w-full text-xs font-bold border-slate-300 dark:border-gray-700 bg-slate-50 dark:bg-gray-800 text-gray-600dark:text-white rounded-xl px-3 py-2 focus:border-blue-500 shadow-2xs transition"
                        >
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                            End Date &amp; Time <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="datetime-local"
                            name="schedule_to"
                            id="modal-schedule-to"
                            required
                            value="{{ now()->addHour()->format('Y-m-dTH:00') }}"
                            class="w-full text-xs font-bold border-slate-300 dark:border-gray-700 bg-slate-50 dark:bg-gray-800 text-gray-600dark:text-white rounded-xl px-3 py-2 focus:border-blue-500 shadow-2xs transition"
                        >
                    </div>
                </div>

                <!-- Location -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                        Location / Venue
                    </label>
                    <input
                        type="text"
                        name="location"
                        id="modal-activity-location"
                        placeholder="e.g. Office HQ or Zoom"
                        class="w-full text-xs font-semibold border-slate-300 dark:border-gray-700 bg-slate-50 dark:bg-gray-800 text-gray-600dark:text-white rounded-xl px-3.5 py-2 focus:border-blue-500 shadow-2xs transition"
                    >
                </div>

                <!-- Discussion Comment / Agenda Notes -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                        Meeting Notes &amp; Agenda
                    </label>
                    <textarea
                        name="comment"
                        id="modal-activity-comment"
                        rows="2.5"
                        placeholder="Key client requirements, brochure copies, payment schedule discussion points..."
                        class="w-full text-xs font-medium border-slate-300 dark:border-gray-700 bg-slate-50 dark:bg-gray-800 text-gray-600dark:text-white rounded-xl p-3 focus:border-blue-500 resize-none shadow-2xs transition"
                    ></textarea>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100 dark:border-gray-800">
                    <button
                        type="button"
                        onclick="closeScheduleModal()"
                        class="px-4 py-2.5 rounded-xl border border-slate-300 dark:border-gray-700 text-xs font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-gray-800 transition cursor-pointer"
                    >
                        Cancel
                    </button>

                    <button
                        type="submit"
                        class="primary-button !px-6 !py-2.5 !rounded-xl !text-xs font-bold shadow-md cursor-pointer transition active:scale-95 bg-blue-600 hover:bg-blue-700 text-white"
                    >
                        Confirm &amp; Schedule
                    </button>
                </div>
            </form>
        </div>
    </div>

    @pushOnce('scripts')
        <script>
            // Switch main view: 'feed' or 'calendar'
            function switchMainView(mode) {
                const feedView = document.getElementById('container-feed-view');
                const calView = document.getElementById('container-calendar-workspace');
                const btnFeed = document.getElementById('btn-header-feed');
                const btnCal = document.getElementById('btn-header-calendar');
                const btnSubCal = document.getElementById('btn-sub-calendar-toggle');
                const metricsCards = document.getElementById('activities-metrics-section');

                const activeHeaderClass = 'px-3.5 py-1.5 rounded-xl bg-white dark:bg-gray-900 shadow-2xs text-xs font-bold text-blue-600 dark:text-blue-400 flex items-center gap-1.5 transition cursor-pointer';
                const inactiveHeaderClass = 'px-3.5 py-1.5 rounded-xl text-xs font-bold text-slate-600 dark:text-slate-300 hover:text-gray-800 dark:hover:text-white flex items-center gap-1.5 transition cursor-pointer';

                if (mode === 'calendar') {
                    if (feedView) feedView.classList.add('hidden');
                    if (calView) calView.classList.remove('hidden');
                    if (metricsCards) metricsCards.classList.add('hidden');
                    if (btnFeed) btnFeed.className = inactiveHeaderClass;
                    if (btnCal) btnCal.className = activeHeaderClass;
                    if (btnSubCal) {
                        btnSubCal.className = 'px-3.5 py-2 rounded-xl bg-blue-50 text-blue-700 dark:bg-blue-950 dark:text-blue-300 border border-blue-300 dark:border-blue-700 text-xs font-bold transition cursor-pointer flex items-center gap-1.5 shadow-2xs';
                    }

                    setTimeout(() => {
                        window.dispatchEvent(new Event('resize'));
                    }, 60);
                } else {
                    if (calView) calView.classList.add('hidden');
                    if (feedView) feedView.classList.remove('hidden');
                    if (metricsCards) metricsCards.classList.remove('hidden');
                    if (btnFeed) btnFeed.className = activeHeaderClass;
                    if (btnCal) btnCal.className = inactiveHeaderClass;
                    if (btnSubCal) {
                        btnSubCal.className = 'px-3.5 py-2 rounded-xl bg-slate-50 hover:bg-slate-100 dark:bg-gray-800 dark:hover:bg-gray-700 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-gray-700 text-xs font-bold transition cursor-pointer flex items-center gap-1.5 shadow-2xs';
                    }

                    setTimeout(() => {
                        window.dispatchEvent(new Event('resize'));
                    }, 60);
                }
            }

            // Filter Feed Activities by Category / Type
            function filterActivitiesByType(type, btn) {
                // If currently in calendar view, switch back to feed view
                switchMainView('feed');

                document.querySelectorAll('.filter-pill-btn').forEach(b => {
                    b.className = 'filter-pill-btn px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-slate-700 dark:text-slate-200 text-xs font-bold transition cursor-pointer';
                });
                if (btn) {
                    btn.className = 'filter-pill-btn px-3.5 py-2 rounded-xl bg-blue-600 text-white text-xs font-bold shadow-2xs transition cursor-pointer';
                }

                document.querySelectorAll('.activity-card').forEach(card => {
                    const cardType = card.getAttribute('data-type');
                    const isDone = card.getAttribute('data-done');

                    if (type === 'all') {
                        card.style.display = '';
                    } else if (type === 'done') {
                        card.style.display = isDone === '1' ? '' : 'none';
                    } else if (type === 'pending') {
                        card.style.display = isDone === '0' ? '' : 'none';
                    } else {
                        card.style.display = cardType === type ? '' : 'none';
                    }
                });

                updateGroupVisibilities();
            }

            function filterActivitiesByTypeSelect(type) {
                filterActivitiesByType(type, null);
            }

            function filterActivitiesByPeriod(period) {
                switchMainView('feed');

                const groupToday = document.getElementById('group-today');
                const groupYesterday = document.getElementById('group-yesterday');
                const groupUpcoming = document.getElementById('group-upcoming');

                if (period === 'all') {
                    if (groupToday) groupToday.style.display = '';
                    if (groupYesterday) groupYesterday.style.display = '';
                    if (groupUpcoming) groupUpcoming.style.display = '';
                } else if (period === 'today') {
                    if (groupToday) groupToday.style.display = '';
                    if (groupYesterday) groupYesterday.style.display = 'none';
                    if (groupUpcoming) groupUpcoming.style.display = 'none';
                } else if (period === 'yesterday') {
                    if (groupToday) groupToday.style.display = 'none';
                    if (groupYesterday) groupYesterday.style.display = '';
                    if (groupUpcoming) groupUpcoming.style.display = 'none';
                } else if (period === 'upcoming') {
                    if (groupToday) groupToday.style.display = 'none';
                    if (groupYesterday) groupYesterday.style.display = 'none';
                    if (groupUpcoming) groupUpcoming.style.display = '';
                }
            }

            // Live Search Filter across activities feed
            function filterActivitiesBySearch(query) {
                switchMainView('feed');

                const q = query.toLowerCase().trim();
                document.querySelectorAll('.activity-card').forEach(card => {
                    const text = card.textContent.toLowerCase();
                    if (!q || text.includes(q)) {
                        card.style.display = '';
                    } else {
                        card.style.display = 'none';
                    }
                });

                updateGroupVisibilities();
            }

            function updateGroupVisibilities() {
                document.querySelectorAll('.activity-date-group').forEach(group => {
                    const visibleCards = group.querySelectorAll('.activity-card:not([style*="display: none"])');
                    group.style.display = visibleCards.length > 0 ? '' : 'none';
                });
            }

            // 1-Click Interactive Activity Completion Toggle
            function toggleActivityStatus(activityId, currentStatus, btn) {
                const newStatus = currentStatus === 1 ? 0 : 1;
                btn.disabled = true;

                axios.put(`{{ url('admin/activities/edit') }}/${activityId}`, {
                    is_done: newStatus,
                }, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(res => {
                    const card = document.getElementById(`activity-card-${activityId}`);
                    const statusPill = document.getElementById(`status-pill-${activityId}`);
                    
                    if (newStatus === 1) {
                        btn.className = 'w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center shadow-xs transition-colors cursor-pointer';
                        btn.innerHTML = '<svg class="w-4 h-4 stroke-white stroke-[2.5]" fill="none" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"></path></svg>';
                        if (statusPill) {
                            statusPill.className = 'text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800';
                            statusPill.textContent = 'Completed';
                        }
                        btn.setAttribute('onclick', `toggleActivityStatus(${activityId}, 1, this)`);
                        if (card) card.setAttribute('data-done', '1');
                    } else {
                        btn.className = 'w-8 h-8 rounded-xl border border-slate-200 hover:border-blue-500 hover:bg-blue-50 text-slate-400 dark:border-gray-700 flex items-center justify-center transition-colors cursor-pointer';
                        btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"></circle></svg>';
                        if (statusPill) {
                            statusPill.className = 'text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-700 dark:bg-blue-950/60 dark:text-blue-300 border border-blue-200 dark:border-blue-800';
                            statusPill.textContent = 'Scheduled';
                        }
                        btn.setAttribute('onclick', `toggleActivityStatus(${activityId}, 0, this)`);
                        if (card) card.setAttribute('data-done', '0');
                    }
                    btn.disabled = false;
                })
                .catch(err => {
                    btn.disabled = false;
                });
            }

            // Modal Handlers
            function adjustModalSidebarOffset() {
                const modal = document.getElementById('schedule-modal');
                if (!modal) return;

                if (window.innerWidth >= 1024) {
                    const sidebar = document.querySelector('aside') || 
                                    document.querySelector('.group\\/container > aside') || 
                                    document.querySelector('[ref="sidebar"]') ||
                                    document.querySelector('[ref="appLayout"] > div:first-child');
                    
                    let sidebarWidth = 215;
                    if (sidebar) {
                        const rect = sidebar.getBoundingClientRect();
                        if (rect.width > 0) sidebarWidth = rect.width;
                    } else if (document.querySelector('.sidebar-collapsed')) {
                        sidebarWidth = 85;
                    }

                    modal.style.paddingLeft = sidebarWidth + 'px';
                    modal.style.paddingRight = '1.5rem';
                } else {
                    modal.style.paddingLeft = '1rem';
                    modal.style.paddingRight = '1rem';
                }
            }

            window.openScheduleModal = function(prefillDate) {
                const modal = document.getElementById('schedule-modal');
                if (modal) {
                    adjustModalSidebarOffset();

                    if (prefillDate) {
                        const fromInput = document.getElementById('modal-schedule-from');
                        const toInput = document.getElementById('modal-schedule-to');
                        if (fromInput) fromInput.value = `${prefillDate}T10:00`;
                        if (toInput) toInput.value = `${prefillDate}T11:30`;
                    }

                    modal.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                }
            };

            window.closeScheduleModal = function() {
                const modal = document.getElementById('schedule-modal');
                if (modal) {
                    modal.classList.add('hidden');
                    document.body.style.overflow = '';
                }
            };

            function handleModalBackdropClick(event) {
                if (event.target.id === 'schedule-modal') {
                    closeScheduleModal();
                }
            }

            window.addEventListener('resize', () => {
                const modal = document.getElementById('schedule-modal');
                if (modal && !modal.classList.contains('hidden')) {
                    adjustModalSidebarOffset();
                }
            });

            async function handleScheduleActivitySubmit(e) {
                e.preventDefault();
                const form = e.target;
                const submitBtn = form.querySelector('button[type="submit"]');
                const origBtnHtml = submitBtn ? submitBtn.innerHTML : '';

                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<span class="inline-block animate-spin mr-1.5">⟳</span> Scheduling...';
                }

                const formData = new FormData(form);
                const data = {};
                formData.forEach((value, key) => {
                    data[key] = value;
                });

                try {
                    const res = await axios.post("{{ route('admin.activities.store') }}", data, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });

                    const created = (res.data && (res.data.activity || res.data.data)) || null;

                    if (created) {
                        // Dispatch event for reactive calendar marking across Month, Week, Day, Year, and Mini Calendar
                        window.dispatchEvent(new CustomEvent('activity:created', { detail: created }));
                    }

                    closeScheduleModal();
                    form.reset();

                    // Optional toast indicator
                    const toast = document.createElement('div');
                    toast.className = 'fixed bottom-5 right-5 z-[10006] bg-emerald-600 text-white px-4 py-2.5 rounded-2xl shadow-xl flex items-center gap-2 text-xs font-bold transition-all duration-300';
                    toast.innerHTML = '<span>✓</span><span>Activity scheduled and marked on calendar!</span>';
                    document.body.appendChild(toast);
                    setTimeout(() => {
                        toast.style.opacity = '0';
                        setTimeout(() => toast.remove(), 300);
                    }, 3000);

                } catch (err) {
                    console.error('Failed to schedule activity:', err);
                    let msg = 'Failed to schedule activity. Please check required fields.';
                    if (err.response && err.response.data) {
                        if (err.response.data.message) {
                            msg = err.response.data.message;
                        } else if (err.response.data.errors) {
                            msg = Object.values(err.response.data.errors).flat().join('\n');
                        }
                    }
                    alert(msg);
                } finally {
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = origBtnHtml;
                    }
                }
            }

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' || e.key === 'Esc') {
                    closeScheduleModal();
                }
            });
        </script>
    @endPushOnce
</x-admin::layouts>
