<?php

namespace App\Services;

use App\Repositories\Survey\Survey\SurveyQuestionRepository;

class SurveyQuestionService {

    protected $survey_question_repository;

    public function __construct(SurveyQuestionRepository $survey_question_repository) {
        $this->survey_question_repository = $survey_question_repository;
    }

    /**
     * @throws \Exception
     */
    public function index(array $params) {

        return $this->survey_question_repository->index($params);
    }

    public function store(array $params) {
        return $this->survey_question_repository->store($params);
    }

    public function update($survey_category, array $params) {

        $survey_category = $this->survey_question_repository->resolveModel($survey_category);
        return $this->survey_question_repository->update($survey_category, $params);
    }

    public function delete($survey_category) {
        $survey_category = $this->survey_question_repository->resolveModel($survey_category);
        return $this->survey_question_repository->delete($survey_category);
    }

    public function show($params) {
        return $this->survey_question_repository->resolveModel($params);
    }

}