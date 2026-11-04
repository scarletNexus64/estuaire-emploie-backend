<?php

/**
 * Génère public/admin-theme/tailwind-dark.css
 *
 * Le panel admin utilise Tailwind (CDN) avec des classes "light" (bg-white,
 * text-gray-900, bg-green-100...). Ce script scanne les vues admin et produit
 * des surcharges dark pour chaque classe de couleur réellement utilisée.
 * Les teintes décoratives (violet, rose, indigo...) sont ramenées au bleu
 * primaire pour garder une palette sobre.
 *
 * Usage : php scripts/build-admin-dark-css.php
 * À relancer après avoir ajouté de nouvelles classes de couleur dans les vues.
 */

$root = dirname(__DIR__);
$viewsDir = $root . '/resources/views/admin';
$outFile = $root . '/public/admin-theme/tailwind-dark.css';

// Familles Tailwind -> famille sémantique
$familyMap = [
    'blue' => 'primary', 'sky' => 'primary', 'cyan' => 'primary', 'indigo' => 'primary',
    'violet' => 'primary', 'purple' => 'primary', 'fuchsia' => 'primary', 'pink' => 'primary',
    'green' => 'success', 'emerald' => 'success', 'teal' => 'success', 'lime' => 'success',
    'red' => 'danger', 'rose' => 'danger',
    'orange' => 'warning', 'amber' => 'warning', 'yellow' => 'warning',
    'gray' => 'gray', 'slate' => 'gray', 'zinc' => 'gray', 'neutral' => 'gray', 'stone' => 'gray',
];

// rgb = teinte de base (fonds translucides / bordures), solid = fond plein, text = texte
$palette = [
    'primary' => ['rgb' => '91 141 239',  'solid' => '#3f72d6', 'hover' => '#4a7fe0', 'deep' => '#1c2c4a', 'text' => '#7ea6f4', 'strong' => '#b8cdf8'],
    'success' => ['rgb' => '79 178 134',  'solid' => '#2f8a63', 'hover' => '#379870', 'deep' => '#173a2b', 'text' => '#6cc39b', 'strong' => '#a6dcc2'],
    'danger'  => ['rgb' => '226 112 111', 'solid' => '#b94a4a', 'hover' => '#c75656', 'deep' => '#4a2222', 'text' => '#ec8b8a', 'strong' => '#f3b8b7'],
    'warning' => ['rgb' => '214 167 74',  'solid' => '#a5781f', 'hover' => '#b58628', 'deep' => '#45340f', 'text' => '#dcb25f', 'strong' => '#ecd29c'],
];

$gray = [
    'bg' => [50 => 'var(--surface-2)', 100 => 'var(--surface-3)', 200 => '#252c39', 300 => '#2f3846', 400 => '#465062',
             500 => '#5a6477', 600 => '#3a4352', 700 => '#2f3846', 800 => 'var(--surface-3)', 900 => 'var(--surface)', 950 => 'var(--bg)'],
    'text' => [50 => '#eef1f5', 100 => '#e4e8ef', 200 => '#d3d9e2', 300 => '#c2c9d4', 400 => 'var(--subtle)', 500 => 'var(--muted)',
               600 => '#9ba4b4', 700 => 'var(--text-2)', 800 => 'var(--text)', 900 => 'var(--text)', 950 => 'var(--text)'],
    'border' => [50 => 'var(--border)', 100 => 'var(--border)', 200 => 'var(--border)', 300 => 'var(--border-strong)', 400 => 'var(--border-strong)',
                 500 => '#465062', 600 => 'var(--border-strong)', 700 => 'var(--border-strong)', 800 => 'var(--border)', 900 => 'var(--border)', 950 => 'var(--border)'],
];

function colorFor(string $prop, string $sem, int $shade, array $palette, array $gray): ?string
{
    if ($sem === 'gray') {
        $kind = match ($prop) {
            'text', 'placeholder' => 'text',
            'border', 'divide', 'ring' => 'border',
            default => 'bg',
        };
        return $gray[$kind][$shade] ?? null;
    }
    $p = $palette[$sem];
    $soft = fn (float $a) => "rgb({$p['rgb']} / {$a})";
    switch ($prop) {
        case 'text':
        case 'placeholder':
            return $shade <= 200 || $shade >= 800 ? $p['strong'] : $p['text'];
        case 'border':
        case 'divide':
            return $shade <= 200 ? $soft(0.22) : ($shade <= 400 ? $soft(0.35) : ($shade <= 700 ? $soft(0.55) : $soft(0.4)));
        case 'ring':
            return $soft(0.4);
        default: // bg, from, via, to
            return match (true) {
                $shade <= 50 => $soft(0.08),
                $shade <= 100 => $soft(0.12),
                $shade <= 200 => $soft(0.16),
                $shade <= 300 => $soft(0.24),
                $shade === 400, $shade === 500, $shade === 700 => $p['hover'],
                $shade === 600 => $p['solid'],
                default => $p['deep'],
            };
    }
}

