<?php

namespace App\Jobs;

use App\Models\Notification;
use App\Models\User;
use App\Services\FirebaseNotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Str;

class SendUsbOrderNotification implements ShouldQueue
{
    use Queueable;

    public $tries = 3;
    public $timeout = 60;
    public $backoff = [30, 120];

    public function __construct(public string $notificationId)
    {
        // La file default est consommée par les workers déjà documentés en local et en production.
        $this->onQueue('default');
        $this->afterCommit();
    }

    public function handle(FirebaseNotificationService $firebase): void
    {
        // La notification persistante est créée avec la commande, jamais par
        // ce job : une nouvelle tentative de push ne crée pas de doublon.
        $notification = Notification::find($this->notificationId);
        if (! $notification || $notification->notifiable_type !== User::class) {
            return;
        }
        $user = User::find($notification->notifiable_id);
        if (! $user?->fcm_token) {
            return;
        }
        $data = $notification->data;
        $firebase->sendToToken(
            $user->fcm_token,
            $data['title'],
            Str::limit($data['message'], 180),
            [
                'type' => $notification->type,
                'order_id' => (string) $data['order_id'],
                'status' => $data['status'],
                'notification_id' => $notification->id,
            ],
        );
    }
}
