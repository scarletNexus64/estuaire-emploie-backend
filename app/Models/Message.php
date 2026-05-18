<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'conversation_id',
        'sender_id',
        'message',
        'status',
        'company_product_id',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Produit/service taggé dans le message (boutique virtuelle)
     */
    public function companyProduct()
    {
        return $this->belongsTo(CompanyProduct::class, 'company_product_id');
    }
}
