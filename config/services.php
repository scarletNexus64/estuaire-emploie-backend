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
        // API publique du catalogue (formations vidéo), synchronisée pour la
        // recherche par compétence / spécialité / secteur.
        'public_api_url' => env('INSAMTECHS_PUBLIC_API_URL', 'https://admin.insamtechs.com/api'),
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

    /*
    | Connexion « Continuer avec Google / Apple » (POST /api/auth/social).
    | Identifiants acceptés comme audience du jeton, séparés par des virgules.
    | Google : client OAuth iOS, Android et Web de la console Google Cloud.
    | Apple  : bundle ID de l'app iOS (et Service ID si connexion web un jour).
    */
    /*
    | KPay Lite — paiement par carte bancaire (Visa / Mastercard), compte et
    | clés distincts de KPay Mobile Money. Ces valeurs ne servent que de repli :
    | la configuration saisie dans l'admin (service_type `kpay_lite`) prime.
    | Le préfixe de la clé API choisit l'environnement (kpay_lite_test_ /
    | kpay_lite_live_), l'URL de base est la même pour les deux.
    */
    'kpay_lite' => [
        'enabled' => (bool) env('KPAY_LITE_ENABLED', false),
        'base_url' => env('KPAY_LITE_BASE_URL', 'https://lite.kpay.site'),
        'api_key' => env('KPAY_LITE_API_KEY'),
        'secret_key' => env('KPAY_LITE_SECRET_KEY'),
        'webhook_secret' => env('KPAY_LITE_WEBHOOK_SECRET'),
        'gateway_secret' => env('KPAY_LITE_GATEWAY_SECRET'),
        // KPay Lite refuse un montant converti inférieur à 1 USD.
        'min_amount_xaf' => (int) env('KPAY_LITE_MIN_AMOUNT_XAF', 1000),
        // Un paiement abandonné sur la page hébergée reste PENDING sans
        // webhook : passé ce délai, on le clôt nous-mêmes (« expired »).
        'expiry_minutes' => (int) env('KPAY_LITE_EXPIRY_MINUTES', 45),
        'timeout' => (int) env('KPAY_LITE_TIMEOUT', 30),
    ],

    'google' => [
        'client_ids' => array_filter(array_map('trim', explode(',', (string) env('GOOGLE_CLIENT_IDS', '')))),
    ],

    'apple' => [
        'client_ids' => array_filter(array_map('trim', explode(',', (string) env('APPLE_CLIENT_IDS', '')))),
    ],

];
