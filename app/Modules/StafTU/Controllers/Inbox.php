<?php

namespace App\Modules\StafTU\Controllers;

use App\Controllers\BaseController;
use App\Modules\StafTU\Models\M_inbox;

/**
 * Inbox — Kelola pengajuan masuk untuk Staf TU Prodi.
 * Staf TU adalah approver pertama dalam alur:
 * Mahasiswa → Staf TU → Kaprodi → Dekan → Selesai
 */
class Inbox extends BaseController
{
    protected M_inbox $model;

    public function __construct()
    {
        $this->model = new M_inbox();
    }

    /** GET /dashboard/staf-tu/inbox — Pengajuan menunggu verifikasi */
    public function index()
    {
        return view('App\Modules\StafTU\Views\v_inbox', [
            'title'    => 'Inbox — Menunggu Verifikasi',
            'tab'      => 'menunggu',
            'daftar'   => $this->model->getPendingStafTU(),
            'user'     => session()->get(),
        ]);
    }

    /** GET /dashboard/staf-tu/diproses — Sudah diteruskan ke Kaprodi */
    public function diproses()
    {
        return view('App\Modules\StafTU\Views\v_inbox', [
            'title'  => 'Inbox — Diproses',
            'tab'    => 'diproses',
            'daftar' => $this->model->getAll('MENUNGGU_KAPRODI'),
            'user'   => session()->get(),
        ]);
    }

    /** GET /dashboard/staf-tu/ditolak */
    public function ditolak()
    {
        return view('App\Modules\StafTU\Views\v_inbox', [
            'title'  => 'Inbox — Ditolak',
            'tab'    => 'ditolak',
            'daftar' => $this->model->getAll('DITOLAK'),
            'user'   => session()->get(),
        ]);
    }

    /** GET /dashboard/staf-tu/semua */
    public function semua()
    {
        return view('App\Modules\StafTU\Views\v_inbox', [
            'title'  => 'Semua Pengajuan',
            'tab'    => 'semua',
            'daftar' => $this->model->getAll(),
            'user'   => session()->get(),
        ]);
    }

    /** GET /dashboard/staf-tu/detail/{id} */
    public function detail(string $id)
    {
        $pengajuan = $this->model->getDetailWithLogs($id);

        if (! $pengajuan) {
            return redirect()->to('/dashboard/staf-tu/inbox')->with('error', 'Pengajuan tidak ditemukan.');
        }

        return view('App\Modules\StafTU\Views\v_detail', [
            'title'     => 'Detail Pengajuan',
            'pengajuan' => $pengajuan,
            'user'      => session()->get(),
        ]);
    }

    /** POST /dashboard/staf-tu/approve/{id} — Teruskan ke Kaprodi */
    public function approve(string $id)
    {
        $pengajuan = $this->model->getDetailWithLogs($id);
        if (! $pengajuan || $pengajuan['status'] !== 'MENUNGGU_TU') {
            return redirect()->to('/dashboard/staf-tu/inbox')->with('error', 'Pengajuan tidak valid.');
        }

        $this->model->approve(
            $id,
            session('user_id'),
            session('nama'),
            'STAF_TU_PRODI',
            'MENUNGGU_KAPRODI',
            $this->request->getPost('catatan')
        );

        // Notifikasi mahasiswa
        $this->model->notifMahasiswa(
            $pengajuan['mahasiswa_id'],
            'Pengajuan Diverifikasi Staf TU',
            'Pengajuan ' . $pengajuan['jenis_surat'] . ' kamu telah diverifikasi dan diteruskan ke Kaprodi.',
            $id
        );

        return redirect()->to('/dashboard/staf-tu/inbox')
                         ->with('success', 'Pengajuan berhasil diteruskan ke Kaprodi.');
    }

    /** POST /dashboard/staf-tu/tolak/{id} */
    public function tolak(string $id)
    {
        $pengajuan = $this->model->getDetailWithLogs($id);
        if (! $pengajuan) {
            return redirect()->to('/dashboard/staf-tu/inbox')->with('error', 'Pengajuan tidak ditemukan.');
        }

        $catatan = $this->request->getPost('catatan');
        if (empty($catatan)) {
            return redirect()->back()->with('error', 'Alasan penolakan harus diisi.');
        }

        $this->model->tolak($id, session('user_id'), session('nama'), 'STAF_TU_PRODI', $catatan);

        $this->model->notifMahasiswa(
            $pengajuan['mahasiswa_id'],
            'Pengajuan Ditolak Staf TU',
            'Pengajuan ' . $pengajuan['jenis_surat'] . ' kamu ditolak. Alasan: ' . $catatan,
            $id
        );

        return redirect()->to('/dashboard/staf-tu/inbox')->with('success', 'Pengajuan telah ditolak.');
    }

    /** POST /dashboard/staf-tu/revisi/{id} — Minta revisi kepada Mahasiswa */
    public function revisi(string $id)
    {
        $pengajuan = $this->model->getDetailWithLogs($id);
        if (! $pengajuan) {
            return redirect()->to('/dashboard/staf-tu/inbox')->with('error', 'Pengajuan tidak ditemukan.');
        }

        $catatan = $this->request->getPost('catatan');
        if (empty($catatan)) {
            return redirect()->back()->with('error', 'Catatan revisi wajib diisi.');
        }

        $this->model->mintaRevisi($id, session('user_id'), session('nama'), 'STAF_TU_PRODI', $catatan);

        $this->model->notifMahasiswa(
            $pengajuan['mahasiswa_id'],
            '⚠️ Pengajuan Butuh Perbaikan',
            'Pengajuan surat ' . str_replace('_', ' ', $pengajuan['jenis_surat']) . ' membutuhkan revisi: ' . $catatan,
            $id
        );

        return redirect()->to('/dashboard/staf-tu/inbox')->with('success', 'Permintaan revisi telah dikirim ke mahasiswa.');
    }
}
