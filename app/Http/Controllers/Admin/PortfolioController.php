<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PortfolioController extends Controller
{
    /**
     * Display a listing of portfolios
     */
    public function index(Request $request): View
    {
        $query = Portfolio::with(['user', 'views']);

        // Filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('template')) {
            $query->where('template_id', $request->template);
        }

        if ($request->filled('visibility')) {
            $query->where('is_public', $request->visibility === 'public');
        }

        $portfolios = $query->orderBy('created_at', 'desc')->paginate(20);

        $stats = [
            'total' => Portfolio::count(),
            'public' => Portfolio::where('is_public', true)->count(),
            'private' => Portfolio::where('is_public', false)->count(),
            'total_views' => Portfolio::sum('view_count'),
        ];

        return view('admin.portfolios.index', compact('portfolios', 'stats'));
    }

    /**
     * Display the specified portfolio
     */
    public function show(Portfolio $portfolio): View
    {
        $portfolio->load(['user', 'views' => function($query) {
            $query->orderBy('viewed_at', 'desc')->limit(50);
        }]);

        $viewsByDay = $portfolio->views()
            ->selectRaw('DATE(viewed_at) as date, COUNT(*) as count')
            ->where('viewed_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();

        return view('admin.portfolios.show', compact('portfolio', 'viewsByDay'));
    }

    /**
     * Remove the specified portfolio
     */
    public function destroy(Portfolio $portfolio): RedirectResponse
    {
        $userName = $portfolio->user->name;

        // Delete associated files
        if ($portfolio->photo_url) {
            \Storage::disk('public')->delete(str_replace('/storage/', '', $portfolio->photo_url));
        }
        if ($portfolio->cv_url) {
            \Storage::disk('public')->delete(str_replace('/storage/', '', $portfolio->cv_url));
        }

        $portfolio->delete();

        return redirect()->route('admin.portfolios.index')
            ->with('success', "Portfolio de {$userName} supprimé avec succès");
    }

    /**
     * Toggle portfolio visibility
     */
    public function toggleVisibility(Portfolio $portfolio): RedirectResponse
    {
        $portfolio->is_public = !$portfolio->is_public;
        $portfolio->save();

        $status = $portfolio->is_public ? 'public' : 'privé';

        return redirect()->back()
            ->with('success', "Portfolio défini comme {$status}");
    }

    /**
     * Bulk delete portfolios
     */
    public function bulkDelete(Request $request): RedirectResponse
    {
        $ids = json_decode($request->input('ids', '[]'));

        if (empty($ids)) {
            return redirect()->back()->with('error', 'Aucun portfolio sélectionné');
        }

        $portfolios = Portfolio::whereIn('id', $ids)->get();

        foreach ($portfolios as $portfolio) {
            // Delete files
            if ($portfolio->photo_url) {
                \Storage::disk('public')->delete(str_replace('/storage/', '', $portfolio->photo_url));
            }
            if ($portfolio->cv_url) {
                \Storage::disk('public')->delete(str_replace('/storage/', '', $portfolio->cv_url));
            }

            $portfolio->delete();
        }

        return redirect()->route('admin.portfolios.index')
            ->with('success', count($ids) . ' portfolio(s) supprimé(s) avec succès');
    }

    /**
     * Export portfolios statistics
     */
    public function export(Request $request)
    {
        $portfolios = Portfolio::with(['user', 'views'])->get();

        $csvData = "ID,Utilisateur,Email,Titre,Slug,Template,Public,Vues Totales,Créé le\n";

        foreach ($portfolios as $portfolio) {
            $csvData .= implode(',', [
                $portfolio->id,
                '"' . $portfolio->user->name . '"',
                $portfolio->user->email,
                '"' . $portfolio->title . '"',
                $portfolio->slug,
                $portfolio->template_id,
                $portfolio->is_public ? 'Oui' : 'Non',
                $portfolio->view_count,
                $portfolio->created_at->format('Y-m-d H:i:s'),
            ]) . "\n";
        }

        return response($csvData, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="portfolios_' . now()->format('Y-m-d') . '.csv"',
        ]);
    }
}
