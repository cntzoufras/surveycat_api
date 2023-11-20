<?php

namespace App\Services;

use App\Repositories\RespondentRepository;

class RespondentService {

    protected RespondentRepository $respondent_repository;

    public function __construct(RespondentRepository $respondent_repository) {
        $this->respondent_repository = $respondent_repository;
    }

    public function index(array $params): mixed {

        return $this->respondent_repository->index($params);
    }

    public function store(array $params): \App\Models\Respondent {
        return $this->respondent_repository->store($params);
    }

    public function update($survey_category, array $params): mixed {

        $survey_category = $this->respondent_repository->resolveModel($survey_category);
        return $this->respondent_repository->update($survey_category, $params);
    }

    public function delete($survey_category): mixed {
        $survey_category = $this->respondent_repository->resolveModel($survey_category);
        return $this->respondent_repository->delete($survey_category);
    }

    public function show($params): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|\App\Models\Respondent|\Illuminate\Database\Eloquent\Builder|array|null {
        return $this->respondent_repository->resolveModel($params);
    }

}