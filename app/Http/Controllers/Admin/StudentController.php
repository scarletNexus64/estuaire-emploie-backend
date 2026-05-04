<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Resume;
use App\Models\User;
use App\Services\Resume\ResumePdfService;
use App\Services\StudentService;
use App\Services\CVGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    protected StudentService $studentService;
    protected CVGeneratorService $cvGeneratorService;
    protected ResumePdfService $resumePdfService;

    public function __construct(
        StudentService $studentService,
        CVGeneratorService $cvGeneratorService,
        ResumePdfService $resumePdfService
    )
    {
        $this->studentService = $studentService;
        $this->cvGeneratorService = $cvGeneratorService;
        $this->resumePdfService = $resumePdfService;
    }

    /**
     * Afficher la liste des étudiants
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'candidate')
            ->whereNotNull('level') // Filtre les candidats qui ont un niveau = étudiants
            ->with(['premiumServices.config', 'userSubscriptionPlans.subscriptionPlan', 'resumes' => function($q) {
                $q->latest()->limit(1);
            }])
            ->latest();

        // Recherche
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('specialty', 'like', "%{$search}%")
                    ->orWhere('level', 'like', "%{$search}%");
            });
        }

        // Filtrer par niveau
        if ($request->has('level') && $request->level) {
            $query->where('level', 'like', "%{$request->level}%");
        }

        $students = $query->paginate(20);

        return view('admin.students.index', compact('students'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $templates = Resume::with('user')
            ->where('customization->source', 'INSAM_IMPORT')
            ->latest()
            ->get();

        return view('admin.students.create', compact('templates'));
    }

    /**
     * Afficher le récapitulatif avant de créer l'étudiant
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,NULL,id,deleted_at,NULL',
            'phone' => 'required|string|unique:users,phone,NULL,id,deleted_at,NULL',
            'specialty' => 'required|string|max:255',
            'level' => 'required|string|max:100',
            'interests' => 'nullable|string|max:1000',
            'cv_template_id' => 'required|integer|exists:resumes,id',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $template = Resume::where('id', $request->integer('cv_template_id'))
            ->where('customization->source', 'INSAM_IMPORT')
            ->first();

        if (!$template) {
            return back()->withErrors([
                'cv_template_id' => 'Le template sélectionné est invalide. Veuillez choisir un CV depuis la bibliothèque.',
            ])->withInput();
        }

        // Générer le mot de passe
        $password = $this->studentService->generatePassword();

        // Préparer le message SMS
        $smsMessage = $this->studentService->prepareSMSMessage($request->name, $request->email, $password, $request->phone);

        // Stocker les données en session pour les récupérer après confirmation
        session([
            'student_data' => $request->all(),
            'student_password' => $password,
        ]);

        return view('admin.students.preview', compact('password', 'smsMessage', 'template'))
            ->with('studentData', $request->all());
    }

    /**
     * Confirmer et créer l'étudiant après le récapitulatif
     */
    public function confirmAndSave(Request $request)
    {
        // Récupérer les données en session
        $studentData = session('student_data');
        $password = session('student_password');

        if (!$studentData || !$password) {
            return redirect()->route('admin.students.create')
                ->with('error', 'Session expirée. Veuillez recommencer.');
        }

        // Créer l'étudiant via le service avec le mot de passe de la session
        $result = $this->studentService->createStudent($studentData, $password);

        if (!$result['success']) {
            return back()->with('error', $result['message'])->withInput();
        }

        $user = $result['user'];

        // Dupliquer le template CV sélectionné pour le nouvel étudiant
        $this->cloneTemplateForStudent($studentData['cv_template_id'], $user);

        // Sauvegarder le password en session pour l'étape suivante
        session(['created_student_password' => $password]);

        // Ne pas nettoyer student_data et student_password ici
        // Rediriger directement vers la confirmation avec CV déjà attribué
        return redirect()->route('admin.students.confirmation', $user->id)
            ->with('success', 'Compte créé avec succès ! Le CV modèle a été attribué automatiquement.');
    }

    /**
     * Afficher l'éditeur de CV pour un étudiant
     */
    public function showCreateCV($userId)
    {
        $student = User::where('role', 'candidate')
            ->whereNotNull('level')
            ->findOrFail($userId);

        // Charger le CV existant s'il existe
        $resume = $student->resumes()->latest()->first();

        return view('admin.students.create_cv', compact('student', 'resume'));
    }

    /**
     * Sauvegarder le CV d'un étudiant
     */
    public function storeCV(Request $request, $userId)
    {
        $student = User::where('role', 'candidate')
            ->whereNotNull('level')
            ->findOrFail($userId);

        // Validation
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'photo' => 'nullable|image|max:2048',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'objective' => 'nullable|string|max:1000',
            'skills' => 'nullable|string|max:2000',
            'hobbies' => 'nullable|string|max:1000',
            'experiences' => 'nullable|array',
            'education' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            // Vérifier si un CV existe déjà
            $existingResume = $student->resumes()->latest()->first();

            if ($existingResume) {
                // Mettre à jour le CV existant
                $resume = $this->cvGeneratorService->updateStudentCV(
                    $existingResume,
                    $request->all(),
                    $request->file('photo')
                );
            } else {
                // Générer un nouveau CV
                $resume = $this->cvGeneratorService->generateStudentCV(
                    $student,
                    $request->all(),
                    $request->file('photo')
                );
            }

            // Message de succès
            $successMessage = $existingResume
                ? 'CV mis à jour avec succès ! Vous pouvez maintenant envoyer le SMS.'
                : 'CV créé avec succès ! Vous pouvez maintenant envoyer le SMS.';

            // Rediriger vers la page de confirmation (PRG pattern)
            return redirect()->route('admin.students.confirmation', $student->id)
                ->with('success', $successMessage);
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la création du CV : ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Afficher la page de confirmation après création/mise à jour du CV
     */
    public function confirmation($userId)
    {
        $student = User::where('role', 'candidate')
            ->whereNotNull('level')
            ->findOrFail($userId);

        $resume = $student->resumes()->latest()->first();

        if (!$resume) {
            return redirect()->route('admin.students.create-cv', $student->id)
                ->with('error', 'Aucun CV trouvé pour cet étudiant.');
        }

        $password = session('created_student_password');

        // Nettoyer les sessions temporaires
        session()->forget(['student_data', 'student_password', 'created_student_password']);

        $benefits = $this->studentService->getStudentBenefits($student);

        return view('admin.students.confirmation', compact('student', 'password', 'benefits', 'resume'))
            ->with('user', $student);
    }

    /**
     * Envoyer les identifiants par SMS
     */
    public function sendSMS(Request $request, $userId)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()->with('error', 'Mot de passe manquant');
        }

        $user = User::findOrFail($userId);
        $password = $request->password;

        $result = $this->studentService->sendCredentialsSMS($user, $password);

        if ($result['success']) {
            // Formater le numéro avec + pour l'affichage
            $phone = preg_replace('/\s+/', '', $user->phone);
            if (!str_starts_with($phone, '+')) {
                if (str_starts_with($phone, '237')) {
                    $phone = '+' . $phone;
                } else if (str_starts_with($phone, '6')) {
                    $phone = '+237' . $phone;
                } else {
                    $phone = '+' . $phone;
                }
            }
            return redirect()->route('admin.students.index')->with('success', 'SMS envoyé avec succès à ' . $phone);
        }

        return back()->with('error', 'Erreur lors de l\'envoi du SMS: ' . $result['message']);
    }

    /**
     * Afficher les détails d'un étudiant
     */
    public function show($id)
    {
        $student = User::where('role', 'candidate')
            ->whereNotNull('level')
            ->with(['premiumServices.config', 'userSubscriptionPlans.subscriptionPlan'])
            ->findOrFail($id);

        $benefits = $this->studentService->getStudentBenefits($student);

        // Charger le CV existant s'il existe
        $resume = $student->resumes()->latest()->first();

        return view('admin.students.show', compact('student', 'benefits', 'resume'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit($id)
    {
        $student = User::where('role', 'candidate')
            ->whereNotNull('level')
            ->findOrFail($id);

        // Charger le CV existant s'il existe
        $resume = $student->resumes()->latest()->first();

        return view('admin.students.edit', compact('student', 'resume'));
    }

    /**
     * Mettre à jour un étudiant
     */
    public function update(Request $request, $id)
    {
        $student = User::where('role', 'candidate')
            ->whereNotNull('level')
            ->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id . ',id,deleted_at,NULL',
            'phone' => 'required|string|unique:users,phone,' . $id . ',id,deleted_at,NULL',
            'specialty' => 'required|string|max:255',
            'level' => 'required|string|max:100',
            'interests' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $student->update($request->only(['name', 'email', 'phone', 'specialty', 'level', 'interests']));

        // Synchroniser automatiquement les infos clés dans le CV existant
        $resume = $student->resumes()->latest()->first();
        if ($resume) {
            $personalInfo = $resume->personal_info ?? [];
            $personalInfo['name'] = $student->name;
            $personalInfo['email'] = $student->email;
            $personalInfo['phone'] = $student->phone;

            $customization = $resume->customization ?? [];
            $customization['level'] = $student->level;
            $customization['specialty'] = $student->specialty;

            $resume->update([
                'personal_info' => $personalInfo,
                'customization' => $customization,
                'hobbies' => $student->interests
                    ? array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $student->interests)))
                    : [],
            ]);
        }

        return redirect()->route('admin.students.show', $student->id)->with('success', 'Étudiant mis à jour avec succès');
    }

    /**
     * Duplique un template CV de la librairie pour un étudiant et injecte ses informations personnelles.
     */
    protected function cloneTemplateForStudent(int $templateId, User $student): Resume
    {
        $template = Resume::where('id', $templateId)
            ->where('customization->source', 'INSAM_IMPORT')
            ->firstOrFail();

        $personalInfo = $template->personal_info ?? [];
        $personalInfo['name'] = $student->name;
        $personalInfo['email'] = $student->email;
        $personalInfo['phone'] = $student->phone;
        $personalInfo['address'] = $personalInfo['address'] ?? null;

        $customization = $template->customization ?? [];
        $customization['level'] = $student->level;
        $customization['specialty'] = $student->specialty;
        $customization['source'] = 'STUDENT_TEMPLATE_CLONE';
        $customization['template_resume_id'] = $template->id;

        // Si l'étudiant a déjà un CV, le nouveau clone devient le CV par défaut.
        Resume::where('user_id', $student->id)->update(['is_default' => false]);

        $resume = Resume::create([
            'user_id' => $student->id,
            'title' => $template->title,
            'template_type' => $template->template_type,
            'personal_info' => $personalInfo,
            'professional_summary' => $template->professional_summary,
            'experiences' => $template->experiences ?? [],
            'education' => $template->education ?? [],
            'skills' => $template->skills ?? [],
            'certifications' => $template->certifications ?? [],
            'projects' => $template->projects ?? [],
            'references' => $template->references ?? [],
            'hobbies' => $student->interests
                ? array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $student->interests)))
                : [],
            'customization' => $customization,
            'pdf_path' => null,
            'pdf_generated_at' => null,
            'is_public' => false,
            'is_default' => true,
        ]);

        // Générer directement le PDF pour que l'étudiant voie un CV complet après création.
        try {
            $this->resumePdfService->generatePdf($resume);
        } catch (\Throwable $e) {
            \Log::warning('Échec génération PDF après duplication template étudiant', [
                'student_id' => $student->id,
                'resume_id' => $resume->id,
                'error' => $e->getMessage(),
            ]);
        }

        return $resume->fresh();
    }

    /**
     * Supprimer un étudiant
     */
    public function destroy($id)
    {
        $student = User::where('role', 'candidate')
            ->whereNotNull('level')
            ->findOrFail($id);

        $student->delete();

        return redirect()->route('admin.students.index')->with('success', 'Étudiant supprimé avec succès');
    }
}
