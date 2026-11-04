<?php

use App\Support\FasciculeCatalog;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Filière des annales (code du FasciculeCatalog), pour les ranger en dossiers
 * Domaine → Filière → Matière → Année dans l'onglet Fascicules.
 * Les épreuves existantes sont remplies depuis leur chemin de stockage
 * (exam_papers/{spécialité}/{filière}/{année}/fichier.pdf).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('exam_papers', function (Blueprint $table) {
            $table->string('filiere', 20)->nullable()->after('specialty');
            $table->index(['filiere', 'subject', 'year']);
        });

        DB::table('exam_papers')
            ->whereNull('filiere')
            ->select(['id', 'file_path'])
            ->orderBy('id')
            ->chunkById(500, function ($papers) {
                $byCode = [];
                foreach ($papers as $paper) {
                    $segment = explode('/', (string) $paper->file_path)[2] ?? null;
                    $code = FasciculeCatalog::codeFromExamFiliere($segment);
                    if ($code !== null) {
                        $byCode[$code][] = $paper->id;
                    }
                }
                foreach ($byCode as $code => $ids) {
                    DB::table('exam_papers')->whereIn('id', $ids)->update(['filiere' => $code]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('exam_papers', function (Blueprint $table) {
            $table->dropIndex(['filiere', 'subject', 'year']);
            $table->dropColumn('filiere');
        });
    }
};
