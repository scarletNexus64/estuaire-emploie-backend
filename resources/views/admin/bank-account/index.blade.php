@extends('admin.layouts.app')

@section('title', 'Compte Bancaire - Tableau de Bord')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0"><i class="fas fa-university me-2 text-primary"></i>Compte Bancaire</h2>
            <p class="text-muted mb-0">Tableau de bord des revenus et retraits de la plateforme</p>
        </div>
        <div>
            <a href="{{ route('admin.bank-account.history') }}" class="btn btn-outline-primary me-2">
                <i class="fas fa-history me-2"></i>Historique Complet
            </a>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#pinModal">
                <i class="fas fa-money-bill-wave me-2"></i>Effectuer un Retrait
            </button>
        </div>
    </div>

    <!-- Main Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="stat-icon bg-primary-soft">
                                <i class="fas fa-chart-line text-primary"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted mb-1 small">Revenus Totaux</p>
                            <h4 class="mb-0 fw-bold">{{ number_format($stats['total_revenue'], 0, ',', ' ') }}</h4>
                            <small class="text-success"><i class="fas fa-arrow-up"></i> Subscriptions + Services</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="stat-icon bg-success-soft">
                                <i class="fas fa-wallet text-success"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted mb-1 small">Solde Disponible</p>
                            <h4 class="mb-0 fw-bold text-success">{{ number_format($stats['available_balance'], 0, ',', ' ') }}</h4>
                            <small class="text-muted">XAF</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="stat-icon bg-warning-soft">
                                <i class="fas fa-arrow-down text-warning"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted mb-1 small">Total Retiré</p>
                            <h4 class="mb-0 fw-bold">{{ number_format($stats['total_withdrawn'], 0, ',', ' ') }}</h4>
                            <small class="text-muted">{{ $stats['completed_withdrawals'] }} retrait(s)</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="stat-icon bg-info-soft">
                                <i class="fas fa-percentage text-info"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted mb-1 small">Taux de Retrait</p>
                            <h4 class="mb-0 fw-bold">
                                {{ $stats['total_revenue'] > 0 ? number_format(($stats['total_withdrawn'] / $stats['total_revenue']) * 100, 1) : 0 }}%
                            </h4>
                            <small class="text-muted">Des revenus totaux</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-4 mb-4">
        <!-- Revenue Trend Chart -->
        <div class="col-xl-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0"><i class="fas fa-chart-area me-2 text-primary"></i>Tendance des Revenus (6 derniers mois)</h5>
                </div>
                <div class="card-body">
                    <div style="height: 300px;">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Revenue Breakdown Pie Chart -->
        <div class="col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0"><i class="fas fa-chart-pie me-2 text-primary"></i>Répartition des Revenus</h5>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center p-4">
                    <div style="max-width: 280px; max-height: 280px;">
                        <canvas id="revenueBreakdownChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue Details Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm border-start border-primary border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Subscriptions</p>
                            <h4 class="mb-0">{{ number_format($revenueBreakdown['subscription']['total'] ?? 0, 0, ',', ' ') }} XAF</h4>
                            <small class="text-muted">{{ $revenueBreakdown['subscription']['count'] ?? 0 }} paiement(s)</small>
                        </div>
                        <i class="fas fa-crown fa-2x text-primary opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm border-start border-info border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Autres Revenus</p>
                            <h4 class="mb-0">{{ number_format($revenueBreakdown['other']['total'] ?? 0, 0, ',', ' ') }} XAF</h4>
                            <small class="text-muted">{{ $revenueBreakdown['other']['count'] ?? 0 }} transaction(s)</small>
                        </div>
                        <i class="fas fa-coins fa-2x text-info opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm border-start border-secondary border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Recharges Wallet</p>
                            <h4 class="mb-0 text-muted">{{ number_format($revenueBreakdown['wallet_recharge']['total'] ?? 0, 0, ',', ' ') }} XAF</h4>
                            <small class="text-danger"><i class="fas fa-info-circle"></i> Non comptabilisé</small>
                        </div>
                        <i class="fas fa-wallet fa-2x text-secondary opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Withdrawals -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-history me-2 text-primary"></i>Retraits Récents</h5>
                <span class="badge bg-primary">{{ count($recentWithdrawals) }} dernier(s)</span>
            </div>
        </div>
        <div class="card-body p-0">
            @if(count($recentWithdrawals) > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0">Référence</th>
                            <th class="border-0">Montant</th>
                            <th class="border-0">Méthode</th>
                            <th class="border-0">Compte</th>
                            <th class="border-0">Admin</th>
                            <th class="border-0">Statut</th>
                            <th class="border-0">Date</th>
                            <th class="border-0">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentWithdrawals as $withdrawal)
                        <tr>
                            <td><strong>#{{ $withdrawal['id'] }}</strong></td>
                            <td>
                                <strong class="text-success">{{ number_format($withdrawal['amount'], 0, ',', ' ') }} XAF</strong>
                            </td>
                            <td>
                                @if($withdrawal['payment_method'] === 'om')
                                    <span class="badge bg-warning text-dark"><i class="fas fa-mobile-alt"></i> Orange Money</span>
                                @else
                                    <span class="badge bg-primary"><i class="fas fa-mobile-alt"></i> MTN MoMo</span>
                                @endif
                            </td>
                            <td><code>{{ $withdrawal['payment_account'] }}</code></td>
                            <td>
                                <i class="fas fa-user-shield text-muted me-1"></i>
                                {{ $withdrawal['admin_name'] }}
                            </td>
                            <td>
                                @if($withdrawal['status'] === 'completed')
                                    <span class="badge bg-success"><i class="fas fa-check-circle"></i> Complété</span>
                                @elseif($withdrawal['status'] === 'processing')
                                    <span class="badge bg-info"><i class="fas fa-spinner fa-spin"></i> En cours</span>
                                @elseif($withdrawal['status'] === 'pending')
                                    <span class="badge bg-warning"><i class="fas fa-clock"></i> En attente</span>
                                @else
                                    <span class="badge bg-danger"><i class="fas fa-times-circle"></i> Échoué</span>
                                @endif
                            </td>
                            <td>
                                <small class="text-muted">
                                    <i class="fas fa-calendar-alt me-1"></i>{{ $withdrawal['created_at'] }}
                                </small>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary" onclick="viewWithdrawalDetails({{ $withdrawal['id'] }})">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                <p class="text-muted">Aucun retrait effectué pour le moment</p>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- PIN Modal -->
