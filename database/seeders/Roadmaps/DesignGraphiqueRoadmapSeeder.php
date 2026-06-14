<?php

namespace Database\Seeders\Roadmaps;

use Illuminate\Database\Seeder;

/**
 * Roadmap Design Graphique — du rôle du graphiste à la livraison d'un projet professionnel.
 */
class DesignGraphiqueRoadmapSeeder extends Seeder
{
    use RoadmapSeederHelper;

    public function run(): void
    {
        $this->createRoadmap([
            'title' => 'Deviens Graphiste / Designer Graphique',
            'slug' => 'design-graphique',
            'domain' => 'design',
            'description' => "Apprends le métier de graphiste depuis zéro : couleurs, typographie, composition, identité visuelle, outils et préparation à l'impression. Une formation concrète, adaptée au marché africain (Douala, Yaoundé), pour créer des visuels professionnels et répondre à des clients réels.",
            'objectives' => "Comprendre le rôle et la valeur du graphiste\nMaîtriser la théorie des couleurs et la typographie\nConstruire des compositions équilibrées avec grilles et hiérarchie\nConcevoir un logo et une identité de marque\nDistinguer RVB, CMJN, résolution et formats de fichiers\nUtiliser Photoshop et Illustrator efficacement\nPréparer un fichier propre pour l'impression\nLire et traiter un brief client de A à Z",
            'icon' => '🎨',
            'color' => '#7C3AED',
            'difficulty' => 'beginner',
            'required_packs' => [],
            'pass_threshold' => 70,
            'order' => 20,
            'levels' => [
                [
                    'title' => 'Niveau 1 — Le rôle du graphiste',
                    'subtitle' => 'Comprendre le métier et sa valeur',
                    'xp_reward' => 100,
                    'content' => "🎯 **Objectif** : comprendre ce que fait vraiment un graphiste et pourquoi son travail a de la valeur.\n\n💡 Le graphiste **résout des problèmes de communication** par le visuel. Il ne fait pas que « rendre joli » : il aide une entreprise à se faire comprendre, reconnaître et choisir. À Douala comme à Yaoundé, un bon visuel peut faire vendre un produit ou rendre crédible une PME.\n\n✅ Domaines courants du graphiste :\n• **Identité visuelle** : logo, charte graphique\n• **Print** : flyers, affiches, cartes de visite, banderoles\n• **Digital** : posts réseaux sociaux, bannières web\n• **Édition** : magazines, brochures, rapports\n\n⚠️ Un graphiste n'est pas un imprimeur ni un développeur web : il conçoit, puis collabore avec eux.\n\nLe processus type :\n1. Comprendre le besoin (brief)\n2. Rechercher et s'inspirer\n3. Concevoir des propositions\n4. Réviser avec le client\n5. Livrer les fichiers finaux\n\nLa **forme suit la fonction** : chaque choix esthétique doit servir un message.",
                    'questions' => [
                        ['question' => 'Quelle est la mission première d\'un graphiste ?', 'options' => ['Rendre les choses jolies sans but', 'Résoudre des problèmes de communication par le visuel', 'Imprimer les documents', 'Écrire les textes publicitaires'], 'correct' => [1], 'explanation' => 'Le graphiste conçoit des visuels pour faire passer un message clair, pas seulement pour décorer.'],
                        ['question' => 'Parmi ces tâches, lesquelles relèvent du graphiste ?', 'options' => ['Concevoir un logo', 'Réparer une imprimante', 'Créer une affiche', 'Coder un site complet de A à Z'], 'correct' => [0, 2], 'explanation' => 'Le logo et l\'affiche sont des livrables de conception graphique ; la réparation et le développement sont d\'autres métiers.'],
                        ['question' => 'Que signifie « la forme suit la fonction » ?', 'options' => ['Le design doit toujours être minimaliste', 'Chaque choix visuel doit servir le message', 'On dessine avant de réfléchir', 'La couleur compte plus que le sens'], 'correct' => [1], 'explanation' => 'Les choix esthétiques sont justifiés par l\'objectif de communication, pas par le hasard.'],
                    ],
                ],
                [
                    'title' => 'Niveau 2 — La théorie des couleurs',
                    'subtitle' => 'Roue chromatique, harmonies et symbolique',
                    'xp_reward' => 110,
                    'content' => "🎯 **Objectif** : choisir des couleurs qui fonctionnent ensemble et transmettent la bonne émotion.\n\n💡 La **roue chromatique** organise les couleurs :\n• **Primaires** : rouge, bleu, jaune\n• **Secondaires** : vert, orange, violet (mélange de deux primaires)\n• **Tertiaires** : mélanges intermédiaires\n\n✅ Harmonies de couleurs utiles :\n• **Complémentaire** : couleurs opposées (bleu/orange) → fort contraste\n• **Analogue** : couleurs voisines (jaune/orange/rouge) → douceur\n• **Triadique** : trois couleurs équidistantes → équilibre vif\n• **Monochrome** : nuances d'une seule teinte → élégance\n\nChaque couleur a trois propriétés : **teinte** (la couleur), **saturation** (intensité), **luminosité** (clair/sombre).\n\n⚠️ La couleur a une **symbolique** : le rouge attire et alerte, le vert évoque la nature et la santé, le bleu inspire confiance (banques, télécoms). Attention aux contextes culturels locaux.\n\nRègle pratique **60-30-10** : 60 % couleur dominante, 30 % secondaire, 10 % accent.",
                    'questions' => [
                        ['question' => 'Quelles sont les couleurs primaires en synthèse traditionnelle ?', 'options' => ['Rouge, vert, bleu', 'Rouge, bleu, jaune', 'Orange, violet, vert', 'Noir, blanc, gris'], 'correct' => [1], 'explanation' => 'Les primaires classiques sont rouge, bleu et jaune ; les autres en découlent.'],
                        ['question' => 'Une harmonie complémentaire utilise des couleurs...', 'options' => ['Voisines sur la roue', 'Opposées sur la roue', 'Toutes identiques', 'Uniquement chaudes'], 'correct' => [1], 'explanation' => 'Les complémentaires sont opposées sur la roue chromatique et créent un fort contraste.'],
                        ['question' => 'Que décrit la règle 60-30-10 ?', 'options' => ['Le temps de travail', 'La répartition dominante/secondaire/accent des couleurs', 'La résolution d\'image', 'Le prix d\'un projet'], 'correct' => [1], 'explanation' => 'Elle équilibre une palette : 60 % dominante, 30 % secondaire, 10 % accent.'],
                    ],
                ],
                [
                    'title' => 'Niveau 3 — La typographie',
                    'subtitle' => 'Familles, lisibilité et associations',
                    'xp_reward' => 120,
                    'content' => "🎯 **Objectif** : choisir et marier les polices pour un texte clair et agréable.\n\n💡 Deux grandes familles :\n• **Serif** (avec empattements, ex. Times) → classique, sérieux, bon pour le texte imprimé long\n• **Sans-serif** (sans empattements, ex. Arial, Roboto) → moderne, propre, idéal pour l'écran\nAutres : **script** (manuscrite), **display** (titres décoratifs).\n\n✅ Vocabulaire essentiel :\n• **Interlignage** : espace entre les lignes\n• **Crénage / approche** : espace entre les lettres\n• **Graisse** : épaisseur (light, regular, bold)\n• **Casse** : MAJUSCULES / minuscules\n\n⚠️ Règles d'or :\n• Maximum **2 à 3 polices** par projet\n• Associer une serif et une sans-serif crée un contraste élégant\n• Le texte de lecture doit rester lisible (corps 9-12 pt en print)\n• Éviter les polices fantaisistes pour les longs paragraphes\n\nLa typographie représente jusqu'à 90 % d'un design : c'est souvent le texte qui porte le message. Une bonne **hiérarchie typographique** guide l'œil du titre vers les détails.",
                    'questions' => [
                        ['question' => 'Quelle famille de police porte des empattements ?', 'options' => ['Sans-serif', 'Serif', 'Script', 'Monospace'], 'correct' => [1], 'explanation' => 'Les polices serif ont des empattements (petits pieds) au bout des lettres.'],
                        ['question' => 'Combien de polices au maximum par projet, en règle générale ?', 'options' => ['1 seule obligatoire', '2 à 3', '5 à 6', 'Autant que possible'], 'correct' => [1], 'explanation' => 'On limite à 2-3 polices pour garder cohérence et lisibilité.'],
                        ['question' => 'Que désigne l\'interlignage ?', 'options' => ['L\'espace entre les lettres', 'L\'espace entre les lignes', 'La taille du titre', 'La couleur du texte'], 'correct' => [1], 'explanation' => 'L\'interlignage est l\'espace vertical entre deux lignes de texte.'],
                    ],
                ],
                [
                    'title' => 'Niveau 4 — Composition et grilles',
                    'subtitle' => 'Aligner, équilibrer et structurer la page',
                    'xp_reward' => 130,
                    'content' => "🎯 **Objectif** : organiser les éléments pour une mise en page claire et harmonieuse.\n\n💡 La **grille** est une structure invisible de colonnes et de marges qui guide le placement. Elle apporte cohérence et professionnalisme. Un magazine sur 12 colonnes, un flyer A5 sur 3 colonnes, etc.\n\n✅ Principes de composition :\n• **Alignement** : aligner les éléments crée de l'ordre\n• **Équilibre** : symétrique (formel) ou asymétrique (dynamique)\n• **Espace négatif** (blanc) : le vide respire et met en valeur\n• **Proximité** : regrouper ce qui va ensemble\n• **Répétition** : répéter styles et marges pour l'unité\n\n⚠️ La **règle des tiers** : divise l'espace en 3×3. Place les éléments importants sur les lignes ou intersections pour un cadrage naturel.\n\nSchéma mental d'une affiche :\n```\n+---------------------+\n|     TITRE fort      |\n|                     |\n|   [ Visuel clé ]    |\n|                     |\n|  Infos | Logo | CTA |\n+---------------------+\n```\nLe **point focal** doit attirer l'œil en premier, puis guider vers l'appel à l'action.",
                    'questions' => [
                        ['question' => 'À quoi sert une grille en mise en page ?', 'options' => ['À colorier le fond', 'À structurer le placement des éléments', 'À choisir la police', 'À imprimer le fichier'], 'correct' => [1], 'explanation' => 'La grille organise colonnes et marges pour un placement cohérent.'],
                        ['question' => 'Que représente l\'espace négatif ?', 'options' => ['Une erreur de design', 'Le vide qui fait respirer la composition', 'Une couleur sombre', 'Un texte caché'], 'correct' => [1], 'explanation' => 'L\'espace négatif (le blanc) aère la page et met en valeur les éléments.'],
                        ['question' => 'La règle des tiers consiste à...', 'options' => ['Diviser l\'espace en 3×3 et placer les éléments clés sur les lignes', 'Utiliser trois couleurs', 'Mettre trois polices', 'Couper l\'image en deux'], 'correct' => [0], 'explanation' => 'On divise l\'espace en neuf cases et on positionne les éléments importants sur les lignes/intersections.'],
                    ],
                ],
                [
                    'title' => 'Niveau 5 — La hiérarchie visuelle',
                    'subtitle' => 'Guider le regard dans le bon ordre',
                    'xp_reward' => 140,
                    'content' => "🎯 **Objectif** : faire en sorte que l'œil lise l'information dans l'ordre voulu.\n\n💡 La **hiérarchie visuelle** classe les éléments par importance. Sans elle, tout semble crier en même temps et le message se perd.\n\n✅ Outils pour créer la hiérarchie :\n• **Taille** : plus c'est grand, plus c'est important\n• **Couleur et contraste** : un accent attire l'œil\n• **Position** : le haut et la gauche sont lus en premier (lecture occidentale)\n• **Graisse** : le gras ressort\n• **Espace** : isoler un élément le met en avant\n\n⚠️ Niveaux types d'un visuel :\n1. **Titre** (accroche, le plus gros)\n2. **Sous-titre** (précise)\n3. **Corps de texte** (détails)\n4. **Appel à l'action / contact**\n\nExemple flyer promo (boutique à Yaoundé) :\n• « SOLDES -50 % » en très gros (niveau 1)\n• « Sur tout le magasin ce samedi » (niveau 2)\n• Adresse + numéro WhatsApp (niveau 3)\n• Logo et horaires (niveau 4)\n\nUn lecteur doit comprendre l'essentiel en **moins de 3 secondes**.",
                    'questions' => [
                        ['question' => 'À quoi sert la hiérarchie visuelle ?', 'options' => ['À choisir l\'imprimeur', 'À guider l\'œil dans l\'ordre d\'importance', 'À réduire le poids du fichier', 'À convertir les couleurs'], 'correct' => [1], 'explanation' => 'Elle organise les éléments par importance pour orienter la lecture.'],
                        ['question' => 'Quels moyens créent de la hiérarchie ?', 'options' => ['La taille des éléments', 'Le contraste de couleur', 'Le nom du fichier', 'La graisse du texte'], 'correct' => [0, 1, 3], 'explanation' => 'Taille, contraste et graisse hiérarchisent ; le nom de fichier n\'a aucun effet visuel.'],
                        ['question' => 'Dans un visuel bien hiérarchisé, quel élément est généralement le plus grand ?', 'options' => ['Le corps de texte', 'Le titre / l\'accroche', 'Les mentions légales', 'Le numéro de téléphone'], 'correct' => [1], 'explanation' => 'Le titre est l\'élément le plus visible car il capte l\'attention en premier.'],
                    ],
                ],
                [
                    'title' => 'Niveau 6 — Logo et identité visuelle',
                    'subtitle' => 'Concevoir une marque mémorable',
                    'xp_reward' => 150,
                    'content' => "🎯 **Objectif** : concevoir un logo solide et comprendre une identité de marque.\n\n💡 Un **logo** est le signe de reconnaissance d'une marque. Il doit être : simple, mémorable, intemporel, polyvalent et adapté au secteur.\n\n✅ Types de logos :\n• **Logotype** : la marque écrite (Coca-Cola)\n• **Monogramme** : initiales (MTN style lettres)\n• **Symbole / pictogramme** : icône (oiseau, pomme)\n• **Combiné** : symbole + texte\n• **Emblème** : texte intégré dans une forme\n\n⚠️ Bonnes pratiques :\n• Tester le logo en **noir et blanc** d'abord\n• Vérifier la lisibilité en **petit** (favicon, tampon)\n• Toujours le concevoir en **vectoriel** (redimensionnable sans perte)\n\nL'**identité visuelle** va plus loin que le logo. La **charte graphique** définit :\n```\n- Logo (versions, zone de protection)\n- Couleurs (codes RVB / CMJN / HEX)\n- Typographies officielles\n- Iconographie et style photo\n- Règles d'usage (interdits)\n```\nUne identité cohérente rend une PME crédible et reconnaissable sur tous ses supports.",
                    'questions' => [
                        ['question' => 'Pourquoi tester un logo en noir et blanc ?', 'options' => ['Pour économiser l\'encre', 'Pour vérifier qu\'il fonctionne sans dépendre de la couleur', 'Parce que la couleur est interdite', 'Pour le rendre plus petit'], 'correct' => [1], 'explanation' => 'Un logo solide doit rester lisible et reconnaissable même sans couleur.'],
                        ['question' => 'Que contient une charte graphique ?', 'options' => ['Les règles d\'usage du logo, couleurs et typographies', 'Le bilan comptable', 'La liste des employés', 'Le plan marketing complet'], 'correct' => [0], 'explanation' => 'La charte graphique fixe l\'usage cohérent du logo, des couleurs et des polices.'],
                        ['question' => 'Quelles qualités caractérisent un bon logo ?', 'options' => ['Simple et mémorable', 'Polyvalent', 'Très détaillé et complexe', 'Intemporel'], 'correct' => [0, 1, 3], 'explanation' => 'Un bon logo est simple, mémorable, polyvalent et intemporel ; la complexité nuit à la reconnaissance.'],
                    ],
                ],
                [
                    'title' => 'Niveau 7 — Formats, RVB et CMJN',
                    'subtitle' => 'Couleurs écran vs impression et résolution',
                    'xp_reward' => 160,
                    'content' => "🎯 **Objectif** : choisir le bon mode colorimétrique, la bonne résolution et le bon format de fichier.\n\n💡 Deux modes de couleur :\n• **RVB** (Rouge Vert Bleu) : synthèse additive, pour les **écrans** (web, réseaux sociaux)\n• **CMJN** (Cyan Magenta Jaune Noir) : synthèse soustractive, pour l'**impression**\n\n⚠️ Un visuel conçu en RVB peut paraître plus terne une fois imprimé en CMJN : on travaille donc en CMJN pour le print.\n\n✅ Résolution :\n• **72 ppp** (pixels par pouce) → écran\n• **300 ppp** → impression de qualité\nUne image floue à l'impression vient souvent d'une résolution trop basse ou d'une image agrandie au-delà de sa taille réelle.\n\nFormats de fichiers :\n```\nLogo / dessin     -> SVG, AI, EPS (vectoriel)\nPhoto web         -> JPG (compressé)\nImage + transparence -> PNG\nDocument à imprimer  -> PDF\nFichier de travail   -> PSD (Photoshop), AI (Illustrator)\n```\n\n**Vectoriel vs matriciel** : le vectoriel (formes) se redimensionne sans perte ; le matriciel (pixels, photos) se pixellise si on l'agrandit trop.",
                    'questions' => [
                        ['question' => 'Quel mode colorimétrique utilise-t-on pour l\'impression ?', 'options' => ['RVB', 'CMJN', 'HEX', 'TSL'], 'correct' => [1], 'explanation' => 'L\'impression utilise le CMJN (Cyan, Magenta, Jaune, Noir).'],
                        ['question' => 'Quelle résolution est recommandée pour une impression de qualité ?', 'options' => ['72 ppp', '150 ppp', '300 ppp', '10 ppp'], 'correct' => [2], 'explanation' => '300 ppp garantit la netteté à l\'impression ; 72 ppp suffit pour l\'écran.'],
                        ['question' => 'Quels formats sont adaptés à un logo redimensionnable sans perte ?', 'options' => ['SVG', 'JPG', 'AI / EPS', 'Image vectorielle'], 'correct' => [0, 2, 3], 'explanation' => 'Le vectoriel (SVG, AI, EPS) se redimensionne sans perte ; le JPG est matriciel et se dégrade.'],
                    ],
                ],
                [
                    'title' => 'Niveau 8 — Les outils : Photoshop et Illustrator',
                    'subtitle' => 'Le bon logiciel pour la bonne tâche',
                    'xp_reward' => 170,
                    'content' => "🎯 **Objectif** : savoir quel logiciel utiliser et maîtriser les bases.\n\n💡 Deux outils phares d'Adobe :\n• **Photoshop** : édition d'**images matricielles** (photos). Retouche, montage, effets, visuels web.\n• **Illustrator** : création **vectorielle**. Logos, icônes, illustrations, mise en page simple.\n\n⚠️ Règle simple : **photo → Photoshop**, **logo/vectoriel → Illustrator**. Pour la mise en page longue (magazine, livre), on utilise **InDesign**. Des alternatives gratuites existent : **GIMP** (≈ Photoshop), **Inkscape** (≈ Illustrator), **Canva** pour le rapide.\n\n✅ Notions à connaître dans les deux :\n• **Calques** (layers) : empiler et organiser les éléments\n• **Plans de travail** (artboards) : plusieurs formats dans un fichier\n• **Outil plume** : tracer des courbes précises (vectoriel)\n• **Masques** : montrer/cacher sans détruire\n\nRaccourcis utiles (Windows) :\n```\nCtrl + Z   -> Annuler\nCtrl + S   -> Enregistrer\nCtrl + G   -> Grouper\nV          -> Outil sélection\nT          -> Outil texte\n```\nLe travail en **calques non destructif** permet de modifier sans tout refaire.",
                    'questions' => [
                        ['question' => 'Pour créer un logo vectoriel, quel logiciel privilégier ?', 'options' => ['Photoshop', 'Illustrator', 'Excel', 'Acrobat Reader'], 'correct' => [1], 'explanation' => 'Illustrator est l\'outil vectoriel idéal pour les logos.'],
                        ['question' => 'Photoshop est surtout conçu pour...', 'options' => ['Éditer des images matricielles / photos', 'Tracer des graphiques financiers', 'Coder des sites', 'Écrire des contrats'], 'correct' => [0], 'explanation' => 'Photoshop manipule des images pixel (matricielles) : retouche et montage.'],
                        ['question' => 'À quoi servent les calques ?', 'options' => ['À organiser et empiler les éléments de façon modifiable', 'À imprimer plus vite', 'À convertir en CMJN', 'À réduire la résolution'], 'correct' => [0], 'explanation' => 'Les calques permettent d\'organiser les éléments et de les modifier sans tout refaire.'],
                    ],
                ],
                [
                    'title' => 'Niveau 9 — Préparation à l\'impression',
                    'subtitle' => 'Fonds perdus, traits de coupe et fichiers propres',
                    'xp_reward' => 185,
                    'content' => "🎯 **Objectif** : livrer un fichier que l'imprimeur acceptera sans problème.\n\n💡 Avant d'envoyer chez l'imprimeur, plusieurs réglages sont indispensables.\n\n✅ Check-list d'un fichier print :\n• **Mode CMJN** activé\n• **Résolution 300 ppp** pour les images\n• **Fonds perdus (bleed)** : 3 à 5 mm de marge qui dépasse, pour éviter un liseré blanc après la coupe\n• **Traits de coupe** : repères qui indiquent où couper\n• **Zone de sécurité** : garder le texte important à 3-5 mm du bord\n• **Polices vectorisées** ou intégrées (pour que l'imprimeur ait les bonnes)\n• **Export en PDF** haute qualité (PDF/X de préférence)\n\n⚠️ Erreurs fréquentes :\n• Oublier les fonds perdus → bords blancs\n• Image en 72 ppp → impression floue\n• Texte trop près du bord → risque d'être coupé\n• Fichier en RVB → couleurs imprévisibles\n\nSchéma d'une carte de visite (85 × 55 mm) :\n```\n[trait de coupe]\n  +-- fond perdu (dépasse) --+\n  |  +-- zone de sécurité -+ |\n  |  |   Nom / contact     | |\n  |  +---------------------+ |\n  +--------------------------+\n```\nUn fichier bien préparé évite des réimpressions coûteuses.",
                    'questions' => [
                        ['question' => 'À quoi servent les fonds perdus (bleed) ?', 'options' => ['À ajouter du texte', 'À éviter un liseré blanc après la coupe', 'À économiser l\'encre', 'À changer la police'], 'correct' => [1], 'explanation' => 'Le fond perdu dépasse de la zone coupée pour garantir une impression sans bord blanc.'],
                        ['question' => 'Quel format d\'export est recommandé pour l\'imprimeur ?', 'options' => ['JPG basse qualité', 'PDF haute qualité (PDF/X)', 'PSD brut', 'Capture d\'écran'], 'correct' => [1], 'explanation' => 'Le PDF haute qualité (PDF/X) préserve couleurs, polices et résolution pour l\'impression.'],
                        ['question' => 'Quelles erreurs compromettent une impression ?', 'options' => ['Fichier en RVB au lieu de CMJN', 'Images en 72 ppp', 'Absence de fonds perdus', 'Export en PDF haute qualité'], 'correct' => [0, 1, 2], 'explanation' => 'RVB, basse résolution et absence de fond perdu causent des problèmes ; le PDF haute qualité est correct.'],
                    ],
                ],
                [
                    'title' => 'Niveau 10 — Le brief client et la livraison',
                    'subtitle' => 'Du besoin du client au projet abouti',
                    'xp_reward' => 200,
                    'content' => "🎯 **Objectif** : comprendre, cadrer et livrer un projet client de façon professionnelle.\n\n💡 Le **brief** est le document de départ : il rassemble le besoin du client. Un projet rate souvent à cause d'un brief flou, pas d'un manque de talent.\n\n✅ Questions à poser au client :\n• Quel est l'objectif ? (vendre, informer, recruter)\n• Quelle est la cible ? (âge, ville, habitudes)\n• Quels supports ? (flyer, post, banderole)\n• Y a-t-il une charte ou des couleurs imposées ?\n• Quel est le délai et le budget ?\n• Des exemples qu'il aime / déteste ?\n\n⚠️ Bonnes pratiques de collaboration :\n• Établir un **devis** clair avant de commencer\n• Préciser le **nombre de révisions** incluses\n• Demander un **acompte** (ex. 50 %), pratique courante au Cameroun\n• Faire valider chaque étape par écrit\n• Livrer les fichiers finaux **et** les sources si convenu\n\nLivraison type :\n```\n/Projet_Client/\n  Logo_final.ai\n  Logo_final.png (fond transparent)\n  Logo_NB.png\n  Charte_graphique.pdf\n```\n\n🏆 **Félicitations !** Tu maîtrises les fondamentaux du design graphique : couleurs, typographie, composition, identité, formats et relation client. \n\n**Débouchés** : graphiste freelance (mobile money + WhatsApp pour gérer tes clients), infographiste en agence ou imprimerie, designer réseaux sociaux pour PME, puis évolution vers directeur artistique, motion designer ou UI/UX designer. Continue à pratiquer, constitue un **portfolio** solide et fixe tes tarifs avec confiance. Bonne route, créatif !",
                    'questions' => [
                        ['question' => 'Quel est le rôle du brief client ?', 'options' => ['Fixer le prix de l\'imprimeur', 'Rassembler et cadrer le besoin du client', 'Choisir la résolution', 'Vectoriser le logo'], 'correct' => [1], 'explanation' => 'Le brief réunit objectif, cible, supports et contraintes pour bien orienter le projet.'],
                        ['question' => 'Quelles pratiques professionnelles sécurisent une collaboration ?', 'options' => ['Établir un devis clair', 'Préciser le nombre de révisions', 'Commencer sans rien valider', 'Demander un acompte'], 'correct' => [0, 1, 3], 'explanation' => 'Devis, révisions définies et acompte protègent le graphiste et le client ; commencer sans validation est risqué.'],
                        ['question' => 'Que faut-il généralement livrer à la fin d\'un projet de logo ?', 'options' => ['Une seule capture d\'écran', 'Les fichiers finaux (vectoriel + versions PNG) et la charte', 'Uniquement le brouillon', 'Rien, juste un aperçu'], 'correct' => [1], 'explanation' => 'On livre les fichiers exploitables (vectoriel, PNG transparent, version N&B) et la charte si convenu.'],
                    ],
                ],
            ],
        ]);

        $this->command->info('  ✓ Roadmap Design Graphique créée (10 niveaux).');
    }
}
