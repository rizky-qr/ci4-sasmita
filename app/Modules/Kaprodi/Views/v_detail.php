<?php
// v_detail.php — Detail pengajuan + form approve / revisi / tolak (Kaprodi)
$basePath   = $basePath   ?? '/dashboard/kaprodi';
$approveUrl = $approveUrl ?? $basePath . '/approve/' . ($pengajuan['id'] ?? '');
$tolakUrl   = $tolakUrl   ?? $basePath . '/tolak/'   . ($pengajuan['id'] ?? '');
$revisiUrl  = $revisiUrl  ?? $basePath . '/revisi/'  . ($pengajuan['id'] ?? '');
$canAct     = $canAct     ?? ($pengajuan['status'] === 'MENUNGGU_KAPRODI');
$approveLabel = $approveLabel ?? 'Setujui & Teruskan ke Dekan';

$notifUrl = '/notifikasi';
$p = $pengajuan;

require_once APPPATH . 'Views/partials/status_helpers.php';

ob_start();
?>

<!-- Breadcrumb -->
<div style="display:flex;align-items:center;gap:.5rem;font-size:.82rem;color:var(--muted);margin-bottom:1.25rem">
    <a href="<?= $basePath ?>" style="color:var(--muted);text-decoration:none">Dashboard</a>
    <span>›</span>
    <a href="<?= $basePath ?>/inbox" style="color:var(--muted);text-decoration:none">Inbox</a>
    <span>›</span>
    <span style="color:var(--text);font-weight:600">Detail Pengajuan</span>
</div>

<div style="display:grid;grid-template-columns:1fr 340px;gap:1.25rem;align-items:start">

    <!-- Kiri: Info pengajuan -->
    <div style="display:flex;flex-direction:column;gap:1.25rem">

        <div class="card">
            <div class="card-header">
                <div>
                    <div class="card-title"><?= esc(str_replace('_', ' ', $p['jenis_surat'])) ?></div>
                    <div class="card-sub"><?= esc($p['mahasiswa_nama']) ?> · <?= esc($p['mahasiswa_nim']) ?></div>
                </div>
                <?= statusBadge($p['status']) ?>
            </div>

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
                    <div style="background:#f8fafc;padding:.85rem;border-radius:8px;border:1px solid var(--border);line-height:1.6;color:var(--text);">
                        <?= nl2br(esc($p['keperluan'])) ?>
                    </div>
                </div>
                <?php if (!empty($p['nomor_surat'])): ?>
                <div style="grid-column:1/-1">
                    <div style="color:var(--muted);font-size:.74rem;font-weight:600;margin-bottom:.2rem">Nomor Surat Resmi</div>
                    <div style="font-weight:700; color:var(--primary); font-size:.9rem;"><?= esc($p['nomor_surat']) ?></div>
                </div>
                <?php endif ?>
            </div>

            <?php if ($p['status'] === 'SELESAI'): ?>
            <div style="margin-top:1.25rem;padding-top:1rem;border-top:1px solid var(--border);display:flex;gap:.75rem;">
                <a href="/surat/cetak/<?= esc($p['verify_token'] ?: $p['id']) ?>" class="btn btn-primary" target="_blank">
                    📄 Cetak / Lihat Surat
                </a>
                <a href="/verify/<?= esc($p['verify_token'] ?: $p['id']) ?>" class="btn btn-outline" target="_blank">
                    🔍 Verifikasi QR
                </a>
            </div>
            <?php endif; ?>
        </div>

        <?php if ($canAct): ?>
        <!-- Form Aksi Approve / Revisi / Tolak -->
        <div class="card">
            <div class="card-title" style="margin-bottom:1rem">⚖️ Ambil Keputusan Persetujuan</div>

            <!-- Approve -->
            <form action="<?= $approveUrl ?>" method="post" style="margin-bottom:1rem">
                <?= csrf_field() ?>
                <div class="form-group" style="margin-bottom:.75rem">
                    <label for="catatan-approve">Catatan Persetujuan (opsional)</label>
                    <textarea name="catatan" id="catatan-approve" rows="2" placeholder="Catatan untuk Dekan..."></textarea>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center;">
                    ✅ <?= $approveLabel ?>
                </button>
            </form>

            <div style="border-top:1px solid var(--border);padding-top:1rem;display:grid;grid-template-columns:1fr 1fr;gap:.75rem;">
                <!-- Modal Revisi -->
                <button type="button" class="btn btn-warning" style="width:100%; justify-content:center;" onclick="document.getElementById('modal-revisi').style.display='flex'">
                    ⚠️ Minta Revisi
                </button>
                <!-- Modal Tolak -->
                <button type="button" class="btn btn-danger" style="width:100%; justify-content:center;" onclick="document.getElementById('modal-tolak').style.display='flex'">
                    ✕ Tolak Pengajuan
                </button>
            </div>
        </div>
        <?php endif ?>
    </div>

    <!-- Kanan: Timeline Approval -->
    <div class="card">
        <div class="card-title" style="margin-bottom:1rem">📋 Riwayat Approval</div>
        <div class="timeline">
            <div class="tl-item">
                <div class="tl-line" style="position:relative">
                    <div class="tl-dot ok">✓</div>
                </div>
                <div class="tl-content">
                    <div class="tl-title"><?= esc($p['mahasiswa_nama']) ?></div>
                    <div class="tl-meta">MAHASISWA · <?= date('d M Y, H:i', strtotime($p['created_at'])) ?></div>
                    <div class="tl-note">Mengajukan <?= esc(str_replace('_',' ',$p['jenis_surat'])) ?></div>
                </div>
            </div>
            <?php foreach ($p['logs'] as $log): ?>
            <div class="tl-item">
                <div class="tl-line" style="position:relative">
                    <div class="tl-dot <?= dotClass($log['status']) ?>">
                        <?= in_array($log['status'], ['DISETUJUI', 'SELESAI']) ? '✓' : ($log['status'] === 'DITOLAK' ? '✕' : ($log['status'] === 'BUTUH_REVISI' ? '⚠️' : '…')) ?>
                    </div>
                </div>
                <div class="tl-content">
                    <div class="tl-title"><?= esc($log['approved_by_nama'] ?? 'Sistem') ?></div>
                    <div class="tl-meta"><?= esc(str_replace('_', ' ', $log['role'])) ?> · <?= date('d M Y, H:i', strtotime($log['created_at'])) ?></div>
                    <?php if ($log['catatan']): ?><div class="tl-note"><?= nl2br(esc($log['catatan'])) ?></div><?php endif ?>
                </div>
            </div>
            <?php endforeach ?>
        </div>
    </div>
