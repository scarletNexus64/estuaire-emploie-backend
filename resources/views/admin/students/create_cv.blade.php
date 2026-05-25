@extends('admin.layouts.app')

@section('title', 'Créer le CV de l\'étudiant')
@section('page-title', 'Création du CV - ' . $student->name)

@section('breadcrumbs')
    <span> / </span>
    <a href="{{ route('admin.students.index') }}">Création de compte étudiant</a>
    <span> / </span>
    <span>Créer le CV</span>
@endsection

@push('styles')
<style>
    .editor-container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        margin-top: 2rem;
    }

    .form-section {
        background: white;
        border-radius: 0.75rem;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        max-height: 85vh;
        overflow-y: auto;
    }

    .preview-section {
        position: sticky;
        top: 1rem;
        background: #f5f5f5;
        border-radius: 0.75rem;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        max-height: 85vh;
        overflow-y: auto;
    }

    /* Preview CV */
    .cv-preview {
        background: white;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        margin: 0 auto;
        transform: scale(0.7);
        transform-origin: top center;
        width: 794px; /* A4 width in pixels at 96dpi */
        min-height: 1123px; /* A4 height in pixels at 96dpi */
    }

    .cv-preview table {
        width: 100%;
        border-collapse: collapse;
    }

    .cv-left {
        width: 280px;
        background-color: #004a7c;
        color: white;
        padding: 56px 32px;
        vertical-align: top;
        box-sizing: border-box;
    }

    .cv-right {
        padding: 56px 45px;
        vertical-align: top;
        box-sizing: border-box;
    }

    /* Left column */
    .photo-box {
        width: 190px;
        height: 190px;
        margin: 0 auto 38px;
        border: 3px solid white;
        border-radius: 50%;
        background-color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .photo-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .photo-placeholder {
        font-size: 80px;
        color: #004a7c;
        text-align: center;
        line-height: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
    }

    .left-section {
        margin-bottom: 30px;
    }

    .left-section h3 {
        font-size: 11px;
        font-weight: bold;
        text-transform: uppercase;
        color: white;
        border-bottom: 2px solid white;
        padding-bottom: 8px;
        margin-bottom: 12px;
    }

    .left-section p {
        font-size: 10px;
        line-height: 1.3;
        color: white;
        word-wrap: break-word;
        overflow-wrap: break-word;
        word-break: break-word;
        text-align: justify;
        hyphens: auto;
        -webkit-hyphens: auto;
        -moz-hyphens: auto;
    }

    .left-section ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .left-section ul li {
        font-size: 10px;
        line-height: 1.3;
        color: white;
        margin-bottom: 8px;
        word-wrap: break-word;
        overflow-wrap: break-word;
        word-break: break-word;
        hyphens: auto;
        -webkit-hyphens: auto;
        -moz-hyphens: auto;
    }

    /* Right column */
    .cv-name {
        font-size: 28px;
        font-weight: bold;
        color: #1a1a1a;
        margin-bottom: 8px;
        word-wrap: break-word;
        overflow-wrap: break-word;
        word-break: break-word;
        line-height: 1.2;
        hyphens: auto;
        -webkit-hyphens: auto;
        -moz-hyphens: auto;
    }

    .cv-job-title {
        font-size: 13px;
        font-weight: bold;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 20px;
        word-wrap: break-word;
        overflow-wrap: break-word;
        line-height: 1.3;
    }

    .contact-box {
        border-top: 2px solid #e0e0e0;
        padding-top: 12px;
        margin-bottom: 24px;
    }

    .contact-item {
        font-size: 10px;
        color: #333;
        margin-bottom: 6px;
        word-wrap: break-word;
        overflow-wrap: break-word;
        word-break: break-word;
    }

    .contact-icon {
        color: #004a7c;
        font-weight: bold;
        display: inline-block;
        width: 16px;
    }

    .right-section {
        margin-top: 20px;
        padding-top: 16px;
        border-top: 2px solid #e0e0e0;
    }

    .right-section h3 {
        font-size: 12px;
        font-weight: bold;
        text-transform: uppercase;
        color: #1a1a1a;
        margin-bottom: 16px;
    }

    .experience-block,
    .education-block {
        margin-bottom: 16px;
    }

    .exp-date {
        font-size: 9px;
        font-weight: bold;
        color: #666;
        margin-bottom: 4px;
    }

    .exp-company {
        font-size: 11px;
        font-weight: bold;
        color: #004a7c;
        margin-bottom: 4px;
        word-wrap: break-word;
        overflow-wrap: break-word;
    }

    .exp-position {
        font-size: 10px;
        font-style: italic;
        color: #333;
        margin-bottom: 8px;
    }

    .exp-desc {
        font-size: 9px;
        color: #555;
        line-height: 1.3;
        text-align: justify;
    }

    .exp-desc ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .exp-desc ul li {
        margin-bottom: 4px;
        word-wrap: break-word;
        overflow-wrap: break-word;
        word-break: break-word;
        text-align: justify;
        hyphens: auto;
        -webkit-hyphens: auto;
        -moz-hyphens: auto;
    }

    /* Form styles */
    .experience-entry, .education-entry {
        margin-bottom: 15px;
        padding: 10px;
        border: 1px solid #e0e0e0;
        border-radius: 5px;
        background: #f9f9f9;
    }

    .btn-remove-entry {
        background: #ef4444;
        color: white;
        border: none;
        padding: 5px 10px;
        border-radius: 3px;
        cursor: pointer;
        font-size: 11px;
    }

    .btn-add-entry {
        background: #10b981;
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 12px;
        margin-top: 10px;
    }

    @media (max-width: 1200px) {
        .editor-container {
            grid-template-columns: 1fr;
        }
        .preview-section {
            position: relative;
        }
    }
</style>
@endpush

@section('content')
<div class="alert alert-info mb-4">
    <strong>📝 {{ isset($resume) ? 'Modifier' : 'Étape 2/3' }} :</strong> {{ isset($resume) ? 'Modifiez' : 'Créez' }} le CV de <strong>{{ $student->name }}</strong> {{ isset($resume) ? '' : 'avant d\'envoyer le SMS d\'activation' }}.
</div>

<form id="cvForm" action="{{ route('admin.students.store-cv', $student->id) }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="editor-container">
        <!-- Formulaire d'édition -->
        <div class="form-section">
            <h3 class="mb-4">📋 Informations du CV</h3>

            <!-- Photo -->
            <div class="form-group mb-3">
                <label class="form-label">Photo de profil</label>
                @if(isset($resume) && $resume->personal_info && isset($resume->personal_info['photo_path']) && $resume->personal_info['photo_path'])
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $resume->personal_info['photo_path']) }}" alt="Photo actuelle" style="width: 100px; height: 100px; object-fit: cover; border-radius: 50%;">
                        <small class="d-block text-muted">Photo actuelle (charger une nouvelle photo pour la remplacer)</small>
                    </div>
                @endif
                <input type="file" name="photo" id="photoInput" class="form-control" accept="image/*">
                <small class="form-text text-muted">Format recommandé : JPG, PNG (max 2MB)</small>
            </div>

            <!-- Titre professionnel -->
            <div class="form-group mb-3">
                <label class="form-label required">Titre professionnel</label>
                <input type="text" name="title" id="titleInput" class="form-control" value="{{ old('title', $resume->title ?? 'AIDE-SOIGNANTE') }}" required>
            </div>

            <!-- Contact -->
            <h4 class="mt-4 mb-3">📞 Contact</h4>
            <div class="form-group mb-3">
                <label class="form-label">Téléphone</label>
                <input type="text" name="phone" id="phoneInput" class="form-control" value="{{ $student->phone }}">
            </div>

            <div class="form-group mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" id="emailInput" class="form-control" value="{{ $student->email }}">
            </div>

            <div class="form-group mb-3">
                <label class="form-label">Adresse</label>
                <input type="text" name="address" id="addressInput" class="form-control" value="{{ old('address', $resume->personal_info['address'] ?? '') }}" placeholder="Ex: 34 rue La Boétie, 75014 Paris">
            </div>

            <!-- Objectif professionnel -->
            <h4 class="mt-4 mb-3">🎯 Objectif Professionnel</h4>
            <div class="form-group mb-3">
                <textarea name="objective" id="objectiveInput" class="form-control" rows="4" placeholder="Décrivez votre objectif professionnel...">{{ old('objective', $resume->professional_summary ?? '') }}</textarea>
            </div>

            <!-- Expériences -->
            <h4 class="mt-4 mb-3">💼 Expériences Professionnelles</h4>
            <div id="experiencesContainer"></div>
            <button type="button" class="btn-add-entry" onclick="addExperience()">➕ Ajouter une expérience</button>

            <!-- Formation -->
            <h4 class="mt-4 mb-3">🎓 Formation</h4>
            <div id="educationContainer"></div>
            <button type="button" class="btn-add-entry" onclick="addEducation()">➕ Ajouter une formation</button>

            <!-- Compétences -->
            <h4 class="mt-4 mb-3">🛠️ Compétences</h4>
            <div class="form-group mb-3">
                <textarea name="skills" id="skillsInput" class="form-control" rows="5" placeholder="Une compétence par ligne&#10;Ex: Premiers secours et sécurité&#10;Gestion des maladies chroniques">{{ old('skills', isset($resume) && is_array($resume->skills) ? implode("\n", $resume->skills) : '') }}</textarea>
            </div>

            <!-- Centres d'intérêt -->
            <h4 class="mt-4 mb-3">🎨 Centres d'Intérêt</h4>
            <div class="form-group mb-3">
                <textarea name="hobbies" id="hobbiesInput" class="form-control" rows="3" placeholder="Ex: Jardinage, Pratique du Pilates">{{ old('hobbies', isset($resume) && is_array($resume->hobbies) ? implode("\n", $resume->hobbies) : $student->interests) }}</textarea>
            </div>

            <!-- Actions -->
            <div class="mt-4 d-flex gap-2 justify-content-end">
                <a href="{{ isset($resume) ? route('admin.cvtheque.index') : route('admin.students.index') }}" class="btn btn-secondary">Annuler</a>
                <button type="submit" class="btn btn-primary">💾 {{ isset($resume) ? 'Mettre à jour' : 'Sauvegarder' }} le CV {{ isset($resume) ? '' : 'et Continuer' }}</button>
            </div>
        </div>

        <!-- Preview -->
        <div class="preview-section">
            <h3 class="mb-3">👁️ Aperçu en temps réel</h3>
            <div class="cv-preview">
                <table>
                    <tr>
                        <!-- Colonne gauche -->
                        <td class="cv-left">
                            <div class="photo-box">
                                <img id="photoPreview" style="display:none;">
                                <div id="photoPlaceholder" class="photo-placeholder">👤</div>
                            </div>

                            <div class="left-section" id="objectiveSection" style="display:none;">
                                <h3>OBJECTIF PROFESSIONNEL</h3>
                                <p id="objectivePreview"></p>
                            </div>

                            <div class="left-section" id="skillsSection" style="display:none;">
                                <h3>COMPÉTENCES</h3>
                                <ul id="skillsPreview"></ul>
                            </div>

                            <div class="left-section" id="hobbiesSection" style="display:none;">
                                <h3>CENTRES D'INTÉRÊT</h3>
                                <ul id="hobbiesPreview"></ul>
                            </div>
                        </td>

                        <!-- Colonne droite -->
                        <td class="cv-right">
                            <h1 class="cv-name" id="namePreview">{{ $student->name }}</h1>
                            <h2 class="cv-job-title" id="titlePreview">AIDE-SOIGNANTE</h2>

                            <div class="contact-box">
                                <div class="contact-item">
                                    <span class="contact-icon">☎</span>
                                    <span id="phonePreview">{{ $student->phone }}</span>
                                </div>
                                <div class="contact-item">
                                    <span class="contact-icon">✉</span>
                                    <span id="emailPreview">{{ $student->email }}</span>
                                </div>
                                <div class="contact-item" id="addressContainer" style="display:none;">
                                    <span class="contact-icon">⌂</span>
                                    <span id="addressPreview"></span>
                                </div>
                            </div>

                            <div class="right-section" id="experiencesSection" style="display:none;">
                                <h3>EXPÉRIENCES PROFESSIONNELLES</h3>
                                <div id="experiencesPreview"></div>
                            </div>

                            <div class="right-section" id="educationSection" style="display:none;">
                                <h3>FORMATION</h3>
                                <div id="educationPreview"></div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
