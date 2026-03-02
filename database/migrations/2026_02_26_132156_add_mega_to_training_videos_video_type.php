<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modifier la colonne video_type pour ajouter 'mega' et 'vimeo'
        DB::statement("ALTER TABLE training_videos MODIFY COLUMN video_type ENUM('upload', 'youtube', 'vimeo', 'mega') NOT NULL DEFAULT 'upload'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Retour à l'ancien état (sans mega et vimeo)
        // ATTENTION: Les vidéos avec type 'mega' ou 'vimeo' seront perdues
        DB::statement("ALTER TABLE training_videos MODIFY COLUMN video_type ENUM('upload', 'youtube') NOT NULL DEFAULT 'upload'");
    }
};
