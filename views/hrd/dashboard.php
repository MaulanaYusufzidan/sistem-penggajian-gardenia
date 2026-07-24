<?php
/**
 * HRD Dashboard
 * 
 * Dashboard untuk HRD
 */

session_start();

require_once dirname(dirname(dirname(__FILE__))) . '/config/database.php';
require_once dirname(dirname(dirname(__FILE__))) . '/config/constant.php';
require_once dirname(dirname(dirname(__FILE__))) . '/helper/Auth.php';
require_once dirname(dirname(dirname(__FILE__))) . '/models/Karyawan.php';
require_once dirname(dirname(dirname(__FILE__))) . '/models/Lembur.php';
require_once dirname(dirname(dirname(__FILE__))) . '/models/Potongan.php';
require_once dirname(dirname(dirname(__FILE__))) . '/models/Penggajian.php';

// Check permission
Auth::requireRole(ROLE_HRD);

// Init models
$karyawanModel = new Karyawan($pdo);
$lemburModel = new Lembur($pdo);
$potonganModel = new Potongan($pdo);
$penggajianModel = new Penggajian($pdo);

// Get statistics
$totalKaryawan = $karyawanModel->getTotal();
$totalLembur = $lemburModel->getTotal();
$totalPotongan = $potonganModel->getTotal();
$totalPenggajian = $penggajianModel->getTotal();

// Page title
$page_title = 'Dashboard HRD';

// Include header
include dirname(__FILE__) . '/../layout/header.php';
include dirname(__FILE__) . '/../layout/sidebar.php';
?>

        <div class="container-fluid">
            <!-- Page Header -->
            <div class="row mb-4">
                <div class="col-12">
                    <h2 class="mb-0">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>! 👋</h2>
                    <p class="text-muted">You're logged in as HRD</p>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <!-- Total Karyawan Card -->
                <div class="col-md-6 col-lg-3 mb-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h6 class="text-muted mb-1">Total Karyawan</h6>
                                    <h2 class="mb-0"><?php echo $totalKaryawan; ?></h2>
                                </div>
                                <div class="stats-icon" style="width: 50px; height: 50px; background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-users" style="color: white; font-size: 24px;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Lembur Card -->
                <div class="col-md-6 col-lg-3 mb-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h6 class="text-muted mb-1">Data Lembur</h6>
                                    <h2 class="mb-0"><?php echo $totalLembur; ?></h2>
                                </div>
                                <div class="stats-icon" style="width: 50px; height: 50px; background: linear-gradient(135deg, #f093fb, #f5576c); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-clock" style="color: white; font-size: 24px;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Potongan Card -->
                <div class="col-md-6 col-lg-3 mb-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h6 class="text-muted mb-1">Data Potongan</h6>
                                    <h2 class="mb-0"><?php echo $totalPotongan; ?></h2>
                                </div>
                                <div class="stats-icon" style="width: 50px; height: 50px; background: linear-gradient(135deg, #4facfe, #00f2fe); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-cut" style="color: white; font-size: 24px;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Penggajian Card -->
                <div class="col-md-6 col-lg-3 mb-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h6 class="text-muted mb-1">Data Penggajian</h6>
                                    <h2 class="mb-0"><?php echo $totalPenggajian; ?></h2>
                                </div>
                                <div class="stats-icon" style="width: 50px; height: 50px; background: linear-gradient(135deg, #fa709a, #fee140); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-money-bill" style="color: white; font-size: 24px;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="mb-0">Aksi Cepat</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 mb-2">
                                    <a href="<?php echo BASE_URL; ?>/views/hrd/karyawan/index.php" class="btn btn-light w-100 text-start">
                                        <i class="fas fa-id-card me-2"></i> Kelola Karyawan
                                    </a>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <a href="<?php echo BASE_URL; ?>/views/hrd/lembur/index.php" class="btn btn-light w-100 text-start">
                                        <i class="fas fa-clock me-2"></i> Kelola Lembur
                                    </a>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <a href="<?php echo BASE_URL; ?>/views/hrd/potongan/index.php" class="btn btn-light w-100 text-start">
                                        <i class="fas fa-cut me-2"></i> Kelola Potongan
                                    </a>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <a href="<?php echo BASE_URL; ?>/views/hrd/penggajian/index.php" class="btn btn-light w-100 text-start">
                                        <i class="fas fa-money-bill me-2"></i> Kelola Penggajian
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activities -->
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="mb-0">Informasi Sistem</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-2">
                                        <strong>Nama Aplikasi:</strong> <?php echo APP_NAME; ?>
                                    </p>
                                    <p class="mb-2">
                                        <strong>Perusahaan:</strong> <?php echo APP_COMPANY; ?>
                                    </p>
                                    <p class="mb-2">
                                        <strong>Versi:</strong> <?php echo APP_VERSION; ?>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-2">
                                        <strong>Waktu Server:</strong> <?php echo date('d-m-Y H:i:s'); ?>
                                    </p>
                                    <p class="mb-2">
                                        <strong>Username Anda:</strong> <?php echo htmlspecialchars($_SESSION['username']); ?>
                                    </p>
                                    <p class="mb-2">
                                        <strong>Email Anda:</strong> <?php echo htmlspecialchars($_SESSION['email']); ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle sidebar on mobile
        document.getElementById('toggleSidebar')?.addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.getElementById('toggleSidebar');
            
            if (sidebar && toggle && !sidebar.contains(event.target) && !toggle.contains(event.target)) {
                sidebar.classList.remove('show');
            }
        });
    </script>
</body>
</html>
