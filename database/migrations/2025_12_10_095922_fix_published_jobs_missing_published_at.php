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
        // Fix tous les jobs publiés qui n'ont pas de published_at
        DB::table('jobs')
            ->where('status', 'published')
            ->whereNull('published_at')
            ->update([
                'published_at' => DB::raw('COALESCE(updated_at, created_at)')
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Pas besoin de rollback car c'est une correction de données
    }
};
