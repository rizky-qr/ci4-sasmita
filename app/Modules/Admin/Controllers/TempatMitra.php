<?php

namespace App\Modules\Admin\Controllers;

use App\Controllers\BaseController;
use App\Modules\Admin\Models\M_mitra;

class TempatMitra extends BaseController
{
    protected M_mitra $model;
    public function __construct() { $this->model = new M_mitra(); }

    public function index()
    {
        return view('App\Modules\Admin\Views\v_tempat_mitra', [
            'title' => 'Manajemen Tempat Mitra',
            'mitra' => $this->model->orderBy('created_at','DESC')->findAll(),
            'user'  => session()->get(),
        ]);
    }

    public function simpan()
    {
        $rules = ['nama'=>'required','alamat'=>'required','kategori'=>'required|in_list[PENELITIAN,OBSERVASI,PLP_MAGANG]'];
        if (! $this->validate($rules)) return redirect()->back()->withInput()->with('errors',$this->validator->getErrors());

        $this->model->insert([
            'id'         => $this->uuid(),
            'nama'       => $this->request->getPost('nama'),
            'alamat'     => $this->request->getPost('alamat'),
            'telepon'    => $this->request->getPost('telepon'),
            'nama_kepala'=> $this->request->getPost('nama_kepala'),
            'kategori'   => $this->request->getPost('kategori'),
            'is_active'  => 1,
        ]);
        return redirect()->to('/dashboard/admin/mitra')->with('success','Tempat mitra berhasil ditambahkan.');
    }

    public function update(string $id)
    {
        $this->model->update($id, [
            'nama'       => $this->request->getPost('nama'),
            'alamat'     => $this->request->getPost('alamat'),
            'telepon'    => $this->request->getPost('telepon'),
            'nama_kepala'=> $this->request->getPost('nama_kepala'),
            'kategori'   => $this->request->getPost('kategori'),
            'is_active'  => $this->request->getPost('is_active') ? 1 : 0,
        ]);
        return redirect()->to('/dashboard/admin/mitra')->with('success','Tempat mitra diperbarui.');
    }

    public function hapus(string $id)
    {
        $this->model->delete($id);
        return redirect()->to('/dashboard/admin/mitra')->with('success','Tempat mitra dihapus.');
    }

    private function uuid(): string
    {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0,0xffff),mt_rand(0,0xffff),mt_rand(0,0xffff),
            mt_rand(0,0x0fff)|0x4000,mt_rand(0,0x3fff)|0x8000,
            mt_rand(0,0xffff),mt_rand(0,0xffff),mt_rand(0,0xffff));
    }
}
