<?php
    
    namespace App\Repositories;
    
    use App\Models\Question;
    
    use Illuminate\Support\Facades\DB;
    
    class QuestionRepository {
        
        public function index(array $params) {
            try {
                $limit = isset($params['limit']) ? $params['limit'] : 10;
                return DB::transaction(function () use ($limit) {
                    
                    //                    $questions = Question::query()->first();
                    return Question::query()->paginate($limit);
                });
            } catch (\Exception $e) {
                throw new \Exception($e, 500);
            }
        }
        
        public function resolveModel($question) {
            if ($question instanceof Question) {
                return $question;
            }
            return Question::query()->findOrFail($question);
        }
        
        public function getIfExist($question) {
            return Question::query()->find($question);
        }
        
        public function update(Question $question, array $params) {
            return DB::transaction(function () use ($params, $question) {
                $question->fill($params);
                $question->save();
                return $question;
            });
        }
        
        public function store(array $params): Question {
            return DB::transaction(function () use ($params) {
                $question = new Question();
                $question->fill($params);
                $question->save();
                return $question;
            });
        }
        
        public function delete(Question $question) {
            return DB::transaction(function () use ($question) {
                $question->delete();
                return $question;
            });
        }
        
    }