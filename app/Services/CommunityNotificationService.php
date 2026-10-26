<?php
namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Jobs\SendCommunityNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CommunityNotificationService
{
    public function send(int $userId, string $type, string $title, string $message, array $data = []): void
    {
        $notification = Notification::create([
            'notifiable_type' => User::class, 'notifiable_id' => $userId, 'type' => $type,
            'data' => ['title' => $title, 'message' => $message, ...$data],
        ]);
        DB::afterCommit(function () use ($notification) {
            try { SendCommunityNotification::dispatch($notification->id); }
            catch (\Throwable $e) { Log::error('Push communauté non mis en file', ['notification_id' => $notification->id, 'error' => $e->getMessage()]); }
        });
    }
}
