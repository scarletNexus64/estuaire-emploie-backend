<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\FirebaseNotificationService;

class TestNotificationController extends Controller
{
    public function send(FirebaseNotificationService $firebase)
    {
        $user = User::whereNotNull('fcm_token')->first();

        if (!$user) {
            return response()->json([
                'message' => 'Aucun utilisateur avec un token FCM'
            ], 404);
        }

        $firebase->sendToToken(
            $user->fcm_token,
            'Test notification 🎉',
            'Firebase fonctionne depuis Laravel 🚀',
            [
                'type' => 'test',
                'source' => 'backend'
            ]
        );

        return response()->json([
            'message' => 'Notification envoyée avec succès'
        ]);
    }
}
