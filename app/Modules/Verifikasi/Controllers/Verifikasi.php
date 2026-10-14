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
        if ($pengajuan) {
            $logs = $db->table('approval_logs')
                ->where('pengajuan_id', $pengajuan['id'])
                ->orderBy('created_at', 'ASC')
                ->get()
                ->getResultArray();
        }

        return view('App\Modules\Verifikasi\Views\v_verify', [
            'token'     => $token,
            'pengajuan' => $pengajuan,
            'logs'      => $logs,
            'isValid'   => ($pengajuan && in_array($pengajuan['status'], ['DISETUJUI', 'SELESAI'])),
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
