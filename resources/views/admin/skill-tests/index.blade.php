@extends('admin.layouts.app')

@section('title', 'Tests de Compétences')
@section('page-title', 'Tests de Compétences')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Liste des Tests de Compétences</h3>
        </div>

        <div class="card-body">
            <!-- Filters -->
            <form method="GET" class="mb-4">
                <div class="row">
                    <div class="col-md-4">
                        <select name="company_id" class="form-control" onchange="this.form.submit()">
                            <option value="">Toutes les entreprises</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                                    {{ $company->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select name="is_active" class="form-control" onchange="this.form.submit()">
                            <option value="">Tous les statuts</option>
                            <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Actifs</option>
                            <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Inactifs</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        @if(request()->anyFilled(['company_id', 'is_active']))
                            <a href="{{ route('admin.skill-tests.index') }}" class="btn btn-secondary">
                                Réinitialiser
                            </a>
                        @endif
                    </div>
                </div>
            </form>

            @if($tests->isEmpty())
                <div class="alert alert-info">
                    Aucun test de compétences trouvé.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Titre</th>
                                <th>Entreprise</th>
                                <th>Offre liée</th>
                                <th>Questions</th>
                                <th>Score min.</th>
                                <th>Durée</th>
                                <th>Utilisations</th>
                                <th>Statut</th>
                                <th>Date création</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tests as $test)
                                <tr>
                                    <td>{{ $test->id }}</td>
                                    <td>
                                        <a href="{{ route('admin.skill-tests.show', $test) }}">
                                            {{ $test->title }}
                                        </a>
                                    </td>
                                    <td>{{ $test->company->name }}</td>
                                    <td>{{ $test->job?->title ?? 'Non lié' }}</td>
                                    <td>{{ count($test->questions) }}</td>
                                    <td>{{ $test->passing_score }}%</td>
                                    <td>{{ $test->duration_minutes ? $test->duration_minutes . ' min' : 'Illimitée' }}</td>
                                    <td>
                                        <span class="badge badge-info">
                                            {{ $test->times_used }} fois
                                        </span>
                                        <br>
                                        <small class="text-muted">{{ $test->results_count }} résultats</small>
                                    </td>
                                    <td>
                                        @if($test->is_active)
                                            <span class="badge badge-success">Actif</span>
                                        @else
                                            <span class="badge badge-secondary">Inactif</span>
                                        @endif
                                    </td>
                                    <td>{{ $test->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('admin.skill-tests.show', $test) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> Voir
                                        </a>
                                        <form action="{{ route('admin.skill-tests.destroy', $test) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce test ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $tests->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Statistics -->
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h3>{{ $tests->total() }}</h3>
                    <p class="text-muted">Tests Total</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h3>{{ \App\Models\RecruiterSkillTest::where('is_active', true)->count() }}</h3>
                    <p class="text-muted">Tests Actifs</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h3>{{ \App\Models\ApplicationTestResult::count() }}</h3>
                    <p class="text-muted">Résultats Total</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h3>{{ \App\Models\ApplicationTestResult::where('passed', true)->count() }}</h3>
                    <p class="text-muted">Tests Réussis</p>
                </div>
            </div>
        </div>
    </div>
@endsection