</div>

<!-- Modal Revisi -->
<div id="modal-revisi" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.65);z-index:200;align-items:center;justify-content:center">
    <div style="background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);padding:1.75rem;max-width:440px;width:90%">
        <h3 style="font-size:1rem;margin-bottom:.4rem;color:#b45309">⚠️ Minta Perbaikan / Revisi</h3>
        <p style="font-size:.82rem;color:var(--muted);margin-bottom:1rem">Tuliskan instruksi perbaikan untuk mahasiswa. Status akan beralih ke Butuh Revisi.</p>
        <form action="<?= $revisiUrl ?>" method="post">
            <?= csrf_field() ?>
            <div class="form-group" style="margin-bottom:1rem">
                <label for="catatan-revisi">Catatan Revisi <span style="color:var(--danger)">*</span></label>
                <textarea name="catatan" id="catatan-revisi" rows="4" placeholder="Jelaskan bagian yang harus diperbaiki mahasiswa..." required></textarea>
            </div>
            <div style="display:flex;gap:.75rem;justify-content:flex-end">
                <button type="button" class="btn btn-outline" onclick="document.getElementById('modal-revisi').style.display='none'">Batal</button>
                <button type="submit" class="btn btn-warning" style="font-weight:700">Kirim Permintaan Revisi</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Tolak -->
<div id="modal-tolak" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.65);z-index:200;align-items:center;justify-content:center">
    <div style="background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);padding:1.75rem;max-width:440px;width:90%">
        <h3 style="font-size:1rem;margin-bottom:.4rem">✕ Tolak Pengajuan</h3>
        <p style="font-size:.82rem;color:var(--muted);margin-bottom:1rem">Wajib isi alasan penolakan. Mahasiswa akan menerima notifikasi.</p>
        <form action="<?= $tolakUrl ?>" method="post">
            <?= csrf_field() ?>
            <div class="form-group" style="margin-bottom:1rem">
                <label for="alasan-tolak">Alasan Penolakan <span style="color:var(--danger)">*</span></label>
                <textarea name="catatan" id="alasan-tolak" rows="4" placeholder="Jelaskan alasan penolakan..." required></textarea>
            </div>
            <div style="display:flex;gap:.75rem;justify-content:flex-end">
                <button type="button" class="btn btn-outline" onclick="document.getElementById('modal-tolak').style.display='none'">Batal</button>
                <button type="submit" class="btn btn-danger">Tolak Pengajuan</button>
            </div>
        </form>
    </div>
</div>

<?php
$pageContent = ob_get_clean();
require APPPATH . 'Views/layouts/main.php';
