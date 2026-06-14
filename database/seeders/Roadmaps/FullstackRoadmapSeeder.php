<?php

namespace Database\Seeders\Roadmaps;

use Illuminate\Database\Seeder;

/**
 * Roadmap Fullstack — du frontend au backend, jusqu'au déploiement complet.
 * Contenu rédigé (tips), QCM de validation par niveau.
 */
class FullstackRoadmapSeeder extends Seeder
{
    use RoadmapSeederHelper;

    public function run(): void
    {
        $this->createRoadmap([
            'title' => 'Développeur Fullstack : le parcours complet',
            'slug' => 'devenir-developpeur-fullstack',
            'domain' => 'developpement',
            'description' => "Apprends à construire une application web complète, du frontend au backend. Architecture, API, authentification de bout en bout, base de données, temps réel, tests et déploiement : la chaîne entière d'un produit web moderne.",
            'objectives' => "Comprendre le rôle d'un développeur fullstack\nMaîtriser l'architecture d'une app moderne (SPA + API)\nConcevoir une API qui sert un frontend\nConnecter frontend et backend proprement\nGérer l'authentification de bout en bout\nModéliser une base de données complète\nAjouter du temps réel\nTester et déployer une application en production",
            'icon' => '🧩',
            'color' => '#8B5CF6',
            'difficulty' => 'advanced',
            'required_packs' => [],
            'pass_threshold' => 70,
            'order' => 4,
            'levels' => [
                [
                    'title' => 'Niveau 1 — Qu\'est-ce qu\'un développeur fullstack ?',
                    'subtitle' => 'Vue d\'ensemble front + back',
                    'xp_reward' => 120,
                    'content' => "🎯 **Objectif** : comprendre ce que recouvre le métier de fullstack.\n\n💡 Un développeur **fullstack** travaille sur les deux faces d'une application :\n• le **frontend** (la partie visible : interface, boutons, formulaires, exécutée dans le navigateur),\n• le **backend** (la logique serveur : règles métier, sécurité, accès aux données).\n\n✅ La **base de données** complète le tableau : elle stocke durablement les informations (utilisateurs, commandes, messages). Le backend est l'intermédiaire entre le frontend et la base.\n\n💡 Schématiquement, une requête traverse toute la pile :\n```\nNavigateur (Frontend)\n      ↓ requête HTTP\nServeur (Backend / API)\n      ↓ requête SQL\nBase de données\n```\n\n⚠️ Être fullstack ne veut pas dire tout faire à l'identique : on a souvent une dominante (front ou back). L'atout, c'est de **comprendre la chaîne entière** pour faire des choix cohérents et dialoguer avec toute l'équipe.",
                    'questions' => [
                        [
                            'question' => 'Que désigne le « frontend » d\'une application web ?',
                            'options' => ['La base de données', 'La partie exécutée dans le navigateur (interface utilisateur)', 'Le serveur de production', 'Le pare-feu réseau'],
                            'correct' => [1],
                            'explanation' => 'Le frontend est l\'interface visible, exécutée côté navigateur.',
                        ],
                        [
                            'question' => 'Quel rôle joue le backend vis-à-vis de la base de données ?',
                            'options' => ['Aucun, le frontend parle directement à la base', 'Il sert d\'intermédiaire entre le frontend et la base', 'Il remplace la base de données', 'Il ne fait qu\'afficher les pages HTML'],
                            'correct' => [1],
                            'explanation' => 'Le backend reçoit les requêtes du front et accède à la base en son nom.',
                        ],
                        [
                            'question' => 'Parmi ces éléments, lesquels font partie d\'une application fullstack ? (plusieurs réponses)',
                            'options' => ['Le frontend', 'Le backend', 'La base de données', 'Le code source du système d\'exploitation'],
                            'correct' => [0, 1, 2],
                            'explanation' => 'Frontend, backend et base de données composent la pile fullstack ; l\'OS n\'en fait pas partie.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 2 — Architecture d\'une app web moderne',
                    'subtitle' => 'SPA + API',
                    'xp_reward' => 140,
                    'content' => "🎯 **Objectif** : comprendre l'architecture SPA + API qui domine aujourd'hui.\n\n💡 Une **SPA** (Single Page Application) charge une seule page HTML, puis met à jour le contenu en JavaScript sans recharger toute la page. Le serveur ne renvoie plus du HTML complet à chaque clic, mais des **données** (souvent en JSON) via une **API**.\n\n✅ On sépare donc clairement deux briques :\n• le **client** (React, Vue, Flutter…) gère l'affichage et l'interaction,\n• l'**API** (le backend) expose des données et de la logique.\n\n```\n[Client SPA]  ⇄  JSON via HTTP  ⇄  [API Backend]  ⇄  [Base]\n```\n\n💡 Avantages : un même backend peut servir plusieurs clients (web, mobile, partenaires). Le front et le back évoluent indépendamment.\n\n⚠️ Inconvénients à connaître : le **SEO** est plus délicat (le contenu arrive en JS), et le premier chargement peut être plus lourd. Des techniques comme le **SSR** (Server-Side Rendering) répondent à ces limites.",
                    'questions' => [
                        [
                            'question' => 'Qu\'est-ce qui caractérise une SPA (Single Page Application) ?',
                            'options' => ['Le serveur renvoie une nouvelle page HTML à chaque clic', 'Une seule page est chargée, puis mise à jour en JavaScript', 'Elle ne fonctionne pas sans rechargement complet', 'Elle n\'utilise jamais d\'API'],
                            'correct' => [1],
                            'explanation' => 'Une SPA charge une page unique et actualise le contenu côté client sans rechargement complet.',
                        ],
                        [
                            'question' => 'Dans une architecture SPA + API, que renvoie généralement le backend au client ?',
                            'options' => ['Des pages HTML complètes', 'Des données structurées (souvent en JSON)', 'Des fichiers exécutables', 'Rien, tout est calculé côté client'],
                            'correct' => [1],
                            'explanation' => 'L\'API renvoie des données (JSON) que le client transforme en interface.',
                        ],
                        [
                            'question' => 'Quels sont des avantages de séparer client SPA et API ? (plusieurs réponses)',
                            'options' => ['Un même backend peut servir web et mobile', 'Front et back évoluent indépendamment', 'Le SEO devient automatiquement parfait', 'On peut réutiliser l\'API pour des partenaires'],
                            'correct' => [0, 1, 3],
                            'explanation' => 'La réutilisabilité et l\'indépendance sont des avantages ; le SEO est au contraire plus délicat avec une SPA.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 3 — Concevoir une API qui sert un frontend',
                    'subtitle' => 'Penser REST et contrats',
                    'xp_reward' => 160,
                    'content' => "🎯 **Objectif** : concevoir une API claire que le frontend pourra consommer facilement.\n\n💡 Une API **REST** organise les données en **ressources** (ex. `users`, `jobs`) manipulées par des **verbes HTTP** :\n• `GET /jobs` → lister,\n• `GET /jobs/42` → un élément,\n• `POST /jobs` → créer,\n• `PUT/PATCH /jobs/42` → modifier,\n• `DELETE /jobs/42` → supprimer.\n\n✅ Chaque réponse renvoie un **code de statut** parlant : `200 OK`, `201 Created`, `400 Bad Request`, `401 Unauthorized`, `404 Not Found`, `500 Server Error`. Le frontend s'appuie dessus pour réagir.\n\n✅ Exemple de réponse JSON cohérente :\n```\n{\n  \"data\": { \"id\": 42, \"title\": \"Développeur\", \"city\": \"Douala\" },\n  \"message\": \"OK\"\n}\n```\n\n💡 Bonnes pratiques côté contrat :\n• nommer les ressources au **pluriel** et en minuscules,\n• **paginer** les listes longues (`?page=2&per_page=20`),\n• **versionner** l'API (`/api/v1/...`) pour la faire évoluer sans casser les clients existants.",
                    'questions' => [
                        [
                            'question' => 'Quel verbe HTTP utilise-t-on pour créer une nouvelle ressource en REST ?',
                            'options' => ['GET', 'POST', 'DELETE', 'HEAD'],
                            'correct' => [1],
                            'explanation' => '`POST` sert à créer une ressource ; il renvoie souvent un `201 Created`.',
                        ],
                        [
                            'question' => 'Quel code de statut signale qu\'une ressource demandée n\'existe pas ?',
                            'options' => ['200', '301', '404', '500'],
                            'correct' => [2],
                            'explanation' => '`404 Not Found` indique que la ressource est introuvable.',
                        ],
                        [
                            'question' => 'Quelles sont de bonnes pratiques de conception d\'API ? (plusieurs réponses)',
                            'options' => ['Versionner l\'API (/api/v1)', 'Paginer les listes longues', 'Renvoyer toujours le code 200 même en cas d\'erreur', 'Nommer les ressources au pluriel'],
                            'correct' => [0, 1, 3],
                            'explanation' => 'Versionnement, pagination et nommage cohérent sont recommandés ; renvoyer 200 sur une erreur masque les problèmes au client.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 4 — Connecter frontend ↔ backend',
                    'subtitle' => 'fetch, CORS et formats',
                    'xp_reward' => 160,
                    'content' => "🎯 **Objectif** : faire communiquer concrètement le client et l'API.\n\n✅ Côté navigateur, on appelle l'API avec `fetch` (ou `axios`) :\n```\nconst res = await fetch('/api/v1/jobs', {\n  method: 'POST',\n  headers: { 'Content-Type': 'application/json' },\n  body: JSON.stringify({ title: 'Développeur', city: 'Douala' })\n});\nconst json = await res.json();\n```\n\n💡 Le header `Content-Type: application/json` indique au serveur le **format** envoyé. En réponse, on lit le statut (`res.ok`, `res.status`) avant d'exploiter les données.\n\n⚠️ **CORS** (Cross-Origin Resource Sharing) : par sécurité, un navigateur **bloque** par défaut les requêtes vers une autre origine (domaine/port différent). Si le front est sur `localhost:3000` et l'API sur `localhost:8000`, le **backend** doit autoriser explicitement l'origine via des en-têtes `Access-Control-Allow-Origin`.\n\n✅ À retenir : CORS se règle **côté serveur**, pas côté front. Et il faut toujours gérer les erreurs réseau et les statuts non-2xx, sinon l'interface plante silencieusement.",
                    'questions' => [
                        [
                            'question' => 'À quoi sert le header `Content-Type: application/json` dans une requête ?',
                            'options' => ['À chiffrer la requête', 'À indiquer au serveur le format des données envoyées', 'À authentifier l\'utilisateur', 'À activer la pagination'],
                            'correct' => [1],
                            'explanation' => 'Il signale que le corps de la requête est au format JSON.',
                        ],
                        [
                            'question' => 'Une erreur CORS apparaît. Où la corrige-t-on généralement ?',
                            'options' => ['Uniquement côté frontend', 'Côté serveur, en autorisant l\'origine du client', 'En désactivant JavaScript', 'En changeant de navigateur'],
                            'correct' => [1],
                            'explanation' => 'CORS se configure côté backend via les en-têtes Access-Control-Allow-Origin.',
                        ],
                        [
                            'question' => 'Pourquoi vérifier `res.ok` ou `res.status` après un fetch ?',
                            'options' => ['Pour accélérer la requête', 'Pour détecter les erreurs (4xx/5xx) avant d\'exploiter les données', 'Ce n\'est jamais nécessaire', 'Pour activer CORS'],
                            'correct' => [1],
                            'explanation' => '`fetch` ne lève pas d\'erreur sur un statut HTTP d\'échec : il faut le vérifier soi-même.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 5 — Authentification de bout en bout',
                    'subtitle' => 'Login, token et routes protégées',
                    'xp_reward' => 180,
                    'content' => "🎯 **Objectif** : authentifier un utilisateur du login jusqu'aux pages protégées du front.\n\n💡 Le flux classique avec **token** :\n1. l'utilisateur envoie email + mot de passe à `POST /login`,\n2. le backend vérifie les identifiants et renvoie un **token** (souvent un **JWT**),\n3. le front **stocke** ce token,\n4. à chaque requête suivante, le front l'envoie dans l'en-tête :\n```\nAuthorization: Bearer <token>\n```\n5. le backend valide le token et autorise (ou refuse avec `401`).\n\n✅ Côté frontend, on protège les **routes** : si aucun token valide, on redirige vers la page de connexion. C'est une protection d'**ergonomie**, pas de sécurité réelle.\n\n⚠️ **Règle d'or** : la vraie sécurité est **côté backend**. Le frontend est public et modifiable par l'utilisateur ; il faut donc **toujours revérifier** les droits côté serveur, même si le front a déjà filtré.\n\n💡 Stockage du token : `localStorage` est simple mais exposé au **XSS** ; un cookie `HttpOnly` est plus sûr contre le vol par script. À choisir selon le contexte.",
                    'questions' => [
                        [
                            'question' => 'Comment le frontend transmet-il généralement le token à chaque requête ?',
                            'options' => ['Dans l\'URL en clair', 'Dans l\'en-tête `Authorization: Bearer <token>`', 'Dans le nom du fichier', 'Il ne le transmet jamais'],
                            'correct' => [1],
                            'explanation' => 'Le token est envoyé dans l\'en-tête Authorization au format Bearer.',
                        ],
                        [
                            'question' => 'Pourquoi protéger une route côté frontend ne suffit-il pas ?',
                            'options' => ['Parce que le frontend est public et modifiable, la vraie vérification doit être côté backend', 'Parce que le frontend est trop lent', 'Parce que CORS l\'interdit', 'Parce que le token expire trop vite'],
                            'correct' => [0],
                            'explanation' => 'La protection front améliore l\'UX, mais seul le backend garantit réellement la sécurité.',
                        ],
                        [
                            'question' => 'Quelles affirmations sur le stockage d\'un token côté front sont vraies ? (plusieurs réponses)',
                            'options' => ['localStorage est simple mais vulnérable au XSS', 'Un cookie HttpOnly protège mieux contre le vol par script', 'Le token doit être en clair dans l\'URL', 'Le serveur doit valider le token à chaque requête'],
                            'correct' => [0, 1, 3],
                            'explanation' => 'localStorage est exposé au XSS, le cookie HttpOnly est plus sûr, et le backend doit toujours valider le token. Mettre le token dans l\'URL est dangereux.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 6 — Gestion d\'état et synchronisation',
                    'subtitle' => 'Garder le front cohérent avec le serveur',
                    'xp_reward' => 180,
                    'content' => "🎯 **Objectif** : gérer l'état partagé d'une app et le synchroniser avec le backend.\n\n💡 L'**état** (state) regroupe les données vivantes du front : utilisateur connecté, contenu d'un panier, liste affichée… On distingue :\n• l'**état local** (propre à un composant),\n• l'**état global/partagé** (visible par plusieurs écrans : profil, thème, panier).\n\n✅ Pour l'état partagé, on utilise un **store** (Redux, Provider/Riverpod, Pinia, Context…). Cela évite de faire « descendre » les données manuellement à travers toute l'arborescence des composants.\n\n💡 Distinction clé : l'état **serveur** (données venues de l'API) n'est pas de même nature que l'état **UI** (ex. une modale ouverte). Des outils comme **React Query / SWR** gèrent le cache, le rafraîchissement et l'invalidation des données serveur.\n\n⚠️ Le piège classique : afficher des données **périmées**. Après un `POST` ou `PUT`, il faut **invalider** le cache concerné ou re-fetcher, sinon l'utilisateur voit l'ancienne version.\n\n✅ Technique utile : la mise à jour **optimiste** — on met à jour l'interface immédiatement, puis on confirme avec le serveur, et on annule en cas d'échec. L'app paraît plus rapide.",
                    'questions' => [
                        [
                            'question' => 'À quoi sert un « store » (Redux, Provider, Pinia…) dans une app frontend ?',
                            'options' => ['À stocker des fichiers sur le serveur', 'À gérer un état partagé accessible par plusieurs composants', 'À remplacer la base de données', 'À configurer CORS'],
                            'correct' => [1],
                            'explanation' => 'Un store centralise l\'état partagé pour éviter de le faire transiter manuellement entre composants.',
                        ],
                        [
                            'question' => 'Après un POST qui modifie des données, pourquoi invalider/re-fetcher le cache ?',
                            'options' => ['Pour vider la base de données', 'Pour éviter d\'afficher des données périmées à l\'utilisateur', 'Pour désactiver le store', 'Ce n\'est jamais utile'],
                            'correct' => [1],
                            'explanation' => 'Sans invalidation, le front continue d\'afficher l\'ancienne version des données.',
                        ],
                        [
                            'question' => 'Qu\'est-ce qu\'une mise à jour « optimiste » ?',
                            'options' => ['Mettre à jour l\'UI immédiatement avant la confirmation serveur, puis annuler si échec', 'Attendre toujours la réponse serveur avant tout affichage', 'Désactiver la synchronisation', 'Recharger toute la page à chaque action'],
                            'correct' => [0],
                            'explanation' => 'La mise à jour optimiste anticipe le succès pour une UI plus réactive, avec rollback en cas d\'erreur.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 7 — Base de données et modélisation',
                    'subtitle' => 'Structurer les données d\'une app complète',
                    'xp_reward' => 200,
                    'content' => "🎯 **Objectif** : modéliser proprement les données d'une application fullstack.\n\n💡 On part des **entités** métier (User, Job, Application…) et de leurs **relations** :\n• **1‑N** : un employeur publie plusieurs offres,\n• **N‑N** : un candidat postule à plusieurs offres, une offre reçoit plusieurs candidats (via une table de liaison `applications`),\n• **1‑1** : un user a un seul profil détaillé.\n\n✅ Exemple de schéma simplifié :\n```\nusers(id, email, password, role)\njobs(id, title, city, employer_id → users.id)\napplications(id, user_id → users.id, job_id → jobs.id, status)\n```\n\n💡 La **clé étrangère** (`employer_id`) garantit l'intégrité : impossible de référencer un user inexistant. On y ajoute des **index** sur les colonnes filtrées/jointes pour la performance.\n\n✅ La **normalisation** évite de dupliquer l'information (une donnée = un seul endroit), ce qui prévient les incohérences. Mais une **dénormalisation** ciblée peut être justifiée pour accélérer des lectures fréquentes.\n\n⚠️ Côté backend, on manipule souvent ces tables via un **ORM** (Eloquent, Prisma, Sequelize) qui mappe les tables sur des objets. Pratique, mais attention au piège **N+1** : charger une liste puis 100 requêtes pour ses relations — on l'évite avec l'**eager loading**.",
                    'questions' => [
                        [
                            'question' => 'Quel type de relation modélise « un candidat postule à plusieurs offres, une offre a plusieurs candidats » ?',
                            'options' => ['1‑1', '1‑N', 'N‑N (avec table de liaison)', 'Aucune relation'],
                            'correct' => [2],
                            'explanation' => 'C\'est une relation N‑N, implémentée via une table de liaison (ici applications).',
                        ],
                        [
                            'question' => 'À quoi sert une clé étrangère dans la modélisation ?',
                            'options' => ['À chiffrer une colonne', 'À garantir l\'intégrité référentielle entre deux tables', 'À trier les résultats', 'À créer une SPA'],
                            'correct' => [1],
                            'explanation' => 'La clé étrangère relie deux tables et empêche de référencer une ligne inexistante.',
                        ],
                        [
                            'question' => 'Qu\'est-ce que le problème « N+1 » avec un ORM ?',
                            'options' => ['Une erreur de syntaxe SQL', 'Charger une liste puis exécuter une requête supplémentaire par élément pour ses relations', 'Un problème de CORS', 'Une faille d\'authentification'],
                            'correct' => [1],
                            'explanation' => 'Le N+1 multiplie les requêtes ; l\'eager loading le résout en chargeant les relations en une fois.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 8 — Temps réel (WebSockets, notifications)',
                    'subtitle' => 'Pousser les données vers le client',
                    'xp_reward' => 200,
                    'content' => "🎯 **Objectif** : ajouter des fonctionnalités temps réel (chat, notifications, présence).\n\n💡 En HTTP classique, c'est toujours le **client** qui demande (requête → réponse). Pour un chat ou des notifications, on veut l'inverse : que le **serveur** pousse l'information dès qu'un événement survient.\n\n✅ Les **WebSockets** ouvrent une connexion **persistante et bidirectionnelle** entre client et serveur. Une fois établie, chacun peut envoyer un message à tout moment, sans rouvrir de connexion :\n```\nconst ws = new WebSocket('wss://api.exemple.com/ws');\nws.onmessage = (e) => console.log('Reçu :', e.data);\nws.send(JSON.stringify({ type: 'message', text: 'Salut' }));\n```\n\n💡 Alternatives selon le besoin :\n• **SSE** (Server-Sent Events) : flux **unidirectionnel** serveur → client, simple, idéal pour des notifications.\n• **Polling** : le client redemande à intervalle régulier — simple mais peu efficace.\n\n⚠️ Le temps réel ajoute de la complexité : reconnexion automatique en cas de coupure, montée en charge (un serveur a une limite de connexions ouvertes), et **scaling** avec un message broker (Redis, etc.) si plusieurs serveurs. À n'introduire que si le besoin le justifie vraiment.",
                    'questions' => [
                        [
                            'question' => 'Quelle est la principale différence d\'un WebSocket par rapport à une requête HTTP classique ?',
                            'options' => ['Il est plus lent', 'Il maintient une connexion persistante et bidirectionnelle', 'Il ne fonctionne qu\'en local', 'Il remplace la base de données'],
                            'correct' => [1],
                            'explanation' => 'Le WebSocket garde la connexion ouverte, permettant au serveur de pousser des données à tout moment.',
                        ],
                        [
                            'question' => 'Pour de simples notifications serveur → client (flux unidirectionnel), quelle techno est adaptée ?',
                            'options' => ['SSE (Server-Sent Events)', 'CORS', 'JWT', 'SQL'],
                            'correct' => [0],
                            'explanation' => 'SSE fournit un flux unidirectionnel serveur → client, plus simple qu\'un WebSocket pour des notifications.',
                        ],
                        [
                            'question' => 'Quels défis le temps réel introduit-il ? (plusieurs réponses)',
                            'options' => ['Gérer la reconnexion après une coupure', 'Tenir compte de la limite de connexions ouvertes', 'Coordonner plusieurs serveurs (scaling)', 'Supprimer la base de données'],
                            'correct' => [0, 1, 2],
                            'explanation' => 'Reconnexion, montée en charge et scaling multi-serveurs sont les vrais défis ; on ne supprime pas la base.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 9 — Tests end-to-end et qualité',
                    'subtitle' => 'Vérifier toute la chaîne',
                    'xp_reward' => 200,
                    'content' => "🎯 **Objectif** : garantir la fiabilité d'une app fullstack par les tests.\n\n💡 La **pyramide des tests** : beaucoup de tests **unitaires** (rapides, une fonction isolée), moins de tests **d'intégration** (plusieurs briques ensemble : API + base), et peu de tests **end-to-end** (E2E), lents mais réalistes.\n\n✅ Un test **E2E** simule un vrai utilisateur dans un navigateur : il remplit le formulaire de connexion, clique, et vérifie que la page protégée s'affiche. Des outils comme **Cypress** ou **Playwright** automatisent ce scénario complet (front + back + base).\n```\n// pseudo-test E2E\nvisit('/login');\nfill('#email', 'awa@test.cm');\nfill('#password', 'secret');\nclick('Se connecter');\nexpect(url).toContain('/dashboard');\n```\n\n💡 Côté backend, on teste les endpoints (statut, format de réponse, droits) ; côté frontend, on teste les composants et l'affichage. Le E2E relie tout.\n\n⚠️ Les E2E sont **précieux mais coûteux** : lents et parfois instables (« flaky »). On en réserve quelques-uns pour les **parcours critiques** (inscription, paiement, login) et on s'appuie surtout sur les tests unitaires/intégration. On automatise enfin l'ensemble en **CI** pour bloquer toute régression avant un merge.",
                    'questions' => [
                        [
                            'question' => 'Dans la pyramide des tests, lesquels doivent être les plus nombreux ?',
                            'options' => ['Les tests end-to-end', 'Les tests unitaires', 'Les tests manuels', 'Les tests de charge'],
                            'correct' => [1],
                            'explanation' => 'La base de la pyramide est constituée de nombreux tests unitaires, rapides et ciblés.',
                        ],
                        [
                            'question' => 'Que vérifie typiquement un test end-to-end (E2E) ?',
                            'options' => ['Une fonction isolée sans dépendance', 'Un parcours utilisateur complet à travers front + back + base', 'Uniquement la syntaxe du code', 'La vitesse du réseau'],
                            'correct' => [1],
                            'explanation' => 'Un test E2E simule un utilisateur réel sur tout le parcours, de l\'interface jusqu\'à la base.',
                        ],
                        [
                            'question' => 'Pourquoi limite-t-on le nombre de tests E2E ?',
                            'options' => ['Ils sont interdits en production', 'Ils sont lents et parfois instables (flaky)', 'Ils ne testent jamais rien d\'utile', 'Ils remplacent les tests unitaires'],
                            'correct' => [1],
                            'explanation' => 'Les E2E sont coûteux et fragiles : on les réserve aux parcours critiques.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 10 — Déploiement complet et CI/CD',
                    'subtitle' => 'Mettre toute l\'app en production',
                    'xp_reward' => 240,
                    'content' => "🎯 **Objectif** : déployer une application complète (front + back + base) et automatiser le processus.\n\n💡 Chaque brique a son mode de déploiement :\n• le **frontend** (SPA) se compile en fichiers statiques (HTML/JS/CSS) servis par un CDN ou un hébergeur statique,\n• le **backend** tourne sur un serveur ou un service géré (conteneur, PaaS),\n• la **base de données** est généralement un service managé, séparé et sauvegardé.\n\n✅ Les **variables d'environnement** séparent la configuration du code : URL de l'API, identifiants de base, clés secrètes. On ne **commit jamais** un secret dans le dépôt.\n```\nDB_HOST=...\nAPI_URL=https://api.exemple.com\nJWT_SECRET=...\n```\n\n💡 Le **CI/CD** automatise la chaîne :\n• **CI** (Continuous Integration) : à chaque push, on lance lint + tests automatiquement,\n• **CD** (Continuous Deployment/Delivery) : si tout est vert, on **build** et on **déploie** automatiquement.\n\n✅ Bonnes pratiques de prod : un environnement de **staging** proche de la prod pour tester avant, des **migrations** de base versionnées et rejouables, du **monitoring** (logs, erreurs, métriques) et une stratégie de **rollback** pour revenir en arrière en cas d'incident.\n\n🏆 Bravo ! Tu maîtrises désormais la chaîne fullstack complète, de l'idée à la production. Continue à pratiquer en livrant de vrais projets de bout en bout.",
                    'questions' => [
                        [
                            'question' => 'Comment déploie-t-on généralement le frontend d\'une SPA ?',
                            'options' => ['On le compile en fichiers statiques servis par un CDN/hébergeur statique', 'On l\'exécute directement dans la base de données', 'On ne déploie jamais le frontend', 'On le lance uniquement en local'],
                            'correct' => [0],
                            'explanation' => 'Une SPA se compile en fichiers statiques (HTML/JS/CSS) distribués via un CDN ou un hébergeur statique.',
                        ],
                        [
                            'question' => 'Pourquoi utiliser des variables d\'environnement plutôt qu\'écrire les secrets dans le code ?',
                            'options' => ['Pour accélérer l\'app', 'Pour séparer la configuration du code et ne pas exposer les secrets dans le dépôt', 'Parce que CORS l\'exige', 'Pour désactiver les tests'],
                            'correct' => [1],
                            'explanation' => 'Les variables d\'environnement gardent les secrets hors du code source et adaptent la config par environnement.',
                        ],
                        [
                            'question' => 'Que recouvrent CI et CD ? (plusieurs réponses)',
                            'options' => ['CI : lancer lint et tests automatiquement à chaque push', 'CD : build et déploiement automatiques quand tout est vert', 'CI : supprimer la base de données', 'CD : déployer manuellement sans aucun test'],
                            'correct' => [0, 1],
                            'explanation' => 'La CI automatise tests/lint à chaque push ; la CD automatise le build et le déploiement une fois les vérifications passées.',
                        ],
                    ],
                ],
            ],
        ]);

        $this->command->info('  ✓ Roadmap Fullstack créée (10 niveaux).');
    }
}
