<?php

namespace App\Services\Resume;

use App\Models\Resume;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class ResumePdfService
{
    public function generatePdf(Resume $resume): string
    {
        $html = $this->getTemplateHtml($resume);

        $pdf = Pdf::loadHTML($html)
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => false,
                'defaultFont' => 'DejaVu Sans',
                'dpi' => 150,
            ]);

        $fileName = 'resumes/' . $resume->user_id . '/' . uniqid() . '_' . $resume->id . '.pdf';
        Storage::disk('public')->put($fileName, $pdf->output());

        if ($resume->pdf_path && Storage::disk('public')->exists($resume->pdf_path)) {
            Storage::disk('public')->delete($resume->pdf_path);
        }

        $resume->update([
            'pdf_path' => $fileName,
            'pdf_generated_at' => now(),
        ]);

        return $fileName;
    }

    private function getTemplateHtml(Resume $resume): string
    {
        return $this->getTemplate($resume);
    }

    private function getTemplate(Resume $resume): string
    {
        $primaryColor = '#8B5CF6';
        $accentColor = '#EC4899';

        $personalInfo = is_array($resume->personal_info) ? $resume->personal_info : [];
        $name = $personalInfo['name'] ?? '';

        $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 0; }
        * { margin: 0; padding: 0; }
        body { font-family: "DejaVu Sans", sans-serif; font-size: 10pt; }
        table { width: 100%; border-collapse: collapse; }
        .accent-bar { width: 8mm; background: linear-gradient(180deg, ' . $primaryColor . ' 0%, ' . $accentColor . ' 100%); }
        .content-cell { width: 202mm; vertical-align: top; }
        .header { background: ' . $primaryColor . '; color: white; padding: 25px 30px; }
        .header h1 { font-size: 32px; margin-bottom: 8px; }
        .header .tagline { font-size: 13pt; opacity: 0.9; }
        .contact { background: #f8f9fa; padding: 12px 30px; font-size: 9pt; }
        .contact span { margin-right: 15px; color: #4a5568; }
        .contact span:before { content: "● "; color: ' . $primaryColor . '; font-weight: bold; }
        .main { padding: 20px 30px; }
        .section { margin-bottom: 20px; }
        .section h2 { font-size: 16px; color: ' . $primaryColor . '; margin-bottom: 12px; padding-bottom: 6px; 
                      border-bottom: 3px solid ' . $accentColor . '; text-transform: uppercase; font-weight: 700; }
        .item { margin-bottom: 15px; }
        .item-title { font-size: 13px; font-weight: bold; color: ' . $primaryColor . '; margin-bottom: 3px; }
        .item-meta { font-size: 9pt; color: #64748b; margin-bottom: 8px; }
        .item-meta .company { font-weight: 600; color: #334155; }
        .item-meta .date { color: ' . $accentColor . '; font-weight: 600; }
        .item-description { font-size: 9.5pt; line-height: 1.6; color: #334155; }
        .summary { font-size: 10pt; line-height: 1.7; padding: 15px; background: #faf5ff; border-radius: 4px; }
        .skill-tag { display: inline-block; background: ' . $primaryColor . '; color: white; padding: 6px 14px; 
                     margin: 4px 6px 4px 0; font-size: 9pt; border-radius: 3px; }
    </style>
</head>
<body>
    <table>
        <tr>
            <td class="accent-bar"></td>
            <td class="content-cell">
                <div class="header">
                    <h1>' . htmlspecialchars($name) . '</h1>
                    <div class="tagline">' . htmlspecialchars($resume->title ?? '') . '</div>
                </div>
                <div class="contact">';
        
        if (!empty($personalInfo['email'])) $html .= '<span>' . htmlspecialchars($personalInfo['email']) . '</span>';
        if (!empty($personalInfo['phone'])) $html .= '<span>' . htmlspecialchars($personalInfo['phone']) . '</span>';
        if (!empty($personalInfo['address'])) $html .= '<span>' . htmlspecialchars($personalInfo['address']) . '</span>';
        
        $html .= '</div><div class="main">';

        if (!empty($resume->professional_summary)) {
            $html .= '<div class="section"><h2>À Propos</h2><div class="summary">' . nl2br(htmlspecialchars($resume->professional_summary)) . '</div></div>';
        }

        if (!empty($resume->experiences) && is_array($resume->experiences)) {
            $html .= '<div class="section"><h2>Expériences</h2>';
            foreach ($resume->experiences as $exp) {
                if (!is_array($exp)) continue;
                $startDate = !empty($exp['start_date']) ? date('m/Y', strtotime($exp['start_date'])) : '';
                $endDate = !empty($exp['currently_working']) ? 'Présent' : (!empty($exp['end_date']) ? date('m/Y', strtotime($exp['end_date'])) : '');
                $html .= '<div class="item"><div class="item-title">' . htmlspecialchars($exp['title'] ?? '') . '</div>';
                $html .= '<div class="item-meta"><span class="company">' . htmlspecialchars($exp['company'] ?? '') . '</span>';
                if (!empty($exp['location'])) $html .= ' • ' . htmlspecialchars($exp['location']);
                $html .= ' • <span class="date">' . $startDate . ' - ' . $endDate . '</span></div>';
                if (!empty($exp['description'])) $html .= '<div class="item-description">' . nl2br(htmlspecialchars($exp['description'])) . '</div>';
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
                $html .= '<div class="item"><div class="item-title">' . htmlspecialchars($edu['degree'] ?? '') . '</div>';
                $html .= '<div class="item-meta"><span class="company">' . htmlspecialchars($edu['institution'] ?? '') . '</span>';
                if (!empty($edu['location'])) $html .= ' • ' . htmlspecialchars($edu['location']);
                $html .= ' • <span class="date">' . $startDate . ($endDate ? ' - ' . $endDate : '') . '</span></div></div>';
            }
            $html .= '</div>';
        }

        if (!empty($resume->skills) && is_array($resume->skills)) {
            $html .= '<div class="section"><h2>Compétences</h2><div>';
            foreach ($resume->skills as $skill) {
                $skillName = is_array($skill) ? ($skill['name'] ?? '') : $skill;
                if (!empty($skillName)) $html .= '<span class="skill-tag">' . htmlspecialchars($skillName) . '</span>';
            }
            $html .= '</div></div>';
        }

        $html .= '</div></td></tr></table></body></html>';

        return $html;
    }
}
