# Réorganisation `epreuves_classees` — Rapport final

Date : 2026-05-04 / 2026-05-05

## Convention de nommage adoptée

```
{Spécialité}/{Filière}/{Matière en Title Case}/{Année|Annales}/[Corrigé/]
    Sujet [N] - {Matière} - BTS {Année}.pdf
    Corrigé Sujet [N] - {Matière} - BTS {Année}.pdf
```

- `[N]` est omis si aucun numéro de sujet n'a pu être détecté.
- `BTS {Année}` est omis si le PDF est dans `Annales/`.
- Matière en **Title Case français** (mots-outils `de`, `des`, `du`,
  `et`, `la`, `le`, `les`, `en`, etc. en minuscules).
- Sous-dossier `Corrigé/` pour les corrigés (préservé tel quel).

## Statistiques

| | |
|---|---|
| PDFs scannés au départ | 5 224 |
| PDFs après nettoyage | **4 958** |
| Doublons binaires supprimés (MD5) | **266** |
| Dossiers `sans_annee` → `Annales` | **919** |
| Dossiers matières MAJUSCULES → Title Case | **1 219** |
| Dossiers matières lowercase → Title Case | **161** |
| Fichiers PDF renommés | **4 705** |
| Fichiers déjà conformes | **253** |
| **Conformité finale** | **4 958 / 4 958 (100%)** |
| Erreurs d'exécution | 0 |

## Répartition par spécialité

| Spécialité | Filières | PDFs |
|---|---|---|
| Informatique | HND-SWE, GL, GSI, IIA, Réseaux, ECM, Télécommunications | 3 169 |
| Gestion | GRH, CGE, MSI | 477 |
| Commerce | DOT, MCV, GLT, ACT | 337 |
| Électrotechnique | ET | 241 |
| Finance | BF, ASS | 200 |
| Tronc Commun | TC | 173 |
| Santé | SI, SF | 96 |
| Bâtiment | BA | 86 |
| Industrie | GMH, AMA | 80 |
| Droit | DA | 75 |
| Géomètre Topographe | GT | 16 |
| Énergie | ENR, CFP | 4 |

## Cas particuliers et anomalies relevées

### 1. Dossiers à nom pathologique (5)
Ces dossiers commencent par un caractère diacritique combinant
(U+0301) ou un underscore — héritage probable de copies/exports
buggés. À examiner manuellement :

- `Commerce/GLT/́ 1 _bts_glt_ci_epreuve_tqg`
- `Commerce/GLT/́ 2_bts_glt_ci_epreuve_tqg`
- `Gestion/GRH/_grh_gp_mcv_epreuve_tqg`
- `Informatique/ECM/_informatique Générale2022`
- `Informatique/GSI/́s Gsi1`

### 2. Années suspectes (probablement des typos de la source)
- `Tronc Commun/TC/Economie Generale/2028`
- `Électrotechnique/ET/Education Civique et Ethique/2029`
- `Électrotechnique/ET/Economie Generale/2028`
- `Électrotechnique/ET/Droit de L'audiovisuel, Ethique et Deontologie Professionnelle/2029`
- `Électrotechnique/ET/Economie Generale, Creation et Organisation de L'entreprise/2028`
- `Droit/DA/Telecom/2027`, `Droit/DA/Telecommunications/2027`
- `Bâtiment/BA/Epreuve Professionnelle de Synthese/2029`, `Bâtiment/BA/Eps/2029`

→ Probable confusion avec 2018/2023 ou 2017/2022. À reclasser
  manuellement quand la vraie année est connue.

### 3. Matières doublonnées sémantiquement (à fusionner manuellement)
La normalisation Title Case ne pouvait pas fusionner des variantes :

- `Eps` ↔ `Epreuve Professionnelle de Synthese` (multiple filières)
- `Tef` ↔ `Techniques D'expression Française`
- `Tea` ↔ `Techniques D'expression Anglaise`
- `Algo et Structure de Données` ↔ `Algorithme et Structure de Donnees`
  ↔ `Algorithmique et Structures de Donnees 2019`
- `Comptabilite` ↔ `Comptabilites` ↔ `Comptabilite Generale`
- `Reseau` ↔ `Reseaux` ↔ `Réseaux`
- Variantes avec/sans accents : `Mathematiques` vs `Mathématiques`
- Variantes d'apostrophes typographiques : `D'expression` (U+0027)
  vs `D’expression` (U+2019)

→ Une passe de fusion manuelle (ou un script sémantique avec
  validation utilisateur) serait pertinente.

### 4. Dossiers suspects (matière = nom de fichier)
Quelques dossiers ont été créés à partir de noms de fichiers,
ex. `Commerce/DOT/Dot Sujet 1 Eps Moune (2)/`. Ils devraient être
fusionnés avec leur vraie matière. ~75 cas listés dans
`/sessions/confident-ecstatic-gauss/manifest_report.md`.

### 5. Matières SANS aucun corrigé
**751** couples (matière, année) ne contiennent aucun corrigé. C'est
le périmètre logique pour la **Phase 2** (rédaction de corrigés
par lots). Liste détaillée dans `apply_log.json`.

## Fichiers de log produits

- `manifest.json` — plan dry-run complet (2 120 KB)
- `manifest_report.md` — rapport markdown du plan
- `apply_log.json` — journal de la phase 1 (deletes + Annales)
- `apply_titlecase_log.json` — journal MAJ → Title Case
- `apply_titlecase_lc_log.json` — journal lowercase → Title Case
- `replan_files_log.json` — journal final des renommages de fichiers

(Les logs sont dans `/sessions/confident-ecstatic-gauss/`.)

## Pilote validé

Pilote initial sur `Informatique/GL/Base de Données/2025/` :

- `Sujet 1 - Base de Données - BTS 2025.pdf`
- `Sujet 2 - Base de Données - BTS 2025.pdf`
- `Corrigé/Corrigé Sujet 1 - Base de Données - BTS 2025.pdf`
  (refait avec corrections : type DATE, RENAME COLUMN, table
  Rattacher complète, FOREIGN KEY explicites)
- `Corrigé/Corrigé Sujet 2 - Base de Données - BTS 2025.pdf`

## Prochaines étapes recommandées

1. **Lancer le seeder Laravel** sur l'arbre nettoyé pour vérifier
   que `ExamPaperBulkSeeder` parse correctement la nouvelle structure.
2. **Fusionner manuellement** les variantes de matières (cf. §3).
3. **Phase 2** : rédaction des corrigés manquants par lots
   (~751 couples matière/année). Stratégie suggérée :
   - Priorité 1 : matières du tronc commun (Maths, Anglais, EPS)
   - Priorité 2 : matières spécialisées par filière populaire (HND-SWE, GRH)
   - Génération PDF via ReportLab (modèle `build_corriges_bdd_2025.py`)
