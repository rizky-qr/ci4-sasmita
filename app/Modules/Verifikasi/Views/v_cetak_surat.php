<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Resmi — <?= esc($pengajuan['nomor_surat'] ?: 'FKIP UIKA') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Times+New+Roman&family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: "Times New Roman", Times, serif;
            color: #000000;
            background: #e2e8f0;
            line-height: 1.5;
            padding: 2rem 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* ── Floating Action Bar (Hanya Tampil di Layar) ── */
        .action-bar {
            position: fixed;
            top: 1rem;
            display: flex;
            align-items: center;
            gap: .75rem;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            padding: .6rem 1.25rem;
            border-radius: 99px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.12);
            z-index: 1000;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .btn-print {
            background: #1b5e20;
            color: #ffffff;
            border: none;
            padding: .5rem 1.15rem;
            border-radius: 99px;
            font-weight: 700;
            font-size: .84rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: .4rem;
            transition: background .15s;
        }
        .btn-print:hover { background: #144218; }

        .btn-back {
            color: #475569;
            text-decoration: none;
            font-size: .84rem;
            font-weight: 600;
            padding: .4rem .75rem;
            border-radius: 99px;
            transition: background .15s;
        }
        .btn-back:hover { background: #f1f5f9; color: #0f172a; }

        /* ── Lembar Surat Resmi (A4 Ukuran Nyata) ── */
        .page {
            width: 210mm;
            min-height: 297mm;
            padding: 25mm 25mm 20mm 25mm;
            background: #ffffff;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            margin-top: 3.5rem;
            position: relative;
        }

        /* ── Kop Surat ── */
        .kop-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 3px;
        }

        .kop-table td {
            vertical-align: middle;
        }

        .kop-logo {
            width: 85px;
            text-align: center;
        }

        .kop-logo-box {
            width: 78px; height: 78px;
            border-radius: 12px;
            background: linear-gradient(135deg, #1b5e20 0%, #144218 100%);
            color: #f9a825;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 2.3rem;
            border: 2px solid #f9a825;
        }

        .kop-text {
            text-align: center;
            line-height: 1.25;
        }

        .kop-text .instansi {
            font-size: 11pt;
            font-weight: bold;
            letter-spacing: .5px;
        }

        .kop-text .univ {
            font-size: 15pt;
            font-weight: bold;
            letter-spacing: .8px;
            margin: 2px 0;
        }

        .kop-text .fakultas {
            font-size: 13pt;
            font-weight: bold;
            color: #1b5e20;
            letter-spacing: .5px;
        }

        .kop-text .alamat {
            font-size: 8.5pt;
            margin-top: 4px;
            color: #1e293b;
        }

        .kop-border {
            border-top: 3px solid #000000;
            border-bottom: 1px solid #000000;
            height: 4px;
            margin-top: 4px;
            margin-bottom: 22px;
        }

        /* ── Info Surat ── */
        .letter-meta {
            width: 100%;
            margin-bottom: 18px;
            font-size: 11pt;
        }

        .letter-meta td {
            vertical-align: top;
            padding: 1.5px 0;
        }

        .letter-title {
            text-align: center;
            margin-bottom: 18px;
        }

        .letter-title h2 {
            font-size: 13pt;
            text-decoration: underline;
            text-transform: uppercase;
            font-weight: bold;
            letter-spacing: .5px;
        }

        .letter-title p {
            font-size: 10.5pt;
            margin-top: 2px;
        }

        /* ── Isi Surat ── */
        .letter-body {
            font-size: 11pt;
            text-align: justify;
            line-height: 1.6;
        }

        .letter-body p {
            margin-bottom: 12px;
            text-indent: 30px;
        }

        .letter-body .no-indent {
            text-indent: 0;
        }

        .data-table {
            width: 90%;
            margin: 12px auto 16px 30px;
            font-size: 11pt;
        }

        .data-table td {
            padding: 2.5px 0;
            vertical-align: top;
        }

        .data-table td.lbl {
            width: 170px;
        }

        /* ── Tanda Tangan & QR Code ── */
        .signature-section {
            margin-top: 28px;
            width: 100%;
            page-break-inside: avoid;
        }

        .signature-table {
            width: 100%;
            font-size: 11pt;
        }

        .signature-table td {
            vertical-align: top;
        }

        .qr-box {
            text-align: center;
            padding: 8px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            display: inline-block;
            background: #ffffff;
        }

        .qr-box img {
            width: 95px; height: 95px;
            display: block;
        }

        .qr-label {
            font-size: 7.5pt;
            color: #475569;
            margin-top: 4px;
            max-width: 110px;
            line-height: 1.2;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .ttd-box {
            text-align: left;
            width: 250px;
            float: right;
        }

        .ttd-stamp {
            height: 75px;
            display: flex;
            align-items: center;
            position: relative;
        }

        .stamp-badge {
            display: inline-block;
            padding: 3px 8px;
            border: 1.5px dashed #16a34a;
            color: #166534;
            font-size: 8.5pt;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 700;
            border-radius: 4px;
            background: #f0fdf4;
        }

        .ttd-name {
            font-weight: bold;
            text-decoration: underline;
        }

        /* ── Print Media Query ── */
        @media print {
            body {
                background: none;
                padding: 0;
            }
            .action-bar {
                display: none !important;
            }
            .page {
                box-shadow: none;
                margin: 0;
                width: 100%;
                min-height: auto;
                padding: 15mm 20mm;
            }
        }
    </style>
</head>
<body>

<div class="action-bar">
    <a href="javascript:history.back()" class="btn-back">⬅️ Kembali</a>
    <button onclick="window.print()" class="btn-print">
        <span>🖨️</span> Cetak / Simpan PDF
    </button>
</div>

<div class="page">
    <!-- Kop Surat -->
    <table class="kop-table">
        <tr>
            <td class="kop-logo">
                <div class="kop-logo-box">🎓</div>
            </td>
            <td class="kop-text">
                <div class="instansi">YAYASAN PENDIDIKAN ISLAM IBN KHALDUN BOGOR</div>
                <div class="univ">UNIVERSITAS IBN KHALDUN BOGOR</div>
                <div class="fakultas">FAKULTAS KEGURUAN DAN ILMU PENDIDIKAN</div>
                <div class="alamat">
                    Jl. KH. Sholeh Iskandar Km. 2 Kota Bogor 16162 · Telp: (0251) 8335335 · Fax: (0251) 8335335<br>
                    Laman: www.fkip.uika-bogor.ac.id · Pos-el: fkip@uika-bogor.ac.id
                </div>
            </td>
        </tr>
    </table>
    <div class="kop-border"></div>

    <!-- Tanggal & Meta Surat -->
    <?php
        $tglDisetujui = !empty($pengajuan['tanggal_disetujui']) ? strtotime($pengajuan['tanggal_disetujui']) : strtotime($pengajuan['updated_at']);
        $bulanIndo = [1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
        $formatTanggal = date('d', $tglDisetujui) . ' ' . $bulanIndo[(int)date('m', $tglDisetujui)] . ' ' . date('Y', $tglDisetujui);

        $jenisLabels = [
            'AKTIF_KULIAH'          => 'SURAT KETERANGAN AKTIF KULIAH',
            'KETERANGAN_AKTIF'      => 'SURAT KETERANGAN AKTIF KULIAH',
            'TIDAK_BEASISWA'        => 'SURAT KETERANGAN TIDAK SEDANG MENERIMA BEASISWA',
            'TIDAK_ADA_PERKULIAHAN' => 'SURAT KETERANGAN TIDAK SEDANG MENERIMA BEASISWA',
            'IZIN_PENELITIAN'       => 'SURAT PERMOHONAN IZIN PENELITIAN',
            'IZIN_PLP_MAGANG'       => 'SURAT PENGANTAR PLP / MAGANG',
            'PERMOHONAN_PLP_MAGANG' => 'SURAT PENGANTAR PLP / MAGANG',
            'OBSERVASI_MATKUL'      => 'SURAT PERMOHONAN OBSERVASI MATA KULIAH',
            'PERMOHONAN_OBSERVASI'  => 'SURAT PERMOHONAN OBSERVASI MATA KULIAH',
            'REKOMENDASI'           => 'SURAT REKOMENDASI DEKAN',
            'KETERANGAN_LULUS'      => 'SURAT KETERANGAN LULUS (SKL)',
        ];
        $judulSurat = $jenisLabels[$pengajuan['jenis_surat']] ?? 'SURAT KETERANGAN';
    ?>

    <div class="letter-title">
        <h2><?= esc($judulSurat) ?></h2>
        <p>Nomor: <?= esc($pengajuan['nomor_surat'] ?: '.../FKIP-UIKA/.../...') ?></p>
    </div>

    <!-- Isi Surat -->
    <div class="letter-body">
        <p class="no-indent">
            Dekan Fakultas Keguruan dan Ilmu Pendidikan (FKIP) Universitas Ibn Khaldun Bogor dengan ini menerangkan bahwa:
        </p>

        <table class="data-table">
            <tr>
                <td class="lbl">Nama Lengkap</td>
                <td>: <strong><?= esc($pengajuan['mahasiswa_nama']) ?></strong></td>
            </tr>
            <tr>
                <td class="lbl">Nomor Induk Mahasiswa (NIM)</td>
                <td>: <?= esc($pengajuan['mahasiswa_nim']) ?></td>
            </tr>
            <tr>
                <td class="lbl">Program Studi</td>
                <td>: <?= esc(str_replace('_', ' ', $pengajuan['mahasiswa_program'])) ?></td>
            </tr>
            <tr>
                <td class="lbl">Fakultas</td>
                <td>: Keguruan dan Ilmu Pendidikan (FKIP)</td>
            </tr>
            <tr>
                <td class="lbl">Universitas</td>
                <td>: Universitas Ibn Khaldun Bogor</td>
            </tr>
        </table>

        <?php if (in_array($pengajuan['jenis_surat'], ['AKTIF_KULIAH', 'KETERANGAN_AKTIF'])): ?>
            <p>
                Adalah benar yang bersangkutan tercatat sebagai mahasiswa aktif pada Program Studi <?= esc(str_replace('_', ' ', $pengajuan['mahasiswa_program'])) ?> Fakultas Keguruan dan Ilmu Pendidikan Universitas Ibn Khaldun Bogor pada Tahun Akademik berjalan dan berkelakuan baik dalam menjalankan aktivitas perkuliahan.
            </p>
            <p>
                Surat keterangan ini dibuat atas permohonan yang bersangkutan untuk keperluan: <em>"<?= esc($pengajuan['keperluan']) ?>"</em>.
            </p>
        <?php elseif (in_array($pengajuan['jenis_surat'], ['TIDAK_BEASISWA', 'TIDAK_ADA_PERKULIAHAN'])): ?>
            <p>
                Dengan ini menerangkan bahwa mahasiswa tersebut di atas benar-benar <strong>tidak sedang menerima beasiswa</strong> dari instansi pemerintah maupun swasta lainnya, serta berstatus aktif mengikuti perkuliahan pada semester berjalan.
            </p>
            <p>
                Surat keterangan ini diberikan untuk keperluan: <em>"<?= esc($pengajuan['keperluan']) ?>"</em>.
            </p>
        <?php elseif ($pengajuan['jenis_surat'] === 'IZIN_PENELITIAN'): ?>
            <p>
                Dengan ini kami mengajukan permohonan izin bagi mahasiswa tersebut di atas untuk dapat melaksanakan penelitian skripsi / tugas akhir guna penyelesaian studinya, dengan fokus keperluan:
            </p>
            <p style="text-indent: 0; padding-left: 30px;">
                <em>"<?= esc($pengajuan['keperluan']) ?>"</em>
            </p>
            <p>
                Sehubungan dengan hal tersebut, kami sangat mengharapkan bantuan dan perkenan Bapak/Ibu pimpinan instansi untuk memberikan izin serta data/informasi yang diperlukan.
            </p>
        <?php elseif (in_array($pengajuan['jenis_surat'], ['IZIN_PLP_MAGANG', 'PERMOHONAN_PLP_MAGANG'])): ?>
            <p>
                Dengan ini kami menyampaikan permohonan penempatan Pengenalan Lapangan Persekolahan (PLP) / Magang Kependidikan bagi mahasiswa tersebut pada lembaga / sekolah yang Bapak/Ibu pimpin.
            </p>
            <p>
                Adapun rincian keperluan dan jadwal pelaksanaan adalah: <em>"<?= esc($pengajuan['keperluan']) ?>"</em>.
            </p>
        <?php else: ?>
            <p>
                Adalah benar mahasiswa tersebut di atas terdaftar resmi di lingkungan Fakultas Keguruan dan Ilmu Pendidikan Universitas Ibn Khaldun Bogor. Surat ini diterbitkan untuk keperluan:
            </p>
            <p style="text-indent: 0; padding-left: 30px;">
                <em>"<?= esc($pengajuan['keperluan']) ?>"</em>.
            </p>
        <?php endif; ?>

        <p>
            Demikian surat keterangan ini kami terbitkan dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya oleh pihak yang berkepentingan.
        </p>
    </div>

    <!-- Tanda Tangan & QR Code Autentikasi -->
    <div class="signature-section">
        <table class="signature-table">
            <tr>
                <td style="width: 45%;">
                    <!-- QR Code Verifikasi Asli -->
                    <div class="qr-box">
                        <img
                            src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=<?= urlencode($verifyUrl) ?>"
                            alt="QR Autentikasi Dokumen"
                        >
                        <div class="qr-label">
                            Pindai untuk verifikasi keaslian surat di SASMITA
                        </div>
                    </div>
                </td>
                <td style="width: 55%;">
                    <div class="ttd-box">
                        Bogor, <?= esc($formatTanggal) ?><br>
                        Dekan FKIP UIKA Bogor,
                        <div class="ttd-stamp">
                            <span class="stamp-badge">
                                [ TTD & CAP RESMI ELEKTRONIK ]<br>
                                Terverifikasi via SASMITA
                            </span>
                        </div>
                        <div class="ttd-name"><?= esc($dekan['nama'] ?? 'Dr. H. Mulyadi Santoso, M.Pd.') ?></div>
                        <div>NIP/NIDN. <?= esc($dekan['nip'] ?? '197205141998031002') ?></div>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>

</body>
</html>
