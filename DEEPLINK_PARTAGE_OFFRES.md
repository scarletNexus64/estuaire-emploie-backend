# Partage d'offres & Deeplink — Guide

Cette feature permet de générer un lien partageable pour chaque offre
d'emploi. Le lien ouvre l'offre directement dans l'application mobile
(mode vitrine, **sans connexion requise**), avec un fallback vers une page
web et les stores si l'app n'est pas installée.

> Frontend Flutter associé : `E-Emploie-Frontend` (même feature, branche correspondante).

---

## 1. Comment ça marche

1. Le backend expose pour chaque offre :
   - `share_url` : `https://api.estuaireemploi.com/jobs/{id}/share` (page web publique)
   - `deep_link` : `estuaireemploi://job/{id}` (scheme custom de l'app)

   Ces deux champs sont **automatiquement présents** dans toutes les
   réponses JSON de l'offre (`GET /api/jobs`, `GET /api/jobs/{id}`,
   création `POST /api/jobs`, etc.) via `$appends` sur le modèle `Job`.

2. L'utilisateur partage le `share_url` depuis le bouton « Partager » de
   l'écran détail de l'offre (app mobile).

3. Le destinataire ouvre le lien :
   - **App installée** → l'offre s'ouvre directement dans l'app
     (App Links Android / Universal Links iOS, ou redirection scheme).
   - **App non installée** → page web `share.blade.php` avec aperçu de
     l'offre + boutons Google Play / App Store.

---

## 2. Fichiers de la feature

### Backend (ce dépôt)

| Fichier | Rôle |
|---|---|
| `config/app.php` | Clés `share_base_url`, `app_scheme`, `app_store_url`, `play_store_url` |
| `app/Models/Job.php` | Accesseurs `share_url` + `deep_link` (dans `$appends`) |
| `app/Http/Controllers/JobShareController.php` | Sert la page web de partage (404 si offre non publiée) |
| `resources/views/jobs/share.blade.php` | Landing page (Open Graph + ouverture app + fallback stores) |
| `routes/web.php` | Route `GET /jobs/{job}/share` + routes `.well-known` |
| `public/.well-known/assetlinks.json` | Vérification App Links Android |
| `public/.well-known/apple-app-site-association` | Vérification Universal Links iOS |

### Frontend (E-Emploie-Frontend)

| Fichier | Rôle |
|---|---|
| `pubspec.yaml` | Dépendance `app_links` |
| `android/app/src/main/AndroidManifest.xml` | Intent-filters (scheme + App Links HTTPS) |
| `ios/Runner/Info.plist` | `CFBundleURLSchemes` = `estuaireemploi` |
| `ios/Runner/Runner.entitlements` | Associated Domains (`api.estuaireemploi.com`) |
| `lib/app/data/services/deeplink_service.dart` | Écoute des liens entrants → route vers l'offre |
| `lib/app/data/models/job_offer.dart` | Champs `shareUrl` / `deepLink` |
| `lib/app/modules/job_detail/controllers/job_detail_controller.dart` | `shareJob()` (partage natif) |

---

## 3. Pour l'instance Claude sur le serveur

Quand tu interviens sur ce serveur pour cette feature :

- **Ne pas régénérer** `share_url` / `deep_link` à la main : ils sont
  calculés par les accesseurs du modèle `Job`. Toute réponse API d'offre
  les contient déjà.
- La base des liens vient de `config('app.share_base_url')`, lui-même
  alimenté par la variable d'env `SHARE_BASE_URL`.
- Le scheme app vient de `config('app.app_scheme')` (`APP_SCHEME`).
- La page de partage **ne doit afficher que les offres publiées**
  (`status === 'published'`) — sinon 404. C'est volontaire.
- Si tu modifies `routes/web.php` ou `config/app.php`, exécute ensuite :

  ```bash
  php artisan route:clear
  php artisan config:clear
  ```

