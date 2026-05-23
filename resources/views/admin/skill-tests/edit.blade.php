@extends('admin.layouts.app')

@section('title', 'Éditer Test de Compétences')
@section('page-title', 'Éditer le Test')

@section('breadcrumbs')
    <span> / </span>
    <a href="{{ route('admin.skill-tests.index') }}" style="color: inherit; text-decoration: none;">Tests de compétences</a>
    <span> / </span>
    <a href="{{ route('admin.skill-tests.show', $test) }}" style="color: inherit; text-decoration: none;">{{ $test->title }}</a>
    <span> / </span>
    <span>Éditer</span>
@endsection

@section('header-actions')
    <a href="{{ route('admin.skill-tests.show', $test) }}" class="btn btn-secondary">Retour</a>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Modifier « {{ $test->title }} »</h3>
    </div>

    <form method="POST" action="{{ route('admin.skill-tests.update', $test) }}">
        @csrf
        @method('PUT')

        <fieldset style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 1.5rem; margin-bottom: 1.5rem;">
            <legend style="padding: 0 0.75rem; font-weight: 600;">Rattachement</legend>
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem;">
                <div class="form-group">
                    <label class="form-label">Entreprise *</label>
                    <select name="company_id" id="company_id" class="form-control" required>
                        @foreach($companies as $c)
                            <option value="{{ $c->id }}" {{ old('company_id', $test->company_id) == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Offre liée</label>
                    <select name="job_id" id="job_id" class="form-control">
                        <option value="">— Aucune —</option>
                        @foreach($jobs as $job)
                            <option value="{{ $job->id }}" data-company-id="{{ $job->company_id }}"
                                    {{ old('job_id', $test->job_id) == $job->id ? 'selected' : '' }}>
                                {{ $job->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </fieldset>

        <fieldset style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 1.5rem; margin-bottom: 1.5rem;">
            <legend style="padding: 0 0.75rem; font-weight: 600;">Configuration</legend>

            <div class="form-group">
                <label class="form-label">Titre *</label>
                <input type="text" name="title" class="form-control" value="{{ old('title', $test->title) }}" required>
                @error('title')<small style="color: var(--danger);">{{ $message }}</small>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description', $test->description) }}</textarea>
            </div>

            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem;">
                <div class="form-group">
                    <label class="form-label">Score de réussite (%) *</label>
                    <input type="number" name="passing_score" class="form-control"
                           value="{{ old('passing_score', $test->passing_score) }}" min="0" max="100" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Durée (min)</label>
                    <input type="number" name="duration_minutes" class="form-control"
                           value="{{ old('duration_minutes', $test->duration_minutes) }}" min="5" max="180">
                </div>
            </div>

            <div class="form-group" style="margin-top: 1rem;">
                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $test->is_active) ? 'checked' : '' }}>
                    <strong>Actif</strong>
                </label>
            </div>
        </fieldset>

        <fieldset style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 1.5rem; margin-bottom: 1.5rem;">
            <legend style="padding: 0 0.75rem; font-weight: 600;">Questions</legend>
            <div id="questionsContainer"></div>
            <button type="button" class="btn btn-secondary" onclick="addQuestion()" style="margin-top: 0.75rem;">+ Ajouter une question</button>
        </fieldset>

        <div style="display: flex; gap: 1rem; padding-top: 1.5rem; border-top: 2px solid var(--light);">
            <button type="submit" class="btn btn-primary">Enregistrer</button>
            <a href="{{ route('admin.skill-tests.show', $test) }}" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>

<script>
const existingQuestions = @json(old('questions', $test->questions ?? []));
let qIndex = 0;

function addQuestion(prefill = null) {
    const i = qIndex++;
    const container = document.getElementById('questionsContainer');
    const block = document.createElement('div');
    block.className = 'question-block';
    block.style.cssText = 'border: 1px solid #e5e7eb; border-radius: 8px; padding: 1rem; margin-bottom: 1rem; background: #fafafa;';

    const initialOptions = prefill?.options && prefill.options.length >= 2 ? prefill.options : ['', '', ''];
    const initialQuestion = prefill?.question ?? '';
    const initialCorrect = prefill?.correct_answer ?? '';

    block.innerHTML = `
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
            <strong>Question <span class="q-num">${i + 1}</span></strong>
            <button type="button" class="btn btn-sm btn-danger" onclick="removeQuestion(this)">✕</button>
        </div>
        <div class="form-group">
            <label class="form-label">Énoncé *</label>
            <textarea name="questions[${i}][question]" class="form-control" rows="2" required>${escapeHtml(initialQuestion)}</textarea>
        </div>
        <div class="form-group">
            <label class="form-label">Options & bonne réponse *</label>
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
            <input type="radio" name="correct_marker_${qi}" ${isCorrect ? 'checked' : ''} class="correct-radio">
            <input type="text" name="questions[${qi}][options][]" class="form-control option-input" value="${escapeHtml(value)}" required>
            <button type="button" class="btn btn-sm btn-danger" onclick="removeOption(this)">✕</button>
        </div>
    `;
}

function addOption(btn) {
    const list = btn.previousElementSibling;
    const qi = list.dataset.qIndex;
    const oi = list.querySelectorAll('.option-row').length;
    if (oi >= 5) { alert('5 options max.'); return; }
    const div = document.createElement('div');
    div.innerHTML = optionRowHtml(qi, oi, '', false);
    list.appendChild(div.firstElementChild);
    rebindOptionEvents(list.closest('.question-block'));
}

function removeOption(btn) {
    const row = btn.closest('.option-row');
    const list = row.parentElement;
    if (list.querySelectorAll('.option-row').length <= 2) { alert('Min 2 options.'); return; }
    const wasChecked = row.querySelector('.correct-radio').checked;
    row.remove();
    if (wasChecked) {
        list.closest('.question-block').querySelector('.correct-answer-input').value = '';
    }
}

function removeQuestion(btn) {
    if (document.querySelectorAll('.question-block').length <= 1) { alert('Min 1 question.'); return; }
    btn.closest('.question-block').remove();
    renumberQuestions();
}

function renumberQuestions() {
    document.querySelectorAll('.question-block').forEach((b, idx) => b.querySelector('.q-num').textContent = idx + 1);
}

function rebindOptionEvents(block) {
    block.querySelectorAll('.correct-radio').forEach(radio => {
        radio.onchange = () => {
            const row = radio.closest('.option-row');
            block.querySelector('.correct-answer-input').value = row.querySelector('.option-input').value;
        };
    });
    block.querySelectorAll('.option-input').forEach(input => {
        input.oninput = () => {
            const row = input.closest('.option-row');
            if (row.querySelector('.correct-radio').checked) {
                block.querySelector('.correct-answer-input').value = input.value;
            }
        };
    });
}

function escapeHtml(str) {
    return String(str ?? '').replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]));
}

if (Array.isArray(existingQuestions) && existingQuestions.length > 0) {
    existingQuestions.forEach(q => addQuestion(q));
} else {
    addQuestion();
}

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
