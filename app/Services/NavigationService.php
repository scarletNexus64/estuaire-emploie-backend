<?php

namespace App\Services;

use App\Enums\AdminRole;
use App\Models\User;

class NavigationService
{
    /**
     * Get the navigation menu items
     *
     * @return array
     */
    public static function getMenuItems(): array
    {
        return [
            [
                'section' => 'Principal',
                'items' => [
                    [
                        'name' => 'Dashboard',
                        'route' => 'admin.dashboard',
                        'icon' => 'fas fa-tachometer-alt',
                        'permission' => null, // Everyone can access dashboard
                    ],
                ],
            ],
            [
                'section' => 'Gestion',
                'items' => [
                    [
                        'name' => 'Utilisateurs',
                        'route' => 'admin.users.index',
                        'icon' => 'fas fa-users',
                        'permission' => 'manage_users',
                    ],
                    [
                        'name' => 'Création de compte étudiant',
                        'route' => 'admin.students.index',
                        'icon' => 'fas fa-user-graduate',
                        'permission' => 'manage_premium_services',
                    ],
                    [
                        'name' => 'Entreprises',
                        'route' => 'admin.companies.index',
                        'icon' => 'fas fa-building',
                        'permission' => 'manage_companies',
                    ],
                    [
                        'name' => 'Offres d\'emploi',
                        'route' => 'admin.jobs.index',
                        'icon' => 'fas fa-briefcase',
                        'permission' => 'manage_jobs',
                    ],
                    [
                        'name' => 'Services Rapides',
                        'route' => 'admin.quick-services.index',
                        'icon' => 'fas fa-tools',
                        'permission' => 'manage_jobs',
                    ],
                    [
                        'name' => 'Candidatures',
                        'route' => 'admin.applications.index',
                        'icon' => 'fas fa-file-alt',
                        'permission' => 'manage_applications',
                    ],
                    [
                        'name' => 'Tests de Compétences',
                        'route' => 'admin.skill-tests.index',
                        'icon' => 'fas fa-clipboard-check',
                        'permission' => 'manage_applications',
                    ],
                    [
                        'name' => 'Portfolios',
                        'route' => 'admin.portfolios.index',
                        'icon' => 'fas fa-id-card',
                        'permission' => 'manage_users',
                    ],
                    [
                        'name' => 'CVthèque',
                        'route' => 'admin.cvtheque.index',
                        'icon' => 'fas fa-file-alt',
                        'permission' => 'manage_cvtheque',
                    ],
                    [
                        'name' => 'Recruteurs',
                        'route' => 'admin.recruiters.index',
                        'icon' => 'fas fa-user-tie',
                        'permission' => 'manage_recruiters',
                    ],
                ],
            ],
            [
                'section' => 'Programmes de Formation',
                'items' => [
                    [
                        'name' => 'Programmes',
                        'route' => 'admin.programs.index',
                        'icon' => 'fas fa-book',
                        'permission' => 'manage_settings',
                    ],
                ],
            ],
            [
                'section' => 'Contenu Étudiant',
                'items' => [
                    [
                        'name' => 'Packs d\'Épreuves',
                        'route' => 'admin.exam-packs.index',
                        'icon' => 'fas fa-box',
                        'permission' => 'manage_premium_services',
                    ],
                    [
                        'name' => 'Épreuves (gestion)',
                        'route' => 'admin.exam-papers.index',
                        'icon' => 'fas fa-file-pdf',
                        'permission' => 'manage_premium_services',
                    ],
                    [
                        'name' => 'Packs de Formation',
                        'route' => 'admin.training-packs.index',
                        'icon' => 'fas fa-graduation-cap',
                        'permission' => 'manage_premium_services',
                    ],
                    [
                        'name' => 'Vidéos de Formation',
                        'route' => 'admin.training-videos.index',
                        'icon' => 'fas fa-video',
                        'permission' => 'manage_premium_services',
                    ],
                ],
            ],
            [
                'section' => 'Monétisation',
                'items' => [
                    [
                        'name' => 'Plans d\'abonnement Recruteurs',
                        'route' => 'admin.subscription-plans.recruiters.index',
                        'icon' => 'fas fa-user-tie',
                        'permission' => 'manage_subscription_plans',
                    ],
                    [
                        'name' => 'Plans d\'abonnement Candidats',
                        'route' => 'admin.subscription-plans.job-seekers.index',
                        'icon' => 'fas fa-users',
                        'permission' => 'manage_subscription_plans',
                    ],
                    [
                        'name' => 'Attribution Manuelle',
                        'route' => 'admin.manual-subscriptions.index',
                        'icon' => 'fas fa-user-shield',
                        'permission' => 'manage_subscriptions',
                    ],
                    [
                        'name' => 'Paiements',
                        'route' => 'admin.payments.index',
                        'icon' => 'fas fa-credit-card',
                        'permission' => 'manage_payments',
                    ],
                    [
                        'name' => 'Wallets',
                        'route' => 'admin.wallets.index',
                        'icon' => 'fas fa-wallet',
                        'permission' => 'manage_payments',
                    ],
                    [
                        'name' => 'Compte FREEMOPAY',
                        'route' => 'admin.bank-account.index',
                        'icon' => 'fas fa-university',
                        'permission' => 'manage_payments',
                    ],
                    [
                        'name' => 'Services pour Recruteurs',
                        'route' => 'admin.recruiter-services.index',
                        'icon' => 'fas fa-briefcase',
                        'permission' => 'manage_recruiter_services',
                    ],
                    [
                        'name' => 'Services pour Candidats',
                        'route' => 'admin.premium-services.index',
                        'icon' => 'fas fa-user-graduate',
                        'permission' => 'manage_premium_services',
                    ],
                    [
                        'name' => 'Gestion des Espaces Publicitaires',
                        'route' => 'admin.advertisements.index',
                        'icon' => 'fas fa-bullhorn',
                        'permission' => 'manage_advertisements',
                    ],
                    [
                        'name' => 'Statistiques Financières',
                        'route' => 'admin.financial-stats.index',
                        'icon' => 'fas fa-chart-line',
                        'permission' => 'view_financial_stats',
                    ],
                ],
            ],
            [
                'section' => 'API & Documentation',
                'items' => [
                    [
                        'name' => 'Documentation API',
                        'url' => '/api/documentation',
                        'icon' => 'fas fa-book',
                        'permission' => null, // Everyone can view API docs
                        'external' => true,
                    ],
                ],
            ],
            [
                'section' => 'Administration',
                'items' => [
                    [
                        'name' => 'Administrateurs',
                        'route' => 'admin.admins.index',
                        'icon' => 'fas fa-user-shield',
                        'permission' => 'manage_admins',
                    ],
                    [
                        'name' => 'Annonces Push',
                        'route' => 'admin.announcements.index',
                        'icon' => 'fas fa-bell',
                        'permission' => null, // All admins can send announcements
                    ],
                    [
                        'name' => 'Tokens FCM',
                        'route' => 'admin.fcm-tokens.index',
                        'icon' => 'fas fa-bell',
                        'permission' => null, // All admins can view FCM tokens
                    ],
                    [
                        'name' => 'Mode Maintenance',
                        'route' => 'admin.maintenance.index',
                        'icon' => 'fas fa-wrench',
                        'permission' => 'manage_settings',
                    ],
                    [
                        'name' => 'Paramètres',
                        'route' => 'admin.settings.index',
                        'icon' => 'fas fa-cog',
                        'permission' => 'manage_settings',
                    ],
                    [
                        'name' => 'Configuration Services',
                        'route' => 'admin.service-config.index',
                        'icon' => 'fas fa-wrench',
                        'permission' => 'manage_service_config',
                    ],
                ],
            ],
        ];
    }

