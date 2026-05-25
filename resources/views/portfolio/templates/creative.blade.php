<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $portfolio->title }} - {{ $portfolio->user->name }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, {{ $portfolio->theme_color }}15 0%, {{ $portfolio->theme_color }}05 100%);
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
            border: 6px solid {{ $portfolio->theme_color }};
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        h1 {
            font-size: 3rem;
            background: linear-gradient(135deg, {{ $portfolio->theme_color }}, {{ $portfolio->theme_color }}cc);
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
            color: {{ $portfolio->theme_color }};
            margin-bottom: 1.5rem;
        }
        .skill-item {
            display: inline-block;
            background: linear-gradient(135deg, {{ $portfolio->theme_color }}, {{ $portfolio->theme_color }}dd);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            margin: 0.5rem;
            font-weight: 600;
        }
        .timeline-item {
            padding: 1.5rem;
            border-left: 4px solid {{ $portfolio->theme_color }};
            margin-bottom: 1.5rem;
            background: {{ $portfolio->theme_color }}08;
            border-radius: 0 12px 12px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <header class="header">
            @if($portfolio->photo_url)
                <img src="{{ $portfolio->photo_url }}" alt="{{ $portfolio->user->name }}" class="profile-photo">
            @endif
            <h1>{{ $portfolio->user->name }}</h1>
            <p style="font-size: 1.5rem; color: #718096; font-weight: 600;">{{ $portfolio->title }}</p>
            <p style="max-width: 600px; margin: 1rem auto; color: #4a5568;">{{ $portfolio->bio }}</p>
        </header>

        @if($portfolio->skills && count($portfolio->skills) > 0)
            <section class="section">
                <h2 class="section-title">✨ Compétences</h2>
                <div>
                    @foreach($portfolio->skills as $skill)
                        <span class="skill-item">{{ $skill['name'] }}</span>
                    @endforeach
                </div>
            </section>
        @endif

        @if($portfolio->experiences && count($portfolio->experiences) > 0)
            <section class="section">
                <h2 class="section-title">💼 Expérience</h2>
                @foreach($portfolio->experiences as $exp)
                    <div class="timeline-item">
                        <h3 style="font-size: 1.25rem; margin-bottom: 0.5rem;">{{ $exp['title'] }}</h3>
                        <p style="color: #718096;">{{ $exp['company'] }} • {{ $exp['duration'] }}</p>
                        @if(isset($exp['description']))<p style="margin-top: 0.75rem;">{{ $exp['description'] }}</p>@endif
                    </div>
                @endforeach
            </section>
        @endif

        @if($portfolio->projects && count($portfolio->projects) > 0)
            <section class="section">
                <h2 class="section-title">🚀 Projets</h2>
                @foreach($portfolio->projects as $project)
                    <div class="timeline-item">
                        <h3>{{ $project['name'] }}</h3>
                        <p>{{ $project['description'] }}</p>
                        @if(isset($project['url']))
                            <a href="{{ $project['url'] }}" target="_blank" style="color: {{ $portfolio->theme_color }}; font-weight: 600;">Voir →</a>
                        @endif
                    </div>
                @endforeach
            </section>
        @endif

        @if($portfolio->cv_url)
            <div style="text-align: center; padding: 3rem;">
                <a href="{{ $portfolio->cv_url }}" style="display: inline-block; padding: 1.25rem 3rem; background: {{ $portfolio->theme_color }}; color: white; text-decoration: none; border-radius: 50px; font-weight: 700; font-size: 1.1rem; box-shadow: 0 10px 30px {{ $portfolio->theme_color }}50;">
                    📥 Télécharger mon CV
                </a>
            </div>
        @endif
    </div>
</body>
</html>
