<?php
/**
 * Auth Controller
 * 
 * Controller untuk menangani proses login, logout, dan autentikasi
 */

require_once dirname(dirname(__FILE__)) . '/config/database.php';
require_once dirname(dirname(__FILE__)) . '/config/constant.php';
require_once dirname(dirname(__FILE__)) . '/helper/Auth.php';
require_once dirname(dirname(__FILE__)) . '/helper/Validation.php';
require_once dirname(dirname(__FILE__)) . '/models/User.php';

class AuthController
{
    private $pdo;
    private $userModel;

    public function __construct()
    {
        global $pdo;
        $this->pdo = $pdo;
        $this->userModel = new User($pdo);
    }

    /**
     * Login process
     * 
     * @return void
     */
    public function login()
    {
        // Redirect jika sudah login
        if (Auth::isLoggedIn()) {
            $this->redirectByRole();
        }

        $error = '';
        $username = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            // Validasi input
            $validation = new Validation();
            $validation->required('username', $username, 'Username')
                ->required('password', $password, 'Password');

            if ($validation->hasError()) {
                $error = 'Username dan password harus diisi.';
            } else {
                // Check user
                $user = $this->userModel->getByUsername($username);

                if ($user && Auth::verifyPassword($password, $user['password'])) {
                    // Check jika user aktif
                    if (!$user['is_active']) {
                        $error = 'Akun Anda telah dinonaktifkan. Hubungi administrator.';
                    } else {
                        // Login berhasil
                        session_start();
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['username'] = $user['username'];
                        $_SESSION['email'] = $user['email'];
                        $_SESSION['role'] = $user['role'];
                        $_SESSION['last_activity'] = time();

                        // Update last login
                        $this->userModel->updateLastLogin($user['id']);

                        // Redirect by role
                        $this->redirectByRole();
                    }
                } else {
                    $error = 'Username atau password salah.';
                }
            }
        }

        // Load view login
        include dirname(dirname(__FILE__)) . '/views/auth/login.php';
    }

    /**
     * Logout process
     * 
     * @return void
     */
    public function logout()
    {
        Auth::logout();
    }

    /**
     * Redirect by user role
     * 
     * @return void
     */
    private function redirectByRole()
    {
        $role = Auth::userRole();

        switch ($role) {
            case ROLE_ADMIN:
                header('Location: ' . BASE_URL . '/views/admin/dashboard.php');
                break;
            case ROLE_HRD:
                header('Location: ' . BASE_URL . '/views/hrd/dashboard.php');
                break;
            case ROLE_KARYAWAN:
                header('Location: ' . BASE_URL . '/views/karyawan/dashboard.php');
                break;
            default:
                header('Location: ' . BASE_URL . '/public/login.php');
        }
        exit;
    }
}

// Handle request
if ($_SERVER['REQUEST_METHOD'] === 'GET' || $_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_GET['action'] ?? 'login';

    $controller = new AuthController();

    switch ($action) {
        case 'logout':
            $controller->logout();
            break;
        case 'login':
        default:
            $controller->login();
            break;
    }
}
