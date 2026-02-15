<?php $__env->startSection('title', 'Envoi de notifications - ' . $job->title); ?>

<?php $__env->startSection('content'); ?>
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
                            <i class="fas fa-briefcase"></i> <?php echo e($job->title); ?>

                        </h5>
                        <p class="mb-0">
                            <strong>Entreprise:</strong> <?php echo e($job->company->name); ?><br>
                            <strong>Localisation:</strong> <?php echo e($job->location->name); ?>

                        </p>
                    </div>

                    <!-- Status de l'envoi PUSH -->
                    <div id="sending-status" class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="mb-0">
                                <i class="fas fa-mobile-alt"></i> Notifications Push
                            </h5>
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

                        <!-- Stats Push -->
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <h6 class="text-muted">Total utilisateurs (Push)</h6>
                                        <h3 id="total-push-count" class="text-primary"><?php echo e($totalPushUsers); ?></h3>
                                        <small class="text-muted">Candidats & Recruteurs (sauf auteur)</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <h6 class="text-muted">Push envoyées</h6>
                                        <h3 id="sent-push-count" class="text-success">0</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status de l'envoi EMAIL -->
                    <div id="email-sending-status" class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="mb-0">
                                <i class="fas fa-envelope"></i> Notifications Email
                            </h5>
                            <span id="email-status-text" class="badge badge-info">
                                <i class="fas fa-spinner fa-spin"></i> Préparation...
                            </span>
                        </div>

                        <!-- Progress Bar Email -->
                        <div class="progress" style="height: 30px;">
                            <div id="email-progress-bar" class="progress-bar progress-bar-striped progress-bar-animated bg-info"
                                 role="progressbar"
                                 style="width: 0%"
                                 aria-valuenow="0"
                                 aria-valuemin="0"
                                 aria-valuemax="100">
                                <span id="email-progress-text">0%</span>
                            </div>
                        </div>

                        <!-- Stats Email -->
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <h6 class="text-muted">Total candidats (Email)</h6>
                                        <h3 id="total-email-count" class="text-primary"><?php echo e($totalEmailUsers); ?></h3>
                                        <small class="text-muted">Candidats actifs avec email vérifié</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <h6 class="text-muted">Emails envoyés</h6>
                                        <h3 id="sent-email-count" class="text-success">0</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div id="completion-actions" class="d-none mt-4">
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i>
                            <strong>Terminé!</strong> Les notifications ont été envoyées avec succès.
                        </div>
                        <a href="<?php echo e(route('admin.jobs.index')); ?>" class="btn btn-primary">
                            <i class="fas fa-arrow-left"></i> Retour à la liste des offres
                        </a>
                        <a href="<?php echo e(route('admin.jobs.show', $job)); ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-eye"></i> Voir l'offre
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Variables pour Push
    let currentPushBatch = 0;
    const pushBatchSize = 50;
    let totalPushSent = 0;
    let totalPushFailed = 0;
    let totalPushUsers = <?php echo e($totalPushUsers); ?>;

    // Variables pour Email
    let currentEmailBatch = 0;
    const emailBatchSize = 10;
    let totalEmailSent = 0;
    let totalEmailFailed = 0;
    let totalEmailUsers = <?php echo e($totalEmailUsers); ?>;

    const jobId = <?php echo e($job->id); ?>;

    function updatePushProgress(sent, failed, total) {
        const percentage = Math.min(100, Math.round(((sent + failed) / total) * 100));
        const progressBar = document.getElementById('progress-bar');
        const progressText = document.getElementById('progress-text');
        const sentPushCount = document.getElementById('sent-push-count');

        progressBar.style.width = percentage + '%';
        progressBar.setAttribute('aria-valuenow', percentage);
        progressText.textContent = percentage + '%';
        sentPushCount.textContent = sent;
    }

    function updateEmailProgress(sent, failed, total) {
        const percentage = Math.min(100, Math.round(((sent + failed) / total) * 100));
        const progressBar = document.getElementById('email-progress-bar');
        const progressText = document.getElementById('email-progress-text');
        const sentEmailCount = document.getElementById('sent-email-count');

        progressBar.style.width = percentage + '%';
        progressBar.setAttribute('aria-valuenow', percentage);
        progressText.textContent = percentage + '%';
        sentEmailCount.textContent = sent;
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

    function updateEmailStatus(message, type = 'info') {
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

        const statusText = document.getElementById('email-status-text');
        statusText.className = 'badge ' + badgeMap[type];
        statusText.innerHTML = `<i class="fas ${iconMap[type]}"></i> ${message}`;
    }

    function sendPushBatch() {
        updateStatus(`Envoi push lot ${currentPushBatch + 1}...`, 'info');

        fetch('<?php echo e(route("admin.jobs.send-notifications-batch", $job)); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                batch: currentPushBatch,
                batch_size: pushBatchSize
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                totalPushSent += data.sent;
                totalPushFailed += data.failed;

                updatePushProgress(totalPushSent, totalPushFailed, totalPushUsers);

                // Si terminé
                if (data.completed) {
                    updateStatus('Push terminées!', 'success');
                    document.getElementById('progress-bar').classList.remove('progress-bar-animated');

                    // Démarrer l'envoi des emails
                    startEmailSending();
                } else {
                    // Envoyer le lot suivant
                    currentPushBatch++;
                    setTimeout(sendPushBatch, 500);
                }
            }
        })
        .catch(error => {
            updateStatus('Push terminées', 'success');
            document.getElementById('progress-bar').classList.remove('progress-bar-animated');
            startEmailSending();
        });
    }

    function sendEmailBatch() {
        updateEmailStatus(`Envoi email lot ${currentEmailBatch + 1}...`, 'info');

        fetch('<?php echo e(route("admin.jobs.send-emails-batch", $job)); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                batch: currentEmailBatch,
                batch_size: emailBatchSize
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                totalEmailSent += data.sent;
                totalEmailFailed += data.failed;

                updateEmailProgress(totalEmailSent, totalEmailFailed, totalEmailUsers);

                // Si terminé
                if (data.completed) {
                    updateEmailStatus('Emails terminés!', 'success');
                    document.getElementById('email-progress-bar').classList.remove('progress-bar-animated');
                    document.getElementById('completion-actions').classList.remove('d-none');
                } else {
                    // Envoyer le lot suivant
                    currentEmailBatch++;
                    setTimeout(sendEmailBatch, 1000);
                }
            }
        })
        .catch(error => {
            updateEmailStatus('Emails terminés', 'success');
            document.getElementById('email-progress-bar').classList.remove('progress-bar-animated');
            document.getElementById('completion-actions').classList.remove('d-none');
        });
    }

    function startEmailSending() {
        if (totalEmailUsers > 0) {
            updateEmailStatus('Démarrage...', 'info');
            sendEmailBatch();
        } else {
            updateEmailStatus('Aucun email à envoyer', 'success');
            document.getElementById('email-progress-bar').style.width = '100%';
            document.getElementById('email-progress-text').textContent = '100%';
            document.getElementById('completion-actions').classList.remove('d-none');
        }
    }

    // Démarrer l'envoi automatiquement
    setTimeout(function() {
        if (totalPushUsers > 0) {
            updateStatus('Démarrage...', 'info');
            sendPushBatch();
        } else {
            updateStatus('Aucune push à envoyer', 'success');
            document.getElementById('progress-bar').style.width = '100%';
            document.getElementById('progress-text').textContent = '100%';
            startEmailSending();
        }
    }, 1000);
});
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/clients/client1/web19/web/estuaire-emploie-backend/resources/views/admin/jobs/send-notifications.blade.php ENDPATH**/ ?>