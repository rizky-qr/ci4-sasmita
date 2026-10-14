<?php

namespace App\Modules\Auth\Models;

use CodeIgniter\Model;

/**
 * M_auth — Model untuk autentikasi user.
 * Hanya menangani pencarian user berdasarkan email (login).
 * CRUD user ada di M_user (Modul User).
 */
class M_auth extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = false; // UUID CHAR(36)
    protected $returnType       = 'array';

    protected $allowedFields = ['email', 'password', 'role', 'nama', 'is_active'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Cari user berdasarkan email (untuk proses login).
     */
    public function findByEmail(string $email): ?array
    {
        return $this->where('email', $email)
                    ->where('is_active', 1)
                    ->first();
    }
}
