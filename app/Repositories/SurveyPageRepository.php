<?php
    
    namespace App\Repositories;
    
    use App\Models\SurveyPage;
    
    use Illuminate\Support\Facades\DB;
    
    class SurveyPageRepository {
        
        public function index(array $params) {
            try {
                $limit = isset($params['limit']) ? $params['limit'] : 100;
                return DB::transaction(function () use ($limit) {
                    return SurveyPage::query()->paginate($limit);
                });
            } catch (\Exception $e) {
                throw new \Exception($e, 500);
            }
        }
        
        public function resolveModel($survey_pages) {
            if ($survey_pages instanceof SurveyPage) {
                return $survey_pages;
            }
            return SurveyPage::query()->findOrFail($survey_pages);
        }
        
        public function getIfExist($survey_pages) {
            return SurveyPage::query()->find($survey_pages);
        }
        
        public function update(SurveyPage $survey_pages, array $params) {
            return DB::transaction(function () use ($params, $survey_pages) {
                $survey_pages->fill($params);
                $survey_pages->save();
                return $survey_pages;
            });
        }
        
        public function store(array $params): SurveyPage {
            return DB::transaction(function () use ($params) {
                $survey_pages = new SurveyPage();
                $survey_pages->fill($params);
                $survey_pages->save();
                return $survey_pages;
            });
        }
        
        public function delete(SurveyPage $survey_pages) {
            return DB::transaction(function () use ($survey_pages) {
                $survey_pages->delete();
                return $survey_pages;
            });
        }
        
    }