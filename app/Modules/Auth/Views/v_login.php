<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — SASMITA FKIP UIKA Bogor</title>
    <meta name="description" content="Sistem Administrasi Surat Mahasiswa Terintegrasi — FKIP UIKA Bogor">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg-hero: linear-gradient(135deg, #144218 0%, #0d2810 100%);
            --accent-gold: linear-gradient(135deg, #f9a825 0%, #f57f17 100%);
            --primary-dark: #144218;
            --glass-bg: rgba(255, 255, 255, 0.07);
            --glass-border: rgba(255, 255, 255, 0.14);
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
            padding: 2rem 1rem;
            position: relative;
            overflow-x: hidden;
        }

        /* Decorative glowing circles */
        .glow-circle-1 {
            position: absolute;
            top: -100px; left: -100px;
            width: 360px; height: 360px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(249, 168, 37, 0.18) 0%, transparent 70%);
            pointer-events: none;
        }

        .glow-circle-2 {
            position: absolute;
            bottom: -120px; right: -80px;
            width: 440px; height: 440px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(46, 125, 50, 0.25) 0%, transparent 70%);
            pointer-events: none;
        }

        .login-container {
            width: 100%;
            max-width: 440px;
            position: relative;
            z-index: 10;
            animation: fadeIn .4s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* Header / Logo */
        .header-brand {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .brand-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 76px; height: 76px;
            border-radius: 20px;
            background: var(--accent-gold);
            font-size: 2.2rem;
            box-shadow: 0 10px 25px rgba(249, 168, 37, 0.35);
            margin-bottom: .85rem;
        }

        .header-brand h1 {
            font-size: 1.85rem;
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

        .header-brand .sub-fac {
            font-size: .72rem;
            color: rgba(255, 255, 255, 0.5);
            margin-top: 2px;
        }

        /* Glass Card */
        .glass-card {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
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

        .card-top-icon {
            font-size: 1.25rem;
            color: #f9a825;
        }

        .card-top-text h2 {
            font-size: 1.05rem;
            font-weight: 700;
            color: #ffffff;
            line-height: 1.2;
        }

        .card-top-text p {
            font-size: .72rem;
            color: rgba(255, 255, 255, 0.55);
            margin-top: 2px;
        }

        /* Flash alert */
        .alert {
            padding: .75rem 1rem;
            border-radius: 10px;
            font-size: .82rem;
            margin-bottom: 1.2rem;
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.35);
            color: #fca5a5;
        }

        .alert-success {
            background: rgba(34, 197, 94, 0.15);
            border: 1px solid rgba(34, 197, 94, 0.35);
            color: #86efac;
        }

        /* Form elements */
        .form-group {
            margin-bottom: 1.15rem;
        }

        label {
            display: block;
            font-size: .8rem;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.85);
            margin-bottom: .4rem;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            font-size: .95rem;
            color: rgba(255, 255, 255, 0.4);
            pointer-events: none;
        }

        input[type="email"], input[type="password"], input[type="text"] {
            width: 100%;
            padding: .75rem 1rem .75rem 2.4rem;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 10px;
            color: #ffffff;
            font-size: .9rem;
            font-family: inherit;
            outline: none;
            transition: border-color .15s, background .15s, box-shadow .15s;
        }

        input::placeholder {
            color: rgba(255, 255, 255, 0.35);
        }

        input:focus {
            border-color: #f9a825;
            background: rgba(255, 255, 255, 0.12);
            box-shadow: 0 0 0 3px rgba(249, 168, 37, 0.25);
        }

        .toggle-pw {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.45);
            font-size: .95rem;
            cursor: pointer;
            padding: 0;
            display: flex;
            align-items: center;
        }
        .toggle-pw:hover { color: #ffffff; }

        .btn-submit {
            width: 100%;
            padding: .8rem;
            background: var(--accent-gold);
            color: var(--primary-dark);
            border: none;
            border-radius: 10px;
            font-size: .92rem;
            font-weight: 700;
            font-family: inherit;
            cursor: pointer;
            box-shadow: 0 6px 20px rgba(249, 168, 37, 0.35);
            transition: transform .15s, box-shadow .15s;
            margin-top: .4rem;
        }

        .btn-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(249, 168, 37, 0.45);
        }

        .btn-submit:active {
            transform: translateY(1px);
        }

        /* Divider */
        .divider {
            position: relative;
            text-align: center;
            margin: 1.4rem 0 1.15rem;
        }

        .divider::before {
            content: '';
            position: absolute;
            left: 0; right: 0; top: 50%;
            height: 1px;
            background: rgba(255, 255, 255, 0.12);
        }

        .divider span {
            position: relative;
            background: #103314;
            padding: 0 .75rem;
            font-size: .7rem;
            color: rgba(255, 255, 255, 0.5);
            border-radius: 4px;
        }

        .divider strong {
            color: #fbbf24;
        }

        /* Demo role pills */
        .demo-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 6px;
        }

        .demo-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            padding: .55rem .25rem;
            border-radius: 9px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.14);
            color: rgba(255, 255, 255, 0.75);
            font-size: .68rem;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            transition: all .15s;
            text-align: center;
            line-height: 1.1;
        }

        .demo-btn:hover {
            background: rgba(255, 255, 255, 0.12);
            border-color: rgba(255, 255, 255, 0.3);
            color: #ffffff;
            transform: translateY(-1px);
        }

        .demo-btn.active {
            background: rgba(249, 168, 37, 0.2);
            border-color: #f9a825;
            color: #fbbf24;
        }

        .demo-badge {
            width: 18px; height: 18px;
            border-radius: 50%;
            border: 1px solid rgba(255, 255, 255, 0.35);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .6rem;
            font-weight: 800;
        }

        .demo-hint {
            text-align: center;
            font-size: .68rem;
            color: rgba(255, 255, 255, 0.38);
            margin-top: .6rem;
        }

        .footer-credit {
            text-align: center;
            font-size: .7rem;
            color: rgba(255, 255, 255, 0.4);
            margin-top: 1.5rem;
        }
    </style>
