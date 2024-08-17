<?php

namespace App\Repositories\Survey;

use App\Models\Survey\Survey;
use App\Models\Survey\SurveyQuestion;
use App\Models\Survey\SurveyTemplate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SurveyRepository {

    public function index(array $params) {
        try {
            $limit = $params['limit'] ?? 50;
            return DB::transaction(function () use ($limit) {
                return Survey::query()->paginate($limit);
            });
        } catch (\Exception $e) {
            throw new \Exception($e, 500);
        }
    }

    /**
     * @throws \Exception
     */
    public function getStockSurveys() {
        try {
            return DB::transaction(function () {
                return Survey::query()->where('is_stock', '=', true)->paginate();
            });
        } catch (\Exception $e) {
            throw new \Exception($e, 500);
        }
    }

    public function getAllTemplates($params) {
        try {
            $limit = $params['limit'] ?? 50;
            return DB::transaction(function () use ($limit) {
                return Survey::query()->paginate($limit);
            });
        } catch (\Exception $e) {
            throw new \Exception($e, 500);
        }
    }

    public function resolveModel($surveys) {
        if ($surveys instanceof Survey) {
            return $surveys;
        }
        return Survey::query()->findOrFail($surveys);
    }

    public function getIfExist($survey) {
        return Survey::query()->find($survey);
    }

    public function getTemplate($survey_templates) {
        if ($survey_templates instanceof SurveyTemplate) {
            return $survey_templates;
        }
        return Survey::query()->findOrFail($survey_templates);
    }

    public function update(Survey $survey, array $params) {
        return DB::transaction(function () use ($params, $survey) {
            $survey->fill($params);
            $survey->save();
            return $survey;
        });
    }

    public function updateTemplate(Survey $survey, array $params) {
        return DB::transaction(function () use ($params, $survey) {
            $survey->fill($params);
            $survey->save();
            return $survey;
        });
    }

    public function store(array $params): Survey {
        return DB::transaction(function () use ($params) {
            $survey = new Survey();
            $survey->fill($params);
            $survey->save();
            return $survey;
        });
    }

    public function saveAsTemplate(array $params): Survey {
        return DB::transaction(function () use ($params) {
            $survey = new Survey();
            $survey->fill($params);
            $survey->save();
            return $survey;
        });
    }

    public function destroy(Survey $survey) {
        return DB::transaction(function () use ($survey) {
            $survey->delete();
            return $survey;
        });
    }

    private function storeSurveyQuestions(Survey $survey, array $questions): void {
        foreach ($questions as $questionData) {

            $question = new SurveyQuestion();

            $question->title = $questionData['title'];
            $question->is_required = $questionData['is_required'];
            $question->question_type_id = $questionData['question_type_id'];
            $question->survey_page_id = $questionData['survey_page_id'];
            $question->additional_settings = $questionData['additional_settings'];

            $question->save();
        }
    }

}