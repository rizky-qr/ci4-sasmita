<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Akun Mahasiswa — SASMITA FKIP UIKA</title>
    <meta name="description" content="Registrasi akun mahasiswa resmi Sistem Administrasi Surat Mahasiswa FKIP UIKA Bogor">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg-hero: linear-gradient(135deg, #144218 0%, #0d2810 100%);
            --accent-gold: linear-gradient(135deg, #f9a825 0%, #f57f17 100%);
            --primary-dark: #144218;
            --glass-bg: rgba(255, 255, 255, 0.08);
            --glass-border: rgba(255, 255, 255, 0.16);
            --text-light: #ffffff;
            --text-muted: rgba(255, 255, 255, 0.65);
        }

        body {
            font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, sans-serif;
            background: var(--bg-hero);
            color: var(--text-light);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2.5rem 1rem;
            position: relative;
            overflow-x: hidden;
        }

        .glow-circle-1 {
            position: absolute;
            top: -120px; left: -100px;
            width: 400px; height: 400px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(249, 168, 37, 0.18) 0%, transparent 70%);
            pointer-events: none;
        }

        .glow-circle-2 {
            position: absolute;
            bottom: -150px; right: -100px;
            width: 480px; height: 480px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(46, 125, 50, 0.28) 0%, transparent 70%);
            pointer-events: none;
        }

        .register-container {
            width: 100%;
            max-width: 580px;
            position: relative;
            z-index: 10;
            animation: fadeIn .4s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .header-brand {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .brand-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 68px; height: 68px;
            border-radius: 18px;
            background: var(--accent-gold);
            font-size: 2rem;
            box-shadow: 0 10px 25px rgba(249, 168, 37, 0.35);
            margin-bottom: .75rem;
        }

        .header-brand h1 {
            font-size: 1.75rem;
            font-weight: 800;
            letter-spacing: -.5px;
            color: #ffffff;
        }

        .header-brand .subtitle {
            font-size: .84rem;
            color: rgba(255, 255, 255, 0.85);
            font-weight: 500;
            margin-top: .25rem;
        }

        .glass-card {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border-radius: 20px;
            padding: 2rem 2.25rem;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.45);
        }

        .card-top {
            display: flex;
            align-items: center;
            gap: .65rem;
            margin-bottom: 1.35rem;
        }

        .card-top-icon { font-size: 1.35rem; color: #f9a825; }

        .card-top-text h2 {
            font-size: 1.1rem;
            font-weight: 700;
            color: #ffffff;
            line-height: 1.2;
        }

        .card-top-text p {
            font-size: .74rem;
            color: rgba(255, 255, 255, 0.6);
            margin-top: 2px;
        }

        .alert {
            padding: .75rem 1rem;
            border-radius: 10px;
            font-size: .82rem;
            margin-bottom: 1.25rem;
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.16);
            border: 1px solid rgba(239, 68, 68, 0.35);
            color: #fca5a5;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .form-group {
            margin-bottom: 1.1rem;
        }

        .form-group.full {
            grid-column: 1 / -1;
        }

        label {
            display: block;
            font-size: .8rem;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.85);
            margin-bottom: .35rem;
        }

        label span.req { color: #f87171; }

        input[type="text"], input[type="email"], input[type="password"], input[type="number"], select {
            width: 100%;
            padding: .7rem .95rem;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 10px;
            color: #ffffff;
            font-size: .88rem;
            font-family: inherit;
            outline: none;
            transition: border-color .15s, background .15s, box-shadow .15s;
        }

        select option {
            background: #144218;
            color: #ffffff;
        }

        input::placeholder {
            color: rgba(255, 255, 255, 0.35);
        }

        input:focus, select:focus {
            border-color: #f9a825;
            background: rgba(255, 255, 255, 0.12);
            box-shadow: 0 0 0 3px rgba(249, 168, 37, 0.25);
        }

        .field-hint {
            font-size: .68rem;
            color: rgba(255, 255, 255, 0.5);
            margin-top: 3px;
            display: block;
        }

        .btn-submit {
            width: 100%;
            padding: .85rem;
            background: var(--accent-gold);
            color: var(--primary-dark);
            border: none;
            border-radius: 10px;
            font-size: .95rem;
            font-weight: 700;
            font-family: inherit;
            cursor: pointer;
            box-shadow: 0 6px 20px rgba(249, 168, 37, 0.35);
            transition: transform .15s, box-shadow .15s;
            margin-top: .75rem;
        }

        .btn-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(249, 168, 37, 0.45);
        }

        .security-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .5rem;
            font-size: .72rem;
            color: rgba(255, 255, 255, 0.6);
            margin-top: 1.25rem;
            padding-top: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .footer-link {
            text-align: center;
            margin-top: 1.25rem;
            font-size: .82rem;
            color: rgba(255, 255, 255, 0.7);
        }

        .footer-link a {
            color: #fbbf24;
            font-weight: 600;
            text-decoration: none;
        }

        .footer-link a:hover {
            text-decoration: underline;
        }

        @media (max-width: 600px) {
            .form-grid { grid-template-columns: 1fr; }
            .form-group.full { grid-column: auto; }
            .glass-card { padding: 1.5rem; }
        }
    </style>
</head>
<body>

<div class="glow-circle-1"></div>
<div class="glow-circle-2"></div>

