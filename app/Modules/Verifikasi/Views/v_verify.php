<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Keaslian Dokumen — SASMITA FKIP UIKA</title>
    <meta name="description" content="Layanan Verifikasi Dokumen dan Surat Resmi Mahasiswa FKIP UIKA Bogor">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --primary: #1b5e20;
            --primary-dark: #144218;
            --accent: #f9a825;
            --bg: #f6f8f5;
            --surface: #ffffff;
            --border: #e2e8f0;
            --text: #1e293b;
            --muted: #64748b;
            --success: #16a34a;
            --danger: #dc2626;
            --radius: 14px;
        }

        body {
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .verify-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);
            width: 100%;
            max-width: 620px;
            overflow: hidden;
            animation: fadeIn .3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(12px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .header-bar {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);
            color: #ffffff;
            padding: 1.5rem;
            text-align: center;
            border-bottom: 3px solid var(--accent);
        }

        .header-logo {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 52px; height: 52px;
            border-radius: 14px;
            background: linear-gradient(135deg, #f9a825 0%, #f57f17 100%);
            font-size: 1.6rem;
            margin-bottom: .6rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .header-bar h1 {
            font-size: 1.25rem;
            font-weight: 800;
            letter-spacing: -.3px;
        }

        .header-bar p {
            font-size: .8rem;
            color: rgba(255, 255, 255, 0.85);
            margin-top: 2px;
        }

        .status-banner {
            padding: 1.25rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .status-banner.valid {
            background: #f0fdf4;
            border-bottom: 1px solid #bbf7d0;
            color: #166534;
        }

        .status-banner.invalid {
            background: #fef2f2;
            border-bottom: 1px solid #fecaca;
            color: #991b1b;
        }

        .banner-icon {
            width: 44px; height: 44px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            flex-shrink: 0;
        }

        .status-banner.valid .banner-icon {
            background: #dcfce7;
            border: 2px solid #86efac;
        }

        .status-banner.invalid .banner-icon {
            background: #fee2e2;
            border: 2px solid #fca5a5;
        }

        .banner-text h2 {
            font-size: 1.05rem;
            font-weight: 700;
        }

        .banner-text p {
            font-size: .8rem;
            margin-top: 2px;
            opacity: .9;
        }

        .content-body {
            padding: 1.5rem;
        }

        .meta-table {
            width: 100%;
            border-collapse: collapse;
            font-size: .86rem;
            margin-bottom: 1.5rem;
        }

        .meta-table tr {
            border-bottom: 1px solid #f1f5f9;
        }

        .meta-table tr:last-child {
            border-bottom: none;
        }

        .meta-table td {
            padding: .75rem 0;
            vertical-align: top;
        }

        .meta-table td.label {
            width: 36%;
            color: var(--muted);
            font-weight: 600;
            font-size: .82rem;
        }

        .meta-table td.value {
            color: var(--text);
            font-weight: 500;
        }

        .meta-table td.value strong {
            color: var(--primary-dark);
            font-weight: 700;
        }

        .badge-verified {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 10px;
            border-radius: 99px;
            background: #dcfce7;
            color: #15803d;
            font-size: .75rem;
            font-weight: 700;
            border: 1px solid #bbf7d0;
        }

        .token-box {
            background: #f8fafc;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: .65rem .9rem;
            font-family: monospace;
            font-size: .78rem;
            color: #475569;
            word-break: break-all;
            margin-top: .4rem;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .5rem;
            width: 100%;
            padding: .75rem;
            background: var(--primary);
            color: #ffffff;
            font-weight: 600;
            font-size: .9rem;
            border-radius: 10px;
            text-decoration: none;
            transition: all .15s;
            box-shadow: 0 4px 12px rgba(27, 94, 32, 0.2);
        }

        .btn-action:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }

        .footer-note {
            text-align: center;
            font-size: .72rem;
            color: var(--muted);
            margin-top: 1.25rem;
        }
    </style>
</head>
<body>

<div class="verify-card">
    <div class="header-bar">
        <div class="header-logo">🎓</div>
        <h1>PORTAL VERIFIKASI DOKUMEN</h1>
        <p>Fakultas Keguruan dan Ilmu Pendidikan — Universitas Ibn Khaldun Bogor</p>
    </div>

    <?php if ($isValid): ?>
        <div class="status-banner valid">
            <div class="banner-icon">✓</div>
            <div class="banner-text">
                <h2>Dokumen Resmi Terverifikasi</h2>
                <p>Surat ini asli dan diterbitkan resmi oleh Dekanat FKIP UIKA Bogor.</p>
            </div>
        </div>

        <div class="content-body">
            <table class="meta-table">
                <tr>
                    <td class="label">Nomor Surat</td>
                    <td class="value"><strong><?= esc($pengajuan['nomor_surat']) ?></strong></td>
                </tr>
                <tr>
                    <td class="label">Perihal / Jenis Surat</td>
                    <td class="value">
                        <?php
                            $labels = [
                                'AKTIF_KULIAH'          => 'Surat Keterangan Aktif Kuliah',
                                'KETERANGAN_AKTIF'      => 'Surat Keterangan Aktif Kuliah',
                                'TIDAK_BEASISWA'        => 'Surat Keterangan Tidak Ada Perkuliahan / Beasiswa',
                                'TIDAK_ADA_PERKULIAHAN' => 'Surat Keterangan Tidak Ada Perkuliahan / Beasiswa',
                                'IZIN_PENELITIAN'       => 'Surat Permohonan Izin Penelitian (Skripsi)',
                                'IZIN_PLP_MAGANG'       => 'Surat Permohonan PLP / Magang',
                                'PERMOHONAN_PLP_MAGANG' => 'Surat Permohonan PLP / Magang',
                                'OBSERVASI_MATKUL'      => 'Surat Permohonan Observasi Mata Kuliah',
                                'PERMOHONAN_OBSERVASI'  => 'Surat Permohonan Observasi Mata Kuliah',
                                'REKOMENDASI'           => 'Surat Rekomendasi Dekan',
                                'KETERANGAN_LULUS'      => 'Surat Keterangan Lulus (SKL)',
                            ];
                            echo esc($labels[$pengajuan['jenis_surat']] ?? str_replace('_', ' ', $pengajuan['jenis_surat']));
                        ?>
                    </td>
                </tr>
                <tr>
                    <td class="label">Nama Mahasiswa</td>
                    <td class="value"><strong><?= esc($pengajuan['mahasiswa_nama']) ?></strong></td>
                </tr>
                <tr>
                    <td class="label">NIM Mahasiswa</td>
                    <td class="value"><?= esc($pengajuan['mahasiswa_nim']) ?></td>
                </tr>
                <tr>
                    <td class="label">Program Studi</td>
                    <td class="value"><?= esc(str_replace('_', ' ', $pengajuan['mahasiswa_program'])) ?></td>
                </tr>
                <tr>
                    <td class="label">Tanggal Pengesahan</td>
                    <td class="value">
                        <?= !empty($pengajuan['tanggal_disetujui']) ? date('d F Y', strtotime($pengajuan['tanggal_disetujui'])) : date('d F Y', strtotime($pengajuan['updated_at'])) ?>
                    </td>
                </tr>
                <tr>
                    <td class="label">Pejabat Pengesah</td>
                    <td class="value">
                        <span class="badge-verified">✓ Dekan FKIP UIKA</span>
                    </td>
                </tr>
                <tr>
                    <td class="label">Keperluan Surat</td>
                    <td class="value"><?= nl2br(esc($pengajuan['keperluan'])) ?></td>
                </tr>
                <tr>
                    <td class="label">Token Integritas</td>
                    <td class="value">
                        <div class="token-box"><?= esc($pengajuan['verify_token']) ?></div>
                    </td>
                </tr>
                <tr>
                    <td class="label">Audit Pemindaian</td>
                    <td class="value">
                        <div style="display:flex;align-items:center;gap:.5rem;font-size:.82rem;color:var(--text);font-weight:600;">
                            <span>👁️</span> Dokumen telah diverifikasi <strong><?= esc($totalScan ?? 1) ?> kali</strong>
                            <span style="color:var(--muted);font-size:.74rem;font-weight:normal;">(Terakhir: <?= date('d M Y, H:i') ?> WIB)</span>
                        </div>
                    </td>
                </tr>
            </table>

            <a href="/surat/cetak/<?= esc($pengajuan['verify_token']) ?>" class="btn-action" target="_blank">
                <span>📄</span> Buka / Cetak Salinan Dokumen Resmi
            </a>
        </div>
    <?php else: ?>
        <div class="status-banner invalid">
            <div class="banner-icon">✕</div>
            <div class="banner-text">
                <h2>Dokumen Tidak Valid / Belum Selesai</h2>
                <p>Dokumen dengan kode token ini tidak ditemukan atau proses pengesahan belum selesai.</p>
            </div>
        </div>

        <div class="content-body" style="text-align: center; padding: 2rem 1.5rem;">
            <p style="color: var(--muted); font-size: .88rem; margin-bottom: 1.5rem;">
                Pastikan Anda memindai QR Code dari surat resmi yang sah. Jika Anda mahasiswa pemohon, silakan cek status pengajuan di dashboard akun Anda.
            </p>
            <a href="/login" class="btn-action">
                <span>🏠</span> Kembali ke Portal SASMITA
            </a>
        </div>
    <?php endif ?>

    <div class="footer-note">
        Sistem Administrasi Surat Mahasiswa (SASMITA) · FKIP Universitas Ibn Khaldun Bogor
    </div>
</div>

</body>
</html>
