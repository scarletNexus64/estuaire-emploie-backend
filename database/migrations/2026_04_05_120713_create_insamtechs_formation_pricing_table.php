<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('insamtechs_formation_pricing', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('insamtechs_formation_id')->unique();
            $table->string('formation_title')->nullable(); // cache pour l'affichage admin
            $table->decimal('price_xaf', 12, 2)->default(0);
            $table->decimal('price_usd', 12, 2)->default(0);
            $table->decimal('price_eur', 12, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('insamtechs_formation_pricing');
    }
};
