<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Compte GFSolutions (G-Financials) offert à la souscription d'un pack.
     * Persisté pour assurer l'idempotence : on ne recrée pas le compte lors
     * d'un renouvellement si l'utilisateur en possède déjà un.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('gfs_client_number')->nullable()->after('referred_by_id');
            $table->string('gfs_account_number')->nullable()->after('gfs_client_number');
            $table->string('gfs_phone')->nullable()->after('gfs_account_number');
            $table->timestamp('gfs_onboarded_at')->nullable()->after('gfs_phone');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'gfs_client_number',
                'gfs_account_number',
                'gfs_phone',
                'gfs_onboarded_at',
            ]);
        });
    }
};
