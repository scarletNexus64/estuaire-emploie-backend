<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Company;
use App\Models\ContractType;
use App\Models\Job;
use App\Models\Location;
use App\Models\QuickService;
use App\Models\Resume;
use App\Models\ServiceCategory;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ImportExportController extends Controller
{
    /**
     * Export template Jobs avec sélection de colonnes
     */
    public function exportJobsTemplate(Request $request): StreamedResponse
    {
        $request->validate([
            'columns' => 'required|array|min:1',
            'columns.*' => 'string|in:title,description,requirements,benefits,salary_min,salary_max,salary_negotiable,experience_level,status,application_deadline,company_name,category_name,location_name,contract_type_name',
        ]);

        $columns = $request->input('columns');

        return $this->generateTemplate('jobs', $columns, $this->getJobsColumnHeaders());
    }

    /**
     * Import Jobs depuis CSV/Excel
     */
    public function importJobs(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls|max:10240', // 10MB max
        ]);

        try {
            $file = $request->file('file');
            $spreadsheet = IOFactory::load($file->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            if (empty($rows)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Le fichier est vide',
                ], 400);
            }

            // Première ligne = headers
            $headers = array_shift($rows);
            $headerMap = $this->mapHeaders($headers, $this->getJobsColumnHeaders());

            $results = [
                'total' => count($rows),
                'imported' => 0,
                'failed' => 0,
                'errors' => [],
            ];

            foreach ($rows as $index => $row) {
                $rowNumber = $index + 2; // +2 car index commence à 0 et on a enlevé la ligne de header

                try {
                    $data = $this->mapRowToData($row, $headerMap);
                    $this->importJobRow($data);
                    $results['imported']++;
                } catch (\Exception $e) {
                    $results['failed']++;
                    $results['errors'][] = [
                        'row' => $rowNumber,
                        'error' => $e->getMessage(),
                        'data' => $this->getSafeRowPreview($row, $headerMap),
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Import terminé: {$results['imported']} importés, {$results['failed']} échecs",
                'results' => $results,
            ]);

        } catch (\Exception $e) {
            Log::error('Jobs import error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'import: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Export template Resumes (CVs) avec sélection de colonnes
     */
    public function exportResumesTemplate(Request $request): StreamedResponse
    {
        $request->validate([
            'columns' => 'required|array|min:1',
            'columns.*' => 'string|in:title,template_type,professional_summary,name,email,phone,address,linkedin,website,skills,languages,is_public',
        ]);

        $columns = $request->input('columns');

        return $this->generateTemplate('resumes', $columns, $this->getResumesColumnHeaders());
    }

    /**
     * Import Resumes (CVs) depuis CSV/Excel
     */
    public function importResumes(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls|max:10240',
        ]);

        try {
            $file = $request->file('file');
            $spreadsheet = IOFactory::load($file->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            if (empty($rows)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Le fichier est vide',
                ], 400);
            }

            $headers = array_shift($rows);
            $headerMap = $this->mapHeaders($headers, $this->getResumesColumnHeaders());

            $results = [
                'total' => count($rows),
                'imported' => 0,
                'failed' => 0,
                'errors' => [],
            ];

            foreach ($rows as $index => $row) {
                $rowNumber = $index + 2;

                try {
                    $data = $this->mapRowToData($row, $headerMap);
                    $this->importResumeRow($data);
                    $results['imported']++;
                } catch (\Exception $e) {
                    $results['failed']++;
                    $results['errors'][] = [
                        'row' => $rowNumber,
                        'error' => $e->getMessage(),
                        'data' => $this->getSafeRowPreview($row, $headerMap),
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Import terminé: {$results['imported']} importés, {$results['failed']} échecs",
                'results' => $results,
            ]);

        } catch (\Exception $e) {
            Log::error('Resumes import error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'import: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Export template Quick Services avec sélection de colonnes
     */
    public function exportQuickServicesTemplate(Request $request): StreamedResponse
    {
        $request->validate([
            'columns' => 'required|array|min:1',
            'columns.*' => 'string|in:title,description,price_type,price_min,price_max,location_name,latitude,longitude,urgency,desired_date,estimated_duration,status,user_email,category_name',
        ]);

        $columns = $request->input('columns');

        return $this->generateTemplate('quick_services', $columns, $this->getQuickServicesColumnHeaders());
    }

    /**
     * Import Quick Services depuis CSV/Excel
     */
    public function importQuickServices(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls|max:10240',
        ]);

        try {
            $file = $request->file('file');
            $spreadsheet = IOFactory::load($file->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            if (empty($rows)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Le fichier est vide',
                ], 400);
            }

            $headers = array_shift($rows);
            $headerMap = $this->mapHeaders($headers, $this->getQuickServicesColumnHeaders());

            $results = [
                'total' => count($rows),
                'imported' => 0,
                'failed' => 0,
                'errors' => [],
            ];

            foreach ($rows as $index => $row) {
                $rowNumber = $index + 2;

                try {
                    $data = $this->mapRowToData($row, $headerMap);
                    $this->importQuickServiceRow($data);
                    $results['imported']++;
                } catch (\Exception $e) {
                    $results['failed']++;
                    $results['errors'][] = [
                        'row' => $rowNumber,
                        'error' => $e->getMessage(),
                        'data' => $this->getSafeRowPreview($row, $headerMap),
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Import terminé: {$results['imported']} importés, {$results['failed']} échecs",
                'results' => $results,
            ]);

        } catch (\Exception $e) {
            Log::error('Quick Services import error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'import: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Génère un template Excel vide avec les colonnes sélectionnées
     */
    private function generateTemplate(string $type, array $columns, array $allHeaders): StreamedResponse
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Ajouter les headers des colonnes sélectionnées
        $col = 1;
        foreach ($columns as $columnKey) {
            if (isset($allHeaders[$columnKey])) {
                $sheet->setCellValueByColumnAndRow($col, 1, $allHeaders[$columnKey]);
                $col++;
            }
        }

        // Styliser les headers
        $sheet->getStyle('1:1')->getFont()->setBold(true);
        $sheet->getStyle('1:1')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFE0E0E0');

        // Auto-size colonnes
        foreach (range(1, count($columns)) as $columnID) {
            $sheet->getColumnDimensionByColumn($columnID)->setAutoSize(true);
        }

        $filename = $type . '_template_' . date('Y-m-d_His') . '.xlsx';

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    /**
     * Mappe les headers du fichier avec les colonnes attendues
     */
    private function mapHeaders(array $fileHeaders, array $expectedHeaders): array
    {
        $map = [];

        foreach ($fileHeaders as $index => $header) {
            $header = trim($header);

            // Recherche exacte
            $key = array_search($header, $expectedHeaders, true);

            if ($key !== false) {
                $map[$key] = $index;
            }
        }

        return $map;
    }

    /**
     * Mappe une ligne de données avec les colonnes
     */
    private function mapRowToData(array $row, array $headerMap): array
    {
        $data = [];

        foreach ($headerMap as $key => $index) {
            $data[$key] = $row[$index] ?? null;
        }

        return $data;
    }

    /**
     * Obtient un aperçu sécurisé de la ligne pour le rapport d'erreur
     */
    private function getSafeRowPreview(array $row, array $headerMap): array
    {
        $preview = [];
        $count = 0;

        foreach ($headerMap as $key => $index) {
            if ($count >= 5) break; // Limite à 5 colonnes
            $preview[$key] = isset($row[$index]) ? substr($row[$index], 0, 50) : null;
            $count++;
        }

        return $preview;
    }

    /**
     * Importe une ligne de Job
     */
    private function importJobRow(array $data): void
    {
        $validator = Validator::make($data, [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'company_name' => 'required|string',
            'category_name' => 'nullable|string',
            'location_name' => 'nullable|string',
            'contract_type_name' => 'nullable|string',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0',
            'experience_level' => 'nullable|string|in:entry,junior,mid,senior,expert',
            'status' => 'nullable|string|in:draft,pending,published,closed',
        ]);

        if ($validator->fails()) {
            throw new \Exception('Validation échouée: ' . implode(', ', $validator->errors()->all()));
        }

        DB::beginTransaction();
        try {
            // Trouver ou créer la company
            $company = Company::firstOrCreate(
                ['name' => $data['company_name']],
                [
                    'email' => 'import@example.com',
                    'status' => 'active',
                ]
            );

            // Trouver ou créer category, location, contract_type
            $categoryId = null;
            if (!empty($data['category_name'])) {
                $category = Category::firstOrCreate(['name' => $data['category_name']]);
                $categoryId = $category->id;
            }

            $locationId = null;
            if (!empty($data['location_name'])) {
                $location = Location::firstOrCreate(['name' => $data['location_name']]);
                $locationId = $location->id;
            }

            $contractTypeId = null;
            if (!empty($data['contract_type_name'])) {
                $contractType = ContractType::firstOrCreate(['name' => $data['contract_type_name']]);
                $contractTypeId = $contractType->id;
            }

            // Créer le job
            Job::create([
                'company_id' => $company->id,
                'category_id' => $categoryId,
                'location_id' => $locationId,
                'contract_type_id' => $contractTypeId,
                'posted_by' => auth()->id() ?? 1, // User admin par défaut
                'title' => $data['title'],
                'description' => $data['description'] ?? '',
                'requirements' => $data['requirements'] ?? '',
                'benefits' => $data['benefits'] ?? '',
                'salary_min' => $data['salary_min'] ?? null,
                'salary_max' => $data['salary_max'] ?? null,
                'salary_negotiable' => $data['salary_negotiable'] ?? false,
                'experience_level' => $data['experience_level'] ?? 'entry',
                'status' => $data['status'] ?? 'pending',
                'application_deadline' => !empty($data['application_deadline']) ? $data['application_deadline'] : null,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Importe une ligne de Resume
     */
    private function importResumeRow(array $data): void
    {
        $validator = Validator::make($data, [
            'title' => 'required|string|max:255',
            'email' => 'required|email',
            'name' => 'required|string|max:255',
            'template_type' => 'nullable|string|in:modern,classic,creative,professional,minimalist',
        ]);

        if ($validator->fails()) {
            throw new \Exception('Validation échouée: ' . implode(', ', $validator->errors()->all()));
        }

        DB::beginTransaction();
        try {
            // Trouver ou créer l'utilisateur
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => bcrypt('password'), // Mot de passe temporaire
                    'role' => 'candidate',
                ]
            );

            // Préparer les informations personnelles
            $personalInfo = [
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'address' => $data['address'] ?? null,
                'linkedin' => $data['linkedin'] ?? null,
                'website' => $data['website'] ?? null,
            ];

            // Préparer les compétences
            $skills = [];
            if (!empty($data['skills'])) {
                $skillsList = explode(',', $data['skills']);
                foreach ($skillsList as $skill) {
                    $skills[] = ['name' => trim($skill), 'level' => 'intermediate'];
                }
            }

            // Préparer les langues
            $languages = [];
            if (!empty($data['languages'])) {
                $languagesList = explode(',', $data['languages']);
                foreach ($languagesList as $language) {
                    $languages[] = ['name' => trim($language), 'level' => 'intermediate'];
                }
            }

            // Créer le CV
            Resume::create([
                'user_id' => $user->id,
                'title' => $data['title'],
                'template_type' => $data['template_type'] ?? 'modern',
                'professional_summary' => $data['professional_summary'] ?? '',
                'personal_info' => $personalInfo,
                'skills' => $skills,
                'education' => [], // À remplir manuellement
                'experiences' => [], // À remplir manuellement
                'certifications' => [],
                'projects' => [],
                'references' => [],
                'hobbies' => $languages,
                'is_public' => $data['is_public'] ?? false,
                'is_default' => false,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Importe une ligne de Quick Service
     */
    private function importQuickServiceRow(array $data): void
    {
        $validator = Validator::make($data, [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'user_email' => 'required|email',
            'category_name' => 'required|string',
            'price_type' => 'nullable|string|in:fixed,range,negotiable',
            'price_min' => 'nullable|numeric|min:0',
            'urgency' => 'nullable|string|in:low,medium,high,urgent',
            'status' => 'nullable|string|in:pending,approved,open,in_progress,completed,cancelled',
        ]);

        if ($validator->fails()) {
            throw new \Exception('Validation échouée: ' . implode(', ', $validator->errors()->all()));
        }

        DB::beginTransaction();
        try {
            // Trouver l'utilisateur
            $user = User::where('email', $data['user_email'])->first();

            if (!$user) {
                throw new \Exception("Utilisateur avec l'email {$data['user_email']} non trouvé");
            }

            // Trouver ou créer la catégorie de service
            $category = ServiceCategory::firstOrCreate(
                ['name' => $data['category_name']],
                ['description' => '']
            );

            // Créer le service rapide
            QuickService::create([
                'user_id' => $user->id,
                'service_category_id' => $category->id,
                'title' => $data['title'],
                'description' => $data['description'] ?? '',
                'price_type' => $data['price_type'] ?? 'negotiable',
                'price_min' => $data['price_min'] ?? null,
                'price_max' => $data['price_max'] ?? null,
                'latitude' => $data['latitude'] ?? null,
                'longitude' => $data['longitude'] ?? null,
                'location_name' => $data['location_name'] ?? null,
                'urgency' => $data['urgency'] ?? 'medium',
                'desired_date' => !empty($data['desired_date']) ? $data['desired_date'] : null,
                'estimated_duration' => $data['estimated_duration'] ?? null,
                'status' => $data['status'] ?? 'pending',
                'views_count' => 0,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Headers pour Jobs
     */
    private function getJobsColumnHeaders(): array
    {
        return [
            'title' => 'Titre du poste',
            'description' => 'Description',
            'requirements' => 'Exigences',
            'benefits' => 'Avantages',
            'salary_min' => 'Salaire minimum',
            'salary_max' => 'Salaire maximum',
            'salary_negotiable' => 'Salaire négociable (oui/non)',
            'experience_level' => 'Niveau d\'expérience (entry/junior/mid/senior/expert)',
            'status' => 'Statut (draft/pending/published/closed)',
            'application_deadline' => 'Date limite de candidature (YYYY-MM-DD)',
            'company_name' => 'Nom de l\'entreprise',
            'category_name' => 'Catégorie',
            'location_name' => 'Localisation',
            'contract_type_name' => 'Type de contrat',
        ];
    }

    /**
     * Headers pour Resumes
     */
    private function getResumesColumnHeaders(): array
    {
        return [
            'title' => 'Titre du CV',
            'template_type' => 'Type de template (modern/classic/creative/professional/minimalist)',
            'professional_summary' => 'Résumé professionnel',
            'name' => 'Nom complet',
            'email' => 'Email',
            'phone' => 'Téléphone',
            'address' => 'Adresse',
            'linkedin' => 'LinkedIn',
            'website' => 'Site web',
            'skills' => 'Compétences (séparées par virgule)',
            'languages' => 'Langues (séparées par virgule)',
            'is_public' => 'Public (oui/non)',
        ];
    }

    /**
     * Headers pour Quick Services
     */
    private function getQuickServicesColumnHeaders(): array
    {
        return [
            'title' => 'Titre du service',
            'description' => 'Description',
            'price_type' => 'Type de prix (fixed/range/negotiable)',
            'price_min' => 'Prix minimum',
            'price_max' => 'Prix maximum',
            'location_name' => 'Localisation',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'urgency' => 'Urgence (low/medium/high/urgent)',
            'desired_date' => 'Date souhaitée (YYYY-MM-DD)',
            'estimated_duration' => 'Durée estimée',
            'status' => 'Statut (pending/approved/open/in_progress/completed/cancelled)',
            'user_email' => 'Email de l\'utilisateur',
            'category_name' => 'Catégorie de service',
        ];
    }
}
