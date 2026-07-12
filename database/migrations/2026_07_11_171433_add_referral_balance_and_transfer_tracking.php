<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Solde de parrainage dédié.
     *
     * Les commissions ne sont plus créditées directement sur le wallet : elles
     * s'accumulent dans `referral_balance` (en XAF, comme le ledger), non
     * dépensable tel quel. Le parrain les transfère explicitement vers son
     * wallet (KPay/PayPal) depuis le dashboard, ce qui crée une transaction.
     * `transferred_to_wallet_at` marque les commissions déjà transférées.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('referral_balance', 15, 2)->default(0)->after('paypal_wallet_balance');
        });

        Schema::table('referral_commissions', function (Blueprint $table) {
            $table->timestamp('transferred_to_wallet_at')->nullable()->after('commission_amount');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('referral_balance');
        });

        Schema::table('referral_commissions', function (Blueprint $table) {
            $table->dropColumn('transferred_to_wallet_at');
        });
    }
};
