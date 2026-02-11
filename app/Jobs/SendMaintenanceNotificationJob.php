<?php

namespace App\Jobs;

use App\Models\User;
use App\Notifications\MaintenanceNotification;
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
            // Send email notification via Laravel notification
            $this->user->notify(new MaintenanceNotification($this->isActive, $this->message));

            // Send push notification via Firebase
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

            Log::info('Maintenance notification sent', [
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
