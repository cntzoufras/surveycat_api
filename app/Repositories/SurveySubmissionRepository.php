<?php
    
    namespace App\Repositories;
    
    use App\Models\SurveySubmission;
    
    use Illuminate\Support\Facades\DB;
    
    class SurveySubmissionRepository {
        
        public function index(array $params) {
            try {
                $limit = $params['limit'] ?? 50;
                return DB::transaction(function () use ($limit) {
                    return SurveySubmission::query()->paginate($limit);
                });
            } catch (\Exception $e) {
                throw new \Exception($e, 500);
            }
        }
        
        public function resolveModel($survey_submissions) {
            if ($survey_submissions instanceof SurveySubmission) {
                return $survey_submissions;
            }
            return SurveySubmission::query()->findOrFail($survey_submissions);
        }
        
        public function getIfExist($survey_submissions) {
            return SurveySubmission::query()->find($survey_submissions);
        }
        
        public function update(SurveySubmission $survey_submissions, array $params) {
            return DB::transaction(function () use ($params, $survey_submissions) {
                $survey_submissions->fill($params);
                $survey_submissions->save();
                return $survey_submissions;
            });
        }
        
        public function store(array $params): SurveySubmission {
            return DB::transaction(function () use ($params) {
                $survey_submissions = new SurveySubmission();
                $survey_submissions->fill($params);
                $survey_submissions->save();
                return $survey_submissions;
            });
        }
        
        public function delete(SurveySubmission $survey_submissions) {
            return DB::transaction(function () use ($survey_submissions) {
                $survey_submissions->delete();
                return $survey_submissions;
            });
        }
        
    }