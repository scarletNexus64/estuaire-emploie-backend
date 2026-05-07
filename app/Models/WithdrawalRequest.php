<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WithdrawalRequest extends Model
{
    protected $fillable = [
        'user_id',
        'amount',
        'paypal_email',
        'status',
        'admin_message',
        'admin_id',
        'processed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'processed_at' => 'datetime',
    ];

    // Relation avec l'utilisateur qui demande le retrait
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relation avec l'admin qui traite la demande
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
