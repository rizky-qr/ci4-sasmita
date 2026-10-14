<?php

namespace App\Modules\Mahasiswa\Controllers;

use App\Controllers\BaseController;
use App\Modules\Mahasiswa\Models\M_pengajuan;

/**
 * Pengajuan — Menangani semua form & alur pengajuan surat mahasiswa.
 */
class Pengajuan extends BaseController
{
    protected M_pengajuan $model;

    // Mapping jenis → label tampil
    private const JENIS_LABEL = [
        'KETERANGAN_AKTIF'      => 'Surat Keterangan Aktif',
        'TIDAK_ADA_PERKULIAHAN' => 'Surat Tidak Ada Perkuliahan',
        'IZIN_PENELITIAN'       => 'Surat Izin Penelitian',
        'PERMOHONAN_PLP_MAGANG' => 'Surat Permohonan PLP/Magang',
        'PERMOHONAN_OBSERVASI'  => 'Surat Permohonan Observasi',
        'REKOMENDASI'           => 'Surat Rekomendasi',
        'KETERANGAN_LULUS'      => 'Surat Keterangan Lulus',
    ];

    public function __construct()
    {
        $this->model = new M_pengajuan();
    }

    // ─── Tampil Form ──────────────────────────────────────────────────────────

    public function aktif()
    {
        return $this->showForm('KETERANGAN_AKTIF');
    }

    public function tidakKuliah()
    {
        return $this->showForm('TIDAK_ADA_PERKULIAHAN');
    }

    public function penelitian()
    {
        return $this->showFormDenganMitra('IZIN_PENELITIAN');
    }

    public function plpMagang()
    {
        return $this->showFormDenganMitra('PERMOHONAN_PLP_MAGANG');
    }

    public function observasi()
    {
        return $this->showFormDenganMitra('PERMOHONAN_OBSERVASI');
    }

    public function rekomendasi()
    {
        return $this->showForm('REKOMENDASI');
    }

    public function lulus()
    {
        return $this->showForm('KETERANGAN_LULUS');
    }

    // ─── Simpan Pengajuan ─────────────────────────────────────────────────────

