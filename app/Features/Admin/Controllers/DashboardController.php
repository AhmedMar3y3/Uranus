<?php

namespace App\Features\Admin\Controllers;

use App\Features\Admin\Services\DashboardService;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(DashboardService $dashboard): View
    {
        return view('admin.dashboard.index', $dashboard->overview());
    }
}
