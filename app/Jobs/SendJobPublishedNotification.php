<?php

namespace App\Jobs;

use App\Models\Job;
use App\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendJobNotificationBatch;

/**
 * Job Laravel pour envoyer des notifications de manière asynchrone
 * lors de la publication d'une offre d'emploi
 */
class SendJobPublishedNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $jobOffer;

    /**
     * Nombre de tentatives
     */
    public $tries = 3;

    /**
     * Timeout en secondes
     */
    public $timeout = 300; // 5 minutes

    /**
     * Create a new job instance.
     */
    public function __construct(Job $jobOffer)
    {
        $this->jobOffer = $jobOffer;

        // Définir la connexion de queue (séparée de 'default' pour éviter les conflits avec Reverb)
        $this->onConnection('notifications');
    }

    /**
     * Execute the job.
     */
    public function handle(NotificationService $notificationService): void
    {
        try {
            $jobOffer = $this->jobOffer->load(['company', 'category', 'location']);

            Log::info('📢 [JOB PUBLISHED] Début dispatch des lots de notifications', [
                'job_id' => $jobOffer->id,
                'job_title' => $jobOffer->title,
            ]);

            // Compter le nombre total de candidats
            $totalCandidates = \App\Models\User::where('role', 'candidate')
                ->whereNotNull('fcm_token')
                ->count();

            // Définir la taille des lots (100 candidats par lot)
            $batchSize = 100;

            // Calculer le nombre de lots nécessaires
            $totalBatches = ceil($totalCandidates / $batchSize);

            Log::info('📢 [JOB PUBLISHED] Création des lots', [
                'job_id' => $jobOffer->id,
                'total_candidates' => $totalCandidates,
                'batch_size' => $batchSize,
                'total_batches' => $totalBatches,
            ]);

            // Créer un job par lot
            for ($i = 0; $i < $totalBatches; $i++) {
                SendJobNotificationBatch::dispatch($jobOffer, $i, $batchSize);
            }

            Log::info('✅ [JOB PUBLISHED] Tous les lots ont été dispatchés', [
                'job_id' => $jobOffer->id,
                'total_batches' => $totalBatches,
            ]);

        } catch (\Exception $e) {
            Log::error('❌ [JOB PUBLISHED] Erreur dispatch des lots', [
                'job_id' => $this->jobOffer->id,
                'error' => $e->getMessage(),
            ]);

            // Relancer le job si échec
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Échec définitif envoi notifications job publié', [
            'job_id' => $this->jobOffer->id,
            'error' => $exception->getMessage(),
        ]);
    }
}
