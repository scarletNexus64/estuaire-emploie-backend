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
            // Champs pour les étudiants - TOUS NULLABLE pour ne pas impacter les users existants
            $table->string('level')->nullable()->after('role')->comment('Niveau académique (L1, L2, L3, M1, M2, BTS1, BTS2, etc.)');
            $table->text('interests')->nullable()->after('level')->comment('Centre d\'intérêt de l\'étudiant');
            $table->foreignId('specialty_id')->nullable()->after('interests')->constrained('specialties')->nullOnDelete()->comment('Spécialité de l\'étudiant');
            $table->boolean('must_change_password')->default(false)->after('password')->comment('Forcer le changement de mot de passe à la première connexion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['specialty_id']);
            $table->dropColumn(['level', 'interests', 'specialty_id', 'must_change_password']);
        });
    }
};
