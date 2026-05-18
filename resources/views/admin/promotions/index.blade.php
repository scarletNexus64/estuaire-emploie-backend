@extends('admin.layouts.app')

@section('title', 'Promotions de Packs')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Promotions de Packs</h1>
            <p class="text-gray-600 mt-1">Gérez les promotions gratuites pour tous les types de packs</p>
        </div>
        <a href="{{ route('admin.pack-promotions.create') }}"
           class="bg-primary hover:bg-primary-dark text-white px-6 py-3 rounded-lg font-semibold transition-colors flex items-center gap-2">
            <i class="fas fa-plus"></i>
            Nouvelle Promotion
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filtres -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <form method="GET" action="{{ route('admin.pack-promotions.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Filtre par type -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Type de pack</label>
                <select name="type" class="w-full border border-gray-300 rounded-lg px-4 py-2">
                    <option value="">Tous les types</option>
                    <option value="App\Models\ExamPack" {{ request('type') == 'App\Models\ExamPack' ? 'selected' : '' }}>Pack Examen</option>
                    <option value="App\Models\TrainingPack" {{ request('type') == 'App\Models\TrainingPack' ? 'selected' : '' }}>Pack Formation</option>
                    <option value="App\Models\StoragePack" {{ request('type') == 'App\Models\StoragePack' ? 'selected' : '' }}>Pack Stockage</option>
                    <option value="App\Models\SubscriptionPlan" {{ request('type') == 'App\Models\SubscriptionPlan' ? 'selected' : '' }}>Plan d'Abonnement</option>
                </select>
            </div>

            <!-- Filtre par statut -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                <select name="status" class="w-full border border-gray-300 rounded-lg px-4 py-2">
                    <option value="">Tous les statuts</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actives</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactives</option>
                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expirées</option>
                    <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Programmées</option>
                </select>
            </div>

            <!-- Bouton filtrer -->
            <div class="flex items-end">
                <button type="submit" class="w-full bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded-lg">
                    <i class="fas fa-filter mr-2"></i>Filtrer
                </button>
            </div>
        </form>
    </div>

    <!-- Liste des promotions -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        @if($promotions->count() > 0)
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pack</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Période</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Activations</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($promotions as $promotion)
                        <tr>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">{{ $promotion->name }}</div>
                                @if($promotion->description)
                                    <div class="text-sm text-gray-500">{{ Str::limit($promotion->description, 50) }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm">
                                    <span class="font-medium">{{ $promotion->pack_name }}</span>
                                    <br>
                                    <span class="text-gray-500 text-xs">{{ $promotion->pack_type_name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <div>{{ $promotion->start_date->format('d/m/Y') }}</div>
                                <div class="text-gray-500">→ {{ $promotion->end_date->format('d/m/Y') }}</div>
                                @if($promotion->remaining_days > 0)
                                    <div class="text-orange-600 text-xs mt-1">
                                        {{ $promotion->remaining_days }} jour(s) restant(s)
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <div class="font-semibold">{{ $promotion->current_activations }}</div>
                                @if($promotion->max_activations)
                                    <div class="text-gray-500 text-xs">/ {{ $promotion->max_activations }}</div>
                                @else
                                    <div class="text-gray-500 text-xs">illimité</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
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
                                    $status = $promotion->status;
                                @endphp
                                <span class="px-2 py-1 text-xs font-semibold rounded {{ $statusColors[$status] ?? 'bg-gray-100' }}">
                                    {{ $statusLabels[$status] ?? $status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm space-x-2">
                                <a href="{{ route('admin.pack-promotions.stats', $promotion->id) }}"
                                   class="text-blue-600 hover:text-blue-800" title="Statistiques">
                                    <i class="fas fa-chart-bar"></i>
                                </a>
                                <a href="{{ route('admin.pack-promotions.edit', $promotion->id) }}"
                                   class="text-yellow-600 hover:text-yellow-800" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST"
                                      action="{{ route('admin.pack-promotions.toggle-active', $promotion->id) }}"
                                      class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="{{ $promotion->is_active ? 'text-red-600 hover:text-red-800' : 'text-green-600 hover:text-green-800' }}"
                                            title="{{ $promotion->is_active ? 'Désactiver' : 'Activer' }}">
                                        <i class="fas fa-{{ $promotion->is_active ? 'toggle-on' : 'toggle-off' }}"></i>
                                    </button>
                                </form>
                                <form method="POST"
                                      action="{{ route('admin.pack-promotions.destroy', $promotion->id) }}"
                                      class="inline"
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette promotion ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800" title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t">
                {{ $promotions->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-gift text-gray-300 text-6xl mb-4"></i>
                <p class="text-gray-500 text-lg">Aucune promotion trouvée</p>
                <a href="{{ route('admin.pack-promotions.create') }}"
                   class="inline-block mt-4 text-primary hover:text-primary-dark">
                    Créer votre première promotion
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
