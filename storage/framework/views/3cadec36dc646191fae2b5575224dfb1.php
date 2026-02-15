<?php $__env->startSection('title', 'Détails Portfolio'); ?>
<?php $__env->startSection('page-title', 'Détails du Portfolio'); ?>

<?php $__env->startSection('breadcrumbs'); ?>
    <span> / </span>
    <a href="<?php echo e(route('admin.portfolios.index')); ?>">Portfolios</a>
    <span> / </span>
    <span><?php echo e($portfolio->user->name); ?></span>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header-actions'); ?>
    <a href="<?php echo e($portfolio->public_url); ?>" target="_blank" class="btn btn-primary">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
        </svg>
        Voir le Portfolio
    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .portfolio-detail-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
        margin-bottom: 2rem;
    }

    @media (max-width: 992px) {
        .portfolio-detail-grid {
            grid-template-columns: 1fr;
        }
    }

    .detail-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .detail-card-header {
        padding: 1.5rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .detail-card-header.template-professional {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .detail-card-header.template-creative {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }

    .detail-card-header.template-tech {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }

    .detail-card-header h3 {
        margin: 0;
        font-size: 1.25rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .detail-card-body {
        padding: 1.5rem;
    }

    .detail-avatar-section {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        flex: 1;
    }

    .detail-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid rgba(255,255,255,0.3);
    }

    .detail-avatar-placeholder {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: rgba(255,255,255,0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        font-weight: bold;
        border: 4px solid rgba(255,255,255,0.3);
    }

    .detail-user-info h2 {
        margin: 0 0 0.5rem 0;
        font-size: 1.75rem;
        font-weight: 800;
    }

    .detail-user-meta {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        font-size: 0.95rem;
    }

    .detail-badges {
        display: flex;
        gap: 0.5rem;
        margin-top: 0.75rem;
        flex-wrap: wrap;
    }

    .detail-badge {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        background: rgba(255,255,255,0.2);
        backdrop-filter: blur(10px);
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .info-item {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .info-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .info-value {
        font-size: 1rem;
        color: #1f2937;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .color-swatch {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        border: 2px solid rgba(0,0,0,0.1);
        display: inline-block;
    }

    .section-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .skill-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 0.75rem;
    }

    .skill-badge {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 0.75rem 1rem;
        border-radius: 12px;
        font-size: 0.9rem;
        font-weight: 600;
        text-align: center;
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .skill-level {
        font-size: 0.75rem;
        opacity: 0.9;
    }

    .experience-card, .education-card, .project-card {
        background: #f9fafb;
        border-left: 4px solid #667eea;
        padding: 1.25rem;
        border-radius: 8px;
        margin-bottom: 1rem;
    }

    .experience-card h4, .education-card h4, .project-card h4 {
        margin: 0 0 0.5rem 0;
        font-size: 1.125rem;
        font-weight: 700;
        color: #1f2937;
    }

    .experience-meta, .education-meta {
        color: #6b7280;
        font-size: 0.9rem;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .stat-card-detail {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        margin-bottom: 1.5rem;
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 800;
        color: #667eea;
        margin: 0.5rem 0;
    }

    .stat-description {
        color: #6b7280;
        font-size: 0.9rem;
    }

    .views-chart {
        margin-top: 1rem;
    }

    .chart-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.75rem;
        font-size: 0.875rem;
    }

    .chart-bar-label {
        min-width: 80px;
        color: #6b7280;
    }

    .chart-bar-visual {
        flex: 1;
        height: 8px;
        background: #e5e7eb;
        border-radius: 4px;
        overflow: hidden;
        margin: 0 1rem;
    }

    .chart-bar-fill {
        height: 100%;
        background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
        border-radius: 4px;
        transition: width 0.3s ease;
    }

    .chart-bar-value {
        min-width: 40px;
        text-align: right;
        font-weight: 700;
        color: #1f2937;
    }

    .action-button {
        padding: 0.875rem;
        border-radius: 12px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        transition: all 0.2s;
        text-decoration: none;
        margin-bottom: 0.75rem;
    }

    .action-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .action-button-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .action-button-secondary {
        background: #f3f4f6;
        color: #374151;
    }

    .action-button-danger {
        background: #fee;
        color: #dc2626;
    }

    .social-links {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
    }

    .social-link {
        padding: 0.75rem 1.25rem;
        background: #f3f4f6;
        border-radius: 10px;
        text-decoration: none;
        color: #374151;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .social-link:hover {
        background: #e5e7eb;
        transform: translateY(-2px);
    }

    .views-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .views-table thead th {
        background: #f9fafb;
        padding: 0.75rem;
        text-align: left;
        font-size: 0.85rem;
        font-weight: 700;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #e5e7eb;
    }

    .views-table tbody td {
        padding: 0.875rem 0.75rem;
        border-bottom: 1px solid #f3f4f6;
        font-size: 0.9rem;
    }

    .views-table tbody tr:hover {
        background: #f9fafb;
    }

    .viewer-info {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .viewer-name {
        font-weight: 600;
        color: #1f2937;
    }

    .viewer-email {
        font-size: 0.85rem;
        color: #6b7280;
    }

    .ip-badge {
        font-family: 'Monaco', 'Menlo', monospace;
        background: #f3f4f6;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.85rem;
    }

    .empty-views {
        text-align: center;
        padding: 3rem 1rem;
        color: #6b7280;
    }

    .empty-views-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="portfolio-detail-grid">
    <!-- Main Content -->
    <div>
        <!-- Header Card -->
        <div class="detail-card">
            <div class="detail-card-header template-<?php echo e($portfolio->template_id); ?>">
                <div class="detail-avatar-section">
                    <?php if($portfolio->photo_url): ?>
                        <img src="<?php echo e($portfolio->photo_url); ?>" alt="<?php echo e($portfolio->user->name); ?>" class="detail-avatar">
                    <?php else: ?>
                        <div class="detail-avatar-placeholder">
                            <?php echo e(strtoupper(substr($portfolio->user->name, 0, 1))); ?>

                        </div>
                    <?php endif; ?>
                    <div class="detail-user-info">
                        <h2><?php echo e($portfolio->title); ?></h2>
                        <div class="detail-user-meta">
                            <div><strong><?php echo e($portfolio->user->name); ?></strong></div>
                            <div><?php echo e($portfolio->user->email); ?></div>
                        </div>
                        <div class="detail-badges">
                            <span class="detail-badge"><?php echo e(ucfirst($portfolio->template_id)); ?></span>
                            <span class="detail-badge"><?php echo e($portfolio->is_public ? '👁️ Public' : '🔒 Privé'); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="detail-card-body">
                <?php if($portfolio->bio): ?>
                <div style="margin-bottom: 2rem;">
                    <div class="section-title">📝 Bio</div>
                    <p style="color: #6b7280; line-height: 1.6;"><?php echo e($portfolio->bio); ?></p>
                </div>
                <?php endif; ?>

                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Slug</span>
                        <span class="info-value" style="font-family: 'Monaco', 'Menlo', monospace; font-size: 0.9rem;"><?php echo e($portfolio->slug); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Couleur Thème</span>
                        <span class="info-value">
                            <span class="color-swatch" style="background: <?php echo e($portfolio->theme_color); ?>;"></span>
                            <code><?php echo e($portfolio->theme_color); ?></code>
                        </span>
                    </div>
                    <?php if($portfolio->cv_url): ?>
                    <div class="info-item">
                        <span class="info-label">CV</span>
                        <span class="info-value">
                            <a href="<?php echo e($portfolio->cv_url); ?>" target="_blank" style="color: #667eea; font-weight: 600;">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="vertical-align: middle;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Télécharger
                            </a>
                        </span>
                    </div>
                    <?php endif; ?>
                    <div class="info-item">
                        <span class="info-label">URL Publique</span>
                        <span class="info-value">
                            <a href="<?php echo e($portfolio->public_url); ?>" target="_blank" style="color: #667eea; font-weight: 600; word-break: break-all;">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="vertical-align: middle;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                                Voir
                            </a>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Skills -->
        <?php if($portfolio->skills && count($portfolio->skills) > 0): ?>
        <div class="detail-card">
            <div class="detail-card-body">
                <div class="section-title">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                    Compétences (<?php echo e(count($portfolio->skills)); ?>)
                </div>
                <div class="skill-grid">
                    <?php $__currentLoopData = $portfolio->skills; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="skill-badge">
                        <span><?php echo e($skill['name']); ?></span>
                        <span class="skill-level"><?php echo e($skill['level']); ?></span>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Experiences -->
        <?php if($portfolio->experiences && count($portfolio->experiences) > 0): ?>
        <div class="detail-card">
            <div class="detail-card-body">
                <div class="section-title">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Expériences Professionnelles (<?php echo e(count($portfolio->experiences)); ?>)
                </div>
                <?php $__currentLoopData = $portfolio->experiences; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="experience-card">
                    <h4><?php echo e($exp['title']); ?></h4>
                    <div class="experience-meta">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <?php echo e($exp['company']); ?>

                        <span>•</span>
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <?php echo e($exp['duration']); ?>

                    </div>
                    <?php if(isset($exp['description'])): ?>
                    <p style="margin: 0; color: #374151; line-height: 1.6;"><?php echo e($exp['description']); ?></p>
                    <?php endif; ?>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Education -->
        <?php if($portfolio->education && count($portfolio->education) > 0): ?>
        <div class="detail-card">
            <div class="detail-card-body">
                <div class="section-title">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/>
                    </svg>
                    Formation (<?php echo e(count($portfolio->education)); ?>)
                </div>
                <?php $__currentLoopData = $portfolio->education; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $edu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="education-card">
                    <h4><?php echo e($edu['degree']); ?></h4>
                    <div class="education-meta">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <?php echo e($edu['school']); ?>

                        <span>•</span>
                        <?php echo e($edu['year']); ?>

                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Projects -->
        <?php if($portfolio->projects && count($portfolio->projects) > 0): ?>
        <div class="detail-card">
            <div class="detail-card-body">
                <div class="section-title">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    Projets (<?php echo e(count($portfolio->projects)); ?>)
                </div>
                <?php $__currentLoopData = $portfolio->projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="project-card">
                    <h4><?php echo e($project['name']); ?></h4>
                    <p style="margin: 0.75rem 0; color: #374151; line-height: 1.6;"><?php echo e($project['description']); ?></p>
                    <?php if(isset($project['url'])): ?>
                    <a href="<?php echo e($project['url']); ?>" target="_blank" style="color: #667eea; font-weight: 600; text-decoration: none;">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="vertical-align: middle;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                        Voir le projet
                    </a>
                    <?php endif; ?>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Social Links -->
        <?php if($portfolio->social_links && count($portfolio->social_links) > 0): ?>
        <div class="detail-card">
            <div class="detail-card-body">
                <div class="section-title">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                    </svg>
                    Réseaux Sociaux
                </div>
                <div class="social-links">
                    <?php $__currentLoopData = $portfolio->social_links; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $platform => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($url): ?>
                    <a href="<?php echo e($url); ?>" target="_blank" class="social-link">
                        <?php echo e(ucfirst($platform)); ?>

                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                    </a>
                    <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Recent Views -->
        <div class="detail-card">
            <div class="detail-card-header template-<?php echo e($portfolio->template_id); ?>">
                <h3>
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    Vues Récentes (50 dernières)
                </h3>
            </div>
            <div style="overflow-x: auto;">
                <table class="views-table">
                    <thead>
                        <tr>
                            <th>Date & Heure</th>
                            <th>Visiteur</th>
                            <th>IP</th>
                            <th>Provenance</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $portfolio->views; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $view): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td style="white-space: nowrap;"><?php echo e($view->viewed_at->format('d/m/Y H:i')); ?></td>
                            <td>
                                <?php if($view->viewer): ?>
                                <div class="viewer-info">
                                    <span class="viewer-name"><?php echo e($view->viewer->name); ?></span>
                                    <span class="viewer-email"><?php echo e($view->viewer->email); ?></span>
                                </div>
                                <?php else: ?>
                                <span style="color: #9ca3af;">Visiteur anonyme</span>
                                <?php endif; ?>
                            </td>
                            <td><span class="ip-badge"><?php echo e($view->viewer_ip ?? '-'); ?></span></td>
                            <td>
                                <?php if($view->referer): ?>
                                <a href="<?php echo e($view->referer); ?>" target="_blank" style="color: #667eea; font-size: 0.85rem;"><?php echo e(Str::limit($view->referer, 40)); ?></a>
                                <?php else: ?>
                                <span style="color: #9ca3af;">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="4">
                                <div class="empty-views">
                                    <div class="empty-views-icon">👀</div>
                                    <p>Aucune vue enregistrée pour le moment</p>
                                </div>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div>
        <!-- Stats -->
        <div class="stat-card-detail">
            <div class="section-title">📈 Statistiques</div>
            <div style="margin-bottom: 1.5rem;">
                <div class="stat-description">Total Vues</div>
                <div class="stat-number"><?php echo e(number_format($portfolio->view_count)); ?></div>
            </div>
            <div style="margin-bottom: 1.5rem;">
                <div class="stat-description">Vues (7 derniers jours)</div>
                <div class="stat-number" style="color: #10b981;"><?php echo e($portfolio->getViewsInLastDays(7)); ?></div>
            </div>
            <div style="margin-bottom: 1.5rem;">
                <div class="stat-description">Vues (30 derniers jours)</div>
                <div class="stat-number" style="color: #f59e0b;"><?php echo e($portfolio->getViewsInLastDays(30)); ?></div>
            </div>
            <div>
                <div class="stat-description">Visiteurs Uniques</div>
                <div class="stat-number" style="color: #3b82f6;"><?php echo e($portfolio->getUniqueViewersCount()); ?></div>
            </div>
        </div>

        <!-- Views by Day Chart -->
        <?php if($viewsByDay->count() > 0): ?>
        <div class="stat-card-detail">
            <div class="section-title">📅 Vues par Jour</div>
            <div class="views-chart">
                <?php $__currentLoopData = $viewsByDay; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="chart-bar">
                    <span class="chart-bar-label"><?php echo e(\Carbon\Carbon::parse($stat->date)->format('d/m')); ?></span>
                    <div class="chart-bar-visual">
                        <div class="chart-bar-fill" style="width: <?php echo e(min(100, ($stat->count / max($viewsByDay->max('count'), 1)) * 100)); ?>%;"></div>
                    </div>
                    <span class="chart-bar-value"><?php echo e($stat->count); ?></span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Info -->
        <div class="stat-card-detail">
            <div class="section-title">ℹ️ Informations</div>
            <div style="margin-bottom: 1rem;">
                <div class="stat-description">Créé le</div>
                <div style="font-weight: 600; color: #1f2937; margin-top: 0.25rem;"><?php echo e($portfolio->created_at->format('d/m/Y à H:i')); ?></div>
            </div>
            <div>
                <div class="stat-description">Modifié le</div>
                <div style="font-weight: 600; color: #1f2937; margin-top: 0.25rem;"><?php echo e($portfolio->updated_at->format('d/m/Y à H:i')); ?></div>
            </div>
        </div>

        <!-- Actions -->
        <div class="stat-card-detail">
            <div class="section-title">⚡ Actions</div>
            <a href="<?php echo e($portfolio->public_url); ?>" target="_blank" class="action-button action-button-primary">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
                Voir le Portfolio
            </a>

            <form method="POST" action="<?php echo e(route('admin.portfolios.toggle-visibility', $portfolio)); ?>">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PATCH'); ?>
                <button type="submit" class="action-button action-button-secondary">
                    <?php if($portfolio->is_public): ?>
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                        </svg>
                        Rendre Privé
                    <?php else: ?>
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        Rendre Public
                    <?php endif; ?>
                </button>
            </form>

            <form method="POST" action="<?php echo e(route('admin.portfolios.destroy', $portfolio)); ?>" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce portfolio ?')">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <button type="submit" class="action-button action-button-danger">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Supprimer Portfolio
                </button>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/clients/client1/web19/web/estuaire-emploie-backend/resources/views/admin/portfolios/show.blade.php ENDPATH**/ ?>