<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Cette migration ajoute le support multi-rôles pour les utilisateurs.
     * Un utilisateur peut être à la fois candidat ET recruteur, et switcher entre les deux.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Rôles disponibles pour cet utilisateur (peut avoir plusieurs rôles)
            // Exemple: ['candidate', 'recruiter'] = peut switcher entre les deux
            $table->json('available_roles')->nullable()->after('role');

            // Note: Le champ 'role' existant devient le "rôle actif"
            // et 'available_roles' contient tous les rôles que le user peut utiliser
        });

        // Initialiser available_roles pour les users existants
        DB::table('users')->update([
            'available_roles' => DB::raw("JSON_ARRAY(role)")
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('available_roles');
        });
    }
};
