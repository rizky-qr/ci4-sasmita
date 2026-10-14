<?php

namespace App\Modules\Dekan\Controllers;

use App\Controllers\BaseController;
use App\Modules\StafTU\Models\M_inbox;

class Dashboard extends BaseController
{
    protected M_inbox $model;
    public function __construct() { $this->model = new M_inbox(); }

    public function index()
    {
        return view('App\Modules\Dekan\Views\v_dashboard', [
            'title'   => 'Dashboard Dekan',
            'stats'   => $this->model->getStatistik(),
            'pending' => $this->model->getPendingDekan(),
            'user'    => session()->get(),
        ]);
    }
}
