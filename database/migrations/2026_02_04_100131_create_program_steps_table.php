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
        Schema::create('program_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description');
            $table->text('content')->nullable()->comment('Contenu détaillé du step');
            $table->json('resources')->nullable()->comment('Liens, documents, vidéos');
            $table->integer('order')->default(0);
            $table->integer('estimated_duration_days')->nullable()->comment('Durée estimée en jours');
            $table->boolean('is_required')->default(true);
            $table->timestamps();

            $table->index(['program_id', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('program_steps');
    }
};
