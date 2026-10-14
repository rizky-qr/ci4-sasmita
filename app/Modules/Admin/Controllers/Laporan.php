<?php

namespace App\Modules\Admin\Controllers;

use App\Controllers\BaseController;
use App\Modules\StafTU\Models\M_inbox;

class Laporan extends BaseController
{
    public function index()
    {
        $model = new M_inbox();
        return view('App\Modules\Admin\Views\v_laporan', [
            'title'      => 'Laporan Global',
            'statStatus' => $model->getStatistik(),
            'statJenis'  => $model->getStatistikByJenis(),
            'user'       => session()->get(),
        ]);
    }
}
