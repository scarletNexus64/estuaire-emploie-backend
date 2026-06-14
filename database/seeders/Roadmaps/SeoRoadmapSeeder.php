<?php

namespace Database\Seeders\Roadmaps;

use Illuminate\Database\Seeder;

/**
 * Roadmap SEO — maîtriser le référencement naturel pour positionner un site en tête de Google.
 */
class SeoRoadmapSeeder extends Seeder
{
    use RoadmapSeederHelper;

    public function run(): void
    {
        $this->createRoadmap([
            'title' => 'Maîtrise le SEO (Référencement Naturel)',
            'slug' => 'seo-referencement-naturel',
            'domain' => 'marketing',
            'description' => "Apprends à positionner un site web en tête des résultats Google sans payer de publicité. Du fonctionnement des moteurs de recherche jusqu'au suivi des performances, cette roadmap couvre les mots-clés, le SEO on-page, technique et local, le netlinking, l'E-E-A-T et les outils de mesure. Exemples concrets adaptés au marché camerounais (Douala, Yaoundé, mobile money).",
            'objectives' => "Comprendre comment Google explore, indexe et classe les pages\nMaîtriser la recherche de mots-clés et l'intention de recherche\nOptimiser les balises et le contenu d'une page (on-page)\nRégler les bases techniques : vitesse, mobile, indexation\nDévelopper une stratégie de backlinks et d'autorité\nAnalyser ses performances avec Search Console et Analytics",
            'icon' => '🔍',
            'color' => '#16A34A',
            'difficulty' => 'intermediate',
            'required_packs' => [],
            'pass_threshold' => 70,
            'order' => 20,
            'levels' => [
                [
                    'title' => 'Niveau 1 — Comment fonctionnent les moteurs de recherche',
                    'subtitle' => 'Exploration, indexation et classement',
                    'xp_reward' => 100,
                    'content' => "🎯 **Objectif** : comprendre le cycle de vie d'une page dans Google.\n\n💡 Google travaille en 3 étapes :\n• **Exploration (crawl)** : des robots (Googlebot) parcourent le web en suivant les liens.\n• **Indexation** : les pages découvertes sont analysées et stockées dans un immense index.\n• **Classement (ranking)** : pour chaque recherche, Google trie les pages indexées selon des centaines de critères (pertinence, autorité, expérience utilisateur).\n\n⚠️ Une page non explorée ou non indexée n'apparaîtra JAMAIS dans les résultats, même si son contenu est excellent.\n\n✅ Le **SEO** (Search Engine Optimization) = l'ensemble des techniques pour améliorer ces 3 étapes et gagner en visibilité **sans payer** (contrairement au SEA, la publicité payante Google Ads).\n\nExemple : un site de petites annonces à Douala veut apparaître quand un internaute tape « voiture occasion Douala ». Si Googlebot ne trouve pas la page ou si elle n'est pas indexée, elle reste invisible.",
                    'questions' => [
                        ['question' => 'Quelle est la première étape réalisée par Google pour découvrir une page web ?', 'options' => ['Le classement', 'L\'exploration (crawl)', 'La publicité', 'La conversion'], 'correct' => [1], 'explanation' => 'Googlebot explore d\'abord le web en suivant les liens avant d\'indexer puis de classer.'],
                        ['question' => 'Que signifie le sigle SEO ?', 'options' => ['Search Engine Optimization', 'Site Editing Online', 'Secure Encrypted Output', 'Social Engagement Operation'], 'correct' => [0], 'explanation' => 'SEO signifie Search Engine Optimization, soit l\'optimisation pour les moteurs de recherche.'],
                        ['question' => 'Quelle différence essentielle entre le SEO et le SEA (Google Ads) ?', 'options' => ['Le SEO est payant à chaque clic', 'Le SEA est gratuit', 'Le SEO vise un trafic organique non payant', 'Le SEA améliore l\'indexation'], 'correct' => [2], 'explanation' => 'Le SEO génère un trafic organique gratuit, alors que le SEA repose sur des annonces payantes.'],
                    ],
                ],
                [
                    'title' => 'Niveau 2 — Mots-clés et intention de recherche',
                    'subtitle' => 'Trouver ce que cherchent vraiment les internautes',
                    'xp_reward' => 110,
                    'content' => "🎯 **Objectif** : choisir les bons mots-clés en fonction de l'intention.\n\n💡 Un **mot-clé** est la requête tapée par l'internaute. On distingue 4 types d'intention :\n• **Informationnelle** : « comment créer un CV » (l'utilisateur veut apprendre).\n• **Navigationnelle** : « facebook connexion » (il cherche un site précis).\n• **Commerciale** : « meilleur smartphone 2026 » (il compare avant d'acheter).\n• **Transactionnelle** : « acheter iPhone Douala » (il veut acheter maintenant).\n\n✅ La **longue traîne** (long tail) = des requêtes longues et précises (« formation comptabilité OHADA Yaoundé »), moins concurrentielles et qui convertissent mieux que les mots génériques (« formation »).\n\n⚠️ Ne pas viser uniquement des mots-clés ultra-concurrentiels : un nouveau site ne battra pas les géants sur « emploi ».\n\nTableau d'analyse :\n\n| Mot-clé | Volume | Concurrence | Intention |\n|---|---|---|---|\n| emploi | élevé | forte | mixte |\n| offre emploi comptable Douala | faible | faible | transactionnelle |\n\nOutils : Google Keyword Planner, suggestions de la barre de recherche, « Recherches associées » en bas de page.",
                    'questions' => [
                        ['question' => 'La requête « acheter ordinateur portable Yaoundé » correspond à quelle intention ?', 'options' => ['Informationnelle', 'Navigationnelle', 'Transactionnelle', 'Aucune'], 'correct' => [2], 'explanation' => 'Le mot « acheter » associé à un lieu indique une intention transactionnelle d\'achat immédiat.'],
                        ['question' => 'Qu\'est-ce que la « longue traîne » en SEO ?', 'options' => ['Des mots-clés courts et génériques', 'Des requêtes longues, précises et peu concurrentielles', 'Des liens entrants', 'Des balises HTML'], 'correct' => [1], 'explanation' => 'La longue traîne désigne les requêtes longues et spécifiques, moins concurrentielles et plus qualifiées.'],
                        ['question' => 'Quels avantages présente généralement la longue traîne ? (plusieurs réponses)', 'options' => ['Moins de concurrence', 'Meilleur taux de conversion', 'Volume de recherche toujours énorme', 'Trafic plus qualifié'], 'correct' => [0, 1, 3], 'explanation' => 'La longue traîne offre moins de concurrence, un meilleur taux de conversion et un trafic plus ciblé, mais un volume plus faible.'],
                    ],
                ],
                [
                    'title' => 'Niveau 3 — SEO on-page : les balises HTML',
                    'subtitle' => 'Title, meta description, Hn et URL',
                    'xp_reward' => 120,
                    'content' => "🎯 **Objectif** : optimiser les balises clés d'une page.\n\n💡 Balises essentielles :\n• **Title** : titre affiché dans les résultats Google (≈ 50-60 caractères), doit contenir le mot-clé principal.\n• **Meta description** : résumé sous le titre (≈ 150-160 caractères), n'influence pas le classement mais améliore le taux de clic (CTR).\n• **Balises de titre Hn** : `<h1>` unique par page (titre principal), puis `<h2>`, `<h3>` pour la hiérarchie.\n• **URL** : courte, lisible, avec le mot-clé (`/formation-comptabilite` plutôt que `/page?id=42`).\n\n✅ Exemple de structure HTML :\n```html\n<title>Formation comptabilité OHADA à Douala | Estuaire</title>\n<meta name=\"description\" content=\"Apprenez la comptabilité OHADA à Douala : cours pratiques, certificat reconnu. Inscriptions ouvertes.\">\n<h1>Formation en comptabilité OHADA à Douala</h1>\n<h2>Programme détaillé</h2>\n```\n\n⚠️ Une seule balise `<h1>` par page, et ne jamais bourrer de mots-clés (keyword stuffing) : Google le pénalise.",
                    'questions' => [
                        ['question' => 'Combien de balises <h1> doit idéalement contenir une page web ?', 'options' => ['Aucune', 'Une seule', 'Au moins trois', 'Autant que possible'], 'correct' => [1], 'explanation' => 'Une page doit comporter un seul <h1> qui décrit son sujet principal.'],
                        ['question' => 'À quoi sert principalement la meta description ?', 'options' => ['À améliorer directement le classement', 'À inciter au clic dans les résultats (CTR)', 'À accélérer le site', 'À créer des backlinks'], 'correct' => [1], 'explanation' => 'La meta description n\'est pas un facteur de classement direct mais améliore le taux de clic.'],
                        ['question' => 'Quelle URL est la plus optimisée pour le SEO ?', 'options' => ['/page?id=42&ref=99', '/formation-comptabilite-douala', '/p/x12z9', '/index.php?cat=3'], 'correct' => [1], 'explanation' => 'Une URL courte, lisible et contenant le mot-clé est préférable pour le SEO et l\'utilisateur.'],
                    ],
                ],
                [
                    'title' => 'Niveau 4 — Contenu optimisé et structure',
                    'subtitle' => 'Écrire pour l\'internaute et pour Google',
                    'xp_reward' => 130,
                    'content' => "🎯 **Objectif** : produire un contenu qui répond à l'intention et plaît à Google.\n\n💡 Bonnes pratiques rédactionnelles :\n• **Répondre à l'intention** : si la requête est « comment faire un CV », donne un guide étape par étape.\n• **Mot-clé principal** placé naturellement dans le title, le `<h1>`, le premier paragraphe et quelques sous-titres.\n• **Champ sémantique** : utiliser des termes liés (cooccurrences). Pour « CV » : compétences, expérience, recruteur, candidature.\n• **Lisibilité** : phrases courtes, paragraphes aérés, listes à puces, gras sur les points clés.\n• **Contenu unique** : jamais de copier-coller (duplicate content), Google déclasse les copies.\n\n✅ Le **maillage interne** = relier ses pages entre elles par des liens (ex : depuis l'article « CV » vers « lettre de motivation »). Cela aide Googlebot à naviguer et répartit l'autorité.\n\n⚠️ La longueur n'est pas une fin en soi : un contenu de 2000 mots vide vaut moins qu'un contenu de 600 mots qui répond parfaitement à la question.",
                    'questions' => [
                        ['question' => 'Qu\'est-ce que le maillage interne ?', 'options' => ['Des liens depuis d\'autres sites', 'Des liens entre les pages d\'un même site', 'Une balise meta', 'Un type de publicité'], 'correct' => [1], 'explanation' => 'Le maillage interne relie les pages d\'un même site, facilitant la navigation et la diffusion de l\'autorité.'],
                        ['question' => 'Pourquoi éviter le contenu dupliqué (duplicate content) ?', 'options' => ['Cela ralentit le serveur', 'Google peut déclasser les pages copiées', 'Cela augmente le CTR', 'Cela crée des backlinks'], 'correct' => [1], 'explanation' => 'Le contenu dupliqué est mal vu par Google qui privilégie un contenu original et unique.'],
                        ['question' => 'Quel est le critère le plus important pour un bon contenu SEO ?', 'options' => ['Atteindre 3000 mots minimum', 'Répondre à l\'intention de recherche de l\'utilisateur', 'Répéter le mot-clé 50 fois', 'Utiliser beaucoup d\'images'], 'correct' => [1], 'explanation' => 'Avant tout, le contenu doit répondre précisément à l\'intention de l\'internaute.'],
                    ],
                ],
                [
                    'title' => 'Niveau 5 — SEO technique : vitesse et mobile',
                    'subtitle' => 'Core Web Vitals et expérience utilisateur',
                    'xp_reward' => 140,
                    'content' => "🎯 **Objectif** : garantir un site rapide et adapté au mobile.\n\n💡 En Afrique, l'essentiel du trafic vient du **mobile** souvent sur des connexions 3G/4G limitées. Google applique le **mobile-first indexing** : il indexe la version mobile en priorité.\n\n✅ Les **Core Web Vitals** mesurent l'expérience :\n• **LCP** (Largest Contentful Paint) : temps d'affichage du contenu principal (< 2,5 s).\n• **CLS** (Cumulative Layout Shift) : stabilité visuelle (le contenu ne doit pas « sauter »).\n• **INP** (Interaction to Next Paint) : réactivité aux clics.\n\nOptimisations de vitesse :\n• Compresser les images (WebP) et activer le **lazy loading**.\n• Activer la mise en cache et la compression Gzip.\n• Minifier le CSS/JS et limiter les scripts externes.\n\n```html\n<img src=\"photo.webp\" loading=\"lazy\" alt=\"Bureau à Douala\">\n```\n\n⚠️ Un site qui charge en 8 secondes sur mobile perd la majorité de ses visiteurs avant même l'affichage. Outil : PageSpeed Insights.",
                    'questions' => [
                        ['question' => 'Que signifie le « mobile-first indexing » de Google ?', 'options' => ['Google n\'indexe que les apps mobiles', 'Google indexe en priorité la version mobile du site', 'Le mobile est interdit', 'Seul le desktop compte'], 'correct' => [1], 'explanation' => 'Google indexe et évalue d\'abord la version mobile des sites.'],
                        ['question' => 'Le LCP (Largest Contentful Paint) devrait idéalement rester sous :', 'options' => ['10 secondes', '2,5 secondes', '30 secondes', '1 minute'], 'correct' => [1], 'explanation' => 'Un bon LCP est inférieur à 2,5 secondes pour une expérience fluide.'],
                        ['question' => 'Quelles techniques améliorent la vitesse d\'un site ? (plusieurs réponses)', 'options' => ['Compresser les images', 'Activer la mise en cache', 'Ajouter de nombreux scripts publicitaires', 'Minifier le CSS et le JS'], 'correct' => [0, 1, 3], 'explanation' => 'Compression d\'images, cache et minification accélèrent le site ; les scripts excessifs le ralentissent.'],
                    ],
                ],
                [
                    'title' => 'Niveau 6 — Indexation et fichiers techniques',
                    'subtitle' => 'robots.txt, sitemap, balise canonical',
                    'xp_reward' => 150,
                    'content' => "🎯 **Objectif** : contrôler ce que Google explore et indexe.\n\n💡 Fichiers et balises de pilotage :\n• **robots.txt** : fichier à la racine qui indique aux robots ce qu'ils peuvent ou non explorer.\n• **sitemap.xml** : liste de toutes les URL importantes à indexer, soumise via Search Console.\n• **balise canonical** : indique l'URL de référence quand plusieurs pages se ressemblent (évite le duplicate content).\n• **meta robots noindex** : empêche l'indexation d'une page précise (ex : page de remerciement).\n\n✅ Exemples :\n```txt\n# robots.txt\nUser-agent: *\nDisallow: /admin/\nSitemap: https://monsite.cm/sitemap.xml\n```\n```html\n<link rel=\"canonical\" href=\"https://monsite.cm/formation\">\n<meta name=\"robots\" content=\"noindex\">\n```\n\n⚠️ Erreur classique : bloquer accidentellement tout le site avec `Disallow: /` ou laisser un `noindex` en production. Résultat : disparition totale de Google. Vérifie toujours dans Search Console.",
                    'questions' => [
                        ['question' => 'À quoi sert le fichier robots.txt ?', 'options' => ['À styliser le site', 'À indiquer aux robots ce qu\'ils peuvent explorer', 'À créer des backlinks', 'À compresser les images'], 'correct' => [1], 'explanation' => 'Le robots.txt donne des directives d\'exploration aux robots des moteurs.'],
                        ['question' => 'Quelle balise permet d\'éviter le contenu dupliqué entre pages similaires ?', 'options' => ['La balise canonical', 'La balise title', 'La balise alt', 'La balise viewport'], 'correct' => [0], 'explanation' => 'La balise canonical désigne l\'URL de référence et évite les problèmes de duplication.'],
                        ['question' => 'Quelle erreur peut rendre un site totalement invisible sur Google ?', 'options' => ['Trop d\'images', 'Un Disallow: / ou un noindex laissé en production', 'Une meta description trop courte', 'Trop de liens internes'], 'correct' => [1], 'explanation' => 'Bloquer tout le site dans robots.txt ou laisser un noindex empêche l\'indexation complète.'],
                    ],
                ],
                [
                    'title' => 'Niveau 7 — Netlinking et autorité',
                    'subtitle' => 'Backlinks, ancres et PageRank',
                    'xp_reward' => 160,
                    'content' => "🎯 **Objectif** : comprendre comment les liens construisent l'autorité.\n\n💡 Un **backlink** (lien entrant) est un lien d'un autre site vers le vôtre. Google le considère comme un **vote de confiance** : plus vous recevez de liens de sites fiables, plus votre autorité monte (principe du PageRank).\n\n✅ Ce qui compte :\n• **Qualité > quantité** : un lien d'un grand média camerounais vaut mieux que 100 liens d'annuaires douteux.\n• **Pertinence thématique** : un lien d'un site emploi vers un site emploi est plus pertinent.\n• **Texte d'ancre** : le texte cliquable doit être naturel et varié, pas toujours le mot-clé exact.\n• **Liens dofollow** (transmettent l'autorité) vs **nofollow** (n'en transmettent pas).\n\nTechniques saines : créer du contenu remarquable, le guest blogging, les relations presse, les partenariats locaux.\n\n⚠️ Le **Black Hat** (achat massif de liens, fermes de liens, PBN) expose à des pénalités Google (Penguin) pouvant faire chuter tout le site. Privilégie un netlinking naturel et progressif.",
                    'questions' => [
                        ['question' => 'Qu\'est-ce qu\'un backlink ?', 'options' => ['Un lien interne au site', 'Un lien d\'un autre site pointant vers le vôtre', 'Une balise meta', 'Une image optimisée'], 'correct' => [1], 'explanation' => 'Un backlink est un lien entrant provenant d\'un autre site, perçu comme un vote de confiance.'],
                        ['question' => 'Quelle affirmation est correcte concernant les backlinks ?', 'options' => ['La quantité prime toujours sur la qualité', 'Un lien de site fiable et pertinent vaut mieux que beaucoup de liens douteux', 'Tous les liens se valent', 'Les liens n\'influencent pas le SEO'], 'correct' => [1], 'explanation' => 'En netlinking, la qualité et la pertinence d\'un lien comptent davantage que le volume.'],
                        ['question' => 'Quel risque comportent les techniques Black Hat comme l\'achat massif de liens ?', 'options' => ['Accélérer le site', 'Subir une pénalité Google (Penguin)', 'Améliorer la vitesse', 'Garantir la première place'], 'correct' => [1], 'explanation' => 'Les pratiques Black Hat exposent à des pénalités qui peuvent effondrer le classement du site.'],
                    ],
                ],
                [
                    'title' => 'Niveau 8 — E-E-A-T et qualité du contenu',
                    'subtitle' => 'Expérience, Expertise, Autorité, Fiabilité',
                    'xp_reward' => 170,
                    'content' => "🎯 **Objectif** : démontrer la crédibilité du contenu et de l'auteur.\n\n💡 **E-E-A-T** est un cadre d'évaluation de la qualité utilisé par Google :\n• **Experience** : l'auteur a-t-il une expérience concrète du sujet (ex : un comptable qui écrit sur la fiscalité) ?\n• **Expertise** : compétence et savoir réels sur le domaine.\n• **Authoritativeness** : reconnaissance par d'autres sources (citations, backlinks, mentions).\n• **Trustworthiness** : fiabilité du site (HTTPS, mentions légales, coordonnées, avis).\n\n✅ Ce cadre est crucial pour les sujets **YMYL** (Your Money Your Life) : santé, finance, droit, emploi, où une mauvaise information peut nuire.\n\nComment renforcer l'E-E-A-T :\n• Signer les articles avec une bio d'auteur crédible.\n• Citer des sources fiables et récentes.\n• Afficher des avis clients, des certifications, une page « À propos » détaillée.\n• Sécuriser le site en HTTPS et publier des mentions légales claires.\n\n⚠️ Un contenu généré sans expertise, sans source et sans transparence inspire peu confiance à Google comme aux utilisateurs.",
                    'questions' => [
                        ['question' => 'Que représentent les lettres E-E-A-T ?', 'options' => ['Experience, Expertise, Authoritativeness, Trustworthiness', 'Email, Edition, Analyse, Trafic', 'Engagement, Énergie, Action, Temps', 'Erreur, Essai, Avis, Test'], 'correct' => [0], 'explanation' => 'E-E-A-T signifie Experience, Expertise, Authoritativeness et Trustworthiness.'],
                        ['question' => 'Pour quels types de sujets l\'E-E-A-T est-il particulièrement critique ?', 'options' => ['Les jeux vidéo uniquement', 'Les sujets YMYL (santé, finance, droit, emploi)', 'Les recettes de cuisine seulement', 'Aucun en particulier'], 'correct' => [1], 'explanation' => 'L\'E-E-A-T est essentiel sur les sujets YMYL où une mauvaise info peut nuire à l\'utilisateur.'],
                        ['question' => 'Quels éléments renforcent la fiabilité (Trust) d\'un site ? (plusieurs réponses)', 'options' => ['Le HTTPS', 'Des mentions légales et coordonnées claires', 'Des avis clients vérifiables', 'Masquer l\'identité de l\'auteur'], 'correct' => [0, 1, 2], 'explanation' => 'HTTPS, transparence légale et avis clients renforcent la confiance ; cacher l\'auteur la diminue.'],
                    ],
                ],
                [
                    'title' => 'Niveau 9 — SEO local et Google Business Profile',
                    'subtitle' => 'Être visible dans sa ville',
                    'xp_reward' => 185,
                    'content' => "🎯 **Objectif** : capter les recherches géolocalisées (« près de moi »).\n\n💡 Le **SEO local** vise les recherches avec une intention de proximité : « restaurant Douala », « école informatique Yaoundé », « plombier près de moi ». Google affiche alors le **Local Pack** (carte + 3 fiches) au-dessus des résultats classiques.\n\n✅ Pilier n°1 : la fiche **Google Business Profile** (ex-Google My Business). À optimiser :\n• Nom, adresse, téléphone (le fameux **NAP**) cohérents partout sur le web.\n• Catégorie d'activité précise, horaires, photos réelles.\n• Récolter des **avis clients** positifs et y répondre.\n\nAutres leviers :\n• Mots-clés géolocalisés dans les titles et le contenu (« comptable agréé à Douala-Bonanjo »).\n• Inscription dans des annuaires locaux fiables.\n• Données structurées **LocalBusiness** (Schema.org).\n\n⚠️ Des informations NAP incohérentes (adresse différente selon les sites) brouillent Google et nuisent au classement local. La cohérence est la clé.",
                    'questions' => [
                        ['question' => 'Que désigne l\'acronyme NAP en SEO local ?', 'options' => ['Name, Address, Phone (Nom, Adresse, Téléphone)', 'New Ad Platform', 'Network Access Point', 'Native App Page'], 'correct' => [0], 'explanation' => 'NAP correspond au Nom, à l\'Adresse et au Téléphone qui doivent rester cohérents partout.'],
                        ['question' => 'Quel outil est central pour le référencement local ?', 'options' => ['Google Business Profile', 'Photoshop', 'Excel', 'WhatsApp'], 'correct' => [0], 'explanation' => 'La fiche Google Business Profile est le pilier de la visibilité locale dans le Local Pack.'],
                        ['question' => 'Pourquoi la cohérence des informations NAP est-elle importante ?', 'options' => ['Pour accélérer le site', 'Pour éviter de brouiller Google et améliorer le classement local', 'Pour créer des backlinks payants', 'Cela n\'a aucune importance'], 'correct' => [1], 'explanation' => 'Des informations cohérentes renforcent la confiance de Google et améliorent le positionnement local.'],
                    ],
                ],
                [
                    'title' => 'Niveau 10 — Suivi, mesure et erreurs à éviter',
                    'subtitle' => 'Search Console, Analytics et pièges courants',
                    'xp_reward' => 200,
                    'content' => "🎯 **Objectif** : mesurer ses résultats et piloter sa stratégie dans la durée.\n\n💡 Deux outils gratuits indispensables :\n• **Google Search Console** : montre les requêtes qui amènent du trafic, les impressions, le CTR, la position moyenne, les erreurs d'indexation et l'état mobile.\n• **Google Analytics (GA4)** : analyse le comportement des visiteurs (pages vues, durée, conversions, sources de trafic).\n\n✅ Indicateurs (KPI) à suivre : positions des mots-clés, trafic organique, taux de clic, pages indexées, taux de conversion.\n\n⚠️ Erreurs classiques à éviter :\n• Keyword stuffing (bourrage de mots-clés) et contenu dupliqué.\n• Ignorer le mobile et la vitesse.\n• Acheter des backlinks douteux.\n• Oublier la balise title ou laisser un noindex.\n• Attendre des résultats en 1 semaine : le SEO prend 3 à 6 mois.\n\n🏆 **Félicitations !** Tu maîtrises désormais les fondamentaux du SEO : exploration, mots-clés, on-page, technique, netlinking, E-E-A-T, local et mesure. Débouchés : **consultant SEO**, **rédacteur web SEO**, **traffic manager**, **chef de projet marketing digital** ou freelance. Au Cameroun comme ailleurs, les entreprises s'arrachent ces compétences pour gagner en visibilité sans budget publicitaire. Continue à pratiquer sur un vrai site et à suivre les évolutions de l'algorithme. À toi de jouer ! 🚀",
                    'questions' => [
                        ['question' => 'Quel outil gratuit affiche les requêtes Google qui amènent du trafic et les erreurs d\'indexation ?', 'options' => ['Google Search Console', 'Google Ads', 'Canva', 'Mailchimp'], 'correct' => [0], 'explanation' => 'Google Search Console fournit les données de performance et d\'indexation directement issues de Google.'],
                        ['question' => 'En combien de temps obtient-on généralement des résultats SEO significatifs ?', 'options' => ['En 24 heures', 'En 1 semaine', 'En 3 à 6 mois en moyenne', 'Jamais'], 'correct' => [2], 'explanation' => 'Le SEO est un travail de fond dont les résultats apparaissent généralement après 3 à 6 mois.'],
                        ['question' => 'Quelles pratiques sont des erreurs SEO à éviter ? (plusieurs réponses)', 'options' => ['Le bourrage de mots-clés', 'Ignorer la version mobile', 'Acheter des backlinks douteux', 'Suivre ses KPI dans Search Console'], 'correct' => [0, 1, 2], 'explanation' => 'Keyword stuffing, négligence du mobile et achat de liens sont des erreurs ; suivre ses KPI est une bonne pratique.'],
                    ],
                ],
            ],
        ]);

        $this->command->info('  ✓ Roadmap SEO créée (10 niveaux).');
    }
}
