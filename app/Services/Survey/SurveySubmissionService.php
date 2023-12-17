<?php

namespace App\Services\Survey;

use App\Models\Survey\SurveySubmission;
use App\Repositories\Survey\SurveySubmissionRepository;
use Illuminate\Contracts\Auth\Access\Authorizable;

class SurveySubmissionService {

    private SurveySubmissionRepository $survey_submissions_repository;

    public function __construct(SurveySubmissionRepository $survey_submissions_repository) {
//        $this->authorizable = $authorizable;
        $this->survey_submissions_repository = $survey_submissions_repository;
    }

    public function index(array $params) {
//        $this->authorizable->authorize('index', $params);

        return $this->survey_submissions_repository->index($params);
    }

    public function store(array $params): SurveySubmission {
        return $this->survey_submissions_repository->store($params);
    }

    public function show($params): mixed {
        return $this->survey_submissions_repository->getIfExist($params);
    }

    public function isUniqueSubmission($respondent_id, $survey_response_id): bool {
        return $this->survey_submissions_repository->isUniqueSubmission($respondent_id, $survey_response_id);
    }
}