<?php

namespace App\Modules\Notifikasi\Models;

/**
 * M_notifikasi — Query helper untuk tabel `notifikasi`.
 */
class M_notifikasi
{
    protected \CodeIgniter\Database\BaseConnection $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function getByUser(string $userId): array
    {
        return $this->db->table('notifikasi')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->get()->getResultArray();
    }

    public function countUnread(string $userId): int
    {
        return $this->db->table('notifikasi')
            ->where('user_id', $userId)
            ->where('is_read', 0)
            ->countAllResults();
    }

    public function markRead(string $id, string $userId): void
    {
        $this->db->table('notifikasi')
            ->where('id', $id)
            ->where('user_id', $userId)
            ->update(['is_read' => 1]);
    }

    public function markAllRead(string $userId): void
    {
        $this->db->table('notifikasi')
            ->where('user_id', $userId)
            ->where('is_read', 0)
            ->update(['is_read' => 1]);
    }
}
