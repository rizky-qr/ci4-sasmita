<?php
// v_detail_pengajuan.php — Detail satu pengajuan + form revisi + timeline approval
$notifUrl = '/dashboard/mahasiswa/notifikasi';
ob_start();
$p = $pengajuan;

require_once APPPATH . 'Views/partials/status_helpers.php';
$bc = badgeClass($p['status']);
?>

<!-- Breadcrumb -->
<div style="display:flex;align-items:center;gap:.5rem;font-size:.82rem;color:var(--muted);margin-bottom:1.25rem">
    <a href="/dashboard/mahasiswa" style="color:var(--muted);text-decoration:none">Dashboard</a>
    <span>›</span>
    <a href="/dashboard/mahasiswa/riwayat" style="color:var(--muted);text-decoration:none">Riwayat</a>
    <span>›</span>
    <span style="color:var(--text);font-weight:600">Detail Pengajuan</span>
</div>

<div style="display:grid;grid-template-columns:1fr 340px;gap:1.25rem;align-items:start">

    <!-- Kiri: Info Pengajuan -->
    <div>
        <div class="card">
            <div class="card-header">
                <div>
                    <div class="card-title"><?= esc($jenisLabel[$p['jenis_surat']] ?? str_replace('_', ' ', $p['jenis_surat'])) ?></div>
                    <?php if (!empty($p['nomor_surat'])): ?>
                        <div class="card-sub" style="font-weight:600; color:var(--primary)">No. <?= esc($p['nomor_surat']) ?></div>
                    <?php endif ?>
                </div>
                <?= statusBadge($p['status']) ?>
            </div>

            <!-- Panel Butuh Revisi -->
            <?php if ($p['status'] === 'BUTUH_REVISI'): ?>
                <div style="background:#fff7ed; border:1px solid #fed7aa; border-radius:10px; padding:1.1rem; margin-bottom:1.25rem;">
                    <div style="display:flex; align-items:center; gap:.5rem; color:#c2410c; font-weight:700; font-size:.9rem; margin-bottom:.4rem;">
                        <span>⚠️</span> Pengajuan Membutuhkan Perbaikan
                    </div>
                    <div style="font-size:.84rem; color:#9a3412; margin-bottom:.85rem;">
                        Approver telah mengembalikan pengajuan ini. Silakan periksa catatan berikut dan kirimkan data perbaikan:
                    </div>
                    <div style="background:#ffffff; border:1px solid #fed7aa; border-radius:8px; padding:.75rem; font-size:.84rem; color:#7c2d12; font-weight:500;">
                        <?= nl2br(esc($p['catatan_terakhir'] ?: 'Silakan perbaiki data keperluan pengajuan surat Anda.')) ?>
                    </div>

                    <!-- Form Kirim Perbaikan -->
                    <form action="/dashboard/mahasiswa/pengajuan/perbaiki/<?= esc($p['id']) ?>" method="post" style="margin-top:1rem;">
                        <?= csrf_field() ?>
                        <label style="font-size:.82rem; font-weight:600; color:#9a3412; display:block; margin-bottom:.35rem;">
                            Data Keperluan yang Diperbaiki:
                        </label>
                        <textarea
                            name="keperluan"
                            rows="4"
                            required
                            style="width:100%; padding:.7rem; border:1px solid #fdba74; border-radius:8px; font-family:inherit; font-size:.86rem; outline:none; background:#ffffff;"
                            placeholder="Tuliskan perbaikan keperluan Anda di sini (min. 10 karakter)..."
                        ><?= esc($p['keperluan']) ?></textarea>

                        <div style="margin-top:.75rem; text-align:right;">
                            <button type="submit" class="btn btn-accent" style="font-weight:700;">
                                🚀 Kirim Perbaikan ke Staf TU
                            </button>
                        </div>
                    </form>
                </div>
            <?php endif; ?>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:.85rem;font-size:.84rem">
                <div>
                    <div style="color:var(--muted);font-size:.74rem;font-weight:600;margin-bottom:.2rem">Nama Pemohon</div>
                    <div style="font-weight:600; color:var(--text-dark)"><?= esc($p['mahasiswa_nama']) ?></div>
                </div>
                <div>
                    <div style="color:var(--muted);font-size:.74rem;font-weight:600;margin-bottom:.2rem">NIM</div>
                    <div style="font-weight:600"><?= esc($p['mahasiswa_nim']) ?></div>
                </div>
                <div>
                    <div style="color:var(--muted);font-size:.74rem;font-weight:600;margin-bottom:.2rem">Program Studi</div>
                    <div><?= esc(str_replace('_', ' ', $p['mahasiswa_program'])) ?></div>
                </div>
                <div>
                    <div style="color:var(--muted);font-size:.74rem;font-weight:600;margin-bottom:.2rem">Tanggal Ajuan</div>
                    <div><?= date('d M Y, H:i', strtotime($p['created_at'])) ?></div>
                </div>
                <div style="grid-column:1/-1">
                    <div style="color:var(--muted);font-size:.74rem;font-weight:600;margin-bottom:.2rem">Keperluan / Keterangan</div>
                    <div style="background:#f8fafc;padding:.85rem;border-radius:8px;border:1px solid var(--border);color:var(--text);line-height:1.5;">
                        <?= nl2br(esc($p['keperluan'])) ?>
                    </div>
                </div>
                <?php if ($p['catatan_terakhir'] && $p['status'] !== 'BUTUH_REVISI'): ?>
                <div style="grid-column:1/-1">
                    <div style="color:var(--muted);font-size:.74rem;font-weight:600;margin-bottom:.2rem">Catatan Terakhir Approver</div>
                    <div style="background:#fef2f2;padding:.75rem;border-radius:8px;border:1px solid #fecaca;color:var(--danger)">
                        <?= nl2br(esc($p['catatan_terakhir'])) ?>
                    </div>
                </div>
                <?php endif ?>
            </div>

            <!-- Tombol Aksi Dokumen Selesai -->
            <?php if ($p['status'] === 'SELESAI'): ?>
            <div style="margin-top:1.5rem;padding-top:1.25rem;border-top:1px solid var(--border);display:flex;gap:.75rem;flex-wrap:wrap;">
                <a href="/surat/cetak/<?= esc($p['verify_token'] ?: $p['id']) ?>" class="btn btn-primary" target="_blank">
                    📄 Cetak / Simpan Surat Resmi (PDF)
                </a>
                <a href="/verify/<?= esc($p['verify_token'] ?: $p['id']) ?>" class="btn btn-outline" target="_blank">
                    🔍 Verifikasi QR Dokumen
                </a>
            </div>
            <?php endif ?>
        </div>
    </div>

    <!-- Kanan: Timeline Approval -->
    <div class="card">
        <div class="card-title" style="margin-bottom:1rem">📋 Riwayat Approval</div>
        <div class="timeline">
            <?php if (empty($p['logs'])): ?>
                <div class="empty-state" style="padding:1.5rem 0">
                    <div class="empty-icon" style="font-size:1.5rem">⏳</div>
                    <div class="empty-title" style="font-size:.84rem">Menunggu proses</div>
                    <div class="empty-sub" style="font-size:.76rem">Belum ada tindakan dari approver.</div>
                </div>
            <?php else: ?>
                <?php foreach ($p['logs'] as $log): ?>
                <div class="tl-item">
                    <div class="tl-line" style="position:relative">
                        <div class="tl-dot <?= dotClass($log['status']) ?>">
                            <?= in_array($log['status'], ['DISETUJUI', 'SELESAI']) ? '✓' : ($log['status'] === 'DITOLAK' ? '✕' : ($log['status'] === 'BUTUH_REVISI' ? '⚠️' : '…')) ?>
                        </div>
                    </div>
                    <div class="tl-content" style="flex:1">
                        <div class="tl-title"><?= esc($log['approved_by_nama'] ?? 'Sistem') ?></div>
                        <div class="tl-meta">
                            <?= esc(str_replace('_', ' ', $log['role'])) ?> · <?= date('d M Y, H:i', strtotime($log['created_at'])) ?>
                        </div>
                        <?php if ($log['catatan']): ?>
                            <div class="tl-note"><?= nl2br(esc($log['catatan'])) ?></div>
                        <?php endif ?>
                    </div>
                </div>
                <?php endforeach ?>
            <?php endif ?>
        </div>
    </div>
</div>

<?php
$pageContent = ob_get_clean();
require APPPATH . 'Views/layouts/main.php';
