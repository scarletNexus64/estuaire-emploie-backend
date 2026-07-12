<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Modèle pour tracer les attributions manuelles de forfaits par les administrateurs
 *
 * @property int $id
 * @property int $user_id
 * @property int $subscription_plan_id
 * @property int $payment_id
 * @property int $user_subscription_plan_id
 * @property int $assigned_by_admin_id
 * @property string|null $notes
 * @property string|null $reason
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property-read User $user
 * @property-read SubscriptionPlan $subscriptionPlan
 * @property-read Payment $payment
 * @property-read UserSubscriptionPlan $userSubscriptionPlan
 * @property-read User $assignedByAdmin
 */
class ManualSubscriptionAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_type',
        'assignable_type',
        'assignable_id',
        'granted_type',
        'granted_id',
        'subscription_plan_id',
        'payment_id',
        'user_subscription_plan_id',
        'assigned_by_admin_id',
        'notes',
        'reason',
    ];

    public const ITEM_PLAN = 'plan';
    public const ITEM_PREMIUM_SERVICE = 'premium_service';
    public const ITEM_ADDON_SERVICE = 'addon_service';

    /**
     * La configuration attribuée (SubscriptionPlan, PremiumServiceConfig ou AddonServicesConfig)
     */
    public function assignable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * L'enregistrement utilisateur créé (UserSubscriptionPlan, UserPremiumService ou UserAddonService)
     */
    public function granted(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Libellé lisible de l'élément attribué (plan ou service)
     */
    public function getItemLabelAttribute(): string
    {
        if ($this->item_type === self::ITEM_PLAN && $this->subscriptionPlan) {
            return $this->subscriptionPlan->name;
        }

        return $this->assignable?->name ?? '—';
    }

    /**
     * L'utilisateur qui a reçu l'abonnement
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Le plan d'abonnement attribué
     */
    public function subscriptionPlan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class);
    }

    /**
     * Le paiement créé pour cette attribution
     */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    /**
     * L'abonnement utilisateur créé
     */
    public function userSubscriptionPlan(): BelongsTo
    {
        return $this->belongsTo(UserSubscriptionPlan::class);
    }

    /**
     * L'administrateur qui a effectué l'attribution
     */
    public function assignedByAdmin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by_admin_id');
    }

    /**
     * Scope pour obtenir les attributions d'un utilisateur spécifique
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope pour obtenir les attributions faites par un admin spécifique
     */
    public function scopeByAdmin($query, int $adminId)
    {
        return $query->where('assigned_by_admin_id', $adminId);
    }

    /**
     * Scope pour obtenir les attributions récentes
     */
    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }
}
