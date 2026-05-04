@extends('admin.layouts.app')

@section('title', 'Prévisualisation du CV')

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-gray-900">Accueil</a>
    <span class="text-gray-400">/</span>
    <a href="{{ route('admin.cvtheque.index') }}" class="text-gray-600 hover:text-gray-900">CVthèque</a>
    <span class="text-gray-400">/</span>
    <span class="text-gray-900 font-medium">Prévisualisation</span>
@endsection

@section('header-actions')
    @if($resume->pdf_path)
        <a href="{{ $resume->pdf_url }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-all duration-200 font-medium">
            <i class="mdi mdi-file-pdf-box"></i>
            Voir PDF
        </a>
    @endif
    <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-200 font-medium">
        <i class="mdi mdi-printer"></i>
        Imprimer
    </button>
@endsection

<style>
    @media print {
        body * {
            visibility: hidden;
        }
        .cv-preview-container, .cv-preview-container * {
            visibility: visible;
        }
        .cv-preview-container {
            position: absolute;
            left: 0;
            top: 0;
        }
    }

    .cv-preview-wrapper {
        background: #f5f5f5;
        padding: 2rem;
        min-height: 100vh;
        overflow-x: auto;
    }

    .cv-preview-container {
        background: white;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        margin: 0 auto;
        width: 210mm;
        min-height: 297mm;
        position: relative;
    }

    .cv-left {
        position: absolute;
        left: 0;
        top: 0;
        width: 75mm;
        min-height: 297mm;
        background-color: #004a7c;
        color: white;
        padding: 56px 32px;
        box-sizing: border-box;
    }

    .cv-right {
        position: absolute;
        left: 75mm;
        top: 0;
        width: 135mm;
        min-height: 297mm;
        background-color: white;
        padding: 56px 45px;
        box-sizing: border-box;
    }

    /* Left column */
    .photo-box {
        width: 190px;
        height: 190px;
        margin: 0 auto 38px;
        border: 3px solid white;
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
        display: block;
    }

    .photo-placeholder {
        font-size: 80px;
        color: #004a7c;
        text-align: center;
        line-height: 1;
    }

    .left-section {
        margin-bottom: 30px;
    }

    .left-section h3 {
        font-size: 11px;
        font-weight: bold;
        text-transform: uppercase;
        color: white;
        border-bottom: 2px solid white;
        padding-bottom: 8px;
        margin-bottom: 12px;
    }

    .left-section p {
        font-size: 10px;
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

    .left-section ul li {
        font-size: 10px;
        line-height: 1.3;
        color: white;
        margin-bottom: 8px;
        word-wrap: break-word;
        overflow-wrap: break-word;
        word-break: break-word;
        hyphens: auto;
        -webkit-hyphens: auto;
        -moz-hyphens: auto;
    }

    /* Right column */
    .cv-name {
        font-size: 28px;
        font-weight: bold;
        color: #1a1a1a;
        margin-bottom: 8px;
        word-wrap: break-word;
        overflow-wrap: break-word;
        word-break: break-word;
        line-height: 1.2;
        hyphens: auto;
        -webkit-hyphens: auto;
        -moz-hyphens: auto;
    }

    .cv-job-title {
        font-size: 13px;
        font-weight: bold;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 20px;
        word-wrap: break-word;
        overflow-wrap: break-word;
        line-height: 1.3;
    }

    .contact-box {
        border-top: 2px solid #e0e0e0;
        padding-top: 12px;
        margin-bottom: 24px;
    }

    .contact-item {
        font-size: 10px;
        color: #333;
        margin-bottom: 6px;
        word-wrap: break-word;
        overflow-wrap: break-word;
        word-break: break-word;
    }

    .contact-icon {
        color: #004a7c;
        font-weight: bold;
        display: inline-block;
        width: 16px;
    }

    .right-section {
        margin-top: 20px;
        padding-top: 16px;
        border-top: 2px solid #e0e0e0;
    }

    .right-section h3 {
        font-size: 12px;
        font-weight: bold;
        text-transform: uppercase;
        color: #1a1a1a;
        margin-bottom: 16px;
    }

    .experience-block,
    .education-block {
        margin-bottom: 16px;
    }

    .exp-date {
        font-size: 9px;
        font-weight: bold;
        color: #666;
        margin-bottom: 4px;
    }

    .exp-company {
        font-size: 11px;
        font-weight: bold;
        color: #004a7c;
        margin-bottom: 4px;
        word-wrap: break-word;
    }

    .exp-position {
        font-size: 10px;
        font-style: italic;
        color: #333;
        margin-bottom: 8px;
    }

    .exp-desc {
        font-size: 9px;
        color: #555;
        line-height: 1.3;
        text-align: justify;
    }

    .exp-desc ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .exp-desc ul li {
        margin-bottom: 4px;
        word-wrap: break-word;
        overflow-wrap: break-word;
        word-break: break-word;
        text-align: justify;
        hyphens: auto;
        -webkit-hyphens: auto;
        -moz-hyphens: auto;
    }

    @media (max-width: 1024px) {
        .cv-preview-wrapper {
            padding: 1rem;
        }
        .cv-preview-container {
            width: 100%;
            max-width: 210mm;
            min-height: auto;
        }
    }

    @media (max-width: 768px) {
        .cv-preview-container {
            position: static;
            width: 100%;
            box-shadow: none;
        }
        .cv-left,
        .cv-right {
            position: static;
            width: 100%;
            min-height: auto;
            padding: 24px 18px;
        }
        .photo-box {
            width: 120px;
            height: 120px;
            margin-bottom: 20px;
        }
        .cv-name {
            font-size: 22px;
        }
        .cv-job-title {
            font-size: 12px;
        }
    }
</style>

@section('content')
@php
    $personalInfo = $resume->personal_info ?? [];
    $experiences = $resume->experiences ?? [];
    $education = $resume->education ?? [];
    $skills = $resume->skills ?? [];
    $hobbies = $resume->hobbies ?? [];
    $projects = $resume->projects ?? [];
    $certifications = $resume->certifications ?? [];
    $softSkills = $resume->customization['soft_skills'] ?? [];
@endphp

<div class="cv-preview-wrapper">
    <div class="cv-preview-container">
        <!-- COLONNE GAUCHE (bleue) -->
        <div class="cv-left">
            <div class="photo-box">
                @if(!empty($personalInfo['photo_path']))
                    <img src="{{ asset('storage/' . $personalInfo['photo_path']) }}" alt="Photo">
                @else
                    <div class="photo-placeholder">👤</div>
                @endif
            </div>

            @if(!empty($resume->professional_summary))
            <div class="left-section">
                <h3>OBJECTIF PROFESSIONNEL</h3>
                <p>{{ $resume->professional_summary }}</p>
            </div>
            @endif

            @if(!empty($skills) && count($skills) > 0)
            <div class="left-section">
                <h3>COMPÉTENCES</h3>
                <ul>
                    @foreach($skills as $skill)
                        @if(is_string($skill))
                            <li>• {{ $skill }}</li>
                        @endif
                    @endforeach
                </ul>
            </div>
            @endif

            @if(!empty($softSkills) && count($softSkills) > 0)
            <div class="left-section">
                <h3>SAVOIR-ÊTRE</h3>
                <ul>
                    @foreach($softSkills as $soft)
                        <li>• {{ $soft }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @if(!empty($personalInfo['languages']))
            <div class="left-section">
                <h3>LANGUES</h3>
                <p>{{ $personalInfo['languages'] }}</p>
            </div>
            @endif

            @if(!empty($hobbies) && count($hobbies) > 0)
            <div class="left-section">
                <h3>CENTRES D'INTÉRÊT</h3>
                <ul>
                    @foreach($hobbies as $hobby)
                        @if(is_string($hobby))
                            <li>• {{ $hobby }}</li>
                        @endif
                    @endforeach
                </ul>
            </div>
            @endif
        </div>

        <!-- COLONNE DROITE (blanche) -->
        <div class="cv-right">
            <h1 class="cv-name">{{ $personalInfo['name'] ?? $resume->user->name ?? 'N/A' }}</h1>
            <h2 class="cv-job-title">{{ $resume->title ?? 'SANS TITRE' }}</h2>

            <div class="contact-box">
                @if(!empty($personalInfo['phone']))
                <div class="contact-item">
                    <span class="contact-icon">☎</span> {{ $personalInfo['phone'] }}
                </div>
                @endif

                @if(!empty($personalInfo['email']))
                <div class="contact-item">
                    <span class="contact-icon">✉</span> {{ $personalInfo['email'] }}
                </div>
                @endif

                @if(!empty($personalInfo['address']))
                <div class="contact-item">
                    <span class="contact-icon">⌂</span> {{ $personalInfo['address'] }}
                </div>
                @endif
            </div>

            @if(!empty($experiences) && count($experiences) > 0)
            <div class="right-section">
                <h3>EXPÉRIENCES PROFESSIONNELLES</h3>
                @foreach($experiences as $exp)
                    <div class="experience-block">
                        @if(is_array($exp))
                            @if(!empty($exp['date']))
                            <div class="exp-date">{{ $exp['date'] }}</div>
                            @endif

                            @if(!empty($exp['company']))
                            <div class="exp-company">{{ $exp['company'] }}</div>
                            @endif

                            @if(!empty($exp['title']))
                            <div class="exp-position">{{ $exp['title'] }}</div>
                            @endif

                            @if(!empty($exp['description']))
                            <div class="exp-desc">
                                <ul>
                                    @if(is_array($exp['description']))
                                        @foreach($exp['description'] as $task)
                                        <li>• {{ $task }}</li>
                                        @endforeach
                                    @else
                                        <li>• {{ $exp['description'] }}</li>
                                    @endif
                                </ul>
                            </div>
                            @endif
                        @else
                            <div class="exp-desc">
                                <ul><li>• {{ $exp }}</li></ul>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
            @endif

            @if(!empty($education) && count($education) > 0)
            <div class="right-section">
                <h3>FORMATION</h3>
                @foreach($education as $edu)
                    <div class="education-block">
                        @if(is_array($edu))
                            @if(!empty($edu['school']))
                            <div class="exp-company">{{ $edu['school'] }}</div>
                            @endif

                            @if(!empty($edu['degree']))
                            <div class="exp-position">{{ $edu['degree'] }}</div>
                            @endif
                        @else
                            <div class="exp-desc">
                                <ul><li>• {{ $edu }}</li></ul>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
            @endif

            @if(!empty($projects) && count($projects) > 0)
            <div class="right-section">
                <h3>PROJETS ACADÉMIQUES</h3>
                <div class="exp-desc">
                    <ul>
                    @foreach($projects as $project)
                        <li>• {{ $project }}</li>
                    @endforeach
                    </ul>
                </div>
            </div>
            @endif

            @if(!empty($certifications) && count($certifications) > 0)
            <div class="right-section">
                <h3>CERTIFICATIONS</h3>
                <div class="exp-desc">
                    <ul>
                    @foreach($certifications as $cert)
                        <li>• {{ $cert }}</li>
                    @endforeach
                    </ul>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
