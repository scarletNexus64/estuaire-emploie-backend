<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Index de l'annuaire de la messagerie : tri A→Z des personnes et des
 * entreprises, et recherche des conversations entre deux utilisateurs.
 */
return new class extends Migration
{
    private function hasIndex(string $table, string $name): bool
    {
        return collect(DB::select("SHOW INDEX FROM `{$table}`"))
            ->contains(fn ($row) => $row->Key_name === $name);
    }

    public function up(): void
    {
        if (! $this->hasIndex('users', 'users_directory_index')) {
            Schema::table('users', function (Blueprint $table) {
                $table->index(['is_active', 'name', 'id'], 'users_directory_index');
            });
        }
        if (! $this->hasIndex('companies', 'companies_directory_index')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->index(['name', 'id'], 'companies_directory_index');
            });
        }
        if (! $this->hasIndex('conversations', 'conversations_pair_index')) {
            Schema::table('conversations', function (Blueprint $table) {
                $table->index(['user_one', 'user_two', 'application_id'], 'conversations_pair_index');
                $table->index(['user_two', 'user_one'], 'conversations_pair_reverse_index');
            });
        }
    }

    public function down(): void
    {
        Schema::table('users', fn (Blueprint $t) => $t->dropIndex('users_directory_index'));
        Schema::table('companies', fn (Blueprint $t) => $t->dropIndex('companies_directory_index'));
        Schema::table('conversations', function (Blueprint $t) {
            $t->dropIndex('conversations_pair_index');
            $t->dropIndex('conversations_pair_reverse_index');
        });
    }
};
