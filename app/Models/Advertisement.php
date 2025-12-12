<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Advertisement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'payment_id',
        'ad_type',
        'title',
        'description',
        'image_url',
        'target_url',
        'price',
        'start_date',
        'end_date',
        'impressions_count',
        'clicks_count',
        'ctr',
        'display_order',
        'targeting',
        'is_active',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'start_date' => 'date',
            'end_date' => 'date',
            'impressions_count' => 'integer',
            'clicks_count' => 'integer',
            'ctr' => 'decimal:2',
            'targeting' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    public function isActive(): bool
    {
        return $this->is_active &&
               $this->status === 'active' &&
               $this->start_date <= now() &&
               $this->end_date >= now();
    }

    public function calculateCTR(): void
    {
        if ($this->impressions_count > 0) {
            $this->ctr = ($this->clicks_count / $this->impressions_count) * 100;
            $this->save();
        }
    }
}
