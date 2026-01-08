<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Estuaire Emploie Admin</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #2563eb;
            --primary-dark: #1e40af;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            max-width: 1000px;
            width: 100%;
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 600px;
        }

        .login-left {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 60px 40px;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .login-left::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: pulse 15s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .logo {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
        }

        .logo-icon {
            display: inline-block;
            width: 50px;
            height: 50px;
            background: white;
            border-radius: 12px;
            margin-right: 10px;
            vertical-align: middle;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .welcome-text {
            font-size: 1.8rem;
            margin-bottom: 15px;
            position: relative;
            z-index: 1;
        }

        .welcome-description {
            font-size: 1.1rem;
            opacity: 0.9;
            line-height: 1.6;
            position: relative;
            z-index: 1;
        }

        .features {
            margin-top: 40px;
            position: relative;
            z-index: 1;
        }

        .feature {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .feature-icon {
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.2);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 20px;
        }

        .login-right {
            padding: 60px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-header {
            margin-bottom: 40px;
        }

        .login-header h2 {
            font-size: 2rem;
            color: var(--gray-900);
            margin-bottom: 10px;
        }

        .login-header p {
            color: var(--gray-600);
            font-size: 1rem;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--gray-700);
            font-weight: 500;
            font-size: 0.95rem;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-400);
            font-size: 18px;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 14px 15px 14px 45px;
            border: 2px solid var(--gray-200);
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s;
            background: var(--gray-50);
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: var(--primary);
            background: white;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }

        .error-message {
            color: var(--danger);
            font-size: 0.875rem;
            margin-top: 8px;
            display: flex;
            align-items: center;
        }

        .error-message::before {
            content: '⚠';
            margin-right: 6px;
        }

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .remember-me {
            display: flex;
            align-items: center;
        }

        .remember-me input[type="checkbox"] {
            width: 18px;
            height: 18px;
            margin-right: 8px;
            cursor: pointer;
        }

        .remember-me label {
            color: var(--gray-600);
            font-size: 0.9rem;
            cursor: pointer;
            margin: 0;
        }

        .btn-login {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .divider {
            text-align: center;
            margin: 30px 0;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            width: 100%;
            height: 1px;
            background: var(--gray-200);
        }

        .divider span {
            background: white;
            padding: 0 20px;
            position: relative;
            color: var(--gray-500);
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .login-container {
                grid-template-columns: 1fr;
            }

            .login-left {
                padding: 40px 30px;
                min-height: 300px;
            }

            .login-right {
                padding: 40px 30px;
            }

            .logo {
                font-size: 2rem;
            }

            .welcome-text {
                font-size: 1.5rem;
            }
        }

        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }

        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #6ee7b7;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-left">
            <div class="logo">
                <span class="logo-icon">💼</span>
                Estuaire Emploie
            </div>
            <h1 class="welcome-text">Bienvenue sur votre espace administrateur</h1>
            <p class="welcome-description">
                Gérez efficacement votre plateforme de recrutement. Accédez à tous vos outils de gestion en un seul endroit.
            </p>

            <div class="features">
                <div class="feature">
                    <div class="feature-icon">📊</div>
                    <div>
                        <strong>Tableau de bord complet</strong><br>
                        <small>Vue d'ensemble de toutes les activités</small>
                    </div>
                </div>
                <div class="feature">
                    <div class="feature-icon">🏢</div>
                    <div>
                        <strong>Gestion des entreprises</strong><br>
                        <small>Validation et suivi des recruteurs</small>
                    </div>
                </div>
                <div class="feature">
                    <div class="feature-icon">💼</div>
                    <div>
                        <strong>Gestion des offres</strong><br>
                        <small>Contrôle total des annonces d'emploi</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="login-right">
            <div class="login-header">
                <h2>Se connecter</h2>
                <p>Entrez vos identifiants pour accéder au panel d'administration</p>
            </div>

            <?php if($errors->any()): ?>
                <div class="alert alert-danger">
                    <strong>⚠</strong>
                    <div style="margin-left: 10px;">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php echo e($error); ?>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if(session('success')): ?>
                <div class="alert alert-success">
                    <strong>✓</strong>
                    <div style="margin-left: 10px;"><?php echo e(session('success')); ?></div>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('admin.login.submit')); ?>">
                <?php echo csrf_field(); ?>

                <div class="form-group">
                    <label for="email">Adresse email</label>
                    <div class="input-wrapper">
                        <span class="input-icon">📧</span>
                        <input type="email"
                               id="email"
                               name="email"
                               value="<?php echo e(old('email')); ?>"
                               placeholder="admin@example.com"
                               required
                               autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <div class="input-wrapper">
                        <span class="input-icon">🔒</span>
                        <input type="password"
                               id="password"
                               name="password"
                               placeholder="••••••••"
                               required>
                    </div>
                </div>

                <div class="remember-forgot">
                    <div class="remember-me">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Se souvenir de moi</label>
                    </div>
                </div>

                <button type="submit" class="btn-login">
                    Se connecter
                </button>
            </form>

            <div class="divider">
                <span>Estuaire Emploie © <?php echo e(date('Y')); ?></span>
            </div>
        </div>
    </div>
</body>
</html>
<?php /**PATH /home/djstar-service/Documents/Project/My_project/EstuaireEmploieProd/estuaire-emploie-backend/resources/views/admin/auth/login.blade.php ENDPATH**/ ?>