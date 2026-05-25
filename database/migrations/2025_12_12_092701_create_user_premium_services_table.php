<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_premium_services', function (Blueprint $table) {
            $table->id();

            // Relations
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('premium_services_config_id')->constrained()->onDelete('restrict');
            $table->foreignId('payment_id')->nullable()->constrained()->onDelete('set null');

            // Période d'activation
            $table->timestamp('purchased_at');
            $table->timestamp('activated_at')->nullable();
            $table->timestamp('expires_at')->nullable(); // null si permanent

            // Statut
            $table->boolean('is_active')->default(true);
            $table->boolean('auto_renew')->default(false);

            // Utilisation (pour services consommables)
            $table->integer('uses_remaining')->nullable(); // Pour services à usage limité

            $table->timestamps();
            $table->softDeletes();

            // Index
            $table->index(['user_id', 'is_active']);
            $table->index('expires_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_premium_services');
    }
};
