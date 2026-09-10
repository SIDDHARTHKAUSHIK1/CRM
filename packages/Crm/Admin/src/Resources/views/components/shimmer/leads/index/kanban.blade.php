<!-- Shimmer Pipeline Metrics Banner -->
<div class="rounded-2xl border border-slate-200/90 bg-white p-4 shadow-xs dark:border-gray-800 dark:bg-gray-900">
    <div class="grid grid-cols-2 gap-3 sm:gap-4 lg:grid-cols-4">
        @for ($k = 0; $k < 4; $k++)
            <div class="flex items-center gap-3.5 rounded-xl border border-slate-100 bg-slate-50/50 p-3 dark:border-gray-800 dark:bg-gray-800/40">
                <div class="shimmer h-11 w-11 rounded-xl"></div>
                <div class="space-y-1.5 flex-1">
                    <div class="shimmer h-3 w-20 rounded"></div>
                    <div class="shimmer h-5 w-28 rounded"></div>
                </div>
            </div>
        @endfor
    </div>
</div>

<x-admin::shimmer.leads.index.kanban.toolbar />

<div class="flex gap-4 overflow-x-auto kanban-scroll max-h-[calc(100vh-280px)] pb-3">
    <!-- Stages -->
    @for ($i = 1; $i <= 6; $i++)
        <div class="flex w-80 min-w-[320px] max-w-[320px] shrink-0 flex-col rounded-2xl border border-slate-200/90 bg-slate-50/70 p-3.5 shadow-2xs dark:border-gray-800 dark:bg-gray-900/60">
            <!-- Stage Header -->
            <div class="flex flex-col pb-3 border-b border-slate-200/80 dark:border-gray-800">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="shimmer h-3 w-3 rounded-full"></div>
                        <div class="shimmer h-4 w-24 rounded-md"></div>
                        <div class="shimmer h-4 w-6 rounded-full"></div>
                    </div>
                    <div class="shimmer h-7 w-7 rounded-lg"></div>
                </div>

                <div class="mt-2.5 flex items-center justify-between gap-2">
                    <div class="shimmer h-3.5 w-16 rounded"></div>
                    <div class="shimmer h-3.5 w-20 rounded"></div>
                </div>

                <div class="mt-1.5 h-1.5 w-full overflow-hidden rounded-full bg-slate-200/90 dark:bg-gray-800">
                    <div class="shimmer h-1.5 w-1/3"></div>
                </div>
            </div>

            <!-- Stage Lead Cards -->
            <div class="flex min-h-[160px] h-[calc(100vh-420px)] flex-col gap-3 overflow-y-auto pt-3">
                @for ($j = 1; $j <= 2; $j++)
                    <!-- Card -->
                    <div class="flex flex-col gap-3 rounded-xl border border-slate-200/90 bg-white p-3.5 shadow-2xs dark:border-gray-800 dark:bg-gray-900">
                        <!-- Header -->
                        <div class="flex items-start justify-between">
                            <div class="flex items-center gap-2.5">
                                <div class="shimmer h-9 w-9 rounded-full"></div>
                                <div class="space-y-1">
                                    <div class="shimmer h-4 w-24 rounded"></div>
                                    <div class="shimmer h-3 w-16 rounded"></div>
                                </div>
                            </div>
                            <div class="shimmer h-6 w-16 rounded-lg"></div>
                        </div>

                        <!-- Body -->
                        <div class="shimmer h-9 w-full rounded-lg"></div>

                        <!-- Badges -->
                        <div class="flex gap-1.5">
                            <div class="shimmer h-5 w-16 rounded-md"></div>
                            <div class="shimmer h-5 w-16 rounded-md"></div>
                        </div>

                        <!-- Footer -->
                        <div class="flex items-center justify-between border-t border-slate-100 pt-2.5 dark:border-gray-800">
                            <div class="flex items-center gap-1.5">
                                <div class="shimmer h-5 w-5 rounded-full"></div>
                                <div class="shimmer h-3 w-20 rounded"></div>
                            </div>
                            <div class="shimmer h-3 w-12 rounded"></div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    @endfor
</div>