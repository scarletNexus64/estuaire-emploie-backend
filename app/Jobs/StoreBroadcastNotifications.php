<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Uuid;

/**
 * Enregistre en base (table notifications) une diffusion envoyée par topic FCM,
 * pour qu'elle apparaisse dans la page Notifications de chaque utilisateur.
 *
 * Le push par topic ne laisse aucune trace par utilisateur : ce job la crée,
 * PAR LOTS. Un job traite au plus CHUNK utilisateurs (pagination par id) puis
 * se relance pour la suite : aucun job long, aucune requête géante.
 *
 * Idempotent : l'id de chaque ligne est un UUID v5 dérivé de
 * ($dedupeKey, user_id) et l'insertion ignore les doublons. Un lot rejoué
 * (retry après échec partiel) ne crée donc jamais deux fois la même notification.
 */
class StoreBroadcastNotifications implements ShouldQueue
{
    use Queueable;

    public $tries = 3;
    public $timeout = 120;
    public $backoff = [30, 120];

    /** Utilisateurs traités par job. */
    public const CHUNK = 1000;

    /**
     * @param  string  $type  Type de notification (ex: cv_profile_request)
     * @param  array<string>  $roles  Rôles destinataires (users.role), vide pour tous
     * @param  array  $data  Payload additionnel (request_id, …) fusionné dans data
     * @param  string  $dedupeKey  Clé unique de la diffusion (ex: "cv_profile_request:12")
     * @param  int|null  $excludeUserId  Auteur de la diffusion, jamais notifié
     */
    public function __construct(
        public string $type,
        public array $roles,
        public string $title,
        public string $message,
        public array $data,
        public string $dedupeKey,
        public ?int $excludeUserId = null,
        public int $afterId = 0,
    ) {
        $this->onQueue('notifications');
    }

    public function handle(): void
    {
        $userIds = User::query()
            ->when($this->roles !== [], fn ($query) => $query->whereIn('role', $this->roles))
            ->where('is_active', true)
            ->when($this->excludeUserId, fn ($q) => $q->where('id', '!=', $this->excludeUserId))
            ->where('id', '>', $this->afterId)
            ->orderBy('id')
            ->limit(self::CHUNK)
            ->pluck('id');

        if ($userIds->isEmpty()) {
            return;
        }

        $now = now();
        $payload = json_encode([
            'title' => $this->title,
            'message' => $this->message,
            'sent_at' => $now->toISOString(),
            ...$this->data,
        ]);

        $rows = $userIds->map(fn (int $userId) => [
            'id' => Uuid::uuid5(Uuid::NAMESPACE_URL, "estuaire:{$this->dedupeKey}:{$userId}")->toString(),
            'type' => $this->type,
            'notifiable_type' => User::class,
            'notifiable_id' => $userId,
            'data' => $payload,
            'read_at' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ])->all();

        foreach (array_chunk($rows, 500) as $chunk) {
            DB::table('notifications')->insertOrIgnore($chunk);
        }

        Log::info('[BroadcastNotifications] Lot enregistré', [
            'key' => $this->dedupeKey,
            'roles' => $this->roles,
            'count' => count($rows),
            'after_id' => $this->afterId,
        ]);

        // Lot plein : il reste peut-être des utilisateurs après le dernier id.
        if ($userIds->count() === self::CHUNK) {
            self::dispatch(
                $this->type,
                $this->roles,
                $this->title,
                $this->message,
                $this->data,
                $this->dedupeKey,
                $this->excludeUserId,
                (int) $userIds->last(),
            );
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('[BroadcastNotifications] Échec définitif d\'un lot', [
            'key' => $this->dedupeKey,
            'after_id' => $this->afterId,
            'error' => $exception->getMessage(),
        ]);
    }
}
