<?php

namespace Database\Seeders\Roadmaps;

use Illuminate\Database\Seeder;

/**
 * Roadmap Techniques de Vente & Négociation — de la psychologie de l'achat au suivi après-vente.
 */
class TechniqueVenteRoadmapSeeder extends Seeder
{
    use RoadmapSeederHelper;

    public function run(): void
    {
        $this->createRoadmap([
            'title' => 'Maîtrise les Techniques de Vente & Négociation',
            'slug' => 'technique-vente-negociation',
            'domain' => 'marketing',
            'description' => "Apprends à vendre et à négocier comme un pro, étape par étape. De la compréhension de la psychologie du client jusqu'à la fidélisation, cette roadmap te donne une méthode complète et concrète, adaptée au terrain camerounais (boutiques de Douala, marchés de Yaoundé, B2B avec entreprises locales, paiements mobile money).",
            'objectives' => "Comprendre ce qui pousse réellement un client à acheter\nConstruire un fichier de prospects et obtenir des rendez-vous\nMener un entretien de vente structuré du contact au closing\nDécouvrir les vrais besoins grâce au bon questionnement\nArgumenter avec les méthodes CAP et SONCAS\nTraiter sereinement les objections, y compris sur le prix\nNégocier en gagnant-gagnant et conclure la vente\nFidéliser et assurer un suivi après-vente professionnel",
            'icon' => '🤝',
            'color' => '#16A34A',
            'difficulty' => 'beginner',
            'required_packs' => [],
            'pass_threshold' => 70,
            'order' => 20,
            'levels' => [
                [
                    'title' => 'Niveau 1 — La psychologie de l\'achat',
                    'subtitle' => 'Pourquoi les gens achètent vraiment',
                    'xp_reward' => 100,
                    'content' => "🎯 **Objectif** : comprendre les vrais ressorts d'un achat avant même de vendre.\n\n💡 On n'achète pas un produit, on achète une **solution à un problème** ou une **émotion**. Une personne qui achète un smartphone à Douala ne cherche pas un écran : elle veut rester en contact, montrer sa réussite, ou faire ses affaires sur WhatsApp.\n\nLes grands moteurs d'achat :\n• **Émotion** : la décision est d'abord émotionnelle, puis justifiée par la raison.\n• **Besoin** vs **désir** : le besoin est rationnel (réparer), le désir est aspirationnel (paraître).\n• **Douleur / plaisir** : on agit pour éviter une douleur (perdre de l'argent) ou gagner un plaisir (gagner du temps).\n\n✅ Règle d'or : un bon vendeur écoute plus qu'il ne parle. Il cherche d'abord à comprendre **ce que le client veut accomplir**.\n\n⚠️ Erreur fréquente : présenter les caractéristiques techniques d'un produit avant d'avoir compris la motivation profonde du client. Tu vends à un humain, pas à une fiche technique.",
                    'questions' => [
                        ['question' => 'Selon la psychologie de l\'achat, sur quoi repose d\'abord une décision d\'achat ?', 'options' => ['Le prix le plus bas', 'L\'émotion, justifiée ensuite par la raison', 'La fiche technique', 'La publicité télévisée'], 'correct' => [1], 'explanation' => 'La décision d\'achat est d\'abord émotionnelle, puis rationalisée par des arguments.'],
                        ['question' => 'Un client achète une moto pour son commerce. Que vend réellement le vendeur ?', 'options' => ['Un moteur', 'Une solution pour gagner du temps et de l\'argent', 'Du métal et des roues', 'Une couleur'], 'correct' => [1], 'explanation' => 'Le client achète le bénéfice (mobilité, revenus) et non l\'objet lui-même.'],
                        ['question' => 'Quels sont des leviers psychologiques d\'achat ? (plusieurs réponses)', 'options' => ['Éviter une douleur', 'Rechercher un plaisir ou un gain', 'Réciter la fiche technique', 'Le statut social et la fierté'], 'correct' => [0, 1, 3], 'explanation' => 'Douleur/plaisir et statut social sont de vrais moteurs ; réciter une fiche technique n\'en est pas un.'],
                    ],
                ],
                [
                    'title' => 'Niveau 2 — La prospection',
                    'subtitle' => 'Trouver et qualifier ses prospects',
                    'xp_reward' => 110,
                    'content' => "🎯 **Objectif** : remplir ton pipeline avec des prospects susceptibles d'acheter.\n\n💡 La prospection, c'est aller chercher les clients là où ils sont. Sans prospects, pas de ventes.\n\nCanaux de prospection au Cameroun :\n• **Terrain** : visites de boutiques, marchés, zones industrielles (Bonabéri, MAGZI).\n• **Téléphone / WhatsApp Business** : appels et messages ciblés.\n• **Réseaux** : recommandations, bouche-à-oreille, LinkedIn pour le B2B.\n• **Réseaux sociaux** : Facebook, TikTok pour le B2C.\n\nQualifier un prospect avec la méthode **BANT** :\n\n| Lettre | Question |\n|--------|----------|\n| **B**udget | A-t-il les moyens ? |\n| **A**uthority | Décide-t-il vraiment ? |\n| **N**eed | A-t-il un vrai besoin ? |\n| **T**iming | Est-ce le bon moment ? |\n\n✅ Concentre ton énergie sur les prospects **qualifiés** : mieux vaut 10 bons contacts que 100 noms au hasard.\n\n⚠️ Ne confonds pas activité et résultat : appeler 50 personnes non qualifiées fait perdre du temps.",
                    'questions' => [
                        ['question' => 'Que signifie la lettre A dans la méthode BANT ?', 'options' => ['Avantage', 'Authority (pouvoir de décision)', 'Argumentation', 'Accord'], 'correct' => [1], 'explanation' => 'Le A de BANT vérifie si l\'interlocuteur a l\'autorité pour décider de l\'achat.'],
                        ['question' => 'Qu\'est-ce qu\'un prospect "qualifié" ?', 'options' => ['N\'importe quel nom dans un carnet', 'Un contact correspondant à des critères de besoin, budget et décision', 'Un client déjà fidèle', 'Une personne qui a refusé'], 'correct' => [1], 'explanation' => 'Un prospect qualifié remplit des critères qui le rendent susceptible d\'acheter.'],
                        ['question' => 'Pourquoi qualifier ses prospects avant de vendre ?', 'options' => ['Pour concentrer son énergie sur les contacts à fort potentiel', 'Pour appeler le plus de monde possible', 'Pour baisser ses prix', 'Pour éviter de vendre'], 'correct' => [0], 'explanation' => 'La qualification évite de gaspiller du temps sur des contacts sans potentiel réel.'],
                    ],
                ],
                [
                    'title' => 'Niveau 3 — La prise de contact',
                    'subtitle' => 'Réussir les premières secondes',
                    'xp_reward' => 120,
                    'content' => "🎯 **Objectif** : créer une bonne première impression et capter l'attention.\n\n💡 On n'a jamais deux fois l'occasion de faire une première bonne impression. Les premières secondes décident souvent de la suite.\n\nLa règle des **4x20** :\n• Les **20 premières secondes**.\n• Les **20 premiers gestes** (poignée de main, posture).\n• Les **20 premiers mots** (accueil clair, ton chaleureux).\n• Les **20 centimètres** du visage (le sourire, le regard).\n\nUne accroche efficace en B2B :\n```\nBonjour M. Ngono, je suis Aïcha de la société X.\nNous aidons les commerces de Douala à réduire\nleurs coûts de paiement mobile money de 15 %.\nAuriez-vous 10 minutes pour que je vous montre comment ?\n```\n\n✅ Sois **clair, bref et orienté bénéfice**. Annonce qui tu es, ce que tu apportes, et demande un peu de temps.\n\n⚠️ Évite le monologue commercial dès le départ : commence par établir une relation de confiance avant de parler produit.",
                    'questions' => [
                        ['question' => 'Que décrit la règle des 4x20 ?', 'options' => ['Le prix de vente', 'Les éléments clés de la première impression', 'Le nombre de prospects à appeler', 'La durée d\'un contrat'], 'correct' => [1], 'explanation' => 'La règle des 4x20 concerne les 20 premières secondes, gestes, mots et centimètres : la première impression.'],
                        ['question' => 'Quelle devrait être la première priorité lors de la prise de contact ?', 'options' => ['Réciter toute la fiche produit', 'Établir la confiance et capter l\'attention', 'Annoncer immédiatement une remise', 'Faire signer le contrat'], 'correct' => [1], 'explanation' => 'La prise de contact vise d\'abord à créer une relation de confiance avant de vendre.'],
                        ['question' => 'Une bonne accroche commerciale doit être :', 'options' => ['Longue et technique', 'Claire, brève et orientée bénéfice client', 'Centrée sur le vendeur', 'Agressive'], 'correct' => [1], 'explanation' => 'Une accroche efficace est concise et met en avant le bénéfice pour le client.'],
                    ],
                ],
                [
                    'title' => 'Niveau 4 — La découverte des besoins',
                    'subtitle' => 'L\'art du questionnement',
                    'xp_reward' => 130,
                    'content' => "🎯 **Objectif** : faire parler le client pour comprendre ses besoins réels.\n\n💡 Le secret de la vente : **poser les bonnes questions et écouter**. Un client convaincu par ses propres réponses achète mieux qu'un client matraqué d'arguments.\n\nLes types de questions :\n• **Ouvertes** : « Comment gérez-vous vos encaissements aujourd'hui ? » (font parler).\n• **Fermées** : « Utilisez-vous déjà le mobile money ? » (confirment).\n• **Alternatives** : « Préférez-vous une livraison à Douala ou à Yaoundé ? »\n\nLa méthode **SPIN** pour structurer la découverte :\n\n| Lettre | Type de question |\n|--------|------------------|\n| **S**ituation | Contexte actuel du client |\n| **P**roblème | Difficultés rencontrées |\n| **I**mplication | Conséquences de ces problèmes |\n| **N**eed-payoff | Bénéfices d'une solution |\n\n✅ Pratique l'**écoute active** : reformule (« Si je comprends bien... »), prends des notes, ne coupe pas la parole.\n\n⚠️ Ne saute jamais cette étape : sans découverte, ton argumentaire tape à côté du vrai besoin.",
                    'questions' => [
                        ['question' => 'Quel type de question fait le plus parler le client ?', 'options' => ['Question fermée', 'Question ouverte', 'Question rhétorique', 'Aucune question'], 'correct' => [1], 'explanation' => 'Les questions ouvertes invitent le client à s\'exprimer librement et à révéler ses besoins.'],
                        ['question' => 'Dans la méthode SPIN, que vise le "I" (Implication) ?', 'options' => ['Décrire la situation actuelle', 'Faire prendre conscience des conséquences des problèmes', 'Donner le prix', 'Conclure la vente'], 'correct' => [1], 'explanation' => 'Les questions d\'implication font mesurer au client l\'impact réel de ses problèmes.'],
                        ['question' => 'Quelles pratiques relèvent de l\'écoute active ? (plusieurs réponses)', 'options' => ['Reformuler ce que dit le client', 'Couper la parole pour vendre', 'Prendre des notes', 'Poser des questions de clarification'], 'correct' => [0, 2, 3], 'explanation' => 'Reformuler, noter et clarifier sont de l\'écoute active ; couper la parole ne l\'est pas.'],
                    ],
                ],
                [
                    'title' => 'Niveau 5 — L\'argumentation CAP',
                    'subtitle' => 'Transformer les caractéristiques en bénéfices',
                    'xp_reward' => 140,
                    'content' => "🎯 **Objectif** : présenter ton offre en parlant le langage du bénéfice client.\n\n💡 Personne n'achète une caractéristique. On achète ce qu'elle **apporte**. La méthode **CAP** structure chaque argument :\n\n• **C**aractéristique : le fait technique. « Cette caisse enregistreuse gère le mobile money. »\n• **A**vantage : ce que ça permet. « Vous encaissez Orange Money et MTN MoMo automatiquement. »\n• **P**reuve : ce qui rassure. « La boutique Mami Nguer à Akwa a réduit ses erreurs de caisse de 90 %. »\n\nSchéma à mémoriser :\n```\nCaractéristique  ➜  Avantage  ➜  Preuve\n(le fait)            (le bénéfice)   (la confiance)\n```\n\nTypes de preuves : témoignage client, démonstration, chiffres, garantie, essai gratuit.\n\n✅ Adapte tes arguments aux besoins découverts au niveau précédent. Un argument qui ne répond à aucun besoin est inutile.\n\n⚠️ Ne noie pas le client sous 10 arguments : choisis les 2 ou 3 qui répondent à SES priorités.",
                    'questions' => [
                        ['question' => 'Que signifie le "A" dans la méthode CAP ?', 'options' => ['Accord', 'Avantage (le bénéfice pour le client)', 'Argent', 'Accroche'], 'correct' => [1], 'explanation' => 'Le A de CAP correspond à l\'avantage, c\'est-à-dire le bénéfice concret apporté au client.'],
                        ['question' => 'Pourquoi ajouter une "Preuve" à un argument ?', 'options' => ['Pour rassurer et crédibiliser', 'Pour allonger la présentation', 'Pour baisser le prix', 'Pour cacher les défauts'], 'correct' => [0], 'explanation' => 'La preuve (témoignage, chiffres, démonstration) rassure le client et renforce la crédibilité.'],
                        ['question' => 'Combien d\'arguments est-il conseillé de présenter ?', 'options' => ['Tous les arguments possibles', 'Les 2 ou 3 qui répondent aux priorités du client', 'Aucun', 'Un seul, toujours le prix'], 'correct' => [1], 'explanation' => 'Mieux vaut sélectionner quelques arguments ciblés que de noyer le client sous une liste.'],
                    ],
                ],
                [
                    'title' => 'Niveau 6 — La méthode SONCAS',
                    'subtitle' => 'Détecter les motivations dominantes',
                    'xp_reward' => 150,
                    'content' => "🎯 **Objectif** : identifier la motivation profonde du client pour adapter ton discours.\n\n💡 **SONCAS** décrit 6 grands types de motivations d'achat. En repérant la dominante du client, tu choisis les bons arguments.\n\n| Lettre | Motivation | Argument qui marche |\n|--------|-----------|---------------------|\n| **S**écurité | Peur du risque | garantie, fiabilité, SAV |\n| **O**rgueil | Image, statut | exclusivité, prestige |\n| **N**ouveauté | Innovation | dernier modèle, tendance |\n| **C**onfort | Simplicité | facilité d'usage, gain de temps |\n| **A**rgent | Économie | rentabilité, prix, ROI |\n| **S**ympathie | Relation | confiance, proximité |\n\nExemple : un commerçant qui répète « Est-ce que c'est solide ? Garanti ? » est un profil **Sécurité** ➜ insiste sur la garantie et le service après-vente.\n\n✅ Écoute les **mots déclencheurs** du client : ils trahissent sa motivation dominante.\n\n⚠️ Un client peut avoir plusieurs motivations : repère la **principale** sans ignorer les autres.",
                    'questions' => [
                        ['question' => 'Dans SONCAS, quelle motivation correspond à la recherche de rentabilité et d\'économie ?', 'options' => ['Sécurité', 'Argent', 'Orgueil', 'Nouveauté'], 'correct' => [1], 'explanation' => 'Le "A" de SONCAS désigne la motivation Argent : économie, prix, rentabilité.'],
                        ['question' => 'Un client demande sans cesse "C\'est garanti ? C\'est fiable ?". Quelle est sa dominante SONCAS ?', 'options' => ['Nouveauté', 'Sécurité', 'Orgueil', 'Sympathie'], 'correct' => [1], 'explanation' => 'Les questions sur la garantie et la fiabilité révèlent une dominante Sécurité.'],
                        ['question' => 'Quelles lettres de SONCAS sont des motivations d\'achat ? (plusieurs réponses)', 'options' => ['Sécurité', 'Orgueil', 'Sanction', 'Confort'], 'correct' => [0, 1, 3], 'explanation' => 'Sécurité, Orgueil et Confort font partie de SONCAS ; "Sanction" n\'en fait pas partie.'],
                    ],
                ],
                [
                    'title' => 'Niveau 7 — Le traitement des objections',
                    'subtitle' => 'Transformer un "non" en opportunité',
                    'xp_reward' => 160,
                    'content' => "🎯 **Objectif** : accueillir et lever les objections sans se braquer.\n\n💡 Une objection n'est pas un refus : c'est un **signe d'intérêt** et une demande de réassurance. « C'est trop cher » veut souvent dire « Prouve-moi que ça les vaut ».\n\nLa méthode **CRAC** :\n• **C**reuser : « Qu'entendez-vous par trop cher ? »\n• **R**eformuler : « Si je comprends bien, vous voulez être sûr de la rentabilité. »\n• **A**rgumenter : apporter la preuve, le bénéfice.\n• **C**ontrôler : « Cela répond-il à votre inquiétude ? »\n\nFace au prix, parle **valeur**, pas coût : ramène au gain ou à l'économie quotidienne (« 500 FCFA par jour pour doubler vos ventes »).\n\n✅ Garde ton calme, ne contredis jamais frontalement. Utilise la technique « oui, et... » plutôt que « oui, mais... ».\n\n⚠️ Ne traite jamais une objection prix **avant** d'avoir démontré la valeur : sinon tu négocies dans le vide.",
                    'questions' => [
                        ['question' => 'Comment faut-il considérer une objection du client ?', 'options' => ['Comme une insulte', 'Comme un signe d\'intérêt et une demande de réassurance', 'Comme un refus définitif', 'Comme une perte de temps'], 'correct' => [1], 'explanation' => 'Une objection traduit souvent un intérêt et un besoin d\'être rassuré.'],
                        ['question' => 'Dans la méthode CRAC, que fait-on en premier ?', 'options' => ['Conclure la vente', 'Creuser pour comprendre l\'objection', 'Baisser le prix', 'Changer de sujet'], 'correct' => [1], 'explanation' => 'On commence par creuser afin de comprendre la vraie nature de l\'objection.'],
                        ['question' => 'Face à l\'objection "c\'est trop cher", quelle est la bonne approche ?', 'options' => ['Baisser immédiatement le prix', 'Parler de la valeur et du retour sur investissement', 'Mettre fin à l\'entretien', 'Ignorer la remarque'], 'correct' => [1], 'explanation' => 'Sur le prix, il faut démontrer la valeur et le gain plutôt que de céder tout de suite.'],
                    ],
                ],
                [
                    'title' => 'Niveau 8 — Le closing',
                    'subtitle' => 'Conclure la vente au bon moment',
                    'xp_reward' => 170,
                    'content' => "🎯 **Objectif** : amener le client à la décision et conclure sans forcer.\n\n💡 Beaucoup de ventes échouent parce que le vendeur **n'ose pas conclure**. Le closing, c'est aider le client à franchir le pas une fois ses besoins satisfaits.\n\nRepère les **signaux d'achat** :\n• Il pose des questions sur la livraison, le paiement, la garantie.\n• Il se projette : « Quand pourrais-je l'avoir ? »\n• Il demande un détail précis sur l'usage.\n\nTechniques de closing :\n• **Alternative** : « Vous le prenez en bleu ou en noir ? »\n• **Bilan** : récapituler les bénéfices acceptés puis proposer de signer.\n• **Engagement progressif** : faire dire des petits « oui » successifs.\n• **Urgence réelle** : « L'offre est valable jusqu'à vendredi. »\n\n✅ Après ta question de closing, **tais-toi**. Le silence laisse le client décider. Le premier qui parle a souvent perdu.\n\n⚠️ Pas de fausse urgence ni de pression mensongère : cela détruit la confiance et la relation future.",
                    'questions' => [
                        ['question' => 'Qu\'est-ce qu\'un "signal d\'achat" ?', 'options' => ['Un signe que le client est prêt à acheter', 'Une objection finale', 'Un refus poli', 'Une demande de remboursement'], 'correct' => [0], 'explanation' => 'Les signaux d\'achat (questions sur livraison, paiement, délai) montrent que le client est prêt.'],
                        ['question' => 'Que faut-il faire juste après avoir posé une question de closing ?', 'options' => ['Parler le plus possible', 'Se taire et laisser le client décider', 'Baisser le prix', 'Changer de produit'], 'correct' => [1], 'explanation' => 'Le silence après la question de closing laisse au client l\'espace pour décider.'],
                        ['question' => 'Que décrit la technique du closing "alternative" ?', 'options' => ['Proposer un choix entre deux options qui mènent à l\'achat', 'Donner une seule option', 'Annuler la vente', 'Reporter indéfiniment'], 'correct' => [0], 'explanation' => 'Le closing alternatif propose un choix (couleur, date) qui suppose déjà la décision d\'achat.'],
                    ],
                ],
                [
                    'title' => 'Niveau 9 — La négociation gagnant-gagnant',
                    'subtitle' => 'Préserver la marge et la relation',
                    'xp_reward' => 185,
                    'content' => "🎯 **Objectif** : négocier sans casser les prix ni la relation.\n\n💡 La meilleure négociation est celle où **les deux parties gagnent**. L'objectif n'est pas d'écraser l'autre, mais de trouver un accord durable.\n\nPrincipes clés :\n• Prépare ta **MESORE** (Meilleure Solution de Repli, ou BATNA) : que fais-tu si l'accord échoue ?\n• Connais tes **limites** : prix plancher, conditions non négociables.\n• Ne donne jamais une **concession gratuite** : « Je peux faire ce geste si vous commandez en gros / payez comptant. »\n• Élargis le gâteau : joue sur les délais, la livraison, le SAV, pas seulement le prix.\n\nExemple : plutôt que baisser de 10 %, offre la livraison gratuite à Yaoundé et un mois de support. Tu préserves ta marge tout en valorisant l'offre.\n\n✅ En contexte OHADA, formalise l'accord par écrit (bon de commande, devis signé) pour sécuriser les deux parties.\n\n⚠️ Évite la spirale des remises : chaque rabais accordé sans contrepartie affaiblit ta crédibilité et ta marge.",
                    'questions' => [
                        ['question' => 'Que désigne la MESORE (BATNA) en négociation ?', 'options' => ['Le prix de vente final', 'La meilleure solution de repli si l\'accord échoue', 'Une remise automatique', 'Le nom du client'], 'correct' => [1], 'explanation' => 'La MESORE est ta meilleure alternative si la négociation n\'aboutit pas.'],
                        ['question' => 'Quelle est la bonne règle face à une demande de concession ?', 'options' => ['Toujours céder sans rien demander', 'Obtenir une contrepartie en échange', 'Refuser toute discussion', 'Augmenter le prix'], 'correct' => [1], 'explanation' => 'Une concession doit s\'accompagner d\'une contrepartie pour rester gagnant-gagnant.'],
                        ['question' => 'Quelles approches relèvent d\'une négociation gagnant-gagnant ? (plusieurs réponses)', 'options' => ['Jouer sur les délais et services, pas seulement le prix', 'Écraser l\'autre partie à tout prix', 'Formaliser l\'accord par écrit', 'Rechercher un accord durable pour les deux'], 'correct' => [0, 2, 3], 'explanation' => 'Élargir la négociation, formaliser et viser un accord durable sont gagnant-gagnant ; écraser l\'autre ne l\'est pas.'],
                    ],
                ],
                [
                    'title' => 'Niveau 10 — Fidélisation & suivi après-vente',
                    'subtitle' => 'Transformer un client en ambassadeur',
                    'xp_reward' => 200,
                    'content' => "🎯 **Objectif** : faire du client satisfait une source de réachat et de recommandations.\n\n💡 Vendre une fois coûte cher ; **fidéliser** rapporte. Garder un client revient bien moins cher que d'en conquérir un nouveau, et un client content en amène d'autres.\n\nLe suivi après-vente :\n• **Rappeler** quelques jours après pour vérifier la satisfaction.\n• **Tenir ses promesses** : livraison, garantie, SAV réactif (WhatsApp Business est idéal).\n• **Traiter vite** les réclamations : un problème bien géré renforce la fidélité.\n\nFidéliser et développer :\n• Programmes de fidélité, offres exclusives, attentions personnalisées.\n• **Upsell** (monter en gamme) et **cross-sell** (vendre des produits complémentaires).\n• Demander des **recommandations** et des avis.\n\n✅ Un client fidèle achète plus, plus souvent, et te recommande gratuitement : c'est ton meilleur commercial.\n\n🏆 **Félicitations !** Tu maîtrises désormais tout le cycle de vente, du premier contact à la fidélisation. Ces compétences ouvrent sur des métiers porteurs : commercial terrain, chargé de clientèle, business developer, responsable des ventes, account manager, voire entrepreneur. Continue à pratiquer sur le terrain : la vente est un art qui se perfectionne à chaque échange. En route vers la réussite ! 🚀",
                    'questions' => [
                        ['question' => 'Pourquoi la fidélisation est-elle stratégique ?', 'options' => ['Garder un client coûte moins cher que d\'en conquérir un nouveau', 'Les clients fidèles paient toujours plus cher', 'Cela évite de vendre', 'C\'est obligatoire par la loi'], 'correct' => [0], 'explanation' => 'Fidéliser un client existant coûte généralement bien moins cher que d\'en acquérir un nouveau.'],
                        ['question' => 'Que signifie le "cross-sell" ?', 'options' => ['Vendre un produit complémentaire', 'Baisser tous les prix', 'Annuler une vente', 'Changer de client'], 'correct' => [0], 'explanation' => 'Le cross-sell consiste à vendre des produits complémentaires à l\'achat initial.'],
                        ['question' => 'Comment bien gérer une réclamation client après-vente ?', 'options' => ['L\'ignorer', 'La traiter rapidement pour renforcer la confiance', 'Rejeter la faute sur le client', 'Couper le contact'], 'correct' => [1], 'explanation' => 'Une réclamation bien et vite traitée renforce la fidélité et la confiance du client.'],
                    ],
                ],
            ],
        ]);

        $this->command->info('  ✓ Roadmap Techniques de Vente & Négociation créée (10 niveaux).');
    }
}
