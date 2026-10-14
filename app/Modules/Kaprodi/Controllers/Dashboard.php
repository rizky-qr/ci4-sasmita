<?php

namespace App\Modules\Kaprodi\Controllers;

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
        return view('App\Modules\Kaprodi\Views\v_dashboard', [
            'title'   => 'Dashboard Kaprodi',
            'stats'   => $this->model->getStatistik(),
            'pending' => $this->model->getPendingKaprodi(),
            'user'    => session()->get(),
        ]);
    }
}
