<?php
// v_riwayat.php — Daftar semua pengajuan mahasiswa
$sidebarNav = '
<span class="nav-section-label">Menu</span>
<a href="/dashboard/mahasiswa" class="nav-item"><span class="nav-icon">🏠</span> Dashboard</a>
<span class="nav-section-label">Pengajuan Surat</span>
<a href="/dashboard/mahasiswa/pengajuan/aktif" class="nav-item"><span class="nav-icon">📋</span> Keterangan Aktif</a>
<a href="/dashboard/mahasiswa/pengajuan/tidak-kuliah" class="nav-item"><span class="nav-icon">📋</span> Tidak Ada Kuliah</a>
<a href="/dashboard/mahasiswa/pengajuan/penelitian" class="nav-item"><span class="nav-icon">🔬</span> Izin Penelitian</a>
<a href="/dashboard/mahasiswa/pengajuan/plp-magang" class="nav-item"><span class="nav-icon">🏢</span> PLP / Magang</a>
<a href="/dashboard/mahasiswa/pengajuan/observasi" class="nav-item"><span class="nav-icon">👁️</span> Observasi</a>
<a href="/dashboard/mahasiswa/pengajuan/rekomendasi" class="nav-item"><span class="nav-icon">⭐</span> Rekomendasi</a>
<a href="/dashboard/mahasiswa/pengajuan/lulus" class="nav-item"><span class="nav-icon">🎓</span> Keterangan Lulus</a>
<span class="nav-section-label">Riwayat</span>
<a href="/dashboard/mahasiswa/riwayat" class="nav-item active"><span class="nav-icon">📂</span> Riwayat Pengajuan</a>
<a href="/dashboard/mahasiswa/notifikasi" class="nav-item"><span class="nav-icon">🔔</span> Notifikasi</a>
<span class="nav-section-label">Akun</span>
<a href="/user/profile" class="nav-item"><span class="nav-icon">👤</span> Profil Saya</a>
';

$notifUrl = '/dashboard/mahasiswa/notifikasi';

ob_start();

require APPPATH . 'Views/partials/status_helpers.php';
?>


<div class="card">
    <div class="card-header">
        <div>
            <div class="card-title">Riwayat Pengajuan Surat</div>
            <div class="card-sub">Semua pengajuan surat kamu (<?= count($pengajuan) ?> total)</div>
        </div>
        <a href="/dashboard/mahasiswa/pengajuan/aktif" class="btn btn-primary btn-sm">+ Ajukan Baru</a>
    </div>

    <?php if (empty($pengajuan)): ?>
        <div class="empty-state">
            <div class="empty-icon">📭</div>
            <div class="empty-title">Belum ada pengajuan</div>
            <div class="empty-sub">Kamu belum pernah mengajukan surat. Mulai sekarang!</div>
        </div>
    <?php else: ?>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Jenis Surat</th>
                        <th>Keperluan</th>
                        <th>Tanggal Ajuan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pengajuan as $i => $p): ?>
                    <tr>
                        <td style="color:var(--muted)"><?= $i + 1 ?></td>
                        <td style="font-weight:500"><?= esc($jenisLabel[$p['jenis_surat']] ?? $p['jenis_surat']) ?></td>
                        <td style="color:var(--muted);max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                            <?= esc(substr($p['keperluan'] ?? '-', 0, 60)) ?>
                        </td>
                        <td style="color:var(--muted);white-space:nowrap"><?= date('d M Y', strtotime($p['created_at'])) ?></td>
                        <td><?= statusBadge($p['status']) ?></td>
                        <td>
                            <a href="/dashboard/mahasiswa/riwayat/<?= $p['id'] ?>" class="btn btn-outline btn-sm">Detail</a>
                            <?php if ($p['status'] === 'SELESAI'): ?>
                                <a href="/surat/cetak/<?= esc($p['verify_token'] ?: $p['id']) ?>" class="btn btn-sm btn-primary" target="_blank">📄 Cetak</a>
                            <?php endif ?>
                        </td>
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
