<?php
// v_inbox.php — Digunakan untuk semua tab inbox StafTU
$tabItems = [
    'menunggu' => ['url' => '/dashboard/staf-tu/inbox',    'label' => 'Menunggu'],
    'diproses' => ['url' => '/dashboard/staf-tu/diproses', 'label' => 'Diteruskan'],
    'ditolak'  => ['url' => '/dashboard/staf-tu/ditolak',  'label' => 'Ditolak'],
    'semua'    => ['url' => '/dashboard/staf-tu/semua',    'label' => 'Semua'],
];

$sidebarNav = '
<span class="nav-section-label">Menu</span>
<a href="/dashboard/staf-tu" class="nav-item"><span class="nav-icon">🏠</span> Dashboard</a>
<span class="nav-section-label">Inbox</span>
<a href="/dashboard/staf-tu/inbox"    class="nav-item ' . ($tab==='menunggu'?'active':'') . '"><span class="nav-icon">📥</span> Menunggu Verifikasi</a>
<a href="/dashboard/staf-tu/diproses" class="nav-item ' . ($tab==='diproses'?'active':'') . '"><span class="nav-icon">⏳</span> Diteruskan</a>
<a href="/dashboard/staf-tu/ditolak"  class="nav-item ' . ($tab==='ditolak' ?'active':'') . '"><span class="nav-icon">❌</span> Ditolak</a>
<a href="/dashboard/staf-tu/semua"    class="nav-item ' . ($tab==='semua'   ?'active':'') . '"><span class="nav-icon">📋</span> Semua Pengajuan</a>
<span class="nav-section-label">Akun</span>
<a href="/user/profile" class="nav-item"><span class="nav-icon">👤</span> Profil Saya</a>
';
$notifUrl = '/notifikasi';

require APPPATH . 'Views/partials/status_helpers.php';

ob_start();
?>

<!-- Tab Navigation -->
<div style="display:flex;gap:.5rem;margin-bottom:1.25rem;border-bottom:1px solid var(--border);padding-bottom:.75rem">
    <?php foreach ($tabItems as $key => $item): ?>
    <a href="<?= $item['url'] ?>" class="btn <?= $tab === $key ? 'btn-primary' : 'btn-outline' ?> btn-sm">
        <?= $item['label'] ?>
    </a>
    <?php endforeach ?>
</div>

<div class="card">
    <div class="card-header">
        <div>
            <div class="card-title"><?= esc($title) ?></div>
            <div class="card-sub"><?= count($daftar) ?> pengajuan</div>
        </div>
    </div>

    <?php if (empty($daftar)): ?>
        <div class="empty-state">
            <div class="empty-icon">📭</div>
            <div class="empty-title">Tidak ada pengajuan</div>
            <div class="empty-sub">Tidak ada data untuk kategori ini.</div>
        </div>
    <?php else: ?>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Mahasiswa</th>
                        <th>NIM</th>
                        <th>Jenis Surat</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($daftar as $i => $p): ?>
                    <tr>
                        <td style="color:var(--muted)"><?= $i + 1 ?></td>
                        <td style="font-weight:500"><?= esc($p['mahasiswa_nama']) ?></td>
                        <td style="color:var(--muted)"><?= esc($p['mahasiswa_nim']) ?></td>
                        <td><?= esc(str_replace('_',' ',$p['jenis_surat'])) ?></td>
                        <td style="color:var(--muted);white-space:nowrap"><?= date('d M Y', strtotime($p['created_at'])) ?></td>
                        <td><?= statusBadge($p['status']) ?></td>
                        <td>
                            <a href="/dashboard/staf-tu/detail/<?= $p['id'] ?>" class="btn btn-outline btn-sm">
                                <?= $tab === 'menunggu' ? 'Proses' : 'Detail' ?>
                            </a>
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
