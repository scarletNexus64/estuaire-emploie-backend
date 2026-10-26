<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommunityDiscussion extends Model
{
    protected $guarded = ['id'];
    protected $casts = ['is_resolved' => 'boolean'];
    public function user() { return $this->belongsTo(User::class)->select(['id', 'name']); }
    public function specialty() { return $this->belongsTo(CommunitySpecialty::class, 'community_specialty_id'); }
    public function acceptedReply() { return $this->belongsTo(CommunityReply::class, 'accepted_reply_id')->with('user'); }
    public function replies() { return $this->hasMany(CommunityReply::class); }
}
