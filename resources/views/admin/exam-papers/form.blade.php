@extends('admin.layouts.app')

@section('title', isset($examPaper) ? 'Modifier une épreuve' : 'Ajouter une épreuve')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center gap-4 mb-2">
            <a href="{{ route('admin.exam-papers.index') }}" class="text-gray-600 hover:text-gray-900">
                <i class="mdi mdi-arrow-left text-2xl"></i>
            </a>
            <h1 class="text-3xl font-bold text-gray-900">
                {{ isset($examPaper) ? '✏️ Modifier' : '➕ Ajouter' }} une épreuve
            </h1>
        </div>
        <p class="text-gray-600 ml-12">
            Uploader un PDF (sujet ou corrigé) pour le Mode Étudiant
        </p>
    </div>

    @if ($errors->any())
        <div class="mb-6 rounded-lg bg-red-100 border border-red-400 text-red-700 px-4 py-3">
            <p class="font-bold mb-2">❌ Erreurs de validation :</p>
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ isset($examPaper) ? route('admin.exam-papers.update', $examPaper) : route('admin.exam-papers.store') }}"
          method="POST"
          enctype="multipart/form-data"
          class="space-y-6">
        @csrf
        @if(isset($examPaper))
            @method('PUT')
        @endif

        <!-- Informations générales -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">📝 Informations générales</h3>
            </div>
            <div class="card-body">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Titre -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Titre de l'épreuve <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            name="title"
                            value="{{ old('title', $examPaper->title ?? '') }}"
                            placeholder="Ex: Examen de Mathématiques - Session 2024"
                            class="form-control"
                            required
                        >
                    </div>

                    <!-- Spécialité -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Spécialité <span class="text-red-500">*</span>
                        </label>
                        <select name="specialty" class="form-control" required id="specialty-select">
                            <option value="">Sélectionner...</option>
                            @foreach($specialties as $key => $specialty)
                                <option value="{{ $key }}"
                                        {{ old('specialty', $examPaper->specialty ?? '') == $key ? 'selected' : '' }}>
                                    {{ $specialty }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Matière -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Matière <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            name="subject"
                            value="{{ old('subject', $examPaper->subject ?? '') }}"
                            placeholder="Ex: Mathématiques"
                            class="form-control"
                            list="subjects-list"
                            required
                        >
                        <datalist id="subjects-list">
                            @foreach($subjects as $subject)
                                <option value="{{ $subject }}">
                            @endforeach
                        </datalist>
                    </div>

                    <!-- Niveau -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Niveau <span class="text-red-500">*</span>
                        </label>
                        <select name="level" class="form-control" required>
                            @foreach($levels as $levelValue => $levelLabel)
                                <option value="{{ $levelValue }}"
                                        {{ old('level', $examPaper->level ?? 1) == $levelValue ? 'selected' : '' }}>
                                    {{ $levelLabel }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Année -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Année
                        </label>
                        <input
                            type="number"
                            name="year"
                            value="{{ old('year', $examPaper->year ?? date('Y')) }}"
                            min="1900"
                            max="{{ date('Y') + 1 }}"
                            placeholder="{{ date('Y') }}"
                            class="form-control"
                        >
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Description (optionnelle)
                        </label>
                        <textarea
                            name="description"
                            rows="3"
                            placeholder="Ajoutez des informations supplémentaires sur cette épreuve..."
                            class="form-control"
                        >{{ old('description', $examPaper->description ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fichier PDF -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">📄 Fichier PDF</h3>
            </div>
            <div class="card-body">
                @if(isset($examPaper) && $examPaper->file_path)
                    <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <i class="mdi mdi-file-pdf text-red-500 text-3xl"></i>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $examPaper->file_name }}</p>
                                    <p class="text-sm text-gray-600">{{ $examPaper->formatted_file_size }}</p>
                                </div>
                            </div>
                            <a href="{{ route('admin.exam-papers.download', $examPaper) }}"
                               class="btn btn-sm btn-info">
                                <i class="mdi mdi-download"></i> Télécharger
                            </a>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 mb-2">
                        Uploader un nouveau fichier remplacera l'ancien
                    </p>
                @endif

                <div class="file-upload" onclick="document.getElementById('pdf-file').click()">
                    <input
                        type="file"
                        id="pdf-file"
                        name="file"
                        accept=".pdf,application/pdf"
                        {{ isset($examPaper) ? '' : 'required' }}
                        onchange="displayFileName(this)"
                    >
                    <div class="text-center">
                        <i class="mdi mdi-cloud-upload text-4xl text-gray-400 mb-2"></i>
                        <p class="text-gray-700 font-medium mb-1">
                            Cliquez pour uploader un fichier PDF
                        </p>
                        <p class="text-sm text-gray-500">
                            Maximum 20 MB - Format PDF uniquement
                        </p>
                    </div>
                </div>

                <p id="file-name" class="mt-2 text-sm text-gray-600 hidden"></p>
            </div>
        </div>

        <!-- Options -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">⚙️ Options</h3>
            </div>
            <div class="card-body">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Checkbox: Est-ce un corrigé ? -->
                    <div>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input
                                type="checkbox"
                                name="is_correction"
                                id="is_correction_checkbox"
                                value="1"
                                {{ old('is_correction', $examPaper->is_correction ?? false) ? 'checked' : '' }}
                                class="w-5 h-5 text-green-600 rounded focus:ring-green-500"
                                onchange="toggleCorrectionSection()"
                            >
                            <div>
                                <p class="font-medium text-gray-900">Corrigé</p>
                                <p class="text-sm text-gray-500">Ce fichier est un corrigé</p>
                            </div>
                        </label>
                    </div>

                    <!-- Checkbox: Actif -->
                    <div>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input
                                type="checkbox"
                                name="is_active"
                                value="1"
                                {{ old('is_active', $examPaper->is_active ?? true) ? 'checked' : '' }}
                                class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500"
                            >
                            <div>
                                <p class="font-medium text-gray-900">Actif</p>
                                <p class="text-sm text-gray-500">Visible pour les étudiants</p>
                            </div>
                        </label>
                    </div>

                    <!-- Ordre d'affichage -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Ordre d'affichage
                        </label>
                        <input
                            type="number"
                            name="display_order"
                            value="{{ old('display_order', $examPaper->display_order ?? 0) }}"
                            min="0"
                            class="form-control"
                        >
                    </div>
                </div>
            </div>
        </div>

        <!-- Lier une correction (visible seulement si ce n'est PAS un corrigé) -->
        <div class="card" id="correction-link-section"
             style="{{ old('is_correction', $examPaper->is_correction ?? false) ? 'display:none;' : '' }}">
            <div class="card-header" style="background-color: #28a745; color: white;">
                <h3 class="card-title" style="color: white;">✅ Correction disponible</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Lier une correction à ce sujet
                    </label>
                    <select name="correction_paper_id" class="form-control">
                        <option value="">— Aucune correction liée —</option>
                        @foreach($corrections as $correction)
                            <option value="{{ $correction->id }}"
                                    {{ old('correction_paper_id', $examPaper->correction_paper_id ?? '') == $correction->id ? 'selected' : '' }}>
                                {{ $correction->title }} ({{ $correction->specialty }} - {{ $correction->subject }}, {{ $correction->year }})
                            </option>
                        @endforeach
                    </select>
                    <small class="form-text text-muted" style="margin-top: 0.5rem; display: block;">
                        Sélectionnez le corrigé correspondant à ce sujet. Le badge "Correction dispo" s'affichera dans l'application.
                    </small>
                </div>

                @if(isset($examPaper) && $examPaper->correction_paper_id)
                    <div class="mt-3 p-3 rounded-lg" style="background-color: #d4edda; border: 1px solid #c3e6cb;">
                        <div class="flex items-center gap-2">
                            <span style="color: #28a745; font-size: 1.2rem;">✅</span>
                            <span style="color: #155724; font-weight: 600;">
                                Correction liée : {{ $examPaper->correctionPaper->title ?? 'N/A' }}
                            </span>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Actions -->
        <div class="flex gap-3 justify-end">
            <a href="{{ route('admin.exam-papers.index') }}" class="btn btn-secondary">
                <i class="mdi mdi-close"></i> Annuler
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="mdi mdi-check"></i>
                {{ isset($examPaper) ? 'Mettre à jour' : 'Enregistrer' }}
            </button>
        </div>
    </form>
</div>

<script>
function displayFileName(input) {
    const fileName = input.files[0]?.name;
    const fileNameEl = document.getElementById('file-name');

    if (fileName) {
        fileNameEl.textContent = '📄 Fichier sélectionné : ' + fileName;
        fileNameEl.classList.remove('hidden');
    } else {
        fileNameEl.classList.add('hidden');
    }
}

function toggleCorrectionSection() {
    const isCorrection = document.getElementById('is_correction_checkbox').checked;
    const section = document.getElementById('correction-link-section');
    section.style.display = isCorrection ? 'none' : '';
}
</script>
@endsection
