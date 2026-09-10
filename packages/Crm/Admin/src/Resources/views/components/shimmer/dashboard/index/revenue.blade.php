<div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
    @for ($i = 0; $i < 4; $i++)
        <div class="flex flex-col justify-between rounded-2xl border border-gray-200/80 bg-white p-5 shadow-xs dark:border-gray-800 dark:bg-gray-900">
            <div class="flex items-start justify-between gap-3">
                <div class="space-y-2 flex-1">
                    <div class="shimmer h-3.5 w-24 rounded-md"></div>
                    <div class="shimmer h-8 w-36 rounded-md"></div>
                </div>
                <div class="shimmer h-12 w-12 rounded-2xl"></div>
            </div>
            <div class="mt-4 flex items-center gap-1">
                <div class="shimmer h-5 w-36 rounded-full"></div>
            </div>
        </div>
    @endfor
</div>