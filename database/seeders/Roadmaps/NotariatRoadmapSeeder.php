<?php

namespace Database\Seeders\Roadmaps;

use Illuminate\Database\Seeder;

/**
 * Roadmap Notariat — devenir notaire : officier public, authenticité des actes, ventes, successions, donations et déontologie.
 */
class NotariatRoadmapSeeder extends Seeder
{
    use RoadmapSeederHelper;

    public function run(): void
    {
        $this->createRoadmap([
            'title' => 'Deviens Notaire',
            'slug' => 'notariat',
            'domain' => 'juridique',
            'description' => "Découvre le métier de notaire, officier public chargé d'authentifier les actes et de sécuriser les transactions. Cette roadmap couvre le rôle du notaire, la force de l'acte authentique, la vente immobilière, les successions, les donations, les régimes matrimoniaux, la déontologie et la conservation des actes, avec un ancrage dans le contexte OHADA et camerounais (Douala, Yaoundé).",
            'objectives' => "Comprendre le statut d'officier public du notaire\nMaîtriser la notion et la force de l'acte authentique\nSécuriser une vente immobilière de la promesse à la publicité foncière\nRégler une succession et calculer les droits des héritiers\nRédiger donations et contrats de mariage selon le régime choisi\nAppliquer la déontologie, le secret professionnel et les règles de conservation des actes",
            'icon' => '⚖️',
            'color' => '#1D4ED8',
            'difficulty' => 'advanced',
            'required_packs' => [],
            'pass_threshold' => 70,
            'order' => 20,
            'levels' => [
                [
                    'title' => 'Niveau 1 — Le notaire, officier public',
                    'subtitle' => "Statut, missions et délégation de l'autorité publique",
                    'xp_reward' => 100,
                    'content' => "🎯 **Objectif** : comprendre qui est le notaire et d'où vient son pouvoir.\n\n💡 Le notaire est un **officier public** : l'État lui délègue une parcelle de l'autorité publique pour **conférer l'authenticité** aux actes qu'il reçoit. Il exerce une profession libérale, mais investi d'une mission de service public.\n\nSes trois grandes missions :\n• **Authentifier** les actes (donner force probante et exécutoire)\n• **Conseiller** les parties de façon impartiale\n• **Conserver** les actes (les minutes) sur de longues années\n\n⚠️ Le notaire n'est pas un simple rédacteur : il engage sa responsabilité et doit assurer **l'efficacité** de l'acte (devoir de conseil, vérifications préalables).\n\n✅ Au Cameroun comme dans l'espace OHADA, le notaire intervient pour la constitution des sociétés, les ventes immobilières, les successions et les actes de la vie familiale. Il est nommé par l'autorité publique et prête serment.\n\nÀ Douala ou Yaoundé, l'office notarial est un point de passage obligé pour sécuriser un patrimoine.",
                    'questions' => [
                        ['question' => "Quel est le statut juridique du notaire ?", 'options' => ['Un simple commerçant', 'Un officier public délégataire de l\'autorité publique', 'Un fonctionnaire salarié de l\'État', 'Un magistrat du siège'], 'correct' => [1], 'explanation' => "Le notaire est un officier public à qui l'État délègue le pouvoir de conférer l'authenticité aux actes."],
                        ['question' => "Parmi ces missions, lesquelles relèvent du notaire ?", 'options' => ['Authentifier les actes', 'Juger les litiges entre commerçants', 'Conseiller les parties de façon impartiale', 'Conserver les minutes des actes'], 'correct' => [0, 2, 3], 'explanation' => "Le notaire authentifie, conseille et conserve les actes ; il ne juge pas les litiges, rôle réservé au juge."],
                        ['question' => "Le devoir de conseil du notaire signifie qu'il doit :", 'options' => ['Favoriser systématiquement le vendeur', 'Assurer l\'efficacité de l\'acte et informer impartialement les parties', 'Garder le silence sur les risques juridiques', 'Refuser tout acte risqué sans explication'], 'correct' => [1], 'explanation' => "Le notaire doit garantir l'efficacité de l'acte et éclairer toutes les parties de manière impartiale."],
                    ],
                ],
                [
                    'title' => 'Niveau 2 — L\'authenticité de l\'acte',
                    'subtitle' => "Force probante, force exécutoire et date certaine",
                    'xp_reward' => 110,
                    'content' => "🎯 **Objectif** : comprendre ce qui distingue un acte authentique d'un acte sous seing privé.\n\n💡 L'**acte authentique** est dressé par un officier public compétent, selon les formalités requises. Il bénéficie de trois forces majeures :\n• **Force probante renforcée** : ce que le notaire a constaté lui-même fait foi jusqu'à inscription de faux\n• **Force exécutoire** : la copie revêtue de la formule exécutoire permet de saisir sans passer par un jugement\n• **Date certaine** : la date inscrite s'impose aux tiers\n\nÀ l'inverse, l'**acte sous seing privé** (signé par les seules parties) a une force probante limitée et n'a pas de date certaine vis-à-vis des tiers.\n\nSchéma comparatif :\n\n| Critère | Acte authentique | Sous seing privé |\n|---|---|---|\n| Force probante | Jusqu'à inscription de faux | Contestable |\n| Force exécutoire | Oui | Non |\n| Date certaine | Oui | Non |\n| Conservation | Par le notaire | Par les parties |\n\n⚠️ Une fausse déclaration faite devant notaire engage gravement son auteur.\n\n✅ C'est cette sécurité juridique qui justifie de recourir au notaire pour les opérations importantes.",
                    'questions' => [
                        ['question' => "Qu'est-ce que la force exécutoire d'un acte authentique ?", 'options' => ['Il oblige à publier l\'acte', 'Il permet une exécution forcée sans jugement préalable', 'Il rend l\'acte secret', 'Il dispense de payer des impôts'], 'correct' => [1], 'explanation' => "La copie exécutoire d'un acte authentique permet de poursuivre l'exécution forcée sans obtenir d'abord un jugement."],
                        ['question' => "Les constatations personnelles du notaire font foi :", 'options' => ['Jusqu\'à preuve contraire simple', 'Jusqu\'à inscription de faux', 'Jamais', 'Seulement entre les parties'], 'correct' => [1], 'explanation' => "Ce que le notaire constate lui-même fait foi jusqu'à la procédure d'inscription de faux, très lourde."],
                        ['question' => "Quels avantages l'acte authentique offre-t-il par rapport à l'acte sous seing privé ?", 'options' => ['Date certaine opposable aux tiers', 'Force exécutoire', 'Absence totale de coût', 'Conservation sécurisée par le notaire'], 'correct' => [0, 1, 3], 'explanation' => "L'acte authentique offre date certaine, force exécutoire et conservation par le notaire ; il n'est pas gratuit."],
                    ],
                ],
                [
                    'title' => 'Niveau 3 — La vente immobilière (1) : avant-contrat',
                    'subtitle' => "Promesse, compromis et conditions suspensives",
                    'xp_reward' => 120,
                    'content' => "🎯 **Objectif** : maîtriser la phase préparatoire d'une vente immobilière.\n\n💡 Avant l'acte définitif, les parties signent souvent un **avant-contrat** :\n• **Promesse unilatérale de vente** : le vendeur s'engage, l'acquéreur dispose d'une option (souvent contre une indemnité d'immobilisation)\n• **Promesse synallagmatique (compromis)** : les deux parties s'engagent réciproquement à vendre et à acheter\n\nL'avant-contrat contient des **conditions suspensives** qui, si elles ne se réalisent pas, anéantissent la vente :\n• Obtention d'un financement bancaire\n• Absence de droit de préemption exercé\n• Situation hypothécaire conforme\n\n⚠️ Le notaire doit vérifier l'**origine de propriété** (titre foncier au Cameroun), l'identité des parties, l'absence de saisie ou d'hypothèque non purgée.\n\n✅ Étapes clés :\n• Recueil des pièces (titre foncier, état civil, situation matrimoniale)\n• Vérification de la capacité et des pouvoirs\n• Rédaction de l'avant-contrat avec conditions suspensives\n• Versement éventuel d'un acompte séquestré chez le notaire\n\nÀ Douala, un titre foncier clair évite des litiges fonciers coûteux.",
                    'questions' => [
                        ['question' => "Dans une promesse synallagmatique de vente :", 'options' => ['Seul le vendeur est engagé', 'Les deux parties s\'engagent réciproquement', 'Personne n\'est engagé', 'Seul le notaire est engagé'], 'correct' => [1], 'explanation' => "Le compromis (promesse synallagmatique) engage à la fois le vendeur à vendre et l'acquéreur à acheter."],
                        ['question' => "Une condition suspensive courante dans une vente immobilière est :", 'options' => ['L\'obtention du prêt bancaire par l\'acquéreur', 'Le déménagement du vendeur', 'La météo du jour de signature', 'L\'accord des voisins'], 'correct' => [0], 'explanation' => "L'obtention du financement est une condition suspensive classique protégeant l'acquéreur."],
                        ['question' => "Que doit vérifier le notaire avant la vente ?", 'options' => ['L\'origine de propriété / le titre foncier', 'L\'absence d\'hypothèque non purgée', 'La couleur préférée de l\'acheteur', 'L\'identité et la capacité des parties'], 'correct' => [0, 1, 3], 'explanation' => "Le notaire vérifie l'origine de propriété, la situation hypothécaire et l'identité/capacité des parties."],
                    ],
                ],
                [
                    'title' => 'Niveau 4 — La vente immobilière (2) : acte définitif',
                    'subtitle' => "Signature, prix, publicité foncière et taxes",
                    'xp_reward' => 130,
                    'content' => "🎯 **Objectif** : conclure la vente et la rendre opposable aux tiers.\n\n💡 Une fois les conditions suspensives levées, le notaire dresse l'**acte authentique de vente**. Le transfert de propriété s'accompagne de plusieurs opérations :\n• **Paiement du prix** : souvent via le compte du notaire, qui sécurise les fonds\n• **Perception des droits et taxes** (droits de mutation, frais d'enregistrement)\n• **Publicité foncière** : inscription de la mutation au livre foncier pour rendre la vente **opposable aux tiers**\n\n⚠️ Sans publicité foncière, l'acheteur n'est pas protégé contre une seconde vente du même bien. Le notaire engage sa responsabilité s'il omet cette formalité.\n\n✅ Déroulé type :\n• Lecture de l'acte aux parties\n• Signature des parties et du notaire\n• Remise des clés et de l'attestation de propriété\n• Versement des fonds au vendeur après formalités\n• Dépôt pour publication / mise à jour du titre foncier\n\nLes **frais de notaire** comprennent surtout des taxes versées à l'État, et non la seule rémunération du notaire. À Yaoundé, le règlement peut transiter par virement ou mobile money pour les acomptes, mais le solde majeur passe par les circuits bancaires sécurisés.",
                    'questions' => [
                        ['question' => "À quoi sert la publicité foncière de la vente ?", 'options' => ['À fixer le prix', 'À rendre la vente opposable aux tiers', 'À éviter de payer les taxes', 'À annuler l\'avant-contrat'], 'correct' => [1], 'explanation' => "La publicité foncière rend la mutation opposable aux tiers et protège l'acquéreur contre une double vente."],
                        ['question' => "Les fonds de la vente transitent souvent par :", 'options' => ['La poche du vendeur directement', 'Le compte du notaire qui les séquestre', 'Un compte anonyme à l\'étranger', 'La mairie'], 'correct' => [1], 'explanation' => "Le notaire séquestre les fonds et ne les libère qu'après accomplissement des formalités, sécurisant la transaction."],
                        ['question' => "Les « frais de notaire » d'une vente sont majoritairement composés :", 'options' => ['De la seule rémunération du notaire', 'De taxes et droits versés à l\'État', 'D\'une assurance facultative', 'De pourboires'], 'correct' => [1], 'explanation' => "L'essentiel des frais correspond à des droits et taxes perçus pour le compte de l'État, pas à la rémunération du notaire."],
                    ],
                ],
                [
                    'title' => 'Niveau 5 — La succession (1) : dévolution',
                    'subtitle' => "Héritiers, ordres et règles de dévolution",
                    'xp_reward' => 140,
                    'content' => "🎯 **Objectif** : identifier qui hérite et dans quel ordre.\n\n💡 À l'ouverture d'une succession (au décès), le notaire détermine la **dévolution** : qui sont les héritiers et leurs parts. En l'absence de testament, on applique la **dévolution légale**, organisée par ordres et degrés :\n• Les descendants (enfants, petits-enfants)\n• Les ascendants et collatéraux privilégiés (parents, frères et sœurs)\n• Les autres ascendants\n• Les collatéraux ordinaires\n\nLe **conjoint survivant** a des droits spécifiques selon le régime matrimonial et la présence d'enfants.\n\n⚠️ Au Cameroun, le droit successoral combine droit écrit et coutumes ; le notaire doit identifier le statut applicable et sécuriser les droits de chacun, notamment ceux du conjoint et des enfants.\n\n✅ L'acte central est l'**acte de notoriété** : il établit la qualité d'héritier et permet aux héritiers de justifier leurs droits (banques, administration).\n\nÉtapes :\n• Recueil de l'état civil et des actes de décès\n• Recherche d'un éventuel testament\n• Établissement de l'acte de notoriété\n• Inventaire et évaluation du patrimoine\n\nLe notaire évite ainsi les conflits familiaux et le blocage des comptes.",
                    'questions' => [
                        ['question' => "L'acte qui établit la qualité d'héritier est :", 'options' => ['Le compromis de vente', 'L\'acte de notoriété', 'Le contrat de mariage', 'La promesse de don'], 'correct' => [1], 'explanation' => "L'acte de notoriété constate la qualité et les droits des héritiers d'une succession."],
                        ['question' => "En l'absence de testament, on applique :", 'options' => ['La dévolution légale par ordres et degrés', 'Le choix libre du notaire', 'L\'attribution à l\'État systématiquement', 'Le partage uniquement entre amis'], 'correct' => [0], 'explanation' => "Sans testament, la loi organise la dévolution selon des ordres et degrés de parenté."],
                        ['question' => "Quels héritiers viennent en priorité dans l'ordre légal ?", 'options' => ['Les descendants (enfants)', 'Les voisins', 'Les collatéraux ordinaires en premier', 'Les amis désignés oralement'], 'correct' => [0], 'explanation' => "Les descendants (les enfants) priment dans l'ordre de la dévolution successorale légale."],
                    ],
                ],
                [
                    'title' => 'Niveau 6 — La succession (2) : réserve et partage',
                    'subtitle' => "Réserve héréditaire, quotité disponible et liquidation",
                    'xp_reward' => 150,
                    'content' => "🎯 **Objectif** : comprendre les limites de la liberté de transmettre et le partage.\n\n💡 La loi protège certains héritiers (les **réservataires**, notamment les enfants) par la **réserve héréditaire** : une part minimale qui leur revient obligatoirement. Le défunt ne peut disposer librement que de la **quotité disponible**.\n\nExemple schématique :\n• 1 enfant : réserve = 1/2, quotité disponible = 1/2\n• 2 enfants : réserve = 2/3, quotité disponible = 1/3\n• 3 enfants ou plus : réserve = 3/4, quotité disponible = 1/4\n\n⚠️ Une donation ou un legs qui empiète sur la réserve peut être **réduit** pour protéger les réservataires.\n\n✅ Le notaire procède ensuite à la **liquidation** et au **partage** :\n• Évaluer l'actif (biens) et le passif (dettes)\n• Calculer la masse partageable\n• Attribuer les lots aux héritiers\n• Régler les droits de succession\n\nLe partage peut être amiable (acte notarié) ou judiciaire en cas de désaccord. À Douala, une bonne anticipation évite l'indivision prolongée, source fréquente de conflits familiaux sur un terrain ou une maison.",
                    'questions' => [
                        ['question' => "La réserve héréditaire est :", 'options' => ['La part dont le défunt dispose librement', 'La part minimale revenant obligatoirement aux héritiers réservataires', 'Une taxe sur l\'héritage', 'Une dette du défunt'], 'correct' => [1], 'explanation' => "La réserve est la fraction du patrimoine légalement réservée aux héritiers réservataires (enfants notamment)."],
                        ['question' => "Avec deux enfants, la quotité disponible est généralement de :", 'options' => ['1/3', '1/2', '2/3', '3/4'], 'correct' => [0], 'explanation' => "Avec deux enfants, la réserve est de 2/3 et la quotité disponible de 1/3."],
                        ['question' => "Pour liquider une succession, le notaire doit :", 'options' => ['Évaluer l\'actif et le passif', 'Calculer la masse partageable', 'Ignorer les dettes du défunt', 'Attribuer les lots aux héritiers'], 'correct' => [0, 1, 3], 'explanation' => "La liquidation suppose d'évaluer actif et passif, de déterminer la masse partageable et d'attribuer les lots ; les dettes ne s'ignorent pas."],
                    ],
                ],
                [
                    'title' => 'Niveau 7 — La donation',
                    'subtitle' => "Donation entre vifs, formes et rapport",
                    'xp_reward' => 160,
                    'content' => "🎯 **Objectif** : sécuriser la transmission de son vivant.\n\n💡 La **donation** est un acte par lequel une personne (le donateur) transfère gratuitement un bien à une autre (le donataire), de son vivant. Elle suppose une **intention libérale** et l'**acceptation** du donataire.\n\nFormes principales :\n• **Donation simple** : transmission immédiate d'un bien\n• **Donation-partage** : répartition anticipée du patrimoine entre héritiers\n• **Don manuel** : remise d'un bien meuble de la main à la main\n\n⚠️ En principe, la donation d'un immeuble exige un **acte notarié** (acte authentique). Une donation peut être **rapportable** : à la succession, on en tient compte pour préserver l'égalité entre héritiers et la réserve.\n\n✅ Avantages d'une donation bien préparée :\n• Anticiper et apaiser la transmission familiale\n• Aider un enfant à s'installer (terrain, fonds de commerce)\n• Réduire les conflits futurs grâce à la donation-partage\n\nLe notaire vérifie la **capacité** du donateur, l'absence de pression et le respect de la réserve. À Yaoundé, une donation-partage écrite chez le notaire prévient les disputes ultérieures entre frères et sœurs.",
                    'questions' => [
                        ['question' => "La donation suppose nécessairement :", 'options' => ['Une vente déguisée', 'Une intention libérale et l\'acceptation du donataire', 'Un paiement en mobile money', 'Un jugement préalable'], 'correct' => [1], 'explanation' => "La donation repose sur l'intention libérale du donateur et l'acceptation du donataire, à titre gratuit."],
                        ['question' => "La donation d'un immeuble exige en principe :", 'options' => ['Un simple message vocal', 'Un acte notarié authentique', 'Aucune formalité', 'Une publication dans un journal'], 'correct' => [1], 'explanation' => "La donation d'un immeuble doit, en principe, être passée par acte notarié authentique."],
                        ['question' => "La donation-partage permet :", 'options' => ['De répartir par anticipation le patrimoine entre héritiers', 'D\'annuler une succession', 'De supprimer toute fiscalité', 'De déshériter totalement les enfants'], 'correct' => [0], 'explanation' => "La donation-partage organise de son vivant la répartition du patrimoine entre les héritiers, réduisant les conflits."],
                    ],
                ],
                [
                    'title' => 'Niveau 8 — Contrat de mariage et régimes matrimoniaux',
                    'subtitle' => "Communauté, séparation de biens et choix du régime",
                    'xp_reward' => 170,
                    'content' => "🎯 **Objectif** : maîtriser les régimes matrimoniaux et le rôle du contrat de mariage.\n\n💡 Le **régime matrimonial** organise la propriété et la gestion des biens des époux ainsi que leur sort en cas de divorce ou de décès. Le **contrat de mariage**, reçu par le notaire avant l'union, permet de choisir un régime adapté.\n\nPrincipaux régimes :\n• **Communauté** : les biens acquis pendant le mariage sont communs ; les biens propres restent personnels\n• **Séparation de biens** : chaque époux conserve la propriété et la gestion de ses biens\n• **Régimes mixtes** : combinaisons aménagées par contrat\n\nTableau d'aide à la décision :\n\n| Profil | Régime souvent conseillé |\n|---|---|\n| Entrepreneur exposé à des dettes | Séparation de biens |\n| Couple sans activité à risque | Communauté |\n| Souhait d'équilibre | Régime aménagé |\n\n⚠️ À défaut de contrat, le régime légal s'applique automatiquement. Un changement de régime ultérieur est possible mais encadré.\n\n✅ Le notaire conseille selon la situation : un commerçant à Douala exposé aux dettes professionnelles protège souvent son conjoint par la séparation de biens.",
                    'questions' => [
                        ['question' => "Le contrat de mariage est reçu par le notaire :", 'options' => ['Après le divorce', 'Avant la célébration du mariage', 'Au décès d\'un époux', 'Jamais'], 'correct' => [1], 'explanation' => "Le contrat de mariage se conclut avant le mariage pour fixer le régime matrimonial choisi."],
                        ['question' => "Dans le régime de séparation de biens :", 'options' => ['Tous les biens deviennent communs', 'Chaque époux conserve la propriété de ses biens', 'Les dettes sont partagées de force', 'Le notaire devient propriétaire'], 'correct' => [1], 'explanation' => "En séparation de biens, chaque époux reste seul propriétaire et gestionnaire de ses biens."],
                        ['question' => "Pour un entrepreneur exposé à des dettes professionnelles, on conseille souvent :", 'options' => ['La séparation de biens', 'La communauté universelle', 'L\'absence totale de régime', 'La donation au créancier'], 'correct' => [0], 'explanation' => "La séparation de biens protège le conjoint des dettes professionnelles de l'entrepreneur."],
                    ],
                ],
                [
                    'title' => 'Niveau 9 — Déontologie et conservation des actes',
                    'subtitle' => "Secret professionnel, impartialité et minutes",
                    'xp_reward' => 185,
                    'content' => "🎯 **Objectif** : intégrer les règles déontologiques et la conservation des actes.\n\n💡 Le notaire est tenu à une **déontologie** stricte qui fonde la confiance du public :\n• **Secret professionnel** : il ne divulgue pas les informations confiées\n• **Impartialité** : il conseille toutes les parties de façon équilibrée\n• **Probité et indépendance** : pas de conflit d'intérêts, pas d'acte pour soi-même ou ses proches\n• **Devoir de conseil** : il éclaire les parties sur la portée et les risques de l'acte\n\n⚠️ La violation du secret ou un manquement à l'impartialité engage la responsabilité disciplinaire, civile et parfois pénale du notaire.\n\n✅ **Conservation des actes** : le notaire garde l'original signé, appelé la **minute**. Il en délivre des **copies** :\n• La **copie exécutoire** (grosse) pour faire exécuter\n• Les **copies simples** (expéditions) pour information\n\nLes minutes sont conservées de très longues années puis versées aux archives. Cette conservation garantit que l'on peut toujours retrouver et prouver un acte, même des décennies plus tard. À Douala, retrouver une minute ancienne permet de régler un litige foncier hérité.",
                    'questions' => [
                        ['question' => "L'original signé d'un acte conservé par le notaire s'appelle :", 'options' => ['La copie', 'La minute', 'Le brouillon', 'L\'expédition'], 'correct' => [1], 'explanation' => "La minute est l'original de l'acte authentique, conservé par le notaire."],
                        ['question' => "Quels principes déontologiques s'imposent au notaire ?", 'options' => ['Le secret professionnel', 'La divulgation publique des dossiers', 'L\'impartialité envers les parties', 'L\'indépendance et l\'absence de conflit d\'intérêts'], 'correct' => [0, 2, 3], 'explanation' => "Secret, impartialité et indépendance sont des piliers déontologiques ; divulguer les dossiers est interdit."],
                        ['question' => "La copie exécutoire (grosse) sert à :", 'options' => ['Informer un tiers curieux', 'Faire procéder à l\'exécution forcée', 'Remplacer la minute', 'Annuler l\'acte'], 'correct' => [1], 'explanation' => "La copie exécutoire permet de poursuivre l'exécution forcée de l'acte authentique."],
                    ],
                ],
                [
                    'title' => 'Niveau 10 — Le conseil juridique et la pratique du notaire',
                    'subtitle' => "Synthèse, responsabilité et carrière",
                    'xp_reward' => 200,
                    'content' => "🎯 **Objectif** : faire la synthèse du métier et entrevoir les débouchés.\n\n💡 Au-delà de la rédaction d'actes, le notaire est un véritable **conseiller juridique de proximité**. Son rôle :\n• Sécuriser les opérations patrimoniales (immobilier, entreprise, famille)\n• Anticiper les conflits (donation-partage, contrat de mariage, testament)\n• Accompagner les projets de vie et d'investissement\n\n⚠️ Le notaire engage sa **responsabilité** : un défaut de conseil, une formalité oubliée ou une vérification négligée peuvent entraîner sa condamnation à réparer le préjudice. La rigueur n'est pas optionnelle.\n\n✅ Bonnes pratiques de fin de parcours :\n• Toujours vérifier identité, capacité, pouvoirs et titres de propriété\n• Expliquer clairement la portée de chaque acte\n• Conserver soigneusement les minutes\n• Respecter secret et impartialité\n\nDans l'espace OHADA, le notaire intervient aussi pour la constitution des sociétés et la sécurité des affaires.\n\n🏆 **Félicitations !** Tu maîtrises désormais les fondamentaux du notariat : authenticité, vente, succession, donation, régimes matrimoniaux et déontologie. Débouchés : notaire titulaire ou associé, notaire assistant, clerc de notaire, juriste en office, conseil patrimonial. À Douala, Yaoundé ou dans tout l'espace OHADA, ces compétences sécurisent les patrimoines et les affaires. Continue à te former : le droit évolue, le notaire aussi !",
                    'questions' => [
                        ['question' => "Le notaire engage sa responsabilité notamment en cas de :", 'options' => ['Conseil parfaitement donné', 'Défaut de conseil ou formalité oubliée', 'Respect du secret professionnel', 'Conservation correcte des minutes'], 'correct' => [1], 'explanation' => "Un manquement comme un défaut de conseil ou une formalité oubliée engage la responsabilité du notaire."],
                        ['question' => "Au-delà des actes, le notaire agit comme :", 'options' => ['Un juge des litiges', 'Un conseiller juridique de proximité', 'Un huissier de justice', 'Un agent du fisc'], 'correct' => [1], 'explanation' => "Le notaire conseille et sécurise les opérations patrimoniales, en véritable conseiller juridique."],
                        ['question' => "Quels débouchés sont ouverts après cette formation ?", 'options' => ['Notaire titulaire ou associé', 'Clerc de notaire ou juriste en office', 'Magistrat sans aucune formation supplémentaire', 'Conseil patrimonial'], 'correct' => [0, 1, 3], 'explanation' => "Notaire, clerc, juriste en office ou conseil patrimonial sont des débouchés ; devenir magistrat exige une autre formation."],
                    ],
                ],
            ],
        ]);

        $this->command->info('  ✓ Roadmap Notariat créée (10 niveaux).');
    }
}
