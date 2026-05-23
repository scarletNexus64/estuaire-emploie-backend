@extends('admin.layouts.app')

@section('title', 'Détails Test - ' . $test->title)
@section('page-title', 'Détails du Test')

@section('content')
    <div class="mb-3" style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
        <a href="{{ route('admin.skill-tests.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
        <a href="{{ route('admin.skill-tests.edit', $test) }}" class="btn btn-primary">
            <i class="fas fa-edit"></i> Éditer
        </a>
        @unless($test->is_active)
            <form action="{{ route('admin.skill-tests.publish', $test) }}" method="POST" style="display:inline;">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-paper-plane"></i> Publier (sans paiement)
                </button>
            </form>
        @endunless
        <form action="{{ route('admin.skill-tests.destroy', $test) }}" method="POST" style="display:inline;"
              onsubmit="return confirm('Supprimer définitivement ce test ?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i> Supprimer</button>
        </form>
    </div>

    <div class="row">
        <!-- Test Information -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $test->title }}</h3>
                    @if($test->is_active)
                        <span class="badge badge-success">Actif</span>
                    @else
                        <span class="badge badge-secondary">Inactif</span>
                    @endif
                </div>

                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Entreprise:</strong> {{ $test->company->name }}</p>
                            <p><strong>Offre liée:</strong> {{ $test->job?->title ?? 'Aucune' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Score minimal:</strong> {{ $test->passing_score }}%</p>
                            <p><strong>Durée:</strong> {{ $test->duration_minutes ? $test->duration_minutes . ' minutes' : 'Illimitée' }}</p>
                        </div>
                    </div>

                    @if($test->description)
                        <div class="mb-3">
                            <strong>Description:</strong>
                            <p>{{ $test->description }}</p>
                        </div>
                    @endif

                    <hr>

                    <h4>Questions ({{ count($test->questions) }})</h4>

                    @foreach($test->questions as $index => $question)
                        <div class="card mb-3">
                            <div class="card-body">
                                <h5>Question {{ $index + 1 }}</h5>
                                <p><strong>{{ $question['question'] }}</strong></p>

                                <p class="text-muted">
                                    <small>Type:
                                        @if($question['type'] === 'multiple_choice')
                                            📝 Choix multiple
                                        @elseif($question['type'] === 'text')
                                            ✏️ Texte libre
                                        @elseif($question['type'] === 'code')
                                            💻 Code
                                        @endif
                                    </small>
                                </p>

                                @if($question['type'] === 'multiple_choice' && isset($question['options']))
                                    <div class="ml-3">
                                        <strong>Options:</strong>
                                        <ul>
                                            @foreach($question['options'] as $option)
                                                <li>
                                                    {{ $option }}
                                                    @if($option === $question['correct_answer'])
                                                        <span class="badge badge-success">✓ Réponse correcte</span>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @else
                                    <div class="ml-3">
                                        <strong>Réponse attendue:</strong>
                                        <pre style="background-color: #f5f5f5; padding: 10px; border-radius: 4px;">{{ $question['correct_answer'] }}</pre>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Statistics & Results -->
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header">
                    <h4 class="card-title">Statistiques</h4>
                </div>
                <div class="card-body">
                    <p><strong>Nombre d'utilisations:</strong> {{ $test->times_used }}</p>
                    <p><strong>Résultats enregistrés:</strong> {{ $test->results->count() }}</p>
                    <p><strong>Taux de réussite:</strong>
                        @if($test->results->count() > 0)
                            {{ number_format(($test->results->where('passed', true)->count() / $test->results->count()) * 100, 1) }}%
                        @else
                            N/A
                        @endif
                    </p>
                    <p><strong>Score moyen:</strong>
                        @if($test->results->count() > 0)
                            {{ number_format($test->results->avg('score'), 1) }}%
                        @else
                            N/A
                        @endif
                    </p>
                    <p><strong>Créé le:</strong> {{ $test->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>

            @if($test->results->count() > 0)
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Résultats Récents</h4>
                    </div>
                    <div class="card-body">
                        @foreach($test->results->sortByDesc('completed_at')->take(5) as $result)
                            <div class="mb-3 pb-3 border-bottom">
                                <p class="mb-1">
                                    <strong>{{ $result->application->user->name }}</strong>
                                    @if($result->passed)
                                        <span class="badge badge-success">✓ Réussi</span>
                                    @else
                                        <span class="badge badge-danger">✗ Échoué</span>
                                    @endif
                                </p>
                                <p class="mb-0 text-muted">
                                    <small>
                                        Score: {{ $result->score }}%<br>
                                        Offre: {{ $result->application->job->title }}<br>
                                        {{ $result->completed_at ? $result->completed_at->format('d/m/Y H:i') : 'En cours' }}
                                    </small>
                                </p>
                            </div>
                        @endforeach

                        @if($test->results->count() > 5)
                            <a href="{{ route('admin.skill-tests.index') }}" class="btn btn-sm btn-secondary btn-block">
                                Voir tous les résultats
                            </a>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
