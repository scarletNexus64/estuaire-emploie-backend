<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modèle pour tracker les contacts de candidats vus par les recruteurs
 * Utilisé pour appliquer la limite contacts_limit du plan d'abonnement
 */
class ViewedContact extends Model
{
    protected $fillable = [
        'recruiter_user_id',
        'candidate_user_id',
    ];

    /**
     * Le recruteur qui a vu le contact
     */
    public function recruiter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recruiter_user_id');
    }

    /**
     * Le candidat dont le contact a été vu
     */
    public function candidate(): BelongsTo
    {
        return $this->belongsTo(User::class, 'candidate_user_id');
    }
}
