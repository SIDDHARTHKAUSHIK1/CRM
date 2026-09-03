<x-admin::layouts>
    <x-slot:title>
        Link WhatsApp Session (QR Code)
    </x-slot>

    <div class="flex flex-col gap-6">
        <!-- Header -->
        <div class="scroll-reactive-sticky sticky top-[60px] z-[1000] flex flex-wrap items-center justify-between gap-4 rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm shadow-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
            <div class="flex flex-col gap-1">
                <x-admin::breadcrumbs name="whatsapp.gateway" />
                <div class="text-xl font-bold dark:text-white">
                    Link WhatsApp Account
                </div>
            </div>

            <div class="flex items-center gap-2.5">
                <a
                    href="{{ route('admin.whatsapp.index') }}"
                    class="secondary-button"
                >
                    &larr; Back to Broadcasts
                </a>
            </div>
        </div>

        <v-whatsapp-gateway
            :initial-status='@json($status)'
            :initial-qr='@json($qrData)'
        ></v-whatsapp-gateway>
    </div>

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-whatsapp-gateway-template"
        >
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <!-- Left: QR Code / Connection Box -->
                <div class="flex flex-col items-center justify-center rounded-lg border border-gray-300 bg-white p-5 text-center shadow-sm dark:border-gray-800 dark:bg-gray-900">
                    <!-- State 1: Connected -->
                    <template v-if="connected">
                        <div class="flex flex-col items-center py-6">
                            <div class="flex h-20 w-20 items-center justify-center rounded-full bg-emerald-100 text-emerald-600 dark:bg-emerald-900/50 dark:text-emerald-300 mb-4">
                                <span class="icon-done text-4xl"></span>
                            </div>

                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                                WhatsApp is Linked &amp; Ready!
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                Connected phone number: <strong class="text-emerald-600 dark:text-emerald-400" v-text="'+' + (phoneNumber || 'Unknown')"></strong>
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5" v-show="pushName" v-text="'Profile: ' + pushName"></p>

                            <div class="mt-8 flex gap-4">
                                <a
                                    href="{{ route('admin.whatsapp.create') }}"
                                    class="primary-button"
                                >
                                    <span class="icon-add text-sm"></span>
                                    New Broadcast
                                </a>

                                <form
                                    action="{{ route('admin.whatsapp.gateway.logout') }}"
                                    method="POST"
                                    onsubmit="return confirm('Are you sure you want to disconnect this WhatsApp session?');"
                                >
                                    @csrf
                                    <button
                                        type="submit"
                                        class="secondary-button text-red-600 dark:text-red-400 dark:border-red-800"
                                    >
                                        Unlink / Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </template>

                    <!-- State 2: QR Code Ready -->
                    <template v-if="!connected && qrImage">
                        <div class="flex flex-col items-center py-4">
                            <div class="rounded-xl border-4 border-emerald-500 bg-white p-3 shadow-lg mb-4">
                                <img
                                    :src="qrImage"
                                    alt="WhatsApp Login QR Code"
                                    class="h-64 w-64 object-contain"
                                >
                            </div>

                            <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">
                                Scan this QR code with WhatsApp on your phone
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                QR updates automatically. Listening for authentication...
                            </p>

                            <button
                                type="button"
                                @click="fetchQr()"
                                class="secondary-button mt-4"
                            >
                                <span class="icon-refresh text-xs"></span>
                                Refresh QR Code
                            </button>
                        </div>
                    </template>

                    <!-- State 3: Loading / Offline -->
                    <template v-if="!connected && !qrImage">
                        <div class="flex flex-col items-center py-10">
                            <div class="flex h-16 w-16 items-center justify-center rounded-full bg-amber-100 text-amber-600 dark:bg-amber-900/40 mb-4 animate-spin">
                                <span class="icon-refresh text-2xl"></span>
                            </div>
                            <h4 class="font-bold text-gray-800 dark:text-gray-200">
                                Connecting to WhatsApp Gateway...
                            </h4>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 max-w-sm">
                                If this takes more than a few seconds, verify that the Node.js service is running on <code class="text-brandColor">127.0.0.1:3001</code>.
                            </p>
                            <button
                                type="button"
                                @click="fetchStatus()"
                                class="secondary-button mt-4"
                            >
                                Retry Connection
                            </button>
                        </div>
                    </template>
                </div>

                <!-- Right: Instructions & Safeguards -->
                <div class="flex flex-col gap-6">
                    <!-- Step by step instructions -->
                    <div class="rounded-lg border border-gray-300 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                        <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <span class="icon-whatsapp text-emerald-500"></span>
                            How to Link WhatsApp
                        </h3>

                        <ol class="space-y-4 text-sm text-gray-600 dark:text-gray-300 list-decimal list-inside">
                            <li class="leading-relaxed">
                                Open <strong>WhatsApp</strong> on your mobile phone.
                            </li>
                            <li class="leading-relaxed">
                                Tap <strong>Menu (⋮)</strong> on Android or <strong>Settings</strong> on iPhone.
                            </li>
                            <li class="leading-relaxed">
                                Tap <strong>Linked Devices</strong>, then tap <strong>Link a Device</strong>.
                            </li>
                            <li class="leading-relaxed">
                                Point your phone camera at the QR code displayed on this screen.
                            </li>
                            <li class="leading-relaxed">
                                Once scanned, this page will automatically update to <strong>Connected</strong>.
                            </li>
                        </ol>
                    </div>

                    <!-- Safeguards Card -->
                    <div class="rounded-lg border border-amber-200 bg-amber-50/70 p-5 text-xs text-amber-900 shadow-sm dark:border-amber-800 dark:bg-amber-950/30 dark:text-amber-200">
                        <h4 class="font-bold text-sm mb-2 flex items-center gap-1.5 text-amber-800 dark:text-amber-300">
                            <span class="icon-alert text-base"></span>
                            Important Safety &amp; Multi-Device Notes
                        </h4>
                        <ul class="space-y-2 list-disc list-inside opacity-95">
                            <li>The session is securely stored locally in the isolated microservice and will survive system restarts.</li>
                            <li>Do not scan with personal accounts that have no prior business interaction history. Use an established WhatsApp number.</li>
                            <li>Keep the default message throttle (15–30s) enabled when broadcasting to maintain healthy account standing.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </script>

        <script type="module">
            app.component('v-whatsapp-gateway', {
                template: '#v-whatsapp-gateway-template',

                props: {
                    initialStatus: {
                        type: Object,
                        default: () => ({})
                    },
                    initialQr: {
                        type: Object,
                        default: () => ({})
                    }
                },

                data() {
                    return {
                        connected: !!(this.initialStatus && this.initialStatus.connected),
                        phoneNumber: (this.initialStatus && this.initialStatus.number) ? this.initialStatus.number : '',
                        pushName: (this.initialStatus && this.initialStatus.pushName) ? this.initialStatus.pushName : '',
                        qrImage: (this.initialQr && this.initialQr.qr) ? this.initialQr.qr : '',
                        pollTimer: null,
                    };
                },

                mounted() {
                    this.fetchStatus();
                    if (!this.connected) {
                        this.fetchQr();
                    }
                    this.pollTimer = setInterval(() => {
                        this.fetchStatus();
                        if (!this.connected) {
                            this.fetchQr();
                        }
                    }, 3000);
                },

                beforeUnmount() {
                    if (this.pollTimer) {
                        clearInterval(this.pollTimer);
                    }
                },

                methods: {
                    async fetchStatus() {
                        try {
                            const res = await fetch('{{ route('admin.whatsapp.gateway.status') }}');
                            if (!res.ok) return;
                            const data = await res.json();
                            this.connected = !!data.connected;
                            this.phoneNumber = data.number || '';
                            this.pushName = data.pushName || '';
                        } catch (e) {
                            console.error('Failed to fetch gateway status:', e);
                        }
                    },

                    async fetchQr() {
                        try {
                            const res = await fetch('{{ route('admin.whatsapp.gateway.qr') }}');
                            if (!res.ok) return;
                            const data = await res.json();
                            if (data.qr) {
                                this.qrImage = data.qr;
                            }
                        } catch (e) {
                            console.error('Failed to fetch QR:', e);
                        }
                    }
                }
            });
        </script>
    @endPushOnce
</x-admin::layouts>
