@extends('admin.layouts.app')

@section('title', 'Créer un Étudiant')
@section('page-title', 'Créer un Nouvel Étudiant')

@section('breadcrumbs')
    <span> / </span>
    <a href="{{ route('admin.students.index') }}">Étudiants</a>
    <span> / </span>
    <span>Créer</span>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@19.2.19/build/css/intlTelInput.css">
<style>
.iti { width: 100%; }
.iti__flag-container { z-index: 10; }
</style>
@endpush

@section('content')
<div class="card">
    <form action="{{ route('admin.students.store') }}" method="POST">
        @csrf

        <div class="card-body">
            <!-- Alert Info -->
            <div class="alert alert-info" style="margin-bottom: 2rem;">
                <strong>ℹ️ Information :</strong> L'étudiant recevra automatiquement :
                <ul style="margin-bottom: 0; margin-top: 0.5rem;">
                    <li><strong>Pack C1 (SILVER)</strong> - Valable 1 mois</li>
                    <li><strong>Mode Étudiant</strong> - Valable 1 an</li>
                </ul>
                Un mot de passe sera généré automatiquement et pourra être envoyé par SMS.
            </div>

            <!-- Nom & Prénom -->
            <div class="form-group">
                <label class="form-label required">Nom & Prénom</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name') }}" placeholder="Ex: Jean Dupont" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Email -->
            <div class="form-group">
                <label class="form-label">Adresse Email</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email') }}" placeholder="Ex: jean.dupont@example.com">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-text">Champ optionnel. L'étudiant peut aussi se connecter avec son téléphone.</small>
            </div>

            <!-- Téléphone -->
            <div class="form-group">
                <label class="form-label required">Numéro de Téléphone</label>
                <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror"
                       value="{{ old('phone') }}" placeholder="Ex: +242064567890" required>
                @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-text">Format international recommandé (+242...).</small>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <!-- Template CV -->
                <div class="form-group">
                    <label class="form-label required">Template CV (Bibliothèque)</label>
                    <input type="text" id="templateSearch" class="form-control mb-2" placeholder="Tapez pour filtrer le template...">
                    <select name="cv_template_id" id="templateSelect" class="form-control @error('cv_template_id') is-invalid @enderror" required>
                        <option value="">-- Choisir un template --</option>
                        @foreach($templates as $template)
                            <option
                                value="{{ $template->id }}"
                                data-search="{{ strtolower(($template->title ?? '') . ' ' . ($template->customization['specialty'] ?? '') . ' ' . ($template->customization['level'] ?? '') . ' ' . ($template->user->name ?? '')) }}"
                                {{ (string)old('cv_template_id') === (string)$template->id ? 'selected' : '' }}
                            >
                                #{{ $template->id }} - {{ $template->title }} | {{ $template->customization['specialty'] ?? 'N/A' }} | {{ $template->customization['level'] ?? 'N/A' }}
                            </option>
                        @endforeach
                    </select>
                    @error('cv_template_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text">Choisissez un CV depuis la CV Library. Il sera dupliqué pour l'étudiant.</small>
                </div>

                <!-- Spécialité -->
                <div class="form-group">
                    <label class="form-label required">Spécialité</label>
                    <input type="text" name="specialty" class="form-control @error('specialty') is-invalid @enderror"
                           value="{{ old('specialty') }}" placeholder="Ex: Informatique, Gestion, Droit..." required>
                    @error('specialty')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text">Saisissez la spécialité de l'étudiant.</small>
                </div>

                <!-- Niveau -->
                <div class="form-group">
                    <label class="form-label required">Niveau Académique</label>
                    <input type="text" name="level" class="form-control @error('level') is-invalid @enderror"
                           value="{{ old('level') }}" placeholder="Ex: L1, L2, L3, M1, M2, BTS1, BTS2..." required>
                    @error('level')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text">Saisissez le niveau académique de l'étudiant.</small>
                </div>
            </div>

            <!-- Centre d'Intérêt -->
            <div class="form-group">
                <label class="form-label">Centre d'Intérêt</label>
                <textarea name="interests" class="form-control @error('interests') is-invalid @enderror"
                          rows="3" placeholder="Ex: Développement web, Marketing digital, Finance...">{{ old('interests') }}</textarea>
                @error('interests')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-text">Champ optionnel. Décrivez les centres d'intérêt de l'étudiant.</small>
            </div>
        </div>

        <div class="card-footer" style="display: flex; gap: 0.5rem; justify-content: flex-end;">
            <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">Annuler</a>
            <button type="submit" class="btn btn-primary">✅ Créer l'Étudiant</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@19.2.19/build/js/intlTelInput.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const phoneInput = document.querySelector('input[name="phone"]');
    const templateSearch = document.getElementById('templateSearch');
    const templateSelect = document.getElementById('templateSelect');

    const iti = window.intlTelInput(phoneInput, {
        initialCountry: "cm", // Cameroun par défaut
        preferredCountries: ["cm", "cg", "ga", "td", "cf"], // Pays d'Afrique Centrale
        separateDialCode: true,
        utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@19.2.19/build/js/utils.js"
    });

    // Au submit, récupérer le numéro complet avec l'indicatif
    phoneInput.closest('form').addEventListener('submit', function(e) {
        const fullNumber = iti.getNumber();
        phoneInput.value = fullNumber;
    });

    // Filtre simple sur la liste de templates
    if (templateSearch && templateSelect) {
        templateSearch.addEventListener('input', function() {
            const term = this.value.toLowerCase().trim();
            Array.from(templateSelect.options).forEach((option, index) => {
                if (index === 0) {
                    option.hidden = false;
                    return;
                }
                const searchable = option.dataset.search || '';
                option.hidden = term !== '' && !searchable.includes(term);
            });
        });
    }
});
</script>
@endpush
