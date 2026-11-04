<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0b0e14">
    <meta name="color-scheme" content="dark">
    <title>Connexion · Estuaire Emploi Admin</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css">

    <style>
        :root {
            color-scheme: dark;
            --bg: #0b0e14;
            --surface: #11151c;
            --surface-2: #161b24;
            --border: #232a36;
            --border-strong: #2f3846;
            --text: #e4e8ef;
            --text-2: #b9c1cd;
            --muted: #8a94a6;
            --subtle: #667084;
            --primary: #7ea6f4;
            --primary-solid: #3f72d6;
            --primary-hover: #4a7fe0;
            --primary-soft: rgb(91 141 239 / 0.12);
            --primary-ring: rgb(91 141 239 / 0.28);
            --success: #6cc39b;
            --success-soft: rgb(79 178 134 / 0.12);
            --danger: #ec8b8a;
            --danger-soft: rgb(226 112 111 / 0.12);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        html, body { height: 100%; }

        body {
            font-family: 'Inter', ui-sans-serif, system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
            background: var(--bg);
            color: var(--text);
            -webkit-font-smoothing: antialiased;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
            min-height: 100vh;
            min-height: 100dvh;
        }

        /* Halo discret derrière la carte */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background: radial-gradient(600px circle at 50% 0%, rgb(63 114 214 / 0.10), transparent 70%);
            pointer-events: none;
        }

        .wrap {
            position: relative;
            width: 100%;
            max-width: 400px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 28px;
        }

        .brand-mark {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: var(--primary-soft);
            border: 1px solid rgb(91 141 239 / 0.2);
            display: grid;
            place-items: center;
            flex-shrink: 0;
        }

        .brand-mark img {
            width: 24px;
            height: 24px;
            object-fit: contain;
            filter: brightness(0) invert(1);
            opacity: .9;
        }

        .brand-name { font-size: 15px; font-weight: 600; line-height: 1.2; }
        .brand-sub { font-size: 12.5px; color: var(--subtle); }

        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 28px;
        }

        h1 {
            font-size: 20px;
            font-weight: 600;
            letter-spacing: -0.01em;
            margin-bottom: 6px;
        }

        .lead {
            font-size: 14px;
            color: var(--muted);
            margin-bottom: 24px;
        }

        .alert {
            display: flex;
            gap: 10px;
            align-items: flex-start;
            padding: 11px 12px;
            border-radius: 10px;
            font-size: 13.5px;
            line-height: 1.45;
            margin-bottom: 20px;
            border: 1px solid;
        }

        .alert .mdi { font-size: 18px; line-height: 1.1; }
        .alert-danger { background: var(--danger-soft); border-color: rgb(226 112 111 / 0.28); color: var(--danger); }
        .alert-success { background: var(--success-soft); border-color: rgb(79 178 134 / 0.28); color: var(--success); }

        .field { margin-bottom: 16px; }

        label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: var(--text-2);
            margin-bottom: 6px;
        }

        .input {
            position: relative;
        }

        .input .mdi {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 18px;
            color: var(--subtle);
            pointer-events: none;
            transition: color .15s;
        }

        .input input {
            width: 100%;
            height: 44px;
            padding: 0 12px 0 40px;
            font: inherit;
            font-size: 14.5px;
            color: var(--text);
            background: var(--surface-2);
            border: 1px solid var(--border-strong);
            border-radius: 10px;
            transition: border-color .15s, box-shadow .15s;
        }

        .input input::placeholder { color: var(--subtle); }

        .input input:hover { border-color: #3a4352; }

        .input input:focus {
            outline: none;
            border-color: var(--primary-hover);
            box-shadow: 0 0 0 3px var(--primary-ring);
        }

        .input:focus-within .mdi { color: var(--primary); }

        .input .toggle {
            position: absolute;
            right: 6px;
            top: 50%;
            transform: translateY(-50%);
            width: 32px;
            height: 32px;
            border: 0;
            border-radius: 8px;
            background: transparent;
            color: var(--subtle);
            cursor: pointer;
            display: grid;
            place-items: center;
        }

        .input .toggle:hover { color: var(--text-2); background: rgb(255 255 255 / 0.04); }
        .input .toggle .mdi { position: static; transform: none; pointer-events: none; }
        .input input.has-toggle { padding-right: 44px; }

        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus {
            -webkit-text-fill-color: var(--text);
            -webkit-box-shadow: 0 0 0 1000px var(--surface-2) inset;
            caret-color: var(--text);
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 4px 0 22px;
            font-size: 13.5px;
            color: var(--muted);
            cursor: pointer;
            user-select: none;
            width: fit-content;
        }

        .remember input {
            width: 16px;
            height: 16px;
            accent-color: var(--primary-solid);
            cursor: pointer;
        }

        .btn {
            width: 100%;
            height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font: inherit;
            font-size: 14.5px;
            font-weight: 600;
            color: #fff;
            background: var(--primary-solid);
            border: 0;
            border-radius: 10px;
            cursor: pointer;
            transition: background-color .15s;
        }

        .btn:hover { background: var(--primary-hover); }
        .btn:focus-visible { outline: 2px solid var(--primary); outline-offset: 2px; }
        .btn[disabled] { opacity: .7; cursor: progress; }

        .spinner {
            width: 16px;
            height: 16px;
            border: 2px solid rgb(255 255 255 / 0.35);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin .7s linear infinite;
            display: none;
        }

        .btn[disabled] .spinner { display: inline-block; }
        .btn[disabled] .mdi { display: none; }

        @keyframes spin { to { transform: rotate(360deg); } }

        .foot {
            margin-top: 20px;
            text-align: center;
            font-size: 12.5px;
            color: var(--subtle);
        }

        @media (max-width: 420px) {
            .card { padding: 22px 18px; }
        }
    </style>
</head>
<body>
    <main class="wrap">
        <div class="brand">
            <span class="brand-mark"><img src="{{ asset('images/logo-estuaire-emploi.png') }}" alt=""></span>
            <div>
                <div class="brand-name">Estuaire Emploi</div>
                <div class="brand-sub">Administration</div>
            </div>
        </div>

        <div class="card">
            <h1>Connexion</h1>
            <p class="lead">Connectez-vous pour accéder au panneau d'administration.</p>

            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    <i class="mdi mdi-alert-circle-outline"></i>
                    <div>
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success" role="status">
                    <i class="mdi mdi-check-circle-outline"></i>
                    <div>{{ session('success') }}</div>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.submit') }}" id="login-form" novalidate>
                @csrf

                <div class="field">
                    <label for="email">Adresse email</label>
                    <div class="input">
                        <input type="email"
                               id="email"
                               name="email"
                               value="{{ old('email') }}"
                               placeholder="admin@exemple.com"
                               autocomplete="username"
                               required
                               autofocus>
                        <i class="mdi mdi-email-outline"></i>
                    </div>
                </div>

                <div class="field">
                    <label for="password">Mot de passe</label>
                    <div class="input">
                        <input type="password"
                               id="password"
                               name="password"
                               class="has-toggle"
                               placeholder="Votre mot de passe"
                               autocomplete="current-password"
                               required>
                        <i class="mdi mdi-lock-outline"></i>
                        <button type="button" class="toggle" id="toggle-password" aria-label="Afficher le mot de passe" aria-pressed="false">
                            <i class="mdi mdi-eye-outline"></i>
                        </button>
                    </div>
                </div>

                <label class="remember" for="remember">
                    <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    Se souvenir de moi
                </label>

                <button type="submit" class="btn" id="login-btn">
                    <span class="spinner" aria-hidden="true"></span>
                    <i class="mdi mdi-login"></i>
                    <span>Se connecter</span>
                </button>
            </form>
        </div>

        <p class="foot">Estuaire Emploi &copy; {{ date('Y') }}</p>
    </main>

    <script>
        (function () {
            var pwd = document.getElementById('password');
            var toggle = document.getElementById('toggle-password');
            toggle.addEventListener('click', function () {
                var show = pwd.type === 'password';
                pwd.type = show ? 'text' : 'password';
                toggle.setAttribute('aria-pressed', show ? 'true' : 'false');
                toggle.setAttribute('aria-label', show ? 'Masquer le mot de passe' : 'Afficher le mot de passe');
                toggle.firstElementChild.className = 'mdi ' + (show ? 'mdi-eye-off-outline' : 'mdi-eye-outline');
                pwd.focus();
            });

            var form = document.getElementById('login-form');
            form.addEventListener('submit', function (e) {
                if (!form.checkValidity()) {
                    e.preventDefault();
                    form.reportValidity();
                    return;
                }
                document.getElementById('login-btn').disabled = true;
            });
        })();
    </script>
</body>
</html>
