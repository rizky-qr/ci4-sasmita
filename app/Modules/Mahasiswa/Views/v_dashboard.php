<?php
// Sidebar navigasi khusus mahasiswa
$sidebarNav = '
<span class="nav-section-label">Menu</span>
<a href="/dashboard/mahasiswa" class="nav-item ' . (uri_string() === 'dashboard/mahasiswa' ? 'active' : '') . '">
    <span class="nav-icon">🏠</span> Dashboard
</a>
<span class="nav-section-label">Pengajuan Surat</span>
<a href="/dashboard/mahasiswa/pengajuan/aktif" class="nav-item ' . (strpos(uri_string(), 'pengajuan/aktif') !== false ? 'active' : '') . '">
    <span class="nav-icon">📋</span> Keterangan Aktif
</a>
<a href="/dashboard/mahasiswa/pengajuan/tidak-kuliah" class="nav-item ' . (strpos(uri_string(), 'tidak-kuliah') !== false ? 'active' : '') . '">
    <span class="nav-icon">📋</span> Tidak Ada Kuliah
</a>
<a href="/dashboard/mahasiswa/pengajuan/penelitian" class="nav-item ' . (strpos(uri_string(), 'penelitian') !== false ? 'active' : '') . '">
    <span class="nav-icon">🔬</span> Izin Penelitian
</a>
<a href="/dashboard/mahasiswa/pengajuan/plp-magang" class="nav-item ' . (strpos(uri_string(), 'plp-magang') !== false ? 'active' : '') . '">
    <span class="nav-icon">🏢</span> PLP / Magang
</a>
<a href="/dashboard/mahasiswa/pengajuan/observasi" class="nav-item ' . (strpos(uri_string(), 'observasi') !== false ? 'active' : '') . '">
    <span class="nav-icon">👁️</span> Observasi
</a>
<a href="/dashboard/mahasiswa/pengajuan/rekomendasi" class="nav-item ' . (strpos(uri_string(), 'rekomendasi') !== false ? 'active' : '') . '">
    <span class="nav-icon">⭐</span> Rekomendasi
</a>
<a href="/dashboard/mahasiswa/pengajuan/lulus" class="nav-item ' . (strpos(uri_string(), 'lulus') !== false ? 'active' : '') . '">
    <span class="nav-icon">🎓</span> Keterangan Lulus
</a>
<span class="nav-section-label">Riwayat</span>
<a href="/dashboard/mahasiswa/riwayat" class="nav-item ' . (strpos(uri_string(), 'riwayat') !== false ? 'active' : '') . '">
    <span class="nav-icon">📂</span> Riwayat Pengajuan
</a>
<a href="/dashboard/mahasiswa/notifikasi" class="nav-item ' . (strpos(uri_string(), 'notifikasi') !== false ? 'active' : '') . '">
    <span class="nav-icon">🔔</span> Notifikasi
</a>
<span class="nav-section-label">Akun</span>
<a href="/user/profile" class="nav-item">
    <span class="nav-icon">👤</span> Profil Saya
</a>
';

$notifUrl = '/dashboard/mahasiswa/notifikasi';

require APPPATH . 'Views/partials/status_helpers.php';

$jenisLabel = $jenisLabel ?? [
    'KETERANGAN_AKTIF'      => 'Surat Keterangan Aktif',
    'TIDAK_ADA_PERKULIAHAN' => 'Surat Tidak Ada Perkuliahan',
    'IZIN_PENELITIAN'       => 'Surat Izin Penelitian',
    'PERMOHONAN_PLP_MAGANG' => 'Surat Permohonan PLP/Magang',
    'PERMOHONAN_OBSERVASI'  => 'Surat Permohonan Observasi',
    'REKOMENDASI'           => 'Surat Rekomendasi',
    'KETERANGAN_LULUS'      => 'Surat Keterangan Lulus',
];

