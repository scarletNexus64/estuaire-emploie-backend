<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommunityAssistanceRequest extends Model
{
    protected $guarded = ['id'];
    public const ACTIVE = ['pending', 'accepted', 'in_progress'];
    public function requester() { return $this->belongsTo(User::class, 'requester_id')->select(['id', 'name']); }
    public function mentor() { return $this->belongsTo(User::class, 'mentor_id')->select(['id', 'name']); }
    public function specialty() { return $this->belongsTo(CommunitySpecialty::class, 'community_specialty_id'); }
    public function messages() { return $this->hasMany(CommunityAssistanceMessage::class, 'request_id'); }
}
