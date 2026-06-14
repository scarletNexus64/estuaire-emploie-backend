@extends('admin.layouts.app')

@section('title', 'CVthèque')

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-gray-900">Accueil</a>
    <span class="text-gray-400">/</span>
    <span class="text-gray-900 font-medium">CVthèque</span>
@endsection

@section('header-actions')
    <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-200 font-medium">
        <i class="mdi mdi-printer"></i>
        Imprimer
    </button>
@endsection

@section('content')
    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-primary/10 to-primary/20 rounded-lg flex items-center justify-center">
                    <i class="mdi mdi-file-document text-2xl text-primary"></i>
                </div>
            </div>
            <h3 class="text-gray-600 text-sm font-medium mb-1">Total CVs</h3>
            <p class="text-3xl font-bold text-gray-900">{{ number_format($resumes->total()) }}</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-green-500/10 to-green-500/20 rounded-lg flex items-center justify-center">
                    <i class="mdi mdi-eye text-2xl text-green-600"></i>
                </div>
            </div>
            <h3 class="text-gray-600 text-sm font-medium mb-1">CVs Publics</h3>
            <p class="text-3xl font-bold text-gray-900">{{ number_format($resumes->where('is_public', true)->count()) }}</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500/10 to-blue-500/20 rounded-lg flex items-center justify-center">
                    <i class="mdi mdi-account-group text-2xl text-blue-600"></i>
                </div>
            </div>
            <h3 class="text-gray-600 text-sm font-medium mb-1">Utilisateurs avec CV</h3>
            <p class="text-3xl font-bold text-gray-900">{{ number_format($resumes->unique('user_id')->count()) }}</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500/10 to-purple-500/20 rounded-lg flex items-center justify-center">
                    <i class="mdi mdi-file-pdf-box text-2xl text-purple-600"></i>
                </div>
            </div>
            <h3 class="text-gray-600 text-sm font-medium mb-1">CVs avec PDF</h3>
            <p class="text-3xl font-bold text-gray-900">{{ number_format($resumes->whereNotNull('pdf_path')->count()) }}</p>
        </div>
    </div>

    <!-- Filtres de recherche -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <form method="GET" action="{{ route('admin.cvtheque.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Recherche -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rechercher</label>
                    <div class="relative">
                        <i class="mdi mdi-magnify absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Nom, email, titre du CV..."
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                    </div>
                </div>

                <!-- Spécialité -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Spécialité</label>
                    <select name="specialty" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="">Toutes les spécialités</option>
                        @foreach($specialties as $specialty)
                            <option value="{{ $specialty }}" {{ request('specialty') === $specialty ? 'selected' : '' }}>
                                {{ $specialty }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Template -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Template</label>
                    <select name="template_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="">Tous les templates</option>
                        @foreach($templates as $template)
                            <option value="{{ $template['type'] }}" {{ request('template_type') === $template['type'] ? 'selected' : '' }}>
                                {{ $template['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Catégorie d'entreprise (cascade Niveau 1 > 2 > 3) -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Secteur (Niveau 1)</label>
                    <select id="filter-level-1" name="level_1" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="">Tous les secteurs</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sous-catégorie (Niveau 2)</label>
                    <select id="filter-level-2" name="level_2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="">Toutes</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Spécialité métier (Niveau 3)</label>
                    <select id="filter-level-3" name="level_3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="">Toutes</option>
                    </select>
                </div>

                <!-- Visibilité -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Visibilité</label>
                    <select name="is_public" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="">Tous</option>
                        <option value="1" {{ request('is_public') === '1' ? 'selected' : '' }}>Public</option>
                        <option value="0" {{ request('is_public') === '0' ? 'selected' : '' }}>Privé</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end gap-2">
                <a href="{{ route('admin.cvtheque.index') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                    Réinitialiser
                </a>
                <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                    <i class="mdi mdi-magnify"></i> Rechercher
                </button>
            </div>
        </form>
    </div>

    <!-- Liste des CVs -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                <i class="mdi mdi-file-document-multiple text-xl text-primary"></i>
                Tous les CVs ({{ number_format($resumes->total()) }})
            </h3>
        </div>

        @if($resumes->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Utilisateur
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Titre du CV
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Template
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Complétude
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Statut
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date création
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($resumes as $resume)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-gradient-to-br from-primary to-tertiary rounded-full flex items-center justify-center text-white text-sm font-bold">
                                            {{ substr($resume->user->name ?? 'U', 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-gray-900">{{ $resume->user->name ?? 'N/A' }}</div>
                                            <div class="text-xs text-gray-500">{{ $resume->user->email ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $resume->title ?? 'Sans titre' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1 bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs font-medium">
                                        <i class="mdi mdi-palette"></i>
                                        {{ $resume->template_info['name'] ?? ucfirst($resume->template_type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <div class="flex-1 bg-gray-200 rounded-full h-2 max-w-[100px]">
                                            <div class="bg-gradient-to-r from-primary to-tertiary h-2 rounded-full"
                                                 style="width: {{ $resume->calculateCompleteness() }}%"></div>
                                        </div>
                                        <span class="text-xs font-medium text-gray-600">{{ $resume->calculateCompleteness() }}%</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="space-y-1">
                                        @if($resume->is_public)
                                            <span class="inline-flex items-center gap-1 bg-green-100 text-green-700 px-2 py-0.5 rounded text-xs font-medium">
                                                <i class="mdi mdi-eye"></i>
                                                Public
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 bg-gray-100 text-gray-700 px-2 py-0.5 rounded text-xs font-medium">
                                                <i class="mdi mdi-eye-off"></i>
                                                Privé
                                            </span>
                                        @endif
                                        @if($resume->is_default)
                                            <span class="inline-flex items-center gap-1 bg-purple-100 text-purple-700 px-2 py-0.5 rounded text-xs font-medium">
                                                <i class="mdi mdi-star"></i>
                                                Par défaut
                                            </span>
                                        @endif
                                        @if($resume->pdf_path)
                                            <span class="inline-flex items-center gap-1 bg-orange-100 text-orange-700 px-2 py-0.5 rounded text-xs font-medium">
                                                <i class="mdi mdi-file-pdf-box"></i>
                                                PDF
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $resume->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.cvtheque.preview', $resume->id) }}"
                                           class="text-purple-600 hover:text-purple-900 transition-colors"
                                           title="Prévisualiser">
                                            <i class="mdi mdi-eye-outline text-xl"></i>
                                        </a>
                                        @if($resume->pdf_path)
                                            <a href="{{ $resume->pdf_url }}" target="_blank"
                                               class="text-orange-600 hover:text-orange-900 transition-colors"
                                               title="Voir le PDF">
                                                <i class="mdi mdi-file-pdf-box text-xl"></i>
                                            </a>
                                        @endif
                                        <a href="{{ route('admin.cvtheque.show', $resume->user_id) }}"
                                           class="text-blue-600 hover:text-blue-900 transition-colors"
                                           title="Voir les détails">
                                            <i class="mdi mdi-eye text-xl"></i>
                                        </a>
                                        <form action="{{ route('admin.cvtheque.destroy', $resume->id) }}" method="POST"
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce CV ?')"
                                              class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="text-red-600 hover:text-red-900 transition-colors"
                                                    title="Supprimer">
                                                <i class="mdi mdi-delete text-xl"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $resumes->links() }}
            </div>
        @else
            <div class="px-6 py-12 text-center text-gray-500">
                <i class="mdi mdi-file-document-off text-6xl mb-4"></i>
                <p class="text-lg font-medium">Aucun CV trouvé</p>
                <p class="text-sm mt-2">Essayez de modifier vos critères de recherche</p>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
<script>
(function () {
    // Arbre des catégories : { "Secteur": { "Sous-cat": ["Spé1", "Spé2"] } }
    const CATEGORY_TREE = @json($categoryTree);
    // Valeurs sélectionnées (rechargées depuis la requête pour persister les filtres).
    const SELECTED = {
        l1: @json(request('level_1', '')),
        l2: @json(request('level_2', '')),
        l3: @json(request('level_3', '')),
    };

    const sel1 = document.getElementById('filter-level-1');
    const sel2 = document.getElementById('filter-level-2');
    const sel3 = document.getElementById('filter-level-3');

    function fill(select, options, placeholder, selectedValue) {
        select.innerHTML = '';
        const opt0 = document.createElement('option');
        opt0.value = '';
        opt0.textContent = placeholder;
        select.appendChild(opt0);
        options.forEach(function (val) {
            const opt = document.createElement('option');
            opt.value = val;
            opt.textContent = val;
            if (val === selectedValue) opt.selected = true;
            select.appendChild(opt);
        });
    }

    function refreshLevel2() {
        const l1 = sel1.value;
        const l2Map = (l1 && CATEGORY_TREE[l1]) ? CATEGORY_TREE[l1] : {};
        fill(sel2, Object.keys(l2Map), 'Toutes', SELECTED.l2);
    }

    function refreshLevel3() {
        const l1 = sel1.value;
        const l2 = sel2.value;
        const l3List = (l1 && l2 && CATEGORY_TREE[l1] && CATEGORY_TREE[l1][l2])
            ? CATEGORY_TREE[l1][l2] : [];
        fill(sel3, l3List, 'Toutes', SELECTED.l3);
    }

    // Initialisation niveau 1.
    fill(sel1, Object.keys(CATEGORY_TREE), 'Tous les secteurs', SELECTED.l1);
    refreshLevel2();
    refreshLevel3();

    sel1.addEventListener('change', function () {
        SELECTED.l2 = ''; SELECTED.l3 = '';
        refreshLevel2();
        refreshLevel3();
    });
    sel2.addEventListener('change', function () {
        SELECTED.l3 = '';
        refreshLevel3();
    });
})();
</script>
@endpush
