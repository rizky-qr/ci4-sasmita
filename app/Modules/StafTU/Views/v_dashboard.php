<?php
// v_dashboard.php — StafTU Dashboard
$sidebarNav = '
<span class="nav-section-label">Menu</span>
<a href="/dashboard/staf-tu" class="nav-item active"><span class="nav-icon">🏠</span> Dashboard</a>
<span class="nav-section-label">Inbox</span>
<a href="/dashboard/staf-tu/inbox" class="nav-item"><span class="nav-icon">📥</span> Menunggu Verifikasi</a>
<a href="/dashboard/staf-tu/diproses" class="nav-item"><span class="nav-icon">⏳</span> Diteruskan</a>
<a href="/dashboard/staf-tu/ditolak" class="nav-item"><span class="nav-icon">❌</span> Ditolak</a>
<a href="/dashboard/staf-tu/semua" class="nav-item"><span class="nav-icon">📋</span> Semua Pengajuan</a>
<span class="nav-section-label">Akun</span>
<a href="/user/profile" class="nav-item"><span class="nav-icon">👤</span> Profil Saya</a>
';
$notifUrl = '/notifikasi';
ob_start();
$pendingCount = count($pending);
?>

<div class="stats-grid">
    <div class="stat-card" style="--stat-color:#fbbf24">
        <div class="stat-icon">📥</div>
        <div class="stat-value"><?= $pendingCount ?></div>
        <div class="stat-label">Menunggu Verifikasi</div>
    </div>
    <div class="stat-card" style="--stat-color:#6366f1">
        <div class="stat-icon">📊</div>
        <div class="stat-value"><?= array_sum($stats) ?></div>
        <div class="stat-label">Total Semua Pengajuan</div>
    </div>
    <div class="stat-card" style="--stat-color:#34d399">
        <div class="stat-icon">✅</div>
        <div class="stat-value"><?= ($stats['SELESAI'] ?? 0) ?></div>
        <div class="stat-label">Selesai</div>
    </div>
    <div class="stat-card" style="--stat-color:#f87171">
        <div class="stat-icon">❌</div>
        <div class="stat-value"><?= ($stats['DITOLAK'] ?? 0) ?></div>
        <div class="stat-label">Ditolak</div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div>
            <div class="card-title">Pengajuan Menunggu Verifikasi</div>
            <div class="card-sub">Perlu tindakan segera</div>
        </div>
        <a href="/dashboard/staf-tu/inbox" class="btn btn-primary btn-sm">Buka Inbox →</a>
    </div>

    <?php if (empty($pending)): ?>
        <div class="empty-state">
            <div class="empty-icon">🎉</div>
            <div class="empty-title">Semua bersih!</div>
            <div class="empty-sub">Tidak ada pengajuan yang menunggu verifikasi.</div>
        </div>
    <?php else: ?>
        <div class="table-wrapper">
            <table>
                <thead><tr><th>Mahasiswa</th><th>NIM</th><th>Jenis Surat</th><th>Tanggal</th><th></th></tr></thead>
                <tbody>
                <?php foreach (array_slice($pending, 0, 5) as $p): ?>
                <tr>
                    <td style="font-weight:500"><?= esc($p['mahasiswa_nama']) ?></td>
                    <td style="color:var(--muted)"><?= esc($p['mahasiswa_nim']) ?></td>
                    <td><?= esc(str_replace('_', ' ', $p['jenis_surat'])) ?></td>
                    <td style="color:var(--muted)"><?= date('d M Y', strtotime($p['created_at'])) ?></td>
                    <td><a href="/dashboard/staf-tu/detail/<?= $p['id'] ?>" class="btn btn-outline btn-sm">Proses</a></td>
                </tr>
                <?php endforeach ?>
                </tbody>
            </table>
        </div>
    <?php endif ?>
</div>

<?php
$pageContent = ob_get_clean();
require APPPATH . 'Views/layouts/main.php';
