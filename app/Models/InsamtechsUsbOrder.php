<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InsamtechsUsbOrder extends Model
{
    protected $fillable = [
        'user_id', 'formations', 'customer_name', 'phone', 'address',
        'city', 'notes', 'unit_price_xaf', 'delivery_price_xaf',
        'total_price_xaf', 'status',
    ];

    protected $casts = [
        'formations' => 'array',
        'unit_price_xaf' => 'integer',
        'delivery_price_xaf' => 'integer',
        'total_price_xaf' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
