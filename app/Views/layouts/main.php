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

        /* ── Sidebar Quick Status Card ── */
        .sidebar-status-card {
            margin: 0.65rem 0.75rem 0.75rem;
            padding: 0.65rem 0.75rem;
            background: rgba(0, 0, 0, 0.28);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 10px;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.35);
        }

        .status-card-header {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 0.45rem;
            padding-bottom: 0.35rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .status-live-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #10b981;
            box-shadow: 0 0 6px #10b981;
            animation: livePulse 2s infinite ease-in-out;
        }

        @keyframes livePulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.4; transform: scale(0.85); }
        }

        .status-card-title {
            font-size: 0.62rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            color: rgba(255, 255, 255, 0.6);
            text-transform: uppercase;
        }

        .status-card-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 6px;
        }

        .status-card-item {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 0.42rem 0.5rem;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .status-card-item:hover {
            transform: translateY(-1px);
            background: rgba(255, 255, 255, 0.12);
            border-color: rgba(255, 255, 255, 0.25);
        }

        .status-card-item.is-alert {
            background: rgba(245, 158, 11, 0.16);
            border: 1px solid rgba(245, 158, 11, 0.45);
        }
        .status-card-item.is-alert:hover {
            background: rgba(245, 158, 11, 0.26);
            border-color: rgba(245, 158, 11, 0.7);
            box-shadow: 0 2px 10px rgba(245, 158, 11, 0.3);
        }

        .status-card-item.is-danger {
            background: rgba(239, 68, 68, 0.16);
            border: 1px solid rgba(239, 68, 68, 0.45);
        }
        .status-card-item.is-danger:hover {
            background: rgba(239, 68, 68, 0.26);
            border-color: rgba(239, 68, 68, 0.7);
            box-shadow: 0 2px 10px rgba(239, 68, 68, 0.3);
        }

        .status-card-icon {
            font-size: 1.05rem;
            line-height: 1;
            flex-shrink: 0;
        }

        .status-card-meta {
            min-width: 0;
            display: flex;
            flex-direction: column;
        }

        .status-card-num {
            font-size: 1.05rem;
            font-weight: 800;
            line-height: 1.1;
            font-feature-settings: "tnum";
        }

        .status-card-num.text-amber {
            color: #fbbf24;
            text-shadow: 0 0 8px rgba(251, 191, 36, 0.4);
        }

        .status-card-num.text-red {
            color: #f87171;
            text-shadow: 0 0 8px rgba(248, 113, 113, 0.4);
        }

        .status-card-num.text-ok {
            color: rgba(255, 255, 255, 0.55);
        }

        .status-card-label {
            font-size: 0.56rem;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.65);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            text-transform: uppercase;
            letter-spacing: 0.02em;
        }

        /* ── Nav Item Counter Pills (Compact Number Badges) ── */
        .nav-counter-pill {
            margin-left: auto;
            font-size: 0.74rem;
            font-weight: 800;
            min-width: 22px;
            height: 22px;
            padding: 0 6px;
            border-radius: 9999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            white-space: nowrap;
            line-height: 1;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            letter-spacing: -0.01em;
            flex-shrink: 0;
            cursor: pointer;
            user-select: none;
            position: relative;
        }

        .nav-counter-pill:hover {
            transform: scale(1.18);
        }

        .nav-counter-pill.pending {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: #ffffff;
            border: 1.5px solid rgba(255, 255, 255, 0.45);
            box-shadow: 0 2px 8px rgba(245, 158, 11, 0.5);
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.35);
        }
        .nav-counter-pill.pending:hover {
            box-shadow: 0 0 14px rgba(245, 158, 11, 0.85);
            border-color: #ffffff;
        }

        .nav-counter-pill.notif {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: #ffffff;
            border: 1.5px solid rgba(255, 255, 255, 0.45);
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.55);
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.35);
        }
        .nav-counter-pill.notif:hover {
            box-shadow: 0 0 14px rgba(239, 68, 68, 0.85);
            border-color: #ffffff;
        }

        .nav-counter-pill.revisi {
            background: linear-gradient(135deg, #ea580c 0%, #c2410c 100%);
            color: #ffffff;
            border: 1.5px solid rgba(255, 255, 255, 0.45);
            box-shadow: 0 2px 8px rgba(234, 88, 12, 0.5);
            animation: revisiPulse 2s infinite ease-in-out;
        }

        .nav-counter-pill.info {
            background: rgba(14, 165, 233, 0.25);
            color: #38bdf8;
            border: 1.5px solid rgba(56, 189, 248, 0.5);
            box-shadow: 0 2px 6px rgba(14, 165, 233, 0.35);
        }

        .nav-counter-pill.clean {
            background: rgba(255, 255, 255, 0.08);
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.68rem;
            font-weight: 600;
            border: 1px solid rgba(255, 255, 255, 0.12);
        }

        .nav-counter-pill.muted {
            background: rgba(255, 255, 255, 0.06);
            color: rgba(255, 255, 255, 0.45);
            font-size: 0.68rem;
            font-weight: 600;
        }

        @keyframes revisiPulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(234, 88, 12, 0.5); }
            50% { box-shadow: 0 0 0 5px rgba(234, 88, 12, 0); }
        }

        /* ── Floating Hover Toolbar / Popover for Sidebar Badges ── */
        .sidebar-floating-toolbar {
            position: fixed;
            z-index: 999999;
            background: linear-gradient(145deg, #0f172a 0%, #1e293b 100%);
            color: #f8fafc;
            padding: 0.55rem 0.8rem;
            border-radius: 8px;
            box-shadow: 0 10px 25px -4px rgba(0, 0, 0, 0.65), 0 0 0 1px rgba(255, 255, 255, 0.16);
            pointer-events: none;
            opacity: 0;
            visibility: hidden;
            transform: translateY(4px);
            transition: opacity 0.15s cubic-bezier(0.4, 0, 0.2, 1), transform 0.15s cubic-bezier(0.4, 0, 0.2, 1), visibility 0.15s;
            max-width: 250px;
            min-width: 160px;
        }

        .sidebar-floating-toolbar.visible {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .sidebar-floating-toolbar .toolbar-header {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.76rem;
            font-weight: 700;
            color: #ffffff;
            line-height: 1.25;
        }

        .sidebar-floating-toolbar .toolbar-icon {
            font-size: 0.95rem;
            line-height: 1;
        }

        .sidebar-floating-toolbar .toolbar-desc {
            font-size: 0.65rem;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.72);
            margin-top: 3px;
            line-height: 1.35;
        }

        .sidebar-floating-toolbar .toolbar-arrow {
            position: absolute;
            bottom: -5px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 0;
            border-left: 6px solid transparent;
            border-right: 6px solid transparent;
            border-top: 6px solid #1e293b;
        }

        .sidebar-floating-toolbar.arrow-top .toolbar-arrow {
            bottom: auto;
            top: -5px;
            border-top: none;
            border-bottom: 6px solid #0f172a;
        }

        /* ── Topbar Notif Badge ── */
        .icon-btn {
            position: relative;
        }
        .topbar-notif-count {
            position: absolute;
            top: -2px;
            right: -4px;
            background: #ef4444;
            color: #ffffff;
            font-size: 0.62rem;
            font-weight: 800;
            min-width: 17px;
            height: 17px;
            line-height: 17px;
            text-align: center;
            border-radius: 9px;
            border: 1.5px solid #ffffff;
            box-shadow: 0 2px 5px rgba(239, 68, 68, 0.5);
            padding: 0 3px;
        }

        /* ── Mobile Nav Badge ── */
        .mobile-nav-item {
            position: relative;
        }
        .mobile-nav-badge {
            position: absolute;
            top: 4px;
            right: 50%;
            margin-right: -16px;
            background: #ef4444;
            color: #ffffff;
            font-size: .6rem;
            font-weight: 800;
            min-width: 16px;
            height: 16px;
            line-height: 16px;
            border-radius: 8px;
            text-align: center;
            padding: 0 4px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.25);
        }
        .mobile-nav-badge.pending {
            background: #f59e0b;
            color: #ffffff;
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


        /* ── Stepper Component ────────────────────────────────────── */
        .stepper-container {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            position: relative;
            margin: 1rem 0 1.5rem;
            padding: .5rem .25rem;
            background: #ffffff;
            border-radius: var(--radius);
            border: 1px solid var(--border);
        }

        .step-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            flex: 1;
            position: relative;
            z-index: 2;
        }

        .step-circle {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .95rem;
            font-weight: 700;
            background: #ffffff;
            border: 2px solid var(--border);
            color: var(--muted);
            transition: all .25s ease;
            box-shadow: 0 2px 5px rgba(0,0,0,0.03);
        }

        .step-info {
            margin-top: .5rem;
            max-width: 105px;
        }

        .step-title {
            font-size: .78rem;
            font-weight: 700;
            color: var(--muted);
            line-height: 1.25;
        }

        .step-role {
            font-size: .68rem;
            color: var(--muted);
            margin-top: 2px;
        }

        .step-connector {
            position: absolute;
            top: 19px;
            left: 50%;
            width: 100%;
            height: 3px;
            background: #e2e8f0;
            z-index: -1;
            transition: background .3s ease;
        }

        .step-connector.line-completed {
            background: #16a34a;
        }

        .step-item.completed .step-circle {
            background: #16a34a;
            border-color: #16a34a;
            color: #ffffff;
            box-shadow: 0 4px 10px rgba(22, 163, 74, 0.28);
        }
        .step-item.completed .step-title { color: #166534; font-weight: 700; }

        .step-item.active .step-circle {
            background: #ffffff;
            border-color: var(--primary);
            color: var(--primary);
            box-shadow: 0 0 0 4px var(--primary-glow);
            animation: pulse-ring 2s infinite;
        }
        .step-item.active .step-title { color: var(--primary); font-weight: 800; }

        .step-item.revisi .step-circle {
            background: #fff7ed;
            border-color: #ea580c;
            color: #ea580c;
            box-shadow: 0 0 0 4px rgba(234, 88, 12, 0.15);
        }
        .step-item.revisi .step-title { color: #c2410c; font-weight: 700; }

        .step-item.rejected .step-circle {
            background: #fef2f2;
            border-color: #dc2626;
            color: #dc2626;
            box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.15);
        }
        .step-item.rejected .step-title { color: #b91c1c; font-weight: 700; }

        @keyframes pulse-ring {
            0% { box-shadow: 0 0 0 0 var(--primary-glow); }
            70% { box-shadow: 0 0 0 8px transparent; }
            100% { box-shadow: 0 0 0 0 transparent; }
        }

        /* ── SLA Badges ───────────────────────────────────────────── */
        .sla-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: .7rem;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 6px;
            letter-spacing: .2px;
            white-space: nowrap;
        }
        .sla-dot { font-size: .55rem; }
        .sla-green  { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
        .sla-yellow { background: #fefce8; color: #854d0e; border: 1px solid #fef08a; }
        .sla-red    { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }

        /* ── Floating Batch Action Bar ────────────────────────────── */
        .batch-bar {
            position: fixed;
            bottom: 1.5rem;
            left: calc(var(--sidebar-w) + 1.5rem);
            right: 1.5rem;
            background: #144218;
            color: #ffffff;
            padding: .85rem 1.5rem;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.25);
            display: none;
            align-items: center;
            justify-content: space-between;
            z-index: 150;
            animation: slideUp .25s ease;
        }
        @keyframes slideUp {
            from { transform: translateY(20px); opacity: 0; }
            to   { transform: translateY(0); opacity: 1; }
        }

        /* ── Mobile Bottom Navigation Bar ─────────────────────────── */
        .mobile-bottom-nav {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 60px;
            background: #ffffff;
            border-top: 1px solid var(--border);
            z-index: 999;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.06);
            align-items: center;
            justify-content: space-around;
            padding: 0 .5rem;
        }

        .mobile-nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: var(--muted);
            text-decoration: none;
            font-size: .68rem;
            font-weight: 600;
            flex: 1;
            height: 100%;
            position: relative;
            transition: color .15s;
        }
        .mobile-nav-item.active {
            color: var(--primary);
        }
        .mobile-nav-item .m-icon {
            font-size: 1.15rem;
            margin-bottom: 2px;
        }
        .mobile-nav-badge {
            position: absolute;
            top: 6px;
            right: calc(50% - 16px);
            background: var(--danger);
            color: #ffffff;
            font-size: .6rem;
            font-weight: 700;
            padding: 1px 4px;
            border-radius: 10px;
            min-width: 14px;
            text-align: center;
        }

        /* ── Responsive ───────────────────────────────────────────── */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .main-wrapper { margin-left: 0; padding-bottom: 75px; }
            .main-content { padding: 1rem .85rem; }
            .form-grid { grid-template-columns: 1fr; }
            .batch-bar { left: 1rem; right: 1rem; bottom: 70px; }
            .mobile-bottom-nav { display: flex; }
            .stepper-container { flex-direction: column; gap: 1rem; align-items: flex-start; padding: 1rem; }
            .step-item { flex-direction: row; gap: .75rem; text-align: left; width: 100%; }
            .step-info { margin-top: 0; max-width: none; }
            .step-connector { display: none; }
            .table-wrapper { border-radius: 8px; -webkit-overflow-scrolling: touch; }
            .table-wrapper table { min-width: 580px; }
        }
    </style>
    <?= $extraHead ?? '' ?>
