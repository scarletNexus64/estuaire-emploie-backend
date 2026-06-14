<?php

namespace Database\Seeders\Roadmaps;

use Illuminate\Database\Seeder;

/**
 * Roadmap Mobile Money & Fintech — comprendre et exploiter le paiement mobile en Afrique.
 */
class MobileMoneyRoadmapSeeder extends Seeder
{
    use RoadmapSeederHelper;

    public function run(): void
    {
        $this->createRoadmap([
            'title' => 'Maîtrise le Mobile Money & la Fintech en Afrique',
            'slug' => 'mobile-money-fintech',
            'domain' => 'finance',
            'description' => "Une formation complète et concrète pour comprendre le Mobile Money et la fintech en Afrique francophone. De la définition du paiement mobile jusqu'aux opportunités business, en passant par les acteurs (MTN MoMo, Orange Money), l'inclusion financière, les API de paiement, la fraude et la réglementation. Pensée pour le contexte camerounais et africain.",
            'objectives' => "Comprendre ce qu'est le Mobile Money et son rôle\nIdentifier les principaux acteurs et leur écosystème\nMaîtriser le cycle d'une transaction et le réseau d'agents\nDécouvrir les innovations fintech et les API de paiement\nReconnaître les fraudes et appliquer les bonnes pratiques de sécurité\nConnaître le cadre réglementaire et les opportunités d'affaires",
            'icon' => '📱',
            'color' => '#F59E0B',
            'difficulty' => 'beginner',
            'required_packs' => [],
            'pass_threshold' => 70,
            'order' => 20,
            'levels' => [
                [
                    'title' => 'Niveau 1 — Qu\'est-ce que le Mobile Money ?',
                    'subtitle' => 'Les bases du paiement mobile',
                    'xp_reward' => 100,
                    'content' => "🎯 **Objectif** : comprendre ce qu'est le Mobile Money et pourquoi il a transformé l'Afrique.\n\n💡 Le Mobile Money est un service financier qui permet de stocker, envoyer et recevoir de l'argent via un simple téléphone, sans posséder de compte bancaire. L'argent est conservé sous forme de **monnaie électronique** dans un portefeuille mobile (wallet) rattaché à votre numéro de téléphone.\n\nConcrètement, à Douala ou Yaoundé, on peut :\n• Envoyer de l'argent à un proche en quelques secondes\n• Payer sa facture d'électricité (ENEO) ou d'eau\n• Acheter du crédit téléphonique\n• Régler ses achats chez un commerçant\n\n✅ Le Mobile Money repose sur trois piliers :\n• Le **wallet** (portefeuille électronique)\n• Le **réseau d'agents** physiques pour déposer/retirer du cash\n• L'**opérateur** (MTN, Orange) qui garantit la valeur\n\n⚠️ Mobile Money n'est PAS de la cryptomonnaie : la monnaie électronique est adossée 1 pour 1 au FCFA déposé en banque par l'opérateur.",
                    'questions' => [
                        ['question' => 'Que permet principalement le Mobile Money ?', 'options' => ['Acheter des actions en bourse', 'Stocker, envoyer et recevoir de l\'argent via un téléphone', 'Imprimer de la monnaie', 'Obtenir un prêt bancaire automatique'], 'correct' => [1], 'explanation' => 'Le Mobile Money sert avant tout à conserver et transférer de la monnaie électronique depuis un téléphone.'],
                        ['question' => 'À quoi est rattaché un portefeuille Mobile Money ?', 'options' => ['À une adresse e-mail', 'À un numéro de téléphone', 'À une carte bancaire obligatoire', 'À une adresse postale'], 'correct' => [1], 'explanation' => 'Le wallet est lié au numéro de téléphone de l\'utilisateur, ce qui le rend accessible sans compte bancaire.'],
                        ['question' => 'La monnaie électronique du Mobile Money est :', 'options' => ['Une cryptomonnaie volatile', 'Adossée 1 pour 1 à la monnaie réelle (FCFA)', 'Créée librement par les agents', 'Sans aucune valeur garantie'], 'correct' => [1], 'explanation' => 'Chaque unité de monnaie électronique correspond à un FCFA réellement déposé par l\'opérateur en banque.'],
                    ],
                ],
                [
                    'title' => 'Niveau 2 — Les acteurs : MTN MoMo & Orange Money',
                    'subtitle' => 'L\'écosystème des opérateurs',
                    'xp_reward' => 110,
                    'content' => "🎯 **Objectif** : identifier les principaux acteurs du Mobile Money en Afrique francophone.\n\n💡 Au Cameroun, deux géants dominent :\n• **MTN Mobile Money (MoMo)** — leader, code USSD *126#\n• **Orange Money** — code USSD #150#\n\nD'autres acteurs existent selon les pays : Wave (Sénégal, Côte d'Ivoire), M-Pesa (Kenya, RDC, pionnier mondial lancé en 2007), Moov Money, Free Money.\n\n✅ L'écosystème comporte plusieurs rôles :\n\n| Acteur | Rôle |\n|--------|------|\n| Opérateur (MTN, Orange) | Émet la monnaie électronique |\n| Banque partenaire | Garde les fonds en garantie |\n| Agents | Font les dépôts/retraits cash |\n| Marchands | Acceptent les paiements |\n| Régulateur (BEAC/COBAC) | Encadre l'activité |\n\n💡 Les opérateurs ont créé des **filiales dédiées** (MTN MoMo, Orange Money) pour répondre aux exigences réglementaires, car l'activité financière est distincte de la téléphonie.\n\n⚠️ Ne confondez pas l'opérateur télécom et l'établissement de monnaie électronique : ce sont juridiquement deux entités séparées.",
                    'questions' => [
                        ['question' => 'Quels sont les deux principaux services de Mobile Money au Cameroun ?', 'options' => ['Wave et M-Pesa', 'MTN MoMo et Orange Money', 'PayPal et Visa', 'Free Money et Moov Money'], 'correct' => [1], 'explanation' => 'MTN MoMo et Orange Money sont les deux leaders du marché camerounais.'],
                        ['question' => 'Quel service est considéré comme le pionnier mondial du Mobile Money (lancé en 2007) ?', 'options' => ['Orange Money', 'Wave', 'M-Pesa', 'PayPal'], 'correct' => [2], 'explanation' => 'M-Pesa, lancé au Kenya en 2007, est le pionnier qui a popularisé le Mobile Money dans le monde.'],
                        ['question' => 'Pourquoi les opérateurs créent-ils des filiales dédiées au Mobile Money ?', 'options' => ['Pour payer moins d\'impôts uniquement', 'Pour répondre aux exigences réglementaires séparant télécom et finance', 'Pour vendre plus de téléphones', 'Pour éviter de payer les agents'], 'correct' => [1], 'explanation' => 'L\'activité financière étant réglementée différemment, elle doit être portée par une entité juridique distincte.'],
                    ],
                ],
                [
                    'title' => 'Niveau 3 — Inclusion financière',
                    'subtitle' => 'Bancariser sans banque',
                    'xp_reward' => 120,
                    'content' => "🎯 **Objectif** : comprendre comment le Mobile Money favorise l'inclusion financière en Afrique.\n\n💡 L'**inclusion financière** désigne l'accès de tous, y compris les populations rurales et modestes, à des services financiers utiles et abordables.\n\nEn Afrique subsaharienne, une grande partie de la population n'a pas de compte bancaire (faible bancarisation). Le Mobile Money comble ce vide :\n• Pas besoin de revenu élevé ni de paperasse complexe\n• Ouverture d'un compte en quelques minutes avec une pièce d'identité\n• Réseau d'agents présent jusque dans les villages\n\n✅ Bénéfices concrets :\n• Une commerçante au marché de Mokolo peut recevoir des paiements et épargner\n• Les transferts d'argent des migrants (diaspora) arrivent plus vite et moins cher\n• Les agriculteurs reçoivent leurs paiements directement, sans déplacement\n\n💡 Le Mobile Money permet aussi l'accès au **micro-crédit** et à la **micro-épargne** via les données d'historique de transactions.\n\n⚠️ Défi : la fracture numérique (zones sans réseau, illettrisme) et le coût des frais peuvent encore exclure certaines personnes.",
                    'questions' => [
                        ['question' => 'Que signifie l\'inclusion financière ?', 'options' => ['Réserver les services bancaires aux riches', 'Donner accès à tous à des services financiers utiles et abordables', 'Interdire les comptes bancaires', 'Augmenter les frais bancaires'], 'correct' => [1], 'explanation' => 'L\'inclusion financière vise à rendre les services financiers accessibles à toute la population.'],
                        ['question' => 'Comment le Mobile Money favorise-t-il l\'inclusion financière ?', 'options' => ['En exigeant un gros dépôt initial', 'En supprimant les téléphones', 'En offrant un accès simple sans compte bancaire ni paperasse lourde', 'En fermant les agences rurales'], 'correct' => [2], 'explanation' => 'Le Mobile Money permet d\'accéder aux services financiers sans les contraintes de la banque traditionnelle.'],
                        ['question' => 'Quels sont des défis qui peuvent freiner l\'inclusion ? (plusieurs réponses)', 'options' => ['La fracture numérique (zones sans réseau)', 'La présence d\'agents partout', 'L\'illettrisme et le coût des frais', 'La gratuité totale des services'], 'correct' => [0, 2], 'explanation' => 'Le manque de réseau, l\'illettrisme et les frais restent des obstacles à une inclusion totale.'],
                    ],
                ],
                [
                    'title' => 'Niveau 4 — Fonctionnement d\'une transaction',
                    'subtitle' => 'Du PIN à la confirmation',
                    'xp_reward' => 130,
                    'content' => "🎯 **Objectif** : comprendre le cycle complet d'une transaction Mobile Money.\n\n💡 Une transaction suit toujours les mêmes étapes, qu'elle passe par USSD (*126#) ou par une application.\n\n✅ Étapes d'un transfert d'argent :\n• 1. L'émetteur compose le code USSD ou ouvre l'app\n• 2. Il choisit « Envoyer de l'argent »\n• 3. Il saisit le numéro du bénéficiaire et le montant\n• 4. Il valide avec son **code PIN secret**\n• 5. Le système débite son wallet et crédite celui du destinataire\n• 6. Les deux parties reçoivent un **SMS de confirmation** avec un identifiant de transaction\n\nSchéma simplifié :\n\n```\nÉmetteur --(débit)--> Plateforme opérateur --(crédit)--> Bénéficiaire\n        <--SMS confirmation--          --SMS confirmation-->\n```\n\n💡 Le **code PIN** est l'élément central de sécurité : il authentifie l'opération. L'identifiant de transaction sert de preuve en cas de litige.\n\n⚠️ Une transaction Mobile Money est quasi instantanée et **irréversible** : un transfert vers un mauvais numéro est très difficile à annuler. Vérifiez toujours le numéro avant de valider !",
                    'questions' => [
                        ['question' => 'Quel élément valide et authentifie une transaction Mobile Money ?', 'options' => ['Le numéro IMEI du téléphone', 'Le code PIN secret', 'Le nom de l\'agent', 'L\'adresse e-mail'], 'correct' => [1], 'explanation' => 'Le code PIN secret confirme l\'identité de l\'utilisateur et autorise l\'opération.'],
                        ['question' => 'Que reçoit-on après une transaction réussie ?', 'options' => ['Une lettre postale', 'Un SMS de confirmation avec un identifiant de transaction', 'Un appel du directeur de la banque', 'Rien du tout'], 'correct' => [1], 'explanation' => 'Le SMS de confirmation avec identifiant sert de preuve de la transaction.'],
                        ['question' => 'Pourquoi faut-il vérifier le numéro avant de valider ?', 'options' => ['Parce que la transaction est lente', 'Parce qu\'elle est quasi instantanée et difficilement réversible', 'Parce que le PIN change à chaque fois', 'Parce que c\'est gratuit'], 'correct' => [1], 'explanation' => 'Les transferts étant quasi instantanés et irréversibles, une erreur de numéro est très difficile à corriger.'],
                    ],
                ],
                [
                    'title' => 'Niveau 5 — Agents : cash-in & cash-out',
                    'subtitle' => 'Le réseau physique de proximité',
                    'xp_reward' => 140,
                    'content' => "🎯 **Objectif** : comprendre le rôle des agents et les opérations cash-in / cash-out.\n\n💡 Les **agents** sont des commerçants partenaires (boutiques, kiosques) qui font le pont entre l'argent physique (cash) et la monnaie électronique. Ils sont la colonne vertébrale du Mobile Money en Afrique.\n\n✅ Deux opérations clés :\n• **Cash-in (dépôt)** : le client donne du cash à l'agent, qui crédite le wallet du client en monnaie électronique\n• **Cash-out (retrait)** : le client transfère de la monnaie électronique à l'agent, qui lui remet du cash\n\nL'agent dispose d'un **float** (flottant) : un solde de monnaie électronique et une caisse de cash qu'il doit équilibrer en permanence.\n\n💡 Modèle économique de l'agent :\n• Il gagne une **commission** sur chaque opération\n• Plus il fait de transactions, plus il gagne\n• Il doit gérer sa liquidité (assez de cash ET assez de float)\n\n⚠️ Risques pour l'agent : manque de liquidité (impossible de servir un client), erreurs de saisie, et arnaques. Un bon agent tient un registre de ses opérations.\n\nC'est un vrai métier qui crée des milliers d'emplois dans les quartiers.",
                    'questions' => [
                        ['question' => 'Qu\'est-ce qu\'une opération de cash-in ?', 'options' => ['Retirer du cash auprès de l\'agent', 'Déposer du cash pour créditer son wallet en monnaie électronique', 'Payer une facture', 'Acheter du crédit téléphonique'], 'correct' => [1], 'explanation' => 'Le cash-in est un dépôt : le client donne du cash et reçoit de la monnaie électronique sur son wallet.'],
                        ['question' => 'Comment l\'agent gagne-t-il de l\'argent ?', 'options' => ['En vendant des téléphones uniquement', 'En percevant une commission sur chaque opération', 'En gardant l\'argent des clients', 'Il ne gagne rien'], 'correct' => [1], 'explanation' => 'L\'agent est rémunéré par une commission sur les dépôts et retraits qu\'il effectue.'],
                        ['question' => 'Que doit gérer un agent pour bien fonctionner ? (plusieurs réponses)', 'options' => ['Assez de cash en caisse', 'Assez de float (monnaie électronique)', 'Aucune liquidité', 'Le mot de passe e-mail des clients'], 'correct' => [0, 1], 'explanation' => 'L\'agent doit équilibrer son cash et son float pour pouvoir servir tous les clients.'],
                    ],
                ],
                [
                    'title' => 'Niveau 6 — Fintech & innovations',
                    'subtitle' => 'Au-delà du simple transfert',
                    'xp_reward' => 150,
                    'content' => "🎯 **Objectif** : découvrir les innovations fintech bâties sur le Mobile Money.\n\n💡 La **fintech** (technologie financière) désigne les entreprises qui utilisent la technologie pour proposer des services financiers innovants. En Afrique, beaucoup s'appuient sur le rail Mobile Money.\n\n✅ Innovations courantes :\n• **Paiement marchand** : payer chez un commerçant via QR code ou code marchand\n• **Crédit instantané** (nano-crédit) basé sur l'historique de transactions\n• **Micro-épargne et tontines digitales**\n• **Micro-assurance** (santé, agriculture) payée par petits montants\n• **Paiement de factures et services** (eau, électricité, scolarité, TV)\n• **Interopérabilité** : envoyer d'un MTN MoMo vers un Orange Money\n\n💡 Des startups camerounaises et africaines (ex. : agrégateurs de paiement) connectent les commerçants et e-commerces à plusieurs wallets via une seule intégration.\n\nSchéma de la pile fintech :\n\n```\nUtilisateur -> App Fintech -> Agrégateur -> [MTN MoMo | Orange Money | Banque]\n```\n\n⚠️ L'innovation doit rester accessible : les meilleures solutions africaines fonctionnent aussi en USSD, pas seulement sur smartphone, pour toucher le plus grand nombre.",
                    'questions' => [
                        ['question' => 'Que désigne le terme « fintech » ?', 'options' => ['Une marque de téléphone', 'Les entreprises utilisant la technologie pour des services financiers innovants', 'Un type de carte SIM', 'Une banque centrale'], 'correct' => [1], 'explanation' => 'La fintech regroupe les entreprises qui innovent dans la finance grâce à la technologie.'],
                        ['question' => 'Qu\'est-ce que l\'interopérabilité dans le Mobile Money ?', 'options' => ['Utiliser deux téléphones à la fois', 'Pouvoir transférer entre opérateurs différents (ex. MoMo vers Orange Money)', 'Avoir plusieurs codes PIN', 'Changer de réseau électrique'], 'correct' => [1], 'explanation' => 'L\'interopérabilité permet d\'envoyer de l\'argent d\'un opérateur à un autre.'],
                        ['question' => 'Pourquoi les bonnes solutions fintech africaines gardent-elles l\'USSD ?', 'options' => ['Parce que c\'est plus joli', 'Pour toucher aussi les personnes sans smartphone', 'Pour consommer plus de données', 'Parce que c\'est obligatoire par la loi'], 'correct' => [1], 'explanation' => 'L\'USSD fonctionne sur tous les téléphones, ce qui élargit l\'accès au-delà des seuls smartphones.'],
                    ],
                ],
                [
                    'title' => 'Niveau 7 — API de paiement',
                    'subtitle' => 'Intégrer le Mobile Money dans une app',
                    'xp_reward' => 160,
                    'content' => "🎯 **Objectif** : comprendre comment une API de paiement permet d'encaisser via Mobile Money.\n\n💡 Une **API** (interface de programmation) permet à un site web ou une application d'interagir automatiquement avec un service. Les API de paiement (MTN MoMo API, agrégateurs comme CinetPay, Notch Pay, Flutterwave) permettent à un e-commerce d'encaisser des paiements Mobile Money.\n\n✅ Deux opérations principales :\n• **Collect / Request-to-Pay** : demander un paiement au client (il valide avec son PIN)\n• **Disbursement / Payout** : envoyer de l'argent (rembourser, payer un fournisseur)\n\nExemple d'appel de collecte (simplifié) :\n\n```http\nPOST /v1/payment/collect\nAuthorization: Bearer VOTRE_TOKEN\nContent-Type: application/json\n\n{\n  \"amount\": 5000,\n  \"currency\": \"XAF\",\n  \"phone\": \"+2376XXXXXXXX\",\n  \"reference\": \"CMD-2026-001\"\n}\n```\n\n💡 Le résultat arrive souvent via un **webhook** (notification automatique) qui informe votre serveur du statut : SUCCESS, PENDING ou FAILED.\n\n⚠️ Ne faites jamais confiance au seul retour client : vérifiez toujours le statut côté serveur avant de livrer un produit. Et ne stockez jamais en clair vos clés d'API.",
                    'questions' => [
                        ['question' => 'À quoi sert une API de paiement Mobile Money ?', 'options' => ['À fabriquer des téléphones', 'À permettre à une application d\'encaisser des paiements automatiquement', 'À recharger la batterie', 'À envoyer des e-mails'], 'correct' => [1], 'explanation' => 'L\'API de paiement connecte une application au service Mobile Money pour automatiser les encaissements.'],
                        ['question' => 'Quelle opération consiste à demander un paiement au client ?', 'options' => ['Disbursement / Payout', 'Collect / Request-to-Pay', 'Cash-out', 'Logout'], 'correct' => [1], 'explanation' => 'Le Collect (Request-to-Pay) déclenche une demande de paiement que le client valide avec son PIN.'],
                        ['question' => 'Quelles bonnes pratiques appliquer avec une API de paiement ? (plusieurs réponses)', 'options' => ['Vérifier le statut côté serveur via webhook', 'Stocker les clés d\'API en clair publiquement', 'Ne jamais exposer ses clés secrètes', 'Livrer le produit sans vérifier le paiement'], 'correct' => [0, 2], 'explanation' => 'Il faut confirmer le paiement côté serveur et protéger les clés d\'API secrètes.'],
                    ],
                ],
                [
                    'title' => 'Niveau 8 — Fraude & sécurité',
                    'subtitle' => 'Protéger son argent et ses données',
                    'xp_reward' => 170,
                    'content' => "🎯 **Objectif** : reconnaître les fraudes et adopter les bons réflexes de sécurité.\n\n💡 Le Mobile Money attire les fraudeurs. Les arnaques les plus courantes au Cameroun :\n• **Ingénierie sociale** : un faux « agent MTN » vous appelle et demande votre PIN\n• **Faux SMS de crédit** : « Vous avez reçu 50 000 FCFA par erreur, renvoyez-les »\n• **SIM swap** : le fraudeur fait dupliquer votre carte SIM pour vider votre wallet\n• **Hameçonnage (phishing)** : faux liens ou faux numéros\n• **Arnaque à l'agent** : faux dépôt, monnaie comptée trop vite\n\n✅ Règles d'or de sécurité :\n• Ne JAMAIS communiquer son code PIN à qui que ce soit (aucun opérateur ne le demande)\n• Vérifier l'identité de l'appelant et le numéro officiel\n• Ne pas renvoyer un argent « reçu par erreur » sans vérifier auprès du service\n• Activer les notifications et vérifier ses relevés\n• Utiliser un PIN difficile à deviner (pas 1234 ni sa date de naissance)\n\n⚠️ Un opérateur ne vous appellera JAMAIS pour vous demander votre PIN ou un code OTP. Toute demande de ce type est une arnaque. En cas de fraude, appelez immédiatement le service client pour bloquer le compte.",
                    'questions' => [
                        ['question' => 'Que faire si quelqu\'un vous demande votre code PIN au téléphone ?', 'options' => ['Le donner s\'il dit travailler chez MTN', 'Ne jamais le communiquer, c\'est une arnaque', 'L\'envoyer par SMS', 'Le partager sur les réseaux sociaux'], 'correct' => [1], 'explanation' => 'Aucun opérateur ne demande jamais votre PIN : toute demande de ce type est une fraude.'],
                        ['question' => 'Qu\'est-ce que le SIM swap ?', 'options' => ['Changer d\'opérateur légalement', 'Un fraudeur fait dupliquer votre SIM pour accéder à votre wallet', 'Échanger son téléphone', 'Recharger sa SIM'], 'correct' => [1], 'explanation' => 'Le SIM swap consiste à dupliquer frauduleusement la SIM pour détourner les accès Mobile Money.'],
                        ['question' => 'Quelles sont de bonnes pratiques de sécurité ? (plusieurs réponses)', 'options' => ['Utiliser un PIN difficile à deviner', 'Donner son PIN à un faux agent', 'Activer les notifications et vérifier ses relevés', 'Utiliser 1234 comme PIN'], 'correct' => [0, 2], 'explanation' => 'Un PIN robuste et la surveillance des relevés protègent efficacement le compte.'],
                    ],
                ],
                [
                    'title' => 'Niveau 9 — Réglementation',
                    'subtitle' => 'Le cadre légal du Mobile Money',
                    'xp_reward' => 185,
                    'content' => "🎯 **Objectif** : connaître le cadre réglementaire qui encadre le Mobile Money en zone CEMAC.\n\n💡 Le Mobile Money est une activité financière strictement encadrée pour protéger les utilisateurs et la stabilité du système.\n\n✅ Les acteurs réglementaires en zone CEMAC (Cameroun et voisins) :\n• La **BEAC** (Banque des États de l'Afrique Centrale) : banque centrale, émet le FCFA et régule les paiements\n• La **COBAC** (Commission Bancaire de l'Afrique Centrale) : supervise les établissements de paiement\n• Les **EME** (Établissements de Monnaie Électronique) : statut requis pour émettre de la monnaie électronique\n\n💡 Exigences clés :\n• **KYC** (Know Your Customer) : identification du client à l'ouverture du compte (pièce d'identité)\n• **AML / LBC-FT** : lutte contre le blanchiment d'argent et le financement du terrorisme\n• **Plafonds** de transaction et de solde selon le niveau de KYC\n• **Cantonnement des fonds** : l'argent des clients est isolé en banque, distinct de celui de l'opérateur\n\n⚠️ Le cadre OHADA régit par ailleurs le droit des affaires (contrats, sociétés) applicable aux entreprises du secteur. Toute fintech doit se conformer à ces règles sous peine de sanctions ou de retrait d'agrément.",
                    'questions' => [
                        ['question' => 'Quelle institution est la banque centrale de la zone CEMAC ?', 'options' => ['La COBAC', 'La BEAC', 'L\'OHADA', 'Le FMI'], 'correct' => [1], 'explanation' => 'La BEAC est la banque centrale qui émet le FCFA et régule les paiements en zone CEMAC.'],
                        ['question' => 'Que signifie KYC dans le contexte du Mobile Money ?', 'options' => ['Keep Your Cash', 'Know Your Customer : identifier le client', 'Kill Your Competitor', 'Key Your Code'], 'correct' => [1], 'explanation' => 'Le KYC (Know Your Customer) impose d\'identifier le client à l\'ouverture du compte.'],
                        ['question' => 'Que vise le cantonnement des fonds ?', 'options' => ['Mélanger l\'argent des clients et de l\'opérateur', 'Isoler l\'argent des clients en banque, distinct de celui de l\'opérateur', 'Supprimer les comptes inactifs', 'Augmenter les frais'], 'correct' => [1], 'explanation' => 'Le cantonnement protège les clients en gardant leurs fonds séparés de ceux de l\'opérateur.'],
                    ],
                ],
                [
                    'title' => 'Niveau 10 — Opportunités business',
                    'subtitle' => 'Construire son projet dans la fintech',
                    'xp_reward' => 200,
                    'content' => "🎯 **Objectif** : identifier les opportunités d'affaires et de carrière autour du Mobile Money.\n\n💡 Le Mobile Money n'est pas qu'un outil : c'est un écosystème porteur d'emplois et d'entreprises en Afrique francophone.\n\n✅ Opportunités entrepreneuriales :\n• Devenir **agent Mobile Money** (point cash-in/cash-out de quartier)\n• Lancer une **fintech** (agrégateur de paiement, micro-crédit, tontine digitale)\n• Intégrer le **paiement mobile** à un e-commerce ou une app via API\n• Proposer du **conseil** et de l'intégration technique aux PME\n\n💡 Métiers qui recrutent :\n• Développeur d'intégration de paiement (API)\n• Chargé de conformité (KYC, AML)\n• Responsable réseau d'agents\n• Analyste fraude / data analyst\n• Chef de produit fintech\n\n✅ Conseils pour se lancer :\n• Identifier un vrai besoin local non résolu\n• Respecter la réglementation (agrément, partenariats)\n• Penser USSD + mobile pour toucher tout le monde\n• Soigner la sécurité et la confiance des utilisateurs\n\n🏆 **Félicitations !** Tu maîtrises désormais les fondamentaux du Mobile Money et de la fintech africaine : acteurs, transactions, agents, API, sécurité et réglementation. Tu peux viser une carrière de développeur fintech, de chargé de conformité, de responsable réseau d'agents, ou même lancer ta propre startup. Le marché africain de la fintech est l'un des plus dynamiques au monde : les opportunités sont immenses. À toi de jouer !",
                    'questions' => [
                        ['question' => 'Quelle est une opportunité entrepreneuriale liée au Mobile Money ?', 'options' => ['Fabriquer des billets de banque', 'Devenir agent Mobile Money ou lancer une fintech', 'Interdire les paiements mobiles', 'Fermer les banques'], 'correct' => [1], 'explanation' => 'Devenir agent ou créer une fintech sont des voies concrètes pour entreprendre dans ce secteur.'],
                        ['question' => 'Quels métiers recrutent dans la fintech ? (plusieurs réponses)', 'options' => ['Développeur d\'intégration de paiement', 'Chargé de conformité (KYC, AML)', 'Astronaute lunaire', 'Analyste fraude / data analyst'], 'correct' => [0, 1, 3], 'explanation' => 'Le secteur recrute des développeurs, des experts conformité et des analystes fraude/data.'],
                        ['question' => 'Quel conseil clé pour réussir une fintech en Afrique ?', 'options' => ['Ignorer la réglementation', 'Cibler uniquement les smartphones haut de gamme', 'Résoudre un vrai besoin local en respectant la réglementation', 'Négliger la sécurité'], 'correct' => [2], 'explanation' => 'Répondre à un besoin réel tout en respectant la loi et la sécurité est la base d\'une fintech viable.'],
                    ],
                ],
            ],
        ]);

        $this->command->info('  ✓ Roadmap Mobile Money & Fintech créée (10 niveaux).');
    }
}
