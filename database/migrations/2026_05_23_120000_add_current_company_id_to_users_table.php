<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('current_company_id')
                ->nullable()
                ->after('role')
                ->constrained('companies')
                ->nullOnDelete();
        });

        // Backfill : pour chaque user ayant exactement un recruiter,
        // initialiser current_company_id avec sa company actuelle.
        \DB::statement(<<<'SQL'
            UPDATE users u
            INNER JOIN recruiters r ON r.user_id = u.id
            SET u.current_company_id = r.company_id
            WHERE u.current_company_id IS NULL
        SQL);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('current_company_id');
        });
    }
};
