<?php
/**
 * sidebar_nav.php — Unified Single Source of Truth Navigation
 * Menyelaraskan struktur menu dengan project Node.js (React)
 * Menggunakan HMVC CI4 dan deteksi link aktif.
 */

$role = session('role') ?? 'MAHASISWA';
$uri  = uri_string();

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
?>

<?php if ($role === 'MAHASISWA'): ?>
    <div class="nav-section-label">Menu Utama</div>
    <a href="/dashboard/mahasiswa" class="nav-item<?= $isExact('dashboard/mahasiswa') ?>">
        <span class="nav-icon">📊</span> Dashboard
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
        <span class="nav-icon">📜</span> Riwayat Pengajuan
    </a>
    <a href="/notifikasi" class="nav-item<?= $isMatch('notifikasi') ?>">
        <span class="nav-icon">🔔</span> Notifikasi
    </a>

<?php elseif ($role === 'STAF_TU_PRODI'): ?>
    <div class="nav-section-label">Menu Utama</div>
    <a href="/dashboard/staf-tu" class="nav-item<?= $isExact('dashboard/staf-tu') ?>">
        <span class="nav-icon">📊</span> Dashboard
    </a>

    <div class="nav-section-label">Administrasi Surat</div>
    <a href="/dashboard/staf-tu/inbox" class="nav-item<?= ($isMatch('staf-tu/inbox') || $isMatch('staf-tu/detail')) ? ' active' : '' ?>">
        <span class="nav-icon">📥</span> Inbox Pengajuan
    </a>
    <a href="/dashboard/staf-tu/inbox?status=MENUNGGU_KAPRODI" class="nav-item<?= ($uri === 'dashboard/staf-tu/inbox' && isset($_GET['status']) && $_GET['status'] === 'MENUNGGU_KAPRODI') ? ' active' : '' ?>">
        <span class="nav-icon">⏳</span> Pengajuan Diproses
    </a>
    <a href="/dashboard/staf-tu/inbox?status=DITOLAK" class="nav-item<?= ($uri === 'dashboard/staf-tu/inbox' && isset($_GET['status']) && $_GET['status'] === 'DITOLAK') ? ' active' : '' ?>">
        <span class="nav-icon">❌</span> Pengajuan Ditolak
    </a>
    <a href="/dashboard/staf-tu/inbox?status=ALL" class="nav-item<?= ($uri === 'dashboard/staf-tu/inbox' && isset($_GET['status']) && $_GET['status'] === 'ALL') ? ' active' : '' ?>">
        <span class="nav-icon">📁</span> Semua Pengajuan
    </a>
    <a href="/dashboard/staf-tu/mitra" class="nav-item<?= $isMatch('staf-tu/mitra') ?>">
        <span class="nav-icon">📍</span> Kelola Tempat/Mitra
    </a>
    <a href="/notifikasi" class="nav-item<?= $isMatch('notifikasi') ?>">
        <span class="nav-icon">🔔</span> Notifikasi
    </a>

<?php elseif ($role === 'KAPRODI'): ?>
    <div class="nav-section-label">Menu Utama</div>
    <a href="/dashboard/kaprodi" class="nav-item<?= $isExact('dashboard/kaprodi') ?>">
        <span class="nav-icon">📊</span> Dashboard
    </a>

    <div class="nav-section-label">Persetujuan & Data</div>
    <a href="/dashboard/kaprodi/inbox" class="nav-item<?= ($isMatch('kaprodi/inbox') || $isMatch('kaprodi/detail')) ? ' active' : '' ?>">
        <span class="nav-icon">📥</span> Inbox Persetujuan
    </a>
    <a href="/dashboard/kaprodi/riwayat" class="nav-item<?= $isMatch('kaprodi/riwayat') ?>">
        <span class="nav-icon">📜</span> Riwayat Persetujuan
    </a>
    <a href="/dashboard/kaprodi/statistik" class="nav-item<?= $isMatch('kaprodi/statistik') ?>">
        <span class="nav-icon">📈</span> Statistik Prodi
    </a>
    <a href="/notifikasi" class="nav-item<?= $isMatch('notifikasi') ?>">
        <span class="nav-icon">🔔</span> Notifikasi
    </a>

<?php elseif ($role === 'DEKAN'): ?>
    <div class="nav-section-label">Menu Utama</div>
    <a href="/dashboard/dekan" class="nav-item<?= $isExact('dashboard/dekan') ?>">
        <span class="nav-icon">🏛️</span> Dashboard Eksekutif
    </a>

    <div class="nav-section-label">Pengesahan & Laporan</div>
    <a href="/dashboard/dekan/inbox" class="nav-item<?= ($isMatch('dekan/inbox') || $isMatch('dekan/detail')) ? ' active' : '' ?>">
        <span class="nav-icon">🖋️</span> Inbox Penandatanganan
    </a>
    <a href="/dashboard/dekan/riwayat" class="nav-item<?= $isMatch('dekan/riwayat') ?>">
        <span class="nav-icon">📜</span> Surat Ditandatangani
    </a>
    <a href="/dashboard/dekan/laporan" class="nav-item<?= $isMatch('dekan/laporan') ?>">
        <span class="nav-icon">📈</span> Laporan & Statistik
    </a>
    <a href="/notifikasi" class="nav-item<?= $isMatch('notifikasi') ?>">
        <span class="nav-icon">🔔</span> Notifikasi
    </a>

<?php elseif ($role === 'ADMIN'): ?>
    <div class="nav-section-label">Menu Utama</div>
    <a href="/dashboard/admin" class="nav-item<?= $isExact('dashboard/admin') ?>">
        <span class="nav-icon">⚙️</span> Dashboard
    </a>

    <div class="nav-section-label">Master Data & Sistem</div>
    <a href="/dashboard/admin/users" class="nav-item<?= $isMatch('admin/users') ?>">
        <span class="nav-icon">👥</span> Manajemen User
    </a>
    <a href="/dashboard/admin/pengajuan" class="nav-item<?= $isMatch('admin/pengajuan') ?>">
        <span class="nav-icon">📁</span> Semua Pengajuan
    </a>
    <a href="/dashboard/admin/mitra" class="nav-item<?= $isMatch('admin/mitra') ?>">
        <span class="nav-icon">📍</span> Tempat / Mitra
    </a>
    <a href="/dashboard/admin/laporan" class="nav-item<?= $isMatch('admin/laporan') ?>">
        <span class="nav-icon">📈</span> Laporan Sistem
    </a>
    <a href="/notifikasi" class="nav-item<?= $isMatch('notifikasi') ?>">
        <span class="nav-icon">🔔</span> Notifikasi
    </a>

<?php endif; ?>
