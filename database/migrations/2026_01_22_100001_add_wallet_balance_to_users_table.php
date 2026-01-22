<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Ajoute le champ wallet_balance à la table users.
     * Le wallet permet aux utilisateurs de recharger leur compte et payer
     * les services/plans sans passer par le paiement direct à chaque fois.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Solde du wallet en FCFA (ou devise configurée)
            $table->decimal('wallet_balance', 10, 2)->default(0)->after('role');

            // Index pour recherches rapides sur le solde
            $table->index('wallet_balance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['wallet_balance']);
            $table->dropColumn('wallet_balance');
        });
    }
};
