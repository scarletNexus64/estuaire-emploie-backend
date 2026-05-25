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
        Schema::table('users', function (Blueprint $table) {
            // Drop la contrainte de clé étrangère
            $table->dropForeign(['specialty_id']);
            // Drop la colonne specialty_id
            $table->dropColumn('specialty_id');
            // Ajouter la colonne specialty en texte libre
            $table->string('specialty')->nullable()->after('interests')->comment('Spécialité de l\'étudiant (texte libre)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Supprimer la colonne specialty
            $table->dropColumn('specialty');
            // Recréer specialty_id avec la foreign key
            $table->foreignId('specialty_id')->nullable()->after('interests')->constrained('specialties')->nullOnDelete();
        });
    }
};
