<x-admin::layouts>
    <x-slot:title>
        Do Not Contact (DNC) List
    </x-slot>

    <div class="flex flex-col gap-6">
        <!-- Header -->
        <div class="scroll-reactive-sticky sticky top-[60px] z-[1000] flex flex-wrap items-center justify-between gap-4 rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm shadow-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
            <div class="flex flex-col gap-1">
                <x-admin::breadcrumbs name="whatsapp.dnc" />
                <div class="text-xl font-bold dark:text-white">
                    Do Not Contact (DNC) List
                </div>
            </div>

            <div class="flex items-center gap-2.5">
                <a
                    href="{{ route('admin.whatsapp.index') }}"
                    class="secondary-button"
                >
                    &larr; Back to Broadcasts
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Left: Add Number to DNC Form -->
            <div class="rounded-lg border border-gray-300 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-2">
                    Add Number to Opt-Out List
                </h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">
                    Any number added here will be automatically skipped by all future WhatsApp broadcasts.
                </p>

                <form action="{{ route('admin.whatsapp.dnc.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="phone" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Phone Number <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            name="phone"
                            id="phone"
                            required
                            placeholder="e.g. 9876543210 or +919876543210"
                            class="w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-brandColor focus:outline-none dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                        >
                    </div>

                    <div class="mb-4">
                        <label for="reason" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Opt-Out Reason (Optional)
                        </label>
                        <input
                            type="text"
                            name="reason"
                            id="reason"
                            placeholder="e.g. Requested STOP via WhatsApp"
                            class="w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-brandColor focus:outline-none dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                        >
                    </div>

                    <button
                        type="submit"
                        class="primary-button w-full justify-center"
                    >
                        Add to DNC List
                    </button>
                </form>
            </div>

            <!-- Right: DNC Table -->
            <div class="rounded-lg border border-gray-300 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900 lg:col-span-2">
                <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4">
                    Opted-Out Numbers ({{ $dncList->total() }})
                </h3>

                @if ($dncList->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
                            <thead class="border-b border-gray-200 bg-gray-50 text-xs uppercase text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">
                                <tr>
                                    <th class="px-4 py-2.5">Phone (E.164)</th>
                                    <th class="px-4 py-2.5">Reason</th>
                                    <th class="px-4 py-2.5">Date Added</th>
                                    <th class="px-4 py-2.5 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dncList as $dnc)
                                    <tr class="border-b border-gray-100 hover:bg-gray-50 dark:border-gray-800 dark:hover:bg-gray-800/50">
                                        <td class="px-4 py-2.5 font-mono text-xs font-semibold text-gray-900 dark:text-white">
                                            +{{ $dnc->phone_e164 }}
                                        </td>
                                        <td class="px-4 py-2.5 text-xs text-gray-500 dark:text-gray-400">
                                            {{ $dnc->reason ?: 'Manual Opt-Out' }}
                                        </td>
                                        <td class="px-4 py-2.5 text-xs text-gray-500 dark:text-gray-400">
                                            {{ $dnc->created_at->format('M d, Y') }}
                                        </td>
                                        <td class="px-4 py-2.5 text-right">
                                            <form
                                                action="{{ route('admin.whatsapp.dnc.delete', $dnc->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Remove this number from DNC list?');"
                                                class="inline"
                                            >
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    type="submit"
                                                    class="text-xs font-semibold text-red-600 hover:underline dark:text-red-400 dark:hover:text-red-300"
                                                >
                                                    Remove
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $dncList->links() }}
                    </div>
                @else
                    <div class="text-center py-8 text-xs text-gray-400 dark:text-gray-500">
                        No numbers on the Do Not Contact list yet.
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-admin::layouts>
