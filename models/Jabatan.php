<?php
/**
 * Jabatan Model
 * 
 * Model untuk mengelola data jabatan
 */

class Jabatan
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Get semua jabatan
     * 
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function getAll($page = 1, $limit = ITEMS_PER_PAGE)
    {
        $offset = ($page - 1) * $limit;

        $stmt = $this->pdo->prepare("
            SELECT * FROM jabatan
            ORDER BY nama_jabatan ASC
            LIMIT ? OFFSET ?
        ");
        $stmt->execute([$limit, $offset]);
        return $stmt->fetchAll();
    }

    /**
     * Get jabatan berdasarkan ID
     * 
     * @param int $id
     * @return array|false
     */
    public function getById($id)
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM jabatan WHERE id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Get total jabatan
     * 
     * @return int
     */
    public function getTotal()
    {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) as total FROM jabatan
        ");
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['total'];
    }

    /**
     * Search jabatan
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
            SELECT * FROM jabatan
            WHERE nama_jabatan LIKE ? OR deskripsi LIKE ?
            ORDER BY nama_jabatan ASC
            LIMIT ? OFFSET ?
        ");
        $stmt->execute([$keyword, $keyword, $limit, $offset]);
        return $stmt->fetchAll();
    }

    /**
     * Search total jabatan
     * 
     * @param string $keyword
     * @return int
     */
    public function searchTotal($keyword)
    {
        $keyword = "%$keyword%";

        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) as total FROM jabatan
            WHERE nama_jabatan LIKE ? OR deskripsi LIKE ?
        ");
        $stmt->execute([$keyword, $keyword]);
        $result = $stmt->fetch();
        return $result['total'];
    }

    /**
     * Insert jabatan baru
     * 
     * @param array $data
     * @return int|false
     */
    public function create($data)
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO jabatan (nama_jabatan, deskripsi, gaji_pokok, tunjangan)
            VALUES (?, ?, ?, ?)
        ");

        return $stmt->execute([
            $data['nama_jabatan'],
            $data['deskripsi'] ?? null,
            $data['gaji_pokok'] ?? 0,
            $data['tunjangan'] ?? 0
        ]) ? $this->pdo->lastInsertId() : false;
    }

    /**
     * Update jabatan
     * 
     * @param int $id
     * @param array $data
     * @return boolean
     */
    public function update($id, $data)
    {
        $stmt = $this->pdo->prepare("
            UPDATE jabatan 
            SET nama_jabatan = ?, deskripsi = ?, gaji_pokok = ?, tunjangan = ?
            WHERE id = ?
        ");

        return $stmt->execute([
            $data['nama_jabatan'],
            $data['deskripsi'] ?? null,
            $data['gaji_pokok'] ?? 0,
            $data['tunjangan'] ?? 0,
            $id
        ]);
    }

    /**
     * Delete jabatan
     * 
     * @param int $id
     * @return boolean
     */
    public function delete($id)
    {
        $stmt = $this->pdo->prepare("
            DELETE FROM jabatan WHERE id = ?
        ");
        return $stmt->execute([$id]);
    }

    /**
     * Check nama jabatan exist
     * 
     * @param string $nama
     * @param int $excludeId
     * @return boolean
     */
    public function namaExists($nama, $excludeId = null)
    {
        if ($excludeId) {
            $stmt = $this->pdo->prepare("
                SELECT COUNT(*) as count FROM jabatan WHERE nama_jabatan = ? AND id != ?
            ");
            $stmt->execute([$nama, $excludeId]);
        } else {
            $stmt = $this->pdo->prepare("
                SELECT COUNT(*) as count FROM jabatan WHERE nama_jabatan = ?
            ");
            $stmt->execute([$nama]);
        }

        $result = $stmt->fetch();
        return $result['count'] > 0;
    }

    /**
     * Get jabatan list untuk select
     * 
     * @return array
     */
    public function getListForSelect()
    {
        $stmt = $this->pdo->prepare("
            SELECT id, nama_jabatan, gaji_pokok, tunjangan FROM jabatan
            ORDER BY nama_jabatan ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
