# 📦 Guide des Seeders - Contenu Étudiant

## ✅ Données Créées

### 🎓 Packs de Formation (5 packs)
1. **Formation Laravel Complète 2026** - 25,000 XAF (5 vidéos YouTube)
2. **React JS - Développement Frontend Moderne** - 20,000 XAF (3 vidéos)
3. **Python pour Débutants - Formation Complète** - 18,000 XAF (2 vidéos)
4. **Marketing Digital - Stratégies 2026** - 22,000 XAF (3 vidéos)
5. **Excel Avancé - Maîtrise Complète** - 15,000 XAF (2 vidéos)

### 📚 Packs d'Épreuves (6 packs)
1. **BTS Informatique 2026 - Pack Complet** - 15,000 XAF
2. **BTS Informatique 2025 - Annales Corrigées** - 12,000 XAF
3. **BTS Gestion 2026 - Épreuves Complètes** - 14,000 XAF
4. **Licence Informatique 2026 - 1ère Année** - 18,000 XAF
5. **BTS Commerce International 2026** - 13,000 XAF
6. **Master Finance 2026 - Semestre 1** - 20,000 XAF

### 🎥 Vidéos de Formation (15 vidéos)
Toutes avec de vrais liens YouTube de formations gratuites :
- Laravel (5 vidéos)
- React JS (3 vidéos)
- Python (2 vidéos)
- Marketing Digital (3 vidéos)
- Excel (2 vidéos)

### 📄 Épreuves (22 épreuves)
Couvrant : Informatique, Gestion, Commerce, Finance

---

## 🚀 Exécuter les Seeders

### Tous les seeders (base complète)
```bash
php artisan db:seed --force
```

### Uniquement Contenu Étudiant
```bash
php artisan db:seed --class=TrainingVideoSeeder --force
php artisan db:seed --class=TrainingPackSeeder --force
php artisan db:seed --class=ExamPackSeeder --force
```

---

## 📸 Ajouter de Vraies Images

### Images de Couverture pour Packs de Formation
Téléchargez des images depuis :
- [Unsplash](https://unsplash.com/) - Rechercher : "coding", "laptop", "study"
- [Pexels](https://pexels.com/) - Gratuit pour usage commercial

Placez-les dans :
```
storage/app/public/training_packs/covers/
```

Noms suggérés :
- `laravel-cover.jpg`
- `react-cover.jpg`
- `python-cover.jpg`
- `marketing-cover.jpg`
- `excel-cover.jpg`

### Images de Couverture pour Packs d'Épreuves
Placez dans :
```
storage/app/public/exam_packs/covers/
```

Noms suggérés :
- `bts-informatique-2026.jpg`
- `bts-gestion-2026.jpg`
- `licence-informatique-2026.jpg`

---

## 📄 Ajouter de Vrais PDFs d'Épreuves

### Sources de PDFs d'épreuves
1. **Annales BTS** : https://www.bankexam.fr/etablissement/1-BTS
2. **Sujets Cameroun** : Sites d'examens officiels
3. **PDF Gratuits** : Rechercher "annales BTS PDF gratuit"

### Organisation des PDFs
Placez dans :
```
storage/app/public/exam_papers/
```

Structure suggérée :
```
exam_papers/
  ├── informatique/
  │   ├── algorithmique-bts-2026.pdf
  │   ├── base-de-donnees-bts-2026.pdf
  │   └── developpement-web-bts-2026.pdf
  ├── gestion/
  │   ├── comptabilite-bts-2026.pdf
  │   └── controle-gestion-bts-2026.pdf
  └── commerce/
      └── techniques-vente-bts-2026.pdf
```

---

## 🎨 Personnaliser les Seeders

### Modifier les Prix
Éditez : `database/seeders/TrainingPackSeeder.php` ou `ExamPackSeeder.php`

```php
'price_xaf' => 25000, // Changez ici
'price_usd' => 45,
'price_eur' => 38,
```

### Ajouter Plus de Vidéos YouTube
Éditez : `database/seeders/TrainingVideoSeeder.php`

```php
[
    'title' => 'Votre Titre',
    'description' => 'Description...',
    'video_type' => 'youtube',
    'video_url' => 'https://www.youtube.com/watch?v=...',
    'duration_seconds' => 1800,
    'duration_formatted' => '30:00',
    'is_preview' => true, // Gratuit
],
```

### Ajouter Plus de Packs
Ajoutez dans le tableau `$packs` du seeder correspondant.

---

## 🔄 Réinitialiser et Re-Seeder

### ATTENTION : Supprime TOUTES les données !
```bash
php artisan migrate:fresh --seed --force
```

### Supprimer uniquement le Contenu Étudiant
```bash
# Pas de commande directe, mais vous pouvez :
php artisan tinker
> \App\Models\ExamPack::truncate();
> \App\Models\TrainingPack::truncate();
> \App\Models\TrainingVideo::truncate();
> \App\Models\ExamPaper::truncate();
> exit

# Puis re-seeder
php artisan db:seed --class=TrainingVideoSeeder --force
php artisan db:seed --class=TrainingPackSeeder --force
php artisan db:seed --class=ExamPackSeeder --force
```

---

## 📊 Vérifier les Données

```bash
php artisan tinker
```

Puis exécuter :
```php
// Compter les packs
\App\Models\TrainingPack::count();
\App\Models\ExamPack::count();

// Voir un pack avec ses vidéos
$pack = \App\Models\TrainingPack::with('trainingVideos')->first();
$pack->name;
$pack->trainingVideos->count();

// Voir un pack d'épreuves
$examPack = \App\Models\ExamPack::with('examPapers')->first();
$examPack->name;
$examPack->examPapers->count();
```

---

## 🎯 Accès Admin

Une fois seedé, accédez aux pages admin :

```
http://0.0.0.0:8001/admin/exam-packs
http://0.0.0.0:8001/admin/training-packs
http://0.0.0.0:8001/admin/training-videos
```

---

## 🌟 Fonctionnalités

✅ Packs payants (prix XAF, USD, EUR)
✅ Vidéos YouTube intégrées
✅ Vidéos aperçu gratuites (is_preview)
✅ PDFs d'épreuves téléchargeables
✅ Statistiques (vues, achats)
✅ Packs mis en avant (featured)
✅ Système de filtres et recherche
✅ API complète pour mobile

---

## 🐛 Problèmes Courants

### Erreur "Column not found"
```bash
php artisan migrate:fresh --force
php artisan db:seed --force
```

### Images ne s'affichent pas
```bash
php artisan storage:link
```

### Cache problématique
```bash
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan config:clear
```

---

**Fait avec ❤️ pour E-Emploi Estuaire**
