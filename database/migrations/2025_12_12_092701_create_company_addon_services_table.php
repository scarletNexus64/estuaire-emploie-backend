<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_addon_services', function (Blueprint $table) {
            $table->id();

            // Relations
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('addon_services_config_id')->constrained()->onDelete('restrict');
            $table->foreignId('payment_id')->nullable()->constrained()->onDelete('set null');

            // Relations optionnelles (selon le type de service)
            $table->foreignId('related_job_id')->nullable()->constrained('jobs')->onDelete('cascade'); // Pour boost
            $table->foreignId('related_user_id')->nullable()->constrained('users')->onDelete('cascade'); // Pour contacts

            // Période d'activation
            $table->timestamp('purchased_at');
            $table->timestamp('activated_at')->nullable();
            $table->timestamp('expires_at')->nullable();

            // Statut
            $table->boolean('is_active')->default(true);

            // Métriques (pour boost d'annonce)
            $table->integer('views_count')->default(0);
            $table->integer('clicks_count')->default(0);

            // Utilisation
            $table->integer('uses_remaining')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Index
            $table->index(['company_id', 'is_active']);
            $table->index('related_job_id');
            $table->index('expires_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_addon_services');
    }
};
