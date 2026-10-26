<?php
namespace App\Jobs;

use App\Models\Notification;
use App\Models\User;
use App\Services\FirebaseNotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Str;

class SendCommunityNotification implements ShouldQueue
{
    use Queueable;
    public $tries = 3;
    public $timeout = 60;
    public $backoff = [30, 120];
    public function __construct(public string $notificationId) { $this->onQueue('default'); $this->afterCommit(); }
    public function handle(FirebaseNotificationService $firebase): void
    {
        $notification = Notification::find($this->notificationId);
        if (! $notification || $notification->notifiable_type !== User::class) return;
        $user = User::find($notification->notifiable_id);
        if (! $user?->fcm_token) return;
        $data = $notification->data;
        $firebase->sendToToken($user->fcm_token, $data['title'], Str::limit($data['message'], 180), [
            'type' => $notification->type,
            'discussion_id' => (string) ($data['discussion_id'] ?? ''),
            'assistance_id' => (string) ($data['assistance_id'] ?? ''),
            'notification_id' => $notification->id,
        ]);
    }
}
