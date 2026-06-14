<?php

namespace Database\Seeders\Roadmaps;

use Illuminate\Database\Seeder;

/**
 * Roadmap E-commerce — lancer et gérer un business en ligne, du choix du produit
 * jusqu'à la croissance. Adapté au contexte africain/camerounais (Mobile Money,
 * livraison locale). Contenu rédigé (tips), QCM de validation par niveau.
 */
class EcommerceRoadmapSeeder extends Seeder
{
    use RoadmapSeederHelper;

    public function run(): void
    {
        $this->createRoadmap([
            'title' => 'Lance ton business E-commerce',
            'slug' => 'lancer-et-gerer-un-business-e-commerce',
            'domain' => 'ecommerce',
            'description' => "Apprends à créer une boutique en ligne rentable, du choix du produit jusqu'à la fidélisation des clients. Sourcing, paiement Mobile Money, livraison locale, acquisition de trafic et analyse des performances : tout pour vendre en ligne dans le contexte africain.",
            'objectives' => "Comprendre les modèles de l'e-commerce\nChoisir un produit et une niche rentables\nTrouver des fournisseurs fiables\nCréer et configurer sa boutique en ligne\nAccepter les paiements (Mobile Money, cartes, à la livraison)\nOrganiser la logistique et la livraison\nAttirer du trafic et convertir les visiteurs\nFidéliser ses clients et faire grandir son chiffre d'affaires",
            'icon' => '🛒',
            'color' => '#06B6D4',
            'difficulty' => 'beginner',
            'required_packs' => [],
            'pass_threshold' => 70,
            'order' => 1,
            'levels' => [
                [
                    'title' => 'Niveau 1 — Comprendre l\'e-commerce',
                    'subtitle' => 'Les modèles de vente en ligne',
                    'xp_reward' => 100,
                    'content' => "🎯 **Objectif** : comprendre ce qu'est l'e-commerce et ses principaux modèles.\n\n💡 L'**e-commerce**, c'est tout simplement vendre des produits ou des services sur internet. À la place d'une boutique physique, tu as une vitrine en ligne accessible 24h/24, sans loyer de magasin.\n\n✅ Les modèles les plus courants :\n• **B2C** (Business to Consumer) : tu vends directement au consommateur final. C'est le plus répandu (ex. une boutique de vêtements en ligne).\n• **B2B** (Business to Business) : tu vends à d'autres entreprises (ex. fournitures de bureau en gros).\n• **Marketplace** : une plateforme où plusieurs vendeurs proposent leurs produits (ex. Jumia). Tu peux être vendeur sur une marketplace ou en créer une.\n\n✅ Le **dropshipping** est un modèle particulier : tu vends un produit que tu ne stockes pas. Quand un client commande, ton fournisseur expédie directement. Avantage : peu d'investissement de départ. Inconvénient : marges plus faibles et moins de contrôle sur la qualité et les délais.\n\n💡 En Afrique, le commerce social (vente via WhatsApp, Facebook, Instagram) est aussi très puissant : beaucoup d'entrepreneurs démarrent là avant d'ouvrir une vraie boutique en ligne.",
                    'questions' => [
                        [
                            'question' => 'Que signifie le modèle B2C ?',
                            'options' => ['Business to Cash', 'Business to Consumer (vente au consommateur final)', 'Buy to Customer', 'Business to Company'],
                            'correct' => [1],
                            'explanation' => 'B2C = Business to Consumer : tu vends directement au consommateur final.',
                        ],
                        [
                            'question' => 'Quelle est la caractéristique principale du dropshipping ?',
                            'options' => ['Tu fabriques toi-même les produits', 'Tu vends des produits que tu ne stockes pas, expédiés par le fournisseur', 'Tu vends uniquement à des entreprises', 'Tu loues une boutique physique'],
                            'correct' => [1],
                            'explanation' => 'En dropshipping, le fournisseur expédie directement au client : pas de stock à gérer.',
                        ],
                        [
                            'question' => 'Lesquels sont des modèles e-commerce valables ? (plusieurs réponses)',
                            'options' => ['Marketplace', 'B2B', 'Full table scan', 'Dropshipping'],
                            'correct' => [0, 1, 3],
                            'explanation' => 'Marketplace, B2B et dropshipping sont des modèles e-commerce. "Full table scan" est un terme de base de données.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 2 — Choisir son produit et sa niche',
                    'subtitle' => 'Vendre ce que les gens veulent',
                    'xp_reward' => 120,
                    'content' => "🎯 **Objectif** : choisir un produit qui se vend et une niche rentable.\n\n💡 La pire erreur du débutant est de vendre un produit qu'il aime mais que personne ne cherche. La règle d'or : **vends ce que les gens veulent déjà acheter**, pas ce que tu espères leur vendre.\n\n✅ Une **niche** est un segment de marché précis (ex. au lieu de \"vêtements\", choisis \"vêtements traditionnels africains pour mariages\"). Une niche bien ciblée a moins de concurrence et des clients plus fidèles.\n\n✅ Comment valider la demande (étude de marché) :\n• Regarde ce qui se vend déjà sur Jumia, AliExpress, Amazon.\n• Utilise les recherches Google et Google Trends pour voir si les gens cherchent ce produit.\n• Observe les groupes WhatsApp/Facebook : quels produits font le buzz ?\n• Demande directement autour de toi : seraient-ils prêts à payer ?\n\n✅ Un bon produit e-commerce coche souvent ces cases :\n• Résout un **problème** ou crée un **désir** fort.\n• A une **marge** suffisante (idéalement, vendre au moins 2,5× le coût d'achat).\n• N'est pas trop lourd/fragile (livraison plus simple et moins chère).\n• N'est pas déjà vendu partout au même prix.\n\n⚠️ Méfie-toi des produits trop saisonniers (ex. uniquement à Noël) si tu veux des revenus réguliers toute l'année.",
                    'questions' => [
                        [
                            'question' => 'Pourquoi cibler une niche précise plutôt qu\'un marché très large ?',
                            'options' => ['Pour payer plus d\'impôts', 'Moins de concurrence et des clients plus ciblés et fidèles', 'Parce que c\'est obligatoire légalement', 'Pour vendre plus cher sans raison'],
                            'correct' => [1],
                            'explanation' => 'Une niche bien choisie réduit la concurrence et attire des clients plus engagés.',
                        ],
                        [
                            'question' => 'Quel outil aide à vérifier si les gens recherchent un produit ?',
                            'options' => ['Google Trends', 'Un tableur vide', 'Le calendrier', 'La calculatrice'],
                            'correct' => [0],
                            'explanation' => 'Google Trends montre l\'évolution de l\'intérêt pour un terme de recherche.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 3 — Sourcing et fournisseurs',
                    'subtitle' => 'Trouver et négocier ses produits',
                    'xp_reward' => 130,
                    'content' => "🎯 **Objectif** : trouver des fournisseurs fiables et négocier de bons prix.\n\n💡 Le **sourcing**, c'est l'art de trouver où acheter tes produits au meilleur rapport qualité/prix. Ta marge dépend directement de ton prix d'achat.\n\n✅ Où trouver des fournisseurs :\n• **En ligne** : AliExpress, Alibaba (gros volumes), 1688 pour l'import depuis la Chine.\n• **Localement** : marchés de gros (ex. marchés de Douala, Yaoundé), grossistes, artisans et producteurs locaux.\n• Le **local** réduit les délais et les frais de douane, et permet de vendre \"made in Cameroun\".\n\n✅ Bien négocier :\n• Demande toujours le **prix de gros** et le minimum de commande (MOQ).\n• Commande un **échantillon** avant de commander en quantité : tu vérifies la qualité réelle.\n• Négocie sur le volume, pas seulement sur le prix unitaire.\n• Compare au moins 3 fournisseurs avant de t'engager.\n\n✅ Vérifier la qualité et la fiabilité :\n• Demande des photos/vidéos réelles, pas seulement les photos du catalogue.\n• Vérifie les avis et l'ancienneté du fournisseur.\n• Commence petit, puis augmente les quantités une fois la confiance établie.\n\n⚠️ Ne mets jamais tout ton budget chez un fournisseur inconnu dès la première commande. Teste d'abord.",
                    'questions' => [
                        [
                            'question' => 'Que désigne le sigle MOQ chez un fournisseur ?',
                            'options' => ['La marge opérationnelle', 'Le minimum de commande (Minimum Order Quantity)', 'Le mode de paiement', 'La méthode de livraison'],
                            'correct' => [1],
                            'explanation' => 'Le MOQ est la quantité minimale que le fournisseur exige par commande.',
                        ],
                        [
                            'question' => 'Pourquoi commander un échantillon avant une grosse commande ?',
                            'options' => ['Pour vérifier la qualité réelle du produit', 'Parce que c\'est gratuit', 'Pour augmenter le prix', 'Cela n\'a aucun intérêt'],
                            'correct' => [0],
                            'explanation' => 'L\'échantillon permet de contrôler la qualité avant d\'investir dans le volume.',
                        ],
                        [
                            'question' => 'Quels sont des avantages d\'un fournisseur local ? (plusieurs réponses)',
                            'options' => ['Délais de livraison plus courts', 'Moins de frais de douane', 'Impossible de négocier', 'Possibilité de vendre "made in local"'],
                            'correct' => [0, 1, 3],
                            'explanation' => 'Le local réduit délais et douane et valorise le "made in". On peut tout à fait négocier en local.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 4 — Créer sa boutique',
                    'subtitle' => 'Plateformes et nom de domaine',
                    'xp_reward' => 140,
                    'content' => "🎯 **Objectif** : mettre en ligne ta boutique avec les bons outils.\n\n💡 Tu n'as pas besoin de savoir coder pour créer une boutique en ligne aujourd'hui. Plusieurs plateformes font le travail technique à ta place.\n\n✅ Les plateformes les plus connues :\n• **Shopify** : tout-en-un, simple, payant par abonnement mensuel. Idéal pour démarrer vite.\n• **WooCommerce** : extension gratuite de WordPress, très flexible, mais demande un peu plus de configuration (hébergement à gérer soi-même).\n• Des solutions locales et le **commerce via WhatsApp/Instagram** restent valables pour tester avant d'investir.\n\n✅ Le **nom de domaine** est ton adresse sur internet (ex. `maboutique.com`). Conseils :\n• Cours, facile à retenir et à épeler.\n• Évite les tirets et chiffres compliqués.\n• Préfère un `.com` si possible, ou un `.cm` pour ancrer ta marque localement.\n\n✅ Éléments indispensables d'une boutique qui inspire confiance :\n• Un **logo** simple et une identité visuelle cohérente.\n• Des **pages claires** : accueil, produits, à propos, contact.\n• Les **conditions** : livraison, retours, mentions de contact (téléphone/WhatsApp).\n• Un site qui s'affiche bien sur **mobile** : en Afrique, la majorité des clients achètent depuis leur téléphone.\n\n💡 Commence simple. Tu pourras toujours améliorer le design plus tard. Mieux vaut une boutique en ligne aujourd'hui qu'une boutique \"parfaite\" jamais lancée.",
                    'questions' => [
                        [
                            'question' => 'Quelle plateforme est une extension gratuite de WordPress ?',
                            'options' => ['Shopify', 'WooCommerce', 'AliExpress', 'Google Trends'],
                            'correct' => [1],
                            'explanation' => 'WooCommerce est un plugin e-commerce gratuit pour WordPress.',
                        ],
                        [
                            'question' => 'Qu\'est-ce qu\'un nom de domaine ?',
                            'options' => ['Le mot de passe administrateur', 'L\'adresse de ton site sur internet (ex. maboutique.com)', 'Le nom de ton fournisseur', 'Le numéro de TVA'],
                            'correct' => [1],
                            'explanation' => 'Le nom de domaine est l\'adresse web par laquelle on accède à ta boutique.',
                        ],
                        [
                            'question' => 'Pourquoi soigner l\'affichage mobile de sa boutique en Afrique ?',
                            'options' => ['C\'est interdit autrement', 'La majorité des clients achètent depuis leur téléphone', 'Le mobile coûte moins cher à héberger', 'Cela n\'a pas d\'importance'],
                            'correct' => [1],
                            'explanation' => 'En Afrique, l\'essentiel du trafic et des achats se fait sur mobile.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 5 — Des fiches produits qui vendent',
                    'subtitle' => 'Photos, descriptions et prix',
                    'xp_reward' => 150,
                    'content' => "🎯 **Objectif** : transformer un visiteur en acheteur grâce à des fiches produits convaincantes.\n\n💡 En ligne, le client ne peut ni toucher ni essayer le produit. Ta fiche produit doit donc **rassurer** et **donner envie** à sa place.\n\n✅ Les **photos** sont l'élément le plus important :\n• Plusieurs angles, fond clair et net, bonne lumière (la lumière naturelle suffit souvent).\n• Montre le produit en situation d'usage (porté, utilisé).\n• Évite les photos floues ou volées au catalogue d'un concurrent.\n\n✅ La **description** doit vendre, pas seulement décrire :\n• Mets en avant les **bénéfices** (\"garde tes boissons fraîches 12h\") avant les caractéristiques (\"acier inoxydable\").\n• Réponds aux questions fréquentes : taille, matière, entretien, garantie.\n• Structure avec des listes à puces : c'est plus lisible sur mobile.\n\n✅ Le **prix** : trouve l'équilibre entre marge et attractivité.\n• Calcule ta marge : prix de vente − (coût d'achat + livraison + frais de plateforme).\n• Les prix se terminant par 9 (ex. 9 900 FCFA) sont souvent perçus comme plus accessibles.\n• Affiche clairement les frais de livraison ou propose la livraison incluse.\n\n💡 Ajoute des **avis clients** dès que possible : la preuve sociale rassure énormément et augmente les ventes.",
                    'questions' => [
                        [
                            'question' => 'Quel est l\'élément le plus déterminant d\'une fiche produit en ligne ?',
                            'options' => ['La couleur du bouton', 'Des photos de qualité', 'Le numéro de série', 'La date de création de la page'],
                            'correct' => [1],
                            'explanation' => 'Le client ne pouvant pas toucher le produit, des photos nettes et variées sont décisives.',
                        ],
                        [
                            'question' => 'Une bonne description produit doit surtout mettre en avant...',
                            'options' => ['Les bénéfices pour le client', 'Uniquement le poids exact en grammes', 'Le nom du fournisseur', 'Le code-barres'],
                            'correct' => [0],
                            'explanation' => 'On vend des bénéfices ("garde au frais 12h") plutôt que de simples caractéristiques techniques.',
                        ],
                        [
                            'question' => 'Pourquoi afficher des avis clients sur une fiche produit ?',
                            'options' => ['Pour remplir la page', 'La preuve sociale rassure et augmente les ventes', 'C\'est obligatoire par la loi', 'Pour ralentir le site'],
                            'correct' => [1],
                            'explanation' => 'Les avis sont une preuve sociale qui rassure les acheteurs hésitants.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 6 — Les moyens de paiement',
                    'subtitle' => 'Mobile Money, cartes et paiement à la livraison',
                    'xp_reward' => 160,
                    'content' => "🎯 **Objectif** : permettre à tes clients de payer facilement, avec les moyens qu'ils utilisent vraiment.\n\n💡 Un client prêt à acheter qui ne trouve pas son moyen de paiement préféré… abandonne. Proposer les bons moyens de paiement est crucial, surtout en Afrique.\n\n✅ Les principaux moyens de paiement au Cameroun et en Afrique :\n• **Mobile Money** (MTN MoMo, Orange Money) : de loin le plus utilisé. Indispensable. Des agrégateurs comme des passerelles de paiement permettent de l'intégrer à ta boutique.\n• **Paiement à la livraison** (cash on delivery) : le client paie quand il reçoit le colis. Très rassurant pour les clients qui ne font pas encore confiance au paiement en ligne.\n• **Cartes bancaires** (Visa, Mastercard) : utiles pour les clients à l'international ou la diaspora.\n\n✅ Bonnes pratiques :\n• Propose **au moins** Mobile Money + paiement à la livraison : tu couvres déjà la majorité des clients.\n• Affiche les moyens de paiement acceptés dès la page produit : ça rassure avant même le panier.\n• Confirme chaque commande par SMS ou WhatsApp.\n\n⚠️ Le paiement à la livraison comporte un risque : le client peut refuser le colis à la réception. Confirme la commande par appel/WhatsApp avant d'expédier pour limiter les colis non payés.\n\n💡 La sécurité compte : utilise des passerelles de paiement reconnues et ne stocke jamais toi-même les codes secrets ou numéros de carte de tes clients.",
                    'questions' => [
                        [
                            'question' => 'Quel moyen de paiement est le plus utilisé en Afrique de l\'Ouest et centrale ?',
                            'options' => ['Le chèque', 'Le Mobile Money (MTN MoMo, Orange Money)', 'Le virement SWIFT', 'Le mandat postal'],
                            'correct' => [1],
                            'explanation' => 'Le Mobile Money est le moyen de paiement dominant ; il est incontournable.',
                        ],
                        [
                            'question' => 'Quel est le principal risque du paiement à la livraison pour le vendeur ?',
                            'options' => ['Le client peut refuser le colis à la réception', 'Le client paie deux fois', 'C\'est interdit', 'Le colis devient gratuit'],
                            'correct' => [0],
                            'explanation' => 'En cash on delivery, le client peut refuser le colis : d\'où l\'intérêt de confirmer avant d\'expédier.',
                        ],
                        [
                            'question' => 'Quels moyens proposer au minimum pour couvrir la majorité des clients ? (plusieurs réponses)',
                            'options' => ['Mobile Money', 'Paiement à la livraison', 'Uniquement le troc', 'Uniquement les cryptomonnaies'],
                            'correct' => [0, 1],
                            'explanation' => 'Mobile Money + paiement à la livraison couvrent déjà l\'essentiel des clients africains.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 7 — Logistique et livraison',
                    'subtitle' => 'Du stock au dernier kilomètre',
                    'xp_reward' => 170,
                    'content' => "🎯 **Objectif** : organiser le stock et la livraison pour que le client reçoive son colis vite et en bon état.\n\n💡 Une vente n'est terminée que lorsque le client a reçu son produit, satisfait. La logistique est souvent ce qui fait ou défait la réputation d'une boutique.\n\n✅ Gérer le **stock** :\n• Suis tes quantités pour ne jamais vendre un produit en rupture (rien de pire qu'annuler une commande payée).\n• En dropshipping, le stock est géré par le fournisseur, mais vérifie sa disponibilité.\n• Garde un petit stock de sécurité sur tes produits qui partent vite.\n\n✅ L'**expédition** :\n• Emballe correctement : un colis bien protégé arrive intact et donne une image pro.\n• Choisis tes transporteurs : services de colis, agences de transport interurbain, ou livreurs à moto en ville.\n• Communique un **délai réaliste** et tiens-le. Mieux vaut promettre 3 jours et livrer en 2 que l'inverse.\n\n✅ Le **dernier kilomètre** (la livraison finale jusqu'au client) est souvent le plus difficile et le plus coûteux en ville :\n• Les livreurs à moto sont rapides et flexibles pour les zones urbaines.\n• Demande un point de repère clair (les adresses précises manquent souvent) et le numéro WhatsApp du client.\n• Propose éventuellement un point de retrait pour réduire les coûts.\n\n⚠️ Calcule bien tes frais de livraison : une livraison sous-évaluée mange ta marge. Intègre-la dans ton prix ou facture-la clairement.",
                    'questions' => [
                        [
                            'question' => 'Que désigne le "dernier kilomètre" en logistique ?',
                            'options' => ['La distance entre deux entrepôts', 'La livraison finale jusqu\'au client', 'La dernière commande de l\'année', 'Le retour d\'un colis'],
                            'correct' => [1],
                            'explanation' => 'Le dernier kilomètre est l\'étape finale de livraison jusqu\'au client, souvent la plus coûteuse.',
                        ],
                        [
                            'question' => 'Pourquoi suivre son stock avec rigueur ?',
                            'options' => ['Pour éviter de vendre un produit en rupture', 'Pour payer moins d\'impôts', 'Pour décorer la boutique', 'Cela ne sert à rien'],
                            'correct' => [0],
                            'explanation' => 'Un bon suivi du stock évite d\'annuler des commandes déjà payées, ce qui ruine la confiance.',
                        ],
                        [
                            'question' => 'Quelle bonne pratique concernant les délais de livraison ?',
                            'options' => ['Promettre très court pour vendre puis livrer en retard', 'Annoncer un délai réaliste et le tenir', 'Ne jamais communiquer de délai', 'Promettre une livraison instantanée'],
                            'correct' => [1],
                            'explanation' => 'Annoncer un délai réaliste et le respecter (voire le dépasser) bâtit la confiance.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 8 — Acquisition de trafic',
                    'subtitle' => 'SEO, réseaux sociaux et publicités',
                    'xp_reward' => 180,
                    'content' => "🎯 **Objectif** : attirer des visiteurs qualifiés vers ta boutique.\n\n💡 Une boutique sans visiteurs ne vend rien. L'**acquisition de trafic** consiste à faire venir des clients potentiels, par plusieurs canaux complémentaires.\n\n✅ Le **SEO** (référencement naturel) : être trouvé sur Google sans payer.\n• Utilise dans tes titres et descriptions les mots que tes clients recherchent.\n• Rédige des fiches produits riches et un blog utile (ex. \"comment choisir…\").\n• C'est gratuit mais long : les résultats arrivent en plusieurs semaines/mois.\n\n✅ Les **réseaux sociaux** : très puissants en Afrique.\n• Facebook, Instagram, TikTok et WhatsApp Business sont d'excellents canaux.\n• Publie régulièrement : photos, vidéos courtes, témoignages, coulisses.\n• Le contenu qui montre le produit en usage marche le mieux.\n\n✅ La **publicité payante** (Facebook/Instagram Ads, Google Ads) :\n• Permet d'atteindre vite une audience ciblée (par ville, âge, intérêts).\n• Commence avec un petit budget de test, mesure, puis augmente ce qui marche.\n• Cible précisément : mieux vaut 1 000 personnes intéressées que 100 000 indifférentes.\n\n💡 Ne mets pas tous tes œufs dans le même panier : combine gratuit (SEO, social) et payant (ads). Et mesure toujours d'où viennent tes ventes pour investir dans ce qui rapporte.",
                    'questions' => [
                        [
                            'question' => 'Que permet le SEO (référencement naturel) ?',
                            'options' => ['Payer pour être premier sur Google', 'Être trouvé gratuitement sur les moteurs de recherche', 'Livrer plus vite', 'Encaisser les paiements'],
                            'correct' => [1],
                            'explanation' => 'Le SEO vise à apparaître dans les résultats de recherche sans payer la publicité.',
                        ],
                        [
                            'question' => 'Quel est un inconvénient du SEO par rapport à la publicité payante ?',
                            'options' => ['Il coûte très cher', 'Les résultats prennent du temps (semaines/mois)', 'Il est illégal', 'Il ne marche que la nuit'],
                            'correct' => [1],
                            'explanation' => 'Le SEO est gratuit mais lent : les effets se voient sur plusieurs semaines ou mois.',
                        ],
                        [
                            'question' => 'Bonne pratique pour démarrer la publicité payante ?',
                            'options' => ['Dépenser tout son budget d\'un coup', 'Tester avec un petit budget, mesurer, puis augmenter ce qui marche', 'Cibler tout le monde sans distinction', 'Ne jamais mesurer les résultats'],
                            'correct' => [1],
                            'explanation' => 'On teste petit, on mesure les résultats, puis on réinvestit sur ce qui convertit.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 9 — Conversion et panier',
                    'subtitle' => 'Réduire l\'abandon, augmenter les ventes',
                    'xp_reward' => 190,
                    'content' => "🎯 **Objectif** : transformer un maximum de visiteurs en acheteurs.\n\n💡 La **conversion**, c'est le pourcentage de visiteurs qui achètent réellement. Attirer du trafic coûte cher ; il faut donc que ce trafic convertisse. Optimiser ton **tunnel d'achat** (parcours du visiteur jusqu'au paiement) peut doubler tes ventes sans un seul visiteur de plus.\n\n✅ Le **tunnel d'achat** typique : page produit → ajout au panier → informations de livraison → paiement → confirmation. À chaque étape, des clients abandonnent.\n\n✅ L'**abandon de panier** est très fréquent (souvent plus de la moitié des paniers). Causes courantes :\n• Frais de livraison surprises au dernier moment.\n• Trop d'étapes ou un formulaire trop long.\n• Manque de confiance (pas de contact visible, pas d'avis).\n• Moyen de paiement souhaité indisponible.\n\n✅ Comment améliorer la conversion :\n• Affiche les **frais de livraison tôt**, pas à la dernière seconde.\n• Simplifie le **paiement** : le moins d'étapes et de champs possible.\n• Rassure : avis clients, numéro WhatsApp, politique de retour claire.\n• Ajoute un sentiment d'**urgence** honnête (\"stock limité\") et des boutons d'action clairs.\n• Relance les paniers abandonnés par WhatsApp ou email : \"Votre commande vous attend\".\n\n💡 Teste une chose à la fois (A/B testing) : change un élément, mesure l'effet sur les ventes, garde ce qui marche.",
                    'questions' => [
                        [
                            'question' => 'Que mesure le taux de conversion ?',
                            'options' => ['Le nombre de visiteurs', 'Le pourcentage de visiteurs qui achètent réellement', 'Le coût de la livraison', 'Le nombre de produits en stock'],
                            'correct' => [1],
                            'explanation' => 'Le taux de conversion = part des visiteurs qui passent commande.',
                        ],
                        [
                            'question' => 'Quelle est une cause fréquente d\'abandon de panier ?',
                            'options' => ['Des frais de livraison surprises au dernier moment', 'Un site trop rapide', 'Trop d\'avis positifs', 'Un prix trop bas'],
                            'correct' => [0],
                            'explanation' => 'Les frais cachés découverts au dernier moment font fuir beaucoup d\'acheteurs.',
                        ],
                        [
                            'question' => 'Quelles actions aident à réduire l\'abandon de panier ? (plusieurs réponses)',
                            'options' => ['Simplifier le processus de paiement', 'Afficher les frais de livraison dès le début', 'Multiplier les étapes et les champs', 'Relancer les paniers abandonnés par WhatsApp'],
                            'correct' => [0, 1, 3],
                            'explanation' => 'Simplifier, être transparent sur les frais et relancer aident ; multiplier les étapes nuit à la conversion.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 10 — Service client et fidélisation',
                    'subtitle' => 'SAV, avis et retours',
                    'xp_reward' => 200,
                    'content' => "🎯 **Objectif** : transformer un acheteur en client fidèle qui revient et te recommande.\n\n💡 Acquérir un nouveau client coûte bien plus cher que d'en garder un. Un client satisfait rachète et parle de toi : c'est de la publicité gratuite. La fidélisation est un moteur de croissance souvent sous-estimé.\n\n✅ Un bon **service client (SAV)** :\n• Réponds **vite**, surtout sur WhatsApp où les clients attendent une réaction rapide.\n• Sois poli et solutionne le problème, même en cas d'erreur de ta part.\n• Confirme chaque commande et tiens le client informé de l'avancée de la livraison.\n\n✅ Les **avis clients** :\n• Demande un avis après chaque achat satisfait (par WhatsApp/email).\n• Affiche les avis sur tes fiches : ils rassurent les futurs acheteurs.\n• Réponds aussi aux avis négatifs avec calme : ta réponse montre ton sérieux aux autres clients.\n\n✅ La gestion des **retours** :\n• Une politique de retour claire et juste rassure avant l'achat.\n• Un retour bien géré peut transformer un client mécontent en client fidèle.\n• Note les motifs de retour : ils révèlent des problèmes produit à corriger.\n\n✅ **Fidéliser** :\n• Offre un petit avantage aux clients récurrents (réduction, cadeau, programme de points).\n• Reste en contact (nouveautés, promos) sans spammer.\n• Crée une communauté autour de ta marque (groupe WhatsApp, page active).\n\n💡 Le bouche-à-oreille est très puissant en Afrique : un client ravi t'en amène souvent plusieurs autres.",
                    'questions' => [
                        [
                            'question' => 'Pourquoi la fidélisation est-elle si importante ?',
                            'options' => ['Garder un client coûte moins cher que d\'en acquérir un nouveau', 'C\'est obligatoire par la loi', 'Cela augmente les frais de livraison', 'Cela n\'a aucun effet sur les ventes'],
                            'correct' => [0],
                            'explanation' => 'Fidéliser coûte moins cher que d\'acquérir, et un client satisfait rachète et recommande.',
                        ],
                        [
                            'question' => 'Comment bien réagir à un avis négatif ?',
                            'options' => ['Le supprimer et ignorer le client', 'Répondre avec calme et chercher à résoudre le problème', 'Insulter le client', 'Ne jamais répondre'],
                            'correct' => [1],
                            'explanation' => 'Une réponse posée et orientée solution rassure aussi les autres clients qui lisent l\'échange.',
                        ],
                        [
                            'question' => 'Quel canal est particulièrement attendu pour un SAV rapide en Afrique ?',
                            'options' => ['Le courrier postal', 'WhatsApp', 'Le fax', 'Le télégramme'],
                            'correct' => [1],
                            'explanation' => 'WhatsApp est le canal privilégié : les clients y attendent des réponses rapides.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 11 — Analyser et faire grandir',
                    'subtitle' => 'KPIs, marge et croissance',
                    'xp_reward' => 220,
                    'content' => "🎯 **Objectif** : piloter ton business avec des chiffres pour le faire grandir durablement.\n\n💡 \"Ce qui ne se mesure pas ne s'améliore pas.\" Pour grandir, tu dois suivre quelques indicateurs clés (**KPIs**) et prendre des décisions basées sur les données, pas sur l'intuition seule.\n\n✅ Les KPIs e-commerce essentiels :\n• **Taux de conversion** : part des visiteurs qui achètent. Un petit gain ici a un gros effet sur le chiffre d'affaires.\n• **Panier moyen** : montant moyen dépensé par commande. L'augmenter (ventes croisées, packs) booste les revenus sans plus de trafic.\n• **Marge** : ce qui te reste après tous les coûts (achat, livraison, publicité, frais de plateforme). C'est elle qui détermine si tu gagnes vraiment de l'argent.\n• **Coût d'acquisition client (CAC)** : combien te coûte un nouveau client (surtout en publicité). Il doit rester inférieur à ce que ce client te rapporte.\n\n✅ Faire **grandir** son business :\n• Augmente le **panier moyen** : produits complémentaires, packs, livraison offerte au-dessus d'un montant.\n• Fais **revenir** les clients : relances, offres fidélité.\n• Réinvestis dans les **canaux qui rapportent** et coupe ceux qui ne convertissent pas.\n• Élargis ta gamme **progressivement**, en restant cohérent avec ta niche.\n\n✅ Surveille ta **rentabilité réelle** : un chiffre d'affaires élevé avec une marge négative t'appauvrit. Vise une croissance rentable, pas seulement de gros volumes.\n\n🏆 Bravo ! Tu connais maintenant tout le parcours pour lancer, gérer et faire grandir un business e-commerce. Le secret restant : passer à l'action et améliorer en continu. 💡",
                    'questions' => [
                        [
                            'question' => 'Que mesure le "panier moyen" ?',
                            'options' => ['Le nombre de visiteurs', 'Le montant moyen dépensé par commande', 'Le poids moyen d\'un colis', 'Le délai de livraison'],
                            'correct' => [1],
                            'explanation' => 'Le panier moyen est le montant moyen dépensé par commande ; l\'augmenter booste les revenus.',
                        ],
                        [
                            'question' => 'Pourquoi un gros chiffre d\'affaires ne suffit pas à juger la réussite ?',
                            'options' => ['Parce que la marge peut être négative malgré un gros CA', 'Parce que le CA est secret', 'Parce que le CA n\'existe pas en ligne', 'Parce que le CA empêche de vendre'],
                            'correct' => [0],
                            'explanation' => 'Sans marge positive, un fort chiffre d\'affaires peut quand même faire perdre de l\'argent.',
                        ],
                        [
                            'question' => 'Quels leviers permettent d\'augmenter le panier moyen ? (plusieurs réponses)',
                            'options' => ['Proposer des produits complémentaires (ventes croisées)', 'Créer des packs', 'Offrir la livraison au-dessus d\'un certain montant', 'Réduire le nombre de produits visibles'],
                            'correct' => [0, 1, 2],
                            'explanation' => 'Ventes croisées, packs et livraison offerte au-delà d\'un seuil augmentent le montant par commande.',
                        ],
                    ],
                ],
            ],
        ]);

        $this->command->info('  ✓ Roadmap E-commerce créée (11 niveaux).');
    }
}
