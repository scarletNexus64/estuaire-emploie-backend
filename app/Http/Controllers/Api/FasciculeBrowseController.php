<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ExamPaper;
use App\Models\Fascicule;
use App\Support\FasciculeCatalog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/**
 * Explorateur de l'onglet Fascicules : un dossier à la fois.
 *
 * GET /api/fascicules/browse
 *   (aucun paramètre)                         → domaines
 *   ?domain=gestion                           → filières du domaine
 *   ?filiere=MCV                              → fascicules de la filière + dossier « Annales »
 *   ?filiere=MCV&section=annales              → matières des annales
 *   ?filiere=MCV&section=annales&subject=X    → années
 *   ?…&subject=X&year=2025 (ou year=none)     → épreuves (sujets puis corrigés)
 *   ?search=texte                             → recherche dans tout l'arbre
 *
 * La consultation est libre ; l'ouverture d'un fichier reste réservée au
 * Pack Étudiant (GET /fascicules/{id}, GET /exam-papers/{id}/view).
 */
class FasciculeBrowseController extends Controller
{
    private const SECTION_ANNALES = 'annales';
    private const NO_YEAR = 'none';
    private const SEARCH_LIMIT = 40;

    public function browse(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $filiere = $this->param($request, 'filiere');
        $domain = $this->param($request, 'domain');
        $section = $this->param($request, 'section');
        $subject = $this->param($request, 'subject');
        $year = $this->param($request, 'year');

        if (mb_strlen($search) >= 2) {
            $node = $this->searchNode($search);
        } elseif ($filiere !== null && FasciculeCatalog::hasFiliere($filiere)) {
            if ($section !== self::SECTION_ANNALES) {
                $node = $this->filiereNode($filiere);
            } elseif ($subject === null) {
                $node = $this->annalesNode($filiere);
            } elseif ($year === null) {
                $node = $this->subjectNode($filiere, $subject);
            } else {
                $node = $this->yearNode($filiere, $subject, $year);
            }
        } elseif ($domain !== null && FasciculeCatalog::hasDomain($domain)) {
            $node = $this->domainNode($domain);
        } else {
            $node = $this->rootNode();
        }

        return response()->json([
            'success' => true,
            'has_access' => $this->hasStudentMode(),
            'title' => $node['title'] ?? null,
            'breadcrumb' => $node['breadcrumb'] ?? [],
            'folders' => $node['folders'] ?? [],
            'files' => $node['files'] ?? [],
        ]);
    }

    // ── Niveaux de l'arbre ───────────────────────────────────────────────

    private function rootNode(): array
    {
        $fasciculeCounts = [];
        $rows = Fascicule::published()
            ->selectRaw('domain, filiere_code, COUNT(*) AS c')
            ->groupBy('domain', 'filiere_code')
            ->get();
        foreach ($rows as $row) {
            $key = $this->effectiveDomain($row->domain, $row->filiere_code);
            $fasciculeCounts[$key] = ($fasciculeCounts[$key] ?? 0) + (int) $row->c;
        }

        $annalesCounts = [];
        foreach ($this->examCountsByFiliere() as $code => $count) {
            $key = FasciculeCatalog::domainOf($code);
            $annalesCounts[$key] = ($annalesCounts[$key] ?? 0) + $count;
        }

        $folders = [];
        foreach (FasciculeCatalog::DOMAINS as $key => $info) {
            $fascicules = $fasciculeCounts[$key] ?? 0;
            $annales = $annalesCounts[$key] ?? 0;
            if ($fascicules + $annales === 0) {
                continue;
            }
            $folders[] = $this->folder('domain', $key, $info['label'], ['domain' => $key], $fascicules, $annales, [
                'icon' => $info['icon'],
                'volume' => $info['volume'],
            ]);
        }

        return ['title' => null, 'breadcrumb' => [], 'folders' => $folders];
    }

