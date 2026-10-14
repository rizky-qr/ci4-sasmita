<?php
/**
 * Helper functions — PHP 7.4 compatible (tidak pakai match expression).
 * Di-require dari views yang membutuhkan badge / dotClass helper.
 */
if (! function_exists('statusBadge')) {
    function statusBadge(string $status): string
    {
        $map = [
            'MENUNGGU_TU'      => ['class' => 'badge-waiting-tu',      'label' => 'Menunggu TU'],
            'MENUNGGU_KAPRODI' => ['class' => 'badge-waiting-kaprodi', 'label' => 'Menunggu Kaprodi'],
            'MENUNGGU_DEKAN'   => ['class' => 'badge-waiting-dekan',   'label' => 'Menunggu Dekan'],
            'BUTUH_REVISI'     => ['class' => 'badge-revisi',          'label' => 'Butuh Revisi'],
            'DISETUJUI'        => ['class' => 'badge-disetujui',       'label' => 'Disetujui'],
            'SELESAI'          => ['class' => 'badge-selesai',         'label' => 'Selesai'],
            'DITOLAK'          => ['class' => 'badge-ditolak',         'label' => 'Ditolak'],
            'DRAFT'            => ['class' => 'badge-draft',           'label' => 'Draft'],
        ];

        $info = $map[$status] ?? ['class' => 'badge-draft', 'label' => str_replace('_', ' ', $status)];
        return '<span class="badge ' . $info['class'] . '">' . esc($info['label']) . '</span>';
    }
}

if (! function_exists('dotClass')) {
    function dotClass(string $status): string
    {
        if (in_array($status, ['DISETUJUI', 'SELESAI'])) return 'ok';
        if ($status === 'DITOLAK') return 'ng';
        if ($status === 'BUTUH_REVISI') return 'rev';
        return 'wait';
    }
}

if (! function_exists('badgeClass')) {
    function badgeClass(string $status): string
    {
        $map = [
            'MENUNGGU_TU'      => 'badge-waiting-tu',
            'MENUNGGU_KAPRODI' => 'badge-waiting-kaprodi',
            'MENUNGGU_DEKAN'   => 'badge-waiting-dekan',
            'BUTUH_REVISI'     => 'badge-revisi',
            'DISETUJUI'        => 'badge-disetujui',
            'SELESAI'          => 'badge-selesai',
            'DITOLAK'          => 'badge-ditolak',
            'DRAFT'            => 'badge-draft',
        ];
        return $map[$status] ?? 'badge-draft';
    }
}
