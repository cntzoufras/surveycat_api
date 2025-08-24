<?php

namespace App\Services\Survey;

use App\Models\Survey\SurveyQuestionChoice;
use App\Repositories\Survey\SurveyQuestionChoiceRepository;

class SurveyQuestionChoiceService {

    protected SurveyQuestionChoiceRepository $survey_question_choice_repository;

    public function __construct(SurveyQuestionChoiceRepository $survey_question_choice_repository) {
        $this->survey_question_choice_repository = $survey_question_choice_repository;
    }

    /**
     * @throws \Exception
     */
    public function index(array $params): \Illuminate\Contracts\Pagination\LengthAwarePaginator {
        return $this->survey_question_choice_repository->index($params);
    }

    public function store(array $params): SurveyQuestionChoice {
        return $this->survey_question_choice_repository->store($params);
    }

    public function storeMultiple(array $question_choices): array {
        return $this->survey_question_choice_repository->storeMultiple($question_choices);
    }


    public function show($params): ?SurveyQuestionChoice {
        return $this->survey_question_choice_repository->getIfExist($params);
    }

    public function update(SurveyQuestionChoice $survey_question_choice, array $params): SurveyQuestionChoice {
        $survey_question_choice = $this->survey_question_choice_repository->resolveModel($survey_question_choice);
        return $this->survey_question_choice_repository->update($survey_question_choice, $params);
    }

    public function delete($survey_question_choice): SurveyQuestionChoice {
        $survey_question_choice = $this->survey_question_choice_repository->resolveModel($survey_question_choice);
        return $this->survey_question_choice_repository->delete($survey_question_choice);
    }

    public function getSurveyQuestionChoicesByQuestion($survey_question_id): \Illuminate\Database\Eloquent\Collection {
        return $this->survey_question_choice_repository->getSurveyQuestionChoicesByQuestion($survey_question_id);
    }

}