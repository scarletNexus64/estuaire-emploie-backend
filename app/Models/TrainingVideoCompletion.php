<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class TrainingVideoCompletion extends Model
{
    protected $fillable = ['user_id', 'training_video_id', 'completed_at'];
    protected $casts = ['completed_at' => 'datetime'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function trainingVideo()
    {
        return $this->belongsTo(TrainingVideo::class);
    }
}
