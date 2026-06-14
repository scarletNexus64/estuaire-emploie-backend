<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'insamtechs' => [
        'api_url' => env('INSAMTECHS_API_URL', 'http://127.0.0.1:8001/api'),
    ],

    // GFSolutions (G-Financials) — compte bancaire offert lors de la
    // souscription d'un pack incluant l'avantage `gfs_free_account`.
    'gfs' => [
        'base_url' => env('GFS_BASE_URL', 'https://backend.gfinancials.com/api/v1'),
        'api_key' => env('GFS_API_KEY'),
        'timeout' => env('GFS_TIMEOUT', 30),
    ],

];
