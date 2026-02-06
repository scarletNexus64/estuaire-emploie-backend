<?php

namespace App\Models\Traits;

use App\Models\UserAddonService;
use App\Models\SubscriptionPlan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

/**
 * Trait pour la gestion des features multi-rôles des utilisateurs
 *
 * Ce trait gère:
 * - Les rôles multiples (candidat + recruteur)
 * - Les features par rôle (candidate_features, recruiter_features)
 * - La synchronisation des features depuis les plans et services
 * - La vérification des permissions
 *
 * Structure permissions JSON:
 * {
 *   "available_roles": ["candidate", "recruiter"],
 *   "candidate_features": {
 *     "cv_premium": { "enabled": true, "expires_at": "..." },
 *     "verified_badge": { "enabled": true },
 *     "sms_alerts": { "enabled": true, "expires_at": "..." }
 *   },
 *   "recruiter_features": {
 *     "push_notifications": { "enabled": true, "expires_at": "..." },
 *     "boost_whatsapp": { "enabled": true, "uses_remaining": 3 },
 *     "access_cvtheque": { "enabled": true }
 *   }
 * }
 */
trait UserFeatures
{
    /**
     * Retourne les rôles disponibles pour cet utilisateur
     */
    public function getAvailableRoles(): array
    {
        return $this->available_roles ?? [$this->role];
    }

    /**
     * Vérifie si l'utilisateur a un rôle spécifique disponible
     */
    public function hasRole(string $role): bool
    {
        return in_array($role, $this->getAvailableRoles());
    }

    /**
     * Ajoute un rôle aux rôles disponibles
     */
    public function addAvailableRole(string $role): void
    {
        $availableRoles = $this->getAvailableRoles();

        if (!in_array($role, $availableRoles)) {
            $availableRoles[] = $role;
            $this->available_roles = $availableRoles;
            $this->save();

            Log::info("[UserFeatures] Role '{$role}' added to user {$this->id}");
        }
    }

    /**
     * Change le rôle actif de l'utilisateur
     */
    public function switchToRole(string $role): bool
    {
        if (!$this->hasRole($role)) {
            return false;
        }

        $this->role = $role;
        $this->save();

        Log::info("[UserFeatures] User {$this->id} switched to role '{$role}'");

        return true;
    }

    /**
     * Retourne la clé de features selon le rôle
     */
    protected function getFeaturesKey(?string $role = null): string
    {
        $role = $role ?? $this->role;

        return match($role) {
            'candidate' => 'candidate_features',
            'recruiter' => 'recruiter_features',
            default => 'features',
        };
    }

    /**
     * Retourne toutes les features pour un rôle donné
     */
    public function getFeaturesForRole(?string $role = null): array
    {
        $permissions = $this->permissions ?? [];
        $featuresKey = $this->getFeaturesKey($role);

        return $permissions[$featuresKey] ?? [];
    }

    /**
     * Vérifie si l'utilisateur a une feature active pour son rôle actuel
     */
    public function hasFeature(string $featureKey, ?string $role = null): bool
    {
        $features = $this->getFeaturesForRole($role);

        if (!isset($features[$featureKey])) {
            return false;
        }

        $feature = $features[$featureKey];

        // Vérifier si enabled
        if (!($feature['enabled'] ?? false)) {
            return false;
        }

        // Vérifier expiration
        if (isset($feature['expires_at']) && $feature['expires_at']) {
            $expiresAt = Carbon::parse($feature['expires_at']);
            if ($expiresAt->isPast()) {
                return false;
            }
        }

        // Vérifier uses_remaining
        if (isset($feature['uses_remaining'])) {
            if ($feature['uses_remaining'] <= 0) {
                return false;
            }
        }

        return true;
    }

    /**
     * Active une feature pour un rôle spécifique
     */
    public function grantFeature(
        string $featureKey,
        string $source,
        ?Carbon $expiresAt = null,
        ?int $usesRemaining = null,
        array $metadata = [],
        ?string $role = null
    ): void {
        $permissions = $this->permissions ?? [];
        $featuresKey = $this->getFeaturesKey($role);
        $features = $permissions[$featuresKey] ?? [];

        $features[$featureKey] = array_filter([
            'enabled' => true,
            'source' => $source,
            'granted_at' => now()->toIso8601String(),
            'expires_at' => $expiresAt?->toIso8601String(),
            'uses_remaining' => $usesRemaining,
            ...$metadata
        ], fn($value) => $value !== null);

        $permissions[$featuresKey] = $features;
        $this->permissions = $permissions;
        $this->save();

        Log::info("[UserFeatures] Feature '{$featureKey}' granted to user {$this->id} for role '{$role}'", [
            'source' => $source,
            'expires_at' => $expiresAt?->toIso8601String(),
        ]);
    }

