<?php

namespace App\Repositories\Survey;

use App\Models\Survey\Survey;
use Illuminate\Database\Eloquent\Collection;

interface SurveyRepositoryInterface {

    /**
     * Get a paginated list of surveys based on parameters.
     *
     * @param array $params
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index(array $params): \Illuminate\Contracts\Pagination\LengthAwarePaginator;

    /**
     * Get stock surveys (those marked as stock).
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getStockSurveys(): \Illuminate\Contracts\Pagination\LengthAwarePaginator;

    /**
     * Resolve a survey model from its ID or instance.
     *
     * @param mixed $surveys
     *
     * @return Survey
     */
    public function resolveModel(Survey|string $surveys): Survey;

    /**
     * Get a survey if it exists.
     *
     * @param int $survey
     *
     * @return Survey|null
     */
    public function getIfExist(string $survey): ?Survey;

    /**
     * Update a survey with new data.
     *
     * @param Survey $survey
     * @param array $params
     *
     * @return Survey
     */
    public function update(Survey $survey, array $params): Survey;

    /**
     * Store a new survey.
     *
     * @param array $params
     *
     * @return Survey
     */
    public function store(array $params): Survey;

    /**
     * Delete a survey.
     *
     * @param Survey $survey
     *
     * @return bool
     */
    public function destroy(Survey $survey): Survey;

    /**
     * Get surveys for a specific user.
     *
     * @param string $user_id
     *
     * @return \Illuminate\Database\Eloquent\Collection|Survey[]
     */
    public function getSurveysForUser(string $user_id): Collection;

    /**
     * Get all surveys with their associated themes and pages.
     *
     * @return Collection
     */
    public function getSurveysWithThemesAndPages(): Collection;

    /**
     * Get Survey with themes, pages, categories
     *
     * @return Collection
     */
    public function getSurveyWithDetails(string $survey_id): Survey;

    /**
     * Get Survey with themes, pages, categories
     *
     * @return Survey
     */
    public function getPublicSurveyBySlug(string $slug): Survey;

}
