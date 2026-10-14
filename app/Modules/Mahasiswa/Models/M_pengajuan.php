<?php

namespace App\Modules\Mahasiswa\Models;

use CodeIgniter\Model;

/**
 * M_pengajuan — Model untuk tabel `pengajuan_surat`.
 * Digunakan oleh Mahasiswa, StafTU, Kaprodi, Dekan.
 */
class M_pengajuan extends Model
{
    protected $table      = 'pengajuan_surat';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = false; // UUID CHAR(36)
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'id', 'nomor_surat', 'verify_token', 'scan_count', 'last_scanned_at',
        'mahasiswa_id', 'mahasiswa_nama', 'mahasiswa_nim', 'mahasiswa_program',
        'jenis_surat', 'status', 'keperluan', 'file_lampiran', 'catatan_terakhir',
        'tanggal_disetujui', 'surat_url', 'file_pdf', 'email_terkirim',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // ─── Mahasiswa ────────────────────────────────────────────────────────────

    /**
     * Ambil semua pengajuan milik mahasiswa tertentu (terbaru dulu).
     */
    public function getByMahasiswa(string $mahasiswaId): array
    {
        return $this->where('mahasiswa_id', $mahasiswaId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Ambil detail pengajuan beserta approval logs-nya.
     */
    public function getDetailWithLogs(string $id): ?array
    {
        $pengajuan = $this->find($id);
        if (! $pengajuan) return null;

        $db = \Config\Database::connect();
        $pengajuan['logs'] = $db->table('approval_logs')
            ->where('pengajuan_id', $id)
            ->orderBy('created_at', 'ASC')
            ->get()->getResultArray();

        return $pengajuan;
    }

    // ─── Approval ────────────────────────────────────────────────────────────

    /**
     * Ambil semua pengajuan dengan status tertentu (untuk inbox approver).
     */
    public function getByStatus(string $status): array
    {
        return $this->where('status', $status)
                    ->orderBy('created_at', 'ASC')
                    ->findAll();
    }

    /**
     * Ambil pengajuan berdasarkan beberapa status (array).
     */
    public function getByStatuses(array $statuses): array
    {
        return $this->whereIn('status', $statuses)
                    ->orderBy('created_at', 'ASC')
                    ->findAll();
    }

    // ─── Statistik ───────────────────────────────────────────────────────────

    /**
     * Hitung pengajuan per status.
     */
    public function countByStatus(): array
    {
        $db  = \Config\Database::connect();
        $rows = $db->query("SELECT status, COUNT(*) as total FROM pengajuan_surat GROUP BY status")->getResultArray();

        $result = [];
        foreach ($rows as $r) {
            $result[$r['status']] = (int) $r['total'];
        }
        return $result;
    }

    /**
     * Generate nomor surat otomatis.
     * Format: SURAT/FKIP-UIKA/{JENIS}/{URUT}/{BULAN}/{TAHUN}
     */
    public function generateNomor(string $jenis): string
    {
        $bulan = date('m');
        $tahun = date('Y');
        $count = $this->where('jenis_surat', $jenis)
                      ->where("MONTH(created_at)", $bulan)
                      ->where("YEAR(created_at)", $tahun)
                      ->countAllResults() + 1;

        $jenisMap = [
            'KETERANGAN_AKTIF'       => 'KA',
            'TIDAK_ADA_PERKULIAHAN'  => 'TAP',
            'IZIN_PENELITIAN'        => 'IP',
            'PERMOHONAN_PLP_MAGANG'  => 'PLP',
            'PERMOHONAN_OBSERVASI'   => 'OBS',
            'REKOMENDASI'            => 'REK',
            'KETERANGAN_LULUS'       => 'KL',
        ];

        $kode = $jenisMap[$jenis] ?? 'SRT';
        return sprintf("SURAT/FKIP-UIKA/%s/%03d/%s/%s", $kode, $count, $bulan, $tahun);
    }
}
