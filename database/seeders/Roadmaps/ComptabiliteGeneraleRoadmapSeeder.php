<?php

namespace Database\Seeders\Roadmaps;

use Illuminate\Database\Seeder;

/**
 * Roadmap Comptabilité Générale — des fondamentaux aux états financiers (système OHADA).
 */
class ComptabiliteGeneraleRoadmapSeeder extends Seeder
{
    use RoadmapSeederHelper;

    public function run(): void
    {
        $this->createRoadmap([
            'title' => 'Maîtrise la Comptabilité Générale',
            'slug' => 'comptabilite-generale',
            'domain' => 'finance',
            'description' => "Apprends la comptabilité générale pas à pas, du rôle de la comptabilité jusqu'aux états financiers de clôture. Tous les exemples s'appuient sur le contexte africain (système comptable OHADA, FCFA, TVA camerounaise, mobile money). Une formation idéale pour les futurs comptables, gestionnaires, entrepreneurs et candidats à un emploi en finance.",
            'objectives' => "Comprendre le rôle et l'utilité de la comptabilité dans une entreprise\nMaîtriser le principe de la partie double\nSavoir débiter et créditer correctement un compte\nUtiliser le plan comptable OHADA\nTenir un journal et reporter au grand livre\nÉtablir une balance, un bilan et un compte de résultat\nComprendre et déclarer la TVA\nRéaliser les opérations de clôture et produire les états financiers",
            'icon' => '📊',
            'color' => '#2563EB',
            'difficulty' => 'beginner',
            'required_packs' => [],
            'pass_threshold' => 70,
            'order' => 20,
            'levels' => [
                [
                    'title' => 'Niveau 1 — À quoi sert la comptabilité ?',
                    'subtitle' => "Le rôle et l'utilité de la comptabilité",
                    'xp_reward' => 100,
                    'content' => "🎯 **Objectif** : comprendre pourquoi toute entreprise doit tenir une comptabilité.\n\n💡 La comptabilité est un langage qui enregistre, classe et résume toutes les opérations financières d'une entreprise (achats, ventes, salaires, emprunts...). Elle répond à une question simple : combien l'entreprise gagne, dépense et possède ?\n\nElle sert à plusieurs acteurs :\n• Le **dirigeant** : piloter et décider\n• L'**État** (impôts) : calculer les taxes (TVA, impôt sur les sociétés)\n• Les **banques** : évaluer la solvabilité avant un crédit\n• Les **investisseurs** : juger la rentabilité\n\n✅ Exemple concret : une boutique de Douala vend pour 500 000 FCFA et achète des marchandises pour 300 000 FCFA. La comptabilité permet de savoir qu'elle a dégagé une marge brute de 200 000 FCFA.\n\n⚠️ Dans l'espace OHADA, la tenue d'une comptabilité régulière est une **obligation légale** pour les entreprises, encadrée par le SYSCOHADA (Système Comptable OHADA).",
                    'questions' => [
                        ['question' => "Quel est le rôle principal de la comptabilité ?", 'options' => ['Vendre des produits', 'Enregistrer, classer et résumer les opérations financières', 'Recruter du personnel', 'Faire de la publicité'], 'correct' => [1], 'explanation' => "La comptabilité enregistre, classe et résume toutes les opérations financières de l'entreprise."],
                        ['question' => "Parmi ces acteurs, lesquels utilisent l'information comptable ?", 'options' => ['Le dirigeant', 'Les concurrents directs', 'Les banques', "L'administration fiscale"], 'correct' => [0, 2, 3], 'explanation' => "Le dirigeant, les banques et l'État (fisc) exploitent les informations comptables ; les concurrents n'y ont pas accès."],
                        ['question' => "Quel référentiel encadre la comptabilité dans l'espace OHADA ?", 'options' => ['Les normes US GAAP', 'Le SYSCOHADA', 'Le code Napoléon', 'Le système suisse'], 'correct' => [1], 'explanation' => "Le SYSCOHADA est le système comptable applicable dans les pays membres de l'OHADA."],
                    ],
                ],
                [
                    'title' => 'Niveau 2 — Le principe de la partie double',
                    'subtitle' => "Toute opération a deux faces",
                    'xp_reward' => 110,
                    'content' => "🎯 **Objectif** : maîtriser la règle fondamentale de la comptabilité moderne.\n\n💡 Le **principe de la partie double** stipule que toute opération est enregistrée au minimum dans deux comptes : un emploi (où va l'argent) et une ressource (d'où vient l'argent). Le total des **débits** est toujours égal au total des **crédits**.\n\nIntuition : si tu reçois de l'argent, il faut savoir D'OÙ il vient. Si tu dépenses, il faut savoir OÙ il va.\n\n✅ Exemple : un commerçant de Yaoundé achète un ordinateur de 400 000 FCFA et paie en espèces.\n• Emploi : le matériel augmente (+400 000)\n• Ressource : la caisse diminue (-400 000)\n\nLes deux montants sont égaux : c'est l'équilibre.\n\n⚠️ Conséquence : à tout moment, **Actif = Passif** et la balance doit être équilibrée. Si débit ≠ crédit, il y a une erreur de saisie.\n\nSchéma : \nDébit (emploi) 400 000  =  Crédit (ressource) 400 000",
                    'questions' => [
                        ['question' => "Selon le principe de la partie double, dans une écriture le total des débits est...", 'options' => ['Toujours supérieur au crédit', 'Toujours égal au total des crédits', 'Toujours inférieur au crédit', 'Sans rapport avec le crédit'], 'correct' => [1], 'explanation' => "Le principe de la partie double impose l'égalité permanente entre le total des débits et celui des crédits."],
                        ['question' => "Combien de comptes au minimum sont mouvementés par une opération ?", 'options' => ['Un seul', 'Au minimum deux', 'Au minimum cinq', 'Aucun'], 'correct' => [1], 'explanation' => "Toute opération mouvemente au moins deux comptes : un en débit, un en crédit."],
                        ['question' => "Que signifie le déséquilibre entre débit et crédit dans une écriture ?", 'options' => ["L'entreprise est rentable", 'Une erreur de saisie', 'Un bénéfice exceptionnel', 'Une opération normale'], 'correct' => [1], 'explanation' => "Un déséquilibre entre débit et crédit révèle systématiquement une erreur d'enregistrement."],
                    ],
                ],
                [
                    'title' => 'Niveau 3 — Compte, débit et crédit',
                    'subtitle' => "Lire et mouvementer un compte en T",
                    'xp_reward' => 120,
                    'content' => "🎯 **Objectif** : savoir débiter et créditer les bonnes catégories de comptes.\n\n💡 Un **compte** est un tableau à deux colonnes : le **débit** (gauche) et le **crédit** (droite). On le représente souvent en « compte en T ».\n\nRègles d'or selon la nature du compte :\n• Comptes d'**actif** (caisse, banque, clients, matériel) : augmentent au **débit**, diminuent au crédit.\n• Comptes de **passif** (capital, fournisseurs, emprunts) : augmentent au **crédit**, diminuent au débit.\n• Comptes de **charges** (achats, salaires, loyer) : au **débit**.\n• Comptes de **produits** (ventes) : au **crédit**.\n\n✅ Exemple : vente de marchandises 200 000 FCFA encaissée par mobile money.\n• Débit : Banque/Mobile money 200 000 (l'actif augmente)\n• Crédit : Ventes 200 000 (le produit augmente)\n\nSchéma compte en T (Caisse) :\nDébit | Crédit\n200 000 |\n\n⚠️ Le **solde** d'un compte = différence entre le total débit et le total crédit. Un compte d'actif a normalement un solde débiteur.",
                    'questions' => [
                        ['question' => "Un compte d'actif (ex : la banque) augmente...", 'options' => ['Au crédit', 'Au débit', 'Au milieu', "Il n'augmente jamais"], 'correct' => [1], 'explanation' => "Les comptes d'actif augmentent au débit et diminuent au crédit."],
                        ['question' => "Lesquels de ces comptes s'enregistrent normalement au débit quand ils augmentent ?", 'options' => ['Les charges (salaires, loyer)', 'Le capital', "Les comptes d'actif (caisse)", 'Les emprunts'], 'correct' => [0, 2], 'explanation' => "Les charges et les comptes d'actif augmentent au débit ; le capital et les emprunts (passif) augmentent au crédit."],
                        ['question' => "Le solde d'un compte correspond à...", 'options' => ['La somme du débit et du crédit', 'La différence entre total débit et total crédit', 'Le montant le plus élevé', 'Toujours zéro'], 'correct' => [1], 'explanation' => "Le solde est la différence entre le total des débits et le total des crédits du compte."],
                    ],
                ],
                [
                    'title' => 'Niveau 4 — Le plan comptable OHADA',
                    'subtitle' => "Classer les comptes par numéro",
                    'xp_reward' => 130,
                    'content' => "🎯 **Objectif** : savoir utiliser la nomenclature des comptes du SYSCOHADA.\n\n💡 Le **plan comptable** est une liste codifiée de tous les comptes. Chaque compte porte un numéro dont le premier chiffre indique la classe.\n\nLes classes du SYSCOHADA :\n• Classe **1** : comptes de ressources durables (capital, emprunts)\n• Classe **2** : immobilisations (matériel, bâtiments)\n• Classe **3** : stocks\n• Classe **4** : tiers (clients, fournisseurs, État, personnel)\n• Classe **5** : trésorerie (banque, caisse)\n• Classe **6** : charges\n• Classe **7** : produits\n• Classe **8** : autres charges et produits\n\nLes classes 1 à 5 servent au **bilan**, les classes 6 à 8 au **compte de résultat**.\n\n✅ Exemples concrets de numéros :\n• 401 : Fournisseurs\n• 411 : Clients\n• 521 : Banque\n• 571 : Caisse\n• 601 : Achats de marchandises\n• 701 : Ventes de marchandises\n\n⚠️ Connaître les classes permet de retrouver instantanément la nature d'un compte à partir de son numéro.",
                    'questions' => [
                        ['question' => "Dans le SYSCOHADA, quelle classe regroupe les comptes de trésorerie (banque, caisse) ?", 'options' => ['Classe 1', 'Classe 5', 'Classe 7', 'Classe 9'], 'correct' => [1], 'explanation' => "La classe 5 regroupe les comptes de trésorerie : banque (52), caisse (57)."],
                        ['question' => "Quelles classes servent à établir le compte de résultat ?", 'options' => ['Classes 6 et 7 (charges et produits)', 'Classe 1', 'Classes 2 et 3', 'Classe 5'], 'correct' => [0], 'explanation' => "Les classes 6 (charges) et 7 (produits) alimentent le compte de résultat."],
                        ['question' => "Le compte 411 correspond à...", 'options' => ['Les fournisseurs', 'Les clients', 'La caisse', 'Le capital'], 'correct' => [1], 'explanation' => "Le compte 411 « Clients » enregistre les créances sur les clients."],
                    ],
                ],
                [
                    'title' => 'Niveau 5 — Le journal',
                    'subtitle' => "Enregistrer les opérations au quotidien",
                    'xp_reward' => 140,
                    'content' => "🎯 **Objectif** : savoir passer une écriture dans le journal comptable.\n\n💡 Le **journal** est le registre où l'on enregistre chronologiquement chaque opération, jour après jour, sous forme d'écritures. Une écriture comporte : la date, le(s) compte(s) débité(s), le(s) compte(s) crédité(s), les montants et un libellé.\n\nRègle de présentation : on inscrit toujours le **débit en premier**, puis le **crédit en dessous** (légèrement décalé).\n\n✅ Exemple : le 14/06, achat de marchandises 300 000 FCFA payé par banque.\n```\n14/06   601 Achats de marchandises   300 000\n              521 Banque                     300 000\n        (Achat marchandises par virement)\n```\n\nAutre exemple : vente au comptant 150 000 FCFA encaissée en caisse.\n```\n14/06   571 Caisse                   150 000\n              701 Ventes de marchandises      150 000\n```\n\n⚠️ Chaque écriture doit être équilibrée (débit = crédit). Le journal est un document obligatoire et doit être tenu sans blanc ni rature.",
                    'questions' => [
                        ['question' => "Dans le journal, les opérations sont enregistrées...", 'options' => ['Par ordre alphabétique', 'Par ordre chronologique', 'Par montant croissant', 'De façon aléatoire'], 'correct' => [1], 'explanation' => "Le journal enregistre les opérations dans l'ordre chronologique, jour après jour."],
                        ['question' => "Dans une écriture de journal, on inscrit en premier...", 'options' => ['Le compte crédité', 'Le compte débité', 'Le libellé', 'Le solde'], 'correct' => [1], 'explanation' => "Par convention, le compte débité est inscrit en premier, suivi du compte crédité décalé."],
                        ['question' => "Que doit obligatoirement contenir une écriture de journal ?", 'options' => ['La date', 'Les comptes et montants', 'Le nom du client uniquement', 'Un libellé explicatif'], 'correct' => [0, 1, 3], 'explanation' => "Une écriture complète comporte la date, les comptes débités/crédités avec montants, et un libellé."],
                    ],
                ],
                [
                    'title' => 'Niveau 6 — Le grand livre',
                    'subtitle' => "Reporter et suivre chaque compte",
                    'xp_reward' => 150,
                    'content' => "🎯 **Objectif** : comprendre comment passer du journal au grand livre.\n\n💡 Le **grand livre** regroupe tous les comptes de l'entreprise. Chaque compte y figure avec l'ensemble de ses mouvements (débit/crédit) reportés depuis le journal. On parle de **report**.\n\nLe lien entre journal et grand livre :\n• Le **journal** = vision chronologique (toutes les opérations dans l'ordre du temps)\n• Le **grand livre** = vision par compte (tout ce qui concerne « Banque », tout ce qui concerne « Clients »...)\n\n✅ Exemple : le compte 521 Banque dans le grand livre, après deux opérations.\n```\nCompte 521 - Banque\nDébit                    Crédit\n500 000 (encaissement)   300 000 (achat)\n_______                  _______\nSolde débiteur : 200 000\n```\n\nLe **solde** de chaque compte du grand livre servira ensuite à construire la balance.\n\n⚠️ Le total des soldes débiteurs et créditeurs du grand livre doit toujours respecter l'égalité de la partie double. Le grand livre ne crée pas d'écriture nouvelle : il reclasse celles du journal.",
                    'questions' => [
                        ['question' => "Le grand livre présente les opérations...", 'options' => ['Par ordre chronologique', 'Regroupées par compte', 'Par fournisseur uniquement', 'Sans aucun ordre'], 'correct' => [1], 'explanation' => "Le grand livre regroupe tous les mouvements par compte, contrairement au journal qui est chronologique."],
                        ['question' => "L'opération qui consiste à transférer les écritures du journal vers le grand livre s'appelle...", 'options' => ['La clôture', 'Le report', 'La balance', "L'inventaire"], 'correct' => [1], 'explanation' => "Le report est l'action de transcrire les écritures du journal dans les comptes du grand livre."],
                        ['question' => "Le grand livre permet surtout de...", 'options' => ['Connaître le solde de chaque compte', "Suivre l'évolution d'un compte précis", 'Remplacer le journal', 'Calculer les salaires'], 'correct' => [0, 1], 'explanation' => "Le grand livre sert à suivre chaque compte et à en déterminer le solde ; il complète le journal sans le remplacer."],
                    ],
                ],
                [
                    'title' => 'Niveau 7 — La balance',
                    'subtitle' => "Vérifier l'égalité avant les états financiers",
                    'xp_reward' => 160,
                    'content' => "🎯 **Objectif** : construire et interpréter une balance des comptes.\n\n💡 La **balance** est un tableau qui récapitule tous les comptes du grand livre avec, pour chacun : le total des débits, le total des crédits, et le solde (débiteur ou créditeur). C'est un outil de **contrôle**.\n\nElle vérifie une double égalité :\n• Total des mouvements débit = Total des mouvements crédit\n• Total des soldes débiteurs = Total des soldes créditeurs\n\nSi ces égalités sont respectées, la comptabilité est arithmétiquement cohérente.\n\n✅ Exemple simplifié (en FCFA) :\n| Compte | Débit | Crédit | Solde D | Solde C |\n|--------|-------|--------|---------|---------|\n| 571 Caisse | 350 000 | 300 000 | 50 000 | |\n| 601 Achats | 300 000 | 0 | 300 000 | |\n| 701 Ventes | 0 | 350 000 | | 350 000 |\n| **Total** | 650 000 | 650 000 | 350 000 | 350 000 |\n\n⚠️ Attention : une balance équilibrée ne garantit pas l'absence d'erreur. Une opération enregistrée dans le mauvais compte (mais avec le bon montant) laisse la balance équilibrée tout en étant fausse.",
                    'questions' => [
                        ['question' => "À quoi sert principalement la balance ?", 'options' => ['À payer les fournisseurs', "À contrôler l'égalité débit/crédit de la comptabilité", 'À recruter du personnel', 'À fixer les prix de vente'], 'correct' => [1], 'explanation' => "La balance est un outil de contrôle vérifiant l'égalité des totaux débit/crédit et des soldes."],
                        ['question' => "Dans une balance, on retrouve pour chaque compte...", 'options' => ['Le total des débits', 'Le total des crédits', 'Le solde du compte', 'Le numéro de téléphone du client'], 'correct' => [0, 1, 2], 'explanation' => "La balance présente pour chaque compte ses totaux débit, crédit et son solde."],
                        ['question' => "Une balance équilibrée signifie que...", 'options' => ["Il n'y a aucune erreur possible", "La comptabilité est arithmétiquement cohérente, mais des erreurs d'imputation restent possibles", "L'entreprise est forcément bénéficiaire", "Le bilan est inutile"], 'correct' => [1], 'explanation' => "Une balance équilibrée prouve la cohérence arithmétique, mais pas l'absence d'erreurs d'imputation."],
                    ],
                ],
                [
                    'title' => 'Niveau 8 — Le bilan',
                    'subtitle' => "La photo du patrimoine à une date donnée",
                    'xp_reward' => 170,
                    'content' => "🎯 **Objectif** : comprendre la structure et la lecture du bilan.\n\n💡 Le **bilan** est un état financier qui présente, à une date donnée, ce que l'entreprise **possède** (l'actif) et ce qu'elle **doit** (le passif). C'est une photographie du patrimoine.\n\n• **Actif** (emplois) : ce que l'entreprise utilise — immobilisations (classe 2), stocks (3), créances clients (411), trésorerie (5).\n• **Passif** (ressources) : d'où vient l'argent — capital (classe 1), emprunts, dettes fournisseurs (401).\n\nÉgalité fondamentale : **Actif = Passif**.\n\n✅ Exemple (entreprise de Douala, en FCFA) :\n| ACTIF | Montant | PASSIF | Montant |\n|-------|---------|--------|---------|\n| Matériel | 2 000 000 | Capital | 3 000 000 |\n| Stocks | 800 000 | Emprunt | 500 000 |\n| Banque | 1 200 000 | Fournisseurs | 500 000 |\n| **Total** | 4 000 000 | **Total** | 4 000 000 |\n\n⚠️ Le bilan est ordonné par **liquidité** (actif) et par **exigibilité** (passif). Le résultat de l'exercice (bénéfice ou perte) figure au passif et fait le lien avec le compte de résultat.",
                    'questions' => [
                        ['question' => "Le bilan représente...", 'options' => ["L'activité sur une période", 'Le patrimoine à une date donnée', 'Le planning des salariés', 'Les prix des concurrents'], 'correct' => [1], 'explanation' => "Le bilan est une photographie du patrimoine (actif et passif) à une date précise."],
                        ['question' => "Quelle égalité fondamentale caractérise le bilan ?", 'options' => ['Charges = Produits', 'Actif = Passif', 'Débit > Crédit', 'Ventes = Achats'], 'correct' => [1], 'explanation' => "Le bilan respecte toujours l'égalité Actif = Passif."],
                        ['question' => "Lesquels de ces éléments figurent à l'actif du bilan ?", 'options' => ['Les immobilisations (matériel)', 'Le capital social', 'La trésorerie (banque, caisse)', 'Les créances clients'], 'correct' => [0, 2, 3], 'explanation' => "Immobilisations, trésorerie et créances clients sont des actifs ; le capital est au passif."],
                    ],
                ],
                [
                    'title' => 'Niveau 9 — Le compte de résultat et la TVA',
                    'subtitle' => "Mesurer la performance et gérer la taxe",
                    'xp_reward' => 185,
                    'content' => "🎯 **Objectif** : calculer un résultat et comprendre le mécanisme de la TVA.\n\n💡 Le **compte de résultat** récapitule les **produits** (classe 7) et les **charges** (classe 6) d'une période. Il mesure la performance :\n**Résultat = Produits − Charges**. Positif = bénéfice ; négatif = perte.\n\n✅ Exemple : Ventes 5 000 000 − (Achats 3 000 000 + Salaires 1 000 000) = **Bénéfice 1 000 000 FCFA**.\n\n💡 La **TVA** (Taxe sur la Valeur Ajoutée, 19,25 % au Cameroun) est collectée sur les ventes et déductible sur les achats. L'entreprise n'est qu'un intermédiaire : elle reverse la différence à l'État.\n**TVA à payer = TVA collectée (sur ventes) − TVA déductible (sur achats)**.\n\n✅ Exemple : TVA collectée 962 500 FCFA, TVA déductible 577 500 FCFA → à reverser : 385 000 FCFA.\n\n```\nVente HT 5 000 000\nTVA collectée 19,25%   962 500\nVente TTC            5 962 500\n```\n\n⚠️ Ne confonds pas la TVA (compte de tiers, classe 4) avec une charge : elle ne réduit pas le résultat, elle transite par l'entreprise.",
                    'questions' => [
                        ['question' => "Comment calcule-t-on le résultat de l'exercice ?", 'options' => ['Actif − Passif', 'Produits − Charges', 'Débit + Crédit', 'Ventes + Achats'], 'correct' => [1], 'explanation' => "Le résultat se calcule par la différence : Produits − Charges."],
                        ['question' => "La TVA à reverser à l'État correspond à...", 'options' => ['La TVA collectée seule', 'La TVA collectée moins la TVA déductible', 'La TVA déductible seule', 'Le total des ventes'], 'correct' => [1], 'explanation' => "On reverse la TVA collectée sur les ventes diminuée de la TVA déductible sur les achats."],
                        ['question' => "Pour l'entreprise, la TVA est avant tout...", 'options' => ['Une charge qui réduit le bénéfice', "Un montant collecté pour le compte de l'État", "Un produit de l'activité", 'Une immobilisation'], 'correct' => [1], 'explanation' => "La TVA transite par l'entreprise qui la collecte pour l'État ; ce n'est ni une charge ni un produit."],
                    ],
                ],
                [
                    'title' => 'Niveau 10 — Clôture et états financiers',
                    'subtitle' => "Inventaire, régularisations et synthèse",
                    'xp_reward' => 200,
                    'content' => "🎯 **Objectif** : réaliser les opérations de clôture et produire les états financiers de fin d'exercice.\n\n💡 La **clôture** (généralement au 31 décembre) consiste à arrêter les comptes pour établir les états financiers. Étapes clés :\n• **Inventaire physique** : compter les stocks, vérifier les immobilisations.\n• **Amortissements** : constater la perte de valeur des immobilisations (ex : matériel amorti sur 5 ans).\n• **Provisions** : anticiper les risques et créances douteuses.\n• **Régularisations** : charges/produits à rattacher au bon exercice.\n\nEnsuite, on produit les **états financiers SYSCOHADA** :\n• Le **bilan** (patrimoine)\n• Le **compte de résultat** (performance)\n• Le **TAFIRE / tableau des flux** (mouvements de trésorerie)\n• Les **notes annexes**\n\n✅ Exemple : un véhicule de 5 000 000 FCFA amorti linéairement sur 5 ans génère une dotation annuelle de 1 000 000 FCFA, enregistrée en charge.\n\n```\n31/12   681 Dotations amortissements   1 000 000\n              2841 Amort. matériel transport   1 000 000\n```\n\n🏆 **Félicitations !** Tu maîtrises désormais le cycle comptable complet, du journal aux états financiers. Débouchés : aide-comptable, comptable, assistant de gestion, puis évolution vers chef comptable, contrôleur de gestion ou expert-comptable. Ces compétences sont très recherchées dans les PME, cabinets et entreprises de l'espace OHADA. Continue avec la fiscalité et la comptabilité analytique pour aller plus loin !",
                    'questions' => [
                        ['question' => "À quoi sert l'amortissement lors de la clôture ?", 'options' => ['À augmenter le bénéfice', 'À constater la perte de valeur des immobilisations', 'À encaisser les clients', 'À payer la TVA'], 'correct' => [1], 'explanation' => "L'amortissement constate comptablement la dépréciation des immobilisations dans le temps."],
                        ['question' => "Quels documents font partie des états financiers SYSCOHADA ?", 'options' => ['Le bilan', 'Le compte de résultat', 'Le menu du restaurant', 'Les notes annexes'], 'correct' => [0, 1, 3], 'explanation' => "Les états financiers comprennent notamment le bilan, le compte de résultat et les notes annexes."],
                        ['question' => "Les opérations de clôture comprennent généralement...", 'options' => ["L'inventaire physique des stocks", 'Le calcul des amortissements', 'Le recrutement du personnel', 'Les régularisations de charges et produits'], 'correct' => [0, 1, 3], 'explanation' => "La clôture inclut l'inventaire, les amortissements et les régularisations, mais pas le recrutement."],
                    ],
                ],
            ],
        ]);

        $this->command->info('  ✓ Roadmap Comptabilité Générale créée (10 niveaux).');
    }
}
