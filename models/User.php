<?php
/**
 * User Model
 * 
 * Model untuk mengelola data pengguna
 */

class User
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Get semua user
     * 
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function getAll($page = 1, $limit = ITEMS_PER_PAGE)
    {
        $offset = ($page - 1) * $limit;

        $stmt = $this->pdo->prepare("
            SELECT * FROM users
            ORDER BY username ASC
            LIMIT ? OFFSET ?
        ");
        $stmt->execute([$limit, $offset]);
        return $stmt->fetchAll();
    }

    /**
     * Get user berdasarkan ID
     * 
     * @param int $id
     * @return array|false
     */
    public function getById($id)
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM users WHERE id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Get user berdasarkan username
     * 
     * @param string $username
     * @return array|false
     */
    public function getByUsername($username)
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM users WHERE username = ?
        ");
        $stmt->execute([$username]);
        return $stmt->fetch();
    }

    /**
     * Get user berdasarkan email
     * 
     * @param string $email
     * @return array|false
     */
    public function getByEmail($email)
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM users WHERE email = ?
        ");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    /**
     * Get total user
     * 
     * @return int
     */
    public function getTotal()
    {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) as total FROM users
        ");
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['total'];
    }

    /**
     * Search user
     * 
     * @param string $keyword
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function search($keyword, $page = 1, $limit = ITEMS_PER_PAGE)
    {
        $offset = ($page - 1) * $limit;
        $keyword = "%$keyword%";

        $stmt = $this->pdo->prepare("
            SELECT * FROM users
            WHERE username LIKE ? OR email LIKE ?
            ORDER BY username ASC
            LIMIT ? OFFSET ?
        ");
        $stmt->execute([$keyword, $keyword, $limit, $offset]);
        return $stmt->fetchAll();
    }

    /**
     * Search total user
     * 
     * @param string $keyword
     * @return int
     */
    public function searchTotal($keyword)
    {
        $keyword = "%$keyword%";

        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) as total FROM users
            WHERE username LIKE ? OR email LIKE ?
        ");
        $stmt->execute([$keyword, $keyword]);
        $result = $stmt->fetch();
        return $result['total'];
    }

    /**
     * Insert user baru
     * 
     * @param array $data
     * @return int|false
     */
    public function create($data)
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO users (username, email, password, role, is_active)
            VALUES (?, ?, ?, ?, ?)
        ");

        return $stmt->execute([
            $data['username'],
            $data['email'],
            $data['password'],
            $data['role'] ?? ROLE_KARYAWAN,
            $data['is_active'] ?? 1
        ]) ? $this->pdo->lastInsertId() : false;
    }

    /**
     * Update user
     * 
     * @param int $id
     * @param array $data
     * @return boolean
     */
    public function update($id, $data)
    {
        $stmt = $this->pdo->prepare("
            UPDATE users 
            SET username = ?, email = ?, role = ?, is_active = ?
            WHERE id = ?
        ");

        return $stmt->execute([
            $data['username'],
            $data['email'],
            $data['role'] ?? ROLE_KARYAWAN,
            $data['is_active'] ?? 1,
            $id
        ]);
    }

    /**
     * Update password user
     * 
     * @param int $id
     * @param string $password
     * @return boolean
     */
    public function updatePassword($id, $password)
    {
        $stmt = $this->pdo->prepare("
            UPDATE users SET password = ? WHERE id = ?
        ");
        return $stmt->execute([$password, $id]);
    }

    /**
     * Delete user
     * 
     * @param int $id
     * @return boolean
     */
    public function delete($id)
    {
        $stmt = $this->pdo->prepare("
            DELETE FROM users WHERE id = ?
        ");
        return $stmt->execute([$id]);
    }

    /**
     * Update last login
     * 
     * @param int $id
     * @return boolean
     */
    public function updateLastLogin($id)
    {
        $stmt = $this->pdo->prepare("
            UPDATE users SET last_login = NOW() WHERE id = ?
        ");
        return $stmt->execute([$id]);
    }

    /**
     * Check username exist
     * 
     * @param string $username
     * @param int $excludeId
     * @return boolean
     */
    public function usernameExists($username, $excludeId = null)
    {
        if ($excludeId) {
            $stmt = $this->pdo->prepare("
                SELECT COUNT(*) as count FROM users WHERE username = ? AND id != ?
            ");
            $stmt->execute([$username, $excludeId]);
        } else {
            $stmt = $this->pdo->prepare("
                SELECT COUNT(*) as count FROM users WHERE username = ?
            ");
            $stmt->execute([$username]);
        }

        $result = $stmt->fetch();
        return $result['count'] > 0;
    }

    /**
     * Check email exist
     * 
     * @param string $email
     * @param int $excludeId
     * @return boolean
     */
    public function emailExists($email, $excludeId = null)
    {
        if ($excludeId) {
            $stmt = $this->pdo->prepare("
                SELECT COUNT(*) as count FROM users WHERE email = ? AND id != ?
            ");
            $stmt->execute([$email, $excludeId]);
        } else {
            $stmt = $this->pdo->prepare("
                SELECT COUNT(*) as count FROM users WHERE email = ?
            ");
            $stmt->execute([$email]);
        }

        $result = $stmt->fetch();
        return $result['count'] > 0;
    }

    /**
     * Get user list untuk select by role
     * 
     * @param string $role
     * @return array
     */
    public function getListByRole($role)
    {
        $stmt = $this->pdo->prepare("
            SELECT id, username FROM users
            WHERE role = ? AND is_active = 1
            ORDER BY username ASC
        ");
        $stmt->execute([$role]);
        return $stmt->fetchAll();
    }
}
