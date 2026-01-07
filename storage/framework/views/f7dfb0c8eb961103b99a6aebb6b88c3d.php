<?php $__env->startSection('title', 'Annonces Push Notification'); ?>

<?php $__env->startSection('page-title', 'Annonces Push Notification'); ?>

<?php $__env->startSection('breadcrumbs'); ?>
    <span>/</span>
    <span>Annonces Push</span>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <!-- Statistiques -->
        <div class="col-md-12 mb-4">
            <div class="row">
                <div class="col-md-4">
                    <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <div class="stat-icon">
                            <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                        <div class="stat-content">
                            <div class="stat-label">Total Utilisateurs</div>
                            <div class="stat-value"><?php echo e($totalUsers); ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                        <div class="stat-icon">
                            <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div class="stat-content">
                            <div class="stat-label">Candidats</div>
                            <div class="stat-value"><?php echo e($totalCandidates); ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                        <div class="stat-icon">
                            <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <div class="stat-content">
                            <div class="stat-label">Recruteurs</div>
                            <div class="stat-value"><?php echo e($totalRecruiters); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulaire d'envoi -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3>Envoyer une annonce</h3>
                    <p class="text-muted">Diffusez des notifications push à vos utilisateurs</p>
                </div>
                <div class="card-body">
                    <!-- Tabs -->
                    <div class="tabs">
                        <button class="tab-btn active" data-tab="broadcast">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                            </svg>
                            Diffusion Générale
                        </button>
                        <button class="tab-btn" data-tab="single">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Utilisateur Spécifique
                        </button>
                    </div>

                    <!-- Diffusion Générale -->
                    <div class="tab-content active" id="broadcast-tab">
                        <form id="broadcast-form">
                            <?php echo csrf_field(); ?>
                            <div class="form-group">
                                <label for="broadcast_target">Groupe cible</label>
                                <select id="broadcast_target" name="target_group" class="form-control" required>
                                    <option value="all">Tous les utilisateurs (<?php echo e($totalUsers); ?>)</option>
                                    <option value="candidates">Candidats uniquement (<?php echo e($totalCandidates); ?>)</option>
                                    <option value="recruiters">Recruteurs uniquement (<?php echo e($totalRecruiters); ?>)</option>
                                </select>
                                <small class="form-text text-muted">Sélectionnez le groupe de destinataires</small>
                            </div>

                            <div class="form-group">
                                <label for="broadcast_title">Titre de la notification *</label>
                                <input type="text" id="broadcast_title" name="title" class="form-control" placeholder="Ex: Nouvelle fonctionnalité disponible" maxlength="255" required>
                                <small class="form-text text-muted">Maximum 255 caractères</small>
                            </div>

                            <div class="form-group">
                                <label for="broadcast_message">Message *</label>
                                <textarea id="broadcast_message" name="message" class="form-control" rows="4" placeholder="Écrivez votre message ici..." maxlength="1000" required></textarea>
                                <small class="form-text text-muted">Maximum 1000 caractères</small>
                            </div>

                            <!-- Barre de progression (cachée par défaut) -->
                            <div id="broadcast-progress" class="progress-container" style="display: none;">
                                <div class="progress-header">
                                    <span id="progress-text">Envoi en cours...</span>
                                    <span id="progress-count">0 / 0</span>
                                </div>
                                <div class="progress">
                                    <div id="progress-bar" class="progress-bar" role="progressbar" style="width: 0%"></div>
                                </div>
                                <div class="progress-stats">
                                    <div class="stat-item">
                                        <span class="stat-label">Envoyés:</span>
                                        <span id="sent-count" class="stat-value text-success">0</span>
                                    </div>
                                    <div class="stat-item">
                                        <span class="stat-label">Échoués:</span>
                                        <span id="failed-count" class="stat-value text-danger">0</span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-actions">
                                <button type="submit" id="broadcast-btn" class="btn btn-primary">
                                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                    </svg>
                                    Envoyer à tous
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Utilisateur Spécifique -->
                    <div class="tab-content" id="single-tab" style="display: none;">
                        <form id="single-form">
                            <?php echo csrf_field(); ?>
                            <div class="form-group">
                                <label for="user_id">Sélectionner un utilisateur *</label>
                                <select id="user_id" name="user_id" class="form-control" required>
                                    <option value="">-- Choisir un utilisateur --</option>
                                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?> (<?php echo e($user->email); ?>) - <?php echo e(ucfirst($user->role)); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <small class="form-text text-muted">Seuls les utilisateurs avec token FCM sont listés</small>
                            </div>

                            <div class="form-group">
                                <label for="single_title">Titre de la notification *</label>
                                <input type="text" id="single_title" name="title" class="form-control" placeholder="Ex: Message important" maxlength="255" required>
                                <small class="form-text text-muted">Maximum 255 caractères</small>
                            </div>

                            <div class="form-group">
                                <label for="single_message">Message *</label>
                                <textarea id="single_message" name="message" class="form-control" rows="4" placeholder="Écrivez votre message ici..." maxlength="1000" required></textarea>
                                <small class="form-text text-muted">Maximum 1000 caractères</small>
                            </div>

                            <div class="form-actions">
                                <button type="submit" id="single-btn" class="btn btn-primary">
                                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                    </svg>
                                    Envoyer
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .container-fluid {
        padding: 2rem;
    }

    .stat-card {
        padding: 1.5rem;
        border-radius: 12px;
        color: white;
        display: flex;
        align-items: center;
        gap: 1rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s;
    }

    .stat-card:hover {
        transform: translateY(-4px);
    }

    .stat-icon {
        width: 56px;
        height: 56px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .stat-content {
        flex: 1;
    }

    .stat-label {
        font-size: 0.875rem;
        opacity: 0.9;
        margin-bottom: 0.25rem;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
    }

    .card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .card-header {
        padding: 1.5rem 2rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .card-header h3 {
        margin: 0;
        font-size: 1.5rem;
        color: var(--dark);
    }

    .card-header .text-muted {
        margin: 0.5rem 0 0;
        color: var(--secondary);
    }

    .card-body {
        padding: 2rem;
    }

    .tabs {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
        border-bottom: 2px solid #e2e8f0;
    }

    .tab-btn {
        padding: 1rem 1.5rem;
        background: transparent;
        border: none;
        border-bottom: 3px solid transparent;
        color: var(--secondary);
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .tab-btn:hover {
        color: var(--primary);
    }

    .tab-btn.active {
        color: var(--primary);
        border-bottom-color: var(--primary);
    }

    .tab-content {
        animation: fadeIn 0.3s;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: var(--dark);
    }

    .form-control {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        font-size: 1rem;
        transition: all 0.3s;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .form-text {
        display: block;
        margin-top: 0.5rem;
        font-size: 0.875rem;
    }

    .text-muted {
        color: var(--secondary);
    }

    .progress-container {
        margin: 1.5rem 0;
        padding: 1.5rem;
        background: #f8fafc;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
    }

    .progress-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.75rem;
    }

    .progress-header #progress-text {
        font-weight: 600;
        color: var(--dark);
    }

    .progress-header #progress-count {
        font-weight: 600;
        color: var(--primary);
    }

    .progress {
        height: 24px;
        background: #e2e8f0;
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 1rem;
    }

    .progress-bar {
        height: 100%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        transition: width 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 0.875rem;
    }

    .progress-stats {
        display: flex;
        gap: 2rem;
    }

    .stat-item {
        display: flex;
        gap: 0.5rem;
    }

    .stat-label {
        color: var(--secondary);
        font-weight: 500;
    }

    .stat-value {
        font-weight: 700;
    }

    .text-success {
        color: var(--success);
    }

    .text-danger {
        color: var(--danger);
    }

    .form-actions {
        margin-top: 2rem;
        display: flex;
        gap: 1rem;
    }

    .btn {
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        border: none;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .btn-primary:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4);
    }

    .btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .alert {
        padding: 1rem 1.5rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .alert-success {
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #6ee7b7;
    }

    .alert-error {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fca5a5;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tab switching
        const tabBtns = document.querySelectorAll('.tab-btn');
        const tabContents = document.querySelectorAll('.tab-content');

        tabBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const targetTab = this.getAttribute('data-tab');

                // Remove active class from all tabs
                tabBtns.forEach(b => b.classList.remove('active'));
                tabContents.forEach(c => {
                    c.classList.remove('active');
                    c.style.display = 'none';
                });

                // Add active class to clicked tab
                this.classList.add('active');
                const targetContent = document.getElementById(targetTab + '-tab');
                targetContent.style.display = 'block';
                targetContent.classList.add('active');
            });
        });

        // Single user form submission
        const singleForm = document.getElementById('single-form');
        const singleBtn = document.getElementById('single-btn');

        singleForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            singleBtn.disabled = true;
            singleBtn.innerHTML = '<span>Envoi en cours...</span>';

            try {
                const response = await fetch('<?php echo e(route("admin.announcements.send-to-user")); ?>', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    showAlert('success', result.message);
                    singleForm.reset();
                } else {
                    showAlert('error', result.message || 'Une erreur est survenue');
                }
            } catch (error) {
                showAlert('error', 'Erreur réseau: ' + error.message);
            } finally {
                singleBtn.disabled = false;
                singleBtn.innerHTML = `
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                    Envoyer
                `;
            }
        });

        // Broadcast form submission with batch processing
        const broadcastForm = document.getElementById('broadcast-form');
        const broadcastBtn = document.getElementById('broadcast-btn');
        const progressContainer = document.getElementById('broadcast-progress');
        const progressBar = document.getElementById('progress-bar');
        const progressText = document.getElementById('progress-text');
        const progressCount = document.getElementById('progress-count');
        const sentCount = document.getElementById('sent-count');
        const failedCount = document.getElementById('failed-count');

        broadcastForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            const title = document.getElementById('broadcast_title').value;
            const message = document.getElementById('broadcast_message').value;
            const targetGroup = document.getElementById('broadcast_target').value;

            // Confirm before sending
            if (!confirm('Êtes-vous sûr de vouloir envoyer cette notification ?')) {
                return;
            }

            // Show progress container
            progressContainer.style.display = 'block';
            broadcastBtn.disabled = true;
            broadcastBtn.innerHTML = '<span>Envoi en cours...</span>';

            // Reset progress
            let totalSent = 0;
            let totalFailed = 0;
            let batch = 0;
            const batchSize = 50; // Envoyer par lots de 50

            // Get total count
            const countResponse = await fetch(`<?php echo e(route("admin.announcements.user-count")); ?>?target_group=${targetGroup}`);
            const countData = await countResponse.json();
            const totalUsers = countData.count;

            progressText.textContent = 'Envoi en cours...';

            try {
                let completed = false;

                while (!completed) {
                    const formData = new FormData();
                    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
                    formData.append('title', title);
                    formData.append('message', message);
                    formData.append('batch', batch);
                    formData.append('batch_size', batchSize);
                    formData.append('target_group', targetGroup);

                    const response = await fetch('<?php echo e(route("admin.announcements.send-to-all")); ?>', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                        },
                        body: formData
                    });

                    const result = await response.json();

                    if (result.success) {
                        totalSent += result.sent;
                        totalFailed += result.failed;
                        completed = result.completed;

                        // Update progress
                        const percentage = result.progress.percentage;
                        progressBar.style.width = percentage + '%';
                        progressBar.textContent = percentage.toFixed(0) + '%';
                        progressCount.textContent = `${result.progress.current} / ${result.progress.total}`;
                        sentCount.textContent = totalSent;
                        failedCount.textContent = totalFailed;

                        batch++;
                    } else {
                        throw new Error(result.message || 'Erreur lors de l\'envoi');
                    }
                }

                // Success
                progressText.textContent = 'Envoi terminé !';
                showAlert('success', `Notification envoyée avec succès à ${totalSent} utilisateur(s)`);
                broadcastForm.reset();

                // Hide progress after 3 seconds
                setTimeout(() => {
                    progressContainer.style.display = 'none';
                }, 3000);

            } catch (error) {
                progressText.textContent = 'Erreur lors de l\'envoi';
                showAlert('error', error.message);
            } finally {
                broadcastBtn.disabled = false;
                broadcastBtn.innerHTML = `
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    Envoyer à tous
                `;
            }
        });

        // Helper function to show alerts
        function showAlert(type, message) {
            const alertClass = type === 'success' ? 'alert-success' : 'alert-error';
            const icon = type === 'success' ?
                '<svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>' :
                '<svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>';

            const alert = document.createElement('div');
            alert.className = `alert ${alertClass}`;
            alert.innerHTML = `${icon}<span>${message}</span>`;

            const cardBody = document.querySelector('.card-body');
            cardBody.insertBefore(alert, cardBody.firstChild);

            // Remove after 5 seconds
            setTimeout(() => {
                alert.remove();
            }, 5000);
        }
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/macbookpro/Desktop/Developments/INSAM-DEV/E-Emploie-Backend/estuaire-emploie-backend/resources/views/admin/announcements/index.blade.php ENDPATH**/ ?>