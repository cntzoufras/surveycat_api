<?php

namespace App\Repositories\Survey;

use App\Models\Survey\Survey;
use App\Models\Survey\SurveySubmission;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SurveySubmissionRepository
{

    public function index(array $params)
    {
        try {
            $perPage = $params['per_page'] ?? ($params["limit"] ?? 10);
            $page = $params['page'] ?? null;
            $search = isset($params['search']) ? trim((string)$params['search']) : '';

            /** @var \App\Models\User|null $user */
            $user = Auth::user();
            $ownerId = Auth::id();
            $isAdmin = $user && method_exists($user, 'isAdmin') && $user->isAdmin();

            return DB::transaction(function () use ($perPage, $page, $search, $ownerId, $isAdmin) {
                $query = SurveySubmission::query()
                    ->with([
                        'survey_response.survey',
                        'survey_response.respondent',
                    ])
                    // Scope to submissions where the parent survey belongs to the authenticated user (unless admin)
                    ->when(!$isAdmin, function ($q) use ($ownerId) {
                        $q->whereExists(function ($sub) use ($ownerId) {
                            $sub->selectRaw('1')
                                ->from('surveys')
                                ->whereColumn('surveys.id', 'survey_submissions.survey_id')
                                ->where('surveys.user_id', $ownerId);
                        });
                    });

                if ($search !== '') {
                    $driver = DB::getDriverName();
                    $likeOperator = $driver === 'pgsql' ? 'ilike' : 'like';

                    $query->where(function ($q) use ($search, $likeOperator) {
                        $q->orWhereHas('survey_response.survey', function ($sq) use ($search, $likeOperator) {
                            $sq->where('title', $likeOperator, "%$search%");
                        });
                        $q->orWhereHas('survey_response.respondent', function ($rq) use ($search, $likeOperator) {
                            $rq->where('email', $likeOperator, "%$search%");
                        });
                        // Allow string (partial) matching on respondent UUIDs
                        $q->orWhereHas('survey_response.respondent', function ($rrqlike) use ($search, $likeOperator) {
                            $rrqlike->where('id', $likeOperator, "%$search%");
                        });
                        if (\Ramsey\Uuid\Uuid::isValid($search)) {
                            $q->orWhereHas('survey_response', function ($rsq) use ($search) {
                                $rsq->where('id', '=', $search);
                            });
                            // Allow searching by respondent UUID (exact match)
                            $q->orWhereHas('survey_response.respondent', function ($rrq) use ($search) {
                                $rrq->where('id', '=', $search);
                            });
                            $q->orWhere('id', '=', $search);
                        }
                        $q->orWhereHas('survey_response', function ($rsq) use ($search, $likeOperator) {
                            $rsq->where('device', $likeOperator, "%$search%")
                                ->orWhere('country', $likeOperator, "%$search%");
                        });
                    });
                }

                if ($page !== null) {
                    return $query->paginate($perPage, ['*'], 'page', (int)$page);
                }

                return $query->paginate($perPage);
            });
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 500); // fix message extraction
        }
    }

    public function resolveModel($survey_submission): mixed
    {
        if ($survey_submission instanceof SurveySubmission) {
            return $survey_submission;
        }
        return SurveySubmission::query()->findOrFail($survey_submission);
    }

    public function getIfExist($survey_submission): mixed
    {
        return SurveySubmission::with([
            'survey_response.survey.survey_pages.survey_questions.survey_question_choices',
            'survey_response.respondent',
        ])->find($survey_submission);
    }

    public function update(SurveySubmission $survey_submission, array $params)
    {
        return DB::transaction(function () use ($params, $survey_submission) {
            $survey_submission->fill($params);
            $survey_submission->save();
            return $survey_submission;
        });
    }

    public function store(array $params): SurveySubmission
    {
        return DB::transaction(function () use ($params) {
            $survey_submission = new SurveySubmission();
            $survey_submission->fill($params);
            $survey_submission->save();
            return $survey_submission;
        });
    }

    public function isUniqueSubmission($respondent_id, $survey_response_id): bool
    {
        return !SurveySubmission::query()
            ->where('respondent_id', $respondent_id)
            ->where('survey_response_id', $survey_response_id)
            ->exists();
    }

    public function getSurveySubmissionsCountForUser(string $user_id): int
    {
        // This efficiently counts submissions by checking if their parent 'survey'
        // has a 'user_id' that matches the one provided.
        return SurveySubmission::whereHas('survey', function ($query) use ($user_id) {
            $query->where('user_id', $user_id);
        })->count();
    }

}
