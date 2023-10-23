<?php
    
    namespace App\Repositories;
    
    use App\Models\SurveyQuestion;
    
    use Illuminate\Support\Facades\DB;
    
    class QuestionRepository {
        
        public function index(array $params) {
            try {
                $limit = $params['limit'] ?? 10;
                return DB::transaction(function () use ($limit) {
                    return SurveyQuestion::query()->paginate($limit);
                });
            } catch (\Exception $e) {
                throw new \Exception($e, 500);
            }
        }
        
        public function resolveModel($question) {
            if ($question instanceof SurveyQuestion) {
                return $question;
            }
            return SurveyQuestion::query()->findOrFail($question);
        }
        
        public function getIfExist($question) {
            return SurveyQuestion::query()->find($question);
        }
        
        public function update(SurveyQuestion $question, array $params) {
            return DB::transaction(function () use ($params, $question) {
                $question->fill($params);
                $question->save();
                return $question;
            });
        }
        
        public function store(array $params): SurveyQuestion {
            return DB::transaction(function () use ($params) {
                $question = new SurveyQuestion();
                $question->fill($params);
                $question->save();
                return $question;
            });
        }
        
        public function delete(SurveyQuestion $question) {
            return DB::transaction(function () use ($question) {
                $question->delete();
                return $question;
            });
        }
        
    }