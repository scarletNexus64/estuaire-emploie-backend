<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index()
    {
        return view('admin.monetization.subscriptions.index');
    }

    public function show($id)
    {
        return view('admin.monetization.subscriptions.show');
    }

    public function cancel($id)
    {
        return redirect()->route('admin.subscriptions.index')
            ->with('success', 'Abonnement annulé avec succès');
    }

    public function activate($id)
    {
        return redirect()->route('admin.subscriptions.index')
            ->with('success', 'Abonnement activé avec succès');
    }

    public function destroy($id)
    {
        return redirect()->route('admin.subscriptions.index')
            ->with('success', 'Abonnement supprimé avec succès');
    }
}
