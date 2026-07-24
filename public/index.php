<?php
/**
 * Main Entry Point
 * 
 * File index.php di folder public sebagai entry point utama aplikasi
 */

// Start session
session_start();

// Require config
require_once dirname(dirname(__FILE__)) . '/config/constant.php';
require_once dirname(dirname(__FILE__)) . '/helper/Auth.php';

// Check login
Auth::requireLogin();

// Redirect berdasarkan role
$role = Auth::userRole();

switch ($role) {
    case ROLE_ADMIN:
        include dirname(__FILE__) . '/../views/admin/dashboard.php';
        break;
    case ROLE_HRD:
        include dirname(__FILE__) . '/../views/hrd/dashboard.php';
        break;
    case ROLE_KARYAWAN:
        include dirname(__FILE__) . '/../views/karyawan/dashboard.php';
        break;
    default:
        Auth::redirectToLogin();
}
