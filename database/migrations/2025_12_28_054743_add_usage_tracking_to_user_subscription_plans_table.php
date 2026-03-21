<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ajoute les colonnes de suivi d'utilisation pour les abonnements.
     *
     * - jobs_used: nombre d'offres d'emploi publiées pendant cet abonnement
     * - contacts_used: nombre de candidats contactés pendant cet abonnement
     * - starts_at: date de début effective de l'abonnement (quand le paiement est confirmé)
     * - expires_at: date d'expiration calculée (starts_at + duration_days du plan)
     * - notifications_sent: pour éviter d'envoyer plusieurs fois la même notification
     */
    public function up(): void
    {
        Schema::table('user_subscription_plans', function (Blueprint $table) {
            // Compteurs d'utilisation
            $table->unsignedInteger('jobs_used')->default(0)->after('payment_id');
            $table->unsignedInteger('contacts_used')->default(0)->after('jobs_used');

            // Dates de validité
            $table->timestamp('starts_at')->nullable()->after('contacts_used');
            $table->timestamp('expires_at')->nullable()->after('starts_at');

            // Suivi des notifications envoyées (JSON pour stocker J-5, J-3, J-1, J-0)
            $table->json('notifications_sent')->nullable()->after('expires_at');

            // Index pour les requêtes de vérification d'expiration
            $table->index('expires_at');
            $table->index(['user_id', 'expires_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_subscription_plans', function (Blueprint $table) {
            $table->dropIndex(['expires_at']);
            $table->dropIndex(['user_id', 'expires_at']);

            $table->dropColumn([
                'jobs_used',
                'contacts_used',
                'starts_at',
                'expires_at',
                'notifications_sent',
            ]);
        });
    }
};
