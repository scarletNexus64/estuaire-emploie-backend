<?php

namespace Database\Seeders\Roadmaps;

use Illuminate\Database\Seeder;

/**
 * Roadmap Machine Learning & IA — des concepts fondamentaux jusqu'au deep learning,
 * aux LLM, au déploiement et à l'éthique.
 * Contenu rédigé (tips), QCM de validation par niveau.
 */
class MachineLearningRoadmapSeeder extends Seeder
{
    use RoadmapSeederHelper;

    public function run(): void
    {
        $this->createRoadmap([
            'title' => 'Lance ta carrière en IA & Data Science',
            'slug' => 'machine-learning-ia',
            'domain' => 'data',
            'description' => "Comprends comment les machines apprennent à partir des données. Des différences entre IA, ML et deep learning jusqu'aux réseaux de neurones, aux LLM, au déploiement d'un modèle et aux enjeux éthiques. Une approche intuition d'abord, sans formalisme mathématique lourd.",
            'objectives' => "Distinguer IA, machine learning et deep learning\nSaisir l'intuition des maths utiles (algèbre, stats, probas)\nManipuler la data avec Python (numpy, pandas)\nPréparer des données et construire des features\nEntraîner et évaluer des modèles supervisés et non supervisés\nComprendre les réseaux de neurones, le NLP et les LLM\nDéployer un modèle et maîtriser ses limites éthiques",
            'icon' => '🤖',
            'color' => '#EC4899',
            'difficulty' => 'advanced',
            'required_packs' => [],
            'pass_threshold' => 70,
            'order' => 1,
            'levels' => [
                [
                    'title' => 'Niveau 1 — Qu\'est-ce que le ML et l\'IA ?',
                    'subtitle' => 'IA, machine learning, deep learning',
                    'xp_reward' => 100,
                    'content' => "🎯 **Objectif** : distinguer trois termes souvent confondus.\n\n💡 L'**intelligence artificielle (IA)** est le terme le plus large : c'est toute technique qui fait exécuter à une machine des tâches qui semblent \"intelligentes\" (jouer aux échecs, traduire, reconnaître une image). Cela inclut même de vieilles approches à base de règles écrites à la main.\n\n💡 Le **machine learning (ML)** est un sous-ensemble de l'IA. Au lieu de programmer des règles explicites, on **montre des exemples** à un algorithme et il apprend les règles tout seul. Par exemple, pour détecter un spam, on ne liste pas tous les mots suspects : on donne des milliers d'emails étiquetés \"spam\" / \"pas spam\" et le modèle déduit les motifs.\n\n💡 Le **deep learning** est un sous-ensemble du ML, basé sur les **réseaux de neurones** à plusieurs couches. Il excelle sur les données complexes (images, sons, texte) mais demande beaucoup de données et de puissance de calcul.\n\n✅ La hiérarchie à retenir : **IA ⊃ Machine Learning ⊃ Deep Learning**. Tout deep learning est du ML, tout ML est de l'IA, mais l'inverse n'est pas vrai.\n\n⚠️ Un point clé : un modèle de ML n'est pas \"magique\". Il ne fait que repérer des régularités statistiques dans les exemples qu'on lui fournit. La qualité des données détermine la qualité du modèle.",
                    'questions' => [
                        [
                            'question' => 'Quelle est la bonne relation entre ces trois notions ?',
                            'options' => ['Deep Learning ⊃ ML ⊃ IA', 'IA ⊃ Machine Learning ⊃ Deep Learning', 'ML ⊃ IA ⊃ Deep Learning', 'Ce sont trois synonymes'],
                            'correct' => [1],
                            'explanation' => 'L\'IA est le terme le plus large, le ML en est un sous-ensemble, et le deep learning un sous-ensemble du ML.',
                        ],
                        [
                            'question' => 'Qu\'est-ce qui caractérise le machine learning par rapport à un programme classique à base de règles ?',
                            'options' => ['Il est toujours plus rapide', 'Il apprend les règles à partir d\'exemples au lieu d\'être codé explicitement', 'Il n\'utilise jamais de données', 'Il fonctionne sans ordinateur'],
                            'correct' => [1],
                            'explanation' => 'Le ML déduit des motifs à partir d\'exemples au lieu de suivre des règles écrites à la main.',
                        ],
                        [
                            'question' => 'Quelles affirmations sont vraies ? (plusieurs réponses)',
                            'options' => ['Le deep learning est un sous-ensemble du ML', 'Toute IA est forcément du deep learning', 'Le deep learning utilise des réseaux de neurones', 'Le ML repose sur des régularités statistiques dans les données'],
                            'correct' => [0, 2, 3],
                            'explanation' => 'Le deep learning est un sous-ensemble du ML basé sur les réseaux de neurones, et le ML exploite les régularités des données. Toute IA n\'est pas du deep learning (ex. systèmes à règles).',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 2 — Les maths utiles (intuition)',
                    'subtitle' => 'Algèbre linéaire, stats, probabilités',
                    'xp_reward' => 120,
                    'content' => "🎯 **Objectif** : comprendre l'intuition des maths derrière le ML, sans formalisme lourd.\n\n💡 **Algèbre linéaire** : en ML, une donnée est souvent un **vecteur** (une liste de nombres). Une image en niveaux de gris, c'est une grille de pixels qu'on peut aplatir en une longue liste. Un ensemble de données, c'est une **matrice** : des lignes (exemples) et des colonnes (caractéristiques). Les modèles font surtout des multiplications de matrices. Tu n'as pas besoin de calculer à la main : il faut juste visualiser que \"transformer des données\" = \"combiner des nombres\".\n\n💡 **Statistiques** : elles décrivent les données. La **moyenne** donne le centre, l'**écart-type** mesure la dispersion. La **corrélation** indique si deux variables varient ensemble (taille et poids, par exemple). ⚠️ Corrélation n'est pas causalité : deux choses peuvent monter ensemble sans que l'une cause l'autre.\n\n💡 **Probabilités** : beaucoup de modèles ne répondent pas \"oui/non\" mais donnent une **probabilité** (ex. \"90 % de chances que ce soit un chat\"). Comprendre qu'une prédiction est souvent une **mesure de confiance**, et non une certitude, est fondamental.\n\n✅ Notion clé : le **gradient**. C'est l'intuition de \"dans quelle direction ajuster les paramètres pour réduire l'erreur\". Imagine descendre une colline dans le brouillard en suivant la pente la plus raide : c'est la **descente de gradient**, le moteur de l'apprentissage.\n\n💡 Bonne nouvelle : les bibliothèques font les calculs pour toi. L'important est l'intuition : données = nombres, apprendre = réduire une erreur étape par étape.",
                    'questions' => [
                        [
                            'question' => 'En ML, comment représente-t-on souvent un jeu de données ?',
                            'options' => ['Comme un seul nombre', 'Comme une matrice : lignes = exemples, colonnes = caractéristiques', 'Comme un texte non structuré uniquement', 'Comme une image obligatoirement'],
                            'correct' => [1],
                            'explanation' => 'Un jeu de données est typiquement une matrice : chaque ligne est un exemple, chaque colonne une caractéristique (feature).',
                        ],
                        [
                            'question' => 'Que dit l\'adage "corrélation n\'est pas causalité" ?',
                            'options' => ['Deux variables corrélées causent toujours l\'une l\'autre', 'Deux variables peuvent varier ensemble sans relation de cause à effet', 'La corrélation est inutile', 'La causalité n\'existe pas en statistiques'],
                            'correct' => [1],
                            'explanation' => 'Deux variables peuvent être corrélées par coïncidence ou via une cause commune, sans que l\'une cause l\'autre.',
                        ],
                        [
                            'question' => 'À quoi correspond intuitivement la descente de gradient ?',
                            'options' => ['Trier les données par ordre croissant', 'Ajuster les paramètres pas à pas pour réduire l\'erreur, en suivant la pente', 'Supprimer les valeurs aberrantes', 'Augmenter la taille du jeu de données'],
                            'correct' => [1],
                            'explanation' => 'La descente de gradient ajuste les paramètres dans la direction qui réduit l\'erreur, comme descendre une pente.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 3 — Python pour la data',
                    'subtitle' => 'numpy et pandas (concepts)',
                    'xp_reward' => 120,
                    'content' => "🎯 **Objectif** : connaître les deux outils Python incontournables pour manipuler des données.\n\n💡 **Python** est le langage dominant en ML, car il est lisible et possède un écosystème riche de bibliothèques. On l'utilise rarement \"nu\" : on s'appuie sur des libraries spécialisées.\n\n💡 **numpy** fournit le **tableau (array)** : une structure de nombres ultra-rapide, parfaite pour l'algèbre linéaire. Contrairement à une liste Python classique, numpy fait des opérations sur tous les éléments d'un coup (\"vectorisation\"), ce qui est beaucoup plus rapide.\n```python\nimport numpy as np\na = np.array([1, 2, 3])\nprint(a * 2)   # [2 4 6] — multiplie chaque élément\n```\n\n💡 **pandas** fournit le **DataFrame** : un tableau avec des colonnes nommées, comme une feuille Excel ou une table SQL en mémoire. C'est l'outil de référence pour charger, explorer et nettoyer des données.\n```python\nimport pandas as pd\ndf = pd.read_csv('clients.csv')\nprint(df.head())          # aperçu des premières lignes\nprint(df['age'].mean())   # moyenne d'une colonne\n```\n\n✅ À retenir : **numpy** pour le calcul numérique brut (vecteurs, matrices), **pandas** pour manipuler des données tabulaires nommées. Les deux sont souvent utilisés ensemble : on charge avec pandas, on calcule avec numpy.\n\n💡 D'autres bibliothèques s'appuient dessus : **matplotlib** pour visualiser, **scikit-learn** pour les modèles classiques.",
                    'questions' => [
                        [
                            'question' => 'Quelle bibliothèque fournit le DataFrame, un tableau avec colonnes nommées ?',
                            'options' => ['numpy', 'pandas', 'matplotlib', 'requests'],
                            'correct' => [1],
                            'explanation' => 'pandas fournit le DataFrame, idéal pour les données tabulaires nommées.',
                        ],
                        [
                            'question' => 'Pourquoi numpy est-il plus rapide qu\'une liste Python pour le calcul numérique ?',
                            'options' => ['Parce qu\'il est écrit en Java', 'Grâce à la vectorisation : il opère sur tous les éléments d\'un coup', 'Parce qu\'il supprime les données inutiles', 'Parce qu\'il utilise le cloud'],
                            'correct' => [1],
                            'explanation' => 'numpy applique les opérations de façon vectorisée sur l\'ensemble du tableau, bien plus vite qu\'une boucle Python.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 4 — Les données : collecte, nettoyage, features',
                    'subtitle' => 'La matière première du ML',
                    'xp_reward' => 150,
                    'content' => "🎯 **Objectif** : comprendre que la qualité des données prime sur l'algorithme.\n\n💡 **Collecte** : les données viennent de fichiers (CSV, bases SQL), d'API, de capteurs, de logs… La première question est toujours : *ces données sont-elles représentatives du problème que je veux résoudre ?* Des données biaisées donneront un modèle biaisé.\n\n💡 **Nettoyage** : les données réelles sont sales. On doit gérer :\n• les **valeurs manquantes** (remplir avec une moyenne, ou supprimer la ligne)\n• les **valeurs aberrantes** (outliers : un âge de 200 ans)\n• les **doublons** et les incohérences de format (dates, majuscules, unités)\n\n💡 **Feature engineering** : une **feature** (caractéristique) est une variable d'entrée du modèle. Souvent les données brutes ne suffisent pas : on les transforme. Par exemple, à partir d'une date de naissance on calcule un âge ; à partir d'un texte on compte des mots. Les modèles ne comprennent que des nombres, donc on doit aussi **encoder** les variables catégorielles (ex. ville \"Douala\" → un code numérique) et souvent **normaliser** (mettre les échelles sur une base comparable).\n\n✅ Adage célèbre : **\"Garbage in, garbage out\"** — des données de mauvaise qualité produisent un modèle de mauvaise qualité, quel que soit l'algorithme. Les data scientists passent souvent 70 à 80 % de leur temps sur la préparation des données.\n\n⚠️ Attention au **data leakage** (fuite) : ne jamais utiliser une information du futur, ou la réponse elle-même, comme feature — le modèle semblerait parfait à l'entraînement et échouerait en réel.",
                    'questions' => [
                        [
                            'question' => 'Que signifie l\'adage "Garbage in, garbage out" en ML ?',
                            'options' => ['Plus de données est toujours mieux', 'Des données de mauvaise qualité produisent un modèle de mauvaise qualité', 'Il faut jeter toutes les données', 'L\'algorithme compte plus que les données'],
                            'correct' => [1],
                            'explanation' => 'La qualité du modèle dépend directement de la qualité des données fournies.',
                        ],
                        [
                            'question' => 'Quelles opérations font partie du nettoyage des données ? (plusieurs réponses)',
                            'options' => ['Gérer les valeurs manquantes', 'Supprimer les doublons', 'Traiter les valeurs aberrantes', 'Déployer le modèle en production'],
                            'correct' => [0, 1, 2],
                            'explanation' => 'Le nettoyage couvre les valeurs manquantes, les doublons et les outliers. Le déploiement est une étape ultérieure distincte.',
                        ],
                        [
                            'question' => 'Qu\'est-ce qu\'une "feature" (caractéristique) ?',
                            'options' => ['Le résultat final du modèle', 'Une variable d\'entrée utilisée par le modèle', 'Un bug dans les données', 'Un type de réseau de neurones'],
                            'correct' => [1],
                            'explanation' => 'Une feature est une variable d\'entrée que le modèle utilise pour prédire.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 5 — Apprentissage supervisé',
                    'subtitle' => 'Régression et classification',
                    'xp_reward' => 160,
                    'content' => "🎯 **Objectif** : comprendre le paradigme le plus courant du ML.\n\n💡 En **apprentissage supervisé**, chaque exemple d'entraînement vient avec sa **réponse correcte** (le label). Le modèle apprend la relation entre les entrées (features) et la sortie attendue. \"Supervisé\" car on \"corrige\" le modèle avec les bonnes réponses pendant l'apprentissage.\n\n✅ Deux grandes familles selon le type de sortie :\n\n💡 **Régression** : la sortie est un **nombre continu**. Exemples : prédire le prix d'une maison, la température de demain, le salaire selon l'expérience. On cherche une fonction qui colle au mieux aux points.\n\n💡 **Classification** : la sortie est une **catégorie**. Exemples : email spam ou non (binaire), reconnaître un chiffre de 0 à 9 (multi-classes), diagnostiquer une maladie. Le modèle attribue chaque entrée à une classe.\n\n✅ Le processus général :\n```\n1. Données étiquetées  (entrée → réponse connue)\n2. Le modèle prédit\n3. On compare prédiction vs réponse réelle (erreur)\n4. On ajuste les paramètres pour réduire l'erreur\n5. On répète jusqu'à convergence\n```\n\n💡 Astuce pour distinguer : si la question est \"combien ?\" → régression ; si la question est \"laquelle / quelle catégorie ?\" → classification.",
                    'questions' => [
                        [
                            'question' => 'Qu\'est-ce qui caractérise l\'apprentissage supervisé ?',
                            'options' => ['Il n\'utilise aucune donnée', 'Chaque exemple d\'entraînement est accompagné de sa réponse correcte (label)', 'Il regroupe les données sans étiquette', 'Il ne fonctionne que sur des images'],
                            'correct' => [1],
                            'explanation' => 'En supervisé, les exemples d\'entraînement sont étiquetés avec la bonne réponse.',
                        ],
                        [
                            'question' => 'Prédire le prix d\'une maison (un nombre) relève de quel type de problème ?',
                            'options' => ['Classification', 'Régression', 'Clustering', 'Réduction de dimension'],
                            'correct' => [1],
                            'explanation' => 'Prédire une valeur numérique continue est un problème de régression.',
                        ],
                        [
                            'question' => 'Lesquels sont des exemples de classification ? (plusieurs réponses)',
                            'options' => ['Détecter si un email est un spam', 'Reconnaître un chiffre manuscrit de 0 à 9', 'Prédire la température exacte de demain en degrés', 'Diagnostiquer une maladie parmi plusieurs catégories'],
                            'correct' => [0, 1, 3],
                            'explanation' => 'Spam/non-spam, chiffres 0-9 et diagnostic sont des classifications. Prédire une température en degrés est une régression.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 6 — Évaluer un modèle',
                    'subtitle' => 'Train/test, overfitting, métriques',
                    'xp_reward' => 180,
                    'content' => "🎯 **Objectif** : savoir si un modèle est vraiment bon, et pas seulement \"bon sur le papier\".\n\n💡 **Séparation train / test** : on ne juge JAMAIS un modèle sur les données qui ont servi à l'entraîner. On garde une partie des données (le **jeu de test**, souvent 20 %) cachée. Le modèle apprend sur le **train**, puis on mesure sa performance sur le **test**, qu'il n'a jamais vu. C'est la seule façon honnête de prédire son comportement en réel.\n\n⚠️ **Overfitting (surapprentissage)** : le modèle \"apprend par cœur\" les données d'entraînement, bruit compris, au lieu de capturer la tendance générale. Symptôme : excellent sur le train, mauvais sur le test. À l'inverse, l'**underfitting (sous-apprentissage)** est un modèle trop simple qui rate même les tendances évidentes (mauvais partout).\n\n💡 **Métriques de classification** :\n• **Accuracy (exactitude)** : pourcentage de bonnes prédictions. Trompeuse si les classes sont déséquilibrées (99 % de \"non-spam\" → un modèle qui dit toujours \"non-spam\" a 99 % d'accuracy mais est inutile).\n• **Précision** : parmi ce que le modèle a prédit positif, combien le sont vraiment ? (limite les fausses alertes)\n• **Rappel (recall)** : parmi tous les vrais positifs, combien le modèle en a-t-il trouvés ? (limite les oublis)\n\n✅ Précision et rappel sont souvent en **tension** : augmenter l'un baisse l'autre. Le **F1-score** combine les deux en un seul chiffre. Pour la régression, on utilise plutôt l'erreur moyenne (ex. **RMSE**).\n\n💡 Pour fiabiliser l'évaluation sur peu de données, on utilise la **validation croisée** : on découpe en plusieurs morceaux et on teste tour à tour sur chacun.",
                    'questions' => [
                        [
                            'question' => 'Pourquoi sépare-t-on les données en jeu d\'entraînement et jeu de test ?',
                            'options' => ['Pour entraîner deux modèles différents', 'Pour évaluer le modèle sur des données qu\'il n\'a jamais vues', 'Pour accélérer l\'entraînement', 'Parce que SQL l\'exige'],
                            'correct' => [1],
                            'explanation' => 'Le jeu de test permet de mesurer honnêtement la performance sur des données nouvelles.',
                        ],
                        [
                            'question' => 'Quel est le symptôme typique de l\'overfitting (surapprentissage) ?',
                            'options' => ['Mauvais sur train ET sur test', 'Excellent sur le train mais mauvais sur le test', 'Excellent partout', 'Le modèle ne s\'entraîne pas'],
                            'correct' => [1],
                            'explanation' => 'L\'overfitting se voit quand le modèle est excellent à l\'entraînement mais échoue sur des données nouvelles.',
                        ],
                        [
                            'question' => 'Pourquoi l\'accuracy peut-elle être trompeuse ?',
                            'options' => ['Elle est toujours fausse', 'Sur des classes déséquilibrées, un modèle naïf peut avoir une accuracy élevée tout en étant inutile', 'Elle ne se calcule pas en classification', 'Elle ne marche qu\'en régression'],
                            'correct' => [1],
                            'explanation' => 'Avec des classes très déséquilibrées, prédire toujours la classe majoritaire donne une forte accuracy sans valeur réelle.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 7 — Algorithmes classiques',
                    'subtitle' => 'Arbres, k-NN, régression logistique',
                    'xp_reward' => 180,
                    'content' => "🎯 **Objectif** : avoir l'intuition de quelques algorithmes fondamentaux (sans les maths).\n\n💡 **Régression logistique** : malgré son nom, c'est un algorithme de **classification**. Il calcule une probabilité (entre 0 et 1) qu'un exemple appartienne à une classe, puis tranche selon un seuil (ex. > 0,5 → positif). Simple, rapide, interprétable : un excellent point de départ.\n\n💡 **k plus proches voisins (k-NN)** : intuition très simple — \"dis-moi qui sont tes voisins, je te dirai qui tu es\". Pour classer un nouveau point, on regarde les **k exemples les plus proches** dans les données et on prend la classe majoritaire parmi eux. Pas de vraie phase d'entraînement, mais lent à la prédiction sur de gros volumes.\n\n💡 **Arbre de décision** : une suite de questions oui/non, comme un organigramme. \"L'âge est-il > 30 ? Si oui… le revenu est-il > 2000 ?…\". Chaque branche affine la décision. Très **interprétable** (on voit le chemin), mais un seul arbre a tendance à l'overfitting.\n\n💡 **Forêt aléatoire (random forest)** : on entraîne **beaucoup d'arbres** légèrement différents et on **vote** (ou on moyenne). C'est un exemple d'**ensemble** : combiner plusieurs modèles faibles pour en former un robuste. Souvent très performant en pratique. Le **gradient boosting** (XGBoost, etc.) est une autre famille d'ensembles redoutable sur données tabulaires.\n\n✅ À retenir : il n'existe pas d'algorithme universellement meilleur (\"no free lunch\"). On teste plusieurs approches et on garde celle qui marche le mieux sur le jeu de test.",
                    'questions' => [
                        [
                            'question' => 'Malgré son nom, la régression logistique sert surtout à...',
                            'options' => ['La régression de valeurs continues', 'La classification', 'Le clustering', 'La réduction de dimension'],
                            'correct' => [1],
                            'explanation' => 'La régression logistique estime une probabilité d\'appartenance à une classe : c\'est un classifieur.',
                        ],
                        [
                            'question' => 'Quelle est l\'intuition de l\'algorithme k-NN ?',
                            'options' => ['Construire un seul grand arbre', 'Classer un point selon la classe majoritaire de ses k voisins les plus proches', 'Réduire le nombre de colonnes', 'Calculer une moyenne globale'],
                            'correct' => [1],
                            'explanation' => 'k-NN regarde les k exemples les plus proches et adopte leur classe majoritaire.',
                        ],
                        [
                            'question' => 'Qu\'est-ce qu\'une forêt aléatoire (random forest) ?',
                            'options' => ['Un seul arbre de décision très profond', 'Un ensemble de nombreux arbres dont on combine les votes', 'Un réseau de neurones', 'Un algorithme de nettoyage de données'],
                            'correct' => [1],
                            'explanation' => 'Une forêt aléatoire combine de nombreux arbres (méthode d\'ensemble) pour gagner en robustesse.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 8 — Apprentissage non supervisé',
                    'subtitle' => 'Clustering et réduction de dimension',
                    'xp_reward' => 200,
                    'content' => "🎯 **Objectif** : trouver de la structure dans des données SANS étiquettes.\n\n💡 En **apprentissage non supervisé**, les données n'ont **pas de réponse connue**. On ne cherche pas à prédire une cible, mais à **découvrir des structures cachées**. Deux grandes tâches : le clustering et la réduction de dimension.\n\n💡 **Clustering (partitionnement)** : regrouper automatiquement les exemples qui se ressemblent. Exemple : segmenter des clients en groupes au comportement similaire, sans avoir défini les groupes à l'avance. L'algorithme le plus connu est **k-means** : on fixe un nombre k de groupes, et l'algorithme place chaque point dans le groupe dont le centre est le plus proche, en ajustant les centres itérativement.\n```\nk-means en bref :\n1. Choisir k centres au hasard\n2. Affecter chaque point au centre le plus proche\n3. Recalculer chaque centre (moyenne de son groupe)\n4. Répéter 2-3 jusqu'à stabilité\n```\n\n💡 **Réduction de dimension** : quand les données ont des centaines de colonnes, c'est difficile à visualiser et à traiter. On les **compresse** en gardant l'essentiel de l'information. La **PCA (analyse en composantes principales)** trouve les directions où les données varient le plus et projette dessus. Cela sert à visualiser (passer en 2D), accélérer l'entraînement et réduire le bruit.\n\n⚠️ Difficulté du non supervisé : sans labels, **évaluer** le résultat est plus subjectif. \"Combien de clusters ?\" n'a pas toujours de réponse unique — il faut souvent l'expertise métier pour interpréter.",
                    'questions' => [
                        [
                            'question' => 'Qu\'est-ce qui distingue l\'apprentissage non supervisé du supervisé ?',
                            'options' => ['Il est toujours plus précis', 'Les données n\'ont pas d\'étiquettes / réponses connues', 'Il n\'utilise pas d\'ordinateur', 'Il ne fonctionne que sur du texte'],
                            'correct' => [1],
                            'explanation' => 'En non supervisé, il n\'y a pas de cible connue : on cherche des structures cachées.',
                        ],
                        [
                            'question' => 'Que fait l\'algorithme k-means ?',
                            'options' => ['Il prédit un prix continu', 'Il regroupe les points en k clusters selon leur proximité', 'Il étiquette les données manuellement', 'Il déploie un modèle en API'],
                            'correct' => [1],
                            'explanation' => 'k-means partitionne les données en k groupes en rapprochant chaque point du centre le plus proche.',
                        ],
                        [
                            'question' => 'À quoi sert la réduction de dimension (ex. PCA) ? (plusieurs réponses)',
                            'options' => ['Compresser les données en gardant l\'essentiel', 'Faciliter la visualisation (ex. passer en 2D)', 'Ajouter des étiquettes aux données', 'Réduire le bruit et accélérer l\'entraînement'],
                            'correct' => [0, 1, 3],
                            'explanation' => 'La réduction de dimension compresse, aide à visualiser et réduit le bruit. Elle n\'ajoute pas d\'étiquettes.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 9 — Réseaux de neurones & deep learning',
                    'subtitle' => 'Couches, neurones, activation',
                    'xp_reward' => 220,
                    'content' => "🎯 **Objectif** : comprendre l'intuition des réseaux de neurones.\n\n💡 Un **neurone artificiel** est une petite unité de calcul : il reçoit plusieurs nombres en entrée, les multiplie par des **poids**, les additionne, puis applique une **fonction d'activation**. C'est vaguement inspiré du cerveau, mais c'est avant tout une fonction mathématique simple.\n\n💡 Un **réseau de neurones** empile ces unités en **couches** :\n• la **couche d'entrée** reçoit les features,\n• une ou plusieurs **couches cachées** transforment l'information,\n• la **couche de sortie** produit la prédiction.\nQuand il y a beaucoup de couches cachées, on parle de **deep learning** (réseau \"profond\").\n\n💡 La **fonction d'activation** (ex. ReLU, sigmoïde) introduit de la **non-linéarité**. Sans elle, empiler des couches ne servirait à rien : le réseau ne pourrait apprendre que des relations linéaires. C'est elle qui permet de modéliser des motifs complexes.\n\n💡 **Comment ça apprend ?** Le réseau fait une prédiction, on mesure l'erreur, puis la **rétropropagation (backpropagation)** calcule comment chaque poids a contribué à l'erreur et les ajuste, via la descente de gradient (vu au niveau 2). On répète sur de nombreux **epochs** (passages sur les données).\n\n✅ Les architectures spécialisées : les **CNN** (réseaux convolutifs) excellent sur les **images**, les **RNN/LSTM** historiquement sur les **séquences** (texte, audio), et les **transformers** dominent aujourd'hui le texte (voir niveau suivant). Le deep learning brille quand on a beaucoup de données et de calcul, mais reste souvent une \"boîte noire\" difficile à interpréter.",
                    'questions' => [
                        [
                            'question' => 'Que fait un neurone artificiel, simplifié ?',
                            'options' => ['Il stocke une base de données', 'Il combine ses entrées via des poids, somme, puis applique une fonction d\'activation', 'Il trie des nombres', 'Il chiffre les données'],
                            'correct' => [1],
                            'explanation' => 'Un neurone pondère ses entrées, les additionne, puis passe le résultat dans une fonction d\'activation.',
                        ],
                        [
                            'question' => 'À quoi sert la fonction d\'activation (ex. ReLU) ?',
                            'options' => ['À supprimer des données', 'À introduire de la non-linéarité pour modéliser des relations complexes', 'À sauvegarder le modèle', 'À accélérer le disque dur'],
                            'correct' => [1],
                            'explanation' => 'Sans non-linéarité, empiler des couches resterait équivalent à un modèle linéaire ; l\'activation débloque la complexité.',
                        ],
                        [
                            'question' => 'Quel mécanisme ajuste les poids du réseau pour réduire l\'erreur ?',
                            'options' => ['Le clustering', 'La rétropropagation (backpropagation) avec descente de gradient', 'La normalisation des données', 'La validation croisée'],
                            'correct' => [1],
                            'explanation' => 'La rétropropagation calcule la contribution de chaque poids à l\'erreur, et la descente de gradient les ajuste.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 10 — NLP & LLM',
                    'subtitle' => 'Embeddings, transformers, grands modèles',
                    'xp_reward' => 240,
                    'content' => "🎯 **Objectif** : comprendre comment les machines traitent le langage et ce qu'est un LLM.\n\n💡 Le **NLP (traitement du langage naturel)** vise à faire manipuler le texte par des machines : traduction, résumé, analyse de sentiment, chatbots. Problème : un modèle ne comprend que des nombres, pas des mots. Il faut donc transformer le texte en nombres.\n\n💡 **Embeddings** : on représente chaque mot (ou morceau de mot) par un **vecteur de nombres** qui capture son sens. L'idée géniale : des mots de sens proche obtiennent des vecteurs proches. \"Roi\" et \"reine\" seront voisins dans cet espace, et on observe même des relations comme *roi − homme + femme ≈ reine*. Les embeddings donnent au modèle une notion de **similarité sémantique**.\n\n💡 **Transformers** : c'est l'architecture qui a révolutionné le NLP depuis 2017. Son ingrédient clé est l'**attention** : pour traiter un mot, le modèle \"regarde\" tous les autres mots de la phrase et pondère leur importance. Cela lui permet de saisir le contexte et les dépendances longues (\"il\" renvoie à qui ?), bien mieux que les anciens modèles séquentiels.\n\n💡 Un **LLM (grand modèle de langage)** est un très gros transformer entraîné sur d'énormes quantités de texte. Son entraînement de base est simple en apparence : **prédire le mot suivant** encore et encore. À grande échelle, cela fait émerger des capacités de rédaction, de raisonnement, de traduction… Des modèles comme ceux de la famille **Claude** (Anthropic) en sont des exemples.\n\n⚠️ Points de vigilance essentiels : un LLM peut **halluciner** (inventer des informations fausses avec assurance), il reflète les **biais** de ses données d'entraînement, et il a une **date de coupure** des connaissances. Il ne \"comprend\" pas comme un humain : il prédit statistiquement le texte le plus plausible. À utiliser avec esprit critique et vérification.",
                    'questions' => [
                        [
                            'question' => 'Qu\'est-ce qu\'un embedding en NLP ?',
                            'options' => ['Un mot écrit en majuscules', 'Un vecteur de nombres représentant le sens d\'un mot, où les mots proches ont des vecteurs proches', 'Une base de données de textes', 'Un type de fonction d\'activation'],
                            'correct' => [1],
                            'explanation' => 'Un embedding transforme un mot en vecteur capturant son sens ; les mots sémantiquement proches sont proches dans l\'espace.',
                        ],
                        [
                            'question' => 'Quel mécanisme est au cœur de l\'architecture transformer ?',
                            'options' => ['Le clustering k-means', 'L\'attention, qui pondère l\'importance des autres mots pour traiter chaque mot', 'La rétropropagation uniquement', 'La normalisation des images'],
                            'correct' => [1],
                            'explanation' => 'Le mécanisme d\'attention permet au transformer de pondérer le contexte de chaque mot par rapport aux autres.',
                        ],
                        [
                            'question' => 'Quelles affirmations sur les LLM sont vraies ? (plusieurs réponses)',
                            'options' => ['Ils sont entraînés en grande partie à prédire le mot suivant', 'Ils peuvent halluciner des informations fausses', 'Ils comprennent le monde exactement comme un humain', 'Ils peuvent refléter les biais de leurs données d\'entraînement'],
                            'correct' => [0, 1, 3],
                            'explanation' => 'Les LLM prédisent le mot suivant à grande échelle, peuvent halluciner et porter des biais. Ils ne "comprennent" pas comme un humain : ils prédisent statistiquement.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 11 — Déployer un modèle',
                    'subtitle' => 'Servir un modèle, exposer une API',
                    'xp_reward' => 220,
                    'content' => "🎯 **Objectif** : faire passer un modèle du notebook au monde réel.\n\n💡 Un modèle entraîné dans un notebook ne sert à rien tant qu'une application ne peut pas l'utiliser. Le **déploiement** consiste à rendre le modèle accessible pour faire des prédictions sur de nouvelles données : c'est l'**inférence**.\n\n💡 **Sérialisation** : on sauvegarde le modèle entraîné dans un fichier (formats comme pickle, ONNX, SavedModel…). On peut alors le recharger sans le ré-entraîner.\n\n💡 **Servir via une API** : la méthode la plus courante est d'exposer le modèle derrière une **API REST**. Une application envoie les données d'entrée, le serveur renvoie la prédiction.\n```\nClient → POST /predict { \"age\": 30, \"ville\": \"Douala\" }\nServeur → { \"prediction\": \"client fidèle\", \"probabilite\": 0.87 }\n```\nDes frameworks comme **FastAPI** ou **Flask** (Python) facilitent cela ; on emballe souvent le tout dans un conteneur **Docker** pour un déploiement reproductible.\n\n💡 **Batch vs temps réel** : certaines prédictions se font **en direct** (à chaque requête, ex. recommandation) ; d'autres en **batch** (on calcule toutes les prédictions la nuit et on les stocke). Le choix dépend des besoins de latence.\n\n⚠️ Le travail ne s'arrête pas au déploiement. Il faut **monitorer** le modèle : les données réelles évoluent (**data drift**) et les performances se dégradent avec le temps. On doit surveiller, et **ré-entraîner** régulièrement. Cet ensemble de pratiques s'appelle le **MLOps**.",
                    'questions' => [
                        [
                            'question' => 'Comment expose-t-on le plus souvent un modèle pour qu\'une application l\'utilise ?',
                            'options' => ['En envoyant le notebook par email', 'Derrière une API REST qui reçoit les entrées et renvoie une prédiction', 'En l\'imprimant sur papier', 'Uniquement via une feuille Excel'],
                            'correct' => [1],
                            'explanation' => 'Servir le modèle via une API REST (ex. FastAPI/Flask) est la méthode standard pour l\'inférence en production.',
                        ],
                        [
                            'question' => 'Qu\'est-ce que le "data drift" et pourquoi surveiller un modèle en production ?',
                            'options' => ['Le modèle disparaît du disque', 'Les données réelles évoluent avec le temps, dégradant les performances du modèle', 'Le code source change tout seul', 'Le serveur change de couleur'],
                            'correct' => [1],
                            'explanation' => 'Le data drift désigne l\'évolution des données réelles ; il faut monitorer et ré-entraîner pour maintenir la performance.',
                        ],
                        [
                            'question' => 'Quelle est la différence entre inférence en temps réel et en batch ?',
                            'options' => ['Aucune, ce sont des synonymes', 'Le temps réel prédit à chaque requête ; le batch calcule un lot de prédictions à l\'avance', 'Le batch est toujours plus précis', 'Le temps réel ne nécessite aucun serveur'],
                            'correct' => [1],
                            'explanation' => 'Le temps réel répond requête par requête ; le batch traite de gros lots de prédictions à intervalles planifiés.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 12 — Éthique & limites',
                    'subtitle' => 'Biais, données, responsabilité',
                    'xp_reward' => 250,
                    'content' => "🎯 **Objectif** : utiliser le ML de façon responsable et lucide.\n\n💡 **Les biais** : un modèle apprend des biais présents dans ses données. Si un historique de recrutement favorisait un certain profil, un modèle entraîné dessus reproduira — voire amplifiera — cette discrimination. Le biais n'est pas forcément dans l'algorithme : il vient souvent des données et de la société qu'elles reflètent. On parle de **fairness** (équité) pour les efforts visant à détecter et corriger ces biais.\n\n💡 **Données et vie privée** : les données d'entraînement peuvent contenir des informations personnelles sensibles. Des réglementations comme le **RGPD** encadrent leur usage. Il faut obtenir le consentement, anonymiser quand c'est possible, et limiter la collecte au strict nécessaire.\n\n💡 **Explicabilité et responsabilité** : pour des décisions à fort impact (crédit, justice, santé), une prédiction \"boîte noire\" pose problème. Les personnes concernées ont le droit de comprendre pourquoi une décision a été prise. Et en cas d'erreur, **qui est responsable** ? Le ML doit rester un **outil d'aide à la décision**, pas un juge automatique irresponsable. On garde un **humain dans la boucle** pour les choix critiques.\n\n💡 **Limites à garder en tête** : un modèle ne fait que des **corrélations statistiques**, il ne \"comprend\" pas le contexte ni les conséquences. Il peut se tromper avec assurance, mal généraliser hors de son domaine d'entraînement, et être détourné. La transparence sur ces limites fait partie d'une pratique professionnelle saine.\n\n🏆 Bravo ! Tu as parcouru toute la roadmap : des concepts de base au deep learning, aux LLM, au déploiement et à l'éthique. Le ML est un domaine qui évolue vite — continue à pratiquer sur de vrais projets, reste curieux et critique. Le meilleur praticien n'est pas celui qui connaît le plus d'algorithmes, mais celui qui sait poser le bon problème, juger ses données et mesurer honnêtement ses résultats. 🎯",
                    'questions' => [
                        [
                            'question' => 'D\'où provient le plus souvent le biais d\'un modèle de ML ?',
                            'options' => ['Uniquement d\'un bug dans le code', 'Des données d\'entraînement, qui reflètent des biais existants', 'De la couleur de l\'interface', 'Du langage de programmation utilisé'],
                            'correct' => [1],
                            'explanation' => 'Les biais proviennent surtout des données d\'entraînement, qui peuvent refléter et amplifier des discriminations existantes.',
                        ],
                        [
                            'question' => 'Pour des décisions à fort impact (crédit, santé, justice), quelle est une bonne pratique ?',
                            'options' => ['Laisser le modèle décider seul, sans contrôle', 'Garder un humain dans la boucle et viser l\'explicabilité des décisions', 'Cacher complètement le fonctionnement', 'Ignorer les réglementations'],
                            'correct' => [1],
                            'explanation' => 'On garde un humain dans la boucle et on vise l\'explicabilité : le ML doit rester une aide à la décision responsable.',
                        ],
                        [
                            'question' => 'Quelles affirmations relèvent d\'un usage responsable du ML ? (plusieurs réponses)',
                            'options' => ['Respecter la vie privée et des réglementations comme le RGPD', 'Détecter et corriger les biais (fairness)', 'Supposer qu\'un modèle ne se trompe jamais', 'Être transparent sur les limites du modèle'],
                            'correct' => [0, 1, 3],
                            'explanation' => 'Respect de la vie privée, lutte contre les biais et transparence sont essentiels. Supposer qu\'un modèle est infaillible est dangereux.',
                        ],
                    ],
                ],
            ],
        ]);

        $this->command->info('  ✓ Roadmap Machine Learning créée (12 niveaux).');
    }
}
