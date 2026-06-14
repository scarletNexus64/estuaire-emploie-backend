<?php

namespace Database\Seeders\Roadmaps;

use Illuminate\Database\Seeder;

/**
 * Roadmap Gestion de Projet — devenir chef de projet, du cadrage à la clôture.
 */
class GestionDeProjetRoadmapSeeder extends Seeder
{
    use RoadmapSeederHelper;

    public function run(): void
    {
        $this->createRoadmap([
            'title' => 'Deviens Chef de Projet (Gestion de Projet)',
            'slug' => 'gestion-de-projet',
            'domain' => 'gestion',
            'description' => "Apprends à piloter un projet du début à la fin : cadrage, planning, budget, risques, parties prenantes et méthodes agiles. Une formation pratique pensée pour le contexte africain (Douala, Yaoundé) avec des exemples concrets que tu peux appliquer dès demain.",
            'objectives' => "Comprendre ce qu'est un projet et son cycle de vie\nRédiger des objectifs SMART et cadrer un projet\nConstruire un planning et un diagramme de Gantt\nDécouper un projet avec une WBS\nIdentifier et gérer les risques\nEstimer un budget et piloter les ressources\nGérer les parties prenantes\nChoisir entre méthode agile et cycle en V\nClôturer un projet et capitaliser",
            'icon' => '📊',
            'color' => '#2563EB',
            'difficulty' => 'intermediate',
            'required_packs' => [],
            'pass_threshold' => 70,
            'order' => 20,
            'levels' => [
                [
                    'title' => 'Niveau 1 — Qu\'est-ce qu\'un projet ?',
                    'subtitle' => 'Comprendre la nature d\'un projet',
                    'xp_reward' => 100,
                    'content' => "🎯 **Objectif** : distinguer un projet d'une activité courante.\n\n💡 Un **projet** est un effort **temporaire** entrepris pour créer un produit, un service ou un résultat **unique**. Il a un **début** et une **fin** clairs.\n\nLes 3 caractéristiques clés :\n• **Temporaire** : il s'arrête une fois l'objectif atteint (≠ une boutique qui tourne tous les jours).\n• **Unique** : le résultat n'a jamais existé exactement ainsi.\n• **Contraint** : il jongle entre 3 contraintes — le **triangle d'or** : ⏱️ délai, 💰 coût, 🎯 qualité/périmètre.\n\n⚠️ Exemple concret : organiser le lancement d'une nouvelle application de mobile money à Douala est un **projet** (date de lancement précise, objectif unique). Traiter les transactions quotidiennes ensuite est une **opération** (activité répétitive).\n\n✅ Retiens : si tu touches à l'un des sommets du triangle (réduire le délai), tu impactes les autres (plus de coût ou moins de qualité). Le chef de projet est le gardien de cet équilibre.",
                    'questions' => [
                        ['question' => 'Quelle est la caractéristique principale qui distingue un projet d\'une opération courante ?', 'options' => ['Il est temporaire et produit un résultat unique', 'Il dure toute l\'année', 'Il ne coûte rien', 'Il ne nécessite aucune équipe'], 'correct' => [0], 'explanation' => 'Un projet est par définition temporaire (début et fin) et vise un résultat unique, contrairement à une opération répétitive.'],
                        ['question' => 'Quels sont les trois sommets du triangle d\'or de la gestion de projet ?', 'options' => ['Délai, coût, qualité/périmètre', 'Client, fournisseur, banque', 'Lundi, mardi, mercredi', 'Bureau, ordinateur, internet'], 'correct' => [0], 'explanation' => 'Le triangle d\'or équilibre le délai, le coût et la qualité (ou périmètre) : modifier l\'un affecte les autres.'],
                        ['question' => 'Parmi ces situations, lesquelles sont des projets ?', 'options' => ['Construire un nouveau site web pour une mairie', 'Servir les clients tous les jours dans un restaurant', 'Organiser un séminaire unique de formation', 'Encaisser les ventes quotidiennes d\'une boutique'], 'correct' => [0, 2], 'explanation' => 'Construire un site et organiser un séminaire unique sont temporaires et uniques ; servir et encaisser au quotidien sont des opérations répétitives.'],
                    ],
                ],
                [
                    'title' => 'Niveau 2 — Le cycle de vie et les phases',
                    'subtitle' => 'Les grandes étapes d\'un projet',
                    'xp_reward' => 110,
                    'content' => "🎯 **Objectif** : connaître les phases par lesquelles passe tout projet.\n\n💡 Le **cycle de vie** d'un projet se découpe classiquement en 5 phases :\n\n• 1️⃣ **Initialisation** : on définit le besoin, on nomme le chef de projet, on valide le lancement.\n• 2️⃣ **Planification** : on planifie le quoi, qui, quand, combien (planning, budget, risques).\n• 3️⃣ **Exécution** : on réalise concrètement les livrables.\n• 4️⃣ **Suivi & contrôle** : on mesure l'avancement et on corrige les écarts (en parallèle de l'exécution).\n• 5️⃣ **Clôture** : on livre, on archive, on tire le bilan.\n\n⚠️ Erreur fréquente : se précipiter sur l'exécution sans planifier. On avance vite… puis on refait tout. \"Bien préparer, c'est à moitié réussir.\"\n\n✅ Exemple : pour digitaliser le service clientèle d'une PME à Yaoundé, on commence par cadrer (initialisation), puis on planifie les sprints, on développe (exécution), on surveille les bugs (suivi), et on forme les équipes avant de clôturer.",
                    'questions' => [
                        ['question' => 'Dans quel ordre se déroulent généralement les phases d\'un projet ?', 'options' => ['Initialisation, planification, exécution, clôture', 'Clôture, exécution, initialisation', 'Exécution, initialisation, planification', 'Budget, repas, vacances'], 'correct' => [0], 'explanation' => 'Le cycle de vie suit la logique : initialisation, planification, exécution (avec suivi), puis clôture.'],
                        ['question' => 'À quelle phase nomme-t-on officiellement le chef de projet et valide-t-on le lancement ?', 'options' => ['L\'initialisation', 'La clôture', 'L\'exécution', 'L\'archivage'], 'correct' => [0], 'explanation' => 'C\'est lors de l\'initialisation qu\'on définit le besoin, qu\'on nomme le chef de projet et qu\'on autorise le démarrage.'],
                        ['question' => 'La phase de suivi et contrôle se déroule principalement…', 'options' => ['En parallèle de l\'exécution pour corriger les écarts', 'Uniquement après la clôture', 'Avant l\'initialisation', 'Seulement le dernier jour'], 'correct' => [0], 'explanation' => 'Le suivi et contrôle accompagne l\'exécution afin de mesurer l\'avancement et corriger les écarts en temps réel.'],
                    ],
                ],
                [
                    'title' => 'Niveau 3 — Cadrage et objectifs SMART',
                    'subtitle' => 'Bien définir ce qu\'on veut atteindre',
                    'xp_reward' => 120,
                    'content' => "🎯 **Objectif** : formuler des objectifs clairs et mesurables.\n\n💡 Un projet flou échoue. La méthode **SMART** structure chaque objectif :\n\n• **S**pécifique : précis, sans ambiguïté.\n• **M**esurable : un chiffre, un indicateur.\n• **A**tteignable : réaliste avec les moyens disponibles.\n• **R**éaliste / pertinent : utile pour l'organisation.\n• **T**emporellement défini : avec une échéance.\n\n⚠️ Mauvais objectif : \"améliorer le service client\". Trop vague.\n\n✅ Bon objectif SMART : \"Réduire le temps moyen de réponse aux clients de 48h à 12h d'ici le 31 décembre, en déployant un chatbot WhatsApp.\"\n\nLe **cadrage** réunit aussi : le **périmètre** (ce qui est inclus / exclu), les **livrables** attendus, les **contraintes** et les **hypothèses**. Tout cela se formalise dans un document de cadrage ou une **note de cadrage** signée par le commanditaire.\n\n📌 Astuce : écris noir sur blanc ce qui est HORS périmètre. C'est ce qui évite les conflits plus tard.",
                    'questions' => [
                        ['question' => 'Que signifie le "M" dans la méthode SMART ?', 'options' => ['Mesurable', 'Moderne', 'Mobile', 'Manuel'], 'correct' => [0], 'explanation' => 'Le M de SMART signifie Mesurable : l\'objectif doit s\'appuyer sur un indicateur chiffré.'],
                        ['question' => 'Lequel de ces objectifs est correctement SMART ?', 'options' => ['Augmenter les ventes de 20% d\'ici juin grâce au mobile money', 'Faire mieux qu\'avant', 'Être les meilleurs', 'Travailler plus'], 'correct' => [0], 'explanation' => 'Seul le premier est spécifique, mesurable (20%) et temporellement défini (juin).'],
                        ['question' => 'Pourquoi est-il important de définir ce qui est HORS périmètre lors du cadrage ?', 'options' => ['Pour éviter les conflits et les dérives plus tard', 'Pour allonger le projet', 'Pour cacher des informations', 'Cela n\'a aucune utilité'], 'correct' => [0], 'explanation' => 'Préciser le hors-périmètre limite les demandes imprévues et les conflits avec le commanditaire.'],
                    ],
                ],
                [
                    'title' => 'Niveau 4 — La WBS et le découpage',
                    'subtitle' => 'Diviser pour mieux régner',
                    'xp_reward' => 130,
                    'content' => "🎯 **Objectif** : découper un projet en éléments gérables.\n\n💡 La **WBS** (Work Breakdown Structure), ou **organigramme des tâches**, décompose le projet en livrables puis en tâches de plus en plus fines. C'est la colonne vertébrale de toute planification.\n\nPrincipe : on part du **résultat global** et on descend par niveaux.\n\nExemple — projet \"Lancer une boutique en ligne\" :\n```\n1. Boutique en ligne\n   1.1 Site web\n       1.1.1 Maquettes\n       1.1.2 Développement\n   1.2 Paiement\n       1.2.1 Intégration mobile money\n       1.2.2 Tests\n   1.3 Logistique\n       1.3.1 Contrats livreurs\n```\n\n⚠️ Règle des **100%** : la somme des sous-éléments doit couvrir exactement le projet, ni plus ni moins.\n\n📌 Le plus petit élément s'appelle un **lot de travail** (work package) : assez petit pour être estimé en temps et coût, assez grand pour être confié à une personne.\n\n✅ Avantage : la WBS rend visibles toutes les tâches, évite les oublis et facilite l'attribution des responsabilités.",
                    'questions' => [
                        ['question' => 'Que représente une WBS (Work Breakdown Structure) ?', 'options' => ['Le découpage hiérarchique du projet en livrables et tâches', 'Le budget total du projet', 'La liste des clients', 'Le contrat de travail'], 'correct' => [0], 'explanation' => 'La WBS est l\'organigramme qui décompose le projet en livrables puis en tâches gérables.'],
                        ['question' => 'Que dit la "règle des 100%" appliquée à la WBS ?', 'options' => ['La somme des sous-éléments couvre exactement le projet', 'Il faut finir à 100% le premier jour', 'Le budget doit doubler', 'Toutes les tâches sont optionnelles'], 'correct' => [0], 'explanation' => 'La règle des 100% impose que la décomposition couvre l\'intégralité du périmètre, sans manque ni surplus.'],
                        ['question' => 'Comment appelle-t-on le plus petit élément d\'une WBS ?', 'options' => ['Un lot de travail (work package)', 'Un jalon', 'Un risque', 'Un sprint'], 'correct' => [0], 'explanation' => 'Le lot de travail (work package) est l\'unité la plus fine de la WBS, estimable et attribuable.'],
                    ],
                ],
                [
                    'title' => 'Niveau 5 — Planning et diagramme de Gantt',
                    'subtitle' => 'Ordonnancer les tâches dans le temps',
                    'xp_reward' => 140,
                    'content' => "🎯 **Objectif** : planifier les tâches et visualiser le calendrier.\n\n💡 Le **diagramme de Gantt** est l'outil roi du planning : chaque tâche est une **barre horizontale** dont la longueur représente sa durée, positionnée sur un axe de temps.\n\n```\nTâche          | S1 | S2 | S3 | S4 |\nMaquettes      |████|    |    |    |\nDéveloppement  |    |████|████|    |\nTests          |    |    |    |████|\n```\n\nNotions clés :\n• **Dépendances** : certaines tâches ne peuvent commencer qu'après d'autres (ex. tester APRÈS développer). C'est la relation **fin → début**.\n• **Jalon** (milestone) : un point clé sans durée (ex. \"Validation du client\"), symbolisé par un losange 🔶.\n• **Chemin critique** : la plus longue chaîne de tâches dépendantes. Tout retard sur ce chemin retarde TOUT le projet.\n\n⚠️ Attention aux dépendances oubliées : si tu lances les tests avant la fin du développement, tu testes du vide.\n\n✅ Des outils comme MS Project, GanttProject ou même un tableur permettent de construire un Gantt facilement.",
                    'questions' => [
                        ['question' => 'Que représente une barre dans un diagramme de Gantt ?', 'options' => ['La durée et la position d\'une tâche dans le temps', 'Le salaire d\'un employé', 'Le bénéfice du projet', 'La couleur préférée du chef de projet'], 'correct' => [0], 'explanation' => 'Chaque barre du Gantt visualise la durée d\'une tâche et sa place sur l\'axe temporel.'],
                        ['question' => 'Qu\'est-ce que le chemin critique ?', 'options' => ['La plus longue chaîne de tâches dépendantes qui détermine la durée totale', 'Le chemin le plus court vers le bureau', 'La tâche la moins importante', 'Le budget restant'], 'correct' => [0], 'explanation' => 'Le chemin critique est la séquence de tâches la plus longue ; tout retard dessus retarde l\'ensemble du projet.'],
                        ['question' => 'Comment représente-t-on généralement un jalon (milestone) sur un Gantt ?', 'options' => ['Par un losange, car il n\'a pas de durée', 'Par une longue barre rouge', 'Par un cercle plein de plusieurs semaines', 'Par une colonne de chiffres'], 'correct' => [0], 'explanation' => 'Un jalon est un point clé sans durée, symbolisé par un losange sur le diagramme.'],
                    ],
                ],
                [
                    'title' => 'Niveau 6 — La gestion des risques',
                    'subtitle' => 'Anticiper ce qui peut mal tourner',
                    'xp_reward' => 150,
                    'content' => "🎯 **Objectif** : identifier, évaluer et traiter les risques.\n\n💡 Un **risque** est un événement **incertain** qui, s'il survient, a un impact (souvent négatif) sur le projet. Gérer les risques, c'est se préparer AVANT qu'ils n'arrivent.\n\nLa démarche en 4 temps :\n• 1️⃣ **Identifier** : lister les risques (ex. coupure d'électricité, retard fournisseur, départ d'un développeur).\n• 2️⃣ **Évaluer** : noter chaque risque selon sa **probabilité** × son **impact**.\n• 3️⃣ **Traiter** : choisir une stratégie.\n• 4️⃣ **Suivre** : revoir régulièrement.\n\nLes 4 stratégies de traitement :\n| Stratégie | Action |\n|-----------|--------|\n| Éviter | Supprimer la cause |\n| Réduire | Diminuer proba/impact |\n| Transférer | Assurance, sous-traitance |\n| Accepter | Prévoir un plan B |\n\n⚠️ Exemple Cameroun : risque de coupure de courant pendant une démo → stratégie *réduire* : prévoir un onduleur et un groupe électrogène.\n\n✅ On consigne tout dans un **registre des risques** mis à jour tout au long du projet.",
                    'questions' => [
                        ['question' => 'Comment évalue-t-on classiquement la criticité d\'un risque ?', 'options' => ['Probabilité × Impact', 'Coût ÷ Délai', 'Nombre d\'employés × salaire', 'Au hasard'], 'correct' => [0], 'explanation' => 'La criticité d\'un risque se mesure en croisant sa probabilité de survenue et son impact.'],
                        ['question' => 'Souscrire une assurance ou sous-traiter une partie risquée correspond à quelle stratégie ?', 'options' => ['Transférer le risque', 'Accepter le risque', 'Ignorer le risque', 'Augmenter le risque'], 'correct' => [0], 'explanation' => 'Transférer consiste à faire porter le risque par un tiers, via une assurance ou un sous-traitant.'],
                        ['question' => 'Parmi ces stratégies, lesquelles sont des stratégies valides de traitement des risques ?', 'options' => ['Éviter', 'Réduire', 'Oublier volontairement sans plan', 'Accepter avec un plan B'], 'correct' => [0, 1, 3], 'explanation' => 'Éviter, réduire et accepter (avec plan B) sont des stratégies valides ; ignorer sans plan n\'en est pas une.'],
                    ],
                ],
                [
                    'title' => 'Niveau 7 — Budget et ressources',
                    'subtitle' => 'Estimer les coûts et gérer les moyens',
                    'xp_reward' => 160,
                    'content' => "🎯 **Objectif** : estimer un budget et piloter les ressources.\n\n💡 Le **budget** d'un projet additionne tous les coûts nécessaires pour livrer le résultat. On distingue :\n• **Coûts directs** : main-d'œuvre, matériel, licences logicielles.\n• **Coûts indirects** : locaux, électricité, frais administratifs.\n• **Provision pour aléas** : une marge (souvent 5 à 15%) pour les imprévus.\n\nMéthodes d'estimation :\n• **Analogique** : on s'appuie sur un projet passé similaire (rapide, approximatif).\n• **Bottom-up** : on chiffre chaque lot de travail de la WBS puis on additionne (précis).\n\n⚠️ Exemple : projet à 2 000 000 FCFA de coûts directs + 400 000 FCFA indirects + 10% d'aléas = budget total d'environ 2 640 000 FCFA.\n\nLes **ressources** ne sont pas que l'argent : humaines (compétences), matérielles (ordinateurs), temporelles. Le **plan de charge** vérifie qu'on ne sur-affecte pas une personne (ex. demander 12h de travail par jour est irréaliste).\n\n✅ Un bon chef de projet suit l'écart entre le **budget prévu** et le **réalisé** à chaque étape.",
                    'questions' => [
                        ['question' => 'Quelle méthode d\'estimation consiste à chiffrer chaque lot de travail puis à additionner ?', 'options' => ['L\'estimation bottom-up', 'L\'estimation au hasard', 'L\'estimation analogique', 'L\'estimation négative'], 'correct' => [0], 'explanation' => 'L\'estimation bottom-up additionne le coût détaillé de chaque lot de travail, ce qui la rend précise.'],
                        ['question' => 'À quoi sert une provision pour aléas dans un budget ?', 'options' => ['À couvrir les imprévus', 'À payer les vacances', 'À gonfler artificiellement le prix', 'À rien'], 'correct' => [0], 'explanation' => 'La provision pour aléas est une marge destinée à absorber les imprévus du projet.'],
                        ['question' => 'Le plan de charge sert principalement à…', 'options' => ['Vérifier qu\'aucune ressource n\'est sur-affectée', 'Calculer les impôts', 'Choisir la couleur du logo', 'Recruter des clients'], 'correct' => [0], 'explanation' => 'Le plan de charge contrôle l\'affectation des ressources pour éviter de surcharger une personne.'],
                    ],
                ],
                [
                    'title' => 'Niveau 8 — Les parties prenantes',
                    'subtitle' => 'Gérer les acteurs autour du projet',
                    'xp_reward' => 170,
                    'content' => "🎯 **Objectif** : identifier et gérer les parties prenantes.\n\n💡 Une **partie prenante** (stakeholder) est toute personne ou entité **affectée** par le projet ou pouvant l'**influencer** : commanditaire, équipe, clients, fournisseurs, autorités, riverains…\n\nÉtapes :\n• 1️⃣ **Recenser** toutes les parties prenantes.\n• 2️⃣ **Cartographier** selon deux axes : leur **pouvoir** (capacité d'influence) et leur **intérêt** (implication).\n\nLa matrice **Pouvoir / Intérêt** :\n| | Faible intérêt | Fort intérêt |\n|---|---|---|\n| **Fort pouvoir** | Satisfaire | Gérer de près |\n| **Faible pouvoir** | Surveiller | Informer |\n\n⚠️ Erreur classique : oublier une autorité locale ou un chef de quartier qui peut bloquer un chantier à Douala. Ignorer une partie prenante puissante = danger.\n\n✅ Pour chacune, on définit une **stratégie de communication** : à quelle fréquence, par quel canal (réunion, WhatsApp, e-mail), avec quel niveau de détail. La communication est 80% du métier de chef de projet.",
                    'questions' => [
                        ['question' => 'Qu\'est-ce qu\'une partie prenante (stakeholder) ?', 'options' => ['Toute personne ou entité affectant ou affectée par le projet', 'Uniquement le client qui paie', 'Seulement le chef de projet', 'Le logiciel utilisé'], 'correct' => [0], 'explanation' => 'Une partie prenante est tout acteur qui peut influencer le projet ou être impacté par lui.'],
                        ['question' => 'Selon la matrice Pouvoir/Intérêt, comment traiter une partie prenante à fort pouvoir et fort intérêt ?', 'options' => ['La gérer de près', 'L\'ignorer complètement', 'La surveiller de loin', 'Lui cacher l\'avancement'], 'correct' => [0], 'explanation' => 'Une partie prenante puissante et très intéressée doit être gérée de près, avec une communication soutenue.'],
                        ['question' => 'Pourquoi ne faut-il pas négliger une autorité locale puissante mais peu impliquée ?', 'options' => ['Elle peut bloquer le projet si elle est mécontente', 'Elle paie le salaire de l\'équipe', 'Elle code l\'application', 'Elle n\'a aucun rôle'], 'correct' => [0], 'explanation' => 'Une partie prenante à fort pouvoir, même peu intéressée, doit être satisfaite car elle peut bloquer le projet.'],
                    ],
                ],
                [
                    'title' => 'Niveau 9 — Agile vs cycle en V',
                    'subtitle' => 'Choisir la bonne approche',
                    'xp_reward' => 185,
                    'content' => "🎯 **Objectif** : comprendre et choisir entre approche prédictive et agile.\n\n💡 Deux grandes familles de méthodes :\n\n**Cycle en V (prédictif)** : on définit TOUT au début (besoins, conception), puis on développe, puis on teste. Linéaire.\n```\nBesoins → Conception → Réalisation → Tests → Recette\n```\n• ✅ Adapté quand le besoin est **stable et bien connu** (ex. construction d'un pont).\n• ⚠️ Risqué si les besoins changent : on s'en aperçoit trop tard.\n\n**Agile (Scrum, itératif)** : on livre par petites itérations courtes appelées **sprints** (1 à 4 semaines), avec des retours fréquents du client.\n• Rituels Scrum : **daily** (point quotidien), **sprint review**, **rétrospective**.\n• Rôles : **Product Owner** (porte le besoin), **Scrum Master** (facilite), équipe.\n• ✅ Adapté quand les besoins **évoluent** (ex. une appli mobile).\n\n📌 Manifeste Agile : privilégier les individus et interactions, le produit qui marche, la collaboration et l'adaptation au changement.\n\n✅ Il n'y a pas de méthode \"meilleure\" : on choisit selon la **stabilité du besoin** et le **niveau d'incertitude**.",
                    'questions' => [
                        ['question' => 'Dans quel contexte le cycle en V est-il le plus adapté ?', 'options' => ['Quand le besoin est stable et bien connu', 'Quand le besoin change tous les jours', 'Quand il n\'y a aucun budget', 'Quand on n\'a pas d\'équipe'], 'correct' => [0], 'explanation' => 'Le cycle en V convient aux projets dont le besoin est figé dès le départ, car tout y est défini en amont.'],
                        ['question' => 'Comment appelle-t-on une itération courte de développement en Scrum ?', 'options' => ['Un sprint', 'Un Gantt', 'Un jalon', 'Un risque'], 'correct' => [0], 'explanation' => 'En Scrum, le travail est découpé en sprints, itérations courtes de 1 à 4 semaines.'],
                        ['question' => 'Lesquels de ces éléments font partie de l\'approche agile / Scrum ?', 'options' => ['Le Product Owner', 'La rétrospective de sprint', 'Le figement total du besoin dès le début', 'Le point quotidien (daily)'], 'correct' => [0, 1, 3], 'explanation' => 'Product Owner, rétrospective et daily sont agiles ; le figement total du besoin caractérise au contraire le cycle en V.'],
                    ],
                ],
                [
                    'title' => 'Niveau 10 — Clôture et bilan',
                    'subtitle' => 'Finir proprement et capitaliser',
                    'xp_reward' => 200,
                    'content' => "🎯 **Objectif** : clôturer un projet et tirer les leçons.\n\n💡 La **clôture** est trop souvent bâclée. Pourtant, mal finir efface une bonne partie du travail. Les étapes :\n\n• 1️⃣ **Recette finale** : faire valider et accepter formellement les livrables par le client (procès-verbal de réception).\n• 2️⃣ **Solder** : factures, contrats fournisseurs, libération des ressources.\n• 3️⃣ **Documenter et archiver** : ranger les documents pour les retrouver.\n• 4️⃣ **Bilan / REX** (Retour d'EXpérience) : qu'est-ce qui a bien marché ? Quoi améliorer ? On capitalise pour les futurs projets.\n• 5️⃣ **Célébrer** : reconnaître le travail de l'équipe. 🎉\n\n⚠️ Mesure du succès : on compare le **réalisé** au **prévu** sur le triangle d'or (délai, coût, qualité) ET la **satisfaction** des parties prenantes.\n\n📌 Indicateur clé : un projet \"réussi\" livre la bonne chose, dans les délais, le budget, et le client est content.\n\n🏆 **Félicitations !** Tu maîtrises désormais le cycle complet de la gestion de projet : du cadrage à la clôture. Débouchés : **assistant chef de projet**, **chef de projet junior**, **Scrum Master**, **Product Owner**, puis **directeur de programme** ou **PMO**. Tu peux viser des certifications reconnues comme **PMP**, **PRINCE2** ou **PSM (Scrum)**. À toi de piloter ! 🚀",
                    'questions' => [
                        ['question' => 'Qu\'est-ce qu\'un REX (Retour d\'Expérience) en clôture de projet ?', 'options' => ['Un bilan des réussites et points à améliorer pour capitaliser', 'Le remboursement des clients', 'Une nouvelle phase d\'exécution', 'Un type de risque'], 'correct' => [0], 'explanation' => 'Le REX analyse ce qui a bien et mal fonctionné afin de capitaliser sur les projets futurs.'],
                        ['question' => 'Comment formalise-t-on l\'acceptation finale des livrables par le client ?', 'options' => ['Par un procès-verbal de réception', 'Par un simple message oral', 'En ne faisant rien', 'En recommençant le projet'], 'correct' => [0], 'explanation' => 'La recette finale se formalise par un procès-verbal de réception signé par le client.'],
                        ['question' => 'Sur quels critères mesure-t-on le succès d\'un projet à sa clôture ?', 'options' => ['Le respect des délais', 'Le respect du budget', 'La qualité et la satisfaction des parties prenantes', 'La couleur de l\'écran de l\'ordinateur'], 'correct' => [0, 1, 2], 'explanation' => 'Le succès se mesure sur le triangle d\'or (délai, coût, qualité) et la satisfaction des parties prenantes.'],
                    ],
                ],
            ],
        ]);

        $this->command->info('  ✓ Roadmap Gestion de Projet créée (10 niveaux).');
    }
}
