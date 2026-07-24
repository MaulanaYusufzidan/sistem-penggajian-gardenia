<?php
/**
 * Potongan Model
 * 
 * Model untuk mengelola data potongan gaji
 */

class Potongan
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Get semua potongan dengan join
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
            FROM potongan p
            JOIN karyawan k ON p.karyawan_id = k.id
            ORDER BY p.tanggal_potongan DESC
            LIMIT ? OFFSET ?
        ");
        $stmt->execute([$limit, $offset]);
        return $stmt->fetchAll();
    }

    /**
     * Get potongan berdasarkan ID
     * 
     * @param int $id
     * @return array|false
     */
    public function getById($id)
    {
        $stmt = $this->pdo->prepare("
            SELECT p.*, k.nama_lengkap, k.nip
            FROM potongan p
            JOIN karyawan k ON p.karyawan_id = k.id
            WHERE p.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Get total potongan
     * 
     * @return int
     */
    public function getTotal()
    {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) as total FROM potongan
        ");
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['total'];
    }

    /**
     * Get potongan berdasarkan karyawan
     * 
     * @param int $karyawanId
     * @return array
     */
    public function getByKaryawan($karyawanId)
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM potongan
            WHERE karyawan_id = ?
            ORDER BY tanggal_potongan DESC
        ");
        $stmt->execute([$karyawanId]);
        return $stmt->fetchAll();
    }

    /**
     * Get total potongan karyawan per bulan
     * 
     * @param int $karyawanId
     * @param int $bulan
     * @param int $tahun
     * @return float
     */
    public function getTotalPotonganByKaryawanBulan($karyawanId, $bulan, $tahun)
    {
        $stmt = $this->pdo->prepare("
            SELECT COALESCE(SUM(nominal), 0) as total
            FROM potongan
            WHERE karyawan_id = ?
            AND MONTH(tanggal_potongan) = ?
            AND YEAR(tanggal_potongan) = ?
        ");
        $stmt->execute([$karyawanId, $bulan, $tahun]);
        $result = $stmt->fetch();
        return $result['total'];
    }

    /**
     * Search potongan
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
            FROM potongan p
            JOIN karyawan k ON p.karyawan_id = k.id
            WHERE k.nama_lengkap LIKE ? OR k.nip LIKE ? OR p.tipe_potongan LIKE ? OR p.keterangan LIKE ?
            ORDER BY p.tanggal_potongan DESC
            LIMIT ? OFFSET ?
        ");
        $stmt->execute([$keyword, $keyword, $keyword, $keyword, $limit, $offset]);
        return $stmt->fetchAll();
    }

    /**
     * Search total potongan
     * 
     * @param string $keyword
     * @return int
     */
    public function searchTotal($keyword)
    {
        $keyword = "%$keyword%";

        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) as total FROM potongan p
            JOIN karyawan k ON p.karyawan_id = k.id
            WHERE k.nama_lengkap LIKE ? OR k.nip LIKE ? OR p.tipe_potongan LIKE ? OR p.keterangan LIKE ?
        ");
        $stmt->execute([$keyword, $keyword, $keyword, $keyword]);
        $result = $stmt->fetch();
        return $result['total'];
    }

    /**
     * Insert potongan baru
     * 
     * @param array $data
     * @return int|false
     */
    public function create($data)
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO potongan (
                karyawan_id, tipe_potongan, nominal, tanggal_potongan, keterangan
            ) VALUES (?, ?, ?, ?, ?)
        ");

        return $stmt->execute([
            $data['karyawan_id'],
            $data['tipe_potongan'],
            $data['nominal'],
            $data['tanggal_potongan'],
            $data['keterangan'] ?? null
        ]) ? $this->pdo->lastInsertId() : false;
    }

    /**
     * Update potongan
     * 
     * @param int $id
     * @param array $data
     * @return boolean
     */
    public function update($id, $data)
    {
        $stmt = $this->pdo->prepare("
            UPDATE potongan SET
                karyawan_id = ?, tipe_potongan = ?, nominal = ?, tanggal_potongan = ?, keterangan = ?
            WHERE id = ?
        ");

        return $stmt->execute([
            $data['karyawan_id'],
            $data['tipe_potongan'],
            $data['nominal'],
            $data['tanggal_potongan'],
            $data['keterangan'] ?? null,
            $id
        ]);
    }

    /**
     * Delete potongan
     * 
     * @param int $id
     * @return boolean
     */
    public function delete($id)
    {
        $stmt = $this->pdo->prepare("
            DELETE FROM potongan WHERE id = ?
        ");
        return $stmt->execute([$id]);
    }

    /**
     * Get tipe potongan
     * 
     * @return array
     */
    public function getTipe()
    {
        return [
            'BPJS Kesehatan' => 'BPJS Kesehatan',
            'BPJS Ketenagakerjaan' => 'BPJS Ketenagakerjaan',
            'PPh Pasal 21' => 'PPh Pasal 21',
            'Denda' => 'Denda',
            'Potongan Barang' => 'Potongan Barang',
            'Cicilan Hutang' => 'Cicilan Hutang',
            'Lainnya' => 'Lainnya'
        ];
    }

    /**
     * Filter potongan by date range
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
            SELECT p.*, k.nama_lengkap, k.nip
            FROM potongan p
            JOIN karyawan k ON p.karyawan_id = k.id
            WHERE p.tanggal_potongan BETWEEN ? AND ?
            ORDER BY p.tanggal_potongan DESC
            LIMIT ? OFFSET ?
        ");
        $stmt->execute([$startDate, $endDate, $limit, $offset]);
        return $stmt->fetchAll();
    }
}
