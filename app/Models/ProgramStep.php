<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgramStep extends Model
{
    use HasFactory;
    protected $fillable = [
        'program_id',
        'title',
        'description',
        'content',
        'resources',
        'order',
        'estimated_duration_days',
        'is_required',
    ];

    protected $casts = [
        'resources' => 'array',
        'is_required' => 'boolean',
        'order' => 'integer',
        'estimated_duration_days' => 'integer',
    ];

    /**
     * Relation avec le programme parent
     */
    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }
}
