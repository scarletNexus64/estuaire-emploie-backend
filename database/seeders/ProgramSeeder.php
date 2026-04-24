<?php

namespace Database\Seeders;

use App\Models\Program;
use App\Models\ProgramStep;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    /**
     * Seeder des 3 programmes professionnels principaux
     * 1. Transformation Professionnelle et Personnelle (OR/C2) - contient les 19 modules
     * 2. Immersion Professionnelle (DIAMANT/C3)
     * 3. Entrepreneuriat (DIAMANT/C3)
     */
    public function run(): void
    {
        $this->command->info('Création des 3 programmes professionnels...');

        ProgramStep::query()->delete();
        Program::query()->delete();

        $this->createTransformationProgram();
        $this->createImmersionProfessionnelleProgram();
        $this->createEntrepreneuriatProgram();

        $this->command->info('3 programmes créés avec succès !');
    }

    // ==========================================
    // 1. PROGRAMME DE TRANSFORMATION PRO/PERSO
    //    Pack OR (C2) - 19 modules/parties
    // ==========================================
    private function createTransformationProgram(): void
    {
        $program = Program::create([
            'title' => 'Programme de Transformation Professionnelle et Personnelle',
            'slug' => 'transformation-professionnelle-personnelle',
            'type' => 'transformation_professionnelle',
            'description' => 'Programme complet de formation regroupant 19 modules couvrant les compétences essentielles pour votre transformation professionnelle et personnelle. Des langues aux technologies, du développement personnel aux métiers techniques, ce programme vous offre un parcours riche et diversifié adapté au contexte camerounais et africain.',
            'objectives' => "Acquérir des compétences transversales dans 19 domaines clés\nRenforcer vos compétences linguistiques et bureautiques\nMaîtriser les outils numériques et technologiques modernes\nDévelopper votre potentiel personnel et professionnel\nExplorer des domaines techniques et spécialisés\nObtenir des certifications reconnues dans chaque module",
            'icon' => '🚀',
            'duration_weeks' => 52,
            'order' => 1,
            'is_active' => true,
            'required_packs' => ['C2'],
        ]);

        $steps = [
            // ---- MODULE 1 : ANGLAIS ----
            [
                'title' => 'Anglais Professionnel',
                'description' => 'Améliorez vos compétences en anglais avec des vidéos de formation captivantes. De la grammaire à la rédaction professionnelle, en passant par la préparation aux entretiens en anglais.',
                'content' => "Embarquez pour un voyage passionnant afin d'améliorer vos compétences en anglais !\n\n🎯 **Objectifs du module**:\n• Maîtriser les bases de la grammaire et du vocabulaire anglais\n• Développer des compétences en compréhension orale et écrite\n• Améliorer la prononciation et la fluidité à l'oral\n• Savoir rédiger des emails et documents professionnels en anglais\n• Préparer des entretiens d'embauche en anglais\n• Atteindre un niveau B1/B2 du CECRL\n\n✅ **Contenu**:\n• Leçons interactives et scénarios du monde réel\n• Exercices pratiques de compréhension orale\n• Ateliers de rédaction professionnelle\n• Simulations d'entretiens en anglais\n• Vocabulaire spécialisé par secteur d'activité",
                'order' => 1,
                'estimated_duration_days' => 30,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Vidéos de formation en anglais', 'url' => 'https://insamtechs.com', 'type' => 'video'],
                ],
            ],

            // ---- MODULE 2 : MARKETING ET COMMERCIALISATION ----
            [
                'title' => 'Marketing et Commercialisation',
                'description' => 'Explorez les stratégies de marketing moderne, digital et traditionnel. Apprenez à créer et gérer des campagnes publicitaires efficaces.',
                'content' => "Découvrez les secrets du marketing moderne !\n\n🎯 **Objectifs du module**:\n• Comprendre les fondamentaux du marketing et de la commercialisation\n• Maîtriser les stratégies de marketing digital et traditionnel\n• Savoir créer et gérer des campagnes publicitaires efficaces\n• Développer des compétences en communication et négociation commerciale\n• Comprendre le comportement des consommateurs\n• Maîtriser les outils de marketing moderne (CRM, Analytics, réseaux sociaux)\n\n✅ **Contenu**:\n• Stratégies marketing clés et tendances actuelles\n• Techniques de commercialisation efficaces\n• Marketing digital et gestion des réseaux sociaux\n• Études de cas adaptées au marché africain",
                'order' => 2,
                'estimated_duration_days' => 21,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Vidéos de formation en marketing', 'url' => 'https://insamtechs.com', 'type' => 'video'],
                ],
            ],

            // ---- MODULE 3 : DÉVELOPPEMENT PERSONNEL ----
            [
                'title' => 'Développement Personnel',
                'description' => 'Renforcez votre confiance, améliorez votre gestion du temps et cultivez un état d\'esprit positif pour réussir dans la vie académique et professionnelle.',
                'content' => "Révélez la meilleure version de vous-même !\n\n🎯 **Objectifs du module**:\n• Renforcer la confiance en soi et l'estime personnelle\n• Maîtriser la gestion du temps et des priorités\n• Développer l'intelligence émotionnelle et la résilience\n• Améliorer ses compétences en communication interpersonnelle\n• Apprendre à fixer et atteindre des objectifs ambitieux\n• Cultiver un état d'esprit de croissance (Growth Mindset)\n\n✅ **Contenu**:\n• Techniques pratiques de développement personnel\n• Exercices de coaching et auto-évaluation\n• Stratégies de gestion du stress et des émotions\n• Planification de carrière et vision personnelle",
                'order' => 3,
                'estimated_duration_days' => 21,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Vidéos de développement personnel', 'url' => 'https://insamtechs.com', 'type' => 'video'],
                ],
            ],

            // ---- MODULE 4 : LOGICIELS MICROSOFT ----
            [
                'title' => 'Logiciels Microsoft',
                'description' => 'Maîtrisez Word, Excel, PowerPoint, Outlook et Teams pour booster votre productivité et créer des documents professionnels.',
                'content' => "Optimisez votre efficacité avec les logiciels Microsoft !\n\n🎯 **Objectifs du module**:\n• Maîtriser Microsoft Word pour la rédaction de documents professionnels\n• Exceller dans Excel : formules, tableaux croisés dynamiques, graphiques\n• Créer des présentations PowerPoint percutantes et impactantes\n• Utiliser Outlook pour la gestion professionnelle des emails\n• Découvrir Microsoft Teams pour le travail collaboratif\n• Obtenir la certification Microsoft Office Specialist (MOS)\n\n✅ **Contenu**:\n• Astuces et techniques avancées pour chaque logiciel\n• Projets professionnels pratiques\n• Préparation à la certification MOS\n• Gestion efficace des données avec Excel",
                'order' => 4,
                'estimated_duration_days' => 21,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Vidéos de formation Microsoft', 'url' => 'https://insamtechs.com', 'type' => 'video'],
                ],
            ],

            // ---- MODULE 5 : RESSOURCES HUMAINES ----
            [
                'title' => 'Ressources Humaines',
                'description' => 'Maîtrisez les fondamentaux du recrutement, de la gestion des talents, de la paie et du droit du travail camerounais et africain.',
                'content' => "Devenez expert en gestion des ressources humaines !\n\n🎯 **Objectifs du module**:\n• Comprendre les fondamentaux de la gestion des ressources humaines\n• Maîtriser le processus de recrutement et de sélection\n• Savoir gérer la paie et les obligations sociales\n• Développer des compétences en gestion des talents et formation\n• Comprendre le droit du travail camerounais et africain\n• Maîtriser les outils RH modernes et la gestion administrative\n\n✅ **Contenu**:\n• Processus de recrutement et intégration\n• Gestion de la paie et administration du personnel\n• Droit du travail et réglementation sociale\n• Outils RH et digitalisation",
                'order' => 5,
                'estimated_duration_days' => 21,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Vidéos de formation RH', 'url' => 'https://insamtechs.com', 'type' => 'video'],
                ],
            ],

            // ---- MODULE 6 : AUTOMATISME ----
            [
                'title' => 'Automatisme Industriel',
                'description' => 'Découvrez l\'automatisme industriel : programmation d\'automates PLC/API, langages Ladder et Grafcet, capteurs et systèmes automatisés.',
                'content' => "Plongez dans l'univers de l'automatisme !\n\n🎯 **Objectifs du module**:\n• Comprendre les principes fondamentaux de l'automatisme industriel\n• Maîtriser la programmation des automates programmables (PLC/API)\n• Savoir câbler et configurer des systèmes automatisés\n• Apprendre les langages de programmation Ladder, Grafcet et ST\n• Comprendre les capteurs, actionneurs et interfaces homme-machine\n• Être capable de diagnostiquer et dépanner des installations automatisées\n\n✅ **Contenu**:\n• Concepts essentiels et applications pratiques\n• Projets captivants sur automates programmables\n• Travaux pratiques de câblage et configuration\n• Diagnostic et maintenance de systèmes",
                'order' => 6,
                'estimated_duration_days' => 30,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Vidéos de formation en automatisme', 'url' => 'https://insamtechs.com', 'type' => 'video'],
                ],
            ],

            // ---- MODULE 7 : COMPTABILITÉ ET FINANCE ----
            [
                'title' => 'Comptabilité et Finance',
                'description' => 'Maîtrisez la comptabilité générale, le plan SYSCOHADA, les états financiers, la gestion de trésorerie et la fiscalité en zone CEMAC.',
                'content' => "Bâtissez une carrière solide en comptabilité et finance !\n\n🎯 **Objectifs du module**:\n• Maîtriser les principes fondamentaux de la comptabilité générale\n• Comprendre le plan comptable SYSCOHADA (système comptable africain)\n• Savoir établir les états financiers (bilan, compte de résultat)\n• Maîtriser la gestion de trésorerie et le contrôle budgétaire\n• Comprendre la fiscalité des entreprises en zone CEMAC\n• Utiliser les logiciels de comptabilité professionnels (Sage, CIEL)\n\n✅ **Contenu**:\n• Principes fondamentaux et outils pratiques\n• Exercices sur le plan SYSCOHADA\n• Cas pratiques d'établissement d'états financiers\n• Formation aux logiciels Sage et CIEL",
                'order' => 7,
                'estimated_duration_days' => 30,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Vidéos de formation en comptabilité', 'url' => 'https://insamtechs.com', 'type' => 'video'],
                ],
            ],

            // ---- MODULE 8 : DESSIN TECHNIQUE DAO/CAO ----
            [
                'title' => 'Dessin Technique : DAO & CAO',
                'description' => 'Apprenez le dessin assisté par ordinateur avec AutoCAD, la modélisation 3D avec SolidWorks et la lecture de plans techniques normalisés.',
                'content' => "Transformez vos idées en réalisations concrètes !\n\n🎯 **Objectifs du module**:\n• Maîtriser les bases du dessin technique industriel\n• Utiliser AutoCAD pour le dessin assisté par ordinateur (DAO)\n• Apprendre la modélisation 3D avec SolidWorks ou Fusion 360\n• Savoir lire et interpréter des plans techniques normalisés\n• Créer des plans architecturaux et mécaniques professionnels\n• Comprendre les normes et conventions du dessin technique\n\n✅ **Contenu**:\n• Outils et techniques de DAO/CAO\n• Projets pratiques de dessin technique\n• Modélisation 3D et rendu\n• Normes et conventions internationales",
                'order' => 8,
                'estimated_duration_days' => 30,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Vidéos de formation en dessin technique', 'url' => 'https://insamtechs.com', 'type' => 'video'],
                ],
            ],

            // ---- MODULE 9 : INFOGRAPHIE ET WEB DESIGN ----
            [
                'title' => 'Infographie et Web Design',
                'description' => 'Maîtrisez Photoshop, Illustrator, le design UX/UI et la création de sites web attractifs et responsive.',
                'content' => "Donnez vie à vos idées créatives !\n\n🎯 **Objectifs du module**:\n• Maîtriser les principes fondamentaux du design graphique\n• Utiliser Photoshop, Illustrator et les outils de design modernes\n• Créer des sites web attractifs et responsive (HTML/CSS)\n• Comprendre l'UX/UI Design et l'expérience utilisateur\n• Réaliser des supports de communication visuelle professionnels\n• Développer une identité visuelle complète (logo, charte graphique)\n\n✅ **Contenu**:\n• Techniques de design modernes\n• Outils essentiels et astuces professionnelles\n• Projets de design graphique et web\n• Création de charte graphique complète",
                'order' => 9,
                'estimated_duration_days' => 30,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Vidéos de formation en infographie', 'url' => 'https://insamtechs.com', 'type' => 'video'],
                ],
            ],

            // ---- MODULE 10 : E-COMMERCE ----
            [
                'title' => 'E-Commerce',
                'description' => 'Créez, gérez et développez votre boutique en ligne. Maîtrisez le dropshipping, les marketplaces et les stratégies de vente en ligne.',
                'content' => "Lancez votre boutique en ligne avec succès !\n\n🎯 **Objectifs du module**:\n• Comprendre les fondamentaux du commerce électronique\n• Créer et configurer une boutique en ligne professionnelle\n• Maîtriser les stratégies de vente en ligne et le marketing e-commerce\n• Gérer la logistique, les paiements et le service client\n• Comprendre le dropshipping et les marketplaces\n• Optimiser les conversions et augmenter le chiffre d'affaires\n\n✅ **Contenu**:\n• Création de boutique en ligne pas à pas\n• Stratégies de marketing numérique\n• Gestion des stocks et logistique\n• Optimisation des conversions",
                'order' => 10,
                'estimated_duration_days' => 21,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Vidéos de formation en e-commerce', 'url' => 'https://insamtechs.com', 'type' => 'video'],
                ],
            ],

            // ---- MODULE 11 : ÉLECTRONIQUE ----
            [
                'title' => 'Électronique',
                'description' => 'Maîtrisez les composants électroniques, les lois de l\'électricité, la conception de circuits PCB et la programmation Arduino/ESP32.',
                'content' => "Devenez l'ingénieur de demain !\n\n🎯 **Objectifs du module**:\n• Comprendre les composants électroniques fondamentaux (résistances, condensateurs, transistors)\n• Maîtriser les lois de l'électricité (Ohm, Kirchhoff, Thévenin)\n• Savoir lire et créer des schémas électroniques\n• Apprendre la conception de circuits imprimés (PCB)\n• Programmer des microcontrôleurs (Arduino, ESP32)\n• Réaliser des projets électroniques pratiques\n\n✅ **Contenu**:\n• Circuits de base aux applications modernes\n• Projets stimulants et expériences pratiques\n• Conception PCB et prototypage\n• Programmation de microcontrôleurs",
                'order' => 11,
                'estimated_duration_days' => 30,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Vidéos de formation en électronique', 'url' => 'https://insamtechs.com', 'type' => 'video'],
                ],
            ],

            // ---- MODULE 12 : GESTION DE PROJETS ----
            [
                'title' => 'Gestion de Projets',
                'description' => 'Maîtrisez les méthodologies Waterfall, Agile et Scrum. Apprenez à planifier, exécuter et clôturer des projets avec succès.',
                'content' => "De l'idée à la réussite !\n\n🎯 **Objectifs du module**:\n• Maîtriser les méthodologies de gestion de projets (Waterfall, Agile, Scrum)\n• Savoir planifier, exécuter et clôturer un projet avec succès\n• Gérer les ressources, les budgets et les délais\n• Utiliser les outils de gestion de projets (MS Project, Trello, Asana)\n• Développer des compétences en leadership et communication d'équipe\n• Préparer la certification PMP ou CAPM\n\n✅ **Contenu**:\n• Outils et techniques de gestion de projets\n• Cas pratiques et mises en situation\n• Préparation aux certifications\n• Leadership et gestion d'équipe",
                'order' => 12,
                'estimated_duration_days' => 21,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Vidéos de formation en gestion de projets', 'url' => 'https://insamtechs.com', 'type' => 'video'],
                ],
            ],

            // ---- MODULE 13 : PROGRAMMATION ----
            [
                'title' => 'Programmation Informatique',
                'description' => 'Initiez-vous aux langages Python, JavaScript et PHP. Développez des applications web et créez votre portfolio de projets.',
                'content' => "Codez votre avenir !\n\n🎯 **Objectifs du module**:\n• Comprendre les concepts fondamentaux de la programmation\n• Maîtriser au moins un langage de programmation (Python, JavaScript, PHP)\n• Développer des applications web complètes (frontend + backend)\n• Comprendre les bases de données et le SQL\n• Utiliser Git et GitHub pour le versioning\n• Créer un portfolio de projets pour votre carrière\n\n✅ **Contenu**:\n• Langages de programmation essentiels\n• Concepts de base aux projets pratiques\n• Développement d'applications concrètes\n• Gestion de code source avec Git",
                'order' => 13,
                'estimated_duration_days' => 30,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Vidéos de formation en programmation', 'url' => 'https://insamtechs.com', 'type' => 'video'],
                ],
            ],

            // ---- MODULE 14 : SYSTÈMES INFORMATIQUES ----
            [
                'title' => 'Systèmes Informatiques',
                'description' => 'Maîtrisez l\'administration Windows Server et Linux, la virtualisation VMware/Hyper-V et la maintenance des systèmes.',
                'content' => "Dominez les systèmes informatiques !\n\n🎯 **Objectifs du module**:\n• Comprendre l'architecture des systèmes informatiques\n• Maîtriser l'administration de Windows Server et Linux\n• Savoir installer, configurer et maintenir des systèmes d'exploitation\n• Gérer la sécurité des systèmes et les sauvegardes\n• Comprendre la virtualisation (VMware, Hyper-V)\n• Diagnostiquer et résoudre les problèmes système courants\n\n✅ **Contenu**:\n• Administration système Windows et Linux\n• Configuration et maintenance\n• Virtualisation et cloud computing\n• Sécurité et sauvegardes",
                'order' => 14,
                'estimated_duration_days' => 30,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Vidéos de formation en systèmes informatiques', 'url' => 'https://insamtechs.com', 'type' => 'video'],
                ],
            ],

            // ---- MODULE 15 : INTELLIGENCE ARTIFICIELLE ----
            [
                'title' => 'Intelligence Artificielle',
                'description' => 'Découvrez le Machine Learning, le Deep Learning, Python et les outils d\'IA générative comme ChatGPT, Claude et Midjourney.',
                'content' => "Transformez vos idées en innovations !\n\n🎯 **Objectifs du module**:\n• Comprendre les concepts fondamentaux de l'intelligence artificielle\n• Maîtriser les bases du Machine Learning et du Deep Learning\n• Savoir utiliser Python et les bibliothèques IA (TensorFlow, scikit-learn)\n• Comprendre le traitement du langage naturel (NLP)\n• Apprendre à utiliser les outils d'IA générative (ChatGPT, Claude, Midjourney)\n• Développer des projets pratiques avec l'IA\n\n✅ **Contenu**:\n• Concepts fondamentaux et algorithmes clés\n• Applications pratiques de l'IA\n• Projets avec Python et bibliothèques IA\n• Outils d'IA générative modernes",
                'order' => 15,
                'estimated_duration_days' => 30,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Vidéos de formation en IA', 'url' => 'https://insamtechs.com', 'type' => 'video'],
                ],
            ],

            // ---- MODULE 16 : MONNAIES VIRTUELLES / CRYPTO ----
            [
                'title' => 'Monnaies Virtuelles et Cryptomonnaies',
                'description' => 'Comprenez la blockchain, Bitcoin, Ethereum, la DeFi et les NFTs. Apprenez à investir de manière responsable dans les cryptomonnaies.',
                'content' => "Devenez un expert en crypto !\n\n🎯 **Objectifs du module**:\n• Comprendre les fondamentaux de la blockchain et des cryptomonnaies\n• Maîtriser les principes du Bitcoin, Ethereum et des altcoins\n• Savoir investir de manière responsable dans les cryptomonnaies\n• Comprendre la DeFi (finance décentralisée) et les NFTs\n• Apprendre la sécurité des portefeuilles crypto et la gestion des risques\n• Connaître la réglementation et la fiscalité des cryptomonnaies\n\n✅ **Contenu**:\n• Concepts fondamentaux de la blockchain\n• Stratégies d'investissement responsable\n• Sécurité et gestion des risques\n• Réglementation et fiscalité",
                'order' => 16,
                'estimated_duration_days' => 21,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Vidéos de formation en crypto', 'url' => 'https://insamtechs.com', 'type' => 'video'],
                ],
            ],

            // ---- MODULE 17 : DJ / MUSIQUE ÉLECTRONIQUE ----
            [
                'title' => 'DJ et Musique Électronique',
                'description' => 'Maîtrisez les techniques de mixage, les logiciels FL Studio et Ableton Live, et développez votre marque personnelle de DJ.',
                'content' => "Maîtrisez l'art de la musique électronique !\n\n🎯 **Objectifs du module**:\n• Comprendre les bases théoriques de la musique (tempo, tonalité, structure)\n• Maîtriser les techniques de mixage DJ (beatmatching, EQ, transitions)\n• Utiliser les logiciels de production musicale (FL Studio, Ableton Live)\n• Savoir créer des sets et programmer une soirée\n• Comprendre le matériel DJ (platines, contrôleurs, tables de mixage)\n• Développer sa marque personnelle en tant que DJ\n\n✅ **Contenu**:\n• Techniques de mixage et production\n• Maîtrise des logiciels musicaux\n• Création de sets professionnels\n• Personal branding pour DJ",
                'order' => 17,
                'estimated_duration_days' => 21,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Vidéos de formation DJ', 'url' => 'https://insamtechs.com', 'type' => 'video'],
                ],
            ],

            // ---- MODULE 18 : PÉDAGOGIE ----
            [
                'title' => 'Pédagogie et Enseignement',
                'description' => 'Maîtrisez les théories éducatives, les méthodes d\'enseignement innovantes et l\'intégration des outils numériques dans l\'éducation.',
                'content' => "Devenez un expert en pédagogie !\n\n🎯 **Objectifs du module**:\n• Comprendre les théories de l'apprentissage et les courants pédagogiques\n• Maîtriser les techniques d'enseignement modernes et innovantes\n• Savoir concevoir des séquences pédagogiques efficaces\n• Développer des compétences en gestion de classe\n• Intégrer les outils numériques dans l'enseignement\n• Adapter sa pédagogie aux différents profils d'apprenants\n\n✅ **Contenu**:\n• Théories éducatives et méthodes innovantes\n• Conception de séquences pédagogiques\n• Gestion de classe et motivation\n• Outils numériques éducatifs",
                'order' => 18,
                'estimated_duration_days' => 21,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Vidéos de formation en pédagogie', 'url' => 'https://insamtechs.com', 'type' => 'video'],
                ],
            ],

            // ---- MODULE 19 : RÉSEAUX INFORMATIQUES ----
            [
                'title' => 'Réseaux Informatiques',
                'description' => 'Maîtrisez les fondamentaux des réseaux, la configuration d\'équipements, la sécurité réseau et préparez la certification Cisco CCNA.',
                'content' => "Maîtrisez les réseaux informatiques !\n\n🎯 **Objectifs du module**:\n• Comprendre les fondamentaux des réseaux informatiques (modèle OSI, TCP/IP)\n• Maîtriser la configuration des équipements réseau (routeurs, switches)\n• Apprendre l'adressage IP, le subnetting et le routage\n• Comprendre les protocoles réseau essentiels (DNS, DHCP, HTTP, FTP)\n• Maîtriser la sécurité réseau (pare-feu, VPN, IDS/IPS)\n• Préparer la certification Cisco CCNA\n\n✅ **Contenu**:\n• Architecture réseau et protocoles\n• Configuration de routeurs et switches\n• Sécurité informatique et VPN\n• Préparation certification CCNA",
                'order' => 19,
                'estimated_duration_days' => 30,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Vidéos de formation en réseaux', 'url' => 'https://insamtechs.com', 'type' => 'video'],
                ],
            ],
        ];

        foreach ($steps as $stepData) {
            ProgramStep::create(array_merge($stepData, ['program_id' => $program->id]));
        }

        $this->command->info('Programme Transformation Professionnelle et Personnelle créé (19 modules)');
    }

    // ==========================================
    // 2. PROGRAMME D'IMMERSION PROFESSIONNELLE
    //    Pack DIAMANT (C3) - 8 étapes
    // ==========================================
    private function createImmersionProfessionnelleProgram(): void
    {
        $program = Program::create([
            'title' => 'Programme d\'Immersion Professionnelle en Entreprise',
            'slug' => 'immersion-professionnelle-entreprise',
            'type' => 'immersion_professionnelle',
            'description' => 'Programme intensif de 16 semaines permettant aux candidats de découvrir et s\'intégrer dans le monde professionnel à travers des stages pratiques, un accompagnement personnalisé et un mentorat continu au sein d\'entreprises partenaires au Cameroun.',
            'objectives' => "Acquérir une expérience professionnelle concrète en entreprise\nDévelopper des compétences techniques et comportementales recherchées\nCréer un réseau professionnel solide et durable\nMaîtriser les codes et la culture d'entreprise camerounaise\nObtenir une attestation de stage valorisante\nFaciliter la transition vers un emploi stable\nDévelopper son personal branding professionnel\nBénéficier d'un mentorat post-immersion de 3 mois",
            'icon' => '🌟',
            'duration_weeks' => 16,
            'order' => 2,
            'is_active' => true,
            'required_packs' => ['C3'],
        ]);

        $steps = [
            // ---- ÉTAPE 1 ----
            [
                'title' => 'Bilan de Compétences et Projet Professionnel',
                'description' => 'Évaluation approfondie de votre profil et construction d\'un projet professionnel clair et réaliste',
                'content' => "Cette première étape établit les fondations de votre parcours d'immersion.\n\n✅ **Activités**:\n• Entretien individuel approfondi avec un conseiller en orientation (2h)\n• Tests de compétences techniques adaptés à votre domaine\n• Tests psychométriques professionnels (MBTI, DISC, Gallup Strengths)\n• Identification de vos forces, talents naturels et axes d'amélioration\n• Cartographie de vos expériences et acquis (formels et informels)\n• Exploration des secteurs d'activité porteurs au Cameroun (Douala, Yaoundé, Bafoussam)\n• Définition d'objectifs SMART à court terme (3 mois) et moyen terme (1 an)\n• Élaboration d'un plan d'action personnalisé\n\n📊 **Livrables**:\n• Rapport de bilan de compétences détaillé (15 pages)\n• Profil psychométrique complet avec analyse\n• Projet professionnel formalisé et validé par le conseiller\n• Plan d'action personnalisé avec jalons et indicateurs\n\n💡 **Méthodologie**:\n• Approche centrée sur les résultats concrets\n• Outils d'évaluation certifiés et reconnus internationalement\n• Accompagnement bienveillant et exigeant",
                'order' => 1,
                'estimated_duration_days' => 5,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Guide d\'auto-évaluation professionnelle', 'url' => 'https://docs.google.com/document/d/guide-evaluation', 'type' => 'document'],
                    ['title' => 'Vidéo: Comment définir son projet professionnel', 'url' => 'https://www.youtube.com/watch?v=projet-pro', 'type' => 'video'],
                    ['title' => 'Test de personnalité MBTI', 'url' => 'https://www.16personalities.com/fr', 'type' => 'link'],
                    ['title' => 'Grille de cartographie des compétences', 'url' => 'https://docs.google.com/grille-competences', 'type' => 'document'],
                ],
            ],

            // ---- ÉTAPE 2 ----
            [
                'title' => 'Construction du Dossier de Candidature',
                'description' => 'Création d\'un CV professionnel impactant, lettres de motivation ciblées et portfolio de compétences',
                'content' => "Préparez un dossier de candidature qui vous démarque de la concurrence.\n\n✅ **Activités**:\n• Atelier de rédaction de CV professionnel (format africain et international)\n• Techniques de rédaction de lettres de motivation personnalisées\n• Création d'un profil LinkedIn optimisé et professionnel\n• Construction d'un portfolio de compétences (physique et digital)\n• Photoshoot professionnel pour vos supports de candidature\n• Rédaction d'un pitch personnel de 30 secondes, 1 minute et 3 minutes\n• Révision par des professionnels RH partenaires\n\n📊 **Livrables**:\n• CV professionnel optimisé (2 versions : français et anglais)\n• 5 lettres de motivation personnalisées pour différents secteurs\n• Profil LinkedIn complété et optimisé\n• Portfolio digital de compétences\n• Photo professionnelle haute qualité\n\n💡 **Conseil**:\n• Chaque document est revu et corrigé individuellement\n• Adaptation aux standards des entreprises camerounaises et multinationales",
                'order' => 2,
                'estimated_duration_days' => 5,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Modèles de CV professionnels 2026', 'url' => 'https://canva.com/templates/cv-professionnel', 'type' => 'document'],
                    ['title' => 'Guide: Optimiser son profil LinkedIn', 'url' => 'https://docs.google.com/guide-linkedin', 'type' => 'document'],
                    ['title' => 'Vidéo: Les erreurs fatales dans un CV', 'url' => 'https://www.youtube.com/watch?v=erreurs-cv', 'type' => 'video'],
                    ['title' => 'Templates de lettres de motivation', 'url' => 'https://docs.google.com/templates-lettre-motivation', 'type' => 'document'],
                ],
            ],

            // ---- ÉTAPE 3 ----
            [
                'title' => 'Recherche et Sélection d\'Entreprise d\'Accueil',
                'description' => 'Identification stratégique des entreprises partenaires et processus de matching candidat-entreprise',
                'content' => "Trouvez l'entreprise d'accueil idéale pour votre immersion.\n\n✅ **Activités**:\n• Consultation du réseau de 200+ entreprises partenaires au Cameroun\n• Analyse des secteurs porteurs : pétrole/gaz, télécoms, banque, BTP, commerce, tech\n• Matching intelligent entre votre profil et les besoins des entreprises\n• Préparation de candidatures ciblées pour 5-10 entreprises\n• Sessions de simulation d'entretiens filmées avec feedback détaillé\n• Coaching sur la négociation des conditions de stage\n• Entretiens réels avec les entreprises présélectionnées\n• Signature de la convention de stage tripartite\n\n📊 **Livrables**:\n• Liste de 10 entreprises cibles avec stratégie d'approche\n• Convention de stage signée\n• Planning d'immersion validé\n\n🏢 **Secteurs partenaires**:\n• Énergie & Mines : Total Energies, Perenco Cameroun, SNH\n• Télécoms : MTN, Airtel, Camtel\n• Banque & Finance : BGFI, LCB, UBA, Ecobank\n• BTP & Immobilier : SOCOFRAN, SGTC\n• Commerce & Distribution : Casino, Carrefour\n• Tech & Digital : startups locales et cabinets IT",
                'order' => 3,
                'estimated_duration_days' => 7,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Annuaire des entreprises partenaires 2026', 'url' => 'https://drive.google.com/entreprises-partenaires', 'type' => 'document'],
                    ['title' => 'Vidéo: Réussir son entretien d\'embauche', 'url' => 'https://www.youtube.com/watch?v=entretien-embauche', 'type' => 'video'],
                    ['title' => 'Guide des secteurs porteurs au Cameroun', 'url' => 'https://docs.google.com/secteurs-porteurs-cameroun', 'type' => 'document'],
                ],
            ],

            // ---- ÉTAPE 4 ----
            [
                'title' => 'Formation Pré-Immersion : Soft Skills et Culture d\'Entreprise',
                'description' => 'Bootcamp intensif de 5 jours sur les codes de l\'entreprise, la communication professionnelle et le savoir-être',
                'content' => "Préparez-vous aux exigences du monde professionnel camerounais.\n\n✅ **Programme du bootcamp (5 jours)**:\n\n📅 **Jour 1 - Culture d'entreprise au Cameroun**:\n• Les codes vestimentaires selon les secteurs\n• Hiérarchie et protocole en entreprise camerounaise\n• Les différences culturelles entreprise locale vs multinationale\n• Gestion des relations avec les aînés et la hiérarchie\n\n📅 **Jour 2 - Communication professionnelle**:\n• Communication orale : prise de parole, réunions, présentations\n• Communication écrite : emails, rapports, comptes rendus\n• Communication non-verbale : posture, regard, poignée de main\n• L'art de l'écoute active et du feedback constructif\n\n📅 **Jour 3 - Travail en équipe**:\n• Intelligence collaborative et travail transversal\n• Gestion des conflits et diplomatie en entreprise\n• Techniques de négociation et d'influence positive\n• Jeux de rôles et mises en situation réelles\n\n📅 **Jour 4 - Outils et productivité**:\n• Suite Microsoft Office : Word, Excel, PowerPoint (rappels rapides)\n• Outils collaboratifs : Teams, Slack, Trello, Google Workspace\n• Gestion du temps : méthode Pomodoro, matrice Eisenhower, GTD\n• Organisation personnelle et gestion des priorités\n\n📅 **Jour 5 - Simulation et certification**:\n• Simulation complète d'une journée en entreprise\n• Évaluation des acquis par un jury professionnel\n• Remise du certificat \"Soft Skills Professionnelles\"\n• Derniers conseils avant l'immersion\n\n📊 **Certification**:\n• Certificat \"Compétences Professionnelles\" délivré par Estuaire Emploi",
                'order' => 4,
                'estimated_duration_days' => 5,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Guide des bonnes pratiques en entreprise camerounaise', 'url' => 'https://docs.google.com/guide-entreprise-cameroun', 'type' => 'document'],
                    ['title' => 'Vidéo: Les codes de l\'entreprise en Afrique', 'url' => 'https://www.youtube.com/watch?v=codes-entreprise-afrique', 'type' => 'video'],
                    ['title' => 'Checklist de préparation à l\'immersion', 'url' => 'https://trello.com/checklist-immersion', 'type' => 'document'],
                    ['title' => 'Guide: Maîtriser les outils collaboratifs', 'url' => 'https://docs.google.com/guide-outils-collaboratifs', 'type' => 'document'],
                ],
            ],

            // ---- ÉTAPE 5 ----
            [
                'title' => 'Immersion Phase 1 : Observation et Intégration (4 semaines)',
                'description' => 'Première phase d\'immersion axée sur la découverte de l\'entreprise, l\'observation des processus et l\'intégration dans l\'équipe',
                'content' => "Les 4 premières semaines sont dédiées à votre intégration.\n\n✅ **Semaine 1 - Accueil et découverte**:\n• Accueil officiel par le tuteur entreprise\n• Visite complète des locaux et présentation aux équipes\n• Découverte de l'organigramme et des processus internes\n• Installation du poste de travail et accès aux outils\n• Premiers échanges avec les collègues\n\n✅ **Semaine 2 - Observation active**:\n• Observation des pratiques métier et des workflows\n• Participation aux réunions d'équipe en tant qu'observateur\n• Prise de notes détaillées dans le journal de bord\n• Identification des opportunités de contribution\n• Premier point hebdomadaire avec le tuteur\n\n✅ **Semaine 3 - Premières missions**:\n• Prise en charge de tâches simples et encadrées\n• Collaboration avec les collègues sur des projets en cours\n• Approfondissement de la compréhension du métier\n• Participation active aux réunions\n\n✅ **Semaine 4 - Validation d'intégration**:\n• Évaluation intermédiaire avec le tuteur\n• Feedback des collègues sur votre intégration\n• Ajustement des objectifs si nécessaire\n• Point de suivi avec le conseiller Estuaire Emploi\n\n📊 **Suivi**:\n• Journal de bord quotidien obligatoire\n• Point hebdomadaire avec le tuteur entreprise\n• Point bimensuel avec le conseiller du programme",
                'order' => 5,
                'estimated_duration_days' => 28,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Modèle de journal de bord professionnel', 'url' => 'https://notion.so/journal-bord-template', 'type' => 'document'],
                    ['title' => 'Checklist d\'intégration en entreprise', 'url' => 'https://docs.google.com/checklist-integration', 'type' => 'document'],
                    ['title' => 'Guide: Comment créer de la valeur dès la 1ère semaine', 'url' => 'https://medium.com/creer-valeur-semaine1', 'type' => 'article'],
                ],
            ],

            // ---- ÉTAPE 6 ----
            [
                'title' => 'Immersion Phase 2 : Autonomie et Contribution (4 semaines)',
                'description' => 'Deuxième phase d\'immersion axée sur la prise d\'autonomie, la gestion de projets et la création de valeur mesurable',
                'content' => "Passez à la vitesse supérieure et démontrez votre valeur ajoutée.\n\n✅ **Semaine 5-6 - Montée en compétences**:\n• Prise en charge de missions de plus en plus complexes\n• Gestion autonome de mini-projets sous supervision\n• Proposition d'idées d'amélioration des processus existants\n• Participation active à la résolution de problèmes concrets\n• Développement de compétences techniques spécifiques au poste\n\n✅ **Semaine 7-8 - Projet personnel d'immersion**:\n• Réalisation d'un projet concret et mesurable pour l'entreprise\n• Exemples : optimisation d'un processus, création d'un outil, étude de marché, rapport d'analyse\n• Présentation du projet au tuteur et à l'équipe\n• Collecte de feedback et itérations\n• Documentation du projet pour le rapport final\n\n📊 **Indicateurs de réussite**:\n• Niveau d'autonomie atteint sur les tâches confiées\n• Qualité et impact du projet personnel réalisé\n• Feedback positif du tuteur et de l'équipe\n• Compétences techniques acquises et démontrées\n\n💡 **Objectif clé**:\n• Prouver votre valeur ajoutée à l'entreprise\n• Créer les conditions d'une proposition d'embauche ou de recommandation",
                'order' => 6,
                'estimated_duration_days' => 28,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Guide: Gérer son premier projet en entreprise', 'url' => 'https://docs.google.com/guide-premier-projet', 'type' => 'document'],
                    ['title' => 'Vidéo: Comment se rendre indispensable en stage', 'url' => 'https://www.youtube.com/watch?v=indispensable-stage', 'type' => 'video'],
                    ['title' => 'Template de rapport de projet', 'url' => 'https://docs.google.com/template-rapport-projet', 'type' => 'document'],
                ],
            ],

            // ---- ÉTAPE 7 ----
            [
                'title' => 'Networking et Personal Branding',
                'description' => 'Construction d\'un réseau professionnel solide et développement de votre marque personnelle pendant et après l\'immersion',
                'content' => "Votre réseau est votre capital le plus précieux au Cameroun.\n\n✅ **Stratégies de networking**:\n• Créer des relations authentiques avec vos collègues et managers\n• Participer aux événements internes de l'entreprise (afterworks, séminaires)\n• Identifier et contacter les décideurs clés de votre secteur\n• Rejoindre les associations professionnelles camerounaises\n• Participer aux meetups tech, business et entrepreneuriat locaux\n• Entretenir votre réseau : la règle des 3 contacts par semaine\n\n✅ **Personal Branding**:\n• Développer une présence LinkedIn professionnelle et active\n• Partager du contenu à valeur ajoutée sur votre domaine d'expertise\n• Créer un portfolio en ligne de vos réalisations\n• Obtenir des recommandations LinkedIn de vos tuteurs et collègues\n• Construire votre réputation de professionnel fiable et compétent\n\n✅ **Activités pratiques**:\n• Atelier de networking en présentiel avec des professionnels\n• Exercice : contacter 5 professionnels de votre secteur cette semaine\n• Création d'une carte de visite professionnelle\n• Simulation de conversations de networking\n\n📊 **Objectifs**:\n• Réseau de 50+ contacts professionnels qualifiés\n• Profil LinkedIn avec 200+ connexions pertinentes\n• Au moins 3 recommandations LinkedIn obtenues\n• Participation à 2+ événements professionnels",
                'order' => 7,
                'estimated_duration_days' => 7,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Guide du networking professionnel au Cameroun', 'url' => 'https://docs.google.com/networking-cameroun', 'type' => 'document'],
                    ['title' => 'Vidéo: L\'art du networking en Afrique', 'url' => 'https://www.youtube.com/watch?v=networking-afrique', 'type' => 'video'],
                    ['title' => 'Liste des événements professionnels au Cameroun 2026', 'url' => 'https://airtable.com/evenements-pro-cameroun', 'type' => 'link'],
                    ['title' => 'Templates de messages de networking', 'url' => 'https://docs.google.com/templates-networking', 'type' => 'document'],
                ],
            ],

            // ---- ÉTAPE 8 ----
            [
                'title' => 'Bilan Final, Certification et Accompagnement Post-Immersion',
                'description' => 'Évaluation complète de l\'immersion, remise de certificat et lancement du plan de carrière avec mentorat de 3 mois',
                'content' => "Finalisez votre immersion et lancez votre carrière professionnelle.\n\n✅ **Bilan et évaluation**:\n• Débriefing approfondi avec le tuteur entreprise (évaluation 360°)\n• Évaluation détaillée des compétences acquises (grille de 60+ compétences)\n• Rédaction du rapport d'immersion complet (15-20 pages)\n• Présentation orale du bilan devant un jury (tuteur + conseiller)\n• Feedback constructif et axes de progression identifiés\n\n✅ **Certification et recommandations**:\n• Attestation de stage officielle signée par l'entreprise\n• Certificat Estuaire Emploi \"Immersion Professionnelle Réussie\"\n• Lettre de recommandation du tuteur entreprise\n• Recommandations LinkedIn du tuteur et des collègues\n• Évaluation des compétences par l'entreprise d'accueil\n\n✅ **Plan de carrière post-immersion**:\n• Élaboration d'un plan de carrière personnalisé (6-12 mois)\n• Stratégie de recherche d'emploi ciblée et efficace\n• Mise à jour du CV avec les nouvelles compétences et réalisations\n• Négociation possible d'un CDI/CDD avec l'entreprise d'accueil\n\n✅ **Mentorat post-immersion (3 mois)**:\n• Un mentor professionnel attitré pour vous accompagner\n• Sessions de coaching bimensuelles (30 min)\n• Aide à la recherche d'emploi et aux candidatures\n• Accès au réseau alumni Estuaire Emploi\n• Invitations aux événements de networking exclusifs\n\n📊 **Livrables finaux**:\n• Rapport d'immersion complet et validé\n• Attestation de stage + certificat Estuaire Emploi\n• Plan de carrière formalisé sur 12 mois\n• Réseau professionnel de 50+ contacts qualifiés",
                'order' => 8,
                'estimated_duration_days' => 7,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Modèle de rapport d\'immersion professionnelle', 'url' => 'https://docs.google.com/rapport-immersion-template', 'type' => 'document'],
                    ['title' => 'Grille d\'auto-évaluation des compétences', 'url' => 'https://airtable.com/evaluation-competences', 'type' => 'document'],
                    ['title' => 'Guide: Valoriser son expérience en entretien', 'url' => 'https://blog.estuaire-emplois.com/valoriser-experience', 'type' => 'article'],
                    ['title' => 'Template de plan de carrière personnalisé', 'url' => 'https://docs.google.com/plan-carriere-template', 'type' => 'document'],
                ],
            ],
        ];

        foreach ($steps as $stepData) {
            ProgramStep::create(array_merge($stepData, ['program_id' => $program->id]));
        }

        $this->command->info('Programme Immersion Professionnelle créé (8 étapes)');
    }

    // ==========================================
    // 3. PROGRAMME ENTREPRENEURIAT
    //    Pack DIAMANT (C3) - 10 étapes
    // ==========================================
    private function createEntrepreneuriatProgram(): void
    {
        $program = Program::create([
            'title' => 'Programme Complet de Formation à l\'Entrepreneuriat',
            'slug' => 'formation-entrepreneuriat-complet',
            'type' => 'entreprenariat',
            'description' => 'Programme intensif de 20 semaines pour accompagner les candidats dans la création, le lancement et le développement de leur entreprise au Cameroun. De l\'idée au premier chiffre d\'affaires, avec un accompagnement par des entrepreneurs et experts reconnus.',
            'objectives' => "Développer un mindset entrepreneurial solide et résilient\nValider son idée de business sur le marché camerounais\nCréer un business plan viable, financé et bancable\nComprendre les aspects juridiques, fiscaux et réglementaires au Cameroun\nMaîtriser la gestion financière et comptable (SYSCOHADA)\nDévelopper des stratégies marketing digital et vente efficaces\nAccéder à des financements (banques, investisseurs, subventions)\nRecruter et manager sa première équipe\nDigitaliser son activité avec les bons outils\nAtteindre ses premiers revenus et fidéliser ses clients",
            'icon' => '💼',
            'duration_weeks' => 20,
            'order' => 3,
            'is_active' => true,
            'required_packs' => ['C3'],
        ]);

        $steps = [
            // ---- ÉTAPE 1 ----
            [
                'title' => 'Mindset Entrepreneurial et Leadership',
                'description' => 'Développer l\'état d\'esprit, la discipline et les habitudes qui font la différence entre un entrepreneur qui réussit et un qui abandonne',
                'content' => "Avant de créer une entreprise, il faut se construire soi-même.\n\n✅ **Le mindset qui fait la différence**:\n• La différence entre employé et entrepreneur : changer de paradigme\n• Growth mindset vs fixed mindset : l'état d'esprit de croissance\n• La gestion de l'échec : comment transformer les obstacles en opportunités\n• La discipline quotidienne : routines des entrepreneurs à succès\n• La solitude de l'entrepreneur : comment la gérer et la transformer\n• L'art de la prise de décision rapide et éclairée\n\n✅ **Leadership et vision**:\n• Définir votre vision entrepreneuriale à 5 et 10 ans\n• Développer votre charisme et votre capacité d'influence\n• L'art de convaincre : famille, associés, investisseurs, clients\n• Gérer le stress, l'incertitude et la pression financière\n• Équilibre vie pro/vie perso quand on est entrepreneur\n\n✅ **Témoignages et inspiration**:\n• Masterclass avec 3 entrepreneurs camerounais qui ont réussi\n• Études de cas : succès et échecs d'entrepreneurs africains\n• Panel de discussion : \"Les erreurs que j'aurais aimé éviter\"\n\n📊 **Livrables**:\n• Vision board entrepreneuriale personnalisée\n• Plan de développement personnel de l'entrepreneur\n• Journal de bord entrepreneurial (à maintenir tout au long du programme)",
                'order' => 1,
                'estimated_duration_days' => 5,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Livre: \"L\'entrepreneur africain\" (résumé)', 'url' => 'https://docs.google.com/entrepreneur-africain', 'type' => 'document'],
                    ['title' => 'Vidéo: Les habitudes des entrepreneurs à succès', 'url' => 'https://www.youtube.com/watch?v=habitudes-entrepreneurs', 'type' => 'video'],
                    ['title' => 'Podcast: Témoignages d\'entrepreneurs camerounais', 'url' => 'https://open.spotify.com/show/entrepreneurs-cameroun', 'type' => 'link'],
                    ['title' => 'Template: Vision Board entrepreneuriale', 'url' => 'https://canva.com/template/vision-board', 'type' => 'document'],
                ],
            ],

            // ---- ÉTAPE 2 ----
            [
                'title' => 'Idéation et Validation du Concept d\'Entreprise',
                'description' => 'Générer, tester et valider votre idée d\'entreprise auprès du marché camerounais réel',
                'content' => "Transformez votre idée en concept entrepreneurial viable et testé.\n\n✅ **Trouver la bonne idée**:\n• Atelier de brainstorming créatif : 15 techniques pour générer des idées\n• Les 7 sources d'opportunités entrepreneuriales au Cameroun\n• Identifier les problèmes non résolus dans votre environnement\n• Analyser les tendances : qu'est-ce qui marche ailleurs et qui manque ici ?\n• Les secteurs porteurs au Cameroun : tech, agro, énergie, BTP, services\n\n✅ **Valider sur le terrain**:\n• Analyse approfondie du marché camerounais et de la concurrence locale\n• Études de faisabilité technique et commerciale\n• Validation du concept auprès de 30+ clients potentiels (interviews terrain)\n• Technique du MVP (Minimum Viable Product) : tester avant d'investir\n• Définition de la proposition de valeur unique (UVP)\n• Élaboration du Business Model Canvas complet\n• Analyse SWOT et PESTEL adaptées au contexte camerounais\n\n📊 **Livrables**:\n• Business Model Canvas validé sur le terrain\n• Rapport d'étude de marché (20+ pages avec données réelles)\n• Pitch deck initial (10 slides)\n• Résultats de validation terrain (30+ interviews documentées)",
                'order' => 2,
                'estimated_duration_days' => 10,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Template Business Model Canvas (français)', 'url' => 'https://miro.com/templates/business-model-canvas', 'type' => 'document'],
                    ['title' => 'Vidéo: Comment valider son idée de business en Afrique', 'url' => 'https://www.youtube.com/watch?v=validation-idee-afrique', 'type' => 'video'],
                    ['title' => 'Guide complet d\'étude de marché au Cameroun', 'url' => 'https://docs.google.com/etude-marche-cameroun', 'type' => 'document'],
                    ['title' => 'Questionnaire de validation client', 'url' => 'https://typeform.com/questionnaire-validation', 'type' => 'link'],
                ],
            ],

            // ---- ÉTAPE 3 ----
            [
                'title' => 'Élaboration du Business Plan Professionnel',
                'description' => 'Créer un business plan complet, professionnel et bancable qui convainc les investisseurs et les banques',
                'content' => "Construisez le plan stratégique détaillé de votre entreprise.\n\n✅ **Structure du business plan**:\n• Résumé exécutif percutant (1-2 pages) - la partie la plus importante\n• Présentation du projet, de la vision et de l'équipe fondatrice\n• Analyse de marché approfondie (taille, tendances, segments, parts de marché)\n• Étude de la concurrence : cartographie et positionnement\n• Stratégie marketing et commerciale détaillée (4P, 7P)\n• Plan opérationnel : production, logistique, fournisseurs\n• Structure organisationnelle et plan de recrutement\n• Prévisions financières réalistes sur 3-5 ans\n• Analyse des risques avec plan de mitigation\n• Besoins de financement et utilisation détaillée des fonds\n\n✅ **Prévisions financières**:\n• Compte de résultat prévisionnel (3-5 ans)\n• Plan de trésorerie mensuel (12-24 mois)\n• Bilan prévisionnel\n• Calcul du point mort et du seuil de rentabilité\n• Plan de financement initial et à 3 ans\n• TRI (Taux de Rendement Interne) et VAN\n\n📊 **Livrables**:\n• Business plan complet de 30-50 pages\n• Fichier Excel de prévisions financières détaillées\n• Pitch deck investisseur professionnel (15 slides)\n• Résumé exécutif d'une page (one-pager)",
                'order' => 3,
                'estimated_duration_days' => 12,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Template Business Plan Complet 2026', 'url' => 'https://docs.google.com/business-plan-template', 'type' => 'document'],
                    ['title' => 'Calculateur de prévisions financières', 'url' => 'https://sheets.google.com/calculateur-previsions', 'type' => 'link'],
                    ['title' => 'Formation: Créer un business plan gagnant', 'url' => 'https://www.udemy.com/business-plan-gagnant', 'type' => 'video'],
                    ['title' => 'Exemples de business plans financés au Cameroun', 'url' => 'https://drive.google.com/exemples-bp-finances', 'type' => 'document'],
                ],
            ],

            // ---- ÉTAPE 4 ----
            [
                'title' => 'Cadre Juridique, Fiscal et Administratif au Cameroun',
                'description' => 'Maîtriser toutes les démarches légales et créer officiellement votre entreprise au Cameroun',
                'content' => "Maîtrisez le cadre juridique et fiscal camerounais pour entrepreneurs.\n\n✅ **Choisir le bon statut juridique**:\n• Entreprise Individuelle : avantages, inconvénients, quand choisir\n• SARLU (Société à Responsabilité Limitée Unipersonnelle) : le plus populaire\n• SARL : pour les associés, capital minimum, fonctionnement\n• SA (Société Anonyme) : pour les projets d'envergure\n• GIE (Groupement d'Intérêt Économique) : pour les activités collectives\n• Comparatif détaillé : fiscalité, responsabilité, coûts, formalités\n\n✅ **Créer son entreprise pas à pas**:\n• Procédures d'immatriculation au CFCE (Centre de Formalités)\n• Rédaction et validation des statuts juridiques\n• Ouverture du compte bancaire professionnel\n• Inscription au registre du commerce (RCCM)\n• Obtention du NIF (Numéro d'Identification Fiscale)\n• Affiliation à la CNSS (Caisse Nationale de Sécurité Sociale)\n• Licences et autorisations sectorielles spécifiques\n\n✅ **Fiscalité et obligations**:\n• Régime fiscal simplifié vs régime réel\n• TVA : seuils, déclarations, remboursements\n• Impôt sur les sociétés (IS) et patente\n• Obligations sociales employeur (CNSS, AMO)\n• Calendrier fiscal annuel de l'entrepreneur\n\n💡 **Accompagnement pratique**:\n• Session avec un avocat d'affaires (1h de consultation offerte)\n• Accompagnement physique au CFCE\n• Modèles de statuts personnalisés offerts\n• Coût total estimé de création : 150,000 - 500,000 FCFA selon le statut",
                'order' => 4,
                'estimated_duration_days' => 10,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Guide complet des statuts juridiques au Cameroun', 'url' => 'https://docs.google.com/statuts-juridiques-cameroun', 'type' => 'document'],
                    ['title' => 'Checklist administrative création entreprise', 'url' => 'https://notion.so/checklist-creation-entreprise', 'type' => 'document'],
                    ['title' => 'Modèles de statuts SARL/SARLU personnalisables', 'url' => 'https://drive.google.com/modeles-statuts', 'type' => 'document'],
                    ['title' => 'Calendrier fiscal de l\'entrepreneur camerounais', 'url' => 'https://docs.google.com/calendrier-fiscal-cameroun', 'type' => 'document'],
                ],
            ],

            // ---- ÉTAPE 5 ----
            [
                'title' => 'Gestion Financière, Comptabilité et Trésorerie',
                'description' => 'Maîtriser la gestion financière quotidienne de votre entreprise pour assurer sa survie et sa croissance',
                'content' => "La trésorerie est le nerf de la guerre : 80% des entreprises qui ferment ont un problème de cash.\n\n✅ **Comptabilité de base (SYSCOHADA)**:\n• Comprendre le plan comptable SYSCOHADA révisé\n• Tenir un journal de caisse et un journal de banque\n• Enregistrer les factures d'achat et de vente\n• Établir un bilan et un compte de résultat simplifiés\n• Les obligations comptables selon votre régime fiscal\n\n✅ **Gestion de trésorerie**:\n• Le plan de trésorerie mensuel : votre outil de survie\n• Prévoir les entrées et sorties d'argent avec précision\n• Gérer les délais de paiement clients et fournisseurs\n• Techniques pour accélérer les encaissements\n• Constituer une réserve de sécurité (3 mois de charges fixes)\n• Quand et comment utiliser le découvert bancaire\n\n✅ **Pilotage financier**:\n• Les 10 indicateurs financiers que tout entrepreneur doit suivre\n• Calculer et suivre sa marge brute, marge nette, et EBITDA\n• Le seuil de rentabilité : combien vendre pour ne pas perdre d'argent ?\n• Tableau de bord financier mensuel\n• Logiciels de comptabilité adaptés aux PME camerounaises (Sage, Zoho, Wave)\n\n💡 **Outils offerts**:\n• Tableur Excel de gestion financière complet et automatisé\n• 3 mois d'abonnement gratuit à un logiciel de comptabilité\n• Templates de factures et devis professionnels",
                'order' => 5,
                'estimated_duration_days' => 10,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Formation: Comptabilité SYSCOHADA pour entrepreneurs', 'url' => 'https://www.youtube.com/playlist?list=compta-syscohada', 'type' => 'video'],
                    ['title' => 'Tableur de gestion financière automatisé', 'url' => 'https://sheets.google.com/tableur-gestion-finance', 'type' => 'document'],
                    ['title' => 'Guide: Survivre les 12 premiers mois financièrement', 'url' => 'https://docs.google.com/survivre-12-mois', 'type' => 'document'],
                    ['title' => 'Templates de factures et devis', 'url' => 'https://canva.com/templates/factures-devis', 'type' => 'document'],
                ],
            ],

            // ---- ÉTAPE 6 ----
            [
                'title' => 'Marketing Digital et Stratégie de Vente',
                'description' => 'Développer une présence en ligne impactante et des stratégies de vente efficaces pour conquérir vos premiers clients',
                'content' => "Pas de clients = pas d'entreprise. Apprenez à vendre efficacement.\n\n✅ **Stratégie marketing complète**:\n• Définir votre client idéal (persona) avec précision\n• Positionner votre marque sur le marché camerounais\n• Construire une identité de marque mémorable (nom, logo, couleurs, ton)\n• Stratégie de prix : comment fixer vos tarifs au Cameroun\n• Les canaux de distribution adaptés à votre secteur\n\n✅ **Marketing digital**:\n• Créer et gérer une page Facebook Business professionnelle\n• Instagram pour les entreprises : stratégie de contenu visuel\n• WhatsApp Business : l'outil n°1 de vente au Cameroun\n• TikTok pour les entreprises : toucher la jeune génération\n• Publicité Facebook/Instagram Ads avec petit budget (5,000-50,000 FCFA/jour)\n• Google My Business : être visible localement\n• Créer un site web simple et efficace (WordPress, Wix)\n• Email marketing et constitution de votre base clients\n\n✅ **Techniques de vente**:\n• La vente consultative : comprendre avant de proposer\n• L'art du closing : transformer les prospects en clients payants\n• Négociation commerciale gagnant-gagnant\n• Gérer les objections courantes avec assurance\n• Service client d'excellence et fidélisation\n• Le bouche-à-oreille : votre meilleur commercial au Cameroun\n\n✅ **Mesurer et optimiser**:\n• KPIs essentiels : CAC, LTV, taux de conversion, panier moyen\n• Google Analytics : comprendre d'où viennent vos clients\n• Facebook Pixel : suivre vos publicités\n• A/B testing : tester et améliorer en continu\n\n📊 **Livrables**:\n• Plan marketing complet sur 6 mois\n• Calendrier éditorial de contenu (3 mois)\n• Page Facebook Business configurée et optimisée\n• Première campagne publicitaire lancée",
                'order' => 6,
                'estimated_duration_days' => 12,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Guide: Marketing digital pour PME africaines', 'url' => 'https://blog.estuaire-emplois.com/marketing-digital-pme', 'type' => 'article'],
                    ['title' => 'Formation: Facebook Ads de A à Z', 'url' => 'https://www.udemy.com/facebook-ads-masterclass', 'type' => 'video'],
                    ['title' => 'Guide: WhatsApp Business pour entrepreneurs', 'url' => 'https://docs.google.com/whatsapp-business-guide', 'type' => 'document'],
                    ['title' => 'Templates de posts réseaux sociaux', 'url' => 'https://canva.com/templates/social-media-posts', 'type' => 'link'],
                ],
            ],

            // ---- ÉTAPE 7 ----
            [
                'title' => 'Pitch, Levée de Fonds et Financement',
                'description' => 'Préparer un pitch convaincant et mobiliser les financements nécessaires au lancement et à la croissance',
                'content' => "Apprenez à convaincre et à lever les fonds nécessaires.\n\n✅ **L'art du pitch**:\n• Élaboration d'un pitch deck professionnel (10-15 slides)\n• Storytelling entrepreneurial : raconter votre histoire avec impact\n• Structurer un pitch de 30 sec (elevator pitch), 3 min et 10 min\n• Maîtriser le langage corporel et la présence sur scène\n• Répondre aux questions difficiles des investisseurs avec aisance\n• Simulation de pitch devant un jury d'experts et investisseurs réels\n\n✅ **Sources de financement au Cameroun**:\n• Autofinancement et love money (famille, amis)\n• Microfinance : MUCODEC, CAPPED, COFINA\n• Banques commerciales : BGFI, LCB, UBA, Ecobank (conditions et dossiers)\n• Fonds d'investissement : ProParco, BIO, FMO, Catalyst Fund\n• Programmes de subventions : BAD, Banque Mondiale, UE, PNUD\n• Concours et prix entrepreneuriaux en Afrique\n• Crowdfunding : plateformes adaptées au marché africain\n• Business angels et investisseurs providentiels locaux\n\n✅ **Préparer son dossier de financement**:\n• Les documents indispensables pour chaque type de financeur\n• Monter un dossier bancaire solide et complet\n• Techniques de négociation avec les banquiers\n• Due diligence : ce que les investisseurs vérifient\n• Term sheet : comprendre et négocier les conditions\n\n💰 **Réseau de financement**:\n• Accès au réseau de 20+ investisseurs et fonds partenaires\n• Partenariats bancaires avec conditions préférentielles\n• Accompagnement personnalisé dans les dossiers de subvention",
                'order' => 7,
                'estimated_duration_days' => 8,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Template Pitch Deck Investisseur', 'url' => 'https://slides.google.com/pitch-deck-template', 'type' => 'document'],
                    ['title' => 'Liste complète des financeurs au Cameroun 2026', 'url' => 'https://airtable.com/financeurs-cameroun-2026', 'type' => 'document'],
                    ['title' => 'Vidéo: L\'art du pitch qui convainc', 'url' => 'https://www.youtube.com/watch?v=art-pitch-convaincant', 'type' => 'video'],
                    ['title' => 'Guide: Monter un dossier bancaire au Cameroun', 'url' => 'https://docs.google.com/dossier-bancaire-cameroun', 'type' => 'document'],
                ],
            ],

            // ---- ÉTAPE 8 ----
            [
                'title' => 'Recrutement, Management d\'Équipe et Leadership',
                'description' => 'Recruter vos premiers collaborateurs, construire une culture d\'entreprise forte et manager avec efficacité',
                'content' => "Votre équipe est votre plus grand atout. Apprenez à la construire et à la mener.\n\n✅ **Recruter les bons profils**:\n• Définir précisément vos besoins en recrutement\n• Rédiger des offres d'emploi attractives\n• Les canaux de recrutement au Cameroun : Estuaire Emploi, LinkedIn, bouche-à-oreille\n• Techniques d'entretien pour évaluer les compétences et la motivation\n• Les erreurs fatales en recrutement et comment les éviter\n• Contrats de travail : CDD, CDI, stage, freelance (droit du travail camerounais)\n\n✅ **Manager son équipe**:\n• Les styles de management : trouver le vôtre\n• Fixer des objectifs clairs et mesurables (OKR, SMART)\n• Communiquer avec son équipe : réunions efficaces, feedback, one-on-one\n• Motiver sans (forcément) augmenter les salaires\n• Déléguer avec confiance : la clé de la croissance\n• Gérer les conflits internes avec diplomatie\n• Les obligations sociales de l'employeur au Cameroun (CNSS, AMO)\n\n✅ **Culture d'entreprise**:\n• Définir les valeurs de votre entreprise\n• Créer un environnement de travail motivant\n• La formation continue de vos collaborateurs\n• Fidéliser vos meilleurs talents\n\n📊 **Livrables**:\n• Organigramme cible de votre entreprise\n• 3 fiches de poste rédigées\n• Charte de management de votre entreprise\n• Grille salariale adaptée au marché camerounais",
                'order' => 8,
                'estimated_duration_days' => 7,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Guide: Recruter au Cameroun - droit du travail', 'url' => 'https://docs.google.com/recruter-cameroun-droit-travail', 'type' => 'document'],
                    ['title' => 'Vidéo: Manager une équipe quand on est jeune entrepreneur', 'url' => 'https://www.youtube.com/watch?v=manager-jeune-entrepreneur', 'type' => 'video'],
                    ['title' => 'Templates de fiches de poste', 'url' => 'https://docs.google.com/templates-fiches-poste', 'type' => 'document'],
                    ['title' => 'Grille salariale du marché camerounais 2026', 'url' => 'https://airtable.com/grille-salariale-cameroun', 'type' => 'document'],
                ],
            ],

            // ---- ÉTAPE 9 ----
            [
                'title' => 'Digitalisation et Outils Tech pour Entrepreneurs',
                'description' => 'Digitaliser votre activité avec les bons outils pour gagner en productivité, réduire les coûts et scaler votre business',
                'content' => "La technologie est votre alliée pour faire plus avec moins.\n\n✅ **Outils essentiels par fonction**:\n\n🏢 **Gestion d'entreprise**:\n• Google Workspace / Microsoft 365 : email pro, documents, stockage cloud\n• Notion / Trello : gestion de projets et organisation\n• Slack / WhatsApp Business : communication d'équipe et clients\n\n💰 **Finance et comptabilité**:\n• Wave / Zoho Books : comptabilité gratuite pour PME\n• Sage ou QuickBooks : pour les entreprises en croissance\n• Mobile Money : MTN MoMo, Airtel Money pour les paiements\n• Stripe / PayDunya : paiements en ligne\n\n📱 **Marketing et vente**:\n• Canva Pro : créer des visuels professionnels sans designer\n• Buffer / Hootsuite : planifier vos posts réseaux sociaux\n• Mailchimp : email marketing et newsletters\n• HubSpot CRM (gratuit) : gérer vos contacts et prospects\n• WordPress / Wix : créer votre site web\n\n📊 **Analyse et suivi**:\n• Google Analytics : comprendre le trafic de votre site\n• Google Data Studio : dashboards automatisés\n• Excel / Google Sheets : tableaux de bord personnalisés\n\n✅ **Automatisation**:\n• Zapier : connecter vos outils et automatiser les tâches répétitives\n• ChatGPT / Claude : rédiger du contenu, analyser des données, brainstormer\n• Calendly : automatiser la prise de rendez-vous\n\n✅ **E-commerce et vente en ligne**:\n• Créer une boutique en ligne simple (Shopify, WooCommerce)\n• Vendre via WhatsApp Business avec catalogue\n• Solutions de livraison au Cameroun\n\n📊 **Livrables**:\n• Écosystème digital de votre entreprise configuré\n• Site web ou page de vente opérationnelle\n• CRM configuré avec vos premiers contacts",
                'order' => 9,
                'estimated_duration_days' => 7,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Guide: Les 20 outils gratuits pour entrepreneurs', 'url' => 'https://docs.google.com/20-outils-gratuits-entrepreneurs', 'type' => 'document'],
                    ['title' => 'Vidéo: Digitaliser son business au Cameroun', 'url' => 'https://www.youtube.com/watch?v=digitaliser-business-cameroun', 'type' => 'video'],
                    ['title' => 'Formation: Créer son site web en 1 journée', 'url' => 'https://www.youtube.com/watch?v=site-web-1-jour', 'type' => 'video'],
                    ['title' => 'Guide: Mobile Money pour les entreprises', 'url' => 'https://docs.google.com/mobile-money-entreprises', 'type' => 'document'],
                ],
            ],

            // ---- ÉTAPE 10 ----
            [
                'title' => 'Lancement, Premiers Clients et Stratégie de Croissance',
                'description' => 'Lancer officiellement votre entreprise, conquérir vos premiers clients payants et poser les bases d\'une croissance durable',
                'content' => "Le moment de vérité : passez de la théorie à l'action.\n\n✅ **Préparation au lancement**:\n• Checklist complète de lancement (50+ items vérifiés)\n• Organisation d'un événement de lancement (physique ou digital)\n• Stratégie de communication de lancement (avant, pendant, après)\n• Offre de lancement irrésistible pour attirer les premiers clients\n• Partenariats stratégiques pour amplifier votre visibilité\n\n✅ **Conquérir vos premiers clients**:\n• Les 10 techniques pour trouver vos 10 premiers clients\n• Le pouvoir du bouche-à-oreille au Cameroun : comment l'activer\n• Transformer vos proches en ambassadeurs (sans les harceler)\n• Prospection terrain efficace : marchés, quartiers, événements\n• Prospection digitale : Facebook Groups, WhatsApp, LinkedIn\n• Les partenariats gagnant-gagnant : mutualiser les audiences\n\n✅ **Les 100 premiers jours**:\n• Suivi hebdomadaire de vos KPIs (CA, marge, clients, trésorerie)\n• Ajuster votre offre selon les retours clients réels\n• Quand et comment pivoter si ça ne marche pas\n• Gérer les premiers défis opérationnels avec calme\n• Fidéliser : un client satisfait en amène 3 nouveaux\n\n✅ **Stratégie de croissance**:\n• Les 5 leviers de croissance pour PME camerounaises\n• Diversifier ses sources de revenus\n• Quand et comment embaucher votre 2ème, 5ème, 10ème employé\n• Ouvrir un 2ème point de vente ou élargir sa zone de chalandise\n• Préparer un 2ème tour de financement si nécessaire\n\n✅ **Mentorat post-programme (6 mois)**:\n• Un mentor entrepreneur attitré\n• Sessions de coaching mensuelles (1h)\n• Accès au réseau d'entrepreneurs alumni Estuaire Emploi\n• Invitations aux événements de networking exclusifs\n• Support continu par WhatsApp Group\n\n📊 **Objectifs concrets**:\n• Réaliser votre premier chiffre d'affaires dans les 30 jours\n• Fidéliser 10+ clients payants dans les 60 jours\n• Atteindre le point d'équilibre dans les 6-12 mois\n• Constituer une base de 100+ prospects qualifiés",
                'order' => 10,
                'estimated_duration_days' => 21,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Checklist complète de lancement d\'entreprise', 'url' => 'https://notion.so/checklist-lancement-startup', 'type' => 'document'],
                    ['title' => 'Tableau de bord de suivi d\'activité (KPIs)', 'url' => 'https://airtable.com/tableau-bord-kpis', 'type' => 'link'],
                    ['title' => 'Guide: Les 100 premiers jours de votre entreprise', 'url' => 'https://docs.google.com/100-premiers-jours', 'type' => 'document'],
                    ['title' => 'Communauté WhatsApp des entrepreneurs du programme', 'url' => 'https://chat.whatsapp.com/estuaire-entrepreneurs', 'type' => 'link'],
                ],
            ],
        ];

        foreach ($steps as $stepData) {
            ProgramStep::create(array_merge($stepData, ['program_id' => $program->id]));
        }

        $this->command->info('Programme Entrepreneuriat créé (10 étapes)');
    }
}
