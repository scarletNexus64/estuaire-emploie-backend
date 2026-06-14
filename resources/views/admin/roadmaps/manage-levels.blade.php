@extends('admin.layouts.app')

@section('title', 'Gérer les Niveaux')
@section('page-title', 'Gérer les Niveaux de la Roadmap')

@section('breadcrumbs')
    <span> / </span>
    <a href="{{ route('admin.roadmaps.index') }}">Roadmaps</a>
    <span> / </span>
    <a href="{{ route('admin.roadmaps.show', $roadmap) }}">{{ $roadmap->title }}</a>
    <span> / </span>
    <span>Niveaux</span>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-7">
        <!-- Roadmap Info -->
        <div class="card mb-3" style="background: linear-gradient(135deg, {{ $roadmap->color }} 0%, #764ba2 100%); color: white;">
            <div style="padding: 1.5rem; display: flex; align-items: center; gap: 1rem;">
                <div style="font-size: 3rem;">{{ $roadmap->icon }}</div>
                <div>
                    <h5 class="mb-1" style="color: white;">{{ $roadmap->title }}</h5>
                    <p class="mb-0" style="opacity: 0.9;">{{ $roadmap->levels->count() }} niveau(x) · Seuil QCM {{ $roadmap->pass_threshold }}%</p>
                </div>
            </div>
        </div>

        <!-- Existing Levels -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">🎯 Niveaux Existants</h5>
            </div>
            <div style="padding: 1.5rem;">
                @forelse($roadmap->levels as $level)
                <div class="card mb-3" style="border-left: 4px solid {{ $roadmap->color }};" id="level-{{ $level->id }}">
                    <div style="padding: 1rem;">
                        <div style="display: flex; justify-content: space-between; align-items: start;">
                            <div style="flex: 1;">
                                <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.5rem;">
                                    <span class="badge badge-primary">Niveau {{ $level->order }}</span>
                                    <h6 style="margin: 0; font-weight: 700;">{{ $level->title }}</h6>
                                    @if($level->has_quiz)
                                        <span class="badge badge-warning">QCM ({{ $level->questions->count() }} Q.)</span>
                                    @else
                                        <span class="badge badge-secondary">Sans QCM</span>
                                    @endif
                                    <span class="badge badge-info">+{{ $level->xp_reward }} XP</span>
                                </div>
                                @if($level->subtitle)
                                    <p class="text-muted mb-2">{{ $level->subtitle }}</p>
                                @endif

                                <button class="btn btn-sm btn-outline-secondary" onclick="toggleContent('content-{{ $level->id }}')">
                                    Voir le contenu
                                </button>
                                <div id="content-{{ $level->id }}" style="display: none; margin-top: 0.75rem; padding: 1rem; background: var(--light); border-radius: 8px;">
                                    <div style="white-space: pre-line;">{{ $level->content }}</div>
                                    @if($level->questions->count() > 0)
                                        <hr>
                                        <strong class="small">QCM :</strong>
                                        <ol class="small" style="margin: 0.5rem 0 0 1.25rem; padding: 0;">
                                            @foreach($level->questions as $q)
                                                <li style="margin-bottom: 0.25rem;">{{ $q->question }}</li>
                                            @endforeach
                                        </ol>
                                    @endif
                                </div>
                            </div>
                            <div style="display: flex; gap: 0.5rem;">
                                <button class="btn btn-sm btn-secondary" onclick="editLevel({{ $level->id }})" title="Modifier">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                <form method="POST" action="{{ route('admin.roadmaps.destroy-level', [$roadmap, $level]) }}" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Supprimer" onclick="return confirm('Supprimer ce niveau ?')">
                                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="alert alert-info">
                    Aucun niveau pour le moment. Utilisez le formulaire à droite pour ajouter le premier niveau.
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <!-- Add/Edit Level Form -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">➕ Ajouter un Niveau</h5>
            </div>
            <div style="padding: 1.5rem;">
                <form method="POST" action="{{ route('admin.roadmaps.store-level', $roadmap) }}" id="levelForm">
                    @csrf
                    <input type="hidden" name="_method" id="form_method" value="POST">

                    <div class="form-group">
                        <label for="title" class="form-label">Titre du niveau *</label>
                        <input type="text" class="form-control" id="title" name="title" placeholder="Ex: Les bases du SQL" required>
                    </div>

                    <div class="form-group">
                        <label for="subtitle" class="form-label">Sous-titre</label>
                        <input type="text" class="form-control" id="subtitle" name="subtitle" placeholder="Court résumé (optionnel)">
                    </div>

                    <div class="form-group">
                        <label for="content" class="form-label">Contenu (tips rédigés) *</label>
                        <textarea class="form-control" id="content" name="content" rows="8"
                                  placeholder="Rédigez les tips de ce niveau. Markdown léger supporté (🎯, ✅, 💡, **gras**)..." required></textarea>
                        <small class="text-muted">Pas de lien vidéo : du texte pédagogique directement.</small>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="order" class="form-label">Niveau N°</label>
                                <input type="number" class="form-control" id="order" name="order"
                                       value="{{ $roadmap->levels->max('order') + 1 }}" min="1">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="xp_reward" class="form-label">XP gagnés</label>
                                <input type="number" class="form-control" id="xp_reward" name="xp_reward" value="100" min="0">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="has_quiz" name="has_quiz" value="1" checked onchange="toggleQuizSection()">
                            <label class="form-check-label" for="has_quiz">Ce niveau a un QCM de validation</label>
                        </div>
                        <small class="text-muted">Réussir le QCM (≥ {{ $roadmap->pass_threshold }}%) débloque le niveau suivant.</small>
                    </div>

                    <div id="quiz-section">
                        <label class="form-label">Questions du QCM</label>
                        <div id="questions-container"></div>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="addQuestion()">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Ajouter une question
                        </button>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3" style="width: 100%;">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span id="submitBtnText">Ajouter le Niveau</span>
                    </button>

                    <button type="button" class="btn btn-outline-secondary mt-2" style="width: 100%; display: none;" id="cancelEditBtn" onclick="resetForm()">
                        Annuler la modification
                    </button>

                    <a href="{{ route('admin.roadmaps.show', $roadmap) }}" class="btn btn-outline-secondary mt-2" style="width: 100%;">
                        Retour à la Roadmap
                    </a>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let questionIndex = 0;

