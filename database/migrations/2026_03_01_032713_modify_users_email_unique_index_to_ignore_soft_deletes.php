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
            // Supprimer l'ancien index unique sur email
            $table->dropUnique('users_email_unique');

            // Créer un nouvel index unique composite sur (email, deleted_at)
            // Cela permet d'avoir le même email pour un utilisateur actif (deleted_at = NULL)
            // et des utilisateurs supprimés (deleted_at != NULL)
            $table->unique(['email', 'deleted_at'], 'users_email_deleted_at_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Supprimer le nouvel index composite
            $table->dropUnique('users_email_deleted_at_unique');

            // Restaurer l'ancien index unique sur email uniquement
            $table->unique('email', 'users_email_unique');
        });
    }
};
