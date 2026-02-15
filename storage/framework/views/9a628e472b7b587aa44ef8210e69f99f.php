<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($portfolio->title); ?> - <?php echo e($portfolio->user->name); ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Fira Code', 'Monaco', 'Courier New', monospace;
            background: #0d1117;
            color: #c9d1d9;
            line-height: 1.6;
        }
        .container { max-width: 1000px; margin: 0 auto; padding: 2rem; }
        .terminal {
            background: #161b22;
            border: 1px solid #30363d;
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 1.5rem;
        }
        .terminal-header {
            display: flex;
            gap: 8px;
            margin-bottom: 1.5rem;
        }
        .terminal-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }
        .dot-red { background: #ff5f56; }
        .dot-yellow { background: #ffbd2e; }
        .dot-green { background: #27c93f; }
        h1 {
            color: <?php echo e($portfolio->theme_color); ?>;
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }
        .prompt {
            color: <?php echo e($portfolio->theme_color); ?>;
            font-weight: bold;
        }
        .section-title {
            color: #58a6ff;
            font-size: 1.5rem;
            margin: 2rem 0 1rem;
            border-bottom: 2px solid #30363d;
            padding-bottom: 0.5rem;
        }
        .skill-badge {
            display: inline-block;
            background: <?php echo e($portfolio->theme_color); ?>20;
            border: 1px solid <?php echo e($portfolio->theme_color); ?>;
            color: <?php echo e($portfolio->theme_color); ?>;
            padding: 0.4rem 1rem;
            border-radius: 6px;
            margin: 0.25rem;
            font-size: 0.9rem;
        }
        .card {
            background: #161b22;
            border: 1px solid #30363d;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1rem;
        }
        .card h3 {
            color: #58a6ff;
            margin-bottom: 0.5rem;
        }
        .meta {
            color: #8b949e;
            font-size: 0.9rem;
        }
        a {
            color: <?php echo e($portfolio->theme_color); ?>;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="terminal">
            <div class="terminal-header">
                <div class="terminal-dot dot-red"></div>
                <div class="terminal-dot dot-yellow"></div>
                <div class="terminal-dot dot-green"></div>
            </div>
            <div>
                <span class="prompt">$</span> cat portfolio.txt<br><br>
                <h1>> <?php echo e($portfolio->user->name); ?></h1>
                <p style="font-size: 1.25rem; color: #8b949e;"><?php echo e($portfolio->title); ?></p>
                <?php if($portfolio->bio): ?>
                    <p style="margin-top: 1rem;"><?php echo e($portfolio->bio); ?></p>
                <?php endif; ?>
                <?php if($portfolio->social_links && count($portfolio->social_links) > 0): ?>
                    <div style="margin-top: 1.5rem;">
                        <?php $__currentLoopData = $portfolio->social_links; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $platform => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($url): ?>
                                <a href="<?php echo e($url); ?>" target="_blank" style="margin-right: 1.5rem;">
                                    <span class="prompt">#</span> <?php echo e($platform); ?>

                                </a>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if($portfolio->skills && count($portfolio->skills) > 0): ?>
            <div class="section-title">
                <span class="prompt">></span> Skills
            </div>
            <div style="margin-bottom: 2rem;">
                <?php $__currentLoopData = $portfolio->skills; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <span class="skill-badge"><?php echo e($skill['name']); ?></span>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>

        <?php if($portfolio->experiences && count($portfolio->experiences) > 0): ?>
            <div class="section-title">
                <span class="prompt">></span> Experience
            </div>
            <?php $__currentLoopData = $portfolio->experiences; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="card">
                    <h3><?php echo e($exp['title']); ?></h3>
                    <div class="meta"><?php echo e($exp['company']); ?> | <?php echo e($exp['duration']); ?></div>
                    <?php if(isset($exp['description'])): ?>
                        <p style="margin-top: 0.75rem;"><?php echo e($exp['description']); ?></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>

        <?php if($portfolio->projects && count($portfolio->projects) > 0): ?>
            <div class="section-title">
                <span class="prompt">></span> Projects
            </div>
            <?php $__currentLoopData = $portfolio->projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="card">
                    <h3><?php echo e($project['name']); ?></h3>
                    <p style="margin: 0.75rem 0;"><?php echo e($project['description']); ?></p>
                    <?php if(isset($project['url'])): ?>
                        <a href="<?php echo e($project['url']); ?>" target="_blank">View Project →</a>
                    <?php endif; ?>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>

        <?php if($portfolio->cv_url): ?>
            <div style="text-align: center; margin: 3rem 0;">
                <a href="<?php echo e($portfolio->cv_url); ?>" style="display: inline-block; background: <?php echo e($portfolio->theme_color); ?>; color: #0d1117; padding: 1rem 2.5rem; border-radius: 6px; font-weight: bold;">
                    <span class="prompt">$</span> download-cv
                </a>
            </div>
        <?php endif; ?>

        <div style="text-align: center; padding: 2rem; color: #8b949e; border-top: 1px solid #30363d; margin-top: 3rem;">
            <span class="prompt">#</span> <?php echo e($portfolio->user->email); ?>

        </div>
    </div>
</body>
</html>
<?php /**PATH /var/www/clients/client1/web19/web/estuaire-emploie-backend/resources/views/portfolio/templates/tech.blade.php ENDPATH**/ ?>