@extends('admin.layouts.app')

@section('title', 'Notifications FCM par Topic')
@section('page-title', 'Envoyer une notification par topic')

@section('breadcrumbs')
    <span> / </span>
    <span>FCM Topics</span>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">📢 Envoi de notification FCM par topic</h3>
    </div>

    <div class="card-body">
        <div style="background: #fffbeb; border-left: 4px solid #f59e0b; padding: 1rem; border-radius: 4px; margin-bottom: 1.5rem;">
            <p style="margin: 0; color: #92400e;">
                <strong>ℹ️ À propos des topics</strong><br>
                Les utilisateurs s'abonnent automatiquement aux topics <code>forum</code> et <code>maintenance</code>
                à la connexion. Le topic <code>all</code> nécessite un abonnement explicite via les Réglages
                de l'application. Un message envoyé à un topic est délivré à <em>tous les abonnés</em>.
            </p>
        </div>

        <form method="POST" action="{{ route('admin.fcm-topics.send') }}">
            @csrf

            <div class="form-group">
                <label class="form-label">Topic cible *</label>
                <select name="topic" class="form-control" required>
                    <option value="">— Choisir un topic —</option>
                    @foreach($topics as $key => $description)
                        <option value="{{ $key }}" {{ old('topic') === $key ? 'selected' : '' }}>
                            {{ $key }} — {{ $description }}
                        </option>
                    @endforeach
                </select>
                @error('topic')<small style="color: var(--danger);">{{ $message }}</small>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Titre * <small style="color:#6b7280;">(max 255)</small></label>
                <input type="text" name="title" class="form-control" maxlength="255" value="{{ old('title') }}" required>
                @error('title')<small style="color: var(--danger);">{{ $message }}</small>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Message * <small style="color:#6b7280;">(max 1000)</small></label>
                <textarea name="body" class="form-control" rows="4" maxlength="1000" required>{{ old('body') }}</textarea>
                @error('body')<small style="color: var(--danger);">{{ $message }}</small>@enderror
            </div>

            <fieldset style="border: 1px dashed #e5e7eb; border-radius: 8px; padding: 1rem; margin-bottom: 1.5rem;">
                <legend style="padding: 0 0.5rem; color: #6b7280; font-size: 0.9rem;">Payload data (optionnel)</legend>
                <p style="color: #6b7280; font-size: 0.85rem; margin-bottom: 0.75rem;">
                    Ces champs sont envoyés dans la <code>data</code> du message FCM. Utile pour le routage côté app
                    (ex: <code>type=maintenance_activated</code> déclenche le check maintenance côté Flutter).
                </p>
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">type</label>
                        <input type="text" name="data_type" class="form-control" value="{{ old('data_type') }}"
                               placeholder="ex: announcement, maintenance_activated">
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">url (deep link)</label>
                        <input type="text" name="data_url" class="form-control" value="{{ old('data_url') }}"
                               placeholder="ex: /notifications">
                    </div>
                </div>
            </fieldset>

            <div style="display: flex; gap: 1rem; padding-top: 1rem; border-top: 1px solid var(--light);">
                <button type="submit" class="btn btn-primary"
                        onclick="return confirm('Envoyer cette notification à tous les abonnés du topic ?')">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                    Envoyer la notification
                </button>
                <a href="{{ url()->previous() }}" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
