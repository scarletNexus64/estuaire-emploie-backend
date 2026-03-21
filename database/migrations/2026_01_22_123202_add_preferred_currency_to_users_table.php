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
            // Devise préférée de l'utilisateur (XAF par défaut)
            $table->string('preferred_currency', 3)
                  ->default('XAF')
                  ->after('email')
                  ->comment('Devise préférée: XAF, USD, EUR');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('preferred_currency');
        });
    }
};
