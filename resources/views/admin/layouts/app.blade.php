<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Estuaire Emploi Admin</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Material Design Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Custom Tailwind Config -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            DEFAULT: '#E31E24',
                            dark: '#B71C1C',
                            light: '#EF5350',
                        },
                        secondary: {
                            DEFAULT: '#0091D5',
                            dark: '#0277BD',
                            light: '#4FC3F7',
                        },
                        tertiary: {
                            DEFAULT: '#7B1FA2',
                            dark: '#6A1B9A',
                            light: '#9C27B0',
                        },
                        accent: '#F39C12',
                    }
                }
            }
        }
    </script>

    @stack('styles')

    <style>
        [x-cloak] { display: none !important; }

        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.05);
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.2);
            border-radius: 3px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(0, 0, 0, 0.3);
        }

        /* Legacy CSS Classes - Compatibility Layer */
        .stat-card {
            background-color: white;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
            padding: 1.5rem;
            transition: box-shadow 0.2s;
        }
        .stat-card:hover {
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        }
        .stat-card.success { border-left: 4px solid #10b981; }
        .stat-card.warning { border-left: 4px solid #f59e0b; }
        .stat-card.danger { border-left: 4px solid #ef4444; }
        .stat-card.info { border-left: 4px solid #3b82f6; }

        .stat-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .stat-label {
            color: #6b7280;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }
        .stat-value {
            font-size: 1.875rem;
            font-weight: 700;
            color: #111827;
        }
        .stat-icon {
            font-size: 2.25rem;
            opacity: 0.5;
        }
        .stat-footer {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #f3f4f6;
        }
        .stat-trend {
            font-size: 0.875rem;
            color: #6b7280;
        }
        .stat-trend.up { color: #10b981; }
        .stat-trend.down { color: #ef4444; }

        .card {
            background-color: white;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);
            overflow: hidden;
            margin-bottom: 1.5rem;
        }
        .card-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .card-title {
            font-size: 1.125rem;
            font-weight: 700;
            color: #111827;
        }
        .card-body {
            padding: 1.5rem;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.2s;
            cursor: pointer;
            border: none;
            text-decoration: none;
        }
        .btn-primary {
            background: linear-gradient(to right, #E31E24, #7B1FA2);
            color: white;
        }
        .btn-primary:hover {
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1);
        }
        .btn-secondary {
            background-color: #4b5563;
            color: white;
        }
        .btn-secondary:hover { background-color: #374151; }
        .btn-success {
            background-color: #10b981;
            color: white;
        }
        .btn-success:hover { background-color: #059669; }
        .btn-danger {
            background-color: #ef4444;
            color: white;
        }
        .btn-danger:hover { background-color: #dc2626; }
        .btn-warning {
            background-color: #f59e0b;
            color: white;
        }
        .btn-warning:hover { background-color: #d97706; }
        .btn-info {
            background-color: #3b82f6;
            color: white;
        }
        .btn-info:hover { background-color: #2563eb; }
        .btn-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }
        .btn-lg {
            padding: 0.75rem 1.5rem;
            font-size: 1.125rem;
        }

        .table-responsive {
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table thead {
            background-color: #f9fafb;
            border-bottom: 2px solid #e5e7eb;
        }
        table th {
            padding: 0.75rem 1.5rem;
            text-align: left;
            font-size: 0.75rem;
            font-weight: 700;
            color: #374151;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        table tbody tr {
            border-bottom: 1px solid #f3f4f6;
            transition: background-color 0.2s;
        }
        table tbody tr:hover {
            background-color: #f9fafb;
        }
        table td {
            padding: 1rem 1.5rem;
            font-size: 0.875rem;
            color: #111827;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.25rem 0.625rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .badge-success {
            background-color: #d1fae5;
            color: #065f46;
        }
        .badge-danger {
            background-color: #fee2e2;
            color: #991b1b;
        }
        .badge-warning {
            background-color: #fed7aa;
            color: #92400e;
        }
        .badge-info {
            background-color: #dbeafe;
            color: #1e40af;
        }
        .badge-secondary {
            background-color: #f3f4f6;
            color: #374151;
        }
        .badge-primary {
            background-color: rgba(227, 30, 36, 0.1);
            color: #E31E24;
        }

        .form-group {
            margin-bottom: 1rem;
        }
        .form-group label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.5rem;
        }
        .form-control, input[type="text"], input[type="email"], input[type="password"],
        input[type="number"], input[type="date"], input[type="time"], textarea, select {
            width: 100%;
            padding: 0.5rem 1rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            transition: all 0.2s;
        }
        .form-control:focus, input:focus, textarea:focus, select:focus {
            outline: none;
            border-color: #E31E24;
            box-shadow: 0 0 0 3px rgba(227, 30, 36, 0.1);
        }
        .form-control:disabled {
            background-color: #f3f4f6;
            cursor: not-allowed;
        }
        textarea {
            min-height: 100px;
            resize: vertical;
        }
        select {
            cursor: pointer;
        }

        .pagination {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            justify-content: center;
            margin-top: 1.5rem;
        }
        .pagination a, .pagination span {
            padding: 0.5rem 0.75rem;
            border-radius: 0.5rem;
            border: 1px solid #d1d5db;
            font-size: 0.875rem;
            text-decoration: none;
            color: #374151;
        }
        .pagination a:hover {
            background-color: #f9fafb;
        }
        .pagination .active {
            background-color: #E31E24;
            color: white;
            border-color: #E31E24;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .filter-bar {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);
            padding: 1rem;
            margin-bottom: 1.5rem;
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            align-items: center;
        }
        .search-box {
            flex: 1;
            min-width: 200px;
        }
        .search-box input {
            width: 100%;
        }

        /* Additional Classes for Detail Pages */
        .detail-section {
            background-color: white;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .detail-section h3 {
            font-size: 1.125rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 1rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #f3f4f6;
        }

        .info-row {
            display: grid;
            grid-template-columns: 200px 1fr;
            gap: 1rem;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: #6b7280;
            font-size: 0.875rem;
        }

        .info-value {
            color: #111827;
            font-size: 0.875rem;
        }

        .action-buttons {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
            margin-top: 1.5rem;
        }

        .nav-tabs {
            display: flex;
            gap: 0.5rem;
            border-bottom: 2px solid #e5e7eb;
            margin-bottom: 1.5rem;
            overflow-x: auto;
        }

        .nav-link {
            padding: 0.75rem 1.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: #6b7280;
            text-decoration: none;
            border-bottom: 2px solid transparent;
            margin-bottom: -2px;
            transition: all 0.2s;
            white-space: nowrap;
        }

        .nav-link:hover {
            color: #111827;
        }

        .nav-link.active {
            color: #E31E24;
            border-bottom-color: #E31E24;
        }

        .tab-content {
            display: block;
        }

        .tab-pane {
            display: none;
        }

        .tab-pane.active {
            display: block;
        }

        .tab-pane.show {
            display: block;
        }

        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: #6b7280;
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .empty-state p {
            font-size: 1rem;
            margin-bottom: 1rem;
        }

        .file-upload {
            border: 2px dashed #d1d5db;
            border-radius: 0.5rem;
            padding: 2rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .file-upload:hover {
            border-color: #E31E24;
            background-color: rgba(227, 30, 36, 0.05);
        }

        .file-upload input[type="file"] {
            display: none;
        }

        .file-preview {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            margin-top: 1rem;
        }

        .file-preview-item {
            position: relative;
            width: 100px;
            height: 100px;
            border-radius: 0.5rem;
            overflow: hidden;
            border: 1px solid #e5e7eb;
        }

        .file-preview-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .file-preview-item .remove {
            position: absolute;
            top: 0.25rem;
            right: 0.25rem;
            background-color: #ef4444;
            color: white;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 14px;
        }

        .list-group {
            display: flex;
            flex-direction: column;
            gap: 0;
        }

        .list-group-item {
            padding: 0.75rem 1rem;
            border: 1px solid #e5e7eb;
            background-color: white;
        }

        .list-group-item:first-child {
            border-top-left-radius: 0.5rem;
            border-top-right-radius: 0.5rem;
        }

        .list-group-item:last-child {
            border-bottom-left-radius: 0.5rem;
            border-bottom-right-radius: 0.5rem;
        }

        .list-group-item + .list-group-item {
            border-top: none;
        }

        .list-group-item.active {
            background-color: #E31E24;
            color: white;
            border-color: #E31E24;
        }

        .progress {
            height: 1rem;
            background-color: #e5e7eb;
            border-radius: 9999px;
            overflow: hidden;
        }

        .progress-bar {
            height: 100%;
            background-color: #E31E24;
            transition: width 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            color: white;
            font-weight: 600;
        }

        .progress-bar.bg-success {
            background-color: #10b981;
        }

        .progress-bar.bg-warning {
            background-color: #f59e0b;
        }

        .progress-bar.bg-danger {
            background-color: #ef4444;
        }

        .progress-bar.bg-info {
            background-color: #3b82f6;
        }

        .spinner-border {
            display: inline-block;
            width: 1rem;
            height: 1rem;
            border: 2px solid currentColor;
            border-right-color: transparent;
            border-radius: 50%;
            animation: spin 0.75s linear infinite;
        }

        .spinner-border-sm {
            width: 0.875rem;
            height: 0.875rem;
            border-width: 1.5px;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .tooltip {
            position: relative;
            display: inline-block;
        }

        .tooltip .tooltiptext {
            visibility: hidden;
            width: 120px;
            background-color: #111827;
            color: white;
            text-align: center;
            border-radius: 0.375rem;
            padding: 0.375rem;
            position: absolute;
            z-index: 1;
            bottom: 125%;
            left: 50%;
            margin-left: -60px;
            opacity: 0;
            transition: opacity 0.3s;
            font-size: 0.75rem;
        }

        .tooltip:hover .tooltiptext {
            visibility: visible;
            opacity: 1;
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            color: #6b7280;
        }

        .breadcrumb a {
            color: #6b7280;
            text-decoration: none;
        }

        .breadcrumb a:hover {
            color: #E31E24;
        }

        .breadcrumb-item.active {
            color: #111827;
            font-weight: 500;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            margin-left: -0.75rem;
            margin-right: -0.75rem;
        }

        .col, .col-6, .col-4, .col-3, .col-12, .col-md-6, .col-md-4, .col-md-3 {
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }

        .col {
            flex: 1;
        }

        .col-12 {
            width: 100%;
        }

        .col-6 {
            width: 50%;
        }

        .col-4 {
            width: 33.333333%;
        }

        .col-3 {
            width: 25%;
        }

        @media (min-width: 768px) {
            .col-md-6 {
                width: 50%;
            }
            .col-md-4 {
                width: 33.333333%;
            }
            .col-md-3 {
                width: 25%;
            }
        }

        .img-fluid {
            max-width: 100%;
            height: auto;
        }

        .img-thumbnail {
            padding: 0.25rem;
            background-color: white;
            border: 1px solid #e5e7eb;
            border-radius: 0.375rem;
            max-width: 100%;
            height: auto;
        }

        .text-truncate {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .float-end {
            float: right;
        }

        .float-start {
            float: left;
        }

        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }

        .visually-hidden {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }

        hr {
            border: 0;
            border-top: 1px solid #e5e7eb;
            margin: 1rem 0;
        }

        .input-group-text {
            padding: 0.5rem 0.75rem;
            background-color: #f3f4f6;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            font-size: 0.875rem;
        }

        .form-check {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .form-check-input {
            width: 1rem;
            height: 1rem;
            cursor: pointer;
        }

        .form-check-label {
            cursor: pointer;
            font-size: 0.875rem;
            color: #374151;
        }

        .form-switch .form-check-input {
            width: 2.5rem;
            height: 1.25rem;
            border-radius: 9999px;
            background-color: #d1d5db;
            border: none;
            cursor: pointer;
            position: relative;
        }

        .form-switch .form-check-input:checked {
            background-color: #10b981;
        }

        .invalid-feedback {
            display: block;
            margin-top: 0.25rem;
            color: #ef4444;
            font-size: 0.875rem;
        }

        .is-invalid {
            border-color: #ef4444 !important;
        }

        .is-valid {
            border-color: #10b981 !important;
        }

        .valid-feedback {
            display: block;
            margin-top: 0.25rem;
            color: #10b981;
            font-size: 0.875rem;
        }

        /* Bootstrap-like utility classes */
        .col-lg-8, .col-lg-4, .col-lg-6 {
            width: 100%;
        }

        @media (min-width: 992px) {
            .col-lg-3 {
                width: 25%;
            }
            .col-lg-4 {
                width: 33.333333%;
            }
            .col-lg-6 {
                width: 50%;
            }
            .col-lg-8 {
                width: 66.666667%;
            }
            .col-lg-9 {
                width: 75%;
            }
            .col-lg-12 {
                width: 100%;
            }
        }

        .d-flex {
            display: flex !important;
        }

        .d-block {
            display: block !important;
        }

        .d-none {
            display: none !important;
        }

        .d-inline {
            display: inline !important;
        }

        .d-inline-block {
            display: inline-block !important;
        }

        .flex-column {
            flex-direction: column !important;
        }

        .flex-row {
            flex-direction: row !important;
        }

        .flex-wrap {
            flex-wrap: wrap !important;
        }

        .flex-nowrap {
            flex-wrap: nowrap !important;
        }

        .justify-content-start {
            justify-content: flex-start !important;
        }

        .justify-content-end {
            justify-content: flex-end !important;
        }

        .justify-content-center {
            justify-content: center !important;
        }

        .justify-content-between {
            justify-content: space-between !important;
        }

        .justify-content-around {
            justify-content: space-around !important;
        }

        .align-items-start {
            align-items: flex-start !important;
        }

        .align-items-end {
            align-items: flex-end !important;
        }

        .align-items-center {
            align-items: center !important;
        }

        .align-items-baseline {
            align-items: baseline !important;
        }

        .align-items-stretch {
            align-items: stretch !important;
        }

        .mb-0 {
            margin-bottom: 0 !important;
        }

        .mb-1 {
            margin-bottom: 0.25rem !important;
        }

        .mb-2 {
            margin-bottom: 0.5rem !important;
        }

        .mb-3 {
            margin-bottom: 1rem !important;
        }

        .mb-4 {
            margin-bottom: 1.5rem !important;
        }

        .mb-5 {
            margin-bottom: 3rem !important;
        }

        .mt-0 {
            margin-top: 0 !important;
        }

        .mt-1 {
            margin-top: 0.25rem !important;
        }

        .mt-2 {
            margin-top: 0.5rem !important;
        }

        .mt-3 {
            margin-top: 1rem !important;
        }

        .mt-4 {
            margin-top: 1.5rem !important;
        }

        .mt-5 {
            margin-top: 3rem !important;
        }

        .ms-1 {
            margin-left: 0.25rem !important;
        }

        .ms-2 {
            margin-left: 0.5rem !important;
        }

        .ms-3 {
            margin-left: 1rem !important;
        }

        .me-1 {
            margin-right: 0.25rem !important;
        }

        .me-2 {
            margin-right: 0.5rem !important;
        }

        .me-3 {
            margin-right: 1rem !important;
        }

        .mx-auto {
            margin-left: auto !important;
            margin-right: auto !important;
        }

        .my-3 {
            margin-top: 1rem !important;
            margin-bottom: 1rem !important;
        }

        .my-4 {
            margin-top: 1.5rem !important;
            margin-bottom: 1.5rem !important;
        }

        .pb-0 {
            padding-bottom: 0 !important;
        }

        .pb-1 {
            padding-bottom: 0.25rem !important;
        }

        .pb-2 {
            padding-bottom: 0.5rem !important;
        }

        .pb-3 {
            padding-bottom: 1rem !important;
        }

        .pt-0 {
            padding-top: 0 !important;
        }

        .pt-1 {
            padding-top: 0.25rem !important;
        }

        .pt-2 {
            padding-top: 0.5rem !important;
        }

        .pt-3 {
            padding-top: 1rem !important;
        }

        .ps-3 {
            padding-left: 1rem !important;
        }

        .pe-3 {
            padding-right: 1rem !important;
        }

        .p-0 {
            padding: 0 !important;
        }

        .p-1 {
            padding: 0.25rem !important;
        }

        .p-2 {
            padding: 0.5rem !important;
        }

        .p-3 {
            padding: 1rem !important;
        }

        .p-4 {
            padding: 1.5rem !important;
        }

        .p-5 {
            padding: 3rem !important;
        }

        .gap-1 {
            gap: 0.25rem !important;
        }

        .gap-2 {
            gap: 0.5rem !important;
        }

        .gap-3 {
            gap: 1rem !important;
        }

        .gap-4 {
            gap: 1.5rem !important;
        }

        .gap-5 {
            gap: 3rem !important;
        }

        .w-25 {
            width: 25% !important;
        }

        .w-50 {
            width: 50% !important;
        }

        .w-75 {
            width: 75% !important;
        }

        .w-100 {
            width: 100% !important;
        }

        .h-100 {
            height: 100% !important;
        }

        .mw-100 {
            max-width: 100% !important;
        }

        .mh-100 {
            max-height: 100% !important;
        }

        .text-start {
            text-align: left !important;
        }

        .text-end {
            text-align: right !important;
        }

        .text-center {
            text-align: center !important;
        }

        .text-uppercase {
            text-transform: uppercase !important;
        }

        .text-lowercase {
            text-transform: lowercase !important;
        }

        .text-capitalize {
            text-transform: capitalize !important;
        }

        .fw-light {
            font-weight: 300 !important;
        }

        .fw-normal {
            font-weight: 400 !important;
        }

        .fw-medium {
            font-weight: 500 !important;
        }

        .fw-semibold {
            font-weight: 600 !important;
        }

        .fw-bold {
            font-weight: 700 !important;
        }

        .fst-italic {
            font-style: italic !important;
        }

        .text-decoration-none {
            text-decoration: none !important;
        }

        .text-decoration-underline {
            text-decoration: underline !important;
        }

        .border {
            border: 1px solid #e5e7eb !important;
        }

        .border-0 {
            border: 0 !important;
        }

        .border-top {
            border-top: 1px solid #e5e7eb !important;
        }

        .border-bottom {
            border-bottom: 1px solid #e5e7eb !important;
        }

        .border-start {
            border-left: 1px solid #e5e7eb !important;
        }

        .border-end {
            border-right: 1px solid #e5e7eb !important;
        }

        .rounded {
            border-radius: 0.375rem !important;
        }

        .rounded-0 {
            border-radius: 0 !important;
        }

        .rounded-1 {
            border-radius: 0.25rem !important;
        }

        .rounded-2 {
            border-radius: 0.375rem !important;
        }

        .rounded-3 {
            border-radius: 0.5rem !important;
        }

        .rounded-circle {
            border-radius: 50% !important;
        }

        .rounded-pill {
            border-radius: 50rem !important;
        }

        .shadow-none {
            box-shadow: none !important;
        }

        .shadow-sm {
            box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05) !important;
        }

        .shadow {
            box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1) !important;
        }

        .shadow-lg {
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1) !important;
        }

        .opacity-25 {
            opacity: 0.25 !important;
        }

        .opacity-50 {
            opacity: 0.5 !important;
        }

        .opacity-75 {
            opacity: 0.75 !important;
        }

        .opacity-100 {
            opacity: 1 !important;
        }

        .overflow-auto {
            overflow: auto !important;
        }

        .overflow-hidden {
            overflow: hidden !important;
        }

        .position-relative {
            position: relative !important;
        }

        .position-absolute {
            position: absolute !important;
        }

        .position-fixed {
            position: fixed !important;
        }

        .position-sticky {
            position: sticky !important;
        }

        .top-0 {
            top: 0 !important;
        }

        .bottom-0 {
            bottom: 0 !important;
        }

        .start-0 {
            left: 0 !important;
        }

        .end-0 {
            right: 0 !important;
        }

        .flex-1 {
            flex: 1 !important;
        }

        .flex-grow-1 {
            flex-grow: 1 !important;
        }

        .flex-shrink-0 {
            flex-shrink: 0 !important;
        }

        .flex-shrink-1 {
            flex-shrink: 1 !important;
        }

        .cursor-pointer {
            cursor: pointer !important;
        }

        .user-select-none {
            user-select: none !important;
        }

        .pointer-events-none {
            pointer-events: none !important;
        }

        h1, h2, h3, h4, h5, h6 {
            margin-top: 0;
            margin-bottom: 0.5rem;
            font-weight: 700;
            line-height: 1.2;
            color: #111827;
        }

        h1 { font-size: 2.5rem; }
        h2 { font-size: 2rem; }
        h3 { font-size: 1.75rem; }
        h4 { font-size: 1.5rem; }
        h5 { font-size: 1.25rem; }
        h6 { font-size: 1rem; }

        p {
            margin-top: 0;
            margin-bottom: 1rem;
        }

        strong, b {
            font-weight: 700;
        }

        small, .small {
            font-size: 0.875rem;
        }

        .lead {
            font-size: 1.25rem;
            font-weight: 300;
        }

        a {
            color: #E31E24;
            text-decoration: none;
        }

        a:hover {
            color: #B71C1C;
            text-decoration: underline;
        }

        /* Additional spacing utilities */
        .ms-0 { margin-left: 0 !important; }
        .ms-1 { margin-left: 0.25rem !important; }
        .ms-2 { margin-left: 0.5rem !important; }
        .ms-3 { margin-left: 1rem !important; }
        .ms-4 { margin-left: 1.5rem !important; }
        .ms-5 { margin-left: 3rem !important; }
        .ms-auto { margin-left: auto !important; }

        .me-0 { margin-right: 0 !important; }
        .me-1 { margin-right: 0.25rem !important; }
        .me-2 { margin-right: 0.5rem !important; }
        .me-3 { margin-right: 1rem !important; }
        .me-4 { margin-right: 1.5rem !important; }
        .me-5 { margin-right: 3rem !important; }
        .me-auto { margin-right: auto !important; }

        /* Legacy ml/mr classes for compatibility */
        .ml-0 { margin-left: 0 !important; }
        .ml-1 { margin-left: 0.25rem !important; }
        .ml-2 { margin-left: 0.5rem !important; }
        .ml-3 { margin-left: 1rem !important; }
        .ml-4 { margin-left: 1.5rem !important; }
        .ml-5 { margin-left: 3rem !important; }
        .ml-auto { margin-left: auto !important; }

        .mr-0 { margin-right: 0 !important; }
        .mr-1 { margin-right: 0.25rem !important; }
        .mr-2 { margin-right: 0.5rem !important; }
        .mr-3 { margin-right: 1rem !important; }
        .mr-4 { margin-right: 1.5rem !important; }
        .mr-5 { margin-right: 3rem !important; }
        .mr-auto { margin-right: auto !important; }

        .pl-0 { padding-left: 0 !important; }
        .pl-1 { padding-left: 0.25rem !important; }
        .pl-2 { padding-left: 0.5rem !important; }
        .pl-3 { padding-left: 1rem !important; }
        .pl-4 { padding-left: 1.5rem !important; }
        .pl-5 { padding-left: 3rem !important; }

        .pr-0 { padding-right: 0 !important; }
        .pr-1 { padding-right: 0.25rem !important; }
        .pr-2 { padding-right: 0.5rem !important; }
        .pr-3 { padding-right: 1rem !important; }
        .pr-4 { padding-right: 1.5rem !important; }
        .pr-5 { padding-right: 3rem !important; }

        /* FontAwesome to MDI Icon mapping */
        .fas, .far, .fab {
            font-family: 'Material Design Icons' !important;
            font-weight: normal !important;
            font-style: normal !important;
        }

        .fa-arrow-left::before { content: "\F004D"; }
        .fa-check::before { content: "\F012C"; }
        .fa-times::before { content: "\F0156"; }
        .fa-edit::before { content: "\F03EB"; }
        .fa-trash::before { content: "\F01B4"; }
        .fa-plus::before { content: "\F0415"; }
        .fa-search::before { content: "\F0349"; }
        .fa-download::before { content: "\F0001"; }
        .fa-upload::before { content: "\F0552"; }
        .fa-file::before { content: "\F0214"; }
        .fa-user::before { content: "\F0004"; }
        .fa-users::before { content: "\F0064"; }
        .fa-cog::before { content: "\F0493"; }
        .fa-chart-bar::before { content: "\F0128"; }
        .fa-calendar::before { content: "\F00ED"; }
        .fa-clock::before { content: "\F0954"; }
        .fa-envelope::before { content: "\F01EE"; }
        .fa-phone::before { content: "\F03F2"; }
        .fa-home::before { content: "\F02DC"; }
        .fa-building::before { content: "\F0163"; }
        .fa-briefcase::before { content: "\F00B7"; }

        /* Pre and Code blocks */
        pre {
            background-color: #f5f5f5;
            padding: 1rem;
            border-radius: 0.5rem;
            border: 1px solid #e5e7eb;
            overflow-x: auto;
            font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
            font-size: 0.875rem;
            line-height: 1.5;
            margin: 0.5rem 0;
        }

        code {
            background-color: #f5f5f5;
            padding: 0.125rem 0.375rem;
            border-radius: 0.25rem;
            font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
            font-size: 0.875rem;
            color: #E31E24;
        }

        pre code {
            background-color: transparent;
            padding: 0;
            color: inherit;
        }

        /* List styles */
        ul {
            padding-left: 1.5rem;
            margin: 0.5rem 0;
        }

        ol {
            padding-left: 1.5rem;
            margin: 0.5rem 0;
        }

        li {
            margin: 0.25rem 0;
        }

        ul.list-unstyled {
            padding-left: 0;
            list-style: none;
        }

        ul.list-inline {
            padding-left: 0;
            list-style: none;
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        ul.list-inline li {
            display: inline-block;
        }

        /* Blockquote */
        blockquote {
            padding: 1rem 1.5rem;
            margin: 1rem 0;
            border-left: 4px solid #E31E24;
            background-color: #f9fafb;
            font-style: italic;
        }

        /* Definition lists */
        dl {
            margin: 0.5rem 0;
        }

        dt {
            font-weight: 600;
            color: #374151;
        }

        dd {
            margin-left: 1.5rem;
            margin-bottom: 0.5rem;
            color: #6b7280;
        }

        /* Horizontal rule */
        hr {
            border: 0;
            border-top: 1px solid #e5e7eb;
            margin: 1.5rem 0;
        }

        hr.my-4 {
            margin: 2rem 0;
        }

        /* Color utilities */
        .text-black { color: #000000 !important; }
        .text-white { color: #ffffff !important; }
        .text-gray { color: #6b7280 !important; }
        .text-gray-light { color: #9ca3af !important; }
        .text-gray-dark { color: #374151 !important; }

        .bg-white { background-color: #ffffff !important; }
        .bg-gray { background-color: #f3f4f6 !important; }
        .bg-gray-light { background-color: #f9fafb !important; }
        .bg-transparent { background-color: transparent !important; }

        /* Display utilities */
        @media (min-width: 768px) {
            .d-md-none { display: none !important; }
            .d-md-block { display: block !important; }
            .d-md-flex { display: flex !important; }
            .d-md-inline { display: inline !important; }
            .d-md-inline-block { display: inline-block !important; }
        }

        @media (min-width: 992px) {
            .d-lg-none { display: none !important; }
            .d-lg-block { display: block !important; }
            .d-lg-flex { display: flex !important; }
            .d-lg-inline { display: inline !important; }
            .d-lg-inline-block { display: inline-block !important; }
        }

        /* Visibility */
        .visible { visibility: visible !important; }
        .invisible { visibility: hidden !important; }

        /* Vertical align */
        .align-baseline { vertical-align: baseline !important; }
        .align-top { vertical-align: top !important; }
        .align-middle { vertical-align: middle !important; }
        .align-bottom { vertical-align: bottom !important; }
        .align-text-top { vertical-align: text-top !important; }
        .align-text-bottom { vertical-align: text-bottom !important; }

        /* White space */
        .text-nowrap { white-space: nowrap !important; }
        .text-wrap { white-space: normal !important; }
        .text-pre { white-space: pre !important; }
        .text-pre-wrap { white-space: pre-wrap !important; }
        .text-pre-line { white-space: pre-line !important; }

        /* Word break */
        .text-break { word-wrap: break-word !important; word-break: break-word !important; }

        /* Overflow */
        .overflow-visible { overflow: visible !important; }
        .overflow-scroll { overflow: scroll !important; }

        /* Z-index */
        .z-0 { z-index: 0 !important; }
        .z-10 { z-index: 10 !important; }
        .z-20 { z-index: 20 !important; }
        .z-30 { z-index: 30 !important; }
        .z-40 { z-index: 40 !important; }
        .z-50 { z-index: 50 !important; }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased" x-data="{ sidebarOpen: true, userMenuOpen: false }">

    <!-- Sidebar -->
    <aside
        class="fixed inset-y-0 left-0 z-50 w-72 bg-gradient-to-b from-gray-900 to-gray-800 shadow-2xl transform transition-transform duration-300 ease-in-out flex flex-col"
        :class="{ '-translate-x-full lg:translate-x-0': !sidebarOpen, 'translate-x-0': sidebarOpen }"
    >
        <!-- Logo -->
        <div class="bg-gradient-to-r from-primary to-tertiary px-6 py-5 border-b border-white/10 flex-shrink-0">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo-estuaire-emploi.png') }}" alt="Estuaire Emploi" class="w-12 h-12 brightness-0 invert">
                <div>
                    <h1 class="text-white font-bold text-lg leading-tight">Estuaire Emploi</h1>
                    <p class="text-white/80 text-xs">Administration</p>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="py-6 flex-1 overflow-y-auto custom-scrollbar">
            @php
                $menuItems = \App\Services\NavigationService::getFilteredMenuItems(auth()->user());

                // Material Design Icons mapping
                $iconMapping = [
                    'fas fa-tachometer-alt' => 'mdi-view-dashboard',
                    'fas fa-building' => 'mdi-office-building',
                    'fas fa-briefcase' => 'mdi-briefcase',
                    'fas fa-file-alt' => 'mdi-file-document',
                    'fas fa-users' => 'mdi-account-group',
                    'fas fa-user-tie' => 'mdi-account-tie',
                    'fas fa-tags' => 'mdi-tag-multiple',
                    'fas fa-crown' => 'mdi-crown',
                    'fas fa-credit-card' => 'mdi-credit-card',
                    'fas fa-wallet' => 'mdi-wallet',
                    'fas fa-university' => 'mdi-bank',
                    'fas fa-star' => 'mdi-star',
                    'fas fa-puzzle-piece' => 'mdi-puzzle',
                    'fas fa-file-pdf' => 'mdi-file-pdf-box',
                    'fas fa-ad' => 'mdi-advertisements',
                    'fas fa-chart-line' => 'mdi-chart-line',
                    'fas fa-book' => 'mdi-book-open',
                    'fas fa-list' => 'mdi-format-list-bulleted',
                    'fas fa-user-shield' => 'mdi-shield-account',
                    'fas fa-bell' => 'mdi-bell',
                    'fas fa-cog' => 'mdi-cog',
                    'fas fa-wrench' => 'mdi-wrench',
                ];
            @endphp

            @foreach($menuItems as $section)
                <div class="mb-6">
                    <div class="px-6 mb-2">
                        <h3 class="text-white/50 text-xs font-bold uppercase tracking-wider">{{ $section['section'] }}</h3>
                    </div>
                    @foreach($section['items'] as $item)
                        @php
                            $isActive = isset($item['route']) && (request()->routeIs($item['route']) || request()->routeIs(str_replace('.index', '.*', $item['route'])));
                            $mdiIcon = $iconMapping[$item['icon']] ?? 'mdi-cog';
                        @endphp
                        <a href="{{ isset($item['url']) ? $item['url'] : route($item['route']) }}"
                           class="flex items-center gap-3 px-6 py-3 mx-3 rounded-lg text-white/70 hover:text-white hover:bg-white/10 transition-all duration-200 group {{ $isActive ? 'bg-gradient-to-r from-primary/20 to-tertiary/20 text-white border-l-4 border-primary ml-2' : '' }}"
                           @if(isset($item['external']) && $item['external']) target="_blank" @endif>
                            <i class="mdi {{ $mdiIcon }} text-xl {{ $isActive ? 'text-primary' : '' }}"></i>
                            <span class="flex-1 font-medium text-sm">{{ $item['name'] }}</span>

                            {{-- Badges --}}
                            @if(isset($item['route']) && $item['route'] === 'admin.applications.index' && isset($pendingApplications) && $pendingApplications > 0)
                                <span class="bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $pendingApplications }}</span>
                            @endif

                            @if(isset($item['route']) && $item['route'] === 'admin.subscriptions.index' && class_exists('\App\Models\Subscription'))
                                @php $activeSubscriptions = \App\Models\Subscription::where('status', 'active')->count(); @endphp
                                @if($activeSubscriptions > 0)
                                    <span class="bg-green-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $activeSubscriptions }}</span>
                                @endif
                            @endif

                            @if(isset($item['external']) && $item['external'])
                                <i class="mdi mdi-open-in-new text-xs opacity-50"></i>
                            @endif
                        </a>
                    @endforeach
                </div>
            @endforeach
        </nav>

        <!-- User Profile Section (Bottom) -->
        <div class="bg-gray-900/50 border-t border-white/10 p-4 flex-shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-primary to-tertiary flex items-center justify-center text-white font-bold text-sm">
                    {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-white text-sm font-medium truncate">{{ auth()->user()->name ?? 'Admin' }}</p>
                    <p class="text-white/60 text-xs truncate">{{ auth()->user()->email ?? '' }}</p>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="lg:ml-72">
        <!-- Header -->
        <header class="sticky top-0 z-40 bg-white border-b border-gray-200 shadow-sm">
            <div class="flex items-center justify-between px-4 lg:px-8 py-4">
                <!-- Left: Mobile menu button + Page title -->
                <div class="flex items-center gap-4">
                    <button
                        @click="sidebarOpen = !sidebarOpen"
                        class="lg:hidden text-gray-600 hover:text-gray-900 focus:outline-none"
                    >
                        <i class="mdi mdi-menu text-2xl"></i>
                    </button>

                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">@yield('title', 'Dashboard')</h2>
                        @hasSection('breadcrumb')
                            <div class="flex items-center gap-2 mt-1 text-sm text-gray-600">
                                @yield('breadcrumb')
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Right: Actions + User menu -->
                <div class="flex items-center gap-4">
                    @hasSection('header-actions')
                        @yield('header-actions')
                    @endif

                    <!-- Notifications -->
                    <div class="relative" x-data="{ notifOpen: false }">
                        <button
                            @click="notifOpen = !notifOpen"
                            @click.away="notifOpen = false"
                            class="relative text-gray-600 hover:text-gray-900 focus:outline-none"
                        >
                            <i class="mdi mdi-bell text-2xl"></i>
                            <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
                        </button>

                        <!-- Dropdown -->
                        <div
                            x-show="notifOpen"
                            x-cloak
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-200 z-50 max-h-96 overflow-y-auto"
                        >
                            <div class="px-4 py-3 border-b border-gray-200">
                                <h3 class="text-sm font-bold text-gray-900">Notifications</h3>
                            </div>

                            <div class="divide-y divide-gray-100">
                                <!-- Exemple de notification -->
                                <div class="px-4 py-3 hover:bg-gray-50 cursor-pointer">
                                    <div class="flex items-start gap-3">
                                        <div class="w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                                        <div class="flex-1">
                                            <p class="text-sm text-gray-900">Nouvelle candidature reçue</p>
                                            <p class="text-xs text-gray-500 mt-1">Il y a 2 heures</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="px-4 py-3 text-center text-sm text-gray-500">
                                    Aucune nouvelle notification
                                </div>
                            </div>

                            <div class="px-4 py-3 border-t border-gray-200 bg-gray-50">
                                <a href="#" class="text-sm text-primary hover:text-primary-dark font-medium">
                                    Voir toutes les notifications
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- User Menu -->
                    <div class="relative" x-data="{ open: false }">
                        <button
                            @click="open = !open"
                            @click.away="open = false"
                            class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors focus:outline-none"
                        >
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-primary to-tertiary flex items-center justify-center text-white font-bold text-sm">
                                {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                            </div>
                            <i class="mdi mdi-chevron-down text-gray-600" :class="{ 'rotate-180': open }"></i>
                        </button>

                        <!-- Dropdown -->
                        <div
                            x-show="open"
                            x-cloak
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50"
                        >
                            <div class="px-4 py-3 border-b border-gray-200">
                                <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name ?? 'Admin' }}</p>
                                <p class="text-xs text-gray-600 truncate">{{ auth()->user()->email ?? '' }}</p>
                            </div>

                            <a href="{{ route('admin.profile') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="mdi mdi-account-circle text-lg"></i>
                                Mon profil
                            </a>

                            <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="mdi mdi-cog text-lg"></i>
                                Paramètres
                            </a>

                            <hr class="my-1 border-gray-200">

                            <form method="POST" action="{{ route('admin.logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center gap-3 px-4 py-2 text-sm text-red-600 hover:bg-red-50 w-full text-left">
                                    <i class="mdi mdi-logout text-lg"></i>
                                    Déconnexion
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="p-4 lg:p-8">
            <!-- Alerts -->
            @if (session('success'))
                <div class="mb-6 flex items-center gap-3 bg-green-50 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-lg shadow-sm">
                    <i class="mdi mdi-check-circle text-xl"></i>
                    <div class="flex-1">{{ session('success') }}</div>
                    <button onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900">
                        <i class="mdi mdi-close text-xl"></i>
                    </button>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 flex items-center gap-3 bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-lg shadow-sm">
                    <i class="mdi mdi-alert-circle text-xl"></i>
                    <div class="flex-1">{{ session('error') }}</div>
                    <button onclick="this.parentElement.remove()" class="text-red-700 hover:text-red-900">
                        <i class="mdi mdi-close text-xl"></i>
                    </button>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-lg shadow-sm">
                    <div class="flex items-start gap-3">
                        <i class="mdi mdi-alert-circle text-xl mt-0.5"></i>
                        <div class="flex-1">
                            <strong class="font-semibold">Erreurs de validation :</strong>
                            <ul class="mt-2 list-disc list-inside space-y-1">
                                @foreach($errors->all() as $error)
                                    <li class="text-sm">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <button onclick="this.closest('[class*=bg-red]').remove()" class="text-red-700 hover:text-red-900">
                            <i class="mdi mdi-close text-xl"></i>
                        </button>
                    </div>
                </div>
            @endif

            <!-- Page Content -->
            @yield('content')
        </main>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div
        x-show="sidebarOpen"
        x-cloak
        @click="sidebarOpen = false"
        class="fixed inset-0 bg-black/50 z-40 lg:hidden"
        x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    ></div>

    <!-- Bulk Actions Toolbar -->
    <div id="bulkActionsToolbar" class="fixed bottom-4 left-1/2 -translate-x-1/2 bg-white shadow-lg rounded-lg border border-gray-200 px-6 py-3 hidden items-center gap-4 z-50" style="display: none;">
        <span class="text-sm font-medium text-gray-700">
            <span id="selectedCount">0</span> sélectionné(s)
        </span>
        <button onclick="bulkActions.deselectAll()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
            Désélectionner tout
        </button>
        <button onclick="bulkActions.confirmDelete()" class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors">
            <i class="mdi mdi-delete"></i> Supprimer
        </button>
    </div>

    <script>
        // Sidebar Scroll Position Manager
        (function() {
            const sidebar = document.querySelector('aside');
            if (!sidebar) return;

            // Restore scroll position
            const savedScrollPosition = sessionStorage.getItem('sidebarScrollPosition');
            if (savedScrollPosition) {
                sidebar.scrollTop = parseInt(savedScrollPosition, 10);
            }

            // Save scroll position
            const menuItems = sidebar.querySelectorAll('a');
            menuItems.forEach(item => {
                item.addEventListener('click', function() {
                    sessionStorage.setItem('sidebarScrollPosition', sidebar.scrollTop);
                });
            });

            let scrollTimeout;
            sidebar.addEventListener('scroll', function() {
                clearTimeout(scrollTimeout);
                scrollTimeout = setTimeout(() => {
                    sessionStorage.setItem('sidebarScrollPosition', sidebar.scrollTop);
                }, 100);
            });
        })();

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
                    row?.classList.add('bg-blue-50');
                } else {
                    this.selectedIds.delete(id);
                    row?.classList.remove('bg-blue-50');
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
                        row?.classList.add('bg-blue-50');
                    } else {
                        this.selectedIds.delete(checkbox.value);
                        row?.classList.remove('bg-blue-50');
                    }
                });

                this.updateUI();
            },

            deselectAll() {
                this.checkboxes.forEach(checkbox => {
                    checkbox.checked = false;
                    checkbox.closest('tr')?.classList.remove('bg-blue-50');
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
                    this.toolbar.style.display = 'flex';
                } else {
                    this.toolbar.style.display = 'none';
                }

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

                const idsInput = document.createElement('input');
                idsInput.type = 'hidden';
                idsInput.name = 'ids';
                idsInput.value = JSON.stringify(Array.from(this.selectedIds));
                form.appendChild(idsInput);

                form.submit();
            }
        };

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', () => {
            bulkActions.init();
        });

        // Bootstrap-like Tabs JavaScript
        document.addEventListener('DOMContentLoaded', function() {
            const tabLinks = document.querySelectorAll('[data-bs-toggle="tab"]');

            tabLinks.forEach(function(tabLink) {
                tabLink.addEventListener('click', function(e) {
                    e.preventDefault();

                    const targetId = this.getAttribute('href');
                    const targetPane = document.querySelector(targetId);

                    if (!targetPane) return;

                    const allTabs = this.closest('.nav-tabs').querySelectorAll('.nav-link');
                    const allPanes = targetPane.closest('.tab-content').querySelectorAll('.tab-pane');

                    allTabs.forEach(tab => tab.classList.remove('active'));
                    allPanes.forEach(pane => {
                        pane.classList.remove('show', 'active');
                    });

                    this.classList.add('active');
                    targetPane.classList.add('show', 'active');
                });
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