    /**
     * Check if a menu item should be visible for a specific admin role
     *
     * @param string $route
     * @param AdminRole $adminRole
     * @return bool
     */
    private static function isMenuItemVisibleForRole(string $route, AdminRole $adminRole): bool
    {
        // Define which routes are visible for each role
        $roleMenuAccess = [
            AdminRole::SUPER_ADMIN->value => ['*'], // Voit tout

            AdminRole::CANDIDATE_RECRUITER_MANAGER->value => [
                'admin.users.index',
                'admin.companies.index',
                'admin.jobs.index',
                'admin.quick-services.index',
                'admin.applications.index',
                'admin.skill-tests.index',
                'admin.portfolios.index',
                'admin.cvtheque.index',
                'admin.recruiters.index',
                'admin.settings.index',
            ],

            AdminRole::TRAINING_PROGRAMS_MANAGER->value => [
                'admin.programs.index',
            ],

            AdminRole::STUDENT_SPACE_MANAGER->value => [
                'admin.students.index',
                'admin.exam-packs.index',
                'admin.exam-papers.index',
                'admin.training-packs.index',
                'admin.training-videos.index',
                'admin.settings.index',
            ],

            AdminRole::ADVERTISING_MANAGER->value => [
                'admin.announcements.index',
                'admin.advertisements.index',
            ],

            AdminRole::FINANCE_MANAGER->value => [
                'admin.subscription-plans.recruiters.index',
                'admin.subscription-plans.job-seekers.index',
                'admin.manual-subscriptions.index',
                'admin.payments.index',
                'admin.wallets.index',
                'admin.bank-account.index',
                'admin.recruiter-services.index',
                'admin.premium-services.index',
                'admin.advertisements.index',
                'admin.financial-stats.index',
            ],

            AdminRole::BULK_ANNOUNCEMENTS_MANAGER->value => [
                'admin.announcements.index',
            ],
        ];

        $allowedRoutes = $roleMenuAccess[$adminRole->value] ?? [];

        // Super admin can see everything
        if (in_array('*', $allowedRoutes)) {
            return true;
        }

        // Check if the route is in the allowed routes
        return in_array($route, $allowedRoutes);
    }

