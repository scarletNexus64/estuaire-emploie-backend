<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Recruiter extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_id',
        'position',
        'can_publish',
        'can_view_applications',
        'can_modify_company',
    ];

    protected function casts(): array
    {
        return [
            'can_publish' => 'boolean',
            'can_view_applications' => 'boolean',
            'can_modify_company' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
