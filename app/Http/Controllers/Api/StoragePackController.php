<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StoragePack;
use App\Services\StoragePackService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StoragePackController extends Controller
{
    protected StoragePackService $storagePackService;

    public function __construct(StoragePackService $storagePackService)
    {
        $this->storagePackService = $storagePackService;
    }

    /**
     * Liste tous les packs de stockage disponibles
     *
     * GET /api/storage-packs
     */
    public function index()
    {
        try {
            $packs = StoragePack::active()
                ->ordered()
                ->get()
                ->map(function ($pack) {
                    return [
                        'id' => $pack->id,
                        'name' => $pack->name,
                        'slug' => $pack->slug,
                        'storage_mb' => $pack->storage_mb,
                        'formatted_storage' => $pack->formatted_storage,
                        'duration_days' => $pack->duration_days,
                        'formatted_duration' => $pack->formatted_duration,
                        'price' => $pack->price,
                        'formatted_price' => $pack->formatted_price,
                        'description' => $pack->description,
                        'display_order' => $pack->display_order,
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $packs,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des packs de stockage',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Affiche les détails d'un pack de stockage
     *
     * GET /api/storage-packs/{id}
     */
    public function show($id)
    {
        try {
            $pack = StoragePack::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $pack->id,
                    'name' => $pack->name,
                    'slug' => $pack->slug,
                    'storage_mb' => $pack->storage_mb,
                    'formatted_storage' => $pack->formatted_storage,
                    'duration_days' => $pack->duration_days,
                    'formatted_duration' => $pack->formatted_duration,
                    'price' => $pack->price,
                    'formatted_price' => $pack->formatted_price,
                    'description' => $pack->description,
                    'is_active' => $pack->is_active,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Pack de stockage non trouvé',
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    /**
     * Achète un pack de stockage via le wallet
     *
     * POST /api/storage-packs/{id}/purchase
     */
    public function purchase(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'provider' => 'required|string|in:freemopay,paypal',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Données de validation invalides',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $user = $request->user();
            $storagePack = StoragePack::findOrFail($id);
            $provider = $request->input('provider', 'freemopay');

            // Acheter le pack
            $userStoragePack = $this->storagePackService->purchaseStoragePack($user, $storagePack, $provider);

            // Charger la relation
            $userStoragePack->load('storagePack');

            return response()->json([
                'success' => true,
                'message' => 'Pack de stockage acheté avec succès',
                'data' => [
                    'id' => $userStoragePack->id,
                    'storage_pack' => [
                        'id' => $userStoragePack->storagePack->id,
                        'name' => $userStoragePack->storagePack->name,
                    ],
                    'storage_mb' => $userStoragePack->storage_mb,
                    'formatted_total_storage' => $userStoragePack->formatted_total_storage,
                    'storage_used_mb' => $userStoragePack->storage_used_mb,
                    'formatted_used_storage' => $userStoragePack->formatted_used_storage,
                    'remaining_storage_mb' => $userStoragePack->remaining_storage_mb,
                    'formatted_remaining_storage' => $userStoragePack->formatted_remaining_storage,
                    'usage_percentage' => $userStoragePack->usage_percentage,
                    'storage_folder_path' => $userStoragePack->storage_folder_path,
                    'purchased_at' => $userStoragePack->purchased_at->toIso8601String(),
                    'expires_at' => $userStoragePack->expires_at->toIso8601String(),
                    'is_active' => $userStoragePack->is_active,
                    'purchase_price' => $userStoragePack->purchase_price,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Liste les packs de stockage de l'utilisateur connecté
     *
     * GET /api/my-storage-packs
     */
    public function myPacks(Request $request)
    {
        try {
            $user = $request->user();
            $includeExpired = $request->boolean('include_expired', false);

            $packs = $includeExpired
                ? $this->storagePackService->getUserAllPacks($user)
                : $this->storagePackService->getUserActivePacks($user);

            $packsData = $packs->map(function ($userPack) {
                return [
                    'id' => $userPack->id,
                    'storage_pack' => [
                        'id' => $userPack->storagePack->id,
                        'name' => $userPack->storagePack->name,
                    ],
                    'storage_mb' => $userPack->storage_mb,
                    'formatted_total_storage' => $userPack->formatted_total_storage,
                    'storage_used_mb' => $userPack->storage_used_mb,
                    'formatted_used_storage' => $userPack->formatted_used_storage,
                    'remaining_storage_mb' => $userPack->remaining_storage_mb,
                    'formatted_remaining_storage' => $userPack->formatted_remaining_storage,
                    'usage_percentage' => $userPack->usage_percentage,
                    'storage_folder_path' => $userPack->storage_folder_path,
                    'purchased_at' => $userPack->purchased_at->toIso8601String(),
                    'expires_at' => $userPack->expires_at->toIso8601String(),
                    'is_active' => $userPack->is_active,
                    'is_expired' => $userPack->isExpired(),
                    'purchase_price' => $userPack->purchase_price,
                ];
            });

            // Récupérer les statistiques de stockage
            $stats = $this->storagePackService->getUserStorageStats($user);

            return response()->json([
                'success' => true,
                'data' => [
                    'packs' => $packsData,
                    'stats' => $stats,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des packs',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Récupère les statistiques de stockage de l'utilisateur
     *
     * GET /api/my-storage-stats
     */
    public function myStats(Request $request)
    {
        try {
            $user = $request->user();
            $stats = $this->storagePackService->getUserStorageStats($user);

            return response()->json([
                'success' => true,
                'data' => $stats,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des statistiques',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Upgrade un pack existant vers un pack supérieur
     *
     * POST /api/storage-packs/{userPackId}/upgrade
     */
    public function upgradePack(Request $request, $userPackId)
    {
        try {
            $validator = Validator::make($request->all(), [
                'new_pack_id' => 'required|exists:storage_packs,id',
                'provider' => 'required|string|in:freemopay,paypal',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Données de validation invalides',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $user = $request->user();
            $newPackId = $request->input('new_pack_id');
            $provider = $request->input('provider', 'freemopay');

            $result = $this->storagePackService->upgradeStoragePack($user, $userPackId, $newPackId, $provider);

            if (!$result['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'],
                ], 400);
            }

            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'data' => $result['data'],
            ]);
        } catch (\Exception $e) {
            \Log::error('Error upgrading storage pack: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de l\'upgrade',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
