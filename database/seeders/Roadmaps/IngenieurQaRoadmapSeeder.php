<?php

namespace Database\Seeders\Roadmaps;

use Illuminate\Database\Seeder;

/**
 * Roadmap Ingénieur QA / Testeur Logiciel — apprends à garantir la qualité des applications, du test manuel à l'automatisation.
 */
class IngenieurQaRoadmapSeeder extends Seeder
{
    use RoadmapSeederHelper;

    public function run(): void
    {
        $this->createRoadmap([
            'title' => 'Deviens Ingénieur QA / Testeur Logiciel',
            'slug' => 'ingenieur-qa-test-logiciel',
            'domain' => 'developpement',
            'description' => "Apprends le métier de l'assurance qualité logicielle (QA) : comprendre le rôle du testeur, écrire des cas et plans de test, signaler des bugs de façon professionnelle, distinguer tests manuels et automatisés, et découvrir l'automatisation avec Selenium et l'intégration continue. Un parcours concret pensé pour le marché tech d'Afrique francophone (Douala, Yaoundé), où la demande en testeurs qualifiés explose.",
            'objectives' => "Comprendre le rôle de la QA dans un projet logiciel\nDistinguer les types de tests : unitaire, intégration, end-to-end\nÉcrire des cas de test et un plan de test clairs\nSignaler et gérer les bugs avec un outil de suivi\nDifférencier tests manuels et tests automatisés\nDécouvrir l'automatisation avec Selenium\nIntégrer les tests dans une chaîne CI/CD\nMesurer la qualité avec des métriques pertinentes",
            'icon' => '🐞',
            'color' => '#16A34A',
            'difficulty' => 'intermediate',
            'required_packs' => [],
            'pass_threshold' => 70,
            'order' => 20,
            'levels' => [
                [
                    'title' => 'Niveau 1 — Le rôle de la QA',
                    'subtitle' => 'Pourquoi tester un logiciel ?',
                    'xp_reward' => 100,
                    'content' => "🎯 **Objectif** : comprendre ce que fait un ingénieur QA et pourquoi la qualité est vitale.\n\n💡 La QA (Quality Assurance) consiste à **prévenir** les défauts, pas seulement à les trouver. Le testeur vérifie qu'un logiciel répond aux besoins, fonctionne sans planter et offre une bonne expérience.\n\n• **Qualité** : conformité aux exigences + satisfaction de l'utilisateur\n• **Bug / défaut** : comportement différent de l'attendu\n• **QA ≠ QC** : la QA est un processus (prévention), le QC (Quality Control) est l'inspection du produit fini\n\n⚠️ Exemple concret : une application de paiement mobile money à Douala. Si un transfert affiche « réussi » mais ne crédite pas le compte, c'est un bug critique. Le testeur l'aurait détecté avant la mise en production, évitant des pertes d'argent et de confiance.\n\n✅ Le testeur n'est pas l'ennemi du développeur : ensemble ils livrent un produit fiable. Sa valeur ? Réduire les coûts : un bug trouvé tard coûte jusqu'à 100 fois plus cher qu'un bug trouvé tôt.",
                    'questions' => [
                        ['question' => 'Quel est l\'objectif principal de la QA ?', 'options' => ['Coder les nouvelles fonctionnalités', 'Prévenir et détecter les défauts pour garantir la qualité', 'Vendre le logiciel aux clients', 'Gérer les serveurs de production'], 'correct' => [1], 'explanation' => 'La QA vise à assurer la qualité en prévenant et détectant les défauts avant la livraison.'],
                        ['question' => 'Qu\'est-ce qu\'un bug (défaut) ?', 'options' => ['Une nouvelle fonctionnalité demandée', 'Un comportement conforme aux attentes', 'Un écart entre le comportement attendu et le comportement réel', 'Un commentaire dans le code'], 'correct' => [2], 'explanation' => 'Un bug est un écart entre ce que le logiciel devrait faire et ce qu\'il fait réellement.'],
                        ['question' => 'Pourquoi trouver un bug tôt est-il avantageux ?', 'options' => ['Cela coûte beaucoup moins cher à corriger', 'Cela ralentit toujours le projet', 'Cela n\'a aucun impact sur le coût', 'Cela oblige à recommencer le projet'], 'correct' => [0], 'explanation' => 'Plus un bug est détecté tôt, moins sa correction est coûteuse en temps et en argent.'],
                    ],
                ],
                [
                    'title' => 'Niveau 2 — Le cycle de vie et les niveaux de test',
                    'subtitle' => 'Quand et où teste-t-on ?',
                    'xp_reward' => 110,
                    'content' => "🎯 **Objectif** : situer les tests dans le cycle de développement (SDLC) et connaître les grands niveaux de test.\n\n💡 Les tests suivent une pyramide. On distingue plusieurs **niveaux** :\n\n• **Test unitaire** : vérifie une seule unité de code (une fonction). Rapide, isolé.\n• **Test d'intégration** : vérifie que plusieurs modules communiquent bien (ex : module commande + module paiement).\n• **Test système** : vérifie l'application complète.\n• **Test d'acceptation (UAT)** : validation par l'utilisateur final / le client.\n\n⚠️ La **pyramide des tests** : beaucoup de tests unitaires (base, rapides et pas chers), moins d'intégration, encore moins de tests end-to-end (lents, coûteux) au sommet.\n\n```\n        /\\   E2E (peu)\n       /--\\  Intégration\n      /----\\ Unitaires (beaucoup)\n```\n\n✅ Modèle en V : à chaque phase de conception correspond une phase de test. Spécifications → tests d'acceptation ; conception → tests système ; etc.",
                    'questions' => [
                        ['question' => 'Que vérifie un test unitaire ?', 'options' => ['L\'application entière de bout en bout', 'Une seule unité de code isolée, comme une fonction', 'La communication entre tous les serveurs', 'La satisfaction du client final'], 'correct' => [1], 'explanation' => 'Le test unitaire valide une unité isolée de code, typiquement une fonction ou une méthode.'],
                        ['question' => 'Que vérifie un test d\'intégration ?', 'options' => ['L\'orthographe du code', 'Une fonction seule sans dépendances', 'Que plusieurs modules communiquent correctement ensemble', 'Le design des couleurs'], 'correct' => [2], 'explanation' => 'Le test d\'intégration vérifie les interactions et échanges entre plusieurs modules.'],
                        ['question' => 'Selon la pyramide des tests, quels tests doit-on avoir en plus grand nombre ?', 'options' => ['Les tests end-to-end (E2E)', 'Les tests manuels exploratoires', 'Les tests unitaires', 'Les tests d\'acceptation'], 'correct' => [2], 'explanation' => 'La base de la pyramide est constituée de nombreux tests unitaires, rapides et peu coûteux.'],
                    ],
                ],
                [
                    'title' => 'Niveau 3 — Tests manuels vs automatisés',
                    'subtitle' => 'Choisir la bonne approche',
                    'xp_reward' => 120,
                    'content' => "🎯 **Objectif** : savoir quand tester à la main et quand automatiser.\n\n💡 Le **test manuel** : un humain exécute les scénarios à la main, observe et juge. Idéal pour l'exploration, l'ergonomie (UX) et les nouveaux écrans. Le **test automatisé** : un script exécute les scénarios. Idéal pour les tests répétitifs et les régressions.\n\n| Critère | Manuel | Automatisé |\n|---|---|---|\n| Mise en place | Rapide | Lente (écriture de scripts) |\n| Répétition | Coûteuse | Quasi gratuite |\n| Exploration / UX | Excellent | Faible |\n| Régression | Lourd | Idéal |\n\n• **Test exploratoire** (manuel) : le testeur navigue librement pour découvrir des bugs inattendus.\n• **Test de régression** (souvent automatisé) : on revérifie qu'une correction n'a rien cassé.\n\n⚠️ Erreur fréquente : vouloir tout automatiser. On automatise ce qui est **stable et répété**. Une interface qui change tous les jours se teste d'abord manuellement.\n\n✅ Règle pratique : manuel pour explorer et valider l'UX, automatisé pour les régressions et les vérifications répétitives.",
                    'questions' => [
                        ['question' => 'Pour quel type de test l\'automatisation est-elle la plus rentable ?', 'options' => ['Les tests d\'ergonomie (UX) subjectifs', 'Les tests de régression répétés à chaque livraison', 'Un test exécuté une seule fois sur un écran instable', 'L\'exploration libre de l\'application'], 'correct' => [1], 'explanation' => 'L\'automatisation excelle sur les tests répétitifs comme les régressions, exécutés souvent.'],
                        ['question' => 'Qu\'est-ce qu\'un test exploratoire ?', 'options' => ['Un script qui s\'exécute automatiquement', 'Le testeur navigue librement pour découvrir des bugs', 'Un test qui vérifie uniquement la base de données', 'Un test écrit par le client'], 'correct' => [1], 'explanation' => 'Le test exploratoire est une approche manuelle où le testeur explore l\'application sans script figé.'],
                        ['question' => 'Quels énoncés sont vrais ? (plusieurs réponses)', 'options' => ['Le test manuel est idéal pour évaluer l\'ergonomie', 'Tout doit toujours être automatisé', 'Le test automatisé est rentable pour les régressions répétées', 'Un écran qui change chaque jour est un bon candidat à l\'automatisation immédiate'], 'correct' => [0, 2], 'explanation' => 'Le manuel excelle pour l\'UX et l\'automatisé pour les régressions ; on n\'automatise pas tout, surtout pas l\'instable.'],
                    ],
                ],
                [
                    'title' => 'Niveau 4 — Écrire un cas de test',
                    'subtitle' => 'La brique de base du testeur',
                    'xp_reward' => 130,
                    'content' => "🎯 **Objectif** : rédiger un cas de test clair, reproductible et vérifiable.\n\n💡 Un **cas de test** décrit précisément comment vérifier une fonctionnalité. Structure type :\n\n• **ID** : identifiant unique (ex : TC-LOGIN-01)\n• **Titre** : objectif du test\n• **Préconditions** : état requis avant de commencer\n• **Étapes** : actions numérotées\n• **Données de test** : valeurs utilisées\n• **Résultat attendu** : ce qui devrait se passer\n• **Résultat obtenu / Statut** : Réussi / Échoué\n\n**Exemple — Connexion à une appli RH à Yaoundé :**\n```\nID : TC-LOGIN-01\nPrécondition : compte « amina@rh.cm » existant\nÉtapes :\n  1. Ouvrir la page de connexion\n  2. Saisir email + mot de passe valides\n  3. Cliquer sur « Se connecter »\nRésultat attendu : redirection vers le tableau de bord\n```\n\n⚠️ Un bon cas de test est **non ambigu** : un autre testeur doit pouvoir l'exécuter sans poser de question. On teste aussi les cas négatifs (mot de passe faux → message d'erreur).\n\n✅ Pensez aux cas limites : champs vides, valeurs extrêmes, caractères spéciaux.",
                    'questions' => [
                        ['question' => 'Quel élément est indispensable dans un cas de test ?', 'options' => ['Le salaire du développeur', 'Le résultat attendu', 'La couleur du bouton uniquement', 'Le nom du serveur de production'], 'correct' => [1], 'explanation' => 'Sans résultat attendu, impossible de juger si le test réussit ou échoue.'],
                        ['question' => 'Pourquoi un cas de test doit-il être non ambigu ?', 'options' => ['Pour qu\'il soit reproductible par n\'importe quel testeur', 'Pour impressionner le client', 'Parce que la loi l\'exige', 'Pour le rendre plus long'], 'correct' => [0], 'explanation' => 'Un cas de test clair garantit que toute personne peut l\'exécuter de la même manière et obtenir le même verdict.'],
                        ['question' => 'Qu\'est-ce qu\'un cas de test négatif ?', 'options' => ['Un test qui réussit toujours', 'Un test qui vérifie le bon comportement face à une mauvaise entrée', 'Un test sans étapes', 'Un test fait de mauvaise humeur'], 'correct' => [1], 'explanation' => 'Le cas négatif vérifie que le système réagit correctement à des données invalides (ex : message d\'erreur).'],
                    ],
                ],
                [
                    'title' => 'Niveau 5 — Le plan de test',
                    'subtitle' => 'Organiser l\'effort de test',
                    'xp_reward' => 140,
                    'content' => "🎯 **Objectif** : construire un plan de test qui cadre toute la campagne.\n\n💡 Le **plan de test** est le document stratégique qui répond à : quoi tester, comment, par qui, avec quelles ressources et selon quels critères de succès.\n\nSections clés :\n• **Périmètre** : ce qui est testé… et ce qui ne l'est PAS (hors-périmètre)\n• **Stratégie** : niveaux et types de tests retenus\n• **Critères d'entrée** : conditions pour démarrer (ex : build déployé)\n• **Critères de sortie** : conditions pour s'arrêter (ex : 0 bug critique, 95 % des cas passés)\n• **Ressources** : équipe, environnements, outils\n• **Risques & planning**\n\n⚠️ Distinguer plan de test (stratégie globale) et **scénario / suite de tests** (regroupement de cas de test). Le plan dit « pourquoi et dans quel cadre », les cas disent « comment exactement ».\n\n✅ Exemple : pour une marketplace camerounaise, le périmètre inclut « passage de commande et paiement mobile money », exclut « module d'export comptable prévu plus tard ». Critère de sortie : aucun bug bloquant sur le paiement.",
                    'questions' => [
                        ['question' => 'À quoi sert le périmètre (scope) dans un plan de test ?', 'options' => ['À fixer le prix du logiciel', 'À définir ce qui sera testé et ce qui ne le sera pas', 'À écrire le code source', 'À choisir la couleur de l\'interface'], 'correct' => [1], 'explanation' => 'Le périmètre délimite clairement ce qui entre et sort du champ des tests.'],
                        ['question' => 'Que sont les critères de sortie d\'un plan de test ?', 'options' => ['Les conditions pour considérer les tests terminés', 'La porte de l\'immeuble', 'Le moment de la pause déjeuner', 'Les bugs créés volontairement'], 'correct' => [0], 'explanation' => 'Les critères de sortie définissent quand arrêter de tester (ex : 0 bug critique, taux de réussite cible atteint).'],
                        ['question' => 'Quelle est la différence entre un plan de test et un cas de test ?', 'options' => ['Aucune, ce sont des synonymes', 'Le plan définit la stratégie globale, le cas décrit une vérification précise', 'Le cas est plus stratégique que le plan', 'Le plan ne contient que du code'], 'correct' => [1], 'explanation' => 'Le plan de test cadre l\'ensemble de la campagne ; le cas de test détaille une vérification unitaire précise.'],
                    ],
                ],
                [
                    'title' => 'Niveau 6 — La gestion des bugs',
                    'subtitle' => 'Signaler un défaut comme un pro',
                    'xp_reward' => 150,
                    'content' => "🎯 **Objectif** : rédiger un rapport de bug efficace et comprendre son cycle de vie.\n\n💡 Un bon **rapport de bug** doit permettre au développeur de reproduire le problème sans vous appeler. Éléments :\n\n• **Titre clair** : « Le paiement échoue avec un montant à virgule »\n• **Étapes pour reproduire** (1, 2, 3…)\n• **Résultat attendu** vs **Résultat obtenu**\n• **Environnement** : navigateur, OS, version de l'app\n• **Sévérité** (impact technique : bloquant → mineur) et **Priorité** (urgence business)\n• **Preuve** : capture d'écran, log, vidéo\n\n**Cycle de vie d'un bug :**\n```\nNouveau → Assigné → En cours → Corrigé → \n  → Re-testé → Fermé (ou Réouvert si non corrigé)\n```\n\n⚠️ Sévérité ≠ Priorité. Un logo mal affiché sur la page d'accueil peut être peu sévère techniquement mais très prioritaire (image de marque). À l'inverse un crash sur un écran rarement utilisé est très sévère mais peu prioritaire.\n\n✅ Outils courants : Jira, Trello, GitHub Issues, Mantis.",
                    'questions' => [
                        ['question' => 'Quel élément rend un rapport de bug exploitable par le développeur ?', 'options' => ['Une humeur joyeuse', 'Des étapes claires pour reproduire le problème', 'Le numéro de téléphone du testeur', 'La date d\'anniversaire du chef de projet'], 'correct' => [1], 'explanation' => 'Des étapes de reproduction précises permettent au développeur de retrouver et corriger le bug.'],
                        ['question' => 'Quelle est la différence entre sévérité et priorité ?', 'options' => ['Ce sont la même chose', 'La sévérité mesure l\'impact technique, la priorité mesure l\'urgence business', 'La priorité concerne uniquement le code', 'La sévérité dépend de la couleur du bug'], 'correct' => [1], 'explanation' => 'La sévérité reflète l\'impact technique du défaut, tandis que la priorité indique l\'urgence de le corriger pour le business.'],
                        ['question' => 'Que se passe-t-il si un bug marqué « Corrigé » échoue au re-test ?', 'options' => ['On le supprime définitivement', 'Il est réouvert (statut Réouvert)', 'Il devient une fonctionnalité', 'On change de développeur automatiquement'], 'correct' => [1], 'explanation' => 'Si le re-test échoue, le bug est réouvert pour être de nouveau corrigé.'],
                    ],
                ],
                [
                    'title' => 'Niveau 7 — Tests fonctionnels et techniques de conception',
                    'subtitle' => 'Tester intelligemment, pas exhaustivement',
                    'xp_reward' => 160,
                    'content' => "🎯 **Objectif** : appliquer des techniques pour couvrir un maximum de cas avec un minimum de tests.\n\n💡 Les **tests fonctionnels** vérifient CE que fait le logiciel (les fonctionnalités) selon les exigences, sans connaître le code interne (boîte noire). Tester toutes les combinaisons est impossible : on utilise des techniques de conception.\n\n• **Partitions d'équivalence** : regrouper les entrées qui se comportent pareil. Pour un âge accepté 18–60, tester une valeur dans [18-60] suffit pour la classe « valide ».\n• **Analyse des valeurs limites** : tester les bornes 17, 18, 60, 61 (là où les bugs se cachent).\n• **Table de décision** : combinaisons de conditions → actions attendues.\n\n⚠️ Boîte noire (sans voir le code) vs boîte blanche (avec accès au code, ex : couverture de branches). Le testeur fonctionnel travaille surtout en boîte noire.\n\n**Exemple — frais de livraison à Douala :**\n```\nMontant < 10 000 FCFA → frais 1 500\nMontant >= 10 000 FCFA → frais 0\n```\nValeurs limites à tester : 9 999, 10 000, 10 001.\n\n✅ Ces techniques réduisent drastiquement le nombre de tests tout en gardant une forte couverture.",
                    'questions' => [
                        ['question' => 'Que vérifie un test fonctionnel (boîte noire) ?', 'options' => ['La structure interne du code ligne par ligne', 'Ce que fait le logiciel selon les exigences, sans regarder le code', 'La vitesse du processeur', 'Le style d\'indentation du code'], 'correct' => [1], 'explanation' => 'Le test fonctionnel en boîte noire valide le comportement attendu sans connaître l\'implémentation interne.'],
                        ['question' => 'À quoi sert l\'analyse des valeurs limites ?', 'options' => ['À tester les bornes des plages de valeurs où les bugs sont fréquents', 'À limiter le nombre de testeurs', 'À fixer le budget du projet', 'À supprimer les tests inutiles du dépôt'], 'correct' => [0], 'explanation' => 'Les bugs apparaissent souvent aux frontières des plages ; on teste donc les valeurs juste avant, sur et juste après la limite.'],
                        ['question' => 'Quel est l\'intérêt des partitions d\'équivalence ?', 'options' => ['Tester chaque valeur possible une par une', 'Regrouper les entrées au comportement identique pour réduire le nombre de tests', 'Augmenter inutilement le nombre de cas', 'Coder plus vite'], 'correct' => [1], 'explanation' => 'Les partitions d\'équivalence regroupent les entrées équivalentes, réduisant le nombre de tests sans perdre en couverture.'],
                    ],
                ],
                [
                    'title' => 'Niveau 8 — Introduction à l\'automatisation (Selenium)',
                    'subtitle' => 'Faire piloter le navigateur par un script',
                    'xp_reward' => 170,
                    'content' => "🎯 **Objectif** : comprendre les bases de l'automatisation web avec Selenium.\n\n💡 **Selenium WebDriver** pilote un vrai navigateur (Chrome, Firefox) par programme. On localise des éléments puis on agit dessus.\n\n• **Localisateurs (locators)** : id, name, class, css selector, **XPath**\n• **Actions** : `click()`, `sendKeys()`, `getText()`\n• **Assertion** : on compare le résultat obtenu à l'attendu\n\n**Exemple (Python) — tester une connexion :**\n```python\nfrom selenium import webdriver\nfrom selenium.webdriver.common.by import By\n\ndriver = webdriver.Chrome()\ndriver.get(\"https://app.emploi.cm/login\")\ndriver.find_element(By.ID, \"email\").send_keys(\"amina@rh.cm\")\ndriver.find_element(By.ID, \"password\").send_keys(\"secret123\")\ndriver.find_element(By.ID, \"btn-login\").click()\nassert \"Tableau de bord\" in driver.title\ndriver.quit()\n```\n\n⚠️ Pièges : les **attentes**. Une page peut être lente ; il faut des attentes explicites (`WebDriverWait`) plutôt que des `sleep()` fixes, sources de tests instables (« flaky »).\n\n✅ Bonne pratique : le modèle **Page Object** isole les sélecteurs dans des classes dédiées, rendant les tests plus lisibles et maintenables.",
                    'questions' => [
                        ['question' => 'Que fait Selenium WebDriver ?', 'options' => ['Il compile le code source', 'Il pilote automatiquement un vrai navigateur pour tester une appli web', 'Il dessine les maquettes', 'Il héberge le site en production'], 'correct' => [1], 'explanation' => 'Selenium WebDriver automatise les interactions avec un navigateur réel pour exécuter des tests web.'],
                        ['question' => 'Pourquoi préférer une attente explicite (WebDriverWait) à un sleep() fixe ?', 'options' => ['Pour rendre les tests plus lents volontairement', 'Pour éviter les tests instables (flaky) en attendant que l\'élément soit prêt', 'Parce que sleep() est interdit en Python', 'Pour augmenter la consommation mémoire'], 'correct' => [1], 'explanation' => 'L\'attente explicite attend la disponibilité réelle de l\'élément, évitant les échecs aléatoires dus aux pages lentes.'],
                        ['question' => 'À quoi sert le modèle Page Object ?', 'options' => ['À isoler les sélecteurs et actions d\'une page dans une classe dédiée pour la maintenabilité', 'À supprimer les pages du site', 'À chiffrer les mots de passe', 'À accélérer le serveur'], 'correct' => [0], 'explanation' => 'Le Page Object centralise les éléments d\'une page, rendant les tests plus lisibles et faciles à maintenir.'],
                    ],
                ],
                [
                    'title' => 'Niveau 9 — Tests dans la CI/CD',
                    'subtitle' => 'Automatiser l\'exécution à chaque changement',
                    'xp_reward' => 185,
                    'content' => "🎯 **Objectif** : intégrer les tests dans une chaîne d'intégration continue (CI).\n\n💡 La **CI (Intégration Continue)** exécute automatiquement les tests à chaque `push` de code. Si un test échoue, l'équipe est alertée immédiatement et la livraison est bloquée. C'est le filet de sécurité de la qualité.\n\n• **Pipeline** : suite d'étapes automatiques (build → tests → déploiement)\n• **CD (Déploiement Continu)** : si tout passe, on déploie automatiquement\n• Outils : GitHub Actions, GitLab CI, Jenkins\n\n**Exemple — étape de test dans GitHub Actions :**\n```yaml\njobs:\n  test:\n    runs-on: ubuntu-latest\n    steps:\n      - uses: actions/checkout@v4\n      - run: npm install\n      - run: npm test\n```\n\n⚠️ Pour rester utile, la suite de tests CI doit être **rapide et fiable**. Des tests lents ou « flaky » poussent l'équipe à les ignorer. On place les tests unitaires en premier (rapides), puis l'intégration, puis l'E2E.\n\n✅ Règle d'or : « le build est rouge → priorité absolue ». Personne ne pousse de nouveau code tant que la chaîne n'est pas réparée (« ne jamais laisser le build cassé »).",
                    'questions' => [
                        ['question' => 'Que déclenche typiquement l\'exécution des tests en intégration continue (CI) ?', 'options' => ['Un push / une modification de code', 'L\'arrivée du week-end', 'Un appel téléphonique du client', 'Le redémarrage du PC du testeur'], 'correct' => [0], 'explanation' => 'La CI lance automatiquement les tests à chaque changement de code poussé sur le dépôt.'],
                        ['question' => 'Que doit-il se passer si un test échoue dans le pipeline CI ?', 'options' => ['On ignore et on déploie quand même', 'L\'équipe est alertée et la livraison est bloquée jusqu\'à correction', 'On supprime le test fautif', 'Le projet est annulé'], 'correct' => [1], 'explanation' => 'Un échec en CI bloque la chaîne et alerte l\'équipe pour corriger avant tout déploiement.'],
                        ['question' => 'Quelles propriétés une bonne suite de tests CI doit-elle avoir ? (plusieurs réponses)', 'options' => ['Être rapide', 'Être fiable (non flaky)', 'Être la plus lente possible', 'Donner des résultats aléatoires'], 'correct' => [0, 1], 'explanation' => 'Une suite CI utile est rapide et fiable ; lenteur et instabilité poussent l\'équipe à l\'ignorer.'],
                    ],
                ],
                [
                    'title' => 'Niveau 10 — Métriques qualité et carrière',
                    'subtitle' => 'Mesurer, améliorer et évoluer',
                    'xp_reward' => 200,
                    'content' => "🎯 **Objectif** : suivre la qualité avec des métriques et préparer ta carrière en QA.\n\n💡 « Ce qui ne se mesure pas ne s'améliore pas. » Métriques clés :\n\n• **Couverture de tests** : % du code/des exigences couverts par des tests\n• **Taux de réussite** : cas passés / cas exécutés\n• **Densité de défauts** : bugs par fonctionnalité ou par millier de lignes\n• **DDP (Defect Detection Percentage)** : bugs trouvés avant prod / total des bugs\n• **Temps moyen de correction** (MTTR)\n• **Taux de fuite** : bugs découverts en production (à minimiser !)\n\n⚠️ Attention : une couverture de 100 % ne garantit PAS l'absence de bugs. La qualité des tests compte plus que leur nombre. Évite la « métrique-vanité » qui flatte sans informer.\n\n📊 Tableau de bord type :\n```\nCouverture : 82 %\nCas réussis : 96 %\nBugs en prod (mois) : 3 (objectif < 5)\n```\n\n✅ **Débouchés** : Testeur manuel → Ingénieur QA → Automaticien (SDET) → Lead QA / QA Manager. Certifications utiles : **ISTQB Foundation**. Compétences recherchées à Douala/Yaoundé : Selenium, API testing (Postman), CI/CD, esprit analytique.\n\n🏆 **Félicitations !** Tu maîtrises désormais les fondamentaux de la QA : rôle, niveaux de test, cas et plans, gestion des bugs, automatisation et métriques. Tu as les bases pour décrocher un poste de testeur junior et bâtir une carrière solide dans la qualité logicielle. La tech africaine a besoin de toi pour livrer des produits fiables. Continue à pratiquer, vise l'ISTQB, et bonne chasse aux bugs !",
                    'questions' => [
                        ['question' => 'Que mesure la couverture de tests ?', 'options' => ['Le salaire du testeur', 'La part du code ou des exigences vérifiée par des tests', 'Le nombre de développeurs', 'La vitesse d\'internet'], 'correct' => [1], 'explanation' => 'La couverture indique quelle proportion du code ou des exigences est effectivement testée.'],
                        ['question' => 'Une couverture de tests de 100 % garantit-elle l\'absence de bugs ?', 'options' => ['Oui, totalement', 'Non, elle ne garantit pas l\'absence de défauts', 'Oui, mais seulement le lundi', 'Cela dépend de la couleur du code'], 'correct' => [1], 'explanation' => 'Une forte couverture réduit le risque mais ne prouve jamais l\'absence totale de bugs ; la qualité des tests prime.'],
                        ['question' => 'Quelle certification reconnue est un bon point de départ pour un testeur ?', 'options' => ['Le permis de conduire', 'ISTQB Foundation Level', 'Un diplôme de cuisine', 'La carte de membre d\'un club'], 'correct' => [1], 'explanation' => 'La certification ISTQB Foundation est la référence d\'entrée dans le métier de testeur logiciel.'],
                    ],
                ],
            ],
        ]);

        $this->command->info('  ✓ Roadmap Ingénieur QA créée (10 niveaux).');
    }
}
