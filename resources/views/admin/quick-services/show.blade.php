@extends('admin.layouts.app')

@section('title', 'Détails Service')
@section('page-title', 'Détails du Service Rapide')

@section('breadcrumbs')
    <span> / </span>
    <a href="{{ route('admin.quick-services.index') }}" style="color: #0091D5; text-decoration: none;">Services Rapides</a>
    <span> / </span>
    <span>Détails</span>
@endsection

@section('header-actions')
    <a href="{{ route('admin.quick-services.index') }}" class="btn btn-secondary">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Retour
    </a>
@endsection

@section('content')
    <!-- En-tête avec actions -->
    <div class="card" style="margin-bottom: 1.5rem;">
        <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 1.5rem;">
            <div style="flex: 1;">
                <h3 style="font-size: 1.5rem; font-weight: 700; margin: 0 0 0.5rem 0; color: white;">{{ $service->title }}</h3>
                <div style="display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;">
                    <span style="display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(255,255,255,0.2); padding: 0.25rem 0.75rem; border-radius: 0.5rem; font-size: 0.875rem;">
                        <i class="mdi mdi-eye"></i> {{ $service->views_count }} vues
                    </span>
                    <span style="display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(255,255,255,0.2); padding: 0.25rem 0.75rem; border-radius: 0.5rem; font-size: 0.875rem;">
                        <i class="mdi mdi-comment"></i> {{ $service->responses_count }} réponses
                    </span>
                    <span style="display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(255,255,255,0.2); padding: 0.25rem 0.75rem; border-radius: 0.5rem; font-size: 0.875rem;">
                        <i class="mdi mdi-clock"></i> {{ $service->created_at->diffForHumans() }}
                    </span>
                </div>
            </div>
            <div style="display: flex; gap: 0.5rem; align-items: flex-start;">
                @if($service->status === 'pending')
                    <form action="{{ route('admin.quick-services.approve', $service->id) }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-success" onclick="return confirm('Approuver ce service ? Des notifications seront envoyées à tous les utilisateurs.')" style="font-weight: 600; padding: 0.75rem 1.5rem; box-shadow: 0 4px 6px rgba(34, 197, 94, 0.3);">
                            <i class="mdi mdi-check-circle"></i> Approuver et Publier
                        </button>
                    </form>
                @else
                    <form action="{{ route('admin.quick-services.status', $service->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('PATCH')
                        <select name="status" class="form-control" style="background: white; border: 2px solid rgba(255,255,255,0.3); color: #1e293b; font-weight: 600; padding: 0.5rem 1rem;" onchange="this.form.submit()">
                            <option value="open" {{ $service->status === 'open' ? 'selected' : '' }}>✅ Ouvert</option>
                            <option value="in_progress" {{ $service->status === 'in_progress' ? 'selected' : '' }}>⏳ En cours</option>
                            <option value="completed" {{ $service->status === 'completed' ? 'selected' : '' }}>🎉 Complété</option>
                            <option value="cancelled" {{ $service->status === 'cancelled' ? 'selected' : '' }}>❌ Annulé</option>
                        </select>
                    </form>
                @endif

                <form action="{{ route('admin.quick-services.destroy', $service->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Supprimer ce service ?')">
                        <i class="mdi mdi-delete"></i> Supprimer
                    </button>
                </form>
            </div>
        </div>

        <div style="padding: 2rem;">
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2.5rem;">
                <!-- Colonne principale -->
                <div>
                    <div style="background: #f8fafc; padding: 1.5rem; border-radius: 0.75rem; margin-bottom: 1.5rem;">
                        <h4 style="margin-bottom: 1rem; font-weight: 700; font-size: 1.125rem; color: #1e293b; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="mdi mdi-text-box-outline" style="color: #667eea;"></i>
                            Description du service
                        </h4>
                        <p style="white-space: pre-wrap; line-height: 1.6; color: #475569;">{{ $service->description }}</p>
                    </div>

                    @if($service->images && count($service->images) > 0)
                        <div style="background: #f8fafc; padding: 1.5rem; border-radius: 0.75rem;">
                            <h4 style="margin-bottom: 1rem; font-weight: 700; font-size: 1.125rem; color: #1e293b; display: flex; align-items: center; gap: 0.5rem;">
                                <i class="mdi mdi-image-multiple" style="color: #667eea;"></i>
                                Images ({{ count($service->images) }})
                            </h4>
                            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem;">
                                @foreach($service->images as $image)
                                    <div style="position: relative; overflow: hidden; border-radius: 0.75rem; box-shadow: 0 4px 6px rgba(0,0,0,0.1); transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                                        <img src="{{ asset('storage/' . $image) }}" alt="Image service" style="width: 100%; height: 200px; object-fit: cover;">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Colonne informations -->
                <div>
                    <div style="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); padding: 1.5rem; border-radius: 0.75rem; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                        <h4 style="margin-bottom: 1.5rem; font-weight: 700; font-size: 1.125rem; color: #1e293b; display: flex; align-items: center; gap: 0.5rem; padding-bottom: 1rem; border-bottom: 2px solid #cbd5e1;">
                            <i class="mdi mdi-information" style="color: #667eea;"></i>
                            Informations
                        </h4>

                        <!-- Info item -->
                        <div style="margin-bottom: 1.25rem; padding-bottom: 1.25rem; border-bottom: 1px solid #e2e8f0;">
                            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                                <i class="mdi mdi-account" style="color: #667eea; font-size: 1.25rem;"></i>
                                <strong style="color: #475569; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.5px;">Utilisateur</strong>
                            </div>
                            <div style="color: #1e293b; font-weight: 600;">{{ $service->user?->name ?? 'N/A' }}</div>
                            <small style="color: #64748b;">{{ $service->user?->phone ?? $service->user?->email }}</small>
                        </div>

                        <div style="margin-bottom: 1.25rem; padding-bottom: 1.25rem; border-bottom: 1px solid #e2e8f0;">
                            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                                <i class="mdi mdi-tag" style="color: #667eea; font-size: 1.25rem;"></i>
                                <strong style="color: #475569; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.5px;">Catégorie</strong>
                            </div>
                            @if($service->category)
                                <div style="display: flex; align-items: center; gap: 0.75rem; background: white; padding: 0.75rem; border-radius: 0.5rem;">
                                    @if($service->category->icon)
                                        <i class="mdi {{ $service->category->icon }}" style="font-size: 28px; color: {{ $service->category->color ?? '#667eea' }};"></i>
                                    @endif
                                    <span style="font-weight: 600; color: #1e293b;">{{ $service->category->name }}</span>
                                </div>
                            @else
                                <span style="color: #94a3b8;">Non spécifié</span>
                            @endif
                        </div>

                        <div style="margin-bottom: 1.25rem; padding-bottom: 1.25rem; border-bottom: 1px solid #e2e8f0;">
                            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                                <i class="mdi mdi-cash" style="color: #667eea; font-size: 1.25rem;"></i>
                                <strong style="color: #475569; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.5px;">Prix</strong>
                            </div>
                            @if($service->price_type === 'negotiable')
                                <span class="badge badge-info" style="font-size: 0.875rem; padding: 0.5rem 1rem;">💬 À négocier</span>
                            @elseif($service->price_type === 'range')
                                <div style="background: white; padding: 0.75rem; border-radius: 0.5rem; font-weight: 600; color: #1e293b;">
                                    {{ number_format($service->price_min, 0, ',', ' ') }} - {{ number_format($service->price_max, 0, ',', ' ') }} FCFA
                                </div>
                            @else
                                <div style="background: white; padding: 0.75rem; border-radius: 0.5rem; font-weight: 700; color: #10b981; font-size: 1.125rem;">
                                    {{ number_format($service->price_min, 0, ',', ' ') }} FCFA
                                </div>
                            @endif
                        </div>

                        <div style="margin-bottom: 1.25rem; padding-bottom: 1.25rem; border-bottom: 1px solid #e2e8f0;">
                            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                                <i class="mdi mdi-map-marker" style="color: #667eea; font-size: 1.25rem;"></i>
                                <strong style="color: #475569; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.5px;">Localisation</strong>
                            </div>
                            <div style="color: #1e293b; font-weight: 600;">{{ $service->location_name ?? 'Non spécifié' }}</div>
                            <small style="color: #64748b;">📍 {{ $service->latitude }}, {{ $service->longitude }}</small>
                        </div>

                        <div style="margin-bottom: 1.25rem; padding-bottom: 1.25rem; border-bottom: 1px solid #e2e8f0;">
                            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                                <i class="mdi mdi-clock-fast" style="color: #667eea; font-size: 1.25rem;"></i>
                                <strong style="color: #475569; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.5px;">Urgence</strong>
                            </div>
                            @if($service->urgency === 'urgent')
                                <span class="badge badge-danger" style="font-size: 0.875rem; padding: 0.5rem 1rem;">🔥 Urgent</span>
                            @elseif($service->urgency === 'this_week')
                                <span class="badge badge-warning" style="font-size: 0.875rem; padding: 0.5rem 1rem;">⏰ Cette semaine</span>
                            @elseif($service->urgency === 'this_month')
                                <span class="badge badge-info" style="font-size: 0.875rem; padding: 0.5rem 1rem;">📅 Ce mois</span>
                            @else
                                <span class="badge badge-secondary" style="font-size: 0.875rem; padding: 0.5rem 1rem;">🕐 Flexible</span>
                            @endif
                        </div>

                        @if($service->desired_date)
                        <div style="margin-bottom: 1.25rem; padding-bottom: 1.25rem; border-bottom: 1px solid #e2e8f0;">
                            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                                <i class="mdi mdi-calendar" style="color: #667eea; font-size: 1.25rem;"></i>
                                <strong style="color: #475569; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.5px;">Date souhaitée</strong>
                            </div>
                            <div style="color: #1e293b; font-weight: 600;">{{ $service->desired_date->format('d/m/Y') }}</div>
                        </div>
                        @endif

                        @if($service->estimated_duration)
                        <div style="margin-bottom: 1.25rem; padding-bottom: 1.25rem; border-bottom: 1px solid #e2e8f0;">
                            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                                <i class="mdi mdi-timer" style="color: #667eea; font-size: 1.25rem;"></i>
                                <strong style="color: #475569; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.5px;">Durée estimée</strong>
                            </div>
                            <div style="color: #1e293b; font-weight: 600;">{{ $service->estimated_duration }}</div>
                        </div>
                        @endif

                        <div style="margin-bottom: 1.25rem; padding-bottom: 1.25rem; border-bottom: 1px solid #e2e8f0;">
                            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                                <i class="mdi mdi-calendar-clock" style="color: #667eea; font-size: 1.25rem;"></i>
                                <strong style="color: #475569; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.5px;">Dates</strong>
                            </div>
                            <div style="color: #64748b; font-size: 0.875rem; margin-bottom: 0.25rem;">
                                <strong>Créé:</strong> {{ $service->created_at->format('d/m/Y H:i') }}
                            </div>
                            @if($service->expires_at)
                            <div style="color: #64748b; font-size: 0.875rem;">
                                <strong>Expire:</strong> {{ $service->expires_at->format('d/m/Y') }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card" style="margin-top: 1.5rem;">
        <div class="card-header">
            <h3 class="card-title">Réponses ({{ $service->responses->count() }})</h3>
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Utilisateur</th>
                        <th>Message</th>
                        <th>Prix proposé</th>
                        <th>Statut</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($service->responses as $response)
                    <tr>
                        <td>
                            <strong>{{ $response->user?->name ?? 'N/A' }}</strong><br>
                            <small style="color: var(--secondary);">{{ $response->user?->phone ?? $response->user?->email }}</small>
                        </td>
                        <td style="max-width: 400px;">{{ $response->message }}</td>
                        <td>
                            @if($response->proposed_price)
                                <strong>{{ number_format($response->proposed_price, 0, ',', ' ') }} FCFA</strong>
                            @else
                                <span style="color: var(--secondary);">Non spécifié</span>
                            @endif
                        </td>
                        <td>
                            @if($response->status === 'accepted')
                                <span class="badge badge-success">Acceptée</span>
                            @elseif($response->status === 'rejected')
                                <span class="badge badge-danger">Rejetée</span>
                            @else
                                <span class="badge badge-warning">En attente</span>
                            @endif
                        </td>
                        <td>{{ $response->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 2rem; color: var(--secondary);">
                            Aucune réponse pour ce service
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
