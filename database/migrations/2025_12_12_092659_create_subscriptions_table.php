<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();

            // Relations
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('subscription_plan_id')->constrained()->onDelete('restrict');

            // Période d'abonnement
            $table->date('start_date');
            $table->date('end_date');
            $table->date('next_billing_date')->nullable();

            // Statut
            $table->enum('status', ['pending', 'active', 'expired', 'cancelled'])->default('pending');
            $table->boolean('auto_renew')->default(true);

            // Tracking des quotas utilisés (reset chaque période)
            $table->integer('jobs_posted_this_period')->default(0);
            $table->integer('contacts_used_this_period')->default(0);
            $table->timestamp('last_reset_at')->nullable(); // Dernière réinitialisation des quotas

            // Informations supplémentaires
            $table->text('cancellation_reason')->nullable();
            $table->timestamp('cancelled_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Index
            $table->index(['company_id', 'status']);
            $table->index('end_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
