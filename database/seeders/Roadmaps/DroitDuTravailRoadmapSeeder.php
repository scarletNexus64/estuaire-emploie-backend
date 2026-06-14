<?php

namespace Database\Seeders\Roadmaps;

use Illuminate\Database\Seeder;

/**
 * Roadmap Droit du Travail — comprendre ses droits et obligations au travail dans le contexte camerounais et OHADA.
 */
class DroitDuTravailRoadmapSeeder extends Seeder
{
    use RoadmapSeederHelper;

    public function run(): void
    {
        $this->createRoadmap([
            'title' => 'Maîtrise le Droit du Travail',
            'slug' => 'droit-du-travail',
            'domain' => 'juridique',
            'description' => "Apprends les bases du droit du travail applicable au Cameroun et dans l'espace OHADA. De la signature du contrat jusqu'à la rupture, en passant par les congés, le SMIG, la représentation du personnel et la sécurité au travail, cette roadmap te donne les repères essentiels pour défendre tes droits et respecter tes obligations, que tu sois salarié, candidat ou employeur.",
            'objectives' => "Distinguer CDD et CDI et leurs régimes\nMaîtriser la période d'essai et ses limites\nConnaître les droits et obligations réciproques\nCalculer durée du travail, heures sup et congés\nComprendre la rémunération et le SMIG\nGérer une rupture de contrat dans les règles",
            'icon' => '⚖️',
            'color' => '#2563EB',
            'difficulty' => 'intermediate',
            'required_packs' => [],
            'pass_threshold' => 70,
            'order' => 20,
            'levels' => [
                [
                    'title' => 'Niveau 1 — Les sources du droit du travail',
                    'subtitle' => 'Comprendre d\'où viennent les règles',
                    'xp_reward' => 100,
                    'content' => "🎯 **Objectif** : situer les textes qui régissent la relation de travail au Cameroun.\n\n💡 Le droit du travail organise les rapports entre un employeur et un salarié lié par un **contrat** et un **lien de subordination** (l'employeur donne des ordres, le salarié les exécute).\n\nLes principales sources, de la plus forte à la plus souple :\n• La **Constitution** et les conventions internationales (OIT)\n• Le **Code du travail** (loi n° 92/007 du 14 août 1992 au Cameroun)\n• Les **conventions collectives** (par secteur : banque, BTP...)\n• Le **règlement intérieur** de l'entreprise\n• Le **contrat de travail** individuel\n\n✅ Règle clé : une règle inférieure ne peut pas être moins favorable au salarié qu'une règle supérieure. C'est le **principe de faveur**.\n\n⚠️ Le droit du travail est largement **d'ordre public** : on ne peut pas y renoncer par avance, même par écrit. Une clause qui prive un salarié d'un droit légal est nulle.",
                    'questions' => [
                        ['question' => 'Quel élément caractérise un contrat de travail ?', 'options' => ['L\'autonomie totale du travailleur', 'Le lien de subordination', 'L\'absence de rémunération', 'La durée illimitée obligatoire'], 'correct' => [1], 'explanation' => 'Le lien de subordination juridique distingue le salarié du travailleur indépendant.'],
                        ['question' => 'Selon le principe de faveur, une convention collective peut :', 'options' => ['Être moins favorable que la loi', 'Supprimer le SMIG', 'Accorder plus que le minimum légal', 'Annuler le Code du travail'], 'correct' => [2], 'explanation' => 'Une norme inférieure peut améliorer la situation du salarié mais jamais la dégrader sous le minimum légal.'],
                        ['question' => 'Quel texte régit principalement le travail au Cameroun ?', 'options' => ['Le Code civil', 'Le Code de commerce', 'Le Code du travail de 1992', 'Le Code pénal'], 'correct' => [2], 'explanation' => 'La loi n° 92/007 du 14 août 1992 constitue le Code du travail camerounais.'],
                    ],
                ],
                [
                    'title' => 'Niveau 2 — Le contrat de travail : CDD et CDI',
                    'subtitle' => 'Choisir et comprendre la bonne forme',
                    'xp_reward' => 110,
                    'content' => "🎯 **Objectif** : distinguer les deux grandes formes de contrat.\n\n💡 Le **CDI** (contrat à durée indéterminée) est la forme normale et générale de l'emploi. Il n'a pas de terme fixé à l'avance.\n\nLe **CDD** (contrat à durée déterminée) a un terme précis. Au Cameroun, sa durée ne peut excéder **2 ans** et il est renouvelable **une seule fois** avec un travailleur donné.\n\nComparatif rapide :\n\n| Critère | CDD | CDI |\n|---|---|---|\n| Durée | Fixée (max 2 ans) | Indéterminée |\n| Forme | Écrit obligatoire | Écrit recommandé |\n| Fin | Au terme prévu | Préavis / motif |\n\n✅ Le CDD doit être **écrit**. À défaut d'écrit, il est réputé conclu pour une durée indéterminée.\n\n⚠️ Enchaîner des CDD pour pourvoir durablement un poste permanent peut entraîner une **requalification en CDI** par le juge.",
                    'questions' => [
                        ['question' => 'Quelle est la forme normale du contrat de travail ?', 'options' => ['Le CDD', 'Le contrat verbal', 'Le CDI', 'Le contrat de stage'], 'correct' => [2], 'explanation' => 'Le CDI est la forme générale et de droit commun de la relation de travail.'],
                        ['question' => 'Un CDD non constaté par écrit est :', 'options' => ['Nul et sans effet', 'Réputé être un CDI', 'Limité à 6 mois', 'Automatiquement renouvelé'], 'correct' => [1], 'explanation' => 'L\'absence d\'écrit fait présumer un contrat à durée indéterminée.'],
                        ['question' => 'Au Cameroun, un CDR/CDD peut être renouvelé :', 'options' => ['Autant de fois que voulu', 'Une seule fois avec le même travailleur', 'Jamais', 'Trois fois maximum'], 'correct' => [1], 'explanation' => 'Le CDD ne peut être renouvelé qu\'une fois avec un même travailleur dans la limite de durée légale.'],
                    ],
                ],
                [
                    'title' => 'Niveau 3 — La période d\'essai',
                    'subtitle' => 'Tester avant de s\'engager',
                    'xp_reward' => 120,
                    'content' => "🎯 **Objectif** : comprendre le rôle et les limites de la période d'essai.\n\n💡 La période d'essai permet à l'employeur d'évaluer les compétences du salarié, et au salarié d'apprécier son poste. Pendant cette période, chacun peut rompre **librement**, sans indemnité ni motivation lourde.\n\nElle doit être **stipulée par écrit** dans le contrat ou la lettre d'engagement. Sans écrit, il n'y a pas de période d'essai.\n\nDurées indicatives (Code du travail camerounais et conventions) :\n• Employés/ouvriers : souvent **1 mois**, renouvelable une fois\n• Cadres : jusqu'à **6 mois** renouvellement compris\n\n✅ Exemple : à Douala, une assistante administrative recrutée avec 1 mois d'essai renouvelable peut être au maximum 2 mois en essai.\n\n⚠️ La rupture pendant l'essai ne doit pas être **abusive** (motif discriminatoire, vengeance...). Un abus peut donner droit à des dommages-intérêts.",
                    'questions' => [
                        ['question' => 'La période d\'essai doit obligatoirement être :', 'options' => ['Verbale', 'Stipulée par écrit', 'Validée par le tribunal', 'Payée double'], 'correct' => [1], 'explanation' => 'Sans clause écrite, aucune période d\'essai n\'est valable.'],
                        ['question' => 'Pendant la période d\'essai, la rupture :', 'options' => ['Est impossible', 'Exige toujours 3 mois de préavis', 'Est en principe libre pour les deux parties', 'Nécessite l\'accord de l\'inspecteur'], 'correct' => [2], 'explanation' => 'L\'essai permet à chacun de rompre librement, sous réserve de ne pas commettre d\'abus.'],
                        ['question' => 'Pour un cadre, la période d\'essai peut atteindre environ :', 'options' => ['1 semaine', '6 mois renouvellement compris', '2 ans', 'Aucune limite'], 'correct' => [1], 'explanation' => 'Pour les cadres, l\'essai peut aller jusqu\'à 6 mois en incluant le renouvellement.'],
                    ],
                ],
                [
                    'title' => 'Niveau 4 — Droits et obligations réciproques',
                    'subtitle' => 'Un contrat à double sens',
                    'xp_reward' => 130,
                    'content' => "🎯 **Objectif** : connaître ce que chacun doit à l'autre.\n\n💡 Le contrat crée des obligations **réciproques**.\n\nObligations de l'**employeur** :\n• Fournir le travail convenu et les moyens de l'exécuter\n• Payer le **salaire** aux échéances prévues\n• Assurer la **sécurité** et la santé au travail\n• Respecter la dignité et ne pas discriminer\n• Déclarer le salarié (CNPS au Cameroun)\n\nObligations du **salarié** :\n• Exécuter personnellement et de bonne foi son travail\n• Respecter les directives et le règlement intérieur\n• Obligation de **loyauté** et de discrétion\n• Prendre soin du matériel confié\n\n✅ Exemple concret : un commercial qui détourne des clients vers une activité personnelle viole son obligation de loyauté.\n\n⚠️ La **discrimination** (sexe, origine, religion, handicap, statut VIH...) est interdite et sanctionnée. Le harcèlement engage la responsabilité de l'employeur.",
                    'questions' => [
                        ['question' => 'Laquelle est une obligation de l\'employeur ?', 'options' => ['Être loyal envers son patron', 'Assurer la sécurité au travail', 'Respecter le règlement intérieur', 'Prendre soin de son propre matériel'], 'correct' => [1], 'explanation' => 'L\'employeur doit garantir la sécurité et la santé de ses salariés.'],
                        ['question' => 'Quelles sont des obligations du salarié ? (plusieurs réponses)', 'options' => ['Exécuter le travail de bonne foi', 'Payer les cotisations CNPS de l\'entreprise', 'Respecter une obligation de loyauté', 'Fixer lui-même son salaire'], 'correct' => [0, 2], 'explanation' => 'Le salarié doit travailler de bonne foi et rester loyal envers son employeur.'],
                        ['question' => 'La discrimination à l\'embauche fondée sur la religion est :', 'options' => ['Autorisée si justifiée', 'Interdite et sanctionnée', 'Laissée au choix de l\'employeur', 'Permise pour les CDD'], 'correct' => [1], 'explanation' => 'Toute discrimination fondée sur un critère protégé est prohibée par la loi.'],
                    ],
                ],
                [
                    'title' => 'Niveau 5 — Durée du travail et heures supplémentaires',
                    'subtitle' => 'Compter le temps de travail',
                    'xp_reward' => 140,
                    'content' => "🎯 **Objectif** : maîtriser la durée légale et les heures supplémentaires.\n\n💡 Au Cameroun, la durée légale du travail est de **40 heures par semaine** dans les établissements non agricoles. Au-delà, on parle d'**heures supplémentaires**, majorées.\n\nMajorations indicatives :\n• Premières heures sup : **+20 %**\n• Heures suivantes : **+30 %**\n• Heures de nuit / dimanche / jours fériés : majorations renforcées\n\nLe **repos hebdomadaire** est obligatoire (au moins 24 h consécutives, en principe le dimanche).\n\n✅ Exemple : un magasinier à Yaoundé payé 600 FCFA/h fait 4 heures sup à +20 %. Chaque heure sup vaut 600 × 1,20 = **720 FCFA**.\n\n⚠️ Les heures supplémentaires doivent rester exceptionnelles et respecter des plafonds. Faire travailler au-delà sans repos ni majoration est une infraction.",
                    'questions' => [
                        ['question' => 'Quelle est la durée légale hebdomadaire au Cameroun (non agricole) ?', 'options' => ['35 heures', '40 heures', '48 heures', '60 heures'], 'correct' => [1], 'explanation' => 'La durée légale est fixée à 40 heures par semaine dans les établissements non agricoles.'],
                        ['question' => 'Une heure travaillée au-delà de la durée légale est :', 'options' => ['Non payée', 'Une heure supplémentaire majorée', 'Toujours interdite', 'Récupérée sans majoration'], 'correct' => [1], 'explanation' => 'Le dépassement de la durée légale ouvre droit à des heures supplémentaires majorées.'],
                        ['question' => 'Le repos hebdomadaire minimal est d\'au moins :', 'options' => ['8 heures', '12 heures', '24 heures consécutives', '48 heures'], 'correct' => [2], 'explanation' => 'Le salarié a droit à un repos hebdomadaire d\'au moins 24 heures consécutives.'],
                    ],
                ],
                [
                    'title' => 'Niveau 6 — Congés et absences',
                    'subtitle' => 'Le droit au repos',
                    'xp_reward' => 150,
                    'content' => "🎯 **Objectif** : calculer les congés payés et connaître les absences protégées.\n\n💡 Au Cameroun, le congé payé est acquis à raison de **1,5 jour ouvrable par mois** de service effectif, soit **18 jours ouvrables** pour 12 mois travaillés.\n\nDes majorations existent : ancienneté, mère salariée pour enfants à charge, jeunes travailleurs.\n\nPendant le congé, le salarié perçoit une **allocation de congé** correspondant à son salaire moyen.\n\nAutres absences protégées :\n• **Congé de maternité** : 14 semaines (avec indemnité)\n• Maladie justifiée par certificat médical\n• Permissions exceptionnelles (mariage, décès, naissance)\n\n✅ Exemple : après 8 mois de travail, un salarié a acquis 8 × 1,5 = **12 jours** de congé.\n\n⚠️ Le droit au congé ne peut pas être remplacé par une **indemnité compensatrice** tant que le contrat continue (sauf rupture). Renoncer à ses congés contre de l'argent en cours de contrat n'est pas valable.",
                    'questions' => [
                        ['question' => 'Le congé payé s\'acquiert à raison de :', 'options' => ['1 jour par an', '1,5 jour ouvrable par mois', '5 jours par mois', '30 jours par mois'], 'correct' => [1], 'explanation' => 'Le droit de base est de 1,5 jour ouvrable par mois de service effectif.'],
                        ['question' => 'Après 12 mois de travail, combien de jours de congé de base ?', 'options' => ['10 jours', '18 jours ouvrables', '30 jours', '40 jours'], 'correct' => [1], 'explanation' => '12 mois × 1,5 jour = 18 jours ouvrables de congé.'],
                        ['question' => 'Le congé de maternité au Cameroun dure :', 'options' => ['4 semaines', '8 semaines', '14 semaines', '6 mois'], 'correct' => [2], 'explanation' => 'Le congé de maternité légal est de 14 semaines, avec indemnité.'],
                    ],
                ],
                [
                    'title' => 'Niveau 7 — Rémunération et SMIG',
                    'subtitle' => 'Le salaire et ses garanties',
                    'xp_reward' => 160,
                    'content' => "🎯 **Objectif** : comprendre la composition du salaire et le salaire minimum.\n\n💡 Le salaire comprend le **salaire de base** plus, le cas échéant, des **primes** et **indemnités** (transport, logement, ancienneté, sujétion...).\n\nLe **SMIG** (Salaire Minimum Interprofessionnel Garanti) est le plancher : aucun salaire ne peut lui être inférieur. Au Cameroun, le SMIG a été relevé à **60 000 FCFA** par mois (revalorisations possibles, vérifier le texte en vigueur).\n\nProtections du salaire :\n• Paiement régulier, en monnaie ayant cours légal\n• **Bulletin de paie** détaillé obligatoire\n• Les **retenues** sont strictement encadrées (CNPS, impôts, avances)\n\n✅ Schéma : Salaire brut = base + primes ; Salaire net = brut − cotisations − impôts.\n\n⚠️ Le paiement « au noir » sans bulletin ni déclaration prive le salarié de droits (retraite, accident). C'est illégal et risqué pour les deux parties.",
                    'questions' => [
                        ['question' => 'Que signifie SMIG ?', 'options' => ['Salaire Maximum Imposable Global', 'Salaire Minimum Interprofessionnel Garanti', 'Système Mensuel d\'Indemnité Globale', 'Salaire Moyen Indexé Général'], 'correct' => [1], 'explanation' => 'Le SMIG est le Salaire Minimum Interprofessionnel Garanti, plancher légal de rémunération.'],
                        ['question' => 'Lesquels protègent le salaire ? (plusieurs réponses)', 'options' => ['La remise d\'un bulletin de paie', 'L\'encadrement strict des retenues', 'La liberté totale de l\'employeur sur les retenues', 'Le paiement sans aucune déclaration'], 'correct' => [0, 1], 'explanation' => 'Le bulletin de paie obligatoire et l\'encadrement des retenues protègent le salarié.'],
                        ['question' => 'Un salaire inférieur au SMIG est :', 'options' => ['Valable si le salarié accepte', 'Interdit', 'Permis pour les stagiaires uniquement', 'Autorisé en CDD'], 'correct' => [1], 'explanation' => 'Aucun salaire ne peut être inférieur au SMIG, même avec l\'accord du salarié.'],
                    ],
                ],
                [
                    'title' => 'Niveau 8 — Rupture du contrat et licenciement',
                    'subtitle' => 'Mettre fin au contrat dans les règles',
                    'xp_reward' => 175,
                    'content' => "🎯 **Objectif** : connaître les modes de rupture et leurs effets.\n\n💡 Un CDI peut prendre fin par :\n• **Démission** (à l'initiative du salarié, avec préavis)\n• **Licenciement** (à l'initiative de l'employeur)\n• Rupture d'un commun accord, départ à la retraite, force majeure\n\nLe **licenciement** doit reposer sur un **motif légitime** : faute, motif économique, inaptitude... Il suppose un **préavis** (sauf faute lourde) et le respect d'une procédure.\n\nIndemnités possibles :\n• **Indemnité de préavis** si le préavis n'est pas effectué\n• **Indemnité de licenciement** selon l'ancienneté\n• **Solde de tout compte** et **certificat de travail**\n\n✅ Le licenciement pour **faute lourde** prive le salarié de préavis et d'indemnité, mais le motif doit être réel et grave.\n\n⚠️ Un licenciement **abusif** (sans motif réel et sérieux, ou discriminatoire) ouvre droit à des **dommages-intérêts** prononcés par le juge social.",
                    'questions' => [
                        ['question' => 'Le licenciement à l\'initiative de l\'employeur doit reposer sur :', 'options' => ['L\'humeur du jour', 'Un motif légitime', 'L\'accord du syndicat seulement', 'Rien du tout'], 'correct' => [1], 'explanation' => 'Tout licenciement doit s\'appuyer sur un motif réel et sérieux (faute, économique, inaptitude...).'],
                        ['question' => 'La faute lourde du salarié :', 'options' => ['Donne droit à double indemnité', 'Prive de préavis et d\'indemnité', 'Est sans conséquence', 'Oblige à le réintégrer'], 'correct' => [1], 'explanation' => 'La faute lourde fait perdre le droit au préavis et à l\'indemnité de licenciement.'],
                        ['question' => 'Un licenciement abusif peut donner lieu à :', 'options' => ['Une amende pour le salarié', 'Des dommages-intérêts pour le salarié', 'Une prime pour l\'employeur', 'Aucune sanction'], 'correct' => [1], 'explanation' => 'Le licenciement sans motif réel et sérieux ouvre droit à réparation au profit du salarié.'],
                    ],
                ],
                [
                    'title' => 'Niveau 9 — Représentation du personnel et conflits',
                    'subtitle' => 'Délégués, syndicats et prud\'hommes',
                    'xp_reward' => 190,
                    'content' => "🎯 **Objectif** : comprendre la défense collective et le règlement des litiges.\n\n💡 Les salariés peuvent être représentés par :\n• Les **délégués du personnel** (élus, présentent les réclamations)\n• Les **syndicats** (défendent les intérêts professionnels)\n\nCes représentants bénéficient d'une **protection** contre le licenciement : l'employeur doit obtenir une autorisation (inspecteur du travail) avant de les licencier.\n\nEn cas de **conflit individuel** (salaire impayé, licenciement contesté), la procédure suit des étapes :\n1. Tentative de règlement amiable\n2. Saisine de l'**inspecteur du travail** (conciliation obligatoire au Cameroun)\n3. À défaut d'accord, saisine du **tribunal compétent** (juridiction sociale)\n\n✅ Au Cameroun, la conciliation devant l'inspecteur du travail est un **préalable** avant le procès. En France, on parle du conseil de **prud'hommes**.\n\n⚠️ Le **droit de grève** est reconnu mais encadré : il doit respecter un préavis et des procédures, sous peine d'être jugé illicite.",
                    'questions' => [
                        ['question' => 'Les délégués du personnel sont :', 'options' => ['Nommés par l\'employeur', 'Élus par les salariés', 'Désignés par l\'État', 'Choisis par les clients'], 'correct' => [1], 'explanation' => 'Les délégués du personnel sont élus par les salariés de l\'établissement.'],
                        ['question' => 'Au Cameroun, avant un procès sur un conflit du travail, il faut :', 'options' => ['Saisir directement la Cour suprême', 'Passer par la conciliation de l\'inspecteur du travail', 'Démissionner d\'abord', 'Payer une caution'], 'correct' => [1], 'explanation' => 'La conciliation devant l\'inspecteur du travail est un préalable obligatoire.'],
                        ['question' => 'Le droit de grève est :', 'options' => ['Interdit', 'Reconnu mais encadré par des procédures', 'Sans aucune règle', 'Réservé aux cadres'], 'correct' => [1], 'explanation' => 'La grève est un droit reconnu mais soumis à des conditions (préavis, procédure).'],
                    ],
                ],
                [
                    'title' => 'Niveau 10 — Santé, sécurité au travail et synthèse',
                    'subtitle' => 'Protéger l\'intégrité et consolider tes acquis',
                    'xp_reward' => 200,
                    'content' => "🎯 **Objectif** : maîtriser les règles de santé-sécurité et faire la synthèse.\n\n💡 L'employeur a une **obligation de sécurité** : il doit prévenir les risques et protéger la santé physique et mentale des salariés.\n\nMesures essentielles :\n• Évaluation des risques et **équipements de protection (EPI)**\n• Information et **formation** à la sécurité\n• **Comité d'hygiène et de sécurité** dans les grandes entreprises\n• Visites médicales et médecine du travail\n\nEn cas d'**accident du travail** ou de **maladie professionnelle**, le salarié déclaré bénéficie de la prise en charge **CNPS** (soins, indemnités, rente).\n\n✅ Exemple : sur un chantier BTP à Douala, fournir casques et harnais relève de l'obligation de sécurité de l'employeur.\n\n⚠️ Un salarié peut exercer un **droit de retrait** face à un danger grave et imminent, sans perdre son salaire.\n\n🏆 **Félicitations !** Tu maîtrises désormais les bases du droit du travail : contrat, essai, droits, durée, congés, salaire, rupture, représentation et sécurité. Ces compétences ouvrent des débouchés comme **assistant RH, gestionnaire de paie, conseiller en droit social, délégué du personnel** ou **inspecteur du travail**. Continue à suivre l'actualité juridique : les textes évoluent !",
                    'questions' => [
                        ['question' => 'En cas d\'accident du travail, le salarié déclaré est pris en charge par :', 'options' => ['Son employeur seul', 'La CNPS', 'Sa famille', 'Personne'], 'correct' => [1], 'explanation' => 'Les accidents du travail sont couverts par la CNPS pour le salarié déclaré.'],
                        ['question' => 'Face à un danger grave et imminent, le salarié peut :', 'options' => ['Être licencié immédiatement', 'Exercer son droit de retrait', 'Continuer obligatoirement', 'Payer une amende'], 'correct' => [1], 'explanation' => 'Le droit de retrait permet de cesser le travail face à un danger grave et imminent, sans perte de salaire.'],
                        ['question' => 'Quelles relèvent de l\'obligation de sécurité de l\'employeur ? (plusieurs réponses)', 'options' => ['Fournir les EPI', 'Former à la sécurité', 'Ignorer les risques pour gagner du temps', 'Organiser la médecine du travail'], 'correct' => [0, 1, 3], 'explanation' => 'Fournir les EPI, former à la sécurité et assurer la médecine du travail font partie de l\'obligation de sécurité.'],
                    ],
                ],
            ],
        ]);

        $this->command->info('  ✓ Roadmap Droit du Travail créée (10 niveaux).');
    }
}
