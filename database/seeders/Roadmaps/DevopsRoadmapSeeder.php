<?php

namespace Database\Seeders\Roadmaps;

use Illuminate\Database\Seeder;

/**
 * Roadmap DevOps — de la culture aux pratiques avancées.
 * Linux, Git, réseaux, conteneurs, CI/CD, IaC, cloud, monitoring et sécurité.
 * Contenu rédigé (tips), QCM de validation par niveau.
 */
class DevopsRoadmapSeeder extends Seeder
{
    use RoadmapSeederHelper;

    public function run(): void
    {
        $this->createRoadmap([
            'title' => 'Ingénieur DevOps : de zéro à la prod',
            'slug' => 'maitriser-le-devops',
            'domain' => 'developpement',
            'description' => "Apprends la culture et les outils DevOps : Linux, Git, réseaux, conteneurs Docker, orchestration Kubernetes, pipelines CI/CD, Infrastructure as Code, cloud, monitoring et sécurité des secrets.",
            'objectives' => "Comprendre la culture DevOps\nMaîtriser Linux et la ligne de commande\nGérer du code avec Git\nConteneuriser des applications avec Docker\nOrchestrer avec Docker Compose et Kubernetes\nAutomatiser le build/test/deploy via CI/CD\nProvisionner l'infrastructure avec Terraform/Ansible\nSuperviser et sécuriser ses systèmes",
            'icon' => '🚀',
            'color' => '#F59E0B',
            'difficulty' => 'advanced',
            'required_packs' => [],
            'pass_threshold' => 70,
            'order' => 5,
            'levels' => [
                [
                    'title' => 'Niveau 1 — Qu\'est-ce que le DevOps ?',
                    'subtitle' => 'Une culture, pas un poste',
                    'xp_reward' => 100,
                    'content' => "🎯 **Objectif** : comprendre ce qu'est vraiment le DevOps.\n\n💡 Le **DevOps** est avant tout une **culture** qui rapproche les équipes de **Dev** (développement) et d'**Ops** (exploitation/opérations). Historiquement, ces deux équipes travaillaient en silo : les devs livraient du code « par-dessus le mur », et les ops devaient le faire tourner en production. Résultat : des conflits et des déploiements lents.\n\n✅ Les objectifs du DevOps :\n• **Livrer plus vite** et plus souvent (délai entre une idée et sa mise en prod).\n• **Automatiser** tout ce qui est répétitif (build, tests, déploiement).\n• **Améliorer la fiabilité** grâce au feedback continu et à la mesure.\n• Casser les silos : responsabilité **partagée** (« you build it, you run it »).\n\n✅ Les piliers culturels, souvent résumés par l'acronyme **CALMS** :\n• **C**ulture (collaboration), **A**utomation (automatisation), **L**ean (flux, petits lots), **M**easurement (métriques), **S**haring (partage).\n\n💡 Le DevOps n'est PAS un simple outil ni un titre de poste : c'est une façon de travailler. Les outils (Docker, CI/CD, Terraform…) ne sont que des moyens au service de cette culture.",
                    'questions' => [
                        [
                            'question' => 'Le DevOps est avant tout...',
                            'options' => ['Un logiciel à installer', 'Une culture rapprochant Dev et Ops', 'Un langage de programmation', 'Un type de serveur'],
                            'correct' => [1],
                            'explanation' => 'Le DevOps est d\'abord une culture de collaboration entre développement et exploitation.',
                        ],
                        [
                            'question' => 'Quels sont des objectifs clés du DevOps ? (plusieurs réponses)',
                            'options' => ['Livrer plus vite et plus souvent', 'Automatiser les tâches répétitives', 'Supprimer tous les tests', 'Améliorer la fiabilité grâce au feedback'],
                            'correct' => [0, 1, 3],
                            'explanation' => 'Le DevOps vise la rapidité, l\'automatisation et la fiabilité — surtout pas à supprimer les tests.',
                        ],
                        [
                            'question' => 'Que résume l\'acronyme CALMS ?',
                            'options' => ['Des langages de programmation', 'Les piliers culturels du DevOps', 'Des distributions Linux', 'Des fournisseurs cloud'],
                            'correct' => [1],
                            'explanation' => 'CALMS = Culture, Automation, Lean, Measurement, Sharing.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 2 — Linux & ligne de commande',
                    'subtitle' => 'Le terrain de jeu du DevOps',
                    'xp_reward' => 120,
                    'content' => "🎯 **Objectif** : être à l'aise dans un terminal Linux.\n\n💡 La grande majorité des serveurs tournent sous **Linux**. Savoir naviguer et agir en ligne de commande est indispensable en DevOps.\n\n✅ Naviguer et explorer :\n```\npwd            # affiche le dossier courant\nls -la         # liste les fichiers (dont cachés)\ncd /var/log    # change de dossier\ncat fichier    # affiche le contenu d'un fichier\n```\n\n✅ Manipuler fichiers et dossiers :\n```\nmkdir projet       # crée un dossier\ncp a.txt b.txt     # copie\nmv a.txt /tmp/     # déplace / renomme\nrm -rf vieux/      # supprime (⚠️ irréversible)\n```\n\n✅ Chercher et filtrer (très courant) :\n```\ngrep \"error\" app.log       # cherche un motif dans un fichier\ntail -f app.log            # suit les logs en temps réel\nps aux | grep nginx        # liste les processus nginx\n```\n\n✅ Permissions et droits :\n```\nchmod +x script.sh   # rend un script exécutable\nchown user:group f   # change le propriétaire\nsudo commande        # exécute en root (admin)\n```\n\n⚠️ `rm -rf /` peut détruire tout le système : manipule `rm -rf` avec une extrême prudence.",
                    'questions' => [
                        [
                            'question' => 'Quelle commande affiche les logs en temps réel ?',
                            'options' => ['cat app.log', 'tail -f app.log', 'ls -la app.log', 'grep app.log'],
                            'correct' => [1],
                            'explanation' => '`tail -f` suit le fichier et affiche les nouvelles lignes au fil de l\'eau.',
                        ],
                        [
                            'question' => 'Que fait `chmod +x script.sh` ?',
                            'options' => ['Supprime le script', 'Rend le script exécutable', 'Change le propriétaire', 'Affiche le script'],
                            'correct' => [1],
                            'explanation' => '`chmod +x` ajoute le droit d\'exécution au fichier.',
                        ],
                        [
                            'question' => 'Quelles commandes permettent de chercher/filtrer du texte ? (plusieurs réponses)',
                            'options' => ['grep', 'mkdir', 'tail', 'cd'],
                            'correct' => [0, 2],
                            'explanation' => '`grep` filtre par motif et `tail` affiche la fin (ou suit) un fichier.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 3 — Git & gestion de versions',
                    'subtitle' => 'Collaborer sans se marcher dessus',
                    'xp_reward' => 130,
                    'content' => "🎯 **Objectif** : versionner du code et collaborer avec Git.\n\n💡 **Git** est un système de **gestion de versions** : il enregistre l'historique des modifications et permet à plusieurs personnes de travailler sur le même code sans conflit.\n\n✅ Le cycle de base :\n```\ngit init                 # initialise un dépôt\ngit status               # voir l'état des fichiers\ngit add .                # prépare les changements (staging)\ngit commit -m \"message\"  # enregistre un instantané\ngit push origin main     # envoie vers le dépôt distant\n```\n\n✅ Les **branches** permettent de travailler en parallèle sans toucher au code stable :\n```\ngit branch feature-login     # crée une branche\ngit checkout feature-login   # bascule dessus\n# (ou git switch feature-login)\ngit merge feature-login      # fusionne dans la branche courante\n```\n\n💡 Un **workflow** courant : on crée une branche par fonctionnalité, on ouvre une **Pull/Merge Request**, l'équipe relit le code (code review), puis on fusionne dans `main`.\n\n⚠️ En cas de **conflit de merge**, Git marque les zones en désaccord avec `<<<<<<<`, `=======`, `>>>>>>>`. Il faut éditer le fichier à la main pour choisir la bonne version, puis valider.\n\n✅ `git pull` récupère et fusionne les changements distants ; `git clone` copie un dépôt complet.",
                    'questions' => [
                        [
                            'question' => 'Dans quel ordre versionne-t-on un changement avec Git ?',
                            'options' => ['commit puis add', 'add puis commit', 'push puis commit', 'merge puis add'],
                            'correct' => [1],
                            'explanation' => 'On prépare avec `git add` (staging) puis on enregistre avec `git commit`.',
                        ],
                        [
                            'question' => 'À quoi sert une branche Git ?',
                            'options' => ['À supprimer l\'historique', 'À travailler en parallèle sans toucher au code stable', 'À compresser le dépôt', 'À chiffrer le code'],
                            'correct' => [1],
                            'explanation' => 'Une branche isole le travail (ex. une fonctionnalité) avant de le fusionner.',
                        ],
                        [
                            'question' => 'Quelle commande envoie les commits locaux vers le dépôt distant ?',
                            'options' => ['git pull', 'git push', 'git add', 'git status'],
                            'correct' => [1],
                            'explanation' => '`git push` publie les commits locaux sur le dépôt distant ; `git pull` fait l\'inverse.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 4 — Réseaux de base',
                    'subtitle' => 'Comment les machines se parlent',
                    'xp_reward' => 140,
                    'content' => "🎯 **Objectif** : comprendre les notions réseau indispensables au DevOps.\n\n💡 Une **adresse IP** identifie une machine sur un réseau (ex. `192.168.1.10` en IPv4). Sur Internet, chaque serveur a une IP publique.\n\n💡 Le **DNS** (Domain Name System) est l'annuaire d'Internet : il traduit un nom de domaine lisible (`exemple.com`) en adresse IP. Sans DNS, il faudrait retenir des suites de chiffres.\n\n✅ Un **port** identifie un service précis sur une machine. Quelques ports standards à connaître :\n• `80` → HTTP\n• `443` → HTTPS\n• `22` → SSH\n• `5432` → PostgreSQL\n• `3306` → MySQL\n\n💡 **HTTP** est le protocole du web ; **HTTPS** est sa version chiffrée (TLS/SSL), qui protège les données en transit. Aujourd'hui, HTTPS est la norme.\n\n✅ Commandes utiles pour diagnostiquer le réseau :\n```\nping google.com       # teste la connectivité\ncurl https://api.x.com  # fait une requête HTTP\nnslookup exemple.com  # résolution DNS\nnetstat -tlnp         # ports en écoute\nssh user@serveur      # connexion distante sécurisée\n```\n\n💡 Une requête web typique : le navigateur résout le **DNS** → ouvre une connexion sur le **port 443** → échange en **HTTPS**.",
                    'questions' => [
                        [
                            'question' => 'À quoi sert le DNS ?',
                            'options' => ['À chiffrer les données', 'À traduire un nom de domaine en adresse IP', 'À ouvrir des ports', 'À compresser le trafic'],
                            'correct' => [1],
                            'explanation' => 'Le DNS résout un nom de domaine (exemple.com) en adresse IP.',
                        ],
                        [
                            'question' => 'Quel port est associé au HTTPS ?',
                            'options' => ['80', '22', '443', '3306'],
                            'correct' => [2],
                            'explanation' => 'HTTPS utilise le port 443 ; HTTP utilise le 80.',
                        ],
                        [
                            'question' => 'Quelles différences entre HTTP et HTTPS sont vraies ? (plusieurs réponses)',
                            'options' => ['HTTPS chiffre les données en transit', 'HTTPS utilise TLS/SSL', 'HTTP est plus sécurisé que HTTPS', 'HTTPS est devenu la norme du web'],
                            'correct' => [0, 1, 3],
                            'explanation' => 'HTTPS = HTTP + chiffrement TLS/SSL ; c\'est la norme. HTTP, lui, n\'est pas chiffré.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 5 — Conteneurs avec Docker',
                    'subtitle' => 'Empaqueter une application',
                    'xp_reward' => 160,
                    'content' => "🎯 **Objectif** : comprendre et utiliser les conteneurs Docker.\n\n💡 Un **conteneur** empaquette une application AVEC tout ce dont elle a besoin (code, dépendances, config). Résultat : « ça marche sur ma machine » devient « ça marche partout ». Contrairement à une VM, un conteneur partage le noyau de l'hôte : il est **léger** et **démarre en quelques secondes**.\n\n✅ Trois notions clés :\n• **Image** : un modèle figé en lecture seule (ex. `nginx`, `node:20`).\n• **Conteneur** : une instance en cours d'exécution d'une image.\n• **Dockerfile** : la recette qui décrit comment construire une image.\n\n✅ Commandes essentielles :\n```\ndocker pull nginx              # télécharge une image\ndocker run -d -p 8080:80 nginx # lance un conteneur (port 8080→80)\ndocker ps                     # liste les conteneurs actifs\ndocker logs <id>              # affiche les logs\ndocker exec -it <id> bash     # ouvre un shell dans le conteneur\ndocker stop <id>              # arrête le conteneur\n```\n\n✅ Un **Dockerfile** minimal pour une app Node.js :\n```\nFROM node:20-alpine\nWORKDIR /app\nCOPY package.json .\nRUN npm install\nCOPY . .\nEXPOSE 3000\nCMD [\"node\", \"server.js\"]\n```\nOn le construit avec :\n```\ndocker build -t mon-app .\n```\n\n💡 `-p 8080:80` mappe le port 8080 de la machine vers le port 80 du conteneur.",
                    'questions' => [
                        [
                            'question' => 'Quelle est la différence entre une image et un conteneur ?',
                            'options' => ['Aucune, c\'est synonyme', 'L\'image est un modèle figé, le conteneur une instance en cours d\'exécution', 'Le conteneur est plus lourd qu\'une VM', 'L\'image s\'exécute, le conteneur se construit'],
                            'correct' => [1],
                            'explanation' => 'L\'image est un modèle en lecture seule ; le conteneur est une instance lancée de cette image.',
                        ],
                        [
                            'question' => 'Quel fichier décrit comment construire une image Docker ?',
                            'options' => ['docker-compose.yml', 'Dockerfile', 'package.json', '.dockerignore'],
                            'correct' => [1],
                            'explanation' => 'Le `Dockerfile` contient les instructions de construction (FROM, COPY, RUN, CMD…).',
                        ],
                        [
                            'question' => 'Pourquoi un conteneur est-il plus léger qu\'une VM ?',
                            'options' => ['Il embarque son propre noyau complet', 'Il partage le noyau de l\'hôte', 'Il n\'a pas de réseau', 'Il ne peut rien exécuter'],
                            'correct' => [1],
                            'explanation' => 'Le conteneur partage le noyau de l\'hôte, contrairement à la VM qui embarque un OS complet.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 6 — Orchestration',
                    'subtitle' => 'Compose et introduction à Kubernetes',
                    'xp_reward' => 180,
                    'content' => "🎯 **Objectif** : gérer plusieurs conteneurs ensemble, puis passer à l'échelle.\n\n💡 Une vraie application a souvent plusieurs services (API, base de données, cache…). Les lancer un par un à la main devient vite ingérable : c'est le rôle de l'**orchestration**.\n\n✅ **Docker Compose** décrit plusieurs conteneurs dans un seul fichier `docker-compose.yml` :\n```\nservices:\n  web:\n    build: .\n    ports:\n      - \"8080:3000\"\n  db:\n    image: postgres:16\n    environment:\n      POSTGRES_PASSWORD: secret\n```\nOn lance tout d'un coup :\n```\ndocker compose up -d   # démarre tous les services\ndocker compose down    # arrête et nettoie\n```\n\n💡 **Kubernetes** (souvent abrégé **K8s**) va beaucoup plus loin : il orchestre des conteneurs sur un **cluster** de plusieurs machines. Il gère automatiquement :\n• le **scaling** (augmenter/réduire le nombre de copies selon la charge),\n• le **self-healing** (redémarrer un conteneur qui plante),\n• le **load balancing** (répartir le trafic).\n\n✅ Vocabulaire Kubernetes de base :\n• **Pod** : la plus petite unité, un ou plusieurs conteneurs.\n• **Deployment** : décrit l'état désiré (ex. « 3 copies de l'app »).\n• **Service** : expose les pods sur le réseau de façon stable.\n\n💡 Compose est parfait en local et pour de petits déploiements ; Kubernetes brille à grande échelle et en production critique.",
                    'questions' => [
                        [
                            'question' => 'À quoi sert Docker Compose ?',
                            'options' => ['À construire une seule image', 'À décrire et lancer plusieurs conteneurs ensemble', 'À chiffrer les volumes', 'À remplacer Git'],
                            'correct' => [1],
                            'explanation' => 'Compose orchestre plusieurs services définis dans un fichier docker-compose.yml.',
                        ],
                        [
                            'question' => 'Dans Kubernetes, quelle est la plus petite unité déployable ?',
                            'options' => ['Le Service', 'Le Pod', 'Le Cluster', 'Le Node'],
                            'correct' => [1],
                            'explanation' => 'Le Pod est la plus petite unité ; il contient un ou plusieurs conteneurs.',
                        ],
                        [
                            'question' => 'Quelles capacités Kubernetes apporte-t-il automatiquement ? (plusieurs réponses)',
                            'options' => ['Scaling (mise à l\'échelle)', 'Self-healing (redémarrage des conteneurs)', 'Load balancing', 'Écriture du code applicatif'],
                            'correct' => [0, 1, 2],
                            'explanation' => 'K8s gère scaling, self-healing et load balancing — mais n\'écrit pas votre code.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 7 — CI/CD',
                    'subtitle' => 'Automatiser build, test et déploiement',
                    'xp_reward' => 190,
                    'content' => "🎯 **Objectif** : automatiser la chaîne du code jusqu'à la production.\n\n💡 **CI/CD** est le cœur de l'automatisation DevOps :\n• **CI** = **Continuous Integration** : à chaque push, on **construit** et on **teste** automatiquement le code. On détecte les régressions tôt.\n• **CD** = **Continuous Delivery/Deployment** : on **déploie** automatiquement (en préprod, voire en prod) une fois les tests verts.\n\n✅ Un **pipeline** est une suite d'étapes (stages) déclenchée par un événement (push, merge request). Étapes classiques :\n```\nbuild → test → package → deploy\n```\n\n✅ Exemple de pipeline **GitHub Actions** (`.github/workflows/ci.yml`) :\n```\nname: CI\non: [push]\njobs:\n  build:\n    runs-on: ubuntu-latest\n    steps:\n      - uses: actions/checkout@v4\n      - run: npm install\n      - run: npm test\n      - run: docker build -t mon-app .\n```\n\n💡 Outils courants : **GitHub Actions**, **GitLab CI**, **Jenkins**, **CircleCI**. Le principe est toujours le même : un fichier de configuration décrit les étapes, un *runner* les exécute.\n\n✅ Bonnes pratiques :\n• Faire échouer le pipeline si un test casse (« fail fast »).\n• Garder les pipelines rapides (paralléliser, mettre en cache les dépendances).\n• Ne jamais déployer du code qui n'a pas passé les tests.\n\n⚠️ Différence à retenir : **Continuous Delivery** = prêt à déployer (validation humaine possible) ; **Continuous Deployment** = déploiement 100 % automatique en prod.",
                    'questions' => [
                        [
                            'question' => 'Que signifie le « CI » de CI/CD ?',
                            'options' => ['Code Inspection', 'Continuous Integration', 'Container Init', 'Cloud Infrastructure'],
                            'correct' => [1],
                            'explanation' => 'CI = Continuous Integration : build et tests automatiques à chaque changement.',
                        ],
                        [
                            'question' => 'Quel est l\'ordre logique des étapes d\'un pipeline ?',
                            'options' => ['deploy → test → build', 'build → test → deploy', 'test → deploy → build', 'deploy → build → test'],
                            'correct' => [1],
                            'explanation' => 'On construit, puis on teste, et seulement ensuite on déploie.',
                        ],
                        [
                            'question' => 'Quels sont des outils de CI/CD courants ? (plusieurs réponses)',
                            'options' => ['GitHub Actions', 'GitLab CI', 'Jenkins', 'PostgreSQL'],
                            'correct' => [0, 1, 2],
                            'explanation' => 'GitHub Actions, GitLab CI et Jenkins sont des outils CI/CD ; PostgreSQL est une base de données.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 8 — Infrastructure as Code',
                    'subtitle' => 'Provisionner par le code',
                    'xp_reward' => 200,
                    'content' => "🎯 **Objectif** : décrire son infrastructure avec du code plutôt qu'à la main.\n\n💡 L'**Infrastructure as Code (IaC)** consiste à décrire serveurs, réseaux et services dans des fichiers versionnés. Fini les serveurs configurés à la main et impossibles à reproduire : l'infra devient **reproductible**, **versionnée** (dans Git) et **auditable**.\n\n✅ **Terraform** : provisionne des ressources cloud (serveurs, réseaux, bases) de façon **déclarative** — on décrit l'état désiré, Terraform calcule les changements à appliquer.\n```\nresource \"aws_instance\" \"web\" {\n  ami           = \"ami-123456\"\n  instance_type = \"t3.micro\"\n}\n```\nLe cycle de travail :\n```\nterraform init     # initialise\nterraform plan     # montre ce qui va changer\nterraform apply    # applique les changements\n```\n\n✅ **Ansible** : configure les machines déjà existantes (installer des paquets, déployer une app) avec des **playbooks** YAML. Approche plutôt **procédurale** et **sans agent** (via SSH).\n```\n- name: Installer nginx\n  hosts: web\n  tasks:\n    - apt:\n        name: nginx\n        state: present\n```\n\n💡 Distinction utile : **Terraform** = *provisionnement* (créer l'infra) ; **Ansible** = *configuration* (paramétrer ce qui existe déjà). Ils sont souvent complémentaires.\n\n💡 Principe clé de l'IaC : l'**idempotence**. Appliquer la même configuration plusieurs fois donne toujours le même résultat, sans effets de bord.",
                    'questions' => [
                        [
                            'question' => 'Que permet l\'Infrastructure as Code ?',
                            'options' => ['Chiffrer les mots de passe', 'Décrire l\'infrastructure dans du code versionné et reproductible', 'Remplacer Docker', 'Compiler le code applicatif'],
                            'correct' => [1],
                            'explanation' => 'L\'IaC décrit l\'infra par du code versionné, reproductible et auditable.',
                        ],
                        [
                            'question' => 'Quelle commande Terraform montre les changements AVANT de les appliquer ?',
                            'options' => ['terraform apply', 'terraform plan', 'terraform destroy', 'terraform init'],
                            'correct' => [1],
                            'explanation' => '`terraform plan` affiche le diff des changements ; `apply` les exécute.',
                        ],
                        [
                            'question' => 'Quelle distinction est correcte ?',
                            'options' => ['Terraform configure, Ansible provisionne', 'Terraform provisionne l\'infra, Ansible configure les machines', 'Les deux remplacent Git', 'Aucun ne touche au cloud'],
                            'correct' => [1],
                            'explanation' => 'Terraform crée l\'infra (provisionnement) ; Ansible configure les machines existantes.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 9 — Cloud',
                    'subtitle' => 'AWS, GCP et les services managés',
                    'xp_reward' => 210,
                    'content' => "🎯 **Objectif** : comprendre les concepts du cloud public.\n\n💡 Le **cloud** met à disposition, à la demande et facturé à l'usage, des ressources informatiques (serveurs, stockage, réseau) gérées par un fournisseur. Les plus connus : **AWS** (Amazon), **GCP** (Google), **Azure** (Microsoft).\n\n✅ Trois modèles de service :\n• **IaaS** (Infrastructure as a Service) : on loue des serveurs/réseaux bruts (ex. AWS EC2).\n• **PaaS** (Platform as a Service) : on déploie une app sans gérer le serveur (ex. App Engine).\n• **SaaS** (Software as a Service) : un logiciel prêt à l'emploi (ex. Gmail).\n\n✅ Les grandes familles de ressources :\n• **Calcul** : machines virtuelles (AWS **EC2**, GCP **Compute Engine**).\n• **Stockage objet** : fichiers à grande échelle (AWS **S3**, GCP **Cloud Storage**).\n• **Bases de données managées** (AWS **RDS**).\n• **Réseau** : réseaux privés virtuels (**VPC**), équilibrage de charge.\n\n💡 Le grand avantage : l'**élasticité**. On peut augmenter ou réduire les ressources en minutes, et ne payer que ce qu'on consomme (modèle **pay-as-you-go**).\n\n⚠️ Le cloud n'est pas « gratuit » : une instance oubliée allumée ou un bucket de stockage mal dimensionné peut coûter cher. Surveille toujours ta facturation et arrête les ressources inutilisées.\n\n✅ La **responsabilité partagée** : le fournisseur sécurise l'infrastructure « du » cloud ; le client est responsable de la sécurité « dans » le cloud (config, accès, données).",
                    'questions' => [
                        [
                            'question' => 'Quel service AWS sert au stockage objet (fichiers à grande échelle) ?',
                            'options' => ['EC2', 'S3', 'RDS', 'VPC'],
                            'correct' => [1],
                            'explanation' => 'AWS S3 est le service de stockage objet ; EC2 = calcul, RDS = base de données.',
                        ],
                        [
                            'question' => 'Que désigne l\'élasticité du cloud ?',
                            'options' => ['Le chiffrement automatique', 'La capacité d\'augmenter/réduire les ressources à la demande', 'L\'obligation de payer un forfait fixe', 'L\'impossibilité de redimensionner'],
                            'correct' => [1],
                            'explanation' => 'L\'élasticité = ajuster les ressources selon la charge, en payant à l\'usage.',
                        ],
                        [
                            'question' => 'Quels modèles de service cloud existent ? (plusieurs réponses)',
                            'options' => ['IaaS', 'PaaS', 'SaaS', 'DaaS-Git'],
                            'correct' => [0, 1, 2],
                            'explanation' => 'Les trois modèles classiques sont IaaS, PaaS et SaaS.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 10 — Monitoring & logs',
                    'subtitle' => 'Voir ce qui se passe en prod',
                    'xp_reward' => 220,
                    'content' => "🎯 **Objectif** : superviser ses systèmes pour détecter et diagnostiquer les problèmes.\n\n💡 On ne pilote pas ce qu'on ne mesure pas. Le **monitoring** consiste à collecter des **métriques** et à déclencher des **alertes** quand quelque chose ne va pas.\n\n✅ Les **métriques** sont des valeurs numériques mesurées dans le temps :\n• CPU, mémoire, espace disque,\n• temps de réponse (latence),\n• taux d'erreurs (ex. % de réponses HTTP 5xx),\n• débit (requêtes par seconde).\n\n💡 Les **logs** sont des messages textuels horodatés émis par les applications. Ils sont précieux pour le **diagnostic** : quand une métrique signale un problème, les logs expliquent **pourquoi**. En production, on les **centralise** (ELK : Elasticsearch + Logstash + Kibana, ou Loki) pour chercher à travers toutes les machines.\n\n✅ Outils répandus :\n• **Prometheus** : collecte des métriques.\n• **Grafana** : tableaux de bord et visualisation.\n• **Alertmanager** : gestion des alertes (email, Slack…).\n\n✅ Les **alertes** doivent être **pertinentes** : trop d'alertes = « fatigue d'alerte » et on finit par les ignorer. On alerte sur des symptômes visibles par l'utilisateur (latence élevée, erreurs) plutôt que sur chaque petite variation.\n\n💡 Trois piliers de l'**observabilité** : **métriques** (quoi), **logs** (pourquoi), **traces** (où, le chemin d'une requête à travers les services).",
                    'questions' => [
                        [
                            'question' => 'Quelle est la différence entre métriques et logs ?',
                            'options' => ['Aucune', 'Les métriques sont des valeurs numériques dans le temps, les logs des messages horodatés', 'Les logs sont des nombres, les métriques du texte', 'Les métriques remplacent les logs'],
                            'correct' => [1],
                            'explanation' => 'Les métriques mesurent (chiffres) ; les logs détaillent (texte) et expliquent le pourquoi.',
                        ],
                        [
                            'question' => 'Quel outil est typiquement utilisé pour la visualisation/les tableaux de bord ?',
                            'options' => ['Grafana', 'Terraform', 'Ansible', 'Git'],
                            'correct' => [0],
                            'explanation' => 'Grafana crée des tableaux de bord ; Prometheus collecte les métriques.',
                        ],
                        [
                            'question' => 'Quels sont les trois piliers de l\'observabilité ? (plusieurs réponses)',
                            'options' => ['Métriques', 'Logs', 'Traces', 'Commits'],
                            'correct' => [0, 1, 2],
                            'explanation' => 'Observabilité = métriques (quoi) + logs (pourquoi) + traces (où).',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 11 — Sécurité & secrets',
                    'subtitle' => 'Protéger ce qui doit l\'être',
                    'xp_reward' => 250,
                    'content' => "🎯 **Objectif** : gérer les secrets et appliquer les bons réflexes de sécurité.\n\n💡 Un **secret** est une donnée sensible : mot de passe, clé d'API, token, certificat. La règle d'or : **un secret ne doit JAMAIS être écrit en clair dans le code ni commité dans Git**. Une clé poussée par erreur sur un dépôt public peut être exploitée en quelques minutes.\n\n⚠️ Erreurs fréquentes à éviter :\n• Mettre des mots de passe dans le code source.\n• Commiter un fichier `.env` contenant des clés (ajoute-le à `.gitignore`).\n• Coller des tokens dans des logs ou des tickets.\n\n✅ Bonnes pratiques de gestion des secrets :\n• Stocker les secrets dans des **variables d'environnement** ou un **gestionnaire dédié** : **HashiCorp Vault**, **AWS Secrets Manager**, les *secrets* de la CI/CD (GitHub/GitLab).\n• **Chiffrer** les secrets au repos et en transit.\n• **Faire tourner** (rotation) régulièrement les clés.\n\n✅ Le **principe du moindre privilège** (least privilege) : chaque utilisateur, service ou conteneur ne reçoit QUE les droits strictement nécessaires à sa tâche — rien de plus. Si un compte est compromis, les dégâts sont limités.\n```\n# Mauvais : un seul compte « admin » pour tout\n# Bon : un rôle dédié par service, droits minimaux\n```\n\n✅ Autres réflexes : maintenir les systèmes **à jour** (patchs de sécurité), scanner les images Docker (**vulnérabilités**), activer la **double authentification (2FA)**, et auditer les accès.\n\n🏆 Bravo ! Tu as parcouru toute la roadmap DevOps : de la culture aux conteneurs, du CI/CD au cloud, jusqu'à la sécurité. Continue à pratiquer sur de vrais projets — c'est en automatisant qu'on devient DevOps.",
                    'questions' => [
                        [
                            'question' => 'Où NE doit-on jamais stocker un secret (mot de passe, clé d\'API) ?',
                            'options' => ['Dans un gestionnaire de secrets', 'En clair dans le code commité sur Git', 'Dans une variable d\'environnement', 'Dans Vault'],
                            'correct' => [1],
                            'explanation' => 'Un secret ne doit jamais être écrit en clair dans le code ni commité dans Git.',
                        ],
                        [
                            'question' => 'Que dit le principe du moindre privilège ?',
                            'options' => ['Donner tous les droits à chacun par défaut', 'N\'accorder que les droits strictement nécessaires', 'Désactiver toute sécurité', 'Utiliser un seul compte admin partout'],
                            'correct' => [1],
                            'explanation' => 'Chaque entité ne reçoit que les droits minimaux nécessaires à sa tâche.',
                        ],
                        [
                            'question' => 'Quelles sont de bonnes pratiques de gestion des secrets ? (plusieurs réponses)',
                            'options' => ['Utiliser un gestionnaire dédié (Vault, Secrets Manager)', 'Chiffrer les secrets au repos et en transit', 'Faire tourner régulièrement les clés', 'Coller les tokens dans les logs'],
                            'correct' => [0, 1, 2],
                            'explanation' => 'Gestionnaire dédié, chiffrement et rotation sont recommandés ; jamais de tokens en clair dans les logs.',
                        ],
                    ],
                ],
            ],
        ]);

        $this->command->info('  ✓ Roadmap DevOps créée (11 niveaux).');
    }
}