</head>
<body>

<?php
$currentRole   = session('role') ?? 'MAHASISWA';
$currentUserId = session('id') ?? session('user_id');
$currentUri    = uri_string();
$unreadNotifCount = 0;
$pendingInboxCount = 0;

if ($currentUserId) {
    try {
        $dbConn = \Config\Database::connect();
        $unreadNotifCount = (int) $dbConn->table('notifikasi')
            ->where('user_id', $currentUserId)
            ->where('is_read', 0)
            ->countAllResults();

        if ($currentRole === 'STAF_TU_PRODI') {
            $pendingInboxCount = (int) $dbConn->table('pengajuan_surat')->where('status', 'MENUNGGU_TU')->countAllResults();
        } elseif ($currentRole === 'KAPRODI') {
            $pendingInboxCount = (int) $dbConn->table('pengajuan_surat')->where('status', 'MENUNGGU_KAPRODI')->countAllResults();
        } elseif ($currentRole === 'DEKAN') {
            $pendingInboxCount = (int) $dbConn->table('pengajuan_surat')->where('status', 'MENUNGGU_DEKAN')->countAllResults();
        } elseif ($currentRole === 'MAHASISWA') {
            $pendingInboxCount = (int) $dbConn->table('pengajuan_surat')->where('mahasiswa_id', $currentUserId)->whereIn('status', ['MENUNGGU_TU', 'MENUNGGU_KAPRODI', 'MENUNGGU_DEKAN'])->countAllResults();
        } elseif ($currentRole === 'ADMIN') {
            $pendingInboxCount = (int) $dbConn->table('pengajuan_surat')->whereIn('status', ['MENUNGGU_TU', 'MENUNGGU_KAPRODI', 'MENUNGGU_DEKAN'])->countAllResults();
        }
    } catch (\Throwable $e) {}
}
?>

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
            <a href="<?= $notifUrl ?? '/notifikasi' ?>" class="icon-btn" title="Notifikasi (<?= $unreadNotifCount ?> pesan baru)">
                🔔
                <?php if ($unreadNotifCount > 0): ?>
                    <span class="topbar-notif-count"><?= $unreadNotifCount > 99 ? '99+' : $unreadNotifCount ?></span>
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

