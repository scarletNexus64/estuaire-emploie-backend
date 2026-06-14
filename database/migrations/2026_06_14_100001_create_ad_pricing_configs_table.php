<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Grille de tarification du sponsoring, configurable par l'admin.
 * Le prix par utilisateur ciblé détermine combien de personnes un budget donné peut toucher.
 * Ex: price_per_user = 2 FCFA => 500 FCFA = 250 personnes.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ad_pricing_configs', function (Blueprint $table) {
            $table->id();
            $table->enum('audience_segment', ['student', 'candidate', 'recruiter', 'all'])->unique();
            $table->decimal('price_per_user', 8, 2)->default(2); // FCFA par utilisateur touché
            $table->decimal('min_budget', 10, 2)->default(500);
            $table->decimal('max_budget', 10, 2)->default(500000);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ad_pricing_configs');
    }
};
