<x-admin::layouts>
    <!-- Page title -->
    <x-slot:title>
        @lang('admin::app.account.edit.title')
    </x-slot>

    {!! view_render_event('admin.user.account.form.before', ['user' => $user]) !!}

    <!-- Input Form -->
    <x-admin::form
        :action="route('admin.user.account.update')"
        enctype="multipart/form-data"
        method="PUT"
    >
        <div class="scroll-reactive-sticky sticky top-[60px] z-[1000] flex items-center justify-between rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm shadow-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
            <div class="flex flex-col gap-2">
                {!! view_render_event('admin.user.account.breadcrumbs.before', ['user' => $user]) !!}

                <!-- Breadcrumbs -->
                <x-admin::breadcrumbs 
                    name="dashboard.account.edit" 
                    :entity="$user"
                />

                {!! view_render_event('admin.user.account.breadcrumbs.after', ['user' => $user]) !!}

                <div class="flex items-center gap-3">
                    <div class="text-xl font-bold dark:text-white">
                        {!! view_render_event('admin.user.account.title.before', ['user' => $user]) !!}

                        @lang('admin::app.account.edit.title')

                        {!! view_render_event('admin.user.account.title.after', ['user' => $user]) !!}
                    </div>

                    @php
                        $isAdmin = $user?->role && $user->role->permission_type === 'all';
                    @endphp
                    @if ($isAdmin)
                        <span class="inline-flex items-center gap-1 rounded-full bg-red-600 px-2.5 py-0.5 text-xs font-extrabold uppercase tracking-wider text-white shadow-xs dark:bg-red-500 dark:text-white" title="@lang('admin::app.admin-panel.roles.admin')">
                            <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                            <span>@lang('admin::app.admin-panel.roles.admin')</span>
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 rounded-full bg-blue-600 px-2.5 py-0.5 text-xs font-extrabold uppercase tracking-wider text-white shadow-xs dark:bg-blue-500 dark:text-white" title="@lang('admin::app.admin-panel.roles.employee')">
                            <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                            <span>@lang('admin::app.admin-panel.roles.employee')</span>
                        </span>
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-x-2.5">
                <!-- Create button for Roles -->
                <div class="flex items-center gap-x-2.5">
                    {!! view_render_event('admin.user.account.save_btn.before', ['user' => $user]) !!}

                    <button
                        type="submit"
                        class="primary-button"
                    >
                        @lang('admin::app.account.edit.save-btn')
                    </button>

                    {!! view_render_event('admin.user.account.save_btn.after', ['user' => $user]) !!}
                </div>
            </div>
        </div>
        
        <!-- Full Panel -->
        <div class="mt-3.5 flex gap-2.5 max-xl:flex-wrap">
            {!! view_render_event('admin.user.account.left.before', ['user' => $user]) !!}

            <!-- Left sub Component -->
            <div class="flex flex-1 flex-col gap-2">
                <!-- General -->
                <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                    <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                        @lang('admin::app.account.edit.general')
                    </p>

                    <!-- Image -->
                    <x-admin::form.control-group>
                        <x-admin::media.images
                            name="image"
                            :uploaded-images="$user->image ? [['id' => 'image', 'url' => $user->image_url]] : []"
                        />
                    </x-admin::form.control-group>

                    <p class="mb-4 text-xs text-gray-600 dark:text-gray-300">
                        @lang('admin::app.account.edit.upload-image-info')
                    </p>

                    <!-- Name -->
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label class="required">
                            @lang('admin::app.account.edit.name')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="text"
                            name="name"
                            rules="required"
                            :value="old('name') ?: $user->name"
                            :label="trans('admin::app.account.edit.name')"
                            :placeholder="trans('admin::app.account.edit.name')"
                        />

                        <x-admin::form.control-group.error control-name="name" />
                    </x-admin::form.control-group>

                    <!-- Email -->
                    <x-admin::form.control-group class="!mb-0">
                        <x-admin::form.control-group.label class="required">
                            @lang('admin::app.account.edit.email')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="email"
                            name="email"
                            id="email"
                            rules="required"
                            :value="old('email') ?: $user->email"
                            :label="trans('admin::app.account.edit.email')"
                        />

                        <x-admin::form.control-group.error control-name="email" />
                    </x-admin::form.control-group>
                </div>
            </div>

            {!! view_render_event('admin.user.account.left.after', ['user' => $user]) !!}

            {!! view_render_event('admin.user.account.right.before', ['user' => $user]) !!}

            <!-- Right sub-component -->
            <div class="flex w-[360px] max-w-full flex-col gap-2 max-md:w-full">
                <x-admin::accordion>
                    <x-slot:header>
                        <p class="p-2.5 text-base font-semibold text-gray-800 dark:text-white">
                            @lang('admin::app.account.edit.change-password')
                        </p>
                    </x-slot>

                    <!-- Change Account Password -->
                    <x-slot:content>
                        {!! view_render_event('admin.user.current_password.before', ['user' => $user]) !!}

                        <!-- Current Password -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.account.edit.current-password')
                            </x-admin::form.control-group.label>

                            <div class="relative flex items-center">
                                <x-admin::form.control-group.control
                                    type="password"
                                    id="current_password"
                                    name="current_password"
                                    rules="required|min:6"
                                    :label="trans('admin::app.account.edit.current-password')"
                                    :placeholder="trans('admin::app.account.edit.current-password')"
                                    class="ltr:pr-10 rtl:pl-10"
                                />

                                <button
                                    type="button"
                                    onclick="toggleAccountPasswordVisibility('current_password', this)"
                                    class="absolute right-2.5 p-1 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 focus:outline-none"
                                    title="Show Password"
                                >
                                    <svg class="eye-open hidden w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <svg class="eye-closed w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a10.025 10.025 0 011.13-.163c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21M3 3l18 18"/></svg>
                                </button>
                            </div>

                            <x-admin::form.control-group.error control-name="current_password" />
                        </x-admin::form.control-group>

                        {!! view_render_event('admin.user.current_password.after', ['user' => $user]) !!}

                        {!! view_render_event('admin.user.password.before', ['user' => $user]) !!}

                        <!-- Password -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label>
                                @lang('admin::app.account.edit.password')
                            </x-admin::form.control-group.label>

                            <div class="relative flex items-center">
                                <x-admin::form.control-group.control
                                    type="password"
                                    id="password"
                                    name="password"
                                    rules="min:6"
                                    :placeholder="trans('admin::app.account.edit.password')"
                                    ref="password"
                                    class="ltr:pr-10 rtl:pl-10"
                                />

                                <button
                                    type="button"
                                    onclick="toggleAccountPasswordVisibility('password', this)"
                                    class="absolute right-2.5 p-1 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 focus:outline-none"
                                    title="Show Password"
                                >
                                    <svg class="eye-open hidden w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <svg class="eye-closed w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a10.025 10.025 0 011.13-.163c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21M3 3l18 18"/></svg>
                                </button>
                            </div>

                            <x-admin::form.control-group.error control-name="password" />
                        </x-admin::form.control-group>

                        {!! view_render_event('admin.user.password.after', ['user' => $user]) !!}

                        {!! view_render_event('admin.user.confirm-password.before', ['user' => $user]) !!}

                        <!-- Confirm Password -->
                        <x-admin::form.control-group class="!mb-0">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.account.edit.confirm-password')
                            </x-admin::form.control-group.label>

                            <div class="relative flex items-center">
                                <x-admin::form.control-group.control
                                    type="password"
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    rules="confirmed:@password"
                                    :label="trans('admin::app.account.edit.confirm-password')"
                                    :placeholder="trans('admin::app.account.edit.confirm-password')"
                                    class="ltr:pr-10 rtl:pl-10"
                                />

                                <button
                                    type="button"
                                    onclick="toggleAccountPasswordVisibility('password_confirmation', this)"
                                    class="absolute right-2.5 p-1 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 focus:outline-none"
                                    title="Show Password"
                                >
                                    <svg class="eye-open hidden w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <svg class="eye-closed w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a10.025 10.025 0 011.13-.163c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21M3 3l18 18"/></svg>
                                </button>
                            </div>

                            <x-admin::form.control-group.error control-name="password_confirmation" />
                        </x-admin::form.control-group>

                        {!! view_render_event('admin.user.confirm-password.after', ['user' => $user]) !!}
                    </x-slot>
                </x-admin::accordion>
            </div>

            {!! view_render_event('admin.user.account.right.after', ['user' => $user]) !!}
        </div>
    </x-admin::form>

    {!! view_render_event('admin.user.account.form.after') !!}

    @push('scripts')
        <script>
            function toggleAccountPasswordVisibility(fieldId, btn) {
                var field = document.getElementById(fieldId) || document.querySelector('input[name="' + fieldId + '"]');
                if (!field) return;

                var isPassword = field.type === 'password';
                field.type = isPassword ? 'text' : 'password';

                var eyeOpen = btn.querySelector('.eye-open');
                var eyeClosed = btn.querySelector('.eye-closed');
                if (eyeOpen && eyeClosed) {
                    if (isPassword) {
                        eyeOpen.classList.remove('hidden');
                        eyeClosed.classList.add('hidden');
                        btn.setAttribute('title', 'Hide Password');
                    } else {
                        eyeOpen.classList.add('hidden');
                        eyeClosed.classList.remove('hidden');
                        btn.setAttribute('title', 'Show Password');
                    }
                }
            }
        </script>
    @endpush
</x-admin::layouts>
