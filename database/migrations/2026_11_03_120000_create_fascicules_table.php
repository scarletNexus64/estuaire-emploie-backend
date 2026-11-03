<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Fascicules : supports de cours (PDF) pour les étudiants.
 * Consultables dans l'app, fichier réservé au Pack Étudiant (Mode Étudiant).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fascicules', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->nullable()->unique();
            $table->text('description')->nullable();
            $table->string('filiere')->nullable()->index();
            $table->string('level')->nullable()->index();
            $table->string('subject')->nullable();
            $table->string('author')->nullable();
            $table->string('file_path');
            $table->string('cover_path')->nullable();
            $table->unsignedInteger('pages_count')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->unsignedInteger('downloads_count')->default(0);
            $table->boolean('is_published')->default(true)->index();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fascicules');
    }
};
