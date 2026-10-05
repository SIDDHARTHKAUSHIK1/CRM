<?php

return [
    'signup' => [
        'enabled'       => env('CRM_SIGNUP_ENABLED', true),
        'auto_activate' => env('CRM_SIGNUP_AUTO_ACTIVATE', true),
        'trial_days'    => env('CRM_SIGNUP_TRIAL_DAYS', 14),
        'notify_email'  => env('CRM_SIGNUP_NOTIFY_EMAIL'), // optional: who gets a "new signup" email
    ],
];
