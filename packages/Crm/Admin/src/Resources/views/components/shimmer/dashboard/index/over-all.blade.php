<div class="grid grid-cols-2 gap-3.5 sm:grid-cols-3 lg:grid-cols-6">
    @for ($i = 1; $i <= 6; $i++)
        <div class="flex flex-col justify-between rounded-2xl border border-slate-200/80 bg-white p-3.5 shadow-2xs dark:border-gray-800 dark:bg-gray-900">
            <div class="shimmer h-3.5 w-20 rounded-md"></div>
            <div class="mt-3 flex items-center gap-2">
                <div class="shimmer h-6 w-6 rounded-md"></div>
                <div class="shimmer h-6 w-8 rounded-md"></div>
            </div>
        </div>
    @endfor
</div>