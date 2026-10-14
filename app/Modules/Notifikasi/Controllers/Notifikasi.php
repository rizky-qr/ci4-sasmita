<?php

namespace App\Modules\Notifikasi\Controllers;

use App\Controllers\BaseController;
use App\Modules\Notifikasi\Models\M_notifikasi;

/**
 * Notifikasi — Endpoint untuk semua role.
 * index()       → halaman notifikasi (redirect ke halaman role masing-masing)
 * markRead()    → AJAX / form POST mark 1 notif as read
 * markAllRead() → Mark semua sebagai read
 * count()       → JSON endpoint jumlah unread (untuk badge topbar)
 */
class Notifikasi extends BaseController
{
    protected M_notifikasi $model;

    public function __construct()
    {
        $this->model = new M_notifikasi();
    }

    /**
     * GET /notifikasi — Tampilkan semua notifikasi user yang login,
     * lalu redirect ke halaman notifikasi sesuai role.
     */
    public function index()
    {
        $role = session('role');

        // Arahkan ke halaman notifikasi per-role agar sidebar benar
        $map = [
            'MAHASISWA'     => '/dashboard/mahasiswa/notifikasi',
            'STAF_TU_PRODI' => '/dashboard/staf-tu/inbox',
            'KAPRODI'       => '/dashboard/kaprodi/inbox',
            'DEKAN'         => '/dashboard/dekan/inbox',
            'ADMIN'         => '/dashboard/admin',
        ];

        $userId      = session('user_id');
        $notifikasi  = $this->model->getByUser($userId);

        // Mark semua dibaca saat halaman dibuka
        $this->model->markAllRead($userId);

        return view('App\Modules\Notifikasi\Views\v_notifikasi', [
            'title'      => 'Notifikasi',
            'notifikasi' => $notifikasi,
            'backUrl'    => $map[$role] ?? '/auth/login',
            'user'       => session()->get(),
        ]);
    }

    /**
     * POST /notifikasi/read/{id} — Mark satu notifikasi sebagai sudah dibaca.
     */
    public function markRead(string $id)
    {
        $this->model->markRead($id, session('user_id'));

        // Jika AJAX, return JSON
        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['ok' => true]);
        }

        return redirect()->back();
    }

    /**
     * POST /notifikasi/read-all — Mark semua notifikasi sebagai sudah dibaca.
     */
    public function markAllRead()
    {
        $this->model->markAllRead(session('user_id'));

        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['ok' => true]);
        }

        return redirect()->back()->with('success', 'Semua notifikasi telah ditandai dibaca.');
    }

    /**
     * GET /notifikasi/count — JSON endpoint untuk badge jumlah unread.
     * Dipanggil via AJAX dari topbar.
     */
    public function count()
    {
        $count = $this->model->countUnread(session('user_id'));
        return $this->response->setJSON(['unread' => $count]);
    }
}
