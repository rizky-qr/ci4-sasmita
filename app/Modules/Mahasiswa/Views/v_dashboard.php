<?php
$notifUrl = '/dashboard/mahasiswa/notifikasi';

require_once APPPATH . 'Views/partials/status_helpers.php';

$jenisLabel = $jenisLabel ?? [
    'KETERANGAN_AKTIF'      => 'Surat Keterangan Aktif',
    'TIDAK_ADA_PERKULIAHAN' => 'Surat Tidak Ada Perkuliahan',
    'IZIN_PENELITIAN'       => 'Surat Izin Penelitian',
    'PERMOHONAN_PLP_MAGANG' => 'Surat Permohonan PLP/Magang',
    'PERMOHONAN_OBSERVASI'  => 'Surat Permohonan Observasi',
    'REKOMENDASI'           => 'Surat Rekomendasi',
    'KETERANGAN_LULUS'      => 'Surat Keterangan Lulus',
];

// Cari pengajuan aktif berjalan untuk Stepper Dashboard
$activePengajuan = null;
foreach ($terbaru as $p) {
    if (in_array($p['status'], ['MENUNGGU_TU', 'MENUNGGU_KAPRODI', 'MENUNGGU_DEKAN', 'BUTUH_REVISI', 'DRAFT'])) {
        $activePengajuan = $p;
        break;
    }
}

ob_start();
?>

<!-- Stat Cards -->
<div class="stats-grid">
    <div class="stat-card" style="--stat-color:#1b5e20">
        <div class="stat-icon">📊</div>
        <div class="stat-value"><?= (int) ($stats['total'] ?? 0) ?></div>
        <div class="stat-label">Total Pengajuan</div>
    </div>
    <div class="stat-card" style="--stat-color:#f9a825">
        <div class="stat-icon">⏳</div>
        <div class="stat-value"><?= (int) ($stats['menunggu'] ?? 0) ?></div>
        <div class="stat-label">Sedang Diproses</div>
    </div>
    <div class="stat-card" style="--stat-color:#16a34a">
        <div class="stat-icon">✅</div>
        <div class="stat-value"><?= (int) ($stats['selesai'] ?? 0) ?></div>
        <div class="stat-label">Selesai & Sah</div>
    </div>
    <div class="stat-card" style="--stat-color:#dc2626">
        <div class="stat-icon">❌</div>
        <div class="stat-value"><?= (int) ($stats['ditolak'] ?? 0) ?></div>
        <div class="stat-label">Ditolak</div>
    </div>
</div>

<?php if ($activePengajuan): ?>
<!-- Kartu Pelacak Alur Berjalan (Stepper di Dashboard) -->
<div class="card" style="margin-top:1.25rem;border-left:4px solid var(--primary);">
    <div class="card-header" style="padding-bottom:.5rem;">
        <div>
            <div style="font-size:.74rem;color:var(--primary);font-weight:700;text-transform:uppercase;letter-spacing:.5px;">Alur Pengajuan Terkini</div>
            <div class="card-title" style="font-size:1.05rem;margin-top:2px;">
                <?= esc($jenisLabel[$activePengajuan['jenis_surat']] ?? str_replace('_', ' ', $activePengajuan['jenis_surat'])) ?>
            </div>
            <div class="card-sub">Diajukan pada <?= date('d M Y, H:i', strtotime($activePengajuan['created_at'])) ?></div>
        </div>
        <a href="/dashboard/mahasiswa/riwayat/<?= esc($activePengajuan['id']) ?>" class="btn btn-outline btn-sm">
            Lihat Detail &raquo;
        </a>
    </div>

    <!-- Stepper Komponen -->
    <?= renderStepper($activePengajuan['status']) ?>

    <?php if ($activePengajuan['status'] === 'BUTUH_REVISI'): ?>
    <div style="background:#fff7ed;border:1px solid #fed7aa;border-radius:8px;padding:.75rem 1rem;display:flex;justify-content:space-between;align-items:center;margin-top:.5rem;">
        <div style="font-size:.82rem;color:#c2410c;font-weight:600;">
            ⚠️ Pengajuan membutuhkan perbaikan data dari Anda.
        </div>
        <a href="/dashboard/mahasiswa/riwayat/<?= esc($activePengajuan['id']) ?>" class="btn btn-warning btn-sm" style="font-weight:700;">
            Perbaiki Sekarang
        </a>
    </div>
    <?php endif ?>