let experienceCount = 0;
let educationCount = 0;

// Fonction pour initialiser le preview
function initializePreview() {
    console.log('Initializing preview...');

    @if(isset($resume))
        // Mettre à jour le titre
        const titlePreview = document.getElementById('titlePreview');
        if (titlePreview) {
            titlePreview.textContent = {!! json_encode($resume->title ?? 'AIDE-SOIGNANTE') !!};
        }

        // Mettre à jour la photo
        @if($resume->personal_info && isset($resume->personal_info['photo_path']) && $resume->personal_info['photo_path'])
            const photoPreview = document.getElementById('photoPreview');
            const photoPlaceholder = document.getElementById('photoPlaceholder');
            if (photoPreview && photoPlaceholder) {
                photoPreview.src = "{{ asset('storage/' . ($resume->personal_info['photo_path'] ?? '')) }}";
                photoPreview.style.display = 'block';
                photoPlaceholder.style.display = 'none';
            }
        @endif

        // Mettre à jour l'objectif
        @if($resume->professional_summary)
            const objectiveSection = document.getElementById('objectiveSection');
            const objectivePreview = document.getElementById('objectivePreview');
            if (objectiveSection && objectivePreview) {
                objectiveSection.style.display = 'block';
                objectivePreview.textContent = {!! json_encode($resume->professional_summary) !!};
            }
        @endif

        // Mettre à jour les compétences
        @if($resume->skills && count($resume->skills) > 0)
            const skillsSection = document.getElementById('skillsSection');
            const skillsPreview = document.getElementById('skillsPreview');
            if (skillsSection && skillsPreview) {
                skillsSection.style.display = 'block';
                skillsPreview.innerHTML = {!! json_encode(implode('', array_map(fn($s) => "<li>• $s</li>", $resume->skills))) !!};
            }
        @endif

        // Mettre à jour les centres d'intérêt
        @if($resume->hobbies && count($resume->hobbies) > 0)
            const hobbiesSection = document.getElementById('hobbiesSection');
            const hobbiesPreview = document.getElementById('hobbiesPreview');
            if (hobbiesSection && hobbiesPreview) {
                hobbiesSection.style.display = 'block';
                hobbiesPreview.innerHTML = {!! json_encode(implode('', array_map(fn($h) => "<li>• $h</li>", $resume->hobbies))) !!};
            }
        @endif

        // Mettre à jour l'adresse
        @if($resume->personal_info && isset($resume->personal_info['address']) && $resume->personal_info['address'])
            const addressContainer = document.getElementById('addressContainer');
            const addressPreview = document.getElementById('addressPreview');
            if (addressContainer && addressPreview) {
                addressContainer.style.display = 'block';
                addressPreview.textContent = {!! json_encode($resume->personal_info['address']) !!};
            }
        @endif
    @endif

    console.log('Preview initialized');
}

