{!! view_render_event('admin.leads.index.kanban.before') !!}

<!-- Kanban Vue Component -->
<v-leads-kanban ref="leadsKanban">
    <div class="flex flex-col gap-4">
        <!-- Shimmer -->
        <x-admin::shimmer.leads.index.kanban />
    </div>
</v-leads-kanban>

{!! view_render_event('admin.leads.index.kanban.after') !!}

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-leads-kanban-template"
    >
        <template v-if="isLoading">
            <div class="flex flex-col gap-4">
                <x-admin::shimmer.leads.index.kanban />
            </div>
        </template>

        <template v-else>
            <div class="flex flex-col gap-4">
                <!-- BEGIN: Pipeline Summary Metrics Banner (4 Executive KPI Cards) -->
                <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5 sm:gap-4" data-purpose="pipeline-summary-stats">
                    <!-- Metric 1: Pipeline Value -->
                    <div class="flex items-center gap-3.5 rounded-2xl border border-slate-100 bg-white p-3.5 sm:p-4 shadow-xs dark:border-gray-800 dark:bg-gray-900 transition hover:shadow-sm min-w-0 overflow-hidden">
                        <div class="flex h-11 w-11 sm:h-12 sm:w-12 shrink-0 items-center justify-center rounded-2xl bg-blue-100/70 text-blue-600 dark:bg-blue-950/70 dark:text-blue-400">
                            <!-- Stacked Coins SVG -->
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <ellipse cx="12" cy="6" rx="8" ry="3"></ellipse>
                                <path d="M4 6v6c0 1.66 3.58 3 8 3s8-1.34 8-3V6"></path>
                                <path d="M4 12v6c0 1.66 3.58 3 8 3s8-1.34 8-3v-6"></path>
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1 overflow-hidden">
                            <p class="text-[11px] sm:text-xs font-semibold text-blue-600 dark:text-blue-400 uppercase tracking-wide truncate block">Pipeline Value</p>
                            <div class="mt-0.5 min-w-0 max-w-full overflow-hidden flex items-baseline">
                                <span class="auto-fit-text text-xl sm:text-2xl font-bold tracking-tight text-gray-900 dark:text-white" data-auto-fit data-min-font-size="12" data-max-font-size="24" style="font-weight: 700 !important;" :title="pipelineMetrics.titleValue">
                                    @{{ pipelineMetrics.formattedValue }}
                                </span>
                            </div>
                            <div class="mt-1 flex items-center min-w-0">
                                <span class="inline-flex max-w-full truncate items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-600 border border-blue-100 dark:bg-blue-950/60 dark:text-blue-300 dark:border-blue-900/60 shrink-0">
                                    <svg class="w-3 h-3 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"/><path d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z"/></svg>
                                    <span class="truncate">@{{ pipelineMetrics.totalDeals }} deals</span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Metric 2: Active Leads -->
                    <div class="flex items-center gap-3.5 rounded-2xl border border-slate-100 bg-white p-3.5 sm:p-4 shadow-xs dark:border-gray-800 dark:bg-gray-900 transition hover:shadow-sm min-w-0 overflow-hidden">
                        <div class="flex h-11 w-11 sm:h-12 sm:w-12 shrink-0 items-center justify-center rounded-2xl bg-emerald-100/70 text-emerald-600 dark:bg-emerald-950/70 dark:text-emerald-400">
                            <!-- Flame SVG -->
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-emerald-500" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2C8 6.5 6 9.5 6 13a6 6 0 0 0 12 0c0-3.5-2-6.5-6-11zm0 15a3 3 0 0 1-3-3c0-1.5 1-2.8 2-4 .5 1.5 1.5 2.5 2 3a2 2 0 0 1-1 4z"/>
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1 overflow-hidden">
                            <p class="text-[11px] sm:text-xs font-semibold text-emerald-600 dark:text-emerald-400 uppercase tracking-wide truncate block">Active Leads</p>
                            <div class="mt-0.5 min-w-0 max-w-full overflow-hidden flex items-baseline">
                                <span class="auto-fit-text text-xl sm:text-2xl font-bold tracking-tight text-gray-900 dark:text-white" data-auto-fit data-min-font-size="12" data-max-font-size="24" style="font-weight: 700 !important;">
                                    @{{ pipelineMetrics.activeLeads }}
                                </span>
                            </div>
                            <div class="mt-1 flex items-center min-w-0">
                                <span class="inline-flex max-w-full truncate items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-600 border border-emerald-100 dark:bg-emerald-950/60 dark:text-emerald-300 dark:border-emerald-900/60 shrink-0">
                                    <svg class="w-3 h-3 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M5 4a1 1 0 00-2 0v7.268a2 2 0 000 3.464V16a1 1 0 102 0v-1.268a2 2 0 000-3.464V4zM11 4a1 1 0 10-2 0v1.268a2 2 0 000 3.464V16a1 1 0 102 0V8.732a2 2 0 000-3.464V4zM16 3a1 1 0 011 1v7.268a2 2 0 010 3.464V16a1 1 0 11-2 0v-1.268a2 2 0 010-3.464V4a1 1 0 011-1z"/></svg>
                                    <span class="truncate">@{{ pipelineMetrics.activeStages }} stages</span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Metric 3: Won This Month -->
                    <div class="flex items-center gap-3.5 rounded-2xl border border-slate-100 bg-white p-3.5 sm:p-4 shadow-xs dark:border-gray-800 dark:bg-gray-900 transition hover:shadow-sm min-w-0 overflow-hidden">
                        <div class="flex h-11 w-11 sm:h-12 sm:w-12 shrink-0 items-center justify-center rounded-2xl bg-teal-100/70 text-teal-600 dark:bg-teal-950/70 dark:text-teal-400">
                            <!-- Calendar Checklist SVG -->
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                                <path d="M9 16l2 2 4-4"></path>
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1 overflow-hidden">
                            <p class="text-[11px] sm:text-xs font-semibold text-teal-600 dark:text-teal-400 uppercase tracking-wide truncate block">Won This Month</p>
                            <div class="mt-0.5 min-w-0 max-w-full overflow-hidden flex items-baseline">
                                <span class="auto-fit-text text-xl sm:text-2xl font-bold tracking-tight text-gray-900 dark:text-white" data-auto-fit data-min-font-size="12" data-max-font-size="24" style="font-weight: 700 !important;" :title="pipelineMetrics.titleWonValue">
                                    @{{ pipelineMetrics.formattedWonValue }}
                                </span>
                            </div>
                            <div class="mt-1 flex items-center min-w-0">
                                <span class="inline-flex max-w-full truncate items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-teal-50 text-teal-600 border border-teal-100 dark:bg-teal-950/60 dark:text-teal-300 dark:border-teal-900/60 shrink-0">
                                    <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                    <span class="truncate">@{{ pipelineMetrics.wonCount }} closed</span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Metric 4: Conversion Rate -->
                    <div class="flex items-center gap-3.5 rounded-2xl border border-slate-100 bg-white p-3.5 sm:p-4 shadow-xs dark:border-gray-800 dark:bg-gray-900 transition hover:shadow-sm min-w-0 overflow-hidden">
                        <div class="flex h-11 w-11 sm:h-12 sm:w-12 shrink-0 items-center justify-center rounded-2xl bg-pink-100/70 text-pink-500 dark:bg-pink-950/70 dark:text-pink-400">
                            <!-- Lightning Bolt SVG -->
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" viewBox="0 0 24 24" fill="currentColor">
                                <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1 overflow-hidden">
                            <p class="text-[11px] sm:text-xs font-semibold text-pink-500 dark:text-pink-400 uppercase tracking-wide truncate block">Conversion Rate</p>
                            <div class="mt-0.5 min-w-0 max-w-full overflow-hidden flex items-baseline">
                                <span class="auto-fit-text text-xl sm:text-2xl font-bold tracking-tight text-gray-900 dark:text-white" data-auto-fit data-min-font-size="12" data-max-font-size="24" style="font-weight: 700 !important;">
                                    @{{ pipelineMetrics.conversionRate }}
                                </span>
                            </div>
                            <div class="mt-1 flex items-center min-w-0">
                                <span class="inline-flex max-w-full truncate items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-pink-50 text-pink-500 border border-pink-100 dark:bg-pink-950/60 dark:text-pink-300 dark:border-pink-900/60 shrink-0">
                                    <span class="h-1.5 w-1.5 rounded-full bg-pink-500 shrink-0"></span>
                                    <span class="truncate">Pipeline Health</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- END: Pipeline Summary Metrics Banner -->

                @include('admin::leads.index.kanban.toolbar')

                {!! view_render_event('admin.leads.index.kanban.content.before') !!}

                <!-- Mobile Stage Switcher Tabs (visible on mobile only) -->
                <div class="flex items-center gap-2 overflow-x-auto pb-1 lg:hidden">
                    <button
                        v-for="(stage, index) in visibleStages"
                        :key="'tab-' + stage.id"
                        @click="activeMobileStageId = stage.id"
                        type="button"
                        class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-bold shrink-0 transition"
                        :class="activeMobileStageId === stage.id ? 'bg-[#6366f1] text-white shadow-sm' : 'bg-white text-slate-700 border border-slate-200 dark:bg-gray-800 dark:text-slate-200 dark:border-gray-700'"
                    >
                        <span>@{{ stage.name }}</span>
                        <span class="px-1.5 py-0.2 text-[10px] rounded-full" :class="activeMobileStageId === stage.id ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-700 dark:bg-gray-700 dark:text-slate-200'">
                            @{{ getStageCount(stage) }}
                        </span>
                    </button>
                </div>

                <!-- Kanban Pipeline Columns Container (4-Column Balanced Grid on Desktop) -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-2.5 w-full">
                    <!-- Stage Column -->
                    <div
                        class="flex flex-col rounded-2xl p-2 sm:p-2.5 shadow-2xs transition-all duration-200"
                        :class="[getStageTheme(stage).cardBg, { 'max-lg:hidden': activeMobileStageId && activeMobileStageId !== stage.id }]"
                        :style="getStageTheme(stage).boxStyle"
                        v-for="(stage, index) in visibleStages"
                        :key="stage.id"
                    >
                        {!! view_render_event('admin.leads.index.kanban.content.stage.header.before') !!}

                        <!-- Stage Header -->
                        <div class="flex flex-col pb-2.5 border-b border-slate-300/70 dark:border-slate-300/70">
                            <!-- Stage Title, Count Badge and Quick Add -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="h-2.5 w-2.5 rounded-full" :class="getStageTheme(stage).dot" :style="getStageTheme(stage).dotStyle"></span>
                                    <h3 class="text-sm font-bold tracking-tight text-slate-900 dark:text-slate-900" style="color: #0f172a !important;">
                                        @{{ stage.name }}
                                    </h3>
                                    <span class="px-2 py-0.5 text-xs font-bold rounded-full" :class="getStageTheme(stage).badge" :style="getStageTheme(stage).badgeStyle">
                                        @{{ getStageCount(stage) }}
                                    </span>
                                </div>

                                @if (bouncer()->hasPermission('leads.create'))
                                    <a
                                        :href="'{{ route('admin.leads.create') }}' + '?stage_id=' + stage.id"
                                        class="flex h-6 w-6 items-center justify-center rounded-lg text-slate-500 transition hover:bg-black/10 hover:text-slate-900 dark:text-slate-600 dark:hover:bg-black/10 dark:hover:text-slate-900"
                                        title="Quick Add Lead"
                                        target="_blank"
                                    >
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 4v16m8-8H4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"></path>
                                        </svg>
                                    </a>
                                @endif
                            </div>

                            <!-- Stage Financial Value -->
                            <div class="mt-2 flex items-center justify-between text-xs min-w-0">
                                <span class="font-semibold text-slate-600 dark:text-slate-600 truncate mr-1" style="color: #475569 !important;">Stage Value</span>
                                <span class="font-bold text-slate-900 dark:text-slate-900 auto-fit-text min-w-0 max-w-[65%]" data-auto-fit data-min-font-size="9" data-max-font-size="12" style="color: #0f172a !important;">
                                    @{{ formatStageValue(stage) }}
                                </span>
                            </div>

                            <!-- Stage Accent Line -->
                            <div class="mt-2 h-1 w-full overflow-hidden rounded-full bg-slate-200/80 dark:bg-slate-200/80">
                                <div
                                    class="h-1 rounded-full transition-all duration-300"
                                    :class="getStageTheme(stage).bar"
                                    :style="getStageTheme(stage).barStyle"
                                    style="width: 100%;"
                                ></div>
                            </div>
                        </div>

                        {!! view_render_event('admin.leads.index.kanban.content.stage.header.after') !!}

                        {!! view_render_event('admin.leads.index.kanban.content.stage.body.before') !!}

                        <!-- Draggable Stage Lead Cards List -->
                        <draggable
                            class="flex min-h-[160px] h-[calc(100vh-420px)] flex-col gap-2.5 overflow-y-auto pt-2.5 journal-scroll"
                            :class="{ 'justify-center': getStageFilteredLeads(stage).length === 0 }"
                            ghost-class="draggable-ghost"
                            handle=".lead-item"
                            v-bind="{animation: 200}"
                            :list="getStageFilteredLeads(stage)"
                            item-key="id"
                            group="leads"
                            @scroll="handleScroll(stage, $event)"
                            @change="handleUpdate(stage, $event)"
                        >
                            <template #header>
                                <!-- Empty Indicator -->
                                <div
                                    class="flex flex-col items-center justify-center rounded-2xl border-2 border-dashed border-slate-300/80 p-5 text-center transition dark:border-slate-300/80"
                                    v-if="! getStageFilteredLeads(stage).length"
                                >
                                    <div class="text-3xl mb-1.5">
                                        @{{ getStageTheme(stage).emoji }}
                                    </div>

                                    <p class="text-xs font-bold text-slate-800 dark:text-slate-800" style="color: #1e293b !important;">
                                        No leads in @{{ stage.name }}
                                    </p>

                                    <p class="mt-0.5 text-[11px] text-slate-500 dark:text-slate-500" style="color: #64748b !important;">
                                        Drop leads here
                                    </p>
                                </div>
                            </template>

                            <!-- Lead Card -->
                            <template #item="{ element, index }">
                                {!! view_render_event('admin.leads.index.kanban.content.stage.body.card.before') !!}

                                <a
                                    class="lead-item group relative flex cursor-grab flex-col gap-2 rounded-2xl border border-slate-200/90 bg-white p-2.5 shadow-2xs transition-all duration-150 hover:-translate-y-0.5 hover:border-indigo-300 hover:shadow-md dark:border-gray-800 dark:bg-gray-900"
                                    :href="'{{ route('admin.leads.view', 'replaceId') }}'.replace('replaceId', element.id)"
                                >
                                    {!! view_render_event('admin.leads.index.kanban.content.stage.body.card.header.before') !!}

                                    <!-- Top Row: Avatar + Title/Org + Price + 3-Dots Menu -->
                                    <div class="flex items-start justify-between gap-1.5 min-w-0">
                                        <div class="flex items-start gap-1.5 min-w-0 flex-1 overflow-hidden pr-0.5">
                                            <!-- Circular Initials Avatar -->
                                            <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full font-bold text-[10px] shadow-2xs" :class="getAvatarColor(element)">
                                                @{{ getInitials(element) }}
                                            </div>

                                            <!-- Contact / Lead Name & Subtitle -->
                                            <div class="flex flex-col min-w-0 flex-1 overflow-hidden">
                                                <span class="text-[11.5px] sm:text-[12px] font-bold text-slate-800 leading-tight group-hover:text-indigo-600 transition dark:text-white truncate block" :title="getCardTitle(element)">
                                                    @{{ getCardTitle(element) }}
                                                </span>

                                                <span class="text-[10px] sm:text-[10.5px] text-slate-400 font-medium leading-tight mt-0.5 truncate block" :title="getCardSubtitle(element)" v-if="getCardSubtitle(element)">
                                                    @{{ getCardSubtitle(element) }}
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Price Pill & 3 Dots Menu -->
                                        <div class="flex items-center gap-0.5 shrink-0 ml-auto">
                                            <span class="px-1.5 py-0.5 bg-emerald-50 text-emerald-600 dark:bg-emerald-950/70 dark:text-emerald-300 font-bold text-[9.5px] sm:text-[10px] rounded-lg border border-emerald-200/60 dark:border-emerald-800/60 whitespace-nowrap tracking-tight shrink-0">
                                                @{{ formatCardValue(element) }}
                                            </span>

                                            <span class="text-slate-400 hover:text-slate-600 px-0.5 text-xs leading-none select-none cursor-pointer">⋮</span>
                                        </div>
                                    </div>

                                    {!! view_render_event('admin.leads.index.kanban.content.stage.body.card.header.after') !!}

                                    <!-- Tags Row (Clean & Deduplicated) -->
                                    <div class="flex flex-wrap items-center gap-1.5" v-if="getCardTags(element).length">
                                        <template v-for="tag in getCardTags(element)">
                                            <span
                                                class="rounded-md px-2 py-0.5 text-[10px] font-semibold"
                                                :style="{
                                                    backgroundColor: tag.bg,
                                                    color: tag.color
                                                }"
                                            >
                                                @{{ tag.name }}
                                            </span>
                                        </template>
                                    </div>

                                    <!-- Footer: Agent & Relative Time -->
                                    <div class="flex items-center justify-between border-t border-slate-100 pt-2 text-xs text-slate-400 dark:border-gray-800">
                                        <div class="flex items-center gap-1.5 text-slate-500 dark:text-slate-400">
                                            <span class="flex h-4.5 w-4.5 items-center justify-center rounded-full bg-slate-100 text-[9px] font-bold text-slate-600 dark:bg-gray-800 dark:text-slate-300">
                                                @{{ getCardUser(element).initials }}
                                            </span>
                                            <span class="text-[10.5px] font-medium">@{{ getCardUser(element).name }}</span>
                                        </div>

                                        <div class="flex items-center text-slate-400">
                                            <span class="text-[10px] font-medium">
                                                @{{ getCardTime(element) }}
                                            </span>
                                        </div>
                                    </div>
                                </a>

                                {!! view_render_event('admin.leads.index.kanban.content.stage.body.card.after') !!}
                            </template>
                        </draggable>

                        {!! view_render_event('admin.leads.index.kanban.content.stage.body.after') !!}
                    </div>
                </div>

                {!! view_render_event('admin.leads.index.kanban.content.after') !!}

                <!-- BEGIN: Bottom Information & Action Bar -->
                <footer class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-slate-200/90 bg-white px-5 py-3 text-xs text-slate-500 shadow-2xs dark:border-gray-800 dark:bg-gray-900 mt-2">
                    <div class="flex items-center gap-3">
                        <span class="flex items-center gap-1.5 font-medium text-slate-700 dark:text-slate-300">
                            <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                            Drag &amp; drop cards between stages to automatically update lead status
                        </span>
                        <span class="hidden md:inline text-slate-300 dark:text-slate-700">|</span>
                        <span class="hidden md:inline text-slate-400">⚡ Instant pipeline valuation &amp; real-time stage tracking</span>
                    </div>
                    <div class="flex items-center gap-3 font-semibold text-slate-600 dark:text-slate-400">
                        @if (bouncer()->hasPermission('settings.lead.pipelines.index'))
                        <a
                            href="{{ route('admin.settings.pipelines.index') }}"
                            class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800"
                        >
                            <svg class="h-3.5 w-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span>Pipeline Settings</span>
                        </a>
                        @endif
                    </div>
                </footer>
                <!-- END: Bottom Information & Action Bar -->
            </div>

            <!-- Show modal for additional information while updating the leads into won or lost stage. -->
            <x-admin::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
                ref="stageUpdateForm"
                >
                <form @submit="handleSubmit($event, handleFormSubmit)">
                    <!-- Modal -->
                    <x-admin::modal
                        ref="stageUpdateModal"
                        @toggle="handleCloseModal"
                    >
                        <!-- Header -->
                        <x-slot:header>
                            <h3 class="text-base font-semibold dark:text-white">
                                @lang('admin::app.leads.index.kanban.stages.need-more-info')
                            </h3>
                        </x-slot>

                        <!-- Content -->
                        <x-slot:content>
                            <x-admin::form.control-group.control
                                type="hidden"
                                name="lead_pipeline_stage_id"
                                ::value="finalized.stage.id"
                            />

                            <!-- Won Value -->
                            <template v-if="finalized.stage.code == 'won'">
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.leads.index.kanban.stages.won-value')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="price"
                                        name="lead_value"
                                        ::value="finalized.lead.lead_value"
                                    />
                                </x-admin::form.control-group>
                            </template>

                            <!-- Lost Reason -->
                            <template v-else>
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.leads.index.kanban.stages.lost-reason')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="textarea"
                                        name="lost_reason"
                                    />
                                </x-admin::form.control-group>
                            </template>

                            <!-- Closed At -->
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label>
                                    @lang('admin::app.leads.index.kanban.stages.closed-at')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="datetime"
                                    name="closed_at"
                                    :label="trans('admin::app.leads.index.kanban.stages.closed-at')"
                                />

                                <x-admin::form.control-group.error control-name="closed_at"/>
                            </x-admin::form.control-group>
                        </x-slot>

                        <!-- Footer -->
                        <x-slot:footer>
                            <x-admin::button
                                class="primary-button"
                                :title="trans('admin::app.leads.index.kanban.stages.save-btn')"
                                ::loading="finalized.updating"
                                ::disabled="finalized.updating"
                            />
                        </x-slot>
                    </x-admin::modal>
                </form>
            </x-admin::form>
        </template>
    </script>

    <script type="module">
        app.component('v-leads-kanban', {
            template: '#v-leads-kanban-template',

            data() {
                return {
                    available: {
                        columns: @json($columns),
                    },

                    applied: {
                        filters: {
                            columns: [],
                        }
                    },

                    finalized: {
                        lead: null,
                        stage: null,
                        updating: false,
                    },

                    stages: @json($pipeline->stages->toArray()),

                    stageLeads: {},

                    activeMobileStageId: null,

                    searchQuery: '',

                    isLoading: true,

                    isLoadingMore: false,
                };
            },

            computed: {
                /**
                 * Filter to the active pipeline stages (excluding won and lost).
                 */
                visibleStages() {
                    let list = [];
                    for (let [sortOrder, stage] of Object.entries(this.stageLeads)) {
                        const code = (stage.code || stage.name || '').toLowerCase();
                        if (!code.includes('won') && !code.includes('lost')) {
                            list.push(stage);
                        }
                    }
                    if (list.length && !this.activeMobileStageId) {
                        this.activeMobileStageId = list[0].id;
                    }
                    return list.length ? list : Object.values(this.stageLeads);
                },

                /**
                 * Dynamically calculate pipeline summary metrics across all user-scoped stages.
                 */
                pipelineMetrics() {
                    let totalVal = 0;
                    let totalDeals = 0;
                    let activeLeads = 0;
                    let activeStagesCount = 0;
                    let wonVal = 0;
                    let wonCount = 0;

                    const stagesList = Object.values(this.stageLeads || {});

                    stagesList.forEach(stage => {
                        const val = parseFloat(stage.lead_value) || 0;
                        const count = stage.leads?.meta?.total ?? (stage.leads?.data?.length || 0);
                        const code = (stage.code || stage.name || '').toLowerCase();

                        totalVal += val;
                        totalDeals += count;

                        if (code.includes('won')) {
                            wonVal += val;
                            wonCount += count;
                        } else if (!code.includes('lost')) {
                            activeLeads += count;
                            activeStagesCount++;
                        }
                    });

                    const formatCurrency = (num) => {
                        num = parseFloat(num) || 0;
                        if (num <= 0) return '₹0.00';
                        if (num >= 10000000) {
                            return '₹' + (num / 10000000).toFixed(2).replace(/\.00$/, '') + ' Cr';
                        } else if (num >= 100000) {
                            return '₹' + (num / 100000).toFixed(2).replace(/\.00$/, '') + ' L';
                        } else {
                            return '₹' + Math.round(num).toLocaleString('en-IN');
                        }
                    };

                    const conversionRate = totalDeals > 0 
                        ? ((wonCount / totalDeals) * 100).toFixed(1) + '%' 
                        : '0.0%';

                    return {
                        totalValue: totalVal,
                        formattedValue: formatCurrency(totalVal),
                        titleValue: totalVal > 0 ? '₹' + totalVal.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : '₹0.00',
                        totalDeals: totalDeals,
                        activeLeads: activeLeads,
                        activeStages: activeStagesCount,
                        wonValue: wonVal,
                        formattedWonValue: formatCurrency(wonVal),
                        titleWonValue: wonVal > 0 ? '₹' + wonVal.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : '₹0.00',
                        wonCount: wonCount,
                        conversionRate: conversionRate,
                    };
                },
            },

            mounted () {
                this.boot();

                this.$emitter.on('reload-datagrids', () => {
                    this.get()
                        .then(response => {
                            for (let [sortOrder, data] of Object.entries(response.data)) {
                                this.stageLeads[sortOrder] = data;
                            }
                        });
                });
            },

            methods: {
                boot() {
                    let kanbans = this.getKanbans();

                    if (kanbans?.length) {
                        const currentKanban = kanbans.find(({ src }) => src === this.src);

                        if (currentKanban) {
                            this.applied.filters = currentKanban.applied.filters;

                            this.get()
                                .then(response => {
                                    for (let [sortOrder, data] of Object.entries(response.data)) {
                                        this.stageLeads[sortOrder] = data;
                                    }
                                });

                            return;
                        }
                    }

                    this.get()
                        .then(response => {
                            for (let [sortOrder, data] of Object.entries(response.data)) {
                                this.stageLeads[sortOrder] = data;
                            }
                        });
                },

                get(requestedParams = {}) {
                    let params = {
                        search: '',
                        searchFields: '',
                        pipeline_id: "{{ request('pipeline_id') }}",
                        limit: 10,
                    };

                    this.applied.filters.columns.forEach((column) => {
                        if (column.index === 'all') {
                            if (! column.value.length) {
                                return;
                            }

                            params['search'] += `title:${column.value.join(',')};`;
                            params['searchFields'] += `title:like;`;

                            return;
                        }

                        if (column.type === 'date' || column.type === 'datetime') {
                            if (column.value.length) {
                                params[column.index] = column.value;
                            }

                            return;
                        }

                        params['search'] += column.filterable_type === 'searchable_dropdown'
                            ? `${column.index}:${column.value.map(option => option.value).join(',')};`
                            : `${column.index}:${column.value.join(',')};`;

                        params['searchFields'] += `${column.index}:${column.search_field};`;
                    });

                    return this.$axios
                        .get("{{ route('admin.leads.get') }}", {
                            params: {
                                ...params,
                                ...requestedParams,
                            }
                        })
                        .then(response => {
                            this.isLoading = false;
                            this.updateKanbans();
                            return response;
                        })
                        .catch(error => {
                            this.isLoading = false;
                        });
                },

                append(requestedParams = {}) {
                    let params = {
                        search: '',
                        searchFields: '',
                    };

                    this.applied.filters.columns.forEach((column) => {
                        if (column.index === 'all') {
                            if (! column.value.length) {
                                return;
                            }

                            params['search'] += `title:${column.value.join(',')};`;
                            params['searchFields'] += `title:like;`;

                            return;
                        }

                        if (column.type === 'date' || column.type === 'datetime') {
                            if (column.value.length) {
                                params[column.index] = column.value;
                            }

                            return;
                        }

                        params['search'] += column.filterable_type === 'searchable_dropdown'
                            ? `${column.index}:${column.value.map(option => option.value).join(',')};`
                            : `${column.index}:${column.value.join(',')};`;

                        params['searchFields'] += `${column.index}:${column.search_field};`;
                    });

                    return this.$axios
                        .get("{{ route('admin.leads.get') }}", {
                            params: {
                                ...params,
                                ...requestedParams,
                            }
                        })
                        .then(response => {
                            let stage = Object.values(response.data)[0];
                            this.stageLeads[stage.sort_order].leads.data.push(...stage.leads.data);
                            this.stageLeads[stage.sort_order].leads.meta = stage.leads.meta;
                        });
                },

                search(filters) {
                    const col = filters.columns?.find(c => c.index === 'all');
                    this.searchQuery = (col?.value?.[0] || '').toLowerCase().trim();
                },

                filter(filters) {
                    this.applied.filters = filters;
                    this.get()
                        .then(response => {
                            for (let [sortOrder, data] of Object.entries(response.data)) {
                                this.stageLeads[sortOrder] = data;
                            }
                        });
                },

                getStageFilteredLeads(stage) {
                    if (!stage.leads || !stage.leads.data) return [];
                    if (!this.searchQuery) return stage.leads.data;
                    return stage.leads.data.filter(element => {
                        const personName = (element.person ? element.person.name : '').toLowerCase();
                        const orgName = (element.person && element.person.organization ? element.person.organization.name : '').toLowerCase();
                        const title = (element.title || '').toLowerCase();
                        return personName.includes(this.searchQuery) ||
                               orgName.includes(this.searchQuery) ||
                               title.includes(this.searchQuery);
                    });
                },

                getStageCount(stage) {
                    if (this.searchQuery) {
                        return this.getStageFilteredLeads(stage).length;
                    }
                    return stage.leads?.meta?.total || (stage.leads?.data?.length || 0);
                },

                handleUpdate(stage, event) {
                    let lead = null;
                    if (event.added) {
                        lead = event.added.element;
                    } else if (event.moved) {
                        lead = event.moved.element;
                    }

                    if (! lead) return;

                    if (['won', 'lost'].includes(stage.code)) {
                        this.finalized.stage = stage;
                        this.finalized.lead = lead;
                        this.toggleStageUpdateModal();
                        return;
                    }

                    this.updateStage("{{ route('admin.leads.stage.update', '__LEAD_ID__') }}".replace('__LEAD_ID__', lead.id), {
                        lead_pipeline_stage_id: stage.id,
                    })
                        .then(response => {
                            this.get().then(res => {
                                for (let [sortOrder, data] of Object.entries(res.data)) {
                                    this.stageLeads[sortOrder] = data;
                                }
                            });
                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                        })
                        .catch(error => {
                            this.$emitter.emit('add-flash', { type: 'error', message: error.response?.data?.message || 'Error updating stage' });
                        });
                },

                updateStage(url, params) {
                    return this.$axios.put(url, params);
                },

                handleFormSubmit(params) {
                    this.finalized.updating = true;
                    this.updateStage("{{ route('admin.leads.stage.update', '__LEAD_ID__') }}".replace('__LEAD_ID__', this.finalized.lead.id), {
                        ...params,
                        lead_pipeline_stage_id: this.finalized.stage.id,
                    })
                        .then(response => {
                            this.toggleStageUpdateModal();
                            this.resetFinalized();
                            this.get().then(res => {
                                for (let [sortOrder, data] of Object.entries(res.data)) {
                                    this.stageLeads[sortOrder] = data;
                                }
                            });
                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                        })
                        .catch(error => {
                            this.$emitter.emit('add-flash', { type: 'error', message: error.response?.data?.message || 'Error updating stage' });
                        })
                        .finally(() => {
                            this.finalized.updating = false;
                        });
                },

                resetFinalized() {
                    this.finalized = {
                        lead: null,
                        stage: null,
                        updating: false,
                    };
                },

                handleCloseModal(state) {
                    if (state && state.isActive) return;
                    this.resetFinalized();
                    this.get().then(response => {
                        for (let [sortOrder, data] of Object.entries(response.data)) {
                            this.stageLeads[sortOrder] = data;
                        }
                    });
                },

                toggleStageUpdateModal() {
                    this.$refs.stageUpdateModal.toggle();
                },

                handleScroll(stage, event) {
                    const bottom = event.target.scrollHeight - event.target.scrollTop - event.target.clientHeight <= 5;
                    if (! bottom || this.isLoadingMore) return;
                    if (this.stageLeads[stage.sort_order].leads.meta.current_page == this.stageLeads[stage.sort_order].leads.meta.last_page) return;

                    this.isLoadingMore = true;
                    this.append({
                        pipeline_stage_id: stage.id,
                        pipeline_id: stage.lead_pipeline_id,
                        page: this.stageLeads[stage.sort_order].leads.meta.current_page + 1,
                        limit: 10,
                    }).finally(() => {
                        this.isLoadingMore = false;
                    });
                },

                formatStageValue(stage) {
                    if (!stage || !stage.lead_value) return '₹0.00';
                    const num = parseFloat(stage.lead_value) || 0;
                    if (num <= 0) return '₹0.00';
                    if (num >= 10000000) return '₹' + (num / 10000000).toFixed(2).replace(/\.00$/, '') + ' Cr';
                    if (num >= 100000) return '₹' + (num / 100000).toFixed(2).replace(/\.00$/, '') + ' L';
                    return '₹' + Number(num).toLocaleString('en-IN');
                },

                getCardTitle(element) {
                    if (element.person && element.person.name) {
                        return element.person.name;
                    }
                    return element.title || 'Lead';
                },

                getCardSubtitle(element) {
                    const title = (element.title || '').trim();
                    const personName = element.person ? element.person.name : '';

                    if (title.toLowerCase() === 'new' && !personName) {
                        return 'Requirement: new';
                    }
                    if (element.person && element.person.organization && element.person.organization.name) {
                        return element.person.organization.name;
                    }
                    if (title && title !== personName) {
                        if (title.toLowerCase().startsWith('requirement:')) {
                            return title;
                        }
                        return 'Requirement: ' + title;
                    }
                    return '';
                },

                formatCardValue(element) {
                    if (!element) return '₹0.00';
                    const val = element.lead_value !== undefined && element.lead_value !== null ? parseFloat(element.lead_value) : null;
                    if (val !== null && !isNaN(val)) {
                        if (val <= 0) return '₹0.00';
                        if (val >= 10000000) {
                            return '₹' + (val / 10000000).toFixed(2).replace(/\.00$/, '') + ' Cr';
                        } else if (val >= 100000) {
                            return '₹' + (val / 100000).toFixed(2).replace(/\.00$/, '') + ' L';
                        }
                        return '₹' + Number(val).toLocaleString('en-IN');
                    }
                    return element.formatted_lead_value || '₹0.00';
                },

                getCardTags(element) {
                    if (element && element.tags && Array.isArray(element.tags) && element.tags.length) {
                        return element.tags.map(t => ({
                            name: t.name || t,
                            bg: t.color ? t.color + '20' : '#eff6ff',
                            color: t.color || '#2563eb',
                        }));
                    }
                    return [];
                },

                getStageTheme(stage) {
                    const name = (stage.code || stage.name || '').toLowerCase();
                    if (name.includes('new') || name.includes('inquir')) {
                        return {
                            dot: 'bg-blue-500',
                            dotStyle: 'background-color: #3b82f6 !important;',
                            badge: 'bg-blue-100 text-blue-700 font-bold',
                            badgeStyle: 'background-color: #dbeafe !important; color: #1d4ed8 !important;',
                            cardBg: 'bg-[#f0f7ff] border border-[#dbeafe]',
                            boxStyle: 'background-color: #f0f7ff !important; border: 1px solid #dbeafe !important;',
                            bar: 'bg-blue-500',
                            barStyle: 'background-color: #3b82f6 !important;',
                            emoji: '🏢'
                        };
                    }
                    if (name.includes('follow')) {
                        return {
                            dot: 'bg-amber-500',
                            dotStyle: 'background-color: #f59e0b !important;',
                            badge: 'bg-amber-100 text-amber-700 font-bold',
                            badgeStyle: 'background-color: #fef3c7 !important; color: #b45309 !important;',
                            cardBg: 'bg-[#fffbeb] border border-[#fef3c7]',
                            boxStyle: 'background-color: #fffbeb !important; border: 1px solid #fef3c7 !important;',
                            bar: 'bg-amber-500',
                            barStyle: 'background-color: #f59e0b !important;',
                            emoji: '⏰'
                        };
                    }
                    if (name.includes('prospect') || name.includes('visit') || name.includes('demo')) {
                        return {
                            dot: 'bg-purple-500',
                            dotStyle: 'background-color: #8b5cf6 !important;',
                            badge: 'bg-purple-100 text-purple-700 font-bold',
                            badgeStyle: 'background-color: #f3e8ff !important; color: #7c3aed !important;',
                            cardBg: 'bg-[#faf5ff] border border-[#f3e8ff]',
                            boxStyle: 'background-color: #faf5ff !important; border: 1px solid #f3e8ff !important;',
                            bar: 'bg-purple-500',
                            barStyle: 'background-color: #8b5cf6 !important;',
                            emoji: '🏡'
                        };
                    }
                    if (name.includes('negotiat')) {
                        return {
                            dot: 'bg-rose-500',
                            dotStyle: 'background-color: #f43f5e !important;',
                            badge: 'bg-rose-100 text-rose-700 font-bold',
                            badgeStyle: 'background-color: #ffe4e6 !important; color: #e11d48 !important;',
                            cardBg: 'bg-[#fff1f2] border border-[#ffe4e6]',
                            boxStyle: 'background-color: #fff1f2 !important; border: 1px solid #ffe4e6 !important;',
                            bar: 'bg-rose-500',
                            barStyle: 'background-color: #f43f5e !important;',
                            emoji: '🤝'
                        };
                    }
                    if (name.includes('won')) {
                        return {
                            dot: 'bg-emerald-500',
                            dotStyle: 'background-color: #22c55e !important;',
                            badge: 'bg-emerald-100 text-emerald-700 font-bold',
                            badgeStyle: 'background-color: #dcfce7 !important; color: #15803d !important;',
                            cardBg: 'bg-[#f0fdf4] border border-[#dcfce7]',
                            boxStyle: 'background-color: #f0fdf4 !important; border: 1px solid #dcfce7 !important;',
                            bar: 'bg-emerald-500',
                            barStyle: 'background-color: #22c55e !important;',
                            emoji: '🎉'
                        };
                    }
                    if (name.includes('lost')) {
                        return {
                            dot: 'bg-red-500',
                            dotStyle: 'background-color: #ef4444 !important;',
                            badge: 'bg-red-100 text-red-700 font-bold',
                            badgeStyle: 'background-color: #fee2e2 !important; color: #b91c1c !important;',
                            cardBg: 'bg-[#fef2f2] border border-[#fee2e2]',
                            boxStyle: 'background-color: #fef2f2 !important; border: 1px solid #fee2e2 !important;',
                            bar: 'bg-red-500',
                            barStyle: 'background-color: #ef4444 !important;',
                            emoji: '❌'
                        };
                    }
                    return {
                        dot: 'bg-blue-500',
                        dotStyle: 'background-color: #3b82f6 !important;',
                        badge: 'bg-blue-100 text-blue-700 font-bold',
                        badgeStyle: 'background-color: #dbeafe !important; color: #1d4ed8 !important;',
                        cardBg: 'bg-slate-50 border border-slate-200 dark:border-gray-800 dark:bg-gray-900',
                        boxStyle: 'background-color: #f8faff !important; border: 1px solid #e2e8f0 !important;',
                        bar: 'bg-blue-500',
                        barStyle: 'background-color: #3b82f6 !important;',
                        emoji: '💼'
                    };
                },

                getInitials(param) {
                    if (param && typeof param === 'object') {
                        return this.getInitials(this.getCardTitle(param));
                    }
                    if (!param) return 'LE';
                    const trimmed = String(param).trim();
                    const parts = trimmed.split(/\s+/);
                    if (parts.length >= 2) {
                        return (parts[0][0] + parts[1][0]).toUpperCase();
                    }
                    return trimmed.substring(0, 2).toUpperCase();
                },

                getAvatarColor(param) {
                    let name = '';
                    if (param && typeof param === 'object') {
                        name = this.getCardTitle(param);
                    } else if (typeof param === 'string') {
                        name = param;
                    }
                    if (!name) return 'bg-purple-100 text-purple-700';

                    const palette = [
                        'bg-amber-100 text-amber-700',
                        'bg-indigo-100 text-indigo-700',
                        'bg-pink-100 text-pink-700',
                        'bg-rose-100 text-rose-700',
                        'bg-purple-100 text-purple-700',
                        'bg-emerald-100 text-emerald-700',
                        'bg-sky-100 text-sky-700',
                    ];
                    let hash = 0;
                    for (let i = 0; i < name.length; i++) hash = name.charCodeAt(i) + ((hash << 5) - hash);
                    return palette[Math.abs(hash) % palette.length];
                },

                getCardUser(element) {
                    if (element && element.user && element.user.name) {
                        const name = element.user.name;
                        const parts = name.trim().split(/\s+/);
                        const initials = parts.length >= 2 ? (parts[0][0] + parts[1][0]).toUpperCase() : name.substring(0, 2).toUpperCase();
                        return { name, initials };
                    }
                    return { name: 'User', initials: 'U' };
                },

                getCardTime(element) {
                    if (element && element.created_at) {
                        return this.timeAgo(element.created_at);
                    }
                    return 'Recently';
                },

                timeAgo(dateStr) {
                    if (!dateStr) return '';
                    try {
                        const date = new Date(dateStr);
                        const now = new Date();
                        const diffSec = Math.floor((now - date) / 1000);
                        if (diffSec < 60) return 'Just now';
                        const diffMin = Math.floor(diffSec / 60);
                        if (diffMin < 60) return diffMin + 'm ago';
                        const diffHr = Math.floor(diffMin / 60);
                        if (diffHr < 24) return diffHr + 'h ago';
                        const diffDays = Math.floor(diffHr / 24);
                        if (diffDays >= 7) {
                            const weeks = Math.floor(diffDays / 7);
                            return weeks + 'w ago';
                        }
                        return diffDays + 'd ago';
                    } catch (e) {
                        return '';
                    }
                },

                updateKanbans() {
                    let kanbans = this.getKanbans();
                    if (kanbans?.length) {
                        const currentKanban = kanbans.find(({ src }) => src === this.src);
                        if (currentKanban) {
                            kanbans = kanbans.map(kanban => {
                                if (kanban.src === this.src) {
                                    return {
                                        ...kanban,
                                        requestCount: ++kanban.requestCount,
                                        available: this.available,
                                        applied: this.applied,
                                    };
                                }
                                return kanban;
                            });
                        } else {
                            kanbans.push(this.getKanbanInitialProperties());
                        }
                    } else {
                        kanbans = [this.getKanbanInitialProperties()];
                    }
                    this.setKanbans(kanbans);
                },

                getKanbanInitialProperties() {
                    return {
                        src: this.src,
                        requestCount: 0,
                        available: this.available,
                        applied: this.applied,
                    };
                },

                getKanbansStorageKey() {
                    return 'kanbans';
                },

                getKanbans() {
                    let kanbans = localStorage.getItem(this.getKanbansStorageKey());
                    return JSON.parse(kanbans) ?? [];
                },

                setKanbans(kanbans) {
                    localStorage.setItem(this.getKanbansStorageKey(), JSON.stringify(kanbans));
                },
            }
        });
    </script>
@endPushOnce
