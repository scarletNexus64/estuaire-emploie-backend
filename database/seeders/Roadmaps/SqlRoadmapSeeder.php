<?php

namespace Database\Seeders\Roadmaps;

use Illuminate\Database\Seeder;

/**
 * Roadmap SQL — des bases jusqu'aux requêtes avancées et l'optimisation.
 * Contenu rédigé (tips), QCM de validation par niveau.
 */
class SqlRoadmapSeeder extends Seeder
{
    use RoadmapSeederHelper;

    public function run(): void
    {
        $this->createRoadmap([
            'title' => 'Deviens Administrateur de Bases de Données',
            'slug' => 'maitriser-le-sql',
            'domain' => 'sql',
            'description' => "Apprends à interroger, manipuler et optimiser des bases de données relationnelles. Du SELECT le plus simple aux jointures, sous-requêtes, index et transactions.",
            'objectives' => "Comprendre le modèle relationnel\nÉcrire des requêtes SELECT efficaces\nMaîtriser les jointures et agrégations\nManipuler les données (INSERT/UPDATE/DELETE)\nOptimiser ses requêtes avec les index\nGérer les transactions et l'intégrité",
            'icon' => '🗄️',
            'color' => '#0EA5E9',
            'difficulty' => 'beginner',
            'required_packs' => [],
            'pass_threshold' => 70,
            'order' => 1,
            'levels' => [
                [
                    'title' => 'Niveau 1 — Qu\'est-ce qu\'une base de données ?',
                    'subtitle' => 'Le vocabulaire de base',
                    'xp_reward' => 100,
                    'content' => "🎯 **Objectif** : comprendre ce qu'est une base de données relationnelle.\n\n💡 Une **base de données** stocke des informations organisées en **tables**. Une table ressemble à un tableau Excel :\n• chaque **ligne** (row) = un enregistrement (ex. un client)\n• chaque **colonne** (column) = un champ (ex. nom, email)\n\n✅ Le **SQL** (Structured Query Language) est le langage qu'on utilise pour parler à la base : lire, ajouter, modifier ou supprimer des données.\n\n✅ Exemple de table `clients` :\n| id | nom    | ville    |\n|----|--------|----------|\n| 1  | Awa    | Douala   |\n| 2  | Steve  | Yaoundé  |\n\n💡 La colonne `id` est une **clé primaire** : un identifiant unique pour chaque ligne.",
                    'questions' => [
                        [
                            'question' => 'Dans une table SQL, que représente une ligne (row) ?',
                            'options' => ['Un champ', 'Un enregistrement', 'Une base de données entière', 'Une colonne'],
                            'correct' => [1],
                            'explanation' => 'Une ligne représente un enregistrement complet (ex. un client).',
                        ],
                        [
                            'question' => 'À quoi sert une clé primaire (primary key) ?',
                            'options' => ['À trier les données', 'À identifier de façon unique chaque ligne', 'À supprimer une table', 'À chiffrer les données'],
                            'correct' => [1],
                            'explanation' => 'La clé primaire garantit l\'unicité de chaque enregistrement.',
                        ],
                        [
                            'question' => 'Que signifie SQL ?',
                            'options' => ['Simple Query Language', 'Structured Query Language', 'System Query Logic', 'Sequential Query List'],
                            'correct' => [1],
                            'explanation' => 'SQL = Structured Query Language.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 2 — Lire des données avec SELECT',
                    'subtitle' => 'La requête la plus utilisée',
                    'xp_reward' => 100,
                    'content' => "🎯 **Objectif** : récupérer des données avec `SELECT`.\n\n✅ La syntaxe de base :\n```\nSELECT colonne1, colonne2\nFROM nom_table;\n```\n\n✅ Pour tout récupérer, on utilise `*` :\n```\nSELECT * FROM clients;\n```\n\n💡 Bonne pratique : évite `SELECT *` en production. Sélectionne uniquement les colonnes dont tu as besoin — c'est plus rapide et plus clair.\n\n✅ Exemple : récupérer seulement le nom et la ville :\n```\nSELECT nom, ville FROM clients;\n```",
                    'questions' => [
                        [
                            'question' => 'Quelle requête récupère TOUTES les colonnes de la table `clients` ?',
                            'options' => ['GET * FROM clients;', 'SELECT * FROM clients;', 'SELECT clients;', 'READ ALL clients;'],
                            'correct' => [1],
                            'explanation' => '`SELECT * FROM clients;` renvoie toutes les colonnes.',
                        ],
                        [
                            'question' => 'Pourquoi éviter SELECT * en production ?',
                            'options' => ['C\'est interdit par SQL', 'Cela renvoie souvent plus de données que nécessaire (moins performant)', 'Cela supprime des données', 'Cela ne fonctionne qu\'avec MySQL'],
                            'correct' => [1],
                            'explanation' => 'Sélectionner uniquement les colonnes utiles est plus performant et lisible.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 3 — Filtrer avec WHERE',
                    'subtitle' => 'Cibler les bonnes lignes',
                    'xp_reward' => 120,
                    'content' => "🎯 **Objectif** : filtrer les résultats avec `WHERE`.\n\n✅ `WHERE` ajoute une condition :\n```\nSELECT * FROM clients\nWHERE ville = 'Douala';\n```\n\n✅ Opérateurs courants : `=`, `!=` (ou `<>`), `<`, `>`, `<=`, `>=`.\n\n✅ Combiner des conditions avec `AND` / `OR` :\n```\nSELECT * FROM clients\nWHERE ville = 'Douala' AND age > 25;\n```\n\n💡 `LIKE` permet une recherche partielle : `WHERE nom LIKE 'A%'` trouve tous les noms qui commencent par A. Le `%` remplace n'importe quelle suite de caractères.",
                    'questions' => [
                        [
                            'question' => 'Quelle clause filtre les lignes selon une condition ?',
                            'options' => ['FILTER', 'WHERE', 'HAVING', 'SELECT'],
                            'correct' => [1],
                            'explanation' => '`WHERE` filtre les lignes avant agrégation.',
                        ],
                        [
                            'question' => 'Que trouve `WHERE nom LIKE \'A%\'` ?',
                            'options' => ['Les noms contenant A', 'Les noms se terminant par A', 'Les noms commençant par A', 'Le nom exact "A%"'],
                            'correct' => [2],
                            'explanation' => '`A%` : commence par A, suivi de n\'importe quoi.',
                        ],
                        [
                            'question' => 'Quels opérateurs combinent plusieurs conditions ? (plusieurs réponses)',
                            'options' => ['AND', 'PLUS', 'OR', 'WITH'],
                            'correct' => [0, 2],
                            'explanation' => '`AND` et `OR` combinent des conditions.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 4 — Trier et limiter (ORDER BY, LIMIT)',
                    'subtitle' => 'Organiser les résultats',
                    'xp_reward' => 120,
                    'content' => "🎯 **Objectif** : trier et limiter les résultats.\n\n✅ `ORDER BY` trie :\n```\nSELECT * FROM clients\nORDER BY nom ASC;\n```\n• `ASC` = croissant (par défaut), `DESC` = décroissant.\n\n✅ `LIMIT` réduit le nombre de lignes renvoyées :\n```\nSELECT * FROM clients\nORDER BY age DESC\nLIMIT 5;\n```\n💡 Cette requête renvoie les 5 clients les plus âgés. Très utile pour des classements (\"top 10\").",
                    'questions' => [
                        [
                            'question' => 'Comment trier par âge décroissant ?',
                            'options' => ['ORDER age DOWN', 'ORDER BY age DESC', 'SORT age DESC', 'ORDER BY age ASC'],
                            'correct' => [1],
                            'explanation' => '`ORDER BY age DESC` trie du plus grand au plus petit.',
                        ],
                        [
                            'question' => 'Que fait LIMIT 5 ?',
                            'options' => ['Limite à 5 colonnes', 'Renvoie au maximum 5 lignes', 'Supprime 5 lignes', 'Saute les 5 premières lignes'],
                            'correct' => [1],
                            'explanation' => '`LIMIT 5` plafonne le résultat à 5 lignes.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 5 — Agréger (COUNT, SUM, AVG, GROUP BY)',
                    'subtitle' => 'Calculer sur des groupes',
                    'xp_reward' => 150,
                    'content' => "🎯 **Objectif** : calculer des statistiques sur les données.\n\n✅ Fonctions d'agrégation :\n• `COUNT(*)` : nombre de lignes\n• `SUM(colonne)` : somme\n• `AVG(colonne)` : moyenne\n• `MIN` / `MAX` : minimum / maximum\n\n```\nSELECT COUNT(*) FROM clients;\n```\n\n✅ `GROUP BY` regroupe avant de calculer :\n```\nSELECT ville, COUNT(*) AS nb\nFROM clients\nGROUP BY ville;\n```\n💡 Cette requête compte les clients par ville.\n\n✅ `HAVING` filtre APRÈS l'agrégation (contrairement à `WHERE` qui filtre avant) :\n```\nGROUP BY ville HAVING COUNT(*) > 10\n```",
                    'questions' => [
                        [
                            'question' => 'Quelle fonction calcule une moyenne ?',
                            'options' => ['SUM()', 'AVG()', 'MEAN()', 'COUNT()'],
                            'correct' => [1],
                            'explanation' => '`AVG()` calcule la moyenne.',
                        ],
                        [
                            'question' => 'Quelle clause filtre APRÈS une agrégation GROUP BY ?',
                            'options' => ['WHERE', 'HAVING', 'FILTER', 'GROUP'],
                            'correct' => [1],
                            'explanation' => '`HAVING` s\'applique après le GROUP BY ; `WHERE` avant.',
                        ],
                        [
                            'question' => 'Que fait `GROUP BY ville` ?',
                            'options' => ['Trie par ville', 'Regroupe les lignes par ville pour agréger', 'Supprime les doublons de ville', 'Renomme la colonne ville'],
                            'correct' => [1],
                            'explanation' => 'GROUP BY crée un groupe par valeur distincte de ville.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 6 — Les jointures (JOIN)',
                    'subtitle' => 'Relier plusieurs tables',
                    'xp_reward' => 180,
                    'content' => "🎯 **Objectif** : combiner des données de plusieurs tables.\n\n💡 Les données sont réparties dans plusieurs tables liées par des **clés étrangères**. Ex. une table `commandes` avec une colonne `client_id` qui pointe vers `clients.id`.\n\n✅ `INNER JOIN` : ne garde que les lignes ayant une correspondance dans les deux tables :\n```\nSELECT c.nom, co.montant\nFROM clients c\nINNER JOIN commandes co ON co.client_id = c.id;\n```\n\n✅ `LEFT JOIN` : garde TOUTES les lignes de la table de gauche, même sans correspondance (valeurs NULL à droite).\n\n💡 Astuce : les **alias** (`clients c`) raccourcissent l'écriture.",
                    'questions' => [
                        [
                            'question' => 'Quelle jointure ne garde que les lignes ayant une correspondance dans les deux tables ?',
                            'options' => ['LEFT JOIN', 'INNER JOIN', 'FULL JOIN', 'CROSS JOIN'],
                            'correct' => [1],
                            'explanation' => 'INNER JOIN ne renvoie que les correspondances communes.',
                        ],
                        [
                            'question' => 'Une clé étrangère (foreign key) sert à...',
                            'options' => ['Chiffrer une colonne', 'Lier une table à une autre', 'Trier les résultats', 'Créer un index unique'],
                            'correct' => [1],
                            'explanation' => 'Elle référence la clé primaire d\'une autre table.',
                        ],
                        [
                            'question' => 'Que garde un LEFT JOIN ?',
                            'options' => ['Seulement les correspondances', 'Toutes les lignes de la table de gauche', 'Toutes les lignes de la table de droite', 'Aucune ligne'],
                            'correct' => [1],
                            'explanation' => 'LEFT JOIN conserve toute la table de gauche, NULL à droite si pas de match.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 7 — Modifier les données (INSERT, UPDATE, DELETE)',
                    'subtitle' => 'Écrire dans la base',
                    'xp_reward' => 150,
                    'content' => "🎯 **Objectif** : ajouter, modifier et supprimer des données.\n\n✅ `INSERT` ajoute une ligne :\n```\nINSERT INTO clients (nom, ville)\nVALUES ('Marie', 'Bafoussam');\n```\n\n✅ `UPDATE` modifie des lignes existantes :\n```\nUPDATE clients\nSET ville = 'Kribi'\nWHERE id = 2;\n```\n\n✅ `DELETE` supprime :\n```\nDELETE FROM clients WHERE id = 2;\n```\n\n⚠️ **DANGER** : un `UPDATE` ou `DELETE` SANS `WHERE` affecte TOUTES les lignes de la table. Vérifie toujours ta clause `WHERE` avant d'exécuter !",
                    'questions' => [
                        [
                            'question' => 'Que se passe-t-il si tu fais `DELETE FROM clients;` sans WHERE ?',
                            'options' => ['Rien', 'La requête échoue', 'Toutes les lignes sont supprimées', 'Seule la première ligne est supprimée'],
                            'correct' => [2],
                            'explanation' => 'Sans WHERE, DELETE vide toute la table. Toujours vérifier le WHERE !',
                        ],
                        [
                            'question' => 'Quelle commande ajoute une nouvelle ligne ?',
                            'options' => ['ADD', 'INSERT INTO', 'UPDATE', 'CREATE'],
                            'correct' => [1],
                            'explanation' => '`INSERT INTO ... VALUES (...)` ajoute une ligne.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 8 — Index et optimisation',
                    'subtitle' => 'Des requêtes rapides',
                    'xp_reward' => 200,
                    'content' => "🎯 **Objectif** : comprendre pourquoi certaines requêtes sont lentes et comment les accélérer.\n\n💡 Un **index** est comme l'index d'un livre : il permet à la base de retrouver des lignes sans tout parcourir. Sans index, une recherche lit toute la table (\"full table scan\").\n\n✅ Créer un index sur une colonne souvent filtrée :\n```\nCREATE INDEX idx_clients_ville ON clients(ville);\n```\n\n✅ Bonnes pratiques :\n• Indexer les colonnes utilisées dans `WHERE`, `JOIN`, `ORDER BY`.\n• Ne pas sur-indexer : chaque index ralentit les écritures (INSERT/UPDATE).\n• Utiliser `EXPLAIN` devant une requête pour voir comment la base l'exécute.\n\n💡 `EXPLAIN SELECT ...` te montre si un index est utilisé ou non.",
                    'questions' => [
                        [
                            'question' => 'À quoi sert un index en SQL ?',
                            'options' => ['À chiffrer les données', 'À accélérer la recherche de lignes', 'À empêcher les doublons uniquement', 'À sauvegarder la base'],
                            'correct' => [1],
                            'explanation' => 'Un index accélère la lecture en évitant un parcours complet de la table.',
                        ],
                        [
                            'question' => 'Quel est l\'inconvénient d\'avoir trop d\'index ?',
                            'options' => ['Les lectures deviennent plus lentes', 'Les écritures (INSERT/UPDATE) deviennent plus lentes', 'La base ne démarre plus', 'Les jointures sont interdites'],
                            'correct' => [1],
                            'explanation' => 'Chaque index doit être maintenu à jour à chaque écriture.',
                        ],
                        [
                            'question' => 'Quelle commande montre comment une requête est exécutée ?',
                            'options' => ['DESCRIBE', 'EXPLAIN', 'SHOW PLAN', 'ANALYZE ONLY'],
                            'correct' => [1],
                            'explanation' => '`EXPLAIN` révèle le plan d\'exécution (et l\'usage des index).',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 9 — Transactions et intégrité',
                    'subtitle' => 'Des opérations fiables',
                    'xp_reward' => 200,
                    'content' => "🎯 **Objectif** : garantir que des opérations multiples réussissent toutes... ou aucune.\n\n💡 Une **transaction** regroupe plusieurs requêtes en une unité \"tout ou rien\". Exemple classique : un virement bancaire (débiter A ET créditer B). Si l'une échoue, on annule tout.\n\n✅ Syntaxe :\n```\nBEGIN;\nUPDATE comptes SET solde = solde - 100 WHERE id = 1;\nUPDATE comptes SET solde = solde + 100 WHERE id = 2;\nCOMMIT;   -- valide définitivement\n```\nEn cas de problème : `ROLLBACK;` annule tout.\n\n✅ Propriétés **ACID** : Atomicité, Cohérence, Isolation, Durabilité — les garanties d'une bonne base relationnelle.",
                    'questions' => [
                        [
                            'question' => 'Que fait COMMIT dans une transaction ?',
                            'options' => ['Annule les changements', 'Valide définitivement les changements', 'Démarre la transaction', 'Crée un index'],
                            'correct' => [1],
                            'explanation' => 'COMMIT applique définitivement les modifications de la transaction.',
                        ],
                        [
                            'question' => 'Que signifie le "A" de ACID ?',
                            'options' => ['Availability', 'Atomicité', 'Authentication', 'Access'],
                            'correct' => [1],
                            'explanation' => 'Atomicité : la transaction est indivisible (tout ou rien).',
                        ],
                        [
                            'question' => 'Quelle commande annule les changements d\'une transaction ?',
                            'options' => ['CANCEL', 'ROLLBACK', 'UNDO', 'RESET'],
                            'correct' => [1],
                            'explanation' => '`ROLLBACK` annule tout ce qui a été fait depuis BEGIN.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 10 — Sous-requêtes et vues',
                    'subtitle' => 'Aller plus loin',
                    'xp_reward' => 220,
                    'content' => "🎯 **Objectif** : structurer des requêtes complexes.\n\n✅ Une **sous-requête** est une requête imbriquée dans une autre :\n```\nSELECT nom FROM clients\nWHERE id IN (\n    SELECT client_id FROM commandes WHERE montant > 1000\n);\n```\n💡 Ici on récupère les clients ayant passé une commande > 1000.\n\n✅ Une **vue** (VIEW) est une requête enregistrée comme une table virtuelle :\n```\nCREATE VIEW gros_clients AS\nSELECT * FROM clients WHERE id IN (...);\n```\nOn l'interroge ensuite comme une table : `SELECT * FROM gros_clients;`.\n\n💡 Les vues simplifient les requêtes répétitives et encapsulent la logique métier.\n\n🏆 Bravo ! Tu maîtrises maintenant les fondamentaux du SQL. Continue à pratiquer sur de vraies bases.",
                    'questions' => [
                        [
                            'question' => 'Qu\'est-ce qu\'une sous-requête ?',
                            'options' => ['Une requête lente', 'Une requête imbriquée dans une autre', 'Une requête sur plusieurs bases', 'Une requête sans résultat'],
                            'correct' => [1],
                            'explanation' => 'Une sous-requête est une requête à l\'intérieur d\'une autre requête.',
                        ],
                        [
                            'question' => 'Qu\'est-ce qu\'une VIEW ?',
                            'options' => ['Une copie physique d\'une table', 'Une requête enregistrée utilisable comme une table virtuelle', 'Un index spécial', 'Un type de transaction'],
                            'correct' => [1],
                            'explanation' => 'Une vue est une requête nommée et réutilisable, comme une table virtuelle.',
                        ],
                    ],
                ],
            ],
        ]);

        $this->command->info('  ✓ Roadmap SQL créée (10 niveaux).');
    }
}