    private function domainNode(string $domain): array
    {
        $codes = FasciculeCatalog::filieresOf($domain);

        $fasciculeCounts = Fascicule::published()
            ->whereIn('filiere_code', $codes)
            ->selectRaw('filiere_code, COUNT(*) AS c')
            ->groupBy('filiere_code')
            ->pluck('c', 'filiere_code');

        $annalesCounts = ExamPaper::active()
            ->whereIn('filiere', $codes)
            ->selectRaw('filiere, COUNT(*) AS c')
            ->groupBy('filiere')
            ->pluck('c', 'filiere');

        $folders = [];
        foreach ($codes as $code) {
            $fascicules = (int) ($fasciculeCounts[$code] ?? 0);
            $annales = (int) ($annalesCounts[$code] ?? 0);
            if ($fascicules + $annales === 0) {
                continue;
            }
            $folders[] = $this->folder('filiere', $code, FasciculeCatalog::filiereLabel($code), ['filiere' => $code], $fascicules, $annales, [
                'code' => $code,
                'is_transversal' => str_starts_with($code, 'T-'),
            ]);
        }
        // Filières d'abord, épreuves transversales en fin de liste.
        usort($folders, fn ($a, $b) => [$a['is_transversal'], $a['label']] <=> [$b['is_transversal'], $b['label']]);

        // Fascicules sans filière connue, rangés directement dans le domaine.
        $loose = Fascicule::published()
            ->where(fn ($q) => $q->whereNull('filiere_code')->orWhereNotIn('filiere_code', array_keys(FasciculeCatalog::FILIERES)))
            ->orderBy('display_order')->orderBy('title')
            ->get()
            ->filter(fn (Fascicule $f) => $this->effectiveDomain($f->domain, $f->filiere_code) === $domain)
            ->values();

        return [
            'title' => FasciculeCatalog::domainLabel($domain),
            'breadcrumb' => [$this->crumbDomain($domain)],
            'folders' => $folders,
            'files' => $loose->map(fn (Fascicule $f) => $this->fasciculeFile($f))->values()->all(),
        ];
    }

    private function filiereNode(string $code): array
    {
        $fascicules = Fascicule::published()
            ->where('filiere_code', $code)
            ->get()
            ->sortBy(fn (Fascicule $f) => [$this->kindOrder($f->kind), $f->display_order, $f->title])
            ->values();

        $annales = ExamPaper::active()->where('filiere', $code)->count();
        $folders = [];
        if ($annales > 0) {
            $subjects = ExamPaper::active()->where('filiere', $code)->distinct()->count('subject');
            $folders[] = [
                'type' => 'annales',
                'key' => self::SECTION_ANNALES,
                'label' => 'Annales d’examens',
                'subtitle' => $this->plural($subjects, 'matière', 'matières') . ' · ' . $this->plural($annales, 'épreuve', 'épreuves'),
                'count' => $annales,
                'query' => ['filiere' => $code, 'section' => self::SECTION_ANNALES],
            ];
        }

        return [
            'title' => FasciculeCatalog::filiereLabel($code),
            'breadcrumb' => $this->crumbsFiliere($code),
            'folders' => $folders,
            'files' => $fascicules->map(fn (Fascicule $f) => $this->fasciculeFile($f))->all(),
        ];
    }

    private function annalesNode(string $code): array
    {
        $rows = ExamPaper::active()
            ->where('filiere', $code)
            ->selectRaw('subject, COUNT(*) AS c, SUM(CASE WHEN is_correction = 1 THEN 1 ELSE 0 END) AS corr, MIN(year) AS y_min, MAX(year) AS y_max')
            ->groupBy('subject')
            ->orderBy('subject')
            ->get();

        $folders = $rows->map(function ($row) use ($code) {
            $count = (int) $row->c;
            $corrections = (int) $row->corr;
            $parts = [$this->plural($count - $corrections, 'sujet', 'sujets')];
            if ($corrections > 0) {
                $parts[] = $this->plural($corrections, 'corrigé', 'corrigés');
            }
            if ($row->y_min) {
                $parts[] = $row->y_min === $row->y_max ? (string) $row->y_min : "{$row->y_min}–{$row->y_max}";
            }

            return [
                'type' => 'subject',
                'key' => $row->subject,
                'label' => $row->subject,
                'subtitle' => implode(' · ', $parts),
                'count' => $count,
                'has_corrections' => $corrections > 0,
                'query' => ['filiere' => $code, 'section' => self::SECTION_ANNALES, 'subject' => $row->subject],
            ];
        })->values()->all();

        return [
            'title' => 'Annales d’examens',
            'breadcrumb' => $this->crumbsAnnales($code),
            'folders' => $folders,
        ];
    }

