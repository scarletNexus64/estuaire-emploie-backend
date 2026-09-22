# Import de l'annuaire des entreprises (Douala, Yaoundé, Bafoussam)

Import de **314 entreprises réelles** géolocalisées, avec leur compte
propriétaire, depuis `entreprises_douala_yaounde_bafoussam.xlsx`.

| | |
|---|---|
| **Seeder** | `EntreprisesCamerounSeeder` |
| **Fichier** | `database/seeders/EntreprisesCamerounSeeder.php` |
| **Tests** | `tests/Feature/EntreprisesCamerounSeederTest.php` (8 tests, 2922 assertions) |
| **Source** | `entreprises_douala_yaounde_bafoussam.xlsx` (racine du dépôt) |

---

## Lancer l'import

```bash
php artisan db:seed --class=EntreprisesCamerounSeeder --force
```

Le `--force` est requis en production (sans lui, Laravel demande une
confirmation interactive).

Le seeder est aussi enregistré dans `DatabaseSeeder` (après `RecruiterSeeder`),
il part donc avec un `php artisan db:seed` global.

### Sortie attendue

```
[EntreprisesCamerounSeeder] 314 entreprises créées (314 géolocalisées), 314 comptes recruteurs, 0 ignorées (déjà présentes).
```

Relancé une seconde fois (idempotent, aucun doublon créé) :

```
[EntreprisesCamerounSeeder] 0 entreprises créées (0 géolocalisées), 0 comptes recruteurs, 314 ignorées (déjà présentes).
```

---

## ⚠️ À vérifier avant de lancer sur le serveur

### 1. Le fichier Excel doit être présent à la racine

Le seeder lit `entreprises_douala_yaounde_bafoussam.xlsx` via `base_path()`.
Ce fichier **n'est pas versionné par défaut** : un `git pull` ne le déposera pas
sur le serveur.

```bash
ls -l entreprises_douala_yaounde_bafoussam.xlsx
```

S'il est absent, le seeder **ne plante pas** — il s'arrête proprement sans rien
importer :

```
[EntreprisesCamerounSeeder] fichier introuvable (...) : import annulé.
```

Pour le déposer :

```bash
scp entreprises_douala_yaounde_bafoussam.xlsx user@serveur:/chemin/du/projet/
```

### 2. Sauvegarder la base

L'import touche une base de production avec de vrais utilisateurs :

```bash
mysqldump -u root -p estuaire_emploie \
  companies users recruiters company_company_category \
  > backup_avant_import.sql
```

---

## Ce que l'import crée

Pour **chacune des 314 entreprises** :

1. une ligne `companies` — géolocalisée, statut `verified` ;
2. un compte propriétaire `users` — role `recruiter`, mot de passe aléatoire,
   `must_change_password = true` ;
3. une ligne `recruiters` qui rattache ce compte à l'entreprise (droits complets) ;
4. le rattachement à sa catégorie via `company_company_category`.

### Volumétrie

| Table | Delta |
|---|---|
| `companies` | +314 |
| `users` | +314 |
| `recruiters` | +314 |
| `company_company_category` | +314 |

### Répartition

| Ville | Entreprises |
|---|---|
| Douala | 127 |
| Yaoundé | 99 |
| Bafoussam | 88 |

20 secteurs couverts (Restauration, Hôtellerie, Banque & Finance, Pharmacie,
Santé, Éducation, Automobile, Immobilier, Télécoms, BTP, Assurance…), mappés sur
les `level_1` existants de `company_categories`.

### Couverture des données

| Donnée | Couverture |
|---|---|
| Coordonnées GPS (lat/lng) | **314 / 314 — 100 %** |
| Google `place_id` | **314 / 314 — 100 %** |
| Téléphone | 284 / 314 |
| Note Google | 304 / 314 |
| Coordonnées hors Cameroun | 0 |
| Emails en doublon | 0 |

---

## Où sont les coordonnées GPS dans le fichier source

C'est la particularité de ce fichier, et la raison d'être du code
d'extraction dans le seeder.

La colonne **F** (« Lien de géolocalisation ») n'affiche que le libellé
`Voir sur Google Maps`. Les coordonnées réelles ne sont **pas dans la cellule** :
elles sont portées par le lien hypertexte, stocké à part dans l'archive `.xlsx`,
au sein de `xl/worksheets/_rels/sheet1.xml.rels` :

```
https://www.google.com/maps/search/?api=1
    &query=4.027792,9.701338
    &query_place_id=ChIJ0V42iiYTYRARovGHr7OX4jY
```

