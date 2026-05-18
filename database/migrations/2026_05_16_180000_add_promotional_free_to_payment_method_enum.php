<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modifier l'enum payment_method pour ajouter promotional_free
        DB::statement("ALTER TABLE payments MODIFY COLUMN payment_method ENUM('mtn_money', 'orange_money', 'card', 'bank_transfer', 'cash', 'free', 'paypal', 'freemopay', 'wallet', 'promotional_free') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restaurer l'enum sans promotional_free
        DB::statement("ALTER TABLE payments MODIFY COLUMN payment_method ENUM('mtn_money', 'orange_money', 'card', 'bank_transfer', 'cash', 'free', 'paypal', 'freemopay', 'wallet') NOT NULL");
    }
};
