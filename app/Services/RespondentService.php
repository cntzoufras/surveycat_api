<?php

namespace App\Services;

use App\Models\Respondent;
use App\Repositories\RespondentRepository;
use Ramsey\Uuid\Uuid;

class RespondentService
{

    protected RespondentRepository $respondent_repository;

    public function __construct(RespondentRepository $respondent_repository)
    {
        $this->respondent_repository = $respondent_repository;
    }

    public function index(array $params): mixed
    {
        return $this->respondent_repository->index($params);
    }

    /**
     * @return mixed
     */
    public function store(): mixed
    {
        return $this->respondent_repository->store();
    }

    public function show($params): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|\App\Models\Respondent|\Illuminate\Database\Eloquent\Builder|array|null
    {
        return $this->respondent_repository->resolveModel($params);
    }

    public function update(Respondent $respondent, array $params)
    {
        return $this->respondent_repository->update($respondent, $params);
    }

}
