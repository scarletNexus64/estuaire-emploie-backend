@extends('admin.layouts.app')

@section('title', 'CV Librairies (IUEs/INSAM)')

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-gray-900">Accueil</a>
    <span class="text-gray-400">/</span>
    <span class="text-gray-900 font-medium">CV Librairies</span>
@endsection

@section('header-actions')
    <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-200 font-medium">
        <i class="mdi mdi-printer"></i>
        Imprimer
    </button>
@endsection

@section('content')
    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-primary/10 to-primary/20 rounded-lg flex items-center justify-center">
                    <i class="mdi mdi-file-document text-2xl text-primary"></i>
                </div>
            </div>
            <h3 class="text-gray-600 text-sm font-medium mb-1">Total CVs Importés</h3>
            <p class="text-3xl font-bold text-gray-900">{{ number_format($resumes->total()) }}</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-green-500/10 to-green-500/20 rounded-lg flex items-center justify-center">
                    <i class="mdi mdi-school text-2xl text-green-600"></i>
                </div>
            </div>
            <h3 class="text-gray-600 text-sm font-medium mb-1">Spécialités</h3>
            <p class="text-3xl font-bold text-gray-900">{{ number_format($resumes->groupBy('customization->specialty')->count()) }}</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500/10 to-blue-500/20 rounded-lg flex items-center justify-center">
                    <i class="mdi mdi-trending-up text-2xl text-blue-600"></i>
                </div>
            </div>
            <h3 class="text-gray-600 text-sm font-medium mb-1">Niveaux d'études</h3>
            <p class="text-3xl font-bold text-gray-900">{{ number_format($resumes->groupBy('customization->level')->count()) }}</p>
        </div>
    </div>

    <!-- Filtres de recherche -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <form method="GET" action="{{ route('admin.cv-library.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Recherche -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rechercher</label>
                    <div class="relative">
                        <i class="mdi mdi-magnify absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Nom, email, métier..."
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                    </div>
                </div>

                <!-- Spécialité -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Spécialité</label>
                    <input type="text" name="specialty" value="{{ request('specialty') }}"
                           placeholder="Ex: Génie Logiciel..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                </div>

                <!-- Niveau -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Niveau</label>
                    <input type="text" name="level" value="{{ request('level') }}"
                           placeholder="Ex: BTS 1..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                </div>
            </div>

            <div class="flex justify-end gap-2">
                <a href="{{ route('admin.cv-library.index') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
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
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                <i class="mdi mdi-file-document-multiple text-xl text-primary"></i>
                Liste des CVs Importés ({{ number_format($resumes->total()) }})
            </h3>
        </div>

        @if($resumes->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Candidat</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Profession/Titre</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Spécialité</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Langues</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Niveau</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
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
                                    <div class="text-sm text-gray-900 font-medium">{{ $resume->title }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-600">{{ $resume->customization['specialty'] ?? 'N/A' }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-xs text-gray-500">{{ Str::limit($resume->personal_info['languages'] ?? 'N/A', 20) }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $resume->customization['level'] ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.cvtheque.preview', $resume->id) }}"
                                           class="text-purple-600 hover:text-purple-900 transition-colors"
                                           title="Prévisualiser">
                                            <i class="mdi mdi-eye-outline text-xl"></i>
                                        </a>
                                        <a href="{{ route('admin.cv-library.show', $resume->id) }}"
                                           class="text-blue-600 hover:text-blue-900 transition-colors"
                                           title="Voir les détails">
                                            <i class="mdi mdi-information-outline text-xl"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-100">
                {{ $resumes->links() }}
            </div>
        @else
            <div class="px-6 py-12 text-center text-gray-500">
                <i class="mdi mdi-file-document-off text-6xl mb-4"></i>
                <p class="text-lg font-medium">Aucun CV importé trouvé</p>
                <p class="text-sm mt-2">Lancez le seeder pour importer les données des fichiers Excel.</p>
            </div>
        @endif
    </div>
@endsection