PhpSpreadsheet en mode `setReadDataOnly(true)` ne lit pas ces relations — d'où
l'impression, à l'ouverture du fichier, que la donnée est absente. Le seeder
rouvre donc l'archive avec `ZipArchive` pour associer chaque cellule (`F2`,
`F3`, …) à son URL, puis en extraire `lat`, `lng` et le `place_id`.

Un garde-fou rejette toute coordonnée hors des bornes du Cameroun
(lat 1.6–13.1, lng 8.4–16.2) plutôt que de géolocaliser une entreprise à tort.

### Compatibilité avec l'existant

Les colonnes `latitude` / `longitude` existent déjà sur `companies`
(migration `2026_02_02_220552`), avec leur index de proximité. **Aucune
migration n'est nécessaire.** Les données sont directement exploitables par
`Company::nearby()` et `Company::distanceTo()` (Haversine) :

```php
// Entreprises à moins de 5 km d'Akwa (centre-ville de Douala)
Company::nearby(4.0511, 9.7679, 5)->get();
```

---

## Adresses e-mail

Le fichier source ne contient **aucun e-mail**, or `companies.email` est
`UNIQUE NOT NULL`. Les adresses sont donc dérivées du nom commercial, sur un
domaine `.cm` :

```
The Yard                     →  contact@theyard.cm
One Rooftop                  →  contact@onerooftop.cm
Piccola Venezia Restaurant   →  contact@piccolaveneziarestaurant.cm
```

Le seeder retire les mentions juridiques (`SARL`, `SA`, `ETS`, `GROUPE`,
`CAMEROUN`…) et la partie « agence » (`SGBC – Agence Hippodrome` → `sgbc.cm`).
En cas de collision entre enseignes multi-agences, il désambiguïse par la ville
puis, si besoin, par un compteur. Résultat : **314 e-mails uniques et valides**,
sans collision avec les entreprises déjà en base.

> ### ⚠️ Point de vigilance
>
> Ces domaines `.cm` sont **reconstitués**, pas vérifiés : certains peuvent
> réellement exister et appartenir à des tiers. **Avant d'activer tout envoi
> d'e-mail transactionnel**, il faut exclure ces comptes, sinon un message
> partirait vers un destinataire réel non concerné.
>
> Ils sont identifiables par `role = 'recruiter'` + `must_change_password = 1`,
> ou par leur date de création. Une alternative plus sûre existe si besoin :
> un domaine `.invalid` (réservé par la RFC 2606, techniquement non-livrable).

---

## Garanties

- **Strictement additif** — aucun `truncate`, aucun `delete`. Les entreprises
  et utilisateurs déjà en base ne sont ni modifiés ni supprimés (un test dédié
  le vérifie).
- **Idempotent** — la clé d'unicité est l'e-mail dérivé du nom. Une seconde
  exécution ne crée rien. `withTrashed()` évite de buter sur l'index unique si
  une ligne a été soft-deleted.
- **Transactionnel** — chaque entreprise est créée dans sa propre transaction
  (`DB::transaction`), avec son compte et son rattachement de catégorie.

---

## Vérification après import

```bash
php artisan tinker --execute='
echo "entreprises = ".App\Models\Company::count()."\n";
echo "avec GPS    = ".App\Models\Company::whereNotNull("latitude")->count()."\n";
echo "recruteurs  = ".App\Models\Recruiter::count()."\n";
'
```

Ce qui compte n'est pas la valeur absolue (elle dépend de l'état de la base
cible) mais le **delta de +314** et le `314 géolocalisées` affiché par le seeder.

### Tests

```bash
php artisan test tests/Feature/EntreprisesCamerounSeederTest.php
```

Les 8 tests couvrent : l'extraction GPS et ses bornes géographiques, la
répartition par ville, la création des comptes propriétaires, l'unicité des
e-mails, l'idempotence, la préservation des données existantes, le rattachement
aux catégories et la recherche par proximité.

> **Note** — `php artisan test` (suite complète) remonte 13 échecs dans
> `InsamIaTest`. Ils sont **pré-existants et sans rapport avec cet import** :
> vérifié en remisant les changements (`git stash`), les 13 mêmes échecs
> apparaissent sur la base de code d'origine.

---

## En cas de rollback

Les entreprises importées sont identifiables par leur date de création ou par
le fait qu'elles cumulent un GPS et l'une des trois villes. Restauration depuis
la sauvegarde :

```bash
mysql -u root -p estuaire_emploie < backup_avant_import.sql
```
