@extends('admin.layouts.app')

@section('title', 'Tableau de bord')

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}">Accueil</a>
    <span>/</span>
    <span>Dashboard</span>
@endsection

@section('header-actions')
    <a href="{{ route('admin.jobs.create') }}" class="ad-btn ad-btn-primary">
        <i class="mdi mdi-plus"></i>
        Nouvelle offre
    </a>
@endsection

@php
    $fmt = fn ($n) => number_format((int) $n, 0, ',', ' ');

    $applicationLabels = [
        'pending' => 'En attente', 'viewed' => 'Vues', 'shortlisted' => 'Présélectionnées',
        'interview' => 'Entretien', 'accepted' => 'Acceptées', 'rejected' => 'Rejetées',
    ];
    $jobLabels = [
        'published' => 'Publiées', 'pending' => 'En attente', 'draft' => 'Brouillons',
        'closed' => 'Fermées', 'expired' => 'Expirées',
    ];

    $buildBreakdown = function ($counts, array $labels) {
        $counts = collect($counts ?? [])->map(fn ($v) => (int) $v);
        $total = max($counts->sum(), 1);
        $rows = collect($labels)->map(fn ($label, $key) => ['label' => $label, 'count' => $counts->get($key, 0)]);
        // Statuts inattendus en base : affichés aussi, rien n'est perdu
        foreach ($counts as $key => $value) {
            if (! array_key_exists($key, $labels)) {
                $rows->put($key, ['label' => ucfirst((string) $key), 'count' => $value]);
            }
        }
        return $rows->map(fn ($r) => $r + ['pct' => round($r['count'] * 100 / $total)])->values();
    };

    $applicationRows = $buildBreakdown($applicationsByStatus ?? [], $applicationLabels);
    $jobRows = $buildBreakdown($jobsByStatus ?? [], $jobLabels);

    $applicationPill = [
        'accepted' => 'ad-pill-success', 'pending' => 'ad-pill-warning', 'rejected' => 'ad-pill-danger',
        'interview' => 'ad-pill-primary', 'shortlisted' => 'ad-pill-primary',
    ];
    $jobPill = ['published' => 'ad-pill-success', 'pending' => 'ad-pill-warning'];

    $totalUsers = $stats['total_candidates'] + $stats['total_recruiters'];
    $candidatePct = $totalUsers > 0 ? round($stats['total_candidates'] * 100 / $totalUsers) : 0;

    $todo = array_filter([
        ['label' => 'Entreprises à valider', 'count' => $stats['pending_companies'], 'icon' => 'mdi-office-building-outline', 'url' => route('admin.companies.index')],
        ['label' => 'Offres en attente', 'count' => $stats['pending_jobs'], 'icon' => 'mdi-briefcase-outline', 'url' => route('admin.jobs.index')],
        ['label' => 'Candidatures en attente', 'count' => $stats['pending_applications'], 'icon' => 'mdi-file-document-outline', 'url' => route('admin.applications.index')],
        ['label' => 'Diplômes à vérifier', 'count' => $stats['pending_diploma_verifications'], 'icon' => 'mdi-certificate-outline', 'url' => route('admin.applications.index')],
    ], fn ($t) => $t['count'] > 0);
@endphp

