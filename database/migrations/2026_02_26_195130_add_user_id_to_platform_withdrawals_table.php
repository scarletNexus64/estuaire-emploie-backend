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
        Schema::table('platform_withdrawals', function (Blueprint $table) {
            // Add user_id to track which user initiated the withdrawal
            $table->foreignId('user_id')
                ->nullable()
                ->after('id')
                ->constrained('users')
                ->onDelete('cascade');

            // Add index for better query performance
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('platform_withdrawals', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropIndex(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
