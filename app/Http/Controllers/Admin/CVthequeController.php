<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CVthequeController extends Controller
{
    public function index()
    {
        return view('admin.monetization.cvtheque.index');
    }

    public function show($id)
    {
        return view('admin.monetization.cvtheque.show');
    }

    public function export()
    {
        return redirect()->route('admin.cvtheque.index')
            ->with('success', 'Export en cours...');
    }
}
