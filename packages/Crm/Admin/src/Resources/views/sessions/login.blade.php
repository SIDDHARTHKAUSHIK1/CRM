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
        .font-sans {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        }
        
        /* PREMIUM FROSTED LIQUID GLASS KPI CARD */
        .login-glass-card {
            width: 100% !important;
            max-width: 425px !important;
            margin: 0 auto !important;
            background: rgba(255, 255, 255, 0.42) !important;
            backdrop-filter: blur(28px) saturate(180%) !important;
            -webkit-backdrop-filter: blur(28px) saturate(180%) !important;
            border: 1.5px solid rgba(255, 255, 255, 0.75) !important;
            box-shadow: 0 30px 60px -15px rgba(0, 30, 80, 0.22), inset 0 1.5px 0 rgba(255, 255, 255, 0.9) !important;
            border-radius: 32px !important;
            padding: 1.75rem 1.85rem !important;
        }

        /* LIQUID GLASS INPUT WRAPPER */
        .glass-input-wrap {
            background: rgba(255, 255, 255, 0.65) !important;
            backdrop-filter: blur(8px) !important;
            -webkit-backdrop-filter: blur(8px) !important;
            border: 1.5px solid rgba(255, 255, 255, 0.85) !important;
            border-radius: 14px !important;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.02) !important;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1) !important;
        }
        .glass-input-wrap:focus-within {
            background: rgba(255, 255, 255, 0.85) !important;
            border-color: #0066FF !important;
            box-shadow: 0 0 0 3.5px rgba(0, 102, 255, 0.2), inset 0 1px 0 rgba(255, 255, 255, 0.9) !important;
        }
        .glass-input-wrap input {
            width: 100% !important;
            background: transparent !important;
            background-color: transparent !important;
            border: none !important;
            outline: none !important;
            box-shadow: none !important;
            padding: 0.72rem 0.85rem 0.72rem 2.45rem !important;
            font-size: 0.9rem !important;
            color: #0B1A30 !important;
            font-weight: 700 !important;
        }
        .glass-input-wrap input::placeholder {
            color: #64748B !important;
            font-weight: 600 !important;
        }

        /* AUTOFILL PRESERVATION */
        .glass-input-wrap input:-webkit-autofill,
        .glass-input-wrap input:-webkit-autofill:hover, 
        .glass-input-wrap input:-webkit-autofill:focus, 
        .glass-input-wrap input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0 1000px rgba(255, 255, 255, 0.75) inset !important;
            -webkit-text-fill-color: #0B1A30 !important;
            caret-color: #0B1A30 !important;
            transition: background-color 5000s ease-in-out 0s !important;
            border-radius: 14px !important;
            font-weight: 700 !important;
        }

        /* DEMO PILL BUTTONS */
        .demo-glass-pill {
            background: rgba(255, 255, 255, 0.65) !important;
            backdrop-filter: blur(8px) !important;
            -webkit-backdrop-filter: blur(8px) !important;
            border: 1.5px solid rgba(255, 255, 255, 0.85) !important;
            border-radius: 14px !important;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02), inset 0 1px 0 rgba(255, 255, 255, 0.8) !important;
            transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1) !important;
        }
        .demo-glass-pill:hover {
            background: rgba(255, 255, 255, 0.90) !important;
            border-color: #0066FF !important;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(0, 102, 255, 0.18) !important;
        }

        /* ELECTRIC BLUE PRIMARY SIGN IN BUTTON */
        .btn-electric-blue {
            background: linear-gradient(135deg, #1A82FF 0%, #0066FF 100%) !important;
            border: 1px solid rgba(255, 255, 255, 0.4) !important;
            border-radius: 14px !important;
            padding: 0.82rem 1.25rem !important;
            font-size: 0.95rem !important;
            font-weight: 800 !important;
            color: #FFFFFF !important;
            box-shadow: 0 10px 24px -4px rgba(0, 102, 255, 0.5), inset 0 1px 0 rgba(255, 255, 255, 0.3) !important;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1) !important;
        }
        .btn-electric-blue:hover {
            background: linear-gradient(135deg, #0A72F0 0%, #0052D4 100%) !important;
            transform: translateY(-1px);
            box-shadow: 0 14px 28px -4px rgba(0, 102, 255, 0.65) !important;
        }

        /* CREATE ACCOUNT PILL BUTTON */
        .btn-create-account {
            background: rgba(255, 255, 255, 0.65) !important;
            backdrop-filter: blur(8px) !important;
            -webkit-backdrop-filter: blur(8px) !important;
            border: 1.5px solid rgba(255, 255, 255, 0.85) !important;
            border-radius: 14px !important;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02), inset 0 1px 0 rgba(255, 255, 255, 0.8) !important;
            transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1) !important;
        }
        .btn-create-account:hover {
            background: rgba(255, 255, 255, 0.90) !important;
            border-color: #0066FF !important;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(0, 102, 255, 0.18) !important;
        }

        .landing-back-pill {
            background: rgba(255, 255, 255, 0.45) !important;
            backdrop-filter: blur(14px) saturate(180%) !important;
            -webkit-backdrop-filter: blur(14px) saturate(180%) !important;
            border: 1.5px solid rgba(255, 255, 255, 0.75) !important;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08), inset 0 1px 0 rgba(255, 255, 255, 0.8) !important;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1) !important;
        }
        .landing-back-pill:hover {
            background: rgba(255, 255, 255, 0.7) !important;
            border-color: #0066FF !important;
            transform: translateY(-1px);
            box-shadow: 0 8px 22px -4px rgba(0, 102, 255, 0.2) !important;
        }
    </style>
    @endpush

    <!-- FULLSCREEN FIXED LUXURY REAL ESTATE EXPERIENCE -->
    <div class="fixed inset-0 h-screen max-h-screen w-screen overflow-y-auto flex flex-col justify-between font-sans selection:bg-[#0066FF] selection:text-white p-3 sm:p-5 box-border">
        
        <!-- LAYER 0: Crystal Clear Luxury Villa Background -->
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
                class="landing-back-pill inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-bold text-[#0B1A30] hover:text-black group shadow-sm"
            >
                <div class="w-4 h-4 rounded-full bg-slate-900/10 flex items-center justify-center text-[#0B1A30] group-hover:-translate-x-0.5 transition-transform shadow-2xs">
                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </div>
                <span class="tracking-wide">Back to Landing Page</span>
            </a>

            <div></div>
        </header>

        <!-- LAYER 2: CENTER AUTHENTICATION SECTION -->
        <main class="relative z-20 w-full my-auto flex items-center justify-center shrink-0 py-3 sm:py-4">
            
            <!-- FROSTED TRANSLUCENT GLASS CARD -->
            <div class="login-glass-card relative animate-in fade-in zoom-in-95 duration-300">
                
                <!-- 1. BRANDING HEADER -->
                <div class="flex flex-col items-center text-center mb-3">
                    <div class="w-10 h-10 flex items-center justify-center text-[#0B1A30] mb-1">
                        <svg class="w-9 h-9" viewBox="0 0 24 24" fill="none" stroke="#0B1A30" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 9.5L12 3l9 6.5V20a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V9.5z"/>
                            <rect x="9.5" y="11.5" width="5" height="5" stroke="#0B1A30" stroke-width="1.8"/>
                            <line x1="12" y1="11.5" x2="12" y2="16.5" stroke="#0B1A30" stroke-width="1.4"/>
                            <line x1="9.5" y1="14" x2="14.5" y2="14" stroke="#0B1A30" stroke-width="1.4"/>
                        </svg>
                    </div>

                    <div class="flex items-center justify-center leading-none">
                        <h1 class="text-[26px] sm:text-[28px] font-black tracking-tight text-[#0B1A30]">
                            RE<span class="text-[#0066FF]">CRM</span>
                        </h1>
                    </div>

                    <p class="text-[9.5px] font-black tracking-[0.14em] text-[#0B1A30] uppercase mt-1">
                        REAL ESTATE SALES &amp; CRM PLATFORM
                    </p>
                </div>

                <!-- 2. QUICK DEMO LOGIN HEADER -->
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-1 text-[#0B1A30]">
                        <svg class="w-4 h-4 fill-[#0066FF]" viewBox="0 0 24 24">
                            <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
                        </svg>
                        <span class="text-[11.5px] font-black uppercase tracking-wider text-[#0B1A30]">QUICK DEMO LOGIN</span>
                    </div>

                    <span class="inline-flex items-center gap-1 text-[10.5px] font-bold text-[#0B1A30] bg-white/70 border border-white/90 px-2.5 py-0.5 rounded-full shadow-2xs backdrop-blur-sm">
                        1-Click Sign In <span class="text-[11px] text-[#0066FF] font-bold">&rarr;</span>
                    </span>
                </div>

                <!-- 3. QUICK DEMO BUTTONS -->
                <div class="grid grid-cols-2 gap-2.5 mb-3">
                    <!-- Admin Demo -->
                    <button
                        type="button"
                        onclick="quickLogin('admin@example.com', 'admin123', 'Administrator', this)"
                        class="demo-glass-pill group flex items-center justify-between px-3 py-2.5 rounded-2xl cursor-pointer"
                        title="Click to 1-click login as Administrator"
                    >
                        <div class="flex items-center gap-1.5 truncate">
                            <span class="text-sm">👑</span>
                            <span class="text-[13px] font-black text-[#0B1A30]">Admin Demo</span>
                        </div>
                        <span class="text-xs font-black text-[#0B1A30] group-hover:translate-x-0.5 group-hover:text-[#0066FF] transition-all">&rarr;</span>
                    </button>

                    <!-- Agent Demo -->
                    <button
                        type="button"
                        onclick="quickLogin('ram@gmail.com', 'admin123', 'Sales Agent', this)"
                        class="demo-glass-pill group flex items-center justify-between px-3 py-2.5 rounded-2xl cursor-pointer"
                        title="Click to 1-click login as Sales Agent"
                    >
                        <div class="flex items-center gap-1.5 truncate">
                            <span class="text-sm">💼</span>
                            <span class="text-[13px] font-black text-[#0B1A30]">Agent Demo</span>
                        </div>
                        <span class="text-xs font-black text-[#0B1A30] group-hover:translate-x-0.5 group-hover:text-[#0066FF] transition-all">&rarr;</span>
                    </button>
                </div>

                <!-- Quick Demo Status Banner -->
                <div id="demoFeedback" class="hidden mb-2 text-center text-[10.5px] font-bold text-[#0066FF]"></div>

                {!! view_render_event('admin.sessions.login.form_controls.before') !!}

                <!-- 4. LOGIN FORM -->
                <x-admin::form :action="route('admin.session.store')" id="admin-login-form">
                    @csrf
                    <div class="space-y-3 text-left">
                        
                        <!-- Work Email -->
                        <div>
                            <label class="text-[12px] font-black text-[#0B1A30] block mb-1" for="email">
                                Work Email *
                            </label>

                            <div class="relative glass-input-wrap">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#0B1A30]">
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

                            <x-admin::form.control-group.error control-name="email" class="text-xs text-red-600 mt-1 font-bold" />
                        </div>

                        <!-- Password -->
                        <div>
                            <label class="text-[12px] font-black text-[#0B1A30] block mb-1" for="password">
                                Password *
                            </label>

                            <div class="relative glass-input-wrap">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#0B1A30]">
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
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-[#0B1A30] hover:text-[#0066FF] transition-colors cursor-pointer"
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

                            <x-admin::form.control-group.error control-name="password" class="text-xs text-red-600 mt-1 font-bold" />
                        </div>

                        <!-- Remember Me & Forgot Password Row -->
                        <div class="flex items-center justify-between pt-0.5">
                            <label class="flex items-center gap-2 cursor-pointer select-none text-[12px] font-black text-[#0B1A30]">
                                <input
                                    type="checkbox"
                                    name="remember"
                                    value="1"
                                    checked
                                    class="w-4 h-4 rounded border-white/60 bg-white text-[#0066FF] focus:ring-blue-600 cursor-pointer accent-[#0066FF]"
                                />
                                <span>Remember me</span>
                            </label>

                            <a
                                class="text-[12px] font-black text-[#0066FF] hover:underline"
                                href="{{ route('admin.forgot_password.create') }}"
                            >
                                Forgot password?
                            </a>
                        </div>

                        <!-- 5. SIGN IN BUTTON -->
                        <button
                            type="submit"
                            class="btn-electric-blue w-full flex items-center justify-center gap-2 cursor-pointer font-black shadow-md hover:shadow-xl mt-2"
                            aria-label="{{ trans('admin::app.users.login.submit-btn') }}"
                        >
                            <span>Sign In</span>
                            <span class="text-base font-bold">&rarr;</span>
                        </button>
                    </div>
                </x-admin::form>

                {!! view_render_event('admin.sessions.login.form_controls.after') !!}

                <!-- 6. CREATE ACCOUNT DIVIDER -->
                <div class="flex items-center gap-3 my-3">
                    <div class="flex-1 h-[1px] bg-slate-400/40"></div>
                    <span class="font-mono text-[8.5px] uppercase tracking-[0.2em] font-black text-[#475569] shrink-0">
                        CREATE ACCOUNT
                    </span>
                    <div class="flex-1 h-[1px] bg-slate-400/40"></div>
                </div>

                <!-- 7. CREATE ACCOUNT BUTTON (FROM REDESIGN) -->
                <div>
                    <a
                        href="{{ route('admin.register.create') }}"
                        class="btn-create-account w-full flex items-center justify-between px-4 py-2.5 rounded-2xl cursor-pointer"
                    >
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#0066FF]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                            <span class="text-[13px] font-black text-[#0B1A30]">Create Account</span>
                        </div>
                        <span class="text-xs font-black text-[#0B1A30] group-hover:translate-x-0.5 transition-all">&rarr;</span>
                    </a>
                </div>

                <!-- 8. FOOTER LINK -->
                <div class="mt-3 text-center">
                    <p class="text-[11.5px] font-bold text-[#0B1A30]">
                        Need access? Contact your Sales Admin or
                        <a href="/#contact" class="text-[#0066FF] font-black underline underline-offset-2 ml-0.5">
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
                        btn.classList.add('ring-2', 'ring-[#0066FF]', 'bg-white');
                    }

                    if (feedback) {
                        feedback.innerHTML = '<span class="inline-flex items-center gap-1 text-[#0066FF] font-black text-[11px] animate-pulse">✓ Signing in as ' + roleLabel + '...</span>';
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
