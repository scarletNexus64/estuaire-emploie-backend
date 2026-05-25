<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Étendre l'ENUM pour ajouter plus de types de programmes
        DB::statement("ALTER TABLE `programs`
            MODIFY COLUMN `type` ENUM(
                'immersion_professionnelle',
                'entreprenariat',
                'transformation_professionnelle',
                'digital_skills',
                'agriculture_agrobusiness',
                'tourisme_hotellerie',
                'btp_construction',
                'sante_social',
                'commerce_vente',
                'artisanat_metiers',
                'finance_comptabilite',
                'energie_environnement'
            ) NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Retirer les nouveaux types
        DB::statement("ALTER TABLE `programs`
            MODIFY COLUMN `type` ENUM(
                'immersion_professionnelle',
                'entreprenariat',
                'transformation_professionnelle'
            ) NOT NULL");
    }
};
