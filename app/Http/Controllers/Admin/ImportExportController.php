<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Company;
use App\Models\CompanyCategory;
use App\Models\ContractType;
use App\Models\Job;
use App\Models\QuickService;
use App\Models\Resume;
use App\Models\ServiceCategory;
use App\Models\User;
use Illuminate\Support\Str;
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
     * Display the import/export interface
     */
    public function index()
    {
        return view('admin.import-export.index');
    }

    /**
     * Export template Jobs avec sélection de colonnes
     */
    public function exportJobsTemplate(Request $request): StreamedResponse
    {
        $request->validate([
            'columns' => 'required|array|min:1',
            'columns.*' => 'string',
        ]);

        $columns = $request->input('columns');

        return $this->generateTemplate('jobs', $columns, $this->getJobsColumnHeaders());
    }

    /**
     * Export template Resumes (CVs) avec sélection de colonnes
     */
    public function exportResumesTemplate(Request $request): StreamedResponse
    {
        $request->validate([
            'columns' => 'required|array|min:1',
            'columns.*' => 'string',
        ]);

        $columns = $request->input('columns');

        return $this->generateTemplate('resumes', $columns, $this->getResumesColumnHeaders());
    }

    /**
     * Export template Quick Services avec sélection de colonnes
     */
    public function exportQuickServicesTemplate(Request $request): StreamedResponse
    {
        $request->validate([
            'columns' => 'required|array|min:1',
            'columns.*' => 'string',
        ]);

        $columns = $request->input('columns');

        return $this->generateTemplate('quick_services', $columns, $this->getQuickServicesColumnHeaders());
    }

    /**
     * Import Jobs depuis CSV/Excel
     */
    public function importJobs(Request $request): JsonResponse
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
                    'message' => __('import_export.file_empty'),
                ], 400);
            }

            $headers = array_shift($rows);
            $headerMap = $this->mapHeaders($headers, $this->getJobsColumnHeaders());

            $results = [
                'total' => 0,
                'imported' => 0,
                'failed' => 0,
                'skipped' => 0,
                'errors' => [],
            ];

            foreach ($rows as $index => $row) {
                $rowNumber = $index + 2;

                if ($this->isRowEmpty($row)) {
                    $results['skipped']++;
                    continue;
                }

                $results['total']++;

                try {
                    $data = $this->mapRowToData($row, $headerMap);
                    $this->importJobRow($data);
                    $results['imported']++;
                } catch (\Exception $e) {
                    $results['failed']++;
                    $results['errors'][] = [
                        'row' => $rowNumber,
                        'error' => $this->toUtf8($e->getMessage()),
                        'data' => $this->getSafeRowPreview($row, $headerMap),
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'message' => __('import_export.import_complete', ['imported' => $results['imported'], 'failed' => $results['failed']])
                    . ($results['skipped'] > 0 ? ", {$results['skipped']} lignes vides ignorées" : ''),
                'results' => $results,
            ]);

        } catch (\Exception $e) {
            Log::error('Jobs import error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => __('import_export.import_error', ['error' => $this->toUtf8($e->getMessage())]),
            ], 500);
        }
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
                    'message' => __('import_export.file_empty'),
                ], 400);
            }

            $headers = array_shift($rows);
            $headerMap = $this->mapHeaders($headers, $this->getResumesColumnHeaders());

            $results = [
                'total' => 0,
                'imported' => 0,
                'failed' => 0,
                'skipped' => 0,
                'errors' => [],
            ];

            foreach ($rows as $index => $row) {
                $rowNumber = $index + 2;

                if ($this->isRowEmpty($row)) {
                    $results['skipped']++;
                    continue;
                }

                $results['total']++;

                try {
                    $data = $this->mapRowToData($row, $headerMap);
                    $this->importResumeRow($data);
                    $results['imported']++;
                } catch (\Exception $e) {
                    $results['failed']++;
                    $results['errors'][] = [
                        'row' => $rowNumber,
                        'error' => $this->toUtf8($e->getMessage()),
                        'data' => $this->getSafeRowPreview($row, $headerMap),
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'message' => __('import_export.import_complete', ['imported' => $results['imported'], 'failed' => $results['failed']])
                    . ($results['skipped'] > 0 ? ", {$results['skipped']} lignes vides ignorées" : ''),
                'results' => $results,
            ]);

        } catch (\Exception $e) {
            Log::error('Resumes import error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => __('import_export.import_error', ['error' => $this->toUtf8($e->getMessage())]),
            ], 500);
        }
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
                    'message' => __('import_export.file_empty'),
                ], 400);
            }

            $headers = array_shift($rows);
            $headerMap = $this->mapHeaders($headers, $this->getQuickServicesColumnHeaders());

            $results = [
                'total' => 0,
                'imported' => 0,
                'failed' => 0,
                'skipped' => 0,
                'errors' => [],
            ];

            foreach ($rows as $index => $row) {
                $rowNumber = $index + 2;

                if ($this->isRowEmpty($row)) {
                    $results['skipped']++;
                    continue;
                }

                $results['total']++;

                try {
                    $data = $this->mapRowToData($row, $headerMap);
                    $this->importQuickServiceRow($data);
                    $results['imported']++;
                } catch (\Exception $e) {
                    $results['failed']++;
                    $results['errors'][] = [
                        'row' => $rowNumber,
                        'error' => $this->toUtf8($e->getMessage()),
                        'data' => $this->getSafeRowPreview($row, $headerMap),
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'message' => __('import_export.import_complete', ['imported' => $results['imported'], 'failed' => $results['failed']])
                    . ($results['skipped'] > 0 ? ", {$results['skipped']} lignes vides ignorées" : ''),
                'results' => $results,
            ]);

        } catch (\Exception $e) {
            Log::error('Quick Services import error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => __('import_export.import_error', ['error' => $this->toUtf8($e->getMessage())]),
            ], 500);
        }
    }

    // ==================== PRIVATE HELPER METHODS ====================

    private function generateTemplate(string $type, array $columns, array $allHeaders): StreamedResponse
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Convert column number to letter (1 => A, 2 => B, etc.)
        $columnLetter = 'A';
        foreach ($columns as $columnKey) {
            if (isset($allHeaders[$columnKey])) {
                $sheet->setCellValue($columnLetter . '1', $allHeaders[$columnKey]);
                $columnLetter++;
            }
        }

        $sheet->getStyle('1:1')->getFont()->setBold(true);
        $sheet->getStyle('1:1')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFE0E0E0');

        // Auto-size all columns
        $columnLetter = 'A';
        for ($i = 0; $i < count($columns); $i++) {
            $sheet->getColumnDimension($columnLetter)->setAutoSize(true);
            $columnLetter++;
        }

        $filename = $type . '_template_' . date('Y-m-d_His') . '.xlsx';

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    private function mapHeaders(array $fileHeaders, array $expectedHeaders): array
    {
        $map = [];
        foreach ($fileHeaders as $index => $header) {
            $header = trim($this->toUtf8($header));
            $key = array_search($header, $expectedHeaders, true);
            if ($key !== false) {
                $map[$key] = $index;
            }
        }
        return $map;
    }

    private function mapRowToData(array $row, array $headerMap): array
    {
        $data = [];
        foreach ($headerMap as $key => $index) {
            $value = $row[$index] ?? null;
            $data[$key] = is_string($value) ? $this->toUtf8($value) : $value;
        }
        return $data;
    }

    /**
     * Returns true if the row has no usable content (all cells null or empty/whitespace).
     * PhpSpreadsheet's toArray() often returns trailing empty rows when the worksheet
     * has formatted-but-empty cells.
     */
    private function isRowEmpty(array $row): bool
    {
        foreach ($row as $value) {
            if ($value === null) {
                continue;
            }
            if (is_string($value) && trim($value) === '') {
                continue;
            }
            return false;
        }
        return true;
    }

    private function getSafeRowPreview(array $row, array $headerMap): array
    {
        $preview = [];
        $count = 0;
        foreach ($headerMap as $key => $index) {
            if ($count >= 5) break;
            if (isset($row[$index])) {
                $value = is_string($row[$index]) ? $this->toUtf8($row[$index]) : (string) $row[$index];
                $preview[$key] = mb_substr($value, 0, 50, 'UTF-8');
            } else {
                $preview[$key] = null;
            }
            $count++;
        }
        return $preview;
    }

    /**
     * Convert a string to valid UTF-8.
     *
     * Handles files exported from Windows Excel (Windows-1252/ISO-8859-1)
     * or any other non-UTF-8 encoded source so response()->json() doesn't
     * throw "Malformed UTF-8 characters" errors.
     */
    private function toUtf8($value): string
    {
        if ($value === null) {
            return '';
        }

        $value = (string) $value;

        if ($value === '' || mb_check_encoding($value, 'UTF-8')) {
            return $value;
        }

        $encoding = mb_detect_encoding($value, ['UTF-8', 'Windows-1252', 'ISO-8859-1', 'ISO-8859-15', 'ASCII'], true);

        if ($encoding === false) {
            $encoding = 'Windows-1252';
        }

        $converted = @mb_convert_encoding($value, 'UTF-8', $encoding);

        if ($converted === false || !mb_check_encoding($converted, 'UTF-8')) {
            // Last-resort: strip any remaining invalid bytes
            $converted = mb_convert_encoding($value, 'UTF-8', 'UTF-8');
        }

        return $converted;
    }

    private function importJobRow(array $data): void
    {
        $validator = Validator::make($data, [
            'title' => 'required|string|max:255',
            'company_name' => 'required|string',
        ]);

        if ($validator->fails()) {
            throw new \Exception('Validation échouée: ' . implode(', ', $validator->errors()->all()));
        }

        DB::beginTransaction();
        try {
            $company = $this->findOrCreateCompany($data['company_name']);

            $categoryId = null;
            if (!empty($data['category_name'])) {
                $categoryId = $this->findOrCreateCompanyCategory($data['category_name'])->id;
            }

            $contractTypeId = null;
            if (!empty($data['contract_type_name'])) {
                $contractTypeId = $this->findOrCreateContractType($data['contract_type_name'])->id;
            }

            Job::create([
                'company_id' => $company->id,
                'category_id' => $categoryId,
                'contract_type_id' => $contractTypeId,
                'posted_by' => $this->resolvePostedBy(),
                'title' => $data['title'],
                'description' => !empty($data['description']) ? $data['description'] : ' ',
                'requirements' => $data['requirements'] ?? null,
                'benefits' => $data['benefits'] ?? null,
                'salary_min' => $data['salary_min'] ?? null,
                'salary_max' => $data['salary_max'] ?? null,
                'salary_negotiable' => $this->parseBool($data['salary_negotiable'] ?? null),
                'experience_level' => $this->normalizeExperienceLevel($data['experience_level'] ?? null),
                'status' => $this->normalizeJobStatus($data['status'] ?? null),
                'application_deadline' => !empty($data['application_deadline']) ? $data['application_deadline'] : null,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Find or create a Company, providing all NOT NULL columns and a
     * unique email when one is missing (the `email` column is unique).
     */
    private function findOrCreateCompany(string $name): Company
    {
        $name = trim($name);
        $company = Company::where('name', $name)->first();
        if ($company) {
            return $company;
        }

        $slugBase = Str::slug($name) ?: 'imported-' . Str::random(6);
        $email = $slugBase . '+' . Str::lower(Str::random(6)) . '@imported.local';

        // Defensive: ensure uniqueness in the unlikely event of a collision.
        while (Company::where('email', $email)->exists()) {
            $email = $slugBase . '+' . Str::lower(Str::random(8)) . '@imported.local';
        }

        return Company::create([
            'name' => $name,
            'email' => $email,
            'sector' => 'Non renseigné',
            'status' => 'pending',
            'subscription_plan' => 'free',
            'country' => 'Cameroun',
        ]);
    }

    /**
     * Find or create a CompanyCategory (level 3) by name (level_3 or level_1).
     */
    private function findOrCreateCompanyCategory(string $name): CompanyCategory
    {
        $name = trim($name);

        $existing = CompanyCategory::where('level_3', $name)
            ->orWhere('level_2', $name)
            ->orWhere('level_1', $name)
            ->first();

        if ($existing) {
            return $existing;
        }

        $code = 'IMP.' . str_pad((string) (CompanyCategory::max('id') + 1), 4, '0', STR_PAD_LEFT);

        return CompanyCategory::create([
            'code' => $code,
            'level_1' => 'Importé',
            'level_2' => null,
            'level_3' => $name,
            'slug' => Str::slug($name) . '-' . Str::lower(Str::random(4)),
            'is_active' => true,
        ]);
    }

    private function findOrCreateContractType(string $name): ContractType
    {
        $name = trim($name);
        $existing = ContractType::where('name', $name)->first();
        if ($existing) {
            return $existing;
        }

        $slug = Str::slug($name) ?: 'contract-' . Str::random(6);
        while (ContractType::where('slug', $slug)->exists()) {
            $slug = Str::slug($name) . '-' . Str::lower(Str::random(4));
        }

        return ContractType::create(['name' => $name, 'slug' => $slug]);
    }

    private function resolvePostedBy(): int
    {
        $authId = auth()->id();
        if ($authId && User::whereKey($authId)->exists()) {
            return $authId;
        }

        $admin = User::where('role', 'admin')->orderBy('id')->first();
        if ($admin) {
            return $admin->id;
        }

        $any = User::orderBy('id')->first();
        if (!$any) {
            throw new \Exception("Aucun utilisateur disponible pour 'posted_by'. Créez au moins un utilisateur admin.");
        }

        return $any->id;
    }

    private function parseBool($value): bool
    {
        if (is_bool($value)) {
            return $value;
        }
        $v = Str::lower(trim((string) $value));
        return in_array($v, ['1', 'true', 'yes', 'oui', 'y', 'o'], true);
    }

    private function normalizeExperienceLevel($value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }
        $allowed = ['junior', 'intermediaire', 'senior', 'expert'];
        $aliases = [
            'entry' => 'junior',
            'débutant' => 'junior',
            'debutant' => 'junior',
            'mid' => 'intermediaire',
            'intermediate' => 'intermediaire',
            'intermédiaire' => 'intermediaire',
        ];
        $v = Str::lower(trim((string) $value));
        if (in_array($v, $allowed, true)) {
            return $v;
        }
        return $aliases[$v] ?? null;
    }

    private function normalizeJobStatus($value): string
    {
        $allowed = ['draft', 'pending', 'published', 'closed', 'expired'];
        $v = Str::lower(trim((string) ($value ?? '')));
        return in_array($v, $allowed, true) ? $v : 'pending';
    }

    private function importResumeRow(array $data): void
    {
        // Auto-derive title from name/email if missing — we only truly need
        // *something* identifying for the resume row to be useful.
        $name = !empty($data['name']) ? trim($data['name']) : null;
        $email = !empty($data['email']) ? trim($data['email']) : null;
        $title = !empty($data['title']) ? trim($data['title']) : null;

        if (!$title && !$name && !$email) {
            throw new \Exception('Ligne sans titre, nom ni email — impossible de créer un CV');
        }

        if (!$email) {
            $slugSource = $name ?: $title ?: 'cv';
            $email = Str::slug($slugSource) . '+' . Str::lower(Str::random(6)) . '@imported.local';
            while (User::where('email', $email)->exists()) {
                $email = Str::slug($slugSource) . '+' . Str::lower(Str::random(8)) . '@imported.local';
            }
        } else {
            $validator = Validator::make(['email' => $email], ['email' => 'email']);
            if ($validator->fails()) {
                throw new \Exception('Email invalide: ' . $email);
            }
        }

        if (!$name) {
            $name = $title ?: Str::before($email, '@');
        }

        if (!$title) {
            $title = 'CV de ' . $name;
        }

        $data['name'] = $name;
        $data['email'] = $email;
        $data['title'] = $title;

        DB::beginTransaction();
        try {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                ['name' => $data['name'], 'password' => bcrypt(Str::random(16)), 'role' => 'candidate']
            );

            $personalInfo = [
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'address' => $data['address'] ?? null,
                'linkedin' => $data['linkedin'] ?? null,
                'website' => $data['website'] ?? null,
            ];

            $skills = [];
            if (!empty($data['skills'])) {
                $skillsList = explode(',', $data['skills']);
                foreach ($skillsList as $skill) {
                    $skill = trim($skill);
                    if ($skill !== '') {
                        $skills[] = ['name' => $skill, 'level' => 'intermediate'];
                    }
                }
            }

            Resume::create([
                'user_id' => $user->id,
                'title' => $data['title'],
                'template_type' => !empty($data['template_type']) ? $data['template_type'] : 'modern',
                'professional_summary' => $data['professional_summary'] ?? null,
                'personal_info' => $personalInfo,
                'skills' => $skills,
                'education' => [],
                'experiences' => [],
                'is_public' => $this->parseBool($data['is_public'] ?? null),
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function importQuickServiceRow(array $data): void
    {
        $validator = Validator::make($data, [
            'title' => 'required|string|max:255',
            'user_email' => 'required|email',
            'category_name' => 'required|string',
        ]);

        if ($validator->fails()) {
            throw new \Exception('Validation échouée: ' . implode(', ', $validator->errors()->all()));
        }

        DB::beginTransaction();
        try {
            $user = User::where('email', $data['user_email'])->first();

            if (!$user) {
                throw new \Exception("Utilisateur avec l'email {$data['user_email']} non trouvé");
            }

            $category = $this->findOrCreateServiceCategory($data['category_name']);

            QuickService::create([
                'user_id' => $user->id,
                'service_category_id' => $category->id,
                'title' => $data['title'],
                'description' => !empty($data['description']) ? $data['description'] : ' ',
                'price_type' => $this->normalizeEnum($data['price_type'] ?? null, ['fixed', 'range', 'negotiable'], 'negotiable'),
                'price_min' => $data['price_min'] ?? null,
                'price_max' => $data['price_max'] ?? null,
                'latitude' => is_numeric($data['latitude'] ?? null) ? (float) $data['latitude'] : 0,
                'longitude' => is_numeric($data['longitude'] ?? null) ? (float) $data['longitude'] : 0,
                'location_name' => $data['location_name'] ?? null,
                'urgency' => $this->normalizeUrgency($data['urgency'] ?? null),
                'desired_date' => !empty($data['desired_date']) ? $data['desired_date'] : null,
                'estimated_duration' => $data['estimated_duration'] ?? null,
                'status' => $this->normalizeEnum(
                    $data['status'] ?? null,
                    ['pending', 'approved', 'open', 'in_progress', 'completed', 'cancelled'],
                    'pending'
                ),
                'views_count' => 0,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function findOrCreateServiceCategory(string $name): ServiceCategory
    {
        $name = trim($name);
        $existing = ServiceCategory::where('name', $name)->first();
        if ($existing) {
            return $existing;
        }

        $slug = Str::slug($name) ?: 'service-' . Str::random(6);
        while (ServiceCategory::where('slug', $slug)->exists()) {
            $slug = Str::slug($name) . '-' . Str::lower(Str::random(4));
        }

        return ServiceCategory::create([
            'name' => $name,
            'slug' => $slug,
            'is_active' => true,
            'display_order' => 0,
        ]);
    }

    private function normalizeUrgency($value): string
    {
        $allowed = ['urgent', 'this_week', 'this_month', 'flexible'];
        $aliases = [
            'high' => 'urgent',
            'medium' => 'this_week',
            'low' => 'flexible',
            'normal' => 'this_week',
        ];
        $v = Str::lower(trim((string) ($value ?? '')));
        if (in_array($v, $allowed, true)) {
            return $v;
        }
        return $aliases[$v] ?? 'flexible';
    }

    private function normalizeEnum($value, array $allowed, string $default): string
    {
        $v = Str::lower(trim((string) ($value ?? '')));
        return in_array($v, $allowed, true) ? $v : $default;
    }

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
            'experience_level' => 'Niveau d\'expérience (junior/intermediaire/senior/expert)',
            'status' => 'Statut (draft/pending/published/closed/expired)',
            'application_deadline' => 'Date limite de candidature (YYYY-MM-DD)',
            'company_name' => 'Nom de l\'entreprise',
            'category_name' => 'Catégorie',
            'contract_type_name' => 'Type de contrat',
        ];
    }

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
            'urgency' => 'Urgence (urgent/this_week/this_month/flexible)',
            'desired_date' => 'Date souhaitée (YYYY-MM-DD)',
            'estimated_duration' => 'Durée estimée',
            'status' => 'Statut (pending/approved/open/in_progress/completed/cancelled)',
            'user_email' => 'Email de l\'utilisateur',
            'category_name' => 'Catégorie de service',
        ];
    }
}