<div class="modal fade" id="pinModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title"><i class="fas fa-lock me-2"></i>Vérification Sécurisée</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mb-4">
                    <i class="fas fa-shield-alt fa-3x text-primary"></i>
                </div>
                <p class="text-muted mb-4">Entrez votre code PIN à 4 chiffres pour accéder aux fonctionnalités de retrait</p>

                <form id="pinForm">
                    @csrf
                    <div class="mb-4">
                        <input type="password"
                               class="form-control form-control-lg text-center fw-bold"
                               id="pinInput"
                               name="pin"
                               maxlength="4"
                               placeholder="• • • •"
                               style="letter-spacing: 15px; font-size: 28px;"
                               required>
                        <div class="invalid-feedback" id="pinError"></div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg px-5">
                        <i class="fas fa-unlock me-2"></i>Déverrouiller
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('styles')
<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
.stat-card {
    transition: transform 0.2s, box-shadow 0.2s;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15) !important;
}

.stat-icon {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    font-size: 1.5rem;
}

.bg-primary-soft {
    background-color: rgba(13, 110, 253, 0.1);
}

.bg-success-soft {
    background-color: rgba(25, 135, 84, 0.1);
}

.bg-warning-soft {
    background-color: rgba(255, 193, 7, 0.1);
}

.bg-info-soft {
    background-color: rgba(13, 202, 240, 0.1);
}

.table thead th {
    font-weight: 600;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.card-header h5 {
    font-weight: 600;
}
</style>
@endpush

@push('scripts')
<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// Revenue Trend Chart (Line Chart)
const revenueTrendData = @json($monthlyStats);
const revenueLabels = revenueTrendData.map(item => {
    const [year, month] = item.month.split('-');
    const date = new Date(year, month - 1);
    return date.toLocaleDateString('fr-FR', { month: 'short', year: 'numeric' });
});
const revenueValues = revenueTrendData.map(item => parseFloat(item.total));

const revenueCtx = document.getElementById('revenueChart').getContext('2d');
new Chart(revenueCtx, {
    type: 'line',
    data: {
        labels: revenueLabels,
        datasets: [{
            label: 'Revenus (XAF)',
            data: revenueValues,
            borderColor: 'rgb(13, 110, 253)',
            backgroundColor: 'rgba(13, 110, 253, 0.1)',
            fill: true,
            tension: 0.4,
            pointRadius: 5,
            pointHoverRadius: 7,
            pointBackgroundColor: 'rgb(13, 110, 253)',
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                padding: 12,
                titleFont: {
                    size: 14
                },
                bodyFont: {
                    size: 13
                },
                callbacks: {
                    label: function(context) {
                        return 'Revenus: ' + context.parsed.y.toLocaleString('fr-FR') + ' XAF';
                    }
                }
            }
        },
        scales: {
            x: {
                grid: {
                    display: false
                }
            },
            y: {
                beginAtZero: true,
                grid: {
                    color: 'rgba(0, 0, 0, 0.05)'
                },
                ticks: {
                    callback: function(value) {
                        return (value / 1000) + 'K';
                    }
                }
            }
        }
    }
});

// Revenue Breakdown Chart (Doughnut Chart)
const revenueBreakdown = @json($revenueBreakdown);
const breakdownLabels = [];
const breakdownValues = [];
const breakdownColors = [];

const colorMap = {
    'subscription': { color: 'rgb(13, 110, 253)', label: 'Subscriptions' },
    'other': { color: 'rgb(13, 202, 240)', label: 'Autres Revenus' },
    'wallet_recharge': { color: 'rgb(108, 117, 125)', label: 'Recharges Wallet (exclus)' }
};

Object.keys(revenueBreakdown).forEach(type => {
    const config = colorMap[type] || { color: 'rgb(25, 135, 84)', label: type };
    breakdownLabels.push(config.label);
    breakdownValues.push(revenueBreakdown[type].total);
    breakdownColors.push(config.color);
});

const breakdownCtx = document.getElementById('revenueBreakdownChart').getContext('2d');
new Chart(breakdownCtx, {
    type: 'doughnut',
    data: {
        labels: breakdownLabels,
        datasets: [{
            data: breakdownValues,
            backgroundColor: breakdownColors,
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 15,
                    usePointStyle: true
                }
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                        const percentage = ((context.parsed / total) * 100).toFixed(1);
                        return context.label + ': ' + context.parsed.toLocaleString('fr-FR') + ' XAF (' + percentage + '%)';
                    }
                }
            }
        }
    }
});

