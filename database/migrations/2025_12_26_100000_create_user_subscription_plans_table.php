<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Table pivot ternaire pour la relation User <-> SubscriptionPlan <-> Payment
     *
     * Cette table représente une souscription d'un utilisateur à un plan d'abonnement,
     * liée à un paiement spécifique. Les trois entités sont indissociables :
     * - Un utilisateur souscrit à un plan via un paiement
     * - L'état "actif" de l'abonnement se déduit du statut du paiement associé
     */
    public function up(): void
    {
        Schema::create('user_subscription_plans', function (Blueprint $table) {
            $table->id();

            // Relation ternaire : les 3 clés étrangères sont obligatoires
            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('subscription_plan_id')
                ->constrained()
                ->onDelete('restrict');

            $table->foreignId('payment_id')
                ->constrained()
                ->onDelete('restrict');

            $table->timestamps();

            // Index composite pour garantir l'unicité de la relation ternaire
            $table->unique(['user_id', 'subscription_plan_id', 'payment_id'], 'user_plan_payment_unique');

            // Index pour les requêtes fréquentes
            $table->index('user_id');
            $table->index('subscription_plan_id');
            $table->index('payment_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_subscription_plans');
    }
};
