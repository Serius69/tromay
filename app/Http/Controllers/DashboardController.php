<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function __construct(private DashboardService $dashboard) {}

    public function analytics()
    {
        return view('admin.analytics_dashboard');
    }

    public function apiSummary(): JsonResponse
    {
        return response()->json($this->dashboard->apiData());
    }
}
