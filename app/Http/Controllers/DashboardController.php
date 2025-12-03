<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private readonly DashboardService $dashboardService
    ) {}

    public function index(): View
    {
        $stats = $this->dashboardService->getStats();
        $trends = $this->dashboardService->getTrends();
        $activities = $this->dashboardService->getRecentActivity(10);
        $topClients = $this->dashboardService->getTopClients(5);
        $revenueData = $this->dashboardService->getRevenueByMonth(6);

        return view('dashboard', compact('stats', 'trends', 'activities', 'topClients', 'revenueData'));
    }
}
