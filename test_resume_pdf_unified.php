<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Resume;
use App\Services\Resume\ResumePdfService;

echo "\n=== Test de génération PDF avec template unifié ===\n\n";

// Récupérer le premier CV disponible avec la relation user
$resume = Resume::with('user')->first();

if (!$resume) {
    echo "❌ Aucun CV trouvé dans la base de données.\n";
    echo "💡 Créez d'abord un CV depuis l'application mobile.\n";
    exit(1);
}

echo "📄 CV trouvé : {$resume->title}\n";
echo "👤 Utilisateur : " . ($resume->user ? $resume->user->name : 'N/A') . "\n";
echo "🎨 Template : {$resume->template_type}\n\n";

// Générer le PDF
try {
    $pdfService = new ResumePdfService();
    
    echo "⏳ Génération du PDF en cours...\n";
    $pdfPath = $pdfService->generatePdf($resume);
    
    echo "✅ PDF généré avec succès !\n";
    echo "📁 Chemin : storage/app/public/{$pdfPath}\n";
    echo "🌐 URL : " . $resume->fresh()->pdf_url . "\n";
    
    // Vérifier que le fichier existe
    $fullPath = storage_path('app/public/' . $pdfPath);
    if (file_exists($fullPath)) {
        $fileSize = filesize($fullPath);
        echo "✅ Fichier vérifié : " . number_format($fileSize / 1024, 2) . " KB\n";
    } else {
        echo "❌ Fichier non trouvé à : {$fullPath}\n";
    }
    
} catch (\Exception $e) {
    echo "❌ Erreur lors de la génération du PDF\n";
    echo "Message : " . $e->getMessage() . "\n";
    echo "Fichier : " . $e->getFile() . ":" . $e->getLine() . "\n";
    exit(1);
}

echo "\n✅ Test terminé avec succès !\n\n";
