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

            // Relations
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('payment_id')->nullable()->constrained()->onDelete('set null');

            // Type de publicité
            $table->enum('ad_type', [
                'homepage_banner',      // Bannière page d'accueil (25000 FCFA/mois)
                'search_banner',        // Bannière résultats recherche (15000 FCFA/mois)
                'featured_company',     // Entreprise en vedette
                'sidebar',              // Bannière latérale
                'custom'                // Position personnalisée
            ]);

            // Contenu de la publicité
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('image_url')->nullable(); // URL de l'image/bannière
            $table->string('target_url')->nullable(); // URL de destination au clic

            // Tarification (configurable)
            $table->decimal('price', 10, 2); // Prix payé pour cette pub

            // Période d'affichage
            $table->date('start_date');
            $table->date('end_date');

            // Métriques
            $table->integer('impressions_count')->default(0); // Nombre d'affichages
            $table->integer('clicks_count')->default(0); // Nombre de clics
            $table->decimal('ctr', 5, 2)->default(0); // Click-through rate (calculé)

            // Paramètres d'affichage
            $table->integer('display_order')->default(0); // Ordre de priorité
            $table->json('targeting')->nullable(); // Critères de ciblage (JSON)

            // Statut
            $table->boolean('is_active')->default(true);
            $table->enum('status', ['pending', 'active', 'paused', 'expired'])->default('pending');

            // Notes
            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Index
            $table->index(['company_id', 'status']);
            $table->index(['ad_type', 'is_active']);
            $table->index(['start_date', 'end_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('advertisements');
    }
};
