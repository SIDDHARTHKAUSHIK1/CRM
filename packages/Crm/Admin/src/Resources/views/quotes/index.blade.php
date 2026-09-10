<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.quotes.index.title')
    </x-slot>

    @php
        $formatIndian = function($num) {
            if ($num >= 10000000) {
                return '₹' . number_format($num / 10000000, 2) . ' Cr';
            } elseif ($num >= 100000) {
                return '₹' . number_format($num / 100000, 2) . ' L';
            } else {
                return core()->formatBasePrice($num, 2);
            }
        };

        $totalVal = $stats['totalValue'] ?? 0;
        $totalCnt = $stats['totalCount'] ?? 0;
        $actVal = $stats['activeValue'] ?? 0;
        $actCnt = $stats['activeCount'] ?? 0;
        $actPct = $stats['activePct'] ?? 0;
        $maxVal = $stats['maxQuoteVal'] ?? 0;
        $maxSubject = $stats['maxQuoteSubject'] ?? '-';
        $expiringCount = $stats['expiringSoonCount'] ?? 0;
    @endphp

    <v-quotes :initial-quotes='@json($quotesData)' :initial-stats='@json($stats)'>
        <!-- Shimmer Placeholder while loading -->
        <div class="flex flex-col gap-5">
            <div class="h-20 bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 animate-pulse"></div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="h-28 bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 animate-pulse"></div>
                <div class="h-28 bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 animate-pulse"></div>
                <div class="h-28 bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 animate-pulse"></div>
                <div class="h-28 bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 animate-pulse"></div>
            </div>
            <div class="h-96 bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 animate-pulse"></div>
        </div>
    </v-quotes>

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-quotes-template"
        >
            <div class="flex flex-col gap-5">
                <!-- Top Header Card -->
                <div class="flex flex-wrap items-center justify-between gap-4 rounded-2xl border border-gray-200 bg-white p-5 shadow-xs dark:border-gray-800 dark:bg-gray-900">
                    <div class="flex items-center gap-4">
                        <!-- Squircle Blue Document Icon Container -->
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-blue-100 text-blue-600 dark:bg-blue-950/60 dark:text-blue-400 shadow-xs">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                <line x1="16" y1="17" x2="8" y2="17"></line>
                                <polyline points="10 9 9 9 8 9"></polyline>
                            </svg>
                        </div>

                        <div>
                            <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                                Quotes
                            </h1>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                View and manage all your quotes in one place.
                            </p>
                        </div>
                    </div>

                    <!-- Right CTA Button -->
                    @if (bouncer()->hasPermission('quotes.create'))
                        <a
                            href="{{ route('admin.quotes.create') }}"
                            class="inline-flex items-center gap-2 rounded-xl px-4 py-2.5 text-sm font-medium shadow-xs transition-all hover:opacity-90 active:scale-95"
                            style="background-color: #6366f1; color: #ffffff;"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            <span>Create Quote</span>
                        </a>
                    @endif
                </div>

                <!-- Executive KPIs Banner (4 Summary Cards) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5 sm:gap-4">
                    <!-- Metric Card 1: Total Quote Value -->
                    <div class="flex items-start gap-3 rounded-2xl border border-gray-200/90 bg-white p-3.5 sm:p-4 shadow-xs dark:border-gray-800 dark:bg-gray-900 min-w-0 overflow-hidden">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-blue-100 text-blue-600 dark:bg-blue-950/60 dark:text-blue-400 shadow-2xs mt-0.5">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                <line x1="16" y1="17" x2="8" y2="17"></line>
                                <polyline points="10 9 9 9 8 9"></polyline>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0 overflow-hidden">
                            <div class="flex items-center justify-between gap-1 min-w-0 max-w-full">
                                <span class="text-[11px] font-semibold text-gray-500 dark:text-gray-400 truncate min-w-0">Total Quote Value</span>
                                <span class="text-[9px] font-bold text-emerald-700 bg-emerald-50 dark:bg-emerald-950/60 dark:text-emerald-300 px-1.5 py-0.5 rounded-full shrink-0">
                                    ↑ Active
                                </span>
                            </div>
                            <div class="mt-0.5 min-w-0 max-w-full overflow-hidden">
                                <span class="auto-fit-text text-xl sm:text-2xl font-bold text-gray-900 dark:text-white tracking-tight block" data-auto-fit data-min-font-size="12" data-max-font-size="24">
                                    {{ $formatIndian($totalVal) }}
                                </span>
                            </div>
                            <div class="text-[11px] text-gray-400 dark:text-gray-500 mt-0.5 truncate">
                                across {{ $totalCnt }} quotes
                            </div>
                            <div class="mt-2.5 h-1.5 w-full bg-slate-100 dark:bg-gray-800 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-purple-600 to-indigo-500 rounded-full" style="width: 100%"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Metric Card 2: Active Quotes -->
                    <div class="flex items-start gap-3 rounded-2xl border border-gray-200/90 bg-white p-3.5 sm:p-4 shadow-xs dark:border-gray-800 dark:bg-gray-900 min-w-0 overflow-hidden">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-600 dark:bg-emerald-950/60 dark:text-emerald-400 shadow-2xs mt-0.5">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                <polyline points="22 4 12 14.01 9 11.01"></polyline>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0 overflow-hidden">
                            <div class="flex items-center justify-between gap-1 min-w-0 max-w-full">
                                <span class="text-[11px] font-semibold text-gray-500 dark:text-gray-400 truncate min-w-0">Active Quotes</span>
                                <span class="text-[9px] font-bold text-emerald-700 bg-emerald-50 dark:bg-emerald-950/60 dark:text-emerald-300 px-1.5 py-0.5 rounded-full shrink-0">
                                    {{ $actPct }}%
                                </span>
                            </div>
                            <div class="mt-0.5 min-w-0 max-w-full overflow-hidden">
                                <span class="auto-fit-text text-xl sm:text-2xl font-bold text-gray-900 dark:text-white tracking-tight block" data-auto-fit data-min-font-size="12" data-max-font-size="24">
                                    {{ $actCnt }}
                                </span>
                            </div>
                            <div class="text-[11px] text-gray-400 dark:text-gray-500 mt-0.5 truncate">
                                valid and in pipeline
                            </div>
                            <div class="mt-2.5 h-1.5 w-full bg-slate-100 dark:bg-gray-800 rounded-full overflow-hidden">
                                <div class="h-full bg-emerald-500 rounded-full" style="width: {{ $actPct }}%"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Metric Card 3: Biggest Deals -->
                    <div class="flex items-start gap-3 rounded-2xl border border-gray-200/90 bg-white p-3.5 sm:p-4 shadow-xs dark:border-gray-800 dark:bg-gray-900 min-w-0 overflow-hidden">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-purple-100 text-purple-600 dark:bg-purple-950/60 dark:text-purple-400 shadow-2xs mt-0.5">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0 overflow-hidden">
                            <div class="flex items-center justify-between gap-1 min-w-0 max-w-full">
                                <span class="text-[11px] font-semibold text-gray-500 dark:text-gray-400 truncate min-w-0">Biggest Deals</span>
                                <span class="text-[9px] font-bold text-purple-700 bg-purple-50 dark:bg-purple-950/60 dark:text-purple-300 px-1.5 py-0.5 rounded-full shrink-0 truncate max-w-[45%]" title="{{ $maxSubject }}">
                                    {{ $maxSubject }}
                                </span>
                            </div>
                            <div class="mt-0.5 min-w-0 max-w-full overflow-hidden">
                                <span class="auto-fit-text text-xl sm:text-2xl font-bold text-gray-900 dark:text-white tracking-tight block" data-auto-fit data-min-font-size="12" data-max-font-size="24">
                                    {{ $formatIndian($maxVal) }}
                                </span>
                            </div>
                            <div class="text-[11px] text-gray-400 dark:text-gray-500 mt-0.5 truncate">
                                highest quote value
                            </div>
                        </div>
                    </div>

                    <!-- Metric Card 4: Quotes Expiring Soon -->
                    <div class="flex items-start gap-3 rounded-2xl border border-gray-200/90 bg-white p-3.5 sm:p-4 shadow-xs dark:border-gray-800 dark:bg-gray-900 min-w-0 overflow-hidden">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-rose-100 text-rose-600 dark:bg-rose-950/60 dark:text-rose-400 shadow-2xs mt-0.5">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="12 6 12 12 16 14"></polyline>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0 overflow-hidden">
                            <div class="flex items-center justify-between gap-1 min-w-0 max-w-full">
                                <span class="text-[11px] font-semibold text-gray-500 dark:text-gray-400 truncate min-w-0">Quotes Expiring</span>
                                <span class="text-[9px] font-bold text-rose-700 bg-rose-50 dark:bg-rose-950/60 dark:text-rose-300 px-1.5 py-0.5 rounded-full shrink-0">
                                    Attention
                                </span>
                            </div>
                            <div class="mt-0.5 min-w-0 max-w-full overflow-hidden">
                                <span class="auto-fit-text text-xl sm:text-2xl font-bold text-gray-900 dark:text-white tracking-tight block" data-auto-fit data-min-font-size="12" data-max-font-size="24">
                                    {{ $expiringCount }}
                                </span>
                            </div>
                            <div class="text-[11px] text-gray-400 dark:text-gray-500 mt-0.5 truncate">
                                within 7 days
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Search & Filters Toolbar -->
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-3.5 rounded-2xl border border-gray-200 bg-white p-3.5 shadow-xs dark:border-gray-800 dark:bg-gray-900">
                    <!-- Left: Search input -->
                    <div class="relative flex-1">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                        </div>
                        <input
                            type="text"
                            v-model="searchQuery"
                            placeholder="Search quotes or customers..."
                            class="w-full rounded-xl border border-gray-200 bg-transparent pl-9 pr-9 py-2 text-xs font-medium text-gray-800 placeholder-gray-400 transition-all focus:border-blue-500 focus:outline-hidden focus:ring-1 focus:ring-blue-500 dark:border-gray-800 dark:text-gray-200 dark:placeholder-gray-500"
                        />
                        <button
                            v-if="searchQuery"
                            @click="searchQuery = ''"
                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200"
                        >
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>

                    <!-- Right: Dropdown Filters -->
                    <div class="flex items-center gap-3 self-end md:self-auto">
                        <!-- Status Dropdown -->
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-gray-400 font-medium">Status</span>
                            <div class="relative">
                                <select
                                    v-model="statusFilter"
                                    class="appearance-none rounded-xl border border-gray-200 bg-white pl-3 pr-8 py-2 text-xs font-medium text-gray-700 cursor-pointer focus:border-blue-500 focus:outline-hidden dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 min-w-[100px]"
                                >
                                    <option value="all">All</option>
                                    <option value="Active">Active</option>
                                    <option value="Expired">Expired</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2.5 text-gray-400">
                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="6 9 12 15 18 9"></polyline>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Date Range Dropdown -->
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-gray-400 font-medium hidden sm:inline">Date Range</span>
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-2.5 text-gray-400">
                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                    </svg>
                                </div>
                                <select
                                    v-model="dateFilter"
                                    class="appearance-none rounded-xl border border-gray-200 bg-white pl-8 pr-8 py-2 text-xs font-medium text-gray-700 cursor-pointer focus:border-blue-500 focus:outline-hidden dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 min-w-[130px]"
                                >
                                    <option value="last_30_days">Last 30 days</option>
                                    <option value="this_month">This Month</option>
                                    <option value="all_time">All Time</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2.5 text-gray-400">
                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="6 9 12 15 18 9"></polyline>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quotes List Container -->
                <div class="rounded-2xl border border-gray-200 bg-white p-4 sm:p-5 shadow-xs dark:border-gray-800 dark:bg-gray-900 flex flex-col gap-3">
                    <!-- Top Counter -->
                    <div class="text-xs font-medium text-gray-500 dark:text-gray-400">
                        @{{ filteredQuotes.length }} quote@{{ filteredQuotes.length === 1 ? '' : 's' }} found
                    </div>

                    <!-- DESKTOP TABLE VIEW -->
                    <div class="quotes-desktop-table w-full">
                        <table class="w-full text-left border-collapse table-auto">
                            <thead>
                                <tr class="text-[11px] font-medium text-gray-400 border-b border-gray-100 dark:border-gray-800">
                                    <th class="pb-3 pr-2 font-normal">Quote / Customer</th>
                                    <th class="pb-3 px-2 font-normal">Sales Person</th>
                                    <th class="pb-3 px-2 font-normal">Amount</th>
                                    <th class="pb-3 px-2 font-normal">Tax</th>
                                    <th class="pb-3 px-2 font-normal">Total</th>
                                    <th class="pb-3 px-2 font-normal">Status</th>
                                    <th class="pb-3 px-2 font-normal">Expiry Date</th>
                                    <th class="pb-3 pl-2 font-normal text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-800/80">
                                <tr
                                    v-for="quote in filteredQuotes"
                                    :key="quote.id"
                                    class="group hover:bg-slate-50/70 dark:hover:bg-gray-800/40 transition-colors"
                                >
                                    <!-- Column 1: Quote / Customer -->
                                    <td class="py-3.5 pr-2 align-middle">
                                        <div class="flex items-start gap-2.5">
                                            <!-- Squircle Icon matching type -->
                                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-blue-50 text-blue-600 dark:bg-blue-950/60 dark:text-blue-400 mt-0.5">
                                                <!-- Building / Commercial -->
                                                <template v-if="quote.icon_type === 'building'">
                                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                                        <rect x="4" y="2" width="16" height="20" rx="2"></rect>
                                                        <path d="M9 22v-4h6v4M8 6h.01M16 6h.01M8 10h.01M16 10h.01M8 14h.01M16 14h.01"></path>
                                                    </svg>
                                                </template>
                                                <!-- House / Villa / Cost Sheet -->
                                                <template v-else-if="quote.icon_type === 'house'">
                                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                                    </svg>
                                                </template>
                                                <!-- Cloud / Architecture -->
                                                <template v-else-if="quote.icon_type === 'cloud'">
                                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                                        <path d="M18 10h-1.26A8 8 0 1 0 9 20h9a5 5 0 0 0 0-10z"></path>
                                                    </svg>
                                                </template>
                                                <!-- Office / Enterprise CRM -->
                                                <template v-else-if="quote.icon_type === 'office'">
                                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                                        <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                                                        <path d="M7 7h3M7 11h3M7 15h3M14 7h3M14 11h3M14 15h3"></path>
                                                    </svg>
                                                </template>
                                                <!-- Document / Lead -->
                                                <template v-else>
                                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                                        <polyline points="14 2 14 8 20 8"></polyline>
                                                    </svg>
                                                </template>
                                            </div>

                                            <div class="min-w-0 max-w-[210px]">
                                                <a
                                                    :href="quote.edit_url"
                                                    class="text-xs font-bold text-gray-900 hover:text-blue-600 transition dark:text-white dark:hover:text-blue-400 block leading-snug truncate"
                                                    :title="quote.title"
                                                >
                                                    @{{ quote.title }}
                                                </a>
                                                <div
                                                    v-if="quote.subtitle"
                                                    class="text-[11px] text-gray-500 dark:text-gray-400 truncate mt-0.5"
                                                    :title="quote.subtitle"
                                                >
                                                    @{{ quote.subtitle }}
                                                </div>
                                                <div class="flex items-center gap-1.5 mt-1 flex-wrap">
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-semibold bg-blue-50 text-blue-600 border border-blue-100 dark:bg-blue-950/60 dark:text-blue-300 dark:border-blue-900">
                                                        @{{ quote.quote_number }}
                                                    </span>
                                                    <span class="text-[9px] text-gray-300 dark:text-gray-700">•</span>
                                                    <span class="text-[9px] text-gray-400 dark:text-gray-500">
                                                        Created: @{{ quote.created_at_formatted }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Column 2: Sales Person -->
                                    <td class="py-3.5 px-2 align-middle whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            <div
                                                class="w-6 h-6 rounded-full flex items-center justify-center font-bold text-[9px] shrink-0"
                                                :class="quote.sales_person.bg"
                                            >
                                                @{{ quote.sales_person.initials }}
                                            </div>
                                            <div class="min-w-0 max-w-[110px]">
                                                <div class="text-xs font-semibold text-gray-800 dark:text-gray-200 truncate" :title="quote.sales_person.name">
                                                    @{{ quote.sales_person.name }}
                                                </div>
                                                <div class="text-[10px] text-gray-400">
                                                    @{{ quote.sales_person.role }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Column 3: Amount -->
                                    <td class="py-3.5 px-2 align-middle whitespace-nowrap">
                                        <div class="text-xs font-bold text-gray-900 dark:text-white tabular-nums tracking-tight">
                                            @{{ quote.amount_formatted }}
                                        </div>
                                    </td>

                                    <!-- Column 4: Tax -->
                                    <td class="py-3.5 px-2 align-middle whitespace-nowrap">
                                        <div class="text-xs font-semibold text-gray-700 dark:text-gray-300 tabular-nums tracking-tight">
                                            @{{ quote.tax_formatted }}
                                        </div>
                                        <div class="text-[9px] text-gray-400 font-medium">
                                            GST / Tax
                                        </div>
                                    </td>

                                    <!-- Column 5: Total -->
                                    <td class="py-3.5 px-2 align-middle whitespace-nowrap">
                                        <div class="inline-flex items-center px-2 py-0.5 rounded-lg bg-blue-50 text-blue-700 font-bold text-xs tracking-tight border border-blue-100 dark:bg-blue-950/60 dark:text-blue-300 dark:border-blue-900 tabular-nums shadow-2xs">
                                            @{{ quote.total_formatted }}
                                        </div>
                                    </td>

                                    <!-- Column 6: Status -->
                                    <td class="py-3.5 px-2 align-middle whitespace-nowrap">
                                        <div v-if="quote.status === 'Active'" class="inline-flex items-center gap-1.5 text-xs font-medium text-emerald-600 dark:text-emerald-400">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            <span>Active</span>
                                        </div>
                                        <div v-else class="flex flex-col items-start">
                                            <span class="inline-flex items-center gap-1.5 text-xs font-medium text-rose-600 dark:text-rose-400">
                                                <span class="w-1.5 h-1.5 rounded-full border border-rose-500"></span>
                                                <span>Expired</span>
                                            </span>
                                            <span v-if="quote.expired_at_formatted" class="text-[10px] font-semibold text-rose-500 dark:text-rose-400 pl-3">
                                                @{{ quote.expired_at_formatted }}
                                            </span>
                                        </div>
                                    </td>

                                    <!-- Column 7: Expiry Date -->
                                    <td class="py-3.5 px-2 align-middle whitespace-nowrap">
                                        <div class="flex items-center gap-1.5 text-xs text-gray-700 dark:text-gray-300 font-medium">
                                            <svg class="h-3.5 w-3.5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                                <line x1="3" y1="10" x2="21" y2="10"></line>
                                            </svg>
                                            <span>@{{ quote.created_at_formatted }}</span>
                                        </div>
                                        <div class="text-[10px] text-gray-400 pl-5">
                                            @{{ (quote.created_at_time || '').split(' ').slice(-2).join(' ') }}
                                        </div>
                                        <div v-if="quote.expiry_subtitle" class="flex items-center gap-1 text-[10px] text-rose-500 font-medium mt-0.5 pl-5">
                                            <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <polyline points="12 6 12 12 16 14"></polyline>
                                            </svg>
                                            <span>@{{ quote.expiry_subtitle }}</span>
                                        </div>
                                    </td>

                                    <!-- Column 8: Actions -->
                                    <td class="py-3.5 pl-2 align-middle text-right whitespace-nowrap">
                                        <div class="flex items-center justify-end gap-1">
                                            <!-- View / Print Button -->
                                            <a
                                                :href="quote.print_url"
                                                target="_blank"
                                                class="flex h-6.5 w-6.5 items-center justify-center rounded-full bg-blue-50 text-blue-600 hover:bg-blue-100 transition dark:bg-blue-950/60 dark:text-blue-400 dark:hover:bg-blue-900"
                                                title="View / Print"
                                            >
                                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                            </a>

                                            <!-- Edit Button -->
                                            <a
                                                :href="quote.edit_url"
                                                class="flex h-6.5 w-6.5 items-center justify-center rounded-full bg-blue-50 text-blue-600 hover:bg-blue-100 transition dark:bg-blue-950/60 dark:text-blue-400 dark:hover:bg-blue-900"
                                                title="Edit"
                                            >
                                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                </svg>
                                            </a>

                                            <!-- Delete Button -->
                                            <button
                                                type="button"
                                                @click="deleteQuote(quote)"
                                                class="flex h-6.5 w-6.5 items-center justify-center rounded-full bg-rose-50 text-rose-600 hover:bg-rose-100 transition dark:bg-rose-950/60 dark:text-rose-400 dark:hover:bg-rose-900"
                                                title="Delete"
                                            >
                                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Empty Search State -->
                                <tr v-if="filteredQuotes.length === 0">
                                    <td colspan="8" class="py-12 text-center text-gray-400">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="h-10 w-10 text-gray-300 dark:text-gray-600 mb-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                                <polyline points="14 2 14 8 20 8"></polyline>
                                            </svg>
                                            <p class="text-sm font-medium text-gray-600 dark:text-gray-300">No quotes found matching your filters</p>
                                            <p class="text-xs text-gray-400 mt-0.5">Try adjusting your search query or status filter</p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- MOBILE CARDS VIEW (Responsive, zero overflow, zero component overlap) -->
                    <div class="quotes-mobile-cards flex-col gap-3.5">
                        <div
                            v-for="quote in filteredQuotes"
                            :key="quote.id"
                            class="rounded-xl border border-gray-200 bg-white p-4 shadow-2xs dark:border-gray-800 dark:bg-gray-900 flex flex-col gap-3"
                        >
                            <!-- Card Header: Icon + Title & Subtitle + Total Pill -->
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-start gap-3 min-w-0">
                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-blue-50 text-blue-600 dark:bg-blue-950/60 dark:text-blue-400 mt-0.5">
                                        <template v-if="quote.icon_type === 'building'">
                                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                                <rect x="4" y="2" width="16" height="20" rx="2"></rect>
                                                <path d="M9 22v-4h6v4M8 6h.01M16 6h.01M8 10h.01M16 10h.01M8 14h.01M16 14h.01"></path>
                                            </svg>
                                        </template>
                                        <template v-else-if="quote.icon_type === 'house'">
                                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                                <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                            </svg>
                                        </template>
                                        <template v-else-if="quote.icon_type === 'cloud'">
                                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                                <path d="M18 10h-1.26A8 8 0 1 0 9 20h9a5 5 0 0 0 0-10z"></path>
                                            </svg>
                                        </template>
                                        <template v-else-if="quote.icon_type === 'office'">
                                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                                <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                                                <path d="M7 7h3M7 11h3M7 15h3M14 7h3M14 11h3M14 15h3"></path>
                                            </svg>
                                        </template>
                                        <template v-else>
                                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                                <polyline points="14 2 14 8 20 8"></polyline>
                                            </svg>
                                        </template>
                                    </div>
                                    <div class="min-w-0">
                                        <a :href="quote.edit_url" class="text-sm font-bold text-gray-900 hover:text-blue-600 dark:text-white block leading-snug">
                                            @{{ quote.title }}
                                        </a>
                                        <div v-if="quote.subtitle" class="text-xs text-gray-500 dark:text-gray-400 line-clamp-2 mt-0.5">
                                            @{{ quote.subtitle }}
                                        </div>
                                    </div>
                                </div>

                                <div class="inline-flex items-center px-2.5 py-1 rounded-xl bg-blue-50 text-blue-700 font-bold text-xs tracking-tight border border-blue-100 shrink-0">
                                    @{{ quote.total_formatted }}
                                </div>
                            </div>

                            <!-- Meta tag & created date -->
                            <div class="flex items-center gap-2 pt-1 border-t border-gray-100 dark:border-gray-800 text-xs">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-blue-50 text-blue-600 border border-blue-100">
                                    @{{ quote.quote_number }}
                                </span>
                                <span class="text-gray-400 text-[11px]">Created: @{{ quote.created_at_formatted }}</span>
                            </div>

                            <!-- 2-Column Details Grid -->
                            <div class="grid grid-cols-2 gap-2 text-xs py-2 bg-gray-50/70 dark:bg-gray-800/40 rounded-xl px-3 border border-gray-100 dark:border-gray-800">
                                <div>
                                    <span class="text-gray-400 text-[10px] uppercase font-semibold">Sales Person</span>
                                    <div class="flex items-center gap-1.5 mt-0.5">
                                        <div class="w-5 h-5 rounded-full flex items-center justify-center font-bold text-[9px] shrink-0" :class="quote.sales_person.bg">
                                            @{{ quote.sales_person.initials }}
                                        </div>
                                        <span class="font-semibold text-gray-800 dark:text-gray-200 truncate">@{{ quote.sales_person.name }}</span>
                                    </div>
                                </div>
                                <div>
                                    <span class="text-gray-400 text-[10px] uppercase font-semibold">Status</span>
                                    <div class="mt-0.5">
                                        <span v-if="quote.status === 'Active'" class="inline-flex items-center gap-1 text-emerald-600 font-bold">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active
                                        </span>
                                        <span v-else class="inline-flex items-center gap-1 text-rose-600 font-bold">
                                            <span class="w-1.5 h-1.5 rounded-full border border-rose-500"></span> Expired
                                        </span>
                                    </div>
                                </div>
                                <div>
                                    <span class="text-gray-400 text-[10px] uppercase font-semibold">Amount</span>
                                    <div class="font-bold text-gray-800 dark:text-gray-200">@{{ quote.amount_formatted }}</div>
                                </div>
                                <div>
                                    <span class="text-gray-400 text-[10px] uppercase font-semibold">Tax (GST)</span>
                                    <div class="font-semibold text-gray-700 dark:text-gray-300">@{{ quote.tax_formatted }}</div>
                                </div>
                            </div>

                            <!-- Expiry date & Action buttons -->
                            <div class="flex items-center justify-between gap-2 pt-1">
                                <div class="text-[11px] text-gray-500 flex items-center gap-1">
                                    <svg class="h-3.5 w-3.5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                    </svg>
                                    <span>@{{ quote.created_at_time || quote.expired_at_time }}</span>
                                </div>

                                <div class="flex items-center gap-1.5">
                                    <a :href="quote.print_url" target="_blank" class="p-1.5 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100" title="Print">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                            <circle cx="12" cy="12" r="3"></circle>
                                        </svg>
                                    </a>
                                    <a :href="quote.edit_url" class="p-1.5 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100" title="Edit">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                    </a>
                                    <button @click="deleteQuote(quote)" class="p-1.5 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100" title="Delete">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Mobile Empty Search State -->
                        <div v-if="filteredQuotes.length === 0" class="py-8 text-center text-gray-400">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-300">No quotes found</p>
                        </div>
                    </div>
                </div>
            </div>
        </script>

        <script type="module">
            app.component('v-quotes', {
                template: '#v-quotes-template',

                props: {
                    initialQuotes: {
                        type: Array,
                        default: () => []
                    },
                    initialStats: {
                        type: Object,
                        default: () => ({})
                    }
                },

                data() {
                    return {
                        quotes: this.initialQuotes || [],
                        stats: this.initialStats || {},
                        searchQuery: '',
                        statusFilter: 'all',
                        dateFilter: 'last_30_days',
                    };
                },

                computed: {
                    filteredQuotes() {
                        return this.quotes.filter(q => {
                            // Status filter
                            if (this.statusFilter !== 'all') {
                                if (q.status !== this.statusFilter) return false;
                            }

                            // Search filter (searches title, subtitle, quote number, customer/sales person name)
                            if (this.searchQuery && this.searchQuery.trim() !== '') {
                                const term = this.searchQuery.toLowerCase().trim();
                                const title = (q.title || '').toLowerCase();
                                const subtitle = (q.subtitle || '').toLowerCase();
                                const quoteNum = (q.quote_number || '').toLowerCase();
                                const person = (q.sales_person?.name || '').toLowerCase();
                                return title.includes(term) || subtitle.includes(term) || quoteNum.includes(term) || person.includes(term);
                            }

                            return true;
                        });
                    }
                },

                methods: {
                    deleteQuote(quote) {
                        if (!confirm(`Are you sure you want to delete quote #${quote.id} (${quote.title})?`)) return;

                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = quote.delete_url;

                        const csrf = document.createElement('input');
                        csrf.type = 'hidden';
                        csrf.name = '_token';
                        csrf.value = '{{ csrf_token() }}';
                        form.appendChild(csrf);

                        const method = document.createElement('input');
                        method.type = 'hidden';
                        method.name = '_method';
                        method.value = 'DELETE';
                        form.appendChild(method);

                        document.body.appendChild(form);
                        form.submit();
                    }
                }
            });
        </script>
    @endPushOnce

    @pushOnce('styles')
        <style>
            .quotes-desktop-table {
                display: block;
            }
            .quotes-mobile-cards {
                display: none;
            }
            @media (max-width: 1023px) {
                .quotes-desktop-table {
                    display: none !important;
                }
                .quotes-mobile-cards {
                    display: flex !important;
                }
            }
        </style>
    @endPushOnce
</x-admin::layouts>
