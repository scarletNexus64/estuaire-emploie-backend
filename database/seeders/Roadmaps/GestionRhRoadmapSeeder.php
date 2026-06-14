<?php

namespace Database\Seeders\Roadmaps;

use Illuminate\Database\Seeder;

/**
 * Roadmap Gestion des Ressources Humaines — maîtriser la fonction RH de A à Z dans le contexte africain.
 */
class GestionRhRoadmapSeeder extends Seeder
{
    use RoadmapSeederHelper;

    public function run(): void
    {
        $this->createRoadmap([
            'title' => 'Deviens Gestionnaire des Ressources Humaines',
            'slug' => 'gestion-ressources-humaines',
            'domain' => 'rh',
            'description' => "Une formation complète et progressive pour devenir un professionnel des ressources humaines compétent. De la compréhension du rôle de la fonction RH jusqu'à la GPEC et la marque employeur, tu apprendras à recruter, intégrer, administrer le personnel, gérer la paie, développer les compétences et entretenir un bon climat social. Le contenu est ancré dans le contexte africain francophone (droit OHADA, Code du travail camerounais, mobile money, réalités des PME de Douala et Yaoundé).",
            'objectives' => "Comprendre le rôle stratégique et opérationnel de la fonction RH\nMaîtriser le processus de recrutement et l'intégration des nouveaux employés\nGérer les contrats de travail et l'administration du personnel\nConnaître les notions essentielles de la paie\nPiloter la gestion des compétences, la formation et l'évaluation\nAppliquer le droit du travail, animer le climat social et construire une marque employeur",
            'icon' => '🧑\u{200D}💼',
            'color' => '#7C3AED',
            'difficulty' => 'intermediate',
            'required_packs' => [],
            'pass_threshold' => 70,
            'order' => 20,
            'levels' => [
                [
                    'title' => 'Niveau 1 — Le rôle de la fonction RH',
                    'subtitle' => 'Comprendre la mission et la valeur des RH',
                    'xp_reward' => 100,
                    'content' => "🎯 **Objectif** : comprendre ce qu'est la fonction Ressources Humaines et pourquoi elle est stratégique pour l'entreprise.\n\n💡 Les RH ne se limitent pas à « gérer les salaires ». Elles ont plusieurs missions :\n\n• **Administrer** : contrats, paie, congés, déclarations sociales\n• **Recruter et intégrer** : attirer et fidéliser les talents\n• **Développer** : formation, gestion des compétences\n• **Animer** : climat social, motivation, dialogue\n• **Conseiller la direction** : la fonction RH devient un partenaire stratégique (Business Partner)\n\n✅ On distingue le **rôle opérationnel** (tâches quotidiennes : paie, dossiers) du **rôle stratégique** (anticiper les besoins, accompagner le changement).\n\n⚠️ Dans une PME de Douala, le gestionnaire RH porte souvent plusieurs casquettes à la fois. Dans une grande entreprise, les rôles sont spécialisés (recruteur, gestionnaire paie, responsable formation).\n\nLa fonction RH crée de la valeur : un personnel bien géré est plus productif, plus fidèle et coûte moins cher en turnover.",
                    'questions' => [
                        ['question' => "Quelle affirmation décrit le mieux le rôle stratégique des RH ?", 'options' => ['Établir uniquement les fiches de paie', 'Anticiper les besoins en compétences et accompagner la direction', 'Surveiller les heures de présence', 'Organiser les pauses café'], 'correct' => [1], 'explanation' => "Le rôle stratégique consiste à anticiper et à conseiller la direction, au-delà des tâches administratives."],
                        ['question' => "Parmi ces missions, lesquelles relèvent de la fonction RH ? (plusieurs réponses)", 'options' => ['Recrutement et intégration', 'Gestion de la paie et des contrats', 'Fabrication des produits de l\'entreprise', 'Développement des compétences et formation'], 'correct' => [0,1,3], 'explanation' => "Recrutement, administration/paie et développement des compétences sont des missions RH ; la fabrication relève de la production."],
                        ['question' => "Dans une petite PME camerounaise, le gestionnaire RH a tendance à :", 'options' => ['Ne faire que du recrutement', 'Cumuler plusieurs fonctions RH à la fois', 'Travailler uniquement sur la stratégie', 'Ne gérer que la paie'], 'correct' => [1], 'explanation' => "Dans les PME, le RH est souvent polyvalent et assure plusieurs missions simultanément."],
                    ],
                ],
                [
                    'title' => 'Niveau 2 — Le recrutement',
                    'subtitle' => 'Attirer et sélectionner les bons candidats',
                    'xp_reward' => 110,
                    'content' => "🎯 **Objectif** : maîtriser les étapes clés d'un recrutement réussi.\n\n💡 Le recrutement suit un processus structuré :\n\n1. **Définir le besoin** : fiche de poste (missions, compétences, profil)\n2. **Sourcer** : diffuser l'annonce (sites emploi, réseaux, cooptation)\n3. **Présélectionner** : tri des CV et lettres de motivation\n4. **Entretenir** : entretien téléphonique puis en face-à-face\n5. **Évaluer** : tests techniques, mise en situation\n6. **Décider et proposer** : offre, négociation salariale\n\n✅ Une bonne **fiche de poste** est la base de tout : sans elle, on recrute « au hasard ». Elle précise l'intitulé, les missions, le rattachement hiérarchique et les compétences requises.\n\n⚠️ Évite les **discriminations** (âge, sexe, origine, ethnie, religion). Le recrutement doit être basé sur les compétences. Au Cameroun comme ailleurs, la cooptation est très utilisée mais ne doit pas remplacer l'objectivité.\n\n💡 Le coût d'un mauvais recrutement est élevé : formation perdue, baisse de productivité, nouveau recrutement à refaire.",
                    'questions' => [
                        ['question' => "Quel document sert de base à tout recrutement ?", 'options' => ['Le bulletin de paie', 'La fiche de poste', 'Le contrat de travail', 'Le règlement intérieur'], 'correct' => [1], 'explanation' => "La fiche de poste définit le besoin (missions, compétences) et oriente tout le processus."],
                        ['question' => "Sur quel critère le recrutement doit-il principalement reposer ?", 'options' => ['L\'origine ethnique du candidat', 'Les relations familiales', 'Les compétences et l\'adéquation au poste', 'L\'âge du candidat'], 'correct' => [2], 'explanation' => "Le recrutement doit être objectif et fondé sur les compétences, pour éviter toute discrimination."],
                        ['question' => "Quelle est la première étape du processus de recrutement ?", 'options' => ['Faire passer l\'entretien', 'Définir le besoin et la fiche de poste', 'Signer le contrat', 'Diffuser l\'annonce'], 'correct' => [1], 'explanation' => "On commence toujours par définir précisément le besoin avant de chercher des candidats."],
                    ],
                ],
                [
                    'title' => 'Niveau 3 — L\'intégration (onboarding)',
                    'subtitle' => 'Réussir l\'accueil des nouveaux employés',
                    'xp_reward' => 120,
                    'content' => "🎯 **Objectif** : comprendre pourquoi et comment bien intégrer un nouvel employé.\n\n💡 L'intégration (ou **onboarding**) commence dès la signature du contrat et dure souvent les premiers mois. Une intégration ratée est l'une des premières causes de départ précoce.\n\n✅ Les étapes d'un bon onboarding :\n\n• **Avant l'arrivée** : préparer le poste de travail, les accès, prévenir l'équipe\n• **Le jour J** : accueil chaleureux, visite des locaux, présentation de l'équipe\n• **La première semaine** : remise du livret d'accueil, explication des règles et outils\n• **Le suivi** : un parrain/tuteur, des points réguliers, un bilan en fin de période d'essai\n\n⚠️ Ne jamais laisser un nouvel arrivant « se débrouiller seul ». Un employé bien accueilli devient productif plus vite et reste plus longtemps.\n\n💡 Astuce : un **livret d'accueil** présentant l'entreprise, l'organigramme et les procédures facilite grandement l'intégration. Dans une entreprise de Yaoundé, désigner un parrain au sein de l'équipe accélère l'adaptation culturelle.",
                    'questions' => [
                        ['question' => "Quel est l\'objectif principal de l\'onboarding ?", 'options' => ['Réduire le salaire du nouvel employé', 'Faciliter l\'adaptation et la fidélisation du nouvel employé', 'Évaluer la rentabilité de l\'entreprise', 'Remplacer le contrat de travail'], 'correct' => [1], 'explanation' => "L'intégration vise à rendre le nouvel employé opérationnel et à le fidéliser dès le départ."],
                        ['question' => "Quel document facilite l\'intégration en présentant l\'entreprise et ses procédures ?", 'options' => ['Le bulletin de paie', 'Le livret d\'accueil', 'La déclaration fiscale', 'Le bilan comptable'], 'correct' => [1], 'explanation' => "Le livret d'accueil regroupe les informations utiles au nouvel arrivant."],
                        ['question' => "Quelles pratiques favorisent une bonne intégration ? (plusieurs réponses)", 'options' => ['Désigner un parrain ou tuteur', 'Préparer le poste avant l\'arrivée', 'Laisser le nouvel employé se débrouiller seul', 'Organiser des points de suivi réguliers'], 'correct' => [0,1,3], 'explanation' => "Parrainage, préparation du poste et suivi sont des bonnes pratiques ; laisser seul est à proscrire."],
                    ],
                ],
                [
                    'title' => 'Niveau 4 — Le contrat de travail',
                    'subtitle' => 'Connaître les types de contrats et leurs règles',
                    'xp_reward' => 130,
                    'content' => "🎯 **Objectif** : distinguer les principaux types de contrats et leurs caractéristiques.\n\n💡 Le contrat de travail est l'accord qui lie l'employeur et le salarié. Au Cameroun (Code du travail), on distingue principalement :\n\n• **CDI** (Contrat à Durée Indéterminée) : sans terme fixe, c'est le contrat de référence\n• **CDD** (Contrat à Durée Déterminée) : pour un besoin temporaire, avec une date de fin\n• **Contrat à temps partiel** : durée de travail inférieure à la durée légale\n• **Contrat de stage / apprentissage** : à visée formative\n\n✅ Éléments obligatoires d'un contrat :\n\n| Élément | Exemple |\n|---------|---------|\n| Identité des parties | Employeur et salarié |\n| Poste et missions | Comptable junior |\n| Rémunération | Salaire brut mensuel |\n| Durée du travail | 40h/semaine |\n| Date de prise de fonction | 01/07/2026 |\n\n⚠️ La **période d'essai** permet à chaque partie de rompre le contrat sans indemnité. Sa durée est encadrée par la loi et la convention collective.\n\n💡 Le CDD ne peut pas être utilisé pour un emploi durable lié à l'activité normale de l'entreprise.",
                    'questions' => [
                        ['question' => "Quel contrat est considéré comme le contrat de référence, sans date de fin ?", 'options' => ['Le CDD', 'Le CDI', 'Le contrat de stage', 'Le contrat saisonnier'], 'correct' => [1], 'explanation' => "Le CDI est le contrat à durée indéterminée, sans terme fixe, et constitue la norme."],
                        ['question' => "À quoi sert la période d\'essai ?", 'options' => ['À augmenter automatiquement le salaire', 'À permettre à chaque partie de rompre le contrat sans indemnité', 'À éviter de payer le salarié', 'À prolonger indéfiniment le contrat'], 'correct' => [1], 'explanation' => "La période d'essai permet de vérifier l'adéquation et de rompre librement le contrat."],
                        ['question' => "Le CDD est approprié pour :", 'options' => ['Un emploi permanent lié à l\'activité normale', 'Un besoin temporaire et précis', 'Tous les postes de l\'entreprise', 'Éviter de payer les charges sociales'], 'correct' => [1], 'explanation' => "Le CDD répond à un besoin temporaire ; il ne peut remplacer un emploi durable."],
                    ],
                ],
                [
                    'title' => 'Niveau 5 — L\'administration du personnel',
                    'subtitle' => 'Gérer les dossiers, congés et obligations',
                    'xp_reward' => 140,
                    'content' => "🎯 **Objectif** : maîtriser la gestion administrative quotidienne du personnel.\n\n💡 L'administration du personnel regroupe toutes les tâches de suivi du salarié, de l'embauche au départ :\n\n• **Dossier individuel** : contrat, pièces d'identité, diplômes, coordonnées\n• **Gestion des temps** : présences, absences, heures supplémentaires\n• **Congés** : congés payés, maladie, maternité\n• **Déclarations sociales** : immatriculation à la caisse de sécurité sociale (CNPS au Cameroun)\n• **Discipline** : avertissements, sanctions, dans le respect du règlement intérieur\n\n✅ Tenir des **dossiers à jour et confidentiels** est une obligation. Les données personnelles doivent être protégées.\n\n⚠️ À l'embauche, certaines formalités sont obligatoires : déclaration auprès de la caisse sociale, visite médicale, registre du personnel. Les oublier expose l'entreprise à des sanctions.\n\n💡 Schéma du cycle de vie administratif :\n\nEmbauche → Suivi (temps, congés, paie) → Évolutions (avenants) → Départ (solde de tout compte, certificat de travail)\n\nUn bon classement (numérique ou papier) évite les litiges et facilite les contrôles.",
                    'questions' => [
                        ['question' => "Quel organisme gère la sécurité sociale des salariés au Cameroun ?", 'options' => ['La CNPS', 'La BEAC', 'L\'OHADA', 'La douane'], 'correct' => [0], 'explanation' => "La CNPS (Caisse Nationale de Prévoyance Sociale) gère la sécurité sociale au Cameroun."],
                        ['question' => "Quel document est remis au salarié lors de son départ de l\'entreprise ?", 'options' => ['La fiche de poste', 'Le certificat de travail', 'Le livret d\'accueil', 'L\'organigramme'], 'correct' => [1], 'explanation' => "Le certificat de travail atteste de l'emploi occupé et est remis lors du départ."],
                        ['question' => "Quelle est une obligation concernant les dossiers du personnel ?", 'options' => ['Les rendre publics', 'Les détruire chaque mois', 'Les tenir à jour et confidentiels', 'Les confier aux clients'], 'correct' => [2], 'explanation' => "Les dossiers doivent être tenus à jour et leur confidentialité protégée."],
                    ],
                ],
                [
                    'title' => 'Niveau 6 — Notions de paie',
                    'subtitle' => 'Comprendre le bulletin et le calcul du salaire',
                    'xp_reward' => 150,
                    'content' => "🎯 **Objectif** : comprendre les notions essentielles de la paie et lire un bulletin de salaire.\n\n💡 Le **salaire brut** est la rémunération avant déductions. Le **salaire net** est ce que touche réellement le salarié, après retenues.\n\n✅ Logique générale :\n\nSalaire brut − Cotisations sociales − Impôts (IRPP) = Salaire net\n\nÉléments du bulletin :\n\n• **Salaire de base** : fixé par le contrat\n• **Primes et indemnités** : ancienneté, transport, logement\n• **Heures supplémentaires** : majorées\n• **Cotisations sociales** : part salariale (CNPS) retenue sur le brut\n• **Charges patronales** : payées par l'employeur, en plus du brut\n\n⚠️ Ne pas confondre **part salariale** (retenue sur le salarié) et **part patronale** (coût supplémentaire pour l'employeur). Le coût total employeur = salaire brut + charges patronales.\n\n💡 Exemple simplifié : pour un brut de 200 000 FCFA, si les cotisations salariales sont de 8 400 FCFA et l'IRPP de 10 000 FCFA, le net est d'environ 181 600 FCFA. Le paiement peut se faire par virement bancaire ou mobile money.",
                    'questions' => [
                        ['question' => "Comment passe-t-on du salaire brut au salaire net ?", 'options' => ['En ajoutant les cotisations', 'En retranchant les cotisations sociales et l\'impôt', 'En multipliant par deux', 'En ajoutant les charges patronales'], 'correct' => [1], 'explanation' => "Le net s'obtient en déduisant du brut les cotisations sociales et l'impôt sur le revenu."],
                        ['question' => "Quelles sont des composantes possibles du bulletin de paie ? (plusieurs réponses)", 'options' => ['Salaire de base', 'Primes et indemnités', 'Cotisations sociales', 'Le chiffre d\'affaires de l\'entreprise'], 'correct' => [0,1,2], 'explanation' => "Salaire de base, primes et cotisations figurent sur le bulletin ; le chiffre d'affaires n'y figure pas."],
                        ['question' => "Le coût total d\'un salarié pour l\'employeur correspond à :", 'options' => ['Le salaire net uniquement', 'Le salaire brut plus les charges patronales', 'Le salaire de base seulement', 'Les primes uniquement'], 'correct' => [1], 'explanation' => "Le coût employeur inclut le salaire brut et les charges patronales qui s'y ajoutent."],
                    ],
                ],
                [
                    'title' => 'Niveau 7 — Compétences et formation',
                    'subtitle' => 'Développer le capital humain',
                    'xp_reward' => 160,
                    'content' => "🎯 **Objectif** : piloter la gestion des compétences et le plan de formation.\n\n💡 Une **compétence** combine savoir (connaissances), savoir-faire (pratique) et savoir-être (attitudes). Identifier et développer les compétences est essentiel pour rester performant.\n\n✅ Démarche de gestion des compétences :\n\n1. **Identifier** les compétences requises par poste (référentiel de compétences)\n2. **Évaluer** les compétences existantes\n3. **Analyser l'écart** entre le requis et l'existant\n4. **Combler l'écart** par la formation, le mentorat ou le recrutement\n\n💡 Le **plan de formation** organise les actions de formation sur une période (souvent annuelle). Il répond aux besoins de l'entreprise et aux souhaits d'évolution des salariés.\n\n⚠️ La formation est un investissement, pas une dépense. Former ses équipes améliore la productivité, la qualité et la fidélisation.\n\n💡 Exemples de formats : formation en présentiel, e-learning, formation sur le tas, coaching. Au Cameroun, le développement du numérique permet désormais des formations en ligne accessibles depuis Douala ou les zones rurales.",
                    'questions' => [
                        ['question' => "Une compétence combine généralement :", 'options' => ['Uniquement des connaissances théoriques', 'Savoir, savoir-faire et savoir-être', 'Seulement l\'expérience', 'Uniquement les diplômes'], 'correct' => [1], 'explanation' => "Une compétence associe connaissances, pratique et attitudes (savoir, savoir-faire, savoir-être)."],
                        ['question' => "Qu\'est-ce qu\'un plan de formation ?", 'options' => ['Un document organisant les actions de formation sur une période', 'Le contrat de travail', 'Le bulletin de paie annuel', 'La déclaration sociale'], 'correct' => [0], 'explanation' => "Le plan de formation planifie les actions de formation pour répondre aux besoins."],
                        ['question' => "Comment combler un écart de compétences ? (plusieurs réponses)", 'options' => ['Par la formation', 'Par le mentorat ou coaching', 'En ignorant le problème', 'Par un recrutement ciblé'], 'correct' => [0,1,3], 'explanation' => "Formation, mentorat et recrutement comblent les écarts ; ignorer le problème ne résout rien."],
                    ],
                ],
                [
                    'title' => 'Niveau 8 — Évaluation et entretiens',
                    'subtitle' => 'Mesurer la performance et accompagner',
                    'xp_reward' => 170,
                    'content' => "🎯 **Objectif** : conduire des entretiens d'évaluation utiles et objectifs.\n\n💡 L'**entretien annuel d'évaluation** est un moment d'échange entre le salarié et son manager pour faire le bilan de l'année, fixer des objectifs et discuter de l'évolution.\n\n✅ Bonnes pratiques :\n\n• Préparer l'entretien (bilan factuel, objectifs précédents)\n• Évaluer sur des **critères objectifs et mesurables** (pas de favoritisme)\n• Écouter le salarié et co-construire les objectifs (méthode SMART : Spécifique, Mesurable, Atteignable, Réaliste, Temporel)\n• Définir un plan d'action et de développement\n\n💡 À distinguer de l'**entretien professionnel**, centré sur les perspectives d'évolution et de carrière (et non sur la performance de l'année).\n\n⚠️ Une évaluation mal menée (subjective, sans préparation, à sens unique) démotive et crée des tensions. L'évaluation n'est pas une sanction mais un outil de progrès.\n\n💡 Exemple d'objectif SMART : « Augmenter le taux de satisfaction client de 70 % à 85 % d'ici décembre 2026 ».",
                    'questions' => [
                        ['question' => "Que signifie l\'acronyme SMART pour un objectif ?", 'options' => ['Simple, Moderne, Aléatoire, Rapide, Théorique', 'Spécifique, Mesurable, Atteignable, Réaliste, Temporel', 'Stratégique, Mensuel, Annuel, Régulier, Total', 'Sûr, Modéré, Abstrait, Relatif, Tactique'], 'correct' => [1], 'explanation' => "SMART signifie Spécifique, Mesurable, Atteignable, Réaliste et Temporel."],
                        ['question' => "Sur quoi doit reposer une évaluation de qualité ?", 'options' => ['Des impressions subjectives', 'Le favoritisme', 'Des critères objectifs et mesurables', 'L\'ancienneté uniquement'], 'correct' => [2], 'explanation' => "Une évaluation juste se fonde sur des critères objectifs et mesurables."],
                        ['question' => "L\'entretien professionnel est principalement centré sur :", 'options' => ['Les perspectives d\'évolution et de carrière', 'Le calcul de la paie', 'La gestion des congés', 'La rédaction du contrat'], 'correct' => [0], 'explanation' => "L'entretien professionnel porte sur l'évolution et les perspectives de carrière du salarié."],
                    ],
                ],
                [
                    'title' => 'Niveau 9 — Droit du travail, climat social et motivation',
                    'subtitle' => 'Appliquer la loi et entretenir un bon climat',
                    'xp_reward' => 185,
                    'content' => "🎯 **Objectif** : appliquer les règles du droit du travail et favoriser un bon climat social.\n\n💡 Le **droit du travail** encadre les relations employeur-salarié. Au Cameroun, il repose sur le Code du travail et, pour les aspects sociaux des entreprises, sur les principes de l'OHADA. Le **règlement intérieur** fixe les règles internes (horaires, discipline, sécurité).\n\n✅ Points clés :\n\n• Respect du **salaire minimum** (SMIG) et de la durée légale du travail\n• Procédures encadrées pour les sanctions et les licenciements\n• Représentation du personnel (délégués) et dialogue social\n• Santé et sécurité au travail\n\n💡 Le **climat social** désigne l'ambiance générale et la qualité des relations dans l'entreprise. Pour le maintenir :\n\n• Communiquer de façon transparente\n• Reconnaître et valoriser le travail\n• Gérer les conflits rapidement et équitablement\n\n⚠️ La **motivation** ne dépend pas que du salaire. La théorie de Maslow et celle de Herzberg montrent l'importance de la reconnaissance, des conditions de travail et du sens donné au travail.\n\n💡 Un climat social dégradé entraîne absentéisme, turnover et baisse de productivité.",
                    'questions' => [
                        ['question' => "Quel document interne fixe les règles de discipline, d\'horaires et de sécurité ?", 'options' => ['Le bulletin de paie', 'Le règlement intérieur', 'Le certificat de travail', 'La fiche de poste'], 'correct' => [1], 'explanation' => "Le règlement intérieur définit les règles internes de l'entreprise."],
                        ['question' => "Qu\'est-ce qui influence la motivation au-delà du salaire ? (plusieurs réponses)", 'options' => ['La reconnaissance du travail', 'Les conditions de travail', 'Le sens donné au travail', 'L\'augmentation systématique des amendes'], 'correct' => [0,1,2], 'explanation' => "Reconnaissance, conditions de travail et sens motivent ; les amendes ne sont pas un facteur de motivation."],
                        ['question' => "Que désigne le SMIG ?", 'options' => ['Le salaire maximum autorisé', 'Le salaire minimum garanti', 'Une cotisation sociale', 'Un type de contrat'], 'correct' => [1], 'explanation' => "Le SMIG est le salaire minimum interprofessionnel garanti que l'employeur doit respecter."],
                    ],
                ],
                [
                    'title' => 'Niveau 10 — GPEC et marque employeur',
                    'subtitle' => 'Anticiper l\'avenir et attirer les talents',
                    'xp_reward' => 200,
                    'content' => "🎯 **Objectif** : maîtriser la GPEC et la marque employeur, leviers stratégiques de la fonction RH.\n\n💡 La **GPEC** (Gestion Prévisionnelle des Emplois et des Compétences) consiste à anticiper les besoins futurs en emplois et compétences, en fonction de la stratégie de l'entreprise et des évolutions du marché.\n\n✅ Démarche GPEC :\n\n• Analyser les emplois actuels et leur évolution probable\n• Identifier les compétences qui seront nécessaires demain (ex : numérique, IA)\n• Réduire l'écart par la formation, la mobilité interne ou le recrutement\n• Construire des plans de succession pour les postes clés\n\n💡 La **marque employeur** est l'image de l'entreprise en tant qu'employeur. Une marque forte attire les meilleurs talents et fidélise les équipes. Elle se construit par :\n\n• Une bonne expérience collaborateur\n• Une communication authentique (réseaux sociaux, témoignages)\n• Des valeurs claires et une réputation positive\n\n⚠️ Une marque employeur ne se décrète pas : elle reflète la réalité vécue par les salariés.\n\n🏆 **Félicitations !** Tu as parcouru l'ensemble du cycle RH, du recrutement à la stratégie. Tu disposes désormais des bases solides pour exercer comme **Gestionnaire RH, Chargé de recrutement, Responsable paie, Responsable formation** ou évoluer vers **Responsable RH** voire **Directeur des Ressources Humaines (DRH)**. Les RH sont un métier d'avenir au Cameroun et en Afrique : continue à te former, notamment sur le digital RH et l'analyse de données. Bonne carrière !",
                    'questions' => [
                        ['question' => "Que signifie GPEC ?", 'options' => ['Gestion Prévisionnelle des Emplois et des Compétences', 'Gestion Permanente des Employés et des Cadres', 'Gestion des Paies et des Cotisations', 'Groupe de Pilotage des Carrières'], 'correct' => [0], 'explanation' => "GPEC signifie Gestion Prévisionnelle des Emplois et des Compétences."],
                        ['question' => "Comment se construit une marque employeur forte ? (plusieurs réponses)", 'options' => ['Une bonne expérience collaborateur', 'Une communication authentique', 'Des valeurs claires et une réputation positive', 'En cachant systématiquement la réalité de l\'entreprise'], 'correct' => [0,1,2], 'explanation' => "Expérience collaborateur, communication authentique et valeurs claires bâtissent la marque ; le mensonge la fragilise."],
                        ['question' => "Quel est l\'objectif principal de la GPEC ?", 'options' => ['Calculer les salaires mensuels', 'Anticiper les besoins futurs en emplois et compétences', 'Rédiger les contrats de travail', 'Gérer uniquement les congés'], 'correct' => [1], 'explanation' => "La GPEC vise à anticiper les besoins futurs en emplois et compétences selon la stratégie."],
                    ],
                ],
            ],
        ]);

        $this->command->info('  ✓ Roadmap Gestion des Ressources Humaines créée (10 niveaux).');
    }
}
