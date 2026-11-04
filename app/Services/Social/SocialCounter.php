<?php

namespace App\Services\Social;

use Illuminate\Support\Facades\DB;

/**
 * Mise à jour atomique des compteurs dénormalisés (likes_count,
 * comments_count, reposts_count, followers_count, replies_count).
 *
 * Passe par le query builder (DB::table) et non par Eloquent : pas de
 * modification de `updated_at`, pas d'événements de modèle (une offre ne doit
 * pas être considérée « modifiée » parce qu'on l'a aimée). Jamais sous 0.
 */
class SocialCounter
{
    public static function increment(string $table, int $id, string $column, int $by = 1): void
    {
        if ($by <= 0) {
            return;
        }

        DB::table($table)->where('id', $id)->increment($column, $by);
    }

    public static function decrement(string $table, int $id, string $column, int $by = 1): void
    {
        if ($by <= 0) {
            return;
        }

        // Colonnes UNSIGNED : `col - n` déborderait avant tout GREATEST(),
        // d'où le CASE.
        $wrapped = DB::getQueryGrammar()->wrap($column);
        DB::table($table)->where('id', $id)->update([
            $column => DB::raw("CASE WHEN {$wrapped} >= {$by} THEN {$wrapped} - {$by} ELSE 0 END"),
        ]);
    }

    public static function value(string $table, int $id, string $column): int
    {
        return (int) DB::table($table)->where('id', $id)->value($column);
    }
}
