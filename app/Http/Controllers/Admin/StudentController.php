<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Specialty;
use App\Models\User;
use App\Services\StudentService;
use App\Services\CVGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    protected StudentService $studentService;
    protected CVGeneratorService $cvGeneratorService;

    public function __construct(StudentService $studentService, CVGeneratorService $cvGeneratorService)
    {
        $this->studentService = $studentService;
        $this->cvGeneratorService = $cvGeneratorService;
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
        return view('admin.students.create');
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
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
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

        return view('admin.students.preview', compact('password', 'smsMessage'))->with('studentData', $request->all());
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

        // Sauvegarder le password en session pour l'étape suivante
        session(['created_student_password' => $password]);

        // Ne pas nettoyer student_data et student_password ici
        // Rediriger vers l'éditeur de CV
        return redirect()->route('admin.students.create-cv', $user->id)
            ->with('success', 'Compte créé avec succès ! Créez maintenant le CV de l\'étudiant.');
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

            // Récupérer le mot de passe de la session
            $password = session('created_student_password');

            // Nettoyer toutes les sessions temporaires
            session()->forget(['student_data', 'student_password', 'created_student_password']);

            // Récupérer les avantages pour l'affichage
            $benefits = $this->studentService->getStudentBenefits($student);

            // Message de succès
            $successMessage = $existingResume
                ? 'CV mis à jour avec succès ! Vous pouvez maintenant envoyer le SMS.'
                : 'CV créé avec succès ! Vous pouvez maintenant envoyer le SMS.';

            // Rediriger vers la page de confirmation avec le CV créé/mis à jour
            return view('admin.students.confirmation', compact('student', 'password', 'benefits', 'resume'))
                ->with('user', $student)
                ->with('success', $successMessage);
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la création du CV : ' . $e->getMessage())->withInput();
        }
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

        return redirect()->route('admin.students.show', $student->id)->with('success', 'Étudiant mis à jour avec succès');
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
