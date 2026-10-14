<?php
// v_form_pengajuan.php — Form pengajuan surat (semua 7 jenis)
$sidebarNav = '
<span class="nav-section-label">Menu</span>
<a href="/dashboard/mahasiswa" class="nav-item"><span class="nav-icon">🏠</span> Dashboard</a>
<span class="nav-section-label">Pengajuan Surat</span>
<a href="/dashboard/mahasiswa/pengajuan/aktif" class="nav-item ' . (strpos(uri_string(), 'aktif') !== false ? 'active' : '') . '"><span class="nav-icon">📋</span> Keterangan Aktif</a>
<a href="/dashboard/mahasiswa/pengajuan/tidak-kuliah" class="nav-item ' . (strpos(uri_string(), 'tidak-kuliah') !== false ? 'active' : '') . '"><span class="nav-icon">📋</span> Tidak Ada Kuliah</a>
<a href="/dashboard/mahasiswa/pengajuan/penelitian" class="nav-item ' . (strpos(uri_string(), 'penelitian') !== false ? 'active' : '') . '"><span class="nav-icon">🔬</span> Izin Penelitian</a>
<a href="/dashboard/mahasiswa/pengajuan/plp-magang" class="nav-item ' . (strpos(uri_string(), 'plp-magang') !== false ? 'active' : '') . '"><span class="nav-icon">🏢</span> PLP / Magang</a>
<a href="/dashboard/mahasiswa/pengajuan/observasi" class="nav-item ' . (strpos(uri_string(), 'observasi') !== false ? 'active' : '') . '"><span class="nav-icon">👁️</span> Observasi</a>
<a href="/dashboard/mahasiswa/pengajuan/rekomendasi" class="nav-item ' . (strpos(uri_string(), 'rekomendasi') !== false ? 'active' : '') . '"><span class="nav-icon">⭐</span> Rekomendasi</a>
<a href="/dashboard/mahasiswa/pengajuan/lulus" class="nav-item ' . (strpos(uri_string(), 'lulus') !== false ? 'active' : '') . '"><span class="nav-icon">🎓</span> Keterangan Lulus</a>
<span class="nav-section-label">Riwayat</span>
<a href="/dashboard/mahasiswa/riwayat" class="nav-item"><span class="nav-icon">📂</span> Riwayat Pengajuan</a>
<a href="/dashboard/mahasiswa/notifikasi" class="nav-item"><span class="nav-icon">🔔</span> Notifikasi</a>
<span class="nav-section-label">Akun</span>
<a href="/user/profile" class="nav-item"><span class="nav-icon">👤</span> Profil Saya</a>
';

$notifUrl = '/dashboard/mahasiswa/notifikasi';

ob_start(); ?>

<div style="max-width:680px">
    <!-- Breadcrumb -->
    <div style="display:flex;align-items:center;gap:.5rem;font-size:.8rem;color:var(--muted);margin-bottom:1.25rem">
        <a href="/dashboard/mahasiswa" style="color:var(--muted);text-decoration:none">Dashboard</a>
        <span>›</span>
        <span style="color:var(--text)"><?= esc($title) ?></span>
    </div>

    <div class="card">
        <div class="card-header">
            <div>
                <div class="card-title"><?= esc($title) ?></div>
                <div class="card-sub">Isi formulir di bawah dengan lengkap dan benar</div>
            </div>
        </div>

        <form action="/dashboard/mahasiswa/pengajuan/simpan" method="post" id="form-pengajuan">
            <?= csrf_field() ?>
            <input type="hidden" name="jenis_surat" value="<?= esc($jenis) ?>">

            <!-- Info Mahasiswa (read-only dari session) -->
            <div style="background:var(--surface2);border:1px solid var(--border);border-radius:8px;padding:1rem;margin-bottom:1.25rem">
                <div style="font-size:.78rem;color:var(--muted);margin-bottom:.6rem;font-weight:600">DATA PEMOHON</div>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" value="<?= esc(session('nama')) ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label>NIM</label>
                        <input type="text" value="<?= esc(session('nim') ?? '-') ?>" disabled>
                    </div>
                    <div class="form-group full">
                        <label>Program Studi</label>
                        <input type="text" value="<?= esc(str_replace('_', ' ', session('program') ?? '-')) ?>" disabled>
                    </div>
                </div>
            </div>

            <?php if (!empty($mitra)): ?>
            <!-- Pilih Tempat Mitra (untuk penelitian / PLP / observasi) -->
            <div class="form-group" style="margin-bottom:1rem">
                <label for="mitra_id">Tempat <?= in_array($jenis, ['PERMOHONAN_PLP_MAGANG','PERMOHONAN_OBSERVASI']) ? 'Magang / Observasi' : 'Penelitian' ?></label>
                <select name="mitra_id" id="mitra_id">
                    <option value="">— Pilih Tempat —</option>
                    <?php foreach ($mitra as $m): ?>
                        <option value="<?= esc($m['id']) ?>"><?= esc($m['nama']) ?> — <?= esc($m['alamat']) ?></option>
                    <?php endforeach ?>
                </select>
                <span class="form-hint">Jika tempat tidak ada dalam daftar, hubungi Staf TU.</span>
            </div>
            <?php endif ?>

            <!-- Keperluan -->
            <div class="form-group" style="margin-bottom:1rem">
                <label for="keperluan">Keperluan / Keterangan <span style="color:var(--danger)">*</span></label>
                <textarea name="keperluan" id="keperluan" rows="5" placeholder="Jelaskan keperluan pengajuan surat ini secara singkat dan jelas..." required><?= esc(old('keperluan')) ?></textarea>
                <span class="form-hint">Minimal 10 karakter. Contoh: "Surat ini diperlukan untuk keperluan daftar ulang semester genap 2025."</span>
            </div>

            <div style="display:flex;gap:.75rem;justify-content:flex-end;margin-top:1.5rem">
                <a href="/dashboard/mahasiswa" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary" id="btn-submit">
                    📤 Kirim Pengajuan
                </button>
            </div>
        </form>
    </div>
</div>

<?php
$pageContent = ob_get_clean();
$extraScript = '
<script>
document.getElementById("form-pengajuan").addEventListener("submit", function() {
    document.getElementById("btn-submit").textContent = "⏳ Mengirim...";
    document.getElementById("btn-submit").disabled = true;
});
</script>';
require APPPATH . 'Views/layouts/main.php';
