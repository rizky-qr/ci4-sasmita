<?php

namespace App\Modules\Kaprodi\Controllers;

use App\Controllers\BaseController;
use App\Modules\StafTU\Models\M_inbox;

class Inbox extends BaseController
{
    protected M_inbox $model;

    public function __construct()
    {
        $this->model = new M_inbox();
    }

    public function index()
    {
        return view('App\Modules\Kaprodi\Views\v_inbox', [
            'title'  => 'Inbox — Menunggu Persetujuan Kaprodi',
            'tab'    => 'menunggu',
            'daftar' => $this->model->getPendingKaprodi(),
            'user'   => session()->get(),
        ]);
    }

    public function riwayat()
    {
        return view('App\Modules\Kaprodi\Views\v_inbox', [
            'title'  => 'Riwayat Persetujuan',
            'tab'    => 'riwayat',
            'daftar' => $this->model->getAll('MENUNGGU_DEKAN'),
            'user'   => session()->get(),
        ]);
    }

    public function detail(string $id)
    {
        $pengajuan = $this->model->getDetailWithLogs($id);
        if (! $pengajuan) {
            return redirect()->to('/dashboard/kaprodi/inbox')->with('error', 'Pengajuan tidak ditemukan.');
        }

        return view('App\Modules\Kaprodi\Views\v_detail', [
            'title'        => 'Detail Pengajuan',
            'pengajuan'    => $pengajuan,
            'basePath'     => '/dashboard/kaprodi',
            'approveUrl'   => '/dashboard/kaprodi/approve/' . $id,
            'tolakUrl'     => '/dashboard/kaprodi/tolak/' . $id,
            'canAct'       => $pengajuan['status'] === 'MENUNGGU_KAPRODI',
            'approveLabel' => 'Setujui & Teruskan ke Dekan',
            'user'         => session()->get(),
        ]);
    }

    public function approve(string $id)
    {
        $pengajuan = $this->model->getDetailWithLogs($id);
        if (! $pengajuan || $pengajuan['status'] !== 'MENUNGGU_KAPRODI') {
            return redirect()->to('/dashboard/kaprodi/inbox')->with('error', 'Pengajuan tidak valid.');
        }

        $this->model->approve($id, session('user_id'), session('nama'), 'KAPRODI', 'MENUNGGU_DEKAN', $this->request->getPost('catatan'));
        $this->model->notifMahasiswa($pengajuan['mahasiswa_id'], 'Disetujui Kaprodi', 'Pengajuan kamu telah disetujui Kaprodi dan diteruskan ke Dekan.', $id);

        return redirect()->to('/dashboard/kaprodi/inbox')->with('success', 'Pengajuan diteruskan ke Dekan.');
    }

    /** POST /dashboard/kaprodi/batch-approve */
    public function batchApprove()
    {
        $ids = (array) $this->request->getPost('ids');
        if (empty($ids)) {
            return redirect()->to('/dashboard/kaprodi/inbox')->with('error', 'Tidak ada pengajuan yang dipilih.');
        }

        $count = 0;
        foreach ($ids as $id) {
            $pengajuan = $this->model->getDetailWithLogs($id);
            if ($pengajuan && $pengajuan['status'] === 'MENUNGGU_KAPRODI') {
                $this->model->approve(
                    $id,
                    session('user_id'),
                    session('nama'),
                    'KAPRODI',
                    'MENUNGGU_DEKAN',
                    'Batch persetujuan akademik oleh Kaprodi'
                );

                $this->model->notifMahasiswa(
                    $pengajuan['mahasiswa_id'],
                    'Disetujui Kaprodi',
                    'Pengajuan kamu telah disetujui Kaprodi dan diteruskan ke Dekan.',
                    $id
                );
                $count++;
            }
        }

        return redirect()->to('/dashboard/kaprodi/inbox')
                         ->with('success', $count . ' pengajuan berhasil disetujui dan diteruskan ke Dekan.');
    }

    public function tolak(string $id)
    {
        $pengajuan = $this->model->getDetailWithLogs($id);
        if (! $pengajuan) return redirect()->back()->with('error', 'Tidak ditemukan.');

        $catatan = $this->request->getPost('catatan');
        if (empty($catatan)) return redirect()->back()->with('error', 'Alasan wajib diisi.');

        $this->model->tolak($id, session('user_id'), session('nama'), 'KAPRODI', $catatan);
        $this->model->notifMahasiswa($pengajuan['mahasiswa_id'], 'Ditolak Kaprodi', 'Pengajuan ditolak Kaprodi. Alasan: ' . $catatan, $id);

        return redirect()->to('/dashboard/kaprodi/inbox')->with('success', 'Pengajuan ditolak.');
    }

    /** POST /dashboard/kaprodi/revisi/{id} — Minta revisi kepada Mahasiswa */
    public function revisi(string $id)
    {
        $pengajuan = $this->model->getDetailWithLogs($id);
        if (! $pengajuan) return redirect()->back()->with('error', 'Tidak ditemukan.');

        $catatan = $this->request->getPost('catatan');
        if (empty($catatan)) return redirect()->back()->with('error', 'Catatan revisi wajib diisi.');

        $this->model->mintaRevisi($id, session('user_id'), session('nama'), 'KAPRODI', $catatan);
        $this->model->notifMahasiswa(
            $pengajuan['mahasiswa_id'],
            '⚠️ Pengajuan Butuh Perbaikan dari Kaprodi',
            'Kaprodi meminta perbaikan pengajuan surat: ' . $catatan,
            $id
        );

        return redirect()->to('/dashboard/kaprodi/inbox')->with('success', 'Permintaan revisi berhasil dikirim ke mahasiswa.');
    }
}
