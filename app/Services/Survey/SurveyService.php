<?php

namespace App\Services\Survey;

use App\Exceptions\SurveyNotEditableException;
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

    public function store(array $params): Survey {
        return $this->survey_repository->store($params);
    }

    /**
     * @throws \Exception
     */
    public function update($survey, array $params) {
        $survey = $this->survey_repository->resolveModel($survey);
        return $this->survey_repository->update($survey, $params);
    }


    public function destroy($survey_id) {
        $survey = $this->survey_repository->resolveModel($survey_id);
        return $this->survey_repository->destroy($survey);
    }

    public function show($params) {
        return $this->survey_repository->getIfExist($params);
    }

    public function getStockSurveys() {
        return $this->survey_repository->getStockSurveys();
    }

    public function publish($survey_id, array $params) {
        $survey = $this->survey_repository->resolveModel($survey_id);

        if (!empty($params['title'])) {
            $params['public_link'] = $this->updatePublicLink($params['title'], $survey_id);
        } else {
            // Handle the case where title is not set or is empty
            throw new \InvalidArgumentException('Title is required to create a public link.');
        }

        // Set the survey as published
        $params['is_published'] = true;

        return $this->survey_repository->update($survey, $params);
    }


    protected function updatePublicLink($title): string {
        $slug = Str::slug($title);
        return "{$slug}-" . uniqid();
    }

    /**
     * @throws \App\Exceptions\SurveyNotEditableException
     */
    protected function disableUpdatesOnPublishedSurvey($survey, $data): void {
        if ($survey->status === 'published' && isset($data['status']) && $data['status'] !== 'draft') {
            throw new SurveyNotEditableException('Survey is published and cannot be edited unless reverted to draft.');
        }
    }
}