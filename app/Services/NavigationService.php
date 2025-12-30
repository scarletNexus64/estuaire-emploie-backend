<?php

namespace App\Services;

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
                        'name' => 'Candidatures',
                        'route' => 'admin.applications.index',
                        'icon' => 'fas fa-file-alt',
                        'permission' => 'manage_applications',
                    ],
                    [
                        'name' => 'Candidats',
                        'route' => 'admin.users.index',
                        'icon' => 'fas fa-users',
                        'permission' => 'manage_users',
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
                'section' => 'Monétisation',
                'items' => [
                    [
                        'name' => 'Plans d\'abonnement',
                        'route' => 'admin.subscription-plans.index',
                        'icon' => 'fas fa-tags',
                        'permission' => 'manage_subscription_plans',
                    ],
                    [
                        'name' => 'Abonnements',
                        'route' => 'admin.subscriptions.index',
                        'icon' => 'fas fa-crown',
                        'permission' => 'manage_subscriptions',
                    ],
                    [
                        'name' => 'Paiements',
                        'route' => 'admin.payments.index',
                        'icon' => 'fas fa-credit-card',
                        'permission' => 'manage_payments',
                    ],
                    [
                        'name' => 'Services Premium',
                        'route' => 'admin.premium-services.index',
                        'icon' => 'fas fa-star',
                        'permission' => 'manage_premium_services',
                    ],
                    [
                        'name' => 'Services Additionnels',
                        'route' => 'admin.addon-services.index',
                        'icon' => 'fas fa-puzzle-piece',
                        'permission' => 'manage_addon_services',
                    ],
                    [
                        'name' => 'CVthèque',
                        'route' => 'admin.cvtheque.index',
                        'icon' => 'fas fa-file-pdf',
                        'permission' => 'manage_cvtheque',
                    ],
                    [
                        'name' => 'Publicités',
                        'route' => 'admin.advertisements.index',
                        'icon' => 'fas fa-ad',
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
                        'name' => 'Sections',
                        'route' => 'admin.sections.index',
                        'icon' => 'fas fa-list',
                        'permission' => 'manage_sections',
                    ],
                    [
                        'name' => 'Administrateurs',
                        'route' => 'admin.admins.index',
                        'icon' => 'fas fa-user-shield',
                        'permission' => 'manage_admins',
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
     * Filter menu items based on user permissions
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
                // If no permission required, or user is super admin, or user has the permission
                if (
                    $item['permission'] === null ||
                    $user->isSuperAdmin() ||
                    $user->hasPermission($item['permission'])
                ) {
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
