# Diagnostic : upload d'images produits/services en PROD

## Contexte

- **En local** (`http://10.73.28.254:8001`) : tout fonctionne. Le produit 21 a bien été créé avec 2 images dans `company_products/`, et les `image_urls` sont renvoyées correctement.
- **En prod** (`https://api.estuaireemploi.com`) : la requête échoue ou les images ne sont pas persistées.

Le code Flutter (`company_product_service.dart`) et le controller Laravel (`CompanyProductController@store`) sont identiques. Le bug est donc **environnemental** sur le serveur de production.

Endpoint concerné : `POST /api/company-products` (multipart/form-data, 2 à 4 images, max 2 Mo chacune).

---

## Checklist à vérifier sur le serveur prod

### 1. Limites PHP (cause #1 quasi-systématique)

Sur prod, lance :

```bash
php -i | grep -E "upload_max_filesize|post_max_size|max_file_uploads|memory_limit|max_execution_time"
```

Valeurs **minimum** requises (4 images × 2 Mo + champs texte) :

| Directive             | Min recommandé |
|-----------------------|----------------|
| `upload_max_filesize` | `5M`           |
| `post_max_size`       | `25M`          |
| `max_file_uploads`    | `20`           |
| `memory_limit`        | `256M`         |
| `max_execution_time`  | `120`          |

> ⚠️ `post_max_size` doit toujours être > `upload_max_filesize × max_file_uploads`. Si `post_max_size = 8M` par défaut → Laravel reçoit `$request->all()` **vide** et la validation `'images' => 'required|array|min:2'` renvoie **422** sans message clair.

**Où modifier :**
- Apache/nginx + PHP-FPM : `/etc/php/8.x/fpm/php.ini` puis `systemctl restart php8.x-fpm`
- cPanel/Plesk : interface "PHP Selector" ou "Select PHP Version"
- Si le serveur est derrière **Cloudflare**, voir aussi point 6.

### 2. Limites du serveur web (nginx surtout)

Si tu es sur **nginx**, vérifie `/etc/nginx/nginx.conf` ou le site dans `sites-available/` :

```nginx
http {
    client_max_body_size 25M;   # ← AJOUTER si absent
    client_body_timeout 120s;
}
```

Puis : `nginx -t && systemctl reload nginx`.

> Si manquant → erreur **413 Request Entity Too Large** avant même d'atteindre PHP. Dio renverra une erreur réseau, pas un 422 Laravel.

Sur Apache, c'est en général `LimitRequestBody` (rarement le coupable).

### 3. Permissions du dossier storage

Les images sont stockées dans `storage/app/public/company_products/`. Vérifie sur prod :

```bash
cd /chemin/vers/estuaire-emploie-backend
ls -la storage/app/public/
ls -la storage/app/public/company_products/ 2>/dev/null || echo "DOSSIER ABSENT"
```

**Attendu :**
- `storage/app/public` doit être accessible en écriture par l'utilisateur PHP-FPM (souvent `www-data` ou `nginx`).
- Le dossier `company_products/` se crée tout seul au 1er upload, mais le **parent** doit être writable.

Si problème :

```bash
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

### 4. Lien symbolique `public/storage`

C'est **LA cause la plus oubliée en prod**. Les images sont uploadées dans `storage/app/public/`, mais les URLs publiques pointent vers `public/storage/` qui doit être un **symlink**.

```bash
cd /chemin/vers/estuaire-emploie-backend
ls -la public/storage
```

**Attendu :** `public/storage -> /chemin/.../storage/app/public`

Si absent ou cassé :

```bash
php artisan storage:link
```

> Sans ce symlink : l'upload réussit (200/201), mais les `image_urls` renvoient **404** dans le navigateur. À vérifier directement en collant une URL `https://api.estuaireemploi.com/storage/company_products/xxx.jpg` dans le navigateur.

### 5. APP_URL / FILESYSTEM_DISK dans `.env` prod

```bash
cat .env | grep -E "^APP_URL|^FILESYSTEM_DISK|^APP_ENV|^APP_DEBUG"
```

