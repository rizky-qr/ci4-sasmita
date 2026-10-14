<?php

namespace App\Modules\Dekan\Controllers;

use App\Controllers\BaseController;
use App\Modules\StafTU\Models\M_inbox;

/**
 * Inbox — Dekan adalah approver terakhir.
 * setujui() → status SELESAI + generate nomor surat.
 */
class Inbox extends BaseController
{
    protected M_inbox $model;
    public function __construct() { $this->model = new M_inbox(); }

    public function index()
    {
        return view('App\Modules\Dekan\Views\v_inbox', [
            'title'  => 'Inbox — Menunggu Pengesahan Dekan',
            'tab'    => 'menunggu',
            'daftar' => $this->model->getPendingDekan(),
            'user'   => session()->get(),
        ]);
    }

    public function selesai()
    {
        return view('App\Modules\Dekan\Views\v_inbox', [
            'title'  => 'Pengajuan Selesai',
            'tab'    => 'selesai',
            'daftar' => $this->model->getAll('SELESAI'),
            'user'   => session()->get(),
        ]);
    }

    public function detail(string $id)
    {
        $pengajuan = $this->model->getDetailWithLogs($id);
        if (! $pengajuan) return redirect()->to('/dashboard/dekan/inbox')->with('error', 'Tidak ditemukan.');

        return view('App\Modules\Dekan\Views\v_detail', [
            'title'        => 'Detail Pengajuan',
            'pengajuan'    => $pengajuan,
            'basePath'     => '/dashboard/dekan',
            'approveUrl'   => '/dashboard/dekan/setujui/' . $id,
            'tolakUrl'     => '/dashboard/dekan/tolak/' . $id,
            'canAct'       => $pengajuan['status'] === 'MENUNGGU_DEKAN',
            'approveLabel' => '✅ Sahkan & Selesaikan',
            'user'         => session()->get(),
        ]);
    }

    /** POST /dashboard/dekan/setujui/{id} — Pengesahan akhir → SELESAI */
    public function setujui(string $id)
    {
        $pengajuan = $this->model->getDetailWithLogs($id);
        if (! $pengajuan || $pengajuan['status'] !== 'MENUNGGU_DEKAN') {
            return redirect()->to('/dashboard/dekan/inbox')->with('error', 'Pengajuan tidak valid.');
        }

        // Generate nomor surat resmi FKIP UIKA & token verifikasi via NumberingService
        $nomor       = \App\Services\NumberingService::generate($pengajuan['jenis_surat']);
        $verifyToken = $pengajuan['verify_token'] ?: \App\Services\NumberingService::generateVerifyToken();

        $this->model->selesaikan($id, session('user_id'), session('nama'), $nomor, null, $verifyToken);
        $this->model->notifMahasiswa(
            $pengajuan['mahasiswa_id'],
            '🎉 Surat Siap Diunduh',
            'Surat ' . str_replace('_', ' ', $pengajuan['jenis_surat']) . ' No. ' . $nomor . ' telah disahkan Dekan dan siap diunduh.',
            $id
        );

        return redirect()->to('/dashboard/dekan/inbox')->with('success', 'Surat berhasil disahkan. Nomor: ' . $nomor);
    }

    public function tolak(string $id)
    {
        $pengajuan = $this->model->getDetailWithLogs($id);
        if (! $pengajuan) return redirect()->back()->with('error', 'Tidak ditemukan.');

        $catatan = $this->request->getPost('catatan');
        if (empty($catatan)) return redirect()->back()->with('error', 'Alasan wajib diisi.');

        $this->model->tolak($id, session('user_id'), session('nama'), 'DEKAN', $catatan);
        $this->model->notifMahasiswa($pengajuan['mahasiswa_id'], 'Ditolak Dekan', 'Surat ditolak Dekan. Alasan: ' . $catatan, $id);

        return redirect()->to('/dashboard/dekan/inbox')->with('success', 'Pengajuan ditolak.');
    }
}
