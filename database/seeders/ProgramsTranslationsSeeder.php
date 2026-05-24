<?php

namespace Database\Seeders;

use App\Models\Program;
use App\Models\ProgramStep;
use Illuminate\Database\Seeder;

/**
 * Peuple la table polymorphe `translations` pour les 3 programmes (Program) et
 * leurs étapes (ProgramStep) en EN / AR / ES. Le FR reste dans les colonnes
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
            'immersion-professionnelle-entreprise' => [
                'title' => [
                    'en' => 'In-Company Professional Immersion Program',
                    'ar' => 'برنامج الانغماس المهني داخل الشركات',
                    'es' => 'Programa de Inmersión Profesional en Empresa',
                ],
                'description' => [
                    'en' => "Intensive 16-week program that lets candidates discover and integrate into the professional world through hands-on internships, personalised mentoring and continuous coaching within partner companies in Congo.",
                    'ar' => "برنامج مكثّف مدته 16 أسبوعًا يتيح للمرشحين اكتشاف عالم الأعمال والاندماج فيه من خلال تدريبات عملية ومرافقة شخصية وتأطير مستمر داخل شركات شريكة في الكونغو.",
                    'es' => "Programa intensivo de 16 semanas que permite a los candidatos descubrir e integrarse en el mundo profesional mediante prácticas, acompañamiento personalizado y mentoría continua en empresas asociadas en Congo.",
                ],
                'objectives' => [
                    'en' => "Gain concrete in-company professional experience\nDevelop in-demand technical and soft skills\nBuild a solid and lasting professional network\nMaster Congolese company codes and culture\nObtain a valuable internship certificate\nEase the transition to a stable job\nDevelop your professional personal branding\nBenefit from 3 months of post-immersion mentoring",
                    'ar' => "اكتساب خبرة مهنية ملموسة داخل الشركات\nتطوير مهارات تقنية وسلوكية مطلوبة\nبناء شبكة مهنية متينة ودائمة\nإتقان قواعد وثقافة الشركات الكونغولية\nالحصول على شهادة تدريب ذات قيمة\nتسهيل الانتقال إلى عمل مستقر\nتطوير العلامة الشخصية المهنية\nالاستفادة من 3 أشهر من المرافقة بعد الانغماس",
                    'es' => "Adquirir experiencia profesional concreta en empresa\nDesarrollar competencias técnicas y de comportamiento demandadas\nCrear una red profesional sólida y duradera\nDominar los códigos y la cultura de empresa congoleña\nObtener un certificado de prácticas valorizado\nFacilitar la transición a un empleo estable\nDesarrollar tu personal branding profesional\nBeneficiarte de una mentoría post-inmersión de 3 meses",
                ],
            ],
            'formation-entrepreneuriat-complet' => [
                'title' => [
                    'en' => 'Complete Entrepreneurship Training Program',
                    'ar' => 'برنامج التدريب الشامل في ريادة الأعمال',
                    'es' => 'Programa Completo de Formación en Emprendimiento',
                ],
                'description' => [
                    'en' => "Intensive 20-week program supporting candidates in creating, launching and growing their business in Congo. From idea to first revenue, mentored by recognised entrepreneurs and experts.",
                    'ar' => "برنامج مكثّف مدته 20 أسبوعًا لمرافقة المرشحين في إنشاء مشروعهم وإطلاقه وتطويره في الكونغو. من الفكرة إلى أول إيرادات، مع مرافقة من رواد أعمال وخبراء معتمدين.",
                    'es' => "Programa intensivo de 20 semanas para acompañar a los candidatos en la creación, lanzamiento y desarrollo de su empresa en Congo. De la idea a los primeros ingresos, con acompañamiento de emprendedores y expertos reconocidos.",
                ],
                'objectives' => [
                    'en' => "Develop a strong and resilient entrepreneurial mindset\nValidate your business idea against the Congolese market\nBuild a viable, fundable and bankable business plan\nUnderstand the legal, tax and regulatory framework in Congo\nMaster financial and accounting management (SYSCOHADA)\nDevelop effective digital marketing and sales strategies\nAccess financing (banks, investors, grants)\nRecruit and manage your first team\nDigitise your business with the right tools\nReach your first revenue and retain your customers",
                    'ar' => "تطوير عقلية ريادية متينة وقادرة على الصمود\nالتحقق من فكرة المشروع أمام السوق الكونغولية\nبناء خطة عمل قابلة للحياة والتمويل والاعتماد البنكي\nفهم الإطار القانوني والضريبي والتنظيمي في الكونغو\nإتقان الإدارة المالية والمحاسبية (SYSCOHADA)\nتطوير استراتيجيات تسويق رقمي ومبيعات فعّالة\nالوصول إلى مصادر التمويل (بنوك، مستثمرون، منح)\nتوظيف وإدارة الفريق الأول\nرقمنة النشاط بالأدوات المناسبة\nتحقيق أولى الإيرادات والحفاظ على العملاء",
                    'es' => "Desarrollar una mentalidad emprendedora sólida y resiliente\nValidar tu idea de negocio en el mercado congoleño\nCrear un plan de negocio viable, financiable y bancable\nComprender el marco legal, fiscal y normativo en Congo\nDominar la gestión financiera y contable (SYSCOHADA)\nDesarrollar estrategias de marketing digital y ventas eficaces\nAcceder a financiación (bancos, inversores, subvenciones)\nReclutar y gestionar tu primer equipo\nDigitalizar tu actividad con las herramientas adecuadas\nAlcanzar tus primeros ingresos y fidelizar a tus clientes",
                ],
            ],
            'transformation-professionnelle-personnelle' => [
                'title' => [
                    'en' => 'Professional & Personal Transformation Program',
                    'ar' => 'برنامج التحول المهني والشخصي',
                    'es' => 'Programa de Transformación Profesional y Personal',
                ],
                'description' => [
                    'en' => "Comprehensive training program bringing together 19 modules covering the essential skills for your professional and personal transformation. From languages to technology, personal development to technical trades — a rich and diverse journey tailored to the Congolese and African context.",
                    'ar' => "برنامج تكويني شامل يجمع 19 وحدة تغطي المهارات الأساسية لتحوّلك المهني والشخصي. من اللغات إلى التكنولوجيا، ومن التنمية الذاتية إلى المهن التقنية، مسار غني ومتنوع يناسب السياقين الكونغولي والإفريقي.",
                    'es' => "Programa completo de formación que reúne 19 módulos que cubren las competencias esenciales para tu transformación profesional y personal. De los idiomas a la tecnología, del desarrollo personal a los oficios técnicos: un recorrido rico y diverso adaptado al contexto congoleño y africano.",
                ],
                'objectives' => [
                    'en' => "Build cross-functional skills across 19 key fields\nStrengthen your language and office software skills\nMaster modern digital and tech tools\nGrow your personal and professional potential\nExplore technical and specialised fields\nEarn recognised certifications in every module",
                    'ar' => "اكتساب مهارات عرضية في 19 مجالًا رئيسيًا\nتعزيز المهارات اللغوية والمكتبية\nإتقان الأدوات الرقمية والتقنية الحديثة\nتنمية الإمكانات الشخصية والمهنية\nاستكشاف مجالات تقنية ومتخصّصة\nالحصول على شهادات معترف بها في كل وحدة",
                    'es' => "Adquirir competencias transversales en 19 áreas clave\nReforzar tus competencias lingüísticas y ofimáticas\nDominar las herramientas digitales y tecnológicas modernas\nDesarrollar tu potencial personal y profesional\nExplorar campos técnicos y especializados\nObtener certificaciones reconocidas en cada módulo",
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

            // ─── Programme 33 : Immersion Professionnelle en Entreprise ───
            'Bilan de Compétences et Projet Professionnel' => [
                'title' => [
                    'en' => 'Skills Assessment & Career Project',
                    'ar' => 'تقييم المهارات والمشروع المهني',
                    'es' => 'Balance de Competencias y Proyecto Profesional',
                ],
                'description' => [
                    'en' => "In-depth assessment of your profile and construction of a clear and realistic career project.",
                    'ar' => "تقييم معمّق لملفك وبناء مشروع مهني واضح وواقعي.",
                    'es' => "Evaluación en profundidad de tu perfil y construcción de un proyecto profesional claro y realista.",
                ],
            ],
            'Construction du Dossier de Candidature' => [
                'title' => [
                    'en' => 'Building Your Application Portfolio',
                    'ar' => 'إعداد ملف الترشّح',
                    'es' => 'Construcción del Expediente de Candidatura',
                ],
                'description' => [
                    'en' => "Creating an impactful professional CV, targeted cover letters and a skills portfolio.",
                    'ar' => "إنشاء سيرة ذاتية مهنية مؤثرة، رسائل تحفيزية مستهدفة وملف مهارات.",
                    'es' => "Creación de un CV profesional impactante, cartas de motivación específicas y un portfolio de competencias.",
                ],
            ],
            "Recherche et Sélection d'Entreprise d'Accueil" => [
                'title' => [
                    'en' => 'Finding & Selecting a Host Company',
                    'ar' => 'البحث عن شركة الاستقبال واختيارها',
                    'es' => 'Búsqueda y Selección de Empresa de Acogida',
                ],
                'description' => [
                    'en' => "Strategic identification of partner companies and candidate-company matching process.",
                    'ar' => "تحديد استراتيجي للشركات الشريكة وعملية التوفيق بين المرشّح والشركة.",
                    'es' => "Identificación estratégica de empresas asociadas y proceso de matching candidato-empresa.",
                ],
            ],
            "Formation Pré-Immersion : Soft Skills et Culture d'Entreprise" => [
                'title' => [
                    'en' => 'Pre-Immersion Training: Soft Skills & Company Culture',
                    'ar' => 'تدريب قبل الانغماس: المهارات الناعمة وثقافة الشركة',
                    'es' => 'Formación Pre-Inmersión: Soft Skills y Cultura de Empresa',
                ],
                'description' => [
                    'en' => "Intensive 5-day bootcamp on company codes, professional communication and workplace conduct.",
                    'ar' => "تدريب مكثّف مدته 5 أيام حول قواعد الشركة، التواصل المهني وحسن التصرّف.",
                    'es' => "Bootcamp intensivo de 5 días sobre los códigos de la empresa, la comunicación profesional y el saber estar.",
                ],
            ],
            'Immersion Phase 1 : Observation et Intégration (4 semaines)' => [
                'title' => [
                    'en' => 'Immersion Phase 1: Observation & Integration (4 weeks)',
                    'ar' => 'الانغماس المرحلة 1: الملاحظة والاندماج (4 أسابيع)',
                    'es' => 'Inmersión Fase 1: Observación e Integración (4 semanas)',
                ],
                'description' => [
                    'en' => "First immersion phase focused on discovering the company, observing processes and integrating into the team.",
                    'ar' => "المرحلة الأولى من الانغماس تركّز على اكتشاف الشركة وملاحظة العمليات والاندماج في الفريق.",
                    'es' => "Primera fase de inmersión centrada en descubrir la empresa, observar los procesos e integrarse en el equipo.",
                ],
            ],
            'Immersion Phase 2 : Autonomie et Contribution (4 semaines)' => [
                'title' => [
                    'en' => 'Immersion Phase 2: Autonomy & Contribution (4 weeks)',
                    'ar' => 'الانغماس المرحلة 2: الاستقلالية والمساهمة (4 أسابيع)',
                    'es' => 'Inmersión Fase 2: Autonomía y Contribución (4 semanas)',
                ],
                'description' => [
                    'en' => "Second immersion phase focused on gaining autonomy, managing projects and creating measurable value.",
                    'ar' => "المرحلة الثانية من الانغماس تركّز على اكتساب الاستقلالية وإدارة المشاريع وخلق قيمة قابلة للقياس.",
                    'es' => "Segunda fase de inmersión centrada en la autonomía, la gestión de proyectos y la creación de valor medible.",
                ],
            ],
            'Networking et Personal Branding' => [
                'title' => [
                    'en' => 'Networking & Personal Branding',
                    'ar' => 'بناء الشبكة والعلامة الشخصية',
                    'es' => 'Networking y Personal Branding',
                ],
                'description' => [
                    'en' => "Building a solid professional network and developing your personal brand during and after the immersion.",
                    'ar' => "بناء شبكة مهنية متينة وتطوير علامتك الشخصية خلال الانغماس وبعده.",
                    'es' => "Construcción de una red profesional sólida y desarrollo de tu marca personal durante y después de la inmersión.",
                ],
            ],
            'Bilan Final, Certification et Accompagnement Post-Immersion' => [
                'title' => [
                    'en' => 'Final Review, Certification & Post-Immersion Support',
                    'ar' => 'التقييم النهائي والشهادة والمرافقة بعد الانغماس',
                    'es' => 'Balance Final, Certificación y Acompañamiento Post-Inmersión',
                ],
                'description' => [
                    'en' => "Comprehensive immersion review, certificate award and career plan kick-off with 3 months of mentoring.",
                    'ar' => "تقييم شامل للانغماس، تسليم الشهادة وانطلاق خطة المسار المهني مع مرافقة لمدة 3 أشهر.",
                    'es' => "Evaluación completa de la inmersión, entrega del certificado y lanzamiento del plan de carrera con mentoría de 3 meses.",
                ],
            ],

            // ─── Programme 34 : Formation à l'Entrepreneuriat ───
            'Mindset Entrepreneurial et Leadership' => [
                'title' => [
                    'en' => 'Entrepreneurial Mindset & Leadership',
                    'ar' => 'العقلية الريادية والقيادة',
                    'es' => 'Mindset Emprendedor y Liderazgo',
                ],
                'description' => [
                    'en' => "Develop the mindset, discipline and habits that set apart entrepreneurs who succeed from those who give up.",
                    'ar' => "تطوير العقلية والانضباط والعادات التي تفرّق بين رائد الأعمال الناجح ومن يتخلّى.",
                    'es' => "Desarrollar la mentalidad, la disciplina y los hábitos que diferencian a los emprendedores que triunfan de los que abandonan.",
                ],
            ],
            "Idéation et Validation du Concept d'Entreprise" => [
                'title' => [
                    'en' => 'Ideation & Business Concept Validation',
                    'ar' => 'توليد الأفكار والتحقق من مفهوم المشروع',
                    'es' => 'Ideación y Validación del Concepto de Empresa',
                ],
                'description' => [
                    'en' => "Generate, test and validate your business idea on the real Congolese market.",
                    'ar' => "توليد فكرة مشروعك واختبارها والتحقق منها أمام السوق الكونغولية الحقيقية.",
                    'es' => "Generar, probar y validar tu idea de negocio en el mercado congoleño real.",
                ],
            ],
            'Élaboration du Business Plan Professionnel' => [
                'title' => [
                    'en' => 'Writing a Professional Business Plan',
                    'ar' => 'إعداد خطة عمل احترافية',
                    'es' => 'Elaboración del Plan de Negocio Profesional',
                ],
                'description' => [
                    'en' => "Build a complete, professional and bankable business plan that convinces investors and banks.",
                    'ar' => "إنشاء خطة عمل متكاملة، احترافية وقابلة للتمويل تقنع المستثمرين والبنوك.",
                    'es' => "Crear un plan de negocio completo, profesional y bancable que convenza a inversores y bancos.",
                ],
            ],
            'Cadre Juridique, Fiscal et Administratif au Congo' => [
                'title' => [
                    'en' => 'Legal, Tax & Administrative Framework in Congo',
                    'ar' => 'الإطار القانوني والضريبي والإداري في الكونغو',
                    'es' => 'Marco Legal, Fiscal y Administrativo en Congo',
                ],
                'description' => [
                    'en' => "Master every legal step and officially set up your company in Congo.",
                    'ar' => "إتقان جميع الإجراءات القانونية وتأسيس شركتك رسميًا في الكونغو.",
                    'es' => "Dominar todos los trámites legales y crear oficialmente tu empresa en Congo.",
                ],
            ],
            'Gestion Financière, Comptabilité et Trésorerie' => [
                'title' => [
                    'en' => 'Financial Management, Accounting & Treasury',
                    'ar' => 'الإدارة المالية والمحاسبة والخزينة',
                    'es' => 'Gestión Financiera, Contabilidad y Tesorería',
                ],
                'description' => [
                    'en' => "Master daily financial management to secure your company's survival and growth.",
                    'ar' => "إتقان الإدارة المالية اليومية لضمان بقاء شركتك ونموّها.",
                    'es' => "Dominar la gestión financiera diaria de tu empresa para asegurar su supervivencia y crecimiento.",
                ],
            ],
            'Marketing Digital et Stratégie de Vente' => [
                'title' => [
                    'en' => 'Digital Marketing & Sales Strategy',
                    'ar' => 'التسويق الرقمي واستراتيجية البيع',
                    'es' => 'Marketing Digital y Estrategia de Venta',
                ],
                'description' => [
                    'en' => "Build an impactful online presence and effective sales strategies to win your first customers.",
                    'ar' => "بناء حضور رقمي مؤثر واستراتيجيات بيع فعّالة لكسب أوائل العملاء.",
                    'es' => "Desarrollar una presencia online impactante y estrategias de venta eficaces para conquistar a tus primeros clientes.",
                ],
            ],
            'Pitch, Levée de Fonds et Financement' => [
                'title' => [
                    'en' => 'Pitching, Fundraising & Financing',
                    'ar' => 'العرض التقديمي وجمع التمويل والتمويل',
                    'es' => 'Pitch, Captación de Fondos y Financiación',
                ],
                'description' => [
                    'en' => "Prepare a convincing pitch and secure the funding needed for launch and growth.",
                    'ar' => "إعداد عرض تقديمي مقنع وتأمين التمويل اللازم للإطلاق والنمو.",
                    'es' => "Preparar un pitch convincente y movilizar la financiación necesaria para el lanzamiento y el crecimiento.",
                ],
            ],
            "Recrutement, Management d'Équipe et Leadership" => [
                'title' => [
                    'en' => 'Recruitment, Team Management & Leadership',
                    'ar' => 'التوظيف وإدارة الفريق والقيادة',
                    'es' => 'Reclutamiento, Gestión de Equipos y Liderazgo',
                ],
                'description' => [
                    'en' => "Recruit your first team members, build a strong company culture and lead effectively.",
                    'ar' => "توظيف أوائل أعضاء الفريق وبناء ثقافة شركة قوية والقيادة بفعالية.",
                    'es' => "Reclutar a tus primeros colaboradores, construir una cultura de empresa fuerte y gestionar con eficacia.",
                ],
            ],
            'Digitalisation et Outils Tech pour Entrepreneurs' => [
                'title' => [
                    'en' => 'Digitalisation & Tech Tools for Entrepreneurs',
                    'ar' => 'الرقمنة والأدوات التقنية لروّاد الأعمال',
                    'es' => 'Digitalización y Herramientas Tech para Emprendedores',
                ],
                'description' => [
                    'en' => "Digitise your business with the right tools to boost productivity, cut costs and scale.",
                    'ar' => "رقمنة نشاطك بالأدوات المناسبة لزيادة الإنتاجية وتقليل التكاليف وتوسيع نطاق العمل.",
                    'es' => "Digitalizar tu actividad con las herramientas adecuadas para ganar productividad, reducir costes y escalar tu negocio.",
                ],
            ],
            'Lancement, Premiers Clients et Stratégie de Croissance' => [
                'title' => [
                    'en' => 'Launch, First Customers & Growth Strategy',
                    'ar' => 'الإطلاق والعملاء الأوائل واستراتيجية النمو',
                    'es' => 'Lanzamiento, Primeros Clientes y Estrategia de Crecimiento',
                ],
                'description' => [
                    'en' => "Officially launch your company, win your first paying customers and lay the foundations for sustainable growth.",
                    'ar' => "إطلاق شركتك رسميًا، كسب أوائل العملاء الدافعين وإرساء أسس نمو مستدام.",
                    'es' => "Lanzar oficialmente tu empresa, conquistar a tus primeros clientes de pago y sentar las bases de un crecimiento duradero.",
                ],
            ],

            // ─── Programme 32 : Transformation Professionnelle et Personnelle (19 modules INSAM) ───
            'Anglais Professionnel' => [
                'title' => [
                    'en' => 'Professional English',
                    'ar' => 'الإنجليزية المهنية',
                    'es' => 'Inglés Profesional',
                ],
                'description' => [
                    'en' => "Boost your English skills with engaging training videos. From grammar to professional writing and preparation for interviews in English.",
                    'ar' => "حسّن مهاراتك في اللغة الإنجليزية عبر فيديوهات تكوينية شيّقة. من القواعد إلى الكتابة المهنية، وصولًا إلى التحضير للمقابلات بالإنجليزية.",
                    'es' => "Mejora tus competencias en inglés con vídeos de formación atractivos. De la gramática a la redacción profesional, pasando por la preparación de entrevistas en inglés.",
                ],
            ],
            'Marketing et Commercialisation' => [
                'title' => [
                    'en' => 'Marketing & Sales',
                    'ar' => 'التسويق والمبيعات',
                    'es' => 'Marketing y Comercialización',
                ],
                'description' => [
                    'en' => "Explore modern, digital and traditional marketing strategies. Learn to create and run effective advertising campaigns.",
                    'ar' => "استكشف استراتيجيات التسويق الحديث الرقمي والتقليدي. تعلّم إنشاء وإدارة حملات إعلانية فعّالة.",
                    'es' => "Explora las estrategias de marketing moderno, digital y tradicional. Aprende a crear y gestionar campañas publicitarias eficaces.",
                ],
            ],
            'Développement Personnel' => [
                'title' => [
                    'en' => 'Personal Development',
                    'ar' => 'التنمية الذاتية',
                    'es' => 'Desarrollo Personal',
                ],
                'description' => [
                    'en' => "Strengthen your confidence, improve your time management and build a positive mindset for academic and professional success.",
                    'ar' => "عزّز ثقتك بنفسك، حسّن إدارتك للوقت ونمِّ عقلية إيجابية للنجاح في الحياة الأكاديمية والمهنية.",
                    'es' => "Refuerza tu confianza, mejora tu gestión del tiempo y cultiva una mentalidad positiva para triunfar en la vida académica y profesional.",
                ],
            ],
            'Logiciels Microsoft' => [
                'title' => [
                    'en' => 'Microsoft Software',
                    'ar' => 'برامج مايكروسوفت',
                    'es' => 'Software Microsoft',
                ],
                'description' => [
                    'en' => "Master Word, Excel, PowerPoint, Outlook and Teams to boost your productivity and craft professional documents.",
                    'ar' => "أتقن Word وExcel وPowerPoint وOutlook وTeams لتعزيز إنتاجيتك وإعداد وثائق احترافية.",
                    'es' => "Domina Word, Excel, PowerPoint, Outlook y Teams para impulsar tu productividad y crear documentos profesionales.",
                ],
            ],
            'Ressources Humaines' => [
                'title' => [
                    'en' => 'Human Resources',
                    'ar' => 'الموارد البشرية',
                    'es' => 'Recursos Humanos',
                ],
                'description' => [
                    'en' => "Master the fundamentals of recruitment, talent management, payroll and Congolese and African labour law.",
                    'ar' => "أتقن أساسيات التوظيف وإدارة المواهب والرواتب وقانون العمل الكونغولي والإفريقي.",
                    'es' => "Domina los fundamentos del reclutamiento, la gestión del talento, las nóminas y el derecho laboral congoleño y africano.",
                ],
            ],
            'Automatisme Industriel' => [
                'title' => [
                    'en' => 'Industrial Automation',
                    'ar' => 'الأتمتة الصناعية',
                    'es' => 'Automatización Industrial',
                ],
                'description' => [
                    'en' => "Discover industrial automation: PLC programming, Ladder and Grafcet languages, sensors and automated systems.",
                    'ar' => "اكتشف الأتمتة الصناعية: برمجة PLC/API ولغات Ladder وGrafcet، وأجهزة الاستشعار والأنظمة الآلية.",
                    'es' => "Descubre la automatización industrial: programación de autómatas PLC/API, lenguajes Ladder y Grafcet, sensores y sistemas automatizados.",
                ],
            ],
            'Comptabilité et Finance' => [
                'title' => [
                    'en' => 'Accounting & Finance',
                    'ar' => 'المحاسبة والمالية',
                    'es' => 'Contabilidad y Finanzas',
                ],
                'description' => [
                    'en' => "Master general accounting, the SYSCOHADA chart of accounts, financial statements, treasury management and CEMAC-zone taxation.",
                    'ar' => "أتقن المحاسبة العامة ومخطط SYSCOHADA والقوائم المالية وإدارة الخزينة والضرائب في منطقة CEMAC.",
                    'es' => "Domina la contabilidad general, el plan SYSCOHADA, los estados financieros, la gestión de tesorería y la fiscalidad en la zona CEMAC.",
                ],
            ],
            'Dessin Technique : DAO & CAO' => [
                'title' => [
                    'en' => 'Technical Drawing: CAD & CAM',
                    'ar' => 'الرسم التقني: DAO و CAO',
                    'es' => 'Dibujo Técnico: DAO y CAO',
                ],
                'description' => [
                    'en' => "Learn computer-aided drawing with AutoCAD, 3D modelling with SolidWorks and reading standardised technical drawings.",
                    'ar' => "تعلّم الرسم بمساعدة الحاسوب باستخدام AutoCAD، النمذجة ثلاثية الأبعاد بـ SolidWorks، وقراءة المخططات التقنية المعيارية.",
                    'es' => "Aprende el dibujo asistido por ordenador con AutoCAD, el modelado 3D con SolidWorks y la lectura de planos técnicos normalizados.",
                ],
            ],
            'Infographie et Web Design' => [
                'title' => [
                    'en' => 'Graphic Design & Web Design',
                    'ar' => 'التصميم الجرافيكي وتصميم الويب',
                    'es' => 'Diseño Gráfico y Web Design',
                ],
                'description' => [
                    'en' => "Master Photoshop, Illustrator, UX/UI design and the creation of attractive responsive websites.",
                    'ar' => "أتقن Photoshop وIllustrator وتصميم UX/UI وإنشاء مواقع ويب جذابة ومتجاوبة.",
                    'es' => "Domina Photoshop, Illustrator, el diseño UX/UI y la creación de sitios web atractivos y responsive.",
                ],
            ],
            'E-Commerce' => [
                'title' => [
                    'en' => 'E-Commerce',
                    'ar' => 'التجارة الإلكترونية',
                    'es' => 'E-Commerce',
                ],
                'description' => [
                    'en' => "Create, manage and grow your online store. Master dropshipping, marketplaces and online sales strategies.",
                    'ar' => "أنشئ متجرك الإلكتروني وأدِره ونمِّه. أتقن الدروبشيبينغ والأسواق الإلكترونية واستراتيجيات البيع عبر الإنترنت.",
                    'es' => "Crea, gestiona y desarrolla tu tienda online. Domina el dropshipping, los marketplaces y las estrategias de venta online.",
                ],
            ],
            'Électronique' => [
                'title' => [
                    'en' => 'Electronics',
                    'ar' => 'الإلكترونيات',
                    'es' => 'Electrónica',
                ],
                'description' => [
                    'en' => "Master electronic components, electrical laws, PCB circuit design and Arduino/ESP32 programming.",
                    'ar' => "أتقن المكونات الإلكترونية وقوانين الكهرباء وتصميم لوحات PCB وبرمجة Arduino/ESP32.",
                    'es' => "Domina los componentes electrónicos, las leyes de la electricidad, el diseño de circuitos PCB y la programación Arduino/ESP32.",
                ],
            ],
            'Gestion de Projets' => [
                'title' => [
                    'en' => 'Project Management',
                    'ar' => 'إدارة المشاريع',
                    'es' => 'Gestión de Proyectos',
                ],
                'description' => [
                    'en' => "Master Waterfall, Agile and Scrum methodologies. Learn to plan, execute and close projects successfully.",
                    'ar' => "أتقن منهجيات Waterfall وAgile وScrum. تعلّم تخطيط المشاريع وتنفيذها وإغلاقها بنجاح.",
                    'es' => "Domina las metodologías Waterfall, Agile y Scrum. Aprende a planificar, ejecutar y cerrar proyectos con éxito.",
                ],
            ],
            'Programmation Informatique' => [
                'title' => [
                    'en' => 'Computer Programming',
                    'ar' => 'البرمجة المعلوماتية',
                    'es' => 'Programación Informática',
                ],
                'description' => [
                    'en' => "Get started with Python, JavaScript and PHP. Build web applications and create your project portfolio.",
                    'ar' => "ابدأ بتعلّم لغات Python وJavaScript وPHP. طوّر تطبيقات ويب وأنشئ ملف مشاريعك.",
                    'es' => "Iníciate en los lenguajes Python, JavaScript y PHP. Desarrolla aplicaciones web y crea tu portfolio de proyectos.",
                ],
            ],
            'Systèmes Informatiques' => [
                'title' => [
                    'en' => 'Computer Systems',
                    'ar' => 'النظم المعلوماتية',
                    'es' => 'Sistemas Informáticos',
                ],
                'description' => [
                    'en' => "Master Windows Server and Linux administration, VMware/Hyper-V virtualisation and systems maintenance.",
                    'ar' => "أتقن إدارة Windows Server وLinux، والافتراضية مع VMware/Hyper-V، وصيانة النظم.",
                    'es' => "Domina la administración de Windows Server y Linux, la virtualización VMware/Hyper-V y el mantenimiento de sistemas.",
                ],
            ],
            'Intelligence Artificielle' => [
                'title' => [
                    'en' => 'Artificial Intelligence',
                    'ar' => 'الذكاء الاصطناعي',
                    'es' => 'Inteligencia Artificial',
                ],
                'description' => [
                    'en' => "Discover Machine Learning, Deep Learning, Python and generative AI tools such as ChatGPT, Claude and Midjourney.",
                    'ar' => "اكتشف تعلم الآلة والتعلّم العميق ولغة Python وأدوات الذكاء الاصطناعي التوليدي مثل ChatGPT وClaude وMidjourney.",
                    'es' => "Descubre el Machine Learning, el Deep Learning, Python y las herramientas de IA generativa como ChatGPT, Claude y Midjourney.",
                ],
            ],
            'Monnaies Virtuelles et Cryptomonnaies' => [
                'title' => [
                    'en' => 'Virtual Currencies & Cryptocurrencies',
                    'ar' => 'العملات الافتراضية والعملات المشفّرة',
                    'es' => 'Monedas Virtuales y Criptomonedas',
                ],
                'description' => [
                    'en' => "Understand blockchain, Bitcoin, Ethereum, DeFi and NFTs. Learn to invest in cryptocurrencies responsibly.",
                    'ar' => "افهم البلوكشين والبيتكوين والإيثيريوم والتمويل اللامركزي (DeFi) والـ NFTs. تعلّم الاستثمار المسؤول في العملات المشفّرة.",
                    'es' => "Comprende la blockchain, Bitcoin, Ethereum, la DeFi y los NFT. Aprende a invertir de forma responsable en criptomonedas.",
                ],
            ],
            'DJ et Musique Électronique' => [
                'title' => [
                    'en' => 'DJing & Electronic Music',
                    'ar' => 'الدي جي والموسيقى الإلكترونية',
                    'es' => 'DJ y Música Electrónica',
                ],
                'description' => [
                    'en' => "Master mixing techniques, FL Studio and Ableton Live software, and build your personal DJ brand.",
                    'ar' => "أتقن تقنيات المكساج وبرامج FL Studio وAbleton Live، وطوّر علامتك الشخصية كـ دي جي.",
                    'es' => "Domina las técnicas de mezcla, los softwares FL Studio y Ableton Live, y desarrolla tu marca personal como DJ.",
                ],
            ],
            'Pédagogie et Enseignement' => [
                'title' => [
                    'en' => 'Pedagogy & Teaching',
                    'ar' => 'علم التربية والتعليم',
                    'es' => 'Pedagogía y Enseñanza',
                ],
                'description' => [
                    'en' => "Master educational theories, innovative teaching methods and the integration of digital tools in education.",
                    'ar' => "أتقن النظريات التربوية وأساليب التدريس المبتكرة ودمج الأدوات الرقمية في التعليم.",
                    'es' => "Domina las teorías educativas, los métodos de enseñanza innovadores y la integración de las herramientas digitales en la educación.",
                ],
            ],
            'Réseaux Informatiques' => [
                'title' => [
                    'en' => 'Computer Networking',
                    'ar' => 'الشبكات المعلوماتية',
                    'es' => 'Redes Informáticas',
                ],
                'description' => [
                    'en' => "Master networking fundamentals, equipment configuration, network security and prepare for the Cisco CCNA certification.",
                    'ar' => "أتقن أساسيات الشبكات وتكوين المعدات وأمن الشبكات، واستعدّ لشهادة Cisco CCNA.",
                    'es' => "Domina los fundamentos de las redes, la configuración de equipos, la seguridad de redes y prepárate para la certificación Cisco CCNA.",
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
