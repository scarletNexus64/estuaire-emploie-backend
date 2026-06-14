<?php

namespace Database\Seeders\Roadmaps;

use Illuminate\Database\Seeder;

/**
 * Roadmap Administrateur Systèmes & Réseaux — des fondamentaux réseau à la sécurité et la supervision.
 */
class AdminSystemesReseauxRoadmapSeeder extends Seeder
{
    use RoadmapSeederHelper;

    public function run(): void
    {
        $this->createRoadmap([
            'title' => 'Deviens Administrateur Systèmes & Réseaux',
            'slug' => 'admin-systemes-reseaux',
            'domain' => 'developpement',
            'description' => "Maîtrise l'infrastructure informatique d'une entreprise : réseaux, serveurs Windows et Linux, Active Directory, sauvegardes, supervision et sécurité. Une formation orientée terrain, pensée pour les PME et institutions au Cameroun et en Afrique francophone.",
            'objectives' => "Comprendre les modèles OSI et TCP/IP\nMaîtriser l'adressage IP et le découpage en sous-réseaux\nConfigurer DNS, DHCP, routage et switching\nAdministrer des serveurs Windows et Linux\nGérer un annuaire Active Directory\nMettre en place sauvegardes, supervision et sécurité réseau",
            'icon' => '🖥️',
            'color' => '#2563EB',
            'difficulty' => 'intermediate',
            'required_packs' => [],
            'pass_threshold' => 70,
            'order' => 20,
            'levels' => [
                [
                    'title' => 'Niveau 1 — Les fondamentaux des réseaux',
                    'subtitle' => "Modèles OSI et TCP/IP, vocabulaire de base",
                    'xp_reward' => 100,
                    'content' => "🎯 **Objectif** : comprendre comment deux machines communiquent à travers un réseau.\n\n💡 Un réseau permet à des équipements (PC, serveurs, téléphones) d'échanger des données. Pour structurer cette communication, on utilise des modèles en couches.\n\nLe **modèle OSI** comporte 7 couches :\n• 1 Physique (câbles, fibre)\n• 2 Liaison (adresse MAC, switch)\n• 3 Réseau (adresse IP, routeur)\n• 4 Transport (TCP, UDP)\n• 5 Session\n• 6 Présentation\n• 7 Application (HTTP, DNS)\n\nLe **modèle TCP/IP**, plus pratique, en regroupe 4 : Accès réseau, Internet, Transport, Application.\n\n✅ Repère utile : « tout administrateur garde TCP/IP en tête au quotidien ».\n\n⚠️ TCP est fiable (accusé de réception, ex. transfert de fichier) tandis qu'UDP est rapide sans garantie (ex. streaming, appels VoIP courants à Douala et Yaoundé).",
                    'questions' => [
                        ['question' => "Combien de couches comporte le modèle OSI ?", 'options' => ['4', '5', '7', '8'], 'correct' => [2], 'explanation' => "Le modèle OSI est composé de 7 couches, de la couche physique à la couche application."],
                        ['question' => "À quelle couche OSI travaille un routeur ?", 'options' => ['Couche 2 (Liaison)', 'Couche 3 (Réseau)', 'Couche 4 (Transport)', 'Couche 7 (Application)'], 'correct' => [1], 'explanation' => "Le routeur opère à la couche 3 (Réseau) car il achemine les paquets selon les adresses IP."],
                        ['question' => "Quel protocole est fiable et garantit la livraison des données ?", 'options' => ['UDP', 'TCP', 'ICMP', 'ARP'], 'correct' => [1], 'explanation' => "TCP est orienté connexion et fiable grâce aux accusés de réception, contrairement à UDP."],
                    ],
                ],
                [
                    'title' => 'Niveau 2 — Adressage IP',
                    'subtitle' => "IPv4, classes, masques et IP publiques/privées",
                    'xp_reward' => 110,
                    'content' => "🎯 **Objectif** : savoir lire et interpréter une adresse IP.\n\n💡 Une adresse IPv4 est composée de 4 octets (0 à 255), ex. `192.168.1.10`. Elle s'accompagne d'un **masque** qui sépare la partie réseau de la partie hôte.\n\nNotation CIDR :\n```\n192.168.1.10/24\nmasque = 255.255.255.0\n```\n\nPlages d'adresses **privées** (non routables sur Internet) :\n• 10.0.0.0/8\n• 172.16.0.0/12\n• 192.168.0.0/16\n\n✅ Les autres plages sont **publiques**. Au Cameroun, votre box d'opérateur reçoit une IP publique côté Internet et distribue des IP privées en interne.\n\n⚠️ Deux machines ne peuvent pas avoir la même IP sur le même réseau : cela crée un **conflit d'adresses** et coupe la connexion. Pour calculer le nombre d'hôtes : 2^(bits hôtes) − 2 (adresse réseau et broadcast réservées).",
                    'questions' => [
                        ['question' => "Quelle plage correspond à des adresses IP privées ?", 'options' => ['8.8.8.0/24', '192.168.0.0/16', '200.10.0.0/16', '41.202.0.0/16'], 'correct' => [1], 'explanation' => "192.168.0.0/16 fait partie des plages privées définies par la RFC 1918."],
                        ['question' => "Que représente le /24 dans 192.168.1.10/24 ?", 'options' => ['Le nombre de machines', "Le nombre de bits du masque réseau", 'Le numéro du sous-réseau', 'La passerelle'], 'correct' => [1], 'explanation' => "Le /24 indique que les 24 premiers bits servent à la partie réseau (masque 255.255.255.0)."],
                        ['question' => "Quel est le nombre maximum d'hôtes utilisables dans un réseau /24 ?", 'options' => ['256', '254', '512', '128'], 'correct' => [1], 'explanation' => "Un /24 offre 256 adresses dont 254 utilisables, l'adresse réseau et le broadcast étant réservés."],
                    ],
                ],
                [
                    'title' => 'Niveau 3 — Sous-réseaux (subnetting)',
                    'subtitle' => "Découper un réseau en plusieurs segments",
                    'xp_reward' => 120,
                    'content' => "🎯 **Objectif** : découper efficacement un réseau pour mieux l'organiser et le sécuriser.\n\n💡 Le **subnetting** consiste à emprunter des bits à la partie hôte pour créer plusieurs sous-réseaux. Cela limite le trafic de diffusion et isole les départements (compta, RH, production).\n\nExemple : on dispose de `192.168.10.0/24` et on veut 4 sous-réseaux. On emprunte 2 bits → masque /26 (255.255.255.192).\n```\n192.168.10.0/26   -> hôtes .1 à .62\n192.168.10.64/26  -> hôtes .65 à .126\n192.168.10.128/26 -> hôtes .129 à .190\n192.168.10.192/26 -> hôtes .193 à .254\n```\n\nChaque /26 offre 62 hôtes utilisables (2^6 − 2).\n\n✅ Astuce : le « saut » entre sous-réseaux = 256 − valeur du masque (256 − 192 = 64).\n\n⚠️ Bien dimensionner : prévoir la croissance d'une PME, ne pas créer des sous-réseaux trop petits qui sature­raient rapidement.",
                    'questions' => [
                        ['question' => "Quel masque permet de créer 4 sous-réseaux à partir d'un /24 ?", 'options' => ['/25', '/26', '/27', '/28'], 'correct' => [1], 'explanation' => "Emprunter 2 bits donne 4 sous-réseaux, soit un masque /26 (255.255.255.192)."],
                        ['question' => "Combien d'hôtes utilisables dans un sous-réseau /26 ?", 'options' => ['64', '62', '30', '126'], 'correct' => [1], 'explanation' => "Un /26 a 6 bits d'hôtes : 2^6 − 2 = 62 hôtes utilisables."],
                        ['question' => "Quels avantages apporte le subnetting ? (plusieurs réponses)", 'options' => ['Réduire le trafic de broadcast', 'Augmenter automatiquement le débit Internet', "Isoler les services pour la sécurité", 'Supprimer le besoin de routeur'], 'correct' => [0, 2], 'explanation' => "Le subnetting limite les broadcasts et isole les segments, mais n'augmente pas le débit ni ne supprime le routeur."],
                    ],
                ],
                [
                    'title' => 'Niveau 4 — DNS et DHCP',
                    'subtitle' => "Résolution de noms et attribution automatique d'adresses",
                    'xp_reward' => 130,
                    'content' => "🎯 **Objectif** : comprendre deux services indispensables à tout réseau.\n\n💡 **DNS** (Domain Name System) traduit un nom de domaine en adresse IP. Sans lui, il faudrait retenir des IP au lieu de `www.exemple.cm`.\n\nTypes d'enregistrements DNS courants :\n• A → nom vers IPv4\n• AAAA → nom vers IPv6\n• MX → serveur de messagerie\n• CNAME → alias\n\n💡 **DHCP** (Dynamic Host Configuration Protocol) attribue automatiquement aux postes une IP, le masque, la passerelle et le DNS. Le processus suit 4 étapes (DORA) :\n```\nDiscover -> Offer -> Request -> Acknowledge\n```\n\n✅ Dans une PME à Yaoundé, le DHCP évite de configurer manuellement chaque poste : gain de temps énorme.\n\n⚠️ Pour les serveurs et imprimantes, on préfère une **IP fixe** (ou réservation DHCP) afin que leur adresse ne change pas. Pensez à dimensionner la plage DHCP selon le nombre de postes.",
                    'questions' => [
                        ['question' => "Quel est le rôle du DNS ?", 'options' => ['Attribuer des adresses IP', 'Traduire les noms de domaine en adresses IP', 'Filtrer le trafic réseau', 'Sauvegarder les données'], 'correct' => [1], 'explanation' => "Le DNS résout les noms de domaine en adresses IP pour faciliter l'accès aux ressources."],
                        ['question' => "Quelle est la séquence correcte du DHCP ?", 'options' => ['Request, Offer, Discover, Ack', 'Discover, Offer, Request, Ack', 'Offer, Discover, Ack, Request', 'Ack, Request, Offer, Discover'], 'correct' => [1], 'explanation' => "Le processus DHCP suit l'ordre DORA : Discover, Offer, Request, Acknowledge."],
                        ['question' => "Quel enregistrement DNS pointe vers un serveur de messagerie ?", 'options' => ['Enregistrement A', 'Enregistrement MX', 'Enregistrement CNAME', 'Enregistrement PTR'], 'correct' => [1], 'explanation' => "L'enregistrement MX (Mail eXchange) désigne le serveur de messagerie d'un domaine."],
                    ],
                ],
                [
                    'title' => 'Niveau 5 — Switching et VLAN',
                    'subtitle' => "Commutation, table MAC et réseaux virtuels",
                    'xp_reward' => 140,
                    'content' => "🎯 **Objectif** : maîtriser le fonctionnement des switches et la segmentation par VLAN.\n\n💡 Un **switch** (commutateur) relie les équipements d'un même réseau local. Il apprend les adresses **MAC** et construit une **table de commutation** pour envoyer chaque trame uniquement vers le bon port (contrairement au hub qui diffuse partout).\n\n💡 Un **VLAN** (Virtual LAN) sépare logiquement un même switch physique en plusieurs réseaux. On peut ainsi isoler la compta, la production et les invités sans tirer de nouveaux câbles.\n```\nVLAN 10 -> Comptabilité\nVLAN 20 -> Production\nVLAN 30 -> WiFi invités\n```\n\nLe **trunk** (802.1Q) transporte plusieurs VLAN entre switches.\n\n✅ Avantages des VLAN : sécurité, réduction des broadcasts, flexibilité.\n\n⚠️ Pour que deux VLAN communiquent, il faut un **routage inter-VLAN** (routeur ou switch de niveau 3) : par défaut, ils sont isolés.",
                    'questions' => [
                        ['question' => "Sur quoi se base un switch pour acheminer une trame ?", 'options' => ["L'adresse IP", "L'adresse MAC", 'Le numéro de port TCP', 'Le nom DNS'], 'correct' => [1], 'explanation' => "Un switch utilise les adresses MAC stockées dans sa table de commutation."],
                        ['question' => "Que permet un VLAN ?", 'options' => ['Augmenter le débit du câble', 'Segmenter logiquement un réseau', 'Remplacer le DNS', 'Chiffrer les données'], 'correct' => [1], 'explanation' => "Un VLAN crée une séparation logique de plusieurs réseaux sur une même infrastructure physique."],
                        ['question' => "Comment faire communiquer deux VLAN différents ?", 'options' => ['Ils communiquent automatiquement', 'Via un routage inter-VLAN', 'En les mettant sur le même port', 'Ce n est jamais possible'], 'correct' => [1], 'explanation' => "Les VLAN étant isolés, un routage inter-VLAN (niveau 3) est nécessaire pour les relier."],
                    ],
                ],
                [
                    'title' => 'Niveau 6 — Routage',
                    'subtitle' => "Routes statiques, dynamiques et passerelle",
                    'xp_reward' => 150,
                    'content' => "🎯 **Objectif** : comprendre comment les paquets voyagent entre réseaux distincts.\n\n💡 Le **routage** détermine le chemin d'un paquet d'un réseau à un autre. Chaque routeur possède une **table de routage** indiquant vers où envoyer le trafic.\n\nDeux familles :\n• **Routage statique** : routes saisies manuellement par l'administrateur. Simple, fiable, mais peu adapté aux grands réseaux.\n• **Routage dynamique** : les routeurs échangent leurs informations via des protocoles (RIP, OSPF, BGP) et s'adaptent automatiquement.\n\nLa **passerelle par défaut** (default gateway) est la route empruntée quand aucune autre ne correspond, typiquement vers Internet.\n```\nRoute par defaut : 0.0.0.0/0 -> 192.168.1.1\n```\n\n✅ OSPF est très utilisé en entreprise car il converge vite et calcule le meilleur chemin selon le coût.\n\n⚠️ Sans passerelle correcte, un poste accède au réseau local mais pas à Internet : panne fréquente dans les bureaux.",
                    'questions' => [
                        ['question' => "Qu'est-ce que la passerelle par défaut ?", 'options' => ['Le serveur DNS', "La route utilisée quand aucune autre ne correspond", "L'adresse du switch", 'Le pare-feu'], 'correct' => [1], 'explanation' => "La passerelle par défaut (0.0.0.0/0) est empruntée lorsque la destination n'est dans aucune route connue."],
                        ['question' => "Quel est un protocole de routage dynamique ?", 'options' => ['DHCP', 'OSPF', 'SMTP', 'FTP'], 'correct' => [1], 'explanation' => "OSPF est un protocole de routage dynamique qui calcule le meilleur chemin selon le coût des liens."],
                        ['question' => "Quel est l'avantage du routage statique ?", 'options' => ["S'adapte automatiquement aux pannes", 'Simple et prévisible sur petits réseaux', 'Ne nécessite aucune configuration', 'Chiffre le trafic'], 'correct' => [1], 'explanation' => "Le routage statique est simple et prévisible, idéal pour les petits réseaux stables."],
                    ],
                ],
                [
                    'title' => 'Niveau 7 — Serveurs Windows et Linux',
                    'subtitle' => "Rôles serveurs, services et administration de base",
                    'xp_reward' => 160,
                    'content' => "🎯 **Objectif** : poser les bases de l'administration des deux grands systèmes serveurs.\n\n💡 Un **serveur** fournit des services aux postes clients : fichiers, web, base de données, messagerie. On distingue principalement deux mondes.\n\n**Windows Server** : interface graphique, gestion par rôles (AD DS, DNS, DHCP, IIS), administration via PowerShell.\n```\nGet-Service        # lister les services\nNew-Item dossier   # creer un dossier\n```\n\n**Linux** (Debian, Ubuntu, CentOS) : très utilisé pour le web et les services, administration en ligne de commande.\n```\nsudo systemctl status apache2\nsudo apt update && sudo apt upgrade\nls -l /var/www\n```\n\n✅ Beaucoup d'hébergeurs et de PME africaines choisissent Linux pour son coût (gratuit) et sa robustesse.\n\n⚠️ Sécurité : ne jamais travailler en permanence en root/Administrateur, créer des comptes dédiés, et appliquer le principe du moindre privilège.",
                    'questions' => [
                        ['question' => "Quel outil permet d'administrer Windows Server en ligne de commande ?", 'options' => ['Bash', 'PowerShell', 'Cron', 'Vim'], 'correct' => [1], 'explanation' => "PowerShell est l'interpréteur et langage de script d'administration de Windows Server."],
                        ['question' => "Quelle commande Linux affiche l'état d'un service ?", 'options' => ['ls -l service', 'systemctl status nom_service', 'cat service', 'mkdir service'], 'correct' => [1], 'explanation' => "systemctl status permet de vérifier l'état d'un service géré par systemd."],
                        ['question' => "Pourquoi éviter de travailler en permanence en root/Administrateur ? (plusieurs réponses)", 'options' => ['Limiter les dégâts en cas d erreur', 'Réduire la surface d attaque', 'Cela accélère le serveur', 'Respecter le moindre privilège'], 'correct' => [0, 1, 3], 'explanation' => "Limiter les privilèges réduit les erreurs et les risques de sécurité, mais cela n'accélère pas le serveur."],
                    ],
                ],
                [
                    'title' => 'Niveau 8 — Active Directory',
                    'subtitle' => "Annuaire, domaine, GPO et gestion centralisée",
                    'xp_reward' => 170,
                    'content' => "🎯 **Objectif** : centraliser la gestion des utilisateurs et des postes d'une organisation.\n\n💡 **Active Directory (AD)** est l'annuaire de Microsoft. Il regroupe utilisateurs, ordinateurs et ressources dans un **domaine**. Le serveur qui l'héberge est un **contrôleur de domaine**.\n\nStructure :\n• Forêt → Domaine(s)\n• **OU** (unités d'organisation) → regroupent objets par service\n• Groupes → simplifient l'attribution de droits\n\nLes **GPO** (stratégies de groupe) appliquent des règles automatiquement : fond d'écran, mot de passe complexe, lecteurs réseau, blocage USB.\n```\nNew-ADUser -Name \"Jean Mbarga\" -Department \"Compta\"\n```\n\n✅ Avantage clé : un employé se connecte avec **un seul compte** sur n'importe quel poste du domaine. Idéal pour une administration ou une banque à Douala.\n\n⚠️ Sauvegardez régulièrement l'AD : la perte du contrôleur de domaine paralyse toute l'authentification de l'entreprise.",
                    'questions' => [
                        ['question' => "Quel serveur héberge l'annuaire Active Directory ?", 'options' => ['Le serveur de fichiers', 'Le contrôleur de domaine', 'Le serveur DHCP', 'Le proxy'], 'correct' => [1], 'explanation' => "Le contrôleur de domaine héberge la base Active Directory et gère l'authentification."],
                        ['question' => "À quoi servent les GPO ?", 'options' => ["Attribuer des adresses IP", 'Appliquer des stratégies aux utilisateurs et postes', 'Router les paquets', 'Sauvegarder les bases de données'], 'correct' => [1], 'explanation' => "Les GPO (stratégies de groupe) appliquent automatiquement des règles aux utilisateurs et ordinateurs."],
                        ['question' => "Que regroupe une OU (unité d'organisation) ?", 'options' => ['Des câbles réseau', 'Des objets AD (utilisateurs, ordinateurs)', 'Des VLAN', 'Des routes statiques'], 'correct' => [1], 'explanation' => "Une OU regroupe et organise les objets AD pour faciliter la gestion et l'application des GPO."],
                    ],
                ],
                [
                    'title' => 'Niveau 9 — Sauvegardes et supervision',
                    'subtitle' => "Stratégies de backup, RPO/RTO et monitoring",
                    'xp_reward' => 185,
                    'content' => "🎯 **Objectif** : garantir la disponibilité et la continuité du système d'information.\n\n💡 Une **sauvegarde** protège contre les pannes, suppressions et ransomwares. La règle d'or est le **3-2-1** :\n• 3 copies des données\n• sur 2 supports différents\n• dont 1 hors site (externalisée)\n\nTypes de sauvegardes :\n| Type | Contenu | Durée |\n|------|---------|-------|\n| Complète | Toutes les données | Longue |\n| Incrémentielle | Depuis la dernière sauvegarde | Rapide |\n| Différentielle | Depuis la dernière complète | Moyenne |\n\nDeux indicateurs clés : **RPO** (perte de données acceptable) et **RTO** (temps de remise en service).\n\n💡 La **supervision** (Nagios, Zabbix, PRTG) surveille en temps réel CPU, disque, services et alerte avant la panne.\n\n✅ Une PME à Yaoundé peut externaliser ses sauvegardes via mobile money/cloud pour résister aux coupures d'électricité.\n\n⚠️ Une sauvegarde jamais testée = aucune sauvegarde : faites des restaurations d'essai régulières.",
                    'questions' => [
                        ['question' => "Que signifie la règle de sauvegarde 3-2-1 ?", 'options' => ['3 serveurs, 2 réseaux, 1 admin', '3 copies, 2 supports, 1 hors site', '3 jours, 2 semaines, 1 mois', '3 disques RAID'], 'correct' => [1], 'explanation' => "La règle 3-2-1 préconise 3 copies sur 2 supports différents dont 1 conservée hors site."],
                        ['question' => "Que mesure le RTO ?", 'options' => ['La quantité de données perdue', 'Le temps de remise en service', 'La taille des disques', 'La vitesse du réseau'], 'correct' => [1], 'explanation' => "Le RTO (Recovery Time Objective) définit le temps maximal acceptable pour remettre le service en route."],
                        ['question' => "Quels outils servent à la supervision ? (plusieurs réponses)", 'options' => ['Zabbix', 'Nagios', 'PowerShell uniquement', 'PRTG'], 'correct' => [0, 1, 3], 'explanation' => "Zabbix, Nagios et PRTG sont des solutions de supervision; PowerShell est un outil d'administration, pas de monitoring dédié."],
                    ],
                ],
                [
                    'title' => 'Niveau 10 — Sécurité réseau',
                    'subtitle' => "Pare-feu, VPN, durcissement et bonnes pratiques",
                    'xp_reward' => 200,
                    'content' => "🎯 **Objectif** : protéger l'infrastructure contre les menaces et finaliser ta montée en compétences.\n\n💡 La sécurité réseau repose sur la **défense en profondeur** : plusieurs couches de protection.\n\nÉquipements et techniques clés :\n• **Pare-feu (firewall)** : filtre le trafic selon des règles (autorise/bloque ports et IP).\n• **VLAN** : isole les segments sensibles.\n• **VPN** : chiffre les connexions distantes (télétravail, sites multiples).\n• **NAT** : masque les IP internes.\n\nBonnes pratiques :\n• Mises à jour régulières (patchs)\n• Mots de passe forts + authentification à deux facteurs\n• Principe du moindre privilège\n• Journaux (logs) et supervision active\n```\n# Exemple de regle pare-feu (Linux ufw)\nsudo ufw allow 22/tcp\nsudo ufw deny 23/tcp\n```\n\n⚠️ Le maillon faible reste l'humain : sensibilisez aux mails de phishing, très répandus.\n\n🏆 **Félicitations !** Tu as parcouru les 10 niveaux et maîtrises désormais les fondations de l'administration systèmes et réseaux. Débouchés : Administrateur systèmes et réseaux, Technicien infrastructure, puis Ingénieur réseau, Architecte cloud, RSSI ou expert cybersécurité. Certifie-toi (Cisco CCNA, LPIC, Microsoft) et lance ta carrière !",
                    'questions' => [
                        ['question' => "Quel équipement filtre le trafic selon des règles d'autorisation ?", 'options' => ['Le switch', 'Le pare-feu (firewall)', 'Le serveur DNS', 'Le hub'], 'correct' => [1], 'explanation' => "Le pare-feu filtre le trafic entrant et sortant selon des règles définies par l'administrateur."],
                        ['question' => "À quoi sert principalement un VPN ?", 'options' => ['Accélérer Internet', 'Chiffrer et sécuriser les connexions distantes', 'Attribuer des IP', 'Stocker les sauvegardes'], 'correct' => [1], 'explanation' => "Un VPN chiffre le trafic pour permettre un accès distant sécurisé au réseau de l'entreprise."],
                        ['question' => "Quelles sont de bonnes pratiques de sécurité ? (plusieurs réponses)", 'options' => ['Mises à jour régulières', 'Partager le mot de passe admin à tous', "Authentification à deux facteurs", 'Principe du moindre privilège'], 'correct' => [0, 2, 3], 'explanation' => "Mises à jour, double authentification et moindre privilège renforcent la sécurité; partager le mot de passe admin est dangereux."],
                    ],
                ],
            ],
        ]);

        $this->command->info('  ✓ Roadmap Administrateur Systèmes & Réseaux créée (10 niveaux).');
    }
}
