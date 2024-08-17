<?php

namespace App\Repositories\Survey;

use App\Models\Survey\SurveyQuestion;
use Illuminate\Support\Facades\DB;

class SurveyQuestionRepository {

    /**
     * @throws \Exception
     */
    public function index(array $params) {
        try {
            $limit = $params['limit'] ?? 20;
            return DB::transaction(function () use ($limit) {
                return SurveyQuestion::query()->paginate($limit);
            });
        } catch (\Exception $e) {
            throw new \Exception($e, 500);
        }
    }

    public function resolveModel($survey_question): mixed {
        if ($survey_question instanceof SurveyQuestion) {
            return $survey_question;
        }
        return SurveyQuestion::query()->findOrFail($survey_question);
    }

    public function getIfExist($survey_question): mixed {
        return SurveyQuestion::query()->find($survey_question);
    }

    public function update(SurveyQuestion $survey_question, array $params) {
        return DB::transaction(function () use ($params, $survey_question) {
            $survey_question->fill($params);
            $survey_question->save();
            return $survey_question;
        });
    }

    public function store(array $params): SurveyQuestion {
        return DB::transaction(function () use ($params) {
            $survey_question = new SurveyQuestion();
            $survey_question->fill($params);
            $survey_question->save();
            return $survey_question;
        });
    }

    public function delete(SurveyQuestion $survey_question) {
        return DB::transaction(function () use ($survey_question) {
            $survey_question->delete();
            return $survey_question;
        });
    }
}