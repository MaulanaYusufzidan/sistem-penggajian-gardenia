<?php
/**
 * Penggajian Model
 * 
 * Model untuk mengelola data penggajian
 */

class Penggajian
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Get semua penggajian dengan join
     * 
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function getAll($page = 1, $limit = ITEMS_PER_PAGE)
    {
        $offset = ($page - 1) * $limit;

        $stmt = $this->pdo->prepare("
            SELECT p.*, k.nama_lengkap, k.nip
            FROM penggajian p
            JOIN karyawan k ON p.karyawan_id = k.id
            ORDER BY p.periode_tahun DESC, p.periode_bulan DESC
            LIMIT ? OFFSET ?
        ");
        $stmt->execute([$limit, $offset]);
        return $stmt->fetchAll();
    }

    /**
     * Get penggajian berdasarkan ID
     * 
     * @param int $id
     * @return array|false
     */
    public function getById($id)
    {
        $stmt = $this->pdo->prepare("
            SELECT p.*, k.nama_lengkap, k.nip, k.no_rekening, k.nama_bank
            FROM penggajian p
            JOIN karyawan k ON p.karyawan_id = k.id
            WHERE p.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Get total penggajian
     * 
     * @return int
     */
    public function getTotal()
    {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) as total FROM penggajian
        ");
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['total'];
    }

    /**
     * Get penggajian berdasarkan karyawan
     * 
     * @param int $karyawanId
     * @return array
     */
    public function getByKaryawan($karyawanId)
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM penggajian
            WHERE karyawan_id = ?
            ORDER BY periode_tahun DESC, periode_bulan DESC
        ");
        $stmt->execute([$karyawanId]);
        return $stmt->fetchAll();
    }

    /**
     * Search penggajian
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
            SELECT p.*, k.nama_lengkap, k.nip
            FROM penggajian p
            JOIN karyawan k ON p.karyawan_id = k.id
            WHERE k.nama_lengkap LIKE ? OR k.nip LIKE ?
            ORDER BY p.periode_tahun DESC, p.periode_bulan DESC
            LIMIT ? OFFSET ?
        ");
        $stmt->execute([$keyword, $keyword, $limit, $offset]);
        return $stmt->fetchAll();
    }

    /**
     * Search total penggajian
     * 
     * @param string $keyword
     * @return int
     */
    public function searchTotal($keyword)
    {
        $keyword = "%$keyword%";

        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) as total FROM penggajian p
            JOIN karyawan k ON p.karyawan_id = k.id
            WHERE k.nama_lengkap LIKE ? OR k.nip LIKE ?
        ");
        $stmt->execute([$keyword, $keyword]);
        $result = $stmt->fetch();
        return $result['total'];
    }

    /**
     * Insert penggajian baru
     * 
     * @param array $data
     * @return int|false
     */
    public function create($data)
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO penggajian (
                karyawan_id, periode_bulan, periode_tahun, gaji_pokok,
                tunjangan, total_lembur, total_potongan, gaji_bersih, status
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        return $stmt->execute([
            $data['karyawan_id'],
            $data['periode_bulan'],
            $data['periode_tahun'],
            $data['gaji_pokok'],
            $data['tunjangan'] ?? 0,
            $data['total_lembur'] ?? 0,
            $data['total_potongan'] ?? 0,
            $data['gaji_bersih'],
            $data['status'] ?? 'Draft'
        ]) ? $this->pdo->lastInsertId() : false;
    }

    /**
     * Update penggajian
     * 
     * @param int $id
     * @param array $data
     * @return boolean
     */
    public function update($id, $data)
    {
        $stmt = $this->pdo->prepare("
            UPDATE penggajian SET
                karyawan_id = ?, periode_bulan = ?, periode_tahun = ?, gaji_pokok = ?,
                tunjangan = ?, total_lembur = ?, total_potongan = ?, gaji_bersih = ?, status = ?
            WHERE id = ?
        ");

        return $stmt->execute([
            $data['karyawan_id'],
            $data['periode_bulan'],
            $data['periode_tahun'],
            $data['gaji_pokok'],
            $data['tunjangan'] ?? 0,
            $data['total_lembur'] ?? 0,
            $data['total_potongan'] ?? 0,
            $data['gaji_bersih'],
            $data['status'] ?? 'Draft',
            $id
        ]);
    }

    /**
     * Delete penggajian
     * 
     * @param int $id
     * @return boolean
     */
    public function delete($id)
    {
        $stmt = $this->pdo->prepare("
            DELETE FROM penggajian WHERE id = ?
        ");
        return $stmt->execute([$id]);
    }

    /**
     * Filter penggajian by month and year
     * 
     * @param int $bulan
     * @param int $tahun
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function filterByPeriode($bulan, $tahun, $page = 1, $limit = ITEMS_PER_PAGE)
    {
        $offset = ($page - 1) * $limit;

        $stmt = $this->pdo->prepare("
            SELECT p.*, k.nama_lengkap, k.nip
            FROM penggajian p
            JOIN karyawan k ON p.karyawan_id = k.id
            WHERE p.periode_bulan = ? AND p.periode_tahun = ?
            ORDER BY k.nama_lengkap ASC
            LIMIT ? OFFSET ?
        ");
        $stmt->execute([$bulan, $tahun, $limit, $offset]);
        return $stmt->fetchAll();
    }
}
