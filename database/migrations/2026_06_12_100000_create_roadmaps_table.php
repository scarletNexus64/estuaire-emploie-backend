<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Table `roadmaps` — parcours d'apprentissage gamifié (mode "jeu").
 *
 * Une roadmap = un objectif (ex. "Développeur Backend", "SQL", "Anglais")
 * composé de niveaux (roadmap_levels) à débloquer un à un en réussissant
 * un QCM. Vit à côté des `programs` (programmes professionnels classiques),
 * tous deux affichés dans la card "Mon projet pro".
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roadmaps', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            // Domaine métier (clé technique, libellé humain mappé côté app)
            $table->string('domain')->index()->comment('developpement, ecommerce, marketing, langues, sql, blockchain...');
            $table->text('description');
            $table->text('objectives')->nullable();
            $table->string('icon')->default('🗺️');
            // Couleur d'accent de la roadmap (hex), pour la carte gamifiée
            $table->string('color', 9)->default('#6366F1');
            $table->enum('difficulty', ['beginner', 'intermediate', 'advanced', 'expert'])->default('beginner');
            // Packs requis pour accéder (null/[] = accessible à tous)
            $table->json('required_packs')->nullable()->comment('Packs requis: C1, C2, C3');
            // Score minimum (%) pour valider un QCM et débloquer le niveau suivant
            $table->unsignedTinyInteger('pass_threshold')->default(70);
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roadmaps');
    }
};
