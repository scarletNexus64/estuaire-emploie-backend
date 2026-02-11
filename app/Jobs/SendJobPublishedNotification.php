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

            $title = "Nouvelle offre : {$jobOffer->title}";
            $message = "{$jobOffer->company->name} recrute à {$jobOffer->location->name}";

            Log::info('Début envoi notifications pour job publié', [
                'job_id' => $jobOffer->id,
                'job_title' => $jobOffer->title,
            ]);

            // Envoi à tous les candidats de manière sécurisée (par lots)
            $result = $notificationService->sendToAllCandidates(
                $title,
                $message,
                'job_published',
                [
                    'job_id' => $jobOffer->id,
                    'job_title' => $jobOffer->title,
                    'company_name' => $jobOffer->company->name,
                    'location' => $jobOffer->location->name,
                    'category' => $jobOffer->category->name ?? null,
                ]
            );

            Log::info('Notifications job publié envoyées', [
                'job_id' => $jobOffer->id,
                'sent' => $result['sent'],
                'failed' => $result['failed'],
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur envoi notifications job publié', [
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