function toggleContent(id) {
    const el = document.getElementById(id);
    el.style.display = el.style.display === 'none' ? 'block' : 'none';
}

function toggleQuizSection() {
    document.getElementById('quiz-section').style.display =
        document.getElementById('has_quiz').checked ? 'block' : 'none';
}

// Construit le HTML d'une question avec ses options.
function addQuestion(data = null) {
    const qi = questionIndex++;
    const container = document.getElementById('questions-container');
    const wrapper = document.createElement('div');
    wrapper.className = 'card mb-2';
    wrapper.id = `question-${qi}`;
    wrapper.style.padding = '0.75rem';
    wrapper.dataset.qindex = qi;
    wrapper.innerHTML = `
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.5rem;">
            <strong class="small">Question</strong>
            <button type="button" class="btn btn-sm btn-danger" onclick="document.getElementById('question-${qi}').remove()">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <textarea class="form-control mb-2" name="questions[${qi}][question]" rows="2" placeholder="Énoncé de la question" required></textarea>
        <div class="options-container" data-qindex="${qi}"></div>
        <button type="button" class="btn btn-sm btn-outline-secondary mb-2" onclick="addOption(${qi})">+ Option</button>
        <input type="text" class="form-control" name="questions[${qi}][explanation]" placeholder="Explication (optionnelle, affichée après réponse)">
    `;
    container.appendChild(wrapper);

    if (data) {
        wrapper.querySelector('textarea').value = data.question || '';
        const opts = data.options || [];
        const correct = (data.correct_answers || []).map(Number);
        opts.forEach((opt, idx) => addOption(qi, opt, correct.includes(idx)));
        const expl = wrapper.querySelector('input[name$="[explanation]"]');
        if (expl) expl.value = data.explanation || '';
    } else {
        // Deux options par défaut.
        addOption(qi);
        addOption(qi);
    }
}

