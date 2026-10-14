<?php
// v_riwayat.php — Daftar semua pengajuan mahasiswa
$notifUrl = '/dashboard/mahasiswa/notifikasi';

require_once APPPATH . 'Views/partials/status_helpers.php';

ob_start();
?>

<div class="card">
    <div class="card-header" style="flex-wrap:wrap;gap:1rem;">
        <div>
            <div class="card-title">Riwayat Pengajuan Surat</div>
            <div class="card-sub">Semua pengajuan surat kamu (<?= count($pengajuan) ?> total)</div>
        </div>
        <div style="display:flex;gap:.5rem;align-items:center;">
            <a href="/dashboard/mahasiswa/pengajuan/aktif" class="btn btn-primary btn-sm">+ Ajukan Surat Baru</a>
        </div>
    </div>

    <!-- Live Search Input -->
    <?php if (!empty($pengajuan)): ?>
    <div style="margin-bottom:1rem;position:relative;">
        <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--muted);font-size:.9rem;">🔍</span>
        <input type="text" id="search-riwayat" onkeyup="filterRiwayat(this)" placeholder="Cari berdasarkan jenis surat, keperluan, atau status..." style="width:100%;padding:.6rem .8rem .6rem 2.2rem;border:1px solid var(--border);border-radius:8px;font-size:.84rem;background:var(--surface);outline:none;">
    </div>
    <?php endif ?>

    <?php if (empty($pengajuan)): ?>
        <div class="empty-state">
            <div class="empty-icon">📭</div>
            <div class="empty-title">Belum ada pengajuan</div>
            <div class="empty-sub">Kamu belum pernah mengajukan surat. Silakan pilih jenis surat untuk mengajukan.</div>
        </div>
    <?php else: ?>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th style="width:40px">#</th>
                        <th>Jenis Surat</th>
                        <th>Keperluan</th>
                        <th>Tanggal Ajuan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="riwayat-tbody">
                    <?php foreach ($pengajuan as $i => $p): ?>
                    <tr>
                        <td style="color:var(--muted)"><?= $i + 1 ?></td>
                        <td style="font-weight:600;color:var(--text-dark)"><?= esc($jenisLabel[$p['jenis_surat']] ?? str_replace('_', ' ', $p['jenis_surat'])) ?></td>
                        <td style="color:var(--muted);max-width:220px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                            <?= esc(substr($p['keperluan'] ?? '-', 0, 60)) ?>
                        </td>
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

<?php
$pageContent = ob_get_clean();
$extraScript = '
<script>
function filterRiwayat(input) {
    var query = input.value.toLowerCase().trim();
    var rows = document.querySelectorAll("#riwayat-tbody tr");
    rows.forEach(function(row) {
        var text = row.innerText.toLowerCase();
        row.style.display = text.indexOf(query) > -1 ? "" : "none";
    });
}
</script>';
require APPPATH . 'Views/layouts/main.php';
