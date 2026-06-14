<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Specialty;
use Illuminate\Http\JsonResponse;

class SpecialtyController extends Controller
{
    /**
     * Liste des spécialités académiques (filières) actives.
     *
     * Utilisé pour filtrer les offres (ex: stages) par spécialité.
     */
    public function index(): JsonResponse
    {
        $specialties = Specialty::active()
            ->ordered()
            ->withCount('jobs')
            ->get();

        return response()->json([
            'data' => $specialties->map(fn ($s) => [
                'id' => $s->id,
                'name' => $s->name,
                'slug' => $s->slug,
                'icon' => $s->icon,
                'color' => $s->color,
                'jobs_count' => $s->jobs_count ?? 0,
            ]),
        ]);
    }
}
