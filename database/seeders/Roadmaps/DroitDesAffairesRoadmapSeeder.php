<?php

namespace Database\Seeders\Roadmaps;

use Illuminate\Database\Seeder;

/**
 * Roadmap Droit des Affaires — comprendre le cadre juridique de l'entreprise en zone OHADA.
 */
class DroitDesAffairesRoadmapSeeder extends Seeder
{
    use RoadmapSeederHelper;

    public function run(): void
    {
        $this->createRoadmap([
            'title' => 'Maîtrise le Droit des Affaires',
            'slug' => 'droit-des-affaires',
            'domain' => 'juridique',
            'description' => "Une roadmap pratique pour comprendre le droit qui encadre l'activité économique au Cameroun et en zone OHADA. Des sources du droit jusqu'aux procédures collectives, tu apprendras à créer une entreprise, sécuriser tes contrats, protéger ton fonds de commerce et recouvrer tes créances.",
            'objectives' => "Identifier les sources du droit des affaires et la place de l'OHADA\nDistinguer le commerçant, l'acte de commerce et leurs obligations\nChoisir la forme de société adaptée à un projet\nMaîtriser les étapes de création d'une entreprise\nSécuriser les contrats commerciaux et le fonds de commerce\nComprendre la concurrence, le recouvrement et les procédures collectives",
            'icon' => '⚖️',
            'color' => '#1D4ED8',
            'difficulty' => 'intermediate',
            'required_packs' => [],
            'pass_threshold' => 70,
            'order' => 20,
            'levels' => [
                [
                    'title' => 'Niveau 1 — Les sources du droit des affaires',
                    'subtitle' => 'D\'où vient la règle de droit ?',
                    'xp_reward' => 100,
                    'content' => "🎯 **Objectif** : comprendre où se trouvent les règles qui s'imposent à une entreprise au Cameroun.\n\n💡 Le droit des affaires combine plusieurs sources qui forment une hiérarchie :\n• La **Constitution** et les lois nationales\n• Les **Actes uniformes OHADA**, qui priment sur le droit national des États membres\n• Les **règlements** et décrets d'application\n• La **jurisprudence** (décisions des tribunaux, dont la CCJA)\n• Les **usages** commerciaux et la doctrine\n\n⚠️ Point clé : depuis l'adhésion du Cameroun, les Actes uniformes OHADA s'appliquent **directement**, sans transposition, et écartent les lois nationales contraires.\n\n✅ À retenir : pour résoudre un litige commercial, on cherche d'abord la règle OHADA, puis la loi nationale, puis l'usage. La CCJA (Cour Commune de Justice et d'Arbitrage, à Abidjan) est la juridiction suprême en matière d'interprétation OHADA.",
                    'questions' => [
                        ['question' => 'Quelle source prime sur le droit national des États membres en matière commerciale ?', 'options' => ['Les usages locaux', 'Les Actes uniformes OHADA', 'Les décrets municipaux', 'La doctrine'], 'correct' => [1], 'explanation' => 'Les Actes uniformes OHADA s\'appliquent directement et priment sur les lois nationales contraires.'],
                        ['question' => 'Quelle juridiction assure l\'interprétation uniforme du droit OHADA ?', 'options' => ['La Cour suprême du Cameroun', 'La CCJA', 'Le tribunal de première instance', 'Le Conseil constitutionnel'], 'correct' => [1], 'explanation' => 'La CCJA, basée à Abidjan, est la juridiction suprême pour l\'interprétation des Actes uniformes.'],
                        ['question' => 'Parmi ces éléments, lesquels sont des sources du droit des affaires ? (plusieurs réponses)', 'options' => ['La jurisprudence', 'Les usages commerciaux', 'Les horoscopes', 'Les Actes uniformes OHADA'], 'correct' => [0, 1, 3], 'explanation' => 'Jurisprudence, usages et Actes uniformes sont des sources réelles ; les horoscopes n\'ont aucune valeur juridique.'],
                    ],
                ],
                [
                    'title' => 'Niveau 2 — Le commerçant et l\'acte de commerce',
                    'subtitle' => 'Qui est commerçant et que fait-il ?',
                    'xp_reward' => 105,
                    'content' => "🎯 **Objectif** : savoir qui est juridiquement commerçant et ce qu'est un acte de commerce.\n\n💡 Selon l'Acte uniforme sur le droit commercial général (AUDCG), est **commerçant** celui qui accomplit des actes de commerce et en fait sa profession habituelle.\n\nExemples d'**actes de commerce par nature** :\n• L'achat de biens pour les revendre (un grossiste à Douala)\n• Les opérations de banque, de bourse, d'assurance\n• Le transport et l'entreprise de services\n\n⚠️ Obligations du commerçant :\n• S'immatriculer au **RCCM** (Registre du Commerce et du Crédit Mobilier)\n• Tenir une comptabilité (système OHADA / SYSCOHADA)\n• Conserver les documents commerciaux\n\n✅ À retenir : un agriculteur qui vend sa propre récolte n'est pas commerçant (acte civil), mais celui qui achète des récoltes pour les revendre l'est. La qualité de commerçant entraîne des obligations mais aussi des protections (preuve par tous moyens entre commerçants).",
                    'questions' => [
                        ['question' => 'Selon l\'AUDCG, qui est commerçant ?', 'options' => ['Toute personne majeure', 'Celui qui accomplit des actes de commerce à titre de profession habituelle', 'Tout salarié d\'une entreprise', 'Le propriétaire d\'un immeuble'], 'correct' => [1], 'explanation' => 'Le commerçant exerce des actes de commerce de manière habituelle et professionnelle.'],
                        ['question' => 'Quel registre le commerçant doit-il rejoindre ?', 'options' => ['Le RCCM', 'Le registre foncier', 'Le registre d\'état civil', 'Le registre des associations'], 'correct' => [0], 'explanation' => 'Le RCCM (Registre du Commerce et du Crédit Mobilier) immatricule les commerçants.'],
                        ['question' => 'Lequel est un acte de commerce par nature ?', 'options' => ['Vendre sa maison familiale', 'Acheter des marchandises pour les revendre', 'Donner un cours bénévole', 'Hériter d\'un terrain'], 'correct' => [1], 'explanation' => 'L\'achat pour revente est l\'acte de commerce typique par nature.'],
                    ],
                ],
                [
                    'title' => 'Niveau 3 — Les formes de sociétés',
                    'subtitle' => 'SARL, SA, SAS, SNC... laquelle choisir ?',
                    'xp_reward' => 115,
                    'content' => "🎯 **Objectif** : distinguer les principales formes de sociétés prévues par l'Acte uniforme sur les sociétés commerciales (AUSCGIE).\n\n💡 Tableau comparatif :\n\n| Forme | Associés | Capital min. | Responsabilité |\n|-------|----------|--------------|----------------|\n| SARL | 1 ou + | librement fixé | limitée aux apports |\n| SA | 1 ou + | 10 000 000 FCFA | limitée aux apports |\n| SAS | 1 ou + | librement fixé | limitée aux apports |\n| SNC | 2 ou + | aucun | illimitée et solidaire |\n\n• La **SARL** est la forme la plus utilisée par les PME camerounaises : souplesse, responsabilité limitée.\n• La **SA** convient aux grandes structures avec conseil d'administration.\n• La **SNC** engage personnellement les associés sur leurs biens propres.\n\n⚠️ Choisir la forme, c'est arbitrer entre protection du patrimoine, crédibilité et coût de fonctionnement.\n\n✅ À retenir : la responsabilité limitée (SARL, SA, SAS) protège le patrimoine personnel ; la SNC l'expose totalement.",
                    'questions' => [
                        ['question' => 'Quel est le capital minimum légal d\'une SA dans la zone OHADA ?', 'options' => ['1 000 000 FCFA', '10 000 000 FCFA', 'Aucun minimum', '100 000 FCFA'], 'correct' => [1], 'explanation' => 'La SA exige un capital minimum de 10 000 000 FCFA selon l\'AUSCGIE.'],
                        ['question' => 'Dans quelle forme les associés sont-ils responsables indéfiniment et solidairement ?', 'options' => ['SARL', 'SA', 'SNC', 'SAS'], 'correct' => [2], 'explanation' => 'Dans la SNC, les associés répondent des dettes sur leurs biens personnels, solidairement.'],
                        ['question' => 'Quelles formes offrent une responsabilité limitée aux apports ? (plusieurs réponses)', 'options' => ['SARL', 'SNC', 'SA', 'SAS'], 'correct' => [0, 2, 3], 'explanation' => 'SARL, SA et SAS limitent la responsabilité aux apports ; la SNC ne le fait pas.'],
                    ],
                ],
                [
                    'title' => 'Niveau 4 — Créer son entreprise',
                    'subtitle' => 'Les étapes concrètes de constitution',
                    'xp_reward' => 125,
                    'content' => "🎯 **Objectif** : maîtriser les étapes pratiques de création d'une société au Cameroun.\n\n💡 Parcours type pour une SARL :\n• 1. Rédiger et signer les **statuts** (objet, capital, gérance, siège)\n• 2. Déposer le capital social en banque (attestation de versement)\n• 3. Enregistrer les statuts auprès des **impôts**\n• 4. S'immatriculer au **RCCM** via le Centre de Formalités de Création d'Entreprises (CFCE)\n• 5. Obtenir le **NIU** (Numéro d'Identifiant Unique) fiscal\n• 6. Publier un avis de constitution\n\n⚠️ Le CFCE / guichet unique permet de regrouper plusieurs démarches en un seul lieu, réduisant les délais.\n\n✅ À retenir : la société acquiert la **personnalité juridique** à compter de son immatriculation au RCCM, pas avant. Avant cela, les fondateurs agissent en leur nom propre. Les statuts sont la « constitution » de l'entreprise : ils fixent les règles du jeu entre associés.",
                    'questions' => [
                        ['question' => 'À quel moment la société acquiert-elle la personnalité juridique ?', 'options' => ['À la signature des statuts', 'À l\'immatriculation au RCCM', 'Au premier client', 'À l\'ouverture du compte bancaire'], 'correct' => [1], 'explanation' => 'La personnalité juridique naît de l\'immatriculation au RCCM.'],
                        ['question' => 'Que sont les statuts d\'une société ?', 'options' => ['Une autorisation municipale', 'Le contrat fixant les règles entre associés', 'Un relevé bancaire', 'Une déclaration fiscale annuelle'], 'correct' => [1], 'explanation' => 'Les statuts constituent le contrat fondateur définissant l\'organisation de la société.'],
                        ['question' => 'Quel numéro fiscal obtient-on lors de la création ?', 'options' => ['Le NIU', 'Le RCCM', 'Le CNPS', 'Le RIB'], 'correct' => [0], 'explanation' => 'Le NIU (Numéro d\'Identifiant Unique) est l\'identifiant fiscal de l\'entreprise.'],
                    ],
                ],
                [
                    'title' => 'Niveau 5 — Les contrats commerciaux',
                    'subtitle' => 'Formation, preuve et exécution',
                    'xp_reward' => 135,
                    'content' => "🎯 **Objectif** : comprendre la formation et la sécurisation des contrats entre professionnels.\n\n💡 Un contrat valable suppose quatre conditions :\n• Le **consentement** libre et éclairé\n• La **capacité** des parties\n• Un **objet** licite et déterminé\n• Une **cause** licite\n\nContrats commerciaux courants : vente commerciale (encadrée par l'AUDCG), bail commercial, contrat de distribution, contrat de transport.\n\n⚠️ Entre commerçants, la **preuve est libre** (factures, e-mails, témoins), contrairement aux actes civils qui exigent souvent un écrit au-delà d'un certain montant.\n\n✅ À retenir : pour sécuriser un contrat, prévoir :\n• Les obligations de chaque partie et les délais\n• Les modalités de paiement (virement, mobile money)\n• Une clause de **résolution** en cas d'inexécution\n• Une clause attributive de juridiction ou d'**arbitrage**\n\nUn écrit clair évite la majorité des litiges. En cas de manquement grave, le créancier peut demander la résolution du contrat et des dommages-intérêts.",
                    'questions' => [
                        ['question' => 'Comment se fait la preuve entre commerçants ?', 'options' => ['Uniquement par acte notarié', 'Par tous moyens', 'Seulement par témoins', 'Uniquement par écrit enregistré'], 'correct' => [1], 'explanation' => 'Entre commerçants, la preuve est libre : factures, e-mails, témoins sont admis.'],
                        ['question' => 'Quelle condition n\'est PAS requise pour la validité d\'un contrat ?', 'options' => ['Le consentement', 'La capacité', 'Un objet licite', 'L\'enregistrement systématique chez un huissier'], 'correct' => [3], 'explanation' => 'L\'enregistrement chez un huissier n\'est pas une condition de validité d\'un contrat.'],
                        ['question' => 'Quelle clause permet de mettre fin au contrat en cas d\'inexécution ?', 'options' => ['La clause de résolution', 'La clause de confidentialité', 'La clause de préambule', 'La clause de définition'], 'correct' => [0], 'explanation' => 'La clause de résolution permet d\'anéantir le contrat en cas de manquement grave.'],
                    ],
                ],
                [
                    'title' => 'Niveau 6 — Le fonds de commerce',
                    'subtitle' => 'Le patrimoine commercial et sa protection',
                    'xp_reward' => 145,
                    'content' => "🎯 **Objectif** : comprendre la nature du fonds de commerce et les opérations qui le concernent.\n\n💡 Le **fonds de commerce** est un ensemble d'éléments permettant d'exercer l'activité. Il comprend :\n\nÉléments **incorporels** :\n• La **clientèle** et l'achalandage (élément essentiel)\n• Le nom commercial et l'enseigne\n• Le droit au bail\n• Les brevets, marques, licences\n\nÉléments **corporels** :\n• Le matériel et l'outillage\n• Les marchandises\n\n⚠️ Sans clientèle, il n'y a pas de fonds de commerce : c'est l'élément central.\n\n✅ Opérations possibles :\n• La **cession** (vente) du fonds, qui obéit à des règles de publicité protégeant les créanciers\n• La **location-gérance**, où le propriétaire confie l'exploitation à un gérant moyennant une redevance\n• Le **nantissement**, qui permet de garantir un crédit sans se déposséder du fonds\n\nÀ retenir : la cession d'un fonds à Yaoundé doit être publiée pour informer les créanciers et purger les oppositions.",
                    'questions' => [
                        ['question' => 'Quel est l\'élément essentiel du fonds de commerce ?', 'options' => ['Le matériel', 'La clientèle', 'Les marchandises en stock', 'Le local'], 'correct' => [1], 'explanation' => 'La clientèle est l\'élément central : sans elle, il n\'y a pas de fonds de commerce.'],
                        ['question' => 'Qu\'est-ce que la location-gérance ?', 'options' => ['La vente définitive du fonds', 'La confier l\'exploitation à un gérant contre une redevance', 'L\'achat d\'un immeuble', 'Le licenciement des salariés'], 'correct' => [1], 'explanation' => 'En location-gérance, le propriétaire fait exploiter son fonds par un tiers contre redevance.'],
                        ['question' => 'Lesquels sont des éléments incorporels du fonds ? (plusieurs réponses)', 'options' => ['La clientèle', 'Le matériel d\'outillage', 'Le nom commercial', 'Le droit au bail'], 'correct' => [0, 2, 3], 'explanation' => 'Clientèle, nom commercial et droit au bail sont incorporels ; le matériel est corporel.'],
                    ],
                ],
                [
                    'title' => 'Niveau 7 — Le droit de la concurrence',
                    'subtitle' => 'Jouer le jeu loyalement sur le marché',
                    'xp_reward' => 155,
                    'content' => "🎯 **Objectif** : comprendre les règles qui encadrent la concurrence entre entreprises.\n\n💡 Le droit de la concurrence vise un marché loyal. Il distingue :\n\n**Pratiques anticoncurrentielles** (atteintes au marché) :\n• Les **ententes** illicites (fixation concertée des prix entre concurrents)\n• L'**abus de position dominante** (un acteur dominant qui écrase ses rivaux)\n\n**Concurrence déloyale** (atteintes entre concurrents) :\n• Le **dénigrement** d'un concurrent\n• La **confusion** (imiter l'enseigne ou les produits d'un rival)\n• Le **parasitisme** (profiter des investissements d'autrui)\n• Le **débauchage** abusif de salariés\n\n⚠️ La sanction de la concurrence déloyale repose sur la responsabilité civile : il faut prouver une faute, un préjudice et un lien de causalité.\n\n✅ À retenir : la liberté du commerce est le principe, mais elle s'arrête là où commence la déloyauté. Imiter le logo d'un concurrent à Douala pour tromper les clients est une faute sanctionnable par des dommages-intérêts et la cessation du trouble.",
                    'questions' => [
                        ['question' => 'Qu\'est-ce qu\'une entente illicite ?', 'options' => ['Un accord de fixation concertée des prix entre concurrents', 'Une publicité comparative', 'Un contrat de travail', 'Une baisse de prix unilatérale'], 'correct' => [0], 'explanation' => 'L\'entente illicite est un accord faussant la concurrence, comme la fixation concertée des prix.'],
                        ['question' => 'Sur quel fondement repose la sanction de la concurrence déloyale ?', 'options' => ['Le droit pénal exclusivement', 'La responsabilité civile (faute, préjudice, lien)', 'Le droit fiscal', 'Le droit administratif'], 'correct' => [1], 'explanation' => 'La concurrence déloyale se sanctionne via la responsabilité civile.'],
                        ['question' => 'Lequel est un acte de concurrence déloyale ?', 'options' => ['Baisser ses prix loyalement', 'Imiter l\'enseigne d\'un concurrent pour créer la confusion', 'Faire de la publicité honnête', 'Innover sur un produit'], 'correct' => [1], 'explanation' => 'Créer la confusion en imitant un concurrent est de la concurrence déloyale.'],
                    ],
                ],
                [
                    'title' => 'Niveau 8 — Le recouvrement des créances',
                    'subtitle' => 'Se faire payer, à l\'amiable ou en justice',
                    'xp_reward' => 165,
                    'content' => "🎯 **Objectif** : connaître les procédures pour recouvrer une créance impayée, encadrées par l'Acte uniforme sur les procédures simplifiées de recouvrement et voies d'exécution (AUPSRVE).\n\n💡 Deux grandes étapes :\n\n**Recouvrement amiable** :\n• Relance, mise en demeure de payer (lettre datée et signée)\n• Négociation d'un échéancier\n\n**Recouvrement judiciaire** :\n• L'**injonction de payer** : procédure rapide pour une créance certaine, liquide et exigible. Le juge rend une ordonnance que le débiteur peut contester par opposition.\n• L'**injonction de délivrer ou restituer** un bien meuble\n\n⚠️ Si le débiteur ne paie toujours pas, on passe aux **voies d'exécution** : saisie-vente, saisie-attribution de comptes bancaires, saisie des rémunérations.\n\n✅ À retenir : pour obtenir une injonction de payer, la créance doit être :\n• **certaine** (elle existe)\n• **liquide** (montant chiffré)\n• **exigible** (le terme est échu)\n\nUne facture impayée échue à Douala remplit ces trois conditions et ouvre la voie à l'injonction de payer.",
                    'questions' => [
                        ['question' => 'Quelle procédure rapide permet de recouvrer une créance certaine, liquide et exigible ?', 'options' => ['L\'injonction de payer', 'La saisie immobilière', 'Le redressement judiciaire', 'La liquidation'], 'correct' => [0], 'explanation' => 'L\'injonction de payer est la procédure simplifiée pour les créances certaines, liquides et exigibles.'],
                        ['question' => 'Que signifie une créance « exigible » ?', 'options' => ['Son montant est inconnu', 'Le terme de paiement est échu', 'Elle est contestée', 'Elle est prescrite'], 'correct' => [1], 'explanation' => 'Une créance exigible est arrivée à échéance et peut être réclamée.'],
                        ['question' => 'Quelles sont des voies d\'exécution ? (plusieurs réponses)', 'options' => ['La saisie-attribution de comptes', 'La saisie-vente', 'La mise en demeure', 'La saisie des rémunérations'], 'correct' => [0, 1, 3], 'explanation' => 'Saisie-attribution, saisie-vente et saisie des rémunérations sont des voies d\'exécution ; la mise en demeure est une étape amiable.'],
                    ],
                ],
                [
                    'title' => 'Niveau 9 — Les procédures collectives',
                    'subtitle' => 'Quand l\'entreprise est en difficulté',
                    'xp_reward' => 180,
                    'content' => "🎯 **Objectif** : comprendre les dispositifs prévus quand une entreprise ne peut plus payer ses dettes, régis par l'Acte uniforme sur les procédures collectives d'apurement du passif (AUPCAP).\n\n💡 Trois procédures principales, de la prévention à la disparition :\n\n• La **conciliation** : procédure préventive et confidentielle, avant la cessation des paiements, pour trouver un accord amiable avec les créanciers.\n• Le **règlement préventif** : pour une entreprise en difficulté mais pas encore en cessation des paiements ; un concordat préventif est proposé.\n• Le **redressement judiciaire** : l'entreprise est en cessation des paiements mais peut être sauvée ; un concordat de redressement organise l'apurement du passif.\n• La **liquidation des biens** : la situation est irrémédiablement compromise ; on vend l'actif pour payer les créanciers.\n\n⚠️ La **cessation des paiements** est l'incapacité de faire face au passif exigible avec l'actif disponible. C'est le critère qui fait basculer du préventif au curatif.\n\n✅ À retenir : redressement = on tente de sauver l'entreprise ; liquidation = on l'arrête et on partage. La déclaration de cessation des paiements doit intervenir dans un délai légal sous peine de sanctions pour le dirigeant.",
                    'questions' => [
                        ['question' => 'Qu\'est-ce que la cessation des paiements ?', 'options' => ['Une grève des salariés', 'L\'impossibilité de faire face au passif exigible avec l\'actif disponible', 'Un retard de TVA', 'Une baisse du chiffre d\'affaires'], 'correct' => [1], 'explanation' => 'La cessation des paiements est l\'incapacité de payer le passif exigible avec l\'actif disponible.'],
                        ['question' => 'Quelle procédure vise à sauver une entreprise déjà en cessation des paiements mais redressable ?', 'options' => ['La liquidation des biens', 'Le redressement judiciaire', 'La conciliation', 'La dissolution amiable'], 'correct' => [1], 'explanation' => 'Le redressement judiciaire cherche à sauver l\'entreprise en cessation des paiements mais viable.'],
                        ['question' => 'Quelle procédure intervient quand la situation est irrémédiablement compromise ?', 'options' => ['La conciliation', 'Le règlement préventif', 'La liquidation des biens', 'Le redressement judiciaire'], 'correct' => [2], 'explanation' => 'La liquidation des biens consiste à vendre l\'actif pour payer les créanciers quand le sauvetage est impossible.'],
                    ],
                ],
                [
                    'title' => 'Niveau 10 — Synthèse OHADA et vision d\'ensemble',
                    'subtitle' => 'Relier tous les Actes uniformes',
                    'xp_reward' => 200,
                    'content' => "🎯 **Objectif** : consolider une vision d'ensemble du droit OHADA et de son rôle dans l'intégration économique africaine.\n\n💡 L'**OHADA** (Organisation pour l'Harmonisation en Afrique du Droit des Affaires) regroupe 17 États et harmonise le droit pour sécuriser les investissements. Ses principaux Actes uniformes :\n• **AUDCG** : droit commercial général (commerçant, RCCM, vente)\n• **AUSCGIE** : sociétés commerciales (SARL, SA, SAS)\n• **AUS** : sûretés (garanties, cautionnement, nantissement)\n• **AUPSRVE** : recouvrement et voies d'exécution\n• **AUPCAP** : procédures collectives\n• **AUDCIF** : comptabilité (SYSCOHADA)\n\n⚠️ L'**arbitrage** OHADA et la CCJA offrent un règlement des litiges fiable, atout majeur pour attirer les investisseurs.\n\n✅ À retenir : tous ces textes forment un système cohérent qui accompagne l'entreprise de sa naissance (création, RCCM) à ses difficultés (procédures collectives).\n\n🏆 **Félicitations !** Tu maîtrises désormais les fondamentaux du droit des affaires en zone OHADA. Débouchés : juriste d'entreprise, conseil en création d'entreprise, assistant juridique, paralegal, gestionnaire de contrats, ou entrepreneur averti. Tu peux poursuivre vers le droit fiscal, le droit du travail OHADA ou une spécialisation en contentieux des affaires. Bonne continuation dans ta carrière juridique !",
                    'questions' => [
                        ['question' => 'Que signifie le sigle OHADA ?', 'options' => ['Organisation Humanitaire d\'Afrique', 'Organisation pour l\'Harmonisation en Afrique du Droit des Affaires', 'Office des Halles et du Domaine Agricole', 'Organe Hiérarchique des Avocats'], 'correct' => [1], 'explanation' => 'OHADA signifie Organisation pour l\'Harmonisation en Afrique du Droit des Affaires.'],
                        ['question' => 'Quel Acte uniforme régit les sociétés commerciales ?', 'options' => ['L\'AUSCGIE', 'L\'AUPCAP', 'L\'AUPSRVE', 'L\'AUDCIF'], 'correct' => [0], 'explanation' => 'L\'AUSCGIE encadre les sociétés commerciales et le groupement d\'intérêt économique.'],
                        ['question' => 'Quel est un atout majeur de l\'OHADA pour les investisseurs ?', 'options' => ['L\'augmentation des impôts', 'La sécurité juridique et l\'arbitrage devant la CCJA', 'L\'interdiction du commerce', 'La suppression des sociétés'], 'correct' => [1], 'explanation' => 'L\'harmonisation et l\'arbitrage OHADA offrent une sécurité juridique attractive pour les investissements.'],
                    ],
                ],
            ],
        ]);

        $this->command->info('  ✓ Roadmap Droit des Affaires créée (10 niveaux).');
    }
}
