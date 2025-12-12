# Services API Integration Documentation

Ce document explique comment utiliser les services intégrés pour WhatsApp, SMS (Nexah) et Paiement (FreeMoPay).

## Table des matières

1. [Configuration](#configuration)
2. [Service WhatsApp](#service-whatsapp)
3. [Service Nexah SMS](#service-nexah-sms)
4. [Service de Notification](#service-de-notification)
5. [Service FreeMoPay](#service-freemopay)
6. [Webhooks](#webhooks)
7. [Tests](#tests)
8. [Documentation API Swagger](#documentation-api-swagger)

---

## Configuration

### Interface Admin

Accédez à la configuration des services via le dashboard admin :
- URL : `http://localhost:8000/admin/service-config`
- Menu : **Administration > Configuration API**

L'interface contient 4 onglets :
1. **WhatsApp** - Configuration de l'API WhatsApp Business
2. **SMS (Nexah)** - Configuration du service Nexah
3. **Paiement (FreeMoPay)** - Configuration FreeMoPay
4. **Préférences** - Canal de notification par défaut

### Configuration WhatsApp

Champs requis :
- **API Token** : Token d'accès depuis Meta Business Suite
- **Phone Number ID** : ID du numéro WhatsApp Business
- **Version API** : v21.0 (par défaut)
- **Nom du template** : Nom du template approuvé (ex: `otp_verification`)
- **Langue** : Code langue (fr, en)

### Configuration Nexah SMS

Champs requis :
- **Base URL** : URL de l'API Nexah
- **User** : Nom d'utilisateur API
- **Password** : Mot de passe API
- **Sender ID** : Identifiant de l'expéditeur (max 11 caractères)
- **Send Endpoint** : `/sms/1/text/single` (par défaut)
- **Credits Endpoint** : `/account/1/balance` (par défaut)

### Configuration FreeMoPay

Champs requis :
- **Base URL** : `https://api-v2.freemopay.com` (par défaut)
- **App Key** : Clé d'application FreeMoPay
- **Secret Key** : Clé secrète FreeMoPay
- **Callback URL** : URL publique pour recevoir les callbacks (ex: `https://votresite.com/api/webhooks/freemopay`)

Paramètres avancés :
- **Init Payment Timeout** : 5s (par défaut)
- **Status Check Timeout** : 5s (par défaut)
- **Token Timeout** : 10s (par défaut)
- **Token Cache Duration** : 3000s (50 minutes)
- **Max Retries** : 2
- **Retry Delay** : 0.5s

---

## Service WhatsApp

### Utilisation

```php
use App\Services\Notifications\WhatsAppService;

$whatsappService = new WhatsAppService();

// Envoyer un OTP
$result = $whatsappService->sendOtp(
    recipient: '+237658895572',
    otpCode: '123456'
);

if ($result['success']) {
    echo "Message envoyé ! ID: " . $result['message_id'];
} else {
    echo "Erreur: " . $result['message'];
}
```

### Test de connexion

```php
$result = $whatsappService->testConnection();
```

---

## Service Nexah SMS

### Utilisation

```php
use App\Services\Notifications\NexahService;

$nexahService = new NexahService();

// Envoyer un SMS
$result = $nexahService->sendSms(
    recipient: '237658895572',
    message: 'Votre code de vérification est : 123456'
);

// Obtenir le solde du compte
$accountInfo = $nexahService->getAccountInfo();
echo "Crédit restant: " . $accountInfo['credit'];
```

---

## Service de Notification

Le `NotificationService` est un wrapper intelligent qui choisit automatiquement entre WhatsApp et SMS selon la configuration.

### Utilisation

```php
use App\Services\Notifications\NotificationService;

$notificationService = new NotificationService();

// Envoi automatique (WhatsApp ou SMS selon config)
$result = $notificationService->sendOtp(
    recipient: '+237658895572',
    otpCode: '123456'
);

// Forcer l'envoi via WhatsApp
$result = $notificationService->sendViaWhatsApp('+237658895572', '123456');

// Forcer l'envoi via SMS
$result = $notificationService->sendViaSms('+237658895572', 'Votre message ici');

// Envoyer un message personnalisé
$result = $notificationService->send('+237658895572', 'Message personnalisé');
```

### Fallback automatique

Le service possède un système de fallback :
- Si WhatsApp est le canal par défaut et échoue → Bascule automatiquement sur SMS
- Si SMS est le canal par défaut et échoue → Bascule automatiquement sur WhatsApp

---

## Service FreeMoPay

### Utilisation

```php
use App\Services\Payment\FreeMoPayService;
use App\Models\User;
use App\Models\Company;

$freemopayService = new FreeMoPayService();

// Initier un paiement
$user = User::find(1);
$payment = $freemopayService->initPayment(
    payer: $user,
    amount: 5000.00,
    phoneNumber: '237658895572',
    description: 'Abonnement Premium'
);

echo "Paiement initié - Référence: " . $payment->reference;
echo "Statut: " . $payment->status; // "pending"

// Vérifier le statut d'un paiement
$status = $freemopayService->checkPaymentStatus($payment->reference);
```

### Format du numéro de téléphone

FreeMoPay accepte uniquement :
- **Cameroun** : 237XXXXXXXXX (12 chiffres, sans +)
- **RDC** : 243XXXXXXXXX (12 chiffres, sans +)

Le service normalise automatiquement les numéros.

### Gestion du token

Le service utilise un système de cache pour les tokens Bearer :
- Token valide pendant **60 minutes**
- Cache pendant **50 minutes** pour éviter l'expiration
- Regénération automatique si expiré

---

## Webhooks

### FreeMoPay Callback

**URL** : `POST /api/webhooks/freemopay`

FreeMoPay envoie des notifications à cette URL lorsque le statut d'un paiement change.

#### Format de la requête

```json
{
  "reference": "FMP123456789",
  "status": "SUCCESS",
  "externalId": "PAY-20251212120000",
  "amount": 5000,
  "payer": "237658895572"
}
```

#### Statuts possibles

- `SUCCESS` / `SUCCESSFUL` / `COMPLETED` → `success`
- `FAILED` / `FAILURE` / `ERROR` / `REJECTED` → `failed`
- `PENDING` / `PROCESSING` / `INITIATED` → `pending`
- `CANCELLED` / `CANCELED` → `cancelled`

#### Configuration

Assurez-vous que :
1. L'URL de callback est **accessible publiquement**
2. Elle utilise **HTTPS** en production
3. Elle est configurée dans l'admin : **Configuration API > FreeMoPay > Callback URL**

---

## Tests

### Test depuis le Dashboard Admin

1. Accédez à `http://localhost:8000/admin/service-config`
2. Configurez les credentials pour chaque service
3. Cliquez sur **"Tester la connexion"** dans chaque onglet

### Tests WhatsApp

Bouton "Tester la connexion" :
- Vérifie les credentials
- Récupère les informations du numéro WhatsApp Business

### Tests Nexah

Bouton "Tester la connexion" :
- Vérifie les credentials
- Récupère le solde du compte SMS

### Tests FreeMoPay

Bouton "Tester la connexion" :
- Vérifie les credentials
- Génère un token Bearer
- Affiche un aperçu du token

---

## Documentation API Swagger

### Accès à la documentation Swagger/OpenAPI

La documentation complète des APIs (incluant WhatsApp, Nexah SMS, FreeMoPay et Webhooks) est disponible via Swagger UI :

**URL** : `http://localhost:8000/api/documentation`

### Endpoints documentés

#### Service Configuration (Admin)

1. **POST** `/admin/service-config/test/whatsapp`
   - Tester la connexion WhatsApp Business API
   - Vérifie les credentials et récupère les informations du numéro WhatsApp

2. **POST** `/admin/service-config/send-test/whatsapp`
   - Envoyer un message WhatsApp de test
   - Paramètres : `phone` (numéro), `otp` (code à envoyer)

3. **POST** `/admin/service-config/test/nexah`
   - Tester la connexion Nexah SMS API
   - Vérifie les credentials et récupère le solde du compte

4. **POST** `/admin/service-config/send-test/nexah`
   - Envoyer un SMS de test via Nexah
   - Paramètres : `phone` (numéro), `message` (texte du SMS)

5. **POST** `/admin/service-config/test/freemopay`
   - Tester la connexion FreeMoPay API
   - Génère un token Bearer pour vérifier les credentials

#### Webhooks

1. **POST** `/api/webhooks/freemopay`
   - Callback webhook pour les notifications de paiement FreeMoPay
   - Reçoit les mises à jour de statut des paiements

### Utilisation de Swagger UI

1. **Accéder à la documentation**
   ```
   http://localhost:8000/api/documentation
   ```

2. **Authentification**
   - Cliquez sur le bouton **"Authorize"** en haut à droite
   - Entrez votre token Bearer obtenu lors du login/register
   - Format : `Bearer <votre_token>`

3. **Tester les endpoints**
   - Sélectionnez un endpoint dans la liste
   - Cliquez sur **"Try it out"**
   - Remplissez les paramètres requis
   - Cliquez sur **"Execute"**
   - Consultez la réponse dans la section **"Response"**

### Régénération de la documentation

Si vous modifiez les annotations OpenAPI dans le code :

```bash
php artisan l5-swagger:generate
```

La documentation sera automatiquement mise à jour dans `storage/api-docs/api-docs.json`.

### Schémas et modèles

Les schémas de réponse incluent :

**WhatsApp Test Response**
```json
{
  "success": true,
  "message": "WhatsApp connection successful",
  "data": {
    "verified_name": "Ma Business",
    "display_phone_number": "+237 6XX XXX XXX",
    "quality_rating": "GREEN"
  }
}
```

**Nexah Test Response**
```json
{
  "success": true,
  "message": "Nexah SMS connection successful",
  "data": {
    "credits": 1500.50,
    "currency": "XAF"
  }
}
```

**FreeMoPay Test Response**
```json
{
  "success": true,
  "message": "FreeMoPay connection successful, token generated",
  "data": {
    "token_length": 256,
    "token_preview": "eyJhbGciOi..."
  }
}
```

**FreeMoPay Webhook Payload**
```json
{
  "reference": "FMP123456789",
  "status": "SUCCESS",
  "externalId": "PAY-20251212120000",
  "amount": 5000,
  "payer": "237658895572"
}
```

### Tags disponibles

- **Authentication** - Endpoints d'authentification (login, register, etc.)
- **Service Configuration** - Test et configuration des services externes
- **Webhooks** - Callbacks et notifications externes
- **Jobs** - Gestion des offres d'emploi
- **Applications** - Gestion des candidatures
- **Companies** - Gestion des entreprises
- **Favorites** - Favoris des candidats
- **Notifications** - Notifications utilisateur

---

## Logs

Tous les services enregistrent des logs détaillés :

```bash
tail -f storage/logs/laravel.log
```

Préfixes de logs :
- `[FreeMoPay]` - Service de paiement
- `[FreeMoPay Service]` - Opérations de paiement
- `[FreeMoPay TokenManager]` - Gestion des tokens
- `[FreeMoPay Client]` - Requêtes HTTP
- `[FreeMoPay Webhook]` - Callbacks reçus

---

## Architecture des fichiers

```
app/
├── Services/
│   ├── Notifications/
│   │   ├── WhatsAppService.php
│   │   ├── NexahService.php
│   │   └── NotificationService.php
│   └── Payment/
│       ├── FreeMoPayClient.php
│       ├── FreeMoPayTokenManager.php
│       └── FreeMoPayService.php
├── Models/
│   ├── ServiceConfiguration.php
│   └── Payment.php
└── Http/Controllers/
    ├── Admin/
    │   └── ServiceConfigController.php
    └── Api/
        └── WebhookController.php
```

---

## Exemples d'utilisation complète

### Envoi d'OTP lors de l'inscription

```php
use App\Services\Notifications\NotificationService;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // ... création utilisateur ...

        $otp = rand(100000, 999999);

        // Envoyer OTP via canal par défaut (WhatsApp ou SMS)
        $notificationService = new NotificationService();
        $result = $notificationService->sendOtp(
            recipient: $user->phone,
            otpCode: $otp
        );

        if (!$result['success']) {
            Log::error("Failed to send OTP: " . $result['message']);
        }

        // Stocker OTP en base
        $user->otp_code = $otp;
        $user->otp_expires_at = now()->addMinutes(5);
        $user->save();

        return response()->json([
            'message' => 'OTP sent successfully',
            'via' => $result['success'] ? 'notification' : 'none'
        ]);
    }
}
```

### Paiement d'abonnement

```php
use App\Services\Payment\FreeMoPayService;

class SubscriptionController extends Controller
{
    public function subscribe(Request $request)
    {
        $user = auth()->user();
        $plan = SubscriptionPlan::find($request->plan_id);

        $freemopayService = new FreeMoPayService();

        try {
            $payment = $freemopayService->initPayment(
                payer: $user,
                amount: $plan->price,
                phoneNumber: $request->phone_number,
                description: "Abonnement {$plan->name}"
            );

            return response()->json([
                'success' => true,
                'payment_reference' => $payment->reference,
                'message' => 'Paiement initié. Veuillez confirmer sur votre téléphone.',
                'status' => $payment->status
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
```

---

## Support

Pour toute question ou problème :
1. Vérifiez les logs : `storage/logs/laravel.log`
2. Testez la configuration depuis le dashboard admin
3. Vérifiez que les URLs de callback sont accessibles publiquement
4. Assurez-vous que les credentials sont corrects

---

**Documentation générée le 12 Décembre 2025**
