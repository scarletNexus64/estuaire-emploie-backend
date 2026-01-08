@extends('admin.layouts.app')

@section('title', 'Envoi de notifications - ' . $job->title)

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-bell"></i> Publication et envoi de notifications
                    </h4>
                </div>

                <div class="card-body">
                    <!-- Info du job -->
                    <div class="alert alert-info mb-4">
                        <h5 class="alert-heading">
                            <i class="fas fa-briefcase"></i> {{ $job->title }}
                        </h5>
                        <p class="mb-0">
                            <strong>Entreprise:</strong> {{ $job->company->name }}<br>
                            <strong>Localisation:</strong> {{ $job->location->name }}
                        </p>
                    </div>

                    <!-- Status de l'envoi -->
                    <div id="sending-status" class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="mb-0">Envoi des notifications push aux candidats</h5>
                            <span id="status-text" class="badge badge-info">
                                <i class="fas fa-spinner fa-spin"></i> Préparation...
                            </span>
                        </div>

                        <!-- Progress Bar -->
                        <div class="progress" style="height: 30px;">
                            <div id="progress-bar" class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                                 role="progressbar"
                                 style="width: 0%"
                                 aria-valuenow="0"
                                 aria-valuemin="0"
                                 aria-valuemax="100">
                                <span id="progress-text">0%</span>
                            </div>
                        </div>

                        <!-- Stats -->
                        <div class="row mt-3">
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <h6 class="text-muted">Total utilisateurs</h6>
                                        <h3 id="total-count" class="text-primary">{{ $totalUsers }}</h3>
                                        <small class="text-muted">Candidats & Recruteurs (sauf auteur)</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <h6 class="text-muted">Envoyées</h6>
                                        <h3 id="sent-count" class="text-success">0</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <h6 class="text-muted">Échecs</h6>
                                        <h3 id="failed-count" class="text-danger">0</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Messages d'erreur -->
                    <div id="error-messages" class="alert alert-danger d-none">
                        <h6>Erreurs rencontrées:</h6>
                        <ul id="error-list"></ul>
                    </div>

                    <!-- Actions -->
                    <div id="completion-actions" class="d-none mt-4">
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i>
                            <strong>Terminé!</strong> Les notifications ont été envoyées avec succès.
                        </div>
                        <a href="{{ route('admin.jobs.index') }}" class="btn btn-primary">
                            <i class="fas fa-arrow-left"></i> Retour à la liste des offres
                        </a>
                        <a href="{{ route('admin.jobs.show', $job) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-eye"></i> Voir l'offre
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentBatch = 0;
    const batchSize = 50;
    const jobId = {{ $job->id }};
    let totalSent = 0;
    let totalFailed = 0;
    let totalCandidates = {{ $totalUsers }};

    function updateProgress(sent, failed, total) {
        const percentage = Math.min(100, Math.round(((sent + failed) / total) * 100));
        const progressBar = document.getElementById('progress-bar');
        const progressText = document.getElementById('progress-text');
        const sentCount = document.getElementById('sent-count');
        const failedCount = document.getElementById('failed-count');

        progressBar.style.width = percentage + '%';
        progressBar.setAttribute('aria-valuenow', percentage);
        progressText.textContent = percentage + '%';
        sentCount.textContent = sent;
        failedCount.textContent = failed;
    }

    function updateStatus(message, type = 'info') {
        const iconMap = {
            'info': 'fa-spinner fa-spin',
            'success': 'fa-check-circle',
            'error': 'fa-exclamation-triangle'
        };

        const badgeMap = {
            'info': 'badge-info',
            'success': 'badge-success',
            'error': 'badge-danger'
        };

        const statusText = document.getElementById('status-text');
        statusText.className = 'badge ' + badgeMap[type];
        statusText.innerHTML = `<i class="fas ${iconMap[type]}"></i> ${message}`;
    }

    function sendBatch() {
        updateStatus(`Envoi du lot ${currentBatch + 1}...`, 'info');

        fetch('{{ route("admin.jobs.send-notifications-batch", $job) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                batch: currentBatch,
                batch_size: batchSize
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                totalSent += data.sent;
                totalFailed += data.failed;

                updateProgress(totalSent, totalFailed, totalCandidates);

                // Afficher les erreurs s'il y en a
                if (data.errors && data.errors.length > 0) {
                    const errorMessages = document.getElementById('error-messages');
                    const errorList = document.getElementById('error-list');
                    errorMessages.classList.remove('d-none');
                    data.errors.forEach(error => {
                        const li = document.createElement('li');
                        li.textContent = `${error.user_name} (${error.user_id}): ${error.error}`;
                        errorList.appendChild(li);
                    });
                }

                // Si terminé
                if (data.completed) {
                    updateStatus('Envoi terminé avec succès!', 'success');
                    document.getElementById('progress-bar').classList.remove('progress-bar-animated');
                    document.getElementById('completion-actions').classList.remove('d-none');
                } else {
                    // Envoyer le lot suivant
                    currentBatch++;
                    setTimeout(sendBatch, 500);
                }
            }
        })
        .catch(error => {
            updateStatus('Erreur lors de l\'envoi', 'error');
            const errorMessages = document.getElementById('error-messages');
            const errorList = document.getElementById('error-list');
            errorMessages.classList.remove('d-none');
            const li = document.createElement('li');
            li.textContent = error.message || 'Une erreur est survenue';
            errorList.appendChild(li);
            document.getElementById('completion-actions').classList.remove('d-none');
        });
    }

    // Démarrer l'envoi automatiquement
    if (totalCandidates > 0) {
        setTimeout(function() {
            updateStatus('Démarrage de l\'envoi...', 'info');
            sendBatch();
        }, 1000);
    } else {
        updateStatus('Aucun candidat à notifier', 'success');
        const progressBar = document.getElementById('progress-bar');
        progressBar.style.width = '100%';
        document.getElementById('progress-text').textContent = '100%';
        document.getElementById('completion-actions').classList.remove('d-none');
    }
});
</script>
@endpush

@endsection
