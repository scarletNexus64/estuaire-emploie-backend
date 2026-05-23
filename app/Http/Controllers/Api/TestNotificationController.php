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
                'message' => __('notification.no_user_with_fcm')
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
            'message' => __('notification.sent')
        ]);
    }
}
