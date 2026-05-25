<?php

namespace App\Models;

use App\Enums\AdminRole;
use App\Models\Traits\UserFeatures;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, UserFeatures;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'fcm_token',
        'role',
        'available_roles',
        'wallet_balance', // Legacy - will be deprecated
        'freemopay_wallet_balance',
        'paypal_wallet_balance',
        'preferred_currency', // XAF, USD, EUR
        'password',
        'must_change_password',
        'profile_photo',
        'bio',
        'skills',
        'cv_path',
        'portfolio_url',
        'experience_level',
        'visibility_score',
        'is_active',
        'is_super_admin',
        'admin_role',
        'permissions',
        'last_login_at',
        'referral_code',
        'referred_by_id',
        // Champs étudiants
        'level',
        'interests',
        'specialty',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'is_super_admin' => 'boolean',
            'must_change_password' => 'boolean',
            'admin_role' => AdminRole::class,
            'permissions' => 'array',
            'available_roles' => 'array',
            'wallet_balance' => 'decimal:2', // Legacy - will be deprecated
            'freemopay_wallet_balance' => 'decimal:2',
            'paypal_wallet_balance' => 'decimal:2',
            'last_login_at' => 'datetime',
        ];
    }

    public function recruiter(): HasOne
    {
        return $this->hasOne(Recruiter::class);
    }

    public function portfolio(): HasOne
    {
        return $this->hasOne(Portfolio::class);
    }

    public function resumes(): HasMany
    {
        return $this->hasMany(Resume::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    public function conversationsAsUserOne(): HasMany
    {
        return $this->hasMany(Conversation::class, 'user_one');
    }

    public function conversationsAsUserTwo(): HasMany
    {
        return $this->hasMany(Conversation::class, 'user_two');
    }

    public function conversations()
    {
        return Conversation::where('user_one', $this->id)
            ->orWhere('user_two', $this->id);
    }
    public function presence()
    {
        return $this->hasOne(UserPresence::class);
    }
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function postedJobs(): HasMany
    {
        return $this->hasMany(Job::class, 'posted_by');
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    // Relation spécifique pour les jobs favoris (via polymorphic)
    public function favoriteJobs(): MorphToMany
    {
        return $this->morphToMany(Job::class, 'favoriteable', 'favorites')
            ->withTimestamps();
    }

    // Relation spécifique pour les services favoris (via polymorphic)
    public function favoriteQuickServices(): MorphToMany
    {
        return $this->morphToMany(\App\Models\QuickService::class, 'favoriteable', 'favorites')
            ->withTimestamps();
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable')->orderBy('created_at', 'desc');
    }

    public function unreadNotifications()
    {
        return $this->morphMany(Notification::class, 'notifiable')->whereNull('read_at');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isRecruiter(): bool
    {
        return $this->role === 'recruiter';
    }

    public function isCandidate(): bool
    {
        return $this->role === 'candidate';
    }

    public function hasPermission(string $permission): bool
    {
        // Super admin has all permissions
        if ($this->isSuperAdmin()) {
            return true;
        }

        // Check role-based permissions
        $rolePermissions = $this->getAdminRolePermissions();
        if (in_array($permission, $rolePermissions)) {
            return true;
        }

        // Check custom permissions
        return in_array($permission, $this->permissions ?? []);
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'admin' && $this->is_super_admin === true;
    }

    /**
     * Get permissions based on admin role
     */
    public function getAdminRolePermissions(): array
    {
        if (!$this->isAdmin() || !$this->admin_role) {
            return [];
        }

        return $this->admin_role->permissions();
    }

    /**
     * Get the admin role enum instance
     */
    public function getAdminRole(): ?AdminRole
    {
        return $this->admin_role;
    }

    /**
     * Check if user has a specific admin role
     */
    public function hasAdminRole(AdminRole $role): bool
    {
        return $this->admin_role === $role;
    }

    /**
     * Relation vers les souscriptions de l'utilisateur (relation ternaire via pivot)
     */
    public function userSubscriptionPlans(): HasMany
    {
        return $this->hasMany(UserSubscriptionPlan::class);
    }

    /**
     * Relation many-to-many vers les plans d'abonnement via la table pivot ternaire
     */
    public function subscriptionPlans(): BelongsToMany
    {
        return $this->belongsToMany(SubscriptionPlan::class, 'user_subscription_plans')
            ->withPivot('payment_id')
            ->withTimestamps();
    }

    /**
     * Récupère l'abonnement actif de l'utilisateur (le plus récent avec paiement complété)
     * @param string|null $forRole Filtre par rôle (candidate ou recruiter). Si null, retourne n'importe quel abonnement actif.
     */
    public function activeSubscription(?string $forRole = null): ?UserSubscriptionPlan
    {
        $query = $this->userSubscriptionPlans()
            ->active()
            ->with(['subscriptionPlan', 'payment'])
            ->latest();

        // 🎯 Filtrer par type de plan selon le rôle demandé
        if ($forRole) {
            $planType = $forRole === 'candidate' ? 'job_seeker' : 'recruiter';
            $query->whereHas('subscriptionPlan', function($q) use ($planType) {
                $q->where('plan_type', $planType);
            });
        }

        return $query->first();
    }

    /**
     * Vérifie si l'utilisateur a un abonnement actif et valide
     */
    public function hasActiveSubscription(): bool
    {
        $subscription = $this->activeSubscription();
        return $subscription && $subscription->isValid();
    }

    /**
     * Récupère le plan d'abonnement actif de l'utilisateur
     */
    public function currentPlan(): ?SubscriptionPlan
    {
        $subscription = $this->activeSubscription();
        return $subscription?->subscriptionPlan;
    }

    /**
     * Vérifie si l'utilisateur peut publier une nouvelle offre d'emploi
     * Basé sur jobs_limit du plan actif
     */
    public function canPublishJob(): bool
    {
        $plan = $this->currentPlan();

        if (!$plan) {
            return false; // Pas d'abonnement = pas de publication
        }

        // Si jobs_limit est null = illimité
        if ($plan->jobs_limit === null) {
            return true;
        }

        // Compter les jobs actifs (published + pending) de l'utilisateur
        $activeJobsCount = $this->postedJobs()
            ->whereIn('status', ['published', 'pending'])
            ->count();

        return $activeJobsCount < $plan->jobs_limit;
    }

    /**
     * Retourne le nombre de jobs restants que l'utilisateur peut publier
     */
    public function remainingJobsCount(): ?int
    {
        $plan = $this->currentPlan();

        if (!$plan) {
            return 0;
        }

        if ($plan->jobs_limit === null) {
            return null; // Illimité
        }

        $activeJobsCount = $this->postedJobs()
            ->whereIn('status', ['published', 'pending'])
            ->count();

        return max(0, $plan->jobs_limit - $activeJobsCount);
    }

    /**
     * Vérifie si l'utilisateur peut accéder aux contacts d'un candidat
     * Basé sur contacts_limit du plan actif
     */
    public function canViewContact(): bool
    {
        $plan = $this->currentPlan();

        if (!$plan) {
            return false;
        }

        // Si contacts_limit est null = illimité
        if ($plan->contacts_limit === null) {
            return true;
        }

        // Compter les contacts déjà vus ce mois-ci
        $viewedContactsCount = $this->viewedContacts()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        return $viewedContactsCount < $plan->contacts_limit;
    }

    /**
     * Retourne le nombre de contacts restants que l'utilisateur peut voir ce mois
     */
    public function remainingContactsCount(): ?int
    {
        $plan = $this->currentPlan();

        if (!$plan) {
            return 0;
        }

        if ($plan->contacts_limit === null) {
            return null; // Illimité
        }

        $viewedContactsCount = $this->viewedContacts()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        return max(0, $plan->contacts_limit - $viewedContactsCount);
    }

    /**
     * Relation vers les contacts vus par l'utilisateur (recruteur)
     */
    public function viewedContacts(): HasMany
    {
        return $this->hasMany(ViewedContact::class, 'recruiter_user_id');
    }

    /**
     * Enregistre qu'un contact a été vu
     */
    public function markContactAsViewed(int $candidateUserId): bool
    {
        // Vérifier si déjà vu
        $alreadyViewed = $this->viewedContacts()
            ->where('candidate_user_id', $candidateUserId)
            ->exists();

        if ($alreadyViewed) {
            return true; // Déjà vu, pas de nouvelle consommation
        }

        // Vérifier la limite
        if (!$this->canViewContact()) {
            return false;
        }

        // Enregistrer le nouveau contact vu
        $this->viewedContacts()->create([
            'candidate_user_id' => $candidateUserId,
        ]);

        return true;
    }

    /**
     * Vérifie si l'utilisateur peut booster des offres
     */
    public function canBoostJobs(): bool
    {
        $plan = $this->currentPlan();
        return $plan && $plan->can_boost_jobs;
    }

    /**
     * Vérifie si l'utilisateur peut voir les analytics
     */
    public function canSeeAnalytics(): bool
    {
        $plan = $this->currentPlan();
        return $plan && $plan->can_see_analytics;
    }

    /**
     * Vérifie si l'utilisateur peut accéder à la CVthèque
     */
    public function canAccessCvtheque(): bool
    {
        $plan = $this->currentPlan();
        return $plan && $plan->can_access_cvtheque;
    }

    /**
     * Vérifie si le candidat est en mode preview (sans abonnement actif)
     * Pour les candidats job_seeker uniquement
     */
    public function isCandidateInPreviewMode(): bool
    {
        // Vérifier si l'utilisateur est candidat
        if ($this->role !== 'candidate') {
            return false;
        }

        // 🔥 NOUVEAU : On filtre directement par rôle 'candidate' pour récupérer uniquement l'abonnement job_seeker
        $subscription = $this->activeSubscription('candidate');

        // Si pas d'abonnement job_seeker ou invalide = mode preview
        if (!$subscription || !$subscription->isValid()) {
            return true;
        }

        return false; // A un abonnement candidat valide = pas en preview
    }

    /**
     * Vérifie si le candidat a accès à une fonctionnalité spécifique
     */
    public function candidateHasFeature(string $featureKey): bool
    {
        // Si en mode preview, aucune feature payante
        if ($this->isCandidateInPreviewMode()) {
            return false;
        }

        // Vérifier si la feature est active
        return $this->hasFeature($featureKey, 'candidate');
    }

    /**
     * Vérifie si le candidat peut accéder à la recherche avancée
     */
    public function canAccessAdvancedSearch(): bool
    {
        // Si en mode preview, pas d'accès à la recherche avancée
        if ($this->isCandidateInPreviewMode()) {
            return false;
        }

        return $this->candidateHasFeature('free_regional_jobs');
    }

    /**
     * Vérifie si le candidat peut utiliser les templates de CV/lettre
     */
    public function canUseCVTemplates(): bool
    {
        // Si en mode preview, pas d'accès aux templates
        if ($this->isCandidateInPreviewMode()) {
            return false;
        }

        return $this->candidateHasFeature('free_cv_creation');
    }

    /**
     * Vérifie si le candidat peut accéder aux formations
     */
    public function canAccessCertifications(): bool
    {
        // Si en mode preview, pas d'accès aux formations
        if ($this->isCandidateInPreviewMode()) {
            return false;
        }

        return $this->candidateHasFeature('free_certifications');
    }

    /**
     * Retourne les infos de l'abonnement actuel pour l'API
     * 🎯 IMPORTANT: Filtre automatiquement par le rôle actuel de l'utilisateur
     */
    public function getSubscriptionInfo(): array
    {
        // 🔥 FORCE REFRESH: Invalider le cache des relations pour avoir les données les plus récentes
        // Ceci est crucial après un paiement pour éviter de retourner des données en cache
        $this->unsetRelation('userSubscriptionPlans');

        // 🎯 NOUVEAU : Récupérer les DEUX abonnements (candidat ET recruteur) si disponibles
        $recruiterSubscription = $this->activeSubscription('recruiter');
        $candidateSubscription = $this->activeSubscription('candidate');

        $currentRoleSubscription = $this->activeSubscription($this->role);
        $plan = $currentRoleSubscription?->subscriptionPlan;

        $isCandidate = $this->role === 'candidate';
        $isRecruiter = $this->role === 'recruiter';

        // Si aucun abonnement pour le rôle actuel
        if (!$plan) {
            return [
                'has_subscription' => false,
                'is_candidate_in_preview_mode' => $isCandidate,
                'plan' => null,
                'limits' => null,
                'usage' => null,
                'candidate_features' => null,
                // 🎯 Ajouter les abonnements alternatifs
                'recruiter_subscription' => $recruiterSubscription ? $this->formatSubscriptionForApi($recruiterSubscription) : null,
                'candidate_subscription' => $candidateSubscription ? $this->formatSubscriptionForApi($candidateSubscription) : null,
            ];
        }

        return [
            'has_subscription' => true,
            'is_candidate_in_preview_mode' => $isCandidate && $this->isCandidateInPreviewMode(),
            'plan' => [
                'id' => $plan->id,
                'name' => $plan->name,
                'slug' => $plan->slug,
                'plan_type' => $plan->plan_type,
            ],
            'expires_at' => $currentRoleSubscription->end_date?->toIso8601String(),
            'is_expired' => $currentRoleSubscription->isExpired(),
            'limits' => $isRecruiter ? [
                'jobs_limit' => $plan->jobs_limit,
                'contacts_limit' => $plan->contacts_limit,
                'can_boost_jobs' => $plan->can_boost_jobs,
                'can_see_analytics' => $plan->can_see_analytics,
                'can_access_cvtheque' => $plan->can_access_cvtheque,
            ] : null,
            'usage' => $isRecruiter ? [
                'jobs_used' => $this->postedJobs()->whereIn('status', ['published', 'pending'])->count(),
                'jobs_remaining' => $this->remainingJobsCount(),
                'contacts_used_this_month' => $this->viewedContacts()
                    ->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->count(),
                'contacts_remaining' => $this->remainingContactsCount(),
            ] : null,
            'candidate_features' => $isCandidate ? [
                'can_access_advanced_search' => $this->canAccessAdvancedSearch(),
                'can_use_cv_templates' => $this->canUseCVTemplates(),
                'can_access_certifications' => $this->canAccessCertifications(),
                'has_free_regional_jobs' => $this->candidateHasFeature('free_regional_jobs'),
                'cv_accessible_by_recruiters' => $this->candidateHasFeature('cv_accessible_recruiters'),
            ] : null,
            // 🎯 Ajouter les deux abonnements pour permettre au frontend de choisir
            'recruiter_subscription' => $recruiterSubscription ? $this->formatSubscriptionForApi($recruiterSubscription) : null,
            'candidate_subscription' => $candidateSubscription ? $this->formatSubscriptionForApi($candidateSubscription) : null,
        ];
    }

    /**
     * Formate un abonnement pour l'API
     */
    private function formatSubscriptionForApi($subscription): array
    {
        $plan = $subscription->subscriptionPlan;

        return [
            'id' => $subscription->id,
            'plan' => [
                'id' => $plan->id,
                'name' => $plan->name,
                'slug' => $plan->slug,
                'plan_type' => $plan->plan_type,
            ],
            'expires_at' => $subscription->end_date?->toIso8601String(),
            'is_expired' => $subscription->isExpired(),
            'is_valid' => $subscription->isValid(),
        ];
    }

    /**
     * Relation vers les transactions du wallet
     */
    public function walletTransactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class)->orderBy('created_at', 'desc');
    }

    /**
     * Retourne le solde du wallet formaté
     */
    public function getFormattedWalletBalanceAttribute(): string
    {
        return number_format($this->wallet_balance ?? 0, 0, ',', ' ') . ' FCFA';
    }

    /**
     * Vérifie si le user a assez d'argent dans son wallet
     */
    public function hasWalletBalance(float $amount): bool
    {
        return ($this->wallet_balance ?? 0) >= $amount;
    }

    /**
     * Boot method pour générer automatiquement le code parrain
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (empty($user->referral_code)) {
                $user->referral_code = static::generateUniqueReferralCode();
            }
        });
    }

    /**
     * Génère un code parrain unique
     */
    public static function generateUniqueReferralCode(): string
    {
        do {
            $code = strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
        } while (static::where('referral_code', $code)->exists());

        return $code;
    }

    /**
     * Le parrain qui a parrainé cet utilisateur
     */
    public function referrer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'referred_by_id');
    }

    /**
     * Les filleuls parrainés par cet utilisateur
     */
    public function referrals(): HasMany
    {
        return $this->hasMany(User::class, 'referred_by_id');
    }

    /**
     * Commissions gagnées en tant que parrain
     */
    public function earnedCommissions(): HasMany
    {
        return $this->hasMany(ReferralCommission::class, 'referrer_id');
    }

    /**
     * Commissions générées pour le parrain
     */
    public function generatedCommissions(): HasMany
    {
        return $this->hasMany(ReferralCommission::class, 'referred_id');
    }

    /**
     * Calcule le total des commissions gagnées
     */
    public function getTotalEarnedCommissions(): float
    {
        return $this->earnedCommissions()->sum('commission_amount');
    }

    /**
     * Relation vers les services premium de l'utilisateur
     */
    public function premiumServices(): HasMany
    {
        return $this->hasMany(UserPremiumService::class);
    }

    /**
     * Vérifie si l'utilisateur a un service premium actif
     */
    public function hasPremiumService(string $serviceSlug): bool
    {
        return $this->premiumServices()
            ->whereHas('config', function ($query) use ($serviceSlug) {
                $query->where('slug', $serviceSlug);
            })
            ->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            })
            ->exists();
    }

    /**
     * Vérifie si l'utilisateur est un étudiant (a le service Mode Étudiant actif)
     */
    public function isStudent(): bool
    {
        return $this->isCandidate() && $this->hasPremiumService('student_mode');
    }
}