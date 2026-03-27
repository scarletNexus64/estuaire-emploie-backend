@extends('admin.layouts.app')

@section('title', 'Étudiant Créé')
@section('page-title', 'Étudiant Créé avec Succès')

@section('breadcrumbs')
    <span> / </span>
    <a href="{{ route('admin.students.index') }}">Création de compte étudiant</a>
    <span> / </span>
    <span>Confirmation</span>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <!-- Success Message -->
        <div class="alert alert-success" style="margin-bottom: 2rem;">
            <h4 style="margin-bottom: 0.5rem;">✅ Étudiant et CV créés avec succès !</h4>
            <p style="margin-bottom: 0;">L'étudiant <strong>{{ $user->name }}</strong> a été créé avec son CV et ses avantages ont été activés.</p>
        </div>

        @if(isset($resume))
        <!-- CV Info -->
        <div style="background: #e7f3ff; border: 2px solid #0056b3; border-radius: 8px; padding: 1.5rem; margin-bottom: 2rem;">
            <h4 style="margin-bottom: 1rem;">📄 CV Généré</h4>
            <p>Le CV de <strong>{{ $user->name }}</strong> a été créé avec succès !</p>
            <div style="display: flex; gap: 1rem; margin-top: 1rem;">
                @if($resume->pdf_path)
                    <a href="{{ asset('storage/' . $resume->pdf_path) }}" target="_blank" class="btn btn-info">
                        👁️ Voir le CV (PDF)
                    </a>
                    <a href="{{ asset('storage/' . $resume->pdf_path) }}" download class="btn btn-success">
                        💾 Télécharger le CV
                    </a>
                    <a href="{{ route('admin.students.create-cv', $user->id) }}" class="btn btn-warning">
                        ✏️ Modifier le CV
                    </a>
                @endif
            </div>
        </div>
        @endif

        <!-- Student Info -->
        <h3 style="margin-bottom: 1.5rem;">📋 Informations de Connexion</h3>

        <div style="background: #f8f9fa; border: 2px solid #dee2e6; border-radius: 8px; padding: 1.5rem; margin-bottom: 2rem;">
            <div style="display: grid; gap: 1rem;">
                <div>
                    <strong>👤 Nom :</strong> {{ $user->name }}
                </div>
                <div>
                    <strong>📧 Email :</strong>
                    <code style="background: white; padding: 0.25rem 0.5rem; border-radius: 4px;">{{ $user->email }}</code>
                </div>
                <div>
                    <strong>📱 Téléphone :</strong>
                    <code style="background: white; padding: 0.25rem 0.5rem; border-radius: 4px;">{{ $user->phone }}</code>
                </div>
                <div style="border-top: 2px solid #dee2e6; padding-top: 1rem; margin-top: 0.5rem;">
                    <strong>🔑 Mot de passe temporaire :</strong>
                    <div style="background: #fff3cd; border: 2px solid #ffc107; padding: 1rem; border-radius: 4px; margin-top: 0.5rem;">
                        <code id="password" style="font-size: 1.2rem; font-weight: bold; color: #856404;">{{ $password }}</code>
                        <button type="button" onclick="copyPassword()" class="btn btn-sm btn-warning" style="margin-left: 1rem;">
                            📋 Copier
                        </button>
                    </div>
                    <small style="color: #856404; display: block; margin-top: 0.5rem;">
                        ⚠️ <strong>Important :</strong> L'étudiant devra changer ce mot de passe lors de sa première connexion.
                    </small>
                </div>
            </div>
        </div>

        <!-- Benefits -->
        <h3 style="margin-bottom: 1.5rem;">🎁 Avantages Activés</h3>

        @if(isset($benefits['subscription']))
        <div class="alert alert-info" style="margin-bottom: 1rem;">
            <h5 style="margin-bottom: 0.5rem;">🥈 {{ $benefits['subscription']['name'] ?? 'Pack SILVER (C1)' }}</h5>
            <p style="margin-bottom: 0.5rem;"><strong>Expire le :</strong> {{ $benefits['subscription']['expires_at'] }}</p>
            @if(isset($benefits['subscription']['features']) && count($benefits['subscription']['features']) > 0)
            <ul style="margin-bottom: 0; margin-top: 0.5rem;">
                @foreach($benefits['subscription']['features'] as $key => $value)
                    @if($value === true)
                        <li>{{ ucfirst(str_replace('_', ' ', $key)) }}</li>
                    @endif
                @endforeach
            </ul>
            @endif
        </div>
        @endif

        @if(isset($benefits['student_mode']))
        <div class="alert alert-primary" style="margin-bottom: 1rem;">
            <h5 style="margin-bottom: 0.5rem;">🎓 {{ $benefits['student_mode']['name'] ?? 'Mode Étudiant' }}</h5>
            <p style="margin-bottom: 0.5rem;"><strong>Expire le :</strong> {{ $benefits['student_mode']['expires_at'] }}</p>
            @if(isset($benefits['student_mode']['features']) && count($benefits['student_mode']['features']) > 0)
            <ul style="margin-bottom: 0;">
                @foreach($benefits['student_mode']['features'] as $feature)
                    <li>{{ $feature }}</li>
                @endforeach
            </ul>
            @endif
        </div>
        @endif

        <!-- SMS Form -->
        <div style="background: #fff3cd; border: 2px solid #ffc107; border-radius: 8px; padding: 1.5rem; margin-top: 2rem;">
            <h4 style="margin-bottom: 1rem;">📲 Dernière étape : Envoyer les Identifiants par SMS</h4>
            <p style="margin-bottom: 1rem;">
                Un SMS contenant les informations de connexion (email, mot de passe) sera envoyé au numéro <strong>{{ $user->phone }}</strong>.
            </p>
            <p style="margin-bottom: 1rem; color: #856404;">
                ⚠️ <strong>Important :</strong> Le SMS permettra à l'étudiant de se connecter à la plateforme et d'accéder à son CV.
            </p>

            <form action="{{ route('admin.students.send-sms', $user->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir envoyer le SMS à {{ $user->phone }} ?');">
                @csrf
                <input type="hidden" name="password" value="{{ $password }}">

                <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                    <button type="submit" class="btn btn-primary btn-lg">
                        📤 Envoyer le SMS maintenant
                    </button>
                    <a href="{{ route('admin.students.index') }}" class="btn btn-secondary btn-lg">
                        ⏭️ Terminer sans envoyer
                    </a>
                    <a href="{{ route('admin.students.show', $user->id) }}" class="btn btn-info btn-lg">
                        👁️ Voir le Profil
                    </a>
                    @if(isset($resume) && $resume->pdf_path)
                        <a href="{{ asset('storage/' . $resume->pdf_path) }}" target="_blank" class="btn btn-success btn-lg">
                            📄 Voir le CV
                        </a>
                        <a href="{{ route('admin.students.create-cv', $user->id) }}" class="btn btn-warning btn-lg">
                            ✏️ Modifier le CV
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function copyPassword() {
    const password = document.getElementById('password').textContent;
    navigator.clipboard.writeText(password).then(function() {
        alert('Mot de passe copié dans le presse-papier !');
    }, function() {
        alert('Erreur lors de la copie');
    });
}
</script>
@endsection
