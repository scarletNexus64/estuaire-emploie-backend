<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'quick_service_id',
        'user_id',
        'message',
        'proposed_price',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'proposed_price' => 'decimal:2',
        ];
    }

    /**
     * Relation avec le service rapide
     */
    public function quickService(): BelongsTo
    {
        return $this->belongsTo(QuickService::class);
    }

    /**
     * Relation avec l'utilisateur qui répond
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Vérifier si la réponse est acceptée
     */
    public function isAccepted(): bool
    {
        return $this->status === 'accepted';
    }

    /**
     * Vérifier si la réponse est rejetée
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Vérifier si la réponse est en attente
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
}
