<?php
// Admin sidebar helper
$_adminSidebar = function($active = '') {
    return '
<span class="nav-section-label">Menu</span>
<a href="/dashboard/admin" class="nav-item ' . ($active==='home'?'active':'') . '"><span class="nav-icon">🏠</span> Dashboard</a>
<span class="nav-section-label">Kelola Data</span>
<a href="/dashboard/admin/users"  class="nav-item ' . ($active==='users'?'active':'') . '"><span class="nav-icon">👥</span> Manajemen User</a>
<a href="/dashboard/admin/mitra"  class="nav-item ' . ($active==='mitra'?'active':'') . '"><span class="nav-icon">🏢</span> Tempat Mitra</a>
<span class="nav-section-label">Laporan</span>
<a href="/dashboard/admin/pengajuan" class="nav-item ' . ($active==='pengajuan'?'active':'') . '"><span class="nav-icon">📋</span> Semua Pengajuan</a>
<a href="/dashboard/admin/laporan"   class="nav-item ' . ($active==='laporan'?'active':'') . '"><span class="nav-icon">📊</span> Laporan</a>
<span class="nav-section-label">Akun</span>
<a href="/user/profile" class="nav-item"><span class="nav-icon">👤</span> Profil Saya</a>
';
};
$sidebarNav = $_adminSidebar('home');
$notifUrl   = '/notifikasi';
ob_start();
$statusColors = ['MENUNGGU_TU'=>'#fbbf24','MENUNGGU_KAPRODI'=>'#6366f1','MENUNGGU_DEKAN'=>'#60a5fa','DISETUJUI'=>'#a3e635','SELESAI'=>'#34d399','DITOLAK'=>'#f87171','DRAFT'=>'#94a3b8'];
?>
<div class="stats-grid">
    <div class="stat-card" style="--stat-color:#6366f1">
        <div class="stat-icon">👥</div>
        <div class="stat-value"><?= $totalUsers ?? 0 ?></div>
        <div class="stat-label">Total User Terdaftar</div>
    </div>
    <div class="stat-card" style="--stat-color:#fbbf24">
        <div class="stat-icon">⏳</div>
        <div class="stat-value"><?= ($stats['MENUNGGU_TU']??0)+($stats['MENUNGGU_KAPRODI']??0)+($stats['MENUNGGU_DEKAN']??0) ?></div>
        <div class="stat-label">Sedang Diproses</div>
    </div>
    <div class="stat-card" style="--stat-color:#34d399">
        <div class="stat-icon">✅</div>
        <div class="stat-value"><?= ($stats['SELESAI']??0) ?></div>
        <div class="stat-label">Surat Selesai</div>
    </div>
    <div class="stat-card" style="--stat-color:#f87171">
        <div class="stat-icon">❌</div>
        <div class="stat-value"><?= ($stats['DITOLAK']??0) ?></div>
        <div class="stat-label">Ditolak</div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
    <div class="card">
        <div class="card-header"><div class="card-title">Status Pengajuan</div></div>
        <div style="display:flex;flex-direction:column;gap:.5rem">
        <?php foreach ($stats as $status => $count): ?>
        <?php $clr = $statusColors[$status] ?? '#6366f1'; ?>
        <div style="display:flex;align-items:center;gap:.75rem;font-size:.84rem">
            <div style="width:10px;height:10px;border-radius:50%;background:<?= $clr ?>;flex-shrink:0"></div>
            <span style="flex:1;color:var(--muted)"><?= str_replace('_',' ',$status) ?></span>
            <span style="font-weight:600"><?= $count ?></span>
        </div>
        <?php endforeach ?>
        </div>
    </div>
    <div class="card">
        <div class="card-header"><div class="card-title">Akses Cepat</div></div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:.6rem">
            <a href="/dashboard/admin/users/tambah" class="btn btn-primary" style="justify-content:center">+ Tambah User</a>
            <a href="/dashboard/admin/mitra" class="btn btn-outline" style="justify-content:center">🏢 Kelola Mitra</a>
            <a href="/dashboard/admin/pengajuan" class="btn btn-outline" style="justify-content:center">📋 Semua Pengajuan</a>
            <a href="/dashboard/admin/laporan" class="btn btn-outline" style="justify-content:center">📊 Laporan</a>
        </div>
    </div>
</div>
<?php $pageContent = ob_get_clean(); require APPPATH . 'Views/layouts/main.php';
