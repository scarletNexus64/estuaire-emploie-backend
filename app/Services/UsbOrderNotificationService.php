<?php

namespace App\Services;

use App\Jobs\SendUsbOrderNotification;
use App\Models\InsamtechsUsbOrder;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UsbOrderNotificationService
{
    /** À appeler dans la même transaction que la création / modification. */
    public function record(InsamtechsUsbOrder $order, string $type, string $title, string $message): void
    {
        $notification = Notification::create([
            'type' => $type,
            'notifiable_type' => User::class,
            'notifiable_id' => $order->user_id,
            'data' => [
                'title' => $title,
                'message' => $message,
                'order_id' => $order->id,
                'status' => $order->status,
            ],
        ]);

        // Le push ne doit jamais faire échouer une commande déjà enregistrée.
        DB::afterCommit(function () use ($notification) {
            try {
                SendUsbOrderNotification::dispatch($notification->id);
            } catch (\Throwable $e) {
                Log::error('Push USB non mis en file ; notification disponible dans l’application.', [
                    'notification_id' => $notification->id,
                    'error' => $e->getMessage(),
                ]);
            }
        });
    }
}
