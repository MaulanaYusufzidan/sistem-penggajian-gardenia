<?php
/**
 * Lembur Model
 * 
 * Model untuk mengelola data lembur
 */

class Lembur
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Get semua lembur dengan join
     * 
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function getAll($page = 1, $limit = ITEMS_PER_PAGE)
    {
        $offset = ($page - 1) * $limit;

        $stmt = $this->pdo->prepare("
            SELECT l.*, k.nama_lengkap, k.nip
            FROM lembur l
            JOIN karyawan k ON l.karyawan_id = k.id
            ORDER BY l.tanggal_lembur DESC
            LIMIT ? OFFSET ?
        ");
        $stmt->execute([$limit, $offset]);
        return $stmt->fetchAll();
    }

    /**
     * Get lembur berdasarkan ID
     * 
     * @param int $id
     * @return array|false
     */
    public function getById($id)
    {
        $stmt = $this->pdo->prepare("
            SELECT l.*, k.nama_lengkap, k.nip
            FROM lembur l
            JOIN karyawan k ON l.karyawan_id = k.id
            WHERE l.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Get total lembur
     * 
     * @return int
     */
    public function getTotal()
    {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) as total FROM lembur
        ");
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['total'];
    }

    /**
     * Get lembur berdasarkan karyawan
     * 
     * @param int $karyawanId
     * @return array
     */
    public function getByKaryawan($karyawanId)
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM lembur
            WHERE karyawan_id = ?
            ORDER BY tanggal_lembur DESC
        ");
        $stmt->execute([$karyawanId]);
        return $stmt->fetchAll();
    }

    /**
     * Get total lembur karyawan per bulan
     * 
     * @param int $karyawanId
     * @param int $bulan
     * @param int $tahun
     * @return float
     */
    public function getTotalLemburByKaryawanBulan($karyawanId, $bulan, $tahun)
    {
        $stmt = $this->pdo->prepare("
            SELECT COALESCE(SUM(nominal_lembur), 0) as total
            FROM lembur
            WHERE karyawan_id = ?
            AND MONTH(tanggal_lembur) = ?
            AND YEAR(tanggal_lembur) = ?
        ");
        $stmt->execute([$karyawanId, $bulan, $tahun]);
        $result = $stmt->fetch();
        return $result['total'];
    }

    /**
     * Search lembur
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
            SELECT l.*, k.nama_lengkap, k.nip
            FROM lembur l
            JOIN karyawan k ON l.karyawan_id = k.id
            WHERE k.nama_lengkap LIKE ? OR k.nip LIKE ? OR l.keterangan LIKE ?
            ORDER BY l.tanggal_lembur DESC
            LIMIT ? OFFSET ?
        ");
        $stmt->execute([$keyword, $keyword, $keyword, $limit, $offset]);
        return $stmt->fetchAll();
    }

    /**
     * Search total lembur
     * 
     * @param string $keyword
     * @return int
     */
    public function searchTotal($keyword)
    {
        $keyword = "%$keyword%";

        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) as total FROM lembur l
            JOIN karyawan k ON l.karyawan_id = k.id
            WHERE k.nama_lengkap LIKE ? OR k.nip LIKE ? OR l.keterangan LIKE ?
        ");
        $stmt->execute([$keyword, $keyword, $keyword]);
        $result = $stmt->fetch();
        return $result['total'];
    }

    /**
     * Insert lembur baru
     * 
     * @param array $data
     * @return int|false
     */
    public function create($data)
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO lembur (
                karyawan_id, tanggal_lembur, jam_mulai, jam_selesai,
                tarif_jam, keterangan
            ) VALUES (?, ?, ?, ?, ?, ?)
        ");

        return $stmt->execute([
            $data['karyawan_id'],
            $data['tanggal_lembur'],
            $data['jam_mulai'],
            $data['jam_selesai'],
            $data['tarif_jam'],
            $data['keterangan'] ?? null
        ]) ? $this->pdo->lastInsertId() : false;
    }

    /**
     * Update lembur
     * 
     * @param int $id
     * @param array $data
     * @return boolean
     */
    public function update($id, $data)
    {
        $stmt = $this->pdo->prepare("
            UPDATE lembur SET
                karyawan_id = ?, tanggal_lembur = ?, jam_mulai = ?, jam_selesai = ?,
                tarif_jam = ?, keterangan = ?
            WHERE id = ?
        ");

        return $stmt->execute([
            $data['karyawan_id'],
            $data['tanggal_lembur'],
            $data['jam_mulai'],
            $data['jam_selesai'],
            $data['tarif_jam'],
            $data['keterangan'] ?? null,
            $id
        ]);
    }

    /**
     * Delete lembur
     * 
     * @param int $id
     * @return boolean
     */
    public function delete($id)
    {
        $stmt = $this->pdo->prepare("
            DELETE FROM lembur WHERE id = ?
        ");
        return $stmt->execute([$id]);
    }

    /**
     * Filter lembur by date range
     * 
     * @param string $startDate
     * @param string $endDate
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function filterByDateRange($startDate, $endDate, $page = 1, $limit = ITEMS_PER_PAGE)
    {
        $offset = ($page - 1) * $limit;

        $stmt = $this->pdo->prepare("
            SELECT l.*, k.nama_lengkap, k.nip
            FROM lembur l
            JOIN karyawan k ON l.karyawan_id = k.id
            WHERE l.tanggal_lembur BETWEEN ? AND ?
            ORDER BY l.tanggal_lembur DESC
            LIMIT ? OFFSET ?
        ");
        $stmt->execute([$startDate, $endDate, $limit, $offset]);
        return $stmt->fetchAll();
    }
}
