<?php

namespace App\Services;

use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\Log;

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
     *
     * @param User $user Utilisateur destinataire
     * @param string $title Titre de la notification
     * @param string $message Message de la notification
     * @param string $type Type de notification (job, application, message, subscription, etc.)
     * @param array $additionalData Données supplémentaires (job_id, application_id, etc.)
     * @return bool Succès de l'envoi
     */
    public function sendToUser(User $user, string $title, string $message, string $type, array $additionalData = []): bool
    {
        try {
            // 1. Envoyer via FCM si l'utilisateur a un token
            if ($user->fcm_token) {
                $data = array_merge([
                    'type' => $type,
                    'sent_at' => now()->toISOString(),
                ], $additionalData);

                $this->firebaseService->sendToToken(
                    $user->fcm_token,
                    $title,
                    $message,
                    $data
                );
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

            Log::info('Notification envoyée', [
                'user_id' => $user->id,
                'type' => $type,
                'title' => $title,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Erreur envoi notification', [
                'user_id' => $user->id,
                'type' => $type,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Envoie une notification à plusieurs utilisateurs (par lots pour éviter erreurs 500)
     *
     * @param \Illuminate\Support\Collection $users Collection d'utilisateurs
     * @param string $title Titre de la notification
     * @param string $message Message de la notification
     * @param string $type Type de notification
     * @param array $additionalData Données supplémentaires
     * @param int $batchSize Taille des lots (par défaut 50)
     * @return array ['sent' => int, 'failed' => int, 'errors' => array]
     */
    public function sendToMultipleUsers($users, string $title, string $message, string $type, array $additionalData = [], int $batchSize = 50): array
    {
        $sent = 0;
        $failed = 0;
        $errors = [];

        // Traiter par lots pour éviter les erreurs 500
        $users->chunk($batchSize)->each(function ($batch) use ($title, $message, $type, $additionalData, &$sent, &$failed, &$errors) {
            foreach ($batch as $user) {
                $success = $this->sendToUser($user, $title, $message, $type, $additionalData);

                if ($success) {
                    $sent++;
                } else {
                    $failed++;
                    $errors[] = [
                        'user_id' => $user->id,
                        'user_name' => $user->name,
                    ];
                }
            }

            // Petit délai entre les lots pour éviter de surcharger le serveur
            usleep(100000); // 100ms
        });

        return [
            'sent' => $sent,
            'failed' => $failed,
            'errors' => $errors,
        ];
    }

    /**
     * Envoie une notification à tous les candidats
     *
     * @param string $title Titre de la notification
     * @param string $message Message de la notification
     * @param string $type Type de notification
     * @param array $additionalData Données supplémentaires
     * @return array Résultat de l'envoi
     */
    public function sendToAllCandidates(string $title, string $message, string $type, array $additionalData = []): array
    {
        $candidates = User::where('role', 'candidate')
            ->whereNotNull('fcm_token')
            ->get();

        return $this->sendToMultipleUsers($candidates, $title, $message, $type, $additionalData);
    }

    /**
     * Envoie une notification au recruteur d'un job
     *
     * @param int $jobId ID du job
     * @param string $title Titre de la notification
     * @param string $message Message de la notification
     * @param string $type Type de notification
     * @param array $additionalData Données supplémentaires
     * @return bool Succès de l'envoi
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
            Log::error('Erreur envoi notification au recruteur', [
                'job_id' => $jobId,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }
}
