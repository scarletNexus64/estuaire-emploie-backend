@extends('admin.layouts.app')

@section('title', isset($fascicule) ? 'Modifier un fascicule' : 'Ajouter un fascicule')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center gap-4 mb-2">
            <a href="{{ route('admin.fascicules.index') }}" class="text-gray-600 hover:text-gray-900">
                <i class="mdi mdi-arrow-left text-2xl"></i>
            </a>
            <h1 class="text-3xl font-bold text-gray-900">
                {{ isset($fascicule) ? 'Modifier' : 'Ajouter' }} un fascicule
            </h1>
        </div>
        <p class="text-gray-600 ml-12">
            Uploader un support de cours (PDF ou Word) pour les étudiants (Pack Étudiant)
        </p>
    </div>

    @if ($errors->any())
        <div class="mb-6 rounded-lg bg-red-100 border border-red-400 text-red-700 px-4 py-3">
            <p class="font-bold mb-2">Erreurs de validation :</p>
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ isset($fascicule) ? route('admin.fascicules.update', $fascicule) : route('admin.fascicules.store') }}"
          method="POST"
          enctype="multipart/form-data"
          class="space-y-6">
        @csrf
        @if(isset($fascicule))
            @method('PUT')
        @endif

        <!-- Informations générales -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Informations générales</h3>
            </div>
            <div class="card-body">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Titre <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" class="form-control" required
                               value="{{ old('title', $fascicule->title ?? '') }}"
                               placeholder="Ex: Comptabilité générale - Cours complet">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                        @php($kind = old('kind', $fascicule->kind ?? 'autre'))
                        <select name="kind" class="form-control">
                            <option value="support_cours" @selected($kind === 'support_cours')>Support de cours</option>
                            <option value="banque_epreuves" @selected($kind === 'banque_epreuves')>Banque d'épreuves corrigées</option>
                            <option value="autre" @selected($kind === 'autre')>Autre fascicule</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Dossier (filière) dans l'app</label>
                        @php($code = old('filiere_code', $fascicule->filiere_code ?? ''))
                        <select name="filiere_code" class="form-control">
                            <option value="">— Aucun (dossier « Autres fascicules ») —</option>
                            @foreach(\App\Support\FasciculeCatalog::DOMAINS as $domainKey => $domainInfo)
                                @php($domainCodes = \App\Support\FasciculeCatalog::filieresOf($domainKey))
                                @if(count($domainCodes))
                                    <optgroup label="{{ $domainInfo['label'] }}">
                                        @foreach(collect($domainCodes)->sortBy(fn ($c) => \App\Support\FasciculeCatalog::filiereName($c)) as $c)
                                            <option value="{{ $c }}" @selected($code === $c)>{{ \App\Support\FasciculeCatalog::filiereLabel($c) }}</option>
                                        @endforeach
                                    </optgroup>
                                @endif
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Le domaine est déduit de la filière.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Filière</label>
                        <input type="text" name="filiere" class="form-control" list="filieres-list"
                               value="{{ old('filiere', $fascicule->filiere ?? '') }}"
                               placeholder="Ex: Comptabilité et Gestion">
                        <datalist id="filieres-list">
                            @foreach($filieres as $filiere)
                                <option value="{{ $filiere }}">
                            @endforeach
                        </datalist>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Niveau</label>
                        <input type="text" name="level" class="form-control" list="levels-list"
                               value="{{ old('level', $fascicule->level ?? '') }}"
                               placeholder="Ex: Licence 1, BTS 2">
                        <datalist id="levels-list">
                            @foreach($levels as $level)
                                <option value="{{ $level }}">
                            @endforeach
                        </datalist>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Matière</label>
                        <input type="text" name="subject" class="form-control" list="subjects-list"
                               value="{{ old('subject', $fascicule->subject ?? '') }}"
                               placeholder="Ex: Mathématiques financières">
                        <datalist id="subjects-list">
                            @foreach($subjects as $subject)
                                <option value="{{ $subject }}">
                            @endforeach
                        </datalist>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Auteur</label>
                        <input type="text" name="author" class="form-control"
                               value="{{ old('author', $fascicule->author ?? '') }}"
                               placeholder="Ex: Dr. Jean Dupont">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nombre de pages (optionnel)</label>
                        <input type="number" name="pages_count" min="1" class="form-control"
                               value="{{ old('pages_count', $fascicule->pages_count ?? '') }}">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description (optionnelle)</label>
                        <textarea name="description" rows="3" class="form-control"
                                  placeholder="Contenu, chapitres abordés...">{{ old('description', $fascicule->description ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fichier PDF -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Fichier (PDF ou Word)</h3>
            </div>
            <div class="card-body">
                @if(isset($fascicule) && $fascicule->file_path)
                    <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <i class="mdi mdi-file-pdf text-red-500 text-3xl"></i>
                                <div>
                                    <p class="font-medium text-gray-900">{{ basename($fascicule->file_path) }}</p>
                                    <p class="text-sm text-gray-600">{{ $fascicule->formatted_file_size }}</p>
                                </div>
                            </div>
                            <a href="{{ route('admin.fascicules.download', $fascicule) }}" class="btn btn-sm btn-info">
                                <i class="mdi mdi-download"></i> Télécharger
                            </a>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 mb-2">Uploader un nouveau fichier remplacera l'ancien</p>
                @endif

                <div class="file-upload" onclick="document.getElementById('pdf-file').click()">
                    <input type="file" id="pdf-file" name="file" accept=".pdf,.docx,application/pdf,application/vnd.openxmlformats-officedocument.wordprocessingml.document"
                           {{ isset($fascicule) ? '' : 'required' }}
                           onchange="displayFileName(this, 'file-name')">
                    <div class="text-center">
                        <i class="mdi mdi-cloud-upload text-4xl text-gray-400 mb-2"></i>
                        <p class="text-gray-700 font-medium mb-1">Cliquez pour uploader un fichier PDF ou Word</p>
                        <p class="text-sm text-gray-500">Maximum 50 MB - PDF ou Word (.docx)</p>
                    </div>
                </div>
                <p id="file-name" class="mt-2 text-sm text-gray-600 hidden"></p>
            </div>
        </div>

        <!-- Couverture -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Couverture (optionnelle)</h3>
            </div>
            <div class="card-body">
                @if(isset($fascicule) && $fascicule->cover_url)
                    <div class="mb-4 flex items-center gap-4">
                        <img src="{{ $fascicule->cover_url }}" alt="" style="width:80px;height:112px;object-fit:cover;border-radius:6px;">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="remove_cover" value="1" class="w-4 h-4">
                            <span class="text-sm text-gray-700">Supprimer la couverture</span>
                        </label>
                    </div>
                @endif

                <div class="file-upload" onclick="document.getElementById('cover-file').click()">
                    <input type="file" id="cover-file" name="cover" accept="image/jpeg,image/png,image/webp"
                           onchange="displayFileName(this, 'cover-name')">
                    <div class="text-center">
                        <i class="mdi mdi-image-plus text-4xl text-gray-400 mb-2"></i>
                        <p class="text-gray-700 font-medium mb-1">Cliquez pour uploader une image</p>
                        <p class="text-sm text-gray-500">JPG, PNG ou WEBP - Maximum 4 MB</p>
                    </div>
                </div>
                <p id="cover-name" class="mt-2 text-sm text-gray-600 hidden"></p>
            </div>
        </div>

        <!-- Options -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Options</h3>
            </div>
            <div class="card-body">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_published" value="1"
                           {{ old('is_published', $fascicule->is_published ?? true) ? 'checked' : '' }}
                           class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500">
                    <div>
                        <p class="font-medium text-gray-900">Publié</p>
                        <p class="text-sm text-gray-500">Visible dans l'application</p>
                    </div>
                </label>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex gap-3 justify-end">
            <a href="{{ route('admin.fascicules.index') }}" class="btn btn-secondary">
                <i class="mdi mdi-close"></i> Annuler
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="mdi mdi-check"></i>
                {{ isset($fascicule) ? 'Mettre à jour' : 'Enregistrer' }}
            </button>
        </div>
    </form>
</div>

<script>
function displayFileName(input, targetId) {
    const fileName = input.files[0]?.name;
    const el = document.getElementById(targetId);

    if (fileName) {
        el.textContent = 'Fichier sélectionné : ' + fileName;
        el.classList.remove('hidden');
    } else {
        el.classList.add('hidden');
    }
}
</script>
@endsection
