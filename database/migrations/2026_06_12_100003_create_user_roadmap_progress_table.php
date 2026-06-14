<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Table `user_roadmap_progress` — progression d'un utilisateur sur une roadmap.
 *
 * Une ligne par (user, roadmap). Trace le niveau courant débloqué, les
 * niveaux complétés, le total d'XP et la date de fin éventuelle.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_roadmap_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('roadmap_id')->constrained()->cascadeOnDelete();
            // Plus haut niveau (order) débloqué — 1 par défaut au démarrage
            $table->integer('current_level')->default(1);
            // IDs des roadmap_levels validés
            $table->json('completed_levels')->nullable();
            // Scores QCM par niveau : { "<level_id>": 85, ... }
            $table->json('quiz_scores')->nullable();
            $table->unsignedInteger('total_xp')->default(0);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'roadmap_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_roadmap_progress');
    }
};
