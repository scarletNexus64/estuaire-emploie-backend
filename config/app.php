<?php

use Illuminate\Support\Facades\Facade;

return [
    'name' => env('APP_NAME', 'Laravel'),
    'env' => env('APP_ENV', 'production'),
    'debug' => (bool) env('APP_DEBUG', false),
    'url' => env('APP_URL', 'http://localhost'),
    'frontend_url' => env('FRONTEND_URL', env('APP_URL', 'http://localhost')),

    // Base URL utilisée pour générer les liens de partage d'offres (deeplinks).
    // En production : domaine HTTPS public. En local : retombe sur APP_URL.
    'share_base_url' => env('SHARE_BASE_URL', env('APP_URL', 'http://localhost')),

    // Scheme custom de l'application mobile (deeplink : estuaireemploi://job/{id}).
    'app_scheme' => env('APP_SCHEME', 'estuaireemploi'),

    // Liens des stores pour la page de partage (fallback si l'app n'est pas installée).
    'app_store_url' => env('APP_STORE_URL', 'https://apps.apple.com/cm/app/estuaire-emploi/id1666203946'),
    'play_store_url' => env('PLAY_STORE_URL', 'https://play.google.com/store/apps/details?id=com.insam.estuaire_emploie'),

    // Comptes exemptés de la vérification device_id lors du login (review Apple, testeurs pawapay…).
    // Liste d'emails séparés par des virgules dans DEVICE_BYPASS_EMAILS.
    'device_bypass_emails' => array_values(array_filter(array_map(
        fn ($e) => strtolower(trim($e)),
        explode(',', (string) env('DEVICE_BYPASS_EMAILS', 'jrkira84@gmail.com'))
    ))),
    'timezone' => env('APP_TIMEZONE', 'UTC'),
    'locale' => env('APP_LOCALE', 'fr'),
    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'fr'),
    'faker_locale' => env('APP_FAKER_LOCALE', 'fr_FR'),
    'cipher' => 'AES-256-CBC',
    'key' => env('APP_KEY'),
    'previous_keys' => [
        ...array_filter(
            explode(',', env('APP_PREVIOUS_KEYS', ''))
        ),
    ],
    'maintenance' => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
        'store' => env('APP_MAINTENANCE_STORE', 'database'),
    ],
];
