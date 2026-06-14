<?php

namespace Database\Seeders\Roadmaps;

use Illuminate\Database\Seeder;

/**
 * Roadmap IA Générative & Prompt Engineering — apprendre à dialoguer efficacement avec les IA comme ChatGPT et Claude.
 */
class IaGenerativeRoadmapSeeder extends Seeder
{
    use RoadmapSeederHelper;

    public function run(): void
    {
        $this->createRoadmap([
            'title' => "Maîtrise l'IA Générative & le Prompt Engineering",
            'slug' => 'ia-generative-prompt-engineering',
            'domain' => 'data',
            'description' => "Comprends ce que sont les IA génératives (ChatGPT, Claude) et apprends à écrire des prompts efficaces pour gagner du temps au travail, à l'école ou pour ton entreprise. Une formation pratique, en français, pensée pour le contexte camerounais et africain : du concept de LLM jusqu'aux usages professionnels et à l'éthique.",
            'objectives' => "Comprendre ce qu'est un LLM et comment il génère du texte\nSaisir les notions de tokens, contexte et limites techniques\nÉcrire des prompts clairs, précis et structurés\nUtiliser les techniques few-shot, persona et chaînage d'instructions\nReconnaître les hallucinations et vérifier les réponses\nDécouvrir le RAG, l'éthique et les outils du marché",
            'icon' => '🤖',
            'color' => '#7C3AED',
            'difficulty' => 'beginner',
            'required_packs' => [],
            'pass_threshold' => 70,
            'order' => 20,
            'levels' => [
                [
                    'title' => 'Niveau 1 — Qu\'est-ce qu\'un LLM ?',
                    'subtitle' => 'Comprendre le moteur derrière ChatGPT et Claude',
                    'xp_reward' => 100,
                    'content' => "🎯 **Objectif** : comprendre ce qu'est une IA générative et un LLM.\n\nUn **LLM** (Large Language Model, ou « grand modèle de langage ») est un programme entraîné sur d'énormes quantités de textes. Son principe est simple : il **prédit le mot le plus probable** qui suit dans une phrase.\n\n💡 Exemple : si tu écris « Le marché central de Douala est très... », le modèle propose probablement « animé », « fréquenté » ou « grand ».\n\n• Il ne « comprend » pas comme un humain : il calcule des probabilités.\n• Il a appris des régularités du langage, pas une base de vérités.\n• ChatGPT (OpenAI) et Claude (Anthropic) sont des produits basés sur des LLM.\n\n✅ À retenir : un LLM est un excellent assistant de rédaction et de raisonnement, mais c'est un outil statistique, pas un oracle.\n\n⚠️ Conséquence directe : il peut se tromper avec assurance. On apprendra à gérer cela plus loin.",
                    'questions' => [
                        ['question' => 'Que signifie l\'abréviation LLM ?', 'options' => ['Logical Learning Machine', 'Large Language Model', 'Linear Logic Method', 'Local Language Memory'], 'correct' => [1], 'explanation' => 'LLM signifie Large Language Model, un grand modèle de langage entraîné sur du texte.'],
                        ['question' => 'Sur quel principe fondamental repose un LLM pour générer du texte ?', 'options' => ['Il copie des phrases d\'une base de données', 'Il prédit le mot suivant le plus probable', 'Il consulte Internet en temps réel à chaque réponse', 'Il applique des règles de grammaire programmées à la main'], 'correct' => [1], 'explanation' => 'Un LLM fonctionne en prédisant statistiquement le mot (token) suivant le plus probable.'],
                        ['question' => 'Quelles affirmations sont correctes au sujet d\'un LLM ?', 'options' => ['Il calcule des probabilités sur le langage', 'Il comprend le monde exactement comme un humain', 'Il peut se tromper tout en paraissant sûr de lui', 'C\'est une base de données de faits vérifiés'], 'correct' => [0, 2], 'explanation' => 'Un LLM est un outil statistique qui peut produire des erreurs présentées avec assurance.'],
                    ],
                ],
                [
                    'title' => 'Niveau 2 — Tokens & fenêtre de contexte',
                    'subtitle' => 'Comment l\'IA découpe et mémorise le texte',
                    'xp_reward' => 110,
                    'content' => "🎯 **Objectif** : comprendre les tokens et la fenêtre de contexte.\n\nUn LLM ne lit pas des mots entiers mais des **tokens** : de petits morceaux de texte (mots, parties de mots, ponctuation).\n\n💡 Exemple : le mot « Yaoundé » peut être découpé en plusieurs tokens. En anglais, 1 token ≈ 0,75 mot environ.\n\nLa **fenêtre de contexte** est la quantité maximale de tokens que le modèle peut « voir » en une fois (ta question + l'historique + sa réponse).\n\n• Si la conversation devient très longue, les premiers messages peuvent « sortir » du contexte et être oubliés.\n• Plus tu envoies de tokens, plus cela coûte (sur les API payantes).\n• Un prompt concis et bien ciblé est souvent plus efficace qu'un texte interminable.\n\n✅ À retenir : tokens = unités de texte ; contexte = mémoire de travail limitée du modèle.\n\n⚠️ Pour un long document, résume ou découpe-le en morceaux pertinents plutôt que de tout coller.",
                    'questions' => [
                        ['question' => 'Qu\'est-ce qu\'un token pour un LLM ?', 'options' => ['Un mot de passe d\'accès à l\'IA', 'Un petit morceau de texte (mot ou partie de mot)', 'Une image générée par l\'IA', 'Un point de fidélité'], 'correct' => [1], 'explanation' => 'Un token est une unité de texte (mot, sous-mot ou ponctuation) que le modèle manipule.'],
                        ['question' => 'Que désigne la « fenêtre de contexte » ?', 'options' => ['La fenêtre du navigateur web', 'La quantité maximale de tokens que le modèle peut traiter en une fois', 'Le délai de réponse de l\'IA', 'Le nombre d\'utilisateurs connectés'], 'correct' => [1], 'explanation' => 'La fenêtre de contexte est la limite de tokens que le modèle peut prendre en compte simultanément.'],
                        ['question' => 'Que peut-il se passer si une conversation devient très longue ?', 'options' => ['Le modèle peut oublier les premiers messages', 'La vitesse double automatiquement', 'Le modèle devient toujours plus intelligent', 'Le coût en tokens diminue'], 'correct' => [0], 'explanation' => 'Au-delà de la fenêtre de contexte, les messages les plus anciens peuvent être perdus.'],
                    ],
                ],
                [
                    'title' => 'Niveau 3 — Écrire un bon prompt',
                    'subtitle' => 'Les bases d\'une instruction claire et précise',
                    'xp_reward' => 120,
                    'content' => "🎯 **Objectif** : structurer un prompt efficace.\n\nUn **prompt** est l'instruction que tu donnes à l'IA. La qualité de la réponse dépend directement de la qualité du prompt.\n\nUn bon prompt précise :\n• **La tâche** : que veux-tu exactement ?\n• **Le contexte** : pour qui, dans quel cadre ?\n• **Le format** : liste, tableau, e-mail, nombre de mots ?\n• **Le ton** : formel, amical, professionnel ?\n\n💡 Comparons :\n```\n❌ Faible : « Écris un texte sur l'emploi. »\n\n✅ Bon : « Rédige une lettre de motivation de 150 mots\npour un poste de caissier dans un supermarché à Douala.\nTon professionnel, candidat jeune diplômé sans expérience. »\n```\n\n✅ À retenir : sois **spécifique**. Plus tu donnes de détails utiles, meilleure est la réponse.\n\n⚠️ Évite les prompts vagues comme « parle-moi de ça » : l'IA devra deviner et risque de répondre à côté.",
                    'questions' => [
                        ['question' => 'Qu\'est-ce qu\'un prompt ?', 'options' => ['Le nom du modèle d\'IA', 'L\'instruction que l\'on donne à l\'IA', 'Le bouton d\'envoi', 'Un type de token'], 'correct' => [1], 'explanation' => 'Le prompt est la consigne ou question fournie à l\'IA pour obtenir une réponse.'],
                        ['question' => 'Lequel de ces prompts est le plus efficace ?', 'options' => ['« Parle de business. »', '« Écris un truc bien. »', '« Liste 5 idées de business à petit budget pour un étudiant à Yaoundé, format puces. »', '« Aide-moi. »'], 'correct' => [2], 'explanation' => 'Il précise la tâche, le contexte (étudiant, Yaoundé), la quantité et le format.'],
                        ['question' => 'Quels éléments rendent un prompt plus performant ?', 'options' => ['Préciser le format attendu', 'Indiquer le contexte et le public', 'Rester le plus vague possible', 'Définir le ton souhaité'], 'correct' => [0, 1, 3], 'explanation' => 'Tâche, contexte, format et ton clairs guident l\'IA vers une meilleure réponse ; le flou nuit.'],
                    ],
                ],
                [
                    'title' => 'Niveau 4 — Few-shot : apprendre par l\'exemple',
                    'subtitle' => 'Montrer des exemples pour guider l\'IA',
                    'xp_reward' => 130,
                    'content' => "🎯 **Objectif** : utiliser la technique du few-shot.\n\nLe **few-shot prompting** consiste à donner à l'IA **quelques exemples** de ce que tu attends avant de poser ta vraie demande. Le modèle imite alors le format et le style des exemples.\n\n• **Zero-shot** : tu demandes sans exemple.\n• **Few-shot** : tu fournis 1 à 3 exemples (« shots »).\n\n💡 Exemple de classification de messages clients :\n```\nMessage : « Je n'ai pas reçu ma commande. » -> Catégorie : Livraison\nMessage : « Comment payer par Mobile Money ? » -> Catégorie : Paiement\nMessage : « Le produit est cassé. » -> Catégorie : ?\n```\nL'IA déduit la catégorie « Qualité produit » en imitant le motif.\n\n✅ À retenir : les exemples valent mieux qu'une longue explication. Ils fixent le format de sortie.\n\n⚠️ Donne des exemples cohérents et représentatifs : des exemples contradictoires embrouillent le modèle.",
                    'questions' => [
                        ['question' => 'Qu\'est-ce que le few-shot prompting ?', 'options' => ['Poser plusieurs questions à la fois', 'Donner quelques exemples avant la vraie demande', 'Utiliser l\'IA peu de temps', 'Envoyer un prompt très court'], 'correct' => [1], 'explanation' => 'Le few-shot fournit quelques exemples pour montrer à l\'IA le format et le style attendus.'],
                        ['question' => 'Comment appelle-t-on un prompt sans aucun exemple fourni ?', 'options' => ['Few-shot', 'Zero-shot', 'Multi-shot', 'One-shot'], 'correct' => [1], 'explanation' => 'Demander directement sans exemple s\'appelle le zero-shot.'],
                        ['question' => 'Pourquoi les exemples doivent-ils être cohérents en few-shot ?', 'options' => ['Pour réduire le nombre de tokens', 'Pour ne pas embrouiller le modèle sur le format attendu', 'Parce que l\'IA refuse sinon de répondre', 'Pour accélérer Internet'], 'correct' => [1], 'explanation' => 'Des exemples contradictoires brouillent le motif que l\'IA doit imiter.'],
                    ],
                ],
                [
                    'title' => 'Niveau 5 — Rôle & persona',
                    'subtitle' => 'Faire jouer un rôle à l\'IA pour de meilleurs résultats',
                    'xp_reward' => 140,
                    'content' => "🎯 **Objectif** : attribuer un rôle (persona) à l'IA.\n\nDonner un **rôle** à l'IA oriente son vocabulaire, son niveau de détail et son ton. On commence souvent par « Tu es... ».\n\n💡 Exemples :\n```\n« Tu es un comptable expert du droit OHADA.\nExplique simplement à un commerçant de Bafoussam\ncomment tenir un livre de caisse. »\n\n« Tu es un coach carrière bienveillant.\nDonne-moi 3 conseils pour réussir un entretien\nd'embauche à Yaoundé. »\n```\n\n• Le rôle adapte le niveau (débutant vs expert).\n• Tu peux préciser le **public** : « explique à un élève de 15 ans ».\n• Combine rôle + format + ton pour un contrôle maximal.\n\n✅ À retenir : « Tu es [rôle] » + « parle à [public] » est une recette puissante et facile.\n\n⚠️ Le rôle ne rend pas l'IA réellement experte : elle simule le style d'un expert, vérifie toujours les faits sensibles (juridiques, médicaux).",
                    'questions' => [
                        ['question' => 'À quoi sert d\'attribuer un rôle (« Tu es un... ») à l\'IA ?', 'options' => ['À la rendre plus lente', 'À orienter son ton, son vocabulaire et son niveau de détail', 'À débloquer des fonctions cachées payantes', 'À supprimer la fenêtre de contexte'], 'correct' => [1], 'explanation' => 'Le rôle ajuste le style, le ton et le niveau de la réponse selon le persona demandé.'],
                        ['question' => 'Quelle formulation utilise correctement un persona ?', 'options' => ['« Réponds vite. »', '« Tu es un médecin, explique à un patient ce qu\'est l\'hypertension. »', '« Donne 10. »', '« Pourquoi ? »'], 'correct' => [1], 'explanation' => 'Elle définit un rôle (médecin) et un public (patient), guidant la réponse.'],
                        ['question' => 'Qu\'est-ce que le rôle ne garantit PAS ?', 'options' => ['Un ton adapté', 'Une expertise réelle et des faits toujours exacts', 'Un vocabulaire ciblé', 'Un niveau de détail ajusté'], 'correct' => [1], 'explanation' => 'L\'IA simule le style d\'un expert mais peut se tromper ; les faits sensibles doivent être vérifiés.'],
                    ],
                ],
                [
                    'title' => 'Niveau 6 — Chaînage d\'instructions',
                    'subtitle' => 'Décomposer une tâche complexe en étapes',
                    'xp_reward' => 150,
                    'content' => "🎯 **Objectif** : enchaîner les instructions pour les tâches complexes.\n\nPour une tâche difficile, ne demande pas tout d'un coup. **Décompose** en étapes : c'est le **chaînage d'instructions** (prompt chaining).\n\n💡 Au lieu de « Crée mon business plan complet », procède par étapes :\n• Étape 1 : « Liste les sections d'un business plan pour une boutique en ligne. »\n• Étape 2 : « Rédige maintenant la section Étude de marché pour Douala. »\n• Étape 3 : « Calcule un budget de démarrage estimatif. »\n\nUne autre technique très utile : demander à l'IA de **réfléchir étape par étape**.\n```\n« Résous ce problème en expliquant ton raisonnement\nétape par étape avant de donner la réponse finale. »\n```\nCela améliore nettement la qualité sur les calculs et la logique.\n\n✅ À retenir : petites étapes claires > une consigne géante. Tu gardes le contrôle et tu corriges au fur et à mesure.\n\n⚠️ Vérifie chaque étape avant de passer à la suivante pour éviter d'accumuler les erreurs.",
                    'questions' => [
                        ['question' => 'En quoi consiste le chaînage d\'instructions (prompt chaining) ?', 'options' => ['Envoyer le même prompt plusieurs fois', 'Décomposer une tâche complexe en étapes successives', 'Attacher des fichiers à un prompt', 'Bloquer l\'IA sur une seule réponse'], 'correct' => [1], 'explanation' => 'Le chaînage découpe une tâche complexe en plusieurs étapes plus simples et maîtrisables.'],
                        ['question' => 'Quelle consigne améliore la qualité sur les problèmes de logique ou de calcul ?', 'options' => ['« Réponds en un seul mot. »', '« Explique ton raisonnement étape par étape avant la réponse finale. »', '« Sois le plus rapide possible. »', '« Ignore le contexte. »'], 'correct' => [1], 'explanation' => 'Demander un raisonnement étape par étape pousse le modèle à mieux structurer sa réflexion.'],
                        ['question' => 'Pourquoi vérifier chaque étape d\'une chaîne d\'instructions ?', 'options' => ['Pour gagner des tokens gratuits', 'Pour éviter d\'accumuler et propager les erreurs', 'Parce que l\'IA l\'exige', 'Pour changer de modèle'], 'correct' => [1], 'explanation' => 'Une erreur en début de chaîne se répercute sur les étapes suivantes si on ne la corrige pas.'],
                    ],
                ],
                [
                    'title' => 'Niveau 7 — Limites & hallucinations',
                    'subtitle' => 'Repérer et gérer les fausses informations',
                    'xp_reward' => 160,
                    'content' => "🎯 **Objectif** : reconnaître les hallucinations et les limites de l'IA.\n\nUne **hallucination** est une réponse fausse mais présentée comme vraie : nom inventé, fausse date, citation imaginaire, loi qui n'existe pas.\n\n💡 Pourquoi ? Le modèle prédit du texte « plausible », pas forcément « vrai ». Il peut inventer pour combler un trou.\n\nRisques fréquents :\n• Données chiffrées et statistiques inventées.\n• Sources et références fictives.\n• Connaissances dépassées (date d'entraînement limitée).\n• Détails locaux erronés (adresses, prix, lois).\n\nComment se protéger :\n• **Vérifie** les faits importants sur des sources fiables.\n• Demande à l'IA de **citer ses limites** : « Si tu n'es pas sûr, dis-le. »\n• Ne fais jamais confiance aveuglément pour le médical, le juridique ou la finance.\n\n✅ À retenir : l'IA est un assistant, pas une source de vérité. La vérification reste ton travail.\n\n⚠️ Une réponse confiante n'est pas une réponse exacte.",
                    'questions' => [
                        ['question' => 'Qu\'est-ce qu\'une hallucination d\'un LLM ?', 'options' => ['Une image floue', 'Une réponse fausse présentée comme vraie', 'Un bug d\'affichage', 'Une réponse trop courte'], 'correct' => [1], 'explanation' => 'Une hallucination est une information inexacte que le modèle présente avec assurance.'],
                        ['question' => 'Pour quelle raison les LLM peuvent-ils inventer des faits ?', 'options' => ['Ils prédisent du texte plausible, pas forcément vrai', 'Ils sont connectés à de fausses bases volontairement', 'Ils refusent de répondre correctement', 'Ils manquent d\'électricité'], 'correct' => [0], 'explanation' => 'Le modèle génère du texte vraisemblable et peut combler les manques par des inventions.'],
                        ['question' => 'Quelles précautions limitent les risques liés aux hallucinations ?', 'options' => ['Vérifier les faits importants sur des sources fiables', 'Faire une confiance totale et aveugle', 'Demander à l\'IA de signaler quand elle n\'est pas sûre', 'Éviter de l\'utiliser seule pour le juridique ou le médical'], 'correct' => [0, 2, 3], 'explanation' => 'Vérification, signalement d\'incertitude et prudence sur les domaines sensibles réduisent les risques.'],
                    ],
                ],
                [
                    'title' => 'Niveau 8 — RAG : nourrir l\'IA avec tes données',
                    'subtitle' => 'La notion de génération augmentée par récupération',
                    'xp_reward' => 170,
                    'content' => "🎯 **Objectif** : comprendre la notion de RAG.\n\nLe **RAG** (Retrieval-Augmented Generation, « génération augmentée par récupération ») permet de fournir à l'IA **tes propres documents** pour qu'elle réponde à partir d'eux, et non seulement de sa mémoire d'entraînement.\n\n💡 Principe en 3 étapes :\n• 1) On **recherche** les passages pertinents dans tes documents (PDF, base de connaissances).\n• 2) On **injecte** ces passages dans le prompt comme contexte.\n• 3) L'IA **génère** une réponse fondée sur ces extraits.\n\nExemple concret : un chatbot d'une PME de Douala qui répond aux clients à partir du catalogue de produits et de la FAQ interne.\n\nAvantages :\n• Réponses plus **à jour** et **spécifiques** à ton entreprise.\n• Moins d'hallucinations car l'IA s'appuie sur des sources réelles.\n• On peut **citer la source** utilisée.\n\n✅ À retenir : RAG = chercher d'abord, puis générer à partir des bons documents.\n\n⚠️ La qualité dépend des documents fournis : mauvaises sources = mauvaises réponses.",
                    'questions' => [
                        ['question' => 'Que permet principalement le RAG ?', 'options' => ['Accélérer le clavier', 'Faire répondre l\'IA à partir de tes propres documents', 'Réduire la taille du modèle', 'Traduire automatiquement en anglais'], 'correct' => [1], 'explanation' => 'Le RAG fournit des documents pertinents à l\'IA pour qu\'elle réponde sur leur base.'],
                        ['question' => 'Quel est le bon ordre des étapes du RAG ?', 'options' => ['Générer, puis rechercher', 'Rechercher les passages pertinents, les injecter, puis générer', 'Supprimer le contexte, puis répondre', 'Deviner, puis vérifier seul'], 'correct' => [1], 'explanation' => 'On récupère d\'abord les passages utiles, on les ajoute au prompt, puis l\'IA génère la réponse.'],
                        ['question' => 'Quels sont des avantages du RAG ?', 'options' => ['Des réponses plus à jour et spécifiques', 'Moins d\'hallucinations grâce à des sources réelles', 'La possibilité de citer la source utilisée', 'Aucune importance de la qualité des documents'], 'correct' => [0, 1, 2], 'explanation' => 'Le RAG améliore l\'actualité, la précision et la traçabilité ; mais la qualité des sources reste cruciale.'],
                    ],
                ],
                [
                    'title' => 'Niveau 9 — Éthique & usages professionnels',
                    'subtitle' => 'Utiliser l\'IA de façon responsable au travail',
                    'xp_reward' => 185,
                    'content' => "🎯 **Objectif** : utiliser l'IA de manière éthique et professionnelle.\n\nL'IA générative est puissante au travail, mais son usage demande de la responsabilité.\n\nBonnes pratiques pro :\n• **Confidentialité** : ne colle pas de données sensibles (numéros clients, dossiers médicaux, secrets d'entreprise) dans un outil public.\n• **Vérification** : relis et valide toujours avant d'envoyer un livrable.\n• **Transparence** : indique quand un contenu a été assisté par IA si le contexte l'exige.\n• **Propriété & plagiat** : l'IA peut produire du texte proche d'œuvres existantes ; reformule et cite tes sources.\n\n💡 Cas d'usage utiles : rédiger des e-mails, résumer des réunions, traduire, brainstormer des idées, préparer des supports de formation, coder.\n\n⚠️ Risques : biais (le modèle reflète les données apprises), dépendance excessive, désinformation.\n\n✅ À retenir : l'IA augmente tes compétences, elle ne te remplace pas. Tu restes responsable du résultat final.",
                    'questions' => [
                        ['question' => 'Que faut-il éviter de coller dans un outil d\'IA public ?', 'options' => ['Une idée de recette de cuisine', 'Des données sensibles ou confidentielles de clients', 'Une question de culture générale', 'Un brouillon de poème'], 'correct' => [1], 'explanation' => 'Les données sensibles ou confidentielles ne doivent pas être saisies dans un outil public.'],
                        ['question' => 'Pourquoi un LLM peut-il reproduire des biais ?', 'options' => ['Parce qu\'il choisit volontairement de discriminer', 'Parce qu\'il reflète les données sur lesquelles il a été entraîné', 'Parce qu\'il n\'a pas assez de tokens', 'Parce qu\'il est connecté à un seul site'], 'correct' => [1], 'explanation' => 'Le modèle apprend de données qui contiennent des biais et peut donc les reproduire.'],
                        ['question' => 'Quelles pratiques relèvent d\'un usage professionnel responsable de l\'IA ?', 'options' => ['Vérifier et relire avant d\'envoyer un livrable', 'Protéger la confidentialité des données', 'Publier tel quel sans jamais relire', 'Citer ses sources et reformuler pour éviter le plagiat'], 'correct' => [0, 1, 3], 'explanation' => 'Vérification, confidentialité et lutte contre le plagiat sont essentielles ; publier sans relire est risqué.'],
                    ],
                ],
                [
                    'title' => 'Niveau 10 — Les outils : ChatGPT, Claude & au-delà',
                    'subtitle' => 'Choisir et combiner les outils du marché',
                    'xp_reward' => 200,
                    'content' => "🎯 **Objectif** : connaître les principaux outils et passer à la pratique.\n\nPrincipaux assistants conversationnels :\n• **ChatGPT** (OpenAI) : très populaire, polyvalent, écosystème de plugins/GPTs.\n• **Claude** (Anthropic) : réputé pour les longs textes, le raisonnement et un ton soigné.\n• Autres : Gemini (Google), assistants intégrés à des outils bureautiques.\n\nConseils pratiques :\n• Choisis l'outil selon la tâche (rédaction longue, code, analyse de documents).\n• Réutilise tes meilleurs prompts : crée ta propre bibliothèque.\n• Combine les techniques apprises : rôle + few-shot + format + étape par étape.\n\n💡 Exemple récapitulatif :\n```\n« Tu es un formateur (rôle).\nVoici 2 exemples de fiches de cours (few-shot).\nCrée une fiche similaire sur la gestion d'un budget familial\nau Cameroun (tâche), en tableau de 5 lignes (format),\nexplique ton choix de rubriques (étape par étape). »\n```\n\n🏆 **Félicitations !** Tu maîtrises désormais les bases de l'IA générative et du prompt engineering. Tu peux gagner des heures chaque semaine, automatiser des tâches et te démarquer.\n\n**Débouchés & évolution** : assistant rédactionnel, community manager, support client augmenté, analyste de données, et le métier en pleine croissance de **Prompt Engineer** / spécialiste IA. Continue à pratiquer : c'est une compétence très recherchée sur le marché africain et international !",
                    'questions' => [
                        ['question' => 'Quel outil est développé par Anthropic ?', 'options' => ['ChatGPT', 'Claude', 'Gemini', 'Copilot'], 'correct' => [1], 'explanation' => 'Claude est l\'assistant conversationnel développé par Anthropic.'],
                        ['question' => 'Quelle est une bonne pratique pour gagner en efficacité avec ces outils ?', 'options' => ['Tout recommencer à zéro à chaque fois', 'Se constituer une bibliothèque de ses meilleurs prompts', 'Ne jamais préciser le format', 'Utiliser un seul mot par prompt'], 'correct' => [1], 'explanation' => 'Réutiliser et capitaliser ses meilleurs prompts fait gagner un temps considérable.'],
                        ['question' => 'Quelles techniques peut-on combiner dans un même prompt avancé ?', 'options' => ['Attribuer un rôle (persona)', 'Donner des exemples (few-shot)', 'Préciser le format de sortie', 'Demander un raisonnement étape par étape'], 'correct' => [0, 1, 2, 3], 'explanation' => 'Un prompt avancé combine rôle, exemples, format et raisonnement étape par étape pour un résultat optimal.'],
                    ],
                ],
            ],
        ]);

        $this->command->info('  ✓ Roadmap IA Générative créée (10 niveaux).');
    }
}
