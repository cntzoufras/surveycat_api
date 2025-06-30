<?php

namespace App\Services\Survey;

use App\Models\Survey\SurveyQuestion;
use App\Repositories\Survey\QuestionTypeRepository;

class QuestionTypeService
{

    protected QuestionTypeRepository $question_type_repository;

    public function __construct(QuestionTypeRepository $question_type_repository)
    {
        $this->question_type_repository = $question_type_repository;
    }

    /**
     * @throws \Exception
     */
    public function index(array $params)
    {
        return $this->question_type_repository->index($params);
    }

}
