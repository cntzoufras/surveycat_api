<?php

namespace App\Repositories\Dashboards;

use App\Models\Respondent;
use App\Models\Survey\SurveyResponse;
use App\Models\Survey\Survey;
use App\Models\Survey\SurveySubmission;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use League\ISO3166\ISO3166;

class DashboardRepository
{

    public function getSurveyDashboardStats()
    {
        /** @var User|null $user */
        $user = Auth::user();
        $ownerId = Auth::id();
        $isAdmin = $user && method_exists($user, 'isAdmin') && $user->isAdmin();

        $totalSurveys = Survey::query()
            ->when(!$isAdmin, fn($q) => $q->where('user_id', $ownerId))
            ->count();

        // Count distinct respondents associated with the owner's surveys
        $totalRespondents = Respondent::query()
            ->when(!$isAdmin, function ($q) use ($ownerId) {
                $q->whereExists(function ($sub) use ($ownerId) {
                    $sub->selectRaw('1')
                        ->from('survey_responses')
                        ->join('surveys', 'survey_responses.survey_id', '=', 'surveys.id')
                        ->whereColumn('survey_responses.respondent_id', 'respondents.id')
                        ->where('surveys.user_id', $ownerId);
                });
            })
            ->count();

        $monthlySubmissions = SurveySubmission::query()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->when(!$isAdmin, function ($q) use ($ownerId) {
                $q->whereExists(function ($sub) use ($ownerId) {
                    $sub->selectRaw('1')
                        ->from('surveys')
                        ->whereColumn('surveys.id', 'survey_submissions.survey_id')
                        ->where('surveys.user_id', $ownerId);
                });
            })
            ->count();

        $surveysCreatedWeekly = Survey::query()
            ->where('created_at', '>=', Carbon::now()->subWeek())
            ->when(!$isAdmin, fn($q) => $q->where('user_id', $ownerId))
            ->count();

        // Participants with any response in the last week tied to owner's surveys
        $participantsWeekly = Respondent::query()
            ->whereExists(function ($sub) use ($ownerId) {
                $sub->selectRaw('1')
                    ->from('survey_responses')
                    ->join('surveys', 'survey_responses.survey_id', '=', 'surveys.id')
                    ->whereColumn('survey_responses.respondent_id', 'respondents.id')
                    // Use started_at for weekly activity window to avoid default created_at timestamps of NOW()
                    ->where('survey_responses.started_at', '>=', Carbon::now()->subWeek())
                    ->when($ownerId, fn($q) => $q->where('surveys.user_id', $ownerId));
            })
            ->when($isAdmin, fn($q) => $q) // no-op for clarity
            ->count();

        $surveyStatusCounts = Survey::query()
            ->select('survey_status_id', DB::raw('count(id) as count'))
            ->when(!$isAdmin, fn($q) => $q->where('user_id', $ownerId))
            ->groupBy('survey_status_id')
            ->pluck('count', 'survey_status_id');

        $respondentsWeekly = Respondent::query()
            ->whereExists(function ($sub) use ($ownerId) {
                $sub->selectRaw('1')
                    ->from('survey_responses')
                    ->join('surveys', 'survey_responses.survey_id', '=', 'surveys.id')
                    ->whereColumn('survey_responses.respondent_id', 'respondents.id')
                    // Use started_at for weekly activity window to avoid default created_at timestamps of NOW()
                    ->where('survey_responses.started_at', '>=', Carbon::now()->subWeek())
                    ->when($ownerId, fn($q) => $q->where('surveys.user_id', $ownerId));
            })
            ->when($isAdmin, fn($q) => $q)
            ->count();

        $topSurveys = SurveySubmission::query()
            ->select('surveys.title', DB::raw('count(survey_submissions.id) as submission_count'))
            ->join('surveys', 'survey_submissions.survey_id', '=', 'surveys.id')
            ->when(!$isAdmin, fn($q) => $q->where('surveys.user_id', $ownerId))
            ->groupBy('surveys.title')
            ->orderByDesc('submission_count')
            ->limit(5)
            ->get();

        $yearlySubmissions = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthName = $date->shortEnglishMonth;
            $year = $date->year;

            $count = SurveySubmission::query()
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $date->month)
                ->when(!$isAdmin, function ($q) use ($ownerId) {
                    $q->whereExists(function ($sub) use ($ownerId) {
                        $sub->selectRaw('1')
                            ->from('surveys')
                            ->whereColumn('surveys.id', 'survey_submissions.survey_id')
                            ->where('surveys.user_id', $ownerId);
                    });
                })
                ->count();