</head>
<body>

<div class="glow-circle-1"></div>
<div class="glow-circle-2"></div>

<div class="login-container">
    <!-- Header -->
    <div class="header-brand">
        <div class="brand-badge">🎓</div>
        <h1>SASMITA</h1>
        <p class="subtitle">Sistem Administrasi Surat Mahasiswa Terintegrasi</p>
        <p class="sub-fac">FKIP Universitas Ibn Khaldun Bogor</p>
    </div>

    <!-- Login Card -->
    <div class="glass-card">
        <div class="card-top">
            <span class="card-top-icon">📖</span>
            <div class="card-top-text">
                <h2>Masuk ke Sistem</h2>
                <p>Gunakan email institusi Anda</p>
            </div>
        </div>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-error">❌ <?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif ?>
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">✅ <?= esc(session()->getFlashdata('success')) ?></div>
        <?php endif ?>

        <form action="/auth/login" method="post" id="loginForm">
            <?= csrf_field() ?>

            <div class="form-group">
                <label for="email">Email Institusi</label>
                <div class="input-wrapper">
                    <span class="input-icon">✉️</span>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="email@uika-bogor.ac.id"
                        value="<?= esc(old('email')) ?>"
                        required
                        autofocus
                    >
                </div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-wrapper">
                    <span class="input-icon">🔒</span>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Masukkan password"
                        required
                    >
                    <button type="button" class="toggle-pw" onclick="togglePw()" title="Lihat password">👁️</button>
                </div>
            </div>

            <button type="submit" class="btn-submit" id="submitBtn">
                Masuk
            </button>
        </form>

        <!-- Divider Demo -->
        <div class="divider">
            <span>Demo · Password: <strong>demo123</strong></span>
        </div>

        <!-- Demo Quick Select -->
        <div class="demo-grid">
            <button type="button" class="demo-btn" onclick="fillDemo('ahmad.fauzan@student.uika-bogor.ac.id', 'demo123', this)">
                <span class="demo-badge">M</span>
                <span>Mhs</span>
            </button>
            <button type="button" class="demo-btn" onclick="fillDemo('dewi.rahayu@uika-bogor.ac.id', 'demo123', this)">
                <span class="demo-badge">T</span>
                <span>TU</span>
            </button>
            <button type="button" class="demo-btn" onclick="fillDemo('hendra.gunawan@uika-bogor.ac.id', 'demo123', this)">
                <span class="demo-badge">K</span>
                <span>Kaprodi</span>
            </button>
            <button type="button" class="demo-btn" onclick="fillDemo('mulyadi.santoso@uika-bogor.ac.id', 'demo123', this)">
                <span class="demo-badge">D</span>
                <span>Dekan</span>
            </button>
            <button type="button" class="demo-btn" onclick="fillDemo('admin@fkip.uika-bogor.ac.id', 'demo123', this)">
                <span class="demo-badge">A</span>
                <span>Admin</span>
            </button>
        </div>
        <p class="demo-hint">Klik tombol role di atas untuk isi otomatis akun testing</p>

        <div style="text-align: center; margin-top: 1.25rem; padding-top: 1rem; border-top: 1px solid rgba(255,255,255,0.1); font-size: .84rem; color: rgba(255,255,255,0.75);">
            Belum memiliki akun mahasiswa? <a href="/register" style="color: #fbbf24; font-weight: 700; text-decoration: none;">Daftar di sini</a>
        </div>
    </div>

    <p class="footer-credit">
        © 2025 FKIP UIKA Bogor · Dikembangkan oleh Tim IT Fakultas
    </p>
</div>

<script>
function togglePw() {
    const input = document.getElementById('password');
    input.type = input.type === 'password' ? 'text' : 'password';
}

function fillDemo(email, pw, btn) {
    document.getElementById('email').value = email;
    document.getElementById('password').value = pw;
    document.querySelectorAll('.demo-btn').forEach(b => b.classList.remove('active'));
    if (btn) btn.classList.add('active');
}
</script>

</body>
</html>
