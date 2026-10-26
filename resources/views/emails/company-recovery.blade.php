<!doctype html>
<html lang="fr"><body style="font-family:Arial,sans-serif;color:#182235;line-height:1.6">
<h2>{{ $details['title'] }}</h2>
<p style="white-space:pre-line">{{ $details['message'] }}</p>
@if(isset($details['code']))
    <p>Votre code : <strong style="font-size:24px;letter-spacing:4px">{{ $details['code'] }}</strong></p>
    <p>Valable 10 minutes. Ne le communiquez à personne.</p>
@endif
@if(isset($details['password']))
    <p>Identifiant : <strong>{{ $details['email'] }}</strong></p>
    <p>Mot de passe temporaire : <strong>{{ $details['password'] }}</strong></p>
    <p>Connectez-vous dans l’application Estuaire Emploi avec cet email. Vous devrez choisir un nouveau mot de passe à la première connexion. Ces identifiants temporaires sont valables 48 heures.</p>
    <p>Si ce délai est dépassé, utilisez « Mot de passe oublié » avec cet email ou contactez l’administration.</p>
@endif
<p>Si vous n’êtes pas à l’origine de cette demande, contactez l’administration d’Estuaire Emploi.</p>
</body></html>
