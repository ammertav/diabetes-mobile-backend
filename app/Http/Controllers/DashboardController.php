<?php

namespace App\Http\Controllers;

use App\Actions\Dashboard\GetDashboardDataAction;

class DashboardController extends Controller
{
    public function index(GetDashboardDataAction $action)
    {
        $data = $action->execute();
        
        return view('dashboard.index', $data);
    }
}
