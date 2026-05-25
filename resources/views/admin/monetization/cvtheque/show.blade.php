@extends('admin.layouts.app')

@section('title', 'Détails Utilisateur - CVthèque')

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-gray-900">Accueil</a>
    <span class="text-gray-400">/</span>
    <a href="{{ route('admin.cvtheque.index') }}" class="text-gray-600 hover:text-gray-900">CVthèque</a>
    <span class="text-gray-400">/</span>
    <span class="text-gray-900 font-medium">{{ $user->name }}</span>
@endsection

@section('header-actions')
    <a href="{{ route('admin.cvtheque.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-200 font-medium">
        <i class="mdi mdi-arrow-left"></i>
        Retour
    </a>
@endsection

@section('content')
    <!-- Informations Utilisateur -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <div class="flex items-start gap-6">
            <div class="w-20 h-20 bg-gradient-to-br from-primary to-tertiary rounded-full flex items-center justify-center text-white text-2xl font-bold">
                {{ substr($user->name, 0, 1) }}
            </div>
            <div class="flex-1">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $user->name }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="flex items-center gap-2 text-gray-600">
                        <i class="mdi mdi-email text-xl"></i>
                        <span>{{ $user->email }}</span>
                    </div>
                    @if($user->phone)
                        <div class="flex items-center gap-2 text-gray-600">
                            <i class="mdi mdi-phone text-xl"></i>
                            <span>{{ $user->phone }}</span>
                        </div>
                    @endif
                    <div class="flex items-center gap-2 text-gray-600">
                        <i class="mdi mdi-calendar text-xl"></i>
                        <span>Membre depuis {{ $user->created_at->format('d/m/Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques CVs de l'utilisateur -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-primary/10 to-primary/20 rounded-lg flex items-center justify-center">
                    <i class="mdi mdi-file-document text-2xl text-primary"></i>
                </div>
            </div>
            <h3 class="text-gray-600 text-sm font-medium mb-1">Total CVs</h3>
            <p class="text-3xl font-bold text-gray-900">{{ number_format($user->resumes->count()) }}</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-green-500/10 to-green-500/20 rounded-lg flex items-center justify-center">
                    <i class="mdi mdi-eye text-2xl text-green-600"></i>
                </div>
            </div>
            <h3 class="text-gray-600 text-sm font-medium mb-1">CVs Publics</h3>
            <p class="text-3xl font-bold text-gray-900">{{ number_format($user->resumes->where('is_public', true)->count()) }}</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500/10 to-purple-500/20 rounded-lg flex items-center justify-center">
                    <i class="mdi mdi-star text-2xl text-purple-600"></i>
                </div>
            </div>
            <h3 class="text-gray-600 text-sm font-medium mb-1">CV par défaut</h3>
            <p class="text-3xl font-bold text-gray-900">{{ $user->resumes->where('is_default', true)->count() > 0 ? '1' : '0' }}</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-orange-500/10 to-orange-500/20 rounded-lg flex items-center justify-center">
                    <i class="mdi mdi-file-pdf-box text-2xl text-orange-600"></i>
                </div>
            </div>
            <h3 class="text-gray-600 text-sm font-medium mb-1">CVs avec PDF</h3>
            <p class="text-3xl font-bold text-gray-900">{{ number_format($user->resumes->whereNotNull('pdf_path')->count()) }}</p>
        </div>
    </div>

    <!-- Liste des CVs de l'utilisateur -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                <i class="mdi mdi-file-document-multiple text-xl text-primary"></i>
                CVs de {{ $user->name }} ({{ $user->resumes->count() }})
            </h3>
        </div>

        @if($user->resumes->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
                @foreach($user->resumes as $resume)
                    <div class="bg-white border-2 border-gray-200 rounded-xl p-6 hover:shadow-lg transition-all duration-200 hover:border-primary">
                        <!-- Header -->
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <h4 class="text-lg font-bold text-gray-900 mb-1">{{ $resume->title ?? 'Sans titre' }}</h4>
                                <p class="text-sm text-gray-500">
                                    Créé le {{ $resume->created_at->format('d/m/Y') }}
                                </p>
                            </div>
                            @if($resume->is_default)
                                <span class="inline-flex items-center gap-1 bg-purple-100 text-purple-700 px-2 py-1 rounded text-xs font-medium">
                                    <i class="mdi mdi-star"></i>
                                    Défaut
                                </span>
                            @endif
                        </div>

                        <!-- Template -->
                        <div class="mb-4">
                            <div class="flex items-center gap-2 mb-2">
                                <i class="mdi mdi-palette text-gray-400"></i>
                                <span class="text-sm font-medium text-gray-700">Template</span>
                            </div>
                            <span class="inline-flex items-center gap-1 bg-blue-100 text-blue-700 px-3 py-1 rounded-lg text-sm font-medium">
                                {{ $resume->template_info['name'] ?? ucfirst($resume->template_type) }}
                            </span>
                        </div>

                        <!-- Complétude -->
                        <div class="mb-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-gray-700">Complétude</span>
                                <span class="text-sm font-bold text-gray-900">{{ $resume->calculateCompleteness() }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-gradient-to-r from-primary to-tertiary h-2 rounded-full transition-all duration-300"
                                     style="width: {{ $resume->calculateCompleteness() }}%"></div>
                            </div>
                        </div>

                        <!-- Statut -->
                        <div class="flex flex-wrap gap-2 mb-4">
                            @if($resume->is_public)
                                <span class="inline-flex items-center gap-1 bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-medium">
                                    <i class="mdi mdi-eye"></i>
                                    Public
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs font-medium">
                                    <i class="mdi mdi-eye-off"></i>
                                    Privé
                                </span>
                            @endif
                            @if($resume->pdf_path)
                                <span class="inline-flex items-center gap-1 bg-orange-100 text-orange-700 px-2 py-1 rounded text-xs font-medium">
                                    <i class="mdi mdi-file-pdf-box"></i>
                                    PDF disponible
                                </span>
                            @endif
                        </div>

                        <!-- Actions -->
                        <div class="flex gap-2 pt-4 border-t border-gray-200">
                            @if($resume->pdf_path)
                                <a href="{{ $resume->pdf_url }}" target="_blank"
                                   class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors">
                                    <i class="mdi mdi-file-pdf-box"></i>
                                    Voir PDF
                                </a>
                            @else
                                <button disabled
                                        class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2 bg-gray-300 text-gray-500 rounded-lg cursor-not-allowed">
                                    <i class="mdi mdi-file-pdf-box"></i>
                                    Pas de PDF
                                </button>
                            @endif
                        </div>

                        <!-- Sections du CV -->
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <p class="text-xs font-medium text-gray-500 mb-2">Sections remplies :</p>
                            <div class="flex flex-wrap gap-1">
                                @if(!empty($resume->personal_info))
                                    <span class="inline-flex items-center gap-1 bg-gray-100 text-gray-700 px-2 py-0.5 rounded text-xs">
                                        <i class="mdi mdi-account"></i>
                                        Infos personnelles
                                    </span>
                                @endif
                                @if(!empty($resume->professional_summary))
                                    <span class="inline-flex items-center gap-1 bg-gray-100 text-gray-700 px-2 py-0.5 rounded text-xs">
                                        <i class="mdi mdi-text"></i>
                                        Résumé
                                    </span>
                                @endif
                                @if(!empty($resume->experiences))
                                    <span class="inline-flex items-center gap-1 bg-gray-100 text-gray-700 px-2 py-0.5 rounded text-xs">
                                        <i class="mdi mdi-briefcase"></i>
                                        Expériences
                                    </span>
                                @endif
                                @if(!empty($resume->education))
                                    <span class="inline-flex items-center gap-1 bg-gray-100 text-gray-700 px-2 py-0.5 rounded text-xs">
                                        <i class="mdi mdi-school"></i>
                                        Formation
                                    </span>
                                @endif
                                @if(!empty($resume->skills))
                                    <span class="inline-flex items-center gap-1 bg-gray-100 text-gray-700 px-2 py-0.5 rounded text-xs">
                                        <i class="mdi mdi-star"></i>
                                        Compétences
                                    </span>
                                @endif
                                @if(!empty($resume->certifications))
                                    <span class="inline-flex items-center gap-1 bg-gray-100 text-gray-700 px-2 py-0.5 rounded text-xs">
                                        <i class="mdi mdi-certificate"></i>
                                        Certifications
                                    </span>
                                @endif
                                @if(!empty($resume->projects))
                                    <span class="inline-flex items-center gap-1 bg-gray-100 text-gray-700 px-2 py-0.5 rounded text-xs">
                                        <i class="mdi mdi-folder"></i>
                                        Projets
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="px-6 py-12 text-center text-gray-500">
                <i class="mdi mdi-file-document-off text-6xl mb-4"></i>
                <p class="text-lg font-medium">Aucun CV trouvé</p>
                <p class="text-sm mt-2">Cet utilisateur n'a pas encore créé de CV</p>
            </div>
        @endif
    </div>
@endsection
