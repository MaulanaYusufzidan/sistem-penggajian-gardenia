<?php
/**
 * Header Layout
 * 
 * Layout header yang digunakan di semua halaman dashboard
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once dirname(dirname(__FILE__)) . '/config/constant.php';
require_once dirname(dirname(__FILE__)) . '/helper/Auth.php';

// Pastikan user sudah login
Auth::requireLogin();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?><?php echo APP_NAME; ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo ASSETS_PATH; ?>/css/style.css">
    
    <style>
        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --sidebar-width: 280px;
            --header-height: 60px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            overflow-x: hidden;
        }

        /* Header Styles */
        .header {
            background: white;
            height: var(--header-height);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            z-index: 1020;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .toggle-sidebar {
            background: none;
            border: none;
            font-size: 22px;
            color: var(--primary-color);
            cursor: pointer;
            display: none;
        }

        .header-title {
            font-size: 18px;
            font-weight: 600;
            color: #333;
            margin: 0;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
            border-left: 1px solid #eee;
            padding-left: 20px;
        }

        .user-avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 16px;
        }

        .user-details {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-size: 13px;
            font-weight: 600;
            color: #333;
        }

        .user-role {
            font-size: 11px;
            color: #999;
            text-transform: uppercase;
        }

        .dropdown-menu {
            min-width: 200px;
            border-radius: 8px;
            border: none;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
        }

        .dropdown-item {
            font-size: 14px;
            padding: 10px 15px;
            color: #333;
            transition: all 0.3s ease;
        }

        .dropdown-item:hover {
            background-color: #f5f7fa;
            color: var(--primary-color);
        }

        .dropdown-item.logout {
            border-top: 1px solid #eee;
            color: #dc3545;
        }

        .dropdown-item.logout:hover {
            background-color: #fff5f5;
        }

        /* Sidebar Styles */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, #2c3e50 0%, #34495e 100%);
            position: fixed;
            left: 0;
            top: 0;
            overflow-y: auto;
            z-index: 1030;
            padding-top: 20px;
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 0 20px;
            margin-bottom: 30px;
            text-decoration: none;
            color: white;
            font-weight: 700;
            font-size: 18px;
        }

        .sidebar-logo-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            color: white;
        }

        .sidebar-menu {
            list-style: none;
        }

        .sidebar-menu-item {
            margin: 0;
        }

        .sidebar-menu-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            color: #bdc3c7;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .sidebar-menu-link:hover {
            background-color: rgba(255, 255, 255, 0.05);
            color: white;
            border-left-color: var(--primary-color);
        }

        .sidebar-menu-link.active {
            background-color: rgba(102, 126, 234, 0.1);
            color: var(--primary-color);
            border-left-color: var(--primary-color);
        }

        .sidebar-menu-link i {
            width: 20px;
            text-align: center;
        }

        /* Main Container */
        .main-container {
            margin-left: var(--sidebar-width);
            margin-top: var(--header-height);
            padding: 30px;
            min-height: calc(100vh - var(--header-height));
        }

        /* Responsive */
        @media (max-width: 768px) {
            :root {
                --sidebar-width: 0;
                --header-height: 60px;
            }

            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
                z-index: 1025;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .header {
                left: 0;
            }

            .toggle-sidebar {
                display: block;
            }

            .main-container {
                margin-left: 0;
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <a href="<?php echo BASE_URL; ?>/public/index.php" class="sidebar-logo">
            <div class="sidebar-logo-icon">
                <i class="fas fa-briefcase"></i>
            </div>
            <span>Gardenia</span>
        </a>

        <ul class="sidebar-menu">
