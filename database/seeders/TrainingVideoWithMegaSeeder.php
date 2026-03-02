<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TrainingVideo;
use App\Models\TrainingPack;
use Illuminate\Support\Facades\DB;

class TrainingVideoWithMegaSeeder extends Seeder
{
    /**
     * Seeder pour créer des vidéos de test avec différentes sources
     * (Upload, YouTube, Vimeo, MEGA)
     */
    public function run(): void
    {
        // Récupérer un pack existant ou en créer un nouveau
        $pack = TrainingPack::first();

        if (!$pack) {
            $pack = TrainingPack::create([
                'name' => 'Pack de Test - Sources Vidéo Multiples',
                'slug' => 'pack-test-sources-video',
                'description' => 'Pack de test avec vidéos de différentes sources (YouTube, MEGA, Vimeo, Upload)',
                'price_xaf' => 5000,
                'category' => 'Test',
                'level' => 'Débutant',
                'instructor_name' => 'Claude AI',
                'instructor_bio' => 'Assistant de test automatisé',
                'is_active' => true,
                'is_featured' => true,
                'display_order' => 0,
            ]);
        }

        echo "📦 Pack utilisé: {$pack->name} (ID: {$pack->id})\n\n";

        // ========================================
        // 1. VIDÉOS YOUTUBE (Gratuites et populaires)
        // ========================================
        $youtubeVideos = [
            [
                'title' => '🎥 Big Buck Bunny - Film d\'animation Open Source',
                'description' => 'Film d\'animation court gratuit et open source, idéal pour tester les lecteurs vidéo.',
                'video_type' => 'youtube',
                'video_url' => 'https://www.youtube.com/watch?v=aqz-KE-bpKQ',
                'duration_seconds' => 596, // 9:56
                'is_preview' => true,
            ],
            [
                'title' => '🎬 Sintel - Court métrage Open Source',
                'description' => 'Un autre excellent court métrage open source de Blender Foundation.',
                'video_type' => 'youtube',
                'video_url' => 'https://www.youtube.com/watch?v=eRsGyueVLvQ',
                'duration_seconds' => 888, // 14:48
                'is_preview' => true,
            ],
            [
                'title' => '📚 Tutoriel Flutter - Introduction',
                'description' => 'Introduction à Flutter pour débutants',
                'video_type' => 'youtube',
                'video_url' => 'https://www.youtube.com/watch?v=1ukSR1GRtMU',
                'duration_seconds' => 615,
                'is_preview' => false,
            ],
        ];

        echo "📹 Création des vidéos YouTube...\n";
        foreach ($youtubeVideos as $index => $videoData) {
            $video = TrainingVideo::create(array_merge($videoData, [
                'is_active' => true,
                'display_order' => $index,
                'duration_formatted' => TrainingVideo::formatDuration($videoData['duration_seconds']),
            ]));

            // Associer au pack
            DB::table('training_pack_videos')->insert([
                'training_pack_id' => $pack->id,
                'training_video_id' => $video->id,
                'section_name' => 'Vidéos YouTube',
                'section_order' => 1,
                'display_order' => $index,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            echo "  ✅ {$video->title}\n";
        }

        // ========================================
        // 2. VIDÉOS MEGA.NZ (Exemples de liens publics)
        // ========================================
        $megaVideos = [
            [
                'title' => '☁️ MEGA - Vidéo de démonstration 1',
                'description' => 'Vidéo hébergée sur MEGA.nz - Test de streaming externe',
                'video_type' => 'mega',
                'video_url' => 'https://mega.nz/file/example1#key1',
                'duration_seconds' => 300,
                'is_preview' => true,
            ],
            [
                'title' => '☁️ MEGA - Vidéo de démonstration 2',
                'description' => 'Deuxième vidéo hébergée sur MEGA.nz',
                'video_type' => 'mega',
                'video_url' => 'https://mega.nz/file/example2#key2',
                'duration_seconds' => 420,
                'is_preview' => false,
            ],
            [
                'title' => '☁️ MEGA - Formation complète',
                'description' => 'Formation complète hébergée sur MEGA - Nécessite l\'achat du pack',
                'video_type' => 'mega',
                'video_url' => 'https://mega.nz/file/example3#key3',
                'duration_seconds' => 1800,
                'is_preview' => false,
            ],
        ];

        echo "\n☁️  Création des vidéos MEGA.nz...\n";
        echo "⚠️  IMPORTANT: Ces liens MEGA sont des exemples fictifs.\n";
        echo "   Remplacez-les par vos vrais liens MEGA publics.\n\n";

        foreach ($megaVideos as $index => $videoData) {
            $video = TrainingVideo::create(array_merge($videoData, [
                'is_active' => true,
                'display_order' => 100 + $index,
                'duration_formatted' => TrainingVideo::formatDuration($videoData['duration_seconds']),
            ]));

            // Associer au pack
            DB::table('training_pack_videos')->insert([
                'training_pack_id' => $pack->id,
                'training_video_id' => $video->id,
                'section_name' => 'Vidéos MEGA.nz',
                'section_order' => 2,
                'display_order' => $index,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            echo "  ✅ {$video->title}\n";
        }

        // ========================================
        // 3. VIDÉOS VIMEO (Exemples publics)
        // ========================================
        $vimeoVideos = [
            [
                'title' => '▶️ Vimeo - Showreel créatif',
                'description' => 'Vidéo de démonstration hébergée sur Vimeo',
                'video_type' => 'vimeo',
                'video_url' => 'https://vimeo.com/148751763',
                'duration_seconds' => 90,
                'is_preview' => true,
            ],
            [
                'title' => '▶️ Vimeo - Tutoriel design',
                'description' => 'Tutoriel de design graphique',
                'video_type' => 'vimeo',
                'video_url' => 'https://vimeo.com/336812660',
                'duration_seconds' => 180,
                'is_preview' => false,
            ],
        ];

        echo "\n▶️  Création des vidéos Vimeo...\n";
        foreach ($vimeoVideos as $index => $videoData) {
            $video = TrainingVideo::create(array_merge($videoData, [
                'is_active' => true,
                'display_order' => 200 + $index,
                'duration_formatted' => TrainingVideo::formatDuration($videoData['duration_seconds']),
            ]));

            // Associer au pack
            DB::table('training_pack_videos')->insert([
                'training_pack_id' => $pack->id,
                'training_video_id' => $video->id,
                'section_name' => 'Vidéos Vimeo',
                'section_order' => 3,
                'display_order' => $index,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            echo "  ✅ {$video->title}\n";
        }

        // ========================================
        // RÉSUMÉ
        // ========================================
        echo "\n" . str_repeat('=', 60) . "\n";
        echo "✅ SEEDER TERMINÉ\n";
        echo str_repeat('=', 60) . "\n";
        echo "📦 Pack: {$pack->name}\n";
        echo "📹 Total vidéos YouTube: " . count($youtubeVideos) . "\n";
        echo "☁️  Total vidéos MEGA: " . count($megaVideos) . "\n";
        echo "▶️  Total vidéos Vimeo: " . count($vimeoVideos) . "\n";
        echo "🎥 Total: " . (count($youtubeVideos) + count($megaVideos) + count($vimeoVideos)) . " vidéos\n";
        echo "\n💡 PROCHAINES ÉTAPES:\n";
        echo "   1. Remplacez les liens MEGA fictifs par vos vrais liens\n";
        echo "   2. Testez chaque type de vidéo dans l'app mobile\n";
        echo "   3. Vérifiez que les vidéos en preview sont accessibles sans achat\n";
        echo str_repeat('=', 60) . "\n";
    }
}
