<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($portfolio->title); ?> - <?php echo e($portfolio->user->name); ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, <?php echo e($portfolio->theme_color); ?>15 0%, <?php echo e($portfolio->theme_color); ?>05 100%);
            color: #2d3748;
        }
        .container { max-width: 1200px; margin: 0 auto; padding: 2rem; }
        .header {
            text-align: center;
            padding: 4rem 0;
            background: white;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        .profile-photo {
            width: 180px;
            height: 180px;
            border-radius: 50%;
            object-fit: cover;
            border: 6px solid <?php echo e($portfolio->theme_color); ?>;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        h1 {
            font-size: 3rem;
            background: linear-gradient(135deg, <?php echo e($portfolio->theme_color); ?>, <?php echo e($portfolio->theme_color); ?>cc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin: 1.5rem 0 0.5rem;
        }
        .section {
            background: white;
            padding: 2.5rem;
            border-radius: 20px;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }
        .section-title {
            font-size: 2rem;
            color: <?php echo e($portfolio->theme_color); ?>;
            margin-bottom: 1.5rem;
        }
        .skill-item {
            display: inline-block;
            background: linear-gradient(135deg, <?php echo e($portfolio->theme_color); ?>, <?php echo e($portfolio->theme_color); ?>dd);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            margin: 0.5rem;
            font-weight: 600;
        }
        .timeline-item {
            padding: 1.5rem;
            border-left: 4px solid <?php echo e($portfolio->theme_color); ?>;
            margin-bottom: 1.5rem;
            background: <?php echo e($portfolio->theme_color); ?>08;
            border-radius: 0 12px 12px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <header class="header">
            <?php if($portfolio->photo_url): ?>
                <img src="<?php echo e($portfolio->photo_url); ?>" alt="<?php echo e($portfolio->user->name); ?>" class="profile-photo">
            <?php endif; ?>
            <h1><?php echo e($portfolio->user->name); ?></h1>
            <p style="font-size: 1.5rem; color: #718096; font-weight: 600;"><?php echo e($portfolio->title); ?></p>
            <p style="max-width: 600px; margin: 1rem auto; color: #4a5568;"><?php echo e($portfolio->bio); ?></p>
        </header>

        <?php if($portfolio->skills && count($portfolio->skills) > 0): ?>
            <section class="section">
                <h2 class="section-title">✨ Compétences</h2>
                <div>
                    <?php $__currentLoopData = $portfolio->skills; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <span class="skill-item"><?php echo e($skill['name']); ?></span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </section>
        <?php endif; ?>

        <?php if($portfolio->experiences && count($portfolio->experiences) > 0): ?>
            <section class="section">
                <h2 class="section-title">💼 Expérience</h2>
                <?php $__currentLoopData = $portfolio->experiences; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="timeline-item">
                        <h3 style="font-size: 1.25rem; margin-bottom: 0.5rem;"><?php echo e($exp['title']); ?></h3>
                        <p style="color: #718096;"><?php echo e($exp['company']); ?> • <?php echo e($exp['duration']); ?></p>
                        <?php if(isset($exp['description'])): ?><p style="margin-top: 0.75rem;"><?php echo e($exp['description']); ?></p><?php endif; ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </section>
        <?php endif; ?>

        <?php if($portfolio->projects && count($portfolio->projects) > 0): ?>
            <section class="section">
                <h2 class="section-title">🚀 Projets</h2>
                <?php $__currentLoopData = $portfolio->projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="timeline-item">
                        <h3><?php echo e($project['name']); ?></h3>
                        <p><?php echo e($project['description']); ?></p>
                        <?php if(isset($project['url'])): ?>
                            <a href="<?php echo e($project['url']); ?>" target="_blank" style="color: <?php echo e($portfolio->theme_color); ?>; font-weight: 600;">Voir →</a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </section>
        <?php endif; ?>

        <?php if($portfolio->cv_url): ?>
            <div style="text-align: center; padding: 3rem;">
                <a href="<?php echo e($portfolio->cv_url); ?>" style="display: inline-block; padding: 1.25rem 3rem; background: <?php echo e($portfolio->theme_color); ?>; color: white; text-decoration: none; border-radius: 50px; font-weight: 700; font-size: 1.1rem; box-shadow: 0 10px 30px <?php echo e($portfolio->theme_color); ?>50;">
                    📥 Télécharger mon CV
                </a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
<?php /**PATH /var/www/clients/client1/web19/web/estuaire-emploie-backend/resources/views/portfolio/templates/creative.blade.php ENDPATH**/ ?>