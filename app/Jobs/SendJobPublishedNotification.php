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
 * Job pour envoyer des notifications lors de la publication d'une offre d'emploi
 * Utilise Firebase Multicast pour envoyer en masse (500 tokens/requête)
 */
class SendJobPublishedNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $jobOffer;

    public $tries = 3;
    public $timeout = 600; // 10 minutes

    public function __construct(Job $jobOffer)
    {
        $this->jobOffer = $jobOffer;
        $this->onQueue('notifications');
    }

    public function handle(NotificationService $notificationService): void
    {
        try {
            $jobOffer = $this->jobOffer->load(['company', 'category', 'location']);

            $title = "Nouvelle offre : {$jobOffer->title}";
            $message = "{$jobOffer->company->name} recrute à {$jobOffer->location->name}";

            $additionalData = [
                'job_id' => $jobOffer->id,
                'job_title' => $jobOffer->title,
                'company_name' => $jobOffer->company->name,
                'location' => $jobOffer->location->name,
                'category' => $jobOffer->category->name ?? null,
            ];

            // Récupérer tous les candidats avec token FCM
            $candidates = User::where('role', 'candidate')
                ->whereNotNull('fcm_token')
                ->get();

            Log::info('Job published notification', [
                'job_id' => $jobOffer->id,
                'candidates' => $candidates->count(),
            ]);

            // Envoyer via multicast (500 tokens par requête Firebase)
            $result = $notificationService->sendToMultipleUsers(
                $candidates,
                $title,
                $message,
                'job_published',
                $additionalData
            );

            Log::info('Job published notification sent', [
                'job_id' => $jobOffer->id,
                'sent' => $result['sent'],
                'failed' => $result['failed'],
            ]);

        } catch (\Exception $e) {
            Log::error('Job published notification failed', [
                'job_id' => $this->jobOffer->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('Job published notification permanently failed', [
            'job_id' => $this->jobOffer->id,
            'error' => $exception->getMessage(),
        ]);
    }
}
