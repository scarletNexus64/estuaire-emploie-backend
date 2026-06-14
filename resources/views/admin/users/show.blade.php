@extends('admin.layouts.app')

@section('title', 'Détails Utilisateur')
@section('page-title', 'Profil de l\'Utilisateur')

@section('breadcrumbs')
    <span> / </span>
    <a href="{{ route('admin.users.index') }}">Utilisateurs</a>
    <span> / </span>
    <span>{{ $user->name }}</span>
@endsection

@section('content')
<div style="display: grid; gap: 1.5rem;">

    {{-- Header card avec actions --}}
    <div class="card">
        <div class="card-header" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                @if($user->profile_photo)
                    <img src="{{ asset('storage/' . $user->profile_photo) }}"
                         alt="{{ $user->name }}"
                         style="width: 64px; height: 64px; border-radius: 50%; object-fit: cover; border: 2px solid #e5e7eb;">
                @else
                    <div style="width: 64px; height: 64px; border-radius: 50%; background: #e5e7eb; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: 600; color: #6b7280;">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
                <div>
                    <h3 class="card-title" style="margin: 0;">{{ $user->name }}</h3>
                    <div style="display: flex; gap: 0.5rem; margin-top: 0.25rem; flex-wrap: wrap;">
                        <span class="badge badge-info">{{ ucfirst($user->role) }}</span>
                        @if($user->is_active ?? true)
                            <span class="badge badge-success">Actif</span>
                        @else
                            <span class="badge badge-secondary">Inactif</span>
                        @endif
                        @if($user->hasStudentMode())
                            <span class="badge badge-warning">🎓 Étudiant</span>
                        @endif
                        @if($user->hasActiveSubscription())
                            <span class="badge badge-success">Abonné</span>
                        @endif
                    </div>
                </div>
            </div>
            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning">✏️ Modifier</a>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">← Retour</a>
            </div>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="card">
        <div class="tabs-nav">
            <button type="button" class="tab-btn active" data-tab="info">👤 Informations</button>
            <button type="button" class="tab-btn" data-tab="subscriptions">
                💳 Abonnements
                @if($subscriptionPlans->count() > 0)
                    <span class="tab-count">{{ $subscriptionPlans->count() }}</span>
                @endif
            </button>
            <button type="button" class="tab-btn" data-tab="premium">
                ⭐ Services Premium
                @if($premiumServices->count() > 0)
                    <span class="tab-count">{{ $premiumServices->count() }}</span>
                @endif
            </button>
            <button type="button" class="tab-btn" data-tab="addons">
                🧩 Services Additionnels
                @if($addonServices->count() > 0)
                    <span class="tab-count">{{ $addonServices->count() }}</span>
                @endif
            </button>
            <button type="button" class="tab-btn" data-tab="packs">
                📦 Packs
                @if(($storagePacks->count() + $packPurchases->count()) > 0)
                    <span class="tab-count">{{ $storagePacks->count() + $packPurchases->count() }}</span>
                @endif
            </button>
            <button type="button" class="tab-btn" data-tab="applications">
                📝 Candidatures
                @if($user->applications->count() > 0)
                    <span class="tab-count">{{ $user->applications->count() }}</span>
                @endif
            </button>
        </div>

        {{-- Tab: Infos --}}
        <div class="tab-pane active" id="tab-info">
            <div class="card-body">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                    <div>
                        <h4 style="margin-bottom: 1rem; font-weight: 600;">Informations Personnelles</h4>
                        <div class="info-row"><strong>Nom :</strong> <span>{{ $user->name }}</span></div>
                        <div class="info-row"><strong>Email :</strong> <span>{{ $user->email ?? 'N/A' }}</span></div>
                        <div class="info-row"><strong>Téléphone :</strong> <span>{{ $user->phone ?? 'N/A' }}</span></div>
                        <div class="info-row"><strong>Rôle :</strong> <span class="badge badge-info">{{ ucfirst($user->role) }}</span></div>
                        @if($user->admin_role)
                            <div class="info-row"><strong>Rôle admin :</strong> <span class="badge badge-warning">{{ $user->admin_role->value ?? $user->admin_role }}</span></div>
                        @endif
                        <div class="info-row"><strong>Niveau d'expérience :</strong> <span>{{ ucfirst($user->experience_level ?? 'N/A') }}</span></div>
                        <div class="info-row"><strong>Score de visibilité :</strong> <span>{{ $user->visibility_score ?? 0 }}/100</span></div>
                        <div class="info-row"><strong>Langue :</strong> <span>{{ strtoupper($user->locale ?? 'fr') }}</span></div>
                        <div class="info-row"><strong>Code parrain :</strong> <span><code>{{ $user->referral_code ?? '—' }}</code></span></div>
                        @if($user->referrer)
                            <div class="info-row"><strong>Parrainé par :</strong> <a href="{{ route('admin.users.show', $user->referrer) }}">{{ $user->referrer->name }}</a></div>
                        @endif
                        <div class="info-row"><strong>Filleuls :</strong> <span>{{ $user->referrals->count() }}</span></div>
                        <div class="info-row"><strong>Inscription :</strong> <span>{{ $user->created_at->format('d/m/Y à H:i') }}</span></div>
                        @if($user->last_login_at)
                            <div class="info-row"><strong>Dernière connexion :</strong> <span>{{ $user->last_login_at->format('d/m/Y à H:i') }}</span></div>
                        @endif
                    </div>

                    <div>
                        <h4 style="margin-bottom: 1rem; font-weight: 600;">Profil</h4>
                        @if($user->bio)
                            <div style="margin-bottom: 1rem;">
                                <strong>Biographie :</strong>
                                <p style="margin-top: 0.5rem; padding: 0.75rem; background: #f8f9fa; border-radius: 4px; white-space: pre-wrap;">{{ $user->bio }}</p>
                            </div>
                        @endif

                        @if($user->skills)
                            <div style="margin-bottom: 1rem;">
                                <strong>Compétences :</strong>
                                <p style="margin-top: 0.5rem; padding: 0.75rem; background: #f8f9fa; border-radius: 4px;">{{ $user->skills }}</p>
                            </div>
                        @endif

                        @if($user->portfolio_url)
                            <div class="info-row"><strong>Portfolio :</strong> <a href="{{ $user->portfolio_url }}" target="_blank">{{ $user->portfolio_url }}</a></div>
                        @endif

                        @if($user->level || $user->specialty || $user->interests)
                            <h4 style="margin-top: 1.5rem; margin-bottom: 1rem; font-weight: 600;">🎓 Infos Étudiant</h4>
                            @if($user->level)
                                <div class="info-row"><strong>Niveau :</strong> <span class="badge badge-success">{{ $user->level }}</span></div>
                            @endif
                            @if($user->specialty)
                                <div class="info-row"><strong>Spécialité :</strong> <span class="badge badge-primary">{{ $user->specialty }}</span></div>
                            @endif
                            @if($user->interests)
                                <div style="margin-top: 0.5rem;">
                                    <strong>Centres d'intérêt :</strong>
                                    <p style="margin-top: 0.5rem; padding: 0.75rem; background: #f8f9fa; border-radius: 4px;">{{ $user->interests }}</p>
                                </div>
                            @endif
                        @endif

                        <h4 style="margin-top: 1.5rem; margin-bottom: 1rem; font-weight: 600;">💰 Portefeuilles</h4>
                        <div class="info-row"><strong>Wallet (legacy) :</strong> <span>{{ number_format($user->wallet_balance ?? 0, 0, ',', ' ') }} FCFA</span></div>
                        <div class="info-row"><strong>Wallet Freemopay :</strong> <span>{{ number_format($user->freemopay_wallet_balance ?? 0, 0, ',', ' ') }} FCFA</span></div>
                        <div class="info-row"><strong>Wallet PayPal :</strong> <span>{{ number_format($user->paypal_wallet_balance ?? 0, 2, ',', ' ') }} USD</span></div>
                        <div class="info-row"><strong>Devise préférée :</strong> <span>{{ $user->preferred_currency ?? 'XAF' }}</span></div>
                    </div>
                </div>

                {{-- Stats rapides --}}
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid #e5e7eb;">
                    <div class="stat-card info">
                        <div class="stat-label">Candidatures</div>
                        <div class="stat-value">{{ $user->applications->count() }}</div>
                    </div>
                    <div class="stat-card success">
                        <div class="stat-label">Offres publiées</div>
                        <div class="stat-value">{{ $user->postedJobs->count() }}</div>
                    </div>
                    <div class="stat-card warning">
                        <div class="stat-label">Entreprises liées</div>
                        <div class="stat-value">{{ $user->companies->count() }}</div>
                    </div>
                    <div class="stat-card danger">
                        <div class="stat-label">Filleuls</div>
                        <div class="stat-value">{{ $user->referrals->count() }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tab: Abonnements --}}
        <div class="tab-pane" id="tab-subscriptions">
            <div class="card-body">
                @if($subscriptionPlans->isEmpty())
                    <div class="empty-state">Aucun abonnement enregistré pour cet utilisateur.</div>
                @else
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Plan</th>
                                    <th>Type</th>
                                    <th>Statut</th>
                                    <th>Début</th>
                                    <th>Expiration</th>
                                    <th>Usage</th>
                                    <th>Paiement</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($subscriptionPlans as $sub)
                                    <tr>
                                        <td>
                                            <strong>{{ $sub->subscriptionPlan?->name ?? 'Plan supprimé' }}</strong>
                                            @if($sub->subscriptionPlan?->slug)
                                                <br><small style="color: #6b7280;">{{ $sub->subscriptionPlan->slug }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-info">{{ $sub->subscriptionPlan?->plan_type ?? '—' }}</span>
                                        </td>
                                        <td>
                                            @if($sub->isValid())
                                                <span class="badge badge-success">Valide</span>
                                            @elseif($sub->isExpired())
                                                <span class="badge badge-danger">Expiré</span>
                                            @elseif($sub->isPending())
                                                <span class="badge badge-warning">En attente</span>
                                            @else
                                                <span class="badge badge-secondary">Inactif</span>
                                            @endif
                                        </td>
                                        <td>{{ $sub->starts_at?->format('d/m/Y') ?? '—' }}</td>
                                        <td>
                                            {{ $sub->expires_at?->format('d/m/Y') ?? '—' }}
                                            @if($sub->expires_at && !$sub->isExpired())
                                                <br><small style="color: #6b7280;">({{ $sub->days_remaining }} j restants)</small>
                                            @endif
                                        </td>
                                        <td>
                                            <small>
                                                Jobs: {{ $sub->jobs_used }} / {{ $sub->getEffectiveJobsLimit() ?? '∞' }}<br>
                                                Contacts: {{ $sub->contacts_used }} / {{ $sub->getEffectiveContactsLimit() ?? '∞' }}
                                            </small>
                                        </td>
                                        <td>
                                            @if($sub->payment_id === null)
                                                <span class="badge badge-info">Gratuit</span>
                                            @elseif($sub->payment)
                                                <span class="badge badge-{{ $sub->payment->status === 'completed' ? 'success' : ($sub->payment->status === 'pending' ? 'warning' : 'danger') }}">
                                                    {{ ucfirst($sub->payment->status) }}
                                                </span>
                                                <br><small>{{ number_format($sub->payment->total ?? $sub->payment->amount, 0, ',', ' ') }} {{ $sub->payment->currency ?? '' }}</small>
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td>
                                            @if($sub->isValid())
                                                <form action="{{ route('admin.users.subscriptions.revoke', [$user, $sub]) }}" method="POST" style="display:inline;"
                                                      onsubmit="return confirm('Retirer cet abonnement « {{ $sub->subscriptionPlan?->name }} » ? Il sera marqué comme expiré immédiatement.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">🚫 Retirer</button>
                                                </form>
                                            @else
                                                <span style="color: #9ca3af; font-size: 0.85rem;">Déjà inactif</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        {{-- Tab: Services Premium --}}
        <div class="tab-pane" id="tab-premium">
            <div class="card-body">
                @if($premiumServices->isEmpty())
                    <div class="empty-state">Aucun service premium activé. (Le « Pack Étudiant » apparaît ici sous le slug <code>student_mode</code>.)</div>
                @else
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Service</th>
                                    <th>Slug</th>
                                    <th>Statut</th>
                                    <th>Activé le</th>
                                    <th>Expire le</th>
                                    <th>Renouv. auto</th>
                                    <th>Utilisations</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($premiumServices as $svc)
                                    <tr>
                                        <td>
                                            <strong>{{ $svc->config?->name ?? 'Service supprimé' }}</strong>
                                            @if($svc->config?->slug === 'student_mode')
                                                <span class="badge badge-warning" style="margin-left: 0.25rem;">🎓 Pack Étudiant</span>
                                            @endif
                                        </td>
                                        <td><code>{{ $svc->config?->slug ?? '—' }}</code></td>
                                        <td>
                                            @if($svc->isValid())
                                                <span class="badge badge-success">Actif</span>
                                            @elseif(!$svc->is_active)
                                                <span class="badge badge-secondary">Désactivé</span>
                                            @else
                                                <span class="badge badge-danger">Expiré</span>
                                            @endif
                                        </td>
                                        <td>{{ $svc->activated_at?->format('d/m/Y') ?? '—' }}</td>
                                        <td>
                                            {{ $svc->expires_at?->format('d/m/Y') ?? 'Permanent' }}
                                        </td>
                                        <td>
                                            @if($svc->auto_renew)
                                                <span class="badge badge-info">Oui</span>
                                            @else
                                                <span class="badge badge-secondary">Non</span>
                                            @endif
                                        </td>
                                        <td>{{ $svc->uses_remaining ?? '∞' }}</td>
                                        <td>
                                            @if($svc->isValid())
                                                <form action="{{ route('admin.users.premium-services.revoke', [$user, $svc]) }}" method="POST" style="display:inline;"
                                                      onsubmit="return confirm('Retirer le service « {{ $svc->config?->name }} » ? Il sera désactivé immédiatement.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">🚫 Retirer</button>
                                                </form>
                                            @else
                                                <span style="color: #9ca3af; font-size: 0.85rem;">Déjà inactif</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        {{-- Tab: Services Additionnels --}}
        <div class="tab-pane" id="tab-addons">
            <div class="card-body">
                @if($addonServices->isEmpty())
                    <div class="empty-state">Aucun service additionnel acheté.</div>
                @else
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Service</th>
                                    <th>Type</th>
                                    <th>Statut</th>
                                    <th>Acheté le</th>
                                    <th>Expire le</th>
                                    <th>Cible</th>
                                    <th>Utilisations</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($addonServices as $addon)
                                    <tr>
                                        <td><strong>{{ $addon->addonServiceConfig?->name ?? '—' }}</strong></td>
                                        <td><span class="badge badge-info">{{ $addon->addonServiceConfig?->service_type ?? '—' }}</span></td>
                                        <td>
                                            @if($addon->isValid())
                                                <span class="badge badge-success">Actif</span>
                                            @elseif($addon->isExpired())
                                                <span class="badge badge-danger">Expiré</span>
                                            @else
                                                <span class="badge badge-secondary">Inactif</span>
                                            @endif
                                        </td>
                                        <td>{{ $addon->purchased_at?->format('d/m/Y') ?? '—' }}</td>
                                        <td>{{ $addon->expires_at?->format('d/m/Y') ?? 'Permanent' }}</td>
                                        <td>
                                            @if($addon->relatedJob)
                                                <small>Job: {{ $addon->relatedJob->title }}</small>
                                            @elseif($addon->relatedUser)
                                                <small>User: {{ $addon->relatedUser->name }}</small>
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td>
                                            @if($addon->uses_remaining !== null)
                                                {{ $addon->uses_remaining }}
                                            @else
                                                ∞
                                            @endif
                                            <br><small style="color:#6b7280;">{{ $addon->views_count }} vues · {{ $addon->clicks_count }} clics</small>
                                        </td>
                                        <td>
                                            @if($addon->isValid())
                                                <form action="{{ route('admin.users.addon-services.revoke', [$user, $addon]) }}" method="POST" style="display:inline;"
                                                      onsubmit="return confirm('Retirer ce service additionnel ?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">🚫 Retirer</button>
                                                </form>
                                            @else
                                                <span style="color: #9ca3af; font-size: 0.85rem;">Déjà inactif</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        {{-- Tab: Packs (storage + exam/training) --}}
        <div class="tab-pane" id="tab-packs">
            <div class="card-body">

                <h4 style="margin-bottom: 1rem; font-weight: 600;">💾 Packs de Stockage</h4>
                @if($storagePacks->isEmpty())
                    <div class="empty-state" style="margin-bottom: 2rem;">Aucun pack de stockage.</div>
                @else
                    <div class="table-responsive" style="margin-bottom: 2rem;">
                        <table>
                            <thead>
                                <tr>
                                    <th>Pack</th>
                                    <th>Statut</th>
                                    <th>Espace</th>
                                    <th>Utilisé</th>
                                    <th>Acheté le</th>
                                    <th>Expire le</th>
                                    <th>Prix</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($storagePacks as $pack)
                                    <tr>
                                        <td><strong>{{ $pack->storagePack?->name ?? 'Pack supprimé' }}</strong></td>
                                        <td>
                                            @if($pack->is_active && !$pack->isExpired())
                                                <span class="badge badge-success">Actif</span>
                                            @else
                                                <span class="badge badge-danger">Inactif</span>
                                            @endif
                                        </td>
                                        <td>{{ $pack->formatted_total_storage }}</td>
                                        <td>
                                            {{ $pack->formatted_used_storage }}
                                            <br><small style="color: #6b7280;">{{ $pack->usage_percentage }}%</small>
                                        </td>
                                        <td>{{ $pack->purchased_at?->format('d/m/Y') ?? '—' }}</td>
                                        <td>{{ $pack->expires_at?->format('d/m/Y') ?? '—' }}</td>
                                        <td>{{ number_format($pack->purchase_price ?? 0, 0, ',', ' ') }} FCFA</td>
                                        <td>
                                            @if($pack->is_active && !$pack->isExpired())
                                                <form action="{{ route('admin.users.storage-packs.revoke', [$user, $pack]) }}" method="POST" style="display:inline;"
                                                      onsubmit="return confirm('Retirer ce pack de stockage ?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">🚫 Retirer</button>
                                                </form>
                                            @else
                                                <span style="color: #9ca3af; font-size: 0.85rem;">Déjà inactif</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                <h4 style="margin-bottom: 1rem; font-weight: 600;">📚 Packs Formation / Examen</h4>
                @if($packPurchases->isEmpty())
                    <div class="empty-state">Aucun achat de pack de formation ou d'examen.</div>
                @else
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Pack</th>
                                    <th>Type</th>
                                    <th>Statut</th>
                                    <th>Acheté le</th>
                                    <th>Expire le</th>
                                    <th>Montant</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($packPurchases as $purchase)
                                    <tr>
                                        <td>
                                            <strong>
                                                {{ $purchase->examPack?->name ?? $purchase->trainingPack?->name ?? '—' }}
                                            </strong>
                                        </td>
                                        <td><span class="badge badge-info">{{ ucfirst($purchase->pack_type) }}</span></td>
                                        <td>
                                            @if($purchase->isActive())
                                                <span class="badge badge-success">Actif</span>
                                            @elseif($purchase->status === 'revoked')
                                                <span class="badge badge-danger">Retiré</span>
                                            @else
                                                <span class="badge badge-secondary">{{ ucfirst($purchase->status) }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $purchase->purchased_at?->format('d/m/Y') ?? '—' }}</td>
                                        <td>{{ $purchase->expires_at?->format('d/m/Y') ?? 'Illimité' }}</td>
                                        <td>{{ number_format($purchase->amount_paid ?? 0, 0, ',', ' ') }} {{ $purchase->currency ?? '' }}</td>
                                        <td>
                                            @if($purchase->isActive())
                                                <form action="{{ route('admin.users.pack-purchases.revoke', [$user, $purchase]) }}" method="POST" style="display:inline;"
                                                      onsubmit="return confirm('Retirer ce pack ?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">🚫 Retirer</button>
                                                </form>
                                            @else
                                                <span style="color: #9ca3af; font-size: 0.85rem;">Déjà inactif</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        {{-- Tab: Candidatures --}}
        <div class="tab-pane" id="tab-applications">
            <div class="card-body">
                @if($user->applications->isEmpty())
                    <div class="empty-state">Aucune candidature.</div>
                @else
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Offre</th>
                                    <th>Entreprise</th>
                                    <th>Statut</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user->applications as $application)
                                    <tr>
                                        <td>{{ $application->job?->title ?? 'N/A' }}</td>
                                        <td>{{ $application->job?->company?->name ?? 'N/A' }}</td>
                                        <td>
                                            @if($application->status === 'pending')
                                                <span class="badge badge-warning">En attente</span>
                                            @elseif($application->status === 'shortlisted')
                                                <span class="badge badge-success">Retenue</span>
                                            @elseif($application->status === 'rejected')
                                                <span class="badge badge-danger">Rejetée</span>
                                            @else
                                                <span class="badge badge-info">{{ ucfirst($application->status) }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $application->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <a href="{{ route('admin.applications.show', $application) }}" class="btn btn-secondary btn-sm">Voir</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    .tabs-nav {
        display: flex;
        gap: 0;
        border-bottom: 2px solid #e5e7eb;
        padding: 0 1rem;
        overflow-x: auto;
    }
    .tab-btn {
        background: none;
        border: none;
        padding: 1rem 1.25rem;
        cursor: pointer;
        font-size: 0.95rem;
        font-weight: 500;
        color: #6b7280;
        border-bottom: 3px solid transparent;
        margin-bottom: -2px;
        white-space: nowrap;
        transition: all 0.15s;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }
    .tab-btn:hover {
        color: #111827;
        background: #f9fafb;
    }
    .tab-btn.active {
        color: #2563eb;
        border-bottom-color: #2563eb;
        font-weight: 600;
    }
    .tab-count {
        background: #e5e7eb;
        color: #374151;
        padding: 0.1rem 0.5rem;
        border-radius: 999px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .tab-btn.active .tab-count {
        background: #2563eb;
        color: white;
    }
    .tab-pane {
        display: none;
    }
    .tab-pane.active {
        display: block;
    }
    .info-row {
        padding: 0.4rem 0;
        border-bottom: 1px dashed #f3f4f6;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
    }
    .info-row strong {
        color: #374151;
        font-weight: 600;
    }
    .empty-state {
        padding: 2rem;
        text-align: center;
        color: #6b7280;
        background: #f9fafb;
        border-radius: 6px;
    }
    .table-responsive table {
        width: 100%;
        border-collapse: collapse;
    }
    .table-responsive th,
    .table-responsive td {
        padding: 0.75rem;
        text-align: left;
        border-bottom: 1px solid #e5e7eb;
        vertical-align: top;
    }
    .table-responsive th {
        background: #f9fafb;
        font-weight: 600;
        color: #374151;
        font-size: 0.85rem;
        text-transform: uppercase;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabBtns = document.querySelectorAll('.tab-btn');
        const tabPanes = document.querySelectorAll('.tab-pane');

        // Restaurer l'onglet actif depuis l'URL (#tab-xxx) si présent
        const hash = window.location.hash.replace('#tab-', '');
        if (hash) {
            const target = document.querySelector(`.tab-btn[data-tab="${hash}"]`);
            if (target) {
                tabBtns.forEach(b => b.classList.remove('active'));
                tabPanes.forEach(p => p.classList.remove('active'));
                target.classList.add('active');
                document.getElementById('tab-' + hash)?.classList.add('active');
            }
        }

        tabBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const tab = this.dataset.tab;
                tabBtns.forEach(b => b.classList.remove('active'));
                tabPanes.forEach(p => p.classList.remove('active'));
                this.classList.add('active');
                document.getElementById('tab-' + tab)?.classList.add('active');
                history.replaceState(null, '', '#tab-' + tab);
            });
        });
    });
</script>
@endsection
