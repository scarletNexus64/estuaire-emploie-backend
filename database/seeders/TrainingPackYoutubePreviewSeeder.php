<?php

namespace Database\Seeders;

use App\Models\TrainingPack;
use App\Models\TrainingVideo;
use Illuminate\Database\Seeder;

class TrainingPackYoutubePreviewSeeder extends Seeder
{
    /**
     * Mapping: pack name (or partial) => [youtube_id, title]
     * Chaque pack aura une vidéo preview YouTube gratuite comme avant-première
     */
    private array $packPreviews = [
        "Pack Apprendre l'anglais" => [
            'id' => 'GhRlYj8i92c',
            'title' => "Apprendre l'anglais - Cours complet pour débutants",
        ],
        'Pack Automatisation' => [
            'id' => 'DKHTCmvyZH4',
            'title' => 'GRAFCET Automatisme - La représentation graphique du GRAFCET',
        ],
        'Pack Business' => [
            'id' => 'c9Ph_oLJ2_I',
            'title' => 'Comprendre la gestion et le business - Formation courte',
        ],
        'Pack Communication et marketing' => [
            'id' => 'lIvl1syEKfs',
            'title' => 'Formation marketing - Cours marketing complet gratuit',
        ],
        'Pack Comptabilite' => [
            'id' => 'KpKREkxpkFQ',
            'title' => 'Comptabilité générale - Introduction au plan comptable',
        ],
        'Pack Dessin technique' => [
            'id' => 'VxnX9Nr3P-M',
            'title' => 'AutoCAD - Débuter rapidement en DAO, tout commence ici',
        ],
        'Pack Dessin, cinematographie et photographie' => [
            'id' => 'yOVOSVoyhvg',
            'title' => 'Les bases de la photographie en moins de 10 minutes',
        ],
        'Pack Developpement personnel' => [
            'id' => 'lLchHt_TWNk',
            'title' => 'Les 5 étapes clés du développement personnel',
        ],
        'Pack Digital marketing' => [
            'id' => '5IcZPL-zJYc',
            'title' => 'Formation Marketing Digital 2026 - Le cours complet',
        ],
        'Pack Electrotechnique' => [
            'id' => 'DMK0B5PeUek',
            'title' => 'Programme de formation gratuit en électrotechnique',
        ],
        'Pack Finance' => [
            'id' => 'IKelzO7R3D0',
            'title' => 'Les bases des finances personnelles - Principes indispensables',
        ],
        'Pack Genie civil' => [
            'id' => 'vFMQdTS9rXI',
            'title' => 'Les bases en génie civil que vous devez apprendre',
        ],
        'Pack Gestion de projets' => [
            'id' => 'Q00H6xrYTOw',
            'title' => 'Formation gratuite à la gestion de projet',
        ],
        'Pack Infographie' => [
            'id' => 'P68C7YLI72I',
            'title' => 'Les bases du graphisme - Épisode 1',
        ],
        'Pack Informatique' => [
            'id' => 'e7chauwrX6M',
            'title' => "Cours en ligne en informatique - Les fondamentaux",
        ],
        'Pack Informatique programmation' => [
            'id' => 'KOmibP9DuTc',
            'title' => 'Débuter en programmation - Partie 1',
        ],
        'Pack Intelligence artificielle' => [
            'id' => 'N457FqQtGDk',
            'title' => "Cours d'Intelligence Artificielle pour débutants",
        ],
        'Pack Marketing' => [
            'id' => 'CRznStTvj-M',
            'title' => "Le marketing c'est quoi ? - Définition du marketing",
        ],
        'Pack Mathematic' => [
            'id' => 'hTb09ceJNGw',
            'title' => 'Calcul de pourcentages - Cours de mathématiques',
        ],
        'Pack Mathematiques' => [
            'id' => 'z41SHoAQ5yE',
            'title' => 'Algèbre - Variables et inconnues',
        ],
        'Pack Microsoft' => [
            'id' => 'Q_NLy_NQ86M',
            'title' => 'Les bases de Word - Formation gratuite Microsoft Word',
        ],
        'Pack Monnaie virtuelle' => [
            'id' => 'jhtJ1zwAkQE',
            'title' => 'Crypto-monnaie - Tutoriel débutant de A à Z',
        ],
        'Pack Music' => [
            'id' => 'j9F0tNq-OFE',
            'title' => 'Cours de solfège débutant - La portée musicale',
        ],
        'Pack Musique dj' => [
            'id' => 'e9_dDSnpXFQ',
            'title' => 'Apprendre à mixer - Les bases du mix',
        ],
        'Pack Pedagogie' => [
            'id' => 'HxjhKqdv5F4',
            'title' => "Les trois piliers de l'acte pédagogique - Philippe Meirieu",
        ],
        'Pack Photography' => [
            'id' => 'yOVOSVoyhvg',
            'title' => 'Les bases de la photographie en moins de 10 minutes',
        ],
        'Pack Programming' => [
            'id' => 'oUJolR5bX6g',
            'title' => 'Apprendre Python - Tuto programmation complet débutant',
        ],
        'Pack Reseau' => [
            'id' => 'OKXlgAPVJ8Y',
            'title' => 'CCNA Introduction - Certifications CISCO Réseau',
        ],
        'Pack Sciences' => [
            'id' => 'ky2SGB8E6ZI',
            'title' => 'Programme de physique chimie au lycée - Révisions',
        ],
        'Pack Sport' => [
            'id' => 'BjSntgAHgj0',
            'title' => 'Cours de Cross Training - Fitness Club',
        ],
        'Pack Transport' => [
            'id' => 'EMWPU4TbOMw',
            'title' => 'Les fondamentaux de la logistique - Partie 1',
        ],
        'Pack Woman and beauty' => [
            'id' => 'Zr0Neg5GO1s',
            'title' => 'Cours de maquillage en ligne - Découvrez le concept',
        ],
    ];

