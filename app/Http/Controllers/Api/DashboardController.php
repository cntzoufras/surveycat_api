<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Dashboards\DashboardService;
use Illuminate\Http\JsonResponse;



class DashboardController extends Controller
{
    private DashboardService $dashboard_service;

    public function __construct(DashboardService $dashboard_service)
    {
        $this->dashboard_service = $dashboard_service;
    }

    /**
     * Fetch statistics for the main application dashboard.
     *
     * @return JsonResponse
     */
    public function getAppDashboardStats(): JsonResponse
    {
        $stats = $this->dashboard_service->getAppDashboardStats();
        return response()->json($stats);
    }

    /**
     * Fetch statistics for the surveys dashboard.
     *
     * @return JsonResponse
     */
    public function getSurveyDashboardStats(): JsonResponse
    {
        $stats = $this->dashboard_service->getSurveyDashboardStats();
        return response()->json($stats);
    }
}
