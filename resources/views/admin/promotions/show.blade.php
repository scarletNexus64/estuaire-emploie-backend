@extends('admin.layouts.app')

@section('title', $promotion->name)

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm text-gray-600 mb-2">
            <a href="{{ route('admin.pack-promotions.index') }}" class="hover:text-primary">Promotions</a>
            <i class="fas fa-chevron-right text-xs"></i>
            <span>Détails</span>
        </div>
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">{{ $promotion->name }}</h1>
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
                <span class="inline-block mt-2 px-3 py-1 text-sm font-semibold rounded {{ $statusColors[$promotion->status] ?? 'bg-gray-100' }}">
                    {{ $statusLabels[$promotion->status] ?? $promotion->status }}
                </span>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.pack-promotions.edit', $promotion->id) }}"
                   class="bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-edit mr-2"></i>Modifier
                </a>
                <a href="{{ route('admin.pack-promotions.stats', $promotion->id) }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-chart-bar mr-2"></i>Statistiques
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Détails principaux -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Informations générales -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informations générales</h3>
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-600">Nom de la promotion</dt>
                        <dd class="mt-1 text-gray-900">{{ $promotion->name }}</dd>
                    </div>

                    @if($promotion->description)
                    <div>
                        <dt class="text-sm font-medium text-gray-600">Description</dt>
                        <dd class="mt-1 text-gray-900">{{ $promotion->description }}</dd>
                    </div>
                    @endif

                    <div>
                        <dt class="text-sm font-medium text-gray-600">Pack cible</dt>
                        <dd class="mt-1">
                            <div class="font-semibold text-gray-900">{{ $promotion->pack_name }}</div>
                            <div class="text-sm text-gray-500">{{ $promotion->pack_type_name }}</div>
                        </dd>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-600">Date de début</dt>
                            <dd class="mt-1 text-gray-900">{{ $promotion->start_date->format('d/m/Y à H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-600">Date de fin</dt>
                            <dd class="mt-1 text-gray-900">{{ $promotion->end_date->format('d/m/Y à H:i') }}</dd>
                        </div>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-600">Durée d'utilisation après activation</dt>
                        <dd class="mt-1 text-gray-900">
                            {{ $promotion->usage_duration_days }} jour{{ $promotion->usage_duration_days > 1 ? 's' : '' }}
                        </dd>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-600">Limite d'activations</dt>
                            <dd class="mt-1 text-gray-900">
                                {{ $promotion->max_activations ?? 'Illimité' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-600">Activations actuelles</dt>
                            <dd class="mt-1 text-gray-900 font-semibold">
                                {{ $promotion->current_activations }}
                            </dd>
                        </div>
                    </div>

                    @if($promotion->creator)
                    <div>
                        <dt class="text-sm font-medium text-gray-600">Créé par</dt>
                        <dd class="mt-1 text-gray-900">{{ $promotion->creator->name }}</dd>
                    </div>
                    @endif

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-600">Date de création</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $promotion->created_at->format('d/m/Y H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-600">Dernière modification</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $promotion->updated_at->format('d/m/Y H:i') }}</dd>
                        </div>
                    </div>
                </dl>
            </div>

            <!-- Actions rapides -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Actions</h3>
                <div class="flex flex-wrap gap-3">
                    <form method="POST" action="{{ route('admin.pack-promotions.toggle-active', $promotion->id) }}">
                        @csrf
                        <button type="submit"
                                class="px-4 py-2 rounded-lg font-semibold transition-colors {{ $promotion->is_active ? 'bg-red-100 text-red-700 hover:bg-red-200' : 'bg-green-100 text-green-700 hover:bg-green-200' }}">
                            <i class="fas fa-{{ $promotion->is_active ? 'toggle-off' : 'toggle-on' }} mr-2"></i>
                            {{ $promotion->is_active ? 'Désactiver' : 'Activer' }} la promotion
                        </button>
                    </form>

                    <form method="POST" action="{{ route('admin.pack-promotions.destroy', $promotion->id) }}"
                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette promotion ? Cette action est irréversible.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-semibold transition-colors">
                            <i class="fas fa-trash mr-2"></i>Supprimer
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="space-y-6">
            <!-- Carte de progression -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Progression</h3>

                <!-- Jours restants -->
                <div class="mb-6">
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-gray-600">Temps écoulé</span>
                        <span class="font-semibold text-gray-900">
                            @php
                                $totalDays = $promotion->start_date->diffInDays($promotion->end_date);
                                $elapsedDays = $promotion->start_date->diffInDays(now());
                                $percentage = $totalDays > 0 ? min(100, ($elapsedDays / $totalDays) * 100) : 0;
                            @endphp
                            {{ round($percentage) }}%
                        </span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-orange-500 h-3 rounded-full transition-all" style="width: {{ $percentage }}%"></div>
                    </div>
                    <div class="text-xs text-gray-500 mt-1">
                        {{ max(0, $promotion->remaining_days) }} jour(s) restant(s)
                    </div>
                </div>

                <!-- Activations -->
                @if($promotion->max_activations)
                <div>
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-gray-600">Activations</span>
                        <span class="font-semibold text-gray-900">
                            {{ round(($promotion->current_activations / $promotion->max_activations) * 100) }}%
                        </span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-primary h-3 rounded-full transition-all"
                             style="width: {{ min(100, ($promotion->current_activations / $promotion->max_activations) * 100) }}%"></div>
                    </div>
                    <div class="text-xs text-gray-500 mt-1">
                        {{ $promotion->current_activations }} / {{ $promotion->max_activations }} places prises
                    </div>
                </div>
                @endif
            </div>

            <!-- Statistiques rapides -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Aperçu</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg">
                        <span class="text-sm text-gray-700">Total activations</span>
                        <span class="text-xl font-bold text-blue-600">{{ $promotion->current_activations }}</span>
                    </div>

                    <div class="flex justify-between items-center p-3 bg-orange-50 rounded-lg">
                        <span class="text-sm text-gray-700">Jours restants</span>
                        <span class="text-xl font-bold text-orange-600">{{ max(0, $promotion->remaining_days) }}</span>
                    </div>

                    @if($promotion->remaining_activations !== null)
                    <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg">
                        <span class="text-sm text-gray-700">Places restantes</span>
                        <span class="text-xl font-bold text-green-600">{{ $promotion->remaining_activations }}</span>
                    </div>
                    @endif

                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm text-gray-700">Durée d'usage</span>
                        <span class="text-xl font-bold text-gray-700">{{ $promotion->usage_duration_days }}j</span>
                    </div>
                </div>
            </div>

            <!-- Disponibilité -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Disponibilité</h3>
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-{{ $promotion->is_active ? 'check-circle text-green-500' : 'times-circle text-red-500' }}"></i>
                        <span class="text-sm">{{ $promotion->is_active ? 'Activée' : 'Désactivée' }}</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <i class="fas fa-{{ $promotion->isAvailable() ? 'check-circle text-green-500' : 'times-circle text-red-500' }}"></i>
                        <span class="text-sm">{{ $promotion->isAvailable() ? 'Disponible pour les utilisateurs' : 'Non disponible' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
