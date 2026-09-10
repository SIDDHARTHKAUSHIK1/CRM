<!DOCTYPE html>

<html
    class="{{ request()->cookie('dark_mode') ? 'dark' : '' }}"
    lang="{{ app()->getLocale() }}"
    dir="{{ in_array(app()->getLocale(), ['fa', 'ar']) ? 'rtl' : 'ltr' }}"
>

<head>

    {!! view_render_event('admin.layout.head.before') !!}

    <title>{{ $title }}</title>

    <meta charset="UTF-8">

    <meta
        http-equiv="X-UA-Compatible"
        content="IE=edge"
    >
    <meta
        http-equiv="content-language"
        content="{{ app()->getLocale() }}"
    >

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >
    <meta
        name="base-url"
        content="{{ url()->to('/') }}"
    >
    <meta
        name="currency"
        content="{{
            json_encode([
                'code' => config('app.currency'),
                'symbol' => core()->currencySymbol(config('app.currency'))])
            }}
        "
    >

    @stack('meta')

    {{
        vite()->set(['src/Resources/assets/css/app.css', 'src/Resources/assets/js/app.js'])
    }}

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap"
        rel="stylesheet"
    />



    @if ($favicon = core()->getConfigData('general.general.admin_logo.favicon_image'))
        <link
            type="image/x-icon"
            href="{{ Storage::url($favicon) }}"
            rel="icon"
        >
        <link
            type="image/x-icon"
            href="{{ Storage::url($favicon) }}"
            rel="shortcut icon"
        >
    @else
        <link
            type="image/svg+xml"
            href="{{ vite()->asset('images/favicon.svg') }}"
            rel="icon"
        />
        <link
            type="image/x-icon"
            href="{{ vite()->asset('images/favicon.ico') }}"
            rel="shortcut icon"
        />
    @endif

    @php
        $brandColor = core()->getConfigData('general.settings.menu_color.brand_color') ?? '#7C3AED';
    @endphp

    @stack('styles')

    <style>
        :root {
            --brand-color: {{ $brandColor }};
        }

        {!! core()->getConfigData('general.content.custom_scripts.custom_css') !!}

        /* Sidebar & Layout Geometry (Guaranteed Zero Overlap) */
        @media (min-width: 1024px) {
            #admin-sidebar {
                width: 240px !important;
                position: fixed !important;
                top: 0 !important;
                bottom: 0 !important;
                left: 0 !important;
                height: 100vh !important;
                z-index: 10003 !important;
                overflow: visible !important;
            }
            .sidebar-collapsed #admin-sidebar {
                width: 70px !important;
            }
            .admin-header-bar {
                margin-left: 240px !important;
                width: calc(100% - 240px) !important;
                transition: all 0.15s ease !important;
                height: 60px !important;
                min-height: 60px !important;
                max-height: 60px !important;
                box-sizing: border-box !important;
                border-bottom: 1px solid rgba(226, 232, 240, 0.9) !important;
            }
            .sidebar-collapsed .admin-header-bar {
                margin-left: 70px !important;
                width: calc(100% - 70px) !important;
            }
            .sidebar-brand-header {
                height: 60px !important;
                min-height: 60px !important;
                max-height: 60px !important;
                box-sizing: border-box !important;
                border-bottom: 1px solid rgba(226, 232, 240, 0.9) !important;
            }
            .admin-main-content {
                margin-left: 240px !important;
                transition: all 0.15s ease !important;
            }
            .sidebar-collapsed .admin-main-content {
                margin-left: 70px !important;
            }
            .mobile-only-logo {
                display: none !important;
            }
        }
        @media (max-width: 1023px) {
            #admin-sidebar {
                display: none !important;
            }
            .admin-header-bar {
                margin-left: 0 !important;
                width: 100% !important;
                height: 60px !important;
                min-height: 60px !important;
                max-height: 60px !important;
                box-sizing: border-box !important;
                border-bottom: 1px solid rgba(226, 232, 240, 0.9) !important;
            }
            .admin-main-content {
                margin-left: 0 !important;
            }
            .mobile-only-logo {
                display: flex !important;
            }
        }

        @media (min-width: 1024px) {
            .admin-impersonation-banner {
                margin-left: 240px !important;
                width: calc(100% - 240px) !important;
            }
            .sidebar-collapsed .admin-impersonation-banner {
                margin-left: 70px !important;
                width: calc(100% - 70px) !important;
            }
        }
        @media (max-width: 1023px) {
            .admin-impersonation-banner {
                margin-left: 0 !important;
                width: 100% !important;
            }
        }

        /* Scrollable nav menu with hidden scrollbars */
        .sidebar-nav-scroll {
            overflow-y: auto !important;
            overflow-x: hidden !important;
            scrollbar-width: none !important; /* Firefox */
            -ms-overflow-style: none !important; /* IE and Edge */
        }
        .sidebar-nav-scroll::-webkit-scrollbar {
            display: none !important; /* Chrome, Safari, Opera */
        }

        /* Submenu Flyout Hover Support */
        .sidebar-submenu-flyout {
            display: none;
            position: fixed !important;
            left: 235px !important;
            z-index: 10005 !important;
            padding-left: 12px !important;
        }
        .sidebar-collapsed .sidebar-submenu-flyout {
            left: 65px !important;
        }
        .sidebar-nav-item:hover .sidebar-submenu-flyout,
        .sidebar-submenu-flyout.is-active {
            display: flex !important;
        }

        /* Sidebar Custom Color Utility Classes */
        .sidebar-brand-re {
            background-color: #5b5df4 !important;
            color: #ffffff !important;
            box-shadow: 0 4px 12px rgba(91, 93, 244, 0.28) !important;
        }
        .sidebar-brand-title {
            color: #0f172a !important;
            font-weight: 800 !important;
        }
        .dark .sidebar-brand-title {
            color: #ffffff !important;
        }

        .nav-item-title {
            color: #1e293b !important;
            font-weight: 700 !important;
            letter-spacing: -0.01em !important;
        }
        .dark .nav-item-title {
            color: #f8fafc !important;
            font-weight: 700 !important;
        }

        .nav-pill-active {
            background-color: #f0eeff !important;
        }
        .dark .nav-pill-active {
            background-color: rgba(91, 93, 244, 0.22) !important;
        }
        .nav-pill-active .nav-icon-container {
            background-color: #e0dbff !important;
            color: #5b5df4 !important;
        }
        .dark .nav-pill-active .nav-icon-container {
            background-color: rgba(91, 93, 244, 0.38) !important;
            color: #c7d2fe !important;
        }
        .nav-pill-active .nav-item-title {
            color: #5b5df4 !important;
            font-weight: 700 !important;
        }
        .dark .nav-pill-active .nav-item-title {
            color: #c7d2fe !important;
            font-weight: 700 !important;
        }
        .nav-pill-active .nav-chevron-icon {
            color: #818cf8 !important;
        }
        .dark .nav-pill-active .nav-chevron-icon {
            color: #c7d2fe !important;
        }

        .sidebar-nav-item:hover > a {
            background-color: #f0eeff !important;
        }
        .dark .sidebar-nav-item:hover > a {
            background-color: rgba(91, 93, 244, 0.2) !important;
        }
        .sidebar-nav-item:hover .nav-icon-container {
            background-color: #e0dbff !important;
            color: #5b5df4 !important;
        }
        .dark .sidebar-nav-item:hover .nav-icon-container {
            background-color: rgba(91, 93, 244, 0.38) !important;
            color: #c7d2fe !important;
        }
        .sidebar-nav-item:hover .nav-item-title {
            color: #5b5df4 !important;
            font-weight: 700 !important;
        }
        .dark .sidebar-nav-item:hover .nav-item-title {
            color: #c7d2fe !important;
            font-weight: 700 !important;
        }
        .sidebar-nav-item:hover .nav-chevron-icon {
            color: #818cf8 !important;
        }
        .dark .sidebar-nav-item:hover .nav-chevron-icon {
            color: #c7d2fe !important;
        }

        /* Top Toggle Button Display Rules */
        .sidebar-collapse-expanded-only {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }
        .sidebar-collapse-collapsed-only {
            display: none !important;
        }
        .sidebar-collapsed .sidebar-collapse-expanded-only {
            display: none !important;
        }
        .sidebar-collapsed .sidebar-collapse-collapsed-only {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            width: 100% !important;
            padding: 8px 0 4px 0 !important;
        }

        /* Collapsed Sidebar Single-Squircle Geometry (Strictly Centered & Clean) */
        .sidebar-collapsed #admin-sidebar .sidebar-nav-scroll nav {
            padding-left: 0 !important;
            padding-right: 0 !important;
            width: 100% !important;
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            justify-content: center !important;
        }
        .sidebar-collapsed .sidebar-nav-item {
            width: 100% !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            margin: 0 auto !important;
        }
        .sidebar-collapsed .sidebar-nav-item > a {
            width: 44px !important;
            height: 44px !important;
            padding: 0 !important;
            margin: 0 auto !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            border-radius: 1rem !important;
            background-color: transparent !important;
            box-shadow: none !important;
            border: none !important;
        }
        .sidebar-collapsed .sidebar-nav-item > a .nav-item-left {
            width: 44px !important;
            height: 44px !important;
            padding: 0 !important;
            margin: 0 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 0 !important;
        }
        .sidebar-collapsed .nav-pill-active,
        .sidebar-collapsed .sidebar-nav-item:hover > a {
            background-color: transparent !important;
            box-shadow: none !important;
            border: none !important;
        }
        .sidebar-collapsed .nav-icon-container {
            width: 44px !important;
            height: 44px !important;
            border-radius: 1rem !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            margin: 0 auto !important;
            flex-shrink: 0 !important;
        }
        .sidebar-collapsed .nav-icon-container svg {
            width: 20px !important;
            height: 20px !important;
            margin: auto !important;
            display: block !important;
            flex-shrink: 0 !important;
        }
        .sidebar-collapsed .sidebar-brand-re {
            margin: 0 auto !important;
        }

        .need-help-icon-box {
            background-color: #ffffff !important;
            color: #5b5df4 !important;
        }
        .sidebar-collapsed .need-help-card {
            width: 44px !important;
            height: 44px !important;
            padding: 0 !important;
            margin: 0 auto !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            border-radius: 1rem !important;
        }
        .sidebar-collapsed .need-help-card .need-help-left {
            width: 44px !important;
            height: 44px !important;
            padding: 0 !important;
            margin: 0 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 0 !important;
        }
        .sidebar-collapsed .need-help-icon-box {
            background-color: transparent !important;
            box-shadow: none !important;
            width: 44px !important;
            height: 44px !important;
            border-radius: 1rem !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            margin: 0 auto !important;
        }
        .sidebar-collapsed .need-help-icon-box svg {
            width: 20px !important;
            height: 20px !important;
            margin: auto !important;
            display: block !important;
        }

        .flyout-card-shadow {
            box-shadow: 0 16px 40px -6px rgba(0, 0, 0, 0.09), 0 4px 16px -2px rgba(0, 0, 0, 0.04) !important;
        }

        /* Automatic Text Auto-Fit & Box Overflow Containment Engine */
        .auto-fit-text, [data-auto-fit] {
            display: inline-block;
            max-width: 100% !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
            white-space: nowrap !important;
            vertical-align: middle;
            transition: font-size 0.06s ease-out;
        }

        .auto-fit-container {
            min-width: 0 !important;
            max-width: 100% !important;
            overflow: hidden !important;
        }

        .kpi-badge-truncate {
            max-width: 100% !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
            white-space: nowrap !important;
        }

        @keyframes tooltipFadeIn {
            from {
                opacity: 0;
                transform: translateY(4px) scale(0.96);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        .animate-fadeIn {
            animation: tooltipFadeIn 0.18s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        /* =========================================================
           Dark Mode Background Theme: #010108 (Midnight Obsidian)
           ========================================================= */
        html.dark,
        html.dark body,
        .dark body,
        .dark #app {
            background-color: #010108 !important;
            color: #f3f4f6 !important;
        }

        .dark .admin-main-content,
        .dark .admin-main-content > div {
            background-color: #010108 !important;
        }

        .dark #admin-sidebar,
        .dark .admin-header-bar,
        .dark .sidebar-brand-header {
            background-color: #070711 !important;
            border-color: #1a1a2c !important;
        }

        /* Standard Cards, Panels, KPI Cards, and Widgets in Dark Mode */
        .dark .bg-gray-900,
        .dark .bg-gray-950,
        .dark .dark\:bg-gray-900,
        .dark .dark\:bg-gray-950,
        .dark [class*="dark:bg-gray-900"],
        .dark [class*="dark:bg-gray-950"],
        .dark .card,
        .dark .box-shadow {
            background-color: #0c0c18 !important;
            border-color: #1b1b2e !important;
        }

        /* Secondary pills & inner containers on #010108 */
        .dark .dark\:bg-gray-800,
        .dark [class*="dark:bg-gray-800"] {
            background-color: #141424 !important;
            border-color: #23233c !important;
        }

        .dark .border-gray-800,
        .dark .dark\:border-gray-800,
        .dark [class*="dark:border-gray-800"] {
            border-color: #1b1b2e !important;
        }

        /* Dark mode inputs, textareas, selects */
        .dark input:not([type="checkbox"]):not([type="radio"]):not([type="color"]),
        .dark select,
        .dark textarea {
            background-color: #0c0c18 !important;
            border-color: #23233c !important;
            color: #ffffff !important;
        }

        /* Flyout menus & modals in dark mode */
        .dark .sidebar-submenu-flyout > div,
        .dark .flyout-card-shadow,
        .dark [class*="animate-modal-in"] {
            background-color: #0c0c18 !important;
            border-color: #1e1e34 !important;
        }
    </style>

    {!! view_render_event('admin.layout.head.after') !!}
</head>

<body class="h-full font-inter dark:bg-[#010108]">
    {!! view_render_event('admin.layout.body.before') !!}

    <div
        id="app"
        class="h-full group/container {{ request()->cookie('sidebar_collapsed') ?? 0 ? 'sidebar-collapsed' : 'sidebar-not-collapsed' }}"
        :class="{'sidebar-submenu-active': isMenuActive && activeSubmenu}"
        ref="appLayout"
    >
        <!-- Flash Message Blade Component -->
        <x-admin::flash-group />

        <!-- Confirm Modal Blade Component -->
        <x-admin::modal.confirm />

        {!! view_render_event('admin.layout.content.before') !!}

        <!-- Page Sidebar Blade Component -->
        <x-admin::layouts.sidebar.desktop />

        <!-- Page Header Blade Component -->
        <x-admin::layouts.header />

        @if (session()->has('impersonator_id'))
            <div class="admin-impersonation-banner sticky top-[60px] z-[10000] flex items-center justify-between border-b border-amber-500 bg-amber-400 px-4 py-2 text-slate-950 shadow-md transition-all duration-150">
                <div class="flex items-center gap-2">
                    <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-black text-amber-300 text-xs font-black">!</span>
                    <span class="text-sm font-extrabold tracking-tight">
                        @lang('admin::app.admin-panel.impersonate.banner_text', ['name' => auth()->guard('user')->user()?->name])
                    </span>
                </div>
                <form method="POST" action="{{ route('admin.panel.employees.impersonate_stop') }}" class="m-0">
                    @csrf
                    <button type="submit" class="inline-flex cursor-pointer items-center gap-1.5 rounded-lg bg-black px-3.5 py-1.5 text-xs font-bold text-white shadow-xs transition hover:bg-slate-800">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                        <span>@lang('admin::app.admin-panel.impersonate.return_btn')</span>
                    </button>
                </form>
            </div>
        @endif

        @php
            /**
             * The bar is fixed, so the content below reserves space for it.
             * When it is switched off that reserve goes away too, otherwise
             * every page keeps a strip of dead space at the bottom.
             */
            $showPoweredBy = (bool) core()->getConfigData('general.settings.footer.show');
        @endphp

        <div class="admin-main-content flex min-h-[calc(100vh-62px)] max-w-full flex-1 flex-col bg-gray-100 pt-4 lg:pt-5 transition-all duration-300 dark:bg-gray-950">
            <!-- Page Content Blade Component -->
            <div class="px-4 lg:px-6 {{ $showPoweredBy ? 'pb-[72px]' : 'pb-6' }} transition-all duration-300">
                {{ $slot }}
            </div>

            @if ($showPoweredBy)
                <!-- Powered By -->
                <div class="fixed bottom-0 left-0 right-0 z-1">
                    <div class="border-t bg-white py-5 text-center text-sm font-normal dark:border-gray-800 dark:bg-gray-900 dark:text-white max-md:py-3">
                        <p>{!! core()->getConfigData('general.settings.footer.label') !!}</p>
                    </div>
                </div>
            @endif
        </div>

        {!! view_render_event('admin.layout.content.after') !!}
    </div>

    {!! view_render_event('admin.layout.body.after') !!}

    @stack('scripts')

    {!! view_render_event('admin.layout.vue-app-mount.before') !!}

    <script>
        /**
         * Universal Auto-Fit Text Engine
         * Automatically adjusts font sizes down or up when the user zooms in, zooms out,
         * resizes window, toggles sidebar, or when dynamic data loads, ensuring zero text overflow.
         */
        window.__autoFitTextElements = function() {
            requestAnimationFrame(function() {
                var elements = document.querySelectorAll('.auto-fit-text, [data-auto-fit]');
                elements.forEach(function(el) {
                    if (!el.dataset.origFontSize) {
                        var computed = window.getComputedStyle(el);
                        var currentPx = parseFloat(computed.fontSize);
                        el.dataset.origFontSize = currentPx || 24;
                        if (!el.dataset.minFontSize) {
                            el.dataset.minFontSize = 10;
                        }
                    }

                    var maxFontSize = parseFloat(el.dataset.maxFontSize || el.dataset.origFontSize);
                    var minFontSize = parseFloat(el.dataset.minFontSize || 10);
                    var container = el.parentElement;
                    if (!container) return;

                    // Temporarily test at maximum font size to calculate required scaling ratio
                    el.style.fontSize = maxFontSize + 'px';

                    var availableWidth = container.clientWidth;
                    if (availableWidth <= 0) return;

                    var scrollWidth = el.scrollWidth;
                    if (scrollWidth > availableWidth) {
                        var scaleRatio = (availableWidth / scrollWidth) * 0.96;
                        var calculatedSize = Math.max(minFontSize, Math.min(maxFontSize, maxFontSize * scaleRatio));
                        el.style.fontSize = calculatedSize.toFixed(1) + 'px';
                    } else {
                        el.style.fontSize = maxFontSize + 'px';
                    }
                });
            });
        };

        window.addEventListener('resize', window.__autoFitTextElements, { passive: true });
        window.addEventListener('orientationchange', window.__autoFitTextElements, { passive: true });
        if (window.visualViewport) {
            window.visualViewport.addEventListener('resize', window.__autoFitTextElements, { passive: true });
        }

        document.addEventListener('DOMContentLoaded', function() {
            window.__autoFitTextElements();
            if (window.MutationObserver && document.body) {
                var observer = new MutationObserver(function() {
                    window.__autoFitTextElements();
                });
                observer.observe(document.body, { childList: true, subtree: true, characterData: true });
            }
        });

        /**
         * Load event, the purpose of using the event is to mount the application
         * after all of our `Vue` components which is present in blade file have
         * been registered in the app. No matter what `app.mount()` should be
         * called in the last.
         */
        window.addEventListener("load", function(event) {
            app.mount("#app");
            window.__autoFitTextElements();
            setTimeout(window.__autoFitTextElements, 250);
            setTimeout(window.__autoFitTextElements, 700);
        });
    </script>

    {!! view_render_event('admin.layout.vue-app-mount.after') !!}
</body>

</html>