    /**
     * POST /dashboard/mahasiswa/pengajuan/simpan
     */
    public function simpan()
    {
        $rules = [
            'jenis_surat' => 'required|in_list[KETERANGAN_AKTIF,TIDAK_ADA_PERKULIAHAN,IZIN_PENELITIAN,PERMOHONAN_PLP_MAGANG,PERMOHONAN_OBSERVASI,REKOMENDASI,KETERANGAN_LULUS]',
            'keperluan'   => 'required|min_length[10]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $user  = session()->get();
        $jenis = $this->request->getPost('jenis_surat');
        $id    = $this->generateUUID();

        $this->model->insert([
            'id'                 => $id,
            'mahasiswa_id'       => $user['user_id'],
            'mahasiswa_nama'     => $user['nama'],
            'mahasiswa_nim'      => $user['nim'] ?? '-',
            'mahasiswa_program'  => $user['program'] ?? '-',
            'jenis_surat'        => $jenis,
            'status'             => 'MENUNGGU_TU',
            'keperluan'          => $this->request->getPost('keperluan'),
        ]);

        // Buat notifikasi untuk diri sendiri
        $this->createNotif(
            $user['user_id'],
            'Pengajuan Dikirim',
            'Pengajuan ' . (self::JENIS_LABEL[$jenis] ?? $jenis) . ' telah dikirim dan menunggu verifikasi Staf TU.',
            $id
        );

        return redirect()->to('/dashboard/mahasiswa/riwayat')
                         ->with('success', 'Pengajuan berhasil dikirim! Menunggu verifikasi Staf TU Prodi.');
    }

    // ─── Riwayat & Detail ─────────────────────────────────────────────────────

    /**
     * GET /dashboard/mahasiswa/riwayat
     */
    public function riwayat()
    {
        $pengajuan = $this->model->getByMahasiswa(session('user_id'));

        return view('App\Modules\Mahasiswa\Views\v_riwayat', [
            'title'     => 'Riwayat Pengajuan',
            'pengajuan' => $pengajuan,
            'jenisLabel'=> self::JENIS_LABEL,
            'user'      => session()->get(),
        ]);
    }

    /**
     * GET /dashboard/mahasiswa/riwayat/{id}
     */
    public function detail(string $id)
    {
        $pengajuan = $this->model->getDetailWithLogs($id);

        if (! $pengajuan || $pengajuan['mahasiswa_id'] !== session('user_id')) {
            return redirect()->to('/dashboard/mahasiswa/riwayat')->with('error', 'Pengajuan tidak ditemukan.');
        }

        return view('App\Modules\Mahasiswa\Views\v_detail_pengajuan', [
            'title'      => 'Detail Pengajuan',
            'pengajuan'  => $pengajuan,
            'jenisLabel' => self::JENIS_LABEL,
            'user'       => session()->get(),
        ]);
    }

    /**
     * POST /dashboard/mahasiswa/pengajuan/perbaiki/{id}
     * Mahasiswa mengirim perbaikan untuk pengajuan yang BUTUH_REVISI
     */
    public function perbaiki(string $id)
    {
        $pengajuan = $this->model->find($id);
        if (! $pengajuan || $pengajuan['mahasiswa_id'] !== session('user_id')) {
            return redirect()->to('/dashboard/mahasiswa/riwayat')->with('error', 'Pengajuan tidak ditemukan.');
        }

        if ($pengajuan['status'] !== 'BUTUH_REVISI') {
            return redirect()->to('/dashboard/mahasiswa/riwayat/' . $id)->with('error', 'Pengajuan ini tidak dalam status butuh revisi.');
        }

        $keperluan = trim((string) $this->request->getPost('keperluan'));
        if (strlen($keperluan) < 10) {
            return redirect()->back()->with('error', 'Keperluan perbaikan minimal 10 karakter.');
        }

        $this->model->update($id, [
            'keperluan' => $keperluan,
            'status'    => 'MENUNGGU_TU',
        ]);

        $db = \Config\Database::connect();
        $db->table('approval_logs')->insert([
            'id'               => $this->generateUUID(),
            'pengajuan_id'     => $id,
            'approved_by_id'   => session('user_id'),
            'approved_by_nama' => session('nama'),
            'role'             => 'MAHASISWA',
            'status'           => 'MENUNGGU_TU',
            'catatan'          => 'Mahasiswa telah mengirimkan perbaikan/revisi data pengajuan.',
            'created_at'       => date('Y-m-d H:i:s'),
        ]);

        $this->createNotif(
            session('user_id'),
            'Revisi Telah Dikirim',
            'Perbaikan pengajuan ' . (self::JENIS_LABEL[$pengajuan['jenis_surat']] ?? $pengajuan['jenis_surat']) . ' berhasil dikirim ke Staf TU.',
            $id
        );

        return redirect()->to('/dashboard/mahasiswa/riwayat/' . $id)->with('success', 'Perbaikan berhasil dikirim. Pengajuan kembali diproses oleh Staf TU.');
    }

    // ─── Private Helpers ──────────────────────────────────────────────────────

    private function showForm(string $jenis)
    {
        return view('App\Modules\Mahasiswa\Views\v_form_pengajuan', [
            'title'      => self::JENIS_LABEL[$jenis] ?? $jenis,
            'jenis'      => $jenis,
            'jenisLabel' => self::JENIS_LABEL,
            'mitra'      => [],
            'user'       => session()->get(),
        ]);
    }

    private function showFormDenganMitra(string $jenis)
    {
        $db    = \Config\Database::connect();
        $kategoriMap = [
            'IZIN_PENELITIAN'       => 'PENELITIAN',
            'PERMOHONAN_PLP_MAGANG' => 'PLP_MAGANG',
            'PERMOHONAN_OBSERVASI'  => 'OBSERVASI',
        ];
        $kategori = $kategoriMap[$jenis] ?? null;
        $mitra = $db->table('tempat_mitra')
                    ->where('is_active', 1)
                    ->where('kategori', $kategori)
                    ->get()->getResultArray();

        return view('App\Modules\Mahasiswa\Views\v_form_pengajuan', [
            'title'      => self::JENIS_LABEL[$jenis] ?? $jenis,
            'jenis'      => $jenis,
            'jenisLabel' => self::JENIS_LABEL,
            'mitra'      => $mitra,
            'user'       => session()->get(),
        ]);
    }

    private function generateUUID(): string
    {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

    private function createNotif(string $userId, string $judul, string $pesan, string $pengajuanId): void
    {
        $db = \Config\Database::connect();
        $db->table('notifikasi')->insert([
            'id'           => $this->generateUUID(),
            'user_id'      => $userId,
            'judul'        => $judul,
            'pesan'        => $pesan,
            'pengajuan_id' => $pengajuanId,
            'is_read'      => 0,
        ]);
    }
}
