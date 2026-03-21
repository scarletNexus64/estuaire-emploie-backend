<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'favoriteable_type',
        'favoriteable_id',
    ];

    /**
     * Get the user that owns the favorite.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the model that this favorite belongs to (Job or QuickService).
     * Relation polymorphique.
     */
    public function favoriteable()
    {
        return $this->morphTo();
    }

    /**
     * DEPRECATED: Get the job that is favorited.
     * Cette méthode est conservée pour compatibilité avec l'ancien code,
     * mais elle ne fonctionnera que si favoriteable_type = App\Models\Job
     *
     * @deprecated Utiliser favoriteable() à la place
     */
    public function job()
    {
        return $this->favoriteable();
    }
}
