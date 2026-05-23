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
        $companyId = $user->current_company_id;

        if (! $companyId) {
            return response()->json([
                'success' => false,
                'message' => __('skill_test.select_active_company'),
                'error_code' => 'NO_CURRENT_COMPANY',
            ], 409);
        }

        $tests = RecruiterSkillTest::where('company_id', $companyId)
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
        $companyId = $user->current_company_id;

        if (! $companyId) {
            return response()->json([
                'success' => false,
                'message' => __('skill_test.select_active_company'),
                'error_code' => 'NO_CURRENT_COMPANY',
            ], 409);
        }

        $test = RecruiterSkillTest::where('company_id', $companyId)
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
     * Create a new skill test (as draft)
     * POST /api/recruiter/skill-tests
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $companyId = $user->current_company_id;

        if (! $companyId) {
            return response()->json([
                'success' => false,
                'message' => __('skill_test.select_active_company'),
                'error_code' => 'NO_CURRENT_COMPANY',
            ], 409);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'job_id' => 'nullable|exists:jobs,id',
            'questions' => 'required|array|min:1',
            'questions.*.question' => 'required|string',
            'questions.*.type' => 'required|in:multiple_choice',
            'questions.*.options' => 'required_if:questions.*.type,multiple_choice|array',
            'questions.*.correct_answer' => 'required',
            'duration_minutes' => 'nullable|integer|min:5|max:180',
            'passing_score' => 'required|integer|min:0|max:100',
        ]);

        // Verify job belongs to company if provided
        if ($request->job_id) {
            $job = \App\Models\Job::where('id', $request->job_id)
                ->where('company_id', $companyId)
                ->first();

            if (!$job) {
                return response()->json([
                    'success' => false,
                    'message' => __('skill_test.job_not_in_company'),
                ], 403);
            }
        }

        // Create test as draft (is_active = false) - no payment required yet
        $test = RecruiterSkillTest::create([
            'company_id' => $companyId,
            'job_id' => $request->job_id,
            'title' => $request->title,
            'description' => $request->description,
            'questions' => $request->questions,
            'duration_minutes' => $request->duration_minutes,
            'passing_score' => $request->passing_score,
            'is_active' => false, // Draft mode by default
        ]);

        return response()->json([
            'success' => true,
            'message' => __('skill_test.created_draft'),
            'test' => $test,
        ], 201);
    }

    /**
     * Publish/activate a test (requires payment)
     * POST /api/recruiter/skill-tests/{id}/publish
     */
    public function publish(Request $request, $id)
    {
        $user = Auth::user();
        $companyId = $user->current_company_id;

        if (! $companyId) {
            return response()->json([
                'success' => false,
                'message' => __('skill_test.select_active_company'),
                'error_code' => 'NO_CURRENT_COMPANY',
            ], 409);
        }

        $test = RecruiterSkillTest::where('company_id', $companyId)
            ->findOrFail($id);

        // Check if already active
        if ($test->is_active) {
            return response()->json([
                'success' => true,
                'message' => __('skill_test.already_active'),
                'test' => $test,
            ]);
        }

        // Check if company has skills test access (payment required)
        $company = \App\Models\Company::find($companyId);
        if (!$this->purchaseService->hasSkillsTestAccess($company)) {
            return response()->json([
                'success' => false,
                'message' => __('skill_test.must_purchase_access'),
                'requires_payment' => true,
                'price' => 2000, // From AddonServiceConfigSeeder
            ], 403);
        }

        // Activate the test
        $test->update(['is_active' => true]);

        return response()->json([
            'success' => true,
            'message' => __('skill_test.published'),
            'test' => $test->fresh(),
        ]);
    }

    /**
     * Update a test
     * PUT /api/recruiter/skill-tests/{id}
     */
    public function update(Request $request, $id)
    {
        \Log::info('========== UPDATE TEST CALLED ==========', [
            'test_id' => $id,
            'method' => $request->method(),
            'url' => $request->fullUrl(),
        ]);

        $user = Auth::user();
        $companyId = $user->current_company_id;

        \Log::info('User & current company check', [
            'user_id' => $user->id ?? null,
            'company_id' => $companyId,
        ]);

        if (! $companyId) {
            return response()->json([
                'success' => false,
                'message' => __('skill_test.select_active_company'),
                'error_code' => 'NO_CURRENT_COMPANY',
            ], 409);
        }

        $test = RecruiterSkillTest::where('company_id', $companyId)
            ->findOrFail($id);

        \Log::info('Test found', [
            'test_id' => $test->id,
            'current_title' => $test->title,
            'current_questions_count' => count($test->questions),
        ]);

        \Log::info('📤 UPDATE TEST REQUEST', [
            'test_id' => $id,
            'request_data_keys' => array_keys($request->all()),
            'has_questions' => $request->has('questions'),
            'questions_count' => $request->has('questions') ? count($request->questions) : 0,
        ]);

        if ($request->has('questions') && !empty($request->questions)) {
            \Log::info('📋 Questions données:', [
                'first_question' => $request->questions[0] ?? null,
            ]);
        }

        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'job_id' => 'nullable|exists:jobs,id',
            'questions' => 'sometimes|required|array|min:1',
            'questions.*.question' => 'required|string',
            'questions.*.type' => 'required|in:multiple_choice',
            'questions.*.options' => 'required_if:questions.*.type,multiple_choice|array',
            'questions.*.correct_answer' => 'required',
            'duration_minutes' => 'nullable|integer|min:5|max:180',
            'passing_score' => 'sometimes|required|integer|min:0|max:100',
            'is_active' => 'sometimes|boolean',
        ]);

        // Verify job belongs to company if provided
        if ($request->has('job_id') && $request->job_id) {
            $job = \App\Models\Job::where('id', $request->job_id)
                ->where('company_id', $companyId)
                ->first();

            if (!$job) {
                return response()->json([
                    'success' => false,
                    'message' => __('skill_test.job_not_in_company'),
                ], 403);
            }
        }

        $dataToUpdate = $request->only([
            'title',
            'description',
            'job_id',
            'questions',
            'duration_minutes',
            'passing_score',
            'is_active',
        ]);

        \Log::info('💾 Données à mettre à jour:', [
            'data_keys' => array_keys($dataToUpdate),
            'has_questions' => isset($dataToUpdate['questions']),
            'questions_count' => isset($dataToUpdate['questions']) ? count($dataToUpdate['questions']) : 0,
        ]);

        $test->update($dataToUpdate);

        $freshTest = $test->fresh();

        \Log::info('✅ Test mis à jour:', [
            'test_id' => $freshTest->id,
            'title' => $freshTest->title,
            'questions_count' => count($freshTest->questions),
            'first_question' => $freshTest->questions[0] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => __('skill_test.updated'),
            'test' => $freshTest,
        ]);
    }

    /**
     * Delete a test
     * DELETE /api/recruiter/skill-tests/{id}
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $companyId = $user->current_company_id;

        if (! $companyId) {
            return response()->json([
                'success' => false,
                'message' => __('skill_test.select_active_company'),
                'error_code' => 'NO_CURRENT_COMPANY',
            ], 409);
        }

        $test = RecruiterSkillTest::where('company_id', $companyId)
            ->findOrFail($id);

        $test->delete();

        return response()->json([
            'success' => true,
            'message' => __('skill_test.deleted'),
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
                'message' => __('skill_test.already_submitted'),
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
                'message' => __('skill_test.submitted'),
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
                'message' => __('skill_test.submit_error'),
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

        \Log::info('🔍 Calcul du score', [
            'total_questions' => $totalQuestions,
            'total_answers' => count($answers),
        ]);

        foreach ($questions as $index => $question) {
            $userAnswer = $answers[$index] ?? null;
            $correctAnswer = $question['correct_answer'] ?? null;

            if ($userAnswer !== null && $correctAnswer !== null) {
                // Normalize both answers: trim whitespace and compare case-insensitively
                $normalizedUserAnswer = trim($userAnswer);
                $normalizedCorrectAnswer = trim($correctAnswer);

                \Log::info("Question $index:", [
                    'user_answer' => $normalizedUserAnswer,
                    'correct_answer' => $normalizedCorrectAnswer,
                    'match' => $normalizedUserAnswer === $normalizedCorrectAnswer,
                ]);

                // For multiple choice, compare after normalization
                if ($normalizedUserAnswer === $normalizedCorrectAnswer) {
                    $correctAnswers++;
                }
            } else {
                \Log::warning("Question $index: réponse ou bonne réponse manquante", [
                    'has_user_answer' => $userAnswer !== null,
                    'has_correct_answer' => $correctAnswer !== null,
                ]);
            }
        }

        $score = (int) (($correctAnswers / $totalQuestions) * 100);

        \Log::info('✅ Score calculé', [
            'correct_answers' => $correctAnswers,
            'total_questions' => $totalQuestions,
            'score' => $score,
        ]);

        return $score;
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

    /**
     * Calculate test score BEFORE applying (no application_id yet)
     * POST /api/candidate/skill-tests/{testId}/calculate-score
     */
    public function calculateScoreOnly(Request $request, $testId)
    {
        $request->validate([
            'answers' => 'required|array',
            'started_at' => 'required|date',
        ]);

        $test = RecruiterSkillTest::where('is_active', true)->findOrFail($testId);

        // Calculate score
        $score = $this->calculateScore($test->questions, $request->answers);
        $passed = $score >= $test->passing_score;

        \Log::info('✅ Score calculé pour candidat (avant candidature)', [
            'test_id' => $testId,
            'user_id' => Auth::id(),
            'score' => $score,
            'passed' => $passed,
        ]);

        return response()->json([
            'success' => true,
            'result' => [
                'score' => $score,
                'passed' => $passed,
                'passing_score' => $test->passing_score,
            ],
        ]);
    }
}
