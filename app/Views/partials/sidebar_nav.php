<?php
/**
 * sidebar_nav.php — Unified Single Source of Truth Navigation
 * Menyelaraskan struktur menu dengan project Node.js (React)
 * Menggunakan HMVC CI4, deteksi link aktif, serta badge angka ringkas dengan toolbar hover.
 */

$role   = session('role') ?? 'MAHASISWA';
$uri    = uri_string();
$userId = session('id') ?? session('user_id');

$isExact = function(string $path) use ($uri): string {
    return ($uri === $path) ? ' active' : '';
};

$isMatch = function(string $pattern) use ($uri): string {
    return (strpos($uri, $pattern) !== false) ? ' active' : '';
};

$isSubActive = function(string $pattern) use ($uri): string {
    return (strpos($uri, $pattern) !== false) ? ' active' : '';
};

$isPengajuanActive = (strpos($uri, 'pengajuan') !== false);

$db = \Config\Database::connect();

$unreadNotifCount  = 0;
$pendingInboxCount = 0;
$revisiCount       = 0;
$diprosesCount     = 0;
$ditolakCount      = 0;

if ($userId) {
    try {
        $unreadNotifCount = (int) $db->table('notifikasi')
            ->where('user_id', $userId)
            ->where('is_read', 0)
            ->countAllResults();

        if ($role === 'STAF_TU_PRODI') {
            $pendingInboxCount = (int) $db->table('pengajuan_surat')->where('status', 'MENUNGGU_TU')->countAllResults();
            $diprosesCount     = (int) $db->table('pengajuan_surat')->whereIn('status', ['MENUNGGU_KAPRODI', 'MENUNGGU_DEKAN'])->countAllResults();
            $ditolakCount      = (int) $db->table('pengajuan_surat')->where('status', 'DITOLAK')->countAllResults();
        } elseif ($role === 'KAPRODI') {
            $pendingInboxCount = (int) $db->table('pengajuan_surat')->where('status', 'MENUNGGU_KAPRODI')->countAllResults();
        } elseif ($role === 'DEKAN') {
            $pendingInboxCount = (int) $db->table('pengajuan_surat')->where('status', 'MENUNGGU_DEKAN')->countAllResults();
        } elseif ($role === 'MAHASISWA') {
            $pendingInboxCount = (int) $db->table('pengajuan_surat')
                ->where('mahasiswa_id', $userId)
                ->whereIn('status', ['MENUNGGU_TU', 'MENUNGGU_KAPRODI', 'MENUNGGU_DEKAN'])
                ->countAllResults();
            $revisiCount       = (int) $db->table('pengajuan_surat')
                ->where('mahasiswa_id', $userId)
                ->where('status', 'BUTUH_REVISI')
                ->countAllResults();
        } elseif ($role === 'ADMIN') {
            $pendingInboxCount = (int) $db->table('pengajuan_surat')
                ->whereIn('status', ['MENUNGGU_TU', 'MENUNGGU_KAPRODI', 'MENUNGGU_DEKAN'])
                ->countAllResults();
        }
    } catch (\Throwable $e) {}
}

/**
 * Render badge angka ringkas ("angka aja") dengan toolbar floating saat mouse di-hover
 */
