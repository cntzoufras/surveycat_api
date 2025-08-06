<?php

namespace App\Repositories;

use App\Models\Respondent;

use Illuminate\Support\Facades\DB;

class RespondentRepository
{

    public function index(array $params)
    {

        try {
            $limit = $params['limit'] ?? 100000;
            return DB::transaction(function () use ($limit) {
                return Respondent::with('survey_response.survey')->paginate($limit);
            });
        } catch (\Exception $e) {
            throw new \Exception($e, 500);
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
