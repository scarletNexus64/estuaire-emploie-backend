<?php

namespace Database\Seeders\Roadmaps;

use Illuminate\Database\Seeder;

/**
 * Roadmap Soins infirmiers — du rôle de l'infirmier aux soins techniques,
 * hygiène, surveillance clinique et éthique. Seeder de référence "non-tech".
 */
class SoinsInfirmiersRoadmapSeeder extends Seeder
{
    use RoadmapSeederHelper;

    public function run(): void
    {
        $this->createRoadmap([
            'title' => 'Deviens Infirmier(ère)',
            'slug' => 'soins-infirmiers',
            'domain' => 'sante',
            'description' => "Découvre le métier d'infirmier : rôle dans l'équipe de soins, hygiène et asepsie, prise des constantes, administration des médicaments, surveillance clinique et relation avec le patient. Un parcours pour comprendre les fondamentaux des soins.",
            'objectives' => "Comprendre le rôle et les responsabilités de l'infirmier\nMaîtriser l'hygiène des mains et l'asepsie\nPrendre et interpréter les constantes vitales\nAdministrer les médicaments en sécurité (règle des 5 B)\nSurveiller un patient et transmettre les informations\nAdopter une posture éthique et bienveillante",
            'icon' => '🩺',
            'color' => '#EF4444',
            'difficulty' => 'beginner',
            'required_packs' => [],
            'pass_threshold' => 70,
            'order' => 10,
            'levels' => [
                [
                    'title' => 'Niveau 1 — Le rôle de l\'infirmier',
                    'subtitle' => 'Comprendre le métier',
                    'xp_reward' => 100,
                    'content' => "🎯 **Objectif** : comprendre ce que fait un infirmier au quotidien.\n\n💡 L'infirmier est un professionnel de santé qui **dispense des soins** sur prescription médicale (rôle prescrit) mais aussi de sa propre initiative (rôle propre : hygiène, confort, surveillance, éducation du patient).\n\n✅ Ses grandes missions :\n• Réaliser les soins techniques (pansements, injections, perfusions)\n• Surveiller l'état de santé (constantes, douleur, comportement)\n• Administrer les traitements prescrits\n• Accompagner et informer le patient et sa famille\n• Transmettre les informations à l'équipe (oral + dossier de soins)\n\n💡 L'infirmier travaille en **collaboration** : médecins, aides-soignants, kinés, pharmaciens. Le **secret professionnel** est une obligation absolue.",
                    'questions' => [
                        [
                            'question' => 'Que désigne le « rôle propre » de l\'infirmier ?',
                            'options' => ['Les soins qu\'il décide et réalise de sa propre initiative', 'Uniquement les soins sur prescription médicale', 'La gestion administrative de l\'hôpital', 'Le diagnostic médical'],
                            'correct' => [0],
                            'explanation' => 'Le rôle propre regroupe les soins relevant de l\'autonomie de l\'infirmier (hygiène, confort, surveillance, éducation).',
                        ],
                        [
                            'question' => 'Le secret professionnel pour un infirmier est…',
                            'options' => ['Optionnel selon les services', 'Une obligation absolue', 'Limité aux médecins', 'Valable seulement à l\'hôpital'],
                            'correct' => [1],
                            'explanation' => 'Le secret professionnel s\'impose à tout soignant, partout et toujours.',
                        ],
                        [
                            'question' => 'Quelles missions relèvent de l\'infirmier ? (plusieurs réponses)',
                            'options' => ['Surveiller l\'état du patient', 'Poser un diagnostic médical', 'Administrer les traitements prescrits', 'Transmettre à l\'équipe'],
                            'correct' => [0, 2, 3],
                            'explanation' => 'Le diagnostic médical relève du médecin ; le reste fait partie des missions infirmières.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 2 — Hygiène des mains & asepsie',
                    'subtitle' => 'La base de la prévention',
                    'xp_reward' => 120,
                    'content' => "🎯 **Objectif** : comprendre pourquoi et comment se laver les mains.\n\n💡 L'**hygiène des mains** est la mesure n°1 pour éviter les **infections nosocomiales** (infections contractées à l'hôpital). La majorité des microbes se transmettent par les mains.\n\n✅ Deux méthodes :\n• **Friction hydro-alcoolique** (solution SHA) : la plus efficace et rapide, sur mains visuellement propres.\n• **Lavage au savon** : si mains souillées ou après contact avec des liquides biologiques.\n\n✅ Les « 5 indications » de l'OMS — se désinfecter les mains :\n1. Avant de toucher un patient\n2. Avant un geste aseptique\n3. Après un risque d'exposition à un liquide biologique\n4. Après avoir touché un patient\n5. Après avoir touché l'environnement du patient\n\n💡 **Asepsie** = ensemble des mesures pour empêcher l'arrivée de microbes (matériel stérile). **Antisepsie** = détruire les microbes sur la peau/plaie (antiseptique).",
                    'questions' => [
                        [
                            'question' => 'Quelle est la mesure la plus importante pour prévenir les infections nosocomiales ?',
                            'options' => ['Porter une blouse', 'L\'hygiène des mains', 'Aérer la chambre', 'Changer les draps'],
                            'correct' => [1],
                            'explanation' => 'L\'hygiène des mains est la mesure de prévention n°1 reconnue par l\'OMS.',
                        ],
                        [
                            'question' => 'Quand privilégie-t-on le lavage au savon plutôt que la friction hydro-alcoolique ?',
                            'options' => ['Toujours', 'Quand les mains sont visiblement souillées', 'Jamais', 'Uniquement le matin'],
                            'correct' => [1],
                            'explanation' => 'La SHA ne convient pas sur des mains souillées : on lave alors à l\'eau et au savon.',
                        ],
                        [
                            'question' => 'Quelle est la différence entre asepsie et antisepsie ?',
                            'options' => ['Aucune', 'L\'asepsie prévient l\'arrivée des microbes, l\'antisepsie les détruit sur la peau', 'L\'asepsie concerne les médicaments', 'L\'antisepsie est réservée au bloc opératoire'],
                            'correct' => [1],
                            'explanation' => 'Asepsie = prévention (matériel stérile) ; antisepsie = destruction sur tissu vivant.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 3 — Les constantes vitales',
                    'subtitle' => 'Mesurer et surveiller',
                    'xp_reward' => 130,
                    'content' => "🎯 **Objectif** : connaître les paramètres vitaux et leurs valeurs normales chez l'adulte.\n\n✅ Les constantes vitales principales :\n• **Température** : 36,5 – 37,5 °C. Au-delà de 38 °C = fièvre.\n• **Pouls (fréquence cardiaque)** : 60 – 100 battements/min au repos.\n• **Tension artérielle** : ≈ 120/80 mmHg. (systolique/diastolique)\n• **Fréquence respiratoire** : 12 – 20 cycles/min.\n• **Saturation en oxygène (SpO₂)** : ≥ 95 %.\n\n💡 Une variation brutale d'une constante est un **signal d'alerte** : on prévient l'équipe. Ex. SpO₂ < 90 % = détresse respiratoire potentielle.\n\n✅ On note toujours les constantes dans le **dossier de soins** avec l'heure : la traçabilité est essentielle.",
                    'questions' => [
                        [
                            'question' => 'Quelle est la fréquence cardiaque normale d\'un adulte au repos ?',
                            'options' => ['30 à 50 /min', '60 à 100 /min', '100 à 140 /min', '140 à 180 /min'],
                            'correct' => [1],
                            'explanation' => 'Le pouls normal de l\'adulte au repos se situe entre 60 et 100 battements/min.',
                        ],
                        [
                            'question' => 'À partir de quelle température parle-t-on de fièvre ?',
                            'options' => ['37 °C', '37,5 °C', '38 °C', '39 °C'],
                            'correct' => [2],
                            'explanation' => 'La fièvre est généralement définie à partir de 38 °C.',
                        ],
                        [
                            'question' => 'Une SpO₂ à 88 % chez un patient doit faire…',
                            'options' => ['Ignorer, c\'est normal', 'Alerter l\'équipe : signe de détresse respiratoire', 'Attendre le lendemain', 'Donner à boire'],
                            'correct' => [1],
                            'explanation' => 'Une saturation < 95 %, a fortiori < 90 %, est un signal d\'alerte à transmettre.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 4 — Administration des médicaments',
                    'subtitle' => 'La règle des 5 B',
                    'xp_reward' => 150,
                    'content' => "🎯 **Objectif** : administrer un médicament en toute sécurité.\n\n💡 L'erreur médicamenteuse peut être grave. Pour l'éviter, on applique la **règle des 5 B** avant toute administration :\n1. **Bon patient** (vérifier l'identité)\n2. **Bon médicament**\n3. **Bonne dose**\n4. **Bonne voie** (orale, IV, IM, sous-cutanée…)\n5. **Bon moment** (horaire prescrit)\n\n✅ On vérifie toujours la **prescription médicale** écrite, la **date de péremption** et l'**aspect** du produit.\n\n✅ Principales voies d'administration :\n• **PO** (per os) : par la bouche\n• **IV** : intraveineuse\n• **IM** : intramusculaire\n• **SC** : sous-cutanée\n\n⚠️ En cas de doute sur une prescription (dose inhabituelle, illisible), **ne jamais administrer** : on contacte le prescripteur.",
                    'questions' => [
                        [
                            'question' => 'Que vérifie la « règle des 5 B » ?',
                            'options' => ['Bon patient, bon médicament, bonne dose, bonne voie, bon moment', 'Cinq signatures du médecin', 'Cinq prises de tension', 'Cinq jours de traitement'],
                            'correct' => [0],
                            'explanation' => 'Les 5 B sécurisent chaque administration : patient, médicament, dose, voie, moment.',
                        ],
                        [
                            'question' => 'Que signifie la voie « PO » ?',
                            'options' => ['Par voie intraveineuse', 'Par la bouche (per os)', 'Par voie sous-cutanée', 'Par perfusion osseuse'],
                            'correct' => [1],
                            'explanation' => 'PO (per os) = administration par voie orale.',
                        ],
                        [
                            'question' => 'Face à une prescription illisible ou une dose inhabituelle, l\'infirmier doit…',
                            'options' => ['Administrer quand même', 'Deviner la dose', 'Ne pas administrer et contacter le prescripteur', 'Demander au patient'],
                            'correct' => [2],
                            'explanation' => 'En cas de doute, on n\'administre jamais : on vérifie auprès du prescripteur.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 5 — Prévention des infections & précautions standard',
                    'subtitle' => 'Protéger patient et soignant',
                    'xp_reward' => 150,
                    'content' => "🎯 **Objectif** : appliquer les précautions standard lors des soins.\n\n💡 Les **précautions standard** s'appliquent pour TOUS les patients, quel que soit leur statut, car on ne peut pas savoir qui est porteur d'un germe.\n\n✅ Elles comprennent :\n• Hygiène des mains avant/après chaque soin\n• **Port de gants** dès qu'il y a risque de contact avec du sang ou un liquide biologique\n• **Masque, lunettes, surblouse** en cas de projection\n• Gestion sécurisée des **déchets** (aiguilles dans le collecteur DASRI jaune)\n\n⚠️ Les **AES** (accidents d'exposition au sang) — piqûre, coupure — sont un risque majeur (VIH, hépatites). On ne **recapuchonne jamais** une aiguille ; on la jette immédiatement dans le collecteur.\n\n✅ En cas d'AES : faire saigner, laver, désinfecter, déclarer immédiatement.",
                    'questions' => [
                        [
                            'question' => 'Les précautions standard s\'appliquent…',
                            'options' => ['Seulement aux patients contagieux connus', 'À tous les patients sans exception', 'Uniquement au bloc', 'Le week-end seulement'],
                            'correct' => [1],
                            'explanation' => 'On les applique pour tous, car le statut infectieux n\'est pas toujours connu.',
                        ],
                        [
                            'question' => 'Que faire d\'une aiguille usagée ?',
                            'options' => ['La recapuchonner', 'La jeter immédiatement dans le collecteur DASRI', 'La poser sur le plateau', 'La rincer et réutiliser'],
                            'correct' => [1],
                            'explanation' => 'On ne recapuchonne jamais : risque d\'AES. Elimination immédiate dans le collecteur.',
                        ],
                        [
                            'question' => 'Que signifie AES ?',
                            'options' => ['Accident d\'Exposition au Sang', 'Acte d\'Évaluation des Soins', 'Aide à l\'Examen Sanguin', 'Agent d\'Entretien Stérile'],
                            'correct' => [0],
                            'explanation' => 'Un AES est un accident exposant au sang (piqûre, coupure, projection).',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 6 — La démarche de soins',
                    'subtitle' => 'Raisonner pour mieux soigner',
                    'xp_reward' => 160,
                    'content' => "🎯 **Objectif** : structurer la prise en charge d'un patient.\n\n💡 La **démarche de soins** est une méthode en étapes pour organiser les soins de façon personnalisée :\n1. **Recueil de données** (observation, dossier, entretien)\n2. **Analyse** : identifier les problèmes / diagnostics infirmiers\n3. **Planification** : fixer des objectifs et choisir les actions\n4. **Réalisation** des soins\n5. **Évaluation** : le résultat est-il atteint ? On réajuste.\n\n✅ Un **diagnostic infirmier** décrit une réaction du patient (ex. « risque d'escarre lié à l'immobilité »), différent du diagnostic médical (la maladie).\n\n💡 Les **transmissions** ciblées (méthode DAR : Données, Actions, Résultats) assurent la continuité des soins entre équipes.",
                    'questions' => [
                        [
                            'question' => 'Quelle est la première étape de la démarche de soins ?',
                            'options' => ['L\'évaluation', 'Le recueil de données', 'La réalisation', 'La planification'],
                            'correct' => [1],
                            'explanation' => 'On commence par recueillir les données (observation, dossier, entretien).',
                        ],
                        [
                            'question' => 'Un diagnostic infirmier décrit…',
                            'options' => ['La maladie diagnostiquée par le médecin', 'Une réaction/un problème du patient pris en charge par l\'infirmier', 'Le traitement médicamenteux', 'Le résultat d\'une analyse de sang'],
                            'correct' => [1],
                            'explanation' => 'Le diagnostic infirmier porte sur les réactions du patient, pas sur la maladie.',
                        ],
                        [
                            'question' => 'Que désigne la méthode DAR des transmissions ?',
                            'options' => ['Données, Actions, Résultats', 'Diagnostic, Antibiotique, Repos', 'Douleur, Alerte, Réanimation', 'Dossier, Admission, Retour'],
                            'correct' => [0],
                            'explanation' => 'DAR = Données, Actions, Résultats : structure des transmissions ciblées.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 7 — La relation soignant-soigné',
                    'subtitle' => 'Communication & bientraitance',
                    'xp_reward' => 160,
                    'content' => "🎯 **Objectif** : établir une relation de confiance avec le patient.\n\n💡 Soigner, ce n'est pas seulement un geste technique : c'est aussi une **relation humaine**. Un patient angoissé, mal informé ou irrespecté guérit moins bien.\n\n✅ Les attitudes clés :\n• **Écoute active** : laisser parler, reformuler, ne pas juger.\n• **Empathie** : comprendre le vécu du patient sans se laisser submerger.\n• **Information claire** : expliquer les soins avec des mots simples.\n• **Respect de la dignité** et de l'intimité (frapper avant d'entrer, préserver la pudeur).\n\n💡 Le **consentement** du patient est requis : il a le droit de refuser un soin (sauf urgence vitale). On l'informe alors des conséquences.\n\n✅ La **bientraitance** est une démarche active de respect et de bienveillance ; son contraire, la **maltraitance**, peut être passive (négligence).",
                    'questions' => [
                        [
                            'question' => 'Qu\'est-ce que l\'écoute active ?',
                            'options' => ['Parler le plus possible au patient', 'Écouter, reformuler et ne pas juger', 'Couper la parole pour gagner du temps', 'Donner son avis personnel'],
                            'correct' => [1],
                            'explanation' => 'L\'écoute active consiste à écouter réellement, reformuler et accueillir sans juger.',
                        ],
                        [
                            'question' => 'Un patient conscient refuse un soin non urgent. L\'infirmier…',
                            'options' => ['L\'impose de force', 'Respecte son refus après l\'avoir informé des conséquences', 'Appelle la sécurité', 'Le note comme guéri'],
                            'correct' => [1],
                            'explanation' => 'Le patient a le droit de refuser ; on informe et on respecte (hors urgence vitale).',
                        ],
                        [
                            'question' => 'La maltraitance peut être…',
                            'options' => ['Uniquement physique et volontaire', 'Aussi passive, par négligence', 'Impossible en milieu hospitalier', 'Sans conséquence'],
                            'correct' => [1],
                            'explanation' => 'La négligence (oubli de soins, manque d\'attention) est une forme de maltraitance passive.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 8 — Urgences & gestes qui sauvent',
                    'subtitle' => 'Réagir face à une détresse',
                    'xp_reward' => 180,
                    'content' => "🎯 **Objectif** : reconnaître une urgence vitale et réagir.\n\n💡 Face à une personne inconsciente, on évalue rapidement avec l'approche **PLS / chaîne de survie**.\n\n✅ Détresse vitale — on vérifie :\n• **Conscience** : la personne répond-elle ?\n• **Respiration** : respire-t-elle (regarder, écouter, sentir) ?\n\n✅ Conduite si la personne respire mais est inconsciente : la mettre en **PLS** (Position Latérale de Sécurité) pour éviter l'étouffement, et alerter les secours.\n\n✅ Si **arrêt cardiaque** (inconscient + ne respire pas) : alerter, commencer la **RCP** (massage cardiaque : 30 compressions / 2 insufflations) et utiliser un **défibrillateur (DAE)** dès que possible.\n\n💡 La **chaîne de survie** : Alerter → Masser → Défibriller → Soins spécialisés. Chaque minute sans réanimation diminue les chances de survie d'environ 10 %.",
                    'questions' => [
                        [
                            'question' => 'Une personne est inconsciente mais respire. Que faire ?',
                            'options' => ['Massage cardiaque', 'Position Latérale de Sécurité (PLS) et alerte', 'La faire boire', 'La laisser sur le dos sans surveillance'],
                            'correct' => [1],
                            'explanation' => 'Inconsciente + respire : PLS pour libérer les voies aériennes, puis alerter.',
                        ],
                        [
                            'question' => 'Quel est le rythme de compressions en RCP adulte ?',
                            'options' => ['10 compressions / 5 insufflations', '30 compressions / 2 insufflations', '5 compressions / 1 insufflation', '50 compressions / 10 insufflations'],
                            'correct' => [1],
                            'explanation' => 'Le standard est 30 compressions pour 2 insufflations chez l\'adulte.',
                        ],
                        [
                            'question' => 'Que signifie DAE ?',
                            'options' => ['Dispositif d\'Aide à l\'Endormissement', 'Défibrillateur Automatisé Externe', 'Détecteur d\'Arrêt d\'Eau', 'Dossier d\'Admission d\'Entrée'],
                            'correct' => [1],
                            'explanation' => 'Le DAE (défibrillateur) analyse le rythme cardiaque et délivre un choc si nécessaire.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 9 — Éthique, droit et responsabilité',
                    'subtitle' => 'Le cadre du métier',
                    'xp_reward' => 180,
                    'content' => "🎯 **Objectif** : connaître les règles déontologiques et la responsabilité de l'infirmier.\n\n💡 L'infirmier exerce dans un cadre **légal et éthique** strict. Il est responsable de ses actes.\n\n✅ Grands principes éthiques :\n• **Bienfaisance** : agir pour le bien du patient.\n• **Non-malfaisance** : « d'abord ne pas nuire ».\n• **Autonomie** : respecter les choix du patient.\n• **Justice** : soigner équitablement, sans discrimination.\n\n✅ Obligations professionnelles :\n• **Secret professionnel**\n• **Devoir d'information** et recueil du consentement\n• Soins consciencieux conformes aux données acquises de la science\n• **Traçabilité** : ce qui n'est pas écrit est réputé non fait\n\n⚠️ La **responsabilité** peut être engagée : civile (réparer un dommage), pénale (faute grave), disciplinaire (ordre professionnel).",
                    'questions' => [
                        [
                            'question' => 'Quel principe éthique signifie « d\'abord ne pas nuire » ?',
                            'options' => ['Bienfaisance', 'Non-malfaisance', 'Autonomie', 'Justice'],
                            'correct' => [1],
                            'explanation' => 'La non-malfaisance impose d\'éviter de causer un préjudice au patient.',
                        ],
                        [
                            'question' => 'En matière de traçabilité des soins, le principe est :',
                            'options' => ['« Ce qui n\'est pas écrit est réputé non fait »', 'On note seulement les erreurs', 'Les transmissions orales suffisent', 'On archive une fois par mois'],
                            'correct' => [0],
                            'explanation' => 'L\'absence de trace écrite équivaut, juridiquement, à une absence de soin.',
                        ],
                        [
                            'question' => 'Quelles formes de responsabilité peuvent être engagées ? (plusieurs réponses)',
                            'options' => ['Civile', 'Pénale', 'Disciplinaire', 'Aucune, l\'infirmier est protégé'],
                            'correct' => [0, 1, 2],
                            'explanation' => 'L\'infirmier peut voir sa responsabilité civile, pénale et disciplinaire engagée.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 10 — Spécialités & évolution de carrière',
                    'subtitle' => 'Construire son avenir',
                    'xp_reward' => 200,
                    'content' => "🎯 **Objectif** : découvrir les débouchés et spécialisations du métier.\n\n💡 Le métier d'infirmier offre de nombreuses **passerelles** et spécialisations après quelques années d'expérience.\n\n✅ Spécialités possibles :\n• **Infirmier de bloc opératoire (IBODE)**\n• **Infirmier anesthésiste (IADE)**\n• **Puériculture** (soins aux enfants)\n• **Santé au travail**, **santé publique**, **soins à domicile**\n\n✅ Lieux d'exercice variés : hôpital, clinique, dispensaire, école, entreprise, humanitaire, libéral.\n\n✅ Évolution : **cadre de santé** (encadrement d'équipe), **formateur** en institut, **pratique avancée** (IPA) avec des compétences élargies.\n\n💡 La **formation continue** est essentielle : la médecine évolue, l'infirmier doit actualiser ses connaissances tout au long de sa carrière.\n\n🏆 Bravo ! Tu connais maintenant les fondamentaux du métier d'infirmier. La pratique encadrée et la formation continue feront le reste.",
                    'questions' => [
                        [
                            'question' => 'Que désigne un IADE ?',
                            'options' => ['Infirmier Anesthésiste Diplômé d\'État', 'Infirmier Administratif', 'Institut d\'Aide à Domicile', 'Inspecteur d\'Activité'],
                            'correct' => [0],
                            'explanation' => 'L\'IADE est l\'infirmier spécialisé en anesthésie-réanimation.',
                        ],
                        [
                            'question' => 'Vers quel poste un infirmier peut-il évoluer pour encadrer une équipe ?',
                            'options' => ['Aide-soignant', 'Cadre de santé', 'Brancardier', 'Agent d\'accueil'],
                            'correct' => [1],
                            'explanation' => 'Le cadre de santé encadre et organise les équipes soignantes.',
                        ],
                        [
                            'question' => 'Pourquoi la formation continue est-elle essentielle pour un infirmier ?',
                            'options' => ['Ce n\'est pas nécessaire', 'Parce que les connaissances et pratiques médicales évoluent', 'Pour gagner des congés', 'Uniquement pour changer d\'hôpital'],
                            'correct' => [1],
                            'explanation' => 'La science médicale évolue : actualiser ses compétences garantit des soins sûrs.',
                        ],
                    ],
                ],
            ],
        ]);

        $this->command->info('  ✓ Roadmap Soins infirmiers créée (10 niveaux).');
    }
}
