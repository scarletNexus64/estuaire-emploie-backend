<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Demandes de profil publiées depuis la CVThèque : une entreprise décrit le
     * profil recherché, la demande est diffusée progressivement (FCM) à tous les
     * utilisateurs et chacun peut y répondre par la messagerie.
     */
    public function up(): void
    {
        Schema::create('cv_profile_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('company_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('specialty')->nullable();
            $table->string('experience_level', 40)->nullable();
            $table->string('contract_type', 40)->nullable();
            $table->string('city')->nullable();
            $table->json('skills')->nullable();
            $table->text('description');
            $table->string('status', 20)->default('open');
            $table->timestamp('notified_at')->nullable();
            $table->unsignedInteger('responses_count')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cv_profile_requests');
    }
};