// Photo preview
document.getElementById('photoInput')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(event) {
            document.getElementById('photoPreview').src = event.target.result;
            document.getElementById('photoPreview').style.display = 'block';
            document.getElementById('photoPlaceholder').style.display = 'none';
        };
        reader.readAsDataURL(file);
    }
});

// Title preview
document.getElementById('titleInput')?.addEventListener('input', function(e) {
    document.getElementById('titlePreview').textContent = e.target.value || 'AIDE-SOIGNANTE';
});

// Contact previews
document.getElementById('phoneInput')?.addEventListener('input', function(e) {
    document.getElementById('phonePreview').textContent = e.target.value;
});

document.getElementById('emailInput')?.addEventListener('input', function(e) {
    document.getElementById('emailPreview').textContent = e.target.value;
});

document.getElementById('addressInput')?.addEventListener('input', function(e) {
    const container = document.getElementById('addressContainer');
    if (e.target.value) {
        container.style.display = 'block';
        document.getElementById('addressPreview').textContent = e.target.value;
    } else {
        container.style.display = 'none';
    }
});

// Objective preview
document.getElementById('objectiveInput')?.addEventListener('input', function(e) {
    const section = document.getElementById('objectiveSection');
    const preview = document.getElementById('objectivePreview');
    if (e.target.value) {
        section.style.display = 'block';
        preview.textContent = e.target.value;
    } else {
        section.style.display = 'none';
    }
});

