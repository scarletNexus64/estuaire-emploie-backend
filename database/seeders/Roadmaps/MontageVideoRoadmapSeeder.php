<?php

namespace Database\Seeders\Roadmaps;

use Illuminate\Database\Seeder;

/**
 * Roadmap Montage Vidéo — du langage des plans à l'export final, un parcours complet pour devenir monteur vidéo.
 */
class MontageVideoRoadmapSeeder extends Seeder
{
    use RoadmapSeederHelper;

    public function run(): void
    {
        $this->createRoadmap([
            'title' => 'Deviens Monteur Vidéo',
            'slug' => 'montage-video',
            'domain' => 'audiovisuel',
            'description' => "Apprends le montage vidéo de A à Z : comprendre le langage des plans, dérusher, construire une timeline, gérer le rythme, étalonner les couleurs, mixer le son, titrer et exporter pour le web. Un parcours concret pensé pour les créateurs de contenu, vidéastes événementiels et futurs professionnels au Cameroun et ailleurs en Afrique francophone.",
            'objectives' => "Lire et nommer les plans et mouvements de caméra\nOrganiser et dérusher des rushes efficacement\nMonter une séquence claire sur une timeline\nMaîtriser raccords, rythme et transitions\nÉtalonner la couleur et mixer le son\nExporter aux bons formats pour chaque plateforme",
            'icon' => '🎬',
            'color' => '#7C3AED',
            'difficulty' => 'beginner',
            'required_packs' => [],
            'pass_threshold' => 70,
            'order' => 20,
            'levels' => [
                [
                    'title' => 'Niveau 1 — Le langage vidéo et les plans',
                    'subtitle' => 'Apprendre à voir et nommer les images',
                    'xp_reward' => 100,
                    'content' => "🎯 **Objectif** : comprendre le vocabulaire de base de l'image avant de toucher au logiciel.\n\n💡 La taille du plan décrit ce que l'on voit du sujet. Du plus large au plus serré :\n• **Plan large (PL)** : situe le décor (une rue de Douala, un stade plein).\n• **Plan moyen** : un personnage de la tête aux genoux.\n• **Plan rapproché** : buste et visage, pour l'émotion.\n• **Gros plan (GP)** : un visage ou un objet, l'attention maximale.\n• **Très gros plan** : un œil, une main qui compte des billets.\n\n✅ Chaque plan a un rôle narratif. On commence souvent large pour **situer**, puis on se rapproche pour **raconter l'émotion**. C'est la règle dite du « plan d'établissement ».\n\n⚠️ Ne tournez jamais une seule taille de plan : sans variété, le montage devient plat et impossible à rythmer.\n\nMémorisez ces noms : ce sera votre langage avec le caméraman et le réalisateur sur chaque tournage.",
                    'questions' => [
                        ['question' => 'Quel plan utilise-t-on pour situer le décor en début de scène ?', 'options' => ['Le gros plan', 'Le plan large (ou plan d\'établissement)', 'Le très gros plan', 'Le plan rapproché'], 'correct' => [1], 'explanation' => 'Le plan large situe le lieu et le contexte avant de se rapprocher des personnages.'],
                        ['question' => 'Le gros plan sert surtout à montrer :', 'options' => ['L\'ensemble du décor', 'La foule entière', 'Un visage ou un objet pour l\'émotion ou le détail', 'La position géographique'], 'correct' => [2], 'explanation' => 'Le gros plan concentre l\'attention sur le visage ou un objet, support de l\'émotion.'],
                        ['question' => 'Pourquoi varier les tailles de plan lors du tournage ?', 'options' => ['Pour remplir la carte mémoire', 'Pour offrir de la matière et rythmer le montage', 'Parce que c\'est obligatoire légalement', 'Pour économiser la batterie'], 'correct' => [1], 'explanation' => 'La variété des plans donne au monteur du matériel pour créer du rythme et de la narration.'],
                    ],
                ],
                [
                    'title' => 'Niveau 2 — Mouvements de caméra et angles',
                    'subtitle' => 'Donner du sens par le cadrage',
                    'xp_reward' => 110,
                    'content' => "🎯 **Objectif** : reconnaître les mouvements et angles, car ils influencent le montage.\n\n💡 Les mouvements principaux :\n• **Panoramique** : la caméra pivote sur place (gauche-droite ou haut-bas).\n• **Travelling** : la caméra se déplace dans l'espace (sur rail, slider, ou à la main).\n• **Zoom** : on rapproche optiquement l'image sans bouger la caméra.\n• **Plan fixe** : aucun mouvement, idéal pour les interviews.\n\nLes angles :\n• **Plongée** (caméra au-dessus) : écrase, rend le sujet vulnérable.\n• **Contre-plongée** (caméra en dessous) : grandit, donne de la puissance.\n• **À hauteur d'yeux** : neutre, naturel.\n\n✅ Pour une interview au bureau à Yaoundé, privilégiez un plan fixe à hauteur d'yeux : c'est stable et facile à monter.\n\n⚠️ Un travelling tremblé ou un zoom brusque sont durs à raccorder. Repérez ces plans au dérushage pour les utiliser avec soin, ou les écarter.",
                    'questions' => [
                        ['question' => 'Quelle est la différence entre un panoramique et un travelling ?', 'options' => ['Aucune, ce sont des synonymes', 'Le panoramique pivote sur place, le travelling déplace la caméra dans l\'espace', 'Le travelling pivote, le panoramique se déplace', 'Les deux sont des zooms'], 'correct' => [1], 'explanation' => 'Le panoramique est une rotation sur place ; le travelling est un déplacement physique de la caméra.'],
                        ['question' => 'Une contre-plongée (caméra en dessous du sujet) tend à :', 'options' => ['Rendre le sujet vulnérable', 'Grandir et donner de la puissance au sujet', 'Rendre l\'image neutre', 'Flouter l\'arrière-plan'], 'correct' => [1], 'explanation' => 'La contre-plongée valorise et donne une impression de force ou de domination.'],
                        ['question' => 'Pour une interview stable et facile à monter, on choisit :', 'options' => ['Un zoom permanent', 'Un travelling à la main', 'Un plan fixe à hauteur d\'yeux', 'Une plongée extrême'], 'correct' => [2], 'explanation' => 'Le plan fixe à hauteur d\'yeux est neutre, stable et idéal pour une interview.'],
                    ],
                ],
                [
                    'title' => 'Niveau 3 — Dérushage et organisation des médias',
                    'subtitle' => 'Trier avant de monter',
                    'xp_reward' => 120,
                    'content' => "🎯 **Objectif** : trier ses rushes pour ne jamais perdre de temps ni de fichier.\n\n💡 Le **dérushage** consiste à visionner tous les rushes, noter les bonnes prises et écarter les ratées. C'est l'étape la plus négligée par les débutants… et la plus rentable.\n\nUne arborescence de projet propre :\n```\nMON_PROJET/\n  01_RUSHES/      (vidéos brutes)\n  02_AUDIO/       (sons, musiques)\n  03_IMAGES/      (logos, photos)\n  04_PROJET/      (fichier du logiciel)\n  05_EXPORTS/     (rendus finaux)\n```\n\n✅ Bonnes pratiques :\n• Copiez TOUJOURS les rushes de la carte SD sur le disque avant de monter (et gardez une sauvegarde).\n• Renommez les fichiers utiles (INTERVIEW_01, PLAN_RUE_DOUALA).\n• Marquez vos prises favorites avec un repère couleur dans le logiciel.\n\n⚠️ Ne montez jamais directement depuis la carte SD : si elle se déconnecte, votre montage se brise. Copiez d'abord, vérifiez, puis formatez la carte.",
                    'questions' => [
                        ['question' => 'En quoi consiste le dérushage ?', 'options' => ['Exporter la vidéo finale', 'Visionner les rushes pour trier les bonnes prises des ratées', 'Ajouter de la musique', 'Étalonner les couleurs'], 'correct' => [1], 'explanation' => 'Le dérushage est le tri et le visionnage des rushes pour sélectionner les meilleures prises.'],
                        ['question' => 'Pourquoi ne faut-il pas monter directement depuis la carte SD ?', 'options' => ['C\'est plus rapide', 'Si la carte se déconnecte, le montage se brise et on risque de perdre les fichiers', 'La qualité baisse', 'Le logiciel l\'interdit'], 'correct' => [1], 'explanation' => 'Monter depuis la carte expose à la perte de fichiers ; il faut copier sur disque avant de monter.'],
                        ['question' => 'Quelles sont de bonnes pratiques de dérushage ? (plusieurs réponses)', 'options' => ['Renommer clairement les fichiers utiles', 'Marquer les prises favorites avec un repère couleur', 'Formater la carte avant d\'avoir copié les rushes', 'Garder une sauvegarde des rushes'], 'correct' => [0, 1, 3], 'explanation' => 'Renommer, marquer et sauvegarder sont de bonnes pratiques ; formater avant la copie ferait perdre les rushes.'],
                    ],
                ],
                [
                    'title' => 'Niveau 4 — La timeline et le cut',
                    'subtitle' => 'Construire sa première séquence',
                    'xp_reward' => 130,
                    'content' => "🎯 **Objectif** : maîtriser la timeline, l'espace où l'on assemble les plans.\n\n💡 La **timeline** (ligne de temps) lit les plans de gauche à droite. Elle se compose de **pistes** empilées :\n• Pistes **vidéo** (V1, V2, V3) : ce qui est en haut recouvre ce qui est en dessous.\n• Pistes **audio** (A1, A2, A3) : tous les sons se mélangent.\n\nLe **cut** est la coupe la plus simple : on passe d'un plan à l'autre sans transition. C'est la base : 90 % d'un montage est fait de cuts nets.\n\nOutils essentiels :\n• **Lame / Ciseaux** : couper un clip en deux.\n• **Sélection** : déplacer, allonger ou raccourcir un clip.\n• **Points d'entrée (I) et de sortie (O)** : choisir le morceau utile d'un rush avant de l'insérer.\n\n✅ Méthode efficace : posez d'abord un **ours** (rough cut), un assemblage rapide bout à bout, sans soigner. On affine ensuite.\n\n⚠️ Travaillez en marquant I et O dans le moniteur source : vous n'insérez que le bon morceau, sans encombrer la timeline.",
                    'questions' => [
                        ['question' => 'Sur les pistes vidéo d\'une timeline, que se passe-t-il quand deux clips se superposent ?', 'options' => ['Ils se mélangent en transparence par défaut', 'Le clip de la piste supérieure recouvre celui du dessous', 'Le clip du dessous gagne toujours', 'Le logiciel renvoie une erreur'], 'correct' => [1], 'explanation' => 'En vidéo, la piste supérieure (V2, V3...) recouvre les pistes inférieures.'],
                        ['question' => 'Qu\'est-ce qu\'un cut ?', 'options' => ['Un fondu progressif', 'Une coupe nette d\'un plan à l\'autre sans transition', 'Un effet de couleur', 'Un export'], 'correct' => [1], 'explanation' => 'Le cut est le passage net d\'un plan à un autre, la transition de base du montage.'],
                        ['question' => 'À quoi servent les points d\'entrée (I) et de sortie (O) ?', 'options' => ['À régler le volume', 'À sélectionner le morceau utile d\'un rush avant de l\'insérer', 'À exporter la vidéo', 'À changer le format'], 'correct' => [1], 'explanation' => 'Les points I et O délimitent la portion du rush que l\'on souhaite poser sur la timeline.'],
                    ],
                ],
                [
                    'title' => 'Niveau 5 — Raccords et continuité',
                    'subtitle' => 'Des coupes invisibles',
                    'xp_reward' => 140,
                    'content' => "🎯 **Objectif** : raccorder les plans pour que les coupes passent inaperçues.\n\n💡 Le **raccord** est l'art de relier deux plans sans casser l'illusion de continuité. Quelques règles :\n• **Raccord dans le mouvement** : couper pendant qu'un geste est en cours (une main qui se lève) ; l'œil suit le mouvement et oublie la coupe.\n• **Règle des 30°** : entre deux plans du même sujet, changez l'angle d'au moins 30°, sinon on obtient un « saut » désagréable (jump cut).\n• **Raccord regard** : si un personnage regarde hors champ, le plan suivant montre ce qu'il regarde.\n• **Cohérence** : mêmes vêtements, même lumière, même position des objets d'un plan à l'autre.\n\n✅ Pour dialoguer entre deux personnes, alternez les **champs / contre-champs** : on voit l'un, puis l'autre, en gardant la règle des 180° (ne pas franchir la ligne imaginaire entre les deux).\n\n⚠️ Un **faux raccord** (un verre plein puis vide, une montre qui change de bras) brise l'immersion. Repérez-les au dérushage.",
                    'questions' => [
                        ['question' => 'Qu\'impose la règle des 30° ?', 'options' => ['Tourner à 30 images par seconde', 'Changer l\'angle d\'au moins 30° entre deux plans du même sujet pour éviter un jump cut', 'Incliner la caméra de 30°', 'Couper toutes les 30 secondes'], 'correct' => [1], 'explanation' => 'Sans un changement d\'angle suffisant, la coupe produit un saut visuel désagréable (jump cut).'],
                        ['question' => 'Le raccord dans le mouvement consiste à :', 'options' => ['Couper pendant un geste pour rendre la coupe invisible', 'Ajouter un fondu', 'Accélérer la vidéo', 'Couper toujours sur un plan fixe'], 'correct' => [0], 'explanation' => 'Couper au cœur d\'un mouvement masque la coupe car l\'œil suit le geste.'],
                        ['question' => 'Lesquels sont des problèmes de continuité (faux raccords) ? (plusieurs réponses)', 'options' => ['Un verre plein dans un plan, vide dans le suivant', 'Une variété de tailles de plan', 'Une montre qui change de poignet entre deux plans', 'Des cuts nets et rythmés'], 'correct' => [0, 2], 'explanation' => 'Les incohérences d\'objets entre plans (verre, montre) sont des faux raccords qui brisent l\'immersion.'],
                    ],
                ],
                [
                    'title' => 'Niveau 6 — Rythme et transitions',
                    'subtitle' => 'Donner du souffle au montage',
                    'xp_reward' => 150,
                    'content' => "🎯 **Objectif** : maîtriser le rythme et utiliser les transitions à bon escient.\n\n💡 Le **rythme** naît de la durée des plans. Plans courts = énergie, tension (clip, pub). Plans longs = calme, contemplation (documentaire). Variez la durée pour ne pas endormir le spectateur.\n\nLes **transitions** principales :\n• **Cut** : 90 % du temps, c'est le meilleur choix.\n• **Fondu enchaîné (dissolve)** : un plan se dissout dans l'autre, suggère le passage du temps.\n• **Fondu au noir** : marque une fin de chapitre ou de scène.\n• **Volet / wipe** : un plan en chasse un autre, style dynamique mais à doser.\n\n✅ Astuce de rythme : montez sur la musique. Posez des **marqueurs** sur les temps forts (le beat) et coupez vos plans dessus. L'effet est immédiatement plus pro.\n\n⚠️ Évitez les transitions « gadget » (étoiles, tourbillons, zooms 3D) : elles datent la vidéo et fatiguent. Un montage propre repose surtout sur des cuts bien placés.",
                    'questions' => [
                        ['question' => 'Comment crée-t-on principalement le rythme d\'un montage ?', 'options' => ['Par la couleur des plans', 'Par la durée des plans (courts = énergie, longs = calme)', 'Par le format d\'export', 'Par le nombre de pistes audio'], 'correct' => [1], 'explanation' => 'La durée des plans est le principal levier du rythme : courts pour l\'énergie, longs pour le calme.'],
                        ['question' => 'Que suggère généralement un fondu enchaîné (dissolve) ?', 'options' => ['Une action très rapide', 'Le passage du temps ou un lien doux entre deux plans', 'Une erreur de montage', 'Un changement de format'], 'correct' => [1], 'explanation' => 'Le fondu enchaîné adoucit la coupe et évoque souvent une ellipse temporelle.'],
                        ['question' => 'Quelle astuce rend un montage musical plus professionnel ?', 'options' => ['Ajouter beaucoup de transitions gadget', 'Poser des marqueurs sur les temps forts et couper sur le beat', 'Allonger tous les plans', 'Désactiver le son'], 'correct' => [1], 'explanation' => 'Couper sur les temps forts de la musique synchronise image et son et donne un rendu pro.'],
                    ],
                ],
                [
                    'title' => 'Niveau 7 — Étalonnage couleur',
                    'subtitle' => 'Corriger puis sublimer l\'image',
                    'xp_reward' => 160,
                    'content' => "🎯 **Objectif** : comprendre l'étalonnage en deux étapes : correction puis stylisation.\n\n💡 On distingue :\n• **Correction colorimétrique** : rendre l'image « juste » (bonne exposition, blancs neutres, contraste équilibré). On harmonise tous les plans entre eux.\n• **Étalonnage créatif (color grading)** : donner une ambiance (chaude pour un coucher de soleil à Kribi, froide pour une scène tendue).\n\nOutils clés :\n• **Roues chromatiques** : agir sur les ombres, les tons moyens, les hautes lumières.\n• **Balance des blancs** : corriger une dominante (orange en lumière artificielle, bleue à l'ombre).\n• **Saturation** : intensité des couleurs ; à doser, sinon les peaux deviennent rouges.\n\n✅ Utilisez les **scopes** (waveform, vectorscope) plutôt que de vous fier à l'écran : un écran mal réglé trompe l'œil. La waveform vérifie l'exposition objectivement.\n\n⚠️ Filmez en profil **plat / Log** si votre caméra le permet : vous gardez plus de latitude pour étalonner. Sinon, restez doux dans les réglages pour ne pas « casser » l'image.",
                    'questions' => [
                        ['question' => 'Quelle est la différence entre correction et étalonnage créatif ?', 'options' => ['Aucune', 'La correction rend l\'image juste ; l\'étalonnage créatif lui donne une ambiance', 'La correction ajoute des transitions', 'L\'étalonnage créatif corrige le son'], 'correct' => [1], 'explanation' => 'On corrige d\'abord pour une image neutre et homogène, puis on stylise pour créer une ambiance.'],
                        ['question' => 'Pourquoi se fier aux scopes (waveform, vectorscope) plutôt qu\'à l\'écran seul ?', 'options' => ['Les scopes sont décoratifs', 'Un écran mal calibré trompe l\'œil ; les scopes donnent une mesure objective', 'Les scopes accélèrent l\'export', 'Ils ajoutent de la saturation'], 'correct' => [1], 'explanation' => 'Les scopes mesurent objectivement l\'exposition et les couleurs, là où un écran peut induire en erreur.'],
                        ['question' => 'À quoi sert la balance des blancs ?', 'options' => ['À corriger une dominante de couleur (orange ou bleue)', 'À couper les plans', 'À régler le volume', 'À choisir le format de fichier'], 'correct' => [0], 'explanation' => 'La balance des blancs neutralise les dominantes colorées dues à la lumière de la scène.'],
                    ],
                ],
                [
                    'title' => 'Niveau 8 — Son et mixage',
                    'subtitle' => 'Le son fait la moitié du film',
                    'xp_reward' => 170,
                    'content' => "🎯 **Objectif** : équilibrer les sons pour un rendu clair et agréable.\n\n💡 Les trois couches sonores d'une vidéo :\n• **Voix / dialogues** : la priorité absolue, toujours intelligibles.\n• **Ambiances et bruitages** : donnent vie à la scène (marché, circulation, oiseaux).\n• **Musique** : soutient l'émotion, sans écraser la voix.\n\nLe **mixage** consiste à régler les niveaux. Repères en décibels :\n• Voix : autour de **-12 à -6 dB**.\n• Musique sous une voix : **-18 à -24 dB** (on la baisse fortement).\n• Ne dépassez jamais **0 dB** : au-delà, le son sature (grésille).\n\n✅ Le **ducking** baisse automatiquement la musique quand quelqu'un parle, puis la remonte. Très utile en interview ou en vlog.\n\n⚠️ Nettoyez d'abord : coupez les bruits de fond (souffle, climatiseur) avec un réducteur de bruit, AVANT de mixer. Un bon son sauve une image moyenne, mais un mauvais son ruine une belle image.",
                    'questions' => [
                        ['question' => 'Dans une vidéo, quelle couche sonore doit rester prioritaire et intelligible ?', 'options' => ['La musique', 'Les voix / dialogues', 'Les bruitages', 'L\'ambiance de fond'], 'correct' => [1], 'explanation' => 'La voix porte l\'information ; elle doit toujours rester claire et au premier plan.'],
                        ['question' => 'Que se passe-t-il si le son dépasse 0 dB ?', 'options' => ['Il devient plus net', 'Il sature et grésille', 'Il s\'éteint', 'La vidéo accélère'], 'correct' => [1], 'explanation' => 'Au-delà de 0 dB, le signal sature (écrêtage) et produit une distorsion désagréable.'],
                        ['question' => 'À quoi sert le ducking ?', 'options' => ['À ajouter des sous-titres', 'À baisser automatiquement la musique quand quelqu\'un parle', 'À augmenter la saturation', 'À couper la vidéo'], 'correct' => [1], 'explanation' => 'Le ducking abaisse la musique pendant la parole pour garder la voix intelligible.'],
                    ],
                ],
                [
                    'title' => 'Niveau 9 — Titrage et motion simple',
                    'subtitle' => 'Habiller la vidéo',
                    'xp_reward' => 185,
                    'content' => "🎯 **Objectif** : ajouter textes, sous-titres et animations simples qui restent lisibles.\n\n💡 Les habillages courants :\n• **Titre / lower third** : un bandeau en bas pour présenter une personne (nom, fonction).\n• **Sous-titres** : indispensables, car beaucoup regardent **sans le son** (réseaux sociaux).\n• **Légendes et call-to-action** : « Abonne-toi », flèches, chiffres.\n\nRègles de lisibilité :\n• Police simple et grande ; évitez les fioritures.\n• Contraste fort : texte clair sur fond sombre, ou ajoutez une ombre / un fond semi-transparent.\n• Laissez le texte assez longtemps à l'écran pour être lu (lisez-le à voix haute deux fois).\n\nLe **motion simple** :\n• **Keyframes (images clés)** : on définit une valeur au temps A et une autre au temps B ; le logiciel anime l'entre-deux.\n• Exemple : faire entrer un titre en fondu + léger glissement.\n\n✅ Respectez les **marges de sécurité** (title safe) : ne collez pas le texte aux bords, il pourrait être coupé selon l'écran.\n\n⚠️ Trop d'animations distraient. Sobre = professionnel.",
                    'questions' => [
                        ['question' => 'Qu\'est-ce qu\'un lower third ?', 'options' => ['Un export basse qualité', 'Un bandeau de titre en bas de l\'écran présentant une personne', 'Une transition', 'Un réglage de couleur'], 'correct' => [1], 'explanation' => 'Le lower third est l\'habillage texte placé dans le tiers inférieur pour identifier un intervenant.'],
                        ['question' => 'Comment crée-t-on une animation simple comme un titre qui entre en fondu ?', 'options' => ['Avec des keyframes (images clés) définissant des valeurs à des instants différents', 'En exportant deux fois', 'En changeant le format', 'En augmentant le volume'], 'correct' => [0], 'explanation' => 'Les keyframes définissent des valeurs à des temps donnés et le logiciel anime entre elles.'],
                        ['question' => 'Quelles pratiques améliorent la lisibilité d\'un texte à l\'écran ? (plusieurs réponses)', 'options' => ['Un contraste fort entre texte et fond', 'Laisser le texte assez longtemps pour être lu', 'Coller le texte tout au bord de l\'image', 'Respecter les marges de sécurité (title safe)'], 'correct' => [0, 1, 3], 'explanation' => 'Contraste, durée suffisante et marges de sécurité aident la lecture ; coller au bord risque la coupe.'],
                    ],
                ],
                [
                    'title' => 'Niveau 10 — Export, formats et workflow',
                    'subtitle' => 'Livrer un fichier propre',
                    'xp_reward' => 200,
                    'content' => "🎯 **Objectif** : exporter au bon format et structurer un workflow professionnel.\n\n💡 Réglages d'export essentiels :\n• **Conteneur** : le plus universel est le **.mp4**.\n• **Codec** : **H.264** (compatible partout) ou **H.265/HEVC** (plus léger, moins universel).\n• **Résolution** : 1920×1080 (Full HD) ou 3840×2160 (4K).\n• **Images par seconde (fps)** : gardez celle du tournage (24, 25 ou 30).\n• **Débit (bitrate)** : plus il est haut, plus la qualité et le poids montent.\n\nFormats selon la plateforme :\n| Usage | Format | Ratio |\n|---|---|---|\n| YouTube | 1080p/4K mp4 | 16:9 |\n| Instagram/TikTok | 1080×1920 | 9:16 (vertical) |\n| WhatsApp | 720p mp4 léger | variable |\n\n✅ Workflow type : **copier les rushes → dérusher → ours → montage fin → étalonnage → mixage son → titrage → export → sauvegarde du projet**.\n\n⚠️ Exportez toujours une version de contrôle et regardez-la en entier AVANT de livrer au client (Douala, Yaoundé ou ailleurs) : on repère souvent une faute de frappe ou un son oublié.\n\n🏆 **Félicitations !** Tu maîtrises désormais la chaîne complète du montage vidéo. Débouchés : monteur freelance, vidéaste événementiel (mariages, conférences), créateur de contenu réseaux sociaux, motion designer, monteur en agence de communication ou en télévision. Continue à pratiquer : un démo-reel solide et un workflow rigoureux feront toute la différence pour décrocher tes premiers contrats.",
                    'questions' => [
                        ['question' => 'Quel couple conteneur/codec est le plus universellement compatible ?', 'options' => ['.mp4 avec H.264', '.avi avec un codec rare', '.mov en ProRes uniquement', '.wmv ancien'], 'correct' => [0], 'explanation' => 'Le .mp4 encodé en H.264 est lisible sur presque tous les appareils et plateformes.'],
                        ['question' => 'Quel format choisir pour une vidéo destinée à TikTok ou aux stories Instagram ?', 'options' => ['16:9 horizontal 1920×1080', '9:16 vertical 1080×1920', '1:1 carré uniquement', '4:3 ancien'], 'correct' => [1], 'explanation' => 'Les contenus mobiles (TikTok, stories) utilisent le format vertical 9:16, soit 1080×1920.'],
                        ['question' => 'Quelles étapes font partie d\'un bon workflow vidéo ? (plusieurs réponses)', 'options' => ['Copier puis dérusher les rushes avant de monter', 'Visionner l\'export en entier avant de livrer', 'Sauvegarder le fichier projet', 'Monter directement depuis la carte SD sans copie'], 'correct' => [0, 1, 2], 'explanation' => 'Copier/dérusher, contrôler l\'export et sauvegarder sont essentiels ; monter depuis la carte est risqué.'],
                    ],
                ],
            ],
        ]);

        $this->command->info('  ✓ Roadmap Montage Vidéo créée (10 niveaux).');
    }
}
