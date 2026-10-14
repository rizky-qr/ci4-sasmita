<?php

namespace App\Services;

/**
 * EmailNotificationService — Layanan notifikasi email otomatis mahasiswa saat surat selesai.
 * Menggunakan graceful dispatch: mencatat log audit & mencoba kirim via SMTP jika terkonfigurasi.
 */
class EmailNotificationService
{
    public static function sendLetterCompletedEmail(array $pengajuan, string $nomorSurat, string $verifyToken): bool
    {
        $db = \Config\Database::connect();
        $user = $db->table('users')->where('id', $pengajuan['mahasiswa_id'])->get()->getRowArray();

        $email = $user['email'] ?? null;
        $nama  = $pengajuan['mahasiswa_nama'] ?? 'Mahasiswa';
        $jenis = str_replace('_', ' ', $pengajuan['jenis_surat']);

        $cetakUrl = site_url('surat/cetak/' . $verifyToken);
        $verifyUrl = site_url('verify/' . $verifyToken);

        $logMsg = sprintf(
            "[%s] EMAIL_DISPATCH: Target=%s (%s), Surat=%s, No=%s, Link=%s\n",
            date('Y-m-d H:i:s'),
            $nama,
            $email ?: 'no-email',
            $jenis,
            $nomorSurat,
            $cetakUrl
        );

        $logDir = WRITEPATH . 'logs';
        if (! is_dir($logDir)) {
            @mkdir($logDir, 0777, true);
        }
        @file_put_contents($logDir . '/email_dispatch.log', $logMsg, FILE_APPEND);

        if (empty($email)) {
            return false;
        }

        try {
            $emailService = \Config\Services::email();
            $emailService->setTo($email);
            $emailService->setSubject("[SASMITA FKIP UIKA] Surat Resmi Anda Telah Disahkan — No: {$nomorSurat}");
            $emailService->setMessage("
                <div style='font-family:sans-serif;line-height:1.6;color:#1e293b;max-width:600px;margin:0 auto;border:1px solid #e2e8f0;border-radius:12px;padding:24px;'>
                    <div style='text-align:center;border-bottom:2px solid #144218;padding-bottom:12px;margin-bottom:20px;'>
                        <h2 style='color:#144218;margin:0;'>SASMITA FKIP UIKA BOGOR</h2>
                        <p style='color:#64748b;font-size:13px;margin:4px 0 0;'>Sistem Administrasi Surat Mahasiswa</p>
                    </div>
                    <p>Halo <strong>{$nama}</strong>,</p>
                    <p>Kabar baik! Pengajuan surat <strong>{$jenis}</strong> Anda telah selesai disahkan oleh Pimpinan Dekanat FKIP UIKA Bogor dengan nomor surat resmi:</p>
                    <div style='background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;padding:12px;text-align:center;font-size:16px;font-weight:bold;color:#166534;margin:16px 0;'>
                        No. {$nomorSurat}
                    </div>
                    <p>Anda dapat mengunduh dan mencetak salinan dokumen resmi yang telah dilengkapi Tanda Tangan & QR Code Digital melalui tautan berikut:</p>
                    <div style='text-align:center;margin:24px 0;'>
                        <a href='{$cetakUrl}' style='background:#1b5e20;color:#ffffff;text-decoration:none;font-weight:bold;padding:12px 24px;border-radius:8px;display:inline-block;'>
                            📄 Unduh / Cetak Surat Resmi (PDF)
                        </a>
                    </div>
                    <p style='font-size:13px;color:#64748b;'>Keaslian surat ini juga dapat diverifikasi publik di: <a href='{$verifyUrl}'>{$verifyUrl}</a></p>
                    <hr style='border:none;border-top:1px solid #e2e8f0;margin:24px 0;'>
                    <p style='font-size:11px;color:#94a3b8;text-align:center;'>Fakultas Keguruan dan Ilmu Pendidikan &bull; Universitas Ibn Khaldun Bogor</p>
                </div>
            ");
            $emailService->setMailType('html');

            return (bool) @$emailService->send();
        } catch (\Throwable $e) {
            return false;
        }
    }
}
