<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\CompanyCategory;
use App\Models\ContractType;
use App\Models\Currency;
use App\Models\Domain;
use App\Models\ProficiencyLevel;
use App\Models\Sector;
use App\Models\ServiceCategory;
use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

/**
 * Seeds the polymorphic `translations` table for all reference data.
 *
 * Strategy:
 *   - FR is always written from the existing model column (canonical source).
 *   - EN, ES, AR are written from the dictionaries below when known.
 *   - Unknown values leave the row absent — the HasTranslations trait then
 *     falls back to FR (and then to the raw column) automatically.
 *
 * Requirements: the reference data must already exist in the DB. In particular:
 *   - DomainsAndSectorsSeeder must run before this seeder (creates Domain/Sector).
 *   - ProficiencyLevelsSeeder must run before this seeder.
 *   - The legacy seeders (CategorySeeder, ContractTypeSeeder, CurrencySeeder,
 *     ServiceCategorySeeder, SubscriptionPlanSeeder, JobSeekerSubscriptionPlanSeeder)
 *     are also expected to have run.
 *
 * Idempotent: safe to re-run; uses updateOrCreate via HasTranslations::setTranslation.
 */
class TranslationsSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedCategories();
        $this->seedContractTypes();
        $this->seedServiceCategories();
        $this->seedDomains();
        $this->seedSectorsFull();
        $this->seedProficiencyLevelsTranslations();
        $this->seedCurrenciesCommon();
        $this->seedCurrenciesAll();
        $this->seedSubscriptionPlans();
        $this->seedSubscriptionPlansFull();
        // CompanyCategory translations are handled by CompanyCategoriesTranslationsSeeder
        // which consumes database/seeders/data/company_categories_level{1,2,3}.php
    }

    /* ------------------------------------------------------------------ */
    /* CATEGORIES                                                          */
    /* ------------------------------------------------------------------ */
    private function seedCategories(): void
    {
        $map = [
            'Informatique & Tech' => [
                'en' => 'IT & Tech',
                'es' => 'Informática y Tecnología',
                'ar' => 'تكنولوجيا المعلومات',
            ],
            'Marketing & Communication' => [
                'en' => 'Marketing & Communication',
                'es' => 'Marketing y Comunicación',
                'ar' => 'التسويق والاتصال',
            ],
            'Ressources Humaines' => [
                'en' => 'Human Resources',
                'es' => 'Recursos Humanos',
                'ar' => 'الموارد البشرية',
            ],
            'Finance & Comptabilité' => [
                'en' => 'Finance & Accounting',
                'es' => 'Finanzas y Contabilidad',
                'ar' => 'المالية والمحاسبة',
            ],
            'Commerce & Vente' => [
                'en' => 'Sales & Trade',
                'es' => 'Comercio y Ventas',
                'ar' => 'التجارة والمبيعات',
            ],
            'Éducation & Formation' => [
                'en' => 'Education & Training',
                'es' => 'Educación y Formación',
                'ar' => 'التعليم والتدريب',
            ],
            'Santé & Médical' => [
                'en' => 'Health & Medical',
                'es' => 'Salud y Medicina',
                'ar' => 'الصحة والطب',
            ],
            'Ingénierie & Architecture' => [
                'en' => 'Engineering & Architecture',
                'es' => 'Ingeniería y Arquitectura',
                'ar' => 'الهندسة والعمارة',
            ],
            'Hôtellerie & Restauration' => [
                'en' => 'Hospitality & Catering',
                'es' => 'Hostelería y Restauración',
                'ar' => 'الفندقة والمطاعم',
            ],
            'Transport & Logistique' => [
                'en' => 'Transport & Logistics',
                'es' => 'Transporte y Logística',
                'ar' => 'النقل واللوجستيات',
            ],
            'Agriculture & Agro-industrie' => [
                'en' => 'Agriculture & Agribusiness',
                'es' => 'Agricultura y Agroindustria',
                'ar' => 'الزراعة والصناعات الزراعية',
            ],
            'Banque & Assurance' => [
                'en' => 'Banking & Insurance',
                'es' => 'Banca y Seguros',
                'ar' => 'البنوك والتأمين',
            ],
            'Juridique & Droit' => [
                'en' => 'Legal & Law',
                'es' => 'Jurídico y Derecho',
                'ar' => 'القانون',
            ],
            'Design & Créatif' => [
                'en' => 'Design & Creative',
                'es' => 'Diseño y Creativo',
                'ar' => 'التصميم والإبداع',
            ],
            'Service Client' => [
                'en' => 'Customer Service',
                'es' => 'Servicio al Cliente',
                'ar' => 'خدمة العملاء',
            ],
            // -------- Catégories supplémentaires (ajoutées 2026-05-23) --------
            'Comptabilité & Finance' => [
                'en' => 'Accounting & Finance',
                'es' => 'Contabilidad y Finanzas',
                'ar' => 'المحاسبة والمالية',
            ],
            'Banque & Finance' => [
                'en' => 'Banking & Finance',
                'es' => 'Banca y Finanzas',
                'ar' => 'البنوك والمالية',
            ],
            'Commerce & International' => [
                'en' => 'International Trade',
                'es' => 'Comercio Internacional',
                'ar' => 'التجارة الدولية',
            ],
            'Marketing & Commerce' => [
                'en' => 'Marketing & Trade',
                'es' => 'Marketing y Comercio',
                'ar' => 'التسويق والتجارة',
            ],
            'Administration & Management' => [
                'en' => 'Administration & Management',
                'es' => 'Administración y Gestión',
                'ar' => 'الإدارة والتسيير',
            ],
            'Tourisme & Hôtellerie' => [
                'en' => 'Tourism & Hospitality',
                'es' => 'Turismo y Hostelería',
                'ar' => 'السياحة والفندقة',
            ],
            'Communication & Médias' => [
                'en' => 'Communication & Media',
                'es' => 'Comunicación y Medios',
                'ar' => 'الاتصال والإعلام',
            ],
            'Administration Publique' => [
                'en' => 'Public Administration',
                'es' => 'Administración Pública',
                'ar' => 'الإدارة العامة',
            ],
            'Qualité & HSE' => [
                'en' => 'Quality & HSE',
                'es' => 'Calidad y HSE',
                'ar' => 'الجودة والصحة والسلامة والبيئة',
            ],
            'Industrie & Technique' => [
                'en' => 'Industry & Technical',
                'es' => 'Industria y Técnica',
                'ar' => 'الصناعة والتقنية',
            ],
            'Électrotechnique & Énergie' => [
                'en' => 'Electrical Engineering & Energy',
                'es' => 'Electrotecnia y Energía',
                'ar' => 'الهندسة الكهربائية والطاقة',
            ],
            'Énergies Renouvelables' => [
                'en' => 'Renewable Energy',
                'es' => 'Energías Renovables',
                'ar' => 'الطاقات المتجددة',
            ],
            'Douane & Logistique' => [
                'en' => 'Customs & Logistics',
                'es' => 'Aduana y Logística',
                'ar' => 'الجمارك واللوجستيك',
            ],
            'Gestion de Projet' => [
                'en' => 'Project Management',
                'es' => 'Gestión de Proyectos',
                'ar' => 'إدارة المشاريع',
            ],
            'Communication' => [
                'en' => 'Communication',
                'es' => 'Comunicación',
                'ar' => 'الاتصال',
            ],
            'Journalisme & Médias' => [
                'en' => 'Journalism & Media',
                'es' => 'Periodismo y Medios',
                'ar' => 'الصحافة والإعلام',
            ],
            'Marketing & Vente' => [
                'en' => 'Marketing & Sales',
                'es' => 'Marketing y Ventas',
                'ar' => 'التسويق والمبيعات',
            ],
            'Industrie textile' => [
                'en' => 'Textile Industry',
                'es' => 'Industria textil',
                'ar' => 'صناعة النسيج',
            ],
            'BTP' => [
                'en' => 'Construction (BTP)',
                'es' => 'Construcción (BTP)',
                'ar' => 'البناء والأشغال العمومية',
            ],
            'Industrie & Maintenance' => [
                'en' => 'Industry & Maintenance',
                'es' => 'Industria y Mantenimiento',
                'ar' => 'الصناعة والصيانة',
            ],
            'Froid & Climatisation' => [
                'en' => 'Refrigeration & Air Conditioning',
                'es' => 'Refrigeración y Climatización',
                'ar' => 'التبريد والتكييف',
            ],
            'Santé & Petite Enfance' => [
                'en' => 'Health & Early Childhood',
                'es' => 'Salud y Primera Infancia',
                'ar' => 'الصحة والطفولة المبكرة',
            ],
            'Topographie & Géomatique' => [
                'en' => 'Topography & Geomatics',
                'es' => 'Topografía y Geomática',
                'ar' => 'المساحة والجيوماتيك',
            ],
            'Plomberie Sanitaire' => [
                'en' => 'Sanitary Plumbing',
                'es' => 'Fontanería Sanitaria',
                'ar' => 'السباكة الصحية',
            ],
            'Chaudronnerie & Soudure' => [
                'en' => 'Boilermaking & Welding',
                'es' => 'Calderería y Soldadura',
                'ar' => 'الحدادة واللحام',
            ],
            'Informatique & Réseaux' => [
                'en' => 'IT & Networks',
                'es' => 'Informática y Redes',
                'ar' => 'تكنولوجيا المعلومات والشبكات',
            ],
            'Électrotechnique' => [
                'en' => 'Electrical Engineering',
                'es' => 'Electrotecnia',
                'ar' => 'الهندسة الكهربائية',
            ],
            'Maintenance Industrielle' => [
                'en' => 'Industrial Maintenance',
                'es' => 'Mantenimiento Industrial',
                'ar' => 'الصيانة الصناعية',
            ],
            'Tronc Commun Commerce BTS' => [
                'en' => 'BTS Business Core Curriculum',
                'es' => 'Tronco Común Comercio BTS',
                'ar' => 'المنهاج المشترك للتجارة (BTS)',
            ],
        ];

        $descriptions = [
            'fr' => "Offres d'emploi dans le secteur :sector",
            'en' => 'Job offers in the :sector sector',
            'es' => 'Ofertas de empleo en el sector :sector',
            'ar' => 'عروض العمل في قطاع :sector',
        ];

        foreach (Category::all() as $category) {
            $frName = $category->name;

            $category->setTranslation('name', 'fr', $frName);
            if (isset($map[$frName])) {
                foreach ($map[$frName] as $locale => $value) {
                    $category->setTranslation('name', $locale, $value);
                }
            }

            foreach (['fr', 'en', 'es', 'ar'] as $locale) {
                $sectorName = $locale === 'fr'
                    ? $frName
                    : ($map[$frName][$locale] ?? $frName);
                $category->setTranslation(
                    'description',
                    $locale,
                    str_replace(':sector', $sectorName, $descriptions[$locale])
                );
            }
        }
    }

    /* ------------------------------------------------------------------ */
    /* CONTRACT TYPES                                                      */
    /* ------------------------------------------------------------------ */
    private function seedContractTypes(): void
    {
        $map = [
            'CDI (Contrat à Durée Indéterminée)' => [
                'en' => 'Permanent contract',
                'es' => 'Contrato indefinido',
                'ar' => 'عقد دائم',
            ],
            'CDI' => [
                'en' => 'Permanent contract (CDI)',
                'es' => 'Contrato indefinido (CDI)',
                'ar' => 'عقد دائم (CDI)',
            ],
            'CDD (Contrat à Durée Déterminée)' => [
                'en' => 'Fixed-term contract',
                'es' => 'Contrato de duración determinada',
                'ar' => 'عقد محدد المدة',
            ],
            'CDD' => [
                'en' => 'Fixed-term contract (CDD)',
                'es' => 'Contrato de duración determinada (CDD)',
                'ar' => 'عقد محدد المدة (CDD)',
            ],
            'Stage' => [
                'en' => 'Internship',
                'es' => 'Prácticas',
                'ar' => 'تدريب',
            ],
            'Freelance' => [
                'en' => 'Freelance',
                'es' => 'Autónomo',
                'ar' => 'عمل حر',
            ],
            'Temps Partiel' => [
                'en' => 'Part-time',
                'es' => 'Tiempo parcial',
                'ar' => 'دوام جزئي',
            ],
            'Intérim' => [
                'en' => 'Temporary work',
                'es' => 'Trabajo temporal',
                'ar' => 'عمل مؤقت',
            ],
            'Alternance' => [
                'en' => 'Work-study',
                'es' => 'Alternancia',
                'ar' => 'تدريب بالتناوب',
            ],
            'Contrat de Projet' => [
                'en' => 'Project contract',
                'es' => 'Contrato por proyecto',
                'ar' => 'عقد مشروع',
            ],
            // -------- Contrats supplémentaires (formes longues en DB, ajoutées 2026-05-23) --------
            'Contrat à Durée Déterminée (CDD)' => [
                'en' => 'Fixed-Term Contract (CDD)',
                'es' => 'Contrato de Duración Determinada (CDD)',
                'ar' => 'عقد محدد المدة (CDD)',
            ],
            // NB: le nom en DB contient un typo "Durrée" (deux R) — on garde la
            // clé exacte sinon le mapping ne matche pas.
            'Contrat à Durrée Indéterminé (CDI)' => [
                'en' => 'Permanent Contract (CDI)',
                'es' => 'Contrato Indefinido (CDI)',
                'ar' => 'عقد غير محدد المدة (CDI)',
            ],
        ];

        foreach (ContractType::all() as $type) {
            $fr = $type->name;
            $type->setTranslation('name', 'fr', $fr);
            if (isset($map[$fr])) {
                foreach ($map[$fr] as $locale => $value) {
                    $type->setTranslation('name', $locale, $value);
                }
            }
        }
    }

    /* ------------------------------------------------------------------ */
    /* SERVICE CATEGORIES                                                  */
    /* ------------------------------------------------------------------ */
    private function seedServiceCategories(): void
    {
        $names = [
            'Plomberie' => ['en' => 'Plumbing', 'es' => 'Fontanería', 'ar' => 'سباكة'],
            'Électricité' => ['en' => 'Electricity', 'es' => 'Electricidad', 'ar' => 'كهرباء'],
            'Ménage' => ['en' => 'Cleaning', 'es' => 'Limpieza', 'ar' => 'تنظيف'],
            'Déménagement' => ['en' => 'Moving', 'es' => 'Mudanza', 'ar' => 'نقل أثاث'],
            'Jardinage' => ['en' => 'Gardening', 'es' => 'Jardinería', 'ar' => 'بستنة'],
            'Réparation' => ['en' => 'Repair', 'es' => 'Reparación', 'ar' => 'إصلاح'],
            'Informatique' => ['en' => 'IT support', 'es' => 'Informática', 'ar' => 'دعم تقني'],
            'Peinture' => ['en' => 'Painting', 'es' => 'Pintura', 'ar' => 'دهان'],
            'Cours particuliers' => ['en' => 'Tutoring', 'es' => 'Clases particulares', 'ar' => 'دروس خصوصية'],
            'Livraison' => ['en' => 'Delivery', 'es' => 'Entrega', 'ar' => 'توصيل'],
            'Coiffure' => ['en' => 'Hairdressing', 'es' => 'Peluquería', 'ar' => 'تصفيف الشعر'],
            'Mécanique' => ['en' => 'Mechanics', 'es' => 'Mecánica', 'ar' => 'ميكانيكا'],
            'Couture' => ['en' => 'Sewing', 'es' => 'Costura', 'ar' => 'خياطة'],
            "Garde d'enfants" => ['en' => 'Childcare', 'es' => 'Cuidado de niños', 'ar' => 'رعاية الأطفال'],
            'Cuisine' => ['en' => 'Cooking', 'es' => 'Cocina', 'ar' => 'طبخ'],
        ];

        $descriptions = [
            'Plomberie' => [
                'en' => 'Leak repair, sanitary installation, unclogging',
                'es' => 'Reparación de fugas, instalación sanitaria, desatasco',
                'ar' => 'إصلاح تسربات وتركيب صحي وفك انسداد',
            ],
            'Électricité' => [
                'en' => 'Electrical installation, repair, troubleshooting',
                'es' => 'Instalación eléctrica, reparación, asistencia',
                'ar' => 'تركيب وإصلاح وصيانة كهربائية',
            ],
            'Ménage' => [
                'en' => 'Home cleaning, office cleaning, window cleaning',
                'es' => 'Limpieza de hogar, oficina y cristales',
                'ar' => 'تنظيف منزل ومكتب وزجاج',
            ],
            'Déménagement' => [
                'en' => 'Furniture transport, moving help',
                'es' => 'Transporte de muebles, ayuda en mudanzas',
                'ar' => 'نقل أثاث ومساعدة في الترحيل',
            ],
            'Jardinage' => [
                'en' => 'Garden maintenance, lawn mowing, hedge trimming',
                'es' => 'Mantenimiento de jardín, corte de césped, poda',
                'ar' => 'صيانة الحديقة وقص العشب وتقليم',
            ],
            'Réparation' => [
                'en' => 'Furniture, door and window repair',
                'es' => 'Reparación de muebles, puertas, ventanas',
                'ar' => 'إصلاح أثاث وأبواب ونوافذ',
            ],
            'Informatique' => [
                'en' => 'PC troubleshooting, software setup, networking',
                'es' => 'Asistencia PC, instalación de software, red',
                'ar' => 'إصلاح حواسيب وتثبيت برامج وشبكات',
            ],
            'Peinture' => [
                'en' => 'Indoor and outdoor painting, decoration',
                'es' => 'Pintura interior y exterior, decoración',
                'ar' => 'دهان داخلي وخارجي وديكور',
            ],
            'Cours particuliers' => [
                'en' => 'Maths, French, English and other tutoring',
                'es' => 'Clases de matemáticas, francés, inglés, etc.',
                'ar' => 'دروس رياضيات وفرنسية وإنجليزية',
            ],
            'Livraison' => [
                'en' => 'Parcel, shopping, and document delivery',
                'es' => 'Entrega de paquetes, compras, documentos',
                'ar' => 'توصيل طرود ومشتريات ووثائق',
            ],
            'Coiffure' => [
                'en' => 'Home hairdressing, braids, haircuts',
                'es' => 'Peluquería a domicilio, trenzas, corte',
                'ar' => 'تصفيف الشعر في المنزل وضفائر وقص',
            ],
            'Mécanique' => [
                'en' => 'Car, motorcycle repair and diagnostics',
                'es' => 'Reparación de coche, moto y diagnóstico',
                'ar' => 'إصلاح سيارات ودراجات وتشخيص',
            ],
            'Couture' => [
                'en' => 'Alterations, garment making',
                'es' => 'Retoques, confección de ropa',
                'ar' => 'تعديلات وتفصيل ملابس',
            ],
            "Garde d'enfants" => [
                'en' => 'Babysitting, occasional childcare',
                'es' => 'Cuidado de niños, niñera ocasional',
                'ar' => 'مجالسة الأطفال ورعاية مؤقتة',
            ],
            'Cuisine' => [
                'en' => 'Home chef, meal prep, catering',
                'es' => 'Chef a domicilio, comidas, catering',
                'ar' => 'طاهٍ منزلي وتحضير وجبات وتموين',
            ],
        ];

        foreach (ServiceCategory::all() as $service) {
            $fr = $service->name;
            $service->setTranslation('name', 'fr', $fr);
            $service->setTranslation('description', 'fr', $service->description);

            if (isset($names[$fr])) {
                foreach ($names[$fr] as $locale => $value) {
                    $service->setTranslation('name', $locale, $value);
                }
            }
            if (isset($descriptions[$fr])) {
                foreach ($descriptions[$fr] as $locale => $value) {
                    $service->setTranslation('description', $locale, $value);
                }
            }
        }
    }

    /* ------------------------------------------------------------------ */
    /* DOMAINS (translations only — creation is in DomainsAndSectorsSeeder) */
    /* ------------------------------------------------------------------ */
    private function seedDomains(): void
    {
        $domainNames = [
            'Technologie & Digital' => ['en' => 'Technology & Digital', 'es' => 'Tecnología y Digital', 'ar' => 'التكنولوجيا والرقمية'],
            'Finance & Comptabilité' => ['en' => 'Finance & Accounting', 'es' => 'Finanzas y Contabilidad', 'ar' => 'المالية والمحاسبة'],
            'Marketing & Communication' => ['en' => 'Marketing & Communication', 'es' => 'Marketing y Comunicación', 'ar' => 'التسويق والاتصال'],
            'Commerce & Vente' => ['en' => 'Sales & Trade', 'es' => 'Comercio y Ventas', 'ar' => 'التجارة والمبيعات'],
            'Industrie & Production' => ['en' => 'Industry & Production', 'es' => 'Industria y Producción', 'ar' => 'الصناعة والإنتاج'],
            'Santé & Sciences' => ['en' => 'Health & Sciences', 'es' => 'Salud y Ciencias', 'ar' => 'الصحة والعلوم'],
            'Éducation & Formation' => ['en' => 'Education & Training', 'es' => 'Educación y Formación', 'ar' => 'التعليم والتدريب'],
            'Transport & Logistique' => ['en' => 'Transport & Logistics', 'es' => 'Transporte y Logística', 'ar' => 'النقل واللوجستيات'],
            'Hôtellerie & Restauration' => ['en' => 'Hospitality & Catering', 'es' => 'Hostelería y Restauración', 'ar' => 'الفندقة والمطاعم'],
            'Construction & BTP' => ['en' => 'Construction & Civil works', 'es' => 'Construcción y Obras', 'ar' => 'البناء والأشغال'],
            'Agriculture & Environnement' => ['en' => 'Agriculture & Environment', 'es' => 'Agricultura y Medio Ambiente', 'ar' => 'الزراعة والبيئة'],
            'Services Juridiques' => ['en' => 'Legal services', 'es' => 'Servicios jurídicos', 'ar' => 'الخدمات القانونية'],
            'Arts & Culture' => ['en' => 'Arts & Culture', 'es' => 'Artes y Cultura', 'ar' => 'الفنون والثقافة'],
            'Services à la Personne' => ['en' => 'Personal services', 'es' => 'Servicios personales', 'ar' => 'خدمات شخصية'],
            'Médias & Presse' => ['en' => 'Media & Press', 'es' => 'Medios y Prensa', 'ar' => 'الإعلام والصحافة'],
            'Autre' => ['en' => 'Other', 'es' => 'Otro', 'ar' => 'أخرى'],
        ];

        foreach (Domain::all() as $domain) {
            $fr = $domain->name;
            $domain->setTranslation('name', 'fr', $fr);
            if (isset($domainNames[$fr])) {
                foreach ($domainNames[$fr] as $locale => $value) {
                    $domain->setTranslation('name', $locale, $value);
                }
            }
        }
    }

    /* ------------------------------------------------------------------ */
    /* PROFICIENCY LEVELS                                                  */
    /* ------------------------------------------------------------------ */
    private function seedProficiencyLevelsTranslations(): void
    {
        $map = [
            'Débutant'           => ['en' => 'Beginner',     'es' => 'Principiante',  'ar' => 'مبتدئ'],
            'Intermédiaire'      => ['en' => 'Intermediate', 'es' => 'Intermedio',    'ar' => 'متوسط'],
            'Avancé'             => ['en' => 'Advanced',     'es' => 'Avanzado',      'ar' => 'متقدم'],
            'Expert'             => ['en' => 'Expert',       'es' => 'Experto',       'ar' => 'خبير'],
            'Courant'            => ['en' => 'Fluent',       'es' => 'Fluido',        'ar' => 'طلق'],
            'Langue maternelle'  => ['en' => 'Native',       'es' => 'Lengua materna','ar' => 'لغة أم'],
        ];

        foreach (ProficiencyLevel::all() as $level) {
            $fr = $level->name;
            $level->setTranslation('name', 'fr', $fr);
            if (isset($map[$fr])) {
                foreach ($map[$fr] as $locale => $value) {
                    $level->setTranslation('name', $locale, $value);
                }
            }
        }
    }

    /* ------------------------------------------------------------------ */
    /* CURRENCIES — common ones get full translation; rest stays FR only.  */
    /* ------------------------------------------------------------------ */
    private function seedCurrenciesCommon(): void
    {
        $map = [
            'XAF' => ['fr' => 'Franc CFA (BEAC)',   'en' => 'CFA franc (BEAC)',  'es' => 'Franco CFA (BEAC)',   'ar' => 'فرنك أفريقي (BEAC)'],
            'XOF' => ['fr' => 'Franc CFA (BCEAO)',  'en' => 'CFA franc (BCEAO)', 'es' => 'Franco CFA (BCEAO)',  'ar' => 'فرنك أفريقي (BCEAO)'],
            'USD' => ['fr' => 'Dollar américain',   'en' => 'US Dollar',         'es' => 'Dólar estadounidense','ar' => 'دولار أمريكي'],
            'EUR' => ['fr' => 'Euro',               'en' => 'Euro',              'es' => 'Euro',                'ar' => 'يورو'],
            'GBP' => ['fr' => 'Livre sterling',     'en' => 'Pound sterling',    'es' => 'Libra esterlina',     'ar' => 'جنيه إسترليني'],
            'NGN' => ['fr' => 'Naira nigérian',     'en' => 'Nigerian Naira',    'es' => 'Naira nigeriano',     'ar' => 'نايرا نيجيري'],
            'GHS' => ['fr' => 'Cedi ghanéen',       'en' => 'Ghanaian Cedi',     'es' => 'Cedi ghanés',         'ar' => 'سيدي غاني'],
            'ZAR' => ['fr' => 'Rand sud-africain',  'en' => 'South African Rand','es' => 'Rand sudafricano',    'ar' => 'راند جنوب أفريقي'],
            'MAD' => ['fr' => 'Dirham marocain',    'en' => 'Moroccan Dirham',   'es' => 'Dírham marroquí',     'ar' => 'درهم مغربي'],
            'DZD' => ['fr' => 'Dinar algérien',     'en' => 'Algerian Dinar',    'es' => 'Dinar argelino',      'ar' => 'دينار جزائري'],
            'TND' => ['fr' => 'Dinar tunisien',     'en' => 'Tunisian Dinar',    'es' => 'Dinar tunecino',      'ar' => 'دينار تونسي'],
            'EGP' => ['fr' => 'Livre égyptienne',   'en' => 'Egyptian Pound',    'es' => 'Libra egipcia',       'ar' => 'جنيه مصري'],
            'SAR' => ['fr' => 'Riyal saoudien',     'en' => 'Saudi Riyal',       'es' => 'Riyal saudí',         'ar' => 'ريال سعودي'],
            'AED' => ['fr' => 'Dirham émirati',     'en' => 'UAE Dirham',        'es' => 'Dírham emiratí',      'ar' => 'درهم إماراتي'],
            'CAD' => ['fr' => 'Dollar canadien',    'en' => 'Canadian Dollar',   'es' => 'Dólar canadiense',    'ar' => 'دولار كندي'],
            'CHF' => ['fr' => 'Franc suisse',       'en' => 'Swiss Franc',       'es' => 'Franco suizo',        'ar' => 'فرنك سويسري'],
            'CNY' => ['fr' => 'Yuan chinois',       'en' => 'Chinese Yuan',      'es' => 'Yuan chino',          'ar' => 'يوان صيني'],
            'JPY' => ['fr' => 'Yen japonais',       'en' => 'Japanese Yen',      'es' => 'Yen japonés',         'ar' => 'ين ياباني'],
            'INR' => ['fr' => 'Roupie indienne',    'en' => 'Indian Rupee',      'es' => 'Rupia india',         'ar' => 'روبية هندية'],
        ];

        foreach (Currency::all() as $currency) {
            $currency->setTranslation('name', 'fr', $currency->name);
            if (isset($map[$currency->code])) {
                foreach ($map[$currency->code] as $locale => $value) {
                    $currency->setTranslation('name', $locale, $value);
                }
            }
        }
    }

    /* ------------------------------------------------------------------ */
    /* SUBSCRIPTION PLANS                                                  */
    /* ------------------------------------------------------------------ */
    private function seedSubscriptionPlans(): void
    {
        $names = [
            'ARGENT'   => ['en' => 'SILVER',   'es' => 'PLATA',     'ar' => 'فضي'],
            'OR'       => ['en' => 'GOLD',     'es' => 'ORO',       'ar' => 'ذهبي'],
            'PLATINUM' => ['en' => 'PLATINUM', 'es' => 'PLATINO',   'ar' => 'بلاتيني'],
            'SILVER'   => ['en' => 'SILVER',   'es' => 'PLATA',     'ar' => 'فضي'],
            'GOLD'     => ['en' => 'GOLD',     'es' => 'ORO',       'ar' => 'ذهبي'],
        ];

        foreach (SubscriptionPlan::all() as $plan) {
            $fr = $plan->name;
            $plan->setTranslation('name', 'fr', $fr);
            $plan->setTranslation('description', 'fr', $plan->description);

            $key = strtoupper(trim($fr));
            if (isset($names[$key])) {
                foreach ($names[$key] as $locale => $value) {
                    $plan->setTranslation('name', $locale, $value);
                }
            }
        }
    }

    /* ------------------------------------------------------------------ */
    /* SECTORS — full translation of the 122 entries from domains_sectors  */
    /* ------------------------------------------------------------------ */
    private function seedSectorsFull(): void
    {
        $map = [
            // Technologie & Digital
            'Développement Web' => ['en' => 'Web development', 'es' => 'Desarrollo web', 'ar' => 'تطوير الويب'],
            'Développement Mobile' => ['en' => 'Mobile development', 'es' => 'Desarrollo móvil', 'ar' => 'تطوير الجوال'],
            'IA & Data Science' => ['en' => 'AI & Data Science', 'es' => 'IA y Ciencia de Datos', 'ar' => 'الذكاء الاصطناعي وعلم البيانات'],
            'Cybersécurité' => ['en' => 'Cybersecurity', 'es' => 'Ciberseguridad', 'ar' => 'الأمن السيبراني'],
            'Cloud Computing' => ['en' => 'Cloud Computing', 'es' => 'Computación en la nube', 'ar' => 'الحوسبة السحابية'],
            'DevOps' => ['en' => 'DevOps', 'es' => 'DevOps', 'ar' => 'ديف أوبس'],
            'UI/UX Design' => ['en' => 'UI/UX Design', 'es' => 'Diseño UI/UX', 'ar' => 'تصميم واجهة المستخدم'],
            'Support Informatique' => ['en' => 'IT Support', 'es' => 'Soporte informático', 'ar' => 'الدعم التقني'],
            'Réseaux & Télécommunications' => ['en' => 'Networks & Telecommunications', 'es' => 'Redes y Telecomunicaciones', 'ar' => 'الشبكات والاتصالات'],

            // Finance & Comptabilité
            'Comptabilité' => ['en' => 'Accounting', 'es' => 'Contabilidad', 'ar' => 'المحاسبة'],
            'Audit' => ['en' => 'Audit', 'es' => 'Auditoría', 'ar' => 'تدقيق'],
            'Gestion Financière' => ['en' => 'Financial Management', 'es' => 'Gestión Financiera', 'ar' => 'الإدارة المالية'],
            'Banque' => ['en' => 'Banking', 'es' => 'Banca', 'ar' => 'مصرفية'],
            'Assurance' => ['en' => 'Insurance', 'es' => 'Seguros', 'ar' => 'تأمين'],
            'Contrôle de Gestion' => ['en' => 'Management Control', 'es' => 'Control de Gestión', 'ar' => 'مراقبة التسيير'],
            'Fiscalité' => ['en' => 'Taxation', 'es' => 'Fiscalidad', 'ar' => 'الضرائب'],
            'Analyse Financière' => ['en' => 'Financial Analysis', 'es' => 'Análisis Financiero', 'ar' => 'التحليل المالي'],

            // Marketing & Communication
            'Marketing Digital' => ['en' => 'Digital Marketing', 'es' => 'Marketing Digital', 'ar' => 'التسويق الرقمي'],
            "Communication d'Entreprise" => ['en' => 'Corporate Communication', 'es' => 'Comunicación Corporativa', 'ar' => 'الاتصال المؤسسي'],
            'Relations Publiques' => ['en' => 'Public Relations', 'es' => 'Relaciones Públicas', 'ar' => 'العلاقات العامة'],
            'Publicité' => ['en' => 'Advertising', 'es' => 'Publicidad', 'ar' => 'الإعلانات'],
            'Community Management' => ['en' => 'Community Management', 'es' => 'Community Management', 'ar' => 'إدارة المجتمعات'],
            'Content Marketing' => ['en' => 'Content Marketing', 'es' => 'Marketing de Contenidos', 'ar' => 'تسويق المحتوى'],
            'SEO/SEM' => ['en' => 'SEO/SEM', 'es' => 'SEO/SEM', 'ar' => 'تحسين محركات البحث'],
            'Brand Management' => ['en' => 'Brand Management', 'es' => 'Gestión de Marca', 'ar' => 'إدارة العلامة التجارية'],

            // Commerce & Vente
            'Vente au Détail' => ['en' => 'Retail Sales', 'es' => 'Venta al Por Menor', 'ar' => 'البيع بالتجزئة'],
            'Vente B2B' => ['en' => 'B2B Sales', 'es' => 'Ventas B2B', 'ar' => 'مبيعات بين الشركات'],
            'Vente B2C' => ['en' => 'B2C Sales', 'es' => 'Ventas B2C', 'ar' => 'مبيعات للمستهلكين'],
            'E-commerce' => ['en' => 'E-commerce', 'es' => 'Comercio electrónico', 'ar' => 'التجارة الإلكترونية'],
            'Distribution' => ['en' => 'Distribution', 'es' => 'Distribución', 'ar' => 'التوزيع'],
            'Import/Export' => ['en' => 'Import/Export', 'es' => 'Importación/Exportación', 'ar' => 'استيراد وتصدير'],
            'Gestion Commerciale' => ['en' => 'Commercial Management', 'es' => 'Gestión Comercial', 'ar' => 'الإدارة التجارية'],
            'Développement Commercial' => ['en' => 'Business Development', 'es' => 'Desarrollo Comercial', 'ar' => 'تطوير الأعمال'],

            // Industrie & Production
            'Manufacture' => ['en' => 'Manufacturing', 'es' => 'Manufactura', 'ar' => 'التصنيع'],
            'Agroalimentaire' => ['en' => 'Food industry', 'es' => 'Agroalimentaria', 'ar' => 'الصناعات الغذائية'],
            'Textile' => ['en' => 'Textile', 'es' => 'Textil', 'ar' => 'النسيج'],
            'Métallurgie' => ['en' => 'Metallurgy', 'es' => 'Metalurgia', 'ar' => 'علم المعادن'],
            'Chimie' => ['en' => 'Chemistry', 'es' => 'Química', 'ar' => 'الكيمياء'],
            'Énergie' => ['en' => 'Energy', 'es' => 'Energía', 'ar' => 'الطاقة'],
            'Production Industrielle' => ['en' => 'Industrial Production', 'es' => 'Producción Industrial', 'ar' => 'الإنتاج الصناعي'],
            'Maintenance Industrielle' => ['en' => 'Industrial Maintenance', 'es' => 'Mantenimiento Industrial', 'ar' => 'الصيانة الصناعية'],

            // Santé & Sciences
            'Médecine' => ['en' => 'Medicine', 'es' => 'Medicina', 'ar' => 'الطب'],
            'Pharmacie' => ['en' => 'Pharmacy', 'es' => 'Farmacia', 'ar' => 'الصيدلة'],
            'Soins Infirmiers' => ['en' => 'Nursing', 'es' => 'Enfermería', 'ar' => 'التمريض'],
            'Laboratoire' => ['en' => 'Laboratory', 'es' => 'Laboratorio', 'ar' => 'مختبر'],
            'Recherche Médicale' => ['en' => 'Medical Research', 'es' => 'Investigación Médica', 'ar' => 'البحث الطبي'],
            'Biotechnologie' => ['en' => 'Biotechnology', 'es' => 'Biotecnología', 'ar' => 'التكنولوجيا الحيوية'],
            'Santé Publique' => ['en' => 'Public Health', 'es' => 'Salud Pública', 'ar' => 'الصحة العامة'],
            'Dentisterie' => ['en' => 'Dentistry', 'es' => 'Odontología', 'ar' => 'طب الأسنان'],

            // Éducation & Formation
            'Enseignement Primaire' => ['en' => 'Primary Education', 'es' => 'Educación Primaria', 'ar' => 'التعليم الابتدائي'],
            'Enseignement Secondaire' => ['en' => 'Secondary Education', 'es' => 'Educación Secundaria', 'ar' => 'التعليم الثانوي'],
            'Enseignement Supérieur' => ['en' => 'Higher Education', 'es' => 'Educación Superior', 'ar' => 'التعليم العالي'],
            'Formation Professionnelle' => ['en' => 'Vocational Training', 'es' => 'Formación Profesional', 'ar' => 'التكوين المهني'],
            'E-learning' => ['en' => 'E-learning', 'es' => 'E-learning', 'ar' => 'التعلم الإلكتروني'],
            'Recherche Académique' => ['en' => 'Academic Research', 'es' => 'Investigación Académica', 'ar' => 'البحث الأكاديمي'],
            'Administration Scolaire' => ['en' => 'School Administration', 'es' => 'Administración Escolar', 'ar' => 'الإدارة المدرسية'],

            // Transport & Logistique
            'Transport Routier' => ['en' => 'Road Transport', 'es' => 'Transporte por Carretera', 'ar' => 'النقل البري'],
            'Transport Maritime' => ['en' => 'Maritime Transport', 'es' => 'Transporte Marítimo', 'ar' => 'النقل البحري'],
            'Transport Aérien' => ['en' => 'Air Transport', 'es' => 'Transporte Aéreo', 'ar' => 'النقل الجوي'],
            'Livraison' => ['en' => 'Delivery', 'es' => 'Entrega', 'ar' => 'التوصيل'],
            'Supply Chain' => ['en' => 'Supply Chain', 'es' => 'Cadena de Suministro', 'ar' => 'سلسلة التوريد'],
            'Entreposage' => ['en' => 'Warehousing', 'es' => 'Almacenamiento', 'ar' => 'التخزين'],
            'Gestion de Flotte' => ['en' => 'Fleet Management', 'es' => 'Gestión de Flota', 'ar' => 'إدارة الأسطول'],
            'Fret' => ['en' => 'Freight', 'es' => 'Flete', 'ar' => 'الشحن'],

            // Hôtellerie & Restauration
            'Hôtellerie' => ['en' => 'Hotel industry', 'es' => 'Hostelería', 'ar' => 'الفندقة'],
            'Restauration' => ['en' => 'Catering', 'es' => 'Restauración', 'ar' => 'المطاعم'],
            'Tourisme' => ['en' => 'Tourism', 'es' => 'Turismo', 'ar' => 'السياحة'],
            'Événementiel' => ['en' => 'Events', 'es' => 'Eventos', 'ar' => 'الفعاليات'],
            'Traiteur' => ['en' => 'Caterer', 'es' => 'Catering', 'ar' => 'تموين'],
            'Services Hôteliers' => ['en' => 'Hotel Services', 'es' => 'Servicios Hoteleros', 'ar' => 'الخدمات الفندقية'],
            'Animation' => ['en' => 'Animation', 'es' => 'Animación', 'ar' => 'التنشيط'],

            // Construction & BTP
            'Bâtiment' => ['en' => 'Building', 'es' => 'Edificación', 'ar' => 'البناء'],
            'Travaux Publics' => ['en' => 'Public Works', 'es' => 'Obras Públicas', 'ar' => 'الأشغال العامة'],
            'Architecture' => ['en' => 'Architecture', 'es' => 'Arquitectura', 'ar' => 'العمارة'],
            'Génie Civil' => ['en' => 'Civil Engineering', 'es' => 'Ingeniería Civil', 'ar' => 'الهندسة المدنية'],
            'Plomberie' => ['en' => 'Plumbing', 'es' => 'Fontanería', 'ar' => 'السباكة'],
            'Électricité Bâtiment' => ['en' => 'Building Electrical', 'es' => 'Electricidad de Edificios', 'ar' => 'كهرباء البناء'],
            'Maçonnerie' => ['en' => 'Masonry', 'es' => 'Albañilería', 'ar' => 'البناء بالحجارة'],
            'Charpenterie' => ['en' => 'Carpentry', 'es' => 'Carpintería', 'ar' => 'النجارة'],

            // Agriculture & Environnement
            'Agriculture' => ['en' => 'Agriculture', 'es' => 'Agricultura', 'ar' => 'الزراعة'],
            'Élevage' => ['en' => 'Livestock', 'es' => 'Ganadería', 'ar' => 'تربية الماشية'],
            'Pêche' => ['en' => 'Fishing', 'es' => 'Pesca', 'ar' => 'صيد الأسماك'],
            'Foresterie' => ['en' => 'Forestry', 'es' => 'Silvicultura', 'ar' => 'الغابات'],
            'Environnement' => ['en' => 'Environment', 'es' => 'Medio Ambiente', 'ar' => 'البيئة'],
            'Développement Durable' => ['en' => 'Sustainable Development', 'es' => 'Desarrollo Sostenible', 'ar' => 'التنمية المستدامة'],
            'Énergies Renouvelables' => ['en' => 'Renewable Energy', 'es' => 'Energías Renovables', 'ar' => 'الطاقات المتجددة'],

            // Services Juridiques
            'Droit des Affaires' => ['en' => 'Business Law', 'es' => 'Derecho Mercantil', 'ar' => 'قانون الأعمال'],
            'Droit Civil' => ['en' => 'Civil Law', 'es' => 'Derecho Civil', 'ar' => 'القانون المدني'],
            'Droit Pénal' => ['en' => 'Criminal Law', 'es' => 'Derecho Penal', 'ar' => 'القانون الجنائي'],
            'Droit du Travail' => ['en' => 'Labor Law', 'es' => 'Derecho Laboral', 'ar' => 'قانون العمل'],
            'Notariat' => ['en' => 'Notary services', 'es' => 'Notaría', 'ar' => 'التوثيق'],
            'Conseil Juridique' => ['en' => 'Legal Advice', 'es' => 'Asesoría Jurídica', 'ar' => 'الاستشارات القانونية'],
            'Médiation' => ['en' => 'Mediation', 'es' => 'Mediación', 'ar' => 'الوساطة'],

            // Arts & Culture
            'Arts Plastiques' => ['en' => 'Visual Arts', 'es' => 'Artes Plásticas', 'ar' => 'الفنون التشكيلية'],
            'Musique' => ['en' => 'Music', 'es' => 'Música', 'ar' => 'الموسيقى'],
            'Cinéma' => ['en' => 'Cinema', 'es' => 'Cine', 'ar' => 'السينما'],
            'Théâtre' => ['en' => 'Theatre', 'es' => 'Teatro', 'ar' => 'المسرح'],
            'Danse' => ['en' => 'Dance', 'es' => 'Danza', 'ar' => 'الرقص'],
            'Patrimoine' => ['en' => 'Heritage', 'es' => 'Patrimonio', 'ar' => 'التراث'],
            'Muséologie' => ['en' => 'Museology', 'es' => 'Museología', 'ar' => 'علم المتاحف'],
            'Édition' => ['en' => 'Publishing', 'es' => 'Edición', 'ar' => 'النشر'],

            // Services à la Personne
            'Aide à Domicile' => ['en' => 'Home Help', 'es' => 'Ayuda a Domicilio', 'ar' => 'المساعدة المنزلية'],
            "Garde d'enfants" => ['en' => 'Childcare', 'es' => 'Cuidado de Niños', 'ar' => 'رعاية الأطفال'],
            'Coiffure & Esthétique' => ['en' => 'Hairdressing & Beauty', 'es' => 'Peluquería y Estética', 'ar' => 'تصفيف الشعر والتجميل'],
            'Bien-être & Spa' => ['en' => 'Wellness & Spa', 'es' => 'Bienestar y Spa', 'ar' => 'الصحة والسبا'],
            'Sport & Fitness' => ['en' => 'Sport & Fitness', 'es' => 'Deporte y Fitness', 'ar' => 'الرياضة واللياقة'],
            'Conciergerie' => ['en' => 'Concierge', 'es' => 'Conserjería', 'ar' => 'خدمات الكونسيرج'],
            'Pressing & Blanchisserie' => ['en' => 'Dry cleaning & Laundry', 'es' => 'Tintorería y Lavandería', 'ar' => 'التنظيف الجاف والمغسلة'],

            // Médias & Presse
            'Presse Écrite' => ['en' => 'Print Media', 'es' => 'Prensa Escrita', 'ar' => 'الصحافة المكتوبة'],
            'Radio' => ['en' => 'Radio', 'es' => 'Radio', 'ar' => 'الإذاعة'],
            'Télévision' => ['en' => 'Television', 'es' => 'Televisión', 'ar' => 'التلفزيون'],
            'Médias en Ligne' => ['en' => 'Online Media', 'es' => 'Medios Online', 'ar' => 'الإعلام الإلكتروني'],
            'Journalisme' => ['en' => 'Journalism', 'es' => 'Periodismo', 'ar' => 'الصحافة'],
            'Production Audiovisuelle' => ['en' => 'Audiovisual Production', 'es' => 'Producción Audiovisual', 'ar' => 'الإنتاج السمعي البصري'],
            'Photographie' => ['en' => 'Photography', 'es' => 'Fotografía', 'ar' => 'التصوير الفوتوغرافي'],

            // Autre
            'Autre Activité' => ['en' => 'Other Activity', 'es' => 'Otra Actividad', 'ar' => 'نشاط آخر'],
            'Non spécifié' => ['en' => 'Unspecified', 'es' => 'No especificado', 'ar' => 'غير محدد'],
            'Autre Secteur' => ['en' => 'Other Sector', 'es' => 'Otro Sector', 'ar' => 'قطاع آخر'],

            // Additional sectors (BTP / Construction)
            'Électricité' => ['en' => 'Electricity', 'es' => 'Electricidad', 'ar' => 'الكهرباء'],
            'Menuiserie' => ['en' => 'Carpentry', 'es' => 'Carpintería', 'ar' => 'النجارة'],
            'Gestion de Projets BTP' => ['en' => 'Construction Project Management', 'es' => 'Gestión de Proyectos BTP', 'ar' => 'إدارة مشاريع البناء'],

            // Additional sectors (Agriculture)
            'Agronomie' => ['en' => 'Agronomy', 'es' => 'Agronomía', 'ar' => 'علم الزراعة'],

            // Additional sectors (Legal)
            'Droit Social' => ['en' => 'Social Law', 'es' => 'Derecho Social', 'ar' => 'القانون الاجتماعي'],
            'Contentieux' => ['en' => 'Litigation', 'es' => 'Contencioso', 'ar' => 'التقاضي'],
            'Droit Fiscal' => ['en' => 'Tax Law', 'es' => 'Derecho Fiscal', 'ar' => 'القانون الضريبي'],

            // Additional sectors (Arts & Culture)
            'Arts Visuels' => ['en' => 'Visual Arts', 'es' => 'Artes Visuales', 'ar' => 'الفنون البصرية'],
            'Cinéma & Audiovisuel' => ['en' => 'Cinema & Audiovisual', 'es' => 'Cine y Audiovisual', 'ar' => 'السينما والسمعي البصري'],
            'Design' => ['en' => 'Design', 'es' => 'Diseño', 'ar' => 'التصميم'],
            'Animation Culturelle' => ['en' => 'Cultural Activities', 'es' => 'Animación Cultural', 'ar' => 'التنشيط الثقافي'],

            // Additional sectors (Personal Services)
            "Garde d'Enfants" => ['en' => 'Childcare', 'es' => 'Cuidado de Niños', 'ar' => 'رعاية الأطفال'],
            'Soins aux Personnes Âgées' => ['en' => 'Elderly Care', 'es' => 'Cuidado de Personas Mayores', 'ar' => 'رعاية المسنين'],
            'Nettoyage' => ['en' => 'Cleaning', 'es' => 'Limpieza', 'ar' => 'التنظيف'],
            'Sécurité' => ['en' => 'Security', 'es' => 'Seguridad', 'ar' => 'الأمن'],
            'Aide Sociale' => ['en' => 'Social Assistance', 'es' => 'Asistencia Social', 'ar' => 'المساعدة الاجتماعية'],

            // Additional sectors (Media)
            'Web Média' => ['en' => 'Web Media', 'es' => 'Medios Web', 'ar' => 'الإعلام الإلكتروني'],

            // Additional sectors (Cross-cutting)
            'Administration Générale' => ['en' => 'General Administration', 'es' => 'Administración General', 'ar' => 'الإدارة العامة'],
            'Ressources Humaines' => ['en' => 'Human Resources', 'es' => 'Recursos Humanos', 'ar' => 'الموارد البشرية'],
            'Conseil & Stratégie' => ['en' => 'Consulting & Strategy', 'es' => 'Consultoría y Estrategia', 'ar' => 'الاستشارات والاستراتيجية'],
            'Gestion de Projet' => ['en' => 'Project Management', 'es' => 'Gestión de Proyectos', 'ar' => 'إدارة المشاريع'],
            'Qualité' => ['en' => 'Quality', 'es' => 'Calidad', 'ar' => 'الجودة'],
        ];

        foreach (Sector::all() as $sector) {
            $fr = $sector->name;
            $sector->setTranslation('name', 'fr', $fr);
            if (isset($map[$fr])) {
                foreach ($map[$fr] as $locale => $value) {
                    $sector->setTranslation('name', $locale, $value);
                }
            }
        }
    }

    /* ------------------------------------------------------------------ */
    /* CURRENCIES — remaining currencies beyond the common set             */
    /* ------------------------------------------------------------------ */
    private function seedCurrenciesAll(): void
    {
        $map = [
            'KES' => ['en' => 'Kenyan Shilling',         'es' => 'Chelín keniano',          'ar' => 'شلن كيني'],
            'CDF' => ['en' => 'Congolese Franc',         'es' => 'Franco congoleño',        'ar' => 'فرنك كونغولي'],
            'RWF' => ['en' => 'Rwandan Franc',           'es' => 'Franco ruandés',          'ar' => 'فرنك رواندي'],
            'AUD' => ['en' => 'Australian Dollar',       'es' => 'Dólar australiano',       'ar' => 'دولار أسترالي'],
            'BRL' => ['en' => 'Brazilian Real',          'es' => 'Real brasileño',          'ar' => 'ريال برازيلي'],
            'RUB' => ['en' => 'Russian Ruble',           'es' => 'Rublo ruso',              'ar' => 'روبل روسي'],
            'TRY' => ['en' => 'Turkish Lira',            'es' => 'Lira turca',              'ar' => 'ليرة تركية'],
            'SGD' => ['en' => 'Singapore Dollar',        'es' => 'Dólar de Singapur',       'ar' => 'دولار سنغافوري'],
            'HKD' => ['en' => 'Hong Kong Dollar',        'es' => 'Dólar de Hong Kong',      'ar' => 'دولار هونغ كونغ'],
            'SEK' => ['en' => 'Swedish Krona',           'es' => 'Corona sueca',            'ar' => 'كرونة سويدية'],
            'NOK' => ['en' => 'Norwegian Krone',         'es' => 'Corona noruega',          'ar' => 'كرونة نرويجية'],
            'DKK' => ['en' => 'Danish Krone',            'es' => 'Corona danesa',           'ar' => 'كرونة دنماركية'],
            'PLN' => ['en' => 'Polish Zloty',            'es' => 'Zloty polaco',            'ar' => 'زلوتي بولندي'],
            'MXN' => ['en' => 'Mexican Peso',            'es' => 'Peso mexicano',           'ar' => 'بيزو مكسيكي'],
            'KRW' => ['en' => 'South Korean Won',        'es' => 'Won surcoreano',          'ar' => 'وون كوري جنوبي'],
            'IDR' => ['en' => 'Indonesian Rupiah',       'es' => 'Rupia indonesia',         'ar' => 'روبية إندونيسية'],
            'THB' => ['en' => 'Thai Baht',               'es' => 'Baht tailandés',          'ar' => 'بات تايلاندي'],
            'MYR' => ['en' => 'Malaysian Ringgit',       'es' => 'Ringgit malasio',         'ar' => 'رينغيت ماليزي'],
            'PHP' => ['en' => 'Philippine Peso',         'es' => 'Peso filipino',           'ar' => 'بيزو فلبيني'],
            'VND' => ['en' => 'Vietnamese Dong',         'es' => 'Dong vietnamita',         'ar' => 'دونغ فيتنامي'],
            'NZD' => ['en' => 'New Zealand Dollar',      'es' => 'Dólar neozelandés',       'ar' => 'دولار نيوزيلندي'],
            'ARS' => ['en' => 'Argentine Peso',          'es' => 'Peso argentino',          'ar' => 'بيزو أرجنتيني'],
            'CLP' => ['en' => 'Chilean Peso',            'es' => 'Peso chileno',            'ar' => 'بيزو تشيلي'],
            'COP' => ['en' => 'Colombian Peso',          'es' => 'Peso colombiano',         'ar' => 'بيزو كولومبي'],
            'ILS' => ['en' => 'Israeli Shekel',          'es' => 'Séquel israelí',          'ar' => 'شيكل إسرائيلي'],
            'QAR' => ['en' => 'Qatari Riyal',            'es' => 'Riyal catarí',            'ar' => 'ريال قطري'],
            'KWD' => ['en' => 'Kuwaiti Dinar',           'es' => 'Dinar kuwaití',           'ar' => 'دينار كويتي'],
            'XPF' => ['en' => 'CFP Franc',               'es' => 'Franco CFP',              'ar' => 'فرنك المحيط الهادئ'],
            'ANG' => ['en' => 'Netherlands Antillean Guilder', 'es' => 'Florín antillano',  'ar' => 'فلورين أنتيلي'],
            'MUR' => ['en' => 'Mauritian Rupee',         'es' => 'Rupia mauriciana',        'ar' => 'روبية موريشية'],
            'AOA' => ['en' => 'Angolan Kwanza',          'es' => 'Kwanza angoleño',         'ar' => 'كوانزا أنغولي'],
            'ETB' => ['en' => 'Ethiopian Birr',          'es' => 'Birr etíope',             'ar' => 'بير إثيوبي'],
            'GNF' => ['en' => 'Guinean Franc',           'es' => 'Franco guineano',         'ar' => 'فرنك غيني'],
            'TZS' => ['en' => 'Tanzanian Shilling',      'es' => 'Chelín tanzano',          'ar' => 'شلن تنزاني'],
            'UGX' => ['en' => 'Ugandan Shilling',        'es' => 'Chelín ugandés',          'ar' => 'شلن أوغندي'],
            'ZMW' => ['en' => 'Zambian Kwacha',          'es' => 'Kwacha zambiano',         'ar' => 'كواشا زامبي'],
            'BWP' => ['en' => 'Botswana Pula',           'es' => 'Pula botsuano',           'ar' => 'بولا بوتسواني'],
            'MZN' => ['en' => 'Mozambican Metical',      'es' => 'Metical mozambiqueño',    'ar' => 'متيكال موزمبيقي'],
            'MGA' => ['en' => 'Malagasy Ariary',         'es' => 'Ariary malgache',         'ar' => 'أرياري مدغشقري'],
        ];

        foreach (Currency::all() as $currency) {
            if (! isset($map[$currency->code])) {
                continue;
            }
            $currency->setTranslation('name', 'fr', $currency->name);
            foreach ($map[$currency->code] as $locale => $value) {
                $currency->setTranslation('name', $locale, $value);
            }
        }
    }

    /* ------------------------------------------------------------------ */
    /* SUBSCRIPTION PLANS — full names + descriptions                      */
    /* ------------------------------------------------------------------ */
    private function seedSubscriptionPlansFull(): void
    {
        // Heuristic translation of the actual seeded plan names.
        // Matches names like "PACK R1 ( ARGENT )" and "PACK C2 ( OR )" etc.
        $tierMap = [
            'ARGENT'  => ['en' => 'SILVER',   'es' => 'PLATA',    'ar' => 'فضي'],
            'OR'      => ['en' => 'GOLD',     'es' => 'ORO',      'ar' => 'ذهبي'],
            'DIAMANT' => ['en' => 'DIAMOND',  'es' => 'DIAMANTE', 'ar' => 'ماسي'],
            'PLATINUM'=> ['en' => 'PLATINUM', 'es' => 'PLATINO',  'ar' => 'بلاتيني'],
            'SILVER'  => ['en' => 'SILVER',   'es' => 'PLATA',    'ar' => 'فضي'],
            'GOLD'    => ['en' => 'GOLD',     'es' => 'ORO',      'ar' => 'ذهبي'],
            'BRONZE'  => ['en' => 'BRONZE',   'es' => 'BRONCE',   'ar' => 'برونزي'],
        ];

        $prefixMap = [
            'recruiter' => ['en' => 'PACK R', 'es' => 'PACK R', 'ar' => 'باقة R'],
            'job_seeker' => ['en' => 'PACK C', 'es' => 'PACK C', 'ar' => 'باقة C'],
        ];

        foreach (SubscriptionPlan::all() as $plan) {
            $fr = $plan->name;
            $plan->setTranslation('name', 'fr', $fr);

            // Try to extract tier from "PACK X# ( TIER )" pattern.
            if (preg_match('/\(\s*([A-ZÉÈÊËÀÂÄÎÏÔÖÙÛÜÇ]+)\s*\)/u', $fr, $m)) {
                $tier = strtoupper(trim($m[1]));
                $tier = strtr($tier, ['É'=>'E','È'=>'E','À'=>'A']);
                if (isset($tierMap[$tier])) {
                    foreach (['en', 'es', 'ar'] as $locale) {
                        $translated = preg_replace(
                            '/\(\s*[A-ZÉÈÊËÀÂÄÎÏÔÖÙÛÜÇ]+\s*\)/u',
                            '( '.$tierMap[$tier][$locale].' )',
                            $fr
                        );
                        $plan->setTranslation('name', $locale, $translated);
                    }
                }
            }

            // Translate "X FCFA/Mois" descriptions.
            $desc = $plan->description;
            if ($desc) {
                $plan->setTranslation('description', 'fr', $desc);

                $en = preg_replace('/FCFA\s*\/\s*Mois/iu', 'XAF/Month', $desc);
                $es = preg_replace('/FCFA\s*\/\s*Mois/iu', 'XAF/Mes', $desc);
                $ar = preg_replace('/FCFA\s*\/\s*Mois/iu', 'فرنك أفريقي/شهر', $desc);

                $plan->setTranslation('description', 'en', $en);
                $plan->setTranslation('description', 'es', $es);
                $plan->setTranslation('description', 'ar', $ar);
            }
        }
    }
}
