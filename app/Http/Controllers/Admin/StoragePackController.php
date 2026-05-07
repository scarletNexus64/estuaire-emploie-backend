<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StoragePack;
use App\Models\UserStoragePack;
use Illuminate\Http\Request;

class StoragePackController extends Controller
{
    /**
     * Liste des packs de stockage
     */
    public function index(Request $request)
    {
        $query = StoragePack::query();

        // Filtre de recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filtre par statut
        if ($request->filled('status')) {
            $isActive = $request->status === 'active';
            $query->where('is_active', $isActive);
        }

        $storagePacks = $query->ordered()->paginate(20);

        // Stats globales
        $totalActive = StoragePack::where('is_active', true)->count();
        $totalInactive = StoragePack::where('is_active', false)->count();

        return view('admin.storage-packs.index', compact(
            'storagePacks',
            'totalActive',
            'totalInactive'
        ));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        return view('admin.storage-packs.form');
    }

    /**
     * Enregistrer un nouveau pack
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:storage_packs,slug',
            'storage_mb' => 'required|integer|min:1',
            'duration_days' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'display_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['display_order'] = $validated['display_order'] ?? 0;

        StoragePack::create($validated);

        return redirect()->route('admin.storage-packs.index')
                        ->with('success', 'Pack de stockage créé avec succès');
    }

    /**
     * Afficher les détails d'un pack
     */
    public function show(StoragePack $storagePack)
    {
        return view('admin.storage-packs.show', compact('storagePack'));
    }

    /**
     * Formulaire d'édition
     */
    public function edit(StoragePack $storagePack)
    {
        return view('admin.storage-packs.form', compact('storagePack'));
    }

    /**
     * Mettre à jour un pack
     */
    public function update(Request $request, StoragePack $storagePack)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:storage_packs,slug,' . $storagePack->id,
            'storage_mb' => 'required|integer|min:1',
            'duration_days' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'display_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $storagePack->update($validated);

        return redirect()->route('admin.storage-packs.index')
                        ->with('success', 'Pack de stockage mis à jour avec succès');
    }

    /**
     * Supprimer un pack
     */
    public function destroy(StoragePack $storagePack)
    {
        $storagePack->delete();

        return redirect()->route('admin.storage-packs.index')
                        ->with('success', 'Pack de stockage supprimé avec succès');
    }

    /**
     * Activer/Désactiver un pack
     */
    public function toggle(StoragePack $storagePack)
    {
        $storagePack->update(['is_active' => !$storagePack->is_active]);

        $status = $storagePack->is_active ? 'activé' : 'désactivé';

        return redirect()->back()
                        ->with('success', "Pack {$status} avec succès");
    }

    /**
     * Liste des souscripteurs (utilisateurs qui ont acheté des packs)
     */
    public function subscribers(Request $request)
    {
        $query = UserStoragePack::with(['user', 'storagePack']);

        // Filtre par statut
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'expired') {
                $query->where('is_active', false);
            }
        }

        // Filtre par pack
        if ($request->filled('pack_id')) {
            $query->where('storage_pack_id', $request->pack_id);
        }

        // Recherche par nom d'utilisateur
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $userPacks = $query->orderBy('created_at', 'desc')->paginate(20);

        // Stats
        $totalSubscriptions = UserStoragePack::count();
        $activeSubscriptions = UserStoragePack::where('is_active', true)->count();
        $expiredSubscriptions = UserStoragePack::where('is_active', false)->count();
        $totalStorageAllocated = UserStoragePack::where('is_active', true)->sum('storage_mb');
        $totalStorageUsed = UserStoragePack::where('is_active', true)->sum('storage_used_mb');
        $totalRevenue = UserStoragePack::sum('purchase_price');

        // Liste des packs pour le filtre
        $storagePacks = StoragePack::orderBy('display_order')->get();

        return view('admin.storage-packs.subscribers', compact(
            'userPacks',
            'totalSubscriptions',
            'activeSubscriptions',
            'expiredSubscriptions',
            'totalStorageAllocated',
            'totalStorageUsed',
            'totalRevenue',
            'storagePacks'
        ));
    }

    /**
     * Supprimer une souscription d'un pack de stockage
     */
    public function destroySubscription(UserStoragePack $userStoragePack)
    {
        try {
            $userName = $userStoragePack->user->name;
            $packName = $userStoragePack->storagePack->name;
            $storageUsed = $userStoragePack->storage_used_mb;

            // Supprimer la souscription (soft delete)
            $userStoragePack->delete();

            // Message selon si des fichiers étaient stockés ou non
            $message = "Souscription de {$userName} au pack {$packName} supprimée avec succès";
            if ($storageUsed > 0) {
                $storageFormatted = $storageUsed < 1024
                    ? $storageUsed . ' Mo'
                    : round($storageUsed / 1024, 2) . ' Go';
                $message .= " ({$storageFormatted} de fichiers étaient stockés)";
            }

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            \Log::error('Error deleting user storage pack subscription: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la suppression de la souscription');
        }
    }
}