// Skills preview
document.getElementById('skillsInput')?.addEventListener('input', function(e) {
    const section = document.getElementById('skillsSection');
    const preview = document.getElementById('skillsPreview');
    const skills = e.target.value.split('\n').filter(s => s.trim());

    if (skills.length > 0) {
        section.style.display = 'block';
        preview.innerHTML = skills.map(skill => `<li>• ${skill.trim()}</li>`).join('');
    } else {
        section.style.display = 'none';
    }
});

// Hobbies preview
document.getElementById('hobbiesInput')?.addEventListener('input', function(e) {
    const section = document.getElementById('hobbiesSection');
    const preview = document.getElementById('hobbiesPreview');
    const hobbies = e.target.value.split('\n').filter(h => h.trim());

    if (hobbies.length > 0) {
        section.style.display = 'block';
        preview.innerHTML = hobbies.map(hobby => `<li>• ${hobby.trim()}</li>`).join('');
    } else {
        section.style.display = 'none';
    }
});

// Add experience
function addExperience() {
    experienceCount++;
    const container = document.getElementById('experiencesContainer');
    const entry = document.createElement('div');
    entry.className = 'experience-entry';
    entry.id = `experience-${experienceCount}`;
    entry.innerHTML = `
        <div class="d-flex justify-content-between align-items-center mb-2">
            <strong>Expérience #${experienceCount}</strong>
            <button type="button" class="btn-remove-entry" onclick="removeExperience(${experienceCount})">✖ Supprimer</button>
        </div>
        <div class="form-group mb-2">
            <label class="form-label">Dates</label>
            <input type="text" name="experiences[${experienceCount}][date]" class="form-control form-control-sm" placeholder="Ex: 02/2013 - Actuel" onchange="updateExperiencesPreview()">
        </div>
        <div class="form-group mb-2">
            <label class="form-label">Entreprise / Lieu</label>
            <input type="text" name="experiences[${experienceCount}][company]" class="form-control form-control-sm" placeholder="Ex: EHPAD | Paris" onchange="updateExperiencesPreview()">
        </div>
        <div class="form-group mb-2">
            <label class="form-label">Poste</label>
            <input type="text" name="experiences[${experienceCount}][title]" class="form-control form-control-sm" placeholder="Ex: Aide-soignante" onchange="updateExperiencesPreview()">
        </div>
        <div class="form-group mb-2">
            <label class="form-label">Description (une tâche par ligne)</label>
            <textarea name="experiences[${experienceCount}][description]" class="form-control form-control-sm" rows="3" placeholder="• Suivi des progrès et consignation..." onchange="updateExperiencesPreview()"></textarea>
        </div>
    `;
    container.appendChild(entry);
    updateExperiencesPreview();
}

