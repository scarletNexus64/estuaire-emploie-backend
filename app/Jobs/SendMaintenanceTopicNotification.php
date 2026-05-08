<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class SendMaintenanceTopicNotification implements ShouldQueue
{
    use Queueable;

    public $isActive;
    public $message;

    /**
     * Create a new job instance.
     */
    public function __construct(bool $isActive, ?string $message = null)
    {
        $this->isActive = $isActive;
        $this->message = $message;
        $this->onQueue('notifications');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Initialiser Firebase Messaging
            $factory = (new Factory)->withServiceAccount(config('firebase.credentials.file'));
            $messaging = $factory->createMessaging();

            // Préparer le titre et le message
            $title = $this->isActive ? '⚠️ Maintenance en cours' : '✅ Services disponibles';
            $messageText = $this->message ?? ($this->isActive
                ? 'Nous effectuons actuellement une maintenance. L\'application sera bientôt disponible.'
                : 'La maintenance est terminée. Tous nos services sont à nouveau disponibles.');

            // Créer la notification FCM
            $notification = Notification::create($title, $messageText);

            // Créer le message avec data payload
            $message = CloudMessage::withTarget('topic', 'maintenance')
                ->withNotification($notification)
                ->withData([
                    'type' => 'maintenance_activated', // 🔥 IMPORTANT: Type pour le frontend
                    'is_active' => (string) $this->isActive,
                    'message' => $this->message ?? '',
                    'timestamp' => now()->toIso8601String(),
                ]);

            // Envoyer au topic 'maintenance' (tous les users abonnés reçoivent)
            $messaging->send($message);

            Log::info('Maintenance topic notification sent', [
                'topic' => 'maintenance',
                'is_active' => $this->isActive,
                'message' => $this->message,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send maintenance topic notification', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Rethrow pour que la queue puisse retry
            throw $e;
        }
    }
}
