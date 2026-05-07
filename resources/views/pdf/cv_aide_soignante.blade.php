<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>CV - {{ $data['name'] }}</title>
    <style>
        @page {
            margin: 0;
            size: A4 portrait;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: DejaVu Sans, sans-serif;
            font-size: 9pt;
            line-height: 1.2;
            color: #333;
        }

        .cv-container {
            display: table;
            width: 100%;
            table-layout: fixed;
        }

        /* Zone bleue */
        .cv-left {
            display: table-cell;
            vertical-align: top;
            width: 35%;
            background-color: #004a7c;
            color: white;
            padding: 12mm 8mm;
            box-sizing: border-box;
        }

        /* Zone blanche */
        .cv-right {
            display: table-cell;
            vertical-align: top;
            width: 65%;
            background-color: white;
            padding: 12mm 10mm;
            box-sizing: border-box;
        }

        /* === COLONNE GAUCHE === */
        .photo-box {
            width: 48mm;
            height: 48mm;
            margin: 0 auto 7mm;
            border: 2pt solid white;
            border-radius: 50%;
            background-color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .photo-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .photo-placeholder {
            font-size: 60pt;
            color: #004a7c;
            text-align: center;
            line-height: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
        }

        .left-section {
            margin-bottom: 5mm;
        }

        .left-section h3 {
            font-size: 7.5pt;
            font-weight: bold;
            text-transform: uppercase;
            color: white;
            border-bottom: 1pt solid white;
            padding-bottom: 1.5mm;
            margin-bottom: 2mm;
        }

        .left-section p {
            font-size: 7pt;
            line-height: 1.3;
            color: white;
            word-wrap: break-word;
            overflow-wrap: break-word;
            word-break: break-word;
            text-align: justify;
            hyphens: auto;
            -webkit-hyphens: auto;
            -moz-hyphens: auto;
        }

        .left-section ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .left-section li {
            font-size: 7pt;
            line-height: 1.3;
            color: white;
            margin-bottom: 1.5mm;
            word-wrap: break-word;
            overflow-wrap: break-word;
            word-break: break-word;
            hyphens: auto;
            -webkit-hyphens: auto;
            -moz-hyphens: auto;
        }

        /* === COLONNE DROITE === */
        .cv-name {
            font-size: 18pt;
            font-weight: bold;
            color: #1a1a1a;
            margin-bottom: 2mm;
            word-wrap: break-word;
            overflow-wrap: break-word;
            word-break: break-word;
            line-height: 1.2;
            hyphens: auto;
            -webkit-hyphens: auto;
            -moz-hyphens: auto;
        }

        .cv-job-title {
            font-size: 9pt;
            font-weight: bold;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.3pt;
            margin-bottom: 4mm;
            word-wrap: break-word;
            overflow-wrap: break-word;
            line-height: 1.3;
        }

        .contact-box {
            border-top: 1pt solid #e0e0e0;
            padding-top: 2mm;
            margin-bottom: 4mm;
        }

        .contact-item {
            font-size: 7pt;
            color: #333;
            margin-bottom: 1mm;
            word-wrap: break-word;
            overflow-wrap: break-word;
            word-break: break-word;
        }

        .contact-icon {
            color: #004a7c;
            font-weight: bold;
        }

        .right-section {
            margin-top: 3.5mm;
            padding-top: 2.5mm;
            border-top: 1pt solid #e0e0e0;
        }

        .right-section h3 {
            font-size: 8.5pt;
            font-weight: bold;
            text-transform: uppercase;
            color: #1a1a1a;
            margin-bottom: 2.5mm;
        }

        .experience-block,
        .education-block {
            margin-bottom: 2.5mm;
        }

        .exp-date {
            font-size: 6.5pt;
            font-weight: bold;
            color: #666;
            margin-bottom: 0.7mm;
        }

        .exp-company {
            font-size: 8pt;
            font-weight: bold;
            color: #004a7c;
            margin-bottom: 0.7mm;
            word-wrap: break-word;
        }

        .exp-position {
            font-size: 7pt;
            font-style: italic;
            color: #333;
            margin-bottom: 1.2mm;
            word-wrap: break-word;
        }

        .exp-desc {
            font-size: 6.5pt;
            color: #555;
            line-height: 1.3;
            text-align: justify;
        }

        .exp-desc ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .exp-desc li {
            margin-bottom: 0.7mm;
            word-wrap: break-word;
            overflow-wrap: break-word;
            word-break: break-word;
            text-align: justify;
            hyphens: auto;
            -webkit-hyphens: auto;
            -moz-hyphens: auto;
        }
    </style>
</head>
<body>
    <div class="cv-container">
        <!-- COLONNE GAUCHE (bleue) -->
        <div class="cv-left">
            <div class="photo-box">
                @if(!empty($data['photo_path']))
                    <img src="{{ $data['photo_path'] }}" alt="Photo de profil">
                @endif
            </div>

            @if(!empty($data['objective']))
            <div class="left-section">
                <h3>OBJECTIF PROFESSIONNEL</h3>
                <p>{{ $data['objective'] }}</p>
            </div>
            @endif

            @if(!empty($data['skills']) && count($data['skills']) > 0)
            <div class="left-section">
                <h3>COMPÉTENCES</h3>
                <ul>
                    @foreach($data['skills'] as $skill)
                    <li>• {{ $skill }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @if(!empty($data['soft_skills']) && count($data['soft_skills']) > 0)
            <div class="left-section">
                <h3>SAVOIR-ÊTRE</h3>
                <ul>
                    @foreach($data['soft_skills'] as $softSkill)
                    <li>• {{ $softSkill }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @if(!empty($data['languages']))
            <div class="left-section">
                <h3>LANGUES</h3>
                <p>{{ $data['languages'] }}</p>
            </div>
            @endif

            @if(!empty($data['hobbies']) && count($data['hobbies']) > 0)
            <div class="left-section">
                <h3>CENTRES D'INTÉRÊT</h3>
                <ul>
                    @foreach($data['hobbies'] as $hobby)
                    <li>• {{ $hobby }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>

        <!-- COLONNE DROITE (blanche) -->
        <div class="cv-right">
            <h1 class="cv-name">{{ $data['name'] }}</h1>
            <h2 class="cv-job-title">{{ $data['title'] }}</h2>

            <div class="contact-box">
                @if(!empty($data['phone']))
                <div class="contact-item">
                    <span class="contact-icon">☎</span> {{ $data['phone'] }}
                </div>
                @endif

                @if(!empty($data['email']))
                <div class="contact-item">
                    <span class="contact-icon">✉</span> {{ $data['email'] }}
                </div>
                @endif

                @if(!empty($data['address']))
                <div class="contact-item">
                    <span class="contact-icon">⌂</span> {{ $data['address'] }}
                </div>
                @endif

                @if(!empty($data['level']))
                <div class="contact-item">
                    <span class="contact-icon">🎓</span> {{ $data['level'] }}
                </div>
                @endif

                @if(!empty($data['specialty']))
                <div class="contact-item">
                    <span class="contact-icon">🧭</span> {{ $data['specialty'] }}
                </div>
                @endif
            </div>

            @if(!empty($data['experiences']) && count($data['experiences']) > 0)
            <div class="right-section">
                <h3>EXPÉRIENCES PROFESSIONNELLES</h3>
                @foreach($data['experiences'] as $exp)
                <div class="experience-block">
                    @if(!empty($exp['date']))
                    <div class="exp-date">{{ $exp['date'] }}</div>
                    @endif

                    @if(!empty($exp['company']))
                    <div class="exp-company">{{ $exp['company'] }}</div>
                    @endif

                    @if(!empty($exp['title']))
                    <div class="exp-position">{{ $exp['title'] }}</div>
                    @endif

                    @if(!empty($exp['description']) && count($exp['description']) > 0)
                    <div class="exp-desc">
                        <ul>
                            @foreach($exp['description'] as $task)
                            <li>• {{ $task }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
            @endif

            @if(!empty($data['education']) && count($data['education']) > 0)
            <div class="right-section">
                <h3>FORMATION</h3>
                @foreach($data['education'] as $edu)
                <div class="education-block">
                    @if(!empty($edu['school']))
                    <div class="exp-company">{{ $edu['school'] }}</div>
                    @endif

                    @if(!empty($edu['degree']))
                    <div class="exp-position">{{ $edu['degree'] }}</div>
                    @endif
                </div>
                @endforeach
            </div>
            @endif

            @if(!empty($data['projects']) && count($data['projects']) > 0)
            <div class="right-section">
                <h3>PROJETS ACADÉMIQUES</h3>
                <div class="exp-desc">
                    <ul>
                        @foreach($data['projects'] as $project)
                        <li>• {{ $project }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            @if(!empty($data['certifications']) && count($data['certifications']) > 0)
            <div class="right-section">
                <h3>CERTIFICATIONS</h3>
                <div class="exp-desc">
                    <ul>
                        @foreach($data['certifications'] as $certification)
                        <li>• {{ $certification }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif
        </div>
    </div>
</body>
</html>