**Attendu en prod :**

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://api.estuaireemploi.com
FILESYSTEM_DISK=public
```

> Si `APP_URL` est resté sur localhost → les `image_urls` renvoyées au front contiendront du localhost et le téléchargement échouera côté Flutter.
> Si `FILESYSTEM_DISK=local` → les fichiers sont stockés dans `storage/app/` (privé) et inaccessibles via `/storage/...`.

Après modif `.env` :

```bash
php artisan config:clear
php artisan config:cache
```

### 6. Cloudflare / proxy / WAF

Si le domaine passe par **Cloudflare** :

- Dashboard Cloudflare → **Rules → Configuration Rules** ou ancien **Page Rules** : vérifier qu'il n'y a pas de limite `Maximum Upload Size`. Sur plan gratuit, **Cloudflare bloque les uploads > 100 Mo** (suffisant ici) mais peut buffer agressivement.
- Vérifier le **WAF / Bot Fight Mode** : il peut bloquer les requêtes multipart sans User-Agent navigateur. Dio envoie un UA Dart par défaut.
  - Solution rapide : ajouter une **WAF rule** "skip Bot Fight Mode" pour le path `/api/company-products`.
- Tester en désactivant temporairement le proxy (orange cloud → grey cloud sur le DNS) pour isoler la cause.

### 7. PHP-FPM timeout / Gateway timeout

Pour 4 images, l'upload peut dépasser 30s sur 4G faible.

- nginx → PHP-FPM : `fastcgi_read_timeout 120s;` dans le `location ~ \.php$`.
- PHP-FPM pool : `request_terminate_timeout = 120` dans `/etc/php/8.x/fpm/pool.d/www.conf`.
- Flutter (`api_service.dart:131`) : `connectTimeout` est à 30s — OK pour la connexion, mais ajoute aussi un `sendTimeout` si nécessaire.

### 8. Vérifier que la requête arrive vraiment (logs Laravel)

Sur prod, surveille en direct pendant que tu testes l'upload depuis l'app :

```bash
tail -f storage/logs/laravel.log
```

Et côté nginx :

```bash
tail -f /var/log/nginx/access.log /var/log/nginx/error.log
```

**Si tu ne vois RIEN dans laravel.log** → c'est nginx/Cloudflare qui bloque (points 2 ou 6).
**Si tu vois un 422** → c'est la validation (point 1, taille).
**Si tu vois un 500** → c'est storage/permissions (points 3 ou 4).

---

## Test rapide en 30 secondes

Depuis ta machine de dev, fais un curl direct vers prod avec une petite image (< 100 Ko) :

```bash
TOKEN="ton_bearer_token"
curl -i -X POST https://api.estuaireemploi.com/api/company-products \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json" \
  -F "name=Test Diag" \
  -F "description=Description longue de test pour diagnostic" \
  -F "type=service" \
  -F "billing_type=to_discover" \
  -F "images[]=@/chemin/vers/test1.jpg" \
  -F "images[]=@/chemin/vers/test2.jpg"
```

**Lis le code de retour :**
- `201` → le backend fonctionne, le bug est côté app (cache, build prod). Rebuild + clear cache.
- `413` → nginx (point 2).
- `422` → validation (point 1, taille ou format).
- `500` → storage/permissions (points 3, 4).
- `502/504` → timeout (point 7).
- Pas de réponse / timeout curl → Cloudflare ou DNS (point 6).

---

## Ordre conseillé de vérification

1. `tail -f storage/logs/laravel.log` puis tester l'upload depuis l'app → on sait immédiatement si la requête arrive.
2. Si elle n'arrive pas → vérifier nginx `client_max_body_size` + Cloudflare.
3. Si elle arrive avec 422 → `php -i | grep post_max_size`.
4. Si elle arrive avec 500 → permissions storage + symlink.
5. Si succès mais images cassées dans l'app → `php artisan storage:link` + `APP_URL`.

---

## Une fois corrigé

- `php artisan config:cache && php artisan route:cache`
- Redémarrer PHP-FPM : `sudo systemctl restart php8.x-fpm`
- Reload nginx : `sudo systemctl reload nginx`
- Vider le cache Cloudflare si actif (Dashboard → Caching → Purge Everything).
