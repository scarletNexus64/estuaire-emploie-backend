<?php

namespace App\Services;

use App\Models\Resume;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class CVGeneratorService
{
    /**
     * Génère un CV au format PDF pour un étudiant
     *
     * @param User $student
     * @param array $data
     * @param UploadedFile|null $photo
     * @return Resume
     */
    public function generateStudentCV(User $student, array $data, ?UploadedFile $photo = null): Resume
    {
        // 1. Gérer la photo si fournie
        $photoPath = null;
        if ($photo) {
            $photoPath = $photo->store('resumes/photos', 'public');
        }

        // 2. Préparer les données pour le template
        $cvData = $this->prepareCVData($student, $data, $photoPath);

        // 3. Générer le PDF
        $pdfPath = $this->generatePDF($student, $cvData);

        // 4. Créer l'enregistrement Resume dans la base
        $resume = Resume::create([
            'user_id' => $student->id,
            'title' => $data['title'] ?? 'Mon CV',
            'template_type' => 'aide-soignante',
            'personal_info' => [
                'name' => $student->name,
                'email' => $data['email'] ?? $student->email,
                'phone' => $data['phone'] ?? $student->phone,
                'address' => $data['address'] ?? null,
                'photo_path' => $photoPath,
            ],
            'professional_summary' => $data['objective'] ?? null,
            'experiences' => $data['experiences'] ?? [],
            'education' => $data['education'] ?? [],
            'skills' => $this->parseMultilineToArray($data['skills'] ?? ''),
            'hobbies' => $this->parseMultilineToArray($data['hobbies'] ?? ''),
            'pdf_path' => $pdfPath,
            'pdf_generated_at' => now(),
            'is_default' => true,
            'is_public' => false,
        ]);

        return $resume;
    }

    /**
     * Prépare les données pour le template PDF
     *
     * @param User $student
     * @param array $data
     * @param string|null $photoPath
     * @return array
     */
    protected function prepareCVData(User $student, array $data, ?string $photoPath): array
    {
        // Convertir le chemin de la photo en chemin absolu pour DomPDF
        $photoFullPath = null;
        if ($photoPath) {
            $photoFullPath = storage_path('app/public/' . $photoPath);
        }

        return [
            'name' => $student->name,
            'title' => $data['title'] ?? 'AIDE-SOIGNANTE',
            'phone' => $data['phone'] ?? $student->phone,
            'email' => $data['email'] ?? $student->email,
            'address' => $data['address'] ?? null,
            'photo_path' => $photoFullPath,
            'objective' => $data['objective'] ?? null,
            'experiences' => $this->formatExperiences($data['experiences'] ?? []),
            'education' => $this->formatEducation($data['education'] ?? []),
            'skills' => $this->parseMultilineToArray($data['skills'] ?? ''),
            'hobbies' => $this->parseMultilineToArray($data['hobbies'] ?? ''),
        ];
    }

    /**
     * Formate les expériences pour le template
     *
     * @param array $experiences
     * @return array
     */
    protected function formatExperiences(array $experiences): array
    {
        $formatted = [];

        foreach ($experiences as $exp) {
            if (empty($exp['company']) && empty($exp['title'])) {
                continue;
            }

            $formatted[] = [
                'date' => $exp['date'] ?? '',
                'company' => $exp['company'] ?? '',
                'title' => $exp['title'] ?? '',
                'description' => $this->parseMultilineToArray($exp['description'] ?? ''),
            ];
        }

        return $formatted;
    }

    /**
     * Formate les formations pour le template
     *
     * @param array $education
     * @return array
     */
    protected function formatEducation(array $education): array
    {
        $formatted = [];

        foreach ($education as $edu) {
            if (empty($edu['school']) && empty($edu['degree'])) {
                continue;
            }

            $formatted[] = [
                'school' => $edu['school'] ?? '',
                'degree' => $edu['degree'] ?? '',
            ];
        }

        return $formatted;
    }

    /**
     * Parse un texte multiligne en tableau
     *
     * @param string $text
     * @return array
     */
    protected function parseMultilineToArray(string $text): array
    {
        return array_filter(
            array_map('trim', explode("\n", $text)),
            fn($line) => !empty($line)
        );
    }

    /**
     * Génère le fichier PDF
     *
     * @param User $student
     * @param array $cvData
     * @return string Le chemin relatif du PDF dans storage
     */
    protected function generatePDF(User $student, array $cvData): string
    {
        try {
            // Générer le HTML à partir du template Blade
            $pdf = Pdf::loadView('pdf.cv_aide_soignante', ['data' => $cvData]);

            // Configuration du PDF
            $pdf->setPaper('a4', 'portrait');
            $pdf->setOption('isHtml5ParserEnabled', true);
            $pdf->setOption('isRemoteEnabled', true);
            $pdf->setOption('defaultFont', 'DejaVu Sans');
            $pdf->setOption('dpi', 96);
            $pdf->setOption('enable_php', false);
            $pdf->setOption('enable_javascript', false);
            $pdf->setOption('enable_remote', true);

            // Définir le nom du fichier
            $fileName = 'cv_' . $student->id . '_' . time() . '.pdf';
            $relativePath = 'resumes/pdfs/' . $fileName;

            // Sauvegarder le PDF
            $pdfContent = $pdf->output();
            Storage::disk('public')->put($relativePath, $pdfContent);

            return $relativePath;
        } catch (\Exception $e) {
            \Log::error('Erreur génération PDF CV', [
                'student_id' => $student->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    /**
     * Met à jour un CV existant
     *
     * @param Resume $resume
     * @param array $data
     * @param UploadedFile|null $photo
     * @return Resume
     */
    public function updateStudentCV(Resume $resume, array $data, ?UploadedFile $photo = null): Resume
    {
        // Gérer la photo
        $photoPath = $resume->personal_info['photo_path'] ?? null;
        if ($photo) {
            // Supprimer l'ancienne photo si elle existe
            if ($photoPath) {
                Storage::disk('public')->delete($photoPath);
            }
            $photoPath = $photo->store('resumes/photos', 'public');
        }

        // Préparer les données
        $cvData = $this->prepareCVData($resume->user, $data, $photoPath);

        // Régénérer le PDF
        $pdfPath = $this->generatePDF($resume->user, $cvData);

        // Supprimer l'ancien PDF
        if ($resume->pdf_path) {
            Storage::disk('public')->delete($resume->pdf_path);
        }

        // Mettre à jour l'enregistrement
        $resume->update([
            'title' => $data['title'] ?? $resume->title,
            'personal_info' => [
                'name' => $resume->user->name,
                'email' => $data['email'] ?? $resume->user->email,
                'phone' => $data['phone'] ?? $resume->user->phone,
                'address' => $data['address'] ?? null,
                'photo_path' => $photoPath,
            ],
            'professional_summary' => $data['objective'] ?? null,
            'experiences' => $data['experiences'] ?? [],
            'education' => $data['education'] ?? [],
            'skills' => $this->parseMultilineToArray($data['skills'] ?? ''),
            'hobbies' => $this->parseMultilineToArray($data['hobbies'] ?? ''),
            'pdf_path' => $pdfPath,
            'pdf_generated_at' => now(),
        ]);

        return $resume->fresh();
    }

    /**
     * Supprime un CV (soft delete)
     *
     * @param Resume $resume
     * @return bool
     */
    public function deleteCV(Resume $resume): bool
    {
        // Supprimer les fichiers physiques
        if ($resume->pdf_path) {
            Storage::disk('public')->delete($resume->pdf_path);
        }

        $photoPath = $resume->personal_info['photo_path'] ?? null;
        if ($photoPath) {
            Storage::disk('public')->delete($photoPath);
        }

        return $resume->delete();
    }
}