    public function run(): void
    {
        $updated = 0;
        $created = 0;

        foreach ($this->packPreviews as $packName => $youtube) {
            $pack = TrainingPack::where('name', $packName)->first();

            if (!$pack) {
                $this->command->warn("  Pack introuvable: {$packName}");
                continue;
            }

            $youtubeUrl = "https://www.youtube.com/watch?v={$youtube['id']}";
            $thumbnailUrl = "https://img.youtube.com/vi/{$youtube['id']}/hqdefault.jpg";

            // Chercher la vidéo preview existante de ce pack
            $existingPreview = $pack->trainingVideos()
                ->where('is_preview', true)
                ->first();

            if ($existingPreview) {
                // Mettre à jour la preview existante : passer de MEGA à YouTube
                $existingPreview->update([
                    'title' => "[Avant-première] " . $youtube['title'],
                    'description' => "Avant-première gratuite du {$packName}. Découvrez un aperçu de cette formation avant de vous abonner.",
                    'video_url' => $youtubeUrl,
                    'video_type' => 'youtube',
                    'video_path' => null,
                    'video_filename' => null,
                    'video_size' => null,
                    'is_preview' => true,
                    'is_active' => true,
                ]);

                $updated++;
                $this->command->info("  ✅ {$packName} → preview mise à jour (YouTube: {$youtube['id']})");
            } else {
                // Créer une nouvelle vidéo preview YouTube
                $video = TrainingVideo::create([
                    'title' => "[Avant-première] " . $youtube['title'],
                    'description' => "Avant-première gratuite du {$packName}. Découvrez un aperçu de cette formation avant de vous abonner.",
                    'video_url' => $youtubeUrl,
                    'video_type' => 'youtube',
                    'is_preview' => true,
                    'is_active' => true,
                    'display_order' => 0,
                    'views_count' => 0,
                    'completions_count' => 0,
                ]);

                // Attacher au pack en première position
                $pack->trainingVideos()->attach($video->id, [
                    'section_name' => 'Avant-première',
                    'section_order' => 0,
                    'display_order' => 0,
                ]);

                $created++;
                $this->command->info("  ✅ {$packName} → preview créée (YouTube: {$youtube['id']})");
            }
        }

        $this->command->info("\n🎉 Terminé ! {$updated} previews mises à jour, {$created} previews créées.");
        $this->command->info("📺 Toutes les avant-premières sont maintenant sur YouTube (gratuites).");
        $this->command->info("💰 Le reste du contenu reste sur MEGA (payant).");
    }
}
