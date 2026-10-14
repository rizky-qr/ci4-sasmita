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

<!-- Visual Step Progress Bar -->
<?= renderStepper($p['status']) ?>

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

                <?php if (!empty($p['file_lampiran'])): ?>
                <div style="grid-column:1/-1">
                    <div style="color:var(--muted);font-size:.74rem;font-weight:600;margin-bottom:.2rem">Lampiran Pendukung</div>
                    <div style="display:flex;align-items:center;justify-content:space-between;gap:.75rem;background:#f8fafc;padding:.75rem 1rem;border-radius:8px;border:1px solid var(--border);">
                        <div style="display:flex;align-items:center;gap:.6rem;min-width:0;">
                            <span style="font-size:1.3rem;">📎</span>
                            <div style="min-width:0;">
                                <div style="font-size:.84rem;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:320px;">
                                    <?= esc($p['file_lampiran']) ?>
                                </div>
                                <div style="font-size:.72rem;color:var(--muted)">Lampiran berkas pendukung</div>
                            </div>
                        </div>
                        <a href="/uploads/lampiran/<?= esc($p['file_lampiran']) ?>" target="_blank" class="btn btn-outline btn-sm">
                            👁️ Buka Berkas
                        </a>
                    </div>
                </div>
                <?php endif ?>

                <?php if ($p['catatan_terakhir'] && $p['status'] !== 'BUTUH_REVISI'): ?>
                <div style="grid-column:1/-1">
                    <div style="color:var(--muted);font-size:.74rem;font-weight:600;margin-bottom:.2rem">Catatan Terakhir Approver</div>
                    <div style="background:#fef2f2;padding:.75rem;border-radius:8px;border:1px solid #fecaca;color:var(--danger)">
                        <?= nl2br(esc($p['catatan_terakhir'])) ?>
                    </div>
                </div>
                <?php endif ?>
            </div>

            <!-- Tombol Aksi Dokumen Selesai & 1-Click Copy Verifikasi -->
            <?php if ($p['status'] === 'SELESAI'): ?>
            <div style="margin-top:1.5rem;padding-top:1.25rem;border-top:1px solid var(--border);display:flex;gap:.75rem;flex-wrap:wrap;">
                <a href="/surat/cetak/<?= esc($p['verify_token'] ?: $p['id']) ?>" class="btn btn-primary" target="_blank">
                    📄 Cetak / Simpan Surat Resmi (PDF)
                </a>
                <a href="/verify/<?= esc($p['verify_token'] ?: $p['id']) ?>" class="btn btn-outline" target="_blank">
                    🔍 Cek Verifikasi QR
                </a>
            </div>

            <!-- 1-Click Copy Verifikasi Link Box -->
            <div class="verif-copy-box" style="margin-top:1.25rem; background:#f0fdf4; border:1px solid #bbf7d0; border-radius:10px; padding:1rem; display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap;">
                <div style="display:flex; align-items:center; gap:.75rem; min-width:0; flex:1;">
                    <div style="font-size:1.5rem; flex-shrink:0;">🔗</div>
                    <div style="min-width:0; flex:1;">
                        <div style="font-size:.74rem; font-weight:700; color:#15803d; text-transform:uppercase; letter-spacing:0.5px;">Link Verifikasi Resmi</div>
                        <div style="font-size:.82rem; color:#166534; font-family:monospace; word-break:break-all;" id="verif-url-text"><?= esc(site_url('verify/' . ($p['verify_token'] ?: $p['id']))) ?></div>
                    </div>
                </div>
                <button type="button" class="btn btn-primary btn-sm" onclick="copyVerifLink()" id="btn-copy-verif" style="display:inline-flex; align-items:center; gap:.4rem; font-weight:600; white-space:nowrap;">
                    <span>📋</span> <span id="copy-text">Salin Link</span>
                </button>
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
$extraScript = '
<script>
function copyVerifLink() {
    const text = document.getElementById("verif-url-text").innerText.trim();
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(text).then(function() {
            triggerCopySuccess();
        }).catch(function() {
            fallbackCopy(text);
        });
    } else {
        fallbackCopy(text);
    }
}
function triggerCopySuccess() {
    const btn = document.getElementById("btn-copy-verif");
    const span = document.getElementById("copy-text");
    span.innerText = "Tersalin! ✓";
    btn.style.backgroundColor = "#15803d";
    setTimeout(function() {
        span.innerText = "Salin Link";
        btn.style.backgroundColor = "";
    }, 2500);
}
function fallbackCopy(text) {
    prompt("Salin URL Verifikasi Dokumen:", text);
}
</script>';
require APPPATH . 'Views/layouts/main.php';
