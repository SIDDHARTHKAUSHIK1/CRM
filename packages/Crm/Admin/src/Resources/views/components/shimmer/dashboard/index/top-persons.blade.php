<div class="flex h-full flex-col justify-between rounded-2xl border border-slate-200/80 bg-white p-5 shadow-2xs dark:border-gray-800 dark:bg-gray-900">
    <div class="flex items-start gap-3">
        <div class="shimmer h-10 w-10 rounded-xl"></div>
        <div class="space-y-2">
            <div class="shimmer h-4 w-32 rounded-md"></div>
            <div class="shimmer h-3 w-40 rounded-md"></div>
        </div>
    </div>

    <div class="mt-5 space-y-3.5 flex-1">
        @for ($i = 0; $i < 3; $i++)
            <div class="flex items-center gap-3 pb-3">
                <div class="shimmer h-9 w-9 rounded-full shrink-0"></div>
                <div class="space-y-1.5 flex-1">
                    <div class="shimmer h-3.5 w-36 rounded-md"></div>
                    <div class="shimmer h-2.5 w-48 rounded-md"></div>
                </div>
            </div>
        @endfor
    </div>
</div>