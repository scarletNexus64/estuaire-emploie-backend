<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class ExamPaper extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'specialty',
        'subject',
        'level',
        'year',
        'is_correction',
        'description',
        'file_path',
        'file_name',
        'file_size',
        'downloads_count',
        'views_count',
        'is_active',
        'display_order',
    ];

    protected function casts(): array
    {
        return [
            'level' => 'integer',
            'year' => 'integer',
            'is_correction' => 'boolean',
            'file_size' => 'integer',
            'downloads_count' => 'integer',
            'views_count' => 'integer',
            'is_active' => 'boolean',
            'display_order' => 'integer',
        ];
    }

    /**
     * Spécialités disponibles
     */
    public static function getSpecialties(): array
    {
        return [
            'Informatique' => 'Informatique',
            'Gestion' => 'Gestion',
            'Commerce' => 'Commerce',
            'Marketing' => 'Marketing',
            'Finance' => 'Finance',
            'Comptabilité' => 'Comptabilité',
            'Ressources Humaines' => 'Ressources Humaines',
            'Droit' => 'Droit',
            'Économie' => 'Économie',
            'Communication' => 'Communication',
            'Ingénierie' => 'Ingénierie',
            'Architecture' => 'Architecture',
            'Médecine' => 'Médecine',
            'Sciences' => 'Sciences',
            'Lettres' => 'Lettres',
            'Autre' => 'Autre',
        ];
    }

    /**
     * Matières par spécialité
     */
    public static function getSubjectsBySpecialty(string $specialty = null): array
    {
        $allSubjects = [
            'Informatique' => [
                'Programmation',
                'Base de données',
                'Réseaux',
                'Algorithmique',
                'Systèmes d\'exploitation',
                'Développement Web',
                'Développement Mobile',
                'Intelligence Artificielle',
                'Cybersécurité',
            ],
            'Gestion' => [
                'Gestion de projet',
                'Management',
                'Stratégie d\'entreprise',
                'Contrôle de gestion',
                'Audit',
            ],
            'Commerce' => [
                'Techniques de vente',
                'Négociation',
                'Commerce international',
                'Distribution',
            ],
            'Marketing' => [
                'Marketing digital',
                'Étude de marché',
                'Communication',
                'Brand management',
            ],
            'Finance' => [
                'Analyse financière',
                'Marchés financiers',
                'Gestion de portefeuille',
                'Finance d\'entreprise',
            ],
            'Comptabilité' => [
                'Comptabilité générale',
                'Comptabilité analytique',
                'Fiscalité',
                'Audit comptable',
            ],
            'Droit' => [
                'Droit civil',
                'Droit commercial',
                'Droit du travail',
                'Droit des affaires',
            ],
            'Mathématiques' => [
                'Algèbre',
                'Analyse',
                'Géométrie',
                'Probabilités',
                'Statistiques',
            ],
        ];

        if ($specialty && isset($allSubjects[$specialty])) {
            return $allSubjects[$specialty];
        }

        // Retourner toutes les matières
        $all = [];
        foreach ($allSubjects as $subjects) {
            $all = array_merge($all, $subjects);
        }
        return array_unique($all);
    }

    /**
     * Niveaux disponibles
     */
    public static function getLevels(): array
    {
        return [
            1 => 'Niveau 1 - BTS',
            2 => 'Niveau 2 - BTS',
            3 => 'Niveau 3 - Licence',
            4 => 'Niveau 4 - Master 1',
            5 => 'Niveau 5 - Master 2',
        ];
    }

    /**
     * Scope pour filtrer par spécialité
     */
    public function scopeSpecialty($query, string $specialty)
    {
        return $query->where('specialty', $specialty);
    }

    /**
     * Scope pour filtrer par matière
     */
    public function scopeSubject($query, string $subject)
    {
        return $query->where('subject', $subject);
    }

    /**
     * Scope pour filtrer par niveau
     */
    public function scopeLevel($query, int $level)
    {
        return $query->where('level', $level);
    }

    /**
     * Scope pour les épreuves actives
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope pour les corrigés
     */
    public function scopeCorrections($query)
    {
        return $query->where('is_correction', true);
    }

    /**
     * Scope pour les sujets (non corrigés)
     */
    public function scopeSubjects($query)
    {
        return $query->where('is_correction', false);
    }

    /**
     * Incrémenter le compteur de vues
     */
    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    /**
     * Incrémenter le compteur de téléchargements
     */
    public function incrementDownloads(): void
    {
        $this->increment('downloads_count');
    }

    /**
     * Obtenir l'URL du fichier
     */
    public function getFileUrlAttribute(): string
    {
        return Storage::url($this->file_path);
    }

    /**
     * Obtenir la taille du fichier formatée
     */
    public function getFormattedFileSizeAttribute(): string
    {
        if (!$this->file_size) {
            return 'N/A';
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $this->file_size;
        $unit = 0;

        while ($size >= 1024 && $unit < count($units) - 1) {
            $size /= 1024;
            $unit++;
        }

        return round($size, 2) . ' ' . $units[$unit];
    }

    /**
     * Obtenir le nom du niveau
     */
    public function getLevelNameAttribute(): string
    {
        $levels = self::getLevels();
        return $levels[$this->level] ?? "Niveau {$this->level}";
    }

    /**
     * Vérifier si le fichier existe
     */
    public function fileExists(): bool
    {
        return Storage::disk('public')->exists($this->file_path);
    }

    /**
     * Supprimer le fichier du storage
     */
    public function deleteFile(): bool
    {
        if ($this->fileExists()) {
            return Storage::disk('public')->delete($this->file_path);
        }
        return false;
    }

    /**
     * Event lors de la suppression du model
     */
    protected static function booted()
    {
        static::deleting(function (ExamPaper $examPaper) {
            // Supprimer le fichier PDF lors de la suppression du model
            $examPaper->deleteFile();
        });
    }
}
