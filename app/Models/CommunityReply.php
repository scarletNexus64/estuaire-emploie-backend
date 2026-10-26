<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommunityReply extends Model
{
    protected $guarded = ['id'];
    public function user() { return $this->belongsTo(User::class)->select(['id', 'name']); }
    public function discussion() { return $this->belongsTo(CommunityDiscussion::class, 'community_discussion_id'); }
}
