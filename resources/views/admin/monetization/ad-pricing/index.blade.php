@extends('admin.layouts.app')

@section('title', 'Tarifs Sponsoring')
@section('page-title', 'Tarification du Sponsoring (Marketing Digital)')

@section('breadcrumbs')
    <span>/ Monétisation / Tarifs Sponsoring</span>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Prix par utilisateur ciblé</h3>
        <p style="color: #64748b; font-size: 0.875rem; margin-top: 0.5rem;">
            Définissez le prix par utilisateur touché pour chaque audience. L'entreprise voit en temps réel
            combien de personnes son budget peut atteindre. Ex : 2 FCFA / utilisateur ⇒ 500 FCFA = 250 personnes.
        </p>
    </div>

    @if(session('success'))
        <div class="alert alert-success" style="margin: 1rem;">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Audience</th>
                    <th>Prix / utilisateur (FCFA)</th>
                    <th>Budget min (FCFA)</th>
                    <th>Budget max (FCFA)</th>
                    <th>Actif</th>
                    <th>Aperçu</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $labels = [
                        'all' => 'Tout le monde',
                        'student' => 'Étudiants',
                        'candidate' => 'Candidats',
                        'recruiter' => 'Entreprises',
                    ];
                @endphp
                @foreach($configs as $config)
                    <tr>
                        <form action="{{ route('admin.ad-pricing.update', $config) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <td>
                                <strong>{{ $labels[$config->audience_segment] ?? $config->audience_segment }}</strong>
                            </td>
                            <td>
                                <input type="number" step="0.01" min="0" name="price_per_user"
                                       value="{{ $config->price_per_user }}" class="form-control" style="max-width: 130px;" required>
                            </td>
                            <td>
                                <input type="number" step="1" min="0" name="min_budget"
                                       value="{{ (int) $config->min_budget }}" class="form-control" style="max-width: 130px;" required>
                            </td>
                            <td>
                                <input type="number" step="1" min="0" name="max_budget"
                                       value="{{ (int) $config->max_budget }}" class="form-control" style="max-width: 150px;" required>
                            </td>
                            <td>
                                <input type="checkbox" name="is_active" value="1" {{ $config->is_active ? 'checked' : '' }}>
                            </td>
                            <td>
                                <span style="color: #64748b; font-size: 0.875rem;">
                                    @php $reach = $config->price_per_user > 0 ? (int) floor(500 / $config->price_per_user) : 0; @endphp
                                    500 FCFA ⇒ <strong>{{ $reach }}</strong> pers.
                                </span>
                            </td>
                            <td>
                                <button type="submit" class="btn btn-primary btn-sm">Enregistrer</button>
                            </td>
                        </form>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
