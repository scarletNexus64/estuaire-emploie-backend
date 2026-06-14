<?php

namespace Database\Seeders\Roadmaps;

use Illuminate\Database\Seeder;

/**
 * Roadmap Data Analyst — de la donnée brute à la décision éclairée.
 */
class DataAnalystRoadmapSeeder extends Seeder
{
    use RoadmapSeederHelper;

    public function run(): void
    {
        $this->createRoadmap([
            'title' => 'Deviens Data Analyst',
            'slug' => 'devenir-data-analyst',
            'domain' => 'data',
            'description' => "Apprends à transformer des données brutes en décisions concrètes. De la compréhension du métier au storytelling, en passant par le nettoyage, les statistiques, Excel, le SQL et la visualisation : un parcours complet pour démarrer une carrière de Data Analyst en Afrique francophone.",
            'objectives' => "Comprendre le rôle et le quotidien d'un data analyst\nReconnaître les types de données et leur structure\nNettoyer et fiabiliser un jeu de données\nMaîtriser les statistiques descriptives essentielles\nAnalyser avec Excel et SQL\nConstruire des visualisations et dashboards clairs\nRaconter une histoire avec les données et éviter les biais",
            'icon' => '📊',
            'color' => '#2563EB',
            'difficulty' => 'beginner',
            'required_packs' => [],
            'pass_threshold' => 70,
            'order' => 20,
            'levels' => [
                [
                    'title' => 'Niveau 1 — Le métier de Data Analyst',
                    'subtitle' => 'Comprendre le rôle et la valeur métier',
                    'xp_reward' => 100,
                    'content' => "🎯 **Objectif** : comprendre ce que fait réellement un data analyst.\n\n💡 Le data analyst transforme des **données brutes** en **informations utiles** pour décider. Il ne se contente pas de chiffres : il répond à des questions métier.\n\nExemples concrets au Cameroun :\n• Un opérateur mobile money à Douala veut savoir pourquoi les transactions chutent le lundi.\n• Une PME de Yaoundé veut identifier ses produits les plus rentables.\n\n✅ Les étapes typiques d'une analyse :\n• Comprendre la question (ex : « Quel canal vend le mieux ? »)\n• Collecter et nettoyer les données\n• Analyser (statistiques, croisements)\n• Visualiser et communiquer le résultat\n\n⚠️ Différence importante :\n• **Data analyst** : explique le passé et le présent.\n• **Data scientist** : prédit le futur (modèles avancés).\n• **Data engineer** : construit les tuyaux de données.\n\nUn bon analyste est autant curieux du **business** que des outils. Savoir poser la bonne question vaut souvent plus que connaître un outil compliqué.",
                    'questions' => [
                        ['question' => 'Quelle est la mission principale d\'un data analyst ?', 'options' => ['Construire des serveurs de bases de données', 'Transformer des données en informations utiles à la décision', 'Réparer le matériel informatique', 'Vendre des logiciels'], 'correct' => [1], 'explanation' => 'Le data analyst donne du sens aux données pour aider à décider.'],
                        ['question' => 'Par quoi commence idéalement une analyse de données ?', 'options' => ['Par choisir un beau graphique', 'Par comprendre la question métier à résoudre', 'Par acheter un logiciel coûteux', 'Par supprimer les données'], 'correct' => [1], 'explanation' => 'Tout part de la question métier : elle guide toute la démarche.'],
                        ['question' => 'Qui se concentre surtout sur la prédiction du futur ?', 'options' => ['Le data analyst', 'Le data scientist', 'Le comptable', 'Le data engineer'], 'correct' => [1], 'explanation' => 'Le data scientist construit des modèles prédictifs, là où l\'analyste explique le passé et le présent.'],
                    ],
                ],
                [
                    'title' => 'Niveau 2 — Les types de données',
                    'subtitle' => 'Quantitatif, qualitatif et structure',
                    'xp_reward' => 110,
                    'content' => "🎯 **Objectif** : reconnaître les types de données pour choisir la bonne analyse.\n\n💡 Deux grandes familles :\n• **Quantitatives** (chiffres mesurables) : montant d'une vente, âge, nombre de clients.\n• **Qualitatives** (catégories) : ville, sexe, type de produit, statut.\n\nLes quantitatives se divisent en :\n• **Discrètes** : nombres entiers (nombre d'enfants).\n• **Continues** : valeurs avec décimales (poids, montant en FCFA).\n\n✅ Données **structurées** vs **non structurées** :\n• Structurées : tableaux, colonnes, lignes (Excel, SQL).\n• Non structurées : textes libres, images, messages WhatsApp.\n\nExemple de tableau structuré :\n\n| Client | Ville | Montant (FCFA) | Canal |\n|--------|--------|----------------|-------|\n| Amina | Douala | 15000 | Mobile money |\n| Junior | Yaoundé | 8000 | Espèces |\n\n⚠️ Le type de donnée détermine ce qu'on peut calculer : on fait une **moyenne** sur un montant, jamais sur une ville. Confondre les types mène à des analyses fausses.",
                    'questions' => [
                        ['question' => 'La colonne « Ville » (Douala, Yaoundé...) est une donnée :', 'options' => ['Quantitative continue', 'Qualitative', 'Quantitative discrète', 'Numérique'], 'correct' => [1], 'explanation' => 'Une ville est une catégorie, donc une donnée qualitative.'],
                        ['question' => 'Lesquelles de ces données sont quantitatives ? (plusieurs réponses)', 'options' => ['Le montant d\'une vente en FCFA', 'Le nom du produit', 'Le nombre de clients', 'La couleur préférée'], 'correct' => [0, 2], 'explanation' => 'Le montant et le nombre de clients sont mesurables, donc quantitatifs.'],
                        ['question' => 'Un fichier Excel avec colonnes et lignes est un exemple de données :', 'options' => ['Non structurées', 'Structurées', 'Cryptées', 'Continues'], 'correct' => [1], 'explanation' => 'Les données organisées en tableau lignes/colonnes sont structurées.'],
                    ],
                ],
                [
                    'title' => 'Niveau 3 — Le nettoyage des données',
                    'subtitle' => 'Fiabiliser avant d\'analyser',
                    'xp_reward' => 120,
                    'content' => "🎯 **Objectif** : savoir nettoyer un jeu de données avant toute analyse.\n\n💡 On dit souvent que 80 % du travail d'un analyste, c'est le nettoyage. Des données sales mènent à des conclusions fausses.\n\n✅ Les problèmes les plus fréquents :\n• **Doublons** : un même client compté deux fois.\n• **Valeurs manquantes** : une cellule vide pour le montant.\n• **Incohérences de format** : « Douala », « douala », « DLA ».\n• **Valeurs aberrantes** : un âge de 250 ans.\n• **Erreurs de saisie** : montant en FCFA écrit comme texte.\n\nExemple : avant de calculer le chiffre d'affaires par ville, il faut uniformiser « Yde », « Yaoundé », « yaounde » en une seule valeur.\n\n⚠️ Pour les valeurs manquantes, plusieurs choix :\n• Supprimer la ligne (si peu de cas).\n• Remplacer par une moyenne ou une médiane.\n• Marquer comme « Inconnu ».\n\nLe nettoyage doit être **documenté** : on note ce qu'on a corrigé, pour rester transparent et reproductible. Une analyse propre commence toujours par une donnée propre.",
                    'questions' => [
                        ['question' => 'Pourquoi nettoyer les données avant d\'analyser ?', 'options' => ['Pour gagner du temps de stockage', 'Pour éviter des conclusions fausses', 'Parce que c\'est obligatoire par la loi', 'Pour rendre les fichiers plus colorés'], 'correct' => [1], 'explanation' => 'Des données sales produisent des analyses et décisions erronées.'],
                        ['question' => 'Les valeurs « Douala », « douala » et « DLA » illustrent quel problème ?', 'options' => ['Des doublons exacts', 'Une incohérence de format', 'Une valeur manquante', 'Une valeur aberrante'], 'correct' => [1], 'explanation' => 'Ce sont des écritures différentes d\'une même ville : une incohérence de format à uniformiser.'],
                        ['question' => 'Quelles sont des façons valables de traiter des valeurs manquantes ? (plusieurs réponses)', 'options' => ['Supprimer la ligne si les cas sont rares', 'Remplacer par la moyenne ou la médiane', 'Inventer des valeurs au hasard', 'Marquer comme « Inconnu »'], 'correct' => [0, 1, 3], 'explanation' => 'Supprimer, imputer par une statistique ou marquer « Inconnu » sont des approches valides ; inventer au hasard fausse l\'analyse.'],
                    ],
                ],
                [
                    'title' => 'Niveau 4 — Statistiques descriptives',
                    'subtitle' => 'Résumer les données en quelques chiffres',
                    'xp_reward' => 130,
                    'content' => "🎯 **Objectif** : résumer un jeu de données avec les bonnes statistiques.\n\n💡 Les **mesures de tendance centrale** :\n• **Moyenne** : somme divisée par le nombre de valeurs.\n• **Médiane** : la valeur du milieu une fois les données triées.\n• **Mode** : la valeur la plus fréquente.\n\nExemple : ventes (en milliers FCFA) = 5, 6, 6, 7, 100.\n• Moyenne = 24,8 (tirée vers le haut par le 100).\n• Médiane = 6 (plus représentative ici).\n• Mode = 6.\n\n⚠️ La moyenne est **sensible aux valeurs extrêmes**. Quand il y a des valeurs aberrantes, la médiane décrit mieux la réalité.\n\n✅ Les **mesures de dispersion** indiquent à quel point les données varient :\n• **Étendue** = max − min.\n• **Écart-type** : dispersion moyenne autour de la moyenne. Un écart-type faible = données regroupées ; élevé = données très dispersées.\n\nEnsemble, tendance centrale et dispersion donnent une photo complète : où se situe le « centre » et à quel point les valeurs s'en éloignent. Ne jamais résumer par la seule moyenne.",
                    'questions' => [
                        ['question' => 'Pour les valeurs 5, 6, 6, 7, 100, quelle mesure est la plus représentative ?', 'options' => ['La moyenne (24,8)', 'La médiane (6)', 'Le maximum (100)', 'L\'étendue (95)'], 'correct' => [1], 'explanation' => 'La médiane résiste à la valeur extrême 100, contrairement à la moyenne.'],
                        ['question' => 'Que mesure l\'écart-type ?', 'options' => ['La valeur la plus fréquente', 'La dispersion des valeurs autour de la moyenne', 'La somme totale', 'La valeur du milieu'], 'correct' => [1], 'explanation' => 'L\'écart-type quantifie à quel point les données s\'écartent de la moyenne.'],
                        ['question' => 'Le mode d\'un jeu de données correspond à :', 'options' => ['La valeur la plus fréquente', 'La moyenne arrondie', 'La plus grande valeur', 'La différence max - min'], 'correct' => [0], 'explanation' => 'Le mode est la valeur qui apparaît le plus souvent.'],
                    ],
                ],
                [
                    'title' => 'Niveau 5 — Analyser avec Excel / tableur',
                    'subtitle' => 'L\'outil de base de tout analyste',
                    'xp_reward' => 140,
                    'content' => "🎯 **Objectif** : exploiter un tableur (Excel, Google Sheets) pour analyser.\n\n💡 Le tableur est l'outil le plus accessible et le plus utilisé en entreprise. Maîtriser quelques fonctions suffit pour aller loin.\n\n✅ Fonctions essentielles :\n• `SOMME` (SUM) : total d'une plage.\n• `MOYENNE` (AVERAGE) : moyenne.\n• `NB.SI` (COUNTIF) : compter selon une condition.\n• `SOMME.SI` (SUMIF) : sommer selon une condition.\n• `RECHERCHEV` (VLOOKUP) : retrouver une valeur dans une autre table.\n\nExemple : total des ventes de Douala uniquement.\n```\n=SOMME.SI(B2:B100;\"Douala\";C2:C100)\n```\nIci la colonne B contient la ville et C le montant.\n\n💡 Les **tableaux croisés dynamiques** (TCD / Pivot Table) sont l'arme ultime : en quelques clics, ils résument des milliers de lignes par ville, par canal ou par mois.\n\n⚠️ Bonnes pratiques :\n• Une donnée par cellule, des en-têtes clairs.\n• Pas de cellules fusionnées dans les données.\n• Vérifier les totaux avant de conclure.\n\nUn tableur bien organisé est déjà une mini base de données.",
                    'questions' => [
                        ['question' => 'Quelle fonction permet de sommer uniquement les montants de Douala ?', 'options' => ['MOYENNE', 'SOMME.SI (SUMIF)', 'NB (COUNT)', 'MAX'], 'correct' => [1], 'explanation' => 'SOMME.SI additionne les valeurs qui respectent une condition, ici « Douala ».'],
                        ['question' => 'À quoi sert un tableau croisé dynamique ?', 'options' => ['À résumer rapidement de grandes données par catégories', 'À envoyer des emails', 'À crypter les données', 'À supprimer les doublons automatiquement'], 'correct' => [0], 'explanation' => 'Le TCD agrège et résume des milliers de lignes par dimensions en quelques clics.'],
                        ['question' => 'Quelles sont de bonnes pratiques dans un tableur ? (plusieurs réponses)', 'options' => ['Une donnée par cellule', 'Fusionner beaucoup de cellules dans les données', 'Utiliser des en-têtes de colonnes clairs', 'Vérifier les totaux avant de conclure'], 'correct' => [0, 2, 3], 'explanation' => 'Cellules atomiques, en-têtes clairs et vérification sont recommandés ; les fusions cassent les analyses.'],
                    ],
                ],
                [
                    'title' => 'Niveau 6 — SQL pour l\'analyse',
                    'subtitle' => 'Interroger des bases de données',
                    'xp_reward' => 150,
                    'content' => "🎯 **Objectif** : extraire des données avec le langage SQL.\n\n💡 SQL sert à interroger des bases de données. C'est la compétence la plus demandée pour un data analyst.\n\n✅ La requête de base :\n```sql\nSELECT ville, montant\nFROM ventes\nWHERE montant > 10000\nORDER BY montant DESC;\n```\n• `SELECT` : quelles colonnes afficher.\n• `FROM` : quelle table.\n• `WHERE` : filtre les lignes.\n• `ORDER BY` : trie le résultat.\n\n💡 L'**agrégation** résume les données :\n```sql\nSELECT ville, SUM(montant) AS total\nFROM ventes\nGROUP BY ville\nORDER BY total DESC;\n```\nCette requête donne le chiffre d'affaires total par ville. `GROUP BY` regroupe, et `SUM` additionne dans chaque groupe.\n\nFonctions d'agrégation utiles : `COUNT`, `SUM`, `AVG`, `MIN`, `MAX`.\n\n⚠️ Ordre logique d'exécution : FROM → WHERE → GROUP BY → SELECT → ORDER BY. Pour filtrer **après** une agrégation, on utilise `HAVING`, pas `WHERE`.\n\nMaîtriser SQL, c'est pouvoir répondre à n'importe quelle question chiffrée sans dépendre de quelqu'un d'autre.",
                    'questions' => [
                        ['question' => 'Quelle clause filtre les lignes avant agrégation ?', 'options' => ['ORDER BY', 'WHERE', 'GROUP BY', 'SELECT'], 'correct' => [1], 'explanation' => 'WHERE filtre les lignes individuelles avant tout regroupement.'],
                        ['question' => 'Pour obtenir le total des ventes par ville, on utilise :', 'options' => ['SUM(montant) avec GROUP BY ville', 'COUNT(*) sans GROUP BY', 'ORDER BY ville uniquement', 'WHERE ville'], 'correct' => [0], 'explanation' => 'GROUP BY ville regroupe par ville et SUM additionne les montants de chaque groupe.'],
                        ['question' => 'Pour filtrer un résultat APRÈS une agrégation (ex : total > 50000), on utilise :', 'options' => ['WHERE', 'HAVING', 'ORDER BY', 'SELECT'], 'correct' => [1], 'explanation' => 'HAVING filtre les groupes après l\'agrégation, contrairement à WHERE qui agit avant.'],
                    ],
                ],
                [
                    'title' => 'Niveau 7 — La visualisation des données',
                    'subtitle' => 'Choisir le bon graphique',
                    'xp_reward' => 160,
                    'content' => "🎯 **Objectif** : choisir le graphique adapté au message.\n\n💡 Un bon graphique fait comprendre en 3 secondes. Le mauvais graphique trompe ou ennuie.\n\n✅ Quel graphique pour quel besoin :\n• **Comparer des catégories** → diagramme en **barres** (ventes par ville).\n• **Évolution dans le temps** → **courbe** (CA par mois).\n• **Proportion d'un tout** → **camembert** (part de chaque canal), à éviter si plus de 5 catégories.\n• **Relation entre 2 variables** → **nuage de points** (budget pub vs ventes).\n\n⚠️ Erreurs fréquentes :\n• Axe vertical qui ne commence pas à zéro → exagère les écarts.\n• Trop de couleurs ou d'effets 3D inutiles.\n• Camembert avec 12 parts illisibles.\n\nExemple : pour montrer l'évolution mensuelle des transactions mobile money, une **courbe** est bien plus parlante qu'un camembert.\n\n💡 Principe clé : **moins, c'est plus**. On supprime tout ce qui ne sert pas le message (le « data-ink »). Un titre clair, des étiquettes lisibles, et une seule idée par graphique. La visualisation est au service de la compréhension, pas de la décoration.",
                    'questions' => [
                        ['question' => 'Quel graphique pour montrer l\'évolution des ventes mois par mois ?', 'options' => ['Camembert', 'Courbe (graphique en ligne)', 'Nuage de points', 'Tableau brut'], 'correct' => [1], 'explanation' => 'La courbe est idéale pour visualiser une évolution dans le temps.'],
                        ['question' => 'Pourquoi un axe vertical qui ne commence pas à zéro est-il problématique ?', 'options' => ['Il rend le graphique plus joli', 'Il exagère visuellement les écarts et peut tromper', 'Il accélère le calcul', 'Il est interdit par la loi'], 'correct' => [1], 'explanation' => 'Tronquer l\'axe amplifie artificiellement les différences et induit en erreur.'],
                        ['question' => 'Quelles affirmations sur la visualisation sont correctes ? (plusieurs réponses)', 'options' => ['Un camembert reste lisible avec 12 catégories', 'Les barres conviennent pour comparer des catégories', 'Supprimer le superflu améliore la lecture', 'Une idée par graphique est préférable'], 'correct' => [1, 2, 3], 'explanation' => 'Les barres comparent bien, l\'épure et le focus aident ; un camembert à 12 parts devient illisible.'],
                    ],
                ],
                [
                    'title' => 'Niveau 8 — KPI et tableaux de bord',
                    'subtitle' => 'Mesurer ce qui compte vraiment',
                    'xp_reward' => 170,
                    'content' => "🎯 **Objectif** : définir des KPI utiles et construire un dashboard.\n\n💡 Un **KPI** (Key Performance Indicator) est un indicateur clé qui mesure l'atteinte d'un objectif. Tous les chiffres ne sont pas des KPI.\n\n✅ Un bon KPI est **SMART** : Spécifique, Mesurable, Atteignable, Pertinent, Temporel.\n\nExemples pour une boutique en ligne au Cameroun :\n• Chiffre d'affaires mensuel (FCFA).\n• Taux de conversion = commandes / visiteurs.\n• Panier moyen = CA / nombre de commandes.\n• Taux d'abandon de panier.\n\n⚠️ Piège : les **vanity metrics** (mesures de vanité) comme le nombre de « vues » flattent mais ne guident aucune décision. Privilégie les **actionable metrics** (mesures sur lesquelles on peut agir).\n\n💡 Un **dashboard** (tableau de bord) regroupe les KPI essentiels sur un seul écran :\n• Les chiffres les plus importants en haut.\n• Des comparaisons (vs mois dernier, vs objectif).\n• Pas plus de 5 à 7 indicateurs clés.\n\nUn bon tableau de bord répond instantanément à : « Est-ce que ça va bien ou mal, et pourquoi ? » Il guide l'action, il n'accumule pas les chiffres.",
                    'questions' => [
                        ['question' => 'Qu\'est-ce qu\'un KPI ?', 'options' => ['Un type de base de données', 'Un indicateur clé qui mesure l\'atteinte d\'un objectif', 'Un logiciel de visualisation', 'Une erreur de saisie'], 'correct' => [1], 'explanation' => 'Un KPI est un indicateur clé de performance lié à un objectif.'],
                        ['question' => 'Le « nombre de vues » sans lien avec une décision est un exemple de :', 'options' => ['Actionable metric', 'Vanity metric (mesure de vanité)', 'KPI SMART', 'Médiane'], 'correct' => [1], 'explanation' => 'Une vanity metric flatte mais ne guide aucune action concrète.'],
                        ['question' => 'Quelles caractéristiques décrivent un bon tableau de bord ? (plusieurs réponses)', 'options' => ['Afficher 50 indicateurs pour être complet', 'Mettre les chiffres les plus importants en évidence', 'Inclure des comparaisons (vs objectif, vs mois dernier)', 'Aider à répondre « ça va bien ou mal, et pourquoi »'], 'correct' => [1, 2, 3], 'explanation' => 'Un bon dashboard est focalisé et actionnable ; trop d\'indicateurs noient l\'information.'],
                    ],
                ],
                [
                    'title' => 'Niveau 9 — Le storytelling avec les données',
                    'subtitle' => 'Convaincre et faire agir',
                    'xp_reward' => 185,
                    'content' => "🎯 **Objectif** : raconter une histoire claire à partir des données.\n\n💡 Une analyse n'a de valeur que si elle est **comprise et utilisée**. Le data storytelling combine trois éléments : les **données**, le **visuel** et le **récit**.\n\n✅ La structure efficace :\n• **Contexte** : la situation et la question (« Les ventes baissent à Douala »).\n• **Constat** : ce que disent les données (« −30 % depuis mars, surtout sur mobile money »).\n• **Cause** : pourquoi (« coupures réseau récurrentes »).\n• **Recommandation** : que faire (« proposer un canal de secours »).\n\n⚠️ Adapte ton message à ton **public** :\n• Un directeur veut la conclusion d'abord, en une phrase.\n• Une équipe technique veut le détail de la méthode.\n\n💡 Bonnes pratiques :\n• Commence par le message clé, pas par la méthode.\n• Une seule idée par diapositive ou graphique.\n• Utilise des chiffres ronds et des comparaisons parlantes (« 3 clients sur 10 »).\n• Termine toujours par une **action recommandée**.\n\nUn analyste qui sait raconter ses données a dix fois plus d'impact qu'un analyste qui se contente d'afficher des tableaux. La donnée informe, l'histoire fait agir.",
                    'questions' => [
                        ['question' => 'Quels sont les trois piliers du data storytelling ?', 'options' => ['Données, visuel et récit', 'Excel, SQL et Python', 'Doublons, moyenne et médiane', 'Serveur, réseau et stockage'], 'correct' => [0], 'explanation' => 'Le storytelling combine les données, leur visualisation et un récit clair.'],
                        ['question' => 'Face à un directeur pressé, on commence par :', 'options' => ['Le détail de la méthode de nettoyage', 'Le message clé et la recommandation', 'La liste des requêtes SQL', 'L\'historique du fichier'], 'correct' => [1], 'explanation' => 'Un décideur veut d\'abord la conclusion et l\'action ; le détail vient ensuite si besoin.'],
                        ['question' => 'Par quoi devrait idéalement se terminer une présentation data ?', 'options' => ['Une longue annexe technique', 'Une action recommandée', 'La moyenne brute des données', 'Un remerciement uniquement'], 'correct' => [1], 'explanation' => 'Le storytelling vise l\'action : conclure par une recommandation concrète.'],
                    ],
                ],
                [
                    'title' => 'Niveau 10 — Biais et interprétation',
                    'subtitle' => 'Penser juste et conclure honnêtement',
                    'xp_reward' => 200,
                    'content' => "🎯 **Objectif** : reconnaître les biais qui faussent l'interprétation.\n\n💡 Les données ne mentent pas, mais on peut leur faire dire n'importe quoi. L'analyste rigoureux se méfie de ses propres conclusions.\n\n⚠️ Les pièges classiques :\n• **Corrélation ≠ causalité** : les ventes de parapluies et de bottes augmentent ensemble, mais l'un ne cause pas l'autre (c'est la pluie).\n• **Biais de sélection** : sonder seulement les clients satisfaits fausse la moyenne.\n• **Biais de confirmation** : ne retenir que les chiffres qui confirment ce qu'on croyait déjà.\n• **Effet de l'échantillon trop petit** : conclure sur 5 clients n'est pas fiable.\n• **Données manquantes ignorées** : oublier les abandons fausse le taux de réussite.\n\n✅ Bonnes pratiques :\n• Se demander : « Quelle autre explication est possible ? »\n• Vérifier la taille et la représentativité de l'échantillon.\n• Distinguer ce que les données montrent de ce qu'on suppose.\n• Communiquer aussi les **limites** de l'analyse.\n\n🏆 Félicitations, tu as terminé la roadmap Data Analyst ! Tu sais désormais comprendre le métier, nettoyer, analyser avec Excel et SQL, visualiser, mesurer des KPI et raconter tes résultats sans te tromper.\n\nDébouchés : Data Analyst junior, Business Analyst, Analyste reporting/BI, chargé d'études. Évolutions possibles : Data Analyst senior, Data Scientist, Analytics Engineer ou Lead Data. Continue à pratiquer sur de vrais jeux de données : c'est en analysant qu'on devient analyste. Bonne route !",
                    'questions' => [
                        ['question' => 'Deux variables augmentent ensemble. Que peut-on conclure ?', 'options' => ['L\'une cause forcément l\'autre', 'Il y a corrélation mais pas forcément causalité', 'Les données sont fausses', 'Il faut les supprimer'], 'correct' => [1], 'explanation' => 'Corrélation n\'implique pas causalité : un facteur tiers peut expliquer les deux.'],
                        ['question' => 'Ne retenir que les chiffres qui confirment son idée de départ est un :', 'options' => ['Biais de confirmation', 'Calcul de médiane', 'Tableau croisé dynamique', 'Nettoyage de données'], 'correct' => [0], 'explanation' => 'Le biais de confirmation consiste à privilégier les données qui valident une croyance préexistante.'],
                        ['question' => 'Quelles pratiques renforcent une interprétation honnête ? (plusieurs réponses)', 'options' => ['Vérifier la taille et la représentativité de l\'échantillon', 'Communiquer les limites de l\'analyse', 'Ignorer les données manquantes', 'Chercher d\'autres explications possibles'], 'correct' => [0, 1, 3], 'explanation' => 'Vérifier l\'échantillon, exposer les limites et envisager d\'autres causes sont rigoureux ; ignorer les manquants fausse les résultats.'],
                    ],
                ],
            ],
        ]);

        $this->command->info('  ✓ Roadmap Data Analyst créée (10 niveaux).');
    }
}
