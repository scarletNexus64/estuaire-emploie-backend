<?php

namespace App\Jobs;

use App\Models\CvProfileRequest;
use App\Services\FirebaseNotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Diffuse une demande de profil en un appel au topic all_users.
 * Les échecs FCM déclenchent les nouvelles tentatives de la queue.
 */
class SendCvProfileRequestNotification implements ShouldQueue
{
    use Queueable;

    public $tries = 3;
    public $timeout = 60;
    public $backoff = [30, 120];

    // Conserver wave pour les tâches déjà sérialisées avant le passage au topic global.
    public function __construct(public int $requestId, public int $wave = 0)
    {
        $this->onQueue('notifications');
    }

    public function handle(FirebaseNotificationService $firebaseService): void
    {
        $request = CvProfileRequest::with('company:id,name')->find($this->requestId);
        if (! $request || ! $request->isOpen() || $request->notified_at) {
            Log::info('[CvProfileRequestNotif] Diffusion interrompue', [
                'request_id' => $this->requestId,
                'wave' => $this->wave,
            ]);

            return;
        }

        $title = Str::limit($request->publisherName(), 40).' recherche : '.Str::limit($request->title, 60);
        $body = Str::limit($request->description, 110);
        if ($request->city) {
            $body = "📍 {$request->city} · {$body}";
        }

        $sent = $firebaseService->sendToTopic('all_users', $title, $body, [
            'type' => 'cv_profile_request',
            'request_id' => (string) $request->id,
            'requester_id' => (string) $request->user_id,
        ]);

        if (! $sent) {
            throw new \RuntimeException('Firebase a refusé la diffusion de la demande de profil au topic all_users.');
        }

        // Ne marquer la diffusion terminée qu'après acceptation par Firebase.
        $request->forceFill(['notified_at' => now()])->saveQuietly();

        // L'historique est traité par lots séparément du push.
        try {
            StoreBroadcastNotifications::dispatch(
                'cv_profile_request',
                [],
                $title,
                $body,
                [
                    'request_id' => $request->id,
                    'requester_id' => $request->user_id,
                ],
                "cv_profile_request:{$request->id}",
            );
        } catch (\Throwable $e) {
            Log::error('[CvProfileRequestNotif] Enregistrement en base non mis en file', [
                'request_id' => $request->id,
                'wave' => $this->wave,
                'error' => $e->getMessage(),
            ]);
        }

        Log::info('[CvProfileRequestNotif] Diffusion acceptée par Firebase', [
            'request_id' => $request->id,
            'topic' => 'all_users',
            'accepted_by_fcm' => true,
        ]);
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('[CvProfileRequestNotif] Échec définitif', [
            'request_id' => $this->requestId,
            'wave' => $this->wave,
            'error' => $exception->getMessage(),
        ]);
    }
}