- Vérification rapide que tout répond :

  ```bash
  # Remplace 1 par un id d'offre publiée existante
  curl -s https://api.estuaireemploi.com/api/jobs/1 | grep -o '"share_url":"[^"]*"'
  curl -s -o /dev/null -w "%{http_code}\n" https://api.estuaireemploi.com/jobs/1/share
  curl -s -o /dev/null -w "%{http_code} %{content_type}\n" https://api.estuaireemploi.com/.well-known/assetlinks.json
  curl -s -o /dev/null -w "%{http_code} %{content_type}\n" https://api.estuaireemploi.com/.well-known/apple-app-site-association
  ```

  Attendu : le `share_url` présent, et les 3 URLs en `200`
  (les `.well-known` en `application/json`).

---

## 4. Pour le développeur — config restante à compléter

Trois éléments doivent être renseignés **par le dev** (placeholders ou
valeurs de dev actuellement). Sans eux, le partage fonctionne mais les
liens HTTPS n'ouvriront pas l'app automatiquement sur Android.

### 4.1 — Variable d'environnement de production (OBLIGATOIRE)

Dans le `.env` **du serveur de production** :

```env
SHARE_BASE_URL=https://api.estuaireemploi.com
APP_SCHEME=estuaireemploi
APP_STORE_URL=https://apps.apple.com/cm/app/estuaire-emploi/id1666203946
PLAY_STORE_URL=https://play.google.com/store/apps/details?id=com.insam.estuaire_emploie
```

> ⚠️ Actuellement `SHARE_BASE_URL` pointe sur l'IP de dev
> (`http://10.36.174.129:8001`). À corriger en prod, sinon les liens
> partagés pointeront vers le serveur de dev.

Après modification du `.env` :

```bash
php artisan config:clear
```

### 4.2 — SHA-256 du certificat Android (pour App Links Android)

Fichier : `public/.well-known/assetlinks.json`

Remplacer `REMPLACER_PAR_LE_SHA256_DU_CERTIFICAT_DE_SIGNATURE` par
l'empreinte SHA-256 du certificat de **signature de l'app publiée**.

**Où le trouver :**

- Si l'app est sur le Play Store (recommandé) :
  Play Console → **Configuration** → **Intégrité de l'application** →
  **Signature de l'application** → copier le SHA-256.
- Ou depuis le keystore de release :

  ```bash
  keytool -list -v -keystore /chemin/vers/release.jks -alias <alias>
  # Copier la ligne "SHA256:" (format AA:BB:CC:...)
  ```

Résultat attendu dans le fichier :

```json
"sha256_cert_fingerprints": [
  "AA:BB:CC:DD:...:FF"
]
```

> Si l'app utilise « Play App Signing », il peut y avoir **deux**
> empreintes (upload + app signing) : mettre les deux dans le tableau.

### 4.3 — Universal Links iOS (déjà configuré, à vérifier)

Fichier : `public/.well-known/apple-app-site-association`

L'`appID` est déjà renseigné : `MVNNT5L9VD.com.insam.estuaire_emploie`
(Team ID `MVNNT5L9VD`). À ne modifier que si le Team ID ou le bundle id
change.

---

## 5. Checklist de mise en production

- [ ] `.env` prod : `SHARE_BASE_URL=https://api.estuaireemploi.com`
- [ ] `php artisan config:clear` exécuté sur le serveur
- [ ] `assetlinks.json` : SHA-256 réel renseigné (§4.2)
- [ ] `apple-app-site-association` : `appID` correct (§4.3)
- [ ] HTTPS valide sur `api.estuaireemploi.com` (Universal/App Links
      l'exigent — pas de certificat auto-signé)
- [ ] Les 2 fichiers `.well-known` répondent en `200` +
      `Content-Type: application/json`
- [ ] Test réel : ouvrir un `share_url` depuis un téléphone avec l'app
      installée → l'offre s'ouvre dans l'app
- [ ] Test réel : même lien sans l'app → page web + boutons stores
