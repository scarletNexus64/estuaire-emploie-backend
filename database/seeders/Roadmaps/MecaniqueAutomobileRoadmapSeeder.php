<?php

namespace Database\Seeders\Roadmaps;

use Illuminate\Database\Seeder;

/**
 * Roadmap Mécanique Automobile — Apprends à diagnostiquer, entretenir et réparer un véhicule, de l'anatomie de base à la relation client.
 */
class MecaniqueAutomobileRoadmapSeeder extends Seeder
{
    use RoadmapSeederHelper;

    public function run(): void
    {
        $this->createRoadmap([
            'title' => 'Deviens Mécanicien Automobile',
            'slug' => 'mecanique-automobile',
            'domain' => 'artisanat',
            'description' => "Parcours complet pour apprendre le métier de mécanicien automobile, de l'anatomie d'un véhicule jusqu'à la relation client. Adapté au contexte africain (parc roulant ancien, routes difficiles, débrouille atelier). Du débutant à l'intermédiaire, pas à pas.",
            'objectives' => "Identifier les grands systèmes d'un véhicule et leur rôle\nComprendre le cycle du moteur 4 temps\nMaîtriser le système de freinage et la transmission\nLire un circuit électrique et faire un diagnostic OBD\nRéaliser l'entretien courant (vidange, filtres, pneus)\nTravailler en sécurité et bien conseiller le client",
            'icon' => '🔧',
            'color' => '#DC2626',
            'difficulty' => 'beginner',
            'required_packs' => [],
            'pass_threshold' => 70,
            'order' => 20,
            'levels' => [
                [
                    'title' => 'Niveau 1 — Anatomie d\'un véhicule',
                    'subtitle' => 'Les grands ensembles et leur rôle',
                    'xp_reward' => 100,
                    'content' => "🎯 **Objectif** : reconnaître les grands ensembles d'une voiture et savoir à quoi ils servent.\n\n💡 Une automobile se découpe en familles de systèmes :\n• **Le moteur** (groupe motopropulseur) : produit l'énergie en brûlant le carburant.\n• **La transmission** : embrayage, boîte de vitesses, arbres ; elle transmet la force aux roues.\n• **Le châssis et les liaisons au sol** : suspension, direction, pneus.\n• **Le freinage** : ralentit et immobilise le véhicule.\n• **L'électricité** : batterie, alternateur, démarreur, éclairage, calculateurs.\n• **La carrosserie** : structure et protection.\n\n✅ Vocabulaire utile à Douala comme à Yaoundé : on parle de *capot* (avant moteur), *coffre* (arrière), *soubassement* (dessous). Sur les véhicules d'occasion importés, vérifie toujours plaque constructeur et VIN (numéro de châssis à 17 caractères) pour identifier le modèle.\n\n⚠️ Avant toute intervention : connaître la motorisation (essence ou diesel) change les pièces et les méthodes.",
                    'questions' => [
                        ['question' => 'Quel ensemble transmet la force du moteur aux roues ?', 'options' => ['Le freinage', 'La transmission', 'La carrosserie', 'L\'éclairage'], 'correct' => [1], 'explanation' => 'La transmission (embrayage, boîte, arbres) achemine la puissance du moteur jusqu\'aux roues.'],
                        ['question' => 'Combien de caractères comporte un numéro de châssis (VIN) standard ?', 'options' => ['10', '13', '17', '21'], 'correct' => [2], 'explanation' => 'Le VIN normalisé comporte 17 caractères et identifie le véhicule de façon unique.'],
                        ['question' => 'Parmi ces éléments, lesquels font partie des liaisons au sol ? (plusieurs réponses)', 'options' => ['La suspension', 'Les pneus', 'L\'alternateur', 'Le coffre'], 'correct' => [0, 1], 'explanation' => 'La suspension et les pneus relient le véhicule à la route et forment les liaisons au sol.'],
                    ],
                ],
                [
                    'title' => 'Niveau 2 — Le moteur 4 temps',
                    'subtitle' => 'Admission, compression, combustion, échappement',
                    'xp_reward' => 110,
                    'content' => "🎯 **Objectif** : comprendre comment un moteur à explosion transforme le carburant en mouvement.\n\n💡 Le cycle 4 temps se répète dans chaque cylindre :\n• **1. Admission** : le piston descend, la soupape d'admission s'ouvre, mélange air + carburant entre.\n• **2. Compression** : le piston remonte, soupapes fermées, le mélange est comprimé.\n• **3. Combustion (explosion)** : la bougie (essence) ou la forte compression (diesel) enflamme le mélange ; le piston est repoussé : c'est le temps moteur.\n• **4. Échappement** : le piston remonte, la soupape d'échappement s'ouvre, les gaz brûlés sortent.\n\n```\nAdmission → Compression → Combustion → Échappement\n  (descend)   (remonte)     (descend)      (remonte)\n```\n\n✅ La rotation des pistons via le vilebrequin crée le couple. Plus de cylindres = fonctionnement plus régulier. Sur le parc camerounais, les 4 cylindres essence et diesel dominent.\n\n⚠️ Essence = allumage par bougie. Diesel = allumage par compression (pas de bougie d'allumage, mais des bougies de préchauffage).",
                    'questions' => [
                        ['question' => 'Dans quel ordre se déroulent les 4 temps ?', 'options' => ['Compression, Admission, Échappement, Combustion', 'Admission, Compression, Combustion, Échappement', 'Combustion, Admission, Compression, Échappement', 'Échappement, Combustion, Admission, Compression'], 'correct' => [1], 'explanation' => 'Le cycle correct est Admission, Compression, Combustion puis Échappement.'],
                        ['question' => 'Comment s\'enflamme le mélange dans un moteur diesel ?', 'options' => ['Par une bougie d\'allumage', 'Par la forte compression de l\'air', 'Par l\'alternateur', 'Par le démarreur'], 'correct' => [1], 'explanation' => 'Le diesel s\'auto-allume grâce à la chaleur produite par une très forte compression de l\'air.'],
                        ['question' => 'Quel temps produit réellement la force motrice ?', 'options' => ['L\'admission', 'La compression', 'La combustion', 'L\'échappement'], 'correct' => [2], 'explanation' => 'C\'est la combustion qui repousse le piston et fournit le travail moteur.'],
                    ],
                ],
                [
                    'title' => 'Niveau 3 — Le système de freinage',
                    'subtitle' => 'Disques, tambours, plaquettes et liquide',
                    'xp_reward' => 120,
                    'content' => "🎯 **Objectif** : comprendre le freinage hydraulique et savoir repérer une usure.\n\n💡 Quand on appuie sur la pédale, le **maître-cylindre** envoie du liquide de frein sous pression vers chaque roue :\n• **Frein à disque** : un étrier serre des **plaquettes** contre un disque. Très répandu à l'avant.\n• **Frein à tambour** : des **mâchoires** poussent contre un tambour. Souvent à l'arrière des véhicules économiques.\n• Le **liquide de frein** est incompressible : c'est lui qui transmet l'effort.\n\n✅ Signes d'usure à connaître :\n• Bruit de grincement métallique → plaquettes usées.\n• Pédale qui s'enfonce trop → présence d'air ou fuite, il faut **purger** le circuit.\n• Vibration au freinage → disque voilé.\n\n⚠️ Sécurité absolue : ne jamais rouler avec un niveau de liquide bas. Le liquide est **hygroscopique** (il absorbe l'humidité) : on le remplace en général tous les 2 ans. Sur les routes en pente de l'Ouest Cameroun, des freins sains sont vitaux.",
                    'questions' => [
                        ['question' => 'Quel composant met le liquide de frein sous pression ?', 'options' => ['L\'alternateur', 'Le maître-cylindre', 'Le radiateur', 'La bougie'], 'correct' => [1], 'explanation' => 'Le maître-cylindre, actionné par la pédale, met le liquide sous pression.'],
                        ['question' => 'Un grincement métallique au freinage indique le plus souvent :', 'options' => ['Un pneu sous-gonflé', 'Des plaquettes usées', 'Une batterie faible', 'Un filtre à air sale'], 'correct' => [1], 'explanation' => 'Le grincement métallique signale en général des plaquettes en fin de vie.'],
                        ['question' => 'Pourquoi remplace-t-on périodiquement le liquide de frein ?', 'options' => ['Parce qu\'il devient inflammable', 'Parce qu\'il absorbe l\'humidité (hygroscopique)', 'Parce qu\'il gèle facilement', 'Parce qu\'il colore les disques'], 'correct' => [1], 'explanation' => 'Étant hygroscopique, le liquide absorbe l\'eau, ce qui abaisse son point d\'ébullition et réduit l\'efficacité du freinage.'],
                    ],
                ],
                [
                    'title' => 'Niveau 4 — Transmission et embrayage',
                    'subtitle' => 'Boîte de vitesses, embrayage, différentiel',
                    'xp_reward' => 130,
                    'content' => "🎯 **Objectif** : comprendre comment la voiture change de vitesse et adapte le couple.\n\n💡 Chaîne de transmission :\n• **L'embrayage** relie ou sépare le moteur de la boîte. Quand on appuie sur la pédale, on désaccouple pour changer de rapport.\n• **La boîte de vitesses** propose plusieurs rapports : démarrage en 1re (beaucoup de force, peu de vitesse), 5e/6e pour rouler vite à bas régime.\n• **Le différentiel** permet aux roues de tourner à des vitesses différentes en virage.\n\n✅ Composants d'un embrayage : disque, mécanisme (plateau de pression) et butée.\n\n⚠️ Symptômes d'embrayage usé :\n• L'embrayage **patine** : le régime monte mais la voiture n'accélère pas.\n• Odeur de brûlé en côte.\n• Pédale dure ou point de patinage très haut.\n\n💡 Boîte automatique : pas de pédale d'embrayage, le passage est géré par un convertisseur de couple ou un embrayage piloté. Sur les taxis de Douala (boîtes manuelles très sollicitées), l'embrayage est une pièce d'usure fréquente.",
                    'questions' => [
                        ['question' => 'À quoi sert l\'embrayage ?', 'options' => ['À freiner les roues', 'À relier ou séparer le moteur et la boîte', 'À recharger la batterie', 'À refroidir le moteur'], 'correct' => [1], 'explanation' => 'L\'embrayage accouple ou désaccouple le moteur et la boîte pour permettre de changer de rapport.'],
                        ['question' => 'Que signifie « l\'embrayage patine » ?', 'options' => ['La voiture freine seule', 'Le régime moteur monte sans que la vitesse augmente', 'La boîte refuse de passer la marche arrière', 'La batterie se décharge'], 'correct' => [1], 'explanation' => 'Un disque usé glisse : le moteur monte en régime mais la puissance n\'est plus transmise aux roues.'],
                        ['question' => 'Quel organe permet aux roues de tourner à des vitesses différentes en virage ?', 'options' => ['Le différentiel', 'Le maître-cylindre', 'L\'alternateur', 'Le carburateur'], 'correct' => [0], 'explanation' => 'Le différentiel répartit le mouvement et autorise des vitesses de rotation différentes entre les roues.'],
                    ],
                ],
                [
                    'title' => 'Niveau 5 — Le circuit électrique automobile',
                    'subtitle' => 'Batterie, alternateur, démarreur, fusibles',
                    'xp_reward' => 140,
                    'content' => "🎯 **Objectif** : comprendre le circuit basse tension 12 V et dépanner les pannes électriques courantes.\n\n💡 Les acteurs clés :\n• **La batterie (12 V)** : stocke l'énergie, surtout utile au démarrage.\n• **Le démarreur** : moteur électrique qui lance le moteur thermique.\n• **L'alternateur** : recharge la batterie et alimente le véhicule une fois le moteur tournant.\n• **Les fusibles et relais** : protègent et commandent les circuits.\n\n```\nBatterie → Démarreur → moteur démarre\nMoteur tournant → Alternateur → recharge Batterie\n```\n\n✅ Mesures de base au multimètre :\n• Batterie au repos : ~12,6 V. Moteur tournant : 13,8–14,4 V (preuve que l'alternateur charge).\n• Moins de 12 V au repos = batterie faible ou déchargée.\n\n⚠️ Sécurité : débrancher d'abord la borne **négative (−)** pour éviter les courts-circuits. Un voyant batterie allumé moteur tournant = problème de charge (souvent courroie ou alternateur). En zone humide (Littoral), surveille la corrosion des cosses.",
                    'questions' => [
                        ['question' => 'Quel composant recharge la batterie quand le moteur tourne ?', 'options' => ['Le démarreur', 'L\'alternateur', 'Le radiateur', 'La bougie'], 'correct' => [1], 'explanation' => 'L\'alternateur produit le courant qui recharge la batterie et alimente le véhicule moteur en marche.'],
                        ['question' => 'Quelle tension lit-on environ sur une batterie 12 V saine au repos ?', 'options' => ['6,3 V', '9,0 V', '12,6 V', '24,0 V'], 'correct' => [2], 'explanation' => 'Une batterie 12 V correctement chargée affiche environ 12,6 V au repos.'],
                        ['question' => 'Quelles précautions de sécurité prendre sur le circuit électrique ? (plusieurs réponses)', 'options' => ['Débrancher d\'abord la borne négative', 'Vérifier l\'état des fusibles avant de remplacer une pièce', 'Court-circuiter les bornes pour tester', 'Mesurer la tension au multimètre'], 'correct' => [0, 1, 3], 'explanation' => 'On débranche le négatif d\'abord, on contrôle fusibles et tensions, mais on ne court-circuite jamais les bornes.'],
                    ],
                ],
                [
                    'title' => 'Niveau 6 — Diagnostic des pannes (OBD)',
                    'subtitle' => 'Lire les codes défaut avec un scanner',
                    'xp_reward' => 150,
                    'content' => "🎯 **Objectif** : utiliser une valise/scanner OBD pour identifier une panne au lieu de remplacer au hasard.\n\n💡 Depuis les années 2000, les véhicules disposent d'une prise **OBD-II** (souvent sous le volant). Un scanner s'y branche et lit les **codes défaut (DTC)** mémorisés par le calculateur.\n\n• Format d'un code : une lettre + 4 chiffres. Exemple **P0301** : *P* = Powertrain (moteur/transmission), *0300* = ratés d'allumage, *01* = cylindre n°1.\n• Familles : **P** moteur, **B** carrosserie, **C** châssis, **U** réseau/communication.\n\n```\nP0301 → P = moteur | 03xx = allumage | 01 = cylindre 1\n```\n\n✅ Méthode de diagnostic :\n1. Lire le ou les codes.\n2. Vérifier les **données en direct** (température, sondes, régime).\n3. Tester physiquement le composant suspect avant de le remplacer.\n4. Effacer le code et faire un essai routier pour confirmer.\n\n⚠️ Un code ne donne pas LA pièce coupable : il indique une zone. P0420 (catalyseur) peut venir d'une sonde lambda, pas forcément du catalyseur. Le diagnostic évite de gaspiller l'argent du client.",
                    'questions' => [
                        ['question' => 'Que lit-on grâce à un scanner branché sur la prise OBD-II ?', 'options' => ['Le niveau de carburant uniquement', 'Les codes défaut mémorisés par le calculateur', 'La pression des pneus seulement', 'Le kilométrage garanti exact'], 'correct' => [1], 'explanation' => 'Le scanner OBD lit les codes défaut (DTC) stockés par le calculateur du véhicule.'],
                        ['question' => 'Dans le code P0301, que désigne la lettre « P » ?', 'options' => ['La carrosserie', 'Le moteur/transmission (Powertrain)', 'Le châssis', 'Le réseau de communication'], 'correct' => [1], 'explanation' => 'La lettre P correspond au groupe motopropulseur (Powertrain).'],
                        ['question' => 'Pourquoi ne faut-il pas remplacer directement la pièce nommée par un code ?', 'options' => ['Parce que les codes sont toujours faux', 'Parce qu\'un code indique une zone, pas forcément la pièce coupable', 'Parce que le scanner efface la pièce', 'Parce que la pièce est toujours en garantie'], 'correct' => [1], 'explanation' => 'Un code pointe une zone de défaut ; il faut confirmer par des tests avant de remplacer, sinon on gaspille.'],
                    ],
                ],
                [
                    'title' => 'Niveau 7 — Entretien courant : vidange et filtres',
                    'subtitle' => 'Huile, filtres et plan d\'entretien',
                    'xp_reward' => 160,
                    'content' => "🎯 **Objectif** : réaliser une vidange propre et connaître les filtres à remplacer.\n\n💡 L'huile moteur lubrifie, refroidit et nettoie. Elle se dégrade et doit être remplacée régulièrement.\n\nÉtapes d'une vidange :\n• Faire chauffer le moteur (huile plus fluide), couper le contact.\n• Dévisser le **bouchon de vidange** sous le carter, laisser couler dans un bac.\n• Remplacer le **filtre à huile**.\n• Remettre le bouchon (avec joint neuf), remplir avec l'huile préconisée, contrôler à la jauge.\n\n✅ Les filtres :\n• **Filtre à huile** : retient les impuretés de l'huile.\n• **Filtre à air** : protège le moteur de la poussière — crucial sur les pistes latéritiques.\n• **Filtre à carburant** : protège l'injection.\n• **Filtre habitacle** : air de la ventilation.\n\n| Élément | Périodicité indicative |\n|---|---|\n| Huile + filtre à huile | 5 000 à 10 000 km |\n| Filtre à air | 15 000 à 20 000 km |\n| Filtre à carburant | selon constructeur |\n\n⚠️ Respecter la **viscosité** (ex : 15W40) et ne jamais jeter l'huile usagée par terre : la recycler. La poussière des routes non bitumées impose de raccourcir les intervalles.",
                    'questions' => [
                        ['question' => 'Quel filtre protège le moteur de la poussière aspirée, point critique sur piste ?', 'options' => ['Le filtre à huile', 'Le filtre à air', 'Le filtre habitacle', 'Le filtre à pollen seul'], 'correct' => [1], 'explanation' => 'Le filtre à air empêche la poussière d\'entrer dans le moteur, essentiel sur les routes latéritiques.'],
                        ['question' => 'Que désigne « 15W40 » sur un bidon d\'huile ?', 'options' => ['Le volume en litres', 'La viscosité de l\'huile', 'La marque du véhicule', 'La pression des pneus'], 'correct' => [1], 'explanation' => 'Le code 15W40 indique la viscosité de l\'huile à froid (W) et à chaud.'],
                        ['question' => 'Que faire de l\'huile de vidange usagée ?', 'options' => ['La verser dans le caniveau', 'La recycler dans un point de collecte', 'La réutiliser telle quelle', 'La brûler à l\'air libre'], 'correct' => [1], 'explanation' => 'L\'huile usagée est polluante : on la collecte et la fait recycler, jamais déversée dans la nature.'],
                    ],
                ],
                [
                    'title' => 'Niveau 8 — Pneumatiques et géométrie',
                    'subtitle' => 'Pression, usure, parallélisme',
                    'xp_reward' => 170,
                    'content' => "🎯 **Objectif** : entretenir les pneus et comprendre la géométrie des trains roulants.\n\n💡 Le pneu est le seul contact avec la route : sécurité maximale.\n• **Pression** : trop basse = surchauffe, surconsommation, éclatement ; trop haute = usure centrale, moins d'adhérence. Contrôler à froid.\n• **Témoin d'usure** : petites bosses dans les rainures ; profondeur légale mini ~1,6 mm.\n• Lecture du flanc, ex **195/65 R15** : 195 = largeur (mm), 65 = hauteur en % de la largeur, R = radial, 15 = diamètre jante en pouces.\n\n✅ Diagnostic par l'usure :\n• Usure sur les **deux bords** → sous-gonflage.\n• Usure au **centre** → surgonflage.\n• Usure d'**un seul côté** → mauvais **parallélisme** (géométrie).\n\n💡 La **géométrie** (parallélisme, carrossage) aligne correctement les roues. Un déréglage tire le volant d'un côté et use vite les pneus — fréquent après un nid-de-poule sur les routes dégradées.\n\n⚠️ Permuter les pneus régulièrement et vérifier la roue de secours. Ne jamais monter des pneus de tailles incompatibles sur un même essieu.",
                    'questions' => [
                        ['question' => 'Une usure sur les deux bords du pneu indique :', 'options' => ['Un surgonflage', 'Un sous-gonflage', 'Un freinage brusque', 'Une jante voilée'], 'correct' => [1], 'explanation' => 'Le sous-gonflage écrase les épaules du pneu et use les deux bords.'],
                        ['question' => 'Dans 195/65 R15, que représente le « 15 » ?', 'options' => ['La largeur en mm', 'Le diamètre de la jante en pouces', 'La hauteur du flanc', 'L\'âge du pneu'], 'correct' => [1], 'explanation' => 'Le dernier nombre indique le diamètre de la jante exprimé en pouces.'],
                        ['question' => 'Un volant qui tire d\'un côté et une usure d\'un seul bord signalent :', 'options' => ['Une batterie faible', 'Un défaut de géométrie (parallélisme)', 'Un filtre à air bouché', 'Un embrayage qui patine'], 'correct' => [1], 'explanation' => 'Une usure latérale et un volant qui tire trahissent un mauvais parallélisme à régler en géométrie.'],
                    ],
                ],
                [
                    'title' => 'Niveau 9 — Sécurité à l\'atelier',
                    'subtitle' => 'Levage, EPI, produits et risques',
                    'xp_reward' => 185,
                    'content' => "🎯 **Objectif** : travailler sans accident, pour soi et pour les autres.\n\n💡 Le levage est le danger n°1 :\n• Toujours utiliser des **chandelles** après avoir levé au cric. **Jamais** sous une voiture tenue uniquement par le cric.\n• Lever aux **points de levage** prévus, sol plat et dur, frein à main serré, cales aux roues.\n\n✅ Équipements de protection individuelle (EPI) :\n• **Gants** (huiles, coupures), **lunettes** (projections, liquide de frein), **chaussures de sécurité**.\n• Protection auditive si bruit important.\n\n⚠️ Risques chimiques et incendie :\n• Carburants et solvants = **inflammables** : pas de flamme ni cigarette près des vapeurs.\n• Gaz d'échappement = **monoxyde de carbone** mortel : ventiler ou utiliser une extraction si le moteur tourne en local fermé.\n• Acide de batterie corrosif : rincer abondamment à l'eau en cas de contact.\n\n💡 Organisation : poste propre, outils rangés, chiffons gras stockés à l'écart, **extincteur** accessible. Un atelier ordonné à Douala inspire confiance et limite les accidents.",
                    'questions' => [
                        ['question' => 'Quel dispositif doit soutenir un véhicule sous lequel on travaille ?', 'options' => ['Le cric seul', 'Des chandelles', 'Des briques empilées', 'La roue de secours'], 'correct' => [1], 'explanation' => 'On place des chandelles : le cric sert à lever, jamais à maintenir le véhicule pendant l\'intervention.'],
                        ['question' => 'Pourquoi ne jamais faire tourner un moteur dans un local fermé sans extraction ?', 'options' => ['À cause du bruit', 'À cause du monoxyde de carbone mortel', 'Parce que ça use l\'embrayage', 'Parce que ça décharge la batterie'], 'correct' => [1], 'explanation' => 'Les gaz d\'échappement contiennent du monoxyde de carbone, inodore et mortel : il faut ventiler ou extraire.'],
                        ['question' => 'Quels EPI sont recommandés à l\'atelier ? (plusieurs réponses)', 'options' => ['Gants de protection', 'Lunettes de sécurité', 'Chaussures de sécurité', 'Sandales ouvertes'], 'correct' => [0, 1, 2], 'explanation' => 'Gants, lunettes et chaussures de sécurité protègent des principaux risques ; les sandales sont à proscrire.'],
                    ],
                ],
                [
                    'title' => 'Niveau 10 — Relation client et professionnalisme',
                    'subtitle' => 'Devis, confiance et fidélisation',
                    'xp_reward' => 200,
                    'content' => "🎯 **Objectif** : transformer une compétence technique en activité durable grâce à une relation client de confiance.\n\n💡 Avant l'intervention :\n• Écouter et **reformuler** le problème (« la voiture cale au ralenti depuis 3 jours »).\n• Faire un **diagnostic** puis un **devis clair** : pièces, main-d'œuvre, délai. Pas de surprise sur la facture.\n• Expliquer en mots simples, sans jargon inutile.\n\n✅ Pendant et après :\n• Tenir les délais ou prévenir en cas de retard.\n• Rendre la voiture propre, conserver les **pièces remplacées** pour les montrer.\n• Donner un conseil d'entretien (prochain entretien, point à surveiller).\n\n💡 Confiance et réputation :\n• La transparence sur les prix attire le bouche-à-oreille, moteur n°1 de clientèle au Cameroun.\n• Garder un cahier ou une appli de suivi des véhicules (historique, kilométrage).\n• Encaissements modernes : mobile money (Orange Money, MTN MoMo) facilite et trace les paiements.\n\n⚠️ Respecter les engagements et la sécurité du client : ne jamais rendre un véhicule dont les freins sont douteux.\n\n🏆 **Félicitations !** Tu maîtrises les bases du métier de mécanicien automobile : anatomie, moteur, freinage, transmission, électricité, diagnostic OBD, entretien, pneus, sécurité et relation client. **Débouchés** : mécanicien d'atelier, spécialiste diagnostic électronique, chef d'atelier, ou entrepreneur avec ton propre garage. Continue à te spécialiser (injection diesel, électronique, hybrides/électriques) : le parc automobile africain a un immense besoin de techniciens qualifiés. En route ! 🔧🚗",
                    'questions' => [
                        ['question' => 'Que doit contenir un bon devis remis au client ?', 'options' => ['Seulement le prix total arrondi', 'Le détail des pièces, de la main-d\'œuvre et le délai', 'Uniquement le nom du mécanicien', 'Rien, l\'oral suffit toujours'], 'correct' => [1], 'explanation' => 'Un devis clair détaille pièces, main-d\'œuvre et délai pour éviter toute mauvaise surprise.'],
                        ['question' => 'Pourquoi conserver et montrer les pièces remplacées au client ?', 'options' => ['Pour les revendre en cachette', 'Pour instaurer la transparence et la confiance', 'Parce que la loi interdit de les jeter', 'Pour augmenter le prix après coup'], 'correct' => [1], 'explanation' => 'Montrer les pièces usagées prouve le travail réalisé et renforce la confiance du client.'],
                        ['question' => 'Quelles pratiques renforcent la fidélisation et la réputation ? (plusieurs réponses)', 'options' => ['Tenir les délais annoncés', 'Donner des conseils d\'entretien', 'Surfacturer les clients pressés', 'Assurer la transparence des prix'], 'correct' => [0, 1, 3], 'explanation' => 'Respect des délais, conseils et transparence fidélisent ; la surfacturation détruit la réputation.'],
                    ],
                ],
            ],
        ]);

        $this->command->info('  ✓ Roadmap Mécanique Automobile créée (10 niveaux).');
    }
}
