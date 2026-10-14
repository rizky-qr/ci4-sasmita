<?php

namespace App\Modules\StafTU\Models;

use CodeIgniter\Database\RawSql;

/**
 * M_inbox — Model untuk approval workflow.
 * Digunakan oleh StafTU, Kaprodi, dan Dekan (namespace berbeda, file sama logikanya).
 * Mengakses tabel: pengajuan_surat, approval_logs, notifikasi.
 */
class M_inbox
{
    protected \CodeIgniter\Database\BaseConnection $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    // ─── Ambil pengajuan ─────────────────────────────────────────────────────

    /**
     * Ambil semua pengajuan dengan status MENUNGGU_TU (untuk Staf TU).
     */
    public function getPendingStafTU(): array
    {
        return $this->db->table('pengajuan_surat')
            ->where('status', 'MENUNGGU_TU')
            ->orderBy('created_at', 'ASC')
            ->get()->getResultArray();
    }

    /**
     * Ambil semua pengajuan dengan status MENUNGGU_KAPRODI.
     */
    public function getPendingKaprodi(): array
    {
        return $this->db->table('pengajuan_surat')
            ->where('status', 'MENUNGGU_KAPRODI')
            ->orderBy('created_at', 'ASC')
            ->get()->getResultArray();
    }

    /**
     * Ambil semua pengajuan dengan status MENUNGGU_DEKAN.
     */
    public function getPendingDekan(): array
    {
        return $this->db->table('pengajuan_surat')
            ->where('status', 'MENUNGGU_DEKAN')
            ->orderBy('created_at', 'ASC')
            ->get()->getResultArray();
    }

    /**
     * Ambil semua pengajuan (untuk tab "Semua" / riwayat).
     */
    public function getAll(?string $status = null): array
    {
        $builder = $this->db->table('pengajuan_surat')->orderBy('created_at', 'DESC');
        if ($status) {
            $builder->where('status', $status);
        }
        return $builder->get()->getResultArray();
    }

    /**
     * Ambil satu pengajuan beserta approval logs-nya.
     */
    public function getDetailWithLogs(string $id): ?array
    {
        $pengajuan = $this->db->table('pengajuan_surat')->where('id', $id)->get()->getRowArray();
        if (! $pengajuan) return null;

        $pengajuan['logs'] = $this->db->table('approval_logs')
            ->where('pengajuan_id', $id)
            ->orderBy('created_at', 'ASC')
            ->get()->getResultArray();

        return $pengajuan;
    }

    // ─── Aksi approval ───────────────────────────────────────────────────────

    /**
     * Approve pengajuan — update status dan catat log.
     *
     * @param string $id          ID pengajuan
     * @param string $approvedById User ID approver
     * @param string $approvedByNama Nama approver
     * @param string $role        Role approver
     * @param string $nextStatus  Status setelah diapprove
     * @param string|null $catatan Catatan opsional
     */
    public function approve(
        string $id,
        string $approvedById,
        string $approvedByNama,
        string $role,
        string $nextStatus,
        ?string $catatan = null
    ): void {
        // Update status pengajuan
        $this->db->table('pengajuan_surat')->where('id', $id)->update(['status' => $nextStatus]);

        // Catat approval log
        $this->insertLog($id, $approvedById, $approvedByNama, $role, 'DISETUJUI', $catatan);
    }

    /**
     * Tolak pengajuan — update status DITOLAK dan catat log.
     */
    public function tolak(
        string $id,
        string $approvedById,
        string $approvedByNama,
        string $role,
        string $catatan
    ): void {
        $this->db->table('pengajuan_surat')->where('id', $id)->update([
            'status'           => 'DITOLAK',
            'catatan_terakhir' => $catatan,
        ]);

        $this->insertLog($id, $approvedById, $approvedByNama, $role, 'DITOLAK', $catatan);
    }

    /**
     * Minta Revisi — update status BUTUH_REVISI dan catat log.
     */
    public function mintaRevisi(
        string $id,
        string $approvedById,
        string $approvedByNama,
        string $role,
        string $catatan
    ): void {
        $this->db->table('pengajuan_surat')->where('id', $id)->update([
            'status'           => 'BUTUH_REVISI',
            'catatan_terakhir' => $catatan,
        ]);

        $this->insertLog($id, $approvedById, $approvedByNama, $role, 'BUTUH_REVISI', $catatan);
    }

    /**
     * Selesaikan pengajuan (Dekan) + generate nomor surat & verifikasi token.
     */
    public function selesaikan(
        string $id,
        string $approvedById,
        string $approvedByNama,
        ?string $nomorSurat = null,
        ?string $suratUrl = null,
        ?string $verifyToken = null
    ): void {
        $update = [
            'status'            => 'SELESAI',
            'tanggal_disetujui' => date('Y-m-d H:i:s'),
        ];
        if ($nomorSurat)  $update['nomor_surat']  = $nomorSurat;
        if ($suratUrl)    $update['surat_url']    = $suratUrl;
        if ($verifyToken) $update['verify_token'] = $verifyToken;

        $this->db->table('pengajuan_surat')->where('id', $id)->update($update);

        $this->insertLog($id, $approvedById, $approvedByNama, 'DEKAN', 'SELESAI', 'Surat telah disahkan dan ditandatangani.');
    }

    // ─── Statistik ───────────────────────────────────────────────────────────

    public function getStatistik(): array
    {
        $rows = $this->db->query("
            SELECT status, COUNT(*) as total
            FROM pengajuan_surat
            GROUP BY status
        ")->getResultArray();

        $result = [];
        foreach ($rows as $r) {
            $result[$r['status']] = (int) $r['total'];
        }
        return $result;
    }

    public function getStatistikByJenis(): array
    {
        return $this->db->query("
            SELECT jenis_surat, COUNT(*) as total
            FROM pengajuan_surat
            GROUP BY jenis_surat
            ORDER BY total DESC
        ")->getResultArray();
    }

    // ─── Private ─────────────────────────────────────────────────────────────

    private function insertLog(
        string $pengajuanId,
        string $approvedById,
        string $approvedByNama,
        string $role,
        string $status,
        ?string $catatan
    ): void {
        $this->db->table('approval_logs')->insert([
            'id'               => $this->uuid(),
            'pengajuan_id'     => $pengajuanId,
            'approved_by_id'   => $approvedById,
            'approved_by_nama' => $approvedByNama,
            'role'             => $role,
            'status'           => $status,
            'catatan'          => $catatan,
        ]);
    }

    private function uuid(): string
    {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

    /**
     * Buat notifikasi untuk mahasiswa.
     */
    public function notifMahasiswa(string $userId, string $judul, string $pesan, string $pengajuanId): void
    {
        $this->db->table('notifikasi')->insert([
            'id'           => $this->uuid(),
            'user_id'      => $userId,
            'judul'        => $judul,
            'pesan'        => $pesan,
            'pengajuan_id' => $pengajuanId,
            'is_read'      => 0,
        ]);
    }
}
