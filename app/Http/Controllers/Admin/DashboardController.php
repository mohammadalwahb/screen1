<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\Service;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'buildingsCount' => Building::count(),
            'servicesCount' => Service::count(),
            'activeServicesCount' => Service::where('is_active', true)->count(),
        ]);
    }
}
