<?php

namespace Database\Seeders\Roadmaps;

use Illuminate\Database\Seeder;

/**
 * Roadmap Entrepreneuriat — de l'idée à la création et la croissance de son entreprise.
 */
class EntrepreneuriatRoadmapSeeder extends Seeder
{
    use RoadmapSeederHelper;

    public function run(): void
    {
        $this->createRoadmap([
            'title' => 'Deviens Entrepreneur (Créer son Entreprise)',
            'slug' => 'entrepreneuriat-creation-entreprise',
            'domain' => 'gestion',
            'description' => "Un parcours complet pour passer de l'idée au projet rentable. Tu apprendras à cultiver l'esprit entrepreneurial, valider un besoin réel, étudier ton marché, construire ton modèle économique, choisir ton statut juridique (OHADA), monter un business plan, trouver des financements, lancer un MVP, gérer la croissance et rebondir après un échec. Exemples ancrés dans le contexte camerounais et africain.",
            'objectives' => "Développer une vraie posture d'entrepreneur\nTrouver une idée et valider qu'elle résout un vrai besoin\nRéaliser une étude de marché simple et fiable\nModéliser son activité avec le Business Model Canvas\nChoisir un statut juridique adapté (OHADA)\nMonter un business plan et trouver des financements",
            'icon' => '🚀',
            'color' => '#F97316',
            'difficulty' => 'beginner',
            'required_packs' => [],
            'pass_threshold' => 70,
            'order' => 20,
            'levels' => [
                [
                    'title' => 'Niveau 1 — L\'esprit entrepreneurial',
                    'subtitle' => 'Adopter la bonne posture',
                    'xp_reward' => 100,
                    'content' => "🎯 **Objectif** : comprendre ce qui distingue un entrepreneur d'un simple employé et adopter le bon état d'esprit.\n\n💡 Entreprendre, ce n'est pas avoir une idée géniale par hasard, c'est résoudre un problème pour des clients prêts à payer. Les qualités clés :\n\n• **Prise d'initiative** : agir sans attendre la permission.\n• **Tolérance au risque maîtrisé** : tester petit avant d'investir gros.\n• **Résilience** : continuer malgré les refus et les obstacles.\n• **Orientation client** : écouter plus que parler.\n\n⚠️ Mythe à éviter : « il faut beaucoup d'argent pour commencer ». Beaucoup d'entreprises à Douala ou Yaoundé démarrent avec un capital modeste et le mobile money comme outil d'encaissement.\n\n✅ À retenir : l'entrepreneur transforme un problème en opportunité, prend des décisions dans l'incertitude et apprend en avançant. La motivation seule ne suffit pas : il faut de la discipline et une exécution régulière.",
                    'questions' => [
                        ['question' => 'Quelle est la priorité d\'un entrepreneur au démarrage ?', 'options' => ['Avoir un beau logo', 'Résoudre un vrai problème pour des clients', 'Louer un grand bureau', 'Embaucher dix personnes'], 'correct' => [1], 'explanation' => 'Une entreprise existe d\'abord pour résoudre un problème que des clients sont prêts à payer.'],
                        ['question' => 'Quelles qualités sont essentielles à l\'entrepreneur ? (plusieurs réponses)', 'options' => ['La résilience', 'La peur du changement', 'L\'orientation client', 'L\'attente passive d\'instructions'], 'correct' => [0,2], 'explanation' => 'La résilience et l\'écoute du client sont des piliers ; la passivité et la peur du changement sont des freins.'],
                        ['question' => 'Le mythe « il faut beaucoup d\'argent pour démarrer » est :', 'options' => ['Toujours vrai', 'Souvent faux, on peut démarrer petit', 'Une loi OHADA', 'Réservé aux grandes villes'], 'correct' => [1], 'explanation' => 'On peut tester une idée avec peu de moyens avant d\'investir davantage.'],
                    ],
                ],
                [
                    'title' => 'Niveau 2 — Trouver une idée et valider le besoin',
                    'subtitle' => 'Du problème à l\'opportunité',
                    'xp_reward' => 110,
                    'content' => "🎯 **Objectif** : générer des idées à partir de vrais problèmes et vérifier qu'un besoin existe avant d'investir.\n\n💡 Une bonne idée part d'un **problème ressenti** par des gens précis. Méthodes pour trouver des idées :\n\n• Observer les frustrations du quotidien (transport, livraison, paiement).\n• Améliorer ce qui existe déjà (moins cher, plus rapide, plus proche).\n• Identifier un besoin mal servi dans ton quartier ou ta profession.\n\n⚠️ Le piège classique : tomber amoureux de son idée sans jamais en parler aux clients. C'est le « syndrome du garage ».\n\n✅ La **validation du besoin** se fait sur le terrain :\n\n• Interroger 15 à 20 clients potentiels (sans vendre, juste écouter).\n• Demander : « Comment faites-vous aujourd'hui ? Combien ça vous coûte ? »\n• Repérer si les gens paient déjà pour résoudre ce problème.\n\nExemple : avant de lancer un service de livraison de repas à Yaoundé, demande aux bureaux s'ils commandent déjà et combien ils dépensent par midi.",
                    'questions' => [
                        ['question' => 'Une bonne idée d\'entreprise part avant tout :', 'options' => ['D\'une technologie à la mode', 'D\'un problème réel ressenti par des clients', 'D\'un nom accrocheur', 'D\'un concurrent à copier'], 'correct' => [1], 'explanation' => 'L\'idée doit répondre à un problème concret vécu par des clients identifiés.'],
                        ['question' => 'Comment valider un besoin avant d\'investir ?', 'options' => ['Demander à sa famille si l\'idée est bonne', 'Interroger des clients potentiels sur le terrain', 'Lancer directement la production', 'Attendre que le besoin se manifeste seul'], 'correct' => [1], 'explanation' => 'On valide en allant écouter les vrais clients potentiels et leurs habitudes actuelles.'],
                        ['question' => 'Le « syndrome du garage » désigne :', 'options' => ['Réparer des voitures', 'S\'enfermer sur son idée sans parler aux clients', 'Travailler la nuit', 'Démarrer sans local'], 'correct' => [1], 'explanation' => 'C\'est le risque de développer un produit sans jamais confronter l\'idée au marché.'],
                    ],
                ],
                [
                    'title' => 'Niveau 3 — L\'étude de marché',
                    'subtitle' => 'Connaître son marché et ses concurrents',
                    'xp_reward' => 120,
                    'content' => "🎯 **Objectif** : mesurer la taille du marché, comprendre la concurrence et cibler ses clients.\n\n💡 Une étude de marché répond à trois questions :\n\n• **Qui** sont mes clients (âge, revenus, localisation, habitudes) ?\n• **Combien** sont-ils et que dépensent-ils ?\n• **Qui** sont mes concurrents et comment se différencier ?\n\nDeux types de données :\n\n| Type | Exemple | Coût |\n|------|---------|------|\n| Terrain (primaire) | Sondages, interviews, observation | Faible |\n| Documentaire (secondaire) | Rapports INS, presse, réseaux sociaux | Gratuit |\n\n✅ Outil utile : la **segmentation**. On découpe le marché en groupes homogènes (ex. étudiants, salariés, commerçants) et on choisit la cible la plus accessible et rentable.\n\n⚠️ Ne confonds pas marché total et marché réellement atteignable. Vendre « à tout le monde » revient souvent à ne convaincre personne.\n\nExemple : pour un cybercafé à Douala, observe le passage devant l'emplacement, compte les concurrents proches et estime le prix moyen pratiqué.",
                    'questions' => [
                        ['question' => 'Une étude de marché sert principalement à :', 'options' => ['Décorer le local', 'Comprendre les clients et la concurrence', 'Payer moins d\'impôts', 'Recruter du personnel'], 'correct' => [1], 'explanation' => 'Elle éclaire qui sont les clients, leur nombre, leurs dépenses et les concurrents.'],
                        ['question' => 'Quelles sources sont des données documentaires (secondaires) ?', 'options' => ['Rapports de l\'institut de statistique', 'Interviews que tu mènes toi-même', 'Articles de presse', 'Observation directe d\'un magasin'], 'correct' => [0,2], 'explanation' => 'Les données secondaires existent déjà (rapports, presse) ; les interviews et l\'observation sont primaires.'],
                        ['question' => 'La segmentation consiste à :', 'options' => ['Diviser le marché en groupes homogènes', 'Baisser tous les prix', 'Vendre à tout le monde', 'Fermer un segment de l\'entreprise'], 'correct' => [0], 'explanation' => 'Segmenter permet de cibler le groupe de clients le plus accessible et rentable.'],
                    ],
                ],
                [
                    'title' => 'Niveau 4 — Le Business Model Canvas',
                    'subtitle' => 'Modéliser son activité sur une page',
                    'xp_reward' => 130,
                    'content' => "🎯 **Objectif** : décrire son modèle économique avec les 9 blocs du Business Model Canvas (BMC).\n\n💡 Le BMC tient sur une page et relie ce que tu offres, à qui, et comment tu gagnes de l'argent. Les 9 blocs :\n\n• **Segments de clients** : à qui tu t'adresses.\n• **Proposition de valeur** : le problème que tu résous.\n• **Canaux** : comment tu atteins les clients.\n• **Relations clients** : comment tu les fidélises.\n• **Sources de revenus** : comment tu encaisses (vente, abonnement, commission).\n• **Ressources clés** : ce qu'il te faut (matériel, équipe).\n• **Activités clés** : ce que tu dois faire chaque jour.\n• **Partenaires clés** : fournisseurs, alliés.\n• **Structure de coûts** : tes dépenses principales.\n\n✅ Le cœur du modèle : l'équilibre entre **revenus** (à droite) et **coûts** (à gauche). Si les revenus ne couvrent pas les coûts, le modèle n'est pas viable.\n\n⚠️ Le BMC est un brouillon vivant : on le révise à chaque nouvelle info terrain. Exemple : une couturière à Bafoussam peut tester l'abonnement mensuel vs. la vente à l'unité.",
                    'questions' => [
                        ['question' => 'Combien de blocs compose le Business Model Canvas ?', 'options' => ['5', '7', '9', '12'], 'correct' => [2], 'explanation' => 'Le BMC est composé de 9 blocs interdépendants.'],
                        ['question' => 'Lesquels de ces éléments sont des blocs du BMC ?', 'options' => ['Proposition de valeur', 'Sources de revenus', 'Logo de l\'entreprise', 'Segments de clients'], 'correct' => [0,1,3], 'explanation' => 'La proposition de valeur, les revenus et les segments clients sont des blocs ; le logo n\'en est pas un.'],
                        ['question' => 'Un modèle économique est viable quand :', 'options' => ['Les revenus couvrent durablement les coûts', 'Le logo est joli', 'On a beaucoup de partenaires', 'On dépense beaucoup en publicité'], 'correct' => [0], 'explanation' => 'La viabilité repose sur des revenus qui dépassent durablement les coûts.'],
                    ],
                ],
                [
                    'title' => 'Niveau 5 — Choisir son statut juridique (OHADA)',
                    'subtitle' => 'Le cadre légal de l\'entreprise',
                    'xp_reward' => 140,
                    'content' => "🎯 **Objectif** : choisir une forme juridique adaptée à son projet dans l'espace OHADA.\n\n💡 Le statut détermine la responsabilité, la fiscalité et la crédibilité. Principales formes dans l'espace OHADA :\n\n• **Entreprenant / entreprise individuelle** : simple, peu coûteux, mais responsabilité personnelle illimitée (tes biens propres peuvent être engagés).\n• **SARL (Société à Responsabilité Limitée)** : responsabilité limitée aux apports, capital libre, adaptée aux PME. La forme la plus courante.\n• **SA (Société Anonyme)** : pour les grands projets, capital plus élevé, gouvernance lourde.\n• **SAS** : souple, introduite par l'OHADA révisé.\n\n✅ Critères de choix :\n\n• Niveau de risque que tu acceptes (responsabilité limitée ou non).\n• Nombre d'associés.\n• Besoin de lever des fonds.\n• Image vis-à-vis des banques et clients.\n\n⚠️ Une entreprise individuelle ne sépare pas ton patrimoine personnel de celui de l'activité : en cas de dette, tes biens personnels sont exposés. La société (SARL, SA) crée une personne morale distincte qui protège ce patrimoine.",
                    'questions' => [
                        ['question' => 'Quel statut limite la responsabilité aux apports et convient aux PME ?', 'options' => ['Entreprise individuelle', 'SARL', 'Association', 'Coopérative agricole'], 'correct' => [1], 'explanation' => 'La SARL limite la responsabilité des associés au montant de leurs apports.'],
                        ['question' => 'Quel est le principal risque de l\'entreprise individuelle ?', 'options' => ['Trop d\'associés', 'Responsabilité personnelle illimitée', 'Capital social trop élevé', 'Interdiction de vendre'], 'correct' => [1], 'explanation' => 'En entreprise individuelle, le patrimoine personnel de l\'entrepreneur n\'est pas protégé.'],
                        ['question' => 'Quels critères guident le choix du statut juridique ?', 'options' => ['Le niveau de responsabilité accepté', 'Le nombre d\'associés', 'La couleur du logo', 'Le besoin de lever des fonds'], 'correct' => [0,1,3], 'explanation' => 'Responsabilité, nombre d\'associés et besoins de financement orientent le choix ; le logo n\'a aucun rôle.'],
                    ],
                ],
                [
                    'title' => 'Niveau 6 — Le business plan',
                    'subtitle' => 'Structurer et chiffrer son projet',
                    'xp_reward' => 150,
                    'content' => "🎯 **Objectif** : rédiger un business plan clair qui convainc partenaires, banques et investisseurs.\n\n💡 Le business plan raconte ton projet et prouve sa rentabilité. Parties essentielles :\n\n• **Résumé (executive summary)** : l'essentiel en 1 page, à rédiger en dernier.\n• **Présentation du projet et de l'équipe**.\n• **Étude de marché** (vue au niveau 3).\n• **Stratégie commerciale et marketing** (prix, distribution, communication).\n• **Plan financier** : compte de résultat prévisionnel, plan de trésorerie, seuil de rentabilité.\n\n✅ Le **seuil de rentabilité** (point mort) est le chiffre d'affaires minimum pour couvrir tous les coûts. Formule simplifiée :\n\nSeuil = Charges fixes / Taux de marge sur coûts variables\n\nExemple : si tes charges fixes mensuelles sont 200 000 FCFA et que chaque vente laisse 40 % de marge, il faut 500 000 FCFA de ventes pour atteindre l'équilibre.\n\n⚠️ Un business plan trop optimiste perd toute crédibilité. Prévois aussi un scénario prudent et justifie chaque chiffre par des hypothèses réalistes.",
                    'questions' => [
                        ['question' => 'Le seuil de rentabilité correspond :', 'options' => ['Au chiffre d\'affaires minimum pour couvrir les coûts', 'Au bénéfice maximum possible', 'Au capital social', 'Au montant de l\'emprunt'], 'correct' => [0], 'explanation' => 'Au seuil de rentabilité, les revenus couvrent exactement l\'ensemble des charges.'],
                        ['question' => 'Quand rédige-t-on idéalement le résumé (executive summary) ?', 'options' => ['En tout premier', 'En dernier, une fois le plan terminé', 'Jamais', 'À mi-parcours seulement'], 'correct' => [1], 'explanation' => 'Le résumé synthétise tout le plan : on le rédige en dernier pour qu\'il soit fidèle.'],
                        ['question' => 'Un bon plan financier prévoit :', 'options' => ['Uniquement le scénario le plus optimiste', 'Un compte de résultat et un plan de trésorerie réalistes', 'Aucun chiffre, juste des idées', 'Seulement le capital de départ'], 'correct' => [1], 'explanation' => 'Le plan financier doit présenter des prévisions réalistes et justifiées, pas seulement l\'optimisme.'],
                    ],
                ],
                [
                    'title' => 'Niveau 7 — Financement et levée de fonds',
                    'subtitle' => 'Trouver l\'argent pour démarrer et grandir',
                    'xp_reward' => 160,
                    'content' => "🎯 **Objectif** : connaître les sources de financement et savoir laquelle choisir selon son stade.\n\n💡 On finance rarement tout seul. Principales sources :\n\n• **Fonds propres** : ton épargne, c'est le signal d'engagement n°1.\n• **Love money** : famille, amis (les « 3 F » : Family, Friends, Fools).\n• **Tontines** : épargne rotative très répandue en Afrique, sans intérêt bancaire.\n• **Microfinance / banque** : crédit avec intérêts, demande des garanties.\n• **Subventions et concours** : programmes d'appui aux jeunes entrepreneurs.\n• **Investisseurs (business angels, capital-risque)** : apportent de l'argent contre une part du capital.\n\n✅ Distinction clé : la **dette** (à rembourser avec intérêts, tu gardes 100 % de l'entreprise) vs. le **capital** (l'investisseur prend des parts, pas de remboursement mais tu partages le pouvoir et les bénéfices).\n\n⚠️ Ne lève pas trop d'argent trop tôt : céder beaucoup de parts au début te coûte cher si l'entreprise réussit. Commence léger (bootstrapping) et lève quand la traction est prouvée.",
                    'questions' => [
                        ['question' => 'Les « 3 F » du financement désignent :', 'options' => ['Family, Friends, Fools', 'Factures, Frais, Fiscalité', 'Fonds, Foncier, Franchise', 'Finance, Force, Futur'], 'correct' => [0], 'explanation' => 'Les 3 F (famille, amis, « fous ») sont les premiers soutiens financiers d\'un projet débutant.'],
                        ['question' => 'Quelle différence entre financement par dette et par capital ?', 'options' => ['La dette se rembourse, le capital cède des parts', 'Aucune différence', 'La dette cède des parts, le capital se rembourse', 'Les deux sont gratuits'], 'correct' => [0], 'explanation' => 'La dette se rembourse avec intérêts ; le capital implique de céder une part de l\'entreprise.'],
                        ['question' => 'Quelles sources de financement sont courantes en Afrique pour démarrer ?', 'options' => ['Les tontines', 'Les fonds propres et le love money', 'Les obligations d\'État émises par l\'entrepreneur', 'La microfinance'], 'correct' => [0,1,3], 'explanation' => 'Tontines, fonds propres, love money et microfinance sont accessibles ; un particulier n\'émet pas d\'obligations d\'État.'],
                    ],
                ],
                [
                    'title' => 'Niveau 8 — Lancement et MVP',
                    'subtitle' => 'Tester vite, apprendre vite',
                    'xp_reward' => 170,
                    'content' => "🎯 **Objectif** : lancer rapidement une version minimale pour apprendre du marché réel.\n\n💡 Le **MVP (Minimum Viable Product)** est la version la plus simple de ton offre qui résout déjà le problème principal du client. Objectif : tester l'idée avec un minimum de moyens.\n\n• Au lieu d'une appli complète, commence par une page WhatsApp Business + un formulaire.\n• Au lieu d'un restaurant, vends d'abord quelques plats sur commande.\n\n✅ Le cycle **Construire → Mesurer → Apprendre** (Lean Startup) :\n\n• Construire le MVP rapidement.\n• Mesurer le comportement réel des clients (ventes, retours).\n• Apprendre et ajuster, voire **pivoter** si le besoin n'est pas confirmé.\n\nIndicateurs à suivre dès le lancement :\n\n• Nombre de clients réels.\n• Taux de clients qui reviennent (rétention).\n• Coût pour acquérir un client vs. ce qu'il rapporte.\n\n⚠️ Erreur fréquente : viser la perfection avant de lancer. « Fait vaut mieux que parfait » : le marché t'apprendra plus en 1 mois qu'un an de planification dans ta chambre.",
                    'questions' => [
                        ['question' => 'Un MVP est :', 'options' => ['Le produit le plus cher possible', 'La version minimale qui résout déjà le problème', 'Un produit sans aucun client', 'Le produit final parfait'], 'correct' => [1], 'explanation' => 'Le MVP est la version la plus simple qui apporte déjà de la valeur et permet de tester le marché.'],
                        ['question' => 'Le cycle Lean Startup est :', 'options' => ['Construire, Mesurer, Apprendre', 'Vendre, Acheter, Stocker', 'Planifier, Attendre, Espérer', 'Recruter, Licencier, Recruter'], 'correct' => [0], 'explanation' => 'Le cycle Construire-Mesurer-Apprendre permet d\'itérer rapidement à partir des retours du marché.'],
                        ['question' => '« Pivoter » signifie :', 'options' => ['Changer de modèle quand le besoin n\'est pas confirmé', 'Fermer l\'entreprise', 'Augmenter les prix', 'Déménager le bureau'], 'correct' => [0], 'explanation' => 'Pivoter, c\'est changer d\'orientation stratégique en fonction des apprentissages du terrain.'],
                    ],
                ],
                [
                    'title' => 'Niveau 9 — Gestion et croissance',
                    'subtitle' => 'Piloter et faire grandir l\'entreprise',
                    'xp_reward' => 185,
                    'content' => "🎯 **Objectif** : gérer la trésorerie, suivre ses indicateurs et faire croître l'entreprise sainement.\n\n💡 La première cause d'échec n'est pas le manque de profit, c'est le manque de **trésorerie** (cash). On peut être rentable sur le papier et faire faillite faute de liquidités pour payer les salaires ou les fournisseurs.\n\n• Sépare toujours le compte personnel du compte de l'entreprise.\n• Suis ta trésorerie chaque semaine : entrées vs. sorties.\n• Surveille les délais de paiement de tes clients.\n\n✅ Indicateurs (KPI) à piloter :\n\n• Chiffre d'affaires et marge.\n• Coût d'acquisition client et valeur d'un client dans le temps.\n• Taux de rétention.\n\nLeviers de croissance :\n\n• **Vendre plus** aux clients existants (montée en gamme, fidélisation).\n• **Élargir** la clientèle (nouveaux quartiers, nouvelles villes).\n• **Déléguer et recruter** pour ne pas être le goulot d'étranglement.\n\n⚠️ Croître trop vite sans organisation tue la qualité. Documente tes processus (procédures simples) avant de dupliquer ton activité.",
                    'questions' => [
                        ['question' => 'Quelle est la première cause d\'échec des entreprises ?', 'options' => ['Trop de clients', 'Le manque de trésorerie (cash)', 'Un logo raté', 'Trop de bénéfices'], 'correct' => [1], 'explanation' => 'Beaucoup d\'entreprises rentables font faillite faute de liquidités pour fonctionner au quotidien.'],
                        ['question' => 'Quelles pratiques de gestion sont saines ?', 'options' => ['Séparer compte personnel et compte de l\'entreprise', 'Suivre la trésorerie régulièrement', 'Mélanger l\'argent perso et celui de l\'entreprise', 'Ignorer les délais de paiement clients'], 'correct' => [0,1], 'explanation' => 'Séparer les comptes et suivre la trésorerie sont des bases ; mélanger les fonds et ignorer les délais sont des erreurs.'],
                        ['question' => 'Un levier de croissance consiste à :', 'options' => ['Vendre plus aux clients existants', 'Réduire la qualité', 'Arrêter de communiquer', 'Refuser de déléguer'], 'correct' => [0], 'explanation' => 'Fidéliser et vendre davantage aux clients actuels est un levier de croissance efficace.'],
                    ],
                ],
                [
                    'title' => 'Niveau 10 — Échec et résilience',
                    'subtitle' => 'Rebondir et durer',
                    'xp_reward' => 200,
                    'content' => "🎯 **Objectif** : comprendre l'échec comme étape d'apprentissage et bâtir sa résilience d'entrepreneur.\n\n💡 La majorité des entrepreneurs à succès ont d'abord échoué. L'échec n'est pas une fin, c'est une donnée : il révèle ce qui ne marche pas pour ajuster la suite.\n\n• **Échec ≠ identité** : c'est le projet qui échoue, pas toi en tant que personne.\n• Fais un **post-mortem** : qu'est-ce qui a marché ? quoi a échoué ? pourquoi ?\n• Protège-toi en limitant la casse (statut à responsabilité limitée, pas de surendettement).\n\n✅ Construire sa résilience :\n\n• Entoure-toi (mentors, réseaux d'entrepreneurs, incubateurs).\n• Garde une réserve financière personnelle de sécurité.\n• Décompose les grands objectifs en petites victoires.\n• Prends soin de ta santé physique et mentale : l'entrepreneur est sa propre ressource clé.\n\n⚠️ Persévérer n'est pas s'entêter : sache distinguer un obstacle temporaire d'un mur infranchissable, et accepte parfois d'arrêter pour mieux repartir.\n\n🏆 **Félicitations !** Tu maîtrises désormais le parcours complet : esprit entrepreneurial, idée, marché, modèle, statut, business plan, financement, MVP, croissance et résilience. Tes débouchés : créer ta propre entreprise, devenir consultant en création d'entreprise, intrapreneur dans une organisation, chargé d'incubation, ou conseiller en financement de projets. L'aventure commence maintenant : passe à l'action, lance ton MVP !",
                    'questions' => [
                        ['question' => 'Face à un échec, l\'entrepreneur résilient :', 'options' => ['Abandonne définitivement toute idée', 'En tire des leçons via un post-mortem', 'Cache toutes ses erreurs', 'Considère qu\'il a échoué en tant que personne'], 'correct' => [1], 'explanation' => 'Analyser ses erreurs (post-mortem) permet d\'apprendre et de mieux rebondir.'],
                        ['question' => 'Quelles pratiques renforcent la résilience entrepreneuriale ?', 'options' => ['S\'entourer de mentors et de réseaux', 'Garder une réserve financière de sécurité', 'S\'isoler complètement', 'Décomposer les objectifs en petites victoires'], 'correct' => [0,1,3], 'explanation' => 'Le réseau, une réserve financière et des petites victoires soutiennent la résilience ; l\'isolement la fragilise.'],
                        ['question' => 'Persévérer intelligemment, c\'est :', 'options' => ['S\'entêter quoi qu\'il arrive', 'Distinguer obstacle temporaire et mur infranchissable', 'Ne jamais s\'arrêter', 'Ignorer tous les signaux du marché'], 'correct' => [1], 'explanation' => 'La résilience suppose de savoir quand insister et quand changer de cap ou s\'arrêter.'],
                    ],
                ],
            ],
        ]);

        $this->command->info('  ✓ Roadmap Entrepreneuriat créée (10 niveaux).');
    }
}
