<?php
$sidebarNav = '
<span class="nav-section-label">Menu</span>
<a href="/dashboard/kaprodi" class="nav-item"><span class="nav-icon">🏠</span> Dashboard</a>
<a href="/dashboard/kaprodi/inbox" class="nav-item"><span class="nav-icon">📥</span> Inbox</a>
<a href="/dashboard/kaprodi/statistik" class="nav-item active"><span class="nav-icon">📊</span> Statistik</a>
<span class="nav-section-label">Akun</span>
<a href="/user/profile" class="nav-item"><span class="nav-icon">👤</span> Profil Saya</a>
';
$notifUrl = '/notifikasi';
ob_start();
$total = array_sum($statStatus);
$statusColors = ['MENUNGGU_TU'=>'#fbbf24','MENUNGGU_KAPRODI'=>'#6366f1','MENUNGGU_DEKAN'=>'#60a5fa','DISETUJUI'=>'#a3e635','SELESAI'=>'#34d399','DITOLAK'=>'#f87171','DRAFT'=>'#94a3b8'];
?>
<div class="stats-grid">
    <?php foreach ($statStatus as $status => $count): ?>
    <div class="stat-card" style="--stat-color:<?= $statusColors[$status] ?? '#6366f1' ?>">
        <div class="stat-value"><?= $count ?></div>
        <div class="stat-label"><?= str_replace('_',' ',$status) ?></div>
    </div>
    <?php endforeach ?>
</div>

<div class="card" style="margin-top:1rem">
    <div class="card-header"><div class="card-title">📊 Pengajuan per Jenis Surat</div></div>
    <div style="display:flex;flex-direction:column;gap:.6rem">
        <?php foreach ($statJenis as $row): ?>
        <?php $pct = $total > 0 ? round($row['total'] / $total * 100) : 0; ?>
        <div>
            <div style="display:flex;justify-content:space-between;font-size:.82rem;margin-bottom:.25rem">
                <span><?= esc(str_replace('_',' ',$row['jenis_surat'])) ?></span>
                <span style="color:var(--muted)"><?= $row['total'] ?> (<?= $pct ?>%)</span>
            </div>
            <div style="height:6px;background:var(--border);border-radius:3px;overflow:hidden">
                <div style="height:100%;width:<?= $pct ?>%;background:var(--accent);border-radius:3px;transition:width .5s"></div>
            </div>
        </div>
        <?php endforeach ?>
    </div>
</div>
<?php $pageContent = ob_get_clean(); require APPPATH . 'Views/layouts/main.php';
