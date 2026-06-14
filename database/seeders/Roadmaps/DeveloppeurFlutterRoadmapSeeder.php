<?php

namespace Database\Seeders\Roadmaps;

use Illuminate\Database\Seeder;

/**
 * Roadmap Flutter — du langage Dart à la publication d'une application mobile.
 */
class DeveloppeurFlutterRoadmapSeeder extends Seeder
{
    use RoadmapSeederHelper;

    public function run(): void
    {
        $this->createRoadmap([
            'title' => 'Deviens Développeur Mobile (Flutter)',
            'slug' => 'developpeur-mobile-flutter',
            'domain' => 'developpement',
            'description' => "Apprends à créer des applications mobiles modernes pour Android et iOS avec Flutter et le langage Dart. De la syntaxe de base jusqu'à la publication sur les stores, cette roadmap te guide pas à pas avec des exemples concrets adaptés au contexte africain (paiement mobile money, faible connexion, etc.).",
            'objectives' => "Maîtriser les bases du langage Dart\nComprendre la philosophie des widgets de Flutter\nDifférencier widgets stateless et stateful\nConstruire des interfaces avec les layouts\nNaviguer entre plusieurs écrans\nGérer l'état d'une application proprement\nConsommer des API REST et stocker des données localement\nPublier une application sur le Play Store et l'App Store",
            'icon' => '📱',
            'color' => '#02569B',
            'difficulty' => 'intermediate',
            'required_packs' => [],
            'pass_threshold' => 70,
            'order' => 20,
            'levels' => [
                [
                    'title' => 'Niveau 1 — Découverte de Flutter et Dart',
                    'subtitle' => 'Comprendre l\'écosystème et écrire son premier code Dart',
                    'xp_reward' => 100,
                    'content' => "🎯 **Objectif** : comprendre ce qu'est Flutter et écrire tes premières lignes de Dart.\n\n💡 Flutter est un framework open source de Google qui permet de créer une seule base de code pour Android, iOS, le web et le bureau. Le langage utilisé s'appelle **Dart**, compilé en code natif pour de bonnes performances.\n\n• `main()` est le point d'entrée de tout programme Dart.\n• Les types principaux : `int`, `double`, `String`, `bool`, `List`, `Map`.\n• `var` infère le type, `final` est non réassignable, `const` est constant à la compilation.\n\n```dart\nvoid main() {\n  String ville = 'Douala';\n  int habitants = 3000000;\n  final double taux = 0.18; // TVA\n  print('Bienvenue à \$ville');\n}\n```\n\n✅ Installe le SDK Flutter et lance `flutter doctor` pour vérifier ton environnement.\n\n⚠️ Dart est fortement typé : déclarer le bon type évite beaucoup de bugs.",
                    'questions' => [
                        ['question' => 'Quel langage de programmation utilise Flutter ?', 'options' => ['Java', 'Dart', 'Kotlin', 'Swift'], 'correct' => [1], 'explanation' => 'Flutter utilise le langage Dart, développé par Google.'],
                        ['question' => 'Quel mot-clé déclare une variable non réassignable mais évaluée à l\'exécution ?', 'options' => ['const', 'var', 'final', 'static'], 'correct' => [2], 'explanation' => '`final` rend la variable non réassignable, sa valeur étant fixée à l\'exécution.'],
                        ['question' => 'Quelle commande vérifie que ton environnement Flutter est correctement installé ?', 'options' => ['flutter run', 'flutter doctor', 'flutter build', 'flutter test'], 'correct' => [1], 'explanation' => '`flutter doctor` diagnostique l\'installation et signale les dépendances manquantes.'],
                    ],
                ],
                [
                    'title' => 'Niveau 2 — Approfondir Dart',
                    'subtitle' => 'Fonctions, classes et programmation asynchrone',
                    'xp_reward' => 110,
                    'content' => "🎯 **Objectif** : maîtriser les fonctions, les classes et l'asynchrone, essentiels avant d'aborder les widgets.\n\n💡 Dart est orienté objet : tout est un objet, même les nombres.\n\n• Fonction fléchée : `int carre(int x) => x * x;`\n• Classe avec constructeur :\n\n```dart\nclass Etudiant {\n  String nom;\n  int age;\n  Etudiant(this.nom, this.age);\n  String saluer() => 'Bonjour \$nom';\n}\n```\n\n• L'asynchrone est partout (réseau, fichiers). On utilise `Future`, `async` et `await` :\n\n```dart\nFuture<String> chargerProfil() async {\n  await Future.delayed(Duration(seconds: 2));\n  return 'Profil chargé';\n}\n```\n\n✅ `null safety` : un type comme `String?` peut être nul, `String` ne peut pas.\n\n⚠️ Oublier `await` renvoie un `Future` non résolu au lieu de la valeur attendue.",
                    'questions' => [
                        ['question' => 'Que retourne une fonction marquée `async` ?', 'options' => ['Un String', 'Un Future', 'Un void toujours', 'Une List'], 'correct' => [1], 'explanation' => 'Une fonction `async` retourne toujours un `Future` qui se résoudra plus tard.'],
                        ['question' => 'Que signifie le type `String?` en Dart ?', 'options' => ['Une chaîne obligatoire', 'Une chaîne qui peut être nulle', 'Une chaîne constante', 'Une liste de chaînes'], 'correct' => [1], 'explanation' => 'Le `?` indique que la variable peut contenir la valeur `null` (null safety).'],
                        ['question' => 'Quel mot-clé permet d\'attendre le résultat d\'un Future ?', 'options' => ['wait', 'async', 'await', 'then'], 'correct' => [2], 'explanation' => '`await` suspend l\'exécution jusqu\'à ce que le Future soit résolu.'],
                    ],
                ],
                [
                    'title' => 'Niveau 3 — Les widgets, brique de base de Flutter',
                    'subtitle' => 'Tout est widget : comprendre l\'arbre de widgets',
                    'xp_reward' => 120,
                    'content' => "🎯 **Objectif** : comprendre la philosophie « tout est widget » et construire un premier écran.\n\n💡 Dans Flutter, l'interface se compose en assemblant des **widgets** imbriqués qui forment un arbre. Texte, bouton, image, mise en page : ce sont tous des widgets.\n\n• `MaterialApp` enveloppe l'application (style Android).\n• `Scaffold` fournit la structure de base d'un écran (AppBar, body, etc.).\n• `Text`, `Icon`, `Image` affichent du contenu.\n\n```dart\nMaterialApp(\n  home: Scaffold(\n    appBar: AppBar(title: Text('Mon App')),\n    body: Center(\n      child: Text('Salut Yaoundé !'),\n    ),\n  ),\n)\n```\n\n✅ Le widget `runApp()` démarre l'application en passant le widget racine.\n\n⚠️ Un widget est immuable : pour le modifier, Flutter le reconstruit. C'est ce qui rend l'UI réactive.",
                    'questions' => [
                        ['question' => 'Dans Flutter, que représente principalement l\'interface utilisateur ?', 'options' => ['Des fichiers XML', 'Un arbre de widgets', 'Des feuilles CSS', 'Des activités Android'], 'correct' => [1], 'explanation' => 'L\'UI Flutter est un arbre de widgets imbriqués.'],
                        ['question' => 'Quel widget fournit la structure de base d\'un écran (AppBar, body) ?', 'options' => ['Container', 'Scaffold', 'Column', 'MaterialApp'], 'correct' => [1], 'explanation' => '`Scaffold` offre l\'ossature d\'une page avec AppBar, body, etc.'],
                        ['question' => 'Quelle fonction démarre l\'application en affichant le widget racine ?', 'options' => ['startApp()', 'runApp()', 'main()', 'build()'], 'correct' => [1], 'explanation' => '`runApp()` attache le widget racine à l\'écran.'],
                    ],
                ],
                [
                    'title' => 'Niveau 4 — Stateless vs Stateful',
                    'subtitle' => 'Widgets sans état et widgets avec état',
                    'xp_reward' => 130,
                    'content' => "🎯 **Objectif** : distinguer les widgets `StatelessWidget` et `StatefulWidget` et savoir quand utiliser chacun.\n\n💡 Un **StatelessWidget** ne change jamais après sa création (ex : un logo, un titre fixe). Un **StatefulWidget** peut changer dynamiquement (ex : un compteur, un formulaire).\n\n• On modifie l'état avec `setState()`, qui demande à Flutter de reconstruire le widget.\n\n```dart\nclass Compteur extends StatefulWidget {\n  @override\n  State<Compteur> createState() => _CompteurState();\n}\n\nclass _CompteurState extends State<Compteur> {\n  int valeur = 0;\n  @override\n  Widget build(BuildContext context) {\n    return TextButton(\n      onPressed: () => setState(() => valeur++),\n      child: Text('Clics : \$valeur'),\n    );\n  }\n}\n```\n\n✅ Règle simple : si l'écran ne change jamais, choisis Stateless ; sinon Stateful.\n\n⚠️ Modifier une variable d'état sans `setState()` ne rafraîchira pas l'affichage.",
                    'questions' => [
                        ['question' => 'Quelle méthode déclenche la reconstruction d\'un StatefulWidget ?', 'options' => ['rebuild()', 'setState()', 'update()', 'refresh()'], 'correct' => [1], 'explanation' => '`setState()` notifie Flutter que l\'état a changé et reconstruit le widget.'],
                        ['question' => 'Quel widget choisir pour afficher un logo fixe qui ne change jamais ?', 'options' => ['StatefulWidget', 'StatelessWidget', 'InheritedWidget', 'AnimatedWidget'], 'correct' => [1], 'explanation' => 'Un contenu immuable convient parfaitement à un StatelessWidget.'],
                        ['question' => 'Que se passe-t-il si tu modifies une variable d\'état sans appeler setState() ?', 'options' => ['L\'app plante', 'L\'affichage ne se met pas à jour', 'Une exception est levée', 'L\'app redémarre'], 'correct' => [1], 'explanation' => 'Sans setState(), Flutter ne reconstruit pas le widget et l\'UI reste inchangée.'],
                    ],
                ],
                [
                    'title' => 'Niveau 5 — Layout et mise en page',
                    'subtitle' => 'Organiser les widgets avec Row, Column et Container',
                    'xp_reward' => 140,
                    'content' => "🎯 **Objectif** : organiser les éléments à l'écran avec les widgets de mise en page.\n\n💡 Les layouts les plus utilisés :\n\n• `Column` : empile les enfants verticalement.\n• `Row` : aligne les enfants horizontalement.\n• `Container` : boîte avec marges, padding, couleur, bordure.\n• `Padding`, `Expanded`, `SizedBox` : ajustent l'espace.\n\n```dart\nColumn(\n  mainAxisAlignment: MainAxisAlignment.center,\n  children: [\n    Text('Prix du forfait'),\n    SizedBox(height: 8),\n    Row(\n      children: [\n        Icon(Icons.phone_android),\n        Text('5000 FCFA'),\n      ],\n    ),\n  ],\n)\n```\n\n✅ `mainAxisAlignment` gère l'axe principal, `crossAxisAlignment` l'axe transversal.\n\n⚠️ Un `Row` ou `Column` trop large provoque un débordement (« overflow » jaune et noir). Utilise `Expanded` ou `Flexible` pour répartir l'espace.",
                    'questions' => [
                        ['question' => 'Quel widget empile ses enfants verticalement ?', 'options' => ['Row', 'Column', 'Stack', 'Wrap'], 'correct' => [1], 'explanation' => '`Column` dispose ses enfants de haut en bas.'],
                        ['question' => 'Quel widget utiliser pour qu\'un enfant occupe l\'espace restant dans une Row ?', 'options' => ['Padding', 'Expanded', 'Center', 'SizedBox'], 'correct' => [1], 'explanation' => '`Expanded` étire l\'enfant pour remplir l\'espace disponible.'],
                        ['question' => 'Quelles propriétés contrôlent l\'alignement dans une Column ? (plusieurs réponses)', 'options' => ['mainAxisAlignment', 'color', 'crossAxisAlignment', 'onPressed'], 'correct' => [0, 2], 'explanation' => '`mainAxisAlignment` gère l\'axe vertical et `crossAxisAlignment` l\'axe horizontal d\'une Column.'],
                    ],
                ],
                [
                    'title' => 'Niveau 6 — Navigation entre écrans',
                    'subtitle' => 'Passer d\'une page à une autre et transmettre des données',
                    'xp_reward' => 150,
                    'content' => "🎯 **Objectif** : naviguer entre plusieurs écrans et transmettre des données.\n\n💡 Flutter empile les écrans dans une pile (stack) gérée par le `Navigator`.\n\n• Aller vers un nouvel écran :\n\n```dart\nNavigator.push(\n  context,\n  MaterialPageRoute(builder: (_) => DetailOffre(titre: 'Comptable OHADA')),\n);\n```\n\n• Revenir en arrière : `Navigator.pop(context);`\n• On peut aussi déclarer des routes nommées dans `MaterialApp(routes: {...})` et utiliser `Navigator.pushNamed(context, '/profil')`.\n\n✅ Pour transmettre une donnée, on la passe au constructeur de l'écran de destination (ex : un titre d'offre d'emploi).\n\n⚠️ `push` ajoute un écran, `pushReplacement` remplace l'écran courant (utile après une connexion réussie pour empêcher le retour à la page de login).",
                    'questions' => [
                        ['question' => 'Quelle méthode ouvre un nouvel écran en l\'ajoutant à la pile ?', 'options' => ['Navigator.pop()', 'Navigator.push()', 'Navigator.close()', 'Navigator.open()'], 'correct' => [1], 'explanation' => '`Navigator.push()` empile un nouvel écran par-dessus l\'actuel.'],
                        ['question' => 'Comment revenir à l\'écran précédent ?', 'options' => ['Navigator.back()', 'Navigator.pop(context)', 'Navigator.remove()', 'Navigator.exit()'], 'correct' => [1], 'explanation' => '`Navigator.pop(context)` retire l\'écran courant et revient au précédent.'],
                        ['question' => 'Quelle méthode utiliser après une connexion réussie pour empêcher le retour à la page de login ?', 'options' => ['push', 'pushReplacement', 'pop', 'pushNamed'], 'correct' => [1], 'explanation' => '`pushReplacement` remplace l\'écran courant, l\'enlevant de la pile de navigation.'],
                    ],
                ],
                [
                    'title' => 'Niveau 7 — Gestion d\'état',
                    'subtitle' => 'Provider, Riverpod et architecture propre',
                    'xp_reward' => 160,
                    'content' => "🎯 **Objectif** : gérer l'état partagé entre plusieurs écrans de façon propre.\n\n💡 `setState()` suffit pour un widget isolé, mais devient ingérable à grande échelle. On utilise des solutions de gestion d'état :\n\n• **Provider** : simple et recommandé par l'équipe Flutter.\n• **Riverpod** : évolution de Provider, plus sûre.\n• **BLoC** : basé sur des flux (streams), pour les grosses applications.\n\n```dart\nclass Panier extends ChangeNotifier {\n  int total = 0;\n  void ajouter(int prix) {\n    total += prix;\n    notifyListeners(); // notifie les widgets abonnés\n  }\n}\n```\n\n✅ `ChangeNotifier` + `notifyListeners()` permettent de prévenir l'UI d'un changement sans recharger tout l'écran.\n\n⚠️ Évite de mélanger plusieurs solutions de gestion d'état dans le même projet : choisis-en une et reste cohérent.",
                    'questions' => [
                        ['question' => 'Quelle solution de gestion d\'état est officiellement recommandée par l\'équipe Flutter pour débuter ?', 'options' => ['Redux', 'Provider', 'MobX', 'GetStorage'], 'correct' => [1], 'explanation' => 'Provider est la solution simple et officiellement recommandée pour démarrer.'],
                        ['question' => 'Quelle méthode notifie les widgets abonnés à un ChangeNotifier ?', 'options' => ['setState()', 'notifyListeners()', 'update()', 'rebuild()'], 'correct' => [1], 'explanation' => '`notifyListeners()` informe tous les widgets écoutant le ChangeNotifier.'],
                        ['question' => 'Quelles affirmations sont vraies sur la gestion d\'état ? (plusieurs réponses)', 'options' => ['setState() convient à un widget isolé', 'BLoC repose sur des streams', 'Provider est obligatoire pour compiler', 'Mélanger plusieurs solutions est déconseillé'], 'correct' => [0, 1, 3], 'explanation' => 'setState convient en local, BLoC repose sur les streams, et mélanger les solutions nuit à la cohérence ; Provider n\'est jamais obligatoire.'],
                    ],
                ],
                [
                    'title' => 'Niveau 8 — Appels API et HTTP',
                    'subtitle' => 'Consommer une API REST et afficher les données',
                    'xp_reward' => 170,
                    'content' => "🎯 **Objectif** : récupérer des données depuis un serveur via une API REST.\n\n💡 On utilise le paquet `http` (ou `dio`) pour faire des requêtes. La réponse JSON est décodée avec `jsonDecode`.\n\n```dart\nimport 'package:http/http.dart' as http;\nimport 'dart:convert';\n\nFuture<List> chargerOffres() async {\n  final url = Uri.parse('https://api.exemple.cm/offres');\n  final reponse = await http.get(url);\n  if (reponse.statusCode == 200) {\n    return jsonDecode(reponse.body);\n  }\n  throw Exception('Erreur de chargement');\n}\n```\n\n• `FutureBuilder` affiche un indicateur de chargement puis les données quand elles arrivent.\n\n✅ Pense à gérer les erreurs réseau : en Afrique, la connexion peut être lente ou instable.\n\n⚠️ Le code 200 signifie succès ; 404 = introuvable, 401 = non autorisé, 500 = erreur serveur.",
                    'questions' => [
                        ['question' => 'Quel paquet est couramment utilisé pour les requêtes HTTP en Flutter ?', 'options' => ['sql', 'http', 'image', 'path'], 'correct' => [1], 'explanation' => 'Le paquet `http` permet d\'envoyer des requêtes GET, POST, etc.'],
                        ['question' => 'Quelle fonction convertit une réponse JSON en objet Dart ?', 'options' => ['jsonEncode', 'jsonDecode', 'parseJson', 'toJson'], 'correct' => [1], 'explanation' => '`jsonDecode` transforme une chaîne JSON en structure Dart (Map ou List).'],
                        ['question' => 'Quel code HTTP indique une requête réussie ?', 'options' => ['404', '500', '200', '401'], 'correct' => [2], 'explanation' => 'Le code 200 signifie que la requête a abouti avec succès.'],
                    ],
                ],
                [
                    'title' => 'Niveau 9 — Stockage local',
                    'subtitle' => 'Sauvegarder des données sur l\'appareil',
                    'xp_reward' => 185,
                    'content' => "🎯 **Objectif** : conserver des données sur l'appareil, même hors connexion.\n\n💡 Plusieurs options selon le besoin :\n\n| Solution | Usage |\n|----------|-------|\n| `shared_preferences` | petites valeurs (token, préférences) |\n| `sqflite` | base de données SQL locale |\n| `hive` | base NoSQL rapide, sans SQL |\n\n```dart\nimport 'package:shared_preferences/shared_preferences.dart';\n\nFuture<void> sauverToken(String token) async {\n  final prefs = await SharedPreferences.getInstance();\n  await prefs.setString('token', token);\n}\n```\n\n✅ Le stockage local est essentiel pour le mode hors ligne, très utile là où la connexion mobile est intermittente.\n\n⚠️ Ne stocke jamais de mot de passe en clair. Pour les données sensibles, utilise `flutter_secure_storage` qui chiffre les valeurs.",
                    'questions' => [
                        ['question' => 'Quelle solution convient pour stocker une petite valeur comme un token ?', 'options' => ['sqflite', 'shared_preferences', 'http', 'provider'], 'correct' => [1], 'explanation' => '`shared_preferences` est idéal pour de petites paires clé-valeur.'],
                        ['question' => 'Quel paquet utiliser pour une base de données SQL locale ?', 'options' => ['hive', 'sqflite', 'dio', 'intl'], 'correct' => [1], 'explanation' => '`sqflite` fournit une base de données SQLite locale.'],
                        ['question' => 'Quel paquet chiffre les données sensibles stockées localement ?', 'options' => ['shared_preferences', 'flutter_secure_storage', 'path_provider', 'sqflite'], 'correct' => [1], 'explanation' => '`flutter_secure_storage` chiffre les valeurs, contrairement à shared_preferences.'],
                    ],
                ],
                [
                    'title' => 'Niveau 10 — Publication et bonnes pratiques UI',
                    'subtitle' => 'Déployer sur les stores et soigner l\'expérience mobile',
                    'xp_reward' => 200,
                    'content' => "🎯 **Objectif** : préparer, publier et polir une application prête pour les utilisateurs.\n\n💡 **Build de production** :\n\n```bash\nflutter build appbundle   # Android (Play Store)\nflutter build ipa         # iOS (App Store)\n```\n\n• Android : crée un compte Google Play Console (frais unique ~25 \$), signe l'app avec une clé de signature, téléverse le fichier `.aab`.\n• iOS : compte Apple Developer (~99 \$/an), build via Xcode, soumission via App Store Connect.\n\n✅ **Bonnes pratiques UI mobile** :\n• Zones tactiles d'au moins 48x48 px.\n• Tester sur petits écrans et connexion lente.\n• Gérer les états : chargement, vide, erreur, succès.\n• Respecter le Material Design (Android) et les Human Interface Guidelines (iOS).\n• Optimiser le poids des images pour économiser la data mobile.\n\n⚠️ Teste toujours sur un vrai appareil avant publication : l'émulateur masque certains problèmes de performance.\n\n🏆 **Félicitations !** Tu maîtrises maintenant le cycle complet Flutter, du Dart à la publication. Tu peux viser des postes de **Développeur Mobile Junior**, travailler en freelance sur des apps de e-commerce, de mobile money ou de services locaux, puis évoluer vers **Développeur Mobile Senior**, **Lead Mobile** ou **architecte d'applications**. Le mobile est l'un des marchés les plus porteurs en Afrique. Continue à construire des projets concrets pour étoffer ton portfolio !",
                    'questions' => [
                        ['question' => 'Quel format de fichier est attendu pour publier une app Android sur le Play Store ?', 'options' => ['.apk seulement', '.aab (App Bundle)', '.ipa', '.exe'], 'correct' => [1], 'explanation' => 'Le Play Store recommande l\'App Bundle `.aab` généré par `flutter build appbundle`.'],
                        ['question' => 'Quelles sont de bonnes pratiques d\'UI mobile ? (plusieurs réponses)', 'options' => ['Zones tactiles d\'au moins 48 px', 'Gérer les états de chargement et d\'erreur', 'Ignorer les petits écrans', 'Optimiser le poids des images'], 'correct' => [0, 1, 3], 'explanation' => 'Des zones tactiles suffisantes, la gestion des états et l\'optimisation des images améliorent l\'expérience ; ignorer les petits écrans est une erreur.'],
                        ['question' => 'Pourquoi tester sur un vrai appareil avant publication ?', 'options' => ['C\'est obligatoire pour compiler', 'L\'émulateur masque des problèmes de performance', 'Pour augmenter le XP', 'Pour réduire la taille de l\'app'], 'correct' => [1], 'explanation' => 'Un appareil réel révèle des soucis de performance et d\'ergonomie invisibles sur émulateur.'],
                    ],
                ],
            ],
        ]);

        $this->command->info('  ✓ Roadmap Flutter créée (10 niveaux).');
    }
}
