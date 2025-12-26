<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Illuminate\Support\Facades\Log;

class FirebaseNotificationService
{
    protected $messaging;

    public function __construct()
    {
        $factory = (new Factory)
            ->withServiceAccount(config('firebase.credentials'));

        $this->messaging = $factory->createMessaging();
    }

    public function sendToToken(string $fcmToken, string $title, string $body, array $data = [])
    {
        $message = CloudMessage::withTarget('token', $fcmToken)
            ->withNotification(Notification::create($title, $body))
            ->withData($data);

        try {
            $result = $this->messaging->send($message);
            Log::info('FCM notification sent', [
                'token' => $fcmToken,
                'title' => $title,
                'body' => $body,
                'data' => $data,
                'result' => $result,
            ]);

            return $result;
        } catch (\Throwable $e) {
            Log::error('Failed to send FCM notification', [
                'token' => $fcmToken,
                'title' => $title,
                'body' => $body,
                'data' => $data,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }
}
