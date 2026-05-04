<?php

namespace Database\Seeders;

use App\Models\Resume;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;

class CVLibrarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $files = [
            base_path('CVs/CVs_IUEs_INSAM_Final.xlsx'),
            base_path('CVs/CV_IUEs_INSAM_Complet (1).xlsx')
        ];

        $totalImported = 0;

        foreach ($files as $file) {
            if (!file_exists($file)) {
                $this->command->error("Fichier introuvable : $file");
                continue;
            }

            $this->command->info("Importation depuis : " . basename($file));

            $spreadsheet = IOFactory::load($file);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray(null, true, true, true);

            // Skip header
            unset($rows[1]);

            foreach ($rows as $rowIndex => $row) {
                // A=Niveau, B=Spécialité, C=Nom, D=Prénom, E=Email, F=profession, G=Profil, H=Experiences, I=Formations, J=competences, K=langues, L=centres interet, M=Savoir-etre, N=Projets academiques, O=Certifications
                $email = trim($row['E'] ?? '');
                if (empty($email)) {
                    continue;
                }

                $name = trim(($row['C'] ?? '') . ' ' . ($row['D'] ?? ''));
                if (empty($name)) {
                    $name = $email;
                }

                // Trouver ou créer l'utilisateur
                $user = User::where('email', $email)->first();
                if (!$user) {
                    $user = User::create([
                        'name' => $name,
                        'email' => $email,
                        'password' => bcrypt('password'),
                        'role' => 'candidate',
                        'level' => $row['A'] ?? null,
                        'specialty' => $row['B'] ?? null,
                        'is_active' => true,
                    ]);
                }

                // Créer le CV s'il n'existe pas déjà pour cet utilisateur avec cette source
                $resumeExists = Resume::where('user_id', $user->id)
                    ->where('customization->source', 'INSAM_IMPORT')
                    ->where('title', $row['F'] ?? 'Mon CV')
                    ->exists();

                if (!$resumeExists) {
                    Resume::create([
                        'user_id' => $user->id,
                        'title' => $row['F'] ?? 'Mon CV',
                        'template_type' => 'modern',
                        'professional_summary' => $row['G'] ?? null,
                        'personal_info' => [
                            'name' => $name,
                            'email' => $email,
                            'languages' => $row['K'] ?? null,
                        ],
                        'experiences' => $this->formatToStructuredArray($row['H'] ?? null, 'description'),
                        'education' => $this->formatToStructuredArray($row['I'] ?? null, 'degree'),
                        'skills' => $this->formatToArray($row['J'] ?? null),
                        'hobbies' => $this->formatToArray($row['L'] ?? null),
                        'projects' => $this->formatToArray($row['N'] ?? null),
                        'certifications' => $this->formatToArray($row['O'] ?? null),
                        'customization' => [
                            'source' => 'INSAM_IMPORT',
                            'import_file' => basename($file),
                            'level' => $row['A'] ?? null,
                            'specialty' => $row['B'] ?? null,
                            'soft_skills' => $this->formatToArray($row['M'] ?? null),
                        ],
                        'is_public' => true,
                        'is_default' => !Resume::where('user_id', $user->id)->exists(),
                    ]);
                    $totalImported++;
                }
            }
        }

        $this->command->info("Importation terminée. $totalImported CVs importés.");
    }

    private function formatToArray($text): array
    {
        if (empty($text)) {
            return [];
        }

        $lines = preg_split('/\r\n|\r|\n|•|-/', $text);
        $filtered = array_filter(array_map('trim', $lines));

        return array_values($filtered);
    }

    private function formatToStructuredArray($text, $key): array
    {
        $items = $this->formatToArray($text);
        $structured = [];

        foreach ($items as $item) {
            $structured[] = [$key => $item];
        }

        return $structured;
    }
}