// PIN Form Handler
document.addEventListener('DOMContentLoaded', function() {
    const pinModalEl = document.getElementById('pinModal');
    const pinForm = document.getElementById('pinForm');
    const pinInput = document.getElementById('pinInput');
    const pinError = document.getElementById('pinError');

    if (!pinForm || !pinInput) {
        console.error('PIN form elements not found');
        return;
    }

    // Reset form when modal opens
    pinModalEl.addEventListener('show.bs.modal', function() {
        pinForm.reset();
        pinInput.classList.remove('is-invalid');
        pinError.textContent = '';
    });

    // Handle form submission
    pinForm.addEventListener('submit', async function(e) {
        e.preventDefault();

        const pin = pinInput.value.trim();

        if (pin.length !== 4) {
            pinInput.classList.add('is-invalid');
            pinError.textContent = 'Le code PIN doit contenir 4 chiffres';
            return;
        }

        // Disable submit button
        const submitBtn = pinForm.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Vérification...';

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (!csrfToken) {
                throw new Error('CSRF token not found');
            }

            console.log('Sending PIN verification request...');
            const response = await fetch('{{ route("admin.bank-account.verify-pin") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken.content,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ pin: pin })
            });

            console.log('Response status:', response.status);

            // Parse JSON response regardless of status
            const data = await response.json();
            console.log('Response data:', data);

            if (response.ok && data.success) {
                // Close modal
                if (typeof window.bootstrap !== 'undefined') {
                    // Use Bootstrap API if available
                    const modalInstance = window.bootstrap.Modal.getInstance(pinModalEl) || new window.bootstrap.Modal(pinModalEl);
                    modalInstance.hide();
                } else {
                    // Fallback: close manually
                    pinModalEl.classList.remove('show');
                    pinModalEl.style.display = 'none';
                    document.body.classList.remove('modal-open');
                    const backdrop = document.querySelector('.modal-backdrop');
                    if (backdrop) backdrop.remove();
                }

                // Redirect after modal is hidden
                setTimeout(() => {
                    window.location.href = '{{ route("admin.bank-account.withdrawal") }}';
                }, 300);
            } else {
                // Handle error
                pinInput.classList.add('is-invalid');
                pinError.textContent = data.message || 'Code PIN incorrect';
                pinInput.value = '';
                pinInput.focus();

                // Re-enable button
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-unlock me-2"></i>Déverrouiller';
            }
        } catch (error) {
            console.error('Fetch error:', error);
            console.error('Error details:', error.message);

            pinInput.classList.add('is-invalid');
            pinError.textContent = 'Erreur de connexion: ' + error.message;

            // Re-enable button
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-unlock me-2"></i>Déverrouiller';
        }
    });

    // Clear error on input
    pinInput.addEventListener('input', function() {
        pinInput.classList.remove('is-invalid');
        pinError.textContent = '';
    });

    // Allow only numbers
    pinInput.addEventListener('keypress', function(e) {
        if (!/[0-9]/.test(e.key)) {
            e.preventDefault();
        }
    });
});

function viewWithdrawalDetails(id) {
    // TODO: Implement withdrawal details modal
    alert('Détails du retrait #' + id);
}
</script>
@endpush
@endsection
