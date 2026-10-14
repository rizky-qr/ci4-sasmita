<?php
/**
 * v_profile.php — Halaman Profil Pengguna SASMITA
 * Tampilan modern, responsif, dan interaktif untuk kelola data akun.
 */

$title = 'Profil Saya';

$roleColors = [
    'MAHASISWA'     => ['bg' => 'rgba(99,102,241,.15)',  'color' => '#818cf8', 'border' => 'rgba(99,102,241,.3)',  'icon' => '🎓', 'label' => 'Mahasiswa'],
    'STAF_TU_PRODI' => ['bg' => 'rgba(251,191,36,.15)',  'color' => '#fbbf24', 'border' => 'rgba(251,191,36,.3)',  'icon' => '🗂️', 'label' => 'Staf TU Prodi'],
    'KAPRODI'       => ['bg' => 'rgba(52,211,153,.15)',  'color' => '#34d399', 'border' => 'rgba(52,211,153,.3)',  'icon' => '👨‍🏫', 'label' => 'Ketua Program Studi'],
    'DEKAN'         => ['bg' => 'rgba(96,165,250,.15)',  'color' => '#60a5fa', 'border' => 'rgba(96,165,250,.3)',  'icon' => '🏛️', 'label' => 'Dekan Fakultas'],
    'ADMIN'         => ['bg' => 'rgba(248,113,113,.15)',  'color' => '#f87171', 'border' => 'rgba(248,113,113,.3)',  'icon' => '⚙️', 'label' => 'Administrator'],
];

$roleMeta = $roleColors[$user['role']] ?? [
    'bg' => 'rgba(255,255,255,.1)', 'color' => '#e2e8f0', 'border' => 'var(--border)', 'icon' => '👤', 'label' => $user['role']
];

$programList = [
    'PENDIDIKAN_MASYARAKAT'                 => 'Pendidikan Masyarakat',
    'PENDIDIKAN_BAHASA_INGGRIS'             => 'Pendidikan Bahasa Inggris',
    'TEKNOLOGI_PENDIDIKAN'                  => 'Teknologi Pendidikan',
    'PENDIDIKAN_VOKASIONAL_DESAIN_FASHION'  => 'Pendidikan Vokasional Desain Fashion',
    'PENDIDIKAN_MATEMATIKA'                 => 'Pendidikan Matematika',
    'PENDIDIKAN_KEPELATIHAN_OLAHRAGA'       => 'Pendidikan Kepelatihan Olahraga',
    'PENDIDIKAN_PROFESI_GURU'               => 'Pendidikan Profesi Guru',
];

$namaProgram = $programList[$user['program']] ?? ($user['program'] ? str_replace('_', ' ', $user['program']) : 'Fakultas Keguruan & Ilmu Pendidikan');

ob_start();
?>

