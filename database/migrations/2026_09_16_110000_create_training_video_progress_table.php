<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Suivi du visionnage des vidéos de formation.
 *
 * Les vidéos elles-mêmes vivent chez InsamTechs (`admin.insamtechs.com`), qui
 * ne conserve aucune progression par étudiant : Estuaire la tient donc ici.
 * C'est cette progression qui ouvre droit à l'attestation de formation, une
 * fois toutes les vidéos d'une formation vues.
 *
 * Aucune FK vers les formations ou les vidéos : leurs identifiants sont ceux
 * du référentiel InsamTechs, hors de cette base.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_video_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Identifiants InsamTechs.
            $table->unsignedBigInteger('formation_id');
            $table->unsignedBigInteger('video_id');

            // Conservés pour rester lisibles si le catalogue distant change.
            $table->string('formation_title')->nullable();
            $table->string('video_title')->nullable();

            // Position de lecture, en secondes, pour reprendre où l'étudiant
            // s'est arrêté.
            $table->unsignedInteger('position_seconds')->default(0);
            $table->unsignedInteger('duration_seconds')->nullable();

            $table->unsignedTinyInteger('progress_percent')->default(0);
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('last_watched_at')->nullable();

            $table->timestamps();

            // Une seule ligne de progression par vidéo et par étudiant.
            $table->unique(['user_id', 'video_id'], 'training_video_progress_unique');
            $table->index(['user_id', 'formation_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_video_progress');
    }
};
