<?php

namespace App\Jobs;

use App\Models\DigitalizationRequest;
use App\Models\User;
use App\Services\FirebaseNotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Diffuse une demande de prestation à tous les utilisateurs, PROGRESSIVEMENT.
 *
 * Une vague = un seul appel FCM sur une condition de topics (aucune boucle sur
 * les tokens => ni timeout ni 500). Les vagues s'enchaînent avec un délai :
 * entreprises d'abord (prestataires naturels), puis candidats, puis étudiants.
 * Chaque condition exclut les rôles des vagues précédentes, pour qu'un
 * utilisateur abonné à plusieurs topics de rôle ne soit notifié qu'une fois.
 *
 * Chaque vague enregistre aussi la notification en BDD (table notifications)
 * pour les utilisateurs du rôle de la vague, par lots, afin qu'elle apparaisse
 * dans la page Notifications de l'app. Le rôle principal (users.role) répartit
 * chaque utilisateur dans une seule vague : pas de doublon en BDD.
 *
 * Avant chaque vague la demande est relue : si l'admin l'a rejetée ou si
 * l'auteur l'a supprimée entre-temps, la diffusion s'arrête.
 */
class SendServiceRequestNotification implements ShouldQueue
{
    use Queueable;

    public $tries = 3;
    // L'enregistrement BDD d'une vague peut concerner des dizaines de milliers
    // d'utilisateurs (inserts par lots) : on laisse de la marge.
    public $timeout = 600;
    public $backoff = [30, 120];

    /** Délai entre deux vagues. */
    public const WAVE_DELAY_MINUTES = 5;

    /** Rôle (users.role) dont les utilisateurs reçoivent la notif en BDD, par vague. */
    public const WAVE_ROLES = ['recruiter', 'candidate', 'student'];

    /** Taille des lots d'insertion en BDD. */
    private const DB_CHUNK = 1000;

    public const WAVES = [
        "'role_recruiter' in topics",
        "'role_candidate' in topics && !('role_recruiter' in topics)",
        "'role_student' in topics && !('role_candidate' in topics) && !('role_recruiter' in topics)",
    ];

    public function __construct(public int $requestId, public int $wave = 0)
    {
        $this->onQueue('notifications');
    }

    public function handle(FirebaseNotificationService $firebaseService): void
    {
        $request = DigitalizationRequest::find($this->requestId);
        if (! $request || $request->status === 'rejected') {
            Log::info('[ServiceRequestNotif] Diffusion interrompue', [
                'request_id' => $this->requestId,
                'wave' => $this->wave,
            ]);

            return;
        }

        $condition = self::WAVES[$this->wave] ?? null;
        if ($condition === null) {
            return;
        }

        $title = "{$request->category_label} : ".Str::limit($request->project_name, 60);
        $body = Str::limit($request->description, 110);
        if ($request->city) {
            $body = "📍 {$request->city} · {$body}";
        }

        // 1. Historique en BDD (page Notifications). Jamais bloquant : un échec
        //    est journalisé et la diffusion push continue.
        $stored = $this->storeForWave($request, $title, $body);

        // 2. Push. sendToCondition ne lève pas d'exception : un échec FCM
        //    n'empêche pas les vagues suivantes.
        $sent = $firebaseService->sendToCondition($condition, $title, $body, [
            'type' => 'service_request',
            'request_id' => (string) $request->id,
            'requester_id' => (string) $request->user_id,
            'category' => (string) $request->category,
        ]);

        Log::info('[ServiceRequestNotif] Vague envoyée', [
            'request_id' => $request->id,
            'wave' => $this->wave,
            'sent' => $sent,
            'stored' => $stored,
        ]);

        $next = $this->wave + 1;
        if ($next < count(self::WAVES)) {
            self::dispatch($request->id, $next)
                ->delay(now()->addMinutes(self::WAVE_DELAY_MINUTES));
        } else {
            $request->forceFill(['notified_at' => now()])->saveQuietly();
        }
    }

    /**
     * Enregistre la notification pour les utilisateurs du rôle de la vague
     * (hors auteur), par lots d'inserts. Idempotent : si la vague est rejouée
     * (retry), les utilisateurs déjà notifiés pour cette demande sont ignorés.
     *
     * @return int Nombre de lignes insérées
     */
    private function storeForWave(DigitalizationRequest $request, string $title, string $body): int
    {
        $role = self::WAVE_ROLES[$this->wave] ?? null;
        if ($role === null) {
            return 0;
        }

        $payload = json_encode([
            'title' => $title,
            'message' => $body,
            'sent_at' => now()->toISOString(),
            'request_id' => $request->id,
            'category' => $request->category,
            'city' => $request->city,
        ]);

        $inserted = 0;

        try {
            User::query()
                ->select('id')
                ->where('role', $role)
                ->when($request->user_id, fn ($q) => $q->where('id', '!=', $request->user_id))
                ->chunkById(self::DB_CHUNK, function ($users) use ($request, $payload, &$inserted) {
                    $ids = $users->pluck('id')->all();

                    // Retry de la vague : ne pas notifier deux fois.
                    $already = DB::table('notifications')
                        ->where('type', 'service_request')
                        ->where('notifiable_type', User::class)
                        ->whereIn('notifiable_id', $ids)
                        ->where('data', 'like', '%"request_id":'.$request->id.',%')
                        ->pluck('notifiable_id')
                        ->all();
                    $ids = array_diff($ids, $already);
                    if (empty($ids)) {
                        return;
                    }

                    $now = now();
                    $rows = array_map(fn ($id) => [
                        'id' => (string) Str::uuid(),
                        'type' => 'service_request',
                        'notifiable_type' => User::class,
                        'notifiable_id' => $id,
                        'data' => $payload,
                        'read_at' => null,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ], $ids);

                    DB::table('notifications')->insert($rows);
                    $inserted += count($rows);
                });
        } catch (\Throwable $e) {
            Log::error('[ServiceRequestNotif] Enregistrement BDD échoué', [
                'request_id' => $request->id,
                'wave' => $this->wave,
                'inserted' => $inserted,
                'error' => $e->getMessage(),
            ]);
        }

        return $inserted;
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('[ServiceRequestNotif] Échec définitif', [
            'request_id' => $this->requestId,
            'wave' => $this->wave,
            'error' => $exception->getMessage(),
        ]);
    }
}