@push('styles')
<style data-theme-skip>
    .kpi { padding: 16px; } @media (min-width: 640px) { .kpi { padding: 18px 20px; } }
    .kpi { display: flex; flex-direction: column; gap: 14px; }
    .kpi-top { display: flex; align-items: center; justify-content: space-between; gap: 8px; }
    .kpi-label { font-size: 13px; font-weight: 500; color: var(--muted); }
    .kpi-icon { width: 32px; height: 32px; border-radius: 8px; display: grid; place-items: center; background: var(--surface-3); color: var(--muted); font-size: 18px; }
    .kpi-value { font-size: 28px; line-height: 1; font-weight: 600; letter-spacing: -0.02em; color: var(--text); font-variant-numeric: tabular-nums; }
    .kpi-sub { font-size: 12.5px; color: var(--subtle); margin-top: 6px; }
    .kpi-foot { display: flex; align-items: center; justify-content: space-between; padding-top: 12px; border-top: 1px solid var(--border); }

    .split { height: 6px; border-radius: 999px; background: var(--surface-3); overflow: hidden; display: flex; gap: 2px; }
    .split > span { display: block; height: 100%; border-radius: 999px; }

    .todo { display: flex; align-items: center; gap: 12px; padding: 12px 14px; border-radius: 10px; border: 1px solid var(--border); background: var(--surface); text-decoration: none !important; transition: border-color .12s, background-color .12s; }
    .todo:hover { border-color: var(--border-strong); background: var(--surface-2); }
    .todo-icon { width: 34px; height: 34px; border-radius: 8px; display: grid; place-items: center; background: var(--warning-soft); color: var(--warning); font-size: 18px; flex-shrink: 0; }
    .todo-count { font-size: 18px; font-weight: 600; color: var(--text); font-variant-numeric: tabular-nums; line-height: 1.1; }
    .todo-label { font-size: 12.5px; color: var(--muted); }

    .bars { padding: 18px 20px; display: flex; flex-direction: column; gap: 14px; }
    .bar-head { display: flex; align-items: baseline; justify-content: space-between; gap: 8px; font-size: 13px; margin-bottom: 6px; }
    .bar-label { color: var(--text-2); }
    .bar-value { color: var(--text); font-weight: 500; font-variant-numeric: tabular-nums; }
    .bar-value small { color: var(--subtle); font-weight: 400; margin-left: 6px; font-size: 12px; }
    .bar-track { height: 6px; border-radius: 999px; background: var(--surface-3); overflow: hidden; }
    .bar-fill { height: 100%; border-radius: 999px; background: var(--primary-hover); min-width: 0; }
    .bar-row.is-zero .bar-label, .bar-row.is-zero .bar-value { color: var(--subtle); }

    .row-title { font-size: 13.5px; font-weight: 500; color: var(--text); }
    .row-meta { font-size: 12.5px; color: var(--muted); }
    .row-time { font-size: 12px; color: var(--subtle); white-space: nowrap; }
    .initial { width: 32px; height: 32px; border-radius: 999px; display: grid; place-items: center; font-size: 12.5px; font-weight: 600; background: var(--surface-3); color: var(--text-2); flex-shrink: 0; }
    .empty { padding: 32px 20px; text-align: center; font-size: 13.5px; color: var(--subtle); }
    .empty .mdi { display: block; font-size: 28px; margin-bottom: 6px; }
</style>
@endpush

