<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WalletTransaction;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WalletController extends Controller
{
    protected WalletService $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    /**
     * Liste tous les wallets des utilisateurs
     *
     * GET /admin/wallets
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $role = $request->input('role');
        $perPage = $request->input('per_page', 20);

        $query = User::query()
            ->select(['id', 'name', 'email', 'phone', 'role', 'wallet_balance', 'created_at'])
            ->orderBy('wallet_balance', 'desc');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($role) {
            $query->where('role', $role);
        }

        $users = $query->paginate($perPage);

        // Calculer les statistiques globales
        $totalBalance = User::sum('wallet_balance');
        $usersWithBalance = User::where('wallet_balance', '>', 0)->count();
        $totalUsers = User::count();

        return view('admin.wallets.index', compact(
            'users',
            'totalBalance',
            'usersWithBalance',
            'totalUsers',
            'search',
            'role'
        ));
    }

    /**
     * Affiche les détails du wallet d'un utilisateur
     *
     * GET /admin/wallets/{user}
     */
    public function show(User $user)
    {
        $stats = $this->walletService->getWalletStats($user);
        $transactions = $user->walletTransactions()
            ->with(['payment', 'admin'])
            ->paginate(20);

        return view('admin.wallets.show', compact('user', 'stats', 'transactions'));
    }

    /**
     * Formulaire d'ajustement du wallet
     *
     * GET /admin/wallets/{user}/adjust
     */
    public function adjustForm(User $user)
    {
        return view('admin.wallets.adjust', compact('user'));
    }

    /**
     * Effectue un ajustement du wallet
     *
     * POST /admin/wallets/{user}/adjust
     */
    public function adjust(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|not_in:0',
            'reason' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $amount = (float) $request->amount;
            $reason = $request->reason;
            $admin = $request->user();

            $transaction = $this->walletService->adjustBalance(
                $user,
                $amount,
                $admin,
                $reason
            );

            return redirect()
                ->route('admin.wallets.show', $user)
                ->with('success', 'Ajustement effectué avec succès');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Formulaire d'ajout de bonus
     *
     * GET /admin/wallets/{user}/bonus
     */
    public function bonusForm(User $user)
    {
        return view('admin.wallets.bonus', compact('user'));
    }

    /**
     * Ajoute un bonus au wallet
     *
     * POST /admin/wallets/{user}/bonus
     */
    public function bonus(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1',
            'description' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $amount = (float) $request->amount;
            $description = $request->description;
            $admin = $request->user();

            $transaction = $this->walletService->addBonus(
                $user,
                $amount,
                $description,
                [
                    'admin_id' => $admin->id,
                    'admin_name' => $admin->name,
                ]
            );

            return redirect()
                ->route('admin.wallets.show', $user)
                ->with('success', 'Bonus ajouté avec succès');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Affiche toutes les transactions (tous utilisateurs)
     *
     * GET /admin/wallets/transactions
     */
    public function transactions(Request $request)
    {
        $type = $request->input('type');
        $userId = $request->input('user_id');
        $perPage = $request->input('per_page', 50);

        $query = WalletTransaction::query()
            ->with(['user', 'payment', 'admin'])
            ->orderBy('created_at', 'desc');

        if ($type) {
            $query->where('type', $type);
        }

        if ($userId) {
            $query->where('user_id', $userId);
        }

        $transactions = $query->paginate($perPage);

        // Statistiques des transactions
        $totalCredits = WalletTransaction::completed()->credits()->sum('amount');
        $totalDebits = abs(WalletTransaction::completed()->debits()->sum('amount'));
        $transactionCount = WalletTransaction::count();

        return view('admin.wallets.transactions', compact(
            'transactions',
            'totalCredits',
            'totalDebits',
            'transactionCount',
            'type',
            'userId'
        ));
    }

    /**
     * Effectue un remboursement
     *
     * POST /admin/wallets/transactions/{transaction}/refund
     */
    public function refund(Request $request, WalletTransaction $transaction)
    {
        $validator = Validator::make($request->all(), [
            'reason' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator);
        }

        try {
            $reason = $request->reason;
            $refundTransaction = $this->walletService->refund($transaction, $reason);

            return redirect()
                ->back()
                ->with('success', 'Remboursement effectué avec succès');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }
}
