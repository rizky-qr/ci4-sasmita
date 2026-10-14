<?php

namespace App\Modules\User\Models;

use CodeIgniter\Model;

/**
 * M_user — Model utama untuk tabel `users`.
 * Digunakan oleh Modul User (CRUD) dan di-reuse oleh modul lain.
 */
class M_user extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = false; // UUID CHAR(36)
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'id', 'email', 'password', 'nama',
        'nim', 'nip', 'role', 'program',
        'angkatan', 'semester', 'no_hp', 'foto', 'is_active',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'email'    => 'required|valid_email',
        'password' => 'required|min_length[6]',
        'nama'     => 'required|max_length[255]',
        'role'     => 'required|in_list[MAHASISWA,STAF_TU_PRODI,KAPRODI,DEKAN,ADMIN]',
    ];

    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Cari user aktif berdasarkan email.
     */
    public function findByEmail(string $email): ?array
    {
        return $this->where('email', $email)
                    ->where('is_active', 1)
                    ->first();
    }

    /**
     * Ambil semua user berdasarkan role.
     */
    public function getByRole(string $role): array
    {
        return $this->where('role', $role)->findAll();
    }

    /**
     * Ambil daftar user untuk admin (semua role, bisa filter).
     */
    public function getForAdmin(?string $role = null, ?string $search = null): array
    {
        $builder = $this->orderBy('created_at', 'DESC');

        if ($role) {
            $builder->where('role', $role);
        }

        if ($search) {
            $builder->groupStart()
                        ->like('nama', $search)
                        ->orLike('email', $search)
                        ->orLike('nim', $search)
                    ->groupEnd();
        }

        return $builder->findAll();
    }

    /**
     * Hash password sebelum simpan (override insert & update).
     */
    public function hashPassword(string $plain): string
    {
        return password_hash($plain, PASSWORD_BCRYPT);
    }
}
