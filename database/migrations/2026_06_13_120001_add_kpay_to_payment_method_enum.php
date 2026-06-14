<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * NOTE: 'freemopay' est conservé pour les lignes historiques. 'kpay' est ajouté
     * comme nouvelle méthode (recharges via KPay Mobile Money).
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE payments MODIFY COLUMN payment_method ENUM('mtn_money', 'orange_money', 'card', 'bank_transfer', 'cash', 'free', 'paypal', 'freemopay', 'wallet', 'promotional_free', 'kpay') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE payments MODIFY COLUMN payment_method ENUM('mtn_money', 'orange_money', 'card', 'bank_transfer', 'cash', 'free', 'paypal', 'freemopay', 'wallet', 'promotional_free') NOT NULL");
    }
};
