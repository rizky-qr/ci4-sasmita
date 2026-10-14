<?php
// v_form_user.php — Form tambah/edit user (Admin)
$sidebarNav = '
<span class="nav-section-label">Menu</span>
<a href="/dashboard/admin" class="nav-item"><span class="nav-icon">🏠</span> Dashboard</a>
<a href="/dashboard/admin/users" class="nav-item active"><span class="nav-icon">👥</span> Manajemen User</a>
<a href="/dashboard/admin/mitra" class="nav-item"><span class="nav-icon">🏢</span> Tempat Mitra</a>
<a href="/dashboard/admin/laporan" class="nav-item"><span class="nav-icon">📊</span> Laporan</a>
<span class="nav-section-label">Akun</span>
<a href="/user/profile" class="nav-item"><span class="nav-icon">👤</span> Profil Saya</a>
';
$notifUrl   = '/notifikasi';
$isEdit     = $editMode ?? false;
$eu         = $editUser ?? [];
$actionUrl  = $isEdit ? '/dashboard/admin/users/update/' . ($eu['id'] ?? '') : '/dashboard/admin/users/simpan';
ob_start();
?>

<div style="max-width:700px">
    <!-- Breadcrumb -->
    <div style="display:flex;align-items:center;gap:.5rem;font-size:.8rem;color:var(--muted);margin-bottom:1.25rem">
        <a href="/dashboard/admin" style="color:var(--muted);text-decoration:none">Dashboard</a>
        <span>›</span>
        <a href="/dashboard/admin/users" style="color:var(--muted);text-decoration:none">Manajemen User</a>
        <span>›</span>
        <span style="color:var(--text)"><?= esc($title) ?></span>
    </div>

    <div class="card">
        <div class="card-header">
            <div>
                <div class="card-title"><?= esc($title) ?></div>
                <div class="card-sub"><?= $isEdit ? 'Ubah data user yang sudah terdaftar' : 'Buat akun user baru' ?></div>
            </div>
        </div>

        <form action="<?= $actionUrl ?>" method="post">
            <?= csrf_field() ?>

            <!-- Informasi Dasar -->
            <div style="font-size:.72rem;font-weight:600;color:var(--muted);letter-spacing:.08em;text-transform:uppercase;margin-bottom:.75rem">Informasi Dasar</div>
            <div class="form-grid" style="margin-bottom:1.25rem">
                <div class="form-group full">
                    <label>Nama Lengkap <span style="color:var(--danger)">*</span></label>
                    <input type="text" name="nama" value="<?= esc(old('nama', $eu['nama'] ?? '')) ?>" required>
                </div>
                <div class="form-group">
                    <label>Email <span style="color:var(--danger)">*</span></label>
                    <input type="email" name="email" value="<?= esc(old('email', $eu['email'] ?? '')) ?>" required>
                </div>
                <div class="form-group">
                    <label>Password <?= $isEdit ? '(kosongkan jika tidak diubah)' : '<span style="color:var(--danger)">*</span>' ?></label>
                    <input type="password" name="password" <?= $isEdit ? '' : 'required' ?> placeholder="<?= $isEdit ? 'Biarkan kosong jika tidak diubah' : 'Min. 6 karakter' ?>">
                </div>
                <div class="form-group">
                    <label>Role <span style="color:var(--danger)">*</span></label>
                    <select name="role" required>
                        <?php foreach (['MAHASISWA','STAF_TU_PRODI','KAPRODI','DEKAN','ADMIN'] as $r): ?>
                        <option value="<?= $r ?>" <?= (old('role', $eu['role'] ?? '') === $r) ? 'selected' : '' ?>><?= $r ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>NIM (khusus Mahasiswa)</label>
                    <input type="text" name="nim" value="<?= esc(old('nim', $eu['nim'] ?? '')) ?>">
                </div>
                <div class="form-group">
                    <label>NIP (khusus Staf/Dosen)</label>
                    <input type="text" name="nip" value="<?= esc(old('nip', $eu['nip'] ?? '')) ?>">
                </div>
            </div>

            <!-- Info Akademik -->
            <div style="font-size:.72rem;font-weight:600;color:var(--muted);letter-spacing:.08em;text-transform:uppercase;margin-bottom:.75rem">Info Akademik</div>
            <div class="form-grid" style="margin-bottom:1.25rem">
                <div class="form-group full">
                    <label>Program Studi</label>
                    <select name="program">
                        <option value="">— Pilih Program Studi —</option>
                        <?php
                        $programs = ['PENDIDIKAN_MASYARAKAT','PENDIDIKAN_BAHASA_INGGRIS','TEKNOLOGI_PENDIDIKAN','PENDIDIKAN_VOKASIONAL_DESAIN_FASHION','PENDIDIKAN_MATEMATIKA','PENDIDIKAN_KEPELATIHAN_OLAHRAGA','PENDIDIKAN_PROFESI_GURU'];
                        foreach ($programs as $p): ?>
                        <option value="<?= $p ?>" <?= (old('program', $eu['program'] ?? '') === $p) ? 'selected' : '' ?>><?= str_replace('_', ' ', $p) ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Angkatan</label>
                    <input type="text" name="angkatan" value="<?= esc(old('angkatan', $eu['angkatan'] ?? '')) ?>" placeholder="2022">
                </div>
                <div class="form-group">
                    <label>Semester</label>
                    <input type="number" name="semester" value="<?= esc(old('semester', $eu['semester'] ?? '')) ?>" min="1" max="14">
                </div>
                <div class="form-group">
                    <label>No. HP</label>
                    <input type="text" name="no_hp" value="<?= esc(old('no_hp', $eu['no_hp'] ?? '')) ?>">
                </div>
                <?php if ($isEdit): ?>
                <div class="form-group">
                    <label>Status Akun</label>
                    <select name="is_active">
                        <option value="1" <?= ($eu['is_active'] ?? 1) ? 'selected' : '' ?>>Aktif</option>
                        <option value="0" <?= !($eu['is_active'] ?? 1) ? 'selected' : '' ?>>Nonaktif</option>
                    </select>
                </div>
                <?php endif ?>
            </div>

            <div style="display:flex;gap:.75rem;justify-content:flex-end;padding-top:1rem;border-top:1px solid var(--border)">
                <a href="/dashboard/admin/users" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <?= $isEdit ? '💾 Simpan Perubahan' : '➕ Tambah User' ?>
                </button>
            </div>
        </form>
    </div>
</div>

<?php
$pageContent = ob_get_clean();
require APPPATH . 'Views/layouts/main.php';
