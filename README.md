# Estuaire Emploie - Backend Laravel

> 🇨🇲 Plateforme de gestion d'emploi pour le Cameroun avec dashboard administrateur et API REST complète.

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
