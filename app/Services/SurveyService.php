<?php

namespace App\Services;

use App\Repositories\Survey\Survey\SurveyRepository;

class SurveyService {

    protected $survey_repository;

    public function __construct(SurveyRepository $survey_repository) {
        $this->survey_repository = $survey_repository;
    }

    public function index(array $params) {
        return $this->survey_repository->index($params);
    }

    public function store(array $params) {
        return $this->survey_repository->store($params);
    }

    public function update($question_id, array $params) {
        $question = $this->survey_repository->resolveModel($question_id);
        return $this->survey_repository->update($question, $params);
    }

    public function delete($question_id) {
        $question = $this->survey_repository->resolveModel($question_id);
        return $this->survey_repository->delete($question);
    }

    public function show($params) {
        return $this->survey_repository->getIfExist($params);
    }

}