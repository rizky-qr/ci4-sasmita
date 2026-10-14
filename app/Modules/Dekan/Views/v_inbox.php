<?php
$sidebarNav = '
<span class="nav-section-label">Menu</span>
<a href="/dashboard/dekan" class="nav-item"><span class="nav-icon">🏠</span> Dashboard</a>
<a href="/dashboard/dekan/inbox"   class="nav-item ' . ($tab==='menunggu'?'active':'') . '"><span class="nav-icon">📥</span> Menunggu Pengesahan</a>
<a href="/dashboard/dekan/selesai" class="nav-item ' . ($tab==='selesai'?'active':'') . '"><span class="nav-icon">✅</span> Surat Selesai</a>
<a href="/dashboard/dekan/laporan" class="nav-item"><span class="nav-icon">📊</span> Laporan</a>
<span class="nav-section-label">Akun</span>
<a href="/user/profile" class="nav-item"><span class="nav-icon">👤</span> Profil Saya</a>
';
$notifUrl = '/notifikasi';
require APPPATH . 'Views/partials/status_helpers.php';
ob_start();
?>
<div style="display:flex;gap:.5rem;margin-bottom:1.25rem;border-bottom:1px solid var(--border);padding-bottom:.75rem">
    <a href="/dashboard/dekan/inbox"   class="btn <?= $tab==='menunggu'?'btn-primary':'btn-outline' ?> btn-sm">Menunggu</a>
    <a href="/dashboard/dekan/selesai" class="btn <?= $tab==='selesai'?'btn-primary':'btn-outline'  ?> btn-sm">Selesai</a>
</div>
<div class="card">
    <div class="card-header">
        <div><div class="card-title"><?= esc($title) ?></div><div class="card-sub"><?= count($daftar) ?> pengajuan</div></div>
    </div>
    <?php if (empty($daftar)): ?>
        <div class="empty-state"><div class="empty-icon">📭</div><div class="empty-title">Tidak ada data</div></div>
    <?php else: ?>
        <div class="table-wrapper"><table>
            <thead><tr><th>#</th><th>Mahasiswa</th><th>NIM</th><th>Jenis Surat</th><th>No. Surat</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
            <?php foreach ($daftar as $i => $p): ?>
            <tr>
                <td style="color:var(--muted)"><?= $i+1 ?></td>
                <td style="font-weight:500"><?= esc($p['mahasiswa_nama']) ?></td>
                <td style="color:var(--muted)"><?= esc($p['mahasiswa_nim']) ?></td>
                <td><?= esc(str_replace('_',' ',$p['jenis_surat'])) ?></td>
                <td style="color:var(--muted);font-size:.78rem"><?= esc($p['nomor_surat'] ?? '—') ?></td>
                <td><?= statusBadge($p['status']) ?></td>
                <td><a href="/dashboard/dekan/detail/<?= $p['id'] ?>" class="btn btn-outline btn-sm"><?= $tab==='menunggu'?'Sahkan':'Detail' ?></a></td>
            </tr>
            <?php endforeach ?>
            </tbody>
        </table></div>
    <?php endif ?>
</div>
<?php $pageContent = ob_get_clean(); require APPPATH . 'Views/layouts/main.php';

