<?php

// Garder le chemin historique des serveurs déjà configurés, puis chercher
// le compte de service livré dans storage/firebase sur cette installation.
$credentialPaths = [
    base_path('firebase/estuaire-emplois-firebase-adminsdk-fbsvc-6c0d8105ad.json'),
    storage_path('firebase/estuaire-emplois-firebase-adminsdk-fbsvc-f136a4ae4a.json'),
];
$defaultCredentials = $credentialPaths[1];
foreach ($credentialPaths as $credentialPath) {
    if (is_file($credentialPath)) {
        $defaultCredentials = $credentialPath;
        break;
    }
}

return [
    'credentials' => env('FIREBASE_CREDENTIALS') ?: $defaultCredentials,
];
