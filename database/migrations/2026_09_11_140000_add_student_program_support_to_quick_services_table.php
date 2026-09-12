<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Permet de publier les « jobs étudiants » comme services rapides.
 *
 * Ces programmes (apporteur d'affaires Estuaire Eat / Achats / Emploi,
 * coursier Merci-E) diffèrent des services rapides classiques sur trois points :
 *
 *  1. ils sont nationaux — d'où la géolocalisation rendue facultative ;
 *  2. ils rémunèrent à la commission (un pourcentage, éventuellement plafonné,
 *     et/ou une prime fixe) plutôt qu'à un prix de mission ;
 *  3. ils sont permanents et publiés par la plateforme, pas par un recruteur.
 */
return new class extends Migration
{
    public function up(): void
    {
        // La géolocalisation n'a pas de sens pour un programme national.
        // change() sur une colonne indexée : l'index (latitude, longitude)
        // reste valide, seules les contraintes de nullité évoluent.
        Schema::table('quick_services', function (Blueprint $table) {
            $table->decimal('latitude', 10, 8)->nullable()->change();
            $table->decimal('longitude', 11, 8)->nullable()->change();
        });

        // Rémunération à la commission, en complément de fixed/range/negotiable.
        DB::statement("ALTER TABLE quick_services MODIFY COLUMN price_type ENUM('fixed','range','negotiable','commission') NOT NULL DEFAULT 'negotiable'");

        Schema::table('quick_services', function (Blueprint $table) {
            if (!Schema::hasColumn('quick_services', 'slug')) {
                // Identifiant stable des programmes seedés (idempotence).
                $table->string('slug')->nullable()->unique()->after('id');
            }

            if (!Schema::hasColumn('quick_services', 'is_student_program')) {
                // Distingue les programmes de la plateforme des demandes
                // ponctuelles publiées par les recruteurs.
                $table->boolean('is_student_program')->default(false)->after('status');
            }

            if (!Schema::hasColumn('quick_services', 'program_partner')) {
                // Plateforme du groupe concernée par le programme.
                $table->string('program_partner', 40)->nullable()->after('is_student_program');
            }

            if (!Schema::hasColumn('quick_services', 'program_type')) {
                // Famille du programme, qui sert à regrouper les annonces :
                // apport d'affaires (recommander un partenaire) ou course
                // (effectuer des livraisons).
                $table->enum('program_type', ['business_provider', 'courier'])
                    ->nullable()
                    ->after('program_partner');
            }

            if (!Schema::hasColumn('quick_services', 'commission_rate')) {
                // Pourcentage reversé à l'étudiant (ex. 5.00 pour 5 %).
                $table->decimal('commission_rate', 5, 2)->nullable()->after('program_partner');
            }

            if (!Schema::hasColumn('quick_services', 'commission_basis')) {
                // Assiette de la commission : premier mois de chiffre d'affaires,
                // première transaction, chaque transaction, ou montant de course.
                $table->enum('commission_basis', [
                    'first_month_revenue',
                    'first_transaction',
                    'per_transaction',
                    'per_course',
                ])->nullable()->after('commission_rate');
            }

            if (!Schema::hasColumn('quick_services', 'commission_cap')) {
                // Plafond anti-abus sur les grosses transactions (null = aucun).
                $table->decimal('commission_cap', 12, 2)->nullable()->after('commission_basis');
            }

            if (!Schema::hasColumn('quick_services', 'fixed_bonus')) {
                // Prime fixe, cumulable ou alternative à la commission.
                $table->decimal('fixed_bonus', 12, 2)->nullable()->after('commission_cap');
            }

            if (!Schema::hasColumn('quick_services', 'fixed_bonus_basis')) {
                // Ce que rémunère la prime fixe (ex. « par entreprise validée »).
                $table->string('fixed_bonus_basis', 60)->nullable()->after('fixed_bonus');
            }

            if (!Schema::hasColumn('quick_services', 'bonus_is_cumulative')) {
                // false : la prime remplace la commission (« ou » — Estuaire Eat).
                // true  : les deux se cumulent (« et » — Estuaire Emploi).
                $table->boolean('bonus_is_cumulative')->default(false)->after('fixed_bonus_basis');
            }

            if (!Schema::hasColumn('quick_services', 'min_active_days')) {
                // Ancienneté exigée du partenaire recommandé avant versement.
                $table->unsignedSmallInteger('min_active_days')->nullable()->after('bonus_is_cumulative');
            }

            if (!Schema::hasColumn('quick_services', 'has_rating_system')) {
                // Merci-E priorise les coursiers les mieux notés.
                $table->boolean('has_rating_system')->default(false)->after('min_active_days');
            }

            if (!Schema::hasColumn('quick_services', 'program_order')) {
                $table->unsignedInteger('program_order')->default(0)->after('has_rating_system');
            }
        });

        $indexExists = collect(Schema::getIndexes('quick_services'))
            ->contains(fn ($index) => $index['name'] === 'quick_services_student_program_idx');

        if (!$indexExists) {
            Schema::table('quick_services', function (Blueprint $table) {
                $table->index(['is_student_program', 'status', 'program_order'], 'quick_services_student_program_idx');
            });
        }
    }

    public function down(): void
    {
        $indexExists = collect(Schema::getIndexes('quick_services'))
            ->contains(fn ($index) => $index['name'] === 'quick_services_student_program_idx');

        if ($indexExists) {
            Schema::table('quick_services', function (Blueprint $table) {
                $table->dropIndex('quick_services_student_program_idx');
            });
        }

        // Les programmes reposent sur ces colonnes : on les retire avant de
        // restaurer l'enum, sinon des lignes 'commission' deviendraient invalides.
        DB::table('quick_services')->where('is_student_program', true)->delete();

        Schema::table('quick_services', function (Blueprint $table) {
            foreach ([
                'program_order', 'has_rating_system', 'min_active_days',
                'bonus_is_cumulative', 'fixed_bonus_basis', 'fixed_bonus', 'commission_cap',
                'commission_basis', 'commission_rate', 'program_type', 'program_partner',
                'is_student_program',
            ] as $column) {
                if (Schema::hasColumn('quick_services', $column)) {
                    $table->dropColumn($column);
                }
            }

            if (Schema::hasColumn('quick_services', 'slug')) {
                $table->dropUnique(['slug']);
                $table->dropColumn('slug');
            }
        });

        DB::statement("ALTER TABLE quick_services MODIFY COLUMN price_type ENUM('fixed','range','negotiable') NOT NULL DEFAULT 'negotiable'");

        Schema::table('quick_services', function (Blueprint $table) {
            $table->decimal('latitude', 10, 8)->nullable(false)->change();
            $table->decimal('longitude', 11, 8)->nullable(false)->change();
        });
    }
};
