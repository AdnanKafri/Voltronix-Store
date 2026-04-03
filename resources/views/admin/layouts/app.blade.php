<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Dashboard') - Voltronix Digital Store</title>
    <meta name="description" content="@yield('description', 'Voltronix Digital Store Admin Dashboard')">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=orbitron:400,700,900|poppins:300,400,500,600,700" rel="stylesheet" />
    @if(app()->getLocale() == 'ar')
        <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Arabic:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @endif

    <!-- Bootstrap CSS -->
    @if(app()->getLocale() == 'ar')
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    @else
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @endif
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --voltronix-primary: #007fff;
            --voltronix-secondary: #23efff;
            --voltronix-accent: #1a1a1a;
            --voltronix-light: #f8f9fa;
            --voltronix-gradient: linear-gradient(135deg, #007fff, #23efff);
            --sidebar-width: 280px;
            --topbar-height: 70px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: {{ app()->getLocale() == 'ar' ? "'Noto Sans Arabic'" : "'Poppins'" }}, sans-serif;
            background: var(--voltronix-light);
            overflow-x: hidden;
        }

        .admin-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .admin-sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 50%, #e2e8f0 100%);
            border-right: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            color: #334155;
            position: fixed;
            height: 100vh;
            left: 0;
            top: 0;
            z-index: 1000;
            overflow-y: auto;
            overflow-x: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            scrollbar-width: none;
        }
        
        .admin-sidebar::-webkit-scrollbar {
            display: none;
        }
        
        .admin-sidebar.collapsed {
            width: 80px;
        }
        
        .admin-sidebar.collapsed .sidebar-logo,
        .admin-sidebar.collapsed .sidebar-subtitle,
        .admin-sidebar.collapsed .nav-link span {
            opacity: 0;
            visibility: hidden;
        }
        
        .admin-sidebar.collapsed .nav-link {
            justify-content: center;
            align-items: center;
            padding: 1rem 0;
            margin: 0.25rem 0;
            border-radius: 0;
            position: relative;
            display: flex;
            width: 80px;
            height: 50px;
        }
        
        .admin-sidebar.collapsed .nav-link i {
            margin: 0 !important;
            width: 20px;
            min-width: 20px;
            font-size: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
        }
        
        .admin-sidebar.collapsed .nav-link .badge {
            position: absolute;
            top: 5px;
            right: 5px;
            min-width: 16px;
            height: 16px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.6rem;
            border-radius: 50%;
            z-index: 10;
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
            position: relative;
            background: linear-gradient(135deg, #007fff, #23efff);
            color: white;
            height: auto;
            min-height: 100px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        
        .sidebar-header::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 30% 30%, rgba(255, 255, 255, 0.1), transparent 70%);
            z-index: 1;
        }
        
        .sidebar-toggle {
            position: absolute;
            top: 1rem;
            right: 1rem;
            width: 32px;
            height: 32px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 8px;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1003;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        
        .sidebar-toggle:hover {
            transform: scale(1.05);
            background: rgba(255, 255, 255, 0.3);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        .sidebar-toggle i {
            transition: transform 0.3s ease;
            font-size: 0.9rem;
        }
        
        .admin-sidebar.collapsed .sidebar-toggle i {
            transform: rotate(180deg);
        }

        .sidebar-logo {
            font-family: 'Orbitron', monospace;
            font-weight: 900;
            font-size: 1.5rem;
            color: white;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
            z-index: 3;
            position: relative;
            margin-bottom: 0.25rem;
        }
        
        .sidebar-subtitle {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.8);
            z-index: 3;
            position: relative;
            font-weight: 500;
        }

        .sidebar-nav {
            padding: 2rem 0 1rem 0;
        }

        .nav-item {
            margin-bottom: 0.25rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.875rem 1.5rem;
            color: #64748b;
            text-decoration: none;
            transition: all 0.3s ease;
            border-radius: 0 25px 25px 0;
            margin: 0.125rem 0.5rem 0.125rem 0;
            position: relative;
            white-space: nowrap;
        }

        .nav-link:hover {
            color: #1e293b;
            background: linear-gradient(90deg, rgba(0, 127, 255, 0.1), rgba(35, 239, 255, 0.05));
            transform: translateX(4px);
            box-shadow: 0 2px 8px rgba(0, 127, 255, 0.15);
        }
        
        .admin-sidebar.collapsed .nav-link:hover {
            transform: none;
            background: radial-gradient(circle, rgba(0, 127, 255, 0.15), rgba(35, 239, 255, 0.1));
            border-radius: 50%;
            width: 50px;
            height: 50px;
            margin: 0.25rem auto;
        }
        
        .admin-sidebar.collapsed .nav-link.active {
            background: radial-gradient(circle, rgba(0, 127, 255, 0.2), rgba(35, 239, 255, 0.15));
            border-radius: 50%;
            width: 50px;
            height: 50px;
            margin: 0.25rem auto;
        }
        
        .admin-sidebar.collapsed .nav-link.active::before {
            display: none;
        }
        
        /* Perfect centering for collapsed sidebar */
        .admin-sidebar.collapsed .sidebar-nav {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 1rem 0;
        }
        
        .admin-sidebar.collapsed .nav-item {
            width: 100%;
            display: flex;
            justify-content: center;
            margin-bottom: 0.5rem;
        }
        
        .nav-link.active {
            color: #007fff;
            background: linear-gradient(90deg, rgba(0, 127, 255, 0.15), rgba(35, 239, 255, 0.1));
            font-weight: 600;
        }
        
        .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 24px;
            background: var(--voltronix-gradient);
            border-radius: 0 4px 4px 0;
        }

        .nav-link i {
            width: 20px;
            min-width: 20px;
            margin-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}: 0.75rem;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            color: #64748b;
            text-align: center;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        
        .nav-link:hover i,
        .nav-link.active i {
            color: #007fff;
        }
        
        .nav-link span {
            transition: all 0.3s ease;
        }
        
        .nav-link .badge {
            font-size: 0.7rem;
            padding: 0.25em 0.5em;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        
        .admin-sidebar.collapsed .nav-link .badge {
            position: absolute;
            top: 8px;
            right: 8px;
            min-width: 18px;
            height: 18px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.65rem;
            transform: scale(0.9);
        }

        /* Main Content */
        .admin-main {
            flex: 1;
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            max-width: calc(100vw - var(--sidebar-width));
            overflow-x: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .admin-main.sidebar-collapsed {
            margin-left: 80px;
            max-width: calc(100vw - 80px);
        }
        
        .admin-content {
            padding: 2rem;
            width: 100%;
            box-sizing: border-box;
        }
        
        @media (max-width: 768px) {
            .admin-sidebar {
                transform: translateX(-100%);
                z-index: 1050;
            }
            
            .admin-sidebar.show {
                transform: translateX(0);
            }
            
            .admin-main {
                margin-left: 0;
                max-width: 100vw;
            }
            
            .admin-main.sidebar-collapsed {
                margin-left: 0;
                max-width: 100vw;
            }
            
            .admin-content {
                padding: 1rem;
            }
            
            .sidebar-header {
                min-height: 80px;
                padding: 1rem;
            }
            
            .sidebar-logo {
                font-size: 1.25rem;
            }
            
            .sidebar-toggle {
                top: 0.75rem;
                right: 0.75rem;
                width: 28px;
                height: 28px;
            }
            
            .mobile-sidebar-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100vw;
                height: 100vh;
                background: rgba(0, 0, 0, 0.5);
                z-index: 1040;
                display: none;
            }
            
            .mobile-sidebar-overlay.show {
                display: block;
            }
        }

        /* Topbar */
        .admin-topbar {
            height: var(--topbar-height);
            background: white;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            position: sticky;
            top: 0;
            z-index: 999;
            width: 100%;
            box-sizing: border-box;
        }

        .topbar-title {
            font-family: 'Orbitron', monospace;
            font-weight: 700;
            color: var(--voltronix-accent);
            font-size: 1.25rem;
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .notification-btn {
            position: relative;
            background: none;
            border: none;
            color: var(--voltronix-accent);
            font-size: 1.25rem;
            padding: 0.5rem;
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .notification-btn:hover {
            background: var(--voltronix-light);
            color: var(--voltronix-primary);
        }

        .notification-badge {
            position: absolute;
            top: 0;
            right: 0;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .admin-profile {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 10px;
            transition: background 0.3s ease;
        }

        .admin-profile:hover {
            background: var(--voltronix-light);
        }

        .profile-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--voltronix-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }

        /* Content Area */
        .admin-content {
            padding: 1.5rem;
            max-width: 100%;
            overflow-x: hidden;
        }
        
        .container-fluid {
            max-width: 100%;
            padding: 0;
        }

        /* Cards */
        .admin-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 127, 255, 0.08);
            border: 1px solid rgba(0, 127, 255, 0.1);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .admin-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(0, 127, 255, 0.15);
        }

        .card-header-admin {
            background: linear-gradient(135deg, rgba(0, 127, 255, 0.05), rgba(35, 239, 255, 0.03));
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid rgba(0, 127, 255, 0.1);
        }

        .card-title-admin {
            font-family: 'Orbitron', monospace;
            font-weight: 700;
            color: var(--voltronix-accent);
            margin: 0;
            font-size: 1.1rem;
        }

        /* Stats Cards */
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 5px 20px rgba(0, 127, 255, 0.08);
            border: 1px solid rgba(0, 127, 255, 0.1);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--voltronix-gradient);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 127, 255, 0.15);
        }

        .stat-value {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--voltronix-primary);
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: #6c757d;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .stat-icon {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            font-size: 2rem;
            color: rgba(0, 127, 255, 0.2);
        }


        /* Utilities */
        .text-gradient {
            background: var(--voltronix-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .btn-voltronix {
            background: var(--voltronix-gradient);
            border: none;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-voltronix:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 127, 255, 0.3);
            color: white;
        }

        /* ===== ENHANCED UI FIXES ===== */
        
        /* Dashboard Cards RTL/LTR Fixes */
        .stat-card {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        [dir="rtl"] .stat-card {
            align-items: flex-end;
            text-align: right;
        }

        .stat-icon {
            position: relative;
            top: auto;
            right: auto;
            margin-bottom: 1rem;
            width: 60px;
            height: 60px;
            border-radius: 15px;
            background: var(--voltronix-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
        }

        [dir="rtl"] .stat-icon {
            margin-left: 0;
            margin-right: auto;
        }

        /* Enhanced Button Styling for Admin Tables */
        .btn-group .btn {
            border-radius: 8px !important;
            margin: 0 2px;
            transition: all 0.2s ease;
            font-size: 0.875rem;
            padding: 0.375rem 0.75rem;
            border-width: 1px;
            font-weight: 500;
        }

        .btn-group .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .btn-sm {
            padding: 0.25rem 0.5rem !important;
            font-size: 0.75rem !important;
            border-radius: 6px !important;
        }

        /* Enhanced Action Button Colors */
        .btn-outline-info {
            color: #17a2b8;
            border-color: #17a2b8;
            background: rgba(23, 162, 184, 0.1);
        }

        .btn-outline-info:hover {
            color: white;
            background-color: #17a2b8;
            border-color: #17a2b8;
        }

        .btn-outline-primary {
            color: var(--voltronix-primary);
            border-color: var(--voltronix-primary);
            background: rgba(0, 127, 255, 0.1);
        }

        .btn-outline-primary:hover {
            color: white;
            background-color: var(--voltronix-primary);
            border-color: var(--voltronix-primary);
        }

        .btn-outline-danger {
            color: #dc3545;
            border-color: #dc3545;
            background: rgba(220, 53, 69, 0.1);
        }

        .btn-outline-danger:hover {
            color: white;
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .btn-outline-secondary {
            color: #6c757d;
            border-color: #6c757d;
            background: rgba(108, 117, 125, 0.1);
        }

        .btn-outline-secondary:hover {
            color: white;
            background-color: #6c757d;
            border-color: #6c757d;
        }

        /* Filter Button Enhancements */
        .btn-outline-primary.w-100 {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        /* Card Voltronix Enhancements */
        .card-voltronix {
            background: linear-gradient(145deg, #ffffff, #f8f9fa);
            border: 1px solid rgba(0, 127, 255, 0.1);
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0, 127, 255, 0.08);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
        }

        .card-voltronix:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(0, 127, 255, 0.12);
        }

        /* Enhanced Table Styling */
        .table th {
            font-weight: 600;
            color: #1a1a1a;
            border-bottom: 2px solid rgba(0, 127, 255, 0.1);
            background: linear-gradient(145deg, #f8f9fa, #e9ecef);
            padding: 1rem 0.75rem;
        }

        .table tbody tr {
            transition: all 0.2s ease;
        }

        .table tbody tr:hover {
            background-color: rgba(0, 127, 255, 0.05);
        }

        /* Badge Enhancements */
        .badge {
            font-weight: 500;
            padding: 0.5em 0.75em;
            border-radius: 10px;
            font-size: 0.75rem;
        }

        .badge.bg-success {
            background: linear-gradient(135deg, #28a745, #20c997) !important;
        }

        .badge.bg-secondary {
            background: linear-gradient(135deg, #6c757d, #495057) !important;
        }

        .badge.bg-info {
            background: linear-gradient(135deg, #17a2b8, #007bff) !important;
        }

        .badge.bg-primary {
            background: linear-gradient(135deg, #007bff, #0056b3) !important;
        }

        .badge.bg-warning {
            background: linear-gradient(135deg, #ffc107, #e0a800) !important;
            color: #212529 !important;
        }

        .badge.bg-danger {
            background: linear-gradient(135deg, #dc3545, #c82333) !important;
        }

        /* Recent Reviews Section Fix */
        .recent-reviews-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            padding: 1rem;
            border-radius: 10px;
            transition: all 0.2s ease;
        }

        .recent-reviews-item:hover {
            background-color: rgba(0, 127, 255, 0.05);
        }

        .recent-reviews-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--voltronix-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            flex-shrink: 0;
        }

        .recent-reviews-content {
            flex: 1;
            min-width: 0;
        }

        .recent-reviews-meta {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 0.5rem;
        }

        [dir="rtl"] .recent-reviews-item {
            flex-direction: row-reverse;
        }

        [dir="rtl"] .recent-reviews-meta {
            flex-direction: row-reverse;
        }

        /* Deliveries Page Card Fixes */
        .delivery-stat-card {
            background: white;
            border: 1px solid rgba(0, 127, 255, 0.1);
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 5px 20px rgba(0, 127, 255, 0.08);
            transition: all 0.3s ease;
            height: 100%;
        }

        .delivery-stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(0, 127, 255, 0.12);
        }

        .delivery-stat-content {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        [dir="rtl"] .delivery-stat-content {
            flex-direction: row-reverse;
        }

        .delivery-stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .delivery-stat-text {
            flex: 1;
            min-width: 0;
        }

        .delivery-stat-text h6 {
            margin-bottom: 0.25rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: #6c757d;
        }

        .delivery-stat-text h4 {
            margin-bottom: 0;
            font-size: 1.75rem;
            font-weight: 700;
            color: #1a1a1a;
            font-family: 'Orbitron', monospace;
        }

        [dir="rtl"] .delivery-stat-text {
            text-align: right;
        }

        /* Title Orbitron Class */
        .title-orbitron {
            font-family: 'Orbitron', sans-serif;
            font-weight: 900;
            background: linear-gradient(135deg, #007fff, #23efff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .font-orbitron {
            font-family: 'Orbitron', sans-serif;
            font-weight: 700;
        }

        /* ===== ENHANCED ACTION BUTTONS & FILTERS ===== */
        
        /* Action Buttons in Tables */
        .action-buttons {
            display: flex;
            gap: 0.375rem;
            align-items: center;
            justify-content: center;
        }

        .action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            border: 1px solid;
            background: white;
            color: #6c757d;
            text-decoration: none;
            transition: all 0.2s ease;
            font-size: 0.875rem;
            position: relative;
            overflow: hidden;
        }

        .action-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: currentColor;
            opacity: 0;
            transition: opacity 0.2s ease;
        }

        .action-btn i {
            position: relative;
            z-index: 1;
        }

        /* Action Button Colors */
        .action-btn.btn-view {
            color: #17a2b8;
            border-color: #17a2b8;
        }

        .action-btn.btn-view:hover {
            color: white;
            background-color: #17a2b8;
            border-color: #17a2b8;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(23, 162, 184, 0.3);
        }

        .action-btn.btn-edit {
            color: #28a745;
            border-color: #28a745;
        }

        .action-btn.btn-edit:hover {
            color: white;
            background-color: #28a745;
            border-color: #28a745;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(40, 167, 69, 0.3);
        }

        .action-btn.btn-delete {
            color: #dc3545;
            border-color: #dc3545;
        }

        .action-btn.btn-delete:hover {
            color: white;
            background-color: #dc3545;
            border-color: #dc3545;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3);
        }

        .action-btn.btn-primary-action {
            color: var(--voltronix-primary);
            border-color: var(--voltronix-primary);
        }

        .action-btn.btn-primary-action:hover {
            color: white;
            background-color: var(--voltronix-primary);
            border-color: var(--voltronix-primary);
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 127, 255, 0.3);
        }

        .action-btn.btn-warning-action {
            color: #ffc107;
            border-color: #ffc107;
        }

        .action-btn.btn-warning-action:hover {
            color: #212529;
            background-color: #ffc107;
            border-color: #ffc107;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(255, 193, 7, 0.3);
        }

        /* Filter Section Enhancements */
        .filter-section {
            background: linear-gradient(145deg, #ffffff, #f8f9fa);
            border: 1px solid rgba(0, 127, 255, 0.1);
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 15px rgba(0, 127, 255, 0.05);
        }

        .filter-section .card-header {
            background: transparent;
            border: none;
            padding: 0 0 1rem 0;
        }

        .filter-section .card-body {
            padding: 0;
        }

        .filter-row {
            display: flex;
            gap: 1rem;
            align-items: end;
            flex-wrap: wrap;
        }

        .filter-col {
            flex: 1;
            min-width: 200px;
        }

        .filter-col.filter-col-auto {
            flex: 0 0 auto;
        }

        .filter-actions {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .filter-btn {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            border: 1px solid;
            background: white;
            color: #6c757d;
            text-decoration: none;
            transition: all 0.2s ease;
            font-size: 0.875rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            white-space: nowrap;
        }

        .filter-btn.btn-filter {
            color: var(--voltronix-primary);
            border-color: var(--voltronix-primary);
            background: rgba(0, 127, 255, 0.05);
        }

        .filter-btn.btn-filter:hover {
            color: white;
            background-color: var(--voltronix-primary);
            border-color: var(--voltronix-primary);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 127, 255, 0.3);
        }

        .filter-btn.btn-clear {
            color: #6c757d;
            border-color: #6c757d;
            background: rgba(108, 117, 125, 0.05);
        }

        .filter-btn.btn-clear:hover {
            color: white;
            background-color: #6c757d;
            border-color: #6c757d;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3);
        }

        /* Enhanced Form Controls */
        .form-control-enhanced {
            border: 1px solid #e0e6ed;
            border-radius: 8px;
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
            transition: all 0.2s ease;
            background: white;
        }

        .form-control-enhanced:focus {
            border-color: var(--voltronix-primary);
            box-shadow: 0 0 0 0.2rem rgba(0, 127, 255, 0.1);
            outline: none;
        }

        .form-label-enhanced {
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
        }

        /* Search Input Group */
        .search-input-group {
            position: relative;
            display: flex;
        }

        .search-input-group .form-control {
            border-radius: 8px 0 0 8px;
            border-right: none;
        }

        .search-input-group .search-btn {
            border-radius: 0 8px 8px 0;
            border-left: none;
            padding: 0.5rem 1rem;
            background: var(--voltronix-primary);
            border-color: var(--voltronix-primary);
            color: white;
        }

        .search-input-group .search-btn:hover {
            background: #0056b3;
            border-color: #0056b3;
        }

        /* Table Enhancements */
        .admin-table {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 127, 255, 0.05);
            border: 1px solid rgba(0, 127, 255, 0.1);
        }

        .admin-table .table {
            margin-bottom: 0;
        }

        .admin-table .table thead th {
            background: linear-gradient(145deg, #f8f9fa, #e9ecef);
            border: none;
            font-weight: 600;
            color: #374151;
            padding: 1rem 0.75rem;
            font-size: 0.875rem;
        }

        .admin-table .table tbody td {
            padding: 0.875rem 0.75rem;
            border-color: #f1f5f9;
            vertical-align: middle;
        }

        .admin-table .table tbody tr:hover {
            background-color: rgba(0, 127, 255, 0.02);
        }

        /* RTL Support */
        [dir="rtl"] .action-buttons {
            flex-direction: row-reverse;
        }

        [dir="rtl"] .filter-row {
            flex-direction: row-reverse;
        }

        [dir="rtl"] .filter-actions {
            flex-direction: row-reverse;
        }

        [dir="rtl"] .search-input-group .form-control {
            border-radius: 0 8px 8px 0;
            border-left: none;
            border-right: 1px solid #e0e6ed;
        }

        [dir="rtl"] .search-input-group .search-btn {
            border-radius: 8px 0 0 8px;
            border-right: none;
            border-left: 1px solid var(--voltronix-primary);
        }

        /* Responsive Fixes */
        @media (max-width: 768px) {
            .action-buttons {
                gap: 0.25rem;
            }

            .action-btn {
                width: 28px;
                height: 28px;
                font-size: 0.75rem;
            }

            .filter-row {
                flex-direction: column;
                gap: 1rem;
            }

            .filter-col {
                min-width: 100%;
            }

            .filter-actions {
                justify-content: stretch;
            }

            .filter-btn {
                flex: 1;
                justify-content: center;
            }

            .table-responsive {
                font-size: 0.875rem;
            }

            .delivery-stat-content {
                flex-direction: column;
                text-align: center;
                gap: 0.75rem;
            }

            [dir="rtl"] .delivery-stat-content {
                flex-direction: column;
            }

            .delivery-stat-text {
                text-align: center;
            }

            [dir="rtl"] .delivery-stat-text {
                text-align: center;
            }

            .stat-card {
                text-align: center;
                align-items: center;
            }

            [dir="rtl"] .stat-card {
                text-align: center;
                align-items: center;
            }
        }
    </style>

    @stack('styles')
</head>

<body>
    <div class="admin-wrapper">
        <!-- Mobile Sidebar Overlay -->
        <div class="mobile-sidebar-overlay" onclick="toggleSidebar()"></div>
        
        <!-- Sidebar -->
        @include('admin.partials.sidebar')

        <!-- Main Content -->
        <div class="admin-main">
            <!-- Topbar -->
            @include('admin.partials.topbar')

            <!-- Content -->
            <div class="admin-content">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JS -->
    <script>
        // Mobile sidebar toggle
        function toggleSidebar() {
            const sidebar = document.querySelector('.admin-sidebar');
            const overlay = document.querySelector('.mobile-sidebar-overlay');
            
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
        }
        
        // Desktop sidebar collapse/expand
        function toggleSidebarCollapse() {
            const sidebar = document.querySelector('.admin-sidebar');
            const mainContent = document.querySelector('.admin-main');
            const toggleBtn = document.querySelector('.sidebar-toggle i');
            
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('sidebar-collapsed');
            
            // Update toggle button icon
            if (sidebar.classList.contains('collapsed')) {
                toggleBtn.className = 'bi bi-chevron-double-right';
            } else {
                toggleBtn.className = 'bi bi-chevron-double-left';
            }
            
            // Save state to localStorage
            localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
        }
        
        // Initialize sidebar state from localStorage
        document.addEventListener('DOMContentLoaded', function() {
            const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            if (isCollapsed) {
                toggleSidebarCollapse();
            }
        });

        // SweetAlert2 configuration
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        // Global success/error handlers
        window.showSuccess = function(message) {
            Toast.fire({
                icon: 'success',
                title: message
            });
        };

        window.showError = function(message) {
            Toast.fire({
                icon: 'error',
                title: message
            });
        };

        // CSRF token setup for AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Show flash messages with SweetAlert2
        @if(session('success'))
            Toast.fire({
                icon: 'success',
                title: '{{ session('success') }}'
            });
        @endif

        @if(session('error'))
            Toast.fire({
                icon: 'error',
                title: '{{ session('error') }}'
            });
        @endif
    </script>

    @stack('scripts')
</body>
</html>
