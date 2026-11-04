<?php

use App\Support\FasciculeCatalog;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Anciennes annales rangées à plat (exam_papers/fichier.pdf) : leur chemin ne
 * donne pas la filière, mais leur spécialité est le nom de la filière.
 * On la convertit en code du FasciculeCatalog pour qu'elles apparaissent dans
 * l'onglet Fascicules. Additif : seules les épreuves sans filière sont touchées.
 */
return new class extends Migration
{
    /** Spécialité (majuscules, sans accents, espaces réduits) => code filière. */
    private const BY_SPECIALTY = [
        'PRODUCTION VEGETALE' => 'PV',
        'PRODUCTION ANIMALE' => 'PA',
        'TRONC COMMUN AGROPASTORAL BTS' => 'T-AGRI',
        'DOUANE ET TRANSIT' => 'DOT',
        'GESTION LOGISTIQUE ET TRANSPORT' => 'GLT',
        'MARKETING COMMERCE VENTE' => 'MCV',
        'MARKETING' => 'MCV',
        'COMPTABILITE GESTION ENTREPRISES' => 'CGE',
        'BANQUE ET FINANCE' => 'BAF',
        'GESTION DES SYSTEMES INFORMATIQUES' => 'GSI',
        'COMMERCE' => 'T-GEST',
        'TRONC COMMUN COMMERCE' => 'T-GEST',
        'FROID ET CLIMATISATION' => 'FC',
        'MAINTENANCE DES APPAREILS BIOMEDICAUX' => 'MAB',
        'ENERGIE RENOUVELABLE' => 'ENR',
        'GEOMETRE TOPOGRAPHIE' => 'GT',
        'TRAVAUX PUBLIC' => 'TP',
        'ELECTROTECHNIQUE' => 'ET',
        'MAINTENANCE APRES VENTE AUTOMOBILE' => 'MAVA',
        'INDUSTRIE' => 'T-IND',
        'TRONC COMMUN INDUSTRIE' => 'T-IND',
        'TRONC COMMUN MEI/MIP' => 'T-IND',
        'INFOGRAPHIE WEB DESIGN' => 'IWD',
        'GENIE LOGICIEL' => 'GL',
        'INFORMATIQUE INDUSTRIELLE ET AUTOMATISME' => 'IIA',
        'GESTION MANAGEMENT HOTELIER' => 'GMH',
        'SAGE FEMME' => 'SF',
        'NURSING' => 'SI',
        'RADIOLOGIE ET IMAGERIE MEDICALE' => 'RIM',
        'TRONC COMMUN SANTE' => 'T-SANTE',
        'JOURNALISME' => 'JO',
        'TRONC COMMUN' => 'TC',
        'TRONC COMMUN COMMERCE ET INDUSTRIE' => 'TC',
        // Options HND (anglophones)
        'LOGISTICS AND TRANSPORT MANAGEMENT' => 'HND',
        'CIVIL ENGENEERING TECHNOLOGIE' => 'HND',
        'BANKING AND FINANCE' => 'HND',
        'TRONC COMMUN HND' => 'HND',
        'TRONC COMMUN HND AGRICULTURE' => 'HND',
        'TRONC COMMUN COMMERCE HND' => 'HND',
        'TRONC COMMUN INDUSTRIE HND' => 'HND',
    ];

    public function up(): void
    {
        DB::table('exam_papers')
            ->whereNull('filiere')
            ->select(['id', 'specialty'])
            ->orderBy('id')
            ->chunkById(500, function ($papers) {
                $byCode = [];
                foreach ($papers as $paper) {
                    $code = self::codeFor($paper->specialty);
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
        // Rien à défaire : la colonne disparaît avec la migration qui l'a créée.
    }

    private static function codeFor(?string $specialty): ?string
    {
        $key = strtoupper(Str::ascii(trim((string) $specialty)));
        $key = preg_replace('/\s+/', ' ', $key);
        $code = self::BY_SPECIALTY[$key] ?? null;

        return FasciculeCatalog::hasFiliere($code) ? $code : null;
    }
};
