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
 * Génère une fiche de révision à partir d'un support de cours.
 *
 * Pendant de [GenerateInsamIaRevisionCard], qui travaille par filière. Ici la
 * fiche porte sur le cours que l'étudiant révise réellement : c'est le mode de
 * génération à privilégier.
 *
 * Comme sa jumelle, la génération est différée — l'assistant met plusieurs
 * dizaines de secondes à rédiger — et l'étudiant est notifié à l'arrivée.
 */
class GenerateInsamIaCourseRevisionCard implements ShouldQueue
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

    /**
     * @param  array<int, string>  $lacunes
     */
    public function __construct(
        private readonly int $userId,
        private readonly int $documentId,
        private readonly ?string $chapitre = null,
        private readonly array $lacunes = [],
    ) {
        $this->onQueue('notifications');
    }

    public function handle(InsamIaService $insamIa, NotificationService $notifications): void
    {
        $user = User::find($this->userId);

        if (!$user) {
            Log::info('🧾 [INSAM-IA COURS] Utilisateur absent, génération annulée', [
                'user_id' => $this->userId,
            ]);

            return;
        }

        // Le verrou porte sur le couple (étudiant, cours) : deux étudiants
        // peuvent demander la fiche du même cours en même temps, chacune
        // tenant compte de leurs propres lacunes.
        $lock = Cache::lock(
            "insam_ia:course-revision:{$this->userId}:{$this->documentId}",
            $this->timeout
        );

        if (!$lock->get()) {
            Log::info('🧾 [INSAM-IA COURS] Génération déjà en cours, job ignoré', [
                'user_id' => $this->userId,
                'document_id' => $this->documentId,
            ]);

            return;
        }

        try {
            Log::info('🧾 [INSAM-IA COURS] Début de génération', [
                'user_id' => $user->id,
                'document_id' => $this->documentId,
            ]);

            $card = $insamIa->generateCourseRevisionCard(
                $this->documentId,
                $user,
                $this->chapitre,
                $this->lacunes,
            );

            $notifications->sendToUser(
                $user,
                __('insam_ia.generation_ready_title'),
                __('insam_ia.generation_ready_body', ['title' => $card['title'] ?? '']),
                'insam_ia_revision_ready',
                [
                    'revision_card_id' => $card['id'] ?? null,
                    // La fiche se relit par son identifiant local : sans cette
                    // source, l'application la chercherait chez INSAM-IA.
                    'source' => 'course',
                    'document_id' => $this->documentId,
                ]
            );

            Log::info('✅ [INSAM-IA COURS] Fiche générée', [
                'user_id' => $user->id,
                'revision_card_id' => $card['id'] ?? null,
            ]);
        } catch (InsamIaException $e) {
            // Service tiers en défaut : on prévient l'étudiant plutôt que de
            // le laisser attendre une notification qui ne viendra pas.
            Log::warning('⚠️ [INSAM-IA COURS] Génération impossible', [
                'user_id' => $user->id,
                'document_id' => $this->documentId,
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
        Log::error('❌ [INSAM-IA COURS] Échec définitif', [
            'user_id' => $this->userId,
            'document_id' => $this->documentId,
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
                ['document_id' => $this->documentId]
            );
        } catch (Throwable $e) {
            Log::warning('⚠️ [INSAM-IA COURS] Notification d\'échec non envoyée', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
