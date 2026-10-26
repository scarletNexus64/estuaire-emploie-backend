<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InsamtechsUsbOrder extends Model
{
    protected $fillable = [
        'user_id', 'formations', 'customer_name', 'phone', 'address',
        'city', 'notes', 'unit_price_xaf', 'delivery_price_xaf',
        'total_price_xaf', 'status', 'admin_reply', 'replied_at', 'replied_by',
    ];

    protected $casts = [
        'formations' => 'array',
        'replied_at' => 'datetime',
        'unit_price_xaf' => 'integer',
        'delivery_price_xaf' => 'integer',
        'total_price_xaf' => 'integer',
    ];

    public const STATUS_LABELS = [
        'pending' => 'En attente',
        'confirmed' => 'Confirmée',
        'shipped' => 'Expédiée',
        'delivered' => 'Livrée',
        'cancelled' => 'Annulée',
    ];

    protected $hidden = ['replied_by'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
