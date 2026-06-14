<?php

namespace Database\Seeders\Roadmaps;

use Illuminate\Database\Seeder;

/**
 * Roadmap Cybersécurité Blue Team — défendre, détecter et répondre aux cyberattaques.
 */
class CybersecuriteBlueTeamRoadmapSeeder extends Seeder
{
    use RoadmapSeederHelper;

    public function run(): void
    {
        $this->createRoadmap([
            'title' => 'Deviens Analyste Cybersécurité (Blue Team)',
            'slug' => 'cybersecurite-blue-team',
            'domain' => 'cybersecurite',
            'description' => "Apprends à protéger les systèmes d'information du côté défensif (Blue Team) : comprendre les menaces, durcir les machines, surveiller les logs avec un SIEM, détecter les intrusions, gérer les incidents et organiser la reprise d'activité. Une roadmap concrète, adaptée au contexte des entreprises et administrations en Afrique francophone.",
            'objectives' => "Identifier les principales menaces et vecteurs d'attaque\nMaîtriser les principes CIA (confidentialité, intégrité, disponibilité)\nDurcir (hardening) postes et serveurs\nExploiter un SIEM et analyser des logs\nDétecter les intrusions et anomalies réseau\nGérer un incident et organiser sauvegardes, PRA et MFA",
            'icon' => '🛡️',
            'color' => '#2563EB',
            'difficulty' => 'intermediate',
            'required_packs' => [],
            'pass_threshold' => 70,
            'order' => 20,
            'levels' => [
                [
                    'title' => 'Niveau 1 — Comprendre les menaces et vecteurs d\'attaque',
                    'subtitle' => 'Le paysage des cybermenaces',
                    'xp_reward' => 100,
                    'content' => "🎯 **Objectif** : connaître les menaces qui visent une entreprise camerounaise (banque, PME, administration).\n\n💡 Une **menace** exploite une **vulnérabilité** via un **vecteur d'attaque** (le chemin emprunté).\n\nPrincipaux vecteurs :\n• **E-mail** : phishing, pièces jointes piégées\n• **Web** : sites malveillants, faux portails mobile money\n• **USB** : clés infectées laissées dans les bureaux\n• **Réseau** : Wi-Fi public non sécurisé, RDP exposé\n\nTypes de logiciels malveillants :\n• **Ransomware** : chiffre les fichiers et exige une rançon\n• **Spyware** : espionne l'activité\n• **Cheval de Troie** : se cache dans un logiciel légitime\n\n✅ Le rôle de la Blue Team est de **réduire la surface d'attaque** et de réagir vite.\n\n⚠️ Le maillon le plus faible reste souvent l'**humain** : un employé qui clique sur un faux lien Orange Money peut compromettre tout le réseau.",
                    'questions' => [
                        ['question' => 'Qu\'est-ce qu\'un vecteur d\'attaque ?', 'options' => ['Le chemin ou moyen utilisé pour atteindre une cible', 'Un antivirus', 'Un type de pare-feu', 'Un mot de passe fort'], 'correct' => [0], 'explanation' => 'Le vecteur d\'attaque est le chemin emprunté par l\'attaquant pour exploiter une vulnérabilité.'],
                        ['question' => 'Quel logiciel malveillant chiffre les fichiers pour exiger une rançon ?', 'options' => ['Spyware', 'Ransomware', 'Adware', 'Keylogger'], 'correct' => [1], 'explanation' => 'Le ransomware chiffre les données de la victime et réclame un paiement pour la clé de déchiffrement.'],
                        ['question' => 'Parmi ces éléments, lesquels sont des vecteurs d\'attaque courants ?', 'options' => ['Les e-mails de phishing', 'Les clés USB infectées', 'Un onduleur électrique', 'Les Wi-Fi publics non sécurisés'], 'correct' => [0, 1, 3], 'explanation' => 'E-mails piégés, clés USB et Wi-Fi non sécurisés sont des vecteurs classiques ; un onduleur n\'en est pas un.'],
                    ],
                ],
                [
                    'title' => 'Niveau 2 — Le triangle CIA',
                    'subtitle' => 'Confidentialité, Intégrité, Disponibilité',
                    'xp_reward' => 110,
                    'content' => "🎯 **Objectif** : maîtriser les 3 piliers fondateurs de la sécurité, le modèle **CIA**.\n\n💡 Tableau de référence :\n\n| Pilier | Signification | Exemple d'atteinte |\n|--------|---------------|--------------------|\n| **C**onfidentialité | Seules les personnes autorisées accèdent | Fuite de la base clients |\n| **I**ntégrité | Les données ne sont pas altérées | Modification frauduleuse d'un virement |\n| **D**isponibilité | Le service reste accessible | Attaque DDoS qui fait tomber le site |\n\nMécanismes associés :\n• Confidentialité → chiffrement, contrôle d'accès\n• Intégrité → hachage (SHA-256), signatures\n• Disponibilité → redondance, sauvegardes, anti-DDoS\n\n✅ Toute décision de sécurité vise à protéger au moins un pilier sans casser les autres.\n\n⚠️ Trop de confidentialité (mots de passe partout) peut nuire à la disponibilité si les utilisateurs ne peuvent plus travailler. Il faut **équilibrer**.",
                    'questions' => [
                        ['question' => 'Que protège le pilier « Intégrité » ?', 'options' => ['L\'accessibilité du service', 'La non-altération des données', 'Le secret des données', 'La vitesse du réseau'], 'correct' => [1], 'explanation' => 'L\'intégrité garantit que les données ne sont pas modifiées de façon non autorisée.'],
                        ['question' => 'Une attaque DDoS qui rend un site inaccessible vise quel pilier ?', 'options' => ['Confidentialité', 'Intégrité', 'Disponibilité', 'Authentification'], 'correct' => [2], 'explanation' => 'Le DDoS sature le service et porte atteinte à la disponibilité.'],
                        ['question' => 'Quel mécanisme protège principalement la confidentialité ?', 'options' => ['Le chiffrement', 'La redondance des serveurs', 'Le hachage de fichiers', 'L\'onduleur'], 'correct' => [0], 'explanation' => 'Le chiffrement rend les données illisibles sans la clé, protégeant la confidentialité.'],
                    ],
                ],
                [
                    'title' => 'Niveau 3 — Durcissement des systèmes (hardening)',
                    'subtitle' => 'Réduire la surface d\'attaque',
                    'xp_reward' => 120,
                    'content' => "🎯 **Objectif** : appliquer les bonnes pratiques de **durcissement** sur postes et serveurs.\n\n💡 Principes clés :\n• **Moindre privilège** : chaque compte n'a que les droits nécessaires\n• **Désactiver les services inutiles** (FTP, Telnet…)\n• **Mises à jour** régulières des correctifs de sécurité\n• **Fermer les ports** non utilisés sur le pare-feu\n\nExemple sous Linux pour lister les ports en écoute :\n\n```bash\nss -tulpn\n# Désactiver un service inutile\nsudo systemctl disable telnet.socket\n```\n\nDurcir un accès SSH (fichier /etc/ssh/sshd_config) :\n\n```bash\nPermitRootLogin no\nPasswordAuthentication no\nPort 2222\n```\n\n✅ Un système durci offre **moins de portes d'entrée** à l'attaquant.\n\n⚠️ Évitez Telnet et FTP : ils transmettent les mots de passe **en clair** sur le réseau. Préférez SSH et SFTP.",
                    'questions' => [
                        ['question' => 'Que signifie le principe du moindre privilège ?', 'options' => ['Donner tous les droits aux utilisateurs', 'N\'accorder que les droits strictement nécessaires', 'Supprimer tous les comptes', 'Utiliser un seul mot de passe partout'], 'correct' => [1], 'explanation' => 'Le moindre privilège limite chaque compte aux droits indispensables, réduisant les dégâts en cas de compromission.'],
                        ['question' => 'Pourquoi éviter Telnet au profit de SSH ?', 'options' => ['Telnet est plus lent', 'Telnet transmet les données en clair', 'SSH ne fonctionne pas sous Linux', 'Telnet coûte plus cher'], 'correct' => [1], 'explanation' => 'Telnet envoie les identifiants en clair, alors que SSH les chiffre.'],
                        ['question' => 'Quelles actions relèvent du durcissement d\'un serveur ?', 'options' => ['Désactiver les services inutiles', 'Appliquer les mises à jour de sécurité', 'Ouvrir tous les ports par défaut', 'Fermer les ports non utilisés'], 'correct' => [0, 1, 3], 'explanation' => 'Désactiver les services inutiles, patcher et fermer les ports renforcent la sécurité ; ouvrir tous les ports l\'affaiblit.'],
                    ],
                ],
                [
                    'title' => 'Niveau 4 — Logs et journalisation',
                    'subtitle' => 'Les traces, base de toute analyse',
                    'xp_reward' => 130,
                    'content' => "🎯 **Objectif** : comprendre l'importance des **logs** comme source de vérité pour la défense.\n\n💡 Un log est un enregistrement horodaté d'un événement. Sources principales :\n• **Système** : connexions, erreurs, démarrages\n• **Authentification** : succès et échecs de login\n• **Réseau** : pare-feu, proxy\n• **Applications** : serveur web, base de données\n\nSous Linux, les échecs de connexion :\n\n```bash\ngrep \"Failed password\" /var/log/auth.log\n# Compter les IP qui attaquent\ngrep \"Failed password\" /var/log/auth.log | awk '{print \\$11}' | sort | uniq -c | sort -rn\n```\n\n✅ Centraliser les logs sur un serveur dédié empêche un attaquant de les effacer pour cacher ses traces.\n\n⚠️ Sans **synchronisation horaire (NTP)** entre machines, corréler les événements devient impossible : un incident à Douala et un autre à Yaoundé doivent partager la même heure de référence.",
                    'questions' => [
                        ['question' => 'Pourquoi centraliser les logs sur un serveur dédié ?', 'options' => ['Pour gagner de la place disque', 'Pour empêcher l\'attaquant de les effacer localement', 'Pour accélérer le réseau', 'Pour réduire la facture électrique'], 'correct' => [1], 'explanation' => 'La centralisation protège les logs : même si une machine est compromise, les traces restent intactes ailleurs.'],
                        ['question' => 'Quel protocole assure la synchronisation horaire des machines ?', 'options' => ['HTTP', 'NTP', 'SMTP', 'FTP'], 'correct' => [1], 'explanation' => 'NTP (Network Time Protocol) synchronise les horloges, indispensable pour corréler les logs.'],
                        ['question' => 'Quelle commande recherche les échecs de connexion SSH sous Linux ?', 'options' => ['ping auth.log', 'grep "Failed password" /var/log/auth.log', 'rm /var/log/auth.log', 'chmod 777 /var/log'], 'correct' => [1], 'explanation' => 'grep "Failed password" dans auth.log liste les tentatives de connexion échouées.'],
                    ],
                ],
                [
                    'title' => 'Niveau 5 — Le SIEM',
                    'subtitle' => 'Centraliser et corréler les événements',
                    'xp_reward' => 140,
                    'content' => "🎯 **Objectif** : comprendre le rôle d'un **SIEM** (Security Information and Event Management).\n\n💡 Un SIEM collecte les logs de tout le SI, les **normalise**, les **corrèle** et déclenche des **alertes**. Exemples : Wazuh (open source), Splunk, Elastic SIEM.\n\nCycle de traitement :\n• Collecte (agents, syslog)\n• Normalisation (format commun)\n• Corrélation (règles)\n• Alerte et tableau de bord\n\nExemple de règle de corrélation (pseudo) :\n\n```text\nSI 5 échecs de connexion\nDEPUIS la même IP\nEN moins de 1 minute\nALORS alerte \"Tentative de brute force\"\n```\n\n✅ Le SIEM transforme des **millions de lignes de logs** en quelques alertes exploitables par l'analyste.\n\n⚠️ Trop de règles mal réglées génèrent des **faux positifs** : l'analyste finit par ignorer les alertes (fatigue d'alerte). Il faut **affiner** les règles en continu.",
                    'questions' => [
                        ['question' => 'À quoi sert principalement un SIEM ?', 'options' => ['Héberger des sites web', 'Collecter, corréler les logs et générer des alertes', 'Remplacer le pare-feu', 'Chiffrer les disques'], 'correct' => [1], 'explanation' => 'Le SIEM centralise et corrèle les logs pour produire des alertes de sécurité.'],
                        ['question' => 'Qu\'est-ce qu\'un faux positif dans un SIEM ?', 'options' => ['Une vraie attaque détectée', 'Une alerte déclenchée sans menace réelle', 'Un serveur en panne', 'Un log effacé'], 'correct' => [1], 'explanation' => 'Un faux positif est une alerte sans menace réelle, source de fatigue d\'alerte si trop fréquente.'],
                        ['question' => 'Quels exemples sont des SIEM ?', 'options' => ['Wazuh', 'Splunk', 'Microsoft Word', 'Elastic SIEM'], 'correct' => [0, 1, 3], 'explanation' => 'Wazuh, Splunk et Elastic SIEM sont des SIEM ; Word est un traitement de texte.'],
                    ],
                ],
                [
                    'title' => 'Niveau 6 — Détection d\'intrusion (IDS/IPS)',
                    'subtitle' => 'Repérer l\'anomalie avant le drame',
                    'xp_reward' => 150,
                    'content' => "🎯 **Objectif** : distinguer **IDS** et **IPS** et comprendre les méthodes de détection.\n\n💡 Définitions :\n• **IDS** (Intrusion Detection System) : **détecte** et alerte, sans bloquer\n• **IPS** (Intrusion Prevention System) : détecte **et bloque** en temps réel\n\nMéthodes de détection :\n• **Par signature** : compare à une base d'attaques connues (efficace mais aveugle au nouveau)\n• **Par anomalie** : repère ce qui dévie du comportement normal (détecte l'inconnu, plus de faux positifs)\n\nOutils : Snort, Suricata, Zeek.\n\nExemple de règle Snort simplifiée :\n\n```text\nalert tcp any any -> 192.168.1.0/24 22 (msg:\"Scan SSH detecte\"; sid:1000001;)\n```\n\n✅ Placer un IDS sur le réseau interne permet de repérer un attaquant qui se **déplace latéralement** après avoir franchi le pare-feu.\n\n⚠️ La détection par signature ne voit pas les attaques **zero-day** : combinez-la avec la détection par anomalie.",
                    'questions' => [
                        ['question' => 'Quelle est la différence entre IDS et IPS ?', 'options' => ['L\'IDS bloque, l\'IPS alerte seulement', 'L\'IDS alerte, l\'IPS bloque en temps réel', 'Ils sont identiques', 'L\'IPS ne fonctionne que sur Windows'], 'correct' => [1], 'explanation' => 'L\'IDS se contente d\'alerter alors que l\'IPS bloque activement le trafic malveillant.'],
                        ['question' => 'Quelle méthode de détection repère les attaques inconnues (zero-day) ?', 'options' => ['Par signature', 'Par anomalie', 'Par mot de passe', 'Par chiffrement'], 'correct' => [1], 'explanation' => 'La détection par anomalie repère les écarts au comportement normal, donc des attaques inédites.'],
                        ['question' => 'Quels outils sont des systèmes de détection/prévention d\'intrusion ?', 'options' => ['Snort', 'Suricata', 'Photoshop', 'Zeek'], 'correct' => [0, 1, 3], 'explanation' => 'Snort, Suricata et Zeek analysent le trafic réseau ; Photoshop est un logiciel de graphisme.'],
                    ],
                ],
                [
                    'title' => 'Niveau 7 — Phishing et ingénierie sociale',
                    'subtitle' => 'L\'humain, cible n°1',
                    'xp_reward' => 160,
                    'content' => "🎯 **Objectif** : reconnaître et contrer le **phishing** et l'**ingénierie sociale**.\n\n💡 L'ingénierie sociale manipule la **confiance** des personnes. Variantes :\n• **Phishing** : e-mail frauduleux de masse\n• **Spear phishing** : ciblé sur une personne précise\n• **Vishing** : par appel téléphonique\n• **Smishing** : par SMS (faux message « votre compte Mobile Money est bloqué »)\n\nSignaux d'alerte d'un e-mail :\n• Adresse expéditeur suspecte (servlce-paie@... au lieu de service-paie@...)\n• Urgence et menace (« compte suspendu sous 24h »)\n• Lien qui ne pointe pas vers le vrai domaine\n• Fautes d'orthographe, demande d'identifiants\n\n✅ Réflexe : **survoler le lien** avant de cliquer, vérifier le domaine, et signaler au service IT.\n\n⚠️ Aucune banque sérieuse ne demande votre code PIN ou mot de passe par e-mail ou SMS. En cas de doute, **appelez le numéro officiel** vous-même.",
                    'questions' => [
                        ['question' => 'Comment appelle-t-on un phishing par SMS ?', 'options' => ['Vishing', 'Smishing', 'Spear phishing', 'Pharming'], 'correct' => [1], 'explanation' => 'Le smishing est l\'hameçonnage réalisé via SMS.'],
                        ['question' => 'Quel réflexe adopter face à un e-mail urgent demandant vos identifiants ?', 'options' => ['Cliquer vite pour ne pas perdre l\'accès', 'Vérifier l\'expéditeur et le lien sans cliquer, puis signaler', 'Répondre avec votre mot de passe', 'Transférer à tous vos collègues'], 'correct' => [1], 'explanation' => 'Il faut vérifier la source, ne pas cliquer et signaler à l\'équipe IT.'],
                        ['question' => 'Quels sont des signaux d\'alerte de phishing ?', 'options' => ['Un sentiment d\'urgence ou de menace', 'Une adresse expéditeur falsifiée', 'Un e-mail signé par votre collègue connu via canal vérifié', 'Un lien pointant vers un domaine différent du vrai'], 'correct' => [0, 1, 3], 'explanation' => 'Urgence, expéditeur falsifié et lien trompeur sont des signaux ; un message vérifié d\'un collègue ne l\'est pas.'],
                    ],
                ],
                [
                    'title' => 'Niveau 8 — Hygiène des mots de passe et MFA',
                    'subtitle' => 'Renforcer l\'authentification',
                    'xp_reward' => 170,
                    'content' => "🎯 **Objectif** : appliquer une bonne **hygiène d'authentification** et déployer le **MFA**.\n\n💡 Bonnes pratiques mots de passe :\n• Longs (≥ 12 caractères), uniques par service\n• Utiliser une **phrase de passe** mémorisable\n• Stocker dans un **gestionnaire** (KeePass, Bitwarden)\n• Ne jamais réutiliser le même mot de passe\n\nLe **MFA** (authentification multifacteur) combine plusieurs facteurs :\n• Ce que je **sais** (mot de passe)\n• Ce que je **possède** (téléphone, token, code OTP)\n• Ce que je **suis** (empreinte, visage)\n\n✅ Même si un mot de passe fuite, le MFA bloque l'attaquant qui n'a pas le second facteur.\n\n⚠️ Le SMS comme second facteur est mieux que rien, mais vulnérable au **SIM swapping**. Préférez une **application d'authentification** (TOTP) ou une clé physique. Forcez le MFA sur tous les comptes administrateurs et la messagerie.",
                    'questions' => [
                        ['question' => 'Que combine l\'authentification multifacteur (MFA) ?', 'options' => ['Plusieurs mots de passe du même type', 'Au moins deux facteurs de catégories différentes', 'Deux adresses e-mail', 'Deux navigateurs'], 'correct' => [1], 'explanation' => 'Le MFA combine des facteurs de natures différentes (savoir, posséder, être).'],
                        ['question' => 'Pourquoi un gestionnaire de mots de passe est-il recommandé ?', 'options' => ['Il ralentit les connexions', 'Il permet des mots de passe uniques et forts sans les retenir', 'Il supprime le besoin de MFA', 'Il partage vos mots de passe publiquement'], 'correct' => [1], 'explanation' => 'Le gestionnaire stocke des mots de passe uniques et complexes pour chaque service.'],
                        ['question' => 'Lesquels sont de bons facteurs d\'authentification MFA ?', 'options' => ['Un mot de passe (ce que je sais)', 'Un code OTP d\'une application (ce que je possède)', 'La couleur préférée écrite sur un post-it', 'Une empreinte digitale (ce que je suis)'], 'correct' => [0, 1, 3], 'explanation' => 'Mot de passe, OTP et biométrie sont des facteurs valides des trois catégories ; un post-it ne l\'est pas.'],
                    ],
                ],
                [
                    'title' => 'Niveau 9 — Sauvegardes et plan de reprise (PRA)',
                    'subtitle' => 'Survivre à l\'incident',
                    'xp_reward' => 185,
                    'content' => "🎯 **Objectif** : garantir la **disponibilité** des données via sauvegardes et **PRA**.\n\n💡 La règle d'or **3-2-1** :\n• **3** copies des données\n• sur **2** supports différents\n• dont **1** hors site (autre bâtiment, cloud)\n\nNotions de continuité :\n• **RPO** (Recovery Point Objective) : quantité de données qu'on accepte de perdre (ex. 1h)\n• **RTO** (Recovery Time Objective) : temps maximal pour redémarrer\n• **PRA** : Plan de Reprise d'Activité (technique)\n• **PCA** : Plan de Continuité d'Activité (global)\n\n✅ Face à un ransomware, une sauvegarde **isolée et testée** permet de restaurer **sans payer** la rançon.\n\n⚠️ Une sauvegarde jamais testée n'est pas une sauvegarde ! Vérifiez régulièrement la **restauration**. À Douala comme ailleurs, conservez une copie hors site pour résister à un incendie ou une inondation du local serveur.",
                    'questions' => [
                        ['question' => 'Que recommande la règle 3-2-1 ?', 'options' => ['3 mots de passe, 2 comptes, 1 admin', '3 copies, sur 2 supports, dont 1 hors site', '3 pare-feu, 2 antivirus, 1 VPN', '3 serveurs identiques au même endroit'], 'correct' => [1], 'explanation' => 'La règle 3-2-1 : trois copies, deux supports, une copie hors site.'],
                        ['question' => 'Que désigne le RTO ?', 'options' => ['La quantité de données perdues acceptable', 'Le temps maximal pour redémarrer le service', 'Le nombre de sauvegardes', 'Le débit du réseau'], 'correct' => [1], 'explanation' => 'Le RTO (Recovery Time Objective) est le délai maximal de remise en service.'],
                        ['question' => 'Pourquoi tester régulièrement ses sauvegardes ?', 'options' => ['Pour vérifier qu\'on peut réellement restaurer les données', 'Pour augmenter la taille des fichiers', 'Parce que c\'est inutile', 'Pour ralentir le réseau'], 'correct' => [0], 'explanation' => 'Une sauvegarde non testée peut être corrompue ; seul un test de restauration garantit sa fiabilité.'],
                    ],
                ],
                [
                    'title' => 'Niveau 10 — Gestion des incidents de sécurité',
                    'subtitle' => 'Réagir, contenir, apprendre',
                    'xp_reward' => 200,
                    'content' => "🎯 **Objectif** : conduire la **réponse à incident** de bout en bout (cadre NIST).\n\n💡 Les 6 phases :\n1. **Préparation** : procédures, contacts, outils prêts\n2. **Identification** : confirmer qu'il s'agit bien d'un incident\n3. **Confinement** : isoler la machine du réseau pour stopper la propagation\n4. **Éradication** : supprimer le malware, corriger la faille\n5. **Récupération** : restaurer depuis une sauvegarde saine, surveiller\n6. **Leçons apprises** : rapport post-incident, amélioration\n\n✅ Pendant le confinement, **débrancher le câble réseau** (sans éteindre, pour préserver les preuves en mémoire vive) est souvent le premier bon geste.\n\n⚠️ Ne jamais sauter la phase « leçons apprises » : c'est elle qui empêche que l'incident se reproduise.\n\n🏆 **Félicitations !** Tu maîtrises les fondamentaux de la Blue Team : menaces, CIA, durcissement, SIEM, IDS/IPS, phishing, MFA, sauvegardes et réponse à incident. Débouchés : **Analyste SOC niveau 1/2**, **Administrateur sécurité**, **Ingénieur SIEM**, puis évolution vers **Threat Hunter**, **DFIR (forensique)** ou **RSSI**. Vise des certifications comme CompTIA Security+, puis Blue Team Level 1 (BTL1) pour décrocher ton premier poste en SOC. La cybersécurité défensive recrute fortement en Afrique : à toi de jouer !",
                    'questions' => [
                        ['question' => 'Quelle phase consiste à isoler la machine pour stopper la propagation ?', 'options' => ['Identification', 'Confinement', 'Récupération', 'Préparation'], 'correct' => [1], 'explanation' => 'Le confinement isole le système touché afin d\'empêcher l\'attaque de s\'étendre.'],
                        ['question' => 'Pourquoi débrancher le réseau plutôt qu\'éteindre une machine compromise ?', 'options' => ['Pour économiser de l\'électricité', 'Pour préserver les preuves en mémoire vive', 'Parce que l\'extinction est interdite', 'Pour accélérer le redémarrage'], 'correct' => [1], 'explanation' => 'Débrancher le réseau stoppe la propagation tout en conservant les preuves volatiles en RAM.'],
                        ['question' => 'Quelles phases font partie de la réponse à incident (NIST) ?', 'options' => ['Préparation', 'Confinement', 'Facturation client', 'Leçons apprises'], 'correct' => [0, 1, 3], 'explanation' => 'Préparation, confinement et leçons apprises sont des phases du cycle ; la facturation n\'en fait pas partie.'],
                    ],
                ],
            ],
        ]);

        $this->command->info('  ✓ Roadmap Cybersécurité Blue Team créée (10 niveaux).');
    }
}
