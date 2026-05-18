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
        Schema::create('company_categories', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Ex: 1.1.1
            $table->string('level_1'); // Secteur principal
            $table->string('level_2')->nullable(); // Sous-catégorie
            $table->string('level_3')->nullable(); // Sous-sous-catégorie
            $table->string('slug')->index(); // Pour les URLs
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_categories');
    }
};
