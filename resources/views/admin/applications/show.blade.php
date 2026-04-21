@extends('admin.layouts.app')

@section('title', 'Détails Candidature')
@section('page-title', 'Détails de la Candidature')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Candidature de {{ $application->user?->name ?? 'N/A' }}</h3>
            <a href="{{ route('admin.applications.index') }}" class="btn btn-secondary">Retour</a>
        </div>

        <div style="padding: 1.5rem;">
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
                <div>
                    <h4 style="margin-bottom: 1rem; font-weight: 600;">Informations du Candidat</h4>
                    <p><strong>Nom:</strong> {{ $application->user?->name ?? 'N/A' }}</p>
                    <p><strong>Email:</strong> {{ $application->user?->email ?? 'N/A' }}</p>
                    <p><strong>Téléphone:</strong> {{ $application->user?->phone ?? 'N/A' }}</p>
                    <p><strong>Niveau d'expérience:</strong> {{ ucfirst($application->user?->experience_level ?? 'N/A') }}</p>

                    @if($application->user?->skills)
                        <p><strong>Compétences:</strong> {{ $application->user->skills }}</p>
                    @endif

                    @if($application->latitude && $application->longitude)
                        <h4 style="margin-top: 2rem; margin-bottom: 1rem; font-weight: 600;">📍 Localisation du Candidat</h4>
                        <div style="border: 1px solid #dee2e6; border-radius: 4px; padding: 1rem; background-color: #f8f9fa;">
                            <p><strong>Coordonnées GPS:</strong></p>
                            <p style="margin-left: 1rem;">
                                <strong>Latitude:</strong> {{ $application->latitude }}<br>
                                <strong>Longitude:</strong> {{ $application->longitude }}
                            </p>

                            @if($application->address || $application->city || $application->country)
                                <p style="margin-top: 1rem;"><strong>Adresse:</strong></p>
                                <p style="margin-left: 1rem;">
                                    @if($application->address)
                                        {{ $application->address }}<br>
                                    @endif
                                    @if($application->city)
                                        {{ $application->city }}
                                    @endif
                                    @if($application->country)
                                        , {{ $application->country }}
                                    @endif
                                </p>
                            @endif

                            <p style="margin-top: 1rem;">
                                <a href="https://www.google.com/maps?q={{ $application->latitude }},{{ $application->longitude }}"
                                   target="_blank"
                                   class="btn btn-primary"
                                   style="display: inline-block; padding: 0.5rem 1rem; background-color: #007bff; color: white; text-decoration: none; border-radius: 4px;">
                                    <i class="fas fa-map-marker-alt"></i> Voir sur Google Maps
                                </a>
                            </p>
                        </div>
                    @endif

                    <h4 style="margin-top: 2rem; margin-bottom: 1rem; font-weight: 600;">Offre d'Emploi</h4>
                    <p><strong>Titre:</strong> {{ $application->job?->title ?? 'N/A' }}</p>
                    <p><strong>Entreprise:</strong> {{ $application->job?->company?->name ?? 'N/A' }}</p>

                    <h4 style="margin-top: 2rem; margin-bottom: 1rem; font-weight: 600;">CV</h4>
                    @if($application->cv_path)
                        <p>
                            <a href="{{ asset('storage/' . $application->cv_path) }}"
                               target="_blank"
                               class="btn btn-primary"
                               style="display: inline-block; padding: 0.5rem 1rem; background-color: #007bff; color: white; text-decoration: none; border-radius: 4px;">
                                <i class="fas fa-file-download"></i> Télécharger le CV
                            </a>
                        </p>
                    @else
                        <p style="color: #dc3545;">Aucun CV fourni</p>
                    @endif

                    <h4 style="margin-top: 2rem; margin-bottom: 1rem; font-weight: 600;">Lettre de Motivation</h4>
                    <p style="white-space: pre-wrap;">{{ $application->cover_letter ?? 'Aucune lettre de motivation fournie' }}</p>

                    @if($application->portfolio_url)
                        <h4 style="margin-top: 2rem; margin-bottom: 1rem; font-weight: 600;">Portfolio (Lien externe)</h4>
                        <p>
                            <a href="{{ $application->portfolio_url }}" target="_blank" class="btn btn-secondary"
                               style="display: inline-block; padding: 0.5rem 1rem; background-color: #6c757d; color: white; text-decoration: none; border-radius: 4px;">
                                <i class="fas fa-external-link-alt"></i> Ouvrir le portfolio externe
                            </a>
                        </p>
                        <p style="word-break: break-all; color: #6c757d; font-size: 0.875rem;">{{ $application->portfolio_url }}</p>
                    @endif

                    @if($application->portfolio)
                        <h4 style="margin-top: 2rem; margin-bottom: 1rem; font-weight: 600;">Portfolio attaché</h4>
                        <div style="border: 1px solid #dee2e6; border-radius: 4px; padding: 1rem; background-color: #f8f9fa;">
                            <p><strong>Titre:</strong> {{ $application->portfolio->title ?? 'Portfolio' }}</p>
                            @if($application->portfolio->bio)
                                <p><strong>Bio:</strong> {{ $application->portfolio->bio }}</p>
                            @endif
                            <p><strong>Visibilité:</strong> {{ $application->portfolio->is_public ? 'Public' : 'Privé' }}</p>
                            @if($application->portfolio->view_count)
                                <p><strong>Vues:</strong> {{ $application->portfolio->view_count }}</p>
                            @endif
                            <p>
                                <a href="{{ route('admin.portfolios.show', $application->portfolio) }}"
                                   class="btn btn-primary"
                                   style="display: inline-block; padding: 0.5rem 1rem; background-color: #007bff; color: white; text-decoration: none; border-radius: 4px; margin-right: 0.5rem;">
                                    <i class="fas fa-eye"></i> Voir le portfolio complet
                                </a>
                                @if($application->portfolio->is_public)
                                    <a href="{{ url('/portfolio/' . $application->portfolio->slug) }}"
                                       target="_blank"
                                       class="btn btn-secondary"
                                       style="display: inline-block; padding: 0.5rem 1rem; background-color: #6c757d; color: white; text-decoration: none; border-radius: 4px;">
                                        <i class="fas fa-external-link-alt"></i> Vue publique
                                    </a>
                                @endif
                            </p>
                        </div>
                    @endif
                </div>

                <div>
                    <h4 style="margin-bottom: 1rem; font-weight: 600;">Statut de la Candidature</h4>

                    <form action="{{ route('admin.applications.status', $application) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="form-group">
                            <label class="form-label">Changer le statut</label>
                            <select name="status" class="form-control">
                                <option value="accepted" {{ $application->status === 'accepted' ? 'selected' : '' }}>✅ Acceptée</option>
                                <option value="rejected" {{ $application->status === 'rejected' ? 'selected' : '' }}>❌ Rejetée</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Notes internes</label>
                            <textarea name="internal_notes" class="form-control" rows="4">{{ $application->internal_notes }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">Mettre à jour</button>
                    </form>

                    <div style="margin-top: 2rem;">
                        <p><strong>Date de candidature:</strong> {{ $application->created_at->format('d/m/Y H:i') }}</p>
                        @if($application->viewed_at)
                            <p><strong>Vue le:</strong> {{ $application->viewed_at->format('d/m/Y H:i') }}</p>
                        @endif
                        @if($application->responded_at)
                            <p><strong>Répondu le:</strong> {{ $application->responded_at->format('d/m/Y H:i') }}</p>
                        @endif
                    </div>

                    <!-- Diploma Verification Section -->
                    <div style="margin-top: 2rem; padding: 1rem; border: 1px solid #dee2e6; border-radius: 4px; background-color: #f8f9fa;">
                        <h4 style="margin-bottom: 1rem; font-weight: 600;">🎓 Vérification de Diplômes</h4>

                        @if($application->diploma_verified)
                            <div style="padding: 1rem; background-color: #d4edda; border: 1px solid #c3e6cb; border-radius: 4px; margin-bottom: 1rem;">
                                <p style="margin: 0; color: #155724;">
                                    <strong>✅ Diplôme vérifié</strong>
                                </p>
                                <p style="margin: 0.5rem 0 0 0; font-size: 0.875rem; color: #155724;">
                                    Vérifié le: {{ $application->diploma_verified_at->format('d/m/Y H:i') }}
                                    @if($application->diplomaVerifier)
                                        <br>Par: {{ $application->diplomaVerifier->name }}
                                    @endif
                                </p>
                                @if($application->diploma_verification_notes)
                                    <p style="margin: 0.5rem 0 0 0; font-size: 0.875rem; color: #155724;">
                                        <strong>Notes:</strong> {{ $application->diploma_verification_notes }}
                                    </p>
                                @endif
                            </div>
                        @else
                            <form action="{{ route('admin.applications.verify-diploma', $application) }}" method="POST">
                                @csrf
                                @method('PATCH')

                                <div class="form-group">
                                    <label class="form-label">Notes de vérification (optionnel)</label>
                                    <textarea name="verification_notes" class="form-control" rows="3" placeholder="Ajouter des notes sur la vérification..."></textarea>
                                </div>

                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-check"></i> Marquer comme vérifié
                                </button>
                            </form>
                        @endif
                    </div>

                    <!-- Test Results Section -->
                    @if($application->testResults && $application->testResults->count() > 0)
                        <div style="margin-top: 2rem; padding: 1rem; border: 1px solid #dee2e6; border-radius: 4px; background-color: #f8f9fa;">
                            <h4 style="margin-bottom: 1rem; font-weight: 600;">📊 Résultats des Tests</h4>

                            @foreach($application->testResults as $result)
                                <div style="padding: 1rem; background-color: #ffffff; border: 1px solid #dee2e6; border-radius: 4px; margin-bottom: 1rem;">
                                    <h5 style="margin: 0 0 0.5rem 0;">{{ $result->test->title }}</h5>
                                    <p style="margin: 0;">
                                        <strong>Score:</strong> {{ $result->score }}%
                                        @if($result->passed)
                                            <span style="color: #28a745;">✅ Réussi</span>
                                        @else
                                            <span style="color: #dc3545;">❌ Échoué</span>
                                        @endif
                                    </p>
                                    <p style="margin: 0.5rem 0 0 0; font-size: 0.875rem; color: #6c757d;">
                                        Score minimal: {{ $result->test->passing_score }}%
                                        @if($result->completed_at)
                                            | Complété le: {{ $result->completed_at->format('d/m/Y H:i') }}
                                        @endif
                                        @if($result->duration_seconds)
                                            | Durée: {{ gmdate('i:s', $result->duration_seconds) }}
                                        @endif
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
