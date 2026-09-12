# Plan d'intégration — Bannières, Jobs étudiants & INSAM-IA

> Source : `document final.pdf` (2 pages)
> Cible : `estuaire-emploie-backend` (Laravel 11, PHP 8.2, Sanctum)
> Date d'analyse : 2026-09-11
> Statut : **analyse terminée, implémentation non commencée**

---

## 0. Résumé exécutif

Trois chantiers indépendants, livrables séparément :

| # | Chantier | Existant réutilisable | Effort |
|---|---|---|---|
| **A** | 13 bannières par défaut avec redirection | `advertisements` (table + API + tracking) | Moyen |
| **B** | 4 jobs étudiants prédéfinis (apporteurs d'affaires + coursier) | `quick_services` + `service_categories` + `StudentQuickServiceSeeder` | Moyen |
| **C** | Intégration INSAM-IA (QCM, ressources IA, révision) | Aucun — nouveau module | Élevé |

**Trois gaps bloquants identifiés :**

1. La table `advertisements` **n'a aucune colonne de redirection** (`url`/`link`/`deeplink`). L'endpoint `POST /advertisements/{id}/click` incrémente un compteur **sans destination**. C'est exactement l'objet du point 1 du PDF.
2. Il **n'existe aucune notion de bannière « par défaut »/fallback**. `AdvertisementController::index()` retourne une collection vide s'il n'y a rien à diffuser.
3. Le PDF mentionne des « ressources actu » existantes. **Aucun module news/actualités/articles n'existe** dans le repo. Ce qui existe est du contenu pédagogique (`roadmaps`, `exam_packs`, `training_packs`). À clarifier avant de coder le C-3.

---

## 1. Ce que dit le document final.pdf

### 1.1 — Redirections des 13 bannières publicitaires standard

| # | Bannière | Redirection demandée | Cible technique probable |
|---|---|---|---|
| 1 | Développez votre **carrière** | Formation vidéo spécifique « trouver le job qui vous correspond » | `training_packs` / `training_videos` |
| 2 | Trouvez votre emploi de **rêve** | Liste d'offres d'emploi encore valides | `GET /api/jobs` |
| 3 | Trouvez les meilleurs talents | CV des candidats (CVthèque) | CVthèque recruteur |
| 4 | Rejoignez notre **communauté** | Groupe Telegram (à créer) | URL externe |
| 5 | Formation certifiante | Tutoriels de formation | `training_packs` |
| 6 | Stage et Alternance | Liste des thèmes de stage / offres de stage entreprises | `jobs` filtrés `contract_type.slug=stage` |
| 7 | JOB à distance | Jobs étudiants | `quick_services` (chantier B) |
| 8 | Startups qui recrutent | Parc entreprise | `GET /api/companies` |
| 9 | **Préparer** un entretien | Vidéo développement personnel | `training_videos` |
| 10 | Réseautage pro | **`??????` — non défini dans le PDF** | ⚠️ **À clarifier** |
| 11 | Web binaire | Lance l'appel vidéo → redirigé vers le gold center | ⚠️ **À clarifier** (mécanisme d'appel vidéo) |
| 12 | Salaire attractif | Job étudiant | `quick_services` |
| 13 | Évaluation Rapide | Tutoriels de formation (vidéo) | `training_videos` |

### 1.2 — Jobs étudiants standard (4 services prédéfinis)

| # | Service | Rémunération (verbatim PDF) |
|---|---|---|
| 1 | **Apporteur d'affaires — Estuaire Eat** | 5 % sur le **premier mois de chiffre d'affaires** généré par chaque partenaire recommandé (restaurant/commerce), **ou** prime fixe de mise en relation si le partenaire s'inscrit et reste actif après 30 jours |
| 2 | **Apporteur d'affaires — Estuaire Achats** | **3 % sur la valeur de la première transaction** réalisée grâce à la recommandation, **plafonnée à un montant à définir** pour éviter les abus sur les grosses transactions |
| 3 | **Apporteur d'affaires — Estuaire Emploi** | 5 % à chaque transaction pour un étudiant d'un autre établissement recommandé ; **1000 FCFA par entreprise** inscrite et validée, active ≥ 1 mois |
| 4 | **Coursier — Merci-E** | **50 % du montant de la course** (le reste à la plateforme pour gestion/mise en relation/support). Système de **notation par les clients**, priorisation des mieux notés |

### 1.3 — INSAM-IA (points 4 & 5 + section Ressources)

- **4.** Intégration `insam-ia.com` — « à intégrer avec Kira »
- **5.** Questions aléatoires (QCM)
- **Ressources 1.** Packs d'épreuve : module insam-ia
- **Ressources 3.** Spécialités **sans formation vidéo** : IH, GMH, Froid et climatisation, Santé, PV, PA, Mécatronique, HSE
- **Ressources 4.** Progression de lecture + évaluation, puis **attestation en fin de parcours** selon la note d'évaluation
- **Ressources 5.** Module révision insam-ia.com

