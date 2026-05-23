<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Supported locales
    |--------------------------------------------------------------------------
    | Locales the API and the admin dashboard accept. The first entry is the
    | default; the fallback is set in config/app.php (fallback_locale).
    */
    'locales' => ['fr', 'en', 'es', 'ar'],

    /*
    |--------------------------------------------------------------------------
    | Human readable labels for the admin dashboard
    |--------------------------------------------------------------------------
    */
    'labels' => [
        'fr' => 'Français',
        'en' => 'English',
        'es' => 'Español',
        'ar' => 'العربية',
    ],

    /*
    |--------------------------------------------------------------------------
    | RTL locales
    |--------------------------------------------------------------------------
    | Locales that should be rendered right-to-left in the admin dashboard.
    */
    'rtl' => ['ar'],

    /*
    |--------------------------------------------------------------------------
    | Header used to detect locale on API requests
    |--------------------------------------------------------------------------
    */
    'request_header' => 'Accept-Language',

    /*
    |--------------------------------------------------------------------------
    | Query parameter override
    |--------------------------------------------------------------------------
    | Optional ?lang=xx that wins over the header (useful for testing).
    */
    'query_param' => 'lang',
];
