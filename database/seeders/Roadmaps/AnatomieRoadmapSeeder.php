<?php

namespace Database\Seeders\Roadmaps;

use Illuminate\Database\Seeder;

/**
 * Roadmap Anatomie & Physiologie — comprendre l'organisation et le fonctionnement du corps humain.
 */
class AnatomieRoadmapSeeder extends Seeder
{
    use RoadmapSeederHelper;

    public function run(): void
    {
        $this->createRoadmap([
            'title' => "Maîtrise l'Anatomie & la Physiologie Humaine",
            'slug' => 'anatomie-physiologie',
            'domain' => 'sante',
            'description' => "Une initiation claire et progressive au corps humain : comment il est organisé, de quoi il est fait, et comment chaque grand système (squelette, muscles, cœur, poumons, digestion, nerfs, reins, hormones) travaille pour nous garder en vie. Pensée pour les futurs aides-soignants, infirmiers, agents de santé communautaire et étudiants en sciences de la santé au Cameroun et en Afrique francophone.",
            'objectives' => "Décrire les niveaux d'organisation du corps humain\nIdentifier les principaux tissus et leurs rôles\nNommer les os et muscles majeurs et leurs fonctions\nExpliquer le fonctionnement du cœur, des poumons et de la digestion\nComprendre le rôle du système nerveux, des reins et des hormones\nDéfinir l'homéostasie et son importance pour la santé",
            'icon' => '🫀',
            'color' => '#DC2626',
            'difficulty' => 'beginner',
            'required_packs' => [],
            'pass_threshold' => 70,
            'order' => 20,
            'levels' => [
                [
                    'title' => 'Niveau 1 — Organisation du corps humain',
                    'subtitle' => "Des atomes à l'organisme entier",
                    'xp_reward' => 100,
                    'content' => "🎯 **Objectif** : comprendre comment le corps est construit, étage par étage.\n\n💡 Le corps humain s'organise en niveaux, du plus petit au plus grand :\n\n• **Atomes** → assemblés en **molécules** (eau, protéines, sucres)\n• **Cellules** → la plus petite unité vivante (ex. globule rouge)\n• **Tissus** → un groupe de cellules semblables (ex. tissu musculaire)\n• **Organes** → plusieurs tissus qui forment une structure (cœur, foie)\n• **Systèmes (ou appareils)** → des organes qui coopèrent (appareil digestif)\n• **Organisme** → tous les systèmes réunis : un être humain complet\n\n✅ On utilise aussi un vocabulaire de position : **antérieur** (devant), **postérieur** (derrière), **proximal** (près du tronc), **distal** (loin du tronc). La **position anatomique** de référence : debout, paumes vers l'avant.\n\n⚠️ Retenez l'ordre : cellules → tissus → organes → systèmes → organisme. C'est la base de tout le reste.",
                    'questions' => [
                        ['question' => "Quel est le bon ordre des niveaux d'organisation, du plus petit au plus grand ?", 'options' => ['Organe → tissu → cellule → système', 'Cellule → tissu → organe → système', 'Tissu → cellule → système → organe', 'Système → organe → cellule → tissu'], 'correct' => [1], 'explanation' => "On part de la cellule, qui forme des tissus, qui forment des organes, regroupés en systèmes."],
                        ['question' => "Quelle est la plus petite unité vivante du corps ?", 'options' => ['La molécule', "L'atome", 'La cellule', "L'organe"], 'correct' => [2], 'explanation' => "La cellule est la plus petite unité capable de vivre par elle-même."],
                        ['question' => "Le terme « distal » désigne une partie située...", 'options' => ['Près du tronc', 'Loin du tronc', "À l'avant du corps", "À l'arrière du corps"], 'correct' => [1], 'explanation' => "« Distal » signifie éloigné du point d'attache ou du tronc (ex. la main est distale par rapport à l'épaule)."],
                    ],
                ],
                [
                    'title' => 'Niveau 2 — La cellule et les tissus',
                    'subtitle' => "Les briques vivantes du corps",
                    'xp_reward' => 110,
                    'content' => "🎯 **Objectif** : connaître la cellule et les 4 grands types de tissus.\n\n💡 La **cellule** possède trois parties clés :\n• La **membrane** : enveloppe qui contrôle les entrées/sorties\n• Le **cytoplasme** : milieu où flottent les organites (ex. **mitochondries**, qui produisent l'énergie)\n• Le **noyau** : contient l'ADN, le « plan de construction »\n\n✅ Les cellules semblables forment 4 grands **tissus** :\n\n| Tissu | Rôle principal | Exemple |\n|-------|----------------|---------|\n| Épithélial | Recouvrir, protéger | peau, paroi de l'intestin |\n| Conjonctif | Soutenir, relier | os, sang, graisse |\n| Musculaire | Produire le mouvement | muscle du bras, cœur |\n| Nerveux | Transmettre des signaux | cerveau, nerfs |\n\n⚠️ Le **sang** est un tissu conjonctif (ses cellules baignent dans un liquide, le plasma). Les **mitochondries** sont surnommées les « centrales énergétiques » de la cellule.",
                    'questions' => [
                        ['question' => "Quelle structure de la cellule contient l'ADN ?", 'options' => ['La membrane', 'Le noyau', 'Le cytoplasme', 'La mitochondrie'], 'correct' => [1], 'explanation' => "Le noyau renferme l'ADN, qui contient les informations génétiques de la cellule."],
                        ['question' => "Quel tissu a pour rôle de transmettre des signaux dans le corps ?", 'options' => ['Le tissu épithélial', 'Le tissu conjonctif', 'Le tissu nerveux', 'Le tissu musculaire'], 'correct' => [2], 'explanation' => "Le tissu nerveux transmet les messages électriques entre le cerveau et le reste du corps."],
                        ['question' => "Parmi ces éléments, lesquels sont des tissus conjonctifs ? (plusieurs réponses)", 'options' => ['Le sang', 'La peau (épiderme)', "L'os", 'Le muscle cardiaque'], 'correct' => [0, 2], 'explanation' => "Le sang et l'os sont des tissus conjonctifs ; la peau est épithéliale et le cœur musculaire."],
                    ],
                ],
                [
                    'title' => 'Niveau 3 — Le système squelettique',
                    'subtitle' => "La charpente du corps",
                    'xp_reward' => 120,
                    'content' => "🎯 **Objectif** : connaître les os, leurs rôles et les grandes articulations.\n\n💡 Le squelette adulte compte environ **206 os**. Ses rôles :\n• **Soutien** : il forme la charpente du corps\n• **Protection** : le crâne protège le cerveau, la cage thoracique le cœur et les poumons\n• **Mouvement** : les os servent de leviers aux muscles\n• **Fabrication du sang** : la **moelle osseuse rouge** produit les cellules sanguines\n• **Réserve de calcium**\n\n✅ Quelques os à retenir :\n• **Crâne** (tête) • **Colonne vertébrale** (dos) • **Côtes** (thorax)\n• **Fémur** : os de la cuisse, le plus long et le plus solide\n• **Tibia** et **péroné** (jambe) • **Humérus** (bras)\n\n⚠️ Les os se rejoignent aux **articulations**. Certaines sont mobiles (genou, épaule), d'autres fixes (os du crâne). Les os sont reliés entre eux par des **ligaments**.\n\n💡 Astuce santé : la marche au soleil et une alimentation riche en calcium (poisson, légumes verts) renforcent les os.",
                    'questions' => [
                        ['question' => "Quel est l'os le plus long du corps humain ?", 'options' => ["L'humérus", 'Le tibia', 'Le fémur', 'La colonne vertébrale'], 'correct' => [2], 'explanation' => "Le fémur, os de la cuisse, est le plus long et le plus résistant du corps."],
                        ['question' => "Où sont fabriquées les cellules sanguines ?", 'options' => ['Dans le foie', 'Dans la moelle osseuse rouge', 'Dans la peau', 'Dans les ligaments'], 'correct' => [1], 'explanation' => "La moelle osseuse rouge, à l'intérieur des os, produit les cellules du sang."],
                        ['question' => "Qu'est-ce qui relie deux os entre eux au niveau d'une articulation ?", 'options' => ['Un tendon', 'Un ligament', 'Un nerf', 'Une artère'], 'correct' => [1], 'explanation' => "Les ligaments relient les os entre eux ; les tendons relient les muscles aux os."],
                    ],
                ],
                [
                    'title' => 'Niveau 4 — Le système musculaire',
                    'subtitle' => "Le moteur du mouvement",
                    'xp_reward' => 125,
                    'content' => "🎯 **Objectif** : distinguer les types de muscles et comprendre le mouvement.\n\n💡 Il existe **3 types de muscles** :\n• **Squelettique (strié)** : volontaire, attaché aux os, permet de bouger (biceps, mollet)\n• **Lisse** : involontaire, dans les parois des organes (estomac, intestin, vaisseaux)\n• **Cardiaque** : involontaire, uniquement dans le cœur, ne se fatigue jamais\n\n✅ Comment naît un mouvement :\n• Le cerveau envoie un signal nerveux au muscle\n• Le muscle se **contracte** (raccourcit) et tire sur l'os via un **tendon**\n• Les muscles travaillent souvent par paires opposées : quand le **biceps** se contracte pour plier le bras, le **triceps** se relâche\n\n⚠️ Un muscle ne peut que **tirer**, jamais pousser. C'est pourquoi il faut un muscle « partenaire » pour faire le mouvement inverse (on parle de muscles **antagonistes**).\n\n💡 Le mouvement consomme de l'énergie produite par les mitochondries des cellules musculaires ; un bon apport en eau et en glucides soutient l'effort.",
                    'questions' => [
                        ['question' => "Quel type de muscle se trouve uniquement dans le cœur ?", 'options' => ['Le muscle lisse', 'Le muscle squelettique', 'Le muscle cardiaque', 'Le muscle strié volontaire'], 'correct' => [2], 'explanation' => "Le muscle cardiaque est un muscle involontaire propre au cœur, qui ne se fatigue jamais."],
                        ['question' => "Qu'est-ce qui relie un muscle à un os ?", 'options' => ['Un ligament', 'Un tendon', 'Un cartilage', 'Une veine'], 'correct' => [1], 'explanation' => "Les tendons attachent les muscles aux os pour transmettre la force de contraction."],
                        ['question' => "Un muscle peut effectuer quel type d'action mécanique ?", 'options' => ['Seulement pousser', 'Seulement tirer (se contracter)', 'Pousser et tirer en même temps', 'Aucune action'], 'correct' => [1], 'explanation' => "Un muscle ne peut que se contracter et tirer ; le mouvement inverse exige un muscle antagoniste."],
                    ],
                ],
                [
                    'title' => 'Niveau 5 — Le système cardiovasculaire',
                    'subtitle' => "Le cœur et la circulation du sang",
                    'xp_reward' => 135,
                    'content' => "🎯 **Objectif** : comprendre le cœur, le sang et son trajet dans le corps.\n\n💡 Le **cœur** est une pompe musculaire à **4 cavités** :\n• 2 **oreillettes** (en haut, reçoivent le sang)\n• 2 **ventricules** (en bas, propulsent le sang)\n\n✅ Les vaisseaux sanguins :\n• **Artères** : transportent le sang du cœur vers les organes (sous pression)\n• **Veines** : ramènent le sang vers le cœur\n• **Capillaires** : minuscules vaisseaux où se font les échanges (oxygène, nutriments)\n\n💡 Deux circulations :\n• **Petite circulation** : cœur → poumons → cœur (le sang se recharge en oxygène)\n• **Grande circulation** : cœur → corps → cœur (le sang livre l'oxygène)\n\n```\nPoumons --(O2)--> Cœur --(artères)--> Organes\nOrganes --(veines)--> Cœur --> Poumons (cycle)\n```\n\n⚠️ Le **sang** transporte l'oxygène (via les **globules rouges**), combat les infections (**globules blancs**) et aide à la coagulation (**plaquettes**). Une tension artérielle trop élevée fatigue le cœur : surveiller sa tension est essentiel.",
                    'questions' => [
                        ['question' => "Combien de cavités possède le cœur humain ?", 'options' => ['2', '3', '4', '6'], 'correct' => [2], 'explanation' => "Le cœur possède 4 cavités : deux oreillettes et deux ventricules."],
                        ['question' => "Quel vaisseau ramène le sang vers le cœur ?", 'options' => ["L'artère", 'La veine', 'Le capillaire', 'Le tendon'], 'correct' => [1], 'explanation' => "Les veines ramènent le sang vers le cœur ; les artères l'en éloignent."],
                        ['question' => "Quelles cellules du sang transportent l'oxygène ?", 'options' => ['Les globules blancs', 'Les plaquettes', 'Les globules rouges', 'Le plasma'], 'correct' => [2], 'explanation' => "Les globules rouges contiennent l'hémoglobine qui fixe et transporte l'oxygène."],
                    ],
                ],
                [
                    'title' => 'Niveau 6 — Le système respiratoire',
                    'subtitle' => "Comment le corps respire",
                    'xp_reward' => 145,
                    'content' => "🎯 **Objectif** : suivre le trajet de l'air et comprendre les échanges gazeux.\n\n💡 Trajet de l'air à l'inspiration :\n```\nNez/Bouche → Pharynx → Larynx → Trachée → Bronches → Alvéoles\n```\n\n• La **trachée** se divise en deux **bronches** (une par poumon)\n• Les bronches se ramifient en bronchioles, terminées par les **alvéoles**\n• Les **alvéoles** sont de minuscules sacs entourés de capillaires : c'est là que se font les **échanges gazeux**\n\n✅ L'échange gazeux :\n• L'**oxygène (O₂)** de l'air passe dans le sang\n• Le **dioxyde de carbone (CO₂)**, déchet, passe du sang vers l'air et est expiré\n\n💡 Le **diaphragme** est le muscle principal de la respiration : il s'abaisse pour faire entrer l'air (inspiration) et remonte pour l'expulser (expiration).\n\n⚠️ Le système respiratoire et le système cardiovasculaire travaillent ensemble : les poumons captent l'oxygène, le cœur le distribue. La fumée et la pollution endommagent les alvéoles — d'où l'importance d'un air propre.",
                    'questions' => [
                        ['question' => "Où se font les échanges gazeux (oxygène / dioxyde de carbone) ?", 'options' => ['Dans la trachée', 'Dans les alvéoles', 'Dans le larynx', 'Dans le nez'], 'correct' => [1], 'explanation' => "Les alvéoles, entourées de capillaires, sont le lieu des échanges gazeux dans les poumons."],
                        ['question' => "Quel muscle est le principal responsable de la respiration ?", 'options' => ['Le biceps', 'Le cœur', 'Le diaphragme', "Le muscle lisse de l'estomac"], 'correct' => [2], 'explanation' => "Le diaphragme se contracte et se relâche pour faire entrer et sortir l'air des poumons."],
                        ['question' => "Quel gaz, déchet de l'organisme, est rejeté lors de l'expiration ?", 'options' => ["L'oxygène", "L'azote", 'Le dioxyde de carbone', "L'hydrogène"], 'correct' => [2], 'explanation' => "Le dioxyde de carbone (CO₂) est un déchet du métabolisme évacué par l'expiration."],
                    ],
                ],
                [
                    'title' => 'Niveau 7 — Le système digestif',
                    'subtitle' => "De la bouche à l'absorption des nutriments",
                    'xp_reward' => 155,
                    'content' => "🎯 **Objectif** : comprendre comment les aliments sont transformés et absorbés.\n\n💡 Le trajet des aliments :\n```\nBouche → Œsophage → Estomac → Intestin grêle → Gros intestin → Anus\n```\n\n• **Bouche** : mastication + salive (début de la digestion des sucres)\n• **Estomac** : brasse les aliments avec des sucs acides (digère les protéines)\n• **Intestin grêle** : lieu principal de l'**absorption** des nutriments dans le sang\n• **Gros intestin** : récupère l'eau, forme les déchets (selles)\n\n✅ Des organes annexes aident la digestion :\n• Le **foie** fabrique la **bile** (digère les graisses)\n• Le **pancréas** produit des enzymes et l'**insuline**\n\n⚠️ Ne pas confondre digestion et absorption :\n• **Digestion** = casser les aliments en petits morceaux\n• **Absorption** = faire passer ces nutriments dans le sang (surtout dans l'intestin grêle)\n\n💡 Boire de l'eau propre et bien laver les aliments évite les maladies digestives (diarrhées) fréquentes en zone tropicale.",
                    'questions' => [
                        ['question' => "Dans quel organe se fait l'essentiel de l'absorption des nutriments ?", 'options' => ["L'estomac", "L'intestin grêle", 'Le gros intestin', "L'œsophage"], 'correct' => [1], 'explanation' => "L'intestin grêle est le site principal d'absorption des nutriments dans le sang."],
                        ['question' => "Quel organe fabrique la bile, utile à la digestion des graisses ?", 'options' => ['Le pancréas', 'Le foie', "L'estomac", 'Le rein'], 'correct' => [1], 'explanation' => "Le foie produit la bile, qui facilite la digestion des graisses."],
                        ['question' => "Quel est le bon ordre du trajet des aliments ?", 'options' => ['Bouche → estomac → œsophage → intestin', 'Bouche → œsophage → estomac → intestin grêle', 'Estomac → bouche → intestin → œsophage', 'Œsophage → bouche → estomac → intestin'], 'correct' => [1], 'explanation' => "Les aliments passent par la bouche, l'œsophage, l'estomac, puis l'intestin grêle."],
                    ],
                ],
                [
                    'title' => 'Niveau 8 — Le système nerveux',
                    'subtitle' => "Le centre de commande",
                    'xp_reward' => 165,
                    'content' => "🎯 **Objectif** : comprendre comment le corps reçoit, traite et envoie l'information.\n\n💡 Le système nerveux se divise en deux :\n• **Système nerveux central (SNC)** : le **cerveau** + la **moelle épinière** (traitement, décision)\n• **Système nerveux périphérique** : les **nerfs** qui relient le SNC au reste du corps\n\n✅ La cellule du système nerveux est le **neurone**. Il transmet des messages sous forme de signaux électriques, très rapidement.\n\n💡 Deux grands rôles :\n• **Volontaire** : décider de bouger, parler, marcher\n• **Involontaire (autonome)** : régler la respiration, le rythme cardiaque, la digestion, sans y penser\n\n⚠️ Le **réflexe** est une réaction automatique et très rapide qui passe par la moelle épinière sans attendre le cerveau (ex. retirer la main d'une surface brûlante). Cela protège le corps immédiatement.\n\n💡 Le cerveau commande aussi les sens (vue, ouïe, toucher) et travaille avec les hormones pour coordonner tout l'organisme. Protéger sa tête (casque à moto) protège ce centre de commande.",
                    'questions' => [
                        ['question' => "De quoi est composé le système nerveux central ?", 'options' => ['Du cœur et des poumons', 'Du cerveau et de la moelle épinière', 'Des nerfs des bras et jambes', 'Des muscles et des os'], 'correct' => [1], 'explanation' => "Le système nerveux central est formé du cerveau et de la moelle épinière."],
                        ['question' => "Quelle est la cellule de base du système nerveux ?", 'options' => ['Le globule rouge', 'Le neurone', "L'alvéole", 'La plaquette'], 'correct' => [1], 'explanation' => "Le neurone est la cellule qui transmet les signaux nerveux dans le corps."],
                        ['question' => "Lesquelles de ces fonctions sont contrôlées de façon involontaire (automatique) ? (plusieurs réponses)", 'options' => ['Le battement du cœur', 'Marcher volontairement', 'La digestion', 'Parler'], 'correct' => [0, 2], 'explanation' => "Le rythme cardiaque et la digestion sont gérés automatiquement par le système nerveux autonome."],
                    ],
                ],
                [
                    'title' => 'Niveau 9 — Systèmes rénal et endocrinien',
                    'subtitle' => "Filtrage du sang et hormones",
                    'xp_reward' => 180,
                    'content' => "🎯 **Objectif** : comprendre le rôle des reins et des hormones.\n\n💡 **Le système rénal (urinaire)** :\n• Les deux **reins** filtrent le sang en continu\n• Ils éliminent les déchets et l'excès d'eau sous forme d'**urine**\n• L'urine descend par les **uretères** → **vessie** → est évacuée\n• Les reins règlent aussi l'équilibre en eau et en sels du corps\n\n✅ **Le système endocrinien** utilise des **hormones** : des messagers chimiques libérés dans le sang par des **glandes**.\n\n| Glande | Hormone | Rôle |\n|--------|---------|------|\n| Pancréas | Insuline | Baisse le sucre dans le sang |\n| Thyroïde | Hormones thyroïdiennes | Règlent le métabolisme |\n| Surrénales | Adrénaline | Réaction au stress |\n\n⚠️ Différence importante :\n• **Système nerveux** = messages **rapides** (électriques)\n• **Système endocrinien** = messages **plus lents mais durables** (chimiques)\n\n💡 Le **diabète** survient quand l'insuline manque ou agit mal : le sucre s'accumule dans le sang. Boire assez d'eau aide les reins à bien filtrer, surtout sous la chaleur.",
                    'questions' => [
                        ['question' => "Quel est le rôle principal des reins ?", 'options' => ["Pomper le sang", "Filtrer le sang et produire l'urine", 'Digérer les graisses', 'Produire des globules blancs'], 'correct' => [1], 'explanation' => "Les reins filtrent le sang, éliminent les déchets et fabriquent l'urine."],
                        ['question' => "Quelle hormone fait baisser le taux de sucre dans le sang ?", 'options' => ["L'adrénaline", "L'insuline", 'La bile', "L'hémoglobine"], 'correct' => [1], 'explanation' => "L'insuline, produite par le pancréas, fait baisser la glycémie ; son défaut cause le diabète."],
                        ['question' => "Comparé au système nerveux, le système endocrinien transmet ses messages...", 'options' => ['Plus vite mais brièvement', 'Plus lentement mais de façon durable', 'Exactement à la même vitesse', "Sans aucun messager"], 'correct' => [1], 'explanation' => "Les hormones agissent plus lentement que les nerfs mais leur effet dure plus longtemps."],
                    ],
                ],
                [
                    'title' => 'Niveau 10 — L\'homéostasie : l\'équilibre du corps',
                    'subtitle' => "Comment le corps reste stable",
                    'xp_reward' => 200,
                    'content' => "🎯 **Objectif** : comprendre l'homéostasie, qui relie tous les systèmes étudiés.\n\n💡 L'**homéostasie**, c'est la capacité du corps à **maintenir un milieu interne stable** malgré les changements extérieurs : température, taux de sucre, eau, sels, pH.\n\n✅ Le mécanisme : la **boucle de régulation (rétroaction négative)**\n• Un **capteur** détecte un écart (ex. il fait trop chaud)\n• Un **centre de contrôle** (souvent le cerveau) décide\n• Un **effecteur** corrige (ex. on transpire pour se refroidir)\n\n```\nTrop chaud → cerveau détecte → on transpire → la température baisse → retour à la normale\n```\n\n💡 Exemples au quotidien :\n• **Température** : frissons (réchauffer) ou sueur (refroidir)\n• **Sucre sanguin** : l'insuline le fait baisser après un repas\n• **Eau** : les reins ajustent l'urine selon l'hydratation\n\n⚠️ Quand l'homéostasie échoue, la maladie apparaît : fièvre, déshydratation, diabète non contrôlé. Tous les systèmes (nerveux, endocrinien, rénal, cardiovasculaire) coopèrent pour cet équilibre.\n\n🏆 **Félicitations !** Vous avez parcouru tout le corps humain : de la cellule à l'organisme entier. Vous maîtrisez les bases de l'anatomie et de la physiologie ! Ces connaissances ouvrent la voie aux métiers de **aide-soignant**, **infirmier**, **agent de santé communautaire**, **technicien de laboratoire** ou aux études de **médecine** et de **sciences infirmières**. Continuez à apprendre : la santé des autres commence par votre savoir. 💪🩺",
                    'questions' => [
                        ['question' => "Que signifie le terme « homéostasie » ?", 'options' => ['La croissance des os', "Le maintien d'un milieu interne stable", 'La digestion des aliments', 'La fabrication des hormones'], 'correct' => [1], 'explanation' => "L'homéostasie est la capacité du corps à garder son milieu interne stable et équilibré."],
                        ['question' => "Quand il fait très chaud, comment le corps réagit-il pour se refroidir ?", 'options' => ['Il frissonne', 'Il transpire', 'Il arrête de respirer', 'Il augmente le sucre sanguin'], 'correct' => [1], 'explanation' => "La transpiration évacue la chaleur et fait baisser la température corporelle."],
                        ['question' => "Dans une boucle de régulation, quels éléments interviennent ? (plusieurs réponses)", 'options' => ["Un capteur qui détecte l'écart", 'Un effecteur qui corrige', 'Un tendon qui se contracte', 'Un centre de contrôle qui décide'], 'correct' => [0, 1, 3], 'explanation' => "Une boucle de régulation associe un capteur, un centre de contrôle et un effecteur."],
                    ],
                ],
            ],
        ]);

        $this->command->info('  ✓ Roadmap Anatomie & Physiologie créée (10 niveaux).');
    }
}
