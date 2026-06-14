<?php

namespace Database\Seeders\Roadmaps;

use App\Models\Roadmap;
use App\Models\RoadmapLevel;
use App\Models\RoadmapQuestion;
use Illuminate\Support\Str;

/**
 * Helper partagé par tous les seeders de roadmaps.
 *
 * Permet de créer une roadmap complète (roadmap + niveaux + QCM) à partir
 * d'un tableau descriptif, de façon idempotente (la roadmap est recréée si
 * son slug existe déjà).
 *
 * Format attendu :
 * [
 *   'title' => '...', 'domain' => 'sql', 'description' => '...',
 *   'objectives' => "...\n...", 'icon' => '🗄️', 'color' => '#0EA5E9',
 *   'difficulty' => 'beginner', 'required_packs' => [], 'pass_threshold' => 70,
 *   'order' => 1,
 *   'levels' => [
 *     [
 *       'title' => 'Niveau 1 : ...', 'subtitle' => '...', 'xp_reward' => 100,
 *       'content' => "🎯 ...\n✅ ...",
 *       'questions' => [
 *         [
 *           'question' => '...?',
 *           'options' => ['A', 'B', 'C', 'D'],
 *           'correct' => [1],            // index 0-based
 *           'explanation' => '...',
 *         ],
 *       ],
 *     ],
 *   ],
 * ]
 */
trait RoadmapSeederHelper
{
    protected function createRoadmap(array $data): Roadmap
    {
        $slug = $data['slug'] ?? Str::slug($data['title']);

        // Idempotence : on repart d'une roadmap propre.
        Roadmap::where('slug', $slug)->get()->each(function ($existing) {
            $existing->delete(); // cascade niveaux + questions via FK
        });

        $roadmap = Roadmap::create([
            'title' => $data['title'],
            'slug' => $slug,
            'domain' => $data['domain'],
            'description' => $data['description'],
            'objectives' => $data['objectives'] ?? null,
            'icon' => $data['icon'] ?? '🗺️',
            'color' => $data['color'] ?? '#6366F1',
            'difficulty' => $data['difficulty'] ?? 'beginner',
            'required_packs' => $data['required_packs'] ?? [],
            'pass_threshold' => $data['pass_threshold'] ?? 70,
            'order' => $data['order'] ?? 0,
            'is_active' => $data['is_active'] ?? true,
        ]);

        foreach ($data['levels'] ?? [] as $i => $levelData) {
            $questions = $levelData['questions'] ?? [];

            $level = RoadmapLevel::create([
                'roadmap_id' => $roadmap->id,
                'title' => $levelData['title'],
                'subtitle' => $levelData['subtitle'] ?? null,
                'content' => $levelData['content'],
                'order' => $levelData['order'] ?? ($i + 1),
                'has_quiz' => !empty($questions),
                'xp_reward' => $levelData['xp_reward'] ?? 100,
            ]);

            foreach ($questions as $qi => $q) {
                RoadmapQuestion::create([
                    'roadmap_level_id' => $level->id,
                    'question' => $q['question'],
                    'options' => $q['options'],
                    'correct_answers' => $q['correct'] ?? $q['correct_answers'] ?? [],
                    'explanation' => $q['explanation'] ?? null,
                    'order' => $qi + 1,
                ]);
            }
        }

        return $roadmap;
    }
}