$renderBadge = function(int $count, string $type = 'pending', string $title = '', string $desc = '', string $icon = '', bool $alwaysShow = false): string {
    if ($count <= 0 && !$alwaysShow) return '';

    if ($count <= 0) {
        $t = $title ?: 'Tidak ada antrean tertunda';
        $d = $desc ?: 'Semua berkas telah diproses';
        $i = $icon ?: '✅';
        return '<span class="nav-counter-pill clean" data-toolbar-title="' . esc($t) . '" data-toolbar-desc="' . esc($d) . '" data-toolbar-icon="' . esc($i) . '" title="' . esc($t) . '">0</span>';
    }

    if ($type === 'danger' || $type === 'notif') {
        $t = $title ?: ($count . ' Notifikasi Baru');
        $d = $desc ?: 'Pesan notifikasi belum dibaca (Klik untuk buka)';
        $i = $icon ?: '🔔';
        return '<span class="nav-counter-pill notif" data-toolbar-title="' . esc($t) . '" data-toolbar-desc="' . esc($d) . '" data-toolbar-icon="' . esc($i) . '" title="' . esc($t) . '">' . $count . '</span>';
    }

    if ($type === 'revisi') {
        $t = $title ?: ($count . ' Berkas Butuh Revisi');
        $d = $desc ?: 'Perlu perbaikan data pengajuan (Klik untuk buka)';
        $i = $icon ?: '⚠️';
        return '<span class="nav-counter-pill revisi" data-toolbar-title="' . esc($t) . '" data-toolbar-desc="' . esc($d) . '" data-toolbar-icon="' . esc($i) . '" title="' . esc($t) . '">' . $count . '</span>';
    }

    if ($type === 'info') {
        $t = $title ?: ($count . ' Berkas Sedang Berjalan');
        $d = $desc ?: 'Sedang ditinjau pada tahap selanjutnya';
        $i = $icon ?: '⏳';
        return '<span class="nav-counter-pill info" data-toolbar-title="' . esc($t) . '" data-toolbar-desc="' . esc($d) . '" data-toolbar-icon="' . esc($i) . '" title="' . esc($t) . '">' . $count . '</span>';
    }

    if ($type === 'muted') {
        $t = $title ?: ($count . ' Berkas Ditolak');
        $d = $desc ?: 'Arsip pengajuan yang ditolak';
        $i = $icon ?: '❌';
        return '<span class="nav-counter-pill muted" data-toolbar-title="' . esc($t) . '" data-toolbar-desc="' . esc($d) . '" data-toolbar-icon="' . esc($i) . '" title="' . esc($t) . '">' . $count . '</span>';
    }

    // Default: 'pending' (amber glowing badge with pure number)
    $t = $title ?: ($count . ' Berkas Belum Disetujui');
    $d = $desc ?: 'Menunggu tindakan persetujuan (Klik untuk buka)';
    $i = $icon ?: '⏳';
    return '<span class="nav-counter-pill pending" data-toolbar-title="' . esc($t) . '" data-toolbar-desc="' . esc($d) . '" data-toolbar-icon="' . esc($i) . '" title="' . esc($t) . '">' . $count . '</span>';
};
?>

<?php if ($role === 'MAHASISWA'): ?>
    <div class="nav-section-label">Menu Utama</div>
    <a href="/dashboard/mahasiswa" class="nav-item<?= $isExact('dashboard/mahasiswa') ?>">
        <span class="nav-icon">📊</span> <span>Dashboard</span>
    </a>

    <div class="nav-section-label">Layanan Surat</div>
    <div class="nav-group">
        <div class="nav-group-header" onclick="this.nextElementSibling.style.display = this.nextElementSibling.style.display === 'none' ? 'flex' : 'none';">
            <span class="nav-icon">📝</span>
            <span>Pengajuan Surat</span>
            <span class="nav-group-arrow">▼</span>
        </div>
        <div class="nav-sub-items" style="<?= $isPengajuanActive ? 'display:flex;' : 'display:flex;' ?>">
            <a href="/dashboard/mahasiswa/pengajuan/aktif" class="nav-sub-item<?= $isSubActive('pengajuan/aktif') ?>">
                <span>📄</span> Keterangan Aktif
            </a>
            <a href="/dashboard/mahasiswa/pengajuan/tidak-kuliah" class="nav-sub-item<?= $isSubActive('pengajuan/tidak-kuliah') ?>">
                <span>📄</span> Tidak Ada Perkuliahan
            </a>
            <a href="/dashboard/mahasiswa/pengajuan/penelitian" class="nav-sub-item<?= $isSubActive('pengajuan/penelitian') ?>">
                <span>🔬</span> Izin Penelitian
            </a>
            <a href="/dashboard/mahasiswa/pengajuan/plp-magang" class="nav-sub-item<?= $isSubActive('pengajuan/plp-magang') ?>">
                <span>🏢</span> PLP / Magang
            </a>
            <a href="/dashboard/mahasiswa/pengajuan/observasi" class="nav-sub-item<?= $isSubActive('pengajuan/observasi') ?>">
                <span>👁️</span> Observasi Matkul
            </a>
            <a href="/dashboard/mahasiswa/pengajuan/rekomendasi" class="nav-sub-item<?= $isSubActive('pengajuan/rekomendasi') ?>">
                <span>⭐</span> Rekomendasi
            </a>
            <a href="/dashboard/mahasiswa/pengajuan/lulus" class="nav-sub-item<?= $isSubActive('pengajuan/lulus') ?>">
                <span>🎓</span> Keterangan Lulus
            </a>
        </div>
    </div>

    <div class="nav-section-label">Aktivitas</div>
    <a href="/dashboard/mahasiswa/riwayat" class="nav-item<?= $isMatch('mahasiswa/riwayat') ?>">
        <span class="nav-icon">📜</span> <span>Riwayat Pengajuan</span>
        <?php if ($revisiCount > 0): ?>
            <?= $renderBadge($revisiCount, 'revisi', $revisiCount . ' Berkas Perlu Revisi', 'Perlu perbaikan data pengajuan (Klik untuk buka)', '⚠️') ?>
        <?php else: ?>
            <?= $renderBadge($pendingInboxCount, 'pending', $pendingInboxCount . ' Berkas Belum Disetujui', 'Menunggu persetujuan pejabat kampus (Klik untuk buka)', '⏳', true) ?>
        <?php endif; ?>
    </a>
    <a href="/notifikasi" class="nav-item<?= $isMatch('notifikasi') ?>">
        <span class="nav-icon">🔔</span> <span>Notifikasi</span>
        <?= $renderBadge($unreadNotifCount, 'notif', $unreadNotifCount . ' Notifikasi Baru', 'Pesan notifikasi belum dibaca (Klik untuk buka)', '🔔', true) ?>
    </a>

