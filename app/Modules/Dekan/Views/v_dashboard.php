<?php
$sidebarNav = '
<span class="nav-section-label">Menu</span>
<a href="/dashboard/dekan" class="nav-item active"><span class="nav-icon">🏠</span> Dashboard</a>
<span class="nav-section-label">Inbox</span>
<a href="/dashboard/dekan/inbox"   class="nav-item"><span class="nav-icon">📥</span> Menunggu Pengesahan</a>
<a href="/dashboard/dekan/selesai" class="nav-item"><span class="nav-icon">✅</span> Surat Selesai</a>
<a href="/dashboard/dekan/laporan" class="nav-item"><span class="nav-icon">📊</span> Laporan</a>
<span class="nav-section-label">Akun</span>
<a href="/user/profile" class="nav-item"><span class="nav-icon">👤</span> Profil Saya</a>
';
$notifUrl = '/notifikasi';
ob_start();
?>
<div class="stats-grid">
    <div class="stat-card" style="--stat-color:#fbbf24">
        <div class="stat-icon">📥</div>
        <div class="stat-value"><?= count($pending) ?></div>
        <div class="stat-label">Menunggu Pengesahan</div>
    </div>
    <div class="stat-card" style="--stat-color:#34d399">
        <div class="stat-icon">✅</div>
        <div class="stat-value"><?= ($stats['SELESAI'] ?? 0) ?></div>
        <div class="stat-label">Surat Selesai</div>
    </div>
    <div class="stat-card" style="--stat-color:#6366f1">
        <div class="stat-icon">📊</div>
        <div class="stat-value"><?= array_sum($stats) ?></div>
        <div class="stat-label">Total Pengajuan</div>
    </div>
    <div class="stat-card" style="--stat-color:#f87171">
        <div class="stat-icon">❌</div>
        <div class="stat-value"><?= ($stats['DITOLAK'] ?? 0) ?></div>
        <div class="stat-label">Ditolak</div>
    </div>
</div>
<div class="card">
    <div class="card-header">
        <div class="card-title">Menunggu Pengesahan Dekan</div>
        <a href="/dashboard/dekan/inbox" class="btn btn-primary btn-sm">Buka Inbox →</a>
    </div>
    <?php if (empty($pending)): ?>
        <div class="empty-state"><div class="empty-icon">🎉</div><div class="empty-title">Tidak ada yang menunggu</div></div>
    <?php else: ?>
        <div class="table-wrapper"><table>
            <thead><tr><th>Mahasiswa</th><th>NIM</th><th>Jenis Surat</th><th>Tanggal</th><th></th></tr></thead>
            <tbody>
            <?php foreach (array_slice($pending, 0, 5) as $p): ?>
            <tr>
                <td style="font-weight:500"><?= esc($p['mahasiswa_nama']) ?></td>
                <td style="color:var(--muted)"><?= esc($p['mahasiswa_nim']) ?></td>
                <td><?= esc(str_replace('_',' ',$p['jenis_surat'])) ?></td>
                <td style="color:var(--muted)"><?= date('d M Y', strtotime($p['created_at'])) ?></td>
                <td><a href="/dashboard/dekan/detail/<?= $p['id'] ?>" class="btn btn-primary btn-sm">✅ Sahkan</a></td>
            </tr>
            <?php endforeach ?>
            </tbody>
        </table></div>
    <?php endif ?>
</div>
<?php $pageContent = ob_get_clean(); require APPPATH . 'Views/layouts/main.php';
