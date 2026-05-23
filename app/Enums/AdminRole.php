<?php

namespace App\Enums;

enum AdminRole: string
{
    case SUPER_ADMIN = 'super_admin';
    case CANDIDATE_RECRUITER_MANAGER = 'candidate_recruiter_manager';
    case TRAINING_PROGRAMS_MANAGER = 'training_programs_manager';
    case STUDENT_SPACE_MANAGER = 'student_space_manager';
    case ADVERTISING_MANAGER = 'advertising_manager';
    case FINANCE_MANAGER = 'finance_manager';
    case BULK_ANNOUNCEMENTS_MANAGER = 'bulk_announcements_manager';

    /**
     * Get the display label for the role.
     * Localized via lang/{locale}/admin_roles.php
     */
    public function label(): string
    {
        return __('admin_roles.labels.'.$this->value);
    }

    /**
     * Get the description for the role.
     * Localized via lang/{locale}/admin_roles.php
     */
    public function description(): string
    {
        return __('admin_roles.descriptions.'.$this->value);
    }

    /**
     * Get the permissions for this role
     */
    public function permissions(): array
    {
        return match($this) {
            self::SUPER_ADMIN => [], // Super admin has all permissions by default
            self::CANDIDATE_RECRUITER_MANAGER => [
                'manage_users',
                'manage_companies',
                'manage_jobs',
                'manage_applications',
                'manage_recruiters',
                'manage_settings',
            ],
            self::TRAINING_PROGRAMS_MANAGER => [
                'manage_settings', // For programs section
            ],
            self::STUDENT_SPACE_MANAGER => [
                'manage_premium_services', // For exam packs, training packs, videos
                'manage_settings',
            ],
            self::ADVERTISING_MANAGER => [
                'manage_advertisements',
            ],
            self::FINANCE_MANAGER => [
                'manage_subscription_plans',
                'manage_subscriptions',
                'manage_payments',
                'manage_recruiter_services',
                'manage_premium_services',
                'manage_advertisements',
                'view_financial_stats',
            ],
            self::BULK_ANNOUNCEMENTS_MANAGER => [
                // Announcements are accessible to all admins by default
            ],
        };
    }

    /**
     * Get all available admin roles
     */
    public static function all(): array
    {
        return [
            self::SUPER_ADMIN,
            self::CANDIDATE_RECRUITER_MANAGER,
            self::TRAINING_PROGRAMS_MANAGER,
            self::STUDENT_SPACE_MANAGER,
            self::ADVERTISING_MANAGER,
            self::FINANCE_MANAGER,
            self::BULK_ANNOUNCEMENTS_MANAGER,
        ];
    }

    /**
     * Get roles as options array for select
     */
    public static function options(): array
    {
        $options = [];
        foreach (self::all() as $role) {
            $options[$role->value] = [
                'label' => $role->label(),
                'description' => $role->description(),
            ];
        }
        return $options;
    }
}
