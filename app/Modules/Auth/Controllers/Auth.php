<?php

namespace App\Modules\Auth\Controllers;

use App\Controllers\BaseController;
use App\Modules\Auth\Models\M_auth;

/**
 * Auth — Controller untuk login & logout SASMITA.
 */
class Auth extends BaseController
{
    protected M_auth $model;

    public function __construct()
    {
        $this->model = new M_auth();
    }

    /**
     * GET /auth/login — Tampilkan halaman login.
     * Jika sudah login, redirect ke dashboard sesuai role.
     */
    public function index()
    {
        if (session()->get('isLoggedIn')) {
            return $this->redirectByRole(session()->get('role'));
        }

        return view('App\Modules\Auth\Views\v_login');
    }

    /**
     * POST /auth/login — Proses autentikasi.
     */
    public function login()
    {
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required|min_length[6]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->validator->getErrors());
        }

        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $this->model->findByEmail($email);

        if (! $user || ! password_verify($password, $user['password'])) {
            return redirect()->back()
                             ->withInput()
                             ->with('error', 'Email atau password salah.');
        }

        // Simpan session
        session()->set([
            'isLoggedIn' => true,
            'user_id'    => $user['id'],
            'nama'       => $user['nama'],
            'email'      => $user['email'],
            'role'       => $user['role'],
            'nim'        => $user['nim']    ?? null,
            'nip'        => $user['nip']    ?? null,
            'program'    => $user['program'] ?? null,
            'foto'       => $user['foto']   ?? null,
        ]);

        return $this->redirectByRole($user['role']);
    }

    /**
     * GET /auth/logout — Hancurkan session dan kembali ke login.
     */
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/auth/login')->with('success', 'Anda berhasil logout.');
    }

    /**
     * Redirect user ke dashboard sesuai role-nya.
     */
    private function redirectByRole(string $role)
    {
        $map = [
            'MAHASISWA'     => '/dashboard/mahasiswa',
            'STAF_TU_PRODI' => '/dashboard/staf-tu',
            'KAPRODI'       => '/dashboard/kaprodi',
            'DEKAN'         => '/dashboard/dekan',
            'ADMIN'         => '/dashboard/admin',
        ];

        return redirect()->to($map[$role] ?? '/auth/login');
    }
}
