<?php

namespace App\Repositories;

use App\Models\Respondent;

use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class RespondentRepository
{

    public function index(array $params)
    {
        try {
            $perPage = $params['per_page'] ?? ($params['limit'] ?? 10);
            $page = $params['page'] ?? null;
            $search = isset($params['search']) ? trim((string)$params['search']) : '';

            return DB::transaction(function () use ($perPage, $page, $search) {
                $query = Respondent::query()
                    ->with('survey_response.survey');

                if ($search !== '') {
                    $driver = DB::getDriverName();
                    $likeOperator = $driver === 'pgsql' ? 'ilike' : 'like';
                    // Search limited to email, age, survey title, and UUID id
                    $query->where(function ($q) use ($search, $likeOperator) {
                        // Email partial match
                        $q->where('email', $likeOperator, "%$search%");

                        // Age: prefer exact match for numeric input; otherwise allow partial text match
                        if (is_numeric($search)) {
                            $q->orWhere('age', '=', (int) $search);
                        } else {
                            // Some DBs permit text comparison; harmless if column is numeric in others it will be ignored
                            $q->orWhere('age', $likeOperator, "%$search%");
                        }

                        // Survey title via relationship
                        $q->orWhereHas('survey_response.survey', function ($sq) use ($search, $likeOperator) {
                            $sq->where('title', $likeOperator, "%$search%");
                        });

                        // Only compare by UUID when the search term is a valid UUID to avoid PG uuid cast errors
                        if (Uuid::isValid($search)) {
                            $q->orWhere('id', '=', $search);
                        }
                    });
                }

                if ($page !== null) {
                    return $query->paginate($perPage, ['*'], 'page', (int)$page);
                }

                return $query->paginate($perPage);
            });
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 500);
        }
    }

    public function resolveModel($survey_respondent)
    {
        if ($survey_respondent instanceof Respondent) {
            return $survey_respondent;
        }
        return Respondent::query()->findOrFail($survey_respondent);
    }

    public function getIfExist($survey_respondent)
    {
        return Respondent::query()->find($survey_respondent);
    }

    public function store()
    {
        return DB::transaction(function () {
            $survey_respondent = new Respondent();
            $survey_respondent->save();
            return $survey_respondent->id;
        });
    }

    public function update(Respondent $respondent, array $params): Respondent
    {
        if (!$respondent->exists) {
            $respondent = Respondent::query()
                ->findOrFail($respondent->id);
        }

        return DB::transaction(function () use ($respondent, $params) {
            $respondent->fill($params);
            $respondent->save();
            return $respondent;
        });
    }

}
