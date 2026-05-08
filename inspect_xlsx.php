<?php
require __DIR__ . '/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

function dump($path, $maxRows = 6) {
    echo "===== $path =====\n";
    $sp = IOFactory::load($path);
    foreach ($sp->getAllSheets() as $sheet) {
        $rows = $sheet->toArray(null, true, true, true);
        $title = $sheet->getTitle();
        $count = count($rows);
        echo "-- Sheet: $title ($count rows) --\n";
        $shown = 0;
        foreach ($rows as $i => $row) {
            if ($shown >= $maxRows) break;
            echo "Row $i: ";
            $vals = array_map(fn($v) => is_string($v) ? mb_substr($v, 0, 100) : $v, $row);
            echo json_encode($vals, JSON_UNESCAPED_UNICODE) . "\n";
            $shown++;
        }
    }
}
dump('/home/kirasb/Themes_Stage_BTS_Cameroun.xlsx', 6);
echo "\n\n";
dump('/home/kirasb/CVs_IUEs_INSAM_Complet_1.xlsx', 4);