$pageContent = '
<div class="stats-grid">
    <div class="stat-card" style="--stat-color:#6366f1">
        <div class="stat-icon">📊</div>
        <div class="stat-value">' . ($stats['total'] ?? 0) . '</div>
        <div class="stat-label">Total Pengajuan</div>
    </div>
    <div class="stat-card" style="--stat-color:#fbbf24">
        <div class="stat-icon">⏳</div>
        <div class="stat-value">' . ($stats['menunggu'] ?? 0) . '</div>
        <div class="stat-label">Sedang Diproses</div>
    </div>
    <div class="stat-card" style="--stat-color:#34d399">
        <div class="stat-icon">✅</div>
        <div class="stat-value">' . ($stats['selesai'] ?? 0) . '</div>
        <div class="stat-label">Selesai</div>
    </div>
    <div class="stat-card" style="--stat-color:#f87171">
        <div class="stat-icon">❌</div>
        <div class="stat-value">' . ($stats['ditolak'] ?? 0) . '</div>
        <div class="stat-label">Ditolak</div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div>
            <div class="card-title">Pengajuan Terbaru</div>
            <div class="card-sub">5 pengajuan terakhir kamu</div>
        </div>
        <a href="/dashboard/mahasiswa/riwayat" class="btn btn-outline btn-sm">Lihat Semua</a>
    </div>
    <div class="table-wrapper">
        ' . (empty($terbaru) ? '
        <div class="empty-state">
            <div class="empty-icon">📭</div>
            <div class="empty-title">Belum ada pengajuan</div>
            <div class="empty-sub">Pilih jenis surat di menu kiri untuk mengajukan surat.</div>
        </div>
        ' : '
        <table>
            <thead>
                <tr>
                    <th>Jenis Surat</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                ' . implode('', array_map(function($p) use ($jenisLabel) {
                    $label   = $jenisLabel[$p['jenis_surat']] ?? $p['jenis_surat'];
                    $tgl     = date('d M Y', strtotime($p['created_at']));
                    $badge   = statusBadge($p['status']);
                    return "<tr>
                        <td>{$label}</td>
                        <td style='color:var(--muted)'>{$tgl}</td>
                        <td>{$badge}</td>
                        <td><a href='/dashboard/mahasiswa/riwayat/{$p['id']}' class='btn btn-outline btn-sm'>Detail</a></td>
                    </tr>";
                }, $terbaru ?? [])) . '
            </tbody>
        </table>
        ') . '
    </div>
</div>

<div class="card" style="margin-top:1rem">
    <div class="card-header">
        <div class="card-title">Ajukan Surat Baru</div>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:.75rem">
        <a href="/dashboard/mahasiswa/pengajuan/aktif" class="btn btn-outline" style="justify-content:center;padding:.75rem">📋 Keterangan Aktif</a>
        <a href="/dashboard/mahasiswa/pengajuan/tidak-kuliah" class="btn btn-outline" style="justify-content:center;padding:.75rem">📋 Tidak Ada Kuliah</a>
        <a href="/dashboard/mahasiswa/pengajuan/penelitian" class="btn btn-outline" style="justify-content:center;padding:.75rem">🔬 Penelitian</a>
        <a href="/dashboard/mahasiswa/pengajuan/plp-magang" class="btn btn-outline" style="justify-content:center;padding:.75rem">🏢 PLP/Magang</a>
        <a href="/dashboard/mahasiswa/pengajuan/observasi" class="btn btn-outline" style="justify-content:center;padding:.75rem">👁️ Observasi</a>
        <a href="/dashboard/mahasiswa/pengajuan/rekomendasi" class="btn btn-outline" style="justify-content:center;padding:.75rem">⭐ Rekomendasi</a>
        <a href="/dashboard/mahasiswa/pengajuan/lulus" class="btn btn-outline" style="justify-content:center;padding:.75rem">🎓 Ket. Lulus</a>
    </div>
</div>
';

// Render layout
extract(['jenisLabel' => $jenisLabel]);
require APPPATH . 'Views/layouts/main.php';