<div style="max-width: 980px; margin: 0 auto; display: flex; flex-direction: column; gap: 1.5rem;">

    <!-- ═══ Profile Hero Header ═══ -->
    <div class="card" style="position: relative; overflow: hidden; padding: 2rem; background: linear-gradient(135deg, #144218 0%, #1b5e20 100%); border: 1px solid rgba(255,255,255,.15); box-shadow: 0 10px 30px rgba(20,66,24,.25);">
        <div style="position: absolute; right: -40px; top: -40px; width: 180px; height: 180px; background: radial-gradient(circle, rgba(249, 168, 37, 0.25) 0%, transparent 70%); border-radius: 50%; pointer-events: none;"></div>
        
        <div style="display: flex; align-items: center; gap: 1.5rem; flex-wrap: wrap;">
            
            <!-- Avatar Frame -->
            <div style="position: relative; flex-shrink: 0;">
                <?php if (!empty($user['foto'])): ?>
                    <img id="avatar-img-preview" src="/<?= esc($user['foto']) ?>" alt="<?= esc($user['nama']) ?>" 
                         style="width: 88px; height: 88px; border-radius: 50%; object-fit: cover; border: 3px solid #f9a825; box-shadow: 0 4px 14px rgba(0,0,0,.3);">
                <?php else: ?>
                    <div id="avatar-placeholder" style="width: 88px; height: 88px; border-radius: 50%; background: linear-gradient(135deg, #f9a825 0%, #f57f17 100%); border: 3px solid #ffffff; display: flex; align-items: center; justify-content: center; font-size: 2.2rem; font-weight: 800; color: #144218; box-shadow: 0 4px 14px rgba(0,0,0,.3);">
                        <?= strtoupper(substr($user['nama'], 0, 1)) ?>
                    </div>
                <?php endif; ?>
                <span style="position: absolute; bottom: 0; right: 0; background: #ffffff; border: 2px solid #144218; border-radius: 50%; padding: 3px; font-size: 1rem;" title="<?= $roleMeta['label'] ?>">
                    <?= $roleMeta['icon'] ?>
                </span>
            </div>

            <!-- Profile Details -->
            <div style="flex: 1; min-width: 240px;">
                <div style="display: flex; align-items: center; gap: .75rem; flex-wrap: wrap;">
                    <h2 style="font-size: 1.4rem; font-weight: 700; color: #fff; letter-spacing: -.3px;">
                        <?= esc($user['nama']) ?>
                    </h2>
                    <span style="display: inline-flex; align-items: center; gap: .35rem; font-size: .75rem; font-weight: 600; padding: .25rem .75rem; border-radius: 20px; background: <?= $roleMeta['bg'] ?>; color: <?= $roleMeta['color'] ?>; border: 1px solid <?= $roleMeta['border'] ?>;">
                        <?= $roleMeta['icon'] ?> <?= $roleMeta['label'] ?>
                    </span>
                    <span style="display: inline-flex; align-items: center; gap: .25rem; font-size: .72rem; color: var(--success); background: rgba(52,211,153,.1); padding: .2rem .6rem; border-radius: 12px; border: 1px solid rgba(52,211,153,.25);">
                        ● Aktif
                    </span>
                </div>

                <div style="display: flex; align-items: center; gap: 1.25rem; margin-top: .6rem; color: var(--muted); font-size: .83rem; flex-wrap: wrap;">
                    <span style="display: flex; align-items: center; gap: .35rem;">
                        ✉️ <?= esc($user['email']) ?>
                    </span>

                    <?php if (!empty($user['nim'])): ?>
                        <span style="display: flex; align-items: center; gap: .35rem;">
                            🎓 NIM: <strong style="color: var(--text);"><?= esc($user['nim']) ?></strong>
                        </span>
                    <?php endif; ?>

                    <?php if (!empty($user['nip'])): ?>
                        <span style="display: flex; align-items: center; gap: .35rem;">
                            👔 NIP: <strong style="color: var(--text);"><?= esc($user['nip']) ?></strong>
                        </span>
                    <?php endif; ?>

                    <span style="display: flex; align-items: center; gap: .35rem;">
                        🏫 <?= esc($namaProgram) ?>
                    </span>
                </div>
            </div>

            <!-- Quick Action / Info Box -->
            <div style="text-align: right; background: rgba(255,255,255,.03); border: 1px solid var(--border); border-radius: 10px; padding: .75rem 1.25rem; font-size: .78rem;">
                <div style="color: var(--muted); margin-bottom: 2px;">Terdaftar Sejak</div>
                <div style="font-weight: 600; color: var(--text);"><?= date('d F Y', strtotime($user['created_at'] ?? 'now')) ?></div>
            </div>

        </div>
    </div>

    <!-- ═══ Profile Edit Form ═══ -->
    <form action="/user/update/<?= esc($user['id']) ?>" method="post" enctype="multipart/form-data" id="profile-form">
        <?= csrf_field() ?>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1.5rem;">

            <!-- Column 1: Data Diri & Kontak -->
            <div class="card" style="display: flex; flex-direction: column; gap: 1.25rem;">
                <div class="card-header" style="margin-bottom: 0; border-bottom: 1px solid var(--border); padding-bottom: .85rem;">
                    <div>
                        <div class="card-title">👤 Informasi Pribadi</div>
                        <div class="card-sub">Perbarui informasi identitas & kontak Anda</div>
                    </div>
                </div>

                <!-- Foto Upload with Live Preview -->
                <div class="form-group">
                    <label for="foto">Foto Profil Baru (Opsional)</label>
                    <div style="display: flex; align-items: center; gap: 1rem; margin-top: .25rem;">
                        <input type="file" id="foto" name="foto" accept="image/png, image/jpeg, image/webp" 
                               style="font-size: .8rem; padding: .5rem; background: var(--bg); border: 1px dashed var(--border); border-radius: 8px; flex: 1;"
                               onchange="previewProfileImage(event)">
                    </div>
                    <span class="form-hint">Format yang didukung: JPG, PNG, WEBP (Maksimal 2MB).</span>
                </div>

                <div class="form-group">
                    <label for="nama">Nama Lengkap <span style="color: var(--danger)">*</span></label>
                    <input type="text" id="nama" name="nama" value="<?= esc(old('nama', $user['nama'])) ?>" required placeholder="Nama Lengkap beserta gelar">
                </div>

                <div class="form-group">
                    <label for="email">Email Utama (Terdaftar)</label>
                    <input type="email" id="email" value="<?= esc($user['email']) ?>" disabled 
                           style="background: rgba(255,255,255,.03); cursor: not-allowed; color: var(--muted); border-style: dashed;">
                    <span class="form-hint">Email akun dikelola oleh sistem dan tidak dapat diubah sembarangan.</span>
                </div>

                <div class="form-group">
                    <label for="no_hp">Nomor Telepon / WhatsApp</label>
                    <input type="text" id="no_hp" name="no_hp" value="<?= esc(old('no_hp', $user['no_hp'] ?? '')) ?>" placeholder="Contoh: 081234567890">
                    <span class="form-hint">Digunakan untuk notifikasi surat dan konfirmasi cepat.</span>
                </div>

                <?php if ($user['role'] === 'MAHASISWA'): ?>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: .75rem;">
                        <div class="form-group">
                            <label for="angkatan">Tahun Angkatan</label>
                            <input type="number" id="angkatan" name="angkatan" value="<?= esc(old('angkatan', $user['angkatan'] ?? '')) ?>" placeholder="2021" min="2010" max="<?= date('Y') ?>">
                        </div>
                        <div class="form-group">
                            <label for="semester">Semester Saat Ini</label>
                            <input type="number" id="semester" name="semester" value="<?= esc(old('semester', $user['semester'] ?? '')) ?>" placeholder="7" min="1" max="14">
                        </div>
                    </div>
                <?php endif; ?>

                <div style="margin-top: auto; padding-top: .5rem;">
                    <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; padding: .75rem;">
                        💾 Simpan Perubahan Profil
                    </button>
                </div>
            </div>

            <!-- Column 2: Data Akademik & Keamanan Password -->
            <div style="display: flex; flex-direction: column; gap: 1.5rem;">

                <!-- Card Identitas Akademik (Read-only verified) -->
                <div class="card">
                    <div class="card-header" style="margin-bottom: 0; border-bottom: 1px solid var(--border); padding-bottom: .85rem;">
                        <div>
                            <div class="card-title">🎓 Data Identitas & Unit</div>
                            <div class="card-sub">Data resmi yang tercatat di pangkalan data kampus</div>
                        </div>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: .75rem; margin-top: 1rem;">
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: .65rem .85rem; background: var(--bg); border: 1px solid var(--border); border-radius: 8px;">
                            <span style="font-size: .8rem; color: var(--muted);">Peran / Role</span>
                            <span style="font-size: .8rem; font-weight: 700; color: <?= $roleMeta['color'] ?>;"><?= esc($user['role']) ?></span>
                        </div>

                        <?php if (!empty($user['nim'])): ?>
                            <div style="display: flex; justify-content: space-between; align-items: center; padding: .65rem .85rem; background: var(--bg); border: 1px solid var(--border); border-radius: 8px;">
                                <span style="font-size: .8rem; color: var(--muted);">Nomor Induk Mahasiswa (NIM)</span>
                                <span style="font-size: .85rem; font-weight: 700; font-family: monospace; color: var(--text);"><?= esc($user['nim']) ?></span>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($user['nip'])): ?>
                            <div style="display: flex; justify-content: space-between; align-items: center; padding: .65rem .85rem; background: var(--bg); border: 1px solid var(--border); border-radius: 8px;">
                                <span style="font-size: .8rem; color: var(--muted);">Nomor Induk Pegawai (NIP)</span>
                                <span style="font-size: .85rem; font-weight: 700; font-family: monospace; color: var(--text);"><?= esc($user['nip']) ?></span>
                            </div>
                        <?php endif; ?>

                        <div style="display: flex; justify-content: space-between; align-items: center; padding: .65rem .85rem; background: var(--bg); border: 1px solid var(--border); border-radius: 8px;">
                            <span style="font-size: .8rem; color: var(--muted);">Program Studi / Fakultas</span>
                            <span style="font-size: .8rem; font-weight: 600; text-align: right; color: var(--text);"><?= esc($namaProgram) ?></span>
                        </div>
                    </div>
                </div>

                <!-- Card Keamanan Password -->
                <div class="card">
                    <div class="card-header" style="margin-bottom: 0; border-bottom: 1px solid var(--border); padding-bottom: .85rem;">
                        <div>
                            <div class="card-title">🔒 Keamanan Akun</div>
                            <div class="card-sub">Kosongkan jika tidak ingin mengganti password</div>
                        </div>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 1rem; margin-top: 1rem;">
                        <div class="form-group">
                            <label for="password">Password Baru</label>
                            <input type="password" id="password" name="password" placeholder="Minimal 6 karakter" autocomplete="new-password">
                        </div>

                        <div class="form-group">
                            <label for="password_confirm">Konfirmasi Password Baru</label>
                            <input type="password" id="password_confirm" placeholder="Ulangi password baru" autocomplete="new-password" oninput="checkPasswordMatch()">
                            <span id="pw-hint" class="form-hint">Pastikan kedua password sama sebelum menyimpan.</span>
                        </div>

                        <div style="background: rgba(99,102,241,.06); border: 1px solid rgba(99,102,241,.2); border-radius: 8px; padding: .75rem; font-size: .75rem; color: var(--muted); line-height: 1.4;">
                            💡 <strong>Tips Keamanan:</strong> Gunakan kombinasi huruf, angka, dan simbol untuk password yang kuat dan aman.
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </form>

