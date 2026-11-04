<?php

// Garder le chemin historique des serveurs déjà configurés, puis chercher
// le compte de service livré dans storage/firebase sur cette installation.
// Le compte de service le plus récent de storage/firebase gagne : remplacer
// une clé révoquée suffit, sans toucher au code ni au .env.
$storedCredentials = glob(storage_path('firebase/*adminsdk*.json')) ?: [];
usort($storedCredentials, fn ($a, $b) => filemtime($b) <=> filemtime($a));

$credentialPaths = [
    base_path('firebase/estuaire-emplois-firebase-adminsdk-fbsvc-6c0d8105ad.json'),
    ...$storedCredentials,
];
$defaultCredentials = $credentialPaths[1] ?? $credentialPaths[0];
foreach ($credentialPaths as $credentialPath) {
    if (is_file($credentialPath)) {
        $defaultCredentials = $credentialPath;
        break;
    }
}

return [
    'credentials' => env('FIREBASE_CREDENTIALS') ?: $defaultCredentials,
];