            $yearlySubmissions[] = [
                'month' => $monthName,
                'count' => $count,
            ];
        }

        // Use submissions as the definition of a completed survey within the app-local day window (mapped to UTC)
        $startOfLocalDayUtc = Carbon::now()->startOfDay()->utc();
        $endOfLocalDayUtc = Carbon::now()->endOfDay()->utc();
        $surveysCompletedToday = SurveySubmission::query()
            ->whereBetween('created_at', [$startOfLocalDayUtc, $endOfLocalDayUtc])
            ->when(!$isAdmin, function ($q) use ($ownerId) {
                $q->whereExists(function ($sub) use ($ownerId) {
                    $sub->selectRaw('1')
                        ->from('surveys')
                        ->whereColumn('surveys.id', 'survey_submissions.survey_id')
                        ->where('surveys.user_id', $ownerId);
                });
            })
            ->count();

        $topSurveyTopics = DB::table('survey_submissions')
            ->join('surveys', 'survey_submissions.survey_id', '=', 'surveys.id')
            ->join('survey_categories', 'surveys.survey_category_id', '=', 'survey_categories.id')
            ->when(!$isAdmin, fn($q) => $q->where('surveys.user_id', $ownerId))
            ->select('survey_categories.title as topic', DB::raw('COUNT(survey_submissions.id) as submissions'))
            ->groupBy('survey_categories.title')
            ->orderBy('submissions', 'desc')
            ->limit(5) // Get top 5 topics
            ->get();

        $totalSubmissions = SurveySubmission::query()
            ->when(!$isAdmin, function ($q) use ($ownerId) {
                $q->whereExists(function ($sub) use ($ownerId) {
                    $sub->selectRaw('1')
                        ->from('surveys')
                        ->whereColumn('surveys.id', 'survey_submissions.survey_id')
                        ->where('surveys.user_id', $ownerId);
                });
            })
            ->count();
        $topSurveyTopics = $topSurveyTopics->map(function ($item) use ($totalSubmissions) {
            $item->percentage = $totalSubmissions > 0 ? round(($item->submissions / $totalSubmissions) * 100) : 0;
            return $item;
        });

        // New Stats
        $weeklySubmissions = SurveySubmission::query()
            ->where('created_at', '>=', Carbon::now()->subWeek())
            ->when(!$isAdmin, function ($q) use ($ownerId) {
                $q->whereExists(function ($sub) use ($ownerId) {
                    $sub->selectRaw('1')
                        ->from('surveys')
                        ->whereColumn('surveys.id', 'survey_submissions.survey_id')
                        ->where('surveys.user_id', $ownerId);
                });
            })
            ->count();
        $respondentsWeekly = $respondentsWeekly; // already computed above with scope

        // Completion Rate Stats (scoped for non-admins)
        $completedCount = SurveySubmission::query()
            ->when(!$isAdmin, function ($q) use ($ownerId) {
                $q->whereExists(function ($sub) use ($ownerId) {
                    $sub->selectRaw('1')
                        ->from('surveys')
                        ->whereColumn('surveys.id', 'survey_submissions.survey_id')
                        ->where('surveys.user_id', $ownerId);
                });
            })
            ->count();
        $totalResponses = SurveyResponse::query()
            ->when(!$isAdmin, function ($q) use ($ownerId) {
                $q->whereExists(function ($sub) use ($ownerId) {
                    $sub->selectRaw('1')
                        ->from('surveys')
                        ->whereColumn('surveys.id', 'survey_responses.survey_id')
                        ->where('surveys.user_id', $ownerId);
                });
            })
            ->count();
        $didNotFinish = $totalResponses - $completedCount;
        $didNotStart = $totalSurveys - $totalResponses;

        $result = [
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

        // Append analytics for dashboard modules with default, non-selectable windows
        $result['analytics'] = [
            'bounceRate' => $this->getBounceRateSeries(Carbon::now()->subDays(7), Carbon::now(), null, $ownerId, $isAdmin),
            'visitorSessions' => $this->getVisitorSessions(Carbon::now()->subDays(30), Carbon::now(), null, $ownerId, $isAdmin),
            'audienceByCountry' => $this->getAudienceByCountry(Carbon::now()->subDays(30), Carbon::now(), null, $ownerId, $isAdmin),
            'occupancy' => $this->getOccupancySeries(Carbon::now()->subWeeks(8), Carbon::now(), null, $ownerId, $isAdmin),
        ];

        return $result;
    }

    public function getAppDashboardStats()
    {
        /** @var User|null $user */
        $user = Auth::user();
        $ownerId = Auth::id();
        $isAdmin = $user && method_exists($user, 'isAdmin') && $user->isAdmin();

        // User stats (for non-admins, treat as distinct respondents on their surveys)
        $totalUsers = $isAdmin
            ? User::count()
            : Respondent::query()
                ->whereExists(function ($q) use ($ownerId) {
                    $q->selectRaw('1')
                        ->from('survey_responses')
                        ->join('surveys', 'survey_responses.survey_id', '=', 'surveys.id')
                        ->whereColumn('survey_responses.respondent_id', 'respondents.id')
                        ->where('surveys.user_id', $ownerId);
                })
                ->count();

        $newUsersLast7Days = $isAdmin
            ? User::where('created_at', '>=', Carbon::now()->subDays(7))->count()
            : Respondent::query()
                ->whereExists(function ($q) use ($ownerId) {
                    $q->selectRaw('1')
                        ->from('survey_responses')
                        ->join('surveys', 'survey_responses.survey_id', '=', 'surveys.id')
                        ->whereColumn('survey_responses.respondent_id', 'respondents.id')
                        ->where('surveys.user_id', $ownerId)
                        ->where('survey_responses.started_at', '>=', Carbon::now()->subDays(7));
                })
                ->count();

        $newUsersLast30Days = $isAdmin
            ? User::where('created_at', '>=', Carbon::now()->subDays(30))->count()
            : Respondent::query()
                ->whereExists(function ($q) use ($ownerId) {
                    $q->selectRaw('1')
                        ->from('survey_responses')
                        ->join('surveys', 'survey_responses.survey_id', '=', 'surveys.id')
                        ->whereColumn('survey_responses.respondent_id', 'respondents.id')
                        ->where('surveys.user_id', $ownerId)
                        ->where('survey_responses.started_at', '>=', Carbon::now()->subDays(30));
                })
                ->count();

        // Active respondents per day in last 30 days
        $activeUsers = DB::table('survey_submissions')
            ->join('survey_responses', 'survey_submissions.survey_response_id', '=', 'survey_responses.id')
            ->when(!$isAdmin, function ($q) use ($ownerId) {
                $q->join('surveys', 'survey_submissions.survey_id', '=', 'surveys.id')
                  ->where('surveys.user_id', $ownerId);
            })
            ->select(DB::raw('DATE(survey_submissions.created_at) as date'), DB::raw('count(distinct survey_responses.respondent_id) as count'))
            ->where('survey_submissions.created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Submission stats
        $totalSubmissions = SurveySubmission::query()
            ->when(!$isAdmin, function ($q) use ($ownerId) {
                $q->whereExists(function ($sub) use ($ownerId) {
                    $sub->selectRaw('1')
                        ->from('surveys')
                        ->whereColumn('surveys.id', 'survey_submissions.survey_id')
                        ->where('surveys.user_id', $ownerId);
                });
            })
            ->count();
        $submissionsLast7Days = SurveySubmission::query()
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->when(!$isAdmin, function ($q) use ($ownerId) {
                $q->whereExists(function ($sub) use ($ownerId) {
                    $sub->selectRaw('1')
                        ->from('surveys')
                        ->whereColumn('surveys.id', 'survey_submissions.survey_id')
                        ->where('surveys.user_id', $ownerId);
                });
            })
            ->count();

        // Survey stats
        $totalSurveys = Survey::query()
            ->when(!$isAdmin, fn($q) => $q->where('user_id', $ownerId))
            ->count();
        $newSurveysLast7Days = Survey::query()
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->when(!$isAdmin, fn($q) => $q->where('user_id', $ownerId))
            ->count();
        $newSurveysLast30Days = Survey::query()
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->when(!$isAdmin, fn($q) => $q->where('user_id', $ownerId))
            ->count();

        // Advanced Survey Stats
        $totalStarted = SurveyResponse::query()
            ->when(!$isAdmin, function ($q) use ($ownerId) {
                $q->whereExists(function ($sub) use ($ownerId) {
                    $sub->selectRaw('1')
                        ->from('surveys')
                        ->whereColumn('surveys.id', 'survey_responses.survey_id')
                        ->where('surveys.user_id', $ownerId);
                });
            })
            ->count();
        $totalCompleted = SurveySubmission::query()
            ->when(!$isAdmin, function ($q) use ($ownerId) {
                $q->whereExists(function ($sub) use ($ownerId) {
                    $sub->selectRaw('1')
                        ->from('surveys')
                        ->whereColumn('surveys.id', 'survey_submissions.survey_id')
                        ->where('surveys.user_id', $ownerId);
                });
            })
            ->count();
        $averageCompletionRate = $totalStarted > 0 ? ($totalCompleted / $totalStarted) * 100 : 0;

        $averageTimeToComplete = SurveyResponse::query()
            ->join('survey_submissions', 'survey_responses.id', '=', 'survey_submissions.survey_response_id')
            ->when(!$isAdmin, function ($q) use ($ownerId) {
                $q->join('surveys', 'survey_responses.survey_id', '=', 'surveys.id')
                  ->where('surveys.user_id', $ownerId);
            })
            ->whereNotNull('survey_responses.completed_at')
            ->whereRaw('survey_responses.completed_at >= survey_responses.started_at')
            ->selectRaw('AVG(EXTRACT(EPOCH FROM (survey_responses.completed_at - survey_responses.started_at))) as avg_time')
            ->value('avg_time');

        $mostActiveSurveys = SurveySubmission::select('surveys.title', DB::raw('count(survey_submissions.id) as submission_count'))
            ->join('surveys', 'survey_submissions.survey_id', '=', 'surveys.id')
            ->join('survey_responses', 'survey_submissions.survey_response_id', '=', 'survey_responses.id')
            ->when(!$isAdmin, fn($q) => $q->where('surveys.user_id', $ownerId))
            ->where('survey_responses.started_at', '>=', Carbon::now()->subDays(7))
            ->groupBy('surveys.title')
            ->orderByDesc('submission_count')
            ->limit(5)
            ->get();

        // Align High Drop-off window with the last 7 days as well
        $since = Carbon::now()->subDays(7)->toDateTimeString();

        $surveysNeedingAttention = Survey::query()
            ->when(!$isAdmin, fn($q) => $q->where('surveys.user_id', $ownerId))
            ->select('surveys.id', 'surveys.title')
            ->selectRaw('(
                SELECT COUNT(*) 
                FROM survey_responses 
                WHERE survey_responses.survey_id = surveys.id
                  AND survey_responses.started_at >= ' . "'" . $since . "'" . '
            ) as started_count')
            ->selectRaw('(
                SELECT COUNT(*) 
                FROM survey_submissions 
                WHERE survey_submissions.survey_id = surveys.id
                  AND survey_submissions.created_at >= ' . "'" . $since . "'" . '
            ) as completed_count')
            ->selectRaw('CASE 
                WHEN (
                    SELECT COUNT(*) FROM survey_responses 
                    WHERE survey_responses.survey_id = surveys.id
                      AND survey_responses.started_at >= ' . "'" . $since . "'" . '
                ) > 0 THEN ((
                    (SELECT COUNT(*) FROM survey_responses WHERE survey_responses.survey_id = surveys.id AND survey_responses.started_at >= ' . "'" . $since . "'" . ') -
                    (SELECT COUNT(*) FROM survey_submissions WHERE survey_submissions.survey_id = surveys.id AND survey_submissions.created_at >= ' . "'" . $since . "'" . ')
                )::float / (SELECT COUNT(*) FROM survey_responses WHERE survey_responses.survey_id = surveys.id AND survey_responses.started_at >= ' . "'" . $since . "'" . ')) * 100
                ELSE 0 
            END as drop_off_rate')
            ->whereExists(function ($query) use ($since) {
                $query->selectRaw('1')
                    ->from('survey_responses')
                    ->whereColumn('survey_responses.survey_id', 'surveys.id')
                    ->where('survey_responses.started_at', '>=', $since);
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

    /**
     * Bounce rate series between dates. Bounce defined as started responses without a submission.
     */
    public function getBounceRateSeries(Carbon $from, Carbon $to, ?string $surveyId = null, ?string $ownerId = null, bool $isAdmin = false): array
    {
        $rows = SurveyResponse::query()
            ->leftJoin('survey_submissions', 'survey_submissions.survey_response_id', '=', 'survey_responses.id')
            ->select(DB::raw("DATE(survey_responses.started_at) as date"))
            ->selectRaw('COUNT(*) as started')
            ->selectRaw('COUNT(DISTINCT survey_submissions.id) as completed')
            ->whereBetween('survey_responses.started_at', [$from, $to])
            ->when($surveyId, fn($q) => $q->where('survey_responses.survey_id', $surveyId))
            ->when(!$isAdmin && $ownerId, function ($q) use ($ownerId) {
                $q->join('surveys', 'survey_responses.survey_id', '=', 'surveys.id')
                  ->where('surveys.user_id', $ownerId);
            })
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $series = [];
        $totalStarted = 0; $totalCompleted = 0;
        foreach ($rows as $r) {
            $started = (int)$r->started; $completed = (int)$r->completed;
            $bounce = $started > 0 ? round((($started - $completed) / $started) * 100, 1) : 0.0;
            $series[] = ['date' => $r->date, 'bounce' => $bounce];
            $totalStarted += $started; $totalCompleted += $completed;
        }
        $headline = $totalStarted > 0 ? round((($totalStarted - $totalCompleted) / $totalStarted) * 100, 1) : 0.0;

        return [
            'headline' => $headline,
            'series' => $series,
        ];
    }

    /**
     * Visitor sessions aggregated by browser and device type using UA in survey_responses.device.
     */
    public function getVisitorSessions(Carbon $from, Carbon $to, ?string $surveyId = null, ?string $ownerId = null, bool $isAdmin = false): array
    {
        $responses = SurveyResponse::query()
            ->select('device')
            ->whereBetween('started_at', [$from, $to])
            ->when($surveyId, fn($q) => $q->where('survey_id', $surveyId))
            ->when(!$isAdmin && $ownerId, function ($q) use ($ownerId) {
                $q->whereExists(function ($sub) use ($ownerId) {
                    $sub->selectRaw('1')
                        ->from('surveys')
                        ->whereColumn('surveys.id', 'survey_responses.survey_id')
                        ->where('surveys.user_id', $ownerId);
                });
            })
            ->get();

        $browserCounts = [
            'Chrome' => 0,
            'Safari' => 0,
            'Firefox' => 0,
            'Edge' => 0,
            'Other' => 0,
        ];
        $deviceTypeCounts = [
            'desktop' => 0,
            'mobile' => 0,
            'tablet' => 0,
        ];

        foreach ($responses as $resp) {
            $ua = (string)($resp->device ?? '');
            $browser = 'Other';
            if (stripos($ua, 'Edg') !== false) { $browser = 'Edge'; }
            elseif (stripos($ua, 'Firefox') !== false) { $browser = 'Firefox'; }
            elseif (stripos($ua, 'Chrome') !== false && stripos($ua, 'Chromium') === false && stripos($ua, 'Edg') === false) { $browser = 'Chrome'; }
            elseif (stripos($ua, 'Safari') !== false && stripos($ua, 'Chrome') === false) { $browser = 'Safari'; }
            $browserCounts[$browser]++;

            $type = 'desktop';
            if (stripos($ua, 'iPad') !== false || stripos($ua, 'Tablet') !== false) { $type = 'tablet'; }
            elseif (stripos($ua, 'Mobile') !== false || stripos($ua, 'Android') !== false || stripos($ua, 'iPhone') !== false) { $type = 'mobile'; }
            $deviceTypeCounts[$type]++;
        }

        $total = array_sum($browserCounts);
        return [
            'total' => $total,
            'by_browser' => array_map(fn($name, $value) => ['name' => $name, 'value' => $value], array_keys($browserCounts), $browserCounts),
            'by_device_type' => array_map(fn($name, $value) => ['name' => $name, 'value' => $value], array_keys($deviceTypeCounts), $deviceTypeCounts),
        ];
    }

    /**
     * Audience by country with page views and bounce rate.
     */
    public function getAudienceByCountry(Carbon $from, Carbon $to, ?string $surveyId = null, ?string $ownerId = null, bool $isAdmin = false): array
    {
        // 1) Base aggregation by country (top 10 by views)
        $base = SurveyResponse::query()
            ->leftJoin('survey_submissions', 'survey_submissions.survey_response_id', '=', 'survey_responses.id')
            ->select('survey_responses.country')
            ->selectRaw('COUNT(*) as views')
            ->selectRaw('COUNT(DISTINCT survey_submissions.id) as completed')
            ->whereBetween('survey_responses.started_at', [$from, $to])
            ->when($surveyId, fn($q) => $q->where('survey_responses.survey_id', $surveyId))
            ->when(!$isAdmin && $ownerId, function ($q) use ($ownerId) {
                $q->whereExists(function ($sub) use ($ownerId) {
                    $sub->selectRaw('1')
                        ->from('surveys')
                        ->whereColumn('surveys.id', 'survey_responses.survey_id')
                        ->where('surveys.user_id', $ownerId);
                });
            })
            ->groupBy('survey_responses.country')
            ->orderByDesc('views')
            ->limit(10)
            ->get();

        // 2) Fetch devices once; compute dominant per country in PHP (simple and clear)
        $deviceRows = SurveyResponse::query()
            ->select('country', 'device')
            ->whereBetween('started_at', [$from, $to])
            ->when($surveyId, fn($q) => $q->where('survey_id', $surveyId))
            ->when(!$isAdmin && $ownerId, function ($q) use ($ownerId) {
                $q->whereExists(function ($sub) use ($ownerId) {
                    $sub->selectRaw('1')
                        ->from('surveys')
                        ->whereColumn('surveys.id', 'survey_responses.survey_id')
                        ->where('surveys.user_id', $ownerId);
                });
            })
            ->get();

        $deviceCountsByCountry = [];
        foreach ($deviceRows as $row) {
            $country = $row->country ?: 'Unknown';
            $ua = (string)($row->device ?? '');
            $type = 'desktop';
            if (stripos($ua, 'iPad') !== false || stripos($ua, 'Tablet') !== false) { $type = 'tablet'; }
            elseif (stripos($ua, 'Mobile') !== false || stripos($ua, 'Android') !== false || stripos($ua, 'iPhone') !== false) { $type = 'mobile'; }
            $deviceCountsByCountry[$country] = $deviceCountsByCountry[$country] ?? ['desktop'=>0,'mobile'=>0,'tablet'=>0];
            $deviceCountsByCountry[$country][$type]++;
        }

        $result = [];
        // Instantiate ISO3166 once
        $iso = null;
        try { $iso = new ISO3166(); } catch (\Throwable $e) { $iso = null; }
        foreach ($base as $r) {
            $country = $r->country ?: 'Unknown';
            $views = (int) $r->views;
            $completed = (int) $r->completed;
            $bounce = $views > 0 ? round((($views - $completed) / $views) * 100, 1) : 0.0;

            // Determine dominant device type for this country
            $dominant = 'desktop';
            if (isset($deviceCountsByCountry[$country])) {
                $counts = $deviceCountsByCountry[$country];
                arsort($counts);
                $dominant = (string) array_key_first($counts);
            }

            // Resolve ISO alpha-2 country code using aliases first, then ISO3166
            $countryCode = null;
            if ($country && $country !== 'Unknown') {
                $aliases = [
                    'Korea' => 'KR', // ambiguous -> assume South Korea
                    'Republic of Korea' => 'KR',
                    'South Korea' => 'KR',
                    'Libyan Arab Jamahiriya' => 'LY', // legacy for Libya
                    'British Indian Ocean Territory (Chagos Archipelago)' => 'IO',
                    'United States of America' => 'US',
                    'Viet Nam' => 'VN',
                    'Russian Federation' => 'RU',
                    'Syrian Arab Republic' => 'SY',
                    'Lao People\'s Democratic Republic' => 'LA',
                    'Czech Republic' => 'CZ', // legacy, now Czechia
                    'Congo, Democratic Republic of the' => 'CD',
                    'Congo, Republic of the' => 'CG',
                ];
                if (isset($aliases[$country])) {
                    $countryCode = $aliases[$country];
                }
            }

            if ($iso && !$countryCode && $country && $country !== 'Unknown') {
                try {
                    $rec = $iso->name($country);
                    if (is_array($rec) && isset($rec['alpha2'])) {
                        $countryCode = strtoupper($rec['alpha2']);
                    }
                } catch (\Throwable $e) {
                    // leave null if not resolvable
                }
            }

            $result[] = [
                'country' => $country,
                'country_code' => $countryCode,
                'page_views' => $views,
                'device' => $dominant,
                'bounce_rate' => $bounce,
            ];
        }

        // Defensive: ensure sorted by page_views desc
        usort($result, fn($a, $b) => $b['page_views'] <=> $a['page_views']);
        return array_values($result);
    }

    /**
     * Occupancy/funnel series weekly and daily table.
     */
    public function getOccupancySeries(Carbon $from, Carbon $to, ?string $surveyId = null, ?string $ownerId = null, bool $isAdmin = false): array
    {
        // Weekly composed series
        $weekly = SurveyResponse::query()
            ->leftJoin('survey_submissions', 'survey_submissions.survey_response_id', '=', 'survey_responses.id')
            ->whereBetween('survey_responses.started_at', [$from, $to])
            ->when($surveyId, fn($q) => $q->where('survey_responses.survey_id', $surveyId))
            ->when(!$isAdmin && $ownerId, function ($q) use ($ownerId) {
                $q->whereExists(function ($sub) use ($ownerId) {
                    $sub->selectRaw('1')
                        ->from('surveys')
                        ->whereColumn('surveys.id', 'survey_responses.survey_id')
                        ->where('surveys.user_id', $ownerId);
                });
            })
            ->select(DB::raw("to_char(date_trunc('week', survey_responses.started_at), 'IYYY-IW') as week"))
            ->selectRaw('COUNT(*) as views')
            ->selectRaw('COUNT(DISTINCT survey_submissions.id) as completed')
            ->groupBy('week')
            ->orderBy('week')
            ->get()
            ->map(function ($r) {
                $views = (int)$r->views; $completed = (int)$r->completed; $in_progress = $views - $completed; $dropoffs = $views - $completed;
                return [
                    'week' => $r->week,
                    'views' => $views,
                    'in_progress' => $in_progress,
                    'dropoffs' => $dropoffs,
                    'completed' => $completed,
                ];
            })
            ->values()
            ->all();

        // Daily table
        $daily = SurveyResponse::query()
            ->leftJoin('survey_submissions', 'survey_submissions.survey_response_id', '=', 'survey_responses.id')
            ->whereBetween('survey_responses.started_at', [$from, $to])
            ->when($surveyId, fn($q) => $q->where('survey_responses.survey_id', $surveyId))
            ->when(!$isAdmin && $ownerId, function ($q) use ($ownerId) {
                $q->whereExists(function ($sub) use ($ownerId) {
                    $sub->selectRaw('1')
                        ->from('surveys')
                        ->whereColumn('surveys.id', 'survey_responses.survey_id')
                        ->where('surveys.user_id', $ownerId);
                });
            })
            ->select(DB::raw('DATE(survey_responses.started_at) as date'))
            ->selectRaw('COUNT(*) as views')
            ->selectRaw('COUNT(DISTINCT survey_submissions.id) as completed')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function ($r) {
                $views = (int)$r->views; $completed = (int)$r->completed; $in_progress = $views - $completed; $dropoffs = $views - $completed;
                return [
                    'date' => $r->date,
                    'views' => $views,
                    'in_progress' => $in_progress,
                    'dropoffs' => $dropoffs,
                    'completed' => $completed,
                ];
            })
            ->values()
            ->all();

        return [
            'series' => $weekly,
            'table' => $daily,
        ];
    }

}
