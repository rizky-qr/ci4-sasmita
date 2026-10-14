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

if (! function_exists('slaBadge')) {
    function slaBadge(string $createdAt): string
    {
        $now = time();
        $created = strtotime($createdAt);
        $diffHours = max(0, ($now - $created) / 3600);

        if ($diffHours < 24) {
            return '<span class="sla-badge sla-green" title="Diajukan ' . round($diffHours, 1) . ' jam lalu (SLA Aman)"><span class="sla-dot">●</span> &lt; 24 jam</span>';
        } elseif ($diffHours <= 48) {
            return '<span class="sla-badge sla-yellow" title="Diajukan ' . round($diffHours, 1) . ' jam lalu (Perhatian)"><span class="sla-dot">●</span> 1-2 hari</span>';
        } else {
            $days = floor($diffHours / 24);
            return '<span class="sla-badge sla-red" title="Diajukan ' . $days . ' hari lalu (Perlu Tindakan Cepat)"><span class="sla-dot">●</span> &gt; 2 hari</span>';
        }
    }
}

if (! function_exists('renderStepper')) {
    function renderStepper(string $status): string
    {
        $steps = [
            1 => ['title' => 'Diajukan', 'role' => 'Mahasiswa', 'icon' => '📝'],
            2 => ['title' => 'Verifikasi TU', 'role' => 'Staf TU', 'icon' => '🔍'],
            3 => ['title' => 'Persetujuan', 'role' => 'Kaprodi', 'icon' => '🎓'],
            4 => ['title' => 'Pengesahan', 'role' => 'Dekan', 'icon' => '🏛️'],
            5 => ['title' => 'Selesai Terbit', 'role' => 'Dokumen Sah', 'icon' => '📜'],
        ];

        // Tentukan state per step: 'completed', 'active', 'revisi', 'rejected', 'pending'
        $state = [1 => 'pending', 2 => 'pending', 3 => 'pending', 4 => 'pending', 5 => 'pending'];

        if ($status === 'MENUNGGU_TU' || $status === 'DIAJUKAN' || $status === 'DRAFT') {
            $state[1] = 'completed';
            $state[2] = 'active';
        } elseif ($status === 'MENUNGGU_KAPRODI') {
            $state[1] = 'completed';
            $state[2] = 'completed';
            $state[3] = 'active';
        } elseif ($status === 'MENUNGGU_DEKAN') {
            $state[1] = 'completed';
            $state[2] = 'completed';
            $state[3] = 'completed';
            $state[4] = 'active';
        } elseif ($status === 'SELESAI' || $status === 'DISETUJUI') {
            $state[1] = 'completed';
            $state[2] = 'completed';
            $state[3] = 'completed';
            $state[4] = 'completed';
            $state[5] = 'completed';
        } elseif ($status === 'BUTUH_REVISI') {
            $state[1] = 'completed';
            $state[2] = 'revisi';
        } elseif ($status === 'DITOLAK') {
            $state[1] = 'completed';
            $state[2] = 'rejected';
        }

        $html = '<div class="stepper-container">';
        $count = count($steps);
        foreach ($steps as $idx => $step) {
            $s = $state[$idx];
            $icon = $step['icon'];
            if ($s === 'completed') $icon = '✓';
            elseif ($s === 'revisi') $icon = '⚠️';
            elseif ($s === 'rejected') $icon = '✕';

            $html .= '<div class="step-item ' . $s . '">';
            $html .= '  <div class="step-circle">' . $icon . '</div>';
            $html .= '  <div class="step-info">';
            $html .= '      <div class="step-title">' . esc($step['title']) . '</div>';
            $html .= '      <div class="step-role">' . esc($step['role']) . '</div>';
            $html .= '  </div>';
            if ($idx < $count) {
                $lineClass = ($s === 'completed' && in_array($state[$idx+1], ['completed', 'active', 'revisi', 'rejected'])) ? 'line-completed' : '';
                $html .= '  <div class="step-connector ' . $lineClass . '"></div>';
            }
            $html .= '</div>';
        }
        $html .= '</div>';

        return $html;
    }
}
