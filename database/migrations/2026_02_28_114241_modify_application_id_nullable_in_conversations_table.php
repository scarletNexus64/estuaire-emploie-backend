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
        Schema::table('conversations', function (Blueprint $table) {
            // Supprimer la contrainte unique et rendre application_id nullable
            // pour permettre les conversations de service (sans application)
            $table->dropForeign(['application_id']);
            $table->dropColumn('application_id');
        });

        Schema::table('conversations', function (Blueprint $table) {
            // Rajouter application_id comme nullable et sans contrainte unique
            $table->foreignId('application_id')
                ->nullable()
                ->after('user_two')
                ->constrained('applications')
                ->cascadeOnDelete();

            // Ajouter service_id pour traçabilité (optionnel)
            $table->foreignId('service_id')
                ->nullable()
                ->after('application_id')
                ->constrained('quick_services')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('conversations', function (Blueprint $table) {
            // Supprimer service_id
            $table->dropForeign(['service_id']);
            $table->dropColumn('service_id');

            // Recréer application_id avec contrainte unique
            $table->dropForeign(['application_id']);
            $table->dropColumn('application_id');
        });

        Schema::table('conversations', function (Blueprint $table) {
            $table->foreignId('application_id')
                ->unique()
                ->after('user_two')
                ->constrained('applications')
                ->cascadeOnDelete();
        });
    }
};
