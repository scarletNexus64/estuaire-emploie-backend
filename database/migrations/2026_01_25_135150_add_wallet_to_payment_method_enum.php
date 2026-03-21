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
        // Modifier l'ENUM pour ajouter 'wallet', 'paypal', 'freemopay'
        // Inclure toutes les valeurs existantes + les nouvelles
        DB::statement("ALTER TABLE `payments` MODIFY COLUMN `payment_method` ENUM('mtn_money', 'orange_money', 'card', 'bank_transfer', 'cash', 'free', 'paypal', 'freemopay', 'wallet') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Retirer 'wallet' de l'ENUM, garder 'paypal' et 'freemopay'
        DB::statement("ALTER TABLE `payments` MODIFY COLUMN `payment_method` ENUM('mtn_money', 'orange_money', 'card', 'bank_transfer', 'cash', 'free', 'paypal', 'freemopay') NOT NULL");
    }
};
