<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Index de performance : notifications (compteur non lues, liste), activité
 * sociale (commentaires, republications, fil), offres par entreprise,
 * abonnements, tri par nombre d'abonnés. Idempotent : chaque index n'est créé
 * que si la table/les colonnes existent et qu'aucun index équivalent n'existe.
 */
return new class extends Migration
{
    /** [table, nom, colonnes] */
    private function indexes(): array
    {
        return [
            ['notifications', 'notifications_notifiable_read_index', ['notifiable_type', 'notifiable_id', 'read_at']],
            ['notifications', 'notifications_notifiable_created_index', ['notifiable_type', 'notifiable_id', 'created_at']],
            ['post_comments', 'post_comments_user_deleted_created_index', ['user_id', 'deleted_at', 'created_at']],
            ['post_reposts', 'post_reposts_user_created_index', ['user_id', 'created_at']],
            ['post_reposts', 'post_reposts_created_target_index', ['created_at', 'target_type', 'target_id']],
            ['jobs', 'jobs_company_status_published_index', ['company_id', 'status', 'published_at']],
            ['user_subscription_plans', 'user_subscription_plans_user_expires_index', ['user_id', 'expires_at']],
            ['companies', 'companies_followers_count_index', ['followers_count']],
            ['users', 'users_active_followers_index', ['is_active', 'followers_count']],
        ];
    }

    private function hasIndex(string $table, string $name): bool
    {
        return collect(DB::select("SHOW INDEX FROM `{$table}`"))
            ->contains(fn ($row) => $row->Key_name === $name);
    }

    /** Un index existant commence-t-il déjà exactement par ces colonnes ? */
    private function hasEquivalentIndex(string $table, array $columns): bool
    {
        return collect(DB::select("SHOW INDEX FROM `{$table}`"))
            ->groupBy('Key_name')
            ->contains(function ($rows) use ($columns) {
                $existing = $rows->sortBy('Seq_in_index')->pluck('Column_name')->all();

                return array_slice($existing, 0, count($columns)) === $columns;
            });
    }

    public function up(): void
    {
        foreach ($this->indexes() as [$table, $name, $columns]) {
            if (! Schema::hasTable($table) || ! Schema::hasColumns($table, $columns)) {
                continue;
            }
            if ($this->hasIndex($table, $name) || $this->hasEquivalentIndex($table, $columns)) {
                continue;
            }
            Schema::table($table, fn (Blueprint $t) => $t->index($columns, $name));
        }
    }

    public function down(): void
    {
        foreach ($this->indexes() as [$table, $name]) {
            if (Schema::hasTable($table) && $this->hasIndex($table, $name)) {
                Schema::table($table, fn (Blueprint $t) => $t->dropIndex($name));
            }
        }
    }
};
