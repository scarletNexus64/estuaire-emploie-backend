<?php $__env->startSection('title', 'Gérer les Étapes'); ?>
<?php $__env->startSection('page-title', 'Gérer les Étapes du Programme'); ?>

<?php $__env->startSection('breadcrumbs'); ?>
    <span> / </span>
    <a href="<?php echo e(route('admin.programs.index')); ?>">Programmes</a>
    <span> / </span>
    <a href="<?php echo e(route('admin.programs.show', $program)); ?>"><?php echo e($program->title); ?></a>
    <span> / </span>
    <span>Étapes</span>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-lg-8">
        <!-- Program Info -->
        <div class="card mb-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
            <div style="padding: 1.5rem; display: flex; align-items: center; gap: 1rem;">
                <div style="font-size: 3rem;"><?php echo e($program->icon); ?></div>
                <div>
                    <h5 class="mb-1" style="color: white;"><?php echo e($program->title); ?></h5>
                    <p class="mb-0" style="opacity: 0.9;"><?php echo e($program->steps->count()); ?> étape(s) configurée(s)</p>
                </div>
            </div>
        </div>

        <!-- Existing Steps -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">📝 Étapes Existantes</h5>
            </div>
            <div style="padding: 1.5rem;">
                <?php $__empty_1 = true; $__currentLoopData = $program->steps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="card mb-3" style="border-left: 4px solid var(--primary);" id="step-<?php echo e($step->id); ?>">
                    <div style="padding: 1rem;">
                        <div style="display: flex; justify-content: space-between; align-items-start;">
                            <div style="flex: 1;">
                                <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.5rem;">
                                    <span class="badge badge-primary">Étape <?php echo e($step->order); ?></span>
                                    <h6 style="margin: 0; font-weight: 700;"><?php echo e($step->title); ?></h6>
                                    <?php if($step->is_required): ?>
                                        <span class="badge badge-danger">Obligatoire</span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary">Optionnel</span>
                                    <?php endif; ?>
                                </div>
                                <p class="text-muted mb-2"><?php echo e($step->description); ?></p>

                                <?php if($step->content): ?>
                                <div class="mb-2">
                                    <button class="btn btn-sm btn-outline-secondary" onclick="toggleContent('content-<?php echo e($step->id); ?>')">
                                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Voir le contenu
                                    </button>
                                    <div id="content-<?php echo e($step->id); ?>" style="display: none; margin-top: 0.75rem; padding: 1rem; background: var(--light); border-radius: 8px;">
                                        <div style="white-space: pre-line;"><?php echo e($step->content); ?></div>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <?php if($step->estimated_duration_days): ?>
                                <div class="small text-muted mb-2">
                                    ⏱ Durée estimée : <?php echo e($step->estimated_duration_days); ?> jour(s)
                                </div>
                                <?php endif; ?>

                                <?php if($step->resources && count($step->resources) > 0): ?>
                                <div class="mt-2">
                                    <strong class="small">Ressources :</strong>
                                    <ul class="small" style="margin: 0.25rem 0 0 1.5rem; padding: 0;">
                                        <?php $__currentLoopData = $step->resources; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $resource): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li>
                                            <a href="<?php echo e($resource['url']); ?>" target="_blank">
                                                <?php echo e($resource['title']); ?>

                                                <?php if($resource['type'] === 'video'): ?> 🎥
                                                <?php elseif($resource['type'] === 'document'): ?> 📄
                                                <?php elseif($resource['type'] === 'article'): ?> 📰
                                                <?php else: ?> 🔗
                                                <?php endif; ?>
                                            </a>
                                        </li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                </div>
                                <?php endif; ?>
                            </div>
                            <div style="display: flex; gap: 0.5rem;">
                                <button class="btn btn-sm btn-secondary" onclick="editStep(<?php echo e($step->id); ?>)" title="Modifier">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                <form method="POST" action="<?php echo e(route('admin.programs.destroy-step', [$program, $step])); ?>" style="display: inline;">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-danger" title="Supprimer" onclick="return confirm('Supprimer cette étape ?')">
                                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="alert alert-info">
                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"/>
                    </svg>
                    Aucune étape pour le moment. Utilisez le formulaire ci-dessous pour ajouter des étapes.
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Add New Step Form -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">➕ Ajouter une Étape</h5>
            </div>
            <div style="padding: 1.5rem;">
                <form method="POST" action="<?php echo e(route('admin.programs.store-step', $program)); ?>" id="stepForm">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="_method" id="form_method" value="POST">
                    <input type="hidden" name="step_id" id="step_id">

                    <div class="form-group">
                        <label for="title" class="form-label">Titre *</label>
                        <input type="text" class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               id="title" name="title" required>
                        <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="form-group">
                        <label for="description" class="form-label">Description *</label>
                        <textarea class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                  id="description" name="description" rows="3" required></textarea>
                        <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="form-group">
                        <label for="content" class="form-label">Contenu Détaillé</label>
                        <textarea class="form-control <?php $__errorArgs = ['content'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                  id="content" name="content" rows="4"
                                  placeholder="Guide détaillé pour cette étape..."></textarea>
                        <?php $__errorArgs = ['content'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="order" class="form-label">Ordre</label>
                                <input type="number" class="form-control"
                                       id="order" name="order" value="<?php echo e($program->steps->max('order') + 1); ?>" min="0">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="estimated_duration_days" class="form-label">Durée (jours)</label>
                                <input type="number" class="form-control"
                                       id="estimated_duration_days" name="estimated_duration_days" min="1">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_required" name="is_required" value="1" checked>
                            <label class="form-check-label" for="is_required">
                                Étape obligatoire
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Ressources</label>
                        <div id="resources-container">
                            <!-- Resources will be added dynamically -->
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="addResource()">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Ajouter une ressource
                        </button>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%;">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span id="submitBtnText">Ajouter l'Étape</span>
                    </button>

                    <a href="<?php echo e(route('admin.programs.show', $program)); ?>" class="btn btn-outline-secondary mt-2" style="width: 100%;">
                        Retour au Programme
                    </a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
let resourceIndex = 0;
let currentEditingStepId = null;

function toggleContent(id) {
    const element = document.getElementById(id);
    element.style.display = element.style.display === 'none' ? 'block' : 'none';
}

function addResource(title = '', url = '', type = 'link') {
    const container = document.getElementById('resources-container');
    const resourceHtml = `
        <div class="card mb-2" id="resource-${resourceIndex}" style="padding: 0.75rem;">
            <div style="display: flex; gap: 0.5rem; align-items: start;">
                <div style="flex: 1;">
                    <input type="text" class="form-control mb-2" name="resources[${resourceIndex}][title]" placeholder="Titre de la ressource" value="${title}" required>
                    <input type="url" class="form-control mb-2" name="resources[${resourceIndex}][url]" placeholder="https://..." value="${url}" required>
                    <select class="form-control" name="resources[${resourceIndex}][type]" required>
                        <option value="link" ${type === 'link' ? 'selected' : ''}>🔗 Lien</option>
                        <option value="document" ${type === 'document' ? 'selected' : ''}>📄 Document</option>
                        <option value="video" ${type === 'video' ? 'selected' : ''}>🎥 Vidéo</option>
                        <option value="article" ${type === 'article' ? 'selected' : ''}>📰 Article</option>
                    </select>
                </div>
                <button type="button" class="btn btn-sm btn-danger" onclick="removeResource(${resourceIndex})">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', resourceHtml);
    resourceIndex++;
}

function removeResource(index) {
    document.getElementById(`resource-${index}`).remove();
}

function resetForm() {
    currentEditingStepId = null;
    document.getElementById('stepForm').reset();
    document.getElementById('stepForm').action = "<?php echo e(route('admin.programs.store-step', $program)); ?>";
    document.getElementById('form_method').value = "POST";
    document.getElementById('step_id').value = "";
    document.getElementById('submitBtnText').textContent = "Ajouter l'Étape";
    document.querySelector('.card-header h5').textContent = "➕ Ajouter une Étape";
    document.getElementById('resources-container').innerHTML = '';
    resourceIndex = 0;

    // Scroll to form
    document.querySelector('.col-lg-4').scrollIntoView({ behavior: 'smooth' });
}

async function editStep(stepId) {
    try {
        // Use XMLHttpRequest for better cookie handling
        const step = await new Promise((resolve, reject) => {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', `/admin/programs/<?php echo e($program->id); ?>/steps/${stepId}`);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.setRequestHeader('Accept', 'application/json');
            xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '<?php echo e(csrf_token()); ?>');
            xhr.withCredentials = true;

            xhr.onload = function() {
                if (xhr.status >= 200 && xhr.status < 300) {
                    try {
                        resolve(JSON.parse(xhr.responseText));
                    } catch (e) {
                        console.error('Parse error:', xhr.responseText);
                        reject(new Error('Erreur de parsing JSON'));
                    }
                } else {
                    console.error('Erreur API:', xhr.status, xhr.responseText);
                    reject(new Error(`Erreur ${xhr.status}: ${xhr.statusText}`));
                }
            };

            xhr.onerror = function() {
                console.error('Erreur réseau');
                reject(new Error('Erreur réseau lors du chargement de l\'étape'));
            };

            xhr.send();
        });

        // Update form for editing
        currentEditingStepId = stepId;
        document.getElementById('stepForm').action = `/admin/programs/<?php echo e($program->id); ?>/steps/${stepId}`;
        document.getElementById('form_method').value = "PUT";
        document.getElementById('step_id').value = stepId;

        // Populate form fields
        document.getElementById('title').value = step.title || '';
        document.getElementById('description').value = step.description || '';
        document.getElementById('content').value = step.content || '';
        document.getElementById('order').value = step.order || 0;
        document.getElementById('estimated_duration_days').value = step.estimated_duration_days || '';
        document.getElementById('is_required').checked = step.is_required;

        // Clear and populate resources
        document.getElementById('resources-container').innerHTML = '';
        resourceIndex = 0;

        if (step.resources && step.resources.length > 0) {
            step.resources.forEach(resource => {
                addResource(resource.title, resource.url, resource.type);
            });
        }

        // Update button text and header
        document.getElementById('submitBtnText').textContent = "Modifier l'Étape";
        document.querySelector('.card-header h5').textContent = "✏️ Modifier l'Étape";

        // Scroll to form
        document.querySelector('.col-lg-4').scrollIntoView({ behavior: 'smooth' });

    } catch (error) {
        console.error('Erreur:', error);
        alert('Erreur lors du chargement de l\'étape. Veuillez réessayer.');
    }
}

// Add cancel button functionality
document.addEventListener('DOMContentLoaded', function() {
    // Add a cancel/reset button after submit button
    const submitBtn = document.querySelector('button[type="submit"]');
    const cancelBtn = document.createElement('button');
    cancelBtn.type = 'button';
    cancelBtn.className = 'btn btn-outline-secondary mt-2';
    cancelBtn.style.width = '100%';
    cancelBtn.textContent = 'Annuler la modification';
    cancelBtn.style.display = 'none';
    cancelBtn.id = 'cancelEditBtn';
    cancelBtn.onclick = resetForm;

    submitBtn.parentNode.insertBefore(cancelBtn, submitBtn.nextSibling);

    // Show/hide cancel button based on editing state
    const observer = new MutationObserver(function(mutations) {
        const isEditing = document.getElementById('form_method').value === 'PUT';
        document.getElementById('cancelEditBtn').style.display = isEditing ? 'block' : 'none';
    });

    observer.observe(document.getElementById('form_method'), {
        attributes: true,
        attributeFilter: ['value']
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/clients/client1/web19/web/estuaire-emploie-backend/resources/views/admin/programs/manage-steps.blade.php ENDPATH**/ ?>