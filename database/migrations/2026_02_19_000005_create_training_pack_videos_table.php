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
        Schema::create('training_pack_videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_pack_id')->constrained()->onDelete('cascade');
            $table->foreignId('training_video_id')->constrained()->onDelete('cascade');
            $table->string('section_name')->nullable(); // Nom de la section/module (ex: "Introduction", "Chapitre 1")
            $table->integer('section_order')->default(0); // Ordre de la section
            $table->integer('display_order')->default(0); // Ordre d'affichage dans le pack
            $table->timestamps();

            // Index pour éviter les doublons
            $table->unique(['training_pack_id', 'training_video_id']);
            $table->index(['section_order', 'display_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_pack_videos');
    }
};
