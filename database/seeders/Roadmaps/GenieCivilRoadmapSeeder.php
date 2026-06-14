<?php

namespace Database\Seeders\Roadmaps;

use Illuminate\Database\Seeder;

/**
 * Roadmap Génie Civil — devenir ingénieur génie civil, des matériaux aux normes de chantier.
 */
class GenieCivilRoadmapSeeder extends Seeder
{
    use RoadmapSeederHelper;

    public function run(): void
    {
        $this->createRoadmap([
            'title' => 'Deviens Ingénieur Génie Civil',
            'slug' => 'genie-civil',
            'domain' => 'ingenierie',
            'description' => "Une formation complète et progressive au génie civil pensée pour le contexte africain francophone. Tu découvriras le métier d'ingénieur civil, les matériaux de construction (béton, acier, granulats), la résistance des matériaux, le calcul des structures et des charges, les fondations, le béton armé, la lecture de plans, l'organisation et le contrôle de chantier, ainsi que la sécurité et les normes. Des bases jusqu'au dimensionnement, avec des exemples ancrés à Douala et Yaoundé.",
            'objectives' => "Comprendre le rôle et les responsabilités de l'ingénieur génie civil\nMaîtriser les propriétés des matériaux : béton, acier et granulats\nAppliquer les notions de résistance des matériaux et de charges\nDimensionner des éléments simples en béton armé\nSavoir lire et interpréter des plans de structure\nOrganiser un chantier, contrôler la qualité et faire respecter la sécurité",
            'icon' => '🏗️',
            'color' => '#F59E0B',
            'difficulty' => 'advanced',
            'required_packs' => [],
            'pass_threshold' => 70,
            'order' => 20,
            'levels' => [
                [
                    'title' => 'Niveau 1 — Le métier d\'ingénieur génie civil',
                    'subtitle' => 'Rôle, missions et responsabilités',
                    'xp_reward' => 100,
                    'content' => "🎯 **Objectif** : comprendre ce que fait réellement un ingénieur génie civil et où il intervient.\n\n💡 Le génie civil regroupe la conception et la réalisation des ouvrages : bâtiments, ponts, routes, barrages, réseaux d'eau et d'assainissement. À Douala comme à Yaoundé, l'ingénieur intervient sur tout le cycle de vie d'un projet.\n\nSes grandes missions :\n• **Études** : analyser le besoin, le sol, le climat et le budget\n• **Conception** : dimensionner la structure pour qu'elle soit sûre et économique\n• **Suivi de chantier** : coordonner ouvriers, matériaux et délais\n• **Contrôle** : vérifier la qualité et la conformité aux normes\n\n✅ Trois qualités essentielles : la rigueur (une erreur de calcul peut coûter des vies), la communication (avec maçons, architectes, maître d'ouvrage) et la capacité à décider sur le terrain.\n\n⚠️ L'ingénieur engage sa responsabilité : un effondrement dû à une faute de conception peut entraîner des poursuites. La sécurité du public passe avant le profit.",
                    'questions' => [
                        ['question' => 'Quel domaine ne relève PAS directement du génie civil ?', 'options' => ['La construction de ponts', 'La conception de logiciels mobiles', 'Les réseaux d\'assainissement', 'Les fondations de bâtiments'], 'correct' => [1], 'explanation' => 'Le génie civil concerne les ouvrages physiques (ponts, routes, bâtiments, réseaux), pas le développement logiciel.'],
                        ['question' => 'Pourquoi la rigueur est-elle cruciale pour l\'ingénieur civil ?', 'options' => ['Pour gagner du temps', 'Parce qu\'une erreur de calcul peut compromettre la sécurité des personnes', 'Pour plaire au client', 'Parce que c\'est obligatoire administrativement'], 'correct' => [1], 'explanation' => 'Une faute de dimensionnement peut provoquer un effondrement et mettre des vies en danger.'],
                        ['question' => 'Quelles missions font partie du travail de l\'ingénieur civil ? (plusieurs réponses)', 'options' => ['Étude du sol et du besoin', 'Dimensionnement de la structure', 'Vente directe de ciment au détail', 'Suivi et contrôle du chantier'], 'correct' => [0, 1, 3], 'explanation' => 'L\'ingénieur étudie, conçoit et suit le chantier ; la vente de matériaux au détail n\'est pas sa mission.'],
                    ],
                ],
                [
                    'title' => 'Niveau 2 — Les granulats et le ciment',
                    'subtitle' => 'Les constituants de base du béton',
                    'xp_reward' => 110,
                    'content' => "🎯 **Objectif** : connaître les matériaux qui composent le béton avant de l'étudier.\n\n💡 Le béton est un mélange de **ciment + eau + granulats** (sable et gravier). La qualité de chaque constituant détermine la solidité finale.\n\nLes granulats :\n• **Sable** (granulats fins, 0 à 5 mm) : remplit les vides\n• **Gravier** (granulats grossiers, 5 à 25 mm) : donne le squelette résistant\n• Ils doivent être **propres** : un sable argileux ou pollué affaiblit le béton\n\nLe ciment :\n• Le **ciment Portland (CEM)** est le plus courant\n• La classe de résistance (ex : 32,5 ou 42,5) indique sa performance\n• ⚠️ Au Cameroun, attention au stockage : le ciment craint l'humidité et durcit s'il est mal protégé en saison des pluies\n\n✅ Règle de terrain : un bon béton commence par des granulats lavés et un ciment frais, bien à l'abri.\n\nProportions courantes (dosage) d'un béton à 350 kg/m³ :\n\n| Constituant | Quantité indicative |\n|---|---|\n| Ciment | 350 kg |\n| Sable | ~ 0,4 m³ |\n| Gravier | ~ 0,8 m³ |\n| Eau | ~ 175 L |",
                    'questions' => [
                        ['question' => 'Quels sont les constituants de base du béton ?', 'options' => ['Ciment, eau et granulats', 'Acier, bois et plâtre', 'Argile, eau et chaux uniquement', 'Sable et eau seulement'], 'correct' => [0], 'explanation' => 'Le béton est un mélange de ciment, d\'eau et de granulats (sable et gravier).'],
                        ['question' => 'Pourquoi faut-il utiliser des granulats propres ?', 'options' => ['Pour la couleur du béton', 'Parce que l\'argile ou les impuretés affaiblissent le béton', 'Pour réduire le coût', 'Pour accélérer le séchage'], 'correct' => [1], 'explanation' => 'Les impuretés (argile, matières organiques) nuisent à l\'adhérence et réduisent la résistance du béton.'],
                        ['question' => 'Que signifie la classe 42,5 d\'un ciment ?', 'options' => ['Son poids par sac', 'Sa classe de résistance', 'Sa date de péremption', 'Sa couleur'], 'correct' => [1], 'explanation' => 'Le chiffre indique la classe de résistance à la compression du ciment (en MPa à 28 jours).'],
                    ],
                ],
                [
                    'title' => 'Niveau 3 — Le béton et l\'acier',
                    'subtitle' => 'Comportement, dosage et complémentarité',
                    'xp_reward' => 120,
                    'content' => "🎯 **Objectif** : comprendre pourquoi on associe béton et acier.\n\n💡 Le **béton** résiste très bien à la **compression** mais mal à la **traction**. L'**acier**, lui, résiste excellemment à la traction. On les combine donc : c'est le **béton armé**.\n\nCaractéristiques du béton :\n• Résistance désignée par fc28 (résistance à 28 jours), ex : 25 MPa\n• Le **rapport eau/ciment (E/C)** est clé : trop d'eau = béton poreux et faible\n• Le béton continue de durcir pendant 28 jours (cure indispensable)\n\nCaractéristiques de l'acier (armatures) :\n• Désigné par sa limite d'élasticité fe, ex : fe = 400 ou 500 MPa\n• Les barres ont un diamètre normalisé : HA8, HA10, HA12, HA16 (HA = Haute Adhérence)\n\n✅ Calcul simple du rapport E/C :\n```\nE/C = masse d'eau / masse de ciment\nExemple : 175 L d'eau / 350 kg ciment = 0,50\n```\nUn E/C autour de 0,5 donne un bon compromis résistance/maniabilité.\n\n⚠️ Ajouter de l'eau sur chantier pour rendre le béton plus liquide est une erreur fréquente qui ruine la résistance.",
                    'questions' => [
                        ['question' => 'Pourquoi associe-t-on l\'acier au béton ?', 'options' => ['Pour la décoration', 'Parce que l\'acier reprend la traction que le béton supporte mal', 'Pour réduire le poids', 'Parce que l\'acier est moins cher'], 'correct' => [1], 'explanation' => 'Le béton résiste à la compression, l\'acier à la traction : leur association forme le béton armé.'],
                        ['question' => 'Quel est l\'effet d\'un excès d\'eau dans le béton ?', 'options' => ['Il devient plus résistant', 'Il devient poreux et perd en résistance', 'Il sèche plus lentement mais reste solide', 'Cela n\'a aucun effet'], 'correct' => [1], 'explanation' => 'Un rapport E/C trop élevé rend le béton poreux et diminue fortement sa résistance.'],
                        ['question' => 'Que désigne fc28 ?', 'options' => ['Le diamètre des barres', 'La résistance du béton à la compression à 28 jours', 'La quantité de ciment', 'Le poids du béton'], 'correct' => [1], 'explanation' => 'fc28 est la résistance caractéristique du béton à la compression mesurée à 28 jours.'],
                    ],
                ],
                [
                    'title' => 'Niveau 4 — Résistance des matériaux (RDM)',
                    'subtitle' => 'Contraintes, déformations et sollicitations',
                    'xp_reward' => 130,
                    'content' => "🎯 **Objectif** : comprendre comment un matériau réagit aux efforts.\n\n💡 La **résistance des matériaux (RDM)** étudie comment les pièces se déforment et se rompent sous l'effet des forces.\n\nLes sollicitations de base :\n• **Traction** : on tire sur la pièce (elle s'allonge)\n• **Compression** : on l'écrase (elle se raccourcit)\n• **Flexion** : une poutre se courbe sous une charge\n• **Cisaillement** : deux forces glissent l'une contre l'autre\n• **Torsion** : on tord la pièce\n\nLa **contrainte (σ)** mesure l'effort réparti sur une surface :\n```\nσ = F / S\nF = force (N), S = section (mm²), σ en MPa\nExemple : 20 000 N sur 100 mm² → σ = 200 MPa\n```\n\n✅ On compare σ à la contrainte admissible du matériau. Si σ dépasse la limite, la pièce casse.\n\n⚠️ En flexion, les fibres du bas d'une poutre sont en traction : c'est là qu'on place les armatures dans une poutre en béton armé.",
                    'questions' => [
                        ['question' => 'Que mesure la contrainte σ = F/S ?', 'options' => ['La vitesse de la force', 'L\'effort réparti sur une surface', 'Le poids total', 'La température du matériau'], 'correct' => [1], 'explanation' => 'La contrainte est la force divisée par la section : elle quantifie l\'effort par unité de surface.'],
                        ['question' => 'Une poutre qui se courbe sous une charge subit principalement :', 'options' => ['De la torsion', 'De la flexion', 'De la compression pure', 'Aucune sollicitation'], 'correct' => [1], 'explanation' => 'Une poutre chargée transversalement travaille en flexion.'],
                        ['question' => 'Dans une poutre fléchie, où placer en priorité les armatures ?', 'options' => ['Au centre uniquement', 'Du côté tendu (en bas pour une charge vers le bas)', 'Sur les faces latérales', 'Aucune armature n\'est nécessaire'], 'correct' => [1], 'explanation' => 'L\'acier reprend la traction, donc on l\'place du côté tendu de la poutre.'],
                    ],
                ],
                [
                    'title' => 'Niveau 5 — Structures et charges',
                    'subtitle' => 'Descente de charges et stabilité',
                    'xp_reward' => 140,
                    'content' => "🎯 **Objectif** : savoir identifier et faire descendre les charges d'un bâtiment.\n\n💡 Toute structure transmet les charges du haut vers le sol : c'est la **descente de charges**. Chemin type : dalle → poutre → poteau → fondation → sol.\n\nTypes de charges :\n• **Charges permanentes (G)** : poids propre des murs, dalles, revêtements\n• **Charges d'exploitation (Q)** : meubles, personnes, marchandises\n• **Charges climatiques** : vent, et en zone tropicale, fortes pluies\n\nÉtats limites (méthode courante) :\n• **ELU** (État Limite Ultime) : on vérifie que la structure ne s'effondre pas. Combinaison : 1,35 G + 1,5 Q\n• **ELS** (État Limite de Service) : on vérifie le confort (pas de fissures, flèche acceptable)\n\n✅ Exemple de descente sur un poteau :\n```\nDalle 5 t + poutres 2 t + murs 3 t = 10 t\nELU : 1,35 × G ≈ 13,5 t à reprendre\n```\n\n⚠️ Oublier une charge (ex : citerne d'eau sur le toit) fausse tout le dimensionnement et fragilise l'ouvrage.",
                    'questions' => [
                        ['question' => 'Quel est le chemin typique de descente des charges ?', 'options' => ['Sol → poteau → dalle', 'Dalle → poutre → poteau → fondation → sol', 'Fondation → dalle → toit', 'Poutre → toit → sol'], 'correct' => [1], 'explanation' => 'Les charges descendent de la dalle vers les poutres, puis les poteaux, les fondations et enfin le sol.'],
                        ['question' => 'Quelles charges sont des charges permanentes (G) ?', 'options' => ['Le poids propre des murs et dalles', 'Les personnes présentes', 'Le mobilier déplaçable', 'Le vent'], 'correct' => [0], 'explanation' => 'Les charges permanentes correspondent au poids propre fixe de la structure et des revêtements.'],
                        ['question' => 'À quoi sert la vérification à l\'ELU ?', 'options' => ['À vérifier l\'esthétique', 'À s\'assurer que la structure ne s\'effondre pas', 'À calculer le coût', 'À choisir la couleur'], 'correct' => [1], 'explanation' => 'L\'État Limite Ultime vérifie la résistance pour éviter la ruine de la structure.'],
                    ],
                ],
                [
                    'title' => 'Niveau 6 — Les fondations',
                    'subtitle' => 'Transmettre les charges au sol',
                    'xp_reward' => 150,
                    'content' => "🎯 **Objectif** : comprendre comment l'ouvrage repose sur le sol.\n\n💡 La **fondation** transmet les charges du bâtiment au sol sans qu'il s'enfonce ou se tasse de façon dangereuse. Le choix dépend de la **capacité portante du sol**, mesurée par une **étude géotechnique**.\n\nTypes de fondations :\n• **Superficielles** : semelles isolées (sous poteaux), semelles filantes (sous murs), radier (dalle générale). Utilisées quand le bon sol est proche de la surface\n• **Profondes** : **pieux** qui descendent chercher un sol résistant en profondeur. Indispensables sur sols mous, fréquents dans certaines zones de Douala (sols argileux/marécageux)\n\n✅ Principe : plus la charge est grande ou le sol faible, plus la surface d'appui doit être large.\n```\nContrainte au sol = Charge / Surface de la semelle\nDoit rester < capacité portante du sol\n```\n\n⚠️ Construire sur un sol non étudié est une erreur grave : tassements différentiels, fissures, voire effondrement. Le coût d'une étude de sol est dérisoire face au risque.",
                    'questions' => [
                        ['question' => 'À quoi sert une fondation ?', 'options' => ['À décorer la base du bâtiment', 'À transmettre les charges au sol sans tassement dangereux', 'À stocker l\'eau', 'À isoler du froid'], 'correct' => [1], 'explanation' => 'La fondation répartit et transmet les charges de l\'ouvrage vers le sol porteur.'],
                        ['question' => 'Quand utilise-t-on des fondations profondes (pieux) ?', 'options' => ['Toujours, par sécurité', 'Quand le sol de surface est mou et le bon sol est en profondeur', 'Uniquement pour les maisons individuelles', 'Quand on manque de béton'], 'correct' => [1], 'explanation' => 'Les pieux vont chercher un sol résistant en profondeur lorsque le sol superficiel est insuffisant.'],
                        ['question' => 'Quels éléments font partie des fondations superficielles ? (plusieurs réponses)', 'options' => ['Semelle isolée', 'Semelle filante', 'Pieu de 20 m', 'Radier'], 'correct' => [0, 1, 3], 'explanation' => 'Semelles isolées, filantes et radier sont superficielles ; les pieux sont des fondations profondes.'],
                    ],
                ],
                [
                    'title' => 'Niveau 7 — Le béton armé en pratique',
                    'subtitle' => 'Ferraillage des poutres, poteaux et dalles',
                    'xp_reward' => 160,
                    'content' => "🎯 **Objectif** : savoir comment on arme les éléments porteurs.\n\n💡 Le **ferraillage** est la disposition des armatures dans le béton. Chaque élément a ses règles.\n\n**Poutre** :\n• Armatures longitudinales en bas (zone tendue)\n• **Cadres / étriers** verticaux pour reprendre l'effort tranchant\n\n**Poteau** :\n• Barres longitudinales aux angles\n• **Cadres** rapprochés en tête et en pied (zones critiques)\n\n**Dalle** :\n• Treillis ou barres croisées dans les deux sens\n\nL'**enrobage** (distance entre l'acier et la surface) protège l'acier de la corrosion : 2,5 à 3 cm courant, plus en milieu humide ou marin comme la côte de Douala.\n\n✅ Estimation rapide d'une section d'acier :\n```\nAs = Moment / (0,9 × d × fe / γs)\nd = hauteur utile, fe = limite élastique acier\n```\n\n⚠️ Erreurs fatales : enrobage insuffisant (acier qui rouille et fait éclater le béton), cadres trop espacés, barres mal ancrées. Sur la côte, l'air salin accélère la corrosion.",
                    'questions' => [
                        ['question' => 'À quoi servent les cadres (étriers) dans une poutre ?', 'options' => ['À la décorer', 'À reprendre l\'effort tranchant et maintenir les barres', 'À augmenter le poids', 'À remplacer le béton'], 'correct' => [1], 'explanation' => 'Les cadres reprennent l\'effort tranchant et maintiennent en place les armatures longitudinales.'],
                        ['question' => 'Pourquoi respecter l\'enrobage des armatures ?', 'options' => ['Pour économiser l\'acier', 'Pour protéger l\'acier de la corrosion', 'Pour la couleur', 'Pour gagner du temps'], 'correct' => [1], 'explanation' => 'L\'enrobage de béton protège les armatures de l\'humidité et de la corrosion.'],
                        ['question' => 'Dans une dalle, comment dispose-t-on généralement les armatures ?', 'options' => ['Dans une seule direction', 'En barres croisées dans les deux directions', 'En diagonale uniquement', 'Au centre seulement'], 'correct' => [1], 'explanation' => 'Une dalle est généralement armée par un treillis ou des barres croisées dans les deux sens.'],
                    ],
                ],
                [
                    'title' => 'Niveau 8 — Lecture de plans',
                    'subtitle' => 'Comprendre plans, coupes et symboles',
                    'xp_reward' => 170,
                    'content' => "🎯 **Objectif** : savoir lire les documents techniques d'un projet.\n\n💡 Un projet se lit à travers plusieurs documents complémentaires :\n• **Plan de masse** : situe le bâtiment sur le terrain\n• **Plans d'étage (architecte)** : disposition des pièces, vue de dessus\n• **Plans de structure (BA)** : poteaux, poutres, dalles et leur ferraillage\n• **Coupes** : vue verticale tranchée pour voir les hauteurs\n• **Détails** : zoom sur un point précis (escalier, jonction)\n\nL'**échelle** indique le rapport plan/réalité :\n```\nÉchelle 1/100 → 1 cm sur le plan = 100 cm = 1 m réel\nÉchelle 1/50 → 1 cm = 50 cm réels (plus détaillé)\n```\n\nSymboles à connaître : axes (traits mixtes), cotes (dimensions), repérage des poteaux (P1, P2…), désignation des armatures (ex : 4 HA12 = 4 barres haute adhérence Ø12).\n\n✅ Réflexe : toujours vérifier l'échelle et le Nord avant de mesurer.\n\n⚠️ Confondre un plan d'architecte et un plan de structure, ou mal lire une cote, conduit à des erreurs d'exécution coûteuses sur chantier.",
                    'questions' => [
                        ['question' => 'À l\'échelle 1/100, que représente 1 cm sur le plan ?', 'options' => ['1 cm réel', '10 cm réels', '1 mètre réel', '100 mètres réels'], 'correct' => [2], 'explanation' => 'À 1/100, 1 cm sur le plan correspond à 100 cm, soit 1 mètre dans la réalité.'],
                        ['question' => 'Que signifie l\'indication "4 HA12" sur un plan de structure ?', 'options' => ['4 mètres de hauteur', '4 barres haute adhérence de diamètre 12 mm', '4 heures de travail', '12 poteaux'], 'correct' => [1], 'explanation' => 'HA = haute adhérence, 12 = diamètre en mm, donc 4 barres de Ø12.'],
                        ['question' => 'Quel document montre une vue verticale tranchée du bâtiment ?', 'options' => ['Le plan de masse', 'La coupe', 'Le plan de situation', 'La façade en perspective'], 'correct' => [1], 'explanation' => 'La coupe est une vue verticale tranchée qui révèle les hauteurs et niveaux.'],
                    ],
                ],
                [
                    'title' => 'Niveau 9 — Organisation et contrôle de chantier',
                    'subtitle' => 'Planning, qualité et essais',
                    'xp_reward' => 185,
                    'content' => "🎯 **Objectif** : piloter un chantier et garantir la qualité.\n\n💡 Un chantier réussi repose sur trois piliers : **planning**, **moyens** et **contrôle**.\n\nOrganisation :\n• **Planning** (ex : diagramme de Gantt) : ordonne les tâches (terrassement → fondations → élévation → toiture → finitions)\n• **Approvisionnement** : avoir ciment, acier et coffrage au bon moment, surtout en saison des pluies où l'accès est difficile\n• **Coordination** des équipes et des sous-traitants\n\nContrôle qualité (essais courants) :\n• **Slump test (cône d'Abrams)** : mesure la consistance du béton frais\n• **Éprouvettes** : cylindres écrasés à 7 et 28 jours pour vérifier fc28\n• Contrôle du ferraillage **avant coulage** (diamètres, enrobage, nombre de barres)\n\n✅ Tableau de contrôle simple :\n\n| Étape | Contrôle |\n|---|---|\n| Avant coulage | Ferraillage, enrobage, propreté |\n| Béton frais | Slump test |\n| À 28 jours | Écrasement d'éprouvette |\n\n⚠️ Ne jamais couler du béton sans avoir validé le ferraillage : une fois coulé, l'erreur est irréversible.",
                    'questions' => [
                        ['question' => 'Que mesure le slump test (cône d\'Abrams) ?', 'options' => ['La résistance finale du béton', 'La consistance (maniabilité) du béton frais', 'Le poids du béton', 'La couleur du béton'], 'correct' => [1], 'explanation' => 'Le slump test mesure l\'affaissement du béton frais, donc sa consistance et sa maniabilité.'],
                        ['question' => 'Quand contrôle-t-on impérativement le ferraillage ?', 'options' => ['Après le coulage', 'Avant le coulage du béton', 'À la fin du chantier', 'Jamais'], 'correct' => [1], 'explanation' => 'Le ferraillage doit être validé avant coulage car l\'erreur devient irréversible une fois le béton coulé.'],
                        ['question' => 'Quels outils ou essais relèvent du contrôle/organisation de chantier ? (plusieurs réponses)', 'options' => ['Diagramme de Gantt', 'Écrasement d\'éprouvettes à 28 jours', 'Slump test', 'Choix de la peinture décorative'], 'correct' => [0, 1, 2], 'explanation' => 'Planning Gantt, écrasement d\'éprouvettes et slump test relèvent de l\'organisation et du contrôle qualité.'],
                    ],
                ],
                [
                    'title' => 'Niveau 10 — Sécurité, normes et carrière',
                    'subtitle' => 'Prévention, réglementation et débouchés',
                    'xp_reward' => 200,
                    'content' => "🎯 **Objectif** : maîtriser la sécurité, les normes et envisager ta carrière.\n\n💡 La **sécurité (HSE)** est non négociable. Les principaux risques de chantier : chutes de hauteur, effondrement de fouilles, chute d'objets, électrocution, engins.\n\nMesures de prévention :\n• **EPI** : casque, chaussures de sécurité, gants, harnais en hauteur\n• Garde-corps, étaiement des fouilles profondes, balisage\n• Formation et consignes claires aux ouvriers\n\nNormes et réglementation :\n• **Eurocodes** (EC2 pour le béton armé) et anciennes règles **BAEL**, largement utilisés en Afrique francophone\n• Contexte juridique des affaires régi par l'**OHADA**\n• Respect du **DTU**, du contrôle technique et des assurances\n\n✅ Tableau risque/mesure :\n\n| Risque | Mesure |\n|---|---|\n| Chute de hauteur | Harnais, garde-corps |\n| Effondrement de fouille | Étaiement, blindage |\n| Chute d'objet | Casque, filets |\n\n🏆 **Félicitations !** Tu as parcouru tout le cycle du génie civil, des matériaux à la sécurité. Débouchés : ingénieur d'études (bureau de calcul), ingénieur travaux / conducteur de chantier, contrôleur technique, expert géotechnique, chef de projet, puis directeur technique ou entrepreneur. Avec l'urbanisation rapide de Douala, Yaoundé et des capitales de la sous-région, les besoins en bâtiments, ponts et infrastructures sont immenses. À toi de bâtir l'Afrique de demain !",
                    'questions' => [
                        ['question' => 'Quel EPI est indispensable pour un travail en hauteur ?', 'options' => ['Une casquette', 'Un harnais de sécurité', 'Des lunettes de soleil', 'Un gilet réfléchissant seul'], 'correct' => [1], 'explanation' => 'Le harnais antichute est l\'EPI essentiel pour prévenir les chutes de hauteur.'],
                        ['question' => 'Quelles normes/règles sont couramment utilisées pour le béton armé en Afrique francophone ?', 'options' => ['Les Eurocodes (EC2) et le BAEL', 'Le code de la route', 'Les normes alimentaires', 'Les règles de comptabilité'], 'correct' => [0], 'explanation' => 'Les Eurocodes (notamment l\'EC2) et l\'ancien BAEL régissent le calcul du béton armé.'],
                        ['question' => 'Comment prévenir l\'effondrement d\'une fouille profonde ?', 'options' => ['En creusant plus vite', 'Par l\'étaiement ou le blindage des parois', 'En arrosant le sol', 'En enlevant les EPI'], 'correct' => [1], 'explanation' => 'L\'étaiement ou le blindage soutient les parois et évite l\'effondrement de la fouille.'],
                    ],
                ],
            ],
        ]);

        $this->command->info('  ✓ Roadmap Génie Civil créée (10 niveaux).');
    }
}
