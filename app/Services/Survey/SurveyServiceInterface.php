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
    public function index(array $params);

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
    public function update($survey, array $params);

    /**
     * Delete a survey by its ID.
     *
     * @param int $survey_id
     *
     * @return bool
     */
    public function destroy($survey_id);

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
    public function getStockSurveys();

    /**
     * Publish a survey.
     *
     * @param int $survey_id
     * @param array $params
     *
     * @return Survey
     */
    public function publish(string $survey_id, array $params);

    /**
     * Update the public link for a survey.
     *
     * @param string $title
     *
     * @return string
     */
    public function updatePublicLink($title): string;

    /**
     * Get surveys for the authenticated user.
     *
     * @return Collection
     */
    public function getSurveysForUser(string $user_id): Collection;

}
