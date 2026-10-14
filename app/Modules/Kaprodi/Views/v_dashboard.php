<?php
$sidebarNav = '
<span class="nav-section-label">Menu</span>
<a href="/dashboard/kaprodi" class="nav-item active"><span class="nav-icon">🏠</span> Dashboard</a>
<span class="nav-section-label">Inbox</span>
<a href="/dashboard/kaprodi/inbox" class="nav-item"><span class="nav-icon">📥</span> Menunggu Persetujuan</a>
<a href="/dashboard/kaprodi/riwayat" class="nav-item"><span class="nav-icon">📂</span> Riwayat</a>
<a href="/dashboard/kaprodi/statistik" class="nav-item"><span class="nav-icon">📊</span> Statistik</a>
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
        <div class="stat-label">Menunggu Persetujuan</div>
    </div>
    <div class="stat-card" style="--stat-color:#6366f1">
        <div class="stat-icon">📊</div>
        <div class="stat-value"><?= array_sum($stats) ?></div>
        <div class="stat-label">Total Pengajuan</div>
    </div>
    <div class="stat-card" style="--stat-color:#34d399">
        <div class="stat-icon">✅</div>
        <div class="stat-value"><?= ($stats['SELESAI'] ?? 0) ?></div>
        <div class="stat-label">Selesai</div>
    </div>
    <div class="stat-card" style="--stat-color:#60a5fa">
        <div class="stat-icon">➡️</div>
        <div class="stat-value"><?= ($stats['MENUNGGU_DEKAN'] ?? 0) ?></div>
        <div class="stat-label">Diteruskan ke Dekan</div>
    </div>
</div>
<div class="card">
    <div class="card-header">
        <div class="card-title">Pengajuan Menunggu Persetujuan</div>
        <a href="/dashboard/kaprodi/inbox" class="btn btn-primary btn-sm">Buka Inbox →</a>
    </div>
    <?php if (empty($pending)): ?>
        <div class="empty-state"><div class="empty-icon">🎉</div><div class="empty-title">Semua bersih!</div></div>
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
                <td><a href="/dashboard/kaprodi/detail/<?= $p['id'] ?>" class="btn btn-outline btn-sm">Proses</a></td>
            </tr>
            <?php endforeach ?>
            </tbody>
        </table></div>
    <?php endif ?>
</div>
<?php $pageContent = ob_get_clean(); require APPPATH . 'Views/layouts/main.php';