    private function subjectNode(string $code, string $subject): array
    {
        $rows = ExamPaper::active()
            ->where('filiere', $code)
            ->where('subject', $subject)
            ->selectRaw('year, COUNT(*) AS c, SUM(CASE WHEN is_correction = 1 THEN 1 ELSE 0 END) AS corr')
            ->groupBy('year')
            ->get()
            // Années récentes d'abord, « Sans année » à la fin.
            ->sortBy(fn ($row) => $row->year === null ? PHP_INT_MAX : -$row->year)
            ->values();

        // Une seule session : on montre directement ses épreuves.
        if ($rows->count() === 1) {
            $only = $rows->first();
            $node = $this->yearNode($code, $subject, $only->year === null ? self::NO_YEAR : (string) $only->year);
            $node['title'] = $subject;
            $node['breadcrumb'] = $this->crumbsSubject($code, $subject);

            return $node;
        }

        $folders = $rows->map(function ($row) use ($code, $subject) {
            $count = (int) $row->c;
            $corrections = (int) $row->corr;
            $key = $row->year === null ? self::NO_YEAR : (string) $row->year;
            $subtitle = $this->plural($count - $corrections, 'sujet', 'sujets');
            if ($corrections > 0) {
                $subtitle .= ' · ' . $this->plural($corrections, 'corrigé', 'corrigés');
            }

            return [
                'type' => 'year',
                'key' => $key,
                'label' => $row->year === null ? 'Sans année' : 'Session ' . $row->year,
                'subtitle' => $subtitle,
                'count' => $count,
                'has_corrections' => $corrections > 0,
                'query' => ['filiere' => $code, 'section' => self::SECTION_ANNALES, 'subject' => $subject, 'year' => $key],
            ];
        })->all();

        return [
            'title' => $subject,
            'breadcrumb' => $this->crumbsSubject($code, $subject),
            'folders' => $folders,
        ];
    }

    private function yearNode(string $code, string $subject, string $year): array
    {
        $papers = ExamPaper::active()
            ->where('filiere', $code)
            ->where('subject', $subject)
            ->when(
                $year === self::NO_YEAR,
                fn ($q) => $q->whereNull('year'),
                fn ($q) => $q->where('year', (int) $year)
            )
            ->orderBy('is_correction')
            ->orderBy('title')
            ->get();

        $crumbs = $this->crumbsSubject($code, $subject);
        $label = $year === self::NO_YEAR ? 'Sans année' : 'Session ' . $year;
        $crumbs[] = [
            'label' => $label,
            'query' => ['filiere' => $code, 'section' => self::SECTION_ANNALES, 'subject' => $subject, 'year' => $year],
        ];

        return [
            'title' => $label,
            'breadcrumb' => $crumbs,
            'files' => $papers->map(fn (ExamPaper $p) => $this->examFile($p, false))->all(),
        ];
    }

    private function searchNode(string $search): array
    {
        $like = '%' . addcslashes($search, '%_\\') . '%';
        $code = strtoupper($search);

        $fascicules = Fascicule::published()
            ->where(function ($q) use ($like, $code) {
                $q->where('title', 'like', $like)
                    ->orWhere('subject', 'like', $like)
                    ->orWhere('filiere_code', $code);
            })
            ->orderBy('title')
            ->limit(self::SEARCH_LIMIT)
            ->get();

        $papers = ExamPaper::active()
            ->where(function ($q) use ($like, $code) {
                $q->where('title', 'like', $like)
                    ->orWhere('subject', 'like', $like)
                    ->orWhere('filiere', $code);
            })
            ->orderByDesc('year')
            ->orderBy('title')
            ->limit(self::SEARCH_LIMIT)
            ->get();

        // Filières dont le nom correspond : raccourcis vers leur dossier.
        $needle = mb_strtolower(Str::ascii($search));
        $folders = [];
        foreach (FasciculeCatalog::FILIERES as $filiere => [$name]) {
            if (count($folders) >= 8) {
                break;
            }
            $haystack = mb_strtolower(Str::ascii($name . ' ' . $filiere));
            if (str_contains($haystack, $needle)) {
                $folders[] = [
                    'type' => 'filiere',
                    'key' => $filiere,
                    'label' => FasciculeCatalog::filiereLabel($filiere),
                    'subtitle' => FasciculeCatalog::domainLabel(FasciculeCatalog::domainOf($filiere)),
                    'count' => null,
                    'code' => $filiere,
                    'query' => ['filiere' => $filiere],
                ];
            }
        }

        return [
            'title' => null,
            'breadcrumb' => [],
            'folders' => $folders,
            'files' => array_merge(
                $fascicules->map(fn (Fascicule $f) => $this->fasciculeFile($f, true))->all(),
                $papers->map(fn (ExamPaper $p) => $this->examFile($p, true))->all(),
            ),
        ];
    }

    // ── Éléments ─────────────────────────────────────────────────────────

    private function folder(string $type, string $key, string $label, array $query, int $fascicules, int $annales, array $extra = []): array
    {
        $parts = [];
        if ($fascicules > 0) {
            $parts[] = $this->plural($fascicules, 'fascicule', 'fascicules');
        }
        if ($annales > 0) {
            $parts[] = $this->plural($annales, 'annale', 'annales');
        }

        return array_merge([
            'type' => $type,
            'key' => $key,
            'label' => $label,
            'subtitle' => implode(' · ', $parts),
            'count' => $fascicules + $annales,
            'query' => $query,
        ], $extra);
    }

