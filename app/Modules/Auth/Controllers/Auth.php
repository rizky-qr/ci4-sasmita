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
     * GET /auth/register — Halaman pendaftaran mahasiswa baru.
     */
    public function register()
    {
        if (session()->get('isLoggedIn')) {
            return $this->redirectByRole(session()->get('role'));
        }

        return view('App\Modules\Auth\Views\v_register');
    }

    /**
     * POST /auth/register — Proses pendaftaran dengan keamanan tingkat tinggi.
     */
    public function processRegister()
    {
        // 1. Anti-bot honeypot check (jika field terisi robot, tolak hening)
        if (! empty($this->request->getPost('website'))) {
            return redirect()->to('/login');
        }

        // 2. Validasi input ketat
        $rules = [
            'nama'             => 'required|min_length[3]|max_length[100]',
            'nim'              => 'required|numeric|min_length[6]|max_length[20]|is_unique[users.nim]',
            'email'            => 'required|valid_email|is_unique[users.email]|max_length[100]',
            'program'          => 'required|in_list[PENDIDIKAN_MASYARAKAT,PENDIDIKAN_BAHASA_INGGRIS,TEKNOLOGI_PENDIDIKAN,PENDIDIKAN_VOKASIONAL_DESAIN_FASHION,PENDIDIKAN_MATEMATIKA,PENDIDIKAN_KEPELATIHAN_OLAHRAGA,PENDIDIKAN_PROFESI_GURU]',
            'password'         => 'required|min_length[8]|regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/]',
            'password_confirm' => 'required|matches[password]',
            'no_hp'            => 'permit_empty|numeric|min_length[10]|max_length[15]',
            'angkatan'         => 'permit_empty|numeric|exact_length[4]',
        ];

        $messages = [
            'nim' => [
                'is_unique' => 'NIM ini sudah terdaftar di sistem SASMITA.',
                'numeric'   => 'NIM harus berupa angka.',
            ],
            'email' => [
                'is_unique'   => 'Alamat email ini sudah terdaftar di sistem.',
                'valid_email' => 'Format email tidak valid.',
            ],
            'password' => [
                'min_length'  => 'Password minimal terdiri dari 8 karakter.',
                'regex_match' => 'Password wajib mengandung kombinasi huruf besar, huruf kecil, dan angka.',
            ],
            'password_confirm' => [
                'matches' => 'Konfirmasi password tidak cocok dengan password di atas.',
            ],
        ];

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->validator->getErrors());
        }

        // 3. Sanitasi & Penyiapan data (Role mutlak 'MAHASISWA' demi keamanan)
        $db = \Config\Database::connect();
        $userId = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );

        $cleanNama     = strip_tags(trim($this->request->getPost('nama')));
        $cleanEmail    = strtolower(trim($this->request->getPost('email')));
        $cleanNim      = trim($this->request->getPost('nim'));
        $cleanProgram  = $this->request->getPost('program');
        $cleanHp       = trim((string) $this->request->getPost('no_hp'));
        $cleanAngkatan = (int) ($this->request->getPost('angkatan') ?: date('Y'));

        // 4. Hash password dengan Bcrypt cost 12
        $passwordHash = password_hash($this->request->getPost('password'), PASSWORD_BCRYPT, ['cost' => 12]);

        $db->table('users')->insert([
            'id'         => $userId,
            'email'      => $cleanEmail,
            'password'   => $passwordHash,
            'nama'       => $cleanNama,
            'nim'        => $cleanNim,
            'role'       => 'MAHASISWA', // Mutlak di-kunci di server
            'program'    => $cleanProgram,
            'angkatan'   => $cleanAngkatan,
            'no_hp'      => $cleanHp,
            'is_active'  => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/login')->with('success', 'Pendaftaran akun mahasiswa berhasil! Silakan masuk dengan email dan password Anda.');
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
