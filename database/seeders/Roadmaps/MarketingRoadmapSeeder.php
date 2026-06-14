<?php

namespace Database\Seeders\Roadmaps;

use Illuminate\Database\Seeder;

/**
 * Roadmap Marketing Digital — des fondamentaux jusqu'à la stratégie et le budget.
 * Contenu rédigé (tips), QCM de validation par niveau.
 */
class MarketingRoadmapSeeder extends Seeder
{
    use RoadmapSeederHelper;

    public function run(): void
    {
        $this->createRoadmap([
            'title' => 'Deviens Expert en Marketing Digital',
            'slug' => 'marketing-digital-de-a-a-z',
            'domain' => 'marketing',
            'description' => "Apprends à attirer, convaincre et fidéliser une audience en ligne. Des canaux digitaux au SEO, des réseaux sociaux à la publicité, jusqu'à la mesure de la performance et la gestion d'un budget.",
            'objectives' => "Comprendre l'écosystème du marketing digital\nDéfinir sa cible et ses personas\nCréer une stratégie de contenu et de SEO\nAnimer des réseaux sociaux et une communauté\nLancer des campagnes publicitaires et d'emailing\nConstruire un tunnel de conversion\nMesurer la performance avec les bons KPIs\nOptimiser grâce à l'analytics et l'A/B testing\nRépartir un budget marketing efficacement",
            'icon' => '📣',
            'color' => '#F43F5E',
            'difficulty' => 'beginner',
            'required_packs' => [],
            'pass_threshold' => 70,
            'order' => 1,
            'levels' => [
                [
                    'title' => 'Niveau 1 — Fondamentaux du marketing digital',
                    'subtitle' => 'Une vue d\'ensemble des canaux',
                    'xp_reward' => 100,
                    'content' => "🎯 **Objectif** : comprendre ce qu'est le marketing digital et ses grands canaux.\n\n💡 Le **marketing digital** regroupe toutes les actions de promotion réalisées sur des supports numériques : site web, moteurs de recherche, réseaux sociaux, email, publicité en ligne. Contrairement au marketing traditionnel (affiche, TV, radio), il est **mesurable** : on sait combien de personnes ont vu, cliqué et acheté.\n\n✅ On distingue souvent trois grandes familles de canaux :\n• **Owned media** (médias possédés) : ton site, ton blog, ta liste email. Tu les contrôles.\n• **Earned media** (médias gagnés) : bouche-à-oreille, partages, avis clients, articles de presse. Tu les mérites.\n• **Paid media** (médias payants) : Google Ads, Facebook Ads, partenariats sponsorisés. Tu les achètes.\n\n💡 Un bon marketing digital combine ces trois familles : on attire avec du contenu (owned), on amplifie avec de la pub (paid) et on capitalise sur la confiance (earned).\n\n✅ La grande force du digital, c'est la **donnée** : chaque clic, chaque ouverture d'email, chaque visite est traçable. Cela permet d'ajuster en continu plutôt que de deviner.\n\n⚠️ Piège fréquent : vouloir être présent partout en même temps. Mieux vaut maîtriser 2 ou 3 canaux que d'être dilué sur dix.",
                    'questions' => [
                        [
                            'question' => 'Quelle est la principale différence entre le marketing digital et le marketing traditionnel ?',
                            'options' => ['Il est plus cher', 'Il est mesurable précisément', 'Il ne fonctionne que pour les grandes entreprises', 'Il n\'utilise pas d\'images'],
                            'correct' => [1],
                            'explanation' => 'Le digital permet de mesurer précisément vues, clics et conversions, ce qui est difficile en marketing traditionnel.',
                        ],
                        [
                            'question' => 'Parmi ces canaux, lesquels sont des "owned media" (médias possédés) ? (plusieurs réponses)',
                            'options' => ['Ton blog', 'Une publicité Google Ads', 'Ta liste email', 'Un article de presse sur ta marque'],
                            'correct' => [0, 2],
                            'explanation' => 'Le blog et la liste email t\'appartiennent et tu les contrôles. La pub est du paid media, l\'article de presse du earned media.',
                        ],
                        [
                            'question' => 'À quoi correspond le "paid media" ?',
                            'options' => ['Le bouche-à-oreille', 'Les canaux que tu possèdes', 'Les canaux publicitaires que tu achètes', 'Les avis clients'],
                            'correct' => [2],
                            'explanation' => 'Le paid media regroupe les espaces publicitaires achetés (Google Ads, Facebook Ads, etc.).',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 2 — Connaître sa cible',
                    'subtitle' => 'Persona et segmentation',
                    'xp_reward' => 100,
                    'content' => "🎯 **Objectif** : savoir à qui tu parles avant de communiquer.\n\n💡 Vouloir parler à \"tout le monde\", c'est ne parler à personne. Le marketing efficace commence par **définir précisément sa cible**.\n\n✅ Un **persona** est le portrait-robot semi-fictif de ton client idéal. Il regroupe :\n• des données démographiques (âge, sexe, localisation, métier),\n• des objectifs et motivations (ce qu'il veut accomplir),\n• des freins et frustrations (ce qui l'empêche d'acheter),\n• ses canaux préférés (où il passe du temps en ligne).\n\n✅ Exemple : « Awa, 28 ans, jeune diplômée à Douala, cherche un premier emploi, active sur Instagram et LinkedIn, frustrée par le manque d'offres claires. »\n\n💡 La **segmentation** consiste à découper ton marché en groupes homogènes (par âge, comportement, besoin) pour adapter ton message à chacun. On ne parle pas de la même façon à un étudiant et à un dirigeant d'entreprise.\n\n✅ Plus ton message est aligné avec les besoins réels d'un segment, plus ta communication convertit. Un message générique a un faible impact.\n\n⚠️ Erreur classique : inventer un persona \"de tête\" sans données. Appuie-toi sur de vrais clients, des sondages ou des entretiens.",
                    'questions' => [
                        [
                            'question' => 'Qu\'est-ce qu\'un persona en marketing ?',
                            'options' => ['Un logiciel de publicité', 'Le portrait-robot de ton client idéal', 'Un type de réseau social', 'Un indicateur de performance'],
                            'correct' => [1],
                            'explanation' => 'Le persona est une représentation semi-fictive du client idéal, basée sur des données réelles.',
                        ],
                        [
                            'question' => 'À quoi sert la segmentation ?',
                            'options' => ['À envoyer le même message à tout le monde', 'À découper le marché en groupes pour adapter le message', 'À supprimer des clients', 'À mesurer le budget'],
                            'correct' => [1],
                            'explanation' => 'La segmentation découpe le marché en groupes homogènes pour personnaliser la communication.',
                        ],
                        [
                            'question' => 'Quels éléments composent un bon persona ? (plusieurs réponses)',
                            'options' => ['Ses objectifs et motivations', 'Le chiffre d\'affaires de ton entreprise', 'Ses freins et frustrations', 'Ses canaux préférés en ligne'],
                            'correct' => [0, 2, 3],
                            'explanation' => 'Un persona décrit le client (objectifs, freins, canaux), pas les finances de ton entreprise.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 3 — Stratégie de contenu',
                    'subtitle' => 'Content marketing et calendrier éditorial',
                    'xp_reward' => 120,
                    'content' => "🎯 **Objectif** : attirer ta cible avec du contenu utile plutôt qu'avec de la pub intrusive.\n\n💡 Le **content marketing** consiste à créer et diffuser du contenu de valeur (articles, vidéos, infographies, posts) pour attirer une audience et gagner sa confiance. On ne vend pas directement : on aide, on informe, on inspire — et la vente suit.\n\n✅ Les formats de contenu sont variés :\n• articles de blog (bons pour le SEO),\n• vidéos courtes et tutoriels,\n• infographies et carrousels,\n• études de cas et témoignages,\n• newsletters.\n\n✅ Un bon contenu répond à une question que se pose ta cible. Pense « problème du client » avant « produit à vendre ».\n\n💡 Le **calendrier éditorial** planifie quoi publier, où et quand. Il évite la publication au hasard et garantit la **régularité**, qui est la clé : mieux vaut un article solide par semaine que dix publications anarchiques puis plus rien.\n\n✅ Une méthode efficace : la règle du recyclage. Un seul format long (ex. un article ou une vidéo) peut être découpé en plusieurs posts pour les réseaux sociaux, une newsletter et une infographie.\n\n⚠️ Le contenu ne donne pas de résultats immédiats : c'est un investissement de moyen-long terme qui se cumule dans le temps.",
                    'questions' => [
                        [
                            'question' => 'Quel est le principe du content marketing ?',
                            'options' => ['Vendre directement et agressivement', 'Créer du contenu utile pour attirer et gagner la confiance', 'Acheter uniquement de la publicité', 'Envoyer beaucoup d\'emails promotionnels'],
                            'correct' => [1],
                            'explanation' => 'Le content marketing attire l\'audience avec du contenu de valeur plutôt qu\'avec de la vente directe.',
                        ],
                        [
                            'question' => 'À quoi sert un calendrier éditorial ?',
                            'options' => ['À calculer le budget publicitaire', 'À planifier quoi publier, où et quand pour garder une régularité', 'À mesurer le taux de conversion', 'À segmenter les clients'],
                            'correct' => [1],
                            'explanation' => 'Le calendrier éditorial organise les publications et garantit la régularité.',
                        ],
                        [
                            'question' => 'Pourquoi la régularité est-elle importante en content marketing ?',
                            'options' => ['Pour publier le plus possible en une seule fois', 'Pour cumuler les résultats dans le temps et garder son audience', 'Parce que les algorithmes l\'interdisent autrement', 'Pour réduire le coût des publicités'],
                            'correct' => [1],
                            'explanation' => 'Le contenu est un investissement de long terme : la régularité fidélise l\'audience et cumule les effets.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 4 — SEO : le référencement naturel',
                    'subtitle' => 'Mots-clés, on-page et backlinks',
                    'xp_reward' => 150,
                    'content' => "🎯 **Objectif** : faire remonter ton site dans les résultats de recherche Google, sans payer.\n\n💡 Le **SEO** (Search Engine Optimization) désigne l'ensemble des techniques pour améliorer la position d'un site dans les résultats **naturels** (non payants) des moteurs de recherche. Bien positionné, tu reçois du trafic gratuit et régulier.\n\n✅ Le SEO repose sur trois grands piliers :\n• **Les mots-clés** : les termes que tape ta cible. Il faut écrire sur ce que les gens recherchent vraiment.\n• **L'on-page** : l'optimisation des pages elles-mêmes (titre, balise meta description, structure des titres H1/H2, contenu de qualité, vitesse de chargement, version mobile).\n• **Les backlinks** : les liens d'autres sites qui pointent vers le tien. Ils agissent comme des votes de confiance : plus tu as de liens de qualité, plus Google te juge crédible.\n\n✅ Différence à retenir : le **SEO** est gratuit mais lent (résultats sur plusieurs mois), tandis que le **SEA** (la pub Google) est payant mais immédiat.\n\n💡 Bonne pratique on-page : un mot-clé principal par page, placé dans le titre, l'URL et les premiers paragraphes — mais sans bourrage de mots-clés, que Google pénalise.\n\n⚠️ Le SEO demande de la patience : ne t'attends pas à grimper en première page en une semaine.",
                    'questions' => [
                        [
                            'question' => 'Que désigne le SEO ?',
                            'options' => ['La publicité payante sur Google', 'L\'optimisation pour apparaître dans les résultats naturels', 'Un réseau social professionnel', 'Un logiciel d\'emailing'],
                            'correct' => [1],
                            'explanation' => 'Le SEO vise à améliorer la position dans les résultats naturels (non payants) des moteurs de recherche.',
                        ],
                        [
                            'question' => 'Que sont les backlinks ?',
                            'options' => ['Des liens internes entre tes pages', 'Des liens d\'autres sites pointant vers le tien', 'Des publicités sponsorisées', 'Des mots-clés cachés'],
                            'correct' => [1],
                            'explanation' => 'Les backlinks sont des liens externes vers ton site ; ils agissent comme des votes de confiance.',
                        ],
                        [
                            'question' => 'Quels éléments font partie du SEO on-page ? (plusieurs réponses)',
                            'options' => ['La balise titre de la page', 'Le budget de tes campagnes Ads', 'La structure des titres H1/H2', 'La vitesse de chargement'],
                            'correct' => [0, 2, 3],
                            'explanation' => 'Le on-page concerne le contenu et la technique de la page (titre, structure, vitesse). Le budget Ads relève du SEA payant.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 5 — Les réseaux sociaux',
                    'subtitle' => 'Choisir ses plateformes et sa ligne éditoriale',
                    'xp_reward' => 150,
                    'content' => "🎯 **Objectif** : être présent là où ta cible se trouve, avec le bon ton.\n\n💡 Chaque réseau social a sa propre audience et ses propres codes. Il ne sert à rien d'être partout : choisis les plateformes où ta cible est réellement active.\n\n✅ Repères rapides :\n• **Instagram / TikTok** : visuel, jeune, lifestyle, vidéos courtes.\n• **LinkedIn** : professionnel, B2B, recrutement, expertise.\n• **Facebook** : large audience, communautés, événements locaux.\n• **X (Twitter)** : actualité, réactivité, conversations.\n• **YouTube** : vidéos longues, tutoriels, référencement durable.\n\n✅ La **ligne éditoriale** définit ce que tu publies et comment : tes thématiques, ton ton (sérieux, complice, expert, humoristique), tes formats récurrents et ta charte visuelle. Elle assure la cohérence de ta marque.\n\n💡 Une règle utile : varier les types de posts. On parle souvent des contenus qui **éduquent**, **inspirent**, **divertissent** et **convertissent**. Un fil composé uniquement de posts promotionnels lasse l'audience.\n\n✅ La régularité prime sur le volume : un calendrier tenable et constant vaut mieux qu'un pic d'activité suivi d'un silence.\n\n⚠️ Ne copie pas bêtement un format d'une plateforme à l'autre : une vidéo verticale TikTok ne fonctionne pas telle quelle sur LinkedIn.",
                    'questions' => [
                        [
                            'question' => 'Quel réseau est le plus adapté à une cible professionnelle B2B ?',
                            'options' => ['TikTok', 'LinkedIn', 'Snapchat', 'Pinterest'],
                            'correct' => [1],
                            'explanation' => 'LinkedIn est le réseau de référence pour le B2B, l\'expertise et le recrutement.',
                        ],
                        [
                            'question' => 'Qu\'est-ce qu\'une ligne éditoriale ?',
                            'options' => ['Le budget alloué aux réseaux', 'Ce que tu publies et la façon dont tu le fais (thèmes, ton, formats)', 'Le nombre d\'abonnés', 'Un type de publicité payante'],
                            'correct' => [1],
                            'explanation' => 'La ligne éditoriale définit les thématiques, le ton et les formats pour assurer la cohérence de la marque.',
                        ],
                        [
                            'question' => 'Pourquoi ne faut-il pas être présent sur tous les réseaux à la fois ?',
                            'options' => ['C\'est interdit par les plateformes', 'Pour concentrer ses efforts là où la cible est vraiment active', 'Parce que cela coûte toujours de l\'argent', 'Pour éviter de créer du contenu'],
                            'correct' => [1],
                            'explanation' => 'Mieux vaut concentrer ses efforts sur les plateformes où la cible est présente que de se disperser.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 6 — Le community management',
                    'subtitle' => 'Engagement, modération et ton de marque',
                    'xp_reward' => 160,
                    'content' => "🎯 **Objectif** : animer ta communauté et créer du lien autour de ta marque.\n\n💡 Publier ne suffit pas : le **community management** consiste à faire vivre la relation avec ton audience. Répondre, échanger, animer et modérer transforme de simples abonnés en communauté engagée.\n\n✅ L'**engagement** mesure les interactions : likes, commentaires, partages, messages privés, enregistrements. Un taux d'engagement élevé est souvent plus précieux qu'un grand nombre d'abonnés passifs. Mieux vaut 1 000 abonnés actifs que 100 000 fantômes.\n\n✅ Bonnes pratiques d'animation :\n• répondre rapidement aux commentaires et messages,\n• poser des questions pour inciter à réagir,\n• remercier et mettre en avant ta communauté,\n• créer des rendez-vous récurrents.\n\n💡 Le **ton de marque** (tone of voice) doit rester cohérent partout : c'est la \"personnalité\" de ta marque dans ses échanges. Chaleureux, professionnel, complice... mais toujours reconnaissable.\n\n✅ La **modération** consiste à gérer les commentaires négatifs ou inappropriés. Règle d'or face à un avis négatif légitime : répondre poliment, publiquement et proposer une solution. Supprimer une critique fondée se retourne souvent contre la marque.\n\n⚠️ Ne réponds jamais sous le coup de l'émotion. Une réaction agressive en public peut provoquer un bad buzz.",
                    'questions' => [
                        [
                            'question' => 'Que mesure principalement le taux d\'engagement ?',
                            'options' => ['Le nombre total d\'abonnés', 'Les interactions (likes, commentaires, partages)', 'Le budget publicitaire', 'Le nombre de pages du site'],
                            'correct' => [1],
                            'explanation' => 'L\'engagement mesure les interactions de l\'audience, pas seulement le nombre d\'abonnés.',
                        ],
                        [
                            'question' => 'Face à un commentaire négatif mais légitime, quelle est la meilleure attitude ?',
                            'options' => ['Le supprimer immédiatement', 'Répondre poliment en public et proposer une solution', 'Ignorer définitivement', 'Répondre de façon agressive'],
                            'correct' => [1],
                            'explanation' => 'Répondre poliment et publiquement avec une solution renforce la confiance ; supprimer une critique fondée est risqué.',
                        ],
                        [
                            'question' => 'Pourquoi le ton de marque doit-il rester cohérent ?',
                            'options' => ['Pour économiser du budget', 'Pour que la marque reste reconnaissable et crédible', 'Parce que les algorithmes l\'exigent', 'Pour augmenter le nombre de pages'],
                            'correct' => [1],
                            'explanation' => 'Un ton cohérent donne une personnalité reconnaissable à la marque et renforce la confiance.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 7 — La publicité en ligne',
                    'subtitle' => 'Facebook/Instagram Ads et Google Ads',
                    'xp_reward' => 180,
                    'content' => "🎯 **Objectif** : comprendre comment fonctionne la publicité payante et quand l'utiliser.\n\n💡 La pub en ligne permet d'atteindre rapidement une audience ciblée. Deux grandes familles :\n• Les **réseaux sociaux (Meta Ads : Facebook/Instagram)** : tu cibles selon les centres d'intérêt, l'âge, la localisation, les comportements. Idéal pour faire découvrir un produit à des gens qui ne te cherchent pas encore (\"demande latente\").\n• **Google Ads (SEA)** : tu cibles selon les mots-clés tapés dans le moteur. Idéal pour capter des gens qui cherchent déjà ton offre (\"demande active\").\n\n✅ Le **ciblage** est le cœur de la performance : plus ta pub est montrée aux bonnes personnes, plus elle convertit et moins elle coûte. Un excellent visuel montré à la mauvaise audience ne sert à rien.\n\n✅ Le **budget** se gère par enchères : tu définis un montant quotidien ou total, et la plateforme diffuse selon ta mise. Commence petit, mesure, puis augmente ce qui fonctionne.\n\n💡 La structure classique d'une campagne : un **objectif** (notoriété, trafic, conversion), une **audience** ciblée, un **budget**, et une ou plusieurs **créas** (visuels + textes).\n\n⚠️ Erreur fréquente : juger une campagne trop tôt. Les plateformes ont besoin d'une phase d'apprentissage avant de se stabiliser.",
                    'questions' => [
                        [
                            'question' => 'Quelle plateforme cible principalement selon les mots-clés tapés par l\'utilisateur ?',
                            'options' => ['Instagram Ads', 'Google Ads', 'TikTok Ads', 'LinkedIn Ads'],
                            'correct' => [1],
                            'explanation' => 'Google Ads (SEA) cible la demande active : les internautes qui recherchent déjà un terme précis.',
                        ],
                        [
                            'question' => 'Pourquoi le ciblage est-il crucial en publicité en ligne ?',
                            'options' => ['Parce qu\'il rend la pub gratuite', 'Parce que montrer la pub aux bonnes personnes améliore la conversion et réduit le coût', 'Parce qu\'il remplace le budget', 'Parce qu\'il supprime la concurrence'],
                            'correct' => [1],
                            'explanation' => 'Un bon ciblage augmente la pertinence : meilleure conversion et coût par résultat plus bas.',
                        ],
                        [
                            'question' => 'Quels éléments composent la structure de base d\'une campagne ? (plusieurs réponses)',
                            'options' => ['Un objectif', 'Une audience ciblée', 'Un mot de passe administrateur', 'Un budget et des créas'],
                            'correct' => [0, 1, 3],
                            'explanation' => 'Une campagne se structure autour d\'un objectif, d\'une audience, d\'un budget et de créas (visuels + textes).',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 8 — L\'email marketing',
                    'subtitle' => 'Liste, séquences et taux d\'ouverture',
                    'xp_reward' => 170,
                    'content' => "🎯 **Objectif** : exploiter l'un des canaux les plus rentables du marketing digital.\n\n💡 L'**email marketing** reste l'un des meilleurs retours sur investissement, car ta liste t'**appartient** (owned media) : contrairement aux réseaux sociaux, aucun algorithme ne décide qui voit tes messages.\n\n✅ Tout commence par la **constitution d'une liste**. La bonne méthode : proposer une contrepartie de valeur (guide gratuit, réduction, checklist) en échange de l'email — c'est ce qu'on appelle un **lead magnet**.\n\n⚠️ Règle d'or : l'email marketing repose sur le **consentement** (opt-in). Acheter une liste d'adresses est contre-productif et souvent illégal : tu finis en spam et tu abîmes ta réputation d'expéditeur.\n\n✅ Une **séquence** (ou automation) est une suite d'emails envoyés automatiquement selon un déclencheur. Exemple : une séquence de bienvenue qui accueille un nouvel inscrit sur plusieurs jours, ou un panier abandonné qui relance un acheteur indécis.\n\n💡 Indicateurs clés de l'emailing :\n• **Taux d'ouverture** : part des destinataires qui ouvrent l'email (dépend surtout de l'objet et de l'expéditeur).\n• **Taux de clic** : part de ceux qui cliquent sur un lien.\n• **Taux de désabonnement** : à surveiller pour ne pas lasser.\n\n✅ L'**objet** de l'email est décisif : c'est lui qui décide si l'email est ouvert ou ignoré. Court, clair et donnant envie.",
                    'questions' => [
                        [
                            'question' => 'Pourquoi l\'email marketing offre-t-il souvent un excellent ROI ?',
                            'options' => ['Parce qu\'il est totalement gratuit', 'Parce que la liste t\'appartient et aucun algorithme ne filtre qui voit tes messages', 'Parce qu\'il ne nécessite aucun contenu', 'Parce qu\'il fonctionne sans consentement'],
                            'correct' => [1],
                            'explanation' => 'La liste email est un owned media : tu atteins directement tes abonnés sans dépendre d\'un algorithme.',
                        ],
                        [
                            'question' => 'Qu\'est-ce qu\'un lead magnet ?',
                            'options' => ['Un email automatique de relance', 'Une contrepartie de valeur offerte en échange de l\'email', 'Un type de publicité payante', 'Un indicateur de performance'],
                            'correct' => [1],
                            'explanation' => 'Le lead magnet (guide, réduction, checklist) incite le visiteur à laisser son email.',
                        ],
                        [
                            'question' => 'Le taux d\'ouverture d\'un email dépend surtout de...',
                            'options' => ['La couleur du bouton', 'L\'objet et l\'expéditeur de l\'email', 'Le nombre d\'images', 'La longueur de la liste'],
                            'correct' => [1],
                            'explanation' => 'C\'est l\'objet (et l\'identité de l\'expéditeur) qui décide si l\'email est ouvert ou non.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 9 — Le tunnel de conversion',
                    'subtitle' => 'De la découverte à l\'achat',
                    'xp_reward' => 190,
                    'content' => "🎯 **Objectif** : comprendre le parcours qui mène un inconnu jusqu'à l'achat.\n\n💡 Le **tunnel de conversion** (ou funnel) représente les étapes que traverse un prospect avant de devenir client. Personne n'achète au premier contact : il faut d'abord attirer l'attention, puis convaincre, puis déclencher l'action.\n\n✅ Les trois grandes étapes du tunnel :\n• **Awareness (notoriété)** — haut du tunnel : la personne découvre ton existence ou son problème. Objectif : attirer (contenu, réseaux, pub de notoriété).\n• **Consideration (considération)** — milieu du tunnel : elle compare les solutions et s'intéresse à toi. Objectif : convaincre (témoignages, démonstrations, comparatifs, emails).\n• **Conversion** — bas du tunnel : elle est prête à passer à l'action. Objectif : déclencher l'achat (offre claire, appel à l'action, réassurance, garantie).\n\n💡 On parle de \"tunnel\" car il se **rétrécit** : beaucoup de personnes entrent en haut, peu arrivent en bas. À chaque étape, une partie abandonne. Le but est de réduire ces fuites.\n\n✅ Chaque étape appelle un type de contenu différent. Proposer une offre commerciale agressive à quelqu'un qui vient juste de te découvrir est aussi inefficace que de rester vague avec quelqu'un prêt à acheter.\n\n⚠️ Ne néglige pas l'après-achat : un client satisfait peut devenir ambassadeur et revenir. Le tunnel ne s'arrête pas à la première vente.",
                    'questions' => [
                        [
                            'question' => 'Quel est l\'ordre correct des étapes d\'un tunnel de conversion ?',
                            'options' => ['Conversion → Consideration → Awareness', 'Awareness → Consideration → Conversion', 'Consideration → Awareness → Conversion', 'Conversion → Awareness → Consideration'],
                            'correct' => [1],
                            'explanation' => 'Le parcours va de la notoriété (awareness) à la considération puis à la conversion.',
                        ],
                        [
                            'question' => 'À l\'étape "awareness", quel est l\'objectif principal ?',
                            'options' => ['Déclencher l\'achat immédiatement', 'Attirer l\'attention et faire découvrir la marque', 'Fidéliser un client existant', 'Gérer le service après-vente'],
                            'correct' => [1],
                            'explanation' => 'En haut du tunnel, l\'objectif est d\'attirer et de faire connaître, pas encore de vendre.',
                        ],
                        [
                            'question' => 'Pourquoi parle-t-on de "tunnel" ?',
                            'options' => ['Parce qu\'il est sombre', 'Parce qu\'il se rétrécit : beaucoup entrent, peu arrivent à l\'achat', 'Parce qu\'il est souterrain', 'Parce qu\'il n\'a pas de sortie'],
                            'correct' => [1],
                            'explanation' => 'Le tunnel se rétrécit à chaque étape : une partie des prospects abandonne en chemin.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 10 — Mesurer la performance',
                    'subtitle' => 'Les KPIs : CTR, CPC, ROAS, taux de conversion',
                    'xp_reward' => 200,
                    'content' => "🎯 **Objectif** : parler le langage des chiffres pour piloter ses actions.\n\n💡 Un **KPI** (Key Performance Indicator) est un indicateur clé qui mesure l'atteinte d'un objectif. Sans KPIs, on navigue à l'aveugle : on ne sait pas ce qui marche.\n\n✅ Les indicateurs incontournables :\n• **CTR** (Click-Through Rate / taux de clic) = clics ÷ impressions. Mesure l'attractivité d'une pub ou d'un email. Un CTR faible signale un visuel ou un message peu engageant.\n• **CPC** (Cost Per Click / coût par clic) = budget dépensé ÷ nombre de clics. Combien te coûte chaque visiteur.\n• **Taux de conversion** = conversions ÷ visiteurs. La part de visiteurs qui réalisent l'action voulue (achat, inscription).\n• **ROAS** (Return On Ad Spend) = chiffre d'affaires généré ÷ budget pub. Un ROAS de 4 signifie que 1 € investi rapporte 4 € de ventes.\n\n💡 À distinguer : le **CPC** mesure le coût d'un clic, tandis que le **CPA** (Cost Per Acquisition) mesure le coût d'un client réellement acquis. Une pub peut avoir un CPC bas mais un CPA élevé si les clics ne convertissent pas.\n\n✅ Règle de pilotage : relie toujours un KPI à un objectif. Un \"like\" fait plaisir, mais s'il ne mène ni à un clic ni à une vente, ce n'est pas un KPI prioritaire.\n\n⚠️ Méfie-toi des \"vanity metrics\" (métriques de vanité) comme le nombre d'abonnés brut : flatteuses mais souvent déconnectées du chiffre d'affaires.",
                    'questions' => [
                        [
                            'question' => 'Comment se calcule le CTR (taux de clic) ?',
                            'options' => ['Clics ÷ impressions', 'Budget ÷ clics', 'Chiffre d\'affaires ÷ budget', 'Conversions ÷ abonnés'],
                            'correct' => [0],
                            'explanation' => 'Le CTR = clics ÷ impressions ; il mesure l\'attractivité d\'une annonce.',
                        ],
                        [
                            'question' => 'Que signifie un ROAS de 4 ?',
                            'options' => ['Tu as 4 abonnés', '1 € investi en pub rapporte 4 € de ventes', 'Le CPC est de 4 €', 'Tu as fait 4 campagnes'],
                            'correct' => [1],
                            'explanation' => 'Le ROAS (Return On Ad Spend) de 4 signifie 4 € de chiffre d\'affaires par euro dépensé.',
                        ],
                        [
                            'question' => 'Lesquelles de ces métriques sont liées au coût et à la rentabilité ? (plusieurs réponses)',
                            'options' => ['CPC (coût par clic)', 'CTR (taux de clic)', 'ROAS (retour sur dépense pub)', 'CPA (coût par acquisition)'],
                            'correct' => [0, 2, 3],
                            'explanation' => 'CPC, CPA et ROAS concernent le coût et la rentabilité. Le CTR mesure l\'attractivité, pas le coût.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 11 — Analytics et A/B testing',
                    'subtitle' => 'Mesurer pour optimiser',
                    'xp_reward' => 210,
                    'content' => "🎯 **Objectif** : utiliser la donnée pour améliorer continuellement tes résultats.\n\n💡 Les outils d'**analytics** (comme Google Analytics) collectent les données de comportement sur ton site : d'où viennent les visiteurs, quelles pages ils consultent, combien de temps ils restent, et où ils abandonnent.\n\n✅ Quelques notions clés à connaître :\n• **Sessions et utilisateurs** : combien de visites et combien de personnes distinctes.\n• **Sources de trafic** : organique (SEO), direct, social, payant, referral. Savoir d'où viennent tes visiteurs te dit quel canal investir.\n• **Taux de rebond** : part des visiteurs qui repartent sans interagir. Un taux élevé sur une page d'atterrissage peut signaler un problème.\n• **Taux de conversion par canal** : pour comparer la rentabilité réelle de chaque source.\n\n💡 L'**A/B testing** consiste à comparer deux versions (A et B) d'un même élément — un titre, un bouton, un visuel, un objet d'email — auprès d'audiences équivalentes, pour garder celle qui performe le mieux. On ne se fie pas à son intuition : on **teste**.\n\n⚠️ Règle essentielle de l'A/B testing : ne tester **qu'une seule variable à la fois**. Si tu changes le titre ET le bouton ET l'image en même temps, tu ne sauras pas lequel a fait la différence.\n\n✅ Démarche d'optimisation : mesurer → identifier un point faible → formuler une hypothèse → tester → conserver le gagnant → recommencer. C'est un cycle continu, jamais terminé.",
                    'questions' => [
                        [
                            'question' => 'À quoi sert un outil d\'analytics comme Google Analytics ?',
                            'options' => ['À envoyer des emails', 'À analyser le comportement des visiteurs sur ton site', 'À créer des visuels publicitaires', 'À acheter de la publicité'],
                            'correct' => [1],
                            'explanation' => 'L\'analytics collecte et analyse les données de comportement des visiteurs (sources, pages, abandons).',
                        ],
                        [
                            'question' => 'Quelle est la règle fondamentale d\'un A/B test rigoureux ?',
                            'options' => ['Changer plusieurs éléments à la fois pour aller plus vite', 'Ne tester qu\'une seule variable à la fois', 'Toujours choisir la version A', 'Ne jamais mesurer les résultats'],
                            'correct' => [1],
                            'explanation' => 'En ne changeant qu\'une variable, on sait précisément quel élément a causé la différence de performance.',
                        ],
                        [
                            'question' => 'Que mesure le taux de rebond ?',
                            'options' => ['Le nombre de ventes', 'La part de visiteurs qui repartent sans interagir', 'Le coût par clic', 'Le retour sur investissement publicitaire'],
                            'correct' => [1],
                            'explanation' => 'Le taux de rebond mesure la proportion de visiteurs qui quittent le site sans interaction.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 12 — Stratégie et budget',
                    'subtitle' => 'Répartir un budget et choisir ses priorités',
                    'xp_reward' => 250,
                    'content' => "🎯 **Objectif** : assembler toutes les briques en une stratégie cohérente et un budget maîtrisé.\n\n💡 Une stratégie marketing ne se résume pas à \"faire des posts\". Elle part d'un **objectif clair et mesurable** (ex. gagner 100 nouveaux clients en 3 mois), puis décline les canaux, les actions, le budget et les KPIs pour l'atteindre.\n\n✅ Une méthode simple pour fixer de bons objectifs : les critères **SMART** — Spécifique, Mesurable, Atteignable, Réaliste, Temporellement défini. \"Avoir plus de clients\" est flou ; \"+100 clients via Instagram et Google Ads d'ici fin trimestre\" est SMART.\n\n✅ Répartir un **budget** demande des arbitrages. Quelques principes :\n• Investir en priorité sur les canaux qui ont déjà prouvé leur rentabilité (meilleur ROAS).\n• Garder une part pour **tester** de nouveaux canaux (souvent autour de 10-20 %).\n• Distinguer le **court terme** (la pub paie vite mais s'arrête dès qu'on coupe le budget) du **long terme** (SEO et contenu sont lents mais cumulatifs et durables).\n\n💡 Combiner les temporalités est la clé : la pub apporte des résultats immédiats pendant que le SEO et le contenu construisent un actif durable. S'appuyer uniquement sur la pub, c'est louer son trafic ; construire du contenu et du SEO, c'est posséder un actif.\n\n✅ Enfin, une stratégie n'est jamais figée : on mesure, on ajuste, on réinvestit ce qui fonctionne et on coupe ce qui ne marche pas.\n\n🏆 Bravo ! Tu maîtrises désormais les fondamentaux du marketing digital, du persona jusqu'à la stratégie budgétaire. La suite, c'est la pratique : lance, mesure, ajuste, recommence.",
                    'questions' => [
                        [
                            'question' => 'Que signifie l\'acronyme SMART pour un objectif ?',
                            'options' => ['Social, Mobile, Ads, Reach, Traffic', 'Spécifique, Mesurable, Atteignable, Réaliste, Temporellement défini', 'Simple, Marketing, Action, Résultat, Test', 'Stratégie, Média, Audience, ROI, Temps'],
                            'correct' => [1],
                            'explanation' => 'SMART = Spécifique, Mesurable, Atteignable, Réaliste, Temporellement défini.',
                        ],
                        [
                            'question' => 'Pourquoi combiner publicité et SEO/contenu dans un budget ?',
                            'options' => ['Parce que c\'est obligatoire légalement', 'Pour avoir des résultats immédiats (pub) tout en construisant un actif durable (SEO/contenu)', 'Parce que le SEO est gratuit et instantané', 'Pour dépenser tout le budget rapidement'],
                            'correct' => [1],
                            'explanation' => 'La pub apporte des résultats rapides mais éphémères ; le SEO et le contenu sont lents mais cumulatifs et durables.',
                        ],
                        [
                            'question' => 'Quels principes guident une bonne répartition de budget ? (plusieurs réponses)',
                            'options' => ['Investir d\'abord sur les canaux les plus rentables (meilleur ROAS)', 'Garder une part pour tester de nouveaux canaux', 'Tout miser sur un seul canal sans mesurer', 'Distinguer actions de court terme et de long terme'],
                            'correct' => [0, 1, 3],
                            'explanation' => 'On priorise les canaux rentables, on réserve une part au test et on équilibre court et long terme. Tout miser sans mesurer est risqué.',
                        ],
                    ],
                ],
            ],
        ]);

        $this->command->info('  ✓ Roadmap Marketing Digital créée (12 niveaux).');
    }
}
