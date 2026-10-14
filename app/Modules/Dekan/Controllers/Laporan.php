<?php

namespace App\Modules\Dekan\Controllers;

use App\Controllers\BaseController;
use App\Modules\StafTU\Models\M_inbox;

class Laporan extends BaseController
{
    protected M_inbox $model;
    public function __construct() { $this->model = new M_inbox(); }

    public function index()
    {
        return view('App\Modules\Dekan\Views\v_laporan', [
            'title'      => 'Laporan Pengajuan',
            'statStatus' => $this->model->getStatistik(),
            'statJenis'  => $this->model->getStatistikByJenis(),
            'user'       => session()->get(),
        ]);
    }

    public function export()
    {
        $db = \Config\Database::connect();
        $rows = $db->table('pengajuan_surat')
            ->orderBy('created_at', 'DESC')
            ->get()
            ->getResultArray();

        $filename = 'Rekap_Pengajuan_Surat_Dekanat_' . date('Ymd_His') . '.csv';

        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        $out = fopen('php://output', 'w');
        // UTF-8 BOM untuk kompatibilitas Microsoft Excel
        fputs($out, "\xEF\xBB\xBF");

        fputcsv($out, [
            'No',
            'ID Pengajuan',
            'Tanggal Ajuan',
            'Nomor Surat Resmi',
            'Jenis Surat',
            'Nama Mahasiswa',
            'NIM',
            'Program Studi',
            'Status',
            'Keperluan',
            'Token Verifikasi'
        ], ';');

        foreach ($rows as $i => $r) {
            fputcsv($out, [
                $i + 1,
                $r['id'],
                date('d/m/Y H:i', strtotime($r['created_at'])),
                $r['nomor_surat'] ?: '-',
                str_replace('_', ' ', $r['jenis_surat']),
                $r['mahasiswa_nama'],
                $r['mahasiswa_nim'],
                str_replace('_', ' ', $r['mahasiswa_program']),
                $r['status'],
                str_replace(["\r", "\n"], ' ', $r['keperluan']),
                $r['verify_token'] ?: '-'
            ], ';');
        }

        fclose($out);
        exit;
    }
}
