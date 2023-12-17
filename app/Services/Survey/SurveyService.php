<?php

namespace App\Services\Survey;

use App\Models\Survey\Survey;
use App\Repositories\Survey\SurveyRepository;
use Illuminate\Support\Str;

class SurveyService {

    protected SurveyRepository $survey_repository;

    public function __construct(SurveyRepository $survey_repository) {
        $this->survey_repository = $survey_repository;
    }

    /**
     * @throws \Exception
     */
    public function index(array $params) {
        return $this->survey_repository->index($params);
    }

    /**
     * @throws \Exception
     */
    public function getAllTemplates($params) {
        return $this->survey_repository->getAllTemplates($params);
    }

    public function store(array $params): Survey {
        $params['public_link'] = $this->createPublicLink($params['title']);
        return $this->survey_repository->store($params);
    }

    public function saveAsTemplate(array $params): Survey {
        return $this->survey_repository->saveAsTemplate($params);
    }

    public function update($question_id, array $params) {
        $question = $this->survey_repository->resolveModel($question_id);
        return $this->survey_repository->update($question, $params);
    }

    public function updateTemplate($question_id, array $params) {
        $question = $this->survey_repository->resolveModel($question_id);
        return $this->survey_repository->update($question, $params);
    }

    public function delete($question_id) {
        $question = $this->survey_repository->resolveModel($question_id);
        return $this->survey_repository->delete($question);
    }

    public function deleteTemplate($question_id) {
        $question = $this->survey_repository->resolveModel($question_id);
        return $this->survey_repository->delete($question);
    }

    public function show($params) {
        return $this->survey_repository->getIfExist($params);
    }

    public function getTemplate($params) {
        return $this->survey_repository->getTemplate($params);
    }


    protected function createPublicLink($title): string {
        $slug = Str::slug($title);
        $milliseconds = round(microtime(true) * 1000);
        $timePortion = substr($milliseconds, 2, 4);
        $randomString = Str::random(4);
        return "{$slug}-{$timePortion}-{$randomString}";
    }


}