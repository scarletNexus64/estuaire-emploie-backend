<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPresence extends Model
{
    protected $primaryKey = 'user_id';
    protected $fillable = ['user_id', 'online', 'last_seen'];
    public $timestamps = false;

    protected $casts = [
        'online' => 'boolean',
        'last_seen' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mettre à jour le statut en ligne
     */
    public static function setOnline(int $userId): void
    {
        self::updateOrCreate(
            ['user_id' => $userId],
            [
                'online' => true,
                'last_seen' => now(),
            ]
        );
    }

    /**
     * Mettre à jour le statut hors ligne
     */
    public static function setOffline(int $userId): void
    {
        self::updateOrCreate(
            ['user_id' => $userId],
            [
                'online' => false,
                'last_seen' => now(),
            ]
        );
    }

    /**
     * Mettre à jour last_seen sans changer le statut online
     */
    public static function updateLastSeen(int $userId): void
    {
        self::updateOrCreate(
            ['user_id' => $userId],
            ['last_seen' => now()]
        );
    }
}