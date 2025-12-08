<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Dashboard'); ?> - Estuaire Emploie Admin</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #667eea;
            --primary-dark: #5568d3;
            --secondary: #64748b;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #3b82f6;
            --dark: #1e293b;
            --light: #f8fafc;
            --border: #e2e8f0;
            --sidebar-width: 280px;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            background-attachment: fixed;
            color: var(--dark);
            line-height: 1.6;
            min-height: 100vh;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 100;
            box-shadow: 4px 0 24px rgba(0, 0, 0, 0.12);
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 3px;
        }

        .sidebar-header {
            padding: 2rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo-icon {
            width: 48px;
            height: 48px;
            background: white;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .logo-text h1 {
            font-size: 1.25rem;
            color: white;
            font-weight: 700;
            margin: 0;
        }

        .logo-text p {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.8);
            margin: 0;
        }

        .sidebar-menu {
            padding: 1.5rem 0;
        }

        .menu-section {
            margin-bottom: 2rem;
        }

        .menu-section-title {
            padding: 0 1.5rem;
            margin-bottom: 0.5rem;
            font-size: 0.7rem;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.5);
            font-weight: 700;
            letter-spacing: 1px;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 0.875rem 1.5rem;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            margin: 0.25rem 0.75rem;
            border-radius: 10px;
        }

        .menu-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 3px;
            background: var(--primary);
            opacity: 0;
            transition: opacity 0.3s;
        }

        .menu-item:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            transform: translateX(4px);
        }

        .menu-item.active {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.2), rgba(118, 75, 162, 0.2));
            color: white;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .menu-item.active::before {
            opacity: 1;
        }

        .menu-icon {
            width: 22px;
            height: 22px;
            margin-right: 12px;
            opacity: 0.9;
        }

        .menu-badge {
            margin-left: auto;
            background: var(--danger);
            color: white;
            font-size: 0.65rem;
            padding: 0.15rem 0.5rem;
            border-radius: 12px;
            font-weight: 700;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            background: var(--light);
        }

        /* Header */
        .header {
            background: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            padding: 1.25rem 2rem;
            position: sticky;
            top: 0;
            z-index: 50;
            backdrop-filter: blur(10px);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-left h2 {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0.25rem;
        }

        .breadcrumb {
            display: flex;
            gap: 0.5rem;
            font-size: 0.875rem;
            color: var(--secondary);
        }

        .breadcrumb a {
            color: var(--secondary);
            text-decoration: none;
            transition: color 0.2s;
        }

        .breadcrumb a:hover {
            color: var(--primary);
        }

        .header-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .header-btn {
            padding: 0.625rem 1.25rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .header-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4);
        }

        .notification-btn {
            position: relative;
            width: 44px;
            height: 44px;
            background: var(--light);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
            border: 2px solid transparent;
        }

        .notification-btn:hover {
            background: white;
            border-color: var(--primary);
        }

        .notification-badge {
            position: absolute;
            top: -4px;
            right: -4px;
            background: var(--danger);
            color: white;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            font-size: 0.65rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            border: 2px solid white;
        }

        .user-menu {
            position: relative;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0.5rem 1rem;
            background: var(--light);
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s;
            border: 2px solid transparent;
        }

        .user-profile:hover {
            background: white;
            border-color: var(--primary);
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1rem;
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
        }

        .user-info {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-weight: 600;
            color: var(--dark);
            font-size: 0.875rem;
        }

        .user-role {
            font-size: 0.75rem;
            color: var(--secondary);
        }

        .dropdown-menu {
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
            min-width: 220px;
            padding: 0.5rem;
            display: none;
            z-index: 1000;
        }

        .user-menu:hover .dropdown-menu {
            display: block;
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0.75rem 1rem;
            color: var(--dark);
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.2s;
            font-size: 0.875rem;
        }

        .dropdown-item:hover {
            background: var(--light);
            color: var(--primary);
        }

        .dropdown-divider {
            height: 1px;
            background: var(--border);
            margin: 0.5rem 0;
        }

        .dropdown-item.logout {
            color: var(--danger);
        }

        /* Content Area */
        .content {
            padding: 2rem;
        }

        /* Cards */
        .card {
            background: white;
            border-radius: 16px;
            padding: 1.75rem;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
            margin-bottom: 1.5rem;
            transition: all 0.3s;
        }

        .card:hover {
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--light);
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--dark);
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 1.75rem;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
            position: relative;
            overflow: hidden;
            transition: all 0.3s;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary) 0%, var(--info) 100%);
        }

        .stat-card.success::before {
            background: linear-gradient(90deg, var(--success) 0%, #059669 100%);
        }

        .stat-card.warning::before {
            background: linear-gradient(90deg, var(--warning) 0%, #d97706 100%);
        }

        .stat-card.danger::before {
            background: linear-gradient(90deg, var(--danger) 0%, #dc2626 100%);
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .stat-card .stat-icon {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
            color: var(--primary);
        }

        .stat-card.success .stat-icon {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }

        .stat-card.warning .stat-icon {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning);
        }

        .stat-card.danger .stat-icon {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
        }

        .stat-label {
            font-size: 0.875rem;
            color: var(--secondary);
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        .stat-value {
            font-size: 2.25rem;
            font-weight: 800;
            color: var(--dark);
            line-height: 1;
        }

        .stat-footer {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 1rem;
            font-size: 0.8rem;
        }

        .stat-trend {
            display: flex;
            align-items: center;
            gap: 4px;
            font-weight: 600;
        }

        .stat-trend.up {
            color: var(--success);
        }

        .stat-trend.down {
            color: var(--danger);
        }

        /* Alerts */
        .alert {
            padding: 1rem 1.25rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 500;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
            border-left: 4px solid var(--success);
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
            border-left: 4px solid var(--danger);
        }

        .alert-info {
            background: rgba(59, 130, 246, 0.1);
            color: var(--info);
            border-left: 4px solid var(--info);
        }

        .alert-warning {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning);
            border-left: 4px solid var(--warning);
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 0.875rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4);
        }

        .btn-success {
            background: var(--success);
            color: white;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .btn-danger {
            background: var(--danger);
            color: white;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.8125rem;
        }

        /* Badges */
        .badge {
            display: inline-block;
            padding: 0.375rem 0.875rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-success { background: rgba(16, 185, 129, 0.15); color: var(--success); }
        .badge-warning { background: rgba(245, 158, 11, 0.15); color: var(--warning); }
        .badge-danger { background: rgba(239, 68, 68, 0.15); color: var(--danger); }
        .badge-info { background: rgba(59, 130, 246, 0.15); color: var(--info); }
        .badge-secondary { background: rgba(100, 116, 139, 0.15); color: var(--secondary); }

        /* Table */
        .table-responsive {
            overflow-x: auto;
            border-radius: 12px;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        table thead {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        }

        table th {
            padding: 1rem;
            text-align: left;
            font-weight: 700;
            font-size: 0.8125rem;
            color: var(--dark);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        table tbody tr {
            transition: all 0.2s;
            border-bottom: 1px solid var(--border);
        }

        table tbody tr:hover {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.03), rgba(118, 75, 162, 0.03));
        }

        table td {
            padding: 1rem;
            vertical-align: middle;
        }

        /* Forms */
        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--dark);
            font-size: 0.875rem;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid var(--border);
            border-radius: 10px;
            font-size: 0.875rem;
            transition: all 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 120px;
        }

        /* Bulk Actions Toolbar */
        .bulk-actions-toolbar {
            position: fixed;
            bottom: 2rem;
            left: 50%;
            transform: translateX(-50%) translateY(100px);
            background: white;
            padding: 1rem 1.5rem;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
            display: flex;
            gap: 1rem;
            align-items: center;
            z-index: 1000;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 2px solid var(--border);
        }

        .bulk-actions-toolbar.show {
            transform: translateX(-50%) translateY(0);
        }

        .bulk-actions-toolbar .selected-count {
            font-weight: 700;
            color: var(--primary);
            padding: 0.5rem 1rem;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
            border-radius: 10px;
        }

        .bulk-actions-toolbar .btn {
            padding: 0.625rem 1.25rem;
        }

        /* Checkbox Styling */
        .checkbox-cell {
            width: 50px;
        }

        .custom-checkbox {
            width: 20px;
            height: 20px;
            cursor: pointer;
            accent-color: var(--primary);
        }

        table tbody tr.selected {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.08), rgba(118, 75, 162, 0.08));
        }

        .btn-secondary {
            background: var(--secondary);
            color: white;
        }

        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .main-content {
                margin-left: 0;
            }

            .bulk-actions-toolbar {
                flex-direction: column;
                gap: 0.5rem;
                padding: 1rem;
            }
        }
    </style>

    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <div class="logo-icon">💼</div>
                    <div class="logo-text">
                        <h1>Estuaire Emploie</h1>
                        <p>Administration</p>
                    </div>
                </div>
            </div>

            <nav class="sidebar-menu">
                <div class="menu-section">
                    <div class="menu-section-title">Principal</div>
                    <a href="<?php echo e(route('admin.dashboard')); ?>" class="menu-item <?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>">
                        <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Dashboard
                    </a>
                </div>

                <div class="menu-section">
                    <div class="menu-section-title">Gestion</div>
                    <a href="<?php echo e(route('admin.companies.index')); ?>" class="menu-item <?php echo e(request()->routeIs('admin.companies.*') ? 'active' : ''); ?>">
                        <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        Entreprises
                    </a>

                    <a href="<?php echo e(route('admin.jobs.index')); ?>" class="menu-item <?php echo e(request()->routeIs('admin.jobs.*') ? 'active' : ''); ?>">
                        <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Offres d'emploi
                    </a>

                    <a href="<?php echo e(route('admin.applications.index')); ?>" class="menu-item <?php echo e(request()->routeIs('admin.applications.*') ? 'active' : ''); ?>">
                        <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Candidatures
                        <?php if(isset($pendingApplications) && $pendingApplications > 0): ?>
                            <span class="menu-badge"><?php echo e($pendingApplications); ?></span>
                        <?php endif; ?>
                    </a>

                    <a href="<?php echo e(route('admin.users.index')); ?>" class="menu-item <?php echo e(request()->routeIs('admin.users.*') ? 'active' : ''); ?>">
                        <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        Candidats
                    </a>

                    <a href="<?php echo e(route('admin.recruiters.index')); ?>" class="menu-item <?php echo e(request()->routeIs('admin.recruiters.*') ? 'active' : ''); ?>">
                        <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Recruteurs
                    </a>
                </div>

                <div class="menu-section">
                    <div class="menu-section-title">API & Documentation</div>
                    <a href="/api/documentation" target="_blank" class="menu-item">
                        <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        Documentation API
                        <span class="menu-badge" style="background: #10b981;">Swagger</span>
                    </a>
                </div>

                <div class="menu-section">
                    <div class="menu-section-title">Administration</div>
                    <a href="<?php echo e(route('admin.admins.index')); ?>" class="menu-item <?php echo e(request()->routeIs('admin.admins.*') ? 'active' : ''); ?>">
                        <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        Administrateurs
                    </a>

                    <a href="<?php echo e(route('admin.settings.index')); ?>" class="menu-item <?php echo e(request()->routeIs('admin.settings.*') ? 'active' : ''); ?>">
                        <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Paramètres
                    </a>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <header class="header">
                <div class="header-content">
                    <div class="header-left">
                        <h2><?php echo $__env->yieldContent('page-title', 'Tableau de bord'); ?></h2>
                        <div class="breadcrumb">
                            <a href="<?php echo e(route('admin.dashboard')); ?>">Accueil</a>
                            <?php echo $__env->yieldContent('breadcrumbs'); ?>
                        </div>
                    </div>
                    <div class="header-actions">
                        <?php echo $__env->yieldContent('header-actions'); ?>

                        <div class="notification-btn">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            <span class="notification-badge">3</span>
                        </div>

                        <div class="user-menu">
                            <div class="user-profile">
                                <div class="user-avatar"><?php echo e(strtoupper(substr(auth()->user()->name, 0, 1))); ?></div>
                                <div class="user-info">
                                    <span class="user-name"><?php echo e(auth()->user()->name); ?></span>
                                    <span class="user-role"><?php echo e(ucfirst(auth()->user()->role)); ?></span>
                                </div>
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>

                            <div class="dropdown-menu">
                                <a href="<?php echo e(route('admin.profile')); ?>" class="dropdown-item">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    Mon Profil
                                </a>
                                <a href="<?php echo e(route('admin.settings.index')); ?>" class="dropdown-item">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    Paramètres
                                </a>
                                <div class="dropdown-divider"></div>
                                <form method="POST" action="<?php echo e(route('admin.logout')); ?>">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="dropdown-item logout" style="width: 100%; background: none; border: none; cursor: pointer; text-align: left;">
                                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                        </svg>
                                        Déconnexion
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <div class="content">
                <?php if(session('success')): ?>
                    <div class="alert alert-success">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                        </svg>
                        <?php echo e(session('success')); ?>

                    </div>
                <?php endif; ?>

                <?php if(session('error')): ?>
                    <div class="alert alert-danger">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/>
                        </svg>
                        <?php echo e(session('error')); ?>

                    </div>
                <?php endif; ?>

                <?php echo $__env->yieldContent('content'); ?>
            </div>
        </main>
    </div>

    <!-- Bulk Actions Toolbar -->
    <div id="bulkActionsToolbar" class="bulk-actions-toolbar">
        <span class="selected-count">
            <span id="selectedCount">0</span> sélectionné(s)
        </span>
        <button onclick="bulkActions.deselectAll()" class="btn btn-secondary">Désélectionner tout</button>
        <button onclick="bulkActions.confirmDelete()" class="btn btn-danger">Supprimer</button>
    </div>

    <script>
        // Bulk Actions Manager
        const bulkActions = {
            selectedIds: new Set(),
            toolbar: null,
            checkboxes: null,
            selectAllCheckbox: null,

            init() {
                this.toolbar = document.getElementById('bulkActionsToolbar');
                if (!this.toolbar) return;

                this.checkboxes = document.querySelectorAll('.row-checkbox');
                this.selectAllCheckbox = document.getElementById('selectAll');

                // Setup event listeners
                if (this.selectAllCheckbox) {
                    this.selectAllCheckbox.addEventListener('change', () => this.toggleAll());
                }

                this.checkboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', () => this.toggle(checkbox));
                });

                this.updateUI();
            },

            toggle(checkbox) {
                const id = checkbox.value;
                const row = checkbox.closest('tr');

                if (checkbox.checked) {
                    this.selectedIds.add(id);
                    row?.classList.add('selected');
                } else {
                    this.selectedIds.delete(id);
                    row?.classList.remove('selected');
                }

                this.updateUI();
            },

            toggleAll() {
                const isChecked = this.selectAllCheckbox.checked;

                this.checkboxes.forEach(checkbox => {
                    checkbox.checked = isChecked;
                    const row = checkbox.closest('tr');

                    if (isChecked) {
                        this.selectedIds.add(checkbox.value);
                        row?.classList.add('selected');
                    } else {
                        this.selectedIds.delete(checkbox.value);
                        row?.classList.remove('selected');
                    }
                });

                this.updateUI();
            },

            deselectAll() {
                this.checkboxes.forEach(checkbox => {
                    checkbox.checked = false;
                    checkbox.closest('tr')?.classList.remove('selected');
                });

                if (this.selectAllCheckbox) {
                    this.selectAllCheckbox.checked = false;
                }

                this.selectedIds.clear();
                this.updateUI();
            },

            updateUI() {
                const count = this.selectedIds.size;
                document.getElementById('selectedCount').textContent = count;

                if (count > 0) {
                    this.toolbar.classList.add('show');
                } else {
                    this.toolbar.classList.remove('show');
                }

                // Update select all checkbox state
                if (this.selectAllCheckbox && this.checkboxes.length > 0) {
                    const allChecked = Array.from(this.checkboxes).every(cb => cb.checked);
                    this.selectAllCheckbox.checked = allChecked;
                }
            },

            async confirmDelete() {
                if (this.selectedIds.size === 0) return;

                if (!confirm(`Êtes-vous sûr de vouloir supprimer ${this.selectedIds.size} élément(s) ?`)) {
                    return;
                }

                await this.bulkDelete();
            },

            async bulkDelete() {
                const form = document.getElementById('bulkDeleteForm');
                if (!form) {
                    console.error('Bulk delete form not found');
                    return;
                }

                // Add selected IDs to form
                const idsInput = document.createElement('input');
                idsInput.type = 'hidden';
                idsInput.name = 'ids';
                idsInput.value = JSON.stringify(Array.from(this.selectedIds));
                form.appendChild(idsInput);

                // Submit form
                form.submit();
            }
        };

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', () => {
            bulkActions.init();
        });
    </script>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH /Users/macbookpro/Desktop/Developments/INSAM-DEV/E-Emploie-Backend/estuaire-emploie-backend/resources/views/admin/layouts/app.blade.php ENDPATH**/ ?>