<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyReview;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CompanyReviewController extends Controller
{
    public function index(Request $request, Company $company): JsonResponse
    {
        if ($company->status !== 'verified') {
            return response()->json(['message' => 'Entreprise indisponible.'], 404);
        }

        $perPage = min(20, max(1, (int) $request->input('per_page', 10)));
        $reviews = CompanyReview::query()
            ->where('company_id', $company->id)
            ->with('user:id,name')
            ->latest()
            ->paginate($perPage);

        $summary = CompanyReview::query()
            ->where('company_id', $company->id)
            ->selectRaw('COUNT(*) as review_count, AVG(rating) as average_rating')
            ->first();

        return response()->json([
            'data' => $reviews->through(fn (CompanyReview $review) => $this->present($review)),
            'summary' => [
                'review_count' => (int) $summary->review_count,
                'average_rating' => $summary->average_rating === null ? null : round((float) $summary->average_rating, 1),
            ],
        ]);
    }

    public function store(Request $request, Company $company): JsonResponse
    {
        if ($company->status !== 'verified') {
            return response()->json(['message' => 'Entreprise indisponible.'], 404);
        }

        $user = $request->user();
        if ($user->recruiters()->where('company_id', $company->id)->exists()) {
            return response()->json(['message' => 'Vous ne pouvez pas évaluer votre entreprise.'], 403);
        }

        $data = $request->validate([
            'rating' => ['required', 'integer', 'between:1,5'],
            'comment' => ['required', 'string', 'min:3', 'max:2000'],
        ]);
        $data['comment'] = trim($data['comment']);
        if (mb_strlen($data['comment']) < 3) {
            return response()->json(['message' => 'Le commentaire doit contenir au moins 3 caractères.'], 422);
        }

        $review = CompanyReview::updateOrCreate(
            ['company_id' => $company->id, 'user_id' => $user->id],
            $data,
        );
        $review->load('user:id,name');

        return response()->json([
            'data' => $this->present($review),
            'message' => 'Avis enregistré.',
        ], $review->wasRecentlyCreated ? 201 : 200);
    }

    private function present(CompanyReview $review): array
    {
        return [
            'id' => $review->id,
            'rating' => $review->rating,
            'comment' => $review->comment,
            'created_at' => $review->created_at,
            'updated_at' => $review->updated_at,
            'author' => ['id' => $review->user_id, 'name' => $review->user?->name ?? 'Utilisateur'],
        ];
    }
}
