<?php

return [
    // Chemin du fichier de compte de service Firebase. Ce fichier est un secret
    // (gitignored) : il est absent en CI et sur une machine fraîche, d'où le
    // chemin configurable et la dégradation propre dans
    // App\Services\FirebaseNotificationService.
    'credentials' => env(
        'FIREBASE_CREDENTIALS',
        base_path('firebase/estuaire-emplois-firebase-adminsdk-fbsvc-6c0d8105ad.json')
    ),
];
