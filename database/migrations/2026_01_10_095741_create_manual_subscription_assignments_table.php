<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Table pour tracer les attributions manuelles de forfaits par les administrateurs
     */
    public function up(): void
    {
        Schema::create('manual_subscription_assignments', function (Blueprint $table) {
            $table->id();

            // L'utilisateur qui reçoit l'abonnement
            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');

            // Le plan d'abonnement attribué
            $table->foreignId('subscription_plan_id')
                ->constrained()
                ->onDelete('restrict');

            // Le paiement créé automatiquement
            $table->foreignId('payment_id')
                ->constrained()
                ->onDelete('restrict');

            // L'abonnement utilisateur créé
            $table->foreignId('user_subscription_plan_id')
                ->constrained('user_subscription_plans')
                ->onDelete('restrict')
                ->name('manual_subs_user_sub_plan_fk');

            // L'admin qui a fait l'attribution
            $table->foreignId('assigned_by_admin_id')
                ->constrained('users')
                ->onDelete('restrict')
                ->name('manual_subs_assigned_by_fk');

            // Notes optionnelles de l'admin
            $table->text('notes')->nullable();

            // Raison de l'attribution manuelle
            $table->string('reason')->nullable();

            $table->timestamps();

            // Index pour les requêtes fréquentes
            $table->index('user_id');
            $table->index('assigned_by_admin_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manual_subscription_assignments');
    }
};
