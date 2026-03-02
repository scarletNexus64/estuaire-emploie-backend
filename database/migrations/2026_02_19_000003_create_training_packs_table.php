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
        Schema::create('training_packs', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nom du pack de formation
            $table->string('slug')->unique(); // URL-friendly
            $table->text('description')->nullable(); // Description du pack
            $table->text('learning_objectives')->nullable(); // Objectifs d'apprentissage

            // Prix
            $table->decimal('price_xaf', 10, 2)->default(0); // Prix en XAF
            $table->decimal('price_usd', 10, 2)->nullable(); // Prix en USD (optionnel)
            $table->decimal('price_eur', 10, 2)->nullable(); // Prix en EUR (optionnel)

            // Informations du pack
            $table->string('category')->nullable(); // Catégorie (Développement Web, Marketing, etc.)
            $table->string('level')->nullable(); // Niveau (Débutant, Intermédiaire, Avancé)
            $table->integer('duration_hours')->nullable(); // Durée totale en heures

            // Médias
            $table->string('cover_image')->nullable(); // Image de couverture
            $table->string('preview_video')->nullable(); // Vidéo de présentation (optionnel)

            // Instructeur/Auteur
            $table->string('instructor_name')->nullable();
            $table->text('instructor_bio')->nullable();
            $table->string('instructor_photo')->nullable();

            // Gestion
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false); // Pack mis en avant
            $table->integer('display_order')->default(0);

            // Statistiques
            $table->integer('purchases_count')->default(0);
            $table->integer('views_count')->default(0);
            $table->decimal('average_rating', 3, 2)->default(0); // Note moyenne (0-5)
            $table->integer('reviews_count')->default(0);

            $table->timestamps();
            $table->softDeletes();

            // Index
            $table->index(['is_active', 'is_featured', 'display_order']);
            $table->index(['category', 'level']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_packs');
    }
};
