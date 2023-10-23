<?php
    
    namespace App\Repositories;
    
    use App\Models\SurveyRespondent;
    
    use Illuminate\Support\Facades\DB;
    
    class SurveyRespondentRepository {
        
        public function index(array $params) {
            
            try {
                $limit = $params['limit'] ?? 10;
                return DB::transaction(function () use ($limit) {
                    return SurveyRespondent::query()->paginate($limit);
                });
            } catch (\Exception $e) {
                throw new \Exception($e, 500);
            }
        }
        
        public function resolveModel($survey_respondent) {
            if ($survey_respondent instanceof SurveyRespondent) {
                return $survey_respondent;
            }
            return SurveyRespondent::query()->findOrFail($survey_respondent);
        }
        
        public function getIfExist($survey_respondent) {
            return SurveyRespondent::query()->find($survey_respondent);
        }
        
        public function update(SurveyRespondent $survey_respondent, array $params) {
            return DB::transaction(function () use ($params, $survey_respondent) {
                $survey_respondent->fill($params);
                $survey_respondent->save();
                return $survey_respondent;
            });
        }
        
        public function store(array $params): SurveyRespondent {
            return DB::transaction(function () use ($params) {
                $survey_respondent = new SurveyRespondent();
                $survey_respondent->fill($params);
                $survey_respondent->save();
                return $survey_respondent;
            });
        }
        
        public function delete(SurveyRespondent $survey_respondent) {
            return DB::transaction(function () use ($survey_respondent) {
                $survey_respondent->delete();
                return $survey_respondent;
            });
        }
        
    }