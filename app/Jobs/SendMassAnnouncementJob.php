<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Notification;
use App\Services\FirebaseNotificationService;
use App\Notifications\AnnouncementNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Job pour envoyer des annonces en masse de manière asynchrone
 * Utilise Firebase Multicast pour envoyer les push en lot
 */
class SendMassAnnouncementJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $title;
    protected $message;
    protected $targetGroup;
    protected $channel;
    protected $batchNumber;
    protected $batchSize;

    public $tries = 3;
    public $timeout = 600;

    public function __construct(
        string $title,
        string $message,
        string $targetGroup = 'all',
        string $channel = 'both',
        int $batchNumber = 0,
        int $batchSize = 500
    ) {
        $this->title = $title;
        $this->message = $message;
        $this->targetGroup = $targetGroup;
        $this->channel = $channel;
        $this->batchNumber = $batchNumber;
        $this->batchSize = $batchSize;

        $this->onQueue('notifications');
    }

    public function handle(FirebaseNotificationService $firebaseService): array
    {
        Log::info('Mass announcement batch', [
            'batch' => $this->batchNumber,
            'target' => $this->targetGroup,
            'channel' => $this->channel,
        ]);

        // Construire la requête utilisateurs
        $query = User::query();

        if ($this->targetGroup === 'candidates') {
            $query->where('role', 'candidate');
        } elseif ($this->targetGroup === 'recruiters') {
            $query->where('role', 'recruiter');
        }

        // Récupérer le lot d'utilisateurs
        $users = $query->skip($this->batchNumber * $this->batchSize)
            ->take($this->batchSize)
            ->get();

        if ($users->isEmpty()) {
            return ['sent' => 0, 'failed' => 0, 'errors' => []];
        }

        $sent = 0;
        $failed = 0;

        // 1. Envoyer les push via Firebase Multicast
        if ($this->channel === 'push' || $this->channel === 'both') {
            $tokens = $users->whereNotNull('fcm_token')->pluck('fcm_token')->toArray();

            if (!empty($tokens)) {
                $result = $firebaseService->sendMulticast(
                    $tokens,
                    $this->title,
                    $this->message,
                    [
                        'type' => 'announcement',
                        'sent_at' => now()->toISOString(),
                        'sender' => 'admin',
                        'target_group' => $this->targetGroup,
                    ]
                );

                $sent += $result['success'];
                $failed += $result['failure'];

                // Nettoyer les tokens invalides
                if (!empty($result['invalid_tokens'])) {
                    User::whereIn('fcm_token', $result['invalid_tokens'])
                        ->update(['fcm_token' => null]);
                }
            }
        }

        // 2. Envoyer les emails (par petits lots pour ne pas bloquer le SMTP)
        if ($this->channel === 'email' || $this->channel === 'both') {
            foreach ($users->chunk(20) as $emailBatch) {
                foreach ($emailBatch as $user) {
                    try {
                        $user->notify(new AnnouncementNotification($this->title, $this->message));
                    } catch (\Exception $e) {
                        Log::warning('Announcement email failed', [
                            'user_id' => $user->id,
                            'error' => $e->getMessage(),
                        ]);
                    }
                }
            }
        }

        // 3. Insérer les notifications BDD en batch
        $notificationRows = [];
        $now = now();
        foreach ($users as $user) {
            $notificationRows[] = [
                'type' => 'announcement',
                'notifiable_type' => User::class,
                'notifiable_id' => $user->id,
                'data' => json_encode([
                    'title' => $this->title,
                    'message' => $this->message,
                    'sent_at' => $now->toISOString(),
                    'sender' => 'admin',
                    'target_group' => $this->targetGroup,
                    'channel' => $this->channel,
                ]),
                'read_at' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        foreach (array_chunk($notificationRows, 500) as $chunk) {
            DB::table('notifications')->insert($chunk);
        }

        Log::info('Mass announcement batch done', [
            'batch' => $this->batchNumber,
            'sent' => $sent,
            'failed' => $failed,
            'users' => $users->count(),
        ]);

        return [
            'sent' => $sent,
            'failed' => $failed,
            'errors' => [],
        ];
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('Mass announcement batch permanently failed', [
            'batch' => $this->batchNumber,
            'error' => $exception->getMessage(),
        ]);
    }
}
