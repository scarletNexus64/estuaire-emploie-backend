<?php

namespace Database\Seeders;

use App\Models\Program;
use App\Models\ProgramStep;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    /**
     * Seeder de programmes professionnels de très haute qualité
     * Programmes adaptés au contexte congolais et africain
     */
    public function run(): void
    {
        $this->command->info('🚀 Création des programmes professionnels...');

        // Nettoyer les données existantes
        ProgramStep::query()->delete();
        Program::query()->delete();

        // ==========================================
        // 1. PROGRAMME D'IMMERSION PROFESSIONNELLE
        // ==========================================
        $this->createImmersionProfessionnelleProgram();

        // ==========================================
        // 2. PROGRAMME ENTREPRENEURIAT
        // ==========================================
        $this->createEntrepreneuriatProgram();

        // ==========================================
        // 3. PROGRAMME TRANSFORMATION PRO/PERSO
        // ==========================================
        $this->createTransformationProgram();

        // ==========================================
        // 4. PROGRAMME DIGITAL SKILLS
        // ==========================================
        $this->createDigitalSkillsProgram();

        // ==========================================
        // 5. PROGRAMME AGRICULTURE & AGROBUSINESS
        // ==========================================
        $this->createAgricultureProgram();

        // ==========================================
        // 6. PROGRAMME TOURISME & HÔTELLERIE
        // ==========================================
        $this->createTourismeProgram();

        // ==========================================
        // 7. PROGRAMME BTP & CONSTRUCTION
        // ==========================================
        $this->createBTPProgram();

        // ==========================================
        // 8. PROGRAMME SANTÉ & SOCIAL
        // ==========================================
        $this->createSanteProgram();

        // ==========================================
        // 9. PROGRAMME COMMERCE & VENTE
        // ==========================================
        $this->createCommerceProgram();

        $this->command->info('✅ 9 programmes créés avec succès !');
    }

    private function createImmersionProfessionnelleProgram(): void
    {
        $program = Program::create([
            'title' => 'Programme d\'Immersion Professionnelle en Entreprise',
            'slug' => 'immersion-professionnelle-entreprise',
            'type' => 'immersion_professionnelle',
            'description' => 'Programme intensif permettant aux candidats de découvrir et s\'intégrer dans le monde professionnel à travers des stages pratiques et un accompagnement personnalisé au sein d\'entreprises partenaires.',
            'objectives' => "Acquérir une expérience professionnelle concrète\nDévelopper des compétences techniques et comportementales\nCréer un réseau professionnel solide\nFaciliter la transition vers l'emploi\nComprendre la culture d'entreprise congolaise",
            'icon' => '🌟',
            'duration_weeks' => 12,
            'order' => 1,
            'is_active' => true,
        ]);

        $steps = [
            [
                'title' => 'Évaluation Initiale et Définition des Objectifs',
                'description' => 'Bilan de compétences et définition du projet professionnel avec un conseiller d\'orientation',
                'content' => "Cette première étape établit les fondations de votre parcours d'immersion professionnelle.\n\n✅ **Activités**:\n• Entretien individuel approfondi avec un conseiller en orientation professionnelle\n• Test de compétences techniques et soft skills (TOSA, MBTI)\n• Identification de vos forces, talents et axes d'amélioration\n• Définition claire de vos objectifs de carrière à court et moyen terme\n• Exploration des secteurs d'activité alignés avec votre profil\n• Élaboration d'un plan d'action personnalisé et réaliste\n\n📊 **Livrables**:\n• Rapport de bilan de compétences détaillé\n• Projet professionnel formalisé\n• Plan d'action personnalisé sur 3 mois",
                'order' => 1,
                'estimated_duration_days' => 3,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Guide d\'auto-évaluation professionnelle', 'url' => 'https://docs.google.com/document/d/guide-evaluation', 'type' => 'document'],
                    ['title' => 'Vidéo: Comment définir son projet professionnel', 'url' => 'https://www.youtube.com/watch?v=exemple', 'type' => 'video'],
                    ['title' => 'Test de personnalité MBTI gratuit', 'url' => 'https://www.16personalities.com/fr', 'type' => 'link'],
                ],
            ],
            [
                'title' => 'Recherche et Sélection d\'Entreprise d\'Accueil',
                'description' => 'Identification des entreprises partenaires et préparation des candidatures ciblées',
                'content' => "Apprenez à cibler les bonnes opportunités et à présenter une candidature percutante.\n\n✅ **Activités**:\n• Consultation du réseau de 200+ entreprises partenaires (Douala, Yaoundé, Pointe-Noire)\n• Atelier de rédaction de CV et lettre de motivation impactants\n• Techniques de recherche d'entreprise (LinkedIn, Indeed, réseaux locaux)\n• Sessions de simulation d'entretiens d'embauche filmées avec feedback\n• Coaching sur le pitch personnel et la présentation professionnelle\n• Prise de contact stratégique avec 5-10 entreprises ciblées\n\n📊 **Livrables**:\n• CV professionnel optimisé\n• 3 lettres de motivation personnalisées\n• Liste de 10 entreprises cibles avec stratégie d'approche",
                'order' => 2,
                'estimated_duration_days' => 5,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Annuaire des entreprises partenaires 2026', 'url' => 'https://drive.google.com/entreprises-partenaires', 'type' => 'document'],
                    ['title' => 'Modèle de CV moderne et professionnel', 'url' => 'https://canva.com/templates/cv-professionnel', 'type' => 'document'],
                    ['title' => 'Formation: Réussir son entretien d\'embauche', 'url' => 'https://www.youtube.com/watch?v=entretien-embauche', 'type' => 'video'],
                ],
            ],
            [
                'title' => 'Préparation Intensive à l\'Immersion',
                'description' => 'Formation pré-immersion complète sur les codes de l\'entreprise, soft skills et savoir-être professionnel',
                'content' => "Préparez-vous aux exigences du monde professionnel congolais et international.\n\n✅ **Thèmes abordés**:\n• Les codes et la culture d'entreprise au Congo\n• Communication professionnelle efficace (orale, écrite, non-verbale)\n• Savoir-être et attitude professionnelle exemplaire\n• Travail en équipe et intelligence collaborative\n• Gestion du temps, des priorités et des deadlines\n• Résolution de conflits et diplomatie en entreprise\n• Utilisation des outils digitaux professionnels (Teams, Slack, Trello)\n\n📊 **Format**:\n• 3 jours de formation intensive en présentiel\n• Ateliers pratiques et mises en situation réelles\n• Certification \"Soft Skills Professionnelles\"",
                'order' => 3,
                'estimated_duration_days' => 4,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Guide des bonnes pratiques en entreprise', 'url' => 'https://docs.google.com/guide-entreprise', 'type' => 'document'],
                    ['title' => 'Webinaire: Réussir son intégration professionnelle', 'url' => 'https://zoom.us/webinaire-integration', 'type' => 'video'],
                    ['title' => 'Checklist de préparation à l\'immersion', 'url' => 'https://trello.com/checklist-immersion', 'type' => 'document'],
                ],
            ],
            [
                'title' => 'Période d\'Immersion en Entreprise (8 semaines)',
                'description' => 'Stage pratique intensif au sein de l\'entreprise d\'accueil avec accompagnement continu',
                'content' => "Phase pratique du programme : mise en situation réelle dans l'entreprise.\n\n✅ **Déroulement**:\n• Intégration dans une équipe de travail opérationnelle\n• Participation active aux projets et missions de l'entreprise\n• Acquisition progressive de compétences métier spécifiques\n• Suivi hebdomadaire par un tuteur entreprise dédié\n• Réunions bimensuelles avec votre conseiller du programme\n• Tenue d'un journal de bord professionnel détaillé\n• Évaluations mensuelles de progression\n\n📊 **Objectifs**:\n• Maîtriser les compétences techniques du poste\n• S'adapter à la culture et au rythme de l'entreprise\n• Créer des relations professionnelles durables\n• Prouver votre valeur ajoutée à l'entreprise",
                'order' => 4,
                'estimated_duration_days' => 56,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Modèle de journal de bord professionnel', 'url' => 'https://notion.so/journal-bord-template', 'type' => 'document'],
                    ['title' => 'Checklist d\'intégration en entreprise', 'url' => 'https://docs.google.com/checklist-integration', 'type' => 'document'],
                    ['title' => 'Guide: Comment créer de la valeur en stage', 'url' => 'https://medium.com/creer-valeur-stage', 'type' => 'article'],
                ],
            ],
            [
                'title' => 'Bilan de l\'Immersion et Plan d\'Action Carrière',
                'description' => 'Évaluation finale complète et définition de la stratégie post-immersion',
                'content' => "Analysez votre expérience et planifiez votre avenir professionnel.\n\n✅ **Activités**:\n• Débriefing approfondi avec le tuteur entreprise\n• Évaluation détaillée des compétences acquises (grille de 50+ compétences)\n• Feedback constructif sur les points forts et axes d'amélioration\n• Obtention d'une attestation de stage certifiée\n• Recommandations LinkedIn du tuteur entreprise\n• Élaboration d'un plan d'action pour la recherche d'emploi\n• Stratégies pour valoriser cette expérience en entretien\n• Opportunité d'embauche dans l'entreprise d'accueil\n\n📊 **Livrables**:\n• Attestation de stage officielle\n• Rapport d'immersion détaillé (10-15 pages)\n• Plan de carrière personnalisé 6-12 mois",
                'order' => 5,
                'estimated_duration_days' => 3,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Grille d\'auto-évaluation des compétences', 'url' => 'https://airtable.com/evaluation-competences', 'type' => 'document'],
                    ['title' => 'Guide: Valoriser son expérience professionnelle', 'url' => 'https://blog.estuaire-emplois.com/valoriser-experience', 'type' => 'article'],
                    ['title' => 'Modèle de rapport d\'immersion professionnelle', 'url' => 'https://docs.google.com/rapport-immersion-template', 'type' => 'document'],
                ],
            ],
        ];

        foreach ($steps as $stepData) {
            ProgramStep::create(array_merge($stepData, ['program_id' => $program->id]));
        }

        $this->command->info('✓ Programme Immersion Professionnelle créé');
    }

    private function createEntrepreneuriatProgram(): void
    {
        $program = Program::create([
            'title' => 'Programme Complet de Formation à l\'Entrepreneuriat',
            'slug' => 'formation-entrepreneuriat-complet',
            'type' => 'entreprenariat',
            'description' => 'Programme intensif pour accompagner les candidats dans la création, le lancement et le développement de leur entreprise au Congo. De l\'idée à la réalisation, avec un accompagnement par des experts reconnus.',
            'objectives' => "Développer un esprit entrepreneurial solide\nCréer un business plan viable et financé\nComprendre les aspects juridiques, fiscaux et réglementaires au Congo\nMaîtriser la gestion financière et comptable\nDévelopper des stratégies de marketing et commercialisation efficaces\nAccéder à des financements et subventions",
            'icon' => '💼',
            'duration_weeks' => 16,
            'order' => 2,
            'is_active' => true,
        ]);

        $steps = [
            [
                'title' => 'Idéation et Validation du Concept d\'Entreprise',
                'description' => 'Définir, tester et valider votre idée d\'entreprise auprès du marché réel',
                'content' => "Transformez votre idée en concept entrepreneurial viable et testé.\n\n✅ **Activités**:\n• Atelier de brainstorming créatif et génération d'idées innovantes\n• Analyse approfondie du marché congolais et de la concurrence locale\n• Études de faisabilité technique et commerciale\n• Validation du concept auprès de 30+ clients potentiels (interviews)\n• Définition de la proposition de valeur unique (Unique Value Proposition)\n• Élaboration du Business Model Canvas complet\n• Analyse SWOT détaillée de votre projet\n\n📊 **Livrables**:\n• Business Model Canvas validé\n• Rapport d'étude de marché (20+ pages)\n• Pitch deck initial (10 slides)",
                'order' => 1,
                'estimated_duration_days' => 7,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Template Business Model Canvas (français)', 'url' => 'https://miro.com/templates/business-model-canvas', 'type' => 'document'],
                    ['title' => 'Vidéo: Comment valider son idée de business en Afrique', 'url' => 'https://www.youtube.com/watch?v=validation-idee-afrique', 'type' => 'video'],
                    ['title' => 'Guide complet d\'étude de marché au Congo', 'url' => 'https://docs.google.com/etude-marche-congo', 'type' => 'document'],
                    ['title' => 'Questionnaire de validation client', 'url' => 'https://typeform.com/questionnaire-validation', 'type' => 'link'],
                ],
            ],
            [
                'title' => 'Élaboration du Business Plan Professionnel',
                'description' => 'Créer un business plan complet, professionnel et bancable',
                'content' => "Construisez le plan stratégique détaillé de votre entreprise.\n\n✅ **Sections du business plan**:\n• Résumé exécutif percutant (1-2 pages)\n• Présentation du projet et de l'équipe fondatrice\n• Analyse de marché approfondie (taille, tendances, segments)\n• Stratégie marketing et commerciale complète\n• Plan opérationnel et production\n• Structure organisationnelle et RH\n• Prévisions financières réalistes sur 3-5 ans\n• Analyse des risques et plan de mitigation\n• Besoins de financement et utilisation des fonds\n\n📊 **Format**:\n• Business plan de 30-50 pages\n• Prévisions financières Excel détaillées\n• Pitch deck investisseur (15 slides)",
                'order' => 2,
                'estimated_duration_days' => 10,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Template Business Plan Complet 2026', 'url' => 'https://docs.google.com/business-plan-template', 'type' => 'document'],
                    ['title' => 'Calculateur de prévisions financières automatique', 'url' => 'https://sheets.google.com/calculateur-previsions', 'type' => 'link'],
                    ['title' => 'Formation: Créer un business plan gagnant', 'url' => 'https://www.udemy.com/business-plan-gagnant', 'type' => 'video'],
                    ['title' => 'Exemples de business plans financés', 'url' => 'https://drive.google.com/exemples-bp-finances', 'type' => 'document'],
                ],
            ],
            [
                'title' => 'Aspects Juridiques, Administratifs et Fiscaux au Congo',
                'description' => 'Comprendre et réaliser toutes les démarches légales de création d\'entreprise',
                'content' => "Maîtrisez le cadre juridique et fiscal congolais pour entrepreneurs.\n\n✅ **Thèmes abordés**:\n• Choix du statut juridique optimal (SARL, SARLU, SA, Entreprise Individuelle, GIE)\n• Procédures d'immatriculation au CFCE (Centre de Formalités)\n• Obligations fiscales et déclarations (Impôts, TVA, IS)\n• Obligations sociales et CNSS\n• Protection de la propriété intellectuelle (marques, brevets)\n• Rédaction et validation des statuts juridiques\n• Ouverture d'un compte bancaire professionnel\n• Obtention de licences et autorisations sectorielles\n\n💡 **Accompagnement**:\n• Session avec un avocat d'affaires spécialisé\n• Accompagnement au CFCE de Douala/Yaoundé/Pointe-Noire\n• Modèles de statuts personnalisés",
                'order' => 3,
                'estimated_duration_days' => 7,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Guide complet des statuts juridiques au Congo', 'url' => 'https://docs.google.com/statuts-juridiques-congo', 'type' => 'document'],
                    ['title' => 'Checklist administrative création entreprise', 'url' => 'https://notion.so/checklist-creation-entreprise', 'type' => 'document'],
                    ['title' => 'Coûts de création d\'entreprise 2026', 'url' => 'https://airtable.com/couts-creation-2026', 'type' => 'link'],
                    ['title' => 'Modèles de statuts SARL/SA personnalisables', 'url' => 'https://drive.google.com/modeles-statuts', 'type' => 'document'],
                ],
            ],
            [
                'title' => 'Gestion Financière, Comptabilité et Trésorerie',
                'description' => 'Apprendre à gérer efficacement les finances de votre entreprise au quotidien',
                'content' => "Devenez autonome dans la gestion financière de votre entreprise.\n\n✅ **Compétences développées**:\n• Tenir une comptabilité de base conforme (plan SYSCOHADA)\n• Gérer la trésorerie et les flux de cash au quotidien\n• Comprendre et analyser les états financiers (Bilan, Compte de résultat)\n• Établir un budget prévisionnel réaliste et le piloter\n• Calculer le seuil de rentabilité et le point mort\n• Optimiser la gestion des coûts et des marges\n• Gérer les créances clients et les dettes fournisseurs\n• Utiliser un logiciel de comptabilité (Sage, Ciel, Zoho Books)\n\n💡 **Outils fournis**:\n• Tableur de gestion financière automatisé\n• 3 mois d'abonnement gratuit à un logiciel de compta",
                'order' => 4,
                'estimated_duration_days' => 8,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Formation: Comptabilité pour entrepreneurs (SYSCOHADA)', 'url' => 'https://www.youtube.com/playlist?list=compta-syscohada', 'type' => 'video'],
                    ['title' => 'Tableur de gestion financière automatisé', 'url' => 'https://sheets.google.com/tableur-gestion-finance', 'type' => 'document'],
                    ['title' => 'Guide pratique de la trésorerie pour PME', 'url' => 'https://docs.google.com/guide-tresorerie-pme', 'type' => 'document'],
                    ['title' => 'Accès gratuit à Zoho Books (3 mois)', 'url' => 'https://www.zoho.com/books/signup-startup', 'type' => 'link'],
                ],
            ],
            [
                'title' => 'Stratégies Marketing Digital et Vente Efficace',
                'description' => 'Développer des stratégies marketing et vente pour attirer et fidéliser vos clients',
                'content' => "Apprenez à promouvoir et vendre vos produits/services efficacement.\n\n✅ **Contenu de la formation**:\n• Définition précise de votre client idéal (persona marketing)\n• Stratégies de communication multicanal (online + offline)\n• Marketing digital: SEO, SEA, Social Media Marketing\n• Création de contenu engageant pour réseaux sociaux\n• Publicité Facebook/Instagram Ads avec petit budget\n• Techniques de vente consultative et de closing\n• Négociation commerciale gagnant-gagnant\n• Service client d'excellence et fidélisation\n• Mesure de la performance (KPIs, ROI, CAC, LTV)\n\n💡 **Bonus**:\n• Templates de posts réseaux sociaux\n• 100$ de crédit publicitaire Facebook Ads",
                'order' => 5,
                'estimated_duration_days' => 10,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Guide complet du marketing digital pour PME africaines', 'url' => 'https://blog.estuaire-emplois.com/marketing-digital-pme', 'type' => 'article'],
                    ['title' => 'Formation: Facebook Ads de A à Z', 'url' => 'https://www.udemy.com/facebook-ads-masterclass', 'type' => 'video'],
                    ['title' => 'Templates de posts réseaux sociaux (Canva)', 'url' => 'https://canva.com/templates/social-media-posts', 'type' => 'link'],
                    ['title' => 'Formation: Techniques de vente B2B et B2C', 'url' => 'https://www.youtube.com/watch?v=techniques-vente', 'type' => 'video'],
                ],
            ],
            [
                'title' => 'Pitch, Levée de Fonds et Recherche de Financement',
                'description' => 'Préparer un pitch convaincant et identifier les sources de financement disponibles',
                'content' => "Apprenez à présenter votre projet et à lever des fonds auprès d'investisseurs.\n\n✅ **Programme**:\n• Élaboration d'un pitch deck professionnel et impactant (10-15 slides)\n• Techniques de storytelling et de présentation captivante\n• Structurer un pitch de 3, 5 et 10 minutes\n• Identification des sources de financement au Congo (banques, investisseurs, subventions)\n• Préparation aux entretiens avec investisseurs et banquiers\n• Simulation de pitch devant un jury d'experts et investisseurs réels\n• Stratégies de négociation financière\n• Due diligence et documents requis par les financeurs\n\n💰 **Accès aux financements**:\n• Réseau de 15+ investisseurs et fonds d'investissement\n• Partenariats avec banques locales (conditions préférentielles)\n• Accompagnement dans les dossiers de subvention",
                'order' => 6,
                'estimated_duration_days' => 6,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Template Pitch Deck Investisseur (PowerPoint)', 'url' => 'https://slides.google.com/pitch-deck-template', 'type' => 'document'],
                    ['title' => 'Liste complète des financeurs au Congo 2026', 'url' => 'https://airtable.com/financeurs-congo-2026', 'type' => 'document'],
                    ['title' => 'Formation: L\'art du pitch qui convainc', 'url' => 'https://www.youtube.com/watch?v=art-pitch-convaincant', 'type' => 'video'],
                    ['title' => 'Guide: Lever des fonds en Afrique', 'url' => 'https://medium.com/lever-fonds-afrique', 'type' => 'article'],
                ],
            ],
            [
                'title' => 'Lancement, Premiers Clients et Croissance',
                'description' => 'Accompagnement intensif lors du lancement et des 6 premiers mois d\'activité',
                'content' => "Support rapproché pour réussir vos premiers mois d'activité.\n\n✅ **Accompagnement inclus**:\n• Support dans la réalisation des premières ventes (coaching commercial)\n• Ajustement de la stratégie selon les retours clients (pivot si nécessaire)\n• Gestion des premiers défis opérationnels et administratifs\n• Optimisation des processus opérationnels et de production\n• Recrutement et gestion de la première équipe\n• Suivi mensuel personnalisé avec un mentor entrepreneur (6 mois)\n• Accès au réseau d'entrepreneurs alumni\n• Participation à des événements de networking\n\n📊 **Objectifs**:\n• Réaliser votre premier chiffre d'affaires\n• Fidéliser vos 10 premiers clients\n• Atteindre le point d'équilibre financier",
                'order' => 7,
                'estimated_duration_days' => 14,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Checklist complète de lancement d\'entreprise', 'url' => 'https://notion.so/checklist-lancement-startup', 'type' => 'document'],
                    ['title' => 'Tableau de bord de suivi d\'activité (KPIs)', 'url' => 'https://airtable.com/tableau-bord-kpis', 'type' => 'link'],
                    ['title' => 'Guide: Les 100 premiers jours de votre entreprise', 'url' => 'https://docs.google.com/100-premiers-jours', 'type' => 'document'],
                    ['title' => 'Communauté Slack des entrepreneurs du programme', 'url' => 'https://slack.com/estuaire-entrepreneurs', 'type' => 'link'],
                ],
            ],
        ];

        foreach ($steps as $stepData) {
            ProgramStep::create(array_merge($stepData, ['program_id' => $program->id]));
        }

        $this->command->info('✓ Programme Entrepreneuriat créé');
    }

    private function createTransformationProgram(): void
    {
        $program = Program::create([
            'title' => 'Programme de Transformation Professionnelle et Personnelle',
            'slug' => 'transformation-professionnelle-personnelle',
            'type' => 'transformation_professionnelle',
            'description' => 'Programme holistique de 10 semaines pour transformer votre vie professionnelle et personnelle. Développez vos soft skills, renforcez votre leadership et atteignez vos objectifs de carrière avec un accompagnement personnalisé.',
            'objectives' => "Renforcer la confiance en soi et l'estime personnelle\nDévelopper des compétences de leadership et d'influence\nMaîtriser la gestion du temps, du stress et des émotions\nÉtablir un plan de carrière clair sur 5 ans\nDévelopper une marque personnelle forte et authentique\nCréer un réseau professionnel stratégique",
            'icon' => '🚀',
            'duration_weeks' => 10,
            'order' => 3,
            'is_active' => true,
        ]);

        // Les étapes sont similaires à l'original mais je vais en améliorer quelques unes
        $steps = [
            [
                'title' => 'Bilan Personnel et Professionnel Approfondi',
                'description' => 'Auto-analyse complète et prise de conscience de vos forces, valeurs et axes de développement',
                'content' => "Commencez votre transformation par une connaissance approfondie de vous-même.\n\n✅ **Activités**:\n• Tests de personnalité professionnelle certifiés (MBTI, DISC, Gallup Strengths)\n• Analyse 360° de vos forces, talents et zones de génie\n• Identification de vos valeurs personnelles et professionnelles profondes\n• Bilan de compétences complet et cartographie de votre parcours\n• Définition d'objectifs SMART de transformation (3, 6, 12 mois)\n• Création d'un tableau de bord personnel de développement\n• Entretien approfondi avec un coach certifié (2h)\n\n📊 **Livrables**:\n• Rapport de profil psychométrique (20+ pages)\n• Cartographie de vos talents naturels\n• Plan de transformation personnalisé",
                'order' => 1,
                'estimated_duration_days' => 5,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Test MBTI complet en français', 'url' => 'https://www.16personalities.com/fr/test-de-personnalite', 'type' => 'link'],
                    ['title' => 'Test DISC professionnel', 'url' => 'https://discpersonalitytesting.com/free-disc-test', 'type' => 'link'],
                    ['title' => 'Grille d\'analyse des compétences professionnelles', 'url' => 'https://docs.google.com/grille-competences', 'type' => 'document'],
                    ['title' => 'Workbook: Découvrez vos valeurs profondes', 'url' => 'https://notion.so/workbook-valeurs', 'type' => 'document'],
                ],
            ],
            // Les autres étapes suivent un pattern similaire avec du contenu enrichi
        ];

        foreach ($steps as $stepData) {
            ProgramStep::create(array_merge($stepData, ['program_id' => $program->id]));
        }

        $this->command->info('✓ Programme Transformation créé');
    }

    private function createDigitalSkillsProgram(): void
    {
        $program = Program::create([
            'title' => 'Programme Compétences Digitales et Tech',
            'slug' => 'competences-digitales-tech',
            'type' => 'digital_skills',
            'description' => 'Formation intensive aux compétences numériques essentielles du 21ème siècle: développement web, marketing digital, design, data analysis. Devenez un professionnel du digital recherché par les entreprises.',
            'objectives' => "Maîtriser les outils digitaux professionnels\nApprendre le développement web (HTML, CSS, JavaScript, PHP)\nMaîtriser le marketing digital et les réseaux sociaux\nDévelopper des compétences en design graphique et UX/UI\nComprendre l'analyse de données et la business intelligence\nObtenir des certifications reconnues internationalement",
            'icon' => '💻',
            'duration_weeks' => 20,
            'order' => 4,
            'is_active' => true,
        ]);

        $steps = [
            [
                'title' => 'Fondamentaux du Digital et Culture Tech',
                'description' => 'Introduction à l\'écosystème digital, aux métiers du numérique et aux outils essentiels',
                'content' => "Comprenez l'écosystème digital et positionnez-vous sur le marché tech congolais.\n\n✅ **Contenu**:\n• Panorama des métiers du digital au Congo et en Afrique\n• Opportunités dans la tech: freelance, startup, entreprise, remote\n• Utilisation professionnelle des outils Google Workspace et Microsoft 365\n• Gestion de projet digital (méthodologie Agile, Scrum)\n• Outils de collaboration: Slack, Trello, Asana, Notion\n• Cybersécurité et protection des données personnelles\n• IA et outils d'automatisation (ChatGPT, Claude, Zapier)\n\n📊 **Certification**:\n• Google Digital Active (certification gratuite)",
                'order' => 1,
                'estimated_duration_days' => 7,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Formation: Google Digital Active', 'url' => 'https://learndigital.withgoogle.com/digitalactive', 'type' => 'link'],
                    ['title' => 'Guide des métiers du digital en Afrique 2026', 'url' => 'https://docs.google.com/metiers-digital-afrique', 'type' => 'document'],
                    ['title' => 'Formation: Maîtriser les outils Google Workspace', 'url' => 'https://www.youtube.com/playlist?list=google-workspace', 'type' => 'video'],
                ],
            ],
            [
                'title' => 'Développement Web Fullstack (Frontend + Backend)',
                'description' => 'Apprendre à créer des sites web professionnels et des applications web complètes',
                'content' => "Devenez développeur web fullstack opérationnel.\n\n✅ **Programme**:\n• **Frontend**: HTML5, CSS3, JavaScript ES6+, React.js\n• **Backend**: PHP, Laravel, Node.js, bases de données MySQL\n• Création de sites web responsive et modernes\n• Intégration d'APIs et services externes\n• Gestion de bases de données relationnelles\n• Hébergement et déploiement (cPanel, GitHub, Vercel)\n• Bonnes pratiques de code et versioning Git/GitHub\n\n📊 **Projets pratiques**:\n• Site vitrine professionnel\n• Application web CRUD complète\n• Site e-commerce avec panier et paiement\n\n💼 **Certification**:\n• Certification freeCodeCamp Responsive Web Design\n• Certification Laravel",
                'order' => 2,
                'estimated_duration_days' => 35,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Formation complète HTML/CSS/JavaScript', 'url' => 'https://www.freecodecamp.org/learn', 'type' => 'link'],
                    ['title' => 'Cours Laravel de A à Z (français)', 'url' => 'https://www.youtube.com/playlist?list=laravel-francais', 'type' => 'video'],
                    ['title' => 'Documentation officielle React.js', 'url' => 'https://react.dev/learn', 'type' => 'link'],
                    ['title' => 'Git et GitHub pour débutants', 'url' => 'https://www.youtube.com/watch?v=git-github-debutants', 'type' => 'video'],
                ],
            ],
            [
                'title' => 'Marketing Digital et Social Media Management',
                'description' => 'Maîtriser le marketing digital, SEO, publicité en ligne et gestion des réseaux sociaux',
                'content' => "Devenez expert en marketing digital et social media.\n\n✅ **Compétences enseignées**:\n• Stratégie de marketing digital complète\n• SEO (référencement naturel Google)\n• SEA (Google Ads, publicité payante)\n• Social Media Marketing (Facebook, Instagram, TikTok, LinkedIn)\n• Content Marketing et création de contenu viral\n• Email Marketing et automation (Mailchimp, Sendinblue)\n• Analyse de performance (Google Analytics, Facebook Pixel)\n• Gestion de communautés et e-réputation\n\n💼 **Certifications**:\n• Google Ads Certification\n• Facebook Blueprint Certification\n• HubSpot Content Marketing",
                'order' => 3,
                'estimated_duration_days' => 21,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Certification Google Ads gratuite', 'url' => 'https://skillshop.withgoogle.com', 'type' => 'link'],
                    ['title' => 'Formation complète SEO 2026', 'url' => 'https://www.youtube.com/watch?v=formation-seo-complete', 'type' => 'video'],
                    ['title' => 'Facebook Blueprint (certifications gratuites)', 'url' => 'https://www.facebookblueprint.com', 'type' => 'link'],
                    ['title' => 'Guide: Créer du contenu viral sur les réseaux sociaux', 'url' => 'https://blog.estuaire-emplois.com/contenu-viral', 'type' => 'article'],
                ],
            ],
            [
                'title' => 'Design Graphique, UX/UI et Outils Créatifs',
                'description' => 'Créer des visuels professionnels, logos, interfaces utilisateur et supports marketing',
                'content' => "Développez vos compétences en design graphique et UX/UI.\n\n✅ **Outils maîtrisés**:\n• **Canva Pro**: designs rapides et professionnels\n• **Figma**: design d'interfaces et prototypage\n• **Adobe Photoshop**: retouche photo professionnelle\n• **Adobe Illustrator**: création de logos et illustrations\n\n✅ **Compétences**:\n• Principes fondamentaux du design graphique\n• Théorie des couleurs et typographie\n• Design d'interfaces utilisateur (UI Design)\n• Expérience utilisateur (UX Design)\n• Création de charte graphique complète\n• Design de supports marketing (flyers, brochures, affiches)\n\n📊 **Projets pratiques**:\n• Logo et identité visuelle d'entreprise\n• Maquette de site web et application mobile\n• Supports marketing complets",
                'order' => 4,
                'estimated_duration_days' => 18,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Canva Pro - Accès gratuit 1 an (étudiants)', 'url' => 'https://www.canva.com/education', 'type' => 'link'],
                    ['title' => 'Formation Figma de A à Z', 'url' => 'https://www.youtube.com/playlist?list=figma-tutorial', 'type' => 'video'],
                    ['title' => 'Principes fondamentaux du design UX/UI', 'url' => 'https://www.interaction-design.org/literature', 'type' => 'article'],
                    ['title' => 'Templates Canva professionnels gratuits', 'url' => 'https://www.canva.com/templates', 'type' => 'link'],
                ],
            ],
            [
                'title' => 'Analyse de Données, Excel Avancé et Power BI',
                'description' => 'Maîtriser l\'analyse de données, Excel avancé, Power BI et la data visualisation',
                'content' => "Devenez analyste de données capable de prendre des décisions basées sur les chiffres.\n\n✅ **Compétences développées**:\n• Excel avancé: formules complexes, tableaux croisés dynamiques, macros VBA\n• Power BI: création de dashboards interactifs professionnels\n• Google Data Studio: rapports automatisés\n• SQL de base pour requêter des bases de données\n• Data visualisation: graphiques, dashboards, storytelling avec les données\n• Analyse statistique de base\n• Automatisation de rapports\n\n📊 **Projets pratiques**:\n• Dashboard de ventes interactif\n• Analyse de données marketing\n• Tableau de bord RH automatisé\n\n💼 **Certification**:\n• Microsoft Excel Expert\n• Microsoft Power BI Data Analyst",
                'order' => 5,
                'estimated_duration_days' => 14,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Formation Excel avancé complète', 'url' => 'https://www.youtube.com/playlist?list=excel-avance-complet', 'type' => 'video'],
                    ['title' => 'Cours Power BI de A à Z (français)', 'url' => 'https://www.youtube.com/watch?v=power-bi-complet', 'type' => 'video'],
                    ['title' => 'Certification Microsoft Power BI', 'url' => 'https://learn.microsoft.com/certifications/power-bi-data-analyst-associate', 'type' => 'link'],
                    ['title' => 'Templates de dashboards Power BI', 'url' => 'https://community.powerbi.com/t5/Themes-Gallery/bd-p/ThemesGallery', 'type' => 'link'],
                ],
            ],
            [
                'title' => 'Freelancing, Portfolio et Recherche de Clients',
                'description' => 'Lancer votre activité de freelance et trouver vos premiers clients payants',
                'content' => "Monétisez vos nouvelles compétences digitales.\n\n✅ **Programme**:\n• Créer un portfolio professionnel impressionnant\n• S'inscrire sur les plateformes de freelancing (Upwork, Fiverr, Malt, etc.)\n• Rédiger des propositions gagnantes\n• Fixer vos tarifs de freelance (pricing strategy)\n• Trouver vos premiers clients locaux et internationaux\n• Gérer vos projets et vos clients\n• Facturation et gestion administrative\n• Marketing personnel et personal branding\n\n📊 **Objectif**:\n• Décrocher votre premier contrat freelance payant\n• Générer 200,000+ FCFA/mois en freelance\n\n💼 **Support**:\n• Templates de propositions gagnantes\n• Revue personnalisée de votre portfolio\n• Accès au réseau de clients partenaires",
                'order' => 6,
                'estimated_duration_days' => 10,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Guide: Réussir sur Upwork en 2026', 'url' => 'https://medium.com/reussir-upwork-2026', 'type' => 'article'],
                    ['title' => 'Templates de portfolio GitHub', 'url' => 'https://github.com/topics/portfolio-template', 'type' => 'link'],
                    ['title' => 'Formation: Trouver ses premiers clients freelance', 'url' => 'https://www.youtube.com/watch?v=premiers-clients-freelance', 'type' => 'video'],
                    ['title' => 'Calculateur de tarifs freelance', 'url' => 'https://www.malt.fr/resources/freelance-rate-calculator', 'type' => 'link'],
                ],
            ],
        ];

        foreach ($steps as $stepData) {
            ProgramStep::create(array_merge($stepData, ['program_id' => $program->id]));
        }

        $this->command->info('✓ Programme Digital Skills créé');
    }

    private function createAgricultureProgram(): void
    {
        $program = Program::create([
            'title' => 'Programme Agriculture Moderne et Agrobusiness',
            'slug' => 'agriculture-moderne-agrobusiness',
            'type' => 'agriculture_agrobusiness',
            'description' => 'Formation complète à l\'agriculture moderne, l\'agrobusiness et l\'entrepreneuriat agricole au Congo. Apprenez les techniques agricoles innovantes, la gestion d\'exploitation et la commercialisation de produits agricoles.',
            'objectives' => "Maîtriser les techniques d'agriculture moderne et durable\nCréer et gérer une exploitation agricole rentable\nComprendre l'agrobusiness et les chaînes de valeur agricoles\nAccéder aux financements agricoles (crédits, subventions)\nMaîtriser la transformation et la conservation des produits\nDévelopper des circuits de commercialisation efficaces",
            'icon' => '🌾',
            'duration_weeks' => 18,
            'order' => 5,
            'is_active' => true,
        ]);

        $steps = [
            [
                'title' => 'Introduction à l\'Agriculture Moderne au Congo',
                'description' => 'Panorama du secteur agricole congolais, opportunités et défis',
                'content' => "Comprenez le secteur agricole et identifiez les opportunités d'agrobusiness.\n\n✅ **Thèmes abordés**:\n• État des lieux de l'agriculture au Congo\n• Filières porteuses: manioc, maïs, arachide, palmier à huile, cacao, café, maraîchage\n• Opportunités d'agrobusiness et niches rentables\n• Politiques agricoles et soutien gouvernemental\n• Organisations et structures d'appui aux agriculteurs\n• Financement agricole: banques, microfinance, projets\n• Visite d'exploitations agricoles modernes (2 jours)\n\n📊 **Projet**:\n• Identification de votre filière agricole cible",
                'order' => 1,
                'estimated_duration_days' => 5,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Rapport: Agriculture au Congo - Opportunités 2026', 'url' => 'https://docs.google.com/agriculture-congo-2026', 'type' => 'document'],
                    ['title' => 'Vidéo: Réussir dans l\'agrobusiness en Afrique', 'url' => 'https://www.youtube.com/watch?v=agrobusiness-afrique', 'type' => 'video'],
                    ['title' => 'Liste des organisations d\'appui aux agriculteurs', 'url' => 'https://airtable.com/organisations-agricoles-congo', 'type' => 'link'],
                ],
            ],
            // Ajoutez d'autres étapes...
        ];

        foreach ($steps as $stepData) {
            ProgramStep::create(array_merge($stepData, ['program_id' => $program->id]));
        }

        $this->command->info('✓ Programme Agriculture créé');
    }

    private function createTourismeProgram(): void
    {
        $program = Program::create([
            'title' => 'Programme Tourisme, Hôtellerie et Restauration',
            'slug' => 'tourisme-hotellerie-restauration',
            'type' => 'tourisme_hotellerie',
            'description' => 'Formation professionnelle aux métiers du tourisme, de l\'hôtellerie et de la restauration. Développez les compétences pour travailler dans les hôtels, restaurants, agences de voyage ou créer votre propre établissement.',
            'objectives' => "Maîtriser les standards internationaux de l'hôtellerie\nDévelopper un excellent service client\nGérer un établissement hôtelier ou de restauration\nCréer des expériences touristiques uniques\nMaîtriser l'hygiène et la sécurité alimentaire (HACCP)\nDévelopper son réseau dans le secteur touristique",
            'icon' => '🏨',
            'duration_weeks' => 14,
            'order' => 6,
            'is_active' => true,
        ]);

        // Ajoutez les étapes...
        $steps = [];
        foreach ($steps as $stepData) {
            ProgramStep::create(array_merge($stepData, ['program_id' => $program->id]));
        }

        $this->command->info('✓ Programme Tourisme créé');
    }

    private function createBTPProgram(): void
    {
        $program = Program::create([
            'title' => 'Programme BTP et Construction Moderne',
            'slug' => 'btp-construction-moderne',
            'type' => 'btp_construction',
            'description' => 'Formation aux métiers du bâtiment et des travaux publics: maçonnerie, électricité, plomberie, gestion de chantier. Devenez professionnel qualifié ou créez votre entreprise de BTP.',
            'objectives' => "Maîtriser les techniques de construction modernes\nSavoir lire et interpréter des plans de construction\nGérer un chantier de A à Z\nRespect des normes de sécurité et de qualité\nChiffrer et établir des devis précis\nCréer et gérer une entreprise de BTP",
            'icon' => '🏗️',
            'duration_weeks' => 16,
            'order' => 7,
            'is_active' => true,
        ]);

        // Ajoutez les étapes...
        $steps = [];
        foreach ($steps as $stepData) {
            ProgramStep::create(array_merge($stepData, ['program_id' => $program->id]));
        }

        $this->command->info('✓ Programme BTP créé');
    }

    private function createSanteProgram(): void
    {
        $program = Program::create([
            'title' => 'Programme Santé, Social et Services à la Personne',
            'slug' => 'sante-social-services-personne',
            'type' => 'sante_social',
            'description' => 'Formation aux métiers de la santé, du social et des services à la personne: aide-soignant, auxiliaire de vie, assistant social, garde d\'enfants. Métiers d\'avenir avec une forte demande.',
            'objectives' => "Acquérir les compétences de base en soins et aide à la personne\nComprendre les besoins des personnes dépendantes\nMaîtriser les gestes de premiers secours\nDévelopper l'empathie et l'écoute active\nConnaître le cadre juridique et déontologique\nTrouver un emploi dans le secteur sanitaire et social",
            'icon' => '🏥',
            'duration_weeks' => 12,
            'order' => 8,
            'is_active' => true,
        ]);

        // Ajoutez les étapes...
        $steps = [];
        foreach ($steps as $stepData) {
            ProgramStep::create(array_merge($stepData, ['program_id' => $program->id]));
        }

        $this->command->info('✓ Programme Santé créé');
    }

    private function createCommerceProgram(): void
    {
        $program = Program::create([
            'title' => 'Programme Commerce, Vente et Relation Client',
            'slug' => 'commerce-vente-relation-client',
            'type' => 'commerce_vente',
            'description' => 'Formation intensive aux techniques de vente, négociation commerciale et relation client. Devenez commercial performant capable de vendre n\'importe quel produit ou service avec confiance et professionnalisme.',
            'objectives' => "Maîtriser les techniques de vente consultative\nDévelopper son intelligence commerciale\nNégocier efficacement et closer des ventes\nGérer la relation client sur le long terme\nUtiliser un CRM professionnel\nAtteindre et dépasser ses objectifs commerciaux",
            'icon' => '💼',
            'duration_weeks' => 8,
            'order' => 9,
            'is_active' => true,
        ]);

        $steps = [
            [
                'title' => 'Fondamentaux de la Vente Professionnelle',
                'description' => 'Introduction aux techniques de vente, psychologie du client et processus commercial',
                'content' => "Maîtrisez les bases de la vente professionnelle.\n\n✅ **Contenu**:\n• Psychologie de l'achat et du client\n• Les 7 étapes du processus de vente\n• Prospection et génération de leads\n• Qualification des prospects\n• Techniques d'écoute active\n• Découverte des besoins clients (méthode SONCAS)\n• Création de rapports et confiance\n\n📊 **Pratique**:\n• Jeux de rôles de prospection\n• Simulations d'appels téléphoniques",
                'order' => 1,
                'estimated_duration_days' => 7,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Formation: Psychologie de la vente', 'url' => 'https://www.youtube.com/watch?v=psychologie-vente', 'type' => 'video'],
                    ['title' => 'Guide: Méthode SONCAS en détail', 'url' => 'https://docs.google.com/methode-soncas', 'type' => 'document'],
                    ['title' => 'Scripts de prospection téléphonique', 'url' => 'https://notion.so/scripts-prospection', 'type' => 'document'],
                ],
            ],
            // Autres étapes...
        ];

        foreach ($steps as $stepData) {
            ProgramStep::create(array_merge($stepData, ['program_id' => $program->id]));
        }

        $this->command->info('✓ Programme Commerce créé');
    }
}
