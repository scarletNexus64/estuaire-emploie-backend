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
        Schema::table('messages', function (Blueprint $table) {
            // Produit/service taggé dans le message (boutique virtuelle)
            $table->foreignId('company_product_id')
                ->nullable()
                ->after('message')
                ->constrained('company_products')
                ->nullOnDelete();

            // Contexte additionnel (snapshot produit, type d'événement...)
            $table->json('metadata')->nullable()->after('company_product_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropConstrainedForeignId('company_product_id');
            $table->dropColumn('metadata');
        });
    }
};
