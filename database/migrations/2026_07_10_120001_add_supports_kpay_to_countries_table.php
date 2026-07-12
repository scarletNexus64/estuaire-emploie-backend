<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Marque les pays où KPay (Mobile Money) est opérationnel. Le sélecteur de
 * pays côté mobile n'active que ces pays (les autres sont grisés) tant que le
 * déploiement multi-pays n'est pas généralisé. Piloté par la donnée : élargir
 * la couverture = passer `supports_kpay` à true (pas de déploiement app).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('countries', function (Blueprint $table) {
            $table->boolean('supports_kpay')->default(false)->after('currency');
            $table->index('supports_kpay');
        });
    }

    public function down(): void
    {
        Schema::table('countries', function (Blueprint $table) {
            $table->dropIndex(['supports_kpay']);
            $table->dropColumn('supports_kpay');
        });
    }
};
