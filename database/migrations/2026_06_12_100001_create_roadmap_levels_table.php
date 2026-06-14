<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Table `roadmap_levels` — un niveau (palier) d'une roadmap.
 *
 * Affiché comme un "checkpoint" sur le chemin du jeu (niveau 1, 2, 3...).
 * Contient le contenu pédagogique (tips rédigés, pas de vidéo) et,
 * optionnellement, un QCM (has_quiz) dont la réussite débloque le niveau
 * suivant.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roadmap_levels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('roadmap_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('subtitle')->nullable();
            // Contenu pédagogique : tips rédigés en markdown léger (✅, 🎯, 💡...)
            $table->longText('content');
            $table->integer('order')->default(0)->comment('Numéro du niveau (1, 2, 3...)');
            // Le niveau possède-t-il un QCM de validation ?
            $table->boolean('has_quiz')->default(false);
            // XP gagnés en validant le niveau (gamification)
            $table->unsignedInteger('xp_reward')->default(100);
            $table->timestamps();

            $table->index(['roadmap_id', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roadmap_levels');
    }
};
