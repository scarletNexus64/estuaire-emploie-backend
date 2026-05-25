<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Advertisement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'image',
        'background_color',
        'ad_type',
        'start_date',
        'end_date',
        'impressions_count',
        'clicks_count',
        'ctr',
        'display_order',
        'is_active',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'impressions_count' => 'integer',
            'clicks_count' => 'integer',
            'ctr' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    // Accessor pour obtenir l'URL complète de l'image
    public function getImageUrlAttribute(): ?string
    {
        if ($this->image) {
            // Retourner l'URL complète avec le domaine
            return url(Storage::url($this->image));
        }
        return null;
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
