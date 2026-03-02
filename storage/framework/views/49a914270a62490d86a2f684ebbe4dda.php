<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Estuaire Emploi Admin</title>

    <!-- Material Design Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #E31E24;
            --primary-dark: #B71C1C;
            --secondary: #0091D5;
            --secondary-light: #4FC3F7;
            --tertiary: #7B1FA2;
            --accent: #F39C12;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --gray-50: #fafafa;
            --gray-100: #f5f5f5;
            --gray-200: #eeeeee;
            --gray-300: #e0e0e0;
            --gray-400: #bdbdbd;
            --gray-500: #9e9e9e;
            --gray-600: #757575;
            --gray-700: #616161;
            --gray-800: #424242;
            --gray-900: #212121;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, var(--primary) 0%, var(--tertiary) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle at 30% 50%, rgba(0, 145, 213, 0.15), transparent 50%),
                        radial-gradient(circle at 70% 50%, rgba(243, 156, 18, 0.1), transparent 50%);
            animation: float 20s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            33% { transform: translate(30px, -30px) rotate(120deg); }
            66% { transform: translate(-20px, 20px) rotate(240deg); }
        }

        .login-container {
            background: white;
            border-radius: 24px;
            box-shadow: 0 24px 48px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            max-width: 1100px;
            width: 100%;
            display: grid;
            grid-template-columns: 45% 55%;
            min-height: 650px;
            position: relative;
            z-index: 1;
        }

        .login-left {
            background: linear-gradient(135deg, var(--primary) 0%, var(--tertiary) 100%);
            padding: 60px 50px;
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
            top: -100px;
            right: -100px;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
        }

        .login-left::after {
            content: '';
            position: absolute;
            bottom: -150px;
            left: -100px;
            width: 400px;
            height: 400px;
            background: rgba(0, 145, 213, 0.15);
            border-radius: 50%;
        }

        .logo-container {
            position: relative;
            z-index: 1;
            margin-bottom: 40px;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .logo img {
            width: 80px;
            height: auto;
            filter: brightness(0) invert(1);
        }

        .logo-text h1 {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 4px;
            color: white;
        }

        .logo-text p {
            font-size: 0.875rem;
            opacity: 0.9;
            font-weight: 400;
        }

        .welcome-section {
            position: relative;
            z-index: 1;
        }

        .welcome-text {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 16px;
            line-height: 1.3;
        }

        .welcome-description {
            font-size: 1rem;
            opacity: 0.95;
            line-height: 1.7;
            margin-bottom: 40px;
        }

        .features {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .feature {
            display: flex;
            align-items: flex-start;
            gap: 16px;
            padding: 16px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }

        .feature:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateX(8px);
        }

        .feature-icon {
            width: 48px;
            height: 48px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .feature-icon i {
            font-size: 24px;
            color: white;
        }

        .feature-content h3 {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .feature-content p {
            font-size: 0.875rem;
            opacity: 0.9;
            line-height: 1.5;
        }

        .login-right {
            padding: 60px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: white;
        }

        .login-header {
            margin-bottom: 40px;
        }

        .login-header h2 {
            font-size: 2rem;
            color: var(--gray-900);
            margin-bottom: 8px;
            font-weight: 700;
        }

        .login-header p {
            color: var(--gray-600);
            font-size: 0.95rem;
        }

        .alert {
            padding: 14px 16px;
            border-radius: 12px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 0.9rem;
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

        .alert i {
            font-size: 20px;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--gray-700);
            font-weight: 500;
            font-size: 0.9rem;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-400);
            font-size: 20px;
            z-index: 1;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 14px 16px 14px 50px;
            border: 2px solid var(--gray-200);
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: var(--gray-50);
            font-family: inherit;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: var(--primary);
            background: white;
            box-shadow: 0 0 0 4px rgba(227, 30, 36, 0.1);
        }

        input[type="email"]:focus + .input-icon,
        input[type="password"]:focus + .input-icon {
            color: var(--primary);
        }

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .remember-me input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: var(--primary);
        }

        .remember-me label {
            color: var(--gray-600);
            font-size: 0.9rem;
            cursor: pointer;
            margin: 0;
            user-select: none;
        }

        .btn-login {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--tertiary) 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 16px rgba(227, 30, 36, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-family: inherit;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 24px rgba(227, 30, 36, 0.4);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-login i {
            font-size: 18px;
        }

        .footer {
            margin-top: 32px;
            text-align: center;
            padding-top: 24px;
            border-top: 1px solid var(--gray-200);
        }

        .footer p {
            color: var(--gray-500);
            font-size: 0.85rem;
        }

        @media (max-width: 968px) {
            .login-container {
                grid-template-columns: 1fr;
                max-width: 500px;
            }

            .login-left {
                padding: 50px 40px;
                min-height: auto;
            }

            .login-right {
                padding: 50px 40px;
            }

            .welcome-text {
                font-size: 1.75rem;
            }

            .features {
                display: none;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 10px;
            }

            .login-container {
                border-radius: 16px;
            }

            .login-left,
            .login-right {
                padding: 40px 30px;
            }

            .login-header h2 {
                font-size: 1.75rem;
            }

            .welcome-text {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-left">
            <div class="logo-container">
                <div class="logo">
                    <img src="<?php echo e(asset('images/logo-estuaire-emploi.png')); ?>" alt="Estuaire Emploi Logo">
                    <div class="logo-text">
                        <h1>Estuaire Emploi</h1>
                        <p>Administration</p>
                    </div>
                </div>
            </div>

            <div class="welcome-section">
                <h1 class="welcome-text">Bienvenue sur votre espace administrateur</h1>
                <p class="welcome-description">
                    Gérez efficacement votre plateforme de recrutement avec des outils puissants et intuitifs.
                </p>

                <div class="features">
                    <div class="feature">
                        <div class="feature-icon">
                            <i class="mdi mdi-chart-line"></i>
                        </div>
                        <div class="feature-content">
                            <h3>Tableau de bord analytique</h3>
                            <p>Visualisez toutes vos données en temps réel</p>
                        </div>
                    </div>

                    <div class="feature">
                        <div class="feature-icon">
                            <i class="mdi mdi-office-building"></i>
                        </div>
                        <div class="feature-content">
                            <h3>Gestion des entreprises</h3>
                            <p>Contrôlez et validez les recruteurs</p>
                        </div>
                    </div>

                    <div class="feature">
                        <div class="feature-icon">
                            <i class="mdi mdi-briefcase-check"></i>
                        </div>
                        <div class="feature-content">
                            <h3>Gestion des offres</h3>
                            <p>Modérez les annonces d'emploi publiées</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="login-right">
            <div class="login-header">
                <h2>Connexion</h2>
                <p>Accédez au panneau d'administration</p>
            </div>

            <?php if($errors->any()): ?>
                <div class="alert alert-danger">
                    <i class="mdi mdi-alert-circle"></i>
                    <div>
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php echo e($error); ?>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if(session('success')): ?>
                <div class="alert alert-success">
                    <i class="mdi mdi-check-circle"></i>
                    <div><?php echo e(session('success')); ?></div>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('admin.login.submit')); ?>">
                <?php echo csrf_field(); ?>

                <div class="form-group">
                    <label for="email">Adresse email</label>
                    <div class="input-wrapper">
                        <input type="email"
                               id="email"
                               name="email"
                               value="<?php echo e(old('email')); ?>"
                               placeholder="admin@example.com"
                               required
                               autofocus>
                        <i class="mdi mdi-email-outline input-icon"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <div class="input-wrapper">
                        <input type="password"
                               id="password"
                               name="password"
                               placeholder="Entrez votre mot de passe"
                               required>
                        <i class="mdi mdi-lock-outline input-icon"></i>
                    </div>
                </div>

                <div class="remember-forgot">
                    <div class="remember-me">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Se souvenir de moi</label>
                    </div>
                </div>

                <button type="submit" class="btn-login">
                    <i class="mdi mdi-login"></i>
                    Se connecter
                </button>
            </form>

            <div class="footer">
                <p>Estuaire Emploi &copy; <?php echo e(date('Y')); ?> - Tous droits réservés</p>
            </div>
        </div>
    </div>
</body>
</html>
<?php /**PATH /Users/macbookpro/Desktop/Developments/INSAM-DEV/E-Emploie-Backend/estuaire-emploie-backend/resources/views/admin/auth/login.blade.php ENDPATH**/ ?>