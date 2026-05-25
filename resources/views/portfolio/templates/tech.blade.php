<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $portfolio->title }} - {{ $portfolio->user->name }}</title>
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
            color: {{ $portfolio->theme_color }};
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }
        .prompt {
            color: {{ $portfolio->theme_color }};
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
            background: {{ $portfolio->theme_color }}20;
            border: 1px solid {{ $portfolio->theme_color }};
            color: {{ $portfolio->theme_color }};
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
            color: {{ $portfolio->theme_color }};
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
                <h1>> {{ $portfolio->user->name }}</h1>
                <p style="font-size: 1.25rem; color: #8b949e;">{{ $portfolio->title }}</p>
                @if($portfolio->bio)
                    <p style="margin-top: 1rem;">{{ $portfolio->bio }}</p>
                @endif
                @if($portfolio->social_links && count($portfolio->social_links) > 0)
                    <div style="margin-top: 1.5rem;">
                        @foreach($portfolio->social_links as $platform => $url)
                            @if($url)
                                <a href="{{ $url }}" target="_blank" style="margin-right: 1.5rem;">
                                    <span class="prompt">#</span> {{ $platform }}
                                </a>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        @if($portfolio->skills && count($portfolio->skills) > 0)
            <div class="section-title">
                <span class="prompt">></span> Skills
            </div>
            <div style="margin-bottom: 2rem;">
                @foreach($portfolio->skills as $skill)
                    <span class="skill-badge">{{ $skill['name'] }}</span>
                @endforeach
            </div>
        @endif

        @if($portfolio->experiences && count($portfolio->experiences) > 0)
            <div class="section-title">
                <span class="prompt">></span> Experience
            </div>
            @foreach($portfolio->experiences as $exp)
                <div class="card">
                    <h3>{{ $exp['title'] }}</h3>
                    <div class="meta">{{ $exp['company'] }} | {{ $exp['duration'] }}</div>
                    @if(isset($exp['description']))
                        <p style="margin-top: 0.75rem;">{{ $exp['description'] }}</p>
                    @endif
                </div>
            @endforeach
        @endif

        @if($portfolio->projects && count($portfolio->projects) > 0)
            <div class="section-title">
                <span class="prompt">></span> Projects
            </div>
            @foreach($portfolio->projects as $project)
                <div class="card">
                    <h3>{{ $project['name'] }}</h3>
                    <p style="margin: 0.75rem 0;">{{ $project['description'] }}</p>
                    @if(isset($project['url']))
                        <a href="{{ $project['url'] }}" target="_blank">View Project →</a>
                    @endif
                </div>
            @endforeach
        @endif

        @if($portfolio->cv_url)
            <div style="text-align: center; margin: 3rem 0;">
                <a href="{{ $portfolio->cv_url }}" style="display: inline-block; background: {{ $portfolio->theme_color }}; color: #0d1117; padding: 1rem 2.5rem; border-radius: 6px; font-weight: bold;">
                    <span class="prompt">$</span> download-cv
                </a>
            </div>
        @endif

        <div style="text-align: center; padding: 2rem; color: #8b949e; border-top: 1px solid #30363d; margin-top: 3rem;">
            <span class="prompt">#</span> {{ $portfolio->user->email }}
        </div>
    </div>
</body>
</html>
