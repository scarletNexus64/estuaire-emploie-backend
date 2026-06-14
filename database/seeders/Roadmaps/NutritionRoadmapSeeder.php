<?php

namespace Database\Seeders\Roadmaps;

use Illuminate\Database\Seeder;

/**
 * Roadmap Nutrition — devenir nutritionniste / diététicien, des bases à l'accompagnement.
 */
class NutritionRoadmapSeeder extends Seeder
{
    use RoadmapSeederHelper;

    public function run(): void
    {
        $this->createRoadmap([
            'title' => 'Deviens Nutritionniste / Diététicien',
            'slug' => 'nutritionniste-dieteticien',
            'domain' => 'sante',
            'description' => "Apprends les fondamentaux de la nutrition humaine : nutriments, besoins énergétiques, équilibre alimentaire, évaluation de l'état nutritionnel, prise en charge des pathologies courantes (diabète, hypertension), hygiène alimentaire et accompagnement comportemental. Une formation ancrée dans le contexte alimentaire africain.",
            'objectives' => "Distinguer macronutriments et micronutriments et leurs rôles\nCalculer les besoins énergétiques et construire des repas équilibrés\nÉvaluer l'état nutritionnel (IMC, anthropométrie)\nAdapter l'alimentation aux pathologies (diabète, HTA)\nAppliquer les règles d'hygiène alimentaire\nDéconstruire les idées reçues et accompagner le changement de comportement",
            'icon' => '🥗',
            'color' => '#16A34A',
            'difficulty' => 'beginner',
            'required_packs' => [],
            'pass_threshold' => 70,
            'order' => 20,
            'levels' => [
                [
                    'title' => 'Niveau 1 — Les macronutriments',
                    'subtitle' => 'Glucides, lipides, protéines',
                    'xp_reward' => 100,
                    'content' => "🎯 **Objectif** : comprendre les trois macronutriments qui fournissent l'énergie.\n\n💡 Les macronutriments sont consommés en grande quantité et apportent des calories :\n\n• **Glucides** : 4 kcal/g — carburant principal du corps et du cerveau. Exemples : manioc, riz, plantain, pain, igname.\n• **Lipides** (graisses) : 9 kcal/g — réserve d'énergie, transport des vitamines A, D, E, K. Exemples : huile de palme, arachide, avocat, poisson gras.\n• **Protéines** : 4 kcal/g — construction et réparation des tissus, immunité. Exemples : poisson, viande, œufs, haricots, soja, ndolé avec arachide.\n\n✅ Repère mémoire :\n```\nGlucides   → 4 kcal/g\nProtéines  → 4 kcal/g\nLipides    → 9 kcal/g (le plus calorique)\n```\n\n⚠️ Les lipides ne sont pas « mauvais » : ils sont indispensables. C'est l'excès et le choix de mauvaises graisses (fritures répétées) qui posent problème.",
                    'questions' => [
                        ['question' => 'Quel macronutriment apporte le plus de calories par gramme ?', 'options' => ['Les glucides', 'Les lipides', 'Les protéines', 'L\'eau'], 'correct' => [1], 'explanation' => 'Les lipides fournissent 9 kcal/g, contre 4 kcal/g pour les glucides et les protéines.'],
                        ['question' => 'Quel est le rôle principal des protéines ?', 'options' => ['Fournir la réserve de graisse', 'Construire et réparer les tissus', 'Hydrater le corps', 'Colorer les aliments'], 'correct' => [1], 'explanation' => 'Les protéines servent à la construction et à la réparation des tissus ainsi qu\'à l\'immunité.'],
                        ['question' => 'Quels aliments sont de bonnes sources de protéines ? (plusieurs réponses)', 'options' => ['Le poisson', 'Le sucre de table', 'Les haricots', 'L\'huile végétale'], 'correct' => [0, 2], 'explanation' => 'Le poisson et les haricots sont riches en protéines ; le sucre est un glucide et l\'huile un lipide.'],
                    ],
                ],
                [
                    'title' => 'Niveau 2 — Les micronutriments',
                    'subtitle' => 'Vitamines, minéraux et oligo-éléments',
                    'xp_reward' => 110,
                    'content' => "🎯 **Objectif** : reconnaître les micronutriments et leurs sources.\n\n💡 Les micronutriments sont nécessaires en petites quantités mais essentiels :\n\n• **Vitamine A** : vision, immunité — feuilles vertes, mangue, huile de palme rouge.\n• **Vitamine C** : immunité, absorption du fer — goyave, agrumes, piment, tomate.\n• **Fer** : transport de l'oxygène, lutte contre l'anémie — foie, viande rouge, légumes verts, légumineuses.\n• **Calcium** : os et dents — lait, poisson avec arêtes, légumes verts.\n• **Iode** : fonctionnement de la thyroïde — sel iodé, poissons de mer.\n\n✅ Astuce d'absorption : associer une source de vitamine C à un repas riche en fer végétal (ex. tomate + haricots) augmente l'absorption du fer.\n\n⚠️ Les carences sont fréquentes en Afrique : anémie (fer), cécité nocturne (vitamine A), goitre (iode). Une alimentation variée et colorée prévient la plupart de ces carences.",
                    'questions' => [
                        ['question' => 'Quelle carence en micronutriment provoque l\'anémie ?', 'options' => ['Carence en fer', 'Carence en vitamine D', 'Excès de calcium', 'Carence en fibres'], 'correct' => [0], 'explanation' => 'L\'anémie nutritionnelle la plus courante est due à un manque de fer.'],
                        ['question' => 'Quel nutriment associer au fer végétal pour mieux l\'absorber ?', 'options' => ['La vitamine C', 'Le calcium', 'Le sodium', 'Les lipides'], 'correct' => [0], 'explanation' => 'La vitamine C améliore nettement l\'absorption du fer d\'origine végétale.'],
                        ['question' => 'À quoi sert l\'iode dans l\'organisme ?', 'options' => ['Au fonctionnement de la thyroïde', 'À la coagulation du sang', 'À la digestion des graisses', 'À la couleur des cheveux'], 'correct' => [0], 'explanation' => 'L\'iode est indispensable au bon fonctionnement de la thyroïde ; sa carence cause le goitre.'],
                    ],
                ],
                [
                    'title' => 'Niveau 3 — Les besoins énergétiques',
                    'subtitle' => 'Métabolisme de base et dépense calorique',
                    'xp_reward' => 120,
                    'content' => "🎯 **Objectif** : estimer les besoins énergétiques d'une personne.\n\n💡 La dépense énergétique totale dépend de trois composantes :\n\n• **Métabolisme de base (MB)** : énergie au repos pour les fonctions vitales (~60-70 %).\n• **Activité physique** : marche, travail physique, sport (variable).\n• **Thermogenèse** : énergie pour digérer les aliments (~10 %).\n\n✅ Repère simple pour un adulte :\n```\nSédentaire   → ~25-30 kcal/kg/jour\nActif modéré → ~30-35 kcal/kg/jour\nTrès actif   → ~35-40 kcal/kg/jour\n```\nExemple : une femme de 60 kg, employée de bureau à Yaoundé (sédentaire) a besoin d'environ 60 × 28 ≈ 1 680 kcal/jour.\n\n⚠️ Les besoins augmentent pendant la grossesse, l'allaitement, la croissance et chez les sportifs. Un apport insuffisant entraîne une fatigue et une fonte musculaire ; un excès chronique mène au surpoids.",
                    'questions' => [
                        ['question' => 'Quelle composante représente la plus grande part de la dépense énergétique au repos ?', 'options' => ['Le métabolisme de base', 'La thermogenèse alimentaire', 'L\'activité sportive', 'La digestion seule'], 'correct' => [0], 'explanation' => 'Le métabolisme de base représente environ 60 à 70 % de la dépense énergétique totale.'],
                        ['question' => 'Dans quelles situations les besoins énergétiques augmentent-ils ? (plusieurs réponses)', 'options' => ['Pendant la grossesse', 'Pendant l\'allaitement', 'En cas de sédentarité totale', 'Pendant le sommeil prolongé'], 'correct' => [0, 1], 'explanation' => 'La grossesse et l\'allaitement augmentent les besoins énergétiques ; la sédentarité les réduit.'],
                        ['question' => 'Un apport calorique chroniquement insuffisant entraîne surtout :', 'options' => ['Une fatigue et une fonte musculaire', 'Une prise de poids rapide', 'Une hausse de la masse osseuse', 'Une meilleure immunité'], 'correct' => [0], 'explanation' => 'Un déficit énergétique prolongé provoque fatigue, perte de masse musculaire et affaiblissement.'],
                    ],
                ],
                [
                    'title' => 'Niveau 4 — L\'équilibre alimentaire',
                    'subtitle' => 'Construire une assiette équilibrée',
                    'xp_reward' => 130,
                    'content' => "🎯 **Objectif** : composer des repas équilibrés au quotidien.\n\n💡 Un repas équilibré combine les groupes alimentaires. Le modèle de l'assiette :\n\n| Part de l'assiette | Groupe | Exemples locaux |\n|---|---|---|\n| 1/2 | Légumes / crudités | feuilles de manioc, ndolé, gombo, tomate |\n| 1/4 | Féculents | riz, plantain, manioc, igname |\n| 1/4 | Protéines | poisson, viande, haricots, œuf |\n| + | Fruit + eau | mangue, papaye, eau |\n\n✅ Répartition indicative des apports énergétiques sur la journée :\n• Glucides : 50-55 %\n• Lipides : 30-35 %\n• Protéines : 12-15 %\n\n⚠️ Pièges courants : sodas et jus sucrés à chaque repas, fritures quotidiennes, absence de légumes. Boire de l'eau plutôt que des boissons sucrées et privilégier la variété sur la semaine sont les bases d'un équilibre durable.",
                    'questions' => [
                        ['question' => 'Selon le modèle de l\'assiette équilibrée, quelle part occupent les légumes ?', 'options' => ['Environ la moitié', 'Un huitième', 'Rien du tout', 'La totalité'], 'correct' => [0], 'explanation' => 'Les légumes devraient occuper environ la moitié de l\'assiette.'],
                        ['question' => 'Quelle est la part recommandée des glucides dans l\'apport énergétique journalier ?', 'options' => ['50-55 %', '5-10 %', '80-90 %', '0 %'], 'correct' => [0], 'explanation' => 'Les glucides représentent idéalement 50 à 55 % de l\'apport énergétique total.'],
                        ['question' => 'Quelle boisson privilégier pendant les repas ?', 'options' => ['L\'eau', 'Les sodas', 'Les jus industriels sucrés', 'Les boissons énergisantes'], 'correct' => [0], 'explanation' => 'L\'eau est la boisson à privilégier ; les boissons sucrées apportent des calories vides.'],
                    ],
                ],
                [
                    'title' => 'Niveau 5 — IMC et évaluation nutritionnelle',
                    'subtitle' => 'Mesurer et interpréter l\'état nutritionnel',
                    'xp_reward' => 140,
                    'content' => "🎯 **Objectif** : calculer l'IMC et utiliser les mesures anthropométriques.\n\n💡 L'**Indice de Masse Corporelle (IMC)** se calcule ainsi :\n```\nIMC = poids (kg) / taille² (m)\nEx. 70 kg / (1,70 m)² = 70 / 2,89 = 24,2\n```\nInterprétation (adulte) :\n\n| IMC | Interprétation |\n|---|---|\n| < 18,5 | Maigreur |\n| 18,5 - 24,9 | Normal |\n| 25 - 29,9 | Surpoids |\n| ≥ 30 | Obésité |\n\n✅ Autres outils utiles :\n• **Tour de taille** : risque cardiovasculaire élevé si > 94 cm (homme) ou > 80 cm (femme).\n• **Périmètre brachial** (MUAC) : dépistage de la malnutrition chez l'enfant.\n\n⚠️ L'IMC a des limites : il ne distingue pas muscle et graisse. Un sportif musclé peut avoir un IMC élevé sans être en surpoids. Toujours croiser plusieurs mesures et le contexte clinique.",
                    'questions' => [
                        ['question' => 'Comment calcule-t-on l\'IMC ?', 'options' => ['Poids (kg) divisé par la taille au carré (m²)', 'Taille divisée par le poids', 'Poids multiplié par l\'âge', 'Tour de taille divisé par la taille'], 'correct' => [0], 'explanation' => 'L\'IMC = poids en kg divisé par le carré de la taille en mètres.'],
                        ['question' => 'Un IMC de 32 chez un adulte correspond à :', 'options' => ['Une obésité', 'Une maigreur', 'Un poids normal', 'Un surpoids léger'], 'correct' => [0], 'explanation' => 'Un IMC supérieur ou égal à 30 indique une obésité.'],
                        ['question' => 'Quelle est une limite importante de l\'IMC ?', 'options' => ['Il ne distingue pas la masse musculaire de la masse grasse', 'Il est impossible à calculer', 'Il dépend de la couleur de peau', 'Il mesure le taux de sucre'], 'correct' => [0], 'explanation' => 'L\'IMC ne fait pas la différence entre muscle et graisse, d\'où l\'intérêt d\'autres mesures.'],
                    ],
                ],
                [
                    'title' => 'Niveau 6 — Nutrition et diabète',
                    'subtitle' => 'Glycémie, index glycémique et conseils',
                    'xp_reward' => 150,
                    'content' => "🎯 **Objectif** : adapter l'alimentation d'une personne diabétique.\n\n💡 Le diabète se caractérise par une glycémie (taux de sucre dans le sang) trop élevée. L'alimentation vise à stabiliser cette glycémie.\n\n• **Index glycémique (IG)** : vitesse à laquelle un aliment élève la glycémie.\n  - IG élevé : pain blanc, riz blanc, sucre, sodas → pics de glycémie.\n  - IG bas : légumineuses (haricots, lentilles), légumes verts, igname bouillie, fruits entiers.\n• **Conseils clés** :\n  - Fractionner les repas, ne pas sauter de repas.\n  - Privilégier les fibres (légumes, céréales complètes) qui ralentissent l'absorption du sucre.\n  - Limiter sucres rapides et boissons sucrées.\n  - Associer toujours glucides + protéines + légumes.\n\n✅ Exemple : remplacer un grand verre de soda par de l'eau et préférer le haricot au riz blanc raffiné.\n\n⚠️ Le jus de fruit « naturel » reste très sucré : mieux vaut manger le fruit entier, riche en fibres.",
                    'questions' => [
                        ['question' => 'Quel type d\'aliment provoque les pics de glycémie les plus forts ?', 'options' => ['Les aliments à index glycémique élevé', 'Les légumes verts', 'Les légumineuses', 'Les protéines maigres'], 'correct' => [0], 'explanation' => 'Les aliments à IG élevé comme le sucre ou le pain blanc élèvent rapidement la glycémie.'],
                        ['question' => 'Pourquoi les fibres sont-elles utiles chez le diabétique ?', 'options' => ['Elles ralentissent l\'absorption du sucre', 'Elles augmentent la glycémie', 'Elles remplacent l\'insuline', 'Elles suppriment le besoin de manger'], 'correct' => [0], 'explanation' => 'Les fibres ralentissent l\'absorption des glucides et limitent les pics de glycémie.'],
                        ['question' => 'Quels conseils donner à une personne diabétique ? (plusieurs réponses)', 'options' => ['Fractionner les repas', 'Limiter les boissons sucrées', 'Boire beaucoup de sodas', 'Sauter régulièrement des repas'], 'correct' => [0, 1], 'explanation' => 'Fractionner les repas et limiter les boissons sucrées aident à stabiliser la glycémie.'],
                    ],
                ],
                [
                    'title' => 'Niveau 7 — Nutrition et hypertension (HTA)',
                    'subtitle' => 'Sel, potassium et habitudes',
                    'xp_reward' => 160,
                    'content' => "🎯 **Objectif** : adapter l'alimentation en cas d'hypertension artérielle.\n\n💡 L'hypertension (HTA) est une pression artérielle trop élevée, facteur majeur d'AVC et de maladies cardiaques. L'alimentation joue un rôle direct, surtout via le sel.\n\n• **Réduire le sodium (sel)** : l'OMS recommande moins de 5 g de sel par jour (≈ une cuillère à café). Attention au sel caché : bouillons cubes, poisson fumé salé, charcuterie, conserves.\n• **Augmenter le potassium** : banane, légumes verts, tomate, légumineuses — il aide à équilibrer la tension.\n• **Limiter** : aliments ultra-transformés, fritures, excès d'alcool.\n• **Favoriser** : fruits, légumes, poisson, céréales complètes (proche du régime DASH).\n\n✅ Astuce locale : remplacer une partie des cubes assaisonnement et du sel par des épices et aromates (ail, oignon, gingembre, persil) pour garder le goût.\n\n⚠️ Le sel « caché » est souvent plus important que le sel ajouté à table. Lire les habitudes de cuisine est essentiel.",
                    'questions' => [
                        ['question' => 'Quel nutriment faut-il surtout réduire en cas d\'hypertension ?', 'options' => ['Le sodium (sel)', 'Le potassium', 'Les fibres', 'La vitamine C'], 'correct' => [0], 'explanation' => 'Réduire le sel (sodium) est la mesure alimentaire centrale contre l\'hypertension.'],
                        ['question' => 'Quelle quantité de sel l\'OMS recommande-t-elle au maximum par jour ?', 'options' => ['Moins de 5 g', 'Environ 20 g', 'Au moins 15 g', 'Aucune limite'], 'correct' => [0], 'explanation' => 'L\'OMS recommande de consommer moins de 5 g de sel par jour.'],
                        ['question' => 'Où se cache souvent le sel en grande quantité ?', 'options' => ['Dans les bouillons cubes et aliments transformés', 'Dans les fruits frais', 'Dans l\'eau plate', 'Dans les légumes verts crus'], 'correct' => [0], 'explanation' => 'Les bouillons cubes, conserves et produits transformés contiennent beaucoup de sel caché.'],
                    ],
                ],
                [
                    'title' => 'Niveau 8 — Hygiène et sécurité alimentaire',
                    'subtitle' => 'Prévenir les intoxications',
                    'xp_reward' => 170,
                    'content' => "🎯 **Objectif** : appliquer les règles d'hygiène pour des aliments sains.\n\n💡 Les maladies d'origine alimentaire (diarrhées, intoxications) sont fréquentes. Les **5 clés de l'OMS pour des aliments plus sûrs** :\n\n• **1. Propreté** : se laver les mains et nettoyer surfaces et ustensiles.\n• **2. Séparer** cru et cuit : éviter que le jus de viande crue ne contamine les aliments prêts.\n• **3. Bien cuire** : surtout viande, volaille, poisson et œufs.\n• **4. Conserver à bonne température** : ne pas laisser les plats cuits à température ambiante plus de 2 heures ; réfrigérer si possible.\n• **5. Eau et matières premières sûres** : eau potable, fruits et légumes bien lavés.\n\n✅ La « zone de danger » microbienne se situe entre 5 °C et 60 °C : c'est là que les bactéries se multiplient le plus vite.\n\n⚠️ En climat chaud, sans réfrigération fiable, cuisiner en quantité raisonnable et consommer rapidement réduit fortement les risques.",
                    'questions' => [
                        ['question' => 'Pourquoi séparer les aliments crus et cuits ?', 'options' => ['Pour éviter la contamination croisée', 'Pour économiser de la place', 'Pour améliorer le goût', 'Pour réduire les calories'], 'correct' => [0], 'explanation' => 'Séparer cru et cuit évite que les microbes du cru ne contaminent les aliments prêts à manger.'],
                        ['question' => 'Quelle est la « zone de danger » microbienne ?', 'options' => ['Entre 5 °C et 60 °C', 'Entre -20 °C et 0 °C', 'Au-dessus de 100 °C', 'En dessous de -10 °C'], 'correct' => [0], 'explanation' => 'Les bactéries se multiplient rapidement entre 5 °C et 60 °C.'],
                        ['question' => 'Quelles pratiques d\'hygiène alimentaire sont recommandées ? (plusieurs réponses)', 'options' => ['Se laver les mains avant de cuisiner', 'Bien cuire la viande et le poisson', 'Laisser les plats cuits dehors toute la journée', 'Utiliser de l\'eau non potable pour laver les légumes'], 'correct' => [0, 1], 'explanation' => 'Le lavage des mains et une cuisson suffisante sont des règles d\'hygiène essentielles.'],
                    ],
                ],
                [
                    'title' => 'Niveau 9 — Idées reçues et accompagnement',
                    'subtitle' => 'Déconstruire les mythes, changer les comportements',
                    'xp_reward' => 185,
                    'content' => "🎯 **Objectif** : corriger les idées reçues et accompagner le changement.\n\n💡 Idées reçues fréquentes à déconstruire :\n\n• « Manger gras fait toujours grossir » → c'est l'excès calorique global qui compte ; les bonnes graisses sont utiles.\n• « Sauter le repas du soir fait maigrir durablement » → favorise plutôt grignotage et reprise de poids.\n• « Les produits light font maigrir » → souvent compensés par d'autres excès.\n• « Le sucre roux est sain, le blanc dangereux » → différence nutritionnelle minime.\n• « Être en surpoids = bonne santé / signe de richesse » → faux et dangereux culturellement.\n\n✅ **Accompagnement comportemental** : le rôle du nutritionniste n'est pas de juger mais de guider. Outils :\n• Écoute active et objectifs réalistes (SMART).\n• Petits changements progressifs plutôt que régimes drastiques.\n• Renforcement positif et suivi régulier.\n• Tenir compte du budget, de la culture et des habitudes familiales.\n\n⚠️ Les régimes très restrictifs échouent à long terme (effet yo-yo). On vise un changement durable, pas une privation temporaire.",
                    'questions' => [
                        ['question' => 'Pourquoi les régimes très restrictifs échouent-ils souvent à long terme ?', 'options' => ['Ils provoquent l\'effet yo-yo et ne sont pas durables', 'Ils sont trop riches en légumes', 'Ils coûtent toujours trop cher', 'Ils sont interdits par la loi'], 'correct' => [0], 'explanation' => 'Les régimes drastiques entraînent reprise de poids (effet yo-yo) car ils ne sont pas tenables.'],
                        ['question' => 'Quelle approche est la plus efficace pour changer les habitudes ?', 'options' => ['Des petits changements progressifs et réalistes', 'Une privation totale immédiate', 'Juger et culpabiliser le patient', 'Interdire tous les aliments locaux'], 'correct' => [0], 'explanation' => 'Des objectifs réalistes et des changements progressifs favorisent un changement durable.'],
                        ['question' => 'Quelles affirmations sont des idées reçues à corriger ? (plusieurs réponses)', 'options' => ['Le surpoids est forcément un signe de bonne santé', 'Les produits light font automatiquement maigrir', 'Une alimentation variée prévient les carences', 'L\'eau est la meilleure boisson'], 'correct' => [0, 1], 'explanation' => 'Le surpoids n\'est pas un gage de santé et les produits light ne font pas maigrir à eux seuls.'],
                    ],
                ],
                [
                    'title' => 'Niveau 10 — Nutrition en contexte africain',
                    'subtitle' => 'Valoriser le patrimoine alimentaire local',
                    'xp_reward' => 200,
                    'content' => "🎯 **Objectif** : appliquer la nutrition au contexte alimentaire africain et bâtir sa pratique.\n\n💡 Le défi est la **double charge nutritionnelle** : coexistence de la malnutrition (carences, retard de croissance) et des maladies liées au surpoids (diabète, HTA) dans les villes comme Douala et Yaoundé.\n\n• **Atouts locaux** : aliments riches et accessibles — feuilles vertes (ndolé, folong), légumineuses, poisson, fruits (mangue, papaye, goyave), tubercules. Un patrimoine à valoriser plutôt qu'à remplacer.\n• **Risques émergents** : sodas, fritures de rue, excès de cubes assaisonnement, ultra-transformés bon marché.\n• **Conseil culturellement adapté** : travailler avec les plats existants (réduire l'huile et le sel, ajouter des légumes) plutôt qu'imposer des aliments étrangers coûteux.\n\n✅ Conseils budget : les légumineuses et les feuilles vertes offrent un excellent rapport nutrition/prix.\n\n🏆 **Félicitations !** Tu maîtrises les fondamentaux de la nutrition : nutriments, besoins, équilibre, évaluation, pathologies, hygiène et accompagnement.\n\n**Débouchés** : diététicien en hôpital ou clinique, conseiller en nutrition, agent de santé communautaire, consultant en restauration collective, éducateur en santé publique, ou entrepreneur (consultations, ateliers, contenus). Prochaine étape : formation certifiante, stages cliniques et spécialisation (nutrition infantile, sportive, ou clinique).",
                    'questions' => [
                        ['question' => 'Qu\'appelle-t-on la « double charge nutritionnelle » ?', 'options' => ['La coexistence de la malnutrition et des maladies liées au surpoids', 'Le fait de manger deux fois plus', 'Le double prix des aliments', 'La consommation de deux repas par jour'], 'correct' => [0], 'explanation' => 'La double charge nutritionnelle désigne la coexistence des carences et des maladies de surpoids.'],
                        ['question' => 'Quelle approche est recommandée pour conseiller en contexte africain ?', 'options' => ['Adapter et améliorer les plats locaux existants', 'Imposer uniquement des aliments importés coûteux', 'Supprimer tous les féculents traditionnels', 'Interdire les légumes verts'], 'correct' => [0], 'explanation' => 'Il est plus efficace et durable d\'adapter les plats locaux en valorisant le patrimoine alimentaire.'],
                        ['question' => 'Quels aliments locaux offrent un bon rapport nutrition/prix ? (plusieurs réponses)', 'options' => ['Les légumineuses', 'Les feuilles vertes', 'Les sodas sucrés', 'Les fritures de rue répétées'], 'correct' => [0, 1], 'explanation' => 'Les légumineuses et les feuilles vertes sont nutritives, accessibles et économiques.'],
                    ],
                ],
            ],
        ]);

        $this->command->info('  ✓ Roadmap Nutrition créée (10 niveaux).');
    }
}
