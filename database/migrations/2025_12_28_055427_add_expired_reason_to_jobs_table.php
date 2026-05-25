<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ajoute la colonne expired_reason pour tracer pourquoi un job a été expiré.
     * - 'subscription_expired': abonnement du recruteur expiré
     * - 'deadline_passed': date limite de candidature dépassée
     * - 'manual': désactivé manuellement par le recruteur/admin
     */
    public function up(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->string('expired_reason', 50)->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropColumn('expired_reason');
        });
    }
};
