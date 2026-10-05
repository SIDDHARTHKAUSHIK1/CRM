<x-admin::layouts.anonymous>
    <!-- Page Title -->
    <x-slot:title>
        Real Estate CRM — Create Account
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
        .register-glass-card {
            width: 100% !important;
            max-width: 480px !important;
            margin: 0 auto !important;
            background: rgba(255, 255, 255, 0.42) !important;
            backdrop-filter: blur(28px) saturate(180%) !important;
            -webkit-backdrop-filter: blur(28px) saturate(180%) !important;
            border: 1.5px solid rgba(255, 255, 255, 0.75) !important;
            box-shadow: 0 30px 60px -15px rgba(0, 30, 80, 0.22), inset 0 1.5px 0 rgba(255, 255, 255, 0.9) !important;
            border-radius: 32px !important;
            padding: 1.65rem 1.85rem !important;
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
            padding: 0.68rem 0.85rem 0.68rem 2.45rem !important;
            font-size: 0.88rem !important;
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
                href="{{ route('admin.session.create') }}"
                class="landing-back-pill inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-bold text-[#0B1A30] hover:text-black group shadow-sm"
            >
                <div class="w-4 h-4 rounded-full bg-slate-900/10 flex items-center justify-center text-[#0B1A30] group-hover:-translate-x-0.5 transition-transform shadow-2xs">
                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </div>
                <span class="tracking-wide">Back to Sign In</span>
            </a>

            <div>
                <a
                    href="/"
                    class="landing-back-pill inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-bold text-[#0B1A30] hover:text-black group shadow-sm"
                >
                    <span>Landing Page</span>
                </a>
            </div>
        </header>

        <!-- LAYER 2: CENTER AUTHENTICATION SECTION -->
        <main class="relative z-20 w-full my-auto flex items-center justify-center shrink-0 py-3 sm:py-4">
            
            <!-- FROSTED TRANSLUCENT GLASS CARD -->
            <div class="register-glass-card relative animate-in fade-in zoom-in-95 duration-300">
                
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
                        <h1 class="text-[24px] sm:text-[26px] font-black tracking-tight text-[#0B1A30]">
                            Create Your <span class="text-[#0066FF]">Account</span>
                        </h1>
                    </div>

                    <p class="text-[10.5px] font-bold text-[#0B1A30] mt-1">
                        {{ trans('admin::app.users.register.subtitle') }}
                    </p>
                </div>

                <!-- 2. REGISTRATION FORM -->
                <x-admin::form :action="route('admin.register.store')" id="admin-register-form">
                    @csrf

                    <!-- Honeypot Field (Bot Trap) -->
                    <div style="position: absolute; left: -9999px; top: -9999px; opacity: 0; pointer-events: none;" aria-hidden="true">
                        <label for="website">Website</label>
                        <input type="text" name="website" id="website" tabindex="-1" autocomplete="off" value="" />
                    </div>

                    <div class="space-y-2.5 text-left">
                        
                        <!-- Row: Full Name & Company Name -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                            <!-- Full Name -->
                            <div>
                                <label class="text-[11.5px] font-black text-[#0B1A30] block mb-0.5" for="name">
                                    Full Name *
                                </label>

                                <div class="relative glass-input-wrap">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-[#0B1A30]">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <x-admin::form.control-group.control
                                        type="text"
                                        id="name"
                                        name="name"
                                        rules="required"
                                        :label="trans('admin::app.users.register.name')"
                                        :placeholder="trans('admin::app.users.register.name-placeholder')"
                                        :value="old('name')"
                                        autocomplete="name"
                                    />
                                </div>

                                <x-admin::form.control-group.error control-name="name" class="text-xs text-red-600 mt-0.5 font-bold" />
                            </div>

                            <!-- Company Name -->
                            <div>
                                <label class="text-[11.5px] font-black text-[#0B1A30] block mb-0.5" for="company_name">
                                    Company / Workspace *
                                </label>

                                <div class="relative glass-input-wrap">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-[#0B1A30]">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </div>
                                    <x-admin::form.control-group.control
                                        type="text"
                                        id="company_name"
                                        name="company_name"
                                        rules="required"
                                        :label="trans('admin::app.users.register.company')"
                                        :placeholder="trans('admin::app.users.register.company-placeholder')"
                                        :value="old('company_name')"
                                        autocomplete="organization"
                                    />
                                </div>

                                <x-admin::form.control-group.error control-name="company_name" class="text-xs text-red-600 mt-0.5 font-bold" />
                            </div>
                        </div>

                        <!-- Row: Work Email & Phone -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                            <!-- Work Email -->
                            <div>
                                <label class="text-[11.5px] font-black text-[#0B1A30] block mb-0.5" for="email">
                                    Work Email *
                                </label>

                                <div class="relative glass-input-wrap">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-[#0B1A30]">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <x-admin::form.control-group.control
                                        type="email"
                                        id="email"
                                        name="email"
                                        rules="required|email"
                                        :label="trans('admin::app.users.register.email')"
                                        :placeholder="trans('admin::app.users.register.email-placeholder')"
                                        :value="old('email')"
                                        autocomplete="email"
                                    />
                                </div>

                                <x-admin::form.control-group.error control-name="email" class="text-xs text-red-600 mt-0.5 font-bold" />
                            </div>

                            <!-- Phone -->
                            <div>
                                <label class="text-[11.5px] font-black text-[#0B1A30] block mb-0.5" for="phone">
                                    Phone Number <span class="text-slate-500 font-normal">(Optional)</span>
                                </label>

                                <div class="relative glass-input-wrap">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-[#0B1A30]">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                    </div>
                                    <x-admin::form.control-group.control
                                        type="text"
                                        id="phone"
                                        name="phone"
                                        :label="trans('admin::app.users.register.phone')"
                                        :placeholder="trans('admin::app.users.register.phone-placeholder')"
                                        :value="old('phone')"
                                        autocomplete="tel"
                                    />
                                </div>

                                <x-admin::form.control-group.error control-name="phone" class="text-xs text-red-600 mt-0.5 font-bold" />
                            </div>
                        </div>

                        <!-- Row: Password & Confirm Password -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                            <!-- Password -->
                            <div>
                                <label class="text-[11.5px] font-black text-[#0B1A30] block mb-0.5" for="password">
                                    Password *
                                </label>

                                <div class="relative glass-input-wrap">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-[#0B1A30]">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                    </div>
                                    
                                    <x-admin::form.control-group.control
                                        type="password"
                                        class="!pr-8"
                                        id="password"
                                        name="password"
                                        rules="required|min:8"
                                        :label="trans('admin::app.users.register.password')"
                                        placeholder="Min 8 chars"
                                        autocomplete="new-password"
                                    />

                                    <button
                                        type="button"
                                        onclick="togglePass('password', 'eyeOpen1', 'eyeClose1')"
                                        class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-[#0B1A30] hover:text-[#0066FF] transition-colors cursor-pointer"
                                        title="Toggle password visibility"
                                    >
                                        <svg id="eyeClose1" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                        </svg>
                                        <svg id="eyeOpen1" class="w-3.5 h-3.5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                </div>

                                <x-admin::form.control-group.error control-name="password" class="text-xs text-red-600 mt-0.5 font-bold" />
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label class="text-[11.5px] font-black text-[#0B1A30] block mb-0.5" for="password_confirmation">
                                    Confirm Password *
                                </label>

                                <div class="relative glass-input-wrap">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-[#0B1A30]">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                    </div>
                                    
                                    <x-admin::form.control-group.control
                                        type="password"
                                        class="!pr-8"
                                        id="password_confirmation"
                                        name="password_confirmation"
                                        rules="required|confirmed:@password"
                                        :label="trans('admin::app.users.register.confirm-password')"
                                        placeholder="Repeat password"
                                        autocomplete="new-password"
                                    />

                                    <button
                                        type="button"
                                        onclick="togglePass('password_confirmation', 'eyeOpen2', 'eyeClose2')"
                                        class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-[#0B1A30] hover:text-[#0066FF] transition-colors cursor-pointer"
                                        title="Toggle password visibility"
                                    >
                                        <svg id="eyeClose2" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                        </svg>
                                        <svg id="eyeOpen2" class="w-3.5 h-3.5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                </div>

                                <x-admin::form.control-group.error control-name="password_confirmation" class="text-xs text-red-600 mt-0.5 font-bold" />
                            </div>
                        </div>

                        <!-- Terms & Privacy Agreement -->
                        <div class="pt-1">
                            <label class="flex items-start gap-2 cursor-pointer select-none text-[11.5px] font-bold text-[#0B1A30]">
                                <input
                                    type="checkbox"
                                    name="agree"
                                    value="1"
                                    {{ old('agree') ? 'checked' : '' }}
                                    class="w-4 h-4 mt-0.5 rounded border-white/60 bg-white text-[#0066FF] focus:ring-blue-600 cursor-pointer accent-[#0066FF]"
                                    required
                                />
                                <span class="leading-tight">
                                    {{ trans('admin::app.users.register.terms') }}
                                </span>
                            </label>

                            <x-admin::form.control-group.error control-name="agree" class="text-xs text-red-600 mt-0.5 font-bold" />
                        </div>

                        <!-- Submit Button -->
                        <button
                            type="submit"
                            class="btn-electric-blue w-full flex items-center justify-center gap-2 cursor-pointer font-black shadow-md hover:shadow-xl mt-3"
                            aria-label="{{ trans('admin::app.users.register.submit-btn') }}"
                        >
                            <span>{{ trans('admin::app.users.register.submit-btn') }}</span>
                            <span class="text-base font-bold">&rarr;</span>
                        </button>
                    </div>
                </x-admin::form>

                <!-- 3. FOOTER SIGN IN LINK -->
                <div class="mt-3 text-center">
                    <p class="text-[11.5px] font-bold text-[#0B1A30]">
                        {{ trans('admin::app.users.register.have-account') }}
                        <a href="{{ route('admin.session.create') }}" class="text-[#0066FF] font-black underline underline-offset-2 ml-0.5">
                            {{ trans('admin::app.users.register.sign-in-link') }}
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
            function togglePass(fieldId, openIconId, closeIconId) {
                var passwordField = document.getElementById(fieldId);
                var eyeOpen = document.getElementById(openIconId);
                var eyeClose = document.getElementById(closeIconId);

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
