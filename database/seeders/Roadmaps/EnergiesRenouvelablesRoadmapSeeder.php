<?php

namespace Database\Seeders\Roadmaps;

use Illuminate\Database\Seeder;

/**
 * Roadmap Énergies Renouvelables — devenir technicien capable de dimensionner, installer et maintenir des systèmes solaires, éoliens et biomasse, adaptés au contexte africain.
 */
class EnergiesRenouvelablesRoadmapSeeder extends Seeder
{
    use RoadmapSeederHelper;

    public function run(): void
    {
        $this->createRoadmap([
            'title' => 'Deviens Technicien en Énergies Renouvelables',
            'slug' => 'energies-renouvelables',
            'domain' => 'ingenierie',
            'description' => "Une formation complète et pratique pour comprendre, dimensionner, installer et entretenir des systèmes d'énergies renouvelables. De la cellule photovoltaïque au mini-réseau off-grid, en passant par l'éolien, la biomasse et le stockage, cette roadmap te prépare à un métier d'avenir au Cameroun et en Afrique francophone.",
            'objectives' => "Comprendre les enjeux énergétiques et la transition en Afrique\nMaîtriser le fonctionnement du solaire photovoltaïque\nDimensionner une installation solaire autonome\nChoisir et entretenir batteries et systèmes de stockage\nConnaître l'éolien, la biomasse et le biogaz\nInstaller onduleurs et gérer le raccordement\nAssurer la maintenance et calculer la rentabilité\nConcevoir des solutions off-grid adaptées au contexte rural",
            'icon' => '☀️',
            'color' => '#16A34A',
            'difficulty' => 'intermediate',
            'required_packs' => [],
            'pass_threshold' => 70,
            'order' => 20,
            'levels' => [
                [
                    'title' => 'Niveau 1 — Les enjeux énergétiques',
                    'subtitle' => 'Pourquoi les énergies renouvelables ?',
                    'xp_reward' => 100,
                    'content' => "🎯 **Objectif** : comprendre pourquoi le monde et l'Afrique se tournent vers les énergies renouvelables.\n\n💡 Les énergies **fossiles** (pétrole, gaz, charbon) sont épuisables et émettent du CO₂, accélérant le réchauffement climatique. Les énergies **renouvelables** (soleil, vent, eau, biomasse) sont inépuisables à l'échelle humaine.\n\n✅ Enjeux clés en Afrique :\n• Accès à l'électricité : près de 600 millions de personnes en sont privées.\n• Beaucoup de zones rurales (régions de l'Est, Adamaoua) ne sont pas raccordées au réseau.\n• Le soleil est abondant : le Cameroun reçoit en moyenne 4 à 5,5 kWh/m²/jour.\n\n⚠️ On distingue :\n• Énergie **primaire** : la source brute (rayonnement solaire).\n• Énergie **finale** : celle consommée (électricité de la prise).\n\nLes renouvelables réduisent la facture, créent des emplois locaux de techniciens et électrifient les villages sans groupe électrogène bruyant et polluant.",
                    'questions' => [
                        ['question' => 'Quelle source d\'énergie suivante est renouvelable ?', 'options' => ['Le charbon', 'Le rayonnement solaire', 'Le gaz naturel', 'Le pétrole'], 'correct' => [1], 'explanation' => 'Le rayonnement solaire est inépuisable à l\'échelle humaine, contrairement aux énergies fossiles.'],
                        ['question' => 'Quel est un avantage majeur des renouvelables en zone rurale africaine ?', 'options' => ['Elles polluent davantage', 'Elles électrifient des zones non raccordées au réseau', 'Elles nécessitent toujours le réseau national', 'Elles consomment beaucoup d\'eau potable'], 'correct' => [1], 'explanation' => 'Les solutions off-grid permettent d\'alimenter des villages éloignés sans extension coûteuse du réseau.'],
                        ['question' => 'Parmi ces sources, lesquelles sont renouvelables ?', 'options' => ['Le vent', 'Le charbon', 'La biomasse', 'Le pétrole'], 'correct' => [0, 2], 'explanation' => 'Le vent (éolien) et la biomasse sont renouvelables, tandis que charbon et pétrole sont fossiles.'],
                    ],
                ],
                [
                    'title' => 'Niveau 2 — Le solaire photovoltaïque',
                    'subtitle' => 'Comment le soleil devient électricité',
                    'xp_reward' => 110,
                    'content' => "🎯 **Objectif** : comprendre comment un panneau solaire produit de l'électricité.\n\n💡 L'effet **photovoltaïque** : quand la lumière frappe une cellule en silicium, elle libère des électrons et crée un courant électrique **continu (DC)**.\n\n✅ Du plus petit au plus grand :\n• **Cellule** : produit ~0,5 V.\n• **Module / panneau** : plusieurs cellules en série (ex. 36 ou 72 cellules).\n• **String** : plusieurs panneaux en série.\n• **Champ / array** : plusieurs strings.\n\n⚠️ À ne pas confondre :\n• **Photovoltaïque (PV)** : produit de l'électricité.\n• **Solaire thermique** : chauffe de l'eau (chauffe-eau solaire).\n\n📊 Caractéristiques d'un panneau :\n• Puissance crête **Wc** (watt-crête) mesurée dans des conditions standard (STC : 1000 W/m², 25 °C).\n• Tension à vide **Voc**, courant de court-circuit **Isc**.\n\nUn panneau de 300 Wc à Douala produit moins en pleine après-midi nuageuse qu'en matinée dégagée : la production dépend de l'**irradiation** réelle.",
                    'questions' => [
                        ['question' => 'Quel type de courant produit directement un panneau photovoltaïque ?', 'options' => ['Courant alternatif (AC)', 'Courant continu (DC)', 'Aucun courant', 'Courant triphasé'], 'correct' => [1], 'explanation' => 'La cellule PV génère un courant continu, qu\'un onduleur convertira ensuite en alternatif si nécessaire.'],
                        ['question' => 'Que signifie l\'unité « Wc » d\'un panneau ?', 'options' => ['Watt consommé', 'Watt-crête, puissance en conditions standard', 'Watt continu permanent', 'Watt par cellule'], 'correct' => [1], 'explanation' => 'Le watt-crête (Wc) est la puissance maximale mesurée aux conditions standard STC.'],
                        ['question' => 'Quelle différence entre photovoltaïque et solaire thermique ?', 'options' => ['Aucune, c\'est pareil', 'Le PV produit de l\'électricité, le thermique chauffe de l\'eau', 'Le thermique produit du courant continu', 'Le PV chauffe l\'eau sanitaire'], 'correct' => [1], 'explanation' => 'Le photovoltaïque génère de l\'électricité alors que le solaire thermique sert à chauffer un fluide.'],
                    ],
                ],
                [
                    'title' => 'Niveau 3 — Estimer les besoins',
                    'subtitle' => 'Le bilan de consommation',
                    'xp_reward' => 120,
                    'content' => "🎯 **Objectif** : calculer la consommation électrique d'un foyer ou d'un commerce avant de dimensionner.\n\n💡 La consommation se mesure en **wattheures (Wh)** : c'est la puissance multipliée par le temps d'usage.\n\n📐 Formule :\n```\nEnergie (Wh) = Puissance (W) × Durée (h)\n```\n\n✅ Exemple d'un foyer à Yaoundé :\n• 4 lampes LED de 10 W × 5 h = 200 Wh\n• 1 réfrigérateur 150 W × 8 h (cycle réel) = 1200 Wh\n• 1 téléviseur 80 W × 4 h = 320 Wh\n• Recharge téléphones 15 W × 3 h = 45 Wh\n\n```\nTotal journalier = 200 + 1200 + 320 + 45 = 1765 Wh/jour\n```\n\n⚠️ Bonnes pratiques :\n• Lister TOUS les appareils, leur puissance (étiquette) et leur durée réelle.\n• Ajouter une marge de 20 à 30 % pour les pertes et imprévus.\n• Séparer les charges essentielles (éclairage, frigo) des charges de confort.\n\nUn bon bilan de consommation est la base de tout dimensionnement réussi.",
                    'questions' => [
                        ['question' => 'Quelle est l\'énergie consommée par un appareil de 100 W pendant 5 heures ?', 'options' => ['20 Wh', '500 Wh', '105 Wh', '50 Wh'], 'correct' => [1], 'explanation' => 'Énergie = Puissance × Durée = 100 W × 5 h = 500 Wh.'],
                        ['question' => 'Pourquoi ajouter une marge de 20 à 30 % au bilan de consommation ?', 'options' => ['Pour gonfler la facture', 'Pour couvrir les pertes et les imprévus', 'C\'est inutile', 'Pour réduire la production'], 'correct' => [1], 'explanation' => 'Les pertes (câbles, batteries, onduleur) et les besoins futurs justifient une marge de sécurité.'],
                        ['question' => 'Dans quelle unité exprime-t-on la consommation journalière d\'un foyer ?', 'options' => ['En ampères', 'En wattheures (Wh)', 'En volts', 'En watt-crête'], 'correct' => [1], 'explanation' => 'La consommation d\'énergie sur une période se mesure en wattheures (ou kilowattheures).'],
                    ],
                ],
                [
                    'title' => 'Niveau 4 — Dimensionner les panneaux',
                    'subtitle' => 'Calculer la puissance crête nécessaire',
                    'xp_reward' => 130,
                    'content' => "🎯 **Objectif** : déterminer combien de panneaux installer selon le besoin et l'ensoleillement.\n\n💡 La donnée clé est l'**irradiation journalière** exprimée en **heures équivalentes de soleil (HES)** ou kWh/m²/jour. Au Cameroun, on retient souvent 4 à 5 HES.\n\n📐 Formule simplifiée :\n```\nPuissance crête (Wc) = Energie journalière (Wh) / (HES × rendement système)\n```\nLe rendement système (pertes câbles, batterie, onduleur) est d'environ **0,7**.\n\n✅ Exemple : besoin de 1765 Wh/jour, 4,5 HES :\n```\nWc = 1765 / (4,5 × 0,7)\nWc = 1765 / 3,15 ≈ 560 Wc\n```\nAvec des panneaux de 300 Wc :\n```\nNombre = 560 / 300 ≈ 2 panneaux\n```\n\n⚠️ Pièges :\n• Sous-dimensionner = batteries jamais pleines, coupures.\n• Surdimensionner = surcoût inutile.\n• Tenir compte de l'**orientation** (vers le sud dans l'hémisphère nord, mais proche de l'horizontale près de l'équateur) et de l'**ombrage** (arbres, bâtiments).",
                    'questions' => [
                        ['question' => 'Que représentent les « heures équivalentes de soleil » (HES) ?', 'options' => ['Le nombre d\'heures où il fait jour', 'L\'irradiation journalière ramenée à des heures à 1000 W/m²', 'La durée de vie du panneau', 'Le temps de charge de la batterie'], 'correct' => [1], 'explanation' => 'Les HES traduisent l\'énergie solaire reçue en équivalent d\'heures à la puissance standard de 1000 W/m².'],
                        ['question' => 'Pour 2100 Wh/jour, 5 HES et un rendement de 0,7, quelle puissance crête faut-il ?', 'options' => ['Environ 300 Wc', 'Environ 600 Wc', 'Environ 1500 Wc', 'Environ 150 Wc'], 'correct' => [1], 'explanation' => 'Wc = 2100 / (5 × 0,7) = 2100 / 3,5 = 600 Wc.'],
                        ['question' => 'Quel risque en cas de sous-dimensionnement du champ solaire ?', 'options' => ['Trop de production', 'Batteries jamais pleines et coupures', 'Aucun risque', 'Panneaux qui surchauffent le réseau'], 'correct' => [1], 'explanation' => 'Un champ trop faible ne recharge pas assez les batteries, entraînant des coupures.'],
                    ],
                ],
                [
                    'title' => 'Niveau 5 — Batteries et stockage',
                    'subtitle' => 'Stocker l\'énergie pour la nuit',
                    'xp_reward' => 140,
                    'content' => "🎯 **Objectif** : choisir et dimensionner le stockage d'une installation autonome.\n\n💡 Le soleil produit le jour ; les **batteries** stockent l'énergie pour la nuit et les jours nuageux.\n\n✅ Technologies :\n• **Plomb-acide** : moins cher, lourd, durée de vie courte (~500 cycles), à ne pas décharger sous 50 %.\n• **Lithium (LiFePO4)** : plus cher, léger, longue durée (3000+ cycles), décharge profonde possible (~80-90 %).\n\n📐 Paramètres clés :\n• Capacité en **Ah** (ampère-heure) et tension (12/24/48 V).\n• **Profondeur de décharge (DoD)** : part utilisable.\n• **Jours d'autonomie** : combien de jours sans soleil.\n\n📐 Exemple : besoin 1765 Wh/jour, 2 jours d'autonomie, batterie 24 V plomb (DoD 50 %) :\n```\nCapacité = (1765 × 2) / (24 × 0,5)\nCapacité = 3530 / 12 ≈ 294 Ah\n```\n\n⚠️ Une batterie plomb déchargée trop profondément se dégrade vite. Le **régulateur de charge** (MPPT ou PWM) protège la batterie contre surcharge et décharge excessive.",
                    'questions' => [
                        ['question' => 'Quelle technologie de batterie offre la plus longue durée de vie en cycles ?', 'options' => ['Plomb-acide ouvert', 'Lithium LiFePO4', 'Plomb gel', 'Aucune ne dure'], 'correct' => [1], 'explanation' => 'Les batteries lithium LiFePO4 dépassent généralement 3000 cycles, bien plus que le plomb.'],
                        ['question' => 'Que désigne la « profondeur de décharge » (DoD) ?', 'options' => ['La tension maximale', 'La part de capacité réellement utilisable', 'Le poids de la batterie', 'Le nombre de panneaux'], 'correct' => [1], 'explanation' => 'La DoD indique quelle proportion de la capacité peut être déchargée sans abîmer la batterie.'],
                        ['question' => 'Quels éléments influencent le dimensionnement de la capacité batterie ?', 'options' => ['L\'énergie journalière à couvrir', 'Le nombre de jours d\'autonomie', 'La couleur des panneaux', 'La profondeur de décharge admissible'], 'correct' => [0, 1, 3], 'explanation' => 'La capacité dépend du besoin journalier, de l\'autonomie souhaitée et de la DoD admissible, pas de la couleur des panneaux.'],
                    ],
                ],
                [
                    'title' => 'Niveau 6 — L\'énergie éolienne',
                    'subtitle' => 'Produire avec le vent',
                    'xp_reward' => 150,
                    'content' => "🎯 **Objectif** : comprendre comment une éolienne produit de l'électricité et quand elle est pertinente.\n\n💡 Le vent fait tourner les **pales**, qui entraînent un **rotor** relié à une **génératrice** produisant de l'électricité.\n\n✅ Notions clés :\n• La puissance dépend du **cube de la vitesse du vent** : doubler le vent multiplie la puissance par 8.\n• Vitesse de **démarrage** : ~3 m/s ; vitesse nominale : ~12 m/s ; mise en sécurité au-delà.\n• On distingue éoliennes à **axe horizontal** (les plus courantes) et à **axe vertical**.\n\n📐 Puissance théorique :\n```\nP = 0,5 × ρ × A × V³ × Cp\n```\noù ρ = densité de l'air, A = surface balayée, V = vitesse du vent, Cp = coefficient de performance (limite de Betz ≈ 0,59).\n\n⚠️ Contexte africain :\n• Le vent est irrégulier ; les sites côtiers (Kribi, Limbé) ou les plateaux venteux sont plus favorables.\n• Souvent combinée au solaire dans un système **hybride** pour lisser la production.\n• Hauteur du mât importante : plus on monte, plus le vent est fort et régulier.",
                    'questions' => [
                        ['question' => 'De quoi dépend principalement la puissance d\'une éolienne ?', 'options' => ['Du cube de la vitesse du vent', 'De la couleur des pales', 'Du nombre de batteries', 'De la longueur du câble'], 'correct' => [0], 'explanation' => 'La puissance varie avec le cube de la vitesse du vent, ce qui rend les sites venteux très avantageux.'],
                        ['question' => 'Pourquoi monte-t-on l\'éolienne sur un mât élevé ?', 'options' => ['Pour la cacher', 'Parce que le vent est plus fort et régulier en hauteur', 'Pour économiser des câbles', 'Cela ne change rien'], 'correct' => [1], 'explanation' => 'En altitude, le vent rencontre moins d\'obstacles, il est plus fort et plus constant.'],
                        ['question' => 'Que désigne un système « hybride » ?', 'options' => ['Une éolienne sans pales', 'La combinaison de plusieurs sources, ex. solaire + éolien', 'Une batterie au lithium', 'Un panneau thermique'], 'correct' => [1], 'explanation' => 'Un système hybride associe plusieurs sources (solaire, éolien, groupe) pour fiabiliser la production.'],
                    ],
                ],
                [
                    'title' => 'Niveau 7 — Biomasse et biogaz',
                    'subtitle' => 'Valoriser les déchets organiques',
                    'xp_reward' => 160,
                    'content' => "🎯 **Objectif** : comprendre comment la matière organique devient énergie.\n\n💡 La **biomasse** regroupe les matières organiques (bois, résidus agricoles, déchets, fumier) qui peuvent produire de la chaleur, de l'électricité ou du gaz.\n\n✅ Filières principales :\n• **Combustion** : brûler le bois ou les résidus pour produire chaleur/électricité.\n• **Méthanisation** : la fermentation de déchets dans un **digesteur** produit du **biogaz** (riche en méthane CH₄).\n• Le biogaz sert à cuisiner, s'éclairer ou alimenter un groupe.\n\n📊 Exemple concret au Cameroun :\n| Intrant | Usage |\n|---|---|\n| Bouses de vache, fientes | Digesteur familial |\n| Résidus de manioc, déchets de marché | Biogaz communautaire |\n| Coques de cacao, balles de riz | Combustion / briquettes |\n\n⚠️ Atouts et limites :\n• Atout : valorise des déchets abondants, fournit aussi un **digestat** (engrais).\n• Limite : nécessite un apport régulier de matière et un entretien du digesteur.\n• Sécurité : le méthane est inflammable, ventilation et étanchéité indispensables.\n\nLa biomasse est précieuse en milieu rural agricole où les déchets organiques sont nombreux.",
                    'questions' => [
                        ['question' => 'Qu\'est-ce que le biogaz ?', 'options' => ['Un gaz issu du pétrole', 'Un gaz riche en méthane produit par fermentation de déchets organiques', 'De l\'air comprimé', 'Un type de batterie'], 'correct' => [1], 'explanation' => 'Le biogaz provient de la méthanisation de matières organiques et contient principalement du méthane.'],
                        ['question' => 'Quel sous-produit utile obtient-on en plus du biogaz dans un digesteur ?', 'options' => ['Du plastique', 'Du digestat utilisable comme engrais', 'De l\'essence', 'Du charbon'], 'correct' => [1], 'explanation' => 'La méthanisation laisse un digestat riche en nutriments, valorisable comme engrais agricole.'],
                        ['question' => 'Quels intrants conviennent à un digesteur de biogaz ?', 'options' => ['Bouses et fumier', 'Déchets de marché et résidus agricoles', 'Pierres et sable', 'Métaux et verre'], 'correct' => [0, 1], 'explanation' => 'Les matières organiques (fumier, résidus agricoles, déchets de marché) alimentent la méthanisation, pas les matériaux inertes.'],
                    ],
                ],
                [
                    'title' => 'Niveau 8 — Onduleurs et raccordement',
                    'subtitle' => 'Du courant continu à la prise',
                    'xp_reward' => 170,
                    'content' => "🎯 **Objectif** : maîtriser la conversion DC/AC et les schémas de raccordement.\n\n💡 Les panneaux et batteries fournissent du **continu (DC)**, mais la plupart des appareils fonctionnent en **alternatif (AC) 230 V**. L'**onduleur** convertit le DC en AC.\n\n✅ Types d'onduleurs :\n• **Onduleur off-grid** : alimente une maison isolée à partir des batteries.\n• **Onduleur réseau (grid-tie)** : injecte dans le réseau public.\n• **Onduleur hybride** : gère panneaux, batteries ET réseau/groupe.\n\n⚠️ Qualité du signal :\n• **Sinus pur** : indispensable pour appareils sensibles (frigo, ordinateurs, pompes).\n• **Pseudo-sinus** : moins cher, peut endommager certains moteurs.\n\n📐 Chaîne typique off-grid :\n```\nPanneaux → Régulateur MPPT → Batteries → Onduleur → Tableau AC → Appareils\n```\n\n🔌 Sécurité de raccordement :\n• Fusibles et **disjoncteurs** côté DC et côté AC.\n• **Mise à la terre** obligatoire.\n• Sectionneur pour intervenir sans danger.\n\nUn câblage propre et protégé évite incendies et électrocutions.",
                    'questions' => [
                        ['question' => 'Quel est le rôle de l\'onduleur ?', 'options' => ['Stocker l\'énergie', 'Convertir le courant continu en courant alternatif', 'Produire de la chaleur', 'Augmenter l\'irradiation'], 'correct' => [1], 'explanation' => 'L\'onduleur transforme le courant continu (DC) des panneaux/batteries en courant alternatif (AC) utilisable.'],
                        ['question' => 'Pourquoi privilégier un onduleur à sinus pur ?', 'options' => ['Il est moins cher', 'Il protège les appareils sensibles', 'Il produit du DC', 'Il remplace les batteries'], 'correct' => [1], 'explanation' => 'Le sinus pur fournit un signal propre, sûr pour les appareils électroniques et les moteurs.'],
                        ['question' => 'Quels dispositifs de sécurité doit comporter un raccordement ?', 'options' => ['Disjoncteurs DC et AC', 'Mise à la terre', 'Une simple rallonge', 'Un sectionneur'], 'correct' => [0, 1, 3], 'explanation' => 'Protections (disjoncteurs), mise à la terre et sectionneur sont essentiels ; une simple rallonge ne protège pas.'],
                    ],
                ],
                [
                    'title' => 'Niveau 9 — Maintenance et rentabilité',
                    'subtitle' => 'Faire durer et amortir l\'installation',
                    'xp_reward' => 185,
                    'content' => "🎯 **Objectif** : entretenir l'installation et évaluer son intérêt économique.\n\n💡 Une installation bien entretenue dure 20 à 25 ans pour les panneaux.\n\n🔧 Maintenance préventive :\n• Nettoyer les panneaux (poussière de l'harmattan, fientes) tous les 1 à 3 mois.\n• Vérifier le serrage des connexions et l'absence de corrosion.\n• Contrôler le niveau d'électrolyte des batteries plomb.\n• Surveiller les indicateurs de l'onduleur/régulateur.\n• Élaguer la végétation qui crée de l'ombre.\n\n📐 Rentabilité — temps de retour sur investissement :\n```\nRetour (années) = Coût total / Économie annuelle\n```\nExemple : installation à 1 800 000 FCFA, économie de 300 000 FCFA/an (groupe + carburant évités) :\n```\nRetour = 1 800 000 / 300 000 = 6 ans\n```\n\n✅ Comparer au coût d'un groupe électrogène : carburant, bruit, entretien fréquent. Sur la durée, le solaire est souvent gagnant en zone non raccordée.\n\n⚠️ Tenir compte du remplacement des batteries (tous les 3 à 10 ans selon la technologie) dans le calcul de rentabilité.",
                    'questions' => [
                        ['question' => 'Pourquoi nettoyer régulièrement les panneaux solaires ?', 'options' => ['Pour les rendre plus beaux', 'Parce que la poussière et les saletés réduisent la production', 'Pour augmenter leur tension à vide', 'Ce n\'est jamais nécessaire'], 'correct' => [1], 'explanation' => 'La saleté bloque la lumière et fait chuter le rendement, surtout pendant la saison de l\'harmattan.'],
                        ['question' => 'Une installation coûte 1 200 000 FCFA et économise 200 000 FCFA/an. Quel est le temps de retour ?', 'options' => ['2 ans', '6 ans', '12 ans', '20 ans'], 'correct' => [1], 'explanation' => 'Retour = 1 200 000 / 200 000 = 6 ans.'],
                        ['question' => 'Quel coût doit-on intégrer au calcul de rentabilité dans le temps ?', 'options' => ['Le remplacement des batteries', 'Le prix du soleil', 'La taxe sur le vent', 'Aucun coût récurrent'], 'correct' => [0], 'explanation' => 'Les batteries ont une durée de vie limitée et leur remplacement doit être anticipé dans le budget.'],
                    ],
                ],
                [
                    'title' => 'Niveau 10 — Solutions off-grid en Afrique',
                    'subtitle' => 'Électrifier le dernier kilomètre',
                    'xp_reward' => 200,
                    'content' => "🎯 **Objectif** : concevoir des solutions autonomes adaptées au contexte rural africain.\n\n💡 L'**off-grid** désigne les systèmes non raccordés au réseau national, essentiels là où les lignes n'arrivent pas.\n\n✅ Échelles de solutions :\n• **Solar Home System (SHS)** : kit individuel (panneau + batterie + lampes + USB) pour un foyer.\n• **Mini-réseau (mini-grid)** : production solaire/hybride alimentant tout un village via un petit réseau local.\n• **Pico-solaire** : lampes solaires portables, recharge téléphone.\n\n💳 Modèles d'accès innovants :\n• **PAYG (Pay-As-You-Go)** : l'usager paie par mobile money (MTN MoMo, Orange Money) de petites tranches qui débloquent l'usage du kit. Cela rend le solaire accessible sans gros apport initial.\n\n⚠️ Facteurs de réussite :\n• Dimensionnement réaliste, formation de l'usager, service après-vente local.\n• Maintenance assurée par des techniciens de proximité — ton futur métier !\n\n🏆 **Félicitations !** Tu maîtrises désormais les fondamentaux des énergies renouvelables. Débouchés : technicien installateur solaire, dimensionneur d'installations, agent de maintenance, gestionnaire de mini-réseau, entrepreneur PAYG. Un secteur en pleine croissance qui électrifie l'Afrique et crée des emplois durables. Continue à pratiquer sur le terrain et vise les certifications professionnelles pour évoluer vers chef de projet ou ingénieur énergie !",
                    'questions' => [
                        ['question' => 'Que désigne un « mini-réseau » (mini-grid) ?', 'options' => ['Un seul panneau sur un toit', 'Une production qui alimente tout un village via un réseau local', 'Une batterie de téléphone', 'Le réseau national entier'], 'correct' => [1], 'explanation' => 'Un mini-réseau alimente plusieurs foyers d\'un village à partir d\'une production locale, indépendamment du réseau national.'],
                        ['question' => 'Comment fonctionne le modèle PAYG ?', 'options' => ['On paie tout d\'un coup', 'On paie par petites tranches via mobile money pour débloquer l\'usage', 'C\'est gratuit à vie', 'On paie en bois de chauffe'], 'correct' => [1], 'explanation' => 'Le Pay-As-You-Go permet de régler par mobile money des micro-paiements qui activent le kit, sans gros apport initial.'],
                        ['question' => 'Quels sont des débouchés métiers dans les énergies renouvelables ?', 'options' => ['Technicien installateur solaire', 'Gestionnaire de mini-réseau', 'Pêcheur en haute mer', 'Agent de maintenance'], 'correct' => [0, 1, 3], 'explanation' => 'Installateur, gestionnaire de mini-réseau et agent de maintenance sont des métiers directs du secteur ; le reste non.'],
                    ],
                ],
            ],
        ]);

        $this->command->info('  ✓ Roadmap Énergies Renouvelables créée (10 niveaux).');
    }
}
