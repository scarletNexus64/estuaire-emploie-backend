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
        Schema::create('portfolio_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('portfolio_id')->constrained()->cascadeOnDelete();
            $table->foreignId('viewer_id')->nullable()->constrained('users')->nullOnDelete(); // Si connecté
            $table->string('viewer_ip')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('referer')->nullable(); // D'où vient le visiteur
            $table->timestamp('viewed_at')->useCurrent();

            $table->index(['portfolio_id', 'viewed_at']);
            $table->index('viewer_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('portfolio_views');
    }
};
