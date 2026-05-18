<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyProduct extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'company_id',
        'name',
        'description',
        'price',
        'billing_type',
        'currency',
        'company_category_id',
        'type',
        'images',
        'is_active',
        'stock',
    ];

    protected $casts = [
        'images' => 'array',
        'is_active' => 'boolean',
        'price' => 'decimal:2',
        'stock' => 'integer',
    ];

    protected $appends = ['first_image_url', 'image_urls'];

    /**
     * Get the company that owns this product/service
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the level-3 sector (CompanyCategory) attached to this product/service
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(CompanyCategory::class, 'company_category_id');
    }

    /**
     * Get the first image URL
     */
    public function getFirstImageUrlAttribute(): ?string
    {
        if (empty($this->images)) {
            return null;
        }

        $firstImage = is_array($this->images) ? $this->images[0] : null;

        if (!$firstImage) {
            return null;
        }

        // Si l'image commence déjà par http/https, retourner tel quel
        if (str_starts_with($firstImage, 'http://') || str_starts_with($firstImage, 'https://')) {
            return $firstImage;
        }

        // Sinon, construire l'URL complète
        return url('storage/' . $firstImage);
    }

    /**
     * Get all image URLs
     */
    public function getImageUrlsAttribute(): array
    {
        if (empty($this->images)) {
            return [];
        }

        return collect($this->images)->map(function ($image) {
            if (str_starts_with($image, 'http://') || str_starts_with($image, 'https://')) {
                return $image;
            }
            return url('storage/' . $image);
        })->toArray();
    }

    /**
     * Scope to get only active products/services
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get only products
     */
    public function scopeProducts($query)
    {
        return $query->where('type', 'product');
    }

    /**
     * Scope to get only services
     */
    public function scopeServices($query)
    {
        return $query->where('type', 'service');
    }
}
