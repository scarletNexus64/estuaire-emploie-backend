@extends('admin.layouts.app')

@section('title', 'Statistiques - ' . $promotion->name)

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm text-gray-600 mb-2">
            <a href="{{ route('admin.pack-promotions.index') }}" class="hover:text-primary">Promotions</a>
            <i class="fas fa-chevron-right text-xs"></i>
            <span>Statistiques</span>
        </div>
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">{{ $promotion->name }}</h1>
                <p class="text-gray-600 mt-1">Statistiques et activations</p>
            </div>
            <a href="{{ route('admin.pack-promotions.edit', $promotion->id) }}"
               class="bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-lg transition-colors">
                <i class="fas fa-edit mr-2"></i>Modifier
            </a>
        </div>
    </div>

    <!-- Info promotion -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <div class="text-sm text-gray-600 mb-1">Pack cible</div>
                <div class="font-semibold text-gray-900">{{ $promotion->pack_name }}</div>
                <div class="text-xs text-gray-500">{{ $promotion->pack_type_name }}</div>
            </div>
            <div>
                <div class="text-sm text-gray-600 mb-1">Période</div>
                <div class="font-semibold text-gray-900">
                    {{ $promotion->start_date->format('d/m/Y H:i') }}
                </div>
                <div class="text-xs text-gray-500">
                    → {{ $promotion->end_date->format('d/m/Y H:i') }}
                </div>
            </div>
            <div>
                <div class="text-sm text-gray-600 mb-1">Statut</div>
                @php
                    $statusColors = [
                        'active' => 'bg-green-100 text-green-800',
                        'inactive' => 'bg-gray-100 text-gray-800',
                        'expired' => 'bg-red-100 text-red-800',
                        'scheduled' => 'bg-blue-100 text-blue-800',
                        'full' => 'bg-orange-100 text-orange-800',
                    ];
                    $statusLabels = [
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'expired' => 'Expirée',
                        'scheduled' => 'Programmée',
                        'full' => 'Complète',
                    ];
                @endphp
                <span class="inline-block px-3 py-1 text-sm font-semibold rounded {{ $statusColors[$promotion->status] ?? 'bg-gray-100' }}">
                    {{ $statusLabels[$promotion->status] ?? $promotion->status }}
                </span>
            </div>
        </div>
    </div>

    <!-- Statistiques principales -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <!-- Total activations -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-start mb-4">
                <div class="text-gray-600 text-sm font-medium">Total Activations</div>
                <div class="bg-blue-100 p-2 rounded-lg">
                    <i class="fas fa-users text-blue-600"></i>
                </div>
            </div>
            <div class="text-3xl font-bold text-gray-900">{{ $totalActivations }}</div>
            @if($promotion->max_activations)
                <div class="text-sm text-gray-500 mt-1">
                    / {{ $promotion->max_activations }} max
                </div>
            @endif
        </div>

        <!-- Activations actives -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-start mb-4">
                <div class="text-gray-600 text-sm font-medium">Actives</div>
                <div class="bg-green-100 p-2 rounded-lg">
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
            </div>
            <div class="text-3xl font-bold text-green-600">{{ $activeActivations }}</div>
            <div class="text-sm text-gray-500 mt-1">
                {{ $totalActivations > 0 ? round(($activeActivations / $totalActivations) * 100) : 0 }}% du total
            </div>
        </div>

        <!-- Activations expirées -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-start mb-4">
                <div class="text-gray-600 text-sm font-medium">Expirées</div>
                <div class="bg-red-100 p-2 rounded-lg">
                    <i class="fas fa-times-circle text-red-600"></i>
                </div>
            </div>
            <div class="text-3xl font-bold text-red-600">{{ $expiredActivations }}</div>
            <div class="text-sm text-gray-500 mt-1">
                {{ $totalActivations > 0 ? round(($expiredActivations / $totalActivations) * 100) : 0 }}% du total
            </div>
        </div>

        <!-- Jours restants -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-start mb-4">
                <div class="text-gray-600 text-sm font-medium">Jours restants</div>
                <div class="bg-orange-100 p-2 rounded-lg">
                    <i class="fas fa-clock text-orange-600"></i>
                </div>
            </div>
            <div class="text-3xl font-bold text-orange-600">{{ max(0, $promotion->remaining_days) }}</div>
            <div class="text-sm text-gray-500 mt-1">
                Durée d'usage: {{ $promotion->usage_duration_days }}j
            </div>
        </div>
    </div>

    <!-- Graphique des activations par jour -->
    @if($activationsByDay->count() > 0)
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Activations par jour</h3>
        <div class="h-64">
            <canvas id="activationsChart"></canvas>
        </div>
    </div>
    @endif

    <!-- Dernières activations -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b bg-gray-50">
            <h3 class="text-lg font-semibold text-gray-800">
                Dernières activations ({{ $recentActivations->count() }})
            </h3>
        </div>

        @if($recentActivations->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Utilisateur</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date d'activation</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date d'expiration</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jours restants</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($recentActivations as $activation)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 bg-primary rounded-full flex items-center justify-center text-white font-semibold">
                                            {{ substr($activation->user->name ?? 'U', 0, 1) }}
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $activation->user->name ?? 'Utilisateur' }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $activation->user->email ?? '' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $activation->activated_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $activation->expires_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @php
                                        $remainingDays = $activation->expires_at->diffInDays(now(), false);
                                    @endphp
                                    @if($remainingDays < 0)
                                        <span class="text-red-600 font-semibold">Expiré</span>
                                    @elseif($remainingDays <= 3)
                                        <span class="text-orange-600 font-semibold">{{ abs($remainingDays) }}j</span>
                                    @else
                                        <span class="text-green-600 font-semibold">{{ abs($remainingDays) }}j</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($activation->is_expired || now()->gt($activation->expires_at))
                                        <span class="px-2 py-1 text-xs font-semibold rounded bg-red-100 text-red-800">
                                            Expiré
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold rounded bg-green-100 text-green-800">
                                            Actif
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-inbox text-gray-300 text-6xl mb-4"></i>
                <p class="text-gray-500">Aucune activation pour le moment</p>
            </div>
        @endif
    </div>
</div>

@if($activationsByDay->count() > 0)
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('activationsChart').getContext('2d');

    const data = {
        labels: {!! json_encode($activationsByDay->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d/m'))) !!},
        datasets: [{
            label: 'Activations',
            data: {!! json_encode($activationsByDay->pluck('count')) !!},
            borderColor: '#059669',
            backgroundColor: 'rgba(5, 150, 105, 0.1)',
            tension: 0.4,
            fill: true,
        }]
    };

    new Chart(ctx, {
        type: 'line',
        data: data,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.parsed.y + ' activation(s)';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
});
</script>
@endpush
@endif
@endsection
