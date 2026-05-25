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
        Schema::create('application_test_results', function (Blueprint $table) {
            $table->id();

            // Relations
            $table->foreignId('application_id')->constrained()->onDelete('cascade');
            $table->foreignId('recruiter_skill_test_id')->constrained()->onDelete('cascade');

            // Test results
            $table->json('answers'); // Les réponses du candidat
            $table->integer('score')->default(0); // Score en pourcentage (0-100)
            $table->boolean('passed')->default(false); // Si le candidat a réussi

            // Timing
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->integer('duration_seconds')->nullable(); // Temps pris pour compléter

            // Notes du recruteur
            $table->text('recruiter_notes')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Index (with custom short name to avoid MySQL 64-char limit)
            $table->index(['application_id', 'recruiter_skill_test_id'], 'idx_app_test_result');
            $table->index('passed', 'idx_test_passed');

            // Un seul résultat par application et test
            $table->unique(['application_id', 'recruiter_skill_test_id'], 'unique_application_test');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_test_results');
    }
};
