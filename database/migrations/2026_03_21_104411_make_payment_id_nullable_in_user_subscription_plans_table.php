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
        Schema::table('user_subscription_plans', function (Blueprint $table) {
            // Rendre payment_id nullable pour permettre les plans gratuits (étudiants, etc.)
            $table->unsignedBigInteger('payment_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_subscription_plans', function (Blueprint $table) {
            // Retirer la possibilité de NULL pour payment_id
            $table->unsignedBigInteger('payment_id')->nullable(false)->change();
        });
    }
};
