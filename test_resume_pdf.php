<?php

// Script de test pour générer des CV avec tous les templates

require __DIR__ . '/vendor/autoload.php';

use App\Models\Resume;
use App\Services\Resume\ResumePdfService;

// Charger Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🧪 Test de génération de CV avec tous les templates\n\n";

// Données de test complètes et professionnelles
$testData = [
    'title' => 'CV Test - Développeur Full Stack',
    'user_id' => 65,
    'template_type' => 'modern', // Sera changé pour chaque template
    'personal_info' => [
        'name' => 'Jean-Marc Dubois',
        'email' => 'jm.dubois@example.com',
        'phone' => '+237 690 123 456',
        'address' => 'Douala, Cameroun',
        'linkedin' => 'linkedin.com/in/jmdubois',
        'github' => 'github.com/jmdubois',
        'website' => 'jmdubois.dev',
    ],
    'professional_summary' => 'Développeur Full Stack passionné avec 5 ans d\'expérience dans la conception et le développement d\'applications web et mobiles. Expertise en Flutter, Laravel, React et Node.js. Reconnu pour ma capacité à résoudre des problèmes complexes et à livrer des solutions innovantes qui répondent aux besoins des clients.',
    'experiences' => [
        [
            'title' => 'Lead Developer Full Stack',
            'company' => 'TechCorp Solutions',
            'location' => 'Douala, Cameroun',
            'start_date' => '2021-03-01',
            'end_date' => null,
            'currently_working' => true,
            'description' => 'Direction technique d\'une équipe de 5 développeurs pour la création d\'applications web et mobiles innovantes. Responsable de l\'architecture, du code review et de la formation des juniors.',
        ],
        [
            'title' => 'Développeur Mobile Flutter',
            'company' => 'Digital Agency Cameroon',
            'location' => 'Yaoundé, Cameroun',
            'start_date' => '2019-06-01',
            'end_date' => '2021-02-28',
            'currently_working' => false,
            'description' => 'Développement d\'applications mobiles cross-platform avec Flutter pour des clients dans les secteurs de la finance et du e-commerce. Intégration d\'APIs REST et GraphQL.',
        ],
        [
            'title' => 'Développeur Web Junior',
            'company' => 'StartupHub CM',
            'location' => 'Douala, Cameroun',
            'start_date' => '2018-01-15',
            'end_date' => '2019-05-31',
            'currently_working' => false,
            'description' => 'Développement de sites web avec Laravel et Vue.js. Maintenance et optimisation de bases de données MySQL. Participation aux sprints Agile.',
        ],
    ],
    'education' => [
        [
            'degree' => 'Master en Génie Logiciel',
            'institution' => 'Université de Douala',
            'location' => 'Douala, Cameroun',
            'start_date' => '2016-09-01',
            'end_date' => '2018-07-31',
            'description' => 'Spécialisation en développement mobile et web. Projet de fin d\'études: Application mobile de gestion scolaire.',
            'gpa' => 3.8,
        ],
        [
            'degree' => 'Licence en Informatique',
            'institution' => 'École Nationale Supérieure Polytechnique',
            'location' => 'Yaoundé, Cameroun',
            'start_date' => '2013-09-01',
            'end_date' => '2016-07-31',
            'description' => 'Formation en programmation, bases de données, réseaux et systèmes d\'exploitation.',
            'gpa' => 3.5,
        ],
    ],
    'skills' => [
        ['name' => 'Flutter', 'category' => 'Mobile', 'level' => 5],
        ['name' => 'Dart', 'category' => 'Langages', 'level' => 5],
        ['name' => 'Laravel', 'category' => 'Backend', 'level' => 4],
        ['name' => 'PHP', 'category' => 'Langages', 'level' => 4],
        ['name' => 'JavaScript/TypeScript', 'category' => 'Langages', 'level' => 4],
        ['name' => 'React', 'category' => 'Frontend', 'level' => 4],
        ['name' => 'Node.js', 'category' => 'Backend', 'level' => 3],
        ['name' => 'MySQL/PostgreSQL', 'category' => 'Database', 'level' => 4],
        ['name' => 'Git/GitHub', 'category' => 'Outils', 'level' => 5],
        ['name' => 'Docker', 'category' => 'DevOps', 'level' => 3],
    ],
    'certifications' => [
        [
            'name' => 'AWS Certified Developer Associate',
            'issuer' => 'Amazon Web Services',
            'issue_date' => '2022-08-15',
            'expiry_date' => '2025-08-15',
            'credential_id' => 'AWS-DEV-2022-8765',
            'url' => 'https://aws.amazon.com/verification',
        ],
        [
            'name' => 'Google Flutter Development',
            'issuer' => 'Google',
            'issue_date' => '2021-05-20',
            'expiry_date' => null,
            'credential_id' => 'GFLUT-2021-4321',
            'url' => null,
        ],
    ],
    'projects' => [
        [
            'name' => 'E-Commerce Mobile App',
            'description' => 'Application mobile e-commerce complète avec panier, paiement mobile money et suivi de commandes. Plus de 10 000 téléchargements sur Google Play.',
            'url' => 'https://github.com/jmdubois/ecommerce-app',
            'start_date' => '2022-01-01',
            'end_date' => '2022-06-30',
            'technologies' => ['Flutter', 'Laravel', 'MySQL', 'Firebase'],
        ],
        [
            'name' => 'Système de Gestion Scolaire',
            'description' => 'Plateforme web de gestion complète pour établissements scolaires incluant gestion des notes, absences, emplois du temps et communication parents-professeurs.',
            'url' => 'https://github.com/jmdubois/school-management',
            'start_date' => '2020-09-01',
            'end_date' => '2021-03-31',
            'technologies' => ['Laravel', 'Vue.js', 'PostgreSQL', 'Redis'],
        ],
    ],
    'references' => [
        [
            'name' => 'Dr. Marie Kouam',
            'title' => 'CTO',
            'company' => 'TechCorp Solutions',
            'email' => 'm.kouam@techcorp.cm',
            'phone' => '+237 699 888 777',
        ],
    ],
    'hobbies' => [
        'Contribution open source',
        'Blogging technique',
        'Hackathons',
        'Formation des jeunes développeurs',
    ],
    'is_public' => true,
    'is_default' => false,
];

// Templates à tester
$templates = ['modern', 'classic', 'creative', 'professional', 'minimalist'];
$pdfService = new ResumePdfService();

foreach ($templates as $template) {
    echo "📄 Génération du template: $template\n";

    // Créer un CV temporaire
    $testData['template_type'] = $template;
    $testData['title'] = "CV Test - Template " . ucfirst($template);

    $resume = new Resume($testData);
    $resume->id = 999; // ID temporaire pour test
    $resume->user_id = 65;

    try {
        $pdfPath = $pdfService->generatePdf($resume);
        echo "   ✅ PDF généré: $pdfPath\n";
    } catch (Exception $e) {
        echo "   ❌ Erreur: " . $e->getMessage() . "\n";
    }

    echo "\n";
}

echo "✅ Test terminé!\n";
echo "📁 Les PDFs sont dans: storage/app/public/resumes/65/\n";
