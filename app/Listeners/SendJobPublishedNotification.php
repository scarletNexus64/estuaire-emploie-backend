<?php

namespace App\Listeners;

use App\Events\JobPublished;
use App\Models\User;
use App\Notifications\NewJobNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendJobPublishedNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * The number of times the queued listener may be attempted.
     */
    public int $tries = 3;

    /**
     * Handle the event.
     */
    public function handle(JobPublished $event): void
    {
        $job = $event->job;

        // Charger les relations nécessaires
        $job->load(['company', 'category', 'location', 'contractType']);

        $sentCount = 0;
        $failedCount = 0;

        // Récupérer tous les candidats actifs avec email vérifié
        // Utiliser chunk(50) au lieu de chunk(100) pour éviter les timeouts
        User::where('role', 'candidate')
            ->where('is_active', true)
            ->whereNotNull('email_verified_at')
            ->chunk(50, function ($users) use ($job, &$sentCount, &$failedCount) {
                foreach ($users as $user) {
                    try {
                        $user->notify(new NewJobNotification($job));
                        $sentCount++;
                    } catch (\Throwable $e) {
                        $failedCount++;
                        Log::error('Erreur envoi notification nouveau job', [
                            'user_id' => $user->id,
                            'job_id' => $job->id,
                            'error' => $e->getMessage(),
                        ]);
                    }
                }

                // Petit délai entre les lots pour éviter de surcharger le serveur
                usleep(100000); // 100ms
            });

        Log::info('Notifications email de nouveau job envoyées', [
            'job_id' => $job->id,
            'job_title' => $job->title,
            'sent' => $sentCount,
            'failed' => $failedCount,
            'total' => $sentCount + $failedCount,
        ]);
    }

    /**
     * Handle a job failure.
     */
    public function failed(JobPublished $event, \Throwable $exception): void
    {
        Log::error('Échec de l\'envoi des notifications de nouveau job', [
            'job_id' => $event->job->id,
            'error' => $exception->getMessage(),
        ]);
    }
}