function removeExperience(id) {
    document.getElementById(`experience-${id}`)?.remove();
    updateExperiencesPreview();
}

function updateExperiencesPreview() {
    const preview = document.getElementById('experiencesPreview');
    const section = document.getElementById('experiencesSection');
    const entries = document.querySelectorAll('.experience-entry');

    let html = '';
    entries.forEach((entry) => {
        const date = entry.querySelector('[name*="[date]"]')?.value || '';
        const company = entry.querySelector('[name*="[company]"]')?.value || '';
        const title = entry.querySelector('[name*="[title]"]')?.value || '';
        const description = entry.querySelector('[name*="[description]"]')?.value || '';

        if (date || company || title || description) {
            const tasks = description.split('\n').filter(t => t.trim());
            html += `
                <div class="experience-block">
                    ${date ? `<div class="exp-date">${date}</div>` : ''}
                    ${company ? `<div class="exp-company">${company}</div>` : ''}
                    ${title ? `<div class="exp-position">${title}</div>` : ''}
                    ${tasks.length > 0 ? `<div class="exp-desc"><ul>${tasks.map(t => `<li>• ${t.trim()}</li>`).join('')}</ul></div>` : ''}
                </div>
            `;
        }
    });

    if (html) {
        section.style.display = 'block';
        preview.innerHTML = html;
    } else {
        section.style.display = 'none';
    }
}