<?php elseif ($role === 'STAF_TU_PRODI'): ?>
    <div class="nav-section-label">Menu Utama</div>
    <a href="/dashboard/staf-tu" class="nav-item<?= $isExact('dashboard/staf-tu') ?>">
        <span class="nav-icon">📊</span> <span>Dashboard</span>
    </a>

    <div class="nav-section-label">Administrasi Surat</div>
    <a href="/dashboard/staf-tu/inbox" class="nav-item<?= ($isMatch('staf-tu/inbox') && !isset($_GET['status']) || $isMatch('staf-tu/detail')) ? ' active' : '' ?>">
        <span class="nav-icon">📥</span> <span>Inbox Pengajuan</span>
        <?= $renderBadge($pendingInboxCount, 'pending', $pendingInboxCount . ' Berkas Belum Disetujui', 'Menunggu verifikasi Staf TU Prodi (Klik untuk buka)', '⏳', true) ?>
    </a>
    <a href="/dashboard/staf-tu/inbox?status=MENUNGGU_KAPRODI" class="nav-item<?= ($uri === 'dashboard/staf-tu/inbox' && isset($_GET['status']) && $_GET['status'] === 'MENUNGGU_KAPRODI') ? ' active' : '' ?>">
        <span class="nav-icon">⏳</span> <span>Pengajuan Diproses</span>
        <?= $renderBadge($diprosesCount, 'info', $diprosesCount . ' Berkas Sedang Berjalan', 'Sedang ditinjau oleh Kaprodi / Dekan', '⏳') ?>
    </a>
    <a href="/dashboard/staf-tu/inbox?status=DITOLAK" class="nav-item<?= ($uri === 'dashboard/staf-tu/inbox' && isset($_GET['status']) && $_GET['status'] === 'DITOLAK') ? ' active' : '' ?>">
        <span class="nav-icon">❌</span> <span>Pengajuan Ditolak</span>
        <?= $renderBadge($ditolakCount, 'muted', $ditolakCount . ' Berkas Ditolak', 'Arsip pengajuan yang ditolak', '❌') ?>
    </a>
    <a href="/dashboard/staf-tu/inbox?status=ALL" class="nav-item<?= ($uri === 'dashboard/staf-tu/inbox' && isset($_GET['status']) && $_GET['status'] === 'ALL') ? ' active' : '' ?>">
        <span class="nav-icon">📁</span> <span>Semua Pengajuan</span>
    </a>
    <a href="/dashboard/staf-tu/mitra" class="nav-item<?= $isMatch('staf-tu/mitra') ?>">
        <span class="nav-icon">📍</span> <span>Kelola Tempat/Mitra</span>
    </a>
    <a href="/notifikasi" class="nav-item<?= $isMatch('notifikasi') ?>">
        <span class="nav-icon">🔔</span> <span>Notifikasi</span>
        <?= $renderBadge($unreadNotifCount, 'notif', $unreadNotifCount . ' Notifikasi Baru', 'Pesan belum dibaca (Klik untuk buka)', '🔔', true) ?>
    </a>

