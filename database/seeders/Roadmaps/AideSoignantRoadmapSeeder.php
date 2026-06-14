<?php

namespace Database\Seeders\Roadmaps;

use Illuminate\Database\Seeder;

/**
 * Roadmap Aide-Soignant — apprends les bases du métier d'aide-soignant : hygiène, confort, surveillance et bientraitance.
 */
class AideSoignantRoadmapSeeder extends Seeder
{
    use RoadmapSeederHelper;

    public function run(): void
    {
        $this->createRoadmap([
            'title' => 'Deviens Aide-Soignant',
            'slug' => 'aide-soignant',
            'domain' => 'sante',
            'description' => "Une formation complète et pratique pour découvrir le métier d'aide-soignant. Tu apprendras à accompagner les patients au quotidien : soins d'hygiène, aide à la mobilité, prévention des escarres, prise des constantes, observation, alimentation et bientraitance. Un parcours pensé pour le contexte des hôpitaux et structures de santé d'Afrique francophone (Douala, Yaoundé), du débutant à un niveau opérationnel.",
            'objectives' => "Comprendre le rôle et les limites de l'aide-soignant dans l'équipe de soins\nRéaliser les soins d'hygiène et de confort en respectant la dignité du patient\nAider le patient à se déplacer et à se mobiliser en sécurité\nPrévenir et repérer les escarres chez les personnes alitées\nPrendre et noter les constantes simples (température, pouls, tension)\nObserver le patient et donner l'alerte au bon moment\nAccompagner l'alimentation et l'hydratation\nAppliquer les principes de bientraitance et travailler efficacement en équipe",
            'icon' => '🩺',
            'color' => '#0EA5E9',
            'difficulty' => 'beginner',
            'required_packs' => [],
            'pass_threshold' => 70,
            'order' => 20,
            'levels' => [
                [
                    'title' => 'Niveau 1 — Le rôle de l\'aide-soignant',
                    'subtitle' => 'Comprendre ta place dans l\'équipe de soins',
                    'xp_reward' => 100,
                    'content' => "🎯 **Objectif** : situer le métier d'aide-soignant et ses missions.\n\n💡 L'aide-soignant (AS) accompagne le patient dans les gestes de la vie quotidienne et veille à son confort. Il travaille **sous la responsabilité de l'infirmier**, qui supervise les soins.\n\n✅ Tes missions principales :\n• Aider à la toilette, à l'habillage et aux repas\n• Surveiller l'état général et signaler tout changement\n• Refaire le lit, installer le patient confortablement\n• Accompagner et rassurer la personne\n\n⚠️ Ce que tu ne fais PAS seul : poser une perfusion, distribuer des médicaments injectables, faire un diagnostic. Ce sont des actes infirmiers ou médicaux.\n\nDans un hôpital de Douala comme dans un centre de santé de village, l'AS est souvent le soignant le plus proche du patient au quotidien. Ton observation et ta bienveillance sont essentielles à la qualité des soins.",
                    'questions' => [
                        ['question' => 'Sous la responsabilité de qui l\'aide-soignant travaille-t-il ?', 'options' => ['Le pharmacien', 'L\'infirmier', 'Le brancardier', 'Le patient'], 'correct' => [1], 'explanation' => 'L\'aide-soignant exerce ses missions sous la responsabilité et la supervision de l\'infirmier.'],
                        ['question' => 'Parmi ces actes, lequel relève bien du rôle de l\'aide-soignant ?', 'options' => ['Poser une perfusion', 'Faire un diagnostic', 'Aider à la toilette', 'Prescrire un médicament'], 'correct' => [2], 'explanation' => 'L\'aide à la toilette fait partie des soins d\'hygiène et de confort, cœur du métier d\'AS.'],
                        ['question' => 'Quelles missions font partie du quotidien de l\'aide-soignant ? (plusieurs réponses)', 'options' => ['Surveiller l\'état général', 'Réaliser une opération chirurgicale', 'Aider aux repas', 'Refaire le lit du patient'], 'correct' => [0, 2, 3], 'explanation' => 'La surveillance, l\'aide aux repas et la réfection du lit sont des missions de l\'AS ; la chirurgie est un acte médical.'],
                    ],
                ],
                [
                    'title' => 'Niveau 2 — Hygiène des mains et prévention des infections',
                    'subtitle' => 'Le premier geste qui sauve',
                    'xp_reward' => 110,
                    'content' => "🎯 **Objectif** : maîtriser l'hygiène des mains pour éviter de transmettre les microbes.\n\n💡 Les mains sont le principal vecteur de transmission des infections à l'hôpital. Un bon lavage protège le patient ET le soignant.\n\n✅ Les moments clés du lavage des mains :\n• Avant et après chaque contact avec un patient\n• Avant un soin propre (toilette, pansement)\n• Après tout contact avec des liquides corporels\n• Après avoir retiré les gants\n\nDéroulé d'une friction hydro-alcoolique (SHA) :\n• Paume contre paume\n• Dos des mains, espaces entre les doigts\n• Pouces et bouts des doigts\n• Frotter 20 à 30 secondes jusqu'à séchage\n\n⚠️ Les gants ne remplacent PAS le lavage des mains. On se lave les mains avant de les enfiler et après les avoir retirés. Ongles courts, pas de bijoux aux mains : ils retiennent les microbes.",
                    'questions' => [
                        ['question' => 'Combien de temps environ doit durer une friction hydro-alcoolique ?', 'options' => ['2 à 3 secondes', '20 à 30 secondes', '2 minutes', '5 minutes'], 'correct' => [1], 'explanation' => 'Une friction efficace dure 20 à 30 secondes, le temps que le produit agisse et que les mains sèchent.'],
                        ['question' => 'Le port de gants dispense-t-il du lavage des mains ?', 'options' => ['Oui, toujours', 'Non, le lavage reste nécessaire avant et après', 'Oui, si les gants sont stériles', 'Seulement la nuit'], 'correct' => [1], 'explanation' => 'Les gants ne remplacent pas le lavage : on se lave les mains avant de les mettre et après les avoir retirés.'],
                        ['question' => 'Quels sont des moments où il faut se laver les mains ? (plusieurs réponses)', 'options' => ['Avant un contact avec le patient', 'Après contact avec des liquides corporels', 'Une seule fois en début de journée', 'Après avoir retiré les gants'], 'correct' => [0, 1, 3], 'explanation' => 'L\'hygiène des mains se fait à chaque moment clé du soin, pas une seule fois par jour.'],
                    ],
                ],
                [
                    'title' => 'Niveau 3 — Soins d\'hygiène et de confort',
                    'subtitle' => 'La toilette dans le respect de la dignité',
                    'xp_reward' => 120,
                    'content' => "🎯 **Objectif** : réaliser une toilette propre et respectueuse.\n\n💡 La toilette n'est pas qu'un soin technique : c'est un moment de relation et de respect de l'intimité. On préserve toujours la **pudeur** du patient.\n\n✅ Bonnes pratiques :\n• Préparer tout le matériel avant de commencer (eau tiède, savon doux, gants de toilette, serviettes)\n• Fermer la porte ou tirer le rideau\n• Expliquer ce qu'on va faire et demander l'accord\n• Laver du plus propre vers le plus sale (visage en premier, parties intimes en dernier)\n• Bien sécher, surtout les plis (aisselles, aine, sous les seins) pour éviter les mycoses\n\n⚠️ On ne laisse jamais un patient fragile nu et exposé : on découvre seulement la zone qu'on lave. On vérifie aussi la température de l'eau pour ne pas brûler la peau.\n\nFavoriser l'**autonomie** : si le patient peut se laver le visage seul, on l'encourage à le faire.",
                    'questions' => [
                        ['question' => 'Dans quel ordre lave-t-on le corps lors de la toilette ?', 'options' => ['Du plus sale vers le plus propre', 'Du plus propre vers le plus sale', 'Au hasard', 'Toujours les pieds en premier'], 'correct' => [1], 'explanation' => 'On lave du plus propre (visage) vers le plus sale (parties intimes) pour éviter de transporter les microbes.'],
                        ['question' => 'Pourquoi sécher soigneusement les plis cutanés ?', 'options' => ['Pour gagner du temps', 'Pour éviter mycoses et macérations', 'Pour parfumer le patient', 'Ce n\'est pas nécessaire'], 'correct' => [1], 'explanation' => 'L\'humidité dans les plis favorise les mycoses et l\'irritation de la peau ; il faut bien sécher.'],
                        ['question' => 'Comment préserver la dignité du patient pendant la toilette ?', 'options' => ['Laisser la porte ouverte', 'Découvrir seulement la zone lavée', 'Faire vite sans expliquer', 'Découvrir tout le corps d\'emblée'], 'correct' => [1], 'explanation' => 'On ne découvre que la zone en cours de lavage pour respecter la pudeur et le confort du patient.'],
                    ],
                ],
                [
                    'title' => 'Niveau 4 — Aide à la mobilité et manutention',
                    'subtitle' => 'Bouger le patient en sécurité',
                    'xp_reward' => 130,
                    'content' => "🎯 **Objectif** : aider le patient à se déplacer sans se blesser ni le blesser.\n\n💡 La manutention protège à la fois le patient et ton propre dos. Une mauvaise posture est la première cause de mal de dos chez les soignants.\n\n✅ Principes de manutention :\n• Plier les genoux, garder le dos droit (forcer avec les jambes, pas le dos)\n• Rapprocher la charge de son corps\n• Avoir les pieds bien écartés pour un bon équilibre\n• Prévenir le patient : « Je vais vous aider à vous lever, à trois on se redresse »\n• Utiliser les aides disponibles : barre de lit, déambulateur, fauteuil roulant\n\nTransfert lit-fauteuil :\n• Approcher le fauteuil, freins bloqués\n• Aider le patient à s'asseoir au bord du lit\n• Le laisser quelques secondes assis (risque de vertige)\n• Pivoter et l'installer doucement\n\n⚠️ Demander de l'aide d'un collègue si le patient est lourd ou ne tient pas debout. On ne soulève jamais un patient seul au-delà de ses forces.",
                    'questions' => [
                        ['question' => 'Quelle posture protège le dos du soignant lors d\'un transfert ?', 'options' => ['Dos courbé, jambes tendues', 'Genoux pliés et dos droit', 'Se pencher en avant rapidement', 'Bras tendus loin du corps'], 'correct' => [1], 'explanation' => 'Plier les genoux et garder le dos droit permet de forcer avec les jambes et protège la colonne.'],
                        ['question' => 'Pourquoi laisser le patient quelques secondes assis avant de le lever ?', 'options' => ['Pour le faire patienter', 'Pour éviter le vertige (hypotension)', 'Pour gagner du temps', 'Ce n\'est jamais utile'], 'correct' => [1], 'explanation' => 'Se redresser trop vite peut provoquer un vertige par chute de tension ; on laisse le corps s\'adapter.'],
                        ['question' => 'Avant un transfert lit-fauteuil, quelles précautions prendre ? (plusieurs réponses)', 'options' => ['Bloquer les freins du fauteuil', 'Prévenir le patient du geste', 'Soulever seul un patient très lourd', 'Demander de l\'aide si besoin'], 'correct' => [0, 1, 3], 'explanation' => 'On bloque les freins, on prévient le patient et on appelle un collègue plutôt que de soulever seul une charge trop lourde.'],
                    ],
                ],
                [
                    'title' => 'Niveau 5 — Prévention des escarres',
                    'subtitle' => 'Protéger la peau des patients alités',
                    'xp_reward' => 140,
                    'content' => "🎯 **Objectif** : comprendre et prévenir les escarres.\n\n💡 Une **escarre** est une plaie due à une pression prolongée sur la peau, qui coupe la circulation du sang. Elle touche les patients qui restent longtemps couchés ou assis sans bouger.\n\nZones à risque (points d'appui) :\n• Talons\n• Sacrum (bas du dos)\n• Hanches\n• Coudes et omoplates\n\n✅ Prévention :\n• Changer la position du patient toutes les **2 à 3 heures**\n• Garder la peau propre et sèche\n• Surveiller l'apparition de rougeurs qui ne blanchissent pas à la pression\n• Veiller à une bonne alimentation et hydratation\n• Éviter les plis dans les draps et les miettes dans le lit\n\n| Stade | Signe |\n|-------|-------|\n| 1 | Rougeur persistante |\n| 2 | Cloque ou plaie superficielle |\n| 3-4 | Plaie profonde |\n\n⚠️ Une rougeur qui ne disparaît pas est le **premier signal d'alerte**. Il faut la signaler immédiatement à l'infirmier.",
                    'questions' => [
                        ['question' => 'Qu\'est-ce qui provoque une escarre ?', 'options' => ['Une infection virale', 'Une pression prolongée sur la peau', 'Une allergie alimentaire', 'Un excès de mouvement'], 'correct' => [1], 'explanation' => 'L\'escarre résulte d\'une pression prolongée qui coupe la circulation sanguine sur un point d\'appui.'],
                        ['question' => 'À quelle fréquence change-t-on idéalement la position d\'un patient alité à risque ?', 'options' => ['Une fois par jour', 'Toutes les 2 à 3 heures', 'Toutes les 12 heures', 'Jamais'], 'correct' => [1], 'explanation' => 'Changer de position toutes les 2 à 3 heures soulage les points d\'appui et prévient les escarres.'],
                        ['question' => 'Quelles zones sont particulièrement à risque d\'escarre ? (plusieurs réponses)', 'options' => ['Les talons', 'Le sacrum', 'Le bout du nez', 'Les hanches'], 'correct' => [0, 1, 3], 'explanation' => 'Talons, sacrum et hanches sont des points d\'appui classiques où la pression est forte chez le patient couché.'],
                    ],
                ],
                [
                    'title' => 'Niveau 6 — Prise des constantes simples',
                    'subtitle' => 'Température, pouls et respiration',
                    'xp_reward' => 150,
                    'content' => "🎯 **Objectif** : mesurer et noter correctement les constantes vitales de base.\n\n💡 Les constantes renseignent sur l'état du patient. L'AS les prend et les **note**, puis transmet à l'infirmier.\n\n✅ Valeurs normales chez l'adulte :\n• Température : 36,1 à 37,2 °C\n• Pouls : 60 à 100 battements par minute\n• Fréquence respiratoire : 12 à 20 respirations par minute\n\nComment faire :\n• **Température** : thermomètre frontal, axillaire ou tympanique selon le matériel\n• **Pouls** : poser deux doigts sur le poignet (radial), compter sur 1 minute\n• **Respiration** : observer la poitrine se soulever, discrètement\n\nExemple de notation : « T° 38,5 °C — Pouls 96/min — à 8h ».\n\n⚠️ Une température supérieure à 38 °C indique une **fièvre** à signaler. Un pouls très rapide (>100) ou très lent (<60) doit être transmis. On note toujours l'heure de la mesure.",
                    'questions' => [
                        ['question' => 'Quelle est la fréquence cardiaque normale au repos chez l\'adulte ?', 'options' => ['20 à 40 / min', '60 à 100 / min', '120 à 160 / min', '200 / min'], 'correct' => [1], 'explanation' => 'Le pouls normal d\'un adulte au repos se situe entre 60 et 100 battements par minute.'],
                        ['question' => 'À partir de quelle température parle-t-on de fièvre à signaler ?', 'options' => ['36 °C', '37 °C', 'Au-dessus de 38 °C', '34 °C'], 'correct' => [2], 'explanation' => 'Une température dépassant 38 °C correspond à une fièvre qu\'il faut transmettre à l\'infirmier.'],
                        ['question' => 'Que doit toujours accompagner la notation d\'une constante ?', 'options' => ['Le prix du matériel', 'L\'heure de la mesure', 'Le nom du médecin', 'La marque du thermomètre'], 'correct' => [1], 'explanation' => 'On note toujours l\'heure pour que l\'équipe puisse suivre l\'évolution dans le temps.'],
                    ],
                ],
                [
                    'title' => 'Niveau 7 — Observation et alerte',
                    'subtitle' => 'Repérer ce qui ne va pas et le signaler',
                    'xp_reward' => 160,
                    'content' => "🎯 **Objectif** : savoir observer le patient et donner l'alerte au bon moment.\n\n💡 L'AS passe beaucoup de temps auprès du patient : il est souvent le premier à remarquer un changement. L'observation est un véritable soin.\n\n✅ Ce qu'on observe :\n• L'état de conscience (le patient répond-il, est-il confus ?)\n• La couleur de la peau (pâleur, lèvres bleues = cyanose)\n• La respiration (rapide, difficile, sifflante)\n• La douleur (grimaces, plaintes, position repliée)\n• L'appétit, le sommeil, l'humeur\n\nSignes d'alerte URGENTE à transmettre immédiatement :\n• Difficulté à respirer\n• Perte de connaissance\n• Douleur intense soudaine\n• Saignement important\n• Lèvres ou extrémités bleues\n\n⚠️ En cas de doute, on **transmet toujours** : mieux vaut signaler pour rien que de passer à côté d'une urgence. On décrit ce qu'on a vu de façon factuelle : « Mme X respire vite et se tient la poitrine » plutôt qu'une interprétation.",
                    'questions' => [
                        ['question' => 'Que signifie des lèvres bleues (cyanose) ?', 'options' => ['Le patient a froid uniquement', 'Un manque d\'oxygène à signaler vite', 'Une bonne santé', 'Une coloration normale'], 'correct' => [1], 'explanation' => 'La cyanose traduit un manque d\'oxygène et constitue un signe d\'alerte à transmettre rapidement.'],
                        ['question' => 'Comment doit-on décrire une observation à l\'infirmier ?', 'options' => ['De façon factuelle et précise', 'Avec un diagnostic médical', 'En minimisant toujours', 'En attendant le lendemain'], 'correct' => [0], 'explanation' => 'On rapporte des faits observés, sans poser de diagnostic, pour que l\'infirmier décide de la suite.'],
                        ['question' => 'Quels signes nécessitent une alerte urgente ? (plusieurs réponses)', 'options' => ['Difficulté à respirer', 'Perte de connaissance', 'Le patient demande un verre d\'eau', 'Saignement important'], 'correct' => [0, 1, 3], 'explanation' => 'Détresse respiratoire, perte de connaissance et hémorragie sont des urgences ; une demande d\'eau ne l\'est pas.'],
                    ],
                ],
                [
                    'title' => 'Niveau 8 — Alimentation et hydratation',
                    'subtitle' => 'Aider à manger et à boire en sécurité',
                    'xp_reward' => 170,
                    'content' => "🎯 **Objectif** : accompagner les repas et prévenir la déshydratation.\n\n💡 Bien manger et boire favorise la guérison, l'énergie et la prévention des escarres. L'AS aide les patients qui ne peuvent pas s'alimenter seuls.\n\n✅ Aide au repas :\n• Installer le patient **assis** ou bien redressé (jamais couché à plat)\n• Vérifier la température du plat\n• Donner de petites bouchées, sans presser\n• Respecter le rythme et les goûts de la personne\n• Proposer régulièrement à boire\n\n⚠️ Risque de **fausse route** : si la nourriture passe dans les voies respiratoires, le patient peut s'étouffer. Signes : toux, étouffement, voix qui change. On arrête immédiatement et on alerte.\n\nHydratation, surtout en climat chaud (Douala, Yaoundé) :\n• Proposer de l'eau souvent, par petites quantités\n• Surveiller les signes de déshydratation : bouche sèche, urines foncées, fatigue, peau qui reste plissée\n\nOn note ce que le patient a réellement mangé et bu pour repérer les pertes d'appétit.",
                    'questions' => [
                        ['question' => 'Dans quelle position doit-on installer un patient pour manger ?', 'options' => ['Couché à plat', 'Assis ou bien redressé', 'Sur le ventre', 'Debout'], 'correct' => [1], 'explanation' => 'La position assise réduit le risque de fausse route en facilitant la déglutition.'],
                        ['question' => 'Qu\'est-ce qu\'une fausse route ?', 'options' => ['Un mauvais chemin à l\'hôpital', 'Le passage d\'aliments dans les voies respiratoires', 'Un repas trop salé', 'Une erreur de médicament'], 'correct' => [1], 'explanation' => 'La fausse route survient quand des aliments ou liquides passent dans les voies respiratoires au lieu de l\'œsophage.'],
                        ['question' => 'Quels signes peuvent indiquer une déshydratation ? (plusieurs réponses)', 'options' => ['Bouche sèche', 'Urines foncées', 'Peau qui reste plissée', 'Appétit augmenté'], 'correct' => [0, 1, 2], 'explanation' => 'Bouche sèche, urines foncées et pli cutané persistant sont des signes classiques de déshydratation.'],
                    ],
                ],
                [
                    'title' => 'Niveau 9 — Bientraitance et communication',
                    'subtitle' => 'Respecter et accompagner la personne',
                    'xp_reward' => 185,
                    'content' => "🎯 **Objectif** : adopter une attitude bientraitante avec chaque patient.\n\n💡 La **bientraitance** est une façon d'être qui place le respect, la dignité et le bien-être du patient au centre. Elle s'oppose à la maltraitance, qui peut être active (brusquer, crier) ou passive (négliger, faire attendre).\n\n✅ Gestes de bientraitance :\n• Saluer le patient, l'appeler par son nom\n• Frapper avant d'entrer dans la chambre\n• Expliquer chaque soin avant de le faire\n• Respecter les croyances, la culture et la langue de la personne\n• Préserver l'intimité et les choix du patient\n• Être patient avec les personnes âgées ou confuses\n\n⚠️ La **confidentialité** (secret professionnel) est une obligation : on ne raconte pas l'état d'un patient à l'extérieur ni à la famille sans autorisation.\n\nCommuniquer avec un patient confus ou anxieux :\n• Parler calmement, avec des phrases courtes\n• Se mettre à sa hauteur, regarder dans les yeux\n• Rassurer par le toucher et la voix\n\nLa bientraitance ne coûte rien mais change tout pour le patient.",
                    'questions' => [
                        ['question' => 'Qu\'est-ce que la bientraitance ?', 'options' => ['Faire les soins le plus vite possible', 'Une attitude de respect et de dignité envers le patient', 'Donner plus de médicaments', 'Laisser le patient seul'], 'correct' => [1], 'explanation' => 'La bientraitance est une attitude globale centrée sur le respect, la dignité et le bien-être du patient.'],
                        ['question' => 'Le secret professionnel impose de :', 'options' => ['Tout raconter à la famille', 'Ne pas divulguer l\'état du patient sans autorisation', 'Publier les dossiers', 'Discuter des patients dans la rue'], 'correct' => [1], 'explanation' => 'La confidentialité interdit de divulguer les informations sur le patient sans autorisation.'],
                        ['question' => 'Quels comportements relèvent de la bientraitance ? (plusieurs réponses)', 'options' => ['Frapper avant d\'entrer', 'Expliquer le soin avant de le faire', 'Brusquer le patient pressé', 'Appeler le patient par son nom'], 'correct' => [0, 1, 3], 'explanation' => 'Frapper, expliquer et nommer la personne sont bientraitants ; brusquer est de la maltraitance.'],
                    ],
                ],
                [
                    'title' => 'Niveau 10 — Travail en équipe et transmissions',
                    'subtitle' => 'Synthèse et professionnalisme',
                    'xp_reward' => 200,
                    'content' => "🎯 **Objectif** : s'intégrer dans l'équipe de soins et transmettre efficacement.\n\n💡 L'aide-soignant ne travaille jamais isolé : il fait partie d'une équipe pluridisciplinaire (infirmiers, médecins, kinés, agents). La qualité des soins dépend de la **transmission** des informations.\n\n✅ Bonnes transmissions :\n• Orales lors des relèves d'équipe (changement de garde)\n• Écrites dans le dossier ou le cahier de soins\n• Claires, factuelles, datées et signées\n• Exemple : « 14h — M. Nkolo a refusé son déjeuner, dit avoir mal au ventre. Signalé à l\'infirmière. »\n\nTravailler en équipe c'est aussi :\n• Respecter le rôle de chacun\n• Demander de l'aide sans honte\n• Signaler ses limites et ses erreurs\n• Participer aux relèves et aux réunions\n\n⚠️ Une information non transmise est une information perdue : un patient peut en souffrir.\n\n🏆 **Félicitations !** Tu maîtrises désormais les fondamentaux du métier d'aide-soignant : hygiène, mobilité, prévention, surveillance et bientraitance. Tu peux viser un poste d'aide-soignant en hôpital, clinique, maison de retraite ou soins à domicile. Avec de l'expérience et la formation, tu pourras évoluer vers infirmier, aide-soignant référent ou spécialisations (gériatrie, bloc opératoire). Prends soin des autres, et reste fier de ce beau métier !",
                    'questions' => [
                        ['question' => 'Pourquoi les transmissions sont-elles essentielles ?', 'options' => ['Pour remplir du papier', 'Pour assurer la continuité et la sécurité des soins', 'Pour évaluer le médecin', 'Elles ne servent à rien'], 'correct' => [1], 'explanation' => 'Les transmissions garantissent que l\'équipe partage les mêmes informations et assure la continuité des soins.'],
                        ['question' => 'Comment doit être une bonne transmission écrite ?', 'options' => ['Floue et sans date', 'Claire, factuelle, datée et signée', 'Anonyme', 'Effacée chaque jour'], 'correct' => [1], 'explanation' => 'Une transmission utile est claire, factuelle, datée et signée pour être exploitable et traçable.'],
                        ['question' => 'Que faut-il faire si l\'on rencontre une difficulté ou commet une erreur ?', 'options' => ['La cacher à l\'équipe', 'La signaler et demander de l\'aide', 'Quitter le service', 'Accuser un collègue'], 'correct' => [1], 'explanation' => 'Signaler ses limites ou erreurs et demander de l\'aide protège le patient et renforce l\'équipe.'],
                    ],
                ],
            ],
        ]);

        $this->command->info('  ✓ Roadmap Aide-Soignant créée (10 niveaux).');
    }
}
