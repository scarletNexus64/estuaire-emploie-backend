<?php

namespace Database\Seeders\Roadmaps;

use Illuminate\Database\Seeder;

/**
 * Roadmap Cuisine (Arts Culinaires) — du débutant à l'agent de cuisine polyvalent.
 */
class CuisineRoadmapSeeder extends Seeder
{
    use RoadmapSeederHelper;

    public function run(): void
    {
        $this->createRoadmap([
            'title' => 'Deviens Cuisinier (Arts Culinaires)',
            'slug' => 'cuisine-arts-culinaires',
            'domain' => 'hotellerie',
            'description' => "Une formation complète pour entrer dans les métiers de la cuisine professionnelle. Tu apprends l'organisation d'une brigade, l'hygiène HACCP, les techniques de découpe, les cuissons, les fonds et sauces mères, le dressage, la gestion des coûts et la création de menus. Exemples ancrés dans le contexte camerounais (marchés de Douala et Yaoundé, produits locaux, restauration africaine et internationale).",
            'objectives' => "Comprendre l'organisation d'une cuisine professionnelle et les rôles de la brigade\nAppliquer les règles d'hygiène HACCP et de sécurité alimentaire\nMaîtriser les techniques de découpe et les cuissons de base\nRéaliser les fonds, les sauces mères et leurs dérivés\nDresser et présenter une assiette de façon professionnelle\nGérer les denrées, calculer un coût matière et construire une fiche technique\nTravailler en sécurité et développer sa créativité culinaire",
            'icon' => '👨‍🍳',
            'color' => '#EA580C',
            'difficulty' => 'beginner',
            'required_packs' => [],
            'pass_threshold' => 70,
            'order' => 20,
            'levels' => [
                [
                    'title' => 'Niveau 1 — La cuisine professionnelle et la brigade',
                    'subtitle' => "Comprendre l'organisation et les rôles",
                    'xp_reward' => 100,
                    'content' => "🎯 **Objectif** : découvrir comment fonctionne une cuisine professionnelle et qui fait quoi.\n\n💡 La cuisine est organisée en **brigade**, un système hiérarchique créé par Auguste Escoffier. Chaque poste a un rôle précis :\n• **Chef de cuisine** : dirige, conçoit les menus, gère les coûts.\n• **Sous-chef** : second du chef, remplace en son absence.\n• **Chef de partie** : responsable d'un poste (sauces, garde-manger, pâtisserie...).\n• **Commis** : assiste, prépare les bases.\n• **Plongeur** : nettoie la vaisselle et le matériel.\n\nDans un petit restaurant à Douala ou Yaoundé, une seule personne cumule souvent plusieurs postes : on parle de **cuisinier polyvalent**.\n\n✅ Le service se déroule en deux temps : la **mise en place** (préparation avant le coup de feu) puis le **service** (envoi des plats). Une bonne mise en place = un service fluide.\n\n⚠️ La communication est essentielle : on répète les commandes à voix haute (« Oui chef ! ») pour éviter les erreurs.",
                    'questions' => [
                        ['question' => 'Qui dirige la cuisine, conçoit les menus et gère les coûts ?', 'options' => ['Le plongeur', 'Le chef de cuisine', 'Le commis', 'Le serveur'], 'correct' => [1], 'explanation' => "Le chef de cuisine est le responsable global : management, menus et gestion des coûts."],
                        ['question' => 'Que désigne la « mise en place » ?', 'options' => ['Le nettoyage de fin de service', "L'addition du client", 'La préparation des ingrédients avant le service', 'Le dressage du salle à manger'], 'correct' => [2], 'explanation' => "La mise en place regroupe toutes les préparations faites avant le coup de feu pour servir vite et bien."],
                        ['question' => 'Quels rôles font partie de la brigade de cuisine ?', 'options' => ['Sous-chef', 'Chef de partie', 'Directeur commercial', 'Commis'], 'correct' => [0, 1, 3], 'explanation' => "Sous-chef, chef de partie et commis sont des postes de la brigade ; le directeur commercial n'en fait pas partie."],
                    ],
                ],
                [
                    'title' => 'Niveau 2 — Hygiène et HACCP',
                    'subtitle' => "Garantir la sécurité alimentaire",
                    'xp_reward' => 110,
                    'content' => "🎯 **Objectif** : appliquer les règles d'hygiène pour ne pas rendre les clients malades.\n\n💡 La méthode **HACCP** (Hazard Analysis Critical Control Point) identifie et maîtrise les dangers : microbiologiques (bactéries), chimiques et physiques. Principe clé : la **marche en avant** — les aliments avancent toujours du sale vers le propre, sans croisement.\n\n✅ Règles d'or :\n• Se laver les mains régulièrement (eau, savon, 30 secondes).\n• Respecter la **chaîne du froid** : conserver à **0 à 4 °C** au réfrigérateur, **-18 °C** au congélateur.\n• Séparer cru et cuit (planches de couleurs différentes).\n• Cuire à cœur à **+63 °C minimum** pour détruire les bactéries.\n\n⚠️ La **zone de danger** se situe entre **+4 °C et +63 °C** : les bactéries s'y multiplient très vite, surtout sous la chaleur de Douala. Ne jamais laisser un plat traîner à température ambiante.\n\n| Aliment | Conservation |\n|---|---|\n| Viande fraîche | 0–4 °C |\n| Surgelés | -18 °C |\n| Plat chaud servi | +63 °C |",
                    'questions' => [
                        ['question' => 'Quelle est la « zone de danger » où les bactéries se multiplient le plus ?', 'options' => ['Entre -18 et 0 °C', 'Entre +4 et +63 °C', 'Au-dessus de +100 °C', 'En dessous de -20 °C'], 'correct' => [1], 'explanation' => "Entre +4 et +63 °C, les bactéries se développent rapidement : il faut limiter le temps passé dans cette plage."],
                        ['question' => 'Que signifie le principe de « marche en avant » ?', 'options' => ['Cuisiner debout', 'Faire avancer les aliments du sale vers le propre sans croisement', 'Servir les plats rapidement', 'Avancer dans le menu'], 'correct' => [1], 'explanation' => "La marche en avant évite que les zones et produits propres croisent les zones et produits sales."],
                        ['question' => 'Quelles pratiques respectent les règles HACCP ?', 'options' => ['Se laver les mains régulièrement', 'Utiliser la même planche pour le cru et le cuit', 'Respecter la chaîne du froid', 'Cuire les viandes à cœur'], 'correct' => [0, 2, 3], 'explanation' => "Mains propres, chaîne du froid et cuisson à cœur sont conformes ; mélanger cru et cuit sur la même planche est interdit."],
                    ],
                ],
                [
                    'title' => 'Niveau 3 — Le matériel et les techniques de découpe',
                    'subtitle' => "Couteaux, sécurité et taille des légumes",
                    'xp_reward' => 120,
                    'content' => "🎯 **Objectif** : maîtriser les couteaux et les découpes de base.\n\n💡 Le couteau est l'outil n°1. Les essentiels :\n• **Couteau de chef (éminceur)** : usage général.\n• **Couteau d'office** : petites tailles, épluchage.\n• **Couteau à désosser** : viandes.\n\nUn couteau **bien aiguisé est plus sûr** qu'un couteau émoussé : il glisse moins. On tient l'aliment en **« griffe »** (doigts repliés) pour protéger ses doigts.\n\n✅ Les tailles classiques de légumes :\n• **Brunoise** : petits dés de 2 mm.\n• **Macédoine** : dés de 5 mm.\n• **Julienne** : fins bâtonnets.\n• **Mirepoix** : gros morceaux pour les fonds.\n• **Émincer** : couper en fines tranches (oignon).\n\n⚠️ Sécurité : jamais de couteau dans l'eau de vaisselle (on ne le voit pas), on transporte la lame pointée vers le bas, et on ne tente jamais de rattraper un couteau qui tombe.\n\nMaîtriser une découpe régulière garantit une **cuisson uniforme** : des dés de tailles égales cuisent en même temps.",
                    'questions' => [
                        ['question' => 'Pourquoi un couteau bien aiguisé est-il plus sûr ?', 'options' => ['Il coupe plus lentement', 'Il glisse moins et demande moins de force', 'Il est plus lourd', 'Il chauffe les aliments'], 'correct' => [1], 'explanation' => "Un couteau affûté pénètre l'aliment sans déraper, réduisant le risque de glissement et de blessure."],
                        ['question' => 'Comment appelle-t-on la coupe en très petits dés de 2 mm ?', 'options' => ['Mirepoix', 'Brunoise', 'Julienne', 'Émincer'], 'correct' => [1], 'explanation' => "La brunoise désigne de très petits dés réguliers d'environ 2 mm."],
                        ['question' => "Quelle est la bonne attitude de sécurité avec un couteau ?", 'options' => ['Le laisser dans l\'eau de vaisselle', 'Tenter de le rattraper en vol', 'Tenir l\'aliment en « griffe »', 'Le poser lame en l\'air sur le bord du plan'], 'correct' => [2], 'explanation' => "La position en griffe replie les doigts derrière la lame et protège des coupures."],
                    ],
                ],
                [
                    'title' => 'Niveau 4 — Les cuissons de base',
                    'subtitle' => "Modes de cuisson et réactions",
                    'xp_reward' => 130,
                    'content' => "🎯 **Objectif** : connaître les grands modes de cuisson et leurs effets.\n\n💡 On distingue trois grandes familles :\n• **Cuissons par concentration** : on saisit pour garder les sucs à l'intérieur (rôtir, griller, sauter, frire).\n• **Cuissons par expansion** : on démarre à froid pour diffuser les saveurs dans le liquide (bouillir, pocher) — idéal pour un bouillon de poulet.\n• **Cuissons mixtes** : on saisit puis on mouille (braiser, ragoût) — parfait pour un ndolè ou un bœuf braisé.\n\n✅ La **réaction de Maillard** est la coloration brune des aliments saisis à chaud : c'est elle qui donne le goût grillé à une viande ou au plantain mûr poêlé. Elle démarre vers **140–165 °C**.\n\n⚠️ Ne pas confondre Maillard (protéines + sucres) et **caramélisation** (sucres seuls). Une poêle pas assez chaude fait **bouillir** la viande au lieu de la dorer : on perd la croûte savoureuse.\n\nMémo : saisir = concentrer ; départ à froid = échanger avec le liquide.",
                    'questions' => [
                        ['question' => 'Quelle famille de cuisson démarre à froid pour parfumer le liquide ?', 'options' => ['Cuisson par concentration', 'Cuisson par expansion', 'Cuisson par friture', 'Cuisson au gril'], 'correct' => [1], 'explanation' => "La cuisson par expansion (départ à froid) fait passer les saveurs de l'aliment vers le liquide, comme pour un bouillon."],
                        ['question' => 'Qu\'est-ce que la réaction de Maillard ?', 'options' => ['La fonte des graisses', 'La coloration brune et savoureuse des aliments saisis', 'La congélation rapide', 'L\'évaporation de l\'eau'], 'correct' => [1], 'explanation' => "La réaction de Maillard, entre acides aminés et sucres sous l'effet de la chaleur, donne couleur et goût grillé."],
                        ['question' => "Pourquoi faut-il une poêle bien chaude pour saisir une viande ?", 'options' => ['Pour la faire bouillir', 'Pour former une croûte dorée via la réaction de Maillard', 'Pour la congeler', 'Pour la rendre fade'], 'correct' => [1], 'explanation' => "Une chaleur suffisante déclenche la réaction de Maillard et crée la croûte savoureuse ; sinon la viande bout."],
                    ],
                ],
                [
                    'title' => 'Niveau 5 — Fonds, bouillons et liaisons',
                    'subtitle' => "Les bases liquides de la cuisine",
                    'xp_reward' => 140,
                    'content' => "🎯 **Objectif** : réaliser les fonds et comprendre les liaisons.\n\n💡 Un **fond** est un bouillon concentré qui sert de base aux sauces, soupes et braisés. On distingue :\n• **Fond blanc** : volaille ou veau non coloré, mouillé à froid.\n• **Fond brun** : os et garniture **colorés au four** avant de mouiller — goût plus profond.\n• **Fumet** : à base de poisson.\n\n✅ Recette de principe d'un fond :\n```\n1. Os + parures\n2. + Mirepoix (carotte, oignon, céleri/poireau)\n3. + Bouquet garni\n4. Mouiller à l'eau froide\n5. Frémir longuement, écumer la surface\n6. Passer au chinois\n```\nÀ Yaoundé on adapte avec carcasse de poulet local et oignon, sans cube industriel pour un goût naturel.\n\n💡 Les **liaisons** épaississent une sauce :\n• **Roux** : beurre + farine cuits (blanc, blond, brun).\n• **Fécule / maïzena** délayée à froid.\n• **Réduction** : on évapore l'eau pour concentrer.\n\n⚠️ On **écume** (retire la mousse) pour un fond clair, et on ne sale jamais fort un fond qui va réduire.",
                    'questions' => [
                        ['question' => 'Quelle est la différence entre un fond blanc et un fond brun ?', 'options' => ['Le fond brun utilise du lait', 'Les os du fond brun sont colorés au four avant mouillage', 'Le fond blanc contient du chocolat', 'Il n\'y a aucune différence'], 'correct' => [1], 'explanation' => "Le fond brun se distingue par la coloration préalable des os et de la garniture, qui apporte couleur et profondeur."],
                        ['question' => 'Qu\'est-ce qu\'un roux ?', 'options' => ['Un mélange de beurre et de farine cuits ensemble', 'Une réduction de vinaigre', 'Un bouillon de poisson', 'Une découpe de légume'], 'correct' => [0], 'explanation' => "Le roux, beurre + farine cuits, est la liaison de base de nombreuses sauces."],
                        ['question' => 'Quels éléments composent un fond classique ?', 'options' => ['Os et parures', 'Mirepoix', 'Bouquet garni', 'Sucre en poudre'], 'correct' => [0, 1, 2], 'explanation' => "Os, mirepoix et bouquet garni forment un fond ; le sucre n'y a pas sa place."],
                    ],
                ],
                [
                    'title' => 'Niveau 6 — Les sauces mères',
                    'subtitle' => "Les 5 bases et leurs dérivés",
                    'xp_reward' => 150,
                    'content' => "🎯 **Objectif** : maîtriser les sauces mères et en déduire les dérivées.\n\n💡 Antonin Carême puis Escoffier ont classé les **sauces mères** : à partir de quelques bases, on crée des dizaines de sauces.\n\n| Sauce mère | Base / liaison | Exemple de dérivé |\n|---|---|---|\n| **Béchamel** | lait + roux blanc | Mornay (+ fromage) |\n| **Velouté** | fond clair + roux | Suprême (+ crème) |\n| **Espagnole** | fond brun + roux | Bordelaise |\n| **Tomate** | tomates mijotées | Sauce provençale |\n| **Hollandaise** | jaunes + beurre clarifié | Béarnaise (+ estragon) |\n\n✅ Principe : la sauce mère donne la **structure**, on ajoute un ou deux ingrédients pour créer la dérivée. Exemple : Béchamel + fromage râpé = **Mornay**, parfaite pour un gratin.\n\n⚠️ La hollandaise est une **émulsion fragile** : si elle chauffe trop, les jaunes coagulent et la sauce « tranche ». On la monte au bain-marie doux, hors du feu vif.\n\n💡 Astuce : goûter et **assaisonner en fin** de préparation, jamais au hasard.",
                    'questions' => [
                        ['question' => 'Quelle sauce mère est à base de lait et de roux blanc ?', 'options' => ['Espagnole', 'Béchamel', 'Hollandaise', 'Tomate'], 'correct' => [1], 'explanation' => "La béchamel se prépare avec du lait lié par un roux blanc."],
                        ['question' => 'La sauce Mornay est un dérivé de quelle sauce mère ?', 'options' => ['Velouté', 'Béchamel', 'Espagnole', 'Hollandaise'], 'correct' => [1], 'explanation' => "La Mornay est une béchamel enrichie de fromage."],
                        ['question' => 'Lesquelles font partie des cinq sauces mères ?', 'options' => ['Velouté', 'Espagnole', 'Mayonnaise', 'Hollandaise'], 'correct' => [0, 1, 3], 'explanation' => "Velouté, espagnole et hollandaise sont des sauces mères ; la mayonnaise n'en fait pas partie."],
                    ],
                ],
                [
                    'title' => 'Niveau 7 — Dressage et présentation',
                    'subtitle' => "Soigner l'assiette",
                    'xp_reward' => 160,
                    'content' => "🎯 **Objectif** : dresser une assiette appétissante et propre.\n\n💡 « On mange d'abord avec les yeux. » Le dressage valorise le travail et justifie le prix. Principes :\n• **Hauteur et volume** : donner du relief, ne pas tout aplatir.\n• **Couleurs** : contraster (vert d'herbes, rouge de sauce, blanc du riz).\n• **Espace** : laisser de l'air, ne pas surcharger l'assiette.\n• **Point focal** : la pièce principale (protéine) bien placée.\n\n✅ La règle des **composants** : un protéique, un féculent/légume, une sauce, une garniture/finition. On pense à l'**équilibre** visuel.\n\n💡 Techniques simples : la **virgule de sauce** au dos d'une cuillère, l'emporte-pièce pour un dôme de riz, les herbes fraîches en touche finale.\n\n⚠️ Hygiène du dressage :\n• Assiette **propre et chaude** (plat chaud) ou froide (plat froid).\n• Pas de traces de doigts ni d'éclaboussures : on essuie le bord.\n• Dresser **au dernier moment** pour servir à bonne température.\n\nUne assiette de poulet DG bien dressée à Douala se remarque autant par son goût que par sa présentation.",
                    'questions' => [
                        ['question' => 'Pourquoi essuie-t-on le bord de l\'assiette avant l\'envoi ?', 'options' => ['Pour la refroidir', 'Pour une présentation propre sans traces', 'Pour ajouter du sel', 'Pour la rendre plus lourde'], 'correct' => [1], 'explanation' => "Un bord propre, sans éclaboussures ni traces de doigts, donne une présentation soignée et professionnelle."],
                        ['question' => 'Quel principe améliore le dressage d\'une assiette ?', 'options' => ['Tout aplatir au maximum', 'Jouer sur la hauteur et le contraste des couleurs', 'Remplir l\'assiette à ras bord', 'Utiliser une seule couleur'], 'correct' => [1], 'explanation' => "Le relief et le contraste de couleurs rendent l'assiette plus appétissante et équilibrée."],
                        ['question' => 'Quand faut-il dresser un plat chaud ?', 'options' => ['Plusieurs heures à l\'avance', 'Au dernier moment, juste avant le service', 'Après l\'avoir laissé refroidir', 'Pendant le nettoyage'], 'correct' => [1], 'explanation' => "On dresse au dernier moment pour servir le plat à la bonne température."],
                    ],
                ],
                [
                    'title' => 'Niveau 8 — Gestion des denrées et coût matière',
                    'subtitle' => "Stocks, pertes et rentabilité",
                    'xp_reward' => 170,
                    'content' => "🎯 **Objectif** : gérer les stocks et calculer le coût d'un plat.\n\n💡 La règle de rotation **FIFO** (First In, First Out / Premier Entré, Premier Sorti) : on consomme d'abord les produits les plus anciens pour limiter les pertes. On surveille les **DLC** (dates limites de consommation).\n\n✅ Le **coût matière** d'une recette = somme du coût des ingrédients utilisés. On en déduit le **ratio matière** :\n```\nRatio matière = Coût matière / Prix de vente HT × 100\n```\nExemple à Yaoundé : un plat coûte **1 500 FCFA** d'ingrédients et se vend **5 000 FCFA**.\nRatio = 1 500 / 5 000 × 100 = **30 %**.\n\n💡 Un ratio matière entre **25 % et 35 %** est sain en restauration. Plus il est bas, plus la marge est élevée.\n\n⚠️ Attention aux **pertes** : épluchage, parures, casse, vol. Le **rendement** (poids net après épluchage / poids brut) doit entrer dans le calcul. 1 kg de manioc brut ne donne pas 1 kg utilisable.\n\nUn bon cuisinier valorise les parures (fonds, bouillons) pour réduire le gaspillage.",
                    'questions' => [
                        ['question' => 'Que signifie la rotation FIFO des stocks ?', 'options' => ['On jette les produits les plus anciens', 'Premier entré, premier sorti', 'On vend d\'abord les produits les plus chers', 'On stocke sans ordre'], 'correct' => [1], 'explanation' => "FIFO signifie consommer d'abord les produits les plus anciens pour éviter qu'ils ne périment."],
                        ['question' => "Un plat coûte 1 500 FCFA d'ingrédients et se vend 5 000 FCFA HT. Quel est le ratio matière ?", 'options' => ['10 %', '30 %', '50 %', '70 %'], 'correct' => [1], 'explanation' => "1 500 / 5 000 × 100 = 30 %, ce qui correspond à un ratio matière sain."],
                        ['question' => 'Quels éléments réduisent le rendement réel des denrées ?', 'options' => ['Les parures et épluchures', 'La casse et les pertes', 'Le dressage soigné', 'Les produits jetés à la DLC'], 'correct' => [0, 1, 3], 'explanation' => "Parures, casse et produits périmés diminuent le rendement ; un dressage soigné n'a pas d'impact sur le rendement."],
                    ],
                ],
                [
                    'title' => 'Niveau 9 — Menu, fiche technique et sécurité',
                    'subtitle' => "Concevoir et travailler en sécurité",
                    'xp_reward' => 185,
                    'content' => "🎯 **Objectif** : construire un menu, rédiger une fiche technique et prévenir les accidents.\n\n💡 La **fiche technique** est la carte d'identité d'un plat : elle garantit la régularité quel que soit le cuisinier. Elle contient :\n• Nom du plat et nombre de **couverts**\n• **Denrées** et quantités précises\n• **Progression** (étapes de réalisation)\n• Coût matière et **prix de vente** conseillé\n• Photo de dressage\n\n✅ Construire un **menu** : équilibrer les entrées/plats/desserts, varier les cuissons et couleurs, tenir compte de la saison et des produits locaux (manioc, plantain, poisson frais), et calculer la rentabilité de chaque ligne.\n\n⚠️ **Sécurité en cuisine** — les risques majeurs :\n• **Coupures** : couteaux affûtés, position en griffe.\n• **Brûlures** : torchon sec pour les manches chauds, signaler « chaud derrière ! ».\n• **Chutes** : sol propre et sec, chaussures antidérapantes.\n• **Incendie** : ne jamais éteindre une **friteuse en feu avec de l'eau** ; couvrir ou utiliser un extincteur adapté.\n\nUne fiche technique claire + une cuisine sûre = un service maîtrisé et rentable.",
                    'questions' => [
                        ['question' => 'À quoi sert une fiche technique de cuisine ?', 'options' => ['À décorer la salle', 'À garantir la régularité et le coût d\'un plat', 'À remplacer le menu client', 'À payer les fournisseurs'], 'correct' => [1], 'explanation' => "La fiche technique standardise quantités, étapes et coûts pour un plat reproductible quel que soit le cuisinier."],
                        ['question' => 'Comment éteindre une friteuse qui prend feu ?', 'options' => ['Verser de l\'eau dessus', 'Couvrir ou utiliser un extincteur adapté', 'La déplacer en courant', 'Souffler dessus'], 'correct' => [1], 'explanation' => "L'eau sur de l'huile en feu provoque une explosion de flammes ; il faut étouffer le feu ou utiliser un extincteur adapté."],
                        ['question' => "Que doit contenir une bonne fiche technique ?", 'options' => ['Denrées et quantités', 'Progression des étapes', 'Coût matière et prix de vente', 'Le numéro de téléphone du client'], 'correct' => [0, 1, 2], 'explanation' => "Denrées, progression et coûts sont indispensables ; le contact client n'y figure pas."],
                    ],
                ],
                [
                    'title' => 'Niveau 10 — Créativité culinaire et carrière',
                    'subtitle' => "Créer, signer son style, évoluer",
                    'xp_reward' => 200,
                    'content' => "🎯 **Objectif** : développer sa créativité et préparer sa carrière.\n\n💡 La créativité repose sur des **bases solides** : on ne crée bien qu'en maîtrisant les techniques précédentes. Pistes pour innover :\n• **Revisiter un plat local** : un ndolè dressé à l'assiette, un poulet DG en version raffinée.\n• **Marier les textures** : croustillant + fondant + croquant.\n• **Équilibrer les saveurs** : sucré, salé, acide, amer, umami.\n• **Valoriser le terroir** : produits de Douala, Yaoundé, marchés locaux, fruits tropicaux.\n\n✅ Démarche de création :\n```\n1. Idée / inspiration\n2. Essai (test en petite quantité)\n3. Dégustation et ajustement\n4. Fiche technique\n5. Mise à la carte\n```\n\n⚠️ Reste curieux : goûte, voyage par les recettes, note tes idées dans un carnet.\n\n🏆 **Félicitations !** Tu as parcouru les 10 niveaux des arts culinaires : brigade, hygiène, découpe, cuissons, fonds, sauces, dressage, coûts, fiche technique, sécurité et créativité.\n\n**Débouchés** : commis de cuisine → chef de partie → sous-chef → chef de cuisine → chef exécutif. Tu peux aussi viser la **pâtisserie**, le **catering/traiteur**, la **restauration d'hôtel**, le **food truck** ou ouvrir ton propre restaurant. Le secteur de l'hôtellerie-restauration recrute fortement à Douala et Yaoundé. Continue à pratiquer chaque jour : la cuisine est un métier de répétition et de passion. Bon courage, chef ! 👨‍🍳",
                    'questions' => [
                        ['question' => 'Sur quoi repose une vraie créativité culinaire ?', 'options' => ['Sur le hasard total', 'Sur la maîtrise des techniques de base', 'Sur le prix des ingrédients uniquement', 'Sur la décoration de la salle'], 'correct' => [1], 'explanation' => "On innove durablement seulement quand les bases techniques sont solides."],
                        ['question' => "Quelle est une étape clé avant de mettre un nouveau plat à la carte ?", 'options' => ['Le servir directement sans test', 'Faire un essai, déguster puis ajuster', 'Augmenter le prix au hasard', 'Le congeler pendant un an'], 'correct' => [1], 'explanation' => "On teste en petite quantité, on goûte et on ajuste avant de standardiser le plat."],
                        ['question' => "Quelle est une progression de carrière classique en cuisine ?", 'options' => ['Commis → chef de partie → sous-chef → chef', 'Plongeur → directeur financier', 'Serveur → comptable', 'Chef → commis → plongeur'], 'correct' => [0], 'explanation' => "La progression habituelle va du commis au chef en passant par chef de partie et sous-chef."],
                    ],
                ],
            ],
        ]);

        $this->command->info('  ✓ Roadmap Cuisine (Arts Culinaires) créée (10 niveaux).');
    }
}
