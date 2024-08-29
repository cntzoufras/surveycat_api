<?php

namespace App\Repositories\Survey;

use App\Models\Survey\Survey;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Collection;

class SurveyRepository implements SurveyRepositoryInterface {

    public function index(array $params) {
        try {
            $limit = $params['limit'] ?? 50;
            return DB::transaction(function () use ($limit) {
                return Survey::query()->paginate($limit);
            });
        } catch (\Exception $e) {
            throw new \Exception($e, 500);
        }
    }

    public function resolveModel($surveys) {
        if ($surveys instanceof Survey) {
            return $surveys;
        }
        return Survey::query()->findOrFail($surveys);
    }

    public function getIfExist($survey) {
        return Survey::query()->find($survey);
    }

    public function update(Survey $survey, array $params) {
        return DB::transaction(function () use ($params, $survey) {
            $survey->fill($params);
            $survey->save();
            return $survey;
        });
    }

    public function store(array $params): Survey {
        return DB::transaction(function () use ($params) {
            $survey = new Survey();
            $survey->fill($params);
            $survey->save();
            return $survey;
        });
    }


    public function destroy(Survey $survey) {
        return DB::transaction(function () use ($survey) {
            $survey->delete();
            return $survey;
        });
    }

    /**
     * @throws \Exception
     */
    public function getStockSurveys() {
        try {
            return DB::transaction(function () {
                return Survey::query()->where('is_stock', '=', true)->paginate();
            });
        } catch (\Exception $e) {
            throw new \Exception($e, 500);
        }
    }

    public function getSurveysForUser(string $user_id): Collection {
        return Survey::with(['theme:id,title', 'survey_pages'])
                     ->where('user_id', Auth::id())  // Filter by the logged-in user
                     ->where('is_stock', false)       // Exclude stock surveys
                     ->get();
    }

    public function getSurveysWithThemesAndPages(): Collection {
        return Survey::with(['theme:id,title', 'survey_pages'])
                     ->where('user_id', Auth::id())  // Filter by the logged-in user
                     ->where('is_stock', false)       // Exclude stock surveys
                     ->get();
    }

    public function getSurveyWithDetails($survey_id): Survey {
        return Survey::with([
            'theme:id,title',
            'survey_pages' => function ($query) {
                $query->orderBy('sort_index');
            },
            'survey_category:id,title',
        ])
                     ->where('user_id', Auth::id())
                     ->findOrFail($survey_id);
    }
}