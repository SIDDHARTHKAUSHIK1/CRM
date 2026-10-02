<x-admin::layouts.anonymous>
    <!-- Page Title -->
    <x-slot:title>
        Real Estate CRM — Set New Password
    </x-slot>

    @push('styles')
    <style>
        .font-serif {
            font-family: 'Cormorant Garamond', Georgia, serif;
        }
        .font-mono {
            font-family: 'JetBrains Mono', monospace;
        }
        .font-sans {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        }
        .login-glass-container {
            background: rgba(255, 255, 255, 0.76);
            backdrop-filter: blur(28px) saturate(180%);
            -webkit-backdrop-filter: blur(28px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.9);
            box-shadow: 0 30px 60px -15px rgba(0, 0, 0, 0.25), 0 0 0 1px rgba(255, 255, 255, 0.7) inset;
        }
        .glass-field-wrap {
            background: rgba(255, 255, 255, 0.55);
            border: 1px solid #D1D5DB;
            border-radius: 0.75rem;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .glass-field-wrap:focus-within {
            background: #FFFFFF;
            border-color: #047857;
            box-shadow: 0 0 0 3px rgba(4, 120, 87, 0.15);
        }
        .glass-field-wrap input {
            width: 100% !important;
            background: transparent !important;
            border: none !important;
            outline: none !important;
            box-shadow: none !important;
            padding: 0.75rem 1rem 0.75rem 2.75rem !important;
            font-size: 0.875rem !important;
            color: #0F172A !important;
        }
        .btn-obsidian {
            background: #0E1615 !important;
            color: #FFFFFF !important;
            border-radius: 0.75rem !important;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1) !important;
        }
        .btn-obsidian:hover {
            background: #192624 !important;
            transform: translateY(-1px);
            box-shadow: 0 10px 25px -5px rgba(14, 22, 21, 0.4);
        }
    </style>
    @endpush

    <!-- FULLSCREEN PANORAMIC LUXURY REAL ESTATE EXPERIENCE -->
    <div class="relative min-h-screen w-full flex flex-col justify-between font-sans selection:bg-[#047857] selection:text-white p-4 sm:p-6 lg:p-8">
        
        <!-- LAYER 0: Clean Luxury Villa & Sunset Sky Background Image -->
        <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
            <img
                src="{{ asset('images/login-luxury-bg.jpg') }}"
                alt="Luxury Real Estate Panorama"
                class="w-full h-full object-cover object-center"
            />
            <div class="absolute inset-0 bg-black/5"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-black/35 via-transparent to-black/10"></div>
        </div>

        <!-- LAYER 1: TOP NAVIGATION HEADER -->
        <header class="relative z-20 w-full flex items-center justify-between">
            <a href="/" class="flex items-center gap-3 group focus:outline-none">
                <div class="w-10 h-10 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                    <svg class="w-9 h-9" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M18 4L4 15V32H32V15L18 4Z" stroke="#047857" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                        <rect x="15" y="17.5" width="2.6" height="2.6" fill="#047857" rx="0.3" />
                        <rect x="18.8" y="17.5" width="2.6" height="2.6" fill="#047857" rx="0.3" />
                        <rect x="15" y="21.3" width="2.6" height="2.6" fill="#047857" rx="0.3" />
                        <rect x="18.8" y="21.3" width="2.6" height="2.6" fill="#047857" rx="0.3" />
                    </svg>
                </div>
                <div class="flex flex-col">
                    <div class="flex items-center gap-1.5 leading-none">
                        <span class="text-lg font-black tracking-tight text-[#0F241D]">RE</span>
                        <span class="text-lg font-black tracking-tight text-[#047857]">CRM</span>
                    </div>
                    <span class="text-[9px] font-mono tracking-[0.22em] text-[#1E293B] font-bold uppercase mt-1">
                        REAL ESTATE SALES &amp; CRM PLATFORM
                    </span>
                </div>
            </a>

            <div class="hidden md:flex items-center gap-2.5 font-mono text-[11px] tracking-[0.22em] text-[#1E293B] font-bold uppercase">
                <span>MORE LEADS</span>
                <span class="text-[#047857] font-bold">/</span>
                <span>BETTER CLIENTS</span>
                <span class="text-[#047857] font-bold">/</span>
                <span>BIGGER DEALS</span>
            </div>
        </header>

        <!-- LAYER 2: CENTER FROSTED SET PASSWORD CARD -->
        <main class="relative z-20 w-full my-auto py-6 flex items-center justify-center">
            <div class="w-full max-w-[460px] login-glass-container rounded-[2rem] p-7 sm:p-9 shadow-2xl relative animate-in fade-in zoom-in-95 duration-300">
                
                <!-- Inside Card Brand Logo -->
                <div class="flex flex-col items-center text-center mb-5">
                    <div class="w-10 h-10 flex items-center justify-center mb-1">
                        <svg class="w-9 h-9" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M18 4L4 15V32H32V15L18 4Z" stroke="#047857" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                            <rect x="15" y="17.5" width="2.6" height="2.6" fill="#047857" rx="0.3" />
                            <rect x="18.8" y="17.5" width="2.6" height="2.6" fill="#047857" rx="0.3" />
                            <rect x="15" y="21.3" width="2.6" height="2.6" fill="#047857" rx="0.3" />
                            <rect x="18.8" y="21.3" width="2.6" height="2.6" fill="#047857" rx="0.3" />
                        </svg>
                    </div>
                    <div class="flex items-center gap-1.5 leading-none">
                        <span class="text-base font-black tracking-tight text-[#0F241D]">RE</span>
                        <span class="text-base font-black tracking-tight text-[#047857]">CRM</span>
                    </div>
                    <span class="text-[8.5px] font-mono tracking-[0.2em] text-[#475569] font-bold uppercase mt-1">
                        REAL ESTATE SALES &amp; CRM PLATFORM
                    </span>
                </div>

                <!-- Headline -->
                <div class="text-center mb-6 space-y-1">
                    <h1 class="font-serif text-3xl text-[#0F172A] font-normal leading-tight">
                        Set a new
                    </h1>
                    <div class="font-serif text-3xl italic text-[#047857] font-medium leading-tight">
                        Password
                    </div>
                    <p class="text-xs text-[#64748B] pt-1">
                        Create a strong password for your Real Estate CRM account.
                    </p>
                </div>

                {!! view_render_event('admin.sessions.reset-password.form_controls.before') !!}

                <!-- Reset Password Form -->
                <x-admin::form :action="route('admin.reset_password.store')">
                    <x-admin::form.control-group.control
                        type="hidden"
                        name="token"
                        :value="$token"
                    />

                    <div class="space-y-4 text-left">
                        <!-- Email Field -->
                        <div>
                            <label class="block text-xs font-semibold text-[#1E293B] mb-1.5" for="email">
                                Work Email Address *
                            </label>

                            <div class="relative glass-field-wrap">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#64748B]">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <x-admin::form.control-group.control
                                    type="email"
                                    id="email"
                                    name="email"
                                    rules="required|email"
                                    :label="trans('admin::app.users.reset-password.email')"
                                    placeholder="Enter your work email"
                                />
                            </div>

                            <x-admin::form.control-group.error control-name="email" class="text-xs text-red-600 mt-1 font-medium" />
                        </div>

                        <!-- New Password Field -->
                        <div>
                            <label class="block text-xs font-semibold text-[#1E293B] mb-1.5" for="password">
                                New Password *
                            </label>

                            <div class="relative glass-field-wrap">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#64748B]">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                                <x-admin::form.control-group.control
                                    type="password"
                                    id="password"
                                    name="password"
                                    rules="required|min:6"
                                    :label="trans('admin::app.users.reset-password.password')"
                                    placeholder="Enter new password"
                                    ref="password"
                                />
                            </div>

                            <x-admin::form.control-group.error control-name="password" class="text-xs text-red-600 mt-1 font-medium" />
                        </div>

                        <!-- Confirm Password Field -->
                        <div>
                            <label class="block text-xs font-semibold text-[#1E293B] mb-1.5" for="password_confirmation">
                                Confirm New Password *
                            </label>

                            <div class="relative glass-field-wrap">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#64748B]">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                </div>
                                <x-admin::form.control-group.control
                                    type="password"
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    rules="confirmed:@password"
                                    :label="trans('admin::app.users.reset-password.confirm-password')"
                                    placeholder="Confirm new password"
                                    ref="password"
                                />
                            </div>

                            <x-admin::form.control-group.error control-name="password_confirmation" class="text-xs text-red-600 mt-1 font-medium" />
                        </div>

                        <!-- Submit Button -->
                        <button
                            type="submit"
                            class="btn-obsidian w-full py-3.5 px-6 font-bold text-sm tracking-wide shadow-md hover:shadow-xl flex items-center justify-center gap-2 cursor-pointer mt-3"
                        >
                            <span>Update Password &amp; Sign In</span>
                            <span class="text-base font-bold">&rarr;</span>
                        </button>
                    </div>
                </x-admin::form>

                {!! view_render_event('admin.sessions.reset-password.form_controls.after') !!}

                <!-- Footer Link -->
                <div class="mt-6 pt-5 border-t border-[#D5D3CB]/80 text-center">
                    <a
                        href="{{ route('admin.session.create') }}"
                        class="text-xs font-semibold text-[#047857] hover:text-[#065F46] transition-colors"
                    >
                        &larr; Return to Sign In
                    </a>
                </div>
            </div>
        </main>

        <!-- LAYER 3: BOTTOM SECURITY BADGE -->
        <footer class="relative z-20 w-full flex items-end justify-between pointer-events-none">
            <div class="flex items-center gap-2 text-[10px] font-mono tracking-widest text-white/95 drop-shadow uppercase font-bold pl-1 pointer-events-auto">
                <div class="w-4 h-4 rounded-full bg-emerald-500/30 flex items-center justify-center border border-emerald-400/60 shadow-sm">
                    <svg class="w-2.5 h-2.5 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <span>256-BIT ENCRYPTION · ROLE-BASED ACCESS SECURED</span>
            </div>
        </footer>
    </div>
</x-admin::layouts.anonymous>