    private function fasciculeFile(Fascicule $f, bool $withPath = false): array
    {
        $kindLabel = match ($f->kind) {
            FasciculeCatalog::KIND_SUPPORT => 'Support de cours',
            FasciculeCatalog::KIND_BANQUE => 'Banque d’épreuves corrigées',
            default => null,
        };
        $parts = [];
        if ($kindLabel !== null && !str_starts_with($f->title, $kindLabel)) {
            $parts[] = $kindLabel;
        }
        if ($f->subject) {
            $parts[] = $f->subject;
        }
        if ($f->volume) {
            $parts[] = 'Banque CNOENC · Vol. ' . $f->volume;
        }
        if ($withPath) {
            $parts[] = FasciculeCatalog::domainLabel($this->effectiveDomain($f->domain, $f->filiere_code));
        }
        $subtitle = implode(' · ', $parts);

        return [
            'source' => 'fascicule',
            'id' => $f->id,
            'title' => $f->title,
            'subtitle' => $subtitle,
            'kind' => $f->kind ?: FasciculeCatalog::KIND_OTHER,
            'file_type' => $f->file_type ?: Fascicule::TYPE_PDF,
            'file_size' => $f->file_size,
            'pages_count' => $f->pages_count,
            'year' => null,
            'is_correction' => false,
            'is_new' => $f->is_new,
        ];
    }

    private function examFile(ExamPaper $p, bool $withPath): array
    {
        $parts = [$p->is_correction ? 'Corrigé' : 'Sujet'];
        if ($withPath) {
            if ($p->filiere) {
                $parts[] = $p->filiere;
            }
            $parts[] = $p->subject;
        }
        if ($p->year) {
            $parts[] = (string) $p->year;
        }

        return [
            'source' => 'exam_paper',
            'id' => $p->id,
            'title' => $p->title,
            'subtitle' => implode(' · ', $parts),
            'kind' => $p->is_correction ? 'corrige' : 'sujet',
            'file_type' => 'pdf',
            'file_size' => $p->file_size,
            'pages_count' => null,
            'year' => $p->year,
            'is_correction' => (bool) $p->is_correction,
            'is_new' => false,
        ];
    }

    // ── Fil d'Ariane ─────────────────────────────────────────────────────

    private function crumbDomain(string $domain): array
    {
        return ['label' => FasciculeCatalog::domainLabel($domain), 'query' => ['domain' => $domain]];
    }

    private function crumbsFiliere(string $code): array
    {
        return [
            $this->crumbDomain(FasciculeCatalog::domainOf($code)),
            ['label' => FasciculeCatalog::filiereLabel($code), 'query' => ['filiere' => $code]],
        ];
    }

    private function crumbsAnnales(string $code): array
    {
        $crumbs = $this->crumbsFiliere($code);
        $crumbs[] = ['label' => 'Annales', 'query' => ['filiere' => $code, 'section' => self::SECTION_ANNALES]];

        return $crumbs;
    }

    private function crumbsSubject(string $code, string $subject): array
    {
        $crumbs = $this->crumbsAnnales($code);
        $crumbs[] = ['label' => $subject, 'query' => ['filiere' => $code, 'section' => self::SECTION_ANNALES, 'subject' => $subject]];

        return $crumbs;
    }

    // ── Utilitaires ──────────────────────────────────────────────────────

    /** Domaine affiché d'un fascicule : celui de sa filière, sinon le sien, sinon « Autres ». */
    private function effectiveDomain(?string $domain, ?string $filiereCode): string
    {
        if (FasciculeCatalog::hasFiliere($filiereCode)) {
            return FasciculeCatalog::domainOf($filiereCode);
        }

        return FasciculeCatalog::hasDomain($domain) ? $domain : FasciculeCatalog::DOMAIN_OTHER;
    }

    /** @return array<string,int> */
    private function examCountsByFiliere(): array
    {
        return ExamPaper::active()
            ->whereNotNull('filiere')
            ->selectRaw('filiere, COUNT(*) AS c')
            ->groupBy('filiere')
            ->pluck('c', 'filiere')
            ->map(fn ($c) => (int) $c)
            ->all();
    }

    private function kindOrder(?string $kind): int
    {
        return match ($kind) {
            FasciculeCatalog::KIND_SUPPORT => 0,
            FasciculeCatalog::KIND_BANQUE => 1,
            default => 2,
        };
    }

    private function plural(int $n, string $one, string $many): string
    {
        return $n . ' ' . ($n > 1 ? $many : $one);
    }

    private function param(Request $request, string $key): ?string
    {
        $value = $request->query($key);
        if (is_int($value)) {
            $value = (string) $value;
        }
        if (!is_string($value)) {
            return null;
        }
        $value = trim($value);

        return $value === '' ? null : $value;
    }

    private function hasStudentMode(): bool
    {
        $user = Auth::user();

        return $user ? $user->hasStudentMode() : false;
    }
}
