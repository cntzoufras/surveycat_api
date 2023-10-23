<?php
    
    namespace App\Repositories;
    
    use App\Models\SurveyPage;
    
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
            return DB::transaction(function () use ($params) {
                $survey_page = new SurveyPage();
                $survey_page->fill($params);
                $survey_page->save();
                return $survey_page;
            });
        }
        
        public function delete(SurveyPage $survey_page) {
            return DB::transaction(function () use ($survey_page) {
                $survey_page->delete();
                return $survey_page;
            });
        }
        
    }