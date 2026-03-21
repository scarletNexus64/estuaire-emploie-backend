<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SendMaintenanceNotificationJob implements ShouldQueue
{
    use Queueable;

    public $user;
    public $isActive;
    public $message;

    /**
     * Create a new job instance.
     */
    public function __construct(User $user, bool $isActive, ?string $message = null)
    {
        $this->user = $user;
        $this->isActive = $isActive;
        $this->message = $message;
        $this->onQueue('notifications');
    }

    /**
     * Execute the job.
     */
    public function handle(NotificationService $notificationService): void
    {
        try {
            // Send push notification via Firebase (BULK - FCM uniquement, pas d'email)
            $title = $this->isActive ? '⚠️ Maintenance en cours' : '✅ Services disponibles';
            $messageText = $this->message ?? ($this->isActive
                ? 'Nous effectuons actuellement une maintenance. L\'application sera bientôt disponible.'
                : 'La maintenance est terminée. Tous nos services sont à nouveau disponibles.');

            $notificationService->sendToUser(
                $this->user,
                $title,
                $messageText,
                'maintenance',
                [
                    'is_active' => $this->isActive,
                    'message' => $this->message,
                ]
            );

            Log::info('Maintenance notification sent (FCM only)', [
                'user_id' => $this->user->id,
                'is_active' => $this->isActive,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send maintenance notification', [
                'user_id' => $this->user->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
