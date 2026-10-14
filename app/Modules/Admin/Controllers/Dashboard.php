<?php

namespace App\Modules\Admin\Controllers;

use App\Controllers\BaseController;
use App\Modules\Admin\Models\M_user_admin;
use App\Modules\StafTU\Models\M_inbox;

class Dashboard extends BaseController
{
    public function index()
    {
        $inbox = new M_inbox();
        $users = new M_user_admin();

        return view('App\Modules\Admin\Views\v_dashboard', [
            'title'      => 'Dashboard Admin',
            'stats'      => $inbox->getStatistik(),
            'totalUsers' => $users->countAll(),
            'user'       => session()->get(),
        ]);
    }

    public function pengajuan()
    {
        $inbox = new M_inbox();
        $users = new M_user_admin();
        return view('App\Modules\Admin\Views\v_dashboard', [
            'title'      => 'Semua Pengajuan',
            'stats'      => $inbox->getStatistik(),
            'totalUsers' => $users->countAll(),
            'daftar'     => $inbox->getAll(),
            'user'       => session()->get(),
        ]);
    }
}
