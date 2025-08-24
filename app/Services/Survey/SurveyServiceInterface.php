<?php

namespace App\Services\Survey;

use App\Models\Survey\Survey;
use Illuminate\Database\Eloquent\Collection;

interface SurveyServiceInterface {

    /**
     * Get a list of surveys based on parameters.
     *
     * @param array $params
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index(array $params): \Illuminate\Contracts\Pagination\LengthAwarePaginator;

    /**
     * Store a new survey.
     *
     * @param array $params
     *
     * @return Survey
     */
    public function store(array $params): Survey;

    /**
     * Update a survey with new data.
     *
     * @param mixed $survey
     * @param array $params
     *
     * @return Survey
     */
    public function update(Survey|string $survey, array $params): Survey;

    /**
     * Delete a survey by its ID.
     *
     * @param int $survey_id
     *
     * @return bool
     */
    public function destroy(string $survey_id): Survey;

    /**
     * Show a survey based on parameters.
     *
     * @param string $id
     *
     * @return Survey|null
     */
    public function show(string $id): ?Survey;

    /**
     * Get a list of stock surveys.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getStockSurveys(): \Illuminate\Contracts\Pagination\LengthAwarePaginator;

    /**
     * Publish a survey.
     *
     * @param int $survey_id
     * @param array $params
     *
     * @return Survey
     */
    public function publish(string $survey_id, array $params): Survey;

    /**
     * Update the public link for a survey.
     *
     * @param string $title
     *
     * @return string
     */
    public function updatePublicLink(string $title): string;

    /**
     * Get surveys for the authenticated user.
     *
     * @return Collection
     */
    public function getSurveysForUser(string $user_id): Collection;

    /**
     * Get all surveys with their associated themes and pages.
     *
     * @return Collection
     */
    public function getSurveysWithThemesAndPages(): Collection;

    /**
     * Get the public survey with all data, themes, pages, questions.
     *
     * @return Survey
     */
    public function getPublicSurveyBySlug(string $slug): Survey;

}
