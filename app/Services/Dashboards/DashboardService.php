<?php

namespace App\Services\Dashboards;

use App\Repositories\Dashboards\DashboardRepository;

class DashboardService
{

    private DashboardRepository $dashboard_repository;

    public function __construct(DashboardRepository $dashboard_repository)
    {
        $this->dashboard_repository = $dashboard_repository;
    }

    public function getSurveyDashboardStats()
    {
        return $this->dashboard_repository->getSurveyDashboardStats();
    }

    public function getAppDashboardStats()
    {
        return $this->dashboard_repository->getAppDashboardStats();
    }
}
