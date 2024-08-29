<?php

namespace App\Repositories\Survey;

use App\Models\Survey\SurveyQuestion;
use App\Models\Survey\SurveyQuestionChoice;
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

    public function delete($survey_question) {
        return DB::transaction(function () use ($survey_question) {
            $survey_question->delete();
            return $survey_question;
        });
    }

    public function getQuestionsByPage($survey_id, $survey_page_id) {
        try {
            return DB::transaction(function () use ($survey_id, $survey_page_id) {
                return SurveyQuestion::where('survey_page_id', $survey_page_id)
                                     ->whereHas('survey_page', function ($query) use ($survey_id) {
                                         $query->where('survey_id', $survey_id);
                                     })
                                     ->get();
            });
        } catch (\Exception $e) {
            throw new \Exception($e, 500);
        }
    }

    public function getSurveyQuestionsWithChoices($survey_id) {
        try {
            return DB::transaction(function () use ($survey_id) {
                return SurveyQuestion::with('survey_question_choices')
                                     ->whereHas('survey_page', function ($query) use ($survey_id) {
                                         $query->where('survey_id', $survey_id);
                                     })
                                     ->get();
            });
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 500);
        }
    }


}