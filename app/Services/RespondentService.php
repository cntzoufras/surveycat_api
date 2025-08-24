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

    public function index(array $params): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $this->respondent_repository->index($params);
    }

    /**
     * Create a new Respondent and return the persisted model.
     */
    public function store(): mixed
    {
        return $this->respondent_repository->store();
    }

    public function update(Respondent $respondent, array $params): Respondent
    {
        return $this->respondent_repository->update($respondent, $params);
    }

}
