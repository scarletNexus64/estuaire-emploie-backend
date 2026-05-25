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
        Schema::create('exam_packs', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nom du pack (ex: "BTS 2026")
            $table->string('slug')->unique(); // URL-friendly
            $table->text('description')->nullable(); // Description du pack
            $table->decimal('price_xaf', 10, 2)->default(0); // Prix en XAF
            $table->decimal('price_usd', 10, 2)->nullable(); // Prix en USD (optionnel)
            $table->decimal('price_eur', 10, 2)->nullable(); // Prix en EUR (optionnel)

            // Informations du pack
            $table->string('specialty')->nullable(); // Spécialité principale (Informatique, Gestion, etc.)
            $table->integer('year')->nullable(); // Année (2024, 2025, 2026...)
            $table->string('exam_type')->nullable(); // Type d'examen (BTS, Licence, Master...)

            // Médias
            $table->string('cover_image')->nullable(); // Image de couverture du pack

            // Gestion
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false); // Pack mis en avant
            $table->integer('display_order')->default(0);

            // Statistiques
            $table->integer('purchases_count')->default(0); // Nombre d'achats
            $table->integer('views_count')->default(0); // Nombre de vues

            $table->timestamps();
            $table->softDeletes();

            // Index
            $table->index(['is_active', 'is_featured', 'display_order']);
            $table->index(['specialty', 'year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_packs');
    }
};
