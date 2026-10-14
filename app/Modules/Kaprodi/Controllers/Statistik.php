<?php

namespace App\Modules\Kaprodi\Controllers;

use App\Controllers\BaseController;
use App\Modules\StafTU\Models\M_inbox;

class Statistik extends BaseController
{
    protected M_inbox $model;

    public function __construct()
    {
        $this->model = new M_inbox();
    }

    public function index()
    {
        return view('App\Modules\Kaprodi\Views\v_statistik', [
            'title'       => 'Statistik Pengajuan',
            'statStatus'  => $this->model->getStatistik(),
            'statJenis'   => $this->model->getStatistikByJenis(),
            'user'        => session()->get(),
        ]);
    }
}
