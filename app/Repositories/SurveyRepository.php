<?php
    
    namespace App\Repositories;
    
    use App\Models\Survey;
    
    use Illuminate\Support\Facades\DB;
    
    class SurveyRepository {
        
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
        
        public function getIfExist($surveys) {
            return Survey::query()->find($surveys);
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
        
        public function delete(Survey $survey) {
            return DB::transaction(function () use ($survey) {
                $survey->delete();
                return $survey;
            });
        }
        
    }