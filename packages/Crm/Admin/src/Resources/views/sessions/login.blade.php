<x-admin::layouts.anonymous>
    <!-- Page Title -->
    <x-slot:title>
        Real Estate CRM — Login
    </x-slot>

    @push('styles')
    <style>
        html, body {
            min-height: 100vh !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        #app {
            min-height: 100vh !important;
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
        
        /* MAXIMUM TRANSLUCENT LIQUID GLASS CARD */
        .login-glass-card {
            width: 100% !important;
            max-width: 410px !important;
            margin: 0 auto !important;
            background: rgba(255, 255, 255, 0.06) !important;
            backdrop-filter: blur(8px) saturate(220%) !important;
            -webkit-backdrop-filter: blur(8px) saturate(220%) !important;
            border: 1px solid rgba(255, 255, 255, 0.38) !important;
            box-shadow: 0 30px 60px -15px rgba(0, 0, 0, 0.35), inset 0 1px 0 rgba(255, 255, 255, 0.5) !important;
            border-radius: 26px !important;
            padding: 1.45rem 1.6rem !important;
        }

        /* LIQUID TRANSLUCENT GLASS INPUTS */
        .glass-input-wrap {
            background: rgba(255, 255, 255, 0.10) !important;
            backdrop-filter: blur(8px) !important;
            -webkit-backdrop-filter: blur(8px) !important;
            border: 1px solid rgba(255, 255, 255, 0.40) !important;
            border-radius: 12px !important;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04), inset 0 1px 0 rgba(255, 255, 255, 0.3) !important;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1) !important;
        }
        .glass-input-wrap:focus-within {
            background: rgba(255, 255, 255, 0.20) !important;
            border-color: rgba(255, 255, 255, 0.85) !important;
            box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.25) !important;
        }
        .glass-input-wrap input {
            width: 100% !important;
            background: transparent !important;
            background-color: transparent !important;
            border: none !important;
            outline: none !important;
            box-shadow: none !important;
            padding: 0.65rem 0.85rem 0.65rem 2.45rem !important;
            font-size: 0.88rem !important;
            color: #0F172A !important;
            font-weight: 700 !important;
        }
        .glass-input-wrap input::placeholder {
            color: #334155 !important;
            font-weight: 600 !important;
        }

        /* PREVENT CHROME/EDGE SOLID OPAQUE AUTOFILL */
        .glass-input-wrap input:-webkit-autofill,
        .glass-input-wrap input:-webkit-autofill:hover, 
        .glass-input-wrap input:-webkit-autofill:focus, 
        .glass-input-wrap input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0 1000px rgba(255, 255, 255, 0.12) inset !important;
            -webkit-text-fill-color: #0F172A !important;
            caret-color: #0F172A !important;
            transition: background-color 5000s ease-in-out 0s !important;
            border-radius: 12px !important;
            font-weight: 700 !important;
        }

        /* HIGH-CONTRAST READABLE LABELS */
        .glass-label {
            color: #0F172A !important;
            font-weight: 800 !important;
            font-size: 11.5px !important;
            letter-spacing: 0.02em !important;
            text-shadow: 0 1px 3px rgba(255, 255, 255, 0.8), 0 0 1px rgba(255, 255, 255, 0.9);
        }

        /* LIQUID GLASS DEMO BUTTONS */
        .demo-glass-pill {
            background: rgba(255, 255, 255, 0.10);
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            border: 1px solid rgba(255, 255, 255, 0.38);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04), inset 0 1px 0 rgba(255, 255, 255, 0.4);
            transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .demo-glass-pill:hover {
            background: rgba(255, 255, 255, 0.25);
            border-color: rgba(255, 255, 255, 0.85);
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
        }

        /* SLEEK TRANSLUCENT CHARCOAL BUTTON */
        .btn-charcoal-pill {
            background: rgba(15, 23, 42, 0.78) !important;
            backdrop-filter: blur(10px) !important;
            -webkit-backdrop-filter: blur(10px) !important;
            border: 1px solid rgba(255, 255, 255, 0.30) !important;
            border-radius: 12px !important;
            padding: 0.72rem 1.25rem !important;
            font-size: 0.875rem !important;
            color: #FFFFFF !important;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1) !important;
        }
        .btn-charcoal-pill:hover {
            background: rgba(15, 23, 42, 0.92) !important;
            transform: translateY(-1px);
            box-shadow: 0 10px 25px -4px rgba(15, 23, 42, 0.5) !important;
        }

        /* SSO TRANSLUCENT PILLS */
        .btn-glass-sso {
            background: rgba(255, 255, 255, 0.08) !important;
            backdrop-filter: blur(4px) !important;
            -webkit-backdrop-filter: blur(4px) !important;
            border: 1px solid rgba(255, 255, 255, 0.30) !important;
            border-radius: 10px !important;
            transition: all 0.2s ease !important;
        }
        .btn-glass-sso:hover {
            background: rgba(255, 255, 255, 0.25) !important;
            border-color: rgba(255, 255, 255, 0.75) !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06) !important;
        }

        .landing-back-pill {
            background: rgba(255, 255, 255, 0.15) !important;
            backdrop-filter: blur(12px) saturate(180%) !important;
            -webkit-backdrop-filter: blur(12px) saturate(180%) !important;
            border: 1px solid rgba(255, 255, 255, 0.35) !important;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06) !important;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1) !important;
        }
        .landing-back-pill:hover {
            background: rgba(255, 255, 255, 0.35) !important;
            border-color: rgba(255, 255, 255, 0.8) !important;
            transform: translateY(-1px);
            box-shadow: 0 8px 20px -4px rgba(0, 0, 0, 0.12) !important;
        }
    </style>
    @endpush

    <!-- FULLSCREEN FIXED LUXURY REAL ESTATE EXPERIENCE -->
    <div class="fixed inset-0 h-screen max-h-screen w-screen overflow-y-auto flex flex-col justify-between font-sans selection:bg-[#1E293B] selection:text-white p-3 sm:p-5 box-border">
        
        <!-- LAYER 0: Crystal Clear Luxury Villa Background (No Blur, Sharp & 100% Clear) -->
        <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
            <img
                src="{{ asset('images/login-luxury-bg.jpg') }}"
                alt="Luxury Real Estate Panorama"
                class="w-full h-full object-cover object-center"
            />
            <div class="absolute inset-0 bg-black/10"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-black/25 via-transparent to-black/15"></div>
        </div>

        <!-- LAYER 1: TOP HEADER WITH TRANSLUCENT BACK BUTTON -->
        <header class="relative z-20 w-full flex items-center justify-between shrink-0">
            <a
                href="/"
                class="landing-back-pill inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-bold text-[#0F172A] hover:text-black group shadow-sm"
            >
                <div class="w-4 h-4 rounded-full bg-slate-900/10 flex items-center justify-center text-[#0F172A] group-hover:-translate-x-0.5 transition-transform shadow-2xs">
                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </div>
                <span class="tracking-wide">Back to Landing Page</span>
            </a>

            <div></div>
        </header>

        <!-- LAYER 2: CENTER AUTHENTICATION SECTION (PERFECT VERTICAL & HORIZONTAL MIDDLE ALIGNMENT) -->
        <main class="relative z-20 w-full my-auto flex items-center justify-center shrink-0 py-4">
            
            <!-- FROSTED TRANSLUCENT GLASS CARD -->
            <div class="login-glass-card relative animate-in fade-in zoom-in-95 duration-300">
                
                <!-- INSIDE CARD BRANDING (COMPACT & SLEEK) -->
                <div class="flex flex-col items-center text-center mb-3">
                    <div class="w-9 h-9 flex items-center justify-center mb-1 text-[#0F172A]">
                        <svg class="w-8 h-8" viewBox="0 0 40 40" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 5L6 16.5V34H34V16.5L20 5Z" />
                            <rect x="16" y="19" width="3.2" height="3.2" fill="currentColor" stroke="none" rx="0.5" />
                            <rect x="20.8" y="19" width="3.2" height="3.2" fill="currentColor" stroke="none" rx="0.5" />
                            <rect x="16" y="23.8" width="3.2" height="3.2" fill="currentColor" stroke="none" rx="0.5" />
                            <rect x="20.8" y="23.8" width="3.2" height="3.2" fill="currentColor" stroke="none" rx="0.5" />
                        </svg>
                    </div>

                    <div class="flex items-center justify-center gap-1 leading-none mb-0.5">
                        <span class="text-[20px] font-black tracking-tight text-[#0F172A]">RE</span>
                        <span class="text-[20px] font-normal tracking-normal text-[#1E293B]">CRM</span>
                    </div>

                    <div class="text-[8px] font-medium tracking-[0.22em] text-[#475569] uppercase mb-1.5">
                        REAL ESTATE SALES &amp; CRM PLATFORM
                    </div>

                    <div class="w-8 h-[2px] bg-[#1E293B] mx-auto rounded-full"></div>
                </div>

                <!-- QUICK DEMO HEADER -->
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-1.5 text-[#0F172A]">
                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                            <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
                        </svg>
                        <span class="text-[10.5px] font-bold uppercase tracking-[0.16em]">QUICK DEMO LOGIN</span>
                    </div>

                    <span class="inline-flex items-center gap-1 text-[9.5px] font-semibold text-[#1E293B] bg-white/45 border border-white/70 px-2.5 py-0.5 rounded-full shadow-2xs backdrop-blur-sm">
                        1-Click Sign In <span class="text-[10px]">&rarr;</span>
                    </span>
                </div>

                <!-- QUICK DEMO PILL BUTTONS -->
                <div class="grid grid-cols-2 gap-2.5 mb-3.5">
                    <!-- Admin Demo -->
                    <button
                        type="button"
                        onclick="quickLogin('admin@example.com', 'admin123', 'Administrator', this)"
                        class="demo-glass-pill group flex items-center justify-between px-3 py-2.5 rounded-xl cursor-pointer"
                        title="Click to 1-click login as Administrator"
                    >
                        <div class="flex items-center gap-1.5 truncate">
                            <span class="text-sm">👑</span>
                            <span class="text-[11.5px] font-bold text-[#0F172A]">Admin Demo</span>
                        </div>
                        <span class="text-xs font-bold text-[#475569] group-hover:translate-x-0.5 group-hover:text-black transition-all">&rarr;</span>
                    </button>

                    <!-- Agent Demo -->
                    <button
                        type="button"
                        onclick="quickLogin('ram@gmail.com', 'admin123', 'Sales Agent', this)"
                        class="demo-glass-pill group flex items-center justify-between px-3 py-2.5 rounded-xl cursor-pointer"
                        title="Click to 1-click login as Sales Agent"
                    >
                        <div class="flex items-center gap-1.5 truncate">
                            <span class="text-sm">💼</span>
                            <span class="text-[11.5px] font-bold text-[#0F172A]">Agent Demo</span>
                        </div>
                        <span class="text-xs font-bold text-[#475569] group-hover:translate-x-0.5 group-hover:text-black transition-all">&rarr;</span>
                    </button>
                </div>

                <!-- Quick Demo Status Banner -->
                <div id="demoFeedback" class="hidden mb-2 text-center text-[10.5px] font-semibold text-[#0F172A]"></div>

                {!! view_render_event('admin.sessions.login.form_controls.before') !!}

                <!-- Login Form -->
                <x-admin::form :action="route('admin.session.store')" id="admin-login-form">
                    @csrf
                    <div class="space-y-3 text-left">
                        
                        <!-- Work Email -->
                        <div>
                            <label class="glass-label block mb-1" for="email">
                                Work Email *
                            </label>

                            <div class="relative glass-input-wrap">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#0F172A]">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <x-admin::form.control-group.control
                                    type="email"
                                    id="email"
                                    name="email"
                                    rules="required|email"
                                    :label="trans('admin::app.users.login.email')"
                                    placeholder="admin@example.com"
                                    autocomplete="off"
                                />
                            </div>

                            <x-admin::form.control-group.error control-name="email" class="text-xs text-red-600 mt-1 font-medium" />
                        </div>

                        <!-- Password -->
                        <div>
                            <label class="glass-label block mb-1" for="password">
                                Password *
                            </label>

                            <div class="relative glass-input-wrap">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#0F172A]">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                                
                                <x-admin::form.control-group.control
                                    type="password"
                                    class="!pr-9"
                                    id="password"
                                    name="password"
                                    rules="required|min:6"
                                    :label="trans('admin::app.users.login.password')"
                                    placeholder="••••••••"
                                    autocomplete="current-password"
                                />

                                <button
                                    type="button"
                                    onclick="switchVisibility()"
                                    id="visibilityIcon"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-[#0F172A] hover:text-black transition-colors cursor-pointer"
                                    title="Toggle password visibility"
                                    aria-label="Toggle password visibility"
                                >
                                    <svg id="eyeCloseIcon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                    </svg>
                                    <svg id="eyeOpenIcon" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>

                            <x-admin::form.control-group.error control-name="password" class="text-xs text-red-600 mt-1 font-medium" />
                        </div>

                        <!-- Remember Me & Forgot Password Row -->
                        <div class="flex items-center justify-between pt-0.5">
                            <label class="flex items-center gap-2 cursor-pointer select-none text-[11px] font-semibold text-[#1E293B] hover:text-black">
                                <input
                                    type="checkbox"
                                    name="remember"
                                    value="1"
                                    checked
                                    class="w-4 h-4 rounded border-gray-400 bg-white/60 text-[#1E293B] focus:ring-slate-700 cursor-pointer accent-[#1E293B]"
                                />
                                <span>Remember me</span>
                            </label>

                            <a
                                class="text-[11px] font-semibold text-[#475569] hover:text-[#0F172A] transition-colors"
                                href="{{ route('admin.forgot_password.create') }}"
                            >
                                Forgot password?
                            </a>
                        </div>

                        <!-- Sign In Button -->
                        <button
                            type="submit"
                            class="btn-charcoal-pill w-full flex items-center justify-center gap-2 cursor-pointer font-bold shadow-md hover:shadow-xl mt-2"
                            aria-label="{{ trans('admin::app.users.login.submit-btn') }}"
                        >
                            <span>Sign In</span>
                            <span class="text-base font-bold">&rarr;</span>
                        </button>
                    </div>
                </x-admin::form>

                {!! view_render_event('admin.sessions.login.form_controls.after') !!}

                <!-- OR CONTINUE WITH DIVIDER -->
                <div class="flex items-center gap-3 my-3">
                    <div class="flex-1 h-[1px] bg-slate-400/40"></div>
                    <span class="font-mono text-[7.5px] uppercase tracking-[0.22em] text-[#475569] font-bold shrink-0">
                        OR CONTINUE WITH
                    </span>
                    <div class="flex-1 h-[1px] bg-slate-400/40"></div>
                </div>

                <!-- SSO BUTTONS -->
                <div class="grid grid-cols-2 gap-2">
                    <button
                        type="button"
                        onclick="alert('Google Workspace SSO is configured via Admin Settings -> Integrations.')"
                        class="btn-glass-sso flex items-center justify-center gap-1.5 py-1.5 px-2.5 font-semibold text-[#1E293B] cursor-pointer"
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
                        class="btn-glass-sso flex items-center justify-center gap-1.5 py-1.5 px-2.5 font-semibold text-[#1E293B] cursor-pointer"
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
                <div class="mt-3 text-center">
                    <p class="text-[10.5px] text-[#334155] font-medium">
                        Need access? Contact your Sales Admin or
                        <a href="/#contact" class="font-semibold text-[#0F172A] hover:underline underline">
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

            function quickLogin(email, password, roleLabel, btn) {
                var emailInput = document.getElementById("email") || document.querySelector('input[name="email"]');
                var passwordInput = document.getElementById("password") || document.querySelector('input[name="password"]');
                var form = document.querySelector('form');
                var feedback = document.getElementById("demoFeedback");

                if (emailInput && passwordInput) {
                    emailInput.value = email;
                    emailInput.dispatchEvent(new Event('input', { bubbles: true }));
                    emailInput.dispatchEvent(new Event('change', { bubbles: true }));

                    passwordInput.value = password;
                    passwordInput.dispatchEvent(new Event('input', { bubbles: true }));
                    passwordInput.dispatchEvent(new Event('change', { bubbles: true }));

                    if (btn) {
                        btn.classList.add('ring-2', 'ring-[#0F172A]', 'bg-white/90');
                    }

                    if (feedback) {
                        feedback.innerHTML = '<span class="inline-flex items-center gap-1 text-[#0F172A] font-semibold text-[10.5px] animate-pulse">✓ Signing in as ' + roleLabel + '...</span>';
                        feedback.classList.remove('hidden');
                    }

                    setTimeout(function() {
                        if (form) {
                            var submitBtn = form.querySelector('button[type="submit"]');
                            if (submitBtn) {
                                submitBtn.click();
                            } else {
                                form.submit();
                            }
                        }
                    }, 120);
                }
            }
        </script>
    @endpush
</x-admin::layouts.anonymous>