    /**
     * Retire une feature
     */
    public function revokeFeature(string $featureKey, ?string $role = null): void
    {
        $permissions = $this->permissions ?? [];
        $featuresKey = $this->getFeaturesKey($role);
        $features = $permissions[$featuresKey] ?? [];

        if (isset($features[$featureKey])) {
            unset($features[$featureKey]);
            $permissions[$featuresKey] = $features;
            $this->permissions = $permissions;
            $this->save();

            Log::info("[UserFeatures] Feature '{$featureKey}' revoked from user {$this->id} for role '{$role}'");
        }
    }

    /**
     * Consomme une utilisation d'une feature
     */
    public function consumeFeatureUse(string $featureKey, ?string $role = null): bool
    {
        if (!$this->hasFeature($featureKey, $role)) {
            return false;
        }

        $permissions = $this->permissions ?? [];
        $featuresKey = $this->getFeaturesKey($role);
        $features = $permissions[$featuresKey] ?? [];

        if (!isset($features[$featureKey]['uses_remaining'])) {
            // Illimité
            return true;
        }

        if ($features[$featureKey]['uses_remaining'] <= 0) {
            return false;
        }

        $features[$featureKey]['uses_remaining']--;

        // Si épuisé, désactiver
        if ($features[$featureKey]['uses_remaining'] <= 0) {
            $features[$featureKey]['enabled'] = false;
        }

        $permissions[$featuresKey] = $features;
        $this->permissions = $permissions;
        $this->save();

        Log::info("[UserFeatures] Feature '{$featureKey}' use consumed for user {$this->id}", [
            'remaining' => $features[$featureKey]['uses_remaining'],
        ]);

        return true;
    }

    /**
     * Synchronise les features depuis le plan d'abonnement actif
     */
    public function syncFeaturesFromSubscription(?string $role = null): void
    {
        $role = $role ?? $this->role;

        // Pour un recruteur
        if ($role === 'recruiter') {
            $plan = $this->currentPlan();

            if (!$plan) {
                return;
            }

            $subscription = $this->activeSubscription();
            $expiresAt = $subscription?->expires_at;

            // 🎯 Synchroniser TOUTES les features du JSON plan.features
            if ($plan->features && is_array($plan->features)) {
                foreach ($plan->features as $featureKey => $enabled) {
                    // Activer seulement si enabled === true ou 1
                    if ($enabled === true || $enabled === 1) {
                        $this->grantFeature(
                            $featureKey,
                            "subscription_plan:{$plan->id}",
                            $expiresAt,
                            null,
                            ['plan_name' => $plan->name],
                            'recruiter'
                        );
                    }
                }

                Log::info("[UserFeatures] JSON features synced from subscription", [
                    'user_id' => $this->id,
                    'plan' => $plan->name,
                    'features_count' => count(array_filter($plan->features)),
                ]);
            }

            // 🎯 Synchroniser les champs booléens du plan (pour compatibilité)
            if ($plan->can_access_cvtheque) {
                $this->grantFeature('can_access_cvtheque', "subscription_plan:{$plan->id}", $expiresAt, null, [], 'recruiter');
            }

            if ($plan->can_boost_jobs) {
                $this->grantFeature('can_boost_jobs', "subscription_plan:{$plan->id}", $expiresAt, null, [], 'recruiter');
            }

            if ($plan->can_see_analytics) {
                $this->grantFeature('can_see_analytics', "subscription_plan:{$plan->id}", $expiresAt, null, [], 'recruiter');
            }

            if ($plan->priority_support) {
                $this->grantFeature('priority_support', "subscription_plan:{$plan->id}", $expiresAt, null, [], 'recruiter');
            }

            if ($plan->featured_company_badge) {
                $this->grantFeature('featured_company_badge', "subscription_plan:{$plan->id}", $expiresAt, null, [], 'recruiter');
            }

            if ($plan->custom_company_page) {
                $this->grantFeature('custom_company_page', "subscription_plan:{$plan->id}", $expiresAt, null, [], 'recruiter');
            }

            // Ajouter le rôle recruiter s'il ne l'a pas déjà
            $this->addAvailableRole('recruiter');

            Log::info("[UserFeatures] Recruiter features synced from subscription for user {$this->id}", [
                'plan' => $plan->name,
                'expires_at' => $expiresAt?->toIso8601String(),
            ]);
        }

        // Pour un candidat
        if ($role === 'candidate') {
            // Récupérer l'abonnement candidat actif
            $subscription = $this->activeSubscription();

            if (!$subscription) {
                // Pas d'abonnement, pas de features spéciales
                $this->addAvailableRole('candidate');
                return;
            }

            $plan = $subscription->subscriptionPlan;

            // Vérifier que c'est bien un plan job_seeker
            if (!$plan || $plan->plan_type !== 'job_seeker') {
                $this->addAvailableRole('candidate');
                return;
            }

            $expiresAt = $subscription->expires_at;

            // 🎯 Synchroniser TOUTES les features du JSON plan.features
            if ($plan->features && is_array($plan->features)) {
                foreach ($plan->features as $featureKey => $enabled) {
                    // Activer seulement si enabled === true ou 1
                    if ($enabled === true || $enabled === 1) {
                        $this->grantFeature(
                            $featureKey,
                            "subscription_plan:{$plan->id}",
                            $expiresAt,
                            null,
                            ['plan_name' => $plan->name],
                            'candidate'
                        );
                    }
                }

                Log::info("[UserFeatures] Candidate features synced from subscription", [
                    'user_id' => $this->id,
                    'plan' => $plan->name,
                    'features_count' => count(array_filter($plan->features)),
                ]);
            }

            // Ajouter le rôle candidate s'il ne l'a pas déjà
            $this->addAvailableRole('candidate');

            Log::info("[UserFeatures] Candidate features synced from subscription for user {$this->id}", [
                'plan' => $plan->name,
                'expires_at' => $expiresAt?->toIso8601String(),
            ]);
        }
    }

