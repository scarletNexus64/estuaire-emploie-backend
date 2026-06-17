<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\CompanyCategory;
use App\Models\Resume;
use App\Models\User;
use App\Services\Recruiter\RecruiterServicePurchaseService;
use App\Services\Resume\ResumePdfService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * CVThèque côté recruteur : liste paginée de TOUS les candidats disposant d'un
 * CV, quelle que soit la source (CV construit dans l'app via le builder /
 * table resumes, CV uploadé lors d'une candidature, ou CV legacy sur le
 * profil). Permet la recherche, le filtrage et expose has_full_access par
 * candidat (coordonnées débloquées via le service candidate_contact).
 */
class RecruiterCvLibraryController extends Controller
{
    /**
     * Seuil minimal de complétude d'un CV builder pour apparaître dans la
     * CVThèque. Les CV incomplets (< 80 %) sont exclus.
     */
    private const MIN_COMPLETENESS = 80;

    /**
     * GET /api/recruiter/cv-library
     *
     * Query params (optionnels) :
     *   - page, per_page (défaut 10)
     *   - search        : mot-clé libre (compétences, spécialité, titre du CV,
     *                     email — PAS le nom, masqué dans la liste)
     *   - specialty     : filtre par spécialité (valeur exacte renvoyée par
     *                     l'endpoint /specialties)
     *   - level_1/2/3   : filtre par catégorie d'entreprise (cascade). On garde
     *                     le niveau le plus précis fourni et on déduit les
     *                     candidats par correspondance texte sur leurs CV.
     *   - has_cv        : true => uniquement les candidats avec un CV (défaut)
     *   - has_portfolio : true => uniquement avec portfolio
     */
    public function index(Request $request): JsonResponse
    {
        $user = auth()->user();
        $companyId = $user->current_company_id;

        if (! $companyId) {
            return response()->json([
                'message' => __('application.select_active_company'),
                'error_code' => 'NO_CURRENT_COMPANY',
            ], 409);
        }

        // Consultation de la CVThèque ouverte à tout recruteur. L'abonnement
        // n'est PAS requis pour lister/voir les CV ; il conditionne seulement
        // les actions premium (chat, infos détaillées) côté client via ce flag.
        $hasActiveSubscription = $user->hasActiveSubscription();

        $perPage = (int) $request->input('per_page', 10);
        $perPage = max(1, min($perPage, 50));

        // IDs des candidats possédant un CV SUFFISAMMENT COMPLET (≥ 80 %).
        // On exclut les CV incomplets : seul un resume builder dont la complétude
        // atteint le seuil compte. Les CV uploadés (fichier PDF de candidature ou
        // legacy) sont considérés finis par nature et restent éligibles.
        $resumeUserIds = $this->completeCvUserIds(self::MIN_COMPLETENESS);
        $applicationCvUserIds = Application::query()
            ->whereNotNull('cv_path')
            ->where('cv_path', '!=', '')
            ->distinct()
            ->pluck('user_id');

        $candidatesWithCv = $resumeUserIds
            ->merge($applicationCvUserIds)
            ->unique()
            ->values();

        $query = User::query()
            ->where('role', 'candidate')
            ->where('is_active', true);

        // Exclure les CV "placeholder" / non personnalisés : identité factice du
        // type "NOM PRÉNOM", "[NOM] [Prénom]", "N/A" ou email prenom.nom@email.com.
        // Ces comptes de démo génèrent des CV au contenu générique non exploitable.
        $placeholderIds = $this->placeholderUserIds();
        if ($placeholderIds->isNotEmpty()) {
            $query->whereNotIn('id', $placeholderIds);
        }

        // Le nom est désormais affiché dans la liste : n'exposer que les candidats
        // ayant un nom réel (non null / non vide). Sans cela, des cartes "sans nom"
        // apparaîtraient à la place du nom attendu.
        $query->whereNotNull('name')->where('name', '!=', '');

        // Filtre "a un CV" (activé par défaut) : resume OU application CV OU cv_path legacy.
        $hasCv = filter_var($request->input('has_cv', true), FILTER_VALIDATE_BOOLEAN);
        if ($hasCv) {
            $query->where(function ($q) use ($candidatesWithCv) {
                $q->whereIn('id', $candidatesWithCv)
                    ->orWhere(function ($qq) {
                        $qq->whereNotNull('cv_path')->where('cv_path', '!=', '');
                    });
            });
        }

        // Filtre par spécialité : la spécialité vit dans resumes.customization->specialty.
        // On restreint aux users ayant au moins un CV dans cette spécialité.
        if ($request->filled('specialty')) {
            $specialty = $request->specialty;
            $specialtyUserIds = Resume::where('customization->specialty', $specialty)
                ->distinct()
                ->pluck('user_id');
            $query->whereIn('id', $specialtyUserIds);
        }

        // Filtre par catégorie d'entreprise (taxonomie level_1 > level_2 > level_3).
        // Cette taxonomie est rattachée aux entreprises/offres, PAS aux CV : on
        // déduit l'appartenance d'un candidat en faisant correspondre les libellés
        // de catégorie au texte de ses CV (spécialité, titre, résumé, compétences).
        // On part du niveau le plus précis fourni (level_3 > level_2 > level_1).
        $categoryTerms = $this->resolveCategoryTerms($request);
        if ($categoryTerms !== null) {
            // Aucun terme exploitable => aucun candidat ne peut matcher.
            if (empty($categoryTerms)) {
                $query->whereRaw('1 = 0');
            } else {
                $categoryUserIds = Resume::where(function ($q) use ($categoryTerms) {
                    foreach ($categoryTerms as $term) {
                        $q->orWhere('customization->specialty', 'like', "%{$term}%")
                            ->orWhere('title', 'like', "%{$term}%")
                            ->orWhere('professional_summary', 'like', "%{$term}%")
                            ->orWhere('skills', 'like', "%{$term}%");
                    }
                })->distinct()->pluck('user_id');

                $query->where(function ($q) use ($categoryTerms, $categoryUserIds) {
                    $q->whereIn('id', $categoryUserIds);
                    foreach ($categoryTerms as $term) {
                        $q->orWhere('skills', 'like', "%{$term}%")
                            ->orWhere('bio', 'like', "%{$term}%");
                    }
                });
            }
        }

        // Recherche : n'importe quel mot-clé. Couvre le nom et les compétences et
        // l'email du user, ainsi que la spécialité / le titre / le résumé pro des CV.
        if ($request->filled('search')) {
            $search = trim($request->search);
            $resumeMatchIds = Resume::where(function ($q) use ($search) {
                $q->where('customization->specialty', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%")
                    ->orWhere('professional_summary', 'like', "%{$search}%")
                    ->orWhere('skills', 'like', "%{$search}%");
            })->distinct()->pluck('user_id');

            $query->where(function ($q) use ($search, $resumeMatchIds) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('skills', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('bio', 'like', "%{$search}%")
                    ->orWhereIn('id', $resumeMatchIds);
            });
        }

        if (filter_var($request->input('has_portfolio', false), FILTER_VALIDATE_BOOLEAN)) {
            $query->whereHas('portfolio');
        }

        $query->with(['portfolio:id,user_id,slug,title,bio']);

        $candidates = $query->orderByDesc('visibility_score')
            ->orderByDesc('updated_at')
            ->paginate($perPage);

        $purchaseService = app(RecruiterServicePurchaseService::class);
        $company = $user->currentCompany;
        // Si on filtre par spécialité, l'afficher en priorité (un candidat peut
        // avoir plusieurs CV de spécialités différentes).
        $preferredSpecialty = $request->filled('specialty') ? $request->specialty : null;

        $candidates->getCollection()->transform(function (User $candidate) use ($company, $purchaseService, $preferredSpecialty, $hasActiveSubscription) {
            $hasFullAccess = $purchaseService->hasAccessToCandidateContact($company, $candidate);

            // Tout candidat listé a déjà un CV complet (≥ seuil) ou un fichier
            // uploadé — la liste est filtrée en amont. has_cv est donc toujours vrai.
            $cvFile = $this->resolveCvPath($candidate);

            $item = [
                'id' => $candidate->id,
                // Le nom du candidat est désormais affiché dans la liste (filtré
                // en amont pour exclure les noms factices/vides).
                'name' => $candidate->name,
                'specialty' => $this->resolveSpecialty($candidate, $preferredSpecialty),
                'experience_level' => $candidate->experience_level,
                'profile_photo' => $candidate->profile_photo,
                'skills' => $this->normalizeSkills($candidate->skills),
                'has_cv' => true,
                // Le CV s'ouvre via GET /recruiter/cv-library/{id}/cv (premium).
                // On n'expose pas le chemin direct ici sans abonnement.
                'cv_path' => $hasActiveSubscription ? $cvFile : null,
                'portfolio_url' => $candidate->portfolio_url,
                'portfolio' => $candidate->portfolio ? [
                    'slug' => $candidate->portfolio->slug,
                    'title' => $candidate->portfolio->title,
                    'bio' => $candidate->portfolio->bio,
                ] : null,
                'has_full_access' => $hasFullAccess,
                // Coordonnées : seulement si débloquées (le nom, lui, est public).
                'email' => $hasFullAccess ? $candidate->email : null,
                'phone' => $hasFullAccess ? $candidate->phone : null,
            ];

            return $item;
        });

        $response = $candidates->toArray();
        // Flag consommé par le client : conditionne les actions premium
        // (chat, déblocage coordonnées, infos détaillées) sans bloquer la consultation.
        $response['has_active_subscription'] = $hasActiveSubscription;

        return response()->json($response);
    }

    /**
     * GET /api/recruiter/cv-library/specialties
     *
     * Liste distincte et triée des spécialités disponibles (issues des CV),
     * pour alimenter le filtre côté client.
     */
    public function specialties(): JsonResponse
    {
        // Ne lister que les spécialités de CV réellement affichés : complétude
        // ≥ seuil et hors comptes placeholder.
        $placeholderIds = $this->placeholderUserIds();
        $completeUserIds = $this->completeCvUserIds(self::MIN_COMPLETENESS);

        $specialties = Resume::whereNotNull('customization->specialty')
            ->whereIn('user_id', $completeUserIds)
            ->when($placeholderIds->isNotEmpty(),
                fn ($q) => $q->whereNotIn('user_id', $placeholderIds))
            ->get()
            ->map(fn (Resume $r) => trim((string) ($r->customization['specialty'] ?? '')))
            ->filter()
            ->unique()
            ->sort()
            ->values()
            ->all();

        return response()->json(['data' => $specialties]);
    }

    /**
     * GET /api/recruiter/cv-library/{userId}/cv
     *
     * Renvoie l'URL du CV (PDF) d'un candidat — ACTION PREMIUM : nécessite un
     * abonnement recruteur actif. Si le CV builder n'a pas encore de PDF généré,
     * il est généré à la volée. Sert aussi le cv_path legacy / de candidature.
     */
    public function cv(Request $request, int $userId, ResumePdfService $pdfService): JsonResponse
    {
        // L'abonnement (actif, non expiré, accès CVthèque) est vérifié par le
        // middleware 'subscription:feature_cvtheque' sur la route — même garde
        // que POST /conversations. Pas de re-vérification inline ici.

        $candidate = User::where('role', 'candidate')->find($userId);
        if (! $candidate) {
            return response()->json([
                'message' => __('resume.not_found'),
                'error_code' => 'CANDIDATE_NOT_FOUND',
            ], 404);
        }

        // 1) Un PDF déjà disponible (builder pdf_path, legacy, ou candidature) ?
        $existing = $this->resolveCvPath($candidate);
        if (! empty($existing)) {
            return response()->json([
                'success' => true,
                'cv_path' => $existing,
                'cv_url' => url('storage/' . ltrim($existing, '/')),
            ]);
        }

        // 2) Sinon, générer le PDF du meilleur resume SUFFISAMMENT COMPLET
        //    (≥ seuil) du candidat. On ne sert jamais un CV incomplet.
        $resume = $this->nonEmptyResumeQuery()
            ->where('user_id', $candidate->id)
            ->orderByDesc('is_default')
            ->latest('updated_at')
            ->get()
            ->first(fn (Resume $r) => $r->calculateCompleteness() >= self::MIN_COMPLETENESS);

        if (! $resume) {
            return response()->json([
                'message' => __('resume.not_found'),
                'error_code' => 'NO_CV',
            ], 404);
        }

        try {
            $pdfPath = $pdfService->generatePdf($resume);
            $resume->refresh();

            return response()->json([
                'success' => true,
                'cv_path' => $pdfPath,
                'cv_url' => $resume->pdf_url ?? url('storage/' . ltrim($pdfPath, '/')),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('resume.pdf_error'),
            ], 500);
        }
    }

    /**
     * GET /api/recruiter/cv-library/categories
     *
     * Arbre hiérarchique des catégories d'entreprise (level_1 > level_2 > level_3)
     * pour alimenter la cascade de filtres de la CVThèque côté client. Reprend la
     * forme renvoyée par /company-categories/hierarchical.
     */
    public function categories(): JsonResponse
    {
        $categories = CompanyCategory::active()
            ->with('translations')
            ->orderBy('level_1')
            ->orderBy('level_2')
            ->orderBy('level_3')
            ->get();

        $tree = [];
        foreach ($categories as $category) {
            $l1 = $category->level_1;
            if (! isset($tree[$l1])) {
                $tree[$l1] = ['name' => $category->t('level_1'), 'value' => $l1, 'level_2' => []];
            }

            if (! $category->level_2) {
                continue;
            }
            $l2 = $category->level_2;
            if (! isset($tree[$l1]['level_2'][$l2])) {
                $tree[$l1]['level_2'][$l2] = ['name' => $category->t('level_2'), 'value' => $l2, 'level_3' => []];
            }

            if ($category->level_3) {
                $tree[$l1]['level_2'][$l2]['level_3'][] = [
                    'name' => $category->t('level_3'),
                    'value' => $category->level_3,
                    'code' => $category->code,
                ];
            }
        }

        $data = array_map(function ($l1) {
            $l1['level_2'] = array_values(array_map(function ($l2) {
                $l2['level_3'] = array_values($l2['level_3']);

                return $l2;
            }, $l1['level_2']));

            return $l1;
        }, array_values($tree));

        return response()->json(['data' => $data]);
    }

    /**
     * Résout les termes de recherche à partir des filtres de catégorie level_1/2/3.
     *
     * Retourne :
     *   - null  : aucun filtre de catégorie fourni (pas de restriction)
     *   - []    : un filtre fourni mais sans terme exploitable (=> 0 résultat)
     *   - [...] : la liste des libellés à faire correspondre au texte des CV
     *
     * On retient le niveau le plus précis renseigné (level_3 sinon level_2 sinon
     * level_1) et on ajoute les libellés des niveaux parents matchés en base pour
     * élargir la correspondance (ex. "Bloc opératoire" + "Soins infirmiers").
     */
    private function resolveCategoryTerms(Request $request): ?array
    {
        $level1 = trim((string) $request->input('level_1', ''));
        $level2 = trim((string) $request->input('level_2', ''));
        $level3 = trim((string) $request->input('level_3', ''));

        if ($level1 === '' && $level2 === '' && $level3 === '') {
            return null;
        }

        $query = CompanyCategory::active();
        if ($level1 !== '') {
            $query->where('level_1', $level1);
        }
        if ($level2 !== '') {
            $query->where('level_2', $level2);
        }
        if ($level3 !== '') {
            $query->where('level_3', $level3);
        }

        $rows = $query->get(['level_1', 'level_2', 'level_3']);

        // Le niveau le plus précis demandé porte le terme le plus discriminant ;
        // sa valeur brute est toujours un terme candidat même si la catégorie
        // n'existe pas (filtre saisi librement côté client).
        $terms = [];
        $deepestRequested = $level3 !== '' ? $level3 : ($level2 !== '' ? $level2 : $level1);
        if ($deepestRequested !== '') {
            $terms[] = $deepestRequested;
        }

        foreach ($rows as $row) {
            foreach ([$row->level_1, $row->level_2, $row->level_3] as $label) {
                $label = trim((string) $label);
                if ($label !== '') {
                    $terms[] = $label;
                }
            }
        }

        return array_values(array_unique($terms));
    }

    /**
     * Résout le meilleur chemin de CV pour un candidat, par ordre de priorité :
     *   1. CV par défaut du builder (resumes.pdf_path)
     *   2. Dernier resume avec un PDF généré
     *   3. cv_path legacy du profil utilisateur
     *   4. Dernier CV uploadé via une candidature (applications.cv_path)
     */
    /**
     * IDs des candidats ayant au moins un resume builder dont la complétude
     * atteint [$min] %. La complétude est calculée par le modèle (PHP), donc on
     * itère sur les resumes non vides en ne chargeant que les colonnes utiles.
     */
    private function completeCvUserIds(int $min)
    {
        return $this->nonEmptyResumeQuery()
            ->select([
                'id', 'user_id', 'personal_info', 'professional_summary',
                'experiences', 'education', 'skills', 'certifications', 'projects',
            ])
            ->get()
            ->filter(fn (Resume $r) => $r->calculateCompleteness() >= $min)
            ->pluck('user_id')
            ->unique()
            ->values();
    }

    /**
     * IDs des candidats à EXCLURE car leur CV n'est pas personnalisé : identité
     * placeholder ("NOM PRÉNOM", "[NOM] [Prénom]", "Nom Prénom", "N/A") ou email
     * factice (prenom.nom@email.com, *@email.com). Détecté sur le profil user ET
     * sur personal_info des resumes (les comptes de démo génèrent des CV en masse).
     */
    private function placeholderUserIds()
    {
        $nameLikes = ['NOM PR%', '%[NOM]%', 'Nom Prénom', 'N/A'];
        $emailLikes = ['%@email.com', 'prenom.nom@%'];

        // Via le profil utilisateur.
        $byUser = User::where('role', 'candidate')
            ->where(function ($q) use ($nameLikes, $emailLikes) {
                foreach ($nameLikes as $n) {
                    $q->orWhere('name', 'like', $n);
                }
                foreach ($emailLikes as $e) {
                    $q->orWhere('email', 'like', $e);
                }
            })
            ->pluck('id');

        // Via le contenu des resumes (personal_info, colonne JSON brute).
        $byResume = Resume::where(function ($q) {
            $q->where('personal_info', 'like', '%@email.com%')
                ->orWhere('personal_info', 'like', '%NOM PR%')
                ->orWhere('personal_info', 'like', '%[NOM]%')
                ->orWhere('personal_info', 'like', '%[Prénom]%');
        })->distinct()->pluck('user_id');

        return $byUser->merge($byResume)->unique()->values();
    }

    /**
     * Query des resumes NON VIDES : un CV est considéré rempli s'il porte au
     * moins un contenu réel (expériences, formation, résumé pro ou compétences),
     * au-delà des seules informations personnelles qui sont auto-renseignées.
     * Évite de lister des candidats dont le seul CV est une ébauche vide.
     */
    private function nonEmptyResumeQuery()
    {
        return Resume::where(function ($w) {
            $w->where(function ($x) {
                $x->whereNotNull('experiences')
                    ->where('experiences', '!=', '[]')
                    ->where('experiences', '!=', 'null')
                    ->where('experiences', '!=', '');
            })->orWhere(function ($x) {
                $x->whereNotNull('education')
                    ->where('education', '!=', '[]')
                    ->where('education', '!=', 'null')
                    ->where('education', '!=', '');
            })->orWhere(function ($x) {
                $x->whereNotNull('professional_summary')
                    ->where('professional_summary', '!=', '');
            })->orWhere(function ($x) {
                $x->whereNotNull('skills')
                    ->where('skills', '!=', '[]')
                    ->where('skills', '!=', 'null')
                    ->where('skills', '!=', '');
            });
        });
    }

    private function resolveCvPath(User $candidate): ?string
    {
        $default = Resume::where('user_id', $candidate->id)
            ->where('is_default', true)
            ->whereNotNull('pdf_path')
            ->value('pdf_path');
        if ($default) {
            return $default;
        }

        $latestResume = Resume::where('user_id', $candidate->id)
            ->whereNotNull('pdf_path')
            ->latest('updated_at')
            ->value('pdf_path');
        if ($latestResume) {
            return $latestResume;
        }

        if (! empty($candidate->cv_path)) {
            return $candidate->cv_path;
        }

        return Application::where('user_id', $candidate->id)
            ->whereNotNull('cv_path')
            ->where('cv_path', '!=', '')
            ->latest('created_at')
            ->value('cv_path');
    }

    /**
     * Spécialité affichée pour un candidat : celle de son CV par défaut sinon
     * celle de son resume le plus récent qui en porte une.
     */
    private function resolveSpecialty(User $candidate, ?string $preferred = null): ?string
    {
        // Si une spécialité est filtrée et que le candidat la possède, l'afficher.
        if ($preferred !== null && $preferred !== '') {
            $hasPreferred = Resume::where('user_id', $candidate->id)
                ->where('customization->specialty', $preferred)
                ->exists();
            if ($hasPreferred) {
                return $preferred;
            }
        }

        $resume = Resume::where('user_id', $candidate->id)
            ->whereNotNull('customization->specialty')
            ->orderByDesc('is_default')
            ->latest('updated_at')
            ->first();

        $specialty = $resume?->customization['specialty'] ?? null;
        $specialty = $specialty !== null ? trim((string) $specialty) : null;

        return $specialty !== '' ? $specialty : null;
    }

    /**
     * Normalise les compétences en tableau de chaînes.
     * La colonne `skills` peut contenir du JSON ("[...]") ou du CSV libre.
     */
    private function normalizeSkills($skills): array
    {
        if (empty($skills)) {
            return [];
        }

        if (is_array($skills)) {
            return array_values(array_filter(array_map('trim', $skills)));
        }

        $decoded = json_decode($skills, true);
        if (is_array($decoded)) {
            return array_values(array_filter(array_map(fn ($s) => trim((string) $s), $decoded)));
        }

        return array_values(array_filter(array_map('trim', explode(',', $skills))));
    }
}