// Ajoute une option à une question. La case "bonne réponse" est une checkbox
// (supporte le multi-réponses). La value de la checkbox = index de l'option.
function addOption(qi, value = '', checked = false) {
    const optsContainer = document.querySelector(`.options-container[data-qindex="${qi}"]`);
    const optIdx = optsContainer.children.length;
    const row = document.createElement('div');
    row.style = 'display:flex; gap:0.5rem; align-items:center; margin-bottom:0.4rem;';
    row.innerHTML = `
        <input type="checkbox" name="questions[${qi}][correct_answers][]" value="${optIdx}" ${checked ? 'checked' : ''} title="Bonne réponse">
        <input type="text" class="form-control" name="questions[${qi}][options][]" placeholder="Choix de réponse" value="${value.replace(/"/g, '&quot;')}">
        <button type="button" class="btn btn-sm btn-danger" onclick="removeOption(this, ${qi})">
            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    `;
    optsContainer.appendChild(row);
}

// Supprime une option et réindexe les checkbox (value = position).
function removeOption(btn, qi) {
    btn.closest('div').remove();
    reindexOptions(qi);
}

function reindexOptions(qi) {
    const optsContainer = document.querySelector(`.options-container[data-qindex="${qi}"]`);
    [...optsContainer.children].forEach((row, idx) => {
        row.querySelector('input[type="checkbox"]').value = idx;
    });
}

function resetForm() {
    const form = document.getElementById('levelForm');
    form.reset();
    form.action = "{{ route('admin.roadmaps.store-level', $roadmap) }}";
    document.getElementById('form_method').value = "POST";
    document.getElementById('submitBtnText').textContent = "Ajouter le Niveau";
    document.querySelector('.col-lg-5 .card-header h5').textContent = "➕ Ajouter un Niveau";
    document.getElementById('questions-container').innerHTML = '';
    document.getElementById('cancelEditBtn').style.display = 'none';
    questionIndex = 0;
    toggleQuizSection();
}

async function editLevel(levelId) {
    try {
        const level = await new Promise((resolve, reject) => {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', `/admin/roadmaps/{{ $roadmap->id }}/levels/${levelId}`);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.setRequestHeader('Accept', 'application/json');
            xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}');
            xhr.withCredentials = true;
            xhr.onload = () => (xhr.status >= 200 && xhr.status < 300)
                ? resolve(JSON.parse(xhr.responseText))
                : reject(new Error(`Erreur ${xhr.status}`));
            xhr.onerror = () => reject(new Error('Erreur réseau'));
            xhr.send();
        });

        const form = document.getElementById('levelForm');
        form.action = `/admin/roadmaps/{{ $roadmap->id }}/levels/${levelId}`;
        document.getElementById('form_method').value = "PUT";

        document.getElementById('title').value = level.title || '';
        document.getElementById('subtitle').value = level.subtitle || '';
        document.getElementById('content').value = level.content || '';
        document.getElementById('order').value = level.order || 1;
        document.getElementById('xp_reward').value = level.xp_reward ?? 100;
        document.getElementById('has_quiz').checked = !!level.has_quiz;
        toggleQuizSection();

        document.getElementById('questions-container').innerHTML = '';
        questionIndex = 0;
        (level.questions || []).forEach(q => addQuestion(q));

        document.getElementById('submitBtnText').textContent = "Modifier le Niveau";
        document.querySelector('.col-lg-5 .card-header h5').textContent = "✏️ Modifier le Niveau";
        document.getElementById('cancelEditBtn').style.display = 'block';
        document.querySelector('.col-lg-5').scrollIntoView({ behavior: 'smooth' });
    } catch (e) {
        console.error(e);
        alert('Erreur lors du chargement du niveau.');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    toggleQuizSection();
    if (document.getElementById('questions-container').children.length === 0) {
        addQuestion();
    }
});
</script>
@endpush
@endsection
