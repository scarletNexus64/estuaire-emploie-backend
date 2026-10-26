<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommunitySpecialty extends Model
{
    protected $fillable = [
        'community_filiere_id', 'insam_ia_category_id', 'name', 'slug',
        'description', 'icon', 'display_order', 'is_active',
    ];

    protected $casts = [
        'insam_ia_category_id' => 'integer',
        'display_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function filiere(): BelongsTo
    {
        return $this->belongsTo(CommunityFiliere::class, 'community_filiere_id');
    }
}
