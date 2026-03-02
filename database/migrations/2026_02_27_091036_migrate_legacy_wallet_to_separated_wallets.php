<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Migre tous les soldes wallet_balance (legacy) vers freemopay_wallet_balance
     * et met à jour toutes les transactions sans provider pour les attribuer à 'freemopay'
     */
    public function up(): void
    {
        DB::transaction(function () {
            // 1. Migrer tous les soldes wallet_balance vers freemopay_wallet_balance
            DB::statement("
                UPDATE users
                SET freemopay_wallet_balance = wallet_balance,
                    paypal_wallet_balance = 0.00
                WHERE wallet_balance > 0
            ");

            echo "✅ Migrated wallet_balance to freemopay_wallet_balance for all users\n";

            // 2. Mettre à jour toutes les transactions sans provider
            $updatedCount = DB::table('wallet_transactions')
                ->whereNull('provider')
                ->orWhere('provider', '')
                ->update(['provider' => 'freemopay']);

            echo "✅ Updated {$updatedCount} transactions to provider='freemopay'\n";

            // 3. Afficher un résumé
            $usersWithBalance = DB::table('users')
                ->where('freemopay_wallet_balance', '>', 0)
                ->count();

            echo "✅ {$usersWithBalance} users have FreeMoPay wallet balance\n";

            $transactionsWithProvider = DB::table('wallet_transactions')
                ->where('provider', 'freemopay')
                ->count();

            echo "✅ {$transactionsWithProvider} transactions now have provider='freemopay'\n";
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::transaction(function () {
            // Reverser la migration : remettre les soldes dans wallet_balance
            DB::statement("
                UPDATE users
                SET wallet_balance = freemopay_wallet_balance + paypal_wallet_balance
                WHERE freemopay_wallet_balance > 0 OR paypal_wallet_balance > 0
            ");

            // Retirer le provider des transactions
            DB::table('wallet_transactions')
                ->where('provider', 'freemopay')
                ->update(['provider' => null]);

            echo "✅ Reverted migration\n";
        });
    }
};
