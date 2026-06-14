<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Roadmap d'apprentissage gamifiée.
 *
 * Un parcours composé de niveaux (RoadmapLevel) débloqués un à un via QCM.
 * Calqué sur Program mais orienté "jeu" : domaine, difficulté, couleur, XP.
 */
class Roadmap extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'title',
        'slug',
        'domain',
        'description',
        'objectives',
        'icon',
        'color',
        'difficulty',
        'required_packs',
        'pass_threshold',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
        'pass_threshold' => 'integer',
        'required_packs' => 'array',
    ];

    /**
     * Champs traduisibles via la table polymorphe `translations`.
     */
    protected array $translatable = ['title', 'description', 'objectives'];

    /**
     * Les niveaux de la roadmap, ordonnés.
     */
    public function levels(): HasMany
    {
        return $this->hasMany(RoadmapLevel::class)->orderBy('order');
    }

    /**
     * Les progressions des utilisateurs sur cette roadmap.
     */
    public function userProgress(): HasMany
    {
        return $this->hasMany(UserRoadmapProgress::class);
    }

    /**
     * Libellé humain du domaine (fallback ; le rendu localisé se fait côté app).
     */
    public function getDomainDisplayAttribute(): string
    {
        return match ($this->domain) {
            'developpement' => 'Développement',
            'ecommerce' => 'E-commerce',
            'marketing' => 'Marketing Digital',
            'langues' => 'Langues',
            'sql' => 'Bases de données / SQL',
            'blockchain' => 'Blockchain & Web3',
            'data' => 'Data & Intelligence Artificielle',
            'design' => 'Design & UX/UI',
            'cybersecurite' => 'Cybersécurité',
            'finance' => 'Finance & Comptabilité',
            'bureautique' => 'Bureautique',
            'soft_skills' => 'Soft Skills',
            'juridique' => 'Droit & Juridique',
            'sante' => 'Santé & Médical',
            'ingenierie' => 'Ingénierie & Industrie',
            'gestion' => 'Gestion & Management',
            'rh' => 'Ressources Humaines',
            'communication' => 'Communication & Médias',
            'education' => 'Éducation & Formation',
            'agriculture' => 'Agriculture & Agro',
            'btp' => 'BTP & Construction',
            'artisanat' => 'Artisanat & Métiers manuels',
            'hotellerie' => 'Hôtellerie & Restauration',
            'transport' => 'Transport & Logistique',
            'environnement' => 'Environnement & Développement durable',
            'audiovisuel' => 'Audiovisuel & Création',
            default => ucfirst($this->domain),
        };
    }

    /**
     * Libellé humain de la difficulté.
     */
    public function getDifficultyDisplayAttribute(): string
    {
        return match ($this->difficulty) {
            'beginner' => 'Débutant',
            'intermediate' => 'Intermédiaire',
            'advanced' => 'Avancé',
            'expert' => 'Expert',
            default => $this->difficulty,
        };
    }
}
