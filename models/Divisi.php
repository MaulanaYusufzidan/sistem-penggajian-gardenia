<?php
/**
 * Divisi Model
 * 
 * Model untuk mengelola data divisi
 */

class Divisi
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Get semua divisi
     * 
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function getAll($page = 1, $limit = ITEMS_PER_PAGE)
    {
        $offset = ($page - 1) * $limit;

        $stmt = $this->pdo->prepare("
            SELECT * FROM divisi
            ORDER BY nama_divisi ASC
            LIMIT ? OFFSET ?
        ");
        $stmt->execute([$limit, $offset]);
        return $stmt->fetchAll();
    }

    /**
     * Get divisi berdasarkan ID
     * 
     * @param int $id
     * @return array|false
     */
    public function getById($id)
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM divisi WHERE id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Get total divisi
     * 
     * @return int
     */
    public function getTotal()
    {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) as total FROM divisi
        ");
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['total'];
    }

    /**
     * Search divisi
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
            SELECT * FROM divisi
            WHERE nama_divisi LIKE ? OR deskripsi LIKE ?
            ORDER BY nama_divisi ASC
            LIMIT ? OFFSET ?
        ");
        $stmt->execute([$keyword, $keyword, $limit, $offset]);
        return $stmt->fetchAll();
    }

    /**
     * Search total divisi
     * 
     * @param string $keyword
     * @return int
     */
    public function searchTotal($keyword)
    {
        $keyword = "%$keyword%";

        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) as total FROM divisi
            WHERE nama_divisi LIKE ? OR deskripsi LIKE ?
        ");
        $stmt->execute([$keyword, $keyword]);
        $result = $stmt->fetch();
        return $result['total'];
    }

    /**
     * Insert divisi baru
     * 
     * @param array $data
     * @return int|false
     */
    public function create($data)
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO divisi (nama_divisi, deskripsi)
            VALUES (?, ?)
        ");

        return $stmt->execute([
            $data['nama_divisi'],
            $data['deskripsi'] ?? null
        ]) ? $this->pdo->lastInsertId() : false;
    }

    /**
     * Update divisi
     * 
     * @param int $id
     * @param array $data
     * @return boolean
     */
    public function update($id, $data)
    {
        $stmt = $this->pdo->prepare("
            UPDATE divisi 
            SET nama_divisi = ?, deskripsi = ?
            WHERE id = ?
        ");

        return $stmt->execute([
            $data['nama_divisi'],
            $data['deskripsi'] ?? null,
            $id
        ]);
    }

    /**
     * Delete divisi
     * 
     * @param int $id
     * @return boolean
     */
    public function delete($id)
    {
        $stmt = $this->pdo->prepare("
            DELETE FROM divisi WHERE id = ?
        ");
        return $stmt->execute([$id]);
    }

    /**
     * Check nama divisi exist
     * 
     * @param string $nama
     * @param int $excludeId
     * @return boolean
     */
    public function namaExists($nama, $excludeId = null)
    {
        if ($excludeId) {
            $stmt = $this->pdo->prepare("
                SELECT COUNT(*) as count FROM divisi WHERE nama_divisi = ? AND id != ?
            ");
            $stmt->execute([$nama, $excludeId]);
        } else {
            $stmt = $this->pdo->prepare("
                SELECT COUNT(*) as count FROM divisi WHERE nama_divisi = ?
            ");
            $stmt->execute([$nama]);
        }

        $result = $stmt->fetch();
        return $result['count'] > 0;
    }

    /**
     * Get divisi list untuk select
     * 
     * @return array
     */
    public function getListForSelect()
    {
        $stmt = $this->pdo->prepare("
            SELECT id, nama_divisi FROM divisi
            ORDER BY nama_divisi ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
