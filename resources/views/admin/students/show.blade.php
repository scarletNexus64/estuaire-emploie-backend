@extends('admin.layouts.app')

@section('title', 'Détails Étudiant')
@section('page-title', 'Détails de l\'Étudiant')

@section('breadcrumbs')
    <span> / </span>
    <a href="{{ route('admin.students.index') }}">Étudiants</a>
    <span> / </span>
    <span>{{ $student->name }}</span>
@endsection

@section('content')
<div style="display: grid; gap: 1.5rem;">
    <!-- Actions -->
    <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
        <a href="{{ route('admin.students.edit', $student->id) }}" class="btn btn-warning">✏️ Modifier</a>

        @if(isset($resume) && $resume)
            {{-- <a href="{{ route('admin.students.create-cv', $student->id) }}" class="btn btn-info">✏️ Modifier le CV</a> --}}
            @if($resume->pdf_path)
                <a href="{{ asset('storage/' . $resume->pdf_path) }}" target="_blank" class="btn btn-success">👁️ Voir le CV (PDF)</a>
            @endif
        @else
            <a href="{{ route('admin.students.create-cv', $student->id) }}" class="btn btn-primary">➕ Créer un CV</a>
        @endif

        <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">← Retour à la liste</a>
        <form action="{{ route('admin.students.destroy', $student->id) }}" method="POST" style="display: inline;"
              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet étudiant ?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">🗑️ Supprimer</button>
        </form>
    </div>

    <!-- CV Info -->
    @if(isset($resume) && $resume)
    <div class="card">
        <div class="card-header">
            <h3 style="margin: 0;">📄 Curriculum Vitae</h3>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div>
                    <strong>Titre :</strong> {{ $resume->title ?? 'N/A' }}
                </div>
                <div>
                    <strong>Template :</strong>
                    <span class="badge badge-info">{{ ucfirst($resume->template_type) }}</span>
                </div>
                <div>
                    <strong>Complétude :</strong>
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <div style="flex: 1; background: #e5e7eb; border-radius: 9999px; height: 8px; max-width: 200px;">
                            <div style="background: linear-gradient(to right, #3b82f6, #8b5cf6); height: 8px; border-radius: 9999px; width: {{ $resume->calculateCompleteness() }}%;"></div>
                        </div>
                        <span style="font-weight: 600;">{{ $resume->calculateCompleteness() }}%</span>
                    </div>
                </div>
                <div>
                    <strong>Statut :</strong>
                    @if($resume->is_public)
                        <span class="badge badge-success">Public</span>
                    @else
                        <span class="badge badge-secondary">Privé</span>
                    @endif
                    @if($resume->is_default)
                        <span class="badge badge-primary">Par défaut</span>
                    @endif
                </div>
            </div>
            <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid #e5e7eb;">
                <strong>CV créé le :</strong> {{ $resume->created_at->format('d/m/Y à H:i') }}<br>
                @if($resume->pdf_generated_at)
                <strong>PDF généré le :</strong> {{ $resume->pdf_generated_at->format('d/m/Y à H:i') }}
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Student Info -->
    <div class="card">
        <div class="card-header">
            <h3 style="margin: 0;">👤 Informations Personnelles</h3>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div>
                    <strong>Nom :</strong> {{ $student->name }}
                </div>
                <div>
                    <strong>Email :</strong> {{ $student->email }}
                </div>
                <div>
                    <strong>Téléphone :</strong> {{ $student->phone }}
                </div>
                <div>
                    <strong>Rôle :</strong>
                    <span class="badge badge-info">{{ ucfirst($student->role) }}</span>
                </div>
                <div>
                    <strong>Spécialité :</strong>
                    @if($student->specialty)
                        <span class="badge badge-primary">{{ $student->specialty }}</span>
                    @else
                        <span class="badge badge-secondary">Non définie</span>
                    @endif
                </div>
                <div>
                    <strong>Niveau :</strong>
                    @if($student->level)
                        <span class="badge badge-success">{{ $student->level }}</span>
                    @else
                        <span class="badge badge-secondary">Non défini</span>
                    @endif
                </div>
            </div>

            @if($student->interests)
            <div style="margin-top: 1.5rem;">
                <strong>Centre d'Intérêt :</strong>
                <p style="margin-top: 0.5rem; padding: 1rem; background: #f8f9fa; border-radius: 4px;">
                    {{ $student->interests }}
                </p>
            </div>
            @endif

            <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid #e5e7eb;">
                <strong>Inscription :</strong> {{ $student->created_at->format('d/m/Y à H:i') }}<br>
                <strong>Dernière mise à jour :</strong> {{ $student->updated_at->format('d/m/Y à H:i') }}
            </div>
        </div>
    </div>

    <!-- Benefits -->
    @if(count($benefits) > 0)
    <div class="card">
        <div class="card-header">
            <h3 style="margin: 0;">🎁 Avantages Activés</h3>
        </div>
        <div class="card-body">
            @if(isset($benefits['subscription']))
            <div style="margin-bottom: 1.5rem;">
                <h4>🥈 {{ $benefits['subscription']['name'] ?? 'Pack SILVER (C1)' }}</h4>
                <p><strong>Expire le :</strong> {{ $benefits['subscription']['expires_at'] }}</p>
                @if(isset($benefits['subscription']['features']))
                <ul>
                    @foreach($benefits['subscription']['features'] as $key => $value)
                        @if($value === true)
                            <li>{{ ucfirst(str_replace('_', ' ', $key)) }}</li>
                        @endif
                    @endforeach
                </ul>
                @endif
            </div>
            @endif

            @if(isset($benefits['student_mode']))
            <div>
                <h4>🎓 {{ $benefits['student_mode']['name'] ?? 'Mode Étudiant' }}</h4>
                <p><strong>Expire le :</strong> {{ $benefits['student_mode']['expires_at'] }}</p>
                @if(isset($benefits['student_mode']['features']))
                <ul>
                    @foreach($benefits['student_mode']['features'] as $feature)
                        <li>{{ $feature }}</li>
                    @endforeach
                </ul>
                @endif
            </div>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection
