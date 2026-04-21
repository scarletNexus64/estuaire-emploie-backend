<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'content',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Accessor pour inclure les informations de l'utilisateur
     */
    protected $appends = ['user_name', 'user_photo', 'is_admin'];

    public function getUserNameAttribute()
    {
        return $this->user ? $this->user->name : 'Utilisateur';
    }

    public function getUserPhotoAttribute()
    {
        return $this->user && $this->user->profile_photo_path
            ? url('storage/' . $this->user->profile_photo_path)
            : null;
    }

    public function getIsAdminAttribute()
    {
        // Vérifier si l'utilisateur est admin du forum
        return $this->user && $this->user->is_forum_admin;
    }
}
