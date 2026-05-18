<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyProductPurchase extends Model
{
    protected $fillable = [
        'company_product_id',
        'company_id',
        'buyer_user_id',
        'seller_user_id',
        'amount',
        'currency',
        'provider',
        'status',
        'invoice_number',
        'invoice_path',
        'wallet_transaction_id',
        'product_snapshot',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'product_snapshot' => 'array',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(CompanyProduct::class, 'company_product_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_user_id');
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_user_id');
    }
}
