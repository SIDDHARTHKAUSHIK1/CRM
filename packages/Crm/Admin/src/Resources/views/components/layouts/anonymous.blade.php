<!DOCTYPE html>

<html
    lang="{{ app()->getLocale() }}"
    dir="{{ in_array(app()->getLocale(), ['fa', 'ar']) ? 'rtl' : 'ltr' }}"
>

<head>
    <title>{{ $title ?? '' }}</title>

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
        content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover"
    >
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="format-detection" content="telephone=no">
    <meta name="theme-color" content="#ffffff" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#070711" media="(prefers-color-scheme: dark)">

    <script>
        (function() {
            function updateDeviceMetrics() {
                var w = window.innerWidth || (document.documentElement ? document.documentElement.clientWidth : 375);
                var h = window.innerHeight || (document.documentElement ? document.documentElement.clientHeight : 667);
                var doc = document.documentElement;
                if (!doc) return;

                doc.classList.remove('is-mobile', 'is-tablet', 'is-desktop', 'is-ultrawide');
                if (w < 768) {
                    doc.classList.add('is-mobile');
                } else if (w < 1024) {
                    doc.classList.add('is-tablet');
                } else if (w < 1440) {
                    doc.classList.add('is-desktop');
                } else {
                    doc.classList.add('is-ultrawide');
                }

                doc.classList.remove('orientation-portrait', 'orientation-landscape');
                doc.classList.add(h >= w ? 'orientation-portrait' : 'orientation-landscape');

                var isStandalone = (window.matchMedia && window.matchMedia('(display-mode: standalone)').matches) || window.navigator.standalone === true;
                doc.classList.toggle('is-standalone', isStandalone);
                doc.classList.add('is-edge-to-edge');

                doc.style.setProperty('--device-width', w + 'px');
                doc.style.setProperty('--device-height', h + 'px');
                doc.style.setProperty('--app-height', h + 'px');
                doc.style.setProperty('--mobile-top-gap', w < 768 ? '16px' : (w < 1024 ? '20px' : '24px'));
            }
            updateDeviceMetrics();
        })();
    </script>
    <meta
        name="base-url"
        content="{{ url()->to('/') }}"
    >
    <meta
        name="currency-code"
        {{-- content="{{ core()->getCurrentCurrencyCode() }}" --}}
    >

    @stack('meta')

    {{
        vite()->set(['src/Resources/assets/css/app.css', 'src/Resources/assets/js/app.js'])
    }}

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    />

    <link
        href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&display=swap"
        rel="stylesheet"
    />

    @if ($favicon = core()->getConfigData('general.design.admin_logo.favicon'))
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
            --safe-area-top: env(safe-area-inset-top, 0px);
            --safe-area-bottom: env(safe-area-inset-bottom, 0px);
            --safe-area-left: env(safe-area-inset-left, 0px);
            --safe-area-right: env(safe-area-inset-right, 0px);
            --mobile-top-gap: 16px;
            --app-height: 100vh;
        }

        @supports (height: 100dvh) {
            :root {
                --app-height: 100dvh;
            }
        }

        html, body {
            max-width: 100vw;
            overflow-x: hidden;
            -webkit-text-size-adjust: 100%;
            touch-action: manipulation;
        }

        @media (max-width: 767px) {
            body {
                padding-top: max(env(safe-area-inset-top, 0px), 16px);
                padding-bottom: max(env(safe-area-inset-bottom, 0px), 16px);
                padding-left: max(env(safe-area-inset-left, 0px), 12px);
                padding-right: max(env(safe-area-inset-right, 0px), 12px);
            }
        }

        {!! core()->getConfigData('general.content.custom_scripts.custom_css') !!}
    </style>

    {!! view_render_event('admin.layout.head') !!}
</head>

<body>
    {!! view_render_event('admin.layout.body.before') !!}

    <div id="app">
        <!-- Flash Message Blade Component -->
        <x-admin::flash-group />

        {!! view_render_event('admin.layout.content.before') !!}

        <!-- Page Content Blade Component -->
        {{ $slot }}

        {!! view_render_event('admin.layout.content.after') !!}
    </div>

    {!! view_render_event('admin.layout.body.after') !!}

    @stack('scripts')

    {!! view_render_event('admin.layout.vue-app-mount.before') !!}

    <script>
        window.detectDeviceAlignment = function() {
            var width = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
            var height = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
            var docEl = document.documentElement;
            if (!docEl) return;

            var isMobile = width < 768;
            var isTablet = width >= 768 && width < 1024;
            var isDesktop = width >= 1024 && width < 1440;
            var isUltrawide = width >= 1440;

            docEl.classList.toggle('is-mobile', isMobile);
            docEl.classList.toggle('is-tablet', isTablet);
            docEl.classList.toggle('is-desktop', isDesktop);
            docEl.classList.toggle('is-ultrawide', isUltrawide);
            docEl.classList.toggle('orientation-portrait', height >= width);
            docEl.classList.toggle('orientation-landscape', width > height);

            var isStandalone = (window.matchMedia && window.matchMedia('(display-mode: standalone)').matches) || window.navigator.standalone === true;
            docEl.classList.toggle('is-standalone', isStandalone);
            docEl.classList.add('is-edge-to-edge');

            var vh = height * 0.01;
            docEl.style.setProperty('--vh', vh + 'px');
            docEl.style.setProperty('--device-width', width + 'px');
            docEl.style.setProperty('--device-height', height + 'px');
            docEl.style.setProperty('--app-height', height + 'px');
            docEl.style.setProperty('--mobile-top-gap', isMobile ? '16px' : (isTablet ? '20px' : '24px'));
        };

        window.initEdgeToEdge = function() {
            window.detectDeviceAlignment();
        };

        window.addEventListener('resize', window.detectDeviceAlignment, { passive: true });
        window.addEventListener('orientationchange', function() {
            window.detectDeviceAlignment();
            setTimeout(window.detectDeviceAlignment, 150);
        }, { passive: true });

        /**
         * Load event, the purpose of using the event is to mount the application
         * after all of our `Vue` components which is present in blade file have
         * been registered in the app. No matter what `app.mount()` should be
         * called in the last.
         */
        window.addEventListener("load", function(event) {
            app.mount("#app");
            window.initEdgeToEdge();
        });
    </script>

    {!! view_render_event('admin.layout.vue-app-mount.after') !!}

    <script type="text/javascript">
        {!! core()->getConfigData('general.content.custom_scripts.custom_javascript') !!}
    </script>
</body>

</html>
