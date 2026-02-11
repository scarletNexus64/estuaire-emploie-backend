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
        Schema::create('currency_rates', function (Blueprint $table) {
            $table->id();
            $table->string('from_currency', 3)->comment('Devise source (ex: XAF)');
            $table->string('to_currency', 3)->comment('Devise cible (ex: USD)');
            $table->decimal('rate', 20, 8)->comment('Taux de conversion');
            $table->boolean('is_active')->default(true)->comment('Taux actif ou non');
            $table->timestamp('last_updated')->nullable()->comment('Dernière mise à jour du taux');
            $table->timestamps();

            // Index pour recherche rapide
            $table->unique(['from_currency', 'to_currency']);
            $table->index('is_active');
        });

        // Insérer les taux initiaux (mis à jour régulièrement)
        // Taux approximatifs au 22/01/2026
        DB::table('currency_rates')->insert([
            // XAF vers autres devises
            [
                'from_currency' => 'XAF',
                'to_currency' => 'USD',
                'rate' => 0.0016, // 1 XAF = 0.0016 USD (environ 625 XAF = 1 USD)
                'is_active' => true,
                'last_updated' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'from_currency' => 'XAF',
                'to_currency' => 'EUR',
                'rate' => 0.0015, // 1 XAF = 0.0015 EUR (environ 655 XAF = 1 EUR)
                'is_active' => true,
                'last_updated' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // USD vers autres devises
            [
                'from_currency' => 'USD',
                'to_currency' => 'XAF',
                'rate' => 625.0, // 1 USD = 625 XAF
                'is_active' => true,
                'last_updated' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'from_currency' => 'USD',
                'to_currency' => 'EUR',
                'rate' => 0.92, // 1 USD = 0.92 EUR
                'is_active' => true,
                'last_updated' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // EUR vers autres devises
            [
                'from_currency' => 'EUR',
                'to_currency' => 'XAF',
                'rate' => 655.0, // 1 EUR = 655 XAF
                'is_active' => true,
                'last_updated' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'from_currency' => 'EUR',
                'to_currency' => 'USD',
                'rate' => 1.09, // 1 EUR = 1.09 USD
                'is_active' => true,
                'last_updated' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('currency_rates');
    }
};
