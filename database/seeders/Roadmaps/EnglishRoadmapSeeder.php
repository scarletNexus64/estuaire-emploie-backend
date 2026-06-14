<?php

namespace Database\Seeders\Roadmaps;

use Illuminate\Database\Seeder;

/**
 * Roadmap Anglais — du débutant (A1) à l'intermédiaire (B1).
 * Parcours progressif type jeu : tips rédigés en français, QCM de validation par niveau.
 */
class EnglishRoadmapSeeder extends Seeder
{
    use RoadmapSeederHelper;

    public function run(): void
    {
        $this->createRoadmap([
            'title' => 'Maîtrise l\'Anglais Professionnel',
            'slug' => 'apprendre-langlais',
            'domain' => 'langues',
            'description' => "Apprends l'anglais comme dans un jeu, du niveau débutant (A1) à l'intermédiaire (B1). Salutations, grammaire, temps verbaux, vocabulaire du quotidien et de l'entreprise, et expressions courantes pour converser avec assurance.",
            'objectives' => "Maîtriser les salutations et l'alphabet\nUtiliser les pronoms et le verbe to be\nConjuguer aux temps essentiels (présent, passé, futur)\nConstruire des phrases et poser des questions\nÉlargir son vocabulaire quotidien et professionnel\nTenir une conversation courante avec politesse",
            'icon' => '🇬🇧',
            'color' => '#3B82F6',
            'difficulty' => 'beginner',
            'required_packs' => [],
            'pass_threshold' => 70,
            'order' => 1,
            'levels' => [
                [
                    'title' => 'Niveau 1 — L\'alphabet & les salutations',
                    'subtitle' => 'Dire bonjour, merci, au revoir',
                    'xp_reward' => 100,
                    'content' => "🎯 **Objectif** : faire ses premiers pas en anglais avec les salutations et l'alphabet.\n\n💡 L'alphabet anglais a les mêmes 26 lettres qu'en français, mais elles se prononcent différemment. Par exemple, **A** se dit « ey », **E** se dit « i », et **I** se dit « aï ». Savoir épeler son nom est très utile (« How do you spell your name? »).\n\n✅ Les salutations de base :\n• **Hello / Hi** = Bonjour / Salut\n• **Good morning** = Bonjour (le matin)\n• **Good evening** = Bonsoir\n• **Goodbye / Bye** = Au revoir\n\n✅ La politesse essentielle :\n• **Please** = S'il te plaît / s'il vous plaît\n• **Thank you / Thanks** = Merci\n• **You're welcome** = De rien\n\n💡 Exemple de mini-dialogue :\n— *Hello! How are you?* (Bonjour ! Comment vas-tu ?)\n— *I'm fine, thank you. And you?* (Je vais bien, merci. Et toi ?)\n\n⚠️ Attention : on dit **« Good night »** seulement pour se dire bonne nuit / au revoir le soir, pas pour saluer en arrivant.",
                    'questions' => [
                        [
                            'question' => 'Quelle est la traduction correcte de « Merci » en anglais ?',
                            'options' => ['Please', 'Thank you', 'Goodbye', 'Welcome'],
                            'correct' => [1],
                            'explanation' => '« Thank you » (ou « Thanks ») signifie « Merci ».',
                        ],
                        [
                            'question' => 'Que veut dire « Goodbye » ?',
                            'options' => ['Bonjour', 'S\'il vous plaît', 'Au revoir', 'De rien'],
                            'correct' => [2],
                            'explanation' => '« Goodbye » signifie « Au revoir ».',
                        ],
                        [
                            'question' => 'Parmi ces mots, lesquels sont des salutations / formules de politesse ? (plusieurs réponses)',
                            'options' => ['Hello', 'Table', 'Please', 'Goodbye'],
                            'correct' => [0, 2, 3],
                            'explanation' => '« Hello », « Please » et « Goodbye » servent à saluer ou être poli ; « Table » est un objet.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 2 — Les pronoms & le verbe to be',
                    'subtitle' => 'I am, you are, he is...',
                    'xp_reward' => 100,
                    'content' => "🎯 **Objectif** : utiliser les pronoms personnels et le verbe **to be** (être), le plus important de l'anglais.\n\n💡 Les pronoms personnels sujets :\n• **I** = je\n• **you** = tu / vous\n• **he / she / it** = il / elle / (objet ou animal)\n• **we** = nous\n• **they** = ils / elles\n\n✅ Le verbe **to be** (être) au présent :\n• I **am** (I'm)\n• you **are** (you're)\n• he / she / it **is** (he's)\n• we / you / they **are** (we're, they're)\n\n✅ Exemples :\n• *I am a student.* (Je suis étudiant.)\n• *She is happy.* (Elle est contente.)\n• *They are friends.* (Ils sont amis.)\n\n💡 Pour la forme négative, on ajoute **not** : *I am not tired.* (Je ne suis pas fatigué.) On peut contracter : *isn't*, *aren't*.\n\n⚠️ Erreur fréquente des francophones : ne dis pas « I have 20 years » mais **« I am 20 years old »** — en anglais, l'âge s'exprime avec *to be*, pas *to have* !",
                    'questions' => [
                        [
                            'question' => 'Choisissez la phrase grammaticalement correcte.',
                            'options' => ['She are happy.', 'She is happy.', 'She am happy.', 'She be happy.'],
                            'correct' => [1],
                            'explanation' => 'Avec « she » (3e personne du singulier), le verbe to be est « is ».',
                        ],
                        [
                            'question' => 'Comment dit-on « J\'ai 20 ans » en anglais ?',
                            'options' => ['I have 20 years.', 'I am 20 years old.', 'I have 20 years old.', 'I am 20 years.'],
                            'correct' => [1],
                            'explanation' => 'L\'âge s\'exprime avec « to be » : « I am 20 years old ».',
                        ],
                        [
                            'question' => 'Quelle est la bonne forme du verbe to be avec « they » ?',
                            'options' => ['is', 'am', 'are', 'be'],
                            'correct' => [2],
                            'explanation' => '« They are » : avec we / you / they, on utilise « are ».',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 3 — Les nombres, l\'heure et les dates',
                    'subtitle' => 'Compter et donner l\'heure',
                    'xp_reward' => 120,
                    'content' => "🎯 **Objectif** : compter, dire l'heure et les dates en anglais.\n\n💡 Les nombres de base : one (1), two (2), three (3), four (4), five (5), six (6), seven (7), eight (8), nine (9), ten (10). Puis eleven (11), twelve (12), thirteen (13)... twenty (20), thirty (30), hundred (100), thousand (1000).\n\n✅ Donner l'heure :\n• *What time is it?* (Quelle heure est-il ?)\n• *It's seven o'clock.* (Il est sept heures.)\n• *It's half past nine.* (Il est neuf heures et demie.)\n• *It's a quarter to five.* (Il est cinq heures moins le quart.)\n\n✅ Les jours : Monday, Tuesday, Wednesday, Thursday, Friday, Saturday, Sunday.\n✅ Les mois : January, February, March, April, May, June, July, August, September, October, November, December.\n\n💡 Pour les dates, l'anglais utilise souvent les nombres ordinaux : *the first of May* / *May 1st*. (1st, 2nd, 3rd, 4th...).\n\n⚠️ Attention à l'ordre américain : aux États-Unis on écrit souvent **mois/jour/année** (07/04 = 4 juillet), alors qu'au Royaume-Uni c'est jour/mois.",
                    'questions' => [
                        [
                            'question' => 'Comment dit-on le nombre « 12 » en anglais ?',
                            'options' => ['Twenty', 'Twelve', 'Twenty-two', 'Two'],
                            'correct' => [1],
                            'explanation' => '« Twelve » = 12. « Twenty » = 20.',
                        ],
                        [
                            'question' => 'Que signifie « It\'s half past nine » ?',
                            'options' => ['Il est neuf heures moins le quart', 'Il est huit heures et demie', 'Il est neuf heures et demie', 'Il est dix heures'],
                            'correct' => [2],
                            'explanation' => '« Half past nine » = neuf heures trente (et demie).',
                        ],
                        [
                            'question' => 'Parmi ces mots, lesquels sont des jours de la semaine ? (plusieurs réponses)',
                            'options' => ['Monday', 'August', 'Friday', 'Sunday'],
                            'correct' => [0, 2, 3],
                            'explanation' => 'Monday, Friday et Sunday sont des jours ; August est un mois.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 4 — Le présent simple',
                    'subtitle' => 'Habitudes et vérités générales',
                    'xp_reward' => 130,
                    'content' => "🎯 **Objectif** : utiliser le **présent simple** pour parler des habitudes et des faits.\n\n💡 Le présent simple sert à exprimer une habitude, une routine ou une vérité générale : *I work every day* (Je travaille tous les jours), *The sun rises in the east* (Le soleil se lève à l'est).\n\n✅ La conjugaison est simple : le verbe ne change pas... **sauf à la 3e personne du singulier** (he / she / it) où l'on ajoute un **-s** :\n• I work → he **works**\n• you play → she **plays**\n• we go → it **goes**\n\n✅ Règles d'orthographe du -s :\n• verbes en -o, -ch, -sh, -ss, -x → on ajoute **-es** : *go → goes*, *watch → watches*.\n• verbe en consonne + y → y devient **-ies** : *study → studies*.\n\n💡 Exemples :\n• *He plays football on Sundays.* (Il joue au foot le dimanche.)\n• *She watches TV in the evening.* (Elle regarde la télé le soir.)\n\n⚠️ Erreur classique : ne pas oublier le **-s** ! On dit *« She works »*, jamais *« She work »*.",
                    'questions' => [
                        [
                            'question' => 'Choisissez la phrase grammaticalement correcte.',
                            'options' => ['He work in Paris.', 'He works in Paris.', 'He working in Paris.', 'He are work in Paris.'],
                            'correct' => [1],
                            'explanation' => 'À la 3e personne du singulier au présent simple, on ajoute -s : « He works ».',
                        ],
                        [
                            'question' => 'Quelle est la forme correcte du verbe « to study » avec « she » ?',
                            'options' => ['she studys', 'she studies', 'she studyes', 'she study'],
                            'correct' => [1],
                            'explanation' => 'Consonne + y → -ies : « study » devient « studies ».',
                        ],
                        [
                            'question' => 'Le présent simple sert surtout à exprimer...',
                            'options' => ['une action en cours en ce moment précis', 'une habitude ou une vérité générale', 'une action terminée hier', 'une promesse pour demain'],
                            'correct' => [1],
                            'explanation' => 'Le présent simple décrit les habitudes, routines et faits généraux.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 5 — Articles & noms',
                    'subtitle' => 'a / an / the et les pluriels',
                    'xp_reward' => 140,
                    'content' => "🎯 **Objectif** : utiliser correctement les articles et former le pluriel des noms.\n\n💡 Les articles indéfinis **a** et **an** signifient « un / une » :\n• **a** devant un son de consonne : *a book*, *a car*, *a house*.\n• **an** devant un son de voyelle : *an apple*, *an hour*, *an umbrella*.\n\n⚠️ C'est le **son** qui compte, pas la lettre ! On dit *an hour* (le h est muet) mais *a university* (« you-niversity » commence par un son consonne).\n\n✅ L'article défini **the** = « le / la / les ». Il s'utilise pour quelque chose de précis : *the car* (la voiture qu'on connaît).\n\n✅ Le pluriel : on ajoute généralement un **-s** : *cat → cats*, *book → books*.\n• noms en -s, -ch, -sh, -x → **-es** : *box → boxes*, *bus → buses*.\n• consonne + y → **-ies** : *city → cities*.\n\n💡 Pluriels **irréguliers** à connaître : *man → men*, *woman → women*, *child → children*, *foot → feet*, *tooth → teeth*, *mouse → mice*.",
                    'questions' => [
                        [
                            'question' => 'Choisissez l\'article correct : « ___ apple ».',
                            'options' => ['a', 'an', 'the only', 'one'],
                            'correct' => [1],
                            'explanation' => '« Apple » commence par un son de voyelle, donc « an apple ».',
                        ],
                        [
                            'question' => 'Quel est le pluriel correct de « child » ?',
                            'options' => ['childs', 'childes', 'children', 'childrens'],
                            'correct' => [2],
                            'explanation' => '« Child » a un pluriel irrégulier : « children ».',
                        ],
                        [
                            'question' => 'Parmi ces noms, lesquels ont un pluriel irrégulier ? (plusieurs réponses)',
                            'options' => ['man', 'book', 'foot', 'car'],
                            'correct' => [0, 2],
                            'explanation' => '« man → men » et « foot → feet » sont irréguliers ; book → books et car → cars sont réguliers.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 6 — Le vocabulaire du quotidien',
                    'subtitle' => 'Famille, maison, nourriture',
                    'xp_reward' => 140,
                    'content' => "🎯 **Objectif** : enrichir son vocabulaire de tous les jours.\n\n💡 La **famille** (family) :\n• **mother / mum** = mère, **father / dad** = père\n• **brother** = frère, **sister** = sœur\n• **son** = fils, **daughter** = fille\n• **grandmother** = grand-mère, **grandfather** = grand-père\n\n✅ La **maison** (house / home) :\n• **kitchen** = cuisine, **bedroom** = chambre\n• **bathroom** = salle de bain, **living room** = salon\n• **door** = porte, **window** = fenêtre, **table** = table\n\n✅ La **nourriture** (food) :\n• **bread** = pain, **water** = eau, **milk** = lait\n• **apple** = pomme, **meat** = viande, **rice** = riz\n• **breakfast** = petit-déjeuner, **lunch** = déjeuner, **dinner** = dîner\n\n💡 Phrase utile : *I have breakfast in the kitchen with my family.* (Je prends mon petit-déjeuner dans la cuisine avec ma famille.)\n\n⚠️ Faux ami : **« a library »** n'est pas une librairie mais une **bibliothèque** ! Une librairie se dit **« a bookshop »**.",
                    'questions' => [
                        [
                            'question' => 'Quelle est la traduction correcte de « sœur » en anglais ?',
                            'options' => ['Brother', 'Daughter', 'Sister', 'Mother'],
                            'correct' => [2],
                            'explanation' => '« Sister » = sœur. « Brother » = frère.',
                        ],
                        [
                            'question' => 'Que signifie le mot anglais « kitchen » ?',
                            'options' => ['La chambre', 'La cuisine', 'La salle de bain', 'Le salon'],
                            'correct' => [1],
                            'explanation' => '« Kitchen » = la cuisine.',
                        ],
                        [
                            'question' => 'Parmi ces mots, lesquels désignent de la nourriture ? (plusieurs réponses)',
                            'options' => ['Bread', 'Window', 'Milk', 'Rice'],
                            'correct' => [0, 2, 3],
                            'explanation' => 'Bread (pain), Milk (lait) et Rice (riz) sont des aliments ; Window (fenêtre) non.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 7 — Le présent continu',
                    'subtitle' => 'be + verbe en -ing',
                    'xp_reward' => 150,
                    'content' => "🎯 **Objectif** : utiliser le **présent continu** (present continuous) pour décrire une action en cours.\n\n💡 Le présent continu décrit ce qui se passe **maintenant**, au moment où l'on parle : *I am reading* (Je suis en train de lire). On le forme avec **be + verbe-ing**.\n\n✅ Structure : sujet + (am / is / are) + verbe**-ing**\n• I **am working** (je suis en train de travailler)\n• she **is eating** (elle est en train de manger)\n• they **are playing** (ils sont en train de jouer)\n\n✅ Règles d'orthographe du -ing :\n• verbe finissant par -e muet → on retire le e : *make → making*, *write → writing*.\n• verbe court consonne-voyelle-consonne → on double la consonne : *run → running*, *sit → sitting*.\n\n💡 Différence clé avec le présent simple :\n• *I work every day.* (habitude → présent simple)\n• *I am working right now.* (en ce moment → présent continu)\n\n⚠️ Certains verbes d'état (like, want, know, love) ne se mettent **pas** au -ing. On dit *« I want a coffee »*, jamais *« I am wanting a coffee »*.",
                    'questions' => [
                        [
                            'question' => 'Choisissez la phrase qui décrit une action en cours maintenant.',
                            'options' => ['She reads a book every night.', 'She is reading a book now.', 'She read a book yesterday.', 'She will read a book.'],
                            'correct' => [1],
                            'explanation' => 'Le présent continu (is reading) exprime une action en cours en ce moment.',
                        ],
                        [
                            'question' => 'Quelle est la forme -ing correcte du verbe « run » ?',
                            'options' => ['runing', 'running', 'runninge', 'runs'],
                            'correct' => [1],
                            'explanation' => 'Consonne-voyelle-consonne courte : on double le n → « running ».',
                        ],
                        [
                            'question' => 'Quelle phrase est INCORRECTE ?',
                            'options' => ['They are playing.', 'I am working.', 'I am wanting a coffee.', 'He is eating.'],
                            'correct' => [2],
                            'explanation' => 'Le verbe d\'état « want » ne se met pas au -ing : on dit « I want a coffee ».',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 8 — Le passé simple',
                    'subtitle' => 'Past simple : réguliers & irréguliers',
                    'xp_reward' => 170,
                    'content' => "🎯 **Objectif** : raconter le passé avec le **past simple**.\n\n💡 Le past simple décrit une action terminée dans le passé : *Yesterday I worked* (Hier j'ai travaillé).\n\n✅ Verbes **réguliers** : on ajoute **-ed** :\n• work → **worked**, play → **played**, want → **wanted**.\n• verbe finissant par -e → on ajoute juste -d : *like → liked*.\n• consonne + y → -ied : *study → studied*.\n\n✅ Verbes **irréguliers** : il faut les apprendre par cœur (3 formes : base / prétérit / participe) :\n• go → **went**, have → **had**, see → **saw**\n• do → **did**, make → **made**, take → **took**\n• be → **was / were**, eat → **ate**, come → **came**\n\n💡 Exemples :\n• *I played football last weekend.* (J'ai joué au foot le week-end dernier.)\n• *She went to London in 2020.* (Elle est allée à Londres en 2020.)\n\n⚠️ À la forme **négative** et **interrogative**, on utilise **did** + verbe à la base (sans -ed) : *I did not go* (et non « I did not went »), *Did you see it?*.",
                    'questions' => [
                        [
                            'question' => 'Quel est le past simple du verbe « to go » ?',
                            'options' => ['goed', 'gone', 'went', 'goes'],
                            'correct' => [2],
                            'explanation' => '« Go » est irrégulier : son past simple est « went ».',
                        ],
                        [
                            'question' => 'Choisissez la phrase grammaticalement correcte.',
                            'options' => ['I did not went home.', 'I did not go home.', 'I not went home.', 'I didn\'t goed home.'],
                            'correct' => [1],
                            'explanation' => 'Après « did/did not », le verbe reste à la base : « I did not go ».',
                        ],
                        [
                            'question' => 'Parmi ces verbes, lesquels sont irréguliers au passé ? (plusieurs réponses)',
                            'options' => ['have (had)', 'play (played)', 'see (saw)', 'work (worked)'],
                            'correct' => [0, 2],
                            'explanation' => '« have → had » et « see → saw » sont irréguliers ; play et work prennent -ed.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 9 — Le futur',
                    'subtitle' => 'will et going to',
                    'xp_reward' => 170,
                    'content' => "🎯 **Objectif** : parler de l'avenir avec **will** et **going to**.\n\n💡 Deux façons principales d'exprimer le futur en anglais :\n\n✅ **will** + verbe à la base : pour une décision spontanée, une prédiction ou une promesse.\n• *I will help you.* (Je vais t'aider.)\n• *It will rain tomorrow.* (Il pleuvra demain.)\n• Forme contractée : *I'll*, *she'll*. Négatif : *won't* (= will not).\n\n✅ **be going to** + verbe : pour une intention ou un projet déjà décidé, ou une prédiction basée sur des indices.\n• *I am going to study tonight.* (Je vais étudier ce soir — c'est prévu.)\n• *Look at the clouds! It's going to rain.* (Regarde les nuages ! Il va pleuvoir.)\n\n💡 Astuce : **going to** = projet déjà décidé ; **will** = décision sur le moment ou prédiction.\n• *— The phone is ringing! — I'll answer it.* (will : décision immédiate)\n• *We are going to travel next summer.* (going to : projet planifié)\n\n⚠️ Après *will*, le verbe ne prend jamais de -s ni de -ing : on dit *« He will come »*, pas *« He will comes »*.",
                    'questions' => [
                        [
                            'question' => 'Choisissez la phrase grammaticalement correcte.',
                            'options' => ['He will comes tomorrow.', 'He will come tomorrow.', 'He will coming tomorrow.', 'He will to come tomorrow.'],
                            'correct' => [1],
                            'explanation' => 'Après « will », le verbe reste à la base : « He will come ».',
                        ],
                        [
                            'question' => 'Quelle forme exprime le mieux un projet déjà décidé : « Ce soir, je vais étudier » ?',
                            'options' => ['I study tonight.', 'I am going to study tonight.', 'I studied tonight.', 'I am studying yesterday.'],
                            'correct' => [1],
                            'explanation' => '« Be going to » exprime une intention/un projet planifié.',
                        ],
                        [
                            'question' => 'Que signifie « won\'t » ?',
                            'options' => ['want', 'will not', 'would', 'want to'],
                            'correct' => [1],
                            'explanation' => '« Won\'t » est la contraction de « will not ».',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 10 — Poser des questions',
                    'subtitle' => 'do / does et les mots en WH-',
                    'xp_reward' => 180,
                    'content' => "🎯 **Objectif** : poser des questions correctement.\n\n💡 Au présent simple, on pose des questions avec l'auxiliaire **do** (ou **does** à la 3e personne du singulier) placé en début de phrase :\n• *Do you like coffee?* (Aimes-tu le café ?)\n• *Does she work here?* (Travaille-t-elle ici ?)\n\n⚠️ Après *does*, le verbe **perd son -s** : on dit *« Does he play? »*, pas *« Does he plays? »*.\n\n✅ Les mots interrogatifs **WH-** ouvrent des questions ouvertes :\n• **What** = quoi / que → *What is your name?*\n• **Where** = où → *Where do you live?*\n• **When** = quand → *When does the train leave?*\n• **Who** = qui → *Who is that?*\n• **Why** = pourquoi → *Why are you late?*\n• **How** = comment → *How are you?*\n\n💡 Structure générale : **WH- + auxiliaire + sujet + verbe ?**\n• *Where do you work?* (Où travailles-tu ?)\n• *What time does it start?* (À quelle heure ça commence ?)\n\n✅ Avec le verbe **to be**, pas besoin de do : on inverse simplement sujet et verbe : *Are you ready?* (Es-tu prêt ?), *Where is the station?*.",
                    'questions' => [
                        [
                            'question' => 'Choisissez la question grammaticalement correcte.',
                            'options' => ['Does she plays tennis?', 'Does she play tennis?', 'Do she play tennis?', 'She does play tennis?'],
                            'correct' => [1],
                            'explanation' => 'Après « does », le verbe perd son -s : « Does she play tennis? ».',
                        ],
                        [
                            'question' => 'Quel mot interrogatif utilise-t-on pour demander un lieu (« où ») ?',
                            'options' => ['When', 'Where', 'Why', 'Who'],
                            'correct' => [1],
                            'explanation' => '« Where » = où (le lieu). « When » = quand, « Why » = pourquoi.',
                        ],
                        [
                            'question' => 'Parmi ces mots, lesquels sont des mots interrogatifs (WH-) ? (plusieurs réponses)',
                            'options' => ['What', 'With', 'Why', 'When'],
                            'correct' => [0, 2, 3],
                            'explanation' => 'What, Why et When sont des mots interrogatifs ; « With » (avec) ne l\'est pas.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 11 — Vocabulaire professionnel',
                    'subtitle' => 'Emails, entretien, présentation',
                    'xp_reward' => 200,
                    'content' => "🎯 **Objectif** : maîtriser l'anglais utile au travail.\n\n💡 Écrire un **email** professionnel :\n• Formule d'ouverture : *Dear Mr Smith,* / *Hello team,*\n• Corps : *I am writing to...* (Je vous écris pour...), *Please find attached...* (Veuillez trouver ci-joint...)\n• Formule de clôture : *Best regards,* / *Kind regards,* (Cordialement), suivi de ton nom.\n\n✅ Lors d'un **entretien d'embauche** (job interview) :\n• *Tell me about yourself.* (Parlez-moi de vous.)\n• *I have three years of experience in...* (J'ai trois ans d'expérience en...)\n• *I am a hard worker and a good team player.* (Je suis travailleur et j'aime le travail d'équipe.)\n\n✅ Pour une **présentation** :\n• *Today, I'm going to talk about...* (Aujourd'hui, je vais parler de...)\n• *To sum up / In conclusion...* (Pour résumer / En conclusion...)\n• *Thank you for your attention. Any questions?* (Merci de votre attention. Des questions ?)\n\n💡 Vocabulaire clé : **a meeting** (une réunion), **a deadline** (une échéance), **a colleague** (un collègue), **a manager** (un responsable), **a salary** (un salaire), **a CV / resume** (un CV).\n\n⚠️ Faux ami : **« actually »** ne veut pas dire « actuellement » mais **« en fait »** ! Pour « actuellement », dis **« currently »**.",
                    'questions' => [
                        [
                            'question' => 'Quelle formule termine poliment un email professionnel ?',
                            'options' => ['See you!', 'Best regards,', 'Bye bye', 'Thanks a lot dude'],
                            'correct' => [1],
                            'explanation' => '« Best regards » (Cordialement) est une formule de clôture professionnelle.',
                        ],
                        [
                            'question' => 'Que signifie le faux ami « actually » en anglais ?',
                            'options' => ['Actuellement', 'En fait', 'Réellement vrai', 'Activement'],
                            'correct' => [1],
                            'explanation' => '« Actually » = « en fait ». Pour « actuellement », on dit « currently ».',
                        ],
                        [
                            'question' => 'Parmi ces mots, lesquels appartiennent au vocabulaire du travail ? (plusieurs réponses)',
                            'options' => ['meeting', 'breakfast', 'deadline', 'colleague'],
                            'correct' => [0, 2, 3],
                            'explanation' => 'meeting (réunion), deadline (échéance) et colleague (collègue) sont professionnels ; breakfast est un repas.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 12 — Conversation & expressions courantes',
                    'subtitle' => 'Phrases utiles et politesse',
                    'xp_reward' => 220,
                    'content' => "🎯 **Objectif** : tenir une conversation courante avec assurance et politesse.\n\n💡 Phrases utiles du quotidien :\n• *Excuse me, can you help me?* (Excusez-moi, pouvez-vous m'aider ?)\n• *I'm sorry, I don't understand. Can you repeat, please?* (Désolé, je ne comprends pas. Pouvez-vous répéter ?)\n• *How much is it?* (Combien ça coûte ?)\n• *Could you speak more slowly, please?* (Pourriez-vous parler plus lentement ?)\n\n✅ La politesse fait la différence. **Could** et **would** sont plus polis que do/can :\n• *Could you...?* est plus poli que *Can you...?*\n• *I would like a coffee, please.* (Je voudrais un café.) est plus poli que *I want a coffee.*\n\n✅ Expressions courantes (idioms) à connaître :\n• *No problem.* (Pas de souci.)\n• *Never mind.* (Ce n'est pas grave.)\n• *It's up to you.* (C'est comme tu veux.)\n• *I have no idea.* (Je n'en ai aucune idée.)\n\n💡 Pour gagner du temps en parlant : *Well...*, *Let me think...*, *You know...* — ce sont des « filler words » naturels.\n\n🏆 Félicitations ! Tu as parcouru tout le chemin du débutant à l'intermédiaire. Continue à pratiquer en regardant des films en anglais et en parlant chaque jour. **Practice makes perfect!**",
                    'questions' => [
                        [
                            'question' => 'Quelle phrase est la plus polie pour commander ?',
                            'options' => ['Give me a coffee.', 'I want a coffee.', 'I would like a coffee, please.', 'Coffee now.'],
                            'correct' => [2],
                            'explanation' => '« I would like... please » est la formulation la plus polie.',
                        ],
                        [
                            'question' => 'Que veut dire l\'expression « Never mind » ?',
                            'options' => ['Jamais', 'Ce n\'est pas grave', 'Fais attention', 'Bonne idée'],
                            'correct' => [1],
                            'explanation' => '« Never mind » signifie « ce n\'est pas grave / laisse tomber ».',
                        ],
                        [
                            'question' => 'Comment demander poliment de répéter en anglais ?',
                            'options' => ['Repeat!', 'What?', 'Can you repeat, please?', 'Say again now.'],
                            'correct' => [2],
                            'explanation' => '« Can you repeat, please? » est la façon polie de demander une répétition.',
                        ],
                    ],
                ],
            ],
        ]);

        $this->command->info('  ✓ Roadmap Anglais créée (12 niveaux).');
    }
}
