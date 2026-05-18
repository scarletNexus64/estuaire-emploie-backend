@extends('admin.layouts.app')

@section('title', 'Créer une Promotion')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm text-gray-600 mb-2">
            <a href="{{ route('admin.pack-promotions.index') }}" class="hover:text-primary">Promotions</a>
            <i class="fas fa-chevron-right text-xs"></i>
            <span>Créer</span>
        </div>
        <h1 class="text-3xl font-bold text-gray-800">Créer une Promotion</h1>
        <p class="text-gray-600 mt-1">Offrez un pack gratuitement pendant une période définie</p>
    </div>

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Formulaire -->
    <form method="POST" action="{{ route('admin.pack-promotions.store') }}" class="bg-white rounded-lg shadow-md p-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Nom de la promotion -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Nom de la promotion <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name') }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:border-transparent"
                       placeholder="Ex: Promo Rentrée 2026 - Pack Bac Gratuit"
                       required>
                <p class="text-xs text-gray-500 mt-1">Nom visible uniquement dans l'admin</p>
            </div>

            <!-- Description -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Description (optionnelle)</label>
                <textarea name="description" rows="3"
                          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:border-transparent"
                          placeholder="Détails internes sur cette promotion...">{{ old('description') }}</textarea>
            </div>

            <!-- Type de pack -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Type de pack <span class="text-red-500">*</span>
                </label>
                <select name="promotionable_type" id="pack-type"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:border-transparent"
                        required>
                    <option value="">Sélectionnez un type</option>
                    @foreach($promotionableTypes as $type => $label)
                        <option value="{{ $type }}" {{ old('promotionable_type') == $type ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Pack spécifique -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Pack à promouvoir <span class="text-red-500">*</span>
                </label>
                <select name="promotionable_id" id="pack-select"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:border-transparent"
                        required disabled>
                    <option value="">Sélectionnez d'abord un type de pack</option>
                </select>
                <p class="text-xs text-gray-500 mt-1" id="pack-loading" style="display: none;">
                    <i class="fas fa-spinner fa-spin"></i> Chargement des packs...
                </p>
            </div>

            <!-- Date de début -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Date de début <span class="text-red-500">*</span>
                </label>
                <input type="datetime-local" name="start_date" value="{{ old('start_date', now()->format('Y-m-d\TH:i')) }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:border-transparent"
                       required>
                <p class="text-xs text-gray-500 mt-1">Début de la période promotionnelle</p>
            </div>

            <!-- Date de fin -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Date de fin <span class="text-red-500">*</span>
                </label>
                <input type="datetime-local" name="end_date" value="{{ old('end_date') }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:border-transparent"
                       required>
                <p class="text-xs text-gray-500 mt-1">Fin de la période promotionnelle</p>
            </div>

            <!-- Durée d'utilisation -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Durée d'utilisation (jours) <span class="text-red-500">*</span>
                </label>
                <input type="number" name="usage_duration_days" value="{{ old('usage_duration_days', 30) }}"
                       min="1" max="3650"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:border-transparent"
                       required>
                <p class="text-xs text-gray-500 mt-1">Nombre de jours après activation par l'utilisateur</p>
            </div>

            <!-- Limite d'activations -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Limite d'activations (optionnelle)</label>
                <input type="number" name="max_activations" value="{{ old('max_activations') }}"
                       min="1"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:border-transparent"
                       placeholder="Illimité si vide">
                <p class="text-xs text-gray-500 mt-1">Nombre maximum d'utilisateurs pouvant activer</p>
            </div>

            <!-- Activer la promotion -->
            <div class="md:col-span-2">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                           class="w-5 h-5 text-primary focus:ring-2 focus:ring-primary rounded">
                    <span class="text-sm font-medium text-gray-700">
                        Activer immédiatement cette promotion
                    </span>
                </label>
                <p class="text-xs text-gray-500 mt-1 ml-8">
                    ✅ Si coché, une notification FCM sera envoyée à tous les utilisateurs
                </p>
            </div>
        </div>

        <!-- Info box -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex gap-3">
                <i class="fas fa-info-circle text-blue-600 text-xl"></i>
                <div class="text-sm text-blue-800">
                    <p class="font-semibold mb-2">Comment ça fonctionne ?</p>
                    <ul class="list-disc list-inside space-y-1 text-xs">
                        <li>Le pack sera affiché à <strong>0F</strong> dans l'app mobile pendant la période définie</li>
                        <li>Les utilisateurs pourront l'activer gratuitement en un clic</li>
                        <li>Après activation, ils auront accès au pack pendant la <strong>durée d'utilisation</strong> définie</li>
                        <li>Une fois la période d'utilisation expirée, ils devront payer pour renouveler</li>
                        <li>Si la promotion est activée, une notification push sera envoyée à tous les utilisateurs</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Boutons -->
        <div class="flex gap-4 mt-6">
            <button type="submit"
                    class="bg-primary hover:bg-primary-dark text-white px-6 py-3 rounded-lg font-semibold transition-colors">
                <i class="fas fa-save mr-2"></i>Créer la promotion
            </button>
            <a href="{{ route('admin.pack-promotions.index') }}"
               class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-3 rounded-lg font-semibold transition-colors">
                Annuler
            </a>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const packTypeSelect = document.getElementById('pack-type');
    const packSelect = document.getElementById('pack-select');
    const packLoading = document.getElementById('pack-loading');

    packTypeSelect.addEventListener('change', function() {
        const type = this.value;

        if (!type) {
            packSelect.innerHTML = '<option value="">Sélectionnez d\'abord un type de pack</option>';
            packSelect.disabled = true;
            return;
        }

        // Afficher le loader
        packLoading.style.display = 'block';
        packSelect.disabled = true;
        packSelect.innerHTML = '<option value="">Chargement...</option>';

        // Charger les packs via AJAX
        fetch(`/admin/api/packs/${encodeURIComponent(type)}`)
            .then(response => response.json())
            .then(data => {
                packSelect.innerHTML = '<option value="">Sélectionnez un pack</option>';

                if (data.length === 0) {
                    packSelect.innerHTML = '<option value="">Aucun pack disponible</option>';
                } else {
                    data.forEach(pack => {
                        const option = document.createElement('option');
                        option.value = pack.id;
                        option.textContent = `${pack.name} - ${pack.price}`;
                        packSelect.appendChild(option);
                    });
                    packSelect.disabled = false;
                }

                packLoading.style.display = 'none';
            })
            .catch(error => {
                console.error('Erreur:', error);
                packSelect.innerHTML = '<option value="">Erreur de chargement</option>';
                packLoading.style.display = 'none';
            });
    });

    // Déclencher le changement si un type est déjà sélectionné (old input)
    if (packTypeSelect.value) {
        packTypeSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endpush
@endsection
