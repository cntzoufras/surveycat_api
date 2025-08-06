<?php

namespace App\Repositories\Dashboards;

use App\Models\Respondent;
use App\Models\Survey\SurveyResponse;
use App\Models\Survey\Survey;
use App\Models\Survey\SurveySubmission;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardRepository
{

    public function getSurveyDashboardStats()
    {
        $totalSurveys = Survey::count();
        $totalRespondents = Respondent::count();
        $monthlySubmissions = SurveySubmission::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $surveysCreatedWeekly = Survey::where('created_at', '>=', Carbon::now()->subWeek())->count();
        $participantsWeekly = Respondent::where('created_at', '>=', Carbon::now()->subWeek())->count();

        $surveyStatusCounts = Survey::select('survey_status_id', DB::raw('count(id) as count'))
            ->groupBy('survey_status_id')
            ->pluck('count', 'survey_status_id');

        $respondentsWeekly = Respondent::where('created_at', '>=', Carbon::now()->subWeek())->count();

        $topSurveys = SurveySubmission::select('surveys.title', DB::raw('count(survey_submissions.id) as submission_count'))
            ->join('surveys', 'survey_submissions.survey_id', '=', 'surveys.id')
            ->groupBy('surveys.title')
            ->orderByDesc('submission_count')
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

        $surveysCompletedToday = SurveyResponse::whereDate('completed_at', today())->count();

        $topSurveyTopics = DB::table('survey_submissions')
            ->join('surveys', 'survey_submissions.survey_id', '=', 'surveys.id')
            ->join('survey_categories', 'surveys.survey_category_id', '=', 'survey_categories.id')
            ->select('survey_categories.title as topic', DB::raw('COUNT(survey_submissions.id) as submissions'))
            ->groupBy('survey_categories.title')
            ->orderBy('submissions', 'desc')
            ->limit(5) // Get top 5 topics
            ->get();

        $totalSubmissions = SurveySubmission::count();
        $topSurveyTopics = $topSurveyTopics->map(function ($item) use ($totalSubmissions) {
            $item->percentage = $totalSubmissions > 0 ? round(($item->submissions / $totalSubmissions) * 100) : 0;
            return $item;
        });

        // New Stats
        $weeklySubmissions = SurveySubmission::where('created_at', '>=', Carbon::now()->subWeek())->count();
        $respondentsWeekly = Respondent::where('created_at', '>=', Carbon::now()->subWeek())->count();

        // Completion Rate Stats
        $completedCount = SurveySubmission::count();
        $totalResponses = SurveyResponse::count();
        $didNotFinish = $totalResponses - $completedCount;
        $didNotStart = $totalSurveys - $totalResponses;

        return [
            'totalSurveys' => $totalSurveys,
            'totalRespondents' => $totalRespondents,
            'monthlySubmissions' => $monthlySubmissions,
            'weeklySubmissions' => $weeklySubmissions,
            'respondentsWeekly' => $respondentsWeekly,
            'surveyStatusCounts' => [
                'active' => $surveyStatusCounts->get(2, 0) + $surveyStatusCounts->get(3, 0), // IDs for 'published' and 'open'
                'inactive' => $surveyStatusCounts->get(1, 0), // ID for 'draft'
            ],
            'topSurveys' => $topSurveys,
            'yearlySubmissions' => $yearlySubmissions,
            'surveysCompletedToday' => $surveysCompletedToday,
            'topSurveyTopics' => $topSurveyTopics,
            'completionStats' => [
                'completed' => $completedCount,
                'didNotFinish' => $didNotFinish,
                'didNotStart' => $didNotStart,
            ]
        ];
    }

    public function getAppDashboardStats()
    {
        return;
    }

}
