<?php
$sidebarNav = '
<span class="nav-section-label">Menu</span>
<a href="/dashboard/admin" class="nav-item"><span class="nav-icon">🏠</span> Dashboard</a>
<span class="nav-section-label">Kelola Data</span>
<a href="/dashboard/admin/users"  class="nav-item active"><span class="nav-icon">👥</span> Manajemen User</a>
<a href="/dashboard/admin/mitra"  class="nav-item"><span class="nav-icon">🏢</span> Tempat Mitra</a>
<span class="nav-section-label">Laporan</span>
<a href="/dashboard/admin/laporan" class="nav-item"><span class="nav-icon">📊</span> Laporan</a>
<span class="nav-section-label">Akun</span>
<a href="/user/profile" class="nav-item"><span class="nav-icon">👤</span> Profil Saya</a>
';
$notifUrl = '/notifikasi';
$roleBadgeColor = ['MAHASISWA'=>'#6366f1','STAF_TU_PRODI'=>'#fbbf24','KAPRODI'=>'#34d399','DEKAN'=>'#60a5fa','ADMIN'=>'#f87171'];
ob_start();
?>
<div class="card">
    <div class="card-header">
        <div><div class="card-title">Manajemen User</div><div class="card-sub"><?= count($users) ?> user terdaftar</div></div>
        <a href="/dashboard/admin/users/tambah" class="btn btn-primary btn-sm">+ Tambah User</a>
    </div>

    <!-- Filter -->
    <form method="get" style="display:flex;gap:.75rem;margin-bottom:1rem;flex-wrap:wrap">
        <input type="text" name="cari" value="<?= esc($_GET['cari']??'') ?>" placeholder="Cari nama / email / NIM..." style="flex:1;min-width:200px">
        <select name="role" style="min-width:160px">
            <option value="">Semua Role</option>
            <?php foreach (['MAHASISWA','STAF_TU_PRODI','KAPRODI','DEKAN','ADMIN'] as $r): ?>
            <option value="<?= $r ?>" <?= (($_GET['role']??'') === $r) ? 'selected' : '' ?>><?= $r ?></option>
            <?php endforeach ?>
        </select>
        <button type="submit" class="btn btn-outline">🔍 Filter</button>
    </form>

    <?php if (empty($users)): ?>
        <div class="empty-state"><div class="empty-icon">👤</div><div class="empty-title">Tidak ada user</div></div>
    <?php else: ?>
        <div class="table-wrapper"><table>
            <thead><tr><th>#</th><th>Nama</th><th>Email</th><th>NIM/NIP</th><th>Role</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
            <?php foreach ($users as $i => $u): ?>
            <tr>
                <td style="color:var(--muted)"><?= $i+1 ?></td>
                <td style="font-weight:500"><?= esc($u['nama']) ?></td>
                <td style="color:var(--muted);font-size:.8rem"><?= esc($u['email']) ?></td>
                <td style="color:var(--muted)"><?= esc($u['nim'] ?? $u['nip'] ?? '—') ?></td>
                <td>
                    <span class="badge" style="background:<?= $roleBadgeColor[$u['role']]??'#6366f1' ?>22;color:<?= $roleBadgeColor[$u['role']]??'#6366f1' ?>;border-color:<?= $roleBadgeColor[$u['role']]??'#6366f1' ?>44"><?= esc($u['role']) ?></span>
                </td>
                <td>
                    <?php if ($u['is_active']): ?>
                        <span class="badge badge-disetujui">Aktif</span>
                    <?php else: ?>
                        <span class="badge badge-ditolak">Nonaktif</span>
                    <?php endif ?>
                </td>
                <td style="display:flex;gap:.4rem">
                    <a href="/dashboard/admin/users/edit/<?= $u['id'] ?>" class="btn btn-outline btn-sm">✏️ Edit</a>
                    <form action="/dashboard/admin/users/hapus/<?= $u['id'] ?>" method="post" onsubmit="return confirm('Hapus user <?= esc($u['nama']) ?>?')">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-danger btn-sm">🗑</button>
                    </form>
                </td>
            </tr>
            <?php endforeach ?>
            </tbody>
        </table></div>
    <?php endif ?>
</div>
<?php $pageContent = ob_get_clean(); require APPPATH . 'Views/layouts/main.php';