    /**
     * Filter menu items based on user permissions and admin role
     *
     * @param User $user
     * @return array
     */
    public static function getFilteredMenuItems(User $user): array
    {
        $allMenuItems = self::getMenuItems();
        $filteredMenu = [];

        foreach ($allMenuItems as $section) {
            $filteredItems = [];

            foreach ($section['items'] as $item) {
                $shouldShow = false;

                // Super admin sees everything
                if ($user->isSuperAdmin()) {
                    $shouldShow = true;
                }
                // Check role-based access
                elseif ($user->admin_role && isset($item['route'])) {
                    $shouldShow = self::isMenuItemVisibleForRole($item['route'], $user->admin_role);
                }
                // Fallback to permission-based access
                elseif ($item['permission'] === null || $user->hasPermission($item['permission'])) {
                    $shouldShow = true;
                }

                // Dashboard is always visible for all admins
                if (isset($item['route']) && $item['route'] === 'admin.dashboard') {
                    $shouldShow = true;
                }

                if ($shouldShow) {
                    $filteredItems[] = $item;
                }
            }

            // Only add section if it has visible items
            if (!empty($filteredItems)) {
                $filteredMenu[] = [
                    'section' => $section['section'],
                    'items' => $filteredItems,
                ];
            }
        }

        return $filteredMenu;
    }

    /**
     * Get all available permissions
     *
     * @return array
     */
    public static function getAllPermissions(): array
    {
        return config('permissions.permissions', []);
    }

    /**
     * Get permissions grouped by category
     *
     * @return array
     */
    public static function getPermissionsByCategory(): array
    {
        $permissions = self::getAllPermissions();
        $grouped = [];

        foreach ($permissions as $key => $permission) {
            $category = $permission['category'] ?? 'Autres';

            if (!isset($grouped[$category])) {
                $grouped[$category] = [];
            }

            $grouped[$category][$key] = $permission;
        }

        return $grouped;
    }
}
