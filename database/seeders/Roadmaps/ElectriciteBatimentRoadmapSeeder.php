<?php

namespace Database\Seeders\Roadmaps;

use Illuminate\Database\Seeder;

/**
 * Roadmap Électricité du Bâtiment — du débutant à l'électricien capable de câbler une installation et travailler en sécurité.
 */
class ElectriciteBatimentRoadmapSeeder extends Seeder
{
    use RoadmapSeederHelper;

    public function run(): void
    {
        $this->createRoadmap([
            'title' => 'Deviens Électricien du Bâtiment',
            'slug' => 'electricite-batiment',
            'domain' => 'btp',
            'description' => "Apprends le métier d'électricien du bâtiment pas à pas : comprendre l'électricité, lire un schéma, câbler une installation, poser un tableau électrique, protéger les circuits et travailler en sécurité. Une formation pratique pensée pour le contexte camerounais (réseau ENEO 230 V, chantiers de Douala et Yaoundé) qui te prépare à un métier très demandé.",
            'objectives' => "Comprendre tension, intensité, résistance et la loi d'Ohm\nLire et dessiner un schéma électrique simple\nChoisir et installer disjoncteurs et différentiels\nCâbler une installation domestique du tableau aux prises\nRéaliser une mise à la terre conforme\nDiagnostiquer et dépanner une panne en toute sécurité",
            'icon' => '⚡',
            'color' => '#F59E0B',
            'difficulty' => 'beginner',
            'required_packs' => [],
            'pass_threshold' => 70,
            'order' => 20,
            'levels' => [
                [
                    'title' => 'Niveau 1 — Les bases de l\'électricité',
                    'subtitle' => 'Tension, intensité, résistance',
                    'xp_reward' => 100,
                    'content' => "🎯 **Objectif** : comprendre les trois grandeurs fondamentales de l'électricité.\n\n💡 L'électricité, c'est le déplacement d'électrons dans un conducteur (le fil de cuivre). On la décrit avec trois grandeurs :\n\n• **Tension (U)** : la « pression » électrique, mesurée en volts (V). Au Cameroun, ENEO distribue du **230 V** en monophasé.\n• **Intensité (I)** : le débit du courant, mesuré en ampères (A). C'est ce qui « passe » réellement dans le fil.\n• **Résistance (R)** : l'opposition au passage du courant, en ohms (Ω).\n\nUne image simple : imagine un tuyau d'eau.\n• La tension = la pression de l'eau\n• L'intensité = le débit (litres/seconde)\n• La résistance = le robinet plus ou moins ouvert\n\n⚠️ Le courant **continu (DC)** vient des batteries et panneaux solaires ; le courant **alternatif (AC)** vient du réseau ENEO et change de sens 50 fois par seconde (50 Hz).\n\n✅ Retiens : volt = tension, ampère = intensité, ohm = résistance. Ce vocabulaire est la base de tout le métier.",
                    'questions' => [
                        ['question' => 'Quelle grandeur se mesure en ampères ?', 'options' => ['La tension', 'L\'intensité', 'La résistance', 'La puissance'], 'correct' => [1], 'explanation' => 'L\'intensité (le débit du courant) se mesure en ampères (A).'],
                        ['question' => 'Quelle est la tension standard du réseau monophasé ENEO au Cameroun ?', 'options' => ['12 V', '110 V', '230 V', '400 V'], 'correct' => [2], 'explanation' => 'Le réseau domestique camerounais distribue du 230 V en monophasé.'],
                        ['question' => 'La résistance électrique s\'exprime en :', 'options' => ['Volts', 'Watts', 'Ohms', 'Hertz'], 'correct' => [2], 'explanation' => 'La résistance se mesure en ohms (Ω).'],
                    ],
                ],
                [
                    'title' => 'Niveau 2 — La loi d\'Ohm',
                    'subtitle' => 'La relation U = R × I',
                    'xp_reward' => 110,
                    'content' => "🎯 **Objectif** : maîtriser la loi d'Ohm, l'outil de calcul de l'électricien.\n\n💡 La loi d'Ohm relie les trois grandeurs vues au niveau 1 :\n\n```\nU = R × I\n```\n\nOù U est en volts, R en ohms et I en ampères. On peut la retourner :\n\n```\nI = U / R\nR = U / I\n```\n\n**Exemple concret** : une lampe de résistance 460 Ω branchée sur 230 V.\n\n```\nI = U / R = 230 / 460 = 0,5 A\n```\n\nLa lampe consomme 0,5 ampère.\n\n💡 La **puissance** se calcule avec :\n\n```\nP = U × I   (en watts)\n```\n\nUn fer à repasser de 1150 W sur 230 V tire :\n\n```\nI = P / U = 1150 / 230 = 5 A\n```\n\n⚠️ Savoir calculer l'intensité est vital : c'est elle qui détermine la **section du câble** et le **calibre du disjoncteur**. Un calcul faux = surchauffe et risque d'incendie.\n\n✅ Mémorise le triangle U / R-I et la formule P = U × I.",
                    'questions' => [
                        ['question' => 'D\'après la loi d\'Ohm, comment calcule-t-on l\'intensité ?', 'options' => ['I = U × R', 'I = U / R', 'I = R / U', 'I = U + R'], 'correct' => [1], 'explanation' => 'En isolant I dans U = R × I, on obtient I = U / R.'],
                        ['question' => 'Un appareil de 2300 W est branché sur 230 V. Quelle intensité tire-t-il ?', 'options' => ['1 A', '5 A', '10 A', '23 A'], 'correct' => [2], 'explanation' => 'I = P / U = 2300 / 230 = 10 A.'],
                        ['question' => 'La puissance électrique se calcule par :', 'options' => ['P = U / I', 'P = R × I', 'P = U × I', 'P = U + I'], 'correct' => [2], 'explanation' => 'La puissance en courant continu/résistif vaut P = U × I (watts).'],
                    ],
                ],
                [
                    'title' => 'Niveau 3 — Lire un schéma électrique',
                    'subtitle' => 'Symboles et représentation',
                    'xp_reward' => 120,
                    'content' => "🎯 **Objectif** : savoir lire et dessiner un schéma électrique simple.\n\n💡 Un schéma utilise des **symboles normalisés** pour représenter les composants. Quelques symboles courants :\n\n| Composant | Symbole textuel |\n|-----------|-----------------|\n| Lampe | cercle avec une croix ⊗ |\n| Interrupteur | trait coupé avec un levier |\n| Prise de courant | demi-cercle |\n| Disjoncteur | rectangle avec « x » |\n| Terre | trois traits décroissants ⏚ |\n\n💡 On distingue deux types de schémas :\n• **Schéma unifilaire** : un seul trait représente plusieurs conducteurs, on note le nombre de fils dessus. Utile pour avoir une vue d'ensemble du tableau.\n• **Schéma multifilaire (développé)** : chaque conducteur (phase, neutre, terre) est dessiné séparément. Utile pour le câblage réel.\n\n**Repérage des fils par couleur** :\n• Phase (L) : rouge, marron ou noir\n• Neutre (N) : bleu\n• Terre (PE) : vert/jaune\n\n⚠️ Ne jamais utiliser le vert/jaune pour autre chose que la terre : c'est une règle de sécurité universelle.\n\n✅ Un bon électricien lit le schéma avant de toucher un fil : il sait où va chaque conducteur.",
                    'questions' => [
                        ['question' => 'De quelle couleur est obligatoirement le conducteur de terre (PE) ?', 'options' => ['Bleu', 'Rouge', 'Vert/jaune', 'Noir'], 'correct' => [2], 'explanation' => 'Le conducteur de protection (terre) est toujours vert/jaune et rien d\'autre.'],
                        ['question' => 'Dans un schéma unifilaire, un seul trait peut représenter :', 'options' => ['Un seul fil obligatoirement', 'Plusieurs conducteurs', 'Uniquement la terre', 'Un interrupteur'], 'correct' => [1], 'explanation' => 'Le schéma unifilaire condense plusieurs conducteurs en un trait, avec leur nombre indiqué.'],
                        ['question' => 'Le conducteur neutre (N) est repéré par la couleur :', 'options' => ['Bleu', 'Vert/jaune', 'Rouge', 'Orange'], 'correct' => [0], 'explanation' => 'Le neutre est toujours repéré en bleu.'],
                    ],
                ],
                [
                    'title' => 'Niveau 4 — Circuits et protections',
                    'subtitle' => 'Disjoncteur et différentiel',
                    'xp_reward' => 130,
                    'content' => "🎯 **Objectif** : comprendre le rôle des dispositifs de protection.\n\n💡 Une installation doit protéger les **biens** (incendie) et les **personnes** (électrocution). Deux organes clés :\n\n• **Le disjoncteur** : protège le circuit contre les **surcharges** (trop d'appareils) et les **courts-circuits** (phase qui touche le neutre). Il coupe le courant et se réarme. Son **calibre** (10 A, 16 A, 20 A…) doit être adapté à la section du câble.\n\n• **Le différentiel (interrupteur ou disjoncteur différentiel)** : protège les personnes. Il compare le courant qui entre (phase) et celui qui ressort (neutre). S'il manque du courant — il « fuit » à la terre, par exemple à travers un corps humain — il coupe en quelques millisecondes.\n\n💡 La **sensibilité** d'un différentiel se note en milliampères. Pour protéger les personnes, on utilise du **30 mA** :\n\n```\nDéséquilibre détecté ≥ 30 mA  →  coupure immédiate\n```\n\n⚠️ Un disjoncteur ne protège PAS contre l'électrocution. Seul le différentiel le fait. Les deux sont complémentaires, jamais interchangeables.\n\n✅ Règle d'or : surcharge/court-circuit → disjoncteur ; fuite à la terre → différentiel 30 mA.",
                    'questions' => [
                        ['question' => 'Quel dispositif protège les personnes contre l\'électrocution ?', 'options' => ['Le disjoncteur', 'Le différentiel 30 mA', 'Le fusible de phase', 'Le compteur'], 'correct' => [1], 'explanation' => 'Le différentiel détecte les fuites de courant à la terre et protège les personnes.'],
                        ['question' => 'Le disjoncteur protège principalement contre :', 'options' => ['Les surcharges et courts-circuits', 'Les coupures ENEO', 'La foudre directe', 'Les variations de fréquence'], 'correct' => [0], 'explanation' => 'Le disjoncteur coupe en cas de surcharge ou de court-circuit.'],
                        ['question' => 'Parmi ces affirmations, lesquelles sont correctes ? (plusieurs réponses)', 'options' => ['Le différentiel compare phase et neutre', 'Un différentiel 30 mA protège les personnes', 'Le disjoncteur protège contre l\'électrocution', 'Le calibre du disjoncteur dépend de la section du câble'], 'correct' => [0, 1, 3], 'explanation' => 'Le différentiel compare les courants et protège les personnes ; le calibre suit la section du câble ; mais le disjoncteur seul ne protège PAS de l\'électrocution.'],
                    ],
                ],
                [
                    'title' => 'Niveau 5 — Câblage d\'une installation',
                    'subtitle' => 'Sections de fils et circuits dédiés',
                    'xp_reward' => 140,
                    'content' => "🎯 **Objectif** : savoir choisir la section des conducteurs et organiser les circuits.\n\n💡 Chaque type de circuit a une **section de câble** et un **calibre de disjoncteur** adaptés. Plus l'intensité est forte, plus la section (en mm²) doit être grande.\n\n| Circuit | Section (cuivre) | Disjoncteur |\n|---------|------------------|-------------|\n| Éclairage | 1,5 mm² | 10 A |\n| Prises de courant | 2,5 mm² | 16 A |\n| Gros électroménager | 2,5 mm² | 20 A |\n| Chauffe-eau / climatiseur | 2,5 à 6 mm² | 20–32 A |\n\n💡 Principes de câblage :\n• Séparer les circuits : éclairage et prises ne sont pas sur le même disjoncteur.\n• Limiter le nombre de points par circuit (ex. 8 prises max sur un circuit 16 A).\n• Toujours tirer le conducteur de **terre** vert/jaune jusqu'à chaque prise et appareil métallique.\n\n⚠️ Un câble sous-dimensionné chauffe : sur un chantier à Douala, brancher un climatiseur sur du 1,5 mm² peut faire fondre la gaine et déclencher un incendie.\n\n✅ La règle : la section du câble doit toujours « tenir » l'intensité, et le disjoncteur protège ce câble.",
                    'questions' => [
                        ['question' => 'Quelle section de câble utilise-t-on pour un circuit de prises de courant standard ?', 'options' => ['1 mm²', '1,5 mm²', '2,5 mm²', '0,75 mm²'], 'correct' => [2], 'explanation' => 'Les circuits de prises se câblent en 2,5 mm² avec un disjoncteur 16 A.'],
                        ['question' => 'Pourquoi sépare-t-on les circuits éclairage et prises ?', 'options' => ['Pour faire des économies de fil', 'Pour qu\'une panne sur un circuit n\'éteigne pas tout', 'C\'est interdit de les séparer', 'Pour augmenter la tension'], 'correct' => [1], 'explanation' => 'Des circuits séparés limitent les pannes et facilitent le dépannage.'],
                        ['question' => 'Que risque-t-on avec un câble de section trop faible pour l\'intensité ?', 'options' => ['Rien de grave', 'Une surchauffe pouvant causer un incendie', 'Une baisse de la fréquence', 'Une augmentation de la tension'], 'correct' => [1], 'explanation' => 'Un câble sous-dimensionné chauffe et peut provoquer un incendie.'],
                    ],
                ],
                [
                    'title' => 'Niveau 6 — Le tableau électrique',
                    'subtitle' => 'Coeur de l\'installation',
                    'xp_reward' => 150,
                    'content' => "🎯 **Objectif** : comprendre l'organisation du tableau de répartition.\n\n💡 Le **tableau électrique** (ou tableau de répartition) est le centre de l'installation : il reçoit l'arrivée du réseau et distribue le courant vers chaque circuit, en les protégeant.\n\nOrdre logique de haut en bas :\n\n```\nArrivée ENEO (compteur)\n        │\n  Disjoncteur de branchement (général)\n        │\n  Interrupteur différentiel 30 mA\n        │\n  ┌──────┬──────┬──────┐\n  D10A   D16A   D20A   D16A   (disjoncteurs divisionnaires)\n  écl.   prises chauffe prises\n```\n\n💡 Bonnes pratiques :\n• Regrouper plusieurs circuits sous un même différentiel 30 mA.\n• Laisser des **réserves** (emplacements libres) pour de futures extensions.\n• Étiqueter chaque disjoncteur (« Prises cuisine », « Éclairage salon »).\n• Les conducteurs arrivent sur des **borniers** (peignes ou répartiteurs).\n\n⚠️ Un tableau mal organisé ou non étiqueté rend le dépannage dangereux et lent. Le repérage est obligatoire.\n\n✅ Le tableau doit être accessible, fermé, et chaque circuit identifié clairement.",
                    'questions' => [
                        ['question' => 'Quel élément se place en tête de tableau, juste après l\'arrivée ENEO ?', 'options' => ['Une prise', 'Le disjoncteur de branchement général', 'Une lampe', 'Le conducteur de terre'], 'correct' => [1], 'explanation' => 'Le disjoncteur général de branchement protège et coupe toute l\'installation en amont.'],
                        ['question' => 'Pourquoi étiquette-t-on chaque disjoncteur du tableau ?', 'options' => ['Pour décorer', 'Pour identifier rapidement le circuit lors d\'un dépannage', 'Pour augmenter la puissance', 'C\'est facultatif et inutile'], 'correct' => [1], 'explanation' => 'L\'étiquetage permet d\'identifier vite chaque circuit et de dépanner en sécurité.'],
                        ['question' => 'À quoi servent les emplacements libres (réserves) dans un tableau ?', 'options' => ['À rien', 'À ajouter de futurs circuits', 'À stocker des fusibles', 'À refroidir le tableau'], 'correct' => [1], 'explanation' => 'Les réserves permettent d\'ajouter facilement de nouveaux circuits plus tard.'],
                    ],
                ],
                [
                    'title' => 'Niveau 7 — Mise à la terre et sécurité',
                    'subtitle' => 'Protéger les personnes',
                    'xp_reward' => 160,
                    'content' => "🎯 **Objectif** : comprendre et réaliser une mise à la terre efficace.\n\n💡 La **mise à la terre** évacue vers le sol les courants de défaut. Si un fil de phase touche la carcasse métallique d'un appareil, le courant part dans la terre au lieu de traverser la personne qui le touche. Associée au différentiel 30 mA, elle sauve des vies.\n\nÉléments de la prise de terre :\n• Un **piquet de terre** (barre en acier galvanisé ou cuivre) enfoncé dans le sol humide.\n• Un **conducteur de terre** (vert/jaune) reliant le piquet à la **barrette de mesure**.\n• La **liaison équipotentielle** qui relie les masses métalliques (tuyaux d'eau, structure).\n\n💡 On mesure la **résistance de terre** : plus elle est basse, mieux c'est. Une valeur courante visée :\n\n```\nRésistance de terre ≤ 100 Ω  (souvent on vise beaucoup moins)\n```\n\nUn sol sec à Maroua donne une mauvaise terre : on humidifie ou on ajoute des piquets.\n\n⚠️ Sans terre, le différentiel ne peut pas détecter certains défauts : prise de terre + différentiel = duo indissociable.\n\n✅ Toute masse métallique accessible doit être reliée à la terre.",
                    'questions' => [
                        ['question' => 'À quoi sert la mise à la terre ?', 'options' => ['À augmenter la tension', 'À évacuer les courants de défaut vers le sol', 'À éclairer davantage', 'À réduire la facture'], 'correct' => [1], 'explanation' => 'La terre évacue les courants de défaut et protège les personnes en cas de masse sous tension.'],
                        ['question' => 'Plus la résistance de la prise de terre est ___, meilleure est la protection.', 'options' => ['élevée', 'basse', 'variable', 'nulle et infinie à la fois'], 'correct' => [1], 'explanation' => 'Une faible résistance de terre permet d\'écouler efficacement les courants de défaut.'],
                        ['question' => 'Quelles affirmations sont vraies ? (plusieurs réponses)', 'options' => ['Un sol humide améliore la prise de terre', 'Le conducteur de terre est vert/jaune', 'La terre seule suffit, le différentiel est inutile', 'Les masses métalliques accessibles doivent être reliées à la terre'], 'correct' => [0, 1, 3], 'explanation' => 'Un sol humide aide, la terre est vert/jaune et les masses se relient à la terre ; mais terre et différentiel sont complémentaires, l\'un ne remplace pas l\'autre.'],
                    ],
                ],
                [
                    'title' => 'Niveau 8 — Normes et conformité',
                    'subtitle' => 'Travailler dans les règles',
                    'xp_reward' => 170,
                    'content' => "🎯 **Objectif** : connaître les règles qui encadrent une installation conforme.\n\n💡 Une installation électrique doit respecter des **normes** pour être sûre et acceptée par le distributeur (ENEO) et l'assurance. La norme de référence internationale est la **CEI 60364** ; en zone francophone on s'appuie souvent sur la **NF C 15-100** comme guide de bonnes pratiques.\n\nQuelques exigences clés :\n• Un **différentiel 30 mA** obligatoire sur les circuits prises et pièces d'eau.\n• Section minimale : 1,5 mm² éclairage, 2,5 mm² prises.\n• Volumes de sécurité dans la **salle de bain** : pas de prise ni d'interrupteur trop près de la douche/baignoire.\n• Hauteur des prises et interrupteurs normalisée pour l'accessibilité.\n• Conducteur de terre sur tous les circuits.\n\n💡 Au Cameroun, l'installation doit être conforme avant le **raccordement et la pose du compteur ENEO**. Un contrôle peut être exigé.\n\n⚠️ Une installation non conforme peut être refusée au raccordement, et en cas d'incendie l'assurance peut ne pas indemniser.\n\n✅ Travailler aux normes, c'est protéger le client, l'occupant et ta propre responsabilité d'électricien.",
                    'questions' => [
                        ['question' => 'Quelle norme internationale sert de référence pour les installations basse tension ?', 'options' => ['CEI 60364', 'ISO 9001', 'OHADA', 'HACCP'], 'correct' => [0], 'explanation' => 'La CEI 60364 (déclinée en NF C 15-100) encadre les installations électriques basse tension.'],
                        ['question' => 'Dans une salle de bain, que prévoient les normes concernant les prises ?', 'options' => ['Aucune restriction', 'Des volumes de sécurité interdisant les prises près de la douche', 'Des prises uniquement au plafond', 'Le doublement de la tension'], 'correct' => [1], 'explanation' => 'Les normes définissent des volumes de sécurité interdisant prises et interrupteurs trop près de l\'eau.'],
                        ['question' => 'Que peut-il arriver si une installation n\'est pas conforme ?', 'options' => ['Elle fonctionne mieux', 'Le raccordement ENEO peut être refusé et l\'assurance ne pas couvrir un sinistre', 'La tension augmente automatiquement', 'Rien, les normes sont facultatives'], 'correct' => [1], 'explanation' => 'Une installation non conforme peut être refusée au raccordement et non indemnisée en cas de sinistre.'],
                    ],
                ],
                [
                    'title' => 'Niveau 9 — Dépannage et diagnostic',
                    'subtitle' => 'Trouver et réparer la panne',
                    'xp_reward' => 185,
                    'content' => "🎯 **Objectif** : diagnostiquer méthodiquement une panne en toute sécurité.\n\n💡 Le dépannage suit une **méthode** : on ne tâtonne pas au hasard.\n\n1. **Sécuriser** : couper le disjoncteur concerné, vérifier l'absence de tension avec un **VAT** (vérificateur d'absence de tension) ou un multimètre.\n2. **Observer** : quel circuit est touché ? Le disjoncteur a-t-il déclenché ? Le différentiel ?\n3. **Tester** avec le multimètre :\n\n```\nMode voltmètre  → mesurer la tension (présence du 230 V ?)\nMode ohmmètre   → mesurer la continuité d'un fil ou la résistance\n```\n\n4. **Localiser** : circuit par circuit, point par point, jusqu'à trouver le défaut (fil coupé, faux contact, court-circuit, appareil défectueux).\n5. **Réparer puis re-tester** avant remise sous tension.\n\n💡 Pannes fréquentes :\n• Différentiel qui saute → fuite à la terre (appareil humide, fil dénudé).\n• Disjoncteur qui saute → surcharge ou court-circuit.\n• Plus de courant sur une prise → faux contact dans une boîte de dérivation.\n\n⚠️ Toujours consigner (couper et signaler) avant d'intervenir. Ne jamais travailler sous tension sans habilitation.\n\n✅ Méthode + multimètre + sécurité = dépannage efficace.",
                    'questions' => [
                        ['question' => 'Quelle est la toute première étape avant d\'intervenir sur un circuit en panne ?', 'options' => ['Changer tous les câbles', 'Sécuriser : couper et vérifier l\'absence de tension', 'Appeler ENEO', 'Augmenter le calibre du disjoncteur'], 'correct' => [1], 'explanation' => 'On sécurise d\'abord : couper le circuit et vérifier l\'absence de tension avec un VAT.'],
                        ['question' => 'Un différentiel qui saute régulièrement indique le plus souvent :', 'options' => ['Une surcharge normale', 'Une fuite de courant à la terre', 'Une tension trop basse', 'Un manque d\'ampoules'], 'correct' => [1], 'explanation' => 'Le différentiel détecte une fuite de courant à la terre (appareil humide, fil dénudé).'],
                        ['question' => 'Quel mode du multimètre sert à vérifier la continuité d\'un fil ?', 'options' => ['Mode voltmètre', 'Mode ohmmètre', 'Mode fréquencemètre', 'Mode lumière'], 'correct' => [1], 'explanation' => 'L\'ohmmètre (continuité) permet de savoir si un fil est coupé ou non.'],
                    ],
                ],
                [
                    'title' => 'Niveau 10 — Habilitation électrique et métier',
                    'subtitle' => 'Travailler en pro, en sécurité',
                    'xp_reward' => 200,
                    'content' => "🎯 **Objectif** : connaître l'habilitation électrique et lancer ta carrière.\n\n💡 L'**habilitation électrique** est la reconnaissance, par l'employeur, qu'un travailleur est capable d'effectuer des tâches électriques en sécurité. Elle s'appuie sur des **symboles** :\n\n| Symbole | Signification |\n|---------|---------------|\n| B | Basse tension |\n| H | Haute tension |\n| 0 | Travaux non électriques près d'installations |\n| 1 | Exécutant électricien |\n| 2 | Chargé de travaux |\n| R | Intervention/dépannage BT |\n| V | Travail au voisinage |\n\nExemple : un **B1V** est un exécutant en basse tension autorisé à travailler au voisinage.\n\n💡 Les règles d'or de la sécurité (consignation) :\n• Séparer (couper l'alimentation)\n• Condamner (verrouiller, signaler)\n• Vérifier l'absence de tension (VAT)\n• Mettre à la terre si nécessaire\n\nÉquipements : gants isolants, chaussures de sécurité, VAT, outils isolés.\n\n⚠️ Travailler sous tension sans habilitation, c'est risquer sa vie et engager sa responsabilité.\n\n🏆 **Félicitations !** Tu maîtrises les fondamentaux de l'électricité du bâtiment : calculs, schémas, protections, câblage, terre, normes, dépannage et sécurité. Tu peux maintenant viser un poste d'**aide-électricien** puis d'**électricien installateur**, te spécialiser en **domotique**, en **solaire photovoltaïque** (très porteur en Afrique) ou créer ta propre **entreprise d'installation électrique**. Le bâtiment recrute : continue à pratiquer sur le terrain et passe tes habilitations. ⚡",
                    'questions' => [
                        ['question' => 'Que signifie le symbole « B » dans une habilitation électrique ?', 'options' => ['Haute tension', 'Basse tension', 'Bâtiment', 'Batterie'], 'correct' => [1], 'explanation' => 'La lettre B désigne les travaux en basse tension.'],
                        ['question' => 'Dans la procédure de consignation, après avoir séparé et condamné, on doit :', 'options' => ['Remettre le courant immédiatement', 'Vérifier l\'absence de tension (VAT)', 'Quitter le chantier', 'Augmenter la tension'], 'correct' => [1], 'explanation' => 'Après séparation et condamnation, on vérifie l\'absence de tension avant toute intervention.'],
                        ['question' => 'Quels sont des débouchés réalistes après cette formation ? (plusieurs réponses)', 'options' => ['Aide-électricien puis électricien installateur', 'Spécialiste en solaire photovoltaïque', 'Pilote de ligne aérienne', 'Créateur d\'une entreprise d\'installation électrique'], 'correct' => [0, 1, 3], 'explanation' => 'Les débouchés naturels sont l\'électricité du bâtiment, le solaire et l\'entrepreneuriat ; pas l\'aviation.'],
                    ],
                ],
            ],
        ]);

        $this->command->info('  ✓ Roadmap Électricité du Bâtiment créée (10 niveaux).');
    }
}
