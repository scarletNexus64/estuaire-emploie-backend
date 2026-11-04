<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Bannière (couverture) du profil, affichée en tête du profil façon réseau
 * social. Chemin relatif sur le disque `public`, comme `profile_photo`.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('users', 'cover_photo')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('cover_photo')->nullable()->after('profile_photo');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'cover_photo')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('cover_photo');
            });
        }
    }
};
