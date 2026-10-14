<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * RoleFilter — Membatasi akses route berdasarkan role user yang sedang login.
 * Penggunaan di Routes.php: ['filter' => 'role:MAHASISWA'] atau ['filter' => 'role:ADMIN,KAPRODI']
 */
class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // $arguments berisi array role yang diizinkan, misal ['ADMIN', 'KAPRODI']
        if (empty($arguments)) {
            return; // Tidak ada pembatasan role
        }

        $userRole = session()->get('role');

        if (! in_array($userRole, $arguments, true)) {
            // Role tidak diizinkan → redirect ke dashboard yang sesuai
            return redirect()->to($this->getDashboardByRole($userRole))
                             ->with('error', 'Akses ditolak. Anda tidak memiliki izin untuk halaman ini.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak ada aksi setelah request
    }

    /**
     * Redirect user ke dashboard sesuai role-nya jika mencoba akses halaman yang salah.
     */
    private function getDashboardByRole(?string $role): string
    {
        $map = [
            'MAHASISWA'     => '/dashboard/mahasiswa',
            'STAF_TU_PRODI' => '/dashboard/staf-tu',
            'KAPRODI'       => '/dashboard/kaprodi',
            'DEKAN'         => '/dashboard/dekan',
            'ADMIN'         => '/dashboard/admin',
        ];

        return $map[$role] ?? '/auth/login';
    }
}
