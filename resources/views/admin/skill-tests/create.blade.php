@extends('admin.layouts.app')

@section('title', 'Nouveau Test de Compétences')
@section('page-title', 'Créer un Test de Compétences')

@section('breadcrumbs')
    <span> / </span>
    <a href="{{ route('admin.skill-tests.index') }}" style="color: inherit; text-decoration: none;">Tests de compétences</a>
    <span> / </span>
    <span>Créer</span>
@endsection

@section('header-actions')
    <a href="{{ route('admin.skill-tests.index') }}" class="btn btn-secondary">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Retour
    </a>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            @if($prefilledJob)
                Test de compétences pour « {{ $prefilledJob->title }} »
            @else
                Nouveau test de compétences
            @endif
        </h3>
    </div>

    <form method="POST" action="{{ route('admin.skill-tests.store') }}">
        @csrf

        {{-- Rattachement --}}
        <fieldset style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 1.5rem; margin-bottom: 1.5rem;">
            <legend style="padding: 0 0.75rem; font-weight: 600;">Rattachement</legend>

            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem;">
                <div class="form-group">
                    <label class="form-label">Entreprise *</label>
                    <select name="company_id" id="company_id" class="form-control" required>
                        <option value="">— Sélectionner —</option>
                        @foreach($companies as $c)
                            <option value="{{ $c->id }}" {{ old('company_id', $prefilledCompanyId) == $c->id ? 'selected' : '' }}>
                                {{ $c->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('company_id')<small style="color: var(--danger);">{{ $message }}</small>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Offre liée (optionnel)</label>
                    <select name="job_id" id="job_id" class="form-control">
                        <option value="">— Aucune offre —</option>
                        @foreach($jobs as $job)
                            <option value="{{ $job->id }}"
                                    data-company-id="{{ $job->company_id }}"
                                    {{ old('job_id', $prefilledJob?->id) == $job->id ? 'selected' : '' }}>
                                {{ $job->title }} ({{ $job->company?->name }})
                            </option>
                        @endforeach
                    </select>
                    @error('job_id')<small style="color: var(--danger);">{{ $message }}</small>@enderror
                </div>
            </div>
        </fieldset>

        {{-- Infos générales --}}
        <fieldset style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 1.5rem; margin-bottom: 1.5rem;">
            <legend style="padding: 0 0.75rem; font-weight: 600;">Configuration du test</legend>

            <div class="form-group">
                <label class="form-label">Titre du test *</label>
                <input type="text" name="title" class="form-control"
                       value="{{ old('title', $prefilledJob ? 'Test de compétences — ' . $prefilledJob->title : '') }}"
                       required>
                @error('title')<small style="color: var(--danger);">{{ $message }}</small>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                @error('description')<small style="color: var(--danger);">{{ $message }}</small>@enderror
            </div>

            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem;">
                <div class="form-group">
                    <label class="form-label">Score de réussite (%) *</label>
                    <input type="number" name="passing_score" class="form-control"
                           value="{{ old('passing_score', 70) }}" min="0" max="100" required>
                    <small style="color: #6b7280;">Score minimum pour réussir le test (0-100)</small>
                    @error('passing_score')<small style="color: var(--danger); display: block;">{{ $message }}</small>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Durée maximale (minutes)</label>
                    <input type="number" name="duration_minutes" class="form-control"
                           value="{{ old('duration_minutes') }}" min="5" max="180" placeholder="Illimitée si vide">
                    <small style="color: #6b7280;">Entre 5 et 180 minutes</small>
                    @error('duration_minutes')<small style="color: var(--danger); display: block;">{{ $message }}</small>@enderror
                </div>
            </div>

            <div class="form-group" style="margin-top: 1rem;">
                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }}>
                    <strong>Publier directement</strong>
                    <small style="color: #6b7280;">(l'admin bypass le paiement de 2000 FCFA)</small>
                </label>
            </div>
        </fieldset>

        {{-- Questions --}}
        <fieldset style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 1.5rem; margin-bottom: 1.5rem;">
            <legend style="padding: 0 0.75rem; font-weight: 600;">Questions (QCM)</legend>
            <p style="color: #6b7280; font-size: 0.875rem; margin-bottom: 1rem;">
                Ajoutez au moins une question avec 2 à 5 options. La bonne réponse doit faire partie des options.
            </p>

            <div id="questionsContainer">
                {{-- Les questions sont injectées en JS, avec le retour de old() en cas d'erreur de validation --}}
            </div>

            <button type="button" class="btn btn-secondary" onclick="addQuestion()" style="margin-top: 0.75rem;">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Ajouter une question
            </button>
        </fieldset>

        <div style="display: flex; gap: 1rem; padding-top: 1.5rem; border-top: 2px solid var(--light);">
            <button type="submit" class="btn btn-primary">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                Enregistrer le test
            </button>
            <a href="{{ route('admin.skill-tests.index') }}" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>

<script>
const oldQuestions = @json(old('questions', []));
let qIndex = 0;

function addQuestion(prefill = null) {
    const i = qIndex++;
    const container = document.getElementById('questionsContainer');
    const block = document.createElement('div');
    block.className = 'question-block';
    block.style.cssText = 'border: 1px solid #e5e7eb; border-radius: 8px; padding: 1rem; margin-bottom: 1rem; background: #fafafa; position: relative;';

    const initialOptions = prefill?.options && prefill.options.length >= 2
        ? prefill.options
        : ['', '', ''];
    const initialQuestion = prefill?.question ?? '';
    const initialCorrect = prefill?.correct_answer ?? '';

    block.innerHTML = `
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
            <strong>Question <span class="q-num">${i + 1}</span></strong>
            <button type="button" class="btn btn-sm btn-danger" onclick="removeQuestion(this)" title="Supprimer">
                ✕
            </button>
        </div>
        <div class="form-group">
            <label class="form-label">Énoncé *</label>
            <textarea name="questions[${i}][question]" class="form-control" rows="2" required>${escapeHtml(initialQuestion)}</textarea>
        </div>
        <div class="form-group">
            <label class="form-label">Options & bonne réponse * (cochez la radio à côté de la bonne réponse)</label>
            <div class="options-list" data-q-index="${i}">
                ${initialOptions.map((opt, oi) => optionRowHtml(i, oi, opt, opt === initialCorrect)).join('')}
            </div>
            <button type="button" class="btn btn-sm btn-secondary" onclick="addOption(this)" style="margin-top: 0.5rem;">+ Option</button>
            <input type="hidden" name="questions[${i}][correct_answer]" class="correct-answer-input" value="${escapeHtml(initialCorrect)}">
        </div>
    `;
    container.appendChild(block);
    rebindOptionEvents(block);
    renumberQuestions();
}

function optionRowHtml(qi, oi, value, isCorrect) {
    return `
        <div class="option-row" style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
            <input type="radio" name="correct_marker_${qi}" ${isCorrect ? 'checked' : ''}
                   class="correct-radio" style="flex-shrink: 0;">
            <input type="text" name="questions[${qi}][options][]" class="form-control option-input"
                   value="${escapeHtml(value)}" placeholder="Option ${oi + 1}" required>
            <button type="button" class="btn btn-sm btn-danger remove-option" onclick="removeOption(this)" title="Supprimer">✕</button>
        </div>
    `;
}

function addOption(btn) {
    const list = btn.previousElementSibling;
    const qi = list.dataset.qIndex;
    const oi = list.querySelectorAll('.option-row').length;
    if (oi >= 5) { alert('5 options maximum par question.'); return; }
    const div = document.createElement('div');
    div.innerHTML = optionRowHtml(qi, oi, '', false);
    list.appendChild(div.firstElementChild);
    rebindOptionEvents(list.closest('.question-block'));
}

function removeOption(btn) {
    const row = btn.closest('.option-row');
    const list = row.parentElement;
    if (list.querySelectorAll('.option-row').length <= 2) {
        alert('Au moins 2 options sont requises.');
        return;
    }
    const wasChecked = row.querySelector('.correct-radio').checked;
    row.remove();
    if (wasChecked) {
        // Sync hidden correct_answer si la bonne réponse a été supprimée
        const block = list.closest('.question-block');
        block.querySelector('.correct-answer-input').value = '';
    }
}

function removeQuestion(btn) {
    const blocks = document.querySelectorAll('.question-block');
    if (blocks.length <= 1) {
        alert('Au moins une question est requise.');
        return;
    }
    btn.closest('.question-block').remove();
    renumberQuestions();
}

function renumberQuestions() {
    document.querySelectorAll('.question-block').forEach((b, idx) => {
        b.querySelector('.q-num').textContent = idx + 1;
    });
}

function rebindOptionEvents(block) {
    block.querySelectorAll('.correct-radio').forEach(radio => {
        radio.onchange = () => {
            const row = radio.closest('.option-row');
            const value = row.querySelector('.option-input').value;
            block.querySelector('.correct-answer-input').value = value;
        };
    });
    block.querySelectorAll('.option-input').forEach(input => {
        input.oninput = () => {
            const row = input.closest('.option-row');
            const radio = row.querySelector('.correct-radio');
            if (radio.checked) {
                block.querySelector('.correct-answer-input').value = input.value;
            }
        };
    });
}

function escapeHtml(str) {
    return String(str ?? '').replace(/[&<>"']/g, m => ({
        '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
    }[m]));
}

// Init : restaurer old() ou créer 1 question vide
if (Array.isArray(oldQuestions) && oldQuestions.length > 0) {
    oldQuestions.forEach(q => addQuestion(q));
} else {
    addQuestion();
}

// Filter jobs par entreprise sélectionnée
const companySel = document.getElementById('company_id');
const jobSel = document.getElementById('job_id');
const allJobOptions = Array.from(jobSel.options);
function filterJobs() {
    const cid = companySel.value;
    jobSel.innerHTML = '';
    allJobOptions.forEach(opt => {
        if (!opt.value || !cid || opt.dataset.companyId === cid) {
            jobSel.appendChild(opt.cloneNode(true));
        }
    });
}
companySel.addEventListener('change', filterJobs);
filterJobs();
</script>
@endsection
