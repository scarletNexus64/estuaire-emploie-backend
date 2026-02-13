<?php

namespace App\Jobs;

use App\Models\Company;
use App\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * Job Laravel pour envoyer des notifications de manière asynchrone
 * lors de la vérification d'une entreprise
 */
class SendCompanyVerifiedNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $company;

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
    public function __construct(Company $company)
    {
        $this->company = $company;

        // Définir la connexion de queue (séparée de 'default' pour éviter les conflits)
        $this->onQueue('notifications');
    }

    /**
     * Execute the job.
     */
    public function handle(NotificationService $notificationService): void
    {
        try {
            $this->company->load('recruiters.user');

            $title = "Entreprise vérifiée";
            $message = "Félicitations ! Votre entreprise {$this->company->name} a été vérifiée et approuvée.";

            Log::info('🏢 [COMPANY VERIFIED] Début envoi notifications', [
                'company_id' => $this->company->id,
                'company_name' => $this->company->name,
                'recruiters_count' => $this->company->recruiters->count(),
            ]);

            $sent = 0;
            $failed = 0;

            foreach ($this->company->recruiters as $recruiter) {
                if ($recruiter->user) {
                    try {
                        // 1. Envoyer la notification push via NotificationService
                        $success = $notificationService->sendToUser(
                            $recruiter->user,
                            $title,
                            $message,
                            'company_verified',
                            [
                                'company_id' => $this->company->id,
                                'company_name' => $this->company->name,
                            ]
                        );

                        if ($success) {
                            $sent++;
                        } else {
                            $failed++;
                        }

                        // 2. Envoyer l'email
                        $recruiter->user->notify(new \App\Notifications\CompanyVerifiedNotification($this->company));

                    } catch (\Exception $e) {
                        $failed++;
                        Log::error('❌ [COMPANY VERIFIED] Erreur envoi notification', [
                            'company_id' => $this->company->id,
                            'recruiter_id' => $recruiter->id,
                            'error' => $e->getMessage()
                        ]);
                    }
                }
            }

            Log::info('✅ [COMPANY VERIFIED] Notifications envoyées', [
                'company_id' => $this->company->id,
                'sent' => $sent,
                'failed' => $failed,
            ]);

        } catch (\Exception $e) {
            Log::error('❌ [COMPANY VERIFIED] Erreur globale', [
                'company_id' => $this->company->id,
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
        Log::error('❌ [COMPANY VERIFIED] Échec définitif', [
            'company_id' => $this->company->id,
            'error' => $exception->getMessage(),
        ]);
    }
}
