<?php
// Template Professional
private function getProfessionalTemplate(Resume $resume): string
{
    $primaryColor = '#2c5282';
    $accentColor = '#2d3748';

    $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>' . htmlspecialchars($resume->title) . '</title>
    <style>
        @page { margin: 20mm 15mm; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 10pt;
            line-height: 1.6;
            color: #2d3748;
        }
        .header {
            background: ' . $primaryColor . ';
            color: white;
            padding: 25px 20px;
            margin: -20mm -15mm 20px -15mm;
        }
        .header h1 {
            font-size: 26px;
            margin-bottom: 8px;
            font-weight: 600;
        }
        .header .title {
            font-size: 13pt;
            opacity: 0.9;
            margin-bottom: 12px;
        }
        .contact-grid {
            font-size: 9pt;
            margin-top: 10px;
        }
        .contact-grid span {
            display: inline-block;
            margin-right: 15px;
        }
        .section {
            margin-bottom: 18px;
        }
        .section h2 {
            font-size: 13px;
            color: ' . $primaryColor . ';
            text-transform: uppercase;
            letter-spacing: 1.5px;
            padding-bottom: 6px;
            border-bottom: 2px solid ' . $primaryColor . ';
            margin-bottom: 12px;
        }
        .item {
            margin-bottom: 12px;
            page-break-inside: avoid;
        }
        .item-header {
            background: #f7fafc;
            padding: 8px 12px;
            margin-bottom: 5px;
        }
        .item-title {
            font-size: 11.5pt;
            font-weight: 600;
            color: #1a202c;
            display: inline-block;
            width: 60%;
        }
        .item-date {
            font-size: 9pt;
            color: ' . $primaryColor . ';
            font-weight: 600;
            float: right;
            width: 40%;
            text-align: right;
        }
        .item-company {
            font-size: 10pt;
            color: #4a5568;
            font-weight: 500;
            padding: 0 12px;
            margin-bottom: 3px;
            clear: both;
        }
        .item-location {
            font-size: 9pt;
            color: #718096;
            padding: 0 12px;
            margin-bottom: 5px;
        }
        .item-description {
            font-size: 9.5pt;
            text-align: justify;
            padding: 0 12px;
            line-height: 1.5;
        }
        .summary {
            font-size: 10pt;
            line-height: 1.7;
            text-align: justify;
            padding: 12px;
            background: #edf2f7;
            border-left: 4px solid ' . $primaryColor . ';
        }
        .skill-category {
            font-weight: 600;
            color: ' . $primaryColor . ';
            padding: 6px 12px;
            background: #f7fafc;
            font-size: 10pt;
            margin-bottom: 4px;
        }
        .skill-list {
            padding: 4px 12px 8px 12px;
            font-size: 9.5pt;
        }
    </style>
</head>
<body>';

    $personalInfo = is_array($resume->personal_info) ? $resume->personal_info : [];
    $name = $personalInfo['name'] ?? '';

    $html .= '<div class="header">
        <h1>' . htmlspecialchars($name) . '</h1>
        <div class="title">Développeur Full Stack</div>
        <div class="contact-grid">';

    if (!empty($personalInfo['email'])) $html .= '<span>' . htmlspecialchars($personalInfo['email']) . '</span>';
    if (!empty($personalInfo['phone'])) $html .= '<span>' . htmlspecialchars($personalInfo['phone']) . '</span>';
    if (!empty($personalInfo['address'])) $html .= '<span>' . htmlspecialchars($personalInfo['address']) . '</span>';

    $html .= '</div></div>';

    if (!empty($resume->professional_summary)) {
        $html .= '<div class="section">
            <h2>Résumé Exécutif</h2>
            <div class="summary">' . nl2br(htmlspecialchars($resume->professional_summary)) . '</div>
        </div>';
    }

    if (!empty($resume->experiences) && is_array($resume->experiences)) {
        $html .= '<div class="section"><h2>Expérience Professionnelle</h2>';
        foreach ($resume->experiences as $exp) {
            if (!is_array($exp)) continue;

            $startDate = !empty($exp['start_date']) ? date('m/Y', strtotime($exp['start_date'])) : '';
            $endDate = !empty($exp['currently_working']) ? 'Présent' :
                      (!empty($exp['end_date']) ? date('m/Y', strtotime($exp['end_date'])) : '');

            $html .= '<div class="item">
                <div class="item-header">
                    <span class="item-title">' . htmlspecialchars($exp['title'] ?? '') . '</span>
                    <span class="item-date">' . $startDate . ' - ' . $endDate . '</span>
                </div>
                <div class="item-company">' . htmlspecialchars($exp['company'] ?? '') . '</div>';

            if (!empty($exp['location'])) {
                $html .= '<div class="item-location">' . htmlspecialchars($exp['location']) . '</div>';
            }

            if (!empty($exp['description'])) {
                $html .= '<div class="item-description">' . nl2br(htmlspecialchars($exp['description'])) . '</div>';
            }

            $html .= '</div>';
        }
        $html .= '</div>';
    }

    if (!empty($resume->education) && is_array($resume->education)) {
        $html .= '<div class="section"><h2>Formation</h2>';
        foreach ($resume->education as $edu) {
            if (!is_array($edu)) continue;

            $startDate = !empty($edu['start_date']) ? date('Y', strtotime($edu['start_date'])) : '';
            $endDate = !empty($edu['end_date']) ? date('Y', strtotime($edu['end_date'])) : '';

            $html .= '<div class="item">
                <div class="item-header">
                    <span class="item-title">' . htmlspecialchars($edu['degree'] ?? '') . '</span>
                    <span class="item-date">' . $startDate . ($endDate ? ' - ' . $endDate : '') . '</span>
                </div>
                <div class="item-company">' . htmlspecialchars($edu['institution'] ?? '') . '</div>
            </div>';
        }
        $html .= '</div>';
    }

    if (!empty($resume->skills) && is_array($resume->skills)) {
        $html .= '<div class="section"><h2>Compétences</h2>';

        $skillsByCategory = [];
        foreach ($resume->skills as $skill) {
            $skillName = is_array($skill) ? ($skill['name'] ?? '') : $skill;
            $category = is_array($skill) ? ($skill['category'] ?? 'Général') : 'Général';

            if (!empty($skillName)) {
                if (!isset($skillsByCategory[$category])) {
                    $skillsByCategory[$category] = [];
                }
                $skillsByCategory[$category][] = $skillName;
            }
        }

        foreach ($skillsByCategory as $category => $skills) {
            $html .= '<div class="skill-category">' . htmlspecialchars($category) . '</div>';
            $html .= '<div class="skill-list">' . implode(' • ', array_map('htmlspecialchars', $skills)) . '</div>';
        }

        $html .= '</div>';
    }

    $html .= '</body></html>';

    return $html;
}

