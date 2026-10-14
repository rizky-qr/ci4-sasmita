<?php
// v_laporan.php — Laporan global (Admin & Dekan share struktur yang sama)
$isAdmin    = session('role') === 'ADMIN';
$basePath   = $isAdmin ? '/dashboard/admin' : '/dashboard/dekan';
$sidebarNav = $isAdmin ? '
<span class="nav-section-label">Menu</span>
<a href="/dashboard/admin" class="nav-item"><span class="nav-icon">🏠</span> Dashboard</a>
<a href="/dashboard/admin/users" class="nav-item"><span class="nav-icon">👥</span> Manajemen User</a>
<a href="/dashboard/admin/mitra" class="nav-item"><span class="nav-icon">🏢</span> Tempat Mitra</a>
<a href="/dashboard/admin/pengajuan" class="nav-item"><span class="nav-icon">📋</span> Semua Pengajuan</a>
<a href="/dashboard/admin/laporan" class="nav-item active"><span class="nav-icon">📊</span> Laporan</a>
<span class="nav-section-label">Akun</span>
<a href="/user/profile" class="nav-item"><span class="nav-icon">👤</span> Profil Saya</a>
' : '
<span class="nav-section-label">Menu</span>
<a href="/dashboard/dekan" class="nav-item"><span class="nav-icon">🏠</span> Dashboard</a>
<a href="/dashboard/dekan/inbox" class="nav-item"><span class="nav-icon">📥</span> Inbox</a>
<a href="/dashboard/dekan/selesai" class="nav-item"><span class="nav-icon">✅</span> Selesai</a>
<a href="/dashboard/dekan/laporan" class="nav-item active"><span class="nav-icon">📊</span> Laporan</a>
<span class="nav-section-label">Akun</span>
<a href="/user/profile" class="nav-item"><span class="nav-icon">👤</span> Profil Saya</a>
';
$notifUrl = '/notifikasi';
$total    = array_sum($statStatus);

$statusColors = [
    'MENUNGGU_TU'      => '#fbbf24',
    'MENUNGGU_KAPRODI' => '#6366f1',
    'MENUNGGU_DEKAN'   => '#60a5fa',
    'DISETUJUI'        => '#a3e635',
    'SELESAI'          => '#34d399',
    'DITOLAK'          => '#f87171',
    'DRAFT'            => '#94a3b8',
];

ob_start();
?>

<!-- Stat cards -->
<div class="stats-grid" style="margin-bottom:1.5rem">
    <div class="stat-card" style="--stat-color:#6366f1">
        <div class="stat-icon">📊</div>
        <div class="stat-value"><?= $total ?></div>
        <div class="stat-label">Total Pengajuan</div>
    </div>
    <div class="stat-card" style="--stat-color:#34d399">
        <div class="stat-icon">✅</div>
        <div class="stat-value"><?= ($statStatus['SELESAI'] ?? 0) ?></div>
        <div class="stat-label">Selesai</div>
    </div>
    <div class="stat-card" style="--stat-color:#fbbf24">
        <div class="stat-icon">⏳</div>
        <div class="stat-value"><?= ($statStatus['MENUNGGU_TU']??0)+($statStatus['MENUNGGU_KAPRODI']??0)+($statStatus['MENUNGGU_DEKAN']??0) ?></div>
        <div class="stat-label">Sedang Diproses</div>
    </div>
    <div class="stat-card" style="--stat-color:#f87171">
        <div class="stat-icon">❌</div>
        <div class="stat-value"><?= ($statStatus['DITOLAK'] ?? 0) ?></div>
        <div class="stat-label">Ditolak</div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
    <!-- Per Status -->
    <div class="card">
        <div class="card-header"><div class="card-title">📊 Distribusi Status</div></div>
        <?php if ($total === 0): ?>
            <div class="empty-state" style="padding:1.5rem"><div class="empty-icon">📭</div><div class="empty-title">Belum ada data</div></div>
        <?php else: ?>
            <div style="display:flex;flex-direction:column;gap:.75rem">
                <?php foreach ($statStatus as $status => $count): ?>
                <?php $pct = round($count / $total * 100); $clr = $statusColors[$status] ?? '#6366f1'; ?>
                <div>
                    <div style="display:flex;justify-content:space-between;align-items:center;font-size:.82rem;margin-bottom:.3rem">
                        <div style="display:flex;align-items:center;gap:.5rem">
                            <div style="width:9px;height:9px;border-radius:50%;background:<?= $clr ?>"></div>
                            <span><?= str_replace('_', ' ', $status) ?></span>
                        </div>
                        <span style="color:var(--muted)"><?= $count ?> (<?= $pct ?>%)</span>
                    </div>
                    <div style="height:5px;background:var(--border);border-radius:3px;overflow:hidden">
                        <div style="height:100%;width:<?= $pct ?>%;background:<?= $clr ?>;border-radius:3px;transition:width .6s ease"></div>
                    </div>
                </div>
                <?php endforeach ?>
            </div>
        <?php endif ?>
    </div>

    <!-- Per Jenis -->
    <div class="card">
        <div class="card-header"><div class="card-title">📋 Per Jenis Surat</div></div>
        <?php if (empty($statJenis)): ?>
            <div class="empty-state" style="padding:1.5rem"><div class="empty-icon">📭</div><div class="empty-title">Belum ada data</div></div>
        <?php else: ?>
            <div style="display:flex;flex-direction:column;gap:.75rem">
                <?php foreach ($statJenis as $j): ?>
                <?php $pct = $total > 0 ? round($j['total'] / $total * 100) : 0; ?>
                <div>
                    <div style="display:flex;justify-content:space-between;font-size:.82rem;margin-bottom:.3rem">
                        <span style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:60%"><?= esc(str_replace('_', ' ', $j['jenis_surat'])) ?></span>
                        <span style="color:var(--muted);flex-shrink:0"><?= $j['total'] ?> (<?= $pct ?>%)</span>
                    </div>
                    <div style="height:5px;background:var(--border);border-radius:3px;overflow:hidden">
                        <div style="height:100%;width:<?= $pct ?>%;background:var(--accent);border-radius:3px;transition:width .6s ease"></div>
                    </div>
                </div>
                <?php endforeach ?>
            </div>
        <?php endif ?>
    </div>
</div>

<!-- Tabel lengkap per status -->
<div class="card" style="margin-top:1rem">
    <div class="card-header"><div class="card-title">📈 Ringkasan Lengkap</div></div>
    <div class="table-wrapper">
        <table>
            <thead><tr><th>Status</th><th>Jumlah</th><th>Persentase</th><th>Proporsi</th></tr></thead>
            <tbody>
                <?php foreach ($statStatus as $status => $count): ?>
                <?php $pct = $total > 0 ? round($count / $total * 100) : 0; $clr = $statusColors[$status] ?? '#6366f1'; ?>
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:.5rem">
                            <div style="width:8px;height:8px;border-radius:50%;background:<?= $clr ?>"></div>
                            <?= str_replace('_', ' ', $status) ?>
                        </div>
                    </td>
                    <td style="font-weight:600"><?= $count ?></td>
                    <td style="color:var(--muted)"><?= $pct ?>%</td>
                    <td style="min-width:120px">
                        <div style="height:4px;background:var(--border);border-radius:2px;overflow:hidden">
                            <div style="height:100%;width:<?= $pct ?>%;background:<?= $clr ?>;border-radius:2px"></div>
                        </div>
                    </td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>

<?php
$pageContent = ob_get_clean();
require APPPATH . 'Views/layouts/main.php';
