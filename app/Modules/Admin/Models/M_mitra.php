<?php

namespace App\Modules\Admin\Models;

use CodeIgniter\Model;

class M_mitra extends Model
{
    protected $table            = 'tempat_mitra';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $allowedFields    = ['id','nama','alamat','telepon','nama_kepala','kategori','is_active'];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
}
