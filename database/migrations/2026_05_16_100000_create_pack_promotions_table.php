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
        Schema::create('pack_promotions', function (Blueprint $table) {
            $table->id();

            // Identification
            $table->string('name');
            $table->text('description')->nullable();

            // Cible de la promotion (polymorphique)
            $table->string('promotionable_type'); // ExamPack, TrainingPack, StoragePack, SubscriptionPlan
            $table->unsignedBigInteger('promotionable_id');

            // Statut
            $table->boolean('is_active')->default(true);

            // Période de disponibilité de la promotion
            $table->dateTime('start_date');
            $table->dateTime('end_date');

            // Durée d'utilisation après activation par l'utilisateur (en jours)
            $table->integer('usage_duration_days')->default(30);

            // Limitations (optionnel)
            $table->integer('max_activations')->nullable()->comment('Limite du nombre total d\'activations (NULL = illimité)');
            $table->integer('current_activations')->default(0)->comment('Compteur actuel des activations');

            // Métadonnées
            $table->unsignedBigInteger('created_by')->nullable()->comment('ID de l\'admin créateur');
            $table->timestamps();
            $table->softDeletes();

            // Index
            $table->index(['promotionable_type', 'promotionable_id'], 'idx_promotionable');
            $table->index(['is_active', 'start_date', 'end_date'], 'idx_active_dates');
            $table->index(['is_active', 'start_date', 'end_date', 'deleted_at'], 'idx_current');

            // Foreign keys
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pack_promotions');
    }
};