// Template Minimalist
private function getMinimalistTemplate(Resume $resume): string
{
    $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>' . htmlspecialchars($resume->title) . '</title>
    <style>
        @page { margin: 20mm; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 10pt;
            line-height: 1.6;
            color: #000;
        }
        .name {
            font-size: 32px;
            font-weight: 300;
            margin-bottom: 5px;
            letter-spacing: 2px;
        }
        .contact {
            font-size: 9pt;
            color: #666;
            margin-bottom: 25px;
            padding-bottom: 10px;
            border-bottom: 1px solid #000;
        }
        .contact span {
            margin-right: 12px;
        }
        .section {
            margin-bottom: 20px;
        }
        .section h2 {
            font-size: 11pt;
            font-weight: 600;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .item {
            margin-bottom: 12px;
            padding-left: 0;
        }
        .item-title {
            font-size: 11pt;
            font-weight: 600;
            margin-bottom: 2px;
        }
        .item-meta {
            font-size: 9pt;
            color: #666;
            margin-bottom: 5px;
        }
        .item-description {
            font-size: 9.5pt;
            text-align: justify;
            line-height: 1.5;
        }
        .summary {
            font-size: 10pt;
            line-height: 1.7;
            text-align: justify;
            margin-bottom: 5px;
        }
        .skills {
            font-size: 9.5pt;
            line-height: 1.8;
        }
    </style>
</head>
<body>';

    $personalInfo = is_array($resume->personal_info) ? $resume->personal_info : [];
    $name = $personalInfo['name'] ?? '';

    $html .= '<div class="name">' . strtoupper(htmlspecialchars($name)) . '</div>';

    $html .= '<div class="contact">';
    if (!empty($personalInfo['email'])) $html .= '<span>' . htmlspecialchars($personalInfo['email']) . '</span>';
    if (!empty($personalInfo['phone'])) $html .= '<span>' . htmlspecialchars($personalInfo['phone']) . '</span>';
    if (!empty($personalInfo['address'])) $html .= '<span>' . htmlspecialchars($personalInfo['address']) . '</span>';
    $html .= '</div>';

    if (!empty($resume->professional_summary)) {
        $html .= '<div class="summary">' . nl2br(htmlspecialchars($resume->professional_summary)) . '</div>';
    }

    if (!empty($resume->experiences) && is_array($resume->experiences)) {
        $html .= '<div class="section"><h2>Expérience</h2>';
        foreach ($resume->experiences as $exp) {
            if (!is_array($exp)) continue;

            $startDate = !empty($exp['start_date']) ? date('m/Y', strtotime($exp['start_date'])) : '';
            $endDate = !empty($exp['currently_working']) ? 'Présent' :
                      (!empty($exp['end_date']) ? date('m/Y', strtotime($exp['end_date'])) : '');

            $html .= '<div class="item">
                <div class="item-title">' . htmlspecialchars($exp['title'] ?? '') . '</div>
                <div class="item-meta">' . htmlspecialchars($exp['company'] ?? '') . ' | ' . $startDate . ' - ' . $endDate . '</div>';

            if (!empty($exp['description'])) {
                $html .= '<div class="item-description">' . nl2br(htmlspecialchars($exp['description'])) . '</div>';
            }

            $html .= '</div>';
        }
        $html .= '</div>';
    }

    if (!empty($resume->education) && is_array($resume->education)) {
        $html .= '<div class="section"><h2>Formation</h2>';
        foreach ($resume->education as $edu) {
            if (!is_array($edu)) continue;

            $startDate = !empty($edu['start_date']) ? date('Y', strtotime($edu['start_date'])) : '';
            $endDate = !empty($edu['end_date']) ? date('Y', strtotime($edu['end_date'])) : '';

            $html .= '<div class="item">
                <div class="item-title">' . htmlspecialchars($edu['degree'] ?? '') . '</div>
                <div class="item-meta">' . htmlspecialchars($edu['institution'] ?? '') . ' | ' . $startDate . ($endDate ? ' - ' . $endDate : '') . '</div>
            </div>';
        }
        $html .= '</div>';
    }

    if (!empty($resume->skills) && is_array($resume->skills)) {
        $html .= '<div class="section"><h2>Compétences</h2><div class="skills">';
        $skillNames = [];
        foreach ($resume->skills as $skill) {
            $skillName = is_array($skill) ? ($skill['name'] ?? '') : $skill;
            if (!empty($skillName)) {
                $skillNames[] = $skillName;
            }
        }
        $html .= implode(' • ', array_map('htmlspecialchars', $skillNames));
        $html .= '</div></div>';
    }

    $html .= '</body></html>';

    return $html;
}
