<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PortfolioViewController extends Controller
{
    /**
     * Display a portfolio by slug
     */
    public function show(Request $request, string $slug): View
    {
        $portfolio = Portfolio::with('user:id,name,email')
            ->where('slug', $slug)
            ->where('is_public', true)
            ->firstOrFail();

        // Record view
        $portfolio->recordView(
            auth()->id(),
            $request->ip(),
            $request->userAgent(),
            $request->header('referer')
        );

        // Select template view based on portfolio template_id
        $template = match($portfolio->template_id) {
            'professional' => 'portfolio.templates.professional',
            'creative' => 'portfolio.templates.creative',
            'tech' => 'portfolio.templates.tech',
            default => 'portfolio.templates.professional',
        };

        return view($template, compact('portfolio'));
    }
}
