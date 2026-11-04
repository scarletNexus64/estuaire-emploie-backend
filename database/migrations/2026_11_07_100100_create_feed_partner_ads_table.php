<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Cartes sponsorisées des applications partenaires (Estuaire Achats,
 * Estuaire Eat, GFS) insérées dans le fil candidat.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('feed_partner_ads')) {
            return;
        }

        Schema::create('feed_partner_ads', function (Blueprint $table) {
            $table->id();
            $table->string('partner', 50); // estuaire_achats | estuaire_eat | gfs
            $table->string('name');
            $table->string('headline');
            $table->text('body')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('image_path')->nullable();
            $table->string('cta_label')->nullable();
            $table->string('accent_color', 9)->nullable();
            $table->string('play_store_url', 2048)->nullable();
            $table->string('app_store_url', 2048)->nullable();
            $table->string('website_url', 2048)->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('display_order')->default(0);
            $table->unsignedBigInteger('impressions_count')->default(0);
            $table->unsignedBigInteger('clicks_count')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['is_active', 'display_order']);
            $table->index('partner');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feed_partner_ads');
    }
};
