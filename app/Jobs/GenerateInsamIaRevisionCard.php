<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\InsamIa\InsamIaException;
use App\Services\InsamIa\InsamIaService;
use App\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Génère une fiche de révision INSAM-IA pour une catégorie.
 *
 * La génération par IA dure couramment 40 s et peut dépasser la minute :
 * elle est donc systématiquement différée, et l'étudiant est notifié
 * (notification en base + push) dès que la fiche est disponible.
 */
class GenerateInsamIaRevisionCard implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * La génération est coûteuse côté INSAM-IA : un seul réessai, pour ne pas
     * empiler des appels longs sur un service déjà en difficulté.
     */
    public $tries = 2;

    public $timeout = 240;

    /**
     * Laisse le temps au service tiers de se rétablir avant de réessayer.
     */
    public $backoff = 60;

    public function __construct(
        private readonly int $userId,
        private readonly int $categoryId,
        private readonly ?string $categoryName = null,
    ) {
        $this->onQueue('notifications');
    }

    public function handle(InsamIaService $insamIa, NotificationService $notifications): void
    {
        $user = User::find($this->userId);

        if (!$user) {
            Log::info('🧾 [INSAM-IA REVISION] Utilisateur absent, génération annulée', [
                'user_id' => $this->userId,
            ]);

            return;
        }

        // Le driver `database` reprend un job après retry_after (90 s) alors
        // que la génération peut durer plus longtemps : ce verrou empêche une
        // seconde exécution en parallèle pour la même catégorie.
        $lock = Cache::lock("insam_ia:revision:{$this->categoryId}", $this->timeout);

        if (!$lock->get()) {
            Log::info('🧾 [INSAM-IA REVISION] Génération déjà en cours, job ignoré', [
                'category_id' => $this->categoryId,
            ]);

            return;
        }

        try {
            Log::info('🧾 [INSAM-IA REVISION] Début de génération', [
                'user_id' => $user->id,
                'category_id' => $this->categoryId,
            ]);

            $card = $insamIa->generateRevisionCard($this->categoryId, $user);

            $notifications->sendToUser(
                $user,
                __('insam_ia.generation_ready_title'),
                __('insam_ia.generation_ready_body', [
                    'title' => $card['title'] ?: ($this->categoryName ?? ''),
                ]),
                'insam_ia_revision_ready',
                [
                    'revision_card_id' => $card['id'],
                    'category_id' => $this->categoryId,
                ]
            );

            Log::info('✅ [INSAM-IA REVISION] Fiche générée', [
                'user_id' => $user->id,
                'revision_card_id' => $card['id'] ?? null,
            ]);
        } catch (InsamIaException $e) {
            // Service tiers en défaut : on prévient l'étudiant plutôt que de
            // le laisser attendre une notification qui ne viendra pas.
            Log::warning('⚠️ [INSAM-IA REVISION] Génération impossible', [
                'user_id' => $user->id,
                'category_id' => $this->categoryId,
                'reason' => $e->reason,
            ]);

            $this->notifyFailure($notifications, $user);

            // Une indisponibilité passagère mérite le réessai prévu ; une
            // absence de configuration, non.
            if ($e->reason !== InsamIaException::REASON_NOT_CONFIGURED) {
                throw $e;
            }
        } finally {
            $lock->release();
        }
    }

    public function failed(Throwable $exception): void
    {
        Log::error('❌ [INSAM-IA REVISION] Échec définitif', [
            'user_id' => $this->userId,
            'category_id' => $this->categoryId,
            'error' => $exception->getMessage(),
        ]);
    }

    /**
     * L'échec de notification ne doit jamais masquer l'échec d'origine.
     */
    private function notifyFailure(NotificationService $notifications, User $user): void
    {
        try {
            $notifications->sendToUser(
                $user,
                __('insam_ia.generation_failed_title'),
                __('insam_ia.generation_failed_body'),
                'insam_ia_revision_failed',
                ['category_id' => $this->categoryId]
            );
        } catch (Throwable $e) {
            Log::warning('⚠️ [INSAM-IA REVISION] Notification d\'échec non envoyée', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
