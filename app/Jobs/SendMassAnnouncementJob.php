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
use Illuminate\Support\Facades\Log;

/**
 * Job Laravel pour envoyer des annonces en masse de manière asynchrone
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

    /**
     * Nombre de tentatives
     */
    public $tries = 3;

    /**
     * Timeout en secondes
     */
    public $timeout = 300;

    /**
     * Create a new job instance.
     */
    public function __construct(
        string $title,
        string $message,
        string $targetGroup = 'all',
        string $channel = 'both',
        int $batchNumber = 0,
        int $batchSize = 50
    ) {
        $this->title = $title;
        $this->message = $message;
        $this->targetGroup = $targetGroup;
        $this->channel = $channel;
        $this->batchNumber = $batchNumber;
        $this->batchSize = $batchSize;

        // Utiliser la queue 'notifications' pour éviter de bloquer la queue par défaut
        $this->onConnection('notifications');
    }

    /**
     * Execute the job.
     */
    public function handle(FirebaseNotificationService $firebaseService): array
    {
        Log::info('📢 [MASS ANNOUNCEMENT] Début envoi par lots', [
            'batch' => $this->batchNumber,
            'batch_size' => $this->batchSize,
            'target_group' => $this->targetGroup,
            'channel' => $this->channel,
        ]);

        // Construire la requête
        $query = User::query();

        // Filtrer par groupe cible
        if ($this->targetGroup === 'candidates') {
            $query->where('role', 'candidate');
        } elseif ($this->targetGroup === 'recruiters') {
            $query->where('role', 'recruiter');
        }

        // Si on envoie du push, on a besoin du token FCM
        if ($this->channel === 'push' || $this->channel === 'both') {
            $query->whereNotNull('fcm_token');
        }

        // Récupérer le lot d'utilisateurs
        $users = $query->skip($this->batchNumber * $this->batchSize)
            ->take($this->batchSize)
            ->get();

        $sent = 0;
        $failed = 0;
        $errors = [];

        foreach ($users as $user) {
            try {
                $userSent = false;
                $userFailed = false;

                // 1. Envoyer la notification Push si demandé
                if ($this->channel === 'push' || $this->channel === 'both') {
                    if ($user->fcm_token) {
                        try {
                            $firebaseService->sendToToken(
                                $user->fcm_token,
                                $this->title,
                                $this->message,
                                [
                                    'type' => 'announcement',
                                    'sent_at' => now()->toISOString(),
                                    'sender' => 'admin',
                                    'target_group' => $this->targetGroup,
                                ]
                            );
                            $userSent = true;
                        } catch (\Exception $e) {
                            Log::warning('❌ [MASS ANNOUNCEMENT] Erreur FCM', [
                                'user_id' => $user->id,
                                'error' => $e->getMessage(),
                            ]);

                            // Supprimer le token si invalide
                            if (str_contains($e->getMessage(), 'Requested entity was not found') ||
                                str_contains($e->getMessage(), 'registration token is not valid') ||
                                str_contains($e->getMessage(), 'Invalid registration')) {
                                $user->update(['fcm_token' => null]);
                            }

                            $userFailed = true;
                        }
                    }
                }

                // 2. Envoyer l'email si demandé
                if ($this->channel === 'email' || $this->channel === 'both') {
                    try {
                        $user->notify(new AnnouncementNotification($this->title, $this->message));
                        $userSent = true;
                    } catch (\Exception $e) {
                        Log::error('❌ [MASS ANNOUNCEMENT] Erreur email', [
                            'user_id' => $user->id,
                            'error' => $e->getMessage(),
                        ]);
                        $userFailed = true;
                    }
                }

                // 3. Enregistrer dans la BDD
                Notification::create([
                    'type' => 'announcement',
                    'notifiable_type' => User::class,
                    'notifiable_id' => $user->id,
                    'data' => [
                        'title' => $this->title,
                        'message' => $this->message,
                        'sent_at' => now()->toISOString(),
                        'sender' => 'admin',
                        'target_group' => $this->targetGroup,
                        'channel' => $this->channel,
                    ],
                    'read_at' => null,
                ]);

                if ($userSent && !$userFailed) {
                    $sent++;
                } else {
                    $failed++;
                    $errors[] = [
                        'user_id' => $user->id,
                        'user_name' => $user->name,
                        'error' => 'Échec d\'envoi',
                    ];
                }
            } catch (\Exception $e) {
                $failed++;
                $errors[] = [
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'error' => $e->getMessage(),
                ];

                Log::error('❌ [MASS ANNOUNCEMENT] Erreur traitement utilisateur', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        Log::info('✅ [MASS ANNOUNCEMENT] Lot envoyé', [
            'batch' => $this->batchNumber,
            'sent' => $sent,
            'failed' => $failed,
        ]);

        return [
            'sent' => $sent,
            'failed' => $failed,
            'errors' => $errors,
        ];
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('❌ [MASS ANNOUNCEMENT] Échec définitif du job', [
            'batch' => $this->batchNumber,
            'error' => $exception->getMessage(),
        ]);
    }
}
