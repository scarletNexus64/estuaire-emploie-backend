<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class TrainingVideoChapter extends Model
{
    protected $fillable = ['training_video_id', 'title', 'timestamp_seconds', 'timestamp_formatted', 'description', 'display_order'];
    protected $casts = ['timestamp_seconds' => 'integer', 'display_order' => 'integer'];

    public function trainingVideo()
    {
        return $this->belongsTo(TrainingVideo::class);
    }

    public static function formatTimestamp(int $seconds): string
    {
        $h = intdiv($seconds, 3600);
        $m = intdiv($seconds % 3600, 60);
        $s = $seconds % 60;
        return $h > 0 ? sprintf('%d:%02d:%02d', $h, $m, $s) : sprintf('%02d:%02d', $m, $s);
    }
}