</div>

<script>
function previewProfileImage(event) {
    const input = event.target;
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            let img = document.getElementById('avatar-img-preview');
            const placeholder = document.getElementById('avatar-placeholder');
            if (!img && placeholder) {
                img = document.createElement('img');
                img.id = 'avatar-img-preview';
                img.style = 'width: 88px; height: 88px; border-radius: 50%; object-fit: cover; border: 3px solid <?= $roleMeta['color'] ?>; box-shadow: 0 4px 14px rgba(0,0,0,.4);';
                placeholder.parentNode.insertBefore(img, placeholder);
                placeholder.style.display = 'none';
            }
            if (img) {
                img.src = e.target.result;
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function checkPasswordMatch() {
    const p1 = document.getElementById('password').value;
    const p2 = document.getElementById('password_confirm').value;
    const hint = document.getElementById('pw-hint');
    if (!p2) {
        hint.textContent = 'Pastikan kedua password sama sebelum menyimpan.';
        hint.style.color = 'var(--muted)';
        return;
    }
    if (p1 === p2) {
        hint.textContent = '✅ Password konfirmasi sesuai.';
        hint.style.color = 'var(--success)';
    } else {
        hint.textContent = '⚠️ Password konfirmasi tidak sama.';
        hint.style.color = 'var(--danger)';
    }
}

document.getElementById('profile-form').addEventListener('submit', function(e) {
    const p1 = document.getElementById('password').value;
    const p2 = document.getElementById('password_confirm').value;
    if (p1 && p1 !== p2) {
        e.preventDefault();
        alert('Password baru dan konfirmasi password tidak cocok! Silakan periksa kembali.');
    }
});
</script>

<?php
$pageContent = ob_get_clean();
require APPPATH . 'Views/layouts/main.php';
?>
