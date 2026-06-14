<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Ajoute la spécialité académique (filière) rattachée à une offre.
     * Obligatoire côté formulaire lorsque le type de contrat est un Stage.
     */
    public function up(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->foreignId('specialty_id')
                ->nullable()
                ->after('contract_type_id')
                ->constrained('specialties')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropConstrainedForeignId('specialty_id');
        });
    }
};
