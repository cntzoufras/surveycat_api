<?php

namespace App\Repositories\Dashboards;

use App\Models\Respondent;
use App\Models\Survey\SurveyResponse;
use App\Models\Survey\Survey;
use App\Models\Survey\SurveySubmission;
use App\Models\User;
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
        // User stats
        $totalUsers = User::count();
        $newUsersLast7Days = User::where('created_at', '>=', Carbon::now()->subDays(7))->count();
        $newUsersLast30Days = User::where('created_at', '>=', Carbon::now()->subDays(30))->count();

        // Active users in the last 30 days (defined as users with submissions)
        // Active respondents in the last 30 days (defined as distinct respondents with submissions)
        $activeUsers = DB::table('survey_submissions')
            ->join('survey_responses', 'survey_submissions.survey_response_id', '=', 'survey_responses.id')
            ->select(DB::raw('DATE(survey_submissions.created_at) as date'), DB::raw('count(distinct survey_responses.respondent_id) as count'))
            ->where('survey_submissions.created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Submission stats
        $totalSubmissions = SurveySubmission::count();
        $submissionsLast7Days = SurveySubmission::where('created_at', '>=', Carbon::now()->subDays(7))->count();

        // Survey stats
        $totalSurveys = Survey::count();
        $newSurveysLast7Days = Survey::where('created_at', '>=', Carbon::now()->subDays(7))->count();
        $newSurveysLast30Days = Survey::where('created_at', '>=', Carbon::now()->subDays(30))->count();

        // Advanced Survey Stats
        $totalStarted = SurveyResponse::count();
        $totalCompleted = SurveySubmission::count();
        $averageCompletionRate = $totalStarted > 0 ? ($totalCompleted / $totalStarted) * 100 : 0;

        $averageTimeToComplete = SurveyResponse::join('survey_submissions', 'survey_responses.id', '=', 'survey_submissions.survey_response_id')
            ->whereNotNull('survey_responses.completed_at')
            ->whereRaw('survey_responses.completed_at >= survey_responses.started_at')
            ->selectRaw('AVG(EXTRACT(EPOCH FROM (survey_responses.completed_at - survey_responses.started_at))) as avg_time')
            ->value('avg_time');

        $mostActiveSurveys = SurveySubmission::select('surveys.title', DB::raw('count(survey_submissions.id) as submission_count'))
            ->join('surveys', 'survey_submissions.survey_id', '=', 'surveys.id')
            ->join('survey_responses', 'survey_submissions.survey_response_id', '=', 'survey_responses.id')
            ->where('survey_responses.started_at', '>=', Carbon::now()->subDays(7))
            ->groupBy('surveys.title')
            ->orderByDesc('submission_count')
            ->limit(5)
            ->get();

        $surveysNeedingAttention = Survey::query()
            ->select('surveys.id', 'surveys.title')
            ->selectRaw('(
                SELECT COUNT(*) 
                FROM survey_responses 
                WHERE survey_responses.survey_id = surveys.id
            ) as started_count')
            ->selectRaw('(
                SELECT COUNT(*) 
                FROM survey_submissions 
                WHERE survey_submissions.survey_id = surveys.id
            ) as completed_count')
            ->selectRaw('CASE 
                WHEN (
                    SELECT COUNT(*) 
                    FROM survey_responses 
                    WHERE survey_responses.survey_id = surveys.id
                ) > 0 THEN ((
                    (SELECT COUNT(*) FROM survey_responses WHERE survey_responses.survey_id = surveys.id) - 
                    (SELECT COUNT(*) FROM survey_submissions WHERE survey_submissions.survey_id = surveys.id)
                )::float / (SELECT COUNT(*) FROM survey_responses WHERE survey_responses.survey_id = surveys.id)) * 100
                ELSE 0 
            END as drop_off_rate')
            ->whereExists(function ($query) {
                $query->selectRaw('1')
                    ->from('survey_responses')
                    ->whereColumn('survey_responses.survey_id', 'surveys.id');
            })
            ->orderBy('drop_off_rate', 'desc')
            ->limit(5)
            ->get();

        return [
            'totalUsers' => $totalUsers,
            'newUsers' => [
                'last7days' => $newUsersLast7Days,
                'last30days' => $newUsersLast30Days,
            ],
            'activeUsers' => $activeUsers,
            'totalSubmissions' => $totalSubmissions,
            'submissions' => [
                'last7days' => $submissionsLast7Days,
            ],
            'totalSurveys' => $totalSurveys,
            'newSurveys' => [
                'last7days' => $newSurveysLast7Days,
                'last30days' => $newSurveysLast30Days,
            ],
            'overallStats' => [
                'averageCompletionRate' => round($averageCompletionRate, 2),
                'averageTimeToComplete' => round($averageTimeToComplete),
            ],
            'recentPerformance' => [
                'mostActiveSurveys' => $mostActiveSurveys,
                'surveysNeedingAttention' => $surveysNeedingAttention,
            ]
        ];
    }

}
