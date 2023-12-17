<?php

namespace App\Repositories\Survey;

use App\Models\Survey\SurveySubmission;
use Illuminate\Support\Facades\DB;

class SurveySubmissionRepository {

    public function index(array $params) {
        try {
            $limit = $params['limit'] ?? 50;
            return DB::transaction(function () use ($limit) {
                return SurveySubmission::query()->paginate($limit);
            });
        } catch (\Exception $e) {
            throw new \Exception($e, 500);
        }
    }

    public function resolveModel($survey_submission): mixed {
        if ($survey_submission instanceof SurveySubmission) {
            return $survey_submission;
        }
        return SurveySubmission::query()->findOrFail($survey_submission);
    }

    public function getIfExist($survey_submission): mixed {
        return SurveySubmission::query()->find($survey_submission);
    }

    public function update(SurveySubmission $survey_submission, array $params) {
        return DB::transaction(function () use ($params, $survey_submission) {
            $survey_submission->fill($params);
            $survey_submission->save();
            return $survey_submission;
        });
    }

    public function store(array $params): SurveySubmission {
        return DB::transaction(function () use ($params) {
            $survey_submission = new SurveySubmission();
            $survey_submission->fill($params);
            $survey_submission->save();
            return $survey_submission;
        });
    }

    public function isUniqueSubmission($respondent_id, $survey_response_id): bool {
        return !SurveySubmission::query()
                                ->where('respondent_id', $respondent_id)
                                ->where('survey_response_id', $survey_response_id)
                                ->exists();
    }

}