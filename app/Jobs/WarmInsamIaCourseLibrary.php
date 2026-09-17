<?php

namespace App\Jobs;

use App\Services\InsamIa\InsamIaException;
use App\Services\InsamIa\InsamIaService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Met en cache le catalogue de cours d'INSAM-IA.
 *
 * Le filtrage par spécialité et par niveau ne peut pas être délégué au service
 * tiers : la bibliothèque ne connaît ni l'un ni l'autre. Il faut donc disposer
 * du catalogue entier, soit 228 pages et plusieurs minutes de collecte — une
 * durée impensable dans le temps d'une requête.
 *
 * Tant que le cache est vide, l'espace sert la page distante filtrée : peu de
 * cours, mais immédiatement. Ce job comble ensuite le manque en arrière-plan.
 */
class WarmInsamIaCourseLibrary implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Un seul essai : un échec sera de toute façon relancé par la prochaine
     * consultation de l'onglet, sans empiler les collectes.
     */
    public $tries = 1;

    public $timeout = 900;

    public function __construct()
    {
        $this->onQueue('notifications');
    }

    public function handle(InsamIaService $insamIa): void
    {
        try {
            $documents = $insamIa->allCourseDocuments();

            Log::info('📚 [INSAM-IA COURS] Catalogue mis en cache', [
                'documents' => count($documents),
            ]);
        } catch (InsamIaException $e) {
            Log::warning('⚠️ [INSAM-IA COURS] Catalogue non rapatrié', [
                'reason' => $e->reason,
            ]);
        } finally {
            // Le verrou posé à la demande doit être rendu, réussite ou non,
            // sans quoi plus aucune tentative ne serait possible avant son
            // expiration.
            Cache::forget('insam_ia:course-library:warming');
        }
    }

    public function failed(Throwable $exception): void
    {
        Cache::forget('insam_ia:course-library:warming');

        Log::error('❌ [INSAM-IA COURS] Échec de la mise en cache', [
            'error' => $exception->getMessage(),
        ]);
    }
}
