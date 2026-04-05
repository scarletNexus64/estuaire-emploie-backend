<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('training_video_completions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('training_video_id')->constrained()->onDelete('cascade');
            $table->timestamp('completed_at')->useCurrent();
            $table->timestamps();
            $table->unique(['user_id', 'training_video_id']);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('training_video_completions');
    }
};
