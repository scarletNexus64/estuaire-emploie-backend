<?php

namespace App\Jobs;

use App\Models\CvProfileRequest;
use App\Services\FirebaseNotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Diffuse une demande de profil (CVThèque) à tous les utilisateurs, PROGRESSIVEMENT.
 *
 * Même mécanique que SendServiceRequestNotification : une vague = un seul appel
 * FCM sur une condition de topics (aucune boucle sur les tokens => ni timeout
 * ni 500), les vagues s'enchaînent avec un délai. Les chercheurs d'emploi
 * d'abord (candidats, puis étudiants), puis les entreprises (cabinets,
 * recommandations). Chaque condition exclut les rôles des vagues précédentes.
 *
 * Avant chaque vague la demande est relue : si l'entreprise l'a clôturée ou
 * supprimée entre-temps, la diffusion s'arrête.
 */
class SendCvProfileRequestNotification implements ShouldQueue
{
    use Queueable;

    public $tries = 3;
    public $timeout = 60;
    public $backoff = [30, 120];

    /** Délai entre deux vagues. */
    public const WAVE_DELAY_MINUTES = 5;

    public const WAVES = [
        "'role_candidate' in topics",
        "'role_student' in topics && !('role_candidate' in topics)",
        "'role_recruiter' in topics && !('role_candidate' in topics) && !('role_student' in topics)",
    ];

    /** Rôles (users.role) couverts par chaque vague, pour l'enregistrement en base. */
    public const WAVE_ROLES = [
        ['candidate'],
        ['student'],
        ['recruiter'],
    ];

    public function __construct(public int $requestId, public int $wave = 0)
    {
        $this->onQueue('notifications');
    }

    public function handle(FirebaseNotificationService $firebaseService): void
    {
        $request = CvProfileRequest::with('company:id,name')->find($this->requestId);
        if (! $request || ! $request->isOpen()) {
            Log::info('[CvProfileRequestNotif] Diffusion interrompue', [
                'request_id' => $this->requestId,
                'wave' => $this->wave,
            ]);

            return;
        }

        $condition = self::WAVES[$this->wave] ?? null;
        if ($condition === null) {
            return;
        }

        $title = Str::limit($request->publisherName(), 40).' recherche : '.Str::limit($request->title, 60);
        $body = Str::limit($request->description, 110);
        if ($request->city) {
            $body = "📍 {$request->city} · {$body}";
        }

        // sendToCondition ne lève pas d'exception : un échec FCM n'empêche pas
        // les vagues suivantes.
        $sent = $firebaseService->sendToCondition($condition, $title, $body, [
            'type' => 'cv_profile_request',
            'request_id' => (string) $request->id,
            'requester_id' => (string) $request->user_id,
        ]);

        // Trace en base (page Notifications), indépendante du résultat FCM.
        // Mise en file séparée et par lots : la vague reste rapide.
        try {
            StoreBroadcastNotifications::dispatch(
                'cv_profile_request',
                self::WAVE_ROLES[$this->wave],
                $title,
                $body,
                [
                    'request_id' => $request->id,
                    'requester_id' => $request->user_id,
                ],
                "cv_profile_request:{$request->id}",
                $request->user_id,
            );
        } catch (\Throwable $e) {
            Log::error('[CvProfileRequestNotif] Enregistrement en base non mis en file', [
                'request_id' => $request->id,
                'wave' => $this->wave,
                'error' => $e->getMessage(),
            ]);
        }

        Log::info('[CvProfileRequestNotif] Vague envoyée', [
            'request_id' => $request->id,
            'wave' => $this->wave,
            'sent' => $sent,
        ]);

        $next = $this->wave + 1;
        if ($next < count(self::WAVES)) {
            self::dispatch($request->id, $next)
                ->delay(now()->addMinutes(self::WAVE_DELAY_MINUTES));
        } else {
            $request->forceFill(['notified_at' => now()])->saveQuietly();
        }
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
