<?php

namespace App\Services\Social;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * État social du visiteur pour la requête courante (instance « scoped » :
 * réinitialisée à chaque requête HTTP / job de queue).
 *
 * Évite les N+1 lors de la sérialisation des offres, services et entreprises :
 * chaque modèle concerné s'enregistre ici à l'hydratation (événement
 * `retrieved`). Au premier accès à `is_liked` / `is_reposted` /
 * `is_following` / `active_jobs_count` (et, v2, `is_following` /
 * `follows_you` / `mutual_count` des utilisateurs, `is_liked` des
 * commentaires), l'état de TOUS les modèles en attente
 * est chargé en une seule requête (par type d'information), puis servi depuis
 * la mémoire. Une page de 20 offres = 1 requête likes + 1 reposts + 1 follows
 * + 1 offres actives par entreprise, quel que soit l'endpoint.
 *
 * Si les tables sociales n'existent pas encore (code déployé avant
 * `migrate`), les valeurs retombent à false/0 au lieu de casser l'API.
 */
class SocialContext
{
    private const CHUNK = 1000;

    private const MAX_PENDING = 2000;

    private bool $viewerResolved = false;

    private ?int $viewerId = null;

    /** @var array<string, array<int, true>> clé => ids en attente */
    private array $pending = [];

    /** @var array<string, array<int, mixed>> clé => [id => valeur] */
    private array $resolved = [];

    public function viewerId(): ?int
    {
        if (! $this->viewerResolved) {
            $this->viewerResolved = true;
            try {
                $user = auth('sanctum')->user();
                $this->viewerId = $user instanceof User ? (int) $user->id : null;
            } catch (\Throwable) {
                $this->viewerId = null;
            }
        }

        return $this->viewerId;
    }

    /** Force le visiteur (utile hors requête HTTP). */
    public function setViewer(?int $userId): void
    {
        $this->viewerResolved = true;
        $this->viewerId = $userId;
        $this->resolved = [];
    }

    /** Enregistre un modèle hydraté pour un chargement groupé ultérieur. */
    public function track(string $kind, $id): void
    {
        if ($id === null) {
            return;
        }
        $id = (int) $id;
        foreach ($this->keysFor($kind) as $key) {
            // Garde-fou mémoire pour les longues commandes qui hydratent des
            // milliers de modèles sans jamais les sérialiser : au-delà, le
            // lot repart de zéro (un modèle non suivi est chargé à la demande).
            if (count($this->pending[$key] ?? []) >= self::MAX_PENDING) {
                $this->pending[$key] = [];
            }
            if (! array_key_exists($id, $this->resolved[$key] ?? [])) {
                $this->pending[$key][$id] = true;
            }
        }
    }

    public function isLiked(string $type, $id): bool
    {
        return $this->viewerId() !== null && (bool) $this->get("liked:{$type}", (int) $id);
    }

    public function isReposted(string $type, $id): bool
    {
        return $this->viewerId() !== null && (bool) $this->get("reposted:{$type}", (int) $id);
    }

    public function isFollowing($companyId): bool
    {
        return $this->viewerId() !== null && (bool) $this->get('following', (int) $companyId);
    }

    /** Le visiteur suit-il cet utilisateur ? */
    public function isFollowingUser($userId): bool
    {
        return $this->viewerId() !== null && (bool) $this->get('user_following', (int) $userId);
    }

    /** Cet utilisateur suit-il le visiteur ? */
    public function followsViewer($userId): bool
    {
        return $this->viewerId() !== null && (bool) $this->get('user_follows_you', (int) $userId);
    }

    /** Nombre de personnes suivies par le visiteur qui suivent cet utilisateur. */
    public function mutualCount($userId): ?int
    {
        if ($this->viewerId() === null) {
            return null;
        }

        return (int) $this->get('user_mutual', (int) $userId);
    }

    public function isCommentLiked($commentId): bool
    {
        return $this->viewerId() !== null && (bool) $this->get('comment_liked', (int) $commentId);
    }

    public function activeJobsCount($companyId): int
    {
        return (int) $this->get('active_jobs', (int) $companyId);
    }

    /** Met à jour l'état après une action (like, repost, follow…). */
    public function remember(string $key, int $id, mixed $value): void
    {
        $this->resolved[$key][$id] = $value;
        unset($this->pending[$key][$id]);
    }

