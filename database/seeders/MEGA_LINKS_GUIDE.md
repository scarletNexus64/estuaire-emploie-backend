# 📦 Guide pour obtenir des liens MEGA publics

## Comment créer des liens MEGA publics pour les tests

### Option 1 : Utiliser des vidéos de test gratuites sur MEGA

Voici quelques vidéos de test open source que vous pouvez uploader sur MEGA :

1. **Big Buck Bunny** (10s, 60s, ou version complète)
   - Télécharger depuis : https://download.blender.org/demo/movies/
   - Format : MP4, WebM
   - Licence : Creative Commons

2. **Sintel**
   - Télécharger depuis : https://durian.blender.org/download/
   - Format : MP4
   - Licence : Creative Commons

3. **Tears of Steel**
   - Télécharger depuis : https://mango.blender.org/download/
   - Format : MP4
   - Licence : Creative Commons

### Option 2 : Créer vos propres liens MEGA

#### Étapes :

1. **Créer un compte MEGA** (gratuit)
   - Aller sur https://mega.nz
   - S'inscrire (50 GB gratuit)

2. **Uploader une vidéo**
   - Cliquer sur "Upload"
   - Sélectionner votre fichier MP4
   - Attendre la fin de l'upload

3. **Obtenir le lien public**
   - Faire un clic droit sur le fichier → "Get link"
   - Choisir "Link with key" ou "Link without key"
   - Copier le lien (format : `https://mega.nz/file/xxxxx#yyyyy`)

4. **Tester le lien**
   - Ouvrir le lien dans un navigateur en navigation privée
   - Vérifier que la vidéo est accessible publiquement

### Option 3 : Utiliser mes liens de test (temporaires)

⚠️ **ATTENTION** : Ces liens sont des **exemples fictifs**. Remplacez-les par vos vrais liens.

Format d'un lien MEGA valide :
```
https://mega.nz/file/ABC123XY#defGHI456jklMNO789pqrSTU
                     ^^^^^^^^ ^^^^^^^^^^^^^^^^^^^^^^^^
                     File ID   Decryption Key
```

### Exemples de liens MEGA publics réels (vidéos de démonstration)

Si vous cherchez des liens MEGA publics existants pour tester, voici quelques suggestions :

1. **Chercher sur GitHub** :
   - Beaucoup de projets ont des exemples de vidéos MEGA publiques
   - Rechercher : `site:github.com "mega.nz/file"`

2. **Forums de développement** :
   - Stack Overflow, Reddit r/webdev
   - Rechercher des exemples de liens de test

3. **Créer votre propre collection de test** :
   ```bash
   # Télécharger Big Buck Bunny (10s)
   wget https://download.blender.org/demo/movies/BBB/bbb_sunflower_1080p_30fps_normal.mp4.zip

   # Extraire et uploader sur MEGA
   unzip bbb_sunflower_1080p_30fps_normal.mp4.zip
   # Upload manuel sur mega.nz
   ```

## 🔄 Mettre à jour le seeder avec vos liens

Une fois que vous avez vos liens MEGA, modifiez le seeder :

```php
$megaVideos = [
    [
        'title' => '☁️ MEGA - Big Buck Bunny',
        'description' => 'Vidéo de test open source hébergée sur MEGA',
        'video_type' => 'mega',
        'video_url' => 'https://mega.nz/file/VOTRE_LIEN_ICI#VOTRE_CLE',
        'duration_seconds' => 596,
        'is_preview' => true,
    ],
    // ... autres vidéos
];
```

## 🧪 Tester les liens

Avant d'exécuter le seeder, testez vos liens :

```bash
# Test avec curl
curl -I "https://mega.nz/file/xxxxx#yyyyy"

# Test dans le navigateur
# Ouvrir le lien en navigation privée
```

## 📝 Notes importantes

1. **Liens publics vs privés** :
   - Utilisez des liens **publics avec clé** pour les vidéos de formation
   - Les liens sans clé nécessitent une authentification MEGA

2. **Limites de bande passante** :
   - MEGA gratuit : 5 GB de transfert par 6 heures
   - Pour la production, considérez un compte payant

3. **Expiration** :
   - Les liens publics MEGA n'expirent généralement pas
   - Mais les fichiers peuvent être supprimés par l'utilisateur

4. **Alternative** :
   - Pour la production, considérez plutôt :
     - AWS S3 + CloudFront
     - Google Cloud Storage
     - Serveur dédié avec Nginx
     - CDN comme Bunny.net ou Cloudflare Stream

## 🚀 Exécuter le seeder

```bash
php artisan db:seed --class=TrainingVideoWithMegaSeeder
```
