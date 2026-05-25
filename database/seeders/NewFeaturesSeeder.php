<?php

namespace Database\Seeders;

use App\Models\TrainingVideo;
use App\Models\TrainingVideoChapter;
use App\Models\TrainingPack;
use App\Models\ExamPaper;
use Illuminate\Database\Seeder;

class NewFeaturesSeeder extends Seeder
{
    public function run(): void
    {
        // ==============================
        // Feature 1: Chapitres pour les vidéos
        // ==============================
        $videos = TrainingVideo::all();

        foreach ($videos as $video) {
            // Ajouter 3-5 chapitres par vidéo
            $duration = $video->duration_seconds ?? 1800;
            $chapterCount = rand(3, 5);
            $interval = intdiv($duration, $chapterCount + 1);

            $chapterTitles = [
                'Introduction',
                'Concepts de base',
                'Mise en pratique',
                'Cas d\'utilisation',
                'Exercices pratiques',
                'Résumé et conclusion',
                'Configuration initiale',
                'Démonstration',
            ];

            for ($i = 0; $i < $chapterCount; $i++) {
                $timestamp = $interval * ($i + 1);
                TrainingVideoChapter::create([
                    'training_video_id' => $video->id,
                    'title' => $chapterTitles[$i] ?? "Chapitre " . ($i + 1),
                    'timestamp_seconds' => $timestamp,
                    'timestamp_formatted' => TrainingVideoChapter::formatTimestamp($timestamp),
                    'description' => "Section " . ($i + 1) . " de la vidéo « {$video->title} »",
                    'display_order' => $i + 1,
                ]);
            }
        }

        echo "✅ Chapitres ajoutés pour " . $videos->count() . " vidéos\n";

        // ==============================
        // Feature 2: Compteurs de complétion (déjà gérés via views_count/completions_count)
        // Mettre à jour les compteurs pour les vidéos existantes
        // ==============================
        foreach ($videos as $video) {
            $video->update([
                'views_count' => rand(50, 500),
                'completions_count' => rand(10, 200),
            ]);
        }

        echo "✅ Compteurs de vues/complétion mis à jour\n";

        // ==============================
        // Feature 3: Liens WhatsApp pour les packs de formation
        // ==============================
        $trainingPacks = TrainingPack::all();

        $whatsappLinks = [
            'https://chat.whatsapp.com/ExampleGroup1Laravel2026',
            'https://chat.whatsapp.com/ExampleGroup2React2026',
            'https://chat.whatsapp.com/ExampleGroup3Python2026',
            'https://chat.whatsapp.com/ExampleGroup4Marketing2026',
            'https://chat.whatsapp.com/ExampleGroup5Excel2026',
        ];

        foreach ($trainingPacks as $index => $pack) {
            $pack->update([
                'whatsapp_group_link' => $whatsappLinks[$index] ?? $whatsappLinks[0],
            ]);
        }

        echo "✅ Liens WhatsApp ajoutés pour " . $trainingPacks->count() . " packs\n";

        // ==============================
        // Feature 4: Correction disponible pour les épreuves
        // ==============================
        // Trouver les sujets et marquer certains comme ayant une correction
        $subjects = ExamPaper::where('is_correction', false)->get();
        $corrections = ExamPaper::where('is_correction', true)->get();

        $correctionIndex = 0;
        foreach ($subjects as $subject) {
            // ~60% des sujets ont une correction
            if (rand(1, 10) <= 6 && $correctionIndex < $corrections->count()) {
                $correction = $corrections[$correctionIndex];
                $subject->update([
                    'has_correction' => true,
                    'correction_paper_id' => $correction->id,
                ]);
                $correctionIndex++;
            }
        }

        echo "✅ " . $correctionIndex . " sujets marqués avec correction disponible\n";
    }
}
