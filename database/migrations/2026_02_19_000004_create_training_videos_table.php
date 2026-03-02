<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('training_videos', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Titre de la vidéo
            $table->text('description')->nullable(); // Description

            // Fichier vidéo
            $table->string('video_path')->nullable(); // Chemin du fichier MP4 (si uploadé)
            $table->string('video_url')->nullable(); // URL YouTube/Vimeo (si lien externe)
            $table->enum('video_type', ['upload', 'youtube', 'vimeo'])->default('upload');
            $table->string('video_filename')->nullable(); // Nom original du fichier
            $table->bigInteger('video_size')->nullable(); // Taille en bytes

            // Informations vidéo
            $table->integer('duration_seconds')->nullable(); // Durée en secondes
            $table->string('duration_formatted')->nullable(); // Durée formatée (ex: "10:25")
            $table->string('thumbnail')->nullable(); // Miniature de la vidéo

            // Gestion
            $table->boolean('is_active')->default(true);
            $table->boolean('is_preview')->default(false); // Vidéo gratuite en aperçu
            $table->integer('display_order')->default(0);

            // Statistiques
            $table->integer('views_count')->default(0);
            $table->integer('completions_count')->default(0); // Nombre de visionnages complets

            $table->timestamps();
            $table->softDeletes();

            // Index
            $table->index(['is_active', 'display_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_videos');
    }
};
