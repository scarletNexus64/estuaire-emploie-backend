<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $job->title }} — {{ $job->company->name ?? 'Estuaire Emploi' }}</title>

    @php
        $desc = \Illuminate\Support\Str::limit(strip_tags($job->description), 180);
        $logo = $job->company->logo_url ?? null;
    @endphp

    <meta name="description" content="{{ $desc }}">

    {{-- Open Graph (aperçus WhatsApp, Facebook, LinkedIn, SMS...) --}}
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Estuaire Emploi">
    <meta property="og:title" content="{{ $job->title }} — {{ $job->company->name ?? '' }}">
    <meta property="og:description" content="{{ $desc }}">
    <meta property="og:url" content="{{ url()->current() }}">
    @if($logo)
        <meta property="og:image" content="{{ $logo }}">
    @endif

    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="{{ $job->title }} — {{ $job->company->name ?? '' }}">
    <meta name="twitter:description" content="{{ $desc }}">

    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f4f6f9;
            color: #1a1a2e;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .card {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 10px 40px rgba(0,0,0,.08);
            max-width: 460px;
            width: 100%;
            padding: 32px 28px;
            text-align: center;
        }
        .logo {
            width: 72px;
            height: 72px;
            border-radius: 16px;
            object-fit: cover;
            margin: 0 auto 16px;
            background: #eef1f6;
            display: block;
        }
        h1 { font-size: 20px; line-height: 1.35; margin-bottom: 6px; }
        .company { color: #5a6478; font-size: 15px; margin-bottom: 4px; }
        .meta { color: #8a93a6; font-size: 13px; margin-bottom: 20px; }
        .desc {
            color: #444b5e;
            font-size: 14px;
            line-height: 1.6;
            text-align: left;
            background: #f7f8fb;
            border-radius: 12px;
            padding: 14px 16px;
            margin-bottom: 24px;
            max-height: 160px;
            overflow: hidden;
        }
        .btn {
            display: block;
            width: 100%;
            padding: 14px 18px;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            text-decoration: none;
            margin-bottom: 12px;
            border: none;
            cursor: pointer;
        }
        .btn-primary { background: #1a73e8; color: #fff; }
        .stores { display: flex; gap: 10px; margin-top: 16px; }
        .btn-store {
            flex: 1;
            background: #11131a;
            color: #fff;
            font-size: 13px;
            padding: 12px;
            border-radius: 10px;
            text-decoration: none;
        }
        .hint { color: #8a93a6; font-size: 12px; margin-top: 18px; line-height: 1.5; }
    </style>
</head>
<body>
    <div class="card">
        @if($logo)
            <img class="logo" src="{{ $logo }}" alt="{{ $job->company->name ?? '' }}">
        @endif

        <h1>{{ $job->title }}</h1>
        <p class="company">{{ $job->company->name ?? '' }}</p>
        <p class="meta">
            @if($job->company->city ?? null){{ $job->company->city }} · @endif
            {{ $job->contractType->name ?? '' }}
        </p>

        <div class="desc">{{ $desc }}</div>

        <a href="{{ $deepLink }}" class="btn btn-primary" id="openApp">
            Ouvrir dans l'application
        </a>

        <div class="stores">
            <a class="btn-store" href="{{ $playStoreUrl }}">Google Play</a>
            <a class="btn-store" href="{{ $appStoreUrl }}">App Store</a>
        </div>

        <p class="hint">
            Si l'application ne s'ouvre pas automatiquement, installez Estuaire
            Emploi puis rouvrez ce lien.
        </p>
    </div>

    <script>
        (function () {
            var deepLink = @json($deepLink);
            var playStore = @json($playStoreUrl);
            var appStore  = @json($appStoreUrl);

            var ua = navigator.userAgent || navigator.vendor || '';
            var isAndroid = /android/i.test(ua);
            var isIOS = /iPad|iPhone|iPod/.test(ua) && !window.MSStream;

            // Tentative d'ouverture automatique de l'app (mobile uniquement).
            if (isAndroid || isIOS) {
                var now = Date.now();

                // Si l'app ne prend pas le relais, on bascule vers le store.
                var fallback = setTimeout(function () {
                    if (Date.now() - now < 2000) {
                        window.location = isIOS ? appStore : playStore;
                    }
                }, 1200);

                // Annule le fallback si la page est masquée (= app ouverte).
                document.addEventListener('visibilitychange', function () {
                    if (document.hidden) clearTimeout(fallback);
                });

                window.location = deepLink;
            }

            // Bouton manuel : retente le deeplink.
            document.getElementById('openApp').addEventListener('click', function (e) {
                e.preventDefault();
                window.location = deepLink;
            });
        })();
    </script>
</body>
</html>
