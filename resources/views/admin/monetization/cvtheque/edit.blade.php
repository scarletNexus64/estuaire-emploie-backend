@extends('admin.layouts.app')

@section('title', 'Modifier le CV')

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-gray-900">Accueil</a>
    <span class="text-gray-400">/</span>
    <a href="{{ route('admin.cvtheque.index') }}" class="text-gray-600 hover:text-gray-900">CVthèque</a>
    <span class="text-gray-400">/</span>
    <span class="text-gray-900 font-medium">Modifier le CV</span>
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
    <form action="{{ route('admin.cvtheque.update', $resume->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Modifier le CV de {{ $resume->user->name }}</h2>

            <!-- Informations de base -->
            <div class="space-y-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Titre du CV *</label>
                    <input type="text" name="title" value="{{ old('title', $resume->title) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" required>
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Template</label>
                    <select name="template_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        @foreach(\App\Models\Resume::getAvailableTemplates() as $template)
                            <option value="{{ $template['type'] }}" {{ old('template_type', $resume->template_type) === $template['type'] ? 'selected' : '' }}>
                                {{ $template['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Résumé professionnel</label>
                    <textarea name="professional_summary" rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">{{ old('professional_summary', $resume->professional_summary) }}</textarea>
                </div>
            </div>

            <!-- Informations personnelles -->
            <div class="border-t border-gray-200 pt-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informations personnelles</h3>

                @php
                    $personalInfo = $resume->personal_info ?? [];
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nom complet</label>
                        <input type="text" name="personal_info[name]" value="{{ old('personal_info.name', $personalInfo['name'] ?? '') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" name="personal_info[email]" value="{{ old('personal_info.email', $personalInfo['email'] ?? '') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Téléphone</label>
                        <input type="text" name="personal_info[phone]" value="{{ old('personal_info.phone', $personalInfo['phone'] ?? '') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Adresse</label>
                        <input type="text" name="personal_info[address]" value="{{ old('personal_info.address', $personalInfo['address'] ?? '') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                    </div>
                </div>
            </div>

            <!-- Visibilité -->
            <div class="border-t border-gray-200 pt-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Visibilité</h3>

                <div class="space-y-3">
                    <div class="flex items-center">
                        <input type="checkbox" name="is_public" id="is_public" value="1"
                               {{ old('is_public', $resume->is_public) ? 'checked' : '' }}
                               class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                        <label for="is_public" class="ml-2 text-sm text-gray-700">CV public (visible dans la CVthèque)</label>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="is_default" id="is_default" value="1"
                               {{ old('is_default', $resume->is_default) ? 'checked' : '' }}
                               class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                        <label for="is_default" class="ml-2 text-sm text-gray-700">Définir comme CV par défaut</label>
                    </div>
                </div>
            </div>

            <!-- Note sur les données complètes -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex items-start gap-2">
                    <i class="mdi mdi-information text-blue-600 text-xl"></i>
                    <div class="text-sm text-blue-800">
                        <p class="font-medium mb-1">Édition simplifiée</p>
                        <p>Cette interface permet de modifier les informations de base du CV. Les données détaillées (expériences, formations, compétences) sont stockées en JSON et peuvent être modifiées directement dans la base de données si nécessaire.</p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.cvtheque.index') }}"
                   class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                    Annuler
                </a>
                <button type="submit"
                        class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                    <i class="mdi mdi-content-save"></i> Enregistrer les modifications
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
