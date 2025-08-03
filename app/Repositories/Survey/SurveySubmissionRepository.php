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
            $limit = $params['limit'] ?? 1000;

            return DB::transaction(function () use ($limit) {
                return SurveySubmission::with([
                    'survey_response.survey',
                    'survey_response.respondent',
                ])->paginate($limit);
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
