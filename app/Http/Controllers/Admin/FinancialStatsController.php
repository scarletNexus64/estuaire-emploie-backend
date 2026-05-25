<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FinancialStatsController extends Controller
{
    public function index()
    {
        return view('admin.monetization.financial-stats.index');
    }

    public function export()
    {
        return redirect()->route('admin.financial-stats.index')
            ->with('success', 'Export des statistiques en cours...');
    }
}
