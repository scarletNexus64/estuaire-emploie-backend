<?php

namespace Database\Seeders\Roadmaps;

use Illuminate\Database\Seeder;

/**
 * Roadmap Pharmacie d'Officine — devenir un acteur clé de la dispensation et du conseil pharmaceutique.
 */
class PharmacieOfficineRoadmapSeeder extends Seeder
{
    use RoadmapSeederHelper;

    public function run(): void
    {
        $this->createRoadmap([
            'title' => "Deviens Pharmacien d'Officine",
            'slug' => 'pharmacie-officine',
            'domain' => 'sante',
            'description' => "Maîtrise le métier de pharmacien d'officine : dispensation des médicaments, lecture des ordonnances, conseil au patient, détection des interactions et respect de la réglementation. Une formation pratique ancrée dans le contexte camerounais et de l'espace OHADA.",
            'objectives' => "Comprendre le rôle et les responsabilités du pharmacien d'officine\nLire et valider une ordonnance médicale\nMaîtriser les formes galéniques et leurs usages\nDétecter les interactions médicamenteuses dangereuses\nDélivrer un conseil pharmaceutique de qualité au comptoir\nEncadrer l'automédication responsable\nGérer le stock et la chaîne du froid\nParticiper à la pharmacovigilance et respecter la réglementation",
            'icon' => '💊',
            'color' => '#0EA5E9',
            'difficulty' => 'intermediate',
            'required_packs' => [],
            'pass_threshold' => 70,
            'order' => 20,
            'levels' => [
                [
                    'title' => "Niveau 1 — Le rôle du pharmacien d'officine",
                    'subtitle' => "Bien plus qu'un vendeur de médicaments",
                    'xp_reward' => 100,
                    'content' => "🎯 **Objectif** : comprendre la place centrale du pharmacien dans le parcours de soins.\n\n💡 Le pharmacien d'officine est un professionnel de santé. À Douala comme à Yaoundé, il est souvent le premier interlocuteur du patient, accessible sans rendez-vous.\n\nSes missions clés :\n• **Dispensation** : délivrer le bon médicament, à la bonne personne, avec les bonnes explications.\n• **Conseil** : orienter vers le médecin si nécessaire, expliquer la posologie.\n• **Sécurité** : vérifier l'absence d'interactions ou de contre-indications.\n• **Santé publique** : participer aux campagnes de vaccination et de dépistage.\n\n✅ Le pharmacien engage sa responsabilité professionnelle à chaque délivrance. Il peut refuser de délivrer un médicament si la sécurité du patient est en jeu (droit de substitution et de refus motivé).\n\n⚠️ Lutter contre les médicaments de la rue (\"médicaments de la rue\" / contrefaçons), un fléau de santé publique en Afrique, fait partie de sa mission citoyenne.",
                    'questions' => [
                        ['question' => "Quelle est la mission première du pharmacien d'officine ?", 'options' => ['Vendre le maximum de produits', 'Dispenser les médicaments en garantissant leur bon usage', 'Remplacer le médecin', 'Fabriquer tous les médicaments'], 'correct' => [1], 'explanation' => "La dispensation sécurisée et le bon usage du médicament sont au cœur du métier."],
                        ['question' => "Le pharmacien peut-il refuser de délivrer un médicament ?", 'options' => ['Jamais, il doit toujours servir', 'Oui, s\'il estime que la sécurité du patient est menacée', 'Seulement le week-end', 'Uniquement sur ordre de la police'], 'correct' => [1], 'explanation' => "Le pharmacien a un droit de refus motivé lorsqu'un risque pour le patient existe."],
                        ['question' => "Pourquoi lutter contre les médicaments de la rue ?", 'options' => ['Pour protéger son chiffre d\'affaires', 'Parce qu\'ils sont souvent contrefaits, mal conservés et dangereux', 'Parce qu\'ils sont trop chers', 'Aucune raison valable'], 'correct' => [1], 'explanation' => "Les médicaments informels échappent au contrôle qualité et exposent à des risques graves."],
                    ],
                ],
                [
                    'title' => "Niveau 2 — Lire et valider une ordonnance",
                    'subtitle' => "Décoder la prescription en toute sécurité",
                    'xp_reward' => 110,
                    'content' => "🎯 **Objectif** : analyser une ordonnance avant toute délivrance.\n\n💡 Une ordonnance valide doit comporter des mentions obligatoires :\n• Identité et qualification du prescripteur (nom, signature, cachet)\n• Date de prescription\n• Identité du patient (nom, âge, parfois poids pour les enfants)\n• Le médicament : DCI ou nom commercial, **dosage**, **posologie**, **durée**\n\n📋 Exemple de lecture :\n\n| Élément | Lecture |\n|---------|---------|\n| Amoxicilline 500 mg | dosage par prise |\n| 1 gélule x 3 / jour | posologie |\n| pendant 7 jours | durée |\n\n✅ La **validation pharmaceutique** consiste à vérifier la cohérence : dose adaptée à l'âge, durée logique, absence de redondance.\n\n⚠️ Méfiance face aux ordonnances suspectes : écriture modifiée, dosage anormal de psychotropes, absence de cachet. Une ordonnance falsifiée est un délit.",
                    'questions' => [
                        ['question' => "Quelle mention n'est PAS obligatoire sur une ordonnance ?", 'options' => ['La signature du prescripteur', 'La date de prescription', 'La marque de la voiture du médecin', 'L\'identité du patient'], 'correct' => [2], 'explanation' => "Les mentions concernent le prescripteur, le patient, la date et le traitement, pas des détails personnels du médecin."],
                        ['question' => "Que signifie la posologie « 1 cp x 3 / jour » ?", 'options' => ['1 comprimé une fois par jour', '3 comprimés en une prise', '1 comprimé trois fois par jour', '3 comprimés par semaine'], 'correct' => [2], 'explanation' => "« x 3 / jour » indique trois prises d'un comprimé chacune dans la journée."],
                        ['question' => "Quels signes doivent alerter sur une possible falsification ? (plusieurs réponses)", 'options' => ['Dosage anormalement élevé de psychotropes', 'Écriture visiblement modifiée', 'Présence du cachet du médecin', 'Absence totale de signature'], 'correct' => [0, 1, 3], 'explanation' => "Un dosage suspect, une écriture retouchée et l'absence de signature sont des signaux d'alerte ; le cachet est au contraire normal."],
                    ],
                ],
                [
                    'title' => "Niveau 3 — La dispensation au comptoir",
                    'subtitle' => "Le bon médicament à la bonne personne",
                    'xp_reward' => 120,
                    'content' => "🎯 **Objectif** : maîtriser l'acte de dispensation, de l'analyse à la remise du produit.\n\n💡 La dispensation suit des étapes structurées :\n• **Analyser** la demande (ordonnance ou demande spontanée)\n• **Vérifier** dosage, contre-indications, interactions\n• **Préparer** la délivrance (substitution générique possible)\n• **Conseiller** : posologie, durée, conditions de prise\n\n✅ La **substitution générique** : remplacer un princeps par un générique de même DCI, même dosage, même forme. Cela réduit le coût pour le patient, un enjeu majeur au Cameroun où le reste à charge est élevé.\n\n📋 Toujours s'assurer que le patient a compris :\n• À jeun ou pendant le repas ?\n• Combien de fois par jour ?\n• Pendant combien de temps ?\n\n⚠️ Ne jamais délivrer un médicament dont la date de péremption est dépassée. Vérifier l'intégrité de l'emballage avant remise.",
                    'questions' => [
                        ['question' => "Qu'est-ce que la substitution générique ?", 'options' => ['Donner un médicament moins cher au hasard', 'Remplacer un princeps par un générique de même DCI, dosage et forme', 'Supprimer un médicament de l\'ordonnance', 'Doubler la dose'], 'correct' => [1], 'explanation' => "Le générique a la même substance active, le même dosage et la même forme que le princeps."],
                        ['question' => "Avant de remettre une boîte, le pharmacien doit vérifier :", 'options' => ['La couleur préférée du patient', 'La date de péremption et l\'intégrité de l\'emballage', 'Le poids de la boîte uniquement', 'Rien, la confiance suffit'], 'correct' => [1], 'explanation' => "Un produit périmé ou à l'emballage altéré ne doit jamais être délivré."],
                        ['question' => "Quel est l'intérêt majeur du générique au Cameroun ?", 'options' => ['Il est plus joli', 'Il réduit le coût pour le patient à efficacité équivalente', 'Il agit plus vite', 'Il est sans effet secondaire'], 'correct' => [1], 'explanation' => "À efficacité équivalente, le générique allège le reste à charge du patient."],
                    ],
                ],
                [
                    'title' => "Niveau 4 — Les formes galéniques",
                    'subtitle' => "Comprendre comment le médicament agit",
                    'xp_reward' => 130,
                    'content' => "🎯 **Objectif** : connaître les principales formes galéniques et leurs usages.\n\n💡 La **forme galénique** est la présentation finale du médicament destinée à être administrée.\n\n📋 Principales formes :\n• **Voie orale** : comprimés, gélules, sirops, sachets. La plus courante.\n• **Voie injectable** : ampoules, flacons (IM, IV, SC) — action rapide.\n• **Voie locale** : pommades, crèmes, collyres, gouttes auriculaires.\n• **Voie rectale** : suppositoires (utile chez l'enfant qui vomit).\n• **Formes à libération prolongée (LP)** : à ne jamais écraser !\n\n✅ Le choix de la forme dépend du patient :\n• Un enfant ou une personne âgée préférera un **sirop** ou un comprimé orodispersible.\n• Une urgence justifie la voie **injectable**.\n\n⚠️ Écraser un comprimé LP ou gastro-résistant détruit son mécanisme : risque de surdosage brutal. Toujours prévenir le patient.",
                    'questions' => [
                        ['question' => "Qu'est-ce qu'une forme galénique ?", 'options' => ['Le nom commercial du médicament', 'La présentation finale destinée à l\'administration', 'Le prix du médicament', 'Le laboratoire fabricant'], 'correct' => [1], 'explanation' => "La forme galénique est la mise en forme du principe actif pour son administration."],
                        ['question' => "Pourquoi ne faut-il pas écraser un comprimé à libération prolongée (LP) ?", 'options' => ['Cela le rend amer', 'Cela libère toute la dose d\'un coup et risque un surdosage', 'Cela ne change rien', 'Cela améliore son action'], 'correct' => [1], 'explanation' => "Écraser un comprimé LP détruit la libération progressive et provoque un pic dangereux."],
                        ['question' => "Quelle forme convient le mieux à un jeune enfant qui vomit ?", 'options' => ['Un gros comprimé sec', 'Un suppositoire ou un sirop', 'Une gélule de gros calibre', 'Une injection systématique'], 'correct' => [1], 'explanation' => "Le suppositoire (voie rectale) ou le sirop sont adaptés quand la voie orale est difficile."],
                    ],
                ],
                [
                    'title' => "Niveau 5 — Les interactions médicamenteuses",
                    'subtitle' => "Détecter les associations dangereuses",
                    'xp_reward' => 140,
                    'content' => "🎯 **Objectif** : identifier et prévenir les interactions à risque.\n\n💡 Une **interaction médicamenteuse** survient quand un médicament modifie l'effet d'un autre. Elle peut diminuer l'efficacité ou augmenter la toxicité.\n\n📋 Exemples classiques à connaître :\n• **Anticoagulants (warfarine) + AINS (ibuprofène)** : risque hémorragique majeur.\n• **Pamplemousse + certaines statines** : surdosage.\n• **Antiacides + certains antibiotiques (cyclines)** : baisse d'absorption.\n• **Alcool + métronidazole** : effet antabuse (malaise violent).\n\n✅ Bonnes pratiques :\n• Toujours demander au patient ce qu'il prend **déjà** (y compris la phytothérapie locale et l'automédication).\n• Espacer les prises quand l'absorption est en jeu.\n• Utiliser un logiciel d'aide à la dispensation ou un thésaurus d'interactions.\n\n⚠️ Les interactions ne concernent pas que les médicaments : aliments, plantes (millepertuis) et compléments comptent aussi.",
                    'questions' => [
                        ['question' => "L'association anticoagulant + AINS expose principalement à :", 'options' => ['Une somnolence', 'Un risque hémorragique majeur', 'Une perte de cheveux', 'Aucun risque'], 'correct' => [1], 'explanation' => "Les AINS majorent le risque de saignement chez un patient sous anticoagulant."],
                        ['question' => "Quelle question doit systématiquement poser le pharmacien pour prévenir les interactions ?", 'options' => ['« Quel est votre métier ? »', '« Quels médicaments ou plantes prenez-vous déjà ? »', '« Quelle est votre couleur préférée ? »', '« Habitez-vous loin ? »'], 'correct' => [1], 'explanation' => "Connaître les traitements en cours, y compris la phytothérapie, est essentiel pour repérer les interactions."],
                        ['question' => "Quelles associations sont des interactions à risque ? (plusieurs réponses)", 'options' => ['Alcool + métronidazole', 'Eau + paracétamol', 'Anticoagulant + ibuprofène', 'Antiacide + cycline'], 'correct' => [0, 2, 3], 'explanation' => "Alcool/métronidazole, anticoagulant/AINS et antiacide/cycline sont des interactions connues ; eau + paracétamol est sans danger."],
                    ],
                ],
                [
                    'title' => "Niveau 6 — Le conseil au patient",
                    'subtitle' => "Communiquer pour une meilleure observance",
                    'xp_reward' => 150,
                    'content' => "🎯 **Objectif** : délivrer un conseil clair qui favorise l'observance du traitement.\n\n💡 L'**observance** est le respect par le patient de son traitement. Une mauvaise observance est une cause majeure d'échec thérapeutique, notamment pour le paludisme, le VIH ou l'hypertension.\n\n✅ Les piliers d'un bon conseil :\n• **Clarté** : éviter le jargon, reformuler en langue locale si besoin.\n• **Concret** : « 1 comprimé matin et soir, après le repas ».\n• **Vérification** : faire répéter le patient.\n• **Effets indésirables** : prévenir des plus fréquents pour éviter l'arrêt prématuré.\n\n📋 Conseils associés selon le cas :\n• Antibiotique : aller jusqu'au bout du traitement.\n• Antipaludique : importance de la dose complète contre les résistances.\n• Hypertenseur : ne pas arrêter même si on se sent mieux.\n\n⚠️ Adapter le message au niveau de compréhension et au contexte culturel du patient. La confidentialité au comptoir est un droit.",
                    'questions' => [
                        ['question' => "Qu'est-ce que l'observance ?", 'options' => ['Le respect du traitement par le patient', 'La surveillance par la police', 'Le prix du médicament', 'Le délai de péremption'], 'correct' => [0], 'explanation' => "L'observance désigne la bonne prise du traitement selon la prescription."],
                        ['question' => "Pour un antibiotique, quel conseil est essentiel ?", 'options' => ['Arrêter dès qu\'on va mieux', 'Aller jusqu\'au bout du traitement prescrit', 'Doubler les doses', 'Le partager avec la famille'], 'correct' => [1], 'explanation' => "Arrêter un antibiotique trop tôt favorise les résistances bactériennes."],
                        ['question' => "Quelle pratique améliore la compréhension du patient ?", 'options' => ['Utiliser un maximum de termes techniques', 'Faire répéter le patient et reformuler simplement', 'Parler très vite', 'Ne donner aucune explication'], 'correct' => [1], 'explanation' => "Faire reformuler le patient permet de vérifier qu'il a bien compris son traitement."],
                    ],
                ],
                [
                    'title' => "Niveau 7 — L'automédication responsable",
                    'subtitle' => "Encadrer la demande spontanée",
                    'xp_reward' => 160,
                    'content' => "🎯 **Objectif** : encadrer l'automédication pour la rendre sûre.\n\n💡 L'**automédication** est l'usage de médicaments sans prescription, pour des symptômes bénins (maux de tête, rhume, douleurs légères). Le pharmacien en est le garant.\n\n✅ La méthode des questions clés (questionnement structuré) :\n• **Pour qui ?** (adulte, enfant, femme enceinte)\n• **Quels symptômes ?** depuis quand ?\n• **Quels traitements déjà pris ?**\n• **Antécédents / allergies ?**\n\n📋 Limites de l'automédication — orienter vers le médecin si :\n• Fièvre élevée persistante (penser au paludisme grave)\n• Symptômes durant plus de quelques jours\n• Femme enceinte ou nourrisson\n• Douleur thoracique, signes de gravité\n\n⚠️ Le paracétamol est sûr mais hépatotoxique en surdosage. Les AINS sont contre-indiqués en fin de grossesse. Ne jamais banaliser une demande répétée de codéine ou de tramadol (risque de mésusage).",
                    'questions' => [
                        ['question' => "Dans quel cas faut-il orienter vers le médecin plutôt que conseiller l'automédication ?", 'options' => ['Un léger mal de tête passager', 'Une fièvre élevée persistante chez un patient au Cameroun', 'Une petite coupure', 'Un rhume d\'un jour'], 'correct' => [1], 'explanation' => "Une fièvre élevée persistante peut signaler un paludisme grave nécessitant une consultation."],
                        ['question' => "Quel risque présente un surdosage de paracétamol ?", 'options' => ['Une toxicité hépatique grave', 'Une simple somnolence', 'Aucun risque', 'Une chute de cheveux'], 'correct' => [0], 'explanation' => "Le paracétamol en surdosage est gravement toxique pour le foie."],
                        ['question' => "Lors d'une demande spontanée, le pharmacien doit demander : (plusieurs réponses)", 'options' => ['Pour qui est le médicament', 'Les symptômes et leur durée', 'Le salaire du patient', 'Les antécédents et allergies'], 'correct' => [0, 1, 3], 'explanation' => "Le destinataire, les symptômes et les antécédents guident un conseil sûr ; le salaire n'est pas pertinent."],
                    ],
                ],
                [
                    'title' => "Niveau 8 — Gestion du stock et chaîne du froid",
                    'subtitle' => "Une officine bien approvisionnée et sûre",
                    'xp_reward' => 170,
                    'content' => "🎯 **Objectif** : gérer le stock pharmaceutique pour éviter ruptures et pertes.\n\n💡 Une bonne gestion garantit la disponibilité des médicaments tout en limitant les périmés.\n\n✅ Principes clés :\n• **FEFO** (First Expired, First Out) : on délivre d'abord ce qui périme le plus tôt.\n• **Stock minimum / point de commande** : déclencher la commande avant la rupture.\n• **Inventaire régulier** : pour détecter écarts et vols.\n• **Traçabilité des lots** : indispensable en cas de rappel.\n\n❄️ La **chaîne du froid** (2–8 °C) concerne vaccins, insuline, certains antibiotiques. Au Cameroun, les coupures d'électricité imposent :\n• Un réfrigérateur dédié avec thermomètre\n• Un relevé quotidien des températures\n• Des solutions de secours (groupe électrogène, glacières)\n\n⚠️ Un vaccin ayant rompu la chaîne du froid devient inefficace ou dangereux et doit être détruit, pas délivré. Le paiement par mobile money facilite le suivi des règlements fournisseurs.",
                    'questions' => [
                        ['question' => "Que signifie le principe FEFO ?", 'options' => ['First Expired, First Out : délivrer d\'abord ce qui périme en premier', 'Faire Entrer les Factures Officielles', 'Garder les nouveaux produits devant', 'Vendre le plus cher d\'abord'], 'correct' => [0], 'explanation' => "FEFO consiste à écouler en priorité les produits dont la date de péremption est la plus proche."],
                        ['question' => "Quelle plage de température définit la chaîne du froid pharmaceutique ?", 'options' => ['-20 à -10 °C', '2 à 8 °C', '20 à 30 °C', '40 à 50 °C'], 'correct' => [1], 'explanation' => "La chaîne du froid standard pour vaccins et insuline est de 2 à 8 °C."],
                        ['question' => "Que faire d'un vaccin ayant subi une rupture de la chaîne du froid ?", 'options' => ['Le délivrer quand même', 'Le détruire car il peut être inefficace ou dangereux', 'Le remettre au congélateur', 'Le revendre moins cher'], 'correct' => [1], 'explanation' => "Un vaccin dont la chaîne du froid est rompue n'est plus fiable et ne doit pas être utilisé."],
                    ],
                ],
                [
                    'title' => "Niveau 9 — La pharmacovigilance",
                    'subtitle' => "Surveiller la sécurité des médicaments",
                    'xp_reward' => 185,
                    'content' => "🎯 **Objectif** : comprendre et pratiquer la pharmacovigilance.\n\n💡 La **pharmacovigilance** est la surveillance des effets indésirables des médicaments après leur mise sur le marché. Elle protège la population.\n\n📋 Un **effet indésirable** est une réaction nocive et non voulue à un médicament. Exemples :\n• Éruption cutanée après un antibiotique\n• Saignement sous anticoagulant\n• Choc anaphylactique (urgence)\n\n✅ Le rôle du pharmacien :\n• **Détecter** et écouter le patient signalant un effet.\n• **Déclarer** au centre national de pharmacovigilance (au Cameroun, structure rattachée au Ministère de la Santé).\n• **Conseiller** : arrêt et consultation selon la gravité.\n\nUne déclaration utile contient : le médicament suspecté, l'effet, sa date d'apparition, l'évolution, les traitements associés.\n\n⚠️ Tout effet, même bénin ou rare, mérite d'être signalé : c'est ainsi qu'on détecte les médicaments dangereux ou les contrefaçons circulant sur le marché.",
                    'questions' => [
                        ['question' => "Qu'est-ce que la pharmacovigilance ?", 'options' => ['La surveillance du chiffre d\'affaires', 'La surveillance des effets indésirables des médicaments', 'Le gardiennage de l\'officine', 'Le contrôle des prix'], 'correct' => [1], 'explanation' => "La pharmacovigilance surveille les effets indésirables après commercialisation."],
                        ['question' => "Que doit faire le pharmacien face à un effet indésirable signalé par un patient ?", 'options' => ['L\'ignorer si c\'est rare', 'Le déclarer au centre de pharmacovigilance', 'Le cacher pour ne pas inquiéter', 'Augmenter la dose'], 'correct' => [1], 'explanation' => "Tout effet indésirable doit être déclaré pour protéger l'ensemble de la population."],
                        ['question' => "Quels éléments rendent une déclaration utile ? (plusieurs réponses)", 'options' => ['Le médicament suspecté', 'L\'effet observé et sa date d\'apparition', 'La marque du téléphone du patient', 'Les traitements associés'], 'correct' => [0, 1, 3], 'explanation' => "Le médicament, l'effet, sa chronologie et les traitements associés sont nécessaires ; la marque du téléphone ne l'est pas."],
                    ],
                ],
                [
                    'title' => "Niveau 10 — Réglementation et déontologie",
                    'subtitle' => "Exercer dans le cadre légal et éthique",
                    'xp_reward' => 200,
                    'content' => "🎯 **Objectif** : maîtriser le cadre réglementaire de l'exercice officinal.\n\n💡 Le médicament est un produit réglementé. Sa mise sur le marché exige une **AMM** (Autorisation de Mise sur le Marché) délivrée par l'autorité de santé.\n\n📋 Classement des médicaments :\n• **Liste I et II** (substances vénéneuses) : délivrance sur ordonnance, conservation séparée.\n• **Stupéfiants** : ordonnance sécurisée, registre, comptabilité stricte.\n• **Médicaments de médication officinale** : accès sans ordonnance.\n\n✅ Règles de l'officine :\n• La pharmacie doit être dirigée par un pharmacien diplômé et inscrit à l'Ordre.\n• Respect du secret professionnel et du Code de déontologie.\n• Dans l'espace OHADA, la société d'exploitation respecte aussi le droit des affaires harmonisé.\n\n⚠️ La vente de médicaments hors circuit légal (marché informel) est interdite et dangereuse.\n\n🏆 **Félicitations !** Tu maîtrises désormais les fondamentaux du pharmacien d'officine : dispensation, conseil, sécurité et réglementation. Débouchés : titulaire d'officine, pharmacien adjoint, pharmacien hospitalier, grossiste-répartiteur, industrie pharmaceutique, ou inspecteur de pharmacie. Ton expertise est précieuse pour la santé publique en Afrique. Continue à te former : la pharmacie évolue sans cesse !",
                    'questions' => [
                        ['question' => "Que signifie l'AMM ?", 'options' => ['Autorisation de Mise sur le Marché', 'Association des Médecins Modernes', 'Agence du Médicament Maritime', 'Achat Mensuel Minimum'], 'correct' => [0], 'explanation' => "L'AMM est l'autorisation officielle permettant la commercialisation d'un médicament."],
                        ['question' => "Comment sont délivrés les médicaments stupéfiants ?", 'options' => ['Librement comme des bonbons', 'Sur ordonnance sécurisée avec registre et comptabilité stricte', 'Uniquement en ligne', 'Sans aucun contrôle'], 'correct' => [1], 'explanation' => "Les stupéfiants exigent une ordonnance sécurisée et une traçabilité rigoureuse."],
                        ['question' => "Qui doit diriger une officine pharmaceutique ?", 'options' => ['N\'importe quel commerçant', 'Un pharmacien diplômé et inscrit à l\'Ordre', 'Un médecin uniquement', 'Un agent de l\'État'], 'correct' => [1], 'explanation' => "La direction d'une officine est réservée à un pharmacien diplômé et inscrit à l'Ordre."],
                    ],
                ],
            ],
        ]);

        $this->command->info('  ✓ Roadmap Pharmacie d\'Officine créée (10 niveaux).');
    }
}
