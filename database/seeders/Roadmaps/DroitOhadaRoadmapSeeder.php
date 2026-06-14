<?php

namespace Database\Seeders\Roadmaps;

use Illuminate\Database\Seeder;

/**
 * Roadmap Droit OHADA — comprendre et appliquer le droit des affaires harmonisé en Afrique.
 */
class DroitOhadaRoadmapSeeder extends Seeder
{
    use RoadmapSeederHelper;

    public function run(): void
    {
        $this->createRoadmap([
            'title' => 'Maîtrise le Droit OHADA (Afrique)',
            'slug' => 'droit-ohada',
            'domain' => 'juridique',
            'description' => "Une formation pratique pour comprendre le droit des affaires harmonisé en Afrique : de l'organisation de l'OHADA jusqu'à l'arbitrage devant la CCJA. Pensée pour les entrepreneurs, juristes débutants, comptables et gestionnaires du Cameroun et de l'espace OHADA, elle relie chaque notion à des situations concrètes : créer une SARL à Douala, recouvrer une créance, sécuriser un crédit ou gérer une entreprise en difficulté.",
            'objectives' => "Comprendre l'origine, le rôle et l'organisation de l'OHADA\nIdentifier les Actes uniformes et leur force juridique\nMaîtriser les règles du droit commercial général (commerçant, RCCM, bail, fonds de commerce)\nChoisir et constituer la bonne forme de société (SARL, SA, SAS)\nUtiliser les sûretés pour sécuriser un crédit\nMettre en œuvre le recouvrement, les voies d'exécution, les procédures collectives et l'arbitrage CCJA",
            'icon' => '⚖️',
            'color' => '#1D4ED8',
            'difficulty' => 'intermediate',
            'required_packs' => [],
            'pass_threshold' => 70,
            'order' => 20,
            'levels' => [
                [
                    'title' => 'Niveau 1 — Qu\'est-ce que l\'OHADA ?',
                    'subtitle' => 'Origine, objectifs et raison d\'être',
                    'xp_reward' => 100,
                    'content' => "🎯 **Objectif** : comprendre pourquoi l'OHADA existe et ce qu'elle apporte aux entreprises.\n\nL'OHADA signifie **Organisation pour l'Harmonisation en Afrique du Droit des Affaires**. Elle est née du **Traité de Port-Louis (Île Maurice) signé le 17 octobre 1993**, révisé à Québec en 2008.\n\n💡 Le problème de départ : chaque pays africain avait son propre droit des affaires, souvent ancien et incertain. Cette insécurité juridique décourageait l'investissement.\n\n✅ Les objectifs de l'OHADA :\n• Harmoniser le droit des affaires entre pays membres\n• Garantir la **sécurité juridique et judiciaire**\n• Favoriser les investissements et le développement économique\n• Promouvoir l'arbitrage pour régler les litiges\n\n⚠️ À retenir : « harmonisation » ne veut pas dire un droit différent par pays. Les Actes uniformes s'appliquent **directement** dans tous les États membres, de la même manière. Un contrat commercial à Douala obéit aux mêmes règles qu'à Abidjan ou à Dakar.",
                    'questions' => [
                        ['question' => 'Que signifie le sigle OHADA ?', 'options' => ['Organisation Harmonisée des Avocats d\'Afrique', 'Organisation pour l\'Harmonisation en Afrique du Droit des Affaires', 'Office Halal du Développement Africain', 'Organe de Haute Autorité du Droit Administratif'], 'correct' => [1], 'explanation' => 'OHADA = Organisation pour l\'Harmonisation en Afrique du Droit des Affaires.'],
                        ['question' => 'Quel est l\'objectif principal de l\'OHADA ?', 'options' => ['Créer une monnaie unique africaine', 'Garantir la sécurité juridique et favoriser l\'investissement', 'Remplacer les constitutions nationales', 'Gérer les frontières douanières'], 'correct' => [1], 'explanation' => 'L\'OHADA vise la sécurité juridique des affaires pour attirer les investissements.'],
                        ['question' => 'Par quel texte fondateur l\'OHADA a-t-elle été créée ?', 'options' => ['La Charte de l\'Union Africaine', 'Le Traité de Port-Louis de 1993', 'Les Accords de Bretton Woods', 'La Convention de Genève'], 'correct' => [1], 'explanation' => 'L\'OHADA a été instituée par le Traité de Port-Louis signé en 1993, révisé en 2008.'],
                    ],
                ],
                [
                    'title' => 'Niveau 2 — Les États membres et les institutions',
                    'subtitle' => 'Qui adhère et qui dirige l\'OHADA',
                    'xp_reward' => 110,
                    'content' => "🎯 **Objectif** : connaître l'étendue géographique de l'OHADA et ses institutions.\n\nL'OHADA regroupe **17 États membres** (situation actuelle), majoritairement francophones d'Afrique de l'Ouest et centrale, mais aussi des pays comme la Guinée équatoriale (hispanophone) ou la RD Congo (qui a adhéré en 2012).\n\n💡 Exemples de membres : Cameroun, Côte d'Ivoire, Sénégal, Mali, Bénin, Togo, Gabon, Congo, Tchad, RDC, etc.\n\n✅ Les institutions clés :\n• **La Conférence des Chefs d'État** : orientation politique\n• **Le Conseil des Ministres** : adopte les Actes uniformes\n• **Le Secrétariat Permanent** (à Yaoundé, Cameroun) : prépare les textes et coordonne\n• **L'ERSUMA** (Porto-Novo, Bénin) : école de formation des magistrats et auxiliaires de justice\n• **La CCJA** (Abidjan, Côte d'Ivoire) : Cour Commune de Justice et d'Arbitrage, juge suprême du droit OHADA\n\n⚠️ Fierté camerounaise : le **Secrétariat Permanent siège à Yaoundé**. La CCJA, elle, siège à Abidjan.",
                    'questions' => [
                        ['question' => 'Où se trouve le siège du Secrétariat Permanent de l\'OHADA ?', 'options' => ['Abidjan', 'Yaoundé', 'Dakar', 'Porto-Novo'], 'correct' => [1], 'explanation' => 'Le Secrétariat Permanent de l\'OHADA siège à Yaoundé, au Cameroun.'],
                        ['question' => 'Quelle institution adopte les Actes uniformes ?', 'options' => ['La Conférence des Chefs d\'État', 'Le Conseil des Ministres', 'La CCJA', 'L\'ERSUMA'], 'correct' => [1], 'explanation' => 'Les Actes uniformes sont adoptés par le Conseil des Ministres de l\'OHADA.'],
                        ['question' => 'Parmi ces propositions, lesquelles sont des institutions de l\'OHADA ?', 'options' => ['La CCJA', 'La Banque Mondiale', 'L\'ERSUMA', 'Le Secrétariat Permanent'], 'correct' => [0, 2, 3], 'explanation' => 'CCJA, ERSUMA et Secrétariat Permanent sont des institutions OHADA ; la Banque Mondiale n\'en fait pas partie.'],
                    ],
                ],
                [
                    'title' => 'Niveau 3 — Les Actes uniformes',
                    'subtitle' => 'La source du droit OHADA et sa force',
                    'xp_reward' => 120,
                    'content' => "🎯 **Objectif** : comprendre ce qu'est un Acte uniforme et sa place dans la hiérarchie des normes.\n\nUn **Acte uniforme (AU)** est un texte de loi commun adopté par le Conseil des Ministres. Il s'applique **directement et obligatoirement** dans tous les États membres, sans besoin de loi nationale de transposition.\n\n💡 Principe fondamental : les Actes uniformes sont **supranationaux**. Ils l'emportent sur les lois nationales contraires, antérieures comme postérieures.\n\n✅ Principaux Actes uniformes :\n• AU Droit Commercial Général (AUDCG)\n• AU relatif aux Sociétés Commerciales et au GIE (AUSCGIE)\n• AU portant organisation des Sûretés\n• AU sur le Recouvrement et les Voies d'Exécution (AUPSRVE)\n• AU portant Procédures Collectives d'Apurement du Passif\n• AU sur le Droit de l'Arbitrage\n• AU relatif au Droit Comptable et à l'information financière (SYSCOHADA)\n• AU sur le Transport de Marchandises par Route\n\n⚠️ Conséquence pratique : un commerçant de Douala ne peut pas invoquer une loi camerounaise contraire à un Acte uniforme. C'est l'AU qui s'impose.",
                    'questions' => [
                        ['question' => 'Un Acte uniforme nécessite-t-il une loi nationale pour s\'appliquer ?', 'options' => ['Oui, chaque pays doit le transposer', 'Non, il s\'applique directement et obligatoirement', 'Seulement dans les pays francophones', 'Uniquement après référendum'], 'correct' => [1], 'explanation' => 'Les Actes uniformes s\'appliquent directement, sans transposition nationale.'],
                        ['question' => 'En cas de conflit entre un Acte uniforme et une loi nationale contraire, lequel l\'emporte ?', 'options' => ['La loi nationale', 'L\'Acte uniforme', 'Le juge choisit librement', 'Aucun, le contrat est annulé'], 'correct' => [1], 'explanation' => 'L\'Acte uniforme, supranational, prime sur la loi nationale contraire.'],
                        ['question' => 'Lequel de ces textes est un Acte uniforme OHADA ?', 'options' => ['Le Code de la route national', 'L\'Acte uniforme sur les Sûretés', 'La loi de finances annuelle', 'Le Code pénal national'], 'correct' => [1], 'explanation' => 'L\'Acte uniforme portant organisation des Sûretés est un texte OHADA.'],
                    ],
                ],
                [
                    'title' => 'Niveau 4 — Le droit commercial général : le commerçant et le RCCM',
                    'subtitle' => 'Statut de commerçant et immatriculation',
                    'xp_reward' => 130,
                    'content' => "🎯 **Objectif** : savoir qui est commerçant et pourquoi l'immatriculation est essentielle.\n\nSelon l'AUDCG, est **commerçant** celui qui accomplit des **actes de commerce** et en fait sa **profession habituelle** (ex : achat pour revendre, transport, courtage).\n\n💡 Le **RCCM** = Registre du Commerce et du Crédit Mobilier. Toute personne qui veut exercer le commerce doit s'y immatriculer.\n\n✅ Pourquoi s'immatriculer :\n• Cela donne une **existence légale** au commerçant ou à la société\n• Cela crée une **présomption de qualité de commerçant**\n• Cela rend l'activité opposable aux tiers et permet d'ouvrir un compte, signer des marchés, obtenir un crédit\n\n✅ L'**entreprenant** : un statut simplifié pour les petits acteurs (vendeurs de marché, artisans) qui font une simple déclaration au lieu d'une immatriculation complète.\n\n⚠️ Conditions pour être commerçant : avoir la capacité juridique. Un mineur non émancipé ou une personne frappée d'incompatibilité (fonctionnaire, certaines professions) ne peut pas être commerçant.\n\nExemple : Aïcha ouvre une boutique de pagnes à Douala ; elle s'immatricule au RCCM pour obtenir des financements bancaires.",
                    'questions' => [
                        ['question' => 'Que signifie le sigle RCCM ?', 'options' => ['Registre Central des Coopératives Maritimes', 'Registre du Commerce et du Crédit Mobilier', 'Régime Commun de Contrôle Monétaire', 'Répertoire des Contrats Commerciaux Mensuels'], 'correct' => [1], 'explanation' => 'RCCM = Registre du Commerce et du Crédit Mobilier.'],
                        ['question' => 'Qu\'est-ce qui caractérise le commerçant selon l\'OHADA ?', 'options' => ['Posséder une voiture', 'Accomplir des actes de commerce à titre de profession habituelle', 'Habiter en ville', 'Avoir plus de 18 ans seulement'], 'correct' => [1], 'explanation' => 'Le commerçant accomplit des actes de commerce comme profession habituelle.'],
                        ['question' => 'Quel statut simplifié l\'OHADA prévoit-elle pour les petits acteurs économiques ?', 'options' => ['Le salarié', 'L\'entreprenant', 'Le fonctionnaire', 'Le rentier'], 'correct' => [1], 'explanation' => 'L\'entreprenant est un statut simplifié reposant sur une déclaration d\'activité.'],
                    ],
                ],
                [
                    'title' => 'Niveau 5 — Fonds de commerce et bail commercial',
                    'subtitle' => 'Les outils de l\'activité commerciale',
                    'xp_reward' => 140,
                    'content' => "🎯 **Objectif** : maîtriser deux notions clés du commerce : le fonds de commerce et le bail commercial.\n\nLe **fonds de commerce** est un ensemble de biens qu'un commerçant réunit pour attirer une clientèle. Il comprend :\n• Des éléments **incorporels** : la clientèle (le plus important), l'enseigne, le nom commercial, les droits de propriété intellectuelle, le droit au bail\n• Des éléments **corporels** : le matériel, les marchandises, le mobilier\n\n💡 La **clientèle (l'achalandage)** est l'élément central : sans clientèle, il n'y a pas de fonds de commerce.\n\n✅ Le fonds peut être **vendu, loué (location-gérance) ou donné en garantie** (nantissement).\n\n✅ Le **bail commercial** protège fortement le locataire commerçant :\n• Durée minimale et **droit au renouvellement** du bail\n• En cas de refus de renouvellement injustifié, le bailleur doit verser une **indemnité d'éviction**\n\n⚠️ Exemple concret : M. Etoa loue un local au marché Mokolo à Yaoundé pour sa quincaillerie. Grâce au bail commercial OHADA, il bénéficie du droit au renouvellement et ne peut être expulsé sans indemnité s'il a respecté ses obligations.",
                    'questions' => [
                        ['question' => 'Quel est l\'élément le plus important du fonds de commerce ?', 'options' => ['Le matériel', 'La clientèle', 'Le mobilier', 'Les marchandises'], 'correct' => [1], 'explanation' => 'La clientèle est l\'élément central : sans elle, pas de fonds de commerce.'],
                        ['question' => 'Que protège principalement le bail commercial OHADA ?', 'options' => ['Le droit du bailleur d\'augmenter le loyer librement', 'Le droit au renouvellement du locataire commerçant', 'L\'interdiction de sous-louer', 'Le droit de propriété du bailleur sur les marchandises'], 'correct' => [1], 'explanation' => 'Le bail commercial garantit notamment le droit au renouvellement du locataire.'],
                        ['question' => 'Parmi ces éléments, lesquels sont des éléments incorporels du fonds de commerce ?', 'options' => ['La clientèle', 'Le mobilier', 'Le nom commercial', 'Les marchandises'], 'correct' => [0, 2], 'explanation' => 'La clientèle et le nom commercial sont incorporels ; le mobilier et les marchandises sont corporels.'],
                    ],
                ],
                [
                    'title' => 'Niveau 6 — Les sociétés commerciales : SARL',
                    'subtitle' => 'Constitution et fonctionnement de la SARL',
                    'xp_reward' => 150,
                    'content' => "🎯 **Objectif** : comprendre la forme de société la plus utilisée en Afrique : la SARL.\n\nLa **SARL** (Société À Responsabilité Limitée) est régie par l'AUSCGIE. C'est la forme préférée des PME car simple et protectrice.\n\n💡 Caractéristiques essentielles :\n• Les associés ne supportent les pertes qu'à hauteur de leurs **apports** (responsabilité limitée)\n• Le capital est divisé en **parts sociales**\n• Elle peut être constituée par **une seule personne** (SARL unipersonnelle)\n\n✅ Points clés depuis la réforme de 2014 :\n• Le **capital minimum est librement fixé par les statuts** (chaque État peut en décider ; au Cameroun, il peut être très faible)\n• Les statuts peuvent être établis par **acte sous seing privé**, sans notaire obligatoire dans certains cas\n• La société est gérée par un ou plusieurs **gérants**\n\n✅ Exemple type de capital social symbolique :\n```\nCapital social : 1 000 000 FCFA\nDivisé en 100 parts de 10 000 FCFA chacune\nAssocié A : 60 parts (60%)\nAssocié B : 40 parts (40%)\n```\n\n⚠️ La cession de parts à un tiers étranger nécessite en principe l'**agrément** des autres associés : on n'entre pas dans une SARL sans l'accord du groupe.",
                    'questions' => [
                        ['question' => 'Dans une SARL, jusqu\'à quelle hauteur les associés supportent-ils les pertes ?', 'options' => ['Sur tout leur patrimoine personnel', 'À hauteur de leurs apports', 'À hauteur du chiffre d\'affaires', 'Aucune responsabilité'], 'correct' => [1], 'explanation' => 'Dans une SARL, la responsabilité est limitée au montant des apports.'],
                        ['question' => 'Une SARL peut-elle être constituée par une seule personne ?', 'options' => ['Non, jamais', 'Oui, c\'est la SARL unipersonnelle', 'Seulement si l\'associé est étranger', 'Uniquement avec un notaire à l\'étranger'], 'correct' => [1], 'explanation' => 'L\'OHADA admet la SARL unipersonnelle constituée par un associé unique.'],
                        ['question' => 'Comment le capital d\'une SARL est-il divisé ?', 'options' => ['En actions', 'En parts sociales', 'En obligations', 'En coupons'], 'correct' => [1], 'explanation' => 'Le capital de la SARL est divisé en parts sociales (les actions concernent la SA/SAS).'],
                    ],
                ],
                [
                    'title' => 'Niveau 7 — SA et SAS : les sociétés par actions',
                    'subtitle' => 'Grandes structures et souplesse contractuelle',
                    'xp_reward' => 160,
                    'content' => "🎯 **Objectif** : distinguer la SA de la SAS et savoir quand les utiliser.\n\nLa **SA (Société Anonyme)** est destinée aux grandes entreprises et à celles qui veulent lever des fonds importants.\n• Capital divisé en **actions**\n• Capital minimum : **10 000 000 FCFA** (ou 100 000 000 FCFA si appel public à l'épargne)\n• Direction : Conseil d'Administration + Directeur Général, ou Administrateur Général (si moins de 3 actionnaires)\n• Responsabilité limitée aux apports\n\n💡 La **SAS (Société par Actions Simplifiée)**, introduite par la réforme de 2014, offre une **grande liberté statutaire**.\n• Les associés organisent librement le fonctionnement dans les statuts\n• Pas de capital minimum légal imposé par l'AU (fixé par les statuts)\n• Idéale pour les start-ups, joint-ventures et montages flexibles\n\n✅ Tableau comparatif simplifié :\n| Critère | SARL | SA | SAS |\n|---|---|---|---|\n| Titres | Parts sociales | Actions | Actions |\n| Capital min. | Libre | 10M FCFA | Libre |\n| Souplesse | Moyenne | Faible | Très forte |\n\n⚠️ Choix stratégique : une start-up tech à Douala cherchant des investisseurs préférera souvent la **SAS** pour sa flexibilité, tandis qu'une banque sera une **SA**.",
                    'questions' => [
                        ['question' => 'Quel est le capital minimum d\'une SA (sans appel public à l\'épargne) ?', 'options' => ['1 000 000 FCFA', '10 000 000 FCFA', '100 000 FCFA', 'Aucun minimum'], 'correct' => [1], 'explanation' => 'Le capital minimum d\'une SA est de 10 000 000 FCFA.'],
                        ['question' => 'Quel est le principal atout de la SAS ?', 'options' => ['Son capital minimum très élevé', 'Sa grande liberté d\'organisation statutaire', 'L\'absence de responsabilité limitée', 'L\'interdiction d\'avoir des investisseurs'], 'correct' => [1], 'explanation' => 'La SAS se distingue par une très grande souplesse statutaire.'],
                        ['question' => 'Quelles sociétés ont un capital divisé en actions ?', 'options' => ['La SARL', 'La SA', 'La SAS', 'La société en nom collectif'], 'correct' => [1, 2], 'explanation' => 'La SA et la SAS sont des sociétés par actions ; la SARL utilise des parts sociales.'],
                    ],
                ],
                [
                    'title' => 'Niveau 8 — Les sûretés',
                    'subtitle' => 'Sécuriser un crédit et garantir une créance',
                    'xp_reward' => 170,
                    'content' => "🎯 **Objectif** : comprendre comment garantir le paiement d'une dette grâce aux sûretés.\n\nUne **sûreté** est une garantie qui sécurise le créancier en cas de non-paiement du débiteur. L'AU sur les Sûretés les classe en deux grandes familles.\n\n💡 **Sûretés personnelles** : une autre personne s'engage à payer.\n• Le **cautionnement** : la caution paie si le débiteur principal ne paie pas\n• La **garantie autonome** : engagement indépendant du contrat de base\n\n💡 **Sûretés réelles** : un bien garantit la dette.\n• Le **gage** : porte sur un bien meuble avec ou sans dépossession\n• Le **nantissement** : porte sur des biens incorporels (fonds de commerce, parts sociales, créances)\n• L'**hypothèque** : porte sur un immeuble\n• La **réserve de propriété** : le vendeur reste propriétaire jusqu'au paiement complet\n\n✅ Innovation importante : l'**agent des sûretés** centralise la gestion des garanties pour plusieurs créanciers (utile dans les financements bancaires syndiqués).\n\n⚠️ Exemple : une coopérative agricole de l'Ouest Cameroun emprunte à la banque ; elle donne en **hypothèque** son entrepôt et en **nantissement** son fonds de commerce. Si elle ne rembourse pas, la banque peut faire vendre ces biens pour se payer.",
                    'questions' => [
                        ['question' => 'Quelle sûreté porte sur un immeuble ?', 'options' => ['Le gage', 'L\'hypothèque', 'Le cautionnement', 'La garantie autonome'], 'correct' => [1], 'explanation' => 'L\'hypothèque est la sûreté réelle qui porte sur un bien immeuble.'],
                        ['question' => 'Le cautionnement est une sûreté de quel type ?', 'options' => ['Réelle', 'Personnelle', 'Fiscale', 'Pénale'], 'correct' => [1], 'explanation' => 'Le cautionnement est une sûreté personnelle : une personne s\'engage à payer.'],
                        ['question' => 'Parmi ces garanties, lesquelles sont des sûretés réelles ?', 'options' => ['Le gage', 'Le cautionnement', 'L\'hypothèque', 'La garantie autonome'], 'correct' => [0, 2], 'explanation' => 'Le gage et l\'hypothèque portent sur des biens (sûretés réelles) ; le cautionnement et la garantie autonome sont personnels.'],
                    ],
                ],
                [
                    'title' => 'Niveau 9 — Recouvrement, voies d\'exécution et procédures collectives',
                    'subtitle' => 'Récupérer son dû et gérer la difficulté',
                    'xp_reward' => 185,
                    'content' => "🎯 **Objectif** : savoir comment forcer le paiement et ce qui se passe quand une entreprise est en difficulté.\n\n**1) Le recouvrement (AUPSRVE)** propose une procédure rapide :\n• L'**injonction de payer** : pour une créance certaine, liquide et exigible, le créancier obtient du juge une ordonnance enjoignant au débiteur de payer\n• L'**injonction de délivrer ou restituer** : pour récupérer un bien meuble\n\n💡 **2) Les voies d'exécution** : si le débiteur ne paie toujours pas, on exécute sur ses biens.\n• La **saisie-attribution** (sur des sommes, ex : compte bancaire)\n• La **saisie-vente** (biens meubles vendus aux enchères)\n• La **saisie immobilière** (vente d'un immeuble)\n• La **saisie conservatoire** : bloquer un bien en urgence avant le jugement\n\n✅ **3) Les procédures collectives** s'appliquent quand l'entreprise est en difficulté ou en cessation des paiements :\n• La **conciliation** et le **règlement préventif** : pour anticiper avant la cessation des paiements\n• Le **redressement judiciaire** : sauver une entreprise viable\n• La **liquidation des biens** : quand le redressement est impossible\n\n⚠️ La **cessation des paiements** = l'entreprise ne peut plus faire face à son passif exigible avec son actif disponible. Le dirigeant doit la déclarer, sinon il engage sa responsabilité.",
                    'questions' => [
                        ['question' => 'Quelle procédure permet d\'obtenir rapidement un titre pour une créance certaine, liquide et exigible ?', 'options' => ['La liquidation des biens', 'L\'injonction de payer', 'La conciliation', 'L\'arbitrage'], 'correct' => [1], 'explanation' => 'L\'injonction de payer est la procédure rapide pour une créance certaine, liquide et exigible.'],
                        ['question' => 'Que désigne la « cessation des paiements » ?', 'options' => ['Un retard de quelques jours', 'L\'impossibilité de faire face au passif exigible avec l\'actif disponible', 'Une grève des salariés', 'Une baisse du chiffre d\'affaires'], 'correct' => [1], 'explanation' => 'La cessation des paiements est l\'incapacité à payer le passif exigible avec l\'actif disponible.'],
                        ['question' => 'Parmi ces mesures, lesquelles sont des voies d\'exécution OHADA ?', 'options' => ['La saisie-attribution', 'Le cautionnement', 'La saisie-vente', 'La conciliation'], 'correct' => [0, 2], 'explanation' => 'La saisie-attribution et la saisie-vente sont des voies d\'exécution ; le cautionnement est une sûreté et la conciliation une procédure préventive.'],
                    ],
                ],
                [
                    'title' => 'Niveau 10 — L\'arbitrage et la CCJA',
                    'subtitle' => 'Régler les litiges et couronner ton parcours',
                    'xp_reward' => 200,
                    'content' => "🎯 **Objectif** : maîtriser l'arbitrage OHADA et le double rôle de la CCJA.\n\nL'**arbitrage** permet de régler un litige hors des tribunaux étatiques : les parties confient leur différend à un ou plusieurs **arbitres** dont la décision (la **sentence arbitrale**) s'impose à elles.\n\n💡 Pourquoi l'arbitrage séduit les entreprises :\n• **Rapidité** et **confidentialité**\n• Neutralité (utile entre partenaires de pays différents)\n• La sentence est **exécutoire** dans tout l'espace OHADA\n\n✅ La **CCJA (Cour Commune de Justice et d'Arbitrage)**, à Abidjan, a un **double rôle** :\n• **Rôle judiciaire** : elle est la **juridiction suprême** pour l'interprétation et l'application des Actes uniformes. Elle remplace les cours de cassation nationales sur ces matières et assure l'unité d'interprétation\n• **Rôle d'arbitrage** : elle administre des procédures d'arbitrage et confère l'**exequatur** aux sentences\n\n⚠️ Point clé : grâce à la CCJA, le droit OHADA est interprété de la **même façon partout**, ce qui renforce la sécurité juridique.\n\n🏆 **Félicitations !** Tu as terminé la roadmap Droit OHADA. Tu comprends désormais l'architecture du droit des affaires africain : OHADA, Actes uniformes, sociétés, sûretés, recouvrement, procédures collectives et arbitrage.\n\n🚀 **Débouchés et évolution** : juriste d'entreprise, conseil juridique, assistant juridique, paralegal, formateur, consultant en création d'entreprise, comptable maîtrisant le cadre légal, entrepreneur averti. Ces compétences sont très recherchées dans les cabinets, banques, ONG et PME de l'espace OHADA. Continue en te spécialisant (droit des sociétés, contentieux, fiscalité) et en suivant la jurisprudence de la CCJA. Bravo et bonne carrière !",
                    'questions' => [
                        ['question' => 'Où siège la CCJA ?', 'options' => ['Yaoundé', 'Abidjan', 'Dakar', 'Cotonou'], 'correct' => [1], 'explanation' => 'La Cour Commune de Justice et d\'Arbitrage (CCJA) siège à Abidjan.'],
                        ['question' => 'Comment appelle-t-on la décision rendue par les arbitres ?', 'options' => ['Un arrêt', 'Une sentence arbitrale', 'Une ordonnance', 'Un décret'], 'correct' => [1], 'explanation' => 'La décision des arbitres est une sentence arbitrale.'],
                        ['question' => 'Quels sont les rôles de la CCJA ?', 'options' => ['Juridiction suprême pour les Actes uniformes', 'Émission de la monnaie', 'Administration de procédures d\'arbitrage', 'Vote des lois nationales'], 'correct' => [0, 2], 'explanation' => 'La CCJA a un double rôle : juridiction suprême du droit OHADA et centre d\'arbitrage.'],
                    ],
                ],
            ],
        ]);

        $this->command->info('  ✓ Roadmap Droit OHADA créée (10 niveaux).');
    }
}
