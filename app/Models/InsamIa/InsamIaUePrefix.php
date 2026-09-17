<?php

namespace App\Models\InsamIa;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Rattachement d'un préfixe de code UE à une filière et, si possible, à une
 * spécialité du référentiel.
 *
 * La bibliothèque INSAM-IA ne décrit ses supports que par un `ue_code` du type
 * « IGL111 » : filtrer les cours d'un étudiant supposerait d'interroger
 * `course-materials` code par code, ce qui est hors de portée en requête.
 * Cette table est la table de correspondance construite une fois pour toutes
 * par `insam-ia:sync-ue-prefixes`.
 *
 * Le rapprochement avec `/api/public/categories` n'aboutit pas partout : les
 * deux référentiels ne se recouvrent que partiellement. Un préfixe sans
 * [category_id] reste exploitable via son [filiere_label].
 */
class InsamIaUePrefix extends Model
{
    protected $table = 'insam_ia_ue_prefixes';

    protected $fillable = [
        'prefix',
        'filiere_label',
        'category_id',
        'category_name',
        'documents_count',
        'synced_at',
    ];

    protected function casts(): array
    {
        return [
            'category_id' => 'integer',
            'documents_count' => 'integer',
            'synced_at' => 'datetime',
        ];
    }

    // ------------------------------------------------------------------
    // Résolution code UE ↔ spécialité
    // ------------------------------------------------------------------

    /**
     * Partie alphabétique d'un code UE : « IGL111 » → « IGL ».
     *
     * Tolère les codes mal formés (minuscules, espaces) reçus d'INSAM-IA.
     */
    public static function prefixOf(?string $ueCode): ?string
    {
        $code = strtoupper(trim((string) $ueCode));

        if ($code === '' || !preg_match('/^([A-Z]+)/', $code, $matches)) {
            return null;
        }

        return $matches[1];
    }

    /**
     * Niveau d'études porté par un code UE : le premier des trois chiffres.
     *
     * « IGL111 » → 1, « BAT322 » → 3. Renvoie null si le code ne suit pas la
     * convention (préfixe + 3 chiffres), plutôt que d'inventer un niveau.
     */
    public static function levelOf(?string $ueCode): ?int
    {
        $code = strtoupper(trim((string) $ueCode));

        if (!preg_match('/^[A-Z]+(\d)\d{2}$/', $code, $matches)) {
            return null;
        }

        return (int) $matches[1];
    }

    /**
     * Préfixes rattachés à une spécialité et/ou à un libellé de filière.
     *
     * Les deux critères sont combinés en OU : une spécialité couvre rarement
     * tous les préfixes de sa filière, et l'appelant qui fournit les deux veut
     * l'union — mieux vaut un cours de trop qu'un écran vide.
     *
     * Sans aucun critère, renvoie une liste vide : ne jamais retourner le
     * catalogue entier par accident.
     *
     * @return array<int, string>
     */
    public static function prefixesFor(?int $categoryId = null, ?string $filiereLabel = null): array
    {
        $label = trim((string) $filiereLabel);

        if ($categoryId === null && $label === '') {
            return [];
        }

        return static::query()
            ->where(function (Builder $query) use ($categoryId, $label) {
                if ($categoryId !== null) {
                    $query->orWhere('category_id', $categoryId);
                }

                if ($label !== '') {
                    $query->orWhere('filiere_label', $label);
                }
            })
            ->orderByDesc('documents_count')
            ->pluck('prefix')
            ->all();
    }

    /**
     * Spécialité et filière d'un code UE, ou null si le préfixe est inconnu.
     *
     * @return array{prefix: string, filiere_label: ?string, category_id: ?int, category_name: ?string}|null
     */
    public static function resolve(?string $ueCode): ?array
    {
        $prefix = static::prefixOf($ueCode);

        if ($prefix === null) {
            return null;
        }

        /** @var static|null $row */
        $row = static::query()->where('prefix', $prefix)->first();

        if ($row === null) {
            return null;
        }

        return [
            'prefix' => $row->prefix,
            'filiere_label' => $row->filiere_label,
            'category_id' => $row->category_id,
            'category_name' => $row->category_name,
        ];
    }

    /**
     * Le préfixe d'un code UE figure-t-il parmi ceux d'une spécialité ?
     *
     * Raccourci du filtrage en mémoire : la liste de préfixes est chargée une
     * fois puis testée sur chaque document, sans requête par document.
     *
     * @param  array<int, string>  $prefixes
     */
    public static function matches(?string $ueCode, array $prefixes): bool
    {
        $prefix = static::prefixOf($ueCode);

        return $prefix !== null && in_array($prefix, $prefixes, true);
    }

    /**
     * Table de correspondance complète, indexée par préfixe.
     *
     * @return Collection<string, static>
     */
    public static function map(): Collection
    {
        return static::query()->get()->keyBy('prefix');
    }
}
