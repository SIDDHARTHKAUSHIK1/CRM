<?php

return [
    /**
     * URL of the standalone Node.js WhatsApp Gateway service.
     */
    'gateway_url' => env('WHATSAPP_GATEWAY_URL', 'http://127.0.0.1:3001'),

    /**
     * Secret key for X-Gateway-Key header validation.
     */
    'gateway_key' => env('WHATSAPP_GATEWAY_KEY', ''),

    /**
     * Default country code to prepend when numbers have 10 digits without prefix (e.g. 91 for India).
     */
    'default_country_code' => env('WHATSAPP_DEFAULT_COUNTRY_CODE', '91'),

    /**
     * Default safety delay (in seconds) between consecutive messages.
     * Minimum 5 seconds recommended; standard 15-30 seconds.
     */
    'default_throttle_seconds' => (int) env('WHATSAPP_DEFAULT_THROTTLE_SECONDS', 20),

    /**
     * Maximum brochure / media file size in megabytes.
     */
    'max_media_mb' => (int) env('WHATSAPP_MAX_MEDIA_MB', 16),
];
