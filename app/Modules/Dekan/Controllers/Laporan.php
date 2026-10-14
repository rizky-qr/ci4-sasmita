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
}
