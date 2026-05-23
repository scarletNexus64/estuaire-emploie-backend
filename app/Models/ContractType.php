<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContractType extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'name',
        'slug',
    ];

    protected array $translatable = ['name'];

    public function jobs(): HasMany
    {
        return $this->hasMany(Job::class);
    }
}
