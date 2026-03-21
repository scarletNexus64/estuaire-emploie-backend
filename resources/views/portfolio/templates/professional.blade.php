<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $portfolio->title }} - {{ $portfolio->user->name }}</title>
    <meta name="description" content="{{ Str::limit($portfolio->bio, 160) }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-color: {{ $portfolio->theme_color }};
            --text-dark: #2d3748;
            --text-light: #718096;
            --bg-light: #f7fafc;
            --border-color: #e2e8f0;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: var(--text-dark);
            background: #ffffff;
        }

        .container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 2rem 1.5rem;
        }

        /* Header */
        .header {
            text-align: center;
            padding: 3rem 0;
            border-bottom: 2px solid var(--border-color);
            margin-bottom: 3rem;
        }

        .profile-photo {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid var(--primary-color);
            margin-bottom: 1.5rem;
        }

        .profile-photo-placeholder {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: var(--primary-color);
            color: white;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            font-weight: bold;
            margin-bottom: 1.5rem;
        }

        h1 {
            font-size: 2.5rem;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .title {
            font-size: 1.5rem;
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .bio {
            font-size: 1.1rem;
            color: var(--text-light);
            max-width: 700px;
            margin: 0 auto 1.5rem;
        }

        .social-links {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .social-link {
            display: inline-block;
            padding: 0.5rem 1.25rem;
            background: var(--bg-light);
            color: var(--text-dark);
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s;
        }

        .social-link:hover {
            background: var(--primary-color);
            color: white;
        }

        /* Sections */
        .section {
            margin-bottom: 3rem;
        }

        .section-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 3px solid var(--primary-color);
        }

        /* Skills */
        .skills-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
        }

        .skill-item {
            background: var(--bg-light);
            padding: 1rem;
            border-radius: 8px;
            border-left: 4px solid var(--primary-color);
        }

        .skill-name {
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .skill-level {
            font-size: 0.875rem;
            color: var(--text-light);
        }

        /* Experience & Education */
        .timeline-item {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            margin-bottom: 1rem;
        }

        .timeline-item h3 {
            font-size: 1.25rem;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .timeline-meta {
            color: var(--text-light);
            font-size: 0.95rem;
            margin-bottom: 0.75rem;
        }

        .timeline-description {
            color: var(--text-dark);
            line-height: 1.7;
        }

        /* Projects */
        .projects-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 1.5rem;
        }

        .project-card {
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .project-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .project-content {
            padding: 1.5rem;
        }

        .project-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
        }

        .project-description {
            color: var(--text-light);
            margin-bottom: 1rem;
        }

        .project-link {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .project-link:hover {
            text-decoration: underline;
        }

        /* CV Download */
        .cv-download {
            text-align: center;
            padding: 2rem;
            background: var(--bg-light);
            border-radius: 12px;
            margin-top: 3rem;
        }

        .cv-download-btn {
            display: inline-block;
            padding: 1rem 2rem;
            background: var(--primary-color);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: opacity 0.3s;
        }

        .cv-download-btn:hover {
            opacity: 0.9;
        }

        /* Footer */
        .footer {
            text-align: center;
            padding: 2rem 0;
            margin-top: 3rem;
            border-top: 1px solid var(--border-color);
            color: var(--text-light);
        }

        @media (max-width: 768px) {
            h1 {
                font-size: 2rem;
            }

            .title {
                font-size: 1.25rem;
            }

            .skills-grid,
            .projects-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <header class="header">
            @if($portfolio->photo_url)
                <img src="{{ $portfolio->photo_url }}" alt="{{ $portfolio->user->name }}" class="profile-photo">
            @else
                <div class="profile-photo-placeholder">
                    {{ strtoupper(substr($portfolio->user->name, 0, 1)) }}
                </div>
            @endif
            <h1>{{ $portfolio->user->name }}</h1>
            <div class="title">{{ $portfolio->title }}</div>
            @if($portfolio->bio)
                <p class="bio">{{ $portfolio->bio }}</p>
            @endif

            @if($portfolio->social_links && count($portfolio->social_links) > 0)
                <div class="social-links">
                    @foreach($portfolio->social_links as $platform => $url)
                        @if($url)
                            <a href="{{ $url }}" target="_blank" class="social-link">
                                {{ ucfirst($platform) }}
                            </a>
                        @endif
                    @endforeach
                </div>
            @endif
        </header>

        <!-- Skills -->
        @if($portfolio->skills && count($portfolio->skills) > 0)
            <section class="section">
                <h2 class="section-title">Compétences</h2>
                <div class="skills-grid">
                    @foreach($portfolio->skills as $skill)
                        <div class="skill-item">
                            <div class="skill-name">{{ $skill['name'] }}</div>
                            <div class="skill-level">{{ $skill['level'] }}</div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        <!-- Experience -->
        @if($portfolio->experiences && count($portfolio->experiences) > 0)
            <section class="section">
                <h2 class="section-title">Expérience Professionnelle</h2>
                @foreach($portfolio->experiences as $exp)
                    <div class="timeline-item">
                        <h3>{{ $exp['title'] }}</h3>
                        <div class="timeline-meta">{{ $exp['company'] }} • {{ $exp['duration'] }}</div>
                        @if(isset($exp['description']))
                            <div class="timeline-description">{{ $exp['description'] }}</div>
                        @endif
                    </div>
                @endforeach
            </section>
        @endif

        <!-- Education -->
        @if($portfolio->education && count($portfolio->education) > 0)
            <section class="section">
                <h2 class="section-title">Formation</h2>
                @foreach($portfolio->education as $edu)
                    <div class="timeline-item">
                        <h3>{{ $edu['degree'] }}</h3>
                        <div class="timeline-meta">{{ $edu['school'] }} • {{ $edu['year'] }}</div>
                        @if(isset($edu['description']))
                            <div class="timeline-description">{{ $edu['description'] }}</div>
                        @endif
                    </div>
                @endforeach
            </section>
        @endif

        <!-- Projects -->
        @if($portfolio->projects && count($portfolio->projects) > 0)
            <section class="section">
                <h2 class="section-title">Projets</h2>
                <div class="projects-grid">
                    @foreach($portfolio->projects as $project)
                        <div class="project-card">
                            <div class="project-content">
                                <div class="project-title">{{ $project['name'] }}</div>
                                <div class="project-description">{{ $project['description'] }}</div>
                                @if(isset($project['url']))
                                    <a href="{{ $project['url'] }}" target="_blank" class="project-link">
                                        Voir le projet →
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        <!-- Certifications -->
        @if($portfolio->certifications && count($portfolio->certifications) > 0)
            <section class="section">
                <h2 class="section-title">Certifications</h2>
                @foreach($portfolio->certifications as $cert)
                    <div class="timeline-item">
                        <h3>{{ $cert['name'] }}</h3>
                        <div class="timeline-meta">{{ $cert['issuer'] }} • {{ $cert['date'] }}</div>
                        @if(isset($cert['credential_url']))
                            <a href="{{ $cert['credential_url'] }}" target="_blank" class="project-link">
                                Voir la certification
                            </a>
                        @endif
                    </div>
                @endforeach
            </section>
        @endif

        <!-- Languages -->
        @if($portfolio->languages && count($portfolio->languages) > 0)
            <section class="section">
                <h2 class="section-title">Langues</h2>
                <div class="skills-grid">
                    @foreach($portfolio->languages as $lang)
                        <div class="skill-item">
                            <div class="skill-name">{{ $lang['language'] }}</div>
                            <div class="skill-level">{{ $lang['level'] }}</div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        <!-- CV Download -->
        @if($portfolio->cv_url)
            <div class="cv-download">
                <h3 style="margin-bottom: 1rem;">Télécharger mon CV</h3>
                <a href="{{ $portfolio->cv_url }}" target="_blank" class="cv-download-btn" download>
                    📄 Télécharger le CV (PDF)
                </a>
            </div>
        @endif

        <!-- Footer -->
        <footer class="footer">
            <p>Portfolio créé avec Estuaire Emploie</p>
            <p>{{ $portfolio->user->email }}</p>
        </footer>
    </div>
</body>
</html>
