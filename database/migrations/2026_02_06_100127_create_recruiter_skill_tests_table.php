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
        Schema::create('recruiter_skill_tests', function (Blueprint $table) {
            $table->id();

            // Relations
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('job_id')->nullable()->constrained()->onDelete('set null');

            // Test details
            $table->string('title');
            $table->text('description')->nullable();
            $table->json('questions'); // Format: [{question: '', type: 'multiple_choice|text|code', options: [], correct_answer: ''}]

            // Configuration
            $table->integer('duration_minutes')->nullable(); // Durée du test en minutes
            $table->integer('passing_score')->default(70); // Score minimal en pourcentage (0-100)
            $table->boolean('is_active')->default(true);

            // Stats
            $table->integer('times_used')->default(0);

            $table->timestamps();
            $table->softDeletes();

            // Index
            $table->index(['company_id', 'is_active']);
            $table->index('job_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recruiter_skill_tests');
    }
};
