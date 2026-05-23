<?php

namespace Database\Seeders;

use App\Models\Program;
use App\Models\ProgramStep;
use Illuminate\Database\Seeder;

/**
 * Peuple la table polymorphe `translations` pour les 3 programmes (Program) et
 * leurs 13 étapes (ProgramStep) en EN / AR / ES. Le FR reste dans les colonnes
 * brutes et sert de fallback automatique.
 *
 * Matche par `slug` côté Program et par `title` FR côté ProgramStep.
 * Idempotent : utilise setTranslation() qui fait un updateOrCreate.
 */
class ProgramsTranslationsSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Seeding program translations (EN/AR/ES)…');

        $this->seedProgramHeaders();
        $this->seedSteps();

        $this->command->info('Program translations seeded.');
    }

    private function seedProgramHeaders(): void
    {
        $headers = [
            'programme-dimmersion-professionnelle-en-entreprise' => [
                'title' => [
                    'en' => 'In-Company Professional Immersion Program',
                    'ar' => 'برنامج الانغماس المهني داخل الشركات',
                    'es' => 'Programa de Inmersión Profesional en Empresa',
                ],
                'description' => [
                    'en' => 'An intensive program that lets candidates discover and integrate into the professional world through hands-on internships and personalised mentoring within partner companies.',
                    'ar' => 'برنامج مكثّف يتيح للمرشحين اكتشاف عالم الأعمال والاندماج فيه من خلال تدريبات عملية ومرافقة شخصية داخل شركات شريكة.',
                    'es' => 'Programa intensivo que permite a los candidatos descubrir e integrarse en el mundo profesional mediante prácticas y acompañamiento personalizado en empresas asociadas.',
                ],
                'objectives' => [
                    'en' => "Gain concrete professional experience\nDevelop technical and soft skills\nBuild a solid professional network\nEase the transition to employment\nUnderstand company codes and culture",
                    'ar' => "اكتساب خبرة مهنية ملموسة\nتطوير المهارات التقنية والسلوكية\nبناء شبكة مهنية متينة\nتسهيل الانتقال إلى سوق العمل\nفهم قواعد وثقافة الشركات",
                    'es' => "Adquirir experiencia profesional concreta\nDesarrollar competencias técnicas y de comportamiento\nCrear una red profesional sólida\nFacilitar la transición al empleo\nComprender los códigos y la cultura de empresa",
                ],
            ],
            'programme-complet-de-formation-a-lentrepreneuriat' => [
                'title' => [
                    'en' => 'Complete Entrepreneurship Training Program',
                    'ar' => 'برنامج التدريب الشامل في ريادة الأعمال',
                    'es' => 'Programa Completo de Formación en Emprendimiento',
                ],
                'description' => [
                    'en' => 'An intensive program that supports candidates in creating, launching and growing their business in Congo. From idea to execution, with mentoring by experts.',
                    'ar' => 'برنامج مكثّف لمرافقة المرشحين في إنشاء مشروعهم وإطلاقه وتطويره في الكونغو. من الفكرة إلى التنفيذ، مع مرافقة من خبراء.',
                    'es' => 'Programa intensivo para acompañar a los candidatos en la creación, el lanzamiento y el desarrollo de su empresa en Congo. De la idea a la ejecución, con acompañamiento de expertos.',
                ],
                'objectives' => [
                    'en' => "Develop a strong entrepreneurial mindset\nBuild a viable and fundable business plan\nUnderstand the legal, tax and regulatory framework in Congo\nMaster financial and accounting management\nBuild an efficient sales & marketing strategy\nLaunch and grow the first customer base",
                    'ar' => "تطوير عقلية ريادية متينة\nبناء خطة عمل قابلة للحياة والتمويل\nفهم الإطار القانوني والضريبي والتنظيمي في الكونغو\nإتقان الإدارة المالية والمحاسبية\nبناء استراتيجية تسويق ومبيعات فعّالة\nالإطلاق وتنمية قاعدة العملاء الأولى",
                    'es' => "Desarrollar una mentalidad emprendedora sólida\nCrear un plan de negocio viable y financiable\nComprender el marco legal, fiscal y normativo en Congo\nDominar la gestión financiera y contable\nConstruir una estrategia de marketing y ventas eficaz\nLanzar y hacer crecer la primera base de clientes",
                ],
            ],
            'programme-de-transformation-professionnelle-et-personnelle' => [
                'title' => [
                    'en' => 'Professional & Personal Transformation Program',
                    'ar' => 'برنامج التحول المهني والشخصي',
                    'es' => 'Programa de Transformación Profesional y Personal',
                ],
                'description' => [
                    'en' => 'A 10-week holistic program to transform your professional and personal life. Develop your soft skills, strengthen your leadership and reach your career goals with personalised support.',
                    'ar' => 'برنامج شمولي مدته 10 أسابيع لتحويل حياتك المهنية والشخصية. طوّر مهاراتك الناعمة، عزّز قيادتك، وحقّق أهدافك المهنية بمرافقة شخصية.',
                    'es' => 'Programa holístico de 10 semanas para transformar tu vida profesional y personal. Desarrolla tus soft skills, refuerza tu liderazgo y alcanza tus objetivos profesionales con acompañamiento personalizado.',
                ],
                'objectives' => [
                    'en' => "Strengthen self-confidence and self-esteem\nDevelop leadership and influence skills\nMaster time, stress and emotion management\nDefine a clear career plan\nBuild a healthy work-life balance",
                    'ar' => "تعزيز الثقة بالنفس وتقدير الذات\nتطوير مهارات القيادة والتأثير\nإتقان إدارة الوقت والضغط والانفعالات\nبناء خطة مهنية واضحة\nتحقيق توازن صحي بين العمل والحياة",
                    'es' => "Reforzar la confianza en uno mismo y la autoestima\nDesarrollar competencias de liderazgo e influencia\nDominar la gestión del tiempo, el estrés y las emociones\nDefinir un plan de carrera claro\nConstruir un equilibrio sano entre vida laboral y personal",
                ],
            ],
        ];

        foreach ($headers as $slug => $fields) {
            $program = Program::where('slug', $slug)->first();
            if (! $program) {
                $this->command->warn("  ⚠️  Program not found: {$slug}");
                continue;
            }

            foreach ($fields as $field => $values) {
                foreach ($values as $locale => $value) {
                    $program->setTranslation($field, $locale, $value);
                }
            }
            $this->command->info("  ✓ {$slug}");
        }
    }

    private function seedSteps(): void
    {
        // Indexé par titre FR exact (tel qu'il est en DB).
        $stepData = [
            // ─── Programme 1 : Immersion ───
            'Évaluation Initiale et Définition des Objectifs' => [
                'title' => [
                    'en' => 'Initial Assessment & Goal Setting',
                    'ar' => 'التقييم الأولي وتحديد الأهداف',
                    'es' => 'Evaluación Inicial y Definición de Objetivos',
                ],
                'description' => [
                    'en' => 'Skills assessment and definition of the career project with an orientation advisor.',
                    'ar' => 'تقييم المهارات وتحديد المشروع المهني مع مستشار توجيه.',
                    'es' => 'Balance de competencias y definición del proyecto profesional con un asesor de orientación.',
                ],
            ],
            "Recherche et Sélection d'Entreprise d'Accueil" => [
                'title' => [
                    'en' => 'Finding & Selecting a Host Company',
                    'ar' => 'البحث عن شركة الاستقبال واختيارها',
                    'es' => 'Búsqueda y Selección de Empresa de Acogida',
                ],
                'description' => [
                    'en' => 'Identifying partner companies and preparing targeted applications.',
                    'ar' => 'تحديد الشركات الشريكة وإعداد ترشيحات مستهدفة.',
                    'es' => 'Identificación de empresas asociadas y preparación de candidaturas específicas.',
                ],
            ],
            "Préparation Intensive à l'Immersion" => [
                'title' => [
                    'en' => 'Intensive Pre-Immersion Preparation',
                    'ar' => 'التحضير المكثّف للانغماس',
                    'es' => 'Preparación Intensiva para la Inmersión',
                ],
                'description' => [
                    'en' => 'Complete pre-immersion training on company codes, soft skills and professional conduct.',
                    'ar' => 'تدريب شامل قبل الانغماس حول قواعد الشركة، المهارات الناعمة وحسن التصرّف المهني.',
                    'es' => 'Formación completa pre-inmersión sobre los códigos de la empresa, soft skills y saber estar profesional.',
                ],
            ],
            "Période d'Immersion en Entreprise (8 semaines)" => [
                'title' => [
                    'en' => 'In-Company Immersion Period (8 weeks)',
                    'ar' => 'فترة الانغماس داخل الشركة (8 أسابيع)',
                    'es' => 'Período de Inmersión en Empresa (8 semanas)',
                ],
                'description' => [
                    'en' => 'Intensive practical internship within the host company with ongoing mentoring.',
                    'ar' => 'تدريب عملي مكثّف داخل شركة الاستقبال مع مرافقة مستمرة.',
                    'es' => 'Prácticas intensivas en la empresa de acogida con acompañamiento continuo.',
                ],
            ],
            "Bilan de l'Immersion et Plan d'Action Carrière" => [
                'title' => [
                    'en' => 'Immersion Review & Career Action Plan',
                    'ar' => 'تقييم الانغماس وخطة عمل المسار المهني',
                    'es' => 'Balance de la Inmersión y Plan de Acción Profesional',
                ],
                'description' => [
                    'en' => 'Comprehensive final assessment and definition of the post-immersion strategy.',
                    'ar' => 'تقييم نهائي شامل وتحديد استراتيجية ما بعد الانغماس.',
                    'es' => 'Evaluación final completa y definición de la estrategia post-inmersión.',
                ],
            ],

            // ─── Programme 2 : Entrepreneuriat ───
            "Idéation et Validation du Concept d'Entreprise" => [
                'title' => [
                    'en' => 'Ideation & Business Concept Validation',
                    'ar' => 'توليد الأفكار والتحقق من مفهوم المشروع',
                    'es' => 'Ideación y Validación del Concepto de Empresa',
                ],
                'description' => [
                    'en' => 'Define, test and validate your business idea against the real market.',
                    'ar' => 'تحديد فكرة مشروعك واختبارها والتحقق منها أمام السوق الحقيقي.',
                    'es' => 'Definir, probar y validar tu idea de negocio frente al mercado real.',
                ],
            ],
            'Élaboration du Business Plan Professionnel' => [
                'title' => [
                    'en' => 'Writing a Professional Business Plan',
                    'ar' => 'إعداد خطة عمل احترافية',
                    'es' => 'Elaboración del Plan de Negocio Profesional',
                ],
                'description' => [
                    'en' => 'Build a complete, professional and bankable business plan.',
                    'ar' => 'إنشاء خطة عمل متكاملة، احترافية وقابلة للتمويل البنكي.',
                    'es' => 'Crear un plan de negocio completo, profesional y financiable.',
                ],
            ],
            'Aspects Juridiques, Administratifs et Fiscaux au Congo' => [
                'title' => [
                    'en' => 'Legal, Administrative & Tax Aspects in Congo',
                    'ar' => 'الجوانب القانونية والإدارية والضريبية في الكونغو',
                    'es' => 'Aspectos Legales, Administrativos y Fiscales en Congo',
                ],
                'description' => [
                    'en' => 'Understand and complete every legal step required to set up a company.',
                    'ar' => 'فهم وإنجاز جميع الإجراءات القانونية لتأسيس الشركة.',
                    'es' => 'Comprender y realizar todos los trámites legales para crear una empresa.',
                ],
            ],
            'Gestion Financière, Comptabilité et Trésorerie' => [
                'title' => [
                    'en' => 'Financial Management, Accounting & Treasury',
                    'ar' => 'الإدارة المالية والمحاسبة والخزينة',
                    'es' => 'Gestión Financiera, Contabilidad y Tesorería',
                ],
                'description' => [
                    'en' => 'Learn to manage your company finances efficiently on a daily basis.',
                    'ar' => 'تعلّم إدارة مالية شركتك بكفاءة يوميًا.',
                    'es' => 'Aprender a gestionar las finanzas de tu empresa de forma eficiente a diario.',
                ],
            ],
            'Stratégies Marketing Digital et Vente Efficace' => [
                'title' => [
                    'en' => 'Digital Marketing Strategies & Effective Selling',
                    'ar' => 'استراتيجيات التسويق الرقمي والبيع الفعّال',
                    'es' => 'Estrategias de Marketing Digital y Venta Eficaz',
                ],
                'description' => [
                    'en' => 'Build marketing and sales strategies to attract and retain customers.',
                    'ar' => 'تطوير استراتيجيات تسويق وبيع لاستقطاب العملاء والاحتفاظ بهم.',
                    'es' => 'Desarrollar estrategias de marketing y ventas para captar y fidelizar clientes.',
                ],
            ],
            'Pitch, Levée de Fonds et Recherche de Financement' => [
                'title' => [
                    'en' => 'Pitching, Fundraising & Financing',
                    'ar' => 'العرض التقديمي وجمع التمويل والبحث عن مصادر التمويل',
                    'es' => 'Pitch, Captación de Fondos y Búsqueda de Financiación',
                ],
                'description' => [
                    'en' => 'Craft a convincing pitch and identify available funding sources.',
                    'ar' => 'إعداد عرض تقديمي مقنع وتحديد مصادر التمويل المتاحة.',
                    'es' => 'Preparar un pitch convincente e identificar las fuentes de financiación disponibles.',
                ],
            ],
            'Lancement, Premiers Clients et Croissance' => [
                'title' => [
                    'en' => 'Launch, First Customers & Growth',
                    'ar' => 'الإطلاق والعملاء الأوائل والنمو',
                    'es' => 'Lanzamiento, Primeros Clientes y Crecimiento',
                ],
                'description' => [
                    'en' => 'Intensive support during launch and the first 6 months of activity.',
                    'ar' => 'مرافقة مكثّفة خلال الإطلاق والأشهر الستة الأولى من النشاط.',
                    'es' => 'Acompañamiento intensivo durante el lanzamiento y los 6 primeros meses de actividad.',
                ],
            ],

            // ─── Programme 3 : Transformation ───
            'Bilan Personnel et Professionnel Approfondi' => [
                'title' => [
                    'en' => 'In-Depth Personal & Professional Assessment',
                    'ar' => 'تقييم شخصي ومهني معمّق',
                    'es' => 'Balance Personal y Profesional en Profundidad',
                ],
                'description' => [
                    'en' => 'Complete self-analysis and awareness of your strengths, values and growth areas.',
                    'ar' => 'تحليل ذاتي شامل ووعي بنقاط قوّتك وقيمك ومحاور تطوّرك.',
                    'es' => 'Auto-análisis completo y toma de conciencia de tus fortalezas, valores y áreas de desarrollo.',
                ],
            ],
        ];

        $count = 0;
        $notFound = [];

        foreach ($stepData as $frTitle => $payload) {
            $steps = ProgramStep::where('title', $frTitle)->get();

            if ($steps->isEmpty()) {
                $notFound[] = $frTitle;
                continue;
            }

            foreach ($steps as $step) {
                foreach ($payload as $field => $values) {
                    foreach ($values as $locale => $value) {
                        $step->setTranslation($field, $locale, $value);
                    }
                }
                $count++;
            }
        }

        $this->command->info("  ✓ {$count} steps translated");

        if (! empty($notFound)) {
            $this->command->warn('  ⚠️  Steps not found in DB:');
            foreach ($notFound as $t) {
                $this->command->warn("     - {$t}");
            }
        }
    }
}
