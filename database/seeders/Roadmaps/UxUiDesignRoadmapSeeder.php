<?php

namespace Database\Seeders\Roadmaps;

use Illuminate\Database\Seeder;

/**
 * Roadmap UX/UI Design — concevoir des produits numériques utiles, utilisables et beaux.
 */
class UxUiDesignRoadmapSeeder extends Seeder
{
    use RoadmapSeederHelper;

    public function run(): void
    {
        $this->createRoadmap([
            'title' => 'Deviens UX/UI Designer',
            'slug' => 'ux-ui-design',
            'domain' => 'design',
            'description' => "Apprends à concevoir des applications et sites web que les utilisateurs adorent : de la recherche utilisateur aux maquettes finales sur Figma, en passant par les wireframes, le design system, l'accessibilité et les tests. Un parcours pratique pensé pour le marché africain (mobile money, faibles connexions, multilinguisme).",
            'objectives' => "Distinguer clairement UX (expérience) et UI (interface)\nMener une recherche utilisateur et créer des personas réalistes\nCartographier un parcours utilisateur et structurer l'information\nConcevoir wireframes, maquettes et prototypes interactifs\nMaîtriser les principes de design visuel et un design system\nRendre une interface accessible et la valider par des tests utilisateurs\nUtiliser Figma de façon professionnelle",
            'icon' => '🎨',
            'color' => '#7C3AED',
            'difficulty' => 'intermediate',
            'required_packs' => [],
            'pass_threshold' => 70,
            'order' => 20,
            'levels' => [
                [
                    'title' => 'Niveau 1 — UX vs UI : comprendre la différence',
                    'subtitle' => "L'expérience et l'interface ne sont pas la même chose",
                    'xp_reward' => 100,
                    'content' => "🎯 **Objectif** : ne plus jamais confondre UX et UI, les deux piliers du design produit.\n\n💡 Une métaphore simple : imagine un taxi à Douala.\n• L'**UX (User Experience)** = tout le trajet : trouver le taxi, négocier le prix, le confort, arriver à l'heure. C'est le ressenti global.\n• L'**UI (User Interface)** = le tableau de bord : les boutons, le compteur, les couleurs. C'est ce qu'on voit et touche.\n\n✅ En résumé :\n• UX = **utile + utilisable** (structure, logique, parcours)\n• UI = **beau + clair** (couleurs, typographie, boutons)\n• Une belle UI sur une mauvaise UX = un produit frustrant\n• Une bonne UX sans UI soignée = un produit terne mais fonctionnel\n\n⚠️ Erreur fréquente du débutant : sauter directement à « rendre joli » sans comprendre le besoin. Un designer commence toujours par le problème de l'utilisateur, pas par les couleurs.\n\nLe métier exige les deux compétences, mais certaines entreprises séparent les rôles UX Designer et UI Designer.",
                    'questions' => [
                        ['question' => "Que désigne principalement l'UX (User Experience) ?", 'options' => ['Le choix des couleurs et polices', "Le ressenti global et la facilité d'usage du produit", 'Le code du site web', 'Le logo de la marque'], 'correct' => [1], 'explanation' => "L'UX concerne l'expérience globale : utilité, facilité d'usage et satisfaction de l'utilisateur."],
                        ['question' => "Lesquels relèvent de l'UI (interface) ? (plusieurs réponses)", 'options' => ['La palette de couleurs', 'La logique du parcours de paiement', 'La typographie des boutons', "L'espacement visuel des éléments"], 'correct' => [0, 2, 3], 'explanation' => "L'UI concerne le visuel : couleurs, typographie, espacement ; la logique du parcours relève de l'UX."],
                        ['question' => "Une application a un très beau design mais les utilisateurs n'arrivent pas à finaliser leur achat. Quel est le problème principal ?", 'options' => ['Un problème de couleurs', "Un problème d'UX (parcours mal pensé)", 'Un problème de marque', 'Aucun problème'], 'correct' => [1], 'explanation' => "Si le parcours bloque l'utilisateur, c'est l'UX qui est défaillante, malgré une belle UI."],
                    ],
                ],
                [
                    'title' => 'Niveau 2 — La recherche utilisateur',
                    'subtitle' => 'Concevoir pour de vraies personnes, pas pour soi',
                    'xp_reward' => 110,
                    'content' => "🎯 **Objectif** : apprendre à collecter des informations sur les utilisateurs avant de dessiner quoi que ce soit.\n\n💡 La règle d'or : **« You are not your user »** (tu n'es pas ton utilisateur). Ce qui te semble évident peut être incompréhensible pour un commerçant de Mokolo qui découvre une app.\n\n✅ Méthodes de recherche utilisateur :\n• **Entretiens (interviews)** : discuter 30 min avec de vrais utilisateurs pour comprendre leurs besoins et frustrations\n• **Sondages / questionnaires** : collecter des données auprès de beaucoup de personnes\n• **Observation** : regarder les gens utiliser un produit existant\n• **Analyse de la concurrence** : étudier ce que font les autres apps\n\n⚠️ Question ouverte vs fermée :\n• ❌ « Aimes-tu cette app ? » (réponse oui/non, peu utile)\n• ✅ « Raconte-moi la dernière fois que tu as envoyé de l'argent par mobile money » (récit riche)\n\n📊 On distingue :\n• Recherche **qualitative** = comprendre le « pourquoi » (entretiens)\n• Recherche **quantitative** = mesurer le « combien » (statistiques)\n\nLa recherche évite de construire un produit que personne ne veut.",
                    'questions' => [
                        ['question' => 'Pourquoi mener une recherche utilisateur avant de concevoir ?', 'options' => ['Pour rendre le projet plus long', "Pour comprendre les vrais besoins et éviter de concevoir pour soi-même", 'Pour choisir les couleurs', "Parce que c'est obligatoire légalement"], 'correct' => [1], 'explanation' => "La recherche permet de concevoir pour de vrais utilisateurs et non selon ses propres suppositions."],
                        ['question' => 'Quelle est une question ouverte de bonne qualité en entretien ?', 'options' => ['Aimes-tu cette application ?', 'Utilises-tu un smartphone ? Oui/Non', 'Raconte-moi comment tu fais tes achats en ligne', 'Notre app est-elle la meilleure ?'], 'correct' => [2], 'explanation' => "Une question ouverte invite à un récit riche plutôt qu'à un simple oui/non."],
                        ['question' => 'La recherche qualitative sert surtout à...', 'options' => ['Compter le nombre de clics', "Comprendre les motivations et le pourquoi des utilisateurs", 'Mesurer le temps de chargement', 'Calculer le budget'], 'correct' => [1], 'explanation' => "Le qualitatif explore les motivations et le « pourquoi », contrairement au quantitatif qui mesure."],
                    ],
                ],
                [
                    'title' => 'Niveau 3 — Personas : donner un visage aux utilisateurs',
                    'subtitle' => 'Transformer la recherche en profils utiles',
                    'xp_reward' => 120,
                    'content' => "🎯 **Objectif** : créer des personas, des profils fictifs mais réalistes qui représentent vos groupes d'utilisateurs.\n\n💡 Un **persona** synthétise les résultats de la recherche dans un personnage mémorable. Il aide toute l'équipe à se demander : « Est-ce que ça marcherait pour Aïcha ? ».\n\n✅ Un bon persona contient :\n• **Nom + photo + âge** (ex : Aïcha, 28 ans, Yaoundé)\n• **Profession et contexte** (commerçante, smartphone d'entrée de gamme, data limitée)\n• **Objectifs** (vendre ses tissus en ligne)\n• **Frustrations / freins** (connexion lente, peur des arnaques)\n• **Citation** qui résume son état d'esprit\n\nExemple de fiche :\n| Champ | Valeur |\n|-------|--------|\n| Nom | Aïcha, 28 ans |\n| Objectif | Vendre ses pagnes en ligne |\n| Frein | Connexion 3G instable |\n| Citation | « Je veux que ce soit rapide et fiable » |\n\n⚠️ Le persona doit venir de **données réelles**, pas de l'imagination. Inventer un persona « idéal » fausse toutes les décisions.\n\nOn crée généralement 2 à 4 personas principaux pour un produit.",
                    'questions' => [
                        ['question' => "Qu'est-ce qu'un persona en UX design ?", 'options' => ['Un vrai client payé pour tester', "Un profil fictif et réaliste représentant un groupe d'utilisateurs", "Le nom de l'application", "Un type de couleur"], 'correct' => [1], 'explanation' => "Un persona est un profil fictif basé sur des données réelles, représentant un segment d'utilisateurs."],
                        ['question' => "Sur quoi un persona doit-il s'appuyer ?", 'options' => ["L'imagination du designer", 'Des données issues de la recherche utilisateur', 'Le budget marketing', 'Les goûts du patron'], 'correct' => [1], 'explanation' => "Un persona crédible repose sur des données réelles collectées pendant la recherche."],
                        ['question' => 'Quels éléments composent un bon persona ? (plusieurs réponses)', 'options' => ['Ses objectifs', 'Ses frustrations', 'Le code source du produit', 'Son contexte et sa profession'], 'correct' => [0, 1, 3], 'explanation' => "Un persona décrit objectifs, frustrations et contexte ; il ne contient pas de code source."],
                    ],
                ],
                [
                    'title' => 'Niveau 4 — Le parcours utilisateur (User Journey)',
                    'subtitle' => "Cartographier chaque étape de l'expérience",
                    'xp_reward' => 130,
                    'content' => "🎯 **Objectif** : visualiser le chemin complet d'un utilisateur pour atteindre son but, et repérer les points de friction.\n\n💡 Le **user journey map** raconte une histoire étape par étape. Il met en lumière les moments où l'utilisateur galère (pain points) afin de les améliorer.\n\n✅ Exemple : envoyer de l'argent par mobile money\n```\n1. Ouvre l'app        😐  cherche le bouton\n2. Saisit le numéro   😟  doute sur le format\n3. Entre le montant   🙂  simple\n4. Valide le code PIN  😣  oublie son code\n5. Confirmation        😀  reçoit un SMS\n```\n\nPour chaque étape on note :\n• L'**action** de l'utilisateur\n• Son **émotion** (content, frustré, anxieux)\n• Les **points de friction**\n• Les **opportunités d'amélioration**\n\n⚠️ Un parcours doit refléter la réalité, pas le scénario idéal. Les abandons (par ex. au paiement) sont précisément les zones à corriger.\n\n📌 Notion clé : le **happy path** est le parcours sans erreur, mais il faut aussi penser aux cas d'échec (numéro invalide, connexion coupée). Concevoir uniquement le happy path est une erreur classique.",
                    'questions' => [
                        ['question' => "À quoi sert une carte de parcours utilisateur (user journey map) ?", 'options' => ['À choisir la police de caractères', "À visualiser les étapes et repérer les points de friction", 'À écrire le code', 'À fixer le prix du produit'], 'correct' => [1], 'explanation' => "Le user journey map cartographie chaque étape pour identifier les frictions et les améliorer."],
                        ['question' => "Que désigne le « happy path » ?", 'options' => ["Le parcours sans erreur où tout se passe bien", 'La page d\'accueil colorée', 'Un test de couleurs', 'Le chemin le plus long'], 'correct' => [0], 'explanation' => "Le happy path est le scénario idéal sans erreur ; il faut aussi prévoir les cas d'échec."],
                        ['question' => "Pourquoi noter les émotions de l'utilisateur à chaque étape ?", 'options' => ['Pour décorer la carte', "Pour repérer où il est frustré et prioriser les améliorations", 'Pour augmenter le prix', "Cela ne sert à rien"], 'correct' => [1], 'explanation' => "Les émotions révèlent les moments de friction où l'expérience doit être améliorée."],
                    ],
                ],
                [
                    'title' => "Niveau 5 — L'architecture de l'information",
                    'subtitle' => 'Organiser le contenu pour que tout soit trouvable',
                    'xp_reward' => 140,
                    'content' => "🎯 **Objectif** : structurer le contenu et la navigation pour que l'utilisateur trouve facilement ce qu'il cherche.\n\n💡 L'**architecture de l'information (IA)** définit où va chaque chose : menus, catégories, hiérarchie des pages. Une bonne IA = l'utilisateur ne se perd jamais.\n\n✅ Concepts clés :\n• **Hiérarchie** : du général au spécifique (Accueil → Catégorie → Produit)\n• **Catégorisation** : regrouper les éléments logiquement\n• **Navigation** : menus, fil d'Ariane (breadcrumb), barre de recherche\n• **Étiquetage (labeling)** : nommer les rubriques avec les mots de l'utilisateur, pas le jargon interne\n\n🔧 Méthode du **tri de cartes (card sorting)** : on donne aux utilisateurs des étiquettes de contenu et on leur demande de les regrouper. On découvre ainsi comment EUX pensent l'organisation.\n\nExemple de structure d'une app d'emploi :\n```\nAccueil\n├── Offres d'emploi\n│   ├── Par ville (Douala, Yaoundé)\n│   └── Par secteur\n├── Mon profil / CV\n└── Mes candidatures\n```\n\n⚠️ Erreur fréquente : organiser selon la structure interne de l'entreprise plutôt que selon la logique de l'utilisateur. Le menu doit parler à l'utilisateur, pas à l'organigramme.",
                    'questions' => [
                        ['question' => "Que définit l'architecture de l'information ?", 'options' => ['Les couleurs du site', "L'organisation et la hiérarchie du contenu et de la navigation", 'La vitesse du serveur', 'Le logo'], 'correct' => [1], 'explanation' => "L'architecture de l'information structure le contenu et la navigation pour le rendre trouvable."],
                        ['question' => 'À quoi sert la technique du tri de cartes (card sorting) ?', 'options' => ['À choisir les images', "À découvrir comment les utilisateurs regroupent et organisent le contenu", 'À tester les couleurs', 'À mesurer la vitesse'], 'correct' => [1], 'explanation' => "Le card sorting révèle la logique d'organisation naturelle des utilisateurs."],
                        ['question' => "Comment nommer les rubriques d'un menu ?", 'options' => ["Avec le jargon interne de l'entreprise", "Avec les mots que les utilisateurs comprennent", 'Avec des abréviations techniques', 'Avec des codes numériques'], 'correct' => [1], 'explanation' => "L'étiquetage doit utiliser le vocabulaire de l'utilisateur, pas le jargon interne."],
                    ],
                ],
                [
                    'title' => 'Niveau 6 — Wireframes : le squelette des écrans',
                    'subtitle' => "Dessiner la structure avant le design",
                    'xp_reward' => 150,
                    'content' => "🎯 **Objectif** : créer des wireframes, des schémas simples en niveaux de gris qui posent la structure des écrans sans se soucier du visuel.\n\n💡 Un **wireframe** est comme le plan d'un bâtiment : il montre où vont les murs (zones) avant de choisir la peinture (couleurs). On utilise des rectangles, des lignes et des textes gris.\n\n✅ Niveaux de fidélité :\n• **Low-fidelity (basse fidélité)** : croquis rapide papier ou rectangles gris. Idéal pour explorer des idées vite.\n• **High-fidelity (haute fidélité)** : plus détaillé, proche du rendu final, mais toujours sans le style définitif.\n\nReprésentation typique :\n```\n+---------------------------+\n|   [ LOGO ]        [Menu]  |\n+---------------------------+\n|   Titre de la page        |\n|   [ image grise ]         |\n|   texte texte texte       |\n|   [    BOUTON ACTION    ] |\n+---------------------------+\n```\n\n⚠️ Pourquoi ne pas mettre de couleurs tout de suite ? Parce que les couleurs distraient. En gris, les retours portent sur la **structure et le contenu**, pas sur « j'aime pas le bleu ».\n\n📌 Le wireframe est rapide et jetable : il sert à valider l'organisation avant d'investir du temps dans les maquettes détaillées.",
                    'questions' => [
                        ['question' => "Qu'est-ce qu'un wireframe ?", 'options' => ['Un schéma simple de la structure d\'un écran, sans style visuel', 'La maquette finale colorée', 'Le code de la page', 'Un persona'], 'correct' => [0], 'explanation' => "Le wireframe est un schéma en niveaux de gris qui définit la structure avant le design visuel."],
                        ['question' => 'Pourquoi les wireframes sont-ils souvent en niveaux de gris ?', 'options' => ['Pour économiser de l\'encre', "Pour concentrer les retours sur la structure plutôt que sur les couleurs", "Parce que les couleurs sont interdites", 'Pour aller plus vite uniquement'], 'correct' => [1], 'explanation' => "L'absence de couleur fait porter les retours sur la structure et le contenu, pas sur l'esthétique."],
                        ['question' => 'Un wireframe basse fidélité (low-fi) sert surtout à...', 'options' => ['Livrer le produit final', "Explorer rapidement des idées de structure", 'Écrire le code de production', 'Fixer la palette de couleurs'], 'correct' => [1], 'explanation' => "Le low-fi permet d'explorer vite plusieurs structures sans investir dans les détails."],
                    ],
                ],
                [
                    'title' => 'Niveau 7 — Maquettes et prototypes interactifs',
                    'subtitle' => 'Du visuel détaillé à la simulation cliquable',
                    'xp_reward' => 160,
                    'content' => "🎯 **Objectif** : transformer les wireframes en maquettes (mockups) détaillées puis en prototypes cliquables pour simuler le produit réel.\n\n💡 Distinction essentielle :\n• **Mockup (maquette)** = écran statique, mais avec les vraies couleurs, images et textes. C'est la photo finale d'un écran.\n• **Prototype** = des maquettes reliées entre elles, cliquables, qui simulent la navigation. C'est le « film » du produit.\n\n✅ À quoi sert un prototype :\n• Tester le parcours **avant** de coder (économise énormément de temps et d'argent)\n• Montrer le concept à un client ou un investisseur\n• Réaliser des tests utilisateurs réalistes\n\n🔧 Dans Figma, on relie un bouton d'un écran à un autre écran avec une interaction « On click → Navigate to ». On peut ajouter des transitions (slide, fade).\n\n⚠️ Un prototype ne contient pas de vrai code ni de vraie base de données : c'est une **illusion contrôlée**. Si on clique en dehors des zones prévues, rien ne se passe.\n\n📌 Ordre de travail recommandé :\nwireframe → mockup → prototype → test. Sauter des étapes coûte cher en corrections plus tard.",
                    'questions' => [
                        ['question' => 'Quelle est la différence entre un mockup et un prototype ?', 'options' => ['Aucune, ce sont des synonymes', "Le mockup est statique avec le visuel final ; le prototype est cliquable et simule la navigation", "Le prototype n'a pas de couleurs", 'Le mockup contient du code'], 'correct' => [1], 'explanation' => "Le mockup est un écran statique finalisé ; le prototype relie les écrans pour simuler l'usage."],
                        ['question' => "Pourquoi créer un prototype avant de coder ?", 'options' => ['Pour rallonger le projet', "Pour tester et valider le parcours à moindre coût avant le développement", 'Pour remplacer le développeur', "Ce n'est jamais utile"], 'correct' => [1], 'explanation' => "Le prototype permet de tester le produit avant de coder, ce qui évite des corrections coûteuses."],
                        ['question' => "Un prototype Figma contient-il une vraie base de données ?", 'options' => ['Oui, toujours', "Non, c'est une simulation sans code ni base réelle", 'Oui, mais cachée', 'Seulement en haute fidélité'], 'correct' => [1], 'explanation' => "Un prototype simule l'expérience sans véritable code ni base de données."],
                    ],
                ],
                [
                    'title' => 'Niveau 8 — Principes de design visuel & couleur',
                    'subtitle' => 'Hiérarchie, contraste, typographie, espacement',
                    'xp_reward' => 170,
                    'content' => "🎯 **Objectif** : maîtriser les principes qui rendent une interface claire, agréable et professionnelle.\n\n💡 Les fondamentaux du design visuel :\n• **Hiérarchie visuelle** : guider l'œil vers ce qui compte (titre gros, action principale mise en avant)\n• **Contraste** : différencier les éléments (texte foncé sur fond clair)\n• **Alignement** : aligner les éléments crée de l'ordre\n• **Proximité** : regrouper ce qui va ensemble\n• **Espace blanc (white space)** : laisser respirer, ne pas tout entasser\n• **Répétition / cohérence** : mêmes styles répétés = sentiment d'unité\n\n🎨 La couleur :\n• Couleur **primaire** (la marque), **secondaire**, et couleurs neutres (gris)\n• Couleurs sémantiques : vert = succès, rouge = erreur, orange = avertissement\n• Règle 60-30-10 : 60% couleur dominante, 30% secondaire, 10% accent\n\n✍️ Typographie :\n• Limiter à 1 ou 2 polices\n• Tailles cohérentes (titre, sous-titre, corps)\n• Hauteur de ligne suffisante pour la lisibilité\n\n⚠️ Erreur fréquente : trop de couleurs et de polices = chaos visuel. La contrainte rend le design plus pro. « Less is more ».",
                    'questions' => [
                        ['question' => "À quoi sert la hiérarchie visuelle ?", 'options' => ['À utiliser beaucoup de couleurs', "À guider l'œil de l'utilisateur vers les éléments les plus importants", 'À cacher les boutons', 'À ralentir la lecture'], 'correct' => [1], 'explanation' => "La hiérarchie visuelle dirige l'attention vers ce qui compte le plus à l'écran."],
                        ['question' => 'Combien de polices différentes vaut-il mieux utiliser dans une interface ?', 'options' => ['Au moins 5', '1 ou 2', '10', 'Aucune'], 'correct' => [1], 'explanation' => "Se limiter à une ou deux polices garde l'interface cohérente et professionnelle."],
                        ['question' => 'Quels principes améliorent la clarté visuelle ? (plusieurs réponses)', 'options' => ["L'espace blanc", 'Le contraste', "L'entassement maximal des éléments", "L'alignement"], 'correct' => [0, 1, 3], 'explanation' => "Espace blanc, contraste et alignement clarifient ; entasser les éléments nuit à la lisibilité."],
                    ],
                ],
                [
                    'title' => 'Niveau 9 — Design system & accessibilité',
                    'subtitle' => 'Cohérence à grande échelle et design pour tous',
                    'xp_reward' => 185,
                    'content' => "🎯 **Objectif** : construire un design system réutilisable et concevoir des interfaces accessibles à tous, y compris les personnes en situation de handicap.\n\n💡 Un **design system** est une bibliothèque de composants réutilisables et de règles : couleurs, typographies, boutons, champs de formulaire, icônes. Il garantit la cohérence et accélère le travail.\n\n✅ Contenu d'un design system :\n• **Tokens** : valeurs de base (couleurs, espacements, tailles)\n• **Composants** : boutons, cartes, champs, modales\n• **Règles d'usage** : quand utiliser quoi\n\nDans Figma, on crée des **composants** (Component) réutilisables et des **variantes** (Variants) pour les états (normal, survol, désactivé).\n\n♿ **Accessibilité (a11y)** : permettre à TOUS d'utiliser le produit.\n• **Contraste suffisant** texte/fond (ratio recommandé 4.5:1 pour le texte normal)\n• Ne pas transmettre l'information par la **couleur seule** (un daltonien ne distingue pas rouge/vert : ajouter une icône ou un texte)\n• **Zones cliquables** assez grandes pour le doigt (au moins ~44px)\n• **Textes alternatifs** sur les images pour les lecteurs d'écran\n• Tailles de police lisibles\n\n⚠️ L'accessibilité n'est pas une option : elle élargit l'audience et est souvent une obligation. Un design accessible profite aussi à tout le monde (ex : bon contraste en plein soleil).",
                    'questions' => [
                        ['question' => "Qu'est-ce qu'un design system ?", 'options' => ['Un seul écran', "Une bibliothèque de composants et de règles réutilisables pour la cohérence", 'Un test utilisateur', "Le nom d'un logiciel"], 'correct' => [1], 'explanation' => "Un design system regroupe composants et règles réutilisables pour assurer la cohérence."],
                        ['question' => "Pourquoi ne pas transmettre une information uniquement par la couleur ?", 'options' => ['Parce que les couleurs coûtent cher', "Parce que les personnes daltoniennes ne distingueraient pas l'information", 'Parce que la couleur est interdite', "Ce n'est pas un problème"], 'correct' => [1], 'explanation' => "Reposer sur la couleur seule exclut les personnes daltoniennes : il faut un repère complémentaire."],
                        ['question' => "Quelles pratiques améliorent l'accessibilité ? (plusieurs réponses)", 'options' => ['Un contraste suffisant texte/fond', 'Des zones cliquables assez grandes', 'Un texte minuscule pour gagner de la place', 'Des textes alternatifs sur les images'], 'correct' => [0, 1, 3], 'explanation' => "Contraste, grandes zones cliquables et textes alternatifs renforcent l'accessibilité ; un texte minuscule la dégrade."],
                    ],
                ],
                [
                    'title' => 'Niveau 10 — Tests utilisateurs, Figma & métier',
                    'subtitle' => 'Valider, itérer et lancer ta carrière',
                    'xp_reward' => 200,
                    'content' => "🎯 **Objectif** : valider tes designs par des tests utilisateurs, maîtriser Figma comme outil principal, et te projeter dans le métier.\n\n💡 **Tests utilisateurs** : on observe de vraies personnes utiliser le prototype pour repérer les problèmes.\n• Donner des **tâches** (« essaie d'envoyer 5000 FCFA à un contact »), pas des indices\n• **Observer en silence**, ne pas guider\n• Noter où l'utilisateur hésite ou se trompe\n• 5 utilisateurs suffisent souvent à révéler la majorité des problèmes\n\n📌 Le design est **itératif** : concevoir → tester → corriger → re-tester. On n'a jamais raison du premier coup.\n\n🔧 **Figma**, l'outil n°1 du métier :\n• Collaboratif et en ligne (idéal pour les équipes à distance)\n• Frames (écrans), composants, auto-layout, prototypage, partage par lien\n• Gratuit pour démarrer\n\n💼 **Débouchés** : UX Designer, UI Designer, Product Designer, UX Researcher, Design System Manager. Au Cameroun et à l'international, la demande croît avec la digitalisation (fintech, e-commerce, mobile money). Constitue un **portfolio** solide : 2 à 3 études de cas qui racontent ton processus (problème → recherche → solution → résultat) valent plus que mille jolis écrans.\n\n🏆 **Félicitations !** Tu as parcouru tout le processus UX/UI : de la compréhension du besoin à la livraison testée. Tu possèdes désormais les bases pour concevoir des produits utiles, utilisables et beaux. Construis ton portfolio, pratique sur Figma, et lance-toi : le marché du design a besoin de toi !",
                    'questions' => [
                        ['question' => "Comment bien mener un test utilisateur ?", 'options' => ["Donner des indices pour aider l'utilisateur à réussir", "Donner une tâche puis observer en silence sans guider", 'Tester soi-même son propre design', 'Demander seulement si le design est joli'], 'correct' => [1], 'explanation' => "On confie une tâche et on observe sans guider, afin de révéler les vrais problèmes d'usage."],
                        ['question' => "Pourquoi dit-on que le design est itératif ?", 'options' => ['Parce qu\'on le fait une seule fois', "Parce qu'on conçoit, teste, corrige et recommence en boucle", "Parce qu'il faut beaucoup de couleurs", 'Parce que les tests sont inutiles'], 'correct' => [1], 'explanation' => "Le design avance par cycles de conception, test et correction successifs."],
                        ['question' => 'Pourquoi Figma est-il très utilisé par les UX/UI designers ?', 'options' => ['Parce qu\'il code automatiquement les apps', "Parce qu'il est collaboratif, en ligne, et permet design et prototypage", "Parce qu'il remplace les utilisateurs", "Parce qu'il est payant et exclusif"], 'correct' => [1], 'explanation' => "Figma réunit design, prototypage et collaboration en ligne, ce qui en fait l'outil de référence."],
                    ],
                ],
            ],
        ]);

        $this->command->info('  ✓ Roadmap UX/UI Design créée (10 niveaux).');
    }
}
