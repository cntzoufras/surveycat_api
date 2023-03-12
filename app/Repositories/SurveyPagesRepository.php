<?php
    
    namespace App\Repositories;
    
    use App\Models\SurveyPages;
    
    use Illuminate\Support\Facades\DB;
    
    class SurveyPagesRepository {
        
        public function index(array $params) {
            try {
                $limit = isset($params['limit']) ? $params['limit'] : 100;
                return DB::transaction(function () use ($limit) {
                    return SurveyPages::query()->paginate($limit);
                });
            } catch (\Exception $e) {
                throw new \Exception($e, 500);
            }
        }
        
        public function resolveModel($survey_pages) {
            if ($survey_pages instanceof SurveyPages) {
                return $survey_pages;
            }
            return SurveyPages::query()->findOrFail($survey_pages);
        }
        
        public function getIfExist($survey_pages) {
            return SurveyPages::query()->find($survey_pages);
        }
        
        public function update(SurveyPages $survey_pages, array $params) {
            return DB::transaction(function () use ($params, $survey_pages) {
                $survey_pages->fill($params);
                $survey_pages->save();
                return $survey_pages;
            });
        }
        
        public function store(array $params): SurveyPages {
            return DB::transaction(function () use ($params) {
                $survey_pages = new SurveyPages();
                $survey_pages->fill($params);
                $survey_pages->save();
                return $survey_pages;
            });
        }
        
        public function delete(SurveyPages $survey_pages) {
            return DB::transaction(function () use ($survey_pages) {
                $survey_pages->delete();
                return $survey_pages;
            });
        }
        
    }