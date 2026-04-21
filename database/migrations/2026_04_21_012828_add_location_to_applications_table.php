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
        Schema::table('applications', function (Blueprint $table) {
            $table->decimal('latitude', 10, 8)->nullable()->after('portfolio_url');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            $table->string('address')->nullable()->after('longitude');
            $table->string('city')->nullable()->after('address');
            $table->string('country')->nullable()->after('city');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude', 'address', 'city', 'country']);
        });
    }
};