    /**
     * Synchronise les features depuis un service additionnel acheté
     */
    public function syncAddonServiceFeatures(UserAddonService $userAddonService): void
    {
        $service = $userAddonService->addonServiceConfig;

        if (!$service) {
            return;
        }

        $featureKey = $service->getFeatureKey();
        $expiresAt = $userAddonService->expires_at;
        $usesRemaining = $userAddonService->uses_remaining;
        $metadata = [];

        // Ajouter metadata spécifique selon le type
        if ($service->isBoost()) {
            $metadata['boost_multiplier'] = $userAddonService->getBoostMultiplier();
            $metadata['related_job_id'] = $userAddonService->related_job_id;
        }

        // Accorder la feature (toujours pour le rôle recruiter)
        $this->grantFeature(
            $featureKey,
            "addon_service:{$service->id}",
            $expiresAt,
            $usesRemaining,
            $metadata,
            'recruiter'
        );

        Log::info("[UserFeatures] Addon service feature synced for user {$this->id}", [
            'service' => $service->name,
            'feature_key' => $featureKey,
            'expires_at' => $expiresAt?->toIso8601String(),
            'uses_remaining' => $usesRemaining,
        ]);
    }

    /**
     * Retire les features d'un service additionnel
     */
    public function removeAddonServiceFeatures(UserAddonService $userAddonService): void
    {
        $service = $userAddonService->addonServiceConfig;

        if (!$service) {
            return;
        }

        $featureKey = $service->getFeatureKey();
        $this->revokeFeature($featureKey, 'recruiter');

        Log::info("[UserFeatures] Addon service feature removed for user {$this->id}", [
            'service' => $service->name,
            'feature_key' => $featureKey,
        ]);
    }

    /**
     * Synchronise TOUTES les features depuis tous les plans et services actifs
     */
    public function syncAllFeatures(): void
    {
        // Synchroniser depuis le plan recruteur
        if ($this->hasRole('recruiter')) {
            $this->syncFeaturesFromSubscription('recruiter');

            // Synchroniser depuis les services additionnels actifs
            $activeAddonServices = $this->userAddonServices()->active()->get();
            foreach ($activeAddonServices as $addonService) {
                $this->syncAddonServiceFeatures($addonService);
            }
        }

        // Synchroniser depuis les services premium candidat
        if ($this->hasRole('candidate')) {
            $this->syncFeaturesFromSubscription('candidate');
            // TODO: Sync from user_premium_services
        }

        Log::info("[UserFeatures] All features synced for user {$this->id}");
    }

    /**
     * Retourne un résumé des features actives pour l'API
     */
    public function getFeaturesInfo(?string $role = null): array
    {
        $role = $role ?? $this->role;
        $features = $this->getFeaturesForRole($role);
        $activeFeatures = [];

        foreach ($features as $key => $feature) {
            if ($this->hasFeature($key, $role)) {
                $activeFeatures[$key] = [
                    'enabled' => true,
                    'expires_at' => $feature['expires_at'] ?? null,
                    'uses_remaining' => $feature['uses_remaining'] ?? null,
                    'source' => $feature['source'] ?? 'unknown',
                ];
            }
        }

        return [
            'role' => $role,
            'available_roles' => $this->getAvailableRoles(),
            'active_features' => $activeFeatures,
            'features_count' => count($activeFeatures),
        ];
    }

    /**
     * Relation vers les services additionnels achetés
     */
    public function userAddonServices()
    {
        return $this->hasMany(UserAddonService::class);
    }
}
