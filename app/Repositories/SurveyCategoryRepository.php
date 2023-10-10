<?php
    
    namespace App\Repositories;
    
    use App\Models\SurveyCategory;
    
    use Illuminate\Support\Facades\DB;
    
    class SurveyCategoryRepository {
        
        public function index(array $params) {
            
            try {
                $limit = isset($params['limit']) ? $params['limit'] : 10;
                return DB::transaction(function () use ($limit) {
                    return SurveyCategory::query()->paginate($limit);
                });
            } catch (\Exception $e) {
                throw new \Exception($e, 500);
            }
        }
        
        public function resolveModel($survey_category) {
            if ($survey_category instanceof SurveyCategory) {
                return $survey_category;
            }
            return SurveyCategory::query()->findOrFail($survey_category);
        }
        
        public function getIfExist($survey_category) {
            return SurveyCategory::query()->find($survey_category);
        }
        
        public function update(SurveyCategory $survey_category, array $params) {
            return DB::transaction(function () use ($params, $survey_category) {
                $survey_category->fill($params);
                $survey_category->save();
                return $survey_category;
            });
        }
        
        public function store(array $params): SurveyCategory {
            return DB::transaction(function () use ($params) {
                $survey_category = new SurveyCategory();
                $survey_category->fill($params);
                $survey_category->save();
                return $survey_category;
            });
        }
        
        public function delete(SurveyCategory $survey_category) {
            return DB::transaction(function () use ($survey_category) {
                $survey_category->delete();
                return $survey_category;
            });
        }
        
    }