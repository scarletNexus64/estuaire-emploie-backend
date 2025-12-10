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
        Schema::table('jobs', function (Blueprint $table) {
            // Changer salary_min et salary_max de string à decimal
            $table->decimal('salary_min', 12, 2)->nullable()->change();
            $table->decimal('salary_max', 12, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            // Revenir à string en cas de rollback
            $table->string('salary_min')->nullable()->change();
            $table->string('salary_max')->nullable()->change();
        });
    }
};
