<?php
/**
 * Auth Helper
 * 
 * Helper untuk menangani authentikasi dan autorisasi
 */

class Auth
{
    /**
     * Check if user is logged in
     * 
     * @return boolean
     */
    public static function isLoggedIn()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['user_id']);
    }

    /**
     * Require login, redirect jika belum login
     * 
     * @return void
     */
    public static function requireLogin()
    {
        if (!self::isLoggedIn()) {
            header('Location: ' . BASE_URL . '/public/login.php');
            exit;
        }
    }

    /**
     * Require specific role
     * 
     * @param string $role
     * @return void
     */
    public static function requireRole($role)
    {
        self::requireLogin();
        
        if (self::userRole() !== $role) {
            header('Location: ' . BASE_URL . '/public/index.php');
            exit;
        }
    }

    /**
     * Get user ID
     * 
     * @return int|null
     */
    public static function userId()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return $_SESSION['user_id'] ?? null;
    }

    /**
     * Get user role
     * 
     * @return string|null
     */
    public static function userRole()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return $_SESSION['role'] ?? null;
    }

    /**
     * Check if user is admin
     * 
     * @return boolean
     */
    public static function isAdmin()
    {
        return self::userRole() === ROLE_ADMIN;
    }

    /**
     * Check if user is HRD
     * 
     * @return boolean
     */
    public static function isHrd()
    {
        return self::userRole() === ROLE_HRD;
    }

    /**
     * Check if user is Karyawan
     * 
     * @return boolean
     */
    public static function isKaryawan()
    {
        return self::userRole() === ROLE_KARYAWAN;
    }

    /**
     * Hash password
     * 
     * @param string $password
     * @return string
     */
    public static function hashPassword($password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    /**
     * Verify password
     * 
     * @param string $password
     * @param string $hash
     * @return boolean
     */
    public static function verifyPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }

    /**
     * Logout
     * 
     * @return void
     */
    public static function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        session_destroy();
        header('Location: ' . BASE_URL . '/public/login.php?logout=1');
        exit;
    }

    /**
     * Redirect to login
     * 
     * @return void
     */
    public static function redirectToLogin()
    {
        header('Location: ' . BASE_URL . '/public/login.php');
        exit;
    }

    /**
     * Get username
     * 
     * @return string|null
     */
    public static function username()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return $_SESSION['username'] ?? null;
    }

    /**
     * Get email
     * 
     * @return string|null
     */
    public static function email()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return $_SESSION['email'] ?? null;
    }
}
