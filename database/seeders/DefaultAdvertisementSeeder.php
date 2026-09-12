<?php

namespace Database\Seeders;

use App\Models\Advertisement;
use Illuminate\Database\Seeder;

/**
 * Les 13 bannières par défaut de la page d'accueil.
 *
 * Elles constituent le carrousel de repli diffusé lorsqu'aucune campagne
 * publicitaire payante ne cible l'utilisateur. Elles étaient jusqu'ici codées
 * en dur dans l'application mobile (banner_carousel.dart) : les reprendre ici
 * permet de les piloter depuis l'administration et surtout de leur donner une
 * destination au clic, ce que le carrousel en dur ne savait pas faire.
 *
 * Les titres et sous-titres reprennent volontairement ceux déjà traduits côté
 * application (assets/translations/*.json → banner_carousel.defaults) pour que
 * la bascule vers l'API soit invisible pour l'utilisateur.
 *
 * Idempotent : rejouable sans créer de doublon (updateOrCreate sur le slug).
 */
class DefaultAdvertisementSeeder extends Seeder
{
    /**
     * Contact commercial utilisé par les bannières sans destination applicative
     * (réseautage et webinaires) : elles ouvrent une discussion WhatsApp.
     * Même numéro que les services publicitaires du tableau de bord recruteur.
     */
    private const CONTACT_WHATSAPP = '237696118389';

    public function run(): void
    {
        foreach ($this->banners() as $index => $banner) {
            $translations = $banner['translations'];
            unset($banner['translations']);

            /** @var Advertisement $ad */
            $ad = Advertisement::withTrashed()->updateOrCreate(
                ['slug' => $banner['slug']],
                array_merge($banner, [
                    'ad_type' => 'homepage_banner',
                    'is_default' => true,
                    'is_active' => true,
                    'status' => 'active',
                    'source' => 'admin',
                    'content_type' => 'text',
                    'target_countries' => null,
                    'default_order' => $index + 1,
                    'display_order' => $index + 1,
                    // Une bannière de repli n'a pas de fenêtre de diffusion :
                    // scopeDefaults() ignore ces dates, mais les colonnes sont
                    // NOT NULL en base, d'où une plage volontairement large.
                    'start_date' => now()->startOfDay(),
                    'end_date' => now()->addYears(10)->endOfDay(),
                ])
            );

            // Une bannière par défaut supprimée puis re-seedée doit redevenir
            // diffusable (deleted_at n'est pas assignable en masse).
            if ($ad->trashed()) {
                $ad->restore();
            }

            foreach ($translations as $field => $values) {
                $ad->setTranslations($values, $field);
            }
        }

        $this->command?->info('13 bannières par défaut créées ou mises à jour.');
    }

