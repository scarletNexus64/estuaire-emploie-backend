<?php

namespace App\Jobs;

use App\Models\Job;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * Job Laravel pour envoyer des notifications par lots
 * lors de la publication d'une offre d'emploi
 */
class SendJobNotificationBatch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $jobOffer;
    protected $batchNumber;
    protected $batchSize;

    /**
     * Nombre de tentatives
     */
    public $tries = 3;

    /**
     * Timeout en secondes
     */
    public $timeout = 120; // 2 minutes par lot

    /**
     * Create a new job instance.
     */
    public function __construct(Job $jobOffer, int $batchNumber, int $batchSize = 100)
    {
        $this->jobOffer = $jobOffer;
        $this->batchNumber = $batchNumber;
        $this->batchSize = $batchSize;

        // Définir la connexion de queue
        $this->onQueue('notifications');
    }

    /**
     * Execute the job.
     */
    public function handle(NotificationService $notificationService): void
    {
        try {
            $jobOffer = $this->jobOffer->load(['company', 'category']);

            Log::info('📢 [JOB NOTIFICATION BATCH] Début envoi par lots', [
                'job_id' => $jobOffer->id,
                'batch_number' => $this->batchNumber,
                'batch_size' => $this->batchSize,
            ]);

            // Récupérer les candidats pour ce lot
            $candidates = User::where('role', 'candidate')
                ->whereNotNull('fcm_token')
                ->skip($this->batchNumber * $this->batchSize)
                ->take($this->batchSize)
                ->get();

            if ($candidates->isEmpty()) {
                Log::info('ℹ️ [JOB NOTIFICATION BATCH] Aucun candidat dans ce lot', [
                    'job_id' => $jobOffer->id,
                    'batch_number' => $this->batchNumber,
                ]);
                return;
            }

            $title = "Nouvelle offre : {$jobOffer->title}";
            $message = "{$jobOffer->company->name} recrute à {$jobOffer->company?->city}";

            $sent = 0;
            $failed = 0;

            foreach ($candidates as $candidate) {
                try {
                    $success = $notificationService->sendToUser(
                        $candidate,
                        $title,
                        $message,
                        'job_published',
                        [
                            'job_id' => $jobOffer->id,
                            'job_title' => $jobOffer->title,
                            'company_name' => $jobOffer->company->name,
                            'location' => $jobOffer->company?->city,
                            'category' => $jobOffer->category->name ?? null,
                        ]
                    );

                    if ($success) {
                        $sent++;
                    } else {
                        $failed++;
                    }
                } catch (\Exception $e) {
                    $failed++;
                    Log::error('❌ [JOB NOTIFICATION BATCH] Erreur envoi à candidat', [
                        'job_id' => $jobOffer->id,
                        'candidate_id' => $candidate->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            Log::info('✅ [JOB NOTIFICATION BATCH] Lot envoyé', [
                'job_id' => $jobOffer->id,
                'batch_number' => $this->batchNumber,
                'sent' => $sent,
                'failed' => $failed,
                'total_in_batch' => $candidates->count(),
            ]);

        } catch (\Exception $e) {
            Log::error('❌ [JOB NOTIFICATION BATCH] Erreur globale', [
                'job_id' => $this->jobOffer->id,
                'batch_number' => $this->batchNumber,
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
        Log::error('❌ [JOB NOTIFICATION BATCH] Échec définitif', [
            'job_id' => $this->jobOffer->id,
            'batch_number' => $this->batchNumber,
            'error' => $exception->getMessage(),
        ]);
    }
}
