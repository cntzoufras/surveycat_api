<?php

namespace App\Services\Survey;

use App\Models\Survey\SurveyQuestion;
use App\Repositories\Survey\SurveyQuestionRepository;

class SurveyQuestionService {

    protected SurveyQuestionRepository $survey_question_repository;

    public function __construct(SurveyQuestionRepository $survey_question_repository) {
        $this->survey_question_repository = $survey_question_repository;
    }

    /**
     * @throws \Exception
     */
    public function index(array $params) {
        return $this->survey_question_repository->index($params);
    }

    public function store(array $params): SurveyQuestion {
        return $this->survey_question_repository->store($params);
    }

    public function update(SurveyQuestion $survey_question, array $params) {
        $survey_question = $this->survey_question_repository->resolveModel($survey_question);
        return $this->survey_question_repository->update($survey_question, $params);
    }

    public function delete($survey_category) {
        $survey_category = $this->survey_question_repository->resolveModel($survey_category);
        return $this->survey_question_repository->delete($survey_category);
    }

    public function show($params): mixed {
        return $this->survey_question_repository->getIfExist($params);
    }

}