<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            // Référence à l'abonnement actif
            $table->foreignId('active_subscription_id')->nullable()->after('subscription_plan')->constrained('subscriptions')->onDelete('set null');

            // Quotas tracking (copiés depuis le plan pour performance)
            $table->integer('jobs_limit')->nullable()->after('active_subscription_id'); // null = illimité
            $table->integer('contacts_limit')->nullable(); // null = illimité

            // Consommation actuelle (reset chaque période)
            $table->integer('jobs_posted_this_month')->default(0);
            $table->integer('contacts_used_this_month')->default(0);
            $table->timestamp('quota_reset_at')->nullable(); // Date de dernier reset

            // Features actives (boolean pour accès rapide)
            $table->boolean('can_access_cvtheque')->default(false);
            $table->boolean('can_boost_jobs')->default(false);
            $table->boolean('can_see_analytics')->default(false);
            $table->boolean('priority_support')->default(false);

            // Indexation
            $table->index('active_subscription_id');
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropForeign(['active_subscription_id']);
            $table->dropColumn([
                'active_subscription_id',
                'jobs_limit',
                'contacts_limit',
                'jobs_posted_this_month',
                'contacts_used_this_month',
                'quota_reset_at',
                'can_access_cvtheque',
                'can_boost_jobs',
                'can_see_analytics',
                'priority_support',
            ]);
        });
    }
};
