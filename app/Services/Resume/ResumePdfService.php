<?php

namespace App\Services\Resume;

use App\Models\Resume;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class ResumePdfService
{
    public function generatePdf(Resume $resume): string
    {
        // Préparer les données pour le template Blade
        $cvData = $this->prepareCVData($resume);

        // Générer le PDF à partir du template Blade
        $pdf = Pdf::loadView('pdf.cv_aide_soignante', ['data' => $cvData])
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'DejaVu Sans',
                'dpi' => 96,
                'enable_php' => false,
                'enable_javascript' => false,
                'enable_remote' => true,
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

    /**
     * Prépare les données du Resume pour le template cv_aide_soignante.blade.php
     */
    protected function prepareCVData(Resume $resume): array
    {
        // IMPORTANT: Récupérer les données brutes sans passer par l'accessor
        // car l'accessor transforme le chemin en URL complète
        $personalInfo = json_decode($resume->getAttributes()['personal_info'] ?? '[]', true);

        // Gérer la photo si elle existe - encoder en base64 pour DomPDF
        $photoBase64 = null;
        if (!empty($personalInfo['photo'])) {
            // Vérifier si c'est une URL complète (générée par l'accessor) ou un chemin relatif
            $photoPath = $personalInfo['photo'];

            // Si c'est une URL, extraire le chemin relatif
            if (filter_var($photoPath, FILTER_VALIDATE_URL)) {
                // Extraire le chemin après 'storage/'
                $photoPath = str_replace(url('storage/'), '', $photoPath);
                \Log::info('Photo Resume - URL détectée, extraction du chemin: ' . $photoPath);
            }

            $photoFullPath = storage_path('app/public/' . $photoPath);

            \Log::info('Photo Resume - Chemin relatif: ' . $photoPath);
            \Log::info('Photo Resume - Chemin absolu: ' . $photoFullPath);
            \Log::info('Photo Resume - Fichier existe: ' . (file_exists($photoFullPath) ? 'OUI' : 'NON'));

            if (file_exists($photoFullPath)) {
                // Lire le fichier et l'encoder en base64
                $imageData = file_get_contents($photoFullPath);
                $mimeType = mime_content_type($photoFullPath);
                $photoBase64 = 'data:' . $mimeType . ';base64,' . base64_encode($imageData);
                \Log::info('Photo Resume - Image encodée en base64 (longueur: ' . strlen($photoBase64) . ' caractères)');
            } else {
                \Log::warning('Photo Resume - Fichier non trouvé: ' . $photoFullPath);
            }
        } else {
            \Log::info('Photo Resume - Aucune photo dans personal_info, utilisation du logo par défaut');
        }

        // Si aucune photo n'a été encodée, utiliser le logo Estuaire Emploi par défaut
        if ($photoBase64 === null) {
            $defaultLogoPath = public_path('images/logo-estuaire-emploi.png');

            \Log::info('Photo Resume - Tentative de chargement du logo par défaut: ' . $defaultLogoPath);

            if (file_exists($defaultLogoPath)) {
                $imageData = file_get_contents($defaultLogoPath);
                $mimeType = mime_content_type($defaultLogoPath);
                $photoBase64 = 'data:' . $mimeType . ';base64,' . base64_encode($imageData);
                \Log::info('Photo Resume - Logo par défaut utilisé (longueur: ' . strlen($photoBase64) . ' caractères)');
            } else {
                \Log::warning('Photo Resume - Logo par défaut non trouvé: ' . $defaultLogoPath);
            }
        }

        return [
            'name' => $personalInfo['name'] ?? '',
            'title' => $resume->title ?? '',
            'phone' => $personalInfo['phone'] ?? '',
            'email' => $personalInfo['email'] ?? '',
            'address' => $personalInfo['address'] ?? null,
            'languages' => $personalInfo['languages'] ?? null,
            'level' => $resume->customization['level'] ?? null,
            'specialty' => $resume->customization['specialty'] ?? null,
            'photo_path' => $photoBase64,
            'objective' => $resume->professional_summary ?? null,
            'experiences' => $this->formatExperiences($resume->experiences ?? []),
            'education' => $this->formatEducation($resume->education ?? []),
            'skills' => $this->formatSkills($resume->skills ?? []),
            'hobbies' => $this->formatHobbies($resume->hobbies ?? []),
            'soft_skills' => $this->formatSkills($resume->customization['soft_skills'] ?? []),
            'projects' => $this->formatSkills($resume->projects ?? []),
            'certifications' => $this->formatSkills($resume->certifications ?? []),
        ];
    }

    /**
     * Formate les expériences pour le template
     */
    protected function formatExperiences(array $experiences): array
    {
        $formatted = [];

        foreach ($experiences as $exp) {
            if (!is_array($exp)) {
                continue;
            }

            $hasDescription = !empty($exp['description']);
            $hasMainFields = !empty($exp['company']) || !empty($exp['title']) || !empty($exp['date']) || !empty($exp['start_date']);
            if (!$hasDescription && !$hasMainFields) {
                continue;
            }

            // Formater la date
            $date = '';
            if (!empty($exp['start_date'])) {
                $startDate = date('m/Y', strtotime($exp['start_date']));
                $endDate = !empty($exp['currently_working'])
                    ? 'Présent'
                    : (!empty($exp['end_date']) ? date('m/Y', strtotime($exp['end_date'])) : '');
                $date = $startDate . ($endDate ? ' - ' . $endDate : '');
            }

            // Convertir la description en array si c'est une string
            $description = [];
            if (!empty($exp['description'])) {
                if (is_string($exp['description'])) {
                    // Séparer par lignes
                    $description = array_filter(
                        array_map('trim', explode("\n", $exp['description'])),
                        fn($line) => !empty($line)
                    );
                } elseif (is_array($exp['description'])) {
                    $description = $exp['description'];
                }
            }

            $formatted[] = [
                'date' => $date ?: ($exp['date'] ?? ''),
                'company' => $exp['company'] ?? '',
                'title' => $exp['title'] ?? '',
                'description' => $description,
            ];
        }

        return $formatted;
    }

    /**
     * Formate les formations pour le template
     */
    protected function formatEducation(array $education): array
    {
        $formatted = [];

        foreach ($education as $edu) {
            if (!is_array($edu) || (empty($edu['institution']) && empty($edu['degree']))) {
                continue;
            }

            $formatted[] = [
                'school' => $edu['institution'] ?? '',
                'degree' => $edu['degree'] ?? '',
            ];
        }

        return $formatted;
    }

    /**
     * Formate les compétences pour le template (array de strings)
     */
    protected function formatSkills(array $skills): array
    {
        $formatted = [];

        foreach ($skills as $skill) {
            if (is_array($skill)) {
                // Si c'est un objet avec 'name'
                if (!empty($skill['name'])) {
                    $formatted[] = $skill['name'];
                }
            } elseif (is_string($skill) && !empty($skill)) {
                // Si c'est déjà une string
                $formatted[] = $skill;
            }
        }

        return $formatted;
    }

    /**
     * Formate les hobbies pour le template (array de strings)
     */
    protected function formatHobbies(array $hobbies): array
    {
        $formatted = [];

        foreach ($hobbies as $hobby) {
            if (is_string($hobby) && !empty($hobby)) {
                $formatted[] = $hobby;
            }
        }

        return $formatted;
    }
}
