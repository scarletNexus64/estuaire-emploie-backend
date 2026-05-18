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
        Schema::table('company_products', function (Blueprint $table) {
            // Mode de facturation : prix fixe / à découvrir / à visiter
            $table->enum('billing_type', ['fixed_price', 'to_discover', 'to_visit'])
                ->default('fixed_price')
                ->after('price');

            // Code devise (XAF, USD, EUR...) - requis seulement si fixed_price
            $table->string('currency', 3)->nullable()->after('billing_type');

            // Secteur niveau 3 rattaché au produit/service (optionnel)
            $table->foreignId('company_category_id')
                ->nullable()
                ->after('currency')
                ->constrained('company_categories')
                ->nullOnDelete();
        });

        // price devient nullable (NULL si billing_type != fixed_price)
        Schema::table('company_products', function (Blueprint $table) {
            $table->decimal('price', 10, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('company_products', function (Blueprint $table) {
            $table->dropConstrainedForeignId('company_category_id');
            $table->dropColumn(['billing_type', 'currency']);
        });

        Schema::table('company_products', function (Blueprint $table) {
            $table->decimal('price', 10, 2)->nullable(false)->change();
        });
    }
};
