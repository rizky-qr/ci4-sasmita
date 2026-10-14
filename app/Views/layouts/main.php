<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'SASMITA') ?> — SASMITA</title>
    <meta name="description" content="Sistem Administrasi Surat Mahasiswa FKIP UIKA Bogor">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            /* ══ SASMITA UIKA Bogor Color System (Identik dengan Project Node.js) ══ */
            --bg:           #f6f8f5;
            --surface:      #ffffff;
            --surface2:     #f1f5f0;
            --border:       #e2e8f0;
            --border-subtle:#edf2eb;
            --primary:      #1b5e20;
            --primary-dark: #144218;
            --primary-light:#2e7d32;
            --primary-glow: rgba(27, 94, 32, 0.12);
            --accent:       #f9a825;
            --accent-dark:  #e65100;
            --text:         #1e293b;
            --text-dark:    #0f172a;
            --muted:        #64748b;
            --danger:       #dc2626;
            --success:      #16a34a;
            --warning:      #d97706;
            --info:         #0284c7;
            --sidebar-bg:   #144218;
            --sidebar-border: rgba(255, 255, 255, 0.08);
            --sidebar-w:    256px;
            --header-h:     64px;
            --radius:       12px;
            --shadow-card:  0 2px 8px rgba(0, 0, 0, 0.04), 0 1px 2px rgba(0, 0, 0, 0.02);
            --shadow-md:    0 4px 16px rgba(27, 94, 32, 0.08);
        }

        body {
            font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
        }

        /* ── Sidebar ──────────────────────────────────────────────── */
        .sidebar {
            width: var(--sidebar-w);
            min-height: 100vh;
            background: var(--sidebar-bg);
            border-right: 1px solid var(--sidebar-border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 100;
            transition: transform .3s;
            box-shadow: 2px 0 12px rgba(0, 0, 0, 0.1);
        }

        .sidebar-brand {
            padding: 1.25rem 1.25rem 1.1rem;
            border-bottom: 1px solid var(--sidebar-border);
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: .75rem;
        }

        .brand-icon {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, #f9a825 0%, #e65100 100%);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.25rem; flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(249, 168, 37, 0.35);
        }

        .brand-text h2 { font-size: 1.05rem; font-weight: 800; letter-spacing: -.3px; color: #ffffff; }
        .brand-text p  { font-size: .68rem; color: rgba(255, 255, 255, 0.6); margin-top: 2px; }

        .sidebar-user {
            padding: .75rem .9rem;
            border-top: 1px solid var(--sidebar-border);
            text-decoration: none;
            color: inherit;
            display: block;
            margin: .5rem .75rem;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.05);
            transition: background .15s;
        }
        .sidebar-user:hover { background: rgba(255, 255, 255, 0.1); }

        .user-avatar {
            width: 36px; height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, #f9a825 0%, #f57f17 100%);
            color: #144218;
            display: flex; align-items: center; justify-content: center;
            font-size: .85rem; font-weight: 800;
            flex-shrink: 0;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        }

        .user-info { display: flex; align-items: center; gap: .65rem; }
        .user-name  { font-size: .84rem; font-weight: 600; color: #ffffff; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 145px; }
        .user-role  { font-size: .68rem; color: rgba(255, 255, 255, 0.6); margin-top: 1px; }

        .role-badge {
            font-size: .62rem; font-weight: 700;
            padding: 2px 8px;
            border-radius: 20px;
            background: rgba(249, 168, 37, 0.15);
            color: #fbbf24;
            border: 1px solid rgba(249, 168, 37, 0.3);
            white-space: nowrap;
        }

        .sidebar-nav { flex: 1; padding: .75rem 0; overflow-y: auto; }

        .nav-section-label {
            font-size: .62rem; font-weight: 700;
            color: rgba(255, 255, 255, 0.45);
            letter-spacing: .08em;
            text-transform: uppercase;
            padding: .65rem 1.15rem .3rem;
        }

        .nav-item {
            display: flex; align-items: center; gap: .7rem;
            padding: .55rem .95rem;
            margin: 2px .65rem;
            color: rgba(255, 255, 255, 0.75);
            text-decoration: none;
            font-size: .84rem;
            border-radius: 8px;
            transition: all .15s;
            position: relative;
        }

        .nav-item:hover { background: rgba(255, 255, 255, 0.08); color: #ffffff; }

        .nav-item.active {
            background: rgba(249, 168, 37, 0.18);
            color: #fbbf24;
            font-weight: 600;
        }

        .nav-item.active::before {
            content: '';
            position: absolute;
            left: -2px; top: 6px; bottom: 6px;
            width: 3.5px;
            background: #f9a825;
            border-radius: 0 3px 3px 0;
        }

        .nav-icon { font-size: 1rem; width: 20px; text-align: center; flex-shrink: 0; }

        .nav-badge {
            margin-left: auto;
            background: var(--danger);
            color: #fff;
            font-size: .62rem;
            font-weight: 700;
            min-width: 18px; height: 18px;
            border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            padding: 0 5px;
        }

        /* ── Submenus ─────────────────────────────────────────────── */
        .nav-group-header {
            display: flex; align-items: center; gap: .7rem;
            padding: .55rem .95rem;
            margin: 2px .65rem;
            color: rgba(255, 255, 255, 0.82);
            font-size: .84rem;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            user-select: none;
            transition: all .15s;
        }

        .nav-group-header:hover { background: rgba(255, 255, 255, 0.08); color: #ffffff; }

        .nav-group-arrow {
            margin-left: auto;
            font-size: .65rem;
            color: rgba(255, 255, 255, 0.45);
            transition: transform .2s ease;
        }

        .nav-sub-items {
            margin-left: 1.4rem;
            padding-left: .65rem;
            border-left: 1.5px solid rgba(255, 255, 255, 0.12);
            display: flex;
            flex-direction: column;
            gap: 2px;
            margin-top: 3px;
            margin-bottom: 5px;
        }

        .nav-sub-item {
            display: flex; align-items: center; gap: .5rem;
            padding: .42rem .75rem;
            color: rgba(255, 255, 255, 0.72);
            text-decoration: none;
            font-size: .78rem;
            border-radius: 6px;
            transition: all .15s;
        }

        .nav-sub-item:hover { background: rgba(255, 255, 255, 0.08); color: #ffffff; }

        .nav-sub-item.active {
            background: rgba(249, 168, 37, 0.18);
            color: #fbbf24;
            font-weight: 600;
        }

        .sidebar-footer {
            padding: .5rem 0;
            border-top: 1px solid var(--sidebar-border);
        }

        .logout-btn {
            display: flex; align-items: center; gap: .6rem;
            width: calc(100% - 1.5rem); margin: .25rem .75rem;
            padding: .5rem .85rem;
            background: rgba(239, 68, 68, 0.08);
            border: 1px solid rgba(239, 68, 68, 0.2);
            border-radius: 8px;
            color: #fca5a5;
            font-size: .82rem;
            font-family: inherit;
            cursor: pointer;
            text-decoration: none;
            transition: all .15s;
        }

        .logout-btn:hover { border-color: rgba(239, 68, 68, 0.4); color: #ffffff; background: rgba(239, 68, 68, 0.2); }

        /* ── Main ─────────────────────────────────────────────────── */
        .main-wrapper {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .topbar {
            height: var(--header-h);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            padding: 0 1.75rem;
            background: var(--surface);
            gap: 1rem;
            position: sticky; top: 0; z-index: 50;
        }

        .topbar-title { font-size: 1rem; font-weight: 600; flex: 1; }

        .topbar-actions { display: flex; align-items: center; gap: .75rem; }

        .icon-btn {
            width: 36px; height: 36px;
            border-radius: 8px;
            background: var(--surface2);
            border: 1px solid var(--border);
            color: var(--muted);
            display: flex; align-items: center; justify-content: center;
            font-size: 1rem; text-decoration: none;
            cursor: pointer;
            transition: all .15s;
            position: relative;
        }

        .icon-btn:hover { color: var(--text); border-color: var(--accent); }

        .notif-dot {
            position: absolute; top: 6px; right: 6px;
            width: 7px; height: 7px;
            border-radius: 50%;
            background: var(--danger);
            border: 1.5px solid var(--surface);
        }

        .main-content { padding: 1.75rem; flex: 1; }

        /* ── Alert flash ──────────────────────────────────────────── */
        .flash { padding: .75rem 1rem; border-radius: 8px; font-size: .85rem; margin-bottom: 1.25rem; display: flex; align-items: center; gap: .6rem; }
        .flash-success { background: rgba(52,211,153,.1); border: 1px solid rgba(52,211,153,.25); color: var(--success); }
        .flash-error   { background: rgba(248,113,113,.1); border: 1px solid rgba(248,113,113,.25); color: var(--danger); }
        .flash-info    { background: rgba(96,165,250,.1); border: 1px solid rgba(96,165,250,.25); color: var(--info); }

        /* ── Card ─────────────────────────────────────────────────── */
        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 1.5rem;
            box-shadow: var(--shadow-card);
        }

        .card-header {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 1.25rem;
            padding-bottom: .85rem;
            border-bottom: 1px solid var(--border-subtle);
        }

        .card-title { font-size: 1rem; font-weight: 700; color: var(--text-dark); letter-spacing: -.2px; }
        .card-sub   { font-size: .8rem; color: var(--muted); margin-top: 3px; }

        /* ── Stat Cards ───────────────────────────────────────────── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.15rem;
            margin-bottom: 1.5rem;
        }

        .stat-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 1.25rem 1.4rem;
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-card);
            transition: border-color .2s, transform .2s, box-shadow .2s;
        }

        .stat-card:hover {
            border-color: var(--primary-light);
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(27, 94, 32, 0.08);
        }

        .stat-card::after {
            content: '';
            position: absolute;
            top: 0; right: 0;
            width: 70px; height: 70px;
            background: var(--stat-color, var(--primary));
            opacity: .05;
            border-radius: 50%;
            transform: translate(25px, -25px);
        }

        .stat-icon  { font-size: 1.6rem; margin-bottom: .6rem; }
        .stat-value { font-size: 1.85rem; font-weight: 800; line-height: 1.1; color: var(--text-dark); }
        .stat-label { font-size: .8rem; font-weight: 500; color: var(--muted); margin-top: .35rem; }

        /* ── Table ────────────────────────────────────────────────── */
        .table-wrapper {
            overflow-x: auto;
            border-radius: 10px;
            border: 1px solid var(--border);
            background: var(--surface);
        }

        table { width: 100%; border-collapse: collapse; font-size: .85rem; }

        th {
            text-align: left;
            padding: .75rem 1rem;
            font-size: .73rem;
            font-weight: 700;
            color: var(--muted);
            letter-spacing: .05em;
            text-transform: uppercase;
            background: #f8fafc;
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
        }

        td {
            padding: .85rem 1rem;
            border-bottom: 1px solid var(--border-subtle);
            vertical-align: middle;
            color: var(--text);
        }

        tr:last-child td { border-bottom: none; }
        tr:hover td { background: #f8faf8; }

        /* ── Badge status ─────────────────────────────────────────── */
        .badge {
            display: inline-flex; align-items: center; gap: .35rem;
            padding: 4px 10px;
            border-radius: 9999px;
            font-size: .72rem;
            font-weight: 600;
            white-space: nowrap;
            line-height: 1.2;
        }

        .badge::before { content: '●'; font-size: .55rem; }

        .badge-waiting-tu      { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
        .badge-waiting-kaprodi { background: #fefce8; color: #b45309; border: 1px solid #fde68a; }
        .badge-waiting-dekan   { background: #faf5ff; color: #7e22ce; border: 1px solid #e9d5ff; }
        .badge-revisi          { background: #fff7ed; color: #c2410c; border: 1px solid #fed7aa; }
        .badge-disetujui       { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
        .badge-selesai         { background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; }
        .badge-ditolak         { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }
        .badge-draft           { background: #f8fafc; color: #475569; border: 1px solid #e2e8f0; }

        /* Fallback aliases */
        .badge-menunggu        { background: #fefce8; color: #b45309; border: 1px solid #fde68a; }

        /* ── Button ───────────────────────────────────────────────── */
        .btn {
            display: inline-flex; align-items: center; gap: .45rem;
            padding: .55rem 1.15rem;
            border-radius: 8px;
            font-size: .84rem;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            text-decoration: none;
            border: 1px solid transparent;
            transition: all .15s ease-in-out;
        }

        .btn-primary {
            background: var(--primary);
            color: #ffffff;
            box-shadow: 0 2px 6px rgba(27, 94, 32, 0.22);
        }
        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }

        .btn-accent {
            background: var(--accent);
            color: #144218;
            font-weight: 700;
            box-shadow: 0 2px 6px rgba(249, 168, 37, 0.25);
        }
        .btn-accent:hover {
            background: #f57f17;
            color: #ffffff;
            transform: translateY(-1px);
        }

        .btn-outline {
            background: #ffffff;
            border: 1px solid var(--border);
            color: var(--text);
        }
        .btn-outline:hover {
            border-color: var(--primary);
            color: var(--primary);
            background: #f0fdf4;
        }

        .btn-sm { padding: .35rem .8rem; font-size: .78rem; border-radius: 6px; }
        .btn-danger {
            background: #fef2f2;
            color: var(--danger);
            border: 1px solid #fecaca;
        }
        .btn-danger:hover {
            background: #fee2e2;
            color: #991b1b;
        }

        .btn-warning {
            background: #fffbeb;
            color: #b45309;
            border: 1px solid #fde68a;
        }
        .btn-warning:hover {
            background: #fef3c7;
            color: #92400e;
        }

        /* ── Form ─────────────────────────────────────────────────── */
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.1rem; }
        .form-grid.full { grid-template-columns: 1fr; }

        .form-group { display: flex; flex-direction: column; gap: .35rem; }
        .form-group.full { grid-column: 1 / -1; }

        label { font-size: .82rem; font-weight: 600; color: #334155; }

        input[type=text], input[type=email], input[type=password],
        input[type=number], input[type=date], select, textarea {
            padding: .65rem .9rem;
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--text);
            font-size: .88rem;
            font-family: inherit;
            outline: none;
            transition: border-color .15s, box-shadow .15s;
            width: 100%;
        }

        input:focus, select:focus, textarea:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(27, 94, 32, 0.12);
        }

        textarea { resize: vertical; min-height: 90px; }
        select option { background: #ffffff; color: var(--text); }

        .form-hint { font-size: .74rem; color: var(--muted); }
        .form-error { font-size: .74rem; color: var(--danger); font-weight: 500; }

        /* ── Empty state ──────────────────────────────────────────── */
        .empty-state {
            text-align: center;
            padding: 3rem 1.5rem;
        }

        .empty-icon { font-size: 2.75rem; margin-bottom: .75rem; }
        .empty-title { font-size: 1rem; font-weight: 700; color: var(--text-dark); margin-bottom: .3rem; }
        .empty-sub { font-size: .84rem; color: var(--muted); }

        /* ── Timeline (approval log) ──────────────────────────────── */
        .timeline { display: flex; flex-direction: column; gap: 0; }

        .tl-item {
            display: flex; gap: 1rem;
            padding-bottom: 1.35rem;
            position: relative;
        }

        .tl-item:not(:last-child) .tl-line::after {
            content: '';
            position: absolute;
            left: 16px; top: 34px; bottom: 0;
            width: 2px;
            background: var(--border);
        }

        .tl-dot {
            width: 34px; height: 34px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: .88rem;
            flex-shrink: 0;
            position: relative;
            z-index: 1;
        }

        .tl-dot.ok   { background: #f0fdf4; border: 2px solid #16a34a; color: #15803d; }
        .tl-dot.ng   { background: #fef2f2; border: 2px solid #dc2626; color: #b91c1c; }
        .tl-dot.wait { background: #fefce8; border: 2px solid #d97706; color: #b45309; }
        .tl-dot.rev  { background: #fff7ed; border: 2px solid #ea580c; color: #c2410c; }

        .tl-content .tl-title { font-size: .88rem; font-weight: 700; color: var(--text-dark); }
        .tl-content .tl-meta  { font-size: .76rem; color: var(--muted); margin-top: .15rem; }
        .tl-content .tl-note  { font-size: .82rem; color: var(--text); background: #f8fafc; border-radius: 6px; padding: .5rem .75rem; margin-top: .4rem; border-left: 3px solid var(--border); }


        /* ── Responsive ───────────────────────────────────────────── */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .main-wrapper { margin-left: 0; }
            .form-grid { grid-template-columns: 1fr; }
        }
    </style>
    <?= $extraHead ?? '' ?>
</head>
<body>

<!-- ══ Sidebar ══ -->
<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="brand-logo">
            <div class="brand-icon">🎓</div>
            <div class="brand-text">
                <h2>SASMITA</h2>
                <p>FKIP UIKA Bogor</p>
            </div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <?php require APPPATH . 'Views/partials/sidebar_nav.php'; ?>
    </nav>

    <div class="sidebar-footer">
        <?php
            $rawNama = trim(session('nama') ?? 'User');
            $namaParts = explode(' ', $rawNama);
            $initials = strtoupper(substr($namaParts[0] ?? 'U', 0, 1) . (isset($namaParts[1]) && $namaParts[1] !== '' ? substr($namaParts[1], 0, 1) : ''));
            $userSub = session('nim') ?? session('nip') ?? str_replace('_', ' ', session('role') ?? '');
        ?>
        <a href="/user/profile" class="sidebar-user" title="Buka Profil Saya">
            <div class="user-info">
                <div class="user-avatar"><?= esc($initials) ?></div>
                <div style="min-width:0; flex:1;">
                    <div class="user-name"><?= esc($rawNama) ?></div>
                    <div class="user-role" style="font-size:.68rem; color:rgba(255,255,255,0.6); margin-top:1px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                        <?= esc($userSub) ?>
                    </div>
                </div>
            </div>
        </a>
        <a href="/auth/logout" class="logout-btn">
            <span>🚪</span> Keluar
        </a>
    </div>
</aside>

<!-- ══ Main ══ -->
<div class="main-wrapper">
    <header class="topbar">
        <span class="topbar-title"><?= esc($title ?? 'SASMITA') ?></span>
        <div class="topbar-actions">
            <a href="<?= $notifUrl ?? '/notifikasi' ?>" class="icon-btn" title="Notifikasi">
                🔔
                <?php if (($unreadNotif ?? 0) > 0): ?>
                    <span class="notif-dot"></span>
                <?php endif ?>
            </a>
        </div>
    </header>

    <main class="main-content">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="flash flash-success">✅ <?= esc(session()->getFlashdata('success')) ?></div>
        <?php endif ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="flash flash-error">❌ <?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif ?>
        <?php if (session()->getFlashdata('errors')): ?>
            <div class="flash flash-error">
                <?php foreach (session()->getFlashdata('errors') as $e): ?>
                    ❌ <?= esc($e) ?><br>
                <?php endforeach ?>
            </div>
        <?php endif ?>

        <?= $pageContent ?? '' ?>
    </main>
</div>

<?= $extraScript ?? '' ?>
</body>
</html>
