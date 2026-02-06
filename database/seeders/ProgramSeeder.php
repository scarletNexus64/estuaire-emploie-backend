<?php

namespace Database\Seeders;

use App\Models\Program;
use App\Models\ProgramStep;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Programme d'Immersion Professionnelle
        $immersionProgram = Program::create([
            'title' => 'Programme d\'Immersion Professionnelle en Entreprise',
            'slug' => 'immersion-professionnelle-entreprise',
            'type' => 'immersion_professionnelle',
            'description' => 'Programme intensif permettant aux candidats de découvrir et s\'intégrer dans le monde professionnel à travers des stages pratiques et un accompagnement personnalisé.',
            'objectives' => "Acquérir une expérience professionnelle concrète\nDévelopper des compétences techniques et comportementales\nCréer un réseau professionnel\nFaciliter la transition vers l'emploi\nComprendre la culture d'entreprise",
            'icon' => '🌟',
            'duration_weeks' => 12,
            'order' => 1,
            'is_active' => true,
        ]);

        $immersionSteps = [
            [
                'title' => 'Évaluation Initiale et Définition des Objectifs',
                'description' => 'Bilan de compétences et définition du projet professionnel avec un conseiller',
                'content' => "Cette première étape est cruciale pour établir votre parcours d'immersion professionnelle.\n\nActivités:\n- Entretien individuel avec un conseiller en orientation\n- Test de compétences et d'aptitudes professionnelles\n- Définition de vos objectifs de carrière\n- Identification des secteurs d'activité ciblés\n- Élaboration d'un plan d'action personnalisé",
                'order' => 1,
                'estimated_duration_days' => 3,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Guide d\'auto-évaluation professionnelle', 'url' => 'https://example.com/guide-auto-evaluation', 'type' => 'document'],
                    ['title' => 'Vidéo: Comment définir son projet professionnel', 'url' => 'https://youtube.com/watch?v=exemple', 'type' => 'video'],
                ],
            ],
            [
                'title' => 'Recherche et Sélection d\'Entreprise d\'Accueil',
                'description' => 'Identification des entreprises partenaires et préparation des candidatures',
                'content' => "Apprenez à cibler les bonnes opportunités et à présenter votre candidature.\n\nActivités:\n- Consultation du réseau d'entreprises partenaires\n- Recherche d'entreprises correspondant à votre profil\n- Préparation du CV et lettre de motivation\n- Simulation d'entretiens d'embauche\n- Prise de contact avec les entreprises",
                'order' => 2,
                'estimated_duration_days' => 5,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Liste des entreprises partenaires', 'url' => 'https://example.com/entreprises-partenaires', 'type' => 'document'],
                    ['title' => 'Modèle de CV professionnel', 'url' => 'https://example.com/modele-cv', 'type' => 'document'],
                ],
            ],
            [
                'title' => 'Préparation à l\'Immersion',
                'description' => 'Formation pré-immersion sur les codes de l\'entreprise et soft skills',
                'content' => "Préparez-vous aux exigences du monde professionnel.\n\nThèmes abordés:\n- Les codes et la culture d'entreprise\n- Communication professionnelle\n- Travail en équipe\n- Gestion du temps et des priorités\n- Attitude et comportement professionnel",
                'order' => 3,
                'estimated_duration_days' => 4,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Guide des bonnes pratiques en entreprise', 'url' => 'https://example.com/bonnes-pratiques', 'type' => 'document'],
                    ['title' => 'Webinaire: Réussir son intégration en entreprise', 'url' => 'https://example.com/webinaire', 'type' => 'video'],
                ],
            ],
            [
                'title' => 'Période d\'Immersion en Entreprise',
                'description' => 'Stage pratique de 8 semaines au sein de l\'entreprise d\'accueil',
                'content' => "Phase pratique du programme au sein de l'entreprise.\n\nDéroulement:\n- Intégration dans une équipe de travail\n- Participation aux projets de l'entreprise\n- Acquisition de compétences métier\n- Suivi hebdomadaire par un tuteur entreprise\n- Réunions bimensuelles avec le conseiller du programme\n- Tenue d'un journal de bord professionnel",
                'order' => 4,
                'estimated_duration_days' => 56,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Modèle de journal de bord', 'url' => 'https://example.com/journal-bord', 'type' => 'document'],
                    ['title' => 'Checklist d\'intégration professionnelle', 'url' => 'https://example.com/checklist', 'type' => 'document'],
                ],
            ],
            [
                'title' => 'Bilan de l\'Immersion et Plan d\'Action',
                'description' => 'Évaluation finale et définition de la stratégie post-immersion',
                'content' => "Analysez votre expérience et planifiez la suite.\n\nActivités:\n- Débriefing avec le tuteur entreprise\n- Évaluation des compétences acquises\n- Feedback sur les points forts et axes d'amélioration\n- Obtention d'une attestation de stage\n- Élaboration d'un plan d'action pour la recherche d'emploi\n- Conseils pour valoriser cette expérience",
                'order' => 5,
                'estimated_duration_days' => 3,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Grille d\'auto-évaluation des compétences', 'url' => 'https://example.com/auto-evaluation', 'type' => 'document'],
                    ['title' => 'Guide: Valoriser son expérience professionnelle', 'url' => 'https://example.com/valoriser-experience', 'type' => 'article'],
                ],
            ],
        ];

        foreach ($immersionSteps as $stepData) {
            ProgramStep::create(array_merge($stepData, ['program_id' => $immersionProgram->id]));
        }

        // 2. Programme en Entreprenariat
        $entrepreneuriatProgram = Program::create([
            'title' => 'Programme de Formation à l\'Entreprenariat',
            'slug' => 'formation-entreprenariat',
            'type' => 'entreprenariat',
            'description' => 'Programme complet pour accompagner les candidats dans la création et le développement de leur entreprise, de l\'idée à la réalisation.',
            'objectives' => "Développer un esprit entrepreneurial\nCréer un business plan viable\nComprendre les aspects juridiques et fiscaux\nMaîtriser la gestion financière\nDévelopper des stratégies de marketing et commercialisation",
            'icon' => '💼',
            'duration_weeks' => 16,
            'order' => 2,
            'is_active' => true,
        ]);

        $entrepreneuriatSteps = [
            [
                'title' => 'Idéation et Validation du Concept',
                'description' => 'Définir et valider votre idée d\'entreprise',
                'content' => "Transformez votre idée en concept entrepreneurial viable.\n\nActivités:\n- Brainstorming et génération d'idées\n- Analyse du marché et de la concurrence\n- Validation du concept auprès de clients potentiels\n- Définition de la proposition de valeur unique\n- Études de faisabilité",
                'order' => 1,
                'estimated_duration_days' => 7,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Template Business Model Canvas', 'url' => 'https://example.com/bmc-template', 'type' => 'document'],
                    ['title' => 'Vidéo: Comment valider son idée de business', 'url' => 'https://youtube.com/watch?v=exemple', 'type' => 'video'],
                    ['title' => 'Guide d\'étude de marché', 'url' => 'https://example.com/etude-marche', 'type' => 'document'],
                ],
            ],
            [
                'title' => 'Élaboration du Business Plan',
                'description' => 'Créer un business plan complet et professionnel',
                'content' => "Construisez le plan stratégique de votre entreprise.\n\nContenu du business plan:\n- Résumé exécutif\n- Présentation du projet et de l'équipe\n- Étude de marché détaillée\n- Stratégie marketing et commerciale\n- Plan opérationnel\n- Prévisions financières sur 3 ans\n- Analyse des risques",
                'order' => 2,
                'estimated_duration_days' => 10,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Template Business Plan Complet', 'url' => 'https://example.com/business-plan-template', 'type' => 'document'],
                    ['title' => 'Calculateur de prévisions financières', 'url' => 'https://example.com/calculateur', 'type' => 'link'],
                ],
            ],
            [
                'title' => 'Aspects Juridiques et Administratifs',
                'description' => 'Comprendre les démarches légales et choisir le statut juridique',
                'content' => "Maîtrisez les aspects juridiques de la création d'entreprise.\n\nThèmes:\n- Choix du statut juridique (SARL, SA, Entreprise individuelle...)\n- Démarches d'immatriculation\n- Obligations fiscales et sociales\n- Protection de la propriété intellectuelle\n- Rédaction des statuts\n- Ouverture d'un compte bancaire professionnel",
                'order' => 3,
                'estimated_duration_days' => 7,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Guide des statuts juridiques au Congo', 'url' => 'https://example.com/statuts-juridiques', 'type' => 'document'],
                    ['title' => 'Checklist administrative création entreprise', 'url' => 'https://example.com/checklist-admin', 'type' => 'document'],
                ],
            ],
            [
                'title' => 'Gestion Financière et Comptabilité',
                'description' => 'Apprendre les bases de la gestion financière d\'une entreprise',
                'content' => "Gérez efficacement les finances de votre entreprise.\n\nCompétences développées:\n- Tenir une comptabilité de base\n- Gérer la trésorerie\n- Comprendre les états financiers\n- Établir un budget prévisionnel\n- Calculer le seuil de rentabilité\n- Optimiser la gestion des coûts",
                'order' => 4,
                'estimated_duration_days' => 8,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Formation: Comptabilité pour entrepreneurs', 'url' => 'https://example.com/formation-compta', 'type' => 'video'],
                    ['title' => 'Tableur de gestion financière', 'url' => 'https://example.com/tableur-finance', 'type' => 'document'],
                ],
            ],
            [
                'title' => 'Stratégies Marketing et Vente',
                'description' => 'Développer des stratégies pour attirer et fidéliser les clients',
                'content' => "Apprenez à promouvoir et vendre vos produits/services.\n\nContenu:\n- Définition de votre cible client\n- Stratégies de communication et publicité\n- Marketing digital et réseaux sociaux\n- Techniques de vente et négociation\n- Service client et fidélisation\n- Mesure de la performance marketing",
                'order' => 5,
                'estimated_duration_days' => 10,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Guide du marketing pour startups', 'url' => 'https://example.com/guide-marketing', 'type' => 'article'],
                    ['title' => 'Formation: Vendre efficacement vos produits', 'url' => 'https://example.com/formation-vente', 'type' => 'video'],
                ],
            ],
            [
                'title' => 'Pitch et Recherche de Financement',
                'description' => 'Préparer son pitch et identifier les sources de financement',
                'content' => "Apprenez à présenter votre projet et lever des fonds.\n\nActivités:\n- Élaboration d'un pitch deck professionnel\n- Techniques de présentation et storytelling\n- Identification des sources de financement\n- Préparation aux entretiens avec investisseurs\n- Simulation de pitch devant un jury\n- Stratégies de négociation financière",
                'order' => 6,
                'estimated_duration_days' => 6,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Template Pitch Deck', 'url' => 'https://example.com/pitch-deck-template', 'type' => 'document'],
                    ['title' => 'Liste des financeurs et investisseurs', 'url' => 'https://example.com/financeurs', 'type' => 'document'],
                ],
            ],
            [
                'title' => 'Lancement et Premiers Pas',
                'description' => 'Accompagnement lors du lancement effectif de l\'activité',
                'content' => "Support pour les premières semaines d'activité.\n\nSupport inclus:\n- Accompagnement dans les premières ventes\n- Ajustement de la stratégie selon les retours\n- Gestion des premiers défis\n- Optimisation des processus opérationnels\n- Suivi mensuel pendant 6 mois",
                'order' => 7,
                'estimated_duration_days' => 14,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Checklist de lancement d\'entreprise', 'url' => 'https://example.com/checklist-lancement', 'type' => 'document'],
                ],
            ],
        ];

        foreach ($entrepreneuriatSteps as $stepData) {
            ProgramStep::create(array_merge($stepData, ['program_id' => $entrepreneuriatProgram->id]));
        }

        // 3. Programme de Transformation Professionnelle et Personnel
        $transformationProgram = Program::create([
            'title' => 'Programme de Transformation Professionnelle et Personnelle',
            'slug' => 'transformation-pro-perso',
            'type' => 'transformation_professionnelle',
            'description' => 'Programme holistique visant à développer à la fois vos compétences professionnelles et votre développement personnel pour atteindre vos objectifs de carrière.',
            'objectives' => "Renforcer la confiance en soi et l'estime de soi\nDévelopper des compétences de leadership\nAméliorer la gestion du temps et du stress\nÉtablir un plan de carrière à long terme\nDévelopper une marque personnelle forte",
            'icon' => '🚀',
            'duration_weeks' => 10,
            'order' => 3,
            'is_active' => true,
        ]);

        $transformationSteps = [
            [
                'title' => 'Bilan Personnel et Professionnel Approfondi',
                'description' => 'Auto-analyse et prise de conscience de vos forces et axes d\'amélioration',
                'content' => "Commencez votre transformation par une connaissance approfondie de vous-même.\n\nActivités:\n- Test de personnalité professionnelle (MBTI, DISC)\n- Analyse des forces et faiblesses\n- Identification des valeurs personnelles\n- Bilan de compétences complet\n- Définition des objectifs de transformation\n- Création d'un tableau de bord personnel",
                'order' => 1,
                'estimated_duration_days' => 5,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Test de personnalité MBTI', 'url' => 'https://example.com/test-mbti', 'type' => 'link'],
                    ['title' => 'Grille d\'analyse des compétences', 'url' => 'https://example.com/analyse-competences', 'type' => 'document'],
                ],
            ],
            [
                'title' => 'Développement des Soft Skills',
                'description' => 'Formation intensive sur les compétences comportementales essentielles',
                'content' => "Développez les compétences interpersonnelles recherchées par les employeurs.\n\nCompétences travaillées:\n- Communication efficace (orale et écrite)\n- Intelligence émotionnelle\n- Travail en équipe et collaboration\n- Résolution de problèmes et pensée critique\n- Adaptabilité et gestion du changement\n- Leadership et prise d'initiative",
                'order' => 2,
                'estimated_duration_days' => 10,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Formation: Communication interpersonnelle', 'url' => 'https://example.com/formation-communication', 'type' => 'video'],
                    ['title' => 'Exercices pratiques de soft skills', 'url' => 'https://example.com/exercices-soft-skills', 'type' => 'document'],
                ],
            ],
            [
                'title' => 'Gestion du Temps et Productivité',
                'description' => 'Maîtriser les techniques de gestion du temps et d\'organisation',
                'content' => "Optimisez votre efficacité professionnelle.\n\nMéthodes enseignées:\n- Priorisation des tâches (Matrice Eisenhower)\n- Techniques de planification\n- Gestion des interruptions\n- Méthode Pomodoro\n- Organisation de l'espace de travail\n- Outils digitaux de productivité",
                'order' => 3,
                'estimated_duration_days' => 6,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Guide: Méthodes de gestion du temps', 'url' => 'https://example.com/gestion-temps', 'type' => 'article'],
                    ['title' => 'Applications recommandées de productivité', 'url' => 'https://example.com/apps-productivite', 'type' => 'link'],
                ],
            ],
            [
                'title' => 'Personal Branding et Networking',
                'description' => 'Construire votre marque personnelle et développer votre réseau professionnel',
                'content' => "Créez une présence professionnelle forte et élargissez votre réseau.\n\nActivités:\n- Définition de votre identité professionnelle\n- Optimisation du profil LinkedIn\n- Stratégie de contenu professionnel\n- Techniques de networking efficace\n- Participation à des événements professionnels\n- Construction d'un elevator pitch percutant",
                'order' => 4,
                'estimated_duration_days' => 8,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Guide: Construire sa marque personnelle', 'url' => 'https://example.com/personal-branding', 'type' => 'article'],
                    ['title' => 'Checklist optimisation LinkedIn', 'url' => 'https://example.com/linkedin-checklist', 'type' => 'document'],
                ],
            ],
            [
                'title' => 'Gestion du Stress et Bien-être Professionnel',
                'description' => 'Techniques pour gérer le stress et maintenir un équilibre vie pro/perso',
                'content' => "Préservez votre santé mentale et physique dans votre carrière.\n\nThèmes:\n- Identification des sources de stress\n- Techniques de relaxation et méditation\n- Gestion des émotions au travail\n- Équilibre vie professionnelle/vie personnelle\n- Prévention du burn-out\n- Hygiène de vie et santé au travail",
                'order' => 5,
                'estimated_duration_days' => 5,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Méditations guidées pour professionnels', 'url' => 'https://example.com/meditations', 'type' => 'video'],
                    ['title' => 'Guide: Prévenir le burn-out', 'url' => 'https://example.com/prevention-burnout', 'type' => 'article'],
                ],
            ],
            [
                'title' => 'Plan de Développement de Carrière',
                'description' => 'Élaboration d\'une stratégie de carrière à long terme',
                'content' => "Planifiez votre évolution professionnelle sur 5 ans.\n\nÉléments du plan:\n- Vision de carrière à 5 ans\n- Identification des compétences à développer\n- Plan de formation continue\n- Stratégie d'évolution professionnelle\n- Objectifs SMART à court, moyen et long terme\n- Mise en place d'indicateurs de suivi",
                'order' => 6,
                'estimated_duration_days' => 6,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Template Plan de Carrière', 'url' => 'https://example.com/plan-carriere', 'type' => 'document'],
                    ['title' => 'Vidéo: Définir ses objectifs de carrière', 'url' => 'https://youtube.com/watch?v=exemple', 'type' => 'video'],
                ],
            ],
            [
                'title' => 'Mentorat et Suivi Personnalisé',
                'description' => 'Sessions de coaching individuel avec un mentor professionnel',
                'content' => "Bénéficiez d'un accompagnement personnalisé par un expert.\n\nFormat:\n- 6 sessions de coaching individuel (1h30 chacune)\n- Support par email entre les sessions\n- Révision et ajustement des objectifs\n- Accountability et suivi des progrès\n- Conseils personnalisés selon votre situation\n- Accès à un réseau de professionnels",
                'order' => 7,
                'estimated_duration_days' => 28,
                'is_required' => true,
                'resources' => [
                    ['title' => 'Profils des mentors disponibles', 'url' => 'https://example.com/mentors', 'type' => 'link'],
                    ['title' => 'Guide: Tirer profit du mentorat', 'url' => 'https://example.com/guide-mentorat', 'type' => 'article'],
                ],
            ],
        ];

        foreach ($transformationSteps as $stepData) {
            ProgramStep::create(array_merge($stepData, ['program_id' => $transformationProgram->id]));
        }

        // Create additional random programs using factories
        Program::factory(5)->create()->each(function ($program) {
            ProgramStep::factory($this->faker->numberBetween(3, 8))->create([
                'program_id' => $program->id,
            ]);
        });
    }

    private $faker;

    public function __construct()
    {
        $this->faker = \Faker\Factory::create('fr_FR');
    }
}
