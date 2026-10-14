<?php

namespace App\Modules\Admin\Controllers;

use App\Controllers\BaseController;
use App\Modules\Admin\Models\M_user_admin;

class ManajemenUser extends BaseController
{
    protected M_user_admin $model;

    public function __construct()
    {
        $this->model = new M_user_admin();
    }

    public function index()
    {
        return view('App\Modules\Admin\Views\v_manajemen_user', [
            'title' => 'Manajemen User',
            'users' => $this->model->getWithFilter(
                $this->request->getGet('role'),
                $this->request->getGet('cari')
            ),
            'user'  => session()->get(),
        ]);
    }

    public function tambah()
    {
        return view('App\Modules\Admin\Views\v_form_user', [
            'title'     => 'Tambah User Baru',
            'editMode'  => false,
            'editUser'  => null,
            'user'      => session()->get(),
        ]);
    }

    public function simpan()
    {
        $rules = [
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'nama'     => 'required',
            'role'     => 'required|in_list[MAHASISWA,STAF_TU_PRODI,KAPRODI,DEKAN,ADMIN]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->model->insert([
            'id'       => $this->uuid(),
            'email'    => $this->request->getPost('email'),
            'password' => $this->model->hashPassword($this->request->getPost('password')),
            'nama'     => $this->request->getPost('nama'),
            'nim'      => $this->request->getPost('nim'),
            'nip'      => $this->request->getPost('nip'),
            'role'     => $this->request->getPost('role'),
            'program'  => $this->request->getPost('program'),
            'angkatan' => $this->request->getPost('angkatan'),
            'semester' => $this->request->getPost('semester'),
            'no_hp'    => $this->request->getPost('no_hp'),
            'is_active'=> 1,
        ]);

        return redirect()->to('/dashboard/admin/users')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(string $id)
    {
        $editUser = $this->model->find($id);
        if (! $editUser) return redirect()->to('/dashboard/admin/users')->with('error', 'User tidak ditemukan.');

        return view('App\Modules\Admin\Views\v_form_user', [
            'title'    => 'Edit User',
            'editMode' => true,
            'editUser' => $editUser,
            'user'     => session()->get(),
        ]);
    }

    public function update(string $id)
    {
        $rules = [
            'nama'  => 'required',
            'email' => "required|valid_email|is_unique[users.email,id,{$id}]",
            'role'  => 'required|in_list[MAHASISWA,STAF_TU_PRODI,KAPRODI,DEKAN,ADMIN]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'email'    => $this->request->getPost('email'),
            'nama'     => $this->request->getPost('nama'),
            'nim'      => $this->request->getPost('nim'),
            'nip'      => $this->request->getPost('nip'),
            'role'     => $this->request->getPost('role'),
            'program'  => $this->request->getPost('program'),
            'angkatan' => $this->request->getPost('angkatan'),
            'semester' => $this->request->getPost('semester'),
            'no_hp'    => $this->request->getPost('no_hp'),
            'is_active'=> $this->request->getPost('is_active') ? 1 : 0,
        ];

        $pass = $this->request->getPost('password');
        if ($pass) $data['password'] = $this->model->hashPassword($pass);

        $this->model->update($id, $data);
        return redirect()->to('/dashboard/admin/users')->with('success', 'User berhasil diperbarui.');
    }

    public function hapus(string $id)
    {
        $this->model->delete($id);
        return redirect()->to('/dashboard/admin/users')->with('success', 'User berhasil dihapus.');
    }

    private function uuid(): string
    {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0,0xffff),mt_rand(0,0xffff),mt_rand(0,0xffff),
            mt_rand(0,0x0fff)|0x4000,mt_rand(0,0x3fff)|0x8000,
            mt_rand(0,0xffff),mt_rand(0,0xffff),mt_rand(0,0xffff));
    }
}
