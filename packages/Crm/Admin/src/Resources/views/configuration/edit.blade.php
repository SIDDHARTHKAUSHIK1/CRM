@php
    $activeConfiguration = system_config()->getActiveConfigurationItem();

    $name = $activeConfiguration->getName();
@endphp

<x-admin::layouts>
    <x-slot:title>
        {{ strip_tags($name) }}
    </x-slot>

    {!! view_render_event('admin.configuration.edit.form_controls.before') !!}

    <!-- Configuration form fields -->
    <x-admin::form
        action=""
        enctype="multipart/form-data"
    >
        <!-- Modern Header Card -->
        <div class="flex flex-wrap items-center justify-between gap-3.5 rounded-2xl border border-slate-200/90 bg-white px-5 py-4 text-sm shadow-xs dark:border-gray-800 dark:bg-gray-900">
            <div class="flex items-center gap-3.5 min-w-0">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-purple-100 text-purple-600 dark:bg-purple-950/70 dark:text-purple-400">
                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M19.14 12.94c.04-.3.06-.61.06-.94 0-.32-.02-.64-.07-.94l2.03-1.58c.18-.14.23-.41.12-.61l-1.92-3.32c-.12-.22-.37-.29-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54c-.04-.24-.24-.41-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.05.3-.09.63-.09.94s.02.64.07.94l-2.03 1.58c-.18.14-.23.41-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58zM12 15.6c-1.98 0-3.6-1.62-3.6-3.6s1.62-3.6 3.6-3.6 3.6 1.62 3.6 3.6-1.62 3.6-3.6 3.6z"/>
                    </svg>
                </div>

                <div class="flex flex-col min-w-0">
                    <span class="text-xs font-semibold text-purple-600 dark:text-purple-400">
                        Settings / Configuration
                    </span>
                    <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-gray-900 dark:text-white truncate">
                        {{ $name }}
                    </h1>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-2.5 max-sm:w-full max-sm:justify-end">
                {!! view_render_event('admin.configuration.edit.back_button.before') !!}

                <!-- Back Button -->
                <a
                    href="{{ route('admin.configuration.index') }}"
                    class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800"
                >
                    @lang('admin::app.configuration.index.back')
                </a>

                {!! view_render_event('admin.configuration.edit.back_button.after') !!}

                {!! view_render_event('admin.configuration.edit.save_button.before') !!}

                <button
                    type="submit"
                    style="background-color: #6366f1 !important; color: #ffffff !important;"
                    class="inline-flex items-center justify-center rounded-xl px-4 py-2 text-xs font-semibold text-white shadow-sm transition hover:opacity-95"
                >
                    @lang('admin::app.configuration.index.save-btn')
                </button>

                {!! view_render_event('admin.configuration.edit.save_button.after') !!}
            </div>
        </div>

        <div class="grid grid-cols-[1fr_2fr] gap-10 max-lg:grid-cols-1 max-lg:gap-4 mt-6">
            @foreach ($activeConfiguration->getChildren() as $child)
                <div class="grid content-start gap-2.5 max-lg:mt-6">
                    <p class="text-base font-bold text-gray-900 dark:text-white">
                        {{ $child->getName() }}
                    </p>

                    <p class="text-xs text-slate-500 leading-relaxed dark:text-gray-400">
                        {!! $child->getInfo() !!}
                    </p>
                </div>

                <div class="rounded-2xl border border-slate-200/90 bg-white p-6 shadow-xs dark:border-gray-800 dark:bg-gray-900">
                    {!! view_render_event('admin.configuration.edit.form_controls.before') !!}

                    @foreach ($child->getFields() as $field)
                        @if (
                            $field->getType() == 'blade'
                            && view()->exists($path = $field->getPath())
                        )
                            {!! view($path, compact('field', 'child'))->render() !!}
                        @else 
                            @include ('admin::configuration.field-type')
                        @endif
                    @endforeach

                    {!! view_render_event('admin.configuration.edit.form_controls.after') !!}
                </div>
            @endforeach
        </div>
    </x-admin::form>

    {!! view_render_event('admin.configuration.edit.form_controls.after') !!}
</x-admin::layouts>
