<?php

namespace App\Modules\Admin\Models;

use CodeIgniter\Model;

class M_user_admin extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'id','email','password','nama','nim','nip','role','program',
        'angkatan','semester','no_hp','foto','is_active',
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getWithFilter(?string $role = null, ?string $search = null): array
    {
        $b = $this->orderBy('created_at','DESC');
        if ($role) $b->where('role', $role);
        if ($search) {
            $b->groupStart()->like('nama',$search)->orLike('email',$search)->orLike('nim',$search)->groupEnd();
        }
        return $b->findAll();
    }

    public function hashPassword(string $plain): string
    {
        return password_hash($plain, PASSWORD_BCRYPT);
    }
}
