<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class StorageFile extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'original_name',
        'file_type',
        'mime_type',
        'size',
        'path',
        'is_folder',
        'parent_folder_id',
    ];

    protected $casts = [
        'size' => 'integer',
        'is_folder' => 'boolean',
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation parent (dossier parent)
     */
    public function parentFolder()
    {
        return $this->belongsTo(StorageFile::class, 'parent_folder_id');
    }

    /**
     * Relation children (sous-dossiers et fichiers)
     */
    public function children()
    {
        return $this->hasMany(StorageFile::class, 'parent_folder_id');
    }

    /**
     * Scope pour récupérer uniquement les dossiers
     */
    public function scopeFolders($query)
    {
        return $query->where('is_folder', true);
    }

    /**
     * Scope pour récupérer uniquement les fichiers (non-dossiers)
     */
    public function scopeFiles($query)
    {
        return $query->where('is_folder', false);
    }

    /**
     * Obtenir la taille formatée
     */
    public function getFormattedSizeAttribute(): string
    {
        $size = $this->size;
        
        if ($size < 1024) {
            return $size . ' o';
        } elseif ($size < 1024 * 1024) {
            return round($size / 1024, 2) . ' Ko';
        } elseif ($size < 1024 * 1024 * 1024) {
            return round($size / (1024 * 1024), 2) . ' Mo';
        } else {
            return round($size / (1024 * 1024 * 1024), 2) . ' Go';
        }
    }

    /**
     * Obtenir l'URL du fichier
     */
    public function getUrlAttribute(): string
    {
        return Storage::disk('public')->url($this->path);
    }
}
