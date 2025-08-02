<?php

namespace App\Services\Survey;

use App\Exceptions\SurveyNotEditableException;
use App\Models\Survey\Survey;
use App\Models\Survey\SurveySettings;
use App\Repositories\Survey\SurveyRepositoryInterface;
use App\Repositories\Survey\SurveySettingsRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Collection;

class SurveyService implements SurveyServiceInterface
{

    protected SurveyRepositoryInterface $survey_repository;
    protected SurveySettingsRepository $survey_settings_repository;

    public function __construct(SurveyRepositoryInterface $survey_repository, SurveySettingsRepository $survey_settings_repository)
    {
        $this->survey_repository = $survey_repository;
        $this->survey_settings_repository = $survey_settings_repository;
    }

    /**
     * @throws \Exception
     */
    public function index(array $params)
    {
        return $this->survey_repository->index($params);
    }

    public function store(array $params): Survey
    {
        $survey = $this->survey_repository->store($params);

        $settings = SurveySettings::getDefaultSettings();
        $settings['survey_id'] = $survey->id;

        $new_settings = $this->survey_settings_repository->store($settings);

        $survey->setRelation('survey_settings', $new_settings);

        return $survey;
    }

    /**
     * @throws \Exception
     */
    public function update($survey, array $params)
    {
        $survey = $this->survey_repository->resolveModel($survey);
        return $this->survey_repository->update($survey, $params);
    }


    public function destroy($survey_id)
    {
        $survey = $this->survey_repository->resolveModel($survey_id);
        return $this->survey_repository->destroy($survey);
    }

    public function show(string $id): ?Survey
    {
        return $this->survey_repository->getIfExist($id);
    }

    public function getStockSurveys()
    {
        return $this->survey_repository->getStockSurveys();
    }

    public function publish($survey_id, array $params): Survey
    {
        $survey = $this->survey_repository->resolveModel($survey_id);
        $params['public_link'] = $this->updatePublicLink($survey->title);

        if (!empty($survey->title) && $survey->survey_status->title !== 'PUBLISHED') {
            $params['survey_status_id'] = 2;
        } else {
            throw new \InvalidArgumentException('Survey title is required to create a public link.');
        }

        return $this->survey_repository->update($survey, $params);
    }

    public function preview($survey_id, array $params): Survey
    {
        $survey = $this->survey_repository->resolveModel($survey_id);

        if (empty($survey->title)) {
            throw new \InvalidArgumentException('Survey title is required to create a public link.');
        }

        $params['public_link'] = $this->updatePublicLink($survey->title);


        if ($survey->survey_status->title !== 'PUBLISHED') {
            $params['survey_status_id'] = 3;
        }

        $survey = $this->survey_repository->update($survey, $params);

        return $this->getSurveyPreview($survey->id);
    }


    public function updatePublicLink($title): string
    {
        $slug = Str::slug($title);
        $url = "{$slug}-" . uniqid();
        return $url;
    }

    /**
     * @throws \App\Exceptions\SurveyNotEditableException
     */
    protected function disableUpdatesOnPublishedSurvey($survey, $data): void
    {
        if ($survey->status === 'published' && isset($data['status']) && $data['status'] !== 'draft') {
            throw new SurveyNotEditableException('Survey is published and cannot be edited unless reverted to draft.');
        }
    }

    /**
     * Get surveys for the authenticated user.
     *
     * @return Collection
     */
    public function getSurveysForUser(string $user_id): Collection
    {
        return $this->survey_repository->getSurveysForUser($user_id);
    }

    /**
     * Get all surveys with their associated themes and pages.
     *
     * @return Collection
     */
    public function getSurveysWithThemesAndPages(): Collection
    {
        return $this->survey_repository->getSurveysWithThemesAndPages();
    }

    public function getSurveyWithDetails($survey_id): Survey
    {
        return $this->survey_repository->getSurveyWithDetails($survey_id);
    }

    public function getSurveyPreview($survey_id): Survey
    {
        return $this->survey_repository->getSurveyPreview($survey_id);
    }

    public function getPublicSurveyBySlug($slug): Survey
    {
        return $this->survey_repository->getPublicSurveyBySlug($slug);
    }

    public function getProfileSurveyCountsForUser(string $user_id): \stdClass
    {
        return $this->survey_repository->getProfileSurveyCountsForUser($user_id);
    }

}
