<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Table `roadmap_questions` — questions du QCM d'un niveau.
 *
 * Chaque question appartient à un niveau (roadmap_levels) qui a `has_quiz`.
 * Les options et l'index de la bonne réponse sont stockés en JSON pour
 * rester simples à éditer depuis l'admin.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roadmap_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('roadmap_level_id')->constrained()->cascadeOnDelete();
            $table->text('question');
            // Liste des choix : ["Choix A", "Choix B", ...]
            $table->json('options');
            // Index (0-based) de la/les bonne(s) réponse(s) dans `options`
            $table->json('correct_answers')->comment('Index 0-based des bonnes réponses');
            // Explication affichée après réponse (optionnelle)
            $table->text('explanation')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();

            $table->index(['roadmap_level_id', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roadmap_questions');
    }
};
