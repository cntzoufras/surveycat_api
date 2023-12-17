<?php

namespace App\Repositories\Survey;

use App\Models\Survey\SurveyResponse;
use Illuminate\Support\Facades\DB;

class SurveyResponseRepository {

    public function index(array $params) {
        try {
            $limit = $params['limit'] ?? 50;
            return DB::transaction(function () use ($limit) {
                return SurveyResponse::query()->paginate($limit);
            });
        } catch (\Exception $e) {
            throw new \Exception($e, 500);
        }
    }

    public function resolveModel($survey_response): mixed {
        if ($survey_response instanceof SurveyResponse) {
            return $survey_response;
        }
        return SurveyResponse::query()->findOrFail($survey_response);
    }

    public function getIfExist($survey_response): mixed {
        return SurveyResponse::query()->find($survey_response);
    }

    public function update(SurveyResponse $survey_response, array $params) {
        return DB::transaction(function () use ($params, $survey_response) {
            $survey_response->fill($params);
            $survey_response->save();
            return $survey_response;
        });
    }

    public function store(array $params): SurveyResponse {
        return DB::transaction(function () use ($params) {
            $survey_response = new SurveyResponse();
            $survey_response->fill($params);
            isset($params['respondent_ip']) ? ($survey_response->ip_address = $params['respondent_ip']) : '';
            isset($params['respondent_id']) ? ($survey_response->respondent_id = $params['respondent_id']) : '';
            $survey_response->save();
            return $survey_response;
        });
    }
}