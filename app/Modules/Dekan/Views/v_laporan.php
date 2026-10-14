<?php
$notifUrl = '/notifikasi';
ob_start();
$total = array_sum($statStatus);
$statusColors = [
    'MENUNGGU_TU'      => '#3b82f6',
    'MENUNGGU_KAPRODI' => '#eab308',
    'MENUNGGU_DEKAN'   => '#a855f7',
    'DISETUJUI'        => '#22c55e',
    'SELESAI'          => '#10b981',
    'DITOLAK'          => '#ef4444',
    'BUTUH_REVISI'     => '#f97316',
    'DRAFT'            => '#94a3b8'
];
?>

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.25rem;flex-wrap:wrap;gap:.75rem;">
    <div>
        <h2 style="font-size:1.15rem;font-weight:800;color:var(--text-dark);margin:0;">Laporan & Statistik Dekanat</h2>
        <p style="font-size:.8rem;color:var(--muted);margin-top:2px;">Rekapitulasi berkas administrasi surat mahasiswa FKIP UIKA</p>
    </div>
    <a href="/dashboard/dekan/export" class="btn btn-primary" style="display:inline-flex;align-items:center;gap:.5rem;font-weight:700;">
        <span>📥</span> Unduh Rekap Laporan (CSV / Excel)
    </a>
</div>

<div class="stats-grid">
    <?php foreach ($statStatus as $status => $count): ?>
    <div class="stat-card" style="--stat-color:<?= $statusColors[$status] ?? '#6366f1' ?>">
        <div class="stat-value"><?= $count ?></div>
        <div class="stat-label"><?= esc(str_replace('_',' ',$status)) ?></div>
    </div>
    <?php endforeach ?>
</div>

<div class="card" style="margin-top:1.25rem">
    <div class="card-header">
        <div class="card-title">📊 Pengajuan per Jenis Surat</div>
    </div>
    <div style="display:flex;flex-direction:column;gap:.75rem">
        <?php foreach ($statJenis as $row): ?>
        <?php $pct = $total > 0 ? round($row['total'] / $total * 100) : 0; ?>
        <div>
            <div style="display:flex;justify-content:space-between;font-size:.82rem;margin-bottom:.3rem">
                <span style="font-weight:600;"><?= esc(str_replace('_',' ',$row['jenis_surat'])) ?></span>
                <span style="color:var(--muted);font-weight:600;"><?= $row['total'] ?> berkas (<?= $pct ?>%)</span>
            </div>
            <div style="height:8px;background:var(--border);border-radius:4px;overflow:hidden">
                <div style="height:100%;width:<?= $pct ?>%;background:var(--primary);border-radius:4px;transition:width .5s"></div>
            </div>
        </div>
        <?php endforeach ?>
    </div>
</div>

<?php
$pageContent = ob_get_clean();
require APPPATH . 'Views/layouts/main.php';
