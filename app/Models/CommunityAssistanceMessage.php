<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommunityAssistanceMessage extends Model
{
    protected $guarded = ['id'];
    protected $hidden = ['attachment_path'];
    public function user() { return $this->belongsTo(User::class)->select(['id', 'name']); }
    public function assistanceRequest() { return $this->belongsTo(CommunityAssistanceRequest::class, 'request_id'); }
}
