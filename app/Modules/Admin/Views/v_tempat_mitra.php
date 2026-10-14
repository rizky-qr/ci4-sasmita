<?php
$sidebarNav = '
<span class="nav-section-label">Menu</span>
<a href="/dashboard/admin" class="nav-item"><span class="nav-icon">🏠</span> Dashboard</a>
<a href="/dashboard/admin/users" class="nav-item"><span class="nav-icon">👥</span> Manajemen User</a>
<a href="/dashboard/admin/mitra" class="nav-item active"><span class="nav-icon">🏢</span> Tempat Mitra</a>
<a href="/dashboard/admin/laporan" class="nav-item"><span class="nav-icon">📊</span> Laporan</a>
<span class="nav-section-label">Akun</span>
<a href="/user/profile" class="nav-item"><span class="nav-icon">👤</span> Profil Saya</a>
';
$notifUrl = '/notifikasi';
ob_start();
?>
<div class="card">
    <div class="card-header">
        <div><div class="card-title">Tempat Mitra</div><div class="card-sub"><?= count($mitra) ?> tempat terdaftar</div></div>
        <button class="btn btn-primary btn-sm" onclick="document.getElementById('modal-tambah').style.display='flex'">+ Tambah Mitra</button>
    </div>
    <?php if (empty($mitra)): ?>
        <div class="empty-state"><div class="empty-icon">🏢</div><div class="empty-title">Belum ada tempat mitra</div></div>
    <?php else: ?>
        <div class="table-wrapper"><table>
            <thead><tr><th>#</th><th>Nama</th><th>Kategori</th><th>Kepala</th><th>Telepon</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
            <?php foreach ($mitra as $i => $m): ?>
            <tr>
                <td style="color:var(--muted)"><?= $i+1 ?></td>
                <td style="font-weight:500"><?= esc($m['nama']) ?></td>
                <td><span class="badge badge-draft"><?= esc($m['kategori']) ?></span></td>
                <td style="color:var(--muted)"><?= esc($m['nama_kepala'] ?? '—') ?></td>
                <td style="color:var(--muted)"><?= esc($m['telepon'] ?? '—') ?></td>
                <td><?= $m['is_active'] ? '<span class="badge badge-disetujui">Aktif</span>' : '<span class="badge badge-ditolak">Nonaktif</span>' ?></td>
                <td>
                    <form action="/dashboard/admin/mitra/hapus/<?= $m['id'] ?>" method="post" onsubmit="return confirm('Hapus mitra ini?')">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-danger btn-sm">🗑</button>
                    </form>
                </td>
            </tr>
            <?php endforeach ?>
            </tbody>
        </table></div>
    <?php endif ?>
</div>

<!-- Modal Tambah Mitra -->
<div id="modal-tambah" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.65);z-index:200;align-items:center;justify-content:center">
    <div style="background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);padding:1.75rem;max-width:500px;width:90%">
        <h3 style="font-size:1rem;margin-bottom:1rem">🏢 Tambah Tempat Mitra</h3>
        <form action="/dashboard/admin/mitra/simpan" method="post">
            <?= csrf_field() ?>
            <div class="form-grid" style="margin-bottom:.75rem">
                <div class="form-group full"><label>Nama Tempat *</label><input type="text" name="nama" required></div>
                <div class="form-group full"><label>Alamat *</label><textarea name="alamat" required rows="2"></textarea></div>
                <div class="form-group"><label>Kategori *</label>
                    <select name="kategori" required>
                        <option value="PENELITIAN">Penelitian</option>
                        <option value="OBSERVASI">Observasi</option>
                        <option value="PLP_MAGANG">PLP / Magang</option>
                    </select>
                </div>
                <div class="form-group"><label>Telepon</label><input type="text" name="telepon"></div>
                <div class="form-group full"><label>Nama Kepala</label><input type="text" name="nama_kepala"></div>
            </div>
            <div style="display:flex;gap:.75rem;justify-content:flex-end">
                <button type="button" class="btn btn-outline" onclick="document.getElementById('modal-tambah').style.display='none'">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
<?php $pageContent = ob_get_clean(); require APPPATH . 'Views/layouts/main.php';
