<?php

namespace App\Services;

use App\Models\StoragePack;
use App\Models\User;
use App\Models\UserStoragePack;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StoragePackService
{
    protected WalletService $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    /**
     * Achète un pack de stockage via le wallet
     *
     * @param User $user
     * @param StoragePack $storagePack
     * @param string $provider Provider du wallet (freemopay ou paypal)
     * @return UserStoragePack
     * @throws \Exception
     */
    public function purchaseStoragePack(User $user, StoragePack $storagePack, string $provider = 'freemopay'): UserStoragePack
    {
        return DB::transaction(function () use ($user, $storagePack, $provider) {
            // Vérifier que le pack est actif
            if (!$storagePack->is_active) {
                throw new \Exception("Ce pack de stockage n'est plus disponible");
            }

            // Si c'est le pack gratuit, vérifier que l'utilisateur ne l'a pas déjà
            if ($storagePack->slug === 'pack-gratuit') {
                $existingFreePack = UserStoragePack::where('user_id', $user->id)
                    ->whereHas('storagePack', function ($query) {
                        $query->where('slug', 'pack-gratuit');
                    })
                    ->exists();

                if ($existingFreePack) {
                    throw new \Exception("Vous avez déjà le pack gratuit");
                }
            }

            $price = $storagePack->price;

            // Débiter le wallet UNIQUEMENT si le prix est supérieur à 0
            if ($price > 0) {
                $this->walletService->debit(
                    $user,
                    $price,
                    "Achat pack de stockage: {$storagePack->name}",
                    'storage_pack',
                    $storagePack->id,
                    [
                        'storage_pack_id' => $storagePack->id,
                        'storage_pack_name' => $storagePack->name,
                        'storage_mb' => $storagePack->storage_mb,
                        'duration_days' => $storagePack->duration_days,
                    ],
                    $provider
                );
            } else {
                Log::info("[StoragePackService] Free pack - No wallet debit needed", [
                    'user_id' => $user->id,
                    'storage_pack_id' => $storagePack->id,
                ]);
            }

            // Créer le dossier de stockage pour l'utilisateur
            $folderName = $this->createUserStorageFolder($user);

            // Calculer la date d'expiration
            $expiresAt = now()->addDays($storagePack->duration_days);

            // Créer l'enregistrement du pack utilisateur
            $userStoragePack = UserStoragePack::create([
                'user_id' => $user->id,
                'storage_pack_id' => $storagePack->id,
                'storage_mb' => $storagePack->storage_mb,
                'storage_used_mb' => 0,
                'storage_folder_path' => $folderName,
                'purchased_at' => now(),
                'expires_at' => $expiresAt,
                'is_active' => true,
                'purchase_price' => $price,
            ]);

            Log::info("[StoragePackService] Storage pack purchased", [
                'user_id' => $user->id,
                'storage_pack_id' => $storagePack->id,
                'storage_pack_name' => $storagePack->name,
                'price' => $price,
                'provider' => $provider,
                'expires_at' => $expiresAt,
                'folder_path' => $folderName,
            ]);

            return $userStoragePack;
        });
    }

    /**
     * Crée un dossier de stockage pour l'utilisateur
     *
     * @param User $user
     * @return string Chemin du dossier
     */
    protected function createUserStorageFolder(User $user): string
    {
        // Créer un nom de dossier basé sur le nom complet de l'utilisateur
        $folderName = 'user_storage/' . Str::slug($user->name) . '_' . $user->id;

        // Créer le dossier s'il n'existe pas
        if (!Storage::disk('public')->exists($folderName)) {
            Storage::disk('public')->makeDirectory($folderName);

            // Créer un fichier .gitkeep pour conserver le dossier
            Storage::disk('public')->put($folderName . '/.gitkeep', '');

            Log::info("[StoragePackService] Storage folder created", [
                'user_id' => $user->id,
                'folder_path' => $folderName,
            ]);
        }

        return $folderName;
    }

    /**
     * Récupère les packs de stockage actifs d'un utilisateur
     *
     * @param User $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserActivePacks(User $user)
    {
        return UserStoragePack::where('user_id', $user->id)
            ->active()
            ->with('storagePack')
            ->orderBy('expires_at', 'desc')
            ->get();
    }

    /**
     * Récupère tous les packs de stockage d'un utilisateur (actifs et expirés)
     *
     * @param User $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserAllPacks(User $user)
    {
        return UserStoragePack::where('user_id', $user->id)
            ->with('storagePack')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Calcule l'espace total disponible pour un utilisateur
     *
     * @param User $user
     * @return array
     */
    public function getUserStorageStats(User $user): array
    {
        $activePacks = $this->getUserActivePacks($user);

        $totalStorageMb = $activePacks->sum('storage_mb');
        $totalUsedMb = $activePacks->sum('storage_used_mb');
        $totalRemainingMb = max(0, $totalStorageMb - $totalUsedMb);

        // Statistiques par type de fichier
        $filesByType = \DB::table('storage_files')
            ->where('user_id', $user->id)
            ->select('file_type', \DB::raw('SUM(size) as total_size'), \DB::raw('COUNT(*) as count'))
            ->groupBy('file_type')
            ->get()
            ->map(function ($item) {
                $sizeMb = ceil($item->total_size / (1024 * 1024));
                return [
                    'type' => $item->file_type,
                    'size_mb' => $sizeMb,
                    'formatted_size' => $this->formatStorage($sizeMb),
                    'count' => $item->count,
                ];
            });

        return [
            'total_storage_mb' => $totalStorageMb,
            'total_used_mb' => $totalUsedMb,
            'total_remaining_mb' => $totalRemainingMb,
            'usage_percentage' => $totalStorageMb > 0 ? round(($totalUsedMb / $totalStorageMb) * 100, 2) : 0,
            'formatted_total_storage' => $this->formatStorage($totalStorageMb),
            'formatted_used_storage' => $this->formatStorage($totalUsedMb),
            'formatted_remaining_storage' => $this->formatStorage($totalRemainingMb),
            'active_packs_count' => $activePacks->count(),
            'files_by_type' => $filesByType,
        ];
    }

    /**
     * Formate l'espace de stockage en Mo/Go
     *
     * @param int $mb
     * @return string
     */
    protected function formatStorage(int $mb): string
    {
        if ($mb < 1024) {
            return $mb . ' Mo';
        }

        return round($mb / 1024, 2) . ' Go';
    }

    /**
     * Désactive les packs expirés
     *
     * @return int Nombre de packs désactivés
     */
    public function deactivateExpiredPacks(): int
    {
        $count = UserStoragePack::where('is_active', true)
            ->where('expires_at', '<=', now())
            ->update(['is_active' => false]);

        if ($count > 0) {
            Log::info("[StoragePackService] Expired packs deactivated", [
                'count' => $count,
            ]);
        }

        return $count;
    }

    /**
     * Attribue automatiquement le pack gratuit de 50Mo à un utilisateur
     *
     * @param User $user
     * @return UserStoragePack|null
     */
    public function assignFreePackToUser(User $user): ?UserStoragePack
    {
        try {
            // Vérifier si l'utilisateur a déjà un pack gratuit
            $existingFreePack = UserStoragePack::where('user_id', $user->id)
                ->whereHas('storagePack', function ($query) {
                    $query->where('slug', 'pack-gratuit');
                })
                ->first();

            if ($existingFreePack) {
                Log::info("[StoragePackService] User already has free pack", [
                    'user_id' => $user->id,
                    'pack_id' => $existingFreePack->id,
                ]);
                return $existingFreePack;
            }

            // Récupérer le pack gratuit
            $freePack = StoragePack::where('slug', 'pack-gratuit')
                ->where('is_active', true)
                ->first();

            if (!$freePack) {
                Log::error("[StoragePackService] Free pack not found or not active");
                return null;
            }

            // Créer le dossier de stockage pour l'utilisateur
            $folderName = $this->createUserStorageFolder($user);

            // Calculer la date d'expiration (100 ans = à vie)
            $expiresAt = now()->addDays($freePack->duration_days);

            // Créer l'enregistrement du pack utilisateur
            $userStoragePack = UserStoragePack::create([
                'user_id' => $user->id,
                'storage_pack_id' => $freePack->id,
                'storage_mb' => $freePack->storage_mb,
                'storage_used_mb' => 0,
                'storage_folder_path' => $folderName,
                'purchased_at' => now(),
                'expires_at' => $expiresAt,
                'is_active' => true,
                'purchase_price' => 0,
            ]);

            Log::info("[StoragePackService] Free pack assigned successfully", [
                'user_id' => $user->id,
                'pack_id' => $userStoragePack->id,
                'storage_mb' => $freePack->storage_mb,
                'folder_path' => $folderName,
            ]);

            return $userStoragePack;
        } catch (\Exception $e) {
            Log::error("[StoragePackService] Failed to assign free pack", [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Upgrade un pack de stockage vers un pack supérieur
     *
     * @param User $user
     * @param int $userPackId ID du pack utilisateur à upgrader
     * @param int $newPackId ID du nouveau pack
     * @param string $provider Provider du wallet (freemopay ou paypal)
     * @return array
     */
    public function upgradeStoragePack(User $user, int $userPackId, int $newPackId, string $provider = 'freemopay'): array
    {
        return DB::transaction(function () use ($user, $userPackId, $newPackId, $provider) {
            // Récupérer le pack utilisateur actuel
            $currentUserPack = UserStoragePack::where('id', $userPackId)
                ->where('user_id', $user->id)
                ->where('is_active', true)
                ->first();

            if (!$currentUserPack) {
                return [
                    'success' => false,
                    'message' => "Pack de stockage introuvable ou déjà expiré",
                ];
            }

            // Récupérer le nouveau pack
            $newPack = StoragePack::where('id', $newPackId)
                ->where('is_active', true)
                ->first();

            if (!$newPack) {
                return [
                    'success' => false,
                    'message' => "Le nouveau pack n'est plus disponible",
                ];
            }

            // Vérifier que le nouveau pack est supérieur
            if ($newPack->storage_mb <= $currentUserPack->storage_mb) {
                return [
                    'success' => false,
                    'message' => "Le nouveau pack doit offrir plus d'espace que votre pack actuel",
                ];
            }

            // Calculer le prix à payer (prix du nouveau pack)
            $priceToPayé = $newPack->price;

            // Débiter le wallet
            $this->walletService->debit(
                $user,
                $priceToPayé,
                "Upgrade pack de stockage: {$currentUserPack->storagePack->name} → {$newPack->name}",
                'storage_pack_upgrade',
                $newPack->id,
                [
                    'old_pack_id' => $currentUserPack->storage_pack_id,
                    'old_pack_name' => $currentUserPack->storagePack->name,
                    'new_pack_id' => $newPack->id,
                    'new_pack_name' => $newPack->name,
                    'storage_increase_mb' => $newPack->storage_mb - $currentUserPack->storage_mb,
                ],
                $provider
            );

            // Mettre à jour le pack utilisateur
            $currentUserPack->update([
                'storage_pack_id' => $newPack->id,
                'storage_mb' => $newPack->storage_mb,
                'expires_at' => now()->addDays($newPack->duration_days),
                'purchase_price' => $priceToPayé,
            ]);

            // Recharger la relation
            $currentUserPack->load('storagePack');

            Log::info("[StoragePackService] Storage pack upgraded", [
                'user_id' => $user->id,
                'user_pack_id' => $currentUserPack->id,
                'old_pack_name' => $currentUserPack->storagePack->name,
                'new_pack_name' => $newPack->name,
                'price_paid' => $priceToPayé,
                'provider' => $provider,
                'new_expires_at' => $currentUserPack->expires_at,
            ]);

            return [
                'success' => true,
                'message' => "Pack upgradé avec succès vers {$newPack->name}",
                'data' => [
                    'id' => $currentUserPack->id,
                    'storage_pack' => [
                        'id' => $currentUserPack->storagePack->id,
                        'name' => $currentUserPack->storagePack->name,
                    ],
                    'storage_mb' => $currentUserPack->storage_mb,
                    'formatted_total_storage' => $currentUserPack->formatted_total_storage,
                    'storage_used_mb' => $currentUserPack->storage_used_mb,
                    'formatted_used_storage' => $currentUserPack->formatted_used_storage,
                    'remaining_storage_mb' => $currentUserPack->remaining_storage_mb,
                    'formatted_remaining_storage' => $currentUserPack->formatted_remaining_storage,
                    'usage_percentage' => $currentUserPack->usage_percentage,
                    'storage_folder_path' => $currentUserPack->storage_folder_path,
                    'purchased_at' => $currentUserPack->purchased_at->toIso8601String(),
                    'expires_at' => $currentUserPack->expires_at->toIso8601String(),
                    'is_active' => $currentUserPack->is_active,
                    'is_expired' => $currentUserPack->is_expired,
                    'purchase_price' => $currentUserPack->purchase_price,
                ],
            ];
        });
    }
}
