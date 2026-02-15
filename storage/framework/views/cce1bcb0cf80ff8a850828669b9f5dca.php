<?php $__env->startSection('title', 'Annonces Push & Email'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <span>Notifications</span>
    <span>/</span>
    <span class="font-semibold">Annonces</span>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<!-- Statistiques -->
<div class="stats-grid mb-6">
    <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
        <div class="stat-header">
            <div>
                <div class="stat-label" style="color: rgba(255,255,255,0.9);">Total Utilisateurs</div>
                <div class="stat-value" style="color: white;"><?php echo e($totalUsers); ?></div>
            </div>
            <i class="mdi mdi-account-group" style="font-size: 3rem; opacity: 0.3;"></i>
        </div>
    </div>

    <div class="stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
        <div class="stat-header">
            <div>
                <div class="stat-label" style="color: rgba(255,255,255,0.9);">Candidats</div>
                <div class="stat-value" style="color: white;"><?php echo e($totalCandidates); ?></div>
            </div>
            <i class="mdi mdi-account" style="font-size: 3rem; opacity: 0.3;"></i>
        </div>
    </div>

    <div class="stat-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
        <div class="stat-header">
            <div>
                <div class="stat-label" style="color: rgba(255,255,255,0.9);">Recruteurs</div>
                <div class="stat-value" style="color: white;"><?php echo e($totalRecruiters); ?></div>
            </div>
            <i class="mdi mdi-briefcase" style="font-size: 3rem; opacity: 0.3;"></i>
        </div>
    </div>
</div>

<!-- Formulaire d'envoi -->
<div class="card">
    <div class="card-header">
        <div>
            <h3 class="card-title">Envoyer une annonce</h3>
            <p class="text-gray-600 text-sm mt-1">Diffusez des notifications push et/ou emails à vos utilisateurs</p>
        </div>
    </div>
    <div class="card-body">
        <!-- Tabs -->
        <div class="nav-tabs">
            <a href="#" class="nav-link active" data-tab="broadcast">
                <i class="mdi mdi-bullhorn"></i>
                Diffusion Générale
            </a>
            <a href="#" class="nav-link" data-tab="single">
                <i class="mdi mdi-account"></i>
                Utilisateur Spécifique
            </a>
        </div>

        <!-- Diffusion Générale -->
        <div class="tab-content-panel active" id="broadcast-tab">
            <form id="broadcast-form">
                <?php echo csrf_field(); ?>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="broadcast_target">Groupe cible *</label>
                            <select id="broadcast_target" name="target_group" class="form-control" required>
                                <option value="all">Tous les utilisateurs (<?php echo e($totalUsers); ?>)</option>
                                <option value="candidates">Candidats uniquement (<?php echo e($totalCandidates); ?>)</option>
                                <option value="recruiters">Recruteurs uniquement (<?php echo e($totalRecruiters); ?>)</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="broadcast_channel">Canal d'envoi *</label>
                            <select id="broadcast_channel" name="channel" class="form-control" required>
                                <option value="both">Push + Email (Recommandé)</option>
                                <option value="push">Push uniquement</option>
                                <option value="email">Email uniquement</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="broadcast_title">Titre de la notification *</label>
                    <input type="text" id="broadcast_title" name="title" class="form-control"
                           placeholder="Ex: Nouvelle fonctionnalité disponible" maxlength="255" required>
                    <small class="text-gray-600">Maximum 255 caractères</small>
                </div>

                <div class="form-group">
                    <label for="broadcast_message">Message *</label>
                    <textarea id="broadcast_message" name="message" class="form-control" rows="4"
                              placeholder="Écrivez votre message ici..." maxlength="1000" required></textarea>
                    <small class="text-gray-600">Maximum 1000 caractères</small>
                </div>

                <!-- Barre de progression -->
                <div id="broadcast-progress" class="bg-gray-50 p-4 rounded-lg border border-gray-200 mb-4" style="display: none;">
                    <div class="flex justify-between items-center mb-2">
                        <span id="progress-text" class="font-semibold text-gray-900">Envoi en cours...</span>
                        <span id="progress-count" class="font-semibold text-primary">0 / 0</span>
                    </div>
                    <div class="progress mb-3">
                        <div id="progress-bar" class="progress-bar" role="progressbar" style="width: 0%"></div>
                    </div>
                    <div class="flex gap-6">
                        <div>
                            <span class="text-gray-600 text-sm">Envoyés: </span>
                            <span id="sent-count" class="font-bold text-green-600">0</span>
                        </div>
                        <div>
                            <span class="text-gray-600 text-sm">Échoués: </span>
                            <span id="failed-count" class="font-bold text-red-600">0</span>
                        </div>
                    </div>
                </div>

                <button type="submit" id="broadcast-btn" class="btn btn-primary">
                    <i class="mdi mdi-send"></i>
                    Envoyer à tous
                </button>
            </form>
        </div>

        <!-- Utilisateur Spécifique -->
        <div class="tab-content-panel" id="single-tab" style="display: none;">
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
                    <small class="text-gray-600">Seuls les utilisateurs avec token FCM sont listés</small>
                </div>

                <div class="form-group">
                    <label for="single_channel">Canal d'envoi *</label>
                    <select id="single_channel" name="channel" class="form-control" required>
                        <option value="both">Push + Email (Recommandé)</option>
                        <option value="push">Push uniquement</option>
                        <option value="email">Email uniquement</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="single_title">Titre de la notification *</label>
                    <input type="text" id="single_title" name="title" class="form-control"
                           placeholder="Ex: Message important" maxlength="255" required>
                </div>

                <div class="form-group">
                    <label for="single_message">Message *</label>
                    <textarea id="single_message" name="message" class="form-control" rows="4"
                              placeholder="Écrivez votre message ici..." maxlength="1000" required></textarea>
                </div>

                <button type="submit" id="single-btn" class="btn btn-primary">
                    <i class="mdi mdi-send"></i>
                    Envoyer
                </button>
            </form>
        </div>
    </div>
</div>

<style>
/* Styles spécifiques pour les tabs */
.nav-tabs {
    margin-bottom: 2rem;
}

.nav-link {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.tab-content-panel {
    animation: fadeIn 0.3s;
}

.tab-content-panel.active {
    display: block;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Override pour les select qui ont l'air trop basiques */
select.form-control {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 0.5rem center;
    background-repeat: no-repeat;
    background-size: 1.5em 1.5em;
    padding-right: 2.5rem;
    appearance: none;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab switching
    const tabLinks = document.querySelectorAll('.nav-link');
    const tabPanels = document.querySelectorAll('.tab-content-panel');

    tabLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetTab = this.getAttribute('data-tab');

            // Remove active class from all
            tabLinks.forEach(l => l.classList.remove('active'));
            tabPanels.forEach(p => {
                p.classList.remove('active');
                p.style.display = 'none';
            });

            // Add active class to clicked
            this.classList.add('active');
            const targetPanel = document.getElementById(targetTab + '-tab');
            targetPanel.style.display = 'block';
            targetPanel.classList.add('active');
        });
    });

    // Single user form submission
    const singleForm = document.getElementById('single-form');
    const singleBtn = document.getElementById('single-btn');

    singleForm.addEventListener('submit', async function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        singleBtn.disabled = true;
        singleBtn.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i> Envoi en cours...';

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
            singleBtn.innerHTML = '<i class="mdi mdi-send"></i> Envoyer';
        }
    });

    // Broadcast form submission
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
        const channel = document.getElementById('broadcast_channel').value;

        if (!confirm('Êtes-vous sûr de vouloir envoyer cette notification ?')) {
            return;
        }

        progressContainer.style.display = 'block';
        broadcastBtn.disabled = true;
        broadcastBtn.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i> Envoi en cours...';

        let totalSent = 0;
        let totalFailed = 0;
        let batch = 0;
        const batchSize = 50;

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
                formData.append('channel', channel);

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

            progressText.textContent = 'Envoi terminé !';
            showAlert('success', `Notification envoyée avec succès à ${totalSent} utilisateur(s)`);
            broadcastForm.reset();

            setTimeout(() => {
                progressContainer.style.display = 'none';
            }, 3000);

        } catch (error) {
            progressText.textContent = 'Erreur lors de l\'envoi';
            showAlert('error', error.message);
        } finally {
            broadcastBtn.disabled = false;
            broadcastBtn.innerHTML = '<i class="mdi mdi-send"></i> Envoyer à tous';
        }
    });

    function showAlert(type, message) {
        const alertClass = type === 'success' ? 'bg-green-50 border-green-500 text-green-700' : 'bg-red-50 border-red-500 text-red-700';
        const icon = type === 'success' ? 'mdi-check-circle' : 'mdi-alert-circle';

        const alert = document.createElement('div');
        alert.className = `flex items-center gap-3 ${alertClass} border-l-4 px-4 py-3 rounded-lg shadow-sm mb-4`;
        alert.innerHTML = `
            <i class="mdi ${icon} text-xl"></i>
            <div class="flex-1">${message}</div>
            <button onclick="this.parentElement.remove()" class="hover:opacity-75">
                <i class="mdi mdi-close text-xl"></i>
            </button>
        `;

        const cardBody = document.querySelector('.card-body');
        cardBody.insertBefore(alert, cardBody.firstChild);

        setTimeout(() => alert.remove(), 5000);
    }
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/clients/client1/web19/web/estuaire-emploie-backend/resources/views/admin/announcements/index.blade.php ENDPATH**/ ?>