    /**
     * Définition des 13 bannières, dans leur ordre de défilement.
     *
     * `title`/`description` portent le français (locale de repli) ; les autres
     * langues passent par la table de traductions polymorphe.
     */
    private function banners(): array
    {
        return [
            // 1 — Développez votre carrière → formations vidéo
            [
                'slug' => 'default-career',
                'title' => 'Développez votre carrière',
                'description' => 'Formations et opportunités exclusives',
                'background_color' => '#00695C',
                'redirect_type' => 'internal_route',
                'redirect_target' => '/student-space/training-packs',
                'redirect_params' => null,
                'target_audience' => 'all',
                'translations' => [
                    'title' => [
                        'fr' => 'Développez votre carrière',
                        'en' => 'Grow your career',
                        'es' => 'Desarrolle su carrera',
                        'ar' => 'طوّر مسيرتك المهنية',
                    ],
                    'description' => [
                        'fr' => 'Formations et opportunités exclusives',
                        'en' => 'Exclusive trainings and opportunities',
                        'es' => 'Formaciones y oportunidades exclusivas',
                        'ar' => 'تدريبات وفرص حصرية',
                    ],
                ],
            ],

            // 2 — Trouvez votre emploi de rêve → offres encore valides
            [
                'slug' => 'default-dream-job',
                'title' => 'Trouvez votre emploi de rêve',
                'description' => "Des milliers d'offres vous attendent",
                'background_color' => '#0277BD',
                'redirect_type' => 'internal_route',
                'redirect_target' => '/search',
                'redirect_params' => ['status' => 'published'],
                'target_audience' => 'all',
                'translations' => [
                    'title' => [
                        'fr' => 'Trouvez votre emploi de rêve',
                        'en' => 'Find your dream job',
                        'es' => 'Encuentre el empleo de sus sueños',
                        'ar' => 'اعثر على وظيفة أحلامك',
                    ],
                    'description' => [
                        'fr' => "Des milliers d'offres vous attendent",
                        'en' => 'Thousands of offers await you',
                        'es' => 'Miles de ofertas le esperan',
                        'ar' => 'آلاف العروض في انتظارك',
                    ],
                ],
            ],

            // 3 — Trouvez les meilleurs talents → CVthèque (recruteurs)
            [
                'slug' => 'default-recruiters',
                'title' => 'Trouvez les meilleurs talents',
                'description' => 'Publiez vos offres gratuitement',
                'background_color' => '#FF6F00',
                'redirect_type' => 'internal_route',
                'redirect_target' => '/cv-library',
                'redirect_params' => null,
                'target_audience' => 'recruiter',
                'translations' => [
                    'title' => [
                        'fr' => 'Trouvez les meilleurs talents',
                        'en' => 'Recruiters, find top talent',
                        'es' => 'Reclutadores, encuentren los mejores talentos',
                        'ar' => 'أصحاب العمل، اعثروا على أفضل المواهب',
                    ],
                    'description' => [
                        'fr' => 'Publiez vos offres gratuitement',
                        'en' => 'Post your job listings for free',
                        'es' => 'Publiquen sus ofertas gratuitamente',
                        'ar' => 'انشر إعلانات وظائفك مجاناً',
                    ],
                ],
            ],

            // 4 — Rejoignez notre communauté → groupe Telegram
            // TODO: remplacer par l'URL réelle du groupe une fois celui-ci créé.
            [
                'slug' => 'default-community',
                'title' => 'Rejoignez notre communauté',
                'description' => 'Plus de 50,000 professionnels actifs',
                'background_color' => '#6A1B9A',
                'redirect_type' => 'external_url',
                'redirect_target' => 'https://t.me/estuaireemploi',
                'redirect_params' => null,
                'target_audience' => 'all',
                'translations' => [
                    'title' => [
                        'fr' => 'Rejoignez notre communauté',
                        'en' => 'Join our community',
                        'es' => 'Únase a nuestra comunidad',
                        'ar' => 'انضم إلى مجتمعنا',
                    ],
                    'description' => [
                        'fr' => 'Plus de 50,000 professionnels actifs',
                        'en' => 'Over 50,000 active professionals',
                        'es' => 'Más de 50,000 profesionales activos',
                        'ar' => 'أكثر من 50,000 محترف نشط',
                    ],
                ],
            ],

            // 5 — Formations certifiantes → tutoriels de formation
            [
                'slug' => 'default-training',
                'title' => 'Formations certifiantes',
                'description' => "Boostez vos compétences aujourd'hui",
                'background_color' => '#D32F2F',
                'redirect_type' => 'internal_route',
                'redirect_target' => '/student-space/training-packs',
                'redirect_params' => null,
                'target_audience' => 'all',
                'translations' => [
                    'title' => [
                        'fr' => 'Formations certifiantes',
                        'en' => 'Certified trainings',
                        'es' => 'Formaciones certificadas',
                        'ar' => 'تدريبات معتمدة',
                    ],
                    'description' => [
                        'fr' => "Boostez vos compétences aujourd'hui",
                        'en' => 'Boost your skills today',
                        'es' => 'Impulse sus competencias hoy',
                        'ar' => 'عزّز مهاراتك اليوم',
                    ],
                ],
            ],

            // 6 — Stages & Alternances → offres filtrées sur les stages
            [
                'slug' => 'default-internship',
                'title' => 'Stages & Alternances',
                'description' => 'Démarrez votre parcours professionnel',
                'background_color' => '#00897B',
                'redirect_type' => 'internal_route',
                'redirect_target' => '/search',
                'redirect_params' => ['contract_type_slug' => 'stage'],
                'target_audience' => 'all',
                'translations' => [
                    'title' => [
                        'fr' => 'Stages & Alternances',
                        'en' => 'Internships & Apprenticeships',
                        'es' => 'Prácticas y formación dual',
                        'ar' => 'تدريبات وتمهين',
                    ],
                    'description' => [
                        'fr' => 'Démarrez votre parcours professionnel',
                        'en' => 'Kickstart your professional journey',
                        'es' => 'Inicie su trayectoria profesional',
                        'ar' => 'ابدأ مسارك المهني',
                    ],
                ],
            ],

            // 7 — Jobs à distance → jobs étudiants (services rapides)
            [
                'slug' => 'default-remote',
                'title' => 'Jobs à distance',
                'description' => "Travaillez d'où vous voulez",
                'background_color' => '#1976D2',
                'redirect_type' => 'internal_route',
                'redirect_target' => '/services-list',
                'redirect_params' => null,
                'target_audience' => 'all',
                'translations' => [
                    'title' => [
                        'fr' => 'Jobs à distance',
                        'en' => 'Remote jobs',
                        'es' => 'Empleos a distancia',
                        'ar' => 'وظائف عن بُعد',
                    ],
                    'description' => [
                        'fr' => "Travaillez d'où vous voulez",
                        'en' => 'Work from anywhere you want',
                        'es' => 'Trabaje desde donde quiera',
                        'ar' => 'اعمل من أي مكان تريده',
                    ],
                ],
            ],

            // 8 — Startups qui recrutent → parc entreprise
            [
                'slug' => 'default-startups',
                'title' => 'Startups qui recrutent',
                'description' => "Rejoignez l'aventure entrepreneuriale",
                'background_color' => '#E64A19',
                'redirect_type' => 'internal_route',
                'redirect_target' => '/companies-directory',
                'redirect_params' => null,
                'target_audience' => 'all',
                'translations' => [
                    'title' => [
                        'fr' => 'Startups qui recrutent',
                        'en' => 'Startups hiring now',
                        'es' => 'Startups que contratan',
                        'ar' => 'شركات ناشئة توظّف الآن',
                    ],
                    'description' => [
                        'fr' => "Rejoignez l'aventure entrepreneuriale",
                        'en' => 'Join the entrepreneurial adventure',
                        'es' => 'Únase a la aventura emprendedora',
                        'ar' => 'انضم إلى مغامرة ريادة الأعمال',
                    ],
                ],
            ],

            // 9 — Préparez vos entretiens → vidéos de développement personnel
            [
                'slug' => 'default-interviews',
                'title' => 'Préparez vos entretiens',
                'description' => "Conseils et astuces d'experts RH",
                'background_color' => '#5E35B1',
                'redirect_type' => 'internal_route',
                'redirect_target' => '/student-space/training-packs',
                'redirect_params' => ['category' => 'developpement-personnel'],
                'target_audience' => 'all',
                'translations' => [
                    'title' => [
                        'fr' => 'Préparez vos entretiens',
                        'en' => 'Prepare your interviews',
                        'es' => 'Prepare sus entrevistas',
                        'ar' => 'استعدّ لمقابلاتك',
                    ],
                    'description' => [
                        'fr' => "Conseils et astuces d'experts RH",
                        'en' => 'Tips and tricks from HR experts',
                        'es' => 'Consejos y trucos de expertos en RR. HH.',
                        'ar' => 'نصائح وحيل من خبراء الموارد البشرية',
                    ],
                ],
            ],

            // 10 — Réseautage professionnel → contact WhatsApp
            // La destination applicative reste à définir : en attendant, la
            // bannière ouvre le contact commercial plutôt que de ne rien faire.
            [
                'slug' => 'default-networking',
                'title' => 'Réseautage professionnel',
                'description' => 'Connectez-vous avec des recruteurs',
                'background_color' => '#00838F',
                'redirect_type' => 'whatsapp',
                'redirect_target' => self::CONTACT_WHATSAPP,
                'redirect_params' => [
                    'message' => "Bonjour, je vous contacte depuis l'application Estuaire Emploi. "
                        . 'Je souhaiterais obtenir plus d\'informations concernant le réseautage professionnel. '
                        . 'Merci de me recontacter.',
                ],
                'target_audience' => 'all',
                'translations' => [
                    'title' => [
                        'fr' => 'Réseautage professionnel',
                        'en' => 'Professional networking',
                        'es' => 'Networking profesional',
                        'ar' => 'التواصل المهني',
                    ],
                    'description' => [
                        'fr' => 'Connectez-vous avec des recruteurs',
                        'en' => 'Connect with recruiters',
                        'es' => 'Conecte con reclutadores',
                        'ar' => 'تواصل مع أصحاب العمل',
                    ],
                ],
            ],

            // 11 — Webinaires gratuits → contact WhatsApp
            // Le mécanisme d'appel vidéo n'est pas encore arbitré : on ouvre le
            // contact commercial en attendant.
            [
                'slug' => 'default-webinars',
                'title' => 'Webinaires gratuits',
                'description' => 'Apprenez des meilleurs du secteur',
                'background_color' => '#C62828',
                'redirect_type' => 'whatsapp',
                'redirect_target' => self::CONTACT_WHATSAPP,
                'redirect_params' => [
                    'message' => "Bonjour, je vous contacte depuis l'application Estuaire Emploi. "
                        . 'Je souhaiterais obtenir plus d\'informations concernant les webinaires. '
                        . 'Merci de me recontacter.',
                ],
                'target_audience' => 'all',
                'translations' => [
                    'title' => [
                        'fr' => 'Webinaires gratuits',
                        'en' => 'Free webinars',
                        'es' => 'Webinars gratuitos',
                        'ar' => 'ندوات إلكترونية مجانية',
                    ],
                    'description' => [
                        'fr' => 'Apprenez des meilleurs du secteur',
                        'en' => 'Learn from the best in the industry',
                        'es' => 'Aprenda de los mejores del sector',
                        'ar' => 'تعلّم من أفضل المتخصصين في القطاع',
                    ],
                ],
            ],

            // 12 — Salaire attractif → jobs étudiants
            [
                'slug' => 'default-salary',
                'title' => 'Salaire attractif',
                'description' => 'Négociez votre rémunération idéale',
                'background_color' => '#AD1457',
                'redirect_type' => 'internal_route',
                'redirect_target' => '/services-list',
                'redirect_params' => null,
                'target_audience' => 'all',
                'translations' => [
                    'title' => [
                        'fr' => 'Salaire attractif',
                        'en' => 'Attractive salary',
                        'es' => 'Salario atractivo',
                        'ar' => 'راتب جذّاب',
                    ],
                    'description' => [
                        'fr' => 'Négociez votre rémunération idéale',
                        'en' => 'Negotiate your ideal compensation',
                        'es' => 'Negocie su remuneración ideal',
                        'ar' => 'تفاوض على أجرك المثالي',
                    ],
                ],
            ],

            // 13 — Évolution rapide → tutoriels de formation
            [
                'slug' => 'default-evolution',
                'title' => 'Évolution rapide',
                'description' => 'Des postes à hautes responsabilités',
                'background_color' => '#6A1B9A',
                'redirect_type' => 'internal_route',
                'redirect_target' => '/student-space/training-packs',
                'redirect_params' => null,
                'target_audience' => 'all',
                'translations' => [
                    'title' => [
                        'fr' => 'Évolution rapide',
                        'en' => 'Fast career growth',
                        'es' => 'Evolución rápida',
                        'ar' => 'تطور مهني سريع',
                    ],
                    'description' => [
                        'fr' => 'Des postes à hautes responsabilités',
                        'en' => 'Positions with high responsibilities',
                        'es' => 'Puestos con altas responsabilidades',
                        'ar' => 'مناصب ذات مسؤوليات عالية',
                    ],
                ],
            ],
        ];
    }
}
