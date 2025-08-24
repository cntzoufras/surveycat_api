<?php

namespace App\Services\Survey;

use App\Models\Survey\SurveyPage;
use App\Models\Survey\SurveyQuestion;
use App\Repositories\Survey\SurveyQuestionRepository;
use Illuminate\Support\Collection;

class SurveyQuestionService
{

    protected SurveyQuestionRepository $survey_question_repository;

    public function __construct(SurveyQuestionRepository $survey_question_repository)
    {
        $this->survey_question_repository = $survey_question_repository;
    }

    /**
     * @throws \Exception
     */
    public function index(array $params): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $this->survey_question_repository->index($params);
    }

    public function store(array $params): SurveyQuestion
    {
        return $this->survey_question_repository->store($params);
    }

    public function update(SurveyQuestion $survey_question, array $params): SurveyQuestion
    {
        $survey_question = $this->survey_question_repository->resolveModel($survey_question);
        return $this->survey_question_repository->update($survey_question, $params);
    }

    public function delete($survey_question): SurveyQuestion
    {
        $survey_question = $this->survey_question_repository->resolveModel($survey_question);
        return $this->survey_question_repository->delete($survey_question);
    }

    public function show($params): ?SurveyQuestion
    {
        return $this->survey_question_repository->getIfExist($params);
    }

    public function getSurveyQuestionsByPage($survey_id, $page_id): \Illuminate\Database\Eloquent\Collection
    {
        return $this->survey_question_repository->getQuestionsByPage($survey_id, $page_id);
    }

    public function getSurveyQuestionsWithChoices($survey_id): \Illuminate\Database\Eloquent\Collection
    {
        return $this->survey_question_repository->getSurveyQuestionsWithChoices($survey_id);
    }

    public function updateOrder(SurveyPage $survey_page, array $questions): Collection
    {
        return $this->survey_question_repository->updateOrder($survey_page, $questions);
    }


}
