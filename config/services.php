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

    // INSAM-IA — contenu pédagogique de l'espace étudiant : packs d'épreuves,
    // fiches de révision générées par IA et sessions d'évaluation (QCM).
    //
    // Deux mécanismes d'authentification coexistent côté INSAM-IA :
    //  - `api_key` (en-tête X-API-Key) pour /api/external/* ;
    //  - un token Sanctum obtenu par /api/login pour le reste.
    // Sans identifiants configurés, l'intégration se désactive proprement
    // (cf. InsamIaClient::isConfigured) plutôt que d'échouer en cascade.
    'insam_ia' => [
        'base_url' => env('INSAM_IA_BASE_URL', 'https://insam-ia.com'),
        'api_key' => env('INSAM_IA_API_KEY'),
        'email' => env('INSAM_IA_EMAIL'),
        'password' => env('INSAM_IA_PASSWORD'),
        'timeout' => env('INSAM_IA_TIMEOUT', 30),
        // La génération de contenu par IA (fiche de révision, QCM) prend
        // couramment 40 à 180 s : elle a son propre délai, et n'est appelée
        // que depuis un job en file d'attente.
        'generation_timeout' => env('INSAM_IA_GENERATION_TIMEOUT', 180),
        // Durée de mise en cache des contenus stables (catégories, listes).
        'cache_ttl' => env('INSAM_IA_CACHE_TTL', 3600),
    ],

    // exchangerate-api.com — taux de change live pour la conversion des prix
    // vers la devise préférée du user. Base = devise de base du ledger (XAF).
    'exchangerate' => [
        'api_key' => env('EXCHANGERATE_API_KEY'),
        'base_currency' => env('EXCHANGERATE_BASE', 'XAF'),
        // Au-delà de ce nombre d'heures sans MAJ, les taux sont "périmés" :
        // on refuse de convertir des paiements avec un taux trop vieux.
        'max_age_hours' => env('EXCHANGERATE_MAX_AGE_HOURS', 48),
    ],

];
