<?php

namespace App\Jobs;

use App\Models\DigitalizationRequest;
use App\Services\FirebaseNotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/** Diffusion immédiate aux abonnés, avec nouvelles tentatives si Firebase refuse. */
class SendServiceRequestNotification implements ShouldQueue
{
    use Queueable;

    public $tries = 3;
    public $timeout = 60;
    public $backoff = [30, 120];

    // Compatibilité avec les anciennes vagues encore sérialisées dans la queue.
    public function __construct(public int $requestId, public int $wave = 0)
    {
        $this->onQueue('notifications');
        $this->afterCommit();
    }

    public function handle(FirebaseNotificationService $firebaseService): void
    {
        $request = DigitalizationRequest::find($this->requestId);
        if (!$request || $request->status === 'rejected' || $request->notified_at
            || $request->created_at->lt(now()->subDays(30))) {
            return;
        }

        $title = "{$request->category_label} : ".Str::limit($request->project_name, 60);
        $body = Str::limit($request->description, 110);
        if ($request->city) $body = "📍 {$request->city} · {$body}";
        $data = [
            'request_id' => $request->id,
            'requester_id' => $request->user_id,
            'category' => $request->category,
            'city' => $request->city,
        ];

        // Historique indépendant du transport push, sans doublons lors des retries.
        // Une erreur de mise en file remonte également pour permettre une reprise.
        StoreBroadcastNotifications::dispatch(
            'service_request', [], $title, $body, $data,
            "service_request:{$request->id}",
        );

        $sent = $firebaseService->sendToTopic('all_users', $title, $body, [
            'type' => 'service_request',
            ...array_map(fn ($value) => (string) ($value ?? ''), $data),
        ]);
        if (!$sent) {
            throw new \RuntimeException('Firebase a refusé la diffusion de la demande de service.');
        }
        $request->forceFill(['notified_at' => now()])->saveQuietly();
        Log::info('[ServiceRequestNotif] Diffusion acceptée par Firebase', [
            'request_id' => $request->id, 'topic' => 'all_users', 'accepted_by_fcm' => true,
        ]);
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('[ServiceRequestNotif] Échec définitif', [
            'request_id' => $this->requestId, 'error' => $exception->getMessage(),
        ]);
    }
}
