<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Retire complètement l'intégration Baileys WhatsApp :
     * - supprime les colonnes baileys_url / baileys_secret
     * - supprime la ligne de configuration baileys_whatsapp
     * - nettoie l'enregistrement de l'ancienne migration "add"
     */
    public function up(): void
    {
        // Supprimer la configuration Baileys persistée
        DB::table('service_configurations')
            ->where('service_type', 'baileys_whatsapp')
            ->delete();

        // Supprimer les colonnes Baileys
        Schema::table('service_configurations', function (Blueprint $table) {
            if (Schema::hasColumn('service_configurations', 'baileys_url')) {
                $table->dropColumn('baileys_url');
            }
            if (Schema::hasColumn('service_configurations', 'baileys_secret')) {
                $table->dropColumn('baileys_secret');
            }
        });

        // Nettoyer l'enregistrement orphelin de l'ancienne migration supprimée
        DB::table('migrations')
            ->where('migration', '2026_06_02_095458_add_baileys_columns_to_service_configurations_table')
            ->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_configurations', function (Blueprint $table) {
            if (!Schema::hasColumn('service_configurations', 'baileys_url')) {
                $table->string('baileys_url')->nullable();
            }
            if (!Schema::hasColumn('service_configurations', 'baileys_secret')) {
                $table->string('baileys_secret')->nullable();
            }
        });
    }
};
