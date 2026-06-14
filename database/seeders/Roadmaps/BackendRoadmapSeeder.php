<?php

namespace Database\Seeders\Roadmaps;

use Illuminate\Database\Seeder;

/**
 * Roadmap Backend — devenir développeur backend, des fondamentaux client/serveur
 * jusqu'au déploiement en production.
 * Contenu rédigé (tips), QCM de validation par niveau. Langage de référence : PHP.
 */
class BackendRoadmapSeeder extends Seeder
{
    use RoadmapSeederHelper;

    public function run(): void
    {
        $this->createRoadmap([
            'title' => 'Deviens Développeur Backend',
            'slug' => 'devenir-developpeur-backend',
            'domain' => 'developpement',
            'description' => "Apprends à construire la partie serveur d'une application web : du modèle client/serveur jusqu'aux API REST, à la sécurité, aux tests et au déploiement. Exemples en PHP, mais les concepts sont valables partout.",
            'objectives' => "Comprendre le modèle client/serveur et le protocole HTTP\nMaîtriser les bases d'un langage backend (PHP)\nProgrammer en orienté objet (POO)\nConstruire des API REST propres\nManipuler des bases de données via un ORM\nSécuriser l'authentification et les entrées utilisateur\nStructurer son code (MVC, services)\nOptimiser, tester et déployer une application",
            'icon' => '⚙️',
            'color' => '#10B981',
            'difficulty' => 'intermediate',
            'required_packs' => [],
            'pass_threshold' => 70,
            'order' => 3,
            'levels' => [
                [
                    'title' => 'Niveau 1 — Qu\'est-ce que le backend ?',
                    'subtitle' => 'Client, serveur, requête et réponse',
                    'xp_reward' => 100,
                    'content' => "🎯 **Objectif** : comprendre ce qui se passe \"derrière\" un site web.\n\n💡 Une application web a deux côtés :\n• le **frontend** (côté client) : ce que l'utilisateur voit dans son navigateur (HTML, CSS, JavaScript).\n• le **backend** (côté serveur) : la logique cachée qui traite les données, parle à la base de données et renvoie des résultats.\n\n✅ Le cycle **requête → réponse** :\n1. Le navigateur (le **client**) envoie une **requête** (\"donne-moi la page profil de l'utilisateur 42\").\n2. Le **serveur** reçoit cette requête, exécute du code backend, interroge la base de données.\n3. Le serveur renvoie une **réponse** (du HTML, ou du JSON pour une API).\n\n✅ Schéma mental :\n```\nClient (navigateur)  --- requête HTTP -->  Serveur (backend)\n      ^                                          |\n      |  <----- réponse HTTP (HTML/JSON) --------|\n```\n\n💡 Le backend est responsable de la **logique métier**, de la **persistance des données** et de la **sécurité**. Le frontend, lui, gère surtout l'affichage et l'interaction.",
                    'questions' => [
                        [
                            'question' => 'Quel rôle joue le backend dans une application web ?',
                            'options' => ['Afficher les couleurs et animations', 'Traiter la logique, les données et la sécurité côté serveur', 'Gérer uniquement le CSS', 'Stocker les fichiers sur le navigateur'],
                            'correct' => [1],
                            'explanation' => 'Le backend traite la logique métier, accède aux données et gère la sécurité côté serveur.',
                        ],
                        [
                            'question' => 'Dans le cycle requête/réponse, qui envoie la requête en premier ?',
                            'options' => ['Le serveur', 'La base de données', 'Le client (navigateur)', 'L\'ORM'],
                            'correct' => [2],
                            'explanation' => 'Le client initie toujours la communication en envoyant une requête au serveur.',
                        ],
                        [
                            'question' => 'Quelles tâches relèvent typiquement du backend ? (plusieurs réponses)',
                            'options' => ['Interroger la base de données', 'Appliquer une animation CSS au survol', 'Vérifier un mot de passe à la connexion', 'Choisir la police d\'écriture'],
                            'correct' => [0, 2],
                            'explanation' => 'L\'accès aux données et l\'authentification sont du ressort du backend ; le style et les animations sont du frontend.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 2 — Les bases d\'un langage backend (PHP)',
                    'subtitle' => 'Variables, fonctions, structures',
                    'xp_reward' => 120,
                    'content' => "🎯 **Objectif** : écrire ses premières lignes de code serveur en PHP.\n\n✅ Une **variable** stocke une valeur. En PHP elle commence par `\$` :\n```php\n\$nom = 'Awa';\n\$age = 27;\n\$actif = true;\n```\n\n✅ Une **fonction** regroupe un bloc de code réutilisable :\n```php\nfunction bonjour(\$nom) {\n    return \"Bonjour \$nom !\";\n}\necho bonjour('Steve'); // Bonjour Steve !\n```\n\n✅ Les **structures de contrôle** dirigent l'exécution :\n```php\nif (\$age >= 18) {\n    echo 'Majeur';\n} else {\n    echo 'Mineur';\n}\n\nforeach (['php', 'sql', 'http'] as \$sujet) {\n    echo \$sujet;\n}\n```\n\n💡 Un **tableau associatif** (clé → valeur) est omniprésent en backend pour représenter des données :\n```php\n\$user = ['id' => 42, 'nom' => 'Awa'];\necho \$user['nom']; // Awa\n```\n\n💡 Astuce : utilise toujours `===` (égalité stricte) plutôt que `==` pour éviter les conversions de type surprenantes.",
                    'questions' => [
                        [
                            'question' => 'Comment déclare-t-on une variable en PHP ?',
                            'options' => ['var nom = "Awa";', '$nom = "Awa";', 'let nom = "Awa";', 'nom := "Awa";'],
                            'correct' => [1],
                            'explanation' => 'En PHP, une variable commence par le symbole $.',
                        ],
                        [
                            'question' => 'Que renvoie `bonjour(\'Steve\')` si la fonction fait `return "Bonjour $nom !";` ?',
                            'options' => ['Bonjour $nom !', 'Bonjour Steve !', 'Steve', 'Une erreur'],
                            'correct' => [1],
                            'explanation' => 'Dans une chaîne entre guillemets doubles, la variable $nom est interpolée par sa valeur.',
                        ],
                        [
                            'question' => 'Pourquoi préférer `===` à `==` en PHP ?',
                            'options' => ['=== est plus rapide à taper', '=== compare la valeur ET le type, évitant les conversions surprenantes', '== est interdit', '=== ne fonctionne qu\'avec les nombres'],
                            'correct' => [1],
                            'explanation' => 'L\'égalité stricte === compare valeur et type, ce qui évite les conversions implicites trompeuses.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 3 — La programmation orientée objet',
                    'subtitle' => 'Classes, objets, encapsulation',
                    'xp_reward' => 150,
                    'content' => "🎯 **Objectif** : structurer son code avec des objets.\n\n💡 La **POO** organise le code autour d'**objets** qui regroupent des **données** (propriétés) et des **comportements** (méthodes). Une **classe** est le plan ; un **objet** est une instance concrète.\n\n✅ Définir une classe :\n```php\nclass Utilisateur {\n    public function __construct(\n        private string \$nom,\n        private string \$email\n    ) {}\n\n    public function getNom(): string {\n        return \$this->nom;\n    }\n}\n\n\$u = new Utilisateur('Awa', 'awa@mail.com');\necho \$u->getNom(); // Awa\n```\n\n✅ Les **piliers** de la POO :\n• **Encapsulation** : cacher les détails internes (`private`) et exposer une interface (`public`).\n• **Héritage** : une classe `Admin extends Utilisateur` réutilise et étend le comportement.\n• **Polymorphisme** : plusieurs classes répondent au même message différemment.\n• **Abstraction** : une `interface` définit un contrat sans implémentation.\n\n💡 En backend moderne, presque tout est objet : contrôleurs, modèles, services. Bien maîtriser la POO est indispensable pour les frameworks (Laravel, Symfony...).",
                    'questions' => [
                        [
                            'question' => 'Quelle est la différence entre une classe et un objet ?',
                            'options' => ['Aucune, ce sont des synonymes', 'La classe est le plan, l\'objet est une instance concrète', 'L\'objet est le plan, la classe est l\'instance', 'La classe ne contient que des fonctions'],
                            'correct' => [1],
                            'explanation' => 'La classe définit la structure (le plan) ; l\'objet est une instance créée à partir de ce plan.',
                        ],
                        [
                            'question' => 'À quoi sert le mot-clé `private` sur une propriété ?',
                            'options' => ['À la rendre plus rapide', 'À l\'encapsuler : elle n\'est accessible que depuis la classe', 'À la partager entre tous les objets', 'À la supprimer'],
                            'correct' => [1],
                            'explanation' => 'private encapsule la propriété : elle n\'est accessible qu\'à l\'intérieur de la classe.',
                        ],
                        [
                            'question' => 'Quels sont des piliers de la POO ? (plusieurs réponses)',
                            'options' => ['Héritage', 'Compilation', 'Encapsulation', 'Indexation'],
                            'correct' => [0, 2],
                            'explanation' => 'Héritage et encapsulation sont deux piliers de la POO (avec polymorphisme et abstraction).',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 4 — HTTP en profondeur',
                    'subtitle' => 'Méthodes, status codes, headers',
                    'xp_reward' => 150,
                    'content' => "🎯 **Objectif** : comprendre le protocole qui fait tourner le web.\n\n💡 **HTTP** est le langage entre client et serveur. Chaque requête a une **méthode**, une **URL**, des **headers** et parfois un **corps** (body).\n\n✅ Les **méthodes** principales (verbes) :\n• `GET` : lire une ressource (sans la modifier).\n• `POST` : créer une ressource.\n• `PUT` / `PATCH` : modifier (remplacer / modifier partiellement).\n• `DELETE` : supprimer.\n\n✅ Les **status codes** (codes de statut) de la réponse :\n• `2xx` succès : `200 OK`, `201 Created`, `204 No Content`.\n• `3xx` redirection : `301`, `302`.\n• `4xx` erreur du client : `400 Bad Request`, `401 Unauthorized`, `403 Forbidden`, `404 Not Found`, `422 Unprocessable Entity`.\n• `5xx` erreur du serveur : `500 Internal Server Error`, `503 Service Unavailable`.\n\n✅ Les **headers** transportent des métadonnées :\n```\nContent-Type: application/json\nAuthorization: Bearer eyJhbGc...\nAccept: application/json\n```\n\n💡 HTTP est **sans état** (stateless) : chaque requête est indépendante, le serveur ne \"se souvient\" pas de la précédente. C'est pourquoi on a besoin de sessions ou de tokens (niveau 7) pour savoir qui est connecté.",
                    'questions' => [
                        [
                            'question' => 'Quelle méthode HTTP utilise-t-on pour LIRE une ressource sans la modifier ?',
                            'options' => ['POST', 'GET', 'DELETE', 'PUT'],
                            'correct' => [1],
                            'explanation' => 'GET sert à récupérer des données sans effet de bord sur la ressource.',
                        ],
                        [
                            'question' => 'Que signifie un status code de la famille 4xx ?',
                            'options' => ['Succès', 'Redirection', 'Erreur côté client', 'Erreur côté serveur'],
                            'correct' => [2],
                            'explanation' => 'Les codes 4xx indiquent une erreur due à la requête du client (ex. 404 Not Found).',
                        ],
                        [
                            'question' => 'Que veut dire que HTTP est "sans état" (stateless) ?',
                            'options' => ['Le serveur retient tout entre les requêtes', 'Chaque requête est indépendante, le serveur ne mémorise pas la précédente', 'HTTP ne fonctionne pas hors ligne', 'Les requêtes n\'ont pas de headers'],
                            'correct' => [1],
                            'explanation' => 'Stateless : chaque requête est traitée isolément, d\'où le besoin de sessions ou tokens.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 5 — Les API REST',
                    'subtitle' => 'Ressources, verbes, JSON',
                    'xp_reward' => 180,
                    'content' => "🎯 **Objectif** : exposer des données via une API propre et prévisible.\n\n💡 Une **API REST** organise le backend autour de **ressources** (ex. `articles`, `users`), manipulées avec les **verbes HTTP**. Le format d'échange est généralement le **JSON**.\n\n✅ Convention REST pour la ressource `articles` :\n```\nGET    /articles        -> liste des articles\nGET    /articles/42     -> un article précis\nPOST   /articles        -> créer un article\nPUT    /articles/42     -> remplacer l'article 42\nDELETE /articles/42     -> supprimer l'article 42\n```\n\n✅ Réponse JSON typique :\n```json\n{\n  \"id\": 42,\n  \"titre\": \"Apprendre le backend\",\n  \"auteur\": \"Awa\"\n}\n```\n\n✅ Bonnes pratiques REST :\n• Utiliser des **noms au pluriel** pour les ressources (`/articles`, pas `/getArticles`).\n• Le **verbe** porte l'action, pas l'URL.\n• Renvoyer le bon **status code** (`201` à la création, `404` si introuvable).\n• Versionner l'API (`/api/v1/...`).\n\n💡 Une bonne API est **cohérente** et **prévisible** : un développeur qui connaît `/articles` devine immédiatement comment marche `/comments`.",
                    'questions' => [
                        [
                            'question' => 'Quelle URL respecte le mieux les conventions REST pour créer un article ?',
                            'options' => ['GET /createArticle', 'POST /articles', 'GET /articles/new', 'POST /makeArticle'],
                            'correct' => [1],
                            'explanation' => 'En REST, on POST sur la ressource au pluriel /articles pour créer ; le verbe porte l\'action.',
                        ],
                        [
                            'question' => 'Quel format d\'échange est le plus courant dans les API REST modernes ?',
                            'options' => ['XML', 'JSON', 'CSV', 'YAML'],
                            'correct' => [1],
                            'explanation' => 'Le JSON est le format d\'échange standard des API REST modernes.',
                        ],
                        [
                            'question' => 'Quel status code renvoyer après avoir créé avec succès une ressource ?',
                            'options' => ['200 OK', '201 Created', '204 No Content', '404 Not Found'],
                            'correct' => [1],
                            'explanation' => '201 Created indique qu\'une nouvelle ressource a été créée avec succès.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 6 — Bases de données & ORM',
                    'subtitle' => 'Du SQL aux requêtes via un ORM',
                    'xp_reward' => 180,
                    'content' => "🎯 **Objectif** : persister les données et les manipuler avec un ORM.\n\n💡 Le backend stocke ses données dans une **base de données** (souvent relationnelle : MySQL, PostgreSQL). On y parle en **SQL** (voir la roadmap SQL). Mais écrire du SQL brut partout est verbeux et risqué.\n\n💡 Un **ORM** (Object-Relational Mapping) fait correspondre une **table** à une **classe** et une **ligne** à un **objet**. On manipule des objets PHP, l'ORM génère le SQL.\n\n✅ Avec un ORM (style Eloquent / Laravel), lire devient simple :\n```php\n// SQL : SELECT * FROM articles WHERE auteur = 'Awa';\n\$articles = Article::where('auteur', 'Awa')->get();\n\n// Récupérer par id\n\$article = Article::find(42);\n\n// Créer\nArticle::create(['titre' => 'Backend', 'auteur' => 'Awa']);\n```\n\n✅ Les **relations** modélisent les liens entre tables :\n```php\nclass Article {\n    public function comments() {\n        return \$this->hasMany(Comment::class);\n    }\n}\n\$article->comments; // tous les commentaires de l'article\n```\n\n⚠️ Attention au problème **N+1** : charger une liste puis accéder à une relation dans une boucle déclenche une requête par élément. Utilise le **eager loading** (`with('comments')`) pour tout charger en une fois.",
                    'questions' => [
                        [
                            'question' => 'À quoi sert un ORM ?',
                            'options' => ['À chiffrer la base de données', 'À faire correspondre des tables à des classes et générer le SQL', 'À remplacer HTTP', 'À héberger le serveur'],
                            'correct' => [1],
                            'explanation' => 'Un ORM mappe tables/classes et lignes/objets, et génère le SQL à votre place.',
                        ],
                        [
                            'question' => 'Dans un ORM, à quoi correspond généralement une ligne de table ?',
                            'options' => ['Une classe', 'Une méthode', 'Un objet (une instance)', 'Une base de données'],
                            'correct' => [2],
                            'explanation' => 'Une table correspond à une classe et chaque ligne à une instance (objet) de cette classe.',
                        ],
                        [
                            'question' => 'Comment éviter le problème N+1 lors du chargement d\'une relation ?',
                            'options' => ['En ajoutant un index', 'En utilisant le eager loading (charger la relation à l\'avance)', 'En supprimant la relation', 'En passant en SQL brut obligatoire'],
                            'correct' => [1],
                            'explanation' => 'Le eager loading (ex. with(\'comments\')) charge la relation en une seule requête au lieu d\'une par élément.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 7 — Authentification & autorisation',
                    'subtitle' => 'Sessions, tokens/JWT, hashage',
                    'xp_reward' => 200,
                    'content' => "🎯 **Objectif** : savoir qui est l'utilisateur et ce qu'il a le droit de faire.\n\n💡 Deux notions à ne pas confondre :\n• **Authentification** : prouver QUI tu es (login).\n• **Autorisation** : ce que tu as le DROIT de faire (permissions/rôles).\n\n✅ **Ne jamais stocker un mot de passe en clair.** On le **hash** avec un algorithme lent et salé :\n```php\n// À l'inscription\n\$hash = password_hash(\$motDePasse, PASSWORD_BCRYPT);\n// stocke \$hash en base, jamais le mot de passe\n\n// À la connexion\nif (password_verify(\$saisie, \$hash)) {\n    // mot de passe correct\n}\n```\n\n✅ Comme HTTP est sans état, deux approches pour \"garder\" l'utilisateur connecté :\n• **Sessions** : le serveur garde l'état, le client renvoie un cookie de session à chaque requête.\n• **Tokens (JWT)** : le serveur émet un jeton signé à la connexion ; le client l'envoie dans le header `Authorization: Bearer <token>`. Le serveur n'a rien à stocker (stateless), idéal pour les API.\n\n⚠️ Ne mets jamais d'information sensible en clair dans un JWT : il est encodé (base64), pas chiffré. N'importe qui peut lire son contenu.",
                    'questions' => [
                        [
                            'question' => 'Quelle est la différence entre authentification et autorisation ?',
                            'options' => ['Aucune, c\'est pareil', 'L\'authentification prouve qui tu es ; l\'autorisation définit ce que tu as le droit de faire', 'L\'autorisation prouve qui tu es ; l\'authentification donne les droits', 'Les deux concernent uniquement le mot de passe'],
                            'correct' => [1],
                            'explanation' => 'Authentification = identité (qui es-tu) ; autorisation = permissions (qu\'as-tu le droit de faire).',
                        ],
                        [
                            'question' => 'Comment doit-on stocker un mot de passe en base de données ?',
                            'options' => ['En clair pour pouvoir le relire', 'Hashé avec un algorithme comme bcrypt', 'En base64', 'Dans un cookie'],
                            'correct' => [1],
                            'explanation' => 'On stocke uniquement un hash (bcrypt/argon2), jamais le mot de passe en clair ni en base64.',
                        ],
                        [
                            'question' => 'Quelles affirmations sur les JWT sont vraies ? (plusieurs réponses)',
                            'options' => ['Le client l\'envoie dans le header Authorization', 'Son contenu est chiffré et illisible', 'Il permet une authentification stateless côté serveur', 'Il remplace le protocole HTTP'],
                            'correct' => [0, 2],
                            'explanation' => 'Un JWT s\'envoie dans Authorization: Bearer et permet une authentification stateless ; en revanche son contenu est seulement encodé (base64), pas chiffré.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 8 — Validation & sécurité',
                    'subtitle' => 'Injection SQL, XSS, CSRF, validation',
                    'xp_reward' => 220,
                    'content' => "🎯 **Objectif** : ne jamais faire confiance aux données venant du client.\n\n💡 **Règle d'or** : toute donnée envoyée par l'utilisateur est potentiellement malveillante. On la **valide** et on la **nettoie** systématiquement.\n\n✅ **Injection SQL** : un attaquant insère du SQL dans un champ. ❌ Ne JAMAIS concaténer :\n```php\n// DANGER : \"... WHERE nom = '\" . \$input . \"'\"\n```\n✅ Utiliser des **requêtes préparées** (paramètres liés) :\n```php\n\$stmt = \$pdo->prepare('SELECT * FROM users WHERE nom = ?');\n\$stmt->execute([\$input]); // \$input est traité comme une valeur, pas du code\n```\n\n✅ **XSS** (Cross-Site Scripting) : du JavaScript injecté dans une page. Parade : **échapper** toute sortie HTML (`htmlspecialchars()`), ne jamais afficher du HTML utilisateur brut.\n\n✅ **CSRF** (Cross-Site Request Forgery) : un site tiers fait exécuter une action à l'insu de l'utilisateur connecté. Parade : un **token CSRF** unique vérifié sur les formulaires/requêtes sensibles.\n\n✅ **Validation des entrées** : vérifier type, format et longueur AVANT de traiter :\n```php\n\$request->validate([\n    'email' => 'required|email',\n    'age'   => 'required|integer|min:0|max:120',\n]);\n```\n\n💡 Défense en profondeur : valider côté serveur même si le frontend valide déjà. Le client peut toujours être contourné.",
                    'questions' => [
                        [
                            'question' => 'Comment se protège-t-on contre l\'injection SQL ?',
                            'options' => ['En concaténant les chaînes proprement', 'En utilisant des requêtes préparées avec paramètres liés', 'En mettant la base en lecture seule', 'En validant uniquement côté client'],
                            'correct' => [1],
                            'explanation' => 'Les requêtes préparées traitent l\'entrée comme une valeur, jamais comme du code SQL exécutable.',
                        ],
                        [
                            'question' => 'Quelle attaque consiste à injecter du JavaScript dans une page web ?',
                            'options' => ['CSRF', 'Injection SQL', 'XSS', 'DDoS'],
                            'correct' => [2],
                            'explanation' => 'XSS (Cross-Site Scripting) injecte du script exécuté dans le navigateur des visiteurs.',
                        ],
                        [
                            'question' => 'Pourquoi valider les entrées côté serveur même si le frontend les valide déjà ?',
                            'options' => ['Pour ralentir le serveur', 'Parce que la validation côté client peut être contournée', 'Parce que c\'est obligatoire par la loi', 'Pour économiser de la bande passante'],
                            'correct' => [1],
                            'explanation' => 'Un attaquant peut contourner le frontend ; seule la validation serveur est fiable (défense en profondeur).',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 9 — Architecture (MVC & couches)',
                    'subtitle' => 'Séparer les responsabilités',
                    'xp_reward' => 200,
                    'content' => "🎯 **Objectif** : organiser le code pour qu'il reste lisible et maintenable.\n\n💡 Le pattern **MVC** (Model-View-Controller) sépare trois responsabilités :\n• **Model** : les données et la logique métier (ex. `Article`, accès à la base).\n• **View** : la présentation (HTML, ou JSON pour une API).\n• **Controller** : l'aiguilleur — il reçoit la requête, appelle ce qu'il faut, renvoie une réponse.\n\n✅ Un contrôleur **mince** se contente d'orchestrer :\n```php\nclass ArticleController {\n    public function store(Request \$request, ArticleService \$service) {\n        \$data = \$request->validate([...]);\n        \$article = \$service->creer(\$data);\n        return response()->json(\$article, 201);\n    }\n}\n```\n\n✅ La **couche service** contient la logique métier réutilisable, hors du contrôleur :\n```php\nclass ArticleService {\n    public function creer(array \$data): Article {\n        // règles métier, notifications, etc.\n        return Article::create(\$data);\n    }\n}\n```\n\n💡 Principe clé : **séparation des préoccupations**. Chaque couche a un seul rôle. On évite les contrôleurs \"fourre-tout\" (fat controllers) où tout est mélangé. Code mieux séparé = plus facile à tester, à modifier et à comprendre.",
                    'questions' => [
                        [
                            'question' => 'Dans le pattern MVC, quel est le rôle du Controller ?',
                            'options' => ['Stocker les données en base', 'Recevoir la requête, orchestrer et renvoyer une réponse', 'Générer le CSS', 'Remplacer la base de données'],
                            'correct' => [1],
                            'explanation' => 'Le contrôleur aiguille : il reçoit la requête, appelle modèles/services et renvoie une réponse.',
                        ],
                        [
                            'question' => 'Pourquoi extraire la logique métier dans une couche service ?',
                            'options' => ['Pour rendre le code plus lent', 'Pour garder les contrôleurs minces, réutiliser et tester la logique', 'Parce que les contrôleurs ne peuvent pas accéder à la base', 'Pour supprimer le besoin de modèles'],
                            'correct' => [1],
                            'explanation' => 'La couche service centralise la logique métier réutilisable et garde les contrôleurs minces et testables.',
                        ],
                        [
                            'question' => 'Que signifie "M" dans MVC ?',
                            'options' => ['Middleware', 'Model', 'Migration', 'Module'],
                            'correct' => [1],
                            'explanation' => 'M = Model : les données et la logique métier.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 10 — Cache & performance',
                    'subtitle' => 'Mise en cache et files d\'attente',
                    'xp_reward' => 220,
                    'content' => "🎯 **Objectif** : rendre l'application rapide et capable de tenir la charge.\n\n💡 Le **cache** stocke temporairement un résultat coûteux pour ne pas le recalculer à chaque requête. On utilise souvent un magasin rapide en mémoire comme **Redis**.\n\n✅ Mettre en cache une donnée coûteuse :\n```php\n// Si déjà en cache, on la renvoie ; sinon on la calcule pour 1h\n\$stats = Cache::remember('stats_accueil', 3600, function () {\n    return Article::calculerStatistiquesLourdes();\n});\n```\n💡 Attention à l'**invalidation** : quand la donnée source change, il faut vider/mettre à jour le cache, sinon on sert du périmé.\n\n✅ Les **files d'attente** (queues) déportent les tâches lentes hors du cycle requête/réponse. Au lieu d'envoyer un email pendant que l'utilisateur attend, on met un **job** en file ; un **worker** le traite en arrière-plan :\n```php\n// La requête se termine vite ; l'email part plus tard\nSendWelcomeEmail::dispatch(\$user);\n```\n\n✅ Autres leviers de performance : indexer la base (voir roadmap SQL), éviter le problème N+1, paginer les grandes listes, compresser les réponses.\n\n💡 Règle : mesure avant d'optimiser. Profile pour trouver le vrai goulot d'étranglement plutôt que d'optimiser au hasard.",
                    'questions' => [
                        [
                            'question' => 'À quoi sert la mise en cache ?',
                            'options' => ['À chiffrer les données', 'À éviter de recalculer un résultat coûteux à chaque requête', 'À supprimer la base de données', 'À ralentir les réponses volontairement'],
                            'correct' => [1],
                            'explanation' => 'Le cache stocke un résultat coûteux pour le réutiliser rapidement sans le recalculer.',
                        ],
                        [
                            'question' => 'Pourquoi déporter l\'envoi d\'un email dans une file d\'attente (queue) ?',
                            'options' => ['Pour que l\'utilisateur attende plus longtemps', 'Pour traiter la tâche lente en arrière-plan et répondre vite à l\'utilisateur', 'Parce que PHP ne sait pas envoyer d\'emails', 'Pour chiffrer l\'email'],
                            'correct' => [1],
                            'explanation' => 'Une queue traite la tâche lente en arrière-plan via un worker, libérant la requête immédiatement.',
                        ],
                        [
                            'question' => 'Quel est un risque du cache mal géré ?',
                            'options' => ['Servir des données périmées si on n\'invalide pas le cache', 'Une consommation mémoire nulle', 'L\'impossibilité d\'utiliser HTTP', 'La suppression automatique de la base'],
                            'correct' => [0],
                            'explanation' => 'Sans invalidation correcte, le cache peut renvoyer des données obsolètes (périmées).',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 11 — Les tests',
                    'subtitle' => 'Unitaires et intégration',
                    'xp_reward' => 220,
                    'content' => "🎯 **Objectif** : vérifier automatiquement que le code fonctionne et continue de fonctionner.\n\n💡 Les **tests** sont du code qui vérifie ton code. Ils permettent de modifier sans peur : si on casse quelque chose, un test échoue immédiatement.\n\n✅ Les grands types :\n• **Tests unitaires** : vérifient une petite unité isolée (une fonction, une méthode) sans dépendances externes.\n• **Tests d'intégration** : vérifient que plusieurs parties marchent ensemble (ex. contrôleur + base de données).\n• **Tests end-to-end (E2E)** : simulent un parcours complet de l'utilisateur.\n\n✅ Un test unitaire suit le schéma **AAA** (Arrange, Act, Assert) :\n```php\npublic function test_addition() {\n    // Arrange\n    \$calc = new Calculatrice();\n    // Act\n    \$resultat = \$calc->add(2, 3);\n    // Assert\n    \$this->assertEquals(5, \$resultat);\n}\n```\n\n✅ Un test d'intégration d'API :\n```php\npublic function test_creation_article() {\n    \$response = \$this->postJson('/api/articles', ['titre' => 'Test']);\n    \$response->assertStatus(201);\n    \$this->assertDatabaseHas('articles', ['titre' => 'Test']);\n}\n```\n\n💡 Vise des tests **rapides, isolés et déterministes** (toujours le même résultat). Un bon filet de tests donne la confiance de déployer souvent.",
                    'questions' => [
                        [
                            'question' => 'Que vérifie un test unitaire ?',
                            'options' => ['Tout le système en même temps', 'Une petite unité isolée (fonction/méthode)', 'Le design de l\'interface', 'La vitesse du réseau'],
                            'correct' => [1],
                            'explanation' => 'Un test unitaire isole et vérifie une petite unité de code sans dépendances externes.',
                        ],
                        [
                            'question' => 'Que signifie le schéma AAA d\'un test ?',
                            'options' => ['Authentification, Autorisation, Audit', 'Arrange, Act, Assert', 'Add, Adjust, Approve', 'Async, Await, Abort'],
                            'correct' => [1],
                            'explanation' => 'Arrange (préparer), Act (exécuter), Assert (vérifier) : la structure classique d\'un test.',
                        ],
                        [
                            'question' => 'Quel test vérifie que plusieurs parties (ex. contrôleur + base) fonctionnent ensemble ?',
                            'options' => ['Test unitaire', 'Test d\'intégration', 'Test de syntaxe', 'Test de police d\'écriture'],
                            'correct' => [1],
                            'explanation' => 'Le test d\'intégration vérifie la collaboration de plusieurs composants ensemble.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 12 — Déploiement & bonnes pratiques',
                    'subtitle' => 'Env, logs, monitoring',
                    'xp_reward' => 250,
                    'content' => "🎯 **Objectif** : mettre l'application en production de façon fiable et observable.\n\n💡 **Déployer**, c'est rendre ton application accessible sur un serveur réel. L'environnement de production diffère de ta machine : il faut le configurer proprement.\n\n✅ **Variables d'environnement** : ne jamais coder en dur des secrets (mots de passe, clés d'API) dans le code. On les met dans un fichier `.env` (jamais committé) et on les lit :\n```php\n\$dbPassword = env('DB_PASSWORD');\n```\n⚠️ Ajoute `.env` au `.gitignore`. Un secret poussé sur Git est un secret compromis.\n\n✅ **Logs** : enregistre ce qui se passe pour pouvoir diagnostiquer les incidents :\n```php\nLog::info('Utilisateur connecté', ['id' => \$user->id]);\nLog::error('Échec paiement', ['erreur' => \$e->getMessage()]);\n```\n💡 Ne logge JAMAIS de données sensibles (mots de passe, numéros de carte).\n\n✅ **Monitoring** : surveiller l'application en continu — temps de réponse, taux d'erreur, charge serveur — avec des alertes (Sentry, etc.) pour être prévenu avant les utilisateurs.\n\n✅ Bonnes pratiques de déploiement :\n• Passer l'app en mode production (`APP_ENV=production`, debug désactivé).\n• Utiliser HTTPS partout.\n• Automatiser via **CI/CD** : tests + déploiement déclenchés à chaque push.\n• Prévoir une stratégie de **sauvegarde** de la base.\n\n🏆 Félicitations ! Tu as parcouru tout le chemin du backend : du modèle client/serveur jusqu'au déploiement. Continue à pratiquer en construisant de vrais projets — c'est là qu'on apprend le plus.",
                    'questions' => [
                        [
                            'question' => 'Où doit-on stocker les secrets (mots de passe, clés d\'API) ?',
                            'options' => ['En dur dans le code source', 'Dans des variables d\'environnement (.env non committé)', 'Dans un commentaire du code', 'Dans la base de données en clair'],
                            'correct' => [1],
                            'explanation' => 'Les secrets vont dans des variables d\'environnement (.env exclu de Git), jamais en dur dans le code.',
                        ],
                        [
                            'question' => 'À quoi servent les logs en production ?',
                            'options' => ['À ralentir le serveur', 'À enregistrer les événements pour diagnostiquer les incidents', 'À remplacer les tests', 'À afficher des pages plus jolies'],
                            'correct' => [1],
                            'explanation' => 'Les logs tracent ce qui se passe et aident à diagnostiquer les problèmes en production.',
                        ],
                        [
                            'question' => 'Quelles sont de bonnes pratiques de déploiement ? (plusieurs réponses)',
                            'options' => ['Utiliser HTTPS', 'Committer le fichier .env avec les secrets', 'Désactiver le mode debug en production', 'Logger les mots de passe en clair'],
                            'correct' => [0, 2],
                            'explanation' => 'HTTPS et la désactivation du debug en production sont de bonnes pratiques ; committer le .env ou logger des mots de passe sont des fautes graves.',
                        ],
                    ],
                ],
            ],
        ]);

        $this->command->info('  ✓ Roadmap Backend créée (12 niveaux).');
    }
}
