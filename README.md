# Estuaire Emploie - Backend Laravel

> 🇨🇲 Plateforme de gestion d'emploi pour le Cameroun.

[![Laravel](https://img.shields.io/badge/Laravel-11.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)
[![Swagger](https://img.shields.io/badge/API-Swagger-85EA2D.svg)](http://localhost:8000/api/documentation)

## 📋 Table des Matières

- [Liens Rapides](#-liens-rapides)
- [Fonctionnalités](#fonctionnalités)
- [Installation](#installation)
- [Accès à l'Application](#-accès-à-lapplication)
- [API REST - Endpoints](#-api-rest---endpoints)
- [Structure du Projet](#structure-du-projet)
- [Technologies Utilisées](#technologies-utilisées)

## 🔗 Liens Rapides

| 🌐 Interface | 📍 URL | 🔐 Accès |
|--------------|--------|----------|
| **Dashboard Admin** | `http://localhost:8000/admin/dashboard` | admin@estuaire-emploie.com / password |
| **API Swagger** | `http://localhost:8000/api/documentation` | Documentation interactive |
| **API Base URL** | `http://localhost:8000/api` | Authentification via Bearer Token |

## Fonctionnalités

### Dashboard Admin (Interface Web)
- ✅ Vue d'ensemble avec statistiques en temps réel
- ✅ Gestion complète des entreprises (validation, suspension)
- ✅ Gestion des offres d'emploi (publication, mise en avant)
- ✅ Gestion des candidatures avec changement de statut
- ✅ Gestion des candidats
- ✅ Gestion des recruteurs et permissions
- ✅ Configuration des catégories, localisations et types de contrats

### API REST (Application Mobile)
- ✅ Documentation Swagger interactive complète
- ✅ Authentification Laravel Sanctum (Bearer Token)
- ✅ **Authentification** : Inscription, connexion, reset password, gestion profil
- ✅ **Candidats** : Consultation offres, candidatures, favoris, notifications
- ✅ **Recruteurs** : Création entreprise, publication offres, gestion candidatures
- ✅ **Jobs** : Filtres avancés (catégorie, ville, type, expérience, recherche)
- ✅ **Notifications** : Système complet avec marquage lu/non-lu
- ✅ **Favoris** : Sauvegarde et gestion des offres favorites
- ✅ **Statistiques** : Dashboard candidat et recruteur
- ✅ Pagination optimisée + Réponses JSON standardisées

### Base de Données
- **Users** : Admins, Recruteurs, Candidats
- **Companies** : Entreprises avec statut de vérification
- **Jobs** : Offres d'emploi avec statuts multiples
- **Applications** : Candidatures avec workflow complet
- **Categories, Locations, ContractTypes** : Configuration du système

## Installation

### Prérequis
- PHP 8.2 ou supérieur
- Composer
- SQLite (par défaut) ou MySQL

### Étapes d'installation

1. **Cloner le projet** (déjà fait)

2. **Installer les dépendances Composer**
```bash
composer install
```

3. **Créer la base de données SQLite**
```bash
touch database/database.sqlite
```

4. **Générer la clé de l'application**
```bash
php artisan key:generate
```

5. **Exécuter les migrations**
```bash
php artisan migrate
```

6. **Peupler la base avec des données de test**
```bash
php artisan db:seed
```

7. **Démarrer le serveur**
```bash
php artisan serve
```

8. **Accéder au dashboard**
Ouvrez votre navigateur : `http://localhost:8000/admin/dashboard`

## 🔐 Accès à l'Application

### Dashboard Admin (Interface Web)

**URL Dashboard** : `http://localhost:8000/admin/dashboard`

**Compte Administrateur** :
- **Email** : `admin@estuaire-emploie.com`
- **Mot de passe** : `password`

### API REST (Application Mobile)

**Documentation Swagger Interactive** : `http://localhost:8000/api/documentation`

**Comptes de Test API** :

#### Admin
- Email : `admin@estuaire-emploie.com`
- Mot de passe : `password`
- Rôle : `admin`

#### Recruteurs
- Email : `recruteur1@example.com` à `recruteur5@example.com`
- Mot de passe : `password`
- Rôle : `recruiter`

#### Candidats
- Email : voir les noms dans `UserSeeder.php`
- Mot de passe : `password`
- Rôle : `candidate`

## Structure du Projet

```
app/
├── Http/Controllers/Admin/
│   ├── DashboardController.php
│   ├── CompanyController.php
│   ├── JobController.php
│   ├── ApplicationController.php
│   ├── UserController.php
│   ├── RecruiterController.php
│   └── SettingsController.php
├── Models/
│   ├── User.php
│   ├── Company.php
│   ├── Job.php
│   ├── Application.php
│   ├── Recruiter.php
│   ├── Category.php
│   ├── Location.php
│   └── ContractType.php

database/
├── migrations/
│   ├── create_users_table.php
│   ├── create_companies_table.php
│   ├── create_jobs_table.php
│   └── ...
└── seeders/
    ├── DatabaseSeeder.php
    ├── UserSeeder.php
    ├── CompanySeeder.php
    └── ...

resources/views/admin/
├── layouts/
│   └── app.blade.php
├── dashboard/
│   └── index.blade.php
├── companies/
│   └── index.blade.php
├── jobs/
│   └── index.blade.php
├── applications/
│   └── index.blade.php
└── settings/
    └── index.blade.php
```

## Routes Principales

### Dashboard
- `GET /admin/dashboard` - Vue d'ensemble

### Entreprises
- `GET /admin/companies` - Liste des entreprises
- `GET /admin/companies/{id}` - Détails d'une entreprise
- `PATCH /admin/companies/{id}/verify` - Vérifier une entreprise
- `PATCH /admin/companies/{id}/suspend` - Suspendre une entreprise

### Offres d'Emploi
- `GET /admin/jobs` - Liste des offres
- `GET /admin/jobs/{id}` - Détails d'une offre
- `PATCH /admin/jobs/{id}/publish` - Publier une offre
- `PATCH /admin/jobs/{id}/feature` - Mettre en avant une offre

### Candidatures
- `GET /admin/applications` - Liste des candidatures
- `GET /admin/applications/{id}` - Détails d'une candidature
- `PATCH /admin/applications/{id}/status` - Modifier le statut

### Paramètres
- `GET /admin/settings` - Paramètres système
- `POST /admin/settings/categories` - Ajouter catégorie/localisation/type

## 📱 API REST - Endpoints

### Documentation Interactive Swagger

Accédez à la documentation complète et interactive de l'API :

**🔗 URL Swagger** : `http://localhost:8000/api/documentation`

La documentation Swagger fournit :
- ✅ Liste complète de tous les endpoints API
- ✅ Schémas de requêtes et réponses
- ✅ Exemples de code pour chaque endpoint
- ✅ Interface de test interactive (Try it out)
- ✅ Authentification Bearer Token intégrée

### Authentification API

L'API utilise **Laravel Sanctum** avec authentification par Bearer Token.

**Flow d'authentification** :
1. Inscription : `POST /api/register`
2. Connexion : `POST /api/login` → Récupérer le token
3. Utiliser le token : Header `Authorization: Bearer {token}`

### Endpoints Principaux

#### 🔑 Authentication & Profile
| Méthode | Endpoint | Auth | Description |
|---------|----------|------|-------------|
| POST | `/api/register` | ❌ | Inscription candidat |
| POST | `/api/login` | ❌ | Connexion |
| POST | `/api/logout` | ✅ | Déconnexion |
| POST | `/api/password/forgot` | ❌ | Demande reset password |
| POST | `/api/password/reset` | ❌ | Réinitialiser password |
| GET | `/api/user` | ✅ | Profil utilisateur |
| PUT | `/api/user/role` | ✅ | Changer de rôle (candidat/recruteur) |
| PUT | `/api/user/profile` | ✅ | Mettre à jour profil + photo |
| GET | `/api/user/statistics` | ✅ | Statistiques utilisateur |

#### 💼 Jobs (Offres d'Emploi)
| Méthode | Endpoint | Auth | Description |
|---------|----------|------|-------------|
| GET | `/api/jobs` | ❌ | Liste des offres + filtres |
| GET | `/api/jobs/featured` | ❌ | Offres en vedette ⭐ |
| GET | `/api/jobs/{id}` | ❌ | Détails d'une offre |
| POST | `/api/jobs` | ✅ | Créer une offre (recruteur) |
| GET | `/api/recruiter/jobs` | ✅ | Mes offres (recruteur) |
| GET | `/api/recruiter/dashboard` | ✅ | Dashboard recruteur |

**Filtres disponibles** : `category_id`, `location_id`, `contract_type_id`, `experience_level`, `search`

#### 📝 Applications (Candidatures)
| Méthode | Endpoint | Auth | Description |
|---------|----------|------|-------------|
| POST | `/api/jobs/{id}/apply` | ✅ | Postuler à une offre |
| GET | `/api/my-applications` | ✅ | Mes candidatures (candidat) |
| GET | `/api/applications/{id}` | ✅ | Détails candidature |
| GET | `/api/recruiter/applications` | ✅ | Candidatures reçues (recruteur) |
| PATCH | `/api/applications/{id}/status` | ✅ | Modifier statut (recruteur) |

#### ❤️ Favorites (Favoris)
| Méthode | Endpoint | Auth | Description |
|---------|----------|------|-------------|
| GET | `/api/favorites` | ✅ | Liste des favoris |
| POST | `/api/jobs/{id}/favorite` | ✅ | Ajouter/Retirer favori |
| GET | `/api/jobs/{id}/is-favorite` | ✅ | Vérifier si favori |

#### 🔔 Notifications
| Méthode | Endpoint | Auth | Description |
|---------|----------|------|-------------|
| GET | `/api/notifications` | ✅ | Liste notifications |
| GET | `/api/notifications/unread-count` | ✅ | Nombre non lues |
| PUT | `/api/notifications/{id}/read` | ✅ | Marquer comme lue |
| PUT | `/api/notifications/read-all` | ✅ | Tout marquer comme lu |
| DELETE | `/api/notifications/{id}` | ✅ | Supprimer notification |

#### 🏢 Companies (Entreprises)
| Méthode | Endpoint | Auth | Description |
|---------|----------|------|-------------|
| GET | `/api/companies` | ❌ | Liste entreprises |
| GET | `/api/companies/{id}` | ❌ | Détails + offres |
| POST | `/api/companies` | ✅ | Créer entreprise (recruteur) |
| GET | `/api/my-company` | ✅ | Mon entreprise (recruteur) |
| PUT | `/api/my-company` | ✅ | Modifier entreprise (recruteur) |

#### 📑 Categories & Filters
| Méthode | Endpoint | Auth | Description |
|---------|----------|------|-------------|
| GET | `/api/categories` | ❌ | Catégories métiers |
| GET | `/api/locations` | ❌ | Villes du Cameroun |
| GET | `/api/contract-types` | ❌ | Types de contrats |

### Exemple d'utilisation

```bash
# 1. Connexion
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@estuaire-emploie.com","password":"password"}'

# Réponse: {"token":"1|abc123...","user":{...}}

# 2. Récupérer les offres (avec token)
curl http://localhost:8000/api/jobs \
  -H "Authorization: Bearer 1|abc123..."

# 3. Postuler à une offre
curl -X POST http://localhost:8000/api/jobs/1/apply \
  -H "Authorization: Bearer 1|abc123..." \
  -H "Content-Type: application/json" \
  -d '{"cover_letter":"Je suis intéressé..."}'
```

### Documentation Complète

Pour plus de détails, consultez :
- **Documentation API complète** : `API_DOCUMENTATION.md`
- **Documentation Swagger** : `http://localhost:8000/api/documentation`
- **Collection Postman** : Import depuis `http://localhost:8000/docs/api-docs.json`

## Données de Test

Le système est livré avec des données de test incluant :
- 1 admin
- 5 recruteurs
- 15 candidats
- 6 entreprises (4 vérifiées, 2 en attente)
- 5 offres d'emploi
- Plusieurs candidatures avec différents statuts
- 15 catégories professionnelles
- 14 villes du Cameroun
- 8 types de contrats

## Prochaines Étapes

Pour développer davantage la plateforme :

1. **Authentification**
   - Implémenter Laravel Breeze ou Laravel Sanctum
   - Ajouter la protection des routes

2. **API REST**
   - Créer des endpoints API dans `routes/api.php`
   - Ajouter des API Resources pour formater les réponses

3. **Frontend Candidat/Recruteur**
   - Créer des interfaces pour les candidats
   - Dashboard recruteur avec gestion d'offres

4. **Fonctionnalités Avancées**
   - Recherche avancée et filtres
   - Upload de CV et documents
   - Système de messagerie interne
   - Notifications par email
   - Statistiques et rapports avancés

5. **Paiements**
   - Intégration Mobile Money (MTN, Orange)
   - Gestion des abonnements premium

## Technologies Utilisées

### Backend
- **Laravel 11** - Framework PHP moderne
- **Laravel Sanctum** - Authentification API (Bearer Token)
- **SQLite** - Base de données (configurable pour MySQL/PostgreSQL)

### Frontend
- **Blade** - Templating engine Laravel
- **CSS Vanilla** - Styles personnalisés sans framework

### Documentation & API
- **Swagger/OpenAPI** - Documentation interactive (L5-Swagger)
- **API Resources** - Formatage standardisé des réponses JSON

### Outils de Développement
- **Composer** - Gestionnaire de dépendances PHP
- **Artisan** - CLI Laravel pour migrations, seeders, etc.

## Support

Pour toute question ou problème, consultez la documentation Laravel : https://laravel.com/docs

## License

MIT License


## Récupération des entreprises précréées

L’application propose un rattachement au compte connecté depuis l’onglet entreprise,
et une récupération du compte précréé depuis la connexion. Nom, téléphone, fonction
et explication libre suffisent ; aucun justificatif d’immatriculation obligatoire.
Le parcours sans connexion vérifie l’email de réponse par code avant dépôt.

L’administration traite les demandes dans `/admin/company-recoveries` avec la
permission `manage_companies` (et `manage_users` pour le compte complet). Elle
examine les demandes concurrentes et confirme sa vérification avant d’approuver.
L’approbation verrouille l’entreprise en base, clôture les demandes concurrentes et
révoque les anciens accès concernés. Seuls les comptes précréés encore inutilisés
et liés à une seule entreprise peuvent être récupérés par ce parcours.

Activation sur un environnement :

```sh
php artisan migrate
php artisan companies:prepare-recovery
php artisan companies:prepare-recovery --apply
```

La préparation utilise `entreprises_douala_yaounde_bafoussam.xlsx` pour identifier
les données importées. Sans `--apply`, elle ne modifie rien. Les prochains imports
conservent une clé source stable, même après changement de l’email de contact.

Le mailer est `brevo` par défaut (`COMPANY_RECOVERY_MAILER` pour le modifier), avec
l’expéditeur `SUPPORT_MAIL_FROM`. Maintenir un worker dédié avec
`php artisan queue:work --queue=company-recovery --tries=4 --sleep=3` et le scheduler Laravel. `company-recoveries:deliver` relance les envois non livrés toutes
les cinq minutes ; l’administration peut aussi les relancer depuis la demande.
Les notifications en application sont persistées immédiatement. Les mots de passe
temporaires sont chiffrés en attente d’envoi, effacés après livraison et valables
48 heures. Ils doivent être changés à la première connexion. La régénération admin
est bloquée après personnalisation du mot de passe.

Déployer le frontend et le backend ensemble : la vérification OTP du mot de passe
oublié retourne désormais `reset_token`, à transmettre comme `token` à
`POST /api/password/reset`. Un simple état OTP « vérifié » ne suffit plus.
Les anciens clients doivent être mis à jour pour réinitialiser leur mot de passe.

Tests ciblés : `php artisan test --filter='CompanyRecoveryTest|EntreprisesCamerounSeederTest'`.

### Notifications push : exploitation

Deux workers distincts doivent rester actifs sous Supervisor ou systemd. Un worker
qui ne surveille que `company-recovery` ne traite pas les publications.

```sh
php artisan queue:work --queue=notifications --tries=3 --sleep=3 --timeout=660
php artisan queue:work --queue=company-recovery --tries=4 --sleep=3 --timeout=60
```

Les autres tâches applicatives continuent d'utiliser leur worker habituel.
`QUEUE_RETRY_AFTER=900` (valeur par défaut des connexions database et redis) doit
rester supérieur au timeout des jobs ; certains anciens jobs durent 600 secondes.
Après déploiement ou changement de configuration, recharger le cache de configuration
selon le déploiement et exécuter `php artisan queue:restart`. Le gestionnaire de
processus doit redémarrer les workers. Maintenir aussi le scheduler Laravel actif.

`FIREBASE_CREDENTIALS` doit désigner un fichier JSON de compte de service valide du
projet Firebase utilisé par les applications. Ne jamais committer ce fichier.
`invalid_grant / Invalid JWT Signature` indique une clé refusée par Google : remplacer
la clé dans la configuration, puis redémarrer les workers. Un fichier présent sur
disque ne garantit pas que sa clé est encore valide. Sur iOS, la clé APNs doit aussi
être configurée dans Firebase, avec les capacités Push Notifications et Background
Modes / Remote notifications de l'application signée.

Les demandes de services utilisent maintenant le topic `all_users`, sans vagues
différées, auteur inclus. `notified_at` est renseigné uniquement après acceptation
par FCM. Les demandes rejetées, supprimées ou sorties du fil de 30 jours sont ignorées.
La récupération conserve son envoi en attente si aucun appareil n'est enregistré ou
si Firebase refuse le push. `sent_at` signifie accepté par le transport, pas affiché
sur le téléphone. Après réparation de la configuration, l'administration peut
relancer la livraison depuis la demande, ou le scheduler peut la reprendre.

Avant de relancer une ancienne file, examiner les tâches accumulées : ne pas rejouer
en masse des annonces historiques. Pour vérifier le transport sans diffuser de
message, utiliser le mode `validate_only` de Firebase. Les tests applicatifs utilisent
un transport simulé : `php artisan test --filter=PushDeliveryTest`.
