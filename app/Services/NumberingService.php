<?php

namespace App\Services;

/**
 * NumberingService — Layanan Penomoran Surat Resmi Terstandar
 * Format Resmi FKIP Universitas Ibn Khaldun Bogor:
 * [Nomor Urut 3 Digit]/[Kode Klasifikasi]/FKIP-UIKA/[Bulan Romawi]/[Tahun]
 * Contoh: 042/B-AK/FKIP-UIKA/IV/2025
 */
class NumberingService
{
    /**
     * Peta klasifikasi jenis surat ke kode arsip FKIP UIKA
     */
    protected static array $classificationMap = [
        'AKTIF_KULIAH'          => 'B-AK',
        'KETERANGAN_AKTIF'      => 'B-AK',
        'TIDAK_BEASISWA'        => 'B-TB',
        'TIDAK_ADA_PERKULIAHAN' => 'B-TB',
        'IZIN_PENELITIAN'       => 'C-PN',
        'IZIN_PLP_MAGANG'       => 'C-PLP',
        'PERMOHONAN_PLP_MAGANG' => 'C-PLP',
        'OBSERVASI_MATKUL'      => 'C-OB',
        'PERMOHONAN_OBSERVASI'  => 'C-OB',
        'REKOMENDASI'           => 'D-RK',
        'KETERANGAN_LULUS'      => 'A-SKL',
    ];

    /**
     * Konversi angka bulan (1-12) ke angka Romawi
     */
    public static function monthToRoman(int $month): string
    {
        $romans = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV',
            5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII',
            9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII',
        ];
        return $romans[$month] ?? 'I';
    }

    /**
     * Dapatkan kode klasifikasi untuk jenis surat
     */
    public static function getClassificationCode(string $jenisSurat): string
    {
        return self::$classificationMap[$jenisSurat] ?? 'B-UM';
    }

    /**
     * Generate nomor surat resmi dengan nomor urut tahunan
     * @param string $jenisSurat
     * @return string Contoh: 015/B-AK/FKIP-UIKA/X/2026
     */
    public static function generate(string $jenisSurat): string
    {
        $db = \Config\Database::connect();
        $tahun = (int) date('Y');
        $bulan = (int) date('n');

        // Hitung total surat yang telah selesai/disetujui pada tahun berjalan
        // Ditambah 1 untuk urutan berikutnya
        $builder = $db->table('pengajuan_surat');
        $count = $builder
            ->where("YEAR(created_at)", $tahun)
            ->whereIn('status', ['DISETUJUI', 'SELESAI'])
            ->where('nomor_surat IS NOT NULL', null, false)
            ->where("nomor_surat !=", '')
            ->countAllResults();

        $urutan = $count + 1;
        $nomorUrut = sprintf('%03d', $urutan);
        $kode = self::getClassificationCode($jenisSurat);
        $bulanRomawi = self::monthToRoman($bulan);

        return "{$nomorUrut}/{$kode}/FKIP-UIKA/{$bulanRomawi}/{$tahun}";
    }

    /**
     * Generate secure token untuk verifikasi publik (QR Code)
     */
    public static function generateVerifyToken(): string
    {
        return bin2hex(random_bytes(16));
    }
}
