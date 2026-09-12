<?php

namespace App\Models\InsamIa;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Fiche de révision INSAM-IA conservée localement.
 *
 * Générer une fiche mobilise ~45 s de calcul chez INSAM-IA : le contenu reçu
 * est stocké ici pour être resservi instantanément et rester consultable quand
 * le service tiers ne répond pas.
 *
 * L'application continue de désigner les fiches par leur identifiant distant
 * ([remote_id]) : c'est lui que porte la progression de lecture.
 */
class InsamIaRevisionCard extends Model
{
    protected $table = 'insam_ia_revision_cards';

    protected $fillable = [
        'remote_id',
        'title',
        'summary',
        'key_points',
        'content',
        'status',
        'source',
        'category_id',
        'category_name',
        'category_filiere',
        'generated_by',
        'remote_updated_at',
        'synced_at',
    ];

    protected function casts(): array
    {
        return [
            'remote_id' => 'integer',
            'category_id' => 'integer',
            'key_points' => 'array',
            'remote_updated_at' => 'datetime',
            'synced_at' => 'datetime',
        ];
    }

    public function generatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'generated_by');
    }

    /**
     * La fiche porte-t-elle son contenu Markdown ?
     *
     * Une fiche synchronisée depuis la liste distante n'a que son résumé : il
     * faut alors interroger INSAM-IA pour obtenir le corps complet.
     */
    public function hasContent(): bool
    {
        return filled($this->content);
    }

    /**
     * Représentation servie par l'API Estuaire, identique à celle construite
     * depuis une réponse INSAM-IA : l'application ne voit pas la différence
     * entre une fiche fraîchement récupérée et une fiche resservie du cache.
     *
     * @return array<string, mixed>
     */
    public function toApiArray(bool $withContent = false): array
    {
        $payload = [
            'id' => $this->remote_id,
            'title' => $this->title ?? '',
            'summary' => $this->summary,
            'key_points' => $this->key_points ?? [],
            'status' => $this->status,
            'source' => $this->source,
            'category' => $this->category_id !== null
                ? [
                    'id' => $this->category_id,
                    'name' => $this->category_name ?? '',
                    'filiere' => $this->category_filiere,
                ]
                : null,
            'updated_at' => optional($this->remote_updated_at)->toIso8601String(),
        ];

        if ($withContent) {
            $payload['content'] = $this->content;
        }

        return $payload;
    }
}
