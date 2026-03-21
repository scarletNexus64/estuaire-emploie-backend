<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Specialty;
use App\Models\User;
use App\Services\StudentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    protected StudentService $studentService;

    public function __construct(StudentService $studentService)
    {
        $this->studentService = $studentService;
    }

    /**
     * Afficher la liste des étudiants
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'candidate')
            ->whereNotNull('level') // Filtre les candidats qui ont un niveau = étudiants
            ->with(['premiumServices.config', 'userSubscriptionPlans.subscriptionPlan'])
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

        // Nettoyer la session
        session()->forget(['student_data', 'student_password']);

        // Récupérer les avantages pour l'affichage
        $benefits = $this->studentService->getStudentBenefits($user);

        // Retourner vers une page de confirmation avec les infos
        return view('admin.students.confirmation', compact('user', 'password', 'benefits'));
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

        return view('admin.students.show', compact('student', 'benefits'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit($id)
    {
        $student = User::where('role', 'candidate')
            ->whereNotNull('level')
            ->findOrFail($id);

        return view('admin.students.edit', compact('student'));
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
