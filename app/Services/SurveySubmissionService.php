<?php

namespace App\Services;

use AllowDynamicProperties;
use App\Repositories\Survey\Survey\SurveySubmissionRepository;
use Illuminate\Contracts\Auth\Access\Authorizable;

#[AllowDynamicProperties] class SurveySubmissionService {

    protected $survey_submissions_repository;

    public function __construct(SurveySubmissionRepository $survey_submissions_repository, Authorizable $authorizable) {
        $this->authorizable = $authorizable;
        $this->survey_submissions_repository = $survey_submissions_repository;
    }

    public function index(array $params) {
        $this->authorizable->authorize('index', $params);

        return $this->survey_submissions_repository->index($params);
    }

    public function store(array $params) {
        return $this->survey_submissions_repository->store($params);
    }

    public function update($question_id, array $params) {
        $question = $this->survey_submissions_repository->resolveModel($question_id);
        return $this->survey_submissions_repository->update($question, $params);
    }

    public function delete($question_id) {
        $question = $this->survey_submissions_repository->resolveModel($question_id);
        return $this->survey_submissions_repository->delete($question);
    }

    public function show($params) {
        return $this->survey_submissions_repository->getIfExist($params);
    }

}