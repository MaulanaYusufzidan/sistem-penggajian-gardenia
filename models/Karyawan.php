<?php
/**
 * Karyawan Model
 * 
 * Model untuk mengelola data karyawan
 */

class Karyawan
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Get semua karyawan dengan join
     * 
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function getAll($page = 1, $limit = ITEMS_PER_PAGE)
    {
        $offset = ($page - 1) * $limit;

        $stmt = $this->pdo->prepare("
            SELECT k.*, j.nama_jabatan, d.nama_divisi, u.username, u.email
            FROM karyawan k
            JOIN jabatan j ON k.jabatan_id = j.id
            JOIN divisi d ON k.divisi_id = d.id
            JOIN users u ON k.user_id = u.id
            ORDER BY k.nama_lengkap ASC
            LIMIT ? OFFSET ?
        ");
        $stmt->execute([$limit, $offset]);
        return $stmt->fetchAll();
    }

    /**
     * Get karyawan berdasarkan ID
     * 
     * @param int $id
     * @return array|false
     */
    public function getById($id)
    {
        $stmt = $this->pdo->prepare("
            SELECT k.*, j.nama_jabatan, d.nama_divisi, u.username, u.email
            FROM karyawan k
            JOIN jabatan j ON k.jabatan_id = j.id
            JOIN divisi d ON k.divisi_id = d.id
            JOIN users u ON k.user_id = u.id
            WHERE k.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Get karyawan berdasarkan user_id
     * 
     * @param int $userId
     * @return array|false
     */
    public function getByUserId($userId)
    {
        $stmt = $this->pdo->prepare("
            SELECT k.*, j.nama_jabatan, d.nama_divisi, u.username, u.email
            FROM karyawan k
            JOIN jabatan j ON k.jabatan_id = j.id
            JOIN divisi d ON k.divisi_id = d.id
            JOIN users u ON k.user_id = u.id
            WHERE k.user_id = ?
        ");
        $stmt->execute([$userId]);
        return $stmt->fetch();
    }

    /**
     * Get total karyawan
     * 
     * @return int
     */
    public function getTotal()
    {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) as total FROM karyawan
        ");
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['total'];
    }

    /**
     * Search karyawan
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
            SELECT k.*, j.nama_jabatan, d.nama_divisi, u.username, u.email
            FROM karyawan k
            JOIN jabatan j ON k.jabatan_id = j.id
            JOIN divisi d ON k.divisi_id = d.id
            JOIN users u ON k.user_id = u.id
            WHERE k.nip LIKE ? OR k.nama_lengkap LIKE ? OR k.no_hp LIKE ?
            ORDER BY k.nama_lengkap ASC
            LIMIT ? OFFSET ?
        ");
        $stmt->execute([$keyword, $keyword, $keyword, $limit, $offset]);
        return $stmt->fetchAll();
    }

    /**
     * Search total karyawan
     * 
     * @param string $keyword
     * @return int
     */
    public function searchTotal($keyword)
    {
        $keyword = "%$keyword%";

        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) as total FROM karyawan
            WHERE nip LIKE ? OR nama_lengkap LIKE ? OR no_hp LIKE ?
        ");
        $stmt->execute([$keyword, $keyword, $keyword]);
        $result = $stmt->fetch();
        return $result['total'];
    }

    /**
     * Insert karyawan baru
     * 
     * @param array $data
     * @return int|false
     */
    public function create($data)
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO karyawan (
                user_id, nip, nama_lengkap, jenis_kelamin, tanggal_lahir,
                alamat, no_hp, no_rekening, nama_bank, jabatan_id, divisi_id,
                tanggal_bergabung, status_karyawan
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        return $stmt->execute([
            $data['user_id'],
            $data['nip'],
            $data['nama_lengkap'],
            $data['jenis_kelamin'],
            $data['tanggal_lahir'] ?? null,
            $data['alamat'] ?? null,
            $data['no_hp'] ?? null,
            $data['no_rekening'] ?? null,
            $data['nama_bank'] ?? null,
            $data['jabatan_id'],
            $data['divisi_id'],
            $data['tanggal_bergabung'],
            $data['status_karyawan'] ?? STATUS_AKTIF
        ]) ? $this->pdo->lastInsertId() : false;
    }

    /**
     * Update karyawan
     * 
     * @param int $id
     * @param array $data
     * @return boolean
     */
    public function update($id, $data)
    {
        $stmt = $this->pdo->prepare("
            UPDATE karyawan SET
                nip = ?, nama_lengkap = ?, jenis_kelamin = ?, tanggal_lahir = ?,
                alamat = ?, no_hp = ?, no_rekening = ?, nama_bank = ?,
                jabatan_id = ?, divisi_id = ?, tanggal_bergabung = ?, status_karyawan = ?
            WHERE id = ?
        ");

        return $stmt->execute([
            $data['nip'],
            $data['nama_lengkap'],
            $data['jenis_kelamin'],
            $data['tanggal_lahir'] ?? null,
            $data['alamat'] ?? null,
            $data['no_hp'] ?? null,
            $data['no_rekening'] ?? null,
            $data['nama_bank'] ?? null,
            $data['jabatan_id'],
            $data['divisi_id'],
            $data['tanggal_bergabung'],
            $data['status_karyawan'] ?? STATUS_AKTIF,
            $id
        ]);
    }

    /**
     * Delete karyawan
     * 
     * @param int $id
     * @return boolean
     */
    public function delete($id)
    {
        $stmt = $this->pdo->prepare("
            DELETE FROM karyawan WHERE id = ?
        ");
        return $stmt->execute([$id]);
    }

    /**
     * Check NIP exist
     * 
     * @param string $nip
     * @param int $excludeId
     * @return boolean
     */
    public function nipExists($nip, $excludeId = null)
    {
        if ($excludeId) {
            $stmt = $this->pdo->prepare("
                SELECT COUNT(*) as count FROM karyawan WHERE nip = ? AND id != ?
            ");
            $stmt->execute([$nip, $excludeId]);
        } else {
            $stmt = $this->pdo->prepare("
                SELECT COUNT(*) as count FROM karyawan WHERE nip = ?
            ");
            $stmt->execute([$nip]);
        }

        $result = $stmt->fetch();
        return $result['count'] > 0;
    }

    /**
     * Get karyawan aktif
     * 
     * @return array
     */
    public function getAktif()
    {
        $stmt = $this->pdo->prepare("
            SELECT k.*, j.nama_jabatan, d.nama_divisi
            FROM karyawan k
            JOIN jabatan j ON k.jabatan_id = j.id
            JOIN divisi d ON k.divisi_id = d.id
            WHERE k.status_karyawan = ?
            ORDER BY k.nama_lengkap ASC
        ");
        $stmt->execute([STATUS_AKTIF]);
        return $stmt->fetchAll();
    }
}
