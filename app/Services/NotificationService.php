<?php

namespace App\Services;

use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

/**
 * Service centralisé pour gérer l'envoi de notifications push et l'enregistrement en BDD
 */
class NotificationService
{
    protected $firebaseService;

    public function __construct(FirebaseNotificationService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    /**
     * Envoie une notification à un utilisateur spécifique
     */
    public function sendToUser(User $user, string $title, string $message, string $type, array $additionalData = []): bool
    {
        $fcmSent = false;

        try {
            // 1. Envoyer via FCM si l'utilisateur a un token
            if ($user->fcm_token) {
                $data = array_merge([
                    'type' => $type,
                    'sent_at' => now()->toISOString(),
                ], $additionalData);

                try {
                    $this->firebaseService->sendToToken(
                        $user->fcm_token,
                        $title,
                        $message,
                        $data
                    );
                    $fcmSent = true;
                } catch (\Exception $fcmError) {
                    // Si le token est invalide, le supprimer
                    if (str_contains($fcmError->getMessage(), 'not found') ||
                        str_contains($fcmError->getMessage(), 'not valid') ||
                        str_contains($fcmError->getMessage(), 'Invalid registration') ||
                        str_contains($fcmError->getMessage(), 'NotRegistered')) {
                        $user->update(['fcm_token' => null]);
                    }
                }
            }

            // 2. Enregistrer dans la base de données
            Notification::create([
                'type' => $type,
                'notifiable_type' => User::class,
                'notifiable_id' => $user->id,
                'data' => [
                    'title' => $title,
                    'message' => $message,
                    'sent_at' => now()->toISOString(),
                    ...$additionalData,
                ],
                'read_at' => null,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Notification failed', [
                'user_id' => $user->id,
                'type' => $type,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Envoie des notifications push en masse via Firebase Multicast + inserts BDD par lot
     * Beaucoup plus rapide que sendToUser en boucle
     *
     * @param \Illuminate\Support\Collection $users Collection d'utilisateurs
     * @param string $title Titre de la notification
     * @param string $message Message de la notification
     * @param string $type Type de notification
     * @param array $additionalData Données supplémentaires
     * @return array ['sent' => int, 'failed' => int]
     */
    public function sendToMultipleUsers($users, string $title, string $message, string $type, array $additionalData = []): array
    {
        $sent = 0;
        $failed = 0;

        $data = array_merge([
            'type' => $type,
            'sent_at' => now()->toISOString(),
        ], $additionalData);

        $notificationPayload = [
            'title' => $title,
            'message' => $message,
            'sent_at' => now()->toISOString(),
            ...$additionalData,
        ];

        // Traiter par lots de 500 (limite Firebase multicast)
        $users->chunk(500)->each(function ($batch) use ($title, $message, $type, $data, $notificationPayload, &$sent, &$failed) {
            // 1. Collecter les tokens FCM et envoyer via multicast
            $tokensMap = [];
            foreach ($batch as $user) {
                if ($user->fcm_token) {
                    $tokensMap[$user->fcm_token] = $user->id;
                }
            }

            if (!empty($tokensMap)) {
                $result = $this->firebaseService->sendMulticast(
                    array_keys($tokensMap),
                    $title,
                    $message,
                    $data
                );

                $sent += $result['success'];
                $failed += $result['failure'];

                // Nettoyer les tokens invalides en une seule requête
                if (!empty($result['invalid_tokens'])) {
                    User::whereIn('fcm_token', $result['invalid_tokens'])
                        ->update(['fcm_token' => null]);
                }
            }

            // 2. Insérer les notifications en BDD par lot (batch insert)
            $notificationRows = [];
            $now = now();
            foreach ($batch as $user) {
                $notificationRows[] = [
                    'type' => $type,
                    'notifiable_type' => User::class,
                    'notifiable_id' => $user->id,
                    'data' => json_encode($notificationPayload),
                    'read_at' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            // Insert par chunks de 500 pour éviter les limites MySQL
            foreach (array_chunk($notificationRows, 500) as $chunk) {
                DB::table('notifications')->insert($chunk);
            }
        });

        return [
            'sent' => $sent,
            'failed' => $failed,
            'errors' => [],
        ];
    }

    /**
     * Envoie une notification à tous les candidats
     */
    public function sendToAllCandidates(string $title, string $message, string $type, array $additionalData = []): array
    {
        $candidates = User::where('role', 'candidate')
            ->whereNotNull('fcm_token')
            ->get();

        Log::info('Sending to candidates', [
            'count' => $candidates->count(),
            'type' => $type,
        ]);

        return $this->sendToMultipleUsers($candidates, $title, $message, $type, $additionalData);
    }

    /**
     * Envoie une notification à tous les utilisateurs sauf un
     */
    public function sendToAllUsersExcept(string $title, string $message, string $type, ?int $excludeUserId = null, array $additionalData = []): array
    {
        $query = User::whereIn('role', ['candidate', 'recruiter'])
            ->whereNotNull('fcm_token');

        if ($excludeUserId) {
            $query->where('id', '!=', $excludeUserId);
        }

        $users = $query->get();

        Log::info('Sending to all users', [
            'count' => $users->count(),
            'excluded' => $excludeUserId,
            'type' => $type,
        ]);

        return $this->sendToMultipleUsers($users, $title, $message, $type, $additionalData);
    }

    /**
     * Envoie une notification au recruteur d'un job
     */
    public function sendToJobRecruiter(int $jobId, string $title, string $message, string $type, array $additionalData = []): bool
    {
        try {
            $job = \App\Models\JobOffer::with('recruiter')->findOrFail($jobId);
            $recruiter = $job->recruiter;

            return $this->sendToUser($recruiter, $title, $message, $type, array_merge([
                'job_id' => $jobId,
                'job_title' => $job->title,
            ], $additionalData));
        } catch (\Exception $e) {
            Log::error('Failed to notify recruiter', [
                'job_id' => $jobId,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }
}
