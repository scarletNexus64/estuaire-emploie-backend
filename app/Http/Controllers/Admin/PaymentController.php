<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['user', 'company', 'payable'])
            ->latest();

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('transaction_reference', 'like', "%{$search}%")
                    ->orWhere('provider_reference', 'like', "%{$search}%")
                    ->orWhere('external_id', 'like', "%{$search}%")
                    ->orWhere('phone_number', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('company', function ($companyQuery) use ($search) {
                        $companyQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Filtre par statut
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filtre par provider (MTN/Orange)
        if ($request->filled('provider')) {
            $query->where('payment_method', $request->provider);
        }

        // Filtre par date
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $payments = $query->paginate(20)->withQueryString();

        // Statistiques
        $stats = [
            'total' => Payment::count(),
            'total_amount' => Payment::where('status', 'completed')->sum('total'),
            'completed' => Payment::where('status', 'completed')->count(),
            'pending' => Payment::where('status', 'pending')->count(),
            'failed' => Payment::where('status', 'failed')->count(),
        ];

        return view('admin.monetization.payments.index', compact('payments', 'stats'));
    }

    public function show(Payment $payment)
    {
        $payment->load(['user', 'company', 'payable', 'userSubscriptionPlan.subscriptionPlan']);

        return view('admin.monetization.payments.show', compact('payment'));
    }

    public function verify(Payment $payment)
    {
        if ($payment->status !== 'pending') {
            return redirect()->route('admin.payments.show', $payment)
                ->with('error', 'Ce paiement ne peut pas être vérifié.');
        }

        $payment->update([
            'status' => 'completed',
            'paid_at' => now(),
        ]);

        return redirect()->route('admin.payments.show', $payment)
            ->with('success', 'Paiement vérifié et marqué comme complété.');
    }

    public function refund(Payment $payment)
    {
        if ($payment->status !== 'completed') {
            return redirect()->route('admin.payments.show', $payment)
                ->with('error', 'Seuls les paiements complétés peuvent être remboursés.');
        }

        $payment->update([
            'status' => 'refunded',
            'refunded_at' => now(),
        ]);

        return redirect()->route('admin.payments.show', $payment)
            ->with('success', 'Paiement remboursé avec succès.');
    }

    public function export(Request $request): StreamedResponse
    {
        $query = Payment::with(['user', 'company', 'payable'])
            ->latest();

        // Appliquer les mêmes filtres que l'index
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('transaction_reference', 'like', "%{$search}%")
                    ->orWhere('provider_reference', 'like', "%{$search}%")
                    ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('provider')) {
            $query->where('payment_method', $request->provider);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $payments = $query->get();

        $filename = 'paiements_' . date('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($payments) {
            $file = fopen('php://output', 'w');

            // BOM pour UTF-8
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // En-têtes CSV
            fputcsv($file, [
                'ID',
                'Référence',
                'Référence Provider',
                'Utilisateur',
                'Email',
                'Entreprise',
                'Téléphone',
                'Méthode',
                'Montant',
                'Frais',
                'Total',
                'Statut',
                'Date de paiement',
                'Date de création',
            ], ';');

            // Données
            foreach ($payments as $payment) {
                fputcsv($file, [
                    $payment->id,
                    $payment->transaction_reference,
                    $payment->provider_reference,
                    $payment->user?->name ?? 'N/A',
                    $payment->user?->email ?? 'N/A',
                    $payment->company?->name ?? 'N/A',
                    $payment->phone_number,
                    $payment->payment_method,
                    number_format($payment->amount, 0, ',', ' '),
                    number_format($payment->fees ?? 0, 0, ',', ' '),
                    number_format($payment->total, 0, ',', ' '),
                    $payment->status,
                    $payment->paid_at?->format('d/m/Y H:i') ?? 'N/A',
                    $payment->created_at->format('d/m/Y H:i'),
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}