@section('content')
    <div class="space-y-6">

        {{-- Indicateurs --}}
        <section class="grid grid-cols-2 xl:grid-cols-4 gap-3 sm:gap-4" aria-label="Indicateurs clés">
            <div class="ad-card kpi">
                <div class="kpi-top">
                    <span class="kpi-label">Entreprises</span>
                    <span class="kpi-icon"><i class="mdi mdi-office-building-outline"></i></span>
                </div>
                <div>
                    <div class="kpi-value">{{ $fmt($stats['total_companies']) }}</div>
                    <div class="kpi-sub">{{ $fmt($stats['pending_companies']) }} en attente de validation</div>
                </div>
                <div class="kpi-foot">
                    <a href="{{ route('admin.companies.index') }}" class="ad-link">Voir tout <i class="mdi mdi-arrow-right"></i></a>
                </div>
            </div>

            <div class="ad-card kpi">
                <div class="kpi-top">
                    <span class="kpi-label">Offres d'emploi</span>
                    <span class="kpi-icon"><i class="mdi mdi-briefcase-outline"></i></span>
                </div>
                <div>
                    <div class="kpi-value">{{ $fmt($stats['total_jobs']) }}</div>
                    <div class="kpi-sub">{{ $fmt($stats['published_jobs']) }} publiées · {{ $fmt($stats['pending_jobs']) }} en attente</div>
                </div>
                <div class="kpi-foot">
                    <a href="{{ route('admin.jobs.index') }}" class="ad-link">Voir tout <i class="mdi mdi-arrow-right"></i></a>
                </div>
            </div>

            <div class="ad-card kpi">
                <div class="kpi-top">
                    <span class="kpi-label">Candidatures</span>
                    <span class="kpi-icon"><i class="mdi mdi-file-document-outline"></i></span>
                </div>
                <div>
                    <div class="kpi-value">{{ $fmt($stats['total_applications']) }}</div>
                    <div class="kpi-sub">{{ $fmt($stats['pending_applications']) }} en attente</div>
                </div>
                <div class="kpi-foot">
                    <a href="{{ route('admin.applications.index') }}" class="ad-link">Voir tout <i class="mdi mdi-arrow-right"></i></a>
                </div>
            </div>

            <div class="ad-card kpi">
                <div class="kpi-top">
                    <span class="kpi-label">Utilisateurs</span>
                    <span class="kpi-icon"><i class="mdi mdi-account-group-outline"></i></span>
                </div>
                <div>
                    <div class="kpi-value">{{ $fmt($totalUsers) }}</div>
                    <div class="split mt-3" role="img" aria-label="{{ $candidatePct }}% candidats, {{ 100 - $candidatePct }}% recruteurs">
                        <span style="width: {{ $candidatePct }}%; background: var(--primary-hover)"></span>
                        <span style="flex: 1; background: #5a6477"></span>
                    </div>
                    <div class="kpi-sub flex flex-wrap gap-x-3">
                        <span><span class="inline-block w-2 h-2 rounded-full align-middle mr-1" style="background:var(--primary-hover)"></span>{{ $fmt($stats['total_candidates']) }} candidats</span>
                        <span><span class="inline-block w-2 h-2 rounded-full align-middle mr-1" style="background:#5a6477"></span>{{ $fmt($stats['total_recruiters']) }} recruteurs</span>
                    </div>
                </div>
                <div class="kpi-foot">
                    <a href="{{ route('admin.users.index') }}" class="ad-link">Voir tout <i class="mdi mdi-arrow-right"></i></a>
                </div>
            </div>
        </section>

        {{-- À traiter --}}
        @if(count($todo))
            <section aria-label="À traiter">
                <h2 class="text-[13px] font-semibold uppercase tracking-wider mb-3" style="color:var(--subtle)">À traiter</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-3">
                    @foreach($todo as $t)
                        <a href="{{ $t['url'] }}" class="todo">
                            <span class="todo-icon"><i class="mdi {{ $t['icon'] }}"></i></span>
                            <span class="flex-1 min-w-0">
                                <span class="todo-count block">{{ $fmt($t['count']) }}</span>
                                <span class="todo-label block truncate">{{ $t['label'] }}</span>
                            </span>
                            <i class="mdi mdi-chevron-right" style="color:var(--subtle)"></i>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- Commandes USB --}}
        @if(auth()->user()?->hasPermission('manage_premium_services'))
            <section class="ad-card">
                <div class="ad-card-header">
                    <div class="min-w-0">
                        <h3 class="ad-card-title">Commandes de formations sur clé USB</h3>
                        <p class="ad-card-sub">{{ $pendingUsbOrders }} commande(s) en attente de traitement</p>
                    </div>
                    <a href="{{ route('admin.insamtechs-usb.index') }}" class="ad-link flex-shrink-0">Toutes les commandes <i class="mdi mdi-arrow-right"></i></a>
                </div>
                <div>
                    @forelse($recentUsbOrders as $order)
                        <div class="ad-row" style="cursor:default">
                            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-2">
                                <div class="min-w-0 space-y-0.5">
                                    <p class="row-title m-0">#{{ $order->id }} — {{ $order->customer_name }}</p>
                                    <p class="row-meta m-0 break-words">Compte #{{ $order->user_id }} · {{ $order->user?->name ?? 'Compte supprimé' }} · {{ $order->user?->email ?? '—' }} · {{ $order->user?->phone ?? '—' }}</p>
                                    <p class="row-meta m-0 break-words">Livraison : {{ $order->phone }} · {{ $order->address }}, {{ $order->city }}</p>
                                    <p class="row-meta m-0 break-words">Formations : {{ collect($order->formations)->pluck('title')->join(', ') }}</p>
                                    @if(auth()->user()?->isSuperAdmin() && $order->user)
                                        <a href="{{ route('admin.users.show', $order->user) }}" class="ad-link">Voir le compte complet</a>
                                    @endif
                                </div>
                                <div class="flex md:flex-col md:items-end gap-2 flex-shrink-0">
                                    <span class="ad-pill {{ $order->status === 'pending' ? 'ad-pill-warning' : '' }}">{{ \App\Models\InsamtechsUsbOrder::STATUS_LABELS[$order->status] ?? $order->status }}</span>
                                    <span class="row-meta whitespace-nowrap">{{ count($order->formations) }} formation(s) · {{ number_format($order->total_price_xaf, 0, ',', ' ') }} XAF</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty"><i class="mdi mdi-usb-flash-drive-outline"></i>Aucune commande USB reçue.</div>
                    @endforelse
                </div>
            </section>
        @endif

        {{-- Répartitions --}}
        <section class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <div class="ad-card">
                <div class="ad-card-header">
                    <div>
                        <h3 class="ad-card-title">Candidatures par statut</h3>
                        <p class="ad-card-sub">{{ $fmt($stats['total_applications']) }} au total</p>
                    </div>
                </div>
                <div class="bars">
                    @foreach($applicationRows as $row)
                        <div class="bar-row {{ $row['count'] ? '' : 'is-zero' }}" title="{{ $row['label'] }} : {{ $fmt($row['count']) }} ({{ $row['pct'] }}%)">
                            <div class="bar-head">
                                <span class="bar-label">{{ $row['label'] }}</span>
                                <span class="bar-value">{{ $fmt($row['count']) }}<small>{{ $row['pct'] }}%</small></span>
                            </div>
                            <div class="bar-track"><div class="bar-fill" style="width: {{ $row['pct'] }}%"></div></div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="ad-card">
                <div class="ad-card-header">
                    <div>
                        <h3 class="ad-card-title">Offres par statut</h3>
                        <p class="ad-card-sub">{{ $fmt($stats['total_jobs']) }} au total</p>
                    </div>
                </div>
                <div class="bars">
                    @foreach($jobRows as $row)
                        <div class="bar-row {{ $row['count'] ? '' : 'is-zero' }}" title="{{ $row['label'] }} : {{ $fmt($row['count']) }} ({{ $row['pct'] }}%)">
                            <div class="bar-head">
                                <span class="bar-label">{{ $row['label'] }}</span>
                                <span class="bar-value">{{ $fmt($row['count']) }}<small>{{ $row['pct'] }}%</small></span>
                            </div>
                            <div class="bar-track"><div class="bar-fill" style="width: {{ $row['pct'] }}%"></div></div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- Activité récente --}}
        <section class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <div class="ad-card overflow-hidden">
                <div class="ad-card-header">
                    <h3 class="ad-card-title">Offres récentes</h3>
                    @if($recentJobs->count() > 0)
                        <a href="{{ route('admin.jobs.index') }}" class="ad-link">Voir tout <i class="mdi mdi-arrow-right"></i></a>
                    @endif
                </div>
                <div>
                    @forelse($recentJobs->take(5) as $job)
                        <a href="{{ route('admin.jobs.show', $job) }}" class="ad-row">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="row-title truncate m-0">{{ $job->title }}</p>
                                    <p class="row-meta truncate m-0 mt-0.5">
                                        {{ $job->company->name ?? 'N/A' }}
                                        · {{ $job->company?->city ?? 'Ville non précisée' }}
                                        · {{ $job->applications_count ?? 0 }} candidature(s)
                                    </p>
                                </div>
                                <div class="flex flex-col items-end gap-1.5 flex-shrink-0">
                                    <span class="ad-pill {{ $jobPill[$job->status] ?? '' }}">{{ $jobLabels[$job->status] ?? ucfirst($job->status) }}</span>
                                    <span class="row-time">{{ $job->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="empty"><i class="mdi mdi-briefcase-off-outline"></i>Aucune offre récente</div>
                    @endforelse
                </div>
            </div>

            <div class="ad-card overflow-hidden">
                <div class="ad-card-header">
                    <h3 class="ad-card-title">Candidatures récentes</h3>
                    @if($recentApplications->count() > 0)
                        <a href="{{ route('admin.applications.index') }}" class="ad-link">Voir tout <i class="mdi mdi-arrow-right"></i></a>
                    @endif
                </div>
                <div>
                    @forelse($recentApplications->take(5) as $application)
                        <a href="{{ route('admin.applications.show', $application) }}" class="ad-row">
                            <div class="flex items-center justify-between gap-3">
                                <div class="flex items-center gap-3 min-w-0">
                                    <span class="initial">{{ strtoupper(mb_substr($application->user->name ?? 'U', 0, 1)) }}</span>
                                    <div class="min-w-0">
                                        <p class="row-title truncate m-0">{{ $application->user->name ?? 'N/A' }}</p>
                                        <p class="row-meta truncate m-0 mt-0.5">{{ $application->job->title ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <div class="flex flex-col items-end gap-1.5 flex-shrink-0">
                                    <span class="ad-pill {{ $applicationPill[$application->status] ?? '' }}">{{ $applicationLabels[$application->status] ?? ucfirst($application->status) }}</span>
                                    <span class="row-time">{{ $application->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="empty"><i class="mdi mdi-file-document-outline"></i>Aucune candidature récente</div>
                    @endforelse
                </div>
            </div>
        </section>

        {{-- Validations en attente --}}
        @if($pendingCompanies->count() > 0 || $stats['pending_diploma_verifications'] > 0)
            <section class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                @if($pendingCompanies->count() > 0)
                    <div class="ad-card overflow-hidden">
                        <div class="ad-card-header">
                            <div>
                                <h3 class="ad-card-title">Entreprises en attente de validation</h3>
                                <p class="ad-card-sub">{{ $fmt($stats['pending_companies']) }} au total</p>
                            </div>
                            <span class="ad-pill ad-pill-warning">{{ $fmt($stats['pending_companies']) }}</span>
                        </div>
                        <div>
                            @foreach($pendingCompanies as $company)
                                <a href="{{ route('admin.companies.show', $company) }}" class="ad-row">
                                    <div class="flex items-center justify-between gap-3">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <span class="initial"><i class="mdi mdi-office-building-outline"></i></span>
                                            <div class="min-w-0">
                                                <p class="row-title truncate m-0">{{ $company->name }}</p>
                                                <p class="row-meta m-0 mt-0.5">{{ $company->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                        <i class="mdi mdi-chevron-right text-lg" style="color:var(--subtle)"></i>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($stats['pending_diploma_verifications'] > 0)
                    <div class="ad-card overflow-hidden">
                        <div class="ad-card-header">
                            <div>
                                <h3 class="ad-card-title">Vérifications de diplômes en attente</h3>
                                <p class="ad-card-sub">Demandes envoyées par les candidats</p>
                            </div>
                            <span class="ad-pill ad-pill-warning">{{ $fmt($stats['pending_diploma_verifications']) }}</span>
                        </div>
                        <div>
                            @foreach($pendingDiplomaApplications as $application)
                                <a href="{{ route('admin.applications.show', $application->id) }}" class="ad-row">
                                    <div class="flex items-center justify-between gap-3">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <span class="initial"><i class="mdi mdi-school-outline"></i></span>
                                            <div class="min-w-0">
                                                <p class="row-title truncate m-0">{{ $application->user->name }}</p>
                                                <p class="row-meta truncate m-0 mt-0.5">{{ $application->job->title ?? 'N/A' }} · {{ $application->job->company->name ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                        <i class="mdi mdi-chevron-right text-lg" style="color:var(--subtle)"></i>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </section>
        @endif
    </div>
@endsection
