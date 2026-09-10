<x-admin::layouts>
    <x-slot:title>
        Create WhatsApp Broadcast - RealEstate CRM
    </x-slot>

    @pushOnce('styles')
        <style>
            .wa-chat-bg {
                background-color: #efeae2;
                background-image: radial-gradient(#d3cbbe 0.75px, transparent 0.75px), radial-gradient(#d3cbbe 0.75px, #efeae2 0.75px);
                background-size: 20px 20px;
                background-position: 0 0, 10px 10px;
            }
            .dark .wa-chat-bg {
                background-color: #0b141a;
                background-image: radial-gradient(#1f2c34 0.75px, transparent 0.75px), radial-gradient(#1f2c34 0.75px, #0b141a 0.75px);
                background-size: 20px 20px;
                background-position: 0 0, 10px 10px;
            }
        </style>
    @endPushOnce

    <div v-pre class="flex flex-col gap-6" id="whatsapp-create-app">
        <!-- Top Action Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 rounded-2xl border border-slate-200/90 bg-white p-5 shadow-xs dark:border-gray-800 dark:bg-gray-900">
            <div class="flex flex-col gap-1.5">
                <x-admin::breadcrumbs name="whatsapp.create" />

                <div class="flex items-center gap-3 flex-wrap">
                    <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-gray-800 dark:text-white">
                        Create New WhatsApp Broadcast
                    </h1>
                    
                    @if (!empty($gatewayStatus['connected']))
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-300 dark:bg-emerald-950 dark:text-emerald-200 dark:border-emerald-800">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            Gateway Connected &amp; Healthy
                        </span>
                    @else
                        <a 
                            href="{{ route('admin.whatsapp.gateway') }}"
                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-300 hover:bg-amber-200 transition dark:bg-amber-950 dark:text-amber-200 dark:border-amber-800"
                        >
                            <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                            Gateway Offline · Click to Link QR
                        </a>
                    @endif
                </div>
                <p class="text-xs font-semibold text-slate-600 dark:text-slate-300">
                    Send personalized luxury property brochures and launch announcements safely directly to client WhatsApp.
                </p>
            </div>

            <div class="flex items-center gap-3 flex-wrap">
                <a
                    href="{{ route('admin.whatsapp.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 text-xs font-bold text-slate-800 bg-slate-50 border border-slate-300 rounded-xl hover:bg-slate-100 shadow-2xs transition-all dark:border-gray-700 dark:bg-gray-800 dark:text-slate-100 dark:hover:bg-gray-700"
                >
                    <svg class="w-4 h-4 text-slate-600 dark:text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span>Back to Broadcasts</span>
                </a>
            </div>
        </div>

        @if ($errors->any())
            <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-xs font-bold text-rose-800 dark:border-rose-800 dark:bg-rose-950/50 dark:text-rose-200 shadow-xs">
                <p class="font-extrabold text-sm mb-1.5 flex items-center gap-2">
                    <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    Please check the following form issues:
                </p>
                <ul class="list-inside list-disc space-y-1 font-semibold pl-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Interactive Guided Stepper Bar -->
        <div class="bg-white dark:bg-gray-900 p-3 rounded-2xl border border-slate-200/90 dark:border-gray-800 shadow-xs">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2.5">
                <button
                    type="button"
                    onclick="scrollToStep('section-campaign-basics')"
                    class="flex items-center p-3 rounded-xl border text-left transition-all w-full cursor-pointer border-brandColor bg-brandColor/10 dark:bg-brandColor/20 shadow-xs"
                >
                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-brandColor text-white font-bold text-xs shadow-xs">1</span>
                    <div class="ml-3 min-w-0">
                        <p class="text-xs font-bold text-brandColor dark:text-brandColor leading-tight">Step 1</p>
                        <p class="text-xs font-bold text-gray-800 dark:text-white">Campaign Details</p>
                    </div>
                </button>

                <button
                    type="button"
                    onclick="scrollToStep('section-audience')"
                    class="flex items-center p-3 rounded-xl border text-left transition-all w-full cursor-pointer bg-slate-50/80 hover:bg-slate-100 border-slate-200/80 dark:bg-gray-800/60 dark:hover:bg-gray-800 dark:border-gray-700/60"
                >
                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-slate-200 text-slate-800 dark:bg-gray-700 dark:text-slate-200 font-bold text-xs">2</span>
                    <div class="ml-3 min-w-0">
                        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 leading-tight">Step 2</p>
                        <p class="text-xs font-bold text-gray-800 dark:text-white">Audience &amp; Numbers</p>
                    </div>
                </button>

                <button
                    type="button"
                    onclick="scrollToStep('section-media')"
                    class="flex items-center p-3 rounded-xl border text-left transition-all w-full cursor-pointer bg-slate-50/80 hover:bg-slate-100 border-slate-200/80 dark:bg-gray-800/60 dark:hover:bg-gray-800 dark:border-gray-700/60"
                >
                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-slate-200 text-slate-800 dark:bg-gray-700 dark:text-slate-200 font-bold text-xs">3</span>
                    <div class="ml-3 min-w-0">
                        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 leading-tight">Step 3</p>
                        <p class="text-xs font-bold text-gray-800 dark:text-white">Brochure &amp; Media</p>
                    </div>
                </button>

                <button
                    type="button"
                    onclick="scrollToStep('section-safety')"
                    class="flex items-center p-3 rounded-xl border text-left transition-all w-full cursor-pointer bg-slate-50/80 hover:bg-slate-100 border-slate-200/80 dark:bg-gray-800/60 dark:hover:bg-gray-800 dark:border-gray-700/60"
                >
                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-slate-200 text-slate-800 dark:bg-gray-700 dark:text-slate-200 font-bold text-xs">4</span>
                    <div class="ml-3 min-w-0">
                        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 leading-tight">Step 4</p>
                        <p class="text-xs font-bold text-gray-800 dark:text-white">Safety &amp; Schedule</p>
                    </div>
                </button>
            </div>
        </div>

        <form
            action="{{ route('admin.whatsapp.store') }}"
            method="POST"
            enctype="multipart/form-data"
            id="broadcast-form"
            class="space-y-6"
        >
            @csrf

            <!-- Main Dual Column Workspace Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                
                <!-- Left 7 Columns: Form Fields -->
                <div class="lg:col-span-7 flex flex-col gap-6">

                    <!-- Section 1: Campaign Details & Message Caption -->
                    <div id="section-campaign-basics" class="scroll-mt-6 rounded-2xl border border-slate-200/90 bg-white p-6 shadow-xs dark:border-gray-800 dark:bg-gray-900 space-y-5">
                        <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-gray-800">
                            <div class="flex items-center gap-2.5">
                                <span class="w-8 h-8 rounded-xl bg-purple-100 text-purple-600 dark:bg-purple-950/80 dark:text-purple-400 flex items-center justify-center font-bold text-sm">
                                    1
                                </span>
                                <div>
                                    <h2 class="text-base font-bold text-gray-800 dark:text-white">Campaign Basics &amp; WhatsApp Caption</h2>
                                    <p class="text-xs font-semibold text-slate-600 dark:text-slate-300">Set broadcast title and personalize client message copy</p>
                                </div>
                            </div>
                            <span class="text-xs font-bold px-2.5 py-1 bg-emerald-100 text-emerald-800 rounded-full border border-emerald-300 dark:bg-emerald-950 dark:text-emerald-300 dark:border-emerald-800">
                                Personalization Active
                            </span>
                        </div>

                        <!-- Campaign Title Input -->
                        <div>
                            <label for="name" class="block text-xs font-bold uppercase tracking-wider text-gray-800 dark:text-white mb-1.5">
                                Campaign Title <span class="text-rose-500">*</span>
                            </label>
                            <input
                                type="text"
                                name="name"
                                id="name"
                                required
                                placeholder="e.g. Exclusive Launch: Godrej Sky Terraces 4BHK Brochure"
                                value="{{ old('name', 'Exclusive Launch: Godrej Sky Terraces 4BHK Brochure') }}"
                                class="w-full text-sm font-bold border-slate-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-800 dark:text-white rounded-xl px-4 py-2.5 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 shadow-2xs"
                                oninput="updateLivePreview()"
                            >

                            <!-- Quick Tag Suggestions -->
                            <div class="flex items-center gap-2 mt-2.5 flex-wrap">
                                <span class="text-xs font-bold text-slate-500 dark:text-slate-400">Quick Tags:</span>
                                <button type="button" onclick="insertTag('🔥 New Launch: ')" class="text-xs px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-gray-800 text-slate-700 dark:text-slate-200 font-bold hover:bg-purple-100 hover:text-purple-700 transition cursor-pointer border border-slate-200 dark:border-gray-700">🔥 New Launch</button>
                                <button type="button" onclick="insertTag('💰 Exclusive Price Drop: ')" class="text-xs px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-gray-800 text-slate-700 dark:text-slate-200 font-bold hover:bg-purple-100 hover:text-purple-700 transition cursor-pointer border border-slate-200 dark:border-gray-700">💰 Price Drop</button>
                                <button type="button" onclick="insertTag('🏡 Open House VIP Invite: ')" class="text-xs px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-gray-800 text-slate-700 dark:text-slate-200 font-bold hover:bg-purple-100 hover:text-purple-700 transition cursor-pointer border border-slate-200 dark:border-gray-700">🏡 Open House VIP</button>
                            </div>
                        </div>

                        <!-- Message Composer with Formatting Toolbar & Token Chips -->
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="caption" class="block text-xs font-bold uppercase tracking-wider text-gray-800 dark:text-white">
                                    Message Caption &amp; Personalized Template
                                </label>
                                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Supports official WhatsApp formatting</span>
                            </div>

                            <div class="border border-slate-300 dark:border-gray-700 rounded-xl overflow-hidden focus-within:border-purple-500 focus-within:ring-2 focus-within:ring-purple-500/20 transition-all shadow-2xs">
                                <!-- Formatting Toolbar -->
                                <div class="bg-slate-50 dark:bg-gray-800/80 border-b border-slate-200 dark:border-gray-700 px-3.5 py-2 flex flex-wrap items-center justify-between gap-2">
                                    <div class="flex items-center gap-1.5 flex-wrap">
                                        <button type="button" onclick="wrapText('*', '*')" class="px-2 py-1 hover:bg-slate-200 dark:hover:bg-gray-700 rounded-lg text-slate-800 dark:text-white font-bold text-xs cursor-pointer" title="Bold text (*text*)">B</button>
                                        <button type="button" onclick="wrapText('_', '_')" class="px-2 py-1 hover:bg-slate-200 dark:hover:bg-gray-700 rounded-lg text-slate-800 dark:text-white font-serif italic text-xs cursor-pointer" title="Italic text (_text_)">I</button>
                                        <button type="button" onclick="wrapText('~', '~')" class="px-2 py-1 hover:bg-slate-200 dark:hover:bg-gray-700 rounded-lg text-slate-800 dark:text-white line-through text-xs cursor-pointer" title="Strikethrough (~text~)">S</button>
                                        
                                        <div class="h-4 w-px bg-slate-300 dark:bg-gray-600 mx-1"></div>

                                        <!-- Dynamic Lead Token Chips -->
                                        <button type="button" onclick="insertToken('{' + '{First Name}' + '}')" class="inline-flex items-center gap-1 px-2.5 py-1 bg-white dark:bg-gray-900 border border-slate-300 dark:border-gray-700 rounded-lg text-xs font-bold text-slate-800 dark:text-slate-200 hover:border-purple-400 hover:text-purple-600 transition shadow-2xs cursor-pointer">
                                            <span class="text-purple-600 font-extrabold">+</span>
                                            <span>@{{First Name}}</span>
                                        </button>
                                        <button type="button" onclick="insertToken('{' + '{Project Name}' + '}')" class="inline-flex items-center gap-1 px-2.5 py-1 bg-white dark:bg-gray-900 border border-slate-300 dark:border-gray-700 rounded-lg text-xs font-bold text-slate-800 dark:text-slate-200 hover:border-purple-400 hover:text-purple-600 transition shadow-2xs cursor-pointer">
                                            <span class="text-purple-600 font-extrabold">+</span>
                                            <span>@{{Project Name}}</span>
                                        </button>
                                        <button type="button" onclick="insertToken('{' + '{Agent Phone}' + '}')" class="inline-flex items-center gap-1 px-2.5 py-1 bg-white dark:bg-gray-900 border border-slate-300 dark:border-gray-700 rounded-lg text-xs font-bold text-slate-800 dark:text-slate-200 hover:border-purple-400 hover:text-purple-600 transition shadow-2xs cursor-pointer">
                                            <span class="text-purple-600 font-extrabold">+</span>
                                            <span>@{{Agent Phone}}</span>
                                        </button>
                                    </div>
                                    
                                    <div class="flex items-center gap-1">
                                        <button type="button" onclick="insertToken(' ✨ ')" class="p-1 text-sm hover:scale-110 transition cursor-pointer">✨</button>
                                        <button type="button" onclick="insertToken(' 🏡 ')" class="p-1 text-sm hover:scale-110 transition cursor-pointer">🏡</button>
                                        <button type="button" onclick="insertToken(' 📍 ')" class="p-1 text-sm hover:scale-110 transition cursor-pointer">📍</button>
                                        <button type="button" onclick="insertToken(' 📞 ')" class="p-1 text-sm hover:scale-110 transition cursor-pointer">📞</button>
                                    </div>
                                </div>

                                <!-- Textarea -->
                                <textarea
                                    name="caption"
                                    id="caption"
                                    rows="7"
                                    placeholder="Type the message to accompany your brochure/media file..."
                                    class="w-full border-0 p-4 text-sm font-medium text-gray-800 dark:text-white bg-white dark:bg-gray-900 focus:ring-0 leading-relaxed placeholder:text-slate-400"
                                    oninput="updateLivePreview()"
                                >{{ old('caption', "Hello " . "{" . "{First Name}" . "},

We are delighted to share the complete architectural brochure and exclusive payment plan for *Godrej Sky Terraces*, Worli Sea-Face.

✨ Key Highlights:
• Private sky deck with panoramic Arabian Sea views
• 4 & 5 BHK bespoke presidential suites
• Pre-launch pricing valid until Sunday only

Kindly find the attached luxury specification PDF brochure below.

Reply *'BROCHURE'* or call back to schedule a private VIP site tour.
Reply STOP to unsubscribe.") }}</textarea>
                            </div>

                            <!-- Quality & Spam Health Meter -->
                            <div class="flex items-center justify-between mt-2.5 px-1 flex-wrap gap-2">
                                <div class="flex items-center gap-2 text-xs font-bold">
                                    <span class="inline-flex items-center text-emerald-700 dark:text-emerald-400">
                                        <svg class="w-4 h-4 mr-1 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path clip-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" fill-rule="evenodd"></path>
                                        </svg>
                                        Readability: Excellent (Optimal WhatsApp Length)
                                    </span>
                                    <span class="text-slate-300 dark:text-gray-700">·</span>
                                    <span class="text-slate-600 dark:text-slate-300">Spam risk: <span class="font-bold text-emerald-600 dark:text-emerald-400">Very Low</span></span>
                                </div>
                                <span id="char-counter" class="text-xs font-bold text-gray-900 dark:text-white">486 / 1024 characters</span>
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Target Audience & Contact List -->
                    <div id="section-audience" class="scroll-mt-6 rounded-2xl border border-slate-200/90 bg-white p-6 shadow-xs dark:border-gray-800 dark:bg-gray-900 space-y-5">
                        <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-gray-800">
                            <div class="flex items-center gap-2.5">
                                <span class="w-8 h-8 rounded-xl bg-purple-100 text-purple-600 dark:bg-purple-950/80 dark:text-purple-400 flex items-center justify-center font-bold text-sm">
                                    2
                                </span>
                                <div>
                                    <h2 class="text-base font-bold text-gray-800 dark:text-white">Target Audience &amp; Contact List</h2>
                                    <p class="text-xs font-semibold text-slate-600 dark:text-slate-300">Select how you would like to provide client recipient numbers</p>
                                </div>
                            </div>
                            <span id="audience-status-pill" class="inline-flex items-center gap-1 px-3 py-1 bg-emerald-100 text-emerald-800 text-xs font-bold rounded-full border border-emerald-300 dark:bg-emerald-950 dark:text-emerald-300 dark:border-emerald-800">
                                <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Ready for Input</span>
                            </span>
                        </div>

                        <!-- Segmented Navigation for Upload Methods -->
                        <div class="flex border-b border-slate-200 dark:border-gray-800 gap-2">
                            <button
                                type="button"
                                id="tab-btn-file"
                                onclick="switchAudienceTab('file')"
                                class="border-b-2 border-purple-600 pb-2.5 px-4 text-xs font-extrabold text-purple-600 dark:text-purple-400 flex items-center gap-2 cursor-pointer transition"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <span>Upload Spreadsheet (Excel / CSV)</span>
                            </button>

                            <button
                                type="button"
                                id="tab-btn-manual"
                                onclick="switchAudienceTab('manual')"
                                class="border-b-2 border-transparent pb-2.5 px-4 text-xs font-bold text-slate-500 hover:text-gray-600dark:text-slate-400 dark:hover:text-white transition cursor-pointer"
                            >
                                Paste Numbers Manually
                            </button>
                        </div>

                        <!-- Tab Content: Spreadsheet File Upload Dropzone -->
                        <div id="tab-content-file" class="space-y-3">
                            <div class="border-2 border-dashed border-purple-300/80 bg-purple-50/20 hover:bg-purple-50/40 dark:bg-purple-950/20 dark:border-purple-900/60 rounded-2xl p-6 text-center transition-all group relative">
                                <input
                                    type="file"
                                    name="numbers_file"
                                    id="numbers_file"
                                    accept=".csv,.xlsx,.xls,.txt"
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                    onchange="handleNumbersFileSelected(event)"
                                >
                                <div class="w-12 h-12 rounded-2xl bg-white dark:bg-gray-800 text-purple-600 dark:text-purple-400 flex items-center justify-center mx-auto shadow-sm border border-purple-200 dark:border-gray-700 group-hover:scale-105 transition-transform">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                
                                <p id="numbers-file-label" class="mt-3 text-sm font-bold text-slate-800 dark:text-slate-200">
                                    <span class="text-purple-600 dark:text-purple-400 underline">Click to choose spreadsheet file</span> or drag &amp; drop
                                </p>
                                <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-1">Supports Microsoft Excel (.xlsx, .xls) and CSV (.csv)</p>

                                <div class="inline-flex items-center gap-1.5 mt-3 px-3 py-1 rounded-full bg-slate-100 dark:bg-gray-800 text-[11px] font-bold text-slate-700 dark:text-slate-300">
                                    <svg class="w-3.5 h-3.5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path clip-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" fill-rule="evenodd"></path>
                                    </svg>
                                    <span>Auto-cleans spaces, dashes &amp; adds +91 for 10-digit Indian numbers</span>
                                </div>
                            </div>
                        </div>

                        <!-- Tab Content: Manual Numbers Textarea -->
                        <div id="tab-content-manual" class="space-y-2 hidden">
                            <label for="manual_numbers" class="block text-xs font-bold uppercase tracking-wider text-gray-800 dark:text-white">
                                Type or Paste Numbers Manually
                            </label>
                            <textarea
                                name="manual_numbers"
                                id="manual_numbers"
                                rows="5"
                                placeholder="e.g.&#10;9876543210&#10;+91 98765 43211, 9876543212&#10;9876543213"
                                class="w-full text-xs font-bold p-3 border border-slate-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-800 dark:text-white rounded-xl focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 placeholder:text-slate-400 shadow-2xs"
                                oninput="handleManualNumbersInput()"
                            >{{ old('manual_numbers') }}</textarea>
                            <p class="text-[11px] font-semibold text-slate-500 dark:text-slate-400">
                                Pasted numbers are automatically merged and deduplicated with any uploaded spreadsheet.
                            </p>
                        </div>
                    </div>

                    <!-- Section 3: Property Brochure & Media File -->
                    <div id="section-media" class="scroll-mt-6 rounded-2xl border border-slate-200/90 bg-white p-6 shadow-xs dark:border-gray-800 dark:bg-gray-900 space-y-5">
                        <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-gray-800">
                            <div class="flex items-center gap-2.5">
                                <span class="w-8 h-8 rounded-xl bg-purple-100 text-purple-600 dark:bg-purple-950/80 dark:text-purple-400 flex items-center justify-center font-bold text-sm">
                                    3
                                </span>
                                <div>
                                    <h2 class="text-base font-bold text-gray-800 dark:text-white">Property Brochure &amp; Media File</h2>
                                    <p class="text-xs font-semibold text-slate-600 dark:text-slate-300">Attach floor plans, architectural PDF brochures, or video walkthroughs</p>
                                </div>
                            </div>
                            <span class="text-xs font-bold text-slate-600 dark:text-slate-300">Max size: {{ config('whatsapp.max_media_mb', 16) }} MB</span>
                        </div>

                        <!-- Active File Upload Card / Dropzone -->
                        <div
                            id="brochure-dropzone"
                            class="relative rounded-2xl border-2 border-dashed border-slate-300 bg-slate-50/60 p-6 transition-all group hover:border-purple-500 hover:bg-purple-50/20 dark:border-gray-700 dark:bg-gray-800/40 dark:hover:border-purple-500"
                        >
                            <input
                                type="file"
                                name="brochure_file"
                                id="brochure_file"
                                accept=".pdf,.jpg,.jpeg,.png,.webp,.gif,.mp4,.webm,.mov,.doc,.docx,.xls,.xlsx,.csv,.txt"
                                class="absolute inset-0 z-10 h-full w-full cursor-pointer opacity-0"
                                onchange="handleBrochureFileSelected(event)"
                            >

                            <!-- State 1: Empty State (Prompt to Upload) -->
                            <div id="brochure-empty-zone" class="flex flex-col items-center justify-center text-center py-3">
                                <div class="w-14 h-14 rounded-2xl bg-purple-100 dark:bg-purple-950/70 text-purple-600 dark:text-purple-400 flex items-center justify-center mx-auto shadow-xs border border-purple-200 dark:border-purple-800/60 group-hover:scale-105 transition-transform">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                </div>
                                <h3 class="mt-3 text-sm font-bold text-gray-800 dark:text-white">
                                    <span class="text-purple-600 dark:text-purple-400 underline underline-offset-2">Click to choose Property Media</span> or drag &amp; drop here
                                </h3>
                                <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-1">
                                    Upload architectural PDF brochure, high-res photos (JPG/PNG/WEBP), or video walkthrough (MP4)
                                </p>
                                <div class="mt-3.5 inline-flex flex-wrap items-center justify-center gap-2 text-[11px] font-bold text-slate-600 dark:text-slate-300">
                                    <span class="px-2.5 py-0.5 rounded-full bg-rose-100 text-rose-800 dark:bg-rose-950 dark:text-rose-300 border border-rose-200 dark:border-rose-900">📄 PDF Brochure</span>
                                    <span class="px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-900">🖼️ Photos</span>
                                    <span class="px-2.5 py-0.5 rounded-full bg-purple-100 text-purple-800 dark:bg-purple-950 dark:text-purple-300 border border-purple-200 dark:border-purple-900">🎬 Video Walkthrough</span>
                                    <span class="px-2.5 py-0.5 rounded-full bg-blue-100 text-blue-800 dark:bg-blue-950 dark:text-blue-300 border border-blue-200 dark:border-blue-900">📁 Documents</span>
                                </div>
                            </div>                            <!-- State 2: Active Uploaded File Card -->
                            <div id="brochure-filled-zone" class="hidden flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="flex items-center gap-3.5 min-w-0">
                                    <!-- Dynamic Icon / Thumbnail Box -->
                                    <div id="brochure-icon-box" class="w-14 h-14 rounded-2xl bg-rose-500 text-white flex items-center justify-center font-black text-sm shadow-md shrink-0 overflow-hidden border border-slate-200 dark:border-gray-700">
                                        PDF
                                    </div>
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-2">
                                            <p id="brochure-name" class="text-sm font-bold text-gray-800 dark:text-white truncate max-w-xs sm:max-w-md">
                                                Brochure_Filename.pdf
                                            </p>
                                            <span class="px-2 py-0.5 text-[10px] font-bold rounded-md bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-800 shrink-0">
                                                ✓ Attached
                                            </span>
                                        </div>
                                        <p id="brochure-info" class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-0.5">
                                            0 MB · Ready to send · Optimized for WhatsApp
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 shrink-0 z-20">
                                    <!-- Full Mode Preview Button -->
                                    <button
                                        type="button"
                                        id="brochure-fullmode-btn"
                                        onclick="openFullModeModal()"
                                        class="px-3.5 py-2 text-xs font-bold text-purple-700 bg-purple-50 hover:bg-purple-100 border border-purple-200 dark:bg-purple-950/70 dark:text-purple-300 dark:border-purple-800 rounded-xl shadow-2xs transition flex items-center gap-1.5 cursor-pointer active:scale-95"
                                        title="Preview in full high-resolution viewer"
                                    >
                                        <span>🔍</span>
                                        <span>Full Mode</span>
                                    </button>

                                    <!-- Choose / Replace Trigger -->
                                    <label
                                        for="brochure_file"
                                        class="px-3.5 py-2 text-xs font-bold text-slate-700 dark:text-slate-200 bg-white dark:bg-gray-700 hover:bg-slate-100 dark:hover:bg-gray-600 border border-slate-300 dark:border-gray-600 rounded-xl shadow-2xs transition cursor-pointer flex items-center gap-1.5"
                                    >
                                        <span>📁</span>
                                        <span>Replace File</span>
                                    </label>

                                    <!-- Remove Button -->
                                    <button
                                        type="button"
                                        id="brochure-remove-btn"
                                        onclick="removeBrochureFile()"
                                        class="px-2.5 py-2 text-xs font-bold text-rose-600 hover:text-white hover:bg-rose-600 dark:text-rose-400 dark:hover:bg-rose-600 dark:hover:text-white rounded-xl transition cursor-pointer border border-rose-200 dark:border-rose-900 shadow-2xs"
                                        title="Remove attachment (send message text only)"
                                    >
                                        ✕ Remove
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Media Types Feature Highlights -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-center">
                            <div class="p-3 rounded-xl border border-slate-200 dark:border-gray-800 bg-slate-50/80 dark:bg-gray-800/60">
                                <p class="text-xs font-bold text-gray-800 dark:text-white">📄 PDF Brochure</p>
                                <p class="text-[11px] font-semibold text-slate-500 dark:text-slate-400">Full catalog with in-app reader</p>
                            </div>
                            <div class="p-3 rounded-xl border border-slate-200 dark:border-gray-800 bg-slate-50/80 dark:bg-gray-800/60">
                                <p class="text-xs font-bold text-gray-800 dark:text-white">🖼️ Photos (JPG/PNG/WEBP)</p>
                                <p class="text-[11px] font-semibold text-slate-500 dark:text-slate-400">Inline chat preview + Lightbox</p>
                            </div>
                            <div class="p-3 rounded-xl border border-slate-200 dark:border-gray-800 bg-slate-50/80 dark:bg-gray-800/60">
                                <p class="text-xs font-bold text-gray-800 dark:text-white">🎬 Video Walkthrough</p>
                                <p class="text-[11px] font-semibold text-slate-500 dark:text-slate-400">Playable video player + Full mode</p>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Right 5 Columns: Live Phone Mockup Preview & Safety Controls -->
                <div class="lg:col-span-5 flex flex-col gap-6">

                    <!-- Live WhatsApp Phone Chat Mockup -->
                    <div class="rounded-3xl border-4 border-slate-800 bg-slate-900 p-2 shadow-xl">
                        <div class="rounded-2xl overflow-hidden bg-white dark:bg-gray-900 flex flex-col">
                            <!-- WhatsApp Header -->
                            <div class="bg-[#075e54] text-white p-3.5 flex items-center justify-between shadow-md">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-9 h-9 rounded-full bg-brandColor text-white font-bold text-sm flex items-center justify-center ring-2 ring-white/40">
                                        RE
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-1">
                                            <h4 class="text-xs font-extrabold text-white leading-tight">Luxury Real Estate VIP</h4>
                                            <svg class="w-3.5 h-3.5 text-emerald-300" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <p class="text-[10px] text-emerald-200 font-medium">Official Business Line · Online</p>
                                    </div>
                                </div>

                                <span class="text-[10px] font-bold bg-white/20 px-2 py-0.5 rounded text-white">Preview</span>
                            </div>

                            <!-- WhatsApp Chat Body with Authentic Wallpaper -->
                            <div id="mockup-chat-body" class="wa-chat-bg p-3.5 min-h-[440px] max-h-[520px] overflow-y-auto flex flex-col justify-start">
                                <!-- Outgoing Message Bubble -->
                                <div class="bg-[#dcf8c6] dark:bg-[#005c4b] rounded-2xl rounded-tr-none p-3 max-w-[92%] ml-auto shadow-sm space-y-2.5 border border-emerald-200 dark:border-emerald-800 text-gray-800 dark:text-white">
                                    
                                    <!-- Dynamic Media Preview Container in Message Bubble -->
                                    <div id="mockup-media-container" class="space-y-2">
                                        <!-- 1. Image Preview Mode -->
                                        <div id="mockup-media-image" class="hidden group relative cursor-pointer overflow-hidden rounded-xl bg-black/10 dark:bg-black/40 border border-emerald-300/60 dark:border-emerald-700/60 shadow-xs" onclick="openFullModeModal()">
                                            <img id="mockup-img-el" src="" alt="Brochure Image Preview" class="w-full max-h-[230px] object-cover rounded-xl transition group-hover:scale-[1.02]">
                                            <div class="absolute inset-0 bg-black/35 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2 text-white text-xs font-bold backdrop-blur-xs">
                                                <span class="text-base">🔍</span>
                                                <span>Click to View Full High-Res Image</span>
                                            </div>
                                            <span class="absolute bottom-2 right-2 bg-black/60 text-white text-[10px] font-bold px-2 py-0.5 rounded-md backdrop-blur-xs flex items-center gap-1">
                                                <span>🖼️</span> <span>Full View</span>
                                            </span>
                                        </div>

                                        <!-- 2. Video Preview Mode (Playable Inline & Full Mode) -->
                                        <div id="mockup-media-video" class="hidden relative rounded-xl overflow-hidden bg-black border border-emerald-300/60 dark:border-emerald-700/60 shadow-xs">
                                            <video id="mockup-video-el" controls playsinline preload="metadata" class="w-full max-h-[230px] rounded-xl bg-black object-contain"></video>
                                            <button
                                                type="button"
                                                onclick="openFullModeModal()"
                                                class="absolute top-2 right-2 bg-black/75 hover:bg-black text-white text-[10px] font-bold px-2.5 py-1 rounded-lg backdrop-blur-xs border border-white/20 transition flex items-center gap-1 shadow-sm cursor-pointer z-10"
                                            >
                                                <span>⛶</span>
                                                <span>Full Mode</span>
                                            </button>
                                        </div>

                                        <!-- 3. PDF Document Preview Mode -->
                                        <div id="mockup-media-pdf" class="bg-white/90 dark:bg-black/30 p-2.5 rounded-xl border border-emerald-300/60 dark:border-emerald-700/60 flex flex-col gap-2 shadow-2xs">
                                            <div class="flex items-center gap-2.5 cursor-pointer" onclick="openFullModeModal()">
                                                <div class="w-9 h-9 rounded-lg bg-rose-500 text-white font-black text-[10px] flex items-center justify-center shrink-0 shadow-xs">
                                                    PDF
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <p id="mockup-pdf-title" class="text-xs font-bold text-gray-800 dark:text-white truncate">
                                                        Godrej_SkyTerraces_Brochure.pdf
                                                    </p>
                                                    <p id="mockup-pdf-info" class="text-[10px] font-semibold text-slate-500 dark:text-slate-300">
                                                        4.8 MB · PDF Brochure Document
                                                    </p>
                                                </div>
                                            </div>
                                            <button
                                                type="button"
                                                onclick="openFullModeModal()"
                                                class="w-full py-1.5 px-3 bg-rose-50 hover:bg-rose-100 text-rose-700 dark:bg-rose-950/60 dark:hover:bg-rose-900/80 dark:text-rose-300 border border-rose-200 dark:border-rose-800/80 rounded-lg text-[11px] font-bold flex items-center justify-center gap-1.5 transition cursor-pointer"
                                            >
                                                <span>👁️</span>
                                                <span>Open &amp; Read PDF in Full Mode</span>
                                            </button>
                                        </div>

                                        <!-- 4. Generic Document Preview Mode -->
                                        <div id="mockup-media-doc" class="hidden bg-white/90 dark:bg-black/30 p-2.5 rounded-xl border border-emerald-300/60 dark:border-emerald-700/60 flex items-center justify-between gap-2.5 shadow-2xs">
                                            <div class="flex items-center gap-2.5 min-w-0">
                                                <div class="w-9 h-9 rounded-lg bg-blue-600 text-white font-black text-[10px] flex items-center justify-center shrink-0 shadow-xs">
                                                    DOC
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <p id="mockup-doc-title" class="text-xs font-bold text-gray-800 dark:text-white truncate">
                                                        Attachment.docx
                                                    </p>
                                                    <p id="mockup-doc-info" class="text-[10px] font-semibold text-slate-500 dark:text-slate-300">
                                                        Document Attachment
                                                    </p>
                                                </div>
                                            </div>
                                            <button
                                                type="button"
                                                onclick="openFullModeModal()"
                                                class="px-2.5 py-1 text-[11px] font-bold text-blue-700 bg-blue-50 hover:bg-blue-100 dark:bg-blue-950 dark:text-blue-300 rounded-lg border border-blue-200 dark:border-blue-800 shrink-0 cursor-pointer"
                                            >
                                                Preview
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Live Parsed Caption Text -->
                                    <div id="mockup-caption-text" class="text-xs text-slate-800 dark:text-slate-100 font-medium leading-relaxed whitespace-pre-line">
                                        Hello Siddharth,

We are delighted to share the complete architectural brochure and exclusive payment plan for <strong>Godrej Sky Terraces</strong>, Worli Sea-Face.

✨ Key Highlights:
• Private sky deck with panoramic Arabian Sea views
• 4 &amp; 5 BHK bespoke presidential suites
• Pre-launch pricing valid until Sunday only

Kindly find the attached luxury specification PDF brochure below.

Reply <strong>'BROCHURE'</strong> or call back to schedule a private VIP site tour.
Reply STOP to unsubscribe.
                                    </div>

                                    <!-- Timestamp & Blue Double Ticks -->
                                    <div class="flex items-center justify-end gap-1 text-[10px] text-slate-500 dark:text-slate-300 font-bold pt-1">
                                        <span id="mockup-timestamp">11:45 AM</span>
                                        <span class="text-blue-500 font-bold">✓✓</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 4: Safety & Anti-Ban Controls -->
                    <div id="section-safety" class="scroll-mt-6 rounded-2xl border border-slate-200/90 bg-white p-6 shadow-xs dark:border-gray-800 dark:bg-gray-900 space-y-5">
                        <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-gray-800">
                            <div class="flex items-center gap-2.5">
                                <span class="w-8 h-8 rounded-xl bg-purple-100 text-purple-600 dark:bg-purple-950/80 dark:text-purple-400 flex items-center justify-center font-bold text-sm">
                                    🛡️
                                </span>
                                <div>
                                    <h3 class="text-base font-bold text-gray-800 dark:text-white">Safety &amp; Anti-Ban Controls</h3>
                                    <p class="text-xs font-semibold text-slate-600 dark:text-slate-300">Human-like pacing ensures high delivery &amp; zero phone bans</p>
                                </div>
                            </div>
                            <span class="text-xs font-bold px-2.5 py-1 bg-emerald-100 text-emerald-800 rounded-full border border-emerald-300 dark:bg-emerald-950 dark:text-emerald-300 dark:border-emerald-800">
                                Protection Active
                            </span>
                        </div>

                        <!-- Dispatch Interval Delay Slider & Input -->
                        <div class="space-y-2">
                            <div class="flex items-center justify-between text-xs">
                                <label for="throttle_seconds" class="font-bold text-gray-800 dark:text-white">
                                    Dispatch Interval Delay <span class="text-rose-500">*</span>
                                </label>
                                <span id="delay-badge" class="font-bold text-gray-900 dark:text-white bg-gray-100 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 px-2.5 py-0.5 rounded-lg">
                                    20 Seconds (Recommended)
                                </span>
                            </div>

                            <div class="flex items-center gap-4">
                                <input
                                    type="range"
                                    id="throttle_slider"
                                    min="5"
                                    max="120"
                                    value="{{ old('throttle_seconds', config('whatsapp.default_throttle_seconds', 20)) }}"
                                    class="w-full h-2 bg-slate-200 dark:bg-gray-700 rounded-lg appearance-none cursor-pointer accent-purple-600"
                                    oninput="handleThrottleSliderChange(this.value)"
                                >
                                <input
                                    type="number"
                                    name="throttle_seconds"
                                    id="throttle_seconds"
                                    min="5"
                                    max="300"
                                    value="{{ old('throttle_seconds', config('whatsapp.default_throttle_seconds', 20)) }}"
                                    class="w-20 text-center font-bold text-xs border border-slate-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white rounded-xl px-2 py-1.5"
                                    oninput="handleThrottleInputChange(this.value)"
                                >
                            </div>

                            <div class="flex justify-between text-[11px] font-bold text-gray-900 dark:text-white">
                                <span>Fast (5-10s)</span>
                                <span>Safe Pacing (15-30s)</span>
                                <span>Conservative (60s+)</span>
                            </div>
                        </div>

                        <!-- Daily Limit Batching -->
                        <div>
                            <label for="daily_limit" class="block text-xs font-bold uppercase tracking-wider text-gray-800 dark:text-white mb-1">
                                Daily Send Limit (Optional)
                            </label>
                            <div class="relative">
                                <input
                                    type="number"
                                    name="daily_limit"
                                    id="daily_limit"
                                    min="10"
                                    max="5000"
                                    placeholder="e.g. 300"
                                    value="{{ old('daily_limit') }}"
                                    class="w-full text-xs font-bold border-slate-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-800 dark:text-white rounded-xl px-3.5 py-2.5 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 shadow-2xs"
                                >
                                <span class="absolute inset-y-0 right-3 flex items-center text-xs font-bold text-slate-400 pointer-events-none">
                                    messages / day
                                </span>
                            </div>
                            <p class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 mt-1">
                                Remaining contacts stay queued for the subsequent batch cycle.
                            </p>
                        </div>

                        <!-- Circuit Breaker Notice Card -->
                        <div class="p-3.5 rounded-xl bg-sky-50 dark:bg-sky-950/40 border border-sky-200 dark:border-sky-800 flex items-start gap-3">
                            <span class="text-sky-600 dark:text-sky-400 shrink-0 mt-0.5">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </span>
                            <div>
                                <h4 class="text-xs font-bold text-sky-900 dark:text-sky-200">Auto-Pause Circuit Breaker Active</h4>
                                <p class="text-[11px] font-semibold text-sky-800 dark:text-sky-300 mt-0.5 leading-normal">
                                    If 5 consecutive message deliveries fail, the broadcast halts automatically to protect your line reputation.
                                </p>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            <!-- Bottom Sticky Action Bar -->
            <div class="rounded-2xl border border-slate-200/90 bg-white p-5 shadow-xs dark:border-gray-800 dark:bg-gray-900 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300 flex items-center justify-center font-bold text-sm">
                        ⚡
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-800 dark:text-white">WhatsApp Broadcast Delivery Engine Ready</p>
                        <p class="text-[11px] font-semibold text-slate-500 dark:text-slate-400">Official Web Gateway · High Deliverability Guarantee</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                    <a
                        href="{{ route('admin.whatsapp.index') }}"
                        class="secondary-button !px-4 !py-2.5 !rounded-xl !text-xs font-bold transition cursor-pointer"
                    >
                        Save as Draft
                    </a>

                    <button
                        type="submit"
                        class="primary-button !px-6 !py-2.5 !rounded-xl !text-xs font-bold tracking-wide shadow-md active:scale-95 transition-all gap-2 cursor-pointer"
                    >
                        <span>Upload &amp; Preview Contacts</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Interactive Full-Mode Media Lightbox & PDF Viewer Modal -->
    <div
        id="full-mode-media-modal"
        class="fixed inset-0 hidden items-center justify-center bg-black/85 p-3 sm:p-6 backdrop-blur-md transition-opacity duration-200"
        style="z-index: 99999999 !important; position: fixed !important; top: 0 !important; left: 0 !important; right: 0 !important; bottom: 0 !important; width: 100vw !important; height: 100vh !important;"
        onclick="if(event.target === this) closeFullModeModal()"
    >
        <div class="relative w-full max-w-5xl flex flex-col rounded-3xl border border-slate-700/80 bg-slate-900 text-white shadow-2xl overflow-hidden m-auto" style="height: 88vh !important; max-height: 88vh !important;">
                <!-- Modal Top Header Bar -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-800 bg-slate-950/95 backdrop-blur-sm shrink-0">
                    <div class="flex items-center gap-3 min-w-0">
                        <span id="fullmode-badge" class="px-2.5 py-1 rounded-lg bg-purple-500/20 text-purple-300 border border-purple-500/30 font-bold text-xs">
                            MEDIA
                        </span>
                        <div class="min-w-0">
                            <h3 id="fullmode-filename" class="text-sm font-bold text-white truncate max-w-md sm:max-w-xl">
                                Filename
                            </h3>
                            <p id="fullmode-filesize" class="text-[11px] font-medium text-slate-400">
                                Size info
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2.5 shrink-0">
                        <!-- Open in New Tab / Download CTA -->
                        <a
                            id="fullmode-download-btn"
                            href="#"
                            target="_blank"
                            class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-bold border border-slate-700 shadow-xs transition cursor-pointer"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                            </svg>
                            <span>Open in New Tab</span>
                        </a>

                        <!-- Close Modal Button -->
                        <button
                            type="button"
                            onclick="closeFullModeModal()"
                            class="flex h-8 w-8 items-center justify-center rounded-xl bg-slate-800 text-slate-300 hover:bg-rose-600 hover:text-white transition cursor-pointer font-bold text-sm"
                            title="Close Full View (Esc)"
                        >
                            ✕
                        </button>
                    </div>
                </div>

                <!-- Modal Content Container -->
                <div class="relative flex-1 overflow-hidden p-4 flex items-center justify-center bg-slate-950/80 w-full" style="height: calc(88vh - 70px) !important;">
                    <!-- 1. Full Image Element -->
                    <img
                        id="fullmode-image-el"
                        src=""
                        alt="Full View"
                        class="hidden max-h-full max-w-full object-contain rounded-xl shadow-2xl"
                    >

                    <!-- 2. Full Video Element -->
                    <video
                        id="fullmode-video-el"
                        src=""
                        controls
                        autoplay
                        playsinline
                        class="hidden w-full rounded-xl bg-black shadow-2xl object-contain"
                        style="height: 100% !important; max-height: 100% !important;"
                    ></video>

                    <!-- 3. Full PDF / Document Iframe Viewer -->
                    <iframe
                        id="fullmode-iframe-el"
                        src=""
                        class="hidden w-full rounded-xl border border-slate-800 bg-white shadow-2xl"
                        style="height: 100% !important; width: 100% !important;"
                    ></iframe>

                    <!-- 4. Fallback Container for unsupported document formats -->
                    <div id="fullmode-fallback-el" class="hidden flex-col items-center justify-center p-8 text-center m-auto">
                        <div class="w-16 h-16 rounded-2xl bg-blue-500/20 text-blue-400 flex items-center justify-center text-3xl mb-3 shadow-inner">
                            📄
                        </div>
                        <h4 class="text-base font-bold text-white mb-1">Document File Attached</h4>
                        <p class="text-xs text-slate-400 max-w-md mb-5">
                            This file format is ready to be sent with your broadcast. Click below to view or download it directly.
                        </p>
                        <a
                            id="fullmode-fallback-link"
                            href="#"
                            target="_blank"
                            class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-md transition"
                        >
                            Open Document
                        </a>
                    </div>
                </div>
            </div>
        </div>

    @pushOnce('scripts')
        <script>
            let currentBrochure = {
                file: null,
                url: '',
                type: 'none', // 'image' | 'video' | 'pdf' | 'document' | 'none'
                name: 'No Media Attached',
                sizeText: 'Upload a property brochure, photos, or video'
            };

            function detectMediaType(file) {
                const ext = (file.name.split('.').pop() || '').toLowerCase();
                if (file.type.startsWith('image/') || ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg', 'bmp', 'tiff'].includes(ext)) {
                    return 'image';
                }
                if (file.type.startsWith('video/') || ['mp4', 'webm', 'mov', 'mkv', 'avi', '3gp', 'ogg', 'ogv'].includes(ext)) {
                    return 'video';
                }
                if (file.type === 'application/pdf' || ext === 'pdf') {
                    return 'pdf';
                }
                return 'document';
            }

            function handleBrochureFileSelected(event) {
                const file = event.target && event.target.files ? event.target.files[0] : (event.files ? event.files[0] : null);
                if (!file) return;

                const maxMb = {{ config('whatsapp.max_media_mb', 16) }};
                if (file.size > maxMb * 1024 * 1024) {
                    alert(`Selected file is too large (${(file.size / (1024 * 1024)).toFixed(2)} MB). Maximum allowed size is ${maxMb} MB.`);
                    if (event.target && event.target.value) event.target.value = '';
                    return;
                }

                const mediaType = detectMediaType(file);
                const sizeMb = (file.size / (1024 * 1024)).toFixed(2);
                const sizeText = `${sizeMb} MB · ${file.name.split('.').pop().toUpperCase()} File · WhatsApp Ready`;
                const blobUrl = URL.createObjectURL(file);

                currentBrochure = {
                    file: file,
                    url: blobUrl,
                    type: mediaType,
                    name: file.name,
                    sizeText: sizeText
                };

                updateMediaUI();
            }

            function removeBrochureFile() {
                const input = document.getElementById('brochure_file');
                if (input) input.value = '';

                currentBrochure = {
                    file: null,
                    url: '',
                    type: 'none',
                    name: 'No Media Attached',
                    sizeText: 'Direct WhatsApp message without brochure file'
                };

                updateMediaUI();
            }

            function updateMediaUI() {
                const emptyZone = document.getElementById('brochure-empty-zone');
                const filledZone = document.getElementById('brochure-filled-zone');
                const iconBox = document.getElementById('brochure-icon-box');
                const nameEl = document.getElementById('brochure-name');
                const infoEl = document.getElementById('brochure-info');

                if (nameEl) nameEl.textContent = currentBrochure.name;
                if (infoEl) infoEl.textContent = currentBrochure.sizeText;

                if (currentBrochure.type === 'none' || !currentBrochure.url) {
                    if (emptyZone) {
                        emptyZone.classList.remove('hidden');
                        emptyZone.classList.add('flex');
                    }
                    if (filledZone) {
                        filledZone.classList.add('hidden');
                        filledZone.classList.remove('flex');
                    }
                } else {
                    if (emptyZone) {
                        emptyZone.classList.add('hidden');
                        emptyZone.classList.remove('flex');
                    }
                    if (filledZone) {
                        filledZone.classList.remove('hidden');
                        filledZone.classList.add('flex');
                    }

                    if (iconBox) {
                        if (currentBrochure.type === 'image' && currentBrochure.url) {
                            iconBox.className = 'w-14 h-14 rounded-2xl bg-slate-100 dark:bg-gray-800 flex items-center justify-center shadow-md shrink-0 overflow-hidden border border-slate-200 dark:border-gray-700';
                            iconBox.innerHTML = `<img src="${currentBrochure.url}" alt="${currentBrochure.name}" class="w-full h-full object-cover">`;
                        } else if (currentBrochure.type === 'video') {
                            iconBox.className = 'w-14 h-14 rounded-2xl bg-purple-600 text-white flex items-center justify-center font-bold text-xl shadow-md shrink-0';
                            iconBox.innerHTML = '🎬';
                        } else if (currentBrochure.type === 'pdf') {
                            iconBox.className = 'w-14 h-14 rounded-2xl bg-rose-500 text-white flex items-center justify-center font-black text-sm shadow-md shrink-0';
                            iconBox.innerHTML = 'PDF';
                        } else {
                            iconBox.className = 'w-14 h-14 rounded-2xl bg-blue-600 text-white flex items-center justify-center font-black text-sm shadow-md shrink-0';
                            iconBox.innerHTML = 'DOC';
                        }
                    }
                }

                // Update Right Phone Mockup Bubble
                const imgWrap = document.getElementById('mockup-media-image');
                const videoWrap = document.getElementById('mockup-media-video');
                const pdfWrap = document.getElementById('mockup-media-pdf');
                const docWrap = document.getElementById('mockup-media-doc');

                const imgEl = document.getElementById('mockup-img-el');
                const videoEl = document.getElementById('mockup-video-el');
                const pdfTitle = document.getElementById('mockup-pdf-title');
                const pdfInfo = document.getElementById('mockup-pdf-info');
                const docTitle = document.getElementById('mockup-doc-title');
                const docInfo = document.getElementById('mockup-doc-info');

                // Hide all first
                if (imgWrap) imgWrap.classList.add('hidden');
                if (videoWrap) {
                    videoWrap.classList.add('hidden');
                    if (videoEl) videoEl.pause();
                }
                if (pdfWrap) pdfWrap.classList.add('hidden');
                if (docWrap) docWrap.classList.add('hidden');

                if (currentBrochure.type === 'image' && currentBrochure.url) {
                    if (imgWrap) imgWrap.classList.remove('hidden');
                    if (imgEl) imgEl.src = currentBrochure.url;
                } else if (currentBrochure.type === 'video' && currentBrochure.url) {
                    if (videoWrap) videoWrap.classList.remove('hidden');
                    if (videoEl) {
                        videoEl.src = currentBrochure.url;
                        videoEl.load();
                    }
                } else if (currentBrochure.type === 'pdf' && currentBrochure.url) {
                    if (pdfWrap) pdfWrap.classList.remove('hidden');
                    if (pdfTitle) pdfTitle.textContent = currentBrochure.name;
                    if (pdfInfo) pdfInfo.textContent = currentBrochure.sizeText;
                } else if (currentBrochure.type === 'document' && currentBrochure.url) {
                    if (docWrap) docWrap.classList.remove('hidden');
                    if (docTitle) docTitle.textContent = currentBrochure.name;
                    if (docInfo) docInfo.textContent = currentBrochure.sizeText;
                }
            }

            function openFullModeModal() {
                if (currentBrochure.type === 'none' && !currentBrochure.url) return;

                const modal = document.getElementById('full-mode-media-modal');
                if (!modal) return;
                if (modal.parentElement !== document.body) {
                    document.body.appendChild(modal);
                }

                const badge = document.getElementById('fullmode-badge');
                const filename = document.getElementById('fullmode-filename');
                const filesize = document.getElementById('fullmode-filesize');
                const downloadBtn = document.getElementById('fullmode-download-btn');

                const imgEl = document.getElementById('fullmode-image-el');
                const videoEl = document.getElementById('fullmode-video-el');
                const iframeEl = document.getElementById('fullmode-iframe-el');
                const fallbackEl = document.getElementById('fullmode-fallback-el');
                const fallbackLink = document.getElementById('fullmode-fallback-link');

                if (filename) filename.textContent = currentBrochure.name;
                if (filesize) filesize.textContent = currentBrochure.sizeText;

                if (downloadBtn) {
                    if (currentBrochure.url) {
                        downloadBtn.href = currentBrochure.url;
                        downloadBtn.download = currentBrochure.name;
                        downloadBtn.classList.remove('hidden');
                    } else {
                        downloadBtn.classList.add('hidden');
                    }
                }

                // Hide all viewers
                if (imgEl) imgEl.classList.add('hidden');
                if (videoEl) {
                    videoEl.classList.add('hidden');
                    videoEl.pause();
                }
                if (iframeEl) iframeEl.classList.add('hidden');
                if (fallbackEl) fallbackEl.classList.add('hidden');

                if (currentBrochure.type === 'image') {
                    if (badge) {
                        badge.textContent = '🖼️ HIGH-RES IMAGE';
                        badge.className = 'px-2.5 py-1 rounded-lg bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 font-bold text-xs';
                    }
                    if (imgEl && currentBrochure.url) {
                        imgEl.src = currentBrochure.url;
                        imgEl.classList.remove('hidden');
                    }
                } else if (currentBrochure.type === 'video') {
                    if (badge) {
                        badge.textContent = '🎬 VIDEO PLAYER';
                        badge.className = 'px-2.5 py-1 rounded-lg bg-purple-500/20 text-purple-300 border border-purple-500/30 font-bold text-xs';
                    }
                    if (videoEl && currentBrochure.url) {
                        videoEl.src = currentBrochure.url;
                        videoEl.classList.remove('hidden');
                        videoEl.play().catch(() => {});
                    }
                } else if (currentBrochure.type === 'pdf') {
                    if (badge) {
                        badge.textContent = '📄 PDF DOCUMENT VIEWER';
                        badge.className = 'px-2.5 py-1 rounded-lg bg-rose-500/20 text-rose-300 border border-rose-500/30 font-bold text-xs';
                    }
                    if (iframeEl && currentBrochure.url) {
                        iframeEl.src = currentBrochure.url;
                        iframeEl.classList.remove('hidden');
                    } else if (fallbackEl) {
                        fallbackEl.classList.remove('hidden');
                        if (fallbackLink) fallbackLink.href = currentBrochure.url || '#';
                    }
                } else {
                    if (badge) {
                        badge.textContent = '📁 DOCUMENT';
                        badge.className = 'px-2.5 py-1 rounded-lg bg-blue-500/20 text-blue-300 border border-blue-500/30 font-bold text-xs';
                    }
                    if (fallbackEl) {
                        fallbackEl.classList.remove('hidden');
                        if (fallbackLink) fallbackLink.href = currentBrochure.url || '#';
                    }
                }

                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.classList.add('overflow-hidden');
            }

            function closeFullModeModal() {
                const modal = document.getElementById('full-mode-media-modal');
                if (modal) {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }
                const videoEl = document.getElementById('fullmode-video-el');
                if (videoEl) {
                    videoEl.pause();
                }
                const iframeEl = document.getElementById('fullmode-iframe-el');
                if (iframeEl) {
                    iframeEl.src = '';
                }
                document.body.classList.remove('overflow-hidden');
            }

            // Close on Escape key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    closeFullModeModal();
                }
            });

            function scrollToStep(elementId) {
                const el = document.getElementById(elementId);
                if (el) {
                    const offset = 80;
                    const bodyRect = document.body.getBoundingClientRect().top;
                    const elementRect = el.getBoundingClientRect().top;
                    const elementPosition = elementRect - bodyRect;
                    const offsetPosition = elementPosition - offset;

                    window.scrollTo({
                        top: offsetPosition,
                        behavior: 'smooth'
                    });
                }
            }

            function switchAudienceTab(tab) {
                const fileTab = document.getElementById('tab-content-file');
                const manualTab = document.getElementById('tab-content-manual');
                const btnFile = document.getElementById('tab-btn-file');
                const btnManual = document.getElementById('tab-btn-manual');

                if (tab === 'file') {
                    fileTab.classList.remove('hidden');
                    manualTab.classList.add('hidden');
                    btnFile.className = 'border-b-2 border-purple-600 pb-2.5 px-4 text-xs font-extrabold text-purple-600 dark:text-purple-400 flex items-center gap-2 cursor-pointer transition';
                    btnManual.className = 'border-b-2 border-transparent pb-2.5 px-4 text-xs font-bold text-slate-500 hover:text-gray-600dark:text-slate-400 dark:hover:text-white transition cursor-pointer';
                } else {
                    fileTab.classList.add('hidden');
                    manualTab.classList.remove('hidden');
                    btnManual.className = 'border-b-2 border-purple-600 pb-2.5 px-4 text-xs font-extrabold text-purple-600 dark:text-purple-400 flex items-center gap-2 cursor-pointer transition';
                    btnFile.className = 'border-b-2 border-transparent pb-2.5 px-4 text-xs font-bold text-slate-500 hover:text-gray-600dark:text-slate-400 dark:hover:text-white transition cursor-pointer';
                }
            }

            function insertTag(tagText) {
                const nameInput = document.getElementById('name');
                if (nameInput) {
                    if (!nameInput.value.startsWith(tagText)) {
                        nameInput.value = tagText + nameInput.value;
                    }
                    updateLivePreview();
                }
            }

            function insertToken(token) {
                const textarea = document.getElementById('caption');
                if (!textarea) return;

                const start = textarea.selectionStart;
                const end = textarea.selectionEnd;
                const text = textarea.value;

                textarea.value = text.substring(0, start) + token + text.substring(end);
                textarea.selectionStart = textarea.selectionEnd = start + token.length;
                textarea.focus();
                updateLivePreview();
            }

            function wrapText(before, after) {
                const textarea = document.getElementById('caption');
                if (!textarea) return;

                const start = textarea.selectionStart;
                const end = textarea.selectionEnd;
                const text = textarea.value;
                const selectedText = text.substring(start, end) || 'text';

                textarea.value = text.substring(0, start) + before + selectedText + after + text.substring(end);
                textarea.selectionStart = start + before.length;
                textarea.selectionEnd = start + before.length + selectedText.length;
                textarea.focus();
                updateLivePreview();
            }

            function handleNumbersFileSelected(event) {
                const file = event.target.files[0];
                const label = document.getElementById('numbers-file-label');
                const pill = document.getElementById('audience-status-pill');

                if (file) {
                    label.innerHTML = `<strong>Selected:</strong> <span class="text-gray-900 dark:text-white font-extrabold">${file.name}</span> (${(file.size / 1024).toFixed(1)} KB)`;
                    if (pill) {
                        pill.innerHTML = `<span>✓ File Loaded: ${file.name}</span>`;
                    }
                }
            }

            function handleManualNumbersInput() {
                const val = document.getElementById('manual_numbers').value.trim();
                const pill = document.getElementById('audience-status-pill');
                if (val && pill) {
                    const lines = val.split(/\r?\n/).filter(function(l) { return l.trim().length > 0; });
                    pill.innerHTML = `<span><strong class="text-gray-900 dark:text-white">${lines.length}</strong> Number(s) Entered</span>`;
                }
            }

            function handleThrottleSliderChange(val) {
                document.getElementById('throttle_seconds').value = val;
                document.getElementById('delay-badge').textContent = `${val} Seconds`;
            }

            function handleThrottleInputChange(val) {
                const num = parseInt(val) || 20;
                document.getElementById('throttle_slider').value = Math.min(num, 120);
                document.getElementById('delay-badge').textContent = `${num} Seconds`;
            }

            function updateLivePreview() {
                const caption = document.getElementById('caption').value;
                const counter = document.getElementById('char-counter');
                const previewEl = document.getElementById('mockup-caption-text');

                if (counter) {
                    counter.textContent = `${caption.length} / 1024 characters`;
                }

                if (previewEl) {
                    let formatted = caption
                        .replace(/&/g, '&amp;')
                        .replace(/</g, '&lt;')
                        .replace(/>/g, '&gt;');

                    // Replace Tokens with mock values
                    formatted = formatted
                        .replace(/\{\{First Name\}\}/g, 'Siddharth')
                        .replace(/\{\{Project Name\}\}/g, 'Godrej Sky Terraces')
                        .replace(/\{\{Agent Phone\}\}/g, '+91 98765 43210');

                    // Parse WhatsApp markdown (*bold*, _italic_, ~strike~)
                    formatted = formatted
                        .replace(/\*([^\*]+)\*/g, '<strong>$1</strong>')
                        .replace(/_([^_]+)_/g, '<em>$1</em>')
                        .replace(/~([^~]+)~/g, '<del>$1</del>');

                    previewEl.innerHTML = formatted;
                }
            }

            document.addEventListener('DOMContentLoaded', () => {
                const modal = document.getElementById('full-mode-media-modal');
                if (modal && modal.parentElement !== document.body) {
                    document.body.appendChild(modal);
                }

                // Setup Drag & Drop on brochure dropzone
                const dropzone = document.getElementById('brochure-dropzone');
                const fileInput = document.getElementById('brochure_file');

                if (dropzone && fileInput) {
                    ['dragenter', 'dragover'].forEach(name => {
                        dropzone.addEventListener(name, (e) => {
                            e.preventDefault();
                            e.stopPropagation();
                            dropzone.classList.add('border-purple-600', 'bg-purple-100/40', 'dark:bg-purple-950/40');
                        });
                    });

                    ['dragleave', 'drop'].forEach(name => {
                        dropzone.addEventListener(name, (e) => {
                            e.preventDefault();
                            e.stopPropagation();
                            dropzone.classList.remove('border-purple-600', 'bg-purple-100/40', 'dark:bg-purple-950/40');
                        });
                    });

                    dropzone.addEventListener('drop', (e) => {
                        if (e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files.length > 0) {
                            fileInput.files = e.dataTransfer.files;
                            handleBrochureFileSelected({ target: fileInput });
                        }
                    });
                }

                updateLivePreview();
                updateMediaUI();
                const now = new Date();
                const timeStr = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                const ts = document.getElementById('mockup-timestamp');
                if (ts) ts.textContent = timeStr;
            });
        </script>
    @endPushOnce
</x-admin::layouts>