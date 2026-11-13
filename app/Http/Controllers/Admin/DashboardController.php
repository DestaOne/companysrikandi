<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(private DashboardService $dashboardService) {}

    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'counts' => $this->dashboardService->counts(),
            'messages' => $this->dashboardService->recentMessages(),
            'activities' => $this->dashboardService->recentActivities(),
        ]);
    }
}
