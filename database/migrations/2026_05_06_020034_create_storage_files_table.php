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
        Schema::create('storage_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name'); // Nom du fichier stocké (UUID)
            $table->string('original_name'); // Nom original du fichier
            $table->string('file_type')->default('other'); // image, video, audio, document, etc.
            $table->string('mime_type')->nullable();
            $table->bigInteger('size'); // Taille en octets
            $table->string('path'); // Chemin relatif du fichier
            $table->timestamps();

            $table->index('user_id');
            $table->index('file_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('storage_files');
    }
};
