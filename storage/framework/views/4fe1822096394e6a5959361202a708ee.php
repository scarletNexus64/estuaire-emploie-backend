<?php $__env->startSection('title', 'Détails Entreprise'); ?>
<?php $__env->startSection('page-title', 'Détails de l\'Entreprise'); ?>

<?php $__env->startSection('content'); ?>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><?php echo e($company->name); ?></h3>
            <div>
                <?php if($company->status === 'pending'): ?>
                    <form action="<?php echo e(route('admin.companies.verify', $company)); ?>" method="POST" style="display: inline;">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PATCH'); ?>
                        <button type="submit" class="btn btn-success">Vérifier</button>
                    </form>
                <?php endif; ?>
                <?php if($company->status !== 'suspended'): ?>
                    <form action="<?php echo e(route('admin.companies.suspend', $company)); ?>" method="POST" style="display: inline;">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PATCH'); ?>
                        <button type="submit" class="btn btn-danger">Suspendre</button>
                    </form>
                <?php endif; ?>
                <a href="<?php echo e(route('admin.companies.edit', $company)); ?>" class="btn btn-primary">Éditer</a>
            </div>
        </div>

        <div style="padding: 1.5rem;">
            <!-- Logo Section -->
            <?php if($company->logo): ?>
            <div style="text-align: center; margin-bottom: 2rem;">
                <img src="<?php echo e($company->logo_url); ?>"
                     alt="Logo <?php echo e($company->name); ?>"
                     style="max-width: 200px; max-height: 200px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);"
                     onerror="this.style.display='none'">
            </div>
            <?php endif; ?>

            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 2rem;">
                <div>
                    <h4 style="margin-bottom: 1rem; font-weight: 600;">Informations Générales</h4>
                    <p><strong>Email:</strong> <?php echo e($company->email); ?></p>
                    <p><strong>Téléphone:</strong> <?php echo e($company->phone ?? 'N/A'); ?></p>
                    <p><strong>Secteur:</strong> <?php echo e($company->sector); ?></p>
                    <p><strong>Site web:</strong>
                        <?php if($company->website): ?>
                            <a href="<?php echo e($company->website); ?>" target="_blank"><?php echo e($company->website); ?></a>
                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </p>
                    <p><strong>Adresse:</strong> <?php echo e($company->address ?? 'N/A'); ?></p>
                    <p><strong>Ville:</strong> <?php echo e($company->city ?? 'N/A'); ?></p>
                    <p><strong>Pays:</strong> <?php echo e($company->country); ?></p>
                    <p><strong>Coordonnées GPS:</strong>
                        <?php if($company->latitude && $company->longitude): ?>
                            <span style="color: #28a745;">
                                📍 <?php echo e(number_format($company->latitude, 6)); ?>, <?php echo e(number_format($company->longitude, 6)); ?>

                            </span>
                            <a href="https://www.google.com/maps?q=<?php echo e($company->latitude); ?>,<?php echo e($company->longitude); ?>"
                               target="_blank"
                               class="btn btn-sm btn-info"
                               style="margin-left: 10px;">
                                🗺️ Voir sur Google Maps
                            </a>
                        <?php else: ?>
                            <span style="color: #dc3545;">Non renseignées</span>
                        <?php endif; ?>
                    </p>
                    <p><strong>Statut:</strong>
                        <?php if($company->status === 'verified'): ?>
                            <span class="badge badge-success">Vérifiée</span>
                        <?php elseif($company->status === 'pending'): ?>
                            <span class="badge badge-warning">En attente</span>
                        <?php else: ?>
                            <span class="badge badge-danger">Suspendue</span>
                        <?php endif; ?>
                    </p>
                    <p><strong>Plan:</strong>
                        <?php if($company->subscription_plan === 'premium'): ?>
                            <span class="badge badge-success">Premium</span>
                        <?php else: ?>
                            <span class="badge badge-secondary">Gratuit</span>
                        <?php endif; ?>
                    </p>
                    <p><strong>Date d'inscription:</strong> <?php echo e($company->created_at->format('d/m/Y H:i')); ?></p>
                </div>
                <div>
                    <h4 style="margin-bottom: 1rem; font-weight: 600;">Description</h4>
                    <p><?php echo e($company->description ?? 'Aucune description disponible'); ?></p>
                </div>
            </div>

            <!-- Google Maps Preview Section -->
            <?php if($company->latitude && $company->longitude): ?>
            <div style="margin-top: 2rem;">
                <h4 style="margin-bottom: 1rem; font-weight: 600;">🗺️ Localisation sur la Carte</h4>
                <div style="border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                    <iframe
                        width="100%"
                        height="400"
                        frameborder="0"
                        style="border:0"
                        referrerpolicy="no-referrer-when-downgrade"
                        src="https://www.google.com/maps/embed/v1/place?key=AIzaSyAffUHSFli6kMnjkfJOKBGO6AN828ixJPo&q=<?php echo e($company->latitude); ?>,<?php echo e($company->longitude); ?>&zoom=15"
                        allowfullscreen>
                    </iframe>
                </div>
                <p style="margin-top: 0.5rem; font-size: 0.875rem; color: #6c757d;">
                    📍 Coordonnées: <?php echo e(number_format($company->latitude, 6)); ?>, <?php echo e(number_format($company->longitude, 6)); ?>

                </p>
            </div>
            <?php elseif($company->address || $company->city): ?>
            <div style="margin-top: 2rem; padding: 1.5rem; background-color: #fff3cd; border-left: 4px solid #ffc107; border-radius: 8px;">
                <h4 style="margin-bottom: 0.5rem; color: #856404;">⚠️ Coordonnées GPS manquantes</h4>
                <p style="margin: 0; color: #856404;">
                    Cette entreprise a une adresse mais pas de coordonnées GPS.
                    <a href="<?php echo e(route('admin.companies.edit', $company)); ?>" style="color: #004085; text-decoration: underline;">
                        Cliquez ici pour les ajouter
                    </a>
                </p>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Recruteurs (<?php echo e($company->recruiters->count()); ?>)</h3>
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Poste</th>
                        <th>Permissions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $company->recruiters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $recruiter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($recruiter->user?->name ?? 'N/A'); ?></td>
                            <td><?php echo e($recruiter->user?->email ?? 'N/A'); ?></td>
                            <td><?php echo e($recruiter->position ?? 'N/A'); ?></td>
                            <td>
                                <?php if($recruiter->can_publish): ?> <span class="badge badge-success">Publier</span> <?php endif; ?>
                                <?php if($recruiter->can_view_applications): ?> <span class="badge badge-info">Voir candidatures</span> <?php endif; ?>
                                <?php if($recruiter->can_modify_company): ?> <span class="badge badge-warning">Modifier entreprise</span> <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 2rem;">Aucun recruteur</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Offres d'Emploi (<?php echo e($company->jobs->count()); ?>)</h3>
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Statut</th>
                        <th>Candidatures</th>
                        <th>Vues</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $company->jobs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $job): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($job->title); ?></td>
                            <td>
                                <?php if($job->status === 'published'): ?>
                                    <span class="badge badge-success">Publié</span>
                                <?php elseif($job->status === 'pending'): ?>
                                    <span class="badge badge-warning">En attente</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary"><?php echo e(ucfirst($job->status)); ?></span>
                                <?php endif; ?>
                            </td>
                            <td><span class="badge badge-info"><?php echo e($job->applications->count()); ?></span></td>
                            <td><?php echo e($job->views_count); ?></td>
                            <td><?php echo e($job->created_at->format('d/m/Y')); ?></td>
                            <td>
                                <a href="<?php echo e(route('admin.jobs.show', $job)); ?>" class="btn btn-secondary btn-sm">Voir</a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 2rem;">Aucune offre</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/clients/client1/web19/web/estuaire-emploie-backend/resources/views/admin/companies/show.blade.php ENDPATH**/ ?>