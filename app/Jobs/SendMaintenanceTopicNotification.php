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
        Log::info('🔥 [FCM-JOB] ========================================');
        Log::info('🔥 [FCM-JOB] SendMaintenanceTopicNotification job started');
        Log::info('🔥 [FCM-JOB] isActive: ' . ($this->isActive ? 'true' : 'false'));
        Log::info('🔥 [FCM-JOB] message: ' . ($this->message ?? 'null'));
        Log::info('🔥 [FCM-JOB] ========================================');

        try {
            // Initialiser Firebase Messaging
            Log::info('🔥 [FCM-JOB] Initialisation Firebase Factory...');
            $factory = (new Factory)->withServiceAccount(config('firebase.credentials.file'));
            $messaging = $factory->createMessaging();
            Log::info('✅ [FCM-JOB] Firebase Factory initialisé');

            // Préparer le titre et le message
            $title = $this->isActive ? '⚠️ Maintenance en cours' : '✅ Services disponibles';
            $messageText = $this->message ?? ($this->isActive
                ? 'Nous effectuons actuellement une maintenance. L\'application sera bientôt disponible.'
                : 'La maintenance est terminée. Tous nos services sont à nouveau disponibles.');

            Log::info('🔥 [FCM-JOB] Notification préparée:', [
                'title' => $title,
                'messageText' => $messageText,
            ]);

            // Créer la notification FCM
            $notification = Notification::create($title, $messageText);
            Log::info('✅ [FCM-JOB] Notification FCM créée');

            // Créer le message avec data payload
            $dataPayload = [
                'type' => 'maintenance_activated', // 🔥 IMPORTANT: Type pour le frontend
                'is_active' => (string) $this->isActive,
                'message' => $this->message ?? '',
                'timestamp' => now()->toIso8601String(),
            ];

            Log::info('🔥 [FCM-JOB] Data payload:', $dataPayload);

            $message = CloudMessage::withTarget('topic', 'maintenance')
                ->withNotification($notification)
                ->withData($dataPayload);

            Log::info('✅ [FCM-JOB] CloudMessage créé pour topic "maintenance"');

            // Envoyer au topic 'maintenance' (tous les users abonnés reçoivent)
            Log::info('📤 [FCM-JOB] Envoi du message au topic maintenance...');
            $result = $messaging->send($message);
            Log::info('✅ [FCM-JOB] Message envoyé avec succès!');
            Log::info('✅ [FCM-JOB] Result: ' . json_encode($result));

            Log::info('✅ [FCM-JOB] ========================================');
            Log::info('✅ [FCM-JOB] Maintenance topic notification sent successfully', [
                'topic' => 'maintenance',
                'is_active' => $this->isActive,
                'message' => $this->message,
            ]);
            Log::info('✅ [FCM-JOB] ========================================');
        } catch (\Exception $e) {
            Log::error('❌ [FCM-JOB] ========================================');
            Log::error('❌ [FCM-JOB] Failed to send maintenance topic notification', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            Log::error('❌ [FCM-JOB] ========================================');

            // Rethrow pour que la queue puisse retry
            throw $e;
        }
    }
}
