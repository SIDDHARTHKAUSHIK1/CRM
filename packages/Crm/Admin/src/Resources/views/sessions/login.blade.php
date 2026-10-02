<x-admin::layouts.anonymous>
    <!-- Page Title -->
    <x-slot:title>
        Real Estate CRM — Login
    </x-slot>

    @push('styles')
    <style>
        html, body {
            height: 100vh !important;
            max-height: 100vh !important;
            overflow: hidden !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        #app {
            height: 100vh !important;
            max-height: 100vh !important;
            overflow: hidden !important;
        }
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
            width: 100% !important;
            max-width: 380px !important;
            margin: 0 auto !important;
            background: rgba(255, 255, 255, 0.22) !important;
            backdrop-filter: blur(20px) saturate(180%) !important;
            -webkit-backdrop-filter: blur(20px) saturate(180%) !important;
            border: 1px solid rgba(255, 255, 255, 0.45) !important;
            box-shadow: 0 25px 50px -10px rgba(0, 0, 0, 0.28), 0 0 0 1px rgba(255, 255, 255, 0.35) inset !important;
            border-radius: 22px !important;
            padding: 1.35rem 1.65rem !important;
        }
        .glass-field-wrap {
            background: rgba(255, 255, 255, 0.32) !important;
            border: 1px solid rgba(255, 255, 255, 0.50) !important;
            border-radius: 0.65rem !important;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1) !important;
        }
        .glass-field-wrap:focus-within {
            background: rgba(255, 255, 255, 0.85) !important;
            border-color: #047857 !important;
            box-shadow: 0 0 0 3px rgba(4, 120, 87, 0.2) !important;
        }
        .glass-field-wrap input {
            width: 100% !important;
            background: transparent !important;
            border: none !important;
            outline: none !important;
            box-shadow: none !important;
            padding: 0.58rem 0.8rem 0.58rem 2.35rem !important;
            font-size: 0.825rem !important;
            color: #0F172A !important;
        }
        .glass-field-wrap input::placeholder {
            color: #475569 !important;
        }
        .btn-obsidian {
            background: #0E1615 !important;
            color: #FFFFFF !important;
            border-radius: 0.65rem !important;
            padding: 0.62rem 1.25rem !important;
            font-size: 0.825rem !important;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1) !important;
        }
        .btn-obsidian:hover {
            background: #192624 !important;
            transform: translateY(-1px);
            box-shadow: 0 10px 25px -5px rgba(14, 22, 21, 0.4);
        }
        .btn-sso {
            background: rgba(255, 255, 255, 0.35) !important;
            border: 1px solid rgba(255, 255, 255, 0.50) !important;
            border-radius: 0.65rem !important;
            padding: 0.48rem 0.75rem !important;
            font-size: 0.76rem !important;
            transition: all 0.2s ease !important;
        }
        .btn-sso:hover {
            background: rgba(255, 255, 255, 0.75) !important;
            border-color: rgba(255, 255, 255, 0.9) !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08) !important;
        }
        .landing-back-pill {
            background: rgba(255, 255, 255, 0.25) !important;
            backdrop-filter: blur(18px) saturate(160%) !important;
            -webkit-backdrop-filter: blur(18px) saturate(160%) !important;
            border: 1px solid rgba(255, 255, 255, 0.45) !important;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1) !important;
        }
        .landing-back-pill:hover {
            background: rgba(255, 255, 255, 0.60) !important;
            border-color: rgba(255, 255, 255, 0.85) !important;
            transform: translateY(-1px);
            box-shadow: 0 8px 20px -4px rgba(0, 0, 0, 0.15) !important;
        }
    </style>
    @endpush

    <!-- FULLSCREEN FIXED LUXURY REAL ESTATE EXPERIENCE (ZERO SCROLL) -->
    <div class="fixed inset-0 h-screen max-h-screen w-screen overflow-hidden flex flex-col justify-between font-sans selection:bg-[#047857] selection:text-white p-3 sm:p-5 box-border">
        
        <!-- LAYER 0: Clean Luxury Villa & Sunset Sky Background Image -->
        <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
            <img
                src="{{ asset('images/login-luxury-bg.jpg') }}"
                alt="Luxury Real Estate Panorama"
                class="w-full h-full object-cover object-center"
            />
            <div class="absolute inset-0 bg-black/5"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-black/25 via-transparent to-black/10"></div>
        </div>

        <!-- LAYER 1: TOP HEADER WITH HIGHLY TRANSLUCENT LANDING PAGE BUTTON -->
        <header class="relative z-20 w-full flex items-center justify-between shrink-0">
            <!-- Back to Landing Page Translucent Glass Button -->
            <a
                href="/"
                class="landing-back-pill inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-bold text-[#0F241D] hover:text-[#047857] group"
            >
                <div class="w-4 h-4 rounded-full bg-emerald-500/20 flex items-center justify-center text-[#047857] group-hover:-translate-x-0.5 transition-transform shadow-xs">
                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </div>
                <span class="tracking-wide">Back to Landing Page</span>
            </a>

            <div></div>
        </header>

        <!-- LAYER 2: CENTER HIGHLY TRANSLUCENT FROSTED LOGIN CARD -->
        <main class="relative z-20 w-full my-auto flex items-center justify-center shrink-0">
            
            <div class="login-glass-container relative animate-in fade-in zoom-in-95 duration-300" style="max-width: 380px; width: 100%;">
                
                <!-- Inside Card Brand Logo -->
                <div class="flex flex-col items-center text-center mb-2.5">
                    <div class="w-7 h-7 flex items-center justify-center mb-0.5">
                        <svg class="w-6 h-6" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M18 4L4 15V32H32V15L18 4Z" stroke="#047857" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                            <rect x="15" y="17.5" width="2.6" height="2.6" fill="#047857" rx="0.3" />
                            <rect x="18.8" y="17.5" width="2.6" height="2.6" fill="#047857" rx="0.3" />
                            <rect x="15" y="21.3" width="2.6" height="2.6" fill="#047857" rx="0.3" />
                            <rect x="18.8" y="21.3" width="2.6" height="2.6" fill="#047857" rx="0.3" />
                        </svg>
                    </div>
                    <div class="flex items-center gap-1 leading-none">
                        <span class="text-sm font-black tracking-tight text-[#0F241D]">RE</span>
                        <span class="text-sm font-black tracking-tight text-[#047857]">CRM</span>
                    </div>
                    <div class="flex items-center gap-2 w-full mt-1">
                        <div class="flex-1 h-[1px] bg-slate-400/30"></div>
                        <span class="text-[7px] font-mono tracking-[0.18em] text-[#334155] font-bold uppercase whitespace-nowrap">
                            REAL ESTATE SALES &amp; CRM PLATFORM
                        </span>
                        <div class="flex-1 h-[1px] bg-slate-400/30"></div>
                    </div>
                </div>

                <!-- Welcome Headline -->
                <div class="text-center mb-3 space-y-0.5">
                    <h1 class="font-serif text-[22px] sm:text-[24px] text-[#0F172A] font-normal leading-tight tracking-tight">
                        Welcome back to
                    </h1>
                    <div class="font-serif text-[22px] sm:text-[24px] italic text-[#047857] font-medium leading-tight">
                        Real Estate CRM
                    </div>
                </div>

                {!! view_render_event('admin.sessions.login.form_controls.before') !!}

                <!-- Login Form -->
                <x-admin::form :action="route('admin.session.store')">
                    <div class="space-y-2.5 text-left">
                        
                        <!-- Work Email -->
                        <div>
                            <label class="block text-[10.5px] font-semibold text-[#1E293B] mb-0.5" for="email">
                                Work Email *
                            </label>

                            <div class="relative glass-field-wrap">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-[#475569]">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <x-admin::form.control-group.control
                                    type="email"
                                    id="email"
                                    name="email"
                                    rules="required|email"
                                    :label="trans('admin::app.users.login.email')"
                                    placeholder="Enter your work email"
                                />
                            </div>

                            <x-admin::form.control-group.error control-name="email" class="text-xs text-red-600 mt-0.5 font-medium" />
                        </div>

                        <!-- Password -->
                        <div>
                            <label class="block text-[10.5px] font-semibold text-[#1E293B] mb-0.5" for="password">
                                Password *
                            </label>

                            <div class="relative glass-field-wrap">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-[#475569]">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                                
                                <x-admin::form.control-group.control
                                    type="password"
                                    class="!pr-8"
                                    id="password"
                                    name="password"
                                    rules="required|min:6"
                                    :label="trans('admin::app.users.login.password')"
                                    placeholder="Enter your password"
                                />

                                <button
                                    type="button"
                                    onclick="switchVisibility()"
                                    id="visibilityIcon"
                                    class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-[#475569] hover:text-[#047857] transition-colors cursor-pointer"
                                    title="Toggle password visibility"
                                    aria-label="Toggle password visibility"
                                >
                                    <svg id="eyeCloseIcon" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                    </svg>
                                    <svg id="eyeOpenIcon" class="w-3.5 h-3.5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>

                            <x-admin::form.control-group.error control-name="password" class="text-xs text-red-600 mt-0.5 font-medium" />
                        </div>

                        <!-- Remember Me & Forgot Password Row -->
                        <div class="flex items-center justify-between pt-0.5">
                            <label class="flex items-center gap-1.5 cursor-pointer select-none text-[10.5px] font-semibold text-[#1E293B] hover:text-black">
                                <input
                                    type="checkbox"
                                    name="remember"
                                    value="1"
                                    checked
                                    class="w-3.5 h-3.5 rounded border-gray-300 text-[#047857] focus:ring-[#047857] cursor-pointer accent-[#047857]"
                                />
                                <span>Remember me</span>
                            </label>

                            <a
                                class="text-[10.5px] font-semibold text-[#047857] hover:text-[#065F46] transition-colors"
                                href="{{ route('admin.forgot_password.create') }}"
                            >
                                Forgot password?
                            </a>
                        </div>

                        <!-- Sign In Button -->
                        <button
                            type="submit"
                            class="btn-obsidian w-full font-bold shadow-md hover:shadow-xl flex items-center justify-center gap-2 cursor-pointer mt-1"
                            aria-label="{{ trans('admin::app.users.login.submit-btn') }}"
                        >
                            <span>Sign In</span>
                            <span class="text-sm font-bold">&rarr;</span>
                        </button>
                    </div>
                </x-admin::form>

                {!! view_render_event('admin.sessions.login.form_controls.after') !!}

                <!-- OR CONTINUE WITH DIVIDER (Clean flex line without strikethrough) -->
                <div class="flex items-center gap-2.5 my-2.5">
                    <div class="flex-1 h-[1px] bg-slate-400/30"></div>
                    <span class="font-mono text-[7.5px] uppercase tracking-[0.2em] text-[#334155] font-bold shrink-0">
                        OR CONTINUE WITH
                    </span>
                    <div class="flex-1 h-[1px] bg-slate-400/30"></div>
                </div>

                <!-- SSO BUTTONS -->
                <div class="grid grid-cols-2 gap-2">
                    <button
                        type="button"
                        onclick="alert('Google Workspace SSO is configured via Admin Settings -> Integrations.')"
                        class="btn-sso flex items-center justify-center gap-1.5 font-semibold text-[#1E293B] cursor-pointer"
                    >
                        <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                        </svg>
                        <span class="text-[9.5px] sm:text-[10px] truncate">Google Workspace</span>
                    </button>

                    <button
                        type="button"
                        onclick="alert('Microsoft 365 SSO is configured via Admin Settings -> Integrations.')"
                        class="btn-sso flex items-center justify-center gap-1.5 font-semibold text-[#1E293B] cursor-pointer"
                    >
                        <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 23 23">
                            <path fill="#f35325" d="M1 1h10v10H1z"/>
                            <path fill="#81bc06" d="M12 1h10v10H12z"/>
                            <path fill="#05a6f0" d="M1 12h10v10H1z"/>
                            <path fill="#ffba08" d="M12 12h10v10H12z"/>
                        </svg>
                        <span class="text-[9.5px] sm:text-[10px] truncate">Microsoft 365</span>
                    </button>
                </div>

                <!-- FOOTER LINK -->
                <div class="mt-2.5 text-center">
                    <p class="text-[10px] text-[#334155] font-medium">
                        Need access? Contact your Sales Admin or
                        <a href="/#contact" class="font-semibold text-[#047857] hover:text-[#065F46] underline">
                            Request Demo
                        </a>
                    </p>
                </div>
            </div>
        </main>

        <!-- Bottom spacer -->
        <div class="shrink-0 h-2"></div>
    </div>

    @push('scripts')
        <script>
            function switchVisibility() {
                var passwordField = document.getElementById("password");
                var eyeOpen = document.getElementById("eyeOpenIcon");
                var eyeClose = document.getElementById("eyeCloseIcon");

                if (!passwordField) return;

                if (passwordField.type === "password") {
                    passwordField.type = "text";
                    if (eyeOpen) eyeOpen.classList.remove("hidden");
                    if (eyeClose) eyeClose.classList.add("hidden");
                } else {
                    passwordField.type = "password";
                    if (eyeOpen) eyeOpen.classList.add("hidden");
                    if (eyeClose) eyeClose.classList.remove("hidden");
                }
            }
        </script>
    @endpush
</x-admin::layouts.anonymous>
