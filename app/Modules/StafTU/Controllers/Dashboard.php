<?php

namespace App\Modules\StafTU\Controllers;

use App\Controllers\BaseController;
use App\Modules\StafTU\Models\M_inbox;

class Dashboard extends BaseController
{
    protected M_inbox $model;

    public function __construct()
    {
        $this->model = new M_inbox();
    }

    public function index()
    {
        $stats   = $this->model->getStatistik();
        $pending = $this->model->getPendingStafTU();

        return view('App\Modules\StafTU\Views\v_dashboard', [
            'title'   => 'Dashboard Staf TU',
            'stats'   => $stats,
            'pending' => $pending,
            'user'    => session()->get(),
        ]);
    }
}
