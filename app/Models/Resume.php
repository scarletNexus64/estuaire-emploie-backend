<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Resume extends Model
{
    use HasFactory, SoftDeletes;

    protected $appends = ['pdf_url'];

    protected $fillable = [
        'user_id',
        'title',
        'template_type',
        'personal_info',
        'professional_summary',
        'experiences',
        'education',
        'skills',
        'certifications',
        'projects',
        'references',
        'hobbies',
        'customization',
        'pdf_path',
        'pdf_generated_at',
        'is_public',
        'is_default',
    ];

    protected $casts = [
        'personal_info' => 'array',
        'experiences' => 'array',
        'education' => 'array',
        'skills' => 'array',
        'certifications' => 'array',
        'projects' => 'array',
        'references' => 'array',
        'hobbies' => 'array',
        'customization' => 'array',
        'pdf_generated_at' => 'datetime',
        'is_public' => 'boolean',
        'is_default' => 'boolean',
    ];

    /**
     * Relation vers l'utilisateur propriétaire du CV
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope pour récupérer les CVs publics
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    /**
     * Scope pour récupérer les CVs par template
     */
    public function scopeByTemplate($query, string $templateType)
    {
        return $query->where('template_type', $templateType);
    }

    /**
     * Récupère le CV par défaut d'un utilisateur
     */
    public static function getUserDefaultResume(int $userId): ?Resume
    {
        return static::where('user_id', $userId)
            ->where('is_default', true)
            ->first();
    }

    /**
     * Définit ce CV comme CV par défaut (retire le flag des autres)
     */
    public function setAsDefault(): bool
    {
        // Retirer le flag default des autres CVs de l'utilisateur
        static::where('user_id', $this->user_id)
            ->where('id', '!=', $this->id)
            ->update(['is_default' => false]);

        // Définir ce CV comme default
        return $this->update(['is_default' => true]);
    }

    /**
     * Vérifie si le PDF doit être régénéré
     */
    public function shouldRegeneratePdf(): bool
    {
        // Si pas de PDF
        if (!$this->pdf_path) {
            return true;
        }

        // Si le CV a été modifié après la génération du PDF
        if ($this->pdf_generated_at && $this->updated_at > $this->pdf_generated_at) {
            return true;
        }

        return false;
    }

    /**
     * Retourne l'URL complète du PDF
     */
    public function getPdfUrlAttribute(): ?string
    {
        if (!$this->pdf_path) {
            return null;
        }

        return url('storage/' . $this->pdf_path);
    }

    /**
     * Liste des templates disponibles
     */
    public static function getAvailableTemplates(): array
    {
        return [
            [
                'type' => 'modern',
                'name' => 'Moderne',
                'description' => 'Design épuré et contemporain avec mise en page en deux colonnes',
                'preview_image' => 'templates/previews/modern.jpg',
                'features' => ['Deux colonnes', 'Icônes modernes', 'Couleurs personnalisables'],
            ],
            [
                'type' => 'classic',
                'name' => 'Classique',
                'description' => 'Format traditionnel et professionnel, idéal pour les secteurs conservateurs',
                'preview_image' => 'templates/previews/classic.jpg',
                'features' => ['Une colonne', 'Sobre et élégant', 'Police serif'],
            ],
            [
                'type' => 'creative',
                'name' => 'Créatif',
                'description' => 'Design audacieux pour les métiers créatifs et artistiques',
                'preview_image' => 'templates/previews/creative.jpg',
                'features' => ['Design original', 'Couleurs vives', 'Mise en page unique'],
            ],
            [
                'type' => 'professional',
                'name' => 'Professionnel',
                'description' => 'Équilibre parfait entre modernité et sérieux',
                'preview_image' => 'templates/previews/professional.jpg',
                'features' => ['Mise en page claire', 'Hiérarchie visuelle', 'Couleurs sobres'],
            ],
            [
                'type' => 'minimalist',
                'name' => 'Minimaliste',
                'description' => 'Simplicité et élégance avec focus sur le contenu',
                'preview_image' => 'templates/previews/minimalist.jpg',
                'features' => ['Design épuré', 'Beaucoup d\'espace blanc', 'Typographie soignée'],
            ],
        ];
    }

    /**
     * Retourne les informations du template utilisé
     */
    public function getTemplateInfoAttribute(): ?array
    {
        $templates = static::getAvailableTemplates();

        foreach ($templates as $template) {
            if ($template['type'] === $this->template_type) {
                return $template;
            }
        }

        return null;
    }

    /**
     * Retourne un résumé du CV pour l'affichage
     */
    public function getSummary(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'template' => $this->template_info,
            'has_pdf' => !empty($this->pdf_path),
            'pdf_url' => $this->pdf_url,
            'is_default' => $this->is_default,
            'is_public' => $this->is_public,
            'completeness' => $this->calculateCompleteness(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }

    /**
     * Calcule le pourcentage de complétion du CV
     */
    public function calculateCompleteness(): int
    {
        $fields = [
            'personal_info' => 20,
            'professional_summary' => 10,
            'experiences' => 25,
            'education' => 20,
            'skills' => 15,
            'certifications' => 5,
            'projects' => 5,
        ];

        $totalScore = 0;

        foreach ($fields as $field => $weight) {
            $value = $this->$field;

            if ($field === 'personal_info') {
                // Vérifier les champs requis dans personal_info
                if (is_array($value) && !empty($value['name']) && !empty($value['email'])) {
                    $totalScore += $weight;
                }
            } elseif ($field === 'professional_summary') {
                if (!empty($value)) {
                    $totalScore += $weight;
                }
            } else {
                // Pour les arrays
                if (is_array($value) && !empty($value)) {
                    $totalScore += $weight;
                }
            }
        }

        return $totalScore;
    }
}
