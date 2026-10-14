<?php
// v_notifikasi.php — Halaman notifikasi untuk semua role
$role = session('role');
$_navMap = [
    'MAHASISWA' => '
        <span class="nav-section-label">Menu</span>
        <a href="/dashboard/mahasiswa" class="nav-item"><span class="nav-icon">🏠</span> Dashboard</a>
        <a href="/dashboard/mahasiswa/riwayat" class="nav-item"><span class="nav-icon">📂</span> Riwayat</a>
        <a href="/notifikasi" class="nav-item active"><span class="nav-icon">🔔</span> Notifikasi</a>
        <a href="/user/profile" class="nav-item"><span class="nav-icon">👤</span> Profil</a>
    ',
    'STAF_TU_PRODI' => '
        <span class="nav-section-label">Menu</span>
        <a href="/dashboard/staf-tu" class="nav-item"><span class="nav-icon">🏠</span> Dashboard</a>
        <a href="/dashboard/staf-tu/inbox" class="nav-item"><span class="nav-icon">📥</span> Inbox</a>
        <a href="/notifikasi" class="nav-item active"><span class="nav-icon">🔔</span> Notifikasi</a>
    ',
    'KAPRODI' => '
        <span class="nav-section-label">Menu</span>
        <a href="/dashboard/kaprodi" class="nav-item"><span class="nav-icon">🏠</span> Dashboard</a>
        <a href="/dashboard/kaprodi/inbox" class="nav-item"><span class="nav-icon">📥</span> Inbox</a>
        <a href="/notifikasi" class="nav-item active"><span class="nav-icon">🔔</span> Notifikasi</a>
    ',
    'DEKAN' => '
        <span class="nav-section-label">Menu</span>
        <a href="/dashboard/dekan" class="nav-item"><span class="nav-icon">🏠</span> Dashboard</a>
        <a href="/dashboard/dekan/inbox" class="nav-item"><span class="nav-icon">📥</span> Inbox</a>
        <a href="/notifikasi" class="nav-item active"><span class="nav-icon">🔔</span> Notifikasi</a>
    ',
];
$sidebarNav = $_navMap[$role] ?? '
    <span class="nav-section-label">Menu</span>
    <a href="/dashboard/admin" class="nav-item"><span class="nav-icon">🏠</span> Dashboard</a>
    <a href="/notifikasi" class="nav-item active"><span class="nav-icon">🔔</span> Notifikasi</a>
';

$notifUrl = '/notifikasi';
ob_start();
?>

<div class="card" style="max-width:700px">
    <div class="card-header">
        <div>
            <div class="card-title">🔔 Notifikasi</div>
            <div class="card-sub"><?= count($notifikasi) ?> notifikasi</div>
        </div>
        <?php if (!empty($notifikasi)): ?>
        <form action="/notifikasi/read-all" method="post">
            <?= csrf_field() ?>
            <button type="submit" class="btn btn-outline btn-sm">✅ Tandai Semua Dibaca</button>
        </form>
        <?php endif ?>
    </div>

    <?php if (empty($notifikasi)): ?>
        <div class="empty-state">
            <div class="empty-icon">🔕</div>
            <div class="empty-title">Tidak ada notifikasi</div>
            <div class="empty-sub">Notifikasi akan muncul ketika ada perubahan status pengajuan.</div>
        </div>
    <?php else: ?>
        <div style="display:flex;flex-direction:column;gap:.5rem">
        <?php
        $_linkMap = [
            'MAHASISWA'     => '/dashboard/mahasiswa/riwayat/',
            'STAF_TU_PRODI' => '/dashboard/staf-tu/detail/',
            'KAPRODI'       => '/dashboard/kaprodi/detail/',
            'DEKAN'         => '/dashboard/dekan/detail/',
            'ADMIN'         => '/dashboard/admin/',
        ];
        foreach ($notifikasi as $n):
            $baseLink = $_linkMap[$role] ?? '#';
            $linkUrl  = $n['pengajuan_id'] ? $baseLink . $n['pengajuan_id'] : '#';
        ?>

            <a href="<?= $linkUrl ?>" style="text-decoration:none;display:block">
                <div style="
                    padding:.9rem 1rem;
                    border-radius:8px;
                    border:1px solid <?= $n['is_read'] ? 'var(--border)' : 'rgba(99,102,241,.4)' ?>;
                    background:<?= $n['is_read'] ? 'var(--surface2)' : 'rgba(99,102,241,.06)' ?>;
                    display:flex;gap:.85rem;align-items:flex-start;
                    transition:border-color .15s;
                " onmouseover="this.style.borderColor='var(--accent)'" onmouseout="this.style.borderColor='<?= $n['is_read'] ? 'var(--border)' : 'rgba(99,102,241,.4)' ?>'">
                    <div style="font-size:1.2rem;flex-shrink:0;margin-top:.1rem"><?= $n['is_read'] ? '🔔' : '🔴' ?></div>
                    <div style="flex:1">
                        <div style="font-size:.85rem;font-weight:<?= $n['is_read'] ? '500' : '700' ?>;color:var(--text)">
                            <?= esc($n['judul']) ?>
                        </div>
                        <div style="font-size:.79rem;color:var(--muted);margin-top:.2rem;line-height:1.5">
                            <?= esc($n['pesan']) ?>
                        </div>
                        <div style="font-size:.7rem;color:var(--muted);margin-top:.35rem">
                            <?= date('d M Y, H:i', strtotime($n['created_at'])) ?>
                        </div>
                    </div>
                    <?php if (!$n['is_read']): ?>
                        <div style="width:8px;height:8px;border-radius:50%;background:var(--accent);flex-shrink:0;margin-top:4px"></div>
                    <?php endif ?>
                </div>
            </a>
            <?php endforeach ?>
        </div>
    <?php endif ?>

    <div style="margin-top:1.25rem;padding-top:1rem;border-top:1px solid var(--border)">
        <a href="<?= $backUrl ?>" class="btn btn-outline btn-sm">← Kembali</a>
    </div>
</div>

<?php
$pageContent = ob_get_clean();
require APPPATH . 'Views/layouts/main.php';
