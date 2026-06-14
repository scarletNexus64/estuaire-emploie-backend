<?php

namespace Database\Seeders\Roadmaps;

use Illuminate\Database\Seeder;

/**
 * Roadmap Frontend Web — des fondations du web jusqu'à React, l'outillage et le déploiement.
 * Contenu rédigé (tips), QCM de validation par niveau.
 */
class FrontendRoadmapSeeder extends Seeder
{
    use RoadmapSeederHelper;

    public function run(): void
    {
        $this->createRoadmap([
            'title' => 'Deviens Développeur Frontend',
            'slug' => 'devenir-developpeur-frontend',
            'domain' => 'developpement',
            'description' => "Construis des interfaces web modernes, du tout premier `<h1>` jusqu'à une application React déployée. HTML, CSS, JavaScript, frameworks, outillage et bonnes pratiques d'accessibilité et de performance.",
            'objectives' => "Comprendre comment fonctionne le web\nÉcrire du HTML sémantique et du CSS moderne\nMaîtriser Flexbox, Grid et le responsive\nProgrammer en JavaScript (DOM, événements, async)\nConstruire des interfaces avec React (composants, state, hooks)\nUtiliser npm, les bundlers et Git\nAppliquer les bonnes pratiques d'accessibilité, de performance et de déploiement",
            'icon' => '🎨',
            'color' => '#EF4444',
            'difficulty' => 'beginner',
            'required_packs' => [],
            'pass_threshold' => 70,
            'order' => 2,
            'levels' => [
                [
                    'title' => 'Niveau 1 — Comment fonctionne le web',
                    'subtitle' => 'HTTP, navigateur, requêtes',
                    'xp_reward' => 100,
                    'content' => "🎯 **Objectif** : comprendre ce qui se passe quand tu tapes une adresse dans ton navigateur.\n\n💡 Le web repose sur un modèle **client / serveur**. Ton **navigateur** (le client) envoie une **requête HTTP** à un **serveur**, qui répond avec des fichiers (HTML, CSS, JS, images).\n\n✅ Le cycle simplifié :\n1. Tu tapes `https://exemple.com`.\n2. Le **DNS** traduit le nom de domaine en adresse IP.\n3. Le navigateur envoie une requête `GET` au serveur.\n4. Le serveur renvoie une réponse avec un **code de statut** (`200` OK, `404` introuvable, `500` erreur serveur).\n5. Le navigateur **rend** (affiche) le HTML, applique le CSS, exécute le JavaScript.\n\n✅ **HTTP** est le protocole d'échange. `HTTPS` est sa version chiffrée (sécurisée). Les méthodes courantes : `GET` (lire), `POST` (envoyer), `PUT`/`PATCH` (modifier), `DELETE` (supprimer).\n\n💡 Un site web frontend est composé de **trois langages** : HTML (la structure), CSS (l'apparence), JavaScript (le comportement).",
                    'questions' => [
                        [
                            'question' => 'Que fait le DNS quand tu tapes une adresse de site ?',
                            'options' => ['Il chiffre la connexion', 'Il traduit le nom de domaine en adresse IP', 'Il affiche le HTML', 'Il compresse les images'],
                            'correct' => [1],
                            'explanation' => 'Le DNS résout le nom de domaine (exemple.com) en une adresse IP joignable.',
                        ],
                        [
                            'question' => 'Que signifie un code de statut HTTP 404 ?',
                            'options' => ['Succès', 'Ressource introuvable', 'Erreur serveur', 'Redirection'],
                            'correct' => [1],
                            'explanation' => '404 = Not Found : la ressource demandée n\'existe pas sur le serveur.',
                        ],
                        [
                            'question' => 'Quels sont les trois langages de base du frontend ? (plusieurs réponses)',
                            'options' => ['HTML', 'SQL', 'CSS', 'JavaScript'],
                            'correct' => [0, 2, 3],
                            'explanation' => 'HTML (structure), CSS (style) et JavaScript (comportement) forment le socle du frontend. SQL est côté base de données.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 2 — HTML sémantique',
                    'subtitle' => 'Structurer le contenu',
                    'xp_reward' => 100,
                    'content' => "🎯 **Objectif** : structurer une page avec des balises HTML qui ont du sens.\n\n💡 Le **HTML** décrit la structure d'une page avec des **balises** (`tags`). Une balise s'ouvre et se ferme : `<p>texte</p>`.\n\n✅ Squelette minimal d'une page :\n```\n<!DOCTYPE html>\n<html lang=\"fr\">\n  <head>\n    <meta charset=\"UTF-8\">\n    <title>Ma page</title>\n  </head>\n  <body>\n    <h1>Bonjour</h1>\n  </body>\n</html>\n```\n\n✅ Le **HTML sémantique** utilise des balises qui décrivent leur rôle plutôt que des `<div>` partout :\n• `<header>` : en-tête\n• `<nav>` : navigation\n• `<main>` : contenu principal\n• `<article>` / `<section>` : blocs de contenu\n• `<footer>` : pied de page\n\n💡 Pourquoi c'est important ? Le HTML sémantique améliore l'**accessibilité** (lecteurs d'écran) et le **référencement** (SEO). Un `<button>` est compris par tous les outils, contrairement à un `<div onclick>`.\n\n✅ Les **attributs** ajoutent de l'information : `<a href=\"...\">`, `<img src=\"...\" alt=\"description\">`. L'attribut `alt` sur une image est essentiel pour l'accessibilité.",
                    'questions' => [
                        [
                            'question' => 'Quelle balise désigne le contenu principal d\'une page ?',
                            'options' => ['<content>', '<main>', '<body-main>', '<div class=\"main\">'],
                            'correct' => [1],
                            'explanation' => '`<main>` est la balise sémantique dédiée au contenu principal unique de la page.',
                        ],
                        [
                            'question' => 'À quoi sert l\'attribut `alt` sur une image ?',
                            'options' => ['À aligner l\'image', 'À décrire l\'image (accessibilité, image manquante)', 'À changer sa taille', 'À ajouter un lien'],
                            'correct' => [1],
                            'explanation' => '`alt` fournit une description textuelle, utile pour les lecteurs d\'écran et si l\'image ne charge pas.',
                        ],
                        [
                            'question' => 'Pourquoi préférer le HTML sémantique aux <div> partout ?',
                            'options' => ['C\'est plus rapide à charger', 'Cela améliore l\'accessibilité et le SEO', 'Cela réduit le CSS nécessaire', 'C\'est obligatoire en HTML5'],
                            'correct' => [1],
                            'explanation' => 'Les balises sémantiques sont comprises par les lecteurs d\'écran et les moteurs de recherche.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 3 — CSS : sélecteurs et box model',
                    'subtitle' => 'Donner du style',
                    'xp_reward' => 120,
                    'content' => "🎯 **Objectif** : appliquer des styles et comprendre comment chaque élément occupe l'espace.\n\n💡 Le **CSS** (Cascading Style Sheets) décrit l'apparence. On cible un élément avec un **sélecteur**, puis on déclare des propriétés :\n```\np {\n  color: red;\n  font-size: 16px;\n}\n```\n\n✅ Les **sélecteurs** courants :\n• par balise : `p`, `h1`\n• par classe : `.bouton` (cible `class=\"bouton\"`)\n• par id : `#menu` (cible `id=\"menu\"`)\n• descendant : `nav a` (les liens dans une nav)\n\n💡 La **spécificité** détermine quelle règle gagne en cas de conflit : id > classe > balise. Évite `!important`, c'est souvent un signe de mauvaise organisation.\n\n✅ Le **box model** : chaque élément est une boîte composée de quatre couches, de l'intérieur vers l'extérieur :\n• `content` (le contenu)\n• `padding` (espace intérieur)\n• `border` (bordure)\n• `margin` (espace extérieur)\n\n💡 Astuce indispensable : `box-sizing: border-box;` fait que `width` inclut le padding et la bordure. La plupart des projets l'appliquent à tout :\n```\n* { box-sizing: border-box; }\n```",
                    'questions' => [
                        [
                            'question' => 'Quel sélecteur cible un élément avec `class=\"carte\"` ?',
                            'options' => ['#carte', '.carte', 'carte', '*carte'],
                            'correct' => [1],
                            'explanation' => 'Le point `.` cible une classe. Le `#` cible un id.',
                        ],
                        [
                            'question' => 'Dans le box model, quelle couche est l\'espace EXTÉRIEUR de la boîte ?',
                            'options' => ['padding', 'border', 'margin', 'content'],
                            'correct' => [2],
                            'explanation' => 'Le `margin` est l\'espace extérieur qui sépare l\'élément des autres. Le `padding` est l\'espace intérieur.',
                        ],
                        [
                            'question' => 'Que fait `box-sizing: border-box;` ?',
                            'options' => ['Ajoute une bordure', 'Inclut padding et border dans la largeur déclarée', 'Centre l\'élément', 'Supprime les marges'],
                            'correct' => [1],
                            'explanation' => 'Avec border-box, la `width` englobe le contenu + padding + border, ce qui simplifie les calculs.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 4 — CSS Layout avec Flexbox',
                    'subtitle' => 'Aligner en une dimension',
                    'xp_reward' => 140,
                    'content' => "🎯 **Objectif** : aligner et répartir des éléments facilement avec Flexbox.\n\n💡 **Flexbox** est un mode de mise en page **en une dimension** (une ligne OU une colonne). On l'active sur un conteneur :\n```\n.conteneur {\n  display: flex;\n}\n```\nTous les enfants directs deviennent des **flex items**.\n\n✅ Les propriétés clés du conteneur :\n• `flex-direction` : `row` (par défaut, horizontal) ou `column` (vertical)\n• `justify-content` : alignement sur l'axe principal (`center`, `space-between`, `flex-end`...)\n• `align-items` : alignement sur l'axe secondaire (`center`, `stretch`...)\n• `gap` : espace entre les items\n\n✅ Centrer parfaitement un élément, le classique :\n```\n.conteneur {\n  display: flex;\n  justify-content: center;\n  align-items: center;\n}\n```\n\n💡 Sur les items, `flex: 1` fait grandir un élément pour occuper l'espace disponible. C'est idéal pour des barres de navigation ou des cartes de tailles variables.\n\n⚠️ Piège fréquent : `justify-content` agit sur l'axe principal. Si `flex-direction: column`, alors `justify-content` aligne verticalement, pas horizontalement.",
                    'questions' => [
                        [
                            'question' => 'Comment activer Flexbox sur un conteneur ?',
                            'options' => ['display: grid;', 'display: flex;', 'position: flex;', 'layout: flex;'],
                            'correct' => [1],
                            'explanation' => '`display: flex;` transforme l\'élément en conteneur flex.',
                        ],
                        [
                            'question' => 'Quelle propriété aligne les items sur l\'axe principal ?',
                            'options' => ['align-items', 'justify-content', 'flex-wrap', 'gap'],
                            'correct' => [1],
                            'explanation' => '`justify-content` gère l\'axe principal ; `align-items` gère l\'axe secondaire.',
                        ],
                        [
                            'question' => 'Flexbox est un système de layout en combien de dimensions ?',
                            'options' => ['Une seule (ligne OU colonne)', 'Deux (lignes ET colonnes)', 'Trois', 'Aucune, c\'est juste pour le texte'],
                            'correct' => [0],
                            'explanation' => 'Flexbox travaille en une dimension. Pour deux dimensions, on utilise CSS Grid.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 5 — CSS Grid & responsive',
                    'subtitle' => 'Grilles et adaptation aux écrans',
                    'xp_reward' => 160,
                    'content' => "🎯 **Objectif** : construire des grilles en deux dimensions et adapter le site à toutes les tailles d'écran.\n\n💡 **CSS Grid** gère **deux dimensions** (lignes ET colonnes) en même temps. On définit la grille sur le conteneur :\n```\n.grille {\n  display: grid;\n  grid-template-columns: 1fr 1fr 1fr;\n  gap: 16px;\n}\n```\nIci, `1fr 1fr 1fr` crée 3 colonnes de largeur égale. L'unité `fr` représente une fraction de l'espace disponible.\n\n✅ Une grille responsive automatique, sans media query :\n```\ngrid-template-columns: repeat(auto-fit, minmax(200px, 1fr));\n```\nLes colonnes s'ajustent : autant que possible, chacune d'au moins 200px.\n\n✅ Le **responsive design** adapte la mise en page à la taille de l'écran avec les **media queries** :\n```\n@media (max-width: 600px) {\n  .grille { grid-template-columns: 1fr; }\n}\n```\nSur petit écran, on passe à une seule colonne.\n\n💡 Bonne pratique : **mobile-first**. On écrit d'abord le style mobile, puis on ajoute des media queries `min-width` pour les écrans plus larges. Pense aussi à `<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">` dans le `<head>`, indispensable au responsive.",
                    'questions' => [
                        [
                            'question' => 'Quelle différence majeure entre Grid et Flexbox ?',
                            'options' => ['Grid est plus ancien', 'Grid gère deux dimensions (lignes et colonnes)', 'Grid ne fonctionne pas sur mobile', 'Grid remplace le HTML'],
                            'correct' => [1],
                            'explanation' => 'Grid est conçu pour des layouts en 2D ; Flexbox pour une seule dimension.',
                        ],
                        [
                            'question' => 'Que représente l\'unité `fr` en CSS Grid ?',
                            'options' => ['Des pixels fixes', 'Une fraction de l\'espace disponible', 'Une largeur en pourcentage du texte', 'Un nombre de colonnes'],
                            'correct' => [1],
                            'explanation' => 'L\'unité `fr` répartit l\'espace restant en fractions.',
                        ],
                        [
                            'question' => 'Quels éléments sont utiles pour le responsive ? (plusieurs réponses)',
                            'options' => ['Les media queries', 'La balise meta viewport', 'L\'attribut alt', 'L\'approche mobile-first'],
                            'correct' => [0, 1, 3],
                            'explanation' => 'Media queries, meta viewport et mobile-first servent le responsive. L\'attribut alt concerne l\'accessibilité des images.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 6 — JavaScript : les bases',
                    'subtitle' => 'Variables, types, fonctions',
                    'xp_reward' => 160,
                    'content' => "🎯 **Objectif** : écrire ses premières instructions JavaScript.\n\n💡 **JavaScript** ajoute du comportement et de l'interactivité aux pages. On déclare des **variables** avec `let` (réassignable) ou `const` (constante). On évite `var` (ancien, portée piégeuse).\n```\nconst nom = \"Awa\";\nlet age = 25;\nage = 26; // OK avec let, interdit avec const\n```\n\n✅ Les **types** principaux :\n• `string` (texte) : `\"bonjour\"`\n• `number` : `42`, `3.14`\n• `boolean` : `true` / `false`\n• `array` (liste) : `[1, 2, 3]`\n• `object` : `{ nom: \"Awa\", age: 25 }`\n• `null` et `undefined` (absence de valeur)\n\n⚠️ JavaScript est **typé dynamiquement** : une variable peut changer de type. Attention aux comparaisons : utilise `===` (égalité stricte) plutôt que `==` (qui convertit les types). `0 == \"\"` est `true`, mais `0 === \"\"` est `false`.\n\n✅ Les **fonctions** regroupent une logique réutilisable :\n```\nfunction additionner(a, b) {\n  return a + b;\n}\n// fonction fléchée (arrow function) :\nconst doubler = (x) => x * 2;\n```\n\n💡 Les méthodes de tableau modernes sont incontournables : `map` (transformer), `filter` (filtrer), `forEach` (parcourir), `find` (trouver le premier).",
                    'questions' => [
                        [
                            'question' => 'Quelle déclaration crée une variable que tu NE pourras PAS réassigner ?',
                            'options' => ['let', 'var', 'const', 'def'],
                            'correct' => [2],
                            'explanation' => '`const` crée une constante : la réassignation est interdite.',
                        ],
                        [
                            'question' => 'Quel opérateur compare valeur ET type sans conversion ?',
                            'options' => ['==', '===', '=', '!='],
                            'correct' => [1],
                            'explanation' => '`===` est l\'égalité stricte : pas de coercition de type, contrairement à `==`.',
                        ],
                        [
                            'question' => 'Quelle méthode de tableau crée un NOUVEAU tableau en transformant chaque élément ?',
                            'options' => ['forEach', 'map', 'filter', 'find'],
                            'correct' => [1],
                            'explanation' => '`map` renvoie un nouveau tableau de la même longueur avec chaque élément transformé.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 7 — JS : DOM & événements',
                    'subtitle' => 'Manipuler la page',
                    'xp_reward' => 180,
                    'content' => "🎯 **Objectif** : modifier la page et réagir aux actions de l'utilisateur.\n\n💡 Le **DOM** (Document Object Model) est la représentation en arbre de ta page HTML, manipulable en JavaScript. Chaque balise devient un **nœud** (node) accessible et modifiable.\n\n✅ Sélectionner des éléments :\n```\nconst titre = document.querySelector('h1');\nconst boutons = document.querySelectorAll('.btn');\n```\n`querySelector` renvoie le premier élément ; `querySelectorAll` renvoie une liste.\n\n✅ Modifier le contenu et les styles :\n```\ntitre.textContent = 'Nouveau titre';\ntitre.classList.add('actif');\ntitre.style.color = 'blue';\n```\n💡 Préfère manipuler les **classes** (`classList.add/remove/toggle`) plutôt que `style` directement : c'est plus propre et le style reste dans le CSS.\n\n✅ Réagir aux **événements** avec `addEventListener` :\n```\nconst bouton = document.querySelector('#valider');\nbouton.addEventListener('click', () => {\n  alert('Cliqué !');\n});\n```\nÉvénements courants : `click`, `input`, `submit`, `keydown`, `mouseover`.\n\n⚠️ Sur un formulaire, pense à `event.preventDefault()` dans le gestionnaire `submit` pour empêcher le rechargement de la page.",
                    'questions' => [
                        [
                            'question' => 'Que signifie DOM ?',
                            'options' => ['Data Object Model', 'Document Object Model', 'Dynamic Output Mode', 'Document Oriented Markup'],
                            'correct' => [1],
                            'explanation' => 'DOM = Document Object Model, l\'arbre des éléments de la page.',
                        ],
                        [
                            'question' => 'Quelle méthode attache un gestionnaire d\'événement à un élément ?',
                            'options' => ['onEvent()', 'addEventListener()', 'attachEvent()', 'listen()'],
                            'correct' => [1],
                            'explanation' => '`addEventListener(type, callback)` est la méthode standard moderne.',
                        ],
                        [
                            'question' => 'Pourquoi appeler `event.preventDefault()` sur un submit de formulaire ?',
                            'options' => ['Pour valider le formulaire', 'Pour empêcher le rechargement par défaut de la page', 'Pour vider les champs', 'Pour envoyer une requête'],
                            'correct' => [1],
                            'explanation' => 'Cela bloque le comportement par défaut (rechargement), pour gérer l\'envoi en JS.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 8 — JS asynchrone',
                    'subtitle' => 'fetch, promises, async/await',
                    'xp_reward' => 200,
                    'content' => "🎯 **Objectif** : récupérer des données distantes sans bloquer la page.\n\n💡 Certaines opérations (appels réseau, timers) prennent du temps. JavaScript les gère de façon **asynchrone** : le code continue pendant qu'on attend la réponse, grâce aux **promesses** (Promises).\n\n✅ Une **Promise** représente une valeur future (réussie ou échouée). On récupère des données avec `fetch` :\n```\nfetch('https://api.exemple.com/users')\n  .then(response => response.json())\n  .then(data => console.log(data))\n  .catch(error => console.error(error));\n```\n`.then` enchaîne les étapes ; `.catch` capture les erreurs.\n\n✅ La syntaxe **async / await** rend le code asynchrone plus lisible, comme du code synchrone :\n```\nasync function chargerUsers() {\n  try {\n    const response = await fetch('https://api.exemple.com/users');\n    const data = await response.json();\n    console.log(data);\n  } catch (error) {\n    console.error(error);\n  }\n}\n```\n💡 `await` met en pause la fonction jusqu'à ce que la promesse soit résolue. Il ne peut s'utiliser que dans une fonction `async`.\n\n⚠️ `fetch` ne rejette PAS la promesse sur les erreurs HTTP (404, 500). Vérifie toujours `response.ok` toi-même avant de traiter la réponse.",
                    'questions' => [
                        [
                            'question' => 'Que renvoie un appel `fetch()` ?',
                            'options' => ['Les données directement', 'Une Promise', 'Une chaîne JSON', 'Un tableau'],
                            'correct' => [1],
                            'explanation' => '`fetch` renvoie une Promise qui se résout avec un objet Response.',
                        ],
                        [
                            'question' => 'Où peut-on utiliser le mot-clé `await` ?',
                            'options' => ['Partout', 'Uniquement dans une fonction `async`', 'Uniquement dans une boucle', 'Uniquement avec .then()'],
                            'correct' => [1],
                            'explanation' => '`await` ne fonctionne qu\'à l\'intérieur d\'une fonction marquée `async` (ou au top-level d\'un module).',
                        ],
                        [
                            'question' => 'Que faut-il vérifier après un fetch car il ne rejette pas sur les erreurs HTTP ?',
                            'options' => ['response.text', 'response.ok', 'response.size', 'response.async'],
                            'correct' => [1],
                            'explanation' => '`response.ok` est false pour les statuts d\'erreur (>= 400) ; fetch ne lève pas d\'exception dans ce cas.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 9 — React : composants, props, JSX',
                    'subtitle' => 'Un framework moderne',
                    'xp_reward' => 220,
                    'content' => "🎯 **Objectif** : découvrir React et construire des interfaces à base de composants.\n\n💡 **React** est une bibliothèque pour construire des interfaces. L'idée centrale : découper l'UI en **composants** réutilisables, chacun gérant une partie de l'écran.\n\n✅ Un composant est une fonction qui retourne du **JSX** (du HTML mélangé à du JavaScript) :\n```\nfunction Bonjour() {\n  return <h1>Bonjour le monde</h1>;\n}\n```\n💡 En JSX, les expressions JavaScript s'écrivent entre accolades : `<p>{2 + 2}</p>` affiche `4`. On utilise `className` au lieu de `class` (réservé en JS).\n\n✅ Les **props** passent des données d'un composant parent vers un enfant, comme des attributs :\n```\nfunction Carte({ titre, prix }) {\n  return (\n    <div className=\"carte\">\n      <h2>{titre}</h2>\n      <p>{prix} FCFA</p>\n    </div>\n  );\n}\n// utilisation :\n<Carte titre=\"Pizza\" prix={3000} />\n```\n💡 Les props sont **en lecture seule** : un composant ne doit jamais modifier ses propres props.\n\n✅ Pour afficher une liste, on utilise `map` et une `key` unique :\n```\n{produits.map(p => <Carte key={p.id} titre={p.nom} prix={p.prix} />)}\n```\n⚠️ La `key` aide React à identifier chaque élément ; oublier la key provoque un avertissement et des bugs de rendu.",
                    'questions' => [
                        [
                            'question' => 'Comment passe-t-on des données d\'un composant parent vers un enfant en React ?',
                            'options' => ['Avec le state', 'Avec les props', 'Avec une variable globale', 'Avec localStorage'],
                            'correct' => [1],
                            'explanation' => 'Les props transmettent des données du parent vers l\'enfant, en lecture seule.',
                        ],
                        [
                            'question' => 'En JSX, quel attribut remplace `class` ?',
                            'options' => ['cssClass', 'className', 'class-name', 'styleClass'],
                            'correct' => [1],
                            'explanation' => '`class` étant un mot réservé en JS, JSX utilise `className`.',
                        ],
                        [
                            'question' => 'Pourquoi faut-il une `key` quand on rend une liste avec `map` ?',
                            'options' => ['Pour trier la liste', 'Pour que React identifie chaque élément efficacement', 'Pour chiffrer les données', 'C\'est purement décoratif'],
                            'correct' => [1],
                            'explanation' => 'La key permet à React de suivre chaque élément lors des mises à jour du DOM virtuel.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 10 — Gestion d\'état & hooks',
                    'subtitle' => 'useState, useEffect',
                    'xp_reward' => 240,
                    'content' => "🎯 **Objectif** : rendre les composants interactifs avec l'état (state) et les hooks.\n\n💡 Le **state** est une donnée interne à un composant qui peut changer dans le temps (ex. un compteur, le contenu d'un champ). Quand le state change, React **re-rend** automatiquement le composant.\n\n✅ Le hook `useState` :\n```\nimport { useState } from 'react';\n\nfunction Compteur() {\n  const [count, setCount] = useState(0);\n  return (\n    <button onClick={() => setCount(count + 1)}>\n      Cliqué {count} fois\n    </button>\n  );\n}\n```\n`useState(0)` renvoie la valeur actuelle et une fonction pour la modifier. **Ne modifie jamais le state directement** (`count++`) : utilise toujours le setter (`setCount`).\n\n✅ Le hook `useEffect` exécute du code en réaction au cycle de vie (chargement, mises à jour) — parfait pour charger des données :\n```\nuseEffect(() => {\n  fetch('/api/produits')\n    .then(r => r.json())\n    .then(setProduits);\n}, []); // [] = exécuté une seule fois au montage\n```\n💡 Le **tableau de dépendances** (le `[]`) contrôle quand l'effet se ré-exécute : vide = une fois au montage, `[id]` = à chaque changement de `id`.\n\n⚠️ Règles des hooks : on les appelle toujours au **niveau supérieur** du composant, jamais dans une condition, une boucle ou une fonction imbriquée.",
                    'questions' => [
                        [
                            'question' => 'Que renvoie `useState(0)` ?',
                            'options' => ['Juste la valeur', 'Un tableau : la valeur et une fonction pour la modifier', 'Une promesse', 'Le composant entier'],
                            'correct' => [1],
                            'explanation' => '`useState` renvoie un tableau `[valeur, setter]` qu\'on déstructure.',
                        ],
                        [
                            'question' => 'Que signifie un tableau de dépendances vide `[]` dans useEffect ?',
                            'options' => ['L\'effet ne s\'exécute jamais', 'L\'effet s\'exécute une seule fois au montage', 'L\'effet s\'exécute à chaque rendu', 'C\'est une erreur'],
                            'correct' => [1],
                            'explanation' => 'Un tableau vide fait s\'exécuter l\'effet une seule fois, après le premier rendu (montage).',
                        ],
                        [
                            'question' => 'Quelles affirmations sur les hooks sont correctes ? (plusieurs réponses)',
                            'options' => ['On les appelle au niveau supérieur du composant', 'On peut les appeler dans un `if`', 'On ne doit jamais modifier le state directement', 'On utilise le setter de useState pour changer la valeur'],
                            'correct' => [0, 2, 3],
                            'explanation' => 'Les hooks s\'appellent au top-level (pas dans une condition), le state se modifie via le setter, jamais directement.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 11 — Outils : npm, bundlers, Git',
                    'subtitle' => 'L\'écosystème du frontend',
                    'xp_reward' => 220,
                    'content' => "🎯 **Objectif** : maîtriser l'outillage du développeur frontend moderne.\n\n💡 **npm** (Node Package Manager) gère les bibliothèques (packages) de ton projet. Le fichier `package.json` liste les dépendances et les scripts.\n```\nnpm install react      # ajouter une dépendance\nnpm install            # installer toutes les dépendances\nnpm run dev            # lancer un script défini\n```\n⚠️ Le dossier `node_modules` (souvent énorme) ne doit JAMAIS être versionné : on l'ignore via `.gitignore`. Le fichier `package-lock.json`, lui, est versionné pour figer les versions exactes.\n\n✅ Un **bundler** (Vite, Webpack...) regroupe et optimise tes fichiers JS/CSS pour le navigateur. **Vite** est l'outil moderne recommandé : démarrage instantané et rechargement à chaud (`HMR`).\n```\nnpm create vite@latest mon-app\n```\n\n✅ **Git** versionne ton code. Les commandes essentielles :\n```\ngit init                 # initialiser un dépôt\ngit add .                # préparer les fichiers\ngit commit -m \"message\"  # enregistrer un point\ngit push                 # envoyer sur le serveur distant\ngit branch / git checkout -b feature  # gérer les branches\n```\n💡 Bonne pratique : des commits petits et fréquents avec des messages clairs. Travaille sur une **branche** par fonctionnalité, puis fusionne via une **Pull Request**.",
                    'questions' => [
                        [
                            'question' => 'Quel dossier ne doit JAMAIS être versionné dans Git ?',
                            'options' => ['src', 'node_modules', 'public', 'dist'],
                            'correct' => [1],
                            'explanation' => '`node_modules` est volumineux et réinstallable via npm install ; on l\'exclut avec .gitignore.',
                        ],
                        [
                            'question' => 'À quoi sert un bundler comme Vite ou Webpack ?',
                            'options' => ['À gérer la base de données', 'À regrouper et optimiser les fichiers pour le navigateur', 'À écrire du HTML', 'À héberger le site'],
                            'correct' => [1],
                            'explanation' => 'Un bundler assemble et optimise les modules JS/CSS pour la production.',
                        ],
                        [
                            'question' => 'Quelle commande enregistre un point de sauvegarde dans Git ?',
                            'options' => ['git save', 'git commit', 'git push', 'git stage'],
                            'correct' => [1],
                            'explanation' => '`git commit` enregistre les modifications préparées (add) dans l\'historique.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 12 — Bonnes pratiques & déploiement',
                    'subtitle' => 'Accessibilité, performance, mise en ligne',
                    'xp_reward' => 260,
                    'content' => "🎯 **Objectif** : livrer un site rapide, accessible et en ligne.\n\n💡 **Accessibilité (a11y)** : ton site doit être utilisable par tous, y compris les personnes en situation de handicap.\n• Utilise du HTML sémantique et des balises `<button>`, `<label>` correctes.\n• Renseigne les attributs `alt` sur les images.\n• Assure un **contraste** suffisant entre texte et fond.\n• Le site doit être navigable au **clavier** (touche Tab).\n• Utilise les attributs `aria-*` quand le HTML natif ne suffit pas.\n\n✅ **Performance** : un site lent fait fuir les visiteurs.\n• **Optimise les images** (formats modernes comme WebP, dimensions adaptées).\n• Charge le JS de façon différée (`lazy loading`, `code splitting`).\n• Minimise et compresse les fichiers (le bundler s'en charge en production).\n• Mesure avec **Lighthouse** (intégré aux DevTools du navigateur).\n\n✅ **Déploiement** : mettre le site en ligne.\n• On génère d'abord la version de production : `npm run build` (crée un dossier `dist`).\n• Des plateformes comme **Netlify**, **Vercel** ou **GitHub Pages** hébergent gratuitement les sites statiques, souvent connectées à Git pour un déploiement automatique à chaque push.\n\n💡 Pense aussi au **HTTPS** (obligatoire), à un nom de domaine, et à tester ton site sur plusieurs navigateurs et tailles d'écran.\n\n🏆 Félicitations ! Tu as parcouru tout le chemin du frontend, des fondations du web jusqu'au déploiement d'une app React. Continue à pratiquer en construisant de vrais projets.",
                    'questions' => [
                        [
                            'question' => 'Quelles pratiques améliorent l\'accessibilité ? (plusieurs réponses)',
                            'options' => ['Ajouter un attribut alt aux images', 'Assurer un bon contraste de couleurs', 'Mettre toutes les images en très haute résolution', 'Rendre le site navigable au clavier'],
                            'correct' => [0, 1, 3],
                            'explanation' => 'alt, contraste et navigation clavier servent l\'accessibilité. La haute résolution systématique nuit plutôt à la performance.',
                        ],
                        [
                            'question' => 'Quel outil mesure la performance et l\'accessibilité d\'une page ?',
                            'options' => ['Photoshop', 'Lighthouse', 'npm', 'Git'],
                            'correct' => [1],
                            'explanation' => 'Lighthouse (dans les DevTools) audite performance, accessibilité, SEO et bonnes pratiques.',
                        ],
                        [
                            'question' => 'Que produit généralement la commande `npm run build` ?',
                            'options' => ['Une base de données', 'Un dossier de production optimisé (ex. dist)', 'Un dépôt Git', 'Un fichier package.json'],
                            'correct' => [1],
                            'explanation' => '`npm run build` génère les fichiers optimisés prêts à déployer, souvent dans un dossier `dist`.',
                        ],
                    ],
                ],
            ],
        ]);

        $this->command->info('  ✓ Roadmap Frontend créée (12 niveaux).');
    }
}
