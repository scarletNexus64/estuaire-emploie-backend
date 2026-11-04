<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Vidéos courtes verticales (« shorts ») du fil candidat : découverte des
 * métiers. Fichier sur le disque `public` ou, à défaut, URL externe.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('feed_videos')) {
            return;
        }

        Schema::create('feed_videos', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('category')->nullable();
            $table->string('video_path')->nullable();
            $table->string('video_url', 2048)->nullable();
            $table->string('thumbnail_path')->nullable();
            $table->string('thumbnail_url', 2048)->nullable();
            $table->unsignedInteger('duration_seconds')->nullable();
            $table->string('author_name')->nullable();
            $table->string('source_credit')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('display_order')->default(0);
            $table->unsignedBigInteger('views_count')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['is_active', 'display_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feed_videos');
    }
};
