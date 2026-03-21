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
        Schema::create('viewed_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recruiter_user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('candidate_user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            // Un recruteur ne peut voir un candidat qu'une seule fois (unique constraint)
            $table->unique(['recruiter_user_id', 'candidate_user_id']);

            // Index pour les requêtes de comptage mensuel
            $table->index(['recruiter_user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('viewed_contacts');
    }
};
