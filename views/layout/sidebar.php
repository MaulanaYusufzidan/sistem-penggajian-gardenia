<?php
/**
 * Sidebar Layout
 * 
 * Menu sidebar dinamis berdasarkan role user
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once dirname(dirname(__FILE__)) . '/config/constant.php';
require_once dirname(dirname(__FILE__)) . '/helper/Auth.php';

$current_page = basename($_SERVER['PHP_SELF']);
$role = Auth::userRole();
?>

<?php if (Auth::isAdmin()): ?>
    <!-- Admin Menu -->
    <li class="sidebar-menu-item">
        <a href="<?php echo BASE_URL; ?>/views/admin/dashboard.php" class="sidebar-menu-link <?php echo $current_page === 'dashboard.php' ? 'active' : ''; ?>">
            <i class="fas fa-home"></i>
            <span>Dashboard</span>
        </a>
    </li>
    <li class="sidebar-menu-item">
        <a href="<?php echo BASE_URL; ?>/views/admin/user/index.php" class="sidebar-menu-link <?php echo strpos($current_page, 'user') !== false ? 'active' : ''; ?>">
            <i class="fas fa-users"></i>
            <span>Data Pengguna</span>
        </a>
    </li>
    <li class="sidebar-menu-item">
        <a href="<?php echo BASE_URL; ?>/views/admin/divisi/index.php" class="sidebar-menu-link <?php echo strpos($current_page, 'divisi') !== false ? 'active' : ''; ?>">
            <i class="fas fa-sitemap"></i>
            <span>Data Divisi</span>
        </a>
    </li>
    <li class="sidebar-menu-item">
        <a href="<?php echo BASE_URL; ?>/views/admin/jabatan/index.php" class="sidebar-menu-link <?php echo strpos($current_page, 'jabatan') !== false ? 'active' : ''; ?>">
            <i class="fas fa-briefcase"></i>
            <span>Data Jabatan</span>
        </a>
    </li>
    <li class="sidebar-menu-item">
        <a href="<?php echo BASE_URL; ?>/views/admin/karyawan/index.php" class="sidebar-menu-link <?php echo strpos($current_page, 'karyawan') !== false ? 'active' : ''; ?>">
            <i class="fas fa-id-card"></i>
            <span>Data Karyawan</span>
        </a>
    </li>
    <li class="sidebar-menu-item">
        <a href="<?php echo BASE_URL; ?>/views/admin/laporan/index.php" class="sidebar-menu-link <?php echo strpos($current_page, 'laporan') !== false ? 'active' : ''; ?>">
            <i class="fas fa-file-pdf"></i>
            <span>Laporan Penggajian</span>
        </a>
    </li>

<?php elseif (Auth::isHrd()): ?>
    <!-- HRD Menu -->
    <li class="sidebar-menu-item">
        <a href="<?php echo BASE_URL; ?>/views/hrd/dashboard.php" class="sidebar-menu-link <?php echo $current_page === 'dashboard.php' ? 'active' : ''; ?>">
            <i class="fas fa-home"></i>
            <span>Dashboard</span>
        </a>
    </li>
    <li class="sidebar-menu-item">
        <a href="<?php echo BASE_URL; ?>/views/hrd/karyawan/index.php" class="sidebar-menu-link <?php echo strpos($current_page, 'karyawan') !== false ? 'active' : ''; ?>">
            <i class="fas fa-id-card"></i>
            <span>Data Karyawan</span>
        </a>
    </li>
    <li class="sidebar-menu-item">
        <a href="<?php echo BASE_URL; ?>/views/hrd/lembur/index.php" class="sidebar-menu-link <?php echo strpos($current_page, 'lembur') !== false ? 'active' : ''; ?>">
            <i class="fas fa-clock"></i>
            <span>Data Lembur</span>
        </a>
    </li>
    <li class="sidebar-menu-item">
        <a href="<?php echo BASE_URL; ?>/views/hrd/potongan/index.php" class="sidebar-menu-link <?php echo strpos($current_page, 'potongan') !== false ? 'active' : ''; ?>">
            <i class="fas fa-cut"></i>
            <span>Data Potongan</span>
        </a>
    </li>
    <li class="sidebar-menu-item">
        <a href="<?php echo BASE_URL; ?>/views/hrd/penggajian/index.php" class="sidebar-menu-link <?php echo strpos($current_page, 'penggajian') !== false ? 'active' : ''; ?>">
            <i class="fas fa-money-bill"></i>
            <span>Penggajian</span>
        </a>
    </li>
    <li class="sidebar-menu-item">
        <a href="<?php echo BASE_URL; ?>/views/hrd/laporan/index.php" class="sidebar-menu-link <?php echo strpos($current_page, 'laporan') !== false ? 'active' : ''; ?>">
            <i class="fas fa-file-pdf"></i>
            <span>Laporan</span>
        </a>
    </li>

<?php elseif (Auth::isKaryawan()): ?>
    <!-- Karyawan Menu -->
    <li class="sidebar-menu-item">
        <a href="<?php echo BASE_URL; ?>/views/karyawan/dashboard.php" class="sidebar-menu-link <?php echo $current_page === 'dashboard.php' ? 'active' : ''; ?>">
            <i class="fas fa-home"></i>
            <span>Dashboard</span>
        </a>
    </li>
    <li class="sidebar-menu-item">
        <a href="<?php echo BASE_URL; ?>/views/karyawan/profil.php" class="sidebar-menu-link <?php echo $current_page === 'profil.php' ? 'active' : ''; ?>">
            <i class="fas fa-user"></i>
            <span>Profil</span>
        </a>
    </li>
    <li class="sidebar-menu-item">
        <a href="<?php echo BASE_URL; ?>/views/karyawan/gaji/riwayat.php" class="sidebar-menu-link <?php echo strpos($current_page, 'riwayat') !== false ? 'active' : ''; ?>">
            <i class="fas fa-history"></i>
            <span>Riwayat Gaji</span>
        </a>
    </li>
<?php endif; ?>