// Add education
function addEducation() {
    educationCount++;
    const container = document.getElementById('educationContainer');
    const entry = document.createElement('div');
    entry.className = 'education-entry';
    entry.id = `education-${educationCount}`;
    entry.innerHTML = `
        <div class="d-flex justify-content-between align-items-center mb-2">
            <strong>Formation #${educationCount}</strong>
            <button type="button" class="btn-remove-entry" onclick="removeEducation(${educationCount})">✖ Supprimer</button>
        </div>
        <div class="form-group mb-2">
            <label class="form-label">Établissement</label>
            <input type="text" name="education[${educationCount}][school]" class="form-control form-control-sm" placeholder="Ex: CHU de Rouen" onchange="updateEducationPreview()">
        </div>
        <div class="form-group mb-2">
            <label class="form-label">Diplôme</label>
            <input type="text" name="education[${educationCount}][degree]" class="form-control form-control-sm" placeholder="Ex: Diplôme d'État d'Aide-Soignant (DEAS)" onchange="updateEducationPreview()">
        </div>
    `;
    container.appendChild(entry);
    updateEducationPreview();
}

function removeEducation(id) {
    document.getElementById(`education-${id}`)?.remove();
    updateEducationPreview();
}

function updateEducationPreview() {
    const preview = document.getElementById('educationPreview');
    const section = document.getElementById('educationSection');
    const entries = document.querySelectorAll('.education-entry');

    let html = '';
    entries.forEach((entry) => {
        const school = entry.querySelector('[name*="[school]"]')?.value || '';
        const degree = entry.querySelector('[name*="[degree]"]')?.value || '';

        if (school || degree) {
            html += `
                <div class="education-block">
                    ${school ? `<div class="exp-company">${school}</div>` : ''}
                    ${degree ? `<div class="exp-position">${degree}</div>` : ''}
                </div>
            `;
        }
    });

    if (html) {
        section.style.display = 'block';
        preview.innerHTML = html;
    } else {
        section.style.display = 'none';
    }
}

// Initialize with existing data or default
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing CV data...');

    @if(isset($resume) && $resume->experiences && count($resume->experiences) > 0)
        // Pré-remplir les expériences existantes
        @foreach($resume->experiences as $exp)
            (function() {
                addExperience();
                const currentExpCount = experienceCount;
                @if(!empty($exp['date']))
                document.querySelector(`#experience-${currentExpCount} [name*="[date]"]`).value = {!! json_encode($exp['date']) !!};
                @endif
                @if(!empty($exp['company']))
                document.querySelector(`#experience-${currentExpCount} [name*="[company]"]`).value = {!! json_encode($exp['company']) !!};
                @endif
                @if(!empty($exp['title']))
                document.querySelector(`#experience-${currentExpCount} [name*="[title]"]`).value = {!! json_encode($exp['title']) !!};
                @endif
                @if(!empty($exp['description']) && is_array($exp['description']))
                document.querySelector(`#experience-${currentExpCount} [name*="[description]"]`).value = {!! json_encode(implode("\n", $exp['description'])) !!};
                @endif
            })();
        @endforeach
    @else
        // Ajouter une expérience vide par défaut
        addExperience();
    @endif

    @if(isset($resume) && $resume->education && count($resume->education) > 0)
        // Pré-remplir les formations existantes
        @foreach($resume->education as $edu)
            (function() {
                addEducation();
                const currentEduCount = educationCount;
                @if(!empty($edu['school']))
                document.querySelector(`#education-${currentEduCount} [name*="[school]"]`).value = {!! json_encode($edu['school']) !!};
                @endif
                @if(!empty($edu['degree']))
                document.querySelector(`#education-${currentEduCount} [name*="[degree]"]`).value = {!! json_encode($edu['degree']) !!};
                @endif
            })();
        @endforeach
    @else
        // Ajouter une formation vide par défaut
        addEducation();
    @endif

    // Initialiser le preview avec les données existantes
    setTimeout(function() {
        console.log('Updating preview...');

        // Initialiser le preview
        initializePreview();

        // Mettre à jour les expériences et formations
        updateExperiencesPreview();
        updateEducationPreview();

        console.log('Preview update complete');
    }, 300);
});
</script>
@endpush