function declarations(string $prop, string $color): string
{
    return match ($prop) {
        'bg' => "background-color: {$color} !important;",
        'text' => "color: {$color} !important;",
        'border' => "border-color: {$color} !important;",
        'ring' => "--tw-ring-color: {$color} !important;",
        'divide' => "border-color: {$color} !important;",
        'placeholder' => "color: {$color} !important;",
        'from' => "--tw-gradient-from: {$color} var(--tw-gradient-from-position) !important; --tw-gradient-to: transparent var(--tw-gradient-to-position);",
        'via' => "--tw-gradient-stops: var(--tw-gradient-from), {$color} var(--tw-gradient-via-position), var(--tw-gradient-to) !important;",
        'to' => "--tw-gradient-to: {$color} var(--tw-gradient-to-position) !important;",
    };
}

function selector(string $class, ?string $variant, string $prop): string
{
    $esc = '.' . str_replace(':', '\\:', $class);
    $suffix = $prop === 'divide' ? ' > :not([hidden]) ~ :not([hidden])' : ($prop === 'placeholder' ? '::placeholder' : '');
    return match ($variant) {
        'hover' => "{$esc}:hover{$suffix}",
        'focus' => "{$esc}:focus{$suffix}",
        'active' => "{$esc}:active{$suffix}",
        'focus-within' => "{$esc}:focus-within{$suffix}",
        'disabled' => "{$esc}:disabled{$suffix}",
        'group-hover' => ".group:hover {$esc}{$suffix}",
        'peer-checked' => ".peer:checked ~ {$esc}{$suffix}",
        default => "{$esc}{$suffix}",
    };
}

// 1. Collecte des classes utilisées
$classes = [];
$rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($viewsDir, FilesystemIterator::SKIP_DOTS));
foreach ($rii as $file) {
    if (! str_ends_with($file->getFilename(), '.php')) {
        continue;
    }
    preg_match_all(
        '/(?<![\w:-])((?:(?:hover|focus|active|focus-within|disabled|group-hover|peer-checked|sm|md|lg|xl):)*)(bg|text|border(?:-[trblxy])?|ring|divide|placeholder|from|via|to)-(white|[a-z]+-\d{2,3})(?![\w\/-])/',
        file_get_contents($file->getPathname()),
        $m,
        PREG_SET_ORDER
    );
    foreach ($m as $hit) {
        $classes[$hit[0]] = $hit;
    }
}
ksort($classes);

$responsive = ['sm' => 640, 'md' => 768, 'lg' => 1024, 'xl' => 1280];
$rules = [];
$media = [];

foreach ($classes as $class => [$full, $variantChain, $prop, $colorPart]) {
    $baseProp = str_starts_with($prop, 'border') ? 'border' : $prop;
    $variants = array_filter(explode(':', rtrim($variantChain, ':')));
    $screen = null;
    $state = null;
    foreach ($variants as $v) {
        isset($responsive[$v]) ? $screen = $v : $state = $v;
    }

    if ($colorPart === 'white') {
        // Seul bg-white (fond de carte) est inversé ; text-white / border-white restent blancs
        if ($baseProp !== 'bg' && $baseProp !== 'from' && $baseProp !== 'to' && $baseProp !== 'via') {
            continue;
        }
        $color = 'var(--surface)';
    } else {
        [$family, $shade] = [substr($colorPart, 0, strrpos($colorPart, '-')), (int) substr($colorPart, strrpos($colorPart, '-') + 1)];
        if (! isset($familyMap[$family])) {
            continue;
        }
        $color = colorFor($baseProp, $familyMap[$family], $shade, $palette, $gray);
        if ($color === null) {
            continue;
        }
    }

    $decl = declarations($baseProp, $color);
    if ($prop !== $baseProp && $baseProp === 'border') {
        $side = ['border-t' => 'top', 'border-r' => 'right', 'border-b' => 'bottom', 'border-l' => 'left'][$prop] ?? null;
        $decl = $side ? "border-{$side}-color: {$color} !important;" : $decl;
    }
    $rule = selector($full, $state, $baseProp) . " { {$decl} }";
    $screen ? $media[$screen][] = $rule : $rules[] = $rule;
}

$css = "/* Fichier généré par scripts/build-admin-dark-css.php — ne pas modifier à la main. */\n";
$css .= implode("\n", $rules) . "\n";
foreach ($media as $screen => $list) {
    $css .= "@media (min-width: {$responsive[$screen]}px) {\n  " . implode("\n  ", $list) . "\n}\n";
}

if (! is_dir(dirname($outFile))) {
    mkdir(dirname($outFile), 0755, true);
}
file_put_contents($outFile, $css);
echo 'OK: ' . (count($rules) + array_sum(array_map('count', $media))) . " règles -> {$outFile}\n";
