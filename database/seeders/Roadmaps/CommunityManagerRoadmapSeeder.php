<?php

namespace Database\Seeders\Roadmaps;

use Illuminate\Database\Seeder;

/**
 * Roadmap Community Manager — gérer la présence d'une marque sur les réseaux sociaux.
 */
class CommunityManagerRoadmapSeeder extends Seeder
{
    use RoadmapSeederHelper;

    public function run(): void
    {
        $this->createRoadmap([
            'title' => 'Deviens Community Manager',
            'slug' => 'community-manager',
            'domain' => 'marketing',
            'description' => "Apprends à animer une communauté en ligne, créer du contenu engageant et faire grandir une marque sur les réseaux sociaux. Une formation pratique adaptée au contexte africain : commerces de Douala, startups de Yaoundé, marques mobile money et entrepreneurs locaux. De zéro à un poste de Community Manager opérationnel.",
            'objectives' => "Comprendre le rôle réel d'un Community Manager\nChoisir les bons réseaux selon la cible\nConstruire une ligne éditoriale et un calendrier de publication\nCréer du contenu qui génère de l'engagement\nAnimer une communauté et gérer les bad buzz\nMesurer la performance avec les bons KPI",
            'icon' => '📱',
            'color' => '#7C3AED',
            'difficulty' => 'beginner',
            'required_packs' => [],
            'pass_threshold' => 70,
            'order' => 20,
            'levels' => [
                [
                    'title' => 'Niveau 1 — Le métier de Community Manager',
                    'subtitle' => 'Comprendre le rôle et les missions',
                    'xp_reward' => 100,
                    'content' => "🎯 **Objectif** : comprendre ce qu'est vraiment un Community Manager (CM) et le distinguer des métiers voisins.\n\n💡 Le CM est la voix d'une marque en ligne. Il ne se contente pas de \"poster sur Facebook\" : il anime, dialogue, modère et fait grandir une communauté autour d'une marque.\n\nSes missions principales :\n• Publier du contenu régulier et cohérent\n• Répondre aux commentaires et messages\n• Animer et fidéliser la communauté\n• Surveiller la réputation (veille)\n• Rendre compte des résultats (reporting)\n\n⚠️ À ne pas confondre :\n• Le CM gère le quotidien et la relation.\n• Le **Social Media Manager** définit la stratégie globale.\n• Le **Content Creator** produit surtout les visuels et vidéos.\n\nExemple : un restaurant à Douala recrute un CM. Son travail n'est pas de cuisiner ni de faire la pub à la radio, mais de poster les plats du jour sur Instagram, répondre aux clients en commentaire et créer une ambiance fidélisante.\n\n✅ Retiens : un bon CM est avant tout un communicant à l'écoute, pas un simple \"posteur\".",
                    'questions' => [
                        ['question' => 'Quelle est la mission CENTRALE d\'un Community Manager ?', 'options' => ['Coder le site web de la marque', 'Animer et faire grandir une communauté en ligne', 'Gérer la comptabilité de l\'entreprise', 'Livrer les commandes des clients'], 'correct' => [1], 'explanation' => 'Le CM anime la communauté et entretient la relation entre la marque et son public.'],
                        ['question' => 'Quelle différence entre un CM et un Social Media Manager ?', 'options' => ['Aucune, c\'est le même métier', 'Le Social Media Manager définit la stratégie, le CM gère le quotidien', 'Le CM gagne toujours plus', 'Le CM ne touche jamais aux réseaux'], 'correct' => [1], 'explanation' => 'Le Social Media Manager pense la stratégie globale tandis que le CM l\'exécute au quotidien.'],
                        ['question' => 'Parmi ces tâches, lesquelles relèvent du CM ? (plusieurs réponses)', 'options' => ['Répondre aux commentaires', 'Faire la veille de réputation', 'Réparer les ordinateurs du bureau', 'Publier du contenu régulier'], 'correct' => [0, 1, 3], 'explanation' => 'Répondre, surveiller la réputation et publier font partie du cœur de métier du CM ; la réparation informatique non.'],
                    ],
                ],
                [
                    'title' => 'Niveau 2 — Choisir ses réseaux sociaux',
                    'subtitle' => 'Le bon réseau pour la bonne cible',
                    'xp_reward' => 110,
                    'content' => "🎯 **Objectif** : choisir les réseaux adaptés à la marque plutôt que d'être partout.\n\n💡 Erreur de débutant : vouloir être présent sur TOUS les réseaux. Mieux vaut bien gérer 2 réseaux que mal en gérer 6.\n\nVoici un repère rapide :\n\n| Réseau | Public / usage |\n|---|---|\n| Facebook | Large public, communautés locales, groupes |\n| WhatsApp | Relation directe, ventes, mobile money |\n| Instagram | Visuel, lifestyle, jeunes urbains |\n| TikTok | Vidéo courte, viralité, 16-30 ans |\n| LinkedIn | B2B, recrutement, image pro |\n\n✅ Méthode pour choisir :\n• Où se trouve MA cible ? (âge, ville, habitudes)\n• Quel format je peux produire régulièrement ?\n• Quels réseaux utilisent mes concurrents ?\n\nExemple : une boutique de pagnes à Yaoundé vise des femmes de 25-45 ans → Facebook + WhatsApp Business + Instagram. Inutile d'aller sur LinkedIn.\n\n⚠️ Au Cameroun, WhatsApp est central : beaucoup de ventes se concluent en message privé. Ne le néglige jamais.\n\n✅ Retiens : on choisit un réseau parce que la CIBLE y est, pas parce qu'il est à la mode.",
                    'questions' => [
                        ['question' => 'Quelle est la bonne approche pour choisir ses réseaux ?', 'options' => ['Être présent sur tous les réseaux possibles', 'Choisir là où se trouve la cible et que l\'on peut alimenter régulièrement', 'Choisir uniquement le réseau le plus récent', 'Copier un grand groupe international'], 'correct' => [1], 'explanation' => 'On sélectionne les réseaux où la cible est présente et que l\'on peut alimenter avec régularité.'],
                        ['question' => 'Pour une marque B2B qui veut recruter et soigner son image professionnelle, quel réseau privilégier ?', 'options' => ['TikTok', 'LinkedIn', 'Une chaîne de cuisine', 'Snapchat uniquement'], 'correct' => [1], 'explanation' => 'LinkedIn est le réseau de référence pour le B2B, le recrutement et l\'image professionnelle.'],
                        ['question' => 'Pourquoi WhatsApp est-il stratégique dans le contexte camerounais ?', 'options' => ['Il sert uniquement à appeler', 'Beaucoup de ventes se concluent en message privé', 'Il interdit les images', 'Il est réservé aux entreprises occidentales'], 'correct' => [1], 'explanation' => 'WhatsApp est un canal de vente direct majeur où de nombreuses transactions se concluent en privé.'],
                    ],
                ],
                [
                    'title' => 'Niveau 3 — Ligne éditoriale et identité',
                    'subtitle' => 'Donner une voix cohérente à la marque',
                    'xp_reward' => 120,
                    'content' => "🎯 **Objectif** : définir une ligne éditoriale claire pour que la marque ait une voix reconnaissable.\n\n💡 La ligne éditoriale, c'est l'ensemble des règles qui définissent CE QUE l'on dit, COMMENT on le dit et À QUI.\n\nElle repose sur 4 piliers :\n• **Cible** : à qui on parle (persona)\n• **Ton** : sérieux, fun, proche, expert ?\n• **Thématiques** : les sujets récurrents\n• **Valeurs** : ce que la marque défend\n\nUne technique simple : les **piliers de contenu** (3 à 5 thèmes). Exemple pour une école de codage à Douala :\n• Conseils carrière tech\n• Témoignages d'anciens élèves\n• Coulisses des cours\n• Actu du numérique en Afrique\n\n✅ Crée aussi une **charte de ton** : tutoiement ou vouvoiement ? Emojis autorisés ? Argot local accepté ?\n\nExemple de ton : \"Proche et encourageant, on tutoie, emojis bienvenus, pas de langage trop technique.\"\n\n⚠️ Sans ligne éditoriale, les publications partent dans tous les sens et la marque perd en crédibilité.\n\n✅ Retiens : la cohérence crée la reconnaissance et la confiance.",
                    'questions' => [
                        ['question' => 'Que définit une ligne éditoriale ?', 'options' => ['Le budget publicitaire annuel', 'Ce que l\'on dit, comment on le dit et à qui', 'Le matériel informatique nécessaire', 'Le salaire du Community Manager'], 'correct' => [1], 'explanation' => 'La ligne éditoriale fixe le message, le ton et la cible des contenus de la marque.'],
                        ['question' => 'À quoi servent les "piliers de contenu" ?', 'options' => ['À acheter de la publicité', 'À définir 3 à 5 grands thèmes récurrents de la marque', 'À supprimer les commentaires négatifs', 'À mesurer le chiffre d\'affaires'], 'correct' => [1], 'explanation' => 'Les piliers de contenu structurent la production autour de quelques grands thèmes cohérents.'],
                        ['question' => 'Quels éléments composent une bonne ligne éditoriale ? (plusieurs réponses)', 'options' => ['La cible / persona', 'Le ton de communication', 'Le numéro fiscal de l\'entreprise', 'Les thématiques récurrentes'], 'correct' => [0, 1, 3], 'explanation' => 'Cible, ton et thématiques structurent la ligne éditoriale ; le numéro fiscal n\'en fait pas partie.'],
                    ],
                ],
                [
                    'title' => 'Niveau 4 — Calendrier éditorial',
                    'subtitle' => 'Planifier pour publier avec régularité',
                    'xp_reward' => 130,
                    'content' => "🎯 **Objectif** : organiser les publications dans le temps avec un calendrier éditorial.\n\n💡 La régularité bat la quantité. Mieux vaut 3 posts de qualité par semaine, toujours aux mêmes moments, que 10 posts en désordre puis le silence.\n\nUn calendrier éditorial répond à : QUOI publier, QUAND, SUR QUEL réseau, et QUI le fait.\n\nExemple de semaine type pour une boutique :\n\n| Jour | Réseau | Type de post |\n|---|---|---|\n| Lundi | Instagram | Nouveau produit |\n| Mercredi | Facebook | Conseil / astuce |\n| Vendredi | WhatsApp | Promo week-end |\n| Dimanche | Tous | Coulisses / humain |\n\n✅ Bonnes pratiques :\n• Préparer le contenu à l'avance (batch)\n• Adapter aux horaires où la cible est connectée (souvent 12h-13h et 19h-21h)\n• Intégrer les temps forts : fêtes, rentrée, fin de mois (paie / mobile money)\n• Garder de la place pour le spontané et l'actualité\n\n⚠️ Ne planifie pas à 100% : laisse de la souplesse pour réagir à une tendance.\n\n✅ Retiens : un calendrier transforme l'improvisation en stratégie tenable.",
                    'questions' => [
                        ['question' => 'Quel est le principal avantage d\'un calendrier éditorial ?', 'options' => ['Publier dès qu\'on a une idée', 'Assurer une publication régulière et organisée', 'Éviter de répondre aux clients', 'Réduire le nombre de réseaux'], 'correct' => [1], 'explanation' => 'Le calendrier garantit une présence régulière et planifiée plutôt qu\'une publication désordonnée.'],
                        ['question' => 'Qu\'est-ce qui compte le plus pour une communauté ?', 'options' => ['La quantité maximale de posts', 'La régularité et la qualité', 'Publier uniquement la nuit', 'Ne jamais planifier'], 'correct' => [1], 'explanation' => 'La régularité associée à la qualité fidélise mieux que le volume désordonné.'],
                        ['question' => 'Pourquoi laisser de la souplesse dans le calendrier ?', 'options' => ['Pour pouvoir réagir aux tendances et à l\'actualité', 'Pour publier moins souvent', 'Pour économiser de l\'argent', 'Parce que la planification est interdite'], 'correct' => [0], 'explanation' => 'Une marge de souplesse permet de rebondir sur l\'actualité et les tendances du moment.'],
                    ],
                ],
                [
                    'title' => 'Niveau 5 — Créer du contenu engageant',
                    'subtitle' => 'Formats, accroches et appels à l\'action',
                    'xp_reward' => 140,
                    'content' => "🎯 **Objectif** : produire du contenu qui capte l'attention et déclenche des réactions.\n\n💡 L'engagement, c'est tout ce que fait l'audience : likes, commentaires, partages, enregistrements, clics.\n\nLes 3 secondes décisives : l'**accroche** (le début du post ou de la vidéo) doit donner envie de continuer.\n\nFormats qui marchent :\n• Vidéo courte (Reels, TikTok) : forte portée\n• Carrousel : pour expliquer / éduquer\n• Photo \"avant/après\" : preuve concrète\n• Question / sondage : déclenche les réponses\n• Témoignage client : crédibilité\n\n✅ La structure A.I.D.A. :\n• **Attention** : une accroche forte\n• **Intérêt** : une info utile\n• **Désir** : montrer le bénéfice\n• **Action** : un appel clair (CTA)\n\nExemple de post pour un service de livraison à Douala :\n\"😱 Tu attends encore 2h pour un repas ? On livre en 30 min à Akwa. 👉 Commande sur WhatsApp maintenant !\"\n\n⚠️ Un post sans appel à l'action (CTA) laisse l'audience passive. Dis toujours quoi faire ensuite.\n\n✅ Retiens : accroche + valeur + appel à l'action = post engageant.",
                    'questions' => [
                        ['question' => 'Que désigne l\'engagement sur les réseaux sociaux ?', 'options' => ['Le nombre d\'employés de la marque', 'Les réactions de l\'audience : likes, commentaires, partages, clics', 'Le budget publicitaire', 'Le nombre de réseaux utilisés'], 'correct' => [1], 'explanation' => 'L\'engagement mesure les interactions de l\'audience avec le contenu.'],
                        ['question' => 'À quoi sert le "A" final de la méthode AIDA ?', 'options' => ['Attirer l\'attention', 'Inciter à l\'action (CTA)', 'Augmenter le prix', 'Archiver le post'], 'correct' => [1], 'explanation' => 'Le dernier A correspond à l\'Action : l\'appel concret invitant l\'audience à agir.'],
                        ['question' => 'Pourquoi soigner l\'accroche d\'un post ou d\'une vidéo ?', 'options' => ['Parce que les premières secondes décident si l\'audience continue', 'Pour augmenter le nombre d\'abonnés automatiquement', 'Parce que c\'est obligatoire par la loi', 'Pour réduire la qualité de l\'image'], 'correct' => [0], 'explanation' => 'L\'accroche capte l\'attention dans les premières secondes, déterminantes pour la suite.'],
                    ],
                ],
                [
                    'title' => 'Niveau 6 — Animer la communauté',
                    'subtitle' => 'Créer du lien et fidéliser',
                    'xp_reward' => 150,
                    'content' => "🎯 **Objectif** : transformer une audience passive en communauté active et fidèle.\n\n💡 Animer, c'est créer du dialogue. Une communauté qui interagit est plus visible (l'algorithme favorise les posts qui font réagir).\n\nLeviers d'animation :\n• Poser des questions ouvertes\n• Lancer des sondages et quiz\n• Répondre à CHAQUE commentaire (ou presque)\n• Reposter le contenu des abonnés (UGC)\n• Organiser des jeux-concours simples\n• Remercier et valoriser les membres fidèles\n\n✅ La règle d'or : **répondre vite**. Un commentaire ou un message sans réponse en 24h, c'est un client potentiel perdu. Sur WhatsApp Business, l'attente acceptable est encore plus courte.\n\nExemple : une page de produits cosmétiques pose chaque vendredi \"Ta routine beauté du week-end ? 👇\" → des dizaines de commentaires → l'algorithme pousse la page à plus de monde.\n\n⚠️ Ne jamais ignorer une question, même critique. Le silence est souvent perçu comme du mépris.\n\n✅ Retiens : animer = dialoguer, pas seulement diffuser. La conversation crée la fidélité.",
                    'questions' => [
                        ['question' => 'Qu\'est-ce qui caractérise une bonne animation de communauté ?', 'options' => ['Diffuser sans jamais répondre', 'Créer du dialogue et répondre aux interactions', 'Supprimer tous les commentaires', 'Publier une seule fois par mois'], 'correct' => [1], 'explanation' => 'Animer consiste à dialoguer et à entretenir l\'interaction, pas seulement à diffuser.'],
                        ['question' => 'Pourquoi répondre rapidement aux messages et commentaires ?', 'options' => ['Pour éviter de perdre un client potentiel et montrer du respect', 'Parce que c\'est interdit de répondre lentement', 'Pour augmenter automatiquement le prix', 'Cela n\'a aucun impact'], 'correct' => [0], 'explanation' => 'Une réponse rapide retient le client potentiel et témoigne de considération.'],
                        ['question' => 'Que signifie UGC (User Generated Content) ?', 'options' => ['Du contenu créé par les utilisateurs / abonnés', 'Un logiciel de montage vidéo', 'Une taxe sur la publicité', 'Un type de pixel publicitaire'], 'correct' => [0], 'explanation' => 'L\'UGC est le contenu produit par les utilisateurs, précieux car authentique et fédérateur.'],
                    ],
                ],
                [
                    'title' => 'Niveau 7 — Modération et gestion de crise',
                    'subtitle' => 'Commentaires négatifs et bad buzz',
                    'xp_reward' => 160,
                    'content' => "🎯 **Objectif** : gérer les commentaires négatifs et désamorcer un bad buzz sans aggraver la situation.\n\n💡 Un commentaire négatif n'est pas une catastrophe : bien géré, il renforce la confiance. Mal géré, il devient une crise.\n\n✅ La méthode **A.R.C.** :\n• **Accueillir** : remercier / reconnaître la remarque\n• **Répondre** : avec calme, en public puis en privé\n• **Corriger** : proposer une solution concrète\n\nExemple : \"Bonjour Marie, merci de nous avoir signalé ce retard, on est vraiment désolés 🙏. On vous écrit en privé pour trouver une solution.\"\n\n⚠️ À éviter absolument :\n• Supprimer un commentaire critique légitime (effet Streisand)\n• Répondre sous le coup de l'émotion\n• Entrer dans une dispute publique\n• Faire le mort en pleine crise\n\nDistingue :\n• **Critique constructive** → répondre et corriger\n• **Troll / insulte** → ne pas nourrir, masquer si besoin\n• **Spam** → supprimer / bloquer\n\nEn cas de **bad buzz** : reconnaître vite, communiquer un message officiel, ne pas mentir. Le silence ou le mensonge empire toujours les choses.\n\n✅ Retiens : transparence + rapidité + solution = crise maîtrisée.",
                    'questions' => [
                        ['question' => 'Face à une critique CONSTRUCTIVE d\'un client, que faire ?', 'options' => ['La supprimer immédiatement', 'Accueillir, répondre avec calme et proposer une solution', 'L\'ignorer totalement', 'Insulter le client en retour'], 'correct' => [1], 'explanation' => 'La méthode ARC recommande d\'accueillir, répondre calmement et corriger.'],
                        ['question' => 'Pourquoi est-il risqué de supprimer un commentaire critique légitime ?', 'options' => ['Cela peut déclencher l\'effet Streisand et amplifier la crise', 'C\'est techniquement impossible', 'Cela augmente les ventes', 'Le réseau facture chaque suppression'], 'correct' => [0], 'explanation' => 'Supprimer une critique légitime attire l\'attention et amplifie le mécontentement (effet Streisand).'],
                        ['question' => 'En cas de bad buzz, quelles attitudes sont recommandées ? (plusieurs réponses)', 'options' => ['Reconnaître rapidement le problème', 'Communiquer un message officiel transparent', 'Mentir pour gagner du temps', 'Proposer une solution concrète'], 'correct' => [0, 1, 3], 'explanation' => 'Reconnaissance rapide, transparence et solution apaisent une crise ; mentir l\'aggrave.'],
                    ],
                ],
                [
                    'title' => 'Niveau 8 — Mesurer la performance (KPI)',
                    'subtitle' => 'Reach, engagement et conversion',
                    'xp_reward' => 170,
                    'content' => "🎯 **Objectif** : mesurer ce qui compte avec les bons indicateurs (KPI).\n\n💡 \"Ce qui ne se mesure pas ne s'améliore pas.\" Un CM doit savoir lire ses statistiques.\n\nLes KPI clés :\n• **Reach (portée)** : nombre de personnes qui ont vu le contenu\n• **Impressions** : nombre total d'affichages (une personne peut voir plusieurs fois)\n• **Engagement** : likes + commentaires + partages + clics\n• **Taux d'engagement** : engagement ÷ portée (ou abonnés) × 100\n• **Croissance des abonnés**\n• **Taux de clic (CTR)** et **conversions** (ventes, contacts WhatsApp)\n\nExemple de calcul du taux d'engagement :\n```\nTaux d'engagement = (interactions / portee) x 100\nEx : 150 interactions / 3000 vues = 5%\n```\nUn taux de 3 à 6% est généralement considéré comme bon.\n\n✅ Bonnes pratiques :\n• Suivre les KPI chaque semaine / mois\n• Comparer dans le temps (évolution), pas juste un chiffre isolé\n• Relier les chiffres aux objectifs (notoriété ? ventes ?)\n\n⚠️ Le nombre d'abonnés seul est une **vanity metric** : 50 000 abonnés inactifs valent moins que 2 000 engagés.\n\n✅ Retiens : suis l'engagement et la conversion, pas seulement le nombre d'abonnés.",
                    'questions' => [
                        ['question' => 'Que mesure le "reach" (portée) ?', 'options' => ['Le nombre de personnes ayant vu le contenu', 'Le chiffre d\'affaires de la marque', 'Le nombre de salariés', 'Le coût d\'une publicité'], 'correct' => [0], 'explanation' => 'Le reach correspond au nombre de personnes uniques qui ont vu le contenu.'],
                        ['question' => 'Comment calcule-t-on le taux d\'engagement ?', 'options' => ['Abonnés multipliés par 2', 'Interactions divisées par la portée, multiplié par 100', 'Nombre de posts par mois', 'Budget divisé par les ventes'], 'correct' => [1], 'explanation' => 'Le taux d\'engagement se calcule en divisant les interactions par la portée, multiplié par 100.'],
                        ['question' => 'Pourquoi le nombre d\'abonnés seul est-il une "vanity metric" ?', 'options' => ['Parce que des abonnés inactifs apportent peu de valeur réelle', 'Parce qu\'il est toujours faux', 'Parce qu\'il coûte cher', 'Parce qu\'il est interdit de le mesurer'], 'correct' => [0], 'explanation' => 'Un grand nombre d\'abonnés inactifs flatte l\'ego mais ne traduit pas l\'engagement réel.'],
                    ],
                ],
                [
                    'title' => 'Niveau 9 — Publicité sociale et veille',
                    'subtitle' => 'Booster sa portée et rester à la page',
                    'xp_reward' => 185,
                    'content' => "🎯 **Objectif** : comprendre la publicité payante sur les réseaux et organiser sa veille.\n\n💡 La portée organique (gratuite) baisse partout. La **publicité sociale** (Meta Ads, TikTok Ads) permet de toucher précisément une audience.\n\nNotions clés de la pub :\n• **Ciblage** : âge, ville (ex : Douala), centres d'intérêt, comportement\n• **Objectif de campagne** : notoriété, trafic, messages, ventes\n• **Budget** : on peut démarrer avec de petits montants quotidiens\n• **Créa** : le visuel + le texte de l'annonce\n• **A/B testing** : tester 2 versions pour garder la meilleure\n\nExemple : une marque de jus à Yaoundé sponsorise un Reel ciblant les 18-35 ans de la ville, objectif \"messages WhatsApp\", 2 000 FCFA/jour.\n\n✅ La **veille** : rester informé pour ne pas se faire dépasser.\n• Surveiller les tendances (sons TikTok, formats, sujets du moment)\n• Observer les concurrents (benchmark)\n• Suivre l'e-réputation (mentions de la marque)\n• Repérer les nouveautés des plateformes\n\n⚠️ Une tendance dure peu : il faut réagir vite mais rester cohérent avec la marque.\n\n✅ Retiens : la pub amplifie un bon contenu ; la veille te garde pertinent.",
                    'questions' => [
                        ['question' => 'À quoi sert le ciblage en publicité sociale ?', 'options' => ['À diffuser l\'annonce à tout le monde au hasard', 'À montrer l\'annonce aux personnes les plus pertinentes (âge, ville, intérêts)', 'À supprimer les commentaires', 'À mesurer le nombre d\'abonnés'], 'correct' => [1], 'explanation' => 'Le ciblage permet de diffuser l\'annonce auprès de l\'audience la plus pertinente.'],
                        ['question' => 'Qu\'est-ce que l\'A/B testing en publicité ?', 'options' => ['Tester deux versions d\'une annonce pour garder la plus performante', 'Doubler le budget automatiquement', 'Publier deux fois le même jour', 'Bloquer les concurrents'], 'correct' => [0], 'explanation' => 'L\'A/B testing compare deux versions afin de conserver celle qui performe le mieux.'],
                        ['question' => 'Quels éléments font partie de la veille du CM ? (plusieurs réponses)', 'options' => ['Suivre les tendances et formats du moment', 'Observer les concurrents (benchmark)', 'Surveiller l\'e-réputation de la marque', 'Calculer la TVA de l\'entreprise'], 'correct' => [0, 1, 2], 'explanation' => 'Tendances, benchmark concurrentiel et e-réputation composent la veille ; le calcul de la TVA non.'],
                    ],
                ],
                [
                    'title' => 'Niveau 10 — Outils, organisation et carrière',
                    'subtitle' => 'Boîte à outils du CM professionnel',
                    'xp_reward' => 200,
                    'content' => "🎯 **Objectif** : maîtriser les outils du métier et se projeter dans une carrière de CM.\n\n💡 Un CM efficace s'appuie sur des outils pour planifier, créer et analyser.\n\nLes grandes familles d'outils :\n• **Planification** : Meta Business Suite (gratuit), Buffer, Hootsuite, Later → programmer les posts à l'avance\n• **Création visuelle** : Canva (incontournable, gratuit), CapCut pour la vidéo\n• **Analyse** : statistiques natives des réseaux, Meta Business Suite\n• **Organisation** : Trello, Notion, Google Sheets pour le calendrier éditorial\n• **Veille** : Google Alerts, recherche par hashtags\n\n✅ Workflow type d'une semaine :\n• Lundi : préparer les visuels sur Canva (batch)\n• Mardi : programmer la semaine sur Meta Business Suite\n• Chaque jour : animer, répondre, surveiller\n• Vendredi : analyser les KPI et ajuster\n\n⚠️ L'outil ne remplace pas la stratégie : un bel outil mal utilisé ne sauve pas un mauvais contenu.\n\n🏆 **Félicitations !** Tu as terminé la roadmap Community Manager. Tu connais désormais le rôle, le choix des réseaux, la ligne éditoriale, le calendrier, le contenu engageant, l'animation, la gestion de crise, les KPI, la pub et les outils.\n\nDébouchés : Community Manager (junior puis senior), Social Media Manager, Content Creator, Chargé(e) de communication digitale, freelance pour PME et entrepreneurs. Construis un portfolio (gère 1 ou 2 pages réelles), montre tes résultats chiffrés et tu seras recherché(e). En route vers ta carrière ! 🚀",
                    'questions' => [
                        ['question' => 'À quoi sert un outil comme Meta Business Suite, Buffer ou Hootsuite ?', 'options' => ['À programmer et planifier les publications', 'À cuisiner les plats à publier', 'À remplacer la stratégie', 'À payer les salaires'], 'correct' => [0], 'explanation' => 'Ces outils servent à programmer et planifier les publications à l\'avance.'],
                        ['question' => 'Quel outil est une référence gratuite pour créer des visuels ?', 'options' => ['Excel', 'Canva', 'Photoshop payant uniquement', 'Un tableur comptable'], 'correct' => [1], 'explanation' => 'Canva est l\'outil de création visuelle gratuit le plus utilisé par les CM.'],
                        ['question' => 'Quelle affirmation est correcte sur les outils ?', 'options' => ['Un bon outil remplace toujours une bonne stratégie', 'L\'outil aide mais ne remplace pas la stratégie et le contenu', 'Les outils rendent le contenu inutile', 'Il faut payer tous les outils pour réussir'], 'correct' => [1], 'explanation' => 'Les outils facilitent le travail mais ne compensent jamais une stratégie ou un contenu faible.'],
                    ],
                ],
            ],
        ]);

        $this->command->info('  ✓ Roadmap Community Manager créée (10 niveaux).');
    }
}
