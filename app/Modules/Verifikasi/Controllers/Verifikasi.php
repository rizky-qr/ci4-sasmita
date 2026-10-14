<?php

namespace App\Modules\Verifikasi\Controllers;

use App\Controllers\BaseController;

/**
 * Verifikasi — Controller publik untuk verifikasi QR Code & cetak surat resmi
 * Route:
 * - GET /verify/{token}
 * - GET /surat/cetak/{identifier} (bisa pakai token atau id pengajuan)
 */
class Verifikasi extends BaseController
{
    /**
     * Halaman Publik Verifikasi Dokumen Resmi
     */
    public function index(string $token)
    {
        $db = \Config\Database::connect();
        $pengajuan = $db->table('pengajuan_surat')
            ->where('verify_token', $token)
            ->get()
            ->getRowArray();

        // Ambil riwayat log pengesahan
        $logs = [];
        $totalScan = 0;
        if ($pengajuan) {
            $logs = $db->table('approval_logs')
                ->where('pengajuan_id', $pengajuan['id'])
                ->orderBy('created_at', 'ASC')
                ->get()
                ->getResultArray();

            // Audit log pemindaian QR Code
            try {
                $db->table('verify_logs')->insert([
                    'pengajuan_id' => $pengajuan['id'],
                    'ip_address'   => $this->request->getIPAddress(),
                    'user_agent'   => substr((string) $this->request->getUserAgent(), 0, 255),
                    'scanned_at'   => date('Y-m-d H:i:s'),
                ]);

                $db->table('pengajuan_surat')->where('id', $pengajuan['id'])->increment('scan_count', 1);
                $db->table('pengajuan_surat')->where('id', $pengajuan['id'])->update(['last_scanned_at' => date('Y-m-d H:i:s')]);
                $totalScan = (int) ($pengajuan['scan_count'] ?? 0) + 1;
            } catch (\Throwable $e) {
                $totalScan = (int) ($pengajuan['scan_count'] ?? 1);
            }
        }

        return view('App\Modules\Verifikasi\Views\v_verify', [
            'token'     => $token,
            'pengajuan' => $pengajuan,
            'logs'      => $logs,
            'isValid'   => ($pengajuan && in_array($pengajuan['status'], ['DISETUJUI', 'SELESAI'])),
            'totalScan' => $totalScan,
        ]);
    }

    /**
     * Template Cetak Surat Resmi (Kop Surat FKIP UIKA + QR Code)
     */
    public function cetak(string $identifier)
    {
        $db = \Config\Database::connect();

        // Cari berdasarkan verify_token atau id
        $pengajuan = $db->table('pengajuan_surat')
            ->groupStart()
                ->where('id', $identifier)
                ->orWhere('verify_token', $identifier)
            ->groupEnd()
            ->get()
            ->getRowArray();

        if (! $pengajuan) {
            return redirect()->to('/login')->with('error', 'Dokumen surat tidak ditemukan.');
        }

        // Ambil data Dekan penandatangan
        $dekan = $db->table('users')
            ->where('role', 'DEKAN')
            ->get()
            ->getRowArray();

        return view('App\Modules\Verifikasi\Views\v_cetak_surat', [
            'pengajuan' => $pengajuan,
            'dekan'     => $dekan,
            'verifyUrl' => site_url('verify/' . ($pengajuan['verify_token'] ?: $pengajuan['id'])),
        ]);
    }
}