---

## 2. État des lieux du codebase

### 2.1 Bannières → `advertisements`

Pas de modèle `Banner`/`Slider`. Tout passe par `Advertisement` avec `ad_type = 'homepage_banner'`.

**Colonnes existantes** (`database/migrations/2025_12_12_092701_create_advertisements_table.php` + 3 migrations d'extension) :

```
id, title, description, image, background_color, overlay_opacity
ad_type ENUM('homepage_banner','search_banner','featured_company','sidebar','custom')
start_date, end_date
impressions_count, clicks_count, ctr, display_order
is_active, status ENUM('active','paused','expired','completed')
company_id, created_by_user_id, content_type, target_audience, target_countries (json)
budget, target_reach, payment_id, source
timestamps, softDeletes
```

**Manquant :** `url` / `link` / `deeplink` / `is_default` / `is_fallback`.

- Modèle : `app/Models/Advertisement.php` — scopes `currentlyActive()` L.108, `forAudience()` L.79, `forCountry()` L.94
- Controller : `app/Http/Controllers/Api/AdvertisementController.php` — `index()` L.31 (mapping manuel, pas de Resource), `recordImpression()` L.99, `recordClick()` L.128
- Routes : `routes/api.php` L.119-121 (publiques, sans throttle ni dédup)
- i18n : `lang/{fr,en,es,ar}/advertisement.php` — 2 clés seulement
- **Aucun seeder d'Advertisement n'existe**

**Pattern deeplink à réutiliser** — `app/Models/Job.php` :
```php
public function shareUrl(): string  // L.143 → config('app.share_base_url') . "/jobs/{id}/share"
public function deepLink(): string  // L.155 → config('app.app_scheme') . "://job/{id}"
```
`.env` : `APP_SCHEME=estuaireemploi`, `SHARE_BASE_URL`. Doc existante : `DEEPLINK_PARTAGE_OFFRES.md`.

### 2.2 Jobs étudiants → `quick_services`

`quick_services` correspond déjà à la notion de « jobs rapides » du PDF (commentaire `routes/api.php` L.126 : « Services rapides / petits jobs »).

```
user_id, service_category_id, title, description
price_type ENUM('fixed','range','negotiable'), price_min, price_max
latitude (NOT NULL), longitude (NOT NULL), location_name
urgency ENUM('urgent','this_week','this_month','flexible')
desired_date, estimated_duration
status ENUM('open','in_progress','completed','cancelled')  + 'pending'/'approved' ajoutés
approved_at, expires_at, images (json), views_count, language
```

- `QuickService::scopeApproved()` L.107 → `whereIn('status', ['approved','open','in_progress','completed'])`
- Publication réservée aux **recruteurs** (`QuickServiceController::store`), modération par admin
- **`database/seeders/StudentQuickServiceSeeder.php` existe déjà** (243 lignes) : cours particuliers, soutien scolaire, livraison, ménage, baby-sitting, dépannage info — mais **aucun des 4 services du PDF**
- `service_categories` : `name, slug, description, icon, color, display_order, is_active`
- i18n : `lang/*/quick_service.php` (15 clés)

⚠️ **Frictions à résoudre pour le chantier B :**
- `latitude`/`longitude` sont **NOT NULL** — or les 4 services du PDF sont nationaux/non géolocalisés
- `price_type` (`fixed`/`range`/`negotiable`) **ne modélise pas une commission en %** ni un plafond
- Le PDF décrit des **programmes permanents d'affiliation**, pas des missions ponctuelles postées par un recruteur

### 2.3 Services / achats (⚠️ nommage)

**Aucun modèle `RecruiterService*` n'existe.** Les équivalents :
- Recruteur : `AddonServiceConfig` / `CompanyAddonService` / `UserAddonService`
- Candidat : `PremiumServiceConfig` / `UserPremiumService` (dont `service_type = 'student_mode'`)
- Achat 100 % **wallet** : `RecruiterServicePurchaseService::processPurchase()` L.161 → débit `freemopay_wallet_balance`/`paypal_wallet_balance` → `Payment` → `WalletTransaction` → activation
- ⚠️ Bug latent repéré : `return ['success'=>false]` L.174-182 **après `DB::beginTransaction()` sans rollback**

Référentiel commission existant : `ReferralCommissionService` + migration `2026_07_11_171433_add_referral_balance_and_transfer_tracking` → **base solide pour les apporteurs d'affaires**.

### 2.4 Contenu pédagogique existant

| Besoin PDF | Existant | Emplacement |
|---|---|---|
| QCM / quiz | ✅ | `roadmap_questions`, `RoadmapController` (`options` json, `correct_answers` json, `pass_threshold`, `quiz_scores`) |
| Formation vidéo / tutoriels | ✅ | `training_packs`, `training_videos`, `training_video_chapters`, `training_video_completions` |
| Packs d'épreuve / révision | ✅ | `exam_packs`, `exam_papers`, `exam_pack_papers` |
| Progression + XP | ✅ | `user_roadmap_progress` (`current_level`, `completed_levels`, `quiz_scores`, `total_xp`) |
| Attestation / certificat | ❌ | **à créer** |
| « Ressources actu » / news | ❌ | **n'existe pas** — à clarifier |

### 2.5 Conventions du projet (à respecter)

- **Pas** de `app/Http/Resources`, **pas** de trait `ApiResponse`, **pas** de Form Requests hors `Auth/`
- Validation inline `$request->validate([...])` dans chaque méthode
- Réponses : `response()->json(['success' => bool, 'message' => ..., 'data' => ...])`, retour typé `: JsonResponse`
- Annotations Swagger `@OA\*` sur chaque endpoint
- i18n 4 locales `fr/en/es/ar` + trait `HasTranslations` (table polymorphe `translations`) pour les données en base
- **Pattern client HTTP externe à copier : `app/Services/Gfs/GfsService.php`** — `$baseUrl/$apiKey/$timeout` depuis `config('services.*')`, méthode `isConfigured()` pour dégradation gracieuse, `Http::` + `Log::`, échec non bloquant
- Tests : **1 seul** (`tests/Feature/SmokeTest.php`)

---

## 3. API INSAM-IA — exploration complète (testée en live)

- **Base URL :** `https://insam-ia.com`
- **Spec OpenAPI :** `https://insam-ia.com/api-docs/openapi.json` (l'UI est sur `/api-docs/index.html`)
- **Titre :** « INSAM-IA API Externe » v1.0.0 — *« API pour intégrer les fonctionnalités d'INSAM-IA dans des applications tierces (Estuaire Emploi, etc.) »*

### 3.1 Authentification — ⚠️ deux mécanismes distincts

| Mécanisme | Header | Portée |
|---|---|---|
| **Clé API** | `X-API-Key: insam-rh-secret-2026` (ou `?api_key=`) | `/api/external/*` uniquement |
| **Token Sanctum** | `Authorization: Bearer <token>` via `POST /api/login` | `/api/me`, `/api/chat`, `/api/revision-cards` |

> ⚠️ La doc indique le header `X-API-Key`, **pas** `key` comme mentionné dans la demande initiale. Les deux formes fonctionnent en query (`?api_key=`).
> ⚠️ La clé `insam-rh-secret-2026` **ne donne pas accès** aux endpoints Sanctum (`/api/chat` → 401). Il faut **un compte utilisateur INSAM-IA dédié** pour le chat IA et les fiches de révision.

### 3.2 Endpoints vérifiés

| Méthode | Endpoint | Auth | Testé | Note |
|---|---|---|---|---|
| GET | `/api/public/stats` | — | ✅ 200 | `{users:11, categories:47, videos:2}` |
| GET | `/api/public/categories` | — | ✅ 200 | 47 filières (nom, filiere_name, description, icon, image, sort_order) |
| GET | `/api/public/categories/{id}` | — | ✅ | |
| GET | `/api/public/categories/{id}/videos` | — | ✅ 200 | |
| GET | `/api/public/categories/{id}/roadmap` | — | ✅ 200 | `step_number, title, description, level, duration, skills, icon, color` |
| GET | `/api/public/categories/{id}/debouches` | — | ✅ 200 | `title, description, icon, ai_details` (JSON : outils, salaires…) |
| GET | `/api/public/categories/{id}/certifications` | — | ✅ 200 | vide sur cat. 9 |
| GET | `/api/public/videos` | — | ✅ 200 | **2 vidéos seulement en base** |
| GET | `/api/public/videos/{id}` | — | ✅ | |
| GET | `/api/public/documents` | — | ✅ 200 | documents avec `content` texte intégral |
| **POST** | **`/api/external/course-materials`** | **X-API-Key** | ✅ 200 | body `{"codes":["INF111",...]}` → UE + documents |
| **GET** | **`/api/external/course-materials/{id}`** | **X-API-Key** | ✅ 200 | contenu textuel complet |
| GET | `/api/evaluation-sessions/active` | — | ✅ 200 | sessions ouvertes (id, title, specialite, matiere, duration_minutes, questions_count, opens_at, closes_at) |
| **POST** | **`/api/evaluation-sessions/start`** | — | ✅ 201 | ⚠️ **lent (génération IA)** |
| **POST** | **`/api/evaluation-sessions/{attemptId}/submit`** | — | ✅ 200 | score + corrections + explications |
| POST | `/api/chat` | Bearer | ⚠️ 200 | **répond une erreur applicative** (voir 3.5) |
| GET | `/api/me` | Bearer | ✅ 200 | |
| POST | `/api/login` / `/api/register` | — | ✅ 200 | |
| GET | `/api/plans` | — | ✅ 200 | Gratuit / Premium 2500 / Pro 5000 |
| GET | `/api/marketplace` | — | ✅ 200 | **vide (total: 0)** |

### 3.3 Endpoints **non documentés** découverts par sondage

| Méthode | Endpoint | Auth | Résultat |
|---|---|---|---|
| **GET** | **`/api/revision-cards`** | Bearer | ✅ 200 — fiches de révision paginées (`user_id, category_id, title, content` markdown) |
| **POST** | **`/api/revision-cards/generate`** | Bearer | ✅ 200 — génère une fiche IA depuis `{"category_id": 9}` |
| **GET** | **`/api/exams`** | Bearer | ✅ 200 — épreuves (`title, exam_type:'bts', category_id, file_path, correction_path, is_corrected, downloads_count`) |

> Ces 3 endpoints couvrent directement les points **Ressources 1 (packs d'épreuve)** et **Ressources 5 (module révision)** du PDF. **À faire confirmer par l'équipe INSAM-IA** (non contractuels car hors spec OpenAPI).

Retournent 404 : `/api/revision`, `/api/quiz`, `/api/predictions`, `/api/simulations`, `/api/exam-packs`, `/api/packs`, `/api/subjects`, `/api/progress`, `/api/attestation`, `/api/certificates`, `/api/ue`, `/api/documents`, `/api/videos`, `/api/categories`, et tous les `/api/external/*` autres que `course-materials`.

### 3.4 Flux QCM validé de bout en bout

```bash
# 1. Sessions actives
GET /api/evaluation-sessions/active
# → {"sessions":[{"id":749,"title":"Rattrapage ...","specialite":"IM1",
#     "duration_minutes":45,"questions_count":20,"opens_at":..,"closes_at":..}]}

# 2. Démarrer (⚠️ LENT — génération IA)
POST /api/evaluation-sessions/start
{"session_id":749,"nom":"...","prenom":"...","matricule":"...","specialite":"IM1"}
# → 201 {"attempt_id":9,"questions":[{"index":0,"question":"...","options":[4 choix]}],
#         "duration_minutes":45,"session_title":"...","started_at":"..."}

# 3. Soumettre
POST /api/evaluation-sessions/9/submit
{"answers":{"0":0,"1":0,"2":1}}     # index question → index réponse
# → 200 {"score":10,"total":20,"percentage":50,
#         "corrections":[{question, options, submitted, correct_answer, is_correct, explanation}]}
```

**Contraintes relevées :**
- **Une seule tentative par étudiant (nom + prénom) par session** → 403 sinon
- Les **bonnes réponses ne sont PAS exposées** au `start` (seulement au `submit`) — bonne propriété de sécurité
- `start` a **timeout à 25 s** lors du 1er test (HTTP 000), a abouti au 2e essai. **Génération IA synchrone** → obligatoire de traiter en **job asynchrone** côté Estuaire avec timeout ≥ 180 s
- Session id 749 testée (spécialité IM1) : 20 questions générées, corrections avec explications pédagogiques

### 3.5 ⚠️ Anomalies constatées côté INSAM-IA

1. **`POST /api/chat` est cassé** pour le compte admin testé :
   `{"reply":"Erreur API: prompt is too long: 2159239 tokens > 200000 maximum"}` — HTTP **200** malgré l'erreur.
   → À signaler à l'équipe INSAM-IA avant toute intégration du chat. **Ne pas mettre le chat IA en dépendance dure.**
2. **Pas d'en-têtes de rate limiting** exposés (`X-RateLimit-*` absents) → limites inconnues, prévoir back-off et cache.
3. **Contenu de démo faible** : 2 vidéos, marketplace vide. Les volumes réels sont à confirmer avant de promettre des fonctionnalités côté app.
4. **Données de test peu cohérentes** : `POST /api/external/course-materials` avec `["INF111","MAT121","GSI114"]` renvoie « Histoire de la réforme forestière » pour un code informatique → **le mapping code UE ↔ contenu est à valider** avec INSAM-IA.
5. Les identifiants de démo de la doc (`admin@insam-ia.cm` / `admin123`) sont **fonctionnels en production** — à signaler (risque de sécurité côté INSAM-IA).

---

# CHECKLIST A — Bannières par défaut avec redirection

**Objectif :** 13 bannières standard qui défilent en fallback quand aucune bannière publicitaire payante n'est disponible, chacune avec une redirection définie.

### A-1. Base de données

- [ ] Migration `add_redirect_to_advertisements_table` :
  - `redirect_type` ENUM(`none`,`internal_route`,`external_url`,`deeplink`) default `none`
  - `redirect_target` string nullable *(route interne, URL, ou deeplink)*
  - `redirect_params` json nullable *(ex. `{"contract_type_slug":"stage"}`)*
- [ ] Migration `add_default_flag_to_advertisements_table` :
  - `is_default` boolean default `false` (indexé)
  - `default_order` unsignedInteger default `0`
  - Index composite `(is_default, is_active, default_order)`
- [ ] Vérifier l'idempotence (`Schema::hasColumn`) — convention déjà appliquée dans `2026_06_14_100000`

### A-2. Modèle

- [ ] `Advertisement` : ajouter les 5 colonnes au `$fillable`, caster `redirect_params` en `array`, `is_default` en `bool`
- [ ] `scopeDefaults()` → `where('is_default', true)->where('is_active', true)->orderBy('default_order')`
- [ ] Accessor `getRedirectAttribute()` → payload normalisé `{type, target, params}` pour le mobile
- [ ] Ajouter `redirect` à `$appends` (pattern `Job::$appends` L.26)
- [ ] `title`/`description` → `$translatable` (trait `HasTranslations`) pour les 4 locales

### A-3. Contenu des 13 bannières

- [ ] Créer `database/seeders/DefaultAdvertisementSeeder.php` (idempotent via `updateOrCreate` sur un slug/titre)
- [ ] Enregistrer dans `DatabaseSeeder.php`
- [ ] Mapper les 13 redirections (cf. §1.1)
- [ ] ⚠️ **Clarifier avec le client : bannière 10 « Réseautage pro » (`??????` dans le PDF)**
- [ ] ⚠️ **Clarifier : bannière 11 « Web binaire » — mécanisme d'appel vidéo + définition du « gold center »**
- [ ] ⚠️ **Obtenir le lien du groupe Telegram (bannière 4) — à créer selon le PDF**
- [ ] Fournir les 13 visuels (ou définir `background_color` + `overlay_opacity` en attendant)
- [ ] Traduire les 13 titres/descriptions en `en`/`es`/`ar`

### A-4. API

- [ ] `AdvertisementController::index()` : **fallback** — si la collection ciblée est vide → retourner `scopeDefaults()`
- [ ] Ajouter `redirect_type`, `redirect_target`, `redirect_params`, `is_default` au mapping de sortie
- [ ] Flag `is_fallback` dans la réponse pour que le mobile sache qu'il affiche les défauts
- [ ] `recordClick()` : retourner la destination de redirection en plus d'incrémenter
- [ ] Ne **pas** compter les impressions/clics des bannières par défaut dans les stats des campagnes payantes
- [ ] Throttle sur `impression`/`click` (actuellement aucune protection, routes publiques)
- [ ] Mettre à jour les annotations `@OA\*`

### A-5. Admin

- [ ] `Admin/AdvertisementController` : champs redirection au create/update (+ validation `redirect_type` / URL)
- [ ] Écran de gestion des bannières par défaut (ordre de défilement, activation)
- [ ] Empêcher la suppression d'une bannière par défaut (ou re-seed automatique)

### A-6. i18n & tests

- [ ] Étendre `lang/*/advertisement.php` (2 clés actuellement)
- [ ] Test : `index()` sans campagne active → renvoie les 13 défauts
- [ ] Test : `index()` avec campagne active → ne renvoie **pas** les défauts
- [ ] Test : ciblage `target_audience` / `target_countries` inchangé
- [ ] Test : `recordClick` renvoie la bonne destination

---

# CHECKLIST B — Jobs étudiants sous forme de services (jobs rapides)

**Objectif :** prédéfinir les 4 programmes du PDF (3 apporteurs d'affaires + 1 coursier) comme services disponibles aux étudiants.

### B-0. Décision d'architecture — ⚠️ à trancher avant de coder

Les 4 items du PDF sont des **programmes d'affiliation permanents à commission**, pas des missions ponctuelles géolocalisées. Trois options :

| Option | Description | Verdict |
|---|---|---|
| **1** | Étendre `quick_services` (lat/lng nullable, `price_type='commission'`) | Simple, mais dénature le modèle « mission ponctuelle » |
| **2** | Nouvelle table `student_programs` + `student_program_enrollments` | Propre, modélise commissions/plafonds/notation, **recommandée** |
| **3** | Réutiliser `ReferralCommissionService` + `referral_balance` | Bon pour la **mécanique de commission**, à combiner avec l'option 2 |

**Recommandation : option 2 pour le catalogue + option 3 pour le versement des commissions.**

### B-1. Si option 1 (extension `quick_services`)

- [ ] Migration : `latitude`/`longitude` → **nullable** (actuellement NOT NULL, incompatible avec un programme national)
- [ ] Ajouter `commission` à l'enum `price_type` + colonnes `commission_rate`, `commission_cap`, `commission_basis`
- [ ] Ajouter `is_predefined` / `is_student_program` (bool) pour distinguer du contenu utilisateur
- [ ] Adapter `QuickServiceController::index()` (filtre géo actuellement obligatoire ?)
- [ ] Contourner la règle « seuls les recruteurs publient » pour les services système

### B-2. Si option 2 (table dédiée — recommandé)

- [ ] Migration `student_programs` :
  `slug UNIQUE, title, description, partner ENUM('estuaire_eat','estuaire_achats','estuaire_emploi','merci_e'), type ENUM('business_provider','courier'), commission_rate (decimal), commission_basis ENUM('first_month_revenue','first_transaction','per_transaction','per_course'), commission_cap (nullable), fixed_bonus (nullable), min_active_days (nullable), requires_vehicle (bool nullable), has_rating (bool), icon, color, display_order, is_active`
- [ ] Migration `student_program_enrollments` : `user_id, student_program_id, status, enrolled_at, validated_at, total_earned, referrals_count, rating_avg, rating_count`
- [ ] Modèles + trait `HasTranslations` sur `title`/`description`
- [ ] Système de notation coursier (PDF : « priorisation des mieux notés »)

### B-3. Données des 4 programmes (verbatim PDF)

- [ ] **Estuaire Eat** — 5 % du 1er mois de CA du partenaire recommandé **OU** prime fixe si actif > 30 j
      ⚠️ **Montant de la prime fixe non défini dans le PDF**
- [ ] **Estuaire Achats** — 3 % de la 1re transaction, **plafond à définir**
      ⚠️ **Plafond non défini dans le PDF**
- [ ] **Estuaire Emploi** — 5 % par transaction (étudiant parrainé) + **1000 FCFA** par entreprise inscrite/validée active ≥ 1 mois
- [ ] **Merci-E (coursier)** — 50 % du montant de la course, notation client, véhiculé ou non

### B-4. API

- [ ] `GET /api/student-programs` (public ou auth) — liste des 4 programmes
- [ ] `GET /api/student-programs/{slug}` — détail + conditions de rémunération
- [ ] `POST /api/student-programs/{slug}/enroll` (auth, rôle `student`/`candidate`)
- [ ] `GET /api/student-programs/my-enrollments` — gains, filleuls, note
- [ ] Gating éventuel via `student_mode` (`PremiumServiceConfig`) — **à confirmer : réservé aux étudiants premium ou ouvert à tous ?**
- [ ] Annotations `@OA\*`

### B-5. Commissions

- [ ] Relier au `ReferralCommissionService` existant + `referral_balance`
- [ ] Traçabilité : qui a recommandé quoi, quand, validé ou non
- [ ] Règle « actif après 30 jours » / « actif ≥ 1 mois » → job planifié de validation
- [ ] Plafonnement anti-abus (Estuaire Achats)
- [ ] ⚠️ **Estuaire Eat / Estuaire Achats / Merci-E sont des plateformes externes** — définir comment Estuaire Emploi est notifié d'une inscription/transaction (webhook ? API ? saisie manuelle admin ?) — **bloquant pour l'automatisation des commissions**

### B-6. Seeder, i18n, tests

- [ ] `StudentProgramSeeder` idempotent + enregistrement dans `DatabaseSeeder`
- [ ] `lang/{fr,en,es,ar}/student_program.php`
- [ ] Traductions des 4 titres/descriptions
- [ ] Tests : listing, inscription, double inscription refusée, calcul de commission (dont plafond)

---

# CHECKLIST C — Intégration INSAM-IA

**Objectif :** QCM, ressources IA et module révision, en s'appuyant sur `insam-ia.com`.

### C-0. Prérequis — ⚠️ bloquants à lever avec INSAM-IA

- [ ] **Obtenir un compte de service dédié** (login/password) — la clé API seule ne donne pas accès à `/api/chat`, `/api/revision-cards`, `/api/exams`
- [ ] **Confirmer les endpoints non documentés** `/api/revision-cards`, `/api/revision-cards/generate`, `/api/exams` (hors spec OpenAPI = non contractuels)
- [ ] **Signaler le bug `/api/chat`** (`prompt is too long: 2159239 tokens`)
- [ ] **Obtenir les limites de rate limiting** (aucun en-tête exposé)
- [ ] **Valider le mapping code UE ↔ contenu** (`INF111` → « Histoire de la réforme forestière » ?)
- [ ] **Signaler que les identifiants de démo de la doc sont actifs en production**
- [ ] Confirmer le SLA / temps de réponse de `evaluation-sessions/start` (génération IA)
- [ ] Clarifier « à intégrer avec **Kira** » (PDF) — personne, service ou module ?

### C-1. Socle technique

- [ ] `config/services.php` → bloc `insam_ia` :
  ```php
  'insam_ia' => [
      'base_url' => env('INSAM_IA_BASE_URL', 'https://insam-ia.com'),
      'api_key'  => env('INSAM_IA_API_KEY'),
      'email'    => env('INSAM_IA_EMAIL'),
      'password' => env('INSAM_IA_PASSWORD'),
      'timeout'  => env('INSAM_IA_TIMEOUT', 30),
      'eval_timeout' => env('INSAM_IA_EVAL_TIMEOUT', 180),
  ],
  ```
- [ ] Ajouter ces variables à `.env.example` (⚠️ `INSAMTECHS_API_URL` y manque déjà — à corriger au passage)
- [ ] **`app/Services/InsamIa/InsamIaClient.php`** — calqué sur `app/Services/Gfs/GfsService.php` :
  - `isConfigured()` pour dégradation gracieuse
  - `X-API-Key` sur `/api/external/*`
  - gestion + **cache du token Sanctum** (pattern `FreeMoPayTokenManager`) avec re-login sur 401
  - `Http::timeout()` différencié (30 s standard / 180 s génération IA)
  - logs + échecs non bloquants
- [ ] Cache des réponses lentes/stables (catégories, roadmaps, débouchés) — TTL à définir
- [ ] ⚠️ **Ne jamais committer la clé API** — uniquement via `.env`

### C-2. QCM / Évaluations (PDF point 5 : « questions aléatoires »)

- [ ] `InsamIaEvaluationService` : `activeSessions()`, `start()`, `submit()`
- [ ] **Job asynchrone** pour `start()` (génération IA lente, timeout ≥ 180 s) + notification au résultat
- [ ] Table locale `insam_ia_attempts` : `user_id, session_id, attempt_id, specialite, score, total, percentage, started_at, submitted_at, payload json`
- [ ] Mapper l'identité Estuaire (`nom`/`prenom`/`matricule`/`specialite`) → payload INSAM-IA
- [ ] Gérer le **403 « déjà composé »** (1 tentative par nom+prénom par session)
- [ ] Endpoints :
  - `GET /api/insam-ia/evaluations/active`
  - `POST /api/insam-ia/evaluations/start`
  - `POST /api/insam-ia/evaluations/{attemptId}/submit`
  - `GET /api/insam-ia/evaluations/my-attempts`
- [ ] **Ne jamais exposer `correct_answer` avant soumission**
- [ ] Décider : QCM INSAM-IA **en complément** ou **en remplacement** de `roadmap_questions` existant ?

### C-3. Ressources avec IA

> ⚠️ Le PDF dit « car on a déjà des ressources actu ». **Aucun module actualités/news n'existe dans le repo.**
> **À clarifier avec le client** : s'agit-il de `training_packs` (vidéos), `exam_packs` (épreuves), `roadmaps`, ou d'un module réellement absent ?

- [ ] **Clarifier le périmètre exact de « ressources actu »** — bloquant
- [ ] `GET /api/insam-ia/categories` (proxy `/api/public/categories`, 47 filières)
- [ ] `GET /api/insam-ia/categories/{id}/{roadmap|debouches|certifications|videos}`
- [ ] `POST /api/insam-ia/course-materials` (proxy `/api/external/course-materials` par codes UE)
- [ ] `GET /api/insam-ia/course-materials/{id}`
- [ ] **Packs d'épreuve (Ressources 1)** → proxy `/api/exams` (⚠️ non documenté) ; articuler avec `exam_packs` local
- [ ] **Spécialités sans formation vidéo (Ressources 3)** : IH, GMH, Froid et climatisation, Santé, PV, PA, Mécatronique, HSE
      → identifier ces 8 spécialités côté Estuaire et **router vers le contenu INSAM-IA** en substitut
      ⚠️ Décoder les sigles **IH / GMH / PV / PA** avec le client
      ⚠️ INSAM-IA n'a que **2 vidéos en base** — vérifier la couverture réelle
- [ ] Cache + dégradation gracieuse si INSAM-IA indisponible

### C-4. Module révision (Ressources 5)

- [ ] Proxy `GET /api/revision-cards` (⚠️ non documenté, Bearer requis)
- [ ] Proxy `POST /api/revision-cards/generate` avec `{category_id}` (⚠️ génération IA lente → job async)
- [ ] Table `insam_ia_revision_cards` en cache local (`title`, `content` markdown, `category_id`)
- [ ] Endpoints `GET /api/insam-ia/revision-cards` + `POST /api/insam-ia/revision-cards/generate`
- [ ] Le contenu est en **markdown** → prévoir le rendu mobile
- [ ] Quotas : INSAM-IA limite par plan (`revision_cards_per_month` : 2 gratuit / 50 premium) → **définir la politique Estuaire**

### C-5. Progression de lecture & attestation (Ressources 4)

> Le PDF demande : suivi de progression de lecture + évaluation + **attestation finale selon la note**.
> **Rien de tel n'existe** (pas de table `certificates`/`attestations`).

- [ ] Migration `reading_progress` : `user_id, resource_type, resource_id, progress_percent, last_position, completed_at`
- [ ] Migration `attestations` : `user_id, resource_type/id, score, percentage, issued_at, reference UNIQUE, pdf_path`
- [ ] Définir le **seuil de réussite** (cf. `roadmaps.pass_threshold` = 70 par défaut) — ⚠️ à valider avec le client
- [ ] Génération PDF via **`barryvdh/laravel-dompdf` (déjà installé)** — s'inspirer de `Resume/ResumePdfService`
- [ ] Endpoints : `POST /api/reading-progress`, `GET /api/attestations`, `GET /api/attestations/{id}/download`
- [ ] Numéro de référence + vérifiabilité de l'attestation
- [ ] i18n du modèle d'attestation (4 locales)

### C-6. Chat IA (optionnel — bloqué)

- [ ] ⛔ **Bloqué** : `/api/chat` renvoie une erreur applicative avec HTTP 200
- [ ] Ne pas exposer côté mobile tant que ce n'est pas corrigé par INSAM-IA
- [ ] Si corrigé : proxy `POST /api/insam-ia/chat` + quotas (5/jour gratuit, 100/jour premium côté INSAM-IA)

### C-7. Transverse

- [ ] Annotations `@OA\*` sur tous les nouveaux endpoints
- [ ] `lang/{fr,en,es,ar}/insam_ia.php`
- [ ] Gestion d'erreur uniforme : INSAM-IA indisponible ≠ erreur 500 Estuaire
- [ ] Monitoring/logs des appels sortants (latence, taux d'échec)
- [ ] Tests : client HTTP mocké (`Http::fake()`), flux QCM complet, dégradation si `isConfigured()` faux

---

## 4. Questions ouvertes à trancher avec le client

**Chantier A**
1. Bannière 10 « Réseautage pro » : destination ? (`??????` dans le PDF)
2. Bannière 11 « Web binaire » : mécanisme d'appel vidéo et définition du « gold center » ?
3. Lien du groupe Telegram (bannière 4) — à créer ?
4. Les 13 visuels sont-ils disponibles ?

**Chantier B**

5. Montant de la **prime fixe** Estuaire Eat ?
6. **Plafond** de commission Estuaire Achats ?
7. Comment Estuaire Emploi est-il notifié des transactions sur Estuaire Eat / Achats / Merci-E ? (webhook, API, admin manuel) — **bloquant pour l'automatisation**
8. Programmes réservés aux étudiants `student_mode` premium, ou ouverts à tous ?
9. Modéliser en `quick_services` étendus ou en table dédiée `student_programs` ? *(recommandation : table dédiée)*

**Chantier C**

10. Que désigne exactement « ressources actu » ? (aucun module news dans le repo)
11. Décodage des sigles **IH, GMH, PV, PA** ?
12. Le QCM INSAM-IA complète ou remplace les quiz `roadmaps` existants ?
13. Seuil de note pour délivrer l'attestation ? (défaut suggéré : 70 %)
14. Qui/quoi est « **Kira** » (mentionné 5 fois dans le PDF) ?
15. Compte de service INSAM-IA dédié à obtenir.

---

## 5. Ordre d'exécution recommandé

1. **Chantier A** — autonome, gaps clairs, valeur immédiate *(dépend uniquement des Q1-Q4)*
2. **Chantier C-1 + C-2** — socle client HTTP + QCM (l'API est validée et fonctionnelle)
3. **Chantier B** — après arbitrage architecture (Q7-Q9), car dépend d'intégrations externes
4. **Chantier C-3/C-4/C-5** — après clarification du périmètre « ressources » (Q10)

---

## Annexe — Commandes de vérification de l'API INSAM-IA

```bash
K="insam-rh-secret-2026"; B="https://insam-ia.com"

# Spec OpenAPI
curl -s "$B/api-docs/openapi.json" | python3 -m json.tool

# Public (sans auth)
curl -s "$B/api/public/stats"
curl -s "$B/api/public/categories"
curl -s "$B/api/evaluation-sessions/active"

# Externe (clé API)
curl -s -X POST -H "X-API-Key: $K" -H "Content-Type: application/json" \
  -d '{"codes":["INF111","MAT121"]}' "$B/api/external/course-materials"

# Sanctum (token requis)
TOKEN=$(curl -s -X POST -H "Content-Type: application/json" \
  -d '{"email":"<compte>","password":"<mdp>"}' "$B/api/login" | python3 -c "import sys,json;print(json.load(sys.stdin)['token'])")
curl -s -H "Authorization: Bearer $TOKEN" "$B/api/revision-cards"
curl -s -H "Authorization: Bearer $TOKEN" "$B/api/exams"
```
