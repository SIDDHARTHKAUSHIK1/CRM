<x-admin::layouts.anonymous>
    <!-- Page Title -->
    <x-slot:title>
        Real Estate CRM — Reset Password
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
            background: rgba(255, 255, 255, 0.45) !important;
            backdrop-filter: blur(28px) saturate(180%) !important;
            -webkit-backdrop-filter: blur(28px) saturate(180%) !important;
            border: 1px solid rgba(255, 255, 255, 0.75) !important;
            box-shadow: 0 25px 50px -10px rgba(0, 0, 0, 0.22), 0 0 0 1px rgba(255, 255, 255, 0.5) inset !important;
            border-radius: 22px !important;
            padding: 1.35rem 1.65rem !important;
        }
        .glass-field-wrap {
            background: rgba(255, 255, 255, 0.6) !important;
            border: 1px solid rgba(255, 255, 255, 0.8) !important;
            border-radius: 0.65rem !important;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1) !important;
        }
        .glass-field-wrap:focus-within {
            background: rgba(255, 255, 255, 0.95) !important;
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
            color: #64748B !important;
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
        .landing-back-pill {
            background: rgba(255, 255, 255, 0.55);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.8);
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .landing-back-pill:hover {
            background: rgba(255, 255, 255, 0.92);
            border-color: #FFFFFF;
            transform: translateY(-1px);
            box-shadow: 0 8px 20px -4px rgba(0, 0, 0, 0.15);
        }
    </style>
    @endpush

    <!-- FULLSCREEN FIXED LUXURY REAL ESTATE EXPERIENCE (ZERO SCROLL) -->
    <div class="fixed inset-0 h-screen max-h-screen w-screen overflow-hidden flex flex-col justify-between font-sans selection:bg-[#047857] selection:text-white p-3 sm:p-5 box-border">
        
        <!-- LAYER 0: Background Landscape Image -->
        <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
            <img
                src="{{ asset('images/login-luxury-bg.jpg') }}"
                alt="Luxury Real Estate Panorama"
                class="w-full h-full object-cover object-center"
            />
            <div class="absolute inset-0 bg-black/5"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-black/25 via-transparent to-black/10"></div>
        </div>

        <!-- LAYER 1: TOP HEADER WITH STYLISH LANDING PAGE BUTTON -->
        <header class="relative z-20 w-full flex items-center justify-between shrink-0">
            <a
                href="/"
                class="landing-back-pill inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-bold text-[#0F241D] hover:text-[#047857] group"
            >
                <div class="w-4 h-4 rounded-full bg-emerald-100 flex items-center justify-center text-[#047857] group-hover:-translate-x-0.5 transition-transform shadow-xs">
                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </div>
                <span class="tracking-wide">Back to Landing Page</span>
            </a>
            <div></div>
        </header>

        <!-- LAYER 2: CENTER FROSTED CARD -->
        <main class="relative z-20 w-full my-auto flex items-center justify-center shrink-0">
            <div class="login-glass-container relative animate-in fade-in zoom-in-95 duration-300" style="max-width: 380px; width: 100%;">
                
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
                        <div class="flex-1 h-[1px] bg-slate-400/40"></div>
                        <span class="text-[7px] font-mono tracking-[0.18em] text-[#475569] font-bold uppercase whitespace-nowrap">
                            REAL ESTATE SALES &amp; CRM PLATFORM
                        </span>
                        <div class="flex-1 h-[1px] bg-slate-400/40"></div>
                    </div>
                </div>

                <div class="text-center mb-3 space-y-0.5">
                    <h1 class="font-serif text-[22px] text-[#0F172A] font-normal leading-tight">
                        Reset your
                    </h1>
                    <div class="font-serif text-[22px] italic text-[#047857] font-medium leading-tight">
                        CRM Password
                    </div>
                    <p class="text-[10.5px] text-[#64748B] pt-0.5">
                        Enter your work email to receive password reset instructions.
                    </p>
                </div>

                {!! view_render_event('admin.sessions.forgor_password.form_controls.before') !!}

                <!-- Reset Form -->
                <x-admin::form :action="route('admin.forgot_password.store')">
                    <div class="space-y-2.5 text-left">
                        <div>
                            <label class="block text-[10.5px] font-semibold text-[#1E293B] mb-0.5" for="email">
                                Registered Work Email *
                            </label>

                            <div class="relative glass-field-wrap">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-[#64748B]">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <x-admin::form.control-group.control
                                    type="email"
                                    id="email"
                                    name="email"
                                    rules="required|email"
                                    :label="trans('admin::app.users.forget-password.create.email')"
                                    placeholder="Enter your registered work email"
                                />
                            </div>

                            <x-admin::form.control-group.error control-name="email" class="text-xs text-red-600 mt-0.5 font-medium" />
                        </div>

                        <button
                            type="submit"
                            class="btn-obsidian w-full font-bold shadow-md hover:shadow-xl flex items-center justify-center gap-2 cursor-pointer mt-1.5"
                        >
                            <span>Send Reset Link</span>
                            <span class="text-sm font-bold">&rarr;</span>
                        </button>
                    </div>
                </x-admin::form>

                {!! view_render_event('admin.sessions.forgor_password.form_controls.after') !!}

                <div class="mt-3 pt-2.5 border-t border-slate-300/60 text-center">
                    <a
                        href="{{ route('admin.session.create') }}"
                        class="text-[10.5px] font-semibold text-[#047857] hover:text-[#065F46] transition-colors"
                    >
                        &larr; Return to Sign In
                    </a>
                </div>
            </div>
        </main>

        <div class="shrink-0 h-2"></div>
    </div>
</x-admin::layouts.anonymous>
