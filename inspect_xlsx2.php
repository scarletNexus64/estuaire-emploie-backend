<?php
require __DIR__ . '/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

// Find all "section headers" in themes file (lines where only column A has a value, looking like a category)
$sp = IOFactory::load('/home/kirasb/Themes_Stage_BTS_Cameroun.xlsx');
$ws = $sp->getActiveSheet();
$rows = $ws->toArray(null, true, true, true);

$sections = [];
$dataCount = 0;
$currentSection = null;
foreach ($rows as $i => $r) {
    $a = $r['A'] ?? null;
    $b = $r['B'] ?? null;
    // Section header: only A populated, looks like a BTS title (uppercase)
    if ($a && empty($b) && empty($r['C']) && empty($r['D']) && empty($r['E'])) {
        $currentSection = $a;
        $sections[$a] = ['start' => $i, 'count' => 0];
    } elseif ($a === 'entreprise') {
        // header row
    } elseif ($a && $b) {
        if ($currentSection) {
            $sections[$currentSection]['count']++;
        }
        $dataCount++;
    }
}

echo "Total data rows: $dataCount\n";
echo "Sections found: " . count($sections) . "\n\n";
foreach ($sections as $name => $info) {
    echo "  - $name: {$info['count']} rows (starts at row {$info['start']})\n";
}

// Categories used in data
$cats = [];
$contracts = [];
$locations = [];
foreach ($rows as $i => $r) {
    if (($r['A'] ?? '') === 'entreprise' || empty($r['B']) || ($r['A'] ?? '') === null) continue;
    if (!empty($r['B'])) $cats[$r['B']] = ($cats[$r['B']] ?? 0) + 1;
    if (!empty($r['D'])) $contracts[$r['D']] = ($contracts[$r['D']] ?? 0) + 1;
    if (!empty($r['C'])) $locations[$r['C']] = ($locations[$r['C']] ?? 0) + 1;
}
echo "\nCategories: " . json_encode($cats, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . "\n";
echo "Contract types: " . json_encode($contracts, JSON_UNESCAPED_UNICODE) . "\n";
echo "Locations: " . json_encode($locations, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . "\n";

// Now CVs file
echo "\n\n===== CVs file =====\n";
$sp = IOFactory::load('/home/kirasb/CVs_IUEs_INSAM_Complet_1.xlsx');
$ws = $sp->getActiveSheet();
$rows = $ws->toArray(null, true, true, true);
$levels = [];
$pairs = [];
$total = 0;
foreach ($rows as $i => $r) {
    if ($i === 1) continue;
    if (empty($r['A'])) continue;
    $level = trim($r['A']);
    $sp2 = trim($r['B'] ?? '');
    $levels[$level] = ($levels[$level] ?? 0) + 1;
    $pairs[$level . '||' . $sp2] = ($pairs[$level . '||' . $sp2] ?? 0) + 1;
    $total++;
}
echo "Total CV rows: $total\n";
echo "Levels: " . json_encode($levels, JSON_UNESCAPED_UNICODE) . "\n";
echo "Level/Specialty pairs (count of duplicates):\n";
$dups = 0;
foreach ($pairs as $k => $c) {
    if ($c > 1) {
        echo "  DUP ($c): $k\n";
        $dups++;
    }
}
echo "Total unique pairs: " . count($pairs) . " | with duplicates: $dups\n";
