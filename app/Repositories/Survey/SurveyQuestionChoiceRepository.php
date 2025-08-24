<?php

namespace App\Repositories\Survey;

use App\Models\Survey\SurveyQuestionChoice;
use Illuminate\Support\Facades\DB;

class SurveyQuestionChoiceRepository {

    /**
     * @throws \Exception
     */
    public function index(array $params): \Illuminate\Contracts\Pagination\LengthAwarePaginator {
        try {
            $limit = $params['limit'] ?? 20;
            return DB::transaction(function () use ($limit) {
                return SurveyQuestionChoice::query()->paginate($limit);
            });
        } catch (\Exception $e) {
            throw new \Exception($e, 500);
        }
    }

    public function resolveModel($survey_question_choice): \App\Models\Survey\SurveyQuestionChoice {
        if ($survey_question_choice instanceof SurveyQuestionChoice) {
            return $survey_question_choice;
        }
        return SurveyQuestionChoice::query()->findOrFail($survey_question_choice);
    }

    public function getIfExist($survey_question_choice): ?\App\Models\Survey\SurveyQuestionChoice {
        return SurveyQuestionChoice::query()->find($survey_question_choice);
    }

    public function update(SurveyQuestionChoice $survey_question_choice, array $params): \App\Models\Survey\SurveyQuestionChoice {
        return DB::transaction(function () use ($params, $survey_question_choice) {
            $survey_question_choice->fill($params);
            $survey_question_choice->save();
            return $survey_question_choice;
        });
    }

    public function store(array $params): \App\Models\Survey\SurveyQuestionChoice {
        return DB::transaction(function () use ($params) {
            $survey_question_choice = new SurveyQuestionChoice();
            $survey_question_choice->fill($params);
            $survey_question_choice->save();
            return $survey_question_choice;
        });
    }

    public function storeMultiple(array $question_choices): array {
        return DB::transaction(function () use ($question_choices) {
            $createdChoices = [];
            foreach ($question_choices as $params) {
                $survey_question_choice = new SurveyQuestionChoice();
                $survey_question_choice->fill($params);
                $survey_question_choice->save();
                $createdChoices[] = $survey_question_choice;
            }
            return $createdChoices;
        });
    }

    public function delete(SurveyQuestionChoice $survey_question_choice): \App\Models\Survey\SurveyQuestionChoice {
        return DB::transaction(function () use ($survey_question_choice) {
            $survey_question_choice->delete();
            return $survey_question_choice;
        });
    }

    /**
     * @param $survey_question_id
     *
     * @return mixed
     * @throws \Exception
     */
    public function getSurveyQuestionChoicesByQuestion($survey_question_id): \Illuminate\Database\Eloquent\Collection {
        try {
            return DB::transaction(function () use ($survey_question_id) {
                return SurveyQuestionChoice::where('survey_question_id', $survey_question_id)->get();
            });
        } catch (\Exception $e) {
            throw new \Exception($e, 500);
        }
    }


}