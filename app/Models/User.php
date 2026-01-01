<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'fcm_token',
        'role',
        'password',
        'profile_photo',
        'bio',
        'skills',
        'cv_path',
        'portfolio_url',
        'experience_level',
        'visibility_score',
        'is_active',
        'is_super_admin',
        'permissions',
        'last_login_at',
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
            'permissions' => 'array',
            'last_login_at' => 'datetime',
        ];
    }

    public function recruiter(): HasOne
    {
        return $this->hasOne(Recruiter::class);
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

    public function favorites(): BelongsToMany
    {
        return $this->belongsToMany(Job::class, 'favorites')
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

        return in_array($permission, $this->permissions ?? []);
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'admin' && $this->is_super_admin === true;
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
     */
    public function activeSubscription(): ?UserSubscriptionPlan
    {
        return $this->userSubscriptionPlans()
            ->active()
            ->with(['subscriptionPlan', 'payment'])
            ->latest()
            ->first();
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
     * Retourne les infos de l'abonnement actuel pour l'API
     */
    public function getSubscriptionInfo(): array
    {
        $subscription = $this->activeSubscription();
        $plan = $subscription?->subscriptionPlan;

        if (!$plan) {
            return [
                'has_subscription' => false,
                'plan' => null,
                'limits' => null,
                'usage' => null,
            ];
        }

        return [
            'has_subscription' => true,
            'plan' => [
                'id' => $plan->id,
                'name' => $plan->name,
                'slug' => $plan->slug,
            ],
            'expires_at' => $subscription->end_date?->toIso8601String(),
            'is_expired' => $subscription->isExpired(),
            'limits' => [
                'jobs_limit' => $plan->jobs_limit,
                'contacts_limit' => $plan->contacts_limit,
                'can_boost_jobs' => $plan->can_boost_jobs,
                'can_see_analytics' => $plan->can_see_analytics,
                'can_access_cvtheque' => $plan->can_access_cvtheque,
            ],
            'usage' => [
                'jobs_used' => $this->postedJobs()->whereIn('status', ['published', 'pending'])->count(),
                'jobs_remaining' => $this->remainingJobsCount(),
                'contacts_used_this_month' => $this->viewedContacts()
                    ->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->count(),
                'contacts_remaining' => $this->remainingContactsCount(),
            ],
        ];
    }
}