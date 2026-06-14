<?php

namespace Database\Seeders\Roadmaps;

use Illuminate\Database\Seeder;

/**
 * Roadmap Technicien Support Informatique — devenir helpdesk N1/N2 opérationnel.
 */
class TechnicienSupportRoadmapSeeder extends Seeder
{
    use RoadmapSeederHelper;

    public function run(): void
    {
        $this->createRoadmap([
            'title' => 'Deviens Technicien Support Informatique (Helpdesk)',
            'slug' => 'technicien-support-informatique',
            'domain' => 'developpement',
            'description' => "Apprends le métier de technicien support informatique (helpdesk) de zéro : comprendre le rôle, diagnostiquer les pannes, dépanner le matériel et Windows, gérer le réseau de base, utiliser un outil de tickets selon ITIL, prendre la main à distance et bien communiquer avec les utilisateurs. Une formation pratique pensée pour décrocher un premier emploi en entreprise au Cameroun.",
            'objectives' => "Comprendre le rôle du support et l'organisation N1/N2/N3\nAppliquer une méthode de diagnostic structurée\nIdentifier et dépanner les composants matériels d'un PC\nRésoudre les pannes courantes sous Windows\nMaîtriser les bases du réseau (IP, DNS, Wi-Fi)\nGérer les tickets et communiquer efficacement avec les utilisateurs",
            'icon' => '🛠️',
            'color' => '#2563EB',
            'difficulty' => 'beginner',
            'required_packs' => [],
            'pass_threshold' => 70,
            'order' => 20,
            'levels' => [
                [
                    'title' => 'Niveau 1 — Le rôle du support informatique',
                    'subtitle' => "Comprendre la mission du helpdesk en entreprise",
                    'xp_reward' => 100,
                    'content' => "🎯 **Objectif** : comprendre ce que fait réellement un technicien support et sa place dans l'entreprise.\n\n💡 Le support informatique (ou helpdesk) est le **premier point de contact** entre les employés et le service informatique. Sa mission : remettre les utilisateurs au travail le plus vite possible quand un outil ne fonctionne plus.\n\n✅ Les responsabilités clés :\n• Réceptionner les demandes (incidents et requêtes)\n• Diagnostiquer et résoudre les problèmes courants\n• Escalader vers une équipe spécialisée si besoin\n• Documenter chaque intervention\n\n⚠️ On distingue deux types de sollicitations :\n• **Incident** : quelque chose est cassé (imprimante en panne, écran noir)\n• **Requête** : une demande normale (création de compte, accès à un dossier)\n\nDans une PME de Douala, le technicien support gère aussi bien les imprimantes que les comptes e-mail et le Wi-Fi. La qualité de service repose autant sur la technique que sur la **relation humaine** : un utilisateur stressé veut être rassuré, écouté et tenu informé.",
                    'questions' => [
                        ['question' => "Quelle est la mission principale d'un technicien support ?", 'options' => ["Développer des logiciels", "Remettre les utilisateurs au travail en résolvant leurs problèmes", "Vendre du matériel informatique", "Gérer la comptabilité de l'entreprise"], 'correct' => [1], 'explanation' => "Le support existe pour rétablir le service et permettre aux utilisateurs de retravailler rapidement."],
                        ['question' => "Comment appelle-t-on une panne qui empêche un utilisateur de travailler ?", 'options' => ["Une requête", "Un incident", "Un projet", "Une maintenance"], 'correct' => [1], 'explanation' => "Un incident désigne une interruption ou dégradation imprévue d'un service."],
                        ['question' => "Lesquelles sont des tâches typiques du support ? (plusieurs réponses)", 'options' => ["Réceptionner les demandes", "Documenter les interventions", "Concevoir l'architecture réseau d'un datacenter", "Escalader les cas complexes"], 'correct' => [0,1,3], 'explanation' => "La conception d'architecture relève d'experts N3/ingénieurs, pas du helpdesk de premier niveau."],
                    ],
                ],
                [
                    'title' => 'Niveau 2 — Les niveaux N1, N2 et N3',
                    'subtitle' => "Organisation et escalade des demandes",
                    'xp_reward' => 110,
                    'content' => "🎯 **Objectif** : comprendre comment le support est organisé par niveaux et quand escalader.\n\n💡 Le support fonctionne en **paliers** pour traiter chaque problème au bon endroit :\n\n| Niveau | Rôle | Exemples |\n|--------|------|----------|\n| N1 | Premier contact, cas simples | Mot de passe oublié, imprimante |\n| N2 | Dépannage technique avancé | Pannes système, configuration |\n| N3 | Experts / éditeurs | Bug logiciel, infrastructure |\n\n✅ **L'escalade** consiste à transmettre un ticket à un niveau supérieur quand on ne peut pas le résoudre. On distingue :\n• Escalade **fonctionnelle** : vers plus de compétence technique (N1 → N2)\n• Escalade **hiérarchique** : vers un manager (urgence, conflit)\n\n⚠️ Bonnes pratiques avant d'escalader :\n• Avoir réuni les informations (captures, messages d'erreur)\n• Avoir tenté les solutions de base documentées\n• Décrire clairement ce qui a déjà été testé\n\nUn bon N1 résout 60 à 80 % des tickets sans escalade. Plus on monte, plus l'intervention coûte cher : d'où l'intérêt de bien filtrer dès le N1.",
                    'questions' => [
                        ['question' => "Que fait généralement le support de niveau 1 (N1) ?", 'options' => ["Corrige le code source des logiciels", "Traite les demandes simples et courantes", "Gère les serveurs du datacenter", "S'occupe uniquement des contrats fournisseurs"], 'correct' => [1], 'explanation' => "Le N1 est le premier contact qui résout les cas fréquents et filtre les demandes."],
                        ['question' => "Qu'est-ce que l'escalade d'un ticket ?", 'options' => ["Le supprimer", "Le transmettre à un niveau plus compétent", "Le clôturer sans réponse", "Le facturer à l'utilisateur"], 'correct' => [1], 'explanation' => "Escalader, c'est transmettre un ticket vers un niveau supérieur capable de le résoudre."],
                        ['question' => "Avant d'escalader un ticket vers le N2, il faut idéalement…", 'options' => ["Avoir tenté les solutions de base", "Avoir réuni les informations utiles", "Attendre une semaine systématiquement", "Avoir décrit ce qui a déjà été testé"], 'correct' => [0,1,3], 'explanation' => "On escalade après avoir documenté et tenté le dépannage de base, jamais en attendant arbitrairement."],
                    ],
                ],
                [
                    'title' => 'Niveau 3 — La méthode de diagnostic',
                    'subtitle' => "Résoudre un problème de façon structurée",
                    'xp_reward' => 120,
                    'content' => "🎯 **Objectif** : adopter une démarche méthodique pour ne plus dépanner au hasard.\n\n💡 Un bon diagnostic suit des étapes claires :\n\n1. **Écouter / questionner** : que se passe-t-il exactement ? Depuis quand ?\n2. **Reproduire** le problème si possible\n3. **Isoler** la cause (matériel ? logiciel ? réseau ? utilisateur ?)\n4. **Tester** une hypothèse à la fois\n5. **Résoudre** puis **vérifier** avec l'utilisateur\n6. **Documenter** la solution\n\n✅ Questions magiques à poser :\n• « Qu'est-ce qui a changé récemment ? »\n• « Est-ce que ça marchait avant ? »\n• « Le problème touche-t-il une seule personne ou tout le bureau ? »\n\n⚠️ Pièges à éviter :\n• Changer plusieurs choses en même temps (on ne sait plus ce qui a marché)\n• Croire l'utilisateur sur parole sans vérifier soi-même\n• Oublier le redémarrage, qui résout énormément de cas\n\nExemple : à Yaoundé, un employé ne peut plus imprimer. Au lieu de réinstaller le pilote tout de suite, on vérifie d'abord si l'imprimante est allumée, connectée et si d'autres collègues impriment. La méthode évite des heures perdues.",
                    'questions' => [
                        ['question' => "Pourquoi tester une seule hypothèse à la fois ?", 'options' => ["Pour gagner du temps en faisant tout d'un coup", "Pour savoir précisément ce qui a résolu le problème", "Parce que c'est obligatoire par la loi", "Pour éviter de documenter"], 'correct' => [1], 'explanation' => "Changer une chose à la fois permet d'identifier la cause exacte de la résolution."],
                        ['question' => "Quelle question aide le plus à orienter un diagnostic ?", 'options' => ["Quelle est votre couleur préférée ?", "Qu'est-ce qui a changé récemment ?", "Combien gagnez-vous ?", "Quel est votre âge ?"], 'correct' => [1], 'explanation' => "Identifier le changement récent pointe souvent directement vers la cause de la panne."],
                        ['question' => "Si un seul utilisateur sur dix est touché, cela suggère…", 'options' => ["Un problème global du serveur", "Un problème plutôt local au poste de l'utilisateur", "Une panne d'électricité générale", "Un virus dans tout le réseau"], 'correct' => [1], 'explanation' => "Quand un seul poste est affecté, la cause est généralement locale à cette machine ou ce compte."],
                    ],
                ],
                [
                    'title' => 'Niveau 4 — Le matériel du PC',
                    'subtitle' => "Reconnaître et dépanner les composants",
                    'xp_reward' => 130,
                    'content' => "🎯 **Objectif** : identifier les composants d'un ordinateur et diagnostiquer les pannes matérielles.\n\n💡 Les composants essentiels :\n• **CPU** (processeur) : le cerveau, exécute les calculs\n• **RAM** (mémoire vive) : mémoire de travail temporaire\n• **Disque** (HDD/SSD) : stockage permanent ; le SSD est bien plus rapide\n• **Carte mère** : relie tous les composants\n• **Alimentation** : fournit le courant\n• **Carte graphique** : affichage\n\n✅ Symptômes courants :\n• PC qui ne s'allume pas → alimentation, prise, batterie\n• Lenteurs extrêmes → RAM insuffisante ou disque saturé\n• Bips au démarrage → diagnostic matériel (RAM mal enfichée)\n• Surchauffe / extinction brutale → poussière, ventilateur HS\n\n⚠️ Sécurité avant toute manipulation :\n• Éteindre et **débrancher** la machine\n• Se décharger de l'électricité statique (toucher une partie métallique)\n• Ne jamais forcer un composant\n\nDans le climat chaud et poussiéreux de Douala, le **nettoyage régulier** des ventilateurs prévient une grande partie des surchauffes. Remplacer un HDD par un SSD est souvent la mise à niveau la plus rentable pour redonner vie à un vieux PC.",
                    'questions' => [
                        ['question' => "Quel composant sert de mémoire de travail temporaire ?", 'options' => ["Le disque dur", "La RAM", "La carte graphique", "L'alimentation"], 'correct' => [1], 'explanation' => "La RAM stocke temporairement les données en cours d'utilisation par le système."],
                        ['question' => "Un PC très lent à l'ouverture des programmes pourrait bénéficier de…", 'options' => ["Changer la couleur du boîtier", "Remplacer le disque dur par un SSD", "Débrancher la souris", "Augmenter la luminosité"], 'correct' => [1], 'explanation' => "Le SSD réduit drastiquement les temps de chargement par rapport à un disque dur classique."],
                        ['question' => "Quelles précautions prendre avant d'ouvrir un PC ? (plusieurs réponses)", 'options' => ["Débrancher la machine", "Se décharger de l'électricité statique", "Travailler les mains mouillées", "Ne pas forcer les composants"], 'correct' => [0,1,3], 'explanation' => "L'humidité est dangereuse ; on débranche, on se décharge et on évite de forcer."],
                    ],
                ],
                [
                    'title' => 'Niveau 5 — Dépanner Windows',
                    'subtitle' => "Pannes système et outils intégrés",
                    'xp_reward' => 140,
                    'content' => "🎯 **Objectif** : résoudre les problèmes courants du système d'exploitation Windows.\n\n💡 Windows reste le système le plus répandu en entreprise. Le technicien doit connaître ses outils de dépannage :\n• **Gestionnaire des tâches** (Ctrl+Maj+Échap) : fermer une application bloquée\n• **Panneau de configuration / Paramètres** : pilotes, comptes, réseau\n• **Invite de commandes** pour des vérifications rapides\n\n✅ Commandes utiles à connaître :\n```\nipconfig          (voir l'adresse IP)\nping 8.8.8.8      (tester la connexion)\nsfc /scannow      (réparer les fichiers système)\nchkdsk            (vérifier le disque)\n```\n\n⚠️ Pannes fréquentes et réflexes :\n• Application figée → forcer la fermeture via le gestionnaire des tâches\n• Démarrage lent → désactiver les programmes au démarrage\n• Écran bleu (BSOD) → noter le code d'erreur, vérifier pilotes/RAM\n• Manque d'espace disque → nettoyage de disque, vider la corbeille\n\nLe réflexe « **redémarrer la machine** » résout une grande part des soucis logiciels : la mémoire est vidée et les services repartent proprement. Pensez aussi aux **mises à jour Windows**, souvent à l'origine ou à la solution des problèmes.",
                    'questions' => [
                        ['question' => "Quel raccourci ouvre le Gestionnaire des tâches ?", 'options' => ["Ctrl + C", "Ctrl + Maj + Échap", "Alt + F4", "Windows + E"], 'correct' => [1], 'explanation' => "Ctrl+Maj+Échap ouvre directement le Gestionnaire des tâches sous Windows."],
                        ['question' => "Quelle commande répare les fichiers système corrompus de Windows ?", 'options' => ["ping 8.8.8.8", "sfc /scannow", "ipconfig", "dir"], 'correct' => [1], 'explanation' => "sfc /scannow analyse et répare l'intégrité des fichiers système Windows."],
                        ['question' => "Face à une application complètement figée, que faire en premier ?", 'options' => ["Réinstaller Windows", "Forcer sa fermeture via le Gestionnaire des tâches", "Débrancher l'écran", "Changer le disque dur"], 'correct' => [1], 'explanation' => "On commence par fermer l'application bloquée plutôt que d'agir sur le matériel ou le système."],
                    ],
                ],
                [
                    'title' => 'Niveau 6 — Les bases du réseau',
                    'subtitle' => "IP, DNS, Wi-Fi et connectivité",
                    'xp_reward' => 150,
                    'content' => "🎯 **Objectif** : comprendre comment circule l'information sur un réseau et dépanner la connexion.\n\n💡 Notions clés :\n• **Adresse IP** : identifie un appareil sur le réseau (ex. 192.168.1.10)\n• **Passerelle** : la « porte de sortie » vers Internet (souvent la box/routeur)\n• **DNS** : traduit les noms (google.com) en adresses IP\n• **DHCP** : attribue automatiquement les IP aux machines\n\n✅ Démarche de dépannage réseau :\n```\nipconfig /all      (voir IP, passerelle, DNS)\nping 192.168.1.1   (tester la passerelle locale)\nping 8.8.8.8       (tester Internet sans DNS)\nping google.com    (tester le DNS)\n```\nSi le ping vers 8.8.8.8 marche mais pas vers google.com → problème de **DNS**.\n\n⚠️ Cas Wi-Fi fréquents :\n• Mauvais mot de passe Wi-Fi saisi\n• Adresse en 169.254.x.x → DHCP indisponible (pas d'IP attribuée)\n• Trop d'appareils ou interférences → débit faible\n\nDans un bureau de Yaoundé partageant une connexion mobile, comprendre la différence entre « pas de réseau local » et « pas d'accès Internet » permet de cibler vite : redémarrer le routeur, vérifier l'abonnement data, ou renouveler l'adresse IP du poste.",
                    'questions' => [
                        ['question' => "À quoi sert le DNS ?", 'options' => ["À fournir l'électricité au routeur", "À traduire les noms de sites en adresses IP", "À stocker les fichiers", "À chiffrer le disque dur"], 'correct' => [1], 'explanation' => "Le DNS convertit les noms de domaine lisibles en adresses IP utilisables par les machines."],
                        ['question' => "Le ping vers 8.8.8.8 fonctionne mais pas vers google.com. Quel est le problème ?", 'options' => ["La carte graphique", "Le DNS", "L'alimentation", "Le clavier"], 'correct' => [1], 'explanation' => "Si l'IP répond mais pas le nom, c'est la résolution DNS qui est défaillante."],
                        ['question' => "Une machine reçoit une adresse en 169.254.x.x. Cela indique…", 'options' => ["Une connexion parfaite", "Que le DHCP n'a pas attribué d'adresse", "Que le DNS est rapide", "Que le disque est plein"], 'correct' => [1], 'explanation' => "L'adresse APIPA 169.254.x.x signifie qu'aucun serveur DHCP n'a fourni d'IP valide."],
                    ],
                ],
                [
                    'title' => 'Niveau 7 — Ticketing et ITIL',
                    'subtitle' => "Gérer les demandes avec méthode",
                    'xp_reward' => 160,
                    'content' => "🎯 **Objectif** : utiliser un outil de tickets et comprendre les bonnes pratiques ITIL.\n\n💡 Un **ticket** trace une demande du début à la fin. Cycle de vie typique :\n• Nouveau → En cours → En attente → Résolu → Clôturé\n\n**ITIL** est un référentiel de bonnes pratiques de gestion des services informatiques. Il distingue notamment :\n• **Gestion des incidents** : rétablir le service au plus vite\n• **Gestion des problèmes** : trouver la cause racine d'incidents récurrents\n• **Gestion des requêtes** : traiter les demandes standard\n\n✅ Un bon ticket contient :\n• Le demandeur et son contact\n• Une description claire du symptôme\n• La **priorité** (impact × urgence)\n• Les actions réalisées et la solution finale\n\n⚠️ La notion de **SLA** (engagement de service) : délai maximal pour répondre et résoudre. Exemple : « répondre en 1 h, résoudre en 4 h » pour une priorité haute.\n\nDifférence essentielle : un **incident** est une panne ponctuelle qu'on corrige ; un **problème** est la cause profonde qui génère des incidents répétés. Si l'imprimante du 3e étage tombe en panne chaque semaine, l'incident se résout à chaque fois, mais le « problème » (matériel défectueux) doit être traité durablement.",
                    'questions' => [
                        ['question' => "Que représente principalement ITIL ?", 'options' => ["Un langage de programmation", "Un référentiel de bonnes pratiques de gestion des services IT", "Un type de câble réseau", "Un antivirus"], 'correct' => [1], 'explanation' => "ITIL est un ensemble de bonnes pratiques pour gérer les services informatiques."],
                        ['question' => "Quelle est la différence entre un incident et un problème selon ITIL ?", 'options' => ["Aucune différence", "L'incident est la panne ponctuelle, le problème est sa cause racine", "Le problème est moins grave qu'un incident", "L'incident concerne uniquement le réseau"], 'correct' => [1], 'explanation' => "L'incident est l'interruption visible, le problème est la cause sous-jacente des incidents répétés."],
                        ['question' => "Que désigne un SLA ?", 'options' => ["Un type de processeur", "Un engagement sur les délais de réponse et de résolution", "Un logiciel de dessin", "Une marque d'imprimante"], 'correct' => [1], 'explanation' => "Le SLA fixe les délais maximaux contractuels de prise en charge et de résolution."],
                    ],
                ],
                [
                    'title' => 'Niveau 8 — La relation utilisateur',
                    'subtitle' => "Communiquer et rassurer",
                    'xp_reward' => 170,
                    'content' => "🎯 **Objectif** : développer la communication, compétence aussi importante que la technique.\n\n💡 L'utilisateur juge le support autant sur **l'accueil** que sur la solution. Un technicien froid mais efficace laisse une mauvaise impression ; un technicien à l'écoute fidélise.\n\n✅ Les réflexes d'une bonne relation :\n• **Écouter** sans couper, reformuler pour confirmer la compréhension\n• **Vulgariser** : éviter le jargon, parler le langage de l'utilisateur\n• **Rassurer** : « Je comprends, on va régler ça ensemble »\n• **Tenir informé** en cas d'attente : un silence inquiète\n• Rester **calme** face à un utilisateur énervé\n\n⚠️ À éviter absolument :\n• Culpabiliser l'utilisateur (« vous avez encore fait n'importe quoi »)\n• Promettre un délai qu'on ne pourra pas tenir\n• Utiliser des termes techniques incompréhensibles\n\nSchéma simple d'un échange réussi :\n```\nAccueil  →  Écoute active  →  Diagnostic\n   →  Explication simple  →  Résolution  →  Vérification\n```\n\nExemple : un directeur stressé à Douala n'accède plus à ses e-mails avant une réunion. Plutôt que des termes techniques, on dit : « Je m'en occupe tout de suite, vous aurez vos messages dans quelques minutes. » La maîtrise du stress et la clarté font la différence.",
                    'questions' => [
                        ['question' => "Pourquoi reformuler la demande de l'utilisateur ?", 'options' => ["Pour gagner du temps en parlant", "Pour confirmer qu'on a bien compris le problème", "Pour montrer qu'on est plus intelligent", "Ce n'est jamais utile"], 'correct' => [1], 'explanation' => "Reformuler vérifie la bonne compréhension et rassure l'utilisateur sur l'écoute."],
                        ['question' => "Face à un utilisateur énervé, la meilleure attitude est de…", 'options' => ["Hausser le ton aussi", "Rester calme et le rassurer", "Raccrocher", "L'ignorer"], 'correct' => [1], 'explanation' => "Garder son calme et rassurer désamorce la tension et permet de résoudre sereinement."],
                        ['question' => "Quels comportements nuisent à la relation utilisateur ? (plusieurs réponses)", 'options' => ["Culpabiliser l'utilisateur", "Employer un jargon incompréhensible", "Tenir l'utilisateur informé", "Promettre des délais intenables"], 'correct' => [0,1,3], 'explanation' => "Tenir informé est positif ; culpabiliser, jargonner et sur-promettre dégradent la relation."],
                    ],
                ],
                [
                    'title' => 'Niveau 9 — La prise en main à distance',
                    'subtitle' => "Dépanner sans se déplacer",
                    'xp_reward' => 185,
                    'content' => "🎯 **Objectif** : résoudre les incidents à distance, indispensable pour gagner du temps.\n\n💡 La **prise de contrôle à distance** permet de voir et piloter l'écran de l'utilisateur depuis son propre poste. Outils courants : AnyDesk, TeamViewer, le Bureau à distance Windows (RDP).\n\n✅ Avantages :\n• Intervention immédiate, sans déplacement\n• Idéal pour le télétravail ou plusieurs sites (Douala / Yaoundé)\n• Permet de montrer à l'utilisateur comment faire\n\nDéroulé type d'une session :\n```\n1. Obtenir l'ID et le mot de passe de session\n2. Se connecter avec l'accord de l'utilisateur\n3. Diagnostiquer / corriger en expliquant\n4. Couper la session proprement\n```\n\n⚠️ Règles de sécurité **essentielles** :\n• Toujours obtenir le **consentement** avant de prendre la main\n• Ne jamais laisser une session ouverte sans surveillance\n• Méfiance face aux **arnaques** : un vrai support ne vous appelle pas pour exiger un accès distant non sollicité\n• Couper la connexion dès la fin de l'intervention\n\nLa prise à distance ne remplace pas tout : une panne matérielle (écran HS, câble débranché) exige une présence physique ou l'aide de l'utilisateur sur place. Sachez reconnaître ces limites.",
                    'questions' => [
                        ['question' => "Que faut-il toujours obtenir avant de prendre le contrôle d'un poste à distance ?", 'options' => ["Le numéro de série du PC", "Le consentement de l'utilisateur", "L'autorisation du fournisseur d'accès", "Le mot de passe Wi-Fi du voisin"], 'correct' => [1], 'explanation' => "Le consentement de l'utilisateur est indispensable, pour la confiance et la conformité."],
                        ['question' => "Lequel de ces problèmes ne peut PAS se résoudre par prise à distance ?", 'options' => ["Réinstaller un pilote", "Un câble d'écran débranché physiquement", "Configurer une boîte mail", "Vider le cache d'un navigateur"], 'correct' => [1], 'explanation' => "Un problème purement physique exige une intervention sur place, pas une connexion distante."],
                        ['question' => "Un inconnu vous appelle et exige un accès distant immédiat à votre PC. Que faire ?", 'options' => ["Accepter aussitôt", "Refuser : c'est un schéma classique d'arnaque", "Lui donner votre mot de passe", "Installer ce qu'il demande"], 'correct' => [1], 'explanation' => "Un accès distant non sollicité exigé par un inconnu est une arnaque typique : il faut refuser."],
                    ],
                ],
                [
                    'title' => 'Niveau 10 — Documentation et évolution',
                    'subtitle' => "Capitaliser et faire carrière",
                    'xp_reward' => 200,
                    'content' => "🎯 **Objectif** : documenter ses interventions et préparer la suite de sa carrière.\n\n💡 La **documentation** transforme une résolution ponctuelle en savoir réutilisable. Sans elle, on refait sans cesse les mêmes recherches.\n\n✅ Ce qu'on documente :\n• Les **procédures** récurrentes (créer un compte, réinitialiser un mot de passe)\n• Une **base de connaissances** (FAQ interne) : symptôme → solution\n• Les notes dans chaque ticket pour les collègues\n\nStructure d'une fiche de connaissance :\n```\nTitre du problème\nSymptômes observés\nCause identifiée\nSolution étape par étape\nDate / auteur\n```\n\n⚠️ Bonnes pratiques :\n• Écrire **simplement**, pour qu'un débutant puisse suivre\n• Mettre à jour les fiches obsolètes\n• Partager le savoir plutôt que le garder pour soi\n\nUne base de connaissances bien tenue réduit le temps de résolution et facilite l'arrivée des nouveaux.\n\n🏆 **Félicitations !** Tu maîtrises désormais les fondamentaux du métier de technicien support : rôle, diagnostic, matériel, Windows, réseau, ticketing ITIL, relation utilisateur, dépannage à distance et documentation.\n\n🚀 **Tes débouchés** : technicien helpdesk N1/N2, technicien de proximité, administrateur systèmes junior. En te formant (certifications, réseau, Linux, cybersécurité), tu peux évoluer vers administrateur système/réseau, ingénieur support N3 ou responsable IT. Le support est une porte d'entrée idéale vers tous les métiers de l'informatique. Bonne route !",
                    'questions' => [
                        ['question' => "Pourquoi documenter ses interventions ?", 'options' => ["Pour perdre du temps", "Pour réutiliser le savoir et éviter de tout recommencer", "Pour cacher les solutions aux collègues", "Cela ne sert à rien"], 'correct' => [1], 'explanation' => "La documentation capitalise le savoir et accélère la résolution des cas similaires."],
                        ['question' => "Qu'est-ce qu'une base de connaissances ?", 'options' => ["Un disque dur externe", "Un recueil de fiches symptôme → solution", "Un type de routeur", "Un câble réseau"], 'correct' => [1], 'explanation' => "La base de connaissances regroupe des fiches associant problèmes et solutions documentées."],
                        ['question' => "Vers quels métiers peut évoluer un technicien support ? (plusieurs réponses)", 'options' => ["Administrateur systèmes et réseaux", "Ingénieur support N3", "Chirurgien", "Responsable IT"], 'correct' => [0,1,3], 'explanation' => "Le support mène aux métiers IT comme admin sys/réseau, support N3 ou responsable IT, pas à la chirurgie."],
                    ],
                ],
            ],
        ]);

        $this->command->info('  ✓ Roadmap Technicien Support Informatique créée (10 niveaux).');
    }
}
