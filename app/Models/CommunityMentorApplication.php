<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommunityMentorApplication extends Model
{
    protected $guarded = ['id'];
    protected $hidden = ['reviewed_by'];
    protected $casts = ['is_available' => 'boolean', 'reviewed_at' => 'datetime'];
    public function user() { return $this->belongsTo(User::class)->select(['id', 'name']); }
    public function specialties() {
        return $this->belongsToMany(CommunitySpecialty::class, 'community_mentor_specialties', 'application_id', 'specialty_id');
    }
    public function scopeAvailable($query) {
        return $query->where('status', 'approved')->where('is_available', true);
    }
}
