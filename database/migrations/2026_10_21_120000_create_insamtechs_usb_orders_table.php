<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('insamtechs_usb_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->json('formations');
            $table->string('customer_name');
            $table->string('phone', 30);
            $table->string('city');
            $table->text('address');
            $table->text('notes')->nullable();
            $table->unsignedInteger('unit_price_xaf');
            $table->unsignedInteger('delivery_price_xaf');
            $table->unsignedInteger('total_price_xaf');
            $table->string('status', 30)->default('pending');
            $table->timestamps();
            $table->index(['user_id', 'created_at']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('insamtechs_usb_orders');
    }
};
