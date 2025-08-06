<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Respondent;
use App\Models\Survey\Survey;
use App\Models\Survey\SurveySubmission;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Fetch dashboard statistics.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStats(Request $request)
    {
        $totalSurveys = Survey::count();
        $totalRespondents = Respondent::count();
        $monthlySubmissions = SurveySubmission::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $surveyStatusCounts = Survey::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        $topSurveys = SurveySubmission::select('survey_id', DB::raw('count(*) as submission_count'))
            ->groupBy('survey_id')
            ->orderByDesc('submission_count')
            ->with('survey:id,name') // Eager load survey name and id
            ->limit(5)
            ->get();

        $yearlySubmissions = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthName = $date->shortEnglishMonth;
            $year = $date->year;

            $count = SurveySubmission::whereYear('created_at', $year)
                ->whereMonth('created_at', $date->month)
                ->count();

            $yearlySubmissions[] = [
                'month' => $monthName,
                'count' => $count,
            ];
        }

        return response()->json([
            'totalSurveys' => $totalSurveys,
            'totalRespondents' => $totalRespondents,
            'monthlySubmissions' => $monthlySubmissions,
            'surveyStatusCounts' => [
                'published' => $surveyStatusCounts->get('published', 0),
                'draft' => $surveyStatusCounts->get('draft', 0),
            ],
            'topSurveys' => $topSurveys,
            'yearlySubmissions' => $yearlySubmissions,
        ]);
    }
}
