<?php

namespace Database\Seeders\Roadmaps;

use Illuminate\Database\Seeder;

/**
 * Roadmap Santé Publique & Épidémiologie — comprendre la santé des populations, prévenir et gérer les épidémies.
 */
class SantePubliqueRoadmapSeeder extends Seeder
{
    use RoadmapSeederHelper;

    public function run(): void
    {
        $this->createRoadmap([
            'title' => 'Maîtrise la Santé Publique & l\'Épidémiologie',
            'slug' => 'sante-publique-epidemiologie',
            'domain' => 'sante',
            'description' => "Apprends à raisonner en santé publique : de la définition de la santé selon l'OMS jusqu'à la gestion d'une crise sanitaire. Tu découvriras les déterminants de la santé, les niveaux de prévention, les outils de l'épidémiologie, la surveillance des épidémies, la vaccination et la promotion de la santé. Une roadmap pensée pour le contexte africain francophone, avec des exemples concrets (Douala, Yaoundé, paludisme, choléra, mobile money pour la sensibilisation).",
            'objectives' => "Définir la santé et la santé publique selon l'OMS\nIdentifier les déterminants de la santé d'une population\nDistinguer prévention primaire, secondaire et tertiaire\nCalculer et interpréter incidence et prévalence\nComprendre la surveillance et l'investigation d'une épidémie\nMaîtriser les principes de la vaccination et de la couverture vaccinale\nConcevoir une action de promotion de la santé\nUtiliser les principaux indicateurs sanitaires\nCoordonner la réponse à une crise sanitaire",
            'icon' => '🩺',
            'color' => '#0EA5E9',
            'difficulty' => 'intermediate',
            'required_packs' => [],
            'pass_threshold' => 70,
            'order' => 20,
            'levels' => [
                [
                    'title' => 'Niveau 1 — Qu\'est-ce que la santé ?',
                    'subtitle' => 'La définition de l\'OMS',
                    'xp_reward' => 100,
                    'content' => "🎯 **Objectif** : comprendre ce qu'est la santé et la santé publique.\n\n💡 En 1946, l'**OMS** (Organisation Mondiale de la Santé) définit la santé comme :\n« un état de complet bien-être physique, mental et social, et pas seulement l'absence de maladie ou d'infirmité ».\n\nCette définition est révolutionnaire car elle ajoute trois dimensions :\n• physique (le corps fonctionne bien)\n• mentale (équilibre psychologique)\n• sociale (relations, intégration)\n\n✅ La **santé publique** s'intéresse à la santé des **populations** (et non d'un seul patient). Elle agit sur des groupes : un quartier de Douala, une région, un pays.\n\n⚠️ Différence clé : le médecin clinicien soigne **un individu malade** ; la santé publique cherche à **améliorer et protéger la santé du plus grand nombre** (eau potable, vaccination, hygiène).\n\nExemple : installer des points d'eau potable dans un quartier de Yaoundé relève de la santé publique, pas de la consultation individuelle.",
                    'questions' => [
                        ['question' => 'Selon l\'OMS, la santé est définie comme :', 'options' => ['L\'absence de maladie uniquement', 'Un état de complet bien-être physique, mental et social', 'Le fait de pouvoir travailler', 'Avoir accès à un hôpital'], 'correct' => [1], 'explanation' => 'La définition de l\'OMS (1946) inclut le bien-être physique, mental et social, au-delà de la simple absence de maladie.'],
                        ['question' => 'La santé publique s\'intéresse principalement à :', 'options' => ['Un patient unique', 'La santé des populations et des groupes', 'La chirurgie', 'La vente de médicaments'], 'correct' => [1], 'explanation' => 'La santé publique agit à l\'échelle des populations, contrairement à la médecine clinique centrée sur l\'individu.'],
                        ['question' => 'Quelles dimensions la définition de l\'OMS inclut-elle ? (plusieurs réponses)', 'options' => ['Physique', 'Mentale', 'Sociale', 'Financière'], 'correct' => [0, 1, 2], 'explanation' => 'L\'OMS retient trois dimensions : physique, mentale et sociale ; la dimension financière n\'en fait pas partie.'],
                    ],
                ],
                [
                    'title' => 'Niveau 2 — Les déterminants de la santé',
                    'subtitle' => 'Ce qui influence notre santé',
                    'xp_reward' => 110,
                    'content' => "🎯 **Objectif** : identifier ce qui détermine la santé d'une population.\n\n💡 Les **déterminants de la santé** sont les facteurs qui influencent l'état de santé. On les classe en grandes familles :\n\n• **Biologiques** : âge, sexe, hérédité (ex. drépanocytose fréquente en Afrique)\n• **Comportementaux** : alimentation, tabac, alcool, activité physique\n• **Environnementaux** : eau, air, assainissement, logement\n• **Socio-économiques** : revenu, éducation, emploi\n• **Système de santé** : accès aux soins, qualité, coût\n\n✅ Schéma : peu de personnes réalisent que l'**éducation** et le **revenu** pèsent souvent plus lourd que les soins eux-mêmes.\n\n| Déterminant | Exemple concret |\n|---|---|\n| Environnemental | Eau non potable → choléra |\n| Socio-économique | Faible revenu → mauvaise nutrition |\n| Comportemental | Moustiquaire utilisée → moins de paludisme |\n\n⚠️ Agir sur les déterminants (assainissement, scolarisation) a souvent plus d'impact que de multiplier les hôpitaux.",
                    'questions' => [
                        ['question' => 'L\'accès à l\'eau potable est un déterminant :', 'options' => ['Biologique', 'Environnemental', 'Génétique', 'Héréditaire'], 'correct' => [1], 'explanation' => 'L\'eau potable relève de l\'environnement et de l\'assainissement, un déterminant environnemental majeur.'],
                        ['question' => 'Lesquels sont des déterminants socio-économiques ? (plusieurs réponses)', 'options' => ['Le revenu', 'Le niveau d\'éducation', 'Le groupe sanguin', 'L\'emploi'], 'correct' => [0, 1, 3], 'explanation' => 'Revenu, éducation et emploi sont socio-économiques ; le groupe sanguin est biologique.'],
                        ['question' => 'Quel déterminant relève du comportement individuel ?', 'options' => ['L\'hérédité', 'La consommation de tabac', 'La pollution de l\'air', 'L\'âge'], 'correct' => [1], 'explanation' => 'Le tabagisme est un comportement individuel modifiable, contrairement à l\'âge ou l\'hérédité.'],
                    ],
                ],
                [
                    'title' => 'Niveau 3 — Les niveaux de prévention',
                    'subtitle' => 'Primaire, secondaire, tertiaire',
                    'xp_reward' => 120,
                    'content' => "🎯 **Objectif** : distinguer les trois niveaux de prévention.\n\n💡 La **prévention** vise à éviter ou limiter les maladies. On distingue :\n\n• **Prévention primaire** : AVANT la maladie. Empêcher l'apparition. Ex. vaccination, moustiquaires imprégnées, eau potable, éducation nutritionnelle.\n• **Prévention secondaire** : AU DÉBUT de la maladie. Dépister et traiter tôt. Ex. test de dépistage du VIH, frottis du col, mesure de la tension.\n• **Prévention tertiaire** : APRÈS la maladie installée. Limiter les complications et le handicap. Ex. rééducation après un AVC, suivi d'un diabétique.\n\n✅ Astuce mnémotechnique :\n```\nPrimaire   = empêcher (pas encore malade)\nSecondaire = détecter tôt (début de maladie)\nTertiaire  = réparer/limiter (déjà malade)\n```\n\n⚠️ Un même acte peut changer de catégorie selon le moment. Une consultation = secondaire si elle dépiste, tertiaire si elle suit une maladie chronique.\n\nExemple Douala : distribuer des moustiquaires (primaire), dépister le paludisme par TDR (secondaire), prendre en charge un paludisme grave (tertiaire).",
                    'questions' => [
                        ['question' => 'La vaccination est une prévention :', 'options' => ['Primaire', 'Secondaire', 'Tertiaire', 'Curative'], 'correct' => [0], 'explanation' => 'La vaccination empêche l\'apparition de la maladie : c\'est de la prévention primaire.'],
                        ['question' => 'Le dépistage précoce d\'un cancer relève de la prévention :', 'options' => ['Primaire', 'Secondaire', 'Tertiaire', 'Palliative'], 'correct' => [1], 'explanation' => 'Le dépistage détecte la maladie à un stade précoce : c\'est de la prévention secondaire.'],
                        ['question' => 'Lesquelles sont des actions de prévention primaire ? (plusieurs réponses)', 'options' => ['Distribuer des moustiquaires imprégnées', 'Rééduquer après un AVC', 'Promouvoir l\'eau potable', 'Vacciner contre la rougeole'], 'correct' => [0, 2, 3], 'explanation' => 'Moustiquaires, eau potable et vaccination empêchent la maladie ; la rééducation post-AVC est tertiaire.'],
                    ],
                ],
                [
                    'title' => 'Niveau 4 — Incidence et prévalence',
                    'subtitle' => 'Les bases de l\'épidémiologie',
                    'xp_reward' => 130,
                    'content' => "🎯 **Objectif** : calculer et interpréter incidence et prévalence.\n\n💡 L'**épidémiologie** étudie la fréquence et la répartition des maladies dans les populations.\n\n• **Incidence** : nombre de **NOUVEAUX cas** apparus pendant une période donnée. Elle mesure le risque d'attraper la maladie.\n• **Prévalence** : nombre de cas **EXISTANTS** (anciens + nouveaux) à un instant donné. Elle mesure le « poids » de la maladie.\n\n✅ Formules :\n```\nIncidence  = nouveaux cas / population à risque (sur une période)\nPrévalence = cas existants / population totale (à un moment)\n```\n\nExemple Yaoundé : sur 10 000 habitants, 200 personnes vivent avec le VIH (prévalence) et 30 nouveaux cas sont diagnostiqués cette année (incidence).\n\n⚠️ Une maladie chronique bien traitée (les gens vivent longtemps) peut avoir une **prévalence élevée** mais une **incidence faible**. À l'inverse, une épidémie soudaine fait grimper l'incidence rapidement.\n\nLe **taux** rapporte toujours à une population (pour 1 000, pour 100 000) afin de comparer des groupes de tailles différentes.",
                    'questions' => [
                        ['question' => 'L\'incidence mesure :', 'options' => ['Les cas existants à un instant donné', 'Les nouveaux cas sur une période', 'Le nombre de décès', 'La population totale'], 'correct' => [1], 'explanation' => 'L\'incidence compte les nouveaux cas survenus pendant une période : elle reflète le risque.'],
                        ['question' => 'Une maladie chronique bien soignée tend à avoir :', 'options' => ['Une prévalence élevée et une incidence faible', 'Une prévalence faible', 'Une incidence très élevée', 'Aucune prévalence'], 'correct' => [0], 'explanation' => 'Les patients vivent longtemps, donc les cas s\'accumulent (prévalence haute) même si peu de nouveaux cas apparaissent.'],
                        ['question' => 'Pourquoi exprime-t-on les taux pour 1 000 ou 100 000 habitants ?', 'options' => ['Pour rendre le chiffre plus grand', 'Pour comparer des populations de tailles différentes', 'Par obligation légale', 'Pour cacher les données'], 'correct' => [1], 'explanation' => 'Rapporter à une base commune permet de comparer équitablement des populations de tailles différentes.'],
                    ],
                ],
                [
                    'title' => 'Niveau 5 — Surveillance épidémiologique',
                    'subtitle' => 'Détecter une épidémie à temps',
                    'xp_reward' => 140,
                    'content' => "🎯 **Objectif** : comprendre la surveillance des maladies.\n\n💡 La **surveillance épidémiologique** est la collecte continue de données sanitaires pour détecter rapidement un problème. C'est le « système d'alerte » de la santé publique.\n\nÉtapes :\n• **Collecter** les données (centres de santé, laboratoires)\n• **Analyser** : nombre de cas par semaine, par zone\n• **Détecter le seuil d'alerte** (épidémie)\n• **Diffuser** l'information aux décideurs\n• **Réagir** (riposte)\n\n✅ Notions clés :\n• **Cas sporadiques** : cas isolés et rares\n• **Endémie** : maladie présente en permanence dans une zone (ex. paludisme au Cameroun)\n• **Épidémie** : augmentation inhabituelle et rapide de cas (ex. choléra dans un quartier)\n• **Pandémie** : épidémie qui touche plusieurs pays/continents (ex. COVID-19)\n\n⚠️ Les **maladies à déclaration obligatoire** (choléra, fièvre jaune, rougeole...) doivent être signalées immédiatement aux autorités pour déclencher la riposte.\n\nExemple : une flambée de cas de diarrhée aiguë à Douala dépassant le seuil hebdomadaire déclenche une alerte choléra.",
                    'questions' => [
                        ['question' => 'Une maladie présente en permanence dans une région est dite :', 'options' => ['Sporadique', 'Endémique', 'Pandémique', 'Éradiquée'], 'correct' => [1], 'explanation' => 'L\'endémie désigne une maladie constamment présente dans une zone, comme le paludisme au Cameroun.'],
                        ['question' => 'Une épidémie qui touche plusieurs pays et continents est :', 'options' => ['Une endémie', 'Une pandémie', 'Un cas sporadique', 'Une épizootie'], 'correct' => [1], 'explanation' => 'La pandémie est une épidémie à grande échelle internationale, comme la COVID-19.'],
                        ['question' => 'Quels sont des rôles de la surveillance épidémiologique ? (plusieurs réponses)', 'options' => ['Détecter rapidement une flambée', 'Collecter et analyser les données', 'Déclencher l\'alerte', 'Soigner individuellement chaque patient'], 'correct' => [0, 1, 2], 'explanation' => 'La surveillance détecte, collecte/analyse et alerte ; le soin individuel relève de la clinique.'],
                    ],
                ],
                [
                    'title' => 'Niveau 6 — Investigation d\'une épidémie',
                    'subtitle' => 'Mener l\'enquête sur le terrain',
                    'xp_reward' => 150,
                    'content' => "🎯 **Objectif** : savoir comment on enquête sur une épidémie.\n\n💡 Quand une épidémie est suspectée, on lance une **investigation** méthodique pour identifier la cause et stopper la transmission.\n\nÉtapes classiques :\n• Confirmer l'existence de l'épidémie (vérifier le diagnostic, comparer au seuil)\n• Définir un **cas** (critères clairs : symptômes, lieu, période)\n• Décrire l'épidémie selon **temps, lieu, personne**\n• Formuler des **hypothèses** sur la source\n• Tester les hypothèses (enquête, prélèvements)\n• Mettre en place des mesures de contrôle\n\n✅ La **courbe épidémique** (cas par jour) renseigne sur le mode de transmission :\n```\nCas\n |        ▆\n |      ▆ ▆ ▆\n |    ▆ ▆ ▆ ▆ ▆\n |__▆_▆_▆_▆_▆_▆_▆__ Jours\n```\nUn pic unique et brutal évoque une **source commune** (ex. repas contaminé lors d'un mariage à Douala).\n\n⚠️ Définir précisément un « cas » est crucial : sans définition claire, on compte mal et les conclusions sont faussées.\n\nLa triade **temps – lieu – personne** structure toute description épidémiologique.",
                    'questions' => [
                        ['question' => 'La description épidémiologique repose sur la triade :', 'options' => ['Temps – lieu – personne', 'Cause – effet – traitement', 'Âge – sexe – revenu', 'Virus – bactérie – parasite'], 'correct' => [0], 'explanation' => 'Toute investigation décrit l\'épidémie selon le temps, le lieu et les personnes touchées.'],
                        ['question' => 'Un pic brutal et unique sur la courbe épidémique évoque :', 'options' => ['Une transmission de personne à personne', 'Une source commune ponctuelle', 'L\'absence d\'épidémie', 'Une maladie héréditaire'], 'correct' => [1], 'explanation' => 'Un pic unique et brutal suggère une exposition commune ponctuelle, comme un aliment contaminé.'],
                        ['question' => 'Pourquoi définir précisément un « cas » est-il essentiel ?', 'options' => ['Pour faire joli', 'Pour compter correctement et comparer', 'Pour cacher des cas', 'Ce n\'est pas important'], 'correct' => [1], 'explanation' => 'Une définition de cas claire garantit un comptage fiable et des conclusions valides.'],
                    ],
                ],
                [
                    'title' => 'Niveau 7 — La vaccination',
                    'subtitle' => 'Immunité et couverture vaccinale',
                    'xp_reward' => 160,
                    'content' => "🎯 **Objectif** : comprendre les principes de la vaccination.\n\n💡 La **vaccination** entraîne le système immunitaire à reconnaître un agent infectieux sans provoquer la maladie. C'est l'une des interventions de santé publique les plus efficaces.\n\n• **Immunité individuelle** : la personne vaccinée est protégée.\n• **Immunité collective (de groupe)** : si une part suffisante de la population est vaccinée, la circulation du microbe ralentit et protège même les non-vaccinés (nourrissons, immunodéprimés).\n\n✅ La **couverture vaccinale** = proportion de la population correctement vaccinée. Un seuil élevé (souvent 90-95 % pour la rougeole) est nécessaire pour bloquer la transmission.\n\nAu Cameroun, le **PEV** (Programme Élargi de Vaccination) cible des maladies comme la rougeole, la poliomyélite, la tuberculose (BCG), la diphtérie, le tétanos.\n\n⚠️ La **chaîne du froid** (conservation entre 2 et 8 °C) est vitale : un vaccin mal conservé devient inefficace. C'est un défi logistique majeur en zones rurales.\n\nExemple : une baisse de couverture rougeole sous 90 % à Yaoundé peut provoquer une nouvelle épidémie.",
                    'questions' => [
                        ['question' => 'L\'immunité collective protège :', 'options' => ['Uniquement les vaccinés', 'Aussi les non-vaccinés en ralentissant la circulation du microbe', 'Seulement les adultes', 'Personne'], 'correct' => [1], 'explanation' => 'Une couverture suffisante freine la transmission et protège indirectement les non-vaccinés.'],
                        ['question' => 'La chaîne du froid sert à :', 'options' => ['Refroidir les patients', 'Conserver les vaccins efficaces (2-8 °C)', 'Transporter le sang uniquement', 'Climatiser les hôpitaux'], 'correct' => [1], 'explanation' => 'Les vaccins doivent rester entre 2 et 8 °C ; rompre la chaîne du froid les rend inefficaces.'],
                        ['question' => 'Quels énoncés sur la couverture vaccinale sont corrects ? (plusieurs réponses)', 'options' => ['C\'est la proportion de population vaccinée', 'Un seuil élevé est nécessaire pour bloquer la transmission', 'Elle n\'a aucun effet collectif', 'Une baisse peut déclencher des épidémies'], 'correct' => [0, 1, 3], 'explanation' => 'La couverture vaccinale conditionne l\'immunité collective ; une baisse expose à des flambées.'],
                    ],
                ],
                [
                    'title' => 'Niveau 8 — Promotion de la santé',
                    'subtitle' => 'Rendre les gens acteurs de leur santé',
                    'xp_reward' => 170,
                    'content' => "🎯 **Objectif** : concevoir une action de promotion de la santé.\n\n💡 La **promotion de la santé** (Charte d'Ottawa, 1986) vise à donner aux populations les moyens d'améliorer et de contrôler leur propre santé. Elle dépasse le soin : elle agit sur les comportements et les environnements.\n\nAxes de la Charte d'Ottawa :\n• Élaborer des **politiques** favorables à la santé\n• Créer des **environnements** favorables\n• Renforcer l'**action communautaire**\n• Acquérir des **aptitudes individuelles** (éducation pour la santé)\n• Réorienter les **services** de santé vers la prévention\n\n✅ Outils concrets en Afrique :\n• Causeries éducatives dans les centres de santé\n• Radios communautaires en langues locales\n• Sensibilisation par SMS / WhatsApp / **mobile money** (rappels de rendez-vous)\n• Implication des relais communautaires et chefs de quartier\n\n⚠️ Une bonne campagne est **adaptée culturellement** : un message en français écrit touche peu une population peu alphabétisée. Préférer images, théâtre, langues locales.\n\nExemple : à Douala, des agents communautaires forment les mères au lavage des mains pour réduire les diarrhées infantiles.",
                    'questions' => [
                        ['question' => 'La promotion de la santé vise surtout à :', 'options' => ['Construire des hôpitaux', 'Donner aux populations les moyens de contrôler leur santé', 'Vendre des médicaments', 'Remplacer les médecins'], 'correct' => [1], 'explanation' => 'La Charte d\'Ottawa définit la promotion de la santé comme l\'autonomisation des populations face à leur santé.'],
                        ['question' => 'Une campagne de sensibilisation efficace doit être :', 'options' => ['Uniquement en français écrit', 'Adaptée culturellement et au niveau d\'alphabétisation', 'Réservée aux médecins', 'Diffusée une seule fois'], 'correct' => [1], 'explanation' => 'L\'adaptation culturelle et linguistique est indispensable pour que le message atteigne réellement la population.'],
                        ['question' => 'Quels axes figurent dans la Charte d\'Ottawa ? (plusieurs réponses)', 'options' => ['Renforcer l\'action communautaire', 'Acquérir des aptitudes individuelles', 'Créer des environnements favorables', 'Augmenter le prix des soins'], 'correct' => [0, 1, 2], 'explanation' => 'La Charte d\'Ottawa promeut action communautaire, aptitudes individuelles et environnements favorables.'],
                    ],
                ],
                [
                    'title' => 'Niveau 9 — Les indicateurs sanitaires',
                    'subtitle' => 'Mesurer la santé d\'une population',
                    'xp_reward' => 185,
                    'content' => "🎯 **Objectif** : utiliser les principaux indicateurs de santé.\n\n💡 Les **indicateurs sanitaires** chiffrent l'état de santé d'une population et permettent de comparer dans le temps ou entre régions.\n\nIndicateurs essentiels :\n• **Taux de mortalité** : décès / population (ex. pour 1 000 hab.)\n• **Mortalité infantile** : décès d'enfants < 1 an pour 1 000 naissances vivantes\n• **Mortalité maternelle** : décès maternels pour 100 000 naissances vivantes\n• **Espérance de vie** à la naissance\n• **Taux de létalité** : décès parmi les **malades** d'une maladie donnée\n\n✅ Différence importante :\n```\nMortalité = décès / population totale\nLétalité  = décès / nombre de malades\n```\nLa **létalité** mesure la gravité d'une maladie (ex. létalité élevée d'Ebola).\n\n| Indicateur | Ce qu'il mesure |\n|---|---|\n| Mortalité infantile | Santé materno-infantile |\n| Espérance de vie | Santé globale et développement |\n| Létalité | Gravité d'une maladie |\n\n⚠️ Ne pas confondre mortalité (rapportée à toute la population) et létalité (rapportée aux seuls malades). C'est une erreur fréquente.\n\nExemple : une mortalité infantile élevée signale souvent un manque d'accès aux soins et à l'eau potable.",
                    'questions' => [
                        ['question' => 'La létalité d\'une maladie se calcule par :', 'options' => ['Décès / population totale', 'Décès / nombre de malades', 'Nouveaux cas / an', 'Naissances / décès'], 'correct' => [1], 'explanation' => 'La létalité rapporte les décès au nombre de malades : elle mesure la gravité de la maladie.'],
                        ['question' => 'La mortalité infantile concerne les décès :', 'options' => ['Des plus de 65 ans', 'Des enfants de moins de 1 an pour 1 000 naissances vivantes', 'Des mères', 'De toute la population'], 'correct' => [1], 'explanation' => 'La mortalité infantile mesure les décès d\'enfants de moins d\'un an pour 1 000 naissances vivantes.'],
                        ['question' => 'Quels indicateurs reflètent le niveau de développement sanitaire ? (plusieurs réponses)', 'options' => ['Espérance de vie', 'Mortalité maternelle', 'Mortalité infantile', 'Nombre de pharmacies privées'], 'correct' => [0, 1, 2], 'explanation' => 'Espérance de vie, mortalité maternelle et infantile sont des indicateurs clés du développement sanitaire.'],
                    ],
                ],
                [
                    'title' => 'Niveau 10 — Gérer une crise sanitaire',
                    'subtitle' => 'Coordonner la riposte',
                    'xp_reward' => 200,
                    'content' => "🎯 **Objectif** : coordonner la réponse à une crise sanitaire.\n\n💡 Une **crise sanitaire** (épidémie majeure, catastrophe) exige une réponse organisée, rapide et coordonnée.\n\nPhases de gestion :\n• **Préparation** : plans de riposte, stocks, formation, simulations\n• **Détection / alerte** : surveillance, confirmation\n• **Riposte** : coordination, soins, isolement des cas, recherche des contacts, communication\n• **Récupération** : retour à la normale, évaluation, leçons apprises\n\n✅ Piliers de la riposte :\n• **Coordination** : une cellule de crise unique (commandement clair)\n• **Cas et contacts** : isolement, suivi des contacts\n• **Logistique** : médicaments, équipements de protection\n• **Communication des risques** : messages clairs, lutte contre les rumeurs\n• **Engagement communautaire** : impliquer chefs traditionnels, relais, leaders religieux\n\n⚠️ La **communication** est aussi importante que la médecine : les rumeurs (ex. lors d'Ebola en Afrique de l'Ouest) ont aggravé la propagation. Transparence et confiance sont essentielles.\n\n🏆 **Félicitations !** Tu maîtrises désormais les fondamentaux de la santé publique et de l'épidémiologie : définition de la santé, déterminants, prévention, incidence/prévalence, surveillance, vaccination, promotion et gestion de crise.\n\n**Débouchés et évolution** : technicien/agent de santé publique, épidémiologiste, gestionnaire de programmes (PEV, paludisme, VIH), agent de surveillance, chargé de promotion de la santé, coordinateur de riposte aux épidémies, consultant pour les ONG et organisations internationales (OMS, UNICEF). Tu peux poursuivre vers un master en santé publique ou en épidémiologie. Bravo et continue à protéger la santé des populations !",
                    'questions' => [
                        ['question' => 'Pendant une crise sanitaire, la coordination doit reposer sur :', 'options' => ['Plusieurs commandements indépendants', 'Une cellule de crise unique avec commandement clair', 'L\'absence de plan', 'La seule action des hôpitaux'], 'correct' => [1], 'explanation' => 'Une cellule de crise unique évite les ordres contradictoires et assure une riposte cohérente.'],
                        ['question' => 'Pourquoi la communication des risques est-elle cruciale ?', 'options' => ['Elle remplace les soins', 'Elle limite les rumeurs et renforce la confiance', 'Elle est facultative', 'Elle ralentit la riposte'], 'correct' => [1], 'explanation' => 'Une communication claire combat les rumeurs et la défiance, qui peuvent aggraver la propagation.'],
                        ['question' => 'Quels éléments font partie de la riposte à une épidémie ? (plusieurs réponses)', 'options' => ['Isolement des cas et suivi des contacts', 'Engagement des communautés', 'Communication des risques', 'Ignorer les rumeurs'], 'correct' => [0, 1, 2], 'explanation' => 'Isolement, suivi des contacts, engagement communautaire et communication sont les piliers d\'une riposte efficace.'],
                    ],
                ],
            ],
        ]);

        $this->command->info('  ✓ Roadmap Santé Publique & Épidémiologie créée (10 niveaux).');
    }
}
