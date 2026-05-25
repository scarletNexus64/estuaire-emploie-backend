<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('advertisements', function (Blueprint $table) {
            $table->id();

            // Contenu de la publicité (simplifié)
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('image')->nullable(); // Chemin de l'image uploadée
            $table->string('background_color')->default('#0277BD'); // Couleur de fond si pas d'image

            // Type de publicité
            $table->enum('ad_type', [
                'homepage_banner',      // Bannière page d'accueil
                'search_banner',        // Bannière résultats recherche
                'featured_company',     // Entreprise en vedette
                'sidebar',              // Bannière latérale
                'custom'                // Position personnalisée
            ])->default('homepage_banner');

            // Période d'affichage
            $table->date('start_date');
            $table->date('end_date');

            // Métriques
            $table->integer('impressions_count')->default(0); // Nombre d'affichages
            $table->integer('clicks_count')->default(0); // Nombre de clics
            $table->decimal('ctr', 5, 2)->default(0); // Click-through rate (calculé)

            // Paramètres d'affichage
            $table->integer('display_order')->default(0); // Ordre de priorité

            // Statut
            $table->boolean('is_active')->default(true);
            $table->enum('status', ['active', 'paused', 'expired'])->default('active');

            $table->timestamps();
            $table->softDeletes();

            // Index
            $table->index(['ad_type', 'is_active']);
            $table->index(['start_date', 'end_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('advertisements');
    }
};
