<?php

/**
 * Script pour mettre à jour les liens MEGA de test avec de vrais liens
 * Usage: php update_mega_links.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\TrainingVideo;

echo "\n";
echo "╔══════════════════════════════════════════════════════════╗\n";
echo "║   🔄 Mise à jour des liens MEGA pour les vidéos de test  ║\n";
echo "╚══════════════════════════════════════════════════════════╝\n";
echo "\n";

// Récupérer toutes les vidéos MEGA avec des liens fictifs
$megaVideos = TrainingVideo::where('video_type', 'mega')
                           ->where('video_url', 'LIKE', '%example%')
                           ->get();

if ($megaVideos->isEmpty()) {
    echo "✅ Aucune vidéo MEGA avec lien fictif trouvée.\n";
    echo "\n";
    exit(0);
}

echo "📹 Vidéos MEGA trouvées avec liens fictifs:\n\n";
foreach ($megaVideos as $video) {
    echo "  ID: {$video->id}\n";
    echo "  📝 Titre: {$video->title}\n";
    echo "  🔗 Lien actuel: {$video->video_url}\n";
    echo "  👁️  Preview: " . ($video->is_preview ? 'OUI' : 'NON') . "\n";
    echo "  " . str_repeat('-', 55) . "\n\n";
}

echo "💡 OPTIONS DE MISE À JOUR:\n\n";
echo "1️⃣  Option 1 - Mettre à jour manuellement dans la base:\n";
echo "   UPDATE training_videos SET video_url = 'VOTRE_LIEN_MEGA' WHERE id = XX;\n\n";

echo "2️⃣  Option 2 - Utiliser ce script interactif:\n";
echo "   (Fonctionnalité à venir)\n\n";

echo "3️⃣  Option 3 - Créer vos propres liens MEGA:\n";
echo "   a) Aller sur https://mega.nz\n";
echo "   b) Créer un compte gratuit (50 GB)\n";
echo "   c) Uploader vos vidéos de test\n";
echo "   d) Clic droit → Get link → Copy link\n";
echo "   e) Coller le lien dans la commande UPDATE ci-dessus\n\n";

echo "4️⃣  Option 4 - Utiliser des vidéos de test open source:\n";
echo "   📥 Télécharger Big Buck Bunny:\n";
echo "      wget https://download.blender.org/demo/movies/BBB/bbb_sunflower_1080p_30fps_normal.mp4.zip\n";
echo "   📤 Uploader sur MEGA\n";
echo "   🔗 Copier le lien et mettre à jour\n\n";

echo "╔══════════════════════════════════════════════════════════╗\n";
echo "║  🎯 EXEMPLE DE COMMANDES SQL POUR METTRE À JOUR         ║\n";
echo "╚══════════════════════════════════════════════════════════╝\n\n";

foreach ($megaVideos as $video) {
    echo "-- {$video->title}\n";
    echo "UPDATE training_videos\n";
    echo "SET video_url = 'https://mega.nz/file/VOTRE_ID#VOTRE_CLE'\n";
    echo "WHERE id = {$video->id};\n\n";
}

echo "╔══════════════════════════════════════════════════════════╗\n";
echo "║  📚 LIENS UTILES                                          ║\n";
echo "╚══════════════════════════════════════════════════════════╝\n\n";
echo "🌐 MEGA.nz: https://mega.nz\n";
echo "🎬 Vidéos de test Blender: https://download.blender.org/demo/movies/\n";
echo "📖 Guide complet: voir database/seeders/MEGA_LINKS_GUIDE.md\n\n";

echo "✨ Une fois mis à jour, testez les liens dans l'application mobile!\n\n";
