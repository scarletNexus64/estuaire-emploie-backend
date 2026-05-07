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
        Schema::create('storage_packs', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nom du pack (ex: "Pack Basic", "Pack Pro")
            $table->string('slug')->unique(); // Slug pour URL
            $table->unsignedInteger('storage_mb'); // Espace en Mo (ex: 250, 512, 1024)
            $table->unsignedInteger('duration_days'); // Période en jours (ex: 30, 360)
            $table->decimal('price', 10, 2); // Prix en CFA
            $table->boolean('is_active')->default(true); // Statut actif/inactif
            $table->text('description')->nullable(); // Description optionnelle
            $table->unsignedInteger('display_order')->default(0); // Ordre d'affichage
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('storage_packs');
    }
};