<div class="register-container">
    <div class="header-brand">
        <div class="brand-badge">🎓</div>
        <h1>SASMITA</h1>
        <p class="subtitle">Registrasi Akun Mahasiswa FKIP UIKA</p>
    </div>

    <div class="glass-card">
        <div class="card-top">
            <span class="card-top-icon">📝</span>
            <div class="card-top-text">
                <h2>Pendaftaran Mahasiswa Baru</h2>
                <p>Isi data akademik resmi Anda dengan benar</p>
            </div>
        </div>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-error">❌ <?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif ?>
        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-error">
                <?php foreach (session()->getFlashdata('errors') as $err): ?>
                    ❌ <?= esc($err) ?><br>
                <?php endforeach ?>
            </div>
        <?php endif ?>

        <form action="/auth/register" method="post" id="regForm">
            <?= csrf_field() ?>

            <!-- Honeypot anti-spam bot (hidden from human) -->
            <input type="text" name="website" style="display:none !important;" tabindex="-1" autocomplete="off">

            <div class="form-grid">
                <div class="form-group full">
                    <label for="nama">Nama Lengkap <span class="req">*</span></label>
                    <input type="text" id="nama" name="nama" placeholder="Contoh: Ahmad Fauzan Ramadhan" value="<?= esc(old('nama')) ?>" required autofocus>
                    <span class="field-hint">Sesuai nama yang tercatat pada SIAKAD / Ijazah</span>
                </div>

                <div class="form-group">
                    <label for="nim">NIM Mahasiswa <span class="req">*</span></label>
                    <input type="text" id="nim" name="nim" placeholder="Contoh: 2021010001" value="<?= esc(old('nim')) ?>" required>
                    <span class="field-hint">Hanya angka (tanpa spasi/titik)</span>
                </div>

                <div class="form-group">
                    <label for="angkatan">Tahun Angkatan <span class="req">*</span></label>
                    <input type="number" id="angkatan" name="angkatan" placeholder="2023" min="2015" max="<?= date('Y') ?>" value="<?= esc(old('angkatan') ?: date('Y')) ?>" required>
                </div>

                <div class="form-group full">
                    <label for="program">Program Studi <span class="req">*</span></label>
                    <select id="program" name="program" required>
                        <option value="">— Pilih Program Studi —</option>
                        <option value="PENDIDIKAN_BAHASA_INGGRIS" <?= old('program')==='PENDIDIKAN_BAHASA_INGGRIS'?'selected':'' ?>>Pendidikan Bahasa Inggris</option>
                        <option value="TEKNOLOGI_PENDIDIKAN" <?= old('program')==='TEKNOLOGI_PENDIDIKAN'?'selected':'' ?>>Teknologi Pendidikan</option>
                        <option value="PENDIDIKAN_MASYARAKAT" <?= old('program')==='PENDIDIKAN_MASYARAKAT'?'selected':'' ?>>Pendidikan Masyarakat</option>
                        <option value="PENDIDIKAN_MATEMATIKA" <?= old('program')==='PENDIDIKAN_MATEMATIKA'?'selected':'' ?>>Pendidikan Matematika</option>
                        <option value="PENDIDIKAN_KEPELATIHAN_OLAHRAGA" <?= old('program')==='PENDIDIKAN_KEPELATIHAN_OLAHRAGA'?'selected':'' ?>>Pendidikan Kepelatihan Olahraga</option>
                        <option value="PENDIDIKAN_VOKASIONAL_DESAIN_FASHION" <?= old('program')==='PENDIDIKAN_VOKASIONAL_DESAIN_FASHION'?'selected':'' ?>>Pendidikan Vokasional Desain Fashion</option>
                        <option value="PENDIDIKAN_PROFESI_GURU" <?= old('program')==='PENDIDIKAN_PROFESI_GURU'?'selected':'' ?>>Pendidikan Profesi Guru (PPG)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="email">Email Mahasiswa <span class="req">*</span></label>
                    <input type="email" id="email" name="email" placeholder="nama@student.uika-bogor.ac.id" value="<?= esc(old('email')) ?>" required>
                    <span class="field-hint">Gunakan email aktif institusi / pribadi</span>
                </div>

                <div class="form-group">
                    <label for="no_hp">No. WhatsApp / HP</label>
                    <input type="text" id="no_hp" name="no_hp" placeholder="081234567890" value="<?= esc(old('no_hp')) ?>">
                    <span class="field-hint">Untuk info surat selesai</span>
                </div>

                <div class="form-group">
                    <label for="password">Password <span class="req">*</span></label>
                    <input type="password" id="password" name="password" placeholder="Minimal 8 karakter" required>
                    <span class="field-hint">Kombinasi huruf besar, kecil & angka</span>
                </div>

                <div class="form-group">
                    <label for="password_confirm">Konfirmasi Password <span class="req">*</span></label>
                    <input type="password" id="password_confirm" name="password_confirm" placeholder="Ulangi password" required>
                </div>
            </div>

            <button type="submit" class="btn-submit" id="btnRegister">
                🛡️ Daftar Akun Mahasiswa
            </button>
        </form>

        <div class="security-badge">
            <span>🔒</span> Dilindungi Enkripsi Bcrypt & Proteksi Anti-Bot Kampus
        </div>

        <div class="footer-link">
            Sudah memiliki akun? <a href="/login">Masuk ke Sistem</a>
        </div>
    </div>
</div>

<script>
document.getElementById('regForm').addEventListener('submit', function() {
    const btn = document.getElementById('btnRegister');
    btn.disabled = true;
    btn.textContent = '⏳ Memproses Pendaftaran...';
});
</script>

</body>
</html>
