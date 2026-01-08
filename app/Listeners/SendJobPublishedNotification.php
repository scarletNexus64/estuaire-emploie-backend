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

        // Récupérer tous les candidats actifs avec email vérifié
        User::where('role', 'candidate')
            ->where('is_active', true)
            ->whereNotNull('email_verified_at')
            ->chunk(100, function ($users) use ($job) {
                foreach ($users as $user) {
                    try {
                        $user->notify(new NewJobNotification($job));
                    } catch (\Throwable $e) {
                        Log::error('Erreur envoi notification nouveau job', [
                            'user_id' => $user->id,
                            'job_id' => $job->id,
                            'error' => $e->getMessage(),
                        ]);
                    }
                }
            });

        Log::info('Notifications de nouveau job envoyées', [
            'job_id' => $job->id,
            'job_title' => $job->title,
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