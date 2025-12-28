<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

/**
 * Modèle pivot pour la relation ternaire User <-> SubscriptionPlan <-> Payment
 *
 * Représente une souscription d'un utilisateur à un plan d'abonnement via un paiement.
 * L'état "actif" de l'abonnement est déterminé par le statut du paiement associé.
 *
 * Les limites cumulées (jobs_limit_total, contacts_limit_total) permettent de cumuler
 * les limites lors des renouvellements. Exemple: Plan Starter (5 offres) renouvelé
 * donnera jobs_limit_total = 10.
 *
 * @property int $id
 * @property int $user_id
 * @property int $subscription_plan_id
 * @property int $payment_id
 * @property int $jobs_used
 * @property int $contacts_used
 * @property int|null $jobs_limit_total Limite cumulée d'offres (null = utiliser limite du plan)
 * @property int|null $contacts_limit_total Limite cumulée de contacts (null = utiliser limite du plan)
 * @property Carbon|null $starts_at
 * @property Carbon|null $expires_at
 * @property array|null $notifications_sent
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read User $user
 * @property-read SubscriptionPlan $subscriptionPlan
 * @property-read Payment $payment
 */
class UserSubscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subscription_plan_id',
        'payment_id',
        'jobs_used',
        'contacts_used',
        'jobs_limit_total',
        'contacts_limit_total',
        'starts_at',
        'expires_at',
        'notifications_sent',
    ];

    protected function casts(): array
    {
        return [
            'jobs_used' => 'integer',
            'contacts_used' => 'integer',
            'jobs_limit_total' => 'integer',
            'contacts_limit_total' => 'integer',
            'starts_at' => 'datetime',
            'expires_at' => 'datetime',
            'notifications_sent' => 'array',
        ];
    }

    /**
     * Retourne la limite effective d'offres (cumulée ou du plan)
     */
    public function getEffectiveJobsLimit(): ?int
    {
        // Si une limite cumulée existe, l'utiliser
        if ($this->jobs_limit_total !== null) {
            return $this->jobs_limit_total;
        }

        // Sinon, utiliser la limite du plan actuel
        return $this->subscriptionPlan?->jobs_limit;
    }

    /**
     * Retourne la limite effective de contacts (cumulée ou du plan)
     */
    public function getEffectiveContactsLimit(): ?int
    {
        // Si une limite cumulée existe, l'utiliser
        if ($this->contacts_limit_total !== null) {
            return $this->contacts_limit_total;
        }

        // Sinon, utiliser la limite du plan actuel
        return $this->subscriptionPlan?->contacts_limit;
    }

    /**
     * Relation vers l'utilisateur
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation vers le plan d'abonnement
     */
    public function subscriptionPlan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class);
    }

    /**
     * Relation vers le paiement
     */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    /**
     * Vérifie si l'abonnement est actif (basé sur le statut du paiement)
     */
    public function isActive(): bool
    {
        return $this->payment && $this->payment->status === 'completed';
    }

    /**
     * Vérifie si l'abonnement est en attente de paiement
     */
    public function isPending(): bool
    {
        return $this->payment && $this->payment->status === 'pending';
    }

    /**
     * Vérifie si le paiement a échoué
     */
    public function isFailed(): bool
    {
        return $this->payment && $this->payment->status === 'failed';
    }

    /**
     * Calcule la date de fin de l'abonnement
     * Utilise expires_at si défini, sinon calcule depuis starts_at + duration_days
     */
    public function getEndDateAttribute(): ?Carbon
    {
        if ($this->expires_at) {
            return $this->expires_at;
        }

        if ($this->starts_at && $this->subscriptionPlan) {
            return $this->starts_at->copy()->addDays($this->subscriptionPlan->duration_days);
        }

        // Fallback sur l'ancienne logique (pour les anciens enregistrements)
        if ($this->payment && $this->payment->paid_at && $this->subscriptionPlan) {
            return $this->payment->paid_at->addDays($this->subscriptionPlan->duration_days);
        }

        return null;
    }

    /**
     * Vérifie si l'abonnement est expiré
     */
    public function isExpired(): bool
    {
        $endDate = $this->end_date;

        if (!$endDate) {
            return false;
        }

        return $endDate->isPast();
    }

    /**
     * Vérifie si l'abonnement est valide (actif et non expiré)
     */
    public function isValid(): bool
    {
        return $this->isActive() && !$this->isExpired();
    }

    /**
     * Retourne le nombre de jours restants avant expiration
     */
    public function getDaysRemainingAttribute(): ?int
    {
        $endDate = $this->end_date;

        if (!$endDate) {
            return null;
        }

        $diff = now()->diffInDays($endDate, false);

        return max(0, (int) $diff);
    }

    /**
     * Vérifie si l'abonnement expire bientôt (dans les 5 jours)
     */
    public function isExpiringSoon(): bool
    {
        $daysRemaining = $this->days_remaining;

        if ($daysRemaining === null) {
            return false;
        }

        return $daysRemaining <= 5 && $daysRemaining > 0;
    }

    /**
     * Vérifie si l'utilisateur peut publier une nouvelle offre
     * Utilise la limite cumulée si disponible, sinon la limite du plan
     */
    public function canPostJob(): bool
    {
        if (!$this->isValid()) {
            return false;
        }

        $limit = $this->getEffectiveJobsLimit();

        // Si limite illimitée (null)
        if ($limit === null) {
            return true;
        }

        return $this->jobs_used < $limit;
    }

    /**
     * Vérifie si l'utilisateur peut contacter un nouveau candidat
     * Utilise la limite cumulée si disponible, sinon la limite du plan
     */
    public function canContactCandidate(): bool
    {
        if (!$this->isValid()) {
            return false;
        }

        $limit = $this->getEffectiveContactsLimit();

        // Si limite illimitée (null)
        if ($limit === null) {
            return true;
        }

        return $this->contacts_used < $limit;
    }

    /**
     * Incrémente le compteur de jobs utilisés
     */
    public function incrementJobsUsed(): bool
    {
        if (!$this->canPostJob()) {
            return false;
        }

        $this->increment('jobs_used');
        return true;
    }

    /**
     * Incrémente le compteur de contacts utilisés
     */
    public function incrementContactsUsed(): bool
    {
        if (!$this->canContactCandidate()) {
            return false;
        }

        $this->increment('contacts_used');
        return true;
    }

    /**
     * Retourne le nombre de jobs restants
     * Utilise la limite cumulée si disponible
     */
    public function getJobsRemainingAttribute(): ?int
    {
        $limit = $this->getEffectiveJobsLimit();

        if ($limit === null) {
            return null; // Illimité
        }

        return max(0, $limit - $this->jobs_used);
    }

    /**
     * Retourne le nombre de contacts restants
     * Utilise la limite cumulée si disponible
     */
    public function getContactsRemainingAttribute(): ?int
    {
        $limit = $this->getEffectiveContactsLimit();

        if ($limit === null) {
            return null; // Illimité
        }

        return max(0, $limit - $this->contacts_used);
    }

    /**
     * Retourne la limite effective d'offres pour l'API
     */
    public function getJobsLimitAttribute(): ?int
    {
        return $this->getEffectiveJobsLimit();
    }

    /**
     * Retourne la limite effective de contacts pour l'API
     */
    public function getContactsLimitAttribute(): ?int
    {
        return $this->getEffectiveContactsLimit();
    }

    /**
     * Vérifie si une notification a déjà été envoyée pour un certain jour
     */
    public function hasNotificationBeenSent(int $daysBeforeExpiry): bool
    {
        $notifications = $this->notifications_sent ?? [];

        return in_array($daysBeforeExpiry, $notifications);
    }

    /**
     * Marque une notification comme envoyée
     */
    public function markNotificationSent(int $daysBeforeExpiry): void
    {
        $notifications = $this->notifications_sent ?? [];

        if (!in_array($daysBeforeExpiry, $notifications)) {
            $notifications[] = $daysBeforeExpiry;
            $this->notifications_sent = $notifications;
            $this->save();
        }
    }

    /**
     * Active l'abonnement (définit les dates de début et fin)
     * Initialise les limites cumulées avec les valeurs du plan
     */
    public function activate(): void
    {
        $plan = $this->subscriptionPlan;

        if (!$plan) {
            return;
        }

        $this->starts_at = now();
        $this->expires_at = now()->addDays($plan->duration_days);
        $this->jobs_used = 0;
        $this->contacts_used = 0;
        // Initialiser les limites cumulées avec les valeurs du plan
        $this->jobs_limit_total = $plan->jobs_limit;
        $this->contacts_limit_total = $plan->contacts_limit;
        $this->notifications_sent = [];
        $this->save();
    }

    /**
     * Renouvelle/prolonge l'abonnement avec un nouveau plan et paiement
     * - Prolonge la date d'expiration depuis la date actuelle d'expiration (si non expirée)
     *   ou depuis maintenant (si expirée)
     * - CUMULE les limites du nouveau plan (ne réinitialise pas les compteurs)
     * - Met à jour le plan et le paiement associé
     *
     * @param SubscriptionPlan $newPlan Le nouveau plan d'abonnement
     * @param Payment $newPayment Le nouveau paiement
     */
    public function renew(SubscriptionPlan $newPlan, Payment $newPayment): void
    {
        // Déterminer la date de début du renouvellement
        // Si l'abonnement est encore valide, on prolonge depuis la date d'expiration actuelle
        // Sinon, on part de maintenant
        $renewStartDate = now();

        if ($this->expires_at && !$this->isExpired()) {
            // L'abonnement est encore valide, on prolonge depuis la date d'expiration actuelle
            $renewStartDate = $this->expires_at;
        }

        // Calculer les nouvelles limites cumulées
        // On ajoute les limites du nouveau plan aux limites existantes
        $currentJobsLimit = $this->getEffectiveJobsLimit() ?? 0;
        $currentContactsLimit = $this->getEffectiveContactsLimit() ?? 0;

        // Si le nouveau plan a une limite illimitée (null), on garde illimité
        // Sinon, on cumule
        $newJobsLimit = null;
        if ($newPlan->jobs_limit !== null) {
            $newJobsLimit = $currentJobsLimit + $newPlan->jobs_limit;
        }

        $newContactsLimit = null;
        if ($newPlan->contacts_limit !== null) {
            $newContactsLimit = $currentContactsLimit + $newPlan->contacts_limit;
        }

        // Mettre à jour l'abonnement
        $this->subscription_plan_id = $newPlan->id;
        $this->payment_id = $newPayment->id;
        $this->starts_at = now(); // La date de début de ce renouvellement
        $this->expires_at = $renewStartDate->copy()->addDays($newPlan->duration_days);
        // NE PAS réinitialiser les compteurs - garder jobs_used et contacts_used
        // Cumuler les limites
        $this->jobs_limit_total = $newJobsLimit;
        $this->contacts_limit_total = $newContactsLimit;
        $this->notifications_sent = []; // Réinitialiser les notifications
        $this->save();
    }

    /**
     * Scope pour les abonnements actifs (paiement complété)
     */
    public function scopeActive($query)
    {
        return $query->whereHas('payment', function ($q) {
            $q->where('status', 'completed');
        });
    }

    /**
     * Scope pour les abonnements en attente
     */
    public function scopePending($query)
    {
        return $query->whereHas('payment', function ($q) {
            $q->where('status', 'pending');
        });
    }

    /**
     * Scope pour les abonnements d'un utilisateur spécifique
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope pour les abonnements valides (actifs et non expirés)
     */
    public function scopeValid($query)
    {
        return $query->active()
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            });
    }

    /**
     * Scope pour les abonnements expirés
     */
    public function scopeExpired($query)
    {
        return $query->active()
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', now());
    }

    /**
     * Scope pour les abonnements expirant dans X jours
     */
    public function scopeExpiringIn($query, int $days)
    {
        $targetDate = now()->addDays($days)->startOfDay();
        $nextDay = $targetDate->copy()->addDay();

        return $query->active()
            ->whereNotNull('expires_at')
            ->whereBetween('expires_at', [$targetDate, $nextDay]);
    }

    /**
     * Scope pour les abonnements expirant dans les X prochains jours
     */
    public function scopeExpiringWithin($query, int $days)
    {
        return $query->active()
            ->whereNotNull('expires_at')
            ->where('expires_at', '>', now())
            ->where('expires_at', '<=', now()->addDays($days));
    }
}
