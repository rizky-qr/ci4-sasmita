<?php
// v_notifikasi.php — Halaman notifikasi mahasiswa
$sidebarNav = '
<span class="nav-section-label">Menu</span>
<a href="/dashboard/mahasiswa" class="nav-item"><span class="nav-icon">🏠</span> Dashboard</a>
<span class="nav-section-label">Riwayat</span>
<a href="/dashboard/mahasiswa/riwayat" class="nav-item"><span class="nav-icon">📂</span> Riwayat Pengajuan</a>
<a href="/dashboard/mahasiswa/notifikasi" class="nav-item active"><span class="nav-icon">🔔</span> Notifikasi</a>
<span class="nav-section-label">Akun</span>
<a href="/user/profile" class="nav-item"><span class="nav-icon">👤</span> Profil Saya</a>
';
$notifUrl = '/dashboard/mahasiswa/notifikasi';
ob_start();
?>

<div class="card">
    <div class="card-header">
        <div>
            <div class="card-title">🔔 Notifikasi</div>
            <div class="card-sub"><?= count($notifikasi) ?> notifikasi</div>
        </div>
    </div>

    <?php if (empty($notifikasi)): ?>
        <div class="empty-state">
            <div class="empty-icon">🔕</div>
            <div class="empty-title">Tidak ada notifikasi</div>
            <div class="empty-sub">Kamu akan menerima notifikasi ketika status pengajuanmu berubah.</div>
        </div>
    <?php else: ?>
        <div style="display:flex;flex-direction:column;gap:.5rem">
            <?php foreach ($notifikasi as $n): ?>
            <a href="<?= $n['pengajuan_id'] ? '/dashboard/mahasiswa/riwayat/' . $n['pengajuan_id'] : '#' ?>"
               style="text-decoration:none;display:block">
                <div style="
                    padding:.85rem 1rem;
                    border-radius:8px;
                    border:1px solid var(--border);
                    background:var(--surface2);
                    display:flex;gap:.85rem;align-items:flex-start;
                    transition:border-color .15s;
                " onmouseover="this.style.borderColor='var(--accent)'" onmouseout="this.style.borderColor='var(--border)'">
                    <div style="font-size:1.25rem;flex-shrink:0;margin-top:.1rem">🔔</div>
                    <div style="flex:1">
                        <div style="font-size:.85rem;font-weight:600;color:var(--text)"><?= esc($n['judul']) ?></div>
                        <div style="font-size:.79rem;color:var(--muted);margin-top:.2rem"><?= esc($n['pesan']) ?></div>
                        <div style="font-size:.72rem;color:var(--muted);margin-top:.35rem">
                            <?= date('d M Y, H:i', strtotime($n['created_at'])) ?>
                        </div>
                    </div>
                </div>
            </a>
            <?php endforeach ?>
        </div>
    <?php endif ?>
</div>

<?php
$pageContent = ob_get_clean();
require APPPATH . 'Views/layouts/main.php';
