<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\ApplicationTestResult;
use App\Models\RecruiterSkillTest;
use App\Services\Recruiter\RecruiterServicePurchaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RecruiterSkillTestController extends Controller
{
    protected RecruiterServicePurchaseService $purchaseService;

    public function __construct(RecruiterServicePurchaseService $purchaseService)
    {
        $this->purchaseService = $purchaseService;
    }

    /**
     * List all tests for the authenticated company
     * GET /api/recruiter/skill-tests
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user->company) {
            return response()->json([
                'success' => false,
                'message' => 'Vous devez être associé à une entreprise',
            ], 403);
        }

        $tests = RecruiterSkillTest::where('company_id', $user->company->id)
            ->with(['job:id,title', 'results'])
            ->withCount('results')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($test) {
                return [
                    'id' => $test->id,
                    'title' => $test->title,
                    'description' => $test->description,
                    'job_title' => $test->job?->title,
                    'duration_minutes' => $test->duration_minutes,
                    'passing_score' => $test->passing_score,
                    'question_count' => count($test->questions),
                    'is_active' => $test->is_active,
                    'times_used' => $test->times_used,
                    'results_count' => $test->results_count,
                    'created_at' => $test->created_at->format('Y-m-d H:i:s'),
                ];
            });

        return response()->json([
            'success' => true,
            'tests' => $tests,
        ]);
    }

    /**
     * Get a specific test
     * GET /api/recruiter/skill-tests/{id}
     */
    public function show($id)
    {
        $user = Auth::user();

        if (!$user->company) {
            return response()->json([
                'success' => false,
                'message' => 'Vous devez être associé à une entreprise',
            ], 403);
        }

        $test = RecruiterSkillTest::where('company_id', $user->company->id)
            ->with(['job:id,title', 'results.application.user'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'test' => [
                'id' => $test->id,
                'title' => $test->title,
                'description' => $test->description,
                'job_id' => $test->job_id,
                'job_title' => $test->job?->title,
                'questions' => $test->questions,
                'duration_minutes' => $test->duration_minutes,
                'passing_score' => $test->passing_score,
                'is_active' => $test->is_active,
                'times_used' => $test->times_used,
                'created_at' => $test->created_at->format('Y-m-d H:i:s'),
                'results' => $test->results->map(function ($result) {
                    return [
                        'id' => $result->id,
                        'candidate_name' => $result->application->user->name,
                        'score' => $result->score,
                        'passed' => $result->passed,
                        'completed_at' => $result->completed_at?->format('Y-m-d H:i:s'),
                        'duration_seconds' => $result->duration_seconds,
                    ];
                }),
            ],
        ]);
    }

    /**
     * Create a new skill test
     * POST /api/recruiter/skill-tests
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user->company) {
            return response()->json([
                'success' => false,
                'message' => 'Vous devez être associé à une entreprise',
            ], 403);
        }

        // Check if company has skills test access
        if (!$this->purchaseService->hasSkillsTestAccess($user->company)) {
            return response()->json([
                'success' => false,
                'message' => 'Vous devez acheter l\'accès aux tests de compétences pour créer un test',
            ], 403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'job_id' => 'nullable|exists:jobs,id',
            'questions' => 'required|array|min:1',
            'questions.*.question' => 'required|string',
            'questions.*.type' => 'required|in:multiple_choice,text,code',
            'questions.*.options' => 'required_if:questions.*.type,multiple_choice|array',
            'questions.*.correct_answer' => 'required',
            'duration_minutes' => 'nullable|integer|min:5|max:180',
            'passing_score' => 'required|integer|min:0|max:100',
        ]);

        // Verify job belongs to company if provided
        if ($request->job_id) {
            $job = \App\Models\Job::where('id', $request->job_id)
                ->where('company_id', $user->company->id)
                ->first();

            if (!$job) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cette offre n\'appartient pas à votre entreprise',
                ], 403);
            }
        }

        $test = RecruiterSkillTest::create([
            'company_id' => $user->company->id,
            'job_id' => $request->job_id,
            'title' => $request->title,
            'description' => $request->description,
            'questions' => $request->questions,
            'duration_minutes' => $request->duration_minutes,
            'passing_score' => $request->passing_score,
            'is_active' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Test créé avec succès',
            'test' => $test,
        ], 201);
    }

    /**
     * Update a test
     * PUT /api/recruiter/skill-tests/{id}
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();

        if (!$user->company) {
            return response()->json([
                'success' => false,
                'message' => 'Vous devez être associé à une entreprise',
            ], 403);
        }

        $test = RecruiterSkillTest::where('company_id', $user->company->id)
            ->findOrFail($id);

        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'job_id' => 'nullable|exists:jobs,id',
            'questions' => 'sometimes|required|array|min:1',
            'questions.*.question' => 'required|string',
            'questions.*.type' => 'required|in:multiple_choice,text,code',
            'questions.*.options' => 'required_if:questions.*.type,multiple_choice|array',
            'questions.*.correct_answer' => 'required',
            'duration_minutes' => 'nullable|integer|min:5|max:180',
            'passing_score' => 'sometimes|required|integer|min:0|max:100',
            'is_active' => 'sometimes|boolean',
        ]);

        // Verify job belongs to company if provided
        if ($request->has('job_id') && $request->job_id) {
            $job = \App\Models\Job::where('id', $request->job_id)
                ->where('company_id', $user->company->id)
                ->first();

            if (!$job) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cette offre n\'appartient pas à votre entreprise',
                ], 403);
            }
        }

        $test->update($request->only([
            'title',
            'description',
            'job_id',
            'questions',
            'duration_minutes',
            'passing_score',
            'is_active',
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Test mis à jour avec succès',
            'test' => $test->fresh(),
        ]);
    }

    /**
     * Delete a test
     * DELETE /api/recruiter/skill-tests/{id}
     */
    public function destroy($id)
    {
        $user = Auth::user();

        if (!$user->company) {
            return response()->json([
                'success' => false,
                'message' => 'Vous devez être associé à une entreprise',
            ], 403);
        }

        $test = RecruiterSkillTest::where('company_id', $user->company->id)
            ->findOrFail($id);

        $test->delete();

        return response()->json([
            'success' => true,
            'message' => 'Test supprimé avec succès',
        ]);
    }

    /**
     * Submit test results (candidate endpoint)
     * POST /api/candidate/skill-tests/{testId}/submit
     */
    public function submitTestResults(Request $request, $testId)
    {
        $user = Auth::user();

        $request->validate([
            'application_id' => 'required|exists:applications,id',
            'answers' => 'required|array',
            'started_at' => 'required|date',
        ]);

        $test = RecruiterSkillTest::where('is_active', true)->findOrFail($testId);
        $application = Application::where('id', $request->application_id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        // Check if already submitted
        $existing = ApplicationTestResult::where('application_id', $application->id)
            ->where('recruiter_skill_test_id', $test->id)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Vous avez déjà soumis ce test',
            ], 400);
        }

        // Calculate score
        $score = $this->calculateScore($test->questions, $request->answers);

        try {
            DB::beginTransaction();

            $result = ApplicationTestResult::create([
                'application_id' => $application->id,
                'recruiter_skill_test_id' => $test->id,
                'answers' => $request->answers,
                'score' => $score,
                'passed' => $score >= $test->passing_score,
                'started_at' => $request->started_at,
                'completed_at' => now(),
                'duration_seconds' => now()->diffInSeconds($request->started_at),
            ]);

            // Increment test usage
            $test->incrementUsage();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Test soumis avec succès',
                'result' => [
                    'score' => $result->score,
                    'passed' => $result->passed,
                    'passing_score' => $test->passing_score,
                ],
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la soumission du test',
            ], 500);
        }
    }

    /**
     * Calculate test score
     */
    protected function calculateScore(array $questions, array $answers): int
    {
        $totalQuestions = count($questions);
        if ($totalQuestions === 0) {
            return 0;
        }

        $correctAnswers = 0;

        foreach ($questions as $index => $question) {
            $userAnswer = $answers[$index] ?? null;
            $correctAnswer = $question['correct_answer'] ?? null;

            if ($userAnswer !== null && $correctAnswer !== null) {
                // For multiple choice, compare directly
                if ($question['type'] === 'multiple_choice') {
                    if ($userAnswer === $correctAnswer) {
                        $correctAnswers++;
                    }
                }
                // For text/code, check if it matches (case-insensitive for text)
                else if ($question['type'] === 'text') {
                    if (strtolower(trim($userAnswer)) === strtolower(trim($correctAnswer))) {
                        $correctAnswers++;
                    }
                }
                // For code, exact match required
                else if ($question['type'] === 'code') {
                    if (trim($userAnswer) === trim($correctAnswer)) {
                        $correctAnswers++;
                    }
                }
            }
        }

        return (int) (($correctAnswers / $totalQuestions) * 100);
    }

    /**
     * Get test for candidate to take
     * GET /api/candidate/skill-tests/{testId}
     */
    public function getTestForCandidate($testId)
    {
        $user = Auth::user();

        $test = RecruiterSkillTest::where('is_active', true)->findOrFail($testId);

        // Return test without correct answers
        $questions = collect($test->questions)->map(function ($question) {
            return [
                'question' => $question['question'],
                'type' => $question['type'],
                'options' => $question['options'] ?? null,
            ];
        });

        return response()->json([
            'success' => true,
            'test' => [
                'id' => $test->id,
                'title' => $test->title,
                'description' => $test->description,
                'duration_minutes' => $test->duration_minutes,
                'passing_score' => $test->passing_score,
                'questions' => $questions,
            ],
        ]);
    }
}