<!-- ══ Mobile Bottom Navigation Bar (< 768px) ══ -->
<nav class="mobile-bottom-nav">
    <?php if ($currentRole === 'MAHASISWA'): ?>
        <a href="/dashboard/mahasiswa" class="mobile-nav-item <?= ($currentUri === 'dashboard/mahasiswa') ? 'active' : '' ?>">
            <span class="m-icon">🏠</span>
            <span>Beranda</span>
        </a>
        <a href="/dashboard/mahasiswa/pengajuan/aktif" class="mobile-nav-item <?= (strpos($currentUri, 'pengajuan') !== false) ? 'active' : '' ?>">
            <span class="m-icon">📝</span>
            <span>Ajukan</span>
        </a>
        <a href="/dashboard/mahasiswa/riwayat" class="mobile-nav-item <?= (strpos($currentUri, 'riwayat') !== false) ? 'active' : '' ?>">
            <span class="m-icon">📜</span>
            <span>Riwayat</span>
            <?php if ($pendingInboxCount > 0): ?>
                <span class="mobile-nav-badge pending"><?= $pendingInboxCount ?></span>
            <?php endif ?>
        </a>
        <a href="/notifikasi" class="mobile-nav-item <?= (strpos($currentUri, 'notifikasi') !== false) ? 'active' : '' ?>">
            <span class="m-icon">🔔</span>
            <span>Notif</span>
            <?php if ($unreadNotifCount > 0): ?>
                <span class="mobile-nav-badge"><?= $unreadNotifCount ?></span>
            <?php endif ?>
        </a>
        <a href="/user/profile" class="mobile-nav-item <?= (strpos($currentUri, 'profile') !== false) ? 'active' : '' ?>">
            <span class="m-icon">👤</span>
            <span>Profil</span>
        </a>
    <?php elseif ($currentRole === 'STAF_TU_PRODI'): ?>
        <a href="/dashboard/staf-tu" class="mobile-nav-item <?= ($currentUri === 'dashboard/staf-tu') ? 'active' : '' ?>">
            <span class="m-icon">🏠</span>
            <span>Beranda</span>
        </a>
        <a href="/dashboard/staf-tu/inbox" class="mobile-nav-item <?= ($currentUri === 'dashboard/staf-tu/inbox' || strpos($currentUri, 'staf-tu/detail') !== false) ? 'active' : '' ?>">
            <span class="m-icon">📥</span>
            <span>Inbox</span>
            <?php if ($pendingInboxCount > 0): ?>
                <span class="mobile-nav-badge pending"><?= $pendingInboxCount ?></span>
            <?php endif ?>
        </a>
        <a href="/dashboard/staf-tu/diproses" class="mobile-nav-item <?= ($currentUri === 'dashboard/staf-tu/diproses') ? 'active' : '' ?>">
            <span class="m-icon">⏳</span>
            <span>Diproses</span>
        </a>
        <a href="/notifikasi" class="mobile-nav-item <?= (strpos($currentUri, 'notifikasi') !== false) ? 'active' : '' ?>">
            <span class="m-icon">🔔</span>
            <span>Notif</span>
            <?php if ($unreadNotifCount > 0): ?>
                <span class="mobile-nav-badge"><?= $unreadNotifCount ?></span>
            <?php endif ?>
        </a>
        <a href="/user/profile" class="mobile-nav-item <?= (strpos($currentUri, 'profile') !== false) ? 'active' : '' ?>">
            <span class="m-icon">👤</span>
            <span>Profil</span>
        </a>
    <?php elseif ($currentRole === 'KAPRODI'): ?>
        <a href="/dashboard/kaprodi" class="mobile-nav-item <?= ($currentUri === 'dashboard/kaprodi') ? 'active' : '' ?>">
            <span class="m-icon">🏠</span>
            <span>Beranda</span>
        </a>
        <a href="/dashboard/kaprodi/inbox" class="mobile-nav-item <?= (strpos($currentUri, 'inbox') !== false) ? 'active' : '' ?>">
            <span class="m-icon">📥</span>
            <span>Inbox</span>
            <?php if ($pendingInboxCount > 0): ?>
                <span class="mobile-nav-badge pending"><?= $pendingInboxCount ?></span>
            <?php endif ?>
        </a>
        <a href="/dashboard/kaprodi/riwayat" class="mobile-nav-item <?= (strpos($currentUri, 'riwayat') !== false) ? 'active' : '' ?>">
            <span class="m-icon">📜</span>
            <span>Riwayat</span>
        </a>
        <a href="/dashboard/kaprodi/statistik" class="mobile-nav-item <?= (strpos($currentUri, 'statistik') !== false) ? 'active' : '' ?>">
            <span class="m-icon">📊</span>
            <span>Statistik</span>
        </a>
        <a href="/user/profile" class="mobile-nav-item <?= (strpos($currentUri, 'profile') !== false) ? 'active' : '' ?>">
            <span class="m-icon">👤</span>
            <span>Profil</span>
        </a>
    <?php elseif ($currentRole === 'DEKAN'): ?>
        <a href="/dashboard/dekan" class="mobile-nav-item <?= ($currentUri === 'dashboard/dekan') ? 'active' : '' ?>">
            <span class="m-icon">🏛️</span>
            <span>Beranda</span>
        </a>
        <a href="/dashboard/dekan/inbox" class="mobile-nav-item <?= (strpos($currentUri, 'inbox') !== false) ? 'active' : '' ?>">
            <span class="m-icon">🖋️</span>
            <span>Inbox</span>
            <?php if ($pendingInboxCount > 0): ?>
                <span class="mobile-nav-badge pending"><?= $pendingInboxCount ?></span>
            <?php endif ?>
        </a>
        <a href="/dashboard/dekan/riwayat" class="mobile-nav-item <?= (strpos($currentUri, 'riwayat') !== false || strpos($currentUri, 'selesai') !== false) ? 'active' : '' ?>">
            <span class="m-icon">📜</span>
            <span>Selesai</span>
        </a>
        <a href="/dashboard/dekan/laporan" class="mobile-nav-item <?= (strpos($currentUri, 'laporan') !== false) ? 'active' : '' ?>">
            <span class="m-icon">📊</span>
            <span>Laporan</span>
        </a>
        <a href="/user/profile" class="mobile-nav-item <?= (strpos($currentUri, 'profile') !== false) ? 'active' : '' ?>">
            <span class="m-icon">👤</span>
            <span>Profil</span>
        </a>
    <?php else: ?>
        <a href="/dashboard/admin" class="mobile-nav-item <?= ($currentUri === 'dashboard/admin') ? 'active' : '' ?>">
            <span class="m-icon">🏠</span>
            <span>Beranda</span>
        </a>
        <a href="/dashboard/admin/users" class="mobile-nav-item <?= (strpos($currentUri, 'users') !== false) ? 'active' : '' ?>">
            <span class="m-icon">👥</span>
            <span>Users</span>
        </a>
        <a href="/dashboard/admin/pengajuan" class="mobile-nav-item <?= (strpos($currentUri, 'pengajuan') !== false) ? 'active' : '' ?>">
            <span class="m-icon">📁</span>
            <span>Surat</span>
        </a>
        <a href="/notifikasi" class="mobile-nav-item <?= (strpos($currentUri, 'notifikasi') !== false) ? 'active' : '' ?>">
            <span class="m-icon">🔔</span>
            <span>Notif</span>
        </a>
        <a href="/user/profile" class="mobile-nav-item <?= (strpos($currentUri, 'profile') !== false) ? 'active' : '' ?>">
            <span class="m-icon">👤</span>
            <span>Profil</span>
        </a>
    <?php endif ?>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let toolbar = document.getElementById('sidebar-floating-toolbar');
    if (!toolbar) {
        toolbar = document.createElement('div');
        toolbar.id = 'sidebar-floating-toolbar';
        toolbar.className = 'sidebar-floating-toolbar';
        document.body.appendChild(toolbar);
    }

    let hideTimeout;

    document.querySelectorAll('.nav-counter-pill').forEach(function(pill) {
        pill.addEventListener('mouseenter', function() {
            clearTimeout(hideTimeout);
            const title = pill.getAttribute('data-toolbar-title') || pill.getAttribute('title') || '';
            const desc = pill.getAttribute('data-toolbar-desc') || 'Klik untuk membuka rincian';
            const icon = pill.getAttribute('data-toolbar-icon') || '📌';
            
            if (!title) return;

            toolbar.innerHTML = `
                <div class="toolbar-header">
                    <span class="toolbar-icon">${icon}</span>
                    <span class="toolbar-title">${title}</span>
                </div>
                <div class="toolbar-desc">${desc}</div>
                <div class="toolbar-arrow"></div>
            `;

            toolbar.style.display = 'block';
            
            const rect = pill.getBoundingClientRect();
            const tbRect = toolbar.getBoundingClientRect();
            
            let top = rect.top - tbRect.height - 8;
            let left = rect.left + (rect.width / 2) - (tbRect.width / 2);

            if (top < 10) {
                top = rect.bottom + 8;
                toolbar.classList.add('arrow-top');
            } else {
                toolbar.classList.remove('arrow-top');
            }

            if (left < 10) left = 10;
            if (left + tbRect.width > window.innerWidth - 10) {
                left = window.innerWidth - tbRect.width - 10;
            }

            toolbar.style.top = top + 'px';
            toolbar.style.left = left + 'px';
            
            requestAnimationFrame(function() {
                toolbar.classList.add('visible');
            });
        });

        pill.addEventListener('mouseleave', function() {
            hideTimeout = setTimeout(function() {
                toolbar.classList.remove('visible');
            }, 60);
        });
    });
});
</script>

<?= $extraScript ?? '' ?>
</body>
</html>