</div>
<?php endif ?>

<!-- Pengajuan Terbaru -->
<div class="card" style="margin-top:1.25rem">
    <div class="card-header">
        <div>
            <div class="card-title">Pengajuan Terakhir</div>
            <div class="card-sub">5 permohonan surat terbaru kamu</div>
        </div>
        <a href="/dashboard/mahasiswa/riwayat" class="btn btn-outline btn-sm">Lihat Semua Riwayat</a>
    </div>

    <?php if (empty($terbaru)): ?>
        <div class="empty-state">
            <div class="empty-icon">📭</div>
            <div class="empty-title">Belum ada pengajuan</div>
            <div class="empty-sub">Pilih salah satu layanan surat di bawah untuk memulai pengajuan baru.</div>
        </div>
    <?php else: ?>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Jenis Surat</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($terbaru as $p): ?>
                    <tr>
                        <td style="font-weight:600;color:var(--text-dark)"><?= esc($jenisLabel[$p['jenis_surat']] ?? str_replace('_', ' ', $p['jenis_surat'])) ?></td>
                        <td style="color:var(--muted);white-space:nowrap"><?= date('d M Y, H:i', strtotime($p['created_at'])) ?></td>
                        <td><?= statusBadge($p['status']) ?></td>
                        <td>
                            <div style="display:flex;gap:.4rem;">
                                <a href="/dashboard/mahasiswa/riwayat/<?= esc($p['id']) ?>" class="btn btn-outline btn-sm">Detail</a>
                                <?php if ($p['status'] === 'SELESAI'): ?>
                                    <a href="/surat/cetak/<?= esc($p['verify_token'] ?: $p['id']) ?>" class="btn btn-sm btn-primary" target="_blank">📄 Cetak</a>
                                <?php endif ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    <?php endif ?>
</div>

<!-- Layanan Ajukan Surat Cepat -->
<div class="card" style="margin-top:1.25rem">
    <div class="card-header">
        <div>
            <div class="card-title">⚡ Ajukan Surat Baru</div>
            <div class="card-sub">Pilih jenis persuratan administrasi akademik FKIP UIKA</div>
        </div>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(190px,1fr));gap:.85rem">
        <a href="/dashboard/mahasiswa/pengajuan/aktif" class="btn btn-outline" style="justify-content:flex-start;padding:.85rem 1rem;">
            <span>📋</span> Keterangan Aktif
        </a>
        <a href="/dashboard/mahasiswa/pengajuan/tidak-kuliah" class="btn btn-outline" style="justify-content:flex-start;padding:.85rem 1rem;">
            <span>📋</span> Tidak Ada Kuliah
        </a>
        <a href="/dashboard/mahasiswa/pengajuan/penelitian" class="btn btn-outline" style="justify-content:flex-start;padding:.85rem 1rem;">
            <span>🔬</span> Izin Penelitian
        </a>
        <a href="/dashboard/mahasiswa/pengajuan/plp-magang" class="btn btn-outline" style="justify-content:flex-start;padding:.85rem 1rem;">
            <span>🏢</span> PLP / Magang
        </a>
        <a href="/dashboard/mahasiswa/pengajuan/observasi" class="btn btn-outline" style="justify-content:flex-start;padding:.85rem 1rem;">
            <span>👁️</span> Observasi Matkul
        </a>
        <a href="/dashboard/mahasiswa/pengajuan/rekomendasi" class="btn btn-outline" style="justify-content:flex-start;padding:.85rem 1rem;">
            <span>⭐</span> Rekomendasi
        </a>
        <a href="/dashboard/mahasiswa/pengajuan/lulus" class="btn btn-outline" style="justify-content:flex-start;padding:.85rem 1rem;">
            <span>🎓</span> Keterangan Lulus
        </a>
    </div>
</div>

<?php
$pageContent = ob_get_clean();
require APPPATH . 'Views/layouts/main.php';
