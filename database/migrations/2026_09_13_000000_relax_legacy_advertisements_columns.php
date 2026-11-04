<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Aligne le schéma hérité de `advertisements` (prod) sur celui du code.
 *
 * La table de production vient d'une lignée antérieure où toute annonce
 * appartenait à une entreprise payante : `company_id` et `price` y sont
 * NOT NULL sans valeur par défaut. Or les bannières de repli de l'accueil
 * (`is_default`, cf. DefaultAdvertisementSeeder) appartiennent à la
 * plateforme : ni entreprise, ni paiement. La migration
 * 2026_06_14_100000 crée bien `company_id` en nullable, mais elle est
 * idempotente et ne touche pas une colonne déjà présente — d'où la
 * divergence, qui fait échouer le seeder avec « Field 'company_id'
 * doesn't have a default value ».
 *
 * Idempotent : chaque ALTER n'est joué que si la colonne existe encore
 * dans son ancienne forme.
 */
return new class extends Migration
{
    public function up(): void
    {
        if ($this->columnIsNotNull('company_id')) {
            DB::statement('ALTER TABLE `advertisements` MODIFY `company_id` BIGINT UNSIGNED NULL');
        }

        if ($this->columnIsNotNull('price')) {
            DB::statement('ALTER TABLE `advertisements` MODIFY `price` DECIMAL(10,2) NOT NULL DEFAULT 0.00');
        }
    }

    public function down(): void
    {
        // Pas de retour arrière : rétablir NOT NULL casserait les bannières
        // de repli déjà en base (company_id NULL).
    }

    private function columnIsNotNull(string $column): bool
    {
        if (!Schema::hasTable('advertisements') || !Schema::hasColumn('advertisements', $column)) {
            return false;
        }

        // MariaDB n'accepte pas de paramètre lié dans un SHOW COLUMNS :
        // $column vient d'un littéral du code, jamais d'une entrée externe.
        $info = DB::selectOne("SHOW COLUMNS FROM `advertisements` LIKE '{$column}'");

        return $info && $info->Null === 'NO' && $info->Default === null;
    }
};
