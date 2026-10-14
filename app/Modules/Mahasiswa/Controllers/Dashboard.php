<?php

namespace App\Modules\Mahasiswa\Controllers;

use App\Controllers\BaseController;
use App\Modules\Mahasiswa\Models\M_pengajuan;

/**
 * Dashboard — Halaman utama mahasiswa + notifikasi.
 */
class Dashboard extends BaseController
{
    protected M_pengajuan $pengajuanModel;

    public function __construct()
    {
        $this->pengajuanModel = new M_pengajuan();
    }

    /**
     * GET /dashboard/mahasiswa
     */
    public function index()
    {
        $userId     = session('user_id');
        $pengajuan  = $this->pengajuanModel->getByMahasiswa($userId);

        // Hitung statistik ringkas
        $stats = [
            'total'     => count($pengajuan),
            'menunggu'  => count(array_filter($pengajuan, fn($p) => in_array($p['status'], ['MENUNGGU_TU', 'MENUNGGU_KAPRODI', 'MENUNGGU_DEKAN']))),
            'selesai'   => count(array_filter($pengajuan, fn($p) => $p['status'] === 'SELESAI')),
            'ditolak'   => count(array_filter($pengajuan, fn($p) => $p['status'] === 'DITOLAK')),
        ];

        // 5 terbaru untuk tabel dashboard
        $terbaru = array_slice($pengajuan, 0, 5);

        $jenisLabel = [
            'KETERANGAN_AKTIF'      => 'Surat Keterangan Aktif',
            'TIDAK_ADA_PERKULIAHAN' => 'Surat Tidak Ada Perkuliahan',
            'IZIN_PENELITIAN'       => 'Surat Izin Penelitian',
            'PERMOHONAN_PLP_MAGANG' => 'Surat Permohonan PLP/Magang',
            'PERMOHONAN_OBSERVASI'  => 'Surat Permohonan Observasi',
            'REKOMENDASI'           => 'Surat Rekomendasi',
            'KETERANGAN_LULUS'      => 'Surat Keterangan Lulus',
        ];

        return view('App\Modules\Mahasiswa\Views\v_dashboard', [
            'title'      => 'Dashboard Mahasiswa',
            'stats'      => $stats,
            'terbaru'    => $terbaru,
            'jenisLabel' => $jenisLabel,
            'user'       => session()->get(),
        ]);
    }

    /**
     * GET /dashboard/mahasiswa/notifikasi
     */
    public function notifikasi()
    {
        $db   = \Config\Database::connect();
        $data = $db->table('notifikasi')
                   ->where('user_id', session('user_id'))
                   ->orderBy('created_at', 'DESC')
                   ->get()->getResultArray();

        // Mark semua jadi read
        $db->table('notifikasi')
           ->where('user_id', session('user_id'))
           ->where('is_read', 0)
           ->update(['is_read' => 1]);

        return view('App\Modules\Mahasiswa\Views\v_notifikasi', [
            'title'       => 'Notifikasi',
            'notifikasi'  => $data,
            'user'        => session()->get(),
        ]);
    }
}
