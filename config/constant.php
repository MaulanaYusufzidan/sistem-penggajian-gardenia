<?php
/**
 * Constant Configuration
 * 
 * Konstanta yang digunakan di seluruh aplikasi
 */

// App Configuration
define('APP_NAME', 'Sistem Penggajian Gardenia');
define('APP_COMPANY', 'PT Gardenia Studio');
define('APP_VERSION', '1.0.0');
define('BASE_URL', 'http://localhost/sistem-penggajian-gardenia');
define('ASSETS_PATH', BASE_URL . '/assets');

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'db_penggajian');

// User Roles
define('ROLE_ADMIN', 'Admin');
define('ROLE_HRD', 'HRD');
define('ROLE_KARYAWAN', 'Karyawan');

// Employee Status
define('STATUS_AKTIF', 'Aktif');
define('STATUS_NONAKTIF', 'Nonaktif');
define('STATUS_CUTI', 'Cuti');

// Pagination
define('ITEMS_PER_PAGE', 10);

// Date Format
define('DATE_FORMAT', 'd-m-Y');
define('DATETIME_FORMAT', 'd-m-Y H:i:s');
define('MONTH_FORMAT', 'm');
define('YEAR_FORMAT', 'Y');
