@extends('admin.layouts.app')

@section('title', 'Gestion des Étudiants')
@section('page-title', 'Gestion des Étudiants')

@section('breadcrumbs')
    <span> / </span>
    <span>Étudiants</span>
@endsection

@section('content')
<!-- Stats Overview -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-label">Total Étudiants</div>
                <div class="stat-value">{{ $students->total() }}</div>
            </div>
            <div class="stat-icon">🎓</div>
        </div>
    </div>
</div>

<!-- Filters & Search -->
<div class="card" style="margin-bottom: 1.5rem;">
    <form method="GET" action="{{ route('admin.students.index') }}">
        <div style="display: grid; grid-template-columns: 2fr 1fr auto; gap: 1rem; align-items: end;">
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Rechercher</label>
                <input type="text" name="search" class="form-control" placeholder="Nom, email, téléphone, spécialité..." value="{{ request('search') }}">
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Niveau</label>
                <input type="text" name="level" class="form-control" placeholder="Ex: L1, L2, M1..." value="{{ request('level') }}">
            </div>

            <div>
                <button type="submit" class="btn btn-primary">Filtrer</button>
                <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">Réinitialiser</a>
            </div>
        </div>
    </form>
</div>

<!-- Actions -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
    <div>
        <a href="{{ route('admin.students.create') }}" class="btn btn-primary">
            ➕ Créer un Étudiant
        </a>
    </div>
</div>

<!-- Table -->
<div class="card">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Spécialité</th>
                    <th>Niveau</th>
                    <th>Mode Étudiant</th>
                    <th>Pack C1</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                <tr>
                    <td>
                        <strong>{{ $student->name }}</strong>
                    </td>
                    <td>{{ $student->email }}</td>
                    <td>{{ $student->phone }}</td>
                    <td>
                        @if($student->specialty)
                            <span class="badge badge-info">{{ $student->specialty }}</span>
                        @else
                            <span class="badge badge-secondary">-</span>
                        @endif
                    </td>
                    <td>
                        @if($student->level)
                            <span class="badge badge-primary">{{ $student->level }}</span>
                        @else
                            <span class="badge badge-secondary">-</span>
                        @endif
                    </td>
                    <td>
                        @if($student->hasPremiumService('student_mode'))
                            <span class="badge badge-success">✅ Actif</span>
                        @else
                            <span class="badge badge-danger">❌ Inactif</span>
                        @endif
                    </td>
                    <td>
                        @php
                            $subscription = $student->activeSubscription('candidate');
                        @endphp
                        @if($subscription && $subscription->isValid())
                            <span class="badge badge-success">✅ Actif</span>
                        @else
                            <span class="badge badge-danger">❌ Expiré</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.students.show', $student->id) }}" class="btn btn-sm btn-primary">👁️ Voir</a>
                        <a href="{{ route('admin.students.edit', $student->id) }}" class="btn btn-sm btn-warning">✏️ Modifier</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center" style="padding: 3rem;">
                        <p style="font-size: 1.1rem; color: #6c757d;">Aucun étudiant trouvé</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($students->hasPages())
    <div style="padding: 1.5rem; border-top: 1px solid #e5e7eb;">
        {{ $students->links() }}
    </div>
    @endif
</div>
@endsection
