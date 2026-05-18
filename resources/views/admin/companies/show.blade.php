@extends('admin.layouts.app')

@section('title', 'Détails Entreprise')
@section('page-title', 'Détails de l\'Entreprise')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ $company->name }}</h3>
            <div>
                @if($company->status === 'pending')
                    <form action="{{ route('admin.companies.verify', $company) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success">Vérifier</button>
                    </form>
                @endif
                @if($company->status !== 'suspended')
                    <form action="{{ route('admin.companies.suspend', $company) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-danger">Suspendre</button>
                    </form>
                @endif
                <a href="{{ route('admin.companies.edit', $company) }}" class="btn btn-primary">Éditer</a>
            </div>
        </div>

        <div style="padding: 1.5rem;">
            <!-- Logo Section -->
            @if($company->logo)
            <div style="text-align: center; margin-bottom: 2rem;">
                <img src="{{ $company->logo_url }}"
                     alt="Logo {{ $company->name }}"
                     style="max-width: 200px; max-height: 200px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);"
                     onerror="this.style.display='none'">
            </div>
            @endif

            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 2rem;">
                <div>
                    <h4 style="margin-bottom: 1rem; font-weight: 600;">Informations Générales</h4>
                    <p><strong>Email:</strong> {{ $company->email }}</p>
                    <p><strong>Téléphone:</strong> {{ $company->phone ?? 'N/A' }}</p>
                    <p><strong>Domaine:</strong> {{ $company->domain ?? 'N/A' }}</p>
                    <p><strong>Secteur:</strong> {{ $company->sector ?? 'N/A' }}</p>
                    <p><strong>Site web:</strong>
                        @if($company->website)
                            <a href="{{ $company->website }}" target="_blank">{{ $company->website }}</a>
                        @else
                            N/A
                        @endif
                    </p>
                    <p><strong>Adresse:</strong> {{ $company->address ?? 'N/A' }}</p>
                    <p><strong>Ville:</strong> {{ $company->city ?? 'N/A' }}</p>
                    <p><strong>Pays:</strong> {{ $company->country }}</p>
                    <p><strong>Coordonnées GPS:</strong>
                        @if($company->latitude && $company->longitude)
                            <span style="color: #28a745;">
                                📍 {{ number_format($company->latitude, 6) }}, {{ number_format($company->longitude, 6) }}
                            </span>
                            <a href="https://www.google.com/maps?q={{ $company->latitude }},{{ $company->longitude }}"
                               target="_blank"
                               class="btn btn-sm btn-info"
                               style="margin-left: 10px;">
                                🗺️ Voir sur Google Maps
                            </a>
                        @else
                            <span style="color: #dc3545;">Non renseignées</span>
                        @endif
                    </p>
                    <p><strong>Statut:</strong>
                        @if($company->status === 'verified')
                            <span class="badge badge-success">Vérifiée</span>
                        @elseif($company->status === 'pending')
                            <span class="badge badge-warning">En attente</span>
                        @else
                            <span class="badge badge-danger">Suspendue</span>
                        @endif
                    </p>
                    <p><strong>Plan:</strong>
                        @if($company->subscription_plan === 'premium')
                            <span class="badge badge-success">Premium</span>
                        @else
                            <span class="badge badge-secondary">Gratuit</span>
                        @endif
                    </p>
                    <p><strong>Date d'inscription:</strong> {{ $company->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <h4 style="margin-bottom: 1rem; font-weight: 600;">Description</h4>
                    <p>{{ $company->description ?? 'Aucune description disponible' }}</p>
                </div>
            </div>

            <!-- Google Maps Preview Section -->
            @if($company->latitude && $company->longitude)
            <div style="margin-top: 2rem;">
                <h4 style="margin-bottom: 1rem; font-weight: 600;">🗺️ Localisation sur la Carte</h4>
                <div style="border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                    <iframe
                        width="100%"
                        height="400"
                        frameborder="0"
                        style="border:0"
                        referrerpolicy="no-referrer-when-downgrade"
                        src="https://www.google.com/maps/embed/v1/place?key=AIzaSyAffUHSFli6kMnjkfJOKBGO6AN828ixJPo&q={{ $company->latitude }},{{ $company->longitude }}&zoom=15"
                        allowfullscreen>
                    </iframe>
                </div>
                <p style="margin-top: 0.5rem; font-size: 0.875rem; color: #6c757d;">
                    📍 Coordonnées: {{ number_format($company->latitude, 6) }}, {{ number_format($company->longitude, 6) }}
                </p>
            </div>
            @elseif($company->address || $company->city)
            <div style="margin-top: 2rem; padding: 1.5rem; background-color: #fff3cd; border-left: 4px solid #ffc107; border-radius: 8px;">
                <h4 style="margin-bottom: 0.5rem; color: #856404;">⚠️ Coordonnées GPS manquantes</h4>
                <p style="margin: 0; color: #856404;">
                    Cette entreprise a une adresse mais pas de coordonnées GPS.
                    <a href="{{ route('admin.companies.edit', $company) }}" style="color: #004085; text-decoration: underline;">
                        Cliquez ici pour les ajouter
                    </a>
                </p>
            </div>
            @endif

            <!-- Company Photos Gallery Section -->
            @if($company->photos && count($company->photos) > 0)
            <div style="margin-top: 2rem;">
                <h4 style="margin-bottom: 1rem; font-weight: 600;">📸 Photos de l'Entreprise ({{ count($company->photos) }})</h4>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 1rem;">
                    @foreach($company->photos_urls as $index => $photoUrl)
                        <div style="border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1); position: relative; background: #f8f9fa;">
                            <img src="{{ $photoUrl }}"
                                 alt="Photo {{ $index + 1 }} - {{ $company->name }}"
                                 style="width: 100%; height: 200px; object-fit: cover; cursor: pointer;"
                                 onclick="window.open('{{ $photoUrl }}', '_blank')"
                                 onerror="this.parentElement.innerHTML='<div style=\'display: flex; align-items: center; justify-content: center; height: 200px; color: #dc3545;\'>❌ Erreur de chargement</div>'">
                            <div style="position: absolute; top: 8px; right: 8px; background: rgba(0,0,0,0.6); color: white; padding: 4px 8px; border-radius: 4px; font-size: 12px;">
                                Photo {{ $index + 1 }}
                            </div>
                        </div>
                    @endforeach
                </div>
                <p style="margin-top: 0.5rem; font-size: 0.875rem; color: #6c757d;">
                    💡 Cliquez sur une photo pour l'agrandir dans un nouvel onglet
                </p>
            </div>
            @else
            <div style="margin-top: 2rem; padding: 1.5rem; background-color: #f8f9fa; border-left: 4px solid #6c757d; border-radius: 8px;">
                <h4 style="margin-bottom: 0.5rem; color: #495057;">📸 Aucune photo disponible</h4>
                <p style="margin: 0; color: #6c757d;">
                    Cette entreprise n'a pas encore ajouté de photos de présentation.
                </p>
            </div>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Recruteurs ({{ $company->recruiters->count() }})</h3>
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Poste</th>
                        <th>Permissions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($company->recruiters as $recruiter)
                        <tr>
                            <td>{{ $recruiter->user?->name ?? 'N/A' }}</td>
                            <td>{{ $recruiter->user?->email ?? 'N/A' }}</td>
                            <td>{{ $recruiter->position ?? 'N/A' }}</td>
                            <td>
                                @if($recruiter->can_publish) <span class="badge badge-success">Publier</span> @endif
                                @if($recruiter->can_view_applications) <span class="badge badge-info">Voir candidatures</span> @endif
                                @if($recruiter->can_modify_company) <span class="badge badge-warning">Modifier entreprise</span> @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 2rem;">Aucun recruteur</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Offres d'Emploi ({{ $company->jobs->count() }})</h3>
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Statut</th>
                        <th>Candidatures</th>
                        <th>Vues</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($company->jobs as $job)
                        <tr>
                            <td>{{ $job->title }}</td>
                            <td>
                                @if($job->status === 'published')
                                    <span class="badge badge-success">Publié</span>
                                @elseif($job->status === 'pending')
                                    <span class="badge badge-warning">En attente</span>
                                @else
                                    <span class="badge badge-secondary">{{ ucfirst($job->status) }}</span>
                                @endif
                            </td>
                            <td><span class="badge badge-info">{{ $job->applications->count() }}</span></td>
                            <td>{{ $job->views_count }}</td>
                            <td>{{ $job->created_at->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('admin.jobs.show', $job) }}" class="btn btn-secondary btn-sm">Voir</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 2rem;">Aucune offre</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ============================================================= --}}
    {{-- MODE VITRINE : Secteurs d'activité (catégories N1 > N2 > N3)    --}}
    {{-- ============================================================= --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">🏷️ Secteurs d'Activité ({{ $company->categories->count() }})</h3>
        </div>
        <div style="padding: 1.5rem;">
            @forelse($company->categories as $category)
                <span class="badge badge-info" style="display: inline-block; margin: 4px; padding: 8px 12px; font-size: 0.875rem;">
                    {{ $category->full_name }}
                </span>
            @empty
                <p style="color: #6c757d; margin: 0;">Aucun secteur d'activité renseigné pour cette entreprise.</p>
            @endforelse
        </div>
    </div>

    {{-- ============================================================= --}}
    {{-- MODE VITRINE : Statistiques + Produits & Services              --}}
    {{-- ============================================================= --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">🛍️ Vitrine — Produits & Services ({{ $showcaseStats['total'] }})</h3>
        </div>

        {{-- Bandeau de statistiques vitrine --}}
        <div style="padding: 1.5rem; border-bottom: 1px solid #e9ecef;">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 1rem;">
                <div style="background: #f8f9fa; border-radius: 10px; padding: 1rem; text-align: center;">
                    <div style="font-size: 1.75rem; font-weight: 700; color: #0d6efd;">{{ $showcaseStats['total'] }}</div>
                    <div style="font-size: 0.8rem; color: #6c757d;">Total catalogue</div>
                </div>
                <div style="background: #f8f9fa; border-radius: 10px; padding: 1rem; text-align: center;">
                    <div style="font-size: 1.75rem; font-weight: 700; color: #6f42c1;">{{ $showcaseStats['products'] }}</div>
                    <div style="font-size: 0.8rem; color: #6c757d;">Produits</div>
                </div>
                <div style="background: #f8f9fa; border-radius: 10px; padding: 1rem; text-align: center;">
                    <div style="font-size: 1.75rem; font-weight: 700; color: #fd7e14;">{{ $showcaseStats['services'] }}</div>
                    <div style="font-size: 0.8rem; color: #6c757d;">Services</div>
                </div>
                <div style="background: #f8f9fa; border-radius: 10px; padding: 1rem; text-align: center;">
                    <div style="font-size: 1.75rem; font-weight: 700; color: #198754;">{{ $showcaseStats['active'] }}</div>
                    <div style="font-size: 0.8rem; color: #6c757d;">Actifs</div>
                </div>
                <div style="background: #f8f9fa; border-radius: 10px; padding: 1rem; text-align: center;">
                    <div style="font-size: 1.75rem; font-weight: 700; color: #20c997;">{{ $showcaseStats['sales'] }}</div>
                    <div style="font-size: 0.8rem; color: #6c757d;">Ventes payées</div>
                </div>
                <div style="background: #f8f9fa; border-radius: 10px; padding: 1rem; text-align: center;">
                    <div style="font-size: 1.5rem; font-weight: 700; color: #198754;">{{ number_format($showcaseStats['revenue'], 0, ',', ' ') }}</div>
                    <div style="font-size: 0.8rem; color: #6c757d;">Revenu généré</div>
                </div>
            </div>
        </div>

        {{-- Grille des produits/services --}}
        <div style="padding: 1.5rem;">
            @forelse($company->products as $product)
                @php
                    $billingLabels = [
                        'fixed_price'  => ['label' => 'Prix fixe',   'color' => 'badge-success'],
                        'to_discover'  => ['label' => 'À découvrir', 'color' => 'badge-warning'],
                        'to_visit'     => ['label' => 'À visiter',   'color' => 'badge-info'],
                    ];
                    $billing = $billingLabels[$product->billing_type] ?? ['label' => $product->billing_type, 'color' => 'badge-secondary'];
                @endphp
                @if($loop->first)
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.25rem;">
                @endif
                    <div style="border: 1px solid #e9ecef; border-radius: 12px; overflow: hidden; background: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.06); opacity: {{ $product->is_active ? '1' : '0.55' }};">
                        {{-- Image principale --}}
                        <div style="position: relative; height: 180px; background: #f1f3f5;">
                            @if($product->first_image_url)
                                <img src="{{ $product->first_image_url }}"
                                     alt="{{ $product->name }}"
                                     style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;"
                                     onclick="window.open('{{ $product->first_image_url }}', '_blank')"
                                     onerror="this.parentElement.innerHTML='<div style=\'display:flex;align-items:center;justify-content:center;height:100%;color:#adb5bd;\'>🖼️ Image indisponible</div>'">
                            @else
                                <div style="display:flex;align-items:center;justify-content:center;height:100%;color:#adb5bd;">🖼️ Aucune image</div>
                            @endif
                            <span class="badge {{ $product->type === 'product' ? 'badge-primary' : 'badge-warning' }}"
                                  style="position:absolute;top:8px;left:8px;">
                                {{ $product->type === 'product' ? 'Produit' : 'Service' }}
                            </span>
                            @if(!$product->is_active)
                                <span class="badge badge-danger" style="position:absolute;top:8px;right:8px;">Inactif</span>
                            @endif
                            @if(count($product->image_urls) > 1)
                                <span style="position:absolute;bottom:8px;right:8px;background:rgba(0,0,0,0.6);color:#fff;padding:2px 8px;border-radius:4px;font-size:12px;">
                                    📷 {{ count($product->image_urls) }}
                                </span>
                            @endif
                        </div>

                        {{-- Détails --}}
                        <div style="padding: 1rem;">
                            <h4 style="margin: 0 0 0.5rem; font-size: 1rem; font-weight: 600;">{{ $product->name }}</h4>
                            <p style="margin: 0 0 0.75rem; font-size: 0.85rem; color: #6c757d; line-height: 1.4;">
                                {{ \Illuminate\Support\Str::limit($product->description, 110) }}
                            </p>

                            <div style="margin-bottom: 0.5rem;">
                                <span class="badge {{ $billing['color'] }}">{{ $billing['label'] }}</span>
                                @if($product->billing_type === 'fixed_price' && $product->price !== null)
                                    <strong style="margin-left: 6px; color: #198754;">
                                        {{ number_format($product->price, 0, ',', ' ') }} {{ $product->currency }}
                                    </strong>
                                @endif
                            </div>

                            @if($product->category)
                                <div style="font-size: 0.75rem; color: #6c757d; margin-bottom: 0.25rem;">
                                    🏷️ {{ $product->category->full_name }}
                                </div>
                            @endif
                            @if($product->type === 'product' && $product->stock !== null)
                                <div style="font-size: 0.75rem; color: #6c757d;">
                                    📦 Stock : {{ $product->stock }}
                                </div>
                            @endif
                            <div style="font-size: 0.7rem; color: #adb5bd; margin-top: 0.5rem;">
                                Ajouté le {{ $product->created_at->format('d/m/Y') }}
                            </div>
                        </div>
                    </div>
                @if($loop->last)
                </div>
                @endif
            @empty
                <div style="text-align: center; padding: 2rem; color: #6c757d;">
                    Cette entreprise n'a publié aucun produit ni service dans sa vitrine.
                </div>
            @endforelse
        </div>
    </div>

    {{-- ============================================================= --}}
    {{-- MODE VITRINE : Historique des ventes                          --}}
    {{-- ============================================================= --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">💰 Ventes de la Vitrine ({{ $company->productPurchases->count() }})</h3>
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Produit/Service</th>
                        <th>Acheteur</th>
                        <th>Montant</th>
                        <th>Moyen</th>
                        <th>Statut</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($company->productPurchases as $purchase)
                        <tr>
                            <td>{{ $purchase->product?->name ?? ($purchase->product_snapshot['name'] ?? 'Produit supprimé') }}</td>
                            <td>{{ $purchase->buyer?->name ?? 'N/A' }}</td>
                            <td><strong>{{ number_format($purchase->amount, 0, ',', ' ') }} {{ $purchase->currency }}</strong></td>
                            <td><span class="badge badge-secondary">{{ ucfirst($purchase->provider) }}</span></td>
                            <td>
                                @if($purchase->status === 'paid')
                                    <span class="badge badge-success">Payé</span>
                                @else
                                    <span class="badge badge-warning">En attente</span>
                                @endif
                            </td>
                            <td>{{ $purchase->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 2rem;">Aucune vente enregistrée</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
