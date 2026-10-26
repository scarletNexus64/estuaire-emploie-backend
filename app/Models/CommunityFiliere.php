<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CommunityFiliere extends Model
{
    protected $fillable = ['name', 'slug', 'display_order', 'is_active'];

    protected $casts = ['display_order' => 'integer', 'is_active' => 'boolean'];

    public function specialties(): HasMany
    {
        return $this->hasMany(CommunitySpecialty::class)->orderBy('display_order')->orderBy('id');
    }
}
