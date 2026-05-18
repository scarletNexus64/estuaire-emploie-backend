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
        Schema::create('pack_promotion_activations', function (Blueprint $table) {
            $table->id();

            // Relation avec la promotion
            $table->unsignedBigInteger('pack_promotion_id');

            // Utilisateur
            $table->unsignedBigInteger('user_id');

            // Dates d'activation et expiration individuelle
            $table->dateTime('activated_at');
            $table->dateTime('expires_at')->comment('activated_at + usage_duration_days');

            // Statut
            $table->boolean('is_expired')->default(false);

            // Métadonnées
            $table->timestamps();

            // Foreign keys
            $table->foreign('pack_promotion_id')->references('id')->on('pack_promotions')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Index
            $table->index(['user_id', 'pack_promotion_id'], 'idx_user_promotion');
            $table->index(['expires_at', 'is_expired'], 'idx_expiration');

            // Contrainte unique: un user ne peut activer qu'une seule fois la même promotion
            $table->unique(['user_id', 'pack_promotion_id'], 'unique_user_promotion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pack_promotion_activations');
    }
};
