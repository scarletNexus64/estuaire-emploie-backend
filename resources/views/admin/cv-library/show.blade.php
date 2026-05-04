@extends('admin.layouts.app')

@section('title', 'Détails du CV Importé')

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-gray-900">Accueil</a>
    <span class="text-gray-400">/</span>
    <a href="{{ route('admin.cv-library.index') }}" class="text-gray-600 hover:text-gray-900">CV Librairies</a>
    <span class="text-gray-400">/</span>
    <span class="text-gray-900 font-medium">Détails</span>
@endsection

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Sidebar: Info Candidat -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
                <div class="p-6 text-center border-b border-gray-100">
                    <div class="w-24 h-24 bg-gradient-to-br from-primary to-tertiary rounded-full flex items-center justify-center text-white text-3xl font-bold mx-auto mb-4">
                        {{ substr($resume->user->name ?? 'U', 0, 1) }}
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">{{ $resume->user->name ?? 'N/A' }}</h2>
                    <p class="text-primary font-medium">{{ $resume->title }}</p>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-start gap-3">
                        <i class="mdi mdi-email text-gray-400 text-xl"></i>
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-bold">Email</p>
                            <p class="text-sm text-gray-900">{{ $resume->user->email ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <i class="mdi mdi-school text-gray-400 text-xl"></i>
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-bold">Spécialité</p>
                            <p class="text-sm text-gray-900">{{ $resume->customization['specialty'] ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <i class="mdi mdi-trending-up text-gray-400 text-xl"></i>
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-bold">Niveau</p>
                            <p class="text-sm text-gray-900">{{ $resume->customization['level'] ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <i class="mdi mdi-file-import text-gray-400 text-xl"></i>
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-bold">Source d'import</p>
                            <p class="text-sm text-gray-900">{{ $resume->customization['import_file'] ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <!-- Langues -->
                    <div class="flex items-start gap-3">
                        <i class="mdi mdi-translate text-gray-400 text-xl"></i>
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-bold">Langues</p>
                            <p class="text-sm text-gray-900">{{ $resume->personal_info['languages'] ?? 'Non spécifié.' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <a href="{{ route('admin.cvtheque.preview', $resume->id) }}" class="flex items-center justify-center gap-2 w-full py-3 bg-primary text-white rounded-xl font-bold hover:bg-primary-dark transition-all shadow-md">
                <i class="mdi mdi-eye"></i>
                Voir le CV complet
            </a>
        </div>

        <!-- Main: Détails du CV -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm p-8 space-y-8">
                <!-- Résumé -->
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Résumé Professionnel</h3>
                    <p class="text-gray-700 leading-relaxed">{{ $resume->professional_summary ?? 'Aucun résumé.' }}</p>
                </div>

                <!-- Expériences -->
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Expériences</h3>
                    @if(!empty($resume->experiences))
                        <ul class="space-y-3">
                            @foreach($resume->experiences as $exp)
                                <li class="flex items-start gap-2">
                                    <i class="mdi mdi-circle-small text-primary text-2xl mt-[-4px]"></i>
                                    <span class="text-gray-700">
                                        @if(is_array($exp))
                                            {{ $exp['description'] ?? $exp['title'] ?? '' }}
                                        @else
                                            {{ $exp }}
                                        @endif
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-gray-500 italic">Aucune expérience renseignée.</p>
                    @endif
                </div>

                <!-- Formation -->
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Formation</h3>
                    @if(!empty($resume->education))
                        <ul class="space-y-3">
                            @foreach($resume->education as $edu)
                                <li class="flex items-start gap-2">
                                    <i class="mdi mdi-circle-small text-secondary text-2xl mt-[-4px]"></i>
                                    <span class="text-gray-700">
                                        @if(is_array($edu))
                                            {{ $edu['degree'] ?? $edu['school'] ?? '' }}
                                        @else
                                            {{ $edu }}
                                        @endif
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-gray-500 italic">Aucune formation renseignée.</p>
                    @endif
                </div>

                <!-- Compétences (Techniques) -->
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Compétences Techniques</h3>
                    <div class="flex flex-wrap gap-2">
                        @forelse($resume->skills as $skill)
                            <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm">{{ $skill }}</span>
                        @empty
                            <p class="text-gray-500 italic w-full">Aucune compétence renseignée.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Savoir-être -->
                @if(!empty($resume->customization['soft_skills']))
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Savoir-être</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($resume->customization['soft_skills'] as $soft)
                            <span class="px-3 py-1 bg-green-50 text-green-800 rounded-full text-sm">{{ $soft }}</span>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Projets académiques -->
                @if(!empty($resume->projects))
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Projets Académiques</h3>
                    <ul class="space-y-3">
                        @foreach($resume->projects as $project)
                            <li class="flex items-start gap-2">
                                <i class="mdi mdi-lightbulb-on-outline text-yellow-600 text-2xl mt-[-4px]"></i>
                                <span class="text-gray-700">{{ $project }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Certifications -->
                @if(!empty($resume->certifications))
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Certifications</h3>
                    <ul class="space-y-3">
                        @foreach($resume->certifications as $cert)
                            <li class="flex items-start gap-2">
                                <i class="mdi mdi-certificate text-blue-600 text-2xl mt-[-4px]"></i>
                                <span class="text-gray-700">{{ $cert }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection
