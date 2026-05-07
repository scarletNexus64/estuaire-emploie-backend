<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserStoragePack;
use App\Models\StorageFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StorageFileController extends Controller
{
    /**
     * Lister tous les fichiers de l'utilisateur avec recherche et filtres
     *
     * Query params:
     * - folder_id: ID du dossier parent (null = racine)
     * - search: recherche par nom de fichier
     * - type: filtre par type (image, video, audio, document, etc.)
     * - sort_by: tri (name, size, created_at) - défaut: created_at
     * - sort_order: ordre (asc, desc) - défaut: desc
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $folderId = $request->get('folder_id', null);
        $hasSearch = $request->has('search') && $request->search;
        $hasTypeFilter = $request->has('type') && $request->type;

        // Si recherche ou filtre actif, chercher dans TOUS les dossiers
        // Sinon, chercher seulement dans le dossier courant
        $searchGlobally = $hasSearch || $hasTypeFilter;

        $query = StorageFile::where('user_id', $user->id);

        if (!$searchGlobally) {
            // Navigation normale : afficher seulement les fichiers du dossier courant
            $query->where('parent_folder_id', $folderId);
        }

        // Recherche par nom de fichier (global si recherche active)
        if ($hasSearch) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('original_name', 'LIKE', "%{$search}%")
                  ->orWhere('name', 'LIKE', "%{$search}%");
            });
        }

        // Filtre par type de fichier
        if ($hasTypeFilter) {
            // En recherche globale, on ne garde que les fichiers du type demandé (pas les dossiers)
            $query->where('file_type', $request->type);
        }

        // Tri
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        // Validation du tri
        $allowedSortFields = ['name', 'original_name', 'size', 'created_at'];
        if (!in_array($sortBy, $allowedSortFields)) {
            $sortBy = 'created_at';
        }

        if (!in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = 'desc';
        }

        $query->orderBy($sortBy, $sortOrder);

        $files = $query->get()->map(function ($file) use ($user, $searchGlobally) {
            $data = [
                'id' => $file->id,
                'name' => $file->name,
                'original_name' => $file->original_name,
                'file_type' => $file->file_type,
                'mime_type' => $file->mime_type,
                'size' => $file->size,
                'formatted_size' => $file->formatted_size,
                'path' => $file->path,
                'url' => $file->is_folder ? null : $file->url,
                'is_folder' => $file->is_folder,
                'parent_folder_id' => $file->parent_folder_id,
                'created_at' => $file->created_at->toISOString(),
                'updated_at' => $file->updated_at ? $file->updated_at->toISOString() : null,
            ];

            // Si recherche globale, ajouter le breadcrumb (chemin)
            if ($searchGlobally) {
                $data['breadcrumb'] = $this->buildBreadcrumb($file, $user->id);
            }

            return $data;
        });

        return response()->json([
            'success' => true,
            'data' => $files,
            'filters' => [
                'search' => $request->get('search'),
                'type' => $request->get('type'),
                'sort_by' => $sortBy,
                'sort_order' => $sortOrder,
            ],
            'is_global_search' => $searchGlobally,
        ]);
    }

    /**
     * Construit le breadcrumb (chemin) pour un fichier
     */
    private function buildBreadcrumb($file, $userId)
    {
        $breadcrumb = [];
        $currentId = $file->parent_folder_id;

        // Remonter l'arborescence jusqu'à la racine
        while ($currentId !== null) {
            $parent = StorageFile::where('id', $currentId)
                ->where('user_id', $userId)
                ->where('is_folder', true)
                ->first();

            if (!$parent) {
                break;
            }

            // Ajouter au début du breadcrumb
            array_unshift($breadcrumb, [
                'id' => $parent->id,
                'name' => $parent->name,
            ]);

            $currentId = $parent->parent_folder_id;
        }

        // Ajouter "Racine" au début si le fichier a un parent
        if (count($breadcrumb) > 0 || $file->parent_folder_id !== null) {
            array_unshift($breadcrumb, [
                'id' => null,
                'name' => 'Mon Drive',
            ]);
        }

        return $breadcrumb;
    }

    /**
     * Créer un dossier
     */
    public function createFolder(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_folder_id' => 'nullable|exists:storage_files,id',
        ]);

        $user = $request->user();
        $name = $request->input('name');
        $parentFolderId = $request->input('parent_folder_id', null);

        // Vérifier que le parent est bien un dossier si spécifié
        if ($parentFolderId) {
            $parentFolder = StorageFile::where('id', $parentFolderId)
                ->where('user_id', $user->id)
                ->where('is_folder', true)
                ->first();

            if (!$parentFolder) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dossier parent invalide',
                ], 400);
            }
        }

        // Vérifier qu'un dossier avec le même nom n'existe pas déjà
        $existingFolder = StorageFile::where('user_id', $user->id)
            ->where('parent_folder_id', $parentFolderId)
            ->where('name', $name)
            ->where('is_folder', true)
            ->first();

        if ($existingFolder) {
            return response()->json([
                'success' => false,
                'message' => 'Un dossier avec ce nom existe déjà',
            ], 400);
        }

        // Créer le dossier
        $folder = StorageFile::create([
            'user_id' => $user->id,
            'name' => $name,
            'original_name' => $name,
            'file_type' => 'folder',
            'is_folder' => true,
            'parent_folder_id' => $parentFolderId,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Dossier créé avec succès',
            'data' => $this->formatItemData($folder),
        ]);
    }

    /**
     * Upload un fichier
     */
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:102400', // Max 100 Mo
            'parent_folder_id' => 'nullable|exists:storage_files,id',
        ]);

        $user = $request->user();
        $parentFolderId = $request->input('parent_folder_id', null);

        // Vérifier que le parent est bien un dossier si spécifié
        if ($parentFolderId) {
            $parentFolder = StorageFile::where('id', $parentFolderId)
                ->where('user_id', $user->id)
                ->where('is_folder', true)
                ->first();

            if (!$parentFolder) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dossier parent invalide',
                ], 400);
            }
        }

        // Vérifier si l'utilisateur a un pack actif
        $activePacks = UserStoragePack::where('user_id', $user->id)
            ->where('is_active', true)
            ->get();

        if ($activePacks->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Vous n\'avez pas de pack de stockage actif',
            ], 400);
        }

        // Calculer l'espace total disponible
        $totalAvailable = $activePacks->sum('storage_mb') - $activePacks->sum('storage_used_mb');
        
        $file = $request->file('file');
        $fileSizeMb = ceil($file->getSize() / (1024 * 1024));

        if ($fileSizeMb > $totalAvailable) {
            return response()->json([
                'success' => false,
                'message' => "Espace insuffisant. Fichier: {$fileSizeMb} Mo, Disponible: {$totalAvailable} Mo",
            ], 400);
        }

        // Stocker le fichier
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $fileName = Str::uuid() . '.' . $extension;
        
        // Dossier de l'utilisateur
        $userFolder = 'user_storage/' . Str::slug($user->name) . '_' . $user->id;
        $filePath = $file->storeAs($userFolder, $fileName, 'public');

        // Créer l'enregistrement
        $storageFile = StorageFile::create([
            'user_id' => $user->id,
            'name' => $fileName,
            'original_name' => $originalName,
            'file_type' => $this->getFileType($extension),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'path' => $filePath,
            'is_folder' => false,
            'parent_folder_id' => $parentFolderId,
        ]);

        // Mettre à jour l'espace utilisé dans les packs (du plus ancien au plus récent)
        $remainingSizeMb = $fileSizeMb;
        foreach ($activePacks as $pack) {
            if ($remainingSizeMb <= 0) break;
            
            $packAvailable = $pack->storage_mb - $pack->storage_used_mb;
            $toUse = min($remainingSizeMb, $packAvailable);
            
            $pack->storage_used_mb += $toUse;
            $pack->save();
            
            $remainingSizeMb -= $toUse;
        }

        return response()->json([
            'success' => true,
            'message' => 'Fichier uploadé avec succès',
            'data' => [
                'id' => $storageFile->id,
                'name' => $storageFile->name,
                'original_name' => $storageFile->original_name,
                'file_type' => $storageFile->file_type,
                'mime_type' => $storageFile->mime_type,
                'size' => $storageFile->size,
                'formatted_size' => $storageFile->formatted_size,
                'url' => $storageFile->url,
                'created_at' => $storageFile->created_at->toISOString(),
            ],
        ]);
    }

    /**
     * Télécharger un fichier
     */
    public function download($id)
    {
        $file = StorageFile::findOrFail($id);

        if ($file->user_id !== auth()->id()) {
            abort(403, 'Accès non autorisé');
        }

        return Storage::disk('public')->download($file->path, $file->original_name);
    }

    /**
     * Supprimer un fichier ou un dossier
     */
    public function destroy($id)
    {
        $user = auth()->user();
        $item = StorageFile::findOrFail($id);

        if ($item->user_id !== $user->id) {
            abort(403, 'Accès non autorisé');
        }

        $totalSizeFreed = 0;

        if ($item->is_folder) {
            // Supprimer un dossier et tout son contenu récursivement
            $totalSizeFreed = $this->deleteFolderRecursive($item);
        } else {
            // Supprimer un fichier
            if ($item->path && Storage::disk('public')->exists($item->path)) {
                Storage::disk('public')->delete($item->path);
            }

            $totalSizeFreed = $item->size ?? 0;
            $item->delete();
        }

        // Libérer l'espace dans les packs (du plus récent au plus ancien)
        if ($totalSizeFreed > 0) {
            $fileSizeMb = ceil($totalSizeFreed / (1024 * 1024));

            $activePacks = UserStoragePack::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();

            $remainingSizeMb = $fileSizeMb;
            foreach ($activePacks as $pack) {
                if ($remainingSizeMb <= 0) break;

                $toFree = min($remainingSizeMb, $pack->storage_used_mb);
                $pack->storage_used_mb -= $toFree;
                $pack->save();

                $remainingSizeMb -= $toFree;
            }
        }

        return response()->json([
            'success' => true,
            'message' => $item->is_folder ? 'Dossier supprimé' : 'Fichier supprimé',
        ]);
    }

    /**
     * Supprimer un dossier et tout son contenu récursivement
     */
    private function deleteFolderRecursive($folder)
    {
        $totalSize = 0;

        // Récupérer tous les enfants (fichiers et sous-dossiers)
        $children = StorageFile::where('parent_folder_id', $folder->id)->get();

        foreach ($children as $child) {
            if ($child->is_folder) {
                // Récursion pour les sous-dossiers
                $totalSize += $this->deleteFolderRecursive($child);
            } else {
                // Supprimer le fichier physique
                if ($child->path && Storage::disk('public')->exists($child->path)) {
                    Storage::disk('public')->delete($child->path);
                }

                $totalSize += $child->size ?? 0;
                $child->delete();
            }
        }

        // Supprimer le dossier lui-même
        $folder->delete();

        return $totalSize;
    }

    /**
     * Supprimer plusieurs fichiers en une seule fois
     */
    public function batchDelete(Request $request)
    {
        $request->validate([
            'file_ids' => 'required|array',
            'file_ids.*' => 'integer|exists:storage_files,id',
        ]);

        $user = auth()->user();
        $fileIds = $request->input('file_ids', []);

        // Récupérer les fichiers appartenant à l'utilisateur
        $files = StorageFile::whereIn('id', $fileIds)
            ->where('user_id', $user->id)
            ->get();

        if ($files->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Aucun fichier trouvé ou accès non autorisé',
            ], 404);
        }

        $deletedCount = 0;
        $totalSizeFreedMb = 0;

        foreach ($files as $file) {
            // Supprimer le fichier physique
            if (Storage::disk('public')->exists($file->path)) {
                Storage::disk('public')->delete($file->path);
            }

            $fileSizeMb = ceil($file->size / (1024 * 1024));
            $totalSizeFreedMb += $fileSizeMb;

            $file->delete();
            $deletedCount++;
        }

        // Libérer l'espace dans les packs (du plus récent au plus ancien)
        if ($totalSizeFreedMb > 0) {
            $activePacks = UserStoragePack::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();

            $remainingSizeMb = $totalSizeFreedMb;
            foreach ($activePacks as $pack) {
                if ($remainingSizeMb <= 0) break;

                $toFree = min($remainingSizeMb, $pack->storage_used_mb);
                $pack->storage_used_mb -= $toFree;
                $pack->save();

                $remainingSizeMb -= $toFree;
            }
        }

        return response()->json([
            'success' => true,
            'message' => "{$deletedCount} fichier(s) supprimé(s) avec succès",
            'deleted_count' => $deletedCount,
            'space_freed_mb' => $totalSizeFreedMb,
        ]);
    }

    /**
     * Renommer un fichier ou dossier
     */
    public function rename(Request $request, $id)
    {
        $request->validate([
            'new_name' => 'required|string|max:255',
        ]);

        $user = auth()->user();
        $item = StorageFile::findOrFail($id);

        // Vérifier que l'item appartient à l'utilisateur
        if ($item->user_id !== $user->id) {
            abort(403, 'Accès non autorisé');
        }

        $newName = $request->input('new_name');

        // Vérifier qu'un item avec le même nom n'existe pas déjà dans le même dossier
        $existingItem = StorageFile::where('user_id', $user->id)
            ->where('parent_folder_id', $item->parent_folder_id)
            ->where('name', $newName)
            ->where('id', '!=', $id)
            ->first();

        if ($existingItem) {
            return response()->json([
                'success' => false,
                'message' => 'Un élément avec ce nom existe déjà dans ce dossier',
            ], 400);
        }

        // Renommer
        $item->name = $newName;
        $item->original_name = $newName;
        $item->save();

        return response()->json([
            'success' => true,
            'message' => $item->is_folder ? 'Dossier renommé' : 'Fichier renommé',
            'data' => $this->formatItemData($item),
        ]);
    }

    /**
     * Déplacer un fichier ou dossier
     */
    public function move(Request $request, $id)
    {
        $request->validate([
            'destination_folder_id' => 'nullable|exists:storage_files,id',
        ]);

        $user = auth()->user();
        $item = StorageFile::findOrFail($id);

        // Vérifier que l'item appartient à l'utilisateur
        if ($item->user_id !== $user->id) {
            abort(403, 'Accès non autorisé');
        }

        $destinationFolderId = $request->input('destination_folder_id', null);

        // Vérifier que la destination est bien un dossier si spécifiée
        if ($destinationFolderId) {
            $destinationFolder = StorageFile::where('id', $destinationFolderId)
                ->where('user_id', $user->id)
                ->where('is_folder', true)
                ->first();

            if (!$destinationFolder) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dossier de destination invalide',
                ], 400);
            }

            // Empêcher de déplacer un dossier dans lui-même ou dans un de ses enfants
            if ($item->is_folder && $this->isDescendant($destinationFolderId, $id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Impossible de déplacer un dossier dans un de ses sous-dossiers',
                ], 400);
            }
        }

        // Vérifier qu'un item avec le même nom n'existe pas déjà dans la destination
        $existingItem = StorageFile::where('user_id', $user->id)
            ->where('parent_folder_id', $destinationFolderId)
            ->where('name', $item->name)
            ->where('id', '!=', $id)
            ->first();

        if ($existingItem) {
            return response()->json([
                'success' => false,
                'message' => 'Un élément avec ce nom existe déjà dans le dossier de destination',
            ], 400);
        }

        // Déplacer l'item
        $item->parent_folder_id = $destinationFolderId;
        $item->save();

        return response()->json([
            'success' => true,
            'message' => $item->is_folder ? 'Dossier déplacé' : 'Fichier déplacé',
            'data' => $this->formatItemData($item),
        ]);
    }

    /**
     * Copier un fichier ou dossier
     */
    public function copy(Request $request, $id)
    {
        $request->validate([
            'destination_folder_id' => 'nullable|exists:storage_files,id',
            'new_name' => 'nullable|string|max:255',
        ]);

        $user = auth()->user();
        $item = StorageFile::findOrFail($id);

        // Vérifier que l'item appartient à l'utilisateur
        if ($item->user_id !== $user->id) {
            abort(403, 'Accès non autorisé');
        }

        $destinationFolderId = $request->input('destination_folder_id', $item->parent_folder_id);
        $newName = $request->input('new_name', $item->name);

        // Vérifier que la destination est bien un dossier si spécifiée
        if ($destinationFolderId) {
            $destinationFolder = StorageFile::where('id', $destinationFolderId)
                ->where('user_id', $user->id)
                ->where('is_folder', true)
                ->first();

            if (!$destinationFolder) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dossier de destination invalide',
                ], 400);
            }
        }

        if ($item->is_folder) {
            // Copier un dossier (récursif)
            $copiedFolder = $this->copyFolderRecursive($item, $destinationFolderId, $newName);

            return response()->json([
                'success' => true,
                'message' => 'Dossier copié',
                'data' => $this->formatItemData($copiedFolder),
            ]);
        } else {
            // Copier un fichier
            $fileSizeMb = ceil($item->size / (1024 * 1024));

            // Vérifier l'espace disponible
            $activePacks = UserStoragePack::where('user_id', $user->id)
                ->where('is_active', true)
                ->get();

            $totalAvailable = $activePacks->sum('storage_mb') - $activePacks->sum('storage_used_mb');

            if ($fileSizeMb > $totalAvailable) {
                return response()->json([
                    'success' => false,
                    'message' => "Espace insuffisant pour copier ce fichier",
                ], 400);
            }

            // Copier le fichier physique
            $extension = pathinfo($item->path, PATHINFO_EXTENSION);
            $newFileName = Str::uuid() . '.' . $extension;
            $userFolder = 'user_storage/' . Str::slug($user->name) . '_' . $user->id;
            $newPath = $userFolder . '/' . $newFileName;

            Storage::disk('public')->copy($item->path, $newPath);

            // Créer l'enregistrement
            $copiedFile = StorageFile::create([
                'user_id' => $user->id,
                'name' => $newFileName,
                'original_name' => $newName,
                'file_type' => $item->file_type,
                'mime_type' => $item->mime_type,
                'size' => $item->size,
                'path' => $newPath,
                'is_folder' => false,
                'parent_folder_id' => $destinationFolderId,
            ]);

            // Mettre à jour l'espace utilisé
            $remainingSizeMb = $fileSizeMb;
            foreach ($activePacks as $pack) {
                if ($remainingSizeMb <= 0) break;

                $packAvailable = $pack->storage_mb - $pack->storage_used_mb;
                $toUse = min($remainingSizeMb, $packAvailable);

                $pack->storage_used_mb += $toUse;
                $pack->save();

                $remainingSizeMb -= $toUse;
            }

            return response()->json([
                'success' => true,
                'message' => 'Fichier copié',
                'data' => $this->formatItemData($copiedFile),
            ]);
        }
    }

    /**
     * Vérifier si un dossier est un descendant d'un autre
     */
    private function isDescendant($possibleDescendantId, $ancestorId)
    {
        $current = StorageFile::find($possibleDescendantId);

        while ($current && $current->parent_folder_id) {
            if ($current->parent_folder_id == $ancestorId) {
                return true;
            }
            $current = StorageFile::find($current->parent_folder_id);
        }

        return false;
    }

    /**
     * Copier un dossier de manière récursive
     */
    private function copyFolderRecursive($folder, $destinationFolderId, $newName)
    {
        // Créer le nouveau dossier
        $copiedFolder = StorageFile::create([
            'user_id' => $folder->user_id,
            'name' => $newName,
            'original_name' => $newName,
            'file_type' => 'folder',
            'is_folder' => true,
            'parent_folder_id' => $destinationFolderId,
        ]);

        // Copier tous les enfants
        $children = StorageFile::where('parent_folder_id', $folder->id)->get();

        foreach ($children as $child) {
            if ($child->is_folder) {
                // Copier récursivement les sous-dossiers
                $this->copyFolderRecursive($child, $copiedFolder->id, $child->name);
            } else {
                // Copier les fichiers
                $extension = pathinfo($child->path, PATHINFO_EXTENSION);
                $newFileName = Str::uuid() . '.' . $extension;
                $userFolder = 'user_storage/' . Str::slug($folder->user->name) . '_' . $folder->user_id;
                $newPath = $userFolder . '/' . $newFileName;

                Storage::disk('public')->copy($child->path, $newPath);

                StorageFile::create([
                    'user_id' => $child->user_id,
                    'name' => $newFileName,
                    'original_name' => $child->original_name,
                    'file_type' => $child->file_type,
                    'mime_type' => $child->mime_type,
                    'size' => $child->size,
                    'path' => $newPath,
                    'is_folder' => false,
                    'parent_folder_id' => $copiedFolder->id,
                ]);
            }
        }

        return $copiedFolder;
    }

    /**
     * Déterminer le type de fichier
     */
    private function getFileType($extension)
    {
        $extension = strtolower($extension);
        
        $types = [
            'image' => ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp'],
            'video' => ['mp4', 'avi', 'mov', 'wmv', 'flv', 'mkv'],
            'audio' => ['mp3', 'wav', 'ogg', 'flac', 'm4a'],
            'document' => ['pdf', 'doc', 'docx', 'txt', 'rtf', 'odt'],
            'spreadsheet' => ['xls', 'xlsx', 'csv', 'ods'],
            'presentation' => ['ppt', 'pptx', 'odp'],
            'archive' => ['zip', 'rar', '7z', 'tar', 'gz'],
        ];

        foreach ($types as $type => $extensions) {
            if (in_array($extension, $extensions)) {
                return $type;
            }
        }

        return 'other';
    }

    /**
     * Formatte les données d'un item pour la réponse JSON
     */
    private function formatItemData($item)
    {
        return [
            'id' => $item->id,
            'name' => $item->name,
            'original_name' => $item->original_name,
            'file_type' => $item->file_type,
            'mime_type' => $item->mime_type,
            'size' => $item->size,
            'path' => $item->path,
            'is_folder' => $item->is_folder,
            'parent_folder_id' => $item->parent_folder_id,
            'created_at' => $item->created_at ? $item->created_at->toISOString() : null,
            'updated_at' => $item->updated_at ? $item->updated_at->toISOString() : null,
        ];
    }
}