    /** @return string[] */
    private function keysFor(string $kind): array
    {
        return match ($kind) {
            SocialTarget::JOB => ['liked:job', 'reposted:job'],
            SocialTarget::QUICK_SERVICE => ['liked:quick_service', 'reposted:quick_service'],
            'company' => ['following', 'active_jobs'],
            'user' => ['user_following', 'user_follows_you', 'user_mutual'],
            'comment' => ['comment_liked'],
            default => [],
        };
    }

    private function get(string $key, int $id): mixed
    {
        if (! array_key_exists($id, $this->resolved[$key] ?? [])) {
            $this->pending[$key][$id] = true;
            $this->load($key);
        }

        return $this->resolved[$key][$id] ?? null;
    }

    private function load(string $key): void
    {
        $ids = array_keys($this->pending[$key] ?? []);
        unset($this->pending[$key]);
        if ($ids === []) {
            return;
        }

        foreach ($ids as $id) {
            $this->resolved[$key][$id] = null;
        }

        try {
            foreach (array_chunk($ids, self::CHUNK) as $chunk) {
                foreach ($this->fetch($key, $chunk) as $id => $value) {
                    $this->resolved[$key][(int) $id] = $value;
                }
            }
        } catch (\Throwable $e) {
            // Tables sociales absentes (migration non jouée) ou erreur SQL :
            // on sert des valeurs neutres plutôt que de casser la liste.
            Log::warning('SocialContext: chargement impossible', ['key' => $key, 'error' => $e->getMessage()]);
        }
    }

    /** @return array<int, mixed> */
    private function fetch(string $key, array $ids): array
    {
        $viewer = $this->viewerId();

        if ($key === 'active_jobs') {
            return DB::table('jobs')
                ->whereIn('company_id', $ids)
                ->where('status', 'published')
                ->whereNull('deleted_at')
                ->groupBy('company_id')
                ->selectRaw('company_id, COUNT(*) as aggregate')
                ->get()
                ->mapWithKeys(fn ($row) => [(int) $row->company_id => (int) $row->aggregate])
                ->all();
        }

        if ($viewer === null) {
            return [];
        }

        if ($key === 'user_following') {
            return DB::table('user_follows')
                ->where('follower_id', $viewer)
                ->whereIn('followed_id', $ids)
                ->pluck('followed_id')
                ->mapWithKeys(fn ($id) => [(int) $id => true])
                ->all();
        }

        if ($key === 'user_follows_you') {
            return DB::table('user_follows')
                ->where('followed_id', $viewer)
                ->whereIn('follower_id', $ids)
                ->pluck('follower_id')
                ->mapWithKeys(fn ($id) => [(int) $id => true])
                ->all();
        }

        if ($key === 'user_mutual') {
            // Abonnés de chaque utilisateur parmi les personnes que suit le
            // visiteur (une requête pour toute la page).
            return DB::table('user_follows as theirs')
                ->join('user_follows as mine', 'mine.followed_id', '=', 'theirs.follower_id')
                ->where('mine.follower_id', $viewer)
                ->whereIn('theirs.followed_id', $ids)
                ->groupBy('theirs.followed_id')
                ->selectRaw('theirs.followed_id as user_id, COUNT(*) as aggregate')
                ->get()
                ->mapWithKeys(fn ($row) => [(int) $row->user_id => (int) $row->aggregate])
                ->all();
        }

        if ($key === 'comment_liked') {
            return DB::table('comment_likes')
                ->where('user_id', $viewer)
                ->whereIn('comment_id', $ids)
                ->pluck('comment_id')
                ->mapWithKeys(fn ($id) => [(int) $id => true])
                ->all();
        }

        if ($key === 'following') {
            return DB::table('company_follows')
                ->where('user_id', $viewer)
                ->whereIn('company_id', $ids)
                ->pluck('company_id')
                ->mapWithKeys(fn ($id) => [(int) $id => true])
                ->all();
        }

        [$what, $type] = explode(':', $key, 2);
        $table = $what === 'liked' ? 'post_likes' : 'post_reposts';

        return DB::table($table)
            ->where('user_id', $viewer)
            ->where('target_type', $type)
            ->whereIn('target_id', $ids)
            ->pluck('target_id')
            ->mapWithKeys(fn ($id) => [(int) $id => true])
            ->all();
    }
}
