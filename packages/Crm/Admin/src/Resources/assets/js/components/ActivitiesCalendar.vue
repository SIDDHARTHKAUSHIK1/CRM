<template>
    <div id="activities-calendar-root" ref="calendarContainerRef" class="flex flex-col lg:flex-row h-[calc(100vh-140px)] sm:h-[calc(100vh-170px)] lg:h-[calc(100vh-190px)] min-h-[520px] sm:min-h-[580px] lg:min-h-[660px] bg-white dark:bg-gray-900 rounded-2xl border border-slate-200 dark:border-gray-800 shadow-sm overflow-hidden text-slate-800 dark:text-slate-100 font-sans select-none relative w-full">
        
        <!-- ========================================================= -->
        <!-- 1. LEFT SIDEBAR (Desktop: Visible on lg+ screens)          -->
        <!-- ========================================================= -->
        <aside class="hidden lg:flex w-72 xl:w-80 bg-slate-900 text-white flex-col shrink-0 border-r border-slate-800 h-full overflow-hidden select-none">
            
            <!-- Window Traffic Lights & Quick Add -->
            <div class="px-5 pt-4 pb-2 flex items-center justify-between border-b border-slate-800/80">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-red-500/90 inline-block shadow-xs"></span>
                    <span class="w-3 h-3 rounded-full bg-amber-500/90 inline-block shadow-xs"></span>
                    <span class="w-3 h-3 rounded-full bg-emerald-500/90 inline-block shadow-xs"></span>
                </div>
                <button
                    type="button"
                    @click="openAddModal(selectedDate)"
                    title="Schedule New Activity"
                    class="w-7 h-7 rounded-lg bg-slate-800 hover:bg-orange-500 text-slate-300 hover:text-white flex items-center justify-center text-sm font-bold transition shadow-xs cursor-pointer"
                >
                    +
                </button>
            </div>

            <!-- Mini Month Navigator Header -->
            <div class="px-5 py-3 flex items-center justify-between">
                <div class="flex items-baseline gap-1.5">
                    <h2 class="text-base font-bold tracking-tight text-white">
                        {{ monthNames[miniCalendarMonth - 1] }}
                    </h2>
                    <span class="text-base font-bold text-red-500">
                        {{ miniCalendarYear }}
                    </span>
                </div>

                <div class="flex items-center gap-1">
                    <button
                        type="button"
                        @click="navigateMiniMonth(-1)"
                        class="w-6 h-6 rounded-md text-slate-400 hover:text-white hover:bg-slate-800 flex items-center justify-center transition"
                        title="Previous Month"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>
                    <button
                        type="button"
                        @click="navigateMiniMonth(1)"
                        class="w-6 h-6 rounded-md text-slate-400 hover:text-white hover:bg-slate-800 flex items-center justify-center transition"
                        title="Next Month"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mini Calendar 7-Col Grid (Native Vue) -->
            <div class="px-3 pb-3 border-b border-slate-800/80">
                <!-- Weekday initials S M T W T F S -->
                <div class="grid grid-cols-7 gap-1 text-center text-[10px] font-bold text-slate-400 mb-1.5 uppercase">
                    <span>S</span><span>M</span><span>T</span><span>W</span><span>T</span><span>F</span><span>S</span>
                </div>
                <!-- 42 Days Cells -->
                <div class="grid grid-cols-7 gap-1">
                    <button
                        v-for="cell in miniCalendarCells"
                        :key="cell.dateStr"
                        type="button"
                        @click.stop="onMiniCellClick(cell, $event)"
                        :class="[
                            'h-7 rounded-lg flex flex-col items-center justify-center text-[11px] font-semibold transition relative group cursor-pointer p-0.5 date-preview-trigger',
                            cell.isToday
                                ? 'bg-orange-500 text-white font-bold shadow-xs'
                                : cell.dateStr === selectedDate
                                    ? 'bg-slate-700 text-white font-bold'
                                    : cell.isOtherMonth
                                        ? 'text-slate-600 opacity-40'
                                        : 'text-slate-300 hover:bg-slate-800 hover:text-white'
                        ]"
                        :title="`${cell.events.length} activities on ${cell.dateStr}`"
                    >
                        <span class="leading-none">{{ cell.dayNumber }}</span>
                        <!-- Dots for activities on that date -->
                        <div v-if="cell.events && cell.events.length" class="h-1 flex items-center justify-center gap-0.5 mt-0.5">
                            <span
                                v-for="(ev, idx) in cell.events.slice(0, 3)"
                                :key="idx"
                                class="w-1 h-1 rounded-full shrink-0"
                                :style="{ backgroundColor: (cell.isToday || cell.dateStr === selectedDate) ? '#ffffff' : getEventDotColor(ev.type) }"
                            ></span>
                        </div>
                    </button>
                </div>
            </div>

            <!-- Scrollable Upcoming Events List -->
            <div class="flex-1 overflow-y-auto px-4 py-3 space-y-4 custom-dark-scrollbar">
                
                <div v-if="upcomingGroupedDates.length === 0" class="py-8 text-center text-slate-500 text-xs">
                    <p class="font-medium">No upcoming activities</p>
                    <button
                        type="button"
                        @click="openAddModal(selectedDate)"
                        class="mt-2 text-[11px] font-bold text-orange-400 hover:underline"
                    >
                        + Schedule an activity
                    </button>
                </div>

                <div
                    v-for="group in upcomingGroupedDates"
                    :key="group.dateStr"
                    class="space-y-2"
                >
                    <!-- Date Group Header -->
                    <div class="flex items-center justify-between text-xs font-bold tracking-wide">
                        <span :class="group.isToday ? 'text-blue-400' : 'text-slate-300'">
                            {{ group.label }}
                        </span>
                        <span class="text-[11px] text-slate-400 flex items-center gap-1">
                            {{ group.temp }}
                            <span>{{ group.weatherIcon }}</span>
                        </span>
                    </div>

                    <!-- Event Cards inside Group -->
                    <div class="space-y-1.5">
                        <div
                            v-for="(event, eIdx) in group.events"
                            :key="event.id"
                            @click="selectEvent(event)"
                            :class="[
                                'p-2.5 rounded-xl transition cursor-pointer text-xs border border-transparent',
                                group.isToday && eIdx === 0
                                    ? 'bg-gradient-to-r from-purple-900/60 to-purple-800/40 border-purple-500/40 hover:border-purple-400'
                                    : 'bg-slate-800/70 hover:bg-slate-800 border-slate-700/50 hover:border-slate-600'
                            ]"
                        >
                            <!-- Featured Badge for first today event if applicable -->
                            <div v-if="group.isToday && eIdx === 0" class="mb-1.5">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-purple-500 text-white text-[10px] font-bold uppercase tracking-wider shadow-xs">
                                    {{ event.title }}
                                </span>
                            </div>

                            <div class="flex items-start gap-2">
                                <span
                                    class="w-2 h-2 rounded-full mt-1 shrink-0"
                                    :style="{ backgroundColor: getEventDotColor(event.type) }"
                                ></span>

                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between gap-1 text-[11px]">
                                        <span class="font-bold text-slate-200">
                                            {{ formatTimeRange(event.schedule_from, event.schedule_to) }}
                                        </span>
                                        <span class="text-xs shrink-0" :title="event.type">
                                            {{ typeEmoji[event.type] || '📌' }}
                                        </span>
                                    </div>

                                    <h4
                                        v-if="!(group.isToday && eIdx === 0)"
                                        class="font-semibold text-slate-200 text-xs truncate mt-0.5"
                                    >
                                        {{ event.title }}
                                    </h4>

                                    <p v-if="event.comment" class="text-[11px] text-slate-400 truncate mt-0.5">
                                        {{ event.comment }}
                                    </p>

                                    <div v-if="event.location" class="text-[10px] text-slate-400 flex items-center gap-1 mt-1 truncate">
                                        <span>📍</span>
                                        <span class="truncate">{{ event.location }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </aside>

        <!-- ========================================================= -->
        <!-- 1B. MOBILE / TABLET SLIDE-OVER DRAWER (< lg)              -->
        <!-- ========================================================= -->
        <transition
            enter-active-class="transition-opacity ease-out duration-200"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition-opacity ease-in duration-150"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="showMobileSidebar"
                class="lg:hidden fixed inset-0 z-50 bg-slate-950/70 backdrop-blur-xs flex"
                @click="showMobileSidebar = false"
            >
                <div
                    class="w-72 sm:w-80 max-w-[85vw] bg-slate-900 text-white flex flex-col h-full shadow-2xl border-r border-slate-800 overflow-hidden animate-modal-in"
                    @click.stop
                >
                    <!-- Mobile Drawer Header -->
                    <div class="px-4 py-3 bg-slate-950/90 border-b border-slate-800 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="text-sm">📅</span>
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-200">Mini Calendar &amp; Agenda</span>
                        </div>
                        <button
                            type="button"
                            @click="showMobileSidebar = false"
                            class="w-7 h-7 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 flex items-center justify-center font-bold text-sm transition cursor-pointer"
                        >
                            ✕
                        </button>
                    </div>

                    <!-- Mini Month Navigator Header -->
                    <div class="px-4 py-3 flex items-center justify-between">
                        <div class="flex items-baseline gap-1.5">
                            <h2 class="text-base font-bold tracking-tight text-white">
                                {{ monthNames[miniCalendarMonth - 1] }}
                            </h2>
                            <span class="text-base font-bold text-red-500">
                                {{ miniCalendarYear }}
                            </span>
                        </div>

                        <div class="flex items-center gap-1">
                            <button
                                type="button"
                                @click="navigateMiniMonth(-1)"
                                class="w-6 h-6 rounded-md text-slate-400 hover:text-white hover:bg-slate-800 flex items-center justify-center transition cursor-pointer"
                                title="Previous Month"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </button>
                            <button
                                type="button"
                                @click="navigateMiniMonth(1)"
                                class="w-6 h-6 rounded-md text-slate-400 hover:text-white hover:bg-slate-800 flex items-center justify-center transition cursor-pointer"
                                title="Next Month"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Mini Calendar 7-Col Grid -->
                    <div class="px-3 pb-3 border-b border-slate-800/80">
                        <div class="grid grid-cols-7 gap-1 text-center text-[10px] font-bold text-slate-400 mb-1.5 uppercase">
                            <span>S</span><span>M</span><span>T</span><span>W</span><span>T</span><span>F</span><span>S</span>
                        </div>
                        <div class="grid grid-cols-7 gap-1">
                            <button
                                v-for="cell in miniCalendarCells"
                                :key="cell.dateStr"
                                type="button"
                                @click.stop="onMiniCellClick(cell, $event)"
                                :class="[
                                    'h-7 rounded-lg flex flex-col items-center justify-center text-[11px] font-semibold transition relative group cursor-pointer p-0.5 date-preview-trigger',
                                    cell.isToday
                                        ? 'bg-orange-500 text-white font-bold shadow-xs'
                                        : cell.dateStr === selectedDate
                                            ? 'bg-slate-700 text-white font-bold'
                                            : cell.isOtherMonth
                                                ? 'text-slate-600 opacity-40'
                                                : 'text-slate-300 hover:bg-slate-800 hover:text-white'
                                ]"
                                :title="`${cell.events.length} activities on ${cell.dateStr}`"
                            >
                                <span class="leading-none">{{ cell.dayNumber }}</span>
                                <div v-if="cell.events && cell.events.length" class="h-1 flex items-center justify-center gap-0.5 mt-0.5">
                                    <span
                                        v-for="(ev, idx) in cell.events.slice(0, 3)"
                                        :key="idx"
                                        class="w-1 h-1 rounded-full shrink-0"
                                        :style="{ backgroundColor: (cell.isToday || cell.dateStr === selectedDate) ? '#ffffff' : getEventDotColor(ev.type) }"
                                    ></span>
                                </div>
                            </button>
                        </div>
                    </div>

                    <!-- Scrollable Upcoming Events List -->
                    <div class="flex-1 overflow-y-auto px-4 py-3 space-y-4 custom-dark-scrollbar">
                        <div v-if="upcomingGroupedDates.length === 0" class="py-8 text-center text-slate-500 text-xs">
                            <p class="font-medium">No upcoming activities</p>
                            <button
                                type="button"
                                @click="openAddModal(selectedDate)"
                                class="mt-2 text-[11px] font-bold text-orange-400 hover:underline"
                            >
                                + Schedule an activity
                            </button>
                        </div>

                        <div
                            v-for="group in upcomingGroupedDates"
                            :key="group.dateStr"
                            class="space-y-2"
                        >
                            <div class="flex items-center justify-between text-xs font-bold tracking-wide">
                                <span :class="group.isToday ? 'text-blue-400' : 'text-slate-300'">
                                    {{ group.label }}
                                </span>
                                <span class="text-[11px] text-slate-400 flex items-center gap-1">
                                    {{ group.temp }}
                                    <span>{{ group.weatherIcon }}</span>
                                </span>
                            </div>

                            <div class="space-y-1.5">
                                <div
                                    v-for="(event, eIdx) in group.events"
                                    :key="event.id"
                                    @click="selectEvent(event)"
                                    :class="[
                                        'p-2.5 rounded-xl transition cursor-pointer text-xs border border-transparent',
                                        group.isToday && eIdx === 0
                                            ? 'bg-gradient-to-r from-purple-900/60 to-purple-800/40 border-purple-500/40 hover:border-purple-400'
                                            : 'bg-slate-800/70 hover:bg-slate-800 border-slate-700/50 hover:border-slate-600'
                                    ]"
                                >
                                    <div v-if="group.isToday && eIdx === 0" class="mb-1.5">
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-purple-500 text-white text-[10px] font-bold uppercase tracking-wider shadow-xs">
                                            {{ event.title }}
                                        </span>
                                    </div>

                                    <div class="flex items-start gap-2">
                                        <span
                                            class="w-2 h-2 rounded-full mt-1 shrink-0"
                                            :style="{ backgroundColor: getEventDotColor(event.type) }"
                                        ></span>

                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between gap-1 text-[11px]">
                                                <span class="font-bold text-slate-200">
                                                    {{ formatTimeRange(event.schedule_from, event.schedule_to) }}
                                                </span>
                                                <span
                                                    :class="[
                                                        'px-1.5 py-0.5 rounded text-[9px] font-bold uppercase',
                                                        event.is_done ? 'bg-emerald-950 text-emerald-400' : 'bg-slate-700 text-slate-300'
                                                    ]"
                                                >
                                                    {{ event.type }}
                                                </span>
                                            </div>

                                            <p class="font-semibold text-white truncate mt-0.5">
                                                {{ event.title }}
                                            </p>

                                            <div class="flex items-center gap-2 mt-1 text-[10px] text-slate-400 truncate">
                                                <span v-if="event.lead_title" class="truncate">
                                                    🏢 {{ event.lead_title }}
                                                </span>
                                                <span v-else-if="event.location" class="truncate">
                                                    📍 {{ event.location }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </transition>

        <!-- ========================================================= -->
        <!-- 2. MAIN CALENDAR AREA (Right Side)                        -->
        <!-- ========================================================= -->
        <main class="flex-1 flex flex-col bg-white dark:bg-gray-900 min-w-0 h-full overflow-hidden relative">
            
            <!-- Top Navigation Bar -->
            <header class="min-h-14 sm:h-16 px-3 sm:px-6 py-2 sm:py-0 border-b border-slate-200 dark:border-gray-800 flex flex-wrap sm:flex-nowrap items-center justify-between gap-2 sm:gap-4 bg-white/95 dark:bg-gray-900/95 backdrop-blur-sm z-20 shrink-0">
                
                <!-- Left Nav: Toggle (<lg), Prev / Next / Today / Title -->
                <div class="flex items-center gap-1.5 sm:gap-3 shrink-0">
                    <!-- Mobile / Tablet Drawer Toggle Button -->
                    <button
                        type="button"
                        @click="showMobileSidebar = !showMobileSidebar"
                        class="lg:hidden inline-flex items-center gap-1.5 px-2.5 sm:px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-slate-700 dark:text-slate-200 text-xs font-bold transition shadow-2xs cursor-pointer shrink-0"
                        title="Open Mini Calendar &amp; Agenda"
                    >
                        <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2" stroke-width="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6" stroke-width="2"></line>
                            <line x1="8" y1="2" x2="8" y2="6" stroke-width="2"></line>
                            <line x1="3" y1="10" x2="21" y2="10" stroke-width="2"></line>
                        </svg>
                        <span class="hidden sm:inline">Agenda</span>
                    </button>

                    <div class="flex items-center bg-slate-100 dark:bg-gray-800 rounded-xl p-0.5 border border-slate-200 dark:border-gray-700">
                        <button
                            type="button"
                            @click="navigatePeriod(-1)"
                            class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg text-slate-600 dark:text-slate-300 hover:bg-white dark:hover:bg-gray-700 flex items-center justify-center transition cursor-pointer shadow-2xs"
                            title="Previous"
                        >
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>

                        <button
                            type="button"
                            @click="goToToday"
                            class="px-2 sm:px-3 h-7 sm:h-8 rounded-lg text-[11px] sm:text-xs font-bold text-slate-700 dark:text-slate-200 hover:bg-white dark:hover:bg-gray-700 transition cursor-pointer shadow-2xs"
                        >
                            Today
                        </button>

                        <button
                            type="button"
                            @click="navigatePeriod(1)"
                            class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg text-slate-600 dark:text-slate-300 hover:bg-white dark:hover:bg-gray-700 flex items-center justify-center transition cursor-pointer shadow-2xs"
                            title="Next"
                        >
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </div>

                    <h1 class="text-xs sm:text-base lg:text-lg font-bold tracking-tight text-gray-800 dark:text-white truncate max-w-[110px] xs:max-w-[160px] sm:max-w-none">
                        {{ periodTitle }}
                    </h1>
                </div>

                <!-- Right Nav: View Switcher, Quick Add on mobile, Search, Timezone -->
                <div class="flex items-center gap-1.5 sm:gap-3">
                    
                    <!-- View Switcher Segmented Control -->
                    <div class="flex items-center bg-slate-100 dark:bg-gray-800 p-0.5 sm:p-1 rounded-xl border border-slate-200 dark:border-gray-700">
                        <button
                            v-for="view in ['day', 'week', 'month', 'year']"
                            :key="view"
                            type="button"
                            @click="setView(view)"
                            :class="[
                                'px-2 sm:px-3 py-1 sm:py-1.5 rounded-lg text-[11px] sm:text-xs font-bold capitalize transition-all cursor-pointer',
                                currentView === view
                                    ? 'bg-red-500 text-white shadow-xs'
                                    : 'text-slate-600 dark:text-slate-400 hover:text-gray-800 dark:hover:text-white'
                            ]"
                        >
                            {{ view }}
                        </button>
                    </div>

                    <!-- Quick Add button visible on mobile (< md) in calendar header -->
                    <button
                        type="button"
                        @click="openAddModal(selectedDate)"
                        class="md:hidden w-7 h-7 sm:w-8 sm:h-8 rounded-xl bg-orange-500 hover:bg-orange-600 text-white flex items-center justify-center text-sm font-bold shadow-xs cursor-pointer transition"
                        title="Schedule Activity"
                    >
                        +
                    </button>

                    <!-- Client-Side Search Input -->
                    <div class="relative hidden md:block w-36 lg:w-56">
                        <input
                            type="text"
                            v-model="searchQuery"
                            placeholder="Search..."
                            class="w-full pl-8 pr-3 py-1.5 text-xs font-semibold bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-xl text-gray-800 dark:text-white placeholder-slate-400 focus:outline-none focus:border-orange-500 transition"
                        />
                        <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <button
                            v-if="searchQuery"
                            @click="searchQuery = ''"
                            class="absolute right-2 top-2 text-xs text-slate-400 hover:text-slate-600"
                        >
                            ✕
                        </button>
                    </div>

                    <!-- Timezone Pill -->
                    <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400 hidden xl:inline-block bg-slate-100 dark:bg-gray-800 px-2.5 py-1 rounded-lg border border-slate-200/80 dark:border-gray-700">
                        IST GMT+5:30
                    </span>

                </div>

            </header>

            <!-- ===================================================== -->
            <!-- 3. VIEWS CONTAINER                                    -->
            <!-- ===================================================== -->
            <div class="flex-1 overflow-auto relative bg-white dark:bg-gray-900">
                
                <!-- Loading Overlay -->
                <div v-if="isLoading" class="absolute inset-0 bg-white/70 dark:bg-gray-900/70 backdrop-blur-2xs z-30 flex items-center justify-center">
                    <div class="flex items-center gap-2 px-4 py-2 rounded-xl bg-white dark:bg-gray-800 shadow-xl border border-slate-200 dark:border-gray-700 text-xs font-bold text-slate-700 dark:text-slate-200">
                        <svg class="animate-spin h-4 w-4 text-orange-500" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>Loading activities...</span>
                    </div>
                </div>

                <!-- ================================================= -->
                <!-- 3A. WEEK VIEW (Native Vue 7-Day Grid)             -->
                <!-- ================================================= -->
                <div v-if="currentView === 'week'" class="flex flex-col h-full overflow-y-auto overflow-x-auto custom-scrollbar">
                    
                    <div class="min-w-[720px] lg:min-w-full flex flex-col min-h-full">
                        <!-- Week Sticky Header (7 Day Columns) -->
                        <div class="sticky top-0 z-20 bg-slate-50/95 dark:bg-gray-800/95 backdrop-blur-xs border-b border-slate-200 dark:border-gray-800 shadow-2xs">
                        <div class="grid grid-cols-7 divide-x divide-slate-200 dark:divide-gray-800">
                            <div
                                v-for="day in weekDays"
                                :key="day.dateStr"
                                @click="jumpToDay(day.dateStr)"
                                :class="[
                                    'py-2.5 px-1.5 sm:px-2 text-center transition cursor-pointer group',
                                    day.isToday ? 'bg-orange-50/60 dark:bg-orange-950/20' : 'hover:bg-slate-100/70 dark:hover:bg-gray-700/50'
                                ]"
                            >
                                <span class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-200">
                                    {{ day.dayName }}
                                </span>
                                
                                <div class="mt-1 flex items-center justify-center gap-1.5">
                                    <span
                                        :class="[
                                            'w-7 h-7 rounded-full text-xs font-bold flex items-center justify-center transition shadow-2xs',
                                            day.isToday
                                                ? 'bg-orange-500 text-white shadow-orange-500/30 ring-2 ring-orange-200 dark:ring-orange-900'
                                                : day.dateStr === selectedDate
                                                    ? 'bg-slate-800 dark:bg-slate-600 text-white'
                                                    : 'text-slate-800 dark:text-slate-100 group-hover:bg-slate-200 dark:group-hover:bg-gray-700'
                                        ]"
                                    >
                                        {{ day.dayNumber }}
                                    </span>
                                </div>

                                <div class="mt-1 flex items-center justify-center">
                                    <span
                                        v-if="day.events.length"
                                        class="px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-200/80 dark:bg-gray-700 text-slate-700 dark:text-slate-200"
                                    >
                                        {{ day.events.length }} {{ day.events.length === 1 ? 'act' : 'acts' }}
                                    </span>
                                    <span v-else class="text-[10px] text-slate-300 dark:text-slate-600 font-medium">
                                        –
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- All-Day / Off-Hours Row (if any exist) -->
                    <div v-if="weekDays.some(d => getNonHourEvents(d.events).length)" class="bg-slate-100/60 dark:bg-gray-800/40 border-b border-slate-200 dark:border-gray-800 grid grid-cols-7 divide-x divide-slate-200 dark:divide-gray-800">
                        <div
                            v-for="day in weekDays"
                            :key="'non-hour-' + day.dateStr"
                            class="p-1.5 space-y-1"
                        >
                            <div
                                v-for="ev in getNonHourEvents(day.events)"
                                :key="ev.id"
                                @click.stop="selectEvent(ev)"
                                :class="[
                                    'px-2 py-1 rounded-lg text-[10px] font-bold truncate flex items-center gap-1 cursor-pointer transition shadow-2xs border',
                                    getMonthEventPillClasses(ev.type, ev.is_done)
                                ]"
                                :title="`${ev.title} (${ev.schedule_from})`"
                            >
                                <span>{{ typeEmoji[ev.type] || '📌' }}</span>
                                <span class="truncate">{{ ev.title }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Week Grid Body with Hourly Slots (7 AM - 9 PM) -->
                    <div class="flex-1 divide-y divide-slate-100 dark:divide-gray-800/80">
                        <div
                            v-for="hour in hourSlots"
                            :key="hour"
                            class="grid grid-cols-7 divide-x divide-slate-100 dark:divide-gray-800 min-h-[82px]"
                        >
                            <div
                                v-for="day in weekDays"
                                :key="day.dateStr"
                                class="p-1.5 relative group hover:bg-blue-50/20 dark:hover:bg-blue-950/10 transition flex flex-col justify-between"
                            >
                                <!-- Event Cards in this Hour -->
                                <div class="space-y-1.5 flex-1">
                                    <div
                                        v-for="ev in getEventsForHour(day.events, hour)"
                                        :key="ev.id"
                                        @click.stop="selectEvent(ev)"
                                        :class="[
                                            'p-2 rounded-xl text-left shadow-2xs border transition cursor-pointer hover:shadow-md hover:scale-[1.01]',
                                            getWeekEventCardClasses(ev.type, ev.is_done)
                                        ]"
                                        :title="`${formatTimeRange(ev.schedule_from, ev.schedule_to)} · ${ev.title}`"
                                    >
                                        <div class="flex items-center justify-between gap-1 mb-1">
                                            <span class="text-[10px] font-bold flex items-center gap-1">
                                                <span>{{ typeEmoji[ev.type] || '📌' }}</span>
                                                <span>{{ formatTime(ev.schedule_from) }}</span>
                                            </span>
                                            <span v-if="ev.is_done" class="text-[10px] font-bold opacity-80 text-emerald-600 dark:text-emerald-400">✓</span>
                                        </div>
                                        <div class="font-bold text-xs leading-tight line-clamp-2">
                                            {{ ev.title }}
                                        </div>
                                        <div v-if="ev.location || ev.lead_title" class="text-[10px] opacity-75 truncate mt-1 flex items-center gap-1">
                                            <span>📍</span>
                                            <span>{{ ev.lead_title || ev.location }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Subtle Quick Add Button on Hover -->
                                <button
                                    type="button"
                                    @click.stop="openAddModalWithTime(day.dateStr, hour)"
                                    class="w-full opacity-0 group-hover:opacity-100 py-0.5 text-[10px] font-bold text-slate-400 hover:text-orange-500 rounded transition flex items-center justify-center gap-0.5 hover:bg-slate-100 dark:hover:bg-gray-800"
                                    title="Schedule activity at this hour"
                                >
                                    + {{ formatHourLabel(hour) }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

                <!-- ================================================= -->
                <!-- 3B. MONTH VIEW (Native Vue 7-Col Grid)            -->
                <!-- ================================================= -->
                <div v-else-if="currentView === 'month'" class="flex flex-col h-full overflow-y-auto overflow-x-auto custom-scrollbar">
                    
                    <div class="min-w-[580px] md:min-w-full flex flex-col min-h-full">
                        <!-- Weekday Header Row -->
                        <div class="grid grid-cols-7 border-b border-slate-200 dark:border-gray-800 bg-slate-50 dark:bg-gray-800/80 sticky top-0 z-10 text-center py-2 text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                        <span v-for="dName in dayNamesShort" :key="dName">{{ dName }}</span>
                    </div>

                    <!-- 42-Cell Month Grid -->
                    <div class="grid grid-cols-7 flex-1 divide-x divide-y divide-slate-100 dark:divide-gray-800/70">
                        <div
                            v-for="cell in monthViewGridCells"
                            :key="cell.dateStr"
                            @click="openAddModal(cell.dateStr)"
                            :class="[
                                'min-h-[110px] p-2 transition flex flex-col justify-between group cursor-pointer relative',
                                cell.isOtherMonth ? 'bg-slate-50/40 dark:bg-gray-900/40' : 'bg-white dark:bg-gray-900',
                                cell.dateStr === selectedDate ? 'ring-1 ring-inset ring-orange-400' : 'hover:bg-slate-50/80 dark:hover:bg-gray-800/50'
                            ]"
                        >
                            <!-- Cell Header: Date Number + Quick Add -->
                            <div class="flex items-center justify-between mb-1.5">
                                <button
                                    type="button"
                                    @click.stop="openDayPreview(cell.dateStr, $event)"
                                    :class="[
                                        'w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold transition date-preview-trigger',
                                        cell.isToday
                                            ? 'bg-orange-500 text-white shadow-xs'
                                            : cell.dateStr === selectedDate
                                                ? 'bg-slate-800 dark:bg-slate-600 text-white'
                                                : cell.isOtherMonth
                                                    ? 'text-slate-300 dark:text-slate-600'
                                                    : 'text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-gray-700'
                                    ]"
                                    :title="`${cell.events.length} activities. Click for preview.`"
                                >
                                    {{ cell.dayNumber }}
                                </button>

                                <span
                                    class="opacity-0 group-hover:opacity-100 text-[10px] font-bold text-slate-400 hover:text-orange-500 transition px-1"
                                    title="Add activity on this date"
                                >
                                    + Add
                                </span>
                            </div>

                            <!-- Stack of Activity Pills -->
                            <div class="space-y-1 flex-1">
                                <div
                                    v-for="ev in cell.events.slice(0, 3)"
                                    :key="ev.id"
                                    @click.stop="selectEvent(ev)"
                                    :class="[
                                        'px-2 py-1 rounded-lg text-[11px] font-bold truncate flex items-center gap-1.5 transition cursor-pointer shadow-2xs border',
                                        getMonthEventPillClasses(ev.type, ev.is_done)
                                    ]"
                                    :title="`${formatTime(ev.schedule_from)} · ${ev.title}`"
                                >
                                    <span>{{ typeEmoji[ev.type] || '📌' }}</span>
                                    <span class="truncate font-semibold">{{ ev.title }}</span>
                                    <span v-if="ev.is_done" class="ml-auto text-[10px] opacity-75">✓</span>
                                </div>

                                <button
                                    v-if="cell.events.length > 3"
                                    type="button"
                                    @click.stop="openDayPreview(cell.dateStr, $event)"
                                    class="w-full text-center py-0.5 text-[10px] font-bold text-orange-600 dark:text-orange-400 hover:underline cursor-pointer date-preview-trigger"
                                >
                                    +{{ cell.events.length - 3 }} more
                                </button>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>

                <!-- ================================================= -->
                <!-- 3C. DAY VIEW (Detailed Single-Day Schedule)       -->
                <!-- ================================================= -->
                <div v-else-if="currentView === 'day'" class="flex flex-col h-full overflow-y-auto p-4 sm:p-6 space-y-4 custom-scrollbar">
                    
                    <!-- Day Banner Header -->
                    <div class="p-4 sm:p-5 rounded-2xl bg-slate-50 dark:bg-gray-800/60 border border-slate-200 dark:border-gray-800 flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-2xs">
                        <div>
                            <span class="text-xs font-bold uppercase tracking-wider text-orange-500">
                                {{ getDayOfWeekName(selectedDate) }}
                            </span>
                            <h2 class="text-lg sm:text-xl font-bold text-gray-800 dark:text-white mt-0.5">
                                {{ formatFullDate(selectedDate) }}
                            </h2>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                {{ dayViewEvents.length }} {{ dayViewEvents.length === 1 ? 'activity' : 'activities' }} scheduled for this day
                            </p>
                        </div>

                        <button
                            type="button"
                            @click="openAddModal(selectedDate)"
                            class="px-4 py-2 rounded-xl bg-orange-500 hover:bg-orange-600 text-white text-xs font-bold shadow-md shadow-orange-500/20 transition cursor-pointer flex items-center gap-1.5 self-start sm:self-auto"
                        >
                            <span>+ Schedule Activity</span>
                        </button>
                    </div>

                    <!-- Day Agenda Slots (8 AM - 8 PM) -->
                    <div class="divide-y divide-slate-100 dark:divide-gray-800 rounded-2xl bg-white dark:bg-gray-900 border border-slate-200 dark:border-gray-800 overflow-hidden shadow-2xs">
                        <div
                            v-for="hour in hourSlots"
                            :key="hour"
                            class="flex items-start min-h-[70px] p-3 sm:p-4 hover:bg-slate-50/50 dark:hover:bg-gray-800/40 transition group"
                        >
                            <!-- Hour Label -->
                            <div class="w-20 sm:w-24 shrink-0 text-xs font-bold text-slate-400 pt-1">
                                {{ formatHourLabel(hour) }}
                            </div>

                            <!-- Hour Events or Empty Prompt -->
                            <div class="flex-1 space-y-2">
                                <div
                                    v-for="ev in getEventsForHour(dayViewEvents, hour)"
                                    :key="ev.id"
                                    @click="selectEvent(ev)"
                                    :class="[
                                        'p-3 rounded-xl border shadow-2xs transition cursor-pointer hover:shadow-md flex flex-col sm:flex-row sm:items-center justify-between gap-3',
                                        getWeekEventCardClasses(ev.type, ev.is_done)
                                    ]"
                                >
                                    <div class="flex items-start gap-3 min-w-0">
                                        <span class="text-2xl shrink-0 mt-0.5">{{ typeEmoji[ev.type] || '📌' }}</span>
                                        <div class="min-w-0">
                                            <div class="flex items-center gap-2">
                                                <h4 class="font-bold text-sm truncate">{{ ev.title }}</h4>
                                                <span
                                                    :class="[
                                                        'px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider',
                                                        ev.is_done ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300' : 'bg-blue-100 text-blue-800 dark:bg-blue-950 dark:text-blue-300'
                                                    ]"
                                                >
                                                    {{ ev.is_done ? 'Done' : 'Scheduled' }}
                                                </span>
                                            </div>
                                            <div class="flex items-center gap-3 text-xs opacity-75 mt-1">
                                                <span>🕐 {{ formatTimeRange(ev.schedule_from, ev.schedule_to) }}</span>
                                                <span v-if="ev.location">📍 {{ ev.location }}</span>
                                                <span v-if="ev.lead_title">📋 {{ ev.lead_title }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2 self-end sm:self-auto shrink-0">
                                        <button
                                            type="button"
                                            @click.stop="toggleStatus(ev)"
                                            class="px-2.5 py-1 rounded-lg text-xs font-bold border border-current opacity-70 hover:opacity-100 transition"
                                        >
                                            {{ ev.is_done ? '↺ Reopen' : '✓ Done' }}
                                        </button>
                                    </div>
                                </div>

                                <!-- If no events in this hour, show subtle add prompt on hover -->
                                <button
                                    v-if="!getEventsForHour(dayViewEvents, hour).length"
                                    type="button"
                                    @click="openAddModalWithTime(selectedDate, hour)"
                                    class="opacity-0 group-hover:opacity-100 text-xs font-semibold text-slate-400 hover:text-orange-500 py-1 transition flex items-center gap-1"
                                >
                                    + Add activity at {{ formatHourLabel(hour) }}
                                </button>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- ================================================= -->
                <!-- 3D. YEAR VIEW                                     -->
                <!-- ================================================= -->
                <div v-else-if="currentView === 'year'" class="p-6 overflow-y-auto h-full">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 max-w-7xl mx-auto">
                        <div
                            v-for="(mObj, mIdx) in yearViewMonths"
                            :key="mIdx"
                            class="p-4 rounded-2xl bg-slate-50 dark:bg-gray-800/60 border border-slate-200 dark:border-gray-800 shadow-2xs hover:shadow-md transition"
                        >
                            <!-- Month Name Header -->
                            <div class="flex items-center justify-between pb-2 mb-2 border-b border-slate-200 dark:border-gray-700">
                                <button
                                    type="button"
                                    @click="jumpToMonth(mObj.year, mObj.month)"
                                    class="text-sm font-bold text-gray-800 dark:text-white hover:text-orange-500 transition cursor-pointer"
                                >
                                    {{ mObj.monthName }}
                                </button>
                                <span class="text-xs font-bold text-slate-400">
                                    {{ mObj.totalEvents }} events
                                </span>
                            </div>

                            <!-- 7-Col Mini Days Grid -->
                            <div class="grid grid-cols-7 gap-1 text-center text-[10px] font-bold text-slate-400 mb-1 uppercase">
                                <span>S</span><span>M</span><span>T</span><span>W</span><span>T</span><span>F</span><span>S</span>
                            </div>

                            <div class="grid grid-cols-7 gap-1">
                                <button
                                    v-for="cell in mObj.cells"
                                    :key="cell.dateStr"
                                    type="button"
                                    @click.stop="openDayPreview(cell.dateStr, $event)"
                                    :class="[
                                        'min-h-[32px] rounded-lg flex flex-col items-center justify-center text-[11px] font-semibold transition relative group cursor-pointer p-0.5 date-preview-trigger',
                                        cell.isToday
                                            ? 'bg-orange-500 text-white font-bold shadow-xs'
                                            : cell.dateStr === selectedDate
                                                ? 'bg-slate-800 dark:bg-slate-700 text-white'
                                                : cell.isOtherMonth
                                                    ? 'text-slate-300 dark:text-slate-600 opacity-40'
                                                    : 'text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-gray-700'
                                    ]"
                                    :title="`${cell.events.length} activities on ${cell.dateStr}`"
                                >
                                    <span class="leading-none">{{ cell.dayNumber }}</span>
                                    <div v-if="cell.events && cell.events.length && !cell.isOtherMonth" class="h-1.5 flex items-center justify-center gap-0.5 mt-0.5">
                                        <span
                                            v-for="(ev, idx) in cell.events.slice(0, 3)"
                                            :key="idx"
                                            class="w-1 h-1 rounded-full transition-colors shrink-0"
                                            :style="{
                                                backgroundColor: (cell.isToday || cell.dateStr === selectedDate) ? '#ffffff' : getEventDotColor(ev.type)
                                            }"
                                        ></span>
                                    </div>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- ===================================================== -->
            <!-- 4. EVENT DETAIL SLIDE-IN PANEL (Right Side Overlay)   -->
            <!-- ===================================================== -->
            <!-- Mobile Backdrop for Event Drawer -->
            <div
                v-if="selectedEvent"
                class="sm:hidden fixed inset-0 bg-slate-950/60 z-30 backdrop-blur-2xs"
                @click="selectedEvent = null"
            ></div>

            <transition
                enter-active-class="transform transition ease-in-out duration-300"
                enter-from-class="translate-x-full"
                enter-to-class="translate-x-0"
                leave-active-class="transform transition ease-in-out duration-200"
                leave-from-class="translate-x-0"
                leave-to-class="translate-x-full"
            >
                <div
                    v-if="selectedEvent"
                    id="event-detail-drawer"
                    class="absolute top-0 sm:top-16 right-0 bottom-0 w-full sm:w-96 max-w-full bg-white dark:bg-gray-900 border-l border-slate-200 dark:border-gray-800 shadow-2xl z-40 flex flex-col overflow-hidden"
                >
                    <!-- Detail Header -->
                    <div class="p-5 border-b border-slate-200 dark:border-gray-800 flex items-center justify-between bg-slate-50 dark:bg-gray-800">
                        <div class="flex items-center gap-2">
                            <span class="text-xl">{{ typeEmoji[selectedEvent.type] || '📌' }}</span>
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                                {{ selectedEvent.type }} details
                            </span>
                        </div>
                        <button
                            type="button"
                            @click="selectedEvent = null"
                            class="w-8 h-8 rounded-lg text-slate-400 hover:text-slate-700 dark:hover:text-white hover:bg-slate-200 dark:hover:bg-gray-700 flex items-center justify-center transition cursor-pointer"
                            title="Close"
                        >
                            ✕
                        </button>
                    </div>

                    <!-- Detail Body -->
                    <div class="flex-1 overflow-y-auto p-5 space-y-4 text-xs">
                        
                        <!-- Title & Status Badge -->
                        <div>
                            <div class="flex items-center justify-between gap-2 mb-1.5">
                                <span
                                    :class="[
                                        'px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider inline-flex items-center gap-1',
                                        selectedEvent.is_done
                                            ? 'bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300'
                                            : 'bg-amber-100 dark:bg-amber-950 text-amber-700 dark:text-amber-300'
                                    ]"
                                >
                                    <span>{{ selectedEvent.is_done ? '✓' : '⏳' }}</span>
                                    <span>{{ selectedEvent.is_done ? 'Completed' : 'Scheduled' }}</span>
                                </span>

                                <span class="text-slate-400 text-[11px]">#{{ selectedEvent.id }}</span>
                            </div>

                            <h2 class="text-base font-bold text-gray-800 dark:text-white leading-snug">
                                {{ selectedEvent.title }}
                            </h2>
                        </div>

                        <!-- Date & Time -->
                        <div class="p-3 rounded-xl bg-slate-50 dark:bg-gray-800/70 border border-slate-100 dark:border-gray-800 space-y-1">
                            <div class="flex items-center gap-2 text-slate-800 dark:text-slate-200 font-bold">
                                <span>🕐</span>
                                <span>{{ formatTimeRange(selectedEvent.schedule_from, selectedEvent.schedule_to) }}</span>
                            </div>
                            <div class="text-slate-500 dark:text-slate-400 pl-6 text-[11px]">
                                {{ formatFullDate(selectedEvent.schedule_from) }}
                            </div>
                        </div>

                        <!-- Lead / Deal -->
                        <div v-if="selectedEvent.lead_title" class="space-y-1">
                            <label class="font-bold text-slate-400 uppercase text-[10px] tracking-wider">Associated Deal / Lead</label>
                            <div class="flex items-center gap-2 p-2.5 rounded-xl bg-blue-50/60 dark:bg-blue-950/40 text-blue-900 dark:text-blue-200 border border-blue-100 dark:border-blue-900/60">
                                <span>📋</span>
                                <span class="font-bold truncate">{{ selectedEvent.lead_title }}</span>
                            </div>
                        </div>

                        <!-- Location -->
                        <div v-if="selectedEvent.location" class="space-y-1">
                            <label class="font-bold text-slate-400 uppercase text-[10px] tracking-wider">Location / Venue</label>
                            <div class="flex items-start gap-2 p-2.5 rounded-xl bg-slate-50 dark:bg-gray-800/70 border border-slate-100 dark:border-gray-800 text-slate-700 dark:text-slate-300">
                                <span class="mt-0.5">📍</span>
                                <span class="font-medium break-all">{{ selectedEvent.location }}</span>
                            </div>
                        </div>

                        <!-- Assigned User -->
                        <div v-if="selectedEvent.user_name" class="space-y-1">
                            <label class="font-bold text-slate-400 uppercase text-[10px] tracking-wider">Assigned Agent</label>
                            <div class="flex items-center gap-2 p-2.5 rounded-xl bg-slate-50 dark:bg-gray-800/70 border border-slate-100 dark:border-gray-800 text-slate-700 dark:text-slate-300">
                                <span>👤</span>
                                <span class="font-bold">{{ selectedEvent.user_name }}</span>
                            </div>
                        </div>

                        <!-- Comments / Description -->
                        <div v-if="selectedEvent.comment" class="space-y-1">
                            <label class="font-bold text-slate-400 uppercase text-[10px] tracking-wider">Notes & Comments</label>
                            <div class="p-3 rounded-xl bg-slate-50 dark:bg-gray-800/70 border border-slate-100 dark:border-gray-800 text-slate-700 dark:text-slate-300 leading-relaxed whitespace-pre-wrap">
                                {{ selectedEvent.comment }}
                            </div>
                        </div>

                    </div>

                    <!-- Detail Actions Footer -->
                    <div class="p-4 border-t border-slate-100 dark:border-gray-800 bg-slate-50/60 dark:bg-gray-800/60 space-y-2">
                        <div class="flex items-center gap-2">
                            <button
                                type="button"
                                @click="toggleStatus(selectedEvent)"
                                :disabled="isActionLoading"
                                :class="[
                                    'flex-1 py-2 px-3 rounded-xl text-xs font-bold transition shadow-xs flex items-center justify-center gap-1.5 cursor-pointer',
                                    selectedEvent.is_done
                                        ? 'bg-slate-200 hover:bg-slate-300 text-slate-700 dark:bg-gray-700 dark:text-white'
                                        : 'bg-emerald-500 hover:bg-emerald-600 text-white shadow-emerald-500/20'
                                ]"
                            >
                                <span>{{ selectedEvent.is_done ? '↺ Reopen' : '✓ Mark Done' }}</span>
                            </button>

                            <button
                                type="button"
                                @click="deleteEvent(selectedEvent)"
                                :disabled="isActionLoading"
                                class="py-2 px-3 rounded-xl text-xs font-bold text-red-600 hover:bg-red-50 dark:hover:bg-red-950/40 border border-red-200 dark:border-red-900 transition cursor-pointer"
                                title="Delete Activity"
                            >
                                🗑️ Delete
                            </button>
                        </div>
                    </div>
                </div>
            </transition>

        </main>
        <!-- ========================================================= -->
        <!-- 5. DAY PREVIEW POPOVER                                    -->
        <!-- ========================================================= -->
        <transition
            enter-active-class="transition ease-out duration-150"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-100"
            leave-active-class="transition ease-in duration-100"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-95"
        >
            <div
                v-if="activePopover"
                :class="[
                    'z-50',
                    activePopover.isMobile
                        ? 'fixed inset-0 flex items-center justify-center p-4 bg-black/40 backdrop-blur-xs'
                        : 'absolute'
                ]"
                :style="!activePopover.isMobile ? { top: `${activePopover.y}px`, left: `${activePopover.x}px` } : {}"
                @click="activePopover.isMobile ? closeDayPreview() : null"
            >
                <!-- Popover Card -->
                <div
                    class="popover-card-container w-80 sm:w-88 max-w-[92vw] bg-white dark:bg-gray-900 border border-slate-200/90 dark:border-gray-800 rounded-2xl shadow-2xl overflow-hidden flex flex-col animate-modal-in text-slate-800 dark:text-slate-100 select-text"
                    @click.stop
                >
                    <!-- Popover Header -->
                    <div class="px-4 py-3 bg-slate-50 dark:bg-gray-800/80 border-b border-slate-100 dark:border-gray-800 flex items-center justify-between">
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="text-xs font-bold uppercase tracking-wider text-gray-800 dark:text-white truncate">
                                {{ formatFullDate(activePopover.dateStr) }}
                            </span>
                            <span
                                v-if="activePopover.dateStr === todayDateStr"
                                class="px-1.5 py-0.5 rounded-md bg-orange-100 text-orange-700 dark:bg-orange-950/70 dark:text-orange-300 text-[10px] font-bold uppercase tracking-wider shrink-0"
                            >
                                Today
                            </span>
                        </div>

                        <button
                            type="button"
                            @click="closeDayPreview"
                            class="w-6 h-6 rounded-lg text-slate-400 hover:text-slate-700 dark:hover:text-white flex items-center justify-center transition cursor-pointer hover:bg-slate-200/60 dark:hover:bg-gray-700 shrink-0"
                            title="Close (Esc)"
                        >
                            ✕
                        </button>
                    </div>

                    <!-- Popover Event List (Max ~4-5 rows, scrollable) -->
                    <div class="p-3 max-h-56 overflow-y-auto space-y-2 custom-scrollbar">
                        <div v-if="popoverEvents.length === 0" class="py-6 text-center text-slate-400 text-xs">
                            <p class="text-2xl mb-1">📅</p>
                            <p class="font-bold text-slate-600 dark:text-slate-300">No activities scheduled</p>
                            <p class="text-[11px] text-slate-400 mt-0.5">Click below to schedule one for this day.</p>
                        </div>

                        <div
                            v-for="ev in popoverEvents"
                            :key="ev.id"
                            @click="onPopoverEventClick(ev)"
                            class="p-2.5 rounded-xl border border-slate-100 dark:border-gray-800 hover:border-orange-300 dark:hover:border-orange-500/50 bg-slate-50/60 dark:bg-gray-800/50 hover:bg-orange-50/50 dark:hover:bg-orange-950/20 transition cursor-pointer flex items-start gap-2.5 group"
                            :title="`View details for ${ev.title}`"
                        >
                            <span class="text-base shrink-0 mt-0.5">{{ typeEmoji[ev.type] || '📌' }}</span>

                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-1">
                                    <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400">
                                        {{ formatTime(ev.schedule_from) }}
                                    </span>
                                    <span
                                        :class="[
                                            'px-1.5 py-0.5 rounded text-[10px] font-bold tracking-tight shrink-0',
                                            ev.is_done
                                                ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800'
                                                : 'bg-blue-50 text-blue-700 dark:bg-blue-950/60 dark:text-blue-300 border border-blue-200 dark:border-blue-800'
                                        ]"
                                    >
                                        {{ ev.is_done ? 'Done' : 'Scheduled' }}
                                    </span>
                                </div>

                                <h5 class="text-xs font-bold text-slate-800 dark:text-slate-100 truncate mt-0.5 group-hover:text-orange-600 dark:group-hover:text-orange-400 transition">
                                    {{ ev.title }}
                                </h5>

                                <p v-if="ev.location || ev.lead_title" class="text-[10px] text-slate-400 truncate mt-0.5">
                                    {{ ev.lead_title ? `#${ev.lead_id} · ${ev.lead_title}` : ev.location }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Popover Footer Actions -->
                    <div class="p-3 bg-slate-50 dark:bg-gray-800/80 border-t border-slate-100 dark:border-gray-800 flex items-center justify-between gap-2">
                        <button
                            type="button"
                            @click="onPopoverOpenDay"
                            class="px-3 py-1.5 rounded-xl border border-slate-200 dark:border-gray-700 text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-gray-700 text-xs font-bold transition cursor-pointer flex items-center gap-1 shadow-2xs"
                        >
                            <span>Open full day →</span>
                        </button>

                        <button
                            type="button"
                            @click="onPopoverAddActivity"
                            class="px-3 py-1.5 rounded-xl bg-orange-500 hover:bg-orange-600 text-white text-xs font-bold transition cursor-pointer shadow-sm shadow-orange-500/20 flex items-center gap-1"
                        >
                            <span>+ Add activity</span>
                        </button>
                    </div>
                </div>
            </div>
        </transition>

    </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import axios from 'axios';

// Ensure standard CSRF headers
axios.defaults.headers.common['X-CSRF-TOKEN'] =
    document.querySelector('meta[name="csrf-token"]')?.content;
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Local date helpers (Asia/Calcutta / local client timezone - avoids UTC toISOString day shifts)
function getLocalDateYMD(d = new Date()) {
    const year = d.getFullYear();
    const month = String(d.getMonth() + 1).padStart(2, '0');
    const day = String(d.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}

function getLocalDateYM(d = new Date()) {
    const year = d.getFullYear();
    const month = String(d.getMonth() + 1).padStart(2, '0');
    return `${year}-${month}`;
}

const props = defineProps({
    initialMonth:      { type: String, default: () => { const d = new Date(); return `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}`; } },
    initialActivities: { type: Array,  default: () => [] },
    users:             { type: Array,  default: () => [] },
    leads:             { type: Array,  default: () => [] },
    currentUserId:     { type: Number, default: 0 }
});

// Helper for extracting 'YYYY-MM-DD' from any date format
function getActivityDateStr(dateStr) {
    if (!dateStr) return '';
    const s = String(dateStr).trim();
    if (s.includes('T')) return s.split('T')[0];
    if (s.includes(' ')) return s.split(' ')[0];
    return s;
}

function parseSafeDate(dateStr) {
    if (!dateStr) return new Date();
    const clean = String(dateStr).trim();
    const ymd = getActivityDateStr(clean);
    const parts = ymd.split('-');
    if (parts.length === 3) {
        const y = parseInt(parts[0], 10);
        const m = parseInt(parts[1], 10) - 1;
        const d = parseInt(parts[2], 10);
        const res = new Date(y, m, d);
        if (!isNaN(res.getTime())) return res;
    }
    const fallback = new Date(clean);
    return isNaN(fallback.getTime()) ? new Date() : fallback;
}

// State
const calendarContainerRef = ref(null);
const activities = ref([...(props.initialActivities || [])]);
const currentView = ref('month'); // 'day' | 'week' | 'month' | 'year'
const todayDateStr = ref(getLocalDateYMD());
const selectedDate = ref(getLocalDateYMD());
const activePopover = ref(null);
const searchQuery = ref('');
const selectedEvent = ref(null);
const isLoading = ref(false);
const isActionLoading = ref(false);
const showMobileSidebar = ref(false);

// Mini Calendar Month/Year in Left Sidebar
const miniCalendarYear = ref(new Date().getFullYear());
const miniCalendarMonth = ref(new Date().getMonth() + 1); // 1-12

const monthNames = [
    'January', 'February', 'March', 'April', 'May', 'June',
    'July', 'August', 'September', 'October', 'November', 'December'
];

const dayNames = [
    'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'
];

const dayNamesShort = ['SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT'];

const typeEmoji = {
    call:    '📞',
    meeting: '🏢',
    lunch:   '🍽️',
    task:    '📋',
    note:    '📝'
};

// Filter activities based on search query
const filteredActivities = computed(() => {
    if (!searchQuery.value.trim()) {
        return activities.value;
    }
    const q = searchQuery.value.toLowerCase().trim();
    return activities.value.filter(a => {
        const title = (a.title || '').toLowerCase();
        const comment = (a.comment || '').toLowerCase();
        const location = (a.location || '').toLowerCase();
        const lead = (a.lead_title || '').toLowerCase();
        const user = (a.user_name || '').toLowerCase();
        return title.includes(q) || comment.includes(q) || location.includes(q) || lead.includes(q) || user.includes(q);
    });
});

// =========================================================
// MINI CALENDAR COMPUTATIONS (Left Sidebar)
// =========================================================
const miniCalendarCells = computed(() => {
    const year = miniCalendarYear.value;
    const month = miniCalendarMonth.value;
    const firstDayIndex = new Date(year, month - 1, 1).getDay();
    const daysInMonth = new Date(year, month, 0).getDate();
    const prevMonthDays = new Date(year, month - 1, 0).getDate();
    
    const cells = [];

    // Prev month padding
    for (let i = firstDayIndex - 1; i >= 0; i--) {
        const dayNum = prevMonthDays - i;
        const d = new Date(year, month - 2, dayNum);
        const dStr = formatDateToYMD(d);
        cells.push({
            dayNumber: dayNum,
            dateStr: dStr,
            isOtherMonth: true,
            isToday: dStr === todayDateStr.value,
            isSelected: dStr === selectedDate.value,
            events: filteredActivities.value.filter(a => getActivityDateStr(a.schedule_from) === dStr)
        });
    }

    // Current month days
    for (let day = 1; day <= daysInMonth; day++) {
        const d = new Date(year, month - 1, day);
        const dStr = formatDateToYMD(d);
        cells.push({
            dayNumber: day,
            dateStr: dStr,
            isOtherMonth: false,
            isToday: dStr === todayDateStr.value,
            isSelected: dStr === selectedDate.value,
            events: filteredActivities.value.filter(a => getActivityDateStr(a.schedule_from) === dStr)
        });
    }

    // Next month padding to fill 42 cells (6 rows x 7 cols)
    const remaining = 42 - cells.length;
    for (let i = 1; i <= remaining; i++) {
        const d = new Date(year, month, i);
        const dStr = formatDateToYMD(d);
        cells.push({
            dayNumber: i,
            dateStr: dStr,
            isOtherMonth: true,
            isToday: dStr === todayDateStr.value,
            isSelected: dStr === selectedDate.value,
            events: filteredActivities.value.filter(a => getActivityDateStr(a.schedule_from) === dStr)
        });
    }

    return cells;
});

// =========================================================
// UPCOMING EVENTS (Left Sidebar)
// =========================================================
const upcomingGroupedDates = computed(() => {
    const groups = [];
    const today = parseSafeDate(todayDateStr.value);
    
    const dateMap = new Map();
    
    const sorted = [...filteredActivities.value]
        .filter(a => a.schedule_from)
        .sort((a, b) => String(a.schedule_from || '').localeCompare(String(b.schedule_from || '')));

    sorted.forEach(ev => {
        const dStr = getActivityDateStr(ev.schedule_from);
        if (!dStr) return;
        const eventDate = parseSafeDate(dStr);
        if (eventDate >= today || dStr === todayDateStr.value) {
            if (!dateMap.has(dStr)) {
                dateMap.set(dStr, []);
            }
            dateMap.get(dStr).push(ev);
        }
    });

    const sampleWeathers = [
        { temp: '55°/40°', icon: '☀' },
        { temp: '52°/38°', icon: '☀' },
        { temp: '48°/35°', icon: '☁' },
        { temp: '50°/36°', icon: '⛅' },
        { temp: '54°/39°', icon: '🌧️' }
    ];

    let groupIdx = 0;
    for (const [dStr, evList] of dateMap.entries()) {
        if (groupIdx >= 5) break;

        const isToday = dStr === todayDateStr.value;
        const tomorrow = new Date(today);
        tomorrow.setDate(tomorrow.getDate() + 1);
        const isTomorrow = dStr === formatDateToYMD(tomorrow);

        const dObj = parseSafeDate(dStr);
        let label = '';
        const dayName = dayNames[dObj.getDay()] || 'DAY';
        if (isToday) {
            label = `TODAY ${dObj.getMonth() + 1}/${dObj.getDate()}/${dObj.getFullYear()}`;
        } else if (isTomorrow) {
            label = `TOMORROW ${dObj.getMonth() + 1}/${dObj.getDate()}/${dObj.getFullYear()}`;
        } else {
            label = `${dayName.toUpperCase()} ${dObj.getMonth() + 1}/${dObj.getDate()}/${dObj.getFullYear()}`;
        }

        const weather = sampleWeathers[groupIdx % sampleWeathers.length];

        groups.push({
            dateStr: dStr,
            label,
            isToday,
            temp: weather.temp,
            weatherIcon: weather.icon,
            events: evList
        });

        groupIdx++;
    }

    return groups;
});

// =========================================================
// NATIVE CALENDAR COMPUTATIONS (Week, Month, Day)
// =========================================================

// Hour slots for Week & Day schedule grids (7 AM to 9 PM)
const hourSlots = [7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21];

// Helper to filter events starting in a given hour (0-23)
function getEventsForHour(eventsList, hour) {
    if (!eventsList || !eventsList.length) return [];
    return eventsList.filter(ev => {
        if (!ev.schedule_from) return false;
        const s = String(ev.schedule_from).trim();
        const timePart = s.includes('T') ? s.split('T')[1] : (s.includes(' ') ? s.split(' ')[1] : '');
        if (!timePart) return false;
        const h = parseInt(timePart.split(':')[0], 10);
        return h === hour;
    });
}

// Helper for any all-day or off-hours activities
function getNonHourEvents(eventsList) {
    if (!eventsList || !eventsList.length) return [];
    return eventsList.filter(ev => {
        if (!ev.schedule_from) return true;
        const s = String(ev.schedule_from).trim();
        const timePart = s.includes('T') ? s.split('T')[1] : (s.includes(' ') ? s.split(' ')[1] : '');
        if (!timePart) return true;
        const h = parseInt(timePart.split(':')[0], 10);
        return !hourSlots.includes(h);
    });
}

// 7 Days of the active week (Sunday to Saturday)
const weekDays = computed(() => {
    const curr = parseSafeDate(selectedDate.value);
    const dow = curr.getDay(); // 0 = Sun, 1 = Mon ...
    const sunday = new Date(curr);
    sunday.setDate(curr.getDate() - dow);

    const days = [];
    for (let i = 0; i < 7; i++) {
        const d = new Date(sunday);
        d.setDate(sunday.getDate() + i);
        const dStr = formatDateToYMD(d);
        const dayEvs = filteredActivities.value.filter(a => getActivityDateStr(a.schedule_from) === dStr)
            .sort((a, b) => String(a.schedule_from || '').localeCompare(String(b.schedule_from || '')));

        days.push({
            date: d,
            dateStr: dStr,
            dayName: dayNamesShort[i], // SUN, MON...
            dayFull: dayNames[i], // Sunday, Monday...
            dayNumber: d.getDate(),
            monthName: monthNames[d.getMonth()].slice(0, 3),
            isToday: dStr === todayDateStr.value,
            isSelected: dStr === selectedDate.value,
            events: dayEvs
        });
    }
    return days;
});

// 42-cell (or 35-cell) month grid
const monthViewGridCells = computed(() => {
    const d = parseSafeDate(selectedDate.value);
    const year = d.getFullYear();
    const month = d.getMonth() + 1;
    const firstDayIndex = new Date(year, month - 1, 1).getDay();
    const daysInMonth = new Date(year, month, 0).getDate();
    const prevMonthDays = new Date(year, month - 1, 0).getDate();

    const cells = [];

    // Prev month padding
    for (let i = firstDayIndex - 1; i >= 0; i--) {
        const dayNum = prevMonthDays - i;
        const dt = new Date(year, month - 2, dayNum);
        const dStr = formatDateToYMD(dt);
        cells.push({
            dayNumber: dayNum,
            dateStr: dStr,
            isOtherMonth: true,
            isToday: dStr === todayDateStr.value,
            isSelected: dStr === selectedDate.value,
            events: filteredActivities.value.filter(a => getActivityDateStr(a.schedule_from) === dStr)
        });
    }

    // Current month days
    for (let day = 1; day <= daysInMonth; day++) {
        const dt = new Date(year, month - 1, day);
        const dStr = formatDateToYMD(dt);
        cells.push({
            dayNumber: day,
            dateStr: dStr,
            isOtherMonth: false,
            isToday: dStr === todayDateStr.value,
            isSelected: dStr === selectedDate.value,
            events: filteredActivities.value.filter(a => getActivityDateStr(a.schedule_from) === dStr)
        });
    }

    // Next month padding to fill complete calendar grid
    const totalNeeded = cells.length > 35 ? 42 : 35;
    const remaining = totalNeeded - cells.length;
    for (let i = 1; i <= remaining; i++) {
        const dt = new Date(year, month, i);
        const dStr = formatDateToYMD(dt);
        cells.push({
            dayNumber: i,
            dateStr: dStr,
            isOtherMonth: true,
            isToday: dStr === todayDateStr.value,
            isSelected: dStr === selectedDate.value,
            events: filteredActivities.value.filter(a => getActivityDateStr(a.schedule_from) === dStr)
        });
    }

    return cells;
});

// Single day events for Day view
const dayViewEvents = computed(() => {
    return filteredActivities.value
        .filter(a => getActivityDateStr(a.schedule_from) === selectedDate.value)
        .sort((a, b) => String(a.schedule_from || '').localeCompare(String(b.schedule_from || '')));
});

// Event card styling for week & day views
function getWeekEventCardClasses(type, isDone) {
    if (isDone) {
        return 'bg-slate-100/90 dark:bg-gray-800/80 border-slate-300 dark:border-gray-700 text-slate-600 dark:text-slate-400';
    }
    const map = {
        lunch:   'bg-amber-50/95 dark:bg-amber-950/60 border-l-4 border-l-amber-500 border-amber-300 dark:border-amber-800 text-amber-950 dark:text-amber-100',
        meeting: 'bg-purple-50/95 dark:bg-purple-950/60 border-l-4 border-l-purple-500 border-purple-300 dark:border-purple-800 text-purple-950 dark:text-purple-100',
        call:    'bg-blue-50/95 dark:bg-blue-950/60 border-l-4 border-l-blue-500 border-blue-300 dark:border-blue-800 text-blue-950 dark:text-blue-100',
        task:    'bg-emerald-50/95 dark:bg-emerald-950/60 border-l-4 border-l-emerald-500 border-emerald-300 dark:border-emerald-800 text-emerald-950 dark:text-emerald-100',
        note:    'bg-emerald-50/95 dark:bg-emerald-950/60 border-l-4 border-l-emerald-500 border-emerald-300 dark:border-emerald-800 text-emerald-950 dark:text-emerald-100'
    };
    return map[type] || 'bg-slate-50 dark:bg-gray-800 border-slate-300 dark:border-gray-700 text-slate-800 dark:text-slate-200';
}

// =========================================================
// YEAR VIEW COMPUTATIONS
// =========================================================
const yearViewMonths = computed(() => {
    const curr = parseSafeDate(selectedDate.value);
    const selYear = curr.getFullYear();
    const result = [];

    for (let m = 1; m <= 12; m++) {
        const firstDayIndex = new Date(selYear, m - 1, 1).getDay();
        const daysInMonth = new Date(selYear, m, 0).getDate();
        const prevMonthDays = new Date(selYear, m - 1, 0).getDate();
        const cells = [];

        // Prev month padding
        for (let i = firstDayIndex - 1; i >= 0; i--) {
            const dayNum = prevMonthDays - i;
            const d = new Date(selYear, m - 2, dayNum);
            const dStr = formatDateToYMD(d);
            cells.push({
                dayNumber: dayNum,
                dateStr: dStr,
                isOtherMonth: true,
                isToday: dStr === todayDateStr.value,
                events: []
            });
        }

        let totalEvents = 0;
        // Current month
        for (let day = 1; day <= daysInMonth; day++) {
            const d = new Date(selYear, m - 1, day);
            const dStr = formatDateToYMD(d);
            const evs = filteredActivities.value.filter(a => getActivityDateStr(a.schedule_from) === dStr);
            totalEvents += evs.length;
            cells.push({
                dayNumber: day,
                dateStr: dStr,
                isOtherMonth: false,
                isToday: dStr === todayDateStr.value,
                events: evs
            });
        }

        const remaining = 42 - cells.length;
        for (let i = 1; i <= remaining; i++) {
            const d = new Date(selYear, m, i);
            const dStr = formatDateToYMD(d);
            cells.push({
                dayNumber: i,
                dateStr: dStr,
                isOtherMonth: true,
                isToday: dStr === todayDateStr.value,
                events: []
            });
        }

        result.push({
            year: selYear,
            month: m,
            monthName: monthNames[m - 1],
            totalEvents,
            cells
        });
    }

    return result;
});

// =========================================================
// PERIOD TITLE COMPUTATION (Top Nav)
// =========================================================
const periodTitle = computed(() => {
    const d = parseSafeDate(selectedDate.value);
    
    if (currentView.value === 'day') {
        const dayName = dayNames[d.getDay()] || '';
        const monthName = monthNames[d.getMonth()] || '';
        return `${dayName}, ${monthName} ${d.getDate()}, ${d.getFullYear()}`;
    }

    if (currentView.value === 'week') {
        const days = weekDays.value;
        if (days && days.length === 7) {
            const startDate = days[0].date;
            const endDate = days[6].date;
            const startMonthName = monthNames[startDate.getMonth()] || '';
            const endMonthName = monthNames[endDate.getMonth()] || '';

            if (startDate.getMonth() === endDate.getMonth()) {
                return `${startMonthName} ${startDate.getDate()} – ${endDate.getDate()}, ${startDate.getFullYear()}`;
            }
            return `${startMonthName} ${startDate.getDate()} – ${endMonthName} ${endDate.getDate()}, ${endDate.getFullYear()}`;
        }
        return '';
    }

    if (currentView.value === 'month') {
        const monthName = monthNames[d.getMonth()] || '';
        return `${monthName} ${d.getFullYear()}`;
    }

    if (currentView.value === 'year') {
        return `${d.getFullYear()}`;
    }

    return '';
});

// =========================================================
// NAVIGATION ACTIONS
// =========================================================
function setView(view) {
    currentView.value = view;
}

function navigatePeriod(direction) {
    const d = parseSafeDate(selectedDate.value);

    if (currentView.value === 'day') {
        d.setDate(d.getDate() + direction);
    } else if (currentView.value === 'week') {
        d.setDate(d.getDate() + (direction * 7));
    } else if (currentView.value === 'month') {
        d.setMonth(d.getMonth() + direction);
    } else if (currentView.value === 'year') {
        d.setFullYear(d.getFullYear() + direction);
    }

    selectedDate.value = formatDateToYMD(d);
    syncMiniCalendarToSelected();
    checkAndFetchMonthEvents();
}

function navigateMiniMonth(direction) {
    miniCalendarMonth.value += direction;
    if (miniCalendarMonth.value > 12) {
        miniCalendarMonth.value = 1;
        miniCalendarYear.value++;
    } else if (miniCalendarMonth.value < 1) {
        miniCalendarMonth.value = 12;
        miniCalendarYear.value--;
    }
    const d = new Date(miniCalendarYear.value, miniCalendarMonth.value - 1, 1);
    selectedDate.value = formatDateToYMD(d);
    fetchMonthEvents(miniCalendarYear.value, miniCalendarMonth.value);
}

function selectMiniDate(dateStr, isOtherMonth) {
    selectedDate.value = dateStr;
    const d = parseSafeDate(dateStr);
    if (isOtherMonth) {
        miniCalendarYear.value = d.getFullYear();
        miniCalendarMonth.value = d.getMonth() + 1;
    }
    checkAndFetchMonthEvents();
}

function goToToday() {
    selectedDate.value = todayDateStr.value;
    syncMiniCalendarToSelected();
    checkAndFetchMonthEvents();
}

function jumpToDay(dateStr) {
    selectedDate.value = dateStr;
    currentView.value = 'day';
    syncMiniCalendarToSelected();
    checkAndFetchMonthEvents();
}

function jumpToMonth(year, month) {
    const d = new Date(year, month - 1, 1);
    selectedDate.value = formatDateToYMD(d);
    currentView.value = 'month';
    miniCalendarYear.value = year;
    miniCalendarMonth.value = month;
    checkAndFetchMonthEvents();
}

function syncMiniCalendarToSelected() {
    const d = parseSafeDate(selectedDate.value);
    miniCalendarYear.value = d.getFullYear();
    miniCalendarMonth.value = d.getMonth() + 1;
}

// =========================================================
// EVENT SELECTION & ACTIONS (Detail Panel & Modals)
// =========================================================
function selectEvent(event) {
    selectedEvent.value = event;
    showMobileSidebar.value = false;
}

function openAddModal(dateStr) {
    showMobileSidebar.value = false;
    if (typeof window.openScheduleModal === 'function') {
        window.openScheduleModal(dateStr || selectedDate.value);
    } else {
        console.info('Schedule modal triggered for date:', dateStr);
    }
}

function openAddModalWithTime(dateStr, hour) {
    if (typeof window.openScheduleModal === 'function') {
        window.openScheduleModal(dateStr);
        const pad = (n) => String(n).padStart(2, '0');
        const fromInput = document.getElementById('modal-schedule-from');
        const toInput = document.getElementById('modal-schedule-to');
        if (fromInput) fromInput.value = `${dateStr}T${pad(hour)}:00`;
        if (toInput) toInput.value = `${dateStr}T${pad(hour + 1)}:00`;
    }
}

// Toggle status via AJAX
async function toggleStatus(event) {
    if (!event || isActionLoading.value) return;
    isActionLoading.value = true;
    const newStatus = event.is_done ? 0 : 1;

    try {
        const response = await axios.put(`/admin/activities/edit/${event.id}`, {
            is_done: newStatus
        });

        // Update local state reactively
        event.is_done = newStatus;
        const idx = activities.value.findIndex(a => a.id === event.id);
        if (idx !== -1) {
            activities.value[idx].is_done = newStatus;
        }
    } catch (err) {
        console.error('Failed to update activity status:', err);
        // Fallback optimistic local toggle if offline or demo
        event.is_done = newStatus;
    } finally {
        isActionLoading.value = false;
    }
}

// Delete event via AJAX
async function deleteEvent(event) {
    if (!event || isActionLoading.value) return;
    if (!confirm(`Are you sure you want to delete "${event.title}"?`)) return;

    isActionLoading.value = true;
    try {
        await axios.delete(`/admin/activities/${event.id}`);
        // Remove locally
        activities.value = activities.value.filter(a => a.id !== event.id);
        selectedEvent.value = null;
    } catch (err) {
        console.error('Failed to delete activity:', err);
        // Try fallback delete endpoint if needed
        try {
            await axios.delete(`/admin/activities/delete/${event.id}`);
            activities.value = activities.value.filter(a => a.id !== event.id);
            selectedEvent.value = null;
        } catch (innerErr) {
            // Remove locally anyway for responsive UX
            activities.value = activities.value.filter(a => a.id !== event.id);
            selectedEvent.value = null;
        }
    } finally {
        isActionLoading.value = false;
    }
}

// =========================================================
// DATA FETCHING & LAZY LOADING
// =========================================================
const fetchedMonths = new Set();

function checkAndFetchMonthEvents() {
    const d = parseSafeDate(selectedDate.value);
    const y = d.getFullYear();
    const m = d.getMonth() + 1;
    fetchMonthEvents(y, m);
}

async function fetchMonthEvents(year, month) {
    const pad = (n) => String(n).padStart(2, '0');
    const monthKey = `${year}-${pad(month)}`;

    if (fetchedMonths.has(monthKey)) return;

    isLoading.value = true;
    try {
        const res = await axios.get('/admin/activities/calendar-events', {
            params: { month: monthKey }
        });

        const newEvents = res.data.activities || res.data.data || [];
        if (Array.isArray(newEvents)) {
            // Merge newly fetched events without duplicating by id
            const existingIds = new Set(activities.value.map(a => a.id));
            const toAdd = [];
            newEvents.forEach(item => {
                if (!existingIds.has(item.id)) {
                    toAdd.push(item);
                    existingIds.add(item.id);
                }
            });
            if (toAdd.length > 0) {
                activities.value = [...activities.value, ...toAdd];
            }
        }
        fetchedMonths.add(monthKey);
    } catch (err) {
        console.warn('Could not fetch calendar events for month:', monthKey, err);
    } finally {
        isLoading.value = false;
    }
}

// =========================================================
// HELPER FORMATTERS & STYLING
// =========================================================
function formatDateToYMD(d) {
    if (!d || isNaN(d.getTime())) return todayDateStr.value;
    const year = d.getFullYear();
    const month = String(d.getMonth() + 1).padStart(2, '0');
    const day = String(d.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}

function formatFullDate(dateStr) {
    if (!dateStr) return '';
    const d = parseSafeDate(dateStr);
    const monthName = monthNames[d.getMonth()] || '';
    return `${monthName} ${d.getDate()}, ${d.getFullYear()}`;
}

function getDayOfWeekName(dateStr) {
    if (!dateStr) return '';
    const d = parseSafeDate(dateStr);
    return dayNames[d.getDay()] || '';
}

function formatHourLabel(h) {
    if (h === 0 || h === 24) return '12 AM';
    if (h === 12) return '12 PM';
    return h > 12 ? `${h - 12} PM` : `${h} AM`;
}

function formatTime(dateStr) {
    if (!dateStr) return '';
    const d = new Date(dateStr);
    if (isNaN(d.getTime())) return '';
    return d.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });
}

function formatTimeRange(fromStr, toStr) {
    if (!fromStr) return '';
    const fromTime = formatTime(fromStr);
    if (!toStr) return fromTime;
    const toTime = formatTime(toStr);
    return `${fromTime} – ${toTime}`;
}

function getEventDotColor(type) {
    const map = {
        call:    '#3b82f6',
        meeting: '#8b5cf6',
        lunch:   '#f59e0b',
        task:    '#10b981',
        note:    '#10b981'
    };
    return map[type] || '#64748b';
}

function getEventColorClasses(type) {
    const map = {
        call:    'bg-blue-100/90 dark:bg-blue-950/80 border-blue-500 text-blue-900 dark:text-blue-200',
        meeting: 'bg-purple-100/90 dark:bg-purple-950/80 border-purple-500 text-purple-900 dark:text-purple-200',
        lunch:   'bg-amber-100/90 dark:bg-amber-950/80 border-amber-500 text-amber-900 dark:text-amber-200',
        task:    'bg-emerald-100/90 dark:bg-emerald-950/80 border-emerald-500 text-emerald-900 dark:text-emerald-200',
        note:    'bg-emerald-100/90 dark:bg-emerald-950/80 border-emerald-500 text-emerald-900 dark:text-emerald-200'
    };
    return map[type] || 'bg-slate-100 dark:bg-slate-800 border-slate-500 text-slate-800 dark:text-slate-200';
}

function getMonthEventPillClasses(type, isDone = false) {
    if (isDone) {
        return 'bg-slate-100 dark:bg-gray-800 border-slate-300 dark:border-gray-700 text-slate-500 dark:text-slate-400';
    }
    const map = {
        call:    'bg-blue-50 dark:bg-blue-950/70 border-blue-400 dark:border-blue-700 text-blue-800 dark:text-blue-300',
        meeting: 'bg-purple-50 dark:bg-purple-950/70 border-purple-400 dark:border-purple-700 text-purple-800 dark:text-purple-300',
        lunch:   'bg-amber-50 dark:bg-amber-950/70 border-amber-400 dark:border-amber-700 text-amber-800 dark:text-amber-300',
        task:    'bg-emerald-50 dark:bg-emerald-950/70 border-emerald-400 dark:border-emerald-700 text-emerald-800 dark:text-emerald-300',
        note:    'bg-emerald-50 dark:bg-emerald-950/70 border-emerald-400 dark:border-emerald-700 text-emerald-800 dark:text-emerald-300'
    };
    return map[type] || 'bg-slate-100 dark:bg-gray-800 border-slate-400 text-slate-700 dark:text-slate-300';
}

// =========================================================
// DAY PREVIEW POPOVER LOGIC
// =========================================================
function openDayPreview(dateStr, evt) {
    if (!dateStr) return;

    if (activePopover.value && activePopover.value.dateStr === dateStr) {
        activePopover.value = null;
        return;
    }

    selectedDate.value = dateStr;

    let posX = 0;
    let posY = 0;
    const isMobile = window.innerWidth < 640;

    if (!isMobile && calendarContainerRef.value) {
        const containerRect = calendarContainerRef.value.getBoundingClientRect();
        const popoverWidth = 320;
        const popoverHeight = 320;

        if (evt && evt.currentTarget) {
            const targetRect = evt.currentTarget.getBoundingClientRect();
            // Center horizontally on clicked target, clamped inside container
            const targetCenterX = targetRect.left + (targetRect.width / 2) - containerRect.left;
            let left = targetCenterX - (popoverWidth / 2);
            if (left < 16) left = 16;
            if (left + popoverWidth > containerRect.width - 16) {
                left = containerRect.width - popoverWidth - 16;
            }

            // Place below target if fits, otherwise place above
            const targetBottom = targetRect.bottom - containerRect.top + 8;
            const targetTop = targetRect.top - containerRect.top - 8;

            let top = targetBottom;
            if (targetBottom + popoverHeight > containerRect.height - 16) {
                if (targetTop - popoverHeight > 16) {
                    top = targetTop - popoverHeight;
                } else {
                    top = Math.max(16, containerRect.height - popoverHeight - 16);
                }
            }

            posX = Math.round(Math.max(16, left));
            posY = Math.round(Math.max(16, top));
        } else {
            // Clean fallback: center within the calendar container
            posX = Math.round(Math.max(16, (containerRect.width - popoverWidth) / 2));
            posY = Math.round(Math.max(16, (containerRect.height - popoverHeight) / 2));
        }
    }

    activePopover.value = {
        dateStr,
        x: posX,
        y: posY,
        isMobile
    };
}

function closeDayPreview() {
    activePopover.value = null;
}

const popoverEvents = computed(() => {
    if (!activePopover.value || !activePopover.value.dateStr) return [];
    return filteredActivities.value.filter(a => {
        return getActivityDateStr(a.schedule_from) === activePopover.value.dateStr;
    }).sort((a, b) => {
        return new Date(a.schedule_from) - new Date(b.schedule_from);
    });
});

function onPopoverEventClick(ev) {
    selectEvent(ev);
    closeDayPreview();
}

function onPopoverOpenDay() {
    if (activePopover.value) {
        const d = activePopover.value.dateStr;
        closeDayPreview();
        jumpToDay(d);
    }
}

function onPopoverAddActivity() {
    if (activePopover.value) {
        const d = activePopover.value.dateStr;
        closeDayPreview();
        openAddModal(d);
    }
}

function onMiniCellClick(cell, evt) {
    selectMiniDate(cell.dateStr, cell.isOtherMonth);
    openDayPreview(cell.dateStr, evt);
    if (window.innerWidth < 1024) {
        showMobileSidebar.value = false;
    }
}

function handleDocumentClick(e) {
    if (!activePopover.value) return;
    const isInside = e.target.closest('.popover-card-container');
    const isTrigger = e.target.closest('.date-preview-trigger');
    if (!isInside && !isTrigger) {
        closeDayPreview();
    }
}

function handleKeydown(e) {
    if (e.key === 'Escape' || e.key === 'Esc') {
        if (activePopover.value) {
            closeDayPreview();
        } else if (selectedEvent.value) {
            selectedEvent.value = null;
        }
    }
}

function handleActivityCreated(e) {
    const act = e.detail;
    if (!act || !act.id) return;

    // Push into activities if not already present
    const exists = activities.value.some(a => a.id === act.id);
    if (!exists) {
        activities.value.push(act);
    }

    // Immediately navigate calendar to newly scheduled activity's date
    if (act.schedule_from) {
        const actDateStr = getActivityDateStr(act.schedule_from);
        if (actDateStr) {
            selectedDate.value = actDateStr;
            const d = parseSafeDate(actDateStr);
            miniCalendarYear.value = d.getFullYear();
            miniCalendarMonth.value = d.getMonth() + 1;
        }
    }
}

function handleActivityUpdated(e) {
    const act = e.detail;
    if (!act || !act.id) return;
    const idx = activities.value.findIndex(a => a.id === act.id);
    if (idx !== -1) {
        activities.value[idx] = Object.assign({}, activities.value[idx], act);
    } else {
        activities.value.push(act);
    }
}

function handleActivityDeleted(e) {
    const actId = e.detail?.id || e.detail;
    if (!actId) return;
    activities.value = activities.value.filter(a => a.id !== actId);
    if (selectedEvent.value && selectedEvent.value.id === actId) {
        selectedEvent.value = null;
    }
}

// =========================================================
// LIFECYCLE HOOKS
// =========================================================
onMounted(() => {
    window.addEventListener('click', handleDocumentClick);
    window.addEventListener('keydown', handleKeydown);
    window.addEventListener('activity:created', handleActivityCreated);
    window.addEventListener('activity:updated', handleActivityUpdated);
    window.addEventListener('activity:deleted', handleActivityDeleted);

    if (props.initialMonth) {
        const parts = props.initialMonth.split('-');
        if (parts.length === 2) {
            miniCalendarYear.value = parseInt(parts[0], 10);
            miniCalendarMonth.value = parseInt(parts[1], 10);
            const todayYM = todayDateStr.value.slice(0, 7);
            if (props.initialMonth !== todayYM) {
                selectedDate.value = `${parts[0]}-${parts[1]}-01`;
            }
        }
    }
    
    // Mark initial month as fetched
    const pad = (n) => String(n).padStart(2, '0');
    fetchedMonths.add(`${miniCalendarYear.value}-${pad(miniCalendarMonth.value)}`);
});

onUnmounted(() => {
    window.removeEventListener('click', handleDocumentClick);
    window.removeEventListener('keydown', handleKeydown);
    window.removeEventListener('activity:created', handleActivityCreated);
    window.removeEventListener('activity:updated', handleActivityUpdated);
    window.removeEventListener('activity:deleted', handleActivityDeleted);
});
</script>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
    height: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}
.dark .custom-scrollbar::-webkit-scrollbar-thumb {
    background: #374151;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
.dark .custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #4b5563;
}

.custom-dark-scrollbar::-webkit-scrollbar {
    width: 5px;
    height: 5px;
}
.custom-dark-scrollbar::-webkit-scrollbar-track {
    background: #0f172a;
}
.custom-dark-scrollbar::-webkit-scrollbar-thumb {
    background: #334155;
    border-radius: 4px;
}
.custom-dark-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #475569;
}

::-webkit-scrollbar {
    width: 6px;
    height: 6px;
}
::-webkit-scrollbar-track {
    background: transparent;
}
::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}
.dark ::-webkit-scrollbar-thumb {
    background: #374151;
}
::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
.dark ::-webkit-scrollbar-thumb:hover {
    background: #4b5563;
}
</style>
