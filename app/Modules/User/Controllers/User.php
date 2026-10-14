<?php

namespace App\Modules\User\Controllers;

use App\Controllers\BaseController;
use App\Modules\User\Models\M_user;

/**
 * User — Controller untuk manajemen profil user.
 * (Manajemen admin ada di Modul Admin)
 */
class User extends BaseController
{
    protected M_user $model;

    public function __construct()
    {
        $this->model = new M_user();
    }

    /**
     * GET /user — Daftar semua user (dipakai Admin).
     */
    public function index()
    {
        $data['users'] = $this->model->findAll();
        $data['title'] = 'Daftar User';

        return view('App\Modules\User\Views\v_user', $data);
    }

    /**
     * GET /user/profile — Profil user yang sedang login.
     */
    public function profile()
    {
        $userId = session('user_id');
        $user   = $this->model->find($userId);

        if (! $user) {
            return redirect()->to('/auth/login')->with('error', 'User tidak ditemukan.');
        }

        $data['user']  = $user;
        $data['title'] = 'Profil Saya';

        return view('App\Modules\User\Views\v_profile', $data);
    }

    /**
     * POST /user/update/{id} — Update profil user.
     */
    public function update(string $id)
    {
        $userId = session('user_id');

        // Hanya boleh update profil sendiri, kecuali ADMIN
        if ($userId !== $id && session('role') !== 'ADMIN') {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        $rules = [
            'nama'   => 'required|max_length[255]',
            'no_hp'  => 'permit_empty|max_length[20]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $updateData = [
            'nama'     => $this->request->getPost('nama'),
            'no_hp'    => $this->request->getPost('no_hp'),
            'angkatan' => $this->request->getPost('angkatan'),
            'semester' => $this->request->getPost('semester'),
        ];

        // Handle upload foto
        $foto = $this->request->getFile('foto');
        if ($foto && $foto->isValid() && ! $foto->hasMoved()) {
            $newName = $foto->getRandomName();
            $foto->move(ROOTPATH . 'public/uploads/foto', $newName);
            $updateData['foto'] = 'uploads/foto/' . $newName;
        }

        // Update password jika diisi
        $newPassword = $this->request->getPost('password');
        if (!empty($newPassword)) {
            if (strlen($newPassword) < 6) {
                return redirect()->back()->withInput()->with('error', 'Password baru minimal 6 karakter.');
            }
            $updateData['password'] = $this->model->hashPassword($newPassword);
        }

        $this->model->update($id, $updateData);

        // Refresh session jika update diri sendiri
        if ($userId === $id) {
            session()->set('nama', $updateData['nama']);
            if (isset($updateData['foto'])) {
                session()->set('foto', $updateData['foto']);
            }
        }

        return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
    }
}
