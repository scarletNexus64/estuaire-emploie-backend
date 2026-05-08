<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Job;
use App\Models\Resume;

// 1) Themes file
echo "=== Themes file dup check ===\n";
$sp = IOFactory::load('/home/kirasb/Themes_Stage_BTS_Cameroun.xlsx');
$rows = $sp->getActiveSheet()->toArray(null, true, true, true);

$titlesToImport = [];
foreach ($rows as $r) {
    if (empty($r['B']) || $r['A'] === 'entreprise') continue;
    $t = trim((string)($r['E'] ?? ''));
    if ($t !== '') $titlesToImport[$t] = true;
}
echo "Unique titles in xlsx: " . count($titlesToImport) . "\n";

$existing = Job::where('company_id', 39)
    ->whereIn('title', array_keys($titlesToImport))
    ->pluck('title');
echo "Already in DB (would be skipped with --skip-duplicates): " . $existing->count() . "\n";
echo "New to import: " . (count($titlesToImport) - $existing->count()) . "\n";

// Show 5 samples
echo "Sample existing duplicates:\n";
foreach ($existing->take(5) as $t) echo "  - " . substr($t, 0, 100) . "\n";

// 2) CVs file
echo "\n\n=== CVs file dup check ===\n";
$sp = IOFactory::load('/home/kirasb/CVs_IUEs_INSAM_Complet_1.xlsx');
$rows = $sp->getActiveSheet()->toArray(null, true, true, true);

$pairsToImport = [];
foreach ($rows as $i => $r) {
    if ($i === 1) continue;
    if (empty($r['A'])) continue;
    $level = trim((string)$r['A']);
    $spec = trim((string)($r['B'] ?? ''));
    $prof = trim((string)($r['F'] ?? ''));
    $pair = $level . ' || ' . $spec;
    if (!isset($pairsToImport[$pair])) {
        $pairsToImport[$pair] = ['level' => $level, 'spec' => $spec, 'prof' => $prof, 'row' => $i];
    }
}
echo "Unique level/specialty pairs in xlsx: " . count($pairsToImport) . "\n";

// Detect odd levels (encoding corruption)
$oddLevels = [];
foreach ($pairsToImport as $p) {
    if (preg_match('/[+]/', $p['level'])) {
        $oddLevels[] = $p;
    }
}
echo "Levels with suspicious chars (+):\n";
foreach ($oddLevels as $p) {
    echo "  row {$p['row']}: '{$p['level']}' — spec='{$p['spec']}' — bytes=" . bin2hex($p['level']) . "\n";
}

// Check existing in DB
$existingPairs = Resume::where('customization->source', 'INSAM_IMPORT')
    ->get(['customization', 'title'])
    ->map(function ($r) {
        $c = $r->customization ?: [];
        $level = trim((string)($c['level'] ?? ''));
        $spec = trim((string)($c['specialty'] ?? ''));
        return $level . ' || ' . $spec;
    })
    ->unique()
    ->values();

echo "\nExisting unique level/specialty pairs in DB: " . $existingPairs->count() . "\n";
$existingArr = $existingPairs->toArray();
$dupCount = 0;
foreach ($pairsToImport as $pair => $info) {
    if (in_array($pair, $existingArr)) {
        $dupCount++;
    }
}
echo "Pairs from xlsx already in DB: $dupCount\n";
echo "Pairs new to import: " . (count($pairsToImport) - $dupCount) . "\n";

echo "\nSample existing pairs in DB (first 10):\n";
foreach ($existingPairs->take(10) as $p) echo "  - $p\n";

echo "\nSample to import (first 10):\n";
$count = 0;
foreach ($pairsToImport as $pair => $info) {
    if (!in_array($pair, $existingArr)) {
        echo "  - $pair\n";
        if (++$count >= 10) break;
    }
}

// Distribution of levels in xlsx
$levelDist = [];
foreach ($pairsToImport as $info) {
    $levelDist[$info['level']] = ($levelDist[$info['level']] ?? 0) + 1;
}
ksort($levelDist);
echo "\nXLSX level distribution:\n";
foreach ($levelDist as $l => $n) echo "  - '$l': $n\n";
