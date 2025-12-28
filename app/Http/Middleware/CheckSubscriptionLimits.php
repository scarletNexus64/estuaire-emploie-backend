<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware pour vérifier les limites d'abonnement d'un recruteur.
 *
 * Utilisations:
 * - 'subscription:valid' - Vérifie que l'abonnement est valide (actif et non expiré)
 * - 'subscription:can_post_job' - Vérifie que le recruteur peut publier une offre
 * - 'subscription:can_contact' - Vérifie que le recruteur peut contacter un candidat
 * - 'subscription:feature_cvtheque' - Vérifie l'accès à la CVthèque
 * - 'subscription:feature_analytics' - Vérifie l'accès aux analytics
 * - 'subscription:feature_boost' - Vérifie l'accès au boost d'offres
 */
class CheckSubscriptionLimits
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $check  Le type de vérification à effectuer
     */
    public function handle(Request $request, Closure $next, string $check = 'valid'): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Authentification requise',
                'error_code' => 'UNAUTHENTICATED',
            ], 401);
        }

        // Vérifier que l'utilisateur est un recruteur
        if ($user->role !== 'recruiter') {
            return response()->json([
                'success' => false,
                'message' => 'Cette action est réservée aux recruteurs',
                'error_code' => 'NOT_RECRUITER',
            ], 403);
        }

        // Récupérer l'abonnement actif
        $subscription = $user->activeSubscription();

        if (!$subscription) {
            return response()->json([
                'success' => false,
                'message' => 'Aucun abonnement actif. Veuillez souscrire à un plan.',
                'error_code' => 'NO_SUBSCRIPTION',
                'redirect_to' => '/subscription-plans',
            ], 403);
        }

        // Vérifier si l'abonnement est expiré
        if ($subscription->isExpired()) {
            return response()->json([
                'success' => false,
                'message' => 'Votre abonnement a expiré. Veuillez le renouveler.',
                'error_code' => 'SUBSCRIPTION_EXPIRED',
                'expires_at' => $subscription->expires_at?->toIso8601String(),
                'redirect_to' => '/subscription-plans',
            ], 403);
        }

        // Effectuer la vérification spécifique demandée
        switch ($check) {
            case 'valid':
                // Déjà vérifié ci-dessus
                break;

            case 'can_post_job':
                if (!$subscription->canPostJob()) {
                    $plan = $subscription->subscriptionPlan;
                    // Utiliser les limites effectives (cumulées) au lieu des limites du plan
                    $effectiveJobsLimit = $subscription->getEffectiveJobsLimit();
                    return response()->json([
                        'success' => false,
                        'message' => "Vous avez atteint la limite de {$effectiveJobsLimit} offres. Passez à un plan supérieur pour publier plus d'offres.",
                        'error_code' => 'JOBS_LIMIT_REACHED',
                        'usage' => [
                            'jobs_used' => $subscription->jobs_used,
                            'jobs_limit' => $effectiveJobsLimit,
                        ],
                        'limit' => $effectiveJobsLimit,
                        'used' => $subscription->jobs_used,
                        'upgrade_required' => true,
                        'redirect_to' => '/subscription-plans',
                    ], 403);
                }
                break;

            case 'can_contact':
                if (!$subscription->canContactCandidate()) {
                    $plan = $subscription->subscriptionPlan;
                    // Utiliser les limites effectives (cumulées) au lieu des limites du plan
                    $effectiveContactsLimit = $subscription->getEffectiveContactsLimit();
                    return response()->json([
                        'success' => false,
                        'message' => "Vous avez atteint la limite de {$effectiveContactsLimit} contacts. Passez à un plan supérieur pour contacter plus de candidats.",
                        'error_code' => 'CONTACT_LIMIT_REACHED',
                        'usage' => [
                            'contacts_used' => $subscription->contacts_used,
                            'contacts_limit' => $effectiveContactsLimit,
                        ],
                        'limit' => $effectiveContactsLimit,
                        'used' => $subscription->contacts_used,
                        'upgrade_required' => true,
                        'redirect_to' => '/subscription-plans',
                    ], 403);
                }
                break;

            case 'feature_cvtheque':
                $plan = $subscription->subscriptionPlan;
                if (!$plan->can_access_cvtheque) {
                    return response()->json([
                        'success' => false,
                        'message' => "L'accès à la CVthèque n'est pas inclus dans votre plan {$plan->name}.",
                        'error_code' => 'FEATURE_NOT_AVAILABLE',
                        'feature' => 'cvtheque',
                        'upgrade_available' => true,
                        'redirect_to' => '/subscription-plans',
                    ], 403);
                }
                break;

            case 'feature_analytics':
                $plan = $subscription->subscriptionPlan;
                if (!$plan->can_see_analytics) {
                    return response()->json([
                        'success' => false,
                        'message' => "L'accès aux statistiques n'est pas inclus dans votre plan {$plan->name}.",
                        'error_code' => 'FEATURE_NOT_AVAILABLE',
                        'feature' => 'analytics',
                        'upgrade_available' => true,
                        'redirect_to' => '/subscription-plans',
                    ], 403);
                }
                break;

            case 'feature_boost':
                $plan = $subscription->subscriptionPlan;
                if (!$plan->can_boost_jobs) {
                    return response()->json([
                        'success' => false,
                        'message' => "Le boost d'offres n'est pas inclus dans votre plan {$plan->name}.",
                        'error_code' => 'FEATURE_NOT_AVAILABLE',
                        'feature' => 'boost',
                        'upgrade_available' => true,
                        'redirect_to' => '/subscription-plans',
                    ], 403);
                }
                break;

            default:
                // Vérification inconnue, on laisse passer
                break;
        }

        return $next($request);
    }
}
