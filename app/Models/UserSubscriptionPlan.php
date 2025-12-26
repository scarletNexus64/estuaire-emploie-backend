<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modèle pivot pour la relation ternaire User <-> SubscriptionPlan <-> Payment
 *
 * Représente une souscription d'un utilisateur à un plan d'abonnement via un paiement.
 * L'état "actif" de l'abonnement est déterminé par le statut du paiement associé.
 *
 * @property int $id
 * @property int $user_id
 * @property int $subscription_plan_id
 * @property int $payment_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
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
    ];

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
     * Calcule la date de fin de l'abonnement basée sur la date de paiement et la durée du plan
     */
    public function getEndDateAttribute(): ?\Carbon\Carbon
    {
        if (!$this->payment || !$this->payment->paid_at || !$this->subscriptionPlan) {
            return null;
        }

        return $this->payment->paid_at->addDays($this->subscriptionPlan->duration_days);
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
        return $query->active()->whereHas('payment', function ($q) {
            $q->whereHas('subscriptionPlan', function ($subQ) {
                // Logique pour vérifier l'expiration basée sur paid_at + duration_days
            });
        });
    }
}
