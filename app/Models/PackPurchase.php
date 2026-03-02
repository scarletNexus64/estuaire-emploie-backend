<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackPurchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'pack_type',
        'exam_pack_id',
        'training_pack_id',
        'amount_paid',
        'currency',
        'payment_method',
        'transaction_id',
        'status',
        'purchased_at',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'amount_paid' => 'decimal:2',
            'purchased_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    /**
     * Relation : L'utilisateur qui a acheté le pack
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation : Pack d'épreuves (si pack_type = exam)
     */
    public function examPack()
    {
        return $this->belongsTo(ExamPack::class);
    }

    /**
     * Relation : Pack de formation (si pack_type = training)
     */
    public function trainingPack()
    {
        return $this->belongsTo(TrainingPack::class);
    }

    /**
     * Vérifier si l'achat est actif (non expiré)
     */
    public function isActive(): bool
    {
        if ($this->status !== 'completed') {
            return false;
        }

        if (!$this->expires_at) {
            return true; // Accès illimité
        }

        return $this->expires_at->isFuture();
    }

    /**
     * Scope pour les achats complétés
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope pour les achats actifs (non expirés)
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'completed')
                    ->where(function ($q) {
                        $q->whereNull('expires_at')
                          ->orWhere('expires_at', '>', now());
                    });
    }
}