<?php elseif ($role === 'KAPRODI'): ?>
    <div class="nav-section-label">Menu Utama</div>
    <a href="/dashboard/kaprodi" class="nav-item<?= $isExact('dashboard/kaprodi') ?>">
        <span class="nav-icon">📊</span> <span>Dashboard</span>
    </a>

    <div class="nav-section-label">Persetujuan & Data</div>
    <a href="/dashboard/kaprodi/inbox" class="nav-item<?= ($isMatch('kaprodi/inbox') || $isMatch('kaprodi/detail')) ? ' active' : '' ?>">
        <span class="nav-icon">📥</span> <span>Inbox Persetujuan</span>
        <?= $renderBadge($pendingInboxCount, 'pending', $pendingInboxCount . ' Berkas Belum Disetujui', 'Menunggu persetujuan Kaprodi (Klik untuk buka)', '⏳', true) ?>
    </a>
    <a href="/dashboard/kaprodi/riwayat" class="nav-item<?= $isMatch('kaprodi/riwayat') ?>">
        <span class="nav-icon">📜</span> <span>Riwayat Persetujuan</span>
    </a>
    <a href="/dashboard/kaprodi/statistik" class="nav-item<?= $isMatch('kaprodi/statistik') ?>">
        <span class="nav-icon">📈</span> <span>Statistik Prodi</span>
    </a>
    <a href="/notifikasi" class="nav-item<?= $isMatch('notifikasi') ?>">
        <span class="nav-icon">🔔</span> <span>Notifikasi</span>
        <?= $renderBadge($unreadNotifCount, 'notif', $unreadNotifCount . ' Notifikasi Baru', 'Pesan belum dibaca (Klik untuk buka)', '🔔', true) ?>
    </a>

<?php elseif ($role === 'DEKAN'): ?>
    <div class="nav-section-label">Menu Utama</div>
    <a href="/dashboard/dekan" class="nav-item<?= $isExact('dashboard/dekan') ?>">
        <span class="nav-icon">🏛️</span> <span>Dashboard Eksekutif</span>
    </a>

    <div class="nav-section-label">Pengesahan & Laporan</div>
    <a href="/dashboard/dekan/inbox" class="nav-item<?= ($isMatch('dekan/inbox') || $isMatch('dekan/detail')) ? ' active' : '' ?>">
        <span class="nav-icon">🖋️</span> <span>Inbox Penandatanganan</span>
        <?= $renderBadge($pendingInboxCount, 'pending', $pendingInboxCount . ' Berkas Belum Disetujui', 'Menunggu tanda tangan & cap Dekan (Klik untuk buka)', '🖋️', true) ?>
    </a>
    <a href="/dashboard/dekan/riwayat" class="nav-item<?= $isMatch('dekan/riwayat') ?>">
        <span class="nav-icon">📜</span> <span>Surat Ditandatangani</span>
    </a>
    <a href="/dashboard/dekan/laporan" class="nav-item<?= $isMatch('dekan/laporan') ?>">
        <span class="nav-icon">📈</span> <span>Laporan & Statistik</span>
    </a>
    <a href="/notifikasi" class="nav-item<?= $isMatch('notifikasi') ?>">
        <span class="nav-icon">🔔</span> <span>Notifikasi</span>
        <?= $renderBadge($unreadNotifCount, 'notif', $unreadNotifCount . ' Notifikasi Baru', 'Pesan belum dibaca (Klik untuk buka)', '🔔', true) ?>
    </a>

<?php elseif ($role === 'ADMIN'): ?>
    <div class="nav-section-label">Menu Utama</div>
    <a href="/dashboard/admin" class="nav-item<?= $isExact('dashboard/admin') ?>">
        <span class="nav-icon">⚙️</span> <span>Dashboard</span>
    </a>

    <div class="nav-section-label">Master Data & Sistem</div>
    <a href="/dashboard/admin/users" class="nav-item<?= $isMatch('admin/users') ?>">
        <span class="nav-icon">👥</span> <span>Manajemen User</span>
    </a>
    <a href="/dashboard/admin/pengajuan" class="nav-item<?= $isMatch('admin/pengajuan') ?>">
        <span class="nav-icon">📁</span> <span>Semua Pengajuan</span>
        <?= $renderBadge($pendingInboxCount, 'pending', $pendingInboxCount . ' Berkas Belum Disetujui', 'Total berkas menunggu approval di sistem (Klik untuk buka)', '📁', true) ?>
    </a>
    <a href="/dashboard/admin/mitra" class="nav-item<?= $isMatch('admin/mitra') ?>">
        <span class="nav-icon">📍</span> <span>Tempat / Mitra</span>
    </a>
    <a href="/dashboard/admin/laporan" class="nav-item<?= $isMatch('admin/laporan') ?>">
        <span class="nav-icon">📈</span> <span>Laporan Sistem</span>
    </a>
    <a href="/notifikasi" class="nav-item<?= $isMatch('notifikasi') ?>">
        <span class="nav-icon">🔔</span> <span>Notifikasi</span>
        <?= $renderBadge($unreadNotifCount, 'notif', $unreadNotifCount . ' Notifikasi Baru', 'Pesan belum dibaca (Klik untuk buka)', '🔔', true) ?>
    </a>

<?php endif; ?>
