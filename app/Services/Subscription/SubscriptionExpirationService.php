<?php

namespace App\Services\Subscription;

use App\Models\Job;
use App\Models\UserSubscriptionPlan;
use App\Services\FirebaseNotificationService;
use Illuminate\Support\Facades\Log;

/**
 * Service pour gérer l'expiration des abonnements.
 *
 * - Envoie des notifications avant expiration (J-5, J-3, J-1)
 * - Envoie une notification le jour de l'expiration (J-0)
 * - Désactive les offres d'emploi quand l'abonnement expire
 */
class SubscriptionExpirationService
{
    /**
     * Jours avant expiration où une notification doit être envoyée
     */
    private const NOTIFICATION_DAYS = [5, 3, 1, 0];

    private FirebaseNotificationService $notificationService;

    public function __construct(FirebaseNotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Exécute toutes les vérifications d'expiration.
     * À appeler quotidiennement via le scheduler.
     */
    public function checkExpirations(): array
    {
        $results = [
            'notifications_sent' => 0,
            'subscriptions_expired' => 0,
            'jobs_deactivated' => 0,
            'errors' => [],
        ];

        // 1. Envoyer les notifications de pré-expiration
        foreach (self::NOTIFICATION_DAYS as $daysBeforeExpiry) {
            try {
                $count = $this->sendExpirationNotifications($daysBeforeExpiry);
                $results['notifications_sent'] += $count;
            } catch (\Exception $e) {
                $results['errors'][] = "Notification J-{$daysBeforeExpiry}: " . $e->getMessage();
                Log::error("[SubscriptionExpiration] Error sending notifications for J-{$daysBeforeExpiry}", [
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // 2. Désactiver les offres des abonnements expirés
        try {
            $deactivated = $this->deactivateExpiredSubscriptionJobs();
            $results['jobs_deactivated'] = $deactivated['count'];
            $results['subscriptions_expired'] = $deactivated['subscriptions'];
        } catch (\Exception $e) {
            $results['errors'][] = "Deactivation: " . $e->getMessage();
            Log::error("[SubscriptionExpiration] Error deactivating jobs", [
                'error' => $e->getMessage(),
            ]);
        }

        Log::info("[SubscriptionExpiration] Daily check completed", $results);

        return $results;
    }

    /**
     * Envoie des notifications pour les abonnements expirant dans X jours.
     */
    public function sendExpirationNotifications(int $daysBeforeExpiry): int
    {
        $subscriptions = UserSubscriptionPlan::expiringIn($daysBeforeExpiry)
            ->with(['user', 'subscriptionPlan'])
            ->get();

        $notificationsSent = 0;

        foreach ($subscriptions as $subscription) {
            // Vérifier si la notification a déjà été envoyée
            if ($subscription->hasNotificationBeenSent($daysBeforeExpiry)) {
                continue;
            }

            $user = $subscription->user;
            $plan = $subscription->subscriptionPlan;

            if (!$user || !$plan) {
                continue;
            }

            // Préparer le message selon le nombre de jours
            $title = $this->getNotificationTitle($daysBeforeExpiry, $plan->name);
            $body = $this->getNotificationBody($daysBeforeExpiry, $plan->name);

            // Envoyer la notification push
            try {
                if ($user->fcm_token) {
                    $this->notificationService->sendToUser(
                        $user,
                        $title,
                        $body,
                        [
                            'type' => 'subscription_expiring',
                            'days_remaining' => $daysBeforeExpiry,
                            'subscription_id' => $subscription->id,
                            'plan_name' => $plan->name,
                            'action' => 'renew_subscription',
                        ]
                    );

                    $subscription->markNotificationSent($daysBeforeExpiry);
                    $notificationsSent++;

                    Log::info("[SubscriptionExpiration] Notification sent", [
                        'user_id' => $user->id,
                        'days_before' => $daysBeforeExpiry,
                        'plan' => $plan->name,
                    ]);
                }
            } catch (\Exception $e) {
                Log::warning("[SubscriptionExpiration] Failed to send notification", [
                    'user_id' => $user->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return $notificationsSent;
    }

    /**
     * Désactive les offres d'emploi des abonnements expirés.
     */
    public function deactivateExpiredSubscriptionJobs(): array
    {
        $expiredSubscriptions = UserSubscriptionPlan::expired()
            ->with(['user.recruiter.company'])
            ->get();

        $jobsDeactivated = 0;
        $subscriptionsProcessed = 0;

        foreach ($expiredSubscriptions as $subscription) {
            $user = $subscription->user;

            if (!$user || !$user->recruiter) {
                continue;
            }

            $companyId = $user->recruiter->company_id;

            // Désactiver toutes les offres publiées de cette entreprise
            $deactivatedCount = Job::where('company_id', $companyId)
                ->where('status', 'published')
                ->update([
                    'status' => 'expired',
                    'expired_reason' => 'subscription_expired',
                ]);

            if ($deactivatedCount > 0) {
                $jobsDeactivated += $deactivatedCount;
                $subscriptionsProcessed++;

                Log::info("[SubscriptionExpiration] Jobs deactivated", [
                    'user_id' => $user->id,
                    'company_id' => $companyId,
                    'jobs_count' => $deactivatedCount,
                ]);

                // Envoyer une notification
                if ($user->fcm_token) {
                    try {
                        $this->notificationService->sendToUser(
                            $user,
                            'Abonnement expiré',
                            "Votre abonnement a expiré. Vos {$deactivatedCount} offre(s) ont été désactivées. Renouvelez pour les réactiver.",
                            [
                                'type' => 'subscription_expired',
                                'jobs_deactivated' => $deactivatedCount,
                                'action' => 'renew_subscription',
                            ]
                        );
                    } catch (\Exception $e) {
                        Log::warning("[SubscriptionExpiration] Failed to send expiration notification", [
                            'user_id' => $user->id,
                            'error' => $e->getMessage(),
                        ]);
                    }
                }
            }
        }

        return [
            'count' => $jobsDeactivated,
            'subscriptions' => $subscriptionsProcessed,
        ];
    }

    /**
     * Réactive les offres d'emploi quand un abonnement est renouvelé.
     */
    public function reactivateJobsForSubscription(UserSubscriptionPlan $subscription): int
    {
        $user = $subscription->user;

        if (!$user || !$user->recruiter) {
            return 0;
        }

        $companyId = $user->recruiter->company_id;

        // Réactiver les offres qui ont été désactivées à cause de l'expiration
        $reactivatedCount = Job::where('company_id', $companyId)
            ->where('status', 'expired')
            ->where('expired_reason', 'subscription_expired')
            ->update([
                'status' => 'published',
                'expired_reason' => null,
            ]);

        if ($reactivatedCount > 0) {
            Log::info("[SubscriptionExpiration] Jobs reactivated", [
                'user_id' => $user->id,
                'company_id' => $companyId,
                'jobs_count' => $reactivatedCount,
            ]);
        }

        return $reactivatedCount;
    }

    /**
     * Retourne le titre de la notification selon le nombre de jours.
     */
    private function getNotificationTitle(int $daysBeforeExpiry, string $planName): string
    {
        if ($daysBeforeExpiry === 0) {
            return 'Votre abonnement expire aujourd\'hui !';
        }

        if ($daysBeforeExpiry === 1) {
            return 'Dernier jour avant expiration !';
        }

        return "Votre abonnement expire dans {$daysBeforeExpiry} jours";
    }

    /**
     * Retourne le corps de la notification selon le nombre de jours.
     */
    private function getNotificationBody(int $daysBeforeExpiry, string $planName): string
    {
        if ($daysBeforeExpiry === 0) {
            return "Votre abonnement {$planName} expire aujourd'hui. Renouvelez maintenant pour continuer à publier des offres.";
        }

        if ($daysBeforeExpiry === 1) {
            return "Votre abonnement {$planName} expire demain. Renouvelez maintenant pour éviter l'interruption de service.";
        }

        if ($daysBeforeExpiry <= 3) {
            return "Attention : votre abonnement {$planName} expire dans {$daysBeforeExpiry} jours. Pensez à le renouveler.";
        }

        return "Votre abonnement {$planName} expire dans {$daysBeforeExpiry} jours. Renouvelez avant l'expiration pour continuer sans interruption.";
    }
}
