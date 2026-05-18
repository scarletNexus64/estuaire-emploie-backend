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
        Schema::table('company_product_purchases', function (Blueprint $table) {
            $table->string('invoice_number')->nullable()->unique()->after('status');
            $table->string('invoice_path')->nullable()->after('invoice_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('company_product_purchases', function (Blueprint $table) {
            $table->dropColumn(['invoice_number', 'invoice_path']);
        });
    }
};
