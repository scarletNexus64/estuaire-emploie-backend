@extends('admin.layouts.app')

@section('title', 'Compte Bancaire')
@section('page-title', 'Compte Bancaire - Transactions')

@section('content')
    <!-- Total Général Card -->
    <div class="card" style="margin-bottom: 2rem;">
        <div style="padding: 2rem; text-align: center; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 16px;">
            <h3 style="color: white; font-size: 1rem; margin-bottom: 0.5rem; opacity: 0.9;">Total Général</h3>
            <h1 style="color: white; font-size: 3rem; font-weight: 800; margin: 0;">{{ number_format($grandTotal, 0, ',', ' ') }} <span style="font-size: 1.5rem; opacity: 0.8;">XAF</span></h1>
        </div>
    </div>

    <!-- PayPal Transactions -->
    <div class="card" style="margin-bottom: 2rem;">
        <div class="card-header" style="background: linear-gradient(135deg, #0070ba 0%, #1546a0 100%); color: white; padding: 1.5rem; border-radius: 16px 16px 0 0;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <i class="fab fa-paypal" style="font-size: 2rem;"></i>
                    <div>
                        <h3 style="margin: 0; color: white;">Transactions PayPal</h3>
                        <p style="margin: 0; opacity: 0.9; font-size: 0.875rem;">{{ count($paypalTransactions) }} transaction(s)</p>
                    </div>
                </div>
                <div style="text-align: right;">
                    <p style="margin: 0; opacity: 0.8; font-size: 0.875rem;">Total</p>
                    <h3 style="margin: 0; color: white; font-weight: 700;">{{ number_format($paypalTotal, 0, ',', ' ') }} XAF</h3>
                </div>
            </div>
        </div>

        @if(count($paypalTransactions) > 0)
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Montant</th>
                        <th>Type</th>
                        <th>Référence</th>
                        <th>Utilisateur</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($paypalTransactions as $transaction)
                    <tr>
                        <td><strong>#{{ $transaction['id'] }}</strong></td>
                        <td>
                            <span class="badge badge-success" style="font-size: 0.875rem; padding: 0.5rem 1rem;">
                                {{ number_format($transaction['amount'], 0, ',', ' ') }} XAF
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-info">{{ ucfirst($transaction['payment_type'] ?? 'N/A') }}</span>
                        </td>
                        <td><code style="color: #0070ba; font-weight: 600;">{{ $transaction['transaction_reference'] }}</code></td>
                        <td>
                            <i class="fas fa-user" style="color: var(--secondary); margin-right: 0.5rem;"></i>
                            {{ $transaction['user_name'] }}
                        </td>
                        <td>
                            <i class="fas fa-calendar-alt" style="color: var(--secondary); margin-right: 0.5rem;"></i>
                            {{ $transaction['created_at'] }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); font-weight: 700;">
                        <td colspan="5" style="text-align: right; padding: 1.25rem; font-size: 1.125rem;">Total PayPal:</td>
                        <td style="color: var(--success); font-size: 1.125rem; padding: 1.25rem;">{{ number_format($paypalTotal, 0, ',', ' ') }} XAF</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @else
        <div style="text-align: center; padding: 4rem 2rem;">
            <i class="fab fa-paypal" style="font-size: 4rem; color: var(--secondary); opacity: 0.3; margin-bottom: 1rem;"></i>
            <p style="color: var(--secondary); font-size: 1.125rem;">Aucune transaction PayPal pour le moment</p>
        </div>
        @endif
    </div>

    <!-- FreeMoPay Transactions -->
    <div class="card">
        <div class="card-header" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; padding: 1.5rem; border-radius: 16px 16px 0 0;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <i class="fas fa-mobile-alt" style="font-size: 2rem;"></i>
                    <div>
                        <h3 style="margin: 0; color: white;">Transactions FreeMoPay</h3>
                        <p style="margin: 0; opacity: 0.9; font-size: 0.875rem;">{{ count($freemopayTransactions) }} transaction(s)</p>
                    </div>
                </div>
                <div style="text-align: right;">
                    <p style="margin: 0; opacity: 0.8; font-size: 0.875rem;">Total</p>
                    <h3 style="margin: 0; color: white; font-weight: 700;">{{ number_format($freemopayTotal, 0, ',', ' ') }} XAF</h3>
                </div>
            </div>
        </div>

        @if(count($freemopayTransactions) > 0)
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Montant</th>
                        <th>Type</th>
                        <th>Référence</th>
                        <th>Utilisateur</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($freemopayTransactions as $transaction)
                    <tr>
                        <td><strong>#{{ $transaction['id'] }}</strong></td>
                        <td>
                            <span class="badge badge-success" style="font-size: 0.875rem; padding: 0.5rem 1rem;">
                                {{ number_format($transaction['amount'], 0, ',', ' ') }} XAF
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-info">{{ ucfirst($transaction['payment_type'] ?? 'N/A') }}</span>
                        </td>
                        <td><code style="color: #f59e0b; font-weight: 600;">{{ $transaction['transaction_reference'] }}</code></td>
                        <td>
                            <i class="fas fa-user" style="color: var(--secondary); margin-right: 0.5rem;"></i>
                            {{ $transaction['user_name'] }}
                        </td>
                        <td>
                            <i class="fas fa-calendar-alt" style="color: var(--secondary); margin-right: 0.5rem;"></i>
                            {{ $transaction['created_at'] }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); font-weight: 700;">
                        <td colspan="5" style="text-align: right; padding: 1.25rem; font-size: 1.125rem;">Total FreeMoPay:</td>
                        <td style="color: var(--success); font-size: 1.125rem; padding: 1.25rem;">{{ number_format($freemopayTotal, 0, ',', ' ') }} XAF</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @else
        <div style="text-align: center; padding: 4rem 2rem;">
            <i class="fas fa-mobile-alt" style="font-size: 4rem; color: var(--secondary); opacity: 0.3; margin-bottom: 1rem;"></i>
            <p style="color: var(--secondary); font-size: 1.125rem;">Aucune transaction FreeMoPay pour le moment</p>
        </div>
        @endif
    </div>
@endsection
