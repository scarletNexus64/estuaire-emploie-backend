<?php

namespace App\Http\Controllers;

use App\Models\PlatformSetting;
use App\Services\InvestorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class InvestorController extends Controller
{
    protected InvestorService $investorService;

    public function __construct(InvestorService $investorService)
    {
        $this->investorService = $investorService;
    }

    /**
     * Afficher le dashboard de l'investisseur
     */
    public function dashboard()
    {
        $investor = Auth::user();

        if (!$investor->isInvestor()) {
            abort(403, 'Accès non autorisé');
        }

        $stats = $this->investorService->getInvestorDashboardStats($investor);

        $recentTransactions = $investor->investorTransactions()
            ->latest()
            ->limit(5)
            ->get()
            ->map(fn ($t) => [
                'id' => $t->id,
                'type' => $t->type,
                'amount' => (float) $t->amount,
                'amount_formatted' => $t->amount_formatted,
                'status' => $t->status,
                'reason' => $t->reason,
                'created_at' => $t->created_at->format('d/m/Y H:i'),
            ]);

        $maintenanceFund = $this->investorService->getOrCreateMaintenanceFund();

        return Inertia::render('Investor/Dashboard', [
            'stats' => [
                'percentage_share' => (float) $stats['percentage_share'],
                'balance' => (float) $stats['balance'],
                'investment_amount' => (float) $stats['investment_amount'],
                'deposits' => (float) $stats['deposits'],
                'withdrawals' => (float) $stats['withdrawals'],
                'earnings' => (float) $stats['earnings'],
                'chart_data' => $stats['chart_data'],
            ],
            'recentTransactions' => $recentTransactions,
            'maintenanceFund' => [
                'current_balance' => (float) $maintenanceFund->current_balance,
                'needed_amount' => (float) $maintenanceFund->needed_amount,
                'remaining_amount' => (float) $maintenanceFund->getRemainingAmount(),
                'is_active' => $maintenanceFund->is_active,
                'reason' => $maintenanceFund->reason,
            ],
        ]);
    }

    /**
     * Afficher le formulaire de dépôt
     */
    public function showDepositForm()
    {
        $investor = Auth::user();

        if (!$investor->isInvestor()) {
            abort(403, 'Accès non autorisé');
        }

        $maintenanceFund = $this->investorService->getOrCreateMaintenanceFund();
        $canDeposit = $maintenanceFund->is_active && $maintenanceFund->getRemainingAmount() > 0;
        $maxDeposit = $maintenanceFund->getRemainingAmount();

        return Inertia::render('Investor/Deposit', [
            'canDeposit' => $canDeposit,
            'maxDeposit' => (float) $maxDeposit,
            'isActive' => $maintenanceFund->is_active,
            'reason' => $maintenanceFund->reason,
            'neededAmount' => (float) $maintenanceFund->needed_amount,
            'currentBalance' => (float) $maintenanceFund->current_balance,
            'userPhone' => $investor->phone,
        ]);
    }

    /**
     * Effectuer un dépôt
     */
    public function deposit(Request $request)
    {
        $investor = Auth::user();

        if (!$investor->isInvestor()) {
            abort(403, 'Accès non autorisé');
        }

        $request->validate([
            'amount' => 'required|numeric|min:100',
        ]);

        $maintenanceFund = $this->investorService->getOrCreateMaintenanceFund();
        if (!$maintenanceFund->is_active) {
            return response()->json(['success' => false, 'message' => 'Les dépôts sont actuellement désactivés']);
        }

        $maxDeposit = $maintenanceFund->getRemainingAmount();
        if ($request->amount > $maxDeposit) {
            return response()->json(['success' => false, 'message' => "Le montant maximal pouvant être déposé est de {$maxDeposit} FCFA"]);
        }

        try {
            $transaction = $this->investorService->makeDeposit($investor, $request->amount, null, $request->otp_code);

            // Check if OTP is required
            if ($transaction->status === 'pending_otp') {
                return response()->json(['requires_otp' => true, 'message' => 'Code OTP requis pour confirmer le dépôt']);
            }

            return response()->json([
                'success' => true,
                'message' => 'Dépôt effectué avec succès',
                'transaction_id' => $transaction->id
            ]);
        } catch (\Exception $e) {
            // Check if the exception is for OTP required
            if ($e->getMessage() === 'OTP_REQUIRED') {
                return response()->json(['requires_otp' => true, 'message' => 'Code OTP requis pour confirmer le dépôt']);
            }

            // Check if the exception is for missing phone number
            if (str_contains($e->getMessage(), 'Numéro de téléphone requis')) {
                return response()->json(['requires_phone' => true, 'message' => $e->getMessage()]);
            }

            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Afficher le formulaire de retrait
     */
    public function showWithdrawalForm()
    {
        $investor = Auth::user();

        if (!$investor->isInvestor()) {
            abort(403, 'Accès non autorisé');
        }

        $balance = $investor->getInvestorBalance();
        $minWithdrawal = PlatformSetting::investorMinWithdrawal();
        $canWithdraw = $balance >= $minWithdrawal;

        return Inertia::render('Investor/Withdrawal', [
            'canWithdraw' => $canWithdraw,
            'balance' => (float) $balance,
            'minWithdrawal' => (float) $minWithdrawal,
            'userPhone' => $investor->phone,
        ]);
    }

    /**
     * Effectuer un retrait
     */
    public function withdrawal(Request $request)
    {
        $investor = Auth::user();

        if (!$investor->isInvestor()) {
            abort(403, 'Accès non autorisé');
        }

        $request->validate([
            'amount' => 'required|numeric|min:' . PlatformSetting::investorMinWithdrawal(),
            'reason' => 'required|string|max:500',
        ]);

        $balance = $investor->getInvestorBalance();
        if ($request->amount > $balance) {
            return redirect()->back()->withErrors(['amount' => 'Solde insuffisant']);
        }

        try {
            $this->investorService->makeWithdrawal($investor, $request->amount, $request->reason);

            return redirect()->route('investor.dashboard')->with('success', 'Retrait demandé avec succès');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Initiate a deposit (async flow)
     */
    public function initiateDeposit(Request $request)
    {
        $investor = Auth::user();

        if (!$investor->isInvestor()) {
            abort(403, 'Accès non autorisé');
        }

        $request->validate([
            'amount' => 'required|numeric|min:100',
            'phone' => 'required|string',
            'save_phone' => 'boolean',
        ]);

        $maintenanceFund = $this->investorService->getOrCreateMaintenanceFund();
        if (!$maintenanceFund->is_active) {
            return response()->json(['success' => false, 'message' => 'Les dépôts sont actuellement désactivés']);
        }

        $maxDeposit = $maintenanceFund->getRemainingAmount();
        if ($request->amount > $maxDeposit) {
            return response()->json(['success' => false, 'message' => "Le montant maximal pouvant être déposé est de {$maxDeposit} FCFA"]);
        }

        $phone = preg_replace('/\D/', '', $request->phone);

        // Save phone if requested
        if ($request->save_phone && $phone) {
            $phoneToSave = str_starts_with($phone, '237') ? $phone : '237' . $phone;
            $investor->update(['phone' => $phoneToSave]);
        }

        try {
            $transaction = $this->investorService->createPendingDeposit($investor, $request->amount, $phone);

            // Initiate FreeMoPay payment immediately (don't defer to polling)
            $this->investorService->initiateDepositPayment($transaction);
            $transaction->refresh();

            return response()->json([
                'success' => true,
                'transaction_id' => $transaction->id,
                'reference' => $transaction->freemopay_reference,
                'transaction_reference' => $transaction->transaction_reference,
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Check deposit payment status (polling endpoint)
     */
    public function checkDepositStatus(Request $request, int $id)
    {
        $investor = Auth::user();

        if (!$investor->isInvestor()) {
            abort(403, 'Accès non autorisé');
        }

        $transaction = \App\Models\InvestorTransaction::where('id', $id)
            ->where('investor_id', $investor->id)
            ->where('type', 'deposit')
            ->firstOrFail();

        $result = $this->investorService->checkDepositPaymentStatus($transaction);

        return response()->json($result);
    }

    /**
     * Initiate a withdrawal (async flow)
     */
    public function initiateWithdrawal(Request $request)
    {
        $investor = Auth::user();

        if (!$investor->isInvestor()) {
            abort(403, 'Accès non autorisé');
        }

        $request->validate([
            'amount' => 'required|numeric|min:' . PlatformSetting::investorMinWithdrawal(),
            'reason' => 'required|string|max:500',
            'phone' => 'required|string',
            'save_phone' => 'boolean',
        ]);

        $balance = $investor->getInvestorBalance();
        if ($request->amount > $balance) {
            return response()->json(['success' => false, 'message' => 'Solde insuffisant']);
        }

        $phone = preg_replace('/\D/', '', $request->phone);

        // Save phone if requested
        if ($request->save_phone && $phone) {
            $phoneToSave = str_starts_with($phone, '237') ? $phone : '237' . $phone;
            $investor->update(['phone' => $phoneToSave]);
        }

        try {
            $transaction = $this->investorService->createAndInitiateWithdrawal(
                $investor,
                $request->amount,
                $request->reason,
                $phone
            );

            return response()->json([
                'success' => true,
                'transaction_id' => $transaction->id,
                'reference' => $transaction->freemopay_reference,
                'transaction_reference' => $transaction->transaction_reference,
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Check withdrawal payment status (polling endpoint)
     */
    public function checkWithdrawalStatus(Request $request, int $id)
    {
        $investor = Auth::user();

        if (!$investor->isInvestor()) {
            abort(403, 'Accès non autorisé');
        }

        $transaction = \App\Models\InvestorTransaction::where('id', $id)
            ->where('investor_id', $investor->id)
            ->where('type', 'withdrawal')
            ->firstOrFail();

        $result = $this->investorService->checkWithdrawalPaymentStatus($transaction);

        return response()->json($result);
    }

    /**
     * Afficher l'historique des transactions
     */
    public function transactions(Request $request)
    {
        $investor = Auth::user();

        if (!$investor->isInvestor()) {
            abort(403, 'Accès non autorisé');
        }

        $query = $investor->investorTransactions()->latest();

        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        $transactions = $query->paginate(15)->through(fn ($t) => [
            'id' => $t->id,
            'type' => $t->type,
            'amount' => (float) $t->amount,
            'amount_formatted' => $t->amount_formatted,
            'status' => $t->status,
            'reason' => $t->reason,
            'created_at' => $t->created_at->format('d/m/Y H:i'),
        ]);

        return Inertia::render('Investor/Transactions', [
            'transactions' => $transactions,
            'filters' => [
                'type' => $request->get('type', 'all'),
            ],
        ]);
    }
}
