<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceMode extends Model
{
    protected $table = 'maintenance_mode';

    protected $fillable = [
        'is_active',
        'message',
        'activated_at',
        'deactivated_at',
        'activated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'activated_at' => 'datetime',
        'deactivated_at' => 'datetime',
    ];

    public function activatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'activated_by');
    }

    public static function isInMaintenanceMode(): bool
    {
        $maintenance = self::latest()->first();
        return $maintenance ? $maintenance->is_active : false;
    }

    public static function getMaintenanceMessage(): ?string
    {
        $maintenance = self::latest()->first();
        return $maintenance && $maintenance->is_active ? $maintenance->message : null;
    }
}
