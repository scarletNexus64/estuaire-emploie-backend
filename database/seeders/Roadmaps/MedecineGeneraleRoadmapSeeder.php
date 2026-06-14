<?php

namespace Database\Seeders\Roadmaps;

use Illuminate\Database\Seeder;

/**
 * Roadmap Médecine Générale — fondamentaux de la pratique du médecin généraliste.
 */
class MedecineGeneraleRoadmapSeeder extends Seeder
{
    use RoadmapSeederHelper;

    public function run(): void
    {
        $this->createRoadmap([
            'title' => 'Deviens Médecin Généraliste (fondamentaux)',
            'slug' => 'medecin-generaliste',
            'domain' => 'sante',
            'description' => "Parcours pédagogique couvrant les fondamentaux de la médecine générale : rôle du généraliste, démarche diagnostique, signes vitaux, prescription raisonnée, prévention, pathologies courantes, urgences, relation médecin-patient, déontologie et coordination des soins. Conçu pour un public débutant à intermédiaire dans le contexte africain francophone.",
            'objectives' => "Comprendre le rôle central du médecin généraliste dans le système de soins\nMaîtriser la démarche diagnostique : interrogatoire et examen clinique\nMesurer et interpréter les signes vitaux\nPrescrire de façon raisonnée et sûre\nMettre en œuvre la prévention et le dépistage\nReconnaître les urgences vitales et coordonner les soins",
            'icon' => '🩺',
            'color' => '#0EA5E9',
            'difficulty' => 'advanced',
            'required_packs' => [],
            'pass_threshold' => 70,
            'order' => 20,
            'levels' => [
                [
                    'title' => 'Niveau 1 — Le rôle du médecin généraliste',
                    'subtitle' => 'Premier recours et suivi global du patient',
                    'xp_reward' => 100,
                    'content' => "🎯 **Objectif** : comprendre la place du généraliste, médecin de premier recours.\n\n💡 Le médecin généraliste est souvent le **premier contact** du patient avec le système de santé. À Douala comme à Yaoundé, c'est lui qui accueille des plaintes variées avant tout avis spécialisé.\n\nSes missions principales :\n• **Premier recours** : recevoir toute demande, trier, orienter\n• **Suivi global** : connaître le patient dans la durée et sa famille\n• **Prévention** : vaccination, dépistage, éducation à la santé\n• **Coordination** : faire le lien avec spécialistes, hôpital, laboratoire\n• **Synthèse** : centraliser les informations médicales du patient\n\n✅ Le généraliste pratique une **approche globale** (bio-psycho-sociale) : il ne traite pas seulement une maladie, mais une personne dans son contexte de vie, de travail et de famille.\n\n⚠️ Son rôle n'est pas de tout traiter seul : savoir **adresser au bon moment** est une compétence clé. Un bon généraliste connaît les limites de son champ d'action.",
                    'questions' => [
                        ['question' => 'Quelle est la position du médecin généraliste dans le parcours de soins ?', 'options' => ['Spécialiste de dernier recours', 'Médecin de premier recours', 'Médecin uniquement hospitalier', 'Pharmacien prescripteur'], 'correct' => [1], 'explanation' => 'Le généraliste est le médecin de premier recours, premier contact du patient avec le système de santé.'],
                        ['question' => 'Que signifie l\'approche globale du généraliste ?', 'options' => ['Traiter seulement l\'organe malade', 'Prendre en compte les dimensions bio-psycho-sociales', 'Prescrire le maximum d\'examens', 'Voir le patient une seule fois'], 'correct' => [1], 'explanation' => 'L\'approche globale considère la personne dans ses dimensions biologiques, psychologiques et sociales.'],
                        ['question' => 'Parmi ces missions, lesquelles relèvent du généraliste ? (plusieurs réponses)', 'options' => ['La prévention et le dépistage', 'La coordination des soins', 'La chirurgie cardiaque', 'Le suivi global dans la durée'], 'correct' => [0, 1, 3], 'explanation' => 'Prévention, coordination et suivi global sont des missions du généraliste ; la chirurgie cardiaque relève du spécialiste.'],
                    ],
                ],
                [
                    'title' => 'Niveau 2 — L\'interrogatoire médical',
                    'subtitle' => 'Recueillir l\'histoire de la maladie',
                    'xp_reward' => 110,
                    'content' => "🎯 **Objectif** : savoir mener un interrogatoire structuré, base de 80 % des diagnostics.\n\n💡 L'interrogatoire (ou anamnèse) précède l'examen physique. Bien mené, il oriente déjà fortement le diagnostic.\n\nStructure type d'un interrogatoire :\n• **Motif de consultation** : pourquoi le patient vient-il ?\n• **Histoire de la maladie** : début, évolution, facteurs déclenchants\n• **Antécédents** : personnels (diabète, HTA) et familiaux\n• **Traitements en cours** et allergies\n• **Mode de vie** : tabac, alcool, profession, habitat\n\nPour caractériser une douleur, on utilise le schéma **SOCRATES** :\n• Siège • Origine/début • Caractère • Rayonnement (irradiation)\n• Associations (signes accompagnateurs) • Temps (évolution)\n• Exacerbation/soulagement • Sévérité\n\n✅ Poser des **questions ouvertes** d'abord (« Racontez-moi »), puis des questions fermées pour préciser.\n\n⚠️ Ne jamais négliger les antécédents et allergies : ils conditionnent la sécurité de la prescription.",
                    'questions' => [
                        ['question' => 'Quelle proportion des diagnostics repose principalement sur l\'interrogatoire ?', 'options' => ['Environ 10 %', 'Environ 80 %', 'Environ 30 %', 'Aucun, seul l\'examen compte'], 'correct' => [1], 'explanation' => 'On estime qu\'environ 80 % des diagnostics sont orientés par un bon interrogatoire.'],
                        ['question' => 'À quoi sert le sigle SOCRATES ?', 'options' => ['Mesurer la tension', 'Caractériser une douleur', 'Calculer une posologie', 'Évaluer la fièvre'], 'correct' => [1], 'explanation' => 'SOCRATES est un moyen mnémotechnique pour décrire précisément une douleur.'],
                        ['question' => 'Quel type de question privilégier au début de l\'interrogatoire ?', 'options' => ['Questions fermées (oui/non)', 'Questions ouvertes', 'Aucune question', 'Questions sur le paiement'], 'correct' => [1], 'explanation' => 'Les questions ouvertes laissent le patient s\'exprimer librement avant de préciser avec des questions fermées.'],
                    ],
                ],
                [
                    'title' => 'Niveau 3 — L\'examen clinique',
                    'subtitle' => 'Inspection, palpation, percussion, auscultation',
                    'xp_reward' => 120,
                    'content' => "🎯 **Objectif** : maîtriser les quatre temps de l'examen physique.\n\n💡 L'examen clinique suit toujours un ordre logique, dans un environnement éclairé et respectueux de l'intimité du patient.\n\nLes quatre temps fondamentaux :\n• **Inspection** : observer (couleur, posture, déformation, respiration)\n• **Palpation** : toucher (chaleur, masse, douleur provoquée, pouls)\n• **Percussion** : frapper pour évaluer (matité/tympanisme, ex. abdomen, thorax)\n• **Auscultation** : écouter au stéthoscope (cœur, poumons, abdomen)\n\n⚠️ Pour l'**abdomen**, l'ordre change : inspection, **auscultation**, puis palpation et percussion. On ausculte avant de toucher pour ne pas modifier les bruits intestinaux.\n\n✅ Toujours **se laver les mains** avant et après, expliquer ce que l'on fait, et examiner le côté sain avant le côté douloureux.\n\nExemple : un patient à Yaoundé tousse depuis 5 jours. L'auscultation pulmonaire révèle des crépitants à la base droite, orientant vers une pneumopathie.",
                    'questions' => [
                        ['question' => 'Quels sont les quatre temps classiques de l\'examen clinique ?', 'options' => ['Inspection, palpation, percussion, auscultation', 'Radiographie, scanner, IRM, échographie', 'Tension, pouls, température, poids', 'Interrogatoire, ordonnance, repos, contrôle'], 'correct' => [0], 'explanation' => 'Inspection, palpation, percussion et auscultation forment la séquence classique de l\'examen physique.'],
                        ['question' => 'Pour l\'examen de l\'abdomen, quelle particularité d\'ordre faut-il respecter ?', 'options' => ['Commencer par la percussion', 'Ausculter avant de palper', 'Ne pas inspecter', 'Palper d\'abord fortement'], 'correct' => [1], 'explanation' => 'On ausculte l\'abdomen avant la palpation pour ne pas perturber les bruits intestinaux.'],
                        ['question' => 'Quelles bonnes pratiques accompagnent l\'examen clinique ? (plusieurs réponses)', 'options' => ['Se laver les mains avant et après', 'Examiner le côté douloureux en dernier', 'Bousculer le patient pour aller vite', 'Expliquer les gestes au patient'], 'correct' => [0, 1, 3], 'explanation' => 'Hygiène des mains, examen du côté douloureux en dernier et explication des gestes sont des bonnes pratiques essentielles.'],
                    ],
                ],
                [
                    'title' => 'Niveau 4 — Les signes vitaux',
                    'subtitle' => 'Mesurer et interpréter les constantes',
                    'xp_reward' => 130,
                    'content' => "🎯 **Objectif** : mesurer et interpréter les paramètres vitaux de l'adulte.\n\n💡 Les signes vitaux reflètent l'état des grandes fonctions. Voici les valeurs normales de référence chez l'adulte au repos :\n\n| Paramètre | Valeur normale |\n|---|---|\n| Fréquence cardiaque | 60–100 batt/min |\n| Fréquence respiratoire | 12–20 cycles/min |\n| Température | 36,1–37,5 °C |\n| Pression artérielle | ~120/80 mmHg |\n| Saturation O2 (SpO2) | ≥ 95 % |\n\n⚠️ Signaux d'alerte :\n• **Tachycardie** : FC > 100/min • **Bradycardie** : FC < 60/min\n• **Fièvre** : T° ≥ 38 °C • **Hypothermie** : T° < 35 °C\n• **HTA** : PA ≥ 140/90 mmHg de façon répétée\n• **Hypotension** : PA systolique < 90 mmHg\n• **Désaturation** : SpO2 < 95 %\n\n✅ Toujours mesurer la PA au repos, brassard adapté au bras. Un seul chiffre élevé ne suffit pas à diagnostiquer une HTA : il faut des mesures répétées.\n\nExemple : un patient diabétique à Douala présente FC 110, T° 38,5 °C et SpO2 92 % : association inquiétante à explorer en urgence.",
                    'questions' => [
                        ['question' => 'Quelle est la fréquence cardiaque normale au repos chez l\'adulte ?', 'options' => ['30–50 batt/min', '60–100 batt/min', '110–140 batt/min', '150–180 batt/min'], 'correct' => [1], 'explanation' => 'La fréquence cardiaque normale de repos chez l\'adulte se situe entre 60 et 100 battements par minute.'],
                        ['question' => 'À partir de quelle température parle-t-on de fièvre ?', 'options' => ['T° ≥ 38 °C', 'T° ≥ 36 °C', 'T° ≥ 40 °C', 'T° ≥ 37 °C'], 'correct' => [0], 'explanation' => 'La fièvre est définie classiquement par une température corporelle supérieure ou égale à 38 °C.'],
                        ['question' => 'Une SpO2 à 90 % chez l\'adulte signifie :', 'options' => ['Une saturation normale', 'Une désaturation à explorer', 'Une bradycardie', 'Une hypertension'], 'correct' => [1], 'explanation' => 'Une SpO2 inférieure à 95 % traduit une désaturation anormale nécessitant une exploration.'],
                    ],
                ],
                [
                    'title' => 'Niveau 5 — La prescription raisonnée',
                    'subtitle' => 'Prescrire utile, sûr et adapté',
                    'xp_reward' => 140,
                    'content' => "🎯 **Objectif** : prescrire de façon rationnelle, sûre et économiquement adaptée.\n\n💡 La prescription raisonnée vise le bon médicament, à la bonne dose, pour le bon patient, le bon temps, au moindre coût et risque.\n\nUne ordonnance complète comporte :\n• Identité du patient (nom, âge, poids si besoin)\n• **DCI** (dénomination commune internationale) du médicament\n• Dosage, forme, posologie, voie, durée\n• Date et signature du prescripteur\n\nPrincipes de sécurité :\n• Vérifier **allergies** et **interactions médicamenteuses**\n• Adapter aux **insuffisances** rénale/hépatique\n• Prudence chez la **femme enceinte**, l'enfant, la personne âgée\n• Éviter les **antibiotiques inutiles** (résistance bactérienne)\n\n⚠️ En contexte africain, tenir compte du **coût** et de la **disponibilité** des médicaments : privilégier les génériques en DCI accessibles.\n\n✅ Exemple : pour une rhinopharyngite virale, pas d'antibiotique. On prescrit du paracétamol (antalgique/antipyrétique) et des conseils d'hydratation : prescription raisonnée et économique.",
                    'questions' => [
                        ['question' => 'Que signifie prescrire en DCI ?', 'options' => ['Prescrire la marque commerciale', 'Prescrire en dénomination commune internationale', 'Prescrire à dose maximale', 'Prescrire sans posologie'], 'correct' => [1], 'explanation' => 'La DCI désigne la molécule par son nom international, ce qui favorise les génériques et la sécurité.'],
                        ['question' => 'Devant une rhinopharyngite virale simple, quelle est la bonne attitude ?', 'options' => ['Prescrire un antibiotique systématiquement', 'Traiter les symptômes sans antibiotique', 'Hospitaliser le patient', 'Prescrire trois antibiotiques'], 'correct' => [1], 'explanation' => 'Une infection virale ne justifie pas d\'antibiotique ; on traite les symptômes pour limiter les résistances.'],
                        ['question' => 'Quels éléments doivent figurer sur une ordonnance sûre ? (plusieurs réponses)', 'options' => ['La posologie et la durée', 'La vérification des allergies', 'Un médicament sans nom', 'La signature du prescripteur'], 'correct' => [0, 1, 3], 'explanation' => 'Posologie, durée, vérification des allergies et signature sont indispensables à une prescription sûre.'],
                    ],
                ],
                [
                    'title' => 'Niveau 6 — Prévention et dépistage',
                    'subtitle' => 'Agir avant la maladie',
                    'xp_reward' => 150,
                    'content' => "🎯 **Objectif** : intégrer la prévention au cœur de la consultation.\n\n💡 La médecine ne se limite pas à soigner : prévenir et dépister sauvent des vies à moindre coût.\n\nLes trois niveaux de prévention :\n• **Primaire** : empêcher l'apparition (vaccination, moustiquaire contre le paludisme, sevrage tabagique)\n• **Secondaire** : dépister tôt une maladie silencieuse (mesure de la PA, glycémie, frottis du col)\n• **Tertiaire** : limiter les complications d'une maladie installée (éducation du diabétique, rééducation)\n\nExemples concrets en Afrique francophone :\n• Vaccination du nourrisson selon le calendrier élargi (PEV)\n• Dépistage du **VIH**, de l'**hépatite B**, du diabète et de l'HTA\n• Prévention du **paludisme** : moustiquaires imprégnées, traitement préventif chez la femme enceinte\n• Promotion de l'allaitement et de la nutrition\n\n✅ Chaque consultation est une occasion de prévention : vérifier le statut vaccinal, dépister l'HTA, conseiller sur l'hygiène de vie.\n\n⚠️ Un dépistage n'a de sens que s'il existe une prise en charge derrière. Dépister sans pouvoir traiter peut générer de l'angoisse inutile.",
                    'questions' => [
                        ['question' => 'La vaccination relève de quel niveau de prévention ?', 'options' => ['Prévention primaire', 'Prévention secondaire', 'Prévention tertiaire', 'Aucune prévention'], 'correct' => [0], 'explanation' => 'La vaccination empêche l\'apparition de la maladie : c\'est de la prévention primaire.'],
                        ['question' => 'Le dépistage précoce d\'une maladie silencieuse correspond à :', 'options' => ['La prévention primaire', 'La prévention secondaire', 'La prévention tertiaire', 'Un traitement curatif'], 'correct' => [1], 'explanation' => 'Détecter tôt une maladie déjà présente mais silencieuse relève de la prévention secondaire.'],
                        ['question' => 'Quelles actions sont des mesures de prévention pertinentes en Afrique francophone ? (plusieurs réponses)', 'options' => ['Distribution de moustiquaires imprégnées', 'Dépistage du VIH et de l\'hépatite B', 'Arrêt de toute vaccination', 'Suivi vaccinal du nourrisson'], 'correct' => [0, 1, 3], 'explanation' => 'Moustiquaires, dépistage des infections et vaccination du nourrisson sont des mesures de prévention essentielles.'],
                    ],
                ],
                [
                    'title' => 'Niveau 7 — Les pathologies courantes',
                    'subtitle' => 'Reconnaître et prendre en charge le quotidien',
                    'xp_reward' => 160,
                    'content' => "🎯 **Objectif** : identifier les motifs fréquents de consultation en soins primaires.\n\n💡 Le généraliste gère au quotidien un panel de pathologies courantes. En zone tropicale, certaines sont prioritaires.\n\nPathologies fréquentes :\n• **Paludisme** : fièvre, frissons, céphalées → confirmer par TDR ou goutte épaisse avant de traiter\n• **Infections respiratoires** : rhinopharyngite (virale), bronchite, pneumonie\n• **Diarrhées et gastro-entérites** : risque de **déshydratation**, surtout chez l'enfant → réhydratation orale (SRO)\n• **Hypertension artérielle** et **diabète** : maladies chroniques en forte progression\n• **Infections urinaires**, dermatoses, parasitoses intestinales\n\n⚠️ Devant une fièvre en zone d'endémie palustre, **penser systématiquement au paludisme**, mais ne pas oublier les autres causes (typhoïde, infections diverses). Confirmer biologiquement quand c'est possible.\n\n✅ Exemple : un enfant de 3 ans à Douala présente diarrhée et yeux creux. La priorité est d'évaluer la déshydratation et de débuter une réhydratation par SRO, avant tout autre traitement.",
                    'questions' => [
                        ['question' => 'Devant une fièvre en zone d\'endémie, que faut-il idéalement faire avant de traiter le paludisme ?', 'options' => ['Confirmer par un test (TDR ou goutte épaisse)', 'Donner un antibiotique au hasard', 'Ne rien faire', 'Hospitaliser systématiquement'], 'correct' => [0], 'explanation' => 'On confirme le paludisme par un test diagnostique avant de traiter, pour éviter les traitements injustifiés.'],
                        ['question' => 'Quel est le risque principal d\'une diarrhée aiguë chez l\'enfant ?', 'options' => ['La déshydratation', 'L\'hypertension', 'La fracture', 'La surdité'], 'correct' => [0], 'explanation' => 'La diarrhée aiguë expose surtout à la déshydratation, d\'où l\'importance de la réhydratation orale (SRO).'],
                        ['question' => 'Une rhinopharyngite est le plus souvent d\'origine :', 'options' => ['Bactérienne', 'Virale', 'Parasitaire', 'Fongique'], 'correct' => [1], 'explanation' => 'La rhinopharyngite commune est majoritairement virale et ne nécessite pas d\'antibiotique.'],
                    ],
                ],
                [
                    'title' => 'Niveau 8 — Reconnaître les urgences',
                    'subtitle' => 'Détecter les signes de gravité',
                    'xp_reward' => 170,
                    'content' => "🎯 **Objectif** : repérer rapidement les situations qui menacent le pronostic vital.\n\n💡 Reconnaître l'urgence vitale conditionne la survie. On évalue les fonctions vitales selon l'approche **ABCDE** :\n• **A** (Airway) : voies aériennes libres ?\n• **B** (Breathing) : respiration efficace ?\n• **C** (Circulation) : pouls, tension, saignement ?\n• **D** (Disability) : conscience, déficit neurologique ?\n• **E** (Exposure) : examen complet, température\n\nSignes de gravité à reconnaître :\n• Détresse respiratoire (cyanose, tirage, SpO2 basse)\n• État de **choc** (pâleur, pouls filant, tension effondrée)\n• Troubles de la **conscience** (somnolence, coma)\n• Douleur thoracique constrictive (infarctus)\n• Signes d'**AVC** : déficit moteur brutal, déformation du visage, trouble de la parole\n\n⚠️ Devant un paludisme grave (convulsions, coma, ictère, urines foncées), il faut une prise en charge urgente, ne pas perdre de temps.\n\n✅ Règle d'or : **évaluer les fonctions vitales d'abord**, alerter et organiser le transfert vers une structure adaptée sans retard.",
                    'questions' => [
                        ['question' => 'Que vérifie-t-on en priorité (lettre A) dans l\'approche ABCDE ?', 'options' => ['La liberté des voies aériennes', 'La température', 'Le poids', 'La glycémie'], 'correct' => [0], 'explanation' => 'A (Airway) correspond à la liberté des voies aériennes, première étape de l\'évaluation vitale.'],
                        ['question' => 'Quels signes évoquent un état de choc ?', 'options' => ['Pâleur, pouls filant, tension effondrée', 'Bonne coloration et tension normale', 'Appétit augmenté', 'Sommeil réparateur'], 'correct' => [0], 'explanation' => 'Pâleur, pouls rapide et filant et chute tensionnelle sont des signes d\'un état de choc.'],
                        ['question' => 'Quels signes doivent faire évoquer un AVC ? (plusieurs réponses)', 'options' => ['Déficit moteur brutal d\'un côté', 'Déformation soudaine du visage', 'Trouble brutal de la parole', 'Légère fatigue depuis un mois'], 'correct' => [0, 1, 2], 'explanation' => 'Déficit moteur brutal, asymétrie du visage et trouble de la parole sont les signes d\'alerte d\'un AVC.'],
                    ],
                ],
                [
                    'title' => 'Niveau 9 — Relation médecin-patient et déontologie',
                    'subtitle' => 'Communiquer, consentir, respecter le secret',
                    'xp_reward' => 185,
                    'content' => "🎯 **Objectif** : construire une relation de confiance et respecter l'éthique médicale.\n\n💡 La qualité de la relation influence directement l'adhésion au traitement et les résultats de santé.\n\nPrincipes de communication :\n• **Écoute active** et empathie, sans jugement\n• Information **claire et compréhensible** (éviter le jargon)\n• Vérifier la **compréhension** du patient\n• Décision partagée et respect des choix du patient\n\nPiliers de la déontologie médicale :\n• **Bienfaisance** : agir pour le bien du patient\n• **Non-malfaisance** : « primum non nocere », d'abord ne pas nuire\n• **Autonomie** : recueillir le **consentement éclairé**\n• **Justice** : équité dans l'accès aux soins\n\n⚠️ Le **secret médical** est une obligation fondamentale : ne pas divulguer d'informations sans accord, même à la famille, sauf dérogations légales.\n\n✅ Exemple : annoncer un diagnostic de VIH exige confidentialité, tact, et un accompagnement. Le patient doit comprendre sa maladie et donner son consentement aux soins et aux examens.",
                    'questions' => [
                        ['question' => 'Que signifie le consentement éclairé ?', 'options' => ['Le médecin décide seul', 'Le patient accepte après avoir été informé', 'La famille décide à la place du patient', 'Aucune information n\'est donnée'], 'correct' => [1], 'explanation' => 'Le consentement éclairé suppose que le patient accepte les soins après une information claire et complète.'],
                        ['question' => 'Le principe « primum non nocere » signifie :', 'options' => ['D\'abord ne pas nuire', 'Soigner uniquement les riches', 'Prescrire beaucoup', 'Ignorer le patient'], 'correct' => [0], 'explanation' => '« Primum non nocere » signifie « d\'abord ne pas nuire », principe de non-malfaisance.'],
                        ['question' => 'Concernant le secret médical, quelle affirmation est correcte ?', 'options' => ['On peut tout dire à la famille librement', 'Il s\'agit d\'une obligation fondamentale du médecin', 'Il ne s\'applique pas aux maladies graves', 'Il n\'existe pas en médecine générale'], 'correct' => [1], 'explanation' => 'Le secret médical est une obligation déontologique fondamentale, protégeant les informations du patient.'],
                    ],
                ],
                [
                    'title' => 'Niveau 10 — Coordination des soins',
                    'subtitle' => 'Orienter, référer et assurer le suivi',
                    'xp_reward' => 200,
                    'content' => "🎯 **Objectif** : organiser le parcours du patient entre les acteurs de santé.\n\n💡 Le généraliste est le **chef d'orchestre** du parcours de soins. Il sait quand traiter, quand référer et comment assurer la continuité.\n\nLeviers de la coordination :\n• **Référer** vers un spécialiste avec un courrier clair (motif, antécédents, examens déjà faits)\n• **Contre-référence** : le spécialiste renvoie ses conclusions au généraliste\n• Lien avec le **laboratoire**, la pharmacie, l'hôpital de district\n• Suivi des maladies chroniques (HTA, diabète, VIH) avec rendez-vous programmés\n• Dossier médical tenu à jour pour la continuité des soins\n\n⚠️ Un transfert mal préparé (sans information transmise) fait perdre du temps et nuit au patient. La qualité de la lettre de liaison est essentielle.\n\n✅ Exemple : un patient hypertendu de Yaoundé mal équilibré est adressé au cardiologue avec un courrier détaillé ; les conclusions reviennent au généraliste qui poursuit le suivi de proximité.\n\n🏆 **Félicitations !** Tu maîtrises les fondamentaux de la médecine générale : rôle, diagnostic, signes vitaux, prescription, prévention, urgences, déontologie et coordination. Ces bases ouvrent vers des **débouchés variés** : médecine de famille, santé publique, médecine du travail, urgences, ou poursuite vers une spécialisation. Le généraliste reste un pilier irremplaçable des systèmes de santé africains. Continue d'apprendre : la médecine évolue sans cesse !",
                    'questions' => [
                        ['question' => 'Qu\'est-ce qu\'une contre-référence ?', 'options' => ['Le spécialiste renvoie ses conclusions au généraliste', 'Le patient change de pays', 'Le refus de soigner', 'Une ordonnance annulée'], 'correct' => [0], 'explanation' => 'La contre-référence est le retour d\'information du spécialiste vers le médecin généraliste référent.'],
                        ['question' => 'Que doit contenir une bonne lettre de référence à un spécialiste ?', 'options' => ['Rien, juste le nom du patient', 'Le motif, les antécédents et examens déjà réalisés', 'Uniquement la date', 'Le tarif de la consultation'], 'correct' => [1], 'explanation' => 'Une lettre de liaison utile précise le motif, les antécédents et les examens déjà effectués.'],
                        ['question' => 'Quels éléments assurent une bonne coordination des soins ? (plusieurs réponses)', 'options' => ['Un dossier médical tenu à jour', 'Le suivi programmé des maladies chroniques', 'L\'absence de communication entre acteurs', 'La référence avec courrier clair'], 'correct' => [0, 1, 3], 'explanation' => 'Dossier à jour, suivi programmé et référence documentée garantissent une coordination efficace des soins.'],
                    ],
                ],
            ],
        ]);

        $this->command->info('  ✓ Roadmap Médecine Générale créée (10 niveaux).');
    }
}
