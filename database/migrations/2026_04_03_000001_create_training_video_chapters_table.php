<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('training_video_chapters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_video_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->integer('timestamp_seconds')->default(0);
            $table->string('timestamp_formatted')->nullable();
            $table->text('description')->nullable();
            $table->integer('display_order')->default(0);
            $table->timestamps();
            $table->index(['training_video_id', 'display_order']);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('training_video_chapters');
    }
};
