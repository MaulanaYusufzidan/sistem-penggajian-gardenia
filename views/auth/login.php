<?php
/**
 * Login Page
 * 
 * Halaman login untuk semua user (Admin, HRD, Karyawan)
 */

// Start session jika belum
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Require config
require_once dirname(dirname(dirname(__FILE__))) . '/config/constant.php';

// Redirect jika sudah login
if (isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . '/controllers/AuthController.php?action=login');
    exit;
}

// Variable untuk tampilan
$error = $error ?? '';
$username = $username ?? '';
$logout_message = isset($_GET['logout']) ? 'Anda telah berhasil logout.' : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?php echo APP_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-container {
            width: 100%;
            max-width: 400px;
        }

        .login-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }

        .login-header h1 {
            font-size: 28px;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .login-header p {
            font-size: 14px;
            margin: 0;
            opacity: 0.9;
        }

        .login-body {
            padding: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-control {
            border: 1px solid #e0e0e0;
            border-radius: 5px;
            padding: 12px 15px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .form-label {
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 8px;
            color: #333;
        }

        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 12px;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .alert {
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .login-footer {
            text-align: center;
            padding: 20px;
            background: #f8f9fa;
            font-size: 12px;
            color: #666;
        }

        .icon {
            font-size: 48px;
            margin-bottom: 10px;
        }

        .demo-credentials {
            background: #f0f4ff;
            border-left: 4px solid #667eea;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
            font-size: 13px;
        }

        .demo-credentials h6 {
            font-weight: 600;
            margin-bottom: 10px;
            color: #667eea;
        }

        .demo-item {
            margin-bottom: 8px;
            line-height: 1.5;
        }

        .demo-item strong {
            color: #333;
        }

        @media (max-width: 480px) {
            .login-container {
                max-width: 100%;
                margin: 15px;
            }

            .login-header h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <!-- Header -->
            <div class="login-header">
                <div class="icon">🏢</div>
                <h1><?php echo APP_NAME; ?></h1>
                <p><?php echo APP_COMPANY; ?></p>
            </div>

            <!-- Body -->
            <div class="login-body">
                <!-- Success Message -->
                <?php if (!empty($logout_message)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle"></i> <?php echo $logout_message; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <!-- Error Message -->
                <?php if (!empty($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle"></i> <?php echo $error; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <!-- Login Form -->
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required autofocus>
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>

                    <button type="submit" class="btn-login">Login</button>
                </form>

                <!-- Demo Credentials -->
                <div class="demo-credentials">
                    <h6>Demo Credentials:</h6>
                    <div class="demo-item">
                        <strong>Admin:</strong> admin / admin123
                    </div>
                    <div class="demo-item">
                        <strong>HRD:</strong> hrd / hrd123
                    </div>
                    <div class="demo-item">
                        <strong>Karyawan:</strong> karyawan1 / karyawan123
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="login-footer">
                <p>&copy; 2024 PT Gardenia Studio. All rights reserved.</p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
