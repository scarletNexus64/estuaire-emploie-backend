@extends('admin.layouts.app')

@section('title', 'Récapitulatif - Créer un Étudiant')
@section('page-title', 'Récapitulatif de l\'Étudiant')

@section('breadcrumbs')
    <span> / </span>
    <a href="{{ route('admin.students.index') }}">Étudiants</a>
    <span> / </span>
    <a href="{{ route('admin.students.create') }}">Créer</a>
    <span> / </span>
    <span>Récapitulatif</span>
@endsection

@section('content')
<div style="display: grid; gap: 1.5rem;">
    <!-- Alert Info -->
    <div class="alert alert-warning">
        <strong>⚠️ Attention :</strong> Veuillez vérifier toutes les informations avant de confirmer la création de l'étudiant.
    </div>

    <!-- Student Information -->
    <div class="card">
        <div class="card-header">
            <h3 style="margin: 0;">👤 Informations de l'Étudiant</h3>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div>
                    <strong>Nom & Prénom :</strong><br>
                    <span style="font-size: 1.1rem;">{{ $studentData['name'] }}</span>
                </div>
                <div>
                    <strong>Email :</strong><br>
                    <span style="font-size: 1.1rem;">{{ $studentData['email'] }}</span>
                </div>
                <div>
                    <strong>Téléphone :</strong><br>
                    <span style="font-size: 1.1rem;">{{ $studentData['phone'] }}</span>
                </div>
                <div>
                    <strong>Spécialité :</strong><br>
                    <span class="badge badge-primary" style="font-size: 1rem; padding: 0.5rem 1rem;">{{ $studentData['specialty'] }}</span>
                </div>
                <div>
                    <strong>Niveau Académique :</strong><br>
                    <span class="badge badge-success" style="font-size: 1rem; padding: 0.5rem 1rem;">{{ $studentData['level'] }}</span>
                </div>
                @if(!empty($studentData['interests']))
                <div style="grid-column: 1 / -1;">
                    <strong>Centre d'Intérêt :</strong><br>
                    <p style="margin-top: 0.5rem; padding: 1rem; background: #f8f9fa; border-radius: 4px;">
                        {{ $studentData['interests'] }}
                    </p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Generated Password -->
    <div class="card" style="border: 2px solid #28a745;">
        <div class="card-header" style="background: #28a745; color: white;">
            <h3 style="margin: 0;">🔑 Mot de Passe Généré</h3>
        </div>
        <div class="card-body">
            <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 8px; text-align: center;">
                <p style="margin-bottom: 1rem; font-size: 0.9rem; color: #6c757d;">
                    Ce mot de passe sera envoyé à l'étudiant par SMS. Assurez-vous de le noter si nécessaire.
                </p>
                <div style="background: white; padding: 1.5rem; border: 2px dashed #28a745; border-radius: 8px; display: inline-block;">
                    <code style="font-size: 2rem; font-weight: bold; color: #28a745; letter-spacing: 2px;">{{ $password }}</code>
                </div>
                <p style="margin-top: 1rem; font-size: 0.85rem; color: #6c757d;">
                    L'étudiant devra changer ce mot de passe lors de sa première connexion.
                </p>
            </div>
        </div>
    </div>

    <!-- SMS Preview -->
    <div class="card" style="border: 2px solid #007bff;">
        <div class="card-header" style="background: #007bff; color: white;">
            <h3 style="margin: 0;">📱 Aperçu du SMS (via Nexah)</h3>
        </div>
        <div class="card-body">
            <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 8px;">
                <p style="margin-bottom: 1rem; font-size: 0.9rem; color: #6c757d;">
                    Ce message sera envoyé au numéro <strong>{{ $studentData['phone'] }}</strong>
                </p>
                <div style="background: white; padding: 1.5rem; border-left: 4px solid #007bff; font-family: monospace; white-space: pre-wrap; line-height: 1.6;">{{ $smsMessage }}</div>
            </div>
        </div>
    </div>

    <!-- App Download Links -->
    <div class="card" style="border: 2px solid #6f42c1;">
        <div class="card-header" style="background: #6f42c1; color: white;">
            <h3 style="margin: 0;">📲 Liens de Téléchargement de l'Application</h3>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div style="text-align: center; padding: 1rem; background: #f8f9fa; border-radius: 8px;">
                    <h4>Android</h4>
                    <a href="https://play.google.com/store/apps/details?id=com.insam.estuaire_emploie"
                       target="_blank"
                       class="btn btn-success"
                       style="margin-top: 0.5rem;">
                        📥 Google Play
                    </a>
                </div>
                <div style="text-align: center; padding: 1rem; background: #f8f9fa; border-radius: 8px;">
                    <h4>iOS</h4>
                    <a href="https://apps.apple.com/cm/app/estuaire-emploi/id1666203946"
                       target="_blank"
                       class="btn btn-primary"
                       style="margin-top: 0.5rem;">
                        📥 App Store
                    </a>
                </div>
            </div>
            <p style="margin-top: 1rem; text-align: center; font-size: 0.85rem; color: #6c757d;">
                L'étudiant devra télécharger l'application pour se connecter.
            </p>
        </div>
    </div>

    <!-- Benefits Info -->
    <div class="card" style="border: 2px solid #ffc107;">
        <div class="card-header" style="background: #ffc107; color: #000;">
            <h3 style="margin: 0;">🎁 Avantages qui seront Activés Automatiquement</h3>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div style="padding: 1rem; background: #f8f9fa; border-radius: 8px; border-left: 4px solid #c0c0c0;">
                    <h4>🥈 Pack SILVER (C1)</h4>
                    <p><strong>Durée :</strong> 1 mois (30 jours)</p>
                    <p><strong>Coût :</strong> GRATUIT</p>
                    <ul style="margin: 0; padding-left: 1.5rem;">
                        <li>Accès aux offres d'emploi premium</li>
                        <li>Candidatures prioritaires</li>
                        <li>Profil mis en avant</li>
                    </ul>
                </div>
                <div style="padding: 1rem; background: #f8f9fa; border-radius: 8px; border-left: 4px solid #6f42c1;">
                    <h4>🎓 Mode Étudiant</h4>
                    <p><strong>Durée :</strong> 1 an (365 jours)</p>
                    <p><strong>Coût :</strong> GRATUIT</p>
                    <ul style="margin: 0; padding-left: 1.5rem;">
                        <li>Accès aux offres de stage</li>
                        <li>Offres spéciales étudiants</li>
                        <li>Réseau étudiant</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="card">
        <div class="card-footer" style="display: flex; gap: 0.5rem; justify-content: space-between; align-items: center;">
            <a href="{{ route('admin.students.create') }}" class="btn btn-secondary">
                ← Retour au Formulaire
            </a>
            <div style="display: flex; gap: 0.5rem;">
                <form action="{{ route('admin.students.confirm') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success" style="font-size: 1.1rem; padding: 0.75rem 2rem;">
                        ✅ Confirmer et Créer l'Étudiant
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
