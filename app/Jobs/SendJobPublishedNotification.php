<?php

namespace App\Jobs;

use App\Models\Job;
use App\Services\FirebaseNotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * Job pour envoyer des notifications lors de la publication d'une offre d'emploi
 * Un seul appel au topic FCM all_users ; historique en base traité séparément.
 */
class SendJobPublishedNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $jobOffer;

    public $tries = 3;
    public $timeout = 60;
    public $backoff = [30, 120];

    public function __construct(Job $jobOffer)
    {
        $this->jobOffer = $jobOffer;
        $this->onQueue('notifications');
    }

    public function handle(FirebaseNotificationService $firebaseService): void
    {
        try {
            $jobOffer = $this->jobOffer->fresh(['company', 'category']);

            // L'offre peut avoir été retirée pendant l'attente dans la queue.
            if (!$jobOffer || $jobOffer->trashed() || !$jobOffer->isPublished()) {
                return;
            }

            $title = "Nouvelle offre : {$jobOffer->title}";
            $message = "{$jobOffer->company->name} recrute à {$jobOffer->company?->city}";

            $additionalData = [
                'job_id' => $jobOffer->id,
                'job_title' => $jobOffer->title,
                'company_name' => $jobOffer->company->name,
                'location' => $jobOffer->company?->city,
                'category' => $jobOffer->category->name ?? null,
            ];

            $sent = $firebaseService->sendToTopic('all_users', $title, $message, [
                'type' => 'job_published',
                'sent_at' => now()->toISOString(),
                ...array_map(fn ($value) => (string) ($value ?? ''), $additionalData),
            ]);

            if (!$sent) {
                throw new \RuntimeException('Firebase a refusé la diffusion de l’offre au topic all_users.');
            }

            StoreBroadcastNotifications::dispatch(
                'job_published',
                [],
                $title,
                $message,
                $additionalData,
                "job_published:{$jobOffer->id}",
            );

            Log::info('Job published notification sent', [
                'job_id' => $jobOffer->id,
                'topic' => 'all_users',
                'accepted_by_fcm' => true,
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
