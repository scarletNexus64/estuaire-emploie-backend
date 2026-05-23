@extends('admin.layouts.app')

@section('title', 'Offre publiée — Test de compétences ?')
@section('page-title', 'Offre publiée avec succès')

@section('breadcrumbs')
    <span> / </span>
    <a href="{{ route('admin.jobs.index') }}" style="color: inherit; text-decoration: none;">Offres d'emploi</a>
    <span> / </span>
    <span>Test de compétences</span>
@endsection

@section('content')
<div class="card" style="max-width: 720px; margin: 0 auto;">
    <div class="card-body" style="padding: 2.5rem; text-align: center;">

        <div style="width: 80px; height: 80px; margin: 0 auto 1.5rem; background: rgba(45, 150, 211, 0.12); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
            <svg width="40" height="40" fill="none" stroke="#2d96d3" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>

        <h2 style="margin-bottom: 0.5rem;">Offre « {{ $job->title }} » créée</h2>
        <p style="color: #6b7280; margin-bottom: 2rem;">
            Pour {{ $job->company?->name }}{{ $job->company?->city ? ' — ' . $job->company->city : '' }}
        </p>

        <div style="background: #f9fafb; border-radius: 12px; padding: 1.5rem; margin-bottom: 2rem; text-align: left;">
            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
                <div style="width: 40px; height: 40px; background: rgba(45, 150, 211, 0.12); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                    <svg width="22" height="22" fill="none" stroke="#2d96d3" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
                <h3 style="margin: 0; font-size: 1.1rem;">Souhaitez-vous ajouter un test de compétences ?</h3>
            </div>
            <p style="color: #6b7280; font-size: 0.9rem; margin: 0 0 1rem;">
                Filtrez automatiquement les candidats selon leur niveau réel. Le test sera proposé
                au moment de la candidature.
            </p>
            <ul style="list-style: none; padding: 0; margin: 0; color: #4b5563; font-size: 0.9rem;">
                <li style="display: flex; gap: 0.5rem; padding: 0.25rem 0;"><span style="color: #10b981;">✓</span> Filtrage automatique des candidats</li>
                <li style="display: flex; gap: 0.5rem; padding: 0.25rem 0;"><span style="color: #10b981;">✓</span> Évaluation objective des compétences</li>
                <li style="display: flex; gap: 0.5rem; padding: 0.25rem 0;"><span style="color: #10b981;">✓</span> Gain de temps en présélection</li>
            </ul>
        </div>

        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
            <a href="{{ route('admin.skill-tests.create', ['job_id' => $job->id]) }}" class="btn btn-primary" style="min-width: 220px;">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Oui, configurer un test
            </a>
            <a href="{{ route('admin.jobs.send-notifications', $job) }}" class="btn btn-secondary" style="min-width: 220px;">
                📢 Non, notifier les candidats
            </a>
            <a href="{{ route('admin.jobs.index') }}" style="align-self: center; color: #6b7280; text-decoration: underline;">
                Plus tard
            </a>
        </div>
    </div>
</div>
@endsection
