<?php

namespace App\Repositories\Survey;

use App\Models\Survey\SurveyPage;
use Illuminate\Support\Facades\DB;

class SurveyPageRepository {

    public function index(array $params) {

        try {
            $limit = $params['limit'] ?? 10;
            return DB::transaction(function () use ($limit) {
                return SurveyPage::query()->paginate($limit);
            });
        } catch (\Exception $e) {
            throw new \Exception($e, 500);
        }
    }

    public function resolveModel($survey_page) {
        if ($survey_page instanceof SurveyPage) {
            return $survey_page;
        }
        return SurveyPage::query()->findOrFail($survey_page);
    }

    public function getIfExist($survey_page) {
        return SurveyPage::query()->find($survey_page);
    }

    public function update(SurveyPage $survey_page, array $params) {
        return DB::transaction(function () use ($params, $survey_page) {
            $survey_page->fill($params);
            $survey_page->save();
            return $survey_page;
        });
    }

    public function store(array $params): SurveyPage {
        $max_sort_index = SurveyPage::where('survey_id', $params['survey_id'])->max('sort_index');

        $params['sort_index'] = $max_sort_index !== null ? $max_sort_index + 1 : 0;
        return DB::transaction(function () use ($params) {
            $survey_page = new SurveyPage();
            $survey_page->fill($params);
            $survey_page->save();
            return $survey_page;
        });
    }

    public function delete(SurveyPage $survey_page) {
        return DB::transaction(function () use ($survey_page) {
            $survey_id = $survey_page->survey_id;
            $survey_page->delete();
            $this->updateSortIndexes($survey_id);
            return $survey_page;
        });
    }

    public function getSurveyPagesBySurvey($surveyId) {
        try {
            return DB::transaction(function () use ($surveyId) {
                return SurveyPage::query()->where('survey_id', '=', $surveyId)->get();
            });
        } catch (\Exception $e) {
            throw new \Exception($e, 500);
        }
    }

    protected function updateSortIndexes($surveyId) {

        $survey_pages = SurveyPage::where('survey_id', $surveyId)->orderBy('sort_index')->get();

        foreach ($survey_pages as $index => $page) {
            $page->sort_index = $index;
            $page->save();
        }
    